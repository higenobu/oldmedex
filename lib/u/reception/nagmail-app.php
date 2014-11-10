<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/nagmail.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/apptmail.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/nagmail.php';

class appt_nagmail_application_base extends single_table_application {

	var $_browse_only = 1; /* no New/Copy/Edit controls */

	/*
	 * Application states:
	 *
	 * (1) initial -- show nagmail recipient list
	 *
	 *	Controls:
	 *	(1-a) pick subset of potential recipients and move to (2)
	 *      (1-b) change the time range and move to (1)
	 *
	 * (2) confirm -- show chosen recipients and templates
	 *
	 *	Controls:
	 *	(2-a) pick a template and move to (4)
	 *	(2-b) pick a template and move to (3)
	 *	(2-z) go back to (1)
	 *
	 * (3) edit -- show chosen recipients and edit template
	 *
	 *	Controls:
	 *	(3-a) with edited template text move to (4)
	 *	(3-z) go back to (2)
	 *
	 * (4) send -- send them and show "sent" message.
	 *
	 *	Controls:
	 *	(4-z) go back to (1)
	 */
	function setup_widgets() {

		single_table_application::setup_widgets();
		$this->state = 'initial';

		foreach (array('1A' => 'confirm',
			       '1B' => 'initial',
			       '2A' => 'send',
			       '2B' => 'edit',
			       '2Z' => 'initial',
			       '3A' => 'send',
			       '3Z' => 'confirm',
			       '4Z' => 'initial') as $s => $state) {
			if (mx_check_request('Submit' . $s)) {
				$this->state = $state;
				break;
			}
		}
	}

	function draw_row($ix, $row, $control) {
		$eo = ($ix % 2) ? "o" : "e";

		print "<tr class=\"$eo\"><td>";

		$key = $row['pk'];
		if ($control) {
			mx_formi_checkbox("pt-$key", 1);
			print "</td>";
			print "<td>";
		} else {
			mx_formi_hidden("pt-$key", 1);
		}
		print htmlspecialchars
			(mx_format_timestamp($row['予約時刻'], 0));
		print "</td>";

		print "<td>";
		print htmlspecialchars($row['姓'].$row['名']);
		print "</td>";

		print "<td>";
		print htmlspecialchars($row['予約先']);
		print "</td>";

		print "</tr>\n";
	}

	function appt_list_filter($includes) {
		$cfg = $this->naglist_cfg();
		$ee = appt_list_yet_to_show
			($db,  $cfg['Bottom'], $cfg['Top'], $cfg['Purpose']);
		$entry = array();
		$count = count($ee);
		for ($i = 0; $i < $count; $i++) {
			$row = $ee[$i];
			$k = mx_form_escape_key(array($row['appt_id'],
						      $row['rsched_id']));
			$row['pk'] = $k;
			if ($includes &&
			    !array_key_exists($row['pk'], $includes))
				continue;
			$entry[] = $row;
		}
		return $entry;
	}

	function draw_appt_list($includes, $control, $abbrev) {

		$entry = $this->appt_list_filter($includes);
		$count = count($entry);

		if (!$count)
			return -1;

		print '<table class="listofstuff">';
		print "<tr>";
		if ($control) {
			print "<th>〇</th>";
		}
		print "<th>予約日時</th><th>患者</th><th>予約先</th></tr>\n";

		# When there are only enough space for 4 rows ($abbrev),
		# show 3 rows and say "and N more people" at the end.
		# N must be more than 1 for this to make sense, which
		# means ($count - ($abbrev - 1)) must be more than one for
		# such an abbreviation to take place.
		if ($abbrev && (($count - ($abbrev - 1)) > 1)) {
			$limit = $abbrev - 1;
		} else {
			$limit = $count;
		}
		for ($i = 0; $i < $limit; $i++) {
			$row = $entry[$i];
			$key = $row['pk'];
			$this->draw_row($i, $row, $control);
		}
		if (($limit != $count) && !$control) {
			$eo = ($limit % 2) ? "o" : "e";
			print "<tr class=\"$eo\"><td colspan=\"3\">";
			print "(他 " . ($count - $limit) . " 名)";
			for ($i = $limit; $i < $count; $i++) {
				$row = $entry[$i];
				$key = $row['pk'];
				mx_formi_hidden("pt-$key", 1);
			}
			print "</td></tr>\n";
		}
		print "</table>\n";

		return 0;
	}

