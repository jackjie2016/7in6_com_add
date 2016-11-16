<?php

/**

 * 我的代金券

 * by shopjl.com 网店技术交流中心 运营版

 */


use Shopnc\Tpl;



defined('InShopNC') or exit('Access Invalid!');



class member_voucherControl extends mobileMemberControl {



	public function __construct() {

		parent::__construct();

	}



    /**

     * 地址列表

     */

    public function voucher_listOp() {

		$model_voucher = Model('voucher');

                $voucher_list = $model_voucher->getMemberVoucherList($this->member_info['member_id'], $_POST['voucher_state'], $this->page);

                $page_count = $model_voucher->gettotalpage();
		
		//$voucher_list['voucher_end_date'] = date("m月d日",$v_info['voucher_end_date']-86400);
		$field = 'voucher_id,voucher_code,voucher_title,voucher_desc,voucher_start_date,voucher_end_date,voucher_price,voucher_limit,voucher_state,voucher_order_id,voucher_store_id,store_name,store_id,store_domain,voucher_t_customimg';
		
		$on = 'voucher.voucher_store_id = store.store_id,voucher.voucher_t_id=voucher_template.voucher_t_id';
		
		$where['voucher_state'] = 2;
		
		$list = model()->table('voucher,store,voucher_template')->field($field)->join('inner,inner')->on($on)->where($where)->order('voucher_id desc')->select();
		
		$used_voucher_code = array();

		$voucher_order = array();

		if (!empty($list)) {

		    foreach ($list as $v) {

		        if ($v['voucher_state'] == 2 && empty($v['voucher_order_id'])) {

		            $used_voucher_code[] = $v['voucher_code'];

		        }

		    }

		}

		$on = 'order_common.order_id = order.order_id';

		$order_list = Model()->table('order_common,order')->field('order_common.order_id as order_id,order_common.voucher_code as voucher_code,order.order_amount as order_amount')->join('inner')->on($on)->where(array('voucher_code'=>array('in',$used_voucher_code)))->select();
                foreach ($order_list as $v) {

                    $voucher_order[$v['voucher_code']] = $v['order_id'];
					
					$condition =array();
					
					$condition['voucher_code'] = $v['voucher_code'];
					
					$update = array();
					
					$update['voucher_order_id'] = $v['order_id'];
					
					model()->table('voucher')->where($condition)->update($update);
					//mode()->table('voucher')->where(array('voucher_code'=>$v['voucher_code']))->update(array('voucher_order_id'=>$v['order_id']));
					
                    //$model->editVoucher(array('voucher_order_id'=>$v['order_id']),array('voucher_code'=>$v['voucher_code']));

                }
		
		//
		
		//$order_list = Model()->table('order_common')->getOrderCommonList(array('voucher_code'=>array('in',$used_voucher_code)),'order_id,voucher_code');
		
        output_data(array('voucher_list' => $voucher_list,'order_list'=>$v), mobile_page($page_count));

    }
	
	/**
	
	*获取代金卷信息
	
	**/

	public function show_voucherOp() {
               
		$model_voucher = Model()->table('voucher');
		
		$model_voucher_template = Model()->table('voucher_template');              
		
		//$vid = $_POST['voucher_id'];
		
		//$uid = $_POST['uid'];
		
		$voucher_code = $_POST['voucher_code'];
		
		$member_id = $this->member_info['member_id'];

		$condition = array();
		
		//$condition['voucher_t_id'] = $vid;
		
		$condition['voucher_code'] = $voucher_code;
		
		//$condition['voucher_owner_id'] = $member_id;
		
		$v_info = $model_voucher->field('*')->where($condition)->find();
		
		$v_info['voucher_start_date'] = date("Y年m月d日",$v_info['voucher_start_date']);
		
		$v_info['voucher_end_date'] = date("m月d日",$v_info['voucher_end_date']-86400);
		
		if($v_info['voucher_owner_id']==$member_id){
			output_data($v_info);
		}else{
			$callback = array();
			$callback['user_id'] = $member_id; 
			$callback['voucher_t_id'] = $v_info['voucher_t_id'];
			output_data($callback);
		}
	
	}

	
	/**
	
	*领取代金卷
	
	**/

    public function get_voucherOp() {
        
		$vid = $_POST['voucher_id'];
                
                $vid = explode("|",$vid);    
		
		$uid = $_POST['uid'];
                
                foreach($vid as $v){  
                    
                    $result=$this->get_voucher1Op($v,$uid);
                    
		}
                
                $call_back = array();
                
                $call_back['state']=1;
                        
               output_data($call_back);
                
                exit;

    }
    
