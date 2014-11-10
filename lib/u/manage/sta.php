<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext-service.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/appbar.php';

$__lib_u_manage_sta_default_msg = array
('New' => 'new',

 'New Like This' => 'copy',
 'New With Template' => 'new with template',
 'CSV' => 'CSV',
 'Edit' => 'edit',
//07-01-2012
 'Print' => 'print',
'Print2' => '印刷は一覧に戻ってから',
// 'Card' => '診察券',
 'History' => 'history',
 'History Prev' => 'history prev',
 'History Next' => 'jostpry next',
 'ShowLoo' => 'return to list');

function m3extra() {
	if (!ext_service_available('M3')) {
		$goto = '/au/' . $_SERVER['URL_PREFIX_COOKIE'];
		return <<< HTML
<IFRAME style="border-style: none;" frameboarder=0 marginheight=0 marginwidth=0 width=196 height=600 src="$goto/m3.php"> </IFRAME>
HTML;
	} else if (!$_COOKIE['m3']) {
		$goto = '/au/' . $_SERVER['URL_PREFIX_COOKIE'];
		print '<META HTTP-EQUIV="Set-Cookie" content="m3=1; path=/">';
		return <<< HTML
<IFRAME style="border-style: none;" frameboarder=0 marginheight=0 marginwidth=0 width=196 height=600 src="$goto/m3.php"> </IFRAME>
HTML;
	} else {
		$rand = time();
		return <<<HTML
<IFRAME style="border-style: none;" frameboarder=0 marginheight=0 marginwidth=0 width=196 height=600 src=https://www.m3.com/parts/medex/m3panel.jsp?portalId=medex&rand=${rand}> </IFRAME>
HTML;
	}
}

class single_table_application {

  // List of links (absolute under http://server/u/$cookie/)
  // out of this application.
//1221-2012
  var $_upper = array('index.php' => '/images/top_button1.png');
  var $msg = array('New' => 'new',
		   'New Like This' => 'copy',
		   'New With Template' => 'new with template',
		   'CSV' => 'CSV',
		   'Edit' => 'edit',
//07-2012
		'Print2' => '印刷は一覧に戻ってから',
		   'Print' => 'print',
		   'History' => 'history',
		   'History Prev' => 'prev',
		   'History Next' => 'next',
		   'ShowLoo' => 'return to list');
  var $_loo_title = '[一覧表]';
  var $_sod_title = '[表示]';
  var $_soe_title = '[編集]';
  var $_soc_title = '[作成]';
  var $_browse_only = 0;
  var $left_pane_only = 0;
  var $use_single_pane = 0;
  var $inhibit_showloo_in_cheap_layout = 0;
  var $use_subpick_on_left = 0;
  var $can_use_subpick_on_left = 0;
  var $three_pane = 0;
  var $extra_pane_in_unused_soe = 0;

  var $top_inside_form = NULL;
  var $use_upload = NULL;
  var $use_template = NULL;
  var $use_printer = NULL;
//0702-2012
var $use_printer2 = NULL;
  var $use_create_to_display_switch = NULL;

  function single_table_application () {
    global $__lib_u_manage_sta_default_msg;
    global $_mx_use_subpick_on_left;
    global $_mx_use_create_to_display_switch;

    if ($_mx_use_subpick_on_left == '1' && $this->can_use_subpick_on_left)
	    $this->use_subpick_on_left = 1;
    if ($_mx_use_create_to_display_switch)
	    $this->use_create_to_display_switch = 1;

  $this->msg = $__lib_u_manage_sta_default_msg;
    $this->u = mx_authenticate_user();
    $this->auth = mx_authorization();
    $this->encounter_mode = mx_encounter_mode($this->u);
  }

  function setup_widgets() {
    $this->loo = $this->list_of_objects('loo-');
    $this->sod = $this->object_display('sod-');
    $this->soe = $this->object_edit('soe-');
    if ($this->loo->lost_selection()) {
	    $this->sod->reset(NULL);
	    $this->soe->reset(NULL);
    }
  }

