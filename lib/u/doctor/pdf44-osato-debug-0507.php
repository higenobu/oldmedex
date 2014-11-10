<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
//for osato 0508-2014 ussing pastdata
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
//
//select japanese content

function selectjp($inf)
{
$strall="";    
$inf_len = mb_strlen($inf);
$pos1=mb_strpos($inf, "[");
 
 
if ($pos1!==false){
 
while($pos1!==false){
if ($pos1>0)
$strall=$strall.mb_substr($inf,0,$pos1-1);
$inf=mb_substr($inf,$pos1+1,$inf_len);
 
$pos2=mb_strpos($inf, "]");
 
if ($pos2!==false){
$inf=mb_substr($inf,$pos2+1,$inf_len);
$pos1=mb_strpos($inf, "[");
if ($pos1==false){
$strall=$strall.$inf;
}
 
 }
else {
$pos1=false; 
}
 
 
 }
}
else {

$strall=$inf;

}


return $strall;
}
// select en content
function selecten($inf)
{
$strall="";    
$inf_len = mb_strlen($inf);
 
$pos1=mb_strpos($inf, "[");
 
 
if ($pos1!==false){
 
while($pos1!==false){

 
$inf=mb_substr($inf,$pos1+1,$inf_len);
 
$pos2=mb_strpos($inf, "]");
 
if ($pos2!==false){
//0207-2014
$strall=$strall.mb_substr($inf,0,$pos2);
$inf=mb_substr($inf,$pos2+1,$inf_len);
$pos1=mb_strpos($inf, "[");
if ($pos1==false){
 
}
 
 }
else {
$pos1=false; 
}
 
 
 }
}
else {

 
}
      
//print $strall;

 return $strall; 
       
	
}

//for japanese
function go_pdf($oid, $shots, $template=NULL) 
{

$db = mx_db_connect();

$stmt = <<<SQL
SELECT * from attrlist 
SQL;

$rows5 =  mx_db_fetch_all($db, $stmt);
$ii=0;
  	 
  foreach($rows5 as $row5)
 {
 
$attrs[$ii]=$row5['attrnm'];	
 $ii++;
}


 

$ord = get_otatest_order($oid, $shots);


//0920-2011  記録者-> CreatedBy

//  $doc = get_emp_name($ord['CreatedBy']);

$past = get_otatest_pastorder($ord['patient'],$oid,$ord['order_date']);

  $pat = get_patient($ord['patient'],false);
  
  $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  $kubun=array();

 
  // prepare values to be embedded into PDF
  $params = array();
  
  
    $params['PRESCRIPTION_TITLE'] = 'OTATEST';
  
  
   // 患者氏名、生年月日、区分、割合
  $params['PATIENT_ID'] = $pat['患者ID'];
  $params['PATIENT_KANA'] = $pat['フリガナ'];
 
  $params['PATIENT_KANJI'] = $pat['姓'] .','. $pat['名'];
$params['PATIENT_DOB'] = $pat['生年月日'] ;
//  $params['PATIENT_DOB'] = mx_wareki($pat['生年月日']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['生年月日']);
  $params['PATIENT_SEX'] = $pat['性別'] == 'M' ? '男' : '女';
  $params['PATIENT_KUBUN'] = $pat['被保険者'] == '1' ? '本人' : '家族';
  $params['PATIENT_GROUP'] = $pat['希望病棟'];


  $params['PRESCRIPTION_DATE'] = $ord['order_date'];
    $params['PREORDERDATE'] = $ord['preorderdate'];                                      
 $age= $params['PATIENT_AGE'];
$params['PATIENT_ZIP'] = $pat['住所0'];
  $params['PATIENT_ADDR1'] = $pat['住所1'];
$params['PATIENT_ADDR2'] = $pat['住所2'];
  $params['PATIENT_ADDR3'] = $pat['住所3'];
$params['PATIENT_OCC'] = $pat['勤務先名'];
$params['PTDEPT'] = $pat['請求先名'];
$params['PTBR'] = $pat['請求先住所'];
//0211-2014
 
if ($pat["tel"] && !strpos($pat["tel"],"-")) {

      $pat["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($pat["tel"],0,3),
		substr($pat["tel"],3,3),
		substr($pat["tel"],6,4));
    }
//
$params['PTTEL'] = $pat['tel'];

$params['CTRY'] = $pat['country'];
 
 for ($kk=0;$kk<count($attrs);$kk++)
{

$attrnm=$attrs[$kk];
 $params[$attrnm] = $ord[$attrnm];
//print $attrnm."<br>\n";
}

 

 for ($kk=0;$kk<count($attrs);$kk++)
{

$attrnm=$attrs[$kk];
$attrnm2="b".$attrnm;
 if (substr($attrnm,0,1)=='a'){
 $params[$attrnm2] = $past[$attrnm];
 print $attrnm2."<br>\n";}

}




//


 
$params['CAT'] = $ord['category'];
 
$params['pdf'] = $ord['pdf'];

$ejboth = $ord['h12'];
//
if ($ejboth=='1'){

//this is for japanese
 
$inf=$params['c560'];
$params['c560']=selectjp($inf);
 
$inf=$params['c561'];
$params['c561']=selectjp($inf);
$inf=$params['cc0'];
 
$params['cc0']=selectjp($inf);
//
 $inf=$params['ss0'];
 
$params['ss0']=selectjp($inf);
//
 $inf=$params['ss1'];
 
$params['ss1']=selectjp($inf);
 
$inf=$params['notes'];
 
$params['notes']=selectjp($inf);
//
$inf=$params['special_req'];
 
$params['special_req']=selectjp($inf);
//
$inf=$params['c1002'];
 
$params['c1002']=selectjp($inf);
//

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="c50".$kk;
$inf=$params[$index];
$params[$index]=selectjp($inf);

 }
 
$kk=0;
 for ($kk=0;$kk<6;$kk++)
{
 
 
$index="c51".$kk;
$inf=$params[$index];
$params[$index]=selectjp($inf);

 }
 
 
 // 
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="cc40".$kk;
$inf=$params[$index];
$params[$index]=selectjp($inf);

 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="cc41".$kk;
$inf=$params[$index];
$params[$index]=selectjp($inf);

 }
}

