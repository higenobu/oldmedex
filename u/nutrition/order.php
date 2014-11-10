<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nutrition/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

$_REQUEST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';
$action = $_REQUEST['new'] ? "new" : ($_REQUEST['copy'] ? "copy" : 
          ($_REQUEST['update'] ? "update" : $_REQUEST['action']));  
$dbaction = $_REQUEST['dbaction'];
$oid = $_REQUEST['update'] ? $_REQUEST['update'] : 
	($_REQUEST['copy'] ? $_REQUEST['copy'] : $_REQUEST['oid']);
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];

print '<table border="0"><tr><td valign="top"  width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo();
print '<td valign="top" align="left">';

function show_static_order($pat,$var) {
  global $action;

  if ($var['dbaction'] == "������Ͽ") {
    if (!insert_meal_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }
  elseif ($var['dbaction'] == "����") {
    $var['i���ܻε�Ͽ'] = "";
    if (!update_meal_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  $pid = $pat['ObjectID'] ? $pat['ObjectID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  $patn = $pat['�եꥬ��'] ? $pat['�եꥬ��'] : $var['patn'];
  $patb = $pat['��ǯ����'] ? $pat['��ǯ����'] : $var['patb'];
  $pats = $pat['����'] ? ($pat['����'] == 'M' ? "��" : 
			($pat['����'] == 'F' ? "��" : ""))
			: $var['pats'];
  $weight = $var['weight'] ?  $var['weight'] : get_measure($pid,"�ν�");
  $height = $var['height'] ?  $var['height'] : get_measure($pid,"��Ĺ");
  print "<table><tr><th nowrap>����̾<td>$patl $patf
                    <th nowrap>�եꥬ��<td>$patn
                <tr><th nowrap>��ǯ����<td>$patb
                    <th>����<td>$pats
                <tr><th>��Ĺ<td>$height
                    <th>�ν�<td>$weight".
        '<input type="hidden" name="pid" value="'.$pid.'">
         <input type="hidden" name="patl" value="'.$patl.'">
         <input type="hidden" name="patf" value="'.$patf.'">
         <input type="hidden" name="patb" value="'.$patb.'">
         <input type="hidden" name="patn" value="'.$patn.'">
         <input type="hidden" name="pats" value="'.$pats.'">
         <input type="hidden" name="weight" value="'.$weight.'">
         <input type="hidden" name="height" value="'.$height.'">
         <input type="hidden" name="weight" value="'.$weight.'">
         </table><button type="submit" name="new" value="1">
         ���������</button>';
  if ($hists = get_meal_history($pid)) {
    print "<table><tr><th nowrap>�����ID<th nowrap>����ǯ����<td>\n";
    foreach ($hists as $hist) {
      print "<tr><td nowrap>\n";
      $oid = $hist['ObjectID'];
      print '<button type="submit" name="detail" value="' . $oid . 
             "\">�����ID{$oid}</button>" .
	"<td nowrap>{$hist['������']}<td>\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail ($var) {

  $oid= $var['detail'] ? $var['detail'] : 
    ($var['copy'] ? $var['copy'] : 
     ($var['update'] ? $var['update'] : $_REQUEST['oid']));

  if ($oid) {
    $ord = get_meal_order($oid);
    print '<input type="hidden" name="oid" value="'.$oid.'">';
    print_meal_detail($ord,$done);
    print '<tr>
       <td colspan=4><button type="submit" name="copy" value="'.$oid.'">���ԡ�</button>
       <button type="submit" name="update" value="'.$oid.'">����</button>
       <button type="button"'. 
       "OnClick=\"window.open('print.php?oid={$oid}','',
          'width=640,height=640')\">���ν����ΰ������̤򳫤�</button>
        <tr><td colspan=4>";
    get_order_history("�����",$oid,"meal");
    print "</table>\n";
  }
}

function show_edit_order($var) {
  global $__mx_formi_dek, $action, $auth, $ins, $oid, $ord_array;

  $pid = $var['pid'];
  if ($pid && $var["dbaction"] != "������Ͽ") {
    if ($oid && ($var['update'] || $var['copy'] || $var['move']))
      $ord = get_meal_order($oid);
    elseif (!$var['new'])
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    print '<input type="hidden" name="action" value="'.$action.'">';
    print '<input type="hidden" name="i����" value="'.$pid.'">';
    print '<table border="1">';
    if ($action == "update") 
      print "<tr><th>�����ID<td align=center>{$oid}<td><td>";
    $name = get_emp_name($auth[2]['ObjectID']);
    $today = date("Y-m-d H:i:s");
    $day = ereg_replace(" .*$","",$today);
    $time = ereg_replace(".* ","",$today);

    print "<tr><th>��Ͽ��<th>{$name['lname']}&nbsp;{$name['fname']}\n".
      '<input type="hidden" name="i��Ͽ��" value="'.$auth[2]['ObjectID'].'">
       <th>��Ͽ����<td align=center nowrap>'.disp_day_time($day,$time).
      '<input type="hidden" name="i��Ͽ��" value="'.$day.'">
       <input type="hidden" name="i��Ͽ����" value="'.$time.'">
       <tr><th>������<td>'.
      print_input("������",10, 
		  ($ord["������"] ? $ord["������"] : date("Y-m-d"))).
      "<th>�Ƴ���<td>".
      print_input("�Ƴ���",10, 
		  ($ord["�Ƴ���"] ? $ord["�Ƴ���"] : "")).
      '<tr><th>��ʬ<td>';
    print_select("��ʬ","��ʬ",$ord["��ʬ"],false);
    print '<th>�¹���<td>'.
      print_input("�¹���",10,
		  ($ord["�¹���"] ? $ord["�¹���"] : date("Y-m-d"))).
      '<tr><th>�¹Ի�<td>';
    print_select("�¹Ի�","�¹Ի�",$ord["�¹Ի�"],false); 
    print '<th>����<td>';
    print_select("����","����",$ord["����"],true);
    print '<tr><th>Ŭ������<td>';
    if (check_key($ord["����"],$ins))
      print_select("Ŭ������2","Ŭ������",$ord["Ŭ������"],false);
    else
      print_select("Ŭ������","Ŭ������",$ord["Ŭ������"],false);
    print '<td><td><tr><td><th>����̾<th>�翩<th>����
           <tr><th>ī<td>';
    print_select("����","ī����̾",
		 ($ord['����']=='�俩'?"�俩":$ord["ī����̾"]),false);
    print "<td>";
    print_select("�翩","ī�翩",$ord["ī�翩"],false);
    print "<td>";
    print_select("����","ī����",$ord["ī����"],false);
    print
      '<tr><th>��<td>';
    print_select("����","�뿩��̾",
		 ($ord['����']=='�俩'?"�俩":$ord["�뿩��̾"]),false);
    print "<td>";
    print_select("�翩","��翩",$ord["��翩"],false);
    print "<td>";
    print_select("����","������",$ord["������"],false);
    print '<tr><th>ͼ<td>';
    print_select("����","ͼ����̾",
		 ($ord['����']=='�俩'?"�俩":$ord["ͼ����̾"]),false);
    print "<td>";
    print_select("�翩","ͼ�翩",$ord["ͼ�翩"],false);
    print "<td>";
    print_select("����","ͼ����",$ord["ͼ����"],false);
    print  '<tr><th>�����<td colspan="3">'.
      print_input("�����",60,$ord['�����']).'<tr><th>����륮���γ�ǧ<td>';
    print_select("����륮���γ�ǧ","����륮���γ�ǧ",$ord["����륮���γ�ǧ"],false);
    print '<td><td><tr><th>����륮��<br>����¾<td colspan="3">'.
      print_input("����륮���γ�ǧ����¾",60,$ord['����륮���γ�ǧ����¾']).
      '<tr><th>ǻ��ήư��Ǯ��<td>'.
      print_input("ǻ��ήư��Ǯ��",4,$ord['ǻ��ήư��Ǯ��']).'kcal';
    if (check_key($ord["����"],$ins)) {
      print "<th>�ݸ�<td>";
      print_select("�ݸ�","�ݸ�",$ord["�ݸ�"],false);
    } else 
      print "<td><td>";
    print '<tr><th colspan="4" height="50" valign="bottom" align="left">��������
           <tr><td><th>����<th>��<th>��ʬ<br>�ֿ���
           <tr><td nowrap align="center">';
    print_select("��","��0",$ord["��0"],false);
    print '��<td align="center">';
    print_select("����","����0",$ord["����0"],false);
    print '<td align="center">'.print_input("��0",4,$ord['��0']).
      'ml<td align="center">'.
      print_input("�ֿ���0",4,$ord['�ֿ���0']).'ml
          <tr><td align="center">';
    print_select("��","��1",$ord["��1"],false);
    print '��<td align="center">';
    print_select("����","����1",$ord["����1"],false);
    print '<td align="center">'.print_input("��1",4,$ord['��1']).
      'ml<td align="center">'.
      print_input("�ֿ���1",4,$ord['�ֿ���1']).'ml
          <tr><td align="center">';
    print_select("��","��2",$ord["��2"],false);
    print '��<td align="center">';
    print_select("����","����2",$ord["����2"],false);
    print '<td align="center">'.print_input("��2",4,$ord['��2']).
      'ml<td align="center">'.
      print_input("�ֿ���2",4,$ord['�ֿ���2']).'ml
          <tr><td align="center">';
    print_select("��","��3",$ord["��3"],false);
    print '��<td align="center">';
    print_select("����","����3",$ord["����3"],false);
    print '<td align="center">'.print_input("��3",4,$ord['��3']).
      'ml<td align="center">'.
      print_input("�ֿ���3",4,$ord['�ֿ���3']).'ml
          <tr><td align="center">';
    print_select("��","��4",$ord["��4"],false);
    print '��<td align="center">';
    print_select("����","����4",$ord["����4"],false);
    print '<td align="center">'.print_input("��4",4,$ord['��4']).
      'ml<td align="center">'.
      print_input("�ֿ���4",4,$ord['�ֿ���4']).'ml
          <tr><th>����®��<td>';
    print_select("®��","®��",$ord["®��"],false);
    print '<th>®�٤���¾<td>'.print_input("®�٤���¾",4,$ord['®�٤���¾']);
    print '<tr><th>���̻ؼ�
               <td colspan="3">'.print_input("���̻ؼ�",60,$ord['���̻ؼ�']);
    if ($action == "new" || $action == "copy") $label = "������Ͽ";
    else $label = "����";
    print '</table>
      <button type="submit" name="dbaction" value="'.$label.'">'
      .$label."</button>\n";
  }
}

function show_warning () {
  print "<p>�����ʤȿ��ʤ���ߺ���<br>
             <ul><li>��ե�����ǼƦ�ʺ��Ѹ����
                 <li>�����顼�ȥ��ץ��룵���ȥ��졼�ץե롼�ĥ��塼���ʺ���������
                 <li>�˥른�顼�Ⱦ������ȥ��졼�ץե롼�ĥ��塼���ʺ���������
                 <li>�ڥ른�ԥ���������ȥ��졼�ץե롼�ĥ��塼���ʺ���������
                 <li>�ߥΥޥ����󥫥ץ���100���ȥ��륷����ʵ����ʤɡˡʺ��Ѹ����</ul>";
}


if (!$action && !$pid && !($pat = get_pat(""))) {
  print '</table>';
  return;
} else {
  $pid = $pat ? $pat['ObjectID'] :$pid;
  $stmt = ('SELECT "����ID" FROM "������Ģ" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
  $d = mx_db_fetch_single(mx_db_connect(), $stmt);
  $pt_hid = $d['����ID'];
  
  mx_draw_patientinfo_brief($pid);
  mx_draw_ppa_applist($pt_hid);
  print '</td></tr></table>';
  print '<hr />';

  print '<form method="post" action="'.$uri.'">';
  print '<table width="100%" style="border-collapse: collapse; border: hidden">
         <tr><td valign="top" width="50%" style="border-right: solid">'."\n";
  show_static_order($pat,$_REQUEST);
  print "<hr>";
  show_static_detail($_REQUEST);
  show_warning();
  print "\n<td valign=\"top\" width=\"50%\">\n";
  show_edit_order($_REQUEST);
  print "</table></form>\n";
}
?>
</body></html>
