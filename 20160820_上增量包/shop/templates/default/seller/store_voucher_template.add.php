<link type="text/css" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL."/js/jquery-ui/themes/ui-lightness/jquery.ui.css";?>"/>
<link type="text/css" rel="stylesheet" href="<?php echo "templates/default/css/pc_cc8af8afaf.css";?>"/>
<style>

	.app-attr{
		float:right;
		width:55%;
	}
	.app-design{
		float:left;
		width:45%;
		background-color:#fff;
	}
	.app-preview{
		margin:10px;
	
	}
	.app-header2{height:70px;background:url("v2/image/widget/showcase/iphone_head.png") no-repeat center center}
	

</style>



  <div class="tabmenu">

    <?php include template('layout/submenu');?>

  </div>

	<div class="ncsc-form-default">

	  <form id="add_form" method="post" enctype="multipart/form-data">

	  	<input type="hidden" id="act" name="act" value="store_voucher"/>

	  	<?php if ($output['type'] == 'add'){?>

	  	<input type="hidden" id="op" name="op" value="templateadd"/>

	  	<?php }else {?>

	  	<input type="hidden" id="op" name="op" value="templateedit"/>

	  	<input type="hidden" id="tid" name="tid" value="<?php echo $output['t_info']['voucher_t_id'];?>"/>

	  	<?php }?>

	  	<input type="hidden" id="form_submit" name="form_submit" value="ok"/>
		<div>
<!--  app 预览  -->
            <div class="app-design">预览
            <div class="app-preview">
                    <div class="app-header"></div>
                    <div class="app-entry">
                        <div class="app-config js-config-region"><div class="app-field clearfix editing"><h1><span>优惠券</span></h1>
            <div class="app-field-wrap editing">

                <div class="ump-coupon-detail-wrap">
                    <div class="promote-card">
                        <div class="clearfix">
                            <p class="pull-left font-size-16 promote-card-name">优惠券标题</p>
                            
                                <!--<p class="pull-right font-size-14 center promote-share transparent-color js-share">分享</p>-->
                            
                        </div>
                        <p class="center promote-value">
                            <span class="promote-value-sign">
                                <span>￥</span>
                                <i class="v-price">
                                     0.00
                                    
                                </i>
                            </span>
                        </p>
                        <p class="center font-size-14 promote-limit">
                            
                                订单满 xx 元 (含运费)
                            
                        </p>
                        <p class="center font-size-14 transparent-color v-date">
                            有效日期：20xx : 00 : 00 - 20xx : 00 : 00
                            
                        </p>
                        <div class="dot"></div>
                    </div>
            
                    <div class="promote-descs">
                        <p class="font-size-14 c-gray-dark promote-desc-title">使用说明</p>
                        <div class="block">
                            <div class="block-item clearfix">
                                <span class="js-desc-detail"> 
                                    
                                        暂无使用说明……
                                    
                                </span>
                                <br>
                                <br>
                                <span>
                                特别声明：<br>
                                    1.本红包仅限在实体店晨光文具乐清4S店和样样抵线上商城使用。门店地址：乐清市宁康西路138号华 仪电商园内。电话：0577-61577877
                                    <br>
                                    2.本红包需进店出示使用，截屏、复制、修改无效。
                                    <br>
                                    3.本红包不设找零，不可兑现现金。且不能与其他优惠同时使用。
                                    <br>
                                    4.红包抵扣部分的货款不能开具发票。不适用于团体购买、企业用户购买、批发及其他非以个人消费为 目的的购买行为。
                                    <br>
                                    5.本活动未尽事宜以店铺宣传为准，最终解释权归样样抵—晨光文具乐清4S店。
                                </span>
                                <!--<a class="c-blue more-desc pull-right js-more-desc" href="javascript:void(0)">更多</a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            </div></div>
                        <div class="app-fields js-fields-region"><div class="app-fields ui-sortable"></div></div>
                    </div>
                    <div class="js-add-region"><div></div></div>
                </div>
            </div>