  function emit_CSV_from_sod() {
      header('content-type: text/csv; charset=shift_jis');
      $nl = "\n";
      $cr = "\r";
      $dq = '"';
      $comma = ',';
      foreach ($this->sod->csv_data() as $row) {
	$r = array();
	foreach ($row as $v) {
	  $v = str_replace($cr, '', $v);
	  $v = str_replace($nl, ' ', $v);
	  $v = str_replace($dq, $dq.$dq, $v);
	  if (strchr($v, $comma) !== FALSE || strchr($v, $dq) !== FALSE)
	    $v = $dq . $v . $dq;
	  $r[] = mb_convert_encoding($v, "SJIS", "eucJP-win");
	}
	print implode($comma, $r) . "\r\n";
      }
      print "\32"; // Do they want terminationg ^Z like DOS?
  }

  function print_sod() { // override. set $use_printer=1 as well
  }

  function issue_card() { // override. set $use_card=1 as well
  }

  function setup_restore () {
	  $this->restorestate($_REQUEST['RestoreApplicationState'],
			      $_REQUEST['RestoreAction']);
  }

  function setup () {
    $this->setup_widgets();

    if (array_key_exists('ShowLoo', $_REQUEST))
	    $this->sod->reset(NULL);
    if ($this->loo->changed() && $this->loo->chosen())
      $this->sod->reset($this->loo->chosen());

    if ($this->sod->chosen() && array_key_exists('CSV', $_REQUEST)) {
      $this->emit_CSV_from_sod();
      return 1;
    }
    else if ($this->sod->chosen() && array_key_exists('Print', $_REQUEST)) {
      $this->print_sod();
      //return 1;
    }
    else if (array_key_exists('New', $_REQUEST))
      $this->soe->anew(NULL);
    else if (array_key_exists('NewLikeThis', $_REQUEST))
      $this->soe->anew($this->sod->chosen());
    else if ($this->use_template &&
	     array_key_exists('NewWithTemplate', $_REQUEST))
      return $this->redirect_to_template();
    else if (array_key_exists('Edit', $_REQUEST))
      $this->soe->edit($this->sod->chosen());
    else if (array_key_exists('History', $_REQUEST))
      $this->sod->history($_REQUEST['History']);
    if ($this->use_single_pane)
	    $this->soe->so_config['SOE_EXTRA_OK_AFTER_COMMIT'] = 1;
    if ($this->use_create_to_display_switch) {
	    if ($this->soe->created_object)
		    $this->sod->reset($this->soe->created_object);
    }
    return 0;
  }

  function edit_in_progress() {
    return ($this->soe && $this->soe->edit_in_progress());
  }

  function setup_0 () {
    if (array_key_exists('RestoreApplicationState', $_REQUEST))
	    $this->setup_restore();
    return $this->setup();
  }

  function allow_new() { // Override
	  global $_mx_disable_appbar_during_edit;

	  if ($this->_browse_only)
		  return 0;
	  if ($_mx_disable_appbar_during_edit && $this->edit_in_progress())
		  return 0;
	  return 1;
  }

  function allow_copy() { // Override
	  return $this->allow_edit_copy();
  }

  function allow_edit() { // Override
	  return $this->allow_edit_copy();
  }

  function allow_edit_copy() {
	  global $_mx_disable_appbar_during_edit;

	  if ($this->_browse_only)
		  return 0;
	  if (!$this->sod || !$this->sod->chosen())
		  return 0;
	  if ($_mx_disable_appbar_during_edit && $this->edit_in_progress())
		  return 0;
	  return 1;
  }

  function uplink() {
    global $_mx_resource_dir;
    foreach ($this->upper() as $path => $appname) {
	    print "\n<a href=\"";
	    print '/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
		    '/' . $path;
	    print '">';
	    if (substr($appname, 0, 8) == "/images/") {
		    print "<img src=\"/$_mx_resource_dir$appname\">";
	    }
	    else {
		    print htmlspecialchars($appname);
		    print "へ</a>\n";
	    }
    }
  }

  function application_name() {
	  return $this->auth[1];
  }

  function top_pane_left($uplink=0) {
    $appname = $this->application_name();
    mx_titlespan($appname, 'appname');
    if ($uplink)
	    $this->uplink();
    mx_draw_userinfo($this->auth);
  }

