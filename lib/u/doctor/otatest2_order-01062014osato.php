<?php // -*- mode: php; coding: euc-japan -*-
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
//add 0320-2012

//for osato

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
//only for osato-clinic pdf print
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf44.php';


//1103-2013

function _lib_u_ota_kiroku2() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "姓" || "名" as empname , userid
    from "職員台帳"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}

 

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

$__otatest2_category_enum = array
(
	'新規','変更','中止'
);

$__otatest2_addition_enum = array
(
	'','特別','普通',
);




$__otatest2_exam_enum = array
(
	'N/A','異常なし','所見あり','再検査','不明',
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

$__otatest2_all_cols = array(
 
	array('Column' => 'patient',
	      'Draw' => NULL,
	      'Option' => array('noedit' => 1, 'nodisp' => 1),
	      ),
 
	array('Column' => 'order_date',
	      'Label' => '検査日',
	      'Draw' => 'date',
	      'Option' => array('list' => 1,'size'=>10),
	      ),
 
 
array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2(),
 'Option' => array(  'list' => 1,'size'=>10),

				       ),
	array('Column' => 'preorderdate',
	      'Label' => ' 日',
	      'Draw' => 'date',

	      'Option' => array('list' => 1,'size'=>10),
	      ),
	array('Column' => 'category',
	      'Label' => '区分',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_category_enum),
	      'Option' => array('size'=>10),
	      ),
	array('Column' => 'addition',
	      'Label' => '特別指示',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_addition_enum),
		'Option' => array('list' => 1,'size'=>10),
	      ),
	
//1220-2013
array('Column' => 'h1','Label' => '','Draw' => 'enum','Enum' => mk_enum($__otatest2_exam_enum),),
array('Column' => 'h2','Label' => '','Draw' => 'enum','Enum' =>mk_enum($__otatest2_exam_enum),),
 //1212-2013
