/*function sleep(milliSeconds){
	var startTime = new Date().getTime(); // get the current time
	while (new Date().getTime() < startTime + milliSeconds); // hog cpu
}*/
$(function (){

    var unixTimeToDateString = function(ts, ex) {

        ts = parseFloat(ts) || 0;

        if (ts < 1) {

            return '';

        }

        var d = new Date();

        d.setTime(ts * 1e3);

        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();

        if (ex) {

            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();

        }

        return s;

    };



    var buyLimitation = function(a, b) {

        a = parseInt(a) || 0;

        b = parseInt(b) || 0;

        var r = 0;

        if (a > 0) {

            r = a;

        }

        if (b > 0 && r > 0 && b < r) {

            r = b;

        }

        return r;

    };



    template.helper('isEmpty', function(o) {

        for (var i in o) {

            return false;

        }

        return true;

    });



     // 图片轮播

    function picSwipe(){

      var elem = $("#mySwipe")[0];

      window.mySwipe = Swipe(elem, {

        continuous: true,

        // disableScroll: true,

        stopPropagation: true,

        callback: function(index, element) {

          $(".pds-cursize").html(index+1);

        }

      });

    }

    var goods_id = GetQueryString("goods_id");

    //渲染页面

    $.ajax({

       url:ApiUrl+"/index.php?act=goods&op=goods_detail",

       type:"get",

       data:{goods_id:goods_id},

       dataType:"json",

       success:function(result){


   	 	 console.log(result);

          var data = result.datas;

          if(!data.error){

             //商品图片格式化数据

            if(data.goods_image){

              var goods_image = data.goods_image.split(",");

              data.goods_image = goods_image;

            }else{

               data.goods_image = [];

            }

            //商品规格格式化数据

            if(data.goods_info.spec_name){

              var goods_map_spec = $.map(data.goods_info.spec_name,function (v,i){

                var goods_specs = {};

                goods_specs["goods_spec_id"] = i;

                goods_specs['goods_spec_name']=v;

                if(data.goods_info.spec_value){

	                $.map(data.goods_info.spec_value,function(vv,vi){

	                    if(i == vi){

	                      goods_specs['goods_spec_value'] = $.map(vv,function (vvv,vvi){

	                        var specs_value = {};

	                        specs_value["specs_value_id"] = vvi;

	                        specs_value["specs_value_name"] = vvv;

	                        return specs_value;

	                      });

	                    }

	                  });

	                  return goods_specs;

                }else{

                	data.goods_info.spec_value = [];

                }

              });

              data.goods_map_spec = goods_map_spec;

            }else {

              data.goods_map_spec = [];

            }



            

            // 虚拟商品限购时间和数量

            if (data.goods_info.is_virtual == '1') {

                data.goods_info.virtual_indate_str = unixTimeToDateString(data.goods_info.virtual_indate, true);

                data.goods_info.buyLimitation = buyLimitation(data.goods_info.virtual_limit, data.goods_info.upper_limit);

            }



            // 预售发货时间

            if (data.goods_info.is_presell == '1') {

                data.goods_info.presell_deliverdate_str = unixTimeToDateString(data.goods_info.presell_deliverdate);

            }

            

            //渲染模板

			
            var html = template.render('product_detail', data);

      

            $("#product_detail_wp").html(html);


			$(".load_detail").click(function(){
					$("#detail_loading").html("加载中...");
					//获取商品详情
					$(function() {
						var goods_id = GetQueryString("goods_id");
						$.ajax({
							url: ApiUrl + "/index.php?act=goods&op=goods_body",
							data: {goods_id: goods_id},
							type: "get",
							success: function(result) {	
								$(".fixed-tab-pannel").html(result);
								$(".d_pro_curr").html("商品详情");
								//$(".d_pro_curr").removeClass("load_detail");
								$("#detail_loading").hide();
							}
						});
					});
			
			
			});
			
            // @add 手机端详情

            if (data.goods_info.mobile_body) {

                $('#mobile_body').html(data.goods_info.mobile_body);

            }



            //图片轮播

            picSwipe();

            //商品描述

            $(".pddcp-arrow").click(function (){

              $(this).parents(".pddcp-one-wp").toggleClass("current");

            });

            //规格属性

            var myData = {};

            myData["spec_list"] = data.spec_list;

            $(".pddc-stock a").click(function (){

              var self = this;

              arrowClick(self,myData);

            });


            //定义全局变量

            var plus = data.goods_info.goods_per_plus;
              var min = data.goods_info.goods_min_order;

            //赋值给商品购买数量


            //购买数量，减

            $(".minus-wp").click(function (){

               var buynum = $(".buy-num").val();
               var diff = buynum - plus

               if (diff > min & plus >0) {
                $(".buy-num").val(parseInt(buynum-plus));
               }else{
                $(".buy-num").val(parseInt(min));
               }


            });

            //点击加按钮变色

            $(".minus-wp").bind({

              touchstart:function(){$(this).css("backgroundColor","#B7B7B7")},

              touchend:function(){$(this).css("backgroundColor","#e3e3e3")}

            });

            //购买数量加

            $(".add-wp").click(function (){

               var buynum = parseInt($(".buy-num").val());
			   
			   if(buynum >= data.goods_info.purchase_quantity && data.goods_info.purchase_quantity>0){
                          $.sDialog({

                              skin:"red",

                              content:"每人最多购买"+ data.goods_info.purchase_quantity +"件！",

                              okBtn:false,

                              cancelBtn:false

                          });
                     //alert("每人最多购买"+ data.goods_info.purchase_quantity +"件！");
                     return false;
			   }
			   
               if(data.up_limit>0){
                  if(buynum>=data.up_limit){
                     //alert('超过限购数量！');
                          $.sDialog({

                              skin:"red",

                              content:'超过限购数量！',

                              okBtn:false,

                              cancelBtn:false

                          });
                     return false;
                  }
               }

               if(buynum < parseInt($("#goods_storage_e").val())){

                  $(".buy-num").val(parseInt(buynum-(-plus)));

               }

            });
			
			$(".buy-num").blur(function(){
				
			   var buynum = parseInt($(".buy-num").val());
				
			   if(buynum > data.goods_info.purchase_quantity && data.goods_info.purchase_quantity>0){
                     //alert("每人最多购买"+ data.goods_info.purchase_quantity +"件！");
                          $.sDialog({

                              skin:"red",

                              content:"每人最多购买"+ data.goods_info.purchase_quantity +"件！",

                              okBtn:false,

                              cancelBtn:false

                          });
					 $(".buy-num").val(parseInt(data.goods_info.purchase_quantity));
                     return false;
			   }
			   
               if(data.up_limit>0){
                  if(buynum>=data.up_limit){
                     //alert('超过限购数量！');
                          $.sDialog({

                              skin:"red",

                              content:'超过限购数量！',

                              okBtn:false,

                              cancelBtn:false

                          });
                     return false;
                  }
               }
			
			});
			
			


          //显示折扣时间 
          if(data.goods_info.start_time_int && data.goods_info.end_time_int){
              $('.add-wp.fleft').after('<span class="end_time" style="float:left;clear: both;">限时截止：<strong>'+data.goods_info.end_time+'</strong></span>');

              //如果活动开始，显示活动剩余时间
              // $('.add-wp.fleft').after('<strong id="shengyu_time" style="color:red">'+data.goods_info.shengyu_time+'</strong>');

              tms[tms.length] = data.goods_info.shengyu_time;
              day[day.length] = "d";
              hour[hour.length] = "h";
              minute[minute.length] = "m";
              second[second.length] = "s";

              if(data.goods_info.start_time_int < data.goods_info.now_time_int){
                  //活动开始
                  $('.value-no.mt10.clearfix').children().show();
                  $('.value-no.mt10.clearfix').children('.fsm').hide();
              }else{
                  //活动没开始
                  $('.value-no.mt10.clearfix').children().hide();
                  $('.value-no.mt10.clearfix').children('.fsm').show();
                  $('.key-no').hide();
              }
              if(data.goods_info.now_time_int > data.goods_info.start_time_int){
                  $('.value-no.mt10.clearfix').children('.fsm').hide();
              }

          }
          if(!data.goods_info.now_time_int){
                  $('.value-no.mt10.clearfix').children('.fsm').hide();
          }

            //点击减按钮变色

            $(".add-wp").bind({

              touchstart:function(){$(this).css("backgroundColor","#B7B7B7")},

              touchend:function(){$(this).css("backgroundColor","#e3e3e3")}

            })   



            //商品详情等选项卡

            $(".d_inline .pddc-commend-wp").hide();

            $(".d_inline .pddc-commend-wp").eq(0).show();

            $(".d_pro_tle span").click(function(){

              var ns = $(this).index();

              $(".d_pro_tle span").removeClass('d_pro_curr');

              $(this).addClass('d_pro_curr');



              $(".d_inline .pddc-commend-wp").hide();

              $(".d_inline .pddc-commend-wp").eq(ns).show();
			  
            });



            var headHeight=$(".d_pro_tle").offset().top-10;  //获取顶部的距离

            var nav=$(".d_pro_tle"); 

            //$('.d_top_btn').hide();

              $(window).scroll(function(){ 

                if($(this).scrollTop()>headHeight){ 

                  //nav.addClass("d_fixed_top"); 

                  //alert(headHeight)

                }else{ 

                  //nav.removeClass("d_fixed_top"); 

                };

                if ($(this).scrollTop()>$(this).height()) {

                  //$('.d_top_btn').addClass('d_top_btn_show');

                }else{

                  //$('.d_top_btn').removeClass('d_top_btn_show');

                };

              })

              

            //活动、规格、属性弹出

            $('.d-pddetail-cnt .grouping li').click(function(){ 

            	var index = $(this).index();

            	if (index == 2) return;

            	$('.d-pddetail-cnt .group-box').addClass('show');

            	$('.d-pddetail-cnt .group-box ul > li').addClass('hide').eq(index).removeClass('hide');

            })

                //图片轮播

                picSwipe();

                //商品描述

                $(".pddcp-arrow").click(function() {

                    $(this).parents(".pddcp-one-wp").toggleClass("current");

                });

                //规格属性

                var myData = {};

                myData["spec_list"] = data.spec_list;

                $(".pddc-stock a").click(function() {

                    var self = this;

                    arrowClick(self, myData);

                });

                //商品详情等选项卡

                $(".d_inline .pddc-commend-wp").hide();

                $(".d_inline .pddc-commend-wp").eq(0).show();



                $(".d_pro_tle span").click(function() {

                    var ns = $(this).index();

                    $(".d_pro_tle span").removeClass('d_pro_curr');

                    $(this).addClass('d_pro_curr');



                    $(".d_inline .pddc-commend-wp").hide();

                    $(".d_inline .pddc-commend-wp").eq(ns).show();

                });



                $('#evaluate_div').click(function(){

                  //alert(4848)

                })

                //评价图片放大

            function bigimg(this_img){

              var oMineH = $('#product_detail_wp').height() + $('.pddc-commend-wp').height();

              var oBImg = $('.d_big_ph'),imgBoxHtml="";

              //var d_img_content = $('.d_big_photo').html();



              imgBoxHtml+='<div class="d_big_main">'

                            +'<div class="d_img_box">'

                              +'<img src="'+this_img+'">'

                              +'<span class="d_close_btn"></span>'

                            +'</div>'

                         +'</div>';

              oBImg.append(imgBoxHtml);//创建黑色背景和图片框

              $('.d_big_main').css("height",oMineH+100);

              //图片框的尺寸

              //var oMgB = $('.d_img_box').width();

              //$('.d_img_box').css("height",oMgB);

              //关闭图片显示框

                $('.d_close_btn').click(function(){

                  $('.d_big_ph').empty();

                });

             }



                //回到顶部

                function goTop(acceleration, time) {

                    acceleration = acceleration || 0.1;

                    time = time || 16;



                    var x1 = 0;

                    var y1 = 0;

                    var x2 = 0;

                    var y2 = 0;

                    var x3 = 0;

                    var y3 = 0;



                    if (document.documentElement) {

                        x1 = document.documentElement.scrollLeft || 0;

                        y1 = document.documentElement.scrollTop || 0;

                    }

                    if (document.body) {

                        x2 = document.body.scrollLeft || 0;

                        y2 = document.body.scrollTop || 0;

                    }

                    var x3 = window.scrollX || 0;

                    var y3 = window.scrollY || 0;

                    //alert(y3)

                    //alert(y2)

                    // 滚动条到页面顶部的水平距离 

                    var x = Math.max(x1, Math.max(x2, x3));

                    // 滚动条到页面顶部的垂直距离 

                    var y = Math.max(y1, Math.max(y2, y3));



                    // 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小 

                    var speed = 1 + acceleration;

                    window.scrollTo(Math.floor(x / speed), Math.floor(y / speed));



                    // 如果距离不为零, 继续调用迭代本函数 

                    if (x > 0 || y > 0) {

                        window.setTimeout(goTop, time);

                    }

                }

                $('.d_top_btn').click(function() {

                    goTop();

                })

                //活动、规格、属性弹出

                $('.d-pddetail-cnt .grouping li').click(function() {

                    var index = $(this).index();

                    if (index == 3)

                        return;

                    $('.d-pddetail-cnt .group-box').addClass('show');

                    $('.d-pddetail-cnt .group-box ul > li').addClass('hide').eq(index).removeClass('hide');

                })



            $('.d-pddetail-cnt .group-box .close').click(function(){

            	$('.d-pddetail-cnt .group-box').removeClass('show');

            })



            $('.d-pddetail-cnt .group-box .select em').click(function(){

            	$(this).addClass('active').siblings().removeClass('active');

            })





            //商品详情和商品评价固定顶部

              var offsetTop = $(document).scrollTop();

              var oBjTop = $('.d_pro_tle').offset().top;

              var subH = oBjTop-offsetTop;

              //alert(subH)

              if(subH==0) $('.d_pro_tle').addClass('d_fixed_top');

            

            //alert($('.d_pro_tle').offset().top)

            // 一个F码限制只能购买一件商品 所以限制数量为1

            if (data.goods_info.is_fcode == '1') {

                $('.minus-wp').hide();

                $('.add-wp').hide();

                $(".buy-num").attr('readOnly', true);

            }

            //收藏

            $(".pd-collect").click(function (){

                var key = getcookie('key');//登录标记

                if(key==''){

                  window.location.href = WapSiteUrl+'/tmpl/member/login.html';

                }else {

                  $.ajax({

                    url:ApiUrl+"/index.php?act=member_favorites&op=favorites_add",

                    type:"post",

                    dataType:"json",

                    data:{goods_id:goods_id,key:key},

                    success:function (fData){

                     if(checklogin(fData.login)){

                        if(!fData.datas.error){

                          $.sDialog({

                            skin:"green",

                            content:"收藏成功！",

                            okBtn:false,

                            cancelBtn:false

                          });

                        }else{

                          $.sDialog({

                            skin:"red",

                            content:fData.datas.error,

                            okBtn:false,

                            cancelBtn:false

                          });

                        }

                      }

                    }

                  });

                }

            });

            //加入购物车

            $(".add-to-cart").click(function (){
				if($("#ck_goods_id").val()!="none"){
					goods_id=$("#ck_goods_id").val();
				}
              if(data.goods_info.is_virtual == 1){

//            	  alert("此商品为虚拟商品");

            	  $.sDialog({

            		 skin:"block",

            		 content:"虚拟商品不能加入购物车",

            		 "okBtnText":"再逛逛",

            		 okFn:function(){}

            	  });

            	  return

              }

              var key = getcookie('key');//登录标记

               if(key==''){

                  window.location.href = WapSiteUrl+'/tmpl/member/login.html';

               }else{
				   
                  var quantity = parseInt($(".buy-num").val());
				  
                  $.ajax({

                     url:ApiUrl+"/index.php?act=member_cart&op=cart_add",

                     data:{key:key,goods_id:goods_id,quantity:quantity},

                     type:"post",

                     success:function (result){
						 
                        var rData = $.parseJSON(result);

                        if(checklogin(rData.login)){

                          if(!rData.datas.error){
                             $.sDialog({

                                skin:"block",

                                content:"添加购物车成功！",

                                "okBtnText": "再逛逛",

                                "cancelBtnText": "去购物车",

                                okFn:function (){},

                                cancelFn:function (){

                                  window.location.href = WapSiteUrl+'/tmpl/cart_list.html';

                                }

                              });

                          }else{

                            $.sDialog({

                              skin:"red",

                              content:rData.datas.error,

                              okBtn:false,

                              cancelBtn:false

                            });

                          }

                        }

                     }

                  })

               }

            });



            //立即购买



            if (data.goods_info.is_virtual == '1') {

                $(".buy-now").click(function() {
                  
                    if(data.goods_info.now_time_int < data.goods_info.start_time_int){
                        //alert('活动还没开始，不可以购买');
                          $.sDialog({

                              skin:"red",

                              content:'活动还没开始，不可以购买！',

                              okBtn:false,

                              cancelBtn:false

                          });
				
						
                        return false;
                    }

                    var key = getcookie('key');//登录标记

                    if (key == '') {

                        window.location.href = WapSiteUrl+'/tmpl/member/login.html';

                        return false;

                    }



                    var buynum = parseInt($('.buy-num').val()) || 0;



                    if (buynum < 1) {

                          $.sDialog({

                              skin:"red",

                              content:'参数错误！',

                              okBtn:false,

                              cancelBtn:false

                          });

                        return;

                    }

					
                    if (buynum > parseInt($("#goods_storage_e").val())) {

                          $.sDialog({

                              skin:"red",

                              content:'库存不足！',

                              okBtn:false,

                              cancelBtn:false

                          });

                        return;

                    }



                    // 虚拟商品限购数量

                    if (data.goods_info.buyLimitation > 0 && buynum > data.goods_info.buyLimitation) {

                          $.sDialog({

                              skin:"red",

                              content:'超过限购数量！',

                              okBtn:false,

                              cancelBtn:false

                          });

                        return;

                    }



                    var json = {};

                    json.key = key;

                    json.cart_id = goods_id;

                    json.quantity = buynum;

                    $.ajax({

                        type:'post',

                        url:ApiUrl+'/index.php?act=member_vr_buy&op=buy_step1',

                        data:json,

                        dataType:'json',

                        success:function(result){

                            if (result.datas.error) {

                                $.sDialog({

                                    skin:"red",

                                    content:result.datas.error,

                                    okBtn:false,

                                    cancelBtn:false

                                });

                            } else {
								if($("#ck_goods_id").val()=="none"){
									location.href = WapSiteUrl+'/tmpl/order/vr_buy_step1.html?goods_id='+goods_id+'&quantity='+buynum;
								}else{
									location.href = WapSiteUrl+'/tmpl/order/vr_buy_step1.html?goods_id='+$("#ck_goods_id").val()+'&quantity='+buynum;
								}
                                //location.href = WapSiteUrl+'/tmpl/order/vr_buy_step1.html?goods_id='+goods_id+'&quantity='+buynum;

                            }

                        }

                    });

                });

            } else {

                $(".buy-now").click(function (){


                  /**
                  *1元购活动 用户重复购买1元购商品提示
                  *wugangjian 20160911 ATART
                  */
                  
                  if (data.goods_info.goods_type == 2) {
                    alert('感谢您的支持，但是1元购专场商品每人只能购买一件哦~非常抱歉');
                    return false;
                  }
                  /**
                  *1元购活动 用户重复购买1元购商品提示
                  *wugangjian 20160911 END
                  */


                    if(data.goods_info.now_time_int < data.goods_info.start_time_int){
                        //alert('活动还没开始，不可以购买');
                          $.sDialog({

                              skin:"red",

                              content:'活动还没开始，不可以购买！',

                              okBtn:false,

                              cancelBtn:false

                          });
                        return false;
                    }

                   var key = getcookie('key');//登录标记

                   if(key==''){

                      window.location.href = WapSiteUrl+'/tmpl/member/login.html';

                   }else{

                      var buynum = $('.buy-num').val();



                    if (buynum < 1) {

                          $.sDialog({

                              skin:"red",

                              content:'参数错误！',

                              okBtn:false,

                              cancelBtn:false

                          });

                        return;

                    }
					
					
                    if (parseInt(buynum) > parseInt($("#goods_storage_e").val())) {

                          $.sDialog({

                              skin:"red",

                              content:'库存不足！' + buynum + ',' + parseInt($("#goods_storage_e").val()),

                              okBtn:false,

                              cancelBtn:false

                          });

                        return;

                    }



                      var json = {};

                      json.key = key;
                      json.goods_id = goods_id;

                      json.cart_id = goods_id+'|'+buynum;

                      $.ajax({

                          type:'post',

                          url:ApiUrl+'/index.php?act=member_buy&op=buy_step1',

                          data:json,

                          dataType:'json',

                          success:function(result){
                            //1元购 wugangjian 20160922 修改取消订单后不能购买该商品
                              if(!result) {
                                alert("每人最多购买1件");
                                return false;
                              }

                              if (result.datas.error) {

                                  $.sDialog({

                                      skin:"red",

                                      content:result.datas.error,

                                      okBtn:false,

                                      cancelBtn:false

                                  });

                              }else{
								if($("#ck_goods_id").val()=="none"){
									location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+buynum;
								}else{
									location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+$("#ck_goods_id").val()+'&buynum='+buynum;
								}
								//location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+buynum;

                              }

                          }

                      });

                   }

                });



            }



          }else {



            $.sDialog({

                content: data.error + '！<br>请返回上一页继续操作…',

                okBtn:false,

                cancelBtnText:'返回',

                cancelFn: function() { history.back(); }

            });



            //var html = data.error;

            //$("#product_detail_wp").html(html);



          }



          //验证购买数量是不是数字

          $("#buynum").blur(buyNumer);

          AddView();

          

          

           //评论功能开始
		   

          $.ajax({

					type:'get',

                   url:ApiUrl+'/index.php?act=goods&op=goods_evaluate',    

                    data:{goods_id:goods_id},

                    dataType:'json',

                    success:function(result){
								console.log(result);
                                var data = result.datas;

                    	var htmldata = '';

                    	//拼接商品评论

                    for (var i = 0; i < data.length; i++) {

                            

                        htmldata += '<dl>';

                        htmldata += '<dt>' + data[i].geval_frommembername + '<span><i style="width:' + data[i].geval_scores + '%;"></i></span></dt>';

                        htmldata += '<dd class="tit">' + data[i].geval_content + '</dd>';

                        htmldata += '<dd class="pic d_big_photo">';

                            

                        for (var j = 0; j < data[i].geval_image.length; j++) {

                            if(data[i].geval_image[j]){

                            htmldata += '<span class="d_imgb" style="height:50px; width:50px;"><img src="' + SiteUrl + '/data/upload/shop/member/' + data[i].geval_frommemberid + '/' + data[i].geval_image[j] + '"></span>';

                            }

                            }

							htmldata += '</dd>';

							htmldata += '<dd class="tr">' + data[i].geval_addtime + '</dd>';

							htmldata += '</dl>';     

                    	}
						if(htmldata!=""){
                    		$('#evaluate_div').html(htmldata);
						}
                      //点击弹出图片显示框

                      $('#evaluate_div').find("img").each(function(){

                          $(this).click(function(){

                            //alert(1515)

                            var this_img = $(this).attr("src");

                           bigimg(this_img);

                          })

                      });

                      //滚动一定高度top按钮的显示隐藏

                      var headHeight = $(".d_pro_tle").offset().top - 10;  //获取顶部的距离

                      var nav = $(".d_pro_tle");

                      //$('.d_top_btn').hide();

                      $(window).scroll(function() {

                          if ($(this).scrollTop() > headHeight) {

                              //nav.addClass("d_fixed_top");

                              //alert(headHeight)

                          } else {

                              //nav.removeClass("d_fixed_top");

                          }

                          ;

                          if ($(this).scrollTop() > $(this).height()) {

                              //$('.d_top_btn').addClass('d_top_btn_show');

                          } else {

                              //$('.d_top_btn').removeClass('d_top_btn_show');

                          }

                      })/**/

                  }

    			});

         
		 //评论功能结束

          

       }

    });

	
  //点击商品规格，获取新的商品

  function arrowClick(self,myData){

    $(self).addClass("current").siblings().removeClass("current");

    //拼接属性

    var curEle = $(".pddc-stock-spec").find("a.current");

    var curSpec = [];

    $.each(curEle,function (i,v){

      curSpec.push($(v).attr("specs_value_id"));

    });

    var spec_string = curSpec.sort().join("|");

    //获取商品ID

    var spec_goods_id = myData.spec_list[spec_string];

	$("#ck_goods_id").val(spec_goods_id);
	
    //window.location.href = "product_detail.html?goods_id="+spec_goods_id;
	//切换规格后，价格，库存显示变化
 	$.ajax({

        url:ApiUrl+"/index.php?act=goods&op=goods_detail",

        type:"get",

        data:{goods_id:spec_goods_id},

        dataType:"json",

        success:function(result){
		   var data = result.datas;
		   var html_pic="";
            if(data.goods_image){

              var goods_image = data.goods_image.split(",");

              data.goods_image = goods_image;

            }else{

               data.goods_image = [];

            }
		   for(var i =0;i<goods_image.length;i++){
				html_pic = html_pic + '<div class="swipe-item"><img src="' + goods_image[i] + '"/></div>'
		   }
		   //html_pic = html_pic + "</div>";
		   	   
		   $(".swipe-wrap").html(html_pic);
		   $(".pds-tsize").html(goods_image.length);
		   picSwipe();
		   $("#title_now").html(data.goods_info.goods_name);
		   $("#goods_name_now").html(data.goods_info.goods_name);
		   $("#promotion_price_now_now").html(data.goods_info.promotion_price);
		   $("#goods_salenum_now").html(data.goods_info.goods_salenum);
		   $("#goods_price_now").html(data.goods_info.goods_price);
		   $("#goods_marketprice_now").html(data.goods_info.goods_marketprice);
		   $("#goods_storage_now").html(data.goods_info.goods_storage);
		   $("#goods_storage_e").val(data.goods_info.goods_storage);
	    }
	});

  } 



  function AddView(){//增加浏览记录

	  var goods_info = getcookie('goods');

	  var goods_id = GetQueryString('goods_id');

	  if(goods_id<1){

		  return false;

	  }



	  if(goods_info==''){

		  goods_info+=goods_id;

	  }else{



		  var goodsarr = goods_info.split('@');

		  if(contains(goodsarr,goods_id)){

			  return false;

		  }

		  if(goodsarr.length<5){

			  goods_info+='@'+goods_id;

		  }else{

			  goodsarr.splice(0,1);

			  goodsarr.push(goods_id);

			  goods_info = goodsarr.join('@');

		  }

	  }



	  addcookie('goods',goods_info);

	  return false;

  }



  function contains(arr, str) {//检测goods_id是否存入

	    var i = arr.length;

	    while (i--) {

	           if (arr[i] === str) {

	           return true;

	           }

	    }

	    return false;

	}

  $.sValid.init({

        rules:{

            buynum:"digits"

        },

        messages:{

            buynum:"请输入正确的数字"

        },

        callback:function (eId,eMsg,eRules){

            if(eId.length >0){

                var errorHtml = "";

                $.map(eMsg,function (idx,item){

                    errorHtml += "<p>"+idx+"</p>";

                });

                $.sDialog({

                    skin:"red",

                    content:errorHtml,

                    okBtn:false,

                    cancelBtn:false

                });

            }

        }

    });

  //检测商品数目是否为正整数

  function buyNumer(){

    $.sValid();

  }

});

