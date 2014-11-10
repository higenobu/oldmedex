<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_manage_patient_attr_master_cfg = array
(
	'TABLE' => '����°������',
	'COLS' => array("���롼��", "̾��", "°����",
			"LB", "UB", "DP", "�����", "ɽ�����",
			"Retired", "length"),
	'DCOLS' => array(
		"���롼��",
		"̾��",
		array('Column' => "°����",
		      'Draw' => 'enum',
		      'Enum' => array('C' => '�����',
				      'M' => 'ʣ������',
				      'T' => 'ʸ����',
//				      'I' => '������',
//				      'D' => '���ʿ���',
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
		array('Column' => "�����",
		      'Draw' => 'textarea',
		      'empty-is-null' => 1),
		array('Column' => "length",
		      'Label' => "����Ĺ"),
		array('Column' => "ɽ�����",
		      'Draw' => 'text',
		      'Option' => array('validate' => 'digits')),
		array('Column' => "Retired",
		      'Label' => '����',
		      'Draw' => 'enum',
		      'Enum' => array('' => '���Ѥ���',
				      'Y' => '���Ѥ��ʤ�')),
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