  function appbar_filter($path, $name, $pid) {
	  /*
	   * This is not ppa; do not show encounter state applications.
	   */
	  if ($path == 'u/everybody/encounter-mode-flip.php')
		  return 0;
	  return !is_encounter_state_application($path);
  }

  function top_pane() {
	global $_mx_use_appbar;

	if ($_mx_use_appbar) {
		  $this->top_pane_left(0);
		  mx_appbar($this);
	}
	else
		$this->top_pane_left(1);
  }

  function draw_loo() { // override
    global $_mx_cheap_layout;
    if (!$_mx_cheap_layout)
	    mx_titlespan($this->loo_title());

    $hide_loo = ($_mx_cheap_layout && $this->sod->chosen() &&
		 !$this->_browse_only);
    if ($hide_loo)
	    print '<div style="position: absolute; visibility: hidden;">';
    $this->loo->draw();
    if ($hide_loo)
	    print "</div>\n";
    return !$hide_loo;
  }

  function draw_plain_new_control($vertical=0) {
	  global $_mx_uniform_control;

	  if ($this->allow_new() &&
	      !($this->use_single_pane && $this->soe->chosen()))
		  mx_formi_submit('New', 'New', mx_img_url('new.png'),
				  $this->msg['New']);
	  else if ($_mx_uniform_control)
		  mx_formi_nosubmit(mx_img_url('noop-new.png'));
	  else
		  return;
	  if (!$this->use_template)
		  return;
	  if ($vertical)
		  print "<br />";

	  if (($this->allow_new() || $this->edit_in_progress()) &&
	      !($this->use_single_pane && $this->soe->chosen()))
//0925-2013
 
 	  mx_formi_submit('NewWithTemplate', 'NewWithTemplate',
 				  mx_img_url('new-w-template.png'),
 				  $this->msg['New With Template']);
			 
	  else if ($_mx_uniform_control)
		  mx_formi_nosubmit(mx_img_url('noop-new-w-template.png'));


	  if ($vertical)
		  print "<br />";
  }

  function draw_sod_control() {
	  $this->draw_sod_control_1(0);
  }

