<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';

function go_rx_control_pdf($daily_values, $year, $month,
			   $ptid, $oid)
{
  global $_mx_db_pghost;
  global $_mx_pdfgen_cmd2;

  $ndays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

  // prepare values to be embedded into PDF
  $params = array();

  $template='record_list_rx.ods';
  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['DB'] = mx_dbname_cfg();
  if(!empty($_mx_db_pghost))
     $params['PGHOST'] = $_mx_db_pghost;
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;

  $pat = get_patient($ptid,1);
  $params['PATIENT_ID'] = $pat['´µ¼ÔID'];
  $params['PATIENT_KANA'] = $pat['¥Õ¥ê¥¬¥Ê'];
  $params['PATIENT_KANJI'] = $pat['À«'] .'¡¡'. $pat['Ì¾'];

  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];

  # set direct params
  $t = 'sheet';

  $params[sprintf("DIRECT|%s|%s|%s|%s", $t, 0, 28, 2)] = $year;
  $params[sprintf("DIRECT|%s|%s|%s|%s", $t, 0, 31, 2)] = $month;

  $row = 4;
  $page = 0;
  $max_rows = 40;
  foreach($daily_values as $med) {
    $params[sprintf("DIRECT|%s|%s|%s|%s", $t, $page, 0, $row)] = $med['name'];
    $params[sprintf("DIRECT|%s|%s|%s|%s", $t, $page, 0, $row+1)] = $med['direction'];
    for($i=1; $i <= $ndays; $i++) {
      if(!is_null($med['daily_amount'][$i]))
	$params[sprintf("DIRECT|%s|%s|%s|%s", $t, $page, $i+1, $row)] = 
	  $med['daily_amount'][$i];
      if($i == $ndays) {
	$params[sprintf("DIRECT|%s|%s|%s|%s", $t, $page, 33, $row)] =
	  $med['total'];
      }
    }
    $row += 2;
    if($row > $max_rows) {
      $row = 4;
      $page += 1;
      $params[sprintf("DIRECT|%s|%s|%s|%s", $t, $page, 28, 2)] = $year;
      $params[sprintf("DIRECT|%s|%s|%s|%s", $t, $page, 31, 2)] = $month;
    }
  }
  if($row != $max_rows)
    $page += 1;
  
  $params['PAGES'] = $page;

  print ooo_print_pdf($params, $_mx_pdfgen_cmd2);
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
  }else{
    print "PDF¤ÎÀ¸À®¤Ë¼ºÇÔ¤·¤Þ¤·¤¿";
  }
}

