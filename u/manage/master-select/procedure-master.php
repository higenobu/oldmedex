<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'procedure_master',
	       'pk_name' => array('ObjectID'),
	       'accept_name' => '��������',
	       'column' => array('Name', 'Search', '�쥻�Ż�������'),
	       );
master_select_table($param);
?>
