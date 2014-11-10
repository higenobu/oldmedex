<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/u/test/print_historical_content.php';

function _print_pdf_js() {
  return <<<JS
<SCRIPT>
  function print_pdf(type, patient_id) 
  {
    var selectors = document.getElementsByName('selector[]');
    if (selectors == null)
      return false;
    var s = '';
    var count=0;
    for(var i=0; i < selectors.length; i++)
      {
	if (!selectors[i].checked)
	  continue;
	if (s != '')
	  s += ',';
	s += selectors[i].value;
	count += 1;
      }

    if (count < 1 || count > 6) {
      alert("検査は１から６つまで選択してください");
      return false;
    }


    window.open('print_historical_content.php?pdf=1&type=' + type + '&patient_id=' + patient_id + '&selected=' + s);
    return false;
  }
</SCRIPT>
JS;
}

class test_print_historical_application extends per_patient_application {
  var $use_printer = 1;
  var $use_single_pane = 1;
 
  var $auto_use_lop = 'ppa_checkin_list';
 var $use_list_of_checkin = 1;
  var $app_type=0;

  function draw_plain_new_control($vertical=0) {
    global $_mx_resource_dir;

    if(is_null($this->patient_ID))
      return;
//0927-2014

//print '<a href="index.php?tab=1">go back to main</a>';

    printf( '<input onclick="print_pdf(%s, \'%s\'); return false;" type="image" src="/%s/images/printer.png">',
	    $this->app_type,
	    $this->patient_ID,
	    $_mx_resource_dir);
  }


  function allow_new() {
    return 0;
  }
  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $x = new list_of_test_history($prefix, $cfg);
    $x->patient_ID = $this->patient_ID;
    return $x;
  }
}

class list_of_test_history {
  function lost_selection() { return 0; }
  function changed() { return 1; }
  function chosen() { $this->chosen_; }
  function allow_new() { return False;}
  function reset() {}
  function draw() {
    if (is_array($_REQUEST['selector']))
      $selected = $_REQUEST['selector'];

    if ($_REQUEST['switch']) {
	$selected=array();
      }
    $times = $_REQUEST['times'] ? $_REQUEST['times'] : '6';
    mx_formi_radio("times", $times, array(3 => '最近3回分',
					  6 => '6回分を選択'));
    print _print_pdf_js();
    print '<INPUT TYPE="SUBMIT" name="switch" VALUE="変更">';
    print print_historical_content(0, $this->patient_ID, $times,
				   $selected);
  }
}

class stest_print_historical_application extends 
test_print_historical_application {
  var $app_type=1;
  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $x = new list_of_stest_history($prefix, $cfg);
    $x->patient_ID = $this->patient_ID;
    return $x;
  }
}

class list_of_stest_history {
  function lost_selection() { return 0; }
  function changed() { return 1; }
  function chosen() { $this->chosen_; }
  function allow_new() { return False;}
  function reset() {}
  function draw() {
    if (is_array($_REQUEST['selector']))
      $selected = $_REQUEST['selector'];

    if ($_REQUEST['switch']) {
	$selected=array();
      }
    $times = $_REQUEST['times'] ? $_REQUEST['times'] : '6';
    mx_formi_radio("times", $times, array(3 => '最近3回分',
					  6 => '6回分を選択'));
    print _print_pdf_js();
    print '<INPUT TYPE="SUBMIT" name="switch" VALUE="変更">';
    print print_historical_content(1, $this->patient_ID, $times,
				   $selected);
  }
}


?>
