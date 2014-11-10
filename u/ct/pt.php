<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/ct/pt.php';

class ct_pt_application extends single_table_application {

	var $use_upload = 1;
	var $_loo_title = '[治験プロトコール一覧表]';
	var $_sod_title = '[治験患者表示]';

	function allow_new() {
		return $this->loo->allow_new();
	}

	function list_of_objects($prefix) {
		return new list_of_ct_pts($prefix);
	}

	function object_display($prefix) {
		return new ct_pt_display($prefix);
	}

	function object_edit($prefix) {
		return new ct_pt_edit($prefix, &$this);
	}
}

$main = new ct_pt_application();
$main->main();
?>
