<?php



/**

 * 商品

 *

 * by shopjl.com 网店技术交流中心

 *

 *

 */

//by shopjl.com

//use Shopnc\Tpl;



defined('InShopNC') or exit('Access Invalid!');



class goodsControl extends mobileHomeControl {



    public function __construct() {

        parent::__construct();

		$this->upGoodsOp();
    }

	/**
	    将商品发布时间到期的商品自动上架
	*/
	public function upGoodsOp(){
		$time_now = strtotime(date('Y-m-d H:i:s'));
		$condition['goods_state'] = 0;
		$condition['goods_selltime'] = array('between',array(100,$time_now));
		$update['goods_state'] = 1;
		Model()->table('goods_common')->where($condition)->update($update);
		$goods_id_array = Model()->table('goods_common')->field('goods_id')->where($condition)->order('goods_id desc')->find();
		$condition['goods_id'] = array('in', $goods_id_array);
		Model()->table('goods')->where($condition)->update($update);
	}
    /**

     * 商品列表

     */

    public function goods_listOp() {

        $model_goods = Model('goods');

        $model_search = Model('search');



        //查询条件

        $condition = array();

        if (!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {

            $condition['gc_id'] = $_GET['gc_id'];

        } elseif (!empty($_GET['keyword'])) {

            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');

        }



        //所需字段

        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,goods_virtual_salenum,evaluation_good_star,evaluation_count";



        // 添加3个状态字段

        $fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift';



        //排序方式

        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);



        //优先从全文索引库里查找

        list($indexer_ids, $indexer_count) = $model_search->indexerSearch($_GET, $this->page);

        if (is_array($indexer_ids)) {

            //商品主键搜索

            $goods_list = $model_goods->getGoodsOnlineList(array('goods_id' => array('in', $indexer_ids)), $fieldstr, 0, $order, $this->page, null, false);

            //如果有商品下架等情况，则删除下架商品的搜索索引信息

            if (count($goods_list) != count($indexer_ids)) {

                $model_search->delInvalidGoods($goods_list, $indexer_ids);

            }

            pagecmd('setEachNum', $this->page);

            pagecmd('setTotalNum', $indexer_count);

        } else {
			$goods_list_all = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr);
            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
			//$goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fieldstr, $order, $this->page);
        }
		

        $page_count = $model_goods->gettotalpage();
		
		$goods_count = count($goods_list_all);



        //处理商品列表(抢购、限时折扣、商品图片)

        $goods_list = $this->_goods_list_extend($goods_list);

		$page_info = array();
		
		$page_info = mobile_page($page_count);
		
		$page_info['goods_count'] = $goods_count;

