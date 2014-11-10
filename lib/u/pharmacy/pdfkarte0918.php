<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';

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
  $params['PATIENT_ID'] = $pat['患者ID'];
  $params['PATIENT_KANA'] = $pat['フリガナ'];
  $params['PATIENT_KANJI'] = $pat['姓'] .'　'. $pat['名'];

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
    print "PDFの生成に失敗しました";
  }
}

function go_pdf($oid, $shots, $template=NULL) 
{

 global $_mx_rx_template;
  
$template='shohousen.ods';

 
 
   $db = mx_db_connect();

    $stmt = 'SELECT "ID",  "患者" ,"日付" from "カルテデモ表"  WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);

 
 

  $pat = get_patient($rs['患者'],false);
$ptid=$pat['患者ID'];
$pat=array();
 
 $pat = get_patientorca($ptid,false);
 

 $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  

  $params = array();
 
 $params['BODY'] = "  ";

  
 $params['PRESCRIPTION_TITLE'] = 'KARTE1';
  
  // 公費負担者番号
  // 受給者番号
  $params['WELFARE_NUMBER:%8s'] = $pat['kohnum'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['ftnjanum'];
  $params['WELFARE_GOOD_THRU'] = $pat['公費有効期限'];
  
  $params['WELFARE_NUMBER2:%8s'] = $pat['公費負担者番号2'];
  $params['WELFARE_RECIPIENT2:%7s'] = $pat['公費負担医療の受給者番号2'];
  $params['WELFARE_GOOD_THRU2'] = $pat['公費有効期限2'];
  
  $params['WELFARE_NUMBER3'] = $pat['公費負担者番号3'];
  $params['WELFARE_RECIPIENT3'] = $pat['公費負担医療の受給者番号3'];
  $params['WELFARE_GOOD_THRU3'] = $pat['公費有効期限3'];
  
  // 患者氏名、生年月日、区分、割合
  $params['PATIENT_ID'] = $pat['患者ID'];
  $params['PATIENT_KANA'] = $pat['フリガナ'];
  $params['PATIENT_KANJI'] = $pat['ptname'];
   
   
  
  $params['PATIENT_KUBUN'] = $pat['被保険者'] == '1' ? '本人' : '家族';
  $params['PATIENT_GROUP'] = $pat['希望病棟'];
   $dob = substr($pat['ptdob'],0,4) . "-" . substr($pat['ptdob'],4,2) . "-".substr($pat['ptdob'],6,2); 
 $params['PATIENT_DOB'] = mx_wareki($dob);
$params['PATIENT_AGE'] = mx_calc_age($dob);
  $params['PATIENT_SEX'] = $pat['ptsex'] == '1' ? '男' : '女';
 
$data['PatientAddr'] = $pat['home_post']." ".$pat['home_adrs']." ".$pat['home_tel1'];
  
  
  // 交付年月日
  $params['PRESCRIPTION_DATE'] = mx_wareki($rs['日付']);
                                           

  
  
  
  // 保険者番号
  // 被保険者証記号・番号
  $params['INSURER_NUMBER:%8s'] = $pat['hknjanum'];
  $params['INSURED_KIGO']=$pat['kigo'];
  $params['INSURED_NUMBER']=$pat['num'];
  
  // 医療機関所在地、名称
  // 電話番号、保険医氏名
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  
  
  $params['BODY'] = ' ';

  
  $params['COMMENT'] = '';
  $params['COMMENT'] = $pat['備考']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '公費負担者番号: ' . $pat['公費負担者番号3']."\n";
    $params['COMMENT'] .= '公費負担医療の受給者番号: ' . $pat['公費負担医療の受給者番号3'] . "\n";
  }


  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;
//  $db = mx_db_connect();
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
    $type = '処方箋';
    if($shots)
      $type = '注射箋';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
    if($shots) {
      $stmt = 'UPDATE "注射処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "薬剤処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
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
    print "PDFの生成に失敗しました";
  }
}

?>
