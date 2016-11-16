<?php

/**

 * 购买

 *

 *

 *

 *

 * by shopjl.com 网店技术交流中心 运营版

 */



use Shopnc\Tpl;



defined('InShopNC') or exit('Access Invalid!');



class member_buyControl extends mobileMemberControl {



	public function __construct() {

		parent::__construct();

	}



    /**

     * 购物车、直接购买第一步:选择收获地址和配置方式

     */

    public function buy_step1Op() {

        $cart_id = explode(',', $_POST['cart_id']);



        $logic_buy = logic('buy');



        //得到购买数据

        $result = $logic_buy->buyStep1($cart_id, $_POST['ifcart'], $this->member_info['member_id'], $this->member_info['store_id']);

        if(!$result['state']) {

            output_error($result['msg']);

        } else {

            $result = $result['data'];

        }

        

        //整理数据

        $store_cart_list = array();

        foreach ($result['store_cart_list'] as $key => $value) {

            $store_cart_list[$key]['goods_list'] = $value;

            $store_cart_list[$key]['store_goods_total'] = $result['store_goods_total'][$key];

            if(!empty($result['store_premiums_list'][$key])) {

                $result['store_premiums_list'][$key][0]['premiums'] = true;

                $result['store_premiums_list'][$key][0]['goods_total'] = 0.00;

                $store_cart_list[$key]['goods_list'][] = $result['store_premiums_list'][$key][0];

            }

            $store_cart_list[$key]['store_mansong_rule_list'] = $result['store_mansong_rule_list'][$key];

            $store_cart_list[$key]['store_voucher_list'] = $result['store_voucher_list'][$key];

            if(!empty($result['cancel_calc_sid_list'][$key])) {

                $store_cart_list[$key]['freight'] = '0';

                $store_cart_list[$key]['freight_message'] = $result['cancel_calc_sid_list'][$key]['desc'];

            } else {

                $store_cart_list[$key]['freight'] = '1';

            }
			
			$store_cart_list[$key]['store_free_price'] = $result['store_free_price'][$key];

            $store_cart_list[$key]['store_name'] = $value[0]['store_name'];

        }



        $buy_list = array();

        $buy_list['store_cart_list'] = $store_cart_list;

        $buy_list['freight_hash'] = $result['freight_list'];

        $buy_list['address_info'] = $result['address_info'];

        $buy_list['ifshow_offpay'] = $result['ifshow_offpay'];

        $buy_list['vat_hash'] = $result['vat_hash'];

        $buy_list['inv_info'] = $result['inv_info'];

        $buy_list['available_predeposit'] = $result['available_predeposit'];

        $buy_list['available_rc_balance'] = $result['available_rc_balance'];
		
		$buy_list['member_truename']= $this -> member_info['member_truename'];
		
		$buy_list['member_mobile']= $this -> member_info['member_mobile'];

        output_data($buy_list);

    }



    /**

     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式

     *

     */

