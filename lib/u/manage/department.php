<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_department_cfg = array
(
 'COLS' => array("コード", "大分類", "中分類1", "中分類2", "小分類"),
 'LCOLS' => array('コード', '部署'),
 'TABLE' => '部署一覧表',
 'ALLOW_SORT' => array('コード' => array('コード' => '"コード"'),
		       '部署' => array("大分類" => '"大分類"',
				       "中分類1" => '"中分類1"',
				       "中分類2" => '"中分類2"',
				       "小分類" => '"小分類"')),
 'ENABLE_QBE' => array('コード',
		       array('Column' => '部署',
			     'Compare' => ('COALESCE("大分類",\'\')||' .
					   'COALESCE("中分類1",\'\')||' .
					   'COALESCE("中分類2",\'\')||' .
					   'COALESCE("小分類",\'\')'))),
 );

function _lib_u_manage_department_abbrev(&$data, $cols=NULL) {
  global $_lib_u_manage_department_cfg;
  $name = '';
  if (is_null($cols))
    $cols =& $_lib_u_manage_department_cfg['COLS'];

  // e.g.  0:   1:   2:       3:     4:
  //       1111 本部 管理部門 事務部 総務課
  $base = 2;
  if ($data[$cols[$base]] == '') $base = 1;
  $joiner = ' - ';

  $lmt = count($cols);
  for ($ix = $base; $ix < $lmt; $ix++) {
    if ($data[$cols[$ix]] == '') break;
    if ($name != '') $name .= $joiner;
    $name .= $data[$cols[$ix]];
  }
  return $name;
}

class list_of_departments extends list_of_simple_objects {

  function list_of_departments($prefix, $cfg=NULL) {
    global $_lib_u_manage_department_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_department_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function annotate_row_data(&$row) {
    $row['部署'] = _lib_u_manage_department_abbrev($row);
  }

}

class department_display extends simple_object_display {
  function department_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_department_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_department_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class department_edit extends simple_object_edit {
  function department_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_department_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_department_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
    $this->data["コード"] = NULL;
  }
}
?>
