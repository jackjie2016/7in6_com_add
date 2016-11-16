<?php

/**

 * 支付入口

 *

 *

 **by 网店技术交流中心 www.s-con.hk*/





defined('InShopNC') or exit('Access Invalid!');



class paymentControl extends BaseHomeControl{



    public function __construct() {

        //向前兼容

        $_GET['extra_common_param'] = str_replace(array('predeposit','product_buy'),array('pd_order','real_order'),$_GET['extra_common_param']);

        $_POST['extra_common_param'] = str_replace(array('predeposit','product_buy'),array('pd_order','real_order'),$_POST['extra_common_param']);

    }

    

    /**

     * 招商入住支付

     */

    public function store_joinin_orderOp(){

    	//支付订单号

    	$pay_sn = $_GET['pay_sn'];

    	

    	//支付方式代码

    	$payment_code = $_GET['payment_code'];

    	

    	//支付金额

    	$paying_amount = $_GET['paying_amount'];

    	

    	//

    	$store_joinin = $_GET['store_joinin'];

    	

    	//跳转地址

    	if ($store_joinin == 0){

    		$url = 'index.php?act=store_joinin';

    	}else{

    		$url = 'index.php?act=store_joinin_c2c';

    	}

    	//验证支付订单号格式

    	if(!preg_match('/^\d{16}$/',$pay_sn)){

    		showMessage('参数错误','','html','error');

    	}

    	

    	$logic_payment = Logic('payment');

    	

    	$result = $logic_payment->getPaymentInfo($payment_code);

    	if(!$result['state']) {

    		showMessage($result['msg'], $url, 'html', 'error');

    	}

    	$payment_info = $result['data'];

    	//print_r($payment_info);exit();

    	//计算所需支付金额等支付单信息

    	//$result = $logic_payment->getRealOrderInfo($pay_sn, $_SESSION['member_id']);

    	

    	$result = $logic_payment->getStoreJoininOrderInfo($pay_sn, $_SESSION['member_id'], $store_joinin);

    	if(!$result['state']) {

    		showMessage($result['msg'], $url, 'html', 'error');

    	}

    

    	if ($result['data']['api_pay_state'] || empty($result['data']['api_pay_amount'])) {

            showMessage('该订单不需要支付', $url, 'html', 'error');

        }

    	//转到第三方API支付

    	$this->_api_pay($result['data'], $payment_info);

    }

    

    

	/**

	 * 实物商品订单

	 */

	public function real_orderOp(){

	    $pay_sn = $_POST['pay_sn'];

            $payment_code = $_POST['payment_code'];

            $url = 'index.php?act=member_order';



        if(!preg_match('/^\d{16}$/',$pay_sn)){

            showMessage('参数错误','','html','error');

        }



        $logic_payment = Logic('payment');

        $result = $logic_payment->getPaymentInfo($payment_code);    

        if(!$result['state']) {

            showMessage($result['msg'], $url, 'html', 'error');

        }

        $payment_info = $result['data'];
 

        //计算所需支付金额等支付单信息

        $result = $logic_payment->getRealOrderInfo($pay_sn, $_SESSION['member_id']);

        if(!$result['state']) {

            showMessage($result['msg'], $url, 'html', 'error');

        }



        if ($result['data']['api_pay_state'] || empty($result['data']['api_pay_amount'])) {

            showMessage('该订单不需要支付', $url, 'html', 'error');

        }



        //转到第三方API支付

        $this->_api_pay($result['data'], $payment_info);

	}



	/**

	 * 虚拟商品购买

	 */

	public function vr_orderOp(){

	    $order_sn = $_POST['order_sn'];

	    $payment_code = $_POST['payment_code'];

	    $url = 'index.php?act=member_vr_order';



	    if(!preg_match('/^\d{16}$/',$order_sn)){

            showMessage('参数错误','','html','error');

        }



        $logic_payment = Logic('payment');

        $result = $logic_payment->getPaymentInfo($payment_code);

        if(!$result['state']) {

            showMessage($result['msg'], $url, 'html', 'error');

        }

        $payment_info = $result['data'];



        //计算所需支付金额等支付单信息

        $result = $logic_payment->getVrOrderInfo($order_sn, $_SESSION['member_id']);

        if(!$result['state']) {

            showMessage($result['msg'], $url, 'html', 'error');

        }



        if ($result['data']['order_state'] != ORDER_STATE_NEW || empty($result['data']['api_pay_amount'])) {

            showMessage('该订单不需要支付', $url, 'html', 'error');

        }



        //转到第三方API支付

        $this->_api_pay($result['data'], $payment_info);

	}



