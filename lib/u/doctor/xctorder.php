<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
function _lib_u_xct_kiroku() {
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
array('Column' => "CreatedBy",
					'Label' => '記録者',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_kiroku()

				       ),
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


'DEFAULT_SORT' => 'orderdate',

DCOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日'
),			      
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
'Label' => '技師所見'),			      
array('Column' => 'drsyoken',
'Label' => '医師所見'),
array('Column' => "CreatedBy",
					'Label' => '記録者',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_kiroku()

				       ),
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
'Option' => array('vocab' => array('え食事箋コメント'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => '処方',
				   
				       'Draw' => 'enum',
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
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 '単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 
 'その他' => 'その他'),
				    'Option' => array('validate' =>
							 'nonnull')),

array('Column' => 'memo11',
					'Label' => 'フィルムサイズ1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ１' => 'サイズ１',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo12',
					'Label' => '枚数１',
				    'Draw' => 'text'),

array('Column' => 'bui2',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 'その他' => 'その他'

						     )
				       ),
array('Column' => 'memo21',
					'Label' => 'フィルムサイズ2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ2' => 'サイズ2',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo22',
					'Label' => '枚数2',
				    'Draw' => 'text'),



array('Column' => 'bui3',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       '単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 'その他' => 'その他'

 )
),
array('Column' => 'memo31',
					'Label' => 'フィルムサイズ3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ3' => 'サイズ3',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo32',
					'Label' => '枚数3',
				    'Draw' => 'text'),


array('Column' => 'bui4',
					'Label' => '部位4',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 'その他' => 'その他'

						     )
				       ),

array('Column' => 'memo41',
					'Label' => 'フィルムサイズ4',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ4' => 'サイズ4',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo42',
					'Label' => '枚数4',
				    'Draw' => 'text'),



array('Column' => 'bui5',
					'Label' => '部位5',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      '単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 'その他' => 'その他'

 )
),
array('Column' => 'memo51',
					'Label' => 'フィルムサイズ5',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ5' => 'サイズ5',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'memo52',
					'Label' => '枚数5',
				    'Draw' => 'text'),




array('Column' => 'techsyoken',
'Label' => '技師所見',
'Draw' => 'textarea',
'Option' => array('vocab' => array('え食事箋コメント'),
'cols' => 80)
),



			      
array('Column' => 'drsyoken',
'Label' => '医師所見',
'Draw' => 'textarea',
'Option' => array('vocab' => array('え食事箋コメント'),
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

