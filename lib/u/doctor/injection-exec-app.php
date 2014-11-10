<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-exec-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/injection-exec.php';

class injection_exec_application extends pharma_exec_application {

	var $application_order = 'injection';
	var $application_title = "��ͼ»ܵ�Ͽ";
	var $exec_references = "��ͽ��������";
	var $exec_stores = "��ͼ»ܵ�Ͽ";

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
		$name = trim($med['�쥻�ץ��Ż����������ƥ������̾']);
		$xage = array();
		if ($med['��ˡ'])
			$xage[] = $med['��ˡ'];
		if ($med['�����ˡ'])
			$xage[] = $med['�����ˡ'];
		if ($med['�굻'])
			$xage[] = $med['�굻'];
		$usage = implode("��", $xage);
		return array('drugname' => $name,
			     'usage' => $usage,
			     'days' => $med['����']);
	}

}
