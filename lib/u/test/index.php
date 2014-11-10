<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';

class mx_management_index extends single_table_application {
	var $use_single_pane = 1;
	var $no_control_bar = 1;

	function setup() {
		; // nothing
	}

	function apppath($path) {
		return '/au/' . $_SERVER['URL_PREFIX_COOKIE'] . '/' . $path;
	}

	function single_pane() {
		$mmgmt = array();
		$hmgmt = array();
		$smgmt = array();
		$apps = mx_find_application($this->u, 'M');

		if (!is_array($apps['M'])) {
			print "アクセスできる管理アプリケーションはありません";
			return;
		}
		foreach ($apps['M'] as $data) {
			$rank = $data['sortorder'];
			if (-1500 < $rank) { $mmgmt[] = $data; }
			elseif (-2000 < $rank) { $hmgmt[] = $data; }
			elseif (-3000 < $rank) { $smgmt[] = $data; }
		}

		foreach (array('マスタ管理' => $mmgmt,
			       '病院管理' => $hmgmt,
			       'システム管理' => $smgmt) as $grp => $lst) {
			if (count($lst)) {
				print '<span class="heading">';
				print htmlspecialchars($grp);
				print '</span><hr /><ul>';
				foreach ($lst as $data) {
					$path = $this->apppath($data['path']);
					print '<li><a href="';
					print htmlspecialchars($path);
					print '">';
					print htmlspecialchars($data['name']);
					print "</a></li>\n";
				}
				print "</ul>\n<hr />\n";
			}
		}
	}
}

$main = new mx_management_index();
$main->main();
?>
