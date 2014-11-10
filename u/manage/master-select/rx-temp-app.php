<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/mx3-compat.php';

$param = array('table_name' => 'rx_temp',
	       'pk_name' => array('rx_id'),
	       'accept_name' => array('selected'),
	       'column' => array(rx_id, pt_num, pt_last, pt_first, pt_kana, nyugai, indate, outdate, 
       room, orderdate, startdate,selected
				 ),
	       
		       ),
);

master_select_table($param,
		    $__uiconfig_ms_qbe_enum_medicine,
		    $__uiconfig_ms_header_fields_medicine);
?>
