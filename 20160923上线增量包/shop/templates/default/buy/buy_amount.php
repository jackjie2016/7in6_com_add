<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="ncc-bottom"> <a href="javascript:void(0)" id='submitOrder' class="ncc-btn ncc-btn-acidblue fr"><?php echo $lang['cart_index_submit_order'];?></a> </div>
<script>
function submitNext(){
	if (!SUBMIT_FORM) return;

	if ($('input[name="cart_id[]"]').size() == 0) {
		showDialog('所购商品无效', 'error','','','','','','','','',2);
		return;
	}
    if ($('#address_id').val() == ''){
		showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error','','','','','','','','',2);
		return;
	}
	if ($('#buy_city_id').val() == '') {
		showDialog('正在计算运费,请稍后', 'error','','','','','','','','',2);
		return;
	}
	if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
		showDialog('使用充值卡/预存款支付，需输入支付密码并使用  ', 'error','','','','','','','','',2);
		return;
	}
	if ($('input[name="fcode"]').size() == 1 && $('#fcode_callback').val() != '1') {
		showDialog('请输入并使用F码', 'error','','','','','','','','',2);
		return;
	}

	//1元购 wugangjian 20160922 Ajax判断订单表中是否有1元购商品 START
	// var groupbuy = $('.groupbuy').text();
	var wgj_phone = $('.phone').text();
	var goods_id = $('.wgj_goods_id').text();
	var member_id = $('.wgj_member_id').text();
	var goods_type;

	//split函数不支持多浏览器
	// var allcookies = document.cookie.split("member_id=");
	// var member_id = allcookies[1].split(";")[0];
	// alert(allcookies);return;
    var data = {};

	data.goods_id = goods_id;
	data.member_id = member_id;
    console.log(data);

		$.ajax({

			type:'post',

			url:'/index.php?act=buy&op=wgj_goods_detail2',

			data:data,

			dataType:'json',

			success:function(result){

				console.log(result);
				goods_type = result;
				console.log('20160921 Gentle is the most handsome man !');
				
				if(result == 2){
		    		alert('感谢您的支持,但是1元购专场商品每人只能购买1件哦~非常抱歉');
		    		return false;
		    	}
			}

		});

		if(goods_type == 2){
			return false;
		}

	//1元购 wugangjian 20160922 Ajax判断订单表中是否有1元购商品 END

	SUBMIT_FORM = false;

	$('#order_form').submit();
}
$(function(){
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
        	submitNext();
        	return false;
        }
    });
	$('#submitOrder').on('click',function(){submitNext()});
	calcOrder();
});
</script>