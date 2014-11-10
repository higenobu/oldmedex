<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/xctorder.php';

class xctorder_application extends per_patient_application {

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_xctorders($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new xctorder_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new xctorder_edit($prefix, $cfg);
	}

}
?>


