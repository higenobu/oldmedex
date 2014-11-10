<?php // -*- mode: php; coding: euc-japan -*-
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
//add 0320-2012

//LCM 0325-2014

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
//only for osato-clinic pdf print
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf44.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ndk-dw.php';


//1103-2013

function _lib_u_ota_kiroku2() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "Ì¾" ||' '||"À«"  as empname , userid
    from "¿¦°÷ÂæÄ¢" where "¿¦¼ï"=2 and "Superseded" is null
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['empname']] = $row['empname'];
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
$__otatest2_a3 =  array
('', '(-)','(+-)','(+)','2(+)','3(+)','4(+)'
);
$__otatest2_a4 = array
('','1-2','2-5','5-15','15-30','30-50','50->'
);
$__otatest2_a5 = array
('','(-)','(-)x2','(+)','(+)x2' );
$__otatest2_a6 = array
('','none','rare','moderate','many');

$__otatest2_report_enum = array
(
	'1'=>'Japanese','2'=>'English','3'=>'Both',
);
$__otatest2_category_enum = array
(
	'A','B','C','D','E','F','G'
);
//0320-2014
$__otatest2_addition_enum = array
(
	'0'=>'','1'=>'normal','2'=>'deleted',
);


 
 
$__otatest2_exam_enum = array
(
	'0'=>'','1'=>'°Û¾ï¤Ê¤· normal','2'=>'Àµ¾ïÈÏ°Ï Mild Abnormal','3'=>'·Ð²á´Ñ»¡Recheckin 12 month','4'=>'ºÆ¸¡ºº Recheckin 6 month','5'=>'ÀºÌ©¸¡ºº Needs Followup','6'=>'Í×¼£ÎÅ Needs Treatment'
);
 
 

$__otatest2_hantei_enum = array
(
	'0'=>'','1'=>'°Û¾ï¤Ê¤· normal','2'=>'Àµ¾ïÈÏ°Ï Mild Abnormal','3'=>'·Ð²á´Ñ»¡Recheckin 12 month','4'=>'ºÆ¸¡ºº Recheckin 6 month','5'=>'ÀºÌ©¸¡ºº Needs Followup','6'=>'Í×¼£ÎÅ Needs Treatment'
);
//0323-2014
$__otatest2_n_enum = array
(
'0'=>'-','1'=>'Ab','2'=>'small up','3'=>'small down','4'=>'up','5'=>'down'
);
$__otatest2_n2_enum = array
(
''=>'','non-reactive'=>'non-reactive','reactive'=>'reactive',
);
$__otatest2_plus_enum = array
(
	'','(-)','(+)',);
$__otatest2_abo_enum = array
(
   '','A','B','O','AB',
);

$__otatest2_rh_enum = array
(
	'','Rh+','Rh-', 
);
$__otatest2_np_enum = array
(
	'','NEGATIVE','POSITIVE', 
);

//************************


$__otatest2_all_cols = array(
 
	array('Column' => 'patient',
	      'Draw' => NULL,
	      'Option' => array('noedit' => 1, 'nodisp'=> 1),
	      ),
 
	array('Column' => 'order_date',
	      'Label' => 'DOS',
	      'Draw' => 'date',
	      'Option' => array('list' => 1,'size'=>10),
	      ),
 
 
array('Column' => 'shiji',
					'Label' => 'DR',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2(), 
  'Option' => array(  'list' => 1,'size'=>10),

				       ),
	array('Column' => 'preorderdate',
	      'Label' => ' Previous DOS',
	      'Draw' => 'date',

	      'Option' => array('list' => 1,'size'=>10),
	      ),
	array('Column' => 'category',
	      'Label' => 'Package',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_category_enum),
	      'Option' => array('list'=>1,'size'=>10),
	      ),
	array('Column' => 'addition',
	      'Label' => 'Normal/Deleted',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_addition_enum,
		'Option' => array(  'list' => 1,'size'=>10),
	      ),
//1120-2013
//NEW
 
array('Column' => 'cc0','Label' => 'History À¸³è½¬´·','Draw' => 'textarea',
	     'Option' => array('vocab' => array('notes'),
		'cols' =>60,'rows'=>5),),
//0211-2014
array('Column' => 'ss0','Label' => '¼«³Ð¾É¾õ','Draw' => 'textarea',
	     'Option' => array('vocab' => array('notes'),
		'cols' =>60,'rows'=>5),),

array('Column' => 'ss1','Label' => ' ','Draw' => 'textarea',
	     'Option' => array('vocab' => array('notes'),
		'cols' =>60,'rows'=>5),),
//k560 for MRI/MRA
array('Column' => 'k560',
'Label' => '560',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p560',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c560',
'Draw' => 'textarea',
'Option' => array('vocab' => array('560','na'),
'cols' => 20,'rows'=>3),),
//1120-2013
//
array('Column' => 'k561',
'Label' => '561',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p561',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c561',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c561','na'),
'cols' => 20,'rows'=>3),),	
//0712-2013
array('Column' => 'k1006',
'Draw' => 'text','Option' => array('size' => 10), 
),
array('Column' => 'p1006',
'Draw' => 'static','Option' => array('size' => 10), 
),
array('Column' => 'k1007',
'Draw' => 'static', 
),
array('Column' => 'p1007',
'Draw' => 'static','Option' =>array('size' => 10), 
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
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k81',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p81',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k82',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p82',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k83',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p83',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k84',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p84',
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k85',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p85',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k86',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p86',
'Draw' => 'static','Option' => array('size' => 10),
),
//
array('Column' => 'k87',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p87',
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k88',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p88',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k89',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p89',
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k90',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p90',
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k91',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p91',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'k100',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p100',
'Draw' => 'static','Option' =>array('size' => 10),
),

array('Column' => 'k101',
'Draw' => 'text','Option' =>array('size' => 10),
),
array('Column' => 'p101',
'Draw' => 'static','Option' =>array('size' => 10),
),
array('Column' => 'k102',
'Draw' => 'text','Option' =>array('size' => 10),
),
array('Column' => 'p102',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k103',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p103',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k104',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p104',
'Draw' => 'static','Option' =>array('size' => 10), 
),

array('Column' => 'k105',
'Draw' => 'text','Option' =>array('size' => 10), 

//'Draw' => 'static',

),
array('Column' => 'p105',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k106',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p106',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k107',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p107',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k1000',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p1000',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k1001',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p1001',
'Draw' => 'static','Option' =>array('size' => 10), 
),
//0510-2013
array('Column' => 'k1002',
'Label' => '',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'p1002',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum, 
),
array('Column' => 'c1002',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc0','na'),
'cols' =>20,'rows'=>3), 
),
//0601-2013
array('Column' => 'k1003',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_plus_enum), 
),
array('Column' => 'p1003',
'Draw' => 'static','Option' =>array('size' => 10), 
),
array('Column' => 'k1004',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p1004',
'Draw' => 'static','Option' =>array('size' => 10), 
),

array('Column' => 'k1005',
'Draw' => 'text','Option' =>array('size' => 10), 
),
array('Column' => 'p1005',
'Draw' => 'static','Option' =>array('size' => 10), 
),


//0510-2013
//
array('Column' => 'k500',
'Label' => '¶»Éô£ØÀþ',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p500',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
//05-08-2013
array('Column' => 'c500',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c500','na'),
'cols' =>20,'rows'=>3),
),
array('Column' => 'k501',
'Label' => '¶»Éô(¿´Â¡)£ØÀþ',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p501',
'Draw' => 'static',
//0323-2014
//'Draw' => 'enum',
//'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c501',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c501','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k502',
'Label' => '2',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p502',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c502',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c502','na'),
'cols' => 20,'rows'=>3),
),
//

