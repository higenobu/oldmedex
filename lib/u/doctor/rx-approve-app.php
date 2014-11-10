<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-approve-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/rx-approve.php';

class rx_approve_application extends pharma_approve_application {

	var $application_order = 'rx';
	var $application_title = "泔管噩ロ筏狤";
	var $approve_references = "泔管質杅鉾";
	var $approve_stores = "泔管噩ロ筏狤";

	function edit_class() {
		return new rx_approve_edit('soe-');
	}

}
