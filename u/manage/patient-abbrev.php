<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-application.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-abbrev.php';

class patient_abbrev_application extends patient_application_base {
  var $_upper = array('index.php' => '/images/top_button.png');
  var $_loo_title = '�������ౡ����';
  var $_sod_title = '�������ౡ�ǡ���';
  var $_soe_title = '�������ౡ�ǡ����Խ�';

  var $msgs = array
  ('Edit' => '�������ౡ����');

  function object_display($prefix) {
    return new patient_abbrev_display($prefix);
  }

  function object_edit($prefix) {
    return new patient_abbrev_edit($prefix);
  }

  function allow_new() { // Override
	  return 0;
  }

}

$pba = new patient_abbrev_application();
$pba->main();
?>
