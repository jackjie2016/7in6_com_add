<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/

defined('InShopNC',true);

	require_once("./log_.php");
	require_once("./WxPayPubHelper/WxPayPubHelper.php");

  //echo $_SERVER['HTTP_HOST'] ;


  //使用通用通知接口
	$notify = new Notify_pub();
	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

  //获取交易支付id,附加数据,交易结果
  $postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
  $pay_sn = $postObj->out_trade_no;  //可以这样获取XML里面的信息 
  $attach = $postObj->attach;
  $result_code = $postObj->result_code ;

	$notify->saveData($xml);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
 
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        
       
	
	//以log文件形式记录回调信息
	$log_ = new Log_();
	$log_name="./notify_url_new.log";//log文件路径
	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
	 

	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			
		 
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
      
      $log_->log_result($log_name,"【支付成功】:\n".$pay_sn."\n");
      $log_->log_result($log_name,"【支付成功11】:\n".$attach."\n");
      $log_->log_result($log_name,"【支付成功12】:\n".$extra_common_param."\n");
     //$log_->log_result($log_name,"【支付成功12】:\n".$order_list."\n");
        //终于到最后一步了。
        $_GET['act']	= 'payment';
        $_GET['op']	= 'return';
        $_GET['payment_code'] = 'wxpay';
        //获取订单交易类型，一个是商品购买，一个是预存款,这里取返回的附加数据
        $_GET['extra_common_param'] = $attach; 
        //交易号，这里取返回的pay_sn即可
        $_GET['out_trade_no'] = $pay_sn;

        $_GET['result_code'] = $result_code ;
        require_once(dirname(__FILE__).'/../../../index.php');
 
		    
		}
		
		
		
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
	
	    $_GET['act']	= 'payment';
        $_GET['op']	= 'return';
        $_GET['payment_code'] = 'wxpay';
        //获取订单交易类型，一个是商品购买，一个是预存款,这里取返回的附加数据
        $_GET['extra_common_param'] = $attach; 
        //交易号，这里取返回的pay_sn即可
        $_GET['out_trade_no'] = $pay_sn;

        $_GET['result_code'] = $result_code ;
		 
        require_once(dirname(__FILE__).'/../../../index.php');
?>