<?php

/**

 * 会员中心——账户概览

 *

 *

 *

 *** by 网店技术交流中心 www.7in6.com*/





defined('InShopNC') or exit('Access Invalid!');



class memberControl extends BaseMemberControl{



    /**

     * 我的商城

     */

    public function homeOp() {

        Tpl::showpage('member_home');

    }


    /*此方法没用*/
    public function ajax_load_member_infoOp() {

        $member_info = $this->member_info;

        $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);

        //代金券数量

        $member_info['voucher_count'] = Model('voucher')->getCurrentAvailableVoucherCount($_SESSION['member_id']);
        

        Tpl::output('home_member_info',$member_info);

        Tpl::showpage('member_home.member_info','null_layout');

    }



    public function ajax_load_order_infoOp() {

        $model_order = Model('order');



        //交易提醒 - 显示数量

        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'NewCount');

        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'SendCount');

        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'EvalCount');

        Tpl::output('home_member_info',$member_info);



        //交易提醒 - 显示订单列表

        $condition = array();

        $condition['buyer_id'] = $_SESSION['member_id'];

        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS));

        $order_list = $model_order->getNormalOrderList($condition,'','*','order_id desc',3,array('order_goods'));

        

        foreach ($order_list as $order_id => $order) {

            //显示物流跟踪

            $order_list[$order_id]['if_deliver'] = $model_order->getOrderOperateState('deliver',$order);

            //显示评价

            $order_list[$order_id]['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order);

            //显示支付

            $order_list[$order_id]['if_payment'] = $model_order->getOrderOperateState('payment',$order);

            //显示收货

            $order_list[$order_id]['if_receive'] = $model_order->getOrderOperateState('receive',$order);

        }

        

        Tpl::output('order_list',$order_list);

        

        //取出购物车信息

        $model_cart = Model('cart');

        $cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']),3);

        Tpl::output('cart_list',$cart_list);

        Tpl::showpage('member_home.order_info','null_layout');

    }



    public function ajax_load_goods_infoOp() {

        //商品收藏

        $favorites_model = Model('favorites');

        $favorites_list = $favorites_model->getGoodsFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 7);

        if (!empty($favorites_list) && is_array($favorites_list)){

            $favorites_id = array();//收藏的商品编号

            foreach ($favorites_list as $key=>$favorites){

                $fav_id = $favorites['fav_id'];

                $favorites_id[] = $favorites['fav_id'];

                $favorites_key[$fav_id] = $key;

            }

            $goods_model = Model('goods');

            $field = 'goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.goods_price,goods.evaluation_count,goods.goods_salenum,goods.goods_collect,store.store_name,store.member_id,store.member_name,store.store_qq,store.store_ww,store.store_domain';

            $goods_list = $goods_model->getGoodsStoreList(array('goods_id' => array('in', $favorites_id)), $field);

            $store_array = array();//店铺编号

            if (!empty($goods_list) && is_array($goods_list)){

                $store_goods_list = array();//店铺为分组的商品

                foreach ($goods_list as $key=>$fav){

                    $fav_id = $fav['goods_id'];

                    $fav['goods_member_id'] = $fav['member_id'];

                    $key = $favorites_key[$fav_id];

                    $favorites_list[$key]['goods'] = $fav;

                }

            }

        }

        Tpl::output('favorites_list',$favorites_list);

        

        //店铺收藏

        $favorites_list = $favorites_model->getStoreFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 6);

        if (!empty($favorites_list) && is_array($favorites_list)){

            $favorites_id = array();//收藏的店铺编号

            foreach ($favorites_list as $key=>$favorites){

                $fav_id = $favorites['fav_id'];

                $favorites_id[] = $favorites['fav_id'];

                $favorites_key[$fav_id] = $key;

            }

            $store_model = Model('store');

            $store_list = $store_model->getStoreList(array('store_id'=>array('in', $favorites_id)));

            if (!empty($store_list) && is_array($store_list)){

                foreach ($store_list as $key=>$fav){

                    $fav_id = $fav['store_id'];

                    $key = $favorites_key[$fav_id];

                    $favorites_list[$key]['store'] = $fav;

                }

            }

        }

        Tpl::output('favorites_store_list',$favorites_list);

        $goods_count_new = array();

        if (!empty($favorites_id)) {

            foreach ($favorites_id as $v){

                $count = Model('goods')->getGoodsCommonOnlineCount(array('store_id' => $v));

                $goods_count_new[$v] = $count;

            }

        }

        Tpl::output('goods_count',$goods_count_new);

        Tpl::showpage('member_home.goods_info','null_layout');

    }



    public function ajax_load_sns_infoOp() {

        //我的足迹

        $goods_list = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);

        $viewed_goods = array();

        if(is_array($goods_list) && !empty($goods_list)) {

            foreach ($goods_list as $key => $val) {

                $goods_id = $val['goods_id'];

                $val['url'] = urlShop('goods', 'index', array('goods_id' => $goods_id));

                $val['goods_image'] = thumb($val, 240);

                $viewed_goods[$goods_id] = $val;

            }

        }

        Tpl::output('viewed_goods',$viewed_goods);

        

        //我的圈子

        $model = Model();

        $circlemember_array = $model->table('circle_member')->where(array('member_id'=>$_SESSION['member_id']))->select();

        if(!empty($circlemember_array)) {

            $circlemember_array = array_under_reset($circlemember_array, 'circle_id');

            $circleid_array = array_keys($circlemember_array);

            $circle_list = $model->table('circle')->where(array('circle_id'=>array('in', $circleid_array)))->limit(6)->select();

            Tpl::output('circle_list', $circle_list);

        }

        

        //好友动态

        $model_fd = Model('sns_friend');

        $fields = 'member.member_id,member.member_name,member.member_avatar';

        $follow_list = $model_fd->listFriend(array('limit'=>15,'friend_frommid'=>"{$_SESSION['member_id']}"),$fields,'','detail');

        $member_ids = array();$follow_list_new = array();

        if (is_array($follow_list)) {

            foreach ($follow_list as $v) {

                $follow_list_new[$v['member_id']] = $v;

                $member_ids[] = $v['member_id'];

            }

        }

        $tracelog_model = Model('sns_tracelog');

        //条件

        $condition = array();

        $condition['trace_memberid'] = array('in',$member_ids);

        $condition['trace_privacy'] = 0;

        $condition['trace_state'] = 0;

        $tracelist = Model()->table('sns_tracelog')->where($condition)->field('count(*) as _count,trace_memberid')->group('trace_memberid')->limit(5)->select();

        $tracelist_new = array();$follow_list = array();

        if (!empty($tracelist)){

            foreach ($tracelist as $k=>$v){

                $tracelist_new[$v['trace_memberid']] = $v['_count'];

                $follow_list[] = $follow_list_new[$v['trace_memberid']];

            }

        }

        Tpl::output('tracelist',$tracelist_new);

        Tpl::output('follow_list',$follow_list);

        Tpl::showpage('member_home.sns_info','null_layout');

    }
    
    /*edit by peiyu start 新增签到功能*/
    
    public function do_signOp() {
        
        $call_back = array();
        
        //再次验证今天是否签到
        
        $time = strtotime(date('Y-m-d',time()));

        $time_end = $time + 86400;

        $where['sign_time'] = array(array('gt',$time),array('lt',$time_end),'and');
        
        $where['member_id'] = $_SESSION['member_id'];

        $tod_is_sign = Model('member')->table('sign')->where($where)->count();
        
        if($tod_is_sign>0){
            
            $call_back['tod_is_sign']=1;

            echo json_encode($call_back);exit;
            
        }
        
       // 生成先到签到数据
        
        $insert_arr = array();

        $insert_arr['member_id'] = $_SESSION['member_id'];

        $insert_arr['sign_time'] = time();
        
        $result = Model()->table('sign')->insert($insert_arr);
        
        //签到成功的状态
        
        if($result){
            
            //查询此次连续签到多少天
            
            $where = array();
            
            $where['member_id'] =  $_SESSION['member_id'];
            
            $member_info =  Model()->table('member')->where($where)->select();           
            
            //查询昨天是否签到
            
            $time = strtotime(date('Y-m-d',time()));

            $time_start = $time - 86400;

            $where['sign_time'] = array(array('lt',$time),array('gt',$time_start),'and');

            $yed_is_sign = Model('member')->table('sign')->where($where)->count();
            
            //重新计算连续签到时间
            if($yed_is_sign==1){
   
                $sign_num = $member_info[0]['sign_num']+1;
                
            }else{
                
                $sign_num = 1;
                
            }   
            
            //判断相应获得的印币数
            
            if($sign_num>0 and $sign_num <4){
                
                $jifen = C('sign_1'); 
                
            } elseif ( $sign_num>3 and $sign_num <7) {
                
                $jifen = C('sign_2');
                
            }else{
                
                $jifen = C('sign_3');
                
            }
            
            //修改用户表连续多少天签到
            
            $update=array();

            $update['sign_num'] = $sign_num;  //更新连续发放的数量
            
            $update['member_points']= $member_info[0]['member_points']+$jifen;  //更新印币的数量
            
            $where = array();
            
            $where['member_id'] = $_SESSION['member_id'];

            $result_member = Model()->table('member')->where($where)->update($update);
            
            if($result_member){
                
                //写入日志
                $insert_arr = array();

                $insert_arr['pl_memberid'] = $_SESSION['member_id'];
                
                $insert_arr['pl_membername'] = $member_info[0]['member_name'];
                
                $insert_arr['pl_points'] = $jifen;
                
                $insert_arr['pl_addtime'] = time();
                
                $insert_arr['pl_desc'] = "签到获得".$jifen.'印币';

                $insert_arr['pl_stage'] = 'sign';

                $result_log = Model()->table('points_log')->insert($insert_arr);
                
                if(!$result_log){
                    
                    $call_back['error']=1;

                    echo json_encode($call_back);
                
                    exit;
                    
                }else{
                    
                    //返回值
                    
                    $call_back['succ']=1;
                    
                    $call_back['jifen'] = $member_info[0]['member_points']+$jifen;
                    
                    $call_back['sign_num'] =  $sign_num;

                    echo json_encode($call_back);
                
                    exit;
                    
                }
                
            }else{
                
                $call_back['error']=1;

                echo json_encode($call_back);
                
                exit;
               
            }
           
        }else{
            
            $call_back['error']=1;

            echo json_encode($call_back);
                
            exit;
            
        }
        
    }
    
    
    
    /*edit by peiyu stop*/



    /**

     * 设置常用菜单

     */

    public function common_operationsOp() {

        $type = $_GET['type'];

        $value = $_GET['value'];

        if (!in_array($type, array('add', 'del')) || empty($value)) {

            echo false;exit;

        }

        $quicklink = $this->quicklink;

        if ($type == 'add') {

            if (!empty($quicklink)) {

                array_push($quicklink, $value);

            } else {

                $quicklink[] = $value;

            }

        } else {

            $quicklink = array_diff($quicklink, array($value));

        }

        $quicklink = array_unique($quicklink);

        $quicklink = implode(',', $quicklink);

        $result = Model('member')->editMember(array('member_id' => $_SESSION['member_id']), array('member_quicklink' => $quicklink));

        if ($result) {

            echo true;exit;

        } else {

            echo false;exit;

        }

    }

}

