<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/karteview.php';

function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;

  $DateOfIssue = s($data['reportdate']);

  $PatientID = s($data['PATIENT_ID']);
  $PatientName = s($data['PATIENT_KANJI']);
  $PatientKana = s($data['PATIENT_KANA']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientGroup = s($data['PatientGroup']);
  $PatientAge = s($data['PatientAge']);
  // $RxBody = s($data['RxBody']);
  $facttype = s($data['facttype']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 $S0=s($data['S0']);



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


class to extends everybody_karteview_display {
  function draw_body_2() {
  }
}



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
         </center>';
}
elseif ($bottom) {
  $test_app_types = array('insident');
  $titles = array("insident");
  $ttl = "";
  mx_html_head($ttl,false);
 


  $db = mx_db_connect();
  $stmt = 'select "ID","ObjectID","Superseded", "����" ,"����","S0"
  from "����ƥǥ�ɽ"   where "Superseded" is null and "ObjectID"=1' ;
  $r = mx_db_fetch_single($db, $stmt);
 
 $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['����'];
  $sod->id = $oid;
  $sod->fetch_data($oid);

// mx_draw_patientinfo_bmd($r['����'], array('Culture' => 'Japanese',
//					       'ShowWardPref' => 1));
/*  
$sod->draw();
  print <<<HTML

HTML;
*/
 

$pat = get_patient($r['����'],false);



$data = array();
  
  $data['reportdate'] = mx_wareki($r['����']);
 	 
 
 $data['S0'] = $r['S0'];
 

$data['PATIENT_ID'] = $pat['����ID'];
  $data['PATIENT_KANA'] = $pat['�եꥬ��'];
  $data['PATIENT_KANJI'] = $pat['��'] .'��'. $pat['̾'];
  $data['PATIENT_DOB'] = mx_wareki($pat['��ǯ����']);
  
  $data['PATIENT_SEX'] = $pat['����'] == 'M' ? '��' : '��';
  
  
$data['PatientGroup'] = $pat['��˾����'];
  $data['PatientAge'] = mx_calc_age($pat['��ǯ����']);

  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
 

 

  print rx_template($data);
	 

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="printkarte.php?top=1" name="top_frame" scrolling="no">
         <frame src="printkarte.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
