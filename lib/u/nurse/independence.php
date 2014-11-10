<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_independence_cfg = array
(
 'TABLE' => '日常生活自立度管理表',
 'Patient_ID' => NULL,
 'Patient_ObjectID' => NULL,
 'Patient_Name' => NULL,
 'DEFAULT_SORT' => '日付',
 'LCOLS' => array('日付', '記録者名', '日常生活自立度'),
 'ICOLS' => array('日付', '日常生活自立度', '経口摂取', '排泄自立',
		  '算定', '患者',
		  '痴呆性老人の日常生活自立度判定基準',
		  '排泄・短期目標', '排泄・アプローチ'),
 'ALLOW_SORT' => array('日付' => array('日付' => '"日付"'),
		       '記録者名' => array
		       ('記録者名' => '(E."姓" || E."名")'),
		       '日常生活自立度' => array
		       ('日常生活自立度' => '"日常生活自立度"') ),

 'UNIQ_ID' => 'I."ObjectID"',
 );

$_lib_u_nurse_independence_bfd_prev = array
(1 => '自力立位強化',
 2 => '離床訓練・トイレ誘導',
 3 => '拘縮予防・褥瘡予防',
 4 => '拘縮予防・筋力強化');

$_lib_u_nurse_independence_bfd_plan = array
(1 => '車椅子自走・歩行器介助',
 2 => 'ベッドサイド起立・移乗介助',
 3 => '床上体操・筋力強化',
 4 => '床上ROM訓練・体位変換');

$_lib_u_nurse_independence_stmt_head = '';

function _lib_u_nurse_independence__init() {
  global $_lib_u_nurse_independence_cfg,
         $_lib_u_nurse_independence_stmt_head,
         $_lib_u_nurse_independence_bfd_prev,
         $_lib_u_nurse_independence_bfd_plan;

  $_lib_u_nurse_independence_stmt_head = '
SELECT I."ObjectID", I."Superseded", I."CreatedBy",
I."日付", I."患者", I."日常生活自立度", I."経口摂取", I."排泄自立", I."算定",
I."痴呆性老人の日常生活自立度判定基準",
I."排泄・短期目標", I."排泄・アプローチ",
(E."姓" || E."名") AS "記録者名"';
  foreach ($_lib_u_nurse_independence_bfd_prev as $ix => $val)
    $_lib_u_nurse_independence_stmt_head .= sprintf(', I."予防_%02d"', $ix);
  foreach ($_lib_u_nurse_independence_bfd_plan as $ix => $val)
    $_lib_u_nurse_independence_stmt_head .= sprintf(', I."計画_%02d"', $ix);
  $_lib_u_nurse_independence_stmt_head .= '
FROM "日常生活自立度管理表" AS I
LEFT JOIN "職員台帳" AS E
ON E."userid" = I."CreatedBy" AND E."Superseded" IS NULL
';

  $_lib_u_nurse_independence_cfg['HSTMT'] =
      $_lib_u_nurse_independence_stmt_head . ' WHERE NULL IS NULL';
  $_lib_u_nurse_independence_cfg['STMT'] =
      $_lib_u_nurse_independence_stmt_head . ' WHERE I."Superseded" IS NULL';
  $ecol = array(array('Column' => '日常生活自立度',
		      'Draw' => 'enum',
		      'Enum' => array('-' => '-',
				      'J' => 'J',
				      'A1' => 'A1', 'A2' => 'A2',
				      'B1' => 'B1', 'C2' => 'B2',
				      'C1' => 'C1', 'C2' => 'C2') ),
		array('Column' => '経口摂取', 'Draw' => 'check'),
		array('Column' => '排泄自立', 'Draw' => 'check'),
		array('Column' => '患者', 'Draw' => NULL),
		);
  foreach ($_lib_u_nurse_independence_bfd_prev as $bit => $name) {
    $cn = sprintf('予防_%02d', $bit);
    $ecol[] = array('Label' => $name,
		    'Column' => $cn,
		    'Draw' => 'check');
    $_lib_u_nurse_independence_cfg['ICOLS'][] = $cn;
  }

  foreach ($_lib_u_nurse_independence_bfd_plan as $bit => $name) {
    $cn = sprintf('計画_%02d', $bit);
    $ecol[] = array('Label' => $name,
		    'Column' => $cn,
		    'Draw' => 'check');
    $_lib_u_nurse_independence_cfg['ICOLS'][] = $cn;
  }

  $ecol[] = array('Column' => '算定', 'Draw' => 'check');
  $ecol[] = array('Column' => "痴呆性老人の日常生活自立度判定基準",
		  'Draw' => 'enum',
		  'Enum' => array('-' => '-',
				  "I" => "I",
				  "IIa" => "IIa",
				  "IIb" => "IIb",
				  "IIIa" => "IIIa",
				  "IIIb" => "IIIb",
				  "IV" => "IV",
				  "V" => "V",
				  "VI" => "VI",
				  "M" => "M"));
  $ecol[] = array('Column' => '排泄・短期目標', 'Draw' => 'textarea');
  $ecol[] = array('Column' => '排泄・アプローチ', 'Draw' => 'textarea');

  $dcol = array_merge(array('日付', '記録者名'), $ecol);
  $ecol = array_merge(array(array('Column' => '日付',
				  'Option' => array('ime' => 'disabled',
						'validate' => 'date'))),
		      $ecol);
  $_lib_u_nurse_independence_cfg['DCOLS'] = $dcol;
  $_lib_u_nurse_independence_cfg['ECOLS'] = $ecol;
}
_lib_u_nurse_independence__init();

