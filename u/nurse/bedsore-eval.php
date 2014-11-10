<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/bedsore-eval.php';

class nurse_bedsore_eval_application extends per_patient_application {

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_nurse_bedsore_evals($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new nurse_bedsore_eval_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new nurse_bedsore_eval_edit($prefix, $cfg);
  }

}

$main = new nurse_bedsore_eval_application();
$main->main();
?>