<!--   -->
           <div class="app-attr">
    
            <dl>
    
              <dt><i class="required">*</i><?php echo $lang['voucher_template_title'].$lang['nc_colon']; ?></dt>
    
              <dd>
    
                <input type="text" class="w300 text" name="txt_template_title" value="<?php echo $output['t_info']['voucher_t_title'];?>">
    
                <span></span>
    
              </dd>
    
            </dl>
         <!--edit by peiyu 增加代金券适用平台的id和代金券种类的id start-->   
            <dl>
    
              <dt><i class="required">*</i><?php echo "代金卷类别："; ?></dt>
    
              <dd>

		<input type="radio"  name="voucher_t_channel" value="0"  <?php if($output['t_info']['voucher_t_channel'] && $output['t_info']['voucher_t_channel']==1){echo "checked";}?>  /> <label> 微信代金券</label> &nbsp;

                <input type="radio"   name="voucher_t_channel" value="1"  <?php if($output['t_info']['voucher_t_channel'] && $output['t_info']['voucher_t_channel']==1){echo "checked";}?> /><label> web代金券</label>
                
                <input type="radio"   name="voucher_t_channel" value="2"  <?php if($output['t_info']['voucher_t_channel'] && $output['t_info']['voucher_t_channel']==2){echo "checked";}?> /><label> 无平台限制代金券</label>
    
                <span></span>
    
              </dd>
    
            </dl>
            
            <dl>
    
              <dt><i class="required">*</i><?php echo "代金券的种类:"; ?></dt>
    
              <dd>

		<input type="radio"  name="voucher_t_kind" value="0"  <?php if($output['t_info']['voucher_t_kind']==0){echo "checked";}?>/> <label>通用代金券</label>&nbsp;

                <input type="radio"   name="voucher_t_kind" value="1" <?php if($output['t_info']['voucher_t_kind']==1){echo "checked";}?>/><label>抵扣代金券</label>&nbsp;
                
                <span></span>
    
              </dd>
    
            </dl>
            <!--edit stop-->
    
    
            <?php if ($output['isOwnShop']) { ?>
    
           
            <dl>
    
              <dt><i class="required">*</i>代金券所属：</dt>
    
              <dd>
    
                <select name="sc_id" >
   
                   <option value="0" <?php if($output['t_info']['voucher_t_sc_id']==0){echo "checked";}?>>全部</option>
    
                   <?php foreach ($output['store_class'] as $k=>$v){?>
    
                        <option value="<?php echo $v['gc_id'];?>" <?php if ($output['t_info']['voucher_t_sc_id']==$v['gc_id']){ echo 'selected';}?>><?php echo $v['gc_name'];?></option>
                   
                    <?php }?>
    
                </select>
    
                <span></span>
    
              </dd>
    
            </dl>
    
            <?php } else {?>
    
            <input type="hidden" name="sc_id" value="<?php echo $output['store_info']['sc_id'];?>"/>
    
            <?php }?>
    
            <dl>
    
              <dt><i class="required">*</i><?php echo $lang['voucher_template_price'].$lang['nc_colon']; ?></dt>
    
              <dd>
    
    			     <input type="text" class="w70 text" name="select_template_price" value="<?php echo $output['t_info']['voucher_t_price'] ?>" /> 
             		 <span class="price-max" <?php if($output['t_info']['is_random']!=1){?> style="display:none" <?php } ?>  > 
                     	至 <input type="text" class="w70 text" name="template_price_max" value="<?php echo $output['t_info']['voucher_t_price_max'] ?>" />
                     </span><em class="add-on"><i class="icon-renminbi"></i></em>
                     <label><input type="checkbox" id="is_random" name="is_random" value="1" <?php if($output['t_info']['is_random']==1){echo "checked" ;} ?>> 随机</label>   		 <span  class="price-max" style="display:none"><label><input type="checkbox" id="is_mod_5" name="is_mod_5" value="1" <?php if($output['t_info']['is_mod_5']==1){echo "checked" ;} ?>> 5的倍数</label> </span>  			  
                  
