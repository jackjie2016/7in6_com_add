<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.public-top-layout, .head-app, .head-search-bar, .head-user-menu, .public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}
.public-head-layout {
	margin: 10px auto -10px auto;
}
.wrapper {
	width: 1000px;
}
#footer {
	border-top: none!important;
	padding-top: 30px;
}
</style>

<div class="nc-login-layout d_wd1000" style="min-height:720px;">
<!--登陆界面修改-->
<!--+++++++++++++++ 2015/06/16 +++++++++++++++-->
    <div class="d-regist-now">
      <span>您好，欢迎来到<br>
            已注册的会员请登录，或立即<a href="index.php?act=login&op=register&ref_url=<?php echo urlencode($output['ref_url']);?>">注册新会员</a></span>
    </div>
<!--++++++++++++++++++++++++++++++-->
  <div class="left-pic">
      <a href="/index.php?act=special&op=special_detail&special_id=10"><img src="<?php echo $output['lpic'];?>"  border="0"></a>
  </div>
  <div class="nc-login d_wd440">
    <i class="d_top_arrow"></i>
    <div class="nc-login-title d_bdno">
      <ul>
        <li class="d_login">
          <a href="javascript:;" class="d_curr"><?php echo $lang['login_index_user_login'];?><i></i></a>
        </li>
        <li class="d_login">
          <a href="javascript:;">手机动态码登录<i></i></a>
        </li>
      </ul>
    </div>
    <div class="d_login_bder">
      <div class="d_login_mbd" style=" min-height:323px;">
        <!--====== 用户登录 begin=====-->
        <div class="nc-login-content d_bdno d_other" id="demo-form-site" style="position: absolute;">
          <form id="login_form" method="post" action="index.php?act=login&op=login" class="bg">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
            <dl class="d_item_mian">
              <dt class="d_item_tle"><?php echo $lang['login_index_username'];?>：</dt>
              <dd class="d_item_tle_con">
                <input type="text" class="text d_in" autocomplete="off"  name="user_name" id="user_name" autofocus placeholder="可使用已注册的用户名或手机号登录">
                <label></label>
              </dd>
            </dl>
            <dl class="d_item_mian">
              <dt class="d_item_tle"><?php echo $lang['login_index_password'];?>： </dt>
              <dd class="d_item_tle_con">
                <input type="password" class="text d_in" name="password" autocomplete="off"  id="password" placeholder="6-20个大小写英文字母、符号或数字">
				<label></label>
              </dd>
            </dl>
            <?php if(C('captcha_status_login') == '1') { ?>
            <div class="d_ym_main mt15 code-div">
              <dl class="d_yanzh d_wd228">
                <dt class="d_item_tle"><?php echo $lang['login_index_checkcode'];?>：</dt>
                <dd class="d_item_tle_con d_wd100">
                  <input type="text" name="captcha" autocomplete="off" class="text d_in w100 fl" id="captcha" maxlength="4" size="10" placeholder="输入验证码" style="width: 100px !important;" />
                  <label></label>
                </dd>      
              </dl>
              <span class="d_yma">
                <img src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>" name="codeimage" border="0" id="codeimage" class="d_ym_ph"> 
                <a href="javascript:void(0)" class="d_next_ym" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
              </span>
            </div>
            <div class="d_auto_login">
              <span class="d_auto">
                <input type="checkbox" class="auto_checkbox" name="auto_login" style="vertical-align: middle;">&nbsp;七天自动登录
                <i class="d_tsh">请勿在公用电脑上使用</i>
              </span>
              <a class="forget d_fgt" href="index.php?act=login&op=forget_password"><?php echo $lang['login_index_forget_password'];?></a>
            </div>
            <?php } ?>
            <div class="mt15">
              <dd class="d_fno">
                <input type="submit" class="submit d_submit" value="<?php echo $lang['login_index_login'];?>">
               <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
              </dd>
            </div>
          </form>
		</div>
      <div class="nc-login-content d_bdno d_other hideflip" style="position: absolute; top: 0px;">
        <form id="d_phone_login" method="post" action="index.php?act=login&op=phone_login">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />		
          <dl class="d_item_mian">
            <dt class="d_item_tle">手机号：</dt>
            <dd class="d_item_tle_con">
              <input type="text" class="text d_in" autocomplete="off"  name="user_name" id="user_mobile" autofocus placeholder="可使用已注册的用户名或手机号登录">
              <label></label>
            </dd>
          </dl>

          <dl class="d_item_mian">
            <dt class="d_item_tle">动态码： </dt>
            <dd class="d_item_tle_con">
              <input type="text" class="text d_in" name="mobile_code" autocomplete="off"  id="mobile_code" placeholder="输入6位手机动态码">
              <label></label>
            </dd>
          </dl>
          <div class="tiptext" id="sms_text">
            点击<span><a href="javascript:;" id="getCode" onclick="register_code()"><i class="icon-mobile-phone" ></i>发送手机动态码</a></span>，查收短信将系统发送的“6位手机动态码”输入到下方验证后登录。
          </div>	  
      <script  type="text/javascript">

			function register_code(){
				var mobile = $("#user_mobile").val();//获取手机号
				getCode(mobile);
			}
			 
			//获取验证码
			function getCode(mobile){
				if($.trim(mobile)==""){
					alert("请输入手机号");	
					return false; 
				}
			  RemainTime("getCode",60);
				$.ajax({
						type:"POST",
						url:"index.php", 
						cache:false,	
						data:{'act':'ihuyi','op':'login','mobile':mobile},          
						success:function(data){
							if(data=="2"){
								//倒计时
								//RemainTime("getCode",20);  //发送成功   getCode ： 发送的按钮ID  ，20：倒计时时间
							}else if(data=="-1"){
								clearTimeout(timeid);
								RemainTime("getCode",1);
								alert("发送失败"); 
							}
						}       
				});
			}

		//倒计时itime =秒
		function RemainTime(id,iTime){
		
			iTime=iTime-1;
			if(iTime!=0){
			  timeid=setTimeout("RemainTime('"+id+"',"+iTime+")",1000);
				$("#"+id).removeAttr("onclick");
				$("#"+id).text(iTime+"秒");
			}else if(iTime<=0){
				$("#"+id).attr("onclick","register_code();");
				$("#"+id).text("获取")
			}
		}
		
		//验证码是否正确
		function bool_code(){
		
			var user_mobile = $("#user_mobile").val();
			var mobile_code = $("#mobile_code").val();
			
			if(user_mobile ==""){
				alert ("请出入手机号码");
				return false;
			}
			if(mobile_code ==""){
				alert ("请输入手机验证码");
				return false;
			}
			
			$.ajax({
						type:"POST",
						url:"<?php echo ADMIN_SITE_URL;?>/index.php", 
						cache:false,
						data:{'act':'ihuyi','op':'boolReg','mobile_code':mobile_code,'mobile':mobile},          
						success:function(data){
							 if(data=="1"){
								 return true;
							}else{
								 return false;
							}
							
						}       
			});
				
				
			return false;
			
		}
		
		
		</script>		  
          <div class="mt15">
            <dd class="d_fno">
              <input type="submit" class="submit d_submit" value="<?php echo $lang['login_index_login'];?>">
              <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
            </dd>
          </div>
        </form>
      </div>
      <!--======= 手机动态码登录 end ========-->
      </div>
      
		<div class="d_other_login">
    
		  <?php if ($output['setting_config']['qq_isuse'] == 1 || $output['setting_config']['sina_isuse'] == 1|| $output['setting_config']['weixin_isuse'] == 1){?>
			<dd class="nc-login-other d_o_lg">
			  <p class="d_other_tle"><?php echo $lang['nc_otherlogintip'];?></p>
			  <?php if ($output['setting_config']['qq_isuse'] == 1){?>
			  <a href="<?php echo SHOP_SITE_URL;?>/api.php?act=toqq" title="QQ" class="d_qq_login"><i></i>QQ</a>
			  <?php } ?>
			  <?php if ($output['setting_config']['sina_isuse'] == 1){?>
			  <a href="<?php echo SHOP_SITE_URL;?>/api.php?act=tosina" title="<?php echo $lang['nc_otherlogintip_sina']; ?>" class="d_sina_login"><i></i>新浪微博</a>
			  <?php } ?>
			  
			    <?php if ($output['setting_config']['weixin_isuse'] == 1){?>
			   <a href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo SHOP_SITE_URL;?>/index.php?act=connect_wx&op=index', 360);" title="微信账号登录" class="d_wxin_login"><i></i>微信</a>
			  <?php } ?>
			</dd>
		  <?php } ?>
		  </div>
		</div>
    
	  </div>
	</div>
