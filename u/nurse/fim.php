<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/fim-application.php';
class fim_application_N extends fim_application {
  var $side = 'N';
}
$main = new fim_application_N();
$main->main();
?>
