<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '新規を登録する',
 'New Like This' => 'コピーして新しい職位を作る',
 'Edit' => '編集する',
 'Object Name' => '職位',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/insident2.php';

$stm_list_of_objects = 'list_of_insident2';
$stm_object_display = 'insident2_display';
  $stm_object_edit = 'insident2_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
