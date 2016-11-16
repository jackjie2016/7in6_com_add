<?php defined('InShopNC') or exit('Access Invalid!');?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<center class="top-full-ad"><?php echo loadadv(1047);?></center>



<!-- 新的右边工具条 -->
<div class="toolbar">
	<div class="d_open">
		<span>点<br>击<br>展<br>开</span>
	</div>
	<!--主要工具条-->
	<div class="main_panel" id="js_mainPanel">
		<ul class="bar-center">

			<li class="login">
				<i>登录</i>
				<div></div>
			</li>
			<li class="main-btn cart">
				<i></i>
				<span class="text">购物车</span>
				<span class="cart-num"></span>
			</li>
			<li class="main-btn asset">
				<i>我的财产</i>
				<div class="tooltip"><em class="triangle"></em>我的财产</div>
			</li>
			<li class="main-btn favorite">
				<i>我的收藏夹</i>
				<div class="tooltip"><em class="triangle"></em>我的收藏夹</div>
			</li>
			<li class="main-btn histroy">
			  <i>我看过的</i>
			  <div class="tooltip"><em class="triangle"></em>我看过的</div>
			</li>
			<li></li>
		</ul>
		<ul class="bar-bottom">
			<li class="qrcode">
				<i>手机淘厂网</i>
				<div>
					<img height="150" width="150" src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logowx'];?>">
					<p>手机上易盒购物更便捷</p>
					<em class="triangle"></em>
				</div>
				
			</li>
			<li class="service">
				<!--<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3281288645&site=qq&menu=yes"><i>聊天</i></a>--> 
                                <a target="_blank" href="http://amos.alicdn.com/msg.aw?v=2&amp;uid=meinongebox:包装君&amp;site=cnalichn&amp;s=1&amp;charset=UTF-8" title="google不支持"><i>聊天</i></a>
				<div class="tooltip"><a target="_blank"  href="http://amos.alicdn.com/msg.aw?v=2&amp;uid=meinongebox:包装君&amp;site=cnalichn&amp;s=1&amp;charset=UTF-8" id="chat_show_user" class="chat" ><a style="color:white;" title="google不支持">联系我们</a><i id="new_msg" class="new_msg" style="display:none;"></i></a></div>
			</li>
			<li class="gotop" id="gotop">
				<i>返回顶部</i>
				<div class="tooltip"><em class="triangle"></em>返回顶部</div>
			</li>
			<!-- 新增关闭按钮 -->
			<li class="d_close">
				<i class="d_cl_btn"></i>
			</li>
			<!---->
		</ul>
	</div>
	<!--登录面板-->
	<div class="bar-login-box f14 none" id="js_loginBox">
		<?php 
			if ($_SESSION['is_login']) {
		?>
		<div class="on">
			<div class="photo"><img height="60" width="60" src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"></div>
			<p>
				用户名：<?php echo $_SESSION['member_name'];?><br>
				用户级别: <?php echo $output['member_info']['level_name'];?>
			</p>
		</div>
		<?php 
			} else {
		?>
		<div class="out">
			<div class="photo"><img height="60" width="60" src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"></div>
			<p>您好！请<a href="javascript:void(0)" Click="login(); return false;">登录</a>|<a href="<?php echo urlshop('login','register')?>">注册</a></p>
		</div>
		<?php }?>
		<div class="btn-box">
			<a class="btn" href="<?php echo urlshop('member_message','message');?>">我的消息</a>
			<a class="btn" href="<?php echo urlshop('member_order','index');?>">我的订单</a>
		</div>
		<em class="triangle"></em>
		<span class="close"></span>
	</div>
	<!--功能面板-->
	<div class="bar-sub-panel" id="js_subPanel">
		<!--我的购物车-->
		<div class="cart-box">
			<h3>购物车</h3>
			<div id="rtoolbar_cartlist"></div>
		</div>
		<!--我的财富-->
		<div class="asset-box">
			<h3>我的财富</h3>
			<div class="elasticity-box" id="asset-box"></div>
		</div>
		<!--我的收藏-->
		<div class="favorite-box">
			<h3>我的收藏</h3>
			<div class="elasticity-box" id="favorite-box"></div>
		</div>
		<!--最近查看-->
		<div class="histroy-box">
			<h3>最近查看 <b nc_type="bar_delbtn" data-param='{"goods_id":"all"}'>清空</b></h3>
			<div class="elasticity-box" id="histroy-box"></div>
		</div>
		<span class="close"></span>
	</div>
