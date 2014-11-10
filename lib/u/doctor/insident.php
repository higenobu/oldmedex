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
  "����" ,
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
'Label' => '�����'),			      
array('Column' => 'factdate',
'Label' => 'ȯ����'),
array('Column' => 'factloc',
'Label' => '���'),
array('Column' => 'facttype',
'Label' => '����'),
array('Column' => 'busho',
'Label' => '����'),
array('Column' => 'empnm1',
'Label' => '��̾'),

array('Column' => 'pnm1',
'Label' => '����̾'),
array('Column' => 'factcont',
'Label' => '����'),
array('Column' => 'factdone',
'Label' => '�б�'),
array('Column' => 'factdo',
'Label' => '������'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),




DCOLS => array(
array('Column' => 'reportdate',
'Label' => '�����'),			      
array('Column' => 'factdate',
'Label' => 'ȯ����'),
array('Column' => 'factloc',
'Label' => '���'),
array('Column' => 'facttype',
'Label' => '����'),
array('Column' => 'busho',
'Label' => '����'),
array('Column' => 'empnm1',
'Label' => '��̾'),

array('Column' => 'pnm1',
'Label' => '����̾'),
array('Column' => 'factcont',
'Label' => '����'),
array('Column' => 'factdone',
'Label' => '�б�'),
array('Column' => 'factdo',
'Label' => '������'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),


ECOLS => array(
array('Column' => 'reportdate','Label' => '�����',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),
array('Column' => 'busho',
'Label' => '����',

				       'Draw' => 'enum',
				       'Enum' => array('1��' => '1��',
						       
						       '2��' => '2��'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),
array('Column' => 'empnm1',
'Label' => '����̾',

				       'Draw' => 'enum',
				       'Enum' => array('1' => 'tanaka',
						       
						       '2' => 'suzuki'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),
array('Column' => 'pnm1',
'Label' => '����̾',

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
'Label' => 'ȯ����',
				       'Draw' => 'date',
				       'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'factloc',
'Label' => '���',

				       'Draw' => 'enum',
				       'Enum' => array('1��' => '1��',
						       
						       '2��' => '2��'
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
					'Label' => '����',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident1'),
'cols' => 80)

),
array('Column' => 'factdone',
					'Label' => '�б�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident2'),
'cols' => 80)

),
array('Column' => 'factdo',
					'Label' => '�к�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('insident3'),
'cols' => 80)

),

array('Column' => 'factdo',
'Label' => '������',

				       'Draw' => 'enum',
				       'Enum' => array('A' => 'A',
						       
						       'B' => 'B'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')),

 array('Column' => 'proof',
'Label' => '�»ܡ�̤���ѡ���ǧ',

				       'Draw' => 'enum',
				       'Enum' => array('̤��ǧ' => '̤��ǧ',
						       
						       '��ǧ' => '��ǧ'
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
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
	/*	$this->data['reportdate'] = mx_now_string(); */

		return simple_object_edit::commit($force);
	}
}
?>

