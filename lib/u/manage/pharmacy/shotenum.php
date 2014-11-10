<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_shotenum_method_cfg = array
(
 'COLS' => array("手技", '精密', "病院使用レセコン用法コード", 'sortorder'),
 'LCOLS' => array("手技", '精密',"病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'DCOLS' => array("手技", '精密',"病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'ECOLS' => array("手技", '精密',"病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'sortorder',
 'TABLE' => '注射手技',
);

class list_of_shotenum_methods extends list_of_simple_objects {
  function list_of_shotenum_methods($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_shotenum_method_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_shotenum_method_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class shotenum_method_display extends simple_object_display {
  function shotenum_method_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_shotenum_method_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_shotenum_method_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class shotenum_method_edit extends simple_object_edit {
  function shotenum_method_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_shotenum_method_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_shotenum_method_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_shotenum_dosage_cfg = array
(
 'COLS' => array("用法", 'sortorder'),
 'LCOLS' => array("用法","病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'DCOLS' => array("用法", "病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'ECOLS' => array("用法", "病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'sortorder',
 'TABLE' => '注射用法',
 );

class list_of_shotenum_dosages extends list_of_simple_objects {
  function list_of_shotenum_dosages($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_shotenum_dosage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_shotenum_dosage_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class shotenum_dosage_display extends simple_object_display {
  function shotenum_dosage_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_shotenum_dosage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_shotenum_dosage_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class shotenum_dosage_edit extends simple_object_edit {
  function shotenum_dosage_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_shotenum_dosage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_shotenum_dosage_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}

?>
