<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
/*
function _lib_u_xct_get_kiroku() {
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

*/

function _lib_u_xct_get_bui() {





  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."bui_code" as id ,  E.bui_id as buiid, E.bui_name as name
    from bui_master4 E 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
{
    if ($row['buiid']>300) {
    $ret[$row['id']] = "CT".$row['name'];}
else {
	 $ret[$row['id']] = $row['name'];
	}
}

  return $ret;


}
/*

//0615-2011 fro shiji
function _lib_u_xct_get_shiji() {
 


  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."id" as id ,  "name" as name
    from modalities E where rtype=904
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;


}


//0617-2011 fro gishi
function _lib_u_xct_get_gishi() {
  





  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."id" as id ,  "name" as name
    from modalities E where rtype= 905 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}

//**********************************

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
*/

function __lib_u_doctor_xctorder2_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'xctorder',
'ALLOW_SORT' =>1,

 COLS => array(
	
 "orderdate" ,
 "plandate",
 "procdate" ,
  "����" ,
"stop",
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
"shiji",
"gishi",

"proof" 
 ),
//
LCOLS => array(

array('Column' => 'orderdate',
'Label' => '������'),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => 'stop',
'Label' => '���'),

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
'170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),



array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),
/*
array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_kiroku()

				       ),
*/


array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken3',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),



array('Column' => 'syoken5',
					'Label' => '����',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => '�»ܡ�̤����'),



),

//
/*
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
'Label' => '�»ܡ�̤����')),

*/

//'DEFAULT_SORT' => 'orderdate',

//

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '������'
),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => 'stop',
'Label' => '���'),

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				      'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),

array('Column' => 'memo1',
'Label' => '�ؼ�'),
				      
array('Column' => 'memo4',
'Label' => '��Ū'),
array('Column' => 'memo2',
'Label' => '����'),
array('Column' => 'memo3',
'Label' => '�Ű�����¾'),




array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '���ե�����'),			      
array('Column' => 'drsyoken',
'Label' => '��ս긫'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')


),

//

ECOLS => array(
array('Column' => 'orderdate',
'Label' => '������'
),			      
array('Column' => 'plandate',
'Label' => 'ͽ����'),
array('Column' => 'procdate',
'Label' => '�»���'),
array('Column' => 'stop',
'Label' => '���'),

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => '�ؼ���',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '����',
				   
				       'Draw' => 'enum',
				      'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),
array('Column' => "CreatedBy",
					'Label' => '��Ͽ��',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),

array('Column' => 'memo1',
'Label' => '�ؼ�'),
				      
array('Column' => 'memo4',
'Label' => '��Ū'),
array('Column' => 'memo2',
'Label' => '����'),
array('Column' => 'memo3',
'Label' => '�Ű�����¾'),




array('Column' => 'bui1',
					'Label' => '����1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '����2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '����3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '����4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '����5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '����',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '���ե�����'),			      
array('Column' => 'drsyoken',
'Label' => '��ս긫'),
array('Column' => 'proof',
'Label' => '�»ܡ�̤����')


),

//

//


), $cfg);
	return $cfg;
}

class list_of_xctorder2s extends list_of_ppa_objects {
	function list_of_xctorders($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder2_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class xctorder2_display extends simple_object_display {
	function xctorder2_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder2_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class xctorder2_edit extends simple_object_edit {
	function xctorder2_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder2_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
		$this->data['orderdate'] = mx_now_string(); 
 $patient_objectid = $this->data['����'];
		return simple_object_edit::commit($force);
	}
}
?>

