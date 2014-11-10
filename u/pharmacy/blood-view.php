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
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  print "患者名　$patl $patf <p>
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n";
  if ($items = get_bloods($pid)) {
    print "<table><tr><th>処方箋内容ID<th>調剤年月日<td>\n";
    foreach ($items as $item) {
      $data = base64_encode(serialize($item));
      print '<tr><td>
             <button type="submit" name="detail" value="' . $data .
             "\">処方箋内容ID{$item['ObjectID']}</button>" .
        "<td>{$item['調剤年月日']}<td>\n";
    }
    print "</table><p>\n";
  } else
    print "この患者には、薬剤処方箋が提出されていません。";
}

function show_blood_detail($var) {
  if ($var['detail']) {
    $data = unserialize(base64_decode($var['detail']));
    if ($data["調剤薬剤師"]) $pharm = get_emp_name($data["調剤薬剤師"]);
    print "<table><tr><th>処方箋内容ID<td>{$data['ObjectID']}
           <tr><th>患者<td>{$data['姓']}　{$data['名']}
           <tr><th>住所<td>〒{$data['住所0']}　{$data['住所1']}　
           {$data['住所2']}　{$data['住所3']}　{$data['住所4']}
           <tr><th>調剤薬剤師<td>{$pharm['姓']}　{$pharm['名']}
           <tr><th>調剤年月日<td>{$data['調剤年月日']}
           <tr><th>日数<td>{$ord['日数']}
           <tr><td colspan=\"2\">{$data['レセプト電算処理システム医薬品名']}　
                   {$data['用法']}{$data['注射用法']}　
                   {$data['用量']}　{$data['用量単位']}
           <tr><th>製造番号<td>{$data['製造番号']}
           </table>";
  }
}

if (!$pid && !($pat = search_patient("",$ym))) {
  print '</table>';
  return;
} else {
  $pid = $pat ? $pat['ObjectID'] :$pid;
  $stmt = ('SELECT "患者ID" FROM "患者台帳" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
  $d = mx_db_fetch_single(mx_db_connect(), $stmt);
  $pt_hid = $d['患者ID'];
  
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