<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '新規職員を登録する',
 'New Like This' => 'この職員をコピーして新しい職員を作る',
 'Edit' => 'この職員を編集する',
 'History' => 'この職員の変更履歴を表示する',
 'History Prev' => 'この職員の変更履歴、昔へ',
 'History Next' => 'この職員の変更履歴、最近へ',
 'Object Name' => '職員',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/limited-employee.php';

$stm_list_of_objects = 'list_of_limited_employees';
$stm_object_display = 'employee_display';
$stm_object_edit = 'limited_employee_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
