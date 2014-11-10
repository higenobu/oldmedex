<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/multi-select-list.php';
//change test_master0-> test_master 0412-2014
$_lib_u_manage_testmaster_pick_cfg = array
(
 'COLS' => array('TestID', 'TestName', 'Color'),
 'TABLE' => 'test_master0',
 'LIST_IDS' => array('TestID', 'TestName'),
 //'SHOW_IDS' => array('TestName'),
 'LCOLS' => array('ÁªÂò',
		  array('Column' => 'TestName',
			'Label' => '¸¡ºº¹àÌÜ')
		  ),
 'UNIQ_ID' => 'm."ObjectID"',
 'MULTI_COLS' => 5,
 'INLINE_GROUP_HEADER' => 'Category',
 'Span' => 2,
 'ROW_PER_PAGE' => 100,
 'TEST_APP_TYPE' => 0,
 );

class testmaster_pick extends multi_select_list {
//change to test_master0  0315-2012 go back to test_master

  var $base_select_stmt = 'SELECT m."ObjectID" as "TestID",
                           m."Name" as "TestName", c."Name" as "Category",
                           mt."Color" as "Color",
                           c."SortOrder" as "SortOrder", m."SortOrder" as "mSortOrder"
			  FROM "test_master0" m left join test_material mt
                                on m."Material" = mt."ObjectID",
                                test_category c

			  WHERE m."Superseded" IS NULL and
                                c."Superseded" IS NULL and
                                mt."Superseded" IS NULL and
                                m."Åö±¡ºÎÍÑ" = \'Y\' and
                                m."Parent" is NULL and
                                m."DispCategory" = c."ObjectID"';

  function testmaster_pick($prefix, $config=NULL) {
    global $_lib_u_manage_testmaster_pick_cfg;
    if (is_null($config)){
      $config = $_lib_u_manage_testmaster_pick_cfg;
    }
    multi_select_list::multi_select_list
      ($prefix, $config);
  }

  function base_fetch_stmt_0() {
    $stmt = $this->base_select_stmt;
    if($this->so_config['TEST_APP_TYPE'] === 0)
      $stmt .= ' AND "Category" <> 9';
    else if($this->so_config['TEST_APP_TYPE'] == 1)
      $stmt .= ' AND "Category" = 9';
    return $stmt;
  }

  /*
  function base_fetch_stmt_1($ix) {
    if ($ix == 1)
      return $this->base_select_stmt;
    return ($this->base_select_stmt . '');
  }
  */

  function annotate_row_data(&$row) {
    $row = array_map('mx_trim', $row);
    multi_select_list::annotate_row_data($row);
  }

  function row_paging_keys() { return array('SortOrder', 'mSortOrder'); }
  function row_paging_aliases() { return array('c."SortOrder"',
  					       'm."SortOrder"'); }

  function row_decoration(&$row, $ix, $total) {
	  $color = mx_check_option('Color', $row);
	  if ($color)
		  return sprintf('style="background: %s"', $color);
	  else {
		  $row_class = ($ix % 2) ? 'o' : 'e';
		  return "class=\"$row_class\"";
	  }
  }

}
?>
