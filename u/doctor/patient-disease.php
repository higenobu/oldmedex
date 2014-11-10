<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/patient-disease.php';

class doctor_patient_disease_application extends per_patient_application {

  var $use_list_of_checkin = 0;
  var $can_use_subpick_on_left = 1;
  var $auto_use_lop = 'ppa_checkin_list';

  function doctor_patient_disease_application() {
    global $_mx_use_checkin_list;
    $this->use_list_of_checkin = $_mx_use_checkin_list;
    per_patient_application::per_patient_application();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_patient_diseases($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new patient_disease_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new patient_disease_edit($prefix, $cfg);
  }

}

$main = new doctor_patient_disease_application();
$main->main();
?>
