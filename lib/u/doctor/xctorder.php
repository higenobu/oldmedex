<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
function _lib_u_xct_kiroku() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "��" || "̾" as empname , userid
    from "������Ģ"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}
function __lib_u_doctor_xctorder_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'xctorder',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'orderdate',
 COLS => array(
//"CreatedBy",
 "orderdate" ,
 "plandate",
 "procdate" ,
  "����" ,
  "teikikubun",
  "xctkubun" ,
  "techname",
  "techid" ,
  "bui1" ,
  "bui2" ,
 "bui3" ,
 "bui4" ,
 "bui5",
"memo1",
"memo2" ,
"memo3" ,
"memo4" ,
"memo5" ,
"memo11" ,
"memo21" ,
"memo31" ,
"memo41" ,
"memo51",
"memo12" ,
"memo22" ,
"memo32" ,
"memo42" ,
"memo52",
"syoken1" ,
"syoken2" ,
"syoken3" ,
"syoken4" ,
"syoken5" ,
"techsyoken" ,
"drsyoken" ,
"proof" 
 ),

LCOLS => array(
array('Column' => 'orderdate',
'Label' => '������'),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_kiroku()

				       ),
array('Column' => 'bui1',
'Label' => '���̣�'),
array('Column' => 'bui2',
'Label' => '���̣�'),
array('Column' => 'bui3',
'Label' => '���̣�'),

array('Column' => 'bui4',
'Label' => '���̣�'),
array('Column' => 'bui5',
'Label' => '���̣�'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')),


'DEFAULT_SORT' => 'orderdate',

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '������'
),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => 'memo1',
'Label' => '�ؼ�'),

array('Column' => 'memo2',
'Label' => '����'),

array('Column' => 'bui1',
'Label' => '���̣�'),
array('Column' => 'bui2',
'Label' => '���̣�'),
array('Column' => 'bui3',
'Label' => '���̣�'),

array('Column' => 'bui4',
'Label' => '���̣�'),
array('Column' => 'bui5',
'Label' => '���̣�'),
array('Column' => 'techsyoken',
'Label' => '���ս긫'),			      
array('Column' => 'drsyoken',
'Label' => '��ս긫'),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_kiroku()

				       ),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')
),


