<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/reorder-app.php';

class pharma_exec_application extends reorder_application {

	// abstract
	// application_order, application_title, exec_references and
	// exec_stores must be set by subclasses.

	var $app_ix_name = 'reorder';
	var $default_all_select = 1;
	var $fixed_order_type = NULL;
	var $use_choose_patients = 0;
	var $use_single_date = 0;
	var $always_choose_order = 1;
	var $use_multi_exec_per_day = 0;

	function pharma_exec_application() {
		$this->fixed_order_type = array($this->application_order);
		reorder_application::reorder_application();
	}

	function draw_control_on_order_list() {
		foreach ($this->patients_passthru as $passthru) {
			mx_formi_hidden($passthru, 1);
		}
		$this->limit_type_passthru();
		if ($this->shown_orders)
			mx_formi_submit('RecordExecution', "記録");
	}

	function med_info($med) {
		// abstract
	}

	function edit_class() {
		// abstract
	}

	function fmt_one_med($med) {
		$info = $this->med_info($med);
		$name = $info['drugname'];
		$xage = array();
		if (trim($info['usage']) != '')
			$xage[] = trim($info['usage']);
		if (trim($info['days']) != '')
			$xage[] = trim($info['days']) . '日分';
		$usage = implode("・", $xage);
		return "$name<br />$usage";
	}

	function draw_one_med($order_id, $order, $med) {
		$oid = $med['medid'];
		$limit = $med['一日当り回数'];
		if (!$limit || !$this->use_multi_exec_per_day)
			$limit = 1;

		print $this->fmt_one_med($med);

		$current = array();
		if (array_key_exists($oid, $this->execution_check))
			$current = $this->execution_check[$oid];
		$count = count($current);
		for ($ix = 0; $ix < $limit; $ix++) {
			print "<br /><hr />\n";
			if ($ix < $count) {
				$check = $current[$ix];
				$ts = $this->fmt_ts($check['recorded']);
				print "〇実施済 ($ts 記録)";
				$special = $check['特記事項'];
				if (trim($special) != '') {
					print "<br />　特記事項:";
					print htmlspecialchars($special);
				}
				continue;
			}
			$control = "ILID-$order_id-$oid";
			mx_formi_checkbox($control, 1);
			print "実施<br />\n";
			print "　特記事項:";
			mx_formi_text("ILIN-$order_id-$oid", '');
			$this->shown_orders++;
			break;
		}
	}

	function check_executions() {
		$date = mx_db_sql_quote(mx_check_request('LimitPatientDate'));
		$opid = array();
		$ref = $this->exec_references;
		$store = $this->exec_stores;
		foreach ($this->orders as $o) {
			foreach ($o['OrderPieces'] as $med) {
				$opid[] = $med['medid'];
			}
		}
		if (count($opid)) {
			$opid = implode(', ', $opid);
			$opid = "AND \"$ref\" IN ($opid)";
		} else {
			$opid = '';
		}
		$stmt = <<<SQL
			SELECT "ObjectID", "実施日", "実施者",
			"$ref", "特記事項", recorded
			FROM "$store"
			WHERE "Superseded" IS NULL
			$opid
			AND "実施日" = $date
			ORDER BY "$ref", recorded
SQL;

		$dbh = mx_db_connect();
		$exec = pg_fetch_all(pg_query(&$dbh, $stmt));
		if (!$exec) {
			$this->execution_check = array();
			return;
		}
		$check = array();
		foreach ($exec as $it) {
			$ident = $it[$ref];
			if (!array_key_exists($ident, $check))
				$check[$ident] = array();
			$check[$ident][] = $it;
		}
		$this->execution_check = $check;
	}

	function draw_one_order($o) {
		$order_id = $o['object_id'];

		if (!$this->execution_check)
			$this->check_executions();

		print "<td>";
		print htmlspecialchars($o['timestamp']);
		print " <br />";
		print htmlspecialchars($o['text']);
		print "</td><td>";

		$hr = '';
		foreach ($o['OrderPieces'] as $med) {
			print "$hr";
			$this->draw_one_med($order_id, $o, $med);
			$hr = "<br /><hr />"; 
		}
	}

	function options_to_collect() {
		$option = reorder_application::options_to_collect();
		$option['ReturnOrderPieces'] = 1;
		$option['LimitWithRxRange'] = 1;
		return $option;
	}

	function setup_application_widgets () {
		if (mx_check_request('RecordExecution')) {
			$this->record_execution();
		} else {
			reorder_application::setup_application_widgets();
		}
	}

	function record_one($date, $oid, $opid, $note) {
		$ref = $this->exec_references;
		$soe = $this->edit_class();
		$soe->anew(NULL);
		$soe->data = array('実施日' => $date,
				   '実施者' => $this->whoami,
				   $ref => $opid,
				   '実施' => 'Y',
				   '特記事項' => $note);
		$soe->commit();
	}

	function record_execution() {
		$patients = array();
		$patient_map = array();
		$ordids = array();
		$ordpieces = array();
		$modinfo = $this->application_order . '_module_index_info';
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
			if (preg_match('/^RAREDO-([0-9]+)-(.+)$/',
					$k, &$m)) {
				$oid = $m[1];
				$app = mx_form_decode_name($m[2]);
				if ($app != $modinfo)
					continue;
				$ordids[$oid] = 1;
			} else if (preg_match('/^ILID-([0-9]+)-([0-9]+)$/',
					      $k, &$m)) {
				$oid = $m[1];
				$opid = $m[2];
				if (!array_key_exists($oid, $ordpieces))
					$ordpieces[$oid] = array();
				$ordpieces[$oid][$opid] = 1;
			}
		}
		$date = mx_check_request('LimitPatientDate');
		$qdate = mx_db_sql_quote($date);
		$exec = array();
		$dbh = mx_db_connect();
		$this->dbh = &$dbh;
		$this->executed = 0;
		$this->whoami = $this->auth[2]['ObjectID'];
		foreach ($ordpieces as $k => $v) {
			if (!array_key_exists($k, $ordids))
				continue;
			foreach ($v as $opid => $done) {
				$note = $_REQUEST["ILIN-$k-$opid"];
				$exec[] = array($k, $opid, $note);
				$this->record_one($date, $oid, $opid, $note);
				$this->executed++;
			}
		}
		$this->exec = $exec;
		$this->action = 'draw_execution';
	}

	function draw_execution() {
		$date = mx_check_request('LimitPatientDate');

		if ($this->executed)
			print "記録しました。";
		print "<!--\n";
		foreach ($this->exec as $v) {
			print "execute on $date $v[0] $v[1] $v[2]\n";
		}
		print "-->\n";

	}

}
?>
