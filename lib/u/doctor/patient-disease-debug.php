<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/doctor/diseasepick.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/ord_module.php';
function __lib_u_doctor_patient_disease_config(&$cfg)
{
	$acols = array(
		array('Column' => '��Ͽ��', 'Draw' => 'static'),
		array('Column' => '������', 'Draw' => 'date',
		      'Option' => array('validate' => 'nonnull')),
		array('Column' => 'ž����', 'Draw' => 'date'),
		array('Column' => 'ž��', 'Draw' => 'enum',
		      'Enum' => array('' => '', '1' => '����',
				      '2' => '��˴', '3' => '���'),
		      ),
		array('Column' => '����̾', 'Draw' => 'enum',
		      'Enum' => array('' => '', 'Y' => '��')),
		array('Column' => '��Ƭ��̾',
		      'Label' => '��Ƭ��',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '��Ƭ��',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '��Ƭ������',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '������ɽ��'),
		       'EnumCapable' => 1)),
		array('Column' => '��Ƭ��̾2',
		      'Label' => '��Ƭ��(2)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '��Ƭ��2',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '��Ƭ������',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '������ɽ��'),
		       'EnumCapable' => 1)),
		array('Column' => '��Ƭ��̾3',
		      'Label' => '��Ƭ��(3)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '��Ƭ��3',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '��Ƭ������',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '������ɽ��'),
		       'EnumCapable' => 1)),
		array('Column' => '��Ƭ��̾4',
		      'Label' => '��Ƭ��(4)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_pre',
		       'ObjectColumn' => '��Ƭ��4',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '��Ƭ������',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '������ɽ��'),
		       'EnumCapable' => 1)),

		array('Column' => '����̾',
		      'Label' => '����',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'diseasepick',
		       'ObjectColumn' => '����',
		       'Config' => $_lib_u_doctor_diseasepick_dps_cfg,
		       'Message' => '��̾����',
		       'Allow_NULL' => 0,
		       'ListID' => array('ObjectID', '��̾ɽ��'),
		       'EnumCapable' => 1)),
		array('Column' => '����', 'Draw' => NULL),

		array('Column' => '������̾1',
		      'Label' => '������',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_post',
		       'ObjectColumn' => '������1',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '����������',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '������ɽ��'),
		       'EnumCapable' => 1)),
		array('Column' => '������̾2',
		      'Label' => '������(2)',
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'dismodpick_post',
		       'ObjectColumn' => '������2',
		       'Config' => $_lib_u_doctor_dismodpick_dps_cfg,
		       'Message' => '����������',
		       'Allow_NULL' => 1,
		       'ListID' => array('ObjectID', '������ɽ��'),
		       'EnumCapable' => 1)),

		array('Column' => '��Ƭ��', 'Draw' => NULL),
		array('Column' => '��Ƭ��2', 'Draw' => NULL),
		array('Column' => '��Ƭ��3', 'Draw' => NULL),
		array('Column' => '��Ƭ��4', 'Draw' => NULL),
		array('Column' => '������1', 'Draw' => NULL),
		array('Column' => '������2', 'Draw' => NULL),

		array('Column' => '����', 'Draw' => 'enum',
		      'Enum' => array('' => '', 'Y' => '��'),
		      ),
		array('Column' => '������', 'Draw' => 'text'),

		array('Column' => '�±����ѥ쥻�����ݸ�����',
		      'Draw' => NULL,
		      'Option' => array('empty-is-null' => 1)),
		array('Column' => '�±����ѥ쥻������ǲʾ���',
		      'Draw' => NULL,
		      'Option' => array('empty-is-null' => 1)),
		);
	$allow_sort = array();
	foreach (array('��Ͽ��', '������', 'ž����') as $df)
		$allow_sort[$df] = array($df => "\"$df\"");

	$lcols = array();
	foreach ($acols as $a) {
		if (array_key_exists('Draw', $a) && is_null($a['Draw']))
			continue;
		$lcols[] = $a;
	}
	$lcols[] = array('Column' => '�����̾',
			 'Label' => '��̾',
			 'Draw' => 'text');
	$llayo = _lib_so_zip_layo(&$lcols,
				  array('��Ͽ��', '������', 'ž����',
					'ž��', '//',
					2, '�����̾', '������'));
	$dcols = $acols;
	$ecols = $acols;
	$cols = array();
	foreach ($acols as $a) {
		$cols[] = $a['Column'];
	}
	$cols[] = '����';
	$icols = $cols;

	$stmt = <<<SQL
		CASE WHEN ("��Ƭ��̾" IS NULL)
		THEN ''
		ELSE "��Ƭ��̾"
		END ||
		CASE WHEN ("��Ƭ��̾2" IS NULL)
		THEN ''
		ELSE "��Ƭ��̾2"
		END ||
		CASE WHEN ("��Ƭ��̾3" IS NULL)
		THEN ''
		ELSE "��Ƭ��̾3"
		END ||
		CASE WHEN ("��Ƭ��̾4" IS NULL)
		THEN ''
		ELSE "��Ƭ��̾4"
		END ||
		CASE WHEN ("����̾" IS NULL)
		THEN ''
		ELSE "����̾"
		END ||
		CASE WHEN ("������̾1" IS NULL)
		THEN ''
		ELSE "������̾1"
		END ||
		CASE WHEN ("������̾2" IS NULL)
		THEN ''
		ELSE "������̾2"
		END ||
		CASE WHEN ("����̾" = 'Y') THEN '[����̾]' ELSE '' END ||
		CASE WHEN ("����" = 'Y') THEN '[����]' ELSE '' END ||
		''
