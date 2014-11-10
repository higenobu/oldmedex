<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_pharmacy_shotenum_method_cfg = array
(
 'COLS' => array("�굻", '��̩', "�±����ѥ쥻������ˡ������", 'sortorder'),
 'LCOLS' => array("�굻", '��̩',"�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'DCOLS' => array("�굻", '��̩',"�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'ECOLS' => array("�굻", '��̩',"�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'sortorder',
 'TABLE' => '��ͼ굻',
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
 'COLS' => array("��ˡ", 'sortorder'),
 'LCOLS' => array("��ˡ","�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'DCOLS' => array("��ˡ", "�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'ECOLS' => array("��ˡ", "�±����ѥ쥻������ˡ������",
		  array('Column' => 'sortorder',
			'Label' => 'ɽ����')),
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'sortorder',
 'TABLE' => '�����ˡ',
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
