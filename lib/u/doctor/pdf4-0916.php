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
//$_mx_rx_template = "xctform.ods";
//debug 071-2011


  if(is_null($template))
    $template = $_mx_rx_template;
  // read DB
 // 03152011 -05-52011
 //$ord = get_pharm_order($oid, $shots);
$ord = get_xct_order($oid, $shots);
  $doc = get_emp_name($ord['記録者']);
 $ret = _lib_u_xct_get_bui();

 // $meds = get_pharm_order($oid,$shots);
  $pat = get_patient($ord['患者'],false);
  
  $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  $kubun=array();


  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = '照射録';
  
  
   // 患者氏名、生年月日、区分、割合
  $params['PATIENT_ID'] = $pat['患者ID'];
  $params['PATIENT_KANA'] = $pat['フリガナ'];
  $params['PATIENT_KANJI'] = $pat['姓'] .'　'. $pat['名'];
  $params['PATIENT_DOB'] = mx_wareki($pat['生年月日']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['生年月日']);
  $params['PATIENT_SEX'] = $pat['性別'] == 'M' ? '男' : '女';
  $params['PATIENT_KUBUN'] = $pat['被保険者'] == '1' ? '本人' : '家族';
  $params['PATIENT_GROUP'] = $pat['希望病棟'];
  if ($pat['入外区分'] == 'I'){
    $params['PATIENT_INOUT'] = '入院';
 $params['PATIENT_ROOM'] = $pat['病室'];}
  else if ($pat['入外区分'] == 'O'){
    $params['PATIENT_INOUT'] = '外来';
 $params['PATIENT_ROOM'] = '';
	}

  else {
    $params['PATIENT_INOUT'] = '';
	$params['PATIENT_ROOM'] = '';
}


  //TODO: 0721-2011 take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['入院科'];


  $params['DEDUCTIBLE'] = '';
  
  // 交付年月日
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['orderdate'],
                                           array('dayofweek'=>1));
  $params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['procdate']);
if ($ord['procdate'] == null)
    $params['PRESCRIPTION_STARTDATE'] = null; 
  // NEEDSWORK: 処方開始日 + max(日数）
  $days = 0;
/*
  foreach($meds as $m)
    $days = max($days, $m['日数']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['plandate'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
*/

  $params['PRESCRIPTION_ID'] = $oid;
  $params['PRESCRIPTION_NAIGAI'] = $ord['bui1'];
  
    $params['PRESCRIPTION_REGULAR'] = $ord['memo1'];
	 $params['SHIJI'] = $ord['shiji'] ;

$params['KUBUN']='XP';
 if ($ord['xctkubun'] =='170011810'){
$params['KUBUN']='CT';
	}
 if ($ord['xctkubun'] =='170020110'){
$params['KUBUN']='MRI';
	}

 if ($ord['xctkubun'] =='170021910'){
$params['KUBUN']='XP';
	}

 if ($ord['xctkubun'] =='170001910'){
$params['KUBUN']='XP';
        }
$params['GISHI'] =  $ord['gishi'] ;
$params['MEMO4'] =  $ord['memo4'] ;
$params['BUI1'] = $ret[$ord['bui1']];
     $params['BUI2'] = $ret[$ord['bui2']];
$params['BUI3'] = $ret[$ord['bui3']];
$params['BUI4'] = $ret[$ord['bui4']];
$params['BUI5'] = $ret[$ord['bui5']];
  $params['HOKO1'] =  $ord['syoken1'];
     $params['HOKO2'] = $ord['syoken2'];
$params['HOKO3'] = $ord['syoken3'];
$params['HOKO4'] = $ord['syoken4'];
$params['HOKO5'] = $ord['syoken5'];
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




  // 医療機関所在地、名称
  // 電話番号、保険医氏名
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'　'.$doc['fname'];
  
  // 処方
  // 03152011
  $params['BODY'] = "   ";

 

$template = "shohousen_tg10.ods";
 
  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = "shohousen_tg10.ods";
	

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