	function draw_initial() {

		$cfg = $this->naglist_cfg();

		print "<div>";
		mx_titlespan($this->range_title);

		print "<div>";
		mx_formi_date('NagApptBottomDate',
			      $cfg['Bottom'],
			      array('vname' => 'calend_bottom'));
		print " から<br/>";
		mx_formi_date('NagApptTopDate',
			      $cfg['Top'],
			      array('vname' => 'calend_top'));
		print " までに<br />";
		print htmlspecialchars($this->range_explain);
		print "</div>";
		print "</div>";

		print "<div>";
		mx_titlespan("送信先患者の選択");

		$has_none = $this->draw_appt_list(NULL, 'YesPlease', 0);

		if ($has_none)
			mx_titlespan("対象となる患者はありません");
		mx_formi_submit('Submit1B', 'Submit1B',
				'送信対象日付設定', '送信対象日付設定');
		if (!$has_none)
			mx_formi_submit('Submit1A', 'Submit1A',
					'メール', 'メール');
		print "</div>";
	}

	function draw_template_list() {

		$entry = _lib_u_manage_nagmail_find_template($this->purpose);
		$count = count($entry);

		print '<table class="listofstuff">';
		print "<tr>";
		print "<th>〇</th><th>表題</th><th>本文</th></tr>\n";

		$c = " checked";
		for ($i = 0; $i < $count; $i++) {
			$row = $entry[$i];
			$key = $row['template'];
			$eo = ($i % 2) ? "o" : "e";

			print "<tr class=\"$eo\"><td>";
			print "<input type=\"radio\" name=\"template\" ";
			print "value=\"$key\"$c>";
			print "</td>";

			print "<td>";
			print htmlspecialchars($row['subject']);
			print "</td>";

			$body = (mb_substr($row['body'], 0, 40, 'EUC-JP') .
				 "・・・");
			$body = $row['body'];
			print "<td>";
			print mx_html_paragraph($body);
			print "</td>";
			print "</tr>\n";
			$c = '';
		}
		print "</table>\n";
	}

	function draw_confirm() {

		$cfg = $this->naglist_cfg();
		mx_formi_hidden('NagApptBottomDate', $cfg['Bottom']);
		mx_formi_hidden('NagApptTopDate', $cfg['Top']);

		$r = array();
		foreach ($_REQUEST as $k => $v) {
			if (substr($k, 0, 3) != 'pt-')
				continue;
			$r[substr($k, 3)] = 1;
		}

		if (count($r) == 0)
			return $this->draw_initial();

		mx_titlespan("送信先患者の確認");

		$this->draw_appt_list($r, NULL, 3);

		mx_titlespan("送信文面の選択");

		$this->draw_template_list();

		mx_formi_submit('Submit2A', 'Submit2A',
				'送信', '送信');
		mx_formi_submit('Submit2B', 'Submit2B',
				'文面編集', '文面編集');
		mx_formi_submit('Submit2Z', 'Submit2Z',
				'戻る', '戻る');
	}

	function get_template() {
		$id = mx_check_request('template');
		$e = _lib_u_manage_nagmail_find_template($this->purpose, $id);
		$e = $e[0];
		if ($msgbody = mx_check_request('template_body'))
			$e['body'] = $msgbody;
		return $e;
	}

	function draw_edit() {

		$cfg = $this->naglist_cfg();
		mx_formi_hidden('NagApptBottomDate', $cfg['Bottom']);
		mx_formi_hidden('NagApptTopDate', $cfg['Top']);

		$db = mx_db_connect();
		$r = array();
		foreach ($_REQUEST as $k => $v) {
			if (substr($k, 0, 3) != 'pt-')
				continue;
			$r[substr($k, 3)] = 1;
		}
		$entry = $this->appt_list_filter($r);
		$count = count($entry);
		if (count($r) == 0)
			return $this->draw_initial();

		$t = $this->get_template();
		if (!$t['template'])
			return $this->draw_initial();
		$subject = $t['subject'];
		$body = $t['body'];
		$template = $t['template'];

		mx_titlespan("送信文面の編集");

		$this->draw_appt_list($r, NULL, 3);

		print "<div>\n";
		print "<pre>\n";
		print htmlspecialchars("表題: $subject\n");
		print "</pre>\n";
		mx_formi_textarea('template_body', $body,
				  array('cols' => 60,
					'rows' => 12));
		mx_formi_hidden('template', $template);

		print "</div>\n";
		mx_formi_submit('Submit3A', 'Submit3A',
				'送信', '送信');
		mx_formi_submit('Submit3Z', 'Submit3Z',
				'戻る', '戻る');

	}

