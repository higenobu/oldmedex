<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppas.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/nagmail-ppas.php';

class nagmail_ppas_application extends per_patient_singleton_application {

	var $auto_use_lop = 'ppa_patient_list';

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_nagmail_ppases($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new nagmail_ppas_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new nagmail_ppas_edit($prefix, $cfg);
	}

}
?>