ECOLS => array(
array('Column' => 'orderdate','Label' => '������',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'plandate',
'Label' => '�»�ͽ����',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '�»���',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),



 array('Column' => 'memo1',
					'Label' => '�ؼ�',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('������䵥�����'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '�����٥��300�����100������������������' => '�����٥��300�����100����������������',
'����˥С���300�����' => '����˥С���300�����',
'�����ȥ�����ե���' => '�����ȥ�����ե���',
'�Х�ȥ��󣳣���' => '�Х�ȥ��󣳣���',
'�Х�å������ȣ�' => '�Х�å������ȣ�',
'�Хꥨ�ͥޣ�����' => '�Хꥨ�ͥޣ�����',
'�ӥꥹ���ӥ�ģɣã���' => '�ӥꥹ���ӥ�ģɣã���',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 'ñ��Ƭ��' => 'ñ��Ƭ��',
 'ñ�㶻��' => 'ñ�㶻��',
 'ñ��ʢ��' => 'ñ��ʢ��',
 'ñ����׹�' => 'ñ����׹�',
 '¤��Ƭ��' => '¤��Ƭ��',
 '¤�ƶ���' => '¤�ƶ���',
 '¤��ʢ��' => '¤��ʢ��',
 '¤�ƹ��׹�' => '¤�ƹ��׹�',
 '����' => '����',
 'ʢ��' => 'ʢ��',
 'Ƭ��' => 'Ƭ��',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '������' => '������',
 
 '����' => '����',
 'ɪ����' => 'ɪ����',
 '����' => '����',
 '�����' => '�����',
 '���' => '���',
 '�Դ���' => '�Դ���',
 '����' => '����',

 '­����' => '­����',
 '�ͣģ�' => '�ͣģ�',
 '�ģģ�' => '�ģģ�',
 '�ģɣ�' => '�ģɣ�',
 '�ģɣ�' => '�ģɣ�',
 '�ˣգ�' => '�ˣգ�',
 '�գ֣�' => '�գ֣�',
 '�ڣ�' => '�ڣ�',
 '��������' => '��������',
 
 '����¾' => '����¾'),
				    'Option' => array('validate' =>
							 'nonnull')),

array('Column' => 'memo11',
					'Label' => '�ե���ॵ����1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '��������' => '��������',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo12',
					'Label' => '�����',
				    'Draw' => 'text'),

array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'ñ��Ƭ��' => 'ñ��Ƭ��',
 'ñ�㶻��' => 'ñ�㶻��',
 'ñ��ʢ��' => 'ñ��ʢ��',
 'ñ����׹�' => 'ñ����׹�',
 '¤��Ƭ��' => '¤��Ƭ��',
 '¤�ƶ���' => '¤�ƶ���',
 '¤��ʢ��' => '¤��ʢ��',
 '¤�ƹ��׹�' => '¤�ƹ��׹�',
 '����' => '����',
 'ʢ��' => 'ʢ��',
 'Ƭ��' => 'Ƭ��',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '������' => '������',
 
 '����' => '����',
 'ɪ����' => 'ɪ����',
 '����' => '����',
 '�����' => '�����',
 '���' => '���',
 '�Դ���' => '�Դ���',
 '����' => '����',

 '­����' => '­����',
 '�ͣģ�' => '�ͣģ�',
 '�ģģ�' => '�ģģ�',
 '�ģɣ�' => '�ģɣ�',
 '�ģɣ�' => '�ģɣ�',
 '�ˣգ�' => '�ˣգ�',
 '�գ֣�' => '�գ֣�',
 '�ڣ�' => '�ڣ�',
 '��������' => '��������',
 '����¾' => '����¾'

						     )
				       ),
array('Column' => 'memo21',
					'Label' => '�ե���ॵ����2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '������2' => '������2',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo22',
					'Label' => '���2',
				    'Draw' => 'text'),



array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       'ñ��Ƭ��' => 'ñ��Ƭ��',
 'ñ�㶻��' => 'ñ�㶻��',
 'ñ��ʢ��' => 'ñ��ʢ��',
 'ñ����׹�' => 'ñ����׹�',
 '¤��Ƭ��' => '¤��Ƭ��',
 '¤�ƶ���' => '¤�ƶ���',
 '¤��ʢ��' => '¤��ʢ��',
 '¤�ƹ��׹�' => '¤�ƹ��׹�',
 '����' => '����',
 'ʢ��' => 'ʢ��',
 'Ƭ��' => 'Ƭ��',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '������' => '������',
 
 '����' => '����',
 'ɪ����' => 'ɪ����',
 '����' => '����',
 '�����' => '�����',
 '���' => '���',
 '�Դ���' => '�Դ���',
 '����' => '����',

 '­����' => '­����',
 '�ͣģ�' => '�ͣģ�',
 '�ģģ�' => '�ģģ�',
 '�ģɣ�' => '�ģɣ�',
 '�ģɣ�' => '�ģɣ�',
 '�ˣգ�' => '�ˣգ�',
 '�գ֣�' => '�գ֣�',
 '�ڣ�' => '�ڣ�',
 '��������' => '��������',
 '����¾' => '����¾'

 )
),
array('Column' => 'memo31',
					'Label' => '�ե���ॵ����3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '������3' => '������3',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo32',
					'Label' => '���3',
				    'Draw' => 'text'),


