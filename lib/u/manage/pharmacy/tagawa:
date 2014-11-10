<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/pharmacy/rxenum.php';

////////////////////////////////////////////////////////////////
function _lib_u_manage_pharmacy_medicine_cfg_setup() {
  global $_lib_u_manage_pharmacy_medicine_cfg;
  global $_lib_u_manage_pharmacy_rxenum_unit_cfg;
  global $__uiconfig_ms_qbe_enum_medicine;

  $choice =  $__uiconfig_ms_qbe_enum_medicine;
  unset($choice['']);
  unset($choice['U']);
  unset($choice['N']);

  $choice_edit = $choice; // copy of array
  foreach(array_keys($choice_edit) as $k)
    if(strlen($k) > 1)
      unset($choice_edit[$k]);

  $kubun_enum = array('内' => '内',
		      '外' => '外',
		'注' => '注' );
$label_enum = array('Y' => '要',
		      'N' => '不要');
  $cols = array(
		"基準番号",
		"処方用番号",
		"会社識別用番号",
		"調剤用番号",
		"物流用番号",
		"ＪＡＮコード",
		"薬価基準収載医薬品コード",
		"個別医薬品コード",
		"レセプト電算処理システムコード（１）",
		"レセプト電算処理システムコード（２）",
		"告示名称",
		"販売名",
		"レセプト電算処理システム医薬品名",
		"規格単位",
		"包装形態",
		"包装単位数",
		"包装単位単位",
		"包装総量数",
		"包装総量単位",
		"区分",
		"製造会社",
		"販売会社",
		"更新区分",
		"更新年月日",
		"当院採用",
		"病院使用医薬品名",
		"病院使用包装単位単位",
		"病院使用レセコンコード",
		"病院使用ラベル要印刷",
"kananame"
		);

  $dcols = array(
		array('Column' =>"基準番号",
		      'Draw' => 'static'
		      ),
		array('Column' =>"区分",
		      'Label' => "区分",
		      ),
		"薬価基準収載医薬品コード",
		
		"レセプト電算処理システム医薬品名",
		"包装単位単位",
		array('Column' => "当院採用",
		      'Label' => "当院採用",
		      'Draw' => 'enum',
		      'Enum' => $choice),
		array('Column' => "病院使用医薬品名",
		      'Label' => "病院使用検索名称"),
		array('Column' => "病院使用包装単位単位",
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'list_of_rxenum_units',
		       'Message' => 'この単位に設定する',
		       'Config' => $_lib_u_manage_pharmacy_rxenum_unit_cfg,
		       'ListID' => array('ObjectID','用量単位'),
		       'Allow_NULL' => 0,
		       )),

		"病院使用レセコンコード",
		array('Column' => "病院使用ラベル要印刷",
		      'Draw' => 'enum',
		      'Enum' => $label_enum,
		      ),
//1230-2012
array('Column' => "kananame",
		      'Label' => 'カタカナ名',
		     
		      ),
		);

  $ecols = array(
		array('Column' =>"基準番号",
		      'Draw' => 'static'
		      ),
		"薬価基準収載医薬品コード",
		"レセプト電算処理システム医薬品名",
		"包装単位単位",
		array('Column' => "当院採用",
		      'Label' => "当院採用",
		      'Draw' => 'enum',
		      'Enum' => $choice_edit),
		array('Column' => "病院使用医薬品名",
		      'Label' => "病院使用検索名称"),
		array('Column' => "病院使用包装単位単位",
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'list_of_rxenum_units',
		       'Message' => 'この単位に設定する',
		       'Config' => $_lib_u_manage_pharmacy_rxenum_unit_cfg,
		       'ListID' => array('ObjectID','用量単位'),
		       'Allow_NULL' => 0,
		       )),
		array('Column' => "区分",
		      'Draw' => 'enum',
		      'Enum' => $kubun_enum
		      ),
		"病院使用レセコンコード",
		array('Column' => "病院使用ラベル要印刷",
		      'Draw' => 'enum',
		      'Enum' => $label_enum,
		      ),
