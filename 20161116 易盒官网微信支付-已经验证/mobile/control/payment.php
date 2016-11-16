<?php
/**
 * 支付回调
 *
 *
 *
 *

 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */

use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class paymentControl extends mobileHomeControl{

    private $payment_code;

	public function __construct() {
		parent::__construct();

        $this->payment_code = $_GET['payment_code'];
	}

    public function returnopenidOp(){
        $payment_api = $this->_get_payment_api();
        if($this->payment_code != 'wxpay'){
            output_error('支付参数异常');
            die;
        }

        $payment_api->getopenid();

    }

    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                
     
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
			}
        } else {
			//验证失败
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
		}

        Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {
        // 恢复框架编码的post值
        $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getNotifyInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                if($this->payment_code == 'wxpay'){
                    echo $callback_info['returnXml'];
                    die;
                }else{
                    echo 'success';die;
                }

            }
		}

        //验证失败

        if($this->payment_code == 'wxpay'){
            echo '<xml><return_code><!--[CDATA[FAIL]]--></return_code></xml>';
            die;
        }else{
            echo "fail";die;
        }
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        
        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no) {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('|', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
                $order_type = 'v';
            } else {
                $order_type = 'r';
            }
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $this->payment_code, $order_list, $trade_no);

        } elseif ($order_type == 'v') {
        	$result = $logic_payment->getVrOrderInfo($out_trade_no);
	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {
	            return array('state'=>true);
	        }
	        $result = $logic_payment->updateVrOrder($out_trade_no, $this->payment_code, $result['data'], $trade_no);
        }

        return $result;
    }



    public function  log_result($file,$word) 
    {
        $text = "执行日期：".strftime("%Y-%m-%d %H:%M:%S",time())."\r\n".$word."\r\n";
        file_put_contents($file, $text,FILE_APPEND);
    }
    /**
     * 微信支付异步通知
     */
    public function notify_weixinOp(){		
	//require_once dirname(__FILE__).'/../api/payment/wxpay/wxpay.php';       		
	require_once dirname(__FILE__).'/../api/payment/wxpay/Utils.class.php';	
	require_once dirname(__FILE__).'/../api/payment/wxpay/Utils.class.php';	
	require_once dirname(__FILE__).'/../api/payment/wxpay/config/config.php';
	require_once dirname(__FILE__).'/../api/payment/wxpay/RequestHandler.class.php';
	require_once dirname(__FILE__).'/../api/payment/wxpay/ClientResponseHandler.class.php';	
	require_once dirname(__FILE__).'/../api/payment/wxpay/PayHttpClient.class.php';			
	$xml = file_get_contents('php://input');	
	print_r($xml);  
	$this->resHandler->setContent($xml);
	
	//var_dump($this->resHandler->setContent($xml));    
    $this->resHandler->setKey($this->cfg->C('key'));    
    if($this->resHandler->isTenpaySign()){   
	if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){	
	echo $this->resHandler->getParameter('status');			
	$pay_result=$this->resHandler->getParameter('pay_result');		
	if($pay_result=='0'){			
	$transaction_id=$this->resHandler->getParameter('transaction_id');		
	$out_trade_no=$this->resHandler->getParameter('out_trade_no');			
	$logic_payment = Logic('payment');			
	$result = $logic_payment->getRealOrderInfo($out_trade_no);
	
	$order_list = $result['data']['order_list'];			
	echo $order_pay_info = $result['data'];					
	$result = $logic_payment->updateRealOrder($out_trade_no, 'wxpay', $order_list, $transaction_id);	
	}				
	echo '3';			
	// 11;			
	//更改订单状态       
	Utils::dataRecodes('接口回调收到通知参数',$this->resHandler->getAllParameters());    
	echo 'success';
	
    exit();    
	}else{		
	echo '4';        
	echo 'failure';		
	exit();				      
	}     
	}else{    
	echo 'failure';    
    }
    }
   /**
     * 处理微信支付成功订单
     */
 
    public function handleOp(){
  
        $pay_sn = $_GET['pay_sn'];
        $where['pay_sn'] = $pay_sn;
        $orderObj = Model('order');
        $vrorderObj = Model('vr_order');
     
        //判断订单类型
        if (strlen($pay_sn) == 16){
		 
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
	            $data_log['log_orderstate'] = 20;
	            $orderObj->addOrderLog($data_log);
			 	 $this->pay_searchOp($pay_sn);
	        }
    	}elseif (strlen($pay_sn) == 18){
    		//虚拟订单
    		$model_vr_order = Model('vr_order');
    		$info = $vrorderObj->getOrderInfo(array('pay_sn'=>$pay_sn));
    		if ($info['order_state'] == 10){
    			$data['payment_time'] = $_GET['time_end'];               //支付时间
    			// $data['threeOrderId'] = $_GET['transaction_id'];      //微信订单号
    			$data['order_state'] = '20';                                     //已支付状态
    			$vrorderObj->editOrder($data,$where);
    			
    			//发放兑换码
    			$insert = $model_vr_order->addOrderCode($info);
    			
    			//发送兑换码到手机
    			$param = array('order_id'=>$info['order_id'],'buyer_id'=>$info['buyer_id'],'buyer_phone'=>$info['buyer_phone']);
    			QueueClient::push('sendVrCode', $param);
    			
    			//添加订单日志
    			$data_log['order_id'] = $info['order_id'];
    			$data_log['log_role'] = 'buyer';
    			$data_log['log_user'] = $info['buyer_name'];
    			$data_log['log_msg'] = '收到了货款 ( 支付平台交易号 : '.$_GET['transaction_id'].' )';
    			$data_log['log_orderstate'] = 20;
    			$orderObj->addOrderLog($data_log);
				$this->pay_searchOp($pay_sn);
    		}
    	}
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
}
