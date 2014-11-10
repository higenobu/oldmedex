<?php // -*- mode: php; coding: euc-japan -*-
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
//add 0320-2012



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
//only for osato-clinic pdf print
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf45.php';

function __lib_u_doctor_otatest_order_anno(&$data)
{
 	if ($data['kk1'] && $data['kk2']) {
		
 		$b=$data['kk1'] + $data['kk2'];
 				$data['kk4'] = sprintf("%.2f", $b);
 		
		
			}


	

/*
//
if ($data['order_date'] && $data['patient']){
		$orderdate = mx_db_sql_quote($data['order_date']);
		$db = mx_db_connect();
		$stmt = <<<SQL
			SELECT "patient", order_date, kk0,kk1,k2,kk3,kk4
			
			FROM otatest_order
			where "patient"=25 
			order by order_date desc
SQL;

		if ($d = mx_db_fetch_single($db, $stmt)) {
			
				$data['kk4']=$d['kk1'];
			
		}

		

	
	}
*/


}

//

function _lib_u_ota_kiroku4() {
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

$__otatest_category_enum = array
(
	'new','change','stop'
);

$__otatest_addition_enum = array
(
	'','special','standard',
);




$__otatest_exam_enum = array
(
	'N/A','normal','findings','retest','unknown',
);
$__otatest_hantei_enum = array
(
	'-','A','B','C','D',
);
//1020-2012
$__otatest_n_enum = array
(
'0'=>'-','1'=>'Ab',
);
$__otatest_plus_enum = array
(
	'N/A','(-)','(+)', 
);
$__otatest_abo_enum = array
(
   'N/A','A','B','O','AB',
);

$__otatest_rh_enum = array
(
	'N/A','Rh+','Rh-', 
);
//04-20-2013
// if you need to list, set list=1
$__otatest_all_cols = array(
/*
	array('Column' => 'recompute',
	      'Label' => 'ºÆ·×»»',
	      'Draw' => 'submit',
	      'Option' => array('nostore' => 1, 'nodisp' => 1)),
*/

	array('Column' => 'patient',
	      'Draw' => NULL,
	      'Option' => array('noedit' => 1, 'nodisp' => 1),
	      ),
/*
	array('Column' => 'recorded_on',
	      'Label' => 'µ­Ï¿Æü»þ',
	      'Draw' => 'static',
	      ),
*/
/*
array('Column' => 'CreatedBy',
	      'Label' => 'Recorded',
	      'Draw' => 'static',
	      ),
*/
//04-20-2013
array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku4(),
 'Option' => array(  'list' => 1,'size'=>10),

				       ),

	array('Column' => 'order_date',
	      'Label' => 'TestDate',
	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1,'size'=>10),
	      ),
 
