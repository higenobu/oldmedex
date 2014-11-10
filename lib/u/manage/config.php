<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_medex_config_cfg = array
(
 'COLS' => array('item_name', 'item_value'),
 'DCOLS' => array(array(Label => "名称",
		        Column => 'item_name',
		        Draw => 'static'),
		  array(Label => "変数値",
		        Column => 'item_value',
		        Draw => 'text')),
 'TABLE' => 'bmd_config',
 'ALLOW_SORT' => array('item_name' => array('item_name' => '"item_name"')),
);
$_lib_u_manage_medex_config_cfg['ECOLS'] =
	$_lib_u_manage_medex_config_cfg['LCOLS'] =
	$_lib_u_manage_medex_config_cfg['DCOLS'];

class list_of_medex_configs extends list_of_simple_objects {
	var $debug = 1;
  function list_of_medex_configs($prefix, $cfg=NULL) {
    global $_lib_u_manage_medex_config_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_medex_config_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class medex_config_display extends simple_object_display {
  function medex_config_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_medex_config_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_medex_config_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class medex_config_edit extends simple_object_edit {
  function medex_config_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_medex_config_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_medex_config_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
