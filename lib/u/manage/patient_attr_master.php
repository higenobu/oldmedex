<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_manage_patient_attr_master_cfg = array
(
	'TABLE' => '患者属性一覧',
	'COLS' => array("グループ", "名称", "属性型",
			"LB", "UB", "DP", "選択肢", "表示順位",
			"Retired", "length"),
	'DCOLS' => array(
		"グループ",
		"名称",
		array('Column' => "属性型",
		      'Draw' => 'enum',
		      'Enum' => array('C' => '選択肢',
				      'M' => '複数選択',
				      'T' => '文字列',
//				      'I' => '整数値',
//				      'D' => '十進数値',
				      )),
/*
		array('Column' => "LB",
		      'Draw' => 'text',
		      'Option' => array('validate' => 'digits',
					'empty-is-null' => 1)),
		array('Column' => "UB",
		      'Draw' => 'text',
		      'Option' => array('validate' => 'digits',
					'empty-is-null' => 1)),
		array('Column' => "DP",
		      'Draw' => 'text',
		      'Option' => array('validate' => 'digits',
					'empty-is-null' => 1)),
*/
		array('Column' => "選択肢",
		      'Draw' => 'textarea',
		      'empty-is-null' => 1),
		array('Column' => "length",
		      'Label' => "最大長"),
		array('Column' => "表示順位",
		      'Draw' => 'text',
		      'Option' => array('validate' => 'digits')),
		array('Column' => "Retired",
		      'Label' => '使用',
		      'Draw' => 'enum',
		      'Enum' => array('' => '使用する',
				      'Y' => '使用しない')),
		),
);
$_lib_u_manage_patient_attr_master_cfg['LCOLS'] =
$_lib_u_manage_patient_attr_master_cfg['ECOLS'] =
$_lib_u_manage_patient_attr_master_cfg['DCOLS'];


class list_of_patient_attr_master extends list_of_simple_objects {
	function list_of_patient_attr_master($prefix, $cfg=NULL) {
		global $_lib_u_manage_patient_attr_master_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_manage_patient_attr_master_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

class patient_attr_master_display extends simple_object_display {
	function patient_attr_master_display($prefix, $cfg=NULL) {
		global $_lib_u_manage_patient_attr_master_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_manage_patient_attr_master_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class patient_attr_master_edit extends simple_object_edit {
	function patient_attr_master_edit($prefix, $cfg=NULL) {
		global $_lib_u_manage_patient_attr_master_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_manage_patient_attr_master_cfg;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
}

?>
