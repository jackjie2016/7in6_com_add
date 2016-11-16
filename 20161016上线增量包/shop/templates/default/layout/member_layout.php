<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<?php include template('layout/common_layout'); ?>
<!-- <?php include template('layout/cur_local'); ?> -->
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/ToolTip.js"></script>
<script>
//sidebar-menu
    $(document).ready(function () {
        $.each($(".side-menu > a"), function () {
            $(this).click(function () {
                var ulNode = $(this).next("ul");
                if (ulNode.css('display') == 'block') {
                    $.cookie(COOKIE_PRE + 'Mmenu_' + $(this).attr('key'), 1);
                } else {
                    $.cookie(COOKIE_PRE + 'Mmenu_' + $(this).attr('key'), null);
                }
                ulNode.slideToggle();
                if ($(this).hasClass('shrink')) {
                    $(this).removeClass('shrink');
                } else {
                    $(this).addClass('shrink');
                }
            });
        });
        $.each($(".side-menu-quick > a"), function () {
            $(this).click(function () {
                var ulNode = $(this).next("ul");
                ulNode.slideToggle();
                if ($(this).hasClass('shrink')) {
                    $(this).removeClass('shrink');
                } else {
                    $(this).addClass('shrink');
                }
            });
        });
    });
    $(function () {
        //展开关闭常用菜单设置
        $('.set-btn').bind("click",
                function () {
                    $(".set-container-arrow").show("fast");
                    $(".set-container").show("fast");
                });
        $('[nctype="closeCommonOperations"]').bind("click",
                function () {
                    $(".set-container-arrow").hide("fast");
                    $(".set-container").hide("fast");
                });

        $('dl[nctype="checkcCommonOperations"]').find('input').click(function () {
            var _this = $(this);
            var _dd = _this.parents('dd:first');
            var _type = _this.is(':checked') ? 'add' : 'del';
            var _value = _this.attr('name');
            var _operations = $('[nctype="commonOperations"]');

            // 最多添加5个
            if (_operations.find('li').length >= 5 && _type == 'add') {
                showError('最多只能添加5个常用选项。');
                return false;
            }
            $.getJSON('<?php echo urlShop('member', 'common_operations') ?>', {type: _type, value: _value}, function (data) {
                if (data) {
                    if (_type == 'add') {
                        _dd.addClass('checked');
                        if (_operations.find('li').length == 0) {
                            _operations.fadeIn('slow');
                        }
                        _operations.find('ul').append('<li style="display : none;" nctype="' + _value + '"><a href="' + _this.attr('data-value') + '">' + _this.attr('data-name') + '</a></li>');
                        _operations.find('li[style]').fadeIn('slow');
                    } else {
                        _dd.removeClass('checked');
                        _operations.find('li[nctype="' + _value + '"]').fadeOut('slow', function () {
                            $(this).remove();
                            if (_operations.find('li').length == 0) {
                                _operations.fadeOut('slow');
                            }
                        });
                    }
                }
            });
        });
    });

    /* 新增个人中心nav判断i*/
    $(function () {
        $('.d_onenav_main ul.d_my_navm').find('li').each(
                function () {
                    $(this).hover(function () {
                        if ($(this).children('a').hasClass('d_one_curr')) {
                            $(this).find('.d_sub-menu').css("display", "none");
                        } else {
                            $(this).find('.d_sub-menu').css("display", "block");
                        }
                    }, function () {
                        $(this).find('.d_sub-menu').css("display", "none");
                    });
                    if ($(this).children('a').hasClass('d_one_curr')) {
                        $(this).find('i').css("display", "none");
                    }
                    ;
                    $(this).click(function () {
                        $(this).siblings().children('a').removeClass('d_one_curr');
                        $(this).children('a').addClass('d_one_curr');
                        $(this).find('i').css("display", "none");
                        $(this).siblings().find('i').css("display", "inline-block");
                    })
                }
        )
    })