function go_pdf($oid, $shots, $template=NULL) 
{
  global $_mx_rx_template;
//$_mx_rx_template = "xctform.ods";

  if(is_null($template))
    $template = $_mx_rx_template;
  // read DB
 // 03152011 
 $ord = get_pharm_order($oid, $shots);
  $doc = get_emp_name($ord['µ­Ï¿¼Ô']);

 // 03152011
  $meds = get_meds($oid,$shots);
  $pat = get_patient($ord['´µ¼Ô'],false);
  
  $pat['´µ¼ÔID'] = ereg_replace("^(.*) .*","\\1",$pat['´µ¼ÔID']);
  
  
  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = 'image order';
  
  
  // ¸øÈñÉéÃ´¼ÔÈÖ¹æ
  // ¼õµë¼ÔÈÖ¹æ
  $params['WELFARE_NUMBER:%8s'] = $pat['¸øÈñÉéÃ´¼ÔÈÖ¹æ'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ'];
  $params['WELFARE_GOOD_THRU'] = $pat['¸øÈñÍ­¸ú´ü¸Â'];
  
  $params['WELFARE_NUMBER2:%8s'] = $pat['¸øÈñÉéÃ´¼ÔÈÖ¹æ2'];
  $params['WELFARE_RECIPIENT2:%7s'] = $pat['¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ2'];
  $params['WELFARE_GOOD_THRU2'] = $pat['¸øÈñÍ­¸ú´ü¸Â2'];
  
  $params['WELFARE_NUMBER3'] = $pat['¸øÈñÉéÃ´¼ÔÈÖ¹æ3'];
  $params['WELFARE_RECIPIENT3'] = $pat['¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3'];
  $params['WELFARE_GOOD_THRU3'] = $pat['¸øÈñÍ­¸ú´ü¸Â3'];
  
  // ´µ¼Ô»áÌ¾¡¢À¸Ç¯·îÆü¡¢¶èÊ¬¡¢³ä¹ç
  $params['PATIENT_ID'] = $pat['´µ¼ÔID'];
  $params['PATIENT_KANA'] = $pat['¥Õ¥ê¥¬¥Ê'];
  $params['PATIENT_KANJI'] = $pat['À«'] .'¡¡'. $pat['Ì¾'];
  $params['PATIENT_DOB'] = mx_wareki($pat['À¸Ç¯·îÆü']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['À¸Ç¯·îÆü']);
  $params['PATIENT_SEX'] = $pat['À­ÊÌ'] == 'M' ? 'ÃË' : '½÷';
  $params['PATIENT_KUBUN'] = $pat['ÈïÊÝ¸±¼Ô'] == '1' ? 'ËÜ¿Í' : '²ÈÂ²';
  $params['PATIENT_GROUP'] = $pat['´õË¾ÉÂÅï'];
  if ($pat['Æþ³°¶èÊ¬'] == 'I')
    $params['PATIENT_INOUT'] = 'Æþ±¡';
  else if ($pat['Æþ³°¶èÊ¬'] == 'O')
    $params['PATIENT_INOUT'] = '³°Íè';
  else
    $params['PATIENT_INOUT'] = '';

  //TODO: take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['Æþ±¡²Ê'];
  $params['PATIENT_ROOM'] = $pat['ÉÂ¼¼'];
  $params['DEDUCTIBLE'] = '';
  
  // ¸òÉÕÇ¯·îÆü
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['orderdate'],
                                           array('dayofweek'=>1));
  $params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['plandate']);
  // NEEDSWORK: ½èÊý³«»ÏÆü + max(Æü¿ô¡Ë
  $days = 0;
  foreach($meds as $m)
    $days = max($days, $m['Æü¿ô']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['plandate'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
  $params['PRESCRIPTION_ID'] = $oid;
  $params['PRESCRIPTION_NAIGAI'] = $ord['bui1'];
  
    $params['PRESCRIPTION_REGULAR'] = $ord['memo1'];
	 $params['BUI1'] = $ord['bui1'];
     $params['BUI2'] = $ord['bui2'];
$params['BUI3'] = $ord['bui3'];
$params['BUI4'] = $ord['bui4'];
$params['BUI5'] = $ord['bui5'];
  
   $params['MEMO1'] = $ord['memo1'];
     $params['MEMO2'] = $ord['memo2'];
 $params['DRMEMO'] = $ord['drsyoken'];
$params['TECHMEMO'] = $ord['techsyoken'];
$params['ORDER_ID'] = $oid;
$params['MEMO11'] = $ord['memo11'];
     $params['MEMO12'] = $ord['memo12'];


$params['MEMO21'] = $ord['memo21'];
     $params['MEMO22'] = $ord['memo22'];
$params['MEMO31'] = $ord['memo31'];
     $params['MEMO32'] = $ord['memo32'];


$params['MEMO41'] = $ord['memo41'];
     $params['MEMO42'] = $ord['memo42'];
$params['MEMO51'] = $ord['memo51'];
     $params['MEMO52'] = $ord['memo52'];



  
  // ÊÝ¸±¼ÔÈÖ¹æ
  // ÈïÊÝ¸±¼Ô¾Úµ­¹æ¡¦ÈÖ¹æ
  $params['INSURER_NUMBER:%8s'] = $pat['ÊÝ¸±¼ÔÈÖ¹æ'];
  $params['INSURED_KIGO']=$pat['ÈïÊÝ¸±¼Ô¼êÄ¢¤Îµ­¹æ'];
  $params['INSURED_NUMBER']=$pat['ÈïÊÝ¸±¼Ô¼êÄ¢¤ÎÈÖ¹æ'];
  
  // °åÎÅµ¡´Ø½êºßÃÏ¡¢Ì¾¾Î
  // ÅÅÏÃÈÖ¹æ¡¢ÊÝ¸±°å»áÌ¾
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'¡¡'.$doc['fname'];
  
  // ½èÊý
  // 03152011
  $params['BODY'] = "   ";

 

  // È÷¹Í
  // comment in or append if you want to print a comment from rx
#$params['COMMENT'] = $ord['Comment'];
  $params['COMMENT'] = 'yobi';
  $params['COMMENT'] = $pat['È÷¹Í']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '¸øÈñÉéÃ´¼ÔÈÖ¹æ: ' . $pat['¸øÈñÉéÃ´¼ÔÈÖ¹æ3']."\n";
    $params['COMMENT'] .= '¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ: ' . $pat['¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3'] . "\n";
  }

 
  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = "shohousen_tg.ods";
	$template = "shohousen_tg.ods";

  print ooo_print_pdf2($params);
  if(file_exists($pdf_path)) {
    //---- read pdf file
    $handler = fopen($pdf_path, 'rb');
    $content = fread($handler, filesize($pdf_path));
    fclose($handler);
    unlink($pdf_path);

    //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
    $type = '½èÊýäµ';
    if($shots)
      $type = 'Ãí¼Íäµ';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "Ãí¼Í½èÊýäµ" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "ÌôºÞ½èÊýäµ" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }
    pg_query($db, $stmt);
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
      '/generated.pdf","","width=640,height=640");
</SCRIPT>';
    
  }else{
    print "PDF¤ÎÀ¸À®¤Ë¼ºÇÔ¤·¤Þ¤·¤¿";
  }
}

?>