	/**

	 * 预存款充值

	 */

	public function pd_orderOp(){

	    $pdr_sn = $_POST['pdr_sn'];

	    $payment_code = $_POST['payment_code'];

	    $url = 'index.php?act=predeposit';



	    if(!preg_match('/^\d{16}$/',$pdr_sn)){

	        showMessage('参数错误',$url,'html','error');

	    }



	    $logic_payment = Logic('payment');

	    $result = $logic_payment->getPaymentInfo($payment_code);

	    if(!$result['state']) {

	        showMessage($result['msg'], $url, 'html', 'error');

	    }

	    $payment_info = $result['data'];



        $result = $logic_payment->getPdOrderInfo($pdr_sn,$_SESSION['member_id']);

        if(!$result['state']) {

            showMessage($result['msg'], $url, 'html', 'error');

        }

        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {

            showMessage('该充值单不需要支付', $url, 'html', 'error');

        }



	    //转到第三方API支付

	    $this->_api_pay($result['data'], $payment_info);

	}



	/**

	 * 第三方在线支付接口

	 *

	 */

	private function _api_pay($order_info, $payment_info) {
		

    	$payment_api = new $payment_info['payment_code']($payment_info,$order_info);

    	if($payment_info['payment_code'] == 'chinabank') {

    		$payment_api->submit();
			
    	} else {

            @header("Location: ".$payment_api->get_payurl());

    	}

    	exit();

	}



	/**

	 * 通知处理(支付宝异步通知和网银在线自动对账)

	 *

	 */

	public function notifyOp(){

        switch ($_GET['payment_code']) {

            case 'alipay':

                $success = 'success'; $fail = 'fail'; break;

            case 'chinabank':

                $success = 'ok'; $fail = 'error'; break;

            default: 

                exit();

        }



        $order_type = $_POST['extra_common_param'];

        $out_trade_no = $_POST['out_trade_no'];

        $trade_no = $_POST['trade_no'];



		//参数判断

		if(!preg_match('/^\d{16}$/',$out_trade_no)) exit($fail);



		$model_pd = Model('predeposit');

		$logic_payment = Logic('payment');



		if ($order_type == 'real_order') {



		    $result = $logic_payment->getRealOrderInfo($out_trade_no);

		    if (intval($result['data']['api_pay_state'])) {

		        exit($success);

		    }

		    $order_list = $result['data']['order_list'];



	    } elseif ($order_type == 'vr_order'){



	        $result = $logic_payment->getVrOrderInfo($out_trade_no);

	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {

	            exit($success);

	        }



		} elseif ($order_type == 'pd_order') {



		    $result = $logic_payment->getPdOrderInfo($out_trade_no);

		    if ($result['data']['pdr_payment_state'] == 1) {

		        exit($success);

		    }



		}else if ($order_type == 'store_order'){

			$result = $logic_payment->getStoreJoininOrderInfo($out_trade_no);

			if ($result['data']['order_state'] == 1) {

				exit($success);

			}

		}else if ($order_type == 'store_order_c2c'){

			$result = $logic_payment->getStoreJoininOrderInfo($out_trade_no);

			if ($result['data']['order_state'] == 1) {

				exit($success);

			}

		} else {

		    exit();

		}

		$order_pay_info = $result['data'];



		//取得支付方式

		$result = $logic_payment->getPaymentInfo($_GET['payment_code']);

		if (!$result['state']) {

		    exit($fail);

		}

		$payment_info = $result['data'];



		//创建支付接口对象

		$payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);



		//对进入的参数进行远程数据判断

		$verify = $payment_api->notify_verify();

		if (!$verify) {

		    exit($fail);

		}



        //购买商品

