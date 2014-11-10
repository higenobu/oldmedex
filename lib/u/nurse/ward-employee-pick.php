<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/multi-select-list.php';

$_lib_u_nurse_ward_employee_pick_cfg = array
(
 'COLS' => array('����ID', '��̾'),
 'TABLE' => '������Ģ',
 'LCHOICE' => array(0 => '���￦����������',
		    1 => '��������������'),
 'LIST_IDS' => array('ObjectID', '��̾'),
 'SHOW_IDS' => array('��̾'),
 'LCOLS' => array('����', '����ID', '��̾'),
 'ALLOW_SORT' => array('����ID' => array('����ID' => '"����ID"'),
		       '��̾' => array('��̾' => '("��"||"̾")')),
 'ENABLE_QBE' => array('����ID',
		       array('Column' => '��̾',
			     'Compare' => '("��"||"̾")'),
		       ),
 );

class ward_employee_pick extends multi_select_list {

  var $base_select_stmt = 'SELECT "ObjectID", "CreatedBy", "����ID",
			  ("��" || \' \' || "̾") AS "��̾"
			  FROM "������Ģ"
			  WHERE "Superseded" IS NULL';

  function ward_employee_pick($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_employee_pick_cfg;
    if (is_null($config))
      $config = $_lib_u_nurse_ward_employee_pick_cfg;
    $this->ward = $config['Ward'];
    multi_select_list::multi_select_list
      ($prefix, $config);
  }

  function base_fetch_stmt_1($ix) {
    if ($ix == 1)
      return $this->base_select_stmt;
    return ($this->base_select_stmt . ' AND ' .
	    '"����" IN (SELECT "����" FROM "�������ɽ"
                        WHERE "Superseded" IS NULL AND
                        "ObjectID" = ' . mx_db_sql_quote($this->ward) .
	    ')');
  }

}
?>