</script>
<div class="ncm-container d_ohide">
    <!--======= 个人中心头像等调整 15/6/24 begin==========-->
    <div class="d_osf_hd">
        <div class="d_osf_top">
            <!-- 个人中心头像等信息 begin -->
            <div class="d_osf_info">
                <div class="d_my_ph">
                    <a href="index.php?act=member_information&op=avatar" title="修改头像">
                        <img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']); ?>">
                        <div class="d_frame"></div>
                    </a>
                </div>
                <!-- 个人中心头像等信息 end other-information -->
                <dl class="d_osf_other">
                    <dt><a href="index.php?act=member_information&op=member" title="修改资料"><?php echo $output['member_info']['member_name']; ?></a></dt>
                    <dd>会员等级：
                        <div class="nc-grade-mini d-level-max d-level-max-<?php echo $output['member_info']['level'] ?>" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade', 'index'); ?>');"></div>
                    </dd>
                    <dd>上次登录：<?php echo date('Y年m月d日 H:i:s', $output['member_info']['member_old_login_time']); ?></dd>
                    <dd class="d_other_lg">登录绑定：
                        <?php if ($output['member_info']['member_qqopenid']) { ?>
                            <a class="d_qq_bding" href="javascript:;" title="登录绑定QQ账号"></a>
                        <?php } else { ?>
                            <a class="d_qq_icon" href="index.php?act=member_connect&op=qqbind" title="登录绑定QQ账号"></a> 
                        <?php } ?>
                        <?php if ($output['member_info']['wecha_id']) { ?>
                            <a class="d_wx_bding" href="javascript:;" title="登录绑定微信账号"></a>
                        <?php } else { ?>
                            <a class="d_wx_icon" href="index.php?act=member_connect&op=wxinbind" title="登录绑定微信账号"></a>
                        <?php } ?>

                        <?php if ($output['member_info']['member_sinaopenid']) { ?>
                            <a class="d_xl_bding" href="javascript:;" title="登录绑定微博账号"></a>
                        <?php } else { ?>
                            <a class="d_xl_icon" href="index.php?act=member_connect&op=sinabind" title="登录绑定微博账号"></a>
<?php } ?>
                    </dd>
                    <!-- <dd>账户安全：
                        <div class="SAM">
                          <a href="<?php echo urlShop('member_security', 'index'); ?>">
                    <?php if ($output['home_member_info']['security_level'] <= 1) { ?>
                                <div id="low" class="SAM-info"><span><em></em></span><strong>低</strong>
                    <?php } elseif ($output['home_member_info']['security_level'] == 2) { ?>
                                  <div id="normal" class="SAM-info"><span><em></em></span><strong>中</strong>
                    <?php } else { ?>
                                    <div id="high" class="SAM-info"><span><em></em></span><strong>高</strong>
                    <?php } ?>
                    <?php if ($output['home_member_info']['security_level'] < 3) { ?>
<?php } ?>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>
                      </dd> -->
                </dl>
            </div>
        </div>
        <div class="ncm-index-container d_fl">
            <div id="account" class="double">
                <div class="outline">
                    <div class="user-account">
                        <ul>
                            <li id="pre-deposit"><a href="index.php?act=predeposit&op=pd_log_list" title="查看我的余额">
                                    <h5><?php echo $lang['nc_predepositnum']; ?></h5>
                                    <span class="icon"></span> <span class="value">￥<em><?php echo $output['member_info']['available_predeposit']; ?></em></span></a> </li>
                            <li id="voucher"><a href="index.php?act=member_voucher&op=index" title="查看我的代金券">
                                    <h5>代金券</h5>
                                    <span class="icon"></span> <span class="value"><em><?php echo $output['member_info']['voucher_count'] ? $output['member_info']['voucher_count'] : 0; ?></em>张</span></a> </li>
                            <li id="points"><a href="index.php?act=member_points&op=index" title="查看我的印币">
                                    <h5><?php echo $lang['nc_pointsnum']; ?></h5>
                                    <span class="icon"></span> <span class="value"><em><?php echo $output['member_info']['member_points']; ?></em>币</span></a> </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="security" class="normal">
                <div class="outline">
                    <div class="SAM">
                        <h5>账户安全</h5>
                            <?php if ($output['member_info']['security_level'] <= 1) { ?>
                            <div id="low" class="SAM-info"><strong>低</strong><span><em></em></span>
                                <?php } elseif ($output['member_info']['security_level'] == 2) { ?>
                                <div id="normal" class="SAM-info"><strong>中</strong><span><em></em></span>
                                    <?php } else { ?>
                                    <div id="high" class="SAM-info"><strong>高</strong><span><em></em></span>
                                    <?php } ?>
                                    <?php if ($output['member_info']['security_level'] < 3) { ?>
                                        <a href="<?php echo urlShop('member_security', 'index'); ?>" title="安全设置">提升></a>
<?php } ?>
                                </div>
                                <div class="SAM-handle"><span><i class="mobile"></i>手机：
                                        <?php if ($output['member_info']['member_mobile_bind'] == 1) { ?>
                                            <em>已绑定</em>
                                        <?php } else { ?>
                                            <a href="<?php echo urlShop('member_security', 'auth', array('type' => 'modify_mobile')); ?>" title="绑定手机">未绑定</a>
                                        <?php } ?></span>
                                    <span><i class="mail"></i>邮箱：
                                        <?php if ($output['member_info']['member_email_bind'] == 1) { ?>
                                            <em>已绑定</em>
                                        <?php } else { ?>
                                            <a href="<?php echo urlShop('member_security', 'auth', array('type' => 'modify_email')); ?>" title="绑定邮箱">未绑定</a>
<?php } ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ===== 15/6/27 ===== -->
                <!--========= 新增个人中心导航 ============-->
                <div class="d_oneself_nav cl">
                    <div class="d_onenav_main">
                        <ul class="d_my_navm">
                            <li class="d_shop">
                                <a href="index.php?act=member&op=home" class="d_one_curr">我的商城<i></i></a>
                                <div class="d_sub-menu">
                                    <dl>
                                        <dt><a href="<?php echo $output['menu_list']['trade']['child']['member_order']['url']; ?>" style="color:#398EE8;">交易管理</a></dt>
                                        <dd><a href="<?php echo $output['menu_list']['trade']['child']['member_order']['url']; ?>">实物交易订单</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['trade']['child']['member_vr_order']['url']; ?>">虚拟兑码订单</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['trade']['child']['member_evaluate']['url']; ?>">评价/晒单</a></dd>
                                        <dd><a href="#">预约/到货通知</a></dd>
                                    </dl>
                                    <dl>
                                        <dt><a href="<?php echo $output['menu_list']['trade']['child']['member_favorites']['url']; ?>" style="color:#3AAC8A;">收藏关注</a></dt>
                                        <dd><a href="<?php echo $output['menu_list']['trade']['child']['member_favorites']['url']; ?>">收藏的商品</a></dd>
                                        <dd><a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_favorites&op=fslist">收藏的店铺</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['info']['child']['member_goodsbrowse']['url']; ?>">浏览足迹</a></dd>
                                    </dl>
                                    <dl>
                                        <dt><a href="<?php echo $output['menu_list']['serv']['child']['member_refund']['url']; ?>" style="color:#B68571;">服务售后</a></dt>
                                        <dd><a href="<?php echo $output['menu_list']['serv']['child']['member_refund']['url']; ?>">退款/退货</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['serv']['child']['member_complain']['url']; ?>">交易投诉</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['serv']['child']['member_consult']['url']; ?>">商品咨询</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['serv']['child']['member_mallconsult']['url']; ?>">平台客服</a></dd>
                                    </dl>
                                </div>
                            </li>
