<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/xctorder2.php';

class xctorder2_application extends per_patient_application {
//	var $auto_use_lop = 'ppa_checkin_list';
//	var $use_list_of_checkin = 1;
var $use_printer =1;
	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_xctorder2s($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new xctorder2_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new xctorder2_edit($prefix, $cfg);
	}
 function print_sod() {
    $this->sod->print_sod();
   }

}
?>


