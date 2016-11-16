<?php
/**
 * 商品评价模型
 *
 * by shopjl 网店技术交流中心  www.shopjl.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');
class evaluate_goodsModel extends Model {

    public function __construct(){
        parent::__construct('evaluate_goods');
    }

	/**
	 * 查询评价列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getEvaluateGoodsList($condition, $page = null, $order = 'geval_id desc', $field = '*') {
		
		 
 
     /*    $list = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $list; */
	   $param	= array();
		$param['table']	= 'member,evaluate_goods';
		$param['join_type']	= 'inner join';
		$param['field']	= 'member.member_avatar,evaluate_goods.*';
		$param['join_on']	= array('member.member_id=evaluate_goods.geval_frommemberid');
		$param['where']	= $this->_condition($condition);
		$param['order']	= $order;
		return Db::select($param,$page);    
    }


/**
	 * 构造检索条件
	 *
	 * @param array $condition 检索条件
	 * @return string 字符串类型的返回结果
	 */
	public function _condition($condition){
		$condition_str = ' ';
		
		if ($condition['geval_ordergoodscommonid'] != ''){
			$condition_str .= " evaluate_goods.geval_ordergoodscommonid = '". $condition['geval_ordergoodscommonid'] ."'";
		}
	 
	 
		
		return $condition_str;
	}
    /**
     * 隐藏字符为*号
     */
    public function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
    {
        if($code == 'UTF-8')
        {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
            return join('', array_slice($t_string[0], $start, $sublen));
        }
        else
        {
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = '';
            for($i=0; $i< $strlen; $i++)
            {
                if($i>=$start && $i< ($start+$sublen))
                {
                    if(ord(substr($string, $i, 1))>129)
                    {
                        $tmpstr.= substr($string, $i, 2);
                    }
                    else
                    {
                        $tmpstr.= substr($string, $i, 1);
                    }
                }
                if(ord(substr($string, $i, 1))>129) $i++;
            }
            //if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
            return $tmpstr;
        }
    }



    /**
     * 根据编号查询商品评价 
     */
    public function getEvaluateGoodsInfoByID($geval_id, $store_id = 0) {
        if(intval($geval_id) <= 0) {
            return null;
        }

        $info = $this->where(array('geval_id' => $geval_id))->find();

        if($store_id > 0 && intval($info['geval_storeid']) !== $store_id) {
            return null;
        } else {
            return $info;
        }
    }

    /**
     * 根据商品编号查询商品评价信息 
     */
    public function getEvaluateGoodsInfoByGoodsID($goods_id) {
        $prefix = 'goods_evaluation';
        $info = rcache($goods_id, $prefix);
        /*edit by peiyu start查询goods_commonid*/
        $goods = Model("goods");
        $info1  = $goods->where("goods_id=$goods_id")->select();
        $goods_commonid = $info1[0]['goods_commonid'];
        /*edit by peiyu stop查询goods_commonid*/
        if(empty($info)) {
            $info = array();
            $count_array = $this->field('count(*) as count,geval_scores')->where(array('geval_ordergoodscommonid' => $goods_commonid))->group('geval_scores')->key(geval_scores)->select();
            $star1 = intval($count_array['1']['count']);
            $star2 = intval($count_array['2']['count']);
            $star3 = intval($count_array['3']['count']);
            $star4 = intval($count_array['4']['count']);
            $star5 = intval($count_array['5']['count']);
            $info['good'] = $star4 + $star5;
            $info['normal'] = $star2 + $star3;
            $info['bad'] = $star1;
            $info['all'] = $star1 + $star2 + $star3 + $star4 + $star5;
            if(intval($info['all']) > 0) {
                $info['good_percent'] = intval($info['good'] / $info['all'] * 100);
                $info['normal_percent'] = intval($info['normal'] / $info['all'] * 100);
                $info['bad_percent'] = intval($info['bad'] / $info['all'] * 100);
                $info['good_star'] = ceil($info['good'] / $info['all'] * 5);
                $info['star_average'] = ceil(($star1 + $star2 * 2 + $star3 * 3 + $star4 * 4 + $star5 * 5) / $info['all']);
            } else {
                $info['good_percent'] = 100;
                $info['normal_percent'] = 0;
                $info['bad_percent'] = 0;
                $info['good_star'] = 5;
                $info['star_average'] = 5;
            }

            //更新商品表好评星级和评论数
            $model_goods = Model('goods');
            $update = array();
            $update['evaluation_good_star'] = $info['star_average'];
            $update['evaluation_count'] = $info['all'];
            /*eidt by peiyu start更新同一commonid的评论数和评论星级*/
            
            $arr = Model('goods')->where(array('goods_commonid'=>$goods_commonid))->select();
            
            foreach($arr as $v){
                
               $model_goods->editGoodsById($update, $v['goods_id']); 
               
            }
            
            /*edit by peiyu stop*/
           $model_goods->editGoodsById($update, $goods_id);
            wcache($goods_id, $info, $prefix);
        }
        return $info;
    }

    /**
     * 根据团购编号查询商品评价信息 
     */
    public function getEvaluateGoodsInfoByCommonidID($goods_commonid) {
        $prefix = 'goods_common_evaluation';
        $info = rcache($goods_commonid, $prefix);
        if(empty($info)) {
            $info = array();
            $info['good_percent'] = 100;
            $info['normal_percent'] = 0;
            $info['bad_percent'] = 0;
            $info['good_star'] = 5;
            $info['all'] = 0;
            $info['good'] = 0;
            $info['normal'] = 0;
            $info['bad'] = 0;
            
            $condition = array();
            $condition['goods_commonid'] = $goods_commonid;
            $goods_list = Model('goods')->getGoodsList($condition, 'goods_id');
            if (!empty($goods_list)) {
                $goodsid_array = array();
                foreach ($goods_list as $value) {
                    $goodsid_array[] = $value['goods_id'];
                }
                $good = $this->where(array('geval_goodsid'=>array('in' ,$goodsid_array),'geval_scores' => array('in', '4,5')))->count();
                $info['good'] = $good;
                $normal = $this->where(array('geval_goodsid'=>array('in' ,$goodsid_array),'geval_scores' => array('in', '2,3')))->count();
                $info['normal'] = $normal; 
                $bad = $this->where(array('geval_goodsid'=>array('in' ,$goodsid_array),'geval_scores' => array('in', '1')))->count();
                $info['bad'] = $bad; 
                $info['all'] = $info['good'] + $info['normal'] + $info['bad']; 
                if(intval($info['all']) > 0) {
                    $info['good_percent'] = intval($info['good'] / $info['all'] * 100);
                    $info['normal_percent'] = intval($info['normal'] / $info['all'] * 100);
                    $info['bad_percent'] = intval($info['bad'] / $info['all'] * 100);
                    $info['good_star'] = ceil($info['good'] / $info['all'] * 5);
                }
            }
            wcache($goods_commonid, $info, $prefix, 24*60); // 缓存周期1天。
        }
        return $info;
    }

    /**
     * 批量添加商品评价
     * 
     * @param array $param
     * @param array $goodsid_array 商品id数组，更新缓存使用
     * @return boolean
     */
    public function addEvaluateGoodsArray($param, $goodsid_array) {
        $result = $this->insertAll($param);
        // 删除商品评价缓存
        if ($result && !empty($goodsid_array)) {
            foreach ($goodsid_array as $goods_id) {
                dcache($goods_id, 'goods_evaluation');
            }
        }
        return $result;
    }

    /**
     * 更新商品评价
     * 
     * 现在此方法只是编辑晒单，不需要更新缓存
     * 如果使用此方法修改大星星数量请根据goods_id删除缓存
     * 例：dcache($goods_id, 'goods_evaluation');
     */
    public function editEvaluateGoods($update, $condition) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除商品评价
     */
    public function delEvaluateGoods($condition) {
        return $this->where($condition)->delete();
    }
}