<!--                <select id="select_template_price" name="select_template_price" class="w80 vt">
    
                  <?php if(!empty($output['pricelist'])) { ?>
    
                    <?php foreach($output['pricelist'] as $voucher_price) {?>
    
                    <option value="<?php echo $voucher_price['voucher_price'];?>" <?php echo $output['t_info']['voucher_t_price'] == $voucher_price['voucher_price']?'selected':'';?>><?php echo $voucher_price['voucher_price'];?></option>
    
                  <?php } } ?>
    
                </select><em class="add-on"><i class="icon-renminbi"></i></em>-->
    
                <span></span>
    
              </dd>
    
            </dl>
    
            <dl>
    
              <dt><i class="required">*</i><?php echo $lang['voucher_template_orderpricelimit'].$lang['nc_colon']; ?></dt>
    
              <dd>
    
                <input type="text" name="txt_template_limit" class="text w70" value="<?php echo $output['t_info']['voucher_t_limit'];?>"><em class="add-on"><i class="icon-renminbi"></i></em>
    
                <span>抵扣券：消费金额不得低于红包金额&nbsp;通用券直接0</span>
    
              </dd>
    
            </dl>
            
            <dl>

              <dt><i class="required">*</i><em class="pngFix"></em>生效时间:</dt>
    
              <dd>
              
                    <input type="text" class="text w70" id="txt_template_startdate" name="txt_template_startdate" value="<?php  if(!empty($output['t_info']['voucher_t_start_date'])){echo date("Y-m-d",$output['t_info']['voucher_t_start_date']);} ?>" readonly><em class="add-on"><i class="icon-calendar"></i></em>
    		  
              </dd>
    			
                
    
              <dt><i class="required">*</i><em class="pngFix"></em>结束时间:<!--<?php echo $lang['voucher_template_enddate'].$lang['nc_colon']; ?>--></dt>
    
              <dd>
              
                <input type="text" class="text w70" id="txt_template_enddate" name="txt_template_enddate" value="<?php if(!empty($output['t_info']['voucher_t_end_date'])){echo date("Y-m-d",$output['t_info']['voucher_t_end_date']);} ?>" readonly><em class="add-on"><i class="icon-calendar"></i></em>
    
                <span></span><p class="hint">
    
