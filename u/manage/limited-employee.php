<?php // -*- mode: php; coding: euc-japan -*-
$stm_msgs = array
(
 'New' => '¿·µ¬¿¦°÷¤òÅÐÏ¿¤¹¤ë',
 'New Like This' => '¤³¤Î¿¦°÷¤ò¥³¥Ô¡¼¤·¤Æ¿·¤·¤¤¿¦°÷¤òºî¤ë',
 'Edit' => '¤³¤Î¿¦°÷¤òÊÔ½¸¤¹¤ë',
 'History' => '¤³¤Î¿¦°÷¤ÎÊÑ¹¹ÍúÎò¤òÉ½¼¨¤¹¤ë',
 'History Prev' => '¤³¤Î¿¦°÷¤ÎÊÑ¹¹ÍúÎò¡¢ÀÎ¤Ø',
 'History Next' => '¤³¤Î¿¦°÷¤ÎÊÑ¹¹ÍúÎò¡¢ºÇ¶á¤Ø',
 'Object Name' => '¿¦°÷',
);

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/limited-employee.php';

$stm_list_of_objects = 'list_of_limited_employees';
$stm_object_display = 'employee_display';
$stm_object_edit = 'limited_employee_edit';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/single-table-manage.php';
?>
