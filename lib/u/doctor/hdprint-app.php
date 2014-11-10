<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/hdorder.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/hdprint-form.php';

class hdprint_application extends single_table_application {

	var $_browse_only = 1; /* no New/Copy/Edit controls */

	/*
	 * The application states are:
	 * (1) asking for the date, defaulting to today;
	 * (2) got the date, showing the list of Pts and asking for
	 *     confirmation;
	 * (3) got the confirmation, open a new window that has
	 *     a frameset with "Print" and what is to be printed.
	 */

	function setup_widgets() {
		single_table_application::setup_widgets();

		$this->show_right = NULL;

		$o = array();
		foreach ($_REQUEST as $k => $v) {
			$m = array();
			if (!preg_match('/^HDOrderPicked(\d+)$/',
					$k, &$m))
				continue;
			$o[] = $m[1];
		}
		$this->orders_chosen = $o;

		if (mx_check_request('HDPrint')) {
			$date = mx_check_request('HDExecDate');
			$this->errs = mx_db_validate_date($date);
			if (!$this->errs) {
				$this->order_list = hdorder_list_orders($date);
				$this->state = 'hdprint_go';
				return;
			}
		}

		if (!mx_check_request('HDExecDate')) {
			$this->state = 'initial';
			return;
		}

		$date = mx_check_request('HDExecDate');
		$this->errs = mx_db_validate_date($date);
		if ($this->errs) {
			$this->state = 'initial';
			return;
		}

		foreach ($_REQUEST as $k => $v) {
			$m = array();
			if (preg_match('/^HDExamOrder-(\d+)$/', $k, &$m)) {
				$this->show_right = $m[1];
				continue;
			}
		}
		$this->order_list = hdorder_list_orders($date);
		$this->state = 'list';

	}

	function handle_confirmed_list() {

		$this->state = 'print';
		$date = mx_check_request('HDExecDate');
		$this->errs = mx_db_validate_date($date);
		if (!$this->errs)
			return $this->handle_picked_date($date);

	}

	function draw_error($error) {
		print '<br /><br /><span class="error-message">';
		print $error;
		print "</span><br /><br />\n";
	}

	function draw_title($title) {

		mx_titlespan($title);
		if ($this->errs) {
			$this->draw_error($this->errs);
		}

	}

	function draw_initial() {

		$date = mx_check_request('HDExecDate');
		if (trim($date) == '')
			$date = mx_today_string();

		$this->draw_title('透析実施日を選択');

		print "<div>\n実施日: ";
		mx_formi_date('HDExecDate', $date);
		print "</div>\n";
		mx_formi_submit('PickedDate', 'OK');

	}

	function draw_list() {

		if (mx_check_request('PickedDate'))
			$o = $orders = NULL;
		else {
			$o = $this->orders_chosen;
			$orders = implode(',', $o);
		}
		$xd = mx_check_request('HDExecDate');

		$this->draw_title('透析実施の確認');

		$l = $this->order_list;
		if (!is_array($l) || !count($l)) {
			$this->draw_error("指定された日の透析対象はありません");
			return;
		}

		hdorder_show_table_order($l, $o, 'HDExamOrder');

		mx_formi_hidden('HDExecDate', $xd);
		mx_formi_submit('HDPrint', '確認');

	}

	function hdprint_go() {

		$xd = mx_check_request('HDExecDate');
		$orders = implode(',', $this->orders_chosen);
		if ($orders)
			print <<<SCRIPT
<script>
window.open('hdprint-print.php?HDExecDate=$xd&HDOrders=$orders',
	    '', 'width=1000,height=640');
</script>
SCRIPT;
		else {
			mx_titlespan("オーダが一つも選択されていません");
			print "<br /><hr /><br />";
		}
		$this->draw_list();

	}

	/*
	 * This is not even the usual 3-pane application.
	 * We take over the whole lower pane and do our thing.
	 */
	function left_pane() {

		switch ($this->state) {
		case 'initial':
			return $this->draw_initial();
			break;
		case 'list':
			return $this->draw_list();
			break;
		case 'hdprint_go':
			return $this->hdprint_go();
			break;
		}

		print "NOT HANDLED";
	}

	function right_pane() {

		if (!$this->show_right)
			return;

		$rt = new hdorder_display('poo');
		$rt->reset($this->show_right);
		$rt->draw();

	}

}

?>