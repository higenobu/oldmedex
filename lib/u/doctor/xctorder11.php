<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
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
/*array('Column' => 'xctkubun',
'Label' => 'CXT-Kubun'),*/
array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170001910' => 'X',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),



/* array('Column' => 'bui1',
'Label' => '部位１'),*/

array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 '002000004' => '単純頭部',
 '002000005' => '単純胸部',
 
 '002000001' => '胸部',
 '002000002' => '腹部',
 '002000003' => '頭部',
 
 'その他' => 'その他')),
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
/* array('Column' => 'xctkubun',
'Label' => 'XCT-KUBUN'), */
array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170001910' => 'X',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),



array('Column' => 'memo1',
'Label' => '指示'),

array('Column' => 'memo2',
'Label' => '処方'),

/* array('Column' => 'bui1',
'Label' => '部位１'),*/


array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 '002000004' => '単純頭部',
 '002000005' => '単純胸部',
 
 '002000001' => '胸部',
 '002000002' => '腹部',
 '002000003' => '頭部',
 
 'その他' => 'その他')),

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

array('Column' => 'xctkubun',
					'Label' => 'XCT-Kubun',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170001910' => 'X',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),


 array('Column' => 'memo1',
					'Label' => '指示',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => '処方',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'イオバミロン300' => 'イオバミロン３００',
'オムニバーグ240' => 'オムニバーグ240',
'ガストログラフィン' => 'ガストログラフィン',
'バリトゲン３００' => 'バリトゲン３００',
'バレックスモルトＳ' => 'バレックスモルトＳ',
'バリエネマ３００' => 'バリエネマ３００',
'ビリスコビンＤＩＣ５０' => 'ビリスコビンＤＩＣ５０',
'その他の造影剤' => 'その他の造影剤',
'輸液点滴セット（ディスポ）' => '輸液点滴セット（ディスポ）',

'翼状針' => '翼状針',

'生食注' => '生食注',
'スポラミン注シリンジ（20ml/1ml）' => 'スポラミン注シリンジ（20ml/1ml）',
'グルカゴン' => 'グルカゴン',
						     )
				       ),


array('Column' => 'bui1',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 '002000004' => '単純頭部',
 '002000005' => '単純胸部',
 
 '002000001' => '胸部',
 '002000002' => '腹部',
 '002000003' => '頭部',
 
 'その他' => 'その他'),
				    'Option' => array('validate' =>
							 'nonnull')),

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
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'002000001' => '単純頭部',
 '002000002' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '002000001' => '胸部',
 '002000002' => '腹部',
 '002000003' => '頭部',
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
'股関節正面像' => '股関節正面像',
'股関節斜位像' => '股関節斜位像',
 'その他' => 'その他'

						     )
				       ),
array('Column' => 'memo21',
					'Label' => 'フィルムサイズ2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '01' => '半切（デジタルフィルム）',
'02' => '大四つ切（デジタルフィルム）',
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
'股関節正面像' => '股関節正面像',
'股関節斜位像' => '股関節斜位像',
 'その他' => 'その他'

 )
),
array('Column' => 'memo31',
					'Label' => 'フィルムサイズ3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
  '01' => '半切（デジタルフィルム）',
'02' => '大四つ切（デジタルフィルム）',
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
'股関節正面像' => '股関節正面像',
'股関節斜位像' => '股関節斜位像',
 'その他' => 'その他'

						     )
				       ),

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
'股関節正面像' => '股関節正面像',
'股関節斜位像' => '股関節斜位像',
 'その他' => 'その他'

 )
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

/* function print_sod() {
    go_pdf($this->id, 0);
  }


} */

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


function commit($force=NULL) {
  

    $this->data['患者'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['procdate'];
 $patient_objectid = $this->data['患者'];
/* 0408-2011*/

 simple_object_edit::commit($force); 
  
    

/* always claim_request */

$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";

print $stmt;
  
	 pg_query($db, $stmt); 



     
  }



 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	
/* 0407-2011 change  
function commit($force=NULL) {
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
	

		return simple_object_edit::commit($force);
	}  */

}
?>