$(document).ready(function(){ 



var headHeight=200; 

 

var nav=$(".d_pro_tle"); 



  $(window).scroll(function(){ 

   

    if($(this).scrollTop()>headHeight){ 

    	//nav.addClass("d_fixed_top"); 

    } 

    else{ 

    	//nav.removeClass("d_fixed_top"); 

    } 

  }) 



  



})



window.onload=function(){


}

//bottom nav 33 hao-v3 by 33h ao.com Qq 1244 986 40
$(function(){
	setTimeout(function(){
		if($("#content .container").height()<$(window).height())
		{
			$("#content .container").css("min-height",$(window).height());
		}
	},300);
	$("#bottom .nav .get_down").click(function(){
		$("#bottom .nav").animate({"bottom":"-50px"});
		$("#nav-tab").animate({"bottom":"0px"});
	});
	$("#nav-tab-btn").click(function(){
		$("#bottom .nav").animate({"bottom":"0px"});
		$("#nav-tab").animate({"bottom":"-40px"});
	});
	setTimeout(function(){$("#bottom .nav .get_down").click();},500);
	$("#scrollUp").click(function(t) {
		$("html, body").scrollTop(300);
		$("html, body").animate( {
			scrollTop : 0
		}, 300);
		t.preventDefault()
	});
});

