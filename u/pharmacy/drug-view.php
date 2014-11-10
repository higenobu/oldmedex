<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
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

function show_drug_list($drugs, &$ym){
  print '<table><tr><th>����ID<th>��ʧǯ����
           <input type="hidden" name="ym" value="'. $ym .'"<td>' . "\n";
  foreach ($drugs as $item) {
    $data = base64_encode(serialize($item));
    print '<tr><td>
             <button type="submit" name="detail" value="' . $data .
      "\">����ID{$item['ID']}</button>" .
      "<td>{$item['��ʧǯ����']}<td>\n";
  }
  print "</table><p>\n";
}

function get_pat_name($oid) {
  $con = mx_db_connect();

   return (pg_fetch_assoc(pg_query($con,
  'select P."��", P."̾"
   from "���޽����" as O, "������Ģ" as P
   where O."Superseded" is NULL and
         P."Superseded" is NULL and
         P."ObjectID" = O."����" and
         O."ObjectID" = ' . mx_db_sql_quote($oid))));
}

function show_drug_detail($detail) {
  if ($detail) {
    $data = unserialize(base64_decode($detail));
    if ($data["���޻�"]) $pharm = get_emp_name($data["���޻�"]);
    if ($data["�����"]) $pname = get_pat_name($data["�����"]);
    print "<table><tr><th>��ϿID<td>{$data['ID']}
           <tr><th>��ʧ��<td>" .
          ($data['��ʧ'] == "I" ? "����" : 
             ($data['��ʧ'] == "O" ? "ʧ��" : "")) .
          "<tr><th>����̾<td>{$data['�쥻�ץ��Ż����������ƥ������̾']}
           <tr><th>��ʧǯ����<td>{$data['��ʧǯ����']}
           <tr><th>��ʧ����<td>{$data['��ʧ����']}
           <tr><th>���޻�̾<td>{$pharm['lname']}��{$pharm['fname']}
           <tr><th>����̾<td>{$pname['��']}��{$pname['̾']}
           <tr><th>�Ŀ���<td>{$data['�Ŀ���']}
           <tr><th>����<td>{$data['����']}
           </table>";
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
if(!$ym && (!$drugs = search_drugs("",$ym))) {
  print "</table>";
  return;
} else {
  print "</table><hr>";
  print '<table border="0"><tr><td valign="top">' . "\n";
  show_drug_list($drugs,$ym);
  print "<tr><td><hr>";
  print "<tr><td>";
  show_drug_detail($_POST['detail']);
  print "</table>\n";
}
print "</form>";
?>