//1230-2012
array('Column' => "kananame",
		      'Label' => 'カタカナ名',
		     
		      ),
		array('Column' => "処方用番号", 'Draw' => NULL),
		array('Column' => "会社識別用番号", 'Draw' => NULL),
		array('Column' => "調剤用番号", 'Draw' => NULL),
		array('Column' => "物流用番号", 'Draw' => NULL),
		array('Column' => "ＪＡＮコード", 'Draw' => NULL),
		array('Column' => "個別医薬品コード", 'Draw' => NULL),
		array('Column' => "レセプト電算処理システムコード（１）",
		      'Draw' => NULL),
		array('Column' => "レセプト電算処理システムコード（２）",
		      'Draw' => NULL),
		array('Column' => "告示名称", 'Draw' => NULL),
		array('Column' => "販売名", 'Draw' => NULL),
		array('Column' => "規格単位", 'Draw' => NULL),
		array('Column' => "包装形態", 'Draw' => NULL),
		array('Column' => "包装単位数", 'Draw' => NULL),
		array('Column' => "包装総量数", 'Draw' => NULL),
		array('Column' => "包装総量単位", 'Draw' => NULL),

		array('Column' => "製造会社", 'Draw' => NULL),
		array('Column' => "販売会社", 'Draw' => NULL),
		array('Column' => "更新区分", 'Draw' => NULL),
		array('Column' => "更新年月日", 'Draw' => NULL),
		);

    $c = array(
	       TABLE => 'Medis医薬品マスター',
	       COLS => $cols,
	       LCOLS => array("レセプト電算処理システム医薬品名",
			      array('Column' => "病院使用医薬品名",
				    'Label' => "病院使用検索名称",
				    ),
			"区分",
			     "病院使用包装単位単位",
			     "病院使用レセコンコード",
		      array('Column' => "当院採用",
				    'Label' => "当院採用",
				    'Draw' => 'enum',
				    'Enum' => $choice),
			      array('Column' => "病院使用ラベル要印刷",
				    'Draw' => 'enum',
				    'Enum' => $label_enum,
				    ),
//1230-2012
			array('Column' => "kananame",
		      'Label' => 'カタカナ名',
		      ),
			      ),
	       DCOLS => $dcols,
	       ECOLS => $ecols,
	       LCHOICE => $choice,
	       X_LCHOICE_FORCE_DROPDOWN => 1,
	       ALLOW_SORT => 1,
	       ENABLE_QBE => 1
	       );
    $_lib_u_manage_pharmacy_medicine_cfg = $c;
}

_lib_u_manage_pharmacy_medicine_cfg_setup();

class list_of_medicine extends list_of_simple_objects {
  function list_of_medicine($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_medicine_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_medicine_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function const_where($qn, $k) {
    if (mb_strlen($k, 'EUC-JP') == 1) {
	$wadd = "$qn = " . mx_db_sql_quote($k);
    }
    else {
      $sa = array();
      for ($ix = 0; $ix < mb_strlen($k, 'EUC-JP'); $ix++)
	$sa[] = mx_db_sql_quote(mb_substr($k, $ix, 1, 'EUC-JP'));
      $w = ('IN ( ' .
	    implode(', ', $sa) .
	    ' )');
      $wadd = "$qn $w";
    }
    return $wadd;
  }

  function base_fetch_stmt_1($i) {
    $base = $this->so_config['STMT'];
    if ($i != '') {
      $base .= " AND " . $this->const_where('"当院採用"', $i);
    }
    return $base;
  }
}

class medicine_display extends simple_object_display {
  function medicine_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_medicine_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_medicine_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }

}

class medicine_edit extends simple_object_edit {
  function medicine_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_pharmacy_medicine_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_pharmacy_medicine_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function annotate_form_data(&$data) {
    if (trim($data["包装単位単位"]) == '') {
      $data["包装単位単位"] = trim($data["病院使用包装単位単位"]);
    }
    if (trim($data["レセプト電算処理システム医薬品名"]) == '') {
      $data["レセプト電算処理システム医薬品名"] =
	trim($data["病院使用医薬品名"]);
    }
  }

  function _validate() {
    $d =& $this->data;

    $bad = 0;
    // Ugh.
    if (! $this->id) {
      $db = mx_db_connect();
      $r = mx_db_fetch_single($db,
	  "SELECT MAX(\"基準番号\") AS x FROM \"Medis医薬品マスター\"
           WHERE \"基準番号\" LIKE 'S%'");
      if (!is_null($r)) {
	$new_num = sprintf("S%012d", intval(substr($r['x'],1))+1);
      }else{
	$new_num = 'S000000000001';
      }
      $d['基準番号'] = $new_num;
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

    if (trim($d['病院使用医薬品名']) == '') {
      $this->err("病院使用医薬品名を指定してください\n");
      $bad++;
    }
    if (trim($d['病院使用包装単位単位']) == '') {
      $this->err("病院使用包装単位単位を指定してください\n");
      $bad++;
    }
    if (! $bad)
      return 'ok';
  }
  
}

?>
