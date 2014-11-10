<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'Medis病名マスター',
	       'pk_name' => array('病名管理番号'),
	       'accept_name' => '当院採用',
	       'column' => array('病名表記',
			         '病名交換用コード',
				 'ICD10',
				 'ICD10確度'));
master_select_table($param);
?>
