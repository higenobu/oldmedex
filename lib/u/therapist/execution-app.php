<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/execution.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

class therapist_execution_application extends per_patient_application {

  var $_loo_title = '[¥ê¥Ïäµ°ìÍ÷]';
  var $use_single_pane = 1;

  function allow_new() {
	  return $this->loo->allow_new();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_rehab_execs($prefix, $cfg);
  }

  function cfg_pt(&$cfg, &$it) {
    per_patient_application::cfg_pt($cfg, $it);
    $db =& mx_db_connect();
    $patient = $it->patient_ObjectID;

    $stmt = ('
SELECT DISTINCT E."ObjectID", (E."À«" || \' \' || E."Ì¾") AS "À«Ì¾"
FROM "¿¦°÷ÂæÄ¢" AS E
JOIN "´µ¼ÔÃ´Åö¿¦°÷" AS R ON
	R."Superseded" IS NULL AND
	R."´µ¼Ô" = ' . mx_db_sql_quote($patient) . '
JOIN "´µ¼ÔÃ´Åö¿¦°÷¥Ç¡¼¥¿" AS RD ON
	R."ObjectID" = RD."´µ¼ÔÃ´Åö¿¦°÷" AND
	E."ObjectID" = RD."¿¦°÷"
JOIN "¿¦¼ï°ìÍ÷É½" AS EC ON
	EC."ObjectID" = E."¿¦¼ï" AND
	EC."¿¦¼ï" IN ' . enum_therapist_cat_sql() . '
ORDER BY E."ObjectID"');

    $emp_enum = array();
    foreach (pg_fetch_all(pg_query($db, $stmt)) as $e) {
      $emp_enum[$e['ObjectID']] = $e["À«Ì¾"];
    }
    $cfg['EmployeeEnum'] = $emp_enum;
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new rehab_exec_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $edit = new rehab_exec_edit($prefix, $cfg);
    $edit->Rx_ObjectID = $this->loo->Rx_ObjectID;
    return $edit;
  }

}

class therapist_execution_browse_application extends therapist_execution_application  {
	var $_browse_only = 1;
	function allow_new() { return 0; }
}

?>
