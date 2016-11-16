$(function() {

/*	var map = new BMap.Map("allmap");
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			var mk = new BMap.Marker(r.point);
			map.addOverlay(mk);
			map.panTo(r.point);
			p_point = r.point;
			p_lng = r.point.lng;
			p_lat = r.point.lat;
			map.centerAndZoom(r.point, 15);
			var mk = new BMap.Marker(r.point);
			map.addOverlay(mk);
			mk.setAnimation(BMAP_ANIMATION_BOUNCE);
			var label = new BMap.Label("您的位置",{offset:new BMap.Size(20,-10)});
		    mk.setLabel(label);
			map.panTo(r.point);
			$(".wy_company").text("东方豪园");
		}else{
			$(".wy_company").text("定位失败");
		}
		
	});*/
			
    $.ajax({
        url: ApiUrl + "/index.php?act=index",
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.datas;

            var html = '';
			var aa = 0;
			var nav_count = 0 //导航栏内图标个数
			var dd = 0;//导航栏条数
            $.each(data, function(k, v) {
				$(document).scroll(function(){
					if($(document).scrollTop()>=$(document).height()-$(window).height() && aa==0){
						//alert('已到底');
						aa++ ;
					}
				})
				$(document).scrollTop()
				
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;
							
                        case 'navigation_list':
						    dd++;
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
								nav_count++;
                            });
							
                            break;

                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                    }
                    html += template.render(kk, vv);
                    return false;
                });
            });


			
            $("#main-container").html(html);
			
			$('.search-btn').click(function(){
				var keyword = encodeURIComponent($('#keyword').val());
				location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
			});

			// 导航有几个宽度自动设置
			var item_width = 100*dd/nav_count + '%';
			$(".navigation_list .item").css("width",item_width);
/*			var wx_openid = data[0].wid;
			if(wx_openid==0){
				picBig();
			}
			LoadingClose();*/
			
            $('.adv_list').each(function() {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                Swipe(this, {
                    startSlide: 0,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {},
                    transitionEnd: function(index, elem) {}
                });
            });
			
        }
    });
    

           

/*    $('.search-btn').click(function(){
        var keyword = encodeURIComponent($('#keyword').val());
        location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
    });*/

    /* index img列表图片的高度 */
    function photoH(){
        var photoW = $('.goods-item-pic').width();
        $('.goods-item-pic').css("height",photoW);
    }
    photoH();
});

function picBig() {
	
	//$("#img_code").attr('src',$("#erweima_div").children('img').attr('src'));
	var v = document.getElementById('divCenter');
	v.style.display = "block";

}

function picClose() {
	var v = document.getElementById('divCenter');
	v.style.display = "none";
}

// 20161102 wugangjian 添加写入cookie时间方法
function setCookie(name,value,days){
    //设置cookie过期时间
    var d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = name + "=" + value + "; " + expires;
}

/*window.onload=function(){
    var x=document.getElementById("demo");
    function getLocation()
      {
      if (navigator.geolocation)
        {
        navigator.geolocation.getCurrentPosition(showPosition);
        }
      else{x.innerHTML="Geolocation is not supported by this browser.";}
      }
    function showPosition(position)
      {
      x.innerHTML="Latitude: " + position.coords.latitude + 
      "<br />Longitude: " + position.coords.longitude;  
      }
      //alert(115)
    getLocation();
}*/
    
$(function (){
    var key = getcookie('key');

    // 20161102 wugangjian 添加判断显示代金券推广
    var guoqi_sj = getcookie('guoqi_sj');
    if (guoqi_sj == '') {
        //判断时间，显示提示层
        setCookie("guoqi_sj","hayden","s1");
        $('.tanceng_ts').show();
        // $("#windowcenter").css('display','none');
        $('.tanceng_ts').click(function(){

            if($(this).css('display') == 'block'){

                $(this).css('display','none');
                // location.href = '/wap/tmpl/voucher.html?voucher_id=1&uid=2892';
            }
        });
        // location.href = 'tmpl/member/login.html';
    }
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=member_index",
        data: {key: key},
        dataType: 'json',
        //jsonp:'callback',
        success: function(result) {
			var point ;
			point =result.datas.member_info.point;
			var goal_point = 100;
			var progress = point*100/goal_point+"%";
			if(point<=40){
				progress_ball = point*100/goal_point+"%";
			}else{
				progress_ball = (point-11)*100/goal_point+"%";
			}
			//console.log(result.datas.member_info.point);
			$(".progress").html(progress);
			
			$(".line-base-checked").css("width",progress);
			
			$(".line-ball").css("left",progress_ball);
		}
	});
	$("#keyword").focus(function(){
		window.location="tmpl/member/hot_seach.html";
	});
	var headTitle = document.title;
	var tmpl = '<div class="header-wrap d_mine">'
