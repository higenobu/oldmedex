<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/hospital-ward.php';

$_lib_u_manage_hospital_room_cfg = array
(
 'TABLE' => '病室一覧表',
 'COLS' => array("病室名", "病棟名", "病棟", "性別", "定数"),
 'HSTMT' => ('SELECT R."ObjectID", R."CreatedBy", '.
	     'R."病室名", W."病棟名", R."病棟", R."性別", R."定数" ' .
	     'FROM "病室一覧表" AS R LEFT JOIN "病棟一覧表" AS W ON ' .
	     'W."ObjectID" = R."病棟" AND W."Superseded" IS NULL ' .
	     'WHERE (NULL IS NULL) '),
 'UNIQ_ID' => 'R."ObjectID"',
 'LCOLS' => array("病室名", "病棟名",
		  array('Column' => "性別", 'Draw' => 'enum',
			'Enum' => array('M' => '男', 'F' => '女',
					NULL => '無指定')),
		  "定数"),
 'ALLOW_SORT' => 1,
 'ECOLS' => array("病室名",
		  array('Column' => '病棟', 'Draw' => NULL),
		  array('Column' => '病棟名',
			'Draw' => 'subpick',
			'Subpick' => array
			('Class' => 'list_of_hospital_wards',
			 'Message' => 'この病棟に設定する',
			 'Config' => $_lib_u_manage_hospital_ward_cfg,
			 'ListID' => array('ObjectID', '病棟名'),
			 'Allow_NULL' => 0,
			 'ObjectColumn' => '病棟',
			 ),
			),
		  array('Column' => "性別", 'Draw' => 'enum',
			'Enum' => array('M' => '男', 'F' => '女',
					NULL => '無指定')),
		  "定数"),
 'ICOLS' => array("病室名", "病棟", "性別", "定数"),
);
$_lib_u_manage_hospital_room_cfg['DCOLS'] =
    $_lib_u_manage_hospital_room_cfg['LCOLS'];
$_lib_u_manage_hospital_room_cfg['DCOLS'][] = array
('Column' => 'CreatedBy',
 'Label' => '記録者',
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
    foreach (array('病棟') as $col) {
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
      $stmt = 'SELECT "ObjectID" FROM "病室患者表" WHERE
               "Superseded" IS NULL AND "病室" = ' . $this->id;
      $curr = mx_db_fetch_single($db, $stmt);
      $stmt = 'INSERT INTO "病室患者表" ("病室", "CreatedBy") VALUES (' .
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
		if ($st = mx_db_validate_posint($this->data['定数'])) {
			$this->err("(定数): $st\n");
			$errs++;
		}
		if (!$errs)
			return 'ok';
	}

}

?>
