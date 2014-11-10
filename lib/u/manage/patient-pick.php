<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/multi-select-list.php';

$_lib_u_manage_patient_pick_cfg = array
(
 'COLS' => array('´µ¼ÔID', '´µ¼ÔÌ¾'),
 'TABLE' => '´µ¼ÔÂæÄ¢',
 'LCHOICE' => array(0 => 'Æþ±¡´µ¼Ô¤«¤éÁª¤Ö',
		    1 => 'Á´´µ¼Ô¤«¤éÁª¤Ö'),
 'LIST_IDS' => array('ObjectID', '´µ¼ÔID', '´µ¼ÔÌ¾'),
 'SHOW_IDS' => array('´µ¼ÔÌ¾'),
 'LCOLS' => array('ÁªÂò', '´µ¼ÔID', '´µ¼ÔÌ¾'),
 'ALLOW_SORT' => array('´µ¼ÔID' => array('´µ¼ÔID' => '"´µ¼ÔID"'),
		       '´µ¼ÔÌ¾' => array('´µ¼ÔÌ¾' => '("À«"||"Ì¾")')),
 'ENABLE_QBE' => array(array('Column' => '´µ¼ÔID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		       array('Column' => '´µ¼ÔÌ¾',
			     'Compare' => '("À«"||"Ì¾")'),
		       ),
 );

class room_patient_pick extends multi_select_list {

  var $base_select_stmt = 'SELECT "ObjectID", "CreatedBy", "´µ¼ÔID",
			  ("À«" || \' \' || "Ì¾") AS "´µ¼ÔÌ¾"
			  FROM "´µ¼ÔÂæÄ¢"
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
    return ($this->base_select_stmt . ' AND "Æþ³°¶èÊ¬" = \'I\'');
  }

  function annotate_row_data(&$row) {
    $row = array_map('mx_trim', $row);
    multi_select_list::annotate_row_data($row);
  }
}
?>
