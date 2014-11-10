<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/diff.php';

// Simple Object has been split into five files:
// so.php contains the services common to all three classes.
// los.php contains list_of_simple_objects.
// sod.php contains simple_object_display.
// soe.php contains simple_object_edit.
// simple_object.php is for backward compatibility to include all of them.

class simple_object_display {

  var $debug = NULL;

  var $logmsg = '';
  var $default_creator_format = array('職員ID' => '%s:',
				      '姓' => ' %s',
				      '名' => ' %s',
				      '職種' => ' (%s',
				      '職位' => ' - %s)');

  function simple_object_display($prefix, &$config) {
    $this->prefix = $prefix;
    $this->so_config = $config;
    _lib_so_prepare_config($this->so_config);
    $this->drawer = new _lib_so_drawer($this);

    $this->page_reset = 0;
    if (array_key_exists($prefix . 'id', $_REQUEST))
      $this->id = $_REQUEST[$prefix . 'id'];
    else
      $this->id = NULL;
    if (array_key_exists($prefix . 'history-at', $_REQUEST))
      $this->history_ix = $_REQUEST[$prefix . 'history-at'];
    else
      $this->history_ix = NULL;
  }

  function setup_dpages() {
    if (array_key_exists('DPAGES', $this->so_config) &&
	is_array($this->so_config['DPAGES']) &&
	2 <= count($this->so_config['DPAGES'])) {
      $this->dpages = $this->so_config['DPAGES'];
      if (array_key_exists('DPAGE_BREAKS', $this->so_config) &&
	  is_array($this->so_config['DPAGE_BREAKS']))
	$this->dpage_breaks = $this->so_config['DPAGE_BREAKS'];
      if ($this->page_reset)
        $this->page = 0;
      elseif (array_key_exists($this->prefix . 'page-to', $_REQUEST))
	$this->page = $_REQUEST[$this->prefix . 'page-to'];
      elseif (array_key_exists($this->prefix . 'page', $_REQUEST))
	$this->page = $_REQUEST[$this->prefix . 'page'];
      else
	$this->page = 0;
    }

  }

  function log($msg) {
    $this->logmsg .= $msg;
  }

  function dbglog($msg) {
    if ($this->debug)
      $this->log($msg);
  }

  function reset($id) {
    $this->id = $id;
    // $this->history_ix == NULL means showing the latest.
    // Integer value is an index to the history list (oldest first).
    $this->history_ls = NULL;
    $this->history_ix = NULL;
    $this->page_reset = 1;
  }

  function chosen() {
    if (! $this->id)
      return $this->id;

    $this->history();
    if (is_null($this->history_ix))
      return $this->id;
    else
      return $this->history_ls[$this->history_ix]['ObjectID'];
  }

  function history_id() {
    return $this->id;
  }

