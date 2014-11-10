<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pharma-approves-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/rx-approves.php';

class rx_approves_application extends pharma_approves_application {

	var $application_order = 'rx';
	var $application_title = "泔管噩ロ筏狤";
	var $approve_references = "泔管質杅鉾";
	var $approve_stores = "泔管噩ロ筏狤";

	function edit_class() {
		return new rx_approves_edit('soe-');
	}

}
