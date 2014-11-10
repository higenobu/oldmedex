<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';

class everybody_index_pt_application extends per_patient_application {
	var $use_single_pane = 1;
	var $_browse_only = 1;
//11-01-2014
	var $use_list_of_checkin = 0;
	var $use_patient_history = 0;

	function everybody_index_pt_application() {
		global $_mx_use_checkin_list;
		$this->use_list_of_checkin = $_mx_use_checkin_list;
		if ($this->use_list_of_checkin)
			$this->auto_use_lop = 'ppa_checkin_self_list';
		else
			$this->auto_use_lop = 'ppa_patient_list';
		per_patient_application::per_patient_application();

		$this->date_to = mx_check_request('IndexPtToDate', NULL);
		$this->date_span = mx_check_request('IndexPtSpan', NULL);
		if (is_null($this->date_to) ||
		    mx_check_request('SetPatient')) {
//today1 is one month advance in common.php 11-1-2014
			$this->date_to = mx_today1_string();
//from 90 to 720 days	0620-2014		$this->date_span = 86400*90;
			$this->date_span = 86400*720;
		} else if (array_key_exists('IndexPtToPrev', $_REQUEST)) {
			$this->date_to = $this->date_adjust($this->date_to,
							    -$this->date_span);
		} else if (array_key_exists('IndexPtToNext', $_REQUEST)) {
			$this->date_to = $this->date_adjust($this->date_to,
							    $this->date_span);
		}
		$this->date_from = $this->date_adjust($this->date_to,
						      -$this->date_span);
	}

	function appbar_filter($path, $name, $pid) {
		if (trim($pid) == '') {
			/*
			 * Do not show applications that set encounter to
			 * "finished" and such when seeing no patient.
			 */
			if (is_encounter_state_application($path))
				return 0;
		} else {
			/*
			 * Do not show applications that switch encounter
			 * mode between Inpatient and Outpatient when
			 * already seeing a patient.
			 */
			if ($path == 'u/everybody/encounter-mode-flip.php')
				return 0;
		}
		return 1;
	}

	function date_adjust($date, $shift) {
		$time = mx_datetime_to_unixtime($date . " 00:00:00") + $shift;
		$today = localtime($time, 1);
		return sprintf("%04d-%02d-%02d",
			       $today['tm_year'] + 1900,
			       $today['tm_mon'] + 1,
			       $today['tm_mday']);
	}

	function left_pane_1() {
//1125-2012 open and close  tab
//0611-2014 only listed application
$ptid1=$this->patient_ID;
//echo '<script>blah = window.open("../doctor/karteview.php?SetPatient=1&PatientID='."$ptid1".'"'.', )</script>';
/* 
print '
<script type="text/javascript">
    function winOpen(){'.
//        'mywindow1=window.open("../doctor/karteview.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'. 
//      'mywindow3=window.open("../test/order2.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'.
'mywindow2=window.open("../pharmacy/rx_order.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'. 
      'mywindow1=window.open("../doctor/karteview.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'. 
 
'}
   function closeWin() {
	 
mywindow1.close();
mywindow3.close();
mywindow2.close();
    }';
print '</script>';
*/
print '
<script type="text/javascript">'.
     
//        'mywindow1=window.open("../doctor/karteview.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'. 
//      'mywindow3=window.open("../test/order2.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'.
//'mywindow2=window.open("../pharmacy/rx_order.php?SetPatient=1&PatientID='."$ptid1".'"'.' );'. 
      'mywindow1=window.open("../doctor/karte-app.php?SetPatient=1&PatientID='."$ptid1".'"'.',"width=200, height=100" );'; 
 ;print '</script>';
//0617-2014*************

//11-10-2014 do not use this refresh
/*
print '
<body onload="JavaScript:AutoRefresh();">
</body>';

*/



//******************11-01-2014
//do not show mulittab open close button
/*
print '<a   href="index-pt.php"> MAIN </a>';

print '<a href="../../logout.php"> LOGOUT </a>';
print '<a href="javascript:void(0)" onclick="winOpen();"> OPENTAB </a>';
 
print '<a href="javascript:closeWin();"> CLOSETAB </a>';

//print '<a href="javascript:location.reload(true)">Refresh Page</a>';
print '<a href="javascript:window.location=window.location;">Refresh Page</a>';

print '<br />';
*/

//1125-2012
		print "表示期間 ";
		mx_formi_date('IndexPtToDate', $this->date_to);
		print 'まで';
		mx_formi_select('IndexPtSpan', $this->date_span,
				array(86400*30 => '30日間',
				      86400*60 => '60日間',
				      86400*90 => '90日間',
				      86400*180 => '180日間',
				      86400*360 => '360日間',
				      86400*720 => '720日間',
				      ),
				array('immediate-submit' => 1));
		print ' ';
		print '<button>更新</button>';
		print '<button name="IndexPtToPrev">前へ</button>';
		if ($this->date_from < mx_today_string())
			print '<button name="IndexPtToNext">後へ</button>';
		print '<br>';

		index_pt_left_pane_1($this->patient_ObjectID,
				     $this->patient_ID,
				     $this->date_from, $this->date_to);


	}

}

class everybody_encounter_mode_flip_application extends everybody_index_pt_application {

	function everybody_encounter_mode_flip_application() {
		everybody_index_pt_application::everybody_index_pt_application();
		if (is_null($this->u))
			return;
		$e = $this->encounter_mode;

 
		if (is_null($e) || $e != 'O')
			$ee = 'O';
		else
			$ee = 'I';
 


		mx_set_encounter_mode($this->u, $ee);
	}

	function main() {
		mx_redirect_to_user_top($this->u);
	}
}

class everybody_finish_encounter_application extends everybody_index_pt_application {

	function main() {
		if (array_key_exists('SetPatient', $_REQUEST) &&
		    array_key_exists('PatientID', $_REQUEST) &&
		    $this->encounter_mode == 'O') {
			mx_finish_encounter_drop_checkin
				($this->u, $_REQUEST['PatientID']);
		}
		mx_redirect_to_user_top($this->u);
	}
}

class everybody_interrupt_encounter_application extends everybody_index_pt_application {

	function main() {
		if (array_key_exists('SetPatient', $_REQUEST) &&
		    array_key_exists('PatientID', $_REQUEST) &&
		    $this->encounter_mode == 'O') {
			mx_note_checkin_list_use($this->u, '', '');
		}
		mx_redirect_to_user_top($this->u);
	}

}

?>
