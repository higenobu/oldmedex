<?php // -*- mode: php; coding: euc-japan -*-

//updated 0910-2014
//k400 is activated for shinsatusyoken
//for osato
//kk75 Ph 
//added dos input
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
//only for osato-clinic pdf print
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf44.php';
//include def2.php
//0918-2014

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/def2.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/abcd.php';


function _lib_u_ota_kiroku2() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "À«" || "Ì¾" as empname , userid
    from "¿¦°÷ÂæÄ¢"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}

 /*

function mk_enum($a) {
	$r = array();
	foreach ($a as $k) {
		if (trim($k) == '') {
			$r[NULL] = '';
		} else {
			$r[$k] = $k;
		}
	}
	return $r;
}

 

$__otatest2_addition_enum = array
(
	'','ÆÃÊÌ','ÉáÄÌ',
);




$__otatest2_exam_enum = array
(
	'N/A','°Û¾ï¤Ê¤·','½ê¸«¤¢¤ê','ºÆ¸¡ºº','ÉÔÌÀ',
);
$__otatest2_hantei_enum = array
(
	'-','A','B','C(1)','C(2)','C(3)','D','E','F','G'
);
//1020-2012
$__otatest2_n_enum = array
(
'0'=>'-','1'=>'Ab',
);
$__otatest2_plus_enum = array
(
	'N/A','(-)','(+)', 
);
$__otatest2_abo_enum = array
(
   'N/A','A','B','O','AB',
);

 $__otatest2_rh_enum = array
 (
 	'N/A','Rh+','Rh-', 
);
$__otatest2_np_enum = array
(
	'N/A','NEGATIVE','POSITIVE', 
);
//0715-2014 added test template

$__otatest2_report_enum = array
(
'1'=>'Japanese', '2'=>'English','3'=>'test Japanese','4'=>'test English' 
);
//0820-2014


*/


//0912-2014
//definition

//$__otatest2_order_cfg = array();

$__otatest2_order_cfg['TABLE'] = 'otatest_order';
$__otatest2_order_cfg['SEQUENCE'] = 'otatest_order_id_seq';
$__otatest2_order_cfg['COLS'] = array();
$__otatest2_order_cfg['ICOLS'] = array();
$__otatest2_order_cfg['ECOLS'] = array();
$__otatest2_order_cfg['LCOLS'] = array();
$__otatest2_order_cfg['DCOLS'] = array();
//testing 0911-2014

/*
//read def

$stream=fopen("/home/medex/defrandom","rb");
$iii=0;
$albl=array();
$aclm=array();
$pclm=array();
$aaa=array();
//0915-2014 added
$ccc=array();
while ($info =fgetcsv($stream,1024,",")){
  
$work=str_replace("'","",$info[0]);
if (substr($work,0,1)=='e') {
$work=str_replace("'","",$info[1]);
$albl[$iii]=$work;
 
$work=str_replace("'","",$info[2]);

 $aclm[$iii]=$work;
$work=str_replace("'","",$info[3]);
 
$pclm[$iii]=$work;
$work=str_replace("'","",$info[4]);
 
$aaa[$iii]=$work;
$work=str_replace("'","",$info[5]);
 $ccc[$iii]=$work;
$iii++;
}
else {
$albl[$iii]=$work;
  

$work=str_replace("'","",$info[1]);

$aclm[$iii]=$work;



$work=str_replace("'","",$info[2]);

$pclm[$iii]=$work;

$work=str_replace("'","",$info[3]);

$aaa[$iii]=$work;
$work=str_replace("'","",$info[4]);
$ccc[$iii]=$work;

$iii++;
}
}
$ijk=0;
for ($kk=0;$kk<1000;$kk++)
{
//
switch ($kk % 6) {
    case 0:
        
$labeli=$albl[$ijk];
 

if (substr($labeli,0,2)=='kk'|| substr($labeli,0,2)=='ss'){
$www=array( 'Column' =>$labeli,'Option' => array('size' =>3));
	}
else {
 $www=array( 'Label' =>$labeli);
	}

$__otatest2_order_cfg['E_RANDOM_LAYOUT'][$kk]=$www;

        break;
    case 1:
         
$labelj=$aclm[$ijk];
$www=array( 'Column' =>$labelj,'Option' => array('size' =>3));
//print $www;
$__otatest2_order_cfg['E_RANDOM_LAYOUT'][$kk]=$www;
//$ijk++; 
        break;
    case 2:         
  $labelj=$pclm[$ijk];
  $www=array( 'Column' =>$labelj, 'Span' => 2,'Option' => array('size' => 1));
  $__otatest2_order_cfg['E_RANDOM_LAYOUT'][$kk]=$www;
//  $ijk++; 
        break;
case 3:
        
$labelj=$aaa[$ijk];
  $www=array( 'Column' =>$labelj,'Label' => '','Span' => 1,'Option' => array('size' => 1));
  $__otatest2_order_cfg['E_RANDOM_LAYOUT'][$kk]=$www;
//  $ijk++; 
        break;
case 4:
 $labelj=$ccc[$ijk];
  $www=array( 'Column' =>$labelj,'Label' => '','Span' => 1,'Option' => array('size' => 1));   

  
//$www=array( 'Insn' =>'//');
$__otatest2_order_cfg['E_RANDOM_LAYOUT'][$kk]=$www;
$ijk++; 
        break;
case 5:
        
$www=array( 'Insn' =>'//');
$__otatest2_order_cfg['E_RANDOM_LAYOUT'][$kk]=$www;
        break;

}

if ($albl[$ijk]=='finish'){$kk=1001;}


 

}
// edit def

//0912-2014
//definition


$ijk=0;
for ($kk=0;$kk<1000;$kk++)
{
//
switch ($kk % 6) {
    case 0:
        
$labeli=$albl[$ijk];
//
if (substr($labeli,0,2)=='kk'|| substr($labeli,0,2)=='ss'){
$www=array( 'Column' =>$labeli,'Option' => array('size' =>3));
	}
else {
 $www=array( 'Label' =>$labeli);
	}

$__otatest2_order_cfg['D_RANDOM_LAYOUT'][$kk]=$www;

        break;
//

    case 1:
//

//         
$labelj=$aclm[$ijk];
$www=array( 'Column' =>$labelj,'Option' => array('size' => 5));
//print $www;
$__otatest2_order_cfg['D_RANDOM_LAYOUT'][$kk]=$www;
//$ijk++; 
        break;
    case 2:         
  $labelj=$pclm[$ijk];
  $www=array( 'Column' =>$labelj, 'Span' => 1);
  $__otatest2_order_cfg['D_RANDOM_LAYOUT'][$kk]=$www;
//  $ijk++; 
        break;
case 3:
        
$labelj=$aaa[$ijk];
  $www=array( 'Column' =>$labelj,'Label' => '','Span' => 1);
  $__otatest2_order_cfg['D_RANDOM_LAYOUT'][$kk]=$www;
//  $ijk++; 
        break;
case 4:
        
$labelj=$ccc[$ijk];
  $www=array( 'Column' =>$labelj,'Label' => '','Span' => 1,'Option' => array('size' => 1));   

  
//$www=array( 'Insn' =>'//');
$__otatest2_order_cfg['D_RANDOM_LAYOUT'][$kk]=$www;
$ijk++; 
        break;
case 5:
        
$www=array( 'Insn' =>'//');
$__otatest2_order_cfg['D_RANDOM_LAYOUT'][$kk]=$www;
        break;

}




 

}

 
*/




