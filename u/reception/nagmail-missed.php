<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/nagmail-app.php';

$main = new appt_nagmail_missed_application();
$main->main();
?>
