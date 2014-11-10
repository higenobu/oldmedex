<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/poa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/hdn.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/hdorder.php';

class list_of_hdorders_for_hdn extends list_of_hdorders {

	function list_of_hdorders_for_hdn($prefix, $cfg=NULL) {
		__lib_u_doctor_hdorder_cfg(&$cfg);
		$lc = array();
		$copy_from_dcol_columns = array('ダイアライザー');
		$drop_from_lcol_columns = array('オーダ終了日');
		foreach ($cfg['LCOLS'] as $elem) {
			$col = $elem['Column'];
			if ($col == '')
				;
			else if (in_array($col, $drop_from_lcol_columns))
				continue;
			$lc[] = $elem;
		}
		foreach ($copy_from_dcol_columns as $col) {
			$elem = find_by_column($cfg['DCOLS'], $col);
			if ($elem)
				$lc[] = $elem;
		}
		$cfg['LCOLS'] = $lc;
		list_of_hdorders::list_of_hdorders($prefix, $cfg);
	}

	function base_fetch_stmt_0() {
		return (list_of_hdorders::base_fetch_stmt_0() .
			' AND ' .
			'("オーダ終了日" IS NULL OR '.
			'CURRENT_DATE <= "オーダ終了日")');
	}

}

class hdn_application extends per_order_application {

	var $auto_use_lop = 'ppa_checkin_list';
	var $use_list_of_checkin = 1;

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$that = new list_of_hdns($prefix, $cfg);
		$that->application = &$it;
		return $that;
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$that = new hdn_display($prefix, $cfg);
		$that->application = &$it;
		return $that;
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$that = new hdn_edit($prefix, $cfg);
		$that->application = &$it;
		return $that;
	}

	function list_of_order_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$that = new list_of_hdorders_for_hdn($prefix, &$cfg);
		$that->application = &$it;
		return $that;
	}

	function order_object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$that = new hdorder_display($prefix, $cfg);
		$that->application = &$it;
		return $that;
	}

}
?>