  function draw_sod_control_1($vertical=0) {
    global $_mx_cheap_layout;
    global $_mx_uniform_control;

    $sod_history = $this->sod->history();
    if (!$this->allow_edit() && (($sod_history & 16) == 0))
	    $sod_history += 16;

    if ($_mx_cheap_layout && !$this->inhibit_showloo_in_cheap_layout) {
	    $shown = 0;
	    if ($this->sod->chosen() &&
		!($this->use_single_pane && $this->soe->chosen()) &&
		(!$this->_browse_only || $this->use_single_pane)) {
		    mx_formi_submit('ShowLoo', 'ShowLoo',
				    mx_img_url('list.png'),
				    $this->msg['ShowLoo']);
		    $shown = 1;
	    }
	    else if ($_mx_uniform_control) {
		    mx_formi_nosubmit(mx_img_url('noop-list.png'));
		    $shown = 1;
	    }
	    if ($vertical && $shown)
		    print "<br />";
    }

    $shown = 0;
    $this->draw_plain_new_control($vertical);
    if ($this->allow_new() && $this->allow_copy() &&
	!($this->use_single_pane && $this->soe->chosen())) {
	    mx_formi_submit('NewLikeThis', 'New Like This',
			    mx_img_url('copy.png'),
			    $this->msg['New Like This']);
	    $shown = 1;
    }
    else if ($_mx_uniform_control) {
	    mx_formi_nosubmit(mx_img_url('noop-copy.png'));
	    $shown = 1;
    }
    if ($vertical && $shown)
	    print "<br />";

    if (($this->use_single_pane && $this->soe->chosen())) {
	    if ($_mx_uniform_control) {
		    $shown = 1;
		    mx_formi_nosubmit(mx_img_url('noop-edit.png'));
		    mx_formi_nosubmit(mx_img_url('noop-history.png'));
	    }
    }
    else if ($sod_history & 2) {
	    /* Not showing history at all */
	    $shown = 0;
	    if (($sod_history & 16) == 0) {
		    mx_formi_submit('Edit', 'Edit', mx_img_url('edit.png'),
				    $this->msg['Edit']);
		    $shown = 1;
	    }
	    else if ($_mx_uniform_control) {
		    mx_formi_nosubmit(mx_img_url('noop-edit.png'));
		    $shown = 1;
	    }
	    if ($vertical && $shown)
		    print "<br />";

	    $shown = 0;
	    if ($sod_history & 1) {
		    mx_formi_submit('History', 'Prev',
				    mx_img_url('history.png'),
				    $this->msg['History']);
		    $shown = 1;
	    }
	    else if ($_mx_uniform_control) {
		    mx_formi_nosubmit(mx_img_url('noop-history.png'));
		    $shown = 1;
	    }
	    if ($vertical && $shown)
		    print "<br />";
    }
    else {
	    $shown = 0;
	    if ($_mx_uniform_control) {
		    mx_formi_nosubmit(mx_img_url('noop-edit.png'));
		    $shown = 1;
	    }
	    if (($sod_history & 5) == 5) {
		    mx_formi_submit('History', 'Prev',
				    mx_img_url('history-prev.png'),
				    $this->msg['History Prev']);
		    $shown = 1;
	    }
	    else if ($_mx_uniform_control) {
		    mx_formi_nosubmit(mx_img_url('noop-history-prev.png'));
		    $shown = 1;
	    }

	    $shown = 0;
	    if (($sod_history & 9) == 9) {
		    mx_formi_submit('History', 'Next',
				    mx_img_url('history-next.png'),
				    $this->msg['History Next']);
		    $shown = 1;
	    }
	    else if ($_mx_uniform_control) {
		    mx_formi_nosubmit(mx_img_url('noop-history-next.png'));
		    $shown = 1;
	    }

	    if ($vertical && $shown)
		    print "<br />";
    }
    if ((($sod_history & 32) == 32)) {
	    $this->extra_buttons_html($vertical);
    }

    if ($this->sod->chosen() && $this->use_printer)
      mx_formi_submit('Print', 'Print',
		      mx_img_url('printer.png'), $this->msg['Print']);
//0702-2012
if ($this->sod->chosen() && $this->use_printer2)
      mx_formi_submit('Print', 'Print2',
		      mx_img_url('printer.png'), $this->msg['Print2']);

//    if ($this->use_card)
//      mx_formi_submit('Card', 'Card',
//		      mx_img_url('card.png'), $this->msg['Card']);

  }

  function sod_control_top() {
	  return 1; // override
  }

  function sod_control_bottom() {
	  return 1; // override
  }

  function sod_pane() {

    global $_mx_cheap_layout;
    global $_mx_bmd_layout;
    global $_mx_use_control_bar;

    if ($_mx_bmd_layout) {
	  print "<table><tr><td width=\"20px\" ";
	  print "align=\"center\" valign=\"top\">";
	  if (!$_mx_use_control_bar)
		  $this->draw_sod_control_1(0);
	  print "</td><td width=\"100%\">";
	  $this->sod->draw();
	  print "</td></tr></table>";
	  return;
    }

    if (!$_mx_cheap_layout) {
	    mx_titlespan($this->sod_title());
	    if (!$_mx_use_control_bar && $this->sod_control_top()) {
		    print "<br />";
		    $this->draw_sod_control();
	    }
    }

    $this->sod->draw();

    if (!$_mx_use_control_bar && $this->sod_control_bottom()) {
	    $this->draw_sod_control();
    }
  }

  function left_pane() {
    global $_mx_use_control_bar;

    $loo_drawn = $this->draw_loo();
    if (!$this->_browse_only) {
      if ($this->sod->chosen()) {
        if ($loo_drawn)
	  print '<hr />';
	$this->sod_pane();

      }
      else if (!$_mx_use_control_bar && !$this->no_control_bar) {
        print "<br />";
	$this->draw_plain_new_control();
      }
      if ($this->use_subpick_on_left && $this->soe) {
	      $soe = $this->soe;
	      $soe->draw_subpick(&$soe->so_config);
      }
    }
  }

