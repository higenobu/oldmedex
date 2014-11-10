<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';
//0815-2012 add  "Í½Ìó½ªÎ»»þ¹ï" is not null to SQL
//1010-2012 appt_list1 from appt_list
//1113-2012 appt_list1 again  ------and  "Í½Ìó½ªÎ»»þ¹ï" is not null  
SQL;
function __lib_u_reception_appt_list_cfg($date, $range, $patient, $past_end_appt_tm=True, $nolink=True)
{
	global $_mx_show_all_appointments;

	$cols = array("´µ¼ÔID", "À«", "Ì¾",
		      "Í½ÌóÀè", "Í½Ìó¼ïÊÌ", "ÌÜÅª",
		      "Í½Ìó»þ¹ï", "Í½Ìó½ªÎ»»þ¹ï", "¼õÉÕ»þ¹ï");
	$stmt = <<<SQL
		SELECT rsched_id,
		"´µ¼ÔID", "À«", "Ì¾", "Í½ÌóÀè", "Í½Ìó¼ïÊÌ", "ÌÜÅª",
	  "Í½Ìó»þ¹ï", "Í½Ìó½ªÎ»»þ¹ï", "¼õÉÕ»þ¹ï",
	  cast("Í½Ìó»þ¹ï" as date) as apptdate,
	  cast("Í½Ìó»þ¹ï" as time) as appttime,
          extract(HOUR FROM "Í½Ìó½ªÎ»»þ¹ï" - "Í½Ìó»þ¹ï") * 60 +
          extract(MINUTE FROM "Í½Ìó½ªÎ»»þ¹ï" - "Í½Ìó»þ¹ï") as apptdur,
	  appt_id, modality_id
		FROM APPT_LIST1 WHERE (NULL IS NULL)   and "Í½Ìó½ªÎ»»þ¹ï" is not null 
SQL;
	if ($date) {
		$stmt .= <<<SQL
		AND
		"Í½Ìó»þ¹ï" + interval '$range days' >= '$date'
		AND
		"Í½Ìó»þ¹ï" - interval '1 days' < '$date'
SQL;
	}

	if ($_mx_show_all_appointments)
		$end_appt_tm_cond = '';
	else if ($past_end_appt_tm)
		$end_appt_tm_cond = 'AND "Í½Ìó½ªÎ»»þ¹ï" < now()';
	else
		$end_appt_tm_cond = 'AND "Í½Ìó½ªÎ»»þ¹ï" > now()';

	if ($patient) {
		$stmt .= <<<SQL
		AND
		  "´µ¼ÔID" = '$patient' $end_appt_tm_cond
SQL;
/*
		$cols = array(array('Column' =>"Í½ÌóÀè",'Label' => 'Reserv'),
array('Column' => "Í½Ìó¼ïÊÌ",'Label' => 'Category'),
array('Column'=>"ÌÜÅª",'Label' => 'Objective'),
array('Column'=>"Í½Ìó»þ¹ï",'Label' => 'RsvTime'),¡¡¡¡
array('Column'=>"Í½Ìó½ªÎ»»þ¹ï",'Label' => 'RsvEndTime'), 
array('Column'=>"¼õÉÕ»þ¹ï",'Label' => 'CheckTime'),);
*/
$cols("Í½ÌóÀè");
	}
	$cfg = array(TABLE => 'appt_list',
		     UNIQ_ID => 'rsched_id',
		     UNIQ_KEY => 'rsched_id',
		     LIST_IDS => array('rsched_id', "´µ¼ÔID", "Í½ÌóÀè", "ÌÜÅª",
				       'apptdate', 'appttime', 'apptdur', 'appt_id', 'modality_id'),
		     HSTMT => $stmt,
		     COLS => $cols,
		     ENABLE_QBE => array("Í½ÌóÀè"),
		     );
	if($nolink)
	  $cfg['NOLINK'] = 'nowrap';

	$cfg['STMT'] = $cfg['HSTMT'];
	// do not limit the number of rows
//11-13-2012
print "AAAA".$stmt."AAAAA";
	$cfg['ROW_PER_PAGE'] = 0;
	return $cfg;
}

class list_of_appt extends list_of_simple_objects {

	function annotate_row_data(&$row) {
		foreach (array('Í½Ìó»þ¹ï', 'Í½Ìó½ªÎ»»þ¹ï', '¼õÉÕ»þ¹ï') as $k) {
			if (!array_key_exists($k, $row))
				continue;
			$m = array();
			if (preg_match('/^(\d+-\d+-\d+ \d+:\d+):[.\d]+$/',
				       $row[$k], &$m)) {
				$row[$k] = $m[1];
			}
		}
	}

