<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.no-content { font: normal 16px/20px Arial, "microsoft yahei"; color: #999999; text-align: center; padding: 150px 0; }
/*专题活动列表*/
.design {width:1200px;margin:0px auto;font-family: "microsoft yahei";font-size: normal 16px/20px}
.design_bane1{width:100%;height:500px;margin-bottom:0.833%;}
.design_bane2{width:100%;height:140px;margin-bottom:4.17%;}
.design_bane3{width:100%;height:52px;margin-bottom:2.5%;}
.design_goods{width:100%;height:1384px;}
.design_goods .goods_content{width:380px;height:336px;margin-bottom:10px;margin-right:30px;float:left;}
.title_top{width:360px;height:25px;line-height:25px;margin:20px 0px 10px 20px;font-size:20px;font-weight:bold;color:#333333}
.title_bottom{width:360px;height:17px;line-height:17px;margin-left:20px;font-size:12px;color:#999999}
.design_bane4{width:100%;height:52px;margin-top:3.34%;margin-bottom:2.5%;}
.design_edit{widht:100%;height:20px;text-align:center;font-size:14px;color:#666666;margin-bottom:30px;}
.design_edit_content {widht:100%;height:100px;font-size:14px;color:#666666;margin-bottom:30px;}
.design_edit_content .content{width:400px;height:100%;float:left}
.design_edit_content .content img{display:block;float:left}
.design_edit_content .content p{float:left}
/* 翻页样式 */
.pagination { display: inline-block; margin: 0 auto; }
.pagination ul { font-size: 0; *word-spacing:-1px/*IE6、7*/;}
.pagination ul li { vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; margin: 0 0 0 -1px; }
.pagination ul li { *display: inline/*IE6、7*/;*zoom:1;}
.pagination li span { font: normal 14px/20px "microsoft yahei"; color: #AAA; background-color: #FAFAFA; text-align: center; display: block; min-width: 20px; padding: 8px; border: 1px solid #E6E6E6; position: relative; z-index: 1; }
.pagination li a span, .pagination li a:visited span { color: #005AA0; text-decoration: none; background-color: #FFF; position: relative; z-index: 1; }
.pagination li a:hover span, .pagination li a:active span { color: #FFF; text-decoration: none !important; background-color: #D93600; border-color: #CA3300; position: relative; z-index: 9; cursor:pointer; }
.pagination li a:hover { text-decoration: none; }
.pagination li span.currentpage { color: #AAA; font-weight: bold; background-color: #FAFAFA; border-color: #E6E6E6; position: relative; z-index: 2; }
.nc-appbar-tabs a.compare { display: none !important; }
</style>
<div class="design">
    <div class="design_bane1">
        <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design1.jpg"/>
    </div>
    <div class="design_bane2">
        <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design2.jpg"/>
    </div>
    <div class="design_bane3">
        <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design3.jpg"/>
    </div>
    <div class="design_goods"> 
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods1.jpg"/>
            <p class="title_top">企业画册设计</p>
            <p class="title_bottom">可读性强的经典画册，展现企业软实力，提升品牌文化形象</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods2.jpg"/>
            <p class="title_top">产品海报设计</p>
            <p class="title_bottom">全方位、多角度展示产品，信息全面而具体</p>
        </div>
        <div class="goods_content" style="margin-right:0px">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods3.jpg"/>
            <p class="title_top">书籍封面设计</p>
            <p class="title_bottom">雅致大方，艺术装帧，吸引读者的门面</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods4.jpg"/>
            <p class="title_top">LOGO设计</p>
            <p class="title_bottom">品牌释义，独特内涵，设计与品位的完美结合</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods5.jpg"/>
            <p class="title_top">户外海报设计</p>
            <p class="title_bottom">很强的色彩冲击力，户外吸睛利器</p>
        </div>
        <div class="goods_content" style="margin-right:0px">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods6.jpg"/>
            <p class="title_top">单张/菜单设计</p>
            <p class="title_bottom">助力企业形象、诠释企业文化的有效方式，广泛应用于推广</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods7.jpg"/>
            <p class="title_top">公司全套VI设计</p>
            <p class="title_bottom">视觉识别设计最具传播力和感染力，最容易被公众接受</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods8.jpg"/>
            <p class="title_top">包装盒设计</p>
            <p class="title_bottom">产品呈现的最好展示，保证运输中产品的安全，提升产品的档次</p>
        </div>
        <div class="goods_content" style="margin-right:0px">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods9.jpg"/>
            <p class="title_top">包装袋设计</p>
            <p class="title_bottom">流通过程中方便运输，容易存储，盛装及装饰作用</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods10.jpg"/>
            <p class="title_top">商业插画/卡通形象设计</p>
            <p class="title_bottom">展现企业文化，更具趣味性与 传播性</p>
        </div>
        <div class="goods_content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods11.jpg"/>
            <p class="title_top">网络推广页面设计</p>
            <p class="title_bottom">清晰有效的推广页面，详实细致的推广方案</p>
        </div>
        <div class="goods_content" style="margin-right:0px">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design_goods12.jpg"/>
            <p class="title_top">网站网页设计</p>
            <p class="title_bottom">全面清晰的网站页面，有效跳转的服务需求</p>
        </div>
    <p style="height:0px;clear:both"></p>      
    </div>
    <div class="design_bane4">
        <img src="<?php echo SHOP_TEMPLATES_URL?>/images/design4.jpg"/>
    </div>
    <p class="design_edit">稿件修改是指设计师完成方案后，您对稿件提出的修改意见；因为修改属超出正常设计时间范围之内的，若需修改，则要重新安排时间。</p>
    <div class="design_edit_content">
        <div class="content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/content_1.png"/>
            <p>
                提供无限次修改服务。在设计定稿<br/>
                之前，去印为您提供无限次修改服<br/>
                务，直到您满意为止。
            </p>
        </div>
        <div class="content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/content_2.png"/>
            <p>
                在设计定稿之后，如果您仍需要修<br/>
                改设计内容，去印将视情况收取相<br/>
                关费用，敬请谅解。
            </p>
        </div>
        <div class="content">
            <img src="<?php echo SHOP_TEMPLATES_URL?>/images/content_3.png"/>
            <p>
                如果您不满意我们的设计服务，可<br/>
                申请退款；鉴于在整个设计服务中<br/>
                去印付出了相应的人力时间成本，<br/>
                所以需要收取整体费用的70%，敬<br/>
                请悉知。
            </p>
        </div>
    </div>
</div>