  function history($move_direction=NULL) {
    if (is_null($this->history_ls)) {
      $db = mx_db_connect();
      $stmt = ('SELECT "ObjectID", "ID", "CreatedBy", "Superseded" FROM ' .
	       mx_db_sql_quote_name($this->so_config['TABLE']) .
	       ' WHERE "ID" = ' . mx_db_sql_quote($this->history_id()) .
	       ' ORDER BY "Superseded"');
      $this->dbglog("$stmt;\n");
      $ls = pg_fetch_all(pg_query($db, $stmt));

      // Prepare user to Employee moniker mappings --- Ugh.
      $this->umap = NULL;
      $users = array();
      $uids = array();
      $lls = NULL;
      if ($ls) {
	$lls = array();
	foreach ($ls as $row) {
	  $u = $row['CreatedBy'];
	  if (! is_null($u) && ! array_key_exists($u, $users)) {
	    $uids[] = $row['CreatedBy'];
	    $users[$row['CreatedBy']] = NULL;
	  }
	  if (! is_null($row['Superseded']))
	    $lls[] = $row;
	}
	if (count($lls))
	  $this->history_ls = $lls;
      } else
	$this->history_ls = $ls;

      if (1 <= count($uids)) {
	$stmt = ('SELECT E.userid, E."職員ID", E."姓", E."名", '.
		 'E."フリガナ", C."職種", R."職位", '.
		 'D."大分類", D."中分類1", D."中分類2", D."小分類" ' .
		 'FROM "職員台帳" AS E '.
		 'JOIN "職種一覧表" AS C '.
		 'ON E."Superseded" IS NULL AND '.
		 'C."Superseded" IS NULL AND E."職種" = C."ObjectID" '.
		 'JOIN "職位一覧表" AS R '.
		 'ON R."Superseded" IS NULL AND E."職位" = R."ObjectID" '.
		 'JOIN "部署一覧表" AS D '.
		 'ON D."Superseded" IS NULL AND E."部署" = D."ObjectID" '.
		 'WHERE E.userid IN (' . implode(', ', $uids) . ')');
	$umapdb = pg_fetch_all(pg_query($db, $stmt));
	if ($umapdb) {
	  $this->umap = array();
	  foreach ($umapdb as $d) {
	    $this->umap[$d['userid']] = $d;
	  }
	}
      }
    }
    if (! is_null($move_direction)) {
      if (! is_array($this->history_ls))
	$this->history_ix = NULL;
      else {
	// Moving forward or backward.
	if ($move_direction == 'Prev')
	  $nx = (is_null($this->history_ix)
		 ? (count($this->history_ls) - 1)
		 : ($this->history_ix - 1));
	else
	  $nx = (is_null($this->history_ix)
		 ? -1
		 : ($this->history_ix + 1));

	// Out of bounds just snaps out of history mode.
	if ($nx < 0 || count($this->history_ls) <= $nx)
	  $this->history_ix = NULL;
	else
	  $this->history_ix = $nx;
      }
    }
    // Return values:
    // & 1: Has history.
    // & 2: Not showing history at all.
    // & 4: Can go Prev.
    // & 8: Can go Next.
    // In addition these are not returned from the base simple object
    // but can be returned from subclasses to control the behavior of
    // single-table-application.
    // &16: No Edit.
    // &32: Adds its own buttons.

    $r = 0;
    if (is_null($this->history_ix))
      $r += 2;
    if (is_array($this->history_ls)) {
      $r += 1;
      if ($this->history_ix)
	$r += 4;
      if (! is_null($this->history_ix))
	$r += 8;
    }
    return $r;
  }

  // Override:
  // Called after a row is fetched, before we send it to the browser.
  function annotate_row_data(&$data) { ; }

  // Override:
  // Called to compare the rows to find out which one changed.
  function hist_compare($data, $hdata, $col, $desc) {
    if (is_null($hdata)) { return ''; }
    if (!is_array($col)) { $col = array($col); }
    $r = '';
    foreach ($col as $cc) {
      if ($data[$cc] != $hdata[$cc]) {
	$r = ' class="changed"'; break;
      }
    }
    if ($this->debug) {
      print "<!-- HC $c\n";
      foreach ($col as $cc) {
	if ($data[$cc] != $hdata[$cc]) {
	  print "\$data[$cc]: ";
	  var_dump($data[$cc]);
	  print "\n\$hdata[$cc]: ";
	  var_dump($hdata[$cc]);
	}
      }
      print "-->\n";
    }
    return $r;
  }

  function fetch_data($id) {
    return _lib_so_fetch_data
      (mx_db_connect(), $id, $this->so_config);
  }

  function prepare_data_for_draw() {
    global $_mx_show_old_rev_in_latest;

    $this->history();
    if (is_null($this->history_ix))
      $id = $this->id;
    else {
      if ($_mx_show_old_rev_in_latest) {
        $ix = $this->history_ix + 1;
	if (count($this->history_ls) <= $ix)
	  $id = $this->id;
	else
          $id = $this->history_ls[$ix]['ObjectID'];
      } else {
        $id = $this->history_ls[$this->history_ix]['ObjectID'];
      }
    }

    $data =& $this->fetch_data($id);
    $this->annotate_row_data(&$data);
    return $data;
  }

