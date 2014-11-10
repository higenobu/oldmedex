<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
//0510-2013 update fro hayashi
function __lib_u_pharmacy_direction_cfg(&$cfg) {
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => '処方箋用法',
		  'COLS' => array('用法','sortorder','頓服', "一日当り回数"
				  ),
		  'LCOLS' => array('用法',
				  ),
		  
		  'ALLOW_SORT' => array('sortorder' =>
					array ('sortorder' => 'sortorder')),
		  'DEFAULT_SORT' => 'sortorder',
		  'LIST_IDS' => array('ObjectID','頓服','用法'),
                  'ROW_PER_PAGE' => 4,
		  'COMPUTE_NOLINK' => 'is_separator'
		  ));
}

class list_of_pharmacy_directions extends list_of_simple_objects {

	function list_of_pharmacy_directions($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_pharmacy_direction_cfg(&$cfg);

                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
//0508-2013 hayashi
	function base_fetch_stmt_0() {
	  $stmt = list_of_simple_objects::base_fetch_stmt_0() .
	    ' AND (sortorder is null or sortorder > 0)  AND use=1';
	  if ($this->so_config['MED_TYPE'])
	    $stmt .= sprintf(" AND (type=0 OR type=%d)", $this->so_config['MED_TYPE']);
	  return $stmt;
	}

	function is_separator(&$row) {
	  return $row["一日当り回数"] < 0;
	}
}

?>
