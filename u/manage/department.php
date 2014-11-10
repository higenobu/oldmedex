<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '新規部署を登録する',
 'New Like This' => 'この部署をコピーして新しい部署を作る',
 'Edit' => 'この部署を編集する',
 'Object Name' => '部署',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/department.php';

$stm_list_of_objects = 'list_of_departments';
$stm_object_display = 'department_display';
$stm_object_edit = 'department_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
