<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_procedure_proceduremaster2_cfg(&$cfg) {
  global $__uiconfig_dismod_kbd;
  $stmt = 'SELECT "ObjectID", "Name" from procedure_category WHERE "Superseded" IS NULL ORDER BY "SortOrder"';
  $db = mx_db_connect();
  $sth = pg_query($db, $stmt);
  $rs = pg_fetch_all($sth);
  $procedure_category_choice = array(NULL => '選択してください');
  $procedure_category = array();
  foreach($rs as $r) {
    $procedure_category[$r['ObjectID']] = $r['Name'];
    $procedure_category_choice["=" . $r['ObjectID']] = $r['Name'];
  }

  $cfg = array_merge
    ($cfg,
     array
     ('TABLE' => 'procedure_master',
      'COLS' => array('Category','Name', 'Search'),
      'LCOLS' => array(array('Column' => 'Category',
			     'Label' => 'カテゴリー',
			     'Draw' => 'enum',
			     'Enum' => $procedure_category
			     ),
		       array('Column' => 'Name',
			     'Label' => '処置項目'),
		       ),
      'ENABLE_QBE' => array( array('Column' => 'カテゴリー',
				   'Compare' => '"Category"',
				   'Draw' => 'enum',
				   'Enum' => $procedure_category_choice,
				   'Singleton' => 1,
				   ),
			     
			     array('Column' => '処置項目検索',
				   'Compare' => '"Search"',
				   'IncSearch' => 'procedure',
				   'NormalizeCompareKey' => 'AC',
				   )
			     ),
      # DEFAULT_QBE is needed to show text input box for ajax search
      'DEFAULT_QBE' => array(), 
      'LIST_IDS' => array('ObjectID', 'Name'),
      'MULTI_COLS' => 1,
      'ROW_PER_PAGE' => 48,
      )
     );
}

class list_of_proceduremaster2 extends list_of_simple_objects {

	function list_of_proceduremaster2($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_procedure_proceduremaster2_cfg(&$cfg);
                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
		       	' AND "当院採用" = \'Y\' '
			);
	}
}

?>
