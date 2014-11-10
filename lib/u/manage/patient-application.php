<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

// manage/patient-application does not have the list of objects in the
// traditional sense.  It just has a single entry for the patient.
// Therefore, its loo is set up to always return the patient that
// the top pane selected.

class _lib_u_manage_per_patient_application_dummy_loo {
  function _lib_u_manage_per_patient_application_dummy_loo($prefix, $it) {
    // $it coming in is a ppa.  We are interested in patient_ID and stuff.
    $this->prefix = $prefix;
    $this->patient_ObjectID = $it->patient_ObjectID;
    if (array_key_exists($this->prefix . 'pOID', $_REQUEST)) {
	    if ($_REQUEST[$this->prefix . 'pOID'] != $it->patient_ObjectID) {
		    $this->patient_changed = 1;
	    }
	    if (array_key_exists('SetPatient', $_REQUEST))
		    $this->patient_changed = 1;
    }
    else
	    $this->patient_changed = 1;
  }
  function chosen() {
    return $this->patient_ObjectID;
  }
  function changed() {
	  return $this->patient_changed;
  }
  function draw() {
	  print mx_formi_hidden($this->prefix . 'pOID',
				$this->patient_ObjectID);
  }
  function lost_selection() {
    return 0;
  }
  function reset() { return NULL; }
}

class patient_application_base extends per_patient_application {
  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');
  var $inhibit_showloo_in_cheap_layout = 1;

  function list_of_objects($prefix) {
    return new _lib_u_manage_per_patient_application_dummy_loo
      ($prefix, $this);
  }

  function draw_loo() {
	  $this->loo->draw();
	  return NULL;
  }

  function switch_patient_reset() { // override
    // Loo always says changed and sod will be redrawn.
    $this->soe->reset(NULL);
  }

}
?>
