<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_test_set_cfg(&$cfg) {
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => 'test_set',
		  'COLS' => array('Name','SortOrder',
				  ),
		  'LCOLS' => array(array('Column' => 'Name',
					 'Label' => 'Set name'
					 )
				  ),
		  
		  'ALLOW_SORT' => array('SortOrder' =>
					array ('SortOrder' => '"SortOrder"')),
		  'DEFAULT_SORT' => '"SortOrder"',
		  'LIST_IDS' => array('ObjectID','Name'),
                  'ROW_PER_PAGE' => 100,
		  'SCROLLABLE_HEIGHT' => '159px'
		  ));
}

class list_of_test_sets extends list_of_simple_objects {
  var $debug =1;
	function list_of_test_sets($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_test_set_cfg(&$cfg);

                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
			" AND (\"SortOrder\" is null or \"SortOrder\" > 0) AND \"CT\" <> 'Y'"
			);
	}

}

?>
