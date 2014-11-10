<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_procedure_category_cfg(&$cfg) {
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => 'procedure_category',
		  'COLS' => array('Name','SortOrder',
				  ),
		  'LCOLS' => array(array('Column' => 'Name',
					 'Label' => 'カテゴリー'
					 )
				  ),
		  
		  'ALLOW_SORT' => array('SortOrder' =>
					array ('SortOrder' => '"SortOrder"')),
		  'DEFAULT_SORT' => '"SortOrder"',
		  'LIST_IDS' => array('ObjectID',),
                  'ROW_PER_PAGE' => 10,
		  'SCROLLABLE_HEIGHT' => '159px'
		  ));
}

function _lib_u_procedure_category_fetch_data($it, $oid) {
  

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_procedure_order_cfg['HSTMT'] .
	   'AND O."ObjectID" = ' . mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Main: $stmt;\n");
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];

  // Fetch from subtables.
  $stmt = 'SELECT G."Name" AS "Category",
                  C."ProcedureID" as "ProcedureID",
                  M."Name" AS "ProcedureName"
           FROM "procedure_order_content" AS C,
                "procedure_master" AS M, "procedure_category" AS G
           WHERE M."ObjectID" = C."ProcedureID" AND
                 G."ObjectID" = M."Category" AND
                 M."Superseded" IS NULL AND
                 G."Superseded" IS NULL AND
                 G."ObjectID" = ' . mx_db_sql_quote($oid);
  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['Procedure'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach ($d as $row)
      $data['Procedure'][] = array_map('mx_trim', $row);
  }
  return $data;
}

class list_of_procedure_categories extends list_of_simple_objects {
  var $debug =1;
	function list_of_procedure_categories($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_procedure_category_cfg(&$cfg);

                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
			' AND ("SortOrder" IS NULL OR "SortOrder" > 0) '
			);
	}

}

?>
