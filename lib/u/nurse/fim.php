<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_fim__fim_enum = array
(
 '' => '(未評価)',
 '7' => '7: 完全自立',
 '6' => '6: 修正自立',
 '5' => '5: 監視・準備',
 '4' => '4: 最小介助',
 '3' => '3: 中等度介助',
 '2' => '2: 最大介助',
 '1' => '1: 全介助',
 );

function _lib_u_nurse_fim__fim_pair($name) {
  global $_lib_u_nurse_fim__fim_enum;
  return array
    (
     array('Column' => $name . '_P', 'Label' => $name, 'Draw' => 'enum',
	   'Enum' => &$_lib_u_nurse_fim__fim_enum),
     array('Column' => $name . '_C', 'Label' => NULL, 'Draw' => 'textarea'),
     );
}

function __lib_u_nurse_fim_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'FIM評価表',
      // 'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '日付',
      'LCOLS' => array('日付',
		       '記録者名',
		       '運動項目合計点',
		       '認知項目合計点',
		       '合計点'),
      'LLAYO' => array
      ('2', '日付', '1', '記録者名', '//',
       array('Column' => '運動項目合計点', 'Label' => '運動項目'),
       array('Column' => '認知項目合計点', 'Label' => '認知項目'),
       '合計点'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  // List of flip-pages COLS elements.
  $flippage = array
    (
     '運動項目(1)' =>
     array_merge
     (
      _lib_u_nurse_fim__fim_pair('食事'),
      _lib_u_nurse_fim__fim_pair('整容'),
      _lib_u_nurse_fim__fim_pair('清拭'),
      _lib_u_nurse_fim__fim_pair('更衣・上半身'),
      _lib_u_nurse_fim__fim_pair('更衣・下半身'),
      _lib_u_nurse_fim__fim_pair('トイレ動作')
      ),

     '運動項目(2)' =>
     array_merge
     (
      _lib_u_nurse_fim__fim_pair('排尿管理'),
      _lib_u_nurse_fim__fim_pair('排泄管理'),
      _lib_u_nurse_fim__fim_pair('ベッド・椅子・車椅子'),
      _lib_u_nurse_fim__fim_pair('トイレ'),
      _lib_u_nurse_fim__fim_pair('浴槽シャワー'),
      _lib_u_nurse_fim__fim_pair('車椅子'),
      _lib_u_nurse_fim__fim_pair('歩行'),
      _lib_u_nurse_fim__fim_pair('階段'),
      array
      (
       array('Column' => '移動手段',
	     'Draw' => 'enum',
	     'Enum' => array('L' => '歩行', 'W' => '車椅子')),
       )),

     '認知項目' =>
     array_merge
     (
      _lib_u_nurse_fim__fim_pair('理解'),
      _lib_u_nurse_fim__fim_pair('表出'),
      _lib_u_nurse_fim__fim_pair('社会的交流'),
      _lib_u_nurse_fim__fim_pair('問題解決'),
      _lib_u_nurse_fim__fim_pair('記憶')
      ),

     );

  $pmco = array();
  foreach (array('運動項目(1)', '運動項目(2)') as $page) {
    foreach ($flippage[$page] as $desc)
      if (substr($desc['Column'], -2) == '_P')
	$pmco[] = 'COALESCE(F.' . mx_db_sql_quote_name($desc['Column']) .
	  ', 0)';
  }

  // Yuck.
  $m1 = ('COALESCE(F.' . mx_db_sql_quote_name('車椅子_P') . ', 0)');
  $m2 = ('COALESCE(F.' . mx_db_sql_quote_name('歩行_P') . ', 0)');
  $pmco[] = "(- (CASE WHEN ($m1 < $m2) THEN $m1 ELSE $m2 END))";

  $pmst = '(' . implode(" +\n  ", $pmco) . ') AS "運動項目合計点"';

  $pcco = array();
  foreach (array('認知項目') as $page) {
    foreach ($flippage[$page] as $desc)
      if (substr($desc['Column'], -2) == '_P')
	$pcco[] = 'COALESCE(F.' . mx_db_sql_quote_name($desc['Column']) .
	  ', 0)';
  }
  $pcst = '(' . implode(" +\n  ", $pcco) . ') AS "認知項目合計点"';

  $pbst = '(' . implode(" +\n  ", array_merge($pmco, $pcco)) . ') AS "合計点"';

  $cfg['ALLOW_SORT'] = array
    (
     '日付' => array('日付' => '"日付"'),
     '記録者名' => array('記録者名' => '"記録者名"'),
     '運動項目合計点' => array('運動項目合計点' => '(' .
		       implode(" +\n  ", $pmco) . ')'),
     '認知項目合計点' => array('認知項目合計点' => '(' .
		       implode(" +\n  ", $pcco) . ')'),
     '合計点' => array('合計点' => '(' .
		       implode(" +\n  ", array_merge($pmco, $pcco)) . ')'),
     );

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名",' . "
$pmst,
$pcst,
$pbst" . '
FROM "FIM評価表" AS F
LEFT JOIN "職員台帳" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $cfg['ECOLS'] = array(array('Column' => '日付',
			      'Option' =>  array('ime' => 'disabled',
						 'validate' => 'date'),
			array('Column' => '評価種', 'Draw' => NULL),
			array('Column' => '患者', 'Draw' => NULL)));
  $cfg['DCOLS'] = array('日付');
  $cfg['ICOLS'] = array('日付', '患者', '評価種');
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
  $page_num = -1;
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $a) {
      $a['Page'] = $page_num;
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      $cfg['ICOLS'][] = $a['Column'];
    }
  }
  $cfg['DCOLS'][] = '記録者名';
}

class list_of_nurse_fims extends list_of_ppa_objects {

  var $side = 'T'; // or 'N'
  var $default_row_per_page = 4;

  function list_of_nurse_fims($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fim_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
  
  function base_fetch_stmt_0() {
    return (list_of_ppa_objects::base_fetch_stmt_0() .
	    ' AND "評価種" = ' . mx_db_sql_quote($this->side));
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '日付') ? 1 : 0);
    }
    return $paging_orders;
  }

}

class nurse_fim_display extends simple_object_display {

  function nurse_fim_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fim_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_fim_edit extends simple_object_edit {

  var $default_threeway_ok = 1;

  function nurse_fim_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fim_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['日付'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['患者'] = $this->so_config['Patient_ObjectID'];
    $d['評価種'] = $this->side; // relies on fim-application
    $this->dbglog("ARD: ");
    $this->dbglog(mx_var_dump($d));
    $this->dbglog(mx_var_dump($this->so_config));
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {

    $bad = 0;
    foreach ($this->so_config['ICOLS'] as $col) {
      if (strstr($col, "_P")) {
        if ($this->data[$col] == "")
	  $this->data[$col] = NULL;
	else if ($st = mx_db_validate_range($this->data[$col], 1, 7)) {
	  $len = strlen($col);
	  $col = substr($col, 0, $len - 2);
	  $this->err("($col): $st\n");
	  $bad = 1;
	}
      }
    }

    if ($st = mx_db_validate_date($this->data['日付'])) {
      $this->err("(日付): $st\n");
      $bad++;
    }

    if ($bad == 0)
      return 'ok';
  }

}

?>
