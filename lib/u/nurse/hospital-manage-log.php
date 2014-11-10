<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-manage-log.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

function _lib_u_nurse_hospital_manage_log_config(&$config)
{
  $config['DCOLS'] = array('ÆüÄ¾°å»ÕÌ¾',
			   'ÅöÄ¾°å»ÕÌ¾',
			   '³°ÍèÃ´Åö´Ç¸î»ÕÌ¾',
			   '»õ²Ê±ÒÀ¸»ÎÌ¾',
			   'ÉÂ±¡³°Íè´µ¼Ô¿ô',
			   '»õ²Ê³°Íè´µ¼Ô¿ô',
			   array('Column' => 'ÆÃµ­»ö¹à',
				 'Draw' => 'textarea'),
			   array('Column' => 'CreatedBy',
				 'Draw' => 'user'));
  _lib_so_prepare_config_ledcols(&$config['DCOLS']);
}

function _lib_u_nurse_hospital_manage_log_peek_id(&$it, $db, $dt)
{
  $stmt = ('SELECT HM."ObjectID"
FROM "ÉÂ±¡´Ç¸î´ÉÍıÆü»ï" AS HM
WHERE HM."Superseded" IS NULL AND HM."ÆüÉÕ" = ' . mx_db_sql_quote($dt));
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
  $stmt = ('SELECT "ObjectID", "ÉÂÅïÌ¾" FROM "ÉÂÅï°ìÍ÷É½"
WHERE "Superseded" IS NULL
ORDER BY "ÉÂÅïÌ¾"');
  $it->dbglog("$stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  $dt = $data['ÆüÉÕ'];
  $it->ward_list = array();
  $data['Å¾Åï'] = array();
  $data['Æş±¡'] = array();
  $data['Âà±¡'] = array();
  $data['³°Çñ'] = array();
  $ward_total = 'Á´ÉÂÅï¹ç·×';
  foreach ($r as $_ward_data) {
    $ward = $_ward_data['ObjectID'];
    $ward_name = $_ward_data['ÉÂÅïÌ¾'];
    $it->ward_list[$ward] = $ward_name;
    $wcf = array();
    _lib_u_nurse_ward_manage_log_prepare_config($wcf);
    $wd =
      _lib_u_nurse_ward_manage_log_get_by_dt_ward($it, $wcf, $db, $dt, $ward);

    foreach (array("¶õ¾²Éô²°", "µö²ÄÉÂ¾²¿ô",
		   "Æş±¡¡¦Æü", "Æş±¡¡¦Ìë",
		   "·Ú²÷", "Å¾±¡", "»àË´", "Â¾¤ÎÅ¾µ¢",
		   "Å¾Æş", "Å¾½Ğ") as $col) {
      $data[$ward_name . '.' . $col] = $wd['Á´ÈÌ'][0][$col];
      $data[$ward_total . '.' . $col] += $wd['Á´ÈÌ'][0][$col];
    }
    foreach (array("´µ¼Ô¿ô" => array("Ã´Á÷", "¸îÁ÷", "ÆÈÊâ"),
		   "Æş±¡" => array("Æş±¡¡¦Æü", "Æş±¡¡¦Ìë"),
		   "Å¾±¡¡¦Å¾µ¢" => array("·Ú²÷", "Å¾±¡", "»àË´", "Â¾¤ÎÅ¾µ¢"))
	     as $col => $scols) {
      $sum = 0;
      $all_null = 1;
      foreach ($scols as $scol) {
	if ($wd['Á´ÈÌ'][0][$scol] != '') $all_null = 0;
	$sum += $wd['Á´ÈÌ'][0][$scol];
      }
      if (! $all_null) {
	$data[$ward_name . '.' . $col] = $sum;
	$data[$ward_total . '.' . $col] += $sum;
      }
    }

    foreach ($wd['Å¾ÆşÅ¾½Ğ'] as $d) {
      $d['ÉÂÅï'] = $ward;
      $d['ÉÂÅïÌ¾'] = $ward_name;
      switch ($d['Æş½Ğ']) {
      case 'i': // Â¾ÉÂÅï¤è¤êÅ¾Æş
	$data['Å¾Åï'][] = $d; break;
      case 'o': // Â¾ÉÂÅï¤ËÅ¾½Ğ
	;; // already covered above.
      case 'I': // Æş±¡
	$data['Æş±¡'][] = $d; break;
      case 'O': // Âà±¡
	$data['Âà±¡'][] = $d; break;
      }
    }

    foreach ($wd['³°Çñ¡¦³°½Ğ'] as $d) {
      if ($d['´ÉÍıÆü»ïÍó'] == 0) {
	$d['ÉÂÅï'] = $ward;
	$d['ÉÂÅïÌ¾'] = $ward_name;
	$data['³°Çñ'][] = $d;
      }
    }
  }
}

function _lib_u_nurse_hospital_manage_log_fetch_data(&$it, $db, $id)
{
  $stmt = ('SELECT HM."ObjectID", HM."Superseded", HM."CreatedBy",
HM."ÆüÉÕ",
HM."ÆüÄ¾°å»Õ", (ED0."À«" || ED0."Ì¾") AS "ÆüÄ¾°å»ÕÌ¾",
HM."ÅöÄ¾°å»Õ", (ED1."À«" || ED1."Ì¾") AS "ÅöÄ¾°å»ÕÌ¾",
HM."³°ÍèÃ´Åö´Ç¸î»Õ", (EN."À«" || EN."Ì¾") AS "³°ÍèÃ´Åö´Ç¸î»ÕÌ¾",
HM."»õ²Ê±ÒÀ¸»Î", (EP."À«" || EP."Ì¾") AS "»õ²Ê±ÒÀ¸»ÎÌ¾",
HM."ÉÂ±¡³°Íè´µ¼Ô¿ô",
HM."»õ²Ê³°Íè´µ¼Ô¿ô",
HM."ÆÃµ­»ö¹à",
HM."Àµ´Ç¸î»Õ¡¦¾ï¶ĞÁí¿ô",
HM."Àµ´Ç¸î»Õ¡¦¥Ñ¡¼¥ÈÁí¿ô",
HM."Àµ´Ç¸î»Õ¡¦¥¢¥ë¥Ğ¥¤¥ÈÁí¿ô",
HM."½Ú´Ç¸î»Õ¡¦¾ï¶ĞÁí¿ô",
HM."½Ú´Ç¸î»Õ¡¦¥Ñ¡¼¥ÈÁí¿ô",
HM."½Ú´Ç¸î»Õ¡¦¥¢¥ë¥Ğ¥¤¥ÈÁí¿ô",
HM."´Ç¸î½õ¼êÁí¿ô"
FROM "ÉÂ±¡´Ç¸î´ÉÍıÆü»ï" AS HM
LEFT JOIN "¿¦°÷ÂæÄ¢" AS ED0
ON ED0."ObjectID" = HM."ÆüÄ¾°å»Õ" AND ED0."Superseded" IS NULL
LEFT JOIN "¿¦°÷ÂæÄ¢" AS ED1
ON ED1."ObjectID" = HM."ÅöÄ¾°å»Õ" AND ED1."Superseded" IS NULL
LEFT JOIN "¿¦°÷ÂæÄ¢" AS EN
ON EN."ObjectID" = HM."³°ÍèÃ´Åö´Ç¸î»Õ" AND EN."Superseded" IS NULL
LEFT JOIN "¿¦°÷ÂæÄ¢" AS EP
ON EP."ObjectID" = HM."»õ²Ê±ÒÀ¸»Î" AND EP."Superseded" IS NULL
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
    $data = array('ÆüÉÕ' => $dt);
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
    $this->so_config['TABLE'] = "ÉÂ±¡´Ç¸î´ÉÍıÆü»ï";
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

    print '<tr><th>ÆüÉÕ</th><td>';
    print $data['ÆüÉÕ'];
    print '</td><th>ÆüÄ¾°å»Õ</th>';
    $this->draw_body_atom($this->find_dcol('ÆüÄ¾°å»ÕÌ¾', $dcols),
			  $data, $hdata, 3);
    print '<th colspan="2">ÅöÄ¾°å»Õ</th>';
    $this->draw_body_atom($this->find_dcol('ÅöÄ¾°å»ÕÌ¾', $dcols),
			  $data, $hdata, 2);
    print "</tr>\n";

    print '<tr><th>³°ÍèÃ´Åö´Ç¸î»Õ</th>';
    $this->draw_body_atom($this->find_dcol('³°ÍèÃ´Åö´Ç¸î»ÕÌ¾', $dcols),
			  $data, $hdata, 4);
    print '</td><th>»õ²Ê±ÒÀ¸»Î</th>';
    $this->draw_body_atom($this->find_dcol('»õ²Ê±ÒÀ¸»ÎÌ¾', $dcols),
			  $data, $hdata, 4);
    print "</tr>\n";

    print '<tr><th>ÉÂ±¡³°Íè´µ¼Ô¿ô</th>';
    $this->draw_body_atom($this->find_dcol('ÉÂ±¡³°Íè´µ¼Ô¿ô', $dcols),
			  $data, $hdata, 4);
    print '</td><th>»õ²Ê³°Íè´µ¼Ô¿ô</th>';
    $this->draw_body_atom($this->find_dcol('»õ²Ê³°Íè´µ¼Ô¿ô', $dcols),
			  $data, $hdata, 4);
    print "</tr>\n";

    $this->draw_ward_row_title();
    foreach ($this->ward_list as $ward => $ward_name)
      $this->draw_ward_row($data, $hdata, $dcols, $ward, $ward_name);
    $this->draw_ward_row($data, $hdata, $dcols, NULL, 'Á´ÉÂÅï¹ç·×');

    $this->draw_inout_row($data, $hdata, $dcols, 'Æş±¡');
    $this->draw_inout_row($data, $hdata, $dcols, 'Âà±¡');
    $this->draw_xfer_row($data, $hdata, $dcols, 'Å¾Åï');
    $this->draw_outstay_row($data, $hdata, $dcols, '³°Çñ');

    print '<tr><th>ÆÃµ­»ö¹à</th>';
    $desc = $this->find_dcol('ÆÃµ­»ö¹à', $dcols);
    $this->draw_body_atom($this->find_dcol('ÆÃµ­»ö¹à', $dcols),
			  $data, $hdata, 8);
    print "</tr>\n";

    print '<tr><th>µ­Ï¿¼Ô</th>';

    $this->draw_body_atom($this->find_dcol('CreatedBy', $dcols),
			  $data, $hdata, 2);

    if (is_null($this->history_ix))
      print '<td colspan="6">&nbsp;</td>';
    else {
      print '<td colspan="3">ÊÑ¹¹¥¿¥¤¥à¥¹¥¿¥ó¥×</td><td colspan="3">';
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
    print "<th>ÉÂÅï</th>";
    print "<th>¶õ¾²Éô²°</th>";
    print "<th>Äê¿ô/´µ¼Ô¿ô</th>";
    print "<th colspan=\"2\">Æş±¡</th>";
    print "<th colspan=\"3\">Å¾±¡¡¦Å¾µ¢</th>";
    print "<th>Å¾Æş/Å¾½Ğ</th>";
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

    $this->draw_ward_row_helper($data, $hdata, $ward, '¶õ¾²Éô²°', 2);

    $this->draw_ward_row_helper($data, $hdata, $ward, 'µö²ÄÉÂ¾²¿ô', 1);
    $this->draw_ward_row_helper($data, $hdata, $ward, 'Æş±¡', 2);
    $this->draw_ward_row_helper($data, $hdata, $ward, 'Æş±¡¡¦Æü', 1, 'Æü');

    $this->draw_ward_row_helper($data, $hdata, $ward, 'Å¾±¡¡¦Å¾µ¢', 2);
    $this->draw_ward_row_helper($data, $hdata, $ward, '·Ú²÷', 1, '·Ú²÷');
    $this->draw_ward_row_helper($data, $hdata, $ward, '»àË´', 1, '»àË´');

    $this->draw_ward_row_helper($data, $hdata, $ward, 'Å¾Æş', 1);
    print "</tr>\n";

    print '<tr>';
    $this->draw_ward_row_helper($data, $hdata, $ward, '´µ¼Ô¿ô', 1);

    $this->draw_ward_row_helper($data, $hdata, $ward, 'Æş±¡¡¦Ìë', 1, 'Ìë');
    $this->draw_ward_row_helper($data, $hdata, $ward, 'Å¾±¡', 1, 'Å¾±¡');
    $this->draw_ward_row_helper($data, $hdata, $ward, 'Â¾¤ÎÅ¾µ¢', 1, 'Â¾');

    $this->draw_ward_row_helper($data, $hdata, $ward, 'Å¾½Ğ', 1);
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
      print '<th>ÉÂÅï</th><th>Éô²°</th><th>Ì¾Á°</th><th>Ç¯Îğ</th>';
      print '<th colspan="5">È÷¹Í</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['ÉÂÅïÌ¾']);
	print '</td><td>';
	print htmlspecialchars($d['ÉÂ¼¼Ì¾']);
	print '</td><td>';
	print htmlspecialchars($d['´µ¼ÔÌ¾']);
	print '</td><td>';
	print htmlspecialchars($d['´µ¼ÔÇ¯Îğ']);
	print '</td><td colspan="5">';
	print htmlspecialchars($d['È÷¹Í']);
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
      print '<th>Å¾ÅïÀèÉÂÅï</th><th>Éô²°</th>';
      print '<th>Ì¾Á°</th><th>Ç¯Îğ</th><th colspan="5">Å¾Åï¸µÉÂÅï</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['ÉÂÅïÌ¾']);
	print '</td><td>';
	print htmlspecialchars($d['ÉÂ¼¼Ì¾']);
	print '</td><td>';
	print htmlspecialchars($d['´µ¼ÔÌ¾']);
	print '</td><td>';
	print htmlspecialchars($d['´µ¼ÔÇ¯Îğ']);
	print '</td><td colspan="5">';
	print htmlspecialchars($d['Å¾ÅïÉÂÅïÌ¾']);
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
      print '<th>ÉÂÅï</th><th>Éô²°</th><th>Ì¾Á°</th><th>Ç¯Îğ</th>';
      print '<th colspan="2">³°Çñ½Ğ»ş´Ö</th><th>µ¢±¡»ş¹ï</th>';
      print '<th colspan="2">È÷¹Í</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['ÉÂÅïÌ¾']);
	print '</td><td>';
	print htmlspecialchars($d['ÉÂ¼¼Ì¾']);
	print '</td><td>';
	print htmlspecialchars($d['´µ¼ÔÌ¾']);
	print '</td><td>';
	print htmlspecialchars($d['´µ¼ÔÇ¯Îğ']);
	print '</td><td colspan="2">';
	print htmlspecialchars($d['³°Çñ½Ğ»ş´Ö']);
	print '</td><td>';
	print htmlspecialchars($d['µ¢±¡»ş¹ï']);
	print '</td><td colspan="2">';
	print htmlspecialchars($d['È÷¹Í']);
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
      AND C."¿¦¼ï" in ' . enum_doctor_cat_sql();
    $list_of_employees_ns_cfg['HSTMT'] .= '
      AND C."¿¦¼ï" in ' . enum_nurse_cat_sql();

    $list_of_employees_dr_cfg['STMT'] =
      $list_of_employees_dr_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $list_of_employees_ns_cfg['STMT'] =
      $list_of_employees_ns_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $list_of_employees_pp_cfg['STMT'] =
      $list_of_employees_pp_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $config['TABLE'] = 'ÉÂ±¡´Ç¸î´ÉÍıÆü»ï';
    $config['ECOLS'] = array
      (array('Column' => 'ÆüÉÕ', 'Draw' => NULL),

       array('Column' => 'ÆüÄ¾°å»Õ', 'Draw' => NULL),
       array('Column' => 'ÆüÄ¾°å»ÕÌ¾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '¤³¤Î°å»Õ¤ËÀßÄê¤¹¤ë',
	      'Config' => $list_of_employees_dr_cfg,
	      'ListID' => array('ObjectID', 'À«Ì¾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => 'ÆüÄ¾°å»Õ') ),

       array('Column' => 'ÅöÄ¾°å»Õ', 'Draw' => NULL),
       array('Column' => 'ÅöÄ¾°å»ÕÌ¾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '¤³¤Î°å»Õ¤ËÀßÄê¤¹¤ë',
	      'Config' => $list_of_employees_dr_cfg,
	      'ListID' => array('ObjectID', 'À«Ì¾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => 'ÅöÄ¾°å»Õ') ),

       array('Column' => '³°ÍèÃ´Åö´Ç¸î»Õ', 'Draw' => NULL),
       array('Column' => '³°ÍèÃ´Åö´Ç¸î»ÕÌ¾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '¤³¤Î´Ç¸î»Õ¤ËÀßÄê¤¹¤ë',
	      'Config' => $list_of_employees_ns_cfg,
	      'ListID' => array('ObjectID', 'À«Ì¾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '³°ÍèÃ´Åö´Ç¸î»Õ') ),

       array('Column' => '»õ²Ê±ÒÀ¸»Î', 'Draw' => NULL),
       array('Column' => '»õ²Ê±ÒÀ¸»ÎÌ¾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '¤³¤Î´Ç¸î»Õ¤ËÀßÄê¤¹¤ë',
	      'Config' => $list_of_employees_pp_cfg, // # NEEDSWORK
	      'ListID' => array('ObjectID', 'À«Ì¾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '»õ²Ê±ÒÀ¸»Î') ),

       array('Column' => 'ÉÂ±¡³°Íè´µ¼Ô¿ô', 'Draw' => 'text'),
       array('Column' => '»õ²Ê³°Íè´µ¼Ô¿ô', 'Draw' => 'text'),

       array('Column' => 'ÆÃµ­»ö¹à',
	     'Draw' => 'textarea'),
       // employee numbers left unspecified
       );
    $config['ICOLS'] = array
	    ('ÆüÉÕ', 'ÆüÄ¾°å»Õ', 'ÅöÄ¾°å»Õ', '³°ÍèÃ´Åö´Ç¸î»Õ',
	     '»õ²Ê±ÒÀ¸»Î', 'ÉÂ±¡³°Íè´µ¼Ô¿ô', '»õ²Ê³°Íè´µ¼Ô¿ô', 'ÆÃµ­»ö¹à');

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
    foreach (array('ÆüÄ¾°å»Õ', 'ÅöÄ¾°å»Õ', '³°ÍèÃ´Åö´Ç¸î»Õ', '»õ²Ê±ÒÀ¸»Î')
	     as $not_null_col) {
      if ($st = mx_db_validate_length($this->data[$not_null_col], 1, 0)) {
	$this->err("($not_null_col): $st\n");
	$bad++;
      }
    }
    foreach (array('ÉÂ±¡³°Íè´µ¼Ô¿ô', '»õ²Ê³°Íè´µ¼Ô¿ô')
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
		     ($this, $db, $this->data['ÆüÉÕ']);
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