//print_r($__otatest2_order_cfg['D_RANDOM_LAYOUT']);
//print "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
/*
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//display layout 0315-2013 DDDDDDDDDDDDDDDDDD
//*************************************************************
//DISPLAY
$__otatest2_order_cfg['D_RANDOM_LAYOUT'] = array(
array('Label' => '¸¡¿ÇÆü'),
 
 array('Column' => 'dos','Span' => 1),
	array('Label' => ''),
array('Insn' => '//'),
array('Insn' => '//'),

	array('Label' => 'CMBÊó¹ðÆü'),
 
 array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
array('Insn' => '//'),
array('Insn' => '//'),
array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
	

 array('Insn' => '//'),
 array('Insn' => '//'),
array('Column' => 'category',
	      'Label' => 'Report Type',
	       
	      ),	
array('Column' => 'ss11',
	      'Label' => 'Completed',
	       
	      ),	


array('Insn' => '//'),
array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '¿Ç»¡½ê¸«'),
	array('Column' => 'cc400', 'Option' => array('size' => 5)),	
 
array('Insn' => '//'),
array('Label' =>  '****'),
	array('Insn' => '//'),
array('Label' =>  '¿Ç»¡½ê¸«È½Äê'),
	array('Column' => 'k400', 'Span' => 2),
array('Insn' => '//'),	
 
array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' =>  '¿ÈÄ¹'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => 'ÂÎ½Å'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101'),
array('Column' => 'aa52','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'ÈîËþÅÙ'),
	array('Column' => 'k1006','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1006'),
array('Column' => 'aa1006','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'BMI'),
	array('Column' => 'k1007','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1007'),
array('Column' => 'aa1007','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'Ê¢°Ï'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'ÂÎ»éËÃÎ¨'),
	array('Column' => 'k1005','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1005'),
array('Column' => 'aa1005','Label' => '','Span' => 1),
array('Insn' => '//'),
array('Label' =>  '¿ÈÂÎ·×Â¬'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),
	
	
	array('Insn' => '//'),
	array('Label' =>  '·ì°µ¡Ê¾å¡Ë'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '·ì°µ¡Ê²¼¡Ë'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '¿´Çï¿ô'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  '¶»Éô(¿´Â¡)XÀþ'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  '°ÂÀÅ»þ¿´ÅÅ¿Þ'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ÂçÆ°Ì®Ä¶²»ÇÈ'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => ''),
	array('Column' => 'c503','Span' => 3),
//0510-2013
array('Insn' => '//'),
	array('Label' =>  '·ÛÆ°Ì®Ä¶²»ÇÈ'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),
	
	 
//0320
	
array('Insn' => '//'),
 
	array('Label' =>  '½Û´Ä´ï·ÏÈ½Äê'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc403','Span' => 5),
array('Insn' => '//'),
	
	array('Label' =>  'ÇÙµ¡Ç½¸¡ºº'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => ''),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  'ÇÙ³èÎÌ'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Insn' => '//'),
 //
 
	
	array('Label' =>  'Í½ÁÛÇÙ³èÎÌ'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p103', 'Span' => 1),
//	array('Column' => 'aa103','Label' => '','Span' => 1),
	array('Insn' => '//'),
 array('Label' =>  '%ÇÙ³èÎÌ'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
//	array('Column' => 'p104', 'Span' => 1),
//	array('Column' => 'aa104','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '°ìÉÃÎÌ'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
	
	array('Label' =>  '°ìÉÃÎ¨'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 

//??

	 
	array('Insn' => '//'),
 

 


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  '¶»ÉôXÀþ'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
	
	 

//0320
array('Insn' => '//'),
array('Label' =>  '¸ÆµÛ´ï·ÏÈ½Äê'),
	array('Column' => 'k402', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
 

//
//1111-2013
array('Label' =>  '¶ºÀµ¡Ê±¦¡Ë'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  '¶ºÀµ¡Êº¸¡Ë'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  'Íç´ã¡Ê±¦¡Ë'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  'Íç´ã¡Êº¸¡Ë'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '´ãÄì'),
	array('Column' => 'h1', 'Span' => 1),
array('Insn' => '//'),

array('Insn' => '//'),
array('Label' =>  '´ã°µ'),
	array('Column' => 'h2', 'Span' => 1),
array('Insn' => '//'),

array('Label' =>  '´ã²ÊÈ½Äê'),
	array('Column' => 'k414', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

array('Insn' => '//'),

//array('Insn' => '  ', 'Span' => 1),

//1111-2013
	array('Insn' => '//'),
	 
array('Label' =>  '500Hz¡Ê±¦¡Ë'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz¡Êº¸¡Ë'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz¡Êº¸¡Ë'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz¡Êº¸¡Ë'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz¡Êº¸¡Ë'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '8000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
array('Column' => 'aa90','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '8000Hz¡Êº¸¡Ë'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
array('Column' => 'aa91','Label' => '','Span' => 1), 
	
//
//1111-2013


array('Insn' => '//'),
array('Label' =>  'Ä°ÎÏÈ½Äê'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),

	array('Label' =>  '·ì±Õ·¿ABO¼°'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  'Çò·ìµå¿ô'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  'ÀÖ·ìµå¿ô'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => '·ì¿§ÁÇÎÌ'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => '¥Ø¥Þ¥È¥¯¥ê¥Ã¥È'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh¼°'),
	array('Column' => 'kk75','Span' => 1),
	
	array('Insn' => '//'),
	
//
	array('Label' =>  'MCV'),
	array('Column' => 'kk76', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp76'),
	 array('Column' => 'aa42','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc76','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' =>  'MCH'),
	array('Column' => 'kk77',
	      
	       'Span' =>1),
	array('Label' => ''),
	array('Column' => 'pp77'),
	array('Column' => 'aa43','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc77','Span' => 3),
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'MCHC'),
	array('Column' => 'kk78',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp78'),
	array('Column' => 'aa44','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc78','Span' => 3),
	
	 
	array('Insn' => '//'),
//
	
	array('Label' => '·ì¾®ÈÄ¿ô'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
	 
	array('Insn' => '//'), 
array('Label' =>  '·ì±Õ°ìÈÌÈ½Äê'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
 

array('Label' =>  '¶õÊ¢»þ·ìÅü'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¶õÊ¢»þÇ¢Åü'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
	array('Column' => 'aa21','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc51','Span' => 3),
	
	array('Insn' => '//'),



array('Label' =>  'HbA1C'),
	array('Column' => 'kk52', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp52'),
	array('Column' => 'aa22','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc52','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  'ÅüÂå¼ÕÈ½Äê'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),

 
 

//1020-2012
array('Label' =>  'Áí¥³¥ì¥¹¥Æ¥í¡¼¥ë'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ÃæÀ­»éËÃ'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'HDL(Á±¶Ì) ŽºŽÚŽ½ŽÃŽÛŽ°ŽÙ'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL (°­¶Ì) ŽºŽÚŽ½ŽÃŽÛŽ°ŽÙ'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL(°­¶Ì) ŽºŽÚŽ½ŽÃŽÛŽ°ŽÙ'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
array('Label' =>  'CHOL/HDL Èæ'),
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),
	array('Column' => 'aa1003','Label' => '','Span' => 1),
//0510-2013
	
	array('Insn' => '//'),

array('Label' =>  '»é¼ÁÂå¼ÕÈ½Äê'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),

array('Label' =>  '¥¢¥ß¥é¡¼¥¼'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
	array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	array('Label' => ''),
	array('Column' => 'c519','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  'ç¹Â¡´ï·ÏÈ½Äê'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
 
array('Label' =>  'ÁíÃÁÇò'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Ž±ŽÙŽÌŽÞŽÐŽÝ'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/GÈæ'),
	array('Column' => 'kk32', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp32'),
	array('Column' => 'aa10','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc32','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'GOT'),
	array('Column' => 'kk33', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp33'),
	array('Column' => 'aa11','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc33','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'GPT'),
	array('Column' => 'kk34', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp34'),
	array('Column' => 'aa12','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc34','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¦Ã-GTP'),
	array('Column' => 'kk35', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp35'),
	array('Column' => 'aa13','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc35','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDH'),
	array('Column' => 'kk36', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp36'),
	array('Column' => 'aa14','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc36','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'ÁíŽËŽÞŽØŽÙŽËŽÞŽÝ'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc38','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '£È£Â£ó¹³¸¶'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '£È£Â£ó¹³ÂÎ'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'C·¿´Î±ê¹³ÂÎ'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => ''),
	array('Column' => 'c520','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  '´ÎÃÀ¤Î¤¦È½Äê'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
 

array('Label' =>  'Ç¢°ìÈÌ¡¡ÃÁÇò'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Åü'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
	array('Column' => 'aa21','Label' => '','Span' => 1),
// 	array('Label' => ''),
 //	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  '¥±¥È¥óÂÎ'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Àø·ì'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
array('Label' =>  '(Ç¢ÄÀÞÖ¡ËÀÖ·ìµå'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(Ç¢¡ËÇò·ìµå'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(Ç¢¡ËºÙ¶Ý'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Na'),
	array('Column' => 'kk53', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp53'),
	array('Column' => 'aa27','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'K'),
	array('Column' => 'kk54', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp54'),
	array('Column' => 'aa28','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc54','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Cl'),
	array('Column' => 'kk55', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp55'),
	array('Column' => 'aa29','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc55','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CO2'),
	array('Column' => 'kk56', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp56'),
	array('Column' => 'aa30','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc56','Span' => 3),
	
	array('Insn' => '//'),

 
array('Label' =>  'Ç¢ÁÇÃâÁÇ'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¥¯¥ì¥¢¥Á¥Ë¥ó'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 3),
	
	array('Insn' => '//'),
 array('Label' =>  '¿Õµ¡Ç½È½Äê'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

 
array('Label' =>  'Ç¢»À¡ÊÄËÉ÷¡Ë'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

array('Label' =>  'ÄËÉ÷È½Äê'),
	array('Column' => 'k417', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
array('Insn' => '//'),

//aaaaaaaaaaaaaaaaaaaaaaaaaaa
array('Label' =>  '¹Ã¾õÁ£¿¨¿Ç'),
	array('Column' => 'k511', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p511'),
	
	array('Label' => ''),
	array('Column' => 'c511','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
	array('Column' => 'aa46','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),
 
	array('Insn' => '//'),

array('Insn' => '//'),
 
array('Label' =>  '¹Ã¾õÁ£¸¡ºº'),
	array('Column' => 'k411', 'Span' => 2),
array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
	
array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),
//aaaaaaaaaaaaaaaaaaaaaaaa 
	 
array('Label' =>  'ÊØÀø·ì¡ÊÌÈ±ÖË¡)'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),
array('Label' =>  '°ß£ØÀþÆ©»ë¸¡ºº'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  '¥Ô¥í¥ê¶Ý¸Æµ¤¸¡ºº'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => ''),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¾åÉô¾Ã²½´ïÆâ»ë¶À¸¡ºº'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '²¼Éô¾Ã²½´ïÆâ»ë¶À¸¡ºº'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '¥«¥×¥»¥ë¾®Ä²Æâ»ë¶À¸¡ºº'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '¾Ã²½´ï·ÏÈ½Äê'),
	array('Column' => 'k415', 'Span' => 1),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc415','Span' =>5),
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
 //bbbbbbbbbbbbbbbbbbbbbbbbb
array('Label' =>  'ÆýË¼¿¨¿Ç½ê¸«'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ŽÏŽÝŽÓŽ¸ŽÞŽ×ŽÌŽ¨Ž°'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),

array('Insn' => '//'),
 
array('Label' =>  'ÉØ¿Í²Ê¸¡ºº¡ÊÆýË¼¡Ë'),
	array('Column' => 'k412', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc412','Span' => 5),
	array('Insn' => '//'),
  
 
	

array('Label' =>  '¿Ç»¡½ê¸«'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => ''),
	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
	array('Column' => 'aa48','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
array('Label' =>  '»ÒµÜºÙË¦¿Ç:·ÛÉô'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => ''),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),


array('Insn' => '//'),
 
array('Label' =>  'ÉØ¿Í²Ê¸¡ºº¡Ê»ÒµÜ¡¢ÍñÁã¡Ë'),
	array('Column' => 'k413', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc413','Span' => 5),
array('Insn' => '//'),	
 
 	
array('Label' =>  '¿¨¿Ç'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => ''),
	array('Column' => 'c517','Span' => 3),
	
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Label' =>  'Ä¾Ä²¿Ç'),
array('Column' => 'k418', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
array('Column' => 'cc418','Span' => 5),
array('Insn' => '//'),
 
 		
 
array('Label' =>  '¿¨¿Ç'),
	array('Column' => 'k518','Span' => 1),
	array('Label' => ''),
	array('Column' => 'p518'),
	array('Label' => ''),
	array('Column' => 'c518','Span' => 3),
	array('Insn' => '//'),
	array('Label' =>  'PSA'),
	array('Column' => 'kk95','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
array('Label' =>  'Á°Î©Á£¸¡ºº'),
array('Column' => 'k416','Span' => 2),
array('Insn' => '//'),
 
	array('Label' => ''),
	array('Column' => 'cc416','Span' => 5),	
	array('Insn' => '//'),
//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbb


	
	array('Insn' => '//'),
//yobi etc
//0820-2014
array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	array('Label' => ''),
	array('Column' => 'aa2001'),
	array('Label' => ''),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	array('Label' => ''),
	array('Column' => 'aa2002'),
	array('Label' => ''),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	array('Label' => ''),
	array('Column' => 'aa2003'),
	array('Label' => ''),
	array('Column' => 'cc532','Span' => 3),
//0816-2014
 array('Insn' => '//'),	
array('Label' =>  ''),
	array('Column' => 'ss20', 'Span' => 1),
	array('Column' => 'ss40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'ss50'),
array('Label' => ''),
	array('Column' => 'aa2004'),
	array('Label' => ''),
	array('Column' => 'ss53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'ss21', 'Span' => 1),
	array('Column' => 'ss41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'ss51'),
	array('Label' => ''),
	array('Column' => 'aa2005'),
	array('Label' => ''),
	array('Column' => 'ss54','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'ss22', 'Span' => 1),
	array('Column' => 'ss42', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'ss52'),
	array('Label' => ''),
	array('Column' => 'aa2006'),
	array('Label' => ''),
	array('Column' => 'ss55','Span' => 3),
		

//0820-2014

array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => 'Áí¹ç·ë²Ì.»Ø¼¨»ö¹à'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => 'È÷¹Í'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),

);


*/

