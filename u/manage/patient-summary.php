<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-summary.php';

class patient_summary_print_application extends single_table_application {

	var $_loo_title = '[患者サマリーの出力]';
	// This is not a list but just something in the left upper pane.
	function list_of_objects($prefix) {
		$loo = new patient_summary_print_cfg($prefix);
		$loo->application = &$this;
		return $loo;
	}

	// Again this does not do any display; it just does csv.
	function object_display($prefix) {
		return new patient_summary_print($prefix);
	}

	function allow_new() {
		return NULL;
	}

}

$main = new patient_summary_print_application();
$main->main();
?>
