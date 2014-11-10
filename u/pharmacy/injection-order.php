<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

function date_sort ($a,$b) {
  return strcmp($a["処方年月日"],$b["処方年月日"]);
}
function type_sort ($a,$b) {
  return strcmp($b["区分"],$a["区分"]);
}
function id_sort ($a,$b) {
  return strcmp($a["oid"],$b["oid"]);
}
function canc_sort ($a,$b) {
  return strcmp($a["停止日"],$b["停止日"]);
}

mx_html_head($auth[1]); print '<body>';
$action = $_POST['new'] ? "new" : ($_POST['copy'] ? "copy" : 
          ($_POST['update'] ? "update" : $_POST['action']));  
$class = "";
$dbaction = $_POST['dbaction'];
$oid = $_POST['update'] ? $_POST['update'] : 
	($_POST['copy'] ? $_POST['copy']  : 
	 ( $_POST['detail'] ? $_POST['detail'] : $_REQUEST['oid']));
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];

function show_static_order($pat,$ym,$var) {
  global $dberror;
  global $oid;

  $dberror = "";
  if ($var['dbaction'] == "dbnew") {
    $ignore = 'ignore';
    if (!insert_pharm_order($var, &$ignore)) {
      pg_query(mx_db_connect(),"rollback");
      $dberror = '<font color="red">登録に失敗しました。再度登録を行ってください。</font>';
    }
    flush();
  }
  elseif ($var['dbaction'] == "dbupdate") {
    if (!update_pharm_order($var)) {
      pg_query(mx_db_connect(),"rollback");
      $dberror = '<font color="red">更新に失敗しました。再度更新を行ってください。</font>';
    }
    flush();
  }
  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  print "{$dberror}<br><input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n
         <button type=\"submit\" name=\"new\" value=\"1\">
         新規処方作成</button>\n";
  if ($pid) {
    if (!($hists = get_history($pid,$ym,1))) return;
    $sort_type=$_REQUEST['sort'];
    switch ($sort_type) {
    case "type" :
      usort($hists,"type_sort"); break;
    case "date" :
      usort($hists,"date_sort"); break;
    case "id" :
      usort($hists,"id_sort"); break;
    case "canc" :
      usort($hists,"canc_sort"); break;
    }

    print '<input type="hidden" name="sort" value="'.$sort_type.'"><table><tr>';
    print '<th><button type="submit" class="plain" name="sort" value="id">処方箋ID</button>';
    print '<th><button type="submit" class="plain" name="sort" value="date">処方年月日</button>';
    print '<th><button type="submit" class="plain" name="sort" value="type">区分</button>';
    print '<th><button type="submit" class="plain" name="sort" value="canc">停止日</button>';
    $hnum = count($hists);
    $last = 1;
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $objid = $hist['oid'];
      if ($var['dbaction'] == "dbnew" && $last++ == $hnum)
	$oid = $objid;
      print '<button type="submit" name="detail" value="'.$objid.'">処方箋ID'.$objid."</button>";
      if ($hist['停止日']) 
	print '<td><font color="red">'.$hist['処方年月日'].'</font><td><font color="red">'.$hist['区分'].'
               </font><td><font color="red">'.$hist['停止日']."</font>\n";
      else 
	print "<td>{$hist['処方年月日']}<td>{$hist['区分']}<td>{$hist['停止日']}\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail () {
  global $class;
  global $oid;

  if ($oid) {
    $ord = get_pharm_order($oid);
    $meds = get_meds($oid,1);
    if ($meds) {
      $class = "";
      foreach($meds as $med)
	if (check_drug($med['medis'])) {
	  $class = 'class="historical-data"';
	  break;
	}
    }
    print "<input type=hidden name=oid value={$oid}>
           <table {$class}><tr><th>処方箋ID<td>{$oid}";
    $name = get_emp_name($ord['記録者']);
    print "<th>記録者<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>処方年月日<td>{$ord['処方年月日']}
               <th nowrap>処方開始日<td>{$ord['処方開始日']}
           <tr><th>区分<td>{$ord['区分']}
               <th>日数<td>{$ord['日数']}";

    $name = get_emp_name($ord['停止医']);
    print "<tr><th>停止医<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>停止日<td>{$ord['停止日']}";

    /* 内容の表示 */
    show_meds($meds,4);
    print '<tr><td colspan="4" align="left">
    <button type="submit" name="update" value="'.$oid."\">更新</button>\n";
    print '<button type="submit" name="copy" value="'.$oid."\">コピー</button>\n";
    print "<button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      この処方の印刷画面を開く</button>
      <tr><td colspan=4>";
    get_order_history("薬剤処方箋",$oid,"chusha");
    print '</table>';
  }
}

/* chech is user is selected medicine */
function check_medis($med) {
  if ($med['medis']) return true;
}

function manage_med_contents($var) {
  global $action;
  global $class;
  global $oid;
  global $__mx_formi_dek;

  /*
  if the key name is "delcont", delete a content was requested.
  remove the delete requested content.
  */

  if ($var['delcont']) $skip = $var['delcont'];
  else unset($skip);

  /*
  if the key name in $var starts with "med...", these are the contents
  of the order. Divide them into individual contents.
  */
  $slot = -1;
  foreach ($var as $key => $val) {
    if (ereg("^med[-]*[0-9].*",$key)) {
      $indx = ereg_replace("^(med[-]*[0-9]*).*","\\1",$key);
      if ($indx == $skip) continue;
      if ($indx != $oindx) {
	$oindx = $indx;
	$slot++;
      }
      $meds[$slot][ereg_replace($indx,"",$key)] = $val;
    }
  }

  if (($var["addcont"] || $var["delcont"]) && count($meds) > 1)
    $meds = array_filter($meds,'check_medis');

  /* add a content was requested. Add one empty content to $meds. */
  if ($var['addcont'] && $meds[0]['medis'])
    $meds[count($meds)]['medid'] = $var['addcont'];
  elseif ($var['new'] || !count($meds)) {
    unset($meds);
    $meds[0]['medid'] = -1;
  }

  /* 
  the request was posted from the left pane, get the current contents
  from the order.
  */
  $drugpick_cfg = array('LIST_IDS' 
			=> array ("ObjectID", "レセプト電算処理システム医薬品名", "告示名称", "製造会社",
			 "販売会社","包装単位単位"),
			'SKIP_CATEGORY' => 1,
			'INJECTION' => 1);

  if ($var["update"] || $var["copy"] || $var["dbaction"])
    $meds = get_meds($oid,1);
  $class="";
  foreach($meds as $med)
    if($med['medis'] && check_drug($med['medis']))
       $class = 'class="historical-data"';
  $min = 0;
  print "<table $class>";
  for ($i=0, $c=count($meds); $i < $c ; $i++) {
    $medid = $meds[$i]['medid'];
    if ($medid < $min) $min = $medid;
    $dp[$i] =  new drugpick('dp'.$medid.'-', $drugpick_cfg);
    print '<tr><th align="center" colspan="4"><hr>
           <tr><th align="center" colspan="4"><font color="green">';
    if ($medid < 0) print "処方内容";
    elseif ($action == "update") print "処方内容ID{$medid}";
    elseif ($action == "copy") print "処方内容ID{$medid}のコピー";
    else  print "処方内容";
    print "</font>\n";
    if (($v = $meds[$i]["レセプト電算処理システム医薬品名"]) && !$_POST['drug'.$medid]) {
      print '<tr><th>薬剤<td>';
      print '<input type="hidden" name="med'.$medid.'medis" value="'.$meds[$i]['medis'].'">';
      print '<input type="hidden" name="med'.$medid.'レセプト電算処理システム医薬品名" value="'.$meds[$i]['レセプト電算処理システム医薬品名'].'">';
      print '<button type="submit" name="drug'.$medid. '" value="'.$v.'">'.$v. "</button>";
    }
    elseif (($var['drug'.$medid] || !$dp[$i]->chosen()) && (!$var['copy'] && 
	!$var['update'])) {
      $dp[$i]->draw();
    }
    elseif ($dp[$i]->chosen()) {
      print '<tr><th>薬剤<td>';
      $k = mx_form_unescape_key($dp[$i]->chosen());
      print '<input type="hidden" name="med'.$medid.'medis" value="'.$k[0].'">';
      print '<input type="hidden" name="med'.$medid.'レセプト電算処理システム医薬品名" value="'.$k[1].'">';
      print '<button type="submit" name="drug'.$medid.'" value="'.$k[1].'">'.$k[1] . "</button>";
    }
    print '<tr><th>日数<td><input type="text" maxlength="3" size="3" 
         name="med'.($medid).'日数" value="'.$meds[$i]["日数"].'"'. 
         $__mx_formi_dek . '>';
    print '<tr><th>用量<td><input type="text" maxlength="5" size="5"
         name="med'.($medid).'用量" value="'.($meds[$i]["用量"] == "" ? "1" : $meds[$i]["用量"]).'"'. 
         $__mx_formi_dek . '>';
    /* look for the unit ID from Medis data */
    if ($k[5]) $uid = get_unitid($k[5]);
    list_med("med".$medid."unitid",($uid['id'] == "" ? $meds[$i]["unitid"] : $uid['id']),"units");
    print '<td><td><tr><th>手技<td>';
    list_med("med".$medid."placeid",$meds[$i]["placeid"],"place");
    $def_freq = $meds[$i]['注射用法'] ? $meds[$i]['注射用法'] : 
      (($i-1 >= 1) ? $meds[$i-1]["注射用法"] : $meds[0]['注射用法']);
    print '<td><td><tr><th>用法<td>
           <input type="text" name="med'.$medid.'注射用法" value="'.
           $def_freq.'" '.$__mx_formi_dek.' size="22" maxlength="22">
           <tr><th nowrap valign="top">その他コメント<td colspan="3">
           <textarea name="med'.$medid.'その他コメント" '.$__mx_formi_dek.'
           cols="32" rows="2">'.$meds[$i]['その他コメント'].'</textarea>';
    print "<tr><td colspan=\"4\">
           <input type=\"hidden\" name=\"med{$medid}medid\" value=\"{$medid}\">";
    if ($c > 1)
      print "<button type=\"submit\" name=\"delcont\" value=\"med{$medid}\">
           処方内容削除</button>";
  }
  /*
  add content request will have negative IDs in order to separate from
  the current contents in the database.
  */
  print '</table><p>
  <button type="submit" name="addcont" value="'.($min - 1).'">新処方内容の追加
  </button><p>';

  print '<button type="submit" name="dbaction" value="' .
    ($action == "update" ? "dbupdate" : "dbnew") . '">';
  if ($action == "update") print "処方箋ID{$oid}のアップデート";
  else print "処方登録"; 
  print '</button>';
}

function show_edit_order($var) {
  global $__mx_formi_dek;
  global $action;
  global $auth;
  global $dberror;
  global $oid;

  $pid = $var['pid'];
  if (!$action) return;
  foreach ($var as $k => $v)
    if ($k == "new" || $k == "copy" || $k == "update" || 
	$k == 'addcont' || $k == 'delcont' || $k == 'detail' ||
	$k == 'sort' || ereg("dp",$k) || ereg("drug",$k))
      $go = true;
  if ($pid && $go && !($var["dbaction"] && $dberror == '')) {
    if ($oid && !$var['new'] && !$var['detail']) {
      $ord = get_pharm_order($oid);
    } else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    if ($action == "copy") { $ord["処方年月日"] = $ord["処方開始日"] = date("Y-m-d"); }
    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";
    print "<table $class>";
    if ($action == "update") print "<tr><th>処方箋ID<td>{$oid}";
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    print "<tr><th>記録者<td>{$rec['name']['lname']}&nbsp;{$rec['name']['fname']}
           <input type=\"hidden\" name=\"i記録者\" value=\"{$rec['id']}\">";
    print '<td><td><tr><th>処方年月日<td><input type="text" maxlength="10" 
         name="i処方年月日" '.$__mx_formi_dek.' value="' . 
        ($ord["処方年月日"] ? $ord["処方年月日"] : date("Y-m-d")) . '"><td><td>
         <tr><th>処方開始日<td><input type="text" maxlength="10" 
         name="i処方開始日" '.$__mx_formi_dek.' value="' . 
        ($ord["処方開始日"] ? $ord["処方開始日"] : date("Y-m-d")) . '"><td><td>
         <tr><th>日数<td><input type="text" maxlength="3" size="3"
         name="i日数" '.$__mx_formi_dek.' value="' . $ord["日数"] . '"><td><td>
         <td><td><tr><th>区分<td><select name="i区分" '.$__mx_formi_dek.' >
         <option value="院外" '.($ord['区分'] == "院外" ? "selected" : "").'>院外
         <option value="院内" '.($ord['区分'] == "院内" ? "selected" : "").'>院内
         <option value="自費" '.($ord['区分'] == "自費" ? "selected" : "").'>自費
         </select>
         <td><td><tr><th>停止日<td><input type="text" maxlength="10" 
         name="i停止日" value="' . $ord["停止日"] . '" '.$__mx_formi_dek.'>
           <th>停止医<td>
         <input type="hidden" name="i患者" value="'.$pid.'">';
    list_doctors("i停止医",$ord['停止医'],$pid,"all0",$rec);

    print "</table>\n";

    manage_med_contents($var);
  }
}

print '<table border="0"><tr><td valign="top" width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
print '<td valign="top" align="left">';
if (!$pid) {
	/*
	 * This part is incredibly stupid.  It sometimes draws and
	 * it sometimes doesn't.  If it is _functional_ it should do
	 * its thing and leave the drawing to the caller.  Otherwise
	 * it should always draw stuff.  This stupid style does not
	 * let the caller to tweak how the output begins with X-<.
	 */
	$pat = search_patient("",$ym);
	if (!$pat) {
	  print '</table>';
	  return;
	}
	$pid = $pat['ObjectID'];
}
$stmt = ('SELECT "患者ID" FROM "患者台帳" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['患者ID'];

mx_draw_patientinfo_brief($pid);
mx_draw_ppa_applist($pt_hid);
print '</td></tr></table>';
print '<hr />';


print "<form method=\"post\" action=\"$uri\">\n";
print '<table style="border-collapse: collapse; border: hidden">
       <tr><td valign="top" width="50%" style="border-right: solid">'."\n";
show_static_order($pat,$ym,$_POST);
print "<hr>";
show_static_detail($_POST);
print "\n<td valign=\"top\" width=\"50%\">\n";
show_edit_order($_REQUEST);
print "</table></form>\n";
?>
</body></html>