	function exists($rsched_id) {
	  $db = mx_db_connect();
	  $stmt = <<<SQL
	    SELECT * FROM APPT_LIST
	    WHERE rsched_id=$rsched_id 
SQL;
	  return mx_db_fetch_single($db, $stmt);
	}

}

class list_of_day_appt extends list_of_appt {

	function list_of_day_appt($prefix, $date) {
		$cfg = __lib_u_reception_appt_list_cfg($date, 0, NULL);
		$cfg['ALLOW_SORT'] = 1;
		$cfg['DEFAULT_SORT'] = '´µ¼ÔID';
		list_of_simple_objects::list_of_simple_objects($prefix, &$cfg);
	}

	function draw_no_data_message() {
		print '<br />»ØÄê¤µ¤ì¤¿Æü¤Ë¤ÏÍ½Ìó¥Ç¡¼¥¿¤¬¤¢¤ê¤Þ¤»¤ó¡£';
	}

}

class list_of_pt_appt extends list_of_appt {

	function list_of_pt_appt($prefix, $patient, $date, $range, $past=True, $nolink=True) {
                $cfg = __lib_u_reception_appt_list_cfg($date, $range, $patient, $past, $nolink);
		//$cfg['ALLOW_SORT'] = 1;
		$cfg['DEFAULT_SORT'] = 'Í½Ìó»þ¹ï';
		$this->patient_ID = $patient;
		$this->past_end_appt_tm = $past;
		$this->nolink = $nolink;
		list_of_simple_objects::list_of_simple_objects($prefix, &$cfg);
	}

       function row_paging_keys() {
         return array('Í½Ìó»þ¹ï', '¼õÉÕ»þ¹ï');
       }

       function row_paging_orders() {
         $paging_keys = $this->row_paging_keys();
         $paging_orders = array();
         foreach ($paging_keys as $col) {
	   if ($col == 'Í½Ìó»þ¹ï' || $col == '¼õÉÕ»þ¹ï')
             $paging_orders[] = 1;
            else
             $paging_orders[] = 0;
         }
         return $paging_orders;
       }
//11-13-2012 AND "Í½Ìó»þ¹ï" IS NOT NULL
	function base_fetch_stmt_0() {
	  return (list_of_appt::base_fetch_stmt_0() .
		  ' AND "Í½Ìó»þ¹ï" IS NOT NULL ');
	}
}

class reception_list_appt_application extends single_table_application {

	var $_browse_only = 1; // no "New" "Edit" etc controls please
	var $default_mode = NULL;

	function setup_widgets() {

		single_table_application::setup_widgets();

		if ($this->default_mode &&
		    !mx_check_request('ListApptMode', NULL))
			$mode = $this->default_mode;
		else
			$mode = mx_check_request('ListApptMode', 'initial');
		$this->mode = $mode;
		$fn = "setup_$mode";
		$this->$fn();

	}

	function left_pane() {
		$mode = $this->mode;
		if ($mode != 'notyet') {
			$fn = "draw_left_$mode";
			$this->$fn();
		} else {
			mx_titlespan("Ì¤¼ÂÁõ");
			mx_formi_linkalike("Í½Ìó¥µ¥Þ¥ê¡¼¤ËÌá¤ë",
					   'ListApptMode', 'initial');
		}
	}

	function setup_initial() {
	}

	function draw_left_initial() {

		print "<ul>\n";

		print "<li>";
		mx_formi_linkalike("Í½Ìó¼Ô°ìÍ÷", 'ListApptMode', 'list');
		print "</li>\n";

		print "<li>";
		mx_formi_linkalike("´µ¼ÔÍ½Ìó¼ÂÀÓ", 'ListApptMode', 'hist');
		print "</li>\n";

		print "<li>";
		mx_formi_linkalike("Í½Ìóºï½ü", 'ListApptMode', 'remove');
		print "</li>\n";

		print "</ul>\n";
	}

	function setup_list() {
	}

