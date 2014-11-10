<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/limited-employee-password.php';

$application = new limited_employee_password_application();
$application->main();
?>
