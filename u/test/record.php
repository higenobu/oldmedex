<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/testpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';

$action = $_POST['new'] ? "new" : ($_POST['copy'] ? "copy" :
          ($_POST['update'] ? "update" : $_POST['action']));

$dbaction = $_POST['dbaction'];
$oid = $_POST['update'] ? $_POST['update'] : 
	($_POST['copy'] ? $_POST['copy']  : $_REQUEST['oid']);
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];

print '<table border="0"><tr><td valign="top"  width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo();
print '<td valign="top" align="left">';

function show_static_order($pat,$var) {
  global $auth;
  if ($var['dbaction'] == "dbupdate") {
    if (!update_test_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  print "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n";

  if ($pid) {
    if (!($hists = get_test_order($pid,0))) return;
    print "<table><tr><td><th>����ǯ����<th>����ǯ����<th>�����\n";
    $hnum = count($hists);
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['oid'];
      print '<button type="submit" name="detail" value="'.$oid.'">�����ID'.$oid."</button>";
      if ($hist['�����']) 
	print '<td><font color="red">'.$hist['����ǯ����'].
	  '</font><td><font color="red">'.$hist['����ǯ����'].
	  '</font><td><font color="red">'.$hist['�����']."</font>\n";
      else 
	print "<td>{$hist['����ǯ����']}<td>{$hist['����ǯ����']}
               <td>{$hist['�����']}\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail ($var) {
  $oid = $var['detail'] ? $var['detail'] : $var['det-in'];

  if ($oid) {
    $ord = get_test_order($oid,1);
    $meds = get_tp_tests($oid,0);
    print "<input type=hidden name=\"det-in\" value={$oid}>
           <table><tr><th>�����ID<td>{$oid}<td><td>";
    $name = get_emp_name($ord['��Ͽ��']);
    print "<tr><th>��Ͽ��<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "    <th nowrap>����ǯ����<td>{$ord['����ǯ����']}";

    $name = get_emp_name($ord['��߰�']);
    print "<tr><th>��߰�<td>
           <font color=red>{$name['lname']}&nbsp;{$name['fname']}</font>
               <th>�����<td><font color=red>{$ord['�����']}</font>";
    $name = get_emp_name($ord['������']);
    print "<tr><th>������<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>����ǯ����<td>{$ord['����ǯ����']}";
    /* ���Ƥ�ɽ�� */
    show_tp_tests($meds);
    print '<tr><td colspan="4" align=left>
           <button type="submit" name="update" value="'.$oid."\">����</button>\n";
    print "<button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      ���ν����ΰ������̤򳫤�</button>
      <tr><td colspan=4>";
    get_order_history("���������",$oid,"pill");
    print '</table>';
  }
}

/* chech is user is selected medicine */
function manage_med_contents($var) {
  global $oid;


  $meds = get_tp_tests($oid,0);
  foreach ($meds as $med) {
    $medis = $med['medis'];
    foreach ($med as $k => $v) {
      if ($k != "��Ϳ����" && $k != "��ˡ")
	printf('<input type="hidden" name="med%d%s" value="%s">'."\n",
	     $medis,$k,$v,$v);
    }
  }
  print "<table>";
  show_tp_tests($meds);
  print '</table>
         <button type="submit" name="dbaction" value="dbupdate">
         ������Ͽ</button>';
}

function show_edit_order($var) {
  global $__mx_formi_dek;
  global $action;
  global $auth;
  global $oid;
  global $pid;

  if (!$action) return;
  foreach ($var as $k => $v)
    if ($k == "new" || $k == "copy" || $k == "update" || 
	$k == 'delcont' ||
	$k == 'sort' || ereg("tp",$k) || ereg("drug",$k))
      $go = true;

  if ($pid && $go && $var["dbaction"] != "dbnew") {
    if ($oid && !$var['new'] && !$var['detail'])
      $ord = get_test_order($oid,1);
    else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    foreach($ord as $k => $v) {
      if ($k != "������" && $k != "����ǯ����" &&
	  $k != "CreatedBy" && $k != "oid" )
	printf('<input type="hidden" name="i%s" value="%s">'."\n",$k,$v);
    }
    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";
    print "<table border=1>";
    if ($action == "update") print "<tr><th nowrap>�����ID<td>{$oid}";
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    $doc = get_emp_name($ord['��Ͽ��']);
    $stp = get_emp_name($ord['��߰�']);
    print "<tr><th nowrap>��Ͽ��
               <td>{$doc['lname']}&nbsp;{$doc['fname']}
               <th nowrap>����ǯ����<td nowrap>{$ord['����ǯ����']}
           <tr><th nowrap>��߰�
               <td>{$stp['lname']}&nbsp;{$stp['fname']}
               <th nowrap>�����<td nowrap>{$ord['�����']}
           <tr><th nowrap>������
               <td>{$rec['name']['lname']}&nbsp;{$rec['name']['fname']}
           <input type=\"hidden\" name=\"i������\" value=\"{$rec['id']}\">";
    print '<th>����ǯ����<td><input type="text" maxlength="10" 
         name="i����ǯ����" '.$__mx_formi_dek.' value="' . 
      ($ord["����ǯ����"] ? $ord["����ǯ����"] : date("Y-m-d")) . '">';
    print "</table>\n";

    manage_med_contents($var);
  }
}

if (!$pid) {
	/*
	 * This part is incredibly stupid.  It sometimes draws and
	 * it sometimes doesn't.  If it is _functional_ it should do
	 * its thing and leave the drawing to the caller.  Otherwise
	 * it should always draw stuff.  This stupid style does not
	 * let the caller to tweak how the output begins with X-<.
	 */
	$pat = get_pat("");
	if (!$pat) {
	  print "</table>";
	  return;
	}
	$pid = $pat['ObjectID'];
}

$stmt = ('SELECT "����ID" FROM "������Ģ" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['����ID'];

mx_draw_patientinfo_brief($pid);
mx_draw_ppa_applist($pt_hid);
print '</td></tr></table>';
print '<hr />';

print "<form method=\"post\" action=\"$uri\">\n";
print '<table style="border-collapse: collapse; border: hidden">
       <tr><td valign="top" width="50%" style="border-right: solid">'."\n";
show_static_order($pat,$_POST);
print "<hr>";
show_static_detail($_POST);
print "\n<td valign=\"top\" width=\"50%\">\n";
show_edit_order($_REQUEST);
print "</table></form>\n";
?>
</body></html>
