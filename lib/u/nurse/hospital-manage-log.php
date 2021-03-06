<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-manage-log.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

function _lib_u_nurse_hospital_manage_log_config(&$config)
{
  $config['DCOLS'] = array('日直医師名',
			   '当直医師名',
			   '外来担当看護師名',
			   '歯科衛生士名',
			   '病院外来患者数',
			   '歯科外来患者数',
			   array('Column' => '特記事項',
				 'Draw' => 'textarea'),
			   array('Column' => 'CreatedBy',
				 'Draw' => 'user'));
  _lib_so_prepare_config_ledcols(&$config['DCOLS']);
}

function _lib_u_nurse_hospital_manage_log_peek_id(&$it, $db, $dt)
{
  $stmt = ('SELECT HM."ObjectID"
FROM "病院看護管理日誌" AS HM
WHERE HM."Superseded" IS NULL AND HM."日付" = ' . mx_db_sql_quote($dt));
  $it->dbglog("CHECK-EXISTS: $stmt;\n");
  $st = pg_query($db, $stmt);
  if (! $st) {
    var_dump(debug_backtrace());
  }
  $r = pg_fetch_all($st);
  if (! is_array($r) || count($r) != 1)
    return NULL;
  return $r[0]['ObjectID'];
}

function _lib_u_nurse_hospital_manage_log_borrow_wml(&$it, &$data, $db)
{
  // Fetch data from the ward management log.
  $stmt = ('SELECT "ObjectID", "病棟名" FROM "病棟一覧表"
WHERE "Superseded" IS NULL
ORDER BY "病棟名"');
  $it->dbglog("$stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  $dt = $data['日付'];
  $it->ward_list = array();
  $data['転棟'] = array();
  $data['入院'] = array();
  $data['退院'] = array();
  $data['外泊'] = array();
  $ward_total = '全病棟合計';
  foreach ($r as $_ward_data) {
    $ward = $_ward_data['ObjectID'];
    $ward_name = $_ward_data['病棟名'];
    $it->ward_list[$ward] = $ward_name;
    $wcf = array();
    _lib_u_nurse_ward_manage_log_prepare_config($wcf);
    $wd =
      _lib_u_nurse_ward_manage_log_get_by_dt_ward($it, $wcf, $db, $dt, $ward);

    foreach (array("空床部屋", "許可病床数",
		   "入院・日", "入院・夜",
		   "軽快", "転院", "死亡", "他の転帰",
		   "転入", "転出") as $col) {
      $data[$ward_name . '.' . $col] = $wd['全般'][0][$col];
      $data[$ward_total . '.' . $col] += $wd['全般'][0][$col];
    }
    foreach (array("患者数" => array("担送", "護送", "独歩"),
		   "入院" => array("入院・日", "入院・夜"),
		   "転院・転帰" => array("軽快", "転院", "死亡", "他の転帰"))
	     as $col => $scols) {
      $sum = 0;
      $all_null = 1;
      foreach ($scols as $scol) {
	if ($wd['全般'][0][$scol] != '') $all_null = 0;
	$sum += $wd['全般'][0][$scol];
      }
      if (! $all_null) {
	$data[$ward_name . '.' . $col] = $sum;
	$data[$ward_total . '.' . $col] += $sum;
      }
    }

    foreach ($wd['転入転出'] as $d) {
      $d['病棟'] = $ward;
      $d['病棟名'] = $ward_name;
      switch ($d['入出']) {
      case 'i': // 他病棟より転入
	$data['転棟'][] = $d; break;
      case 'o': // 他病棟に転出
	;; // already covered above.
      case 'I': // 入院
	$data['入院'][] = $d; break;
      case 'O': // 退院
	$data['退院'][] = $d; break;
      }
    }

    foreach ($wd['外泊・外出'] as $d) {
      if ($d['管理日誌欄'] == 0) {
	$d['病棟'] = $ward;
	$d['病棟名'] = $ward_name;
	$data['外泊'][] = $d;
      }
    }
  }
}

function _lib_u_nurse_hospital_manage_log_fetch_data(&$it, $db, $id)
{
  $stmt = ('SELECT HM."ObjectID", HM."Superseded", HM."CreatedBy",
HM."日付",
HM."日直医師", (ED0."姓" || ED0."名") AS "日直医師名",
HM."当直医師", (ED1."姓" || ED1."名") AS "当直医師名",
HM."外来担当看護師", (EN."姓" || EN."名") AS "外来担当看護師名",
HM."歯科衛生士", (EP."姓" || EP."名") AS "歯科衛生士名",
HM."病院外来患者数",
HM."歯科外来患者数",
HM."特記事項",
HM."正看護師・常勤総数",
HM."正看護師・パート総数",
HM."正看護師・アルバイト総数",
HM."准看護師・常勤総数",
HM."准看護師・パート総数",
HM."准看護師・アルバイト総数",
HM."看護助手総数"
FROM "病院看護管理日誌" AS HM
LEFT JOIN "職員台帳" AS ED0
ON ED0."ObjectID" = HM."日直医師" AND ED0."Superseded" IS NULL
LEFT JOIN "職員台帳" AS ED1
ON ED1."ObjectID" = HM."当直医師" AND ED1."Superseded" IS NULL
LEFT JOIN "職員台帳" AS EN
ON EN."ObjectID" = HM."外来担当看護師" AND EN."Superseded" IS NULL
LEFT JOIN "職員台帳" AS EP
ON EP."ObjectID" = HM."歯科衛生士" AND EP."Superseded" IS NULL
WHERE HM."ObjectID" = ' . mx_db_sql_quote($id));
  $it->dbglog("fetch: $stmt;\n");
  $data = mx_db_fetch_single($db, $stmt);
  _lib_u_nurse_hospital_manage_log_borrow_wml(&$it, &$data, $db);
  return $data;
}

function _lib_u_nurse_hospital_manage_log_get_by_dt(&$it, $db, $dt)
{
  $id = _lib_u_nurse_hospital_manage_log_peek_id(&$it, $db, $dt);
  if (! is_null($id))
    $data =
      _lib_u_nurse_hospital_manage_log_fetch_data($it, $db, $id);
  else {
    $data = array('日付' => $dt);
    _lib_u_nurse_hospital_manage_log_borrow_wml(&$it, &$data, $db);
  }
  return $data;
}

class hospital_manage_log_display extends simple_object_display {

  var $debug = 0;

  function hospital_manage_log_display($prefix, $config=NULL) {
    $this->prefix = $prefix;
    _lib_u_nurse_hospital_manage_log_config(&$config);
    $this->so_config = $config;
    $this->so_config['TABLE'] = "病院看護管理日誌";
    $this->drawer = new _lib_so_drawer($this);

    if (array_key_exists($prefix . 'history-at', $_REQUEST))
      $this->history_ix = $_REQUEST[$prefix . 'history-at'];
    else
      $this->history_ix = NULL;

    $db = mx_db_connect();
    $this->chosen = $this->config_dt();
    $this->id = _lib_u_nurse_hospital_manage_log_peek_id
      ($this, $db, $this->chosen);

  }

  function config_dt() {
    return sprintf("%04d-%02d-%02d", $this->so_config['Year'],
		   $this->so_config['Month'],
		   $this->so_config['Date']);
  }

  function reset($id=NULL) {
    $this->history_ls = $this->history_ix = NULL;
  }

  function chosen() {
    return $this->chosen;
  }

  function fetch_data($id=NULL) {
    $db = mx_db_connect();
    if (is_null($id))
      return _lib_u_nurse_hospital_manage_log_get_by_dt
	($this, $db, $this->config_dt());
    else
      return _lib_u_nurse_hospital_manage_log_fetch_data($this, $db, $id);
  }

  function history($move_direction=NULL) {
    if (is_null($this->id)) return 2;
    return simple_object_display::history($move_direction);
  }

  var $table_class = array('random-format', 'random-format-historical');
  var $history_in_body =1;

  function draw_body_3($data, $hdata, $dcols) {
    // Ugh.  random format

    print '<tr><th>日付</th><td>';
    print $data['日付'];
    print '</td><th>日直医師</th>';
    $this->draw_body_atom($this->find_dcol('日直医師名', $dcols),
			  $data, $hdata, 3);
    print '<th colspan="2">当直医師</th>';
    $this->draw_body_atom($this->find_dcol('当直医師名', $dcols),
			  $data, $hdata, 2);
    print "</tr>\n";

    print '<tr><th>外来担当看護師</th>';
    $this->draw_body_atom($this->find_dcol('外来担当看護師名', $dcols),
			  $data, $hdata, 4);
    print '</td><th>歯科衛生士</th>';
    $this->draw_body_atom($this->find_dcol('歯科衛生士名', $dcols),
			  $data, $hdata, 4);
    print "</tr>\n";

    print '<tr><th>病院外来患者数</th>';
    $this->draw_body_atom($this->find_dcol('病院外来患者数', $dcols),
			  $data, $hdata, 4);
    print '</td><th>歯科外来患者数</th>';
    $this->draw_body_atom($this->find_dcol('歯科外来患者数', $dcols),
			  $data, $hdata, 4);
    print "</tr>\n";

    $this->draw_ward_row_title();
    foreach ($this->ward_list as $ward => $ward_name)
      $this->draw_ward_row($data, $hdata, $dcols, $ward, $ward_name);
    $this->draw_ward_row($data, $hdata, $dcols, NULL, '全病棟合計');

    $this->draw_inout_row($data, $hdata, $dcols, '入院');
    $this->draw_inout_row($data, $hdata, $dcols, '退院');
    $this->draw_xfer_row($data, $hdata, $dcols, '転棟');
    $this->draw_outstay_row($data, $hdata, $dcols, '外泊');

    print '<tr><th>特記事項</th>';
    $desc = $this->find_dcol('特記事項', $dcols);
    $this->draw_body_atom($this->find_dcol('特記事項', $dcols),
			  $data, $hdata, 8);
    print "</tr>\n";

    print '<tr><th>記録者</th>';

    $this->draw_body_atom($this->find_dcol('CreatedBy', $dcols),
			  $data, $hdata, 2);

    if (is_null($this->history_ix))
      print '<td colspan="6">&nbsp;</td>';
    else {
      print '<td colspan="3">変更タイムスタンプ</td><td colspan="3">';
      print htmlspecialchars(mx_format_timestamp($data['Superseded']));
    }

    print "</td></tr>\n";
  }

  function draw_random_row_helper($data, $hdata, $colname, $rs, $pfx='') {
    $changed = ($data && $hdata && ($data[$colname] != $hdata[$colname]))
      ? ' class="changed"' : '';
    $rs = (1 < $rs) ? (' rowspan="' . $rs . '"') : '';
    print "<td$rs$changed>$pfx";
    print htmlspecialchars($data[$colname]);
    print "</td>";
  }

  function draw_ward_row_title() {
    print "<tr>";
    print "<th>病棟</th>";
    print "<th>空床部屋</th>";
    print "<th>定数/患者数</th>";
    print "<th colspan=\"2\">入院</th>";
    print "<th colspan=\"3\">転院・転帰</th>";
    print "<th>転入/転出</th>";
    print "</tr>\n";
  }

  function draw_ward_row($data, $hdata, $dcols, $ward_id, $ward) {

    print '<tr>';
    print '<th rowspan="2">';
    if (! is_null($ward_id)) {
      print ('<a href="/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
	     '/u/nurse/wardlog.php?Ward=' . htmlspecialchars($ward_id) .
	     '&amp;WardName=' . htmlspecialchars($ward) .
	     '&amp;SetDate=' . htmlspecialchars($this->config_dt()) .
	     '">');
      print htmlspecialchars($ward);
      print "</a>";
    } else
      print htmlspecialchars($ward);

    print '</th>';

    $this->draw_ward_row_helper($data, $hdata, $ward, '空床部屋', 2);

    $this->draw_ward_row_helper($data, $hdata, $ward, '許可病床数', 1);
    $this->draw_ward_row_helper($data, $hdata, $ward, '入院', 2);
    $this->draw_ward_row_helper($data, $hdata, $ward, '入院・日', 1, '日');

    $this->draw_ward_row_helper($data, $hdata, $ward, '転院・転帰', 2);
    $this->draw_ward_row_helper($data, $hdata, $ward, '軽快', 1, '軽快');
    $this->draw_ward_row_helper($data, $hdata, $ward, '死亡', 1, '死亡');

    $this->draw_ward_row_helper($data, $hdata, $ward, '転入', 1);
    print "</tr>\n";

    print '<tr>';
    $this->draw_ward_row_helper($data, $hdata, $ward, '患者数', 1);

    $this->draw_ward_row_helper($data, $hdata, $ward, '入院・夜', 1, '夜');
    $this->draw_ward_row_helper($data, $hdata, $ward, '転院', 1, '転院');
    $this->draw_ward_row_helper($data, $hdata, $ward, '他の転帰', 1, '他');

    $this->draw_ward_row_helper($data, $hdata, $ward, '転出', 1);
    print "</tr>\n";

  }

  function draw_ward_row_helper($data, $hdata, $ward, $colname, $rs, $pfx='') {
    $this->draw_random_row_helper($data, $hdata, "$ward.$colname", $rs, $pfx);
  }

  function draw_inout_row($data, $hdata, $dcols, $label) {

    print '<tr><th colspan="9">';
    print htmlspecialchars($label);
    print "</th></tr>\n";

    if ($data[$label]) {
      print '<tr>';
      print '<th>病棟</th><th>部屋</th><th>名前</th><th>年齢</th>';
      print '<th colspan="5">備考</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['病棟名']);
	print '</td><td>';
	print htmlspecialchars($d['病室名']);
	print '</td><td>';
	print htmlspecialchars($d['患者名']);
	print '</td><td>';
	print htmlspecialchars($d['患者年齢']);
	print '</td><td colspan="5">';
	print htmlspecialchars($d['備考']);
	print "</td></tr>\n";
      }
    } else
      print "<tr><td colspan=\"9\">&nbsp;</td></tr>\n";
  }

  function draw_xfer_row($data, $hdata, $dcols, $label) {
    print '<tr><th colspan="9">';
    print htmlspecialchars($label);
    print "</th></tr>\n";

    if ($data[$label]) {
      print '<tr>';
      print '<th>転棟先病棟</th><th>部屋</th>';
      print '<th>名前</th><th>年齢</th><th colspan="5">転棟元病棟</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['病棟名']);
	print '</td><td>';
	print htmlspecialchars($d['病室名']);
	print '</td><td>';
	print htmlspecialchars($d['患者名']);
	print '</td><td>';
	print htmlspecialchars($d['患者年齢']);
	print '</td><td colspan="5">';
	print htmlspecialchars($d['転棟病棟名']);
	print "</td></tr>\n";
      }

    } else
      print "<tr><td colspan=\"9\">&nbsp;</td></tr>\n";

  }

  function draw_outstay_row($data, $hdata, $dcols, $label) {
    print '<tr><th colspan="9">';
    print htmlspecialchars($label);
    print "</th></tr>\n";

    if ($data[$label]) {
      print '<tr>';
      print '<th>病棟</th><th>部屋</th><th>名前</th><th>年齢</th>';
      print '<th colspan="2">外泊出時間</th><th>帰院時刻</th>';
      print '<th colspan="2">備考</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['病棟名']);
	print '</td><td>';
	print htmlspecialchars($d['病室名']);
	print '</td><td>';
	print htmlspecialchars($d['患者名']);
	print '</td><td>';
	print htmlspecialchars($d['患者年齢']);
	print '</td><td colspan="2">';
	print htmlspecialchars($d['外泊出時間']);
	print '</td><td>';
	print htmlspecialchars($d['帰院時刻']);
	print '</td><td colspan="2">';
	print htmlspecialchars($d['備考']);
	print "</td></tr>\n";
      }
    } else
      print "<tr><td colspan=\"9\">&nbsp;</td></tr>\n";

  }

}

class hospital_manage_log_edit extends simple_object_edit {

  var $debug = 0;

  function hospital_manage_log_edit($prefix, $config=NULL) {

    global $_lib_u_manage_employee_cfg;

    if (is_null($config)) $config = array();

    $list_of_employees_dr_cfg =
      $list_of_employees_ns_cfg =
	$list_of_employees_pp_cfg = $_lib_u_manage_employee_cfg;

    $list_of_employees_dr_cfg['HSTMT'] .= '
      AND C."職種" in ' . enum_doctor_cat_sql();
    $list_of_employees_ns_cfg['HSTMT'] .= '
      AND C."職種" in ' . enum_nurse_cat_sql();

    $list_of_employees_dr_cfg['STMT'] =
      $list_of_employees_dr_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $list_of_employees_ns_cfg['STMT'] =
      $list_of_employees_ns_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $list_of_employees_pp_cfg['STMT'] =
      $list_of_employees_pp_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $config['TABLE'] = '病院看護管理日誌';
    $config['ECOLS'] = array
      (array('Column' => '日付', 'Draw' => NULL),

       array('Column' => '日直医師', 'Draw' => NULL),
       array('Column' => '日直医師名',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => 'この医師に設定する',
	      'Config' => $list_of_employees_dr_cfg,
	      'ListID' => array('ObjectID', '姓名'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '日直医師') ),

       array('Column' => '当直医師', 'Draw' => NULL),
       array('Column' => '当直医師名',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => 'この医師に設定する',
	      'Config' => $list_of_employees_dr_cfg,
	      'ListID' => array('ObjectID', '姓名'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '当直医師') ),

       array('Column' => '外来担当看護師', 'Draw' => NULL),
       array('Column' => '外来担当看護師名',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => 'この看護師に設定する',
	      'Config' => $list_of_employees_ns_cfg,
	      'ListID' => array('ObjectID', '姓名'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '外来担当看護師') ),

       array('Column' => '歯科衛生士', 'Draw' => NULL),
       array('Column' => '歯科衛生士名',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => 'この看護師に設定する',
	      'Config' => $list_of_employees_pp_cfg, // # NEEDSWORK
	      'ListID' => array('ObjectID', '姓名'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '歯科衛生士') ),

       array('Column' => '病院外来患者数', 'Draw' => 'text'),
       array('Column' => '歯科外来患者数', 'Draw' => 'text'),

       array('Column' => '特記事項',
	     'Draw' => 'textarea'),
       // employee numbers left unspecified
       );
    $config['ICOLS'] = array
	    ('日付', '日直医師', '当直医師', '外来担当看護師',
	     '歯科衛生士', '病院外来患者数', '歯科外来患者数', '特記事項');

    simple_object_edit::simple_object_edit($prefix, $config);

  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_hospital_manage_log_fetch_data(&$this, $db, $id);
  }

  function resync() {
    $this->data = $this->fetch_data($this->id);
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function _validate() {

    $bad = 0;
    foreach (array('日直医師', '当直医師', '外来担当看護師', '歯科衛生士')
	     as $not_null_col) {
      if ($st = mx_db_validate_length($this->data[$not_null_col], 1, 0)) {
	$this->err("($not_null_col): $st\n");
	$bad++;
      }
    }
    foreach (array('病院外来患者数', '歯科外来患者数')
	     as $not_null_col) {
      if ($st = mx_db_validate_nnint($this->data[$not_null_col])) {
	$this->err("($not_null_col): $st\n");
	$bad++;
      }
    }

    if (! $bad)
      return 'ok';

  }

  function edit($chosen) {
    $this->chosen = $chosen;
    $db = mx_db_connect();
    $id = _lib_u_nurse_hospital_manage_log_peek_id
      ($this, $db, $this->chosen);
    simple_object_edit::edit($id);
    if (is_null($id))
      $this->data =
	_lib_u_nurse_hospital_manage_log_get_by_dt(&$this, $db, $chosen);

  }

  function _broken_origin_check() {
     if ($this->id == '') {
	     $db = mx_db_connect();
	     $id = _lib_u_nurse_hospital_manage_log_peek_id
		     ($this, $db, $this->data['日付']);
	     if (is_null($id)) {
		     $this->broken_origin = 0;
		     return 0;
	     }
	     $this->id = $id;
	     $this->broken_origin = 1;
	     return 1;
     }
     else
        return simple_object_edit::_broken_origin_check();
  }

}
?>
