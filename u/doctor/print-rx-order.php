<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/print-rx-order-app.php';

$main = new print_rx_order_application();
$main->main();
?>
