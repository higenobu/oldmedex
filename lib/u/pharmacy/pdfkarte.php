<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';

function get_patientorca($id,$type)
{
  
   

$con =  pg_connect("host=localhost dbname=orca user=orca ");


$query = 'select a.ptid, a.name as ptname,a.birthday as ptdob,home_post, home_adrs, home_tel1, setainusi,
a.sex as ptsex, 
b.hknid, b.hknjanum,b.kigo,b.num, b.hihknjaname, b.kakuninymd, d.hknjaname, d.adrs

from tbl_ptinf a,

  tbl_pthkninf b,
  
 tbl_hknjainf d,
tbl_ptnum p

where
 
a.ptid=b.ptid and
b.hknjanum=d.hknjanum  and
a.ptid=p.ptid and
 p.ptnum=';
 

$cond11="'".$id."'";	

 

$query = $query.$cond11;




//0615-2011  	
$query = $query."  order by hknid desc  limit 1";

$res = pg_query($con, $query);

 


  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;

 pg_close($con);

  return $pat;
}

//0920-2011

function go_pdfkarte($oid, $shots, $template=NULL) 
{

 global $_mx_rx_template;
  
$template='shohousen_k.ods';

 $_mx_rx_template=$template;

 
   $db = mx_db_connect();

    $stmt = 'SELECT "ID",  "����" ,"����" from "����ƥǥ�ɽ"  WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);

 
 


  $pat = get_patient($rs['����'],false);

 // $pat = get_patientorca($ptid,false);
 

 // $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  

  $params = array();
 
 $params['BODY'] = "  ";

  
 $params['PRESCRIPTION_TITLE'] = '����Ͽ';
  
  // ������ô���ֹ�
  // ������ֹ�
  $params['WELFARE_NUMBER:%8s'] = $pat['kohnum'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['ftnjanum'];
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
  
  $params['PATIENT_SEX'] = $pat['����'] == 'M' ? '��' : '��';

$params['PatientGroup'] = $pat['��˾����'];
  $params['PatientAge'] = mx_calc_age($pat['��ǯ����']);
$params['PatientGroup'] = $pat['��˾����'];
  $params['PatientAge'] = mx_calc_age($pat['��ǯ����']);
  
   
  
  $params['PATIENT_KUBUN'] = $pat['���ݸ���'] == '1' ? '�ܿ�' : '��²';
  $params['PATIENT_GROUP'] = $pat['��˾����'];

 

  
  // ����ǯ����
  $params['PRESCRIPTION_DATE'] = mx_wareki($rs['����']);
                                           

  
  
  
  // �ݸ����ֹ�
  // ���ݸ��Ծڵ��桦�ֹ�
  $params['INSURER_NUMBER:%8s'] = $pat['hknjanum'];
  $params['INSURED_KIGO']=$pat['kigo'];
  $params['INSURED_NUMBER']=$pat['num'];
  
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
//  $db = mx_db_connect();
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
      $type = '�����';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "���ͽ����" SET "PDF"=' . mx_db_sql_quote($id) .
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
