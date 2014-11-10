<?php
//defkarte,php

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

 

$__karte_addition_enum = array
(
	'','特別','普通',
);




$__karte_exam_enum = array
(
	'N/A','異常なし','所見あり','再検査','不明',
);
$__karte_hantei_enum = array
(
	'-','A','B','C(1)','C(2)','C(3)','D','E','F','G'
);
//1020-2012
$__karte_n_enum = array
(
'0'=>'-','1'=>'Ab',
);
$__karte_plus_enum = array
(
	'N/A','(-)','(+)', 
);
$__karte_abo_enum = array
(
   'N/A','A','B','O','AB',
);

 $__karte_rh_enum = array
 (
 	'N/A','Rh+','Rh-', 
);
$__karte_np_enum = array
(
	'N/A','NEGATIVE','POSITIVE', 
);
//0715-2014 added test template

$__karte_report_enum = array
(
'1'=>'Japanese', '2'=>'English','3'=>'test Japanese','4'=>'test English' 
);


$__karte_all_cols = array(

array('Column' => "患者",'Draw' => NULL,'Option' => array('noedit' => 1, 'nodisp' => 1),),
	  
 
 
	    
array('Column' => "日付",'Label' => 'Karte作成日','Draw' => 'date','Option' => array('list' => 1,'size'=>10),),
	   
 



array('Column' => 'P',
	      'Label' => 'P',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('P'),
				'cols' =>50,'list' => 1,'rows'=>40),
	      ),
				   
 
	
 
array('Column' => 'O0',
	      'Label' => 'O0',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('S1'),
			'cols' => 50,'list' => 1,'rows'=>40),
	      ),
array('Column' => 'D',
	      'Label' => 'D',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('D'),
			'cols' => 50,'list' => 1,'rows'=>10),
	      ),


array('Column' => "I1", 'Label' =>"画像", 'Draw' => "extdocument",
			      'Option' =>array('img' => 'always',
			    'Extdocument' => '画像')),
			

array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp')
			
);


?>
