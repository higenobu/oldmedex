<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// This is just a mock-up demo to validate the application flow.

class template_fill_application {
	var $debug = 0;

	function main() {
		$this->u = mx_authenticate_user();
		$this->auth = mx_authorization();
		if (! $this->auth[0])
			return mx_authorization_error($this->auth);

		$this->prepare_template();

		if (array_key_exists('Commit', $_REQUEST))
			$this->resume_application();
		else if (array_key_exists('Rollback', $_REQUEST)) {
			if ($_REQUEST['mode'] == 'single')
				$this->back_to_application();
			else
				$this->back_to_pick();
		}
		else
			$this->draw_template();
	}

	function draw_template() {
		global $_mx_resource_dir;

		mx_html_head($this->auth[1]);
		print "<form method=\"POST\" action=\"template-fill.php\">\n";
		mx_formi_hidden('state', $_REQUEST['state']);
		mx_formi_hidden('use-template', $_REQUEST['use-template']);
		if ($_REQUEST['mode'] != '')
			mx_formi_hidden('mode', $_REQUEST['mode']);
		print "<table class=\"tabular-data\" width=\"100%\">\n";
		print "<tr><th width=\"15%\">欄</th>";
		print "<th width=\"85%\">テンプレート</th></tr>\n";
		print $this->template_form;
		print "</table>\n";
		mx_formi_submit('Commit', 1,
				'<img src="/' .
				$_mx_resource_dir .
				'/images/commit_button.png">');
		mx_formi_submit('Rollback', 1,
				'<img src="/' .
				$_mx_resource_dir .
				'/images/rollback_button.png">');
		print "</body></html>\n";
	}

	function fill_template_one(&$request, $col, $val) {
		$result = '';

		$txt = $val;
		$max = -1;
		while (1) {
			$match = array();
			if (preg_match('/(?s)^(.*?)%%(\d+)%%(.*)$/',
				       $txt, &$match)) {
				$it = $match[2];
				$txt = $match[3];
				if ($max < $it)
					$max = $it;
			}
			else
				break;
		}

		$replace = array();
		for ($i = 0; $i <= $max; $i++) {
			$v = '';
			if (array_key_exists("val-$i", $_REQUEST))
				$v = $_REQUEST["val-$i"];
			if ($v && array_key_exists("lbl-$i", $_REQUEST)) {
				$v = $_REQUEST["lbl-$i"] . " ";
			}
			$replace[$i] = $v;
		}

		if ($this->debug) {
			print "Template string:\n";
			print "$val\n\n";
			print "Template replacement:\n";
			var_dump($replace);
		}

		for ($i = 0; $i <= $max; $i++) {
			if ($replace[$i] != '')
				continue;
			if (!array_key_exists("end-$i", $_REQUEST))
				continue;
			/* From $i to (end-$i)-1 are to be nuked */
			$end = $_REQUEST["end-$i"];
			for ($j = $i; $j <= $end; $j++)
				$replace[$j] = '';
			/* Also from the template text */
			$pos0 = strpos($val, "%%$i%%", 0);

			/* Does not apply to us */
			if ($pos0 === false)
				continue;

			$pos1 = strpos($val, "%%$end%%", 0);
			$pos1 += strlen("%%$end%%");

			if ($this->debug) {
				print "Template trimming $i..$end\n";
				print "Trim location $pos0..$pos1\n";
				print "Template string:\n";
				print "$val\n";
			}
			$val = substr($val, 0, $pos0) . substr($val, $pos1);
			if ($this->debug) {
				print "Trimmed template string:\n";
				print "$val\n";
			}
		}

		while (1) {
			$match = array();
			if (preg_match('/(?s)^(.*?)%%(\d+)%%(.*)$/',
				       $val, &$match)) {
				$it = $match[2];
				$val = $match[3];
				$result .= $match[1] . $replace[$it];
			}
			else {
				$result .= $val;
				break;
			}
		}
		$result = trim($result);
		if ($this->debug && $result != '') {
			print "Final replacement for $col\n";
			print "<<$result>>\n\n\n";
		}

		if ($result != '') {
			$col = 'soe-' . mx_form_encode_name($col);
			if ($request[$col] != '')
				$request[$col] .= "\n";
			$request[$col] .= $result;
		}
	}

	function fill_template() {

		$id = $_REQUEST['state'];
		$db = mx_db_connect();
		$stmt = 'SELECT data FROM mx_appstate WHERE id = ' .
			mx_db_sql_quote($id);
		$d = mx_db_fetch_single($db, $stmt);
		$data = $d['data'];
		$data = mx_form_decode_name($data);
		$data = unserialize($data);
		$request = $data['updated'];

		foreach ($this->value_desc as $col => $val)
			$this->fill_template_one(&$request, $col, $val);

		$data['updated'] = $request;
		$data = mx_form_encode_name(serialize($data));
		$stmt = 'UPDATE mx_appstate SET data = ' .
			mx_db_sql_quote($data) .
			' WHERE id = ' .
			mx_db_sql_quote($id);
		pg_query($db, $stmt);

		if ($this->debug) {
			print "Request:\n";
			var_dump($request);
		}

	}

	function back_to_pick() {
		$id = $_REQUEST['state'];
		mx_http_redirect('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
				 "/template-pick.php?ID=$id");
	}

	function back_to_application() {
		if ($this->debug) {
			header("Content-type: text/plain\n");
			print "\n";
			print "Debug mode\n";
		}

		$id = $_REQUEST['state'];
		$db = mx_db_connect();
		template_restore_appstate(&$db, $id, 'Cancel', $this->debug);
	}

	function resume_application() {
		if ($this->debug) {
			header("Content-type: text/plain\n");
			print "\n";
			print "Debug mode\n";
		}

		$this->fill_template();

		$id = $_REQUEST['state'];
		$db = mx_db_connect();
		template_restore_appstate(&$db, $id, NULL, $this->debug);
	}

	function prepare_template() {
		$db = mx_db_connect();
		$stmt = '
SELECT template_obj FROM mx_template
WHERE "ObjectID" = ' . mx_db_sql_quote($_REQUEST['use-template']);
		$d = mx_db_fetch_single($db, $stmt);
		$template_obj = mx_form_decode_name($d['template_obj']);
		$template_obj = unserialize($template_obj);

		$this->template_form = $template_obj[0];
		$this->value_desc = $template_obj[1];
	}

}

$t = new template_fill_application();
$t->main();
?>
