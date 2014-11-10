<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/nagmail-template.php';

class nagmail_template_application extends single_table_application {

	function list_of_objects($prefix) {
		return new list_of_nagmail_templates($prefix, $this->purpose);
	}

	function object_display($prefix) {
		return new nagmail_template_display($prefix, $this->purpose);
	}

	function object_edit($prefix) {
		return new nagmail_template_edit($prefix, $this->purpose);
	}

}

class nagmail_APPT_MISSED_application extends nagmail_template_application {
	var $purpose = 'APPT_MISSED';
}

class nagmail_APPT_REMIND_application extends nagmail_template_application {
	var $purpose = 'APPT_REMIND';
}

class nagmail_RX_CONFIRM_RECOVERY_application extends nagmail_template_application {
	var $purpose = 'RX_CONFIRM_RECOVERY';
}

class nagmail_RX_MEDICATION_REMIND_application extends nagmail_template_application {
	var $purpose = 'RX_MEDICATION_REMIND';
}

?>
