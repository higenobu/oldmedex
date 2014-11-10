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
	var $use_single_date = 1;
	var $always_choose_order = 1;

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
			mx_formi_submit('RecordExecution', "µ­Ï¿");
	}

	function edit_class() {
		// abstract
	}

	function check_executions() {
		$date = mx_db_sql_quote(mx_check_request('LimitPatientDate'));
		$oid = array();
		$ref = $this->exec_references;
		$store = $this->exec_stores;
		foreach ($this->orders as $o)
			$oid[] = $o['object_id'];
		if (count($oid)) {
			$oid = implode(', ', $oid);
			$oid = "AND \"$ref\" IN ($oid)";
		} else {
			$oid = '';
		}
		$stmt = <<<SQL
			SELECT "ObjectID", "¼Â»ÜÆü", "¼Â»Ü¼Ô",
			"$ref", "ÆÃµ­»ö¹à", recorded
			FROM "$store"
			WHERE "Superseded" IS NULL
			$oid
			ORDER BY "$ref", recorded
SQL;
//print $stmt.'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
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
		$this->shown_orders++;

		print "<td>";
		print htmlspecialchars($o['timestamp']);
		print "<br />";
		print htmlspecialchars($o['text']);
		print "</td><td>";
		print $o['fuller'];

		$current = array();
		if (array_key_exists($order_id, $this->execution_check))
			$current = $this->execution_check[$order_id];
		$count = count($current);
		$limit = 1;
		for ($ix = 0; $ix < $limit; $ix++) {
			print "<br /><hr />\n";
			if ($ix < $count) {
				$check = $current[$ix];
				$ts = $this->fmt_ts($check['recorded']);
				print "¡»¼Â»ÜºÑ ($ts µ­Ï¿)";
				$special = $check['ÆÃµ­»ö¹à'];
				if (trim($special) != '') {
					print "<br />¡¡ÆÃµ­»ö¹à:";
					print htmlspecialchars($special);
				}
				continue;
			}
			$control = "ILID-$order_id";
			mx_formi_checkbox($control, 1);
			print "¼Â»Ü<br />\n";
			print "¡¡ÆÃµ­»ö¹à:";
			mx_formi_text("ILIN-$order_id", '');
			break;
		}
	}

	function setup_application_widgets () {
		if (mx_check_request('RecordExecution')) {
			$this->record_execution();
		} else {
			reorder_application::setup_application_widgets();
		}
	}

	function record_one($date, $oid, $note) {
		$ref = $this->exec_references;
		$soe = $this->edit_class();
		$soe->anew(NULL);
		$soe->data = array('¼Â»ÜÆü' => $date,
				   '¼Â»Ü¼Ô' => $this->whoami,
				   $ref => $oid,
				   '¼Â»Ü' => 'Y',
				   'ÆÃµ­»ö¹à' => $note);
		$soe->commit();
	}

	function record_execution() {
		$patients = array();
		$patient_map = array();
		$ordids = array();
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
			if (!preg_match('/^RAREDO-([0-9]+)-(.+)$/',
					$k, &$m))
				continue;
			$oid = $m[1];
			$app = mx_form_decode_name($m[2]);
			if ($app != $modinfo)
				continue;
			if (!array_key_exists("ILID-$oid", $_REQUEST))
				continue;
			$ordids[$oid] = $app;
		}
		$date = mx_check_request('LimitPatientDate');
		$qdate = mx_db_sql_quote($date);
		$exec = array();
		$dbh = mx_db_connect();
		$this->dbh = &$dbh;
		$this->executed = 0;
		$this->whoami = $this->auth[2]['ObjectID'];
		foreach ($ordids as $oid => $app) {
			if (!array_key_exists($oid, $ordids))
				continue;
			$note = $_REQUEST["ILIN-$oid"];
			$exec[] = array($oid, $note);
			$this->record_one($date, $oid, $note);
			$this->executed++;
		}
		$this->exec = $exec;
		$this->action = 'draw_execution';
	}

	function draw_execution() {
		$date = mx_check_request('LimitPatientDate');

		if ($this->executed)
			print "µ­Ï¿¤·¤Þ¤·¤¿¡£";
		print "<!--\n";
		foreach ($this->exec as $v) {
			print "execute on $date $v[0] $v[1] $v[2]\n";
		}
		print "-->\n";

	}

}
?>
