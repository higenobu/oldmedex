<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/csl.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';
$action = $_POST['copy'] ? "copy" : ($_POST['update'] ? "update" : 
        			     ($_POST['new'] ? "new" : ""));
$dbaction = $_POST['dbaction'];
$id = $_POST['detail'] ? $_POST['detail'] :
($_POST['copy'] ? $_POST['copy'] :
 ($_POST['update'] ? $_POST['update'] : $_POST['ID']));
$uri = $_SERVER['SCRIPT_NAME'];

print '<table border="0"><tr><td valign="top"  width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
print '<td valign="top" align="left">';

function show_static_order($drugs,$ym,$var) {

  if ($drugs) {
    print '<table><tr><th>管理ID<th>受払年月日
                    <input type="hidden" name="ym" value="'.$ym.'"><td>';
    foreach ($drugs as $drug) {
      $id = $drug['ID'];
      print '<tr><td>
             <button type="submit" name="detail" value="' . $id . 
	"\">管理ID{$id}</button>" .
	"<td>{$drug['受払年月日']}\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail ($var) {
  global $id;

  if ($id) $drug = get_drug_with_id($id);
  if ($drug) {
    print '<table>
       <tr><th>管理ID<td>'.$drug["ID"].    
      '<tr><th>名称<td>'.$drug["レセプト電算処理システム医薬品名"].
      '<tr><th>受払<td>'.($drug["受払"] == "I" ? "受" : 
			  ($drug["受払"] == "O" ? "払" : "")).
      '<tr><th>受払年月日<td>'.$drug["受払年月日"].
      '<tr><th>受払数量<td>'.$drug["受払数量"].
      '<tr><th>残数量<td>'.$drug["残数量"].
      '<tr><th>備考<td>'.$drug["備考"].
      '<tr><td colspan="2">
         <button type="submit" name="new" value="new">新規登録</button>
         <button type="submit" name="update" value="'.$id.'">更新</button>
         <button type="submit" name="copy" value="'.$id.'">コピー</button>
      </table>';
  }
}

function show_edit_prescription($var) {
  global $__mx_formi_dek;
  global $action;
  global $id;
  $pcols = array("ObjectID", "レセプト電算処理システム医薬品名");
  $cfg = _lib_u_pharmacy_csl_config();
  $cfg['LIST_IDS'] = $pcols;

  $dbaction = $action ? $action : $var['dbaction'];

  $dp =  new list_of_narcotic_or_poison('薬剤', $cfg);

  if ($action == "update" || $action == "copy")
    $data = get_drug_with_id($id);
  elseif ($action == "new")
    $data = NULL;
  else
    foreach ($var as $key => $val)
      if (ereg("^i.*",$key)) $data[substr($key,1)] = $val;
  print '<table>
       <tr><th>名称<input type="hidden" name="ID" value="'.$id.'"><td>';
  
  if ($dp->chosen() || $action =="update" || $action=="copy" ||
      (($var['detail'] || $var['dbup'] || $var['dbnew']) && $var['i薬剤'])) {
    $k = mx_form_unescape_key($dp->chosen());
    print '<button type="submit" name="薬剤" value="'.
      ($k[1]?$k[1]:$data["レセプト電算処理システム医薬品名"]).'">'.
      ($k[1]?$k[1]:$data["レセプト電算処理システム医薬品名"]). '</button>
      <input type="hidden" name="iレセプト電算処理システム医薬品名" value="'.
      ($k[1]?$k[1]:$data["レセプト電算処理システム医薬品名"]).'">
      <input type="hidden" name="i薬剤" value="'.
      ($k[0]?$k[0]:$data["薬剤"]).'">';
  } else
    $dp->draw();
  
  print '<tr><th>受払<td>受<input type="radio"  name="i受払" value="I" '.
    ($data["受払"] == "I" ? "checked " : "").$__mx_formi_dek.'>
                     　払<input type="radio"  name="i受払" value="O" '.
    ($data["受払"] == "O" ? "checked " : "").$__mx_formi_dek.'>
       <tr><th>受払年月日<br>(yyyy-mm-dd)
       <td><input type="text" size="10" maxlength="10" name="i受払年月日"
       value="'.($dbaction == "copy" || $dbaction == "update" ? 
		 $data["受払年月日"] : date("Y-m-d")).'" '
    .$__mx_formi_dek.'>
       <tr><th>受払数量
       <td><input type="text" size="4" maxlength="4" name="i受払数量" 
       value="'.$data["受払数量"].'" '
    .$__mx_formi_dek.'>
       <tr><th>残数量
           <td><input type="text" size="4" maxlength="4" name="i残数量"
       value="'.$data["残数量"].'" '
    .$__mx_formi_dek.'>
       <tr><th>備考
           <td><input type="text" size="16" maxlength="16" name="i備考"
       value="'.$data["備考"].'" '
    .$__mx_formi_dek.'>
       <tr><td>';

  if ($dbaction == "update")
    print '<input type="hidden" name="dbaction" value="update">
             <input type="hidden" name="iID" value="'.$data['ID'].'">
             <button type="submit" name="dbup" value="変更">
              管理ID'.$data['ID'].'の変更</button>';
  else
    print '<button type="submit" name="dbnew" value="登録">登録</button>';
  print '</table>';
}

function do_database($var,&$ym) {
  global $auth;
  $var["i受払年月日"] = mb_convert_kana($var["i受払年月日"],'a','EUC-JP');
  $var["i受払数量"] = mb_convert_kana($var["i受払数量"],'n','EUC-JP');
  $var["i残数量"] = mb_convert_kana($var["i残数量"],'n','EUC-JP');

  if ($var["dbnew"] || $var["dbup"])
    if (!$var["i薬剤"]) {
      print '<font color="red"><blink>薬剤を指定してください。</blink></font>';
      return;
    } elseif (!$var["i受払"]) {
      print '<font color="red"><blink>受払を指定してください。</blink></font>';
      return;
    }
  if ($var["dbnew"]) {
    foreach ($var as $k => $v) {
      if ($k == "iレセプト電算処理システム医薬品名") continue;
      if (ereg("^i.*",$k)) $new[substr($k,1)] = $v;
    }
    $new['CreatedBy'] = $var['u'];
    $new['薬剤師'] = $auth[2]['ObjectID'];
    $insstr =  make_insert_str("麻毒管理",$new,false);
    $ret = false;
    $con = mx_db_connect();
    pg_query($con,"begin") or $ret = true;
    pg_query($con,$insstr) or $ret = true;
    pg_query($con,"commit") or $ret = true;
    if ($ret) pg_query($con,"rollback");
    flush();
    $ym = date('Y-m');
  } elseif ($var["dbup"]) {
    foreach ($var as $k => $v) {
      if ($k == "iレセプト電算処理システム医薬品名") continue;
      if ($k == "iID") $up['act'] = $v;
      elseif (ereg("^i.*",$k)) $up[substr($k,1)] = $v;
    }
    $up['薬剤師'] = $auth[2]['ObjectID'];
    $up['CreatedBy'] = $var['u'];
    if (diff_contents("麻毒管理",$up)) {
      make_update_str("麻毒管理",$up,$upstr,$insstr);
      $ret = false;
      $con = mx_db_connect();
      pg_query($con,"begin") or $ret = true;
      pg_query($con,$insstr) or $ret = true;
      pg_query($con,$upstr) or $ret = true;
      pg_query($con,"commit") or $ret = true;
      if ($ret) pg_query($con,"rollback");
      $ym = date('Y-m');
    }
    flush();
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
if ($_POST['dbnew'] || $_POST['dbup']) {
  print "</table><hr>";
  do_database($_POST,$ym);
}
$drugs = search_drugs("",$ym);
print "</table><hr>";
print '<table style="border-collapse: collapse; border: hidden">
       <tr><td valign="top" width="50%" style="border-right: solid">'."\n";
show_static_order($drugs,$ym,$_POST);
print "<hr>";
show_static_detail($_POST);
print "\n<td valign=\"top\" width=\"50%\">\n";
show_edit_prescription($_REQUEST);
print "</table></form>\n";

?>
</body></html>
