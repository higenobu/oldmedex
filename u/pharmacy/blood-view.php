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
print '<br>';
mx_draw_userinfo();
print '<td valign="top" align="left">';

function show_blood_list($pat,$var){
  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  print "����̾��$patl $patf <p>
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n";
  if ($items = get_bloods($pid)) {
    print "<table><tr><th>���������ID<th>Ĵ��ǯ����<td>\n";
    foreach ($items as $item) {
      $data = base64_encode(serialize($item));
      print '<tr><td>
             <button type="submit" name="detail" value="' . $data .
             "\">���������ID{$item['ObjectID']}</button>" .
        "<td>{$item['Ĵ��ǯ����']}<td>\n";
    }
    print "</table><p>\n";
  } else
    print "���δ��Ԥˤϡ����޽���䵤���Ф���Ƥ��ޤ���";
}

function show_blood_detail($var) {
  if ($var['detail']) {
    $data = unserialize(base64_decode($var['detail']));
    if ($data["Ĵ�����޻�"]) $pharm = get_emp_name($data["Ĵ�����޻�"]);
    print "<table><tr><th>���������ID<td>{$data['ObjectID']}
           <tr><th>����<td>{$data['��']}��{$data['̾']}
           <tr><th>����<td>��{$data['����0']}��{$data['����1']}��
           {$data['����2']}��{$data['����3']}��{$data['����4']}
           <tr><th>Ĵ�����޻�<td>{$pharm['��']}��{$pharm['̾']}
           <tr><th>Ĵ��ǯ����<td>{$data['Ĵ��ǯ����']}
           <tr><th>����<td>{$ord['����']}
           <tr><td colspan=\"2\">{$data['�쥻�ץ��Ż����������ƥ������̾']}��
                   {$data['��ˡ']}{$data['�����ˡ']}��
                   {$data['����']}��{$data['����ñ��']}
           <tr><th>��¤�ֹ�<td>{$data['��¤�ֹ�']}
           </table>";
  }
}

if (!$pid && !($pat = search_patient("",$ym))) {
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

  print "<form method=\"post\" action=\"$uri\">\n";
  print '<table border="0"><tr><td valign="top">' . "\n";
  show_blood_list($pat,$_POST);
  print "<tr><td><hr>";
  print "<tr><td>";
  show_blood_detail($_POST);
  print "</table></form>\n";
}
?>