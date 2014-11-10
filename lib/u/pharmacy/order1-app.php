<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rx3.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/order1.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/patient-disease.php';

function _lib_u_pharmacy_order1_app_cfg(&$cfg, $tbl)
{

  $tbls = array(array('薬剤処方箋', '薬剤処方箋内容'),
		array('注射処方箋', '注射処方箋内容'));
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

class pharmacy_order1_application extends simple_object_application {
  var $use_printer =1;
  function pharmacy_order1_application() {
    global $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    pharmacy_order1_application::pharmacy_order1_application();

  function print_sod() {
    $this->sod->print_sod();
  }

  function list_of_objects($prefix, $it) {
    $this->cfg_pt($this->my_app_cfg, $it);
    return new list_of_pharmacy_order1s($prefix, $this->my_app_cfg);
  }

  function object_display($prefix, &$it) {
    $this->cfg_pt($this->my_app_cfg, $it);
    return new pharmacy_order1_display($prefix, $this->my_app_cfg);
  }

  function object_edit($prefix, &$it) {
    $this->cfg_pt($this->my_app_cfg, $it);
    $this->my_app_cfg['u'] = $it->u;

    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $lod = new list_of_patient_diseases('lod-', $cfg);
    $this->my_app_cfg['diseases'] = $lod->current_diseases();
    return new pharmacy_order1_edit($prefix, $this->my_app_cfg);
  }

  function left_pane_1() {
    per_patient_application::left_pane_1();

    if ($this->soe &&
	$this->soe->chosen() &&
	!$this->soe->commit_ran)
      $this->soe->rp_edit->draw_pickers();
  }
}

class pharmacy_rx_order1_application extends pharmacy_order1_application {
  function pharmacy_rx_order1_application() {
    $this->my_app_cfg = array();
    _lib_u_pharmacy_order1_app_cfg($this->my_app_cfg, 0);
    pharmacy_order1_application::pharmacy_order1_application();
  }
}

class pharmacy_shot_order1_application extends pharmacy_order1_application {
  function pharmacy_shot_order1_application() {
    $this->my_app_cfg = array();
    _lib_u_pharmacy_order1_app_cfg($this->my_app_cfg, 1);
    pharmacy_order1_application::pharmacy_order1_application();
  }
}

?>
