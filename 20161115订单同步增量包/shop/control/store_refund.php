<?php

/**

 * 卖家退款

 *

 *

 *

 *** by 网店技术交流中心 www.7in6.com*/





defined('InShopNC') or exit('Access Invalid!');



class store_refundControl extends BaseSellerControl {

	public function __construct() {

		parent::__construct();

		$model_refund = Model('refund_return');

		$model_refund->getRefundStateArray();

        Language::read('member_store_index');

	}

	/**

	 * 退款记录列表页

	 *

	 */

	public function indexOp() {

		$model_refund = Model('refund_return');

		$condition = array();

		$condition['store_id'] = $_SESSION['store_id'];



		$keyword_type = array('order_sn','refund_sn','buyer_name');

		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {

			$type = $_GET['type'];

			$condition[$type] = array('like','%'.$_GET['key'].'%');

		}

		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {

			$add_time_from = strtotime(trim($_GET['add_time_from']));

			$add_time_to = strtotime(trim($_GET['add_time_to']));

			if ($add_time_from !== false || $add_time_to !== false) {

				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));

			}

		}

		$seller_state = intval($_GET['state']);

		if ($seller_state > 0) {

		    $condition['seller_state'] = $seller_state;

		}

		$order_lock = intval($_GET['lock']);

		if ($order_lock != 1) {

		    $order_lock = 2;

		}

		$_GET['lock'] = $order_lock;

		$condition['order_lock'] = $order_lock;



		$refund_list = $model_refund->getRefundList($condition,10);

		Tpl::output('refund_list',$refund_list);

		Tpl::output('show_page',$model_refund->showpage());

		self::profile_menu('refund',$order_lock);

		Tpl::showpage('store_refund');

	}

	/**

	 * 退款审核页

	 *

	 */

	public function editOp() {

		$model_refund = Model('refund_return');

		$condition = array();

		$condition['store_id'] = $_SESSION['store_id'];

		$condition['refund_id'] = intval($_GET['refund_id']);

		$refund_list = $model_refund->getRefundList($condition);

		$refund = $refund_list[0];

		if (chksubmit()) {

			$reload = 'index.php?act=store_refund&lock=1';

			if ($refund['order_lock'] == '2') {

			    $reload = 'index.php?act=store_refund&lock=2';

			}

			if ($refund['seller_state'] != '1') {//检查状态,防止页面刷新不及时造成数据错误

				showDialog(Language::get('wrong_argument'),$reload,'error');

			}

			$order_id = $refund['order_id'];

			$refund_array = array();

			$refund_array['seller_time'] = time();

			$refund_array['seller_state'] = $_POST['seller_state'];//卖家处理状态:1为待审核,2为同意,3为不同意

			$refund_array['seller_message'] = $_POST['seller_message'];

			if ($refund_array['seller_state'] == '3') {

			    $refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成

			} else {

			    $refund_array['seller_state'] = '2';

			    $refund_array['refund_state'] = '2';

			}

			$state = $model_refund->editRefundReturn($condition, $refund_array);

			if ($state) {

    			if ($refund_array['seller_state'] == '3' && $refund['order_lock'] == '2') {

    			    $model_refund->editOrderUnlock($order_id);//订单解锁
                            
                            /*edit bu peiyu start 拒绝退款*/
                            $where = array();
                            $where['order_id']=$order_id;
                            $res = $model_refund->table('order')->where($where)->select();
                            $order_post =array();
                            $order_post['sp_statusSucc'] = 20;
                            $order_post['sp_statusFail'] = '003';
                            $order_post['sp_orderNum'] = $res[0]['order_sn'] ;
                            $order_post['sp_addressee'] = '';
                            $order_post['sp_address']   =  '';
                            $order_post['sp_mobile']   =  '';
                            $order_post['sp_goods']   =   '';
                            $order_post['sp_orderPayTime']   =  '';
                            $order_post['sp_orderRremarks']   =  '';
                            $order_post['sp_goods']   =   '';
                            $order_post['sp_orderRremarks']   =  '';
                            $order_post['sp_invoiceInfo']   =  '';
                            $order_post['sp_orderFare']   =  '';
                             //后期修改（需要加积分）
                            $order_post['sp_orderDiscount']   = '';
                            $order_post['sp_transType']   =  '';
                            //组装secret
                            $secret = 'meinong88';
                            foreach($order_post as $k =>$v){
                                 $secret.=$v;
                            }
                            $secret = md5($secret);
                            $order_post['secret']   = $secret;
                            $url = 'http://118.178.230.163/oa/orderAndStatusApi.do';
                            $res = request_post($url, $order_post);    
                            //判断订单是否同步成功
                            $obj = json_decode($res);
                            //写入日志
                            if($obj->code==1){
                                $dir = '../shop/api/interface/log/';
                                $name='';
                                $name.=substr(Date("Y"),2,4).Date("m").'.txt';
                                $path = $dir.$name;
                                $content = date('y-m-d h:i:s',time()).'订单编号为'.$obj->sp_orderNum."拒绝退款状态同步到工单失败 \r\n";
                                w_log($path,$content);
                            }
                            /*edit by peiyu stop*/
                            
                        }else{
                            /*edit bu peiyu start 拒绝退款*/
                            $where = array();
                            $where['order_id']=$order_id;
                            $res = $model_refund->table('order')->where($where)->select();
                            $order_post =array();
                            $order_post['sp_statusSucc'] = 20;
                            $order_post['sp_statusFail'] = '012';
                            $order_post['sp_orderNum'] = $res[0]['order_sn'] ;
                            $order_post['sp_addressee'] = '';
                            $order_post['sp_address']   =  '';
                            $order_post['sp_mobile']   =  '';
                            $order_post['sp_goods']   =   '';
                            $order_post['sp_orderPayTime']   =  '';
                            $order_post['sp_orderRremarks']   =  '';
                            $order_post['sp_goods']   =   '';
                            $order_post['sp_orderRremarks']   =  '';
                            $order_post['sp_invoiceInfo']   =  '';
                            $order_post['sp_orderFare']   =  '';
                             //后期修改（需要加积分）
                            $order_post['sp_orderDiscount']   = '';
                            $order_post['sp_transType']   =  '';
                            //组装secret
                            $secret = 'meinong88';
                            foreach($order_post as $k =>$v){
                                 $secret.=$v;
                            }
                            $secret = md5($secret);
                            $order_post['secret']   = $secret;
                            $url = 'http://118.178.230.163/oa/orderAndStatusApi.do';
                            $res = request_post($url, $order_post);    
                            //判断订单是否同步成功
                            $obj = json_decode($res);
                            //写入日志
                            if($obj->code==1){
                                $dir = '../shop/api/interface/log/';
                                $name='';
                                $name.=substr(Date("Y"),2,4).Date("m").'.txt';
                                $path = $dir.$name;
                                $content = date('y-m-d h:i:s',time()).'订单编号为'.$obj->sp_orderNum."客服同意退款状态同步到工单失败 \r\n";
                                w_log($path,$content);
                            }
                            /*edit by peiyu stop*/      
                        }

    			$this->recordSellerLog('退款处理，退款编号：'.$refund['refund_sn']);
                        
                        /* edit by peiyu start将用户退款同步到工单*/
                        
                        /* edit by peiyu stop*/



    			// 发送买家消息

                $param = array();

                $param['code'] = 'refund_return_notice';

                $param['member_id'] = $refund['buyer_id'];

                $param['param'] = array(

                    'refund_url'=> urlShop('member_refund', 'view', array('refund_id' => $refund['refund_id'])),

                    'refund_sn' => $refund['refund_sn']

                );

                QueueClient::push('sendMemberMsg', $param);



				showDialog(Language::get('nc_common_save_succ'),$reload,'succ');

			} else {

				showDialog(Language::get('nc_common_save_fail'),$reload,'error');

			}

		}

		Tpl::output('refund',$refund);

		$info['buyer'] = array();

	    if(!empty($refund['pic_info'])) {

	        $info = unserialize($refund['pic_info']);

	    }

		Tpl::output('pic_list',$info['buyer']);

		$model_member = Model('member');

		$member = $model_member->getMemberInfoByID($refund['buyer_id']);

		Tpl::output('member',$member);

		$condition = array();

		$condition['order_id'] = $refund['order_id'];

		$model_refund->getRightOrderList($condition, $refund['order_goods_id']);

		Tpl::showpage('store_refund_edit');

	}

	/**

	 * 退款记录查看页

	 *

	 */

	public function viewOp() {

		$model_refund = Model('refund_return');

		$condition = array();

		$condition['store_id'] = $_SESSION['store_id'];

		$condition['refund_id'] = intval($_GET['refund_id']);

		$refund_list = $model_refund->getRefundList($condition);

		$refund = $refund_list[0];

		Tpl::output('refund',$refund);

		$info['buyer'] = array();

	    if(!empty($refund['pic_info'])) {

	        $info = unserialize($refund['pic_info']);

	    }

		Tpl::output('pic_list',$info['buyer']);

		$model_member = Model('member');

		$member = $model_member->getMemberInfoByID($refund['buyer_id']);

		Tpl::output('member',$member);

		$condition = array();

		$condition['order_id'] = $refund['order_id'];

		$model_refund->getRightOrderList($condition, $refund['order_goods_id']);

		Tpl::showpage('store_refund_view');

	}

	/**

	 * 用户中心右边，小导航

	 *

	 * @param string	$menu_type	导航类型

	 * @param string 	$menu_key	当前导航的menu_key

	 * @return

	 */

	private function profile_menu($menu_type,$menu_key='') {

		$menu_array = array();

		switch ($menu_type) {

			case 'refund':

				$menu_array = array(

					array('menu_key'=>'2','menu_name'=>'售前退款',	'menu_url'=>'index.php?act=store_refund&lock=2'),

					array('menu_key'=>'1','menu_name'=>'售后退款','menu_url'=>'index.php?act=store_refund&lock=1')

				);

				break;

		}

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

	}

}

