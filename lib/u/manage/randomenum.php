<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_randomenum_cfg = array
(
 'TABLE' => "Îóµó·¿",
 'COLS' => array("¥°¥ë¡¼¥×", "Ì¾¾Î", "ÁªÂò»è", "Multi"),
 'LCOLS' => array("¥°¥ë¡¼¥×", "Ì¾¾Î", "ÁªÂò»è"),
 'DCOLS' => array("¥°¥ë¡¼¥×", "Ì¾¾Î",
		  array('Column' => "ÁªÂò»è",
			'Draw' => 'textarea',
			'Option' => array('rows' => '10')),
		  array('Column' => 'Multi',
			'Label' => 'Ê£¿ôÁªÂò',
			'Draw' => 'static')),
);
$_lib_u_manage_randomenum_cfg['ECOLS'] =
$_lib_u_manage_randomenum_cfg['DCOLS'];

class list_of_randomenums extends list_of_simple_objects {
  function list_of_randomenums($prefix, $cfg=NULL) {
    global $_lib_u_manage_randomenum_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_randomenum_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class randomenum_display extends simple_object_display {
  function randomenum_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_randomenum_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_randomenum_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class randomenum_edit extends simple_object_edit {
  function randomenum_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_randomenum_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_randomenum_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
