<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/xctorder2.php';

function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;

  $DateOfIssue = s($data['DateOfIssue']);
  $PatientID = s($data['PatientID']);
  $PatientName = s($data['PatientName']);
  $PatientKana = s($data['PatientKana']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientGroup = s($data['PatientGroup']);
  $PatientAge = s($data['PatientAge']);
  $RxBody = s($data['RxBody']);
  $Kubun = s($data['Kubun']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 $bui1 = s($data['bui1']);
 $bui2 = s($data['bui2']);
 $bui3 = s($data['bui3']);
 $bui4 = s($data['bui4']);
 $bui5 = s($data['bui5']);
$drsyoken = s($data['drsyoken']);
$techsyoken = s($data['techsyoken']);
//0707-2011
	$Stop = s($data['Stop']);
  $template = 'xct.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];

class to extends xctorder_display {
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
  $test_app_types = array('XCT');
  $titles = array("XCT");
  $ttl = "";
  mx_html_head($ttl,false);
  print '<center><span class="appname">'."¾È¼ÍÏ¿".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select orderdate, plandate, 
       procdate, "´µ¼Ô", teikikubun, xctkubun, techname, techid, bui1, 
       bui2, bui3, bui4, bui5, memo1, memo2, memo3, memo4, memo5, memo11, 
       memo21, memo31, memo41, memo51, memo12, memo22, memo32, memo42, 
       memo52, syoken1, syoken2, syoken3, syoken4, syoken5, techsyoken, 
       drsyoken, proof, shiji, gishi, stop from xctorder where "Superseded" is null and "ObjectID"=' . $oid;
  $r = mx_db_fetch_single($db, $stmt);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['´µ¼Ô'];
  $sod->id = $oid;
  $sod->fetch_data($oid);

// mx_draw_patientinfo_bmd($r['´µ¼Ô'], array('Culture' => 'Japanese',
//					       'ShowWardPref' => 1));
/*  
$sod->draw();
  print <<<HTML

HTML;
*/
$ret=array();
$ret=_lib_u_xct_get_bui();
$data = array();
  
  $data['DateOfIssue'] = mx_wareki($r['orderdate']);
 	 if  ($r['xctkubun']=='170027910') $data['Kubun'] = 'XP';
	if  ($r['xctkubun']=='170011810') $data['Kubun'] = 'CT';
	if  ($r['xctkubun']=='170020110') $data['Kubun'] = 'MRI';
 

//  $data['Kubun'] = $r['xctkubun'];
 $data['bui1'] = $ret[$r['bui1']]; 
$data['bui2'] = $ret[$r['bui2']]; 
 $data['bui3'] = $ret[$r['bui3']]; 
$data['bui4'] = $ret[$r['bui4']]; 
 $data['bui5'] = $ret[$r['bui5']]; 
 $data['drsyoken'] = $r['drsyoken'];
 $data['techsyoken'] = $r['techsyoken'];
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
  
  print rx_template($data);
	 

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print12.php?top=1" name="top_frame" scrolling="no">
         <frame src="print12.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
