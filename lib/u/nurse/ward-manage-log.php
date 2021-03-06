<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-employee-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-patient.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-room.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward.php';

function _lib_u_nurse_ward_manage_log_A1C4($ty=NULL) {
  if (is_null($ty))
    return array("A1", "A2", "A3", "A4",
		 "B1", "B2", "B3", "B4",
		 "C1", "C2", "C3", "C4");
  $l = array();
  foreach (_lib_u_nurse_ward_manage_log_A1C4(NULL) as $elt) {
    $l[] = 'L.' . mx_db_sql_quote_name($elt);
  }
  return implode(', ', $l);
}

function _lib_u_nurse_ward_manage_log_prepare_config(&$config)
{
  $config['TABLE'] = '病棟管理日誌';
  $config['COLS'] = array('unused');
  $config['ECOLS'] = array();
  $config['Pages'] = array
    ('全般' => array_merge(array("日付", "病棟", "担送", "護送", "独歩"),
			   _lib_u_nurse_ward_manage_log_A1C4(),
			   array("備考", "空床部屋",
				 "入院・日", "入院・夜", "軽快", "転院",
				 "死亡", "他の転帰", "転入", "転出",
				 "師長", "部長", "病棟名", "許可病床数",
				 "師長名", "部長名")),
     '職員' => array("職員", "職員名", "管理日誌欄"),
     '転入転出' => array("患者", "病室", "入出", "転棟病棟", "備考",
			 "患者名", "患者ID", "患者年齢",
			 "転棟病棟名", "病室名"),
     '転室' => array("患者", "転室元病室", "転室先病室",
		     "患者名", "患者ID", "患者年齢",
		     "転室元病室名", "転室先病室名"),
     '外泊・外出' => array("患者", "病室", "外泊出時間", "帰院時刻",
			   "管理日誌欄", "備考",
			   "患者名", "患者ID", "患者年齢",
			   "病室名"),
     '患者管理特記事項' => array("患者", "病室",
				 "日勤帯特記事項", "夜勤帯特記事項",
				 "患者名", "患者ID", "患者年齢",
				 "病室名"),
     '在庫管理' => array("名称", "在庫数", "管理日誌欄"));

  $config['ICOLS'] = array_merge(array("日付", "病棟", "担送", "護送", "独歩"),
				 _lib_u_nurse_ward_manage_log_A1C4(),
				 array("備考",
				       "空床部屋", "入院・日", "入院・夜",
				       "軽快", "転院", "死亡", "他の転帰",
				       "転入", "転出", "師長", "部長"));
}

function _lib_u_nurse_ward_manage_log_peek_id(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT "ObjectID" FROM "病棟管理日誌"
WHERE "病棟" = ' . mx_db_sql_quote($ward) . '
AND "日付" = ' . mx_db_sql_quote($dt) . '
AND "Superseded" IS NULL');
  $it->dbglog("CHECK-EXISTS: $stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  if (! is_array($r) || count($r) != 1)
    return NULL;
  if (! is_null($r[0]['ObjectID']))
    return $r[0]['ObjectID'];
  return NULL;
}

function _lib_u_nurse_ward_manage_log_get_by_dt_ward(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT WML."ObjectID", W."病棟名", sum(R."定数") AS "許可病床数"
            FROM "病棟一覧表" AS W
            JOIN "病室一覧表" AS R
            ON W."ObjectID" = R."病棟" AND
               W."Superseded" IS NULL AND R."Superseded" IS NULL
            LEFT JOIN "病棟管理日誌" AS WML
            ON W."ObjectID" = WML."病棟" AND
	       WML."Superseded" IS NULL AND
               WML."日付" = ' . mx_db_sql_quote($dt) . '
            WHERE W."ObjectID" = ' . mx_db_sql_quote($ward) . '
            GROUP BY WML."ObjectID", W."病棟名";');
  $it->dbglog("CHECK-EXISTS: $stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  if (! is_array($r) || count($r) != 1)
    die('Whoa');

  if (! is_null($r[0]['ObjectID']))
    return
      _lib_u_nurse_ward_manage_log_fetch_data($it->debug, $db,
					      $r[0]['ObjectID']);
  $a = array('CreatedBy' => NULL,
	     'ObjectID' => NULL,
	     'Superseded' => NULL,
	     '全般' => array(array('日付' => $dt,
				   '病棟' => $ward,
				   '病棟名' => $r[0]['病棟名'],
				   '許可病床数' => $r[0]['許可病床数'])) );
  foreach ($cf['Pages'] as $slot => $cfg)
    if (! array_key_exists($slot, $a)) $a[$slot] = array();

  // Annotate with default inventory hints
  $hints = _lib_u_nurse_ward_manage_log_neigh_hints_by_dt_ward
    ($it, $cf, $db, $dt, $ward);

  if (is_array($hints)) {
    foreach ($hints['在庫管理'] as $row)
      $a['在庫管理'][] = mx_pick_array($row, '名称', '管理日誌欄');

    foreach ($hints['外泊・外出'] as $row)
      if (is_null($row['帰院時刻']))
	$a['外泊・外出'][] = mx_pick_array
	  ($row,
	   '患者', '患者名', '患者ID', '患者年齢', '病室', '病室名',
	   '管理日誌欄');

  }
  return $a;
}

function _lib_u_nurse_ward_manage_log_neigh_hints_by_dt_ward(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT WML."ObjectID"
FROM "病棟管理日誌" AS WML
WHERE WML."Superseded" IS NULL AND WML."日付" < ' . mx_db_sql_quote($dt) . '
AND WML."病棟" = ' .mx_db_sql_quote($ward) . '
ORDER BY WML."日付" DESC
LIMIT 1');
  $it->dbglog("PRV -- $stmt;\n");
  $hint_oid = mx_db_fetch_single($db, $stmt);
  if (! is_array($hint_oid) || ! array_key_exists('ObjectID', $hint_oid))
    return NULL;
  return _lib_u_nurse_ward_manage_log_fetch_data($it->debug, $db,
						 $hint_oid['ObjectID']);
}

function _lib_u_nurse_ward_manage_log_compare_data($new, $org, $cfg)
{
  $diff = 0;
  foreach ($cfg['Pages'] as $page => $conf) {
    if ($diff) break;
    $o = $org[$page]; $n =& $new[$page];
    if (count($o) != count($n))
      $diff = 1;
    else {
      $cnt = count($o);
      for ($ix = 0; $ix < $cnt; $ix++) {
	if ($diff) break;
	foreach ($conf as $col) {
	  if ($o[$ix][$col] != $n[$ix][$col]) {
	    $diff = 1;
	    break;
	  }
	}
      }
    }
  }
  return $diff;
}

function __d($debug, $db, $stmt, &$data, $column) {
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d))
    $data[$column] = $d;
  else
    $data[$column] = array();

  if ($debug) {
    print "<!--\n$stmt;\n";
    if (is_array($d))
      print "Returned " . count($d) . " items\n";
    elseif (! is_array($d) && ! is_bool($d)) {
      print var_dump($d);
      print "Error?\n";
    }
    print "-->\n";
  }
}

