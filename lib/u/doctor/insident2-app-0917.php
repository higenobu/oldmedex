<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/insident2.php';

class insident2_application extends single_table_application {
var $use_printer =1;
	function list_of_objects($prefix) {
		$cfg = array();
		
		return new list_of_insident2($prefix, $cfg);
	}

	function object_display($prefix) {

		$cfg = array();
		
		return new insident2_display($prefix, $cfg);
	}

	function object_edit($prefix) {
		$cfg = array();
		
		return new insident2_edit($prefix, $cfg);
	}
function print_sod() {
    $this->sod->print_sod();
  }
}
?>


