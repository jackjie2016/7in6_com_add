<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/imagezoom/jquery.imagezoom.min.js"></script>
<!--edit by peiyu start-->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!--edit by peiyu stop-->
<style type="text/css">
.preview{position: relative; width:350px; height:410px;}
/* smallImg */
.smallImg{position:relative; height:52px; margin-top:1px; background-color:#F1F0F0; padding:6px 0px; width:350px; overflow:hidden;float:left;}
.scrollbutton{width:14px; height:50px; overflow:hidden; position:relative; float:left; cursor:pointer; }
.scrollbutton.smallImgUp , .scrollbutton.smallImgUp.disabled{background:url(../shop/templates/default/images/d_08.png) no-repeat; margin:0px 5px;}
.scrollbutton.smallImgDown , .scrollbutton.smallImgDown.disabled{background:url(../shop/templates/default/images/d_09.png) no-repeat; margin-left:330px; margin-top:-50px;}

#imageMenu {height:50px; width:300px; overflow:hidden; margin-left:6px; float:left;}
#imageMenu li {height:50px; width:60px; overflow:hidden; float:left; text-align:center;}
#imageMenu li img{width:50px; height:50px;cursor:pointer;}
#imageMenu li#onlickImg img, #imageMenu li:hover img{ width:44px; height:44px; border:3px solid #55a4e0;}
/* bigImg */
.bigImg{position:relative; float:left;  width:350px; height:350px; overflow:hidden;}
.bigImg #midimg{ width:350px; height:350px;}
.bigImg #winSelector{width:235px; height:210px;}
#winSelector{position:absolute; cursor:crosshair; filter:alpha(opacity=15); -moz-opacity:0.15; opacity:0.15; background-color:#000; border:1px solid #fff;}
/* bigView */
#bigView{position:absolute;border: 1px solid #CCC; overflow: hidden; z-index:999;}
#bigView img{position:absolute;}

.ncs-goods-picture .levelB, .ncs-goods-picture .levelC { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/shop/zoom.cur), pointer;}
.ncs-goods-picture .levelD { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/shop/hand.cur), move\9;}
</style>
<div id="content" class="wrapper pr">
    <input type="hidden" id="lockcompare" value="unlock" />
	<input type="hidden" id="id_value" value="" />
  <div class="ncs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>">
    <!-- S 商品图片 -->
    <div id="ncs-goods-picture" class="ncs-goods-picture image_zoom">
    <!-- 商品图新版结束 -->
    <div class="preview">
	<div id="vertical" class="bigImg">
		<img src="<?php echo $output["goods_image"]["0"]["1"] ?>" width="350" height="350" alt="" id="midimg" />
		<div style="display:none;" id="winSelector"></div>
	</div>
	
	<!--bigImg end-->	
	<div class="smallImg">
		<div class="scrollbutton smallImgUp disabled"></div>
		<div id="imageMenu">
			<ul>
				<?php
                                //print_r(count($output["goods_image"]));die;
		    		foreach ($output["goods_image"] as $key => $value) {
		 		?>
				<li <?php if ($key==0){?>id="onlickImg"<?php }?>><img src="<?php echo $value['0'] ?>" width="68" height="68"/></li>
				<?php
					 }
				?>
			</ul>
		</div>
		<div class="scrollbutton smallImgDown"></div>
	</div><!--smallImg end-->	
	<div id="bigView" style="display:none;"><img width="650" height="700" alt="" src="" /></div>
