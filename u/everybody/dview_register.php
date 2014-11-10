<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/dview.php';
if(isset($_REQUEST['PatientID'])) {
  $dview = new DView($_REQUEST['PatientID']);
  if (is_null($dview->pt)) {
    print "患者ID{$_REQUEST['PatientID']}は存在しません";
    return;
  }
 }else{
  print "患者IDが指定されていません";
  return;
 }
$dview->register_patient();
?>