/*	        		+'<a href="javascript:history.back();" class="header-back"><span>返回</span><i>返回</i></a>'
						+'<h2>'+headTitle+'</h2>'
						+'<a href="javascript:void(0)" id="btn-opera" class="i-main-opera d_menu">'
				 	+'</a>'
    			+'</div>'
		    	+'<div class="main-opera-pannel">'*/
		    		+'<div class="main-op-table main-op-warp">'
		    			+'<a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html" class="quarter">'
		    				+'<p>分类</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/cart_list.html" class="quarter">'
		    				+'<p>购物车</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/member/hot_seach.html" class="quarter">'
		    				+'<p>搜索</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/member/member.html?act=member" class="quarter">'
		    				+'<p>我的</p>'
		    			+'</a>'
		    			+'<a href="'+WapSiteUrl+'/tmpl/member/member.html?act=member" class="quarter">'
		    				+'<p>关注</p>'
		    			+'</a>'
		    		+'</div>'
		    	+'</div>';
    //渲染页面
	var html = template.compile(tmpl);
	$("#header-top").html(html);
	$("#btn-opera").click(function (){
		$(".main-opera-pannel").toggle();
		$(".d_top_ch").toggle();
	});
	//当前页面
/*	if(headTitle == "商品分类"){
		$(".i-categroy").parent().addClass("d_home_curr");
	}else if(headTitle == "搜索"){
	     $("#foot_search").addClass("d_home_curr");
	}else if(headTitle == "购物车列表"){
		$(".i-cart").parent().addClass("d_home_curr");
	}else if(headTitle == "我的"){
		$(".d_min").parent().addClass("d_home_curr");
	}*/
});
function changeURLPar(url, ref, value)
{
    var str = "";
    if (url.indexOf('?') != -1)
        str = url.substr(url.indexOf('?') + 1);
    else
        return url + "?" + ref + "=" + value;
    var returnurl = "";
    var setparam = "";
    var arr;
    var modify = "0";
    if (str.indexOf('&') != -1)
    {
        arr = str.split('&');
        for (i in arr)
        {
            if (arr[i].split('=')[0] == ref)
            {
                setparam = value;
                modify = "1";
            }
            else
            {
                setparam = arr[i].split('=')[1];
            }
            returnurl = returnurl + arr[i].split('=')[0] + "=" + setparam + "&";
        }
        returnurl = returnurl.substr(0, returnurl.length - 1);
        if (modify == "0")
            if (returnurl == str)
                returnurl = returnurl + "&" + ref + "=" + value;
    }
    else
    {
        if (str.indexOf('=') != -1)
        {
            arr = str.split('=');
            if (arr[0] == ref)
            {
                setparam = value;
                modify = "1";
            }
            else
            {
                setparam = arr[1];
            }
            returnurl = arr[0] + "=" + setparam;
            if (modify == "0")
                if (returnurl == str)
                    returnurl = returnurl + "&" + ref + "=" + value;
        }
        else
            returnurl = ref + "=" + value;
    }
    return url.substr(0, url.indexOf('?')) + "?" + returnurl;
}

var delay = false, page = 0;
function view(url)
{
    var srollPos = $(window).scrollTop();
    var th = parseFloat($(window).height()) + parseFloat(srollPos);
    var dh = $(document).height();
    var offset = 30;
    if (delay === true || (dh - offset) > th)
    {
        return false;
    }
    delay = true;
    $.ajax(
    {
        url : ApiUrl + "/index.php?act=store&op=store_list&page=" + page + url,
        type : 'get',
        dataType : 'json',
        success : function (result)
        {
            //console.log(result);
            if (!result.datas.error)
            {
                var html = template.render('stores', result.datas);
                $("#product_list").append(html);
                page += 1;
                delay = false;
            }
        }
    }
    );
}
// $(function ()
// {
// 	var map = new BMap.Map("allmap");
// 	var geolocation = new BMap.Geolocation();
// 	var p_lng = "";
// 	var p_lat = "";
// 	var p_point = "";
// 	geolocation.getCurrentPosition(function(r){
// 		if(this.getStatus() == BMAP_STATUS_SUCCESS){
// 			//alert(1);
// 			p_point = r.point;
// 			p_lng = r.point.lng;
// 			p_lat = r.point.lat;
// 			console.log(p_lng);
// 			$(".wy_company").text("东方豪园");
// 			$.ajax(
// 			{
// 				url : ApiUrl + "/index.php?act=store&op=get_location&lng="+p_lng+"&lat="+p_lat,
// 				type : 'get',
// 				dataType : 'json',
// 				success : function (ss)
// 				{
					
// 					//var html = template.render('meishia_body', result.datas);
// 					//$("#meishia").append(html);
// 					var url = '';
// 					view(url);
// 					$(window).scroll(function ()
// 					{
// 						view(url);
// 					}
// 					);
// 				}
// 			}
// 			);
			
// 		}else{
// 			$(".wy_company").text("定位失败");
			
//    			var url = '';
// 			view(url);
// 			$(window).scroll(function ()
// 			{
// 				view(url);
// 			}
// 			);
			
// 		}
// 	})

// }
// );

