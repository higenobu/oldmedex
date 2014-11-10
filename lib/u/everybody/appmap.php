<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

class everybody_appmap_application extends per_patient_application {
	var $left_pane_only = 1;
	var $use_list_of_patients_in_main = 0;
	var $_upper = array('index.php' => '/images/top_button.png',
			    'logout.php' => '/images/logout_button.png');

	function draw_ppa_applist() { }

	function everybody_appmap_application() {
		global $_mx_use_appbar;

		if ($_mx_use_appbar)
			$this->use_list_of_patients_in_main = 1;
		per_patient_application::per_patient_application();
	}

	function left_pane_1() {
		global $_mx_use_appbar;

		if (!$_mx_use_appbar)
			per_patient_application::left_pane_1();
		// otherwise do not do anything.
	}

	function left_pane() {
		global $__lib_u_manage_app_auth__applink_names;
		global $_mx_use_appbar;

		if ($_mx_use_appbar)
			return per_patient_application::left_pane();

		$pt = $this->patient_ID;
		$poid = $this->patient_ObjectID;
		$u = $this->u;
		$apps = mx_find_application($u);
		$a = array();
		$me = $_SERVER['PHP_SELF'];
		$match = array();
		if (! preg_match('/^(\/au\/[^\/]+\/)(.*)$/', $me, &$match)) {
			print "UNM $me";
			return;
		}
		$cookie = $match[1];
		$me = $match[2];

		$ppa_apps = array();
		$other_apps = array();
		foreach ($apps as $cat => $ac) {
			if (!is_array($ac))
				continue;
			$apps = array();
			foreach ($ac as $d) {
				if (!$poid &&
				    ($d['ppa'] == 'F' || $d['ppa'] == 'Y'))
					continue;
				$path = $d['path'];
				$name = $d['name'];
				if ($path == $me)
					continue;
				if ($d['ppa'] == 'F')
					$pid = '?PID=' .
						htmlspecialchars($pt);
				else if ($d['ppa'] == 'Y')
$pid = '?SetPatient=1&PatientID=' .
					//1115-2012 $pid = '?SetPatient=1&amp;PatientID=' .
						htmlspecialchars($pt);
				else
					$pid = '';
				$it = htmlspecialchars($cookie.$path).$pid;
				$it = array($it, $name);
				if ($pid)
					$ppa_apps[$cat][] = $it;
				else
					$other_apps[$cat][] = $it;
			}
		}
		$group = array('Á´ÈÌ' => $other_apps,
			       '´µ¼ÔËè' => $ppa_apps);

		$this->preamble();
		foreach ($group as $cat => $apps) {
			if (!count($apps))
				continue;
			$this->preamble_category($cat);

			foreach ($__lib_u_manage_app_auth__applink_names as
				 $k => $v) {
				if (!array_key_exists($k, $apps) ||
				    !count($apps[$k]))
					continue;
				$this->preamble_group($v);
				foreach ($apps[$k] as $app) {
					print $this->applink($app);
				}
				$this->postamble_group($v);
			}
			$this->postamble_category($cat);
		}
		$this->postamble();
	}

	function applink($it) {
		$path = $it[0];
		$name = $it[1];
		print "<tr><td class=\"steel\"><a href=\"$path\"><div>";
		print htmlspecialchars($name);
		print "</div></a></td></tr>\n";
	}

	function preamble_group($name) {
		print "<tr><td class=\"steel\"><div>[";
		print htmlspecialchars($name);
		print "]</div></td></tr>\n";
	}
	function postamble_group($group) {
	}
	function preamble_category($cat) {
		print "<td class=\"catcell\"><table class=\"bleu\">\n";
	}
	function postamble_category($cat) {
		print "</table></td>\n";
	}
	function preamble() {
		print '<style type="text/css">
td.catcell {
  vertical-align: top;
}
table.bleu {
  background-color: #eef;
}

td.steel {
  color: #00f;
}
td.steel div {
  padding-right: 32px;
  padding-left: 32px;
  line-height: 48px;
  text-decoration: none;
  vertical-align: middle;
  font-family: sans, sans-serif, gothic;
  font-weight: bold;
  font-size: 24px;
  background-color: #cc6;
}
td.steel a {
  line-height: 48px;
  text-decoration: none;
  vertical-align: middle;
  text-align: right;
  font-family: sans, sans-serif, gothic;
  font-weight: bold;
  font-size: 24px;
}
td.steel a div {
  color: #024;
  font-size: 24px;
  background-color: #bbb;
}
td.steel a:hover div {
  color: #024;
  background-color: #888;
}
td.steel a:active div {
  color: #eee;
  background-color: #444;
}
</style>';
		print '<table><tr>';
	}
	function postamble() {
		print "</tr></table>\n";
	}
}
?>
