<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';

function _lib_u_manage_medis_medicine_cfg_setup() {
	global $_lib_u_manage_medis_medicine_cfg;
	$cols = array(
		"����ֹ�",
		"�������ֹ�",
		"��Ҽ������ֹ�",
		"Ĵ�����ֹ�",
		"ʪή���ֹ�",
		"�ʣ��Υ�����",
		"���������ܰ����ʥ�����",
		"���̰����ʥ�����",
		"�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
		"�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
		"��̾��",
		"����̾",
		"�쥻�ץ��Ż����������ƥ������̾",
		"����ñ��",
		"��������",
		"����ñ�̿�",
		"����ñ��ñ��",
		"�������̿�",
		"��������ñ��",
		"��ʬ",
		"��¤���",
		"������",
		"������ʬ",
		"����ǯ����",
		"��������",
		"�±����Ѱ�����̾",
		"�±���������ñ��ñ��",
		"�±����ѥ쥻���󥳡���"
		);
	$c = array(
		TABLE => 'Medis�����ʥޥ�����',
		COLS => array("�±����Ѱ�����̾",
			      "�±���������ñ��ñ��",
			      "�±����ѥ쥻���󥳡���",
			      ),
		DCOLS => $cols,
		ECOLS => $cols,
		);


	$_lib_u_manage_medis_medicine_cfg = $c;
}

_lib_u_manage_medis_medicine_cfg_setup();

class list_of_medis_medicines extends list_of_simple_objects {
	var $default_row_per_page = 4;
	function list_of_medis_medicines($prefix, $cfg=NULL) {
		global $_lib_u_manage_medis_medicine_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_medis_medicine_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

class medis_medicine_display extends simple_object_display {
	function medis_medicine_display($prefix, $cfg=NULL) {
		global $_lib_u_manage_medis_medicine_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_medis_medicine_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

?>
