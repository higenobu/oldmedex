<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/lcmfm.php';

class lcmfm_application extends per_patient_application {

	var $auto_use_lop = 'ppa_checkin_list';
//	var $use_list_of_checkin = 1;
	var $use_auto_sod_soe_setup = 2;
	var $use_single_pane = 1;
	var $use_printer =1;

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		if ($this->qxr_text)
			$cfg['Default_QXR_Draw'] = 'text';
		$this->cfg_pt($cfg, $it);
		return new list_of_lcmfms($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		if ($this->qxr_text)
			$cfg['Default_QXR_Draw'] = 'text';
		$this->cfg_pt($cfg, $it);
		return new lcmfm_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		if ($this->qxr_text)
			$cfg['Default_QXR_Draw'] = 'text';
		$this->cfg_pt($cfg, $it);
		return new lcmfm_edit($prefix, $cfg);
	}

	function print_sod() {
    		$this->sod->print_sod();
  }


}

class lcmfm_read_only_application extends lcmfm_application {
	var $_browse_only = 1; 
}

?>
