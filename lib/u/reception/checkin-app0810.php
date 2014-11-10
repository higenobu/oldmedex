<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/checkin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/status.php';

class reception_checkin_application extends single_table_application {

	var $_browse_only = 1;
	var $show_patient_cols = array(array("患者ID", "氏名"),
				       array("性別", "生年月日"));

	var $default_mode = 'without-appointment';
	var $use_redraw = 1;
	var $draw_immediate_at_top = 1;

	function setup_widgets() {

		single_table_application::setup_widgets();

	$this->mode = $_REQUEST['ApplicationMode'];

		
		if ($this->mode == '')
			$this->mode = $this->default_mode;
			
		if (array_key_exists('CheckinPatient', $_REQUEST))
			$this->handle_checkin_patient();
		else if (array_key_exists('CheckinImmediate', $_REQUEST))
			$this->handle_immediate_checkin();
		else if (array_key_exists('CheckoutPatient', $_REQUEST))
			$this->handle_checkout_patient();
		else if (array_key_exists('CancelCheckin', $_REQUEST))
			$this->handle_cancel_checkin();
		else if (array_key_exists('CancelAppt', $_REQUEST))
			$this->handle_cancel_appt();
		else if (array_key_exists('ImmediateCheckIn', $_REQUEST))
			$this->mode = 'without-appointment';
		else if (array_key_exists('AppointmentCheckIn', $_REQUEST))
			$this->mode = 'with-appointment';
		else if (array_key_exists('UseThisPatientID', $_REQUEST))
			$this->mode = $this->start_with_patient_id();

		if ($this->mode == 'ask-patient-id') {

			; /* Nothing in particular */

		} else if ($this->mode == 'with-appointment') {
			$this->loo_ci = new reception_checkin_list('loo-ci-');

			if ($this->appt_pre_select)
				$this->loo_ci->set_selection
					($this->appt_pre_select);
			if ($this->loo_ci->changed() &&
			    $this->loo_ci->chosen())
				$this->choose_checkin();

		} else {
			$this->loo_pi = new reception_patient_list('loo-pi-');
			$this->loo_dr = new appt_dr_list('loo-dr-');

			if (array_key_exists('Cancel', $_REQUEST)) {
				$this->loo_pi->reset(NULL);
				$this->loo_dr->reset(NULL);
			}
			if ($this->checkin_pre_select) {
				$this->loo_pi->set_selection
					($this->checkin_pre_select);
			}
			if ($this->loo_pi->chosen())
				$this->choose_patient();
			if ($this->loo_pi->changed()) {
				$this->loo_dr->patient =
					$this->patient_ObjectID;
				$this->loo_dr->reset(NULL);
			}
			if ($this->loo_dr->chosen())
				$this->choose_dr();
		}

	}

	function left_pane() {
		global $_mx_reception_redraw_label;

		if (trim($_mx_reception_redraw_label) != '')
			$redraw_label = $_mx_reception_redraw_label;
		else
			$redraw_label = '再描画';

		print '<div>';
		mx_formi_hidden('ApplicationMode', $this->mode);

		if ($this->mode == 'ask-patient-id') {

			mx_titlespan('患者 ID を入力');
			print '<span class="inputpatientidfromcardreader">';
			mx_formi_text('PatientID', '');
			print '</span>';

		} else if ($this->mode == 'with-appointment') {
			if ($this->draw_immediate_at_top)
				mx_formi_submit('ImmediateCheckIn',
						'予約のない患者を受付');
			if ($this->use_redraw && $redraw_label != 'never')
				mx_formi_submit('NoopJustRedrawMe',
						$redraw_label);

			if ($this->draw_immediate_at_top || $this->use_redraw)
				print "<br /><hr />";

			mx_titlespan('予約・受付済患者を選択');
			$this->loo_ci->draw();
		} else {
			mx_titlespan('予約のない患者を選択');
			$this->loo_pi->draw();
			if ($this->loo_pi->chosen()) {
				print "<hr />\n";
				$this->loo_dr->draw();
			}
		}
		print '</div>';
		if ($this->fromlist)
			return;
		print "<hr />\n";

		if ($this->mode == 'ask-patient-id')
			mx_formi_submit('UseThisPatientID',
					'患者 ID を入力');
		else if ($this->mode == 'with-appointment') {
			if (!$this->draw_immediate_at_top) {
				mx_formi_submit('ImmediateCheckIn',
						'予約のない患者を受付');
			}
		}
		else
			mx_formi_submit('AppointmentCheckIn',
					'予約・受付済患者の受付');
	}

