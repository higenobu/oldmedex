<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/newrx.php';

class newrx_application extends single_table_application {
var $use_printer =1;
var $use_single_pane = 1;
//var $_browse_only = 1; 
	function list_of_objects($prefix) {
		$cfg = array();
		
		return new list_of_newrx($prefix, $cfg);
	}

	function object_display($prefix) {

		$cfg = array();
		
		return new newrx_display($prefix, $cfg);
	}

	function object_edit($prefix) {
		$cfg = array();
		
		return new newrx_edit($prefix, $cfg);
	}
function print_sod() {
    $this->sod->print_sod();
  }
}
?>


