<?php
// defined('InShopNC') or exit('Access Invalid!');

$config = array();
$config['shop_site_url'] 		= 'http://www.7in6.com';
$config['cms_site_url'] 		= 'http://root.7in6.com/cms';
$config['microshop_site_url'] 	= 'http://root.7in6.com/microshop';
$config['circle_site_url'] 		= 'http://root.7in6.com/circle';
$config['admin_site_url'] 		= 'http://service.7in6.com';
$config['mobile_site_url'] 		= 'http://root.7in6.com/mobile';
$config['wap_site_url'] 		= 'http://root.7in6.com/wap';
$config['chat_site_url'] 		= 'http://root.7in6.com/chat';
$config['node_site_url'] 		= 'http://123.57.87.161:8090';
$config['upload_site_url']		= 'http://root.7in6.com/data/upload';
$config['resource_site_url']	= 'http://root.7in6.com/data/resource';

//微信登陆插件start
$config['appid']	= 'wx05afb6563cef4477';
$config['appsecret']       ='6850fa98f8d832d6ec87824d1deef631';
//微信登陆插件end

$config['version'] 		= '201411158256';
$config['setup_date'] 	= '2015-06-08 18:24:08';
$config['gip'] 			= 0;
$config['dbdriver'] 	= 'mysqli';
$config['tablepre']		= 'sykjshop_';

$config['db']['1']['dbhost']       = 'rm-wz955cr8bcr1i0766.mysql.rds.aliyuncs.com';
$config['db']['1']['dbport']       = '3306';
$config['db']['1']['dbuser']       = 'cogidoo_root';
$config['db']['1']['dbpwd']        = 'Yi*^pL*j8';

/* $config['db']['1']['dbhost']       = 'localhost';
$config['db']['1']['dbport']       = '3306';
$config['db']['1']['dbuser']       = 'root';
$config['db']['1']['dbpwd']        = 'cogidoo$2016@'; */
$config['db']['1']['dbname']       = '7in6_com';
$config['db']['1']['dbcharset']    = 'UTF-8';
$config['db']['slave']                  = $config['db']['master'];
$config['session_expire'] 	= 3600;
$config['lang_type'] 		= 'zh_cn';
$config['cookie_pre'] 		= 'BCA5_';
$config['thumb']['cut_type'] = 'gd';
$config['thumb']['impath'] = '';
$config['cache']['type'] 			= 'file';
//$config['redis']['prefix']      	= 'nc_';
//$config['redis']['master']['port']     	= 6379;
//$config['redis']['master']['host']     	= '127.0.0.1';
//$config['redis']['master']['pconnect'] 	= 0;
//$config['redis']['slave']      	    = array();
//$config['fullindexer']['open']      = false;
//$config['fullindexer']['appname']   = 'shopnc';
$config['debug'] 			= false;
$config['default_store_id'] = '1';
$config['url_model'] = true;
$config['subdomain_suffix'] = '';
//$config['session_type'] = 'redis';
//$config['session_save_path'] = 'tcp://127.0.0.1:6379';
$config['node_chat'] = true;
//流量记录表数量，为1~10之间的数字，默认为3，数字设置完成后请不要轻易修改，否则可能造成流量统计功能数据错误
$config['flowstat_tablenum'] = 3;
$config['sms']['gwUrl'] = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService';
$config['sms']['serialNumber'] = '';
$config['sms']['password'] = '';
$config['sms']['sessionKey'] = '';
$config['queue']['open'] = false;
$config['queue']['host'] = '127.0.0.1';
$config['queue']['port'] = 6379;
$config['cache_open'] = false;
$config['delivery_site_url']    = 'http://root.7in6.com/delivery';


$config['cache']['type']= 'memcache';
$config['memcache']['prefix'] = 'nc_'; // memcached存取时值时，自动添加的前缀
# 第 1 台 memcached 配置
$config['memcache'][1]['port']       = 11211; // memcached 监听端口号
$config['memcache'][1]['host']       = '10.169.216.146'; // memcached服务器IP地址
$config['memcache'][1]['pconnect']   = 0; //是否长连接 默认0(否)，1表示使用长连接

return $config;
