<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/mykarte/mykarte-app.php';

mykarte_update_user_info();
mx_http_redirect("index.php");

?>
