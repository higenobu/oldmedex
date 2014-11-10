<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/doctor/diseasepick.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/ord_module.php';
function __lib_u_doctor_patient_disease_config(&$cfg)
{
	$acols = array(
		array('Column' => '記録日', 'Draw' => 'static'),
		array('Column' => '開始日', 'Draw' => 'date',
		      'Option' => array('validate' => 'nonnull')),
		array('Column' => '転帰日', 'Draw' => 'date'),
		array('Column' => '転帰', 'Draw' => 'enum',
		      'Enum' => array('' => '', '1' => '治癒',
				      '2' => '死亡', '3' => '中止'),
		      ),
		array('Column' => '主病名', 'Draw' => 'enum',
		      'Enum' => array('' => '', 'Y' => '〇')),
		array('Column' => '接頭語名',
		      'Label' => '接頭語',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '接頭語',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '接頭語設定',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '修飾語表記'),
		       'EnumCapable' => 1)),
		array('Column' => '接頭語名2',
		      'Label' => '接頭語(2)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '接頭語2',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '接頭語設定',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '修飾語表記'),
		       'EnumCapable' => 1)),
		array('Column' => '接頭語名3',
		      'Label' => '接頭語(3)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '接頭語3',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '接頭語設定',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '修飾語表記'),
		       'EnumCapable' => 1)),
		array('Column' => '接頭語名4',
		      'Label' => '接頭語(4)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '接頭語4',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '接頭語設定',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '修飾語表記'),
		       'EnumCapable' => 1)),

		array('Column' => '疾病名',
		      'Label' => '疾病',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'diseasepick',
		       'ObjectColumn' => '疾病',
		       'Config' => $_lib_u_doctor_diseasepick_dps_cfg,
		       'Message' => '病名設定',
		       'Allow_NULL' => 0,
		       'ListID' => array('ObjectID', '病名表記'),
		       'EnumCapable' => 1)),
		array('Column' => '疾病', 'Draw' => NULL),

		array('Column' => '接尾語名1',
		      'Label' => '接尾語',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_post',
		       'ObjectColumn' => '接尾語1',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '接尾語設定',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '修飾語表記'),
		       'EnumCapable' => 1)),
		array('Column' => '接尾語名2',
		      'Label' => '接尾語(2)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_post',
		       'ObjectColumn' => '接尾語2',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '接尾語設定',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '修飾語表記'),
		       'EnumCapable' => 1)),

		array('Column' => '接頭語', 'Draw' => NULL),
		array('Column' => '接頭語2', 'Draw' => NULL),
		array('Column' => '接頭語3', 'Draw' => NULL),
		array('Column' => '接頭語4', 'Draw' => NULL),
		array('Column' => '接尾語1', 'Draw' => NULL),
		array('Column' => '接尾語2', 'Draw' => NULL),

		array('Column' => '疑い', 'Draw' => 'enum',
		      'Enum' => array('' => '', 'Y' => '〇'),
		      ),
		array('Column' => 'コメント', 'Draw' => 'text'),

		array('Column' => '病院使用レセコン保険情報',
		      'Draw' => NULL,
		      'Option' => array('empty-is-null' => 1)),
		array('Column' => '病院使用レセコン受診科情報',
		      'Draw' => NULL,
		      'Option' => array('empty-is-null' => 1)),
		);
	$allow_sort = array();
	foreach (array('記録日', '開始日', '転帰日') as $df)
		$allow_sort[$df] = array($df => "\"$df\"");

	$lcols = array();
	foreach ($acols as $a) {
		if (array_key_exists('Draw', $a) && is_null($a['Draw']))
			continue;
		$lcols[] = $a;
	}
	$lcols[] = array('Column' => '一行病名',
			 'Label' => '病名',
			 'Draw' => 'text');
	$llayo = _lib_so_zip_layo(&$lcols,
				  array('記録日', '開始日', '転帰日',
					'転帰', '//',
					2, '一行病名', 'コメント'));
	$dcols = $acols;
	$ecols = $acols;
	$cols = array();
	foreach ($acols as $a) {
		$cols[] = $a['Column'];
	}
	$cols[] = '患者';
	$icols = $cols;

	$stmt = <<<SQL
		CASE WHEN ("接頭語名" IS NULL)
		THEN ''
		ELSE "接頭語名"
		END ||
		CASE WHEN ("接頭語名2" IS NULL)
		THEN ''
		ELSE "接頭語名2"
		END ||
		CASE WHEN ("接頭語名3" IS NULL)
		THEN ''
		ELSE "接頭語名3"
		END ||
		CASE WHEN ("接頭語名4" IS NULL)
		THEN ''
		ELSE "接頭語名4"
		END ||
		CASE WHEN ("疾病名" IS NULL)
		THEN ''
		ELSE "疾病名"
		END ||
		CASE WHEN ("接尾語名1" IS NULL)
		THEN ''
		ELSE "接尾語名1"
		END ||
		CASE WHEN ("接尾語名2" IS NULL)
		THEN ''
		ELSE "接尾語名2"
		END ||
		CASE WHEN ("主病名" = 'Y') THEN '[主病名]' ELSE '' END ||
		CASE WHEN ("疑い" = 'Y') THEN '[疑い]' ELSE '' END ||
		''
