<?php

/**

 * 用户图片模块

 *** by 网店技术交流中心 www.7in6.com*/





defined('InShopNC') or exit('Access Invalid!');



class member_imageControl extends BaseMemberControl {

    public function __construct() {

        parent::__construct();

		//读取语言包

		Language::read('member_home_message');

    }

	/**

	 * 查询个人图片

	 *

	 * @param

	 * @return

	 */

	public function imageOp() {
                           
		/**

		 * 分页类

		 */

		$page	= new Page();

		$page->setEachNum(12);

		$page->setStyle('admin');

		/**

		 * 实例化相册类

		 */

		$model_image = Model('image');

		$param = array();

		$param['member_id']	= $_SESSION['member_id'];
                
                $param['order']	                = 'upload_time desc';

		$pic_list = $model_image->getPicList($param,$page);

		Tpl::output('pic_list',$pic_list);

		Tpl::output('show_page',$page->show());

		self::profile_menu('image','image');

		$this->profile_menu('image');

		Tpl::showpage('member_image');

	}


	/**

	 * 用户中心右边，小导航

	 *

	 * @param string 	$menu_key	当前导航的menu_key

	 * @return

	 */

	private function profile_menu($menu_key='') {

		$menu_array	= array(

    		1=>array('menu_key'=>'image',	'menu_name'=>Language::get('imagelist'),'menu_url'=>'index.php?act=member_image&op=image'),
                

		);

		Tpl::output('member_menu',$menu_array);
                
		Tpl::output('menu_key',$menu_key);

	}

}

