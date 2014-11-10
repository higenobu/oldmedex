<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/multi-select-list.php';

$_lib_u_nurse_ward_employee_pick_cfg = array
(
 'COLS' => array('¿¦°÷ID', 'À«Ì¾'),
 'TABLE' => '¿¦°÷ÂæÄ¢',
 'LCHOICE' => array(0 => 'ÉÂÅï¿¦°÷¤«¤éÁª¤Ö',
		    1 => 'Á´¿¦°÷¤«¤éÁª¤Ö'),
 'LIST_IDS' => array('ObjectID', 'À«Ì¾'),
 'SHOW_IDS' => array('À«Ì¾'),
 'LCOLS' => array('ÁªÂò', '¿¦°÷ID', 'À«Ì¾'),
 'ALLOW_SORT' => array('¿¦°÷ID' => array('¿¦°÷ID' => '"¿¦°÷ID"'),
		       'À«Ì¾' => array('À«Ì¾' => '("À«"||"Ì¾")')),
 'ENABLE_QBE' => array('¿¦°÷ID',
		       array('Column' => 'À«Ì¾',
			     'Compare' => '("À«"||"Ì¾")'),
		       ),
 );

class ward_employee_pick extends multi_select_list {

  var $base_select_stmt = 'SELECT "ObjectID", "CreatedBy", "¿¦°÷ID",
			  ("À«" || \' \' || "Ì¾") AS "À«Ì¾"
			  FROM "¿¦°÷ÂæÄ¢"
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
	    '"Éô½ð" IN (SELECT "Éô½ð" FROM "ÉÂÅï°ìÍ÷É½"
                        WHERE "Superseded" IS NULL AND
                        "ObjectID" = ' . mx_db_sql_quote($this->ward) .
	    ')');
  }

}
?>
