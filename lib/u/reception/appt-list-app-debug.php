<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';

function __lib_u_reception_appt_list_cfg($date, $range, $patient, $past_end_appt_tm=True, $nolink=True)
{
	global $_mx_show_all_appointments;

	
	
$cfg = array(TABLE => 'xctorder',
		     
		     LIST_IDS => array( orderdate, plandate, 
       procdate, "����", teikikubun, xctkubun, techname, techid, bui1, 
       bui2, bui3, bui4, bui5, memo1, memo2, memo3, memo4, memo5, memo11, 
       memo21, memo31, memo41, memo51, memo12, memo22, memo32, memo42, 
       memo52, syoken1, syoken2, syoken3, syoken4, syoken5, techsyoken, 
       drsyoken, proof),
		     HSTMT => $stmt,
		     COLS => $cols,
		     ENABLE_QBE => array(orderdate),
		     );
	if($nolink)
	  $cfg['NOLINK'] = 'nowrap';

	$cfg['STMT'] = $cfg['HSTMT'];
	// do not limit the number of rows
	$cfg['ROW_PER_PAGE'] = 0;
	return $cfg;
}

class list_of_appt extends list_of_simple_objects {

	function annotate_row_data(&$row) {
		foreach (array(orderdate,plandate,procdate) as $k) {
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
	    SELECT * FROM xctorder
	    
SQL;
	  return mx_db_fetch_single($db, $stmt);
	}

}

class list_of_day_appt extends list_of_appt {

	function list_of_day_appt($prefix, $date) {
		$cfg = __lib_u_reception_appt_list_cfg($date, 0, NULL);
		$cfg['ALLOW_SORT'] = 1;
		$cfg['DEFAULT_SORT'] = '����';
		list_of_simple_objects::list_of_simple_objects($prefix, &$cfg);
	}

	function draw_no_data_message() {
		print '<br />���ꤵ�줿���ˤ�ͽ��ǡ���������ޤ���';
	}

}

class list_of_pt_appt extends list_of_appt {

	function list_of_pt_appt($prefix, $patient, $date, $range, $past=True, $nolink=True) {
                $cfg = __lib_u_reception_appt_list_cfg($date, $range, $patient, $past, $nolink);
		//$cfg['ALLOW_SORT'] = 1;
		$cfg['DEFAULT_SORT'] = 'plandate';
		$this->patient_ID = $patient;
		$this->past_end_appt_tm = $past;
		$this->nolink = $nolink;
		list_of_simple_objects::list_of_simple_objects($prefix, &$cfg);
	}

       function row_paging_keys() {
         return array(plandate, orderdate);
       }

       function row_paging_orders() {
         $paging_keys = $this->row_paging_keys();
         $paging_orders = array();
         foreach ($paging_keys as $col) {
	   if ($col == orderate || $col == pladate)
             $paging_orders[] = 1;
            else
             $paging_orders[] = 0;
         }
         return $paging_orders;
       }

	function base_fetch_stmt_0() {
	  return (list_of_appt::base_fetch_stmt_0() .
		  ' AND orderdate IS NOT NULL');
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
			mx_titlespan("̤����");
			mx_formi_linkalike("ͽ�󥵥ޥ꡼�����",
					   'ListApptMode', 'initial');
		}
	}

	function setup_initial() {
	}

	function draw_left_initial() {

		print "<ul>\n";

		print "<li>";
		mx_formi_linkalike("ͽ��԰���", 'ListApptMode', 'list');
		print "</li>\n";

		print "<li>";
		mx_formi_linkalike("����ͽ�����", 'ListApptMode', 'hist');
		print "</li>\n";

		print "<li>";
		mx_formi_linkalike("ͽ����", 'ListApptMode', 'remove');
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
			mx_titlespan("���դ����");
		mx_formi_date('ListApptDate', $date);
		mx_formi_linkalike("���դ���ꤷ�ư���", 'ListApptMode',
				   'list_1');
		if (!$this->default_mode) {
			print "</form>";
			print $this->open_form_head();
			mx_formi_linkalike("ͽ�󥵥ޥ꡼�����",
					   'ListApptMode', 'initial');
		}
	}

