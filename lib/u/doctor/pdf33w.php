<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 //for osato-clinic
//updated 0722-2014 newer than runnig server
//added dos 
//kk75 Rh
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common3.php';
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
//
//read attr list from database

function ppattr(){

$db = mx_db_connect();
$stmt0 = <<<SQL
select * from attrlist

SQL;
 


 $rows =  mx_db_fetch_all($db, $stmt0);
  $ppp = array();
$attr = array();
$ii=0;
  foreach($rows as $row)
 {
  

	$attr[$ii]=$row['attrnm'];
//	print $attr[$ii]."\n";
	$ii++; 
 

}
return $attr;
}

//
function ppset($max,$string,$param){

$kk=0;
 for ($kk=0;$kk<$max;$kk++)
{ 
$index="$string".$kk;
$inf=$param[$index];
// print $inf.":";
//0710-2014
if (mb_substr($inf,0,1)=='4'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='1'){$param[$index]='Unremarkable';}
if (mb_substr($inf,0,1)==''){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='0'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='2'){$param[$index]='Remarkable';}
if (mb_substr($inf,0,1)=='3'){$param[$index]='Retest';}


 
$param[$index]=mb_str_replace("所見あり","Remarkable",$inf);
if (mb_substr($inf,0,1)=='異'){$param[$index]='Unremarkable';}
if (mb_substr($inf,0,1)==''){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='N'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='不'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='再'){$param[$index]='Retest';}
 

} 
return $param;

}

function appset($max,$string,$param){

$kk=0;
//print $max;

 for ($kk=0;$kk<$max;$kk++)
{ 
$index="$string".$kk;
$inf=$param[$index];
//print $inf;
 
$param[$index]=mb_str_replace("所見あり","2",$inf);
if (mb_substr($inf,0,1)=='異'){$param[$index]='1';}
//0712-2014
if (mb_substr($inf,0,1)==''){$param[$index]=' ';}
if (mb_substr($inf,0,1)=='N'){$param[$index]='4';}
if (mb_substr($inf,0,1)=='不'){$param[$index]='4';}
if (mb_substr($inf,0,1)=='再'){$param[$index]='3';}
 
 
} 
return $param;

}
// single
function ppset2($string,$param){


  
 
$index="$string";
$inf=$param[$index];
 
 
 $param[$index]=mb_str_replace("所見あり","Remarkable",$inf);
if (mb_substr($inf,0,1)=='異'){$param[$index]='Unremarkable';}
if (mb_substr($inf,0,1)==''){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='N'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='不'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='再'){$param[$index]='Retest';}
 


// print $inf.":";
//0710-2014
if (mb_substr($inf,0,1)=='4'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='1'){$param[$index]='Unremarkable';}
//0712-2014
if (mb_substr($inf,0,1)==''){$param[$index]='';}
if (mb_substr($inf,0,1)=='0'){$param[$index]='N/A';}
if (mb_substr($inf,0,1)=='2'){$param[$index]='Remarkable';}
if (mb_substr($inf,0,1)=='3'){$param[$index]='Retest';}

return $param;

}
function ppset3($string,$param){
//set N/A or null if value is 4, 0,''
//piroly only
$index="$string";
$inf=$param[$index];
//0710-2014
if (mb_substr($inf,0,1)=='4'){$param[$index]='N/A';}
 
if (mb_substr($inf,0,1)==''){$param[$index]=' ';}
if (mb_substr($inf,0,1)=='0'){$param[$index]='';}
 

return $param;

}
function appset2($string,$param){


  
 
$index="$string";
$inf=$param[$index];
 
$param[$index]=mb_str_replace("所見あり","2",$inf);
if (mb_substr($inf,0,1)=='異'){$param[$index]='1';}
if (mb_substr($inf,0,1)==''){$param[$index]='4';}
if (mb_substr($inf,0,1)=='N'){$param[$index]='4';}
if (mb_substr($inf,0,1)=='不'){$param[$index]='4';}
if (mb_substr($inf,0,1)=='再'){$param[$index]='3';}
 
 

return $param;

}

//
function mb_str_replace($needle, $replacement, $haystack)
{
    $needle_len = mb_strlen($needle);
    $replacement_len = mb_strlen($replacement);
    $pos = mb_strpos($haystack, $needle);
    while ($pos !== false)
    {
        $haystack = mb_substr($haystack, 0, $pos) . $replacement
                . mb_substr($haystack, $pos + $needle_len);
        $pos = mb_strpos($haystack, $needle, $pos + $replacement_len);
    }
    return $haystack;
}



