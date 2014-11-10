<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/testmaster-pick.php';

$_lib_u_manage_testset_base_stmt =
 'select "ObjectID" , "Name", "SortOrder", "CT", "code"
  from   test_set';

$_lib_u_manage_testset_cfg = array
(
 'TABLE' => 'test_set',
 'COLS' => array('ObjectID', 'Name', 'SortOrder', 'CT'),
 'LCOLS' => array(array('Column' => 'Name',
			'Label' => '¸¡ºº¥»¥Ã¥ÈÌ¾',
			'Draw' => 'text'),
		  array('Column' => 'SortOrder',
			'Label' => 'É½¼¨½ç',
			'Draw' => 'text'),
		  array('Column' => 'CT',
			'Label' => '¼£¸³ÍÑ',
			'Draw' => 'enum',
			'Enum' => array( 'Y' => '¼£¸³ÍÑ')
			),
		  ),
 'ECOLS' => array(array('Column' => 'ObjectID', 'Draw' => NULL),
		  array('Column' => 'Name',
			'Label' => '¸¡ºº¥»¥Ã¥ÈÌ¾',
			'Draw' => 'text'),
		  array('Column' => 'code',
			'Label' => '¸¡ºº²ñ¼Ò¥»¥Ã¥È¥³¡¼¥É',
			'Draw' => 'text',
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => 'SortOrder',
			'Label' => 'É½¼¨½ç',
			'Draw' => 'text',
			'Option' => array('validate' => 'nonnull,posint')),
		  array('Column' => 'Test',
			'Label' => '¸¡ºº¹àÌÜ',
			'Draw' => 'test_list'),
		  array('Column' => 'CT',
			'Label' => '¼£¸³ÍÑ',
			'Draw' => 'check',
			),
		  ),
 'ICOLS' => array('Name', 'SortOrder', 'CT', 'code'),
 'DCOLS' => array(array('Column' => 'Name',
			'Label' => '¸¡ºº¥»¥Ã¥ÈÌ¾',
			'Draw' => 'text'),
		  array('Column' => 'code',
			'Label' => '¸¡ºº²ñ¼Ò¥»¥Ã¥È¥³¡¼¥É',
			'Draw' => 'text'),
		  array('Column' => 'SortOrder',
			'Label' => 'É½¼¨½ç',
			'Draw' => 'text'),
		  array('Column' => 'Test',  // this has to match with fetch
			'Label' => '¸¡ºº¹àÌÜ',
			'Draw' => 'test_list'),
		  array('Column' => 'CT',
			'Label' => '¼£¸³ÍÑ',
			'Draw' => 'enum',
			'Enum' => array( 'Y' => '¼£¸³ÍÑ')
			),
		  array('Column' => 'CreatedBy',
			'Label' => 'µ­Ï¿¼Ô',
			'Draw' => 'user')),

 'HSTMT' => $_lib_u_manage_testset_base_stmt .' WHERE (NULL IS NULL) ',

 'STMT' => ($_lib_u_manage_testset_base_stmt .' WHERE  ("Superseded" IS NULL) '),
);

function _lib_u_manage_testset_fetch_data($it, $oid) {
  global $_lib_u_manage_testset_cfg;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_manage_testset_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));

  if ($it) $it->dbglog("Fetch-Main: $stmt;\n");
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // $data = 1, screening, 100
  // Fetch from subtables.
  $stmt = ('SELECT sm."TestID" as "Test", m."Name" AS "TestName",
            c."Name" as "Category", m."Åö±¡ºÎÍÑ" as "Active"
            FROM "test_set_map" AS sm JOIN "test_master" AS m
            ON sm."TestID" = m."ObjectID" AND m."Superseded" IS NULL,
            "test_category" as c
            WHERE m."Category" = c."ObjectID" and sm."SetID" = '. mx_db_sql_quote($oid));

  $stmt .= ' ORDER BY m."ObjectID"';

  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['Test'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach ($d as $row){
      $data['Test'][] = array_map('mx_trim', $row);
    }
  }
  return $data;
}

class list_of_testsets extends list_of_simple_objects {

  function list_of_testsets($prefix, $config=NULL) {
    global $_lib_u_manage_testset_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_testset_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

}

class testset_display extends simple_object_display {

