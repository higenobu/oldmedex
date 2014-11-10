<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/procedure/order.php';
/*

flippage in the left pane switches:
    History --> history subpick
    Set --> set subpick
    Category --> category subpick
*/
function __lib_u_procedure_order_application_cfg(&$cfg) {
  $cfg['APAGES'] = array('履歴', 'セット','処置名');
}

class procedure_order_application extends per_patient_application {

  var $use_upload = 1;
  var $can_use_subpick_on_left = 1;
  var $auto_use_lop = 'ppa_checkin_list';
  var $use_list_of_checkin = 1;

  function procedure_order_application() {
    $cfg = array();
    __lib_u_procedure_order_application_cfg(&$cfg);
    $this->app_config = $cfg;
    $this->prefix = 'order_app-';

    if (array_key_exists($this->prefix . 'page-to', $_REQUEST))
      $this->page = $_REQUEST[$this->prefix . 'page-to'];
    elseif (array_key_exists($this->prefix . 'page', $_REQUEST))
      $this->page = $_REQUEST[$this->prefix . 'page'];
    else
      $this->page = 0;

    # cancel order
    if($_REQUEST['cancel_order']) {
      $userid = mx_authenticate_user();
      $o = $_REQUEST['cancel_order'];
      $db = mx_db_connect();
      // HACK
      $data = mx_db_fetch_single($db,
	         sprintf("select * from procedure_order where \"ObjectID\"=%d", $o));
      if($data) {
	pg_query($db, sprintf('update procedure_order set "Cancelled"=now(), "CanceledBy"=%d where "ObjectID"=%d', $userid, $o));
	$date = $data['ExecDate'];
	$match = array();
	if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
		$date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);
		mx_kick_claim_if_by_poid($db, $data["Patient"], $date);
	}
      }
    }
    per_patient_application::per_patient_application();
  }

  function list_of_objects($prefix, &$it) {
    global $_lib_u_procedure_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_procedure_order_cfg;
    $this->cfg_pt($cfg, $it);
    return new list_of_procedure_orders($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    global $_lib_u_procedure_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_procedure_order_cfg;
    $this->cfg_pt($cfg, $it);
    return new procedure_order_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    global $_lib_u_procedure_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_procedure_order_cfg;
    $this->cfg_pt($cfg, $it);
    return new procedure_order_edit($prefix, $cfg);
  }


  function draw_flippage() {
    $pages = $this->app_config['APAGES'];
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

    if ($this->page != 0 || $this->soe->chosen())
      $this->draw_flippage();

    if ($this->page == 0) {
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
    elseif ($this->page == 1 ){
      if (array_key_exists('sod-id', $_REQUEST))
	mx_formi_hidden('sod-id', $_REQUEST['sod-id']);
      
      $cfg=array();
      $lts = new list_of_procedure_sets($prefix . "set-", $cfg);
      $lts->draw();
    }
    elseif ($this->page == 2){
      if (array_key_exists('sod-id', $_REQUEST)) 
	mx_formi_hidden('sod-id', $_REQUEST['sod-id']);
      $ltc2 = new list_of_proceduremaster2('proceduremaster2-');
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