array('Column' => 'k503',
'Label' => '3',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p503',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c503',
'Draw' => 'textarea',
 'Option' => array('vocab' => array('c503','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k504',
'Label' => '4',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p504',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
//05-08-2013
array('Column' => 'c504',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c509','na'),
'cols' => 20,'rows'=>3),
),
//pirori
array('Column' => 'k505',
'Label' => '505',
'Draw' => 'text','Option' => array('size' => 10),

),
array('Column' => 'p505',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'c505',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c505','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k506',
'Label' => '6',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p506',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c506',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c506','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k507',
'Label' => '7',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p507',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c507',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c507','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k508',
'Label' => '8',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p508',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c508',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c508','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k509',
'Label' => '9',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p509',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c509',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c509','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k510',
'Label' => '10',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p510',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c510',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c510','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k511',
'Label' => '11',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p511',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c511',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c511','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k512',
'Label' => '12',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p512',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c512',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c512','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k513',
'Label' => '13',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p513',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c513',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c513','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k514',
'Label' => '14',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p514',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c514',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c514','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k515',
'Label' => '15',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p515',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c515',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c515','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k516',
'Label' => '16',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p516',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c516',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c516','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k517',
'Label' => '17',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p517',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c517',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c517','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k518',
'Label' => '18',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p518',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c518',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c518','na'),
'cols' => 20,'rows'=>3),
),
//
array('Column' => 'k519',
'Label' => 'chouonpa',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p519',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
//
array('Column' => 'c519',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c519','na'),
'cols' => 20,'rows'=>3),
),
array('Column' => 'k520',
'Label' => '20',
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,

),
array('Column' => 'p520',
'Draw' => 'static',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'c520',
'Draw' => 'textarea',
'Option' => array('vocab' => array('c520','na'),
'cols' => 20,'rows'=>3),
),
// k500-k521 add Kk530--KK540  0315-2013
array('Column' => 'kk530',
'Draw' => 'text','Option' => array('size' => 10),


),
array('Column' => 'pp530',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc530',
 
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,
),
array('Column' => 'kk540',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'kk531',
'Label' => '20',
'Draw' => 'text','Option' => array('size' => 10),


),
array('Column' => 'pp531',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc531',
 
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,
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
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'cc532',
 
'Draw' => 'enum',
'Enum' => $__otatest2_exam_enum,
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
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k201',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p201',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k202',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p202',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k203',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p203',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k204',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p204',
'Draw' => 'static','Option' => array('size' => 10),
),

//
//

array('Column' => 'k300',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p300',
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'k301',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p301',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'k302',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'p302',
'Draw' => 'static','Option' => array('size' => 10),
),

//04-24-2012


array('Column' => 'kk10',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp10',
'Draw' => 'static','Option' => array('size' => 10),
),
 
array('Column' => 'cc10',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc10','na'),
'cols' => 20),
),
//
array('Column' => 'kk11',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp11',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc11',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc11','na'),
'cols' => 20),),
array('Column' => 'kk12',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp12',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc12',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc12','na'),
'cols' => 20),),
array('Column' => 'kk13',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp13',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc13',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc13','na'),
'cols' => 20),),
array('Column' => 'kk14',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp14',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc14',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc14','na'),
'cols' => 20),),
array('Column' => 'kk15',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a5),

),

array('Column' => 'pp15',
'Draw' => 'static',
'Enum' => mk_enum($__otatest2_plus_enum),
),


array('Column' => 'cc15',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc15','na'),
'cols' => 20),),

//1020-2012
array('Column' => 'cc16',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc16','na'),
'cols' => 20),),
array('Column' => 'cc17',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc17','na'),
'cols' => 20),),
array('Column' => 'cc18',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc18','na'),
'cols' => 20),),
array('Column' => 'cc19',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc19','na'),
'cols' => 20),),



//
array('Column' => 'kk21',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp21',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc21',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc21','na'),
'cols' => 20),),
array('Column' => 'kk22',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp22',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc22',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc22','na'),
'cols' => 20),),
array('Column' => 'kk20',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp20',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc20',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc20','na'),
'cols' => 20),),
//
//
array('Column' => 'kk30',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp30',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc30',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc30','na'),
'cols' => 20),),

array('Column' => 'kk31',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp31',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc31',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc31','na'),
'cols' => 20),),
array('Column' => 'kk32',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp32',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc32',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc32','na'),
'cols' => 20),),
array('Column' => 'kk33',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp33',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc33',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc33','na'),
'cols' => 20),),
array('Column' => 'kk34',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp34',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc34',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc34','na'),
'cols' => 20),),
array('Column' => 'kk35',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp35',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc35',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc35','na'),
'cols' => 20),),

array('Column' => 'kk36',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp36',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc36',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc36','na'),
'cols' => 20),),
array('Column' => 'kk37',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp37',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc37',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc37','na'),
'cols' => 20),),
array('Column' => 'kk38',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp38',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc38',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc38','na'),
'cols' => 20),),
//

array('Column' => 'kk39',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'pp39',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc39',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc39','na'),
'cols' => 20),),


array('Column' => 'kk40',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'pp40',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc40',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc40','na'),
'cols' => 20),),

array('Column' => 'kk41',
'Draw' => 'text','Option' => array('size' => 10),

),
array('Column' => 'pp41',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc41',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc41','na'),
'cols' => 20),),
array('Column' => 'kk42',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp42',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc42',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc42','na'),
'cols' => 20),),
//
//04-23-2012 kk50
array('Column' => 'kk50',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp50',
'Draw' => 'static','Option' => array('size' => 10), 
),
array('Column' => 'cc50',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc50','na'),
'cols' => 20),),
//04-22-2012

array('Column' => 'kk51',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a3), 
),
array('Column' => 'pp51',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc51',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc51','na'),
'cols' => 20),),
array('Column' => 'kk52',
'Draw' => 'text','Option' => array('size' => 10),

),
array('Column' => 'pp52',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc52',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc52','na'),
'cols' => 20),),
array('Column' => 'kk53',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp53',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc53',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc53','na'),
'cols' => 20),),
array('Column' => 'kk54',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp54',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc54',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc54','na'),
'cols' => 20),),
array('Column' => 'kk55',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp55',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc55',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc55','na'),
'cols' => 20),),

array('Column' => 'kk56',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp56',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc56',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc56','na'),
'cols' => 20),),
array('Column' => 'kk57',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp57',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc57',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc57','na'),
'cols' => 20),),
array('Column' => 'kk58',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp58',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc58',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc58','na'),
'cols' => 20),),

array('Column' => 'kk59',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp59',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc59',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc59','na'),
'cols' => 20),),


//
//
array('Column' => 'kk60',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a4), 
),
array('Column' => 'pp60',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc60',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc60','na'),
'cols' => 20),),

array('Column' => 'kk61',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a4), 
),
array('Column' => 'pp61',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc61',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc61','na'),
'cols' => 20),),
array('Column' => 'kk62',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a3), 
),
array('Column' => 'pp62',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc62',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc62','na'),
'cols' => 20),),
array('Column' => 'kk63',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp63',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc63',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc63','na'),
'cols' => 20),),
array('Column' => 'kk64',
 'Draw' => 'enum',
 	      'Enum' => mk_enum($__otatest2_a3),
//'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp64',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc64',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc64','na'),
'cols' => 20),),
array('Column' => 'kk65',
//'Draw' => 'text','Option' => array('size' => 10),
 'Draw' => 'enum',
 	      'Enum' => mk_enum($__otatest2_a3),
),
array('Column' => 'pp65',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc65',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc65','na'),
'cols' => 20),),
//0531-2013

array('Column' => 'kk66',
//'Draw' => 'text','Option' => array('size' => 10),
 'Draw' => 'enum',
 	      'Enum' => mk_enum($__otatest2_a3),
),
array('Column' => 'pp66',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc66',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc66','na'),
'cols' => 20),),
array('Column' => 'kk67',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a3), 
),
array('Column' => 'pp67',
'Draw' => 'static',
'Enum' => mk_enum($__otatest2_a3), 
),
array('Column' => 'cc67',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc67','na'),
'cols' => 20),),


//04-22-2012
array('Column' => 'kk70',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_abo_enum),

),
array('Column' => 'pp70',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc70',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc70','na'),
'cols' => 20),),

array('Column' => 'kk71',
'Draw' => 'text','Option' => array('size' => 10),

),
array('Column' => 'pp71',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'cc71',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc71','na'),
'cols' => 20),
),
//
array('Column' => 'kk72',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp72',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'cc72',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc72','na'),
'cols' => 20),
),
array('Column' => 'kk73',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp73',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'cc73',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc73','na'),
'cols' => 20),
),
//
array('Column' => 'kk74',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp74',
'Draw' => 'static','Option' => array('size' => 10),
),

array('Column' => 'cc74',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc74','na'),
'cols' => 20),
),

array('Column' => 'kk75',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_rh_enum),
),

array('Column' => 'kk76',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp76',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'cc76',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc76','na'),
'cols' => 20),
),
array('Column' => 'kk77',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp77',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc77',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc77','na'),
'cols' => 20),
),

array('Column' => 'kk78',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp78',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'cc78',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc78','na'),
'cols' => 20),
),

array('Column' => 'kk79',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp79',
'Draw' => 'static','Option' => array('size' => 10),
),


array('Column' => 'cc79',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc79','na'),
'cols' => 20),
),
//
//
array('Column' => 'kk90',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp90',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc90',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc90','na'),
'cols' => 20),),