/* array('Column' => 'plandate',
 	      'Label' => '¸¡ººÍ½ÄêÆü',
 	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1,'size'=>10),
	      ),
*/

	array('Column' => 'preorderdate',
	      'Label' => 'PreDate',
	      'Draw' => 'date',

	      'Option' => array('validate' => 'date', 'list' => 1,'size'=>10),
	      ),
	array('Column' => 'category',
	      'Label' => 'category',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_category_enum),
	      'Option' => array('size'=>10),
	      ),
	array('Column' => 'addition',
	      'Label' => 'special',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_addition_enum),
		'Option' => array('list' => 1,'size'=>10),
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
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p103',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'k104',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p104',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),

	array('Column' => 'k105',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'p105',
	       'Draw' => 'text','Option' =>array('size' => 10), 
	      ),
	array('Column' => 'k106',
	       'Draw' => 'text','Option' =>array('size' => 10), 
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

//
//
	array('Column' => 'k500',
	      'Label' => '¶»Éô£ØÀþ',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p500',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//10-20-2012
	array('Column' => 'c500',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' =>20,'rows'=>3),
	      ),
	array('Column' => 'k501',
	      'Label' => '¶»Éô(¿´Â¡)£ØÀþ',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p501',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c501',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
	array('Column' => 'k502',
	      'Label' => '2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p502',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c502',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//

array('Column' => 'k503',
	      'Label' => '3',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p503',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c503',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k504',
	      'Label' => '4',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p504',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c504',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k505',
	      'Label' => '5',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p505',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c505',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k506',
	      'Label' => '6',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p506',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c506',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k507',
	      'Label' => '7',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p507',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c507',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k508',
	      'Label' => '8',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p508',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c508',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k509',
	      'Label' => '9',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p509',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c509',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k510',
	      'Label' => '10',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p510',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c510',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k511',
	      'Label' => '11',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p511',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c511',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k512',
	      'Label' => '12',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p512',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c512',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k513',
	      'Label' => '13',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p513',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c513',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k514',
	      'Label' => '14',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p514',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c514',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k515',
	      'Label' => '15',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p515',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c515',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k516',
	      'Label' => '16',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p516',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c516',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k517',
	      'Label' => '17',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p517',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c517',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k518',
	      'Label' => '18',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p518',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c518',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
//
array('Column' => 'k519',
	      'Label' => 'chouonpa',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p519',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//
array('Column' => 'c519',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'k520',
	      'Label' => '20',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'p520',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'c520',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
// k500-k521 add Kk530--KK540  0315-2013
array('Column' => 'kk530',
	      'Label' => '20',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'pp530',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'cc530',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'kk540',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'kk531',
	      'Label' => '20',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'pp531',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
array('Column' => 'cc531',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20,'rows'=>3),
	      ),
array('Column' => 'kk541',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//
array('Column' => 'kk532',
	      'Label' => '20',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_exam_enum),
	      
	      ),
	array('Column' => 'pp532',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 
array('Column' => 'cc532',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk12',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp12',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc12',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk13',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp13',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc13',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk14',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp14',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc14',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'kk15',
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_plus_enum),
	      
	      ),

	array('Column' => 'pp15',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
 

	 array('Column' => 'cc15',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   
//1020-2012
array('Column' => 'cc16',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'cc17',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'cc18',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'cc19',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk22',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp22',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc22',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk20',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp20',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc20',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   
	array('Column' => 'kk31',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp31',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc31',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk32',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp32',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc32',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk33',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp33',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc33',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk34',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp34',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc34',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk35',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp35',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc35',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),

	array('Column' => 'kk36',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp36',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc36',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk37',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp37',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc37',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk38',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp38',
	      'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc38',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
//
 //04-20-2013
array('Column' => 'kk39',
/*
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_plus_enum),
*/
'Draw' => 'text','Option' => array('size' => 10),	      
	      ),
	array('Column' => 'pp39',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc39',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
/*0315-2013
array('Column' => 'kk40',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
*/

array('Column' => 'kk40',
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_plus_enum),
	      
	      ),
	array('Column' => 'pp40',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc40',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   
	 array('Column' => 'kk41',
/*
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_plus_enum),
*/
'Draw' => 'text','Option' => array('size' => 10),	      
	      ),
	array('Column' => 'pp41',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc41',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk42',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp42',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc42',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
//04-20-2013
	   array('Column' => 'kk52',
/*
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_plus_enum),
*/
'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp52',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc52',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk53',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp53',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc53',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk54',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp54',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc54',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk55',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp55',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc55',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),

array('Column' => 'kk56',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp56',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc56',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk57',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp57',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc57',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk58',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp58',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc58',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),

array('Column' => 'kk59',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp59',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc59',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   
	array('Column' => 'kk61',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp61',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc61',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk62',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp62',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc62',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk63',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp63',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc63',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk64',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp64',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc64',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk65',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp65',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc65',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),

array('Column' => 'kk66',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp66',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc66',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk67',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp67',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc67',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	  

//04-22-2012
array('Column' => 'kk70',
	     'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_abo_enum),
	      
	      ),
	array('Column' => 'pp70',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc70',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   
	array('Column' => 'kk71',
	      'Draw' => 'text','Option' => array('size' => 10),
	      
	      ),
	array('Column' => 'pp71',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc71',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),
	      ),

	array('Column' => 'kk75',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_rh_enum),
	      ),
/*
	array('Column' => 'pp75',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 
	    
	array('Column' => 'cc75',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
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
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   
	array('Column' => 'kk91',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp91',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc91',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk92',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp92',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc92',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk93',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp93',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc93',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk94',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp94',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc94',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	   array('Column' => 'kk95',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'pp95',
	     'Draw' => 'text','Option' => array('size' => 10),
	      ),
	 array('Column' => 'cc95',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),



//	    
	array('Column' => 'special_req',
	      'Label' => 'Total',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('NG01'),
			'cols' => 60,'list' => 1,'rows'=>5),
	      ),

	   
	array('Column' => 'notes',
	      'Label' => 'note',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('NG01'),
				'cols' =>60,'list' => 1,'rows'=>5),
	      ),
//
/*
array('Column' => 'k400',
	      'Label' => 'hantei0',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k401',
	      'Label' => 'hantei1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k402',
	      'Label' => 'hantei2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k403',
	      'Label' => 'hantei3',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k404',
	      'Label' => 'hantei4',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k405',
	      'Label' => 'hantei5',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k406',
	      'Label' => 'hantei6',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k407',
	      'Label' => 'hante7',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k408',
	      'Label' => 'hantei8',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k409',
	      'Label' => 'hantei9',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k410',
	      'Label' => 'hantei10',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k413',
	      'Label' => 'hantei13',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k414',
	      'Label' => 'hantei14',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k415',
	      'Label' => 'hantei15',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k411',
	      'Label' => 'hantei11',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k412',
	      'Label' => 'hante12',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
array('Column' => 'k416',
	      'Label' => 'hante16',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
*/


//04-20-2013 start
array('Column' => 'k400',
	      'Label' => 'hantei0',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc400',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k401',
	      'Label' => 'hantei1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),),
//0430-2013
array('Column' => 'cc401',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
	      
	    
array('Column' => 'k402',
	      'Label' => 'hantei2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc402',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k403',
	      'Label' => 'hantei3',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc403',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k404',
	      'Label' => 'hantei4',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'k405',
	      'Label' => 'hantei5',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc405',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k406',
	      'Label' => 'hantei6',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc406',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k407',
	      'Label' => 'hante7',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc407',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k408',
	      'Label' => 'hantei8',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc408',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k409',
	      'Label' => 'hantei9',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc409',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k410',
	      'Label' => 'hantei10',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc410',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),

array('Column' => 'k413',
	      'Label' => 'hantei13',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc413',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k414',
	      'Label' => 'hantei14',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc414',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k415',
	      'Label' => 'hantei15',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc415',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k411',
	      'Label' => 'hantei11',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc411',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k412',
	      'Label' => 'hante12',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc412',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k416',
	      'Label' => 'hante16',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc416',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),
array('Column' => 'k417',
	      'Label' => 'hante17',
	      'Draw' => 'enum',
	      'Enum' => mk_enum($__otatest_hantei_enum),
	      
	      ),
array('Column' => 'cc417',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('NG01'),
				'cols' => 20),),

//04-20-2013 end

	      
//	      ),
//1020-2012 normal abnormal 
array('Column' => 'aa1',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa2',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa3',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa4',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa5',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa6',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa7',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa8',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa9',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa10',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa11',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa12',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa13',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa14',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa15',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa16',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa17',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa18',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa19',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa20',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa21',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa22',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa23',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa24',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa25',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa26',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa27',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa28',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa29',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa30',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa31',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa32',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa33',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa34',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa35',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa36',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa37',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa38',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa39',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa40',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa41',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa42',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa43',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa44',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa45',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa46',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa47',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa48',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa49',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa50',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
// add 0315-2013

 array('Column' => 'aa51',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa52',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa53',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa54',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa55',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa56',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa57',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
 array('Column' => 'aa58',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),
  array('Column' => 'aa59',
	      'Label' => '',
	      'Draw' => 'enum',
	      'Enum' => $__otatest_n_enum,
	      
	      ),

 
);

$__otatest_order_cfg = array();

$__otatest_order_cfg['TABLE'] = 'otatest_order';
$__otatest_order_cfg['SEQUENCE'] = 'otatest_order_id_seq';
$__otatest_order_cfg['COLS'] = array();
$__otatest_order_cfg['ICOLS'] = array();
$__otatest_order_cfg['ECOLS'] = array();
$__otatest_order_cfg['LCOLS'] = array();
$__otatest_order_cfg['DCOLS'] = array();
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//display layout 0315-2013
//*************************************************************
//DISPLAY
$__otatest_order_cfg['D_RANDOM_LAYOUT'] = array(

	
 
	array('Label' => 'Testdate'),
	array('Column' => 'order_date'),
 
	array('Label' => 'Pre-date'),
	array('Column' => 'preorderdate'),
array('Column' => 'shiji',
					'Label' => 'doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku()

				       ),
  
	
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	 

	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Total Results'),
	array('Column' => 'k400', 'Span' => 1),
array('Label' => 'memo'),
	array('Column' => 'cc400','Span' => 3),
	
//0320	
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Physical Exam'),
	array('Column' => 'k401', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc401','Span' => 3),
	
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
array('Insn' => '//'),
	array('Label' => 'Abd. Girth:'),
	array('Column' => 'k105','Option' => array('size' => 5)),
	array('Label' => ''),
	array('Column' => 'p105'),
array('Column' => 'aa53','Label' => '','Span' => 1),
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Insn' => '  ', 'Span' => 1),
	array('Label' =>  'Cardiac Function'),
	array('Column' => 'k403', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc11','Span' => 3),
	array('Insn' => '//'),
	array('Label' =>  'Blood Pressure(up):'),
	array('Column' => 'k300', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p300', 'Span' => 1),
	array('Column' => 'aa56','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => 'Blood Pressure(low):'),
	array('Column' => 'k301', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p301', 'Span' => 1),
	array('Column' => 'aa57','Label' => '','Span' => 1),
	array('Insn' => '//'),
	array('Label' => 'Pulse'),
	array('Column' => 'k302', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p302', 'Span' => 1),
	array('Column' => 'aa58','Label' => '','Span' => 1),
	array('Insn' => '//'),

	array('Label' =>  'Chest Xray'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => 'memo'),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  'EKG:'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => 'memo'),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Echocardiogram:'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => 'memo'),
	array('Column' => 'c503','Span' => 3),
	
	 
//0320
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Insn' => '  ', 'Span' => 1),
	array('Label' =>  'Pulmonary Function'),
	array('Column' => 'k402', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc402','Span' => 3),	
//0328-2013 ??	
	array('Insn' => '//'),
	array('Label' =>  'Spirometry'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => 'memo'),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	array('Label' =>  'Forced Vital Capacity:'),
	array('Column' => 'k200', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p200', 'Span' => 1),
	array('Column' => 'aa54','Label' => '','Span' => 1),
	array('Insn' => '//'),
 
	array('Label' => 'FEV1:'),
	array('Column' => 'k203', 'Span' => 1),
 	array('Label' => ''),
	array('Column' => 'p203', 'Span' => 1),
	array('Column' => 'aa55','Label' => '','Span' => 1),
	array('Insn' => '//'),
 

//??


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  'Chest Xray'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => 'memo'),
	array('Column' => 'c500','Span' => 3),
	
	array('Insn' => '//'),

//
array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),


//ETC
//
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Eyesight'),
	array('Column' => 'k414', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc414','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'Right'),
	array('Column' => 'k80', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p80'),
array('Insn' => '//'),
array('Label' =>  'Left'),
	array('Column' => 'k81', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p81'),
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Audiometry'),
	array('Column' => 'k410', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc410','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '500Hz¡Êright¡Ë'),
	array('Column' => 'k82', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p82'),
array('Insn' => '//'),
array('Label' =>  '500Hz¡Êleft¡Ë'),
	array('Column' => 'k83', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p83'),
	
array('Insn' => '//'),
array('Label' =>  '1000Hz¡Êright¡Ë'),
	array('Column' => 'k84', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p84'),
array('Insn' => '//'),
array('Label' =>  '1000Hz¡Êleft¡Ë'),
	array('Column' => 'k85', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p85'),
	
array('Insn' => '//'),
array('Label' =>  '2000Hz¡Êright¡Ë'),
	array('Column' => 'k86', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p86'),
array('Insn' => '//'),
array('Label' =>  '2000Hz¡Êlrft¡Ë'),
	array('Column' => 'k87', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p87'),
	
array('Insn' => '//'),
array('Label' =>  '4000Hz¡Êright¡Ë'),
	array('Column' => 'k88', 'Span' => 1),
	array('Label' => ''),

	array('Column' => 'p88'),
array('Insn' => '//'),
array('Label' =>  '4000Hz¡Êleft¡Ë'),
	array('Column' => 'k89', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p89'),
	
array('Insn' => '//'),
array('Label' =>  '8000Hz¡Êright¡Ë'),
	array('Column' => 'k90', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p90'),
array('Insn' => '//'),
array('Label' =>  '8000Hz¡Êleft¡Ë'),
	array('Column' => 'k91', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p91'),
	
//
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Hematologic Study'),
	array('Column' => 'k409', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc409','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Blood Type'),
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
	
	array('Label' => 'Hemoglobin'),
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
//	array('Label' => 'memo'),
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
//	array('Label' => 'memo'),
//	array('Column' => 'cc76','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' =>  'MCH'),
	array('Column' => 'kk77',
	      
	       'Span' =>1),
	array('Label' => ''),
	array('Column' => 'pp77'),
	array('Column' => 'aa43','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc77','Span' => 3),
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'MCHC'),
	array('Column' => 'kk78',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp78'),
	array('Column' => 'aa44','Label' => '','Span' => 1), 
//	array('Label' => 'memo'),
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
	array('Insn' => ' '),
 
	array('Insn' => '//'),	 
	array('Insn' => '//'), 
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Glucose Metabolism'),
	array('Column' => 'k407', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc407','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Glucose:'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Urinary Glucose:'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
	array('Column' => 'aa21','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc51','Span' => 3),
	
	array('Insn' => '//'),



array('Label' =>  'HbA1C'),
	array('Column' => 'kk52', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp52'),
	array('Column' => 'aa22','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc52','Span' => 3),
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Lipid Metabolism'),
	array('Column' => 'k404', 'Span' => 1),
	
	array('Label' => 'memo'),
	array('Column' => 'cc404','Span' => 3),
	array('Insn' => '//'),
//1020-2012
array('Label' =>  'Total Cholesterol:'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => 'memo'),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'HDL'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc14','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Triglycerides:'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc13','Span' => 3),

array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Pancreatic Function'),
	array('Column' => 'k405', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc405','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Amylase:'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc21','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Abdominal Ultrasound:'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	
	array('Label' => 'memo'),
	array('Column' => 'c519','Span' => 3),
	
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Liver function'),
	array('Column' => 'k406', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc406','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'T.Protein:'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Albumin:'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G'),
	array('Column' => 'kk32', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp32'),
	array('Column' => 'aa10','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc32','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'GOT'),
	array('Column' => 'kk33', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp33'),
	array('Column' => 'aa11','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc33','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'GPT'),
	array('Column' => 'kk34', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp34'),
	array('Column' => 'aa12','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc34','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¦Ã-GTP'),
	array('Column' => 'kk35', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp35'),
	array('Column' => 'aa13','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc35','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDH'),
	array('Column' => 'kk36', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp36'),
	array('Column' => 'aa14','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc36','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'T.Bilirubin:'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc38','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Hepatitis B Antigen:'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Hepatitis B Antibody:'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Hepatitis C Antibody:'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Abdominal Ultrasound:'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => 'memo'),
	array('Column' => 'c520','Span' => 3),
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),

//
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Kidney Function'),
	array('Column' => 'k408', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc408','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'UrinalysisProtein:'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Sugar:'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'Ketone:'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Blood'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc67','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'RBC(UA):'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'WBC(UA):'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Bacteria:'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Na'),
	array('Column' => 'kk53', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp53'),
	array('Column' => 'aa27','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'K'),
	array('Column' => 'kk54', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp54'),
	array('Column' => 'aa28','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc54','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Cl'),
	array('Column' => 'kk55', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp55'),
	array('Column' => 'aa29','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc55','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CO2'),
	array('Column' => 'kk56', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp56'),
	array('Column' => 'aa30','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc56','Span' => 3),
	
	array('Insn' => '//'),

 
array('Label' =>  'BUN'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Creatinine:'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Abdominal Ultrasound:'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => 'memo'),
	array('Column' => 'c510','Span' => 3),
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Label' =>  'Urinary acid:'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),



 

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@	
 
//
	

	
//
 
//0710-2012



 //kojosen
 array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Thyroid Function'),
	array('Column' => 'k411', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc411','Span' => 3),
	
	
	 
 

array('Insn' => '//'),
array('Label' =>  'Findings'),
	array('Column' => 'k511', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p511'),
	
	array('Label' => 'memo'),
	array('Column' => 'c511','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
	array('Column' => 'aa46','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc92','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc93','Span' => 3),
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Digestive Function'),
	array('Column' => 'k415', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc415','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Occult Blood Stool:'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc15','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'UGI X-Ray:'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => 'memo'),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  'H.Pylori Test:'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => 'memo'),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'EGD'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => 'memo'),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'olonoscopy'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => 'memo'),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Capsule Endoscopy:'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => 'memo'),
	array('Column' => 'c509','Span' => 3),
	
	 
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
//nyugan
 
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Breast Exam'),
	array('Column' => 'k412', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc412','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  'Findings'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => 'memo'),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Mammogram:'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => 'memo'),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Breast Ultrasound:'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => 'memo'),
	array('Column' => 'c514','Span' => 3),
	
	
//fujinnka
 array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Gynecology Exam'),
	array('Column' => 'k413', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc413','Span' => 3),
array('Insn' => '//'),	

array('Label' =>  'Findings'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => 'memo'),
	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
	array('Column' => 'aa48','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc94','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Pap Smear'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => 'memo'),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
//chokuchou
 
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Rectal Exam'),
	array('Column' => 'k414', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc414','Span' => 3),
	
array('Insn' => '//'),
array('Label' =>  'Findings'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => 'memo'),
	array('Column' => 'c517','Span' => 3),
	
 
//zenritsu
 array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Prostate Exam'),
	array('Column' => 'k416', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc416','Span' => 3),
array('Insn' => '//'),	

array('Label' =>  'Findings'),
	array('Column' => 'k518', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p518'),
	
	array('Label' => 'memo'),
	array('Column' => 'c518','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'PSA'),
	array('Column' => 'kk95', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc95','Span' => 3),
	
	array('Insn' => '//'),
//yobi etc
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	
	array('Label' => 'memo'),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => 'memo'),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => 'memo'),
	array('Column' => 'cc532','Span' => 3),

	

array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => 'Total Results'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => 'Note'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),




);



//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE 0315-2013 modefied
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
$__otatest_order_cfg['E_RANDOM_LAYOUT'] = array(

array('Label' => 'Testdate'),
	array('Column' => 'order_date'),
 
	array('Label' => 'Pre-date'),
	array('Column' => 'preorderdate'),
array('Column' => 'shiji',
					'Label' => 'doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_ota_kiroku()

				       ), 
	
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),

//

	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Total Results'),
	array('Column' => 'k400', 'Span' => 1),
array('Label' => 'memo'),
	array('Column' => 'cc400','Span' => 3),
	
//0320	
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Physical Exam'),
	array('Column' => 'k401', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc401','Span' => 3),
	
	array('Insn' => '//'),
	
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	

	array('Label' =>  'Chest Xray'),
	array('Column' => 'k501', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p501'),
	
	array('Label' => 'memo'),
	array('Column' => 'c501','Span' => 3),
	
	array('Insn' => '//'),
  

	array('Label' =>  'EKG:'),
	array('Column' => 'k502', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p502'),
	
	array('Label' => 'memo'),
	array('Column' => 'c502','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Echocardiogram:'),
	array('Column' => 'k503', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p503'),
	
	array('Label' => 'memo'),
	array('Column' => 'c503','Span' => 3),
	
	 
//0320
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
	array('Insn' => '  ', 'Span' => 1),
	array('Label' =>  'Pulmonary Function'),
	array('Column' => 'k402', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc402','Span' => 3),	
//0328-2013 ??	
	array('Insn' => '//'),
	array('Label' =>  'Spirometry'),
	array('Column' => 'k507', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p507'),
	
	array('Label' => 'memo'),
	array('Column' => 'c507','Span' => 3),
	
	array('Insn' => '//'),
	
	
 

//??


//0315-2013
	array('Insn' => '//'),
	array('Label' =>  'Chest Xray'),
	array('Column' => 'k500', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p500'),
	
	array('Label' => 'memo'),
	array('Column' => 'c500','Span' => 3),
	
	array('Insn' => '//'),

//
array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),


//ETC

//
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Hematologic Study'),
	array('Column' => 'k409', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc409','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  'Blood Type'),
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
	
	array('Label' => 'Hemoglobin'),
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
//	array('Label' => 'memo'),
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
//	array('Label' => 'memo'),
//	array('Column' => 'cc76','Span' => 3),
	
	 
	array('Insn' => '//'),
	array('Label' =>  'MCH'),
	array('Column' => 'kk77',
	      
	       'Span' =>1),
	array('Label' => ''),
	array('Column' => 'pp77'),
	array('Column' => 'aa43','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc77','Span' => 3),
	
	 
	array('Insn' => '//'),
	
	array('Label' => 'MCHC'),
	array('Column' => 'kk78',
	      
	       'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp78'),
	array('Column' => 'aa44','Label' => '','Span' => 1), 
//	array('Label' => 'memo'),
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
	array('Insn' => ' '),
 
	array('Insn' => '//'),	 
	array('Insn' => '//'), 
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Glucose Metabolism'),
	array('Column' => 'k407', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc407','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Glucose:'),
	array('Column' => 'kk50', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp50'),
	array('Column' => 'aa20','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc50','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Urinary Glucose:'),
	array('Column' => 'kk51', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp51'),
	array('Column' => 'aa21','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc51','Span' => 3),
	
	array('Insn' => '//'),



array('Label' =>  'HbA1C'),
	array('Column' => 'kk52', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp52'),
	array('Column' => 'aa22','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc52','Span' => 3),
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Lipid Metabolism'),
	array('Column' => 'k404', 'Span' => 1),
	
	array('Label' => 'memo'),
	array('Column' => 'cc404','Span' => 3),
	array('Insn' => '//'),
//1020-2012
array('Label' =>  'Total Cholesterol:'),
	array('Column' => 'kk10', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp10'),
	array('Column' => 'aa1','Label' => '','Span' => 1),
	      
	     
//	array('Label' => 'memo'),
//	array('Column' => 'cc10','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'HDL'),
	array('Column' => 'kk11', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp11'),
	array('Column' => 'aa2','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc11','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'VLDL'),
	array('Column' => 'kk12', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp12'),
	array('Column' => 'aa3','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc12','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDL'),
	array('Column' => 'kk14', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp14'),
	array('Column' => 'aa5','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc14','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Triglycerides:'),
	array('Column' => 'kk13', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp13'),
	array('Column' => 'aa4','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc13','Span' => 3),

array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Pancreatic Function'),
	array('Column' => 'k405', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc405','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Amylase:'),
	array('Column' => 'kk21', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp21'),
	array('Column' => 'aa7','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc21','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Abdominal Ultrasound:'),
	array('Column' => 'k519', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p519'),
	
	array('Label' => 'memo'),
	array('Column' => 'c519','Span' => 3),
	
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Liver function'),
	array('Column' => 'k406', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc406','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'T.Protein:'),
	array('Column' => 'kk30', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp30'),
	array('Column' => 'aa8','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc30','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Albumin:'),
	array('Column' => 'kk31', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp31'),
	array('Column' => 'aa9','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc31','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'A/G'),
	array('Column' => 'kk32', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp32'),
	array('Column' => 'aa10','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc32','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'GOT'),
	array('Column' => 'kk33', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp33'),
	array('Column' => 'aa11','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc33','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'GPT'),
	array('Column' => 'kk34', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp34'),
	array('Column' => 'aa12','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc34','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  '¦Ã-GTP'),
	array('Column' => 'kk35', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp35'),
	array('Column' => 'aa13','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc35','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'LDH'),
	array('Column' => 'kk36', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp36'),
	array('Column' => 'aa14','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc36','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'T.Bilirubin:'),
	array('Column' => 'kk37', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp37'),
	array('Column' => 'aa15','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc37','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'ALP'),
	array('Column' => 'kk38', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp38'),
	array('Column' => 'aa16','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc38','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Hepatitis B Antigen:'),
	array('Column' => 'kk39', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp39'),
	array('Column' => 'aa17','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc39','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Hepatitis B Antibody:'),
	array('Column' => 'kk40', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp40'),
	array('Column' => 'aa18','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc40','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Hepatitis C Antibody:'),
	array('Column' => 'kk41', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp41'),
	array('Column' => 'aa19','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc41','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Abdominal Ultrasound:'),
	array('Column' => 'k520', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p520'),
	
	array('Label' => 'memo'),
	array('Column' => 'c520','Span' => 3),
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),

//
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Kidney Function'),
	array('Column' => 'k408', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc408','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'UrinalysisProtein:'),
	array('Column' => 'kk64', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp64'),
	array('Column' => 'aa23','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc64','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Sugar:'),
	array('Column' => 'kk65', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp65'),
	array('Column' => 'aa24','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc65','Span' => 3),
	
	array('Insn' => '//'),
//
array('Label' =>  'Ketone:'),
	array('Column' => 'kk66', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp66'),
	array('Column' => 'aa25','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc66','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Blood'),
	array('Column' => 'kk67', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp67'),
	array('Column' => 'aa26','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc67','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'RBC(UA):'),
	array('Column' => 'kk60', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp60'),
	array('Column' => 'aa31','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc60','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'WBC(UA):'),
	array('Column' => 'kk61', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp61'),
	array('Column' => 'aa32','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc61','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Bacteria:'),
	array('Column' => 'kk62', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp62'),
	array('Column' => 'aa33','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc62','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Na'),
	array('Column' => 'kk53', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp53'),
	array('Column' => 'aa27','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc53','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'K'),
	array('Column' => 'kk54', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp54'),
	array('Column' => 'aa28','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc54','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Cl'),
	array('Column' => 'kk55', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp55'),
	array('Column' => 'aa29','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc55','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CO2'),
	array('Column' => 'kk56', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp56'),
	array('Column' => 'aa30','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc56','Span' => 3),
	
	array('Insn' => '//'),

 
array('Label' =>  'BUN'),
	array('Column' => 'kk57', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp57'),
	array('Column' => 'aa35','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc57','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Creatinine:'),
	array('Column' => 'kk58', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp58'),
	array('Column' => 'aa36','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc58','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Abdominal Ultrasound:'),
	array('Column' => 'k510', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p510'),
	
	array('Label' => 'memo'),
	array('Column' => 'c510','Span' => 3),
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Label' =>  'Urinary acid:'),
	array('Column' => 'kk59', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp59'),
	array('Column' => 'aa37','Label' => '','Span' => 1),
 
	
	array('Insn' => '//'),



 

 



 //kojosen
 array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Thyroid Function'),
	array('Column' => 'k411', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc411','Span' => 3),
	
	
	 
 

array('Insn' => '//'),
array('Label' =>  'Findings'),
	array('Column' => 'k511', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p511'),
	
	array('Label' => 'memo'),
	array('Column' => 'c511','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'TSH'),
	array('Column' => 'kk92', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp92'),
	array('Column' => 'aa46','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc92','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'FreeT4'),
	array('Column' => 'kk93', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp93'),
	array('Column' => 'aa47','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc93','Span' => 3),
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),	
	array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Digestive Function'),
	array('Column' => 'k415', 'Span' => 1),
	array('Label' => 'memo'),
	array('Column' => 'cc415','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Occult Blood Stool:'),
	array('Column' => 'kk15', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp15'),
	array('Column' => 'aa6','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc15','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'UGI X-Ray:'),
	array('Column' => 'k504', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p504'),
	
	array('Label' => 'memo'),
	array('Column' => 'c504','Span' => 3),
	
	array('Insn' => '//'),


array('Label' =>  'H.Pylori Test:'),
	array('Column' => 'k505', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p505'),
	
	array('Label' => 'memo'),
	array('Column' => 'c505','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'EGD'),
	array('Column' => 'k506', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p506'),
	
	array('Label' => 'memo'),
	array('Column' => 'c506','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'olonoscopy'),
	array('Column' => 'k508', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p508'),
	
	array('Label' => 'memo'),
	array('Column' => 'c508','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  'Capsule Endoscopy:'),
	array('Column' => 'k509', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p509'),
	
	array('Label' => 'memo'),
	array('Column' => 'c509','Span' => 3),
	
	 
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
//nyugan
 
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Breast Exam'),
	array('Column' => 'k412', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc412','Span' => 3),
	array('Insn' => '//'),

array('Label' =>  'Findings'),
	array('Column' => 'k512', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p512'),
	
	array('Label' => 'memo'),
	array('Column' => 'c512','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Mammogram:'),
	array('Column' => 'k513', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p513'),
	
	array('Label' => 'memo'),
	array('Column' => 'c513','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Breast Ultrasound:'),
	array('Column' => 'k514', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p514'),
	
	array('Label' => 'memo'),
	array('Column' => 'c514','Span' => 3),
	
	
//fujinnka
 array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Gynecology Exam'),
	array('Column' => 'k413', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc413','Span' => 3),
array('Insn' => '//'),	

array('Label' =>  'Findings'),
	array('Column' => 'k515', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p515'),
	
	array('Label' => 'memo'),
	array('Column' => 'c515','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'CA125'),
	array('Column' => 'kk94', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp94'),
	array('Column' => 'aa48','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc94','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'Pap Smear'),
	array('Column' => 'k516', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p516'),
	
	array('Label' => 'memo'),
	array('Column' => 'c516','Span' => 3),
	
	array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
//chokuchou
 
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Rectal Exam'),
	array('Column' => 'k414', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc414','Span' => 3),
	
array('Insn' => '//'),
array('Label' =>  'Findings'),
	array('Column' => 'k517', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p517'),
	
	array('Label' => 'memo'),
	array('Column' => 'c517','Span' => 3),
	
 
//zenritsu
 array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
	array('Insn' => '//'),
//0320
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Prostate Exam'),
	array('Column' => 'k416', 'Span' => 1),
 	array('Label' => 'memo'),
 	array('Column' => 'cc416','Span' => 3),
array('Insn' => '//'),	

array('Label' =>  'Findings'),
	array('Column' => 'k518', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'p518'),
	
	array('Label' => 'memo'),
	array('Column' => 'c518','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  'PSA'),
	array('Column' => 'kk95', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp95'),
	array('Column' => 'aa49','Label' => '','Span' => 1),
//	array('Label' => 'memo'),
//	array('Column' => 'cc95','Span' => 3),
	
	array('Insn' => '//'),
//yobi etc
array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk540', 'Span' => 1),
	array('Column' => 'kk530', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp530'),
	
	array('Label' => 'memo'),
	array('Column' => 'cc530','Span' => 3),
	
	array('Insn' => '//'),
array('Label' =>  ''),
	array('Column' => 'kk541', 'Span' => 1),
	array('Column' => 'kk531', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp531'),
	
	array('Label' => 'memo'),
	array('Column' => 'cc531','Span' => 3),
	
	array('Insn' => '//'),

array('Label' =>  ''),
	array('Column' => 'kk542', 'Span' => 1),
	array('Column' => 'kk532', 'Span' => 1),
	array('Label' => ''),
	array('Column' => 'pp532'),
	
	array('Label' => 'memo'),
	array('Column' => 'cc532','Span' => 3),

	

array('Insn' => '//'),
	array('Insn' => ' '),
 
	array('Insn' => '//'),
array('Insn' => '//'),
	array('Insn' => '//'),
	array('Label' => 'Total Results'),
	array('Column' => 'special_req', 'Span' => 5),
	array('Insn' => '//'),

	array('Label' => 'Note'),
	array('Column' => 'notes', 'Span' =>5),
	array('Insn' => '//'),




);


// copy end 0425-2013





 


foreach ($__otatest_all_cols as $v) {
	$o = mx_check_option('Option', $v);
	$c = $v['Column'];

	if (!mx_check_option('nostore', $o)) {
		$__otatest_order_cfg['COLS'][] = $c;
		$__otatest_order_cfg['ICOLS'][] = $c;
	}
	if (mx_check_option('list', $o))
		$__otatest_order_cfg['LCOLS'][] = $v;
	if (!mx_check_option('nodisp', $o))
		$__otatest_order_cfg['DCOLS'][] = $v;
	if (!mx_check_option('noedit', $o))
		$__otatest_order_cfg['ECOLS'][] = $v;
}

function __otatest_anno(&$data)
{
	if ($data['nu_order'] && $data['staple_qty']) {
		$nu_order = mx_db_sql_quote($data['nu_order']);
		$db = mx_db_connect();
		$stmt = <<<SQL
			SELECT energy_base, protein_base, fat_base, salt_base,
			energy_mod, protein_mod, fat_mod, salt_mod
			FROM otatest_master
			WHERE "ObjectID" = $nu_order
SQL;

		if ($d = mx_db_fetch_single($db, $stmt)) {
			foreach (array('energy', 'protein', 'fat', 'salt')
				 as $k) {
				$b = ($d[$k.'_base'] +
				      $data['staple_qty'] * $d[$k.'_mod']
				      / 100.0);
				$data[$k.'_base'] = sprintf("%.2f", $b);
				$b += $data[$k.'_mod'];
				$data[$k.'_total'] = sprintf("%.2f", $b);
			}
		}
	}


	$data['order_range'] =
		sprintf("%s (%s) ¡Á %s (%s)",
			$data['order_since'], $data['order_since1'],
			(trim($data['order_until1']) == ''
			 ? $data['order_until'] : $data['order_until1']),
			$data['order_until2']);
}

class list_of_otatest_orders extends list_of_ppa_objects {

	var $debug = 1;
	var $patient_column_name_quoted = 'patient';

	function list_of_otatest_orders($prefix, $cfg=NULL) {
		global $__otatest_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__otatest_order_cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_doctor_otatest_order_anno(&$data);
		return list_of_ppa_objects::annotate_row_data(&$data);
	}
}

class otatest_order_display extends simple_object_display {

	var $debug = 1;

	function otatest_order_display($prefix, $cfg=NULL) {
		global $__otatest_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__otatest_order_cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_doctor_otatest_order_anno(&$data);
		return simple_object_display::annotate_row_data(&$data);
	}

//
 
//


 
function print_sod() {
    go_pdfe($this->id, 0);
  }

 


}

class otatest_order_edit extends simple_object_ppa_edit {

	var $debug = 1;

	var $patient_column_name = 'patient';

	function edit_tweak() {
		$this->data['recorded_on'] = mx_today_string();
		__lib_u_doctor_otatest_order_anno(&$this->data);
	}

	function anew_tweak($orig_id) {
		if (trim($this->data['order_date']) == '')
			$this->data['order_date'] = mx_today_string();
		$this->data['recorded_on'] = mx_today_string();
	}

	function annotate_form_data(&$data) {
		if ($data['kk1'] && $data['kk2'])
			__lib_u_doctor_otatest_order_anno(&$data);
		return simple_object_ppa_edit::annotate_form_data(&$data);
	}

	function otatest_order_edit($prefix, $cfg=NULL) {
		global $__otatest_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__otatest_order_cfg);
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
function otatest_module_index_info
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
		$sod = new otatest_order_display("sod-$ix-", &$config);
		$sod->reset($object);
		$result[] = $sod->module_info($p_pid);
	}
	return $result;
}


?>
