<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext_edit.php';

// Simple Object has been split into five files:
// so.php contains the services common to all three classes.
// los.php contains list_of_simple_objects.
// sod.php contains simple_object_display.
// soe.php contains simple_object_edit.
// simple_object.php is for backward compatibility to include all of them.

class simple_object_edit {

  var $prefix;
  var $chosen;
  var $default_empty_after_commit = NULL;
  var $debug = NULL;
  var $separate_subpick = NULL;

  // Set this to true only if your class is OK with
  // the default implementation of three-way _and_ have
  // a working pass_data().
  var $default_threeway_ok = NULL;

  function simple_object_edit($prefix, &$config) {

    $this->prefix = $prefix;
    $this->so_config = $config;
    _lib_so_prepare_config($this->so_config);

    $this->id = $_REQUEST[$prefix . 'id'];
    $this->origin = $_REQUEST[$prefix . 'origin'];
    $this->chosen = $_REQUEST[$prefix . 'chosen'];
    $this->commit_message = NULL;
    $this->commit_ran = NULL;
    $this->broken_origin = NULL;
    if (is_null($this->default_empty_after_commit))
      $this->empty_after_commit = array
	('updated' => htmlspecialchars('change recorded'),
	 'nochange' => htmlspecialchars('no change'),
	 'created' => htmlspecialchars('create new record'));
    else
      $this->empty_after_commit = $this->default_empty_after_commit;

    $this->Subpick = NULL;

    $this->data = array();
    $this->get_form_data();

    // Override annotate_form_data if the subclass wants to pick up
    // other values in $_REQUEST.
    $this->annotate_form_data(&$this->data);

    if ($this->Subpick) {
      if ($this->Subpicker && $this->Subpicker->changed() &&
	  ! is_null($this->Subpicker->chosen())) {
	$this->accept_subpick($this->Subpick, $this->Subpicker->chosen());
	$this->Subpick = $this->Subpicker = NULL;
      }
      elseif ($this->Subpick['Subpick']['Allow_NULL'] &&
	      array_key_exists($prefix . 'Subpick_NULL', $_REQUEST)) {
	$this->accept_subpick($this->Subpick, NULL);
	$this->Subpick = $this->Subpicker = NULL;
      }
      elseif (array_key_exists($prefix . 'Subpick_CANCEL', $_REQUEST)) {
	$this->Subpick = $this->Subpicker = NULL;
      }
    }

    $this->precompute_insert_stmt_head();

    if (array_key_exists($prefix . 'commit', $_REQUEST))
      $this->commit();

    if (array_key_exists($prefix . 'force-commit', $_REQUEST))
      $this->commit('force');

    if (array_key_exists($prefix . 'rollback', $_REQUEST)) {
      $this->reset();
      $this->cancelled = 1;
    }

    if (array_key_exists($this->prefix . 'page-to', $_REQUEST))
	    $this->page = $_REQUEST[$this->prefix . 'page-to'];
    elseif (array_key_exists($this->prefix . 'page', $_REQUEST))
	    $this->page = $_REQUEST[$this->prefix . 'page'];
    else
	    $this->page = 0;

    if (array_key_exists($prefix . 'broken-origin', $_REQUEST))
	    $this->resync();
  }

  function get_form_data() {
    $prefix = $this->prefix;
    foreach ($this->so_config['ECOLS'] as $desc) {
	    $this->get_form_data_one_col($desc);
    }

    if (array_key_exists($prefix . 'Subpick', $_REQUEST)) {
      $subpick = $_REQUEST[$prefix . 'Subpick'];
      foreach ($this->so_config['ECOLS'] as $desc) {
	if ($desc['Column'] == $subpick &&
	    array_key_exists('Subpick', $desc)) {
	  $this->Subpick = $desc;
	  $d = $desc['Subpick'];
	  $cls = $d['Class'];
	  $cfg = $d['Config'];
	  $cfg['MSGS']['Inspect'] = $d['Message'];
	  $cfg['LIST_IDS'] = $d['ListID'];
	  $this->Subpicker = new $cls($prefix . 'spk-', $cfg);
	}
      }
    }
  }

  function get_form_data_one_col($desc) {
    $col = $desc['Column'];
    $cen = $this->en($col);
    if ($desc['Draw'] == 'check') {
	if (is_callable($desc['Check']))
	  $v = $desc['Check']((mx_check_request($cen) == 'on'), 'value');
	else
	  $v = (mx_check_request($cen) == 'on') ? 'Y' : 'N';
	$this->data[$col] = $v;
    }
    else if ($desc['Draw'] == 'extdocument') {
      $this->data[$col] = $_REQUEST[$cen];
      $bcol = $col . ".blobmedia";
      $bcen = $this->en($bcol);
      if (array_key_exists($bcen, $_FILES)) {
        // there is a file upload.
	$bicol = $col . ".blobmedia-id";
	$bicen = $this->en($bicol);
        $bid = mx_check_request($bicen);
	$f = $_FILES[$bcen];
	$type = $f['type'];
	if ($f['size'] == 0 || $f['error'] || is_null($f['tmp_name']) ||
	    !($data = file_get_contents($f['tmp_name'])))
	  ;
	else {
	  $db = mx_db_connect();
	  if ($bid == "")
	    $bid = mx_db_insert_blobmedia($db, $type, $data);
	  else
	    mx_db_update_blobmedia($db, $bid, $type, $data);
	}
        $this->data[$bicol] = $bid;
      }

      foreach (array('.blobmedia-id', '.blobmedia-showing') as $e) {
	      $k = $col . $e;
	      $ken = $this->en($k);
	      if (!array_key_exists($k, $this->data))
		      $this->data[$k] = $_REQUEST[$ken];
      }

    }
    else if ($desc['Draw'] == 'datetime') {
      $dt = $_REQUEST[$cen . '_dt'];
      $hh = $_REQUEST[$cen . '_hh'];
      $mm = $_REQUEST[$cen . '_mm'];
      if(is_null($hh))
	$hh=$mm=0;

      if($dt)
	$this->data[$col] = sprintf("%s %02d:%02d", $dt, $hh, $mm);
      else
	$this->data[$col] = NULL;
    }
    else {
      $this->data[$col] = $_REQUEST[$cen];
    }
}

  function precompute_insert_stmt_head() {
    $this->insert_stmt_head =
      ('INSERT INTO ' .
       mx_db_sql_quote_name($this->so_config['TABLE']) . " (\n" .
       '"ID", "ObjectID", "Superseded", "CreatedBy",' . "\n" .
       implode(', ', array_map('mx_db_sql_quote_name',
			       $this->so_config['ICOLS'])) .
       "\n)");
  }

  // Override:
  // Called after a data from form is fetched, before we start processing.
  function annotate_form_data(&$data) {
    if (is_array($this->so_config['ECOLS'])) {
      foreach ($this->so_config['ECOLS'] as $desc) {
	$col = mx_check_option('Column', $desc);
	if (! array_key_exists($col,$data))
	  continue;

	if (trim($data[$col]) == '' &&
	    mx_check_option('empty-is-null', $option)) {
		$data[$col] = NULL;
	}
	switch (mx_check_option('Draw', $desc)) {
	case 'text':
	case 'textarea':
	case 'timestamp':
	case 'date':
	  $data[$col] = str_replace("\r\n", "\n", $data[$col]);
	  $option = mx_check_option('Option', $desc);
	  $ime = mx_check_option('ime', $option);
	  $ts = (mx_check_option('Draw', $desc) == 'timestamp');
	  if ($ime == 'disabled' || $ts) {
	    $v = $data[$col];
	    if (! is_array($v) && ! is_null($v)) {
	      $v = mb_convert_kana($v, 'as', 'euc');
	      if ($v != $data[$col])
		$this->dbglog("Kana $data[$col] => $v\n");
	      if ($ts)
		      $v = mx_ui_japanese_date($v);
	      $data[$col] = $v;
	    }
	  }

	  $zeropad = mx_check_option('zeropad', $option);
	  if (0 < $zeropad)
		  $data[$col] = mx_zeropad($data[$col], $zeropad);
	  break;
	case 'daysoftheweek':
		$this->annotate_form_daysoftheweek(&$data, $desc);
		break;
	case 'dbenum':
		$this->annotate_form_dbenum(&$data, $desc);
		break;
	case 'subpick':
		$this->annotate_form_subpick(&$data, $desc);
		break;
	}
	$constraints = mx_check_option('validate',
				       mx_check_option('Option', $desc));
	if ($constraints)
	  mx_forme_convert($constraints, &$data, $col);
      }
    }
  }