  function testset_display($prefix, $config=NULL) {
    global $_lib_u_manage_testset_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_testset_cfg;
    simple_object_display::simple_object_display
      ($prefix, $config);
  }

  function fetch_data($id) {
    return _lib_u_manage_testset_fetch_data(&$this, $id);
  }

  function dx_test_list($desc, $data, $changed) {
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
      $value = $value . '<td width=50%>';
      if($row['Active'] != 'Y') {
	$value .= '<font color="red">';
	$value .= htmlspecialchars($row['TestName']);
	$value .= '</font>';
      }else
	$value .= htmlspecialchars($row['TestName']);
      $value .= '</td></tr>';
    }
    $value = $value . '</table>';
    $this->_dx_textish($value, $changed, 'noquote');
  }

}

class testset_edit extends simple_object_edit {
  var $debug = 1;
  function testset_edit($prefix, $config=NULL) {
    global $_lib_u_manage_testset_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_testset_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $config);
  }

  function fetch_data($id) {
    $d = _lib_u_manage_testset_fetch_data(&$this, $id);
    $this->dbglog('RPE fetch: ' . mx_var_dump($d));
    $d2 = array();
    foreach($d['Test'] as $row)
      if ($row['Active'] == 'Y')
	$d2[] = $row;
    $d['Test']  = $d2;
    return $d;
  }

  function data_compare($curr, $data) {
    foreach (array('Name', 'SortOrder', 'CT', 'code') as $col)
      if ($curr[$col] != $data[$col]) return 1;
    if (count($curr['Test']) != count($data['Test'])) return 1;
    $cpid = array();
    $dpid = array();
    foreach ($curr['Test'] as $r) $cpid[] = $r['Test'];
    foreach ($data['Test'] as $r) $dpid[] = $r['Test'];
    return $cpid != $dpid;
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_testmaster_pick_cfg;
    $this->log('AFD0 ' . mx_var_dump($_REQUEST));
    $this->log('AFD0 ' . mx_var_dump($data));
    if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'tp'])) {
      $data['Test'] = array();
      foreach ($_REQUEST[$this->prefix . 'tp'] as $ent) {
	$a = mx_form_unescape_key($ent);
	// must match with LIST_ID in testmaster-pick config
	$data['Test'][] = array('Test' => $a[0],
				'TestName' => $a[1]);
      }
    }
    $this->log('AFD1 ' . mx_var_dump($data));

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST)) {
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];
      $cfg = $_lib_u_manage_testmaster_pick_cfg;
      if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	  is_array($_REQUEST[$this->prefix . 'tp']))
	$cfg['Select'] = $_REQUEST[$this->prefix . 'tp'];
      else
	$cfg['Select'] = array();
      $this->Subpicker = new testmaster_pick($this->prefix . 'tsp-', $cfg);
      $this->Subpick = array('Column' => '¸¡ºº¹àÌÜ¥ê¥¹¥È',
			     'Subpick' => array());
    }

  }

  function dx_test_list($desc, $name, $data) {
    $value = '';
    $value = $value . '<table class="">';
    $prev_category = '';
    if(is_array($data)) {
      foreach ($data as $row) {
	if ($prev_category != $row['Category']) {
	  $value = $value . '<tr><td width=50%>'.$row['Category']."</td>";
	  $prev_category = $row['Category'];
	}
	else
	  $value = $value .'<tr><td></td>';
	$value = $value . '<td width=50%>' .htmlspecialchars($row['TestName']) . '</td></tr>';
	mx_formi_hidden($this->prefix . 'tp[]',
			mx_form_escape_key
			(array($row['Test'], $row['TestName'])));
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
	// must match with LIST_ID in testmaster-pick config
	$d[] = array('Test' => $a[0],
		     'TestName' => $a[1]);
      }
      $this->data['Test'] = $d;
    }
    $this->log('AS2' . mx_var_dump($this));
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "test_set_map" SET "SetID" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "SetID" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
    foreach ($this->data['Test'] as $r) {
      $stmt = ('INSERT INTO "test_set_map" ("SetID", "TestID") VALUES '.
	       '(' . mx_db_sql_quote($id) . ', ' .
	       mx_db_sql_quote($r['Test']) . ')');
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }

}

?>
