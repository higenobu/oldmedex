<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';


	
function __lib_u_doctor_patient_memo_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'ptmemo',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'OrderDate',

 COLS => array("患者",'OrderDate','tenki', 'a0','a1',
'a2','a3' ),

LCOLS => array(

array('Column' => 'OrderDate',
'Label' => '作成日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
array('Column' => 'tenki',
'Label' => 'カルテ転記日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),

 array('Column' => 'a0',
					'Label' => '備考',
				    'Draw' => 'textarea'),
array('Column' =>'a1','Label' => 'アレルギー', 'Draw' => 'textarea','Option' =>array('vocab' => array('pt-alergy'),'cols' => 20,'rows'=>3),),

array('Column' => 'a2',
	      'Label' => '感染症',
	      'Draw' => 'textarea','Option' =>array('vocab' => array('pt-kansen'),'cols' => 20,'rows'=>3),),
	   


array('Column' => 'a3',
 'Label' => 'その他', 
'Draw' => 'textarea','Option' =>array('vocab' => array('pt-etc'),'cols' => 20,'rows'=>3),)



     

),


DCOLS => array(

array('Column' => 'OrderDate',
'Label' => '作成日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
array('Column' => 'tenki',
'Label' => 'カルテ転記日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),

 array('Column' => 'a0',
					'Label' => '備考',
				    'Draw' => 'textarea'),
array('Column' =>'a1','Label' => 'アレルギー', 'Draw' => 'textarea','Option' =>array('vocab' => array('pt-alergy'),'cols' => 20,'rows'=>3),),

array('Column' => 'a2',
	      'Label' => '感染症',
	      'Draw' => 'textarea','Option' =>array('vocab' => array('pt-kansen'),'cols' => 20,'rows'=>3),),
	   


array('Column' => 'a3',
 'Label' => 'その他', 
'Draw' => 'textarea','Option' =>array('vocab' => array('pt-etc'),'cols' => 20,'rows'=>3),)

),


ECOLS => array(

array('Column' => 'OrderDate',
'Label' => '作成日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
array('Column' => 'tenki',
'Label' => 'カルテ転記日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),

 array('Column' => 'a0',
					'Label' => '備考',
				    'Draw' => 'textarea'),
array('Column' =>'a1','Label' => 'アレルギー', 'Draw' => 'textarea','Option' =>array('vocab' => array('pt-alergy'),'cols' => 20,'rows'=>3),),

array('Column' => 'a2',
	      'Label' => '感染症',
	      'Draw' => 'textarea','Option' =>array('vocab' => array('pt-kansen'),'cols' => 20,'rows'=>3),),
	   


array('Column' => 'a3',
 'Label' => 'その他', 
'Draw' => 'textarea','Option' =>array('vocab' => array('pt-etc'),'cols' => 20,'rows'=>3),)



     

)
),$cfg);
	return $cfg;
}

class list_of_patient_memos extends list_of_ppa_objects {
	function list_of_patient_memos($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_patient_memo_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class patient_memo_display extends simple_object_display {
	function patient_memo_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_patient_memo_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class patient_memo_edit extends simple_object_edit {
	function patient_memo_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_patient_memo_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
function anew_tweak($orig_id) {
		$this->data['OrderDate'] = mx_today_string();
		$this->data['tenki'] = mx_today_string();
	} 
function commit($force=NULL) {
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
		return simple_object_edit::commit($force);
	}



}
?>