<!--    <?php if ($output['isOwnShop']) { ?>
    
                留空则默认30天之后到期
    
    <?php } else { ?>
    
                <?php echo $lang['voucher_template_enddate_tip'];?><?php echo @date('Y-m-d',$output['quotainfo']['quota_starttime']);?> ~ <?php echo @date('Y-m-d',$output['quotainfo']['quota_endtime']);?>
    
    <?php } ?>-->
    
                </p>
    
              </dd>
    
            </dl>
    
            <dl>
    
              <dt><i class="required">*</i><?php echo $lang['voucher_template_total'].$lang['nc_colon']; ?></dt>
    
              <dd>
    
                <input type="text" class="w70 text" name="txt_template_total" value="<?php echo $output['t_info']['voucher_t_total']; ?>">
    
                <span></span>
    
              </dd>
    
            </dl>
    
            <dl>
    
              <dt><i class="required">*</i><?php echo $lang['voucher_template_eachlimit'].$lang['nc_colon']; ?></dt>
    
              <dd>
    
                <select name="eachlimit" class="w80">

                    <option value="0"><?php echo $lang['voucher_template_eachlimit_item'];?></option>
    
                    <?php for($i=1;$i<=intval(C('promotion_voucher_buyertimes_limit'));$i++){?>
    
                    <option value="<?php echo $i;?>" <?php echo $output['t_info']['voucher_t_eachlimit'] == $i?'selected':'';?>><?php echo $i;?><?php echo $lang['voucher_template_eachlimit_unit'];?></option>
    
                    <?php }?>
    
                </select>
    
              </dd>
    
            </dl>
    
            <dl>
    
              <dt><i class="required">*</i>使用说明:<!--<?php echo $lang['voucher_template_describe'].$lang['nc_colon']; ?>--></dt>
    
              <dd>
    
                <textarea  name="txt_template_describe" class="textarea w400 h600"><?php echo $output['t_info']['voucher_t_desc'];?></textarea>
    
                <span></span>
    
              </dd>
    
            </dl>
    
            <dl>
    
              <dt><?php echo $lang['voucher_template_image'].$lang['nc_colon']; ?></dt>
    
              <dd>
    
              <div id="customimg_preview" class="ncsc-upload-thumb voucher-pic"><p><?php if ($output['t_info']['voucher_t_customimg']){?>
    
                    <img src="<?php echo $output['t_info']['voucher_t_customimg'];?>"/>
    
                    <?php }else {?>
    
                    <i class="icon-picture"></i>
    
                    <?php }?></p>
    
                </div>
    
                <div class="ncsc-upload-btn"><a href="javascript:void(0);"><span>
    
              <input type="file" hidefocus="true" size="1" class="input-file" name="customimg" id="customimg" nc_type="customimg"/>
    
              </span>
    
              <p><i class="icon-upload-alt"></i>图片上传</p>
    
              </a> </div>
    
              <p class="hint"><?php echo $lang['voucher_template_image_tip'];?></p>
    
              </dd>
    
           </dl>
    
              <?php if ($output['type'] == 'edit'){?>
    
              <dl>
    
                <dt><em class="pngFix"></em><?php echo $lang['nc_status'].$lang['nc_colon']; ?></dt>
    
                <dd>
    
                    <input type="radio" value="<?php echo $output['templatestate_arr']['usable'][0];?>" name="tstate" <?php echo $output['t_info']['voucher_t_state'] == $output['templatestate_arr']['usable'][0]?'checked':'';?>> <?php echo $output['templatestate_arr']['usable'][1];?>
    
                    <input type="radio" value="<?php echo $output['templatestate_arr']['disabled'][0];?>" name="tstate" <?php echo $output['t_info']['voucher_t_state'] == $output['templatestate_arr']['disabled'][0]?'checked':'';?>> <?php echo $output['templatestate_arr']['disabled'][1];?>
    
                </dd>
    
            </dl>
           </div>
		</div>
	    <?php }?>

	    <div class="bottom">

	      <label class="submit-border"><input id='btn_add' type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>" /></label>

	      </div>
	  </form>

	</div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>

<script>

//判断是否显示预览模块

<?php if (!empty($output['t_info']['voucher_t_customimg'])){?>

$('#customimg_preview').show();

<?php }?>

var year = <?php echo date('Y',$output['quotainfo']['quota_endtime']);?>;

var month = <?php echo intval(date('m',$output['quotainfo']['quota_endtime']));?>;

var day = <?php echo intval(date('d',$output['quotainfo']['quota_endtime']));?>;



