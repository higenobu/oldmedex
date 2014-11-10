<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/exec-print.php';

class therapist_exec_print_application extends single_table_application {

	var $_loo_title = '[出力設定]';
	// This is not a list but just something in the left upper pane.
	function list_of_objects($prefix) {
		$loo = new therapist_exec_print_cfg($prefix);
		$loo->application = &$this;
		return $loo;
	}

	// Again this does not do any display; it just does csv.
	function object_display($prefix) {
		return new therapist_exec_print($prefix);
	}

	function allow_new() {
		return NULL;
	}

}

$main = new therapist_exec_print_application();
$main->main();
?>