function _lib_u_nurse_ward_manage_log_fetch_data($debug, $db, $oid)
{
  $stmt = ('SELECT L."CreatedBy", L."ObjectID", L."ID", L."Superseded",
		   L."日付", L."病棟", L."担送", L."護送", L."独歩", '.
	   _lib_u_nurse_ward_manage_log_A1C4('L') . ',
	           L."備考", L."空床部屋",
		   L."入院・日", L."入院・夜", L."軽快", L."転院",
		   L."死亡", L."他の転帰", L."転入", L."転出",
		   L."師長", L."部長", W."病棟名",
		   sum(R."定数") AS "許可病床数",
		   (E0."姓" || \' \' || E0."名") AS "師長名",
		   (E1."姓" || \' \' || E1."名") AS "部長名"
	    FROM "病棟管理日誌" AS L JOIN "病棟一覧表" AS W
	    ON L."病棟" = W."ObjectID" AND W."Superseded" IS NULL
	    JOIN "病室一覧表" AS R
	    ON R."病棟" = W."ObjectID" AND R."Superseded" IS NULL
	    LEFT JOIN "職員台帳" AS E0
	    ON L."師長" = E0."ObjectID" AND E0."Superseded" IS NULL
	    LEFT JOIN "職員台帳" AS E1
	    ON L."部長" = E1."ObjectID" AND E1."Superseded" IS NULL
	    WHERE L."ObjectID" = ' . mx_db_sql_quote($oid) .
	   ' GROUP BY
	    L."CreatedBy", L."ObjectID", L."ID", L."Superseded",
	    L."日付", L."病棟", L."担送", L."護送", L."独歩", ' .
	   _lib_u_nurse_ward_manage_log_A1C4('L') . ',
            L."備考", L."空床部屋",
	    L."入院・日", L."入院・夜", L."軽快", L."転院",
	    L."死亡", L."他の転帰", L."転入", L."転出",
	    L."師長", L."部長", W."病棟名",
	    "師長名",
	    "部長名"'); // Ugh.
  $data = array();
  __d($debug, $db, $stmt, &$data, '全般');
  $data['CreatedBy'] = $data['全般'][0]['CreatedBy'];
  $data['ObjectID'] = $oid;

  $stmt = ('SELECT X."職員", (E."姓" || \' \' || E."名") AS "職員名",
            X."管理日誌欄"
            FROM "病棟管理日誌・職員" AS X JOIN "職員台帳" AS E
            ON X."職員" = E."ObjectID" AND E."Superseded" IS NULL
            WHERE X."病棟管理日誌" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY E."職員ID"');
  __d($debug, $db, $stmt, &$data, '職員');

  $pex = ('(P."姓" || \' \' || P."名") AS "患者名", P."患者ID",
           (extract(year from age(timestamp \'' .
	  $data['全般'][0]['日付'] .
	  '\', P."生年月日"))) AS "患者年齢"');

  $stmt = ('SELECT X."患者", X."病室", X."入出", X."転棟病棟", X."備考",
            ' . $pex . ',
            W."病棟名" AS "転棟病棟名", R."病室名"
            FROM "病棟管理日誌・転入転出" AS X JOIN "患者台帳" AS P
            ON X."患者" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "病室一覧表" AS R
            ON X."病室" = R."ObjectID" AND R."Superseded" IS NULL
            LEFT JOIN "病棟一覧表" AS W
            ON X."転棟病棟" = W."ObjectID" AND W."Superseded" IS NULL
            WHERE X."病棟管理日誌" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."病室名", P."患者ID"');
  __d($debug, $db, $stmt, &$data, '転入転出');

  $stmt = ('SELECT X."患者", X."転室元病室", X."転室先病室",
            ' . $pex . ',
            R0."病室名" AS "転室元病室名",
            R1."病室名" AS "転室先病室名"
            FROM "病棟管理日誌・転室" AS X JOIN "患者台帳" AS P
            ON X."患者" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "病室一覧表" AS R0
            ON X."転室元病室" = R0."ObjectID" AND R0."Superseded" IS NULL
            JOIN "病室一覧表" AS R1
            ON X."転室先病室" = R1."ObjectID" AND R1."Superseded" IS NULL
            WHERE X."病棟管理日誌" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY P."患者ID"');
  __d($debug, $db, $stmt, &$data, '転室');

  $stmt = ('SELECT X."患者", X."病室", X."外泊出時間", X."帰院時刻",
            X."管理日誌欄", X."備考",
            ' . $pex . ', R."病室名"
            FROM "病棟管理日誌・外泊・外出" AS X JOIN "患者台帳" AS P
            ON X."患者" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "病室一覧表" AS R
            ON X."病室" = R."ObjectID" AND R."Superseded" IS NULL
            WHERE X."病棟管理日誌" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."病室名", P."患者ID"');
  __d($debug, $db, $stmt, &$data, '外泊・外出');

  $stmt = ('SELECT X."患者", X."病室", X."日勤帯特記事項", X."夜勤帯特記事項",
            ' . $pex . ',
            R."病室名"
            FROM "病棟管理日誌・患者管理特記事項" AS X JOIN "患者台帳" AS P
            ON X."患者" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "病室一覧表" AS R
            ON X."病室" = R."ObjectID" AND R."Superseded" IS NULL
            WHERE X."病棟管理日誌" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."病室名", P."患者ID"');
  __d($debug, $db, $stmt, &$data, '患者管理特記事項');

  $stmt = ('SELECT X."名称", X."在庫数", X."管理日誌欄"
            FROM "病棟管理日誌・在庫管理" AS X
            WHERE X."病棟管理日誌" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY X."名称"');
  __d($debug, $db, $stmt, &$data, '在庫管理');

  return $data;
}

$_lib_u_nurse_ward_manage_log_employee_work = array
  ('日勤', '早出', '遅出', '当直明け', '当直入り', '公休', '週休',
   '欠勤', '有給', '特別休暇', '出張', '研修', '忌引', '病欠');

class ward_manage_log_display0 extends simple_object_display {

  var $debug = NULL;

  var $logmsg = '';
  var $inventory_label = array('薬品名' => 2, '物品名' => 3);

  function ward_manage_log_display0($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_manage_log_employee_work;
    $this->employee_work = $_lib_u_nurse_ward_manage_log_employee_work;
    $this->prefix = $prefix;
    $this->so_config = $config;
    _lib_u_nurse_ward_manage_log_prepare_config($this->so_config);
    $this->drawer = new _lib_so_drawer($this);

    $this->chosen = array($config['Year'],
			  $config['Month'],
			  $config['Date'],
			  $config['Ward']);
    if (array_key_exists($prefix . 'history-at', $_REQUEST))
      $this->history_ix = $_REQUEST[$prefix . 'history-at'];
    else
      $this->history_ix = NULL;

    // We only need id not data.
    $db = mx_db_connect();
    $chosen = $this->chosen;
    $ward = $chosen[3];
    $dt = sprintf("%04d-%02d-%02d", $chosen[0], $chosen[1], $chosen[2]);
    $this->id = _lib_u_nurse_ward_manage_log_peek_id
      ($this, $this->so_config, $db, $dt, $ward);
  }

  function reset($id=NULL) {
    $this->history_ls = $this->history_ix = NULL;
  }

  function chosen() {
    return $this->chosen;
  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_ward_manage_log_fetch_data($this->debug, $db, $id);
  }

  function _check($v) {
    if ($v != '')
      return mx_img_url('check.png');
    else
      return '';
  }

  function _thtd($thtd, $colspan, $rowspan, $a) {
    array_splice($a, 0, 2);
    $a = join(' ', $a);
    if ($a == '') $a = '&nbsp;';

    if ($colspan) print "<$thtd colspan=\"$colspan\"";
    else print "<$thtd";
    if ($rowspan) print " rowspan=\"$rowspan\"";
    print ">"; // may add class later here
    print $a;
    print "</$thtd>\n";
  }

  function _th($colspan, $rowspan) {
    $a = func_get_args();
    $this->_thtd('th', $colspan, $rowspan, $a);
  }

  function _td($colspan, $rowspan) {
    $a = func_get_args();
    $this->_thtd('td', $colspan, $rowspan, $a);
    return;

    $a = func_get_args();
    array_splice($a, 0, 2);
    $a = join(' ', $a);
    if ($a == '') $a = '&nbsp;';

    if ($colspan) print "<td colspan=\"$colspan\"";
    else print "<td";
    if ($rowspan) print " rowspan=\"$rowspan\"";
    print ">"; // may add class later here
    print $a;
    print "</td>\n";
  }

  function nps($v, $unit='名') { // Num People String
    if ($v) return "$v$unit";
    if ($v != '' && $v == 0)
      return "0$unit";
    return '';
  }

  function draw() {

    if ($this->debug) {
      print "<!--\n";
      var_dump($data);
      print "-->\n";
    }

    if (! is_null($this->id))
      $this->history();

    if (is_null($this->history_ix)) {
      // Showing the latest.
      $id = $this->id;
      if (is_array($this->history_ls) && count($this->history_ls)) {
	// Let's compare the latest with one before since it exists.
	$cid = $this->history_ls[count($this->history_ls)-1]['ObjectID'];
	$hdata =& $this->fetch_data($cid);
      }
      else
	$hdata = NULL;
      $chosen = $this->chosen;
      $ward = $chosen[3];
      $db = mx_db_connect();
      $dt = sprintf("%04d-%02d-%02d", $chosen[0], $chosen[1], $chosen[2]);
      $data = _lib_u_nurse_ward_manage_log_get_by_dt_ward
	(&$this, $this->so_config, $db, $dt, $ward);
    }
    else {
      // Showing something from history.
      $id = $this->history_ls[$this->history_ix]['ObjectID'];
      mx_formi_hidden($this->prefix . 'history-at', $this->history_ix);

      // We are lookinig at history item $this->history_ix (0 being the
      // oldest).  Fetch one after that one so that we can compare what
      // got overwritten.
      $cid = $this->history_ix + 1;
      if (count($this->history_ls) <= $cid)
	$cid = $this->id; // comparison against the current
      else
	// comparison against the one after
	$cid = $this->history_ls[$cid]['ObjectID'];
      $data = $this->fetch_data($id);
      $hdata = $this->fetch_data($cid);
      // we are going to show $data and give comparison to $hdata
    }

    if ($this->logmsg != '') {
      print "<!--\n";
      print $this->logmsg;
      print "-->\n";
    }

    if (is_null($this->history_ix))
      print '<table class="random-format">';
    else
      print '<table class="random-format-historical">';

    $this->draw_summary_rows($data, $hdata);

    $this->draw_employee_rows($data, $hdata);

    $this->draw_external_transfer_rows($data, $hdata);

    $this->draw_transfer_rows($data, $hdata);

    $this->draw_temporary_out_rows($data, $hdata);

    $this->draw_patient_notes_rows($data, $hdata);

    $this->draw_inventory_rows($data, $hdata);

    $this->draw_ward_notes_rows($data, $hdata);

    $this->draw_superseded_notes($data, $hdata);

    print "</table>\n";
  }

  function draw_superseded_notes($data, $hdata) {
    $d = $data['全般'][0];
    if (! is_null($hdata)) {
      $h = $hdata['全般'][0];
      $changed = ($d['CreatedBy'] != $h['CreatedBy']);
    }
    print '<tr><td colspan="2">記録者</td>';
    if ($changed) print '<td colspan="2" class="changed">';
    else print '<td colspan="2">';
    $this->dx_user(array(), $d['CreatedBy'], $changed);
    print '</td>';
    if (is_null($this->history_ix))
      print '<td colspan="5">&nbsp;</td>';
    else {
      print '<td colspan="2">変更タイムスタンプ</td><td colspan="3">';
      print htmlspecialchars(mx_format_timestamp($d['Superseded']));
    }
    print "</tr>\n";
  }

  function number_of_people_in_categories($d) {
    $a = func_get_args();
    array_shift($a);
    $s = $found_non_empty = 0;
    foreach ($a as $k) {
      $tdk = trim($d[$k]);
      $s += $tdk;
      if (!$found_non_empty && $tdk != '')
	$found_non_empty = 1;
    }
    if ($found_non_empty)
      return $s;
    return NULL;
  }

  function draw_summary_rows($data, $hdata) {

    $d = $data['全般'][0];

    print '<tr>';
    $this->_td(4, 0, '日付:', $d['日付']);
    $this->_td(5, 0, '病棟管理日誌:', $d['病棟名']);
    print "</tr>\n";

    print '<tr>';
    $this->_th(2, 0, '空床部屋');
    $this->_th(2, 0, '入院');
    $this->_th(3, 0, '転院・転帰');
    $this->_th(0, 0, '転入');
    $this->_th(0, 0, '転出');
    print "</tr>\n";

    $discharge_sum = $this->number_of_people_in_categories
      ($d, "軽快","転院","死亡","他の転帰");
    $hospitalized_sum = $this->number_of_people_in_categories
      ($d, '入院・日', '入院・夜');

    print '<tr>';
    $this->_td(2, 2, $this->nps($d['空床部屋'], '室'));
    $this->_td(0, 2, $this->nps($hospitalized_sum));
    $this->_td(0, 0, '日:', $this->nps($d['入院・日']));
    $this->_td(1, 2, $this->nps($discharge_sum));
    $this->_td(0, 0, '軽快:', $this->nps($d["軽快"]));
    $this->_td(0, 0, '死亡:', $this->nps($d["死亡"]));
    $this->_td(0, 2, $this->nps($d['転入']));
    $this->_td(0, 2, $this->nps($d['転出']));
    print "</tr>\n";

    print '<tr>';
    $this->_td(0, 0, '夜:', $this->nps($d['入院・夜']));
    $this->_td(0, 0, '転院:', $this->nps($d["転院"]));
    $this->_td(0, 0, '他:', $this->nps($d["他の転帰"]));
    print "</tr>\n";

    $current = $this->number_of_people_in_categories
      ($d, '担送', '護送', '独歩');

    print '<tr>';
    $this->_td(4, 0, '許可病床数', $this->nps($d['許可病床数'], '床'));
    $this->_td(5, 0, '現在数', $this->nps($current));
    print "</tr>\n";

    print '<tr>';
    $this->_td(3, 0, '担送', $this->nps($d['担送']));
    $this->_td(3, 0, '護送', $this->nps($d['護送']));
    $this->_td(3, 0, '独歩', $this->nps($d['独歩']));
    print "</tr>\n";

    print '<tr>';
    $this->_th(9, 0, '看護度別患者数');
    print "</tr>\n";
    foreach (array('A', 'B', 'C') as $c0) {
      print '<tr>';
      foreach (array('1' => 2, '2' => 2, '3' => 2, '4' => 3) as $c1 => $cs)
	$this->_td($cs, 0, "$c0$c1", $this->nps($d["$c0$c1"]));
      print "</tr>\n";
    }

  }

  function draw_ward_notes_rows($data, $hdata) {
    print '<tr>';
    $this->_th(9, 0, '備考');
    print "</tr>\n";
    print '<tr>';
    $this->_td(9, 0, mx_html_paragraph($data['全般'][0]['備考']));
    print "</tr>\n";
  }

  function draw_inventory_rows($data, $hdata) {
    if (count($data['在庫管理']) == 0) return;
    $tr = $data['在庫管理'];

    print '<tr>';
    $this->_th(9, 0, '在庫管理');
    print "</tr>\n";

    $d = array();
    foreach ($tr as $e) {
      $d[$e['管理日誌欄']][] = $e;
    }
    $lim = 0;
    foreach ($d as $col => $row) {
      if ($lim < count($row)) $lim = count($row);
    }

    print "<tr>";
    foreach ($this->inventory_label as $label => $colspan) {
      $this->_td($colspan, 0, $label);
      $this->_td(2, 0, '在庫数');
    }
    print "</tr>\n";

    for ($ix = 0; $ix < $lim; $ix++) {
      print "<tr>";
      $iy = 0;
      foreach ($this->inventory_label as $label => $colspan) {
	if (count($d[$iy]) <= $ix) {
	  $this->_td($colspan, 0, '');
	  $this->_td(2, 0, '');
	} else {
	  $e = $d[$iy][$ix];
	  $this->_td($colspan, 0, htmlspecialchars($e['名称']));
	  $this->_td(2, 0, htmlspecialchars($e['在庫数']));
	}
	$iy++;
      }
      print "</tr>\n";
    }
  }

  function draw_external_transfer_rows($data, $hdata) {
    $ih = array(); // 入院
    $it = array(); // 転入
    $oh = array(); // 退院
    $ot = array(); // 転出
    foreach ($data['転入転出'] as $e) {
      switch ($e['入出']) {
      case 'i': $it[] = $e; break;
      case 'I': $ih[] = $e; break;
      case 'o': $ot[] = $e; break;
      case 'O': $oh[] = $e; break;
      }
    }
    $this->draw_inout_row('入院', '備考', $ih);
    $this->draw_inout_row('転入', '転入病棟名', $it);
    $this->draw_inout_row('転出', '転出病棟名', $ot);
    $this->draw_inout_row('退院', '備考', $oh);
  }

  function draw_employee_rows($data, $hdata) {
    print '<tr>';
    $this->_th(9, 0, '職員');
    print "</tr>\n";

    foreach ($this->employee_work as $ix => $label) {
	    print '<tr><th colspan="2">';
	    print htmlspecialchars($label);
	    print '</th>';
	    $a = '';
	    foreach ($data['職員'] as $e) {
		    if ($e['管理日誌欄'] == $ix)
			    $a = $a . $e['職員名'] . " ";
	    }
	    $this->_td(7, 0, $a);
	    print "</tr>\n";
    }
  }

  function draw_patient_notes_rows($data, $hdata) {
    if (0 == count($data['患者管理特記事項'])) return;

    $tr = $data['患者管理特記事項'];
    print '<tr>';
    $this->_th(9, 0, '患者管理特記事項');
    print "</tr>\n";

    print '<tr>';
    $this->_td(1, 0, '部屋番号');
    $this->_td(1, 0, '患者名');
    $this->_td(1, 0, '年齢');
    $this->_td(3, 0, '日勤帯特記事項');
    $this->_td(3, 0, '夜勤帯特記事項');
    print "</tr>\n";

    foreach ($tr as $e) {
      print '<tr>';
      $this->_td(1, 0, $e['病室名']);
      $this->_td(1, 0, $e['患者名']);
      $this->_td(1, 0, $e['患者年齢']);
      $this->_td(3, 0, htmlspecialchars($e['日勤帯特記事項']));
      $this->_td(3, 0, htmlspecialchars($e['夜勤帯特記事項']));
      print "</tr>\n";
    }
  }

  function draw_temporary_out_rows($data, $hdata) {
    if (0 == count($data['外泊・外出'])) return;

    $tr = $data['外泊・外出'];
    print '<tr>';
    $this->_th(9, 0, '外泊・外出');
    print "</tr>\n";

    print '<tr>';
    $this->_td(1, 0, '部屋番号');
    $this->_td(1, 0, '患者名');
    $this->_td(1, 0, '泊・出');
    $this->_td(2, 0, '外泊出時間');
    $this->_td(2, 0, '帰院時間');
    $this->_td(2, 0, '備考');
    print "</tr>\n";

    foreach ($tr as $e) {
      switch ($e['管理日誌欄']) {
      case 0: $stay_shape = '外泊'; break;
      case 1: $stay_shape = '外出'; break;
      }
      print '<tr>';
      $this->_td(1, 0, $e['病室名']);
      $this->_td(1, 0, $e['患者名']);
      $this->_td(1, 0, $stay_shape);
      $this->_td(2, 0, $e['外泊出時間']);
      $this->_td(2, 0, $e['帰院時刻']);
      $this->_td(2, 0, htmlspecialchars($e['備考']));
      print "</tr>\n";
    }
  }

  function draw_transfer_rows($data, $hdata) {
    if (0 == count($data['転室'])) return;

    $tr = $data['転室'];
    print '<tr>';
    $this->_th(9, 0, '転室');
    print "</tr>\n";

    print '<tr>';
    $this->_td(2, 0, '患者名');
    $this->_td(2, 0, '部屋番号→部屋番号');
    $this->_td(2, 0, '患者名');
    $this->_td(3, 0, '部屋番号→部屋番号');
    print "</tr>\n";

    $lim = count($tr);
    if ($lim % 2) { $lim++; }
    for ($ix = 0; $ix < $lim; $ix += 2) {
      print '<tr>';

      $n = $tr[$ix]['患者名'];
      $t = $tr[$ix]['転室元病室名'] . '→' .  $tr[$ix]['転室先病室名'];
      $this->_td(2, 0, $n);
      $this->_td(2, 0, $t);

      if ($ix+1 < count($tr)) {
	$n = $tr[$ix+1]['患者名'];
	$t = $tr[$ix+1]['転室元病室名'] . '→' .  $tr[$ix+1]['転室先病室名'];
      } else {
	$n = $t = '&nbsp;';
      }
      $this->_td(2, 0, $n);
      $this->_td(3, 0, $t);

      print "</tr>\n";
    }
  }

  function draw_inout_row($label, $foo, $data) {
    if (count($data)) {
      print '<tr>';
      $this->_th(9, 0, $label);
      print "</tr>\n";

      print '<tr>';
      $this->_td(1, 0, "部屋番号");
      $this->_td(1, 0, "患者番号");
      $this->_td(1, 0, "患者名");
      $this->_td(1, 0, "年齢");
      $this->_td(5, 0, $foo);
      print "</tr>\n";

      foreach ($data as $e) {
	$comment = $e['転棟病棟名'];
	if ($e['備考'])
	  $comment = $e['備考'];
	print '<tr>';
	$this->_td(1, 0, $e['病室名']);
	$this->_td(1, 0, $e['患者ID']);
	$this->_td(1, 0, $e['患者名']);
	$this->_td(1, 0, $e['患者年齢']);
	$this->_td(5, 0, htmlspecialchars($comment));
	print "</tr>\n";
      }
    }
  }

}

class ward_manage_log_display extends ward_manage_log_display0 {
}

class ward_manage_log_edit extends simple_object_edit {

  function ward_manage_log_edit($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_manage_log_employee_work;
    $this->employee_work = $_lib_u_nurse_ward_manage_log_employee_work;
    $this->_Subpicker = NULL;
    if (is_null($config)) { $config = array(); }
    _lib_u_nurse_ward_manage_log_prepare_config(&$config);
    simple_object_edit::simple_object_edit($prefix, $config);
  }

  function resync() {
    $this->data = $this->fetch_data($this->id);
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function edit($chosen) {

    $db = mx_db_connect();
    $year = $chosen[0];
    $month = $chosen[1];
    $date = $chosen[2];
    $ward = $chosen[3];

    $dt = sprintf("%04d-%02d-%02d", $year, $month, $date);
    $this->data = _lib_u_nurse_ward_manage_log_get_by_dt_ward
      ($this, $this->so_config, $db, $dt, $ward);
    $this->id = $this->data['ObjectID'];

    if ($this->debug) {
      print "<!-- EDIT\n";
      var_dump($this);
      print "-->\n";
    }

    $this->_Subpicker = NULL;
    $this->chosen = 1;
  }

  function chosen() { return $this->chosen; }

  function fetch_data($id) {
    $db = mx_db_connect();
    $data = _lib_u_nurse_ward_manage_log_fetch_data($this->debug, $db, $id);
    $dd = array('CreatedBy' => $data['CreatedBy'],
		'ObjectID' => $data['ObjectID']);

    $page_num = 0;
    foreach ($this->so_config['Pages'] as $page_name => $cfg) {
      $it = array();
      foreach ($data[$page_name] as $row) {
	$i = array();
	foreach ($cfg as $colname)
	  if (! is_null($row[$colname]))
	    $i[$colname] = trim($row[$colname]);
	  else
	    $i[$colname] = NULL;
	$it[] = $i;
      }
      $dd[$page_name] = $it;
      $page_num++;
    }
    $this->annotate_row_data(&$dd);
    return $dd;
  }

  function annotate_row_data(&$d) {
    $ws_null_col = array('全般' => array('師長', '部長'),
			 '転入転出' => array('転棟病棟'),
			 '外泊・外出' => array('外泊出時間', '帰院時刻'),
			 );
    // First sanitize
    foreach ($this->so_config['Pages'] as $page => $cfg)
      foreach ($d[$page] as $ix => $row)
	foreach ($cfg as $col) {
	  if (! is_null($row[$col]))
	    $d[$page][$ix][$col] = trim($row[$col]);
	}

    // Then nullify
    foreach ($ws_null_col as $page => $cfg)
      foreach ($d[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if ($row[$col] == '')
	    $d[$page][$ix][$col] = NULL;
  }

  function annotate_form_data() {
    global
      $_lib_u_nurse_ward_employee_pick_cfg,
      $_lib_u_nurse_ward_patient_cfg,
      $_lib_u_nurse_ward_room_cfg;

    if (array_key_exists($this->prefix . 'page-to', $_REQUEST))
      $this->page = $_REQUEST[$this->prefix . 'page-to'];
    elseif (array_key_exists($this->prefix . 'page', $_REQUEST))
      $this->page = $_REQUEST[$this->prefix . 'page'];
    else
      $this->page = 0;
    $page_num = 0;
    foreach ($this->so_config['Pages'] as $page_name => $cfg) {
      $num_items = $_REQUEST[$this->prefix . $page_num . '-total'];
      $data = array();
      for ($ix = 0; $ix < $num_items; $ix++) {
	$it = array();
	foreach ($cfg as $colname) {
	  $cnx = mx_form_encode_name($colname);
	  $it[$colname] = $_REQUEST[$this->prefix . $page_num .
				    '-data-' . $ix . '-' . $cnx];
	}
	$data[] = $it;
      }
      $this->data[$page_name] = $data;
      $page_num++;
    }

    // Handle subpicks, addrows, etc.
    // Note that this should be done *after* the above code slurped
    // the values into $this->data[]; otherwise the row numbers will
    // become inconsistent.

    if (array_key_exists($this->prefix . 'AddRow', $_REQUEST)) {
      $addrow = $_REQUEST[$this->prefix . 'AddRow'];
      $add_switch = array('2-I' => array('転入転出', array('入出' => 'I')),
			  '2-i' => array('転入転出', array('入出' => 'i')),
			  '2-o' => array('転入転出', array('入出' => 'o')),
			  '2-O' => array('転入転出', array('入出' => 'O')),
			  '3' => array('転室', array()),
			  '4-0' => array('外泊・外出',
					 array('管理日誌欄' => 0)),
			  '4-1' => array('外泊・外出',
					 array('管理日誌欄' => 1)),
			  '5' => array('患者管理特記事項', array()),
			  '6-0' => array('在庫管理', array('管理日誌欄' => 0)),
			  '6-1' => array('在庫管理', array('管理日誌欄' => 1)),
			  );
      if (array_key_exists($addrow, $add_switch)) {
	$as = $add_switch[$addrow];
	$this->data[$as[0]][] = $as[1];
      }
    }

    if (array_key_exists($this->prefix . 'DelRow', $_REQUEST)) {
      $delrow = $_REQUEST[$this->prefix . 'DelRow'];
      $delkey = substr($delrow, 0, 2);
      $del_switch = array('2-' => '転入転出',
			  '3-' => '転室',
			  '4-' => '外泊・外出',
			  '5-' => '患者管理特記事項',
			  '6-' => '在庫管理');
      if (array_key_exists($delkey, $del_switch)) {
	$ds = $del_switch[$delkey];
	$ix = substr($delrow, 2);
	array_splice($this->data[$ds], $ix, 1); // remove 1 elt
      }
    }

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST))
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];

    if ($subpick) {
      if (substr($subpick, 0, 2) == '1-') {
	// 職員
	$slot = substr($subpick, 2);
	$ss = array();
	foreach ($this->data['職員'] as $row)
	  if ($row['管理日誌欄'] == $slot)
	    $ss[] = mx_form_escape_key(array($row['職員'], $row['職員名']));
	$subconfig = $_lib_u_nurse_ward_employee_pick_cfg;
	$subconfig['Ward'] = $this->data['全般'][0]['病棟'];
	$subconfig['Select'] = $ss;
	$subconfig['Title'] = '職員('.$this->employee_work[$slot].')を選択';
	$this->_Subpicker = new ward_employee_pick($this->prefix . 'wep-',
						   $subconfig);

	$chosen = $this->_Subpicker->chosen();
	if (is_array($chosen)) {
	  $this->_Subpicker = NULL;
	  $d = array();
	  foreach ($this->data['職員'] as $row)
	    if ($row['管理日誌欄'] != $slot)
	      $d[] = $row;
	  foreach ($chosen as $v) {
	    $a = mx_form_unescape_key($v);
	    $d[] = array('職員' => $a[0],
			 '職員名' => $a[1],
			 '管理日誌欄' => $slot);
	  }
	  $this->data['職員'] = $d;
	}
      }
      elseif (substr($subpick, 0, 4) == '2-0-' ||
	      substr($subpick, 0, 4) == '3-0-' ||
	      substr($subpick, 0, 4) == '4-0-' ||
	      substr($subpick, 0, 4) == '5-0-') {
	// 転入転出（患者） or 転室（患者）or 特記事項（患者）
	// or 外泊・外出（患者）
	$ix = substr($subpick, 4);
	$ty = substr($subpick, 0, 1);
	$idl = array('患者', '患者ID', '患者名', '患者年齢', '病室', '病室名');
	switch ($ty) {
	case '2': $page = '転入転出'; break;
	case '3':
	  $idl = array('患者', '患者ID', '患者名', '患者年齢',
		       '転室元病室', '転室元病室名');
	  $page = '転室'; break;
	case '4': $page = '外泊・外出'; break;
	case '5': $page = '患者管理特記事項'; break;
	}
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_patient_cfg;
	$subconfig['Ward'] = $this->data['全般'][0]['病棟'];
	$this->_Subpicker = new list_of_ward_patients($this->prefix . 'lwp-',
						      $subconfig);
	$this->_Subpicker->Title = "患者名を設定";
	$a = array();
	foreach ($idl as $iy => $col)
	  $a[] = $d[$col];
	$this->_Subpicker->Original = mx_form_escape_key($a);
	if ($this->_Subpicker->changed() &&
	    $this->_Subpicker->chosen()) {
	  $a = mx_form_unescape_key($this->_Subpicker->chosen());
	  $this->_Subpicker = NULL;
	  foreach ($idl as $iy => $col)
	    $d[$col] = $a[$iy];
	}
	unset($d);
      }
      elseif (substr($subpick, 0, 4) == '2-1-' ||
	      substr($subpick, 0, 4) == '3-1-' ||
	      substr($subpick, 0, 4) == '3-2-' ||
	      substr($subpick, 0, 4) == '4-1-' ||
	      substr($subpick, 0, 4) == '5-1-') {
	// 転入転出（病室）or
	// 転室（転室元病室）or 転室（転室先病室）or
	// 特記事項（病室）or 外泊・外出（病室）
	$ix = substr($subpick, 4);
	$ty = substr($subpick, 0, 4);
	$idl = array('病室', '病室名');
	switch ($ty) {
	case '2-1-': $page = '転入転出'; break;
  	case '3-1-':
	  $idl = array('転室元病室', '転室元病室名');
	  $page = '転室';
	  break;
  	case '3-2-':
	  $idl = array('転室先病室', '転室先病室名');
	  $page = '転室';
	  break;
	case '4-1-': $page = '外泊・外出'; break;
	case '5-1-': $page = '患者管理特記事項'; break;
	}
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_room_cfg;
	$subconfig['Ward'] = $this->data['全般'][0]['病棟'];
	$this->_Subpicker = new list_of_ward_rooms($this->prefix . 'lwr-',
						   $subconfig);
	$this->_Subpicker->Title = "病室を選択";
	$a = array();
	foreach ($idl as $iy => $col)
	  $a[] = $d[$col];
	$this->_Subpicker->Original = mx_form_escape_key($a);
	if ($this->_Subpicker->changed() &&
	    $this->_Subpicker->chosen()) {
	  $a = mx_form_unescape_key($this->_Subpicker->chosen());
	  $this->_Subpicker = NULL;
	  foreach ($idl as $iy => $col)
	    $d[$col] = $a[$iy];
	}
	unset($d);
      }
      elseif (substr($subpick, 0, 4) == '2-2-') {
	// 転入転出（転棟病棟）
	$ix = substr($subpick, 4);
	$idl = array('転棟病棟', '転棟病棟名');
	$page = '転入転出';
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_cfg;
	$this->_Subpicker = new list_of_wards($this->prefix . 'lww-',
					      $subconfig);
	$this->_Subpicker->Title = "転棟病棟を選択";
	$a = array();
	foreach ($idl as $iy => $col)
	  $a[] = $d[$col];
	$this->_Subpicker->Original = mx_form_escape_key($a);
	if ($this->_Subpicker->changed() &&
	    $this->_Subpicker->chosen()) {
	  $a = mx_form_unescape_key($this->_Subpicker->chosen());
	  $this->_Subpicker = NULL;
	  foreach ($idl as $iy => $col)
	    $d[$col] = $a[$iy];
	}
	unset($d);
      }
    }

    // Yuck.
    foreach (array_merge(array("日付",
			       "空床部屋",
			       "入院・日", "入院・夜", "軽快", "転院",
			       "死亡", "他の転帰", "転入", "転出",
			       "担送", "護送", "独歩"),
			 _lib_u_nurse_ward_manage_log_A1C4()) as $asc) {
      $o = $v = $this->data['全般'][0][$asc];
      if (! is_array($v) && ! is_null($v)) {
	$v = mb_convert_kana($v, 'as', 'euc');
	if ($v != $o) {
	  $this->dbglog("Kana $o => $v\n");
	  $this->data['全般'][0][$asc] = $v;
	}
      }
    }

    for ($ix = 0; $ix < count($this->data['外泊・外出']); $ix++) {
      foreach (array("外泊出時間", "帰院時刻") as $asc) {
	$o = $v = $this->data['外泊・外出'][$ix][$asc];
	$v = mb_convert_kana($v, 'as', 'euc');
	if ($v != $o) {
	  $this->dbglog("Kana $o => $v\n");
	  $this->data['外泊・外出'][$ix][$asc] = $v;
	}
      }
    }

    $this->annotate_row_data(&$this->data);
  }

  function draw_body() {

    $page = $this->page;
    $data = $this->data;
    $config = $this->so_config;

    // Draw flippage and propagate hidden
    print "<table class=\"flippage\"><tr>";
    $page_num = -1;
    foreach ($this->so_config['Pages'] as $page_name => $cfg) {
      $page_num++;
      $a = $this->data[$page_name];
      $num_items = count($a);
      mx_formi_hidden($this->prefix . $page_num . '-total', $num_items);
      if ($page_num == $page) {
	print "<td class=\"focused ltcorner\">&nbsp;</td>";
	print "<td class=\"focused\">&nbsp;";
	print $page_name;
	mx_formi_hidden($this->prefix . 'page', $page_num);
	print "&nbsp;</td><td class=\"focused rtcorner\">&nbsp;</td>";
      } else {
	print "<td class=\"unfocused ltcorner\">&nbsp;</td>";
	print "<td class=\"unfocused\">";
	if ($this->_Subpicker)
	  print $page_name;
	else
	  mx_formi_submit($this->prefix . 'page-to', $page_num, $page_name);

	for ($ix = 0; $ix < $num_items; $ix++) {
	  foreach ($cfg as $colname) {
	    $cnx = mx_form_encode_name($colname);
	    mx_formi_hidden(($this->prefix . $page_num . '-data-' .
			     $ix . '-' . $cnx), $a[$ix][$colname]);
	  }
	}

	print "</td><td class=\"unfocused rtcorner\">&nbsp;</td>";
      }
    }
    print "</tr></table>\n";

    // Draw shown page.
    $draw_page_method = 'draw_page_' . $page;
    $this->$draw_page_method();

    if ($this->_Subpicker) {
      print "<hr />\n";
      mx_formi_hidden($this->prefix . 'Subpick',
		      $_REQUEST[$this->prefix . 'Subpick']);
      $this->_Subpicker->draw();
    }
    else {
      print "<br />\n";
      mx_formi_submit($this->prefix . 'commit', '編集完了');
      mx_formi_submit($this->prefix . 'rollback', '編集中止');
    }

  }

  function dx_hidden($pfx, $p, $colname) {
    $cnx = mx_form_encode_name($colname);
    mx_formi_hidden($pfx . $cnx, $p[$colname]);
  }

  function dx_ro($evenodd, $pfx, $p, $colname, $label=NULL) {
    if (is_null($label)) $label = $colname;
    print "<tr class=\"$evenodd\"><th>$label</th><td>" . $p[$colname];
    $cnx = mx_form_encode_name($colname);
    mx_formi_hidden($pfx . $cnx, $p[$colname]);
    print "</td></tr>\n";
  }

  function dx_checkbox($evenodd, $pfx, $p, $colname, $label=NULL) {
    if (is_null($label)) $label = $colname;
    print "<tr class=\"$evenodd\"><th>$label</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      if ($value) print "x";
    }
    else
	mx_formi_checkbox($pfx . $cnx, $p[$colname]);
    print "</td></tr>\n";
  }

  function dx_text($evenodd, $pfx, $p, $colname, $option=NULL) {
    print "<tr class=\"$evenodd\"><th>$colname</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      print htmlspecialchars($p[$colname]);
    }
    else
      mx_formi_text($pfx . $cnx, $p[$colname], $option);
    print "</td></tr>\n";
  }

  function dx_textarea($evenodd, $pfx, $p, $colname, $option=NULL) {
    print "<tr class=\"$evenodd\"><th>$colname</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      print htmlspecialchars($p[$colname]);
    }
    else
      mx_formi_textarea($pfx . $cnx, $p[$colname], $option);
    print "</td></tr>\n";
  }

  function dx_enum($evenodd, $pfx, $p, $colname, $choice, $label=NULL) {
    if (is_null($label)) $label = $colname;
    print "<tr class=\"$evenodd\"><th>$label</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      print htmlspecialchars($choice[$p[$colname]]);
    } else
      mx_formi_select($pfx . $cnx, $p[$colname], $choice);
    print "</td></tr>\n";
  }

  function draw_page_0() {
    // "日付", "病棟", "担送", "護送",
    // "独歩", "A1".."C4", "備考", "空床部屋",
    // "入院・日", "入院・夜", "軽快", "転院",
    // "死亡", "他の転帰", "転入", "転出",
    // "師長", "部長", "病棟名",
    // "許可病床数",
    // "師長名", "部長名"
    $pfx = $this->prefix . 0 . '-data-0-';
    $p = $this->data['全般'][0];
    $oe = array(0 => 'e', 1 => 'o');
    $oex = 1;

    $ime_opt = array('ime' => 'disabled');

    print '<table class="listofstuff">';

    $this->dx_ro($oe[$oex = 1 - $oex], $pfx, $p, '日付');
    $this->dx_ro($oe[$oex = 1 - $oex], $pfx, $p, '病棟名');

    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '空床部屋', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '入院・日', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '入院・夜', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '軽快', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '転院', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '死亡', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '他の転帰', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '転入', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '転出', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '担送', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '護送', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '独歩', $ime_opt);

    print "<tr><th colspan=\"2\">看護度別患者数</th><tr>\n";
    foreach (array('A', 'B', 'C') as $c0)
      foreach (array('1', '2', '3', '4') as $c1)
	$this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, "$c0$c1", $ime_opt);

    $this->dx_textarea($oe[$oex = 1 - $oex], $pfx, $p, '備考');

    print "</table>\n";
    $this->dx_hidden($pfx, $p, '病棟');
    $this->dx_hidden($pfx, $p, '許可病床数');
    $this->dx_hidden($pfx, $p, '師長');
    $this->dx_hidden($pfx, $p, '部長');
    $this->dx_hidden($pfx, $p, '師長名');
    $this->dx_hidden($pfx, $p, '部長名');
  }

  function patient_label($p) {
    if (trim($p['患者']) != '')
      return sprintf("%s %s (%s 歳)",
		     $p['患者ID'], $p['患者名'], $p['患者年齢']);
    else
      return "(未設定)";
  }

  function draw_page_1() {
    // '職員' => array("職員", "職員名", "管理日誌欄"),
    $cfg = $this->so_config['Pages']['職員'];
    $pfx = $this->prefix . 1 . '-data-';
    $p = $this->data['職員'];
    print '<table class="listofstuff">';
    foreach ($this->employee_work as $slot => $label) {
      $evenodd = ($slot % 2) ? "o" : "e";
      print "<tr class=\"$evenodd\"><th>";
      if ($this->_Subpicker)
	print $label;
      else
	mx_formi_submit($this->prefix . 'Subpick', '1-' . $slot,
			"<span class=\"link\">$label</span>");
      print "</th><td>";
      $it = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	$row = $p[$ix];
	if ($row['管理日誌欄'] == $slot) {
	  if ($it++) print "<br />";
	  print $row['職員名'];
	  foreach ($cfg as $colname) {
	    $cnx = mx_form_encode_name($colname);
	    mx_formi_hidden(($pfx . $ix . '-' . $cnx), $row[$colname]);
	  }
	}
      }
      print "</td></tr>\n";
    }
    print "</table>\n";
  }

  function draw_page_2() {
    // '転入転出' => array("患者", "病室", "入出", "転棟病棟", "備考",
    // "患者名", "患者ID", "患者年齢",
    // "転棟病棟名", "病室名"),
    $cfg = $this->so_config['Pages']['転入転出'];
    $pfx = $this->prefix . 2 . '-data-';
    $p = $this->data['転入転出'];

    print '<table class="listofstuff">';
    $iy = 0;
    foreach (array('I' => '入院', 'i' => '転入',
		   'o' => '転出', 'O' => '退院') as $slot => $slotname) {
      print "<tr><th colspan=\"2\">$slotname</th></tr>\n";
      for ($ix = 0; $ix < count($p); $ix++) {
	if ($p[$ix]['入出'] != $slot) continue;
	$evenodd = ($iy % 2) ? "o" : "e";

	print "<tr class=\"$evenodd\"><th>患者</th><td>";
	foreach (array('患者', '患者ID', '患者名', '患者年齢',
		       '病室', '病室名', '入出',
		       '転棟病棟', '転棟病棟名') as $col) {
	  $cnx = mx_form_encode_name($col);
	  mx_formi_hidden(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	}
	$label = $this->patient_label($p[$ix]);
	if ($this->_Subpicker)
	  print $label;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '2-0-' . $ix,
			  "<span class=\"link\">$label</span>");
	print "</td></tr>\n";

	$label = '病室';
	print "<tr class=\"$evenodd\"><th>$label</th><td>";
	$value = trim($p[$ix][$label . '名']);
	if ($value == '')
	  $value = '(未設定)';
	if ($this->_Subpicker)
	  print $value;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '2-1-' . $ix,
			  "<span class=\"link\">$value</span>");
	print "</td></tr>\n";

	if ($slot == 'i' || $slot == 'o') {
	  $label = '転棟病棟';
	  $show = ($slot == 'i') ? '転入病棟' : '転出病棟';
	  print "<tr class=\"$evenodd\"><th>$show</th><td>";
	  $value = trim($p[$ix][$label . '名']);
	  if ($value == '')
	    $value = '(未設定)';
	  if ($this->_Subpicker)
	    print $value;
	  else
	    mx_formi_submit($this->prefix . 'Subpick', '2-2-' . $ix,
			    "<span class=\"link\">$value</span>");
	  print "</td></tr>\n";
	}
	else {
	  $col = '備考';
	  print "<tr class=\"$evenodd\"><th>$col</th><td>";
	  $cnx = mx_form_encode_name($col);
	  mx_formi_textarea(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	  print "</td></tr>\n";
	}
	if (! $this->_Subpicker) {
	  print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	  mx_formi_submit($this->prefix . 'DelRow', '2-' . $ix,
			  "<span class=\"link\">この項目を削除</span>");
	  print "</td></tr>\n";
	}
	$iy++;
      }
      if (! $this->_Subpicker) {
	print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'AddRow', '2-' . $slot,
			"<span class=\"link\">項目を追加</span>");
	print "</td></tr>\n";
      }
    }
    print "</table>\n";
  }

  function draw_page_3() {
    // '転室' => array("患者", "転室元病室", "転室先病室",
    // "患者名", "患者ID", "患者年齢",
    // "転室元病室名", "転室先病室名"),
    $cfg = $this->so_config['Pages']['転室'];
    $pfx = $this->prefix . 3 . '-data-';
    $p = $this->data['転室'];

    print '<table class="listofstuff">';
    for ($ix = 0; $ix < count($p); $ix++) {
      $evenodd = ($ix % 2) ? "o" : "e";

      print "<tr class=\"$evenodd\"><th>患者</th><td>";
      foreach (array('患者', '患者ID', '患者名', '患者年齢',
		     '転室元病室', '転室元病室名',
		     '転室先病室', '転室先病室名') as $col) {
	$cnx = mx_form_encode_name($col);
	mx_formi_hidden(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
      }
      $label = $this->patient_label($p[$ix]);
      if ($this->_Subpicker)
	print $label;
      else
	mx_formi_submit($this->prefix . 'Subpick', '3-0-' . $ix,
			"<span class=\"link\">$label</span>");
      print "</td></tr>\n";

      foreach (array(1 => '転室元', 2 => '転室先') as $slot => $col) {
	$label = $col . '病室';
	print "<tr class=\"$evenodd\"><th>$label</th><td>";
	$value = trim($p[$ix][$label . '名']);
	if ($value == '')
	  $value = '(未設定)';
	if ($this->_Subpicker)
	  print $value;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '3-' . $slot . '-' . $ix,
			  "<span class=\"link\">$value</span>");
	print "</td></tr>\n";
      }
      if (! $this->_Subpicker) {
	print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'DelRow', '3-' . $ix,
			"<span class=\"link\">この項目を削除</span>");
	print "</td></tr>\n";
      }
    }
    if (! $this->_Subpicker) {
      print "<tr><td colspan=\"2\">";
      mx_formi_submit($this->prefix . 'AddRow', 3,
		      "<span class=\"link\">項目を追加</span>");
      print "</td></tr>\n";
    }
    print "</table>\n";
  }

  function draw_page_4() {
    // '外泊・外出' => array("患者", "病室", "管理日誌欄",
    // "外泊出時間", "帰院時刻",
    // "備考","患者名", "患者ID", "患者年齢", "病室名"),
    $cfg = $this->so_config['Pages']['外泊・外出'];
    $pfx = $this->prefix . 4 . '-data-';
    $p = $this->data['外泊・外出'];

    $this->__draw_page_sub_45($cfg, $pfx, $p, 4,
			      array('外泊出時間' => 'text-i',
				    '帰院時刻' => 'text-i',
				    '備考' => 'textarea'),
			      array('管理日誌欄',
				    array(0 => '外泊',
					  1 => '外出')));
  }

  function __draw_page_sub_45($cfg, $pfx, $p, $pg,
			      $xtra, $subloop=NULL) {

    $ime_opt = array('ime' => 'disabled');

    print '<table class="listofstuff">';

    if (is_null($subloop)) {
      $subfield = NULL;
      $picklist = array(0 => 1);
    } else {
      $subfield = $subloop[0];
      $picklist = $subloop[1];
    }

    foreach ($picklist as $pickkey => $picklabel) {
      if (! is_null($subfield))
	print ("<tr><th colspan=\"2\">" . htmlspecialchars($picklabel) .
	       "</th></tr>\n");
      $ccnt = 0; $lix = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	if (! is_null($subfield) && ($p[$ix][$subfield] != $pickkey))
	  continue;
	$lix = $ix;
	$evenodd = ($ccnt % 2) ? "o" : "e";
	$ccnt++;
	print "<tr class=\"$evenodd\"><th>患者</th><td>";
	foreach (array('患者', '患者ID', '患者名', '患者年齢',
		       '病室', '病室名') as $col) {
	  $cnx = mx_form_encode_name($col);
	  mx_formi_hidden(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	}
	if (! is_null($subfield)) {
	  $col = $subfield;
	  $cnx = mx_form_encode_name($col);
	  mx_formi_hidden(($pfx . $ix . '-' . $cnx), $p[$ix][$col]);
	}
	$label = $this->patient_label($p[$ix]);
	if ($this->_Subpicker)
	  print $label;
	else
	  mx_formi_submit($this->prefix . 'Subpick', $pg . '-0-' . $ix,
			  "<span class=\"link\">$label</span>");
	print "</td></tr>\n";

	$label = $p[$ix]['病室名'];
	if (trim($label) == '') $label = '(未設定)';
	print "<tr class=\"$evenodd\"><th>病室</th><td>";
	if ($this->_Subpicker)
	  print $label;
	else
	  mx_formi_submit($this->prefix . 'Subpick', $pg . '-1-' . $ix,
			  "<span class=\"link\">$label</span>");
	print "</td></tr>\n";

	foreach ($xtra as $col => $kind) {
	  print "<tr class=\"$evenodd\"><th>$col</th><td>";
	  $cnx = mx_form_encode_name($col);
	  switch ($kind) {
	  case 'text':
	    mx_formi_text(($pfx . $ix . '-' . $cnx), $p[$ix][$col]);
	    break;
	  case 'text-i':
	    mx_formi_text(($pfx . $ix . '-' . $cnx), $p[$ix][$col], $ime_opt);
	    break;
	  case 'textarea':
	    mx_formi_textarea(($pfx . $ix . '-' . $cnx), $p[$ix][$col]);
	    break;
	  }
	  print "</td></tr>\n";
	}

	if (! $this->_Subpicker) {
	  print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	  mx_formi_submit($this->prefix . 'DelRow', $pg . '-' . $ix,
			  "<span class=\"link\">この項目を削除</span>");
	  print "</td></tr>\n";
	}

      }

      if (! is_null($subfield) &&
	  ! $this->_Subpicker && (! $ccnt || $p[$lix]['患者'] != '')) {
	print "<tr><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'AddRow', "$pg-$pickkey",
			"<span class=\"link\">項目を追加</span>");
	print "</td></tr>\n";
      }
    }

    print "</table>\n";

    if (is_null($subloop)) {
      if (! $this->_Subpicker &&
	  (! count($p) || $p[count($p)-1]['患者'] != ''))
	mx_formi_submit($this->prefix . 'AddRow', $pg,
			"<span class=\"link\">項目を追加</span>");
    }
  }

  function draw_page_5() {
    // '患者管理特記事項' => array("患者", "病室",
    // "日勤帯特記事項", "夜勤帯特記事項",
    // "患者名", "患者ID", "患者年齢",
    // "病室名"),
    $cfg = $this->so_config['Pages']['患者管理特記事項'];
    $pfx = $this->prefix . 5 . '-data-';
    $p = $this->data['患者管理特記事項'];

    $this->__draw_page_sub_45($cfg, $pfx, $p, 5,
			      array('日勤帯特記事項' => 'textarea',
				    '夜勤帯特記事項' => 'textarea'));
  }

  function draw_page_6() {
    // '在庫管理' => array("名称", "在庫数", "管理日誌欄"));
    $cfg = $this->so_config['Pages']['在庫管理'];
    $pfx = $this->prefix . 6 . '-data-';
    $p = $this->data['在庫管理'];
    print '<table class="listofstuff">';

    foreach (array(0 => '薬品', 1 => '物品') as $slot => $slotname) {
      print "<tr><th colspan=\"3\">$slotname</th></tr>\n";
      print "<tr><th>名称</th><th>在庫数</th><th>(行削除)</th></tr>\n";
      $iy = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	if ($p[$ix]['管理日誌欄'] != $slot)
	  continue;
	$evenodd = ($iy % 2) ? "o" : "e";
	print "<tr class=\"$evenodd\">";
	foreach (array('名称','在庫数') as $col) {
	  print "<td>";
	  mx_formi_text($pfx . $ix . '-' . mx_form_encode_name($col),
			$p[$ix][$col]);
	  print "</td>";
	}
	print "<td>";
	$col = '管理日誌欄';
	mx_formi_hidden($pfx . $ix . '-' . mx_form_encode_name($col),
			$p[$ix][$col]);
	mx_formi_submit($this->prefix . 'DelRow', '6-' . $ix,
			'<span class="link">x</span>');
	print "</td></tr>\n";
	$iy++;
      }
      $evenodd = ($iy % 2) ? "o" : "e";
      print "<tr class=\"$evenodd\"><td colspan=\"3\">";
      mx_formi_submit($this->prefix . 'AddRow', '6-' . $slot,
		      "<span class=\"link\">(行追加)</span><br />");
      print "</td></tr>\n";
    }
    print "</table>\n";
  }


  function data_compare($curr, $data) {
    return _lib_u_nurse_ward_manage_log_compare_data
      ($data, $curr, $this->so_config);
  }

  function _update_stmt($d, $u, $id) {
    // simple-object-edit expects to find $this->data formatted
    // a bit differently.  We override.
    $stmt = ('UPDATE ' .
	     mx_db_sql_quote_name($this->so_config['TABLE']) .
	     ' SET "CreatedBy" = ' .
	     mx_db_sql_quote($u));
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= (",\n " . mx_db_sql_quote_name($col) . ' = ' .
		mx_db_sql_quote($d['全般'][0][$col]));
    $stmt .= (' WHERE "ObjectID" = ' . mx_db_sql_quote($id) .
	      ' AND "Superseded" IS NULL ');
    return $stmt;
  }

  function _insert_stmt(&$d, $ObjectID, $StashID) {
    global $mx_authenticate_current_user;

    // $d passed is of shape $this->data, but we do not store things
    // as the simple-object does.  We override.

    if (is_null($StashID)) {
      $o = mx_db_sql_quote($ObjectID);
      $o = "$o, $o, NULL, " . mx_db_sql_quote($mx_authenticate_current_user);
    } else {
      // We are stashing the old information away.
      $o = (mx_db_sql_quote($ObjectID) . ', ' .
	    mx_db_sql_quote($StashID) . ', now(), ' .
	    mx_db_sql_quote($d["CreatedBy"]));
    }

    // foreach (array('師長', '部長') as $col)
    // if ($d['全般'][0][$col] == '')
    // $d['全般'][0][$col] = NULL;

    $stmt = (($this->insert_stmt_head) . 'VALUES (' . "$o");
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= ",\n " . mx_db_sql_quote($d['全般'][0][$col]);
    $stmt .= ')';
    return $stmt;
  }

  function _validate() {
    $bad = 0;
    $null_bad_col = array('全般' => array(),
			  '職員' => array('職員'),
			  '転入転出' => array('患者', '病室'),
			  '転室' => array('患者', '転室元病室', '転室先病室'),
			  '外泊・外出' => array('患者', '病室'),
			  '患者管理特記事項'=> array('患者', '病室'),
			  '在庫管理' => array('名称', '在庫数'));
    $pos_num_col = array('全般' => array_merge
			 (array
			  ("空床部屋", "入院・日", "入院・夜",
			   "軽快", "転院", "死亡", "他の転帰",
			   "転入", "転出", "担送", "護送", "独歩"),
			  _lib_u_nurse_ward_manage_log_A1C4()),
			 );

    $time_of_day_or_null_col = array
      ('外泊・外出' => array('外泊出時間', '帰院時刻'));

    foreach ($null_bad_col as $page => $cfg)
      foreach ($this->data[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if (($st = mx_db_validate_length($row[$col], 1, NULL)) != '') {
	    $this->err("($page) $col: " . $st . "\n");
	    $bad++;
	  }

    foreach ($pos_num_col as $page => $cfg)
      foreach ($this->data[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if (($st = mx_db_validate_nnint($row[$col])) != '') {
	    $this->err("($page) $col: " . $st . "\n");
	    $bad++;
	  }

    foreach ($time_of_day_or_null_col as $page => $cfg)
      foreach ($this->data[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if (! is_null($row[$col]) &&
	      (($st = mx_db_validate_time($row[$col])) != '')) {
	    $this->err("($page) $col: " . $st . "\n");
	    $bad++;
	  }

    foreach ($this->data['外泊・外出'] as $ix => $row)
      if (($row['管理日誌欄'] == 1) &&
	  (is_null($row['外泊出時間']) || is_null($row['帰院時刻']))) {
	$this->err("外出の外泊出時間や帰院時刻は空ではいけません\n");
	$bad++;
      }

    foreach ($this->data['転入転出'] as $ix => $row)
      if (($row['入出'] == 'i' || $row['入出'] == 'o') &&
	  ($st = mx_db_validate_length($row['転棟病棟'], 1, NULL)) != '') {
	$this->err("(転入転出) 転入・転出での転棟病棟は空ではいけません\n");
	$bad++;
      }

    foreach ($this->data['転室'] as $ix => $row)
      if ($row['転室元病室'] == $row['転室先病室']) {
	$this->err("(転室) 転室元病室と転室先病室が同じではいけません\n");
	$bad++;
      }

    $rm = $pt = $conflict = $ptname = $rmname = NULL;
    $this->summarize_patient_movement($rm, $pt, $conflict,
				      $ptname, $rmname);

    foreach ($conflict as $ix => $row) {
      $this->err("転室・転入転出: 患者 $row が複数回指定されています\n");
      $bad++;
    }


    $tot_num = ($this->data['全般'][0]['担送'] +
		$this->data['全般'][0]['護送'] +
		$this->data['全般'][0]['独歩']);
    $sub_num = 0;
    foreach (_lib_u_nurse_ward_manage_log_A1C4() as $col)
      $sub_num += $this->data['全般'][0][$col];

    if ($tot_num != $sub_num) {
      $this->err("(全般) 現在数 $tot_num と".
		 "看護度別患者数の総計 $sub_num が一致しません\n");
      $bad++;
    }

    if (! $bad)
      return 'ok';
  }

  function _update_subtables(&$db, $id, $stash_id) {
    $subtable = array
      (array("病棟管理日誌・職員", "職員",
	     array("職員", "管理日誌欄")),
       array("病棟管理日誌・転入転出", "転入転出",
	     array("患者", "病室", "入出", "転棟病棟", "備考")),
       array("病棟管理日誌・転室", "転室",
	     array("患者", "転室元病室", "転室先病室")),
       array("病棟管理日誌・外泊・外出", "外泊・外出",
	     array("患者", "病室", "管理日誌欄",
		   "外泊出時間", "帰院時刻", "備考")),
       array("病棟管理日誌・患者管理特記事項", "患者管理特記事項",
	     array("患者", "病室", "日勤帯特記事項", "夜勤帯特記事項")),
       array("病棟管理日誌・在庫管理", "在庫管理",
	     array("名称", "在庫数", "管理日誌欄")),
       );

    if (! is_null($stash_id)) {
      // Current rows in subtables should point at $stash_id
      foreach ($subtable as $d) {
	$st = $d[0];
	$stmt = ('UPDATE "' . $st . '" SET "病棟管理日誌" = ' .
		 mx_db_sql_quote($stash_id) .
		 ' WHERE "病棟管理日誌" = ' .
		 mx_db_sql_quote($id));
	$this->dbglog("Stash-Subs: $stmt\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);
      }
    }

    foreach ($subtable as $d) {
      $st = $d[0];
      $slot = $d[1];
      $cols = $d[2];
      foreach ($this->data[$slot] as $r) {
	$cc = array();
	foreach ($cols as $col)
	  $cc[] = '"' . $col . '"';
	$cc = join(', ', $cc);
	if (trim($r[$cols[0]]) != '') {
	  $stmt = ('INSERT INTO "' . $st . '" ("病棟管理日誌", ' .
		   $cc . ') values (' .
		   mx_db_sql_quote($id));
	  foreach ($cols as $col) {
	    $stmt = $stmt . ', ' . mx_db_sql_quote($r[$col]);
	  }
	  $stmt = $stmt . ")\n";
	  $this->dbglog("Insert-Subs: $stmt\n");
	  if (! pg_query($db, $stmt))
	    return pg_last_error($db);
	}
      }
    }

    $st = $this->move_patients($db);
    return $st;
  }

  function summarize_patient_movement(&$rm, &$pt, &$conflict,
				      &$ptname, &$rmname) {

    $rm = array(); $pt = array(); $conflict = array();
    $ptname = array(); $rmname = array();

    // Find affected patients and rooms.
    // For each patient, find where it belongs now.
    // For each patient, find where it wants to belong to with this log.
    // For each room, find which patient should move with this log.

    $pt_seen = array();
    foreach ($this->data['転入転出'] as $ent) {
      $ptname[$ent['患者']] = $ent['患者名'];
      $rmname[$ent['病室']] = $ent['病室名'];
      if (array_key_exists($ent['患者'], $pt_seen))
	$conflict[$ent['患者']] = $ent['患者名'];

      $pt_seen[$ent['患者']] = 1;
      switch ($ent['入出']) {
      case 'i':
      case 'I':
	$pt[$ent['患者']] = $ent['病室'];
	$rm[$ent['病室']][] = array('+', $ent['患者']);
	break;
      case 'o':
      case 'O':
	$pt[$ent['患者']] = NULL;
	$rm[$ent['病室']][] = array('-', $ent['患者']);
	break;
      }
    }

    foreach ($this->data['転室'] as $ent) {
      $ptname[$ent['患者']] = $ent['患者名'];
      $rmname[$ent['転室元病室']] = $ent['転室元病室名'];
      $rmname[$ent['転室先病室']] = $ent['転室先病室名'];
      if (array_key_exists($ent['患者'], $pt_seen))
	$conflict[$ent['患者']] = $ent['患者名'];

      $pt[$ent['患者']] = $ent['転室先病室'];
      $rm[$ent['転室元病室']][] = array('-', $ent['患者']);
      $rm[$ent['転室先病室']][] = array('+', $ent['患者']);
    }

    // These records say where they are without mentioning explicit movements.
    foreach (array('外泊・外出', '患者管理特記事項') as $slot) {
      foreach ($this->data[$slot] as $ent) {
	$ptname[$ent['患者']] = $ent['患者名'];
	$rmname[$ent['病室']] = $ent['病室名'];
	if (array_key_exists($ent['患者'], $pt_seen))
	  continue;
	$pt[$ent['患者']] = $ent['病室'];
	$rm[$ent['病室']][] = array('+', $ent['患者']);
      }
    }

  }

  function move_patients(&$db) {
    global $mx_authenticate_current_user;

    $rm = $pt = $conflict = $patient_name = $room_name = NULL;
    $this->summarize_patient_movement($rm, $pt, $conflict,
				      $patient_name, $room_name);

    if (count($rm) == 0) {
      // pt should be empty otherwise something is wrong.
      if (count($pt)) die("rm empty but pt not?");
      return '';
    }

    // Find where they are right now.
    $stmt = ('SELECT RP."病室", R."病室名", RP."ObjectID", RP."日付",
              RP."CreatedBy", RPD."患者"
	      FROM "病室患者表" AS RP
              JOIN "病室一覧表" AS R
              ON R."ObjectID" = RP."病室" AND R."Superseded" IS NULL
	      LEFT JOIN "病室患者データ" AS RPD
	      ON RP."ObjectID" = RPD."病室患者表" AND RP."Superseded" IS NULL
              WHERE RP."病室" IN (' . join(',', array_keys($rm)) . ') OR
                    RPD."患者" IN (' . join(',', array_keys($pt)) . ')');
    $this->dbglog("Get RPD: $stmt;\n");
    $_rpd = pg_fetch_all(pg_query($db, $stmt));
    $this->dbglog("RPD raw: " . mx_var_dump($_rpd));

    $rpd = array();
    $rrpd = array();
    if (is_array($_rpd)) {
      foreach ($_rpd as $r) {
	$room = $r['病室'];
	$patient = $r['患者'];
	$date = $r['日付'];
	if (! array_key_exists($room, $rpd)) {
	  $rpd[$room] = array('ObjectID' => $r['ObjectID'],
			      'CreatedBy' => $r['CreatedBy'],
			      '病室名' => $r['病室名'],
			      '日付' => $date,
			      '患者' => array());
	  $room_name[$room] = $r['病室名'];
	}
	if (! is_null($patient)) {
	  $rpd[$room]['患者'][$patient] = $date;
	  $rrpd[$patient][] = $room;
	}
      }
    }
    $this->dbglog("RPD: " . mx_var_dump($rpd));
    $this->dbglog("RRPD: " . mx_var_dump($rrpd));

    foreach ($rrpd as $p => $rm_list) {
      if (count($rm_list) != 1)
	// There is something very wrong with this data.  The
	// patient belongs to more than one room.
	$this->log("Bad RPD entry: " . $patient_name[$p] . " ($p) in rooms " .
		   join(" ", $rm_list) . "\n");
    }

    $this->dbglog("RM: " . mx_var_dump($rm));

    $dt = $this->data['全般'][0]['日付'];

    // A room-patient record newer than this log entry should not be
    // touched, and the patients described there should not appear
    // anywhere else.
    $room_forbidden = array();
    $patient_forbidden = array();
    foreach ($rpd as $room => $data) {
      if ($dt < $data['日付']) {
	$room_forbidden[$room] = 1;
	foreach ($data['患者'] as $patient => $junk)
	  $patient_forbidden[$patient] = array($room, $data['日付']);
      }
    }

    // Compute who should be in what room and record it in $nrpd.
    // Also remember patients with explicit move instruction.
    $nrpd = array();
    $pt_explicit = array();
    foreach ($rm as $room => $insn_list) {
      if (array_key_exists($room, $room_forbidden))
	continue;
      $nrpd[$room] = array();
      if (array_key_exists($room, $rpd))
	$nrpd[$room] = $rpd[$room]['患者'];
      foreach ($patient_forbidden as $patient => $junk)
	unset($nrpd[$room][$patient]);
      foreach ($insn_list as $insn) {
	$patient = $insn[1];
	if (array_key_exists($patient, $patient_forbidden))
	  continue;
	if ($insn[0] == '-')
	  unset($nrpd[$room][$patient]);
	elseif (! array_key_exists($patient, $nrpd[$room]))
	  $nrpd[$room][$patient] = $dt;
	$pt_explicit[$patient] = $dt;
      }
    }

    // $nrpd[$r] is the list of patients explicitly placed.
    // people without explicit instruction can be placed now.
    foreach ($pt as $patient => $room) {
      if (is_null($room) ||
	  array_key_exists($patient, $patient_forbidden) ||
	  array_key_exists($room, $room_forbidden) ||
	  array_key_exists($patient, $pt_explicit))
	continue;
      if (! array_key_exists($patient, $nrpd[$room]))
	$nrpd[$room][$patient] = $dt;
    }
    $this->dbglog("NRPD-0: " . mx_var_dump($nrpd));

    // Now $nrpd[] has list of desired patient movements.
    // Sanitize it.

    // Phase I: against NRPD itself.
    $pt_after = array();
    foreach ($nrpd as $room => $pt_list) {
      foreach ($pt_list as $patient => $junk) {
	if (array_key_exists($patient, $pt_after)) {
	  // This should not happen, because summarize should have
	  // detected conflicts in explicit movements already and
	  // we have been careful constructing nrpd so far...
	  $this->log("Bad NRPD entry: " . $patient . " in both " .
		     $pt_after[$patient] . ' and ' . $room . "\n");
	  unset($nrpd[$pt_after[$patient]][$patient]);
	}
	$pt_after[$patient] = $room;
      }
    }
    $this->dbglog("NRPD-1: " . mx_var_dump($nrpd));

    // Phase II: against RPD.
    foreach ($rpd as $room => $data) {
      if (array_key_exists($room, $nrpd))
	continue;
      foreach ($data['患者'] as $patient => $junk) {
	if (array_key_exists($patient, $pt_after)) {
	  // This should not happen, either.
	  $this->log("Bad NRPD entry tells to move $patient to " .
		     $pt_after[$patient] .
		     ", but newer RPD entry has it in $room\n");
	  unset($nrpd[$pt_after[$patient]][$patient]);
	}
      }
    }
    $this->dbglog("NRPD-2: " . mx_var_dump($nrpd));

    // Phase III: final sanity check.  Each patient should appear at
    // most once.
    $pt_after = array();
    foreach ($rpd as $room => $data) {
      if (array_key_exists($room, $nrpd))
	foreach ($nrpd[$room] as $patient => $junk)
	  $pt_after[$patient][] = $room;
      else
	foreach ($data['患者'] as $patient => $junk)
	  $pt_after[$patient][] = $room;
    }
    foreach ($nrpd as $room => $data) {
      if (array_key_exists($room, $rpd))
	; // we have done this above.
      else
	foreach ($data as $patient => $junk)
	  $pt_after[$patient][] = $room;
    }
    $this->dbglog("PT-AFTER: " . mx_var_dump($pt_after));
    foreach ($pt_after as $patient => $rm_list) {
      if (count($rm_list) != 1)
	// There is something very wrong with this data.  The
	// patient belongs to more than one room.
	$this->log("Bad NRPD sanitization: " . $patient . " in rooms " .
		   join(" ", $rm_list) . "\n");
    }

    // Now move patients according to nrpd.
    foreach ($nrpd as $r => $pt_list) {
      if (array_key_exists($r, $rpd)) {
	$opt = array();
	foreach ($rpd[$r]['患者'] as $p => $junk)
	  $opt[] = $p;
	$npt = array();
	foreach ($pt_list as $p => $junk)
	  $npt[] = $p;

	if ($rpd[$r]['日付'] == $dt &&
	    count(array_diff($npt, $opt)) == 0 &&
	    count(array_diff($opt, $npt)) == 0) {
	  $this->dbglog("Patient list for $r remains the same (" .
			join(", ", $npt) . ")\n");
	  continue;
	}

	$oid = $rpd[$r]['ObjectID'];
	$this->dbglog("Setting patient list for $r to (" .
		      join(", ", $npt) . "), was (" .
		      join(", ", $opt) . ")\n");

	$stmt = ('SELECT nextval(\'"病室患者表_ID_seq"\') as "v"');
	$this->dbglog("SEQ -- $stmt;\n");
	$stash_id = mx_db_fetch_single($db, $stmt);
	$stash_id = $stash_id['v'];
	$this->dbglog("SEQ is $stash_id\n");

	// Stash
	$stmt = ('INSERT INTO "病室患者表" ("ID", "ObjectID", "CreatedBy",
                 "Superseded", "病室", "日付") VALUES (' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($stash_id) . ', ' .
		 mx_db_sql_quote($rpd[$r]['CreatedBy']) . ', now(), ' .
		 mx_db_sql_quote($r) . ', ' .
		 mx_db_sql_quote($rpd[$r]['日付']) . ')');
	$this->dbglog("Stash -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);

	$stmt = ('UPDATE "病室患者データ" SET "病室患者表" = ' .
		 mx_db_sql_quote($stash_id) . ' WHERE "病室患者表" = ' .
		 mx_db_sql_quote($oid));
	$this->dbglog("Stash Sub -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);

	// UPDATE in place
	$stmt = ('UPDATE "病室患者表" SET "CreatedBy" = ' .
		 mx_db_sql_quote($mx_authenticate_current_user) . ', ' .
		 '"日付" = ' .
		 mx_db_sql_quote($dt) . '
                 WHERE "ObjectID" = ' . $oid);
      }
      else {
	$npt = array();
	foreach ($pt_list as $p => $junk)
	  $npt[] = $p;
	$this->dbglog("Setting patient list for $r to (" .
		      join(", ", $npt) . "), a new RP entry\n");

	$stmt = ('SELECT nextval(\'"病室患者表_ID_seq"\') as "v"');
	$this->dbglog("SEQ -- $stmt;\n");
	$stash_id = mx_db_fetch_single($db, $stmt);
	$oid = $stash_id['v'];
	$this->dbglog("SEQ is $oid\n");

	// INSERT the new one.
	$stmt = ('INSERT INTO "病室患者表" ("ID", "ObjectID", "CreatedBy",
               "病室", "日付") VALUES (' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($mx_authenticate_current_user) . ', ' .
		 mx_db_sql_quote($r) . ', ' .
		 mx_db_sql_quote($dt) . ')');
      }
      $this->dbglog("Latest -- $stmt;\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);

      foreach ($pt_list as $p => $junk) {
	$stmt = ('INSERT INTO "病室患者データ" ("病室患者表", "患者")
                 VALUES (' . mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($p) . ')');
	$this->dbglog("Latest Sub -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);
      }

    }

    $msg = '';

    // Filter patient-forbidden
    $pf = array();
    foreach ($patient_forbidden as $patient => $data) {
      if (array_key_exists($patient, $pt))
	$pf[$patient] = $data;
    }
    $this->dbglog("Patient-Forbidden: " . mx_var_dump($patient_forbidden));
    $this->dbglog("Patient-Forbidden: " . mx_var_dump($pf));

    if (count($pf)) {
      $msg .= ("<p>以下の患者には、現在編集中の $dt 日付病棟管理日誌より" .
	       "新しい記録により患者の所在がすでに記録されていますので、" .
	       "本記録によって患者の移動は行ないません。</p>\n<ul>");
      foreach ($pf as $patient => $data) {
	$room = $rpd[$data[0]]['病室名'];
	$date = $data[1];
	$msg .= ("<li>" . htmlspecialchars($patient_name[$patient]) .
		 " ($date 、病室 $room)</li>\n");
      }
      $msg .= "</ul>\n";
    }

    // Compare new location with the old
    $patient_move_log = array();
    foreach ($pt_after as $patient => $rm_list)
      $patient_move_log[$patient] = array(NULL, $rm_list[0]);
    foreach ($rrpd as $patient => $rm_list) {
      if (! array_key_exists($patient, $patient_move_log))
	$patient_move_log[$patient] = array(NULL, NULL);
      $patient_move_log[$patient][0] = $rm_list[0];
    }
    // Filter it
    $pm = array();
    foreach ($patient_move_log as $patient => $data) {
      if (! is_null($data[0]) && ! is_null($data[1]) && $data[0] == $data[1])
	;
      elseif (is_null($data[0]) && is_null($data[1]))
	;
      else
	$pm[$patient] = $data;
    }
    $this->dbglog("Patient-Move: " . mx_var_dump($patient_move_log));
    $this->dbglog("Patient-Move: " . mx_var_dump($pm));

    if (count($pm)) {
      $msg .= ("<p>以下の患者については、現在編集中の $dt 日付病棟管理日誌" .
	       "が患者の所在に関する最新データですので、本記録によって患者" .
	       "を各病室に記録しました。</p>\n<ul>");
      foreach ($pm as $patient => $data) {
	$fromto = '';
	if (! is_null($data[0]))
	  $fromto = $room_name[$data[0]] . 'から';
	if (! is_null($data[1]))
	  $fromto = $fromto . $room_name[$data[1]] . 'へ';
	$fromto = $fromto . '移動';
	$msg .= ("<li>" . htmlspecialchars($patient_name[$patient]) .
		 " " . htmlspecialchars($fromto) . "</li>\n");
      }
      $msg .= "</ul>\n<br />";
    }
    $this->commit_message = $msg;
    return '';
  }

  function _broken_origin_check() {
     if ($this->id == '') {
	     $db = mx_db_connect();
	     $d = _lib_u_nurse_ward_manage_log_get_by_dt_ward
		     ($this, $this->so_config, $db,
		      $this->data['全般'][0]['日付'],
		      $this->data['全般'][0]['病棟']);
	     $id = $d['ObjectID'];
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
