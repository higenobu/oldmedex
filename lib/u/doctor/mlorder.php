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
  "患者" ,
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
array('Column' => 'orderdate','Label' => '依頼日'),			      
array('Column' => 'plandate','Label' => '予定日'),
array('Column' => 'procdate','Label' => '変更日'),
array('Column' => 'mlkubun','Label' => 'いつ'),

array('Column' => 'syusyoku','Label' => '主食'),
array('Column' => 'sbunryo','Label' => '分量'),
array('Column' => 'fukusyoku','Label' => '副食'),

array('Column' => 'fbunryo','Label' => '分量'),
array('Column' => 'syokusyu','Label' => '食種'),
array('Column' => 'memo1','Label' => 'メモ'),
array('Column' => 'kinsyoku', 'Label' => '禁食'),
array('Column' => 'memo2', 'Label' => 'アレルギー等'),

array('Column' => 'proof')),



DCOLS => array(
array('Column' => 'orderdate','Label' => '依頼日'),			      
array('Column' => 'plandate','Label' => '予定日'),
array('Column' => 'procdate','Label' => '変更日'),
array('Column' => 'mlkubun','Label' => 'いつ'),

array('Column' => 'syusyoku','Label' => '主食'),
array('Column' => 'sbunryo','Label' => '分量'),
array('Column' => 'fukusyoku','Label' => '副食'),

array('Column' => 'fbunryo','Label' => '分量'),
array('Column' => 'syokusyu','Label' => '食種'),
array('Column' => 'memo1','Label' => 'メモ'),
array('Column' => 'kinsyoku', 'Label' => '禁食'),
array('Column' => 'memo2','Label' => 'アレルギー等'),
array('Column' => 'proof')),


ECOLS => array(
array('Column' => 'orderdate',
'Label' => '依頼日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
array('Column' => 'plandate',
'Label' => '予定日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => '変更日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),
array('Column' => 'mlkubun',
					'Label' => 'いつ',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '朝' => '朝',
'昼' => '昼',
'夕' => '夕',
' 検査食止め' => '検査食止め',
'検査食待ち' => '検査食待ち',
'外泊' => '外泊',
'外出' => '外出',
)
 ),
array('Column' => 'syokusyu',
					'Label' => '食種',
				    'Draw' => 'text'), 
 
array('Column' => 'syusyoku',
					'Label' => '主食',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'ご飯' => 'ご飯',
'おかゆ' => 'おかゆ',
 'うどん' => 'うどん',
'パン' => 'パン',
 'おにぎり' => 'おにぎり',
'その他' => 'その他',
						     )
				       ),



array('Column' => 'sbunryo',
					'Label' => '分量',
				   
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
					'Label' => '副食',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '普通' => '普通',
'その他' => 'その他',
						      
 '一口大' => '一口大',
'キザミ' => 'キザミ',
'やわらか食' => 'やわらか食',
'ミキサー' => 'ミキサー',
 )
 ),
array('Column' => 'fbunryo',
					'Label' => '分量',
				   
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
	      'Label' => '禁食',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('え食事箋コメント'),
				'cols' => 80)
	      ),

				   
array('Column' => 'memo2',
	      'Label' => '備考',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('え食事箋コメント'),
				'cols' => 80)
	      ),

 array('Column' => 'proof',
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       '医師' => '医師',
						       '管理者' => '管理者',
						       '未承認' => '未承認'
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
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
		return simple_object_edit::commit($force);
	}


}
?>

