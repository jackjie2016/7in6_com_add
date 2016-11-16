<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['upload_set'];?></h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post" enctype="multipart/form-data" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">开启图片服务器:</label></td>
        </tr>
        <tr class="noborder">
        	<td class="vatop rowform onoff"><label for="upload_service_enabled_1" class="cb-enable <?php if($output['list_setting']['upload_service_enabled'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="upload_service_enabled_0" class="cb-disable <?php if($output['list_setting']['upload_service_enabled'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input type="radio" <?php if($output['list_setting']['upload_service_enabled'] == '1'){ ?>checked="checked"<?php } ?> value="1" name="upload_service_enabled" id="upload_service_enabled_1" />
            <input type="radio" <?php if($output['list_setting']['upload_service_enabled'] == '0'){ ?>checked="checked"<?php } ?> value="0" name="upload_service_enabled" id="upload_service_enabled_0" />
        	</td>
          	<td class="vatop tips">&nbsp;</td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label for="upload_service_type">服务器类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select id="upload_service_type" name="upload_service_type">
          		<option value="">-请选择-</option>
          		<option value="upyun" <?php if($output['list_setting']['upload_service_type'] == 'upyun')echo 'selected';?>>又拍云</option>
              <option value="oss" <?php if($output['list_setting']['upload_service_type'] == 'oss')echo 'selected';?>>阿里云Oss</option>
          	</select>
          </td>
          <td class="vatop tips">图片服务器引擎</td>
        </tr>

		    <tr>
          <td colspan="2" class="required"><label for="upload_service_host">服务器地址:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="upload_service_host" name="upload_service_host" value="<?php echo $output['list_setting']['upload_service_host'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">图片服务器的地址，比如v1.api.upyun.com，不含http://</span></td>
        </tr>

        <tr>
        	<td colspan="2" class="required"><label for="upload_service_username">用户:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="upload_service_username" name="upload_service_username" value="<?php echo $output['list_setting']['upload_service_username'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">图片服务器提供的上传的用户名</span></td>
        </tr>

        <tr>
        	<td colspan="2" class="required"><label for="upload_service_password">密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="upload_service_password" name="upload_service_password" value="<?php echo $output['list_setting']['upload_service_password'];?>" class="txt" type="password" /></td>
          <td class="vatop tips"><span class="vatop rowform">图片服务器提供的上传的密码</span></td>
        </tr>

        <tr>
        	<td colspan="2" class="required"><label for="upload_service_bucket">空间名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="upload_service_bucket" name="upload_service_bucket" value="<?php echo $output['list_setting']['upload_service_bucket'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">需要上传至服务器的空间名</span></td>
        </tr>

        <tr>
        	<td colspan="2" class="required"><label for="upload_service_domain">绑定域名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="upload_service_domain" name="upload_service_domain" value="<?php echo $output['list_setting']['upload_service_domain'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">上传图片的根域名，末尾不含“/”</span></td>
        </tr>

      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
//<!CDATA[
$(function(){
	$('#form').validate({
		rules : {
			image_max_size : {
				number : true,
				maxlength : 4
			},
			image_allow_ext : {
				required : true
			}
		},
		messages : {
			image_max_size : {
				number : '<?php echo $lang['image_max_size_only_num'];?>',
				maxlength : '<?php echo $lang['image_max_size_c_num'];?>'
			},
			image_allow_ext : {
				required : '<?php echo $lang['image_allow_ext_not_null'];?>'
			}
		}
	});
});
//]]>
</script>