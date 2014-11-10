<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

function __lib_u_insident_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'insident',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'reportdate',
 COLS => array(
 "reportdate" ,
 "factdate",
 "factloc",
"facttype",
"busho",
"empnm1",
"empnm2",
"empnm3",
  "´µ¼Ô" ,
"pnm1",
"pnm2",
"pnm3",
 
  "factcont" ,
  "factdone",
  "factplan" ,
  "factdo",
"proof"
 ),

LCOLS => array(
array('Column' => 'reportdate',
'Label' => 'Êó¹ðÆü'),			      
array('Column' => 'factdate',
'Label' => 'È¯À¸Æü'),
array('Column' => 'factloc',
'Label' => '¾ì½ê'),
array('Column' => 'facttype',
'Label' => '¼ïÎà'),
array('Column' => 'busho',
'Label' => 'Éô½ð'),
array('Column' => 'empnm1',
'Label' => '»áÌ¾'),

array('Column' => 'pnm1',
'Label' => '´µ¼ÔÌ¾'),
array('Column' => 'factcont',
'Label' => '¾õ¶·'),
array('Column' => 'factdone',
'Label' => 'ÂÐ±þ'),
array('Column' => 'factdo',
'Label' => '½ÅÍ×ÅÙ'),
array('Column' => 'proof',
'Label' => '¼Â»Ü¡¦Ì¤¡¦ºÑ')),




DCOLS => array(
array('Column' => 'reportdate',
'Label' => 'Êó¹ðÆü'),			      
array('Column' => 'factdate',
'Label' => 'È¯À¸Æü'),
array('Column' => 'factloc',
'Label' => '¾ì½ê'),
array('Column' => 'facttype',
'Label' => '¼ïÎà'),
array('Column' => 'busho',
'Label' => 'Éô½ð'),
array('Column' => 'empnm1',
'Label' => '»áÌ¾'),

array('Column' => 'pnm1',
'Label' => '´µ¼ÔÌ¾'),
array('Column' => 'factcont',
'Label' => '¾õ¶·'),
array('Column' => 'factdone',
'Label' => 'ÂÐ±þ'),
array('Column' => 'factdo',
'Label' => '½ÅÍ×ÅÙ'),
array('Column' => 'proof',
'Label' => '¼Â»Ü¡¦Ì¤¡¦ºÑ')),


ECOLS => array(
array('Column' => 'reportdate','Label' => 'Êó¹ðÆü',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),
array('Column' => 'busho',
'Label' => 'Éô½ð',

				       'Draw' => 'enum',
				       'Enum' => array('1³¬' => '1³¬',
						       
						       '2³¬' => '2³¬'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),
array('Column' => 'empnm1',
'Label' => '¿¦°÷Ì¾',

				       'Draw' => 'enum',
				       'Enum' => array('1' => 'tanaka',
						       
						       '2' => 'suzuki'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),
array('Column' => 'pnm1',
'Label' => '´µ¼ÔÌ¾',

				       'Draw' => 'text',
				       
				       'Option' => array('validate' =>
							 'nonnull')),
array('Column' => 'facttype',
'Label' => 'Type',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       
						       'B' => 'B'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),


array('Column' => 'factdate',
'Label' => 'È¯À¸Æü',
				       'Draw' => 'date',
				       'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'factloc',
'Label' => '¾ì½ê',

				       'Draw' => 'enum',
				       'Enum' => array('1³¬' => '1³¬',
						       
						       '2³¬' => '2³¬'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),
array('Column' => 'facttype',
'Label' => 'Type',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       
						       'B' => 'B'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),



 array('Column' => 'factcont',
					'Label' => '¾õ¶·',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident1'),
'cols' => 80)

),
array('Column' => 'factdone',
					'Label' => 'ÂÐ±þ',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident2'),
'cols' => 80)

),
array('Column' => 'factdo',
					'Label' => 'ÂÐºö',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident3'),
'cols' => 80)

),

array('Column' => 'factdo',
'Label' => '½ÅÍ×ÅÙ',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       
						       'B' => 'B'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),

 array('Column' => 'proof',
'Label' => '¼Â»Ü¡¦Ì¤¡¦ºÑ¡¦¾µÇ§',

				       'Draw' => 'enum',
				       'Enum' => array('Ì¤¾µÇ§' => 'Ì¤¾µÇ§',
						       
						       '¾µÇ§' => '¾µÇ§'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)
), $cfg);
	return $cfg;
}

class list_of_insidents extends list_of_ppa_objects {
	function list_of_insidents($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class insident_display extends simple_object_display {
	function insident_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class insident_edit extends simple_object_edit {
	function insident_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_insident_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
 function anew_tweak($orig_id) {
		$this->data['reportdate'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
	/*	$this->data['reportdate'] = mx_now_string(); */

		return simple_object_edit::commit($force);
	}
}
?>

