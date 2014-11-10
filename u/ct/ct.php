<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/ct/ct.php';

class ct_ct_application extends single_table_application {

	var $use_upload = 1;

	function list_of_objects($prefix) { // override
		return new list_of_ct_cts($prefix);
	}

	function object_display($prefix) { // override
		return new ct_ct_display($prefix);
	}

	function object_edit($prefix) { //override
		return new ct_ct_edit($prefix);
	}
}

$main = new ct_ct_application();
$main->main();
?>
