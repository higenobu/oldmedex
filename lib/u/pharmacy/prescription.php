<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';
$class = "";
$oid = $_POST['detail'] ? $_POST['detail'] : $_REQUEST['oid'];
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];

print '<table border="0"><tr><td valign="top"  width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo();
print '<td valign="top" align="left">';

global $isNurse;
function show_static_order($pat,$ym,$var) {
  global $isNurse;

  if ($var['dbaction']) {
    if (!record_pharm($var,$isNurse))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }
  $pid = $pat['ID'] ? $pat['ID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  print "����̾��$patl $patf <p>\n
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n";
  if ($isNurse) $hists = get_nurse_history($pid,$ym);
  else $hists = get_history($pid,$ym,3);
  if ($hists) {
    print "<table><tr><th>�����ID<th>����ǯ����<th>��ʬ";
    if ($isNurse) print "<th>��Ͽ��<th>������\n"; 
    else print "<th>�����\n";
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['oid'];
      print '<button type="submit" name="detail" value="' . $oid . 
	"\">�����ID{$oid}</button>";
      if ($isNurse) {
	$name = get_emp_name($hist['�ǸϿ��']);
	print "<td>{$hist['����ǯ����']}<td>{$hist['��ʬ']}
               <td>{$name['lname']}&nbsp;{$name['fname']}
               <td>".substr($hist['�Ǹ�ռ»�'],0,10)."\n";
      }
      elseif ($hist['�����'])
	print '<td><font color="red">'.$hist['����ǯ����'].'</font><td><font color="red">'.
          $hist['��ʬ'].'</font><td><font color="red">'.$hist['�����']."</font>\n";
      else print 
	"<td>{$hist['����ǯ����']}<td>{$hist['��ʬ']}<td>\n";
    }
    print "</table><p>\n";
  } else
    echo "���δ��Ԥˤϡ����޽���䵤���Ф���Ƥ��ޤ���";
}

function show_static_detail() {
  global $class;
  global $oid;
  global $isNurse;

  if ($oid) {
    $ord = get_pharm_order($oid);
    $injection = $ord['���'];
    if ($injection)
      $meds = get_meds($oid,1);
    else
      $meds = get_meds($oid,0);
    if ($meds) {
      $class = "";
      foreach($meds as $med) 
	if (check_drug($med['medis'])) {
	  $class = 'class="historical-data"';
	  break;
	}
    }
    print "<input type=hidden name=oid value=".$oid.">
           <table {$class}><tr><th align=left>�����ID<td>{$oid}";
    if ($isNurse) $name = get_emp_name($ord['�ǸϿ��']);
    else $name = get_emp_name($ord['���޵�Ͽ��']);
    print "<th align=left>��Ͽ��
                 <td align=left>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th align=left nowrap>����ǯ����<td align=left>{$ord['����ǯ����']}
               <th align=left nowrap>����������<td align=left>{$ord['����������']}
           <tr><th align=left>��ʬ<td align=left>{$ord['��ʬ']}
               <th align=left>����<td align=left>{$ord['����']}";
    if ($isNurse)
      print "<tr><th align=left>������<td colspan=3>{$ord['�Ǹ�ռ»�']}";
    else {
      $name = get_emp_name($ord['��߰�']);
      print "<tr><th align=left>��߰�
                 <td align=left>{$name['lname']}&nbsp;{$name['fname']}
               <th align=left>�����<td align=left>{$ord['�����']}";
    }
    $name = get_emp_name($ord['Ĵ�����޻�']);
    print "<tr><th align=left>Ĵ�����޻�
                 <td align=left>{$name['lname']}&nbsp;{$name['fname']}
               <th align=left>Ĵ��ǯ����<td align=left>{$ord['Ĵ��ǯ����']}";

    /* ���Ƥ�ɽ�� */
    show_meds($meds,4);
    print "<tr><td colspan=4>";
    get_order_history("���޽����",$oid,"kango");
    print "</table>";
  }
}

function draw_if_drug ($medid,$medis,$oid) {
  global $__mx_formi_dek;
  
  if ($medis && ($v = check_drug($medis))) {
    if ($v & 3) {
      if (($drug = get_drug("���Ǵ���",$medis,$oid)))
	print '<input type="hidden" name="dmed'.$medid.'drugid" 
               value="'.$drug["ID"].'">';
      print '<tr><th><font color="red">���Ǵ���</font><td>
             <input type="hidden" name="dmed'.$medid.'����" value="'.$medis.'">
             <input type="hidden" name="dmed'.$medid.'�����" value="'.$oid.'">
             <tr><th><font color="red">��ʧǯ����</font>
               <td><input type="text" name="dmed'.$medid.'��ʧǯ����" value="'
	.($drug['��ʧǯ����'] ? $drug['��ʧǯ����'] : date("Y-m-d")).'" '
	.$__mx_formi_dek.'>
               <tr><th><font color="red">��ʧ����</font>
               <td><input type="text" name="dmed'.$medid.'��ʧ����" value="'
	.($drug['��ʧ����'] ? $drug['��ʧ����'] : "").'" '
	.$__mx_formi_dek.'>
               <tr><th><font color="red">�Ŀ���</font>
               <td><input type="text" name="dmed'.$medid.'�Ŀ���" value="' 
	.($drug['�Ŀ���'] ? $drug['�Ŀ���'] : "").'" '
	.$__mx_formi_dek.'>
               <tr><th><font color="red">����</font>
               <td><input type="text" name="dmed'.$medid.'����" value="'
	.($drug['����'] ? $drug['����'] : "").'" '
	.$__mx_formi_dek.">\n";
    }
    if ($v & 12) {
      if (($blood = get_drug("�����ʪͳ�����ʻ��ѵ�Ͽ",$medis,$oid)))
	print '<input type="hidden" name="bmed'.$medid.'bloodid" 
               value="'.$blood["ID"].'">';
      print '<tr><th><font color="purple">��ʪͳ������</font><td>
             <input type="hidden" name="bmed'.$medid.'�����" value="'.$oid.'">
             <input type="hidden" name="bmed'.$medid.'����" value="'.$medis.'">
             <tr><th><font color="purple">��¤�ֹ�</font>
               <td><input type="text" name="bmed'.$medid.'��¤�ֹ�" value="'
	.($blood['��¤�ֹ�'] ? $blood['��¤�ֹ�'] : "").'" '
	.$__mx_formi_dek.">\n";
    }
  }
}