  function extra_buttons_html($vertical=0) {
	  // if you return 32 from history,
	  // you should *always* override this function.
	  if (0) {
		  // just an example.
		  mx_formi_submit('CSV', 'CSV', mx_img_url('csv.png'),
				  $this->msg['CSV']);
	  }
  }

  function right_pane() {
    global $_mx_cheap_layout;

    if ($this->_browse_only) {
      if ($this->sod->chosen()) {
	 $this->sod_pane();
      }
    }
    else {
	    if ($this->extra_pane_in_unused_soe &&
		!$this->edit_in_progress() &&
		(!$this->soe->chosen() || $this->soe->commit_ran)) {
		    $this->extra_pane();
	    } else if ($this->soe->chosen()) {
	      if (!$_mx_cheap_layout) {
		      if ($this->soe->creating())
			      mx_titlespan($this->soc_title());
		      else
			      mx_titlespan($this->soe_title());
	      }
	      if ($this->use_subpick_on_left)
		      $this->soe->separate_subpick = 1;
	      $this->soe->draw();
	    }
    }
  }

  function extra_pane() {
	  ; /* nothing */
  }

  function single_pane() {
    global $_mx_cheap_layout;

    $hide_left = 0;
    if ( ($this->_browse_only && $this->sod && $this->sod->chosen()) ||
	 ($this->soe && $this->soe->chosen()) )
	    $hide_left = 1;

    if ($hide_left)
	    print '<div style="position: absolute; visibility: hidden;">';
    $this->left_pane();
    if ($hide_left)
	    print '</div>';
    $this->right_pane();
  }

  function extra_td() {
	  global $_mx_product_name;
	  if ($_mx_product_name == 'M3') {
		  print "<td>" . m3extra() . "</td>";
	  }
  }

  function open_form_head() {
	  if (!$this->form_head_tag) {
		  // You could do funny tricks with PATH_INFO
		  // if you wanted to...
		  $program = preg_replace('/\.php\/.*$/', '.php',
					  $_SERVER['PHP_SELF']);
		  $url = htmlspecialchars($program);
		  $form = 'form';
		  if ($this->use_upload)
			  $form = 'form enctype="multipart/form-data"';
		  $this->form_head_tag =
			  "<$form method=\"POST\" action=\"$url\">\n";
	  }
	  return $this->form_head_tag;
  }

  function html_head() {
    mx_html_head($this->auth[1]);
  }

  function main() {
    global $_mx_use_control_bar;
    global $__lib_u_manage_sta_default_msg;
    global $_mx_yui;

    if (! $this->auth[0])
      return mx_authorization_error($this->auth);

    if ($this->setup_0())
	    return;

    foreach ($__lib_u_manage_sta_default_msg as $k => $v) {
	    if (!array_key_exists($k, $this->msg))
		$this->msg[$k] = $v;
    }
    $this->html_head();
    if ($_mx_yui)
      $cls = ' class="yui-skin-sam"';
    print "<body${cls}>\n";

    if ($this->top_inside_form)
	    print $this->open_form_head();
//1120-2012
  $this->top_pane();

    print "<hr />\n";

    if (! $this->top_inside_form)
	    print $this->open_form_head();

    if ($_mx_use_control_bar && !$this->no_control_bar) {
	    print "<div width=\"100%\">";
	    if ($this->sod && $this->sod->chosen())
		    $this->draw_sod_control();
	    else if (!$this->_browse_only)
		    $this->draw_plain_new_control();
	    if ($this->loo && $this->loo->use_printer)
	      mx_formi_submit('Print', 'Print',
			      mx_img_url('printer.png'), $this->msg['Print']);
//0702-2012
	if ($this->loo && $this->loo->use_printer2)
	      mx_formi_submit('Print', 'Print2',
			      mx_img_url('printer.png'), $this->msg['Print2']);
//0702-2012
	    if ($this->soe &&
		$this->soe->chosen() &&
		!$this->soe->commit_ran &&
		!$this->_browse_only)
		    $this->soe->draw_control($this->soe->so_config);
	    print "</div>\n";
    }

    print "<table width=\"100%\"><tr valign=\"top\">";

    if ($this->use_single_pane) {
	    print "<td width=\"100%\">\n";
	    $this->single_pane();
    }
    elseif ($this->left_pane_only) {
	    print "<td width=\"100%\">\n";
	    $this->left_pane();
    }
    else if ($this->three_pane) {
	    print "<td width=\"33%\">\n";
	    $this->left_pane();
	    print "</td><td width=\"33%\" class=\"twoside-right\">\n";
	    $this->right_pane();
	    print "</td><td width=\"33%\">\n";
	    $this->extra_pane();
    }
    else {
	    print "<td width=\"50%\">\n";
	    $this->left_pane();
	    print "</td><td width=\"50%\" class=\"twoside-right\">\n";
	    $this->right_pane();
    }
    print "</td>";
    $this->extra_td();
    print "</tr></table>\n";
    $this->form_tail();
    print $this->close_form();
    $this->body_tail();
    print "</body></html>\n";
  }

