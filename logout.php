<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
$u = mx_authenticate_user();

mx_authenticate_logout();
mx_http_redirect('/login.php');
?>
