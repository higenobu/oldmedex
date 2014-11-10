<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms5-compat.php';

$param = array('table_name' => 'investt',
	       'pk_name' => array('inventid'),
	       'accept_name' => 'Åö±¡ºÎÍÑ',
	       'column' => array('inventname',
			         'inventcode',
				 'value'
				 ));
master_select_table($param);
?>