  function prepare_hdata_for_draw() {
    global $_mx_show_old_rev_in_latest;

    $this->history();
    if (is_null($this->history_ix))
      return NULL;

    if (!$_mx_show_old_rev_in_latest) {
      $cid = $this->history_ix - 1;
      if ($cid < 0)
        return NULL;
      $id = $this->history_ls[$cid]['ObjectID'];
    } else {
      $id = $this->history_ls[$this->history_ix]['ObjectID'];
    }
    $hdata =& $this->fetch_data($id);
    if (!is_null($hdata))
      $this->annotate_row_data(&$hdata);
    return $hdata;
  }

  function pre_draw_hook($data, $hdata) { // override
    $this->setup_dpages();
  }

  function draw() {
    $data = $this->prepare_data_for_draw();
    $hdata = $this->prepare_hdata_for_draw();
    $this->pre_draw_hook($data, $hdata);
    if (!is_null($this->history_ix))
      mx_formi_hidden($this->prefix . 'history-at', $this->history_ix);

    if ($this->debug) {
      print "<!--\n";
      var_dump($data);
      print "\n-- vs --\n";
      var_dump($hdata);
      print ";\n-->\n";
    }
    if ($this->logmsg != '') {
      print "<!--\n";
      print $this->logmsg;
      print "-->\n";
    }
    mx_formi_hidden($this->prefix . 'id', $this->id);

    $this->draw_body($data, $hdata);
  }

  function draw_body_0($data, $hdata, $dcols) { // override

    if (! $this->dpages) return;

    $page = $this->page;
    $pages = $this->dpages;

    // Flip Page.
    print "<table class=\"flippage\" width=\"100%\"><tr>";
    $page_num = -1;
    foreach ($pages as $page_name) {
      $page_num++;
      if ($page_num == $page) {
	print "<td class=\"focused ltcorner\"></td>";
	print "<td class=\"focused\">&nbsp;";
	print $page_name;
	mx_formi_hidden($this->prefix . 'page', $page_num);
	print "&nbsp;</td><td class=\"focused rtcorner\"></td>";
      } else {
	// A page that is hidden
	print "<td class=\"unfocused ltcorner\"></td>";
	print "<td class=\"unfocused\">";
	if ($this->_Subpicker)
	  print $page_name;
	else {
	  print "<div onclick=\"activateInnerButton(this);\">";
	  mx_formi_submit($this->prefix . 'page-to', $page_num, $page_name);
	  print "</div>";
	}
	print "</td><td class=\"unfocused rtcorner\"></td>";
      }
      if ($this->dpage_breaks && in_array($page_num, $this->dpage_breaks))
	print "</tr></table><table class=\"flippage\" width=\"100%\"><tr>";
    }
    print "</tr></table>\n";

  }

  var $table_class = array('tabular-data', 'historical-data');

  function draw_body_1($data, $hdata, $dcols) {
    print '<table class="wide ';
    if (! is_null($this->history_ix))
      print $this->table_class[1];
    else
      print $this->table_class[0];
    print "\">\n";

    $this->draw_body_3($data, $hdata, $dcols);

    if (! is_null($this->history_ix) && ! $this->history_in_body) {
      // Showing history
      $mts = $this->history_ls[$this->history_ix]['Superseded'];
      print ("<tr><th>変更タイムスタンプ</th><td>" .
	     htmlspecialchars(mx_format_timestamp($mts)) .
	     "</td></tr>\n");
    }
    print "</table>";
  }

  function draw_body_2($data, $hdata, $dcols) { // override
  }

  function omit_if_empty($desc, $data, $hdata) { // override
	  $col = $desc['Column'];
	  if ($data[$col] != '')
		  return 0;
	  $oie = $desc['Option']['OmitIfEmpty'];
	  if (!is_array($oie)) {
		  if ($hdata) {
			  if ($hdata[$col] == '')
				  return 1;
			  return 0;
		  }
		  return 1;
	  }
	  foreach ($oie as $col) {
		  if ($data[$col] != '')
			  return 0;
	  }
	  if ($hdata) {
		  foreach ($oie as $col) {
			  if ($hdata[$col] != '')
				  return 0;
		  }
	  }
	  return 1;
  }

