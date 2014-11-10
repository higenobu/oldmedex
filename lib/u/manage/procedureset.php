<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/proceduremaster-pick.php';

$_lib_u_manage_procedureset_base_stmt =
 'select "ObjectID" , "Name", "SortOrder"
  from   procedure_set';

$_lib_u_manage_procedureset_cfg = array
(
 'TABLE' => 'procedure_set',
 'COLS' => array('ObjectID', 'Name', 'SortOrder'),
 'LCOLS' => array(array('Column' => 'Name',
			'Label' => '処置セット名',
			'Draw' => 'text'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順',
			'Draw' => 'text'),
		  ),
 'ECOLS' => array(array('Column' => 'ObjectID', 'Draw' => NULL),
		  array('Column' => 'Name',
			'Label' => '処置セット名',
			'Draw' => 'text'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順',
			'Draw' => 'text'),
		  array('Column' => 'Procedure',
			'Label' => '処置項目',
			'Draw' => 'procedure_list'),
		  ),
 'ICOLS' => array('Name', 'SortOrder'),
 'DCOLS' => array(array('Column' => 'Name',
			'Label' => '処置セット名',
			'Draw' => 'text'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順',
			'Draw' => 'text'),
		  array('Column' => 'Procedure',  // this has to match with fetch
			'Label' => '処置項目',
			'Draw' => 'procedure_list'),
		  array('Column' => 'CreatedBy',
			'Label' => '記録者',
			'Draw' => 'user')),

 'HSTMT' => $_lib_u_manage_procedureset_base_stmt .' WHERE (NULL IS NULL) ',

 'STMT' => ($_lib_u_manage_procedureset_base_stmt .' WHERE  ("Superseded" IS NULL) '),
);

function _lib_u_manage_procedureset_fetch_data($it, $oid) {
  global $_lib_u_manage_procedureset_cfg;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_manage_procedureset_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Main: $stmt;\n");
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // $data = 1, screening, 100
  // Fetch from subtables.
  $stmt = ('SELECT sm."ProcedureID" as "Procedure", m."Name" AS "ProcedureName",
            c."Name" as "Category"
            FROM "procedure_set_map" AS sm JOIN "procedure_master" AS m
            ON sm."ProcedureID" = m."ObjectID" AND m."Superseded" IS NULL,
            "procedure_category" as c
            WHERE m."Category" = c."ObjectID" and sm."SetID" = '. mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['Procedure'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach ($d as $row){
      $data['Procedure'][] = array_map('mx_trim', $row);
    }
  }
  return $data;
}

class list_of_proceduresets extends list_of_simple_objects {

  function list_of_proceduresets($prefix, $config=NULL) {
    global $_lib_u_manage_procedureset_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_procedureset_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

}

class procedureset_display extends simple_object_display {

  function procedureset_display($prefix, $config=NULL) {
    global $_lib_u_manage_procedureset_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_procedureset_cfg;
    simple_object_display::simple_object_display
      ($prefix, $config);
  }

  function fetch_data($id) {
    return _lib_u_manage_procedureset_fetch_data(&$this, $id);
  }

  function dx_procedure_list($desc, $data, $changed) {
    $value = '';
    $value = $value . '<table>';
    $prev_category = '';
    foreach ($data as $row) {
      if ($prev_category != $row['Category']) {
	$value = $value . '<tr><td width=50%>'.$row['Category']."</td>";
	$prev_category = $row['Category'];
      }
      else
	$value = $value .'<tr><td></td>';
      $value = $value . '<td width=50%>' .htmlspecialchars($row['ProcedureName']) . '</td></tr>';
    }
    $value = $value . '</table>';
    $this->_dx_textish($value, $changed, 'noquote');
  }

}

class procedureset_edit extends simple_object_edit {
  var $debug = 1;
  function procedureset_edit($prefix, $config=NULL) {
    global $_lib_u_manage_procedureset_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_procedureset_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $config);
  }

  function fetch_data($id) {
    $d = _lib_u_manage_procedureset_fetch_data(&$this, $id);
    $this->dbglog('RPE fetch: ' . mx_var_dump($d));
    return $d;
  }

  function data_compare($curr, $data) {
    foreach (array('Name', 'SortOrder') as $col)
      if ($curr[$col] != $data[$col]) return 1;
    if (count($curr['Procedure']) != count($data['Procedure'])) return 1;
    $cpid = array();
    $dpid = array();
    foreach ($curr['Procedure'] as $r) $cpid[] = $r['Procedure'];
    foreach ($data['Procedure'] as $r) $dpid[] = $r['Procedure'];
    return $cpid != $dpid;
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_proceduremaster_pick_cfg;
    $this->log('AFD0 ' . mx_var_dump($_REQUEST));
    $this->log('AFD0 ' . mx_var_dump($data));
    if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'tp'])) {
      $data['Procedure'] = array();
      foreach ($_REQUEST[$this->prefix . 'tp'] as $ent) {
	$a = mx_form_unescape_key($ent);
	// must match with LIST_ID in proceduremaster-pick config
	$data['Procedure'][] = array('Procedure' => $a[0],
				'ProcedureName' => $a[1]);
      }
    }
    $this->log('AFD1 ' . mx_var_dump($data));

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST)) {
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];
      $cfg = $_lib_u_manage_proceduremaster_pick_cfg;
      if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	  is_array($_REQUEST[$this->prefix . 'tp']))
	$cfg['Select'] = $_REQUEST[$this->prefix . 'tp'];
      else
	$cfg['Select'] = array();
      $this->Subpicker = new proceduremaster_pick($this->prefix . 'tsp-', $cfg);
      $this->Subpick = array('Column' => '処置項目リスト',
			     'Subpick' => array());
    }

  }

  function dx_procedure_list($desc, $name, $data) {
    $value = '';
    $value = $value . '<table class="">';
    $prev_category = '';
    if(!is_null($data) and count($data) > 0) {
     foreach ($data as $row) {
      if ($prev_category != $row['Category']) {
	$value = $value . '<tr><td width=50%>'.$row['Category']."</td>";
	$prev_category = $row['Category'];
      }
      else
	$value = $value .'<tr><td></td>';
      $value = $value . '<td width=50%>' .htmlspecialchars($row['ProcedureName']) . '</td></tr>';
      mx_formi_hidden($this->prefix . 'tp[]',
		      mx_form_escape_key
		      (array($row['Procedure'], $row['ProcedureName'])));
     }
    }
    $value = $value . '</table>';
    if ($value == '<table class=""></table>')
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
	// must match with LIST_ID in proceduremaster-pick config
	$d[] = array('Procedure' => $a[0],
		     'ProcedureName' => $a[1]);
      }
      $this->data['Procedure'] = $d;
    }
    $this->log('AS2' . mx_var_dump($this));
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "procedure_set_map" SET "SetID" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "SetID" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
    foreach ($this->data['Procedure'] as $r) {
      $stmt = ('INSERT INTO "procedure_set_map" ("SetID", "ProcedureID") VALUES '.
	       '(' . mx_db_sql_quote($id) . ', ' .
	       mx_db_sql_quote($r['Procedure']) . ')');
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }

}

?>
