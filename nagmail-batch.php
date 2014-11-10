<?php // -*- mode: php; coding: euc-japan -*-

# Never run from the web server.  This is for cron job consumption.
if ($_SERVER['DOCUMENT_ROOT'] != '') {
	print "This is not for interactive use\n";
	exit(1);
}

$set = dirname(__FILE__);
$_SERVER['DOCUMENT_ROOT'] = $set;

include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/u/reception/nagmail-batch.php';

$it = new appt_nagmail_batch_remind();
$it->main();
$it = new appt_nagmail_batch_missed();
$it->main();

?>
