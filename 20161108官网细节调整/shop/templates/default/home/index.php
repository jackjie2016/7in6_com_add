<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<script type="text/javascript" src="http://121.199.25.147:8080/adms/adms_js/w.js"></script>
<!--2016 editor by peiyu -->
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/slider.js" charset="utf-8"></script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var uid = window.location.href.split("#V3");
var  fragment = uid[1];
if(fragment){
	if (fragment.indexOf("V3") == 0) {document.cookie='uid=0';}
else {document.cookie='uid='+uid[1];}
	}

$(function(){
  $('.tabs-panel').find('li').each(
    function(){
      $(this).hover(function(){
        var dd = $(this).find('dd.goods-thumb');

        dd.animate({left:"-5px"},400)
      },function(){
        var dd = $(this).find('dd.goods-thumb');

        dd.stop(true,false).animate({left:"0"},400)
      })
    }
  )
  function setCookie(name,value,days){
        //设置cookie过期时间
        var d = new Date();
        d.setTime(d.getTime() + days);
        var expires = "expires="+d.toGMTString();
        document.cookie = name + "=" + escape (value) + "; " + expires;
    }
    // 20161102 peiyu 添加判断显示代金券推广
    var guoqi_sj = $.cookie('guoqi_sj');
    var s1=1;
    var d= new Date();
    var h = d.getHours(); 
    var m = d.getMinutes(); 
    var se = d.getSeconds(); 
    var time = h*3600+m*60+se;
    var dead_time = 86400-time;
    dead_time =dead_time*1000;
    if (guoqi_sj == null) {
        //判断时间，显示提示层
        //setCookie("guoqi_sj","time",dead_time);
        setTimeout(function () { 
            $('#pop_layer').show();
        }, 5000);
        $('.tanceng_tp1').click(function(){
            if($('#pop_layer').css('display') == 'block'){
                $('#pop_layer').css('display','none');
            }
        });
    }
  
})
</script>
<style type="text/css">
.category { display: block !important; }
/*edit by peiyu start*/
    #pop_layer{
            display: none;
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            text-align: center;
            background: rgba(0,0,0,0.5);
            z-index: 999;
    }
    .pop_layer_all{position:relative;}
    .tanceng_tp1{position:absolute;top:15%;left:64%;width:30px;height:29px;}
    /*edit by peiyu stop*/
</style>
<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-standard-layout wrapper style-default">  </div>
<div class="home-focus-layout"> 

  <div id="5">
<script type="text/javascript">fillAdSpace("5");</script> 
</div>  
  
  <script type="text/javascript">

	update_screen_focus();

</script> 
<!--======================================================================
==========================================================================-->
<!--增加的每项分类切换banner图片-->
 
