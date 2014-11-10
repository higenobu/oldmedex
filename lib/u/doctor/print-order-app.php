<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/reorder-app.php';

class print_order_application extends reorder_application {

	var $application_title = "¥ª¡¼¥À¥ê¥¹¥È°õºþ";
	var $default_all_select = 1;
	var $use_choose_patients = 1;
//0327-2012
	var $me = 'print-order.php';
	var $app_ix_name = 'print';
	var $left_pane_only = 1;

	function setup_apps() {
		/*
		 * NEEDSWORK: this need to be integrated with
		 * what index-pt.php does elsewhere...
		 *
		 * $this_app = $this->app_ix_name; e.g. 'reorder', 'print'
		 * $this->apps = __lib_u_everybody_index_pt__applist($app);
		 */
		return array('pharm' => '½èÊýäµ',
			     'injection' => 'Ãí¼Í',
			     'test' => '¸¡ºº',
				
				 'xct' => 'xct',
			     'procedure' => '½èÃÖ');
	}

	function default_date_range() {
		return array(mx_today_string(), '');
	}

	function setup() {
		single_table_application::setup();
		if ($this->action == 'draw_not') {
			print "<!-- RETURN TRUE -->";
			return 1;
		}
		return 0;
	}

	function setup_application_widgets () {
		if (mx_check_request('TopFrame')) {
			$this->action = 'draw_not';
			$this->draw_top_frame();
		} else if (mx_check_request('LowFrame')) {
			$this->action = 'draw_not';
			$this->draw_low_frame();
		} else if (mx_check_request('PrintOrders')) {
			$this->action = 'draw_not';
			$this->draw_print_frames();
		} else {
			reorder_application::setup_application_widgets();
		}
	}

	function draw_not() {
	}

	function draw_control_on_order_list() {
		$it = array();
		foreach ($this->orders as $o) {
			$oid = $o['´µ¼ÔObjectID'];
			$o['´µ¼ÔÌ¾'] = $this->patient_map[$oid];
			$it[] = $o;
		}
		$it = serialize($it);
		$db = mx_db_connect();
		$it = mx_db_insert_blobmedia($db, 'x-application/orders', $it);

		mx_formi_hidden("PossibleOrdersBlobMedia", $it);
		mx_formi_submit('PrintOrders', "³ÎÇ§");
	}

	function draw_print_frames() {
		$it = $_REQUEST['PossibleOrdersBlobMedia'];
		$db = mx_db_connect();
		$media = '';
		if (!mx_db_fetch_blobmedia(&$db, &$media, $it)) {
			print "¥¨¥é¡¼";
			return;
		}
		$media = unserialize($media);

		$use = array();
		foreach ($_REQUEST as $k => $v) {
			$m = array();
			if (!preg_match('/^RAREDO-([0-9]+)-(.+)$/',
					$k, &$m))
				continue;
			$oid = $m[1];
			$app = mx_form_decode_name($m[2]);
			$use["$app-$oid"] = 1;
		}
		$order = array();
		foreach ($media as $o) {
			$key = $o['type'] . '-' . $o['object_id'];
			if (!array_key_exists($key, $use))
				continue;
			$order[] = $o;
		}

		if (!$order) {
			$this->orders = NULL;
			$this->action = 'draw_orders';
			return;
		}
		$it = serialize($order);
		$it = mx_db_insert_blobmedia($db, 'x-application/orders', $it);
		$me = $this->me;
		print <<<HTML
<frameset rows="60,*" noresize border="0">
<frame src="$me?TopFrame=1"
	"name="top_frame" scrolling="no">
<frame src="$me?LowFrame=$it"
	"name="bottom_frame">
</frameset>
HTML;
	}

	function draw_top_frame() {
		$me = $this->me;
		mx_html_head(NULL, 'do_not_close_head');
		print <<<HTML
<script language="javascript" type="text/javascript">
         <!--
		function printPopup() {
			parent.frames[1].focus();
			parent.frames[1].print();
		}
         -->
         </script>
<style type="text/css">
	body {
		background-image: none;
		background-color: #fff;
	}
</style>
</head><body><center>
<form><input type="button" value="°õºþ" onClick="printPopup()">
<a href="$me" target="_top">Ìá¤ë</a>
</form></center></body></html>
HTML;
	}

	function entry_rowspan() {
		return 1;
	}

	function draw_entry_seal_area($eo) {
		print '<td class="sealarea">»Ø¼¨°å°õ</td>';
		print '<td class="sealarea">¼Â»Ü¼Ô°õ</td></tr>';
	}

	function draw_low_frame() {
		mx_html_head(NULL);

		$it = $_REQUEST['LowFrame'];
		$db = mx_db_connect();
		$media = '';
		if (!mx_db_fetch_blobmedia(&$db, &$media, $it)) {
			print "¥¨¥é¡¼";
			return;
		}
		$media = unserialize($media);

		print '<table class="listofstuff">';
		$p = NULL;
		$eo = 'e';

		foreach ($media as $o) {

			if ($o['´µ¼ÔObjectID'] != $p) {
				$p = $o['´µ¼ÔObjectID'];
				$i = $o['´µ¼ÔID'];
				$n = $o['´µ¼ÔÌ¾'];
				print '<tr class="y"><td colspan="5">';
				print htmlspecialchars("$i: $n");
				print "</td></tr>\n";
				$eo = 'e';
				$sel = 1;
			} else
				$sel = 0;

			$entry_rs = $this->entry_rowspan();
			if ($entry_rs < 2)
				$rs = "";
			else
				$rs = " rowspan=\"$entry_rs\"";
			$eo = $eo == 'e' ? 'o' : 'e';
			print "<tr class=\"$eo\">";

			if ($o['html']) {
			  print "<td colspan=\"2\"$rs>";
			  print $o['html'];
			  print "</td>";
			} else {
			  print "<td$rs>";
			  $app = mx_form_encode_name($o['type']);
			  $oid = $o['object_id'];
			  print htmlspecialchars($o['timestamp']);
			  print "<br />";
			  print htmlspecialchars($o['text']);
			  print "</td><td$rs>";
			  print $o['fuller'];
			  print "</td>\n";
			}
			$this->draw_entry_seal_area($eo);
		}

		print "</table>\n";
	}

}

class quick_print_test_order_application extends print_order_application {
	var $direct_go_application = 1;
	var $me = 'test-order.php';
	var $application_title = "ËÜÆü¸¡ºº¥ª¡¼¥À";
	var $app_ix_name = 'print-test';

	function default_date_range() {
		return array(mx_today_string(), mx_today_string());
	}
	function setup_apps() {
		return array('test' => '¸¡ºº');
	}
}

?>
