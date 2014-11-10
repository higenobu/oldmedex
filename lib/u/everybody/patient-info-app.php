<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppas.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/patient-info.php';

class patientinfo_application extends per_patient_singleton_application {

  var $use_auto_sod_soe_setup = 3;
      var $use_printer =1;
      var $use_single_pane = 1;
      function print_sod() {
      	       $this->sod->print_sod();
      }


	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_patientinfos($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$cfg['D_TEMPLATE'] = 'patientinfo.html';
		return new patientinfo_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		$cfg['E_TEMPLATE'] = 'patientinfo.html';
		return new patientinfo_edit($prefix, $cfg);
	}
}
?>