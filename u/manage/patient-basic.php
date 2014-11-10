<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-basic.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/appbar.php';

class patient_basic_application extends single_table_application {
  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');
  var $_loo_title = '患者台帳';
  var $_sod_title = '患者基本データ';
  var $_soe_title = '患者基本データ編集';
  var $_soc_title = '患者基本データ登録';
  var $top_inside_form = 1;
  var $use_card = 1;

  var $msgs = array
  ('New' => '新規患者を登録する',
   'New Like This' => 'この患者をコピーして新しい患者を作る',
   'Edit' => 'この患者を編集する',
   'Card' => '診察券を発行する',
   );
  function list_of_objects($prefix) {
    return new list_of_patient_basics($prefix);
  }
  function object_display($prefix) {
    return new patient_basic_display($prefix);
  }
  function object_edit($prefix) {
    return new patient_basic_edit($prefix);
  }

  function setup() {
    $v = single_table_application::setup();
    if ($v)
	    return $v;
   
    if ($this->sod->chosen() && array_key_exists('Card', $_REQUEST))
      $this->issue_card();



    if (mx_check_request('SetPatient')) { // Foreign call from other manage
      $patient_id = trim(mx_check_request('PatientID'));
      if ($patient_ObjectID = mx_find_patient_by_patient_id($patient_id)) {
	// Ugh.
	$this->loo->selection = $patient_ObjectID;
	$this->loo->selection_changed = 1;
	$this->sod->reset($this->loo->chosen());
      }
    }
    return 0;
  }

  function top_pane() {
    global $_mx_use_appbar;

    print "<table><tr valign=\"top\"><td width=\"50%\">";
    if ($_mx_use_appbar)
      $this->top_pane_left(0);
    else
      $this->top_pane_left(1);
    print "</td><td>";
    if ($this->loo && $this->loo->chosen()) {
      $data = mx_draw_patientinfo_brief($this->loo->chosen());
      $this->patient_ID = $data['患者ID'];
      $this->patient_ObjectID = $this->loo->selection;
      if (!$_mx_use_appbar)
        mx_draw_ppa_applist($data['患者ID']);
    }
    print "</td></tr></table>\n";
    if ($_mx_use_appbar)
	    mx_appbar($this);
  }

  function issue_card() {
 
    $this->sod->issue_card();
  }
}

$pba = new patient_basic_application();
$pba->main();
?>