</div>

    </div>
    <!-- S 商品基本信息 -->
    <div class="ncs-goods-summary">
      <div class="name">
        <h1><?php echo $output['goods']['goods_name']; ?></h1>
        <strong><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></strong> </div>
      <div class="ncs-meta">
        <div class="rate"> <!-- S 描述相符评分 --><a href="#ncGoodsRate">商品评分</a>
          <div class="raty" data-score="<?php echo $output['goods_evaluate_info']['star_average'];?>"></div>
          <!-- E 描述相符评分 --> </div>

        <!-- S 商品参考价格 -->
        <dl>
          <dt><?php echo $lang['goods_index_goods_cost_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="cost-price"><strong><?php echo $lang['currency'].$output['goods']['goods_marketprice'];?></strong></dd>
        </dl>
        <!-- E 商品参考价格 -->
        <!-- S 商品发布价格 -->
        <dl>
          <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="price" id="price1">
            <?php if (isset($output['goods']['title']) && $output['goods']['title'] != '') {?>
            <!-- wugangjian 20160917 修改1团购标签为1元购 -->
            <!-- <span class="tag"><?php echo $output['goods']['title'];?></span> -->
            <span class="tag">1元购</span>
            <?php }?>
            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
            <strong><?php echo $lang['currency'].$output['goods']['promotion_price'];?></strong><em>(原售价<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].$output['goods']['goods_price'];?>)</em>
            <?php } else {?>
            <strong><?php echo $lang['currency'].$output['goods']['goods_price'];?></strong>
            <?php }?>
          </dd>
        </dl>
        <!-- E 商品发布价格 -->
        <!-- S 促销 -->
        <?php if (isset($output['goods']['promotion_type']) || $output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt>促销信息：</dt>
          <dd class="promotion-info">
            <!-- S 限时折扣 -->
            <?php if ($output['goods']['promotion_type'] == 'xianshi') {?>
            <?php echo '直降：'.$lang['currency'].$output['goods']['down_price'];?>
            <?php if($output['goods']['lower_limit']) {?>
            <em><?php echo sprintf('最低%s件起',$output['goods']['lower_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['explain'];?></span><br>
            <?php }?>
            <!-- E 限时折扣  -->
            <!-- S 团购-->
            <?php if ($output['goods']['promotion_type'] == 'groupbuy') {?>
            <?php if ($output['goods']['upper_limit']) {?>
            <em><?php echo sprintf('最多限购%s件',$output['goods']['upper_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['remark'];?></span><br>
            <?php }?>
            <!-- E 团购 -->
            <!-- S 赠品 -->
            <?php if ($output['goods']['have_gift'] == 'gift') {?>
            <?php echo '赠品'?> <span>赠下方的热销商品，赠完即止</span>
            <?php }?>
            <!-- E 赠品 -->
          </dd>
        </dl>
        <?php }?>
        <!-- E 促销 -->
      </div>
      <div class="ncs-plus">
        <!-- S 物流运费  预售商品不显示物流 -->
        <?php if ($output['goods']['is_virtual'] == 0) {?>
        <dl class="ncs-freight">
          <dt>
            <?php if ($output['goods']['goods_transfee_charge'] == 1){?>
            <?php echo $lang['goods_index_freight'].$lang['nc_colon'];?>
            <?php }else{?>
            <!-- 如果买家承担运费 -->
            <!-- 如果使用了运费模板 -->
			<?php if ($output['goods']['transport_id'] != '0'){;
                $id_1=$output['goods']['areaid_1'];
                $id_2=$output['goods']['areaid_2'];
                $province = Model('area')->where(array('area_id'=>$id_1))->select();
                $city = Model('area')->where(array('area_id'=>$id_2))->select();
                echo $province[0]['area_name']."省";
                echo $city[0]['area_name'];
            ?>
            <?php echo $lang['goods_index_trans_to'];?><a href="javascript:void(0)" id="ncrecive"><?php echo $lang['goods_index_trans_country'];?></a><?php echo $lang['nc_colon'];?>
            <div class="ncs-freight-box" id="transport_pannel">
              <?php if (is_array($output['area_list'])){?>
              <?php foreach($output['area_list'] as $k=>$v){?>
              <a href="javascript:void(0)" nctype="<?php echo $k;?>"><?php echo $v;?></a>
              <?php }?>
              <?php }?>
            </div>
            <?php }else{?>
            <?php echo $lang['goods_index_trans_zcountry'];?><?php echo $lang['nc_colon'];?>
            <?php }?>
            <?php }?>
          </dt>
          <dd id="transport_price">
            <?php if($output['goods']['promotion_type'] == 'groupbuy') { ?>
            <!-- 1元购 调整团购运费，使用运费模板；wugangjian 20160910 START-->
            <!-- <span><?php echo $lang['goods_index_groupbuy_no_shipping_fee'];?></span> -->
            <span id="nc_kd">运费<?php echo $lang['nc_colon'];?><em><?php echo $output['goods']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
            <!-- 1元购 调整团购运费，使用运费模板；wugangjian 20160910 END-->
            <?php } else { ?>
            <?php if ($output['goods']['goods_freight'] == 0){?>
            <span id="nc_kd"><?php echo $lang['goods_index_trans_for_seller'];?></span>
            <?php }else{?>
            <!-- 如果买家承担运费 -->
            <span id="nc_kd">运费<?php echo $lang['nc_colon'];?><em><?php echo $output['goods']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
            <?php }?>
            <?php }?>
          </dd>
          <dd style="color:red;display:none" id="loading_price">loading.....</dd>
        </dl>
        <?php }?>
        <!-- E 物流运费 --->

        <!-- S 赠品 -->
        <?php if ($output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt>赠&#12288;&#12288;品：</dt>
          <dd class="goods-gift" id="ncsGoodsGift">
            <?php if (!empty($output['gift_array'])) {?>
            <ul>
              <?php foreach ($output['gift_array'] as $val){?>
              <li>
                <div class="goods-gift-thumb"><span><img src="<?php echo cthumb($val['gift_goodsimage'], '60', $output['goods']['store_id']);?>"></span></div>
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['gift_goodsid']));?>" class="goods-gift-name" target="_blank"><?php echo $val['gift_goodsname']?></a><em>x<?php echo $val['gift_amount'];?></em> </li>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <!-- S 赠品 -->
      </div>
      <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
      <div class="ncs-key">
        <!-- S 商品规格值-->
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
        <dl nctype="nc-spec">
          <dt><?php echo $val;?><?php echo $lang['nc_colon'];?></dt>
          <dd>						<input type="hidden" id="ck_goods_id" value="none" />			
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-img"><a href="javascript:void(0);" class="<?php if (isset($output['goods']['goods_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><img src="<?php echo $output['spec_image'][$k];?>"/><?php echo $v;?><i></i></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?><i></i></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值-->
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <dl>
          <dt>提货方式：</dt>
          <dd>
            <ul>
              <li class="sp-txt"><a href="javascript:void(0)" class="hovered">电子兑换券<i></i></a></li>
            </ul>
          </dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <!-- 虚拟商品有效期 -->
        <dl>
          <dt>有&nbsp;效&nbsp;期：</dt>
          <dd>即日起 到 <?php echo date('Y-m-d H:i:s', $output['goods']['virtual_indate']);?></dd>
        </dl>
        <?php }else if ($output['goods']['is_presell'] == 1) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>预&#12288;&#12288;售：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered"><?php echo date('Y-m-d', $output['goods']['presell_deliverdate']);?>&nbsp;日发货<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_fcode']) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>购买类型：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered">F码优先购买<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <!-- S 购买数量及库存 -->
        <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] >= 0) {?>
        <dl>
          <dt><?php echo $lang['goods_index_buy_amount'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="ncs-figure-input">
              <?php if($output['goods']['goods_min_order']==''){?>
              <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="text w30" <?php if ($output['goods']['is_fcode'] == 1 || $output['goods']['purchase_quantity'] == 1 )?> onblur="this.value=this.value.replace(/\D/g,'')" >
              <?php }else{?>
               <input type="text" name="" id="quantity" value="<?php echo $output['goods']['goods_min_order'];?>" size="3" maxlength="6" class="text w30" <?php if ($output['goods']['is_fcode'] == 1 || $output['goods']['purchase_quantity'] == 1 )?> onblur="this.value=this.value.replace(/\D/g,'')" >
              <?php }?>
              <input type="hidden" id="purchase_quantity" value="<?php if($output['goods']['purchase_quantity']>0){echo $output['goods']['purchase_quantity'];}else{echo 0;} ?>" />			
            <?php if ($output['goods']['is_fcode'] == 1) {?>
            <span style="margin-left: 5px;">（每个F码优先购买一件商品）</span>(<?php echo $lang['goods_index_stock'];?><em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?>)
            <?php } else {?>
            <a href="javascript:void(0)" class="increase">+</a><a href="javascript:void(0)" class="decrease">-</a> <span>(<?php echo $lang['goods_index_stock'];?><em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?>
            <!-- 虚拟商品限购数 -->
            <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) { ?>，每人次限购<strong>
              <!-- 虚拟团购 设置了虚拟团购限购数 该数小于原商品限购数 -->
              <!-- wugangjian 20160917 设置1元购已购数量 -->
              <?php echo ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0 && $output['goods']['upper_limit'] < $output['goods']['virtual_limit']) ? $output['goods']['upper_limit'] : $output['goods']['virtual_limit'];?>
              </strong>件<?php } ?>
            <?php if($output['goods']['purchase_quantity']>0){?>,已购<?php echo 1000-$output['goods']['goods_storage']; ?>件<?php } ?>)			<?php if($output['goods']['purchase_quantity']>0){?>此商品每人限购 <?php echo $output['goods']['purchase_quantity'];?> 件 <?php }?>						</span><?php } ?>
          </dd>
        </dl>
        <?php }?>
        <!-- E 购买数量及库存 -->
      </div>
      <!-- S 购买按钮 -->
        <div class="ncs-btn"><!-- S 提示已选规格及库存不足无法购买 -->
          <!--<div nctype="goods_prompt" class="ncs-point">
            <?php if (!empty($output['goods']['goods_spec'])) {?>
            <span class="yes"><?php echo $lang['goods_index_you_choose'];?> <strong><?php echo implode($lang['nc_comma'], $output['goods']['goods_spec']);?></strong></span>
            <?php }?>
            <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
            <span class="no"><i class="icon-exclamation-sign"></i>&nbsp;<?php echo $lang['goods_index_understock_prompt'];?></span>
            <?php }?>
          </div>-->
          <!-- E 提示已选规格及库存不足无法购买 -->
          <!-- S到货通知 -->
          <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
          <a href="javascript:void(0);" nctype="arrival_notice" class="arrival" title="到货通知">（<i class="icon-bullhorn"></i>到货通知）</a>
          <?php }?>
          <!-- E到货通知 -->
          <div class="clear"></div>
          
          <!-- 预约 -->
          <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
          <div>销售时间：<?php echo date('Y-m-d H:i:s', $output['goods']['appoint_satedate']);?></div>
          <a href="javascript:void(0);" nctype="appoint_submit" class="addcart" title="立即预约">立即预约</a>
          <?php }?>
          <!-- 立即购买-->
          <a href="javascript:void(0);" nctype="buynow_submit" class="buynow <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>no-buynow<?php }?>" title="<?php echo $output['goods']['buynow_text'];?>"><?php echo $output['goods']['buynow_text'];?></a>
          
          <!-- 1元购 调整1元购隐藏加入购物车按钮 wugangjian 20170910
          && empty($output['goods']['title']) START-->
          <?php if ($output['goods']['cart'] == true  && empty($output['goods']['title'])&& $output['goods']['gc_id_1']!=5481) {?>
          <!-- 1元购 调整1元购隐藏加入购物车按钮 wugangjian 20170910 END-->
          <!-- 加入购物车-->
          <a href="javascript:void(0);" nctype="addcart_submit" class="addcart <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>no-addcart<?php }?>" title="<?php echo $lang['goods_index_add_to_cart'];?>"><i class="icon-shopping-cart"></i><?php echo $lang['goods_index_add_to_cart'];?></a>
          <?php } ?>

          <!-- S 加入购物车弹出提示框 -->
          <div class="ncs-cart-popup">
            <dl>
              <dt><?php echo $lang['goods_index_cart_success'];?><a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.ncs-cart-popup').css({'display':'none'});">X</a></dt>
              <dd><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly" class="saleP"></em></dd>
              <dd class="btns"><a href="javascript:void(0);" class="ncs-btn-mini ncs-btn-green" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?act=cart'"><?php echo $lang['goods_index_view_cart'];?></a> <a href="javascript:void(0);" class="ncs-btn-mini" value="" onClick="$('.ncs-cart-popup').css({'display':'none'});"><?php echo $lang['goods_index_continue_shopping'];?></a></dd>
            </dl>
          </div>
          <!-- E 加入购物车弹出提示框 -->

        </div>
        <!-- E 购买按钮 -->
      <?php }else{?>
      <div class="ncs-saleout">
        <dl>
          <dt><i class="icon-info-sign"></i><?php echo $lang['goods_index_is_no_show'];?></dt>
          <dd><?php echo $lang['goods_index_is_no_show_message_one'];?></dd>
          <dd><?php echo $lang['goods_index_is_no_show_message_two_1'];?>&nbsp;<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['goods']['store_id']), $output['store_info']['store_domain']);?>" class="ncs-btn-mini"><?php echo $lang['goods_index_is_no_show_message_two_2'];?></a>&nbsp;<?php echo $lang['goods_index_is_no_show_message_two_3'];?> </dd>
        </dl>
      </div>
      <?php }?>
      <!--E 商品信息 -->

    </div>
    <!-- E 商品图片及收藏分享 -->
    <div class="ncs-handle">
      <!-- S 分享 -->
      <a href="javascript:void(0);" class="share" nc_type="sharegoods" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_snsshare_goods'];?><span>(<em nc_type="sharecount_<?php echo $output['goods']['goods_id'];?>"><?php echo intval($output['goods']['sharenum'])>0?intval($output['goods']['sharenum']):0;?>)</em></span></a>
      <!-- S 收藏 -->
      <a href="javascript:collect_goods('<?php echo $output['goods']['goods_id']; ?>','count','goods_collect');" class="favorite"><i></i><?php echo $lang['goods_index_favorite_goods'];?><span>(<em nctype="goods_collect"><?php echo $output['goods']['goods_collect']?></em>)</span></a>
      <!-- S 对比 -->
      <a href="javascript:void(0);" class="compare" nc_type="compare_<?php echo $output['goods']['goods_id'];?>" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i>加入对比</a><!-- S 举报 -->
      <?php if($output['inform_switch']) { ?>
      <a href="<?php if ($_SESSION['is_login']) {?>index.php?act=member_inform&op=inform_submit&goods_id=<?php echo $output['goods']['goods_id'];?><?php } else {?>javascript:login_dialog();<?php }?>" title="<?php echo $lang['goods_index_goods_inform'];?>" class="inform"><i></i><?php echo $lang['goods_index_goods_inform'];?></a>
      <?php } ?>
      <!-- End --> </div>

    <!--S 店铺信息-->
    <div style=" position: absolute; z-index: 1; top: -1px; right: -1px;">
      <?php include template('store/info');?>
    </div>
    <!--E 店铺信息 -->
    <div class="clear"></div>
  </div>
  <div class="ncs-goods-layout expanded" >
    <div class="ncs-goods-main" id="main-nav-holder">
      <!--edit by peiyu 图片上传-->
      <div class="up-img">
      <form method="post" action="" id="fileupload" enctype="multipart/form-data" >
          <div class='top'>
                <div class='up_left'>
                    <input type="hidden" name="category_id" id="category_id" value="<?php echo $output['class_info']['aclass_id']?>">
                        <div class="upload-con-div">
                            <div class="ncsc-upload-btn"> 
                                <a href="javascript:void(0);">
                                    <span>
                                        <?php if($_SESSION['is_login'] !== '1'){?>
                                            <input type="file" hidefocus="true" size="1" id='pic' class="input-file" name="file" multiple="multiple" disabled="disable"/>
                                        <?php }else{?>
                                            <input type="file" hidefocus="true" size="1" id='pic' class="input-file" name="file" multiple="multiple" />
                                        <?php }?>
                                    </span>
                                    <p>
                                        <i class="icon-upload-alt"></i>图片上传
                                    </p>
                                </a> 
                            </div>
                        </div>
               </div>
               <div class='up_right'>
                    <p class="tip">
                        温馨提示只有登录才可以上传文件素材，允许上传gif,jpg,jpeg,bmp,<br/>
                        png,swf,tbi格式的文件,单次上传不能超过4张,单张文件不大于2M,文<br/>
                        件上传后可在个人中心查看,下单后联系客服自己上传的文件名
                    </p>
               </div>
              <p style='clear:both'></p>
          </div>
        </form>
          <div nctype="file_msg" id='file_msg'></div>
          <div class="upload-pmgressbar" nctype="file_loading"></div>
      </div>
      <!--edit by peiyu -->
      <!-- S 优惠套装 -->
      <div class="ncs-promotion" id="nc-bundling" style="display:none;"></div>
      <!-- E 优惠套装 -->
      <div class="tabbar pngFix" id="main-nav">
        <div class="ncs-goods-title-nav">
          <ul id="categorymenu">
            <li class="current"><a id="tabGoodsIntro" href="javascript:void(0);"><?php echo $lang['goods_index_goods_info'];?></a></li>
            <li><a id="tabGoodsRate" href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?><em>(<?php echo $output['goods_evaluate_info']['all'];?>)</em></a></li>
            <li><a id="tabGoodsTraded" href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?><em>(<?php echo $output['goods']['goods_salenum']; ?>)</em></a></li>
            <li><a id="tabGuestbook" href="javascript:void(0);"><?php echo $lang['goods_index_goods_consult'];?></a></li>
          </ul>
          <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
        </div>
      </div>
      <div class="ncs-intro">
        <div class="content bd" id="ncGoodsIntro">

          <!--S 满就送 -->
          <?php if($output['mansong_info']) { ?>
          <div class="nc-mansong">
            <div class="nc-mansong-ico"></div>
            <dl class="nc-mansong-content">
              <dt><?php echo $output['mansong_info']['mansong_name'];?>
                <time>( <?php echo $lang['nc_promotion_time'];?><?php echo $lang['nc_colon'];?><?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )</time>
              </dt>
              <dd>
                <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                <span><?php echo $lang['nc_man'];?><em><?php echo ncPriceFormat($rule['price']);?></em><?php echo $lang['nc_yuan'];?>
                <?php if(!empty($rule['discount'])) { ?>
                ， <?php echo $lang['nc_reduce'];?><i><?php echo ncPriceFormat($rule['discount']);?></i><?php echo $lang['nc_yuan'];?>
                <?php } ?>
                <?php if(!empty($rule['goods_id'])) { ?>
                ， <?php echo $lang['nc_gift'];?> <a href="<?php echo $rule['goods_url'];?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank"> <img src="<?php echo cthumb($rule['goods_image'], 60);?>" alt="<?php echo $rule['mansong_goods_name'];?>"> </a>&nbsp;。
                <?php } ?>
                </span>
                <?php } ?>
              </dd>
              <dd class="nc-mansong-remark"><?php echo $output['mansong_info']['remark'];?></dd>
            </dl>
          </div>
          <?php } ?>
          <!--E 满就送 -->
          <?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
          <ul class="nc-goods-sort">
            <li>商家货号：<?php echo $output['goods']['goods_serial'];?></li>
            <?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php foreach ($output['goods']['goods_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
            <?php }?>
          </ul>
          <?php }?>
          <div class="ncs-goods-info-content">
            <?php if (isset($output['plate_top'])) {?>
            <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['goods']['goods_body'];?></div>
            <?php if (isset($output['plate_bottom'])) {?>
            <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
            <?php }?>
          </div>
        </div>
      </div>
      <div class="ncs-comment">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsRate">
          <div class="top">
            <div class="rate">
              <p><strong><?php echo $output['goods_evaluate_info']['good_percent'];?></strong><sub>%</sub>好评</p>
              <span>共有<?php echo $output['goods_evaluate_info']['all'];?>人参与评分</span></div>
            <div class="percent">
              <dl>
                <dt>好评<em>(<?php echo $output['goods_evaluate_info']['good_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['good_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>中评<em>(<?php echo $output['goods_evaluate_info']['normal_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['normal_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>差评<em>(<?php echo $output['goods_evaluate_info']['bad_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['bad_percent'];?>%"></i></dd>
              </dl>
            </div>
            <div class="btns"><span>您可对已购商品进行评价</span>
              <p><a href="<?php if ($output['goods']['is_virtual']) { echo urlShop('member_vr_order', 'index');} else { echo urlShop('member_order', 'index');}?>" class="ncs-btn ncs-btn-blue" target="_blank"><i class="icon-comment-alt"></i>评价商品</a></p>
            </div>
          </div>
          <div class="ncs-goods-title-nav">
            <ul id="comment_tab">
              <li data-type="all" class="current"><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?>(<?php echo $output['goods_evaluate_info']['all'];?>)</a></li>
              <li data-type="1"><a href="javascript:void(0);">好评(<?php echo $output['goods_evaluate_info']['good'];?>)</a></li>
              <li data-type="2"><a href="javascript:void(0);">中评(<?php echo $output['goods_evaluate_info']['normal'];?>)</a></li>
              <li data-type="3"><a href="javascript:void(0);">差评(<?php echo $output['goods_evaluate_info']['bad'];?>)</a></li>
            </ul>
          </div>
          <!-- 商品评价内容部分 -->
          <div id="goodseval" class="ncs-commend-main"></div>
        </div>
      </div>
      <div class="ncg-salelog">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsTraded">
          <div class="top">
            <div class="price" id="price2"><?php echo $lang['goods_index_goods_price'];?><strong><?php echo $output['goods']['goods_price'];?></strong><?php echo $lang['goods_index_yuan'];?><span><?php echo $lang['goods_index_price_note'];?></span></div>
          </div>
          <!-- 成交记录内容部分 -->
          <div id="salelog_demo" class="ncs-loading"> </div>
        </div>
      </div>
      <div class="ncs-consult">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_goods_consult'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGuestbook">
          <!-- 咨询留言内容部分 -->
          <div id="consulting_demo" class="ncs-loading"> </div>
        </div>
      </div>
      <?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?>
      <div class="ncs-recommend">
        <div class="title">
          <h4><?php echo $lang['goods_index_goods_commend'];?></h4>
        </div>
        <div class="content">
          <ul>
            <?php foreach($output['goods_commend'] as $goods_commend){?>
            <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?>
            <li>
              <dl>
                <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><!--<?php echo $goods_commend['goods_name'];?>--><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
                <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
                <dd class="goods-price"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_price'];?></dd>
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
      </div>
      <?php }?>
    </div>
    <div class="ncs-sidebar">
      <div class="ncs-sidebar-container">
        <div class="title">
          <h4>商品二维码</h4>
        </div>
        <div class="content">
          <div class="ncs-goods-code">
            <p><img src="<?php echo goodsQRCode($output['goods']);?>"  title="商品原始地址：<?php echo urlShop('goods', 'index', array('goods_id'=>$output['goods']['goods_id']));?>"></p>
            <span class="ncs-goods-code-note"><i></i>扫描二维码，手机查看分享</span> </div>
        </div>
      </div>
     <?php include template('store/callcenter');?>
      <?php include template('store/left');?>
    </div>
  </div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<!-- <!-- 商品图新版 --> -->
<script type="text/javascript">
$(document).ready(function(){
	// 图片上下滚动
	var count = $("#imageMenu li").length - 4; /* 显示 5 个 li标签内容 */
	var interval = $("#imageMenu li:first").width();
	var curIndex = 0;
	
	$('.scrollbutton').click(function(){
		if( $(this).hasClass('disabled') || count <= 1) return false;
		
		if ($(this).hasClass('smallImgUp')) --curIndex;
		else ++curIndex;
		
		$('.scrollbutton').removeClass('disabled');
		if (curIndex == 0) $('.smallImgUp').addClass('disabled');
		if (curIndex == count-1) $('.smallImgDown').addClass('disabled');
		
		$("#imageMenu ul").stop(false, true).animate({"marginLeft" : -curIndex*interval + "px"}, 600);
	});	
	// 解决 ie6 select框 问题
	$.fn.decorateIframe = function(options) {
        if ($.browser.msie && $.browser.version < 7) {
            var opts = $.extend({}, $.fn.decorateIframe.defaults, options);
            $(this).each(function() {
                var $myThis = $(this);
                //创建一个IFRAME
                var divIframe = $("<iframe />");
                divIframe.attr("id", opts.iframeId);
                divIframe.css("position", "absolute");
                divIframe.css("display", "none");
                divIframe.css("display", "block");
                divIframe.css("z-index", opts.iframeZIndex);
                divIframe.css("border");
                divIframe.css("top", "0");
                divIframe.css("left", "0");
                if (opts.width == 0) {
                    divIframe.css("width", $myThis.width() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                if (opts.height == 0) {
                    divIframe.css("height", $myThis.height() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                divIframe.css("filter", "mask(color=#fff)");
                $myThis.append(divIframe);
            });
        }
    }
    $.fn.decorateIframe.defaults = {
        iframeId: "decorateIframe1",
        iframeZIndex: -1,
        width: 0,
        height: 0
    }
    //放大镜视窗
    $("#bigView").decorateIframe();
    //点击到中图
    var midChangeHandler = null;
	
    $("#imageMenu li img").bind("click", function(){
		if ($(this).attr("id") != "onlickImg") {
			midChange($(this).attr("src").replace("_60.", "_360."));
			$("#imageMenu li").removeAttr("id");
			$(this).parent().attr("id", "onlickImg");
		}
	}).bind("mouseover", function(){
		if ($(this).attr("id") != "onlickImg") {
			window.clearTimeout(midChangeHandler);
			midChange($(this).attr("src").replace("_60.", "_360."));
			$(this).css({ "border": "3px solid #55a4e0" });
		}
	}).bind("mouseout", function(){
		if($(this).attr("id") != "onlickImg"){
			$(this).removeAttr("style");
			midChangeHandler = window.setTimeout(function(){
				midChange($("#onlickImg img").attr("src").replace("_60.", "_360."));
			}, 1000);
		}
	});
    function midChange(src) {
        $("#midimg").attr("src", src).load(function() {
            changeViewImg();
        });
    }
    //大视窗看图
    function mouseover(e) {
        if ($("#winSelector").css("display") == "none") {
            $("#winSelector,#bigView").show();
        }
        $("#winSelector").css(fixedPosition(e));
        e.stopPropagation();
    }
    function mouseOut(e) {
        if ($("#winSelector").css("display") != "none") {
            $("#winSelector,#bigView").hide();
        }
        e.stopPropagation();
    }
    $("#midimg").mouseover(mouseover); //中图事件
    $("#midimg,#winSelector").mousemove(mouseover).mouseout(mouseOut); //选择器事件

    var $divWidth = $("#winSelector").width(); //选择器宽度
    var $divHeight = $("#winSelector").height(); //选择器高度
    var $imgWidth = $("#midimg").width(); //中图宽度
    var $imgHeight = $("#midimg").height(); //中图高度
    var $viewImgWidth = $viewImgHeight = $height = null; //IE加载后才能得到 大图宽度 大图高度 大图视窗高度

    function changeViewImg() {
        $("#bigView img").attr("src", $("#midimg").attr("src").replace("_360.", "_1280."));
    }
    changeViewImg();
    $("#bigView").scrollLeft(0).scrollTop(0);
    function fixedPosition(e) {
        if (e == null) {
            return;
        }
        var $imgLeft = $("#midimg").offset().left; //中图左边距
        var $imgTop = $("#midimg").offset().top; //中图上边距
        X = e.pageX - $imgLeft - $divWidth / 2; //selector顶点坐标 X
        Y = e.pageY - $imgTop - $divHeight / 2; //selector顶点坐标 Y
        X = X < 0 ? 0 : X;
        Y = Y < 0 ? 0 : Y;
        X = X + $divWidth > $imgWidth ? $imgWidth - $divWidth : X;
        Y = Y + $divHeight > $imgHeight ? $imgHeight - $divHeight : Y;

        if ($viewImgWidth == null) {
            $viewImgWidth = $("#bigView img").outerWidth();
            $viewImgHeight = $("#bigView img").height();
            if ($viewImgWidth < 200 || $viewImgHeight < 200) {
                $viewImgWidth = $viewImgHeight = 800;
            }
            $height = $divHeight * $viewImgHeight / $imgHeight;
            $("#bigView").width($divWidth * $viewImgWidth / $imgWidth);
            $("#bigView").height($height);
        }
        var scrollX = X * $viewImgWidth / $imgWidth;
        var scrollY = Y * $viewImgHeight / $imgHeight;
        $("#bigView img").css({ "left": scrollX * -1, "top": scrollY * -1 });
        $("#bigView").css({ "top": 0, "left": $(".preview").width() + 15 });
        console.log($(".preview").width())

        return { left: X, top: Y };
    }
});
</script>
<!-- 商品图新版结束 -->
<script type="text/javascript">

    //收藏分享处下拉操作
    jQuery.divselect = function(divselectid,inputselectid) {
      var inputselect = $(inputselectid);
      $(divselectid).mouseover(function(){
          var ul = $(divselectid+" ul");
          ul.slideDown("fast");
          if(ul.css("display")=="none"){
              ul.slideDown("fast");
          }
      });
      $(divselectid).live('mouseleave',function(){
          $(divselectid+" ul").hide();
      });
    };
$(function(){
	//赠品处滚条
	$('#ncsGoodsGift').perfectScrollbar({suppressScrollX:true});
    <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
    // 加入购物车
    $('a[nctype="addcart_submit"]').click(function(){
    	if (typeof(allow_buy) != 'undefined' && allow_buy === false) return ;
		if($("#id_value").attr("value")!=''){
			addcart($("#id_value").attr("value"), checkQuantity(),'addcart_callback');
		}else{
			addcart(<?php echo $output['goods']['goods_id'];?>, checkQuantity(),'addcart_callback');
		}
        //addcart(<?php echo $output['goods']['goods_id'];?>, checkQuantity(),'addcart_callback');
    });
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        // 立即购买
        $('a[nctype="buynow_submit"]').click(function(){
          /**
          *1元购活动 用户重复购买1元购商品提示
          *wugangjian 20160911 ATART
          */
          <?php if ($output['goods_type'] == 2) {?>
            alert ('感谢您的支持，但是1元购专场商品每人只能购买一件哦~非常抱歉');
            return false;
          <?php }?>
          /**
          *1元购活动 用户重复购买1元购商品提示
          *wugangjian 20160911 END
          */
        	if (typeof(allow_buy) != 'undefined' && allow_buy === false) return ;
			if($("#id_value").attr("value")!=''){
				buynow($("#id_value").attr("value"), checkQuantity());
			}else{
				buynow(<?php echo $output['goods']['goods_id'];?>, checkQuantity());
			}
            //buynow(<?php echo $output['goods']['goods_id']?>,checkQuantity());
        });
        <?php }?>
    <?php }?>
    // 到货通知
    <?php if ($output['goods']['goods_storage'] == 0 || $output['goods']['goods_state'] == 0) {?>
    $('a[nctype="arrival_notice"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '到货通知','<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id']));?>', 350);
        <?php }?>
    });
    <?php }?>
    <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
    $('a[nctype="appoint_submit"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '立即预约', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id'], 'type' => 2));?>', 350);
        <?php }?>
    });
    <?php }?>
    //浮动导航  waypoints.js
    $('#main-nav').waypoint(function(event, direction) {
        $(this).parent().parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });

    // 分享收藏下拉操作
    $.divselect("#handle-l");
    $.divselect("#handle-r");

    // 规格选择
    $('dl[nctype="nc-spec"]').find('a').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
            $(this).parents('ul:first').find('a').removeClass('hovered');
            $(this).addClass('hovered');
            checkSpec();
        });
    });

});

