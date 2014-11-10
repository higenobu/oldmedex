<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/patient-pick.php';

$_lib_u_manage_room_patient_base_stmt =
'SELECT RP."ObjectID", RP."CreatedBy", RP."ÆüÉÕ",
            R."ObjectID" AS "ÉÂ¼¼", R."ÉÂ¼¼Ì¾"
	    FROM "ÉÂ¼¼´µ¼ÔÉ½" AS RP JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
            ON RP."ÉÂ¼¼" = R."ObjectID" AND R."Superseded" IS NULL';

$_lib_u_manage_room_patient_cfg = array
(
 'TABLE' => 'ÉÂ¼¼´µ¼ÔÉ½',
 'COLS' => array('ÉÂ¼¼', 'ÉÂ¼¼Ì¾', 'ÆüÉÕ'),
 'LCOLS' => array('ÉÂ¼¼Ì¾', 'ÆüÉÕ'),
 'ECOLS' => array(array('Column' => 'ÉÂ¼¼', 'Draw' => NULL),
		  array('Column' => 'ÆüÉÕ', 'Draw' => 'rp_date_today'),
		  array('Column' => '´µ¼Ô',
			'Draw' => 'pt_list'),
		  ),
 'ICOLS' => array('ÉÂ¼¼', 'ÆüÉÕ'),

 'DCOLS' => array('ÉÂ¼¼Ì¾', 'ÆüÉÕ',
		  array('Column' => '´µ¼Ô',
			'Draw' => 'pt_list'),
		  array('Column' => 'CreatedBy',
			'Label' => 'µ­Ï¿¼Ô',
			'Draw' => 'user')),

 'HSTMT' => $_lib_u_manage_room_patient_base_stmt .' WHERE (NULL IS NULL) ',

 'STMT' => ($_lib_u_manage_room_patient_base_stmt .
	    ' WHERE (RP."Superseded" IS NULL) '),

);

function _lib_u_manage_room_patient_fetch_data($it, $oid) {
  global $_lib_u_manage_room_patient_cfg;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_manage_room_patient_cfg['HSTMT'] .
	   'AND RP."ObjectID" = ' . mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Main: $stmt;\n");
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];

  // Fetch from subtables.
  $stmt = ('SELECT RPD."´µ¼Ô", (P."À«" || \' \' || P."Ì¾") AS "´µ¼ÔÌ¾",
            P."´µ¼ÔID"
            FROM "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" AS RPD JOIN "´µ¼ÔÂæÄ¢" AS P
            ON RPD."´µ¼Ô" = P."ObjectID" AND P."Superseded" IS NULL
            WHERE "ÉÂ¼¼´µ¼ÔÉ½" = '. mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['´µ¼Ô'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach ($d as $row)
      $data['´µ¼Ô'][] = array_map('mx_trim', $row);
  }
  return $data;
}

class list_of_room_patients extends list_of_simple_objects {

  function list_of_room_patients($prefix, $config=NULL) {
    global $_lib_u_manage_room_patient_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_room_patient_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function row_paging_keys() { return array('ÉÂ¼¼'); }
  function row_paging_aliases() { return array('R."ObjectID"'); }

}

class room_patient_display extends simple_object_display {

  function room_patient_display($prefix, $config=NULL) {
    global $_lib_u_manage_room_patient_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_room_patient_cfg;
    simple_object_display::simple_object_display
      ($prefix, $config);
  }

  function fetch_data($id) {
    return _lib_u_manage_room_patient_fetch_data(&$this, $id);
  }

  function dx_pt_list($desc, $data, $changed) {
    $value = '';
    foreach ($data as $row) {
      $value = $value . htmlspecialchars($row['´µ¼ÔÌ¾']) . '<br />';
    }
    $this->_dx_textish($value, $changed, 'noquote');
  }

}

class room_patient_edit extends simple_object_edit {

  function room_patient_edit($prefix, $config=NULL) {
    global $_lib_u_manage_room_patient_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_room_patient_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $config);
  }

  function fetch_data($id) {
    $d = _lib_u_manage_room_patient_fetch_data(&$this, $id);
    $this->dbglog('RPE fetch: ' . mx_var_dump($d));
    return $d;
  }