<!--                            <li>
                                <a href="index.php?act=member&op=home" class="d_set">我的物业<i></i></a>
                                <div class="d_sub-menu">
                                    <dl>
                                        <dd><a href="index.php?act=member_snshome&op=trace<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">家庭成员</a></dd>
                                        <dd><a href="index.php?act=sns_album<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">物业通知</a></dd>
                                        <dd><a href="index.php?act=pm_member_repair&op=repair">物业报修</a></dd>
                                        <dd><a href="index.php?act=member_snshome&op=storelist<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">物业缴费</a></dd>
                                    </dl>
                                </div>
                            </li>-->
                            <li class="d_set">
                                <a href="index.php?act=member_information&op=member">用户设置<i></i></a>
                                <div class="d_sub-menu">
                                    <dl>
                                        <dt><a href="<?php echo urlShop('member_security', 'index'); ?>" style="color:#3AAC8A;">安全设置</a></dt>
                                        <dd><a href="<?php echo urlShop('member_security', 'index'); ?>">修改登录密码</a></dd>
                                        <dd><a href="<?php echo urlShop('member_security', 'auth', array('type' => 'modify_mobile')); ?>">手机绑定</a></dd>
                                        <dd><a href="<?php echo urlShop('member_security', 'auth', array('type' => 'modify_email')); ?>">邮件绑定</a></dd>
                                        <dd><a href="<?php echo urlShop('member_security', 'index'); ?>">支付密码</a></dd>
                                    </dl>
                                    <dl>
                                        <dt><a href="<?php echo urlShop('member_security', 'index'); ?>" style="color:#EA746B;">个人资料</a></dt>
                                        <dd><a href="<?php echo $output['menu_list']['info']['child']['member_address']['url']; ?>">收货地址</a></dd>
                                        <dd><a href="index.php?act=member_information&op=avatar">修改头像</a></dd>
                                        <dd><a href="<?php echo urlShop('member_message', 'setting'); ?>">消息接受设置</a></dd>
                                    </dl>
                                    <dl>
                                        <dt><a href="<?php echo $output['menu_list']['trade']['child']['predeposit']['url']; ?>" style="color: #FF7F00;">账户财产</a></dt>
                                        <dd><a href="index.php?act=predeposit&op=recharge_add">余额充值</a></dd>
                                        <dd><a href="#">领取代金券</a></dd>
                                        <dd><a href="#">领取红包</a></dd>
                                    </dl>
                                    <dl>
                                        <dt><a href="<?php echo $output['menu_list']['info']['child']['member_connect']['url']; ?>" style="color:#398EE8;">账号绑定</a></dt>
                                        <dd><a href="<?php echo $output['menu_list']['info']['child']['member_connect']['url']; ?>">QQ绑定</a></dd>
                                        <dd><a href="index.php?act=member_connect&op=sinabind">微博绑定</a></dd>
                                        <dd><a href="index.php?act=member_connect&op=wxinbind">微信绑定</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['info']['child']['member_sharemanage']['url']; ?>">分享绑定</a></dd>
                                    </dl>
                                </div>
                            </li>
                          <!--  <li>
                                <a href="<?php echo $output['menu_list']['app']['child']['sns']['url']; ?>">个人主页<i></i></a>
                                <div class="d_sub-menu">
                                    <dl>
                                        <dd><a href="index.php?act=member_snshome&op=trace<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">新鲜事</a></dd>
                                        <dd><a href="index.php?act=sns_album<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">个人相册</a></dd>
                                        <dd><a href="index.php?act=member_snshome&op=shareglist<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">分享商品</a></dd>
                                        <dd><a href="index.php?act=member_snshome&op=storelist<?php if (!empty($output['master_id'])) {
    echo '&mid=' . $output['master_id'];
} ?>">分享店铺</a></dd>
                                    </dl>
                                </div>
                            </li>
                            <li>
                                <a href="#">其他应用<i></i></a>
                                <div class="d_sub-menu">
                                    <dl>
                                        <dd><a href="<?php echo $output['menu_list']['app']['child']['cms']['url']; ?>">我的CMS</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['app']['child']['circle']['url']; ?>">我的圈子</a></dd>
                                        <dd><a href="<?php echo $output['menu_list']['app']['child']['microshop']['url']; ?>">我的微商城</a></dd>
                                    </dl>
                                </div>
                            </li>-->
                        </ul>
                    </div>
                </div>
                <!--============================================-->
            </div>
            <!--======= 个人中心头像等调整 end==========-->



            <div class="left-layout">
                <!--================= 我的头像等调整 15/6/24 ===================-->
                <!-- <div class="ncm-l-top">
                  <h2><a href="index.php?act=member&op=home" title="我的商城">我的商城</a></h2>
                  <a href="javascript:void(0)" title="常用菜单设置" class="set-btn"></a>
                  <div class="set-container-arrow"></div>
                  <div class="set-container">
                    <div class="title">
                      <h3>常用菜单设置</h3>
                      <a href="javascript:void(0)" title="关闭" class="close-btn close-container" nctype="closeCommonOperations"></a></div>
                    <div class="tip">勾选您经常使用的菜单，最多可选5个。 </div>
                    <div class="menu-list">
