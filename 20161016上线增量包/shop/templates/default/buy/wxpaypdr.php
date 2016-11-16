<?php defined('InShopNC') or exit('Access Invalid!');?>
 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>weixinpay</title>
        <link rel="stylesheet" type="text/css" href="../templates/default/css/reset.css">
        <link rel="stylesheet" type="text/css" href="../templates/default/css/wxpay_main.css">
        <script src="<?php echo $output['resource_site_url'];?>/js/jquery.js"></script>
    </head>
    <body>

        <div class="payment">
            <div class="wxin_pay"> 
<br/>
                <h3>微信支付</h3>
                <div class="pay_main">
                    <div class="pay_ewm">
                        <img src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($output['code_url']); ?>"> 
                    </div>
                    <div class="pay_ewm_tsh">
                        <p>请使用微信扫一扫</p>
                        <p>扫描二维码支付</p>
                    </div>
                </div>
                <div class="pay_sidebar"><img src="../templates/default/images/payment/phone-bg.png"></div>
            </div>
            <div class="other_pay">
            	<input type="hidden" id="total_fee" name="total_fee" value="<?php echo $output['total_fee']?>">
               <a class="pc-wrap" href="javascript:;" onClick="javascript :history.back(-1);" ><i><</i><strong>选择其他支付方式</strong></a>
            </div>
        </div>
    </body>
</html>

	<script type="text/javascript">

		function GetRequest() {
		    var url = location.search; //获取url中”?”符后的字串
		    var theRequest = new Object();
		    if (url.indexOf("?") != -1) {
		        var str = url.substr(1);
		        strs = str.split("&");
		        for (var i = 0; i < strs.length; i++) {
		            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
		        }
		    }
		    return theRequest;
		}

		var Request = new Object();
	    Request = GetRequest();
	    var pay_sn = Request['pay_sn'];

		function ajax_order(){
			$.ajax({
			url: "/index.php?act=payment&op=is_wxpaypdr",
		        data: {pay_sn: pay_sn,trade_no:'<?php echo $output['trade_no'];?>'},
		        type: "get",
		        dataType: 'json',
		        success: function(result) {
                                if(result.data == '20'){
                                        location.href = "<?php echo $output['shop_site_url'];?>/index.php?act=predeposit&op=pd_log_list";
                                }
			    }
			});
		}
	    //ajax 查询订单是否被支付
		setInterval("ajax_order()",1000)
// 		ajax_order();
	</script>
