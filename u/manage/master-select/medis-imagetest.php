<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'Medis画像検査マスター',
	       'pk_name' => array('コード値'),
	       'accept_name' => '当院採用',
	       'column' => array("モダリティ",
				 "大分類",
				 "小分類",
				 "拡張",
				 "部位コード",
				 "部位複合名称"));

master_select_table($param);
?>
