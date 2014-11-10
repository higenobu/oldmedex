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
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  print "患者名　$patl $patf <p>
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n";
  if ($hists = get_history($pid,$ym,2)) {
    print "<table><tr><th>処方箋ID<th>処方年月日<th>停止日\n";
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['oid'];
      print '<button type="submit" name="detail" value="' . $oid .
	"\">処方箋ID{$oid}</button>";
      if ($hist['停止日'])
	print '<td><font color="red">'.$hist['処方年月日'].'</font><td><font color="red">'.
	  $hist['停止日']."</font>\n";
      else
	print "<td>{$hist['処方年月日']}<td>{$hist['停止日']}\n";
    }
    print "</table><p>\n";
  } else
    print "この患者には該当する処方箋がありません。";
}

function show_hist_detail($var) {
  $oid = $var['detail'];

  if ($oid) {
    $ord = get_pharm_order($oid);
    $doc = get_emp_name($ord['記録者']);
    $injection = $ord['注射'];
    if ($injection) $meds = get_meds($oid,1);
    else $meds = get_meds($oid,0);
    $room = get_pat_room($var['pid'],false);
    $rec = get_emp_name($ord['薬剤記録者']);
    $name = get_emp_name($ord['停止医']);
    $pname = get_emp_name($ord['調剤薬剤師']);
    print "<table><tr><th>処方箋ID<td>{$oid}
           <tr><th>処方医<td>{$doc['lname']}　{$doc['fname']}
           <tr><th>記録者<td>{$rec['lname']}　{$rec['fname']}
           <tr><th>病室<td>{$room['病室名']}
           <tr><th nowrap>処方年月日<td>{$ord['処方年月日']}
           <tr><th>処方開始日<td>{$ord['処方開始日']}
           <tr><th>日数<td>{$ord['日数']}
           <tr><th>区分<td>{$ord['区分']}
           <tr><th>停止医<td>{$name['lname']}&nbsp;{$name['fname']}
           <tr><th>停止日<td>{$ord['停止日']}           
           <tr><th>調剤薬剤師<td>{$pname['lname']}&nbsp;{$pname['fname']}
           <tr><th>調剤年月日<td align=left>{$ord['調剤年月日']}";

    /* 内容の表示 */
    show_meds($meds,2);
    print '</table>';
  }
}

if (!$pid && !($pat = search_patient("",$ym))) {
  print "</table>";
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
  show_hist_list($pat,$ym,$_POST);
  print "<tr><td>";
  show_hist_detail($_POST);
  print "</table></form>\n";
}
?>