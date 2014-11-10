<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/vital-data.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/chartdirector/phpchartdir.php';

class everybody_vital_data_application extends per_patient_application {

  var $use_list_of_checkin = 0;
  var $auto_use_lop = 'ppa_checkin_list';

  function everybody_vital_data_application() {
    global $_mx_template_input;
    global $_mx_use_checkin_list;
    global $_mx_auto_sodsoe_setup;
//  $this->use_template = 1;
//$this->use_template = $_mx_template_input;
    $this->use_list_of_checkin = $_mx_use_checkin_list;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    per_patient_application::per_patient_application();
  }

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new list_of_everybody_vital_datas($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new everybody_vital_data_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    return new everybody_vital_data_edit($prefix, $cfg);
  }

  function right_pane_1() {
    # show chart only when no item is chosen in los
    global $_mx_cheap_layout;
    global $_mx_chartdirector;
    
    $_empty = True;
    if ($this->_browse_only) {
      if ($this->sod->chosen()) {
	 $this->sod_pane();
	 $_empty = False;
      }
    }
    else {
      if ($this->soe->chosen()) {
	      if (!$_mx_cheap_layout) {
		      if ($this->soe->creating())
			      mx_titlespan($this->soc_title());
		      else
			      mx_titlespan($this->soe_title());
	      }
	      if ($this->use_subpick_on_left)
		      $this->soe->separate_subpick = 1;
	      $this->soe->draw();
	      $_empty = False;
      }
    }
/*
    if($_empty and $_mx_chartdirector) {
      $pt_oid = $this->patient_ObjectID;
      $cookie = getenv('URL_PREFIX_COOKIE');
      if($cookie)
	print "<img src=\"/au/${cookie}/u/everybody/vital-graph.php?pt_oid=${pt_oid}\">";
    }
*/

  }
}

?>
