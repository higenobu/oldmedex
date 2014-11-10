<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
//0325-2014 ndk_order.php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/ndk_order.php';

class otatest2_order_application extends per_patient_application {
	var $use_printer =1;
	var $use_single_pane = 1;
 	var $use_list_of_checkin = 1;
 	var $auto_use_lop = 'ppa_checkin_list';

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_otatest2_orders($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new otatest2_order_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new otatest2_order_edit($prefix, $cfg);
	}
function print_sod() {
    $this->sod->print_sod();
  }
}

?>