function checkSpec() {
    var spec_param = <?php echo $output['spec_list'];?>;	
    var store_goods = <?php echo $output['goods']['store_id']; ?>;
    var virtual = <?php echo $output['goods']['is_virtual'];?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {			//var g_id = (n.url).substr((n.url).indexOf('goods_id') + 9);						$("#ck_goods_id").val(g_id);
           //window.location.href = n.url;
	   var price = n.price.split("|"); 
           $(".cost-price").html("<strong>¥"+price[1]+"</strong>");
           $("#price1").html("<strong>¥"+price[0]+"</strong>");
           $("#price2").html("商&nbsp;城&nbsp;价"+"<strong>"+price[0]+"</strong>元<span>购买的价格不同可能是由于店铺往期促销活动引起的，详情可以咨询卖家</span>");
		   var id=n.url.split("/");
		   var id=id[id.length-1];
		   var id=id.split("-");
		   var id=id[id.length-1];
		   var id=id.split(".");
		   $("#id_value").val(id[0]);
                   /*edit by peiyu start*/
                    $("#salelog_demo").load('index.php?act=goods&op=salelog&goods_id='+id[0]+'&store_id='+store_goods+'&vr='+virtual, function(){
                        // Membership card
                         $(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
                    });
                   /*edit by peiyu stop*/
		   /* $.ajax({
			   url:window.location.href,
			   data:goods_id=3,
			   type:"get",
			   success
		   }) */
       }
    });
}

// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());
    if (quantity < 1) {
        alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return false;
    }
    max = parseInt($('[nctype="goods_stock"]').text());
    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
    max = <?php echo $output['goods']['virtual_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    <?php if (!empty($output['goods']['upper_limit'])) {?>
             max = <?php echo $output['goods']['upper_limit']; ?>;
                  /*限购bug修复*/
                  /*1元购 wugangjian 20160919 1元购商品已购买则跳转到列表页*/
                  my_buy_num = <?php echo $output['goods']['my_buy_num']; ?>;
                  can_buy=max-my_buy_num;
                  if(quantity > can_buy){
                        alert('最多限购'+max+'件,您已购买'+my_buy_num+'件');
                         return false;  
                   }
                 /*限购bug修复*/ 
        if(quantity > max){
            alert('最多限购'+max+'件');
            return false;
        }
    <?php } ?>
    if(quantity > max){
        alert("<?php echo $lang['goods_index_add_too_much'];?>");
        return false;
    }
    return quantity;
}

// 立即购买js
function buynow(goods_id,quantity){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#cart_id").val(goods_id+'|'+quantity);
    $("#buynow_form").submit();
<?php }?>
}
        /*edit by peiyu start 文件上传验证登录*/
        
        $("#pic").change(function(){ 
            $("#pic").attr("disabled","disabled");
        })
        //图片上传开始
        var upload_num = 0; // 上传图片成功数量
        $('#fileupload').fileupload({
            dataType: 'json',
            url: '<?php echo SHOP_SITE_URL;?>/index.php?act=goods&op=image_upload',
            change: function(e, data) {
                if(data.files.length > 4){
                    alert("最多支持一次上传4张图片");
                    $("#pic").attr("disabled",false); 
                    return false;
                }
            },
            drop: function(e, data) {
                if(data.files.length >4){
                    alert("最多支持一次上传4张图片");
                    $("#pic").attr("disabled",false); 
                    return false;
                }
            },
            add: function (e,data) {
        	$.each(data.files, function (index, file) {
                $('<div nctype=' + file.name.replace(/\./g, '_') + '><p>'+ file.name +'</p><p class="loading"></p></div>').appendTo('div[nctype="file_loading"]');
            });
        	data.submit();
            },
            done: function (e,data) {
            var param = data.result;
            $this = $('div[nctype="' + param.origin_file_name.replace(/\./g, '_') + '"]');
            $this.fadeOut(3000, function(){
                $(this).remove();
                if ($('div[nctype="file_loading"]').html() == '') {
                    //setTimeout("window.location.reload()", 1000);
                    $("#pic").attr("disabled",false); 
                    $('div[nctype="file_msg"]').empty();
                    upload_num = 0;
                }
            });
            if(param.state == 'true'){
                upload_num++;
                $('div[nctype="file_msg"]').html('<i class="icon-ok-sign">'+'</i>'+'<?php echo $lang['album_upload_complete_one'];?>'+upload_num+'<?php echo $lang['album_upload_complete_two'];?>');
            } else {
                $this.find('.loading').html(param.message).removeClass('loading');
            }
            }
        }); 