$(function() {
    $(".input-del").click(function() {
        $(this).parent().removeClass("write").find("input").val("");
        btnCheck($(this).parents("form"))
    });
    $("body").on("click", "label",
    function() {
        if ($(this).has('input[type="radio"]').length > 0) {
            $(this).addClass("checked").siblings().removeAttr("class").find('input[type="radio"]').removeAttr("checked")
        } else if ($(this).has('[type="checkbox"]')) {
            if ($(this).find('input[type="checkbox"]').prop("checked")) {
                $(this).addClass("checked")
            } else {
                $(this).removeClass("checked")
            }
        }
    });
    if ($("body").hasClass("scroller-body")) {
        new IScroll(".scroller-body", {
            mouseWheel: true,
            click: true
        })
    }
    $("#header").on("click", "#header-nav",
    function() {
        if ($(".nctouch-nav-layout").hasClass("show")) {
            $(".nctouch-nav-layout").removeClass("show")
        } else {
            $(".nctouch-nav-layout").addClass("show")
        }
    });
    $("#header").on("click", ".nctouch-nav-layout",
    function() {
        $(".nctouch-nav-layout").removeClass("show")
    });
    $(document).scroll(function() {
        $(".nctouch-nav-layout").removeClass("show")
    });
    $(document).scroll(function() {
        e()
    });
    $(".fix-block-r,footer").on("click", ".gotop",
    function() {
        btn = $(this)[0];
        this.timer = setInterval(function() {
            $(window).scrollTop(Math.floor($(window).scrollTop() * .8));
            if ($(window).scrollTop() == 0) clearInterval(btn.timer, e)
        },
        10)
    });
    function e() {
        $(window).scrollTop() == 0 ? $("#goTopBtn").addClass("hide") : $("#goTopBtn").removeClass("hide")
    }
    $.scrollTransparent();
}); (function($) {
    $.extend($, {
        scrollTransparent: function(e) {
            var t = {
                valve: "#header",
                scrollHeight: 50
            };
            var e = $.extend({},
            t, e);
            function a() {
                $(window).scroll(function() {
                    if ($(window).scrollTop() <= e.scrollHeight) {
                        $(e.valve).addClass("transparent").removeClass("posf")
                    } else {
                        $(e.valve).addClass("posf").removeClass("transparent")
                    }
                })
            }
            return this.each(function() {
                a()
            })()
        },
        areaSelected: function(options) {
            var defaults = {
                success: function(e) {}
            };
            var options = $.extend({},
            defaults, options);
            var ASID = 0;
            var ASID_1 = 0;
            var ASID_2 = 0;
            var ASID_3 = 0;
            var ASNAME = "";
            var ASINFO = "";
            var ASDEEP = 1;
            var ASINIT = true;
            function _init() {
                if ($("#areaSelected").length > 0) {
                    $("#areaSelected").remove()
                }
                var e = '<div id="areaSelected">' + '<div class="nctouch-full-mask left">' + '<div class="nctouch-full-mask-bg"></div>' + '<div class="nctouch-full-mask-block">' + '<div class="header">' + '<div class="header-wrap">' + '<div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>' + '<div class="header-title">' + "<h1>选择地区</h1>" + "</div>" + '<div class="header-r"><a href="javascript:void(0);"><i class="close"></i></a></div>' + "</div>" + "</div>" + '<div class="nctouch-main-layout">' + '<div class="nctouch-single-nav">' + '<ul id="filtrate_ul" class="area">' + '<li class="selected"><a href="javascript:void(0);">一级地区</a></li>' + '<li><a href="javascript:void(0);" >二级地区</a></li>' + '<li><a href="javascript:void(0);" >三级地区</a></li>' + "</ul>" + "</div>" + '<div class="nctouch-main-layout-a"><ul class="nctouch-default-list"></ul></div>' + "</div>" + "</div>" + "</div>" + "</div>";
                $("body").append(e);
                _getAreaList();
                _bindEvent();
                _close()
            }
            function _getAreaList() {
                $.ajax({
                    type: "get",
                    url: ApiUrl + "/index.php?act=area&op=area_list",
                    data: {
                        area_id: ASID
                    },
                    dataType: "json",
                    async: false,
                    success: function(e) {
                        if (e.datas.area_list.length == 0) {
                            _finish();
                            return false
                        }
                        if (ASINIT) {
                            ASINIT = false
                        } else {
                            ASDEEP++
                        }
                        $("#areaSelected").find("#filtrate_ul").find("li").eq(ASDEEP - 1).addClass("selected").siblings().removeClass("selected");
                        checkLogin(e.login);
                        var t = e.datas;
                        var a = "";
                        for (var n = 0; n < t.area_list.length; n++) {
                            a += '<li><a href="javascript:void(0);" data-id="' + t.area_list[n].area_id + '" data-name="' + t.area_list[n].area_name + '"><h4>' + t.area_list[n].area_name + '</h4><span class="arrow-r"></span> </a></li>'
                        }
                        $("#areaSelected").find(".nctouch-default-list").html(a);
                        if (typeof myScrollArea == "undefined") {
                            if (typeof IScroll == "undefined") {
                                $.ajax({
                                    url: WapSiteUrl + "/js/iscroll.js",
                                    dataType: "script",
                                    async: false
                                })
                            }
                            myScrollArea = new IScroll("#areaSelected .nctouch-main-layout-a", {
                                mouseWheel: true,
                                click: true
                            })
                        } else {
                            myScrollArea.refresh()
                        }
                    }
                });
                return false
            }
            function _bindEvent() {
                $("#areaSelected").find(".nctouch-default-list").off("click", "li > a");
                $("#areaSelected").find(".nctouch-default-list").on("click", "li > a",
                function() {
                    ASID = $(this).attr("data-id");
                    eval("ASID_" + ASDEEP + "=$(this).attr('data-id')");
                    ASNAME = $(this).attr("data-name");
                    ASINFO += ASNAME + " ";
                    var _li = $("#areaSelected").find("#filtrate_ul").find("li").eq(ASDEEP);
                    _li.prev().find("a").attr({
                        "data-id": ASID,
                        "data-name": ASNAME
                    }).html(ASNAME);
                    if (ASDEEP == 3) {
                        _finish();
                        return false
                    }
                    _getAreaList()
                });
                $("#areaSelected").find("#filtrate_ul").off("click", "li > a");
                $("#areaSelected").find("#filtrate_ul").on("click", "li > a",
                function() {
                    if ($(this).parent().index() >= $("#areaSelected").find("#filtrate_ul").find(".selected").index()) {
                        return false
                    }
                    ASID = $(this).parent().prev().find("a").attr("data-id");
                    ASNAME = $(this).parent().prev().find("a").attr("data-name");
                    ASDEEP = $(this).parent().index();
                    ASINFO = "";
                    for (var e = 0; e < $("#areaSelected").find("#filtrate_ul").find("a").length; e++) {
                        if (e < ASDEEP) {
                            ASINFO += $("#areaSelected").find("#filtrate_ul").find("a").eq(e).attr("data-name") + " "
                        } else {
                            var t = "";
                            switch (e) {
                            case 0:
                                t = "一级地区";
                                break;
                            case 1:
                                t = "二级地区";
                                break;
                            case 2:
                                t = "三级地区";
                                break
                            }
                            $("#areaSelected").find("#filtrate_ul").find("a").eq(e).html(t)
                        }
                    }
                    _getAreaList()
                })
            }
            function _finish() {
                var e = {
                    area_id: ASID,
                    area_id_1: ASID_1,
                    area_id_2: ASID_2,
                    area_id_3: ASID_3,
                    area_name: ASNAME,
                    area_info: ASINFO
                };
                options.success.call("success", e);
                if (!ASINIT) {
                    $("#areaSelected").find(".nctouch-full-mask").addClass("right").removeClass("left")
                }
                return false
            }
            function _close() {
                $("#areaSelected").find(".header-l").off("click", "a");
                $("#areaSelected").find(".header-l").on("click", "a",
                function() {
                    $("#areaSelected").find(".nctouch-full-mask").addClass("right").removeClass("left")
                });
                return false
            }
            return this.each(function() {
                return _init()
            })()
        },
        animationLeft: function(e) {
            var t = {
                valve: ".animation-left",
                wrapper: ".nctouch-full-mask",
                scroll: ""
            };
            var e = $.extend({},
            t, e);
            function a() {
                $(e.valve).click(function() {
                    $(e.wrapper).removeClass("hide").removeClass("right").addClass("left");
                    if (e.scroll != "") {
                        if (typeof myScrollAnimationLeft == "undefined") {
                            if (typeof IScroll == "undefined") {
                                $.ajax({
                                    url: WapSiteUrl + "/js/iscroll.js",
                                    dataType: "script",
                                    async: false
                                })
                            }
                            myScrollAnimationLeft = new IScroll(e.scroll, {
                                mouseWheel: true,
                                click: true
                            })
                        } else {
                            myScrollAnimationLeft.refresh()
                        }
                    }
                });
                $(e.wrapper).on("click", ".header-l > a",
                function() {
                    $(e.wrapper).addClass("right").removeClass("left")
                })
            }
            return this.each(function() {
                a()
            })()
        },
        animationUp: function(e) {
            var t = {
                valve: ".animation-up",
                wrapper: ".nctouch-bottom-mask",
                scroll: ".nctouch-bottom-mask-rolling",
                start: function() {},
                close: function() {}
            };
            var e = $.extend({},
            t, e);
            function a() {
                e.start.call("start");
                $(e.wrapper).removeClass("down").addClass("up");
                if (e.scroll != "") {
                    if (typeof myScrollAnimationUp == "undefined") {
                        if (typeof IScroll == "undefined") {
                            $.ajax({
                                url: WapSiteUrl + "/js/iscroll.js",
                                dataType: "script",
                                async: false
                            })
                        }
                        myScrollAnimationUp = new IScroll(e.scroll, {
                            mouseWheel: true,
                            click: true
                        })
                    } else {
                        myScrollAnimationUp.refresh()
                    }
                }
            }
            return this.each(function() {
                if (e.valve != "") {
                    $(e.valve).on("click",
                    function() {
                        a()
                    })
                } else {
                    a()
                }
                $(e.wrapper).on("click", ".nctouch-bottom-mask-bg,.nctouch-bottom-mask-close",
                function() {
                    $(e.wrapper).addClass("down").removeClass("up");
                    e.close.call("close")
                })
            })()
        }
    })
})(Zepto);

































