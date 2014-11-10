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

  if ($var['dbaction'] == "新規登録") {
    if (!insert_meal_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }
  elseif ($var['dbaction'] == "更新") {
    $var['i栄養士記録'] = "";
    if (!update_meal_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  $pid = $pat['ObjectID'] ? $pat['ObjectID'] : $var['pid'];
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  $patn = $pat['フリガナ'] ? $pat['フリガナ'] : $var['patn'];
  $patb = $pat['生年月日'] ? $pat['生年月日'] : $var['patb'];
  $pats = $pat['性別'] ? ($pat['性別'] == 'M' ? "男" : 
			($pat['性別'] == 'F' ? "女" : ""))
			: $var['pats'];
  $weight = $var['weight'] ?  $var['weight'] : get_measure($pid,"体重");
  $height = $var['height'] ?  $var['height'] : get_measure($pid,"身長");
  print "<table><tr><th nowrap>患者名<td>$patl $patf
                    <th nowrap>フリガナ<td>$patn
                <tr><th nowrap>生年月日<td>$patb
                    <th>性別<td>$pats
                <tr><th>身長<td>$height
                    <th>体重<td>$weight".
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
         新規食事箋</button>';
  if ($hists = get_meal_history($pid)) {
    print "<table><tr><th nowrap>食事箋ID<th nowrap>処方年月日<td>\n";
    foreach ($hists as $hist) {
      print "<tr><td nowrap>\n";
      $oid = $hist['ObjectID'];
      print '<button type="submit" name="detail" value="' . $oid . 
             "\">食事箋ID{$oid}</button>" .
	"<td nowrap>{$hist['処方日']}<td>\n";
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
       <td colspan=4><button type="submit" name="copy" value="'.$oid.'">コピー</button>
       <button type="submit" name="update" value="'.$oid.'">更新</button>
       <button type="button"'. 
       "OnClick=\"window.open('print.php?oid={$oid}','',
          'width=640,height=640')\">この処方の印刷画面を開く</button>
        <tr><td colspan=4>";
    get_order_history("食事箋",$oid,"meal");
    print "</table>\n";
  }
}

function show_edit_order($var) {
  global $__mx_formi_dek, $action, $auth, $ins, $oid, $ord_array;

  $pid = $var['pid'];
  if ($pid && $var["dbaction"] != "新規登録") {
    if ($oid && ($var['update'] || $var['copy'] || $var['move']))
      $ord = get_meal_order($oid);
    elseif (!$var['new'])
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    print '<input type="hidden" name="action" value="'.$action.'">';
    print '<input type="hidden" name="i患者" value="'.$pid.'">';
    print '<table border="1">';
    if ($action == "update") 
      print "<tr><th>食事箋ID<td align=center>{$oid}<td><td>";
    $name = get_emp_name($auth[2]['ObjectID']);
    $today = date("Y-m-d H:i:s");
    $day = ereg_replace(" .*$","",$today);
    $time = ereg_replace(".* ","",$today);

    print "<tr><th>記録者<th>{$name['lname']}&nbsp;{$name['fname']}\n".
      '<input type="hidden" name="i記録者" value="'.$auth[2]['ObjectID'].'">
       <th>記録日時<td align=center nowrap>'.disp_day_time($day,$time).
      '<input type="hidden" name="i記録日" value="'.$day.'">
       <input type="hidden" name="i記録時間" value="'.$time.'">
       <tr><th>処方日<td>'.
      print_input("処方日",10, 
		  ($ord["処方日"] ? $ord["処方日"] : date("Y-m-d"))).
      "<th>再開日<td>".
      print_input("再開日",10, 
		  ($ord["再開日"] ? $ord["再開日"] : "")).
      '<tr><th>区分<td>';
    print_select("区分","区分",$ord["区分"],false);
    print '<th>実行日<td>'.
      print_input("実行日",10,
		  ($ord["実行日"] ? $ord["実行日"] : date("Y-m-d"))).
      '<tr><th>実行時<td>';
    print_select("実行時","実行時",$ord["実行時"],false); 
    print '<th>食種<td>';
    print_select("食種","食種",$ord["食種"],true);
    print '<tr><th>適応疾患<td>';
    if (check_key($ord["食種"],$ins))
      print_select("適応疾患2","適応疾患",$ord["適応疾患"],false);
    else
      print_select("適応疾患","適応疾患",$ord["適応疾患"],false);
    print '<td><td><tr><td><th>食種名<th>主食<th>副菜
           <tr><th>朝<td>';
    print_select("食種","朝食種名",
		 ($ord['食種']=='絶食'?"絶食":$ord["朝食種名"]),false);
    print "<td>";
    print_select("主食","朝主食",$ord["朝主食"],false);
    print "<td>";
    print_select("副菜","朝副菜",$ord["朝副菜"],false);
    print
      '<tr><th>昼<td>';
    print_select("食種","昼食種名",
		 ($ord['食種']=='絶食'?"絶食":$ord["昼食種名"]),false);
    print "<td>";
    print_select("主食","昼主食",$ord["昼主食"],false);
    print "<td>";
    print_select("副菜","昼副菜",$ord["昼副菜"],false);
    print '<tr><th>夕<td>';
    print_select("食種","夕食種名",
		 ($ord['食種']=='絶食'?"絶食":$ord["夕食種名"]),false);
    print "<td>";
    print_select("主食","夕主食",$ord["夕主食"],false);
    print "<td>";
    print_select("副菜","夕副菜",$ord["夕副菜"],false);
    print  '<tr><th>補助食<td colspan="3">'.
      print_input("補助食",60,$ord['補助食']).'<tr><th>アレルギーの確認<td>';
    print_select("アレルギーの確認","アレルギーの確認",$ord["アレルギーの確認"],false);
    print '<td><td><tr><th>アレルギー<br>その他<td colspan="3">'.
      print_input("アレルギーの確認その他",60,$ord['アレルギーの確認その他']).
      '<tr><th>濃厚流動総熱量<td>'.
      print_input("濃厚流動総熱量",4,$ord['濃厚流動総熱量']).'kcal';
    if (check_key($ord["食種"],$ins)) {
      print "<th>保険<td>";
      print_select("保険","保険",$ord["保険"],false);
    } else 
      print "<td><td>";
    print '<tr><th colspan="4" height="50" valign="bottom" align="left">注入内容
           <tr><td><th>種類<th>量<th>水分<br>間水量
           <tr><td nowrap align="center">';
    print_select("時","時0",$ord["時0"],false);
    print '時<td align="center">';
    print_select("種類","種類0",$ord["種類0"],false);
    print '<td align="center">'.print_input("量0",4,$ord['量0']).
      'ml<td align="center">'.
      print_input("間水量0",4,$ord['間水量0']).'ml
          <tr><td align="center">';
    print_select("時","時1",$ord["時1"],false);
    print '時<td align="center">';
    print_select("種類","種類1",$ord["種類1"],false);
    print '<td align="center">'.print_input("量1",4,$ord['量1']).
      'ml<td align="center">'.
      print_input("間水量1",4,$ord['間水量1']).'ml
          <tr><td align="center">';
    print_select("時","時2",$ord["時2"],false);
    print '時<td align="center">';
    print_select("種類","種類2",$ord["種類2"],false);
    print '<td align="center">'.print_input("量2",4,$ord['量2']).
      'ml<td align="center">'.
      print_input("間水量2",4,$ord['間水量2']).'ml
          <tr><td align="center">';
    print_select("時","時3",$ord["時3"],false);
    print '時<td align="center">';
    print_select("種類","種類3",$ord["種類3"],false);
    print '<td align="center">'.print_input("量3",4,$ord['量3']).
      'ml<td align="center">'.
      print_input("間水量3",4,$ord['間水量3']).'ml
          <tr><td align="center">';
    print_select("時","時4",$ord["時4"],false);
    print '時<td align="center">';
    print_select("種類","種類4",$ord["種類4"],false);
    print '<td align="center">'.print_input("量4",4,$ord['量4']).
      'ml<td align="center">'.
      print_input("間水量4",4,$ord['間水量4']).'ml
          <tr><th>注入速度<td>';
    print_select("速度","速度",$ord["速度"],false);
    print '<th>速度その他<td>'.print_input("速度その他",4,$ord['速度その他']);
    print '<tr><th>特別指示
               <td colspan="3">'.print_input("特別指示",60,$ord['特別指示']);
    if ($action == "new" || $action == "copy") $label = "新規登録";
    else $label = "更新";
    print '</table>
      <button type="submit" name="dbaction" value="'.$label.'">'
      .$label."</button>\n";
  }
}

function show_warning () {
  print "<p>医薬品と食品の相互作用<br>
             <ul><li>ワーファリンと納豆（作用減弱）
                 <li>アダラートカプセル５ｍｇとグレープフルーツジュース（作用増強）
                 <li>ニルジラート錠２ｍｇとグレープフルーツジュース（作用増強）
                 <li>ペルジピン錠１０ｍｇとグレープフルーツジュース（作用増強）
                 <li>ミノマイシンカプセル100ｍｇとカルシウム（牛乳など）（作用減弱）</ul>";
}


if (!$action && !$pid && !($pat = get_pat(""))) {
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
