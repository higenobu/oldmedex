<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class medex_config_application extends single_table_application {

	function list_of_objects($prefix) {
		return new list_of_medex_configs($prefix);
	}
	function object_display($prefix) {
		return new medex_config_display($prefix);
	}
	function object_edit($prefix) {
		return new medex_config_edit($prefix);
	}
	function allow_new() {
		return 0;
	}
}

$it = new medex_config_application();
$it->main();
?>