  function draw_one_item($desc, $data, $hdata, $spans) {
	  global $_mx_show_old_rev_in_latest;

	  $changed = $this->hist_compare($data, $hdata, $desc['Column'],
					 $desc);
	  $td_class = array();
	  if ($changed && !$_mx_show_old_rev_in_latest)
	    $td_class[] = 'changed';

	  if (array_key_exists('Option', $desc)) {
		  $option = $desc['Option'];
		  if (mx_check_option('nowrap', $option, ''))
		    $td_class[] = 'nowrap';
		  $cols = mx_check_option('cols', $option);
		  $rows = mx_check_option('rows', $option);
		  if ($cols && $rows) {
			  /* HACK 8x16 */
			  $cols *= 8; $rows *= 16;
			  $spans .= (" height=\"${rows}px\" ".
				     "width=\"${cols}px\"");
		  }
		  $anno = mx_check_option('annotate', $option);
		  if ($anno)
			  $anno(&$desc, &$data);
	  }
	  if (count($td_class))
	    $td_class = ' class="' . implode(' ', $td_class) . '"';
	  else
	    $td_class = '';
	  if (!$changed || !$hdata ||
	      (is_null($this->history_ix) && !$_mx_show_old_rev_in_latest)) {
		  print "<td$td_class$spans>";
		  $this->draw_body_atom($desc, $data, $changed);
		  print "</td>";
		  return;
	  }

	  if (!$_mx_show_old_rev_in_latest) {
		  print "<td$td_class$spans>";
		  print '<div class="revctl-current">';
		  $this->draw_body_atom($desc, $data, $changed);
		  print '</div></td>';
		  return;
	  }
	  print "<td$td_class$spans>";
	  $this->show_old_new($desc, $hdata, $data, $changed);
	  print '</td>';
  }

  function show_old_new($desc, $hdata, $data, $changed) {
	  global $_mx_show_change_format;

	$col = $desc['Column'];
	$draw = $desc['Draw'];
	if ($draw != 'text' && $draw != 'textarea') {
		print '<div class="revctl-old">';
		$this->draw_body_atom($desc, $hdata, $changed);
		print '</div><div class="revctl-new">';
		$this->draw_body_atom($desc, $data, NULL);
		print '</div>';
	} else {
		if (is_array($col)) {
			$data = $data;
			$hdata = $hdata;
		}
		else {
			$data = $data[$col];
			$hdata = $hdata[$col];
		}
		$diff = new Mx_Diff($hdata, $data);
		print $diff->render($_mx_show_change_format);
	}
  }

  function draw_body_3($data, $hdata, $dcols) { // override
          if($this->so_config['D_TEMPLATE'])
	          return $this->draw_body_template($data, $hdata, $dcols);
	  if (!$this->so_config['D_RANDOM_LAYOUT'])
		  return $this->draw_body_4($data, $hdata, $dcols);
	  $lookup = array();
	  foreach ($dcols as $d) {
		  if (!$d['Column'])
			  continue;
		  $lookup[$d['Column']] = $d;
	  }
	  $layo = $this->so_config['D_RANDOM_LAYOUT'];
	  $cnt = count($layo);
print "________________________________________________________________________________________________\n";

	  $col = 0;
	  for ($ix = 0; $ix < $cnt; $ix++) {
		  $insn = $layo[$ix];
		  if (!is_array($insn))
			  $insn = array('Column' => $insn);
		  $span = $insn['Span'] ? $insn['Span'] : 1;
		  $rowspan = $insn['Rowspan'] ? $insn['Rowspan'] : 1;
		  $spans = "";
		  if (1 < $span)
			  $spans = "$spans colspan=\"$span\"";
		  if (1 < $rowspan)
			  $spans = "$spans rowspan=\"$rowspan\"";

		  if ($insn['Insn'] == '//') {
			  if ($col){
				  print "</tr>\n";
//10-21-2012
				  
				}
			  $col = 0;
			  continue;
//10-20-2012 ' ' change
		  } else if ($insn['Insn'] == ' ') {
			 
			  if (!$col){
				  print "<tr>\n";
//10-21-2012
 
					}
				 

 			   
 			// print "<td $spans>&nbsp;</td>";
 		print '<td colspan="1"'.">____________________</td>";
 		print '<td colspan="1"'.">____________</td>";
		print '<td colspan="1"'.">____________</td>"; 	 
		print '<td colspan="1"'.">____________</td>";
		print '<td colspan="1"'.">____________</td>"; 	   
			  $col += $span;
			  continue;
		  } else if ($insn['Insn'] == 'CreatedBy') {
			  $desc = array('Column' => 'CreatedBy',
					'Draw' => 'user');
		  } else {
			  $desc = $lookup[$insn['Column']];
		  }
		  if (!$col)
//			print "<tr ".'BgColor="#ffd5ea"'.">\n";
 			  print "<tr>\n";
		  if ($insn['Label']) {
 			  print "<th$spans>";
//			print "<th ".'BgColor="#ffd5ea"'.">BB";
			  print htmlspecialchars($insn['Label']);
			  print "</th>";
			  $col += $span;
		  }
		  if (!$desc)
			  continue;
		  $col += $span;
		  $this->draw_one_item($desc, $data, $hdata, $spans);
	  }
	  print "</tr>\n";
  }

