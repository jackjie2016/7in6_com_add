<?php
/**
 * 
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class wxpay{

    private $pay_result;
    private $order_type ;
    private $payment;
    private $order;

	
    public function __construct($payment_info,$order_info){
    	$this->wxpay($payment_info,$order_info);
    }
    public function wxpay($payment_info = array(),$order_info = array()){
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;
    	}
    }
	
	public function submitRefund($payment_info,$order_info){
		//print_r($payment_info);
		//print_r($order_info);exit;
		include_once("WxPayPubHelper/WxPayPubHelper.php");
	
		//输入需退款的订单号
		if (!isset($order_info['order_list']['0']['order_sn']) || !isset($order_info["api_pay_amount"]) || $payment_info["payment_code"]!="wxpay")
		{
			echo "不是微信支付接口";
			exit();
			$out_trade_no = " ";
			$refund_fee = "1";
		}else{
			$out_trade_no = $order_info['order_list']['0']['order_sn'];
			//$refund_fee = 100 * $order_info["api_pay_amount"];
			//商户退款单号，商户自定义，此处仅作举例
			//$out_refund_no = "$out_trade_no"."$time_stamp";
			//总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
			$total_fee = 100*$order_info['order_amount'];

			$out_refund_no=$order_info['outOrderNo'];
			if(count($order_info['refundAmount'])){
				$refund_fee=$order_info['refundAmount']*100;
			}else{
				$refund_fee=$order_info['order_amount']*100;
			}
			//使用退款接口
			$refund = new Refund_pub();
			//设置必填参数
			//appid已填,商户无需重复填写
			//mch_id已填,商户无需重复填写
			//noncestr已填,商户无需重复填写
			//sign已填,商户无需重复填写
			$refund->setParameter("out_trade_no","$out_trade_no");//商户订单号
			$refund->setParameter("out_refund_no","$out_refund_no");//商户退款单号
			$refund->setParameter("total_fee","$total_fee");//总金额
			$refund->setParameter("refund_fee","$refund_fee");//退款金额
			$refund->setParameter("op_user_id",WxPayConf_pub::MCHID);//操作员
			//非必填参数，商户可根据实际情况选填
			//$refund->setParameter("sub_mch_id","XXXX");//子商户号 
			//$refund->setParameter("device_info","XXXX");//设备号 
			//$refund->setParameter("transaction_id","XXXX");//微信订单号
			
			//调用结果
			$refundResult = $refund->getResult();
			
			//商户根据实际情况设置相应的处理流程,此处仅作举例
			if ($refundResult["return_code"] == "FAIL") {
				echo "通信出错：".$refundResult['return_msg']."<br>";
			}
			else{
				echo "业务结果：".$refundResult['result_code']."<br>";
				echo "错误代码：".$refundResult['err_code']."<br>";
				echo "错误代码描述：".$refundResult['err_code_des']."<br>";
				echo "公众账号ID：".$refundResult['appid']."<br>";
				echo "商户号：".$refundResult['mch_id']."<br>";
				echo "子商户号：".$refundResult['sub_mch_id']."<br>";
				echo "设备号：".$refundResult['device_info']."<br>";
				echo "签名：".$refundResult['sign']."<br>";
				echo "微信订单号：".$refundResult['transaction_id']."<br>";
				echo "商户订单号：".$refundResult['out_trade_no']."<br>";
				echo "商户退款单号：".$refundResult['out_refund_no']."<br>";
				echo "微信退款单号：".$refundResult['refund_idrefund_id']."<br>";
				echo "退款渠道：".$refundResult['refund_channel']."<br>";
				echo "退款金额：".$refundResult['refund_fee']."<br>";
				echo "现金券退款金额：".$refundResult['coupon_refund_fee']."<br>";
				return json_encode(array('status'=>200,'msg'=>'ok,退款成功!','data'=>$refundResult));
			}
		}
	}
	/**
	 * 获取支付表单
	 *
	 * @param 
	 * @return array
	 */
	public function get_payurl(){
		//echo '111111';  
    //将商品名称 ，商品价格(变为分)以get方式传到下面这个页面里面。。
    //在下面页面里使用这两个变量
    //然后生成二维码
     //print_r($this->order);
     //exit ;
    
     

     $out_trade_no = $this->order['pay_sn']; 
     $total_fee = ($this->order['pay_amount'])*100; 
      //附加数据,这里设置为order_type,分为商品购买和预存款充值
     $attach = $this->order['order_type'];  
 if($this->order['order_type']=='real_order')
     {
        $body = $this->order['goods_name']; 
		$reqUrl = "index.php?act=buy&op=wxpay&pay_sn=$out_trade_no";
     }else{
        $body = "预存款充值"; 
		$reqUrl = "index.php?act=buy&op=wxpaypdr&pay_sn=$out_trade_no";
     }
    return $reqUrl;
		
	}

  /*
  *返回验证

  */


  public function return_verify(){
    //根据交易结果，为pay_result和order_type赋值，并返回true .
    if($_GET['result_code']=='SUCCESS')
    {
       $this->pay_result = true ;
       $this->order_type = $_GET['extra_common_param'] ;
       return true ;   
    }else{
       return false ;  
    }
     
	}
	
	/**
	 * 取得订单支付状态，成功或失败
	 *
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
	   return $this->pay_result;
	}

    
  public function __get($name){
	    return $this->$name;
	}
	
	
	
}
