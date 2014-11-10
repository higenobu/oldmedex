<?php
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
  $DateOfIssue = s($data['DateOfIssue']);
  $PatientID = s($data['PatientID']);
  $PatientName = s($data['PatientName']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientGroup = s($data['PatientGroup']);
  $PatientAge = s($data['PatientAge']);
  $RxBody = s($data['RxBody']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  #$date_range = '2008ǯ7��1����7��14���ޤ�';
  $date_range = '';
  $html = <<<HTML
<HTML>
  <HEAD>
    <META http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
    <META http-equiv="Content-Style-Type" content="text/css">
    <STYLE>
      <!-- 
	   BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-size:x-small ;}
	-->
    </STYLE>
    <TITLE>�ꡡ�����ɡ�������</TITLE>	
  </HEAD>
  <BODY text="#000000">
    <TABLE frame="VOID" cellspacing="0" rules="NONE" border="1">
      <TBODY>
	<TR>
	  <TD colspan="6" height="33" align="CENTER" valign="MIDDLE"><B><FONT SIZE=5>�ꡡ�����ɡ�������</FONT></B></TD>
	</TR>
	<TR>
	  <TD style="border-top: 2pt solid #000000; border-bottom: 1pt solid #000000; border-left: 2pt solid #000000;" colspan="2" align="right" valign="MIDDLE" height="60"  width="100"><FONT COLOR="#0000FF">${DateOfIssue}</FONT></TD>
	  <TD style="border-top: 2pt solid #000000; border-bottom: 1pt solid #000000; border-right: 1pt solid #000000" align="LEFT" valign="MIDDLE" height="60"  width="60">ȯ��</TD>
	  <TD style="border-top: 2pt solid #000000; border-bottom: 1pt solid #000000; border-right: 1pt solid #000000;" align="LEFT" valign="MIDDLE" height="60"  width="100">ID:${PatientID}</TD>
	  <TD style="border-top: 2pt solid #000000; border-bottom: 1pt solid #000000; " valign="MIDDLE" align="center" height="60"  width="60">���ܼ�</TD>
	  <TD style="border-top: 2pt solid #000000; border-bottom: 1pt solid #000000; border-right: 2pt solid #000000" align="LEFT" valign="MIDDLE" height="60" width="60"><FONT color="#0000FF">${EnteredBy}</FONT></TD>
	</TR>
	<TR>
	  <TD style="border-left: 2pt solid #000000; border-right: 1pt solid #000000;" align="CENTER" valign="MIDDLE" height="30"><FONT COLOR="#0000FF">${PatientGroup}</FONT></TD>
	  <TD align="center" valign="MIDDLE" height="30">����̾</TD>
	  <TD style="border-right: 1pt solid #000000;" colspan="2" align="LEFT" valign="MIDDLE" height="30">${PatientName}</TD>
	  <TD align="right" valign="MIDDLE" width="60" height="30"><FONT color="#0000FF">${PatientAge}</FONT></TD>
	  <TD style="border-right: 2pt solid #000000;" align="LEFT" valign="MIDDLE" height="30">��</TD>
	</TR>
	<TR>
	  <TD style="border-top: 1pt solid #000000; border-bottom: 1pt solid #000000; border-left: 2pt solid #000000; border-right: 1pt solid #000000" colspan="4" rowspan="5" align="LEFT" valign="TOP"><FONT COLOR="#0000FF">
	      <PRE>
		${RxBody}
	      </PRE>
	  <TD style="border-bottom: 1pt dotted #000000; border-top: 1pt solid #000000; border-right: 2pt solid #000000" colspan="3" align="CENTER" valign="MIDDLE" height="20">��աʻؼ��ԡ�</TD>
	</TR>
	<TR>
	  <TD style="border-bottom: 1pt solid #000000; border-right: 2pt solid #000000" colspan="3" align="CENTER" valign="MIDDLE" height="30"><FONT COLOR="#0000FF">${DrName}</FONT></TD>
	</TR>
	<TR>
	  <TD style="border-right: 2pt solid #000000" colspan="3" align="CENTER" valign="MIDDLE" height="230"><FONT COLOR="#0000FF">������</FONT></TD>
	</TR>
	<TR>
	  <TD style="border-top: 1pt solid #000000; border-bottom: 1pt dotted #000000; border-right: 2pt solid #000000" colspan="3" align="CENTER" valign="MIDDLE" height="20">Ĵ�޼�</TD>
	</TR>
	<TR>
	  <TD style="border-bottom: 1pt solid #000000; border-right: 2pt solid #000000" colspan="3" align="LEFT" valign="MIDDLE" height="90">&nbsp;</TD>
	</TR>
																		       
	<TR>
																		       <TD style="border-bottom: 2pt solid #000000; border-left: 2pt solid #000000; border-right: 2pt solid #000000;" colspan="7" align="LEFT" valign="TOP" height="120">���������齪λ���ޤ����֤�ɬ����ǧ���Ƶ��ܤ���<br>${date_range}</TD>
	</TR>
	<TR>
	  <TD colspan="7" align="CENTER" valign="MIDDLE" height="20">${HospitalName}</TD>
	</TR>
      </TBODY>
    </TABLE>
  </BODY>
</HTML>
HTML;
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
		background-image: none;
		background-color: #fff;
	}
</style>
<body>';
  $title = "";
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
  /*
  if ($oid)
    $buff .= sprintf("<pre>\n%s��%s %s %s %s %s\n",$pat['��'],$pat['̾'],$pat['����ID'],
	   $ord['����ǯ����'],$ord['��ʬ'],$ord['��ȯ��'] ? '��ȯ�ʲ�' : '');
  else
    $buff .= sprintf("<pre>\nRP̾ %s\t%s��%s\n",$ord['RP̾'],$doc['lname'],$doc['fname']);
      */
      $buff = "\n";
  $buff .= join("\n", set_body($meds, 1, $ord['��ȯ��']));
  $buff .= "\n";
  $buff .= sprintf("%s��%s\n",$doc['lname'],$doc['fname']);
  $data = array();
  $data['RxBody'] = $buff;
  $data['DateOfIssue'] = mx_wareki($ord['����ǯ����']);
  $data['PatientID'] = $pat['����ID'];
  $data['PatientName'] = $pat['��'].$pat['̾'];
  $data['EnteredBy'] = $doc['lname'].$doc['fname'];
  $data['PatientGroup'] = $pat['��˾����'];
  $data['PatientAge'] = mx_calc_age($pat['��ǯ����']);
  $data['RxBody'] = $buff;
  $data['DrName'] = $doc['lname'].$doc['fname'];
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  
  print rx_template($data);
}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print_inhosp_control.php?top=1" name="top_frame" scrolling="no">
         <frame src="print_inhosp_control.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top) print '</body></html>';
?>
