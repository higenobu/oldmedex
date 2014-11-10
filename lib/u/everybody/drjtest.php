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
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf44.php';
 
//

function __lib_u_drjtest_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'drjms',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 's0',
 COLS => array(
 hizuke,s0, s1, s2, s3, s4, p0, p1, p2, p3, p4, p5, p6, a1, a2, a3, a4, 
       a5, a6
 ),

LCOLS => array(

array('Column' => 's0',
'Label' => 'Ê¬ÎàÈÖ¹æ'),
array('Column' => 's1',
'Label' => 'ÂçÊ¬Îà'),
 
array('Column' => 's3',
'Label' => '¾®Ê¬Îà'),
array('Column' => 'p0',
'Label' => '¹àÈÖ'),
array('Column' => 'p1',
'Label' => '´ÑÅÀ'),
array('Column' => 'p2',
'Label' => '»î¸³ÆâÍÆ'),
array('Column' => 'p3',
'Label' => '»î¸³¾ò·ï'),
array('Column' => 'p4',
'Label' => '´üÂÔÃÍ'),
array('Column' => 'p5',
'Label' => '³ÎÇ§ÂÐ¾ÝÊª'),),
 

'DEFAULT_SORT' => 's0',

DCOLS => array(
array('Column' => 's0',
'Label' => 'Ê¬ÎàÈÖ¹æ'),
array('Column' => 's1',
'Label' => 'ÂçÊ¬Îà'),
 
array('Column' => 's3',
'Label' => '¾®Ê¬Îà'),
array('Column' => 'p0',
'Label' => '¹àÈÖ'),
array('Column' => 'p1',
'Label' => '´ÑÅÀ'),
array('Column' => 'p2',
'Label' => '»î¸³ÆâÍÆ'),
array('Column' => 'p3',
'Label' => '»î¸³¾ò·ï'),
array('Column' => 'p4',
'Label' => '´üÂÔÃÍ'),
array('Column' => 'p5',
'Label' => '³ÎÇ§ÂÐ¾ÝÊª'),),


ECOLS => array(
array('Column' => 'hizume',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),


 array('Column' => 's0',
					'Label' => '»Ø¼¨',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('¤¨¿©»öäµ¥³¥á¥ó¥È'),
'cols' => 80)

),
array('Column' => 's1',		'Label' => '½èÊý',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '¥¤¥ª¥Ù¥ê¥ó300¥·¥ê¥ó¥¸100£í£ìÀ¸¿©£±£°£°£í£ì' => '¥¤¥ª¥Ù¥ê¥ó300¥·¥ê¥ó¥¸100£íÀ¸¿©£±£°£°£í£ì',
'¥ª¥à¥Ë¥Ð¡¼¥°300¥·¥ê¥ó¥¸' => '¥ª¥à¥Ë¥Ð¡¼¥°300¥·¥ê¥ó¥¸',
'¥¬¥¹¥È¥í¥°¥é¥Õ¥£¥ó' => '¥¬¥¹¥È¥í¥°¥é¥Õ¥£¥ó',
'¥Ð¥ê¥È¥²¥ó£³£°£°' => '¥Ð¥ê¥È¥²¥ó£³£°£°',
'¥Ð¥ì¥Ã¥¯¥¹¥â¥ë¥È£Ó' => '¥Ð¥ì¥Ã¥¯¥¹¥â¥ë¥È£Ó',
'¥Ð¥ê¥¨¥Í¥Þ£³£°£°' => '¥Ð¥ê¥¨¥Í¥Þ£³£°£°',
'¥Ó¥ê¥¹¥³¥Ó¥ó£Ä£É£Ã£µ£°' => '¥Ó¥ê¥¹¥³¥Ó¥ó£Ä£É£Ã£µ£°',
'¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'
						     )
				       ),


