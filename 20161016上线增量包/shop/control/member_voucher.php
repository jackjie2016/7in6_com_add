<?php

/**

 * 代金券

 *

 *

 *

 **by 网店技术交流中心 localhost/yangyangdi*/





defined('InShopNC') or exit('Access Invalid!');

class member_voucherControl extends BaseMemberControl{

	public function __construct() {

		parent::__construct();

		Language::read('member_layout,member_voucher');

		//判断系统是否开启代金券功能

		if (intval(C('voucher_allow')) !== 1){

			showMessage(Language::get('member_voucher_unavailable'),urlShop('member', 'home'),'html','error');

		}

	}

	/*

	 * 默认显示代金券模版列表

	 */

	public function indexOp() {

        $this->voucher_listOp() ;

    }



	/*

	 * 获取代金券模版详细信息

	 */

    public function voucher_listOp(){

		$model = Model('voucher');

                $list = $model->getMemberVoucherList($_SESSION['member_id'], $_GET['select_detail_state'], 10);

		//取已经使用过并且未有voucher_order_id的代金券的订单ID

		$used_voucher_code = array();

		$voucher_order = array();

		if (!empty($list)) {

		    foreach ($list as $v) {

		        if ($v['voucher_state'] == 2 && empty($v['voucher_order_id'])) {

		            $used_voucher_code[] = $v['voucher_code'];

		        }

		    }

		}

        if (!empty($used_voucher_code)) {

            $order_list = Model('order')->getOrderCommonList(array('voucher_code'=>array('in',$used_voucher_code)),'order_id,voucher_code');

            if (!empty($order_list)) {

                foreach ($order_list as $v) {

                    $voucher_order[$v['voucher_code']] = $v['order_id'];

                    $model->editVoucher(array('voucher_order_id'=>$v['order_id']),array('voucher_code'=>$v['voucher_code']));

                }

            }

        }



	Tpl::output('list', $list);

	Tpl::output('voucherstate_arr', $model->getVoucherStateArray());

        Tpl::output('show_page',$model->showpage(2)) ;

        $this->profile_menu('voucher_list');

        Tpl::showpage('member_voucher.list');

    }
    
    
    /*封装的函数 eidt by peiyu start*/
    