	function start_with_patient_id() {
		global $_mx_patient_id_zeropad;

		$patient_id = trim(mx_check_request('PatientID'));
		$patient_id = mb_convert_kana($patient_id, 'as', 'euc');
		$patient_id = mx_zeropad($patient_id, $_mx_patient_id_zeropad);

		$oid = mx_find_patient_by_patient_id($patient_id);
		if (!$oid)
			return 'with-appointment';

		$r = _lib_u_reception_checkin_pt_has_appt($oid);
		if ($r) {
			$this->appt_pre_select = $r;
			return 'with-appointment';
		}
		$r = _lib_u_reception_checkin_pt($oid);
		if ($r)
			$this->checkin_pre_select = $r;
		return 'without-appointment';
	}

	function choose_checkin() {
		$v = $this->loo_ci->chosen_data();

		$this->patient_ID = trim($v['患者ID']);
		$this->patient_ObjectID = trim($v['患者']);
		$this->patient_Name = trim($v['姓']) . trim($v['名']);
		$this->checkin_ID = $v['ObjectID'];
		$this->checkin_Time = $v['受付時刻'];
		$this->appt_Time = $v['予約時刻'];

		$this->fromlist = 'checkin';
	}

	function choose_patient() {
		$k = $this->loo_pi->chosen();
		$a = mx_form_unescape_key($k);
		$this->patient_ID = trim($a[0]);
		$this->patient_ObjectID = trim($a[1]);
		$this->patient_Name = trim($a[2]) . trim($a[3]);
		$this->fromlist = 'patient';
	}

	function choose_dr() {
		$k = $this->loo_dr->chosen();
		$a = mx_form_unescape_key($k);
		$this->modality = trim($a[0]);
		// $this->modality_Name = trim($a[1]);
		$this->modality_Name = $a[1];
	}

