<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

$__rcl_list_config = $__ppa_list_cfg;
$__rcl_list_config['HSTMT'] .= '
AND "ObjectID" NOT IN
    (SELECT "����" FROM "���մ���ɽ" WHERE "Superseded" IS NULL)
';
$__rcl_list_config['LLAYO'] =
_lib_so_zip_layo($__ppa_list_cfg['LCOLS'], array
		 ('���ԥޡ���', '����ID', '��̾', '�եꥬ��','����',
		  'ǯ��',  '�����象����'));
$__rcl_list_config['ENABLE_QBE'] =
_lib_so_zip_layo($__ppa_list_cfg['ENABLE_QBE'], array
		 (array('Column' => '����ID','Label' => 'PID',
			       'Option' => array('Class' => 'nowrap')),
array('Column' => '����','Label' => 'Gender',
			       'Option' => array('Class' => 'nowrap')),
array('Column' => '��','Label' => 'Last Name',
			       'Option' => array('Class' => 'nowrap')), ));

class reception_patient_list extends ppa_patient_list {
	function reception_patient_list($prefix, $lop_in_main=1) {
		global $__rcl_list_config;

		$cfg = $__rcl_list_config;
		$cfg['DEFAULT_QBE'] = array(array('�եꥬ��', ''));
		ppa_patient_list::ppa_patient_list($prefix, $lop_in_main, $cfg);
	}
}

$__rcl_checkin_list_config = $__ppa_checkin_list_cfg;
$__rcl_checkin_list_config['UNIQ_ID'] = 'C."ObjectID"';
$__rcl_checkin_list_config['LIST_IDS'][] = '���ջ���';
$__rcl_checkin_list_config['LIST_IDS'][] = 'ͽ�����';

if ($_mx_use_dept_in_reception) {
	$__rcl_checkin_list_config['LLAYO'] =
		_lib_so_zip_layo($__ppa_checkin_list_cfg['LCOLS'], array(
//				 ($__status,
				  '����ID', '��̾',  '��', '����̾',
				  '��Ū',  '���ջ���', 'ͽ�����',
				  '�����象����'));
} else {
	$__rcl_checkin_list_config['LLAYO'] =
		_lib_so_zip_layo($__ppa_checkin_list_cfg['LCOLS'], array(
//0115-2014				 ($__status,
				  '����ID', '��̾',   '��',
				  
				  '��Ū',  '���ջ���','ͽ�����',
				  '�����象����'));
}

class reception_checkin_list extends ppa_checkin_list {

	function reception_checkin_list($prefix, $lop_in_main=1) {
		global $__rcl_checkin_list_config;

		$cfg = $__rcl_checkin_list_config;
		ppa_checkin_list::ppa_checkin_list($prefix, $lop_in_main, $cfg);
	}

}

function _lib_u_reception_checkin_pt_has_appt($pt)
{
	global $__rcl_checkin_list_config;
	$stmt = ($__rcl_checkin_list_config['HSTMT'] .
		 ' AND C."����" = ' . mx_db_sql_quote($pt) .
		 ' ORDER BY C."ͽ�����"');
	$db = mx_db_connect();
	$r = mx_db_fetch_all($db, $stmt);
	if ($r && count($r) >= 1)
		return $r[0];
	return NULL;
}

function _lib_u_reception_checkin_pt($pt)
{
	global $__ppa_list_cfg;
	$stmt = ($__ppa_list_cfg['HSTMT'] .
		 ' AND "ObjectID" = ' . mx_db_sql_quote($pt));
	$db = mx_db_connect();
	$r = mx_db_fetch_all($db, $stmt);
	if ($r && count($r) >= 1)
		return $r[0];
	return NULL;
}
?>