//EDIT START
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE 1115-2013 modefied
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
/*
$__otatest2_order_cfg['E_RANDOM_LAYOUT'] = array(
array('Label' => '¸¡¿ÇÆü'),
 
 array('Column' => 'dos','Span' => 1),
	array('Label' => ''),
array('Insn' => '//'),
array('Insn' => '//'),

	array('Label' => 'CMBÊó¹ðÆü'),
 
 array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
array('Insn' => '//'),
array('Insn' => '//'),
array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
	

 array('Insn' => '//'),
 array('Insn' => '//'),
array('Column' => 'category',
	      'Label' => 'Report Type',
	       
	      ),	
array('Column' => 'ss11',
	      'Label' => 'Completed',
	       
	      ),	


array('Insn' => '//'),

array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '¿Ç»¡½ê¸«'),
	array('Column' => 'cc400', 'Option' => array('size' => 5)),	
 
array('Insn' => '//'),
array('Label' =>  '****'),
array('Insn' => '//'),
array('Label' =>  '¿Ç»¡½ê¸«È½Äê'),
	array('Column' => 'k400', 'Span' => 2),
array('Insn' => '//'),	
 
array('Insn' => '//'),
array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '¿ÈÄ¹'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => 'ÂÎ½Å'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101'),
array('Column' => 'aa52','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'ÈîËþÅÙ'),
	array('Column' => 'k1006','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1006'),
array('Column' => 'aa1006','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'BMI'),
	array('Column' => 'k1007','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1007'),
array('Column' => 'aa1007','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'Ê¢°Ï'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'ÂÎ»éËÃÎ¨'),
	array('Column' => 'k1005','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1005'),
array('Column' => 'aa1005','Label' => '','Span' => 1),
array('Insn' => '//'),
array('Label' =>  '¿ÈÂÎ·×Â¬'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),
	
	
	array('Insn' => '//'),
	array('Label' =>  '·ì°µ¡Ê¾å¡Ë'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '·ì°µ¡Ê²¼¡Ë'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '¿´Çï¿ô'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  '¶»Éô(¿´Â¡)XÀþ'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  '°ÂÀÅ»þ¿´ÅÅ¿Þ'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'ÂçÆ°Ì®Ä¶²»ÇÈ'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => ''),
	array('Column' => 'c503','Span' => 3),
//0510-2013
array('Insn' => '//'),
	array('Label' =>  '·ÛÆ°Ì®Ä¶²»ÇÈ'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),
	
	 
//0320
	
array('Insn' => '//'),
 
	array('Label' =>  '½Û´Ä´ï·ÏÈ½Äê'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc403','Span' => 5),
array('Insn' => '//'),
	
	array('Label' =>  'ÇÙµ¡Ç½¸¡ºº'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => ''),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  'ÇÙ³èÎÌ'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Insn' => '//'),
 //
 
	
	array('Label' =>  'Í½ÁÛÇÙ³èÎÌ'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p103', 'Span' => 1),
//	array('Column' => 'aa103','Label' => '','Span' => 1),
	array('Insn' => '//'),
 array('Label' =>  '%ÇÙ³èÎÌ'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
//	array('Column' => 'p104', 'Span' => 1),
//	array('Column' => 'aa104','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '°ìÉÃÎÌ'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
	
	array('Label' =>  '°ìÉÃÎ¨'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 

//??

	 
	array('Insn' => '//'),
 

 


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  '¶»ÉôXÀþ'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
	
	 

//0320
array('Insn' => '//'),
array('Label' =>  '¸ÆµÛ´ï·ÏÈ½Äê'),
	array('Column' => 'k402', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
 

//
//1111-2013
array('Label' =>  '¶ºÀµ¡Ê±¦¡Ë'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  '¶ºÀµ¡Êº¸¡Ë'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  'Íç´ã¡Ê±¦¡Ë'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  'Íç´ã¡Êº¸¡Ë'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),

array('Insn' => '//'),
array('Label' =>  '´ãÄì'),
	array('Column' => 'h1', 'Span' => 1),
array('Insn' => '//'),

array('Insn' => '//'),
array('Label' =>  '´ã°µ'),
	array('Column' => 'h2', 'Span' => 1),
array('Insn' => '//'),


array('Label' =>  '´ã²ÊÈ½Äê'),
	array('Column' => 'k414', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

array('Insn' => '//'),

//array('Insn' => '  ', 'Span' => 1),

//1111-2013
	array('Insn' => '//'),
	 
array('Label' =>  '500Hz¡Ê±¦¡Ë'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz¡Êº¸¡Ë'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz¡Êº¸¡Ë'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz¡Êº¸¡Ë'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz¡Êº¸¡Ë'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '8000Hz¡Ê±¦¡Ë'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
array('Column' => 'aa90','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '8000Hz¡Êº¸¡Ë'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
array('Column' => 'aa91','Label' => '','Span' => 1), 
	
//
//1111-2013


array('Insn' => '//'),
array('Label' =>  'Ä°ÎÏÈ½Äê'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),

	array('Label' =>  '·ì±Õ·¿ABO¼°'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  'Çò·ìµå¿ô'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  'ÀÖ·ìµå¿ô'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => '·ì¿§ÁÇÎÌ'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => '¥Ø¥Þ¥È¥¯¥ê¥Ã¥È'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh¼°'),
	array('Column' => 'kk75','Span' => 1),
	
	array('Insn' => '//'),
	
//
	array('Label' =>  'MCV'),
	array('Column' => 'kk76', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp76'),
	 array('Column' => 'aa42','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc76','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' =>  'MCH'),
	array('Column' => 'kk77',
	      
	       'Span' =>1),
	array('Label' => ''),
	array('Column' => 'pp77'),
	array('Column' => 'aa43','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc77','Span' => 3),
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'MCHC'),
	array('Column' => 'kk78',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp78'),
	array('Column' => 'aa44','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc78','Span' => 3),
	
	 
	array('Insn' => '//'),
//
	
	array('Label' => '·ì¾®ÈÄ¿ô'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
	 
	array('Insn' => '//'), 
array('Label' =>  '·ì±Õ°ìÈÌÈ½Äê'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
 

array('Label' =>  '¶õÊ¢»þ·ìÅü'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¶õÊ¢»þÇ¢Åü'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
	array('Column' => 'aa21','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc51','Span' => 3),
	
	array('Insn' => '//'),



array('Label' =>  'HbA1C'),
	array('Column' => 'kk52', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp52'),
	array('Column' => 'aa22','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc52','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  'ÅüÂå¼ÕÈ½Äê'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),

 
 

//1020-2012
array('Label' =>  'Áí¥³¥ì¥¹¥Æ¥í¡¼¥ë'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ÃæÀ­»éËÃ'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'HDL(Á±¶Ì) ŽºŽÚŽ½ŽÃŽÛŽ°ŽÙ'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL (°­¶Ì) ŽºŽÚŽ½ŽÃŽÛŽ°ŽÙ'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL(°­¶Ì) ŽºŽÚŽ½ŽÃŽÛŽ°ŽÙ'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
array('Label' =>  'CHOL/HDL Èæ'),
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),
	array('Column' => 'aa1003','Label' => '','Span' => 1),
//0510-2013
	
	array('Insn' => '//'),

array('Label' =>  '»é¼ÁÂå¼ÕÈ½Äê'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),

array('Label' =>  '¥¢¥ß¥é¡¼¥¼'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
	array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	array('Label' => ''),
	array('Column' => 'c519','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  'ç¹Â¡´ï·ÏÈ½Äê'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
 
array('Label' =>  'ÁíÃÁÇò'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Ž±ŽÙŽÌŽÞŽÐŽÝ'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/GÈæ'),
	array('Column' => 'kk32', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp32'),
	array('Column' => 'aa10','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc32','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'GOT'),
	array('Column' => 'kk33', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp33'),
	array('Column' => 'aa11','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc33','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'GPT'),
	array('Column' => 'kk34', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp34'),
	array('Column' => 'aa12','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc34','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¦Ã-GTP'),
	array('Column' => 'kk35', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp35'),
	array('Column' => 'aa13','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc35','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDH'),
	array('Column' => 'kk36', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp36'),
	array('Column' => 'aa14','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc36','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'ÁíŽËŽÞŽØŽÙŽËŽÞŽÝ'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc38','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '£È£Â£ó¹³¸¶'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '£È£Â£ó¹³ÂÎ'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'C·¿´Î±ê¹³ÂÎ'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => ''),
	array('Column' => 'c520','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  '´ÎÃÀ¤Î¤¦È½Äê'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
 

array('Label' =>  'Ç¢°ìÈÌ¡¡ÃÁÇò'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
//0910-2014
array('Label' =>  'Åü'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
// 	array('Label' => ''),
// 	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  '¥±¥È¥óÂÎ'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Àø·ì'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
array('Label' =>  '(Ç¢ÄÀÞÖ¡ËÀÖ·ìµå'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(Ç¢¡ËÇò·ìµå'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(Ç¢¡ËºÙ¶Ý'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Na'),
	array('Column' => 'kk53', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp53'),
	array('Column' => 'aa27','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'K'),
	array('Column' => 'kk54', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp54'),
	array('Column' => 'aa28','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc54','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Cl'),
	array('Column' => 'kk55', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp55'),
	array('Column' => 'aa29','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc55','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CO2'),
	array('Column' => 'kk56', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp56'),
	array('Column' => 'aa30','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc56','Span' => 3),
	
	array('Insn' => '//'),

 
array('Label' =>  'Ç¢ÁÇÃâÁÇ'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¥¯¥ì¥¢¥Á¥Ë¥ó'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 3),
	
	array('Insn' => '//'),
 array('Label' =>  '¿Õµ¡Ç½È½Äê'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

 
array('Label' =>  'Ç¢»À¡ÊÄËÉ÷¡Ë'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

array('Label' =>  'ÄËÉ÷È½Äê'),
	array('Column' => 'k417', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
array('Insn' => '//'),

 
	
array('Label' =>  '¹Ã¾õÁ£¿¨¿Ç'),
	array('Column' => 'k511', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p511'),
	
	array('Label' => ''),
	array('Column' => 'c511','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
	array('Column' => 'aa46','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),
 
	array('Insn' => '//'),

array('Insn' => '//'),
 
array('Label' =>  '¹Ã¾õÁ£¸¡ºº'),
	array('Column' => 'k411', 'Span' => 2),
array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
	
array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),

array('Label' =>  'ÊØÀø·ì¡ÊÌÈ±ÖË¡)'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),
array('Label' =>  '°ß£ØÀþÆ©»ë¸¡ºº'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  '¥Ô¥í¥ê¶Ý¸Æµ¤¸¡ºº'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	array('Column' => 'cc50','Label' => '','Span' => 1), //Ab
	array('Label' => ''),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¾åÉô¾Ã²½´ïÆâ»ë¶À¸¡ºº'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '²¼Éô¾Ã²½´ïÆâ»ë¶À¸¡ºº'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '¥«¥×¥»¥ë¾®Ä²Æâ»ë¶À¸¡ºº'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '¾Ã²½´ï·ÏÈ½Äê'),
	array('Column' => 'k415', 'Span' => 1),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc415','Span' =>5),
	
	array('Insn' => '//'),
 
	
 
array('Label' =>  'ÆýË¼¿¨¿Ç½ê¸«'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ŽÏŽÝŽÓŽ¸ŽÞŽ×ŽÌŽ¨Ž°'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Ä¶²»ÇÈ¸¡ºº'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),

array('Insn' => '//'),
 
array('Label' =>  'ÉØ¿Í²Ê¸¡ºº¡ÊÆýË¼¡Ë'),
	array('Column' => 'k412', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc412','Span' => 5),
	array('Insn' => '//'),
  
 
	

array('Label' =>  '¿Ç»¡½ê¸«'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => ''),
	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
	array('Column' => 'aa48','Label' => '','Span' => 1),
 
	array('Insn' => '//'),
array('Label' =>  '»ÒµÜºÙË¦¿Ç:·ÛÉô'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => ''),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),


array('Insn' => '//'),
 
array('Label' =>  'ÉØ¿Í²Ê¸¡ºº¡Ê»ÒµÜ¡¢ÍñÁã¡Ë'),
	array('Column' => 'k413', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc413','Span' => 5),
array('Insn' => '//'),	
 
 	
array('Label' =>  '¿¨¿Ç'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => ''),
	array('Column' => 'c517','Span' => 3),
	
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Label' =>  'Ä¾Ä²¿Ç'),
array('Column' => 'k418', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
array('Column' => 'cc418','Span' => 5),
array('Insn' => '//'),
 
 		
 
array('Label' =>  '¿¨¿Ç'),
	array('Column' => 'k518','Span' => 1),
	array('Label' => ''),
	array('Column' => 'p518'),
	array('Label' => ''),
	array('Column' => 'c518','Span' => 3),
	array('Insn' => '//'),
	array('Label' =>  'PSA'),
	array('Column' => 'kk95','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
array('Label' =>  'Á°Î©Á£¸¡ºº'),
array('Column' => 'k416','Span' => 2),
array('Insn' => '//'),
 
	array('Label' => ''),
	array('Column' => 'cc416','Span' => 5),	
	array('Insn' => '//'),
 
 
 
//yobi etc add 6 items

//0820-2014
array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	array('Label' => ''),
	array('Column' => 'aa2001'),
	array('Label' => ''),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	array('Label' => ''),
	array('Column' => 'aa2002'),
	array('Label' => ''),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	array('Label' => ''),
	array('Column' => 'aa2003'),
	array('Label' => ''),
	array('Column' => 'cc532','Span' => 3),
//0816-2014
 array('Insn' => '//'),	
array('Label' =>  ''),
	array('Column' => 'ss20', 'Span' => 1),
	array('Column' => 'ss40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'ss50'),
array('Label' => ''),
	array('Column' => 'aa2004'),
	array('Label' => ''),
	array('Column' => 'ss53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'ss21', 'Span' => 1),
	array('Column' => 'ss41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'ss51'),
	array('Label' => ''),
	array('Column' => 'aa2005'),
	array('Label' => ''),
	array('Column' => 'ss54','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'ss22', 'Span' => 1),
	array('Column' => 'ss42', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'ss52'),
	array('Label' => ''),
	array('Column' => 'aa2006'),
	array('Label' => ''),
	array('Column' => 'ss55','Span' => 3),
		

//0820-2014
	
 


array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => 'Áí¹ç·ë²Ì.»Ø¼¨»ö¹à'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => 'È÷¹Í'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),




);

*/
//
 
 /*
$stream1=fopen("/home/medex/labelwork7","rb");
 
$info=array();
 
//$__otatest2_all_cols=array();

while ($info =fgetcsv($stream1,1024,"\n")){
 $cc=$info[0]; 
//print_r($info);
//print "<br>";
$findme="'Column' =>'";
$pos = mb_strpos($cc, $findme);
$top1=mb_substr($cc,$pos+12,20);
 
 
 	$pos2 = mb_strpos($top1,","); 
	 
	$text=mb_substr($top1,1,$pos2-2);
 
 
$__otatest2_all_cols1[] = $info[0];
		 
 

}
print_r($__otatest2_all_cols1);

*/


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// print "*******************************************";
foreach ($__otatest2_all_cols as $v) {
	$o = mx_check_option('Option', $v);
	$c = $v['Column'];

	if (!mx_check_option('nostore', $o)) {
		$__otatest2_order_cfg['COLS'][] = $c;
		$__otatest2_order_cfg['ICOLS'][] = $c;
	}
	if (mx_check_option('list', $o))
		$__otatest2_order_cfg['LCOLS'][] = $v;
	if (!mx_check_option('nodisp', $o))
		$__otatest2_order_cfg['DCOLS'][] = $v;
	if (!mx_check_option('noedit', $o))
		$__otatest2_order_cfg['ECOLS'][] = $v;
}
 