  function annotate_form_daysoftheweek(&$data, $desc) {
	  global $_mx_daysoftheweek;
	  $option = mx_check_option('Option', $desc);
	  $dow = mx_check_option('dow', $option, $_mx_daysoftheweek);
	  $name = $desc['Column'];
	  $cn = $this->en($name);
	  $value = "";
	  for ($i = 0; $i < count($dow); $i++)
		  $value = $value . (mx_check_request("$cn-$i") ? "Y" : "N");
	  $data[$name] = $value;
  }

  function annotate_form_dbenum(&$data, $desc) {
	  $name = $desc['Column'];
	  $cn = $this->en($name);
	  $group = $desc['DBEnum'][0];
	  $item = $desc['DBEnum'][1];
	  $spec = mx_dbenum($group, $item);
	  if ($spec['Multi'] != 'Y')
		  return;
	  $repertoire = $spec['選択肢'];
	  $repertoire = explode("\n", $repertoire);
	  $i = 0;
	  $value = '';
	  $infix = '';
	  foreach ($repertoire as $r) {
		  $r = trim($r);
		  if ($r == '')
			  continue;
		  if (mx_check_request("$cn-$i")) {
			  $value .= $infix . $r;
			  $infix = '|';
		  }
		  $i++;
	  }
	  $data[$name] = $value;
  }

  function annotate_form_subpick(&$data, $desc) {
	  $d = $desc['Subpick'];
	  $oc = $d['ObjectColumn'];
	  if (!$oc || !$d['EnumCapable'])
		  return;
	  /*
	   * In enumcapable subpick backed by a separate
	   * ObjectColumn, the value is actually for the
	   * ObjectColumn.
	   */
	  $value = $data[$desc['Column']];
	  if (trim($value) == '')
		  $value = NULL;
	  $data[$oc] = $value;

	  if (!is_null($value)) {
		  $class = $d['Class'];
		  $ins = new $class('dummy');
		  $enumattr = $ins->enum($name, $value);
		  $enum = $enumattr['Enum'];
		  $data[$desc['Column']] = $enum[$value];
	  } else
		  $data[$desc['Column']] = NULL;
  }

  // Override:
  // Called after a row is fetched, before we start processing.
  function annotate_row_data(&$data) {
	  if (is_array($this->so_config['ECOLS'])) {
		  foreach ($this->so_config['ECOLS'] as $desc) {
			  $col = mx_check_option('Column', $desc);
			  if (! array_key_exists($col,$data))
				  continue;
			  $opt = mx_check_option('Option', $desc);
			  $constraints = mx_check_option('validate', $opt);
			  $v = $data[$col];
			  if (strpos(",$constraints,", ',number,') !== false)
				  $v = $this->annotate_row_number($v, $opt);

			  $data[$col] = $v;
		  }
	  }
  }

  function annotate_row_number($v, $opt) {
	  $prec = mx_check_option('validate-precision', $opt);
	  if (is_null($prec))
		  return $v;
	  return round($v, $prec);
  }

  function reset() {
    $this->chosen = NULL;
    $this->page = 0;
  }

  function chosen() {
    return $this->chosen;
  }

  function en($name) {
    return $this->prefix . mx_form_encode_name($name);
  }

  function de($name) {
    return mx_form_decode_name(substr($name, strlen($this->prefix)));
  }

  function fetch_data($id) { // override
    return _lib_so_fetch_data
      (mx_db_connect(), $id, $this->so_config);
  }

  function fetch_origin_info() { // perhaps override
    return _lib_so_fetch_origin_info
      (mx_db_connect(), $this->id, $this->so_config);
  }

  function fetch_origin_next() { // perhaps override
    return _lib_so_fetch_origin_next
      (mx_db_connect(), $this->id, $this->origin, $this->so_config);
  }

  function edit_tweak() {
    ; // override
  }

