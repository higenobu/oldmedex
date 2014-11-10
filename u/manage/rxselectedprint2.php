<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';

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
//0929-2011
  $template = 'inhosp_rx3.html';
 // if ($_mx_hack_takamiya)
  //  $template = 'inhosp_rx.html';
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
$top=$_GET['top'];
$bottom=$_GET['bottom'];
$shots=$_GET['shots'];

//*********************************************
 $db = mx_db_connect();

$stmt='SELECT   rx_id FROM rx_temp2  where "Superseded" is null and selected = 1 ';


$rows =  mx_db_fetch_all($db, $stmt);
 
 

 

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
         
         </form>
	<a href="index.php?tab=1" onclick="window.open(this.href);return false;">�ᥤ������</a>	
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
 foreach($rows as $row){
	if ($row['rx_id'] !=0) {
//	print "�� RX-ID��¸�ߤ��Ƥ��ޤ��� ";
	$oid=$row['rx_id'];
				}




  if ($oid) {
    if($shots)
      $ord = get_pharm_order($oid,1);
    else
      $ord = get_pharm_order($oid);
    

 
  
    $doc = get_emp_name($ord['��Ͽ��']);
    $injection = $ord['���'];
    if ($injection || $shots) {
      $meds = get_meds($oid,1);
      $title = "��ͽ����";
    }
    else {
      $meds = get_meds($oid,0);
      $title = "���������ѽ����";
    }
  }
  else {
    $title = "RP";
    $ord = get_rp_order($rpid);
    $doc = get_emp_name($ord['��Ͽ��']);
    $meds = get_meds($rpid,2);
  }
  if ($meds) {
    foreach($meds as $med)
      if ($v = check_drug($med['medis'])) {
	if ($v & 3)
	  if (!$d) {
	    $title = $title.'<br><font color="red">����</font>';
	    $d = true;
	  }
	if ($v & 12)
	  if (!$b) {
	    $title = $title.'<br><font color="orange">�����ʪͳ������</font>';
	    $b = true;
	  }
      }
  }

  $room = get_pat_room($ord['����'],false);
  $pat = get_patient($ord['����'],false);
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  
      $buff = "\n";
  $buff .= join("\n", set_body($meds, 1, $ord['��ȯ��']));

$buff1=$buff;
$buff2=$buff;


$page=0;
 $pos =strpos($buff1,'##',1);
 



while  ($pos!==false && $page <50)
	{
	
       //max 5000 message
  	$buff2 = substr($buff1,0,$pos);
	$buff1 = substr($buff1,$pos+2,500000);
	$pos =strpos($buff1,'##',1);
	
  $data = array();
  $data['RxBody'] = $buff2;
  $data['DateOfIssue'] = mx_wareki($ord['����ǯ����']);
  $data['PatientID'] = $pat['����ID'];
  $data['PatientName'] = $pat['��'].$pat['̾'];
  $data['PatientKana'] = $pat['�եꥬ��'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['��˾����'];
  $data['PatientAge'] = mx_calc_age($pat['��ǯ����']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['��ʬ'];
//0707-2011	
   if ($ord['�����']!=null) 
	$data['Stop'] = $ord['�����']."���";
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
  $data['DateOfIssue'] = mx_wareki($ord['����ǯ����']);
  $data['PatientID'] = $pat['����ID'];
  $data['PatientName'] = $pat['��'].$pat['̾'];
  $data['PatientKana'] = $pat['�եꥬ��'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['��˾����'];
  $data['PatientAge'] = mx_calc_age($pat['��ǯ����']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['��ʬ'];
//0707-2011	
   if ($ord['�����']!=null) 
	$data['Stop'] = $ord['�����']."���";
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
//add 0718-2011



} 
//foreach end


else {
  print '<frameset rows="55, *" noresize border="0">
         <frame src="rxselectedprint2.php?top=1" name="top_frame" scrolling="no">
         <frame src="rxselectedprint2.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
}


if ($top) print '</body></html>';
?>