//
function __lib_u_doctor_otatest2_order_anno(&$data)
{
//1111-2013
/*
if ($data['order_date']){
	$data['order_date']=sprintf("%02d/%02d/%04d",
		
		 substr($data['order_date'],5,2),
		substr($data['order_date'],8,2),
		substr($data['order_date'],0,4));
}
if ($data['preorderdate']){
	$data['preorderdate']=sprintf("%02d/%02d/%04d",
		
		 substr($data['preorderdate'],5,2),
		substr($data['preorderdate'],8,2),
		substr($data['preorderdate'],0,4));
}
*/


//1221-2013 ketsuatsu
if ($data['k300']>135 ) {
 $data['aa56'] = '1';
}
if ($data['k301']>85) {
 $data['aa57'] = '1';
}
//1221-2013
if ($data['k101']) {
 $data['k1007'] = sprintf("%.2f",$data['k101']/($data['k100']/100*$data['k100']/100));
$riso=$data['k100']/100*$data['k100']/100*22;
$himando=($data['k101'] - $riso);
//print $himando;
$data['k1006'] = sprintf("%.2f",$himando/$riso*100);
}
//haikatsu
 	if ($data['k200']) {
 //ibyoritsu
 		$data['k106'] = sprintf("%.2f",$data['k203']/$data['k200']*100);
		$pat = get_patient($data['patient'],false);
		if ($pat['À­ÊÌ'] == 'M'){
 
 //yosouhaikatu
		$data['k103']= sprintf("%.2f",(27.63-0.112*mx_calc_age($pat["À¸Ç¯·îÆü"]))*$data['k100']);
 


		}
		else {
 
		// print "DOB=".$pat['À¸Ç¯·îÆü'];
		// print "age=".mx_calc_age($pat['À¸Ç¯·îÆü']);
		$data['k103']= sprintf("%.2f",(21.78-0.101*mx_calc_age($pat["À¸Ç¯·îÆü"]))*$data['k100']);
		}
// if non 0
		$data['k104'] = sprintf("%.2f",$data['k200']/$data['k103']*100);
 		}
}
 
