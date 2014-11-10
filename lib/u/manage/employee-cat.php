<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_employee_cat_cfg = array
(
 'COLS' => array("コード", "職種"),
 'TABLE' => '職種一覧表',
 'ALLOW_SORT' => array("コード" => array("コード" => '"コード"')),
);

class list_of_employee_cats extends list_of_simple_objects {
  function list_of_employee_cats($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_cat_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_cat_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class employee_cat_display extends simple_object_display {
  function employee_cat_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_cat_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_cat_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class employee_cat_edit extends simple_object_edit {
  function employee_cat_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_cat_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_cat_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
    $this->data["コード"] = NULL;
  }
}
?>
