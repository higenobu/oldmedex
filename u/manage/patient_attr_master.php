<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array('Object Name' => '患者属性マスタ');

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient_attr_master.php';

$stm_list_of_objects = 'list_of_patient_attr_master';
$stm_object_display = 'patient_attr_master_display';
$stm_object_edit = 'patient_attr_master_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
