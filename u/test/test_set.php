<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
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
$uri = $_SERVER['SCRIPT_NAME'];

print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
print "<hr>";

function show_static_order($var,$tp) {
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

  print "<button type=\"submit\" name=\"new\" value=\"1\">
         �����������åȺ���</button>\n";

  $testpick_cfg = array('LIST_IDS' 
			=> array ("����̾ (ά��̾)",
				  "����̾ (���ܸ�)",
				  "ñ��̾",
				  "ObjectID"));
    
  $tp =  new testpick('tp-', $testpick_cfg);
  $tp->draw();
  show_tp($auth[2]['ObjectID'],"detail",1);
}

function show_static_detail ($var) {
  $oid = $var['detail'] ? $var['detail'] : $var['det-in'];

  if ($oid) {
    $ord = get_tp("",$oid);
    $meds = get_tp_tests($oid,1);
    print "<input type=hidden name=\"det-in\" value={$oid}>
           <table><tr><th>SETID<td>{$oid}";
    $name = get_emp_name($ord['��Ͽ��']);
    print "<th>��Ͽ��<td>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th nowrap>SET̾<td>{$ord['SET̾']}";

    /* ���Ƥ�ɽ�� */
    print '<tr><th colspan="4" align="center">��������';
    show_tp_tests($meds);
    print '<tr><td colspan="4" align=left>
    <button type="submit" name="update" value="'.$oid."\">����</button>\n";
    print '<button type="submit" name="copy" value="'.$oid."\">���ԡ�</button>\n";
    print "<button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      ����SET�ΰ������̤򳫤�</button>
      <tr><td colspan=4>";
    get_order_history("TSET",$oid,"test");
    print '</table>';
  }
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

  if ($var['new'] || !count($meds))
    unset($meds);

  if (!$var['min']) $min = -1; else $min = $var['min'];

  if ($_POST['tp-sel-id-select']) {
    $k = mx_form_unescape_key($tp->chosen());
    $local_idx = count($meds);
    $meds[$local_idx]['medid'] = $min;
    $meds[$local_idx]['����̾(ά��̾)'] = $k[0];
    $meds[$local_idx]['����̾(���ܸ�)'] = $k[1];
    $meds[$local_idx]['ñ��̾'] = $k[2];
    $meds[$local_idx]['medis'] = $k[3];
    $min = $min - 1;
  } elseif ($var['tp-select']) {
    $tpmeds = get_tests($var['tp-select'],2);
    $tp_index = count($meds);
    foreach($tpmeds as $tpitem) {
      $meds[$tp_index]['medid'] = $tpitem['medid'];
      $meds[$tp_index]['����̾(ά��̾)'] = $tpitem['����̾(ά��̾)'];
      $meds[$tp_index]['shape'] = $tpitem['shape'];
      $meds[$tp_index]['ñ��̾'] = $tpitem['ñ��̾'];
      $meds[$tp_index]['freq'] = $tpitem['freq'];
      $meds[$tp_index]['freqid'] = $tpitem['freqid'];
      $meds[$tp_index]['����'] = $tpitem['����'];
      $meds[$tp_index]['��ˡ'] = $tpitem['��ˡ'];
      $meds[$tp_index]['����¾������'] = $rpitem['����¾������'];
      $tp_index++;
    }
    $min = $min - count($tpmeds);
  }

  /* 
  the request was posted from the left pane, get the current contents
  from the order.
  */
  if ($var["update"] || $var["copy"] || $var["dbaction"])
    $meds = get_tp_tests($oid,1);

  if (!count($meds)) return;

  print "<table>";
  for ($i=0, $c=count($meds); $i < $c ; $i++) {
    $medid = $meds[$i]['medid'];

    print '<tr><th align="center" colspan="4"><hr>
           <tr><th align="center" colspan="4"><font color="green">';
    if ($medid < 0) print "��������";
    elseif ($action == "update") print "��������ID{$medid}";
    elseif ($action == "copy") print "��������ID{$medid}�Υ��ԡ�";
    else  print "��������";
    print "</font>\n";

    if (($v = $meds[$i]["����̾(ά��̾)"]) && !$_POST['drug'.$medid]) {
      print '<tr><th>';
      print '<input type="hidden" name="med'.$medid.
	'����̾(ά��̾)" value="'.$meds[$i]['����̾(ά��̾)'].'">';
      print $v;
    }
    print '<td><input type="text" maxlength="3" size="3"
         name="med'.($medid).'����" value="'.
      ($meds[$i]["����"] == "" ? "1" : $meds[$i]["����"]).'"'. 
      $__mx_formi_dek . ">".$meds[$i]['ñ��̾'].
      '<input type="hidden" name="med'.($medid).'medis" value="'.
      ($meds[$i]["medis"] ? $meds[$i]["medis"] : "").'">
       <input type="hidden" name="med'.($medid).'ñ��̾" value="'.
      ($meds[$i]["ñ��̾"] ? $meds[$i]["ñ��̾"] : "").'">'."\n";

    print '<td>';
    list_med("med".$medid."shapeid",$meds[$i]["shapeid"],"shape");
    print '<td>';
    print "<tr><th nowrap valign=\"top\">������<td colspan=\"2\">
           <textarea name=\"med{$medid}����¾������\" ".$__mx_formi_dek."
           cols=\"32\" rows=\"2\">{$meds[$i]['����¾������']}</textarea>";
    print "<td>
           <input type=\"hidden\" name=\"med{$medid}medid\" value=\"{$medid}\">";

    print "<button type=\"submit\" name=\"delcont\" value=\"med{$medid}\">
           �������ƺ��</button>";
  }
  print '</table><input type="hidden" name="min" value="'.($min).'">';

  print '<button type="submit" name="dbaction" value="' .
    ($action == "update" ? "dbupdate" : "dbnew") . '">';
  if ($action == "update") print "SET ID $oid �Υ��åץǡ���";
  else print "SET��Ͽ"; 
  print '</button>';
}

