<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
// I don't want to show this page to unauthorized users.
// I should check auth against /u/test/print_historical.php
/*
$u = $_REQUEST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}
*/
function ue($s) {
  return $s == '&nbsp;' ? '' : $s;
}
function __get_patient($patient_id) {
  $db = mx_db_connect();
  $stmt = <<<SQL
    SELECT * 
    FROM "患者台帳"
    WHERE "患者ID"='${patient_id}' AND "Superseded" IS NULL
SQL;
  return mx_db_fetch_single($db, $stmt);
}
// functions
function trimd($d) {
  list($dt, $tm) = split(' ', $d);
  return $dt;
}
function get_test_order($patient_id, $limit, $type, $selected) {
  $db = mx_db_connect();
  $patient_id = mx_db_sql_quote($patient_id);
  $limit = intval($limit);
  $where_selected = '';
  if (is_array($selected) and count($selected) > 0)
    $where_selected = 'AND O."ObjectID" in (' . implode(', ', $selected) . ')';
  $stmt = <<<SQL
    SELECT DISTINCT R."TestOrder", O."SampleDate", O."OrderDate", O."PatientGroup", O."ObjectID", E."姓" || E."名" as "DrName", O."DrCode"
    FROM test_result R
    JOIN test_order O ON R."TestOrder"=O."ObjectID" AND O."Superseded" IS NULL
    JOIN "患者台帳" P ON O."Patient" = P."ObjectID" AND P."Superseded" IS NULL
    LEFT JOIN "職員台帳" E ON O."DrCode" = E."LaboSystemCode" AND E."Superseded" IS NULL
    WHERE O.test_app_type=${type} AND O."Cancelled" IS NULL AND P."患者ID"=${patient_id}
  ${where_selected}
    ORDER BY O."SampleDate" DESC, O."ObjectID" DESC
SQL;
 return mx_db_fetch_all($db, $stmt);
}

function get_test_result($order_oid) {
  $db = mx_db_connect();
//0927-2014
  $stmt = <<< SQL
    SELECT *
    FROM test_result R
    JOIN test_master M ON R."TestMaster"=M."ObjectID" AND M."Superseded" IS NULL
    WHERE R."TestOrder"=${order_oid} 
SQL;
 $rs = mx_db_fetch_all($db, $stmt);
 return $rs;
}