SQL;
	$synthetic = array('一行病名' => $stmt);

	$defaults = array(
		'TABLE' => '患者病名',
		'COLS'  => $cols,
		'SCOLS' => $synthetic,
		'LCOLS' => $lcols,
		'ECOLS' => $ecols,
		'ICOLS' => $icols,
		'DCOLS' => $dcols,
		'LLAYO' => $llayo,
		'ALLOW_SORT' => $allow_sort,
		'DEFAULT_SORT' => '記録日',
	);
	$cfg = array_merge($defaults, $cfg);
}

class list_of_patient_diseases extends list_of_ppa_objects {
	function list_of_patient_diseases($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_doctor_patient_disease_config(&$cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	function current_diseases() {
		$db = mx_db_connect();
		$stmt = $this->base_fetch_stmt();
		$stmt .= " AND \"転帰\" IS NULL";
		return mx_db_fetch_all($db, $stmt);
	}
}

class patient_disease_display extends simple_object_display {
	function patient_disease_display($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_doctor_patient_disease_config(&$cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class patient_disease_edit extends simple_object_ppa_edit {

	var $kick_claim_column = '記録日';
	var $reception_column_name = '患者受付情報';
	var $rececom_inscol_name = '病院使用レセコン保険情報';
	var $rececom_dptcol_name = '病院使用レセコン受診科情報';

	function patient_disease_edit($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_doctor_patient_disease_config(&$cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}

	function anew_tweak($orig_id) {
		simple_object_ppa_edit::anew_tweak($orig_id);
		$today = mx_today_string();
		$this->data['記録日'] = $today;
		$this->data['開始日'] = $today;
		$this->data['転帰日'] = NULL;
		$this->data['転帰'] = NULL;
	}

	function annotate_form_data(&$data) {
		simple_object_ppa_edit::annotate_form_data($data);
		$today = mx_today_string();
		if ($data['転帰'] != '' &&
		    trim($data['転帰日']) == '')
			$data['転帰日'] = $today;
		else if ($data['転帰'] == '')
			$data['転帰日'] = NULL;
		$data['記録日'] = $today;
	}

	function _validate($force=NULL) {
		$bad = 0;
		if (simple_object_ppa_edit::_validate($force) != 'ok')
			$bad = 1;
		$d =& $this->data;
		$err = array();
		if (trim($d['疾病']) == '' && trim($d['コメント']) == '') {
			$bad = 1;
			$this->err('疾病・コメントの両方とも空ではいけません');
		}

		if ($bad)
			return '';
		return 'ok';
	}


//10-25-2014
function commit($force=NULL) {
  

    $this->data['患者'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['記録日'];
 $patient_objectid = $this->data['患者'];
$byomei=$this->data["疾病名"];
$kaishi=$this->data['開始日'];
$tenkibi=$this->data['転帰日'];
$tenki=$this->data['転帰'];
$st=$this->data['接頭語名'];
$st2=$this->data['接頭語名2'];
$st3=$this->data['接頭語名3'];
$bi1=$this->data["接尾語名1"];
$bi2=$this->data["接尾語名2"];
 simple_object_edit::commit($force); 
  
    

 

$p_oid=$patient_objectid;
//$p_pid=$data["患者"];
$time_from=$date;
$time_to=$date;
//$returnv= disease_module_index_info
//(&$db, $p_oid, $p_pid, $time_from, $time_to, $options=NULL);

//print_r($returnv);
$ocont='記録日='.$date.'開始日='.$kaishi.'転帰日='.$tenkibi.'転帰='.$tenki."  ".$st." ".$st2." ".$byomei.$bi1.$bi2"\n";

print $ocont;
 
 
//new 10-24-2014
$stmt10 = <<<SQL
select * from "カルテデモ表" where "日付"='$date' and "患者"=$p_oid and 
	"Superseded" is null;
SQL;
 
 print $stmt10;

$rs0 = mx_db_fetch_single($db, $stmt10);
//print $rs0;
if ($rs0 == null){
$stmt11 = <<<SQL
INSERT INTO "カルテデモ表" ("患者", "日付","P") values ($p_oid,'$date','$ocont');
SQL;
 
print $stmt11;

if (pg_query($db, $stmt11)){

	}
else {
print '<p > DB access error</p>';
die;
	}

 }


 

else{ 	
$ocont2=$rs0["P"].'\r\n'.'-----(updated to)-----'.$ocont;
print "BBBBBBBBBBBBB".$ocont2;

$stmt1 = <<<SQL
   	update  "カルテデモ表" set "P"='$ocont2' where "日付"='$date' and "患者"=$p_oid and 
	"Superseded" is null; 
SQL;
print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > DB access error</p>';
die;
}

} //end else

 



 
/*
$con = $db;
$otype="disease";
$odate=$date;
$ptid=$p_oid;

$ocont="----------------------------\n"."病名\n".$ocont." |".$shiji." |";


$oid=99999;
 $stmt1 = <<<SQL
INSERT INTO orderinfo(
            orderdate, patient, 
            ordertype, "content",oid)
    
    VALUES ('$odate','$ptid', '$otype', '$ocont',$oid)
        
SQL;
//print $stmt1;
if (pg_query($con, $stmt1)){
//print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}


 */





 







}



}

?>