</div>
<script type="text/javascript">
$(function() {
       
	//右侧工具购物车信息获取
	if (!$("#rtoolbar_cartlist").html()) {
		ajaxLoad_cart_information();	
	}
	//右侧工具查看足迹获取
	if (!$("#histroy-box").html()) {
		ajaxLoad_history_information();	
	}

	//右侧工具-我的收藏
	if (!$("#favorite-box").html()) {
		ajaxLoad_favorite_information();
	}

	//右侧工具-我的财富
	if (!$("#asset-box").html()) {
		ajaxLoad_asset_information();
	}
	//清除浏览记录
	$("[nc_type='bar_delbtn']").bind('click',function(){
		   if(confirm("确实要删除吗？")){
			   var data_str = $(this).attr('data-param');
			   eval( "data_str = "+data_str);
			   $.getJSON('index.php?act=member_goodsbrowse&op=del&goods_id='+data_str.goods_id,function(data){
					if(data.done == true){
						if(data_str.goods_id == 'all'){
							location.reload(true);
						} else {
							$("#browserow_"+data_str.goods_id).hide();
					    }
					}else{
						showDialog(data.msg);
					}
				});
		   }
	});
         
});
//返回顶部
backTop=function (btnId){
	var btn=document.getElementById(btnId);
	var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	window.onscroll=set;
	btn.onclick=function (){
		btn.style.opacity="0.5";
		window.onscroll=null;
		this.timer=setInterval(function(){
		    scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
			scrollTop-=Math.ceil(scrollTop*0.1);
			if(scrollTop==0) clearInterval(btn.timer,window.onscroll=set);
			if (document.documentElement.scrollTop > 0) document.documentElement.scrollTop=scrollTop;
			if (document.body.scrollTop > 0) document.body.scrollTop=scrollTop;
		},10);
	};
	function set(){
	    scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	    btn.style.opacity=scrollTop?'1':"0.5";
	}
};
backTop('gotop');

//右边工具条
function toolbar() { 
	var mainBtn = $('#js_mainPanel .main-btn'),
	    loginBtn = $('#js_mainPanel .login'),
	    loginBox = $('#js_loginBox'),
	    loginColseBtn = $('#js_loginBox .close'),
	    subPanel = $('#js_subPanel'),
	    subBox = $('#js_subPanel > div'),
	    subColseBtn = $('#js_subPanel .close'),
	    login = $('#js_loginBox, #js_mainPanel .login');

	var index = 0,
			loginBtnOffset = loginBtn.offset().top,
			Wheight = $(window).height();

	var elasticityBox = $('#js_subPanel .elasticity-box'),
		cartElasticityBox = $('#js_subPanel .cart-box .elasticity-box');

	init();
	function init(){ 
		elasticityBox.css('max-height',Wheight-52);
		cartElasticityBox.css('max-height',Wheight-152);
	}

	mainBtn.click(function(){ 
		index = mainBtn.index(this);
		if (index == 1 || index == 2) {
			is_login = "<?php echo $_SESSION['is_login'];?>";
			//判断是否登录
			if (!is_login) {
				var Stop = $(window).scrollTop();
				var Etop = $(this).offset().top;
				loginBox.css('top',Etop-Stop);
				loginBox.show();
				return;
			}
		}
		subPanel.animate({left:'-282px'});
		subBox.addClass('none');
		subBox.eq(index).removeClass('none');
	})

	subColseBtn.click(function(){ 
		subPanel.animate({left:'40px'});
	})

	loginBtn.mouseover(function(){ 
		loginBox.css('top',loginBtnOffset);
	})

	login.mouseover(function(){ 
		loginBox.show();
	})

	login.mouseout(function(){ 
		loginBox.hide();
	})

	loginColseBtn.click(function(){ 
		loginBox.hide();
	})
}
toolbar();

