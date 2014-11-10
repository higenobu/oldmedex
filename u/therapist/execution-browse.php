<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/execution-app.php';

$main = new therapist_execution_browse_application(array());
$main->main();
?>
