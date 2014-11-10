<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nutrition/meal-nutri.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;
 
  $DateOfIssue = s($data['DateOfIssue']);
 
  $PatientID = s($data['pt_no']);
  $PatientName = s($data['pt_nm']);
  $PatientKana = s($data['pt_kana']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientGroup = s($data['PatientGroup']);
  $PatientAge = s($data['PatientAge']);
   
  $Kubun = s($data['Kubun']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 
$kk1 = s($data['kk1']);
$ss1 = s($data['ss1']);
$cc1=s( $data['cc1'])  ;

  $template = 'ml.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
//10-28-2014
//
$bottom=1;
//print $top."top".$oid;

class to extends meal_nutri_order_display {
  function draw_body_2() {
  }
}
//print "AAAAAAAAAAAA";

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
  $test_app_types = array('ML');
  $titles = array("ML");
  $ttl = "";
  mx_html_head($ttl,false);
//  print '<center><span class="appname">'."食事せん".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select order_date, patient, dr_order,staple_shape,kk1, ss1,cc1 ,pt_no , pt_nm, pt_kana from meal_order  inner join tbl_patient on  patient=id where "Superseded" is null and "ObjectID"=' . $oid;
  $r = mx_db_fetch_single($db, $stmt);

//print_r($r);

  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r[patient];
  $sod->id = $oid;
//print "oid".$oid;
  $sod->fetch_data($oid);

$pat = get_patient($r[patient],false);
$data = array();
// print_r($pat); 
  $data['DateOfIssue'] = $r['order_date'];
 	 
 
 $data['Kubun'] = $r['mlkubun'];
 $data['memo2'] = $r['memo2'];
 
 $data['kk1'] = $r['kk1'];
$data['ss1'] = $r['ss1'];
 $data['cc1'] = $r['cc1'];

 

$data['pt_no'] = $r['pt_no'];
$data['pt_nm'] = $r['pt_nm'];


$data['PatientAge'] = mx_calc_age($pat['生年月日']);

 $idata = mx_get_install_data();
 $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];

print '
        <input type="button" value="印刷" onClick="printPopup()">
        <input type="button" value="画面を閉じる" onClick="window.parent.close()">';
   
  
  print rx_template($data);
	 

}

else {
//print "else".$oid;

  print '<frameset rows="60, *" noresize border="0">
         <frame src="printmeal.php?top=1" name="top_frame" scrolling="no">
         <frame src="printmeal.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
