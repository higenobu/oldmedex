<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/sorder.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rxclass.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_module.php';
function __lib_u_doctor_reorder_index_entry_cmp($a, $b) {
	if ($a['患者ID'] < $b['患者ID'])
		return -1;
	else if ($a['患者ID'] > $b['患者ID'])
		return 1;
	else if ($a['timestamp'] < $b['timestamp'])
		return 1;
	else if ($b['timestamp'] < $a['timestamp'])
		return -1;
	else
		return 0;
}

class reorder_application extends single_table_application {

	var $_browse_only = 1; // no "New" "Edit" etc controls please
	var $application_title = "再オーダ";
	var $app_ix_name = 'reorder';
//0328-2012
	var $default_all_select = 0;
	var $direct_go_application = 0;
	var $fixed_order_type = NULL;
//0328-2012
	var $use_choose_patients = 0;
	var $use_single_date = 0;
//0328-2012
	var $always_choose_order =1;

	function setup_apps() {
		/*
		 * NEEDSWORK: this need to be integrated with
		 * what index-pt.php does elsewhere...
		 *
		 * $this_app = $this->app_ix_name; e.g. 'reorder', 'print'
		 * $this->apps = __lib_u_everybody_index_pt__applist($app);
		 */
		return array('rx' => '処方箋',
			     'test' => '検査',
			     'stest' => '生理',
			     'procedure' => '処置',
				
			     'injection' => '注射' 
//05-02-2012
				  );
	}

	function get_apps() {
		if (!$this->__apps)
			$this->__apps = $this->setup_apps();
		return $this->__apps;
	}

	function setup_widgets () {
		global $_mx_no_choosepatients_in_reorder;

		$me = get_class($this);
		if (strstr(",$_mx_no_choosepatients_in_reorder,", ",$me,"))
			$this->use_choose_patients = 0;

		single_table_application::setup_widgets();
		$this->setup_application_widgets();
	}

	function setup_application_widgets () {
		if (mx_check_request('RedoOrders'))
			$this->redo_orders();
		else if (mx_check_request('ChooseOrders'))
			$this->setup_orders();
		else if (mx_check_request('ChoosePatients'))
			$this->setup_patients();
		else if (mx_check_request('ChooseAllPatients'))
			
			$this->setup_orders_all();
		else if ($this->direct_go_application)
			$this->setup_directgo();
		else
			$this->action = 'draw_initial';
	}

	function left_pane() {
		$action = $this->action;
		$this->$action();
	}

	function default_date_range() {
		return array('', mx_today_string());
	}

	function fmt_ts($ts) {
		$today = mx_today_string() . ' ';
		$len = strlen($today);
		$ts = mx_format_timestamp($ts, 0);
		if (substr($ts, 0, $len) == $today)
			$ts = substr($ts, $len);
		return $ts;
	}

	function draw_initial() {

		mx_titlespan("オーダ選択条件");

		print '<table class="listofstuff">';
//2012
//		$group = mx_dbenum_patientgroup();
//              $group = mx_dbenum_emp();
/*
		if ($group && count($group) > 2) {
			print '<tr><th>患者</th><td>';
			print '<select multiple name="LimitPatientGroup[]">';
			foreach ($group as $g => $l) {
				if (trim($g) == '')
					continue;
				print '<option value="';
				print htmlspecialchars($g);
				print '">';
				print htmlspecialchars($l);
				print '</option>';
			}
			print '</select>';
			print '</td></tr>';
		}
*/

		if ($this->use_single_date) {
			$default_date = $_REQUEST['LimitPatientDate'];
			if (!$default_date)
				$default_date = mx_today_string();
			print '<tr><th>日付指定</th><td>';
			mx_formi_date('LimitPatientDate', $default_date);
			print '</td></tr>';
		} else {
			$default_range = $this->default_date_range();
			$default_since = $_REQUEST['LimitPatientDateFrom'];
			if (!$default_since)
				$default_since = $default_range[0];
			$default_until = $_REQUEST['LimitPatientDateTo'];
			if (!$default_until)
				$default_until = $default_range[1];

			print '<tr><th>日付範囲指定</th><td>';
			mx_formi_date('LimitPatientDateFrom', $default_since);
			print 'から';
			mx_formi_date('LimitPatientDateTo', $default_until);
			print 'まで';
			print '</td></tr>';
		}

		if (!$this->fixed_order_type) {
			$apps = $this->get_apps();

			print '<tr><th>オーダ種別</th><td>';
			print '<select multiple name="LimitOrderType[]">';

			foreach ($apps as $name => $label) {
				print '<option value="';
				print htmlspecialchars($name);
				print '">';
				print htmlspecialchars($label);
				print '</option>';
			}
			print '</select>';
			print '</td></tr>';
		}

		print '</table>';

		if ($this->use_choose_patients)
			mx_formi_submit("ChoosePatients", "患者選択");
//0928-2011 オーダ選択
		mx_formi_submit("ChooseAllPatients", "患者すべて選択");
	}

