<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-execs-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/rx-execs.php';

class rx_execs_application extends pharma_execs_application {

	var $application_order = 'rx';
	var $application_title = "ÌôºÞ¼Â»Üµ­Ï¿";
	var $exec_references = "ÌôºÞ½èÊýäµ";
	var $exec_stores = "ÌôºÞ¼Â»Üµ­Ï¿";

	function rx_execs_application() {
		global $_mx_rx_multiple_executions_per_day;
		$this->use_multi_exec_per_day =
			$_mx_rx_multiple_executions_per_day;
		pharma_execs_application::pharma_execs_application();
	}

	function edit_class() {
		return new rx_execs_edit('soe-');
	}

	
}
