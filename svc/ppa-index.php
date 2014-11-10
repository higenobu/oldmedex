<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/index-pt.php';

mx_authenticate_user();
index_pt_left_pane_1($_REQUEST['poid'], $_REQUEST['pid']);
?>


