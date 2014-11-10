<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/multi-select-list.php';

$_lib_u_manage_proceduremaster_pick_cfg = array
(
 'COLS' => array('ProcedureID', 'ProcedureName'),
 'TABLE' => 'procedure_master',
 'LIST_IDS' => array('ProcedureID', 'ProcedureName'),
 'SHOW_IDS' => array('ProcedureName'),
 'LCOLS' => array('ÁªÂò',
		  array('Column' => 'ProcedureName',
			'Label' => '½èÃÖ¹àÌÜ')
		  ),
 'UNIQ_ID' => 'm."ObjectID"',
 'MULTI_COLS' => 1,
 'INLINE_GROUP_HEADER' => 'Category',
 'Span' => 2,
 'ROW_PER_PAGE' => 51,
 'ENABLE_QBE' => array( array('Column' => '½èÃÖ¹àÌÜ¸¡º÷',
			      'Compare' => '"Search"',
			      'IncSearch' => 'multi_select_procedure',

			      'NormalizeCompareKey' => 'AC',
			      'Singleton' => 1,
			      )
	 ),
 'Select' => array(),
 );

class proceduremaster_pick extends multi_select_list {
  var $base_select_stmt = 'SELECT m."ObjectID" as "ProcedureID",
                           m."Name" as "ProcedureName", c."Name" as "Category",
                           c."SortOrder" as "cSortOrder"
			  FROM "procedure_master" m,
                                procedure_category c
			  WHERE m."Superseded" IS NULL and
                                c."Superseded" IS NULL and
                                m."Category" = c."ObjectID"';

  function proceduremaster_pick($prefix, $config=NULL) {
    global $_lib_u_manage_proceduremaster_pick_cfg;
    if (is_null($config)){
      $config = $_lib_u_manage_proceduremaster_pick_cfg;
    }
    multi_select_list::multi_select_list
      ($prefix, $config);
  }

  function base_fetch_stmt_selected_only() {
    $oids = array();
    foreach($this->selected as $row) {
      $t = mx_form_unescape_key($row);
      $oids[] = $t[0];
    }
    if(count($oids) == 0) {
      # this should yield no rows
      return $this->base_select_stmt . ' AND m."ObjectID" is NULL';
    }

    $ext = implode(',', $oids);
    return $this->base_select_stmt . ' AND m."ObjectID" in (' . $ext . ')';
    
  }

  function base_fetch_stmt_0() {
    if ($this->so_config['SELECTED_ONLY'])
      return $this->base_fetch_stmt_selected_only();
    
    return $this->base_select_stmt;
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

  function row_paging_keys() { return array('ProcedureID'); }
  function row_paging_aliases() { return array('m."ObjectID"'); }
}
?>
