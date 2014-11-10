<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
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
  $tr = get_teiki_rinji($ord['����׻�']);
  if ($oid)
    if($_mx_simple_rx_label)
      printf("<pre>\n%s��%s %s %s\n",$pat['��'],$pat['̾'],$pat['����ID'],
	     $ord['����ǯ����']);
    else
      printf("<pre>\n%s��%s %s %s %s%s %s\n",$pat['��'],$pat['̾'],$pat['����ID'],
	     $ord['����ǯ����'],$ord['��ʬ'], $tr, $ord['��ȯ��'] ? '��ȯ�ʲ�' : '');
  else
    printf("<pre>\nRP̾ %s\t%s��%s\n",$ord['RP̾'],$doc['lname'],$doc['fname']);
  print join("\n", set_body($meds, 1, $ord['��ȯ��']));
  print "\n";
  printf ("����ô����: %s��%s\n<pre>",$doc['lname'],$doc['fname']);
}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print.php?top=1" name="top_frame" scrolling="no">
         <frame src="print.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