  function draw_body_4($data, $hdata, $dcols) { // override
    global $_mx_resource_dir;

    foreach ($dcols as $desc) {
      if (is_null($desc['Draw']) ||
	  $this->dpages &&
	  ( array_key_exists('Page', $desc) && $desc['Page'] != $this->page ) )
	continue;

      $option = mx_check_option('Option', $desc);
      if (array_key_exists('Column', $desc))
	      $col = $desc['Column'];
      else
	      $col = NULL;

      if (mx_check_option('OmitIfEmpty', $option) &&
	  $option['OmitIfEmpty'] &&
	  $this->omit_if_empty($desc, $data, $hdata))
	      continue;

      if (!is_null($desc['Label']))
	      $label = htmlspecialchars($desc['Label']);
      else
	      $label = NULL;

      print '<tr>';
      if ($desc['Draw'] == 'group_head') {
	print '<th colspan="2" class="group_head">';
	print $label;
	print '</th>';
      } else {
	$abbrev = mx_check_option('AbbrevField', $option);
	if ($abbrev && !is_null($col)) {
		$en = $this->prefix . $this->en($col);
		$img = "/$_mx_resource_dir/images/";
		if ($abbrev < 0)
			$img = $img . "hide.png";
		else
			$img = $img . "show.png";
		$show_hide = "show_hide('$en', '/$_mx_resource_dir')";
		$label .= ('<a href="javascript:void(0)" ' .
			   "onclick=\"$show_hide\">" .
			   "<img id=\"SHC-$en\" " .
			   "src=\"$img\" alt=\"\" ".
			   'border="0" height="18" width="18"></a>');
		printf('<th colspan="2">%s</th>', $label);
		
		if ($abbrev < 0)
			$st0 = ' style="display: none"';
		else
			$st0 = '';
		printf('</tr><tr id="%s"%s>', "SHD-$en", $st0);
		$span = 2;
	}
	else if (!is_null($label)) {
		$span = 1;
		if (is_null($col))
			print '<th colspan="2">';
		else
			print '<th>';
		print $label . "</th>";
	}
	else
		$span = 2;

	if (!is_null($col)) {
	  $span = (1 < $span) ? " colspan=\"$span\"" : "";
	  $this->draw_one_item($desc, $data, $hdata, $span);
	}
      }
      print "</tr>\n";
    }
  }

  function tweak_template($s) {
    return $s;
  }

  function draw_body_template($data, $hdata, $dcols) {
    global $_mx_resource_dir;
    $template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/' . $this->so_config['D_TEMPLATE']);
    $template = $this->tweak_template($template);
    foreach ($dcols as $desc) {
      if($desc['Draw'] == 'group_head')
         continue;
	$col = $desc['Column'];
	$gc = "@@$col@@";
	$pat = "/@@$col:(.*?)@@/";
	$m = array();
	if(preg_match($pat, $template, &$m)) {
	  $opt = explode(':', $m[1]);
	  foreach($opt as $x) {
	    list($k, $v) = explode('=', $x);
	    switch($k) {
	    case 'draw':
	      $desc['Draw'] = $v;
	      break;
	    case 'option':
	      if($desc['Draw'] == 'icd10')
      	          $desc['Option'] = array('disease' => $v,
		  		          'add_id' => 1);
	    }
	  }
	  $gc = $m[0];
	}
	ob_start();
	$this->draw_body_atom($desc, $data, FALSE);
	$v = ob_get_contents();
	ob_end_clean();
	$template = str_replace($gc, $v, $template);
    }
    print $template;
  } 

