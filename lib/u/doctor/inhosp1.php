<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdfkarte.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';

function __lib_u_doctor_pttest_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'pttest1',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'OrderDate',

 COLS => array(
 "OrderDate" ,
 
  "����" ,
  "a0",
  "a1" ,
  "a2",
 
"a3" 
 ),

LCOLS => array(
array('Column' => 'OrderDate','Label' => '������'),			      
//array('Column' => 'plandate','Label' => 'ͽ����'),
//array('Column' => 'procdate','Label' => '�ѹ���'),
//array('Column' => 'mlkubun','Label' => '����'),

array('Column' => 'a0','Label' => '�翩'),
array('Column' => 'a1','Label' => 'ʬ��'),
array('Column' => 'a2','Label' => '����'),


array('Column' => 'a3')),



DCOLS => array(
array('Column' => 'OrderDate','Label' => '������'),			      
//array('Column' => 'plandate','Label' => 'ͽ����'),
//array('Column' => 'procdate','Label' => '�ѹ���'),
//array('Column' => 'mlkubun','Label' => '����'),

array('Column' => 'a0','Label' => '�翩'),
array('Column' => 'a1','Label' => 'ʬ��'),
array('Column' => 'a2','Label' => '����'),


array('Column' => 'a3')),


ECOLS => array(
array('Column' => 'OrderDate',
'Label' => '������',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),

array('Column' => 'a0',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'ī' => 'ī',
'��' => '��',
'ͼ' => 'ͼ',
' �������ߤ�' => '�������ߤ�',
'�������Ԥ�' => '�������Ԥ�',
'����' => '����',
'����' => '����',
)
 ),
array('Column' => 'a1',
					'Label' => '����',
				    'Draw' => 'text'), 
 
array('Column' => 'a2',
					'Label' => '�翩',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'����' => '����',
'������' => '������',
 '���ɤ�' => '���ɤ�',
'�ѥ�' => '�ѥ�',
 '���ˤ���' => '���ˤ���',
'����¾' => '����¾',
						     )
				       ),








 array('Column' => 'a3',
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       '���' => '���',
						       '������' => '������',
						       '̤��ǧ' => '̤��ǧ'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')
     )

)
),$cfg);
	return $cfg;
}

class list_of_pttests extends list_of_ppa_objects {
	function list_of_pttests($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_pttest_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class pttest_display extends simple_object_display {

	function pttest_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_pttest_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

/* 
function print_sod() {
    go_pdf5($this->id, 0);
  }
 
*/

function print_sod($template='srl') {
    $db = mx_db_connect();

  $oid = $this->id;



    $stmt = 'SELECT "ID" from "pttest1" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printml.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }



}

class pttest_edit extends simple_object_edit {
	function pttest_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_pttest_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
function anew_tweak($orig_id) {
		$this->data['OrderDate'] = mx_today_string();
	} 
function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
		return simple_object_edit::commit($force);
	}


}
?>