    public function buy_step2Op() {

        /**
        *1元购活动 限制每位用户只能购买唯一一款产品
        *wugangjian 20160922 START
        */
        $buyer_id = $_POST['member_id'];
        $goods_id = $_POST['goods_id'];

        $model_order_goods = Model('order_goods');
        $where['buyer_id'] = $buyer_id;
        $wgj_order_goods = $model_order_goods->field('goods_type')->where($where)->select();
        $goods_types = [];
        foreach ($wgj_order_goods as $k => $v) {
            $goods_types[] = $v[goods_type];
        }

        //查询goods表商品
        $model_goods = Model()->table('goods');
        $where_0922['goods_id'] = $goods_id;
        $wgj_model_goods = $model_goods->field('goods_promotion_type')->where($where_0922)->find();
        $wgj_goods_promotion_type = $wgj_model_goods['goods_promotion_type'];

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

        if($goods_type == 2){
            // $result['goods_type'] = $goods_type;
            output_data(array('goods_type' => $goods_type));
            $_POST[] = [];
        }
        // $goods_detail['goods_info']['goods_type']=$goods_type;
        // var_dump(in_array("2", $goods_types));
        // var_dump($wgj_goods_promotion_type);
        // var_dump($wgj_order_state['order_state']);
        // var_dump($goods_type);
        // die;

        /**
        *1元购活动 限制每位用户只能购买唯一一款产品
        *wugangjian 20160922 END
        */

        $param = array();
		
		$param['shipping_type'] = $_POST['shipping_type'];

        $param['ifcart'] = $_POST['ifcart'];

        $param['cart_id'] = explode(',', $_POST['cart_id']);

        $param['address_id'] = $_POST['address_id'];

        $param['vat_hash'] = $_POST['vat_hash'];

        $param['offpay_hash'] = $_POST['offpay_hash'];

        $param['offpay_hash_batch'] = $_POST['offpay_hash_batch'];

        $param['pay_name'] = $_POST['pay_name'];

        $param['invoice_id'] = $_POST['invoice_id'];

		$member_truename = $_POST['member_truename'];
		
		$member_mobile = $_POST['member_mobile'];
		
		if($this->member_info['member_truename']!=$member_truename){
		
			$result = model()->table('member')->where(array('member_id'=>$this->member_info['member_id']))->update(array('member_truename'=>$member_truename));
			if(!$result){output_error("eeee");}
			
		}
		
		if($this->member_info['member_mobile']!=$member_mobile){
		
			$result = model()->table('member')->where(array('member_id'=>$this->member_info['member_id']))->update(array('member_mobile'=>$member_mobile));
			
			if(!$result){output_error("ssss");}
			
		}
		
		
        $voucher_1 = array();
        
        $voucher_2 = array();
        
        $voucher_3 = array();

        $post_voucher = explode(',', $_POST['voucher']);

        
        /*重组手机端代金券 edit by peiyu start*/
        
        /*代金券1*/
       
        if(!empty($post_voucher[0])) {
 

                list($voucher_t_id,$store_id, $voucher_price,$voucher_id) = explode('|', $post_voucher[0]);

                $voucher_1[$store_id]= $post_voucher[0];

        }

        $param['voucher_0'] = $voucher_1;
        
        
        /*代金券2*/
        
        if(!empty($post_voucher[1])) {

                list($voucher_t_id,$store_id, $voucher_price,$voucher_id) = explode('|', $post_voucher[1]);

                $voucher_2[$store_id]= $post_voucher[1];

        }
        
        $param['voucher_1'] = $voucher_2;
        
        
        /*代金券3*/
        
        if(!empty($post_voucher[2])) {

                list($voucher_t_id,$store_id, $voucher_price,$voucher_id) = explode('|', $post_voucher[2]);

                $voucher_3[$store_id]= $post_voucher[2];

        }
        
        $param['voucher_2'] = $voucher_3;



        //手机端暂时不做支付留言，页面内容太多了

        //$param['pay_message'] = json_decode($_POST['pay_message']);

        $param['pd_pay'] = $_POST['pd_pay'];

        $param['rcb_pay'] = $_POST['rcb_pay'];

        $param['password'] = $_POST['password'];

        $param['fcode'] = $_POST['fcode'];

        $param['order_from'] = 2;

        $logic_buy = logic('buy');

        $result = $logic_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email']);

        if(!$result['state']) {

            output_error($result['msg']);

        }

		foreach ($result['data']['order_list'] as $key=>$val){

			$order_id=$key;

		}			

        output_data(array('pay_sn' => $result['data']['pay_sn']));

    }



    /*

     * 添加商品评论

     */

