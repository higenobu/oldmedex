<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-approve-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/rx-approve.php';

class rx_approve_application extends pharma_approve_application {

	var $application_order = 'rx';
	var $application_title = "���޾�ǧ��Ͽ";
	var $approve_references = "���޽����";
	var $approve_stores = "���޾�ǧ��Ͽ";

	function edit_class() {
		return new rx_approve_edit('soe-');
	}

}