	function setup_list_1() {

		$date = mx_check_request('ListApptDate', '');
		if (mx_db_validate_date($date)) {
			$this->error = '���դ�����������';
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
		mx_formi_linkalike("���ջ�������", 'ListApptMode', 'list');
	}

	function setup_hist() {
	}

	function draw_left_hist() {

		$patientID = mx_check_request('ListApptPatientID', '');
		$date = mx_check_request('ListApptDate', '');
		$range = mx_check_request('ListApptSpan', '');
AND
		"ͽ�����" + interval '$range days' >= '$date'
		AND
		"ͽ�����" - interval '1 days' < '$date'
		if ($this->error)
			mx_titlespan($this->error);
		else
			mx_titlespan("����ID�����ա����֤����");

		print "<br />\n";
		print "����ID: ";
		mx_formi_text('ListApptPatientID', $patientID);

		print "<br />\n";
		print "����: ";
		mx_formi_date('ListApptDate', $date);

		print "�ޤ�";

		mx_formi_select('ListApptSpan', $range,
				array('30' => '30',
				      '60' => '60',
				      '90' => '90',
				      '180' => '180'));

		print "����";

		print "<br />\n";
		mx_formi_linkalike("����",
				   'ListApptMode', 'hist_1');

		if (!$this->default_mode) {
			print "</form>";
			print $this->open_form_head();
			mx_formi_linkalike("ͽ�󥵥ޥ꡼�����",
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
			$error[] = '����ID������������';
		$date = mx_check_request('ListApptDate', '');
		$this->date = $date;
		if (mx_db_validate_date($date))
			$error[] = "���դ�����������";

		$range = mx_check_request('ListApptSpan', '');
		if (!$range)
			$error[] = "���֤�����������";

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
		mx_formi_linkalike("���Ի�������", 'ListApptMode', 'hist');
	}

	function setup_remove() {
	}

	function draw_left_remove() {
		$patientID = mx_check_request('ListApptRemovePatientID', '');
		$date = mx_check_request('ListApptRemoveDate', '');
		if ($this->error)
			mx_titlespan($this->error);
		else
			mx_titlespan("����ID�����դ����");

		print "<br />\n";
		print "����ID: ";
		mx_formi_text('ListApptRemovePatientID', $patientID);

		print "<br />\n";
		print "����: ";
		mx_formi_date('ListApptRemoveDate', $date);

		mx_formi_linkalike("���괵�Ԥλ�������ͽ�����",
				   'ListApptMode', 'remove_1');

		if (!$this->default_mode) {
			print "</form>";
			print $this->open_form_head();
			mx_formi_linkalike("ͽ�󥵥ޥ꡼�����",
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
			$error[] = "����ID������������";
		$this->patient_oid = $oid;

		$date = mx_check_request('ListApptRemoveDate', '');
		$this->date = $date;
		if (mx_db_validate_date($date))
			$error[] = "���դ�����������";

		if (count($error) != 0) {
			$this->error = implode("<br />", $error);
			$this->mode = 'remove';
			return;
		}

		$stmt = <<<SQL
			SELECT orderdate, plandate
			FROM xctorder
			WHERE
			"����" = '$patient_id'
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
					
SQL;
				pg_query($db, $stmt);
			}
			if (!is_null($rsid)) {
				$stmt = <<<SQL
					
SQL;
				pg_query($db, $stmt);
			}
		}

	}

	function draw_left_remove_1() {

		mx_titlespan('ͽ�������ޤ���');

		mx_draw_patientinfo_brief($this->patient_oid);
		print "����: ";
		print $this->date;
		print "<br />";

		if (!$this->default_mode) {
			mx_formi_linkalike("ͽ�󥵥ޥ꡼�����",
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