  function en($name) {
    return $this->prefix . mx_form_encode_name($name);
  }

  function draw_body_atom($desc, $data, $changed) {
    $col = $desc['Column'];
    $draw = "dx_" . $desc['Draw'];
    if (is_array($col))
	    $passdata = $data;
    else
	    $passdata = $data[$col];
    $this->$draw($desc, $passdata, $changed);
  }

  function find_dcol($column_name, $dcol=NULL) {
    if (is_null($dcol))
      $dcol = $this->so_config['DCOLS'];
    foreach ($dcol as $ix => $data) {
      if ($data['Column'] == $column_name)
	return $data;
    }
    return NULL;
  }

  function draw_body($data, $hdata) { // override
    // $data is what the user is viewing.  $hdata, if not NULL, is
    // the neighboring version to compare to, in order to show
    // change marks.

    $dcols = $this->so_config['DCOLS'];
    $this->draw_body_0($data, $hdata, $dcols);
    $this->draw_body_1($data, $hdata, $dcols);
    $this->draw_body_2($data, $hdata, $dcols);
  }

  function dx_user($desc, $value, $changed) {
	  return $this->drawer->dx_user($desc, $value, $changed);
  }

  function _dx_textish($value, $changed, $noquote=NULL) {
	  return $this->drawer->_dx_textish($value, $changed, $noquote);
  }

  function dx_text($desc, $value, $changed) {
	  $header = $footer = '';
	  $option = $desc['Option'];
	  if(mx_check_option('pre', $option) == 1) {
	        $header = '<pre>';
	        $footer = '</pre>';
	  }
	  print $header;
	  $ret = $this->drawer->dx_text($desc, $value, $changed);
	  print $footer;
	  return $ret;
  }

  function dx_static($desc, $value, $changed) {
	  return $this->drawer->dx_static($desc, $value, $changed);
  }

  function dx_timestamp($desc, $value, $changed) {
	  return $this->drawer->dx_timestamp($desc, $value, $changed);
  }

  function dx_date($desc, $value, $changed) {
	  return $this->drawer->dx_date($desc, $value, $changed);
  }

  function dx_textarea($desc, $value, $changed) {
	  return $this->drawer->dx_textarea($desc, $value, $changed);
  }

  function dx_static_enum($desc, $value, $changed) {
	  return $this->drawer->dx_static_enum($desc, $value, $changed);
  }

  function dx_subpick($desc, $value, $changed) {
	  return $this->drawer->dx_subpick($desc, $value, $changed);
  }

  function dx_enum($desc, $value, $changed) {
	  return $this->drawer->dx_enum($desc, $value, $changed);
  }

  function dx_radio($desc, $value, $changed) {
	  return $this->drawer->dx_enum($desc, $value, $changed);
  }

  function dx_dbenum($desc, $value, $changed) {
	  $value = str_replace('|', ' ・ ', $value);
	  return $this->dx_text($desc, $value, $changed);
  }

  function dx_check($desc, $value, $changed) {
	  return $this->drawer->dx_check($desc, $value, $changed);
  }

  function dx_daysoftheweek($desc, $value, $changed) {
	  return $this->drawer->dx_daysoftheweek($desc, $value, $changed);
  }

  function dx_extdocument($desc, $value, $changed) {
	  return $this->drawer->dx_extdocument($desc, $value, $changed);
  }

  function dx_schema($desc, $value, $changed) {
	  return $this->drawer->dx_schema($desc, $value, $changed, 0);
  }

  function dx_icd10($desc, $value, $changed) {
          return $this->drawer->dx_icd10($desc, $value, $changed, 0);
  }
}

class _lib_so_dummy_object_display extends simple_object_display {
  function _lib_so_dummy_object_display($prefix) {
    global $_lib_so_dummy_cfg;
    simple_object_display::simple_object_display($prefix, $_lib_so_dummy_cfg);
  }
  function reset() { return NULL; }
  function chosen() { return NULL; }
}

?>
