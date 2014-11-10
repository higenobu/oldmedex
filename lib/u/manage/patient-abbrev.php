<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/patient-basic.php';

$_lib_u_manage_patient_abbrev_cfg = $_lib_u_manage_patient_basic_cfg;

function _lib_u_manage_patient_abbrev_tweak()
{
	global $_lib_u_manage_patient_abbrev_cfg;
	// 0 - read only, 1 - allow edit
	$keep = array("´µ¼ÔID" => 0,
		      "À«" => 0,
		      "Ì¾" => 0,
//		      "¥Õ¥ê¥¬¥Ê" => 0,
 		      "À­ÊÌ" => 0,
 		      "À¸Ç¯·îÆü" => 0,
		      "Æþ³°¶èÊ¬" => 1,
//		      "È¯¾ÉÆü" => 1,
		      "Æþ±¡Æü" => 1,
		      "Âà±¡Í½ÄêÆü" => 1,
		      "Âà±¡Í½Äê¡¦¸«¹þ" => 1,
 		      "È÷¹Í" => 1,
 		      "²óÉü´ü" => 1,
 		      "°å³ØÅªÉÔ°ÂÄê" => 1,
		      "´õË¾ÉÂÅï" => 1,
 		      "¥¢¥ì¥ë¥®¡¼" => 1,
 		      "´¶À÷¾É" => 1,
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
		array(array('Æþ³°¶èÊ¬', 'Æþ±¡ÂÔ¤Á'));
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
