<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
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
  $params['PATIENT_ID'] = $pat['����ID'];
  $params['PATIENT_KANA'] = $pat['�եꥬ��'];
  $params['PATIENT_KANJI'] = $pat['��'] .'��'. $pat['̾'];

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
//07122012 yakuzai-rireki
 print ooo_print_pdf($params, $_mx_pdfgen_cmd2);
//  print ooo_print_pdf($params, $_mx_pdfgen_cmd);
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
    print "PDF�������˼��Ԥ��ޤ���";
  }
}
//for printing pdf file
function go_pdf($oid, $shots, $template=NULL) 
{
  global $_mx_rx_template;

	if(is_null($template))
    $template = $_mx_rx_template;

  //0916-2012
$template = 'shohousen.ods';

  // read DB
  $ord = get_pharm_order($oid, $shots);
//05-11-2012 shiji
  $doc = get_emp_name($ord['shiji']);
  $meds = get_meds($oid,$shots);
  $pat = get_patient($ord['����'],false);
  
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  
  
  // prepare values to be embedded into PDF
  $params = array();
  
  if ($ord['�����'])
    $params['PRESCRIPTION_TITLE'] = '����ߡ۽� �� �� ��';
  else
    $params['PRESCRIPTION_TITLE'] = '�衡����������������';
  if($shots) {
    if ($ord['�����'])
      $params['PRESCRIPTION_TITLE'] = '����ߡ��� �� �� ��';
    else
      $params['PRESCRIPTION_TITLE'] = '�����͡�����������';
  }
  
  // ������ô���ֹ�
  // ������ֹ�
  $params['WELFARE_NUMBER:%8s'] = $pat['������ô���ֹ�'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['������ô���Ťμ�����ֹ�'];
  $params['WELFARE_GOOD_THRU'] = $pat['����ͭ������'];
  
  $params['WELFARE_NUMBER2:%8s'] = $pat['������ô���ֹ�2'];
  $params['WELFARE_RECIPIENT2:%7s'] = $pat['������ô���Ťμ�����ֹ�2'];
  $params['WELFARE_GOOD_THRU2'] = $pat['����ͭ������2'];
  
  $params['WELFARE_NUMBER3'] = $pat['������ô���ֹ�3'];
  $params['WELFARE_RECIPIENT3'] = $pat['������ô���Ťμ�����ֹ�3'];
  $params['WELFARE_GOOD_THRU3'] = $pat['����ͭ������3'];
  
  // ���Ի�̾����ǯ��������ʬ�����
  $params['PATIENT_ID'] = $pat['����ID'];
  $params['PATIENT_KANA'] = $pat['�եꥬ��'];
  $params['PATIENT_KANJI'] = $pat['��'] .'��'. $pat['̾'];
  $params['PATIENT_DOB'] = mx_wareki($pat['��ǯ����']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['��ǯ����']);
  $params['PATIENT_SEX'] = $pat['����'] == 'M' ? '��' : '��';
  $params['PATIENT_KUBUN'] = $pat['���ݸ���'] == '1' ? '�ܿ�' : '��²';
  $params['PATIENT_GROUP'] = $pat['��˾����'];
  if ($pat['������ʬ'] == 'I')
    $params['PATIENT_INOUT'] = '����';
  else if ($pat['������ʬ'] == 'O')
    $params['PATIENT_INOUT'] = '����';
  else
//    $params['PATIENT_INOUT'] = 'XXX';
 $params['PATIENT_INOUT'] = 'unknown';
//2012
$params['PATIENTIO']='YYY';
  //TODO: take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['��˾����'];
  $params['PATIENT_ROOM'] = $pat['�¼�'];
  $params['DEDUCTIBLE'] = '';
  $params['PATIENT_DD'] = 'ABC';
  // ����ǯ����
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['����ǯ����'],
                                           array('dayofweek'=>1));
//0404-2012  use startdate (new schema)
$params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['startdate'],
						array('dayofweek'=>1));
  // NEEDSWORK: ���������� + max(������
  $days = 0;
  foreach($meds as $m)
    $days = max($days, $m['����']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['startdate'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
  $params['PRESCRIPTION_ID'] = $oid;
  $params['PRESCRIPTION_NAIGAI'] = $ord['��ʬ'];
  if ($ord['����׻�'] == 1)
    $params['PRESCRIPTION_REGULAR'] = '���';
  else if ($ord['����׻�'] == 2)
    $params['PRESCRIPTION_REGULAR'] = '�׻�';
  else if ($ord['��ʬ'] == '�׻�' or $ord['��ʬ'] == '���') {
    # for old rx app that usese kubun for teiki/rinji
    $params['PRESCRIPTION_REGULAR'] = $ord['��ʬ'];
  }
  else
    $params['PRESCRIPTION_REGULAR'] = '';
    
  
  
  // �ݸ����ֹ�
  // ���ݸ��Ծڵ��桦�ֹ�
  $params['INSURER_NUMBER:%8s'] = $pat['�ݸ����ֹ�'];
  $params['INSURED_KIGO']=$pat['���ݸ��Լ�Ģ�ε���'];
  $params['INSURED_NUMBER']=$pat['���ݸ��Լ�Ģ���ֹ�'];
  
  // ���ŵ��ؽ���ϡ�̾��
  // �����ֹ桢�ݸ����̾
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'��'.$doc['fname'];
  
  // ����
  // preformatted body for shohousen.ods
  $params['BODY'] = implode("\n", set_body($meds, $shots, $ord["generic"]));

  // individual lines
  $a = set_body($meds, $shots, $ord["generic"], 1);
// how many lines? 10-20-2014
  for($i=0; $i < count($a); $i++)
    	$params[sprintf("LINE%04d", $i)] = $a[$i];
//10-20-2014
//print "no of lines:=".count($a);
  	// ����
 	 // comment in or append if you want to print a comment from rx
	//$params['COMMENT'] = $ord['Comment'];
  	$params['COMMENT'] = '';
  	$params['COMMENT'] = $pat['����']."\n";
  	if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    	$params['COMMENT'] .= '������ô���ֹ�: ' . $pat['������ô���ֹ�3']."\n";
    	$params['COMMENT'] .= '������ô���Ťμ�����ֹ�: ' . $pat['������ô���Ťμ�����ֹ�3'] . "\n";
 	 }

  	// ��ȯ�ʲ� changed attribute generic from if ($ord['��ȯ��'] == 1) {
 	 if ($ord['generic'] == 1 || $ord['generic'] == '' ) {
   	 $params['GENERIC_OK'] = '��';
    	$params['GENERIC_DR'] = '';
 	 }else{
    	$params['GENERIC_OK'] = '�Բ�';
    	$params['GENERIC_DR'] = $params['DOCTOR'];
 	 }
  
  // ʴ��
  	if ($ord['funsai'] == 1)
   	 $params['FUNSAI'] = 'ʴ��';
 	 else
    	$params['FUNSAI'] = '';

  	$rand = rand(0,100000000);
  	$pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  	$params['PDF_PATH'] = $pdf_path;
  	$params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  	$params['TEMPLATE'] = $template;
	//04-13-2012 for new format
	// print ooo_print_pdf3($params);
	//0703-2012 for old-format
	 print ooo_print_pdf($params);
  if(file_exists($pdf_path)) {
    //---- read pdf file
    $handler = fopen($pdf_path, 'rb');
    $content = fread($handler, filesize($pdf_path));
    fclose($handler);
    unlink($pdf_path);

    //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
    $type = '�����';
    if($shots)
      $type = '����';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "��ͽ����" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "���޽����" SET "PDF"=' . mx_db_sql_quote($id) .
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
    print "PDF�������˼��Ԥ��ޤ���";
  }
}
//07-06-2012 for rx_order-tagawa inhosp-pdf syohousen(old format)) 10-20-2014
function go_pdf3($oid, $shots, $template=NULL) 
{
  global $_mx_rx_template;

	if(is_null($template))
    $template = $_mx_rx_template;
//0720-2012
//10-15-2014
$template ='shohousen_tg2.ods';
//$template ='shohousen_hay1.ods';
//0916-2012
//$template ='shohousen-test.ods';

  // read DB
  $ord = get_pharm_order($oid, $shots);
//05-11-2012 shiji
  $doc = get_emp_name($ord['shiji']);
  $meds = get_meds($oid,$shots);
  $pat = get_patient($ord['����'],false);
  
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  
  
  // prepare values to be embedded into PDF
  $params = array();
  
  if ($ord['�����'])
    $params['PRESCRIPTION_TITLE'] = '����ߡ۽� �� �� ��';
  else
    $params['PRESCRIPTION_TITLE'] = '�衡����������������';
  if($shots) {
    if ($ord['�����'])
      $params['PRESCRIPTION_TITLE'] = '����ߡ��� �� �� ��';
    else
      $params['PRESCRIPTION_TITLE'] = '�����͡�����������';
  }
  
  // ������ô���ֹ�
  // ������ֹ�
  $params['WELFARE_NUMBER:%8s'] = $pat['������ô���ֹ�'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['������ô���Ťμ�����ֹ�'];
  $params['WELFARE_GOOD_THRU'] = $pat['����ͭ������'];
  
  $params['WELFARE_NUMBER2:%8s'] = $pat['������ô���ֹ�2'];
  $params['WELFARE_RECIPIENT2:%7s'] = $pat['������ô���Ťμ�����ֹ�2'];
  $params['WELFARE_GOOD_THRU2'] = $pat['����ͭ������2'];
  
  $params['WELFARE_NUMBER3'] = $pat['������ô���ֹ�3'];
  $params['WELFARE_RECIPIENT3'] = $pat['������ô���Ťμ�����ֹ�3'];
  $params['WELFARE_GOOD_THRU3'] = $pat['����ͭ������3'];
  
  // ���Ի�̾����ǯ��������ʬ�����
  $params['PATIENT_ID'] = $pat['����ID'];
  $params['PATIENT_KANA'] = $pat['�եꥬ��'];
  $params['PATIENT_KANJI'] = $pat['��'] .'��'. $pat['̾'];
  $params['PATIENT_DOB'] = mx_wareki($pat['��ǯ����']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['��ǯ����']);
  $params['PATIENT_SEX'] = $pat['����'] == 'M' ? '��' : '��';
  $params['PATIENT_KUBUN'] = $pat['���ݸ���'] == '1' ? '�ܿ�' : '��²';
  $params['PATIENT_GROUP'] = $pat['��˾����'];
  if ($pat['������ʬ'] == 'I')
    $params['PATIENT_INOUT'] = '����';
  else if ($pat['������ʬ'] == 'O')
    $params['PATIENT_INOUT'] = '����';
  else
//    $params['PATIENT_INOUT'] = 'XXX';
 $params['PATIENT_INOUT'] = 'unknown';
//2012
$params['PATIENTIO']='YYY';
  //TODO: take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['��˾����'];
  $params['PATIENT_ROOM'] = $pat['�¼�'];
  $params['DEDUCTIBLE'] = '';
  $params['PATIENT_DD'] = 'ABC';
  // ����ǯ����
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['����ǯ����'],
                                           array('dayofweek'=>1));
//0404-2012  
$params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['startdate'],
						array('dayofweek'=>1));
  // NEEDSWORK: ���������� + max(������
  $days = 0;
  foreach($meds as $m)
    $days = max($days, $m['����']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['startdate'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
  $params['PRESCRIPTION_ID'] = $oid;
  $params['PRESCRIPTION_NAIGAI'] = $ord['��ʬ'];
  if ($ord['����׻�'] == 1)
    $params['PRESCRIPTION_REGULAR'] = '���';
  else if ($ord['����׻�'] == 2)
    $params['PRESCRIPTION_REGULAR'] = '�׻�';
  else if ($ord['��ʬ'] == '�׻�' or $ord['��ʬ'] == '���') {
    # for old rx app that usese kubun for teiki/rinji
    $params['PRESCRIPTION_REGULAR'] = $ord['��ʬ'];
  }
  else
    $params['PRESCRIPTION_REGULAR'] = '';
    
  
  
  // �ݸ����ֹ�
  // ���ݸ��Ծڵ��桦�ֹ�
  $params['INSURER_NUMBER:%8s'] = $pat['�ݸ����ֹ�'];
  $params['INSURED_KIGO']=$pat['���ݸ��Լ�Ģ�ε���'];
  $params['INSURED_NUMBER']=$pat['���ݸ��Լ�Ģ���ֹ�'];
  
  // ���ŵ��ؽ���ϡ�̾��
  // �����ֹ桢�ݸ����̾
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'��'.$doc['fname'];
  
  // ����
  // preformatted body for shohousen.ods 10-20-2014 set 1 to tab
  $params['BODY'] = implode("\n", set_body($meds, $shots, $ord["generic"]));

  // individual lines
  $a = set_body($meds, $shots, $ord["generic"],1);
  for($i=0; $i < count($a); $i++)
    $params[sprintf("LINE%04d", $i)] = $a[$i];
//10-20-2014
print "no of lines".count($a);
  // ����
  // comment in or append if you want to print a comment from rx
#$params['COMMENT'] = $ord['Comment'];
  $params['COMMENT'] = '';
  $params['COMMENT'] = $pat['����']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '������ô���ֹ�: ' . $pat['������ô���ֹ�3']."\n";
    $params['COMMENT'] .= '������ô���Ťμ�����ֹ�: ' . $pat['������ô���Ťμ�����ֹ�3'] . "\n";
  }

  // ��ȯ�ʲ� changed attribute generic
  if ($ord['generic'] == 1 || $ord['generic'] == '' ) {
    $params['GENERIC_OK'] = '��';
    $params['GENERIC_DR'] = '';
  }else{
    $params['GENERIC_OK'] = '�Բ�';
    $params['GENERIC_DR'] = $params['DOCTOR'];
  }
  
  // ʴ��
  if ($ord['funsai'] == 1)
    $params['FUNSAI'] = 'ʴ��';
  else
    $params['FUNSAI'] = '';

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;
//04-13-2012 for new format
//print ooo_print_pdf3($params);
//0703-2012 for old-format
 print ooo_print_pdf($params);
print "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
  if(file_exists($pdf_path)) {
    //---- read pdf file
    $handler = fopen($pdf_path, 'rb');
    $content = fread($handler, filesize($pdf_path));
    fclose($handler);
    unlink($pdf_path);

    //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
    $type = '�����';
    if($shots)
      $type = '����';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "��ͽ����" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "���޽����" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }
    pg_query($db, $stmt);
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
      '/generated.pdf","","width=800,height=800");
</SCRIPT>';
    
  }else{
    print "PDF�������˼��Ԥ��ޤ���";
  }
}


//

?>