SQL;
	$synthetic = array('�����̾' => $stmt);

	$defaults = array(
		'TABLE' => '������̾',
		'COLS'  => $cols,
		'SCOLS' => $synthetic,
		'LCOLS' => $lcols,
		'ECOLS' => $ecols,
		'ICOLS' => $icols,
		'DCOLS' => $dcols,
		'LLAYO' => $llayo,
		'ALLOW_SORT' => $allow_sort,
		'DEFAULT_SORT' => '��Ͽ��',
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
		$stmt .= " AND \"ž��\" IS NULL";
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

	var $kick_claim_column = '��Ͽ��';
	var $reception_column_name = '���Լ��վ���';
	var $rececom_inscol_name = '�±����ѥ쥻�����ݸ�����';
	var $rececom_dptcol_name = '�±����ѥ쥻������ǲʾ���';

	function patient_disease_edit($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_doctor_patient_disease_config(&$cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}

	function anew_tweak($orig_id) {
		simple_object_ppa_edit::anew_tweak($orig_id);
		$today = mx_today_string();
		$this->data['��Ͽ��'] = $today;
		$this->data['������'] = $today;
		$this->data['ž����'] = NULL;
		$this->data['ž��'] = NULL;
	}

	function annotate_form_data(&$data) {
		simple_object_ppa_edit::annotate_form_data($data);
		$today = mx_today_string();
		if ($data['ž��'] != '' &&
		    trim($data['ž����']) == '')
			$data['ž����'] = $today;
		else if ($data['ž��'] == '')
			$data['ž����'] = NULL;
		$data['��Ͽ��'] = $today;
	}

	function _validate($force=NULL) {
		$bad = 0;
		if (simple_object_ppa_edit::_validate($force) != 'ok')
			$bad = 1;
		$d =& $this->data;
		$err = array();
		if (trim($d['����']) == '' && trim($d['������']) == '') {
			$bad = 1;
			$this->err('���¡������Ȥ�ξ���Ȥ���ǤϤ����ޤ���');
		}

		if ($bad)
			return '';
		return 'ok';
	}


//10-25-2014
function commit($force=NULL) {
  

    $this->data['����'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['��Ͽ��'];
 $patient_objectid = $this->data['����'];
$byomei=$this->data["����̾"];
$kaishi=$this->data['������'];
$tenkibi=$this->data['ž����'];
$tenki=$this->data['ž��'];
$st=$this->data['��Ƭ��̾'];
$st2=$this->data['��Ƭ��̾2'];
$st3=$this->data['��Ƭ��̾3'];
$bi1=$this->data["������̾1"];
$bi2=$this->data["������̾2"];
 simple_object_edit::commit($force); 
  
    

 

$p_oid=$patient_objectid;
//$p_pid=$data["����"];
$time_from=$date;
$time_to=$date;
//$returnv= disease_module_index_info
//(&$db, $p_oid, $p_pid, $time_from, $time_to, $options=NULL);

//print_r($returnv);
$ocont='��Ͽ��='.$date.'������='.$kaishi.'ž����='.$tenkibi.'ž��='.$tenki."  ".$st." ".$st2." ".$byomei.$bi1.$bi2"\n";

print $ocont;
 
 
//new 10-24-2014
$stmt10 = <<<SQL
select * from "����ƥǥ�ɽ" where "����"='$date' and "����"=$p_oid and 
	"Superseded" is null;
SQL;
 
 print $stmt10;

$rs0 = mx_db_fetch_single($db, $stmt10);
//print $rs0;
if ($rs0 == null){
$stmt11 = <<<SQL
INSERT INTO "����ƥǥ�ɽ" ("����", "����","P") values ($p_oid,'$date','$ocont');
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
   	update  "����ƥǥ�ɽ" set "P"='$ocont2' where "����"='$date' and "����"=$p_oid and 
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

$ocont="----------------------------\n"."��̾\n".$ocont." |".$shiji." |";


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
