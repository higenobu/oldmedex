<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

$__lib_u_nutrition_meal_nutri_master_cfg = array
(
	'TABLE' => 'meal_nutrition_master',
	'SEQUENCE' => 'meal_nutrition_master_id_seq',
	'COLS' => array('sort_order', 'name',
			'energy_base', 'protein_base', 'fat_base', 'salt_base',
			'energy_mod', 'protein_mod', 'fat_mod', 'salt_mod'),

	'LCOLS' => array(
		array('Column' => 'sort_order',
		      'Label' => '表示順',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,digits')),
		array('Column' => 'name',
		      'Label' => '名前',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull')),
		),
		array('Column' => 'energy_base',
		      'Label' => 'エネルギー(基本)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'energy_mod',
		      'Label' => 'エネルギー(主食100グラム当り)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),

	'DCOLS' => array(

		array('Column' => 'sort_order',
		      'Label' => '表示順',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,digits')),
		array('Column' => 'dont_use',
		      'Label' => '使用中止',
		      'Draw' => 'enum',
		      'Enum' => array(NULL => '使用',
				      'Y' => '使用中止')),
		array('Column' => 'name',
		      'Label' => '名前',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull')),

		array('Column' => 'energy_base',
		      'Label' => 'エネルギー(基本)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'protein_base',
		      'Label' => '蛋白質(基本)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'fat_base',
		      'Label' => '脂質(基本)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'salt_base',
		      'Label' => '塩分(基本)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),

		array('Column' => 'energy_mod',
		      'Label' => 'エネルギー(主食100グラム当り)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'protein_mod',
		      'Label' => '蛋白質(主食100グラム当り)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'fat_mod',
		      'Label' => '脂質(主食100グラム当り)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		array('Column' => 'salt_mod',
		      'Label' => '塩分(主食100グラム当り)',
		      'Draw' => 'text',
		      'Option' => array('validate' => 'nonnull,number')),
		),
);
$__lib_u_nutrition_meal_nutri_master_cfg['ECOLS'] =
$__lib_u_nutrition_meal_nutri_master_cfg['DCOLS'];
$__lib_u_nutrition_meal_nutri_master_cfg['ICOLS'] =
$__lib_u_nutrition_meal_nutri_master_cfg['COLS'];

class list_of_meal_nutri_masters extends list_of_simple_objects {

	function list_of_meal_nutri_masters($prefix, $cfg=NULL) {
		global $__lib_u_nutrition_meal_nutri_master_cfg;
		if (is_null($cfg))
			$cfg = $__lib_u_nutrition_meal_nutri_master_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

}

class meal_nutri_master_display extends simple_object_display {

	function meal_nutri_master_display($prefix, $cfg=NULL) {
		global $__lib_u_nutrition_meal_nutri_master_cfg;
		if (is_null($cfg))
			$cfg = $__lib_u_nutrition_meal_nutri_master_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}

}

class meal_nutri_master_edit extends simple_object_edit {

	function meal_nutri_master_edit($prefix, $cfg=NULL) {
		global $__lib_u_nutrition_meal_nutri_master_cfg;
		if (is_null($cfg))
			$cfg = $__lib_u_nutrition_meal_nutri_master_cfg;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

}

?>
