<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/meal-nutri.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;

  $DateOfIssue = s($data['DateOfIssue']);
$procdate = s($data['procdate']);
  $PatientID = s($data['pt_no']);
  $PatientName = s($data['pt_nm']);
  $PatientKana = s($data['pt_kana']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientGroup = s($data['PatientGroup']);
  $PatientAge = s($data['PatientAge']);
  // $RxBody = s($data['RxBody']);
  $Kubun = s($data['Kubun']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 
$syusyoku = s($data['syusyoku']);
$kinsyoku = s($data['kinsyoku']);
$memo2 = s($data['memo2']);
$sbunryo=s($data['sbunryo'] ) ;
$fukusyoku=s($data['fukusyoku'] ) ;
$fbunryo=s( $data['fbunryo'])  ;
$syokusyu=s( $data['syokusyu'])  ;
//0707-2011
//	$Stop = s($data['Stop']);
  $template = 'ml.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];

class to extends meal_nutri_order_display {
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
         <form><input type="button" value="°õºþ" onClick="printPopup()">
         <input type="button" value="²èÌÌ¤òÊÄ¤¸¤ë" onClick="window.parent.close()">
         </form>
         </center>';
}
elseif ($bottom) {
  $test_app_types = array('ML');
  $titles = array("ML");
  $ttl = "";
  mx_html_head($ttl,false);
// print '<center><span class="appname">'."¿©»ö¤»¤ó".'</span></center>';
//using mlorder 10-29-2014
  $db = mx_db_connect();
$stmt = 'select order_date, patient, dr_order,staple_shape,kk1, ss1,cc1 ,pt_no , pt_nm, pt_kana from meal_order  inner join tbl_patient on  patient=id where "Superseded" is null and "ObjectID"=' . $oid;

/*
  $stmt = 'select orderdate, plandate, procdate, "´µ¼Ô", teikikubun, mlkubun, nutname, nutid, syusyoku  ,sbunryo  ,fukusyoku  ,fbunryo  ,syokusyu  ,memo1  ,kinsyoku  ,memo2  ,memo3  ,proof  ,pt_no , pt_nm, pt_kana from mlorder  inner join tbl_patient on  "´µ¼Ô"=id where "Superseded" is null and "ObjectID"=' . $oid;
*/
 
  $r = mx_db_fetch_single($db, $stmt);
print $r['patient'];
print_r($r);

  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['patient'];
  $sod->id = $oid;
  $sod->fetch_data($oid);

// mx_draw_patientinfo_bmd($r['´µ¼Ô'], array('Culture' => 'Japanese',
//					       'ShowWardPref' => 1));
/*  
$sod->draw();
  print <<<HTML

HTML;
*/
 
//0813-2011
$pat = get_patient($r['patient'],false);
$data = array();
  
  $data['DateOfIssue'] = mx_wareki($r['orderdate']);
 	 
 $data['procdate'] = mx_wareki($r['procdate']);
 if ($r['procdate']==null) $data['procdate']='' ;
 $data['Kubun'] = $r['mlkubun'];
 $data['memo2'] = $r['memo2'];
 $data['syusyoku'] = $r['syusyoku'];
 $data['sbunryo'] = $r['sbunryo'];
$data['fukusyoku'] = $r['fukusyoku'];
 $data['fbunryo'] = $r['fbunryo'];
$data['kinsyoku'] = $r['kinsyoku'];
$data['syokusyu'] = $r['syokusyu'];
$data['DateOfIssue'] = mx_wareki($r['orderdate']);
$data['pt_no'] = $r['pt_no'];
$data['pt_nm'] = $r['pt_nm'];
$data['PatientGroup'] = $pat['´õË¾ÉÂÅï'];
  $data['PatientAge'] = mx_calc_age($pat['À¸Ç¯·îÆü']);

  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
  
  print rx_template($data);
	 

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="printml3.php?top=1" name="top_frame" scrolling="no">
         <frame src="printml3.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
