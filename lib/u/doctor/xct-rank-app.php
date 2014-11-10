<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/xct-rank.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/simple-object.php';
class xct_rank_application extends simple_object_application {

var $use_printer =1;
	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_xct_ranks($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new xct_rank_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new xct_rank_edit($prefix, $cfg);
	}
function print_sod() {
    $this->sod->print_sod();
  }

}
?>


