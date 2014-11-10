<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_rxenum_usage_cfg = array
(
 'COLS' => array("��Ϳ����"),
 'TABLE' => '�������Ϳ����',
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
 'COLS' => array("��ˡ", "����������",'����',"�±����ѥ쥻������ˡ������",
		 'sortorder', 'type'),
 'LCOLS' => array("��ˡ", "����������",
		  array('Column' => 'type',
			'Label' => '��ʬ',
			'Enum' => array(0 => '',
					1 => '����',
					2 => '����',
					4 => '���',
					),
			'Draw' => 'enum',
			),
		  '����',"�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'DCOLS' => array("��ˡ", "����������",
		  array('Column' => 'type',
			'Label' => '��ʬ',
			'Enum' => array(0 => '',
					1 => '����',
					2 => '����',
					4 => '���',
					),
			'Draw' => 'enum',
			),
			'����',"�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'ECOLS' => array("��ˡ", "����������",
		  array('Column' => 'type',
			'Label' => '��ʬ',
			'Enum' => array(0 => '',
					1 => '����',
					2 => '����',
					4 => '���',
					),
			'Draw' => 'enum',
			),
			'����',"�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'sortorder',
 'TABLE' => '�������ˡ',
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
 'COLS' => array("����ñ��"),
 'TABLE' => '���������ñ��',
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
