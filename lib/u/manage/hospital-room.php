<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/hospital-ward.php';

$_lib_u_manage_hospital_room_cfg = array
(
 'TABLE' => '�¼�����ɽ',
 'COLS' => array("�¼�̾", "����̾", "����", "����", "���"),
 'HSTMT' => ('SELECT R."ObjectID", R."CreatedBy", '.
	     'R."�¼�̾", W."����̾", R."����", R."����", R."���" ' .
	     'FROM "�¼�����ɽ" AS R LEFT JOIN "�������ɽ" AS W ON ' .
	     'W."ObjectID" = R."����" AND W."Superseded" IS NULL ' .
	     'WHERE (NULL IS NULL) '),
 'UNIQ_ID' => 'R."ObjectID"',
 'LCOLS' => array("�¼�̾", "����̾",
		  array('Column' => "����", 'Draw' => 'enum',
			'Enum' => array('M' => '��', 'F' => '��',
					NULL => '̵����')),
		  "���"),
 'ALLOW_SORT' => 1,
 'ECOLS' => array("�¼�̾",
		  array('Column' => '����', 'Draw' => NULL),
		  array('Column' => '����̾',
			'Draw' => 'subpick',
			'Subpick' => array
			('Class' => 'list_of_hospital_wards',
			 'Message' => '������������ꤹ��',
			 'Config' => $_lib_u_manage_hospital_ward_cfg,
			 'ListID' => array('ObjectID', '����̾'),
			 'Allow_NULL' => 0,
			 'ObjectColumn' => '����',
			 ),
			),
		  array('Column' => "����", 'Draw' => 'enum',
			'Enum' => array('M' => '��', 'F' => '��',
					NULL => '̵����')),
		  "���"),
 'ICOLS' => array("�¼�̾", "����", "����", "���"),
);
$_lib_u_manage_hospital_room_cfg['DCOLS'] =
    $_lib_u_manage_hospital_room_cfg['LCOLS'];
$_lib_u_manage_hospital_room_cfg['DCOLS'][] = array
('Column' => 'CreatedBy',
 'Label' => '��Ͽ��',
 'Draw' => 'user');
$_lib_u_manage_hospital_room_cfg['STMT'] =
  $_lib_u_manage_hospital_room_cfg['HSTMT'] . ' AND (R."Superseded" IS NULL)';

class list_of_hospital_rooms extends list_of_simple_objects {
  function list_of_hospital_rooms($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_room_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_room_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class hospital_room_display extends simple_object_display {
  function hospital_room_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_room_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_room_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class hospital_room_edit extends simple_object_edit {
  function hospital_room_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_hospital_room_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_hospital_room_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function annotate_form_data(&$data) {
    foreach (array('����') as $col) {
      if (trim($data[$col]) == '') $data[$col] = NULL;
    }
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    if ($this->_validate() != 'ok')
      return 'failure';

    $orig_id = $this->id;
    if (($ok = simple_object_edit::try_commit(&$db)) != 'ok')
      return $ok;
    if (! $orig_id) {
      // We created a room.  Need to create the associated
      // Room-Patient hook, if it does not exist.
      $stmt = 'SELECT "ObjectID" FROM "�¼�����ɽ" WHERE
               "Superseded" IS NULL AND "�¼�" = ' . $this->id;
      $curr = mx_db_fetch_single($db, $stmt);
      $stmt = 'INSERT INTO "�¼�����ɽ" ("�¼�", "CreatedBy") VALUES (' .
	$this->id . ', ' .
	mx_db_sql_quote($mx_authenticate_current_user) . ')';
      if (! $curr && ! is_null($curr) && pg_query($db, $stmt))
	; // All is well.
      else
	return 'failure';
    }
    return 'ok';
  }

	function _validate() {
		$errs = 0;
		if ($st = mx_db_validate_posint($this->data['���'])) {
			$this->err("(���): $st\n");
			$errs++;
		}
		if (!$errs)
			return 'ok';
	}

}

?>
