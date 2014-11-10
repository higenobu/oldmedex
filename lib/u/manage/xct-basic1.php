<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_employee_rank_cfg = array
(
 'COLS' => array("職位"),
 'TABLE' => '職位一覧表',
);

class list_of_employee_ranks extends list_of_simple_objects {
  function list_of_employee_ranks($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_rank_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_employee_rank_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class employee_rank_display extends simple_object_display {
  function employee_rank_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_rank_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_employee_rank_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class employee_rank_edit extends simple_object_edit {
  function employee_rank_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_rank_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_employee_rank_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
