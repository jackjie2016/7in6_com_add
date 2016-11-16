<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <ul class="tab">
      <?php if(is_array($output['member_menu']) and !empty($output['member_menu'])) {
	foreach ($output['member_menu'] as $key => $val) {
		$classname = 'normal';
		if($val['menu_key'] == $output['menu_key']) {
			$classname = 'active';
		}
		if ($val['menu_key'] == 'image'){
			echo '<li class="'.$classname.'"><a href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
		}
	}
}?>
    </ul>
  </div>
    <div class="up_image">
        
    <div class="ncp-main-layout">
    
    <?php if (!empty($output['pic_list'])){?>
    <ul class="ncp-voucher-list">
      <?php foreach ($output['pic_list'] as $k=>$v){
      if(($k+1)%4==0){
      ?>
      <li style="margin-right:0px">
        <div class="ncp-voucher">
             <img id="aclass_cover" src="<?php echo getUpimages($v['member_id'],$v['apic_cover'],240); ?>" style='width:100%;height:100%'>
        </div>
      </li>
      <?php }else{?>
        <li>
            <div class="ncp-voucher">
                <img id="aclass_cover" src="<?php echo getUpimages($v['member_id'],$v['apic_cover'],240); ?>" style='width:100%;height:100%'>
            </div>
        </li>
      <?php }}?>
    </ul>
    <?php }else{?>
        <table class="ncm-default-table">
            <tfoot>
                 <tr>
                        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
                 </tr>
            </tfoot>
        </table>
    <?php }?>
     <p style='clear:both'></p>
    </div>
    <table class="ncm-default-table">
        <tfoot>
          <?php if (!empty($output['pic_list'])) { ?>      
          <tr>
            <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
          </tr>
          <?php } ?>
        </tfoot>
    </table>
    </div>
    </div>
    
</div>