<?php if (!empty($output['menu_list'])) { ?>
    <?php foreach ($output['menu_list'] as $value) { ?>
                              <dl class="collapsed" nctype="checkcCommonOperations">
                                <dt><?php echo $value['name']; ?></dt>
        <?php if (is_array($value['child'])) { ?>
            <?php foreach ($value['child'] as $key => $val) { ?>
                                        <dd <?php if ($val['selected']) { ?>class="checked"<?php } ?>>
                                          <label>
                                            <input name="<?php echo $key ?>" data-value="<?php echo $val['url']; ?>" data-name="<?php echo $val['name']; ?>" type="checkbox" class="checkbox" <?php if ($val['selected']) { ?>checked="checked"<?php } ?> />
                                <?php echo $val['name']; ?></label>
                                        </dd>
            <?php } ?>
        <?php } ?>
                              </dl>
                    <?php } ?>
                <?php } ?>
                    </div>
                    <div class="bottom">
                      <input type="submit" value="确定" class="setting" nctype="closeCommonOperations">
                    </div>
                  </div>
                </div>
                <div class="ncm-user-info">
                  <div class="avatar"><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']); ?>">
                    <div class="frame"></div>
                            <?php if (intval($output['message_num']) > 0) { ?>
                        <a href="index.php?act=member_message&op=message" class="new-message" title="新消息"><?php echo intval($output['message_num']); ?></a>
                            <?php } ?>
                  </div>
                  <div class="handle"><a href="index.php?act=member_information&op=avatar" title="修改头像"><i class="icon-camera"></i>修改头像</a><a href="index.php?act=member_information&op=member" title="修改资料"><i class="icon-pencil"></i>修改资料</a><a href="index.php?act=login&op=logout" title="安全退出"><i class="icon-off"></i>安全退出</a></div>
                  <div class="name"><?php echo $output['member_info']['member_name']; ?>&nbsp;