    public function get_voucher1Op($voucher_id){
        
        $call_back = array();
        
        $member_id = $_SESSION['member_id'];     
		
        $model_voucher = Model()->table('voucher');

        $model_voucher_template = Model()->table('voucher_template');
        
        $where = array();
		
        $where['voucher_t_id'] = $voucher_id;

        $template_info = $model_voucher_template->field('*')->where($where)->find();

        $condition = array();

        $condition['voucher_t_id'] = $voucher_id;

        $condition['voucher_owner_id'] = $member_id;

        //检验是否第一次领取         

        $start_time = strtotime('2016-07-01');

        $end_time =   strtotime('2016-12-31');

        $member = Model()->table('member')->where("member_id=$member_id")->select();    

        if($member[0]['member_time']<$start_time || $member[0]['member_time']>$end_time ){

            $call_back['is_get']=1;
            echo json_encode($call_back);exit; 

        }
        
        //检查是否已经领过

        $v_info = $model_voucher->field('*')->where($condition)->find();

        if(count($v_info)>$template_info['voucher_t_eachlimit']&&$template_info['voucher_t_eachlimit']!=0){ // 优惠券是否领取

                $call_back['geted']=1;

                echo json_encode($call_back);exit;

        }
        
        if($template_info['voucher_t_start_date']>time()){ // 优惠券领取时间还没有开始
                $call_back['start']=1;
                echo json_encode($call_back);
                exit;
        }

        if($template_info['voucher_t_end_date']<time()){// 优惠券领取过期
                $call_back['end']=1;
                  echo json_encode($call_back);
                exit;
        }

        if($template_info['voucher_t_total']<=$template_info['voucher_t_giveout']){// 优惠券领取已经领完
                $call_back['total_zero']=1;
                echo json_encode($call_back);
                exit;
        }

        if (!$member_name){           // 获取用户信息

            $member_info = Model('member')->getMemberInfoByID($member_id);

            $member_name = $member_info['member_name'];

        }

        //添加代金券信

        $insert_arr = array();

        $insert_arr['voucher_code'] = mt_rand(10,99)

		      . sprintf('%010d',time() - 946656000)

		      . sprintf('%03d', (float) microtime() * 1000)

		      . sprintf('%03d', (int) $member_id % 1000);

        $insert_arr['voucher_t_id'] = $template_info['voucher_t_id'];

        $insert_arr['voucher_title'] = $template_info['voucher_t_title'];

        $insert_arr['voucher_desc'] = $template_info['voucher_t_desc'];

        $insert_arr['voucher_start_date'] = $template_info['voucher_t_start_date'];

        $insert_arr['voucher_end_date'] = $template_info['voucher_u_end_date'];
        $insert_arr['voucher_store_id'] = $template_info['voucher_t_store_id'];
		
		$price = rand($template_info['voucher_t_price']-($template_info['voucher_t_price_max']-$template_info['voucher_t_price']),$template_info['voucher_t_price_max']);
		if($price<$template_info['voucher_t_price']){$price=$template_info['voucher_t_price'];}
		
		if($template_info['is_mod_5']==1 && $template_info['is_random']==1){
			if($price % 5 !=0 ){
				if($price % 5 > 3 ){
					$price = $price + 5 - ($price % 5);
				}else{
					$price = $price - ($price % 5);
				}
			}
		}
	
        $insert_arr['voucher_price'] = $template_info['is_random']!=1?$template_info['voucher_t_price']:$price;

        $insert_arr['voucher_limit'] = $template_info['voucher_t_limit'];

        $insert_arr['voucher_t_sc_id'] = $template_info['voucher_t_sc_id'];
        $insert_arr['voucher_t_channel'] = $template_info['voucher_t_channel'];
        $insert_arr['voucher_t_kind'] = $template_info['voucher_t_kind'];

        $insert_arr['voucher_state'] = 1;

        $insert_arr['voucher_active_date'] = time();

        $insert_arr['voucher_owner_id'] = $member_id;

        $insert_arr['voucher_owner_name'] = $member_name;
        
        $insert_arr['voucher_t_sc_id'] =  $template_info['voucher_t_sc_id'];
        
        $insert_arr['voucher_t_channel'] = $template_info['voucher_t_channel'];
        
        $insert_arr['voucher_t_kind']   =  $template_info['voucher_t_kind'];
        
        $result = Model()->table('voucher')->insert($insert_arr);
        
        /*查询code start*/
        
        $voucher_info = Model()->table('voucher')->where("voucher_id=$result")->select(); 
        
        /*查询code stop*/
		
        $update=array();

        $update['voucher_t_giveout']=$template_info['voucher_t_giveout'] + 1; //更新发放数量

        $result=Model()->table('voucher_template')->where($where)->update($update);
		
        
    }
    
    /*封装的函数 eidt by peiyu stop*/
    public function get_voucherOp() {
		
		$vid = $_POST['voucher_id'];
		
                $vid = explode("|",$vid);         
                
                /*遍历取数组，此次互动只针对于一次领取*/
                

                foreach($vid as $v){  
                    
                    $result=$this->get_voucher1Op($v);
                    
		}
                
                $call_back['state']=1;
                
                echo json_encode($call_back);
                
                exit;
    }
       

	/**

	 * 用户中心右边，小导航

	 *

	 * @param string	$menu_type	导航类型

	 * @param string 	$menu_key	当前导航的menu_key

	 * @param array 	$array		附加菜单

	 * @return

	 */

	private function profile_menu($menu_key='') {

		$menu_array = array(

			1=>array('menu_key'=>'voucher_list','menu_name'=>Language::get('nc_myvoucher'),'menu_url'=>'index.php?act=member_voucher&op=voucher_list'),

		);

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

    }
    
        



}

