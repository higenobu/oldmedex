<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_ctitem_category_cfg = array
(
 'COLS' => array("µÏ∫ŒÕ—ÃÙ", "√Ê ¨Œ‡", "æÆ ¨Œ‡","π‡Ã‹Ãæ"),
 'TABLE' => 'º£∏≥∏°∫∫π‡Ã‹',
);

class list_of_ctitem_categories extends list_of_simple_objects {
  function list_of_ctitem_categories($prefix, $cfg=NULL) {
    global $_lib_u_manage_ctitem_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_ctitem_category_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class ctitem_category_display extends simple_object_display {
  function ctitem_category_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_ctitem_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_ctitem_category_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class ctitem_category_edit extends simple_object_edit {
  function ctitem_category_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_ctitem_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_ctitem_category_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
