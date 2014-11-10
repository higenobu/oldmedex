<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-approve-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/injection-approve.php';

class injection_approve_application extends pharma_approve_application {

	var $application_order = 'injection';
	var $application_title = "注射承認記録";
	var $approve_references = "注射処方箋";
	var $approve_stores = "注射承認記録";

	function edit_class() {
		return new injection_approve_edit('soe-');
	}

}