//


//for english
if ($ejboth=='2'){

$inf=$params['c560'];
$params['c560']=selecten($inf);
$inf=$params['c561'];
$params['c561']=selecten($inf);
$inf=$params['c510'];
$params['c510']=selecten($inf);
$inf=$params['cc0'];
 
$params['cc0']=selecten($inf);
$inf=$params['ss0'];
 
$params['ss0']=selecten($inf);
$inf=$params['ss1'];
 
$params['ss1']=selecten($inf);
 
//
 
$inf=$params['notes'];
 
$params['notes']=selecten($inf);
//
$inf=$params['special_req'];
 
$params['special_req']=selecten($inf);
//
$inf=$params['c1002'];
 
$params['c1002']=selecten($inf);
//

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="c50".$kk;
$inf=$params[$index];
$params[$index]=selecten($inf);

 }
$kk=0;
 for ($kk=0;$kk<6;$kk++)
{
 
 
$index="c51".$kk;
$inf=$params[$index];
$params[$index]=selecten($inf);

 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="cc40".$kk;
$inf=$params[$index];
$params[$index]=selecten($inf);

 }
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="cc41".$kk;
$inf=$params[$index];
$params[$index]=selecten($inf);

 }
 
 $params['PATIENT_SEX'] = $pat['性別'] == 'M' ? 'Male' : 'Female';


}
//above for engish

 	
// haikaturyo
$height=$params['k100'];
if ($pat['性別']=='M'){
 $params['k202'] = (27.63 - 0.112 * $age) * $height;
 $params['SEX'] =0;
 }
else {
$params['SEX'] =1;
$params['k202'] = (21.78 - 0.101 * $age) * $height;
}


	
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
//  $params['DOCTOR'] = $ord['shiji'];
   $params['DOCTOR'] = $ord['shiji'].", M.D.";
   
  $params['BODY'] = "   ";
 

//$ejboth = $ord['h12'];
 
//0115-2014
$template = "osato.ods";
if ($ejboth=='1')
$template = "osato.ods";
 
if ($ejboth=='2')
$template = "osato2.ods";
if ($ejboth=='3')
$template = "osato3.ods";

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
//0115-2014
$params['TEMPLATE'] = "osato.ods";

if ($ejboth=='1')
  $params['TEMPLATE'] = "osato.ods";
 
if ($ejboth=='2')
  $params['TEMPLATE'] = "osato2.ods";
if ($ejboth=='3')
  $params['TEMPLATE'] = "osato3.ods";	

  print ooo_print_pdf2($params);
//0315-2013
$ppid=$pat['患者ID'];
//use patient-id as file name
$ppid2=substr($ppid,0,8);
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
	$type = 'karte';
 
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
 

    print '
<SCRIPT LANGUAGE="JavaScript"> 
  window.open("/blobmedia.php/' . $id .
      '/'.$pdfname.'","","width=640,height=640");
</SCRIPT>';
    
  }else{
    print "PDFの生成に失敗しました";
  }


}

?>
