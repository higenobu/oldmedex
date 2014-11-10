<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '新規職種を登録する',
 'New Like This' => 'この職種をコピーして新しい職種を作る',
 'Edit' => 'この職種を編集する',
 'Object Name' => '職種',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-cat.php';

$stm_list_of_objects = 'list_of_employee_cats';
$stm_object_display = 'employee_cat_display';
$stm_object_edit = 'employee_cat_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
