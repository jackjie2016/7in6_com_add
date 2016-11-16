<?php
 @ini_set('session.name','PHPSESSID');
        @ini_set("session.save_handler", "memcache");
		@ini_set('session.cookie_domain', '.7in6.com');
	    @ini_set("session.save_path", '10.169.216.146:11211');
session_start();
//判断是否已经登录
if(isset($_SESSION['slast_key'])) 
{
	@header("Location:".SHOP_SITE_URL."/index.php");
	exit;
}
include_once(BASE_PATH.DS.'api'.DS.'sina'.DS.'config.php' );
include_once(BASE_PATH.DS.'api'.DS.'sina'.DS.'saetv2.ex.class.php' );
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
@header("location:$code_url");
exit;
?>