<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '新規文書型を登録する',
 'New Like This' => 'この文書型をコピーして新しい文書型を作る',
 'Edit' => 'この文書型を編集する',
 'Object Name' => '文書型',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/doctype.php';

$stm_list_of_objects = 'list_of_doctypes';
$stm_object_display = 'doctype_display';
$stm_object_edit = 'doctype_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
