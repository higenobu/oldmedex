<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-exec-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/rx-exec.php';

class rx_exec_application extends pharma_exec_application {

	var $application_order = 'rx';
	var $application_title = "���޼»ܵ�Ͽ";
	var $exec_references = "���޽����";
	var $exec_stores = "���޼»ܵ�Ͽ";

	function rx_exec_application() {
		global $_mx_rx_multiple_executions_per_day;
		$this->use_multi_exec_per_day =
			$_mx_rx_multiple_executions_per_day;
		pharma_exec_application::pharma_exec_application();
	}

	function edit_class() {
		return new rx_exec_edit('soe-');
	}

	
}