array('Column' => 'kk91',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp91',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc91',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc91','na'),
'cols' => 20),),
array('Column' => 'kk92',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp92',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc92',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc92','na'),
'cols' => 20),),
array('Column' => 'kk93',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp93',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc93',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc93','na'),
'cols' => 20),),
array('Column' => 'kk94',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp94',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc94',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc94','na'),
'cols' => 20),),
array('Column' => 'kk95',
'Draw' => 'text','Option' => array('size' => 10),
),
array('Column' => 'pp95',
'Draw' => 'static','Option' => array('size' => 10),
),
array('Column' => 'cc95',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc95','na'),
'cols' => 20),),
//0115-2014

//**************************************************
//	    
array('Column' => 'special_req',
'Label' => 'Summary',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cccc','na'),
'cols' => 60,'list' => 0,'rows'=>5),
),


array('Column' => 'notes',
'Label' => 'Note',
'Draw' => 'textarea',
'Option' => array('vocab' => array('notes','na'),
'cols' =>60,'list' => 0,'rows'=>5),
),
//**************************************************
//04-20-2013 start
array('Column' => 'k400',
'Draw' =>'static',
//	      'Label' => 'hantei0',
//	      'Draw' => 'enum',
//	      'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc400',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cccc','na'),
//				'cols' => 20),),
'cols' => 60,'rows'=>5),),
array('Column' => 'k401',
'Label' => 'hantei1',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,),
//1125-2013
array('Column' => 'cc401',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc401','na'),
'cols' => 60,'rows'=>5),),

array('Column' => 'k402',
'Label' => 'hantei2',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc402',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc402','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k403',
'Label' => 'hantei3',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc403',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc403','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k404',
'Label' => 'hantei4',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,
),
array('Column' => 'cc404',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc404','na'),
'cols' => 60,'rows'=>5),),


array('Column' => 'k405',
'Label' => 'hantei5',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc405',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc405','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k406',
'Label' => 'hantei6',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc406',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc406','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k407',
'Label' => 'hante7',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc407',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc407','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k408',
'Label' => 'hantei8',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc408',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc408','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k409',
'Label' => 'hantei9',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc409',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc409','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k410',
'Label' => 'hantei10',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc410',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc410','na'),
'cols' => 60,'rows'=>5),),

array('Column' => 'k413',
'Label' => 'hantei13',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc413',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc413','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k414',
'Label' => 'hantei14',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc414',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc414','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k415',
'Label' => 'hantei15',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc415',
'Draw' => 'textarea',
'Option' => array('vocab' => array('f100','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k411',
'Label' => 'hantei11',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc411',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc411','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k412',
'Label' => 'hante12',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc412',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc412','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k416',
'Label' => 'hante16',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc416',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc416','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k417',
'Label' => 'hante17',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc417',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc417','na'),
'cols' => 60,'rows'=>5),),
array('Column' => 'k418',
'Label' => 'hante18',
'Draw' => 'enum',
'Enum' => $__otatest2_hantei_enum,

),
array('Column' => 'cc418',
'Draw' => 'textarea',
'Option' => array('vocab' => array('cc418','na'),
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
//***********************************



//******************************

array('Column' => 'cc100', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc100','na'),'cols' => 20),),
array('Column' => 'kk100',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp100',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa100',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc101', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc101','na'),'cols' => 20),),
array('Column' => 'kk101',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp101',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa101',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc102', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc102','na'),'cols' => 20),),
array('Column' => 'kk102',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp102',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa102',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc103', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc103','na'),'cols' => 20),),
array('Column' => 'kk103',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp103',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa103',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc104', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc104','na'),'cols' => 20),),
array('Column' => 'kk104',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp104',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa104',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc105', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc105','na'),'cols' => 20),),
array('Column' => 'kk105',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'pp105',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa105',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc106', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc106','na'),'cols' => 20),),
array('Column' => 'kk106',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp106',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa106',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc107', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc107','na'),'cols' => 20),),
array('Column' => 'kk107',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'pp107',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa107',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc108', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc108','na'),'cols' => 20),),
array('Column' => 'kk108',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'pp108',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa108',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc109', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc109','na'),'cols' => 20),),
array('Column' => 'kk109',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'pp109',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa109',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc110', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc110','na'),'cols' => 20),),
array('Column' => 'kk110',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp110',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa110',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc111', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc111','na'),'cols' => 20),),
array('Column' => 'kk111',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp111',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa111',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc112', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc112','na'),'cols' => 20),),
array('Column' => 'kk112',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp112',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa112',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc113', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc113','na'),'cols' => 20),),
array('Column' => 'kk113',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp113',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa113',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc114', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc114','na'),'cols' => 20),),
array('Column' => 'kk114',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp114',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa114',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc115', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc115','na'),'cols' => 20),),
array('Column' => 'kk115',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp115',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa115',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc116', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc116','na'),'cols' => 20),),
array('Column' => 'kk116',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp116',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa116',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc117', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc117','na'),'cols' => 20),),
array('Column' => 'kk117',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp117',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa117',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc118', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc118','na'),'cols' => 20),),
array('Column' => 'kk118',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp118',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa118',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc119', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc119','na'),'cols' => 20),),
array('Column' => 'kk119',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp119',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa119',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc120', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc120','na'),'cols' => 20),),
array('Column' => 'kk120',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp120',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa120',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc121', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc121','na'),'cols' => 20),),
array('Column' => 'kk121',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp121',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa121',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc122', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc122','na'),'cols' => 20),),
array('Column' => 'kk122',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_a6), 
),
array('Column' => 'pp122',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa122',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc123', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc123','na'),'cols' => 20),),
array('Column' => 'kk123',
'Draw' => 'enum',
'Enum' => mk_enum($__otatest2_plus_enum), 
),
array('Column' => 'pp123',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa123',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc124', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc124','na'),'cols' => 20),),
array('Column' => 'kk124',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp124',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa124',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc125', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc125','na'),'cols' => 20),),
array('Column' => 'kk125',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp125',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa125',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc126', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc126','na'),'cols' => 20),),
array('Column' => 'kk126',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp126',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa126',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),
array('Column' => 'cc127', 'Draw' => 'textarea',
'Option' => array('vocab' => array('cc127','na'),'cols' => 20),),
array('Column' => 'kk127',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp127',
'Draw' => 'static','Option' => array('size' => 10),),
array('Column' => 'aa127',
'Label' => '','Draw' => 'enum','Enum' => $__otatest2_n_enum,),

//**************************************
//1205-2013 add
 
array('Column' => 'kk600',
'Draw' => 'text','Option' => array('size' => 10),),
array('Column' => 'pp600',
'Draw' => 'static','Option' => array('size' => 10),),
 


 
//***************************************


array('Column' => 'h1','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h2','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h3','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h4','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h5','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h6','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h7','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h8','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
//0210-2014
array('Column' => 'h9','Label' => '','Draw' => 'enum','Enum' => $__otatest2_n2_enum,),
array('Column' => 'h10','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h11','Label' => '','Draw' => 'enum','Enum' => $__otatest2_hantei_enum,),
array('Column' => 'h12','Label' => '','Draw' => 'enum','Enum' => $__otatest2_report_enum,),
//
);







//******************************************END***************

//**************************
$__otatest2_order_cfg = array();

$__otatest2_order_cfg['TABLE'] = 'otatest_order';
$__otatest2_order_cfg['SEQUENCE'] = 'otatest_order_id_seq';
$__otatest2_order_cfg['COLS'] = array();
$__otatest2_order_cfg['ICOLS'] = array();
$__otatest2_order_cfg['ECOLS'] = array();
$__otatest2_order_cfg['LCOLS'] = array();
$__otatest2_order_cfg['DCOLS'] = array();
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

//DDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD
//*****************************************************
//0323-2014
//

//****************************************************************

