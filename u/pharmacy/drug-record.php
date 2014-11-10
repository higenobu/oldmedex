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
    print '<table><tr><th>����ID<th>��ʧǯ����
                    <input type="hidden" name="ym" value="'.$ym.'"><td>';
    foreach ($drugs as $drug) {
      $id = $drug['ID'];
      print '<tr><td>
             <button type="submit" name="detail" value="' . $id . 
	"\">����ID{$id}</button>" .
	"<td>{$drug['��ʧǯ����']}\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail ($var) {
  global $id;

  if ($id) $drug = get_drug_with_id($id);
  if ($drug) {
    print '<table>
       <tr><th>����ID<td>'.$drug["ID"].    
      '<tr><th>̾��<td>'.$drug["�쥻�ץ��Ż����������ƥ������̾"].
      '<tr><th>��ʧ<td>'.($drug["��ʧ"] == "I" ? "��" : 
			  ($drug["��ʧ"] == "O" ? "ʧ" : "")).
      '<tr><th>��ʧǯ����<td>'.$drug["��ʧǯ����"].
      '<tr><th>��ʧ����<td>'.$drug["��ʧ����"].
      '<tr><th>�Ŀ���<td>'.$drug["�Ŀ���"].
      '<tr><th>����<td>'.$drug["����"].
      '<tr><td colspan="2">
         <button type="submit" name="new" value="new">������Ͽ</button>
         <button type="submit" name="update" value="'.$id.'">����</button>
         <button type="submit" name="copy" value="'.$id.'">���ԡ�</button>
      </table>';
  }
}

function show_edit_prescription($var) {
  global $__mx_formi_dek;
  global $action;
  global $id;
  $pcols = array("ObjectID", "�쥻�ץ��Ż����������ƥ������̾");
  $cfg = _lib_u_pharmacy_csl_config();
  $cfg['LIST_IDS'] = $pcols;

  $dbaction = $action ? $action : $var['dbaction'];

  $dp =  new list_of_narcotic_or_poison('����', $cfg);

  if ($action == "update" || $action == "copy")
    $data = get_drug_with_id($id);
  elseif ($action == "new")
    $data = NULL;
  else
    foreach ($var as $key => $val)
      if (ereg("^i.*",$key)) $data[substr($key,1)] = $val;
  print '<table>
       <tr><th>̾��<input type="hidden" name="ID" value="'.$id.'"><td>';
  
  if ($dp->chosen() || $action =="update" || $action=="copy" ||
      (($var['detail'] || $var['dbup'] || $var['dbnew']) && $var['i����'])) {
    $k = mx_form_unescape_key($dp->chosen());
    print '<button type="submit" name="����" value="'.
      ($k[1]?$k[1]:$data["�쥻�ץ��Ż����������ƥ������̾"]).'">'.
      ($k[1]?$k[1]:$data["�쥻�ץ��Ż����������ƥ������̾"]). '</button>
      <input type="hidden" name="i�쥻�ץ��Ż����������ƥ������̾" value="'.
      ($k[1]?$k[1]:$data["�쥻�ץ��Ż����������ƥ������̾"]).'">
      <input type="hidden" name="i����" value="'.
      ($k[0]?$k[0]:$data["����"]).'">';
  } else
    $dp->draw();
  
  print '<tr><th>��ʧ<td>��<input type="radio"  name="i��ʧ" value="I" '.
    ($data["��ʧ"] == "I" ? "checked " : "").$__mx_formi_dek.'>
                     ��ʧ<input type="radio"  name="i��ʧ" value="O" '.
    ($data["��ʧ"] == "O" ? "checked " : "").$__mx_formi_dek.'>
       <tr><th>��ʧǯ����<br>(yyyy-mm-dd)
       <td><input type="text" size="10" maxlength="10" name="i��ʧǯ����"
       value="'.($dbaction == "copy" || $dbaction == "update" ? 
		 $data["��ʧǯ����"] : date("Y-m-d")).'" '
    .$__mx_formi_dek.'>
       <tr><th>��ʧ����
       <td><input type="text" size="4" maxlength="4" name="i��ʧ����" 
       value="'.$data["��ʧ����"].'" '
    .$__mx_formi_dek.'>
       <tr><th>�Ŀ���
           <td><input type="text" size="4" maxlength="4" name="i�Ŀ���"
       value="'.$data["�Ŀ���"].'" '
    .$__mx_formi_dek.'>
       <tr><th>����
           <td><input type="text" size="16" maxlength="16" name="i����"
       value="'.$data["����"].'" '
    .$__mx_formi_dek.'>
       <tr><td>';

  if ($dbaction == "update")
    print '<input type="hidden" name="dbaction" value="update">
             <input type="hidden" name="iID" value="'.$data['ID'].'">
             <button type="submit" name="dbup" value="�ѹ�">
              ����ID'.$data['ID'].'���ѹ�</button>';
  else
    print '<button type="submit" name="dbnew" value="��Ͽ">��Ͽ</button>';
  print '</table>';
}

function do_database($var,&$ym) {
  global $auth;
  $var["i��ʧǯ����"] = mb_convert_kana($var["i��ʧǯ����"],'a','EUC-JP');
  $var["i��ʧ����"] = mb_convert_kana($var["i��ʧ����"],'n','EUC-JP');
  $var["i�Ŀ���"] = mb_convert_kana($var["i�Ŀ���"],'n','EUC-JP');

  if ($var["dbnew"] || $var["dbup"])
    if (!$var["i����"]) {
      print '<font color="red"><blink>���ޤ���ꤷ�Ƥ���������</blink></font>';
      return;
    } elseif (!$var["i��ʧ"]) {
      print '<font color="red"><blink>��ʧ����ꤷ�Ƥ���������</blink></font>';
      return;
    }
  if ($var["dbnew"]) {
    foreach ($var as $k => $v) {
      if ($k == "i�쥻�ץ��Ż����������ƥ������̾") continue;
      if (ereg("^i.*",$k)) $new[substr($k,1)] = $v;
    }
    $new['CreatedBy'] = $var['u'];
    $new['���޻�'] = $auth[2]['ObjectID'];
    $insstr =  make_insert_str("���Ǵ���",$new,false);
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
      if ($k == "i�쥻�ץ��Ż����������ƥ������̾") continue;
      if ($k == "iID") $up['act'] = $v;
      elseif (ereg("^i.*",$k)) $up[substr($k,1)] = $v;
    }
    $up['���޻�'] = $auth[2]['ObjectID'];
    $up['CreatedBy'] = $var['u'];
    if (diff_contents("���Ǵ���",$up)) {
      make_update_str("���Ǵ���",$up,$upstr,$insstr);
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
