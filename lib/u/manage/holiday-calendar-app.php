<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/holiday-calendar.php';

class holiday_calendar_application extends single_table_application {

	var $_browse_only = 1; /* no New/Copy/Edit controls */

	var $columns = array('rule', 'year', 'month', 'mday',
			     'nth', 'wday', 'name', 'avail',
			     'start_time', 'end_time',
			     'sortorder');

	function setup_modality() { /* override */
		$this->error = 0;
		return NULL;
	}

	function read_form() {
		$data = array();
		$i = 0;
		while (array_key_exists("D_rule_$i", $_REQUEST)) {
			$d = array();
			foreach ($this->columns as $c) {
				$k = "D_" . $c . "_$i";
				$d[$c] = $_REQUEST[$k];
			}
			$data[] = $d;
			$i++;
		}
		return $data;
	}

	function handle_commit() {
		// NEEDSWORK -- input validation

		if ($this->calendar->update($this->data))
			$this->error = 'Gaah';
	}

	function create_empty() {
		$empty = array();
		foreach ($this->columns as $c)
			$empty[$c] = NULL;
		return $empty;
	}

	function handle_insert() {
		$pos = $_REQUEST['InsertHere'];
		$cnt = count($this->data);

		$new = array();
		for ($i = 0; $i < $pos; $i++)
			$new[] = $this->data[$i];
		$new[] = $this->create_empty();
		for ( ; $i < $cnt; $i++)
			$new[] = $this->data[$i];
		$this->data = $new;
	}

	function handle_delete() {
		$pos = $_REQUEST['DeleteThis'];
		$cnt = count($this->data);

		$new = array();
		for ($i = 0; $i < $cnt; $i++)
			if ($i != $pos)
				$new[] = $this->data[$i];
		$this->data = $new;
	}

	function setup_widgets() {

		single_table_application::setup_widgets();

		$this->modality = $this->setup_modality();
		if ($this->error)
			return;
		$calendar = new holiday_calendar($this->modality);
		$this->calendar = $calendar;

		if (!mx_check_request('Initialized'))
			$this->data = $calendar->read_calendar();
		else
			$this->data = $this->read_form();

		if (mx_check_request('Commit')) {
			$this->handle_commit();
			$this->data = $calendar->read_calendar();
		}
		if (mx_check_request('Rollback'))
			$this->data = $calendar->read_calendar();
		else if (array_key_exists('InsertHere', $_REQUEST))
			$this->handle_insert();
		else if (array_key_exists('DeleteThis', $_REQUEST))
			$this->handle_delete();

		if (!count($this->data))
			$this->data[] = $this->create_empty();
	}

	function draw_insert_here($i) {
		print '<tr>';
		print '<td colspan="5" style="text-align: right">';
		print '<hr />';
		print '</td><td>';
		mx_formi_submit("InsertHere", $i, "挿入", "挿入");
		print "</td></tr>\n";
	}

	function draw_avail($name, $value) {
		mx_formi_select($name, $value,
				array('0' => '休診',
				      '1' => '診療'));
	}

	function draw_rule($name, $value, $magic) {
		$option = array('onchange-script' =>
				sprintf("showhide_caledit('%d');", $magic));
		if ($value != 'D')
			$value = 'W';
		mx_formi_select($name, $value,
				array('D' => '日付で指定',
				      'W' => '曜日で指定'),
				$option);
	}

	function draw_range($name, $value, $low, $high, $with_empty=NULL) {
		$s = array();
		if (!is_null($with_empty))
			$s[''] = $with_empty;
		for ($i = $low; $i <= $high; $i++) {
			$s[$i] = $i;
		}
		mx_formi_select($name, $value, $s);

	}

	function draw_year($name, $value) {
		$y = substr(mx_today_string(), 0, 4);
		$this->draw_range($name, $value, $y, $y+2, ' ');
	}

	function draw_month($name, $value) {
		$this->draw_range($name, $value, 1, 12, ' ');
	}

	function draw_mday($name, $value) {
		$this->draw_range($name, $value, 1, 31, ' ');
	}

	function draw_wday($name, $value) {
		mx_formi_select($name, $value, array('' => ' ',
						     '1' => '月',
						     '2' => '火',
						     '3' => '水',
						     '4' => '木',
						     '5' => '金',
						     '6' => '土'));
	}

	function draw_hour($name, $value) {
		$s = array('' => ' ');
		for ($h = 6; $h < 24; $h++) {
			$hh = sprintf("%02d", $h);
			$hm = "$hh:00";
			$s[$hm] = $hm;
			$hm = "$hh:30";
			$s[$hm] = $hm;
		}
		mx_formi_select($name, $value, $s);
	}

	function draw_row($i) {

		$d = $this->data[$i];

		print '<tr>';

		print '<td rowspan="2">';
		$this->draw_avail("D_avail_$i", $d['avail']);
		print '</td>';

		print "<td>";
		$this->draw_year("D_year_$i", $d['year']);
		print '年</td>';

		print '<td>';
		$this->draw_month("D_month_$i", $d['month']);
		print '月</td>';

		print "<td><span id=\"E_mday_$i\">";
		$this->draw_mday("D_mday_$i", $d['mday']);
		print '日</span></td>';

		print '<td rowspan="2">';
		$this->draw_hour("D_start_time_$i", $d['start_time']);
		print '〜<br />';
		$this->draw_hour("D_end_time_$i", $d['end_time']);
		print '</td>';

		print '<td rowspan="2"><br />';
		mx_formi_submit("DeleteThis", $i, "削除", "削除");
		print '</td>';
		print "</tr>\n";

		print '<tr>';
		print '<td colspan="2">';
		$this->draw_rule("D_rule_$i", $d['rule'], $i);
		print '</td>';
		print "<td><span id=\"E_wday_$i\">";
		print "第";
		$this->draw_range("D_nth_$i", $d['nth'], 1, 5, ' ');
		$this->draw_wday("D_wday_$i", $d['wday']);
		mx_formi_hidden("D_name_$i", $d['name']);
		print '曜日</span></td>';
		print "</tr>\n";
	}

