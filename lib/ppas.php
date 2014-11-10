<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

class per_patient_singleton_application extends per_patient_application {

	var $use_single_pane = 1;
	var $use_auto_sod_soe_setup = 7;
	var $use_list_of_checkin = 1;
	var $auto_use_lop = 'ppa_checkin_list';

	function setup() {
		$this->setup_patient(); // from ppa
		if (is_null($this->patient_ObjectID))
			return;
		$v = single_table_application::setup();
		if ($v) {
			return $v;
		}
		if ($this->switch_patient) {
			$this->switch_patient_reset();
		}
		$this->auto_sod_soe_setup();
	}

	function draw_plain_new_control($optional=NULL) {
		; /* ignore */
	}

	function switch_patient_reset() { // override
		$this->soe->reset(NULL);
	}

	function right_pane() {
		if (is_null($this->patient_ObjectID))
			return;
		$this->right_pane_1();
	}

	function right_pane_1() { // override
		single_table_application::right_pane();
	}

	function allow_new() {
		return !is_null($this->patient_ID);
	}

}
?>
