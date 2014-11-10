<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-password.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

$application = new employee_password_application();
$application->main();
?>
