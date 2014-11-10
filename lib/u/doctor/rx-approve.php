<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function __lib_u_doctor_rx_approve_config(&$cfg)
{
	$acols = array(
		array('Column' => '¾µÇ§Æü'),
		array('Column' => '¾µÇ§¼Ô'),
		array('Column' => 'ÌôºÞ½èÊýäµ'),
		array('Column' => '¾µÇ§'),
		array('Column' => 'ÆÃµ­»ö¹à'),
		);
	foreach ($acols as $a) {
		$cols[] = $a['Column'];
	}
	$defaults = array(
		'TABLE' => 'ÌôºÞ¾µÇ§µ­Ï¿',
		'COLS' => $cols,
		'LCOLS' => $cols,
		'DCOLS' => $acols,
		'ECOLS' => $acols,
		);
	$cfg = array_merge($defaults, $cfg);
}

class rx_approve_edit extends simple_object_edit {
	var $debug = 1;

	function rx_approve_edit($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_doctor_rx_approve_config(&$cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
}
?>
