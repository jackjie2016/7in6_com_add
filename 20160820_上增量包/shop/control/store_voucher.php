<?php

/**

 * 代金券

 **by 网店技术交流中心 localhost/yangyangdi*/





defined('InShopNC') or exit('Access Invalid!');

class store_voucherControl extends BaseSellerControl{

	//定义代金券类常量

	const SECONDS_OF_30DAY = 2592000;

    private $applystate_arr;

    private $quotastate_arr;

    private $templatestate_arr;



	public function __construct() {

		parent::__construct() ;

		//读取语言包

		Language::read('member_layout,member_voucher');

		//判断系统是否开启代金券功能

		if (C('voucher_allow') != 1){

			showMessage(Language::get('voucher_unavailable'),'index.php?act=store','html','error');

		}

		//申请记录状态

		$this->applystate_arr = array('new'=>array(1,Language::get('voucher_applystate_new')),'verify'=>array(2,Language::get('voucher_applystate_verify')),'cancel'=>array(3,Language::get('voucher_applystate_cancel')));
		//套餐状态

		$this->quotastate_arr = array('activity'=>array(1,Language::get('voucher_quotastate_activity')),'cancel'=>array(2,Language::get('voucher_quotastate_cancel')),'expire'=>array(3,Language::get('voucher_quotastate_expire')));

		//代金券模板状态

		$this->templatestate_arr = array('usable'=>array(1,Language::get('voucher_templatestate_usable')),'disabled'=>array(2,Language::get('voucher_templatestate_disabled')));

		Tpl::output('applystate_arr',$this->applystate_arr);

		Tpl::output('quotastate_arr',$this->quotastate_arr);

		Tpl::output('templatestate_arr',$this->templatestate_arr);

	}

	/*  发送红包提醒  */
	public function send_weixinmsgOp(){
		set_time_limit(0);
		$access_token = get_access_token();
/*		send_template_message(user_template('oc9PrwwVIqO1vzhJiizuuAKJIQz0',5),$access_token);
		exit;*/
		$voucher_list = Model()->table('voucher')->field('voucher_owner_id,voucher_price')->where(array('voucher_t_id'=>'15','voucher_state'=>'1'))->select();
		$i=0;
		foreach($voucher_list as $v){
			$member_list = Model()->table('member')->field('wecha_id,member_name')->where(array('member_id'=>$v['voucher_owner_id']))->select();
			foreach($member_list as $m){
				if($m['wecha_id']!=''){
				$i++;
					if($m['wecha_id']=='oc9PrwxONc0HkCqZ71_vRjVTq3us'){echo $i;exit;}
					if($i>1 && $i<=10 ){
						send_template_message(user_template($m['wecha_id'],$v['voucher_price']),$access_token);
						echo $m['member_name'].':'.$m['wecha_id']."<br>";
					}
				}
			}
		}
		echo $i;
		//$member_list = Model()->table('member')->field('wecha_id')->where(array('member_id'=>array('in',$vv)))->select();
		
		
		//$i=0;
		//foreach($member_list as $m){
			
/*			if($m['wecha_id']!=""){
				$i++;
				echo $m['wecha_id']."<br>";
				
				send_template_message(user_template("oc9PrwxONc0HkCqZ71_vRjVTq3us",$rmb),$access_token);
			}*/
			
		//}
		//echo $i;
	}


	/*

	 * 实体店线下核销

	 */

	public function output_data($datas, $extend_data = array()) {
		$data = array();
		$data['code'] = 200;
	
		if(!empty($extend_data)) {
			$data = array_merge($data, $extend_data);
		}
	
		$data['datas'] = $datas;
	
		if(!empty($_GET['callback'])) {
			echo $_GET['callback'].'('.json_encode($data).')';die;
		} else {
			echo json_encode($data);die;
		}
	}	 