	function send_message($db, $purpose, $template, $idata, $row) {

		$subject = $template['subject'];
		$body = $template['body'];
		$template = $template['template'];
		$addressee = $row['メールアドレス'];

		$row['予約時刻'] = mx_format_timestamp($row['予約時刻'], 0);
		$row['予約日時'] = $row['予約時刻'];
		$row['予約日'] = substr($row['予約時刻'], 0, 10);
		$row['病院名'] = $idata['HOSPITAL_NAME'];

		$ss = _lib_u_manage_nagmail_apply_template($subject, $row);
		$bb = _lib_u_manage_nagmail_apply_template($body, $row);

		print "<pre>\n";
		print htmlspecialchars("表題: $ss\n");
		print htmlspecialchars("宛先: $addressee\n");
		print htmlspecialchars("$bb");
		print "\n\n";
		print "</pre>\n";
		print "<hr />\n";

		_lib_u_manage_nagmail_send_nagmail
			($db, $purpose, $row['rsched_id'], NULL, $template,
			 $addressee, $subject, $body, $row);
	}

	function draw_send() {

		$cfg = $this->naglist_cfg();
		mx_formi_hidden('NagApptBottomDate', $cfg['Bottom']);
		mx_formi_hidden('NagApptTopDate', $cfg['Top']);

		$db = mx_db_connect();
		$r = array();
		foreach ($_REQUEST as $k => $v) {
			if (substr($k, 0, 3) != 'pt-')
				continue;
			$r[substr($k, 3)] = 1;
		}
		$entry = $this->appt_list_filter($r);
		$count = count($entry);
		if (count($r) == 0)
			return $this->draw_initial();

		mx_titlespan("送信内容の確認");

		$t = $this->get_template();
		$idata = mx_get_install_data();
		$p = $this->purpose;
		for ($i = 0; $i < $count; $i++) {
			$row = $entry[$i];
			$this->send_message($db, $p, $t, $idata, $row);
		}

		mx_titlespan("送信完了");

		mx_formi_submit('Submit4Z', 'Submit4Z',
				'戻る', '戻る');
	}

	/*
	 * This is not even the usual 3-pane application.
	 * We take over the whole lower pane and do our thing.
	 */
	function left_pane() {

		print "<div class=\"nagmail-form\">\n";

		switch ($this->state) {
		case 'initial':
			$this->draw_initial();
			break;
		case 'confirm':
			$this->draw_confirm();
			break;
		case 'edit':
			$this->draw_edit();
			break;
		case 'send':
			$this->draw_send();
			break;
		default:
			print "NOT HANDLED";
			break;
		}
		print "</div>\n";
	}

}

class appt_nagmail_missed_application extends appt_nagmail_application_base {
	var $purpose = 'APPT_MISSED';
	var $range_title = '来院リマインド送信対象日付の選択';
	var $range_explain = '予約があったのに来院しなかった患者';

	function naglist_cfg() {
		$bottom = mx_check_request('NagApptBottomDate');
		if (trim($bottom) == '')
			$bottom = mx_today_string(-86400*7);
		$top = mx_check_request('NagApptTopDate');
		if (trim($top) == '')
			$top = mx_today_string(-86400*1);
		return array('Bottom' => $bottom,
			     'Top' => $top,
			     'Purpose' => $this->purpose);
	}
}

class appt_nagmail_remind_application extends appt_nagmail_application_base {
	var $purpose = 'APPT_REMIND';
	var $range_title = '予約リマインド送信対象日付の選択';
	var $range_explain = '予約がある患者';

	function naglist_cfg() {
		$bottom = mx_check_request('NagApptBottomDate');
		if (trim($bottom) == '')
			$bottom = mx_today_string(86400*2);
		$top = mx_check_request('NagApptTopDate');
		if (trim($top) == '')
			$top = mx_today_string(86400*8);
		return array('Bottom' => $bottom,
			     'Top' => $top,
			     'Purpose' => $this->purpose);
	}
}

?>
