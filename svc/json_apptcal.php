<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// Unfortunately these are not symmetric
function is_really_array($data)
{
	$count = count($data);

	for ($i = 0; $i < $count; $i++) {
		if (!array_key_exists($i, $data))
			return 0;
	}
	return 1;
}

function array_encode($data)
{
	$count = count($data);

	$encoded = '[';
	$first = 1;
	for ($i = 0; $i < $count; $i++) {
		if (!$first) {
			$encoded .= ",\n";
		}
		$first = 0;
		$encoded .= data_encode($data[$i]);
	}
	$encoded .= ']';
	return $encoded;
}

function data_encode($data)
{
	$encoded = '';

	if (is_array($data)) {
		if (is_really_array($data))
			return array_encode($data);
		$encoded = '{';
		$first = 1;
		foreach ($data as $k => $v) {
			if (!$first) {
				$encoded .= ",\n";
			}
			$first = 0;
			$encoded .= data_encode($k);
			$encoded .= ':';
			$encoded .= data_encode($v);
		}
		$encoded .= "}";
	} else {
		$encoded = sprintf("'%s'", addslashes($data));
	}
	return $encoded;
}

function timestamp_to_date($timestamp)
{
	$d = localtime($timestamp, 1);
	return sprintf('%04d-%02d-%02d',
		       $d['tm_year'] + 1900,
		       $d['tm_mon'] + 1,
		       $d['tm_mday']);
}

function grab_appointments($base, $next, $modality)
{
	$modality = mx_db_sql_quote($modality);
	$base = timestamp_to_date($base);
	$next = timestamp_to_date($next);
	$stmt = "SELECT start_time, end_time, subject
FROM resource_sched
WHERE res_desc = $modality AND
NOT (end_time <= timestamp '$base' OR
     timestamp '$next' <= start_time)";
	$db = mx_db_connect();
	$result = pg_fetch_all(pg_query($db, $stmt));
	if (!$result)
		$result = array();
	return $result;
}

function count_appts($appt, $mdate, $hcm)
{
	$m = array();
	if (!preg_match('/^(\d+):(\d+)$/', $hcm, &$m))
		return 0;
	$h = $m[1];
	$m = $m[2];
	$range0 = "$mdate $h:$m:00";
	if ($m + 30 >= 60) {
		$h = sprintf("%02d", $h + 1);
		$m = sprintf("%02d", $m + 30 - 60);
	} else {
		$m = sprintf("%02d", $m + 30);
	}
	$range1 = "$mdate $h:$m:00";

	$cnt = 0;
	foreach ($appt as $data) {
		$start_time = $data['start_time'];
		$end_time = $data['end_time'];
		if (($end_time <= $range0) || ($range1 <= $start_time))
			continue;
		$cnt++;
	}
	return $cnt;
}

function count_pts($appt, $mdate, $hcm)
{
	$m = array();
	if (!preg_match('/^(\d+):(\d+)$/', $hcm, &$m))
		return 0;
	$h = $m[1];
	$m = $m[2];
	$range0 = "$mdate $h:$m:00";
	if ($m + 30 >= 60) {
		$h = sprintf("%02d", $h + 1);
		$m = sprintf("%02d", $m + 30 - 60);
	} else {
		$m = sprintf("%02d", $m + 30);
	}
	$range1 = "$mdate $h:$m:00";

	$pts = NULL;
	foreach ($appt as $data) {
		$start_time = $data['start_time'];
		$end_time = $data['end_time'];
		$pt = $data['subject'];
		if (($end_time <= $range0) || ($range1 <= $start_time) || is_null($pt))
			continue;
		$pts[] = $pt;
	}
	if($pts)
	  return implode("|", $pts);
	return '';
}

function grab_available($base, $next, $modality)
{

	$base = timestamp_to_date($base);
	$next = timestamp_to_date($next);

	return mx_sched_available($base, $next, $modality);
}

