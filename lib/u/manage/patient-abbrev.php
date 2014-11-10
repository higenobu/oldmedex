<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/patient-basic.php';

$_lib_u_manage_patient_abbrev_cfg = $_lib_u_manage_patient_basic_cfg;

function _lib_u_manage_patient_abbrev_tweak()
{
	global $_lib_u_manage_patient_abbrev_cfg;
	// 0 - read only, 1 - allow edit
	$keep = array("����ID" => 0,
		      "��" => 0,
		      "̾" => 0,
//		      "�եꥬ��" => 0,
 		      "����" => 0,
 		      "��ǯ����" => 0,
		      "������ʬ" => 1,
//		      "ȯ����" => 1,
		      "������" => 1,
		      "�ౡͽ����" => 1,
		      "�ౡͽ�ꡦ����" => 1,
 		      "����" => 1,
 		      "������" => 1,
 		      "���Ū�԰���" => 1,
		      "��˾����" => 1,
 		      "����륮��" => 1,
 		      "������" => 1,
		      );
	// Adjust DCOLS and ECOLS
	$dcols = array();
	foreach ($_lib_u_manage_patient_abbrev_cfg['DCOLS'] as $elem) {
		$name = is_array($elem) ? $elem['Column'] : $elem;
		if (!array_key_exists($name, $keep))
			continue;
		$dcols[] = $elem;
	}
	$_lib_u_manage_patient_abbrev_cfg['DCOLS'] = $dcols;

	$ecols = array();
	foreach ($_lib_u_manage_patient_abbrev_cfg['ECOLS'] as $elem) {
		$name = is_array($elem) ? $elem['Column'] : $elem;
		if (!array_key_exists($name, $keep))
			$elem = array('Column' => $name, 'Draw' => NULL);
		else if (!$keep[$name]) {
			if (!is_array($elem))
				$elem = array('Column' => $name,
					      'Draw' => 'static');
			else if ($elem['Draw'] == 'enum')
				$elem['Draw'] = 'static_enum';
			else
				$elem['Draw'] = 'static';
		}
		$ecols[] = $elem;
	}
	$_lib_u_manage_patient_abbrev_cfg['ECOLS'] = $ecols;
	$_lib_u_manage_patient_abbrev_cfg['DEFAULT_QBE'] =
		array(array('������ʬ', '�����Ԥ�'));
	unset($_lib_u_manage_patient_abbrev_cfg['DPAGES']);
	unset($_lib_u_manage_patient_abbrev_cfg['EPAGES']);
}

_lib_u_manage_patient_abbrev_tweak();
class list_of_patient_abbrevs extends list_of_patient_basics {
	function list_of_patient_abbrevs($prefix, $cfg=NULL) {
		global $_lib_u_manage_patient_abbrev_cfg;
		$cfg =& $_lib_u_manage_patient_abbrev_cfg;
		list_of_patient_basics::list_of_patient_basics($prefix, $cfg);
	}
}


class patient_abbrev_display extends patient_basic_display {
	function patient_abbrev_display($prefix, $cfg=NULL) {
		global $_lib_u_manage_patient_abbrev_cfg;
		$cfg =& $_lib_u_manage_patient_abbrev_cfg;
		patient_basic_display::patient_basic_display($prefix, $cfg);
	}
}

class patient_abbrev_edit extends patient_basic_edit {
	function patient_abbrev_edit($prefix, $cfg=NULL) {
		global $_lib_u_manage_patient_abbrev_cfg;
		$cfg =& $_lib_u_manage_patient_abbrev_cfg;
		patient_basic_edit::patient_basic_edit($prefix, $cfg);
	}
}