/*edit by peiyu stop*/

$(function(){
    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('nctype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['goods']['transport_id'];?>';
	    var url = 'index.php?act=goods&op=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data != 'undefined') {$('#nc_kd').html('运费<?php echo $lang['nc_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');}else{'<?php echo $lang['goods_index_trans_for_seller'];?>';}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#ncrecive').html($(_self).html());
	    });
    });
    $("#nc-bundling").load('index.php?act=goods&op=get_bundling&goods_id=<?php echo $output['goods']['goods_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    $("#salelog_demo").load('index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>&vr=<?php echo $output['goods']['is_virtual'];?>', function(){
        // Membership card
        //$(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
    });
	$("#consulting_demo").load('index.php?act=goods&op=consulting&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>', function(){
		// Membership card
		$(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
	});

/** goods.php **/
	// 商品内容部分折叠收起侧边栏控制
	$('#fold').click(function(){
  		$('.ncs-goods-layout').toggleClass('expanded');
	});
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabGoodsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabGoodsRate').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabGoodsTraded').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#ncGuestbook').css('display','');
		$('.hd').css('display','none');
	});
	//商品排行Tab切换
	$(".ncs-top-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-top-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
	//信用评价动态评分打分人次Tab切换
	$(".ncs-rate-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-rate-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});

//触及显示缩略图
	$('.goods-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);

	/* 商品购买数量增减js */
	// 增加
	$('.increase').click(function(){				
		num = parseInt($('#quantity').val());	
                pq = parseInt($('#purchase_quantity').val());	
                min = <?php echo $output['goods']['goods_min_order'];?>;
                plus = <?php echo $output['goods']['goods_per_plus'];?>;
                reduce = <?php echo $output['goods']['goods_per_plus'];?>;
                if(num>=pq && pq>0){			alert("最多限购"+pq+"件");			return false;		}
	    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
	    max = <?php echo $output['goods']['virtual_limit'];?>;	    
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }		
	    <?php } ?>
	    <?php if (!empty($output['goods']['upper_limit'])) {?>
	     max = <?php echo $output['goods']['upper_limit'];?>;
                      /*限购bug修复*/
                      my_buy_num = <?php echo $output['goods']['my_buy_num'];?>;
                      can_buy=max-my_buy_num;
                      if(num > can_buy){
                         alert('最多限购'+max+'件,您已购买'+my_buy_num+'件');
	        return false;  
                      }					  
                    /*限购bug修复*/ 
                    
                      if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
            
            
	    <?php } ?>
		max = parseInt($('[nctype="goods_stock"]').text());
		if(num < max){
                    if(plus!=''){
                        $('#quantity').val(num+plus);
                    }else{
			$('#quantity').val(num+1);
                    }
		}
	});
	//减少
	$('.decrease').click(function(){
		num = parseInt($('#quantity').val());
		if(num-reduce >= min){
			$('#quantity').val(num-reduce);
		}
	});
	//自定义数量的设置
	$("#quantity").blur(function(){
		num = parseInt($('#quantity').val());
		min = <?php echo $output['goods']['goods_min_order'];?>;
		storage=<?php echo $output['goods']['goods_storage'];?>;
		if(num<=min){
			$('#quantity').val(min);
		}
		if(num>=storage){
			$('#quantity').val(storage);
		}
	})
    //评价列表
    $('#comment_tab').on('click', 'li', function() {
        $('#comment_tab li').removeClass('current');
        $(this).addClass('current');
        load_goodseval($(this).attr('data-type'));
    });
    load_goodseval('all');
    function load_goodseval(type) {
        var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id']));?>';
        /*edit by peiyu start 修改评论的机制，根据goods_commonsid来查询*/
        //var url = '<?php echo urlShop('goods', 'comments', array('goods_commonid' => $output['goods']['goods_id']));?>';
        /*edit by peiyu stop*/
        url += '&type=' + type;
        $("#goodseval").load(url, function(){
            $(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
        });
    }

    //记录浏览历史
	$.get("index.php?act=goods&op=addbrowse",{gid:<?php echo $output['goods']['goods_id'];?>});
	//初始化对比按钮
	initCompare();
	
	//分期付款
	$(".stagbuy").click(function(){
		$(".stag-pop-box").show();
	});
	$(".ncs-close-stag").click(function(){
		$(".stag-pop-box").hide();
	});
	$(".stag-select-month").change(function(){
		var month_index = $(this).get(0).selectedIndex + 1;
		$(".view-stag-money").find("ul").removeClass("fontred");
		$(".view-stag-money").find("ul").eq(month_index).addClass("fontred");
	});
	$(".shoufutxt").bind('input propertychange', function() {
    	$(".money-sf").html($(this).val());
	});
	
});
/* 加入购物车后的效果函数 */
function addcart_callback(data){
	var btnT = $('a[nctype="addcart_submit"]').offset().top,
		btnL = $('a[nctype="addcart_submit"]').offset().left+50,
		cartT = $('#js_mainPanel .cart').offset().top+10,
		cartL = $('#js_mainPanel .cart').offset().left+5;
	var html = '<div class="goods-flow" style="position:absolute;left:'+btnL+'px;top:'+btnT+'px;z-index:10000;height:30px;width:30px;border-radius:100%;overflow:hidden;"><img height="30" width="30" src="<?php echo $output["goods_image"]["0"]["1"] ?>"></div>';
	//var cart;
	$('body').append(html);
	$('.goods-flow').animate({
		'top':($(document).scrollTop()+30) + 'px',
		'left':btnL+(cartL-btnL)/2+'px'},
		500,
		function(){ 
			$('.goods-flow').animate({
			'top':cartT,
			'left':cartL},
			500,
			function(){ 
				$('.goods-flow').remove();
				$('#bold_num').html(data.num);
			    $('#bold_mly').html(price_format(data.amount));
			    $('.ncs-cart-popup').fadeIn('fast');
			});
	})
	
}
</script>
