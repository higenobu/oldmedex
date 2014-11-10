<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/status-app.php';

if ($_mx_use_checked_in_patient_status) {
	$main = new reception_status_finish_encounter();
} else {
	$main = new everybody_finish_encounter_application();
}
$main->main();
?>
