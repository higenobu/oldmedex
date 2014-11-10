<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/u/therapist/norder-ppa-suite.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/nexec-7.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

class norder_mock_application extends per_patient_application {

  var $_loo_title = '[���䵰���]';

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

  function cfg_pt(&$cfg, &$it) {
    per_patient_application::cfg_pt($cfg, $it);
    $db =& mx_db_connect();
    $patient = $it->patient_ObjectID;

    $stmt = ('
SELECT DISTINCT E."ObjectID", (E."��" || \' \' || E."̾") AS "��̾"
FROM "������Ģ" AS E
JOIN "����ô������" AS R ON
	R."Superseded" IS NULL AND
	R."����" = ' . mx_db_sql_quote($patient) . '
JOIN "����ô�������ǡ���" AS RD ON
	R."ObjectID" = RD."����ô������" AND
	E."ObjectID" = RD."����"
JOIN "�������ɽ" AS EC ON
	EC."ObjectID" = E."����" AND
	EC."����" IN ' . enum_therapist_cat_sql() . '
ORDER BY E."ObjectID"');

    $emp_enum = array();
    foreach (pg_fetch_all(pg_query($db, $stmt)) as $e) {
      $emp_enum[$e['ObjectID']] = $e["��̾"];
    }
    $cfg['EmployeeEnum'] = $emp_enum;
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new rehab_nexec_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $edit = new rehab_nexec_edit($prefix, $cfg);
    $edit->Rx_ObjectID = $this->loo->Rx_ObjectID;
    return $edit;
  }

}

$main = new norder_mock_application();
$main->main();
?>