/* ======= 15/6/25 ========= */
/* 新增左侧栏关闭按钮 */
$('.d_close').click(function(){
	$('.toolbar').animate({right:"-36px"},400);
	$('.d_open').animate({right:"30px"},400);
});
$('.d_open').click(function(){
	$('.toolbar').animate({right:"0"},400);
	$(this).animate({right:"-30px"},400);
});
/* ========================= */
</script>
<div class="public-top-layout w">
  <div class="topbar wrapper">
    <div class="user-entry">
      <?php if($_SESSION['is_login'] == '1'){?>
      <?php echo $lang['nc_hello'];?> <span>
      <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a>
      <?php if ($output['member_info']['level_name']){ ?>
      <div class="nc-grade-mini d-level-min d-level-min-<?php echo $output['member_info']['level']?>" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"></div>
      <?php } ?>
      </span> <?php echo $lang['nc_comma'],$lang['welcome_to_site'];?> <a href="<?php echo BASE_SITE_URL;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><span><?php echo $output['setting_config']['site_name']; ?></span></a> <span>[<a href="<?php echo urlShop('login','logout');?>"><?php echo $lang['nc_logout'];?></a>] </span>
      <?php }else{?>
      <?php echo $lang['nc_hello'].$lang['nc_comma'].$lang['welcome_to_site'];?> <a href="<?php echo BASE_SITE_URL;?>" title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $output['setting_config']['site_name']; ?></a> <span>[<a href="javascript:void(0)" onClick="login()"><?php echo $lang['nc_login'];?></a>]</span> <span>[<a href="<?php echo urlShop('login','register');?>"><?php echo $lang['nc_register'];?></a>]</span>
       <span>[<a href="<?php echo SHOP_SITE_URL;?>/api.php?act=tosina"><?php echo $lang['weibo_lgoin'];  ?></a>] </span>
      <?php }?><!--<span style="margin-left:10px;"> <a href="index.php?act=invite" style="color:red;">邀请返利</a></span>-->
     </div>
    <div class="quick-menu">
      <!--<dl>
        <dt><a href="<?php echo WAP_SITE_URL;?>">手机触屏版</a></dt>
      </dl>-->
	 <!--  <dl>
        <dt><a href="<?php echo urlShop('seller_login','show_login');?>" target="_blank" title="登录商家管理中心">商家登录</a>
       <a href="<?php echo SHOP_SITE_URL;?>/index.php?act=show_joinin&op=index" title="免费开店">免费开店</a><i></i></dt>
       <dd>
          <ul>
	
	    <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=pm_showjoinin&op=index" title="物管入驻">物管入驻</a></li>
            <li><a href="<?php echo urlShop('pm_login','index');?>" target="_blank" title="登录物业管理中心">物管登录</a></li>  
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=show_joinin&op=index" title="招商入驻">招商入驻</a></li>
            <li><a href="<?php echo urlShop('seller_login','show_login');?>" target="_blank" title="登录商家管理中心">商家登录</a></li>
            <li><a href="<?php echo DELIVERY_SITE_URL;?>/index.php?act=login" target="_blank" title="自提驿站">自提驿站</a></li>
          </ul>
        </dd>
      </dl>-->
      <dl>
        <dt><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order">我的订单</a><i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_new">待付款订单</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_send">待确认收货</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_noeval">待评价交易</a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist"><?php echo $lang['nc_favorites'];?></a><i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist">商品收藏</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fslist">店铺收藏</a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt>客户服务<i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 2));?>">帮助中心</a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 3));?>">售后服务</a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 4));?>">会员中心</a></li>
          </ul>
        </dd>
      </dl>
      <?php
      if(!empty($output['nav_list']) && is_array($output['nav_list'])){
	      foreach($output['nav_list'] as $nav){
	      if($nav['nav_location']<1){
	      	$output['nav_list_top'][] = $nav;
	      }
	      }
      }
      if(!empty($output['nav_list_top']) && is_array($output['nav_list_top'])){
      	?>
      <dl>
        <dt>站点导航<i></i></dt>
        <dd>
          <ul>
            <?php foreach($output['nav_list_top'] as $nav){?>
            <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        echo ' href="';
        switch($nav['nav_type']) {
        	case '0':echo $nav['nav_url'];break;
        	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
        	case '2':echo urlShop('article', 'article', array('ac_id'=>$nav['item_id']));break;
        	case '3':echo urlShop('activity', 'index', array('activity_id'=>$nav['item_id']));break;
        }
        echo '"';
        ?>><?php echo $nav['nav_title'];?></a></li>
            <?php }?>
          </ul>
        </dd>
      </dl>
      <?php }?>
	  <dl class="weixin">
        <dt>微信商城<i></i></dt>
        <dd>
          <h4>扫描二维码<br/>
            关注商城微信号</h4>
          <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logowx']; ?>" > </dd>
        </dl>
    </div>
  </div>