    public function add_evaluateOp(){

    	//print_r($_POST);exit();

    	$model_order = Model('order');

    	$model_store = Model('store');

    	//店铺评论 以及商品评论

    	$model_evaluate_goods = Model('evaluate_goods');

    	$model_evaluate_store = Model('evaluate_store');

    	

    	$order_id = intval($_POST['order_id']);

    	//获取订单信息

    	$order_info = $model_order->getOrderInfo(array('order_id' => $order_id));

    	//判断订单身份$this->member_info['member_id']

    	if($order_info['buyer_id'] != $this->member_info['member_id']) {

    		//showMessage(Language::get('wrong_argument'),'index.php?act=member_order','html','error');

    		output_error('错误');

    	}

    	//订单为'已收货'状态，并且未评论

    	$order_info['evaluate_able'] = $model_order->getOrderOperateState('evaluation',$order_info);

    	if (empty($order_info) || !$order_info['evaluate_able']){

    		//showMessage(Language::get('member_evaluation_order_notexists'),'index.php?act=member_order','html','error');

    		output_error('订单无效');

    	}

    	

    	//查询店铺信息

    	$store_info = $model_store->getStoreInfoByID($order_info['store_id']);

    	if(empty($store_info)){

    		//showMessage(Language::get('member_evaluation_store_notexists'),'index.php?act=member_order','html','error');

    		output_error('店铺不存在');

    	}

    	

    	//获取订单商品

    	$order_goods = $model_order->getOrderGoodsList(array('order_id'=>$order_id));

    	if(empty($order_goods)){

    		//showMessage(Language::get('member_evaluation_order_notexists'),'index.php?act=member_order','html','error');

    		output_error('商品不存在');

    	}

    	

    	$evaluate_goods_array = array();

    	$goodsid_array = array();

    	foreach ($order_goods as $value){

    		//如果未评分，默认为5分

    		$evaluate_score = intval($_POST['goods_'.$value['goods_id'].'_score']);

    		if($evaluate_score <= 0 || $evaluate_score > 5) {

    			$evaluate_score = 5;

    		}

    		//默认评语

    		$evaluate_comment = $_POST['goods_'.$value['goods_id'].'_comment'];

    		if(empty($evaluate_comment)) {

    			$evaluate_comment = '不错哦';

    		}

    	

    		$evaluate_goods_info = array();

    		$evaluate_goods_info['geval_orderid'] = $order_id;

    		$evaluate_goods_info['geval_orderno'] = $order_info['order_sn'];

    		$evaluate_goods_info['geval_ordergoodsid'] = $value['rec_id'];

    		$evaluate_goods_info['geval_goodsid'] = $value['goods_id'];

    		$evaluate_goods_info['geval_goodsname'] = $value['goods_name'];

    		$evaluate_goods_info['geval_goodsprice'] = $value['goods_price'];

    		$evaluate_goods_info['geval_goodsimage'] = $value['goods_image'];

    		$evaluate_goods_info['geval_scores'] = $evaluate_score;

    		$evaluate_goods_info['geval_content'] = $evaluate_comment;

//     		$evaluate_goods_info['geval_isanonymous'] = $_POST['anony']?1:0;

    		$evaluate_goods_info['geval_isanonymous'] = 0;

    		$evaluate_goods_info['geval_addtime'] = TIMESTAMP;

    		$evaluate_goods_info['geval_storeid'] = $store_info['store_id'];

    		$evaluate_goods_info['geval_storename'] = $store_info['store_name'];

//     		$evaluate_goods_info['geval_frommemberid'] = $_SESSION['member_id'];

//     		$evaluate_goods_info['geval_frommembername'] = $_SESSION['member_name'];

			$evaluate_goods_info['geval_frommemberid'] = $this->member_info['member_id'];

			$evaluate_goods_info['geval_frommembername'] = $this->member_info['member_name'];

    	

    		$evaluate_goods_array[] = $evaluate_goods_info;

    	

    		$goodsid_array[] = $value['goods_id'];

    	}

    	$model_evaluate_goods->addEvaluateGoodsArray($evaluate_goods_array, $goodsid_array);

    	

    	$store_desccredit = intval($_POST['store_desccredit']);

    	if($store_desccredit <= 0 || $store_desccredit > 5) {

    		$store_desccredit= 5;

    	}

    	$store_servicecredit = intval($_POST['store_servicecredit']);

    	if($store_servicecredit <= 0 || $store_servicecredit > 5) {

    		$store_servicecredit = 5;

    	}

    	$store_deliverycredit = intval($_POST['store_deliverycredit']);

    	if($store_deliverycredit <= 0 || $store_deliverycredit > 5) {

    		$store_deliverycredit = 5;

    	}

    	//             //添加店铺评价

    	if (!$store_info['is_own_shop']) {

    		$evaluate_store_info = array();

    		$evaluate_store_info['seval_orderid'] = $order_id;

    		$evaluate_store_info['seval_orderno'] = $order_info['order_sn'];

    		$evaluate_store_info['seval_addtime'] = time();

    		$evaluate_store_info['seval_storeid'] = $store_info['store_id'];

    		$evaluate_store_info['seval_storename'] = $store_info['store_name'];

    		$evaluate_store_info['seval_memberid'] = $this->member_info['member_id'];

    		$evaluate_store_info['seval_membername'] = $this->member_info['member_name'];

    		$evaluate_store_info['seval_desccredit'] = $store_desccredit;

    		$evaluate_store_info['seval_servicecredit'] = $store_servicecredit;

    		$evaluate_store_info['seval_deliverycredit'] = $store_deliverycredit;

    	}

    	$model_evaluate_store->addEvaluateStore($evaluate_store_info);

    	

    	//更新订单信息并记录订单日志

    	$state = $model_order->editOrder(array('evaluation_state'=>1), array('order_id' => $order_id));

    	$model_order->editOrderCommon(array('evaluation_time'=>TIMESTAMP), array('order_id' => $order_id));

    	if ($state){

    		$data = array();

    		$data['order_id'] = $order_id;

    		$data['log_role'] = 'buyer';

    		$data['log_msg'] = L('order_log_eval');

    		$model_order->addOrderLog($data);

    	}

    	

    	//添加会员抵币

    	if (C('points_isuse') == 1){

    		$points_model = Model('points');

    		$points_model->savePointsLog('comments',array('pl_memberid'=>$this->member_info['member_id'],'pl_membername'=>$this->member_info['member_name']));

    	}

    	//添加会员经验值

    	Model('exppoints')->saveExppointsLog('comments',array('exp_memberid'=>$this->member_info['member_id'],'exp_membername'=>$this->member_info['member_name']));;



    	output_data('1');

    }

