<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

$__ra_patient_list_config = $__ppa_list_cfg;
$__ra_patient_list_config['ENABLE_QBE'] = array();
foreach ($__ppa_list_cfg['ENABLE_QBE'] as $item) {
	if ($item['Column'] == '����ID' ||
	    $item['Column'] == '�եꥬ��' ||
		$item['Column'] == '��̳��̾' ||
$item['Column'] == '���ԥ��롼��' ||
	    $item['Column'] == '��ǯ����') {
		$__ra_patient_list_config['ENABLE_QBE'][] = $item;
	}
}

class appt_patient_list extends ppa_patient_list {
	function appt_patient_list($prefix, $lop_in_main=NULL) {
		global $__ra_patient_list_config;
		global $_mx_appt_app_start_with_qbe;
//0701-2011
$_mx_appt_app_start_with_qbe='N';

		$cfg = $__ra_patient_list_config;

		if ($_mx_appt_app_start_with_qbe == 'Y') 
			$cfg['DEFAULT_QBE'] = array(array('�եꥬ��', ''));
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

$__ra_dr_config = array(
	'TABLE' => '������Ģ',
	'HSTMT' => '
SELECT	E."ObjectID", X.modality, M.name AS "��̾", R.name AS "����"
FROM	"������Ģ" AS E
JOIN modalities_to_medex_employee AS X ON X.employee = E."ObjectID"
JOIN modalities AS M ON M.id = X.modality
JOIN resource_type AS R ON M.rtype = R.id
JOIN "�������ɽ" AS C ON E."����" = C."ObjectID"
WHERE NULL IS NULL',

	'LLAYO' => array('��̾', '����'),
	'LIST_IDS' => array('modality', "��̾"),
);
$__ra_dr_config['STMT'] = $__ra_dr_config['HSTMT'] .
' AND E."Superseded" IS NULL';

class appt_dr_list extends list_of_simple_objects {

	function appt_dr_list($prefix) {
		global $__ra_dr_config;
		$cfg = $__ra_dr_config;
		$this->patient = NULL;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function set_pt_doctor() {
		global $__ra_dr_config;

		if (!$this->patient)
			return NULL;
		$d = mx_find_dr_for_patient($this->patient);
		if (!$d) /* hack */
			return $this->select_first(1);
		else if (count($d) != 1)
			return NULL;
		$stmt = $__ra_dr_config['STMT'] . ' AND E."ObjectID" = '.
			$d[0]['ObjectID'];
		$db = mx_db_connect();
		$row = mx_db_fetch_single($db, $stmt);
		if ($row)
			return $this->set_selection($row);
		return NULL;
	}

	function reset($to=NULL) {
		global $__ra_dr_config;
		global $mx_authenticate_current_user;

		if (is_null($to)) {
			/*
			 * Am I a modality?  If so, pick myself.
			 */
			$stmt = $__ra_dr_config['STMT'] . ' AND E.userid = ' .
				mx_db_sql_quote($mx_authenticate_current_user);
			$db = mx_db_connect();
			$row = mx_db_fetch_single($db, $stmt);
			if ($row)
				return $this->set_selection($row);

			/*
			 * Does the patient have doctor?
			 */
			$r = $this->set_pt_doctor();
			if ($r)
				return $r;
		}
		return list_of_simple_objects::reset($to);
	}
}

$__ra_nondr_config = array(
	'TABLE' => 'modalities',
	'HSTMT' => '
SELECT	M.id as modality, M.name AS "̾��", R.name AS "����"
FROM modalities AS M
JOIN resource_type AS R ON M.rtype = R.id
WHERE M.id NOT IN (
	SELECT X.modality FROM modalities_to_medex_employee X
) AND NOT EXISTS (
	SELECT * FROM modality_capacity
	WHERE capacity = 0 AND modality = M.id
)
',
	'UNIQ_ID' => 'M.id',
	'LLAYO' => array('����', '̾��'),
	'LIST_IDS' => array('modality', '̾��'),
);
$__ra_nondr_config['STMT'] = $__ra_nondr_config['HSTMT'];

if ($_mx_custom_nondr_appt) {
	$__ra_nondr_config['LLAYO'] = array('̾��');
}

class appt_nondr_list extends list_of_simple_objects {
	function appt_nondr_list($prefix) {
		global $__ra_nondr_config;
		$cfg = $__ra_nondr_config;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

?>