  function close_form() {
    print "</form>";
  }

  function form_tail() {
  }

  function body_tail() {
	  global $__mx_formi_date_used;
	  if ($__mx_formi_date_used) {
		  print "<script>";
		  print "mx_preload_holiday_table();";
		  print "</script>\n";
	  }
  }

  function upper() {
    return $this->_upper;
  }

  function restorestate($stateid, $restoreaction) {
	  global $_REQUEST;

	  if (is_null($restoreaction))
		  $restoreaction = 'New';

	  $db = mx_db_connect();
	  $stmt = "SELECT data FROM mx_appstate WHERE id = " .
		  mx_db_sql_quote($stateid);
	  $d = mx_db_fetch_single($db, $stmt);
	  if (!$d || !is_array($d) || !array_key_exists('data', $d))
		  return; // Argh.
	  $stmt = ('DELETE FROM mx_appstate WHERE id = ' .
		   mx_db_sql_quote($stateid) . ' OR age(now(), epoch) > ' .
		  "interval '2 days'");
	  pg_query($db, $stmt);

	  $data = $d['data'];
	  $data = mx_form_decode_name($data);
	  $data = unserialize($data);

	  if (!is_array($data) ||
	      !array_key_exists('original', $data) ||
	      !array_key_exists('updated', $data))
		  return ; // Argh.

	  $orig_request = $data['original'];
	  $updated_request = $data['updated'];

	  if ($restoreaction == 'New') {
		  $this->make_state_into_edit_mode(&$updated_request);
		  $_REQUEST = $updated_request;
	  }
	  else if ($restoreaction == 'Cancel') {
		  $_REQUEST = $orig_request;
	  }
  }

  function savestate($data) {
	  $db = mx_db_connect();
	  unset($data['RestoreApplicationState']);
	  $d = array('original' => $data, 'updated' => $data);
	  $data = mx_form_encode_name(serialize($d));
	  $id = mx_db_allocate_unused_id($db, 'mx_appstate_seq');
	  if (!$id)
		  return $id;
	  $stmt = "INSERT INTO mx_appstate (id, application, data) VALUES (" .
		  mx_db_sql_quote($id) . ", " .
		  mx_db_sql_quote($this->auth[3]) . ", " .
		  mx_db_sql_quote($data) . ")";
	  pg_query($db, $stmt);
	  return $id;
  }

  function make_state_into_edit_mode(&$data) {
	  // override if you are doing something other than "single table"
	  // editing.
	  $data['soe-chosen'] = 1;
  }

  function redirect_to_template() {
	  $data = $_REQUEST;
	  unset($data['NewWithTemplate']);
	  $id = $this->savestate($data);
	  mx_http_redirect('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
			   "/template-pick.php?ID=$id");
	  return 1;
  }

  function loo_title() { return $this->_loo_title; }
  function sod_title() { return $this->_sod_title; }
  function soe_title() { return $this->_soe_title; }
  function soc_title() { return $this->_soc_title; }

  function list_of_objects($prefix) { // override
    return new _lib_so_list_of_dummy_objects($prefix);
  }

  function object_display($prefix) { // override
    return new _lib_so_dummy_object_display($prefix);
  }

  function object_edit($prefix) { //override
    return new _lib_so_dummy_object_edit($prefix);
  }

}

?>
