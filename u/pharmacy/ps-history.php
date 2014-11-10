<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';

print '<table border="0"><tr><td valign="top"  width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo();
print '<td valign="top" align="left">';

function show_hist_list($pat,$ym,$var){
  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  print "����̾��$patl $patf <p>
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n";
  if ($hists = get_history($pid,$ym,2)) {
    print "<table><tr><th>�����ID<th>����ǯ����<th>�����\n";
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['oid'];
      print '<button type="submit" name="detail" value="' . $oid .
	"\">�����ID{$oid}</button>";
      if ($hist['�����'])
	print '<td><font color="red">'.$hist['����ǯ����'].'</font><td><font color="red">'.
	  $hist['�����']."</font>\n";
      else
	print "<td>{$hist['����ǯ����']}<td>{$hist['�����']}\n";
    }
    print "</table><p>\n";
  } else
    print "���δ��Ԥˤϳ����������䵤�����ޤ���";
}

function show_hist_detail($var) {
  $oid = $var['detail'];

  if ($oid) {
    $ord = get_pharm_order($oid);
    $doc = get_emp_name($ord['��Ͽ��']);
    $injection = $ord['���'];
    if ($injection) $meds = get_meds($oid,1);
    else $meds = get_meds($oid,0);
    $room = get_pat_room($var['pid'],false);
    $rec = get_emp_name($ord['���޵�Ͽ��']);
    $name = get_emp_name($ord['��߰�']);
    $pname = get_emp_name($ord['Ĵ�����޻�']);
    print "<table><tr><th>�����ID<td>{$oid}
           <tr><th>������<td>{$doc['lname']}��{$doc['fname']}
           <tr><th>��Ͽ��<td>{$rec['lname']}��{$rec['fname']}
           <tr><th>�¼�<td>{$room['�¼�̾']}
           <tr><th nowrap>����ǯ����<td>{$ord['����ǯ����']}
           <tr><th>����������<td>{$ord['����������']}
           <tr><th>����<td>{$ord['����']}
           <tr><th>��ʬ<td>{$ord['��ʬ']}
           <tr><th>��߰�<td>{$name['lname']}&nbsp;{$name['fname']}
           <tr><th>�����<td>{$ord['�����']}           
           <tr><th>Ĵ�����޻�<td>{$pname['lname']}&nbsp;{$pname['fname']}
           <tr><th>Ĵ��ǯ����<td align=left>{$ord['Ĵ��ǯ����']}";

    /* ���Ƥ�ɽ�� */
    show_meds($meds,2);
    print '</table>';
  }
}

if (!$pid && !($pat = search_patient("",$ym))) {
  print "</table>";
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

  print "<form method=\"post\" action=\"$uri\">\n";
  print '<table border="0"><tr><td valign="top">' . "\n";
  show_hist_list($pat,$ym,$_POST);
  print "<tr><td>";
  show_hist_detail($_POST);
  print "</table></form>\n";
}
?>