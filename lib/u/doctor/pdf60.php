<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
//0920-2011
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';


//for japanese
function go_pdf($oid, $shots, $template=NULL) 
{



 
//0102-2014
$ord = get_formchk($oid, $shots);

$temptype=$ord['a2'];
//0920-2011  ��Ͽ��-> CreatedBy

  $doc = get_emp_name($ord['CreatedBy']);



  $pat = get_patient($ord['����'],false);
  
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  $kubun=array();

 
  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = 'OTATEST';
  
  
   // ���Ի�̾����ǯ��������ʬ�����
  $params['PATIENT_ID'] = $pat['����ID'];
  $params['PATIENT_KANA'] = $pat['�եꥬ��'];
 
  $params['PATIENT_KANJI'] = $pat['��'] .','. $pat['̾'];
$params['PATIENT_DOB'] = $pat['��ǯ����'] ;
//  $params['PATIENT_DOB'] = mx_wareki($pat['��ǯ����']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['��ǯ����']);
  $params['PATIENT_SEX'] = $pat['����'] == 'M' ? '��' : '��';
  $params['PATIENT_KUBUN'] = $pat['���ݸ���'] == '1' ? '�ܿ�' : '��²';
  $params['PATIENT_GROUP'] = $pat['��˾����'];


  $params['order_date'] = $ord['orderdate'];
    $params['PREORDERDATE'] = $ord['preorderdate'];                                      
 $age= $params['PATIENT_AGE'];
$params['PATIENT_ZIP'] = $pat['����0'];
  $params['PATIENT_ADDR1'] = $pat['����1'];
$params['PATIENT_ADDR2'] = $pat['����2'];
  $params['PATIENT_ADDR3'] = $pat['����3'];
$params['PATIENT_OCC'] = $pat['��̳��̾'];
$params['PTDEPT'] = $pat['������̾'];
$params['PTBR'] = $pat['�����轻��'];
//


 
	
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'��'.$doc['fname'];
  
   
  $params['BODY'] = "   ";

 
//0710-2012
if ($temptype==""){$template = "temp01.ods";}
if ($temptype==1){$template = "temp01.ods";}
if ($temptype==2){$template = "temp02.ods";}
if ($temptype==3){$template = "temp03.ods";}
if ($temptype==4){$template = "temp04.ods";}

if ($temptype==5){$template = "temp05.ods";}
if ($temptype==6){$template = "temp06.ods";}
if ($temptype==7){$template = "temp07.ods";}
if ($temptype==8){$template = "temp08.ods";}

 
  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
//0101-2014
$params['TEMPLATE'] = "temp01.ods";
if ($temptype==""){$params['TEMPLATE'] = "temp01.ods";}
if ($temptype==1){$params['TEMPLATE'] = "temp01.ods";}
if ($temptype==2){$params['TEMPLATE'] = "temp02.ods";}
if ($temptype==3){$params['TEMPLATE'] = "temp03.ods";}
if ($temptype==4){$params['TEMPLATE'] = "temp04.ods";}
 if ($temptype==5){$params['TEMPLATE'] = "temp05.ods";}
if ($temptype==6){$params['TEMPLATE'] = "temp06.ods";}
if ($temptype==7){$params['TEMPLATE'] = "temp07.ods";}
if ($temptype==8){$params['TEMPLATE'] = "temp08.ods";} 
	

  print ooo_print_pdf2($params);
//0315-2013
$ppid=$pat['����ID'];
//use patient-id as file name
$ppid2=substr($ppid,0,6);
$pdfname=$ppid2.".pdf";
//0315-2013
  if(file_exists($pdf_path)) {
    //---- read pdf file
    $handler = fopen($pdf_path, 'rb');
    $content = fread($handler, filesize($pdf_path));
    fclose($handler);
    unlink($pdf_path);

    //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
//0413-2012
    $type = '�����';
//    if($shots)
//      $type = '����';
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
      '/'.$pdfname.'","","width=640,height=640");
</SCRIPT>';
    
  }else{
    print "PDF�������˼��Ԥ��ޤ���";
  }


}

?>
