<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/quickxray-app.php';

$main = new quickxray_read_only_application();
$main->main();
?>
