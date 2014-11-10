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
         <form><input type="button" value="印刷" onClick="printPopup()">
         <input type="button" value="画面を閉じる" onClick="window.parent.close()">
         </form>
         </center>';
}
elseif ($bottom) {
  mx_html_head("",false);
  print "<body>";
  $ord = get_meal_order($oid);
  $pat = get_patient($ord['患者'],false);
  print "<table>
         <tr><th>ID<td>{$pat['患者ID']}
             <th>性別<td>".($pat['性別'] == "M" ? "男" :
			    ($pat['性別'] == "F" ? "女" : "")).
       "<tr><th>氏名<td>{$pat['姓']}&nbsp;{$pat['名']}
            <th>フリガナ<td>{$pat['フリガナ']}
        <tr><th>身長<td>".get_measure($pat['ObjectID'],"身長").
           "<th>体重<td>".get_measure($pat['ObjectID'],"体重").
       "<tr><th>生年月日<td>{$pat['生年月日']}
        </table>";
  print_meal_detail($ord,$done);
  print '</table>';
  print '<p><b>「医師が食事箋変更を実施した場合、栄養士へ電話連絡
を行う。<br>内線：165　　PHS：170」</b>
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
