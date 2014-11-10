<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => ' 登録する',
 'New Like This' => ' コピー ',
 'Edit' => ' 編集する',
 'Object Name' => 'xct',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/xct-rank.php';

$stm_list_of_objects = 'list_of_xct_ranks';
$stm_object_display = 'xct_rank_display';
 $stm_object_edit = 'xct_rank_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