  function edit($id) {
    $db = mx_db_connect();

    $this->id = $id;
    $this->data = $this->fetch_data($id);
    $this->annotate_row_data(&$this->data);
    $this->Subpick = NULL;
    $this->page = 0;
    $this->edit_tweak();
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function anew_tweak($orig_id) {
    ; // override
  }

  function anew($id) {
    $this->data = array();
    if (! is_null($id))
      $this->edit($id);
    $this->id = NULL;
    $this->page = 0;
    $this->anew_tweak($id);
    $this->chosen = 1;
  }

  function duplicate($id, $attr) {
	  $this->anew($id);
	  $this->duplicate_tweak($attr);
	  $this->commit('force');
  }

  function duplicate_tweak($attr) {
	  ; // override
  }

  function _allocate_unused_id(&$db) {
    $seq = $this->so_config['SEQUENCE'];
    $stmt = ('SELECT nextval(\'' .
	     mx_db_sql_quote_name($seq) . '\') as "v"');
    $this->dbglog("-- SEQ\n$stmt;\n");
    $sth = pg_query($db, $stmt);
    if (! $sth) {
      $this->log("-- Failure\n$stmt;\n");
      $this->err(pg_last_error($db));
      return 'failure';
    }
    $data = pg_fetch_all($sth);
    $id = $data[0]['v'];
    if (! $id) {
      $this->err(pg_last_error($db));
      return 'failure';
    }
    return $id;
  }

  function _update_stmt($d, $u, $id) {
    $stmt = ('UPDATE ' .
	     mx_db_sql_quote_name($this->so_config['TABLE']) .
	     ' SET "CreatedBy" = ' .
	     mx_db_sql_quote($u));
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= (",\n " . mx_db_sql_quote_name($col) . ' = ' .
		mx_db_sql_quote($d[$col]));
    $stmt .= (' WHERE "ObjectID" = ' . mx_db_sql_quote($id) .
	      ' AND "Superseded" IS NULL ');
    return $stmt;
  }

  function _insert_stmt(&$d, $ObjectID, $StashID) {
    global $mx_authenticate_current_user;

    if (is_null($StashID)) {
      $o = mx_db_sql_quote($ObjectID);
      $o = "$o, $o, NULL, " . mx_db_sql_quote($mx_authenticate_current_user);
    } else {
      // We are stashing the old information away.
      $o = (mx_db_sql_quote($ObjectID) . ', ' .
	    mx_db_sql_quote($StashID) . ', now(), ' .
	    mx_db_sql_quote($d["CreatedBy"]));
    }

    $stmt = (($this->insert_stmt_head) . 'VALUES (' . "$o");
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= ",\n " . mx_db_sql_quote($d[$col]);
    $stmt .= ')';
    return $stmt;
  }

  function log($msg) {
    $this->logmsg .= $msg;
  }

  function dbglog($msg) {
    if ($this->debug)
      $this->log($msg);
  }

  function err($msg) {
    $this->errmsg .= $msg;
  }

  function creating() {
    return $this->id == '';
  }

  function _data_compare_debug($a, $b, $col) {
	  if ($this->debug) {
		  print "<!-- data compare - different at $col\n";
		  print "curr = ";
		  var_dump($a);
		  print "data = ";
		  var_dump($b);
		  print "-->\n";
	  }
  }

  function data_compare($curr, $data) {
    foreach ($this->so_config['ICOLS'] as $col) {
      $a = $curr[$col];
      $b = $data[$col];
      if ("z$a" != "z$b") {
	      $this->_data_compare_debug($a, $b, $col);
	      return 1;
      }
    }
    return 0;
  }

  function _update_subtables(&$db, $id, $stash_id) { return ''; } // override
  function _pre_commit_hook(&$db) { return '';  } // override

  function three_way_merge($base, $latest) {
	  if (!$this->default_threeway_ok)
		  return 0;
	  $threeway = array();
	  $ours =& $this->data; 
	  $has_conflict = 0;
	  foreach ($this->so_config['ICOLS'] as $col) {
		  $o = $base[$col];
		  $a = $ours[$col];
		  $b = $latest[$col];

		  // If neither side changed it, or both
		  // sides changed it the same way,
		  // we do not report.
		  if ($a == $b)
			  continue;

		  if (array_key_exists('NO_3WAY_ICOLS', $this->so_config)) {
			  $i = array_search($col,
					    $this->so_config['NO_3WAY_ICOLS']);
			  if ($i || ($i === 0))
				  continue;
		  }

		  // Default to take ours.
		  $m = $a;
		  
		  if ($o == $a)
			  // We did not change it, which means they did.
			  $m = $b;
		  else {
			  // We changed it, and...
			  if ($o != $b)
				  // they changed it, so that's a conflict.
				  // We resolve it preferring ours.
				  $has_conflict = 1;
			  else
				  // they didn't.  We resolve it preferring 
				  // ours.
				  ;
		  }
		  $this->data[$col] = $m;

		  $oo = $o; $oa = $a; $ob = $b; $om = $m;
		  $this->threeway_tweak($col, &$o, &$a, &$b, &$m);
		  $threeway[] = array('Column' => $col,
				      'O' => $o, 'A' => $a, 'B' => $b,
				      'M' => $m,
				      'OO' => $oo, 'OA' => $oa, 'OB' => $ob,
				      'OM' => $om);
	  }
	  if ($threeway) {
		  if ($has_conflict)
			  $this->err("他のユーザと競合した変更があります。\n");
		  else
			  $this->err("他のユーザも同時に変更しました。\n");
		  $this->err("確認して続行して下さい。\n");
		  $this->threeway_status = $threeway;
		  return 1;
	  }
	  return 2; // Signal that the conflict was only superficial.
  }

  function threeway_tweak($col, &$o, &$a, &$b, &$m) { // override
	  ;
  }

  function _broken_origin_check() {
    if (!is_null($this->broken_origin))
      return $this->broken_origin;
    $this->broken_origin = 0;
    if (! $this->creating()) {
      $o = $this->fetch_origin_info();
      if ($o != $this->origin) {

	      $latest = $this->fetch_data($this->id);
	      $base = $this->fetch_data($this->fetch_origin_next());
	      $this->annotate_row_data($latest);
	      $this->annotate_row_data($base);

	      $this->broken_origin = 1;
	      switch ($this->three_way_merge($base, $latest)) {
	      case 1:
		      // we merged and have something to report.
		      $this->origin = $o;
		      $this->merge_ok = 1;
		      break;
	      case 2:
		      // the change in other end exactly matched ours.
		      $this->origin = $o;
		      $this->broken_origin = 0;
		      break;
	      default:
		      // we did not merge.
		      break;
	      }
      }
    }
    return $this->broken_origin;
  }

  function handle_extdocuments(&$db, &$d) {
    foreach ($this->so_config['ICOLS'] as $name) {
      $desc = NULL;
      foreach ($this->so_config['ECOLS'] as $dd) {
	if ($dd['Column'] == $name) {
	  $desc = $dd;
	  break;
	}
      }
      if (is_null($desc))
        continue;
      $draw = $desc['Draw'];
      if ($draw != 'extdocument')
        continue;
      // There are four cases:
      // 1. $col is empty and $col.blobmedia-id is empty
      //    - leave the column NULL 
      // 2. $col is empty and $col.blobmedia-id is not empty and actually exists
      //    - create a new extdocument, give $col the objectid of it.
      // 3. $col is not empty and $col.blobmedia-id is empty or does not exist
      //    - the extdocument has not changed.
      // 4. $col is not empty and $col.blobmedia-id is not empty and actually exists
      //    - create a new extdocument, give $col the objecid of it.
      // In short, if blobmedia-id is there, create a new extdocument
      // out of it, and store that in the column.
      $col = $desc['Column'];
      $media = "$col.blobmedia-id";

      if (array_key_exists($media, $d) && $d[$media] != '' &&
	  mx_db_blobmedia_exists($db, $d[$media]) ) {
	      $doctype = $desc['Extdocument'];
	      $d[$col] = mx_db_insert_extdocument($db, $doctype, $d[$media]);
      }
      if ($d[$col] == '')
	      $d[$col] = NULL;
    }
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    $this->created_object = NULL;
    if ($this->_broken_origin_check())
      return 'failure';

    $d_id = $this->id;
    $d =& $this->data;
    $this->change_nature = 'nochange';

    $this->handle_extdocuments($db, &$d);

    if ($d_id) {
      // Updating.  Check if things have changed.

      $curr = $this->fetch_data($d_id);
      $this->annotate_row_data(&$curr);

      // $d['CreatedBy'] = $curr['CreatedBy'];
      // $d['ObjectID'] = $d_id;
      if ($this->data_compare($curr, $d)) {
	$stash_id = $this->_allocate_unused_id($db);
	// Stash the current one away first.
	$stmt = $this->_insert_stmt($curr,
				    $this->id,
				    $stash_id);
	$this->dbglog("-- Stash\n$stmt;\n");
	if (! pg_query($db, $stmt)) {
	  $this->log("-- Failure\n$stmt;\n");
	  $this->err(pg_last_error($db));
	  return 'failure';
	}

	if (($st =
	     $this->_update_subtables($db, $this->id, $stash_id)) != '') {
	  $this->err($st);
	  return 'failure';
	}

	// Update the row in place.
	$stmt = $this->_update_stmt($d, $mx_authenticate_current_user, $d_id);

	$this->dbglog("-- Update\n$stmt;\n");
	if (! pg_query($db, $stmt)) {
	  $this->log("-- Failure\n$stmt;\n");
	  $this->err(pg_last_error($db));
	  return 'failure';
	}
	$this->log("Updated\n");
	$this->change_nature = 'updated';
      }
      else
	$this->log("No Change\n");
    }
    else {
      // Creating new.
      $d_id = $this->_allocate_unused_id($db);
      $stmt = $this->_insert_stmt($d, $d_id, NULL);
      $this->dbglog("-- Create\n$stmt;\n");
      if (! pg_query($db, $stmt)) {
	$this->log("-- Failure\n$stmt;\n");
	$this->err(pg_last_error($db));
	return 'failure';
      }
      $this->log("Created\n");
      $this->change_nature = 'created';
      $this->created_object = $d_id;

	if (($st =
	     $this->_update_subtables($db, $d_id, NULL)) != '') {
	  $this->err($st);
	  return 'failure';
	}
    }

    if (($st = $this->_pre_commit_hook($db)) != '') {
      $this->err($st);
      return 'failure';
    }

    if (! pg_query($db, 'commit')) {
      $this->err(pg_last_error($db));
      return 'failure';
    }

    // Set the id on this object (in case this was new).
    // Note that this must be postponed *after* commit.
    $this->id = $d_id;

    return 'ok';
  }

  function clear_badcol() {
	  $this->badcol = array();
  }

  function note_badcol($col, $msg=NULL) {
	  if (is_null($msg))
		  $msg = '';
	  $this->badcol[$col] = $msg;
  }

  function check_previous_error($col) {
	  return (is_array($this->badcol) &&
		  array_key_exists($col, $this->badcol));
  }

  function __pre_validate() {
    $this->clear_badcol();
    return $this->_validate();
  }

  function _validate($force=NULL) {
    $bad = 0;
    $d =& $this->data;

    foreach ($this->so_config['ECOLS'] as $elem) {
	    $col = $elem['Column'];
	    if (!array_key_exists($col, $d))
		    continue;
	    $v = $d[$col];
	    if (is_array($v))
		    continue; /* something special is going on */
	    $v = trim($v);
	    if ($v == '')
		    $v = NULL;
	    $d[$col] = $v;
	    $opt = mx_check_option('Option', $elem);
	    if (!is_array($opt))
		    continue;
	    $err = array();
	    $constraints = $opt['validate'];
	    if (trim($constraints) == '')
		    continue;
	    foreach (explode(',', $constraints) as $c) {
		    print "<!-- validate $col - $c -->\n";
		    if (is_null($v)) {
			    if ($c == 'nonnull')
				    $err[] = "not null";
			    continue;
		    }
		    $validator = 'mx_db_validate_' . $c;
		    if (is_callable($validator, false)) {
			    $st = $validator($v, $opt);
			    if ($st)
				    $err[] = $st;
		    }
	    }
	    if (count($err)) {
		    $this->note_badcol($col);
		    $name = $col;
		    if (array_key_exists('Label', $elem))
			    $name = $elem['Label'];
		    foreach ($err as $st) {
			    $this->err("($name): $st\n");
		    }
		    $bad++;
	    }
    }
    if ($bad)
	    return '';

    $this->error_override_allowed = NULL;
    return 'ok';
  }

  function resync() { // override
    $this->edit($this->id);
  }

  function commit($force=NULL) {

    // As usual, this is called before any of the output/drawing routines.
    // The input errors should be noted in the object and draw() should
    // take care of them.

    // This is where all the error checking should happen.
    if ($this->_validate($force) != 'ok')
      return;

    $db = mx_db_connect();

    while (1) {
      pg_query($db, 'begin');

      if ($this->_validate($force) != 'ok') {
        $status = 'failure';
	break;
      }
      $status = $this->try_commit(&$db);
      if ($status == 'ok') {
	// Resync ObjectID for subobjects.
	$this->resync();
      }
      else
	pg_query($db, 'rollback');
      if ($status != 'retry')
	break;
      error_log('lib/u/manage/simple-object:simple_object_edit xn retry');
    }

    if ($status == 'ok')
      $this->commit_ran = $this->change_nature;

  }

  function edit_in_progress() {
	  return ($this->chosen() &&
		  !$this->commit_ran &&
		  !$this->_browse_only);
  }

  function dx_submit($desc, $name, $value) {
    $label = $desc['Label'];
    mx_formi_submit($name, $name, $label, $label);
  }

  function dx_static($desc, $name, $value) {
    print htmlspecialchars($value);
    mx_formi_hidden($name, $value);
  }

  function dx_text($desc, $name, $value) {
    mx_formi_text($name, $value, $desc['Option']);
  }

  function dx_timestamp($desc, $name, $value) {
	  $timespec = mx_check_option('timespec',
				      mx_check_option('Option', $desc));
	  if ($timespec)
		  $timespec = explode(',', $timespec);
	  if (!is_array($timespec) || count($timespec) != 3)
		  return mx_formi_text($name, $value, $desc['Option']);

	  $n_per_hour = $timespec[0];
	  if ($n_per_hour < 1)
		  $n_per_hour = 1;
	  $hour_lo = $timespec[1];
	  $hour_hi = $timespec[2];

	  $selection = array();
	  $selection[''] = '';
	  $min_incr = 60 / $n_per_hour;
	  for ($hour = $hour_lo; $hour <= $hour_hi; $hour++) {
		  $min = 0;
		  for ($n = 0; $n < $n_per_hour; $n++) {
			  $label = sprintf("%02d:%02d", $hour, $min);
			  $selection[$label] = $label;
			  $min += $min_incr;
			  if ($hour == $hour_hi)
				  break;
		  }
	  }
	  if (trim($value) != '') {
		  $value = trim($value);
		  $m = array();
		  if (preg_match('/^(\d+):(\d+)/', $value, &$m))
			  $value = sprintf("%02d:%02d", $m[1], $m[2]);
	  }
	  mx_formi_select($name, $value, $selection, $desc['Option']);
  }

  function dx_date($desc, $name, $value) {
    print "<div style=\"white-space: nowrap\">";
    mx_formi_date($name, $value, $desc['Option']);
    print "</div>";
  }

  function dx_datetime($desc, $name, $value) {
    print "<div style=\"white-space: nowrap\">";
    mx_formi_datetime($name, $value, $desc['Option']);
    print "</div>";
  }

  function enum_list($desc) {
    /* override */
    return $desc['Enum'];
  }

  function dx_enum($desc, $name, $value) {
    $enum = $this->enum_list($desc);
    mx_formi_select($name, $value, $enum, $desc['Option']);
  }

  function dx_dbenum($desc, $name, $value) {
    $group = $desc['DBEnum'][0];
    $item = $desc['DBEnum'][1];

    $spec = mx_dbenum($group, $item);
    $repertoire = array();
    foreach (explode("\n", $spec['選択肢']) as $r) {
	    $r = trim($r);
	    $repertoire[] = $r;
    }
    if ($spec['Multi'] == 'Y') {
	    $value = explode('|', $value);
	    print "<div style='white-space: nowrap'>";
	    $i = 0;
	    foreach ($repertoire as $r) {
		    if ($r == '') {
			    print "<br />";
			    continue;
		    }
		    $isset = in_array($r, $value);
		    $wname = "$name-$i";
		    mx_formi_checkbox($wname, $isset,
				      array('Caption' => $r));
		    $i++;
	    }
	    print "</div>\n";
    } else {
	    $enum = array();
	    $need_value = !in_array($value, $repertoire);
	    $i = 0;
	    foreach ($repertoire as $r) {
		    if ($r == '' && $i)
			    continue;
		    if ($need_value) {
			    $enum[$value] = $value;
			    $need_value = 0;
		    }
		    $enum[$r] = $r;
		    $i++;
	    }
	    mx_formi_select($name, $value, $enum);
    }
  }

  function dx_radio($desc, $name, $value) {
    mx_formi_radio($name, $value, $desc['Enum'], $desc['Option']);
  }

  function dx_radion($desc, $name, $value) {
    mx_formi_radio($name, $value, $desc['Enum'],
		   array('item-delimiter' => '<br>'));
  }

  function dx_checkbox($desc, $name, $value) {
    mx_formi_checkbox($name, $value);
  }

  function dx_static_enum($desc, $name, $value) {
    
    if (array_key_exists($value, $desc['Enum']))
      $l = $desc['Enum'][$value];
    elseif (array_key_exists(NULL, $desc['Enum']))
      $l = $desc['Enum'][''];
    else
      $l = '';
    print htmlspecialchars($l);
    mx_formi_hidden($name, $value);
  }

  function dx_hidden($desc, $name, $value) {
    mx_formi_hidden($name, $value);
  }

  function dx_textarea($desc, $name, $value) {
    mx_formi_textarea($name, $value, $desc['Option']);
  }

  function dx_check($desc, $name, $value) {
    // $desc['Check'] should be function(v,type=NULL);
    // when type is not given it should yield a bool to tell whether v is true.
    if (is_callable($desc['Check']))
      $value = $desc['Check']($value);
    else
      $value = ($value && $value != 'N');
    mx_formi_checkbox($name, $value, $desc);
  }

  function dx_daysoftheweek($desc, $name, $value) {
	  global $_mx_daysoftheweek;
	  $dow = mx_check_option('dow', $desc['Option'], $_mx_daysoftheweek);
	  $choice = mx_check_option('dow-shorthand', $desc['Option']);
	  if (is_array($choice)) {
		  $wname = "$name-s";
		  print "<select onchange='pick_multi_checkbox(this);'>";
		  foreach ($choice as $c) {
			  $val = $c['Value'];
			  $v = $name;
			  for ($i = 0; $i < count($dow); $i++) {
				  $s = substr($val, $i, 1);
				  if ($s == 'Y')
					  $v .= " +$i";
				  else
					  $v .= " -$i";
			  }
			  print "<option value='$v'>";
			  print htmlspecialchars($c['Label']);
			  print "</option>\n";
		  }
		  print "</select>\n";
	  }
	  print "<div style='white-space: nowrap'>";
	  for ($i = 0; $i < count($dow); $i++) {
		  $isset = substr($value, $i, 1) == 'Y';
		  $wname = "$name-$i";
		  mx_formi_checkbox($wname, $isset,
				    array('Caption' => $dow[$i],
					  'WithID' => 1));
	  }
	  print "</div>\n";
  }

  function dx_extdocument($desc, $name, $value) {
    $db = mx_db_connect();
    mx_formi_hidden($name, $value);
    $col = $this->de($name);
    $bcol = $col . ".blobmedia";
    $bicol = $col . ".blobmedia-id";
    $bival = NULL;

    $option = mx_check_option('Option', $desc);
    $ext_edit = mx_check_option('ext-edit', $option);

    if ($this->data[$bicol]) {
	    $bival = $this->data[$bicol];
	    mx_formi_hidden($this->en($bicol), $bival, " id=\"" . $this->en($bicol) . "id\" ");
    } else
      $bival = mx_db_allocate_unused_id($db, 'mx_blobmedia_ID_seq');

    // hyper link for external doc edit
    $ext_edit_link_open = '';
    $ext_edit_link_close = '';
    if($ext_edit) {
      $ext_edit_link_open = '<a id="a-' . $this->en($bcol) . '" href="' .
	mx_get_ext_edit_href
	(
	 is_array($ext_edit) ? $ext_edit[0] : $ext_edit,
	 $bival,   // blobmedia id
	 $value    // extdoc id
	 ) .
	'">';

      $ext_edit_link_close = '</a>';
    }


    $img_option = mx_check_option('img', $option);
    if ($img_option) {
	    $img_url = NULL;
	    if (mx_db_blobmedia_exists($db, $bival))
		    $img_url = "/blobmedia.php?id=$bival";
	    else if ($value) {
		    $d = mx_find_ext_document($value);
		    if (!is_null($d)) {
			    $ext = $d['extension'];
			    $img_url = "/blobmedia.php/$value/j.$ext";
		    }
	    }

	    if ($img_url) {
		    if ($img_option == 'separate') {
			    print "<a target=\"_blank\" href=\"$img_url\">";
			    print "（image）</a>";
		    }
		    else if ($img_option == 'ondemand') {
			    $showit = $this->en($col . '.blobmedia-showit');
			    $showing = $col . '.blobmedia-showing';
			    if (array_key_exists($showit, $_REQUEST))
				    $this->data[$showing] = $_REQUEST[$showit];
			    if ($this->data[$showing]) {
			      print $ext_edit_link_open;
			      print "<img id=\"".$this->en($bcol)."\" src=\"$img_url\"><br />";
			      print $ext_edit_link_close;
			      mx_formi_submit($showit, 0,
					      '(非表示)', '非表示');
			    }
			    else {
				    mx_formi_submit($showit, 1,
						    '(表示)', '表示');
			    }
			    mx_formi_hidden($this->en($showing),
					    $this->data[$showing]);
		    }
		    else {
		      print $ext_edit_link_open;
		      print "<img id=\"".$this->en($bcol)."\" src=\"$img_url\"><br />";
		      print $ext_edit_link_close;
		    }
	    }else{
	      print $ext_edit_link_open;
	      print "<img id=\"".$this->en($bcol)."\" src=\"/images/new.png\"><br />";
	      print $ext_edit_link_close;
	    }

	    mx_formi_upload($this->en($bcol));
	    
	    print '<input name="再表示" value="再表示" title="再表示"';
	    printf(" onClick=\"check_blob_and_replace('%s', '%s');\"" ,
		     $bival, $this->en($bcol));
	    print "type=\"submit\" />\n";
    } else {
	    mx_formi_upload($this->en($bcol));
    }
    
  }

  function dx_subpick_enum($desc, $name, $value) {

	  $d = $desc['Subpick'];
	  $class = $d['Class'];
	  $ins = new $class('dummy');

	  if ($d['ObjectColumn'])
		  $value = $this->data[$d['ObjectColumn']];
	  $enumattr = $ins->enum($name, $value);
	  $enum = $enumattr['Enum'];
	  $activate = $enumattr['Activate'];
	  $label = $enum[$value];

	  if ($this->Subpick) {
		  mx_formi_hidden($name, $value);
		  print htmlspecialchars($label);
		  return;
	  }

	  // Name of the moniker
	  $de_name = $this->de($name);
	  $submit = $this->prefix . 'Subpick';
	  mx_formi_select($name, $value, $enum,
			  array('onchange-script' =>
				"subpick_enum(this, '$name', '$de_name', '$submit', $activate);"));
  }

  function dx_subpick($desc, $name, $value) {
    // A column with subpick shows a string (moniker) but what matters
    // in the database is the "ObjectID". $desc['Column'] is the column
    // for the moniker; $desc['Subpick']['ObjectColumn'] is the object.
    // If ObjectColumn is not specified, then the subpick column actually
    // stores the copied value from an external table.

    $d = $desc['Subpick'];
    $class = $d['Class'];
    if (array_key_exists('EnumCapable', $d)) {
	    /*
	     * Possible enhancement: ask $class if it can
	     * do this and drop the need for EnumCapable.
	     */
	    return $this->dx_subpick_enum($desc, $name, $value);
    }

    // First propagate the current value of the moniker.
    mx_formi_hidden($name, $value);

    // Name of the moniker
    $name = $this->de($name);

    // Value of the moniker for printing
    $value = htmlspecialchars($value);

    if ($this->Subpick) {
      print $value;
    }
    else {
      // Subpick button would be invisible when empty.
      if ($value == '')
	      $value = mx_empty_field_mark();
      mx_formi_submit($this->prefix . 'Subpick', $name,
		      "<span class=\"link\">$value</span>", "変更");
    }
  }

  function dx_post_code($desc, $name, $value) {
    mx_formi_text($name, $value, $desc['Option']);

    $option = mx_check_option('Option', $desc);
    $zip = mx_check_option('zip', $option);
    $prefecture = mx_check_option('prefecture', $option);
    $city = mx_check_option('city', $option);
    $block = mx_check_option('block', $option);

    printf ('<a href="#" '.
	    "onclick=\"PostCodePopup('%s', '%s', '%s', '%s');return false;\">*</a>",
	    $this->en($zip),
	    $this->en($prefecture),
	    $this->en($city),
	    $this->en($block) );
    
  }

  function dx_schema($desc, $name, $value) {
    // confom identifier naming rule in javascript. The id will be eval() by javascript
    $app_id = str_replace('-', '_', $name . '_drawapp');
    print <<<HTML
      <script>
      draw_drawapp('${app_id}', '${name}', '${value}', 0);
    </script>
    <input type="hidden" id="${name}" name="${name}" value="${value}">
HTML;
  }


  function dx_icd10($desc, $name, $value) {
    print "<span style=\"white-space: nowrap\">";
    mx_formi_text($name, $value, $desc['Option']);
    $option = mx_check_option('Option', $desc);
    $disease_field = mx_check_option('disease', $option);
    $cookie = getenv('URL_PREFIX_COOKIE');
    if(is_null($cookie))
      print "cannot use";
    else
      printf ('<a href="#" '.
	      "onclick=\"ICD10Popup('%s', '%s', '%s');return false;\">[search]</a>",
	      "/au/" . $cookie, $name, $disease_field);
    print "</span>";
  }

  function accept_subpick($subpick, $chosen) {
    if (array_key_exists('ObjectColumn', $subpick['Subpick'])) {
      $oc = $subpick['Subpick']['ObjectColumn'];
    } else
      $oc = NULL;
    $cn = $subpick['Column'];
    if (is_null($chosen)) {
      if (!is_null($oc)) $this->data[$oc] = NULL;
      $this->data[$cn] = mx_empty_field_mark();
    } else {
      $v = mx_form_unescape_key($chosen);
      if (!is_null($oc)) $this->data[$oc] = $v[0];
      $this->data[$cn] = $v[1];
    }
  }

  function draw_body_atom($desc, $d) {
    $col = $desc['Column'];
    $draw = "dx_" . $desc['Draw'];
    $this->$draw($desc, $this->en($col), $d[$col]);
  }

  function draw_body_0($d, $soc) { // override

    $page = $this->page;

    $epages = NULL;
    if (array_key_exists('EPAGES', $soc) &&
	is_array($soc['EPAGES']) &&
	2 <= count($soc['EPAGES']))
	    $epages = $soc['EPAGES'];
    if (! $epages) {
	    return;
    }

    $epage_breaks = NULL;
    if (array_key_exists('EPAGE_BREAKS', $soc) &&
	is_array($soc['EPAGE_BREAKS']))
	    $epage_breaks = $soc['EPAGE_BREAKS'];

    // Flip Page.
    print "<table class=\"flippage\"><tr>";
    $page_num = -1;
    foreach ($epages as $page_name) {
      $page_num++;
      if ($page_num == $page) {
	print "<td class=\"focused ltcorner\"></td>";
	print "<td class=\"focused\">&nbsp;";
	print $page_name;

	// Propagate
	foreach ($this->so_config['ECOLS'] as $desc) {
	  if (array_key_exists('Page', $desc) && $desc['Page'] != $page_num) {
	    $v = $this->data[$desc['Column']];
	    if ($desc['Draw'] == 'check') {
	      $this->dbglog($desc['Column'] . ' ' . $v);
	      if (is_callable($desc['Check']))
		$v = $desc['Check']($v);
	      else
		$v = ($v && $v != 'N');
	      $this->dbglog("\nbecomes " . $v . "\n");
	      if ($v) $v = 'on';
	      else
		continue; // foreach loop to pick up next hidden one.
	    }
	    mx_formi_hidden($this->en($desc['Column']), $v);
	    if ($desc['Draw'] == 'extdocument') {
	      foreach (array('.blobmedia-id', '.blobmedia-showing') as $e) {
		      $k = $desc['Column'] . $e;
		      $v = $this->data[$k];
		      mx_formi_hidden($this->en($k), $v);
	      }
	    }
	  }
	}
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
      if ($epage_breaks && in_array($page_num, $epage_breaks))
	print "</tr></table><table class=\"flippage\" width=\"100%\"><tr>";
    }
    print "</tr></table>\n";
//0320-2014
//0320-2014
//print '</span><button class="plain" title="完了" onclick="mx_submit_button(this,'." 'soe-commit', '1');".'">"';
//print '<img src="/resource/29017a23/images/i-ccommit_button.png"></button>"';
//

//print "<tr><th>user</th><td>TEST TEST</td></tr>\n";
//
  }

  // is the column empty?
  function is_empty_column($col, $data) {
	  if ($data[$col] != '')
		  return 0;
	  foreach ($this->so_config['ECOLS'] as $desc) {
		  if ($desc['Column'] != $col)
			  continue;
		  $draw = $desc['Draw'];
		  if ($draw == 'extdocument') {
			  if ($data[$col . '.blobmedia-id'] != '')
				  return 0;
		  }
		  break;
	  }
	  return 1;
  }

  // is the column specified to be omitted under certain condition?
  // if so return yes to prevent anything from getting shown.
  function omit_if_empty($desc, $data) {
	  $col = $desc['Column'];
	  if (!$this->is_empty_column($col, $data))
		  return 0;
	  $oie = $desc['Option']['OmitIfEmpty'];
	  if (!is_array($oie))
		  return 1;
	  foreach ($oie as $col) {
		  if (!$this->is_empty_column($col, $data))
			  return 0;
	  }
	  return 1;
  }

  function draw_body_3($data, $ecols, $epages, $span) {
          if($this->so_config['E_TEMPLATE'])
	          return $this->draw_body_template($data, $ecols, $epages, $span);
	  if (!$this->so_config['E_RANDOM_LAYOUT'])
		  return $this->draw_body_4($data, $ecols, $epages, $span);
	  $lookup = array();
	  foreach ($ecols as $d) {
		  if (!$d['Column'])
			  continue;
		  $lookup[$d['Column']] = $d;
	  }
	  $layo = $this->so_config['E_RANDOM_LAYOUT'];
	  $cnt = count($layo);
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
			  if ($col)
				  print "</tr>\n";
			  $col = 0;
			  continue;
		  } else if ($insn['Insn'] == '  ') {
			  if (!$col)
				  print "<tr>\n";
			  print "<td$spans>&nbsp;</td>";
			  $col += $span;
			  continue;
		  } else if ($insn['Insn'] == 'CreatedBy') {
			  $desc = array('Column' => 'CreatedBy',
					'Draw' => 'user');
		  } else {
			  $desc = $lookup[$insn['Column']];
		  }
		  if (!$col)
			  print "<tr>\n";
		  if ($insn['Label']) {
			  print "<th$spans>";
			  print htmlspecialchars($insn['Label']);
			  print "</th>";
			  $col += $span;
		  }
		  if (!$desc)
			  continue;

		  $previous_error = '';
		  if ($this->check_previous_error($insn['Column']))
			  $previous_error = ' class="has_errors"';
		  $col += $span;
		  print "<td$changed$spans$previous_error>";
		  $this->draw_body_atom($desc, $data);
		  print "</td>";
	  }
  }


