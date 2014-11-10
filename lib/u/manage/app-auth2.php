<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext-service.php';

$__lib_u_manage_app_auth__applink_names = array(
	'T' => 'トップレベル',
	'S' => 'common', 
	'M' => 'nanage', 
	'1' => '調査・測定', 
	'2' => 'perform', 
	'C' => 'カンファレンス', 
	'3' => '計画', 
	'4' => '指示', 
	'5' => '準備', 
	'6' => 'execution', 
	'7' => '結果', 
	'8' => 'doc',
	'9' => '治験',
	'D' => 'デモ',
	'E' => '今日の診療',
);

if ($_mx_bmd_layout) {
	$__lib_u_manage_app_auth__applink_names['N'] = 'nurse';
}

$__lib_u_manage_app_auth__app_related =
array('u/manage/patient-abbrev.php' =>
      array('u/manage/floor-ed.php' => 1),
      'u/manage/floor-ed.php' =>
      array('u/manage/patient-abbrev.php' => 1));

function check_special_auth($u, $path)
{
	global $_mx_product_name;
	if ($_mx_product_name == 'M3') {
		if ($path == 'u/ext/m3.php') {
			if (!ext_service_available('So-Net'))
				return 1;
		}
	}
	return 0;
}

// If $sublevel is false, list top-level applications.
// otherwise, list sub-level applications.
function mx_find_application($u, $sublevel=NULL)
{
	global $__lib_u_manage_app_auth__applink_names;

	if ($sublevel == 'M') {
		$level_limit = 'P.sortorder < 0';
		$order_by = 'P.sortorder DESC';
	} else if ($sublevel == 'A') {
		$level_limit = 'TRUE';
		$order_by = 'abs(P.sortorder)';
	} else {
		$level_limit = '0 < P.sortorder';
		$order_by = 'P.sortorder';
	}
	foreach ($__lib_u_manage_app_auth__applink_names as $k => $v) {
		$cat_limit[] = mx_db_sql_quote($k);
	}
	$cat_limit = ' AND P.category IN (' . implode(', ', $cat_limit) . ')';

	$stmt = ('SELECT P.category, P.sortorder, P.name, P.path,' .
		 ' P.abbrev, P.disamb, P.ppa ' .
		 'FROM mx_application as P ' .
		 'JOIN mx_authorization as X ' .
		 'ON P."ObjectID" = X.appid ' .
		 'JOIN "職員台帳" as E ' .
		 'ON E."職種" = X."職種" AND E."職位" = X."職位" ' .
		 ' AND E."Superseded" IS NULL ' .
		 'WHERE ' . $level_limit . ' AND E.userid = ' .
		 mx_db_sql_quote($u) .
		 $cat_limit .
		 ' ORDER BY ' . $order_by);
	$result = array();
	$data = pg_fetch_all(pg_query($stmt));
	if (is_array($data)) {
		foreach ($data as $d) {
			if (check_special_auth($u, $d['path']))
				continue;
			if (!array_key_exists($d['category'], $result))
				$result[$d['category']] = array();
			$result[$d['category']][] = $d;
		}
	}
	return $result;
}

function mx_filter_accessible_application($candidates, $u=NULL)
{
	global $mx_authenticate_current_user;

	if (is_null($u))
		$u = $mx_authenticate_current_user;
	$avail = mx_find_application($u);
	$result = array();
	foreach ($avail as $category => $list) {
		foreach ($list as $data) {
			$path = $data['path'];
			if (array_search($path, $candidates) === false)
				continue;
			$result[] = $data;
		}
	}
	return $result;
}

?>
