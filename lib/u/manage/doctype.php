<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_doctype_cfg = array
(
 'COLS' => array("label_string", "mime_type", "extension"),
 'TABLE' => 'mx_doctype',
);

class list_of_doctypes extends list_of_simple_objects {
  function list_of_doctypes($prefix, $cfg=NULL) {
    global $_lib_u_manage_doctype_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_doctype_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class doctype_display extends simple_object_display {
  function doctype_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_doctype_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_doctype_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class doctype_edit extends simple_object_edit {
  function doctype_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_doctype_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_doctype_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