	public function voucher_offline_useOp() {
		//$this->templatelistOp();
		
		$voucher_code = $_POST['voucher_code'];
		
		$order_id = $_POST['order_id'];
		
		$voucher_order_value = $_POST['order_value'];
		
		if(empty($_POST['voucher_code']) && empty($_POST['voucher_code'])){Tpl::showpage('store_voucher_offline_use') ; exit;}
		
		$call_back = array();
		
		$model_voucher = Model()->table('voucher');
		
		$where = array();
		
		$where['voucher_code'] = $voucher_code;
		
		$voucher_info = $model_voucher->field('*')->where($where)->find();
		
		if(count($voucher_info)==0){
			$call_back['nodata']=1;
			$this->output_data($call_back);exit;
		}
		
		if($voucher_info['voucher_state']!=1){
			$call_back['voucher_state']=$voucher_info['voucher_state'];
			$this->output_data($call_back);exit;
		}
		
		//更新代金卷状态
		
		$update=array();
		
		$update['voucher_state'] = 2;
		
		$update['voucher_order_id'] = $order_id;
	
		$update['voucher_used_date'] = time();
		
		$update['voucher_order_value'] = $voucher_order_value;
		
		$where['voucher_state'] = 1;
		

		$result=$model_voucher->where($where)->update($update);
		
		if($result){
			
			$call_back['update'] = 1;
			$call_back['voucher_code'] = $voucher_code;
			$call_back['order_id'] = $order_id;
			
		}else{
			
			$call_back['update'] = 0;
			
		}
		
		$this->output_data($call_back);exit;
		
		
		
	}
	
	
	/*

	 * 默认显示代金券模版列表

	 */

	public function indexOp() {
        $this->templatelistOp();

    }

	/*

	 * 代金券模版列表

	 */

