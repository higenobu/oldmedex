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
  return strcmp($a["����ǯ����"],$b["����ǯ����"]);
}
function type_sort ($a,$b) {
  return strcmp($b["��ʬ"],$a["��ʬ"]);
}
function id_sort ($a,$b) {
  return strcmp($a["oid"],$b["oid"]);
}
function canc_sort ($a,$b) {
  return strcmp($a["�����"],$b["�����"]);
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
      $dberror = '<font color="red">��Ͽ�˼��Ԥ��ޤ�����������Ͽ��ԤäƤ���������</font>';
    }
    flush();
  }
  elseif ($var['dbaction'] == "dbupdate") {
    if (!update_pharm_order($var)) {
      pg_query(mx_db_connect(),"rollback");
      $dberror = '<font color="red">�����˼��Ԥ��ޤ��������ٹ�����ԤäƤ���������</font>';
    }
    flush();
  }
  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  print "{$dberror}<br><input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n
         <button type=\"submit\" name=\"new\" value=\"1\">
         ������������</button>\n";
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
    print '<th><button type="submit" class="plain" name="sort" value="id">�����ID</button>';
    print '<th><button type="submit" class="plain" name="sort" value="date">����ǯ����</button>';
    print '<th><button type="submit" class="plain" name="sort" value="type">��ʬ</button>';
    print '<th><button type="submit" class="plain" name="sort" value="canc">�����</button>';
    $hnum = count($hists);
    $last = 1;
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $objid = $hist['oid'];
      if ($var['dbaction'] == "dbnew" && $last++ == $hnum)
	$oid = $objid;
      print '<button type="submit" name="detail" value="'.$objid.'">�����ID'.$objid."</button>";
      if ($hist['�����']) 
	print '<td><font color="red">'.$hist['����ǯ����'].'</font><td><font color="red">'.$hist['��ʬ'].'
               </font><td><font color="red">'.$hist['�����']."</font>\n";
      else 
	print "<td>{$hist['����ǯ����']}<td>{$hist['��ʬ']}<td>{$hist['�����']}\n";
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
           <table {$class}><tr><th>�����ID<td>{$oid}";
    $name = get_emp_name($ord['��Ͽ��']);
    print "<th>��Ͽ��<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>����ǯ����<td>{$ord['����ǯ����']}
               <th nowrap>����������<td>{$ord['����������']}
           <tr><th>��ʬ<td>{$ord['��ʬ']}
               <th>����<td>{$ord['����']}";

    $name = get_emp_name($ord['��߰�']);
    print "<tr><th>��߰�<td>{$name['lname']}&nbsp;{$name['fname']}
               <th>�����<td>{$ord['�����']}";

    /* ���Ƥ�ɽ�� */
    show_meds($meds,4);
    print '<tr><td colspan="4" align="left">
    <button type="submit" name="update" value="'.$oid."\">����</button>\n";
    print '<button type="submit" name="copy" value="'.$oid."\">���ԡ�</button>\n";
    print "<button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      ���ν����ΰ������̤򳫤�</button>
      <tr><td colspan=4>";
    get_order_history("���޽����",$oid,"chusha");
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
			=> array ("ObjectID", "�쥻�ץ��Ż����������ƥ������̾", "��̾��", "��¤���",
			 "������","����ñ��ñ��"),
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
    if ($medid < 0) print "��������";
    elseif ($action == "update") print "��������ID{$medid}";
    elseif ($action == "copy") print "��������ID{$medid}�Υ��ԡ�";
    else  print "��������";
    print "</font>\n";
    if (($v = $meds[$i]["�쥻�ץ��Ż����������ƥ������̾"]) && !$_POST['drug'.$medid]) {
      print '<tr><th>����<td>';
      print '<input type="hidden" name="med'.$medid.'medis" value="'.$meds[$i]['medis'].'">';
      print '<input type="hidden" name="med'.$medid.'�쥻�ץ��Ż����������ƥ������̾" value="'.$meds[$i]['�쥻�ץ��Ż����������ƥ������̾'].'">';
      print '<button type="submit" name="drug'.$medid. '" value="'.$v.'">'.$v. "</button>";
    }
    elseif (($var['drug'.$medid] || !$dp[$i]->chosen()) && (!$var['copy'] && 
	!$var['update'])) {
      $dp[$i]->draw();
    }
    elseif ($dp[$i]->chosen()) {
      print '<tr><th>����<td>';
      $k = mx_form_unescape_key($dp[$i]->chosen());
      print '<input type="hidden" name="med'.$medid.'medis" value="'.$k[0].'">';
      print '<input type="hidden" name="med'.$medid.'�쥻�ץ��Ż����������ƥ������̾" value="'.$k[1].'">';
      print '<button type="submit" name="drug'.$medid.'" value="'.$k[1].'">'.$k[1] . "</button>";
    }
    print '<tr><th>����<td><input type="text" maxlength="3" size="3" 
         name="med'.($medid).'����" value="'.$meds[$i]["����"].'"'. 
         $__mx_formi_dek . '>';
    print '<tr><th>����<td><input type="text" maxlength="5" size="5"
         name="med'.($medid).'����" value="'.($meds[$i]["����"] == "" ? "1" : $meds[$i]["����"]).'"'. 
         $__mx_formi_dek . '>';
    /* look for the unit ID from Medis data */
    if ($k[5]) $uid = get_unitid($k[5]);
    list_med("med".$medid."unitid",($uid['id'] == "" ? $meds[$i]["unitid"] : $uid['id']),"units");
    print '<td><td><tr><th>�굻<td>';
    list_med("med".$medid."placeid",$meds[$i]["placeid"],"place");
    $def_freq = $meds[$i]['�����ˡ'] ? $meds[$i]['�����ˡ'] : 
      (($i-1 >= 1) ? $meds[$i-1]["�����ˡ"] : $meds[0]['�����ˡ']);
    print '<td><td><tr><th>��ˡ<td>
           <input type="text" name="med'.$medid.'�����ˡ" value="'.
           $def_freq.'" '.$__mx_formi_dek.' size="22" maxlength="22">
           <tr><th nowrap valign="top">����¾������<td colspan="3">
           <textarea name="med'.$medid.'����¾������" '.$__mx_formi_dek.'
           cols="32" rows="2">'.$meds[$i]['����¾������'].'</textarea>';
    print "<tr><td colspan=\"4\">
           <input type=\"hidden\" name=\"med{$medid}medid\" value=\"{$medid}\">";
    if ($c > 1)
      print "<button type=\"submit\" name=\"delcont\" value=\"med{$medid}\">
           �������ƺ��</button>";
  }
  /*
  add content request will have negative IDs in order to separate from
  the current contents in the database.
  */
  print '</table><p>
  <button type="submit" name="addcont" value="'.($min - 1).'">���������Ƥ��ɲ�
  </button><p>';

  print '<button type="submit" name="dbaction" value="' .
    ($action == "update" ? "dbupdate" : "dbnew") . '">';
  if ($action == "update") print "�����ID{$oid}�Υ��åץǡ���";
  else print "������Ͽ"; 
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
    if ($action == "copy") { $ord["����ǯ����"] = $ord["����������"] = date("Y-m-d"); }
    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";
    print "<table $class>";
    if ($action == "update") print "<tr><th>�����ID<td>{$oid}";
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    print "<tr><th>��Ͽ��<td>{$rec['name']['lname']}&nbsp;{$rec['name']['fname']}
           <input type=\"hidden\" name=\"i��Ͽ��\" value=\"{$rec['id']}\">";
    print '<td><td><tr><th>����ǯ����<td><input type="text" maxlength="10" 
         name="i����ǯ����" '.$__mx_formi_dek.' value="' . 
        ($ord["����ǯ����"] ? $ord["����ǯ����"] : date("Y-m-d")) . '"><td><td>
         <tr><th>����������<td><input type="text" maxlength="10" 
         name="i����������" '.$__mx_formi_dek.' value="' . 
        ($ord["����������"] ? $ord["����������"] : date("Y-m-d")) . '"><td><td>
         <tr><th>����<td><input type="text" maxlength="3" size="3"
         name="i����" '.$__mx_formi_dek.' value="' . $ord["����"] . '"><td><td>
         <td><td><tr><th>��ʬ<td><select name="i��ʬ" '.$__mx_formi_dek.' >
         <option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����
         <option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����
         <option value="����" '.($ord['��ʬ'] == "����" ? "selected" : "").'>����
         </select>
         <td><td><tr><th>�����<td><input type="text" maxlength="10" 
         name="i�����" value="' . $ord["�����"] . '" '.$__mx_formi_dek.'>
           <th>��߰�<td>
         <input type="hidden" name="i����" value="'.$pid.'">';
    list_doctors("i��߰�",$ord['��߰�'],$pid,"all0",$rec);

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
$stmt = ('SELECT "����ID" FROM "������Ģ" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['����ID'];

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
