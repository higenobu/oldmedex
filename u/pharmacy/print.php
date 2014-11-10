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
    if($shots)
      $ord = get_pharm_order($oid,1);
    else
      $ord = get_pharm_order($oid);
      
    $doc = get_emp_name($ord['記録者']);
    $injection = $ord['注射'];
    if ($injection || $shots) {
      $meds = get_meds($oid,1);
      $title = "注射処方箋";
    }
    else {
      $meds = get_meds($oid,0);
      $title = "内服、外用処方箋";
    }
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
  $tr = get_teiki_rinji($ord['定期臨時']);
  if ($oid)
    if($_mx_simple_rx_label)
      printf("<pre>\n%s　%s %s %s\n",$pat['姓'],$pat['名'],$pat['患者ID'],
	     $ord['処方年月日']);
    else
      printf("<pre>\n%s　%s %s %s %s%s %s\n",$pat['姓'],$pat['名'],$pat['患者ID'],
	     $ord['処方年月日'],$ord['区分'], $tr, $ord['後発品'] ? '後発品可' : '');
  else
    printf("<pre>\nRP名 %s\t%s　%s\n",$ord['RP名'],$doc['lname'],$doc['fname']);
  print join("\n", set_body($meds, 1, $ord['後発品']));
  print "\n";
  printf ("入力担当者: %s　%s\n<pre>",$doc['lname'],$doc['fname']);
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
