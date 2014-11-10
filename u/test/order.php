<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/testpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
global $tp;
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

function show_static_order($pat,$var,$tp) {
  global $auth;
  if ($var['dbaction'] == "dbnew") {
    if (!insert_test_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }
  elseif ($var['dbaction'] == "dbupdate") {
    if (!update_test_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  print "患者名　$patl $patf <p>\n
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n
         <button type=\"submit\" name=\"new\" value=\"1\">
         新規処方作成</button>\n";


  $testpick_cfg = array('LIST_IDS' 
			=> array ("項目名 (略式名)",
				  "項目名 (日本語)",
				  "単位名",
				  "ObjectID"));
    
  $tp =  new testpick('tp-', $testpick_cfg);
  $tp->draw();
  show_tp($auth[2]['ObjectID'],"tp-select",1);

  if ($pid) {
    if (!($hists = get_test_order($pid,0))) return;
    print "<table><tr><td><th>処方年月日<th>検査年月日<th>停止日";
    $hnum = count($hists);
    $last = 1;
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['oid'];
      if ($last++ == $hnum)
	print '<input type="hidden" name="last" value="'.$oid.'">';
      print '<button type="submit" name="detail" value="'.$oid.'">処方箋ID'.$oid."</button>";
      if ($hist['停止日']) 
	print '<td><font color="red">'.$hist['処方年月日'].
	  '</font><td><font color="red">'.$hist['検査年月日'].
	  '</font><td><font color="red">'.$hist['停止日']."</font>\n";
      else 
	print "<td>{$hist['処方年月日']}<td>{$hist['検査年月日']}
               <td>{$hist['停止日']}\n";
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
           <table><tr><th>処方箋ID<td>{$oid}";
    $name = get_emp_name($ord['記録者']);
    print "<th>記録者<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>処方年月日<td>{$ord['処方年月日']}
               <th nowrap>処方開始日<td>{$ord['処方開始日']}";

    $name = get_emp_name($ord['停止医']);
    print "<tr><th>停止医<td>
           <font color=red>{$name['lname']}&nbsp;{$name['fname']}</font>
               <th>停止日<td><font color=red>{$ord['停止日']}</font>";
    $name = get_emp_name($ord['検査師']);
    print "<tr><th>検査師<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>検査年月日<td>{$ord['検査年月日']}";
    /* 内容の表示 */
    show_tp_tests($meds);
    print '<tr><td colspan="4" align=left>
    <button type="submit" name="update" value="'.$oid."\">更新</button>\n";
    if ($_POST['last'] == $oid)
      print '<button type="submit" name="copy" value="'.$oid."\">コピー</button>\n";
    print "<button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      この処方の印刷画面を開く</button>
      <tr><td colspan=4>";
    get_order_history("検査処方箋",$oid,"pill");
    print '</table>';
  }
}

/* chech is user is selected medicine */
function check_medis($med) {
  if ($med['medis']) return true;
}

function manage_med_contents($var,$tp) {
  global $action;
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

  if ($var["delcont"] && count($meds) > 1)
    $meds = array_filter($meds,'check_medis');

  if ($var['new'] || !count($meds))
    unset($meds);

  if (!$var['min']) $min = -1; else $min = $var['min'];
  if ($_POST['tp-sel-id-select']) {
    $k = mx_form_unescape_key($tp->chosen());
    $local_idx = count($meds);
    $meds[$local_idx]['medid'] = $min;
    $meds[$local_idx]['項目名(略式名)'] = $k[0];
    $meds[$local_idx]['項目名(日本語)'] = $k[1];
    $meds[$local_idx]['単位名'] = $k[2];
    $meds[$local_idx]['medis'] = $k[3];
    $min = $min - 1;
  } elseif ($var['tp-select']) {
    $tpmeds = get_tp_tests($var['tp-select'],1);
    $tp_index = count($meds);
    foreach($tpmeds as $tpitem) {
      $meds[$tp_index]['medid'] = $tpitem['medid'];
      $meds[$tp_index]['medis'] = $tpitem['medis'];
      $meds[$tp_index]['項目名(略式名)'] = $tpitem['項目名(略式名)'];
      $meds[$tp_index]['shape'] = $tpitem['shape'];
      $meds[$tp_index]['単位名'] = $tpitem['単位名'];
      $meds[$tp_index]['freq'] = $tpitem['freq'];
      $meds[$tp_index]['freqid'] = $tpitem['freqid'];
      $meds[$tp_index]['用量'] = $tpitem['用量'];
      $meds[$tp_index]['用法'] = $tpitem['用法'];
      $meds[$tp_index]['その他コメント'] = $tpitem['その他コメント'];
      $tp_index++;
    }
    $min = $min - count($tpmeds);
  }

  /* 
  the request was posted from the left pane, get the current contents
  from the order.
  */
  if ($var["update"] || $var["copy"] || $var["dbaction"])
    $meds = get_tp_tests($oid,0);

  if (!count($meds)) return;

  print "<table>";
  for ($i=0, $c=count($meds); $i < $c ; $i++) {
    $medid = $meds[$i]['medid'];

    print '<tr><th align="center" colspan="4"><hr>
           <tr><th align="center" colspan="4"><font color="green">';
    if ($medid < 0) print "処方内容";
    elseif ($action == "update") print "処方内容ID{$medid}";
    elseif ($action == "copy") print "処方内容ID{$medid}のコピー";
    else  print "処方内容";
    print "</font>\n";

    if (($v = $meds[$i]["項目名(略式名)"]) && !$_POST['drug'.$medid]) {
      print '<tr><td>';
      print '<input type="hidden" name="med'.$medid.
	'medis" value="'.$meds[$i]['medis'].'">';
      print '<input type="hidden" name="med'.$medid.
	'項目名(略式名)" value="'.$meds[$i]['項目名(略式名)'].'">';
      print $v;
    }
    print '<td><input type="text" maxlength="3" size="3"
         name="med'.($medid).'用量" value="'.
      ($meds[$i]["用量"] == "" ? "1" : $meds[$i]["用量"]).'"'. 
      $__mx_formi_dek . '>
       <input type="hidden" name="med'.($medid).'単位名" value="'.
      ($meds[$i]["単位名"] ? $meds[$i]["単位名"] : "").'">'."\n";
    print '<td>';
    list_med("med".$medid."shapeid",$meds[$i]["shapeid"],"shape");
    print '<td>';
    $def_freqid = $meds[$i]["freqid"] ? $meds[$i]["freqid"] : 
      (($i-1 >= 1) ? $meds[$i-1]["freqid"] : $meds[0]["freqid"]);
    list_med("med".$medid."freqid",$def_freqid,"freq");    
    print "<tr><th nowrap valign=\"top\">コメント<td colspan=\"2\">
           <textarea name=\"med{$medid}その他コメント\" ".$__mx_formi_dek."
           cols=\"32\" rows=\"2\">{$meds[$i]['その他コメント']}</textarea>";
    print "<td>
           <input type=\"hidden\" name=\"med{$medid}medid\" value=\"{$medid}\">";

    print "<button type=\"submit\" name=\"delcont\" value=\"med{$medid}\">
           処方内容削除</button>";
  }
  print '</table><input type="hidden" name="min" value="'.($min).'">';

  print '<button type="submit" name="dbaction" value="' .
    ($action == "update" ? "dbupdate" : "dbnew") . '">';
  if ($action == "update") print "処方箋ID{$oid}のアップデート";
  else print "処方登録"; 
  print '</button>';
}

function show_edit_order($var,$tp) {
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

    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";
    print "<table border=1>";
    if ($action == "update") print "<tr><th nowrap>処方箋ID<td>{$oid}";
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    print "<tr><th nowrap>記録者<td>{$rec['name']['lname']}&nbsp;{$rec['name']['fname']}
           <input type=\"hidden\" name=\"i記録者\" value=\"{$rec['id']}\">";
    print '<tr><th>処方年月日<td><input type="text" maxlength="10" 
         name="i処方年月日" '.$__mx_formi_dek.' value="' . 
        ($ord["処方年月日"] ? $ord["処方年月日"] : date("Y-m-d")) . '">
         <th>検査年月日<td><input type="text" maxlength="10" 
         name="i検査年月日" '.$__mx_formi_dek.' value="' . 
        ($ord["検査年月日"] ? $ord["検査年月日"] : "") . '">
         <tr><th>停止日<td><input type="text" maxlength="10" 
         name="i停止日" value="' . $ord["停止日"] . '" '.$__mx_formi_dek.'>
           <th>停止医<td>
         <input type="hidden" name="i患者" value="'.$pid.'">';
    list_doctors("i停止医",$ord['停止医'],$pid,"all0",$rec);

    print "</table>\n";

    manage_med_contents($var,&$tp);
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
show_static_order($pat,$_POST,&$tp);
print "<hr>";
show_static_detail($_POST);
print "\n<td valign=\"top\" width=\"50%\">\n";
show_edit_order($_REQUEST,&$tp);
print "</table></form>\n";
?>
</body></html>
