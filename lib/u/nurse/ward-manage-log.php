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
  $config['TABLE'] = 'ÉÂÅï´ÉÍýÆü»ï';
  $config['COLS'] = array('unused');
  $config['ECOLS'] = array();
  $config['Pages'] = array
    ('Á´ÈÌ' => array_merge(array("ÆüÉÕ", "ÉÂÅï", "Ã´Á÷", "¸îÁ÷", "ÆÈÊâ"),
			   _lib_u_nurse_ward_manage_log_A1C4(),
			   array("È÷¹Í", "¶õ¾²Éô²°",
				 "Æþ±¡¡¦Æü", "Æþ±¡¡¦Ìë", "·Ú²÷", "Å¾±¡",
				 "»àË´", "Â¾¤ÎÅ¾µ¢", "Å¾Æþ", "Å¾½Ð",
				 "»ÕÄ¹", "ÉôÄ¹", "ÉÂÅïÌ¾", "µö²ÄÉÂ¾²¿ô",
				 "»ÕÄ¹Ì¾", "ÉôÄ¹Ì¾")),
     '¿¦°÷' => array("¿¦°÷", "¿¦°÷Ì¾", "´ÉÍýÆü»ïÍó"),
     'Å¾ÆþÅ¾½Ð' => array("´µ¼Ô", "ÉÂ¼¼", "Æþ½Ð", "Å¾ÅïÉÂÅï", "È÷¹Í",
			 "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
			 "Å¾ÅïÉÂÅïÌ¾", "ÉÂ¼¼Ì¾"),
     'Å¾¼¼' => array("´µ¼Ô", "Å¾¼¼¸µÉÂ¼¼", "Å¾¼¼ÀèÉÂ¼¼",
		     "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
		     "Å¾¼¼¸µÉÂ¼¼Ì¾", "Å¾¼¼ÀèÉÂ¼¼Ì¾"),
     '³°Çñ¡¦³°½Ð' => array("´µ¼Ô", "ÉÂ¼¼", "³°Çñ½Ð»þ´Ö", "µ¢±¡»þ¹ï",
			   "´ÉÍýÆü»ïÍó", "È÷¹Í",
			   "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
			   "ÉÂ¼¼Ì¾"),
     '´µ¼Ô´ÉÍýÆÃµ­»ö¹à' => array("´µ¼Ô", "ÉÂ¼¼",
				 "Æü¶ÐÂÓÆÃµ­»ö¹à", "Ìë¶ÐÂÓÆÃµ­»ö¹à",
				 "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
				 "ÉÂ¼¼Ì¾"),
     'ºß¸Ë´ÉÍý' => array("Ì¾¾Î", "ºß¸Ë¿ô", "´ÉÍýÆü»ïÍó"));

  $config['ICOLS'] = array_merge(array("ÆüÉÕ", "ÉÂÅï", "Ã´Á÷", "¸îÁ÷", "ÆÈÊâ"),
				 _lib_u_nurse_ward_manage_log_A1C4(),
				 array("È÷¹Í",
				       "¶õ¾²Éô²°", "Æþ±¡¡¦Æü", "Æþ±¡¡¦Ìë",
				       "·Ú²÷", "Å¾±¡", "»àË´", "Â¾¤ÎÅ¾µ¢",
				       "Å¾Æþ", "Å¾½Ð", "»ÕÄ¹", "ÉôÄ¹"));
}

function _lib_u_nurse_ward_manage_log_peek_id(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT "ObjectID" FROM "ÉÂÅï´ÉÍýÆü»ï"
WHERE "ÉÂÅï" = ' . mx_db_sql_quote($ward) . '
AND "ÆüÉÕ" = ' . mx_db_sql_quote($dt) . '
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
  $stmt = ('SELECT WML."ObjectID", W."ÉÂÅïÌ¾", sum(R."Äê¿ô") AS "µö²ÄÉÂ¾²¿ô"
            FROM "ÉÂÅï°ìÍ÷É½" AS W
            JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
            ON W."ObjectID" = R."ÉÂÅï" AND
               W."Superseded" IS NULL AND R."Superseded" IS NULL
            LEFT JOIN "ÉÂÅï´ÉÍýÆü»ï" AS WML
            ON W."ObjectID" = WML."ÉÂÅï" AND
	       WML."Superseded" IS NULL AND
               WML."ÆüÉÕ" = ' . mx_db_sql_quote($dt) . '
            WHERE W."ObjectID" = ' . mx_db_sql_quote($ward) . '
            GROUP BY WML."ObjectID", W."ÉÂÅïÌ¾";');
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
	     'Á´ÈÌ' => array(array('ÆüÉÕ' => $dt,
				   'ÉÂÅï' => $ward,
				   'ÉÂÅïÌ¾' => $r[0]['ÉÂÅïÌ¾'],
				   'µö²ÄÉÂ¾²¿ô' => $r[0]['µö²ÄÉÂ¾²¿ô'])) );
  foreach ($cf['Pages'] as $slot => $cfg)
    if (! array_key_exists($slot, $a)) $a[$slot] = array();

  // Annotate with default inventory hints
  $hints = _lib_u_nurse_ward_manage_log_neigh_hints_by_dt_ward
    ($it, $cf, $db, $dt, $ward);

  if (is_array($hints)) {
    foreach ($hints['ºß¸Ë´ÉÍý'] as $row)
      $a['ºß¸Ë´ÉÍý'][] = mx_pick_array($row, 'Ì¾¾Î', '´ÉÍýÆü»ïÍó');

    foreach ($hints['³°Çñ¡¦³°½Ð'] as $row)
      if (is_null($row['µ¢±¡»þ¹ï']))
	$a['³°Çñ¡¦³°½Ð'][] = mx_pick_array
	  ($row,
	   '´µ¼Ô', '´µ¼ÔÌ¾', '´µ¼ÔID', '´µ¼ÔÇ¯Îð', 'ÉÂ¼¼', 'ÉÂ¼¼Ì¾',
	   '´ÉÍýÆü»ïÍó');

  }
  return $a;
}

