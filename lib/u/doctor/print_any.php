<?php // -*- coding: euc-jp -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';


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
//0707-2011
	$Stop = s($data['Stop']);
  $template = 'inhosp_rx2.html';
  if ($_mx_hack_takamiya)
    $template = 'inhosp_rx.html';
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}

function rx_template1($data) {


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
//0707-2011
	$Stop = s($data['Stop']);
  
	$template1 = 'inhosp_rx3.html';
  
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template1) . "HTML;\n");
  return $html;
}



#----------------------- main ----------------------------
$oid=$_GET['oid'];
$rpid=$_GET['rpid'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
$shots=$_GET['shots'];

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
  mx_html_head("",false);
  print '
<style type="text/css">
	body {
		background-image: none;
		background-color: #fff;
	}
</style>
<body>';
  $title = "";
  if ($oid) {
    
      $ord = get_xct_order($oid);
      
    $doc = get_emp_name($ord['CreatedBy']);
    
    
       
    
  }
   
   
  $room = get_pat_room($ord['患者'],false);
  $pat = get_patient($ord['患者'],false);
  $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  /*
  if ($oid)
    $buff .= sprintf("<pre>\n%s　%s %s %s %s %s\n",$pat['姓'],$pat['名'],$pat['患者ID'],
	   $ord['処方年月日'],$ord['区分'],$ord['後発品'] ? '後発品可' : '');
  else
    $buff .= sprintf("<pre>\nRP名 %s\t%s　%s\n",$ord['RP名'],$doc['lname'],$doc['fname']);
      */
      $buff = "\n";


$buff1=$buff;
$buff2=$buff;


$page=0;
 $pos =strpos($buff1,'##',1);
 



while  ($pos!==false && $page <5)
	{
	
       //max 5000 message
  	$buff2 = substr($buff1,0,$pos);
	$buff1 = substr($buff1,$pos+2,5000);
	$pos =strpos($buff1,'##',1);
	
  $data = array();
  $data['RxBody'] = $buff2;
  $data['DateOfIssue'] = mx_wareki($ord['orderdate']);
  $data['PatientID'] = $pat['患者ID'];
  $data['PatientName'] = $pat['姓'].$pat['名'];
  $data['PatientKana'] = $pat['フリガナ'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['希望病棟'];
  $data['PatientAge'] = mx_calc_age($pat['生年月日']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['xctkubun'];
//0707-2011	
   if ($ord['stop']!=null) 
	$data['Stop'] = $ord['stop']."中止";
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
  if ($page==0){
  print rx_template($data);
	}
	else {
	print rx_template1($data);
	}
  $page++;
  
}

$data = array();
  $data['RxBody'] = $buff1;
  $data['DateOfIssue'] = mx_wareki($ord['orderdate']);
  $data['PatientID'] = $pat['患者ID'];
  $data['PatientName'] = $pat['姓'].$pat['名'];
  $data['PatientKana'] = $pat['フリガナ'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['希望病棟'];
  $data['PatientAge'] = mx_calc_age($pat['生年月日']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['xctkubun'];
//0707-2011	
   if ($ord['stop']!=null) 
	$data['Stop'] = $ord['stop']."中止";
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
  if ($page==0){
  print rx_template($data);
	}
	else {
	print rx_template1($data);
	}
 
	


	
	


}
//bottom
else {
  print '<frameset rows="55, *" noresize border="0">
         <frame src="print_any.php?top=1" name="top_frame" scrolling="no">
         <frame src="print_any.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top) print '</body></html>';
?>