array('Column' => 's2',
					'Label' => 'Éô°Ì1',
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 'Ã±½ãÆ¬Éô' => 'Ã±½ãÆ¬Éô',
 'Ã±½ã¶»Éô' => 'Ã±½ã¶»Éô',
 'Ã±½ãÊ¢Éô' => 'Ã±½ãÊ¢Éô',
 'Ã±½ã¹üÈ×¹Ð' => 'Ã±½ã¹üÈ×¹Ð',
 'Â¤±ÆÆ¬Éô' => 'Â¤±ÆÆ¬Éô',
 'Â¤±Æ¶»Éô' => 'Â¤±Æ¶»Éô',
 'Â¤±ÆÊ¢Éô' => 'Â¤±ÆÊ¢Éô',
 'Â¤±Æ¹üÈ×¹Ð' => 'Â¤±Æ¹üÈ×¹Ð',
 '¶»Éô' => '¶»Éô',
 'Ê¢Éô' => 'Ê¢Éô',
 'Æ¬Éô' => 'Æ¬Éô',
 '·ÛÄÇ' => '·ÛÄÇ',
 '¶»ÄÇ' => '¶»ÄÇ',
 '¹øÄÇ' => '¹øÄÇ',
 '¹üÈ×' => '¹üÈ×',
 'º¿¹ü' => 'º¿¹ü',
 '¶»¹ü' => '¶»¹ü',
 '¸ª´ØÀá' => '¸ª´ØÀá',
 
 '¾åÏÓ' => '¾åÏÓ',
 'Éª´ØÀá' => 'Éª´ØÀá',
 'Á°ÏÓ' => 'Á°ÏÓ',
 '¼ê´ØÀá' => '¼ê´ØÀá',
 '¼ê»Ø' => '¼ê»Ø',
 '¸Ô´ØÀá' => '¸Ô´ØÀá',
 '²¼ÂÜ' => '²¼ÂÜ',

 'Â­´ØÀá' => 'Â­´ØÀá',
 '£Í£Ä£Ì' => '£Í£Ä£Ì',
 '£Ä£Ä£Ì' => '£Ä£Ä£Ì',
 '£Ä£É£Ã' => '£Ä£É£Ã',
 '£Ä£É£Ð' => '£Ä£É£Ð',
 '£Ë£Õ£Â' => '£Ë£Õ£Â',
 '£Õ£Ö£Ç' => '£Õ£Ö£Ç',
 '£Ú£Ç' => '£Ú£Ç',
 '¹ü±öÄêÎÌ' => '¹ü±öÄêÎÌ',
 
 '¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'),
				    'Option' => array('validate' =>
							 'nonnull')),

array('Column' => 's3',
					'Label' => '¥Õ¥£¥ë¥à¥µ¥¤¥º1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '¥µ¥¤¥º£±' => '¥µ¥¤¥º£±',
'¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'
						     )
				       ),


array('Column' => 's4',
					'Label' => 'Ëç¿ô£±',
				    'Draw' => 'text'),

array('Column' => 'p1',
					'Label' => 'Éô°Ì2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'Ã±½ãÆ¬Éô' => 'Ã±½ãÆ¬Éô',
 'Ã±½ã¶»Éô' => 'Ã±½ã¶»Éô',
 'Ã±½ãÊ¢Éô' => 'Ã±½ãÊ¢Éô',
 'Ã±½ã¹üÈ×¹Ð' => 'Ã±½ã¹üÈ×¹Ð',
 'Â¤±ÆÆ¬Éô' => 'Â¤±ÆÆ¬Éô',
 'Â¤±Æ¶»Éô' => 'Â¤±Æ¶»Éô',
 'Â¤±ÆÊ¢Éô' => 'Â¤±ÆÊ¢Éô',
 'Â¤±Æ¹üÈ×¹Ð' => 'Â¤±Æ¹üÈ×¹Ð',
 '¶»Éô' => '¶»Éô',
 'Ê¢Éô' => 'Ê¢Éô',
 'Æ¬Éô' => 'Æ¬Éô',
 '·ÛÄÇ' => '·ÛÄÇ',
 '¶»ÄÇ' => '¶»ÄÇ',
 '¹øÄÇ' => '¹øÄÇ',
 '¹üÈ×' => '¹üÈ×',
 'º¿¹ü' => 'º¿¹ü',
 '¶»¹ü' => '¶»¹ü',
 '¸ª´ØÀá' => '¸ª´ØÀá',
 
 '¾åÏÓ' => '¾åÏÓ',
 'Éª´ØÀá' => 'Éª´ØÀá',
 'Á°ÏÓ' => 'Á°ÏÓ',
 '¼ê´ØÀá' => '¼ê´ØÀá',
 '¼ê»Ø' => '¼ê»Ø',
 '¸Ô´ØÀá' => '¸Ô´ØÀá',
 '²¼ÂÜ' => '²¼ÂÜ',

 'Â­´ØÀá' => 'Â­´ØÀá',
 '£Í£Ä£Ì' => '£Í£Ä£Ì',
 '£Ä£Ä£Ì' => '£Ä£Ä£Ì',
 '£Ä£É£Ã' => '£Ä£É£Ã',
 '£Ä£É£Ð' => '£Ä£É£Ð',
 '£Ë£Õ£Â' => '£Ë£Õ£Â',
 '£Õ£Ö£Ç' => '£Õ£Ö£Ç',
 '£Ú£Ç' => '£Ú£Ç',
 '¹ü±öÄêÎÌ' => '¹ü±öÄêÎÌ',
 '¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'

						     )
				       ),