	function right_pane() {
		if (!$this->fromlist)
			return;

		mx_draw_patientinfo_custom($this->patient_ObjectID,
					   $this->show_patient_cols);

		if ($this->fromlist == 'checkin') {
			if ($this->checkin_Time)
				$action = 'checkout';
			else
				$action = 'checkin';
		} else {
			$action = 'immediate';
		}
		if ($action == 'checkin')
			$this->draw_checkin();
		else if ($action == 'checkout')
			$this->draw_checkout();
		else if ($action == 'immediate')
			$this->draw_immediate();
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

	function draw_checkout() {
		global $_mx_keep_done_patients_on_reception_list;

		$this->msg_div_0();
		print "この患者さんを受付患者リストから削除しますか？";
		$this->msg_div_1();
		mx_formi_hidden('checkin_ID', $this->checkin_ID);
		mx_formi_hidden('appt_Time', $this->appt_Time);

		$this->ctl_div_0();

		if ($_mx_keep_done_patients_on_reception_list)
			$end_and_del = '診療完了';
		else
			$end_and_del = '診療完了して削除';
		mx_formi_submit('CheckoutPatient', $end_and_del);
		mx_formi_submit('CancelCheckin', '受付を取消す');
		mx_formi_submit('Cancel', '何もせず閉じる');
		$this->ctl_div_1();
	}

	function draw_checkin() {
		$this->msg_div_0();
		print ("この患者さんを受付済みにしますか？");
		mx_formi_hidden('checkin_ID', $this->checkin_ID);
		$this->msg_div_1();

		$this->ctl_div_0();
		mx_formi_submit('CheckinPatient', '受付');
		mx_formi_submit('CancelAppt', '予約受付とも削除');
		mx_formi_submit('Cancel', '何もせず閉じる');
		$this->ctl_div_1();
	}

	function draw_immediate() {
		mx_formi_hidden('patient_ID', $this->patient_ID);
		mx_formi_hidden('patient_ObjectID', $this->patient_ObjectID);
		mx_formi_hidden('patient_Name', $this->patient_Name);

		if (!$this->modality_Name) {
			$this->msg_div_0();
			print "医師を選択して下さい";
			$this->msg_div_1();

			$this->ctl_div_0();
			mx_formi_submit('Cancel', '何もせず閉じる');
			$this->ctl_div_1();
			return;
		}

		mx_formi_hidden('modality', $this->modality);

		$this->msg_div_0();
		print ("この患者さんを受け付けますか？");
		print "<br />\n";
		print "医師: ";
		print $this->modality_Name;
		$this->msg_div_1();

		print "目的: ";
		mx_formi_textarea('commentinput', '');

		$this->ctl_div_0();
		mx_formi_submit('CheckinImmediate', '受付');
		mx_formi_submit('Cancel', '何もせず閉じる');
		$this->ctl_div_1();
	}

	function update_checkin_time($id, $t='now()') {
		$stmt = 'UPDATE "受付患者表" SET "受付時刻" = ' . $t .
			' WHERE "ObjectID" = ' . mx_db_sql_quote($id);
		$db = mx_db_connect();
		pg_query($db, $stmt);
	}

	function pid_from_checkin_id(&$db, $checkin_ID) {
		$checkin_ID = mx_db_sql_quote($checkin_ID);
		$stmt = <<<SQL
		SELECT "患者" FROM "受付患者表" WHERE "ObjectID" = $checkin_ID
SQL;
		$result = mx_db_fetch_single($db, $stmt);
		if ($result && ($pid = $result["患者"]))
			return $pid;
		return NULL;
	}

	function request_claim_processing($checkin_ID) {
		$db = mx_db_connect();
		$pid = $this->pid_from_checkin_id(&$db, $checkin_ID);
		if ($pid) {
			$stmt = <<<SQL
				INSERT INTO claim_request
				(patient, date_since, date_until) VALUES
				($pid, current_date, current_date)
SQL;
			pg_query($db, $stmt);
		}
	}

	function record_checkout($id) {
		$stmt = 'UPDATE "受付患者表" SET "ステータス" = 1, ' .
			'"診療終了時刻" = now() ' .
			'WHERE "ObjectID" = ' . mx_db_sql_quote($id);
		$db = mx_db_connect();
		pg_query($db, $stmt);
		$pid = $this->pid_from_checkin_id(&$db, $id);
		$this->switch_to_done($pid);
	}

	function delete_checkin_record($id) {

		$db = mx_db_connect();
		$id = mx_db_sql_quote($id);
		$stmt = <<<SQL
		SELECT rsched FROM "受付患者表" WHERE "ObjectID" = $id
SQL;
		$result = mx_db_fetch_single($db, $stmt);
		if ($result && $result['rsched']) {
			$rsched = mx_db_sql_quote($result['rsched']);
			$stmt = <<<SQL
				DELETE FROM resource_sched
				WHERE id = $rsched
SQL;
			pg_query($db, $stmt);
		}
		$stmt = ('DELETE FROM "受付患者表" WHERE "ObjectID" = ' .
			 $id);
		pg_query($db, $stmt);
	}

	function immediate_checkin($patient, $modality, $comment) {
		global $_mx_use_checked_in_patient_status;
		global $mx_authenticate_current_user;
		$me = $mx_authenticate_current_user;
		$stmt = 'INSERT INTO "受付患者表"
("CreatedBy", "目的",
 "患者", "患者ID", "受付時刻", "予約先", "姓", "名", "フリガナ")
SELECT ' . mx_db_sql_quote($me) . ' , ' . mx_db_sql_quote($comment) .
', "ObjectID", "患者ID", now(), ' .
mx_db_sql_quote($modality) . ', "姓", "名", "フリガナ"
FROM "患者台帳"
WHERE "Superseded" IS NULL AND "ObjectID" = ' . mx_db_sql_quote($patient);
		$db = mx_db_connect();
		pg_query($db, $stmt);

		$this->switch_to_waiting($patient);
	}

	function handle_checkin_patient() {
		$checkin_ID = mx_check_option('checkin_ID', $_REQUEST);
		$this->update_checkin_time($checkin_ID);
		$db = mx_db_connect();
		$pid = $this->pid_from_checkin_id(&$db, $checkin_ID);
		$this->switch_to_waiting($pid);
	}

	function handle_checkout_patient() {
		$checkin_ID = mx_check_option('checkin_ID', $_REQUEST);
		$this->record_checkout($checkin_ID);
		$this->request_claim_processing($checkin_ID);
	}

	function handle_cancel_checkin() {
		$checkin_ID = mx_check_option('checkin_ID', $_REQUEST);
		if (mx_check_option('appt_Time', $_REQUEST))
			$this->update_checkin_time($checkin_ID, 'NULL');
		else
			$this->delete_checkin_record($checkin_ID);
	}

	function handle_cancel_appt() {
		$checkin_ID = mx_check_option('checkin_ID', $_REQUEST);
		$this->delete_checkin_record($checkin_ID);
	}

	function handle_immediate_checkin() {
		$patient = mx_check_option('patient_ObjectID', $_REQUEST);
		$modality = mx_check_option('modality', $_REQUEST);
		$comment = mx_check_option('commentinput', $_REQUEST);
		$this->immediate_checkin($patient, $modality, $comment);

		$this->mode = 'with-appointment';
	}

	function switch_state($patient, $state) {
		global $_mx_use_checked_in_patient_status;
		if (!$_mx_use_checked_in_patient_status)
			return;
		reception_status_adjust($patient, $state);
	}

	function switch_to_waiting($patient) {
		$this->switch_state($patient, '診察待ち');
	}

	function switch_to_done($patient) {
		$this->switch_state($patient, '診察完了');
	}

}

class reception_checkin_card_application extends reception_checkin_application {
	var $default_mode = 'ask-patient-id';
}
?>
