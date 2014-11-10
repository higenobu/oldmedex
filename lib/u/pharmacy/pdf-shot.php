<?php // -*- coding: euc-jp -*-
//pdf.php
//insert to orderinfo
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';





function go_pdf_new($oid, $shots, $template=NULL) 
{
  global $_mx_rx_template;
/*
  if(is_null($template))
    $template = $_mx_rx_template;
  // read DB
  $ord = get_pharm_order($oid, $shots);
  $doc = get_emp_name($ord['記録者']);
//10-20-2014
  $meds = get_meds($oid,0);
  $pat = get_patient($ord['患者'],false);
  
  $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  
  
  // prepare values to be embedded into PDF
  $params = array();
  
  if ($ord['停止日'])
    $params['PRESCRIPTION_TITLE'] = '【中止】処 方 せ ん';
  else
    $params['PRESCRIPTION_TITLE'] = '処　　方　　せ　　ん';
  if($shots) {
    if ($ord['停止日'])
      $params['PRESCRIPTION_TITLE'] = '【中止】注 射 せ ん';
    else
      $params['PRESCRIPTION_TITLE'] = '注　　射　　せ　　ん';
  }
  
  // 公費負担者番号
  // 受給者番号
  $params['WELFARE_NUMBER:%8s'] = $pat['公費負担者番号'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['公費負担医療の受給者番号'];
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
  $params['PATIENT_KANJI'] = $pat['姓'] .'　'. $pat['名'];
  $params['PATIENT_DOB'] = mx_wareki($pat['生年月日']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['生年月日']);
  $params['PATIENT_SEX'] = $pat['性別'] == 'M' ? '男' : '女';
  $params['PATIENT_KUBUN'] = $pat['被保険者'] == '1' ? '本人' : '家族';
  $params['PATIENT_GROUP'] = $pat['希望病棟'];
  if ($pat['入外区分'] == 'I')
    $params['PATIENT_INOUT'] = '入院';
  else if ($pat['入外区分'] == 'O')
    $params['PATIENT_INOUT'] = '外来';
  else
    $params['PATIENT_INOUT'] = '';

  //TODO: take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['入院科'];
  $params['PATIENT_ROOM'] = $pat['病室'];
  $params['DEDUCTIBLE'] = '';
  
  // 交付年月日
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['処方年月日'],
                                           array('dayofweek'=>1));
  $params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['処方開始日']);
  // NEEDSWORK: 処方開始日 + max(日数）
  $days = 0;
  foreach($meds as $m)
    $days = max($days, $m['日数']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['処方開始日'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
  $params['PRESCRIPTION_ID'] = $oid;
  $params['PRESCRIPTION_NAIGAI'] = $ord['区分'];
  if ($ord['定期臨時'] == 1)
    $params['PRESCRIPTION_REGULAR'] = '定期';
  else if ($ord['定期臨時'] == 2)
    $params['PRESCRIPTION_REGULAR'] = '臨時';
  else if ($ord['区分'] == '臨時' or $ord['区分'] == '定期') {
    # for old rx app that usese kubun for teiki/rinji
    $params['PRESCRIPTION_REGULAR'] = $ord['区分'];
  }
  else
    $params['PRESCRIPTION_REGULAR'] = '';
    
  
  
  // 保険者番号
  // 被保険者証記号・番号
  $params['INSURER_NUMBER:%8s'] = $pat['保険者番号'];
  $params['INSURED_KIGO']=$pat['被保険者手帳の記号'];
  $params['INSURED_NUMBER']=$pat['被保険者手帳の番号'];
  
  // 医療機関所在地、名称
  // 電話番号、保険医氏名
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'　'.$doc['fname'];
  
  // 処方
  // preformatted body for shohousen.ods 10-20-2014 added 1 to set_body last para
$params['BODY'] = implode("\n", set_body($meds, $shots, $ord["後発品"],1)). "\n";


 

  // individual lines
  $a = set_body($meds, $shots, $ord["後発品"], 1);

  for($i=0; $i < count($a); $i++)
    $params[sprintf("LINE%04d", $i)] = $a[$i];

$b="";
for($i=0; $i < count($a); $i++){
   $b =$b."\n". $a[$i];
//print "<br>". $a[$i]."\n";

}

  // 備考
  // comment in or append if you want to print a comment from rx
#$params['COMMENT'] = $ord['Comment'];
  $params['COMMENT'] = '';
  $params['COMMENT'] = $pat['備考']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '公費負担者番号: ' . $pat['公費負担者番号3']."\n";
    $params['COMMENT'] .= '公費負担医療の受給者番号: ' . $pat['公費負担医療の受給者番号3'] . "\n";
  }

  // 後発品可
  if ($ord['後発品'] == 1) {
    $params['GENERIC_OK'] = '可';
    $params['GENERIC_DR'] = '';
  }else{
    $params['GENERIC_OK'] = '不可';
    $params['GENERIC_DR'] = $params['DOCTOR'];
  }
  
  // 粉砕
  if ($ord['funsai'] == 1)
    $params['FUNSAI'] = '粉砕';
  else
    $params['FUNSAI'] = '';

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;
// insert rx-info into orderdb 10-24-2014

$con = mx_db_connect();

$otype="rx";
$odate=$ord['処方年月日'];
$ptid=$ord['患者'];

$ocont="RX\n".$b;
$stmt10 = <<<SQL
SELECT "ID" FROM orderinfo  where oid=$oid and patient=$ptid and ordertype='$otype' limit 1
SQL;
 print $stmt10;

$rs0 = mx_db_fetch_single($con, $stmt10);

if ($rs0 == null){




 $stmt1 = <<<SQL
INSERT INTO orderinfo(
            orderdate, patient, 
            ordertype, "content",oid)
    
    VALUES ('$odate','$ptid', '$otype', '$ocont',$oid)
        
SQL;
//print $stmt1;
if (pg_query($con, $stmt1)){
//print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
}
else {
 

 $stmt1 = <<<SQL
update  orderinfo set "content"='$ocont',"update"=1
		 
             where oid=$oid and patient=$ptid and ordertype='$otype' 
    
  
        
SQL;
print $stmt1;
if (pg_query($con, $stmt1)){

}
else {
print '<p > DB access error</p>';
die;
}


//  }
}
*/

//
  print '<frameset rows="55, *" noresize border="0">
         <frame src="print_inhospshot.php?top=1" name="top_frame" scrolling="no">
         <frame src="print_inhospshot.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
/*
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("print_inhosp.php?' . $id .
      '/generated.pdf","","width=640,height=640");
</SCRIPT>';
  */


}



?>
