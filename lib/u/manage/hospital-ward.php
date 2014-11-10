<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/department.php';

$_lib_u_manage_hospital_ward_cfg = array
(
 'TABLE' => '病棟一覧表',
 'COLS' => array("病棟名", "部署名"),
 'HSTMT' => ('SELECT W."ObjectID", W."CreatedBy", W."病棟名", W."部署",
              D."大分類", D."中分類1", D."中分類2", D."小分類"
              FROM "病棟一覧表" AS W JOIN "部署一覧表" AS D
              ON W."部署" = D."ObjectID" and D."Superseded" IS NULL
              WHERE (NULL IS NULL)'),
 'UNIQ_ID' => 'W."ObjectID"',
 'STMT' => ('SELECT W."ObjectID", W."CreatedBy", W."病棟名", W."部署",
              D."大分類", D."中分類1", D."中分類2", D."小分類"
              FROM "病棟一覧表" AS W JOIN "部署一覧表" AS D
              ON W."部署" = D."ObjectID" and D."Superseded" IS NULL
              WHERE W."Superseded" IS NULL'),
 'ALLOW_SORT' => 0, // we cannot sort on 部署名
 'ECOLS' => array("病棟名",
		  array('Column' => '部署', 'Draw' => NULL),
		  array('Column' => '部署名',
			'Draw' => 'subpick',
			'Subpick' => array
			('Class' => 'list_of_departments',
			 'Message' => 'この部署に設定する',
			 'Config' => $_lib_u_manage_department_cfg,
			 'ListID' => array('ObjectID',
					   '部署',
					   ),
			 'Allow_NULL' => 0,
			 'ObjectColumn' => '部署',
			 ),
			)),
 'ICOLS' => array("病棟名", "部署"),
);

class list_of_hospital_wards extends list_of_simple_objects {

  function list_of_hospital_wards($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_ward_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_ward_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function annotate_row_data(&$row) {
    $row['部署名'] = _lib_u_manage_department_abbrev($row);
  }

}

class hospital_ward_display extends simple_object_display {
  function hospital_ward_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_ward_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_ward_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  function annotate_row_data(&$row) {
    $row['部署名'] = _lib_u_manage_department_abbrev($row);
  }
}

class hospital_ward_edit extends simple_object_edit {
  function hospital_ward_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_ward_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_ward_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
  function annotate_row_data(&$row) {
    $row['部署名'] = _lib_u_manage_department_abbrev($row);
  }
}

?>
