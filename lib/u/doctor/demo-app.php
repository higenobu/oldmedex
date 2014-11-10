<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/demo.php';

class demo_application extends per_patient_application {

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_demos($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new demo_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new demo_edit($prefix, $cfg);
	}

}
?>

