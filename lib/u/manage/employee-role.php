<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_employee_role_cfg = array
(
 'COLS' => array("Ã´ÅöÌò³ä", "À°Îó½ç°Ì", "Employee_Class"),
 'TABLE' => 'Ã´ÅöÌò³ä',
);

class list_of_employee_roles extends list_of_simple_objects {
  function list_of_employee_roles($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_role_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_role_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class employee_role_display extends simple_object_display {
  function employee_role_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_role_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_role_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class employee_role_edit extends simple_object_edit {
  function employee_role_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_role_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_role_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