array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'ñ��Ƭ��' => 'ñ��Ƭ��',
 'ñ�㶻��' => 'ñ�㶻��',
 'ñ��ʢ��' => 'ñ��ʢ��',
 'ñ����׹�' => 'ñ����׹�',
 '¤��Ƭ��' => '¤��Ƭ��',
 '¤�ƶ���' => '¤�ƶ���',
 '¤��ʢ��' => '¤��ʢ��',
 '¤�ƹ��׹�' => '¤�ƹ��׹�',
 '����' => '����',
 'ʢ��' => 'ʢ��',
 'Ƭ��' => 'Ƭ��',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '������' => '������',
 
 '����' => '����',
 'ɪ����' => 'ɪ����',
 '����' => '����',
 '�����' => '�����',
 '���' => '���',
 '�Դ���' => '�Դ���',
 '����' => '����',

 '­����' => '­����',
 '�ͣģ�' => '�ͣģ�',
 '�ģģ�' => '�ģģ�',
 '�ģɣ�' => '�ģɣ�',
 '�ģɣ�' => '�ģɣ�',
 '�ˣգ�' => '�ˣգ�',
 '�գ֣�' => '�գ֣�',
 '�ڣ�' => '�ڣ�',
 '��������' => '��������',
 '����¾' => '����¾'

						     )
				       ),

array('Column' => 'memo41',
					'Label' => '�ե���ॵ����4',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '������4' => '������4',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo42',
					'Label' => '���4',
				    'Draw' => 'text'),



array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      'ñ��Ƭ��' => 'ñ��Ƭ��',
 'ñ�㶻��' => 'ñ�㶻��',
 'ñ��ʢ��' => 'ñ��ʢ��',
 'ñ����׹�' => 'ñ����׹�',
 '¤��Ƭ��' => '¤��Ƭ��',
 '¤�ƶ���' => '¤�ƶ���',
 '¤��ʢ��' => '¤��ʢ��',
 '¤�ƹ��׹�' => '¤�ƹ��׹�',
 '����' => '����',
 'ʢ��' => 'ʢ��',
 'Ƭ��' => 'Ƭ��',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '����' => '����',
 '������' => '������',
 
 '����' => '����',
 'ɪ����' => 'ɪ����',
 '����' => '����',
 '�����' => '�����',
 '���' => '���',
 '�Դ���' => '�Դ���',
 '����' => '����',

 '­����' => '­����',
 '�ͣģ�' => '�ͣģ�',
 '�ģģ�' => '�ģģ�',
 '�ģɣ�' => '�ģɣ�',
 '�ģɣ�' => '�ģɣ�',
 '�ˣգ�' => '�ˣգ�',
 '�գ֣�' => '�գ֣�',
 '�ڣ�' => '�ڣ�',
 '��������' => '��������',
 '����¾' => '����¾'

 )
),
array('Column' => 'memo51',
					'Label' => '�ե���ॵ����5',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '������5' => '������5',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo52',
					'Label' => '���5',
				    'Draw' => 'text'),




array('Column' => 'techsyoken',
'Label' => '���ս긫',
'Draw' => 'textarea',
'Option' => array('vocab' => array('������䵥�����'),
'cols' => 80)
),



			      
array('Column' => 'drsyoken',
'Label' => '��ս긫',
'Draw' => 'textarea',
'Option' => array('vocab' => array('������䵥�����'),
'cols' => 80)
),


 array('Column' => 'proof',
'Label' => '�»ܡ�̤���ѡ���ǧ',

				       'Draw' => 'enum',
				       'Enum' => array('̤�»�' => '̤�»�',
						       '��ռ»�' => '��ռ»�',
						       '���ռ»�' => '���ռ»�',
						       '��վ�ǧ' => '��վ�ǧ'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)
), $cfg);
	return $cfg;
}

class list_of_xctorders extends list_of_ppa_objects {
	function list_of_xctorders($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class xctorder_display extends simple_object_display {
	function xctorder_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class xctorder_edit extends simple_object_edit {
	function xctorder_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
	/*	$this->data['orderdate'] = mx_now_string(); */

		return simple_object_edit::commit($force);
	}
}
?>
