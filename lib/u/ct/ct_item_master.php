<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';


function __lib_u_ct_item_master_cfg(&$cfg) { 
  $cfg = array_merge
    (
     $cfg, array
     (
      'TABLE' => "治験作業項目",
      'COLS' => array("Name", "Flag", "Code", "Interval"),
      'LCOLS' => array(array('Column' => 'Code',
			     'Label' => 'コード',
			     ),
		       array('Column' => 'Name',
			     'Label' => '作業項目名',
			     ),
		       array('Column' => 'Flag',
			     'Label' => '種別',
			     'Draw' => 'enum',
			     'Enum' => array(0 => '一般',
					     1 => 'バイタル等',
					     2 => '蓄尿',
					     3 => '採血',
					     4 => '蓄尿確認',
					     5 => '投薬',
					     6 => 'その他',
					     7 => '食事',
					     )
			     ),
		       array('Column' => 'Interval',
			     'Label' => 'インターバル',
			     ),
		       ),
      'DCOLS' => array(array('Column' => 'Code',
			     'Label' => 'コード',
			     ),
		       array('Column' => 'Name',
			     'Label' => '作業項目名',
			     ),
		       array('Column' => 'Flag',
			     'Label' => '種別',
			     'Draw' => 'enum',
			     'Enum' => array(0 => '一般',
					     1 => '投薬',
					     2 => '検査'
					     )
			     ),
		       array('Column' => 'Interval',
			     'Label' => 'インターバル',
			     ),
		       ),
      'ECOLS' => array(array('Column' => 'Code',
			     'Label' => 'コード',
			     ),
		       array('Column' => 'Name',
			     'Label' => '作業項目名',
			     ),
		       array('Column' => 'Flag',
			     'Label' => '種別',
			     'Draw' => 'enum',
			     'Enum' => array(0 => '一般',
					     1 => '投薬',
					     2 => '検査'
					     )
			     ),
		       array('Column' => 'Interval',
			     'Label' => 'インターバル',
			     ),
		       ),
      )
     );
}

class list_of_ct_item_masters extends list_of_simple_objects {
  function list_of_ct_item_masters($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_ct_item_master_cfg(&$cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class ct_item_master_display extends simple_object_display {
  function ct_item_master_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_ct_item_master_cfg(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class ct_item_master_edit extends simple_object_edit {
  function ct_item_master_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_ct_item_master_cfg(&$cfg);
    simple_object_edit::simple_object_edit($prefix, &$cfg);
  }
}


class ct_item_master_application extends single_table_application {
  function ct_item_master_application() {
    global $_mx_auto_sodsoe_setup;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    single_table_application::single_table_application();
  }

  function list_of_objects($prefix) {
    return new list_of_ct_item_masters($prefix);
  }

  function object_display($prefix) {
    return new ct_item_master_display($prefix);
  }

  function object_edit($prefix) {
    return new ct_item_master_edit($prefix);
  }
}

?>
