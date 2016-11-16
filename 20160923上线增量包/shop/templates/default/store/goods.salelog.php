<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){
		$('#salelog_demo').find('.demo').ajaxContent({
			event:'click', //mouseover
			loaderType:"img",
			loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
			target:'#salelog_demo'
		});

});
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mt10">
  <thead>
    <tr>
      <th class="w200"><?php echo $lang['goods_index_buyer'];?></th>
      <th class="w100"><?php echo $lang['goods_index_buy_price'];?></th>
      <th class=""><?php echo $lang['goods_index_buy_amount'];?></th>
      <th class="w200"><?php echo $lang['goods_index_buy_time'];?></th>
    </tr>
  </thead>
  <?php if(!empty($output['sales']) && is_array($output['sales'])){?>
  <tbody>
     <!--eidt by peiyu start 隐藏部分名字-->
    <?php 
            function substr_cut($user_name){
                $strlen     = mb_strlen($user_name, 'utf-8');
                $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
                $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
                return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
            }
      ?>
    <!--eidt by peiyu stop 隐藏部分名字-->
    <?php foreach($output['sales'] as $key=>$sale){ ?>
    <tr>
      <td><a href="index.php?act=member_snshome&mid=<?php echo $sale['buyer_id'];?>" target="_blank" data-param="{'id':<?php echo $sale['buyer_id'];?>}" nctype="mcard"><?php echo substr_cut($sale['buyer_name']);?></a></td>
      <td><em class="price"><?php echo $lang['currency'].$sale['goods_price'];?></em> <i style="color:red;"><?php echo $output['order_type'][$sale['goods_type']];?></i></td>
      <td><?php echo $sale['goods_num'];?></td>
      <td><time><?php echo date('Y-m-d H:i:s', $sale['add_time']);?></time></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="10" class="tr" ><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
    </tr>
  </tfoot>
  <?php }else{?>
  <tbody>
    <tr>
      <td colspan="10" class="ncs-norecord"><?php echo $lang['no_record'];?></td>
    </tr>
  </tbody>
  <?php }?>
</table>
