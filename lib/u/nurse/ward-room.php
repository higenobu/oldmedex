<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_ward_room_cfg = array
(
 'COLS' => array('病室名', '病棟名', '性別', '定数'),
 'TABLE' => '病室一覧表',
 'LIST_IDS' => array('ObjectID', '病室名'),
 'LCOLS' => array('病室名', '病棟名',
		  array('Column' => '性別',
			'Draw' => 'enum',
			'Enum' => array('M' => '男', 'F' => '女',
					NULL => '不特定')),
		  '定数'),
 'LCHOICE' => array(0 => '病棟病室から選ぶ', 1 => '全病室から選ぶ'),
 'ALLOW_SORT' => 1,
 'UNIQ_ID' => 'R."ObjectID"',
 'ENABLE_QBE' => array('病室名', '病棟名', '定数',
		       array('Column' => '性別',
			     'Draw' => 'enum',
			     'Enum' => array('M' => '男', 'F' => '女',
					     NULL => '不特定'),
			     )),
 );

class list_of_ward_rooms extends list_of_simple_objects {
  var $base_select_stmt = '
SELECT R."ObjectID", R."病室名", R."病棟", W."病棟名", R."性別", R."定数"
FROM "病室一覧表" AS R
JOIN "病棟一覧表" AS W
ON R."病棟" = W."ObjectID" AND W."Superseded" IS NULL
WHERE (NULL IS NULL)';

  function list_of_ward_rooms($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_room_cfg;
    if (is_null($config))
      $config = $_lib_u_nurse_ward_room_cfg;
    $this->ward = $config['Ward'];
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function base_fetch_stmt_1($ix) {
    if ($ix == 1)
      return $this->base_select_stmt;
    return ($this->base_select_stmt . ' AND  R."病棟" = ' .
	    mx_db_sql_quote($this->ward));
  }

  function draw() {
    mx_titlespan($this->Title);
    list_of_simple_objects::draw();
    mx_formi_submit($this->prefix . 'id-select', $this->Original,
		    "<span class=\"link\">変更しない</span>");
  }

}
?>
