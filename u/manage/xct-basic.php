<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/xct-basic.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/appbar.php';

class xct_basic_application extends single_table_application {
  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');
  var $_loo_title = 'xct';
  var $_sod_title = 'xct-display';
  var $_soe_title = 'xct-edit';
  var $_soc_title = 'xct-reg';
  var $top_inside_form = 1;
  var $use_card = 0;

  var $msgs = array
  ('New' => '新規を登録する',
   'New Like This' => 'コピー',
   'Edit' => '編集する',
   'Card' => '診察券を発行する',
   );
  function list_of_objects($prefix) {
    return new list_of_xct_basics($prefix);
  }
  function object_display($prefix) {
    return new xct_basic_display($prefix);
  }
  function object_edit($prefix) {
    return new xct_basic_edit($prefix);
  }

  function setup() {
    $v = single_table_application::setup();
    if ($v)
	    return $v;
    
    

    return 0;
  }

  function top_pane() {
    global $_mx_use_appbar;

    print "<table><tr valign=\"top\"><td width=\"50%\">";
    if ($_mx_use_appbar)
      $this->top_pane_left(0);
    else
      $this->top_pane_left(1);
    print "</td><td>";
    if ($this->loo && $this->loo->chosen()) {
      $data = mx_draw_patientinfo_brief($this->loo->chosen());
      
    }
    print "</td></tr></table>\n";
    if ($_mx_use_appbar)
	    mx_appbar($this);
  }

  function issue_card() {
    $this->sod->issue_card();
  }
}

$pba = new xct_basic_application();
$pba->main();
?>
