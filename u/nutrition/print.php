<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nutrition/common.php';
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
  $ord = get_meal_order($oid);
  $pat = get_patient($ord['����'],false);
  print "<table>
         <tr><th>ID<td>{$pat['����ID']}
             <th>����<td>".($pat['����'] == "M" ? "��" :
			    ($pat['����'] == "F" ? "��" : "")).
       "<tr><th>��̾<td>{$pat['��']}&nbsp;{$pat['̾']}
            <th>�եꥬ��<td>{$pat['�եꥬ��']}
        <tr><th>��Ĺ<td>".get_measure($pat['ObjectID'],"��Ĺ").
           "<th>�ν�<td>".get_measure($pat['ObjectID'],"�ν�").
       "<tr><th>��ǯ����<td>{$pat['��ǯ����']}
        </table>";
  print_meal_detail($ord,$done);
  print '</table>';
  print '<p><b>�ְ�դ�������ѹ���»ܤ�����硢���ܻΤ�����Ϣ��
��Ԥ���<br>������165����PHS��170��</b>
<table border=1 width="80"><tr><td height="80">&nbsp;</table>';
}
else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print.php?top=1" name="top_frame" scrolling="no">
         <frame src="print.php?bottom=1&oid='.$oid.'" name="bottom_frame" >
         </frameset>';
}

if ($top || $bottom) print '</body></html>';

function test_print($v,$k) {
  print "'$k'  => '$v'<br>";
}
?>