array('Column' => 'p2',
					'Label' => '¥Õ¥£¥ë¥à¥µ¥¤¥º2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '¥µ¥¤¥º2' => '¥µ¥¤¥º2',
'¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'
						     )
				       ),


array('Column' => 'p3',
					'Label' => 'Ëç¿ô2',
				    'Draw' => 'text'),



array('Column' => 'p4',
					'Label' => 'Éô°Ì3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       'Ã±½ãÆ¬Éô' => 'Ã±½ãÆ¬Éô',
 'Ã±½ã¶»Éô' => 'Ã±½ã¶»Éô',
 'Ã±½ãÊ¢Éô' => 'Ã±½ãÊ¢Éô',
 'Ã±½ã¹üÈ×¹Ð' => 'Ã±½ã¹üÈ×¹Ð',
 'Â¤±ÆÆ¬Éô' => 'Â¤±ÆÆ¬Éô',
 'Â¤±Æ¶»Éô' => 'Â¤±Æ¶»Éô',
 'Â¤±ÆÊ¢Éô' => 'Â¤±ÆÊ¢Éô',
 'Â¤±Æ¹üÈ×¹Ð' => 'Â¤±Æ¹üÈ×¹Ð',
 '¶»Éô' => '¶»Éô',
 'Ê¢Éô' => 'Ê¢Éô',
 'Æ¬Éô' => 'Æ¬Éô',
 '·ÛÄÇ' => '·ÛÄÇ',
 '¶»ÄÇ' => '¶»ÄÇ',
 '¹øÄÇ' => '¹øÄÇ',
 '¹üÈ×' => '¹üÈ×',
 'º¿¹ü' => 'º¿¹ü',
 '¶»¹ü' => '¶»¹ü',
 '¸ª´ØÀá' => '¸ª´ØÀá',
 
 '¾åÏÓ' => '¾åÏÓ',
 'Éª´ØÀá' => 'Éª´ØÀá',
 'Á°ÏÓ' => 'Á°ÏÓ',
 '¼ê´ØÀá' => '¼ê´ØÀá',
 '¼ê»Ø' => '¼ê»Ø',
 '¸Ô´ØÀá' => '¸Ô´ØÀá',
 '²¼ÂÜ' => '²¼ÂÜ',

 'Â­´ØÀá' => 'Â­´ØÀá',
 '£Í£Ä£Ì' => '£Í£Ä£Ì',
 '£Ä£Ä£Ì' => '£Ä£Ä£Ì',
 '£Ä£É£Ã' => '£Ä£É£Ã',
 '£Ä£É£Ð' => '£Ä£É£Ð',
 '£Ë£Õ£Â' => '£Ë£Õ£Â',
 '£Õ£Ö£Ç' => '£Õ£Ö£Ç',
 '£Ú£Ç' => '£Ú£Ç',
 '¹ü±öÄêÎÌ' => '¹ü±öÄêÎÌ',
 '¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'

 )
),
array('Column' => 'p5',
					'Label' => '¥Õ¥£¥ë¥à¥µ¥¤¥º3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '¥µ¥¤¥º3' => '¥µ¥¤¥º3',
'¤½¤ÎÂ¾' => '¤½¤ÎÂ¾'
						     )
				       ),


