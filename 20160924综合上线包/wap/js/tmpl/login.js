$(function() {
    var memberHtml = '<a class="btn mr5" href="' + WapSiteUrl + '/tmpl/member/member.html?act=member">个人中心</a><a class="btn mr5" href="' + WapSiteUrl + '//tmpl/member/register.html">注册</a>';
    var act = GetQueryString("act");
    if (act && act == "member") {
        memberHtml = '<a class="btn mr5" id="logoutbtn" href="javascript:void(0);">注销账号</a>';
    }
    var tmpl = '<div class="footer">'
            /*+'<div class="footer-top">'
             +'<div class="footer-tleft">'+ memberHtml +'</div>'
             +'<a href="javascript:void(0);"class="gotop">'
             +'<span class="gotop-icon"></span>'
             +'<p>回顶部</p>'
             +'</a>'
             +'</div>'
             +'<div class="footer-content">'
             +'<p class="link">'
             +'<a href="javascript:void(0);" class="standard">手机版首页</a>'
             +'<a href="javascript:void(0);">下载Android客户端</a>'
             +'</p>'
             +'<p class="copyright">'
             +'版权所有 2014-2015 © www.shopjl.com'
             +'</p>'
             +'</div>'*/
            + '</div>';
    var render = template.compile(tmpl);
    var html = render();
    $("#footer").html(html);
    //回到顶部
    $(".gotop").click(function() {
        $(window).scrollTop(0);
    });
    var key = getcookie('key');
    $('#logoutbtn').click(function() {
        var username = getcookie('username');
        var key = getcookie('key');
        var client = 'wap';
        $.ajax({
            type: 'get',
            url: ApiUrl + '/index.php?act=logout',
            data: {username: username, key: key, client: client},
            success: function(result) {
                if (result) {
                    delCookie('username');
                    delCookie('key');
                    location.href = WapSiteUrl + '/tmpl/member/login.html';
                }
            }
        });
    });
    
   var referurl = document.referrer;//上级网址
   
   //alert(referurl);
    
    var res = referurl.indexOf("voucher_show");
    
    if(res>0){  
        
         var cookie_url = document.cookie;
         
         //alert(cookie_url);
    
         var cookie_url = cookie_url.split("=");

         var referurl2 = cookie_url[cookie_url.length-1];
         
         var uid = referurl2.split("=")[referurl2.length-1];
         
         referurl = SiteUrl+"wap/tmpl/voucher.html?uid="+uid;

    }
    
    //var referurl = document.referrer;//上级网址
   
    /*edit by peiyu stop*/ 
    
 
    $("input[name=referurl]").val(referurl);
    $.sValid.init({
        rules: {
            username: "required",
            userpwd: "required"
        },
        messages: {
            username: "用户名必须填写！",
            userpwd: "密码必填!"
        },
        callback: function(eId, eMsg, eRules) {
            var aSpan = $('.login-form').find('.input-40');
            /*var oLb = aSpan.find('label');*/

            if (eId.length > 0) {
                aSpan.parent('span').addClass('error');
                /*var errorHtml = "";
                 $.map(eMsg,function (idx,item){
                 errorHtml += "<label>"+idx+"</label>";
                 });*/
                $(".error-tips").html(errorHtml).show();/**/

            } else {
                $(".error-tips").html("").hide();
                /*aSpan.parent('span').removeClass('error');*/
            }
        }
    });
    $('#loginbtn').click(function() {//会员登陆
        var username = $('#username').val();
        var pwd = $('#userpwd').val();
        var client = 'wap';
        if ($.sValid()) {
            $.ajax({
                type: 'post',
                url: ApiUrl + "/index.php?act=login",
                data: {username: username, password: pwd, client: client},
                dataType: 'json',
                success: function(result) {
                    if (!result.datas.error) {
                        if (typeof (result.datas.key) == 'undefined') {
                            return false;
                        } else {
                            addcookie('member_id', result.datas.member_id);
                            addcookie('username', result.datas.username);
                            addcookie('key', result.datas.key);
							if(referurl=='/wap/tmpl/member/login.html' || referurl==''){
								location.href = WapSiteUrl;
							}else{
                            	location.href = referurl;
							}
						}
                        $(".error-tips").hide();
                    } else {
                        $(".error-tips").html(result.datas.error).show();
                    }
                }
            });
        }
    });
});