		if ($order_type == 'real_order') {

            $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);

		} elseif($order_type == 'vr_order'){

		    $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);

		} elseif ($order_type == 'pd_order') {

		    $result = $logic_payment->updatePdOrder($out_trade_no,$trade_no,$payment_info,$order_pay_info);

		} else if ($order_type == 'store_order'){

		    $result = $this->updateStoreJoininOrderOp($out_trade_no);

		} else if ($order_type == 'store_order_c2c'){

	    	$result = $this->updateStoreJoininOrderOp($out_trade_no);

	    }


        $this->pay_searchOp($out_trade_no);//提交到第三方
		exit($result['state'] ? $success : $fail);

	}

	/**

	 * 检查入驻商家是否已经支付入驻费用

	 */

	public function checkPayOp(){

		$condition = array();

		$condition['order_sn'] = $_POST['order_sn'];

		$condition['order_state'] = 1;

		$model_store_joinin = Model('store_joinin');

		$joinin_detail = $model_store_joinin->getOne($condition);

		echo json_encode($joinin_detail);

	}

	/*

	 * 扫码支付 更改订单状态

	 * 

	 */

	public function weixin_updateOp(){

		//得到支付单号

		$orderObj = Model('order');

		$vrorderObj = Model('vr_order');

		

		$pay_sn = $_GET['extra_common_param'];

		$where['pay_sn'] = $pay_sn;

		

		$info = $orderObj->getOrderInfo($where);

		if($info['order_state'] == '10'){

			$data['payment_time'] = $_GET['time_end'];               //支付时间

			// $data['threeOrderId'] = $notify->data['transaction_id'];      //微信订单号

			$data['order_state'] = '20';                                     //已支付状态

			$orderObj->editOrder($data,$where);                              //更新订单状态

		

			//添加订单日志

			$data_log['order_id'] = $info['order_id'];

			$data_log['log_role'] = 'buyer';

			$data_log['log_user'] = $info['buyer_name'];

			$data_log['log_msg'] = '收到了货款 ( 支付平台交易号 : '.$_GET['transaction_id'].' )';

// 			$data_log['log_msg'] = SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$pay_sn;

			$data_log['log_orderstate'] = 20;

			$orderObj->addOrderLog($data_log);

			 

		}

		redirect(SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$pay_sn.'&pay_amount='.ncPriceFormat($_GET['total_fee']));

		

	}
        /*edit by peiyu start 关于同不订单的一些组装*/  
        public function pay_searchOp($pay_sn){
                
                $model_order = Model('order');
              
		        $where['pay_sn'] = $pay_sn;
             
                $order_info = $model_order->getOrderInfo($where,array('order_goods','order_common'));
                //重组发送的数组
                $order_post =array();
                $order_post['sp_statusSucc'] = 20;
                $order_post['sp_statusFail'] = '';
                $order_post['sp_orderNum'] = $order_info['order_sn'];
                $order_post['sp_addressee'] = $order_info['extend_order_common']['reciver_name'];
                $order_post['sp_address']   =  $order_info['extend_order_common']['reciver_info']['address'];
                $order_post['sp_mobile']   =   $order_info['extend_order_common']['reciver_info']['phone'];
                $goods_info = $order_info['extend_order_goods'];
                //重组订单信息
                $str = '';
                foreach($goods_info as $v){    
                        $str.=(string)$v['goods_id'].'*';
                        $str.=(string)$v['goods_num'].'*';
                        $str.=(string)$v['goods_pay_price'].'*';
                        $str.=(string)($v['goods_pay_price']*$v['goods_num']).'|';
                }
                $order_post['sp_goods']   =   substr($str,0,-1);
                $order_post['sp_orderPayTime']   =  $order_info['payment_time'];
                $order_post['sp_orderRremarks']   =  substr($str,0,-1);
                $order_post['sp_goods']   =   substr($str,0,-1);
                $order_post['sp_orderRremarks']   = $order_info['extend_order_common']['order_message'];
                $str = '';
                //重组发票信息
                foreach ($order_info ['extend_order_common']['invoice_info']as $key => $value) {
                    $str.='['.$key.']'.$value;
                }
                $order_post['sp_invoiceInfo']   =  $str;
                $order_post['sp_orderFare']   =  $order_info ['shipping_fee'];
                //后期修改（需要加积分）
                $order_post['sp_orderDiscount']   = $order_info ['extend_order_common']['voucher_price'];
                //运送方式的传递
                switch($order_info ['extend_order_common']['shipping_type']){
                    case 1:
                         $order_post['sp_transType']   =  '快递';
                    break;
                    case 2:
                         $order_post['sp_transType']   =  '自提';
                    break;
                }
                //组装secret
                $secret = 'meinong88';
                foreach($order_post as $k =>$v){
                    $secret.=$v;
                }
                $secret = md5($secret);
                $order_post['secret']   = $secret;
                //调用接口
				
                $url = 'http://118.178.230.163/oa/orderAndStatusApi.do';
                $res = request_post($url, $order_post);    
                //判断订单是否同步成功
                $obj = json_decode($res);
             
                $order_info_string=json_encode($condition);
             
                //写入日志
                if($obj->code==1){
                    $dir = dirname(__FILE__) . '/../api/interface/log/';
                    $name='';
                    $name.=substr(Date("Y"),2,4).Date("m").'.txt';
                    $path = $dir.$name;
                    $content = date('y-m-d h:i:s',time()).'订单编号为'.$obj->sp_orderNum."同步到工单失败 \r\npay_sn:$pay_sn\r\order_id:$order_id\r\ncondition:$order_info_string";
                    w_log($path,$content);  
                    w_log($path,json_encode($order_post));  
                }else{
				    $dir = dirname(__FILE__) . '/../api/interface/log/';
                    $name='';
                    $name.=substr(Date("Y"),2,4).Date("m").'_1.txt';
                    $path = $dir.$name;
                    $content = date('y-m-d h:i:s',time()).'订单编号为'.$obj->sp_orderNum."同步到工单成功 \r\npay_sn:$pay_sn\r\n$ss\r\n$order_info_string";
                    w_log($path,$content);  
                    w_log($path,json_encode($order_post));
					}
        }
        /*edit by peiyu stop*/
        /*预付款的相关代码*/
        public function is_wxpaypdrOp(){
		
	  require_once(dirname(__FILE__) . '/../api/payment/wxpay/lib/WxPay.Api.php');
          require_once(dirname(__FILE__) . '/../api/payment/wxpay/WxPay.NativePay.php');
	  
	  $trade_no=$_GET['trade_no'];
	  $input= new WxPayOrderQuery();
	  $input->SetOut_trade_no($trade_no);
	  $result = WxPayApi::orderQuery($input);
	   $model_pdobj = Model('predeposit');
		$pay_sn = $_GET['pay_sn'];
		$where['pdr_sn'] = $pay_sn;
	  if($result['trade_state']=='SUCCESS'){
		$update=array('pdr_payment_state'=>1,'pdr_payment_time'=>time(),'pdr_payment_code'=>'wxpay','pdr_payment_name'=>'微信','pdr_trade_sn'=>$result['transaction_id']);
		$model_pdobj->editPdRecharge($update,$where);
		$data['member_id']=$_SESSION['member_id'];
		$data['member_name']=$_SESSION['member_name'];
		$data['pdr_sn']=$_GET['pay_sn'];
		$find_amount=$model_pdobj->getPdRechargeInfo($where,'pdr_amount');//获取充值金额
		$data['amount']=$find_amount['pdr_amount'];	 
		$model_pdobj->changePd('recharge',$data);//更新预存款金额
                $model_pdobj->commit();
		echo json_encode(array('data'=>20));
		 
	  }
   else if($result['trade_state']=='CLOSED'){
	
		$pay_sn = $_GET['pay_sn'];
		$where['pay_sn'] = $pay_sn;
        $update=array('order_state'=>0);
		$orderObj->editOrder($update,$where);
		 
		echo json_encode(array('data'=>0));
	  }
	  else{
	     
			echo json_encode(array('data'=>10));
	  }
    }
	

	//微信扫码支付  ajax异步请求判断是否支付

