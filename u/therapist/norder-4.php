<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/u/therapist/norder-ppa-suite.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/nexec-4.php';

class norder_mock_application extends per_patient_application {

  var $_loo_title = '[リハ箋一覧]';

  function norder_mock_application() {
    global $_u_therapist_norder_ppa_suite;
    $this->application_suite = $_u_therapist_norder_ppa_suite;
    per_patient_application::per_patient_application();
  }

  function allow_new() {
	  return $this->loo->allow_new();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_rehab_nexecs($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new rehab_nexec_display($prefix, $cfg);
  }

}

$main = new norder_mock_application();
$main->main();
?>