//for japanese
function go_pdf($oid, $shots, $template=NULL) 
{


 $params = array();
$att=ppattr(); 

$ord = get_otatest3_order($oid, $shots);
$past=get_otatest3_pastorder ($ord['patient'],$oid,$ord['order_date']);
//added dos attribute


$kk=0;
 for ($kk=0;$kk<count($att);$kk++)
{ 
if (substr($att[$kk],0,1)=='a'){
$bindex="b".$att[$kk];
print $bindex."\n";
$index=$att[$kk];
$params[$bindex]=$past[$index];
print $past[$index].":";
 }
 
}

$kk=0;
 for ($kk=0;$kk<count($att);$kk++)
{ 
 


$index=$att[$kk];
$params[$index]=$ord[$index];
//print $index."+".$params[$index].": ";
 }



  $doc = get_emp_name($ord['CreatedBy']);



  $pat = get_patient($ord['patient'],false);
  
  $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  $kubun=array();

 
   
 
  
  
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

//0722-2014
  $params['PRESCRIPTION_DATE'] = $ord['dos'];
    $params['PREORDERDATE'] = $ord['preorderdate'];                                      
 $age= $params['PATIENT_AGE'];
$params['PATIENT_ZIP'] = $pat['住所0'];
  $params['PATIENT_ADDR1'] = $pat['住所1'];
$params['PATIENT_ADDR2'] = $pat['住所2'];
  $params['PATIENT_ADDR3'] = $pat['住所3'];
$params['PATIENT_OCC'] = $pat['勤務先名'];

//0704-2014 gantei ganatsu
$params['ph1'] = "";
$params['ph2'] = "";
//








 
//for osato clinic

$ej=$ord['category'];

$params['notes'] = $ord['notes'];
$params['special_req'] = $ord['special_req'];
//01-15-2014
if ($params['kk66']=='NEGATIVE'){$params['kk66']="(-)";}

if ($params['kk66']=='POSITIVE'){$params['kk66']="(+)";}
if ($params['kk67']=='NEGATIVE'){$params['kk67']="(-)";}

if ($params['kk67']=='POSITIVE'){$params['kk67']="(+)";}

if ($params['kk65']=='NEGATIVE'){$params['kk65']="(-)";}

if ($params['kk65']=='POSITIVE'){$params['kk65']="(+)";}
if ($params['kk62']=='NEGATIVE'){$params['kk62']="(-)";}

if ($params['kk62']=='POSITIVE'){$params['kk62']="(+)";}

if ($params['kk64']=='NEGATIVE'){$params['kk64']="(-)";}

if ($params['kk64']=='POSITIVE'){$params['kk64']="(+)";}
//0721-2014 Rh
if ($params['kk75']=='NEGATIVE'){$params['kk75']="Rh-";}

if ($params['kk75']=='POSITIVE'){$params['kk75']="Rh+";}



//For english 
//need to convert japanese to index which is used in template for english text
 
if ($ej=='2'){
// print "english template";

$params=appset2('h1',$params);
$params=appset2('h2',$params);
//0715-2014
$params=appset2('k520',$params);
$params=appset2('p520',$params);
//blood type
$params=ppset3('pp70',$params);
$params=ppset3('kk70',$params);
//0805-2014 Rh
$params=ppset3('pp75',$params);
$params=ppset3('kk75',$params);
//0620-2014
//$params=appset2('k1000',$params); right (uncorrected)
//$params=appset2('k1001',$params); left (un)

//keidomyauchoonpa
$params=appset2('p1002',$params);
$params=appset2('k1002',$params);
//pyrori
$k505para=$params['k505'];
$p505para=$params['p505'];
//change  all data from k500-k509 p500-p509, k510-519,p510-519

 $params=appset(10,'k50',$params);
 $params=appset(10,'k51',$params);
 
 $params=appset(10,'p50',$params);
 $params=appset(10,'p51',$params);
//reset pyrori

//0808-2014
$params['k505']=ppset3('k505',$k505para);
$params['p505']=ppset3('p505',$p505para);


}


//


//0710-2014 
if ($params['k300']>135) 
 $params['aa56'] = 1;
 if ($params['k301']>85) 
 $params['aa57'] = 1;
if ($params['p300']>135) 
 $params['baa56'] = 1;
 if ($params['p301']>85) 
 $params['baa57'] = 1; 

// haikaturyo
$height=$params['k100'];
if ($pat['性別']=='M'){
//k202 is not used 0716-2014
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
  
  $params['DOCTOR'] = $doc['lname'] .'　'.$doc['fname'];
  
   
  $params['BODY'] = "   ";

 
//0710-2012
$template = "osato.ods";
if ($ej==3) {  $template = "osato3.ods";}
if ($ej==4) {  $template = "osato4.ods";}
if ($ej==2){  $template = "osato2.ods";

 $params['PATIENT_SEX'] = $pat['性別'] == 'M' ? 'Male' : 'Female';

} 

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
//0710-2012
  $params['TEMPLATE'] = "osato.ods";
if ($ej==2){ $params['TEMPLATE'] = "osato2.ods";}
if ($ej==3){ $params['TEMPLATE'] = "osato3.ods";}
if ($ej==4){ $params['TEMPLATE'] = "osato4.ods";}
  print ooo_print_pdf2($params);
//0315-2013
$ppid=$pat['患者ID'];
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
    $type = '処方箋';
//    if($shots)
//      $type = '注射箋';
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
//comment out 0716-2014
/*
    if($shots) {
      $stmt = 'UPDATE "注射処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }else{
      $stmt = 'UPDATE "薬剤処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
    }
    pg_query($db, $stmt);
*/

    //HACK: open window and show PDF for client-side printing
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
