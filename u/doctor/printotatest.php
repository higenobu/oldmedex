<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/otatest_order.php';

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
  $RxBody = s($data['RxBody']);
  $Kubun = s($data['Kubun']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 $bui1 = s($data['kk0']);
 $bui2 = s($data['kk1']);
 $bui3 = s($data['kk2']);
 $bui4 = s($data['kk3']);
 $bui5 = s($data['kk4']);
$syoken1=s($data['ss0']);
$syoken2=s($data['ss1']);
$syoken3=s($data['ss2']);
$syoken4=s($data['ss3']);
$syoken5=s($data['ss4']);

//$drsyoken = s($data['drsyoken']);
//$techsyoken = s($data['techsyoken']);
//0707-2011
//	$Stop = s($data['Stop']);
  $template = 'otatest.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];

class to extends otatest_order_display {
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
  $test_app_types = array('OTATEST');
  $titles = array("OTATEST");
  $ttl = "";
  mx_html_head($ttl,false);
  print '<center><span class="appname">'."OTA".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select order_date, 
       "patient", kk0, kk1,kk2,kk3,kk4,ss0,ss1,ss2,ss3,ss4,
        pt_no , pt_nm, pt_kana from otatest_order  inner join tbl_patient on  "patient"=id
       where "Superseded" is null and "ObjectID"=' . $oid;
  $r = mx_db_fetch_single($db, $stmt);
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
print $r['patient']."AAAAAA";
$pat = get_patient($r['patient'],false);
$data = array();
  
  $data['DateOfIssue'] = mx_wareki($r['order_date']);
 


 $data['kk0'] =  $r['kk0'] ; 
$data['kk1'] =  $r['kk1'] ; 
$data['kk2'] =  $r['kk2'] ; 
$data['kk3'] =  $r['kk3'] ; 
$data['kk4'] =  $r['kk4'] ; 
 $data['ss0'] =  $r['ss0'] ; 
$data['ss1'] =  $r['ss1'] ; 
$data['ss2'] =  $r['ss2'] ; 
$data['ss3'] =  $r['ss3'] ; 
$data['ss4'] =  $r['ss4'] ; 
// $data['drsyoken'] = $r['drsyoken'];
$data['DateOfIssue'] = mx_wareki($r['order_date']);
$data['pt_no'] = $r['pt_no'];
$data['pt_nm'] = $r['pt_nm'];
$data['pt_kana'] = $r['pt_kana'];
$data['PatientGroup'] = $pat['´õË¾ÉÂÅï'];
  $data['PatientAge'] = mx_calc_age($pat['À¸Ç¯·îÆü']);
// $data['techsyoken'] = $r['techsyoken'];
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];


  print rx_template($data);
	 

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="printotatest.php?top=1" name="top_frame" scrolling="no">
         <frame src="printotatest.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
