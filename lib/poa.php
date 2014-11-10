<?php // -*- mode: php; coding: euc-japan -*-
// Per-order application base class
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

class per_order_application extends per_patient_application {

	function msg_pick_a_order() {
		return "実施するオーダを選択";
	}

	function msg_order_to_execute() {
		return "実施するオーダ";
	}

	function msg_switch_order() {
		return "別のオーダを実施";
	}

	function msg_execution_records() {
		return "実施記録";
	}

	function setup_widgets() {
		$this->loo = $this->list_of_objects('loo-', &$this);
		$this->sod = $this->object_display('sod-', &$this);
		$this->soe = $this->object_edit('soe-', &$this);

		$this->looo = $this->list_of_order_objects('looo-', &$this);

		if (!$this->switch_patient &&
		    !array_key_exists('SwitchOrderObject', $_REQUEST) &&
		    array_key_exists('OrderObjectID', $_REQUEST)) {
			$this->order_ObjectID = $_REQUEST['OrderObjectID'];
		} else if ($this->looo->changed() && $this->looo->chosen()) {
			$this->order_ObjectID = $this->looo->chosen();
		} else {
			$this->order_ObjectID = NULL;
		}

		if ($this->loo->lost_selection() ||
		    is_null($this->order_ObjectID)) {
			$this->sod->reset(NULL);
			$this->soe->reset(NULL);
		}

		if (!is_null($this->order_ObjectID)) {
			$this->switch_sood();
		} else {
			$this->sood = NULL;
		}

	}

	function switch_sood() {
		$this->sood = $this->order_object_display('sood-', &$this);
		$this->sood->reset($this->order_ObjectID);
	}

	function switch_patient_reset() {
		if ($this->looo)
			$this->looo->reset(NULL);
		if ($this->sood)
			$this->sood->reset(NULL);
		per_patient_application::switch_patient_reset();
	}

	function cfg_pt(&$cfg, &$it) {
		$cfg['Patient_ID'] = $it->patient_ID;
		$cfg['Patient_ObjectID'] = $it->patient_ObjectID;
		$cfg['Patient_Name'] = $it->patient_Name;
	}

	function left_pane() {
		if ($this->lop) {
			mx_titlespan($this->lop->list_name . 'から選択');
			$this->lop->draw();
		}
		else if (is_null($this->patient_ObjectID))
			return;
		else
			$this->left_pane_1();
	}

	function left_pane_1() {
		if (is_null($this->sood)) {
			mx_titlespan($this->msg_pick_a_order());
			$this->looo->draw();
		} else {

			mx_titlespan($this->msg_execution_records());
			per_patient_application::left_pane_1();
			print "<hr />";

			mx_formi_hidden('OrderObjectID', $this->sood->id);
			mx_titlespan($this->msg_order_to_execute());
			$this->sood->draw();
			mx_formi_submit('SwitchOrderObject', 1,
					$this->msg_switch_order());
		}
	}

	function allow_new() {
		return (!is_null($this->patient_ID) &&
			!is_null($this->order_ObjectID));
	}

	function auto_sod_soe_setup() {
		if (!$this->use_auto_sod_soe_setup)
			return;

		/*
		 * The lower bits of $use_auto_sod_soe_setup is
		 * used by the parent class ppa to control how
		 * this class behaves after an order object is
		 * chosen.
		 *
		 * When no order object is chosen upon entry,
		 * the presense of bitmask 8 instructs the class to
		 * choose the order automatically if there is only
		 * one order.  In addition, bitmask 16 makes the class
		 * to pick the first order in the order table if there
		 * is more than one.
		 */
		if (is_null($this->sood)) {
			$uasss = $this->use_auto_sod_soe_setup;
			if (($uasss & 24) &&
			    ($this->looo->select_first(!($uasss & 16)))) {
				$this->order_ObjectID = $this->looo->chosen();
				$this->switch_sood();
			}
		}
		if (is_null($this->sood))
			return;
		per_patient_application::auto_sod_soe_setup();
	}

}

?>