$(document).ready(function(){
	
		if($("input[name='txt_template_title']").val()!=""){
			$(".promote-card-name").text($("input[name='txt_template_title']").val());
		}	

		if($("#is_random").prop("checked")==true){
			if($("input[name='template_price_max']").val()!=""){
				$(".v-price").text($("input[name='select_template_price']").val() + " ~ " + $("input[name='template_price_max']").val());
			}
		}else{
			if($("input[name='select_template_price']").val()!=""){
				$(".v-price").text($("input[name='select_template_price']").val());
			}
		}

		 if($("textarea[name='txt_template_describe']").val()!=""){
			$(".js-desc-detail").html($("textarea[name='txt_template_describe']").val().replace(/\n|\r\n/g,"<br>"));
		 }

		if($("input[name='txt_template_limit']").val()!=""){
			if($("input[name='txt_template_limit']").val()==0){$(".promote-limit").text("无限制");}else{
				$(".promote-limit").text("订单满 " + $("input[name='txt_template_limit']").val() + " 元（含运费）");
			}
		}
		if($("input[name='txt_template_startdate']").val()!="" && $("input[name='txt_template_enddate']").val()!=""){
			$(".v-date").text("有效日期：" + $("input[name='txt_template_startdate']").val() + " 00 : 00  -  " + $("input[name='txt_template_enddate']").val() + " 00 : 00");
		}

		 
    //日期控件

    $('#txt_template_enddate').datepicker();
	$('#txt_template_startdate').datepicker();
	
	$("#is_random").click(function(){
		if($(this).prop("checked")==true){
			$(".price-max").show();
		}else{
			$(".price-max").hide();
		}
	});

	
	$("input[name='txt_template_title']").blur(function(){
		if($("input[name='txt_template_title']").val()!=""){
			$(".promote-card-name").text($("input[name='txt_template_title']").val());
		}	
	});

	$("input[name='select_template_price']").blur(function(){
		if($("#is_random").prop("checked")==true){
			$(".v-price").text($("input[name='select_template_price']").val() + " ~ " + $("input[name='template_price_max']").val());
		}else{
			$(".v-price").text($("input[name='select_template_price']").val());
		}
	});
	
	$("textarea[name='txt_template_describe']").blur(function(){
		 if($("textarea[name='txt_template_describe']").val()!=""){
			$(".js-desc-detail").html($("textarea[name='txt_template_describe']").val().replace(/\n|\r\n/g,"<br>"));
		 }
	});
	$("input[name='template_price_max']").blur(function(){
			$(".v-price").text($("input[name='select_template_price']").val() + " ~ " + $("input[name='template_price_max']").val());
	});
	
	$("input[name='txt_template_limit']").blur(function(){
			if($("input[name='txt_template_limit']").val()==0){$(".promote-limit").text("无限制");}else{
				$(".promote-limit").text("订单满 " + $("input[name='txt_template_limit']").val() + " 元（含运费）");
			}
	});
	
	$("input[name='txt_template_startdate']").change(function(){
			$(".v-date").text("有效日期：" + $("input[name='txt_template_startdate']").val() + " 00 : 00  -  " + $("input[name='txt_template_enddate']").val() + " 00 : 00");
	});
	$("input[name='txt_template_enddate']").change(function(){
			$(".v-date").text("有效日期：" + $("input[name='txt_template_startdate']").val() + " 00 : 00  -  " + $("input[name='txt_template_enddate']").val() + " 00 : 00");
	});
	

    var currDate = new Date();

    var date = currDate.getDate();

    date = date + 1;

    currDate.setDate(date);

    

    $('#txt_template_enddate').datepicker( "option", "minDate", currDate);

<?php if (!$output['isOwnShop']) { ?>

    $('#txt_template_enddate').datepicker( "option", "maxDate", new Date(year,month-1,day));

<?php } ?>





    $('#txt_template_enddate').val("<?php echo $output['t_info']['voucher_t_end_date']?@date('Y-m-d',$output['t_info']['voucher_t_end_date']):'';?>");

    $('#customimg').change(function(){

		var src = getFullPath($(this)[0]);

		if(navigator.userAgent.indexOf("Firefox")>0){

			$('#customimg_preview').show();

			$('#customimg_preview').children('p').html('<img src="'+src+'">');

		}

	});

    //表单验证

    $('#add_form').validate({

        errorPlacement: function(error, element){

	    	var error_td = element.parent('dd').children('span');

			error_td.append(error);

	    },

        rules : {

            txt_template_title: {

                required : true,

                rangelength:[0,100]

            },

            txt_template_total: {

                required : true,

                digits : true

            },

            txt_template_limit: {

                required : true,

                number : true

            },

            txt_template_describe: {

                required : true

            }

        },

        messages : {

            txt_template_title: {

                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_title_error'];?>',

                rangelength : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_title_error'];?>'

            },

            txt_template_total: {

                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_total_error'];?>',

                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_total_error'];?>'

            },

            txt_template_limit: {

                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_limit_error'];?>',

                number : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_limit_error'];?>'

            },

            txt_template_describe: {

                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['voucher_template_describe_error'];?>'

            }

        }

    });

});

</script>