$(function() {
    var key = getcookie('key');
    if (key == '') {
		
        //location.href = 'login.html';
    }
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=member_index",
        data: {key: key},
        dataType: 'json',
        //jsonp:'callback',
        success: function(result) {
            checklogin(result.login);
			//进度条
			var point = result.datas.member_info.point; //当前印币
			var goal_point = 100;//兑换一个月物业费需要的印币
			var progress = point*100/goal_point+"%";
			
			var progress_ball ;
			if(point<=7){
				progress_ball = point*100/goal_point+"%";
			}else{
				progress_ball = (point-7)*100/goal_point+"%";
			}
			//console.log(result.datas.member_info.point);
			$(".progress").html(progress);
			
			$(".line-base-checked").css("width",progress);
			
			$(".line-ball").css("left",progress_ball);
			
			
            $('#username').html(result.datas.member_info.user_name);
            $('#point').html(result.datas.member_info.point);
            $('#predepoit').html(result.datas.member_info.predepoit);
            $('#available_rc_balance').html(result.datas.member_info.available_rc_balance);
            // 201608261455-吴钢剑-添加头像；
			if(result.datas.member_info.avator){
                $('#avatar').attr("src", result.datas.member_info.avator);
            }
            

            //微信三级返利插件start 
            $('#invite_people').html(result.datas.member_info.invite_people);
            $('#member_id').html(result.datas.member_info.member_id);
            $('#nickname').html(result.datas.member_info.nickname);
            $('#zcount1').html(result.datas.member_info.zcount1);
            $('#zcount2').html(result.datas.member_info.zcount2);
            $('#zcount3').html(result.datas.member_info.zcount3);

            $('#income_yesterday').html(result.datas.member_info.income_yesterday);
            $('#income_count').html(result.datas.member_info.income_count);
            $('#available_predeposit').html(result.datas.member_info.available_predeposit);
            //微信三级返利插件end
			$('#marketing').attr("href",WapSiteUrl+'/tmpl/member/my_promotion.html?member_name='+result.datas.member_info.user_name+'&form_username='+result.datas.member_info.user_name);
            return false;
        }
    });
});