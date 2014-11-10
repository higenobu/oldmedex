<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_testenum_category_cfg = array
(
 'COLS' => array("Name", "SortOrder"),
 'LCOLS' => array(array('Column' => 'Name',
			'Label' => 'カテゴリー'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順'),
		  ),
 'DCOLS' => array(array('Column' => 'Name',
			'Label' => 'カテゴリー'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順'),
		  ),
 'ECOLS' => array(array('Column' => 'Name',
			'Label' => 'カテゴリー'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順'),
		  ),
 'TABLE' => 'test_category',
);

class list_of_testenum_categories extends list_of_simple_objects {
  function list_of_testenum_categories($prefix, $cfg=NULL) {
    global $_lib_u_manage_testenum_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_testenum_category_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class testenum_category_display extends simple_object_display {
  function testenum_category_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_testenum_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_testenum_category_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class testenum_category_edit extends simple_object_edit {
  function testenum_category_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_testenum_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_testenum_category_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