<?php if ($output['member_info']['level_name']) { ?>
                        <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade', 'index'); ?>');"><?php echo $output['member_info']['level_name']; ?></div>
<?php } ?>
                  </div>
                </div> -->
                <!--====================================-->
                <ul class="ncm-sidebar ncm-quick-menu">
                    <li class="side-menu-quick" nctype="commonOperations" <?php if (empty($output['common_menu_list'])) { ?>style="display: none;"<?php } ?>> <a href="javascript:void(0)">
                            <h3>常用操作</h3>
                        </a>
                        <ul>
                    <?php if (!empty($output['common_menu_list'])) { ?>
    <?php foreach ($output['common_menu_list'] as $key => $value) { ?>
                                    <li nctype="<?php echo $value['key']; ?>"> <a href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                        </ul>
                    </li>
                </ul>
                <ul id="sidebarMenu" class="ncm-sidebar m0">
<!--                    <li class="side-menu">
                        <a href="javascript:void(0)" key="trade"><h3>我的客户</h3></a>
                        <ul>
                            <li><a href="index.php?act=member_client">我的客户</a></li>
                            <li><a href="index.php?act=member_client&op=myincome">我的收益</a></li>
                        </ul>
                    </li>-->
<?php if (!empty($output['menu_list'])) { ?>
                    <?php foreach ($output['menu_list'] as $key => $value) { ?>
                            <li class="side-menu"><a href="javascript:void(0)" key="<?php echo $key; ?>" <?php if (cookie('Mmenu_' . $key) == 1) echo 'class="shrink"'; ?>>
                                    <h3><?php echo $value['name']; ?></h3>
                                </a>
        <?php if (!empty($value['child'])) { ?>
                                    <ul <?php if (cookie('Mmenu_' . $key) == 1) echo 'style="display:none"'; ?>>
            <?php foreach ($value['child'] as $key => $val) { ?>
                                            <li <?php if ($key == $output['menu_highlight']) { ?>class="selected"<?php } ?>><a href="<?php echo $val['url']; ?>"><?php echo $val['name']; ?></a></li>
            <?php } ?>
                                    </ul>
        <?php } ?>
                            </li>
    <?php } ?>
<?php } ?>

                </ul>
            </div>
            <div class="right-layout">
<?php require_once($tpl_file); ?>
            </div>
            <div class="clear"></div>
        </div></div></div>
<?php require_once template('footer'); ?>
</body>
</html>