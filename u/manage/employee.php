<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '������������Ͽ����',
 'New Like This' => '���ο����򥳥ԡ����ƿ�������������',
 'Edit' => '���ο������Խ�����',
 'History' => '���ο������ѹ������ɽ������',
 'History Prev' => '���ο������ѹ������Τ�',
 'History Next' => '���ο������ѹ����򡢺Ƕ��',
 'Object Name' => '����',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';

$stm_list_of_objects = 'list_of_employees';
$stm_object_display = 'employee_display';
$stm_object_edit = 'employee_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