class list_of_otatest2_orders extends list_of_ppa_objects {

	var $debug = 1;
	var $patient_column_name_quoted = 'patient';

	function list_of_otatest2_orders($prefix, $cfg=NULL) {
		global $__otatest2_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__otatest2_order_cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_doctor_otatest2_order_anno(&$data);
		return list_of_ppa_objects::annotate_row_data(&$data);
	}
//0716-2014 added sort order

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'order_date' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}


}

class otatest2_order_display extends simple_object_display {

	var $debug = 1;

	function otatest2_order_display($prefix, $cfg=NULL) {
		global $__otatest2_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__otatest2_order_cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_doctor_otatest2_order_anno(&$data);
		return simple_object_display::annotate_row_data(&$data);
	}

//

//


 //for english go_pdfe
function print_sod() {
    go_pdf($this->id, 0);
  }

 


}

class otatest2_order_edit extends simple_object_ppa_edit {

	var $debug = 1;

	var $patient_column_name = 'patient';

	function edit_tweak() {
		$this->data['recorded_on'] = mx_today_string();
		__lib_u_doctor_otatest2_order_anno(&$this->data);
	}

	function anew_tweak($orig_id) {
		if (trim($this->data['order_date']) == '')
			$this->data['order_date'] = mx_today_string();
		$this->data['recorded_on'] = mx_today_string();
	}