        output_data(array('goods_list' => $goods_list), $page_info);

    }



    /**

     * 商品列表排序方式

     */

    private function _goods_list_order($key, $order) {

        $result = 'is_own_shop desc,goods_id desc';

        if (!empty($key)) {


            switch ($key) {

                //销量

                case '1' :

					$sequence = 'asc';
		
					if ($order == 1) {
		
						$sequence = 'desc';
		
					}

                    $result = 'goods_salenum + goods_virtual_salenum' . ' ' . $sequence;

                    break;

                //浏览量

                case '2' :

					$sequence = 'asc';
		
					if ($order == 1) {
		
						$sequence = 'desc';
		
					}
					
                    $result = 'goods_click' . ' ' . $sequence;

                    break;

                //价格

                case '3' :

					$sequence = 'desc';
		
					if ($order == 1) {
		
						$sequence = 'asc';
		
					}

                    $result = 'goods_price' . ' ' . $sequence;

                    break;

            }

        }

        return $result;

    }



    /**

     * 处理商品列表(抢购、限时折扣、商品图片)

     */

    private function _goods_list_extend($goods_list) {

        //获取商品列表编号数组

        $commonid_array = array();

        $goodsid_array = array();

        foreach ($goods_list as $key => $value) {

            $commonid_array[] = $value['goods_commonid'];

            $goodsid_array[] = $value['goods_id'];

        }



        //促销

        $groupbuy_list = Model('groupbuy')->getGroupbuyListByGoodsCommonIDString(implode(',', $commonid_array));

        $xianshi_list = Model('p_xianshi_goods')->getXianshiGoodsListByGoodsString(implode(',', $goodsid_array));

        foreach ($goods_list as $key => $value) {

            //抢购

            if (isset($groupbuy_list[$value['goods_commonid']])) {

                $goods_list[$key]['goods_price'] = $groupbuy_list[$value['goods_commonid']]['groupbuy_price'];

                $goods_list[$key]['group_flag'] = true;

            } else {

                $goods_list[$key]['group_flag'] = false;

            }



            //限时折扣

            if (isset($xianshi_list[$value['goods_id']]) && !$goods_list[$key]['group_flag']) {

                $goods_list[$key]['goods_price'] = $xianshi_list[$value['goods_id']]['xianshi_price'];

                $goods_list[$key]['xianshi_flag'] = true;

            } else {

                $goods_list[$key]['xianshi_flag'] = false;

            }



            //商品图片url

            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 240, $value['store_id']);


            unset($goods_list[$key]['store_id']);

            unset($goods_list[$key]['goods_commonid']);

            unset($goods_list[$key]['nc_distinct']);

        }



        return $goods_list;

    }


	/* 
	*1元购 wugangjian 20160921 添加字段判断不使用发票和代金券 START 
	*新建function wgj_goods_detailOp
	*/
	public function wgj_goods_detailOp() {
		 $goods_id = $_GET['goods_id'];
		 
		 $model_goods = Model()->table('goods');
		 $goods_detail = $model_goods->where("goods_id=$goods_id")->find();
		 
		 $wgj_goods_promotion_type = $goods_detail['goods_promotion_type'];

		 if (empty($goods_detail)) {

            output_error('商品不存在');

        }
	
		output_data($wgj_goods_promotion_type);
	}
	/* 
	*1元购 wugangjian 20160921 添加字段判断不使用发票和代金券 END 
	*新建function wgj_goods_detailOp
	*/
	 
	 
    /**

     * 商品详细页

     */
	 
	
    public function goods_detailOp() {

        $goods_id = intval($_GET ['goods_id']);



        // 商品详细信息

        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id);

        $lower_limit=$goods_detail['goods_info']['lower_limit'];
        $up_limit=$goods_detail['goods_info']['up_limit'];
        $start_time=$goods_detail['goods_info']['start_time'];
        $end_time=$goods_detail['goods_info']['end_time'];
		$purchase_quantity=$goods_detail['goods_info']['purchase_quantity'];
		
        
        if (empty($goods_detail)) {

            output_error('商品不存在');

        }
		
		/**
        *1元购活动 限制每位用户只能购买唯一一款产品
        *wugangjian 20160910 START
        */
        session_start();
        $buyer_id = $member_id = $_SESSION['member_id'];
        // var_dump($buyer_id);
        $model_order_goods = Model('order_goods');
        $where['buyer_id'] = $buyer_id;
        $wgj_order_goods = $model_order_goods->field('goods_type')->where($where)->select();
        $goods_types = [];
        foreach ($wgj_order_goods as $k => $v) {
            $goods_types[] = $v[goods_type];
        }

        //查询goods表商品
        $wgj_goods_promotion_type = $goods_detail['goods_info']['goods_promotion_type'];

        // 通过order_id关联order_goods表查询order表中order_state数据
        $where3['goods_type'] = 2;
        $where3['buyer_id'] = $buyer_id;
        $wgj_order_id = $model_order_goods->field('order_id')->where($where3)->select();
		$wgj_order_id = array_reverse($wgj_order_id);
        $model_orders = Model()->table('order');
        $where2['order_id'] = $wgj_order_id[0]['order_id'];
        $wgj_order_state = $model_orders->field('order_state')->where($where2)->find();

        if(in_array("2", $goods_types) && $wgj_goods_promotion_type == 1 && $wgj_order_state['order_state'] !== '0'){
            $goods_type = 2;
        }else {
            $goods_type = 1;
        }
            $goods_detail['goods_info']['goods_type']=$goods_type;
            // var_dump(in_array("2", $goods_types));
            // var_dump($wgj_goods_promotion_type);
            // var_dump($wgj_order_state['order_state']);
            // var_dump($goods_type);
            // die;

        // Tpl::output('goods_type', $goods_type);
        /**
        *1元购活动 限制每位用户只能购买唯一一款产品
        *wugangjian 20160910 END
        */



        //新增，拆分重组属性数组

        $array_list = $goods_detail['goods_info']['goods_attr'];

        if ($array_list) {

            $goods_detail['goods_info']['goods_attr'] = array();

            foreach ($array_list as $key => $value) {

                $val = array_values($value);

                $goods_detail['goods_info']['goods_attr'][$key]['key'] = $val[0];

                $goods_detail['goods_info']['goods_attr'][$key]['name'] = $val[1];

            }

        }







        //推荐商品

        $model_store = Model('store');

        $hot_sales = $model_store->getHotSalesList($goods_detail['goods_info']['store_id'], 6);

        $goods_commend_list = array();

        foreach ($hot_sales as $value) {

            $goods_commend = array();

            $goods_commend['goods_id'] = $value['goods_id'];

            $goods_commend['goods_name'] = $value['goods_name'];

            $goods_commend['goods_price'] = $value['goods_price'];

            $goods_commend['goods_image_url'] = cthumb($value['goods_image'], 240);

            $goods_commend_list[] = $goods_commend;
			

        }

        $goods_detail['goods_commend_list'] = $goods_commend_list;

        $store_info = $model_store->getStoreInfoByID($goods_detail['goods_info']['store_id']);

        $goods_detail['store_info']['store_id'] = $store_info['store_id'];

        $goods_detail['store_info']['store_name'] = $store_info['store_name'];

        $goods_detail['store_info']['member_id'] = $store_info['member_id'];

        //显示QQ及旺旺 网店技术交流中心

        $goods_detail['store_info']['store_qq'] = $store_info['store_qq'];

        $goods_detail['store_info']['store_ww'] = $store_info['store_ww'];

        $goods_detail['store_info']['member_name'] = $store_info['member_name'];

        $goods_detail['store_info']['avatar'] = getMemberAvatarForID($store_info['member_id']);



        //商品详细信息处理

        $goods_detail = $this->_goods_detail_extend($goods_detail);

        $goods_detail['lower_limit']=$lower_limit;
        $goods_detail['up_limit']=$up_limit;
        $goods_detail['start_time']=$start_time;
        $goods_detail['end_time']=$end_time;
        output_data($goods_detail);

    }



    /**

     * 商品评价列表

     */

    public function goods_evaluateOp() {

        $goods_id = intval($_GET['goods_id']);



        $condition['geval_goodsid'] = $goods_id;

        $model_evaluate_goods = Model("evaluate_goods");

        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, 10);



        $array_list = $goodsevallist;

        if ($array_list) {

       foreach ($array_list as $key => $value) {

                 $goodsevallist[$key]['geval_frommembername']= $model_evaluate_goods->cut_str($value['geval_frommembername'],1,0).'**'.$model_evaluate_goods->cut_str($value['geval_frommembername'],1,-1);



                $goodsevallist[$key]['geval_addtime'] = date("Y-m-d", $value['geval_addtime']);

                $goodsevallist[$key]['geval_scores'] = $value['geval_scores'] * 20;

                $geval_image = explode(",", $value['geval_image']);

                $goodsevallist[$key]['geval_image'] = $geval_image;

            }

        

        }

           output_data($goodsevallist);

    }











    /**

     * 商品详细信息处理

     */

    private function _goods_detail_extend($goods_detail) {

        //整理商品规格

        unset($goods_detail['spec_list']);

        $goods_detail['spec_list'] = $goods_detail['spec_list_mobile'];

        unset($goods_detail['spec_list_mobile']);



        //整理商品图片

        unset($goods_detail['goods_image']);

        $goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);

        unset($goods_detail['goods_image_mobile']);



        //商品链接

        $goods_detail['goods_info']['goods_url'] = urlShop('goods', 'index', array('goods_id' => $goods_detail['goods_info']['goods_id']));


        //整理数据

        unset($goods_detail['goods_info']['goods_commonid']);

        unset($goods_detail['goods_info']['gc_id']);

        unset($goods_detail['goods_info']['gc_name']);

        unset($goods_detail['goods_info']['store_name']);

        unset($goods_detail['goods_info']['brand_id']);

        unset($goods_detail['goods_info']['brand_name']);

        unset($goods_detail['goods_info']['type_id']);

        unset($goods_detail['goods_info']['goods_image']);

        unset($goods_detail['goods_info']['goods_body']);

        unset($goods_detail['goods_info']['goods_state']);

        unset($goods_detail['goods_info']['goods_stateremark']);

        unset($goods_detail['goods_info']['goods_verify']);

        unset($goods_detail['goods_info']['goods_verifyremark']);

        unset($goods_detail['goods_info']['goods_lock']);

        unset($goods_detail['goods_info']['goods_addtime']);

        unset($goods_detail['goods_info']['goods_edittime']);

        unset($goods_detail['goods_info']['goods_selltime']);

        unset($goods_detail['goods_info']['goods_show']);

        unset($goods_detail['goods_info']['goods_commend']);

        unset($goods_detail['goods_info']['explain']);

        unset($goods_detail['goods_info']['cart']);

        unset($goods_detail['goods_info']['buynow_text']);

        unset($goods_detail['groupbuy_info']);

        unset($goods_detail['xianshi_info']);



        return $goods_detail;

    }



    /**

     * 商品详细页

     */

    public function goods_bodyOp() {

        $goods_id = intval($_GET ['goods_id']);



        $model_goods = Model('goods');



        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');

        $goods_common_info = $model_goods->getGoodeCommonInfoByID($goods_info['goods_commonid']);



        Tpl::output('goods_common_info', $goods_common_info);

        Tpl::showpage('goods_body');

    }



    /**

     * 手机商品详细页

     */

    public function wap_goods_bodyOp() {

        $goods_id = intval($_GET ['goods_id']);



        $model_goods = Model('goods');



        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_id');

        $goods_common_info = $model_goods->getMobileBodyByCommonID($goods_info['goods_commonid']);

        Tpl:output('goods_common_info', $goods_common_info);

        Tpl::showpage('goods_body');

    }



}