	public function templatelistOp(){

        //检查过期的代金券模板状态设为失效

        $this->check_voucher_template_expire();

        $model = Model('voucher');



        if (checkPlatformStore()) {

            Tpl::output('isOwnShop', true);

        } else {

            //查询是否存在可用套餐

            $current_quota = $model->getCurrentQuota($_SESSION['store_id']);

            Tpl::output('current_quota',$current_quota);

        }



		//查询列表

		$param = array();

		$param['voucher_t_store_id'] = $_SESSION['store_id'];

		if(trim($_GET['txt_keyword'])){

			$param['voucher_t_title'] = array('like','%'.trim($_GET['txt_keyword']).'%');

		}

		$select_state = intval($_GET['select_state']);

		if($select_state){

			$param['voucher_t_state'] = $select_state;

		}

		if($_GET['txt_startdate']){

			$param['voucher_t_end_date'] = array('egt',strtotime($_GET['txt_startdate']));

		}

		if($_GET['txt_enddate']){

			$param['voucher_t_start_date'] = array('elt',strtotime($_GET['txt_enddate']));

		}

		$list = $model->table('voucher_template')->where($param)->order('voucher_t_id desc')->page(10)->select();

		if(is_array($list)){

			foreach ($list as $key=>$val){

				if (!$val['voucher_t_customimg'] || !file_exists(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$val['voucher_t_customimg'])){

					$list[$key]['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(60);

				}else{

					$list[$key]['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $val['voucher_t_customimg']);

				}

			}

		}



        $this->profile_menu('voucher','templatelist');

		Tpl::output('list',$list);

		Tpl::output('show_page',$model->showpage(2));

		Tpl::showpage('store_voucher_template.index') ;

	}



	/**

     * 购买套餐

     */

	public function quotaaddOp(){

		if (chksubmit()){

	        $quota_quantity = intval($_POST['quota_quantity']);

	        if($quota_quantity <= 0 || $quota_quantity > 12) {

	            showDialog(Language::get('voucher_apply_num_error'));

	        }

	        //获取当前价格

	        $current_price = intval(C('promotion_voucher_price'));



            $model = Model();

	        $model_voucher = Model('voucher');



            //获取该用户已有套餐

            $current_quota = $model_voucher->getCurrentQuota($_SESSION['store_id']);

            $add_time = 86400 *30 * $quota_quantity;

            if(empty($current_quota)) {

                //生成套餐

                $param = array();

                $param['quota_memberid'] = $_SESSION['member_id'];

                $param['quota_membername'] = $_SESSION['member_name'];

                $param['quota_storeid'] = $_SESSION['store_id'];

                $param['quota_storename'] = $_SESSION['store_name'];

                $param['quota_starttime'] = TIMESTAMP;

                $param['quota_endtime'] = TIMESTAMP + $add_time;

                $param['quota_state'] = 1;

                $reault = $model->table('voucher_quota')->insert($param);

            } else {

                $param = array();

                $param['quota_endtime'] = array('exp', 'quota_endtime + ' . $add_time);

                $reault = $model->table('voucher_quota')->where(array('quota_id'=>$current_quota['quota_id']))->update($param);

            }



            //记录店铺费用

            $this->recordStoreCost($current_price * $quota_quantity, '购买代金券套餐');



            $this->recordSellerLog('购买'.$quota_quantity.'份代金券套餐，单价'.$current_price.L('nc_yuan'));



            if($reault){

                showDialog(Language::get('voucher_apply_buy_succ'),'index.php?act=store_voucher&op=quotalist','succ');

            } else {

                showDialog(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=quotalist');

            }

        }else {

            //输出导航

	        self::profile_menu('quota_add','quotaadd');

	        Tpl::showpage('store_voucher_quota.add');

		}

    }

	/*

	 * 代金券模版添加

	 */

    public function templateaddOp(){

        $model = Model('voucher');
        
        

        if ($isOwnShop = checkPlatformStore()) {

            Tpl::output('isOwnShop', true);

        } else {

            //查询当前可以套餐

            $quotainfo = $model->getCurrentQuota($_SESSION['store_id']);

            if(empty($quotainfo)){

                showMessage(Language::get('voucher_template_quotanull'),'index.php?act=store_voucher&op=quotaadd','html','error');

            }



            //查询该套餐下代金券模板列表

            $count = $model->table('voucher_template')->where(array('voucher_t_quotaid'=>$quotainfo['quota_id'],'voucher_t_state'=>$this->templatestate_arr['usable'][0]))->count();

            if ($count >= C('promotion_voucher_storetimes_limit')){

                $message = sprintf(Language::get('voucher_template_noresidual'),C('promotion_voucher_storetimes_limit'));

                showMessage($message,'index.php?act=store_voucher&op=templatelist','html','error');

            }

        }



        //查询面额列表

        $pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
        
         //商品分类
        
        $store_class = $model->table('goods_class')->where("gc_parent_id=0")->field('gc_id,gc_name')->select();
          
        Tpl::output('store_class', $store_class);

        if(empty($pricelist)){

        	//showMessage(Language::get('voucher_template_pricelisterror'),'index.php?act=store_voucher&op=templatelist','html','error');

        }

        if(chksubmit()){

	        //验证提交的内容面额不能大于限额

	        $obj_validate = new Validate();

	        $obj_validate->validateparam = array(

	            array("input"=>$_POST['txt_template_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('voucher_template_title_error')),

	            array("input"=>$_POST['txt_template_total'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_total_error')),

	            array("input"=>$_POST['select_template_price'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_price_error')),

	            array("input"=>$_POST['txt_template_limit'], "require"=>"true","validator"=>"Double","message"=>Language::get('voucher_template_limit_error')),

	            array("input"=>$_POST['txt_template_describe'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('voucher_template_describe_error')),

	        );

	        $error = $obj_validate->validate();

	        //金额验证

	        $price = intval($_POST['select_template_price'])>0?intval($_POST['select_template_price']):0;
			
			$price_max = intval($_POST['template_price_max'])>0?intval($_POST['template_price_max']):0;

	        foreach($pricelist as $k=>$v){

        		if($v['voucher_price'] == $price){

        			$chooseprice = $v;//取得当前选择的面额记录

        		}

        	}

        	if(empty($chooseprice)){

        		//$error.=Language::get('voucher_template_pricelisterror');

        	}

	        $limit = floatval($_POST['txt_template_limit'])>0?floatval($_POST['txt_template_limit']):0;

//	        if($price>=$limit) $error.=Language::get('voucher_template_price_error');
//
//	        if ($error){
//
//	            showDialog($error,'reload','error');
//
//	        }else {
                    
	        	$insert_arr = array();

	        	$insert_arr['voucher_t_title'] = trim($_POST['txt_template_title']);

	        	$insert_arr['voucher_t_desc'] = trim($_POST['txt_template_describe']);

	        	//$insert_arr['voucher_t_start_date'] = time();//默认代金券模板的有效期为当前时间
				
			$insert_arr['voucher_t_start_date'] = strtotime($_POST['txt_template_startdate']);

	        	if ($_POST['txt_template_enddate']){

	        		$enddate = strtotime($_POST['txt_template_enddate']);

	        		if (!$isOwnShop && $enddate > $quotainfo['quota_endtime']){

	        			$enddate = $quotainfo['quota_endtime'];

	        		}

	        		$insert_arr['voucher_t_end_date'] = $enddate;

	        	}else {//如果没有添加有效期则默认为套餐的结束时间

                    if ($isOwnShop)

                        $insert_arr['voucher_t_end_date'] = time() + 2592000; // 自营店 默认30天到期

                    else

                        $insert_arr['voucher_t_end_date'] = $quotainfo['quota_endtime'];

	        	}

	        	$insert_arr['voucher_t_price'] = $price;
				
                        $insert_arr['voucher_t_price_max'] = $price_max;

                        $insert_arr['is_random'] = intval($_POST['is_random'])==1?intval($_POST['is_random']):0;

                        $insert_arr['is_mod_5'] = intval($_POST['is_mod_5'])==1?intval($_POST['is_mod_5']):0;

	        	$insert_arr['voucher_t_limit'] = $limit;

	        	$insert_arr['voucher_t_store_id'] = $_SESSION['store_id'];

	        	$insert_arr['voucher_t_storename'] = $_SESSION['store_name'];

	        	$insert_arr['voucher_t_sc_id'] = intval($_POST['sc_id']);
                        
                        /*新增加voucher_t_channel，voucher_t_kind start*/
                        
                        $insert_arr['voucher_t_channel'] = intval($_POST['voucher_t_channel']);
                        
                        $insert_arr['voucher_t_kind'] = intval($_POST['voucher_t_kind']);
                        
                        /*stop*/

	        	$insert_arr['voucher_t_creator_id'] = $_SESSION['member_id'];

	        	$insert_arr['voucher_t_state'] = $this->templatestate_arr['usable'][0];

	        	$insert_arr['voucher_t_total'] = intval($_POST['txt_template_total'])>0?intval($_POST['txt_template_total']):0;

	        	$insert_arr['voucher_t_giveout'] = 0;

	        	$insert_arr['voucher_t_used'] = 0;

	        	$insert_arr['voucher_t_add_date'] = time();

	        	$insert_arr['voucher_t_quotaid'] = $quotainfo['quota_id'] ? $quotainfo['quota_id'] : 0;

	        	$insert_arr['voucher_t_points'] = $chooseprice['voucher_defaultpoints']<=10?$price * 10:$chooseprice['voucher_defaultpoints'];

	        	$insert_arr['voucher_t_eachlimit'] = intval($_POST['eachlimit'])>0?intval($_POST['eachlimit']):0;

	        	//自定义图片

		        if (!empty($_FILES['customimg']['name'])){

		        	$upload = new UploadFile();

		        	$upload->set('default_dir',	ATTACH_VOUCHER.DS.$_SESSION['store_id']);

		        	$upload->set('thumb_width','160');

					$upload->set('thumb_height','160');

					$upload->set('thumb_ext','_small');

					$result = $upload->upfile('customimg');

					if ($result){

                                            $insert_arr['voucher_t_customimg'] =  $upload->file_name;

					}

				}
				
	            $rs = $model->table('voucher_template')->insert($insert_arr);

	            if($rs){

	                showDialog(Language::get('nc_common_save_succ'),'index.php?act=store_voucher&op=templatelist','succ');

	            }else{
			echo "<script>console.log('".json_encode($insert_arr)."')</script>";exit;

	                showDialog(Language::get('nc_common_save_fail'),'index.php?act=store_voucher&op=templatelist','error');

	            }

//	        ｝

        }else{

            //店铺分类

//           $store_class = rkcache('store_class', true);
//
//            Tpl::output('store_class', $store_class);

            //查询店铺详情

            $store_info = Model('store')->getStoreInfoByID($_SESSION['store_id']);

            TPL::output('store_info',$store_info);



	        TPL::output('type','add');

	        TPL::output('quotainfo',$quotainfo);

	        TPL::output('pricelist',$pricelist);

	        $this->profile_menu('template','templateadd');

	        Tpl::showpage('store_voucher_template.add');

        }

    }

	/*

	 * 代金券模版编辑

	 */

    public function templateeditOp(){

    	$t_id = intval($_GET['tid']);

    	if ($t_id <= 0){

    		$t_id = intval($_POST['tid']);

    	}

    	if ($t_id <= 0){

    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');

    	}

        $model = Model('voucher');

        //查询模板信息

        $param = array();

        $param['voucher_t_id'] = $t_id;

        $param['voucher_t_store_id'] = $_SESSION['store_id'];

        $param['voucher_t_state'] = $this->templatestate_arr['usable'][0];

        $param['voucher_t_giveout'] = array('elt','0');

        $param['voucher_t_end_date'] = array('gt',time());

        $t_info = $model->table('voucher_template')->where($param)->find();

        if (empty($t_info)){

        	showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');

        }



        if ($isOwnShop = checkPlatformStore()) {

            Tpl::output('isOwnShop', true);

        } else {

            //查询套餐信息

            $quotainfo = $model->table('voucher_quota')->where(array('quota_id'=>$t_info['voucher_t_quotaid'],'quota_storeid'=>$_SESSION['store_id']))->find();

            if(empty($quotainfo)){

                showMessage(Language::get('voucher_template_quotanull'),'index.php?act=store_voucher&op=quotaadd','html','error');

            }

        }



        //查询面额列表

        $pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
        
        //查询分类
        
        $store_class = $model->table('goods_class')->where("gc_parent_id=0")->field('gc_id,gc_name')->select();
          
        Tpl::output('store_class', $store_class);


        if(empty($pricelist)){

        	//showMessage(Language::get('voucher_template_pricelisterror'),'index.php?act=store_voucher&op=templatelist','html','error');

        }

        if(chksubmit()){

	        //验证提交的内容面额不能大于限额

	        $obj_validate = new Validate();

	        $obj_validate->validateparam = array(

	            array("input"=>$_POST['txt_template_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('voucher_template_title_error')),

	            array("input"=>$_POST['txt_template_total'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_total_error')),

	            array("input"=>$_POST['select_template_price'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_price_error')),

	            array("input"=>$_POST['txt_template_limit'], "require"=>"true","validator"=>"Double","message"=>Language::get('voucher_template_limit_error')),

	            array("input"=>$_POST['txt_template_describe'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('voucher_template_describe_error')),

	        );

	        $error = $obj_validate->validate();

	        //金额验证

	        $price = intval($_POST['select_template_price'])>0?intval($_POST['select_template_price']):0;
			
			$price_max = intval($_POST['template_price_max'])>0?intval($_POST['template_price_max']):0;

	        foreach($pricelist as $k=>$v){

        		if($v['voucher_price'] == $price){

        			$chooseprice = $v;//取得当前选择的面额记录

        		}

        	}

        	if(empty($chooseprice)){

        		//$error.=Language::get('voucher_template_pricelisterror');

        	}

	        $limit = floatval($_POST['txt_template_limit'])>0?floatval($_POST['txt_template_limit']):0;

//	        if($price>=$limit && $limit!=0) $error.=Language::get('voucher_template_price_error');
//
//	        if ($error){
//
//	            showDialog($error,'reload','error');
//
//	        }else {

	        	$update_arr = array();

	        	$update_arr['voucher_t_title'] = trim($_POST['txt_template_title']);

	        	$update_arr['voucher_t_desc'] = trim($_POST['txt_template_describe']);
				
				$update_arr['voucher_t_start_date'] = strtotime($_POST['txt_template_startdate']);

	        	if ($_POST['txt_template_enddate']){

	        		$enddate = strtotime($_POST['txt_template_enddate']);

	        		if (!$isOwnShop && $enddate > $quotainfo['quota_endtime']){

	        			$enddate = $quotainfo['quota_endtime'];

	        		}

	        		$update_arr['voucher_t_end_date'] = $enddate;

	        	}else {//如果没有添加有效期则默认为套餐的结束时间

                    if ($isOwnShop)

                        $update_arr['voucher_t_end_date'] = time() + 2592000; // 自营店 默认30天到期

                    else

                        $update_arr['voucher_t_end_date'] = $quotainfo['quota_endtime'];

	        	}

	        	$update_arr['voucher_t_price'] = $price;
				
                        $update_arr['voucher_t_price_max'] = $price_max;

                        $update_arr['is_random'] = intval($_POST['is_random'])==1?intval($_POST['is_random']):0;

                        $update_arr['is_mod_5'] = intval($_POST['is_mod_5'])==1?intval($_POST['is_mod_5']):0;

	        	$update_arr['voucher_t_limit'] = $limit;

	        	$update_arr['voucher_t_sc_id'] = intval($_POST['sc_id']);

	        	$update_arr['voucher_t_state'] = intval($_POST['tstate']) == $this->templatestate_arr['usable'][0]?$this->templatestate_arr['usable'][0]:$this->templatestate_arr['disabled'][0];

	        	$update_arr['voucher_t_total'] = intval($_POST['txt_template_total'])>0?intval($_POST['txt_template_total']):0;

	        	$update_arr['voucher_t_points'] = $chooseprice['voucher_defaultpoints']<=10?$price * 10:$chooseprice['voucher_defaultpoints'];

	        	$update_arr['voucher_t_eachlimit'] = intval($_POST['eachlimit'])>0?intval($_POST['eachlimit']):0;

	        	//自定义图片

		        if (!empty($_FILES['customimg']['name'])){

		        	$upload = new UploadFile();

		        	$upload->set('default_dir',	ATTACH_VOUCHER.DS.$_SESSION['store_id']);

		        	$upload->set('thumb_width','160');

					$upload->set('thumb_height','160');

					$upload->set('thumb_ext','_small');

					$result = $upload->upfile('customimg');

					if ($result){

						//删除原图

						if (!empty($t_info['voucher_t_customimg'])){//如果模板存在，则删除原模板图片

							@unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']);

							@unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $t_info['voucher_t_customimg']));

						}

						$update_arr['voucher_t_customimg'] =  $upload->file_name;

					}

				}

	            $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->update($update_arr);

	            if($rs){

	                showDialog(Language::get('nc_common_op_succ'),'index.php?act=store_voucher&op=templatelist','succ');

	            }else{

	                showDialog(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=templatelist','error');

	            }

//	        }

        }else{

	        if (!$t_info['voucher_t_customimg'] || !file_exists(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg'])){

	        	$t_info['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);

	        }else{

	        	$t_info['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $t_info['voucher_t_customimg']);

	        }
                

	        TPL::output('type','edit');

	        TPL::output('t_info',$t_info);



	        //店铺分类

//	        $store_class = rkcache('store_class', true);
//
//	        Tpl::output('store_class', $store_class);
                
               

	        //查询店铺详情

	        $store_info = Model('store')->getStoreInfoByID($_SESSION['store_id']);

	        TPL::output('store_info',$store_info);



	        TPL::output('quotainfo',$quotainfo);

	        TPL::output('pricelist',$pricelist);

	        $this->profile_menu('templateedit','templateedit');

	        Tpl::showpage('store_voucher_template.add');

        }

    }

    /**

     * 删除代金券

     */

    public function templatedelOp(){

    	$t_id = intval($_GET['tid']);

    	if ($t_id <= 0){

    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');

    	}

        $model = Model();

        //查询模板信息

        $param = array();

        $param['voucher_t_id'] = $t_id;

        $param['voucher_t_store_id'] = $_SESSION['store_id'];

        $param['voucher_t_giveout'] = array('elt','0');//会员没领取过代金券才可删除

        $t_info = $model->table('voucher_template')->where($param)->find();

    	if (empty($t_info)){

    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');

    	}

        $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->delete();

        if ($rs){

        	//删除自定义的图片

        	if (trim($t_info['voucher_t_customimg'])){

        		@unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']);

        		@unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $t_info['voucher_t_customimg']));

        	}

        	showDialog(Language::get('nc_common_del_succ'),'reload','succ');

        }else {

        	showDialog(Language::get('nc_common_del_fail'));

        }

    }

    /**

     * 查看代金券详细

     */

    public function templateinfoOp(){

    	$t_id = intval($_GET['tid']);

    	if ($t_id <= 0){

    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');

    	}

        $model = Model();

        //查询模板信息

        $param = array();

        $param['voucher_t_id'] = $t_id;

        $param['voucher_t_store_id'] = $_SESSION['store_id'];

        $t_info = $model->table('voucher_template')->where($param)->find();

        TPL::output('t_info',$t_info);

        $this->profile_menu('templateinfo','templateinfo');

        Tpl::showpage('store_voucher_template.info');

    }

	/*

	 * 把代金券模版设为失效

	 */

    private function check_voucher_template_expire($voucher_template_id=''){

        $where_array = array();

        if(empty($voucher_template_id)) {

            $where_array['voucher_t_end_date'] = array('lt',time());

        } else {

            $where_array['voucher_t_id'] = $voucher_template_id;

        }

        $where_array['voucher_t_state'] = $this->templatestate_arr['usable'][0];

        $model = Model();

        $model->table('voucher_template')->where($where_array)->update(array('voucher_t_state'=>$this->templatestate_arr['disabled'][0]));

    }

	/**

	 * 用户中心右边，小导航

	 *

	 * @param string	$menu_type	导航类型

	 * @param string 	$menu_key	当前导航的menu_key

	 * @return

	 */

	private function profile_menu($menu_type,$menu_key='') {

		Language::read('member_layout');

		$menu_array	= array();

		switch ($menu_type) {

			case 'voucher':

				$menu_array = array(

				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),

				);

				break;

			case 'quota_add':

				$menu_array = array(

				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),

				4=>array('menu_key'=>'quotaadd','menu_name'=>Language::get('voucher_applyadd'),	'menu_url'=>'index.php?act=store_voucher&op=quotaadd')

				);

				break;

			case 'template':

				$menu_array = array(

				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),

				2=>array('menu_key'=>'templateadd','menu_name'=>Language::get('voucher_templateadd'),	'menu_url'=>'index.php?act=store_voucher&op=templateadd'),

				);

				break;

			case 'templateedit':

				$menu_array = array(

				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),

				2=>array('menu_key'=>'templateedit','menu_name'=>Language::get('voucher_templateedit'),	'menu_url'=>''),

				);

				break;

			case 'templateinfo':

				$menu_array = array(

				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),

				2=>array('menu_key'=>'templateinfo','menu_name'=>Language::get('voucher_templateinfo'), 'menu_url'=>''),

				);

				break;

		}

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

	}

}
function user_template($member,$rmb){
	return urldecode(json_encode(array(
	   "touser"=>$member,//oc9PrwxONc0HkCqZ71_vRjVTq3us：吕伟超 //oc9Prw6aP8FSSQRJCwIMzPeBf7P0：金//oc9Prw6kAESVp3y2TxHsBNV8eW6Y：何//oc9Prw570stJBA7C2BSVs3HkArFk：王总
	   "template_id"=>"CR9NEfW5SfasiXuXTTd7miYz9ARDkB9dCGcV8vy9cGY",
	   "url"=>"http://localhost/yangyangdi/wap/tmpl/member/voucher_list.html",            
	   "data"=>array(
			   "first"=>array('value'=>urlencode("尊敬的客户:"),'color'=>"#743A3A"),
			   "name"=>array('value'=>urlencode("面值".$rmb."元的红包快到期了,"),'color'=>"#0000ff"),
			   "expDate"=>array('value'=>urlencode("2015年10月31日,请赶快使用"),'color'=>"#743A3A"),
			   "remark"=>array('value'=>urlencode("点击此信息可以查看红包详情"),'color'=>"#ff0000"),
	   )
	)));
}
function get_access_token(){
	
	$appid='wx21df8a8bd8408cc6';
	$appsecret='0edac092b8af1c00f24c10d65a20ecf0';
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
	$res = file_get_contents($url);
	$result = json_decode($res,true);
	$access_token = $result['access_token'];
	return $access_token;
}

function send_template_message($data,$access_token){
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
	$res = http_request($url,$data);
	$res = json_decode($res,true);
	print_r($res);
}

function http_request($url,$data = NULL){
	
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	
	if(!empty($data)){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}