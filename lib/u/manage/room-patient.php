<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/patient-pick.php';

$_lib_u_manage_room_patient_base_stmt =
'SELECT RP."ObjectID", RP."CreatedBy", RP."����",
            R."ObjectID" AS "�¼�", R."�¼�̾"
	    FROM "�¼�����ɽ" AS RP JOIN "�¼�����ɽ" AS R
            ON RP."�¼�" = R."ObjectID" AND R."Superseded" IS NULL';

$_lib_u_manage_room_patient_cfg = array
(
 'TABLE' => '�¼�����ɽ',
 'COLS' => array('�¼�', '�¼�̾', '����'),
 'LCOLS' => array('�¼�̾', '����'),
 'ECOLS' => array(array('Column' => '�¼�', 'Draw' => NULL),
		  array('Column' => '����', 'Draw' => 'rp_date_today'),
		  array('Column' => '����',
			'Draw' => 'pt_list'),
		  ),
 'ICOLS' => array('�¼�', '����'),

 'DCOLS' => array('�¼�̾', '����',
		  array('Column' => '����',
			'Draw' => 'pt_list'),
		  array('Column' => 'CreatedBy',
			'Label' => '��Ͽ��',
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
  $stmt = ('SELECT RPD."����", (P."��" || \' \' || P."̾") AS "����̾",
            P."����ID"
            FROM "�¼����ԥǡ���" AS RPD JOIN "������Ģ" AS P
            ON RPD."����" = P."ObjectID" AND P."Superseded" IS NULL
            WHERE "�¼�����ɽ" = '. mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['����'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach ($d as $row)
      $data['����'][] = array_map('mx_trim', $row);
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

  function row_paging_keys() { return array('�¼�'); }
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
      $value = $value . htmlspecialchars($row['����̾']) . '<br />';
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
    foreach (array('�¼�', '����') as $col)
      if ($curr[$col] != $data[$col]) return 1;
    if (count($curr['����']) != count($data['����'])) return 1;
    $cpid = array();
    $dpid = array();
    foreach ($curr['����'] as $r) $cpid[] = $r['����'];
    foreach ($data['����'] as $r) $dpid[] = $r['����'];
    return $cpid != $dpid;
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_patient_pick_cfg;

    $this->log('AFD0 ' . mx_var_dump($_REQUEST));
    $this->log('AFD0 ' . mx_var_dump($data));
    if (array_key_exists($this->prefix . 'pt', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'pt'])) {
      $data['����'] = array();
      foreach ($_REQUEST[$this->prefix . 'pt'] as $ent) {
	$a = mx_form_unescape_key($ent);
	$data['����'][] = array('����' => $a[0],
				'����ID' => $a[1],
				'����̾' => $a[2]);
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
      $this->Subpick = array('Column' => '�¼����ԥꥹ��',
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
	$value = $value . htmlspecialchars($row['����̾']) . '<br />';
	mx_formi_hidden($this->prefix . 'pt[]',
			mx_form_escape_key
			(array($row['����'], $row['����ID'], $row['����̾'])));
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
	$d[] = array('����' => $a[0],
		     '����ID' => $a[1],
		     '����̾' => $a[2]);
      }
      $this->data['����'] = $d;
    }
    $this->log('AS2' . mx_var_dump($this));
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "�¼����ԥǡ���" SET "�¼�����ɽ" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "�¼�����ɽ" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
    if (is_array($this->data['����']) && count($this->data['����'])) {
      foreach ($this->data['����'] as $r) {
	$stmt = ('INSERT INTO "�¼����ԥǡ���" ("�¼�����ɽ", "����") VALUES '.
		 '(' . mx_db_sql_quote($id) . ', ' .
		 mx_db_sql_quote($r['����']) . ')');
	$this->dbglog("Insert-Subs: $stmt\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);
      }
    }
  }

}

?>
