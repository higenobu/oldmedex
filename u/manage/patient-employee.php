<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-application.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-employee.php';

class patient_employee_application extends patient_application_base {
  var $_loo_title = '������Ģ';
  var $_sod_title = '����ô������';
  var $_soe_title = '����ô�������Խ�';

  function object_display($prefix) {
    return new patient_employee_display($prefix);
  }

  function object_edit($prefix) {
    return new patient_employee_edit($prefix);
  }

  function allow_new() {
    return 0;
  }
}

$pea = new patient_employee_application();
$pea->main();
?>
