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
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  print "患者名　$patl $patf <p>\n
         <input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n";
  if ($isNurse) $hists = get_nurse_history($pid,$ym);
  else $hists = get_history($pid,$ym,3);
  if ($hists) {
    print "<table><tr><th>処方箋ID<th>処方年月日<th>区分";
    if ($isNurse) print "<th>記録者<th>コメント\n"; 
    else print "<th>停止日\n";
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['oid'];
      print '<button type="submit" name="detail" value="' . $oid . 
	"\">処方箋ID{$oid}</button>";
      if ($isNurse) {
	$name = get_emp_name($hist['看護記録者']);
	print "<td>{$hist['処方年月日']}<td>{$hist['区分']}
               <td>{$name['lname']}&nbsp;{$name['fname']}
               <td>".substr($hist['看護師実施'],0,10)."\n";
      }
      elseif ($hist['停止日'])
	print '<td><font color="red">'.$hist['処方年月日'].'</font><td><font color="red">'.
          $hist['区分'].'</font><td><font color="red">'.$hist['停止日']."</font>\n";
      else print 
	"<td>{$hist['処方年月日']}<td>{$hist['区分']}<td>\n";
    }
    print "</table><p>\n";
  } else
    echo "この患者には、薬剤処方箋が提出されていません。";
}

function show_static_detail() {
  global $class;
  global $oid;
  global $isNurse;

  if ($oid) {
    $ord = get_pharm_order($oid);
    $injection = $ord['注射'];
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
           <table {$class}><tr><th align=left>処方箋ID<td>{$oid}";
    if ($isNurse) $name = get_emp_name($ord['看護記録者']);
    else $name = get_emp_name($ord['薬剤記録者']);
    print "<th align=left>記録者
                 <td align=left>{$name['lname']}&nbsp;{$name['fname']}";
    print "<tr><th align=left nowrap>処方年月日<td align=left>{$ord['処方年月日']}
               <th align=left nowrap>処方開始日<td align=left>{$ord['処方開始日']}
           <tr><th align=left>区分<td align=left>{$ord['区分']}
               <th align=left>日数<td align=left>{$ord['日数']}";
    if ($isNurse)
      print "<tr><th align=left>コメント<td colspan=3>{$ord['看護師実施']}";
    else {
      $name = get_emp_name($ord['停止医']);
      print "<tr><th align=left>停止医
                 <td align=left>{$name['lname']}&nbsp;{$name['fname']}
               <th align=left>停止日<td align=left>{$ord['停止日']}";
    }
    $name = get_emp_name($ord['調剤薬剤師']);
    print "<tr><th align=left>調剤薬剤師
                 <td align=left>{$name['lname']}&nbsp;{$name['fname']}
               <th align=left>調剤年月日<td align=left>{$ord['調剤年月日']}";

    /* 内容の表示 */
    show_meds($meds,4);
    print "<tr><td colspan=4>";
    get_order_history("薬剤処方箋",$oid,"kango");
    print "</table>";
  }
}

