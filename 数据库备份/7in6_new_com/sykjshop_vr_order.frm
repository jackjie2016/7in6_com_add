�
    0  iV         ! 	      !       i  ��  ?        //                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
       �    �PRIMARY�                                                                                                                                                                                                                                                                                                                                         ��                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   �    �    �    �                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      �                                                                                                                                                                                                                                                                                                                                          InnoDB      /                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                i                                           虚拟订单表                                                                                                                                                                                                   & ��  V�        P   i  =)                                          	order_id  	order_sn  	store_id  store_name  	buyer_id 	 buyer_name 
 buyer_phone  	add_time  payment_code  payment_time  	trade_no  close_time  close_reason  finnshed_time  order_amount  refund_amount  rcb_amount  
pd_amount  order_state X)                                          refund_state  
buyer_msg  delete_state  	goods_id  goods_name 	 goods_price 
 
goods_num  goods_image  commis_rate  gc_id  
vr_indate  vr_send_times  vr_invalid_refund  order_promotion_type  promotions_id  order_from  evaluation_state  evaluation_time  
use_state 	      ! 	   B@   ! 	   @   ! D�    @   ! 	 �  @   ! 	D� �   @   ! 
!! E  @   ! 	

 g @   !  k      �! 

 � �   ! 	Fi �  �   ! 

 � �   ! B� �  �   !  � �   !  �    �!  � �   �!  �    �! 
 �    �!  �     !J  � 
�   !< 
E��  �   !  p     !3 	 q @   ! D� u  @   ! 	  B   �! 

      ! ,,  �   !  A     ! 		 C K�   	! 
 F �   !	  J     !  K     !  L 
    !- 		 M K�   	!.  P     !  Q     !*  R     ! 
 V �   !E �order_id�order_sn�store_id�store_name�buyer_id�buyer_name�buyer_phone�add_time�payment_code�payment_time�trade_no�close_time�close_reason�finnshed_time�order_amount�refund_amount�rcb_amount�pd_amount�order_state�refund_state�buyer_msg�delete_state�goods_id�goods_name�goods_price�goods_num�goods_image�commis_rate�gc_id�vr_indate�vr_send_times�vr_invalid_refund�order_promotion_type�promotions_id�order_from�evaluation_state�evaluation_time�use_state� 虚拟订单索引id订单编号卖家店铺id卖家店铺名称买家id买家登录名买家手机订单生成时间支付方式名称代码支付(付款)时间第三方平台交易号关闭时间关闭原因完成时间订单总价格(支付金额)退款金额充值卡支付金额预存款支付金额订单状态：0(已取消)10(默认):未付款;20:已付款;40:已完成;退款状态:0是无退款,1是部分退款,2是全部退款买家留言删除状态0未删除1放入回收站2彻底删除商品id商品名称商品价格商品数量商品图片佣金比例商品最底级分类ID有效期兑换码发送次数允许过期退款1是0否订单参加的促销类型 0无促销1团购促销ID，与order_promotion_type配合使用1WEB2mobile评价状态0默认1已评价2禁止评价评价时间使用状态0默认，未使用1已使用，有一个被使用即为1