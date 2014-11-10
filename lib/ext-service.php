<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

/* List of external services */
function ext_service_list()
{
	global $_mx_product_name;

	if ($_mx_product_name == 'M3')
		return array('M3' => array('name' => 'M3'),
			     'So-Net' => array('name' => 'ソネット'));
	return array();
}

function ext_service_get($db, $u, $service)
{
	$stmt = sprintf("SELECT account, password, service FROM ".
			"mx_ext_service_account ".
			"WHERE localuser = %s AND service = %s",
			mx_db_sql_quote($u),
			mx_db_sql_quote($service));
	return mx_db_fetch_single($db, $stmt);
}

function ext_service_available($service)
{
	global $mx_authenticate_current_user;

	$db = mx_db_connect();
	$it = ext_service_get($db, $mx_authenticate_current_user, $service);
	if (!$it)
		return 0;
	return 1;
}
?>