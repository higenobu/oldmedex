<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/demo/rx.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/demo/demoorder.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/patient-disease.php';

function _lib_u_demo_order_app_cfg(&$cfg, $tbl)
{

  $tbls = array(array('xctorder', 'xctorder'),
		array('xctorder', 'xctorder'));
  $order_table = $tbls[$tbl][0];
  $detail_table = $tbls[$tbl][1];

  switch ($tbl) {
  case 0: /* Rx */
	  global $_mx_orca_send_rx_on_orderdate;
	  $kkc = ($_mx_orca_send_rx_on_orderdate
		  ? '処方年月日' : '処方開始日');
	  break;
  case 1: /* Injection */
	  global $_mx_orca_send_injection_on_orderdate;
	  $kkc = ($_mx_orca_send_injection_on_orderdate
		  ? '処方年月日' : '処方開始日');
	  break;
  }	  
  $cfg = array_merge($cfg, array('TABLE' => $order_table,
				 'DETAIL_TABLE' => $detail_table,
				 'KICK_CLAIM_COLUMN' => $kkc));
}

class demo_order_application extends per_patient_application {
  var $use_printer =1;
  function demo_order_application() {
    global $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    per_patient_application::per_patient_application();
  }

  function print_sod() {
    $this->sod->print_sod();
  }

  function list_of_objects($prefix, $it) {
    $this->cfg_pt($this->my_app_cfg, $it);
    return new list_of_demo_orders($prefix, $this->my_app_cfg);
  }

  function object_display($prefix, &$it) {
    $this->cfg_pt($this->my_app_cfg, $it);
    return new demo_order_display($prefix, $this->my_app_cfg);
  }

  function object_edit($prefix, &$it) {
    $this->cfg_pt($this->my_app_cfg, $it);
    $this->my_app_cfg['u'] = $it->u;

    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $lod = new list_of_patient_diseases('lod-', $cfg);
    $this->my_app_cfg['diseases'] = $lod->current_diseases();
    return new demo_order_edit($prefix, $this->my_app_cfg);
  }

  function left_pane_1() {
    per_patient_application::left_pane_1();

    if ($this->soe &&
	$this->soe->chosen() &&
	!$this->soe->commit_ran)
      $this->soe->rp_edit->draw_pickers();
  }
}

class demo_rx_order_application extends demo_order_application {
  function demo_rx_order_application() {
    $this->my_app_cfg = array();
    _lib_u_demo_order_app_cfg($this->my_app_cfg, 0);
    demo_order_application::demo_order_application();
  }
}

class demo_shot_order_application extends demo_order_application {
  function demo_shot_order_application() {
    $this->my_app_cfg = array();
    _lib_u_demo_order_app_cfg($this->my_app_cfg, 1);
    demo_order_application::demo_order_application();
  }
}

?>
