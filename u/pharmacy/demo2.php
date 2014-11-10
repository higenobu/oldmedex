<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
global $dp;
$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';

$action = $_POST['new'] ? "new" : ($_POST['copy'] ? "copy" :
          ($_POST['update'] ? "update" : $_POST['action']));

$class = "";
$dbaction = $_POST['dbaction'];
$oid = $_POST['update'] ? $_POST['update'] : 
	($_POST['copy'] ? $_POST['copy']  : $_REQUEST['oid']);
$uri = $_SERVER['SCRIPT_NAME'];

print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
print "<hr>";

function show_static_order($var,$dp) {
  global $auth;
  global $dberror;

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

  print "{$dberror}<br><button type=\"submit\" name=\"new\" value=\"1\">
         新規ＲＰ作成</button>\n";

  $drugpick_cfg = array('LIST_IDS' 
			=> array ("ObjectID", "レセプト電算処理システム医薬品名", "告示名称", "製造会社",
				  "販売会社","包装単位単位"), 
			'SKIP_CATEGORY' => 1,
			'NOT_INJECTION' => 1);
  $dp =  new drugpick('dp-', $drugpick_cfg);
  $dp->draw();
  show_rp($auth[2]['ObjectID'],"detail");
}

function show_static_detail ($var) {
  global $class;
  $oid = $var['detail'] ? $var['detail'] : $var['det-in'];

  if ($oid) {
    $ord = get_rp_order($oid);
    $meds = get_meds($oid,2);
    if ($meds) {
      $class = "";
      foreach($meds as $med)
	if (check_drug($med['medis'])) {
	  $class = 'class="historical-data"';
	  break;
	}
    }
    print "<input type=hidden name=\"det-in\" value={$oid}>
           <table {$class}><tr><th>RPID<td>{$oid}";
    $name = get_emp_name($ord['記録者']);
    print "<th>記録者<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>ＲＰ名<td>{$ord['RP名']}";

    /* 内容の表示 */
    show_meds($meds,4);
    print '<tr><td colspan="4" align=left>
    <button type="submit" name="update" value="'.$oid."\">更新</button>\n";
    print '<button type="submit" name="copy" value="'.$oid."\">コピー</button>\n";
    print "<button type=\"button\"
      OnClick=\"window.open('print.php?rpid={$oid}','',
      'width=640,height=640')\">
      このRPの印刷画面を開く</button>
      <tr><td colspan=4>";
    get_order_history("RP",$oid,"pill");
    print '</table>';
  }
}

/* chech is user is selected medicine */
function check_medis($med) {
  if ($med['medis']) return true;
}

