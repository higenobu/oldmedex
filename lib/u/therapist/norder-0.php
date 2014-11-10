<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_therapist_norder_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'リハ処方箋',
      'ALLOW_SORT' => array
      ('日付' => array('日付' => 'X."処方日"')),
      'DEFAULT_SORT' => '日付',
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => 'リハ箋ID'),
		       '日付', '処方医', '理', '作', '言'),

      'UNIQ_ID' => 'X."ObjectID"',
      ));
  
  $stmt_head = '
SELECT X."ObjectID",
       X."処方日" as "日付",
       (DR."姓" || DR."名") as "処方医",
       CASE WHEN X."理学療法" = \'on\' THEN \'○\' ELSE \'×\' END as "理",
       CASE WHEN X."作業療法" = \'on\' THEN \'○\' ELSE \'×\' END as "作",
       CASE WHEN X."言語聴覚療法" = \'on\' THEN \'○\' ELSE \'×\' END as "言"
FROM "リハ処方箋" as X
LEFT JOIN "職員台帳" as DR ON DR."ObjectID" = X. "医者"
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
      $paging_orders[] = (($col == '日付') ? 1 : 0);
    }
    return $paging_orders;
  }

}
?>
