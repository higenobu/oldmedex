<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pttest.php';

class pttest_application extends per_patient_application {
var $use_printer =1;
	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_pttests($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new pttest_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new pttest_edit($prefix, $cfg);
	}

}
?>

