<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';

function _lib_u_manage_rx_temp_cfg_setup() {
	global $_lib_u_manage_rx_temp_cfg;
	$cols = array(
		"rx_id", "pt_num", "pt_last", "pt_first", "pt_kana", "nyugai", "indate", "outdate", 
       "room", "orderdate", "startdate"
		);
	$c = array(
		TABLE => 'rx_temp',
		COLS => array(
		"rx_id", "pt_num", "pt_last", "pt_first", "pt_kana", "nyugai", "indate", "outdate", 
       "room", "orderdate", "startdate"
		),
		LCOLS=> $cols,
		DCOLS => $cols,
		ECOLS => $cols,
		);


	$_lib_u_manage_rx_temp_cfg = $c;
}

_lib_u_manage_rx_temp_cfg_setup();

class list_of_rx_temps extends list_of_simple_objects {
	var $default_row_per_page = 4;
	function list_of_rx_temps($prefix, $cfg=NULL) {
		global $_lib_u_manage_rx_temp_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_rx_temp_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

class rx_temp_display extends simple_object_display {
	function rx_temp_display($prefix, $cfg=NULL) {
		global $_lib_u_manage_rx_temp_cfg;
		if (is_null($cfg))
			$cfg = $_lib_u_manage_rx_temp_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

?>
