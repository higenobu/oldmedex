<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/print-order-app.php';

class print_rx_order_application extends print_order_application {

	var $application_title = "½èÊý¥ê¥¹¥È°õºþ";
	var $use_choose_patients = 1;
	var $application_order = 'rx';
	var $me = 'print-rx-order.php';

	function print_rx_order_application() {
		$this->fixed_order_type = array($this->application_order);
		print_order_application::print_order_application();
	}

	function entry_rowspan() {
		return 2;
	}

	function draw_entry_seal_area($eo) {
		print '<td class="sealarea">Ä´ºÞ¼Ô°õ</td>';
		print '<td class="sealarea">´Æºº¼Ô°õ</td></tr>';
		print "<tr class=\"$eo\">";
		print '<td class="sealarea">¼õ¼è¼Ô°õ</td>';
		print '<td class="sealarea">¼Â»Ü¼Ô°õ</td></tr>';
	}

}
?>
