<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/rehabdr/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
$oid=$_GET['oid'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
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
  print "<body>";
  $ord = get_rehab(false,$oid);
  $pat = get_patient($ord['����'],false);
  print '<input type="hidden" name="oid" value="'.$oid.'">';
  print '<table border=2><tr><td align=center>��ǧ<td><tr><td height=80 width=80>&nbsp;</table>';
  print "<table border=1><tr><th>����ID<td>{$oid}
                <th>����̾<td>{$pat['��']}��{$pat['̾']}<tr>";
  print_detail($ord);
  print '</table>';
}
else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print.php?top=1" name="top_frame" scrolling="no">
         <frame src="print.php?bottom=1&oid='.$oid.'" name="bottom_frame" >
         </frameset>';
}

if ($top || $bottom) print '</body></html>';
?>

