<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/medis-medicine.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class medis_medicine_application extends single_table_application {

	var $_browse_only = 1;

	function list_of_objects($prefix) {
		return new list_of_medis_medicines($prefix);
	}

	function object_display($prefix) {
		return new medis_medicine_display($prefix);
	}
}

$app = new medis_medicine_application();
$app->main();
?>