function print_historical_content($type, $patient_id, $min_col, $selected=array()) {
  global $_params;

  if ($type == 0)
    $_params['TITLE'] = '検体検査';
  else if($type==1)
    $_params['TITLE'] = '生理検査';
  else
    $_params['TITLE'] = '検査';


  // constants
  // min order columns
  $limit = 500;
  $n_items = 5; // number of test item rows between dividers
  $n_divs = 7; // number of dividers

  //---------- main -----------------------
  $patient = __get_patient($patient_id);
  $orders = get_test_order($patient_id, $limit, $type, $selected);
  if(count($orders) == 0) {
    return "検査依頼がありません。";
  }
  
  $data = array();
  $prefix = $patient["性別"] == 'M' ? 'Male' : 'Female';

  if ($min_col == 3)
    $orders = array_slice($orders, 0, 3);

  foreach($orders as $order) {
    $order['OrderDate'] = trimd($order['OrderDate']);
    $order['SampleDate'] = trimd($order['SampleDate']);
    $results = get_test_result($order['TestOrder']);
    if(is_array($results)) {
      foreach($results as $result) {
	$result['NormalBottom'] = $result[$prefix . 'NormalBottom'];
	$result['NormalTop'] = $result[$prefix . 'NormalTop'];
	$result['NormalText'] = $result[$prefix . 'NormalText'];
	$order['results'][$result['TestMaster']] = $result;
	$order['comment']=$order['LaboComment'];
      }
    }
    $data[] = $order;
  }
  while(count($data) < $min_col) {
    $data[] = array('OrderDate' => NULL,
		    'SampleDate' => NULL,
//04-12-2012
		'DrCode' => NULL,
		    'result' => array(),
		    'comment' => NULL);
  }
  $created_on = date("Y-m-d");
  $patient_name = $patient["姓"] . $patient["名"];
  $patient_kana = $patient["フリガナ"];
  $patient_dob = $patient["生年月日"];
  $patient_wdob = mx_wareki($patient_dob);
  $patient_sex = $patient["性別"] == 'M' ? '男' : ($patient["性別"] == 'F' ? '女' : '不明');
  
  $td_pt_group_name = '';
  $td_dr_name = '';
  $td_order_date = '';
  $td_order_oid = '';
  $td_pt_age = '';      
  $td_sample_date = '';
  $td_test_header = '';
  $tr_test_result = '';
  $td_selector = '<td>&nbsp;</td><td>&nbsp;</td>';
  $test_items = array();
  
  // common
  $_params['PATIENT_ID'] = $patient_id;
  $_params['PATIENT_NAME'] = $patient_name;
  $_params['PATIENT_SEX'] = $patient_sex;
  $_params['PATIENT_DOB'] = mx_wareki($patient_dob);

  // X axis
  $first=True;
  $counter = 0;
  foreach($data as $d) {
    $dt='1';
    if($first) {
      $dt='1.5';
      $first=False;
    }
    
    if(is_null($d['OrderDate'])){
      // padding order
      $patient_group = '&nbsp;';
      $d['DrName'] = '&nbsp;';
      $d['SampleDate'] = '&nbsp;';
      $age = '&nbsp;';
      $patient_group = '&nbsp;';
      
    }else{
      $patient_group = $d['PatientGroup'] ? $d['PatientGroup'] : '&nbsp;';
      
   if(is_null($d['DrName']))
	$d['DrName'] = '&nbsp;';
      
      $age = mx_calc_age($patient_dob, $d['SampleDate']);
      
      // find GCM of tests
      if(is_array($d['results'])) {
	foreach($d['results'] as $ObjectID => $result) {
	  $lsc = sprintf("%04d", $result['LaboSystemCode']);
	  $test_items[$lsc] = array('Name' => $result['Name'],
				    'NormalBottom' => $result['NormalBottom'],
				    'NormalTop' => $result['NormalTop'],
				    'NormalText' => $result['NormalText'],
				    'ObjectID' => $result['TestMaster'],
				    );
	}
      }
    }
    $oid = $d['ObjectID'];
    $cb_checked = '';
    if (is_array($selected) && in_array($oid, $selected))
      $cb_checked = 'checked';
    else if ($min_col == 3) {
      # check all by default when num of column is 3
      $cb_checked = 'checked';
    }
      
    
    if ($oid) 
      $td_selector .= "<TD><input type=\"checkbox\" onclick=\"update_selector(this)\" name=\"selector[]\" value=\"$oid\" ${cb_checked}></td><td>&nbsp;</td>";
    else
      $td_selector .= "<TD>&nbsp;</td><td>&nbsp;</td>";
    
    $td_pt_group_name.= "      <TD style=\"border-top: 1.5pt solid #000000; border-left: ${dt}pt solid #000000;\"  colspan=\"2\" width=\"120\" align=\"center\"><FONT color=\"#0000cc\">${patient_group}</FONT></TD>\n";
    $td_dr_name      .= "      <TD style=\"border-left: ${dt}pt solid #000000;\" colspan=\"2\" width=\"120\"><FONT color=\"#0000cc\">${d['DrName']}</FONT></TD>\n";
    $td_sample_date  .= "      <TD style=\"border-left: ${dt}pt solid #000000;\" colspan=\"2\" width=\"120\"><FONT color=\"#0000cc\">${d['SampleDate']}</FONT></TD>\n";
    $td_pt_age       .= "      <TD style=\"border-bottom: 1.5pt solid #000000; border-left: ${dt}pt solid #000000;\" colspan=\"2\" width=\"120\"><FONT color=\"#0000cc\">${age}歳</FONT></TD>\n";
    $td_test_header  .= "      <TD style=\"border-bottom: 1.5pt solid #000000; border-left: ${dt}pt solid #000000;\"  align=\"center\" width=\"80\" bgcolor=\"#c8ffc8\"><B>結果値</B></TD>\n      <TD style=\"border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;\" align=\"center\" width=\"40\" bgcolor=\"#c8ffc8\"><B>H/L</B></TD>\n";

    $_params[sprintf('PATIENT_GROUP%d', $counter)] = ue($patient_group);
//0412-2012
    $_params[sprintf('DOCTOR%d', $counter)] = $d['DrCode'];
//

    $_params[sprintf('SAMPLE_DATE%d', $counter)] = $d['SampleDate'];
    $_params[sprintf('PATIENT_AGE%d', $counter)] = $age;
    $counter += 1;
  }
  
  $tr_selector = "<tr>" . $td_selector . "</tr>";
  // sort by LaboSystemCode
  ksort($test_items);
  
  $num_test_items = count($test_items);
  
  // Y axis
  $pages = array();
  
  $ttl_colspan = $min_col * 2 + 1;
  $dt_colspan = $min_col * 2 + 2;
  
  $row = 1;
  $total=count($test_items);
  $cc = 0;
  foreach($test_items as $lsc => $test_item) {
    $test_name = !is_null($test_item['Name']) ? $test_item['Name'] : '&nbsp;';
    $test_name = str_replace(" ", "&nbsp;", $test_name);
    if(!is_null($test_item['NormalBottom']) and !is_null($test_item['NormalTop']))
      $normal_range = $test_item['NormalBottom'] . " - " . $test_item['NormalTop'];
    else if(!is_null($test_item['NormalBottom']) and is_null($test_item['NormalTop']))
      $normal_range = $test_item['NormalBottom'] . " > ";
    else if(is_null($test_item['NormalBottom']) and !is_null($test_item['NormalTop']))
      $normal_range = "<" . $test_item['NormalTop'];
    else if(!is_null($test_item['NormalText']))
      $normal_range = $test_item['NormalText'];
    else
      $normal_range = "&nbsp;";

    $_params[sprintf('ITEM%d', $cc)] = $test_item['Name'];
    $_params[sprintf('NORMAL%d', $cc)] = ue($normal_range);
    
    $border_bottom = '';
    if($row%$n_items == 0 || $cc+1 == $total) {
      if($row == $n_items * $n_divs)
	$bottom_dt='1.5';
      else
	$bottom_dt='1';
      $border_bottom = "border-bottom: ${bottom_dt}pt solid #000000; ";
    }
    $tr_test_result .="<TR>\n      <TD style=\"${border_bottom}border-left: 1.5pt solid #000000;\" colspan=\"2\" align=\"left\"><FONT color=\"#0000cc\">${test_name}</FONT></TD>\n";
    $first=True;
    $cx = 0;
    foreach($data as $order) {
      $dt='1';
      if($first) {
	$dt='1.5';
	$first=False;
      }
      $r = $order['results'];
      $test_result = $test_decision = "&nbsp;";
      $ObjectID = $test_item['ObjectID'];
      $rr = $r[$ObjectID];
      if(!is_null($rr)) {
	$v = trim($rr['value']);
	$test_result = !is_null($v) && $v != '' ? $v . $rr['unit'] : '&nbsp;';
	$test_result .= $rr['quantification_limit'];
	$test_decision = !is_null($rr['decision']) && trim($rr['decision']) != '' ? $rr['decision'] : '&nbsp;';
	$_params[sprintf('VALUE%d_%d', $cx, $cc)] = ue($test_result);
	$_params[sprintf('HL%d_%d', $cx, $cc)] = ue($test_decision);
      }else{
	$_params[sprintf('VALUE%d_%d', $cx, $cc)] = '';
	$_params[sprintf('HL%d_%d', $cx, $cc)] = '';
      }
      $cx += 1;
      $tr_test_result .= <<<HTML
<TD style="${border_bottom}border-left: ${dt}pt solid #000000;" align="right"><FONT color="#0000cc">${test_result}</FONT></TD>
<TD style="${border_bottom}" align="center"><FONT color="#0000cc">${test_decision}</FONT></TD>
HTML;
    }
    $tr_test_result .= "      <TD style=\"${border_bottom}border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;\" align=\"left\">${normal_range}</FONT></TD>\n    </TR>\n";
    $cc2 = 0;
    if($row == $n_items * $n_divs) {
      $tr_comment = '';
      foreach($data as $order) {
	$cmt_ttl = $order['OrderDate'] ? ' 検査室コメント' : '&nbsp;';
        $comment = $order['LaboComment'] ? $order['LaboComment'] : '&nbsp;'; 
	$tr_comment .= <<<HTML
<tr>
      <td align="left" colspan="2" style="border-bottom: 1pt solid rgb(0, 0, 0); border-left: 1.5pt solid rgb(0, 0, 0);">{$order['OrderDate']}${cmt_ttl}</td>
      <td align="left" colspan="$dt_colspan" style="border-bottom: 1pt solid rgb(0, 0, 0); border-left: 1pt solid rgb(0, 0, 0); border-right: 1.5pt solid rgb(0, 0, 0);">${comment}</td>
    </tr>
HTML;
	$_params["COMMENT$cc2"] = ue($comment);
	$cc2 += 1;
      }
      $pages[] = $tr_test_result . $tr_comment;
      $tr_test_result = $tr_comment = '';
      $row = 1;
    }else{
      $row += 1;
    }
    $cc += 1;
  }
  if ($tr_test_result != '') {
    $tr_comment = '';
    foreach($data as $order) {
      $cmt_ttl = $order['OrderDate'] ? ' 検査室コメント' : '&nbsp;';
      $comment = $order['LaboComment'] ? $order['LaboComment'] : '&nbsp;'; 
      $tr_comment .= <<<HTML
<tr>
      <td align="left" colspan="2" style="border-bottom: 1pt solid rgb(0, 0, 0); border-left: 1.5pt solid rgb(0, 0, 0);">{$order['OrderDate']}${cmt_ttl}</td>
      <td align="left" colspan="$dt_colspan" style="border-bottom: 1pt solid rgb(0, 0, 0); border-left: 1pt solid rgb(0, 0, 0); border-right: 1.5pt solid rgb(0, 0, 0);">${comment}</td>
    </tr>
HTML;
	$_params["COMMENT$cc2"] = ue($comment);
	$_params["COMMENT$cc2"] = ue($comment);
	$cc2 += 1;
    }
    $pages[] = $tr_test_result . $tr_comment;
  }
  $_params['PAGES'] = count($pages);

  #---------------------------------------------
  $counter=0;
  foreach($pages as $page) {
    if ($counter >0) {
      $tr_selector='';
    }
    $html_results[] = <<<HTML
  <TABLE frame="VOID" cellspacing="0" rules="NONE">
  <TBODY>
  <TR>
  <TD colspan="${ttl_colspan}" align="center"><B><font size="+1">検　査　結　果</font></B></TD>
  <TD valign="bottom" align="right">作成日：</TD>
  <TD valign="bottom"><FONT color="#0000cc">${created_on}</FONT></TD>
  </TR>
  ${tr_selector}
  <TR>
  <TD style="border-top: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" width="150"><FONT color="#0000cc">ID:${patient_id}</FONT></TD>
  <TD style="border-top: 1.5pt solid #000000; border-left: 1pt solid #000000;" width="60"><FONT color="#0000cc">依頼元</FONT></TD>
  ${td_pt_group_name}
  <td style="border-top: 1.5pt solid #000000; border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" rowspan="4">&nbsp;</td>
  </TR>
  <TR>
  <TD style="border-left: 1.5pt solid #000000;"><FONT color="#0000cc">${patient_name}</FONT></TD>
  <TD style="border-left: 1pt solid #000000;" nowrap><FONT color="#0000cc">依頼医</FONT></TD>
  ${td_dr_name}
</TR>
<TR>
<TD style="border-left: 1.5pt solid #000000;"><FONT color="#0000cc">${patient_sex}</FONT></TD>
<TD style="border-left: 1pt solid #000000;" nowrap><FONT color="#0000cc">検査日</FONT></TD>${td_sample_date}
</TR>
<TR>
<TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" width="150" valign="top"><FONT color="#0000cc">${patient_wdob}</FONT></TD>
<TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" nowrap><FONT color="#0000cc">年齢</FONT></TD>
  ${td_pt_age}
</TR>
<TR>
<TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" bgcolor="#c8ffc8" align="center"><B>項目名</B></TD>
  ${td_test_header}
<TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="center" width="100" bgcolor="#c8ffc8"><B>基準範囲値</B></TD>
  </TR>
  ${page}
</TBODY>
</TABLE>
HTML;
    $counter += 1;  
  }

  $html_result = implode('<HR>', $html_results);
  return $html_result;

  $body_template = <<<HTML
  <HEAD>
  <META http-equiv="content-type" content="text/html; charset=UTF-8">
  <META http-equiv="Content-Style-Type" content="text/css">
  <STYLE>
  <!-- 
  BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-size:x-small ; font-family : "ＭＳ Ｐ明朝";}
  hr {
    page-break-before: always;
    visibility: hidden;
  }
  @page {
	size: landscape;
  }
  body {
	size: landscape;
  }
  -->
  </STYLE>
  <TITLE>検査結果時系列表</TITLE>
  </HEAD>
  <BODY text="#000000">
  ${html_result}
