<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/nagmail-template-app.php';

$it = new nagmail_APPT_REMIND_application();
$it->main();
?>
