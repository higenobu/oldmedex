<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdfkarte.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';

function __lib_u_doctor_mlorder_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'mlorder',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'orderdate',

 COLS => array(
 "orderdate" ,
 "plandate",
 "procdate" ,
  "����" ,
  "teikikubun",
  "mlkubun" ,
  "nutname",
  "nutid" ,
 "syusyoku" ,
  "sbunryo" ,
 "fukusyoku" ,
 "fbunryo" ,
 "syokusyu",
"memo1",
"kinsyoku" ,
"memo2" ,
"memo4" ,
"memo5" ,
"proof" 
 ),

LCOLS => array(
array('Column' => 'orderdate','Label' => '������'),			      
array('Column' => 'plandate','Label' => 'ͽ����'),
array('Column' => 'procdate','Label' => '�ѹ���'),
array('Column' => 'mlkubun','Label' => '����'),

array('Column' => 'syusyoku','Label' => '�翩'),
array('Column' => 'sbunryo','Label' => 'ʬ��'),
array('Column' => 'fukusyoku','Label' => '����'),

array('Column' => 'fbunryo','Label' => 'ʬ��'),
array('Column' => 'syokusyu','Label' => '����'),
array('Column' => 'memo1','Label' => '���'),
array('Column' => 'kinsyoku', 'Label' => '�ؿ�'),
array('Column' => 'memo2', 'Label' => '����륮����'),

array('Column' => 'proof')),



DCOLS => array(
array('Column' => 'orderdate','Label' => '������'),			      
array('Column' => 'plandate','Label' => 'ͽ����'),
array('Column' => 'procdate','Label' => '�ѹ���'),
array('Column' => 'mlkubun','Label' => '����'),

array('Column' => 'syusyoku','Label' => '�翩'),
array('Column' => 'sbunryo','Label' => 'ʬ��'),
array('Column' => 'fukusyoku','Label' => '����'),

array('Column' => 'fbunryo','Label' => 'ʬ��'),
array('Column' => 'syokusyu','Label' => '����'),
array('Column' => 'memo1','Label' => '���'),
array('Column' => 'kinsyoku', 'Label' => '�ؿ�'),
array('Column' => 'memo2','Label' => '����륮����'),
array('Column' => 'proof')),


ECOLS => array(
array('Column' => 'orderdate',
'Label' => '������',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
array('Column' => 'plandate',
'Label' => 'ͽ����',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '�ѹ���',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),
array('Column' => 'mlkubun',
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
array('Column' => 'syokusyu',
					'Label' => '����',
				    'Draw' => 'text'), 
 
array('Column' => 'syusyoku',
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



array('Column' => 'sbunryo',
					'Label' => 'ʬ��',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'1/2' => '1/2',
'1/3' =>'1/3',						     
'2/3' =>'2/3',						      
'300g' => '300g',
'200g' => '200g',

						     )
				       ),

array('Column' => 'fukusyoku',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '����' => '����',
'����¾' => '����¾',
						      
 '�����' => '�����',
'������' => '������',
'���餫��' => '���餫��',
'�ߥ�����' => '�ߥ�����',
 )
 ),
array('Column' => 'fbunryo',
					'Label' => 'ʬ��',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '1/2' => '1/2',
'1/3' =>'1/3',						     
'2/3' =>'2/3',						      
'300g' => '300g',
'200g' => '200g',
						     )
				       ),




array('Column' => 'kinsyoku',
	      'Label' => '�ؿ�',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('������䵥�����'),
				'cols' => 80)
	      ),

				   
array('Column' => 'memo2',
	      'Label' => '����',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('������䵥�����'),
				'cols' => 80)
	      ),

 array('Column' => 'proof',
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

class list_of_mlorders extends list_of_ppa_objects {
	function list_of_mlorders($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_mlorder_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class mlorder_display extends simple_object_display {

	function mlorder_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_mlorder_cfg($cfg);
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



    $stmt = 'SELECT "ID" from "mlorder" WHERE "ObjectID"=' . $oid;
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

class mlorder_edit extends simple_object_edit {
	function mlorder_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_mlorder_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
	} 
function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
		return simple_object_edit::commit($force);
	}


}
?>