function show_edit_order($var,$tp) {
  global $__mx_formi_dek;
  global $action;
  global $auth;
  global $oid;

  if (!$action) return;
  foreach ($var as $k => $v)
    if ($k == "new" || $k == "copy" || $k == "update" || 
	$k == 'delcont' ||
	$k == 'sort' || ereg("tp",$k) || ereg("drug",$k))
      $go = true;
  if ($go && $var["dbaction"] != "dbnew") {
    if ($oid && !$var['new'] && !$var['detail']) {
      $ord = get_tp("",$oid);
    } else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;

    print "<input type=hidden name=oid value={$oid}>";
    print "<input type=hidden name=action value={$action}>";
    print "<table border=1>";
    if ($action == "update") print "<tr><th nowrap>SETID<td>{$oid}";
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    print "<th nowrap>��Ͽ��<td>{$rec['name']['lname']}&nbsp;{$rec['name']['fname']}
           <input type=\"hidden\" name=\"i��Ͽ��\" value=\"{$rec['id']}\">";
    print '<tr><th>SET̾<td><input type="text" maxlength="30" 
         name="iSET̾" '.$__mx_formi_dek.' value="' . 
      ($ord["SET̾"] ? $ord["SET̾"] : "") . '">
          <td>';
    $freqid = $freq ? $freq['��ˡ'] : 
      ($var["tp-freqid"] ? $var["tp-freqid"] : "");
    list_med("tp-freqid",$freqid,"freq");
    print "</table>\n";

    manage_med_contents($var,&$tp);
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
print '<table style="border-collapse: collapse; border: hidden">
       <tr><td valign="top" width="50%" style="border-right: solid">'."\n";
show_static_order($_POST,&$tp);
print "<hr>";
show_static_detail($_POST);
print "\n<td valign=\"top\" width=\"50%\">\n";
show_edit_order($_REQUEST,&$tp);
print "</table></form>\n";

?>
</body></html>