    /**

     * 验证密码

     */

    public function check_passwordOp() {

        if(empty($_POST['password'])) {

            output_error('参数错误');

        }



        $model_member = Model('member');



        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        if($member_info['member_paypwd'] == md5($_POST['password'])) {

            output_data('1');

        } else {

            output_error('密码错误');

        }

    }



    /**

     * 更换收货地址

     */

    public function change_addressOp() {

        $logic_buy = Logic('buy');



        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);

        if(!empty($data) && $data['state'] == 'success' ) {

            output_data($data);

        } else {

            output_error('地址修改失败');

        }

        



        $logic_buy = Logic('buy');

        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);

    }

    

    //设置默认地址

    function setDefaultAddressOp(){

    	$update_addr = Model('address');

    	$address_id = $_POST['address_id'];

    	$member_id = $this->member_info['member_id'];

    	

    	$data = array();

    	$update = array();

    	$ishave = $update_addr->getDefaultAddressInfo(array('member_id'=>$member_id));

    	if(!empty($ishave)){

    		if($ishave['address_id'] != $address_id){

    			$update['is_default'] = 0;

    			$data = $update_addr->editAddress($update,array('member_id'=>$member_id,'address_id'=>$ishave['address_id']));

    		}

    	}

    	$update['is_default'] = 1;

    	$data = $update_addr->editAddress($update,array('member_id'=>$member_id,'address_id'=>$address_id));

    	

    	

    	if(!empty($data) /*&& $data['state'] == 'success'*/ ) {

    		output_data($data);

    	} else {

    		output_error('地址修改失败');

    	}

    }

    



	/*

                   *微信三级返利插件start 

	 * 查询我的钱包中的收支明细

	 */

	function getUserMoneyChangeOp(){



		$member_id = $this->member_info['member_id'];

		

		$model_member_money_change = Model('member');

		$moneychangearr = array();

		$moneychangeinfo = array();

		

		$moneychangeinfoarr = array();

		

		$member_nick = array();

		$moneychangearr = $model_member_money_change->getMemberMoneyChangeByID($member_id);

	 

                                 for ($i=0;$i<count($moneychangearr);$i++){

                                       $moneychangeinfoarr[$i]['thismonth']=$moneychangearr[$i]['thismonth'];

                                       $moneychangeinfoarr[$i]['changedesc']=$moneychangearr[$i]['changedesc'];

                                       $moneychangeinfoarr[$i]['money']=$moneychangearr[$i]['money'];



                                       $member_nick = $model_member_money_change->getMemberInfo(array('member_id'=>$moneychangearr[$i]['buyuid']));

                                       if($member_nick['nickname'] != null){

			   $moneychangeinfoarr[$i]['nickname'] = $member_nick['nickname'];

		      }else {

			   $moneychangeinfoarr[$i]['nickname'] = $member_nick['member_name'];

			}

                                   }

                

		 output_data(array('voucher_list' => $moneychangeinfoarr), mobile_page($page_count));

 		 

	}

        /*微信三级返利插件end*/



}