  function data_compare($curr, $data) {
    foreach (array('ÉÂ¼¼', 'ÆüÉÕ') as $col)
      if ($curr[$col] != $data[$col]) return 1;
    if (count($curr['´µ¼Ô']) != count($data['´µ¼Ô'])) return 1;
    $cpid = array();
    $dpid = array();
    foreach ($curr['´µ¼Ô'] as $r) $cpid[] = $r['´µ¼Ô'];
    foreach ($data['´µ¼Ô'] as $r) $dpid[] = $r['´µ¼Ô'];
    return $cpid != $dpid;
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_patient_pick_cfg;

    $this->log('AFD0 ' . mx_var_dump($_REQUEST));
    $this->log('AFD0 ' . mx_var_dump($data));
    if (array_key_exists($this->prefix . 'pt', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'pt'])) {
      $data['´µ¼Ô'] = array();
      foreach ($_REQUEST[$this->prefix . 'pt'] as $ent) {
	$a = mx_form_unescape_key($ent);
	$data['´µ¼Ô'][] = array('´µ¼Ô' => $a[0],
				'´µ¼ÔID' => $a[1],
				'´µ¼ÔÌ¾' => $a[2]);
      }
    }
    $this->log('AFD1 ' . mx_var_dump($data));

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST)) {
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];
      $cfg = $_lib_u_manage_patient_pick_cfg;
      if (array_key_exists($this->prefix . 'pt', $_REQUEST) &&
	  is_array($_REQUEST[$this->prefix . 'pt']))
	$cfg['Select'] = $_REQUEST[$this->prefix . 'pt'];
      else
	$cfg['Select'] = array();
      $this->Subpicker = new room_patient_pick($this->prefix . 'rpp-', $cfg);
      $this->Subpick = array('Column' => 'ÉÂ¼¼´µ¼Ô¥ê¥¹¥È',
			     'Subpick' => array());
    }

  }

  function dx_rp_date_today($desc, $name, $data) {
    $today = mx_today_string();
    mx_formi_hidden($name, $today);
    print htmlspecialchars($today);
  }

  function dx_pt_list($desc, $name, $data) {
    $value = '';
    if (is_array($data) && count($data)) {
      foreach ($data as $row) {
	$value = $value . htmlspecialchars($row['´µ¼ÔÌ¾']) . '<br />';
	mx_formi_hidden($this->prefix . 'pt[]',
			mx_form_escape_key
			(array($row['´µ¼Ô'], $row['´µ¼ÔID'], $row['´µ¼ÔÌ¾'])));
      }
    }
    if ($value == '')
	    $value = mx_empty_field_mark();
    if ($this->Subpick)
      print $value;
    else
      mx_formi_submit($this->prefix . 'Subpick', 0,
		      "<span class=\"link\">$value</span>");
  }

  function accept_subpick($subpick, $chosen) {
    $this->log('AS0' . mx_var_dump($this));
    $this->log('AS1' . mx_var_dump($chosen));
    if (is_array($chosen)) {
      $d = array();
      foreach ($chosen as $v) {
	$a = mx_form_unescape_key($v);
	$d[] = array('´µ¼Ô' => $a[0],
		     '´µ¼ÔID' => $a[1],
		     '´µ¼ÔÌ¾' => $a[2]);
      }
      $this->data['´µ¼Ô'] = $d;
    }
    $this->log('AS2' . mx_var_dump($this));
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" SET "ÉÂ¼¼´µ¼ÔÉ½" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "ÉÂ¼¼´µ¼ÔÉ½" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
    if (is_array($this->data['´µ¼Ô']) && count($this->data['´µ¼Ô'])) {
      foreach ($this->data['´µ¼Ô'] as $r) {
	$stmt = ('INSERT INTO "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" ("ÉÂ¼¼´µ¼ÔÉ½", "´µ¼Ô") VALUES '.
		 '(' . mx_db_sql_quote($id) . ', ' .
		 mx_db_sql_quote($r['´µ¼Ô']) . ')');
	$this->dbglog("Insert-Subs: $stmt\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);
      }
    }
  }

}

?>
