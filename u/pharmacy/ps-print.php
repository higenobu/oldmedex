<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
$uri = $_SERVER['SCRIPT_NAME'];

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
echo "<hr>";

function show_hist_list($var){
  if ($list = get_prescription_list()) {
    print "<table><tr><th>処方箋ID<th>処方年月日
                      <th>処方開始日<th>区分<th>病棟名\n";
    foreach ($list as $ps) {
      $build = get_pat_build($ps['pid']);
      print "<tr><td>\n";
      print '<button type="submit" name="detail" value="' . $ps['oid'] .
	"\">処方箋ID{$ps['oid']}</button>";
      print "<td>{$ps['処方年月日']}
             <td>{$ps['処方開始日']}
             <td>{$ps['区分']}
             <td>{$build['病棟名']}\n";
    }
    print "</table><p>\n";
  } else
    print "該当する処方箋がありません。";
}

function show_hist_detail($var) {
  $oid = $var['detail'];

  if ($oid) {
    $ord = get_pharm_order($oid);
    $doc = get_emp_name($ord['記録者']);
    $injection = $ord['注射'];
    if ($injection) $meds = get_meds($oid,1);
    else $meds = get_meds($oid,0);
    $room = get_pat_room($ord['患者'],false);
    $pat = get_patient($ord['患者'],false);
    print "<table>
           <tr><th>処方箋ID<td>{$oid}
               <th>患者名<td>{$pat['姓']}　{$pat['名']}
           <tr><th>処方医<td>{$doc['lname']}　{$doc['fname']}
               <th>病室<td>{$room['病室名']}
           <tr><th>処方年月日<td>{$ord['処方年月日']}
               <th>処方開始日<td>{$ord['処方開始日']}
           <tr><th>日数<td>{$ord['日数']}
               <th>区分<td>{$ord['区分']}";

    /* 内容の表示 */
    show_meds($meds,4);
    print "<tr><td><button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      この処方の印刷画面を開く</button>
      </table>";
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
print '<table border="0"><tr><td valign="top">' . "\n";
show_hist_list($_POST);
print "<tr><td><hr>";
show_hist_detail($_POST);
print "</table></form>\n";

?>