	function annotate_form_data(&$data) {
//1030-2013
// 		if ($data['k200'])
// 			__lib_u_doctor_otatest2_order_anno(&$data);

		return simple_object_ppa_edit::annotate_form_data(&$data);
	}

	function otatest2_order_edit($prefix, $cfg=NULL) {
		global $__otatest2_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__otatest2_order_cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}

	function _validate($force=NULL) {
		$bad = simple_object_ppa_edit::_validate($force) != 'ok';
		$d =& $this->data;
		
		if ($bad)
			return '';
		return 'ok';
	}

}

//0412-2012
function otatest2_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	if (!is_null($time_from))
		$limit[] = 'X."order_date" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'X."order_date" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;
	else
		$num_limit = 0;
	$limit[] = 'X."Superseded" IS NULL';
	$q_oid = mx_db_sql_quote($p_oid);
	if (count($limit))
		$limit = "\nAND " . implode(' AND ', $limit);
	else
		$limit = '';
	if ($num_limit)
		$num_limit = ' LIMIT 30';
	else
		$num_limit = '';

	$sql = <<<SQL
SELECT "ObjectID"
FROM "otatest_order" AS X
WHERE X."patient" = $p_oid$limit

SQL;
	$all = pg_fetch_all(pg_query($dbh, $sql));
	$result = array();
	if ($all === false)
		return $result;

	$ix = 0;
	foreach ($all as $e) {
		$object = $e['ObjectID'];
		$config = array();
		$sod = new otatest2_order_display("sod-$ix-", &$config);
		$sod->reset($object);
		$result[] = $sod->module_info($p_pid);
	}
	return $result;
}


?>
