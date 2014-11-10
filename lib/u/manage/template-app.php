<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/template.php';

class template_application extends single_table_application {

	var $use_single_pane = 1;

	function list_of_objects($prefix) {
		return new list_of_templates($prefix, $this->application);
	}

	function object_display($prefix) {
		$d = new template_display($prefix, $this->application);
		$d->containing_application = &$this;
		return $d;
	}

	function object_edit($prefix) {
		return new template_edit($prefix, $this->application);
	}

}

class karte_template_application extends template_application {
	var $application = 'u/doctor/karteview.php';
}

?>