  function draw_body_4($d, $ecols, $epages, $span) {
    global $_mx_resource_dir;

    foreach ($ecols as $desc) {
      if (is_null($desc['Draw']) ||
	  $epages &&
	  (array_key_exists('Page', $desc) && $desc['Page'] != $this->page))
	continue;

      $option = mx_check_option('Option', $desc);

      if (mx_check_option('OmitIfEmpty', $option) &&
	  $this->omit_if_empty($desc, $d))
	      continue;

      $col = $desc['Column'];
      if (mx_check_option('SubrecordEdit', $option))
	      continue;

      if (!is_null($desc['Label']))
	      $label = htmlspecialchars($desc['Label']);
      else
	      $label = NULL;

      print '<tr>';
      if ($desc['Draw'] == 'group_head')
	      printf('<th colspan="%d" class="group_head">%s</th>',
		     $span, $label);
      else {
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
		      printf('<th colspan="%d">%s</th>',
			     $span, $label);

		      if ($abbrev < 0)
			      $st0 = ' style="display: none"';
		      else
			      $st0 = '';
		      printf('</tr><tr id="%s"%s>', "SHD-$en", $st0);
		      $head = 0;
	      }
	      else if (!is_null($label)) {
		      $head = 1;
		      print '<th>' . $label . '</th>';
	      }
	      else
		      $head = 0;

	      if (mx_check_option('PrimaryrecordEdit',
				  mx_check_option('Option', $desc))) {
		      $this->draw_body_atoms($ecols, $col, $d);
	      }
	      else {
		      $previous_error = '';
		      if ($this->check_previous_error($col))
			      $previous_error = ' class="has_errors"';
		      printf('<td colspan="%d"%s>',
			     $span - $head,
			     $previous_error);
		      $this->draw_body_atom($desc, $d);
		      print '</td>';
	      }
      }
      print "</tr>\n";
    }

  }

  function draw_subpick($soc) {
	  if (!$this->Subpick)
		  return;
	  $option = mx_check_option('Option', $this->Subpick);
	  if(mx_check_option('popup', $option))
		  print "<div id=\"subpick\" style=\"width: 980; background-color: #e4b1ad; position: absolute; left: 100; top: 60;; border: 2px dashed; padding: 5 5 5 5\">";
	  else
		  print "<hr />\n";
	  $label = $this->Subpick['Label'];
	  if ($label == "")
		  $label = $this->Subpick['Column'];
	  mx_titlespan($label . 'を変更');

	  /*
	   * When subpick is driven with select/option, we cannot grab
	   * the original value the field used to have, so there is no
	   * way to say "Do not modify".
	   */
	  if (!$this->Subpick['Subpick']['EnumCapable'])
		  mx_formi_submit($this->prefix . 'Subpick_CANCEL', 1,
				  '<span class="link">変更しない</span>');

	  if ($this->Subpick['Subpick']['Allow_NULL']) {
		  $allow_null = $this->Subpick['Subpick']['Allow_NULL'];
		  if ($allow_null == '1')
			  $allow_null = '無設定にする';
		  mx_formi_submit($this->prefix . 'Subpick_NULL', 1,
				  "<span class=\"link\">$allow_null</span>");
	  }
	  mx_formi_hidden($this->prefix . 'Subpick', $this->Subpick['Column']);
	  $this->Subpicker->draw();
	  if(mx_check_option('popup', $option) == 1)
		  print "</div>";
  }

  function draw_control($soc) {
	  global $_mx_resource_dir;
	  global $_mx_use_control_bar;
	  global $_mx_uniform_control;

	  if ($this->Subpick)
		  return;
	  if ($this->id) { // Modifying
		  $commit = ((array_key_exists('MSGS', $soc) &&
			      array_key_exists('Commit', $soc['MSGS']))
			     ? $soc['MSGS']['Commit']
			     : $_mx_use_control_bar
			     ? '/images/i-commit_button.png'
			     : '/images/commit_button.png');
		  $rollback = ((array_key_exists('MSGS', $soc) &&
				array_key_exists('Rollback', $soc['MSGS']))
			       ? $soc['MSGS']['Rollback']
			       : $_mx_use_control_bar
			       ? '/images/i-rollback_button.png'
			       : '/images/rollback_button.png');
	  } else { // Creating
		  $commit = ((array_key_exists('MSGS', $soc) &&
			      array_key_exists('Commit', $soc['MSGS']))
			     ? $soc['MSGS']['CCommit']
			     : $_mx_use_control_bar
			     ? '/images/i-ccommit_button.png'
			     : '/images/ccommit_button.png');
		  $rollback = ((array_key_exists('MSGS', $soc) &&
				array_key_exists('Rollback', $soc['MSGS']))
			       ? $soc['MSGS']['CRollback']
			       : $_mx_use_control_bar
			       ? '/images/i-crollback_button.png'
			       : '/images/crollback_button.png');
	  }

	  if ($this->errmsg && $_mx_uniform_control) {
		  if (substr($commit, 0, 8) == "/images/")
			  $cimg = "noop-" . substr($commit, 8);
		  else
			  $cimg = 'noop-i-commit_button.png';
	  }
	  if (substr($commit, 0, 8) == "/images/")
		  $commit = "<img src=\"/$_mx_resource_dir$commit\">";
	  if (substr($rollback, 0, 8) == "/images/")
		  $rollback = "<img src=\"/$_mx_resource_dir$rollback\">";

	  if ($this->errmsg) {
		  if ($_mx_uniform_control)
			  mx_formi_nosubmit(mx_img_url($cimg));
	  } else {
		  mx_formi_submit($this->prefix . 'commit', 1, $commit,
				  'complete');
	  }
	  mx_formi_submit($this->prefix . 'rollback', 1, $rollback, '中止');
  }

  // body of the edit table
  function draw_body_1($d, $soc) {
    global $_mx_resource_dir;

    $epages = NULL;
    if (array_key_exists('EPAGES', $soc) &&
	is_array($soc['EPAGES']) &&
	2 <= count($soc['EPAGES']))
	    $epages = $soc['EPAGES'];

    $ecols = $soc['ECOLS'];
    $span = mx_check_option('EDIT_TABLE_SPAN', $soc);
    if (!$span)
	    $span = 2;
    print "<table class=\"tabular-data\" width=\"100%\">\n";

    $this->draw_body_3($d, $ecols, $epages, $span);

    print "</table>";
//0320-2014
//print '</span><button class="plain" title="完了" onclick="mx_submit_button(this,'." 'soe-commit', '1');".'">"';
//print "<td>Use this button for save</td></tr>\n";
/*
print '<td><button type="button" name="aaa" value="aaa">
<font size="2">ここを</font><font size="5" color="#333399">押してね</font>
</button>"</td>';
*/

print "<tr>";
print '<td><button class="plain" title="complete" onclick="mx_submit_button(this,'." 'soe-commit', '1');".'">'."\n";
print '<img src="/resource/29017a23/images/i-ccommit_button.png"></button>'."\n";

print "</td></tr>\n";


//
    foreach ($ecols as $desc) {
      if (! is_null($desc['Draw'])) continue;
      $col = $desc['Column'];
      mx_formi_hidden($this->en($col), $d[$col]);
    }

  }

  function draw_body_2($d, $soc, $no_subpick=0) {
	  global $_mx_use_control_bar;

	  if (!$this->separate_subpick &&
	      !$no_subpick)
		  $this->draw_subpick($soc);
	  if ($_mx_use_control_bar)
		  return;
	  $this->draw_control($soc);
  }

  function draw_control_at_the_top_too() {
	  return 1;
  }

  function draw_body() {
    global $_mx_cheap_layout;

    $d =& $this->data;
    $soc =& $this->so_config;

    if (!$_mx_cheap_layout && $this->draw_control_at_the_top_too()) {
	    print "<br />";
	    $this->draw_body_2($d, $soc, 1);
    }
    $this->draw_body_0($d, $soc);
    $this->draw_body_1($d, $soc);
    $this->draw_body_2($d, $soc);

  }

  function pass_except($except=NULL) {
    $pfix = $this->prefix;
    $plen = strlen($pfix);
    if (is_null($except))
      $except = array();
    $except = array_merge($except, array('commit', 'rollback'));
    foreach ($_REQUEST as $key => $value) {
      if (strncmp($key, $pfix, $plen) == 0) {
	 $k = substr($key, $plen);
	 $found = array_search($k, $except);
	 if (is_null($found) || false === $found)
	   if(is_array($value))
	     foreach($value as $v) 
	       mx_formi_hidden($pfix . $k . "[]", $v);
	   else
	     mx_formi_hidden($pfix . $k, $value);
      }
    }
  }

  function pass_data() {
    print '<p class="hidden-pass-data">';
    $this->draw_body();
    print '</p>';
    if ($this->threeway_status &&
	array_key_exists('DCOLS', $this->so_config)) {
	    $dcol_rev = array();
	    foreach ($this->so_config['DCOLS'] as $dcol) {
		    $dcol_rev[$dcol['Column']] = $dcol;
	    }
	    $col_dc = array('Column' => NULL, 'Draw' => 'text',
			    'Label' => '欄');
	    print "<table width=\"100%\" class=\"tabular-data\">\n<tr>\n";
	    $drawer = new _lib_so_drawer($this);
	    print "<th>欄名</th>";
	    print "<th>原データ</th>";
	    print "<th>自データ</th>";
	    print "<th>他データ</th>";
	    print "<th>競合解決</th></tr>\n";

	    foreach ($this->threeway_status as $datum) {
		    $col = $datum['Column'];
		    $o = $datum['O'];
		    $a = $datum['A'];
		    $b = $datum['B'];
		    $m = $datum['M'];

		    if (array_key_exists($col, $dcol_rev)) {
			    $dc = $dcol_rev[$col];
			    $colname = $dc['Label'];
		    }
		    else {
			    $dc = array('Column' => $col, 'Draw' => 'text',
					'Label' => $col);
			    $colname = ".$col";
		    }

		    $draw = "dx_" . $dc['Draw'];
		    print "<tr>";
		    print "<td>";
		    $drawer->dx_text($col_dc, $colname, NULL);
		    print "</td>";

		    print "<td>";
		    $drawer->$draw($dc, $o, NULL);
		    print "<!--\n"; var_dump($datum['OO']); print "-->\n";
		    print "</td>";

		    $changed = ($o != $a) ? ' class="changed"' : '';
		    print "<td$changed>";
		    $drawer->$draw($dc, $a, $changed);
		    print "<!--\n"; var_dump($datum['OA']); print "-->\n";
		    print "</td>";

		    $changed = ($o != $b) ? ' class="changed"' : '';
		    print "<td$changed>";
		    $drawer->$draw($dc, $b, $changed);
		    print "<!--\n"; var_dump($datum['OB']); print "-->\n";
		    print "</td>";

		    $changed = ($o != $m) ? ' class="changed"' : '';
		    print "<td$changed>";
		    $drawer->$draw($dc, $m, $changed);
		    print "<!--\n"; var_dump($datum['OM']); print "-->\n";
		    print "</td>";

		    print "</tr>\n";
	    }
	    print "</table>\n";
    }
    return;
  }

  function draw() {
    global $_mx_resource_dir;

    // While the subpicker is in effect the user cannot
    // commit, and we do not check.  Otherwise the columns
    // we expect the subpicker to return needs to be reencoded
    // and passed through which is too much of a mess.
    if (!$this->_Subpicker &&
	($this->_broken_origin_check())) {
        if ($this->merge_ok) {
	  if ($this->errmsg != '')
            print mx_html_paragraph($this->errmsg);
	  $this->pass_data();
	  mx_formi_hidden($this->prefix . 'id',  $this->id);
	  mx_formi_hidden($this->prefix . 'chosen', $this->chosen);
	  mx_formi_hidden($this->prefix . 'origin', $this->origin);
	}
        else {
          print '<br/ >他のユーザが先に変更を記録しました。<br/ >';
	  print '最新の状態から編集をします。内容を確認して下さい。<br />';
	  mx_formi_hidden($this->prefix . 'broken-origin', $this->origin);
	  mx_formi_hidden($this->prefix . 'id', $this->id);
	}
	mx_formi_submit($this->prefix . 'no-action', 'OK');
	return;
    }
    else if ($this->errmsg != '') {
      print "<br/ >以下のエラーを訂正して下さい。\n";
      print mx_html_paragraph($this->errmsg);

      if ($this->error_override_allowed) {
	      mx_formi_submit($this->prefix . 'no-action-error-seen',
			      '戻ってエラーを訂正');
	      mx_formi_submit($this->prefix . 'force-commit',
			      'エラーを無視して記録');
      }
      else
	      mx_formi_submit($this->prefix . 'no-action-error-seen', 'OK');
      $this->pass_except();

      if ($this->logmsg != '') {
	print "<!--\n";
	print $this->logmsg;
	print "-->\n";
      }
      return;
    }

    if ($this->empty_after_commit && $this->commit_ran) {
      print '<br />';
      if (is_array($this->empty_after_commit))
	print $this->empty_after_commit[$this->commit_ran];
      else
	print $this->empty_after_commit;
      if ($this->logmsg != '') {
	print "<!--\n";
	print $this->logmsg;
	print "-->\n";
      }
      if ($this->commit_message != '')
	print $this->commit_message;

      if (mx_check_option('SOE_EXTRA_OK_AFTER_COMMIT', $this->so_config)) {
	      $ok = '/images/done_button.png';
	      $ok = "<img src=\"/$_mx_resource_dir$ok\">";
	      print '<br />';
	      mx_formi_submit($this->prefix . 'rollback', 1, $ok);
      }
      return;
    }

    mx_formi_hidden($this->prefix . 'id',  $this->id);
    mx_formi_hidden($this->prefix . 'chosen', $this->chosen);
    mx_formi_hidden($this->prefix . 'origin', $this->origin);

    if (array_key_exists($this->prefix . 'no-action-error-seen', $_REQUEST) ||
	array_key_exists($this->prefix . 're-validate', $_REQUEST)) {
	    mx_formi_hidden($this->prefix . 're-validate', 'please');
	    $this->__pre_validate();
	    $this->errmsg = '';
    }
    $this->draw_body();

    if ($this->logmsg != '') {
      print "<!--\n";
      print $this->logmsg;
      print "-->\n";
    }
  }

  function tweak_template($s) {
    return $s;
  }
  
  function draw_body_template($d, $ecols, $epages, $span) {
    global $_mx_resource_dir;
    $template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/' . $this->so_config['E_TEMPLATE']);
    $template = $this->tweak_template($template);
    foreach ($ecols as $desc) {
      if (is_null($desc['Draw']) ||
	  $epages &&
	  (array_key_exists('Page', $desc) && $desc['Page'] != $this->page))
	continue;
      
      $option = mx_check_option('Option', $desc);
      
      if (mx_check_option('OmitIfEmpty', $option) &&
	  $this->omit_if_empty($desc, $d))
	continue;
      
      $col = $desc['Column'];
      if (mx_check_option('SubrecordEdit', $option))
	continue;
      
      if (!is_null($desc['Label']))
	$label = htmlspecialchars($desc['Label']);
      else
	$label = NULL;
      
      if ($desc['Draw'] == 'group_head') {
	$group = $label;
      }
      else {
	$abbrev = mx_check_option('AbbrevField', $option);
	if ($abbrev && !is_null($col)) {
	  $en = $this->prefix . $this->en($col);
	  $img = "/$_mx_resource_dir/images/";
	  if ($abbrev < 0)
	    $img = $img . "hide.png";
	  else
	    $img = $img . "show.png";
	  $show_hide = "show_hide('$en', '/$_mx_resource_dir')";
	  $label .= ('a href="javascript:void(0)" ' .
		     "onclick=\"$show_hide\">" .
		     "img id=\"SHC-$en\" " .
		     "src=\"$img\" alt=\"\" ".
		     'border="0" height="18" width="18"/a');
	  
	  if ($abbrev < 0)
	    $st0 = ' style="display: none"';
	  else
	    $st0 = '';
	  $head = 0;
	}
	else if (!is_null($label)) {
	  $head = 1;
	}
	else 
	  $head = 0;
	
	// check if the key has parameter following by :
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
		$desc['Option'] = array('disease' => $this->en($v),
		  		          'add_id' => 1);
	      else if($desc['Draw'] == 'textarea') {
		list($_c, $_r) = explode(',', $v);
		$desc['Option']['rows'] = $_r;
		$desc['Option']['cols'] = $_c;
		}
	    }
	  }
	  $gc = $m[0];
	}
	if (mx_check_option('PrimaryrecordEdit',
			    mx_check_option('Option', $desc))) {
	  //$this->draw_body_atoms($ecols, $col, $d);
	  print "NEEDSWORK: soe.php PrimaryrecordEdit with template";
	}
	else {
	  $desc['Option']['add_id'] = 1;
	  ob_start();
	  $this->draw_body_atom($desc, $d);
	  $v = ob_get_contents();
	  ob_end_clean();
	  $template = str_replace($gc, $v, $template);
	}
      }
      //print "</tr>\n";
    }
    print $template;
  }
}

