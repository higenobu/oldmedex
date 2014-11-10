<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'Medis処置マスター',
	       'pk_name' => array('管理番号'),
	       'accept_name' => '当院採用',
	       'column' => array('マスター名称',
			         '学会英名',
				 '参考名称',
				 '参考英名',
				 '基準名称案'));
master_select_table($param);
?>