array('Column' => 'k1006',
	       'Draw' => 'text', 
	      ),
	array('Column' => 'p1006',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
array('Column' => 'k1007',
	       'Draw' => 'static', 
	      ),
	array('Column' => 'p1007',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
array('Column' => 'aa1005',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa1006',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa1007',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),

//04=20-2012 

 	array('Column' => 'k80',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//04-23-2012
	array('Column' => 'p80',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	
	array('Column' => 'k81',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p81',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k82',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p82',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k83',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p83',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k84',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p84',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

	array('Column' => 'k85',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p85',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k86',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p86',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//
array('Column' => 'k87',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p87',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

	array('Column' => 'k88',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p88',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k89',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p89',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

array('Column' => 'k90',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p90',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

	array('Column' => 'k91',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p91',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	

	array('Column' => 'k100',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p100',
	       'Draw' => 'text','Option' =>array('size' => 10),
	      ),
	
	array('Column' => 'k101',
	       'Draw' => 'text','Option' =>array('size' => 10),
	      ),
	array('Column' => 'p101',
	       'Draw' => 'text','Option' =>array('size' => 10),
	      ),
	array('Column' => 'k102',
	       'Draw' => 'text','Option' =>array('size' => 10),
	      ),
	array('Column' => 'p102',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'k103',
	       'Draw' => 'static', 
	      ),
	array('Column' => 'p103',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'k104',
	       'Draw' => 'static',
	      ),
	array('Column' => 'p104',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),

	array('Column' => 'k105',
 	       'Draw' => 'text','Option' =>array('size' => 10), 

//'Draw' => 'static',

	      ),
	array('Column' => 'p105',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'k106',
	       'Draw' => 'static',  
	      ),
	array('Column' => 'p106',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
	array('Column' => 'k107',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p107',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
array('Column' => 'k1000',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p1000',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
array('Column' => 'k1001',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p1001',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
//0510-2013
array('Column' => 'k1002',
	       'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      ),
	array('Column' => 'p1002',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
array('Column' => 'c1002',
	       'Draw' => 'textarea',
		'Option' => array('vocab' => array('c1002'),
				'cols' =>20,'rows'=>3), 
	      ),
//0601-2013
array('Column' => 'k1003',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p1003',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
array('Column' => 'k1004',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p1004',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
 
	 array('Column' => 'k1005',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p1005',
	       'Draw' => 'text','Option' =>array('size' => 10), 
		),
 

//0510-2013
//
	array('Column' => 'k500',
	      'Label' => '胸部Ｘ線',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p500',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//05-08-2013
	array('Column' => 'c500',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c500'),
				'cols' =>20,'rows'=>3),
	      ),
	array('Column' => 'k501',
	      'Label' => '胸部(心臓)Ｘ線',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p501',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c501',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c501'),
				'cols' => 20,'rows'=>3),
	      ),
	array('Column' => 'k502',
	      'Label' => '2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p502',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c502',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c502'),
				'cols' => 20,'rows'=>3),
	      ),
//

array('Column' => 'k503',
	      'Label' => '3',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p503',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c503',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c503'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k504',
	      'Label' => '4',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p504',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//05-08-2013
array('Column' => 'c504',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c504'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k505',
	      'Label' => '5',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_plus_enum),
	      
	      ),
	array('Column' => 'p505',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c505',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c505'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k506',
	      'Label' => '6',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p506',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c506',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c506'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k507',
	      'Label' => '7',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p507',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c507',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c507'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k508',
	      'Label' => '8',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p508',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c508',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c508'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k509',
	      'Label' => '9',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p509',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c509',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c509'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k510',
	      'Label' => '10',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p510',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c510',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c510'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k511',
	      'Label' => '11',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p511',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c511',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c511'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k512',
	      'Label' => '12',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p512',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c512',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c512'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k513',
	      'Label' => '13',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p513',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c513',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c513'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k514',
	      'Label' => '14',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p514',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c514',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c514'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k515',
	      'Label' => '15',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p515',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c515',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c515'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k516',
	      'Label' => '16',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p516',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c516',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c516'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k517',
	      'Label' => '17',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p517',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c517',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c517'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k518',
	      'Label' => '18',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p518',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c518',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c518'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k519',
	      'Label' => 'chouonpa',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p519',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//
array('Column' => 'c519',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c519'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k520',
	      'Label' => '20',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_exam_enum),
	      
	      ),
	array('Column' => 'p520',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c520',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('c520'),
				'cols' => 20,'rows'=>3),
	      ),
// k500-k521 add Kk530--KK540  0315-2013
array('Column' => 'kk530',
	      'Label' => '20',
	      'Draw' => 'text','Option' => array('size' => 10),
	     
	      
	      ),
	array('Column' => 'pp530',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'cc530',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc530'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'kk540',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'kk531',
	      'Label' => '20',
	     'Draw' => 'text','Option' => array('size' => 10),
	     
	      
	      ),
	array('Column' => 'pp531',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'cc531',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc531'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'kk541',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//
array('Column' => 'kk532',
	      'Label' => '20',
	       'Draw' => 'text','Option' => array('size' => 10),
	     
	      
	      ),
	array('Column' => 'pp532',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 
array('Column' => 'cc532',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc532'),
				'cols' => 20,'rows'=>3),
	      ),

array('Column' => 'kk542',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 
//0315-2013	ETC	
//
	array('Column' => 'k200',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p200',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	
	array('Column' => 'k201',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p201',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k202',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p202',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k203',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p203',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k204',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p204',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

//
//

	array('Column' => 'k300',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p300',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	
	array('Column' => 'k301',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p301',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'k302',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p302',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

//04-24-2012


	array('Column' => 'kk10',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp10',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'ss10',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'cc10',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc10'),
				'cols' => 20),
	      ),
//
array('Column' => 'kk11',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp11',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc11',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc11'),
				'cols' => 20),),
	   array('Column' => 'kk12',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp12',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc12',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc12'),
				'cols' => 20),),
	   array('Column' => 'kk13',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp13',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc13',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc13'),
				'cols' => 20),),
	   array('Column' => 'kk14',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp14',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc14',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc14'),
				'cols' => 20),),
array('Column' => 'kk15',
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_plus_enum),
	      
	      ),

	array('Column' => 'pp15',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
 

	 array('Column' => 'cc15',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc15'),
				'cols' => 20),),
	   
//1020-2012
array('Column' => 'cc16',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc16'),
				'cols' => 20),),
array('Column' => 'cc17',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc17'),
				'cols' => 20),),
array('Column' => 'cc18',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc18'),
				'cols' => 20),),
array('Column' => 'cc19',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc19'),
				'cols' => 20),),



//
	array('Column' => 'kk21',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp21',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc21',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc21'),
				'cols' => 20),),
	   array('Column' => 'kk22',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp22',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc22',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc22'),
				'cols' => 20),),
	   array('Column' => 'kk20',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp20',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc20',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc20'),
				'cols' => 20),),
//
//
	array('Column' => 'kk30',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp30',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc30',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc30'),
				'cols' => 20),),
	   
	array('Column' => 'kk31',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp31',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc31',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc31'),
				'cols' => 20),),
	   array('Column' => 'kk32',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp32',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc32',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc32'),
				'cols' => 20),),
	   array('Column' => 'kk33',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp33',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc33',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc33'),
				'cols' => 20),),
	   array('Column' => 'kk34',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp34',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc34',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc34'),
				'cols' => 20),),
	   array('Column' => 'kk35',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp35',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc35',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc35'),
				'cols' => 20),),

	array('Column' => 'kk36',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp36',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc36',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc36'),
				'cols' => 20),),
	   array('Column' => 'kk37',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp37',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc37',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc37'),
				'cols' => 20),),
	   array('Column' => 'kk38',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp38',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc38',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc38'),
				'cols' => 20),),
//
 
array('Column' => 'kk39',
	      'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp39',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc39',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc39'),
				'cols' => 20),),
/*0315-2013
array('Column' => 'kk40',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
*/

array('Column' => 'kk40',
	      'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp40',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc40',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc40'),
				'cols' => 20),),
	   
	 array('Column' => 'kk41',
	      'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp41',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc41',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc41'),
				'cols' => 20),),
	   array('Column' => 'kk42',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp42',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc42',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc42'),
				'cols' => 20),),
//
//04-23-2012 kk50
	array('Column' => 'kk50',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp50',
	     'Draw' => 'text','Option' => array('size' => 10), 
	      ),
	 array('Column' => 'cc50',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc50'),
				'cols' => 20),),
