<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/multi-select-list.php';

$_lib_u_manage_patient_pick_cfg = array
(
 'COLS' => array('����ID', '����̾'),
 'TABLE' => '������Ģ',
 'LCHOICE' => array(0 => '�������Ԥ�������',
		    1 => '�����Ԥ�������'),
 'LIST_IDS' => array('ObjectID', '����ID', '����̾'),
 'SHOW_IDS' => array('����̾'),
 'LCOLS' => array('����', '����ID', '����̾'),
 'ALLOW_SORT' => array('����ID' => array('����ID' => '"����ID"'),
		       '����̾' => array('����̾' => '("��"||"̾")')),
 'ENABLE_QBE' => array(array('Column' => '����ID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		       array('Column' => '����̾',
			     'Compare' => '("��"||"̾")'),
		       ),
 );

class room_patient_pick extends multi_select_list {

  var $base_select_stmt = 'SELECT "ObjectID", "CreatedBy", "����ID",
			  ("��" || \' \' || "̾") AS "����̾"
			  FROM "������Ģ"
			  WHERE "Superseded" IS NULL';

  function room_patient_pick($prefix, $config=NULL) {
    global $_lib_u_manage_patient_pick_cfg;
    if (is_null($config))
      $config = $_lib_u_manage_patient_pick_cfg;
    multi_select_list::multi_select_list
      ($prefix, $config);
  }

  function base_fetch_stmt_1($ix) {
    if ($ix == 1)
      return $this->base_select_stmt;
    return ($this->base_select_stmt . ' AND "������ʬ" = \'I\'');
  }

  function annotate_row_data(&$row) {
    $row = array_map('mx_trim', $row);
    multi_select_list::annotate_row_data($row);
  }
}
?>
