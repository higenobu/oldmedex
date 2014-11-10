<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/ms2-compat.php';

function config_tweak_disprefix($config, $param)
{
	$config['STMT_FURTHER_LIMIT'] = '"ÀÜÂ³°ÌÃÖ¶èÊ¬" >= 10';
	return $config;
}

$param = array('table_name' => 'MedisÉÂÌ¾½¤¾þ¸ì¥Þ¥¹¥¿¡¼',
	       'pk_name' => array('½¤¾þ¸ì´ÉÍýÈÖ¹æ'),
	       'accept_name' => 'Åö±¡ºÎÍÑ',
	       'column' => array('½¤¾þ¸ì´ÉÍýÈÖ¹æ', '½¤¾þ¸ìÉ½µ­'),
	       'config-tweak' => 'config_tweak_disprefix',
	       );

master_select_table($param);
?>
