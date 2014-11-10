<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-exec-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/injection-exec.php';

class injection_exec_application extends pharma_exec_application {

	var $application_order = 'injection';
	var $application_title = "注射実施記録";
	var $exec_references = "注射処方箋内容";
	var $exec_stores = "注射実施記録";

	function injection_exec_application() {
		global $_mx_injection_multiple_executions_per_day;
		$this->use_multi_exec_per_day =
			$_mx_injection_multiple_executions_per_day;
		pharma_exec_application::pharma_exec_application();
	}

	function edit_class() {
		return new injection_exec_edit('soe-');
	}

	function med_info($med) {
		$name = trim($med['レセプト電算処理システム医薬品名']);
		$xage = array();
		if ($med['用法'])
			$xage[] = $med['用法'];
		if ($med['注射用法'])
			$xage[] = $med['注射用法'];
		if ($med['手技'])
			$xage[] = $med['手技'];
		$usage = implode("・", $xage);
		return array('drugname' => $name,
			     'usage' => $usage,
			     'days' => $med['日数']);
	}

}
