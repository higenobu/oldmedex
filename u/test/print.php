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
         <form><input type="button" value="����" onClick="printPopup()">
         <input type="button" value="���̤��Ĥ���" onClick="window.parent.close()">
         </form>
         </center>';
}
elseif ($bottom) {
  mx_html_head("",false);
  print '<body>';

  if ($bottom == 'order' || $bottom == 'record') {
    $ord = get_test_order($oid,2);
    $doc = get_emp_name($ord['��Ͽ��']);
    $room = get_pat_room($ord['����'],false);
    $pat = get_patient($ord['����'],false);
    $meds = get_tp_tests($oid,0);
    $pte = get_emp_name($ord['������']);
    $stp = get_emp_name($ord['��߰�']);
    $title = "���������";
    print "<center><h1>{$title}</h1><p><p>
         <table border=2>
           <tr><th>�����ID<td>{$oid}<td>
           <tr><th>����<td>{$pat['��']}��{$pat['̾']}
               <th>�¼�<td>{$room['�¼�̾']}
           <tr><th>��Ͽ��<td>{$doc['lname']}��{$doc['fname']}
               <th>����ǯ����<td>{$ord['����ǯ����']}
           <tr><th>����ǯ����<td>{$ord['����ǯ����']}
               <th>������<td>{$pte['lname']}��{$pte['fname']}
           <tr><th>�����<td>{$ord['�����']}
               <th>��߰�<td>{$stp['lname']}��{$stp['fname']}";
  }
  elseif ($bottom == 'test_set') {
    $ord = get_tp("",$oid);
    $doc = get_emp_name($ord['��Ͽ��']);
    $meds = get_tp_tests($oid,1);
    $title = "����SET";

    print "<center><h1>{$title}</h1><p><p>
         <table border=2>
           <tr><th>SETID<td>{$oid}<td>
           <tr><th>��Ͽ��<td>{$doc['lname']}��{$doc['fname']}
               <th>SET̾<td>{$ord['SET̾']}";
  }

  /* ���Ƥ�ɽ�� */
    show_tp_tests($meds);
    print '<tr><td align=center colspan=2>��ǧ��
             <td align=center colspan=2>��ǧ��
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