<!--======================================================================
==========================================================================-->
  <div class="right-sidebar b-z-index d_wd d_side_right">
  <!--   <div class="yBannerListInRight">
      <a href="javascript:;">
        <img src="<?php echo SHOP_TEMPLATES_URL?>/images/banner_fx/fushi01.png"/>
      </a>
      <b class="yimaginaryLine"></b>
      <a href="javascript:;">
        <img src="<?php echo SHOP_TEMPLATES_URL?>/images/banner_fx/fushi02.png"/>
      </a>
    </div>-->
   <!-- <div class="r-inlet">
    <div class="greeting">
      <img class="fl" height="50" width="50" src="<?php echo getMemberAvatar($_SESSION['avatar']);?>">
      <p class="fl">Hi,<?php echo $_SESSION['member_name'];?>,<?php $h=date('G');if($h>=8 && $h<11){?>早上好！<?php }elseif ($h>=11 && $h<13){?>中午好！<?php }elseif ($h>=13 && $h<17){?>下午好！<?php }else {?>晚上好！<?php }?><br>欢迎来到淘厂网</p>
    </div>
    <ul class="user-login">
      <li><a class="login" href="<?php echo urlShop('login');?>"><i></i>登 录</a></li>
      <li><a class="register" href="<?php echo urlShop('login','register');?>"><i></i>立即注册</a></li>
    </ul> 
    </div>-->
   <!---------------------------------2016.6.16 editor by peiyu----------------------------------------------------->
   <div id="6" class="r-inlet">
   <script type="text/javascript">fillAdSpace("6");</script>
    <!--<div class="bane_1">
        <a href='<?php echo SHOP_SITE_URL?>/item-105.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/item_1.png"></a>
    </div>
    <div class="bane_1">
        <a href='<?php echo SHOP_SITE_URL?>/item-607.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/item_2.png"></a>
    </div>
    <div class="bane_1">
        <a href='http://www.7in6.com/article_cate-5.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/item_3.png"></a>
    </div>-->
    </div>
 
    <!-- <div class="policy">
      <ul>
        <li class="b1">七天包退</li>
        <li class="b2">正品保障</li>
        <li class="b3">闪电发货</li>
      </ul>
    </div>
    <?php if(!empty($output['group_list']) && is_array($output['group_list'])) { ?>
    <div class="groupbuy">
      <div class="title"><i>抢</i>近期团购</div>
      <ul>
        <?php foreach($output['group_list'] as $val) { ?>
        <li>
          <dl style=" background-image:url(<?php echo gthumb($val['groupbuy_image1'], 'small');?>)">
            <dt><?php echo $val['groupbuy_name']; ?></dt>
            <dd class="price"><span class="groupbuy-price"><?php echo ncPriceFormatForList($val['groupbuy_price']); ?></span><span class="buy-button"><a href="<?php echo urlShop('show_groupbuy','groupbuy_detail',array('group_id'=> $val['groupbuy_id']));?>">立即抢</a></span></dd>
            <dd class="time"><span class="sell">已售<em><?php echo $val['buy_quantity'];?></em></span> <span class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </span></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?> -->
    <!-- <div class="proclamation">
      <ul class="tabs-nav">
        <li class="tabs-selected">
          <h3>最新公告</h3>
        </li>
        <li>
          <h3>最新活动</h3>
        </li>
      </ul>
      <div class="tabs-panel">
         <ul class="mall-news">
          <?php if(!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
          <?php foreach($output['show_article']['notice']['list'] as $val) { ?>
          <li><i></i><a target="_blank" href="<?php echo empty($val['article_url']) ? urlShop('article', 'show',array('article_id'=> $val['article_id'])):$val['article_url'] ;?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],24);?> </a>
            <time>(<?php echo date('Y-m-d',$val['article_time']);?>)</time>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <div class="tabs-panel tabs-hide">
        <ul class="mall-news">
          <?php if(!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
          <?php foreach($output['show_article']['notice']['list'] as $val) { ?>
          <li><i></i><a target="_blank" href="<?php echo empty($val['article_url']) ? urlShop('article', 'show',array('article_id'=> $val['article_id'])):$val['article_url'] ;?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],24);?> </a>
            <time>(<?php echo date('Y-m-d',$val['article_time']);?>)</time>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div> -->
  </div>
</div>
<!--HomeFocusLayout End-->

<!--<div class="home-sale-layout wrapper">
  <div class="left-layout"> <?php echo $output['web_html']['index_sale'];?> </div>
  <?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
  <div class="right-sidebar">
    <div class="title">
      <h3><?php echo $lang['nc_xianshi'];?></h3>
    </div>
    <div id="saleDiscount" class="sale-discount">
      <ul>
        <?php foreach($output['xianshi_item'] as $val) { ?>
        <li>
          <dl>
            <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
            <dd class="goods-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>"> <img src="<?php echo thumb($val, 240);?>"></a></dd>
            <dd class="goods-price"><?php echo ncPriceFormatForList($val['xianshi_price']); ?> <span class="original"><?php echo ncPriceFormatForList($val['goods_price']);?></span></dd>
            <dd class="goods-price-discount"><em><?php echo $val['xianshi_discount']; ?></em></dd>
            <dd class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP;?>"><i></i><em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </dd>
            <dd class="goods-buy-btn"></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php } ?>
</div>-->

<!--------------------------------------------------2016.6.18 editor by peiyu---------------------------------------------------------------------------->
<!--edit by peiyu 首页新增弹出层-->
<div id='pop_layer' >
    <div class='pop_layer_all'>
        <a href="/index.php?act=special&op=special_detail&special_id=10">
            <img class="tanceng_tp" src="<?php echo SHOP_TEMPLATES_URL;?>/images/PC-2.png" style="width:468px; height:363px; margin-top:12%">
        </a>
        <div class="tanceng_tp1">
                <img  src="<?php echo SHOP_TEMPLATES_URL;?>/images/PC-1.png" >
        </div>
    </div>  
</div>
<!--edit by peiyu stop-->
<div id="7" class="home-sale-layout">
<script type="text/javascript">fillAdSpace("7");</script> 
   <!-- <ul class="bane_1">
        <li class="li_1"><a href='<?php echo SHOP_SITE_URL?>/item-807.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane_001.jpg" width="210px" height="130px"></a></li>
        <li class="li_2"><a href='<?php echo SHOP_SITE_URL?>/item-25.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane_002.jpg" width="225px" height="130px"></a></li>
        <li class="li_2"><a href='<?php echo SHOP_SITE_URL?>/item-607.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane_003.jpg" width="225px" height="130px"></a></li>
        <li class="li_2"><a href='<?php echo SHOP_SITE_URL?>/item-806.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane_004.jpg" width="225px" height="130px"></a></li>
        <li class="li_2"><a href='<?php echo SHOP_SITE_URL?>/item-54.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane_005.jpg" width="240px" height="130px"></a></li>
    </ul>  -->
</div>
<!---推荐商品-->
<div id="8" class="home-standard-layout wrapper style-default">
 <!--<div id="8" class="home-sale-layout2"> -->
<script type="text/javascript">fillAdSpace("8");</script>
 <!--   <p>印刷一键式解决方案</p>
    <div id=slider>
        <div class=slide>
            <a href="<?php echo SHOP_SITE_URL?>/item-249.html">
                <img class=diapo  src="<?php echo SHOP_TEMPLATES_URL;?>/images/taocan_001.jpg">
            </a>
            <div class=text>促销类印刷包装品
            </div>
        </div>
        <div class=slide>
            <a href="<?php echo SHOP_SITE_URL?>/item-1095.html">
                <img class=diapo src="<?php echo SHOP_TEMPLATES_URL;?>/images/taocan_002.jpg">
            </a>
            <div class=text>餐饮类印刷包装品
            </div>
        </div>
        <div class=slide>
            <a href="<?php echo SHOP_SITE_URL?>/item-555.html">
                <img  class=diapo src="<?php echo SHOP_TEMPLATES_URL;?>/images/taocan_003.jpg">
            </a>
            <div class=text>办公类印刷品
            </div>
        </div>
        <div class=slide>
            <a href="<?php echo SHOP_SITE_URL?>/item-54.html">
                <img class=diapo src="<?php echo SHOP_TEMPLATES_URL;?>/images/taocan_004.jpg">
            </a>
            <div class=text>市场推广类印刷品
            </div>
        </div>
        <div class=slide>
            <a href="<?php echo SHOP_SITE_URL?>/item-607.html">
                <img class=diapo src="<?php echo SHOP_TEMPLATES_URL;?>/images/taocan_005.jpg">
            </a>
            <div class=text>展会宣传必备印刷品
            </div>
        </div>
        <div class=slide>
            <a href="<?php echo SHOP_SITE_URL?>/item-903.html">
                <img class=diapo src="<?php echo SHOP_TEMPLATES_URL;?>/images/taocan_006.jpg">
            </a>
            <div class=text>校园印务
            </div>
        </div>
    </div>
</div>
<SCRIPT type=text/javascript>
/* ==== start script ==== */
slider.init();
</SCRIPT> -->
</div>
<!--热卖商品-->
<div class="home-standard-layout wrapper style-default">
<div class="home-sale-layout3">
    <div class="title">
       <p class="p_left" style="color:black;">热销单品</p> 
       <p class="p_right">
           <span class="sp1">热门</span>
           <span class="sp2">名片</span>
           <span class="sp2">单页</span>
           <span class="sp2">二折页</span>
           <span class="sp2">不干胶</span>
           <span class="sp2">易拉宝</span>
           <span class="sp3">More</span>
       </p>
    </div>
	<div id="9" class="layout3_left">
          <script type="text/javascript">fillAdSpace("9");</script>
        <!-- <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_001.jpg"> -->
    </div>
	<div id="10" class="layout3_right">
    <script type="text/javascript">fillAdSpace("10");</script>
      <!--  <div class="layout3_top">
            <div id="4" class="layout3_top1">
               <?php echo SHOP_SITE_URL?>/item-145.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_002.jpg" ></a>
            </div>
            <div class="layout3_top2">
                <a href='<?php echo SHOP_SITE_URL?>/item-54.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_003.jpg" ></a>
            </div>
            <div class="layout3_top3">
                <a href='<?php echo SHOP_SITE_URL?>/item-1042.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_004.jpg" ></a>
            </div>
        </div>
        <div class="layout3_bottom">
            <div class="layout3_top2">
                <a href='<?php echo SHOP_SITE_URL?>/item-607.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_005.jpg" ></a>
            </div>
            <div class="layout3_top2">
                <a href='<?php echo SHOP_SITE_URL?>/item-52.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_006.jpg"  ></a>
            </div>
            <div class="layout3_top2">
                <a href='<?php echo SHOP_SITE_URL?>/item-1011.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_007.jpg" ></a>
            </div>
             <div class="layout3_top3">
                <a href='<?php echo SHOP_SITE_URL?>/item-556.html'><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane3_008.jpg" ></a>
            </div>
            <p style="clear:both"></p>
        </div> -->
    </div>
</div>
<p style="clear:both"></p>
</div>
<!--热卖商品 end-->
<!--按用途分类-->
<div class="home-standard-layout wrapper style-default">
<div class="home-sale-layout4">
    <div class="title">
       <p class="p_left" style="color:black;">按用途找盒</p> 
       <p class="p_right">
           <span class="sp1">热门</span>
           <span class="sp2">食品包装</span>
           <span class="sp2">化妆品包装</span>
           <span class="sp2">医药保健包装</span>
           <span class="sp3">More</span>
       </p>
    </div>
    <div class="content" >
		<div id="11" class="layout4_left">
        	<script type="text/javascript">fillAdSpace("11");</script>
           <!--  <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_001.jpg"> -->
         </div>
		<div id="12" class="layout4_right">
        <script type="text/javascript">fillAdSpace("12");</script>
           <!-- 
            <div class="layout4_bottom">
                <div class="layout4_top2">
                    <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_002.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>3C数码类包装</span><br/>
                        </p>
                        <!--<div class="layout4_top2_21">
                            <a>已售出9000个</a>
                            <button>立即购买</button>
                        </div>-->
                   <!-- </div>
                </div>
                <div class="layout4_top2">
                    <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_003.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>礼盒类包装</span><br/>
                        </p>
                    </div>
                </div>
                <div class="layout4_top2">
                    <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_004.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手工盒类</span><br/>
                        </p>
                    </div>
                </div>
                <div class="layout4_top3">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_005.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>化妆品包装盒</span><br/>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layout4_bottom">
                <div class="layout4_top2">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_006.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>生鲜农产品包装</span><br/>
                        </p>
                    </div>
                </div>
                <div class="layout4_top2">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_007.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>医药保健类包装</span><br/>
                        </p>
                    </div>
                </div>
                <div class="layout4_top2">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_008.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>茶叶酒水类</span><br/>
                        </p>
                    </div>
                </div>
                <div class="layout4_top4">
                    <div class="layout4_top4_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_009.jpg">
                    </div>
                    <div class="layout4_top4_2">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_010.jpg">
                    </div>
                </div>
                <p style="clear:both"></p>
            </div> -->
        </div>
    </div>
</div>
<p style="clear:both"></p>
</div>
<!--按用途分类 end-->
<!--按结构找盒
<div class="home-sale-layout4">
     <div class="title">
       <p class="p_left">F3 按结构找盒</p> 
       <p class="p_right">
           <span class="sp1">热门</span>
           <span class="sp2">管式盒</span>
           <span class="sp2">一体成型盒</span>
           <span class="sp2">天地盖盒</span>
           <span class="sp2">抽屉盒</span>
           <span class="sp2">手提盒</span>
           <span class="sp2">特殊盒型</span>
           <span class="sp2">天地盖盒</span>
           <span class="sp2">自定义印刷</span>
       </p>
    </div>
    <div class="content" >
        <div class="layout4_left">
            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_2.png">
         </div>
        <div class="layout4_right">
            <div class="layout4_bottom">
                <div class="layout4_top2">
                    <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出9000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
                <div class="layout4_top2">
                    <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出18000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
                <div class="layout4_top2">
                    <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出27000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
                <div class="layout4_top3">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出27000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layout4_bottom">
                <div class="layout4_top2">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出27000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
                <div class="layout4_top2">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出27000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
                <div class="layout4_top2">
                     <div class="layout4_top2_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_3.jpg">
                    </div>
                    <div class="layout4_top2_2">
                        <p>
                            <span>手提盒</span><br/>
                            <span>结构新颖，方便携带</span>
                        </p>
                        <div class="layout4_top2_21">
                            <a>已售出27000个</a>
                            <button>立即购买</button>
                        </div>
                    </div>
                </div>
                <div class="layout4_top4">
                    <div class="layout4_top4_1">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4.png">
                    </div>
                    <div class="layout4_top4_2">
                        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane4_1.png">
                    </div>
                </div>
                <p style="clear:both"></p>
            </div>
        </div>
    </div>
</div>
<p style="clear:both"></p>-->
<!--按结构找盒 end-->
<!--其它产品设计 start-->
<div class="home-standard-layout wrapper style-default">
<div id="13" class="home-sale-layout5">
<script type="text/javascript">fillAdSpace("13");</script>
   <!-- <div class="title">
       <p class="p_left">个性定制</p> 
    </div>
    <div class="bane_5">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane5_001.jpg">
    </div>
    <div class="bane_5">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane5_002.jpg">
    </div>
    <div class="bane_5">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/bane5_003.jpg">
    </div>
    <p style="clear:both"></p>-->
</div>
</div>
<!--其它产品设计 end-->
<!--<div class="home-sale-layout wrapper">
  <div class="left-layout"> <?php echo $output['web_html']['index_sale'];?> </div>
  <?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
  <div class="right-sidebar">
    <div class="title">
      <h3><?php echo $lang['nc_xianshi'];?></h3>
    </div>
    <div id="saleDiscount" class="sale-discount">
      <ul>
        <?php foreach($output['xianshi_item'] as $val) { ?>
        <li>
          <dl>
            <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
            <dd class="goods-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>"> <img src="<?php echo thumb($val, 240);?>"></a></dd>
            <dd class="goods-price"><?php echo ncPriceFormatForList($val['xianshi_price']); ?> <span class="original"><?php echo ncPriceFormatForList($val['goods_price']);?></span></dd>
            <dd class="goods-price-discount"><em><?php echo $val['xianshi_discount']; ?></em></dd>
            <dd class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP;?>"><i></i><em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </dd>
            <dd class="goods-buy-btn"></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php } ?>
</div>-->
<div class="wrapper">
  <div class="mt10">
    <div class="mt10"><?php echo loadadv(1,'html');?></div>
  </div>
</div>
<!--StandardLayout Begin--> 
<?php echo $output['web_html']['index'];?> 
<!--StandardLayout End-->
<div class="wrapper">
  <div class="mt10"><?php echo loadadv(9,'html');?></div>
</div>
 

<div class="full_module wansyb-wp">
    <h2><b><?php echo $lang['index_index_link'];?></b></h2>
    <div class="piclink">
      <?php if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] != ''){
		  ?>
      <span><a href="<?php echo $val['link_url']; ?>" target="_blank"><img src="<?php echo $val['link_pic']; ?>" title="<?php echo $val['link_title']; ?>" alt="<?php echo $val['link_title']; ?>" width="88" height="31" ></a></span>
      <?php
		  		}
		 	}
		 }
		 ?>
      <div class="clear"></div>
    </div>
    <div class="textlink">
      <?php 
		  if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] == ''){
		  ?>
      <span><a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a></span>
      <?php
		  		}
		 	}
		 }
		 ?>
      <div class="clear"></div>
    </div>
  </div>
  
