<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
//0422-2014 revised
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
//for osato clinic


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
/*
$stream1=fopen("/home/medex/attrlist","rb");
$ii=0; 
while ($info1 =fgetcsv($stream1,1024)){

//echo $info1[0] . ",\n";
 
$attrs[$ii]=$info1[0];	
 $ii++;
}
print count($attrs)."aaa\n";
*/
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


//0920-2011  ��Ͽ��-> CreatedBy

  $doc = get_emp_name($ord['CreatedBy']);



  $pat = get_patient($ord['patient'],false);
  
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


  $params['PRESCRIPTION_DATE'] = $ord['order_date'];
    $params['PREORDERDATE'] = $ord['preorderdate'];                                      
 $age= $params['PATIENT_AGE'];
$params['PATIENT_ZIP'] = $pat['����0'];
  $params['PATIENT_ADDR1'] = $pat['����1'];
$params['PATIENT_ADDR2'] = $pat['����2'];
  $params['PATIENT_ADDR3'] = $pat['����3'];
$params['PATIENT_OCC'] = $pat['��̳��̾'];
//


for ($kk=0;$kk<count($attrs);$kk++)
{

$attrnm=$attrs[$kk];
 $params[$attrnm] = $ord[$attrnm];
//print $attrnm."<br>\n";
}






$params['notes'] = $ord['notes'];
$params['special_req'] = $ord['special_req'];
//english or japanese
//04-25-2014
$ej=$ord['category'];
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


//
//for english
if ($ej=='2'){
$index="h1";
$inf=$params[$index];
 
$params[$index]=mb_str_replace("�긫����","2",$inf);

if (mb_substr($inf,0,1)=='��'){$params[$index]='1';}
if (mb_substr($inf,0,1)==''){$params[$index]='4';}
if (mb_substr($inf,0,1)=='N'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='3';}
 //
$index="h2";
$inf=$params[$index];
 
$params[$index]=mb_str_replace("�긫����","2",$inf);
if (mb_substr($inf,0,1)=='��'){$params[$index]='1';}
if (mb_substr($inf,0,1)==''){$params[$index]='4';}
if (mb_substr($inf,0,1)=='N'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='3';}
//
$index="k520";
$inf=$params[$index];
 
$params[$index]=mb_str_replace("�긫����","2",$inf);
if (mb_substr($inf,0,1)=='��'){$params[$index]='1';}
if (mb_substr($inf,0,1)==''){$params[$index]='4';}
if (mb_substr($inf,0,1)=='N'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='3';}
$index="k1002";
$inf=$params[$index];
 
$params[$index]=mb_str_replace("�긫����","2",$inf);
if (mb_substr($inf,0,1)=='��'){$params[$index]='1';}
if (mb_substr($inf,0,1)==''){$params[$index]='0';}
if (mb_substr($inf,0,1)=='N'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='3';}

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{ 
$index="k50".$kk;
$inf=$params[$index];
 
$params[$index]=mb_str_replace("�긫����","2",$inf);
if (mb_substr($inf,0,1)=='��'){$params[$index]='1';}
if (mb_substr($inf,0,1)==''){$params[$index]='4';}
if (mb_substr($inf,0,1)=='N'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='3';}
} 
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{ 
$index="k51".$kk;
$inf=$params[$index];
 
$params[$index]=mb_str_replace("�긫����","2",$inf);
if (mb_substr($inf,0,1)=='��'){$params[$index]='1';}
if (mb_substr($inf,0,1)==''){$params[$index]='4';}
if (mb_substr($inf,0,1)=='N'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='4';}
if (mb_substr($inf,0,1)=='��'){$params[$index]='3';}
 }


}



//
//

// haikaturyo
$height=$params['k100'];
if ($pat['����']=='M'){
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
  
  $params['DOCTOR'] = $doc['lname'] .'��'.$doc['fname'];
  
   
  $params['BODY'] = "   ";

 
//0710-2012

$template = "osato.ods";
if ($ej==2){  $template = "osato2.ods";}

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
//0710-2012
  $params['TEMPLATE'] = "osato.ods";
	
if ($ej==2){ $params['TEMPLATE'] = "osato2.ods";
$params['PATIENT_SEX'] = $pat['����'] == 'M' ? 'Male' : 'Female';
}
//for Osato clinic
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
 
$type = 'karte';
 
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    //HACK: open window and show PDF for client-side printing
    print '
<SCRIPT LANGUAGE="JavaScript"> 
  window.open("/blobmedia.php/' . $id .
      '/'.$pdfname.'","","width=640,height=640");
</SCRIPT>';
    
  }else{
    print "PDF�������˼���";
  }


}

?>
