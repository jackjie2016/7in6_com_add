<?php defined('InShopNC') or exit('Access Invalid!');?>

  <div class="title">
	  <i></i>
    <h3><a href="<?php echo urlShop('category', 'index');?>"><?php echo $lang['nc_all_goods_class'];?></a></h3>
  </div>
      <div class="category">
        <ul class="menu">
          <?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
          <?php foreach ($output['show_goods_class'] as $key => $val) { $i++; ?>
          <li cat_id="<?php echo $val['gc_id'];?>" class="<?php echo $i%2==1 ? 'odd':'even';?>" <?php if($i>=15){?>style="display:none;"<?php }?>>
            <div class="class">
              <?php if(!empty($val['pic'])) { ?>
              <span class="ico"><img src="<?php echo $val['pic'];?>"></span>
              <?php } ?>
              <h4><a href="<?php echo urlShop('search','index',array('cate_id'=> $val['gc_id']));?>" style="font-size: 15px"><?php echo $val['gc_name'];?></a></h4>
              <!--
              <span class="recommend-class">
              <?php if (!empty($val['class3']) && is_array($val['class3'])) { ?>
              <?php foreach ($val['class3'] as $k => $v) { ?>
              <a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>" title="<?php echo $v['gc_name']; ?>"><?php echo $v['gc_name'];?></a>
              <?php } ?>
              <?php } ?>
              </span>-->
              <span class="arrow"></span> 
            </div>
            <div class="sub-class d_opacity" cat_menu_id="<?php echo $val['gc_id'];?>" >
              <?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
              <?php foreach ($val['class2'] as $k => $v) { ?>
              <dl >
                <dt>
                    <h3>
                        <?php	  
                        $pic_name = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-pic-'.$v['gc_id'].'.jpg';
                        if (file_exists($pic_name)) {
                            echo "<span class='ico'><img src='".UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-pic-'.$v['gc_id'].'.jpg'."'></span>";
                        }?>
                        <a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>"><?php echo $v['gc_name'];?></a>
                    </h3>
                </dt>
                <dd class="goods-class" >
                  <?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
                  <?php foreach ($v['class3'] as $k3 => $v3) { ?>
                  <a href="<?php echo urlShop('search','index',array('cate_id'=> $v3['gc_id']));?>"><?php echo $v3['gc_name'];?></a>
                  <?php } ?>
                  <?php } ?>
                </dd>
              </dl>
              <?php } ?>
              <?php } ?>
                <div class="right">
                    <a href="<?php echo $val['banner_ad_1_link'];?>">
                        <img src="<?php echo  UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-banner_ad_1-'.$val['gc_id'].'.jpg' ?>"/>
                    </a>
                    <b class="yimaginaryLine"></b>
                    <a href="<?php echo $val['banner_ad_2_link'];?>">
                        <img src="<?php echo  UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-banner_ad_2-'.$val['gc_id'].'.jpg' ?>"/>
                    </a>
                </div>
            </div>
          </li>
          <?php }}?>
        </ul>
      </div>