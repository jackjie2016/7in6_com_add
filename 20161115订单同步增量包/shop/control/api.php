<?php

/**

 * 接口入口

 *

 *

 **by 网店技术交流中心 www.7in6.com*/





defined('InShopNC') or exit('Access Invalid!');

class apiControl extends BaseHomeControl{
 
    
    public function shippingOp(){ 
        
        $secret = $_POST['secret'];
        $order_sn = $_POST['order_sn'];
        $order_state = $_POST['order_state'];
        $shipping_code = $_POST['shipping_code'];
        $shipping_time = $_POST['shipping_times'];
        $shipping_express_id = $_POST['shipping_expres'];
        /*$secret = 'db27e26d41792a1f6fd6b68b2fba0cab';
        $order_sn = '8000000000158501';
        $order_state = '30';
        $shipping_code = '963852741685';
        $shipping_time = '1479209474';
        $shipping_express_id = '申通快递';*/
        $str = 'meinong88';
        $secret_new = $str.$order_sn.$order_state.$shipping_express_id.$shipping_code.$shipping_time;
        $secret_new = md5($secret_new);
        
        //验证两次密钥是否成功
        if($secret==$secret_new){
            //修改order
            $order = Model('order');
            $where = array();
            $where['order_sn'] = $order_sn;
            $update_arr = array();
            $update_arr['order_state'] = $order_state;
            $update_arr['shipping_code'] = $shipping_code;
            //查询order_id
            $order_info  = $order->table('order')->where($where)->select();
            $order_id = $order_info[0]['order_id'];
            $res = $order->table('order')->where($where)->update($update_arr);
            if($res){
                //查询shipping
                $where =array();
                $express_id  = $order->table('express')->where(array('e_name'=>$shipping_express_id))->select();
                if($express_id){
                     $shipping_express_id = $express_id[0]['id'];
                }
                //修改order_common
                $where = array();
                $where['order_id'] = $order_id;
                $update_arr = array();
                $update_arr['shipping_time'] = $shipping_time;
                $update_arr['shipping_express_id'] = $shipping_express_id;
                $res = $order->table('order_common')->where($where)->update($update_arr);
                if($res){
                    echo '0';
                }else{
                    echo '1';
                }
            }else{
                echo '1';
            }
        }else{
            echo '1';
        }
        
        
    }  
}

