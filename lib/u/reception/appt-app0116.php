<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt-list-app.php';

class reception_appt_base extends single_table_application {
	var $_browse_only = 1; // no "New" "Edit" etc controls please
	var $show_patient_cols = array(array("患者ID", "氏名"),
				       array("性別", "生年月日"));
	function setup_widgets() {

		global $_mx_appt_days, $_mx_appt_num_days;

		$this->use_calendar_days = $_mx_appt_days;
		$this->num_calendar_days = $_mx_appt_num_days;

		single_table_application::setup_widgets();

		$this->loo_pi = new appt_patient_list('loo-pi-');
		$this->loo_modality = $this->modality_list('loo-modality-');
		$this->loo_appt = new list_of_pt_appt('loo-appt-', NULL,
						      NULL, NULL, False, False);
		if (array_key_exists($this->prefix . 'appt-to-modify', $_REQUEST) &&
		    !array_key_exists('Noop', $_REQUEST))
		    $this->appt_to_modify = mx_form_unescape_key($_REQUEST[$this->prefix . 'appt-to-modify']);
		if (mx_check_request('SetPatient') &&
		    array_key_exists('PatientID', $_REQUEST))
			$this->loo_pi->set_patient($_REQUEST['PatientID']);

		if ($this->loo_pi->chosen())
			$this->choose_patient();

		if ($this->loo_pi->changed()) {
			$this->loo_modality->patient = $this->patient_ObjectID;
			$this->loo_modality->reset(NULL);
			$this->loo_appt->reset(NULL);
			$this->appt_to_modify = NULL;
		}

		if($this->patient_ID)
		  $this->loo_appt = new list_of_pt_appt('loo-appt-', $this->patient_ID,
							NULL, NULL, False, False);
		if ($this->loo_modality->changed() && $this->loo_modality->chosen())
			$this->choose_dr();
		else if ($this->loo_appt->changed() && $this->loo_appt->chosen()) {
		  $this->appt_to_modify = mx_form_unescape_key($this->loo_appt->selection);
		  /*
		   0 rsched_id
		   1 "患者ID"     patient_ID
		   2 "予約先"     modality_Name
		   3 "目的"       apptobjective
		   4 apptdate
		   5 appttime
		   6 apptdur
		   7 appt_id
		   8 modality_id
		  */
		  // make sure this is not a stale selection
		  if($this->appt_to_modify[0] && $this->loo_appt->exists($this->appt_to_modify[0])) {
		    $this->appt_to_modify[5] = mx_format_timestamp($this->appt_to_modify[5], 0);
		    $this->modality = trim($this->appt_to_modify[8]);
		    $this->modality_Name = trim($this->appt_to_modify[2]);
		  }
		}else {
		  # get modality from last modified by
		  $this->modality = mx_form_decode_name($_REQUEST['last-modality']);
		  $this->modality_Name = mx_form_decode_name($_REQUEST['last-modality_Name']);
		}
		$this->data = array();
		foreach (array('apptdate' => 4, 'appttime' => 5, 'apptdur' => 6, 'apptobjective' => 3)
			 as $k => $n) {
		  if($this->appt_to_modify &&
		     !(array_key_exists('MakeAppt', $_REQUEST) || 
		       array_key_exists('CheckOnly', $_REQUEST) || 
		       array_key_exists('ModifyAppt', $_REQUEST)))
		    $this->data[$k] = $this->appt_to_modify[$n];
		  else if (array_key_exists($k, $_REQUEST))
		    $this->data[$k] = $_REQUEST[$k];
		}
		if (!array_key_exists('apptdate', $this->data)) {
			global $_mx_apptment_default_date_offset;
			$offset = $_mx_apptment_default_date_offset * 86400;
			$this->data['apptdate'] =
				mx_today_string($offset);
		}

		if (array_key_exists('CancelAppt', $_REQUEST)) {
		  $db = mx_db_connect();
		  pg_query($db, 'begin');
		  if($this->cancel_appointment($db))
		    pg_query($db, 'commit');
		  else
		    pg_query($db, 'rollback');
		  $this->loo_modality->reset(NULL);
		  $this->appt_to_modify = NULL;
		  $this->cancelled_appt = True;
		}
		else if (array_key_exists('MakeAppt', $_REQUEST))
		  $this->make_appointment(0);
		else if (array_key_exists('CheckOnly', $_REQUEST))
		  $this->make_appointment(1);
		else if (array_key_exists('ModifyAppt', $_REQUEST))
		  $this->make_appointment(-1);

	}

