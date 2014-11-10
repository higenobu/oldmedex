<?php // -*- mode: php; coding: euc-japan -*-
//karte-app.php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/karte.php';
/*
class karte_application extends per_patient_application {
	var $use_printer =1;
var $use_upload = 1;
	var $use_single_pane = 1;
 	var $use_list_of_checkin = 1;
 	var $auto_use_lop = 'ppa_checkin_list';

	function list_of_objects($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new list_of_kartes($prefix, $cfg);
	}

	function object_display($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new karte_display($prefix, $cfg);
	}

	function object_edit($prefix, &$it) {
		$cfg = array();
		$this->cfg_pt($cfg, $it);
		return new karte_edit($prefix, $cfg);
	}
function sod_control_top() {
	  return 1;
  }

function print_sod() {
    $this->sod->print_sod();
  }
}
*/
class karte_application extends per_patient_application {
//  var $use_printer =1;
  var $use_upload = 1;
//1210-2012 1->0
  var $use_single_pane = 1;
//1210-2012 0->1
  var $use_list_of_checkin = 0;
//1210-2012
  var $auto_use_lop = 'ppa_checkin_self_list';
//1212-2012 add this
//var $auto_use_lop = 'ppa_checkin_list';
//0710-2014 use_auto_sod_soe is not defined
//

function print_sod() {
    $this->sod->print_sod();
  }

  function karte_application() {
    global $_mx_template_input;
    global $_mx_use_checkin_list;
    global $_mx_use_two_panes_in_karteview;
    global $_mx_ppa_karte_use_extra_pane_in_unused_soe;
    if ($_mx_ppa_karte_use_extra_pane_in_unused_soe)
      $this->extra_pane_in_unused_soe = 1;

    $this->use_template = $_mx_template_input;
    $this->use_list_of_checkin = $_mx_use_checkin_list;
//0710-2014
    //$this->use_auto_sod_soe_setup = $this->use_auto_sod_soe();
    $this->use_single_pane = !$_mx_use_two_panes_in_karteview;

    per_patient_application::per_patient_application();
  }

  function use_auto_sod_soe() {
    global $_mx_auto_sodsoe_setup;
    return $_mx_auto_sodsoe_setup;
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_kartes($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new karte_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new karte_edit($prefix, $cfg);
  }

  function sod_control_top() {
	  return 1;
  }

}

class karte_static_application extends ppa_static
{
	function list_of_objects($prefix, $cfg) {
		return new list_of_karte_static(prefix, $cfg);
	}

	function object_display($prefix, $cfg) {
		return new karte_display_static($prefix, $cfg);
	}
}

?>