	function draw_left_list() {
		$date = mx_check_request('ListApptDate', '');
		if (trim($date) == '')
			$date = mx_today_string();
		if ($this->error)
			mx_titlespan($this->error);
		else
			mx_titlespan("ÆüÉÕ¤ò»ØÄê");
		mx_formi_date('ListApptDate', $date);
		mx_formi_linkalike("ÆüÉÕ¤ò»ØÄê¤·¤Æ°ìÍ÷", 'ListApptMode',
				   'list_1');
		if (!$this->default_mode) {
			print "</form>";
			print $this->open_form_head();
			mx_formi_linkalike("Í½Ìó¥µ¥Þ¥ê¡¼¤ËÌá¤ë",
					   'ListApptMode', 'initial');
		}
	}

	function setup_list_1() {

		$date = mx_check_request('ListApptDate', '');
		if (mx_db_validate_date($date)) {
			$this->error = 'ÆüÉÕ¤òÀµ¤·¤¯»ØÄê';
			$this->mode = 'list';
			return;
		}
		$this->loo = new list_of_day_appt('loa', $date);
		$this->loo->use_printer = 1;
	}

	function draw_left_list_1() {
	  
		ob_start();
		$this->loo->draw();
		$v = ob_get_contents();
		ob_end_clean();

		print "$v";

		if ($_REQUEST['Print']) {
		  $db = mx_db_connect();
		  $id = mx_db_insert_blobmedia(&$db, 'text/html', $v);
		  print <<<HTML
		  <SCRIPT LANGUAGE="JavaScript">
		    window.open("print2.php?blobmedia=${id}", "","width=640,height=640");
		  </SCRIPT>
HTML;
		}
		mx_formi_hidden('ListApptMode', 'list_1');
		$date = mx_check_request('ListApptDate', '');
		mx_formi_hidden('ListApptDate', $date);

		print "</form>";
		print $this->open_form_head();
		mx_formi_linkalike("ÆüÉÕ»ØÄê¤ËÌá¤ë", 'ListApptMode', 'list');
	}

	function setup_hist() {
	}

	function draw_left_hist() {

		$patientID = mx_check_request('ListApptPatientID', '');
		$date = mx_check_request('ListApptDate', '');
		$range = mx_check_request('ListApptSpan', '');

		if ($this->error)
			mx_titlespan($this->error);
		else
			mx_titlespan("´µ¼ÔID¡¢ÆüÉÕ¡¢´ü´Ö¤ò»ØÄê");

		print "<br />\n";
		print "´µ¼ÔID: ";
		mx_formi_text('ListApptPatientID', $patientID);

		print "<br />\n";
		print "ÆüÉÕ: ";
		mx_formi_date('ListApptDate', $date);

		print "¤Þ¤Ç";

		mx_formi_select('ListApptSpan', $range,
				array('30' => '30',
				      '60' => '60',
				      '90' => '90',
				      '180' => '180'));

		print "Æü´Ö";

		print "<br />\n";
		mx_formi_linkalike("°ìÍ÷",
				   'ListApptMode', 'hist_1');

		if (!$this->default_mode) {
			print "</form>";
			print $this->open_form_head();
			mx_formi_linkalike("Í½Ìó¥µ¥Þ¥ê¡¼¤ËÌá¤ë",
					   'ListApptMode', 'initial');
		}
	}

	function setup_hist_1() {
		global $_mx_patient_id_zeropad;

		$patient_id = mx_check_request('ListApptPatientID', '');
		$patient_id = trim($patient_id);
		$patient_id = mb_convert_kana($patient_id, 'as', 'euc');
		$patient_id = mx_zeropad($patient_id, $_mx_patient_id_zeropad);

		$error = array();

		$oid = mx_find_patient_by_patient_id($patient_id);
		if (is_null($oid))
			$error[] = '´µ¼ÔID¤òÀµ¤·¤¯»ØÄê';
		$date = mx_check_request('ListApptDate', '');
		$this->date = $date;
		if (mx_db_validate_date($date))
			$error[] = "ÆüÉÕ¤òÀµ¤·¤¯»ØÄê";

		$range = mx_check_request('ListApptSpan', '');
		if (!$range)
			$error[] = "´ü´Ö¤òÀµ¤·¤¯»ØÄê";

		if (count($error)) {
			$this->error = implode("<br />", $error);
			$this->mode = 'hist';
			return;
		}
		$this->patient_oid = $oid;
		$this->date = $date;
		$this->range = $range;
		$this->loo = new list_of_pt_appt('loa', $patient_id,
						 $date, $range);
	}