function count_avail($avail, $mdate, $hcm)
{
	$cnts = mx_check_capacity($avail, $mdate, $hcm, 30);
	return $cnts[0];
}

function get_description($avail, $mdate)
{
	$desc = '';
	$range0 = sprintf("%s 00:00", $mdate);
	$range1 = sprintf("%s 99:99", $mdate);
	foreach ($avail as $data) {
		if ($data['description'] == '')
			continue;
		$start_time = $data['start_time'];
		$end_time = $data['end_time'];
		if ($start_time < $range1 && $range0 < $end_time)
			$desc = $data['description'];
	}
	return $desc;
}

function show_appt()
{
	global $_mx_appt_hours, $_mx_appt_days, $_mx_appt_num_days;

	$basetime = $_REQUEST['basetime'];
	$modality = $_REQUEST['modality'];
	$days = array();
	$dates = array();
	$mdates = array();
	$appt = array();
	$hours = array();
	$avail = array();
	$description = array();
	foreach ($_mx_appt_hours as $hcm) {
		$hours[] = $hcm;
	}

	for ($i = 1, $cnt = 0; $cnt < $_mx_appt_num_days; $i++) {
		$prevbase = $basetime - 86400 * $i;
		$d = localtime($prevbase, 1);
		if (!array_key_exists($d['tm_wday'], $_mx_appt_days))
			continue;
		$cnt++;
	}

	for ($i = $cnt = 0; $cnt <= $_mx_appt_num_days; $i++) {
		$nextbase = $basetime + 86400 * $i;
		$d = localtime($basetime + 86400 * $i, 1);
		if (!array_key_exists($d['tm_wday'], $_mx_appt_days))
			continue;
		$cnt++;
	}

	$apptments = grab_appointments($basetime, $nextbase, $modality);
	$available = grab_available($basetime, $nextbase, $modality);

	for ($i = $cnt = 0; $cnt < $_mx_appt_num_days; $i++) {
		$d = localtime($basetime + 86400 * $i, 1);
		if (!array_key_exists($d['tm_wday'], $_mx_appt_days))
			continue;
		$cnt++;
		$day = $_mx_appt_days[$d['tm_wday']];
		$date = sprintf('%02d/%02d', $d['tm_mon']+1, $d['tm_mday']);
		$mdate = sprintf('%04d-%02d-%02d',
				 $d['tm_year'] + 1900,
				 $d['tm_mon']+1,
				 $d['tm_mday']);
		$days[] = $day;
		$dates[] = $date;
		$mdates[] = $mdate;

		$oneday = array();
		foreach ($_mx_appt_hours as $hcm) {
			$oneday[] = count_appts(&$apptments, $mdate, $hcm);
		}
		$appt[] = $oneday;

		$oneday = array();
		foreach ($_mx_appt_hours as $hcm) {
			$oneday[] = count_pts(&$apptments, $mdate, $hcm);
		}
		$pt[] = $oneday;

		$oneday = array();
		foreach ($_mx_appt_hours as $hcm) {
			$oneday[] = count_avail(&$available, $mdate, $hcm);
		}
		$avail[] = $oneday;

		$description[] = get_description(&$available, $mdate);
	}
	$result = array();
	$result['days'] = $days;
	$result['dates'] = $dates;
	$result['mdates'] = $mdates;
	$result['appt'] = $appt;
	$result['pt'] = $pt;
	$result['avail'] = $avail;
	$result['description'] = $description;
	$result['hours'] = $hours;
	$result['basetime'] = $basetime;
	$result['prevbase'] = $prevbase;
	$result['nextbase'] = $nextbase;
	$result['modality'] = $modality;
	$result['maxdups'] = $_mx_appt_max_dups;

	if (0) {
		$result['rawappt'] = $apptments;
		$result['rawavail'] = $available;
	}
	print data_encode($result);
}
show_appt();
?>