	function options_to_collect() {
		$options = array();
		$options['OmitCancelled'] = 1;
		if ($this->fixed_order_type)
			$options['OrderType'] = $this->fixed_order_type;
		else if (is_array($_REQUEST['LimitOrderType']))
			$options['OrderType'] = $_REQUEST['LimitOrderType'];
		return $options;
	}

	function collect_patients($group_limit, $date_from, $date_to,
				  $options) {
		return index_pt_collect_patients($group_limit,
						 $date_from,
						 $date_to,
						 $this->app_ix_name,
						 $options);
	}

	function request_date_range() {
		$d = mx_check_request('LimitPatientDate');
		if (trim($d) != '')
			$date_from = $date_to = $d;
		$d = mx_check_request('LimitPatientDateFrom');
		if (trim($d) != '')
			$date_from = $d;
		$d = mx_check_request('LimitPatientDateTo');
		if (trim($d) != '')
			$date_to = $d;
		return array($date_from, $date_to);
	}

	function setup_patients () {
		$date_from = $date_to = NULL;

		$group_limit = array();
		if (array_key_exists('LimitPatientGroup', $_REQUEST) &&
		    is_array($_REQUEST['LimitPatientGroup']))
			$group_limit = $_REQUEST['LimitPatientGroup'];
		list($date_from, $date_to) = $this->request_date_range();
		$options = $this->options_to_collect();
		$this->patient = $this->collect_patients($group_limit,
							 $date_from,
							 $date_to,
							 $options);
		$this->action = 'draw_patients';
	}

	function limit_type_passthru() {
		if ($this->use_single_date) {
			$d = mx_check_request('LimitPatientDate');
			mx_formi_hidden('LimitPatientDate', $d);
		} else {
			$d = mx_check_request('LimitPatientDateFrom');
			mx_formi_hidden('LimitPatientDateFrom', $d);
			$d = mx_check_request('LimitPatientDateTo');
			mx_formi_hidden('LimitPatientDateTo', $d);
		}
		if (!$this->fixed_order_type && $_REQUEST['LimitOrderType'])
			foreach ($_REQUEST['LimitOrderType'] as $v)
				mx_formi_hidden('LimitOrderType[]', $v);
	}

	function draw_patients () {
		if (!$this->patient) {
			mx_titlespan("該当患者がありません");
			return;
		}
		mx_titlespan("患者選択確認");
		print '<table class="listofstuff">';
		$eo = 'e';
		foreach ($this->patient as $p) {
			$eo = $eo == 'e' ? 'o' : 'e';
			$o = $p['ObjectID'];
			$i = mx_form_encode_name($p['患者ID']);
			$n = mx_form_encode_name($p['姓名']);
			print "<tr class=\"$eo\"><td>";
			mx_formi_checkbox("RAPT-$o-$i-$n", 1);
			print "</td><td>";
			print htmlspecialchars($p['患者ID'] . ": " .
					       $p['姓名']);
			print "</td></tr>\n";
		}
		print "</table>\n";
		$this->limit_type_passthru();
		mx_formi_submit("ChooseOrders", "確認");
	}

	function setup_orders_all() {
		$this->setup_patients();

 return;
 /*
		if (!$this->patient)
			return;
		foreach ($this->patient as $p) {
			$o = $p['ObjectID'];
			$i = mx_form_encode_name($p['患者ID']);
			$n = mx_form_encode_name($p['姓名']);
			$_REQUEST["RAPT-$o-$i-$n"] = 1;
		}
		return $this->setup_orders();
 
*/


	}

	function setup_directgo() {
		$date_range = $this->default_date_range();
		if ($this->use_single_date) {
			$_REQUEST['LimitPatientDate'] = mx_today_string();
		} else {
			$_REQUEST['LimitPatientDateFrom'] = $date_range[0];
			$_REQUEST['LimitPatientDateTo'] = $date_range[1];
		}
		$this->setup_orders_all();
	}

	function setup_orders () {
		$patients = array();
		$patient_map = array();
		$patients_passthru = array();


		foreach ($_REQUEST as $k => $v) {
			$m = array();
			if (!preg_match('/^RAPT-([0-9]+)-([^-]*)-(.+)$/',
					$k, &$m))
				continue;
			$patients_passthru[] = $k;
			$o = $m[1];
			$i = mx_form_decode_name($m[2]);
			$n = mx_form_decode_name($m[3]);
			$patients[] = array($o, $i);
			$patient_map[$o] = $n;
		}

		list($df, $dt) = $this->request_date_range();
		if (trim($df) == '') $df = NULL;
		if (trim($dt) == '') $dt = NULL;

		$options = $this->options_to_collect();
		$result = index_pt_collect($patients, $df, $dt,
					   $this->app_ix_name,
					   $options);
		usort(&$result, '__lib_u_doctor_reorder_index_entry_cmp');
		$this->orders = $result;

		$this->patients_passthru = $patients_passthru;
		$this->patient_map = $patient_map;
		$this->action = 'draw_orders';
	}

	function draw_control_on_order_list() {
		print '日付: ';
		mx_formi_date('RAREDO-DATE', mx_today_string());
		foreach ($this->patients_passthru as $passthru) {
			mx_formi_hidden($passthru, 1);
		}
		mx_formi_submit("RedoOrders", "再オーダ");
	}

