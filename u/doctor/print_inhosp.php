<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/print.php';

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

  $template = 'inhosp_rx2.html';
  if ($_mx_hack_takamiya)
    $template = 'inhosp_rx.html';
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");
  return $html;
}


#---------------------------




<form action="print_inhosp.php" method="POST">
      <table border="0"
             summary="xct ">
        </** @noinspection PhpExpressionResultUnusedInspection *//** @noinspection PhpExpressionResultUnusedInspection */tr>
          <td>DB name</td>
          <td><input type="text" name="objectid" value="1"></td>
        </tr>
        <tr>
          <td>Table name</td>
          <td><input type="text" name="table" value="tbl_yakuzaiv"></td>
        </tr>
	<tr>
          <td>pre date</td>
          <td><input type="text" name="plusdate" value="0"></td>
        </tr>
	<tr>
          <td>post date</td>
          <td><input type="text" name="plusdate2" value="0"></td>
        </tr>
	<tr>
          <td>in out(I,O) </td>
          <td><input type="text" name="inout" value="I"></td>
        </tr>
	<tr>
          <td>room </td>
          <td><input type="text" name="room" value="0"></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
          </td>
        </tr>
      </table>

    </form>

#----------------------- main ----------------------------http://localhost:8888/au/3iekilzt7j977:eb1e57/u/everybody/index-pt.php
$oid=$_GET['oid'];
$rpid=$_GET['rpid'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
$shots=$_GET['shots'];

$oid = $_POST['objectid'];

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
  mx_html_head("",false);
  print '
<style type="text/css">
	body {
		background-image: none;http://localhost:8888/au/3iekilzt7j977:eb1e57/u/everybody/index-pt.php
		background-color: #fff;http://localhost:8888/au/3iekilzt7j977:eb1e57/u/everybody/index-pt.php
	}
</style>
<body>';
  $title = "";
  if ($oid) {
   
      $ord = get_pharm_order($oid);
      
    $doc = get_emp_name($ord['��Ͽ��']);
    
    
      $meds = get_meds($oid,0);
      $title = "IMAGE";
}

  
  $room = get_pat_room($ord['����'],false);
  $pat = get_patient($ord['����'],false);
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  /*
  if ($oid)
    $buff .= sprintf("<pre>\n%s��%s %s %s %s %s\n",$pat['��'],$pat['̾'],$pat['����ID'],
	   $ord['����ǯ����'],$ord['��ʬ'],$ord['��ȯ��'] ? '��ȯ�ʲ�' : '');
  else
    $buff .= sprintf("<pre>\nRP̾ %s\t%s��%s\n",$ord['RP̾'],$doc['lname'],$doc['fname']);
      */
      $buff = "\n";
  
  $buff .= "\n";
#  $buff .= sprintf("%s��%s\n",$doc['lname'],$doc['fname']);
  $data = array();
  $data['RxBody'] = $buff;
  $data['DateOfIssue'] = mx_wareki($ord['orderdate']);
  $data['PatientID'] = $pat['����ID'];
  $data['PatientName'] = $pat['��'].$pat['̾'];
  $data['PatientKana'] = $pat['�եꥬ��'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['��˾����'];
  $data['PatientAge'] = mx_calc_age($pat['��ǯ����']);
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $data['Kubun'] = $ord['plandate'];
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
  
  print rx_template($data);
}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print_inhosp.php?top=1" name="top_frame" scrolling="no">
         <frame src="print_inhosp.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top) print '</body></html>';
?>