	function append_hidden() { // enhance
		mx_formi_hidden('Initialized', 1);
	}

	function custom_left_pane() { // override
	}

	function left_pane() {
		if ($this->error) {
			print $this->error;
			return;
		}
		$this->custom_left_pane();
		print "<table class=\"tabular-data\">\n";
		$cnt = count($this->data);
		for ($i = 0; $i < $cnt; $i++) {
			$this->draw_insert_here($i);
			$this->draw_row($i);
		}
		$this->draw_insert_here($cnt);
		print "</table>\n";
		$this->append_hidden();

		mx_formi_submit('Commit', 'commit', '保存', '保存');
		mx_formi_submit('Rollback', 'rollback',
				'変更破棄', '変更破棄');
		printf("<script>showhide_caledit_all(%d);</script>", $cnt);
	}

}

class employee_calendar_application extends holiday_calendar_application {

	var $limit_to_current_user = 1;

	function setup_modality() {
		// current user is $this->u
		// does it appear in modality_to_medex_employee
		// mappings, and if so where?
		if ($this->limit_to_current_user) {
			$u = $this->u;
			$limit = "WHERE A.userid = $u";
		}
		$stmt = <<<SQL
SELECT M.id, M.name
FROM mx_authenticate AS A
JOIN "職員台帳" AS E
  ON E."Superseded" IS NULL AND E.userid = A.userid
JOIN modalities_to_medex_employee AS ME
  ON E."ObjectID" = ME.employee
JOIN modalities AS M
  ON ME.modality = M.id
$limit
SQL;
		$db = mx_db_connect();
		$data = pg_fetch_all(pg_query($db, $stmt));
		if (!$data || !count($data)) {
			$this->error = "ユーザは休日表を設定できません。";
			return NULL;
		}

		if (array_key_exists('MODALITY', $_REQUEST)) {
			$m = $_REQUEST['MODALITY'];
			$ok = 0;
			foreach ($data as $d) {
				if ($d['id'] == $m) {
					$ok = 1;
					$n = $d['name'];
					break;
				}
			}
			if (!$ok) {
				$this->error = "YOU CANNOT EDIT THAT CALENDAR";
				return NULL;
			}
		} else if (count($data) == 1) {
			$m = $data[0]['id'];
			$n = $data[0]['name'];
		} else {
			$this->choose_modality = $data;
			$this->error = "NEED TO CHOOSE MODALITY";
			return NULL;
		}
		$this->name = $n;
		return $m;
	}

	function handle_commit() {
		holiday_calendar_application::handle_commit();
		$capacity = mx_check_request("ModalityCapacity");
		mx_update_modality_capacity($this->modality, $capacity);
	}

	function draw_avail($name, $value) {
		$s = array();
		$s['0'] = '休診';
		for ($i = 1; $i <= $this->maxcap; $i++)
			$s[$i] = "患者 $i 名/コマ";
		mx_formi_select($name, $value, $s);
	}

	function append_hidden() {
		holiday_calendar_application::append_hidden();
		if ($this->name)
			mx_formi_hidden('MODALITY_NAME', $this->name);
		if ($this->modality)
			mx_formi_hidden('MODALITY', $this->modality);
	}

	function custom_left_pane() {

		$title = sprintf("%s の休日表", $this->name);
		mx_titlespan($title);
		print '<hr />';

		$capacity = mx_check_request("ModalityCapacity");
		if (is_null($capacity)) {
			$modality = $this->modality;
			$capacity = mx_default_modality_capacity($modality);
		}
		$max = $default_capacity = $capacity;
		foreach ($this->data as $d) {
			if ($d['avail'] > $max)
				$max = $d['avail'];
		}
		$maxcap = $max * 5 / 4;
		$adjust = ($maxcap > 5) ? 3 : 2;
		if ($maxcap < $max + $adjust)
			$maxcap = $max + $adjust;
		print "以下で指定しない診療日に、一コマに診る患者数: ";
		$this->draw_range('ModalityCapacity', $capacity,
				  1, $maxcap);
		print "名";
		$this->maxcap = $maxcap;
	}

	function left_pane() {
		if (!$this->choose_modality) {
			holiday_calendar_application::left_pane();
			return;
		}
		mx_titlespan("カレンダーを選択");
		print "<ul>\n";
		foreach ($this->choose_modality as $d) {
			print "<li>";
			print '<input type="radio" name="MODALITY" value="';
			print $d['id'];
			print '">';
			print htmlspecialchars($d['name']);
			print "</li>\n";
		}
		print "</ul>\n";
		mx_formi_submit('Choose', 'choose', '選択', '選択');
	}

}

class manager_calendar_application extends employee_calendar_application {

	var $limit_to_current_user = 0;

}

?>