function draw_if_drug ($medid,$medis,$oid) {
  global $__mx_formi_dek;
  
  if ($medis && ($v = check_drug($medis))) {
    if ($v & 3) {
      if (($drug = get_drug("麻毒管理",$medis,$oid)))
	print '<input type="hidden" name="dmed'.$medid.'drugid" 
               value="'.$drug["ID"].'">';
      print '<tr><th><font color="red">麻毒管理</font><td>
             <input type="hidden" name="dmed'.$medid.'薬剤" value="'.$medis.'">
             <input type="hidden" name="dmed'.$medid.'処方箋" value="'.$oid.'">
             <tr><th><font color="red">受払年月日</font>
               <td><input type="text" name="dmed'.$medid.'受払年月日" value="'
	.($drug['受払年月日'] ? $drug['受払年月日'] : date("Y-m-d")).'" '
	.$__mx_formi_dek.'>
               <tr><th><font color="red">受払数量</font>
               <td><input type="text" name="dmed'.$medid.'受払数量" value="'
	.($drug['受払数量'] ? $drug['受払数量'] : "").'" '
	.$__mx_formi_dek.'>
               <tr><th><font color="red">残数量</font>
               <td><input type="text" name="dmed'.$medid.'残数量" value="' 
	.($drug['残数量'] ? $drug['残数量'] : "").'" '
	.$__mx_formi_dek.'>
               <tr><th><font color="red">備考</font>
               <td><input type="text" name="dmed'.$medid.'備考" value="'
	.($drug['備考'] ? $drug['備考'] : "").'" '
	.$__mx_formi_dek.">\n";
    }
    if ($v & 12) {
      if (($blood = get_drug("血液生物由来製品使用記録",$medis,$oid)))
	print '<input type="hidden" name="bmed'.$medid.'bloodid" 
               value="'.$blood["ID"].'">';
      print '<tr><th><font color="purple">生物由来製品</font><td>
             <input type="hidden" name="bmed'.$medid.'処方箋" value="'.$oid.'">
             <input type="hidden" name="bmed'.$medid.'薬剤" value="'.$medis.'">
             <tr><th><font color="purple">製造番号</font>
               <td><input type="text" name="bmed'.$medid.'製造番号" value="'
	.($blood['製造番号'] ? $blood['製造番号'] : "").'" '
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
      $injection = $ord['注射'];
    }
    else
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    if ($ord["停止日"]) {
      print '<font color="red">この処方箋には「停止日」が既に指定されています。</font>';
      return;
    }
    print '<input type="hidden" name="i患者" value="'.$pid.'">
           <table '.$class.'>';
    print "<tr><th align=left>処方箋ID<td align=left>{$oid}".
            '<input type="hidden" name="iact" value="'.$oid.'">
             <input type="hidden" name="oid" value="'.$oid.'">';
    if ($ord)
      $sname = get_emp_name($ord['停止医']);

    $rname = get_emp_name($auth[2]['ObjectID']);
    print "<tr><th>処方年月日<td>{$ord['処方年月日']}
	       <th>処方開始日<td>{$ord['処方開始日']}
	   <tr><th>日数<td>{$ord['日数']}<th>区分<td>{$ord['区分']}
	   <tr><th>記録者<td>{$rname['lname']}&nbsp;{$rname['fname']}
               <th>停止医<td>{$sname['lname']}&nbsp;{$sname['fname']}<td><td>";
    if ($isNurse) {
      $pname = get_emp_name($ord['調剤薬剤師']);
      print '<tr><th>調剤年月日<td>'.$ord['調剤年月日'].
                '<th>調剤薬剤師<td>'.$pname['lname'].'&nbsp;'.$pname['fname'].
            '<tr><th>コメント<td><input type="text" size="33" maxlength="33"
             name="i看護師実施" '.$__mx_formi_dek.' value="'.
	$ord["看護師実施"].'">
      <input type="hidden" name="i看護記録者" value="'.
	$auth[2]['ObjectID'].'">';
    }
    else {
      print '<tr><th>調剤年月日<td><input type="text" maxlength="10" 
             name="i調剤年月日" '.$__mx_formi_dek.' value="'.
	     ($ord["調剤年月日"]?$ord["調剤年月日"]:date("Y-m-d")). '">
             <th>調剤薬剤師<td>
             <input type="hidden" name="i薬剤記録者" value="'.
	$auth[2]['ObjectID'].'">';
      list_pharmacists("i調剤薬剤師",$ord['調剤薬剤師']);
    }
    print '</table>';

    if ($injection) $meds = get_meds($oid,1);
    else $meds = get_meds($oid,0);

    print "<table $class>";
    foreach ($meds as $med) {
      print '<tr><th colspan="2"><font color="green">
               処方箋内容ID　' . $med['medid'] . '</font>'.
	"<tr><td colspan=\"2\">{$med['レセプト電算処理システム医薬品名']}
             &nbsp;{$med['用量']}{$med['用量単位']}&nbsp;".
             ($injection ? $med['手技'] : $med['投与形態']).
	"&nbsp;{$med['用法']}{$med['注射用法']}";
      if ($med['その他コメント'])
	print "<tr><td colspan=\"2\">コメント　{$med['その他コメント']}";
      if (!$isNurse) draw_if_drug ($med['medid'],$med['medis'],$oid);
    }
    if ($isNurse)
      print '<tr><td><button type="submit" name="dbaction" 
                   value="'.$oid.'">記録</button>';
    else 
      print '<tr><td><button type="submit" name="dbaction" 
                   value="'.$oid.'">記録</button>';
    print '</table>';
  }
}

if (!$pid && !($pat = search_patient("",$ym))) {
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