/*  	public function is_wxpayOp(){
            require_once(dirname(__FILE__) . '/../api/payment/wxpay/lib/WxPay.Api.php');
            require_once(dirname(__FILE__) . '/../api/payment/wxpay/WxPay.NativePay.php');	  
	    $pay_sn = $_GET['pay_sn'];
            $trade_no = $_GET['trade_no'];
	    $input= new WxPayOrderQuery();
	    $input->SetOut_trade_no($trade_no );
	    $result = WxPayApi::orderQuery($input);
	    $orderObj = Model('order');
	    $pay_sn = $_GET['pay_sn'];
	    $where['pay_sn'] = $pay_sn;	
	    if($result['trade_state']=='SUCCESS'){
                $update=array('order_state'=>20,'payment_time'=>time(),'payment_code'=>'wxpay');
		$orderObj->editOrder($update,$where);
                //先根据支付单号查询订单主键
                $this->pay_searchOp($pay_sn);
		echo json_encode(array('data'=>20));
	  }else if($result['trade_state']=='CLOSED'){
		$pay_sn = $_GET['pay_sn'];
		$where['pay_sn'] = $pay_sn;
                $update=array('order_state'=>0);
		$orderObj->editOrder($update,$where); 
		echo json_encode(array('data'=>0));
	  }
	  else{
                $info = $orderObj->getOrderInfo($where);
		echo json_encode(array('data'=>$info['order_state']));
            }
	}
  */
	

		 public function is_wxpayOp(){

		$orderObj = Model('order');

		$pay_sn = $_GET['pay_sn'];

		$where['pay_sn'] = $pay_sn;

		

		$info = $orderObj->getOrderInfo($where);



		echo json_encode(array('data'=>$info['order_state']));

		

	} 

	/**

	 * 支付接口返回

	 *

	 */

	public function returnOp(){
		
		 //微信支付回调 2016-11-15
		 require_once(dirname(__FILE__) . '/../api/payment/wxpay/log_.php');
	   	$log_ = new Log_();
	    $log_name=dirname(__FILE__) . "/../api/payment/wxpay/return_new.log";//log文件路径
	    $log_->log_result($log_name,"【支付订单信息变更】:\n".$_GET['extra_common_param']."\n");
 //微信支付回调 2016-11-15

	    $order_type = $_GET['extra_common_param'];

		if ($order_type == 'real_order') {

		    $act = 'member_order';

		} elseif($order_type == 'vr_order') {

			$act = 'member_vr_order';

		} elseif($order_type == 'pd_order') {

		    $act = 'predeposit';

		} elseif ($order_type == 'store_order'){

			$act = 'store_joinin';

		} elseif ($order_type == 'store_order_c2c'){

			$act = 'store_joinin_c2c';

		}else {

		    exit();

		}



		$out_trade_no = $_GET['out_trade_no'];

		$trade_no = $_GET['trade_no'];

		$url = SHOP_SITE_URL.'/index.php?act='.$act;



		//对外部交易编号进行非空判断

		if(!preg_match('/^\d{16}$/',$out_trade_no)) {

		    showMessage('参数错误',$url,'','html','error');

		}

		$logic_payment = Logic('payment');



		if ($order_type == 'real_order') {



		    $result = $logic_payment->getRealOrderInfo($out_trade_no);

		    if(!$result['state']) {

		        showMessage($result['msg'], $url, 'html', 'error');

		    }

		    if ($result['data']['api_pay_state']) {

		        $payment_state = 'success';

		    }

		    $order_list = $result['data']['order_list'];



	    }elseif ($order_type == 'vr_order') {



	        $result = $logic_payment->getVrOrderInfo($out_trade_no);

	        if(!$result['state']) {

	            showMessage($result['msg'], $url, 'html', 'error');

	        }

	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {

	            $payment_state = 'success';

	        }



		} elseif ($order_type == 'pd_order') {



		    $result = $logic_payment->getPdOrderInfo($out_trade_no);

		    if(!$result['state']) {

		        showMessage($result['msg'], $url, 'html', 'error');

		    }

		    if ($result['data']['pdr_payment_state'] == 1) {

		        $payment_state = 'success';

		    }

		}else if ($order_type == 'store_order'){

			$result = $logic_payment->getStoreJoininOrderInfo($out_trade_no);

			if ($result['data']['order_state'] == 1) {

				$payment_state = 'success';

			}

		}else if ($order_type == 'store_order_c2c'){

			$result = $logic_payment->getStoreJoininOrderInfo($out_trade_no);

			if ($result['data']['order_state'] == 1) {

				$payment_state = 'success';

			}

		}

		$order_pay_info = $result['data'];

		$api_pay_amount = $result['data']['api_pay_amount'];



		if ($payment_state != 'success') {

		    //取得支付方式

		    $result = $logic_payment->getPaymentInfo($_GET['payment_code']);

		    if (!$result['state']) {

		        showMessage($result['msg'],$url,'html','error');

		    }

		    $payment_info = $result['data'];

		    //创建支付接口对象

		    $payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);



		    //返回参数判断

		    $verify = $payment_api->return_verify();

		    if(!$verify) {

		        showMessage('支付数据验证失败',$url,'html','error');

		    }

		    

		    //取得支付结果

		    $pay_result	= $payment_api->getPayResult($_GET);

		    if (!$pay_result) {

		        showMessage('非常抱歉，您的订单支付没有成功，请您后尝试',$url,'html','error');

		    }


        
            //更改订单支付状态

		    if ($order_type == 'real_order') {

		        $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);

		    } else if($order_type == 'vr_order') {

		        $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);

		    } else if ($order_type == 'pd_order') {

		        $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);

		    } else if ($order_type == 'store_order'){

		    	$result = $this->updateStoreJoininOrderOp($out_trade_no);

		    } else if ($order_type == 'store_order_c2c'){

		    	$result = $this->updateStoreJoininOrderOp($out_trade_no);

		    }

		    if (!$result['state']) {

		        showMessage('支付状态更新失败',$url,'html','error');

		    }

		}


        $this->pay_searchOp($out_trade_no);//提交到第三方
						
						
		//支付成功后跳转

		if ($order_type == 'real_order') {

		    $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$out_trade_no.'&pay_amount='.ncPriceFormat($api_pay_amount);

		} elseif ($order_type == 'vr_order') {

		    $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy_virtual&op=pay_ok&order_sn='.$out_trade_no.'&order_id='.$order_pay_info['order_id'].'&order_amount='.ncPriceFormat($api_pay_amount);

		} elseif ($order_type == 'pd_order') {

		    $pay_ok_url = SHOP_SITE_URL.'/index.php?act=predeposit';

		}elseif ($order_type == 'store_order'){

			$pay_ok_url = SHOP_SITE_URL.'/index.php?act=store_joinin';

		}elseif ($order_type == 'store_order_c2c'){

			$pay_ok_url = SHOP_SITE_URL.'/index.php?act=store_joinin_c2c&op=pay';

		}

        if ($payment_info['payment_code'] == 'tenpay') {

            showMessage('',$pay_ok_url,'tenpay');

        } else {

            redirect($pay_ok_url);

        }

	}

	

	public function updateStoreJoininOrderOp($out_trade_no){

		$condition = array();

		$condition['order_sn'] = $out_trade_no;

		$condition['order_state'] = 0;

		$update = array();

		$update['order_state'] = 1;

		$model_store_joinin = Model('store_joinin');

		try {

			$model_store_joinin->beginTransaction();

			$state=$model_store_joinin->modify($update, $condition);

			if (!$state) {

				throw new Exception('更新订单状态失败！');

			}

			$model_store_joinin->commit();

			return callback(true);

		} catch (Exception $e) {

			$model_store_joinin->rollback();

			return callback(false,$e->getMessage());

		}

	}

	/*退款入口

	 *

	 *

	 *by ptx

	 */

	public function refundOp(){
                
                header("Content-Type: text/html; charset=UTF-8");

		$model_refund=Model('refund_return');

		$model_order = Model('order');

		$condition['refund_id'] = intval($_GET['refund_id']);

		$refund_list = $model_refund->getRefundList($condition);

		$refund=$refund_list['0'];



		if (chksubmit()) {

				if ($refund['refund_state'] != '2') {//检查状态,防止页面刷新不及时造成数据错误

					showMessage(Language::get('nc_common_save_fail'));

				}

				$order_id = $refund['order_id'];

				//new

				$order=$model_order->getOrderInfo(array('order_id'=>$refund['order_id'],'order_sn'=>$refund['order_sn']));



				//退款信息

				$data=array();

				if($goods_id=='0'){

					$data['refundAmount']='';															//全额退款

				}else{

					$data['refundAmount']=$refund['refund_amount'];										//部分退款

				}

					$data['refundReason']=$refund['reason_info'].$refund['buyer_message'];				//退款原因简要说明

					$data['outOrderNo']	 =$refund['refund_sn'];										    //外部退款号

					$data['tradeNo']   	 =$order['order_sn'];											//支付订单号

					$data['order_amount']=$order['order_amount'];

					
              
				if($order['payment_code']=='wxpay'){
 
					$this->wxpay_RefundOp($data,$order['pay_sn']);

				}else{

					exit('暂不支持其它退款');

				}



			}		

	}

	

	/*微信Pay退款请求

	 *

	 *by ptx

	 *

	 *

	*/

	public function wxpay_RefundOp($data,$pay_sn){

		if(!preg_match('/^\d{16}$/',$pay_sn)){

			exit('付款单号错误');

		}

		$payment_code = 'wxpay';

        $url = 'index.php?act=member_order';



        $logic_payment = Logic('payment');

        $result = $logic_payment->getPaymentInfo($payment_code);

        if(!$result['state']) {

            showMessage($result['msg'], $url, 'html', 'error');

        }

        $payment_info = $result['data'];

		//显示订单商品信息

        //计算所需支付金额等支付单信息

        $result = $logic_payment->getRealOrderInfo($pay_sn);

        if(!$result['state']) {

			exit('无法找到订单');

        }

		$order_info=$result['data'];

		foreach($data as $key=>$val){

			$order_info[$key]=$val;

		}

        if (($result['data']['api_pay_state']!='1')&&(empty($result['data']['api_pay_amount']))) {

			exit('该订单未支付');

        }

		foreach($result['data']['order_list'] as $key=>$order){

			$res=null;

			$condition['order_id']=$order['order_id'];

			$res = Model('order')->getOrderGoodsList($condition);

			$result['data']['order_list'][$key]['goods_list']=$res;

		}

		

                //转到第三方API支付		
  
		$payment_api = new $payment_info['payment_code']($payment_info,$order_info);

		$result=$payment_api->submitRefund($payment_info,$order_info);

		//支付返回

		echo $refund=$this->refund_returnOp($result);

		

     }	

	/**

	 * 易汇通退款接口返回

	 *

	 */

	public function refund_returnOp($json){

		$data=json_decode($json,true);

		$date=$data['data'];



		$refund_sn		=  $date['out_refund_no'];
		
		if($date['result_code']=="SUCCESS"){
			$refund_code    = 0;
		}

		

		//对外部交易编号进行非空判断

		if(!preg_match('/^\d{18}$/',$refund_sn)) {

			$refund_array = array();

			$refund_array['admin_time'] = time();

			$refund_array['refund_state'] = '5'; //状态:1为处理中,2为待管理员处理,3为已完成

			$refund_array['admin_message'] = '参数错误'.$refund_sn;

		}

		$logic_payment = Logic('payment');

		

		//取得支付结果

		if($refund_code!= 0){

			$refund_array = array();

			$refund_array['admin_time'] = time();

			$refund_array['refund_state'] = '4';//状态:1为处理中,2为待管理员处理,3为已完成

			$refund_array['admin_message'] = $data['msg'];

		}else{

			$refund_array = array();

			$refund_array['admin_time'] = time();

			$refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成

			$refund_array['admin_message'] = $data['msg'];		

		}

			$model_refund = Model('refund_return');

			$condition = array();

			$condition['refund_sn'] = $refund_sn;

			$refund_list = $model_refund->getRefundList($condition);

			$refund = $refund_list[0];

			$state = $model_refund->editOrderRefund($refund);

                        /*edit by peiyu start 同步状态到工单系统*/
                        $where = array();
                        $where['order_id']=$refund['order_id'];
                        $res = $model_refund->table('order')->where($where)->select();
                        $order_post =array();
                        $order_post['sp_statusSucc'] = 0;
                        $order_post['sp_statusFail'] = '203';
                        $order_post['sp_orderNum'] = $res[0]['order_sn'] ;
                        $order_post['sp_addressee'] = '';
                        $order_post['sp_address']   =  '';
                        $order_post['sp_mobile']   =  '';
                        $order_post['sp_goods']   =   '';
                        $order_post['sp_orderPayTime']   =  '';
                        $order_post['sp_orderRremarks']   =  '';
                        $order_post['sp_goods']   =   '';
                        $order_post['sp_orderRremarks']   =  '';
                        $order_post['sp_invoiceInfo']   =  '';
                        $order_post['sp_orderFare']   =  '';
                         //后期修改（需要加积分）
                        $order_post['sp_orderDiscount']   = '';
                        $order_post['sp_transType']   =  '';
                        //组装secret
                        $secret = 'meinong88';
                        foreach($order_post as $k =>$v){
                             $secret.=$v;
                        }
                        $secret = md5($secret);
                        $order_post['secret']   = $secret;
                        $url = 'http://118.178.230.163/oa/orderAndStatusApi.do';
                        $res = request_post($url, $order_post);    
                        //判断订单是否同步成功
                        $obj = json_decode($res);
                        //写入日志
                        if($obj->code==1){
                            $dir = '../shop/api/interface/log/';
                            $name='';
                            $name.=substr(Date("Y"),2,4).Date("m").'.txt';
                            $path = $dir.$name;
                            $content = date('y-m-d h:i:s',time()).'订单编号为'.$obj->sp_orderNum."财务审核退款同步到工单失败 \r\n";
                            w_log($path,$content);
                        }
                                    
                                /*eidt by peiyu stop*/
                        

			if ($state) {

				$model_refund->editRefundReturn($condition, $refund_array);

				if($refund_array['refund_state']=='3'){
                                
                                

				echo '成功！';

				header('location:'.ADMIN_SITE_URL.'/index.php?act=refund&op=refund_all');

				// 发送买家消息

				$param = array();

				$param['code'] = 'refund_return_notice';

				$param['member_id'] = $refund['buyer_id'];

				$param['param'] = array(

					'refund_url' => urlShop('member_refund', 'view', array('refund_id' => $refund['refund_id'])),

					'refund_sn' => $refund['refund_sn']

				);

				QueueClient::push('sendMemberMsg', $param);

				$this->log('退款确认，退款编号'.$refund['refund_sn']);

				}else{

					return $refund_array['admin_message'];

				}

			} else {

				return('无此退款单信息');

			}

	}

	/**

	 * 退款处理(易汇通异步处理)

	 *

	 */

	public function refund_notifyOp(){

		//print_r($_POST);



		$tradeNo		=$_POST['tradeNo'];

		$refundNo		=$_POST['orderNo'];

		$executeStatus	=$_POST['executeStatus'];

		$message		=$_POST['message'];

		if ($executeStatus=='false') {

			$refund_array = array();

			$refund_array['admin_time'] = time();

			$refund_array['refund_state'] = '5';//状态:1为处理中,2为待管理员处理,3为已完成

			$refund_array['admin_message'] = $message;			

		}else if($executeStatus=='true'){	

			$refund_array = array();

			$refund_array['admin_time'] = time();

			$refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成

			$refund_array['admin_message'] = $message;

		}

			$model_refund = Model('refund_return');

			$condition = array();

			$condition['refund_sn'] = intval($refundNo);

			$refund_list = $model_refund->getRefundList($condition);

			$refund = $refund_list[0];

			

			$state = $model_refund->editOrderRefund($refund);

			if ($state) {

				$model_refund->editRefundReturn($condition, $refund_array);



				// 发送买家消息

				$param = array();

				$param['code'] = 'refund_return_notice';

				$param['member_id'] = $refund['buyer_id'];

				$param['param'] = array(

					'refund_url' => urlShop('member_refund', 'view', array('refund_id' => $refund['refund_id'])),

					'refund_sn' => $refund['refund_sn']

				);

				QueueClient::push('sendMemberMsg', $param);



				echo 'success';

			} else {

				echo 'failed';

			}		   			

		

	}	

}