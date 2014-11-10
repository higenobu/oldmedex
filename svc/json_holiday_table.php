<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

function monthoffset($year, $month, $offset)
{
	$month = $month + $offset;
	while ($month <= 0) {
		$month += 12;
		$year--;
	}
	while ($month >= 13) {
		$month -= 12;
		$year++;
	}
	return sprintf("%04d-%02d", $year, $month);
}

$ym = $_REQUEST['ym'];
$modality = NULL;
$year = substr($ym, 0, 4);
$month = substr($ym, 5, 2);

$range = $_REQUEST['range'];
if (!$range)
	$range = 1;
$yearmonth = array();

for ($offset = -$range; $offset <= $range; $offset++) {
	$yearmonth[] = monthoffset($year, $month, $offset);
}

$since = sprintf("%s-21", monthoffset($year, $month, -$range - 1));
$until = sprintf("%s-07", monthoffset($year, $month, +$range + 1));
$calendar = mx_sched_holiday($since, $until, $modality);

print "{\n	";
$sep = ",\n	";
foreach ($yearmonth as $ym) {
	printf("'%s': 2%s", $ym, $sep);
}
foreach ($calendar as $d => $h) {
	print "'$d': $h$sep";
}
print "}\n";
?>
