<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

$param = array('table_name' => 'Medis��̾�ޥ�����',
	       'pk_name' => array('��̾�����ֹ�'),
	       'accept_name' => '��������',
	       'column' => array('��̾ɽ��',
			         '��̾���ѥ�����',
				 'ICD10',
				 'ICD10����'));
master_select_table($param);
?>
