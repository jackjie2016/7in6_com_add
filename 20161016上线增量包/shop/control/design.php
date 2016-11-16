<?php
/**
 * design专题
 *
 * @copyright  QQ1249200310 peiyu
 * @link       http://shopjlcom.taobao.com
 */
defined('InShopNC') or exit('Access Invalid!');
class designControl extends BaseHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','special');
    }

    public function indexOp() {
        $this->designOp();
    }

    /**
     * 专题列表
     */
    public function designOp() {
        Tpl::showpage('design');
    }

    
}
