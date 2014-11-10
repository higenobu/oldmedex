<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';

function _lib_u_manage_medis_medicine_cfg_setup() {
	global $_lib_u_manage_medis_medicine_cfg;
	$cols = array(
		"基準番号",
		"処方用番号",
		"会社識別用番号",
		"調剤用番号",
		"物流用番号",
		"ＪＡＮコード",
		"薬価基準収載医薬品コード",
		"個別医薬品コード",
		"レセプト電算処理システムコード（１）",
		"レセプト電算処理システムコード（２）",
		"告示名称",
		"販売名",
		"レセプト電算処理システム医薬品名",
		"規格単位",
		"包装形態",
		"包装単位数",
		"包装単位単位",
		"包装総量数",
		"包装総量単位",
		"区分",
		"製造会社",
		"販売会社",
		"更新区分",
		"更新年月日",
		"当院採用",
		"病院使用医薬品名",
		"病院使用包装単位単位",
		"病院使用レセコンコード"
		);
	$c = array(
		TABLE => 'Medis医薬品マスター',
		COLS => array("病院使用医薬品名",
			      "病院使用包装単位単位",
			      "病院使用レセコンコード",
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
