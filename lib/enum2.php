<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// This module builds enum accessing functions from $_lib_enum_data.
// If you want an SQL fragment to limit the query on employee list to
// just doctors, for example, use enum_doctor_cat_sql(), which returns
// "('��ʰ�', '���ʰ�', '����ʰ�','��ϰ�'" as a string, so you can
// say something like this:
//  $stmt = 'SELECT * FROM ... JOIN "�������ɽ" as C WHERE C."����" in ' .
//      enum_doctor_cat_sql() . ' AND ...';
//
// Available functions (can be added by editing _lib_enum__ee()):
// enum_$what_sql()   -- string suitable for "IN ..." clause in SQL
// enum_$what_array() -- raw array suitable for programs.
//
// Available enums (can be added by editing $_lib_enum_data):
// therapist_cat -- employee categories for therapists
// doctor_cat, nurse_cat, pharmacist_cat, nutritionist_cat -- ditto.

$_lib_enum_data = array
(
 // This list must be kept in sync with �������ɽ database table.
 'therapist_cat' => array('������ˡ��', '�����ˡ��', '����İ�л�'),
 'therapist_pt_cat' => array('������ˡ��'),
 'therapist_ot_cat' => array('�����ˡ��'),
 'therapist_st_cat' => array('����İ�л�'),
 'doctor_cat' => array('��ʰ�', '���ʰ�', '���ʰ�','��ϰ�', '�����ʰ�', '���'),
 'nurse_cat' => array('�Ǹ��', '�ڴǸ��'),
 'pharmacist_cat' => array('���޻�'),
 'nutritionist_cat' => array('�������ܻ�', '���ܻ�'),
 'msw_cat' => array('�Ҳ�ʡ���', '�����ݷ�ʡ���'),
 'pp_cat' => array('���ʱ�����'),

 // There may be other enum data defined later for other tables.

 );

function _lib_enum__ee($what, $type) {
  global $_lib_enum_data;
  $what = $_lib_enum_data[$what];
  switch ($type) {
  case 'array':
    return $what;
  case 'sql':
    $ll = array();
    foreach ($what as $d) {
      $ll[] = mx_db_sql_quote($d);
    }
    return '(' . implode(', ', $ll) . ')';
  }
}
// 0408-2011 debug

foreach ($_lib_enum_data as $what => $ll) {
  $func = '
function enum_' . $what . '_sql() {
  return _lib_enum__ee("' . $what . '", "sql");
}

function enum_' . $what . '_array() {
  return _lib_enum__ee("' . $what . '", "array");
}

function enum_' . $what . '_list() {
	$cat = enum_' . $what . '_sql();
	$stmt = <<<SQL
SELECT E."��", E."̾", (E."��" || \' \' || E."̾") AS "��̾"
FROM "������Ģ" AS E

SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array("" => "");
	if ($them) {
		foreach ($them as $e)
			$result[$e["ObjectID"]] = $e["��̾"];
	}
	return $result;
}
';
  eval("$func");
}

?>
