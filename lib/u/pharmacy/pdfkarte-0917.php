<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/ord_common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';



function go_pdf($oid, $shots, $template=NULL) 
{

 global $_mx_rx_template;
  
$template='karte10.ods';

 
 
   $db = mx_db_connect();

    $stmt = 'SELECT "ID",  "����" ,"����" from "����ƥǥ�ɽ"  WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);

 
 

  $pat = get_patient($rs['����'],false);

  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  

  $params = array();
 
 $params['BODY'] = "  ";

  
 $params['PRESCRIPTION_TITLE'] = 'KARTE';
  
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
  
  
  // ����ǯ����
  $params['PRESCRIPTION_DATE'] = mx_wareki($rs['����']);
                                           

  
  
  
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
  
  
  
  $params['BODY'] = ' ';

  
  $params['COMMENT'] = '';
  $params['COMMENT'] = $pat['����']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '������ô���ֹ�: ' . $pat['������ô���ֹ�3']."\n";
    $params['COMMENT'] .= '������ô���Ťμ�����ֹ�: ' . $pat['������ô���Ťμ�����ֹ�3'] . "\n";
  }


  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;
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
