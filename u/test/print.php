<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/common.php';
$oid=$_GET['oid'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
$ref =  ereg_replace('.*/(.*).php','\\1',$_SERVER['HTTP_REFERER']);

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
  print '<body>';

  if ($bottom == 'order' || $bottom == 'record') {
    $ord = get_test_order($oid,2);
    $doc = get_emp_name($ord['記録者']);
    $room = get_pat_room($ord['患者'],false);
    $pat = get_patient($ord['患者'],false);
    $meds = get_tp_tests($oid,0);
    $pte = get_emp_name($ord['検査師']);
    $stp = get_emp_name($ord['停止医']);
    $title = "検査処方箋";
    print "<center><h1>{$title}</h1><p><p>
         <table border=2>
           <tr><th>処方箋ID<td>{$oid}<td>
           <tr><th>患者<td>{$pat['姓']}　{$pat['名']}
               <th>病室<td>{$room['病室名']}
           <tr><th>記録者<td>{$doc['lname']}　{$doc['fname']}
               <th>処方年月日<td>{$ord['処方年月日']}
           <tr><th>検査年月日<td>{$ord['検査年月日']}
               <th>検査師<td>{$pte['lname']}　{$pte['fname']}
           <tr><th>停止日<td>{$ord['停止日']}
               <th>停止医<td>{$stp['lname']}　{$stp['fname']}";
  }
  elseif ($bottom == 'test_set') {
    $ord = get_tp("",$oid);
    $doc = get_emp_name($ord['記録者']);
    $meds = get_tp_tests($oid,1);
    $title = "検査SET";

    print "<center><h1>{$title}</h1><p><p>
         <table border=2>
           <tr><th>SETID<td>{$oid}<td>
           <tr><th>記録者<td>{$doc['lname']}　{$doc['fname']}
               <th>SET名<td>{$ord['SET名']}";
  }

  /* 内容の表示 */
    show_tp_tests($meds);
    print '<tr><td align=center colspan=2>確認印
             <td align=center colspan=2>確認印
         <tr><td height=80 width=80 colspan=2>&nbsp;
             <td height=80 width=80 colspan=2>&nbsp;
          </table></center>';

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print.php?top=1" name="top_frame" scrolling="no">
         <frame src="print.php?bottom='.$ref.'&oid='.$oid.'" name="bottom_frame" >
         </frameset>';
}

if ($top || $bottom) print '</body></html>';
?>