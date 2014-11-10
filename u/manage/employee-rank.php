<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '新規職位を登録する',
 'New Like This' => 'この職位をコピーして新しい職位を作る',
 'Edit' => 'この職位を編集する',
 'Object Name' => '職位',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-rank.php';

$stm_list_of_objects = 'list_of_employee_ranks';
$stm_object_display = 'employee_rank_display';
$stm_object_edit = 'employee_rank_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