//04-22-2012
	   
	array('Column' => 'kk51',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp51',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc51',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc51'),
				'cols' => 20),),
	   array('Column' => 'kk52',
	      'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp52',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc52',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc52'),
				'cols' => 20),),
	   array('Column' => 'kk53',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp53',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc53',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc53'),
				'cols' => 20),),
	   array('Column' => 'kk54',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp54',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc54',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc54'),
				'cols' => 20),),
	   array('Column' => 'kk55',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp55',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc55',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc55'),
				'cols' => 20),),

array('Column' => 'kk56',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp56',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc56',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc56'),
				'cols' => 20),),
	   array('Column' => 'kk57',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp57',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc57',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc57'),
				'cols' => 20),),
	   array('Column' => 'kk58',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp58',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc58',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc58'),
				'cols' => 20),),

array('Column' => 'kk59',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp59',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc59',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc59'),
				'cols' => 20),),


//
//
	array('Column' => 'kk60',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp60',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc60',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc60'),
				'cols' => 20),),
	   
	array('Column' => 'kk61',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp61',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc61',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc61'),
				'cols' => 20),),
	   array('Column' => 'kk62',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp62',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc62',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc62'),
				'cols' => 20),),
	   array('Column' => 'kk63',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp63',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc63',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc63'),
				'cols' => 20),),
	   array('Column' => 'kk64',
//'Draw' => 'enum',
//	      'Enum' => mk_enum($__otatest2_np_enum),
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp64',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc64',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc64'),
				'cols' => 20),),
	   array('Column' => 'kk65',
	       'Draw' => 'text','Option' => array('size' => 10),
//'Draw' => 'enum',
//	      'Enum' => mk_enum($__otatest2_np_enum),
	      ),
	array('Column' => 'pp65',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc65',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc65'),
				'cols' => 20),),
//0531-2013

array('Column' => 'kk66',
	       'Draw' => 'text','Option' => array('size' => 10),
//'Draw' => 'enum',
//	      'Enum' => mk_enum($__otatest2_np_enum),
	      ),
	array('Column' => 'pp66',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc66',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc66'),
				'cols' => 20),),
	   array('Column' => 'kk67',
	       'Draw' => 'text','Option' => array('size' => 10),
//'Draw' => 'enum',
//	      'Enum' => mk_enum($__otatest2_np_enum),
	      ),
	array('Column' => 'pp67',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc67',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc67'),
				'cols' => 20),),
	  

//04-22-2012
array('Column' => 'kk70',
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_abo_enum),
	      
	      ),
	array('Column' => 'pp70',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc70',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc70'),
				'cols' => 20),),
	   
	array('Column' => 'kk71',
	      'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp71',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc71',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc71'),
				'cols' => 20),
	      ),
//
array('Column' => 'kk72',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp72',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	     
	array('Column' => 'cc72',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc72'),
				'cols' => 20),
	      ),
array('Column' => 'kk73',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp73',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc73',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc73'),
				'cols' => 20),
	      ),
//
	array('Column' => 'kk74',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp74',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	
	array('Column' => 'cc74',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc74'),
				'cols' => 20),
	      ),

	array('Column' => 'kk75',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_rh_enum),
	      ),
/*
	array('Column' => 'pp75',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc75',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('え食事箋コメント'),
				'cols' => 20),
	      ),
*/

array('Column' => 'kk76',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp76',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc76',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc76'),
				'cols' => 20),
	      ),
	array('Column' => 'kk77',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp77',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'cc77',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc77'),
				'cols' => 20),
	      ),
	
	array('Column' => 'kk78',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp78',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc78',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc78'),
				'cols' => 20),
	),

array('Column' => 'kk79',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp79',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc79',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc79'),
				'cols' => 20),
	      ),
//
//
	array('Column' => 'kk90',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp90',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc90',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc90'),
				'cols' => 20),),
	   
	array('Column' => 'kk91',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp91',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc91',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc91'),
				'cols' => 20),),
	   array('Column' => 'kk92',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp92',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc92',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc92'),
				'cols' => 20),),
	   array('Column' => 'kk93',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp93',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc93',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc93'),
				'cols' => 20),),
	   array('Column' => 'kk94',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp94',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc94',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc94'),
				'cols' => 20),),
	   array('Column' => 'kk95',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp95',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc95',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc95'),
				'cols' => 20),),



//	    
	array('Column' => 'special_req',
	      'Label' => '総合コメント',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('special'),
			'cols' => 60,'list' => 1,'rows'=>5),
	      ),
array('Column' => 'cc400',
	      'Label' => '診察所見',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('cc400'),
			'cols' => 60,'list' => 1,'rows'=>5),
	      ),
	   
	array('Column' => 'notes',
	      'Label' => '備考',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('notes'),
				'cols' =>60,'list' => 1,'rows'=>5),
	      ),
//04-20-2013 start
array('Column' => 'k400',
'Draw' =>'static',
//	      'Label' => 'hantei0',
//	      'Draw' => 'enum',
//	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
//array('Column' => 'cc400',
//	      'Draw' => 'textarea',
//		'Option' => array('vocab' => array('cc400'),
//				'cols' => 20),),
//'cols' => 60,'rows'=>5),),
array('Column' => 'k401',
	      'Label' => 'hantei1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),),
//0430-2013
array('Column' => 'cc401',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc401'),
				'cols' => 60,'rows'=>5),),
	      
array('Column' => 'k402',
	      'Label' => 'hantei2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc402',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc402'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k403',
	      'Label' => 'hantei3',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc403',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc403'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k404',
	      'Label' => 'hantei4',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
		),
array('Column' => 'cc404',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc404'),
				'cols' => 60,'rows'=>5),),
	      
	      