	function draw_one_order($o) {
		# show preformated html if available.
		if ($o['html']) {
			print "<td colspan=\"2\">";
			print $o['html'];
		} else {
			print "<td>";
			print htmlspecialchars($o['timestamp']);
			print "<br />";
			print htmlspecialchars($o['text']);
			print "</td><td>";
			print $o['fuller'];
		}
	}

	function draw_orders () {
		if (!$this->orders) {
			mx_titlespan("該当オーダがありません");
			return;
		}
		mx_titlespan($this->application_title);
		print '<table class="listofstuff">';

		$p = NULL;
		$eo = 'e';
		foreach ($this->orders as $o) {

			if ($o['患者ObjectID'] != $p) {
				$p = $o['患者ObjectID'];
				$i = $o['患者ID'];
				$n = $this->patient_map[$p];
				print '<tr class="y"><td colspan="3">';
				print htmlspecialchars("$i: $n");
				print "</td></tr>\n";
				$eo = 'e';
				$sel = 1;
			} else
				$sel = $this->default_all_select;

			$eo = $eo == 'e' ? 'o' : 'e';
			print "<tr class=\"$eo\">";
			if (!$this->always_choose_order) {
				print "<td>";
				$app = mx_form_encode_name($o['type']);
				$oid = $o['object_id'];
				mx_formi_checkbox("RAREDO-$oid-$app", $sel);
				print "</td>";
			}
			$this->draw_one_order($o);
			print "</td></tr>\n";
		}

		print "</table>\n";
		if ($this->always_choose_order) {
			foreach ($this->orders as $o) {
				$app = mx_form_encode_name($o['type']);
				$oid = $o['object_id'];
				mx_formi_hidden("RAREDO-$oid-$app", 1);
			}
		}
		$this->draw_control_on_order_list();
	}

	function redo_orders () {
		$d = $_REQUEST['RAREDO-DATE'];
		$e = mx_db_validate_date($d);
		if ($e) {
			$this->error = $e;
			$this->action = 'draw_redo_done';
			return;
		}
		$this->duplicate_date = $d;
		$patients = array();
		$patient_map = array();
		foreach ($_REQUEST as $k => $v) {
			$m = array();
			if (preg_match('/^RAPT-([0-9]+)-([^-]*)-(.+)$/',
				       $k, &$m)) {
				$o = $m[1];
				$i = mx_form_decode_name($m[2]);
				$n = mx_form_decode_name($m[3]);
				$patients[] = array($o, $i);
				$patient_map[$o] = $n;
				continue;
			}

			$m = array();
			if (!preg_match('/^RAREDO-([0-9]+)-(.+)$/',
					$k, &$m))
				continue;
			$oid = $m[1];
			$app = mx_form_decode_name($m[2]);
			if ($app == 'test_module_index_info' ||
			    $app == 'rx_module_index_info' ||
				
			    $app == 'injection_module_index_info' ||
				
			    $app == 'stest_module_index_info')
				;
			else
				continue;
			$this->redo($app, $oid);
		}
		$this->patients = $patients;
		$this->patient_map = $patient_map;
		$this->date = $d;
		$this->action = 'draw_redo_done';
	}

	function redo($app, $oid) {

		/* NEEDSWORK - more */
		$cls = array('test_module_index_info' => 'test_order_edit',
			     'rx_module_index_info' => 'rx_order_edit',
			     'injection_module_index_info' =>
			     'injection_order_edit',
			     'stest_module_index_info' => 'stest_order_edit');

		$cls = $cls[$app];
		if (!$cls)
			return;
		$cls = new $cls('dummy');
		$cls->duplicate($oid, array('DuplicateDate' => $this->duplicate_date));
	}

	function draw_redo_done() {
		if ($this->error) {
			mx_titlespan("エラー");
			print $this->error;
			return;
		}
		mx_titlespan("再オーダ完了しました");

		$options = $this->options_to_collect();
		$result = index_pt_collect($this->patients,
					   $this->date, $this->date,
					   $this->app_ix_name,
					   $options);
		usort(&$result, '__lib_u_doctor_reorder_index_entry_cmp');

		print '<table class="listofstuff">';

		$p = NULL;
		$eo = 'e';
		foreach ($result as $o) {

			if ($o['患者ObjectID'] != $p) {
				$p = $o['患者ObjectID'];
				$i = $o['患者ID'];
				$n = $this->patient_map[$p];
				print '<tr class="y"><td colspan="2">';
				print htmlspecialchars("$i: $n");
				print "</td></tr>\n";
				$eo = 'e';
				$sel = 1;
			} else
				$sel = 0;

			$eo = $eo == 'e' ? 'o' : 'e';
			print "<tr class=\"$eo\"><td>";
			print htmlspecialchars($o['timestamp']);
			print "<br />";
			print htmlspecialchars($o['text']);
			print "</td><td>";
			print $o['fuller'];
			print "</td></tr>\n";
		}

		print "</table>\n";


	}

}
?>
