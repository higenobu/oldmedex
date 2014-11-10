<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_procedureenum_category_cfg = array
(
 'COLS' => array("Name", "code_from", "code_to", "SortOrder"),
 'LCOLS'=> array(array('Column' => 'Name',
		       'Label' => 'カテゴリー'),
		 array('Column' => 'code_from',
		       'Label' => 'ICD9CMカテゴリー自(先頭2桁)'),
		 array('Column' => 'code_to',
		       'Label' => 'ICD9CMカテゴリー至(先頭2桁)'),
		 array('Column' => 'SortOrder',
		       'Label' => '表示順'),
		 ),
 'DCOLS' => array(array('Column' => 'Name',
			'Label' => 'カテゴリー'),
		  array('Column' => 'code_from',
			'Label' => 'ICD9CMカテゴリー自(先頭2桁)'),
		  array('Column' => 'code_to',
			'Label' => 'ICD9CMカテゴリー至(先頭2桁)'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順'),
		  ),
 'ECOLS' => array(array('Column' => 'Name',
			'Label' => 'カテゴリー'),
		  array('Column' => 'code_from',
			'Label' => 'ICD9CMカテゴリー自(先頭2桁)'),
		  array('Column' => 'code_to',
			'Label' => 'ICD9CMカテゴリー至(先頭2桁)'),
		  array('Column' => 'SortOrder',
			'Label' => '表示順'),
		  ),
 'TABLE' => 'procedure_category',
);

class list_of_procedureenum_categories extends list_of_simple_objects {
  function list_of_procedureenum_categories($prefix, $cfg=NULL) {
    global $_lib_u_manage_procedureenum_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_procedureenum_category_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class procedureenum_category_display extends simple_object_display {
  function procedureenum_category_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_procedureenum_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_procedureenum_category_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class procedureenum_category_edit extends simple_object_edit {
  function procedureenum_category_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_procedureenum_category_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_procedureenum_category_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>