array('Column' => 'k405',
	      'Label' => 'hantei5',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc405',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc405'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k406',
	      'Label' => 'hantei6',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc406',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc406'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k407',
	      'Label' => 'hante7',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc407',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc407'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k408',
	      'Label' => 'hantei8',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc408',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc408'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k409',
	      'Label' => 'hantei9',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc409',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc409'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k410',
	      'Label' => 'hantei10',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc410',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc410'),
				'cols' => 60,'rows'=>5),),

array('Column' => 'k413',
	      'Label' => 'hantei13',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc413',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc413'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k414',
	      'Label' => 'hantei14',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc414',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc414'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k415',
	      'Label' => 'hantei15',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc415',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc415'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k411',
	      'Label' => 'hantei11',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc411',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc411'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k412',
	      'Label' => 'hante12',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc412',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc412'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k416',
	      'Label' => 'hante16',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc416',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc416'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k417',
	      'Label' => 'hante17',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc417',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc417'),
				'cols' => 60,'rows'=>5),),
array('Column' => 'k418',
	      'Label' => 'hante18',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_hantei_enum),
	      
	      ),
array('Column' => 'cc418',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('cc418'),
				'cols' => 60,'rows'=>5),),

//04-20-2013 end

//1020-2012 normal abnormal 
array('Column' => 'aa1',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa2',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa3',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa4',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa5',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa6',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa7',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa8',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa9',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa10',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa11',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa12',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa13',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa14',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa15',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa16',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa17',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa18',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa19',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa20',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa21',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa22',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa23',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa24',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa25',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa26',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa27',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa28',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa29',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa30',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa31',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa32',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa33',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa34',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa35',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa36',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa37',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa38',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa39',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa40',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa41',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa42',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa43',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa44',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa45',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa46',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa47',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa48',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa49',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa50',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
// add 0315-2013

 array('Column' => 'aa51',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa52',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa53',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa54',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa55',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa56',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa57',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 array('Column' => 'aa58',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
  array('Column' => 'aa59',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
//0510-2013
array('Column' => 'aa1003',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa94',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
//0510-2013
//1111-2013
array('Column' => 'aa80',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa81',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa82',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa83',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa84',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa85',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa86',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa87',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa88',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa89',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa90',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa91',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa1000',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
array('Column' => 'aa1001',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_n_enum,
	      
	      ),
 
//0510-2013
 
);

$__otatest2_order_cfg = array();

$__otatest2_order_cfg['TABLE'] = 'otatest_order';
$__otatest2_order_cfg['SEQUENCE'] = 'otatest_order_id_seq';
$__otatest2_order_cfg['COLS'] = array();
$__otatest2_order_cfg['ICOLS'] = array();
$__otatest2_order_cfg['ECOLS'] = array();
$__otatest2_order_cfg['LCOLS'] = array();
$__otatest2_order_cfg['DCOLS'] = array();
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//display layout 0315-2013
//*************************************************************
//DISPLAY
$__otatest2_order_cfg['D_RANDOM_LAYOUT'] = array(

	array('Label' => '検査日'),
 
 array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
array('Column' => 'shiji',
					'Label' => 'doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
array('Insn' => '//'),

array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '診察所見'),
	array('Column' => 'cc400', 'Option' => array('size' => 5)),	
 
array('Insn' => '//'),
array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '身長'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => '体重'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101'),
array('Column' => 'aa52','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '肥満度'),
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
	array('Label' => '腹囲'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '体脂肪率'),
	array('Column' => 'k1005','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1005'),
array('Column' => 'aa1005','Label' => '','Span' => 1),
array('Insn' => '//'),
array('Label' =>  '身体計測'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),
	
	
	array('Insn' => '//'),
	array('Label' =>  '血圧（上）'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '血圧（下）'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '心拍数'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  '胸部(心臓)X線'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  '安静時心電図'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '大動脈超音波'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => ''),
	array('Column' => 'c503','Span' => 3),
//0510-2013
array('Insn' => '//'),
	array('Label' =>  '頚動脈超音波'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),
	
	 
//0320
	
array('Insn' => '//'),
 
	array('Label' =>  '循環器系判定'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc403','Span' => 5),
array('Insn' => '//'),
	
	array('Label' =>  '肺機能検査'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => ''),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  '肺活量'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Insn' => '//'),
 //
 
	
	array('Label' =>  '予想肺活量'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p103', 'Span' => 1),
//	array('Column' => 'aa103','Label' => '','Span' => 1),
	array('Insn' => '//'),
 array('Label' =>  '%肺活量'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
//	array('Column' => 'p104', 'Span' => 1),
//	array('Column' => 'aa104','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '一秒量'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
	
	array('Label' =>  '一秒率'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 

//??

	 
	array('Insn' => '//'),
 

 


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  '胸部X線'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
	
	 

//0320
array('Insn' => '//'),
array('Label' =>  '呼吸器系判定'),
	array('Column' => 'k402', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
 

//
//1111-2013
array('Label' =>  '矯正（右）'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  '矯正（左）'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  '裸眼（右）'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '裸眼（左）'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '眼底'),
	array('Column' => 'h1', 'Span' => 1),
array('Insn' => '//'),

array('Insn' => '//'),
array('Label' =>  '眼圧'),
	array('Column' => 'h2', 'Span' => 1),
array('Insn' => '//'),

array('Label' =>  '眼科判定'),
	array('Column' => 'k414', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

array('Insn' => '//'),

//array('Insn' => '  ', 'Span' => 1),

//1111-2013
	array('Insn' => '//'),
	 
array('Label' =>  '500Hz（右）'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz（左）'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz（右）'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz（左）'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz（右）'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz（左）'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz（右）'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz（左）'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '8000Hz（右）'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
array('Column' => 'aa90','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '8000Hz（左）'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
array('Column' => 'aa91','Label' => '','Span' => 1), 
	
//
//1111-2013


array('Insn' => '//'),
array('Label' =>  '聴力判定'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),

	array('Label' =>  '血液型ABO式'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  '白血球数'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  '赤血球数'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => '血色素量'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => 'ヘマトクリット'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh式'),
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
	
	array('Label' => '血小板数'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
	 
	array('Insn' => '//'), 
array('Label' =>  '血液一般判定'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
 

array('Label' =>  '空腹時血糖'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '空腹時尿糖'),
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

array('Label' =>  '糖代謝判定'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),

 
 

//1020-2012
array('Label' =>  '総コレステロール'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '中性脂肪'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'HDL(善玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL (悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL(悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
array('Label' =>  'CHOL/HDL 比'),
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),
	array('Column' => 'aa1003','Label' => '','Span' => 1),
//0510-2013
	
	array('Insn' => '//'),

array('Label' =>  '脂質代謝判定'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),

array('Label' =>  'アミラーゼ'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
	array('Label' =>  '超音波検査'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	array('Label' => ''),
	array('Column' => 'c519','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  '膵臓器系判定'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
 
array('Label' =>  '総蛋白'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ｱﾙﾌﾞﾐﾝ'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G比'),
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
array('Label' =>  'γ-GTP'),
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
array('Label' =>  '総ﾋﾞﾘﾙﾋﾞﾝ'),
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
array('Label' =>  'ＨＢｓ抗原'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ＨＢｓ抗体'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'C型肝炎抗体'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => ''),
	array('Column' => 'c520','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  '肝胆のう判定'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
 

array('Label' =>  '尿一般　蛋白'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '糖'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'ケトン体'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '潜血'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
array('Label' =>  '(尿沈渣）赤血球'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(尿）白血球'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(尿）細菌'),
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

 
array('Label' =>  '尿素窒素'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'クレアチニン'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '超音波検査'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 3),
	
	array('Insn' => '//'),
 array('Label' =>  '腎機能判定'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

 
array('Label' =>  '尿酸（痛風）'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

array('Label' =>  '痛風判定'),
	array('Column' => 'k417', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
array('Insn' => '//'),

//aaaaaaaaaaaaaaaaaaaaaaaaaaa
array('Label' =>  '甲状腺触診'),
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
 
array('Label' =>  '甲状腺検査'),
	array('Column' => 'k411', 'Span' => 2),
array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
	
array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),
//aaaaaaaaaaaaaaaaaaaaaaaa 
	 
array('Label' =>  '便潜血（免疫法)'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),
array('Label' =>  '胃Ｘ線透視検査'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  'ピロリ菌呼気検査'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => ''),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '上部消化器内視鏡検査'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '下部消化器内視鏡検査'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'カプセル小腸内視鏡検査'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '消化器系判定'),
	array('Column' => 'k415', 'Span' => 1),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc415','Span' =>5),
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
 //bbbbbbbbbbbbbbbbbbbbbbbbb
array('Label' =>  '乳房触診所見'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ﾏﾝﾓｸﾞﾗﾌｨｰ'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),

array('Insn' => '//'),
 
array('Label' =>  '婦人科検査（乳房）'),
	array('Column' => 'k412', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc412','Span' => 5),
	array('Insn' => '//'),
  
 
	

array('Label' =>  '診察所見'),
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
array('Label' =>  '子宮細胞診:頚部'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => ''),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),


array('Insn' => '//'),
 
array('Label' =>  '婦人科検査（子宮、卵巣）'),
	array('Column' => 'k413', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc413','Span' => 5),
array('Insn' => '//'),	
 
 	
array('Label' =>  '触診'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => ''),
	array('Column' => 'c517','Span' => 3),
	
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Label' =>  '直腸診'),
array('Column' => 'k418', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
array('Column' => 'cc418','Span' => 5),
array('Insn' => '//'),
 
 		
 
array('Label' =>  '触診'),
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
 
array('Label' =>  '前立腺検査'),
array('Column' => 'k416','Span' => 2),
array('Insn' => '//'),
 
	array('Label' => ''),
	array('Column' => 'cc416','Span' => 5),	
	array('Insn' => '//'),
//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbb


	
	array('Insn' => '//'),
//yobi etc

array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	
	array('Label' => ''),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => ''),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => ''),
	array('Column' => 'cc532','Span' => 3),
	
	
 

array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => '総合結果.指示事項'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => '備考'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),

);

/* END OF DISPLAY
//1112-2013 changed  uper part is copied from EDIT 	
//****************************************************** 
	array('Label' => '検査日'),
	array('Column' => 'order_date'),
 
	array('Label' => ''),
	array('Column' => 'preorderdate'),
 array('Column' => 'shiji',
					'Label' => 'doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ),
  
	
	array('Insn' => '//'),
	
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	 
 
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '身体計測'),
	array('Column' => 'k401', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'cc401','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '身長'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => '体重'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101'),
array('Column' => 'aa52','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '肥満度'),
	array('Column' => 'k1006','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1006', 'Span' => 1),
	array('Column' => 'aa1006','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => 'BMI'),
	array('Column' => 'k1007','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1007', 'Span' => 1),
	array('Column' => 'aa1007','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '腹囲'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '体脂肪率'),
	array('Column' => 'k1005','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1005', 'Span' => 1),
	array('Column' => 'aa1005','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Insn' => '  ', 'Span' => 1),
	array('Label' =>  '循環器系判定'),
	array('Column' => 'k403', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc403','Span' => 3),
	array('Insn' => '//'),
	array('Label' =>  '血圧（上）'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '血圧（下）'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '心拍数'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  '胸部(心臓)X線'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  '安静時心電図'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '大動脈超音波'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => ''),
	array('Column' => 'c503','Span' => 3),
//0510-2013
array('Insn' => '//'),
	array('Label' =>  '頚動脈超音波'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),
	
	 
//0320
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Insn' => '  ', 'Span' => 1),
	array('Label' =>  '呼吸器系判定'),
	array('Column' => 'k402', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 3),	
	
	array('Insn' => '//'),
	array('Label' =>  '肺機能検査'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => ''),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  '肺活量'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
array('Insn' => '//'),
	
	array('Label' =>  '予想肺活量'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p103', 'Span' => 1),
//	array('Column' => 'aa103','Label' => '','Span' => 1),
	array('Insn' => '//'),
 array('Label' =>  '%肺活量'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p104', 'Span' => 1),
//	array('Column' => 'aa104','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '一秒量'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
array('Insn' => '//'),
	
	array('Label' =>  '一秒率'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 

//??


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  '胸部X線'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
	
	array('Insn' => '//'),

//
array('Insn' => ''),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),


//ETC
//
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '眼科判定'),
	array('Column' => 'k414', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 3),
	
	array('Insn' => '//'),
//
//1111-2013
array('Label' =>  '矯正（右）'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  '矯正（左）'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  '裸眼（右）'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '裸眼（左）'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),

//0501-2013
	array('Insn' => ''),
 
	array('Insn' => '//'),	
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '聴力判定'),
	array('Column' => 'k410', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc410','Span' => 3),
	
	array('Insn' => '//'),
	 
array('Label' =>  '500Hz（右）'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz（左）'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz（右）'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz（左）'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz（右）'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz（左）'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz（右）'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz（左）'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '8000Hz（右）'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
array('Column' => 'aa90','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '8000Hz（左）'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
array('Column' => 'aa91','Label' => '','Span' => 1), 
	
//
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '血液一般判定'),
	array('Column' => 'k409', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc409','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '血液型ABO式'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  '白血球数'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  '赤血球数'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => '血色素量'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => 'ヘマトクリット'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh式'),
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
	
	array('Label' => '血小板数'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),	 
	array('Insn' => '//'), 
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '糖代謝判定'),
	array('Column' => 'k407', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc407','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '空腹時血糖'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '空腹時尿糖'),
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



 
	array('Insn' => ''),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '脂質代謝判定'),
	array('Column' => 'k404', 'Span' => 1),
	
	array('Label' => ''),
	array('Column' => 'cc404','Span' => 3),
	array('Insn' => '//'),
//1020-2012
array('Label' =>  '総コレステロール'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
	
array('Label' =>  '中性脂肪'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
array('Insn' => '//'),

//
array('Label' =>  'HDL(善玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL (悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL(悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
array('Label' =>  'CHOL/HDL 比'),
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),
	array('Column' => 'aa1003','Label' => '','Span' => 1),
//0510-2013	
	array('Column' => 'cc13','Span' => 3),

array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '膵臓器系判定'),
	array('Column' => 'k405', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'アミラーゼ'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc21','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	
	array('Label' => ''),
	array('Column' => 'c519','Span' => 3),
	
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '肝胆のう判定'),
	array('Column' => 'k406', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc406','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '総蛋白'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ｱﾙﾌﾞﾐﾝ'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G比'),
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
array('Label' =>  'γ-GTP'),
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
array('Label' =>  '総ﾋﾞﾘﾙﾋﾞﾝ'),
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
array('Label' =>  'ＨＢｓ抗原'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ＨＢｓ抗体'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'C型肝炎抗体'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => ''),
	array('Column' => 'c520','Span' => 3),
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),

//
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '腎機能判定'),
	array('Column' => 'k408', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc408','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '尿一般　蛋白'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '糖'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'ケトン体'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '潜血'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc67','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '(尿沈渣）赤血球'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(尿）白血球'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(尿）細菌'),
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

 
array('Label' =>  '尿素窒素'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'クレアチニン'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '超音波検査'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 3),
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),

	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '痛風判定'),
	array('Column' => 'k417', 'Span' => 1),
array('Label' => ''),
 	array('Column' => 'cc417','Span' => 3),
	
array('Insn' => '//'),
array('Label' =>  '尿酸（痛風）'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),



 


 
//
	

	
//
 
//0710-2012



 //kojosen
 array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '甲状腺検査'),
	array('Column' => 'k411', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 3),
	
	
	 
 

array('Insn' => '//'),
array('Label' =>  '触診'),
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
//	array('Label' => ''),
//	array('Column' => 'cc92','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc93','Span' => 3),
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '消化器系判定'),
	array('Column' => 'k415', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'cc415','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '便潜血（免疫法)'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc15','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '胃Ｘ線透視検査'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  'ピロリ菌呼気検査'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => ''),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '上部消化器内視鏡検査'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '下部消化器内視鏡検査'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'カプセル小腸内視鏡検査'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 3),
	
	 
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
//nyugan
 
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '乳がん検査'),
	array('Column' => 'k412', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'cc412','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  '触診'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ﾏﾝﾓｸﾞﾗﾌｨｰ'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	
//fujinnka
 array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '婦人科検査'),
	array('Column' => 'k413', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'cc413','Span' => 3),
array('Insn' => '//'),	

array('Label' =>  '診察所見'),
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
//	array('Label' => ''),
//	array('Column' => 'cc94','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '子宮細胞診:頚部'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => ''),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
//chokuchou
 
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '直腸診'),
	array('Column' => 'k418', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'cc418','Span' => 3),
	
array('Insn' => '//'),
array('Label' =>  '触診'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => ''),
	array('Column' => 'c517','Span' => 3),
	
 
//zenritsu
 array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '前立腺検査'),
	array('Column' => 'k416', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'cc416','Span' => 3),
array('Insn' => '//'),	

array('Label' =>  '触診'),
	array('Column' => 'k518', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p518'),
	
	array('Label' => ''),
	array('Column' => 'c518','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'PSA'),
	array('Column' => 'kk95', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc95','Span' => 3),
	
	array('Insn' => '//'),
//yobi etc
array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	
	array('Label' => ''),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => ''),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => ''),
	array('Column' => 'cc532','Span' => 3),

	

array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' =>  '総合結果.指示事項'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => '備考'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),




);
*/


//EDIT START
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE 1115-2013 modefied
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
$__otatest2_order_cfg['E_RANDOM_LAYOUT'] = array(
 
	array('Label' => '検査日'),
 
 array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
array('Column' => 'shiji',
					'Label' => 'doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
array('Insn' => '//'),

array('Insn' => '//'),

array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '診察所見'),
	array('Column' => 'cc400', 'Option' => array('size' => 5)),	
 
array('Insn' => '//'),
array('Label' =>  '****'),
	
 
array('Insn' => '//'),
array('Label' =>  '****'),
	
	array('Insn' => '//'),
	array('Label' =>  '身長'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => '体重'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101'),
array('Column' => 'aa52','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '肥満度'),
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
	array('Label' => '腹囲'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Label' => '体脂肪率'),
	array('Column' => 'k1005','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1005'),
array('Column' => 'aa1005','Label' => '','Span' => 1),
array('Insn' => '//'),
array('Label' =>  '身体計測'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),
	
	
	array('Insn' => '//'),
	array('Label' =>  '血圧（上）'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '血圧（下）'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '心拍数'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  '胸部(心臓)X線'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  '安静時心電図'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  '大動脈超音波'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => ''),
	array('Column' => 'c503','Span' => 3),
//0510-2013
array('Insn' => '//'),
	array('Label' =>  '頚動脈超音波'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),
	
	 
//0320
	
array('Insn' => '//'),
 
	array('Label' =>  '循環器系判定'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc403','Span' => 5),
array('Insn' => '//'),
	
	array('Label' =>  '肺機能検査'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => ''),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  '肺活量'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Insn' => '//'),
 //
 
	
	array('Label' =>  '予想肺活量'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p103', 'Span' => 1),
//	array('Column' => 'aa103','Label' => '','Span' => 1),
	array('Insn' => '//'),
 array('Label' =>  '%肺活量'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
//	array('Column' => 'p104', 'Span' => 1),
//	array('Column' => 'aa104','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => '一秒量'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
	
	array('Label' =>  '一秒率'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 

//??

	 
	array('Insn' => '//'),
 

 


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  '胸部X線'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
	
	 

//0320
array('Insn' => '//'),
array('Label' =>  '呼吸器系判定'),
	array('Column' => 'k402', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
 

//
//1111-2013
array('Label' =>  '矯正（右）'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  '矯正（左）'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  '裸眼（右）'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '裸眼（左）'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),

array('Insn' => '//'),
array('Label' =>  '眼底'),
	array('Column' => 'h1', 'Span' => 1),
array('Insn' => '//'),

array('Insn' => '//'),
array('Label' =>  '眼圧'),
	array('Column' => 'h2', 'Span' => 1),
array('Insn' => '//'),


array('Label' =>  '眼科判定'),
	array('Column' => 'k414', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

array('Insn' => '//'),

//array('Insn' => '  ', 'Span' => 1),

//1111-2013
	array('Insn' => '//'),
	 
array('Label' =>  '500Hz（右）'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz（左）'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz（右）'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz（左）'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz（右）'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz（左）'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz（右）'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz（左）'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '8000Hz（右）'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
array('Column' => 'aa90','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '8000Hz（左）'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
array('Column' => 'aa91','Label' => '','Span' => 1), 
	
//
//1111-2013


array('Insn' => '//'),
array('Label' =>  '聴力判定'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),

	array('Label' =>  '血液型ABO式'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  '白血球数'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  '赤血球数'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => '血色素量'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => 'ヘマトクリット'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh式'),
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
	
	array('Label' => '血小板数'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
	 
	array('Insn' => '//'), 
array('Label' =>  '血液一般判定'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
 

array('Label' =>  '空腹時血糖'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '空腹時尿糖'),
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

array('Label' =>  '糖代謝判定'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),

 
 

//1020-2012
array('Label' =>  '総コレステロール'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '中性脂肪'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'HDL(善玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL (悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL(悪玉) ｺﾚｽﾃﾛｰﾙ'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
array('Label' =>  'CHOL/HDL 比'),
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),
	array('Column' => 'aa1003','Label' => '','Span' => 1),
//0510-2013
	
	array('Insn' => '//'),

array('Label' =>  '脂質代謝判定'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),

array('Label' =>  'アミラーゼ'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
	array('Label' =>  '超音波検査'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	array('Label' => ''),
	array('Column' => 'c519','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  '膵臓器系判定'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
 
array('Label' =>  '総蛋白'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ｱﾙﾌﾞﾐﾝ'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G比'),
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
array('Label' =>  'γ-GTP'),
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
array('Label' =>  '総ﾋﾞﾘﾙﾋﾞﾝ'),
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
array('Label' =>  'ＨＢｓ抗原'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ＨＢｓ抗体'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'C型肝炎抗体'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => ''),
	array('Column' => 'c520','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  '肝胆のう判定'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
 

array('Label' =>  '尿一般　蛋白'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '糖'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'ケトン体'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '潜血'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
array('Label' =>  '(尿沈渣）赤血球'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(尿）白血球'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '(尿）細菌'),
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

 
array('Label' =>  '尿素窒素'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'クレアチニン'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '超音波検査'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 3),
	
	array('Insn' => '//'),
 array('Label' =>  '腎機能判定'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

 
array('Label' =>  '尿酸（痛風）'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

array('Label' =>  '痛風判定'),
	array('Column' => 'k417', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
array('Insn' => '//'),

 
	
array('Label' =>  '甲状腺触診'),
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
 
array('Label' =>  '甲状腺検査'),
	array('Column' => 'k411', 'Span' => 2),
array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
	
array('Insn' => '//'),
//array('Insn' => '  ', 'Span' => 1),

array('Label' =>  '便潜血（免疫法)'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),
array('Label' =>  '胃Ｘ線透視検査'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  'ピロリ菌呼気検査'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => ''),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '上部消化器内視鏡検査'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '下部消化器内視鏡検査'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'カプセル小腸内視鏡検査'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  '消化器系判定'),
	array('Column' => 'k415', 'Span' => 1),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc415','Span' =>5),
	
	array('Insn' => '//'),
 
	
 
array('Label' =>  '乳房触診所見'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ﾏﾝﾓｸﾞﾗﾌｨｰ'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '超音波検査'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),

array('Insn' => '//'),
 
array('Label' =>  '婦人科検査（乳房）'),
	array('Column' => 'k412', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc412','Span' => 5),
	array('Insn' => '//'),
  
 
	

array('Label' =>  '診察所見'),
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
array('Label' =>  '子宮細胞診:頚部'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => ''),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),


array('Insn' => '//'),
 
array('Label' =>  '婦人科検査（子宮、卵巣）'),
	array('Column' => 'k413', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc413','Span' => 5),
array('Insn' => '//'),	
 
 	
array('Label' =>  '触診'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => ''),
	array('Column' => 'c517','Span' => 3),
	
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Label' =>  '直腸診'),
array('Column' => 'k418', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
array('Column' => 'cc418','Span' => 5),
array('Insn' => '//'),
 
 		
 
array('Label' =>  '触診'),
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
 
array('Label' =>  '前立腺検査'),
array('Column' => 'k416','Span' => 2),
array('Insn' => '//'),
 
	array('Label' => ''),
	array('Column' => 'cc416','Span' => 5),	
	array('Insn' => '//'),
 
 array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	array('Label' => ''),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => ''),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => ''),
	array('Column' => 'cc532','Span' => 3),
	
	
 

array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => '総合結果.指示事項'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => '備考'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),




);

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

 	if ($data['k200']) {
 
 		$data['k106'] = sprintf("%.2f",$data['k203']/$data['k200']*100);
		$pat = get_patient($data['patient'],false);
		if ($pat['性別'] == 'M'){
 
 
		$data['k103']= sprintf("%.2f",(27.63-0.112*mx_calc_age($pat["生年月日"]))*$data['k100']);
 


		}
		else {
 
		// print "DOB=".$pat['生年月日'];
		// print "age=".mx_calc_age($pat['生年月日']);
		$data['k103']= sprintf("%.2f",(21.78-0.101*mx_calc_age($pat["生年月日"]))*$data['k100']);
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
