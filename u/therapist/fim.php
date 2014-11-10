<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/fim-application.php';
class fim_application_T extends fim_application {
  var $side = 'T';
}
$main = new fim_application_T();
$main->main();
?>
