<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array('Object Name' => '患者グループ');
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patientgroup.php';

$stm_list_of_objects = 'list_of_patientgroups';
$stm_object_display = 'patientgroup_display';
$stm_object_edit = 'patientgroup_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