function _lib_u_nurse_ward_manage_log_neigh_hints_by_dt_ward(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT WML."ObjectID"
FROM "ÉÂÅï´ÉÍýÆü»ï" AS WML
WHERE WML."Superseded" IS NULL AND WML."ÆüÉÕ" < ' . mx_db_sql_quote($dt) . '
AND WML."ÉÂÅï" = ' .mx_db_sql_quote($ward) . '
ORDER BY WML."ÆüÉÕ" DESC
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
		   L."ÆüÉÕ", L."ÉÂÅï", L."Ã´Á÷", L."¸îÁ÷", L."ÆÈÊâ", '.
	   _lib_u_nurse_ward_manage_log_A1C4('L') . ',
	           L."È÷¹Í", L."¶õ¾²Éô²°",
		   L."Æþ±¡¡¦Æü", L."Æþ±¡¡¦Ìë", L."·Ú²÷", L."Å¾±¡",
		   L."»àË´", L."Â¾¤ÎÅ¾µ¢", L."Å¾Æþ", L."Å¾½Ð",
		   L."»ÕÄ¹", L."ÉôÄ¹", W."ÉÂÅïÌ¾",
		   sum(R."Äê¿ô") AS "µö²ÄÉÂ¾²¿ô",
		   (E0."À«" || \' \' || E0."Ì¾") AS "»ÕÄ¹Ì¾",
		   (E1."À«" || \' \' || E1."Ì¾") AS "ÉôÄ¹Ì¾"
	    FROM "ÉÂÅï´ÉÍýÆü»ï" AS L JOIN "ÉÂÅï°ìÍ÷É½" AS W
	    ON L."ÉÂÅï" = W."ObjectID" AND W."Superseded" IS NULL
	    JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
	    ON R."ÉÂÅï" = W."ObjectID" AND R."Superseded" IS NULL
	    LEFT JOIN "¿¦°÷ÂæÄ¢" AS E0
	    ON L."»ÕÄ¹" = E0."ObjectID" AND E0."Superseded" IS NULL
	    LEFT JOIN "¿¦°÷ÂæÄ¢" AS E1
	    ON L."ÉôÄ¹" = E1."ObjectID" AND E1."Superseded" IS NULL
	    WHERE L."ObjectID" = ' . mx_db_sql_quote($oid) .
	   ' GROUP BY
	    L."CreatedBy", L."ObjectID", L."ID", L."Superseded",
	    L."ÆüÉÕ", L."ÉÂÅï", L."Ã´Á÷", L."¸îÁ÷", L."ÆÈÊâ", ' .
	   _lib_u_nurse_ward_manage_log_A1C4('L') . ',
            L."È÷¹Í", L."¶õ¾²Éô²°",
	    L."Æþ±¡¡¦Æü", L."Æþ±¡¡¦Ìë", L."·Ú²÷", L."Å¾±¡",
	    L."»àË´", L."Â¾¤ÎÅ¾µ¢", L."Å¾Æþ", L."Å¾½Ð",
	    L."»ÕÄ¹", L."ÉôÄ¹", W."ÉÂÅïÌ¾",
	    "»ÕÄ¹Ì¾",
	    "ÉôÄ¹Ì¾"'); // Ugh.
  $data = array();
  __d($debug, $db, $stmt, &$data, 'Á´ÈÌ');
  $data['CreatedBy'] = $data['Á´ÈÌ'][0]['CreatedBy'];
  $data['ObjectID'] = $oid;

  $stmt = ('SELECT X."¿¦°÷", (E."À«" || \' \' || E."Ì¾") AS "¿¦°÷Ì¾",
            X."´ÉÍýÆü»ïÍó"
            FROM "ÉÂÅï´ÉÍýÆü»ï¡¦¿¦°÷" AS X JOIN "¿¦°÷ÂæÄ¢" AS E
            ON X."¿¦°÷" = E."ObjectID" AND E."Superseded" IS NULL
            WHERE X."ÉÂÅï´ÉÍýÆü»ï" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY E."¿¦°÷ID"');
  __d($debug, $db, $stmt, &$data, '¿¦°÷');

  $pex = ('(P."À«" || \' \' || P."Ì¾") AS "´µ¼ÔÌ¾", P."´µ¼ÔID",
           (extract(year from age(timestamp \'' .
	  $data['Á´ÈÌ'][0]['ÆüÉÕ'] .
	  '\', P."À¸Ç¯·îÆü"))) AS "´µ¼ÔÇ¯Îð"');

  $stmt = ('SELECT X."´µ¼Ô", X."ÉÂ¼¼", X."Æþ½Ð", X."Å¾ÅïÉÂÅï", X."È÷¹Í",
            ' . $pex . ',
            W."ÉÂÅïÌ¾" AS "Å¾ÅïÉÂÅïÌ¾", R."ÉÂ¼¼Ì¾"
            FROM "ÉÂÅï´ÉÍýÆü»ï¡¦Å¾ÆþÅ¾½Ð" AS X JOIN "´µ¼ÔÂæÄ¢" AS P
            ON X."´µ¼Ô" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
            ON X."ÉÂ¼¼" = R."ObjectID" AND R."Superseded" IS NULL
            LEFT JOIN "ÉÂÅï°ìÍ÷É½" AS W
            ON X."Å¾ÅïÉÂÅï" = W."ObjectID" AND W."Superseded" IS NULL
            WHERE X."ÉÂÅï´ÉÍýÆü»ï" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."ÉÂ¼¼Ì¾", P."´µ¼ÔID"');
  __d($debug, $db, $stmt, &$data, 'Å¾ÆþÅ¾½Ð');

  $stmt = ('SELECT X."´µ¼Ô", X."Å¾¼¼¸µÉÂ¼¼", X."Å¾¼¼ÀèÉÂ¼¼",
            ' . $pex . ',
            R0."ÉÂ¼¼Ì¾" AS "Å¾¼¼¸µÉÂ¼¼Ì¾",
            R1."ÉÂ¼¼Ì¾" AS "Å¾¼¼ÀèÉÂ¼¼Ì¾"
            FROM "ÉÂÅï´ÉÍýÆü»ï¡¦Å¾¼¼" AS X JOIN "´µ¼ÔÂæÄ¢" AS P
            ON X."´µ¼Ô" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "ÉÂ¼¼°ìÍ÷É½" AS R0
            ON X."Å¾¼¼¸µÉÂ¼¼" = R0."ObjectID" AND R0."Superseded" IS NULL
            JOIN "ÉÂ¼¼°ìÍ÷É½" AS R1
            ON X."Å¾¼¼ÀèÉÂ¼¼" = R1."ObjectID" AND R1."Superseded" IS NULL
            WHERE X."ÉÂÅï´ÉÍýÆü»ï" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY P."´µ¼ÔID"');
  __d($debug, $db, $stmt, &$data, 'Å¾¼¼');

  $stmt = ('SELECT X."´µ¼Ô", X."ÉÂ¼¼", X."³°Çñ½Ð»þ´Ö", X."µ¢±¡»þ¹ï",
            X."´ÉÍýÆü»ïÍó", X."È÷¹Í",
            ' . $pex . ', R."ÉÂ¼¼Ì¾"
            FROM "ÉÂÅï´ÉÍýÆü»ï¡¦³°Çñ¡¦³°½Ð" AS X JOIN "´µ¼ÔÂæÄ¢" AS P
            ON X."´µ¼Ô" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
            ON X."ÉÂ¼¼" = R."ObjectID" AND R."Superseded" IS NULL
            WHERE X."ÉÂÅï´ÉÍýÆü»ï" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."ÉÂ¼¼Ì¾", P."´µ¼ÔID"');
  __d($debug, $db, $stmt, &$data, '³°Çñ¡¦³°½Ð');

  $stmt = ('SELECT X."´µ¼Ô", X."ÉÂ¼¼", X."Æü¶ÐÂÓÆÃµ­»ö¹à", X."Ìë¶ÐÂÓÆÃµ­»ö¹à",
            ' . $pex . ',
            R."ÉÂ¼¼Ì¾"
            FROM "ÉÂÅï´ÉÍýÆü»ï¡¦´µ¼Ô´ÉÍýÆÃµ­»ö¹à" AS X JOIN "´µ¼ÔÂæÄ¢" AS P
            ON X."´µ¼Ô" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
            ON X."ÉÂ¼¼" = R."ObjectID" AND R."Superseded" IS NULL
            WHERE X."ÉÂÅï´ÉÍýÆü»ï" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."ÉÂ¼¼Ì¾", P."´µ¼ÔID"');
  __d($debug, $db, $stmt, &$data, '´µ¼Ô´ÉÍýÆÃµ­»ö¹à');

  $stmt = ('SELECT X."Ì¾¾Î", X."ºß¸Ë¿ô", X."´ÉÍýÆü»ïÍó"
            FROM "ÉÂÅï´ÉÍýÆü»ï¡¦ºß¸Ë´ÉÍý" AS X
            WHERE X."ÉÂÅï´ÉÍýÆü»ï" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY X."Ì¾¾Î"');
  __d($debug, $db, $stmt, &$data, 'ºß¸Ë´ÉÍý');

  return $data;
}

$_lib_u_nurse_ward_manage_log_employee_work = array
  ('Æü¶Ð', 'Áá½Ð', 'ÃÙ½Ð', 'ÅöÄ¾ÌÀ¤±', 'ÅöÄ¾Æþ¤ê', '¸øµÙ', '½µµÙ',
   '·ç¶Ð', 'Í­µë', 'ÆÃÊÌµÙ²Ë', '½ÐÄ¥', '¸¦½¤', '´÷°ú', 'ÉÂ·ç');

class ward_manage_log_display0 extends simple_object_display {

  var $debug = NULL;

  var $logmsg = '';
  var $inventory_label = array('ÌôÉÊÌ¾' => 2, 'ÊªÉÊÌ¾' => 3);

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

  function nps($v, $unit='Ì¾') { // Num People String
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
    $d = $data['Á´ÈÌ'][0];
    if (! is_null($hdata)) {
      $h = $hdata['Á´ÈÌ'][0];
      $changed = ($d['CreatedBy'] != $h['CreatedBy']);
    }
    print '<tr><td colspan="2">µ­Ï¿¼Ô</td>';
    if ($changed) print '<td colspan="2" class="changed">';
    else print '<td colspan="2">';
    $this->dx_user(array(), $d['CreatedBy'], $changed);
    print '</td>';
    if (is_null($this->history_ix))
      print '<td colspan="5">&nbsp;</td>';
    else {
      print '<td colspan="2">ÊÑ¹¹¥¿¥¤¥à¥¹¥¿¥ó¥×</td><td colspan="3">';
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

    $d = $data['Á´ÈÌ'][0];

    print '<tr>';
    $this->_td(4, 0, 'ÆüÉÕ:', $d['ÆüÉÕ']);
    $this->_td(5, 0, 'ÉÂÅï´ÉÍýÆü»ï:', $d['ÉÂÅïÌ¾']);
    print "</tr>\n";

    print '<tr>';
    $this->_th(2, 0, '¶õ¾²Éô²°');
    $this->_th(2, 0, 'Æþ±¡');
    $this->_th(3, 0, 'Å¾±¡¡¦Å¾µ¢');
    $this->_th(0, 0, 'Å¾Æþ');
    $this->_th(0, 0, 'Å¾½Ð');
    print "</tr>\n";

    $discharge_sum = $this->number_of_people_in_categories
      ($d, "·Ú²÷","Å¾±¡","»àË´","Â¾¤ÎÅ¾µ¢");
    $hospitalized_sum = $this->number_of_people_in_categories
      ($d, 'Æþ±¡¡¦Æü', 'Æþ±¡¡¦Ìë');

    print '<tr>';
    $this->_td(2, 2, $this->nps($d['¶õ¾²Éô²°'], '¼¼'));
    $this->_td(0, 2, $this->nps($hospitalized_sum));
    $this->_td(0, 0, 'Æü:', $this->nps($d['Æþ±¡¡¦Æü']));
    $this->_td(1, 2, $this->nps($discharge_sum));
    $this->_td(0, 0, '·Ú²÷:', $this->nps($d["·Ú²÷"]));
    $this->_td(0, 0, '»àË´:', $this->nps($d["»àË´"]));
    $this->_td(0, 2, $this->nps($d['Å¾Æþ']));
    $this->_td(0, 2, $this->nps($d['Å¾½Ð']));
    print "</tr>\n";

    print '<tr>';
    $this->_td(0, 0, 'Ìë:', $this->nps($d['Æþ±¡¡¦Ìë']));
    $this->_td(0, 0, 'Å¾±¡:', $this->nps($d["Å¾±¡"]));
    $this->_td(0, 0, 'Â¾:', $this->nps($d["Â¾¤ÎÅ¾µ¢"]));
    print "</tr>\n";

    $current = $this->number_of_people_in_categories
      ($d, 'Ã´Á÷', '¸îÁ÷', 'ÆÈÊâ');

    print '<tr>';
    $this->_td(4, 0, 'µö²ÄÉÂ¾²¿ô', $this->nps($d['µö²ÄÉÂ¾²¿ô'], '¾²'));
    $this->_td(5, 0, '¸½ºß¿ô', $this->nps($current));
    print "</tr>\n";

    print '<tr>';
    $this->_td(3, 0, 'Ã´Á÷', $this->nps($d['Ã´Á÷']));
    $this->_td(3, 0, '¸îÁ÷', $this->nps($d['¸îÁ÷']));
    $this->_td(3, 0, 'ÆÈÊâ', $this->nps($d['ÆÈÊâ']));
    print "</tr>\n";

    print '<tr>';
    $this->_th(9, 0, '´Ç¸îÅÙÊÌ´µ¼Ô¿ô');
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
    $this->_th(9, 0, 'È÷¹Í');
    print "</tr>\n";
    print '<tr>';
    $this->_td(9, 0, mx_html_paragraph($data['Á´ÈÌ'][0]['È÷¹Í']));
    print "</tr>\n";
  }

  function draw_inventory_rows($data, $hdata) {
    if (count($data['ºß¸Ë´ÉÍý']) == 0) return;
    $tr = $data['ºß¸Ë´ÉÍý'];

    print '<tr>';
    $this->_th(9, 0, 'ºß¸Ë´ÉÍý');
    print "</tr>\n";

    $d = array();
    foreach ($tr as $e) {
      $d[$e['´ÉÍýÆü»ïÍó']][] = $e;
    }
    $lim = 0;
    foreach ($d as $col => $row) {
      if ($lim < count($row)) $lim = count($row);
    }

    print "<tr>";
    foreach ($this->inventory_label as $label => $colspan) {
      $this->_td($colspan, 0, $label);
      $this->_td(2, 0, 'ºß¸Ë¿ô');
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
	  $this->_td($colspan, 0, htmlspecialchars($e['Ì¾¾Î']));
	  $this->_td(2, 0, htmlspecialchars($e['ºß¸Ë¿ô']));
	}
	$iy++;
      }
      print "</tr>\n";
    }
  }

  function draw_external_transfer_rows($data, $hdata) {
    $ih = array(); // Æþ±¡
    $it = array(); // Å¾Æþ
    $oh = array(); // Âà±¡
    $ot = array(); // Å¾½Ð
    foreach ($data['Å¾ÆþÅ¾½Ð'] as $e) {
      switch ($e['Æþ½Ð']) {
      case 'i': $it[] = $e; break;
      case 'I': $ih[] = $e; break;
      case 'o': $ot[] = $e; break;
      case 'O': $oh[] = $e; break;
      }
    }
    $this->draw_inout_row('Æþ±¡', 'È÷¹Í', $ih);
    $this->draw_inout_row('Å¾Æþ', 'Å¾ÆþÉÂÅïÌ¾', $it);
    $this->draw_inout_row('Å¾½Ð', 'Å¾½ÐÉÂÅïÌ¾', $ot);
    $this->draw_inout_row('Âà±¡', 'È÷¹Í', $oh);
  }

  function draw_employee_rows($data, $hdata) {
    print '<tr>';
    $this->_th(9, 0, '¿¦°÷');
    print "</tr>\n";

    foreach ($this->employee_work as $ix => $label) {
	    print '<tr><th colspan="2">';
	    print htmlspecialchars($label);
	    print '</th>';
	    $a = '';
	    foreach ($data['¿¦°÷'] as $e) {
		    if ($e['´ÉÍýÆü»ïÍó'] == $ix)
			    $a = $a . $e['¿¦°÷Ì¾'] . " ";
	    }
	    $this->_td(7, 0, $a);
	    print "</tr>\n";
    }
  }

  function draw_patient_notes_rows($data, $hdata) {
    if (0 == count($data['´µ¼Ô´ÉÍýÆÃµ­»ö¹à'])) return;

    $tr = $data['´µ¼Ô´ÉÍýÆÃµ­»ö¹à'];
    print '<tr>';
    $this->_th(9, 0, '´µ¼Ô´ÉÍýÆÃµ­»ö¹à');
    print "</tr>\n";

    print '<tr>';
    $this->_td(1, 0, 'Éô²°ÈÖ¹æ');
    $this->_td(1, 0, '´µ¼ÔÌ¾');
    $this->_td(1, 0, 'Ç¯Îð');
    $this->_td(3, 0, 'Æü¶ÐÂÓÆÃµ­»ö¹à');
    $this->_td(3, 0, 'Ìë¶ÐÂÓÆÃµ­»ö¹à');
    print "</tr>\n";

    foreach ($tr as $e) {
      print '<tr>';
      $this->_td(1, 0, $e['ÉÂ¼¼Ì¾']);
      $this->_td(1, 0, $e['´µ¼ÔÌ¾']);
      $this->_td(1, 0, $e['´µ¼ÔÇ¯Îð']);
      $this->_td(3, 0, htmlspecialchars($e['Æü¶ÐÂÓÆÃµ­»ö¹à']));
      $this->_td(3, 0, htmlspecialchars($e['Ìë¶ÐÂÓÆÃµ­»ö¹à']));
      print "</tr>\n";
    }
  }

  function draw_temporary_out_rows($data, $hdata) {
    if (0 == count($data['³°Çñ¡¦³°½Ð'])) return;

    $tr = $data['³°Çñ¡¦³°½Ð'];
    print '<tr>';
    $this->_th(9, 0, '³°Çñ¡¦³°½Ð');
    print "</tr>\n";

    print '<tr>';
    $this->_td(1, 0, 'Éô²°ÈÖ¹æ');
    $this->_td(1, 0, '´µ¼ÔÌ¾');
    $this->_td(1, 0, 'Çñ¡¦½Ð');
    $this->_td(2, 0, '³°Çñ½Ð»þ´Ö');
    $this->_td(2, 0, 'µ¢±¡»þ´Ö');
    $this->_td(2, 0, 'È÷¹Í');
    print "</tr>\n";

    foreach ($tr as $e) {
      switch ($e['´ÉÍýÆü»ïÍó']) {
      case 0: $stay_shape = '³°Çñ'; break;
      case 1: $stay_shape = '³°½Ð'; break;
      }
      print '<tr>';
      $this->_td(1, 0, $e['ÉÂ¼¼Ì¾']);
      $this->_td(1, 0, $e['´µ¼ÔÌ¾']);
      $this->_td(1, 0, $stay_shape);
      $this->_td(2, 0, $e['³°Çñ½Ð»þ´Ö']);
      $this->_td(2, 0, $e['µ¢±¡»þ¹ï']);
      $this->_td(2, 0, htmlspecialchars($e['È÷¹Í']));
      print "</tr>\n";
    }
  }

  function draw_transfer_rows($data, $hdata) {
    if (0 == count($data['Å¾¼¼'])) return;

    $tr = $data['Å¾¼¼'];
    print '<tr>';
    $this->_th(9, 0, 'Å¾¼¼');
    print "</tr>\n";

    print '<tr>';
    $this->_td(2, 0, '´µ¼ÔÌ¾');
    $this->_td(2, 0, 'Éô²°ÈÖ¹æ¢ªÉô²°ÈÖ¹æ');
    $this->_td(2, 0, '´µ¼ÔÌ¾');
    $this->_td(3, 0, 'Éô²°ÈÖ¹æ¢ªÉô²°ÈÖ¹æ');
    print "</tr>\n";

    $lim = count($tr);
    if ($lim % 2) { $lim++; }
    for ($ix = 0; $ix < $lim; $ix += 2) {
      print '<tr>';

      $n = $tr[$ix]['´µ¼ÔÌ¾'];
      $t = $tr[$ix]['Å¾¼¼¸µÉÂ¼¼Ì¾'] . '¢ª' .  $tr[$ix]['Å¾¼¼ÀèÉÂ¼¼Ì¾'];
      $this->_td(2, 0, $n);
      $this->_td(2, 0, $t);

      if ($ix+1 < count($tr)) {
	$n = $tr[$ix+1]['´µ¼ÔÌ¾'];
	$t = $tr[$ix+1]['Å¾¼¼¸µÉÂ¼¼Ì¾'] . '¢ª' .  $tr[$ix+1]['Å¾¼¼ÀèÉÂ¼¼Ì¾'];
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
      $this->_td(1, 0, "Éô²°ÈÖ¹æ");
      $this->_td(1, 0, "´µ¼ÔÈÖ¹æ");
      $this->_td(1, 0, "´µ¼ÔÌ¾");
      $this->_td(1, 0, "Ç¯Îð");
      $this->_td(5, 0, $foo);
      print "</tr>\n";

      foreach ($data as $e) {
	$comment = $e['Å¾ÅïÉÂÅïÌ¾'];
	if ($e['È÷¹Í'])
	  $comment = $e['È÷¹Í'];
	print '<tr>';
	$this->_td(1, 0, $e['ÉÂ¼¼Ì¾']);
	$this->_td(1, 0, $e['´µ¼ÔID']);
	$this->_td(1, 0, $e['´µ¼ÔÌ¾']);
	$this->_td(1, 0, $e['´µ¼ÔÇ¯Îð']);
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
    $ws_null_col = array('Á´ÈÌ' => array('»ÕÄ¹', 'ÉôÄ¹'),
			 'Å¾ÆþÅ¾½Ð' => array('Å¾ÅïÉÂÅï'),
			 '³°Çñ¡¦³°½Ð' => array('³°Çñ½Ð»þ´Ö', 'µ¢±¡»þ¹ï'),
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
      $add_switch = array('2-I' => array('Å¾ÆþÅ¾½Ð', array('Æþ½Ð' => 'I')),
			  '2-i' => array('Å¾ÆþÅ¾½Ð', array('Æþ½Ð' => 'i')),
			  '2-o' => array('Å¾ÆþÅ¾½Ð', array('Æþ½Ð' => 'o')),
			  '2-O' => array('Å¾ÆþÅ¾½Ð', array('Æþ½Ð' => 'O')),
			  '3' => array('Å¾¼¼', array()),
			  '4-0' => array('³°Çñ¡¦³°½Ð',
					 array('´ÉÍýÆü»ïÍó' => 0)),
			  '4-1' => array('³°Çñ¡¦³°½Ð',
					 array('´ÉÍýÆü»ïÍó' => 1)),
			  '5' => array('´µ¼Ô´ÉÍýÆÃµ­»ö¹à', array()),
			  '6-0' => array('ºß¸Ë´ÉÍý', array('´ÉÍýÆü»ïÍó' => 0)),
			  '6-1' => array('ºß¸Ë´ÉÍý', array('´ÉÍýÆü»ïÍó' => 1)),
			  );
      if (array_key_exists($addrow, $add_switch)) {
	$as = $add_switch[$addrow];
	$this->data[$as[0]][] = $as[1];
      }
    }

    if (array_key_exists($this->prefix . 'DelRow', $_REQUEST)) {
      $delrow = $_REQUEST[$this->prefix . 'DelRow'];
      $delkey = substr($delrow, 0, 2);
      $del_switch = array('2-' => 'Å¾ÆþÅ¾½Ð',
			  '3-' => 'Å¾¼¼',
			  '4-' => '³°Çñ¡¦³°½Ð',
			  '5-' => '´µ¼Ô´ÉÍýÆÃµ­»ö¹à',
			  '6-' => 'ºß¸Ë´ÉÍý');
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
	// ¿¦°÷
	$slot = substr($subpick, 2);
	$ss = array();
	foreach ($this->data['¿¦°÷'] as $row)
	  if ($row['´ÉÍýÆü»ïÍó'] == $slot)
	    $ss[] = mx_form_escape_key(array($row['¿¦°÷'], $row['¿¦°÷Ì¾']));
	$subconfig = $_lib_u_nurse_ward_employee_pick_cfg;
	$subconfig['Ward'] = $this->data['Á´ÈÌ'][0]['ÉÂÅï'];
	$subconfig['Select'] = $ss;
	$subconfig['Title'] = '¿¦°÷('.$this->employee_work[$slot].')¤òÁªÂò';
	$this->_Subpicker = new ward_employee_pick($this->prefix . 'wep-',
						   $subconfig);

	$chosen = $this->_Subpicker->chosen();
	if (is_array($chosen)) {
	  $this->_Subpicker = NULL;
	  $d = array();
	  foreach ($this->data['¿¦°÷'] as $row)
	    if ($row['´ÉÍýÆü»ïÍó'] != $slot)
	      $d[] = $row;
	  foreach ($chosen as $v) {
	    $a = mx_form_unescape_key($v);
	    $d[] = array('¿¦°÷' => $a[0],
			 '¿¦°÷Ì¾' => $a[1],
			 '´ÉÍýÆü»ïÍó' => $slot);
	  }
	  $this->data['¿¦°÷'] = $d;
	}
      }
      elseif (substr($subpick, 0, 4) == '2-0-' ||
	      substr($subpick, 0, 4) == '3-0-' ||
	      substr($subpick, 0, 4) == '4-0-' ||
	      substr($subpick, 0, 4) == '5-0-') {
	// Å¾ÆþÅ¾½Ð¡Ê´µ¼Ô¡Ë or Å¾¼¼¡Ê´µ¼Ô¡Ëor ÆÃµ­»ö¹à¡Ê´µ¼Ô¡Ë
	// or ³°Çñ¡¦³°½Ð¡Ê´µ¼Ô¡Ë
	$ix = substr($subpick, 4);
	$ty = substr($subpick, 0, 1);
	$idl = array('´µ¼Ô', '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð', 'ÉÂ¼¼', 'ÉÂ¼¼Ì¾');
	switch ($ty) {
	case '2': $page = 'Å¾ÆþÅ¾½Ð'; break;
	case '3':
	  $idl = array('´µ¼Ô', '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð',
		       'Å¾¼¼¸µÉÂ¼¼', 'Å¾¼¼¸µÉÂ¼¼Ì¾');
	  $page = 'Å¾¼¼'; break;
	case '4': $page = '³°Çñ¡¦³°½Ð'; break;
	case '5': $page = '´µ¼Ô´ÉÍýÆÃµ­»ö¹à'; break;
	}
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_patient_cfg;
	$subconfig['Ward'] = $this->data['Á´ÈÌ'][0]['ÉÂÅï'];
	$this->_Subpicker = new list_of_ward_patients($this->prefix . 'lwp-',
						      $subconfig);
	$this->_Subpicker->Title = "´µ¼ÔÌ¾¤òÀßÄê";
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
	// Å¾ÆþÅ¾½Ð¡ÊÉÂ¼¼¡Ëor
	// Å¾¼¼¡ÊÅ¾¼¼¸µÉÂ¼¼¡Ëor Å¾¼¼¡ÊÅ¾¼¼ÀèÉÂ¼¼¡Ëor
	// ÆÃµ­»ö¹à¡ÊÉÂ¼¼¡Ëor ³°Çñ¡¦³°½Ð¡ÊÉÂ¼¼¡Ë
	$ix = substr($subpick, 4);
	$ty = substr($subpick, 0, 4);
	$idl = array('ÉÂ¼¼', 'ÉÂ¼¼Ì¾');
	switch ($ty) {
	case '2-1-': $page = 'Å¾ÆþÅ¾½Ð'; break;
  	case '3-1-':
	  $idl = array('Å¾¼¼¸µÉÂ¼¼', 'Å¾¼¼¸µÉÂ¼¼Ì¾');
	  $page = 'Å¾¼¼';
	  break;
  	case '3-2-':
	  $idl = array('Å¾¼¼ÀèÉÂ¼¼', 'Å¾¼¼ÀèÉÂ¼¼Ì¾');
	  $page = 'Å¾¼¼';
	  break;
	case '4-1-': $page = '³°Çñ¡¦³°½Ð'; break;
	case '5-1-': $page = '´µ¼Ô´ÉÍýÆÃµ­»ö¹à'; break;
	}
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_room_cfg;
	$subconfig['Ward'] = $this->data['Á´ÈÌ'][0]['ÉÂÅï'];
	$this->_Subpicker = new list_of_ward_rooms($this->prefix . 'lwr-',
						   $subconfig);
	$this->_Subpicker->Title = "ÉÂ¼¼¤òÁªÂò";
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
	// Å¾ÆþÅ¾½Ð¡ÊÅ¾ÅïÉÂÅï¡Ë
	$ix = substr($subpick, 4);
	$idl = array('Å¾ÅïÉÂÅï', 'Å¾ÅïÉÂÅïÌ¾');
	$page = 'Å¾ÆþÅ¾½Ð';
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_cfg;
	$this->_Subpicker = new list_of_wards($this->prefix . 'lww-',
					      $subconfig);
	$this->_Subpicker->Title = "Å¾ÅïÉÂÅï¤òÁªÂò";
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
    foreach (array_merge(array("ÆüÉÕ",
			       "¶õ¾²Éô²°",
			       "Æþ±¡¡¦Æü", "Æþ±¡¡¦Ìë", "·Ú²÷", "Å¾±¡",
			       "»àË´", "Â¾¤ÎÅ¾µ¢", "Å¾Æþ", "Å¾½Ð",
			       "Ã´Á÷", "¸îÁ÷", "ÆÈÊâ"),
			 _lib_u_nurse_ward_manage_log_A1C4()) as $asc) {
      $o = $v = $this->data['Á´ÈÌ'][0][$asc];
      if (! is_array($v) && ! is_null($v)) {
	$v = mb_convert_kana($v, 'as', 'euc');
	if ($v != $o) {
	  $this->dbglog("Kana $o => $v\n");
	  $this->data['Á´ÈÌ'][0][$asc] = $v;
	}
      }
    }

    for ($ix = 0; $ix < count($this->data['³°Çñ¡¦³°½Ð']); $ix++) {
      foreach (array("³°Çñ½Ð»þ´Ö", "µ¢±¡»þ¹ï") as $asc) {
	$o = $v = $this->data['³°Çñ¡¦³°½Ð'][$ix][$asc];
	$v = mb_convert_kana($v, 'as', 'euc');
	if ($v != $o) {
	  $this->dbglog("Kana $o => $v\n");
	  $this->data['³°Çñ¡¦³°½Ð'][$ix][$asc] = $v;
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
      mx_formi_submit($this->prefix . 'commit', 'ÊÔ½¸´°Î»');
      mx_formi_submit($this->prefix . 'rollback', 'ÊÔ½¸Ãæ»ß');
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
    // "ÆüÉÕ", "ÉÂÅï", "Ã´Á÷", "¸îÁ÷",
    // "ÆÈÊâ", "A1".."C4", "È÷¹Í", "¶õ¾²Éô²°",
    // "Æþ±¡¡¦Æü", "Æþ±¡¡¦Ìë", "·Ú²÷", "Å¾±¡",
    // "»àË´", "Â¾¤ÎÅ¾µ¢", "Å¾Æþ", "Å¾½Ð",
    // "»ÕÄ¹", "ÉôÄ¹", "ÉÂÅïÌ¾",
    // "µö²ÄÉÂ¾²¿ô",
    // "»ÕÄ¹Ì¾", "ÉôÄ¹Ì¾"
    $pfx = $this->prefix . 0 . '-data-0-';
    $p = $this->data['Á´ÈÌ'][0];
    $oe = array(0 => 'e', 1 => 'o');
    $oex = 1;

    $ime_opt = array('ime' => 'disabled');

    print '<table class="listofstuff">';

    $this->dx_ro($oe[$oex = 1 - $oex], $pfx, $p, 'ÆüÉÕ');
    $this->dx_ro($oe[$oex = 1 - $oex], $pfx, $p, 'ÉÂÅïÌ¾');

    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '¶õ¾²Éô²°', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Æþ±¡¡¦Æü', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Æþ±¡¡¦Ìë', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '·Ú²÷', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Å¾±¡', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '»àË´', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Â¾¤ÎÅ¾µ¢', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Å¾Æþ', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Å¾½Ð', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'Ã´Á÷', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '¸îÁ÷', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'ÆÈÊâ', $ime_opt);

    print "<tr><th colspan=\"2\">´Ç¸îÅÙÊÌ´µ¼Ô¿ô</th><tr>\n";
    foreach (array('A', 'B', 'C') as $c0)
      foreach (array('1', '2', '3', '4') as $c1)
	$this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, "$c0$c1", $ime_opt);

    $this->dx_textarea($oe[$oex = 1 - $oex], $pfx, $p, 'È÷¹Í');

    print "</table>\n";
    $this->dx_hidden($pfx, $p, 'ÉÂÅï');
    $this->dx_hidden($pfx, $p, 'µö²ÄÉÂ¾²¿ô');
    $this->dx_hidden($pfx, $p, '»ÕÄ¹');
    $this->dx_hidden($pfx, $p, 'ÉôÄ¹');
    $this->dx_hidden($pfx, $p, '»ÕÄ¹Ì¾');
    $this->dx_hidden($pfx, $p, 'ÉôÄ¹Ì¾');
  }

  function patient_label($p) {
    if (trim($p['´µ¼Ô']) != '')
      return sprintf("%s %s (%s ºÐ)",
		     $p['´µ¼ÔID'], $p['´µ¼ÔÌ¾'], $p['´µ¼ÔÇ¯Îð']);
    else
      return "(Ì¤ÀßÄê)";
  }

  function draw_page_1() {
    // '¿¦°÷' => array("¿¦°÷", "¿¦°÷Ì¾", "´ÉÍýÆü»ïÍó"),
    $cfg = $this->so_config['Pages']['¿¦°÷'];
    $pfx = $this->prefix . 1 . '-data-';
    $p = $this->data['¿¦°÷'];
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
	if ($row['´ÉÍýÆü»ïÍó'] == $slot) {
	  if ($it++) print "<br />";
	  print $row['¿¦°÷Ì¾'];
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
    // 'Å¾ÆþÅ¾½Ð' => array("´µ¼Ô", "ÉÂ¼¼", "Æþ½Ð", "Å¾ÅïÉÂÅï", "È÷¹Í",
    // "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
    // "Å¾ÅïÉÂÅïÌ¾", "ÉÂ¼¼Ì¾"),
    $cfg = $this->so_config['Pages']['Å¾ÆþÅ¾½Ð'];
    $pfx = $this->prefix . 2 . '-data-';
    $p = $this->data['Å¾ÆþÅ¾½Ð'];

    print '<table class="listofstuff">';
    $iy = 0;
    foreach (array('I' => 'Æþ±¡', 'i' => 'Å¾Æþ',
		   'o' => 'Å¾½Ð', 'O' => 'Âà±¡') as $slot => $slotname) {
      print "<tr><th colspan=\"2\">$slotname</th></tr>\n";
      for ($ix = 0; $ix < count($p); $ix++) {
	if ($p[$ix]['Æþ½Ð'] != $slot) continue;
	$evenodd = ($iy % 2) ? "o" : "e";

	print "<tr class=\"$evenodd\"><th>´µ¼Ô</th><td>";
	foreach (array('´µ¼Ô', '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð',
		       'ÉÂ¼¼', 'ÉÂ¼¼Ì¾', 'Æþ½Ð',
		       'Å¾ÅïÉÂÅï', 'Å¾ÅïÉÂÅïÌ¾') as $col) {
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

	$label = 'ÉÂ¼¼';
	print "<tr class=\"$evenodd\"><th>$label</th><td>";
	$value = trim($p[$ix][$label . 'Ì¾']);
	if ($value == '')
	  $value = '(Ì¤ÀßÄê)';
	if ($this->_Subpicker)
	  print $value;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '2-1-' . $ix,
			  "<span class=\"link\">$value</span>");
	print "</td></tr>\n";

	if ($slot == 'i' || $slot == 'o') {
	  $label = 'Å¾ÅïÉÂÅï';
	  $show = ($slot == 'i') ? 'Å¾ÆþÉÂÅï' : 'Å¾½ÐÉÂÅï';
	  print "<tr class=\"$evenodd\"><th>$show</th><td>";
	  $value = trim($p[$ix][$label . 'Ì¾']);
	  if ($value == '')
	    $value = '(Ì¤ÀßÄê)';
	  if ($this->_Subpicker)
	    print $value;
	  else
	    mx_formi_submit($this->prefix . 'Subpick', '2-2-' . $ix,
			    "<span class=\"link\">$value</span>");
	  print "</td></tr>\n";
	}
	else {
	  $col = 'È÷¹Í';
	  print "<tr class=\"$evenodd\"><th>$col</th><td>";
	  $cnx = mx_form_encode_name($col);
	  mx_formi_textarea(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	  print "</td></tr>\n";
	}
	if (! $this->_Subpicker) {
	  print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	  mx_formi_submit($this->prefix . 'DelRow', '2-' . $ix,
			  "<span class=\"link\">¤³¤Î¹àÌÜ¤òºï½ü</span>");
	  print "</td></tr>\n";
	}
	$iy++;
      }
      if (! $this->_Subpicker) {
	print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'AddRow', '2-' . $slot,
			"<span class=\"link\">¹àÌÜ¤òÄÉ²Ã</span>");
	print "</td></tr>\n";
      }
    }
    print "</table>\n";
  }

  function draw_page_3() {
    // 'Å¾¼¼' => array("´µ¼Ô", "Å¾¼¼¸µÉÂ¼¼", "Å¾¼¼ÀèÉÂ¼¼",
    // "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
    // "Å¾¼¼¸µÉÂ¼¼Ì¾", "Å¾¼¼ÀèÉÂ¼¼Ì¾"),
    $cfg = $this->so_config['Pages']['Å¾¼¼'];
    $pfx = $this->prefix . 3 . '-data-';
    $p = $this->data['Å¾¼¼'];

    print '<table class="listofstuff">';
    for ($ix = 0; $ix < count($p); $ix++) {
      $evenodd = ($ix % 2) ? "o" : "e";

      print "<tr class=\"$evenodd\"><th>´µ¼Ô</th><td>";
      foreach (array('´µ¼Ô', '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð',
		     'Å¾¼¼¸µÉÂ¼¼', 'Å¾¼¼¸µÉÂ¼¼Ì¾',
		     'Å¾¼¼ÀèÉÂ¼¼', 'Å¾¼¼ÀèÉÂ¼¼Ì¾') as $col) {
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

      foreach (array(1 => 'Å¾¼¼¸µ', 2 => 'Å¾¼¼Àè') as $slot => $col) {
	$label = $col . 'ÉÂ¼¼';
	print "<tr class=\"$evenodd\"><th>$label</th><td>";
	$value = trim($p[$ix][$label . 'Ì¾']);
	if ($value == '')
	  $value = '(Ì¤ÀßÄê)';
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
			"<span class=\"link\">¤³¤Î¹àÌÜ¤òºï½ü</span>");
	print "</td></tr>\n";
      }
    }
    if (! $this->_Subpicker) {
      print "<tr><td colspan=\"2\">";
      mx_formi_submit($this->prefix . 'AddRow', 3,
		      "<span class=\"link\">¹àÌÜ¤òÄÉ²Ã</span>");
      print "</td></tr>\n";
    }
    print "</table>\n";
  }

  function draw_page_4() {
    // '³°Çñ¡¦³°½Ð' => array("´µ¼Ô", "ÉÂ¼¼", "´ÉÍýÆü»ïÍó",
    // "³°Çñ½Ð»þ´Ö", "µ¢±¡»þ¹ï",
    // "È÷¹Í","´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð", "ÉÂ¼¼Ì¾"),
    $cfg = $this->so_config['Pages']['³°Çñ¡¦³°½Ð'];
    $pfx = $this->prefix . 4 . '-data-';
    $p = $this->data['³°Çñ¡¦³°½Ð'];

    $this->__draw_page_sub_45($cfg, $pfx, $p, 4,
			      array('³°Çñ½Ð»þ´Ö' => 'text-i',
				    'µ¢±¡»þ¹ï' => 'text-i',
				    'È÷¹Í' => 'textarea'),
			      array('´ÉÍýÆü»ïÍó',
				    array(0 => '³°Çñ',
					  1 => '³°½Ð')));
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
	print "<tr class=\"$evenodd\"><th>´µ¼Ô</th><td>";
	foreach (array('´µ¼Ô', '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð',
		       'ÉÂ¼¼', 'ÉÂ¼¼Ì¾') as $col) {
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

	$label = $p[$ix]['ÉÂ¼¼Ì¾'];
	if (trim($label) == '') $label = '(Ì¤ÀßÄê)';
	print "<tr class=\"$evenodd\"><th>ÉÂ¼¼</th><td>";
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
			  "<span class=\"link\">¤³¤Î¹àÌÜ¤òºï½ü</span>");
	  print "</td></tr>\n";
	}

      }

      if (! is_null($subfield) &&
	  ! $this->_Subpicker && (! $ccnt || $p[$lix]['´µ¼Ô'] != '')) {
	print "<tr><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'AddRow', "$pg-$pickkey",
			"<span class=\"link\">¹àÌÜ¤òÄÉ²Ã</span>");
	print "</td></tr>\n";
      }
    }

    print "</table>\n";

    if (is_null($subloop)) {
      if (! $this->_Subpicker &&
	  (! count($p) || $p[count($p)-1]['´µ¼Ô'] != ''))
	mx_formi_submit($this->prefix . 'AddRow', $pg,
			"<span class=\"link\">¹àÌÜ¤òÄÉ²Ã</span>");
    }
  }

  function draw_page_5() {
    // '´µ¼Ô´ÉÍýÆÃµ­»ö¹à' => array("´µ¼Ô", "ÉÂ¼¼",
    // "Æü¶ÐÂÓÆÃµ­»ö¹à", "Ìë¶ÐÂÓÆÃµ­»ö¹à",
    // "´µ¼ÔÌ¾", "´µ¼ÔID", "´µ¼ÔÇ¯Îð",
    // "ÉÂ¼¼Ì¾"),
    $cfg = $this->so_config['Pages']['´µ¼Ô´ÉÍýÆÃµ­»ö¹à'];
    $pfx = $this->prefix . 5 . '-data-';
    $p = $this->data['´µ¼Ô´ÉÍýÆÃµ­»ö¹à'];

    $this->__draw_page_sub_45($cfg, $pfx, $p, 5,
			      array('Æü¶ÐÂÓÆÃµ­»ö¹à' => 'textarea',
				    'Ìë¶ÐÂÓÆÃµ­»ö¹à' => 'textarea'));
  }

  function draw_page_6() {
    // 'ºß¸Ë´ÉÍý' => array("Ì¾¾Î", "ºß¸Ë¿ô", "´ÉÍýÆü»ïÍó"));
    $cfg = $this->so_config['Pages']['ºß¸Ë´ÉÍý'];
    $pfx = $this->prefix . 6 . '-data-';
    $p = $this->data['ºß¸Ë´ÉÍý'];
    print '<table class="listofstuff">';

    foreach (array(0 => 'ÌôÉÊ', 1 => 'ÊªÉÊ') as $slot => $slotname) {
      print "<tr><th colspan=\"3\">$slotname</th></tr>\n";
      print "<tr><th>Ì¾¾Î</th><th>ºß¸Ë¿ô</th><th>(¹Ôºï½ü)</th></tr>\n";
      $iy = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	if ($p[$ix]['´ÉÍýÆü»ïÍó'] != $slot)
	  continue;
	$evenodd = ($iy % 2) ? "o" : "e";
	print "<tr class=\"$evenodd\">";
	foreach (array('Ì¾¾Î','ºß¸Ë¿ô') as $col) {
	  print "<td>";
	  mx_formi_text($pfx . $ix . '-' . mx_form_encode_name($col),
			$p[$ix][$col]);
	  print "</td>";
	}
	print "<td>";
	$col = '´ÉÍýÆü»ïÍó';
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
		      "<span class=\"link\">(¹ÔÄÉ²Ã)</span><br />");
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
		mx_db_sql_quote($d['Á´ÈÌ'][0][$col]));
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

    // foreach (array('»ÕÄ¹', 'ÉôÄ¹') as $col)
    // if ($d['Á´ÈÌ'][0][$col] == '')
    // $d['Á´ÈÌ'][0][$col] = NULL;

    $stmt = (($this->insert_stmt_head) . 'VALUES (' . "$o");
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= ",\n " . mx_db_sql_quote($d['Á´ÈÌ'][0][$col]);
    $stmt .= ')';
    return $stmt;
  }

  function _validate() {
    $bad = 0;
    $null_bad_col = array('Á´ÈÌ' => array(),
			  '¿¦°÷' => array('¿¦°÷'),
			  'Å¾ÆþÅ¾½Ð' => array('´µ¼Ô', 'ÉÂ¼¼'),
			  'Å¾¼¼' => array('´µ¼Ô', 'Å¾¼¼¸µÉÂ¼¼', 'Å¾¼¼ÀèÉÂ¼¼'),
			  '³°Çñ¡¦³°½Ð' => array('´µ¼Ô', 'ÉÂ¼¼'),
			  '´µ¼Ô´ÉÍýÆÃµ­»ö¹à'=> array('´µ¼Ô', 'ÉÂ¼¼'),
			  'ºß¸Ë´ÉÍý' => array('Ì¾¾Î', 'ºß¸Ë¿ô'));
    $pos_num_col = array('Á´ÈÌ' => array_merge
			 (array
			  ("¶õ¾²Éô²°", "Æþ±¡¡¦Æü", "Æþ±¡¡¦Ìë",
			   "·Ú²÷", "Å¾±¡", "»àË´", "Â¾¤ÎÅ¾µ¢",
			   "Å¾Æþ", "Å¾½Ð", "Ã´Á÷", "¸îÁ÷", "ÆÈÊâ"),
			  _lib_u_nurse_ward_manage_log_A1C4()),
			 );

    $time_of_day_or_null_col = array
      ('³°Çñ¡¦³°½Ð' => array('³°Çñ½Ð»þ´Ö', 'µ¢±¡»þ¹ï'));

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

    foreach ($this->data['³°Çñ¡¦³°½Ð'] as $ix => $row)
      if (($row['´ÉÍýÆü»ïÍó'] == 1) &&
	  (is_null($row['³°Çñ½Ð»þ´Ö']) || is_null($row['µ¢±¡»þ¹ï']))) {
	$this->err("³°½Ð¤Î³°Çñ½Ð»þ´Ö¤äµ¢±¡»þ¹ï¤Ï¶õ¤Ç¤Ï¤¤¤±¤Þ¤»¤ó\n");
	$bad++;
      }

    foreach ($this->data['Å¾ÆþÅ¾½Ð'] as $ix => $row)
      if (($row['Æþ½Ð'] == 'i' || $row['Æþ½Ð'] == 'o') &&
	  ($st = mx_db_validate_length($row['Å¾ÅïÉÂÅï'], 1, NULL)) != '') {
	$this->err("(Å¾ÆþÅ¾½Ð) Å¾Æþ¡¦Å¾½Ð¤Ç¤ÎÅ¾ÅïÉÂÅï¤Ï¶õ¤Ç¤Ï¤¤¤±¤Þ¤»¤ó\n");
	$bad++;
      }

    foreach ($this->data['Å¾¼¼'] as $ix => $row)
      if ($row['Å¾¼¼¸µÉÂ¼¼'] == $row['Å¾¼¼ÀèÉÂ¼¼']) {
	$this->err("(Å¾¼¼) Å¾¼¼¸µÉÂ¼¼¤ÈÅ¾¼¼ÀèÉÂ¼¼¤¬Æ±¤¸¤Ç¤Ï¤¤¤±¤Þ¤»¤ó\n");
	$bad++;
      }

    $rm = $pt = $conflict = $ptname = $rmname = NULL;
    $this->summarize_patient_movement($rm, $pt, $conflict,
				      $ptname, $rmname);

    foreach ($conflict as $ix => $row) {
      $this->err("Å¾¼¼¡¦Å¾ÆþÅ¾½Ð: ´µ¼Ô $row ¤¬Ê£¿ô²ó»ØÄê¤µ¤ì¤Æ¤¤¤Þ¤¹\n");
      $bad++;
    }


    $tot_num = ($this->data['Á´ÈÌ'][0]['Ã´Á÷'] +
		$this->data['Á´ÈÌ'][0]['¸îÁ÷'] +
		$this->data['Á´ÈÌ'][0]['ÆÈÊâ']);
    $sub_num = 0;
    foreach (_lib_u_nurse_ward_manage_log_A1C4() as $col)
      $sub_num += $this->data['Á´ÈÌ'][0][$col];

    if ($tot_num != $sub_num) {
      $this->err("(Á´ÈÌ) ¸½ºß¿ô $tot_num ¤È".
		 "´Ç¸îÅÙÊÌ´µ¼Ô¿ô¤ÎÁí·× $sub_num ¤¬°ìÃ×¤·¤Þ¤»¤ó\n");
      $bad++;
    }

    if (! $bad)
      return 'ok';
  }

  function _update_subtables(&$db, $id, $stash_id) {
    $subtable = array
      (array("ÉÂÅï´ÉÍýÆü»ï¡¦¿¦°÷", "¿¦°÷",
	     array("¿¦°÷", "´ÉÍýÆü»ïÍó")),
       array("ÉÂÅï´ÉÍýÆü»ï¡¦Å¾ÆþÅ¾½Ð", "Å¾ÆþÅ¾½Ð",
	     array("´µ¼Ô", "ÉÂ¼¼", "Æþ½Ð", "Å¾ÅïÉÂÅï", "È÷¹Í")),
       array("ÉÂÅï´ÉÍýÆü»ï¡¦Å¾¼¼", "Å¾¼¼",
	     array("´µ¼Ô", "Å¾¼¼¸µÉÂ¼¼", "Å¾¼¼ÀèÉÂ¼¼")),
       array("ÉÂÅï´ÉÍýÆü»ï¡¦³°Çñ¡¦³°½Ð", "³°Çñ¡¦³°½Ð",
	     array("´µ¼Ô", "ÉÂ¼¼", "´ÉÍýÆü»ïÍó",
		   "³°Çñ½Ð»þ´Ö", "µ¢±¡»þ¹ï", "È÷¹Í")),
       array("ÉÂÅï´ÉÍýÆü»ï¡¦´µ¼Ô´ÉÍýÆÃµ­»ö¹à", "´µ¼Ô´ÉÍýÆÃµ­»ö¹à",
	     array("´µ¼Ô", "ÉÂ¼¼", "Æü¶ÐÂÓÆÃµ­»ö¹à", "Ìë¶ÐÂÓÆÃµ­»ö¹à")),
       array("ÉÂÅï´ÉÍýÆü»ï¡¦ºß¸Ë´ÉÍý", "ºß¸Ë´ÉÍý",
	     array("Ì¾¾Î", "ºß¸Ë¿ô", "´ÉÍýÆü»ïÍó")),
       );

    if (! is_null($stash_id)) {
      // Current rows in subtables should point at $stash_id
      foreach ($subtable as $d) {
	$st = $d[0];
	$stmt = ('UPDATE "' . $st . '" SET "ÉÂÅï´ÉÍýÆü»ï" = ' .
		 mx_db_sql_quote($stash_id) .
		 ' WHERE "ÉÂÅï´ÉÍýÆü»ï" = ' .
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
	  $stmt = ('INSERT INTO "' . $st . '" ("ÉÂÅï´ÉÍýÆü»ï", ' .
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
    foreach ($this->data['Å¾ÆþÅ¾½Ð'] as $ent) {
      $ptname[$ent['´µ¼Ô']] = $ent['´µ¼ÔÌ¾'];
      $rmname[$ent['ÉÂ¼¼']] = $ent['ÉÂ¼¼Ì¾'];
      if (array_key_exists($ent['´µ¼Ô'], $pt_seen))
	$conflict[$ent['´µ¼Ô']] = $ent['´µ¼ÔÌ¾'];

      $pt_seen[$ent['´µ¼Ô']] = 1;
      switch ($ent['Æþ½Ð']) {
      case 'i':
      case 'I':
	$pt[$ent['´µ¼Ô']] = $ent['ÉÂ¼¼'];
	$rm[$ent['ÉÂ¼¼']][] = array('+', $ent['´µ¼Ô']);
	break;
      case 'o':
      case 'O':
	$pt[$ent['´µ¼Ô']] = NULL;
	$rm[$ent['ÉÂ¼¼']][] = array('-', $ent['´µ¼Ô']);
	break;
      }
    }

    foreach ($this->data['Å¾¼¼'] as $ent) {
      $ptname[$ent['´µ¼Ô']] = $ent['´µ¼ÔÌ¾'];
      $rmname[$ent['Å¾¼¼¸µÉÂ¼¼']] = $ent['Å¾¼¼¸µÉÂ¼¼Ì¾'];
      $rmname[$ent['Å¾¼¼ÀèÉÂ¼¼']] = $ent['Å¾¼¼ÀèÉÂ¼¼Ì¾'];
      if (array_key_exists($ent['´µ¼Ô'], $pt_seen))
	$conflict[$ent['´µ¼Ô']] = $ent['´µ¼ÔÌ¾'];

      $pt[$ent['´µ¼Ô']] = $ent['Å¾¼¼ÀèÉÂ¼¼'];
      $rm[$ent['Å¾¼¼¸µÉÂ¼¼']][] = array('-', $ent['´µ¼Ô']);
      $rm[$ent['Å¾¼¼ÀèÉÂ¼¼']][] = array('+', $ent['´µ¼Ô']);
    }

    // These records say where they are without mentioning explicit movements.
    foreach (array('³°Çñ¡¦³°½Ð', '´µ¼Ô´ÉÍýÆÃµ­»ö¹à') as $slot) {
      foreach ($this->data[$slot] as $ent) {
	$ptname[$ent['´µ¼Ô']] = $ent['´µ¼ÔÌ¾'];
	$rmname[$ent['ÉÂ¼¼']] = $ent['ÉÂ¼¼Ì¾'];
	if (array_key_exists($ent['´µ¼Ô'], $pt_seen))
	  continue;
	$pt[$ent['´µ¼Ô']] = $ent['ÉÂ¼¼'];
	$rm[$ent['ÉÂ¼¼']][] = array('+', $ent['´µ¼Ô']);
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
    $stmt = ('SELECT RP."ÉÂ¼¼", R."ÉÂ¼¼Ì¾", RP."ObjectID", RP."ÆüÉÕ",
              RP."CreatedBy", RPD."´µ¼Ô"
	      FROM "ÉÂ¼¼´µ¼ÔÉ½" AS RP
              JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
              ON R."ObjectID" = RP."ÉÂ¼¼" AND R."Superseded" IS NULL
	      LEFT JOIN "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" AS RPD
	      ON RP."ObjectID" = RPD."ÉÂ¼¼´µ¼ÔÉ½" AND RP."Superseded" IS NULL
              WHERE RP."ÉÂ¼¼" IN (' . join(',', array_keys($rm)) . ') OR
                    RPD."´µ¼Ô" IN (' . join(',', array_keys($pt)) . ')');
    $this->dbglog("Get RPD: $stmt;\n");
    $_rpd = pg_fetch_all(pg_query($db, $stmt));
    $this->dbglog("RPD raw: " . mx_var_dump($_rpd));

    $rpd = array();
    $rrpd = array();
    if (is_array($_rpd)) {
      foreach ($_rpd as $r) {
	$room = $r['ÉÂ¼¼'];
	$patient = $r['´µ¼Ô'];
	$date = $r['ÆüÉÕ'];
	if (! array_key_exists($room, $rpd)) {
	  $rpd[$room] = array('ObjectID' => $r['ObjectID'],
			      'CreatedBy' => $r['CreatedBy'],
			      'ÉÂ¼¼Ì¾' => $r['ÉÂ¼¼Ì¾'],
			      'ÆüÉÕ' => $date,
			      '´µ¼Ô' => array());
	  $room_name[$room] = $r['ÉÂ¼¼Ì¾'];
	}
	if (! is_null($patient)) {
	  $rpd[$room]['´µ¼Ô'][$patient] = $date;
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

    $dt = $this->data['Á´ÈÌ'][0]['ÆüÉÕ'];

    // A room-patient record newer than this log entry should not be
    // touched, and the patients described there should not appear
    // anywhere else.
    $room_forbidden = array();
    $patient_forbidden = array();
    foreach ($rpd as $room => $data) {
      if ($dt < $data['ÆüÉÕ']) {
	$room_forbidden[$room] = 1;
	foreach ($data['´µ¼Ô'] as $patient => $junk)
	  $patient_forbidden[$patient] = array($room, $data['ÆüÉÕ']);
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
	$nrpd[$room] = $rpd[$room]['´µ¼Ô'];
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
      foreach ($data['´µ¼Ô'] as $patient => $junk) {
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
	foreach ($data['´µ¼Ô'] as $patient => $junk)
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
	foreach ($rpd[$r]['´µ¼Ô'] as $p => $junk)
	  $opt[] = $p;
	$npt = array();
	foreach ($pt_list as $p => $junk)
	  $npt[] = $p;

	if ($rpd[$r]['ÆüÉÕ'] == $dt &&
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

	$stmt = ('SELECT nextval(\'"ÉÂ¼¼´µ¼ÔÉ½_ID_seq"\') as "v"');
	$this->dbglog("SEQ -- $stmt;\n");
	$stash_id = mx_db_fetch_single($db, $stmt);
	$stash_id = $stash_id['v'];
	$this->dbglog("SEQ is $stash_id\n");

	// Stash
	$stmt = ('INSERT INTO "ÉÂ¼¼´µ¼ÔÉ½" ("ID", "ObjectID", "CreatedBy",
                 "Superseded", "ÉÂ¼¼", "ÆüÉÕ") VALUES (' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($stash_id) . ', ' .
		 mx_db_sql_quote($rpd[$r]['CreatedBy']) . ', now(), ' .
		 mx_db_sql_quote($r) . ', ' .
		 mx_db_sql_quote($rpd[$r]['ÆüÉÕ']) . ')');
	$this->dbglog("Stash -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);

	$stmt = ('UPDATE "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" SET "ÉÂ¼¼´µ¼ÔÉ½" = ' .
		 mx_db_sql_quote($stash_id) . ' WHERE "ÉÂ¼¼´µ¼ÔÉ½" = ' .
		 mx_db_sql_quote($oid));
	$this->dbglog("Stash Sub -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);

	// UPDATE in place
	$stmt = ('UPDATE "ÉÂ¼¼´µ¼ÔÉ½" SET "CreatedBy" = ' .
		 mx_db_sql_quote($mx_authenticate_current_user) . ', ' .
		 '"ÆüÉÕ" = ' .
		 mx_db_sql_quote($dt) . '
                 WHERE "ObjectID" = ' . $oid);
      }
      else {
	$npt = array();
	foreach ($pt_list as $p => $junk)
	  $npt[] = $p;
	$this->dbglog("Setting patient list for $r to (" .
		      join(", ", $npt) . "), a new RP entry\n");

	$stmt = ('SELECT nextval(\'"ÉÂ¼¼´µ¼ÔÉ½_ID_seq"\') as "v"');
	$this->dbglog("SEQ -- $stmt;\n");
	$stash_id = mx_db_fetch_single($db, $stmt);
	$oid = $stash_id['v'];
	$this->dbglog("SEQ is $oid\n");

	// INSERT the new one.
	$stmt = ('INSERT INTO "ÉÂ¼¼´µ¼ÔÉ½" ("ID", "ObjectID", "CreatedBy",
               "ÉÂ¼¼", "ÆüÉÕ") VALUES (' .
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
	$stmt = ('INSERT INTO "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" ("ÉÂ¼¼´µ¼ÔÉ½", "´µ¼Ô")
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
      $msg .= ("<p>°Ê²¼¤Î´µ¼Ô¤Ë¤Ï¡¢¸½ºßÊÔ½¸Ãæ¤Î $dt ÆüÉÕÉÂÅï´ÉÍýÆü»ï¤è¤ê" .
	       "¿·¤·¤¤µ­Ï¿¤Ë¤è¤ê´µ¼Ô¤Î½êºß¤¬¤¹¤Ç¤Ëµ­Ï¿¤µ¤ì¤Æ¤¤¤Þ¤¹¤Î¤Ç¡¢" .
	       "ËÜµ­Ï¿¤Ë¤è¤Ã¤Æ´µ¼Ô¤Î°ÜÆ°¤Ï¹Ô¤Ê¤¤¤Þ¤»¤ó¡£</p>\n<ul>");
      foreach ($pf as $patient => $data) {
	$room = $rpd[$data[0]]['ÉÂ¼¼Ì¾'];
	$date = $data[1];
	$msg .= ("<li>" . htmlspecialchars($patient_name[$patient]) .
		 " ($date ¡¢ÉÂ¼¼ $room)</li>\n");
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
      $msg .= ("<p>°Ê²¼¤Î´µ¼Ô¤Ë¤Ä¤¤¤Æ¤Ï¡¢¸½ºßÊÔ½¸Ãæ¤Î $dt ÆüÉÕÉÂÅï´ÉÍýÆü»ï" .
	       "¤¬´µ¼Ô¤Î½êºß¤Ë´Ø¤¹¤ëºÇ¿·¥Ç¡¼¥¿¤Ç¤¹¤Î¤Ç¡¢ËÜµ­Ï¿¤Ë¤è¤Ã¤Æ´µ¼Ô" .
	       "¤ò³ÆÉÂ¼¼¤Ëµ­Ï¿¤·¤Þ¤·¤¿¡£</p>\n<ul>");
      foreach ($pm as $patient => $data) {
	$fromto = '';
	if (! is_null($data[0]))
	  $fromto = $room_name[$data[0]] . '¤«¤é';
	if (! is_null($data[1]))
	  $fromto = $fromto . $room_name[$data[1]] . '¤Ø';
	$fromto = $fromto . '°ÜÆ°';
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
		      $this->data['Á´ÈÌ'][0]['ÆüÉÕ'],
		      $this->data['Á´ÈÌ'][0]['ÉÂÅï']);
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
