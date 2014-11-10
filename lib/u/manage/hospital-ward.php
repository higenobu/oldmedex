<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/department.php';

$_lib_u_manage_hospital_ward_cfg = array
(
 'TABLE' => '�������ɽ',
 'COLS' => array("����̾", "����̾"),
 'HSTMT' => ('SELECT W."ObjectID", W."CreatedBy", W."����̾", W."����",
              D."��ʬ��", D."��ʬ��1", D."��ʬ��2", D."��ʬ��"
              FROM "�������ɽ" AS W JOIN "�������ɽ" AS D
              ON W."����" = D."ObjectID" and D."Superseded" IS NULL
              WHERE (NULL IS NULL)'),
 'UNIQ_ID' => 'W."ObjectID"',
 'STMT' => ('SELECT W."ObjectID", W."CreatedBy", W."����̾", W."����",
              D."��ʬ��", D."��ʬ��1", D."��ʬ��2", D."��ʬ��"
              FROM "�������ɽ" AS W JOIN "�������ɽ" AS D
              ON W."����" = D."ObjectID" and D."Superseded" IS NULL
              WHERE W."Superseded" IS NULL'),
 'ALLOW_SORT' => 0, // we cannot sort on ����̾
 'ECOLS' => array("����̾",
		  array('Column' => '����', 'Draw' => NULL),
		  array('Column' => '����̾',
			'Draw' => 'subpick',
			'Subpick' => array
			('Class' => 'list_of_departments',
			 'Message' => '������������ꤹ��',
			 'Config' => $_lib_u_manage_department_cfg,
			 'ListID' => array('ObjectID',
					   '����',
					   ),
			 'Allow_NULL' => 0,
			 'ObjectColumn' => '����',
			 ),
			)),
 'ICOLS' => array("����̾", "����"),
);

class list_of_hospital_wards extends list_of_simple_objects {

  function list_of_hospital_wards($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_ward_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_ward_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function annotate_row_data(&$row) {
    $row['����̾'] = _lib_u_manage_department_abbrev($row);
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
    $row['����̾'] = _lib_u_manage_department_abbrev($row);
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
    $row['����̾'] = _lib_u_manage_department_abbrev($row);
  }
}

?>