function show_edit_prescription($var) {
  global $__mx_formi_dek;
  global $auth;
  global $class;
  global $isNurse;
  global $oid;

  $pid = $var['pid'];
  if ($pid) {
    if ($oid) {
      $ord = get_pharm_order($oid);
      $injection = $ord['���'];
    }
    else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    if ($ord["�����"]) {
      print '<font color="red">���ν���䵤ˤϡ�������פ����˻��ꤵ��Ƥ��ޤ���</font>';
      return;
    }
    print '<input type="hidden" name="i����" value="'.$pid.'">
           <table '.$class.'>';
    print "<tr><th align=left>�����ID<td align=left>{$oid}".
            '<input type="hidden" name="iact" value="'.$oid.'">
             <input type="hidden" name="oid" value="'.$oid.'">';
    if ($ord)
      $sname = get_emp_name($ord['��߰�']);

    $rname = get_emp_name($auth[2]['ObjectID']);
    print "<tr><th>����ǯ����<td>{$ord['����ǯ����']}
	       <th>����������<td>{$ord['����������']}
	   <tr><th>����<td>{$ord['����']}<th>��ʬ<td>{$ord['��ʬ']}
	   <tr><th>��Ͽ��<td>{$rname['lname']}&nbsp;{$rname['fname']}
               <th>��߰�<td>{$sname['lname']}&nbsp;{$sname['fname']}<td><td>";
    if ($isNurse) {
      $pname = get_emp_name($ord['Ĵ�����޻�']);
      print '<tr><th>Ĵ��ǯ����<td>'.$ord['Ĵ��ǯ����'].
                '<th>Ĵ�����޻�<td>'.$pname['lname'].'&nbsp;'.$pname['fname'].
            '<tr><th>������<td><input type="text" size="33" maxlength="33"
             name="i�Ǹ�ռ»�" '.$__mx_formi_dek.' value="'.
	$ord["�Ǹ�ռ»�"].'">
      <input type="hidden" name="i�ǸϿ��" value="'.
	$auth[2]['ObjectID'].'">';
    }
    else {
      print '<tr><th>Ĵ��ǯ����<td><input type="text" maxlength="10" 
             name="iĴ��ǯ����" '.$__mx_formi_dek.' value="'.
	     ($ord["Ĵ��ǯ����"]?$ord["Ĵ��ǯ����"]:date("Y-m-d")). '">
             <th>Ĵ�����޻�<td>
             <input type="hidden" name="i���޵�Ͽ��" value="'.
	$auth[2]['ObjectID'].'">';
      list_pharmacists("iĴ�����޻�",$ord['Ĵ�����޻�']);
    }
    print '</table>';

    if ($injection) $meds = get_meds($oid,1);
    else $meds = get_meds($oid,0);

    print "<table $class>";
    foreach ($meds as $med) {
      print '<tr><th colspan="2"><font color="green">
               ���������ID��' . $med['medid'] . '</font>'.
	"<tr><td colspan=\"2\">{$med['�쥻�ץ��Ż����������ƥ������̾']}
             &nbsp;{$med['����']}{$med['����ñ��']}&nbsp;".
             ($injection ? $med['�굻'] : $med['��Ϳ����']).
	"&nbsp;{$med['��ˡ']}{$med['�����ˡ']}";
      if ($med['����¾������'])
	print "<tr><td colspan=\"2\">�����ȡ�{$med['����¾������']}";
      if (!$isNurse) draw_if_drug ($med['medid'],$med['medis'],$oid);
    }
    if ($isNurse)
      print '<tr><td><button type="submit" name="dbaction" 
                   value="'.$oid.'">��Ͽ</button>';
    else 
      print '<tr><td><button type="submit" name="dbaction" 
                   value="'.$oid.'">��Ͽ</button>';
    print '</table>';
  }
}

if (!$pid && !($pat = search_patient("",$ym))) {
  print '</table>';
  return;
} else {
  $pid = $pat ? $pat['ObjectID'] :$pid;
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
  show_static_order($pat,$ym,$_POST,$isNurse);
  print "<hr>";
  show_static_detail($_POST);
  print "\n<td valign=\"top\" width=\"50%\">\n";
  show_edit_prescription($_REQUEST);
  print "</table></form>\n";
}
?>
</body></html>
