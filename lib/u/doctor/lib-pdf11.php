<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common11.php';
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
    print "PDF�������˼��Ԥ��ޤ���";
  }
}



function go_pdf($oid, $shots, $template=NULL) 
{
  global $_mx_rx_template;
//$_mx_rx_template = "xctform.ods";

  if(is_null($template))
    $template = $_mx_rx_template;
  // read DB
 // 03152011 -05-52011
 $ord = get_insident($oid, $shots);
 // $doc = get_emp_name($ord['empnm1']);



  $pat = get_patient($ord['pnm1'],false);
  
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  
  
  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = '���󥷥ǥ��';
  
  
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
    $params['PATIENT_INOUT'] = '';

  //TODO: take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['������'];
  $params['PATIENT_ROOM'] = $pat['�¼�'];
  $params['DEDUCTIBLE'] = '';
  
  // ����ǯ����
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['reportdate'],
                                           array('dayofweek'=>1));
  $params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['factdate']);
  // NEEDSWORK: ���������� + max(������
  $days = 0;
/*
  foreach($meds as $m)
    $days = max($days, $m['����']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['plandate'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
*/

  $params['PRESCRIPTION_ID'] = $oid;

  


  
  
     $params['factplan'] = $ord['factplan'];
 $params['factcont'] = $ord['factcont'];
$params['factdone'] = $ord['factdone'];
$params['ORDER_ID'] = $oid;





  // ���ŵ��ؽ���ϡ�̾��
  // �����ֹ桢�ݸ����̾
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $ord['empnm1'];
  
  // ����
  // 03152011
  $params['BODY'] = "   ";

 


 
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

?>
