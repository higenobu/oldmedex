<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function __lib_u_doctor_injection_approve_config(&$cfg)
{
	$acols = array(
		array('Column' => '��ǧ��'),
		array('Column' => '��ǧ��'),
		array('Column' => '��ͽ����'),
		array('Column' => '��ǧ'),
		array('Column' => '�õ�����'),
		);
	foreach ($acols as $a) {
		$cols[] = $a['Column'];
	}
	$defaults = array(
		'TABLE' => '��;�ǧ��Ͽ',
		'COLS' => $cols,
		'LCOLS' => $cols,
		'DCOLS' => $acols,
		'ECOLS' => $acols,
		);
	$cfg = array_merge($defaults, $cfg);
}

class injection_approve_edit extends simple_object_edit {
	var $debug = 1;

	function injection_approve_edit($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_doctor_injection_approve_config(&$cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
}
?>