array('Column' => 'p6',
					'Label' => 'Ëç¿ô3',
				    'Draw' => 'text')
)
), $cfg);
	return $cfg;
}
class list_of_drjtests extends list_of_ppa_objects {
	function list_of_drjtests($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_drjtest_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class drjtest_display extends simple_object_display {
	function drjtest_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_drjtest_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}


class drjtest_edit extends simple_object_edit {
	function drjtest_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_drjtest_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
  
 
	function commit($force=NULL) {
		//$this->data['patient'] = $this->so_config['Patient_ObjectID'];
	 
		return simple_object_edit::commit($force);
	}
}
 
/*
$__drjtest_all_cols = array(
 
 
 
	array('Column' => 'hizuke',
	      'Label' => 'ºîÀ®Æü',
	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1,'size'=>10),
	      ),
 
¡¡
 
 

 

 	array('Column' => 's0',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 
	array('Column' => 's1',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	
	array('Column' => 's2',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 's3',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 's4',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p1',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p2',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p3',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p4',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p5',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

	array('Column' => 'p6',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 	 
	 array('Column' => 'p5',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('¤¨¿©»öäµ¥³¥á¥ó¥È'),
				'cols' => 20),),
	 
 



 
);

$__drjtest_cfg = array();

$__drjtest_cfg['TABLE'] = 'drjms';
$__drjtest_cfg['SEQUENCE'] = 'drjms_id_seq';
$__drjtest_cfg['COLS'] = array();
$__drjtest_cfg['ICOLS'] = array();
$__drjtest_cfg['ECOLS'] = array();
$__drjtest_cfg['LCOLS'] = array();
$__drjtest_cfg['DCOLS'] = array();
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//display layout 0315-2013
//*************************************************************
//DISPLAY
$__drjtest_cfg['D_RANDOM_LAYOUT'] = array(

	
 
	array('Label' => '¸¡ººÆü'),
	array('Column' => 'hizuke'),
 
	 
	
	
	array('Insn' => '//'),
	
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Ê¬ÎàÈÖ¹æ'),
	array('Column' => 's0', 'Span' => 1),
 	array('Label' => 's1'),
	array('Column' => 's1','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  's2'),
	array('Column' => 's2', 'Option' => array('size' => 5)),
	 
	 
array('Insn' => '//'),	
	array('Label' => 's3'),
	array('Column' => 's3','Option' => array('size' => 5)),
 
array('Insn' => '//'),
	array('Label' => 's4'),
	array('Column' => 's4','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p0'),
	array('Column' => 'p0','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p1'),
	array('Column' => 'p1','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p2'),
	array('Column' => 'p2','Option' => array('size' => 5)),
	 
 
);


//EDIT
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE 0315-2013 modefied
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
$__drjtest_cfg['E_RANDOM_LAYOUT'] = array(
array('Label' => '¸¡ººÆü'),
	array('Column' => 'hizuke'),
 
 
	
	array('Insn' => '//'),
	
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  'Ê¬ÎàÈÖ¹æ'),
	array('Column' => 's0', 'Span' => 1),
 	array('Label' => 's1'),
	array('Column' => 's1','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  's2'),
	array('Column' => 's2', 'Option' => array('size' => 5)),
	 
	 
array('Insn' => '//'),	
	array('Label' => 's3'),
	array('Column' => 's3','Option' => array('size' => 5)),
 
array('Insn' => '//'),
	array('Label' => 's4'),
	array('Column' => 's4','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p0'),
	array('Column' => 'p0','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p1'),
	array('Column' => 'p1','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p2'),
	array('Column' => 'p2','Option' => array('size' => 5)),
	 
 
);
 
foreach ($__drjtest_all_cols as $v) {
	$o = mx_check_option('Option', $v);
	$c = $v['Column'];

	if (!mx_check_option('nostore', $o)) {
		$__drjtest_cfg['COLS'][] = $c;
		$__drjtest_cfg['ICOLS'][] = $c;
	}
	if (mx_check_option('list', $o))
		$__drjtest_cfg['LCOLS'][] = $v;
	if (!mx_check_option('nodisp', $o))
		$__drjtest_cfg['DCOLS'][] = $v;
	if (!mx_check_option('noedit', $o))
		$__drjtest_cfg['ECOLS'][] = $v;
}
 
class list_of_drjtests extends list_of_ppa_objects {

	var $debug = 1;
	var $patient_column_name_quoted = 'header';

	function list_of_drjtests($prefix, $cfg=NULL) {
		global $__drjtest_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__drjtest_cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	
}

class drjtest_display extends simple_object_display {

	var $debug = 1;

	function drjtest_display($prefix, $cfg=NULL) {
		global $__drjtest_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__drjtest_cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
 
	function annotate_row_data(&$data) {
		__lib_u_everybody_drjtest_anno(&$data);
		return simple_object_display::annotate_row_data(&$data);
	}
 


//

//


 
 
 


}


class drjtest_edit extends simple_object_ppa_edit {

	var $debug = 1;

//	var $patient_column_name = 'header';
 
	function edit_tweak() {
		$this->data['hizuke'] = mx_today_string();
		__lib_u_everybody_drjtest_anno(&$this->data);
	}

	function anew_tweak($orig_id) {
		if (trim($this->data['hizuke']) == '')
			$this->data['hizuke'] = mx_today_string();
		$this->data['hizuke'] = mx_today_string();
	}

	function annotate_form_data(&$data) {
		if ($data['s0'])
			__lib_u_everybody_drjtest_anno(&$data);
		return simple_object_ppa_edit::annotate_form_data(&$data);
	}
 

	function drjtest_edit($prefix, $cfg=NULL) {
		global $__drjtest_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__drjtest_cfg);
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

 */



?>