	function draw_left_hist_1() {

		mx_draw_patientinfo_brief($this->patient_oid);
		$this->loo->draw();
		mx_formi_hidden('ListApptMode', 'hist_1');
		$patientID = mx_check_request('ListApptPatientID', '');
		mx_formi_hidden('ListApptPatientID', $patientID);
		mx_formi_hidden('ListApptDate', $this->date);
		mx_formi_hidden('ListApptSpan', $this->range);

		print "</form>";
		print $this->open_form_head();
		mx_formi_linkalike("´µ¼Ô»ØÄê¤ËÌá¤ë", 'ListApptMode', 'hist');
	}

	function setup_remove() {
	}

	function draw_left_remove() {
		$patientID = mx_check_request('ListApptRemovePatientID', '');
		$date = mx_check_request('ListApptRemoveDate', '');
		if ($this->error)
			mx_titlespan($this->error);
		else
			mx_titlespan("´µ¼ÔID¤ÈÆüÉÕ¤ò»ØÄê");

		print "<br />\n";
		print "´µ¼ÔID: ";
		mx_formi_text('ListApptRemovePatientID', $patientID);

		print "<br />\n";
		print "ÆüÉÕ: ";
		mx_formi_date('ListApptRemoveDate', $date);

		mx_formi_linkalike("»ØÄê´µ¼Ô¤Î»ØÄêÆü¤ÎÍ½Ìó¤ò¼è¾Ã",
				   'ListApptMode', 'remove_1');

		if (!$this->default_mode) {
			print "</form>";
			print $this->open_form_head();
			mx_formi_linkalike("Í½Ìó¥µ¥Þ¥ê¡¼¤ËÌá¤ë",
					   'ListApptMode', 'initial');
		}
	}

	function setup_remove_1() {

		global $_mx_patient_id_zeropad;

		$patient_id = mx_check_request('ListApptRemovePatientID', '');
		$patient_id = trim($patient_id);
		$patient_id = mb_convert_kana($patient_id, 'as', 'euc');
		$patient_id = mx_zeropad($patient_id, $_mx_patient_id_zeropad);

		$error = array();

		$oid = mx_find_patient_by_patient_id($patient_id);
		if (is_null($oid))
			$error[] = "´µ¼ÔID¤òÀµ¤·¤¯»ØÄê";
		$this->patient_oid = $oid;

		$date = mx_check_request('ListApptRemoveDate', '');
		$this->date = $date;
		if (mx_db_validate_date($date))
			$error[] = "ÆüÉÕ¤òÀµ¤·¤¯»ØÄê";

		if (count($error) != 0) {
			$this->error = implode("<br />", $error);
			$this->mode = 'remove';
			return;
		}

		$stmt = <<<SQL
			SELECT appt_id, rsched_id
			FROM APPT_LIST
			WHERE
			"´µ¼ÔID" = '$patient_id' AND
			"¼õÉÕ»þ¹ï" IS NULL AND
			"Í½Ìó»þ¹ï" >= '$date' AND
			"Í½Ìó»þ¹ï" - interval '1 days' < '$date'
SQL;
		$db = mx_db_connect();
		$st = pg_query($db, $stmt);
		if (!$st)
			return;

		$data = pg_fetch_all($st);
		if (!$data)
			return;
		foreach ($data as $d) {
			$oid = $d['appt_id'];
			$rsid = $d['rsched_id'];
			if (!is_null($oid)) {
				$stmt = <<<SQL
					DELETE FROM "¼õÉÕ´µ¼ÔÉ½"
					WHERE "ObjectID" = $oid
SQL;
				pg_query($db, $stmt);
			}
			if (!is_null($rsid)) {
				$stmt = <<<SQL
					DELETE FROM resource_sched
					WHERE id = $rsid
SQL;
				pg_query($db, $stmt);
			}
		}

	}

	function draw_left_remove_1() {

		mx_titlespan('Í½Ìóºï½ü¤·¤Þ¤·¤¿');

		mx_draw_patientinfo_brief($this->patient_oid);
		print "ÆüÉÕ: ";
		print $this->date;
		print "<br />";

		if (!$this->default_mode) {
			mx_formi_linkalike("Í½Ìó¥µ¥Þ¥ê¡¼¤ËÌá¤ë",
					   'ListApptMode', 'initial');
		}
	}

}

class reception_list_appt_list_application extends reception_list_appt_application {
	var $default_mode = 'list';
}

class reception_list_appt_hist_application extends reception_list_appt_application {
	var $default_mode = 'hist';
}

class reception_list_appt_remove_application extends reception_list_appt_application {
	var $default_mode = 'remove';
}

?>