<!--link end-->

<div class="footer-line"></div>
<!--首页底部保障开始-->
<?php require_once template('layout/index_ensure');?>
<!--首页底部保障结束-->
<!--StandardLayout Begin-->
<!--======== 增加顶部悬停搜索条 15/6/25 =========-->
<div class="d_top_search">
  <div class="d_search_main">
    <div class="d_lg_mg"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/d_lg_mg.png"></div>
    <div class="d_search_in">
        <form class="search-form" method="get" action="<?php echo SHOP_SITE_URL;?>">
        <div class="d_in_box">
            <input type="hidden" value="search" id="search_act" name="act">
            <input class="d_in_sc" name="keyword" type=text id="d_sc" autocomplete="off" title="请输入搜索文字" value="搜索你想要的商品，会有更多惊喜！" onfocus="if (value =='搜索你想要的商品，会有更多惊喜！'){value =''}" onblur="if (value ==''){value='搜索你想要的商品，会有更多惊喜！'}"></div>
           <!--<button class="d_sc_btn">搜索</button>-->
          <input type="submit" id="button" value="搜索" class="d_sc_btn">
          </form>
    </div>
    <div class="d_lg_rg">
      <a href="<?php echo urlShop('login');?>">登录</a>
      <a href="<?php echo urlShop('login','register');?>" style="margin-left: 10px;">注册</a>
      <a href="javascript:;" class="d_cart_nb"><i></i>购物车<span class="cart-num"><?php echo $output['cart_goods_num'];?></span></a>
    </div>
  </div>
</div>
<!--=================-->
<div class="nav_Sidebar">
<a class="nav_Sidebar_1" href="javascript:;" ></a>
<a class="nav_Sidebar_2" href="javascript:;" ></a>
<a class="nav_Sidebar_3" href="javascript:;" ></a>
<a class="nav_Sidebar_4" href="javascript:;" ></a>
<a class="nav_Sidebar_5" href="javascript:;" ></a>
<!--<a class="nav_Sidebar_6" href="javascript:;" ></a> 
<a class="nav_Sidebar_7" href="javascript:;" ></a>
<a class="nav_Sidebar_8" href="javascript:;" ></a>-->
</div>

<!--StandardLayout End-->