</div>
<script>
$(document).ready(function(){
  /*=========================*/
$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
		  submitHandler:function(form){
			  ajaxpost('login_form', '', '', 'onerror');
		  },
		  
        onkeyup: false,
    rules: {
      user_name: "required",
      password: "required",
	  captcha : {
                required : true,
                remote   : {
                    url : '<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                          document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random()
                        }
                    }
                }
            }
          },
    messages: {
      user_name: "<i class='icon-exclamation-sign'></i>请输入已注册的用户名或手机号",
      password: "<i class='icon-exclamation-sign'></i>密码不能为空"
                  ,captcha : {
                required : '<i class="icon-remove-circle" title="验证码不能为空"></i>',
        remote   : '<i class="icon-remove-circle" title="验证码不能为空"></i>'
            }
          }
  });

/*手机动态登录*/
/*=========================*/
$("#d_phone_login").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            error_td.find('label').hide();
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
      submitHandler:function(form){
          ajaxpost('d_phone_login', '', '', 'onerror');
      },
        onkeyup: false,
    rules: {
			 user_mobile : {
                required : true,
                minlength: 11,
            },
			 mobile_code : {
                required : true,
				 remote   : {
                    url :'index.php?act=ihuyi&op=boolReg',
                    type:'get',
                   data:{
                        mobile_code : function(){
                            return $('#mobile_code').val();
                        },
						 mobile : function(){
                            return $("#user_mobile").val();
                        }
                    }
                }     
            }
    },
    messages: {
			 user_mobile : {
                required : '请输入手机号',
              	minlength : '手机号长度为11位' 
            },
			 mobile_code : {
                required : '请输入验证码',
				remote : '验证码错误'
     
            }
    }
  });

