<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_rxenum_usage_cfg = array
(
 'COLS' => array("投与形態"),
 'TABLE' => '処方箋投与形態',
);

class list_of_rxenum_usages extends list_of_simple_objects {
  function list_of_rxenum_usages($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_usage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_usage_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class rxenum_usage_display extends simple_object_display {
  function rxenum_usage_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_usage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_usage_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class rxenum_usage_edit extends simple_object_edit {
  function rxenum_usage_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_usage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_usage_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_rxenum_dosage_cfg = array
(
 'COLS' => array("用法", "一日当り回数",'頓服',"病院使用レセコン用法コード",
		 'sortorder', 'type'),
 'LCOLS' => array("用法", "一日当り回数",
		  array('Column' => 'type',
			'Label' => '区分',
			'Enum' => array(0 => '',
					1 => '内服',
					2 => '外用',
					4 => '注射',
					),
			'Draw' => 'enum',
			),
		  '頓服',"病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'DCOLS' => array("用法", "一日当り回数",
		  array('Column' => 'type',
			'Label' => '区分',
			'Enum' => array(0 => '',
					1 => '内服',
					2 => '外用',
					4 => '注射',
					),
			'Draw' => 'enum',
			),
			'頓服',"病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'ECOLS' => array("用法", "一日当り回数",
		  array('Column' => 'type',
			'Label' => '区分',
			'Enum' => array(0 => '',
					1 => '内服',
					2 => '外用',
					4 => '注射',
					),
			'Draw' => 'enum',
			),
			'頓服',"病院使用レセコン用法コード",
		  array('Column' => 'sortorder',
			'Label' => '表示順')),
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'sortorder',
 'TABLE' => '処方箋用法',
);

class list_of_rxenum_dosages extends list_of_simple_objects {
  function list_of_rxenum_dosages($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_dosage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_dosage_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class rxenum_dosage_display extends simple_object_display {
  function rxenum_dosage_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_dosage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_dosage_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class rxenum_dosage_edit extends simple_object_edit {
  function rxenum_dosage_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_dosage_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_dosage_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_rxenum_unit_cfg = array
(
 'COLS' => array("用量単位"),
 'TABLE' => '処方箋用量単位',
);

class list_of_rxenum_units extends list_of_simple_objects {
  function list_of_rxenum_units($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_unit_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_unit_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class rxenum_unit_display extends simple_object_display {
  function rxenum_unit_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_unit_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_unit_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class rxenum_unit_edit extends simple_object_edit {
  function rxenum_unit_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_rxenum_unit_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_rxenum_unit_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}

?>
