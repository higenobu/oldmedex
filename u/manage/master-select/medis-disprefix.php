<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

function config_tweak_disprefix($config, $param)
{
	$config['STMT_FURTHER_LIMIT'] = '"��³���ֶ�ʬ" >= 10';
	return $config;
}

$param = array('table_name' => 'Medis��̾������ޥ�����',
	       'pk_name' => array('����������ֹ�'),
	       'accept_name' => '��������',
	       'column' => array('����������ֹ�', '������ɽ��'),
	       'config-tweak' => 'config_tweak_disprefix',
	       );

master_select_table($param);
?>