    /*edit by peiyu start 一次领取多张代金券*/
    public function get_voucher1Op($voucher_id,$uid){
        
        $call_back = array();
		
	$model_voucher = Model()->table('voucher');
		
	$model_voucher_template = Model()->table('voucher_template');
        
        //检验是否第一次领取
                
        $start_time = strtotime('2016-07-01');

        $end_time =   strtotime('2016-12-31');

        $member = Model()->table('member')->where("member_id=$uid")->select();    

        if($member[0]['member_time']<$start_time || $member[0]['member_time']>$end_time ){

            $call_back['is_get']=1;

            output_data($call_back);exit;  

        }
        
        $member_id = $this->member_info['member_id'];
        
        $where = array();
		
        $where['voucher_t_id'] = $voucher_id;

        $template_info = $model_voucher_template->field('*')->where($where)->find();
        
        $condition = array();
		
        $condition['voucher_t_id'] = $voucher_id;

        $condition['voucher_owner_id'] = $member_id;

        //检查是否已经领过

        $v_info = $model_voucher->field('*')->where($condition)->find();

        if(count($v_info)>$template_info['voucher_t_eachlimit']&&$template_info['voucher_t_eachlimit']!=0){
                $call_back['geted']=1;
                output_data($call_back);exit;
        }

        if($template_info['voucher_t_start_date']>time()){
                $call_back['start']=1;
                output_data($call_back);exit;
        }

        if($template_info['voucher_t_end_date']<time()){
                $call_back['end']=1;
                output_data($call_back);exit;
        }

        if($template_info['voucher_t_total']<=$template_info['voucher_t_giveout']){
                $call_back['total_zero']=1;
                output_data($call_back);exit;
        }

        if (!$member_name){

            $member_info = Model('member')->getMemberInfoByID($member_id);

            $member_name = $member_info['member_name'];

        }
        
        
        //添加代金券信息

        $insert_arr = array();

        $insert_arr['voucher_code'] = mt_rand(10,99)

		      . sprintf('%010d',time() - 946656000)

		      . sprintf('%03d', (float) microtime() * 1000)

		      . sprintf('%03d', (int) $member_id % 1000);

        $insert_arr['voucher_t_id'] = $template_info['voucher_t_id'];

        $insert_arr['voucher_title'] = $template_info['voucher_t_title'];

        $insert_arr['voucher_desc'] = $template_info['voucher_t_desc'];

        $insert_arr['voucher_start_date'] = $template_info['voucher_t_start_date'];

        $insert_arr['voucher_end_date'] = $template_info['voucher_u_end_date'];
		
		$price = rand($template_info['voucher_t_price']-($template_info['voucher_t_price_max']-$template_info['voucher_t_price']),$template_info['voucher_t_price_max']);
		if($price<$template_info['voucher_t_price']){$price=$template_info['voucher_t_price'];}
		
		if($template_info['is_mod_5']==1 && $template_info['is_random']==1){
			if($price % 5 !=0 ){
				if($price % 5 > 3 ){
					$price = $price + 5 - ($price % 5);
				}else{
					$price = $price - ($price % 5);
				}
			}
		}
	
        $insert_arr['voucher_price'] = $template_info['is_random']!=1?$template_info['voucher_t_price']:$price;

        $insert_arr['voucher_limit'] = $template_info['voucher_t_limit'];

        $insert_arr['voucher_store_id'] = $template_info['voucher_t_store_id'];

        $insert_arr['voucher_state'] = 1;

        $insert_arr['voucher_active_date'] = time();

        $insert_arr['voucher_owner_id'] = $member_id;

        $insert_arr['voucher_owner_name'] = $member_name;
        
        /*edit by peiyu 在vouch表增加字段 start*/
        
        $insert_arr['voucher_t_sc_id'] =  $template_info['voucher_t_sc_id'];
        
        $insert_arr['voucher_t_channel'] = $template_info['voucher_t_channel'];
        
        $insert_arr['voucher_t_kind']   =  $template_info['voucher_t_kind'];
        
        /*edit by peiyu stop*/

        $result=Model()->table('voucher')->insert($insert_arr);
		
        $update=array();

        $update['voucher_t_giveout']=$template_info['voucher_t_giveout'] + 1;

        $result=Model()->table('voucher_template')->where($where)->update($update);
		
        
    }
    /*edit by peiyu stop 一次领取多张代金券*/


	

}

