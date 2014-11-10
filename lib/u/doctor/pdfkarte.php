<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
// include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
// include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/ord_common.php';

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

 

  return $pat;
}


function go_pdf($oid, $shots, $template=NULL) 
{

 global $_mx_rx_template;
  
$template='karte10.ods';

 
 
   $db = mx_db_connect();

    $stmt = 'SELECT "ID",  "´µ¼Ô" ,"ÆüÉÕ" from "¥«¥ë¥Æ¥Ç¥âÉ½"  WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);

 
 

  $pat1 = get_patient($rs['´µ¼Ô'],false);
$ptid=$pat1['´µ¼ÔID'];
$pat=array();

$pat = get_patientorca($ptid,false);


  $pat['´µ¼ÔID'] = ereg_replace("^(.*) .*","\\1",$pat1['´µ¼ÔID']);
  

  $params = array();
 
 $params['BODY'] = "  ";

  
 $params['PRESCRIPTION_TITLE'] = 'KARTE';
  
  // ¸øÈñÉéÃ´¼ÔÈÖ¹æ
  // ¼õµë¼ÔÈÖ¹æ
  $params['WELFARE_NUMBER:%8s'] = $pat['kohnum'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['ftnjanum'];
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
  $params['PATIENT_KANJI'] = $pat['ptname'];
   
   
  
  $params['PATIENT_KUBUN'] = $pat['ÈïÊÝ¸±¼Ô'] == '1' ? 'ËÜ¿Í' : '²ÈÂ²';
  $params['PATIENT_GROUP'] = $pat['´õË¾ÉÂÅï'];
   $dob = substr($pat['ptdob'],0,4) . "-" . substr($pat['ptdob'],4,2) . "-".substr($pat['ptdob'],6,2); 
 $params['PATIENT_DOB'] = mx_wareki($dob);
$params['PATIENT_AGE'] = mx_calc_age($dob);
  $params['PATIENT_SEX'] = $pat['ptsex'] == '1' ? 'ÃË' : '½÷';
 
$data['PatientAddr'] = $pat['home_post']." ".$pat['home_adrs']." ".$pat['home_tel1'];
  
  
  // ¸òÉÕÇ¯·îÆü
  $params['PRESCRIPTION_DATE'] = mx_wareki($rs['ÆüÉÕ']);
                                           

  
  
  
  // ÊÝ¸±¼ÔÈÖ¹æ
  // ÈïÊÝ¸±¼Ô¾Úµ­¹æ¡¦ÈÖ¹æ
  $params['INSURER_NUMBER:%8s'] = $pat['hknjanum'];
  $params['INSURED_KIGO']=$pat['kigo'];
  $params['INSURED_NUMBER']=$pat['num'];
  
  // °åÎÅµ¡´Ø½êºßÃÏ¡¢Ì¾¾Î
  // ÅÅÏÃÈÖ¹æ¡¢ÊÝ¸±°å»áÌ¾
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  
  
  $params['BODY'] = ' ';

  
  $params['COMMENT'] = '';
  $params['COMMENT'] = $pat['È÷¹Í']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '¸øÈñÉéÃ´¼ÔÈÖ¹æ: ' . $pat['¸øÈñÉéÃ´¼ÔÈÖ¹æ3']."\n";
    $params['COMMENT'] .= '¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ: ' . $pat['¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3'] . "\n";
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