function manage_med_contents($var,$dp) {
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

  if ($var["delcont"] && count($meds) > 1)
    $meds = array_filter($meds,'check_medis');

  if ($var['new'] || !count($meds))
    unset($meds);

  if (!$var['min']) $min = -1; else $min = $var['min'];

  if ($_POST['dp-sel-id-select']) {
    $k = mx_form_unescape_key($dp->chosen());
    $local_idx = count($meds);
    $meds[$local_idx]['medid'] = $min;
    $meds[$local_idx]['medis'] = $k[0];
    $meds[$local_idx]['レセプト電算処理システム医薬品名'] = $k[1];
    /* look for the unit ID from Medis data */
    if ($k[5]) {
      $uid = get_unitid($k[5]);
      $meds[$local_idx]['unitid'] = $uid['id'];
    }
    $min = $min - 1;
  }

  /* 
  the request was posted from the left pane, get the current contents
  from the order.
  */
  if ($var["update"] || $var["copy"] || $var["dbaction"])
    $meds = get_meds($oid,2);
  $class="";
  foreach($meds as $med)
    if($med['medis'] && check_drug($med['medis']))
       $class = 'class="historical-data"';


  if (!count($meds)) return;

  print "<table $class>";
  for ($i=0, $c=count($meds); $i < $c ; $i++) {
    $medid = $meds[$i]['medid'];

    print '<tr><th align="center" colspan="4"><hr>
           <tr><th align="center" colspan="4"><font color="green">';
    if ($medid < 0) print "処方内容";
    elseif ($action == "update") print "処方内容ID{$medid}";
    elseif ($action == "copy") print "処方内容ID{$medid}のコピー";
    else  print "処方内容";
    print "</font>\n";

    if (($v = $meds[$i]["レセプト電算処理システム医薬品名"]) && !$_POST['drug'.$medid]) {
      print '<tr><td>';
      print '<input type="hidden" name="med'.$medid.'medis" value="'.$meds[$i]['medis'].'">';
      print '<input type="hidden" name="med'.$medid.'レセプト電算処理システム医薬品名" value="'.$meds[$i]['レセプト電算処理システム医薬品名'].'">';
      print $v;
    }
    print '<td><input type="text" maxlength="5" size="5"
         name="med'.($medid).'用量" value="'.($meds[$i]["用量"] == "" ? "1" : $meds[$i]["用量"]).'"'. 
         $__mx_formi_dek . '>';

    list_med("med".$medid."unitid",($meds[$i]["unitid"]),"units");
    print '<td>';
    list_med("med".$medid."shapeid",$meds[$i]["shapeid"],"shape");
    print '<td>';
    print "<tr><th nowrap valign=\"top\">コメント<td colspan=\"2\">
           <input name=\"med{$medid}その他コメント\" ".$__mx_formi_dek."
           maxlength=\"64\" size=\"32\" value=\"{$meds[$i]['その他コメント']}\">";
    print '<td>
          日数　<input type="text" maxlength="3" size="3" 
         name="med'.($medid).'日数" value="'.$meds[$i]["日数"].'"'. 
         $__mx_formi_dek . ">
           <input type=\"hidden\" name=\"med{$medid}medid\" value=\"{$medid}\">";
    print "<button type=\"submit\" name=\"delcont\" value=\"med{$medid}\">
           処方内容削除</button>";
  }
  print '</table><input type="hidden" name="min" value="'.($min).'">';

  print '<button type="submit" name="dbaction" value="' .
    ($action == "update" ? "dbupdate" : "dbnew") . '">';
  if ($action == "update") print "RPID{$oid}のアップデート";
  else print "RP登録"; 
  print '</button>';
}

function show_edit_order($var,$dp) {
  global $__mx_formi_dek;
  global $action;
  global $auth;
  global $dberror;
  global $oid;

  if (!$action) return;
  foreach ($var as $k => $v)
    if ($k == "new" || $k == "copy" || $k == "update" || 
	$k == 'delcont' ||
	$k == 'sort' || ereg("dp",$k) || ereg("drug",$k))
      $go = true;
  if ($go && !($var["dbaction"] && $dberror == '') ) {
    if ($oid && !$var['new'] && !$var['detail']) {
      $ord = get_rp_order($oid);
      $freq = get_rp_freq($oid);
    } else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";
    print "<table border=1 $class>";
    if ($action == "update") print "<tr><th nowrap>RPID<td>{$oid}";
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    if ($var['new']) $ord["RP名"] = $freq['用法'] = '';
    print "<th nowrap>記録者<td>{$rec['name']['lname']}&nbsp;{$rec['name']['fname']}
           <input type=\"hidden\" name=\"i記録者\" value=\"{$rec['id']}\">";
    print '<tr><th>ＲＰ名<td><input type="text" maxlength="30" 
         name="iRP名" '.$__mx_formi_dek.' value="' . 
      ($ord["RP名"] ? $ord["RP名"] : "") . '">
          <td>';

    $freqid = $freq ? $freq['用法'] : 
      ($var["rp-freqid"] ? $var["rp-freqid"] : "");
    list_med("rp-freqid",$freqid,"freq");
    print "</table>\n";

    manage_med_contents($var,&$dp);
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
print '<table style="border-collapse: collapse; border: hidden">
       <tr><td valign="top" width="50%" style="border-right: solid">'."\n";
show_static_order($_POST,&$dp);
print "<hr>";
show_static_detail($_POST);
print "\n<td valign=\"top\" width=\"50%\">\n";
show_edit_order($_REQUEST,&$dp);
print "</table></form>\n";

?>
</body></html>