class simple_object_ppa_edit extends simple_object_edit {

	var $patient_column_name = '患者';
	var $reception_column_name = NULL;
	var $rececom_inscol_name = NULL;
	var $rececom_dptcol_name = NULL;
	var $kick_claim_column = NULL;

	function set_ins_dept_info() {
		global $mx_authenticate_current_user;
		$r = $this->reception_column_name;
		if (!$r)
			return;
		$uid = $_REQUEST['orca_insurance_uid'];
		if ($uid) {
		  $this->data[$this->rececom_inscol_name] = $uid;
		  return;
		}
		$it = mx_get_current_reception_info
			($mx_authenticate_current_user,
			 $this->data[$this->patient_column_name]);
		$this->data[$r] = $it ? $it : NULL;
		if (!$this->data[$r])
			return;

		$it = $this->data[$r];
		if ($this->rececom_inscol_name &&
		    !$this->data[$this->rececom_inscol_name])
			$this->data[$this->rececom_inscol_name] =
				(trim($it["保険組合せ"]) != ''
				 ? trim($it["保険組合せ"])
				 : NULL);
		if ($this->rececom_dptcol_name &&
		    !$this->data[$this->rececom_dptcol_name])
			$this->data[$this->rececom_dptcol_name] =
				(trim($it["受診科目コード"]) != ''
				 ? trim($it["受診科目コード"])
				 : NULL);
	}

