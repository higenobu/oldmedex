<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
function __lib_u_doctor_xctorderex_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'xctorder',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'orderdate',
 COLS => array(
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
'Label' => '��ǧ')
),


DCOLS => array(
array('Column' => 'orderdate',
'Label' => '������'),			      
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
'Label' => '���ե�����'),			      
array('Column' => 'drsyoken',
'Label' => '��ս긫'),
array('Column' => 'proof',
'Label' => '��ǧ')
),


ECOLS => array(
array('Column' => 'orderdate',
'Label' => '������',
				       'Draw' => 'static'
				      ),
array('Column' => 'plandate',
'Label' => '�»�ͽ����',
				       'Draw' => 'static',
				       ),

array('Column' => 'procdate',
'Label' => '�»���',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),



 array('Column' => 'memo1',
					'Label' => '�ؼ�',
				    'Draw' => 'textarea'),
array('Column' => 'memo2',
					'Label' => '����',
				   
				       'Draw' => 'static',
				       'Enum' => array('' => '',
						      
 '�����٥��300�����100����������������' => '�����٥��300�����100���������������',
'����˥С���300�����' => '����˥С���300�����',
'�����ȥ���ե���' => '�����ȥ���ե���',
'�Х�ȥ��󣳣���' => '�Х�ȥ��󣳣���',
'�Х�å������ȣ�' => '�Х�å������ȣ�',
'�Хꥨ�ͥޣ�����' => '�Хꥨ�ͥޣ�����',
'�ӥꥹ���ӥ�ģɣã���' => '�ӥꥹ���ӥ�ģɣã���',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'static'
),

array('Column' => 'memo11',
					'Label' => '�ե���ॵ����1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '01' => 'Ⱦ�ڡʥǥ�����ե�����',
'02' => '��ͤ��ڡʥǥ�����ե�����',
'�ͤ��ڡʥ��ʥ��ե�����' => '�ͤ��ڡʥ��ʥ��ե�����',
'ϻ���ڤ�ʥ��ʥ��ե�����' => 'ϻ���ڤ�ʥ��ʥ��ե�����',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo12',
					'Label' => '�����',
				    'Draw' => 'text'),

array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'static'
				       ),

array('Column' => 'memo21',
					'Label' => '�ե���ॵ����2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  'Ⱦ�ڡʥǥ�����ե�����' => 'Ⱦ�ڡʥǥ�����ե�����',
'��ͤ��ڡʥǥ�����ե�����' => '��ͤ��ڡʥǥ�����ե�����',
'�ͤ��ڡʥ��ʥ��ե�����' => '�ͤ��ڡʥ��ʥ��ե�����',
'ϻ���ڤ�ʥ��ʥ��ե�����' => 'ϻ���ڤ�ʥ��ʥ��ե�����',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo22',
					'Label' => '���2',
				    'Draw' => 'text'),



array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'static'
),
array('Column' => 'memo31',
					'Label' => '�ե���ॵ����3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  'Ⱦ�ڡʥǥ�����ե�����' => 'Ⱦ�ڡʥǥ�����ե�����',
'��ͤ��ڡʥǥ�����ե�����' => '��ͤ��ڡʥǥ�����ե�����',
'�ͤ��ڡʥ��ʥ��ե�����' => '�ͤ��ڡʥ��ʥ��ե�����',
'ϻ���ڤ�ʥ��ʥ��ե�����' => 'ϻ���ڤ�ʥ��ʥ��ե�����',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo32',
					'Label' => '���3',
				    'Draw' => 'text'),


array('Column' => 'bui4',
					'Label' => '����4',
				       'Draw' => 'static'),
array('Column' => 'memo41',
					'Label' => '�ե���ॵ����4',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  'Ⱦ�ڡʥǥ�����ե�����' => 'Ⱦ�ڡʥǥ�����ե�����',
'��ͤ��ڡʥǥ�����ե�����' => '��ͤ��ڡʥǥ�����ե�����',
'�ͤ��ڡʥ��ʥ��ե�����' => '�ͤ��ڡʥ��ʥ��ե�����',
'ϻ���ڤ�ʥ��ʥ��ե�����' => 'ϻ���ڤ�ʥ��ʥ��ե�����',
'����¾' => '����¾'
						     )
				       ),



array('Column' => 'memo42',
					'Label' => '���4',
				    'Draw' => 'text'),



array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'static'
),
array('Column' => 'memo51',
					'Label' => '�ե���ॵ����5',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'Ⱦ�ڡʥǥ�����ե�����' => 'Ⱦ�ڡʥǥ�����ե�����',
'��ͤ��ڡʥǥ�����ե�����' => '��ͤ��ڡʥǥ�����ե�����',
'�ͤ��ڡʥ��ʥ��ե�����' => '�ͤ��ڡʥ��ʥ��ե�����',
'ϻ���ڤ�ʥ��ʥ��ե�����' => 'ϻ���ڤ�ʥ��ʥ��ե�����',
'����¾' => '����¾'
						     )
				       ),


array('Column' => 'memo52',
					'Label' => '���5',
				    'Draw' => 'text'),




array('Column' => 'techsyoken',
'Label' => '���ե�����',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)

),


			      
array('Column' => 'drsyoken',
					'Label' => '��ս긫',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

 array('Column' => 'proof',
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       '�»ܺ�' => '�»ܺ�',
'��ռ»�' => '��ռ»�',
						       '���ռ»�' => '�����»�',
'̤��ǧ' => '̤��ǧ',
						       '��ǧ' => '��ǧ'
						     ),
				       'Option' => array('validate' =>
							 'nonnull')
     )

)
),$cfg);
	return $cfg;
}

class list_of_xctorderexs extends list_of_ppa_objects {
	function list_of_xctorderexs($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorderex_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class xctorderex_display extends simple_object_display {
	function xctorderex_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorderex_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
 function print_sod($template='srl') {
    $db = mx_db_connect();

    $oid = $this->id;
    $stmt = 'SELECT "ID" from "xctorder" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("print12.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }


}

class xctorderex_edit extends simple_object_edit {
	function xctorderex_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorderex_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
 function anew_tweak($orig_id) {
		$this->data['procdate'] = mx_today_string();
	} 


	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];

		return simple_object_edit::commit($force);
	}
}
?>