function _lib_u_nurse_independence_annotate(&$it, &$row) {
  $row['患者'] = $it->so_config['Patient_ObjectID'];
}

class list_of_independence_evaluations extends list_of_ppa_objects {

  var $default_row_per_page = 4;

  function list_of_independence_evaluations($prefix, $config=NULL) {
    global $_lib_u_nurse_independence_cfg;

    if (is_null($config)) $config = $_lib_u_nurse_independence_cfg;
    list_of_ppa_objects::list_of_ppa_objects($prefix, $config);

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

function _lib_u_nurse_independence_fetch(&$it, &$db, $id) {
  global $_lib_u_nurse_independence_stmt_head;
  $stmt = ($_lib_u_nurse_independence_stmt_head .
	   'WHERE I."ObjectID" = ' . mx_db_sql_quote($id));
  $it->dbglog($stmt);
  return mx_db_fetch_single($db, $stmt);
}

class independence_evaluation_display extends simple_object_display {

  function independence_evaluation_display($prefix, $config=NULL) {
    global $_lib_u_nurse_independence_cfg;

    if (is_null($config)) $config = $_lib_u_nurse_independence_cfg;
    simple_object_display::simple_object_display($prefix, $config);

  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_independence_fetch(&$this, &$db, $id);
  }

  function annotate_row_data(&$row) {
    if ($row['患者'] != $this->so_config['Patient_ObjectID'])
      die("3girID");
  }

}

class independence_evaluation_edit extends simple_object_edit {

  var $debug = 0;

  function independence_evaluation_edit($prefix, $config=NULL) {
    global $_lib_u_nurse_independence_cfg;
    if (is_null($config)) $config = $_lib_u_nurse_independence_cfg;
    simple_object_edit::simple_object_edit($prefix, $config);
  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_independence_fetch(&$this, &$db, $id);
  }

  function anew_tweak($orig_id) {
    $this->data['日付'] = mx_today_string();
    $this->annotate_row_data(&$this->data);
  }

  function annotate_row_data(&$row) {
    // This one is needed---we may be creating anew.
    $row['患者'] = $this->so_config['Patient_ObjectID'];
  }

  function _validate() {

    $bad = 0;
    if ($st = mx_db_validate_date($this->data['日付'])) {
      $this->err("(日付): $st\n");
      $bad++;
    }

    if (! $bad)
      return 'ok';

  }

}

?>
