<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 * by shopjl.com 网店技术交流中心 运营版
 */

use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

         /*微信三级返利插件start  */ 
	public function setuidOp(){
		$uid = $_GET['uid'];
		if ($uid) {
			setcookie("uid", $uid, time() + 2592000);
		}
		output_data(array('uid' => $uid));
	}
        
         /*微信三级返利插件end */      
        
	/**
	 * 登录
	 */
	public function indexOp(){

      $model_member = Model('member');

        $array = array();
        //微信登陆插件start
        if($_POST['client']!=""){
             
             //微信登陆插件end   
            
			 if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
				output_error('登录失败');
			 }
			$array['member_name']	= $_POST['username'];
			$array['member_passwd']	= md5($_POST['password']);
			$member_info = $model_member->getMemberInfo($array);
	
			if(!empty($member_info)) {
				$token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
				if($token) {
					output_data(array('username' => $member_info['member_name'], 'key' => $token,'member_id' => $member_info['member_id']));
				} else {
					output_error('登录失败');
				}
			} else {
				output_error('用户名密码错误');
			}
        
         //微信登陆插件start
         }else{
            
			 $appid=APPID;
			 $appsecret=APPSECRET;
			 $access_token=get_access_token($appid, $appsecret);
         
			 $aa=http_request_json("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code");
			 $obj = json_decode($aa);
			 $openid=$obj->{'openid'}; 
	
             //刷新access_token
			 $refresh_token=$obj->{'refresh_token'};
			 $bb=http_request_json("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$appid."&grant_type=refresh_token&refresh_token=$refresh_token");
			 $bb = json_decode($bb);
			 $new_access_token=$bb->{'access_token'};
         
     
        
             //验证登陆      
			 $ee= http_request_json("https://api.weixin.qq.com/sns/userinfo?access_token=".$new_access_token."&openid=".$openid."&lang=zh_CN");
			 $ee = json_decode($ee);
        
			 $nickname=$ee->{'nickname'};
			 $headimgurl=$ee->{'headimgurl'};
			 $subscribe_time=$ee->{'subscribe_time'};
         
         	 //联合ID
         	 $unionid=$ee->{'unionid'};
        
         	 $array['wecha_id'] = $openid;    
     
             //微信三级返利插件start
             $uid = $_GET['uid'];
             if ($uid) {
             		setcookie("uid", $uid, time() + 2592000);
             }
             //微信三级返利插件end
             $register_info = array();
             if($subscribe_time){
				  $register_info['is_subscribe']=1;
				  $register_info['subscribe_time'] =$subscribe_time;
             }else{
				  $register_info['is_subscribe']=0;
				  $register_info['subscribe_time'] ="";
        	 }
         	$member_info = $model_member->getMemberInfo(array('weixin_unionid'=> $unionid));
          
			if(!empty($member_info)) {
				   //登陆
				$token = $this->_get_token_weixin($member_info['member_id'], $member_info['member_name'], 'wap');
				if($token) {
					 $data['nickname']= $nickname;
					 $data['member_avatar']= $headimgurl;
					 $condition['member_id']=$member_info['member_id'];
					 $model_member->updateWeixinMember($condition, $data);
					 
					 //如果是重复登录，时间超过2个小时以上，才算第二次登录
					 if($member_info['member_login_time'] + 7200 < time() ){
					 	$model_member->createSession($member_info);
					 }
					 
					 header('Location: '.WAP_SITE_URL.'/tmpl/member/jump.html?username='.$member_info['member_name'].'&key='.$token.'&member_id='.$member_info['member_id']);
				} else {
					output_error('登录失败');
				}
        	} else {
          		 //注册
            
				$register_info['wecha_id'] = $openid;
	  
				$password=md5(time()."nsdda".rand(1000, 9999));
				$register_info['password'] = $password;
				$register_info['password_confirm'] = $password;
				 //$register_info['email'] = $unionid.'@weixin.com';
				if($nickname){
					$register_info['nickname'] =$nickname;
				}else{
					$register_info['nickname'] ="微信用户";
				}
		 
				 //微信三级返利插件start
				$uid = $_COOKIE["uid"];
				if ($uid!= "") {
					$register_info['uid'] = $uid;
				}
					//微信三级返利插件end
	 
				$register_info['username'] = $nickname."_".rand(1000, 9999)."(wx)";
				$register_info['member_avatar'] = $headimgurl;
				$register_info['inviter_id'] = $uid;
				$register_info['uid'] = $uid;
		  
	  
				$member_info = $model_member->register($register_info,"weixin");
		
				if(!isset($member_info['error'])) {
					$token = $this->_get_token_weixin($member_info['member_id'], $member_info['member_name'], 'wap');
					if($token) {
				   		$model_member->createSession($member_info);
						header('Location: '.WAP_SITE_URL.'/tmpl/member/jump.html?username='.$member_info['member_name'].'&key='.$token.'&member_id='.$member_info['member_id']);
					 } else {
						   output_error('注册失败');
					}
				} else {
						output_error($member_info['error']);
				}
        	}
         }
         //微信登陆插件end     
        
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }
    
    
    //微信登陆插件start
     private function _get_token_weixin($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] ="wap";

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }
    //微信登陆插件end
    
    

	/**
	 * 注册
	 */
	public function registerOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
        
               //微信三级返利插件start
        $uid = $_COOKIE["uid"];
        if ($uid != "") {
            $register_info['uid'] = $uid;
        }
        //微信三级返利插件end

        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token,'member_id' => $member_info['member_id']));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
	
	/**
	 * 注册
	 */
	public function tel_registerOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['username'] = $_POST['username'];
		$register_info['user_mobile'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
		
        
               //微信三级返利插件start
        $uid = $_COOKIE["uid"];
        if ($uid != "") {
            $register_info['uid'] = $uid;
        }
        //微信三级返利插件end

        $member_info = $model_member->register_phone($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token,'member_id' => $member_info['member_id']));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
	/**
	 * 找回密码
	 */
	public function find_pwdOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['member_mobile'] = $_POST['username'];
        $register_info['member_passwd'] = $_POST['password'];

        $member_info = $model_member->find_pwd($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token,'member_id' => $member_info['member_id']));
            } else {
                output_error('修改失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
	/**
	 * 找回密码的发邮件处理
	 */
	public function find_pwemailOp(){
		if(empty($_POST['username'])){
			output_error('请输入用户名');
		}

		$member_model	= Model('member');
		$member	= $member_model->getMemberInfo(array('member_name'=>$_POST['username']));
		if(empty($member) or !is_array($member)){
			output_error('没有找到该用户！');	
		}

		if(empty($_POST['email'])){
			output_error('请输入邮箱');
		}

		if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){
			output_error('邮箱或用户名错误');
		}
		process::clear('forget');
		//产生密码
		$new_password	= random(15);
		if(!($member_model->editMember(array('member_id'=>$member['member_id']),array('member_passwd'=>md5($new_password))))){
			output_error('发送失败');
		}

		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_pwd'));
		$param = array();
		$param['site_name']	= C('site_name');
		$param['user_name'] = $_POST['username'];
		$param['new_password'] = $new_password;
		$param['site_url'] = SHOP_SITE_URL;
		$subject	= ncReplaceText($tpl_info['title'],$param);
		$message	= ncReplaceText($tpl_info['content'],$param);

		$email = new Email();
		$result	= $email->send_sys_email($_POST["email"],$subject,$message);
		output_error('新密码已经发送至您的邮箱，请尽快登录并更改密码！');		
	}	
}



	 //微信登陆插件start
	function get_access_token($appid,$secret){
	  $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;  
		$json=http_request_json($url);//这个地方不能用file_get_contents  
		$data=json_decode($json,true);
		if($data['access_token']){
		   return $data['access_token'];
		}else{
		   return "获取access_token错误";
		}
	}

function http_request_json($url){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL,$url);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   return $result;
}

//微信登陆插件end
