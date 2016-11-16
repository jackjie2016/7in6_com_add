<?php
/**
 * 用户图片model层
 *
 * by peiyu  www.7in6.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');
class imageModel extends Model {
     public function __construct(){
        parent::__construct();
    }
	/**
	 * 图片列表
	 *
	 * @param array $condition 查询条件
	 * @param obj $page 分页对象
	 * @return array 二维数组
	 */
	public function getPicList($condition, $page='', $field='*'){
		$param	= array();
		$param['table']			= 'upimgs';
		$param['where']			= $this->getCondition($condition);
		$param['order']			= $condition['order'] ? $condition['order'] : 'apic_id desc';
		$param['field']			= $field;
		return Db::select($param,$page);
	}
        /**
	 * 构造查询条件
	 *
	 * @param array $condition 条件数组
	 * @return $condition_sql
	 */
	private function getCondition($condition){
		$condition_sql	= '';
		/*if($condition['apic_id'] != '') {
			$condition_sql .= " and apic_id= '{$condition['apic_id']}'";
		}
		if($condition['apic_name'] != '') {
			$condition_sql .= " and apic_name='".$condition['apic_name']."'";
		}
		if($condition['apic_tag'] != '') {
			$condition_sql .= " and apic_tag like '%".$condition['apic_tag']."%'";
		}
		if($condition['aclass_id'] != '') {
			$condition_sql .= " and aclass_id= '{$condition['aclass_id']}'";
		}
		if($condition['album_aclass.store_id'] != '') {
			$condition_sql .= " and `album_class`.store_id = '{$condition['album_aclass.store_id']}'";
		}
		if($condition['album_aclass.aclass_id'] != '') {
			$condition_sql .= " and `album_class`.aclass_id= '{$condition['album_aclass.aclass_id']}'";
		}
		if($condition['album_pic.store_id'] != '') {
			$condition_sql .= " and `album_pic`.store_id= '{$condition['album_pic.store_id']}'";
		}
		if($condition['album_pic.apic_id'] != '') {
			$condition_sql .= " and `album_pic`.apic_id= '{$condition['album_pic.apic_id']}'";
		}*/
		if($condition['member_id'] != '') {
			$condition_sql .= " and member_id= '{$condition['member_id']}'";
		}
                /*
		if($condition['aclass_name'] != '') {
			$condition_sql .= " and aclass_name='".$condition['aclass_name']."'";
		}
		if($condition['in_apic_id'] != '') {
			$condition_sql .= " and apic_id in (".$condition['in_apic_id'].")";
		}
		if($condition['gt_apic_id'] != '') {
			$condition_sql .= " and apic_id > '{$condition['gt_apic_id']}'";
		}
		if($condition['like_cover'] != '') {
			$condition_sql .= " and apic_cover like '%".$condition['like_cover']."%'";
		}
		if($condition['is_default'] != '') {
			$condition_sql .= " and is_default= '{$condition['is_default']}'";
		}
		if($condition['album_class.un_aclass_id'] != '') {
			$condition_sql .= " and `album_class`.aclass_id <> '{$condition['album_class.un_aclass_id']}'";
		}*/
		return $condition_sql;
	}
	
}