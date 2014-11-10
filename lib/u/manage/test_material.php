<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';


function __lib_u_manage_test_material_cfg(&$cfg) { 
  $cfg = array_merge
    (
     $cfg, array
     (
      'TABLE' => "test_material",
      'COLS' => array("Name", "Color"),
      'LCOLS' => array(array('Column' => 'Name',
			     'Materialel' => 'ºàÎÁÌ¾',
			     ),
		       array('Column' => 'Color',
			     'Materialel' => 'É½¼¨¿§'
			     ),
		       ),
      'DCOLS' => array(array('Column' => 'Name',
			     'Materialel' => 'ºàÎÁÌ¾',
			     ),
		       array('Column' => 'Color',
			     'Materialel' => 'É½¼¨¿§'
			     ),
		       ),
      'ECOLS' => array(array('Column' => 'Name',
			     'Materialel' => 'ºàÎÁÌ¾',
			     ),
		       array('Column' => 'Color',
			     'Materialel' => 'É½¼¨¿§'
			     ),
		       ),
      )
     );
}

class list_of_manage_test_materials extends list_of_simple_objects {
  function list_of_manage_test_materials($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_manage_test_material_cfg(&$cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class manage_test_material_display extends simple_object_display {
  function manage_test_material_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_manage_test_material_cfg(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class manage_test_material_edit extends simple_object_edit {
  function manage_test_material_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_manage_test_material_cfg(&$cfg);
    simple_object_edit::simple_object_edit($prefix, &$cfg);
  }
}


class manage_test_material_application extends single_table_application {
  function manage_test_material_application() {
    global $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    single_table_application::single_table_application();
  }

  function list_of_objects($prefix) {
    return new list_of_manage_test_materials($prefix);
  }

  function object_display($prefix) {
    return new manage_test_material_display($prefix);
  }

  function object_edit($prefix) {
    return new manage_test_material_edit($prefix);
  }
}

?>
