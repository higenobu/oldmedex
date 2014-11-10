<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/insident2.php';

function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;
$kubun=s($data['kubun']);

  $DateOfIssue = s($data['reportdate']);
$factdate = s($data['factdate']);
  $PatientID = s($data['PATIENT_ID']);
  $PatientName = s($data['PATIENT_KANJI']);
  $PatientKana = s($data['PATIENT_KANA']);
 $PatientSex=s($data['PATIENT_SEX']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientGroup = s($data['PatientGroup']);
  $PatientAge = s($data['PatientAge']);
  // $RxBody = s($data['RxBody']);
  $facttype = s($data['facttype']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 $factloc=s($data['factloc']);
 $busho=s($data['busho']);
$empnm1=s($data['empnm1']);
$empnm3=s($data['empnm3']);
$empnm2=s($data['empnm2']);

$pnm1=s($data['pnm1']);
$pnm2=s($data['pnm2']);
$pnm3=s($data['pnm3']);
$facttype=s($data['facttype']);
$factcont=s($data['factcont']);
$factdone=s($data['factdone']);
$factplan=s($data['factplan']);
$factdo=s($data['factdo']);
$proof=s($data['proof']);


//0707-2011
//	$Stop = s($data['Stop']);
  $template = 'insident.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];


class to extends insident2_display {
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
         <form><input type="button" value="印刷" onClick="printPopup()">
         <input type="button" value="画面を閉じる" onClick="window.parent.close()">
         </form>
         </center>';
}
elseif ($bottom) {
  $test_app_types = array('insident');
  $titles = array("insident");
  $ttl = "";
  mx_html_head($ttl,false);
 


  $db = mx_db_connect();
  $stmt = 'select reportdate, factdate,"患者", 
factloc ,facttype  ,busho  ,empnm1  ,empnm2  ,empnm3  ,pnm1  ,pnm2  ,pnm3  ,factcont  ,factdone  ,
factplan  ,factdo  ,kubun,proof   from insident  inner join tbl_patient on  "患者"=id where "Superseded" is null and "ObjectID"='.$oid;
  $r = mx_db_fetch_single($db, $stmt);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['患者'];
  $sod->id = $oid;
  $sod->fetch_data($oid); 


$pat = get_patient($r['患者'],false);

$data = array();
  
  $data['reportdate'] = mx_wareki($r['reportdate']);
 	 
 $data['factdate'] = mx_wareki($r['factdate']);
 if ($r['factdate']==null) $data['factdate']='' ;
 $data['facttype'] = $r['facttype'];
 $data['factloc'] = $r['factloc'];
 $data['busho'] = $r['busho'];
 $data['empnm1'] = $r['empnm1'];
$data['empnm2'] = $r['empnm2'];
 $data['empnm3'] = $r['empnm3'];
$data['pnm1'] = $r['pnm1'];
$data['pnm2'] = $r['pnm2'];
$data['pnm3'] = $r['pnm3'];
$data['factcont'] = $r['factcont'];
$data['factdone'] = $r['factdone'];
$data['factplan'] = $r['factplan'];
$data['factdo'] = $r['factdo'];
$data['proof'] = $r['proof'];
$data['kubun'] = $r['kubun'];

$data['PATIENT_ID'] = $pat['患者ID'];
  $data['PATIENT_KANA'] = $pat['フリガナ'];
  $data['PATIENT_KANJI'] = $pat['姓'] .'　'. $pat['名'];
  $data['PATIENT_DOB'] = mx_wareki($pat['生年月日']);
  
  $data['PATIENT_SEX'] = $pat['性別'] == 'M' ? '男' : '女';
// 0920-2011 from DB
  $data['pt_no'] = $r['pt_no'];
$data['pt_nm'] = $r['pt_nm'];
$data['PatientGroup'] = $pat['希望病棟'];
  $data['PatientAge'] = mx_calc_age($pat['生年月日']);
$data['PatientGroup'] = $pat['希望病棟'];
  $data['PatientAge'] = mx_calc_age($pat['生年月日']);
//
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
 

 

  print rx_template($data);
	 

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="printinsident.php?top=1" name="top_frame" scrolling="no">
         <frame src="printinsident.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
