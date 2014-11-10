<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_therapist_norder_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '��Ͻ����',
      'ALLOW_SORT' => array
      ('����' => array('����' => 'X."������"')),
      'DEFAULT_SORT' => '����',
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => '����ID'),
		       '����', '������', '��', '��', '��'),

      'UNIQ_ID' => 'X."ObjectID"',
      ));
  
  $stmt_head = '
SELECT X."ObjectID",
       X."������" as "����",
       (DR."��" || DR."̾") as "������",
       CASE WHEN X."������ˡ" = \'on\' THEN \'��\' ELSE \'��\' END as "��",
       CASE WHEN X."�����ˡ" = \'on\' THEN \'��\' ELSE \'��\' END as "��",
       CASE WHEN X."����İ����ˡ" = \'on\' THEN \'��\' ELSE \'��\' END as "��"
FROM "��Ͻ����" as X
LEFT JOIN "������Ģ" as DR ON DR."ObjectID" = X. "���"
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';
}

class list_of_rehab_norders extends list_of_ppa_objects {

  var $default_row_per_page = 4;
  var $debug = 1;

  function list_of_rehab_norders($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_therapist_norder_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '����') ? 1 : 0);
    }
    return $paging_orders;
  }

}
?>
