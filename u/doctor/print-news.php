<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';



function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;

  $DateOfIssue = s($data['reportdate']);

  $PatientID = s($data['PATIENT_ID']);
$PatientDOB=s($data['PATIENT_DOB']);
$PatientSex=s($data['PATIENT_SEX']);
  $PatientName = s($data['ptname']);
  $setainusi = s($data['setainusi']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientAddr = s($data['PatientAddr']);
  $PatientAge = s($data['PatientAge']);
  // $RxBody = s($data['RxBody']);
  $facttype = s($data['facttype']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 $S0=s($data['S0']);
$kohnum  = s($data['WELFARE_NUMBER']);
  $ftnjanum = s($data['WELFARE_RECIPIENT']);
  $kohkigen = s($data['WELFARE_GOOD_THRU'] );
  
 
  // ���Ի�̾����ǯ��������ʬ�����
  
  $ptkubun = s($data['PATIENT_KUBUN']);
  
 // �ݸ����ֹ�
  // ���ݸ��Ծڵ��桦�ֹ�
  $hknjanum = s($data['INSURER_NUMBER']);
  $kigo=s($data['INSURED_KIGO']);
  $bango=s($data['INSURED_NUMBER']);
  $insname=s($data['insname']);
  
$insadrs=s($data['insadrs']);
$kaku=s($data['kaku']);
  // ���ŵ��ؽ���ϡ�̾��
  // �����ֹ桢�ݸ����̾
  


//0707-2011
//	$Stop = s($data['Stop']);
  $template = 'karte.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];





if ($top) {
  print '<script language="javascript" type="text/javascript">
         <!--
         function printPopup() {
         parent.frames[1].focus();
         parent.frames[1].print();
         }
         -->
         </script>';
  mx_html_head("",false);



  print '<body><center>
         <form><input type="button" value="����" onClick="printPopup()">
         <input type="button" value="���̤��Ĥ���" onClick="window.parent.close()">
         </form>
         </center>
<a href="index.php?tab=1">�ᥤ������</a>';
}
/*
elseif ($bottom) {
  


  print "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";

	print '<a href="https://localhost:8181/BlogWebApp/">Blog2</a><br>';

 

}
*/

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print-news.php?top=1" name="top_frame" scrolling="no">
         <frame src="print-news.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