	function commit($force=NULL) {
		$p = $this->patient_column_name;
		$this->data[$p] = $this->so_config['Patient_ObjectID'];
		$this->set_ins_dept_info();
		return simple_object_edit::commit($force);
	}

	function kick_claim(&$db) {
		global $_mx_claim_on_order;

		if (!$this->kick_claim_column || !$_mx_claim_on_order)
			return;

		$date = $this->data[$this->kick_claim_column];
		$match = array();
		if (!preg_match('/^(\d{4})-(\d+)-(\d+)/', $date, &$match))
			return;

		$date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);
		$patient = $this->data[$this->patient_column_name];
		mx_kick_claim_if_by_poid($db, $patient, $date);
	}

	function try_commit(&$db) {
		$ret = simple_object_edit::try_commit(&$db);
		if ($ret == 'ok')
			$this->kick_claim($db);
		return $ret;
	}

}

class simple_object_poa_edit extends simple_object_edit {

	var $order_column_name = "order";

	function anew_tweak($orig_id) {
		$sood = $this->application->sood;
		$this->data[$this->order_column_name] = $sood->id;
		$this->anew_tweak_from_order($sood->fetch_data($sood->id),
					     $orig_id);
	}

	function anew_tweak_from_order($data, $orig_id) {
		; // notin'
	}
}

class _lib_so_dummy_object_edit extends simple_object_edit {
  function _lib_so_dummy_object_edit($prefix) {
    global $_lib_so_dummy_cfg;
    simple_object_edit::simple_object_edit($prefix, $_lib_so_dummy_cfg);
  }
  function anew() { return NULL; }
  function edit() { return NULL; }
  function chosen() { return NULL; }
}

?>
