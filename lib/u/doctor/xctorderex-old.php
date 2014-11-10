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
'Label' => '作成日'),			      
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
'Label' => '承認')
),


DCOLS => array(
array('Column' => 'orderdate',
'Label' => '作成日'),			      
array('Column' => 'plandate',
'Label' => '予定日'),
array('Column' => 'procdate',
'Label' => '実施日'),
array('Column' => 'memo1',
'Label' => '指示'),

array('Column' => 'memo2',
'Label' => '処方'),

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
array('Column' => 'techsyoken',
'Label' => '技師コメント'),			      
array('Column' => 'drsyoken',
'Label' => '医師所見'),
array('Column' => 'proof',
'Label' => '承認')
),


ECOLS => array(
array('Column' => 'orderdate',
'Label' => '作成日',
				       'Draw' => 'static'
				      ),
array('Column' => 'plandate',
'Label' => '実施予定日',
				       'Draw' => 'static',
				       ),

array('Column' => 'procdate',
'Label' => '実施日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),



 array('Column' => 'memo1',
					'Label' => '指示',
				    'Draw' => 'textarea'),
array('Column' => 'memo2',
					'Label' => '処方',
				   
				       'Draw' => 'static',
				       'Enum' => array('' => '',
						      
 'イオベリン300シリンジ100ｍｌ生食１００ｍｌ' => 'イオベリン300シリンジ100ｍ生食１００ｍｌ',
'オムニバーグ300シリンジ' => 'オムニバーグ300シリンジ',
'ガストログラフィン' => 'ガストログラフィン',
'バリトゲン３００' => 'バリトゲン３００',
'バレックスモルトＳ' => 'バレックスモルトＳ',
'バリエネマ３００' => 'バリエネマ３００',
'ビリスコビンＤＩＣ５０' => 'ビリスコビンＤＩＣ５０',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'static'
),

array('Column' => 'memo11',
					'Label' => 'フィルムサイズ1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '01' => '半切（デジタルフィルム）',
'02' => '大四つ切（デジタルフィルム）',
'四つ切（アナログフィルム）' => '四つ切（アナログフィルム）',
'六つ切り（アナログフィルム）' => '六つ切り（アナログフィルム）',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo12',
					'Label' => '枚数１',
				    'Draw' => 'text'),

array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'static'
				       ),

array('Column' => 'memo21',
					'Label' => 'フィルムサイズ2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '半切（デジタルフィルム）' => '半切（デジタルフィルム）',
'大四つ切（デジタルフィルム）' => '大四つ切（デジタルフィルム）',
'四つ切（アナログフィルム）' => '四つ切（アナログフィルム）',
'六つ切り（アナログフィルム）' => '六つ切り（アナログフィルム）',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo22',
					'Label' => '枚数2',
				    'Draw' => 'text'),



array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'static'
),
array('Column' => 'memo31',
					'Label' => 'フィルムサイズ3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '半切（デジタルフィルム）' => '半切（デジタルフィルム）',
'大四つ切（デジタルフィルム）' => '大四つ切（デジタルフィルム）',
'四つ切（アナログフィルム）' => '四つ切（アナログフィルム）',
'六つ切り（アナログフィルム）' => '六つ切り（アナログフィルム）',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo32',
					'Label' => '枚数3',
				    'Draw' => 'text'),


array('Column' => 'bui4',
					'Label' => '部位4',
				       'Draw' => 'static'),
array('Column' => 'memo41',
					'Label' => 'フィルムサイズ4',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '半切（デジタルフィルム）' => '半切（デジタルフィルム）',
'大四つ切（デジタルフィルム）' => '大四つ切（デジタルフィルム）',
'四つ切（アナログフィルム）' => '四つ切（アナログフィルム）',
'六つ切り（アナログフィルム）' => '六つ切り（アナログフィルム）',
'その他' => 'その他'
						     )
				       ),



array('Column' => 'memo42',
					'Label' => '枚数4',
				    'Draw' => 'text'),



array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'static'
),
array('Column' => 'memo51',
					'Label' => 'フィルムサイズ5',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '半切（デジタルフィルム）' => '半切（デジタルフィルム）',
'大四つ切（デジタルフィルム）' => '大四つ切（デジタルフィルム）',
'四つ切（アナログフィルム）' => '四つ切（アナログフィルム）',
'六つ切り（アナログフィルム）' => '六つ切り（アナログフィルム）',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo52',
					'Label' => '枚数5',
				    'Draw' => 'text'),




array('Column' => 'techsyoken',
'Label' => '技師コメント',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)

),


			      
array('Column' => 'drsyoken',
					'Label' => '医師所見',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

 array('Column' => 'proof',
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       '実施済' => '実施済',
'医師実施' => '医師実施',
						       '技師実施' => '管理実施',
'未承認' => '未承認',
						       '承認' => '承認'
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
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];

		return simple_object_edit::commit($force);
	}
}
?>

