$(function (){
    var memberHtml = '<a class="btn mr5" href="'+WapSiteUrl+'/tmpl/member/member.html?act=member">个人中心</a>';
    var act = GetQueryString("act");
    if(act && act == "member"){
        memberHtml = '<a class="btn mr5" id="logoutbtn" href="javascript:void(0);">注销账号</a>';
    }
    var tmpl = '<div class="footer">'
        	+'<div class="footer-top">'
            +'<div class="footer-tleft">'+ memberHtml + '</div>'
			+'<div class="footer-tcontent">'
			+'<a href="'+WapSiteUrl+'/shop.html" class="standard">去印咯商城</a>'
            +'<a href="'+WapSiteUrl+'/tmpl/article_list.html?ac_id=2" class="standard">帮助中心</a>'
            +'<a href="'+WapSiteUrl+'/tmpl/article_list.html?ac_id=7" class="standard">关于我们</a>'
            +'<p class="copyright">'
                +'版权所有 2014-2015 © www.easyboxprint.cn'
            +'</p>'			
			+'</div>'
            +'<a href="javascript:void(0);"class="gotop">'
                +'<span class="gotop-icon"></span>'
                +'<p>回顶部</p>'
            +'</a>'
        +'</div>'
        +'<div class="footer-content">'
            +'<p class="link">'

            +'</p>'

        +'</div>'
    +'</div>';
	 var tmpl2 = '<div id="bottom">'
		+'<div class="d_ft_ht">'
  +'<div id="nav-tab" style="bottom:-40px;">'
            +'<div id="nav-tab-btn"><i class="fa fa-chevron-down"></i></div>'
            +'<div class="clearfix tab-line nav d_rgt_bt">'
      +'<div class="tab-line-item" style="width:33%;" ><a id="foot_index"  href="'+WapSiteUrl+'"><i class="d_home"></i><br>首页</a></div>'
		/*
		* Time：20160707
		* Author：wugangjian
		* Conent：delete shangjia connect
		* */
      /*+'<div class="tab-line-item" style="width:33%;" ><a  id="foot_sj" href="'+WapSiteUrl+'/tmpl/store_list.html"><i class="d_sj"></i><br>商家</a></div>'*/
      /*+'<div class="tab-line-item d_gcar" style="width:20%;line-height:40px;padding-top:5px;" ><i style="font-size:30px;" class=""></i><br></div>'*/
/*      +'<div class="tab-line-item d_mycar" style="width:20%;position: relative;" ><a href="'+WapSiteUrl+'/tmpl/cart_list.html"><div class="d_car_in"><div class="d_in_car"><i class="d_minecar"></i><br>购物车</div></div></a></div>'*/
      /*+'<div class="tab-line-item" style="width:33%;" ><a id="foot_search" href="'+WapSiteUrl+'/tmpl/member/hot_seach.html"><i class="d_sear"></i><br>生活</a></div>'*/
      +'<div class="tab-line-item" style="width:33%;" ><a id="foot_search" href="'+WapSiteUrl+'/tmpl/go_store.html?store_id=39"><i class="d_sear"></i><br>商品</a></div>'
      +'<div class="tab-line-item" style="width:33%;" ><a id="foot_member"  href="'+WapSiteUrl+'/tmpl/member/member.html?act=member"><i class="d_min"></i><br>我的</a></div>'
    +'</div>'
   +'</div>'
+'</div>'
		+'<div style="z-index: 10000; border-radius: 3px; position: fixed; background: none repeat scroll 0% 0% rgb(255, 255, 255); display: none;" id="myAlert" class="modal hide fade">'
  +'<div style="text-align: center;padding: 15px 0 0;" class="title"></div>'
  +'<div style="min-height: 40px;padding: 15px;" class="modal-body"></div>'
  +'<div style="padding:3px;height: 35px;line-height: 35px;" class="alert-footer">'
  +'<a style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;border-right: 1px solid #ddd;margin-right: -1px;" class="confirm" href="javascript:;">Save changes</a><a aria-hidden="true" data-dismiss="modal" class="cancel" style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;" href="javascript:;">关闭</a></div>'
+'</div>'
		+'<div style="display:none;" class="tips"><i class="fa fa-info-circle fa-lg"></i><span style="margin-left:5px" class="tips_text"></span></div>'
		+'<div class="bgbg" id="bgbg" style="display: none;"></div>'
        +'</div>'
	+'</div>';


	var render = template.compile(tmpl);
	var html = render();
	$("#footer").html(html+tmpl2);
    //回到顶部
    $(".gotop").click(function (){
        $(window).scrollTop(0);
    });
    var key = getcookie('key');
	$('#logoutbtn').click(function(){
		var username = getcookie('username');
		var key = getcookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl+'/tmpl/member/login.html';
				}
			}
		});
	});
        
        
                   //当前页面
                 var headTitle = document.title;
	if(headTitle == "商品分类"){
         $("#foot_class").addClass("d_home_curr");
	}else if(headTitle == "商家列表"){
	     $("#foot_sj").addClass("d_home_curr");
	}else if(headTitle == "搜索"){
	     $("#foot_search").addClass("d_home_curr");
	}else if(headTitle == "去印咯商城"){
		$("#foot_index").addClass("d_home_curr");
	}else if(headTitle == "我的商城"){
		$("#foot_member").addClass("d_home_curr");
	}
        
        
});

//bottom nav 33 hao-v3 by 33h ao.com Qq 1244 986 40
$(function() {
	setTimeout(function(){
		if($("#content .container").height()<$(window).height())
		{
			$("#content .container").css("min-height",$(window).height());
		}
	},300);
	 
});

/* nav点击效果 */
/*$(function(){
$('.d_rgt_bt .tab-line-item').eq(0).children('a').addClass('d_home_curr');
$('.d_rgt_bt .tab-line-item').click(function(){
        $(this).siblings().find('a').removeClass('d_home_curr');
        $(this).find('a').addClass('d_home_curr');
    })
})*/


	