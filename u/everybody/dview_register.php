<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/dview.php';
if(isset($_REQUEST['PatientID'])) {
  $dview = new DView($_REQUEST['PatientID']);
  if (is_null($dview->pt)) {
    print "����ID{$_REQUEST['PatientID']}��¸�ߤ��ޤ���";
    return;
  }
 }else{
  print "����ID�����ꤵ��Ƥ��ޤ���";
  return;
 }
$dview->register_patient();
?>
