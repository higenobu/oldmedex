<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';

function __lib_u_doctor_xctorder_cfg(&$cfg)
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
  "患者" ,
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
'Label' => '依頼日'),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'bui1',
'Label' => '部位１'),

array('Column' => 'proof',
'Label' => '実施・未・済')),


'DEFAULT_SORT' => 'orderdate',

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日'
),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'xctkubun',
'Label' => 'X/CT'),

array('Column' => 'memo1',
'Label' => '指示'),

array('Column' => 'memo2',
'Label' => '処方'),

array('Column' => 'bui1',
'Label' => '部位１'),
array('Column' => 'memo11',
'Label' => 'size'),
array('Column' => 'memo12',
'Label' => 'page'),


array('Column' => 'techsyoken',
'Label' => '技師コメント'),			      
array('Column' => 'drsyoken',
'Label' => '医師所見'),
array('Column' => 'proof',
'Label' => '実施・未・済')
),


ECOLS => array(
array('Column' => 'orderdate','Label' => '依頼日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'plandate',
'Label' => '実施予定日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '実施日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),



 array('Column' => 'memo1',
					'Label' => '指示',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => '処方',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),

array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'static'
),

array('Column' => 'memo11',
					'Label' => 'フィルムサイズ1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '半切（デジタルフィルム）' => '半切（デジタルフィルム）',
'大四つ切（デジタルフィルム）' => '大四つ切（デジタルフィルム）',
'四つ切（アナログフィルム）' => '四つ切（アナログフィルム）',
'六つ切り（アナログフィルム）' => '六つ切り（アナログフィルム）',

'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo12',
					'Label' => '枚数１',
				    'Draw' => 'text'),



array('Column' => 'drsyoken',
'Label' => '医師所見',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

array('Column' => 'techsyoken',
'Label' => '技師コメント',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)
),


 array('Column' => 'proof',
'Label' => '実施・未・済・承認',

				       'Draw' => 'enum',
				       'Enum' => array('未実施' => '未実施',
						       '医師実施' => '医師実施',
						       '技師実施' => '技師実施',
						       '医師承認' => '医師承認'
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
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
	/*	$this->data['orderdate'] = mx_now_string(); */

		return simple_object_edit::commit($force);
	}
}
?>

