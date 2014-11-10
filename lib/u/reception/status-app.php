<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/status.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt-app.php';

class reception_status_application extends per_patient_application {

	var $auto_use_lop = 'ppa_checkin_list';
	var $use_list_of_checkin = 1;
	var $use_auto_sod_soe_setup = 2;

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_reception_status($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new reception_status_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new reception_status_edit($prefix, $cfg);
	}

}

class reception_status_set_status extends everybody_index_pt_application {

	var $status;

	function main() {
		if (array_key_exists('SetPatient', $_REQUEST) &&
		    array_key_exists('PatientID', $_REQUEST)) {
			$this->set_status();
		}
		mx_redirect_to_user_top($this->u);
	}

	function set_status() {
		$p = mx_find_patient_by_patient_id($_REQUEST['PatientID']);
		reception_status_adjust($p, $this->status);
	}
}

class reception_status_finish_encounter extends reception_status_set_status {
	var $status = '¿Ç»¡´°Î»';
}

class reception_status_interrupt_encounter extends reception_status_set_status {
	var $status = '¿Ç»¡ÃæÃÇ';
}

?>
