<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_test_testmaster2_cfg(&$cfg) {
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => 'test_master0',
		  'COLS' => array('Category','Name'),
		  'LCOLS' => array(array('Column' => 'Name',
					 'Label' => '¸¡ºº¹àÌÜ')),
		  'LIST_IDS' => array('ObjectID', 'Name'),
		  'MULTI_COLS' => 3,
		  'ROW_PER_PAGE' => 60,
		  ));
}

class list_of_testmaster2 extends list_of_simple_objects {

	function list_of_testmaster2($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_test_testmaster2_cfg(&$cfg);
                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
		' AND "Åö±¡ºÎÍÑ" = \'Y\' AND "Parent" is NULL AND "Category"=' . mx_db_sql_quote($this->CID)
			);
	}

}

?>