/*=========================*/
/*登录框效果*/
$('.d_bdno li').click(function(){
  //增加标题样式
  $(this).siblings().children("a").removeClass('d_curr');
  $(this).children("a").addClass('d_curr');
  //登录框效果
  var n = $(this).index();                    //获取索引值
  var oHt = $('.d_login_mbd > div').eq(n).outerHeight()+30+"px";                  //获取对应登录框的高度
  $('.d_login_mbd').css("height",oHt);

  $('.d_login_mbd').addClass('transition');                                       //外层添加transition动画效果
  $('.d_login_mbd > div').addClass('make_transist');                              //内部每个div添加make_transition动画效果
  $('.d_login_mbd > div').addClass('hideflip');                                  //外层添加transition动画效果
  $('.d_login_mbd > div').removeClass('showflip');
  $('.d_login_mbd > div').eq(n).addClass('showflip');
})

$('.nc-login-content input').focus(function(){
  $(this).parents('dl').addClass('d_in_focus');
});
$('.nc-login-content input').blur(function(){
  $(this).parents('dl').removeClass('d_in_focus');
})

// 勾选自动登录显示隐藏文字
    $('input[name="auto_login"]').click(function(){
        if ($(this).attr('checked')){
            $(this).attr('checked', true).next().show();
        } else {
            $(this).attr('checked', false).next().hide();
        }
    });
});
</script>
        	

 