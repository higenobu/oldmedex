<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/sorder.php';


class stest_order_application extends test_order_application {
  function stest_order_application() {
    test_order_application::test_order_application();
  }
  function list_of_objects($prefix, &$it) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_test_order_cfg;
    $this->cfg_pt($cfg, $it);
    return new list_of_stest_orders($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_test_order_cfg;
    $this->cfg_pt($cfg, $it);
    $executed = False;
    if(!is_null($_REQUEST['sod-id']) && $_REQUEST['sod-id'] != "" &&
       !($_REQUEST['New'] or $_REQUEST['NewLikeThis']) &&
       $this->entering_result)
      $executed = $this->check_executed($_REQUEST['sod-id']);
    if($_REQUEST['Edit'])
       $executed = $this->check_executed($_REQUEST['sod-id']);
  
    if($_REQUEST[$prefix . 'soe'] == 'edit2' or $this->entering_result or $executed)
      return new stest_order_edit2($prefix, $cfg);
    return new stest_order_edit($prefix, $cfg);
  }

  function draw_flippage() {
    $pages = array('ÍúÎò', '¸¡ºº¹àÌÜ');
    // Flip Page.
    print "<table class=\"flippage\" width=\"100%\"><tr>";
    $page_num = -1;
    foreach ($pages as $page_name) {
      $page_num++;
      if( ( ! $this->soe->chosen()
	   || $this->soe->commit_ran == 'created'
	   || $this->soe->commit_ran == 'updated') 
	  && $page_num >= 1)
	break;
      if ($page_num == $this->page) {
	print "<td class=\"focused ltcorner\">&nbsp;</td>";
	print "<td class=\"focused\">&nbsp;";
	print $page_name;
	mx_formi_hidden($this->prefix . 'page', $page_num);
	print "&nbsp;</td><td class=\"focused rtcorner\">&nbsp;</td>";
      } else {
	// A page that is hidden
	print "<td class=\"unfocused ltcorner\">&nbsp;</td>";
	print "<td class=\"unfocused\">";
	if ($this->_Subpicker)
	  print $page_name;
	else
	  mx_formi_submit($this->prefix . 'page-to', $page_num, $page_name);
	print "</td><td class=\"unfocused rtcorner\">&nbsp;</td>";
      }
      if ($this->dpage_breaks && in_array($page_num, $this->dpage_breaks))
	print "</tr></table><table class=\"flippage\" width=\"100%\"><tr>";
    }
    print "</tr></table>\n";
  }

  function left_pane_1() {
    global $_mx_use_control_bar;
    if(!$this->soe->chosen()
       || array_key_exists('ShowLoo', $_REQUEST)
       || $this->soe->commit_ran == 'created'
       || $this->soe->commit_ran == 'updated')
      $this->page = 0;

    if(array_key_exists('New', $_REQUEST) || array_key_exists('NewLikeThis', $_REQUEST)) 
      $this->page = 1;
    if ($this->page !=0 || $this->soe->chosen())
       $this->draw_flippage();
    if ($this->page == 0) {
      // history tab
      $loo_drawn = $this->loo->draw();
      if ($this->sod->chosen()) {
        if ($loo_drawn)
	  print '<hr />';
	$this->sod_pane();
      }
      elseif(! $_mx_use_control_bar) {
        print "<br />";
	$this->draw_plain_new_control();
      }
    }
    elseif ($this->page == 1){
      // test category tab
      if (array_key_exists('sod-id', $_REQUEST)) 
	mx_formi_hidden('sod-id', $_REQUEST['sod-id']);

      $cfg = array('CID' => 9);
      $ltc2 = new list_of_testmaster2('testmaster2-', &$cfg);
      $ltc2->CID = 9;
      $ltc2->draw();
    }
    /*
     * This shouldn't need to be here if this class properly
     * called the inherited method.  Sheesh.
     */
    if ($this->use_subpick_on_left && $this->soe) {
	    $soe = $this->soe;
	    $soe->draw_subpick(&$soe->so_config);
    }
  }
}
?>