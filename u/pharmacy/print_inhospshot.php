<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/ord_common.php';
function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}
    
function rx_template($data) {
  global $_mx_hack_takamiya;
//$Yakuzai=s($data['Yakuzai']);
	$PatientSex=s($data['PatientSex']);
	$PatientDOB=s($data['PatientDOB']);
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
//$Yakuzai=s($data['Yakuzai']);
$PatientSex=s($data['PatientSex']);
$PatientDOB=s($data['PatientDOB']);
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
  //0916-2011
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
     
      $ord = get_shot_order($oid);
     
      $meds = get_meds_shot($oid);
      $title = "注射処方箋";
    $doc = get_emp_name($ord['記録者']);
     
  }
  else {
    $title = "RP";
    $ord = get_rp_order($rpid);
    $doc = get_emp_name($ord['記録者']);
    $meds = get_meds($rpid,2);
  }
  if ($meds) {
    foreach($meds as $med)
      if ($v = check_drug($med['medis'])) {
	if ($v & 3)
	  if (!$d) {
	    $title = $title.'<br><font color="red">麻毒</font>';
	    $d = true;
	  }
	if ($v & 12)
	  if (!$b) {
	    $title = $title.'<br><font color="orange">血液生物由来製品</font>';
	    $b = true;
	  }
      }
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


//10-30-2014 added to karte
//1 is yakuzai,2 is shots?
/*
$a = set_body($meds,$shots, $ord["後発品"], 1);

//  for($i=0; $i < count($a); $i++)
//    $params[sprintf("LINE%04d", $i)] = $a[$i];

$b="";
for($i=0; $i < count($a); $i++){
   $b =$b."\n". $a[$i];
//print "<br>". $a[$i]."\n";

}

$con = mx_db_connect();
if ($shots) $otype="SHOTS";
 else $otype="RX";
//$otype="rx";
$odate=$ord['処方年月日'];
$ptid=$ord['患者'];

$ocont="--------------------".$otype."\n".$b;
$stmt10 = <<<SQL
SELECT "ID" FROM orderinfo  where oid=$oid and patient=$ptid and ordertype='$otype' limit 1
SQL;
// print $stmt10;

$rs0 = mx_db_fetch_single($con, $stmt10);
//print_r($rs0);
if ($rs0 == null){




 $stmt1 = <<<SQL
INSERT INTO orderinfo(
            orderdate, patient, 
            ordertype, "content",oid)
    
    VALUES ('$odate','$ptid', '$otype', '$ocont',$oid)
        
SQL;
//print $stmt1;
if (pg_query($con, $stmt1)){
//print $stmt1;$meds = get_meds($oid,1);
      $title = "注射処方箋";
}
else {
print '<p > DB access error</p>';
die;
}
}
else {

 $stmt1 = <<<SQL
update  orderinfo set "content"='$ocont',"update"=1
		 
             where oid=$oid and patient=$ptid and ordertype='$otype' 
    
  
        
SQL;
//print $stmt1;
if (pg_query($con, $stmt1)){

}
else {
print '<p > DB access error</p>';
die;
}



} //end of update

//
//end of inrsert to karte
*/

      $buff = "\n";
//10-30-2014 change 1 to $shots
  $buff .= join("\n", set_body($meds, $shots, $ord['後発品']));

$buff1=$buff;
$buff2=$buff;


$page=0;
 $pos =strpos($buff1,'##',1);
 



while  ($pos!==false && $page <11)
	{
	
       //max 5000 message
  	$buff2 = substr($buff1,0,$pos);
	$buff1 = substr($buff1,$pos+2,50000);
	$pos =strpos($buff1,'##',1);
	
  $data = array();
//10-30-2014
//$data['Yakuzai']="AAAA";
  $data['RxBody'] = $buff2;
  $data['DateOfIssue'] = mx_wareki($ord['処方年月日']);
  $data['PatientID'] = $pat['患者ID'];
  $data['PatientName'] = $pat['姓'].$pat['名'];
  $data['PatientKana'] = $pat['フリガナ'];
//0916-2011
$data['PatientDOB'] = mx_wareki($pat['生年月日']);
$data['PatientSex'] = $pat['性別'] == 'M' ? '男' : '女';
//
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['希望病棟'];
  $data['PatientAge'] = mx_calc_age($pat['生年月日']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['区分'];
//0707-2011	
   if ($ord['停止日']!=null) 
	$data['Stop'] = $ord['停止日']."中止";
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
//$data['Yakuzai']="AAAAAAAA";
  $data['DateOfIssue'] = mx_wareki($ord['処方年月日']);
  $data['PatientID'] = $pat['患者ID'];
  $data['PatientName'] = $pat['姓'].$pat['名'];
  $data['PatientKana'] = $pat['フリガナ'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['希望病棟'];
  $data['PatientAge'] = mx_calc_age($pat['生年月日']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['区分'];
//0916-2011
$data['PatientDOB'] = mx_wareki($pat['生年月日']);
$data['PatientSex'] = $pat['性別'] == 'M' ? '男' : '女';
//
//0707-2011	
   if ($ord['停止日']!=null) 
	$data['Stop'] = $ord['停止日']."中止";
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
         <frame src="print_inhosp.php?top=1" name="top_frame" scrolling="no">
         <frame src="print_inhosp.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top) print '</body></html>';
?>
