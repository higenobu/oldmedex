<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/mediserve.php';

$dbh = mx_db_connect();
$checker = new mediserve_checker($dbh);
$doc = $checker->find_document($_GET['med']);

mx_html_head('MediServeII ╦║╨В');
print $doc;
?>
<button onClick="window.close()">йд╓╦╓К</button>
</body></html>
