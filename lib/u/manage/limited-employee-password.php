<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/limited-employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-password.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class limited_employee_password_application extends employee_password_application {
	var $employee_list_class = 'list_of_limited_employees_for_password';
}

?>
