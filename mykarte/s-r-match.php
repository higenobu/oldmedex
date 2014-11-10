<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/mykarte/s-r-match-app.php';

$app = new mykarte_s_r_match_application();
$app->main();
?>