	function check_capacity(&$db) {
		$avail = mx_sched_available($this->data['apptdate'],
					    $this->data['apptdate'],
					    $this->modality);

		$capacity = mx_check_capacity($avail,
					      $this->data['apptdate'],
					      $this->data['appttime'],
					      $this->data['apptdur']);

		$date_n_time = ($this->data['apptdate'] . ' ' .
				$this->data['appttime']);

		for ($offset = 0, $ix = 0;
		     $offset < $this->data['apptdur'];
		     $offset += 30, $ix++) {
			$begins = ("timestamp '$date_n_time' + interval '" .
				  $offset . " minutes'");
			$ends = ("timestamp '$date_n_time' + interval '" .
				 (30 + $offset) . " minutes'");
			$check = '
SELECT count(*) AS cnt
FROM resource_sched
WHERE
res_desc = ' . $this->modality . ' AND
start_time < ' . $ends . ' AND end_time > ' . $begins;
			$d = mx_db_fetch_single($db, $check);
			if (!$d)
				continue;
			if ($d['cnt'] >= $capacity[$ix])
				return -1;
		}
		return 0;
	}

	function make_appointment($check_only) {
		global $_mx_appt_max_dups;
		global $_mx_claim_checkin_port;

		if ((trim($this->data['appttime']) == '') ||
		    (trim($this->data['apptdate']) == '') ||
		    (trim($this->data['apptdur']) == '') ||
		    (trim($this->modality) == '') ||
		    (trim($this->patient_ObjectID) == '') ||
		    (trim($this->patient_ID) == '') ||
		    (trim($this->patient_Name) == ''))
			return;

		if (mx_db_validate_date($this->data['apptdate']))
			return;

		if (mx_db_validate_time($this->data['appttime']))
			return;

		if ($this->data['apptobjective']) {
			$objective = ' ' . trim($this->data['apptobjective']);
		} else {
			$objective = '';
		}
		$check_duplicate_ok =
			implode('|',
				array(
					$this->data['apptdate'],
					$this->data['appttime'],
					$this->data['apptdur'],
					$this->patient_ID,
					$this->modality));

		$date_n_time = ($this->data['apptdate'] . ' ' .
				$this->data['appttime']);
		$date_n_time = 'timestamp ' . mx_db_sql_quote($date_n_time);

		$begins = $date_n_time;
		$ends = ("$date_n_time + interval '" .
			 $this->data['apptdur'] . " minutes'");

		$gaah = 0;
		$db = mx_db_connect();
		pg_query($db, 'begin');

		if ($this->check_capacity(&$db)) {
			$check = $_REQUEST['duplicate-accept'];
			if ($check_duplicate_ok == $check)
				$gaah = 0;
			else {
				$this->conflicts = $check_duplicate_ok;
				$gaah = 1;
			}
		}

		if ($gaah) {
			pg_query($db, 'rollback');
			return;
		}
		if ($check_only == 1) {
			$this->check_ok = 1;
			pg_query($db, 'rollback');
			return;
		}

		$ptdata = sprintf("患者 %s:%s",
				  $this->patient_ID,
				  $this->patient_Name);
		$pid = $this->patient_ObjectID;
 
		$stmt = '
INSERT INTO resource_sched
(id, orderitem_id,
 start_time, end_time, "patientID", "patientObjectID", "objective",
 res_desc, appointmentcategoryid,
 recurrencepattern, subject, comment, sync_orca)
VALUES (nextval(\'globalserial_id_seq\'), NULL, ';
		$stmt .= $begins . ',';
		$stmt .= $ends . ',';
		$stmt .= mx_db_sql_quote($this->patient_ID) . ', ';
		$stmt .= $this->patient_ObjectID . ', ';
		$stmt .= mx_db_sql_quote($objective) . ', ';
		$stmt .= $this->modality . ', ';
		$stmt .= '0, NULL, ';
		$stmt .= mx_db_sql_quote($ptdata) . ', ';
		$stmt .= mx_db_sql_quote("patientid:$pid$objective") . ', ';
		$stmt .= $_mx_claim_checkin_port ? 1:0;
		$stmt .= ");";
 

//0110-2014
 
 /*
$stmt1 = 'insert into  "受付患者表"
("患者","患者ID",
	 "予約時刻") values (';
$stmt1 .=mx_db_sql_quote("patientid:$pid$objective").',';
$stmt1 .= mx_db_sql_quote($this->patient_ID) . ', ';	   
$stmt1 .= $begins . ' ';	 
 $stmt1 .= ");";

 */




		if (!pg_query($db, $stmt))
			pg_query($db, 'rollback');
		else if($check_only == 0)
			pg_query($db, 'commit');
		else if($check_only == -1) {
		  if(!$this->cancel_appointment($db)) {
		    pg_query($db, 'rollback');
		    return;
		  }
		  pg_query($db, 'commit');
		}
/*
if (!pg_query($db, $stmt1))
			pg_query($db, 'rollback');
		 
		
		  pg_query($db, 'commit');
*/
		 
		$this->created_apptment =
			array('patient_Name' => $this->patient_Name,
			      'patient_ObjectID' => $this->patient_ObjectID,
			      'patient_ID' => $this->patient_ID,
			      'modality' => $this->modality,
			      'modality_Name' => $this->modality_Name,
			      'modality_Type' => $this->modality_type,
			      'apptdate' => $this->data['apptdate'],
			      'appttime' => $this->data['appttime'],
			      'apptdur' => $this->data['apptdur']);
		return True;
	}

	function cancel_appointment($db) {
	  if(!$this->appt_to_modify)
	    return;
	  $oid = $this->appt_to_modify[7]; // appt_id
	  $rsid = $this->appt_to_modify[0]; // rsched_id
	  if (!is_null($oid)) {
	    $stmt = <<<SQL
	      DELETE FROM "受付患者表"
	      WHERE "ObjectID" = $oid
SQL;
	    if(!pg_query($db, $stmt))
	      return;
	  }
	  if (!is_null($rsid)) {
	    $stmt = <<<SQL
	      DELETE FROM resource_sched
	      WHERE id = $rsid
SQL;
	    if(!pg_query($db, $stmt))
	      return;
	  }
	  return True;
	}
	function choose_patient() {
		$k = $this->loo_pi->chosen();
		$a = mx_form_unescape_key($k);
		$this->patient_ID = trim($a[0]);
		$this->patient_ObjectID = trim($a[1]);
		$this->patient_Name = trim($a[2]) . trim($a[3]);
	}

	function choose_dr() {
		$k = $this->loo_modality->chosen();
		$a = mx_form_unescape_key($k);
		$this->modality = trim($a[0]);
		$this->modality_Name = trim($a[1]);
	}

	function left_pane() {
	  mx_formi_hidden($this->prefix . 'appt-to-modify', mx_form_escape_key($this->appt_to_modify));
		mx_formi_hidden($this->prefix . 'last-modality', mx_form_encode_name($this->modality));
		mx_formi_hidden($this->prefix . 'last-modality_Name', mx_form_encode_name($this->modality_Name));
		print '<div>';
		mx_titlespan('Select Patient');
		$this->loo_pi->draw();
		print '</div>';
		print '<hr />';

		print '<div>';
		if ($this->loo_pi->chosen()) {
//0110-2014 mpdality 
//			mx_titlespan($this->modality_type . 'Select');
mx_titlespan('DR ' . 'Select');
			$this->loo_modality->draw();
			print "<br>";
			if($this->loo_appt) {
			  mx_titlespan('History');
			  $this->loo_appt->draw();
			}
		} else if (0) {
			print "<ul>\n";
			foreach (array("予約者一覧" => 'list',
				       "患者予約実績" => 'hist',
				       "予約削除" => 'remove') as $k => $v) {
				print '<li><a href="appt-list.php?';
				print "ListApptMode=$v";
				print '">';
				print htmlspecialchars($k);
				print "</a></li>\n";
			}
			print "</ul>\n";
		}
		print '</div>';
	}

	function appttime() {
		global $_mx_appt_hours;
		mx_formi_select('appttime',
				$this->data['appttime'],
				$_mx_appt_hours,
				NULL);
	}

	function apptdur() {
		global $_mx_appt_durs;

		mx_formi_select('apptdur',
				$this->data['apptdur'],
				$_mx_appt_durs,
				NULL);
	}

	function appt_data() {
		global $_mx_appt_hours, $_mx_appt_num_days;
		$basetime = time();

		$dates = array();
		$days = array();
		$appt = array();
		for ($cnt = 0; $cnt < $_mx_appt_num_days; $cnt++) {
			$date = '(u/u)';
			$day = '(u)';
			$dates[] = $date;
			$days[] = $day;
			$oneday = array();
			foreach ($_mx_appt_hours as $hcm) {
				$oneday[$hcm] = '(u)';
			}
			$appt[] = $oneday;
		}
		$result = array();
		$result['dates'] = $dates;
		$result['days'] = $days;
		$result['appt'] = $appt;
		return $result;
	}

	function draw_calendar_cell($i, $j, $extra='') {
		print "<td class=\"cell vacant\"${extra} id=\"apptcal-$i-$j\"";
		print " onclick=\"apptcal_click('apptcal', 'apptdate', 'appttime', $i, $j, 'CheckOnly');\"";
		print " onmouseover=\"tooltip_on(this, event);\"";
		print " onmouseout=\"tooltip_off(this, event);\"";
		print ">-</td>";
	}

	function show_calendar_pane() {
		global $_mx_appt_hours, $_mx_appt_num_days;
		global $_mx_apptment_divider;
		global $_mx_appt_days;

		print "<br /><hr />";

		print "<div id='apptcal-div'>";
		mx_titlespan("ResvStatus (". $this->modality_Name . ")");

		$data = $this->appt_data();
		$days = $data['days'];
		$dates = $data['dates'];

		$prev="\"apptcal_page_click('apptcal', 'prev');\"";
		$next="\"apptcal_page_click('apptcal', 'next');\"";

		print "<table class=\"apptcal\" id=\"apptcal\"><tr>";
		print "<td rowspan=\"2\" class=\"control\">";
		print mx_img_url('lt-arrow.png', "前へ", "onclick=$prev");
		print "</td>";
		print "<td rowspan=\"2\" class=\"control\">";
		print mx_img_url('rt-arrow.png', "後へ", "onclick=$next");
		print "</td>";
		for ($i = 0; $i < $_mx_appt_num_days; $i++) {
			print "<td class=\"day\" id=\"apptcal-day-$i\">";
			print "―</td>";
		}
		print "</tr>\n<tr>";
		for ($i = 0; $i < $_mx_appt_num_days; $i++) {
			$to = ("appt-list.php?ListApptMode=list_1&amp;" .
			       "ListApptDate=");
			$id = "apptcal-date-$i";
			$oc = "apptcal_label_click('apptcal', '$to', '$i');";
			print "<td class=\"date\" id=\"$id\" onclick=\"$oc\">";
			print "--/--</td>";
		}
		print "</tr>\n";
		$j = 0;
		$bt = 'border-top: dotted 2px; border-top-color: #844;';
		if ($_mx_apptment_divider != '')
			$bl = 'border-left: dotted 2px; border-left-color: #844;';
		else
			$bl = '';
		$nd = count($_mx_appt_days);
		foreach ($_mx_appt_hours as $hcm) {
			$style = NULL;
			if ($hcm == $_mx_apptment_divider)
				$style = $bt;
			$extra = $style ? " style=\"$style\"" : '';
			print "<tr><td class=\"hour\"${extra} colspan=\"2\">$hcm</td>";
			$i = 0;
			$w = date('w') - 1;
			foreach ($data['appt'] as $appt) {
				$extra2 = $extra;
				if (($i+$w) % $nd == 0)
					$extra2 = " style=\"$style$bl\"";
				$this->draw_calendar_cell($i, $j, $extra2);
				$i++;
			}
			print "</tr>\n";
			$j++;
		}
		print "</table>\n";
		print "</div>\n";
		$basetime = time();
		$modality = $this->modality;
		print "<script>load_appt_data('apptcal-div', 'apptcal', $basetime, $modality);</script>\n";
	}

	function done_processing() {
		$vv = array();
		$vvv = array();
		foreach ($this->created_apptment as $k => $v) {
			$vvv["appt.$k"] = $v;
			$v = htmlspecialchars($v);
			$vv[] = "$k=$v";
		}

		$this->show_appt($vvv, 'appt');
		$param = implode('&amp;', $vv);

		//--- for ppa applications
		$vvvv = array('SetPatient' => 1,
			      'PatientID' => $this->created_apptment['patient_ID'],
			      'SampleDate' => $this->created_apptment['apptdate'],
			      'New' => 'New',
			      );
		$vvvvv = array();
		foreach($vvvv as $k => $v) {
		  $v = htmlspecialchars($v);
		  $vvvvv[] = "$k=$v";
		}
		$param2 = implode('&amp;', $vvvvv);
		
		print ("<button type=\"button\" " .
		       "onclick=\"window.open('print.php?" .
		       "type=appt&amp;$param','','width=640,height=640')\">");
		print "Print</button>\n";
/*
		print ("<button type=\"button\" " .
		       "onclick=\"window.open('mail.php?" .
		       "type=appt&amp;$param','','width=640,height=640')\">");
		print "Mail</button>\n";

		
		print "<a href=\"../../u/test/order2.php?$param2\">TestOrder</a>";
*/

	}

	function show_appt($data, $prefix='') {
		mx_titlespan("RsvForm");

		if ($prefix != '')
			$prefix = "$prefix.";

		print "<table class=\"tabular-data wide\">\n";
//0110-2014
		foreach (array('patient_ID' => 'PID',
			       'patient_Name' => 'Name',
//0101-2014
			       'modality_Name' =>
//$data["$prefix" . "modality_Type"],
			       'DR/DOCK',
			       'apptdate' => 'RsvDate',
			       'appttime' => 'RsvTime') as $key => $label) {
			print "<tr>\n";
			print "<th>$label</th><td>";
			print $data["$prefix$key"];
			print "</td>";
			print "</tr>\n";
		}
		print "</table>\n";
	}

	function right_pane() {
	  if ($this->cancelled_apptment){
	    $this->msg_div_0();
	    print "予約を取消ました";
	    $this->msg_div_1();

	  }
		if ($this->created_apptment) 
			return $this->done_processing();

		if (!$this->loo_pi->chosen())
			return;

		mx_titlespan('Reserve');

		if ($this->appt_to_modify &&
		    $this->appt_to_modify[4] <date("Y-m-d")) {
		  //print "過去の予約は参照のみです";
		} else if ($this->conflicts) {
			$this->msg_div_0();
			print "予約時刻には別の予定が入っている等で";
			print "予約できません。";
			print "<br />";
			print "重複していても良ければそのまま、";
			print "そうでなければ、時刻を訂正して";
			print "やり直して下さい。\n";
			mx_formi_hidden('duplicate-accept',
					$this->conflicts);
			$this->msg_div_1();
		}
		else if ($this->check_ok) {
			$this->msg_div_0();
			if($this->appt_to_modify)
			  print "予約を変更しますか？";
			else
			  print "予約を作成しますか？";
			$this->msg_div_1();
		}

		print "<table class=\"tabular-data\" width=\"100%\">\n";
		print "<thead><col width=\"120px\"><col></thead>\n";
		print "<tr><th>PID</th><td>";
		print htmlspecialchars($this->patient_ID);
		print "</td></tr>\n";
		print "<tr><th>Name</th><td>";
		print htmlspecialchars($this->patient_Name);
		print "</td></tr>\n";

		if ($this->loo_modality->chosen() || $this->loo_appt->chosen()) {
			$special = array('オペ' => 1,
					 '小児科検診' => 1);
			$vocab_key = '予約目的';
			if (array_key_exists($this->modality_Name, $special))
				$vocab_key = $vocab_key . $this->modality_Name;

			print "<tr><th>";
//0110-2014
//			print $this->modality_type;
print "DR";
			print "</th><td>";
			print htmlspecialchars($this->modality_Name);
			print "</td></tr>\n";
			print "<tr><th>RsvDate</th><td>";
			mx_formi_date('apptdate', $this->data['apptdate']);
			print "</td></tr>\n";
			print "<tr><th>RsvTime</th><td>";
			$this->appttime();
			print "from";
			$this->apptdur();
			print "minutes</td></tr>\n";
			print "<tr><th>Package</th><td>";
			mx_formi_textarea('apptobjective',
					  $this->data['apptobjective'],
					  array('vocab' => array($vocab_key),
						'rows' => 1));
			print "</td></tr>\n";
		}
		print "</table>\n";

		if ($this->loo_modality->chosen() || $this->loo_appt->chosen()) {
		  if($this->appt_to_modify) {
		    if($this->appt_to_modify[4] <date("Y-m-d"))
		      print "ShowOnly for last history";
		    else {
		      mx_formi_submit_x('ModifyAppt', '予約変更',
					array('class' => 'green'));
		      mx_formi_submit_x('CancelAppt', '予約取消',
					array('class' => 'red'));
		      mx_formi_submit_x('Noop', '何もしない',
					array('class' => 'yellow'));
		    }
				$this->show_calendar_pane();
		  }else if (!$this->check_ok) {
		    mx_formi_submit_x('MakeAppt', 'Reserve',
				    array('class' => 'green'));
				$this->show_calendar_pane();
			}
			else {
			  mx_formi_submit_x('MakeAppt', '予約する',
				    array('class' => 'green'));
			  mx_formi_submit_x('Noop', '何もしない',
				    array('class' => 'yellow'));
			}
		}
	}

	function msg_div_0() {
		print '<div class="confirm-message">';
	}

	function msg_div_1() {
		print '</div>';
	}

	function ctl_div_0() {
		print '<div class="confirm-control">';
	}

	function ctl_div_1() {
		print '</div>';
	}

	function draw_appt() {

		$tentative_allowed = 0;

		$this->msg_div_0();
		if ($this->checkin_ID) {
			print ("上記の患者を、受付患者リストにて" .
			       "受付します。良いですね");
			mx_formi_hidden('checkin_ID', $this->checkin_ID);
		}
		else {
			print ("上記の患者を、受付患者リストに追加" .
			       "します。良いですね");
			mx_formi_hidden('checkin_PatientObjectID',
					$this->patient_ObjectID);
			$tentative_allowed = 1;
		}
		$this->msg_div_1();

		$this->ctl_div_0();
		mx_formi_submit('CheckinPatient', '受付');
		if ($tentative_allowed)
			mx_formi_submit('CheckinPatientTentative', '当日予約');
		mx_formi_submit('Cancel', 'やめ');
		$this->ctl_div_1();
	}

  function appbar_filter($path, $name, $pid) {
	  if ($path == 'u/everybody/encounter-mode-flip.php')
		  return 0;
	  if (trim($pid) == '' && is_encounter_state_application($path))
		  return 0;
	  return 1;
  }

}

class reception_appt_application extends reception_appt_base {

	var $modality_type = "医師";

	function modality_list($prefix) {
		return new appt_dr_list($prefix);
	}

}

class reception_test_appt_application extends reception_appt_base {

	var $modality_type = "検査";

	function modality_list($prefix) {
		return new appt_nondr_list($prefix);
	}

}

?>
