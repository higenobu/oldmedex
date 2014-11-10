<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/checkin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';

class reception_kick_claim_application extends single_table_application {

	var $_browse_only = 1;
	var $show_patient_cols = array(array("患者ID", "氏名"),
				       array("性別", "生年月日"));

	function setup_widgets() {
		global $_mx_patient_id_zeropad;

		single_table_application::setup_widgets();

		if (array_key_exists('PatientID', $_REQUEST)) {
			$patient_id = trim(mx_check_request('PatientID'));
			$patient_id = mb_convert_kana($patient_id, 'as',
						      'euc');
			$patient_id = mx_zeropad($patient_id, $_mx_patient_id_zeropad);
			$_REQUEST['PatientID'] = $patient_id;
		}

		$this->kicked_claim = 0;
		$this->confirm_patient = 0;
		$this->no_such_patient = 0;

		if (array_key_exists('GoBackToInitial', $_REQUEST))
			;
		else if (array_key_exists('Date', $_REQUEST) &&
			 $this->check_date_input()) {
			$this->bad_date = 1;
		}
		else if (array_key_exists('DoUseThisPatientID', $_REQUEST))
			$this->kick_claim();
		else if (array_key_exists('UseThisPatientID', $_REQUEST)) {
			switch ($this->is_inpatient()) {
			case 1:
				$this->confirm_patient = 1;
				break;
			case 0:
				$this->kick_claim();
				break;
			default:
				$this->no_such_patient = 1;
				break;
			}
		}
	}

	function check_date_input() {
		$date = $_REQUEST['Date'];
		return mx_db_validate_date($date);
	}

	function is_inpatient() {
		$patient_id = mx_check_request('PatientID');
		$patient_id = mx_db_sql_quote($patient_id);

		$stmt = <<<SQL
			SELECT "入外区分"
			FROM "患者台帳"
			WHERE "患者ID" = $patient_id AND "Superseded" IS NULL
SQL;
		$db = mx_db_connect();
		$d = mx_db_fetch_single($db, $stmt);
		if ($d) {
			if ($d['入外区分'] == 'I')
				return 1;
			else
				return 0;
		}
		return -1;
	}

	function left_pane() {
		print '<div>';

		if ($this->bad_date) {
			$this->input_params(mx_check_request('PatientID'),
					    1,
					    mx_check_request('Date'));
		}
		else if ($this->no_such_patient) {
			mx_titlespan('患者IDが存在しません');
			print '<span class="inputpatientidfromcardreader">';
			mx_formi_text('PatientID', mx_check_request('PatientID'));
			print '</span>';

			print '<br />';
			print '</div>';
			mx_formi_submit('UseThisPatientID', '送信');
			mx_formi_submit('GoBackToInitial', '間違いました');

		}
		else if ($this->confirm_patient) {
			mx_titlespan('入院患者です');
			print '<span class="inputpatientidfromcardreader">';
			mx_formi_text('PatientID', mx_check_request('PatientID'));
			print '</span>';
			print "<br />日付: ";
			mx_formi_date('Date',
				      mx_check_request('Date', mx_today_string()));

			print '<br />';
			mx_titlespan('本当に送信してよろしいですか？');
			print '</div>';
			mx_formi_submit('DoUseThisPatientID',
					'本当に送信してよろしい');
			mx_formi_submit('GoBackToInitial',
					'間違いました');
		}
		else if ($this->kicked_claim) {
			mx_titlespan('医事へ送信しました');
		}
		else {
			$this->input_params('',
					    '',
					    mx_today_string());
		}
	}

	function input_params($pid, $date_warn, $date) {
		if ($date_warn) {
			mx_titlespan('日付が不正です');
			print "<br />";
		}

		mx_titlespan('患者 ID を入力');
		print '<span class="inputpatientidfromcardreader">';
			mx_formi_text('PatientID', $pid);
			print '</span>';
			print "<br />日付: ";
			mx_formi_date('Date', $date);

			print '</div>';
			mx_formi_submit('UseThisPatientID',
					'患者 ID を入力');
	}

	function right_pane() {
		return;
	}

	function kick_claim() {
		$db = mx_db_connect();
		$pid = mx_check_request('PatientID');
		$date = mx_check_request('Date', mx_today_string());
		mx_kick_claim_if_by_pid($db, $pid, $date, -1);
		$this->kicked_claim = 1;
	}

}
?>
