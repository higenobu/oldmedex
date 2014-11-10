<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

$_test_labs = array();

function get_labs() {
  global $_test_labs;
  if(count($_test_labs) > 0)
    return $_test_labs;

  $db = mx_db_connect();
  $stmt = 'SELECT "ObjectID", "Name" FROM "test_lab" 
           WHERE "Superseded" is NULL  AND "Active" = 1
           ORDER BY "SortOrder"';
  $sth = pg_query($db, $stmt);
  $ret = array();
  while ($row = pg_fetch_array($sth, NULL, PGSQL_ASSOC) )
    $ret[$row['ObjectID']] =  $row['Name'];
  $_test_labs = $ret;
  return $ret;
}

  
function __lib_u_manage_test_form_cfg(&$cfg) { 
  $cfg = array_merge
    (
     $cfg, array
     (
      'TABLE' => "test_form",
      'COLS' => array("Lab", "Name", "SortOrder", "Active"),
      'LCOLS' => array(array('Column' => 'Lab',
			     'Label' => '検査施設名',
			     'Enum' => get_labs(),
			     'Draw' => 'enum',
			     ),
		       array('Column' => 'Name',
			     'Label' => '用紙名',
			     ),
		       array('Column' => 'SortOrder',
			     'Label' => '表示順'
			     ),
		       array('Column' => 'Active',
			     'Draw' => 'enum',
			     'Enum' => array(0 => '無効', 1 => '有効'),
			     'Label' => '状態',
			     ),
		       ),
      'DCOLS' => array(array('Column' => 'Lab',
			     'Label' => '検査施設名',
			     'Enum' => get_labs(),
			     'Draw' => 'enum',
			     ),
		       array('Column' => 'Name',
			     'Label' => '用紙名',
			     ),
		       array('Column' => 'SortOrder',
			     'Label' => '表示順'
			     ),
		       array('Column' => 'Active',
			     'Draw' => 'enum',
			     'Enum' => array(0 => '無効', 1 => '有効'),
			     'Label' => '状態',
			     ),
		       ),
      'ECOLS' => array(array('Column' => 'Lab',
			     'Label' => '検査施設名',
			     'Enum' => get_labs(),
			     'Draw' => 'enum',
			     ),
		       array('Column' => 'Name',
			     'Label' => '用紙名',
			     ),
		       array('Column' => 'SortOrder',
			     'Label' => '表示順'
			     ),
		       array('Column' => 'Active',
			     'Draw' => 'enum',
			     'Enum' => array(0 => '無効', 1 => '有効'),
			     'Label' => '状態',
			     ),
		       ),
      )
     );
}

class list_of_manage_test_forms extends list_of_simple_objects {
  function list_of_manage_test_forms($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_manage_test_form_cfg(&$cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class manage_test_form_display extends simple_object_display {
  function manage_test_form_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_manage_test_form_cfg(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class manage_test_form_edit extends simple_object_edit {
  function manage_test_form_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_manage_test_form_cfg(&$cfg);
    simple_object_edit::simple_object_edit($prefix, &$cfg);
  }
}


class manage_test_form_application extends single_table_application {
  function manage_test_form_application() {
    global $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    single_table_application::single_table_application();
  }

  function list_of_objects($prefix) {
    return new list_of_manage_test_forms($prefix);
  }

  function object_display($prefix) {
    return new manage_test_form_display($prefix);
  }

  function object_edit($prefix) {
    return new manage_test_form_edit($prefix);
  }
}

?>
