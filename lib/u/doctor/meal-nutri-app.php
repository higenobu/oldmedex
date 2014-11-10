<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/meal-nutri.php';

class meal_nutri_order_application extends per_patient_application {

	var $use_single_pane = 1;
	var $use_list_of_checkin = 1;
	var $auto_use_lop = 'ppa_checkin_list';
//added 10-20-2014
var $use_printer =1;
	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_meal_nutri_orders($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new meal_nutri_order_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new meal_nutri_order_edit($prefix, $cfg);
	}

//
function print_sod() {
    $this->sod->print_sod();
  }
//

}

?>
