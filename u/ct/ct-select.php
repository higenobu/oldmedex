<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/ct/ct.php';

class ct_select_application extends single_table_application {
  
  var $use_upload = 1;
  var $use_single_pane = 0;
  function ct_select_application() {
    single_table_application::single_table_application();
    $this->CT_ObjectID = $_REQUEST['loo-id-select'];
  }

  function allow_new() {
    return $this->sod->chosen();
  }

  function list_of_objects($prefix) { // override
    return new list_of_ct_cts($prefix);
  }
}

$app = new ct_select_application();
if($app->CT_ObjectID) {
  $application = '/u/ct/ct-orderset.php';
  $target = '?CTID=' . $app->CT_ObjectID;
  $goto = ('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
	   $application .
	   $target);
  return mx_http_redirect($goto);
}
$app->main();
?>
