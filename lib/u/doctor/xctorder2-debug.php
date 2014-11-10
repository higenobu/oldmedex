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
    select "姓" || "名" as empname , userid
    from "職員台帳"
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
  "患者" ,
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
'Label' => '依頼日'),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'stop',
'Label' => '中止'),

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
					'Label' => '指示医',
				   
				       'Draw' => 'text',
				        

				       ),
/*
array('Column' => 'gishi',
					'Label' => '技師',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => '記録者',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_kiroku()

				       ),
*/


array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken3',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),



array('Column' => 'syoken5',
					'Label' => '方向',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => '実施・未・済'),



),

//
/*
LCOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日'),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'bui1',
'Label' => '部位１'),
array('Column' => 'bui2',
'Label' => '部位２'),
array('Column' => 'bui3',
'Label' => '部位３'),

array('Column' => 'bui4',
'Label' => '部位４'),
array('Column' => 'bui5',
'Label' => '部位５'),
array('Column' => 'proof',
'Label' => '実施・未・済')),

*/

//'DEFAULT_SORT' => 'orderdate',

//

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日'
),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'stop',
'Label' => '中止'),

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
					'Label' => '指示医',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '技師',
				   
				       'Draw' => 'enum',
				      'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),
array('Column' => "CreatedBy",
					'Label' => '記録者',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),

array('Column' => 'memo1',
'Label' => '指示'),
				      
array('Column' => 'memo4',
'Label' => '目的'),
array('Column' => 'memo2',
'Label' => '処方'),
array('Column' => 'memo3',
'Label' => '電圧その他'),




array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '技師コメント'),			      
array('Column' => 'drsyoken',
'Label' => '医師所見'),
array('Column' => 'proof',
'Label' => '実施・未・済')


),

//

ECOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日'
),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'stop',
'Label' => '中止'),

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
					'Label' => '指示医',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => '技師',
				   
				       'Draw' => 'enum',
				      'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),
array('Column' => "CreatedBy",
					'Label' => '記録者',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )

				       ),

array('Column' => 'memo1',
'Label' => '指示'),
				      
array('Column' => 'memo4',
'Label' => '目的'),
array('Column' => 'memo2',
'Label' => '処方'),
array('Column' => 'memo3',
'Label' => '電圧その他'),




array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => '方向',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => '技師コメント'),			      
array('Column' => 'drsyoken',
'Label' => '医師所見'),
array('Column' => 'proof',
'Label' => '実施・未・済')


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
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
		$this->data['orderdate'] = mx_now_string(); 
 $patient_objectid = $this->data['患者'];
		return simple_object_edit::commit($force);
	}
}
?>