</div>
<script type="text/javascript">
/*edit by peiyu 修改登录按钮，记录点击页的地址*/
        function login(){
           //$_SESSION['ref_url'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
           var ref_url = window.location;
           $.cookie('ref_url', ref_url)
           window.location.href = '/index.php?act=login&op=index';
        }
        /*edit by peiyu 修改登录按钮，记录点击页的地址*/
//动画显示边条内容区域
$(function() {
	$(function() {
		$('#activator').click(function() {
			$('#content-cart').animate({'right': '-250px'});
			$('#content-compare').animate({'right': '-150px'});
			$('#ncToolbar').animate({'right': '-60px'}, 300,
			function() {
				$('#ncHideBar').animate({'right': '59px'},	300);
			});
	        $('div[nctype^="bar"]').hide();
		});
		$('#ncHideBar').click(function() {
			$('#ncHideBar').animate({
				'right': '-79px'
			},
			300,
			function() {
				$('#content-cart').animate({'right': '-250px'});
				$('#content-compare').animate({'right': '-250px'});
				$('#ncToolbar').animate({'right': '0'},300);
			});
		});
	});
    $("#compare").click(function(){
    	if ($("#content-compare").css('right') == '-210px') {
 		   loadCompare(false);
 		   $('#content-cart').animate({'right': '-210px'});
  		   $("#content-compare").animate({right:'50px'});
    	} else {
    		$(".close").click();
    		$(".chat-list").css("display",'none');
        }
	});
    $("#rtoolbar_cart").click(function(){
        if ($("#content-cart").css('right') == '-210px') {
         	$('#content-compare').animate({'right': '-210px'});
    		$("#content-cart").animate({right:'50px'});
    		if (!$("#rtoolbar_cartlist").html()) {
    			$("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
    		}
        } else {
        	$(".close").click();
        	$(".chat-list").css("display",'none');
        }
	});
	$(".close").click(function(){
		$(".content-box").animate({right:'-210px'});
      });

	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

    // 右侧bar用户信息
    $('div[nctype="a-barUserInfo"]').click(function(){
        $('div[nctype="barUserInfo"]').toggle();
    });
    // 右侧bar登录
    $('div[nctype="a-barLoginBox"]').click(function(){
        $('div[nctype="barLoginBox"]').toggle();
        document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();
    });
    $('a[nctype="close-barLoginBox"]').click(function(){
        $('div[nctype="barLoginBox"]').toggle();
    });
    <?php if ($output['cart_goods_num'] > 0) { ?>
    $('#rtoobar_cart_count').html(<?php echo $output['cart_goods_num'];?>).show();
    <?php } ?>
});
</script>