$__otatest2_order_cfg['D_RANDOM_LAYOUT'] = array(
 
	array('Label' => 'DOS'),
 
 array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
array('Column' => 'shiji',
					'Label' => 'DR',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
 array('Insn' => '//'),
array('Column' => 'category',
	      'Label' => 'Package',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_category_enum),
	      'Option' => array('size'=>10),
 
	      ),
 
array('Column' => 'addition',
	      'Label' => 'Normal/Deleted',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_addition_enum,
	       
 
	      ),
 

 array('Label' =>  ''),
array('Column' => 'h12',
	      'Label' => 'Report Type',
	      'Draw' => 'enum',
	      'Enum' =>$__otatest2_report_enum,
	      'Option' => array('size'=>10),
	      ),	

array('Insn' => '//'),
array('Label' =>  '----------------'),
	
	array('Insn' => '//'),
	array('Label' =>  'Height'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100'),
array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => 'Weight'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101'),
array('Column' => 'aa52','Label' => '','Span' => 1),
 /*
array('Insn' => '//'),
	array('Label' => 'Obesity index'),
	array('Column' => 'k1006','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1006'),


	array('Column' => 'aa1006','Label' => '','Span' => 1),
*/
	
 
array('Insn' => '//'),
	array('Label' => 'BMI'),
	array('Column' => 'k1007','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1007'),
array('Column' => 'aa1007','Label' => '','Span' => 1),
array('Label' => ''),
	array('Column' => 'h1'),
array('Insn' => '//'),
	array('Label' => 'Adb Girth'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
 

array('Insn' => '//'),
 

array('Label' =>  'Physical Exam'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),



//*********************************************
array('Insn' => '//'),
 
	array('Label' =>  'Cardiac'),
	 
array('Insn' => '//'),
 
array('Label' => ''),	
 	
	array('Insn' => '//'),
	array('Label' =>  'Blood Press(Up)'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => 'Blood Press(Down)'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => ' Blood Press'),
	array('Column' => 'h5', 'Span' => 1),
 
 	array('Insn' => '//'),
 	array('Label' => 'Pulse'),
 	array('Column' => 'k302', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'p302', 'Span' => 1),
 	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),


 
	array('Label' =>  ' Chest(heart)X-Ray '),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => ''),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
 

	array('Label' =>  'EKG'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => ''),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
//	array('Label' =>  'ÂçÆ°Ì®Ä¶²»ÇÈ'),
//	array('Column' => 'k503', 'Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'p503'),
	
//	array('Label' => ''),
//	array('Column' => 'c503','Span' => 3),
 

 
array('Insn' => '//'),
	array('Label' =>  'Echocardiogram2'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002'),
	
	array('Label' => ''),
	array('Column' => 'c1002','Span' => 3),
	
	 
 
 
 	
array('Insn' => '//'),
 
	array('Label' =>  'Cardiac Grade'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc403','Span' => 5),
array('Insn' => '//'),
 
	

 
 
	array('Insn' => '//'),
 
	 
	
	array('Label' =>  ' Forced Vital Capacity'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Label' => ''),
	array('Column' => 'h2'),
	array('Insn' => '//'),
 //
 	array('Insn' => '//'),
 array('Label' =>  'Ideal FVC'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
 
	array('Insn' => '//'),
 
 
 


	array('Insn' => '//'),
 array('Label' =>  '%FVC'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
 
	array('Insn' => '//'),
 

 
 	
	array('Label' =>  'FEV1%'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 
array('Insn' => '//'),
 
	
	array('Label' =>  'intentFVC'),
	array('Column' => 'kk600', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'pp600', 'Span' => 1),

 

	 
	array('Insn' => '//'),
 

 


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  'Chest X-Ray'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
	array('Column' => 'c500','Span' => 3),
	
//*********************************	 

 
array('Insn' => '//'),
array('Label' =>  ' Pulmonary Function'),
	array('Column' => 'k402', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
 
array('Label' =>  'Vision'), 

array('Insn' => '//'),
//
 
array('Label' =>  'Corrected(R)'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  'Corrected(L)'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  'Uncorr(R)'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  'Uncorr(L)'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  ' Ophthalmology'),
	array('Column' => 'k414', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

	array('Insn' => '//'),
 



	array('Insn' => '//'),
   	array('Label' =>  'Audibility'),
	array('Insn' => '//'),

	array('Insn' => '//'),
	 
array('Label' =>  '500Hz(R)'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz(L)'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz(R)'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz(L)'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz(R)'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz(L)'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz(R)'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz(L)'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
 
 
	
 
array('Insn' => '//'),
array('Label' =>  'Audiometry'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),

array('Insn' => '//'),
   	array('Label' =>  'Hematologic Study'),
	array('Insn' => '//'),
 array('Insn' => '//'),
array('Label' =>  'Anemia'),
	array('Column' => 'h3', 'Span' => 2),
	array('Insn' => '//'),

	array('Label' =>  'ABO Blood Type'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  'WBC'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  'RBC'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'Hemogl Cnt'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => 'Hematocrit'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh'),
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
	
	array('Label' => 'Platelet'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
array('Insn' => '//'),
//
	
	array('Label' => 'Nuetr'),
	array('Column' => 'kk100',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp100'),
	 array('Column' => 'aa100','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Lymph'),
	array('Column' => 'kk101',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp101'),
	 array('Column' => 'aa101','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Mono'),
	array('Column' => 'kk102',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp102'),
	 array('Column' => 'aa102','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Eosino'),
	array('Column' => 'kk103',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp103'),
	 array('Column' => 'aa103','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Baso'),
	array('Column' => 'kk104',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp104'),
	 array('Column' => 'aa104','Label' => '','Span' => 1),
	 
	array('Insn' => '//'), 

array('Label' =>  'Hematologic Study'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
 array('Insn' => '//'),
   	array('Label' =>  'Blood Exam'),
	array('Insn' => '//'),
 
array('Insn' => '//'),
//
	
	array('Label' => 'HAV'),
	array('Column' => 'kk105',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp105'),
	 array('Column' => 'aa105','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'CRP'),
	array('Column' => 'kk106',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp106'),
	 array('Column' => 'aa106','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'RA'),
	array('Column' => 'kk107',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp107'),
	 array('Column' => 'aa107','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'RPR'),
	array('Column' => 'kk108',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp108'),
	 array('Column' => 'aa108','Label' => '','Span' => 1),
array('Insn' => '//'),
//


	array('Insn' => '//'),
array('Label' =>  '£È£Â£óAntigen'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '£È£Â£ó Antibody'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
//	
	array('Label' => 'HCV Antibody'),
	array('Column' => 'kk109',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp109'),
	 array('Column' => 'aa109','Label' => '','Span' => 1),
//******************************************************
//0121-2014
array('Insn' => '//'),
array('Label' => 'Glucose study'),
//array('Column' =>'h6'),	 
	
	array('Insn' => '//'),
 

 
 


array('Label' =>  'Glucose'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ' UrinalysisSugar'),
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
 
array('Label' =>  'Glucose Metabolism'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),
 


 
 //
array('Insn' => '//'),
array('Label' => 'Lipid Metabolism'),
	 
	
	array('Insn' => '//'),
 

//1020-2012
array('Label' =>  'Total Cholesterol'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Triglycerides'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'HDL'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
 
	
	array('Insn' => '//'),
array('Label' =>  'LDL'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
 	
	array('Insn' => '//'),

array('Label' =>  ' Lipid Metabolism'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),
//
array('Insn' => '//'),
array('Label' =>  'Pancreatic'),
array('Insn' => '//'),
array('Label' =>  'Amylase'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
 
array('Label' =>  'Pancreatic Grade'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
 array('Insn' => '//'),
array('Label' =>  'Liver'),
array('Insn' => '//'),
array('Label' =>  'T.Protein'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Albumin'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G'),
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
//	array('Column' => 'aa13','Label' => '','Span' => 1),
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
//
array('Label' =>  'T.Bilirubin'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),


 //
	
	array('Insn' => '//'),
array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc38','Span' => 3),
//************************************************
array('Insn' => '//'),
//
	
	array('Label' => 'LAP'),
	array('Column' => 'kk110',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp110'),
	 array('Column' => 'aa110','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Serum iron'),
	array('Column' => 'kk111',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp111'),
	 array('Column' => 'aa111','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Cholinest'),
	array('Column' => 'kk117',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp117'),
	 array('Column' => 'aa117','Label' => '','Span' => 1),
//**********************************************
	
 
	
	array('Insn' => '//'),
 
 

 
array('Label' =>  'Liver function'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
 
array('Insn' => '//'),
array('Label' =>  'Urinalysis'),
array('Column' => 'h7'),
array('Insn' => '//'),

array('Label' =>  ' U.Protein'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),

	
	array('Insn' => '//'),
array('Label' =>  'U.Sugar'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'Ketone'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Occult Blood'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
array('Label' =>  'U.RBC'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ' U.WBC'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	array('Insn' => '//'),
//
	
	array('Label' => 'Ratio'),
	array('Column' => 'kk119',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp119'),
	 array('Column' => 'aa119','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'PH'),
	array('Column' => 'kk120',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp120'),
	 array('Column' => 'aa120','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Urobili'),
	array('Column' => 'kk121',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp121'),
	 array('Column' => 'aa121','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'squam. epith'),
	array('Column' => 'kk122',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp122'),
	 array('Column' => 'aa122','Label' => '','Span' => 1),

array('Insn' => '//'),
	array('Insn' => '//'),

array('Label' =>  'Bacteria'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
//
array('Insn' => '//'),
array('Label' =>  'Metabolic'),
array('Column'=>'h6'),
array('Insn' => '//'),


 
 
//
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
//	array('Column' => 'cc55','Span' => 3)
//*********************************************
array('Insn' => '//'),
//
	
	array('Label' => 'Ca'),
	array('Column' => 'kk112',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp112'),
	 array('Column' => 'aa112','Label' => '','Span' => 1),
array('Insn' => '//'),
//

 
	
	array('Label' => 'Urinalysisacid'),
	array('Column' => 'kk113',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp113'),
	 array('Column' => 'aa113','Label' => '','Span' => 1),
//*******************************************	
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Label' =>  'Kidney'),
array('Insn' => '//'),
 
array('Label' =>  'BUN'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Creatinine'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
//***************************************
array('Insn' => '//'),
//

 

array('Insn' => '//'),
array('Insn' => '//'),
array('Label' =>  'stool analysis'),
array('Insn' => '//'),
//
array('Label' =>  ' Facal occult bld'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),	
	array('Label' => 'parasite egg'),
	array('Column' => 'kk123',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp123'),
	 array('Column' => 'aa123','Label' => '','Span' => 1),

array('Insn' => '//'),
//
array('Label' =>  'Occult bld.react.'),
	array('Column' => 'h8', 'Span' => 1),

 
 
//******************************************	
	array('Insn' => '//'),


 array('Label' =>  'Kidney Function'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

 
array('Label' =>  'Uric Acid'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

array('Label' =>  'Gout'),
	array('Column' => 'k417', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
array('Insn' => '//'),

 
	array('Insn' => '//'),
 
array('Label' =>  'Thyroid Function'),
	array('Column' => 'k411', 'Span' => 2),
array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
 	
	array('Insn' => '//'),
array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
	array('Column' => 'aa46','Label' => '','Span' => 1),
//**********************************
array('Insn' => '//'),
//
	
	array('Label' => 'T3'),
	array('Column' => 'kk118',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp118'),
	 array('Column' => 'aa118','Label' => '','Span' => 1),
//********************************
 
 
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),
 
	array('Insn' => '//'),

	array('Insn' => '//'),
 
	array('Label' =>  'UGI X-Ray'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	array('Label' => ''),
	array('Column' => 'c504','Span' => 3),
	array('Insn' => '//'),
 

 
 
 
array('Label' =>  'EGD'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  'Colonoscopy'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),
 
 

//******************************************
 /*
array('Label' =>  '¿©Æ»¡¦°ß½½Æó»ØÄ²XÀþ½ê¸« UGI'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 5),
	
	array('Insn' => '//'),
 
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
*/

//************************************
//
//******************************************
 
array('Label' =>  'US Abd'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
	array('Column' => 'c510','Span' => 5),
	
	array('Insn' => '//'),
 
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
//************************************

//

	array('Insn' => '//'),

array('Label' =>  'Fundus oculi'),
	array('Column' => 'h4', 'Span' => 1),
	array('Insn' => '//'),
	
	
	array('Insn' => '//'),
 
 
array('Insn' => '//'),
//
	
	array('Label' => 'KEITH'),
	array('Column' => 'kk124',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp124'),
	 array('Column' => 'aa124','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'ScheieH'),
	array('Column' => 'kk125',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp125'),
	 array('Column' => 'aa125','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Scheies'),
	array('Column' => 'kk126',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp126'),
	 array('Column' => 'aa126','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'SCOTT'),
	array('Column' => 'kk127',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp127'),
	 array('Column' => 'aa127','Label' => '','Span' => 1),

//***********************************

 array('Insn' => '//'),

array('Label' =>  'Gynecology'),
	
	array('Insn' => '//'),
	
	
	array('Insn' => '//'),
 array('Insn' => '//'),

array('Label' =>  'Breast'),
	array('Column' => 'k412', 'Span' => 2),
array('Insn' => '//'),

 
 
	array('Insn' => '//'),

	array('Label' =>  'Find'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Breast X-ray'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
 
 
  array('Label' =>  'Uerine'),
	array('Column' => 'k413', 'Span' => 2),
array('Insn' => '//'),
 
 
 
 array('Label' =>  'Uterine cancer'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  'int.Exam'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => ''),
	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  'Tumor marker etc '),
	
array('Insn' => '//'),
 


array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
	array('Column' => 'aa48','Label' => '','Span' => 1),
 
 //
array('Insn' => '//'),
//
	
	array('Label' => 'CEA'),
	array('Column' => 'kk115',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp115'),
	 array('Column' => 'aa115','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'alphafeto'),
	array('Column' => 'kk116',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp116'),
	 array('Column' => 'aa116','Label' => '','Span' => 1),
array('Insn' => '//'),
//
array('Label' =>  'PSA'),
	array('Column' => 'kk95','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
	array('Insn' => '//'),
	
 
 
array('Insn' => '//'),
//
	
	array('Label' => 'Tumore marker'),
	array('Column' => 'h10',
	      
	       'Span' => 1),
//
//0210-2014	 
array('Insn' => '//'),
//
	
	array('Label' => 'HIV'),
	array('Column' => 'h9',
	      
	       'Span' => 1),

	 


 

array('Insn' => '//'),
 
array('Label' =>  'Rectal Exam'),
	array('Column' => 'k418', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc418','Span' => 5),
	
array('Insn' => '//'),

 
	array('Insn' => '//'),




 
//*********************************
array('Insn' => '//'),
//
	
	array('Label' => '£±H Blood Sedime'),
	array('Column' => 'kk114',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp114'),
	 array('Column' => 'aa114','Label' => '','Span' => 1),
array('Insn' => '//'),

//************************************

array('Label' =>  'pylori'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	
	
	array('Insn' => '//'),


array('Insn' => '//'),

array('Insn' => '//'),
//
	
	array('Label' => 'inflam.react'),
	array('Column' => 'h11',
	      
	       'Span' => 1),
	 
 
 


//k560 for mri.mra

array('Insn' => '//'),
array('Label' =>  'MRI/MRA'),
	 
	array('Column' => 'k560', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p560'),
	
	array('Label' => ''),
	array('Column' => 'c560','Span' => 3),
array('Insn' => '//'),

//k561 for kotumitudo
array('Insn' => '//'),
array('Label' =>  'Bone density'),
	 
	array('Column' => 'k561', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p561'),
	
	array('Label' => ''),
	array('Column' => 'c561','Span' => 3),
array('Insn' => '//'),
//
//add 0120-2014
array('Insn' => '//'), 
array('Label' =>  'ANA'),
	 
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),

array('Column' => 'aa1003','Label' => '','Span' => 1),
	
//add 0120-2014	 
	
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
	array('Label' => 'Summary'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => 'Note'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),

 array('Label' => 'Life style'),
	array('Column' => 'cc0', 'Span' =>5),
	array('Insn' => '//'),
//
array('Label' => 'Symptom'),
	array('Column' => 'ss0', 'Span' =>5),
	array('Insn' => '//'),
array('Label' => 'History'),
	array('Column' => 'ss1', 'Span' =>5),
	array('Insn' => '//'),

);





 
 //EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
//*************************************************************
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 
 //0320-2014

//****************************************************************

$__otatest2_order_cfg['E_RANDOM_LAYOUT'] = array(
 
	array('Label' => 'DOS'),
 
 array('Column' => 'order_date','Span' => 1),
	array('Label' => ''),
	array('Column' => 'preorderdate','Span'=>1),
array('Column' => 'shiji',
					'Label' => 'DR',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku2()

				       ), 
 array('Insn' => '//'),
array('Column' => 'category',
	      'Label' => 'Package',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest2_category_enum),
	      'Option' => array('size'=>10),
 
	      ),
//
array('Column' => 'addition',
	      'Label' => 'Normal/Deleted',
	      'Draw' => 'enum',
	      'Enum' => $__otatest2_addition_enum,
	       
 
	      ),

//
 array('Label' =>  ''),
array('Column' => 'h12',
	      'Label' => 'Report Type',
	      'Draw' => 'enum',
	      'Enum' =>$__otatest2_report_enum,
	      'Option' => array('size'=>10),
	      ),	

array('Insn' => '//'),
array('Label' =>  '----------------'),
	
	array('Insn' => '//'),
	array('Label' =>  'Height'),
	array('Column' => 'k100', 'Option' => array('size' => 5)),
	 
	array('Label' => ''),
	array('Column' => 'p100','draw'=>'static'),
//array('Column' => 'aa51','Label' => '','Span' => 1),
array('Insn' => '//'),	
	array('Label' => 'Weight'),
	array('Column' => 'k101','Option' => array('size' => 5)),
array('Label' => ''),
	array('Column' => 'p101','draw'=>'static'),
//array('Column' => 'aa52','Label' => '','Span' => 1),
 /*
array('Insn' => '//'),
	array('Label' => 'Obesity index'),
	array('Column' => 'k1006','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1006'),


	array('Column' => 'aa1006','Label' => '','Span' => 1),
	
*/
 
array('Insn' => '//'),
	array('Label' => 'BMI'),
	array('Column' => 'k1007','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p1007'),
//array('Column' => 'aa1007','Label' => '','Span' => 1),
array('Label' => ''),
//	array('Column' => 'h1'),
array('Insn' => '//'),
	array('Label' => 'Adb Girth'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105','draw'=>'static'),
//array('Column' => 'aa53','Label' => '','Span' => 1),
 

array('Insn' => '//'),
 

array('Label' =>  'Physical Exam'),
	array('Column' => 'k401', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc401','Span' => 5),



//*********************************************
array('Insn' => '//'),
 
	array('Label' =>  'Cardiac'),
	 
array('Insn' => '//'),
 
array('Label' => ''),	
 	
	array('Insn' => '//'),
	array('Label' =>  'Blood Press(Up)'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1,'draw'=>'static'),
//	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => 'Blood Press(Down)'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1,'draw'=>'static'),
//	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => ' Blood Press'),
	array('Column' => 'h5', 'Span' => 1),
 
 	array('Insn' => '//'),
 	array('Label' => 'Pulse'),
 	array('Column' => 'k302', 'Span' => 1),
 	array('Label' => ''),
 	array('Column' => 'p302', 'Span' => 1,'draw'=>'static'),
// 	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),


 
	array('Label' =>  ' Chest(heart)X-Ray '),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501','draw'=>'static'),
	
//	array('Label' => ''),
//	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
 

	array('Label' =>  'EKG'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502','draw'=>'static'),
	
	array('Label' => ''),
//	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
//	array('Label' =>  'ÂçÆ°Ì®Ä¶²»ÇÈ'),
//	array('Column' => 'k503', 'Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'p503'),
	
//	array('Label' => ''),
//	array('Column' => 'c503','Span' => 3),
 

 
array('Insn' => '//'),
	array('Label' =>  'Echocardiogram2'),
	array('Column' => 'k1002', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1002','draw'=>'static'),
	
	array('Label' => ''),
//	array('Column' => 'c1002','Span' => 3),
	
	 
 
 
 	
array('Insn' => '//'),
 
	array('Label' =>  'Cardiac Grade'),
	array('Column' => 'k403', 'Span' => 2),
//	array('Insn' => '  ', 'Span' => 1),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc403','Span' => 5),
array('Insn' => '//'),
 
	

 
 
	array('Insn' => '//'),
 
	 
	
	array('Label' =>  ' Forced Vital Capacity'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
//	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Label' => ''),
//	array('Column' => 'h2'),
	array('Insn' => '//'),
 //
 	array('Insn' => '//'),
 array('Label' =>  'Ideal FVC'),
	array('Column' => 'k103', 'Span' => 1),
	array('Label' => ''),
 
	array('Insn' => '//'),
 
 
 


	array('Insn' => '//'),
 array('Label' =>  '%FVC'),
	array('Column' => 'k104', 'Span' => 1),
	array('Label' => ''),
 
	array('Insn' => '//'),
 

 
 	
	array('Label' =>  'FEV1%'),
	array('Column' => 'k106', 'Span' => 1),
	array('Label' => ''),
 	array('Column' => 'p106', 'Span' => 1),
//	array('Column' => 'aa106','Label' => '','Span' => 1),
 
array('Insn' => '//'),
 
	
	array('Label' =>  'intentFVC'),
	array('Column' => 'kk600', 'Span' => 1),
	array('Label' => ''),
  	array('Column' => 'pp600', 'Span' => 1),

 

	 
	array('Insn' => '//'),
 

 


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  'Chest X-Ray'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => ''),
//	array('Column' => 'c500','Span' => 3),
	
//*********************************	 

 
array('Insn' => '//'),
array('Label' =>  ' Pulmonary Function'),
	array('Column' => 'k402', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc402','Span' => 5),	
	
	array('Insn' => '//'),

 
 
array('Label' =>  'Vision'), 

array('Insn' => '//'),
//
 
array('Label' =>  'Corrected(R)'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
//array('Column' => 'aa80','Label' => '','Span' => 1), 

array('Insn' => '//'),
array('Label' =>  'Corrected(L)'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
//array('Column' => 'aa81','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
//0501-2013
array('Insn' => '//'),
array('Label' =>  'Uncorr(R)'),
	array('Column' => 'k1000', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1000'),
//array('Column' => 'aa1000','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  'Uncorr(L)'),
	array('Column' => 'k1001', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1001'),
//array('Column' => 'aa1001','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  ' Ophthalmology'),
	array('Column' => 'k414', 'Span' => 2),
array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc414','Span' => 5),
	
	array('Insn' => '//'),

	array('Insn' => '//'),
 



	array('Insn' => '//'),
   	array('Label' =>  'Audibility'),
	array('Insn' => '//'),

	array('Insn' => '//'),
	 
array('Label' =>  '500Hz(R)'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
//array('Column' => 'aa82','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '500Hz(L)'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
//array('Column' => 'aa83','Label' => '','Span' => 1), 	
array('Insn' => '//'),
array('Label' =>  '1000Hz(R)'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
//array('Column' => 'aa84','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '1000Hz(L)'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
//array('Column' => 'aa85','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '2000Hz(R)'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
//array('Column' => 'aa86','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '2000Hz(L)'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
//array('Column' => 'aa87','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
array('Label' =>  '4000Hz(R)'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
//array('Column' => 'aa88','Label' => '','Span' => 1), 
array('Insn' => '//'),
array('Label' =>  '4000Hz(L)'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
//array('Column' => 'aa89','Label' => '','Span' => 1), 
	
array('Insn' => '//'),
 
 
	
 
array('Insn' => '//'),
array('Label' =>  'Audiometry'),
	array('Column' => 'k410', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc410','Span' => 5),
	
	array('Insn' => '//'),

array('Insn' => '//'),
   	array('Label' =>  'Hematologic Study'),
	array('Insn' => '//'),
 array('Insn' => '//'),
array('Label' =>  'Anemia'),
	array('Column' => 'h3', 'Span' => 2),
	array('Insn' => '//'),

	array('Label' =>  'ABO Blood Type'),
	array('Column' => 'kk70', 'Span' => 1),
	array('Insn' => '//'),	

	array('Label' =>  'WBC'),
	array('Column' => 'kk71', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp71'),
//	array('Column' => 'aa38','Label' => '','Span' => 1),
 	
	 
	array('Insn' => '//'),
	array('Label' =>  'RBC'),
	array('Column' => 'kk72',
	      
	       'Span' =>1),

	array('Label' => ''),
	array('Column' => 'pp72'),
//	array('Column' => 'aa39','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'Hemogl Cnt'),
	array('Column' => 'kk73','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp73'),
//	array('Column' => 'aa40','Label' => '','Span' => 1),
 
	
	 
	array('Insn' => '//'),
	array('Label' => 'Hematocrit'),
	array('Column' => 'kk74',
	      
	       'Span' => 1),

	array('Label' => ''),
	array('Column' => 'pp74'),
//	array('Column' => 'aa41','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc74','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' => 'Rh'),
	array('Column' => 'kk75','Span' => 1),
	
	array('Insn' => '//'),
	
//
	array('Label' =>  'MCV'),
	array('Column' => 'kk76', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp76'),
//	 array('Column' => 'aa42','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc76','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' =>  'MCH'),
	array('Column' => 'kk77',
	      
	       'Span' =>1),
	array('Label' => ''),
	array('Column' => 'pp77'),
//	array('Column' => 'aa43','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc77','Span' => 3),
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'MCHC'),
	array('Column' => 'kk78',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp78'),
//	array('Column' => 'aa44','Label' => '','Span' => 1), 
//	array('Label' => ''),
//	array('Column' => 'cc78','Span' => 3),
	
	 
	array('Insn' => '//'),
//
	
	array('Label' => 'Platelet'),
	array('Column' => 'kk79',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp79'),
//	 array('Column' => 'aa45','Label' => '','Span' => 1),
 
array('Insn' => '//'),
//
	
	array('Label' => 'Nuetr'),
	array('Column' => 'kk100',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp100'),
//	 array('Column' => 'aa100','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Lymph'),
	array('Column' => 'kk101',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp101'),
//	 array('Column' => 'aa101','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Mono'),
	array('Column' => 'kk102',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp102'),
//	 array('Column' => 'aa102','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Eosino'),
	array('Column' => 'kk103',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp103'),
//	 array('Column' => 'aa103','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Baso'),
	array('Column' => 'kk104',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp104'),
//	 array('Column' => 'aa104','Label' => '','Span' => 1),
	 
	array('Insn' => '//'), 

array('Label' =>  'Hematologic Study'),
	array('Column' => 'k409', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc409','Span' => 5),
	
	array('Insn' => '//'),
 array('Insn' => '//'),
   	array('Label' =>  'Blood Exam'),
	array('Insn' => '//'),
 
array('Insn' => '//'),
//
	
	array('Label' => 'HAV'),
	array('Column' => 'kk105',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp105'),
//	 array('Column' => 'aa105','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'CRP'),
	array('Column' => 'kk106',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp106'),
//	 array('Column' => 'aa106','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'RA'),
	array('Column' => 'kk107',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp107'),
//	 array('Column' => 'aa107','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'RPR'),
	array('Column' => 'kk108',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp108'),
//	 array('Column' => 'aa108','Label' => '','Span' => 1),
array('Insn' => '//'),
//


	array('Insn' => '//'),
array('Label' =>  '£È£Â£óAntigen'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
//	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '£È£Â£ó Antibody'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
//	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
//	
	array('Label' => 'HCV Antibody'),
	array('Column' => 'kk109',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp109'),
//	 array('Column' => 'aa109','Label' => '','Span' => 1),
//******************************************************
//0121-2014
array('Insn' => '//'),
array('Label' => 'Glucose study'),
//array('Column' =>'h6'),	 
	
	array('Insn' => '//'),
 

 
 


array('Label' =>  'Glucose'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
//	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ' UrinalysisSugar'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
//	array('Column' => 'aa21','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc51','Span' => 3),
	
	array('Insn' => '//'),



array('Label' =>  'HbA1C'),
	array('Column' => 'kk52', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp52'),
//	array('Column' => 'aa22','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc52','Span' => 3),
	array('Insn' => '//'),
 
array('Label' =>  'Glucose Metabolism'),
	array('Column' => 'k407', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc407','Span' => 5),
	
	array('Insn' => '//'),
 


 
 //
array('Insn' => '//'),
array('Label' => 'Lipid Metabolism'),
	 
	
	array('Insn' => '//'),
 

//1020-2012
array('Label' =>  'Total Cholesterol'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
//	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => ''),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Triglycerides'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
//	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc13','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'HDL'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
//	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
 
	
	array('Insn' => '//'),
array('Label' =>  'LDL'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
//	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc14','Span' => 3),
//0510-2013
array('Insn' => '//'),
 	
	array('Insn' => '//'),

array('Label' =>  ' Lipid Metabolism'),
	array('Column' => 'k404', 'Span' => 2),
	
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc404','Span' =>5),
	array('Insn' => '//'),
//
array('Insn' => '//'),
array('Label' =>  'Pancreatic'),
array('Insn' => '//'),
array('Label' =>  'Amylase'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
//	array('Column' => 'aa7','Label' => '','Span' => 1),

 	
	array('Insn' => '//'),
 
array('Label' =>  'Pancreatic Grade'),
	array('Column' => 'k405', 'Span' => 2),
	array('Insn' => '//'),
	array('Label' => ''),
	array('Column' => 'cc405','Span' => 5),
	
	array('Insn' => '//'),
 
 array('Insn' => '//'),
array('Label' =>  'Liver'),
array('Insn' => '//'),
array('Label' =>  'T.Protein'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
//	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Albumin'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
//	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G'),
	array('Column' => 'kk32', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp32'),
//	array('Column' => 'aa10','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc32','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'GOT'),
	array('Column' => 'kk33', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp33'),
//	array('Column' => 'aa11','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc33','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'GPT'),
	array('Column' => 'kk34', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp34'),
//	array('Column' => 'aa12','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc34','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¦Ã-GTP'),
	array('Column' => 'kk35', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp35'),
//	array('Column' => 'aa13','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc35','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDH'),
	array('Column' => 'kk36', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp36'),
//	array('Column' => 'aa14','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc36','Span' => 3),
	
	array('Insn' => '//'),
//
//
array('Label' =>  'T.Bilirubin'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
//	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),


 //
	
	array('Insn' => '//'),
array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
//	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc38','Span' => 3),
//************************************************
array('Insn' => '//'),
//
	
	array('Label' => 'LAP'),
	array('Column' => 'kk110',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp110'),
//	 array('Column' => 'aa110','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Serum iron'),
	array('Column' => 'kk111',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp111'),
//	 array('Column' => 'aa111','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Cholinest'),
	array('Column' => 'kk117',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp117'),
//	 array('Column' => 'aa117','Label' => '','Span' => 1),
//**********************************************
	
 
	
	array('Insn' => '//'),
 
 

 
array('Label' =>  'Liver function'),
	array('Column' => 'k406', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc406','Span' => 5),
	
	array('Insn' => '//'),
//
 
array('Insn' => '//'),
array('Label' =>  'Urinalysis'),
array('Column' => 'h7'),
array('Insn' => '//'),

array('Label' =>  ' U.Protein'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
//	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc64','Span' => 3),

	
	array('Insn' => '//'),
array('Label' =>  'U.Sugar'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
//	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'Ketone'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
//	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Occult Blood'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
//	array('Column' => 'aa26','Label' => '','Span' => 1),
 
 	 
 
	
	array('Insn' => '//'),
array('Label' =>  'U.RBC'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
//	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ' U.WBC'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
//	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc61','Span' => 3),
	array('Insn' => '//'),
//
	
	array('Label' => 'Ratio'),
	array('Column' => 'kk119',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp119'),
//	 array('Column' => 'aa119','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'PH'),
	array('Column' => 'kk120',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp120'),
//	 array('Column' => 'aa120','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Urobili'),
	array('Column' => 'kk121',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp121'),
//	 array('Column' => 'aa121','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'squam. epith'),
	array('Column' => 'kk122',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp122'),
//	 array('Column' => 'aa122','Label' => '','Span' => 1),

array('Insn' => '//'),
	array('Insn' => '//'),

array('Label' =>  'Bacteria'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
//	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
//
array('Insn' => '//'),
array('Label' =>  'Metabolic'),
array('Column'=>'h6'),
array('Insn' => '//'),


 
 
//
array('Label' =>  'Na'),
	array('Column' => 'kk53', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp53'),
//	array('Column' => 'aa27','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'K'),
	array('Column' => 'kk54', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp54'),
//	array('Column' => 'aa28','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc54','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Cl'),
	array('Column' => 'kk55', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp55'),
//	array('Column' => 'aa29','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc55','Span' => 3)
//*********************************************
array('Insn' => '//'),
//
	
	array('Label' => 'Ca'),
	array('Column' => 'kk112',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp112'),
//	 array('Column' => 'aa112','Label' => '','Span' => 1),
array('Insn' => '//'),
//

 
	
	array('Label' => 'Urinalysisacid'),
	array('Column' => 'kk113',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp113'),
//	 array('Column' => 'aa113','Label' => '','Span' => 1),
//*******************************************	
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Label' =>  'Kidney'),
array('Insn' => '//'),
 
array('Label' =>  'BUN'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
//	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Creatinine'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
//	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => ''),
//	array('Column' => 'cc58','Span' => 3),
//***************************************
array('Insn' => '//'),
//

 

array('Insn' => '//'),
array('Insn' => '//'),
array('Label' =>  'stool analysis'),
array('Insn' => '//'),
//
array('Label' =>  ' Facal occult bld'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
//	array('Column' => 'aa6','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),	
	array('Label' => 'parasite egg'),
	array('Column' => 'kk123',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp123'),
//	 array('Column' => 'aa123','Label' => '','Span' => 1),

array('Insn' => '//'),
//
array('Label' =>  'Occult bld.react.'),
	array('Column' => 'h8', 'Span' => 1),

 
 
//******************************************	
	array('Insn' => '//'),


 array('Label' =>  'Kidney Function'),
	array('Column' => 'k408', 'Span' => 2),
	array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc408','Span' => 5),
	
	array('Insn' => '//'),

 
array('Label' =>  'Uric Acid'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
//	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),

array('Label' =>  'Gout'),
	array('Column' => 'k417', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
	array('Column' => 'cc417','Span' => 5),
array('Insn' => '//'),

 
	array('Insn' => '//'),
 
array('Label' =>  'Thyroid Function'),
	array('Column' => 'k411', 'Span' => 2),
array('Insn' => '//'),
 	array('Label' => ''),
 	array('Column' => 'cc411','Span' => 5),
 	
	array('Insn' => '//'),
array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
//	array('Column' => 'aa46','Label' => '','Span' => 1),
//**********************************
array('Insn' => '//'),
//
	
	array('Label' => 'T3'),
	array('Column' => 'kk118',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp118'),
//	 array('Column' => 'aa118','Label' => '','Span' => 1),
//********************************
 
 
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
//	array('Column' => 'aa47','Label' => '','Span' => 1),
 
	array('Insn' => '//'),

	array('Insn' => '//'),
 
	array('Label' =>  'UGI X-Ray'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	array('Label' => ''),
//	array('Column' => 'c504','Span' => 3),
	array('Insn' => '//'),
 

 
 
 
array('Label' =>  'EGD'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => ''),
//	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  'Colonoscopy'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => ''),
//	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),
 
 

//******************************************
 /*
array('Label' =>  '¿©Æ»¡¦°ß½½Æó»ØÄ²XÀþ½ê¸« UGI'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => ''),
	array('Column' => 'c509','Span' => 5),
	
	array('Insn' => '//'),
 
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
*/

//************************************
//
//******************************************
 
array('Label' =>  'US Abd'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => ''),
//	array('Column' => 'c510','Span' => 5),
	
	array('Insn' => '//'),
 
	
	array('Insn' => '//'),
 
	array('Insn' => '//'),
//************************************

//

	array('Insn' => '//'),

array('Label' =>  'Fundus oculi'),
	array('Column' => 'h4', 'Span' => 1),
	array('Insn' => '//'),
	
	
	array('Insn' => '//'),
 
 
array('Insn' => '//'),
//
	
	array('Label' => 'KEITH'),
	array('Column' => 'kk124',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp124'),
//	 array('Column' => 'aa124','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'ScheieH'),
	array('Column' => 'kk125',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp125'),
//	 array('Column' => 'aa125','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'Scheies'),
	array('Column' => 'kk126',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp126'),
//	 array('Column' => 'aa126','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'SCOTT'),
	array('Column' => 'kk127',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp127'),
//	 array('Column' => 'aa127','Label' => '','Span' => 1),

//***********************************

 array('Insn' => '//'),

array('Label' =>  'Gynecology'),
	
	array('Insn' => '//'),
	
	
	array('Insn' => '//'),
 array('Insn' => '//'),

array('Label' =>  'Breast'),
	array('Column' => 'k412', 'Span' => 2),
array('Insn' => '//'),

 
 
	array('Insn' => '//'),

	array('Label' =>  'Find'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => ''),
//	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Breast X-ray'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => ''),
//	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
 
 
  array('Label' =>  'Uerine'),
	array('Column' => 'k413', 'Span' => 2),
array('Insn' => '//'),
 
 
 
 array('Label' =>  'Uterine cancer'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => ''),
//	array('Column' => 'c514','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  'int.Exam'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => ''),
//	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
 
array('Label' =>  'Tumor marker etc '),
	
array('Insn' => '//'),
 


array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
//	array('Column' => 'aa48','Label' => '','Span' => 1),
 
 //
array('Insn' => '//'),
//
	
	array('Label' => 'CEA'),
	array('Column' => 'kk115',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp115'),
//	 array('Column' => 'aa115','Label' => '','Span' => 1),
array('Insn' => '//'),
//
	
	array('Label' => 'alphafeto'),
	array('Column' => 'kk116',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp116'),
//	 array('Column' => 'aa116','Label' => '','Span' => 1),
array('Insn' => '//'),
//
array('Label' =>  'PSA'),
	array('Column' => 'kk95','Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
//	array('Column' => 'aa49','Label' => '','Span' => 1),
	array('Insn' => '//'),
	
 
 
array('Insn' => '//'),
//
	
	array('Label' => 'Tumore marker'),
	array('Column' => 'h10',
	      
	       'Span' => 1),
//0210-2014	 
array('Insn' => '//'),
//
	
	array('Label' => 'HIV'),
	array('Column' => 'h9',
	      
	       'Span' => 1),


 

array('Insn' => '//'),
 
array('Label' =>  'Rectal Exam'),
	array('Column' => 'k418', 'Span' => 2),
array('Insn' => '//'),
array('Label' => ''),
//	array('Column' => 'cc418','Span' => 5),
	
array('Insn' => '//'),

 
	array('Insn' => '//'),




 
//*********************************
array('Insn' => '//'),
//
	
	array('Label' => '£±H Blood Sedime'),
	array('Column' => 'kk114',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp114'),
//	 array('Column' => 'aa114','Label' => '','Span' => 1),
array('Insn' => '//'),

//************************************

array('Label' =>  'pylori'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	
	
	array('Insn' => '//'),


array('Insn' => '//'),

array('Insn' => '//'),
//
	
	array('Label' => 'inflam.react'),
	array('Column' => 'h11',
	      
	       'Span' => 1),
	 
 
 


//k560 for mri.mra

array('Insn' => '//'),
array('Label' =>  'MRI/MRA'),
	 
	array('Column' => 'k560', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p560'),
	
	array('Label' => ''),
//	array('Column' => 'c560','Span' => 3),
array('Insn' => '//'),

//k561 for kotumitudo
array('Insn' => '//'),
array('Label' =>  'Bone density'),
	 
	array('Column' => 'k561', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p561'),
	
	array('Label' => ''),
//	array('Column' => 'c561','Span' => 3),
array('Insn' => '//'),
//
//add 0120-2014
array('Insn' => '//'), 
array('Label' =>  'ANA'),
	 
	array('Column' => 'k1003', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p1003'),

//array('Column' => 'aa1003','Label' => '','Span' => 1),
	
//add 0120-2014	 
	
	array('Insn' => '//'),
//yobi etc

array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	
	array('Label' => ''),
//	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => ''),
//	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => ''),
//	array('Column' => 'cc532','Span' => 3),
	

	
 

array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => 'Summary'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => 'Note'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),

 array('Label' => 'Life style'),
	array('Column' => 'cc0', 'Span' =>5),
	array('Insn' => '//'),

//
array('Label' => 'Symptom'),
	array('Column' => 'ss0', 'Span' =>5),
	array('Insn' => '//'),
array('Label' => 'History'),
	array('Column' => 'ss1', 'Span' =>5),
	array('Insn' => '//'),

);






//*************************************************************
//END EDIT DEF





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


if ($data['k101']) {
 $data['k1007'] = sprintf("%.2f",$data['k101']/($data['k100']/100*$data['k100']/100));
$riso=$data['k100']/100*$data['k100']/100*22;
$himando=($data['k101'] - $riso);
//print $himando 1121-2013
// $data['k1006'] = sprintf("%.2f",$himando/$riso*100);
}

 	if ($data['k200']) {
 
 //		$data['k106'] = sprintf("%.2f",$data['k203']/$data['k200']*100);
		$pat = get_patient($data['patient'],false);
		if ($pat['À­ÊÌ'] == 'M'){
 
 
//		$data['k103']= sprintf("%.2f",(27.63-0.112*mx_calc_age($pat["À¸Ç¯·îÆü"]))*$data['k100']);
 


		}
		else {
 
		// print "DOB=".$pat['À¸Ç¯·îÆü'];
		// print "age=".mx_calc_age($pat['À¸Ç¯·îÆü']);
//0210-2014 changed
		//$data['k103']= sprintf("%.2f",(21.78-0.101*mx_calc_age($pat["À¸Ç¯·îÆü"]))*$data['k100']);
		}
// if non 0
		//$data['k104'] = sprintf("%.2f",$data['k200']/$data['k103']*100);
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
//0325-2014 added for sort
	function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'orde_rdate' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}

//
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
 
/*
function print_sod() {
    draw_ndk($this->id, 0);
  }
 
*/


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
//debug 04072014
class doctor_ndk_application extends per_patient_application {

 function setup() {
    if (!array_key_exists('CSV_HACK', $_REQUEST))
      return per_patient_application::setup();

    // HORRIBLE HACK HERE
    $this->setup_patient();
    $this->sod = $this->object_display('sod-', &$this);
    switch ($_REQUEST['CSV_HACK']) {
    case 1:
	    $this->emit_CSV_from_sod();
	    break;
    case 2:
	    $this->emit_printer_page();
	    break;
    }
    return 1;
  } 
  function emit_printer_page() {
	  draw_ndk($this->id);
  }
}
//

?>
