<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdfkarte.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';

function __lib_u_doctor_pttest_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'pttest1',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'OrderDate',

 COLS => array(
 "OrderDate" ,
 
  "患者" ,
  "a0",
  "a1" ,
  "a2",
 
"a3" 
 ),

LCOLS => array(
array('Column' => 'OrderDate','Label' => '依頼日'),			      
//array('Column' => 'plandate','Label' => '予定日'),
//array('Column' => 'procdate','Label' => '変更日'),
//array('Column' => 'mlkubun','Label' => 'いつ'),

array('Column' => 'a0','Label' => '主食'),
array('Column' => 'a1','Label' => '分量'),
array('Column' => 'a2','Label' => '副食'),


array('Column' => 'a3')),



DCOLS => array(
array('Column' => 'OrderDate','Label' => '依頼日'),			      
//array('Column' => 'plandate','Label' => '予定日'),
//array('Column' => 'procdate','Label' => '変更日'),
//array('Column' => 'mlkubun','Label' => 'いつ'),

array('Column' => 'a0','Label' => '主食'),
array('Column' => 'a1','Label' => '分量'),
array('Column' => 'a2','Label' => '副食'),


array('Column' => 'a3')),


ECOLS => array(
array('Column' => 'OrderDate',
'Label' => '依頼日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),

array('Column' => 'a0',
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
array('Column' => 'a1',
					'Label' => '食種',
				    'Draw' => 'text'), 
 
array('Column' => 'a2',
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








 array('Column' => 'a3',
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

class list_of_pttests extends list_of_ppa_objects {
	function list_of_pttests($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_pttest_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class pttest_display extends simple_object_display {

	function pttest_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_pttest_cfg($cfg);
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



    $stmt = 'SELECT "ID" from "pttest1" WHERE "ObjectID"=' . $oid;
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

class pttest_edit extends simple_object_edit {
	function pttest_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_pttest_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
function anew_tweak($orig_id) {
		$this->data['OrderDate'] = mx_today_string();
	} 
function commit($force=NULL) {
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
		return simple_object_edit::commit($force);
	}


}
?>