</BODY>
</HTML>
HTML;
  return $body_template;
}

if (!$_REQUEST['pdf'])
  return;
global $_params;
$_params = array();
$selected = explode(',', $_REQUEST['selected']);
$min_col = count($selected) > 3 ? 6 : 3;
print_historical_content($_REQUEST['type'],
			 $_REQUEST['patient_id'],
			 $min_col,
			 $selected);
// prepare values to be embedded into PDF
$rand = rand(0,100000000);
$template = $min_col == 3 ? 'historical_template.ods' : 'historical_template_6.ods';


$pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
$_params['PDF_PATH'] = $pdf_path;
$_params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
$_params['TEMPLATE'] = $template;
$_params['BODY'] = '';
$_params['creation_date'] = date('Y-m-d');

#var_dump($_params);
$err = fopen('/tmp/ooo_error.txt', 'w');
//0120-2014 from 3 ->33
fwrite($err, ooo_print_pdf($_params, '/s/medex/farm/php/tools/pdfgen33.py'));
fclose($err);


if(file_exists($pdf_path)) {
  //---- read pdf file
  $handler = fopen($pdf_path, 'rb');
  $content = fread($handler, filesize($pdf_path));
  fclose($handler);
  unlink($pdf_path);
 //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
    $type = 'PDF';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
      '/generated.pdf","","width=640,height=640");
</SCRIPT>';
  
//  header('Content-type: application/pdf');
//  print $content;
 }else{
  print "PDFが生成できませんでした";
  print file_get_contents('/tmp/ooo_error.txt');
 }

?>
