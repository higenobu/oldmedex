<?php // -*- mode: php; coding: euc-japan -*-
if(!defined("HTTP_SERVER_VARS"))
  $HTTP_SERVER_VARS = $_SERVER;

$_mx_resource_dir = 'resource/29017a23';
$_mx_logo_url = '';
//0320-2014 60->36000
$_mx_authenticate_session_update=36000;
//

$_medex_config_file = fopen($HTTP_SERVER_VARS['MedexConfigFile'], "r");
if ($_medex_config_file) {
	while (!feof($_medex_config_file)) {
		$line = fgets($_medex_config_file);
		$match = array();
		if (!preg_match('/^([a-z_0-9]+)\s*=\s*(.*?)\s*$/',
				$line, &$match))
			continue;
		$varname = "_mx_" . $match[1];
		$value = $match[2];
		$GLOBALS[$varname] = $value;
	}
	fclose($_medex_config_file);

	

	if ($GLOBALS['mx_product_name'] == 'KARTEX') {
		$GLOBALS['_mx_trial_use_period'] = 30;
		$GLOBALS['_mx_registration_key_expires'] = 0;
	}


	$g = "," . $GLOBALS['_mx_ppa_use_extra_pane_in_unused_soe'] . ",";
	foreach (array('karte', 'all') as $name) {
		$n = '_mx_ppa_' . $name . '_use_extra_pane_in_unused_soe';
		$GLOBALS[$n] = (strpos($g, ",all,") !== false ||
				strpos($g, "," . $name . ",") !== false);
	}
} else {
	printf("Cannot open %s\n", $HTTP_SERVER_VARS['MedexConfigFile']);
	exit;
}

$_mx_auto_sodsoe_setup = $_mx_auto_sodsoe_setup ? 3 : 0;

if ($_mx_product_name == 'MYKARTE')
    $_mx_narrow_username = 0;


if ($_mx_pdfgen_cmd != '' &&
    substr($_mx_pdfgen_cmd, 0, 1) != '/') {
	# Allow server-root (i.e. html/) relative.
	$_mx_pdfgen_cmd = $_SERVER['DOCUMENT_ROOT'] . "/" . $_mx_pdfgen_cmd;
}

if ($_mx_pdfgen_cmd2 != '' &&
    substr($_mx_pdfgen_cmd2, 0, 1) != '/') {
	# Allow server-root (i.e. html/) relative.
	$_mx_pdfgen_cmd2 = $_SERVER['DOCUMENT_ROOT'] . "/" . $_mx_pdfgen_cmd2;
}

if ($_mx_ocrgen_cmd != '' &&
    substr($_mx_ocrgen_cmd, 0, 1) != '/') {
	# Allow server-root (i.e. html/) relative.
	$_mx_ocrgen_cmd = $_SERVER['DOCUMENT_ROOT'] . "/" . $_mx_ocrgen_cmd;
}

$_mx_appt_hours = array();
foreach (explode(',', $_mx_apptment_hours_string) as $_i) {
	$_mx_appt_hours[$_i] = $_i;
}
$_mx_appt_durs =  array();
foreach (explode(',', $_mx_apptment_durs_string) as $_i) {
	$_mx_appt_durs[$_i] = $_i;
}
$_mx_appt_days = array();
foreach (explode(',', $_mx_apptment_days_string) as $_i) {
	switch ($_i) {
	case 0: $_mx_appt_days[$_i] = '日'; break;
	case 1: $_mx_appt_days[$_i] = '月'; break;
	case 2: $_mx_appt_days[$_i] = '火'; break;
	case 3: $_mx_appt_days[$_i] = '水'; break;
	case 4: $_mx_appt_days[$_i] = '木'; break;
	case 5: $_mx_appt_days[$_i] = '金'; break;
	case 6: $_mx_appt_days[$_i] = '土'; break;
	}
}
$_mx_daysoftheweek = array('月', '火', '水', '木', '金', '土', '日');

/////////////////////////////////////////////////////////////////
// Returns a PostgreSQL database handle.  Client encoding is
// automatically set to euc-jp.
// Note that the build procedure will configure your dbname to
// what is set in ../../config.mk in the installed common.php file.

function mx_dbname_cfg() {
	    $f = $_SERVER['DOCUMENT_ROOT'].'/../dbname';
	    $h = fopen($f, 'r');
	    $dbname = fgets($h);
	    fclose($h);
	    return trim($dbname);
}

function mx_db_connect() {
    global $_mx_db_db, $_mx_db_pgport, $_mx_db_pghost, $_mx_db_pguser;
    if (is_null($_mx_db_db)) {
	    $dbname = mx_dbname_cfg();
	    if ($_mx_db_pgport != '') {
		    $portspec = " port=$_mx_db_pgport";
	    }
	    if ($_mx_db_pghost != '') {
		    $hostspec = "host=$_mx_db_pghost";
	    } else {
		    $hostspec = 'host=localhost';
	    }
	    $_mx_db_db =
		    pg_connect("$hostspec".
			       "$portspec dbname=$dbname user=$_mx_db_pguser");
	pg_set_client_encoding('euc-jp');
    }
    return $_mx_db_db;
}
$_mx_db_db = NULL;

/////////////////////////////////////////////////////////////////
// Given a string $value, quote it as an SQL literal suitable to
// be embedded in an SQL statement.

function mx_db_sql_quote ($value) {
    if (is_null($value)) {
	return 'NULL';
    } else {
      return "'" . pg_escape_string($value) . "'";
    }
}

////////////////////////////////////////////////////////////////
// Given a DB metadata string (like table name or column name),
// quote it for suitable to be embedded in an SQL statement.

function mx_db_sql_quote_name ($name) {
	if (is_array($name))
		return implode('.', array_map('mx_db_sql_quote_name', $name));
	return '"' . $name . '"';
}

////////////////////////////////////////////////////////////////
// Given a DB handle returned by mx_db_connect() and an SQL
// statement in a string form, perform a query and get the
// resulting row.  The statment should yield a single row, or no
// row; otherwise it is an error in the program (this is
// primarily to be used for authentication/session management).
// The row is fetched via pg_fetch_array and returned to the
// caller.  If there is no matching row, false is returned.

function mx_db_fetch_single(&$db, $stmt)
{
    $sth = pg_query($db, $stmt);
    if (!$sth) {
	    print "<!-- $stmt -->";
	    return NULL;
    }
    $result = pg_fetch_array($sth, NULL, PGSQL_ASSOC);
    if ($result) {
	if (pg_fetch_array($sth)) {
	    // error: what is our error handling convention?
	    return NULL;
	}
    }
    return $result;
}

function mx_db_fetch_all(&$db, $stmt)
{
    $sth = pg_query($db, $stmt);
    $result = array();
    if (!$sth) {
	    print "<!-- $stmt -->";
	    return $result;
    }
    while (1) {
	    $tuple = pg_fetch_array($sth, NULL, PGSQL_ASSOC);
	    if (!$tuple)
		    break;
	    $result[] = $tuple;
    }
    return $result;
}

////////////////////////////////////////////////////////////////
// Allocate unused id from the sequence.
function mx_db_allocate_unused_id(&$db, $sequence)
{
  $stmt = ('SELECT nextval(\'' .
	   mx_db_sql_quote_name($sequence) . '\') as "v"');
  $sth = pg_query($db, $stmt);
  if (! $sth)
    return NULL;
  $data = pg_fetch_all($sth);
  $id = $data[0]['v'];
  if (! $id)
    return NULL;
  return $id;
}

////////////////////////////////////////////////////////////////
// Fetch and stash when we do not have the full object.
// This does not know anything about subobjects.
function mx_db_stash_original(&$db, $table, $oid, $stash_id, $it=NULL)
{
  $stmt = ('SELECT * FROM ' . mx_db_sql_quote_name($table) .
	   ' WHERE "ObjectID" = ' . mx_db_sql_quote($oid));
  if ($it) $it->dbglog("$stmt;\n");
  $d = mx_db_fetch_single($db, $stmt);
  if ($it)
    $it->dbglog('Stash:' . mx_var_dump($d));
  $n = $d;
  $n['ObjectID'] = $stash_id;
  mx_db_insert_tuple($db, $table, $n, 'now()', $it);
  return $d;
}

////////////////////////////////////////////////////////////////
// Insert one tuple straight.
function mx_db_insert_tuple(&$db, $table, $d, $superseded=NULL, $it=NULL)
{
  $col = array();
  $val = array();
  foreach ($d as $c => $v) {
    $col[] = mx_db_sql_quote_name($c);
    $val[] = (($c == 'Superseded' && ! is_null($superseded))
	      ? $superseded
	      : mx_db_sql_quote($v));
  }
  $stmt = ('INSERT INTO ' . mx_db_sql_quote_name($table) . '(' .
	   implode(", ", $col) . ") VALUES\n(" .
	   implode(", ", $val) . ");\n");
  if ($it) $it->dbglog("INSERT: $stmt");
  pg_query($db, $stmt);
}

////////////////////////////////////////////////////////////////
// Per installation Configuration Data
$_mx_install_data = NULL;
function mx_get_install_data()
{
	global $_mx_install_data;

	#NEEDSWORK: do we need cache expiry?
	if ($_mx_install_data)
		return $_mx_install_data;

	$db = mx_db_connect();
	$stmt = '
	SELECT item_name, item_value FROM bmd_config
	WHERE "Superseded" IS NULL';
	$sth = pg_query($db, $stmt);
	$data = pg_fetch_all($sth);
	$result = array();
	if ($data) {
		foreach ($data as $row) {
			$result[$row['item_name']] = $row['item_value'];
		}
	}
	$_mx_install_data = $result;
	return $result;
}

////////////////////////////////////////////////////////////////
// Data validation functions.
// These all return non-empty string on failure.
$_lib_common_validate_msgs = array
(
 'Bad Date' => '日付(YYYY-MM-DD)が不正です。',
 'Bad Time' => '時刻(HH:MM:SS または HH:MM)が不正です。',
 'Bad Datetime' =>
   '日時(YYYY-MM-DD HH:MM:SS または YYYY-MM-DD HH:MM)が不正です。',
 'Date In The Past' =>
   '過去の日付ではいけません。',

 'Bad Digits' => '半角数字でないといけません。', 
 'Bad Number' => '数値でないといけません。',

 'Bad NNint' => '非負整数値でありません。',
 'Bad Posint' => '正整数値でありません。',

 'Bad NZNumber' => '非零数値でありません。',

 'Value Range' => '%s 以上、%s 以下でないといけません。',
 'Minimum Value' => '%s 以上でないといけません。',
 'Maximum Value' => '%s 以下でないといけません。',
 'Maximum Precision' => '小数点以下は最大 %s 桁です。',

 'Length Range' => '%d 文字以上、%d 文字以下でないといけません。',
 'Minimum Length' => '%d 文字以上でないといけません。',
 'Maximum Length' => '%d 文字以下でないといけません。',

 'Exact Length' => '%d 文字でないといけません。',
 'Non Empty' => '空ではいけません。',
 );

function mx_ui_japanese_date($d) {
  /* JIS X 0301 「5.2.4 元号による日付」
     [HSTM]\d\d\.\d\d\.\d\d
     ただし、太陰暦の換算なんてやってられないので、
     明治六年以前に生まれた人は対象としない。
  */
  if (trim($d) == '') { return NULL; }

  $match = array();
  if (!preg_match('/^([HSTM])(\d{2})\.(\d{2}).(\d{2})(.*)/',
		  $d, &$match))
    return $d;
  $era = $match[1];
  $year = $match[2];
  $mon = $match[3];
  $day = $match[4];
  $remainder = $match[5];
  $japanese_year_offset = array('M' => 1873 - 6,
				'T' => 1912 - 1,
				'S' => 1926 - 1,
				'H' => 1989 - 1);
  $year += $japanese_year_offset[$era];
  return sprintf("%04d-%02d-%02d%s", $year, $mon, $day, $remainder);
}

function mx_db_validate_date($date, $opt=NULL) {
  global $_lib_common_validate_msgs;
  // YYYY-MM-DD
  $match = array();
  if (! preg_match('/^(\d{4})-(\d+)-(\d+)$/', $date, &$match) ||
      ! checkdate($match[2], $match[3], $match[1]))
    return $_lib_common_validate_msgs['Bad Date'];
  return NULL;
}

function mx_db_validate_time($time, $opt=NULL) {
  global $_lib_common_validate_msgs;
  // HH:MM:SS

  $match = array();
  if (preg_match('/^(\d+):(\d+)$/', $time, &$match)) // HH:MM only
    $time = $time . ':00';

  $match = array();
  if (! preg_match('/^(\d+):(\d+):(\d+)$/', $time, &$match))
    return $_lib_common_validate_msgs['Bad Time'];

  $hr = $match[1];
  $mn = $match[2];
  $sc = $match[3];
  if (strspn("$hr$mn$sc", '0123456789') != strlen("$hr$mn$sc"))
    return $_lib_common_validate_msgs['Bad Time'];
  if (24 <= $hr || 60 <= $mn || 60 <= $sc)
    return $_lib_common_validate_msgs['Bad Time'];
  return NULL;
}

function mx_db_validate_datetime($datetime, $opt=NULL) {
  global $_lib_common_validate_msgs;
  // YYYY-MM-DD HH:MM:SS

  $dt = explode(' ', $datetime);
  if (! is_array($dt) || count($dt) != 2 ||
      mx_db_validate_date($dt[0], $opt) ||
      mx_db_validate_time($dt[1], $opt))
    return $_lib_common_validate_msgs['Bad Datetime'];
  return NULL;
}

function mx_db_validate_date_not_in_past($date, $opt=NULL) {
  global $_lib_common_validate_msgs;
  $today = mx_today_string(0);
  $match = array();

  if (! preg_match('/^(\d{4})-(\d+)-(\d+)$/', $date, &$match) ||
      ! checkdate($match[2], $match[3], $match[1]))
    return $_lib_common_validate_msgs['Bad Date'];

  $date = sprintf("%04d-%02d-%02d", $match[1], $match[2], $match[3]);
  if ($date < $today)
    return $_lib_common_validate_msgs['Date In The Past'];
  return NULL;
}

function mx_datetime_to_unixtime($datetime) {
	$match = array();
	if (preg_match('/^(\d+)-(\d+)-(\d+) (\d+):(\d+)$/',
		       $datetime, &$match) ||
	    preg_match('/^(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)$/',
		       $datetime, &$match)) {
		// PHP mktime does 69 dividing with the year -- funky.
		return mktime($match[4], $match[5], $match[6] ? $match[6] : 0,
			      $match[2], $match[3], $match[1]);
	}
	return -1;
}

function mx_validate_range_helper($num, $opt) {
  if (!is_array($opt))
    return NULL;

  $has_min = array_key_exists('validate-min', $opt);
  $has_max = array_key_exists('validate-max', $opt);

  if (($has_min && $num < $opt['validate-min']) ||
      ($has_max && $opt['validate-max'] < $num)) {
	  if ($has_min && $has_max)
	    return sprintf($_lib_common_validate_msgs['Value Range'],
			   $opt['validate-min'], $opt['validate-max']);
	  else if ($has_min)
	    return sprintf($_lib_common_validate_msgs['Minimum Value'],
			   $opt['validate-min']);
	  else
	    return sprintf($_lib_common_validate_msgs['Maximum Value'],
			   $opt['validate-max']);
  }

  return NULL;
}

function mx_db_validate_number($float, $opt=NULL) {
  global $_lib_common_validate_msgs;

  $m = array();
  if (!preg_match('/^-?\d*(?:\.(\d*?)0*)?$/', $float, &$m) ||
      ($float + 0 != $float * 1))
    return $_lib_common_validate_msgs['Bad Number'];

  if (!is_array($opt))
	  return NULL;

  $errs = mx_validate_range_helper($float, $opt);
  if ($errs)
	  return $errs;

  if (array_key_exists('validate-precision', $opt)) {
      $prec = $opt['validate-precision'];
      if ($m && $prec < strlen($m[1]))
	      return sprintf($_lib_common_validate_msgs['Maximum Precision'],
			     $prec);
  }

  return NULL;
}

function mx_db_validate_nonzero($num, $opt=NULL) {
  global $_lib_common_validate_msgs;
  $n = "AZ" . (0 + $num * 1);
  if (($n != "AZ$num") || ($num == 0))
    return $_lib_common_validate_msgs['Bad NZNumber'];
  $errs = mx_validate_range_helper($num, $opt);
  if ($errs)
	  return $errs;
  return NULL;
}

function mx_db_validate_digits($digits, $opt=NULL) {
  global $_lib_common_validate_msgs;
  if (strspn($digits, '0123456789') != strlen($digits))
    return $_lib_common_validate_msgs['Bad Digits'];
  $errs = mx_validate_range_helper($digits, $opt);
  if ($errs)
	  return $errs;
  return NULL;
}

function mx_db_validate_posint($posint, $opt=NULL) {
  global $_lib_common_validate_msgs;
  if (strspn($posint, '0123456789') != strlen($posint) ||
      ! strlen($posint) || ($posint + 0 <= 0))
    return $_lib_common_validate_msgs['Bad Posint'];
  $errs = mx_validate_range_helper($posint, $opt);
  if ($errs)
	  return $errs;
  return NULL;
}

function mx_db_validate_nnint($nnint, $opt=NULL) {
  global $_lib_common_validate_msgs;
  if (strspn($nnint, '0123456789') != strlen($nnint) ||
      ! strlen($nnint) || ($nnint + 0 < 0))
    return $_lib_common_validate_msgs['Bad NNint'];
  $errs = mx_validate_range_helper($nnint, $opt);
  if ($errs)
	  return $errs;
  return NULL;
}

function mx_db_validate_range($s, $min, $max) {
  global $_lib_common_validate_msgs;
  if ($min == $max) {
    // Exact value should not be requested by the caller.
    return NULL;
  }
  return mx_validate_range_helper($s, array('validate-min' => $min,
					    'validate-max' => $max));
}

function mx_db_validate_length($s, $min, $max) {
  global $_lib_common_validate_msgs;
  $l = strlen($s);
  if (0 < $min && $min == $max) {
    if ($l != $min)
      return sprintf($_lib_common_validate_msgs['Exact Length'], $min);
    return NULL;
  }
  if (0 < $min && 0 < $max) {
    if ($l < $min || $max < $l)
      return sprintf($_lib_common_validate_msgs['Length Range'],
		     $min, $max);
    return NULL;
  }
  if (0 < $min && $l < $min) {
    if ($min == 1)
      return sprintf($_lib_common_validate_msgs['Non Empty']);
    return sprintf($_lib_common_validate_msgs['Minimum Length'], $min);
  }
  if (0 < $max && $max < $l)
    return sprintf($_lib_common_validate_msgs['Maximum Length'], $max);
  return NULL;
}

function mx_db_validate_len($s, $opt=NULL) {
	return mx_db_validate_length($s,
				     $opt['validate-minlen'],
				     $opt['validate-maxlen']);
}

////////////////////////////////////////////////////////////////

function mx_db_insert_blobmedia(&$db, $type, $data, $id=null)
{
  if(!$id)
    $id = mx_db_allocate_unused_id($db, 'mx_blobmedia_ID_seq');

  $stmt = ('INSERT INTO mx_blobmedia ("ObjectID", mime_type, data) '.
	   "VALUES (" .
	   pg_escape_string($id) . ", '" .
	   pg_escape_string($type) . "', '" .
	   pg_escape_bytea($data) . "')");
  if (!pg_query($db, $stmt))
    return NULL;
  return $id;	
}

function mx_db_update_blobmedia(&$db, $id, $type, $data)
{
	$stmt = ('UPDATE mx_blobmedia SET '.
		 "mime_type = '" . pg_escape_string($type) . "', " .
		 "data = '" . pg_escape_bytea($data) . "' " .
		 'WHERE "ObjectID" = ' . $id);
	if (!pg_query($db, $stmt))
		return NULL;
	return $id;	
}

function mx_db_blobmedia_exists(&$db, $id)
{
	$stmt = ('SELECT mime_type FROM mx_blobmedia WHERE '.
		 '"ObjectID" = ' .
		 "'" . pg_escape_string($id) . "'");
	$d = mx_db_fetch_single($db, $stmt);
	if (!$d)
		return NULL;
	return $d['mime_type'];
}

function mx_db_fetch_blobmedia(&$db, &$media, $id)
{
	$stmt = ('SELECT mime_type, data FROM mx_blobmedia WHERE '.
		 '"ObjectID" = ' .
		 "'" . pg_escape_string($id) . "'");
	$d = mx_db_fetch_single($db, $stmt);
	if (!$d)
		return NULL;
	$media = pg_unescape_bytea($d['data']);
	return $d['mime_type'];
}

function mx_db_fetch_extmedia(&$db, &$media, $id)
{
	$stmt = ('SELECT t.mime_type as mime_type, t.handler as handler, d.data as data
		  FROM mx_extdocument d
		  JOIN mx_doctype t ON d.doctype = t."ObjectID"
 		  WHERE d."ObjectID" = ' .
		 "'" . pg_escape_string($id) . "'");
	$d = mx_db_fetch_single($db, $stmt);
	if (!$d)
		return NULL;
	$media = pg_unescape_bytea($d['data']);
	return $d['mime_type'];
}

function mx_db_fetch_extmedia_annotation(&$db, $id)
{
	$stmt = ('SELECT t.mime_type as mime_type, t.handler as handler, d.data as data
		  FROM mx_extdocument d
		  JOIN mx_doctype t ON d.doctype = t."ObjectID"
		  WHERE d.annotates = ' .
		 "'" . pg_escape_string($id) . "'");
	$d = mx_db_fetch_all($db, $stmt);
	if (!$d)
		return NULL;
	for ($i=0; $i < count($d); $i++)
		$d['data'] = pg_unescape_bytea($d['data']);
	return $d;
}

function mx_db_insert_extdocument(&$db, $doctype, $blobmediaid,
				  $pt=NULL, $comment=NULL, $annotates=NULL)
{
//1020-2014
//fixed for not save karte image.
//print "CCCC\n".$doctype."=".$blobmediaid."=".$pt;
if ($doctype ==null) $doctype='画像';
//print "DDDDD\n".$doctype."=".$blobmediaid."=".$pt;
	global $mx_authenticate_current_user;
	$u = $mx_authenticate_current_user;
	$stmt = ('SELECT "ObjectID" FROM mx_doctype '.
		 'WHERE "Superseded" IS NULL AND label_string = \''.
		 pg_escape_string($doctype). "'");
	$d = mx_db_fetch_single($db, $stmt);
	if (!$d)
		return NULL;
	$doctype = $d['ObjectID'];
	$id = mx_db_allocate_unused_id($db, "mx_extdocument_ID_seq");
	$u = mx_db_sql_quote($u);
	$pt = mx_db_sql_quote($pt);
	$comment = mx_db_sql_quote($comment);
	$annotates = mx_db_sql_quote($annotates);
	$stmt = "
INSERT INTO mx_extdocument
(\"ID\", \"ObjectID\", \"Superseded\", \"CreatedBy\",
 created_on, regards_to, doctype, comment, annotates, data)
SELECT
	$id, $id, NULL, $u,
	current_timestamp, $pt, $doctype, $comment,
	$annotates, data
FROM mx_blobmedia
WHERE
	\"ObjectID\" = $blobmediaid";
//1020-2014
//print $stmt;
	if (!pg_query($db, $stmt))
		return NULL;
	return $id;
}

////////////////////////////////////////////////////////////////
// Internal: HMAC implementation (RFC 2104)
function mx_authenticate_hmac($text) {
  global $_mx_authenticate_hmac_key;
  global $_mx_authenticate_hmac_key_with_ipad;
  global $_mx_authenticate_hmac_key_with_opad;
  $inner = pack('H*', sha1($_mx_authenticate_hmac_key_with_ipad . $text));
  $outer = sha1($_mx_authenticate_hmac_key_with_opad . $inner);

  return $outer;
}
$_mx_authenticate_hmac_key =
  'Very much trivial key for HMAC see RFC 2004 this will be hashed first.';
if (64 < strlen($_mx_authenticate_hmac_key)) {
    $_mx_authenticate_hmac_key = pack('H*', sha1($_mx_authenticate_hmac_key));
}
if (strlen($_mx_authenticate_hmac_key) < 64) {
    $_mx_authenticate_hmac_key .=
        str_repeat("\0", 64 - strlen($_mx_authenticate_hmac_key));
}
$_mx_authenticate_hmac_key_with_ipad =
    $_mx_authenticate_hmac_key ^ str_repeat("\x36", 64);
$_mx_authenticate_hmac_key_with_opad =
    $_mx_authenticate_hmac_key ^ str_repeat("\x5c", 64);

////////////////////////////////////////////////////////////////
// Internal: minting and quick validating of cookies.

// We use URL prefix scheme to keep track of sessions.
// mod_rewrite module in Apache is used to rewrite
// http://server/au/cookie/url ==> url
// and put the cookie in URL_PREFIX_COOKIE environment variable.
// The cookie consists of two parts: body (i.e. nonce) part and
// sign part.  sign part is the first handful characters of HMAC
// of the nonce part.

// Length of the nonce part.
$_mx_authenticate_cookie_body_length = 13;
// Length of the sign part.
$_mx_authenticate_cookie_sign_length = 6;

// Browser identification hint.
$_mx_authenticate_browser_hint_length = 7;
$_mx_authenticate_browser_hint_name = 'browser-hint';

// Check for URL_PREFIX_COOKIE environment variable mod_rewrite
// placed, and see if the sign part of the cookie actually signs
// the nonce part.  Otherwise we do not have a cookie or we have
// something bogus, in which case return NULL.  If this quick
// check passes, return the cookie to the caller to be validated
// against the session table in the database.
function mx_authenticate_cookie_quick_validate() {
    global $_mx_authenticate_cookie_body_length;
    global $_mx_authenticate_cookie_sign_length;
    $cookie = getenv('URL_PREFIX_COOKIE');
    $ary = explode(':', $cookie, 2);
    $cookie_body = $ary[0];
    $cookie_sign = $ary[1];
    $hmac = mx_authenticate_hmac($ary[0]);
    if (strlen($cookie_body) != $_mx_authenticate_cookie_body_length ||
	strlen($cookie_sign) != $_mx_authenticate_cookie_sign_length ||
	$cookie_sign != substr($hmac, 0,
			       $_mx_authenticate_cookie_sign_length)) {
	return NULL;
    }
    return $cookie;
}

function __base_convert($s) {
  $l = '';
  while (strlen($s) >= 6) {
    $x = substr($s, 0, 6);
    $y = base_convert("7f$x", 16, 36); // $7f = 127 (prime)
    $l .= substr($y, 2);
    $s = substr($s, 6);
  }
  return $l;
}

////////////////////////////////////////////////////////////////
// Internal: random cookie of given length with seed
function mx_random_cookie($length, $seed = ':')
{
	$body = '';
	$time = time();
	while (strlen($body) < $length) {
		$append = sha1(((string)rand()) . ':' . $seed);
		$append = __base_convert($append);
		$body .= $append;
        }
	return substr($body, 0, $length);
}

// Mint a new cookie for the given user and register a new session.
// Return the cookie.
function mx_authenticate_cookie_mint($userid) {
    global $_mx_authenticate_cookie_body_length;
    global $_mx_authenticate_cookie_sign_length;
    global $_mx_authenticate_browser_hint_length;
    global $_mx_authenticate_browser_hint_name;

    $remote_addr = NULL;
    foreach (array('HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $k) {
      if (array_key_exists($k, $_SERVER)) {
	$remote_addr = $_SERVER[$k];
	$match = array();
	if (preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/',
		       $remote_addr, &$match) &&
	    0 <= $match[1] && $match[1] <= 255 &&
	    0 <= $match[2] && $match[2] <= 255 &&
	    0 <= $match[3] && $match[3] <= 255 &&
	    0 <= $match[4] && $match[4] <= 255)
	  break;
	$remote_addr = NULL;
      }
    }

    $db = mx_db_connect();
    while (1) {
	$time = time();
	$random_length = ($_mx_authenticate_browser_hint_length
			  + $_mx_authenticate_cookie_body_length);
	$seed = (string)$time . ':' . $userid;
	$cookie_body_0 = mx_random_cookie($random_length, $seed);
	$cookie_body = substr($cookie_body_0, 0,
			      $_mx_authenticate_cookie_body_length);
	$cookie_sign = substr(mx_authenticate_hmac($cookie_body), 0,
			      $_mx_authenticate_cookie_sign_length);
	$cookie = $cookie_body . ':' . $cookie_sign;
	$stmt = ('SELECT cookie FROM mx_session WHERE cookie = ' .
		 mx_db_sql_quote($cookie));

	pg_query($db, 'begin');
	$result = mx_db_fetch_single($db, $stmt);
	if (! $result && ! is_null($result)) {
	  if (array_key_exists($_mx_authenticate_browser_hint_name,
			       $_COOKIE))
	    $browser_hint = $_COOKIE[$_mx_authenticate_browser_hint_name];
	  else
	    $browser_hint = substr($cookie_body_0,
				   strlen($cookie_body_0)
				   - $_mx_authenticate_browser_hint_length);
	  $stmt = ('INSERT INTO mx_session
                     (userid, last_check, cookie, browser_hint, remote_addr)
		     VALUES (' . $userid . ',' . $time . ',' .
		   mx_db_sql_quote($cookie) . ',' .
		   mx_db_sql_quote($browser_hint) . ',' .
		   mx_db_sql_quote($remote_addr) . ')');
	  if (pg_query($db, $stmt)) {
	    pg_query($db, 'commit');
	    break;
	  }
	  else
	    error_log('mx_authenticate_cookie_mint insert failure');
	}
	pg_query($db, 'rollback');
 	error_log('mx_authenticate_cookie_mint xn retry');
    }
    return array($cookie, $browser_hint);
}

// Garbage collect stale sessions from the database.
function mx_authenticate_cookie_gc($db) {
  $stmt = "SELECT item_value FROM bmd_config WHERE item_name='SESSION_EXPIRE'";
  $d = mx_db_fetch_single(mx_db_connect(), $stmt);
  $time = time() - $d['item_value'];
/*10-22-2014
    $stmt0 = ('INSERT INTO mx_session_log
               ("ID", userid, login_time, remote_addr, browser_hint, expired)
	       SELECT "ID", userid, login_time, remote_addr,
                    browser_hint, \'Y\'
               FROM mx_session
	       WHERE last_check < ' .
	      mx_db_sql_quote($time));
*/

    $stmt1 = ("DELETE FROM mx_session " .
	      'WHERE last_check < ' . mx_db_sql_quote($time));
    while (1) {
	pg_query($db, 'begin');
	if ( 
	    pg_query($db, $stmt1) &&
	    pg_query($db, 'commit'))
	    break;
/* 0315-2014
if (pg_query($db, $stmt0) &&
	    pg_query($db, $stmt1) &&
	    pg_query($db, 'commit'))
	    break;
*/
	error_log('mx_authenticate_cookie_gc xn retry');
	pg_query($db, 'rollback');
    }
}

// Other random periodic tasks
function mx_periodic_task(&$db) {
	$backoff = 1;
//0115-2014
/*
	while (1) {
		pg_query($db, 'begin');

		if (pg_query($db, 'SELECT MOVE_APPT_TO_CHECKIN(0)')) {
			pg_query($db, 'commit');
			break;
		} else {
			error_log('mx_periodic_task');
			error_log('error was' . pg_last_error($db));
			sleep($backoff);
			if ($backoff < 30)
				$backoff *= 1.5;
		}
		pg_query($db, 'rollback');
	}
*/
// stop!!
}

////////////////////////////////////////////////////////////////
// Log out and invalidate the current session.
// This also writes the current session into mx_session_log.
function mx_authenticate_logout() {
    $cookie = getenv('URL_PREFIX_COOKIE');
    if (strlen($cookie) == 0)
	return;
    $db = mx_db_connect();
    mx_authenticate_cookie_gc($db);
    $stmt0 = ('SELECT * FROM mx_session WHERE cookie = '.
	      mx_db_sql_quote($cookie));
    $stmt1 = ('DELETE FROM mx_session WHERE cookie = '.
	      mx_db_sql_quote($cookie));
    while (1) {
	pg_query($db, 'begin');
	$result = mx_db_fetch_single($db, $stmt0);
	if ($result && pg_query($db, $stmt1)) {
	    $stmt2 = ('DELETE FROM mx_session_log WHERE "ID" = ' .
		      mx_db_sql_quote($result['ID']));
	    pg_query($db, $stmt2);
/*0315-2014
	    $stmt2 = ('INSERT into mx_session_log
		      ("ID", userid, login_time, remote_addr,
                       browser_hint, expired)
                      values ('.
		      $result['ID'] . ', ' .
		      $result['userid'] . ', ' .
		      mx_db_sql_quote($result['login_time']) . ', ' .
		      mx_db_sql_quote($result['remote_addr']) . ', ' .
		      mx_db_sql_quote($result['browser_hint']) . ", 'N')");
	    if (pg_query($db, $stmt2) && pg_query($db, 'commit'))
		    break;
*/

	} else {
	    pg_query($db, 'rollback');
	    break;
	}
	error_log('mx_authenticate_logout xn retry');
	error_log('error was' . pg_last_error($db));
	pg_query($db, 'rollback');
    }
}

////////////////////////////////////////////////////////////////
// Inspects the execution environment and return the id of the
// authenticated user.  Unless do_not_redirect is specified, this
// function redirects the browser to "log-in please" page if the
// request does not have a proper URL prefix cookie.

// The session expires in $_mx_authenticate_session_expire seconds.
// To avoid excessive update to the session table in the database,
// the update of the last-check column is done only every
// $_mx_authenticate_session_update seconds.

// Since this is the first function to be called with any real application
// we will also undo magic quotes here and work around issues with
// encoding translation post PHP 5.2.
function mx_authenticate_user($do_not_redirect=0) {
    global $_mx_authenticate_session_update;
    global $mx_authenticate_current_user;
    global $mx_authenticate_current_session;

//0225-2014 seconds 10hour
$_mx_authenticate_session_update = 36000;

//

    if (!is_null($mx_authenticate_current_user))
      return $mx_authenticate_current_user;

    mx_undo_magic_quotes();
    mx_fix_mbencode_on_php5();

    $expired = mx_product_expiry();
    if ($expired)
	    return NULL; /* should not return $expired; */

    $mx_authenticate_current_user = NULL;
    $mx_authenticate_current_session = NULL;
    $cookie = mx_authenticate_cookie_quick_validate();
    $result = 0;
    if ($cookie) {
	$db = mx_db_connect();
	$stmt = ('SELECT S."ID", S.userid, S.last_check, S.last_check
                    + cast(C.item_value as int) as expire,
                    browser_hint, remote_addr' .
		 ' FROM mx_session AS S, bmd_config AS C WHERE ' .
		 'S.cookie = ' . mx_db_sql_quote($cookie) .
		 ' AND C.item_name = \'SESSION_EXPIRE\''.
		 ' AND C."Superseded" IS NULL'
                 );
	$result = mx_db_fetch_single($db, $stmt);
    }

    $bad = 0;
    if (! is_array($result))
	    $bad = 1;
    else if (trim($result['browser_hint']) != '') {
	    global $_mx_authenticate_browser_hint_name;
	    $browser_hint = $_COOKIE[$_mx_authenticate_browser_hint_name];
	    if ($browser_hint != '' &&
		$browser_hint != trim($result['browser_hint']))
		    $bad = sprintf("<%s> vs <%s>", $browser_hint,
				   $result['browser_hint']);
    }

    if ($bad) {
	if (! $do_not_redirect) {
	    mx_http_redirect("/login.php");
	}
	return NULL;
    }

    mx_periodic_task(&$db);

    $last = $result['last_check'];
    $expire = $result['expire'];
    $time = time();

    if ($expire < $time) {
      mx_authenticate_cookie_gc($db);
      mx_http_redirect('/login.php?reason=session-expire');
    }
    elseif ($last < $time - $_mx_authenticate_session_update) {
//0430-2012
//      mx_authenticate_cookie_gc($db);
      $stmt = ("UPDATE mx_session SET last_check = $time WHERE " .
	       'cookie = ' . mx_db_sql_quote($cookie));
      pg_query($db, $stmt);
    }
    $mx_authenticate_current_user = $result['userid'];
    $mx_authenticate_current_session = $result['ID'];
    return $result['userid'];
 

}



// $_mx_authenticate_session_update = 1800;  in seconds



////////////////////////////////////////////////////////////////
// Product expiry
function mx_product_expiry_check() {
	global $_mx_trial_use_period, $_mx_registration_key_expires;

	if ($_mx_trial_use_period < 0)
		return NULL;
	$db = mx_db_connect();
	$stmt = <<<SQL
	SELECT "FirstLogin", "Registered", "Expires", "Cookie",
	(CASE WHEN ("Registered" IS NOT NULL AND
		    (($_mx_registration_key_expires <= 0) OR
		     (NOW()::date < "Expires"::date)))
	 THEN NULL
	 ELSE CASE WHEN ((NOW()::date <
			  "FirstLogin"::date + $_mx_trial_use_period))
	 THEN NULL
	 ELSE 'Expired'
	 END
	 END) AS "Expired",
	(CASE WHEN ("Registered" IS NULL)
	 THEN "FirstLogin"::date + $_mx_trial_use_period - NOW()::date
	 ELSE 0
	 END) AS "Remaining",
	(CASE WHEN ($_mx_registration_key_expires > 0)
	 THEN "Expires"::date - NOW()::date
	 ELSE NULL
	 END) AS "Renewal"
	FROM bmd_register
SQL;
	$sth = pg_query($db, $stmt);
	$result = pg_fetch_array($sth, NULL, PGSQL_ASSOC);
	return $result;
}

function mx_product_activate() {
	$result = mx_product_expiry_check();
	if (! $result) {
		$cookie = sha1(((string)rand()) . ':' . time());
		foreach ($_ENV as $k => $v)
			$cookie = sha1($cookie . ':' . $k);
		foreach ($_SERVER as $k => $v)
			$cookie = sha1($cookie . ':' . $k);
		foreach ($_REQUEST as $k => $v)
			$cookie = sha1($cookie . ':' . $k);
		$cookie = substr($cookie, 0, 5) . '-' . substr($cookie, 7, 5);
		$db = mx_db_connect();
		$stmt = <<<SQL
INSERT INTO bmd_register
("FirstLogin", "Cookie")
VALUES (NOW(), '$cookie')
SQL;
		pg_query($db, $stmt);
		$result = mx_product_expiry_check();
	}
	return $result;
}

function mx_product_expiry() {
	global $_mx_trial_use_period;

	if ($_mx_trial_use_period < 0)
		return NULL;
	$result = mx_product_activate();
	if (! $result)
		return 0; // oops
	if ($result['Expired']) {
		mx_http_redirect('/register.php');
		return 1;
	}
	return 0;
}

function mx_register_product($request) {
	global $_mx_registration_key_expires;

	$result = mx_product_activate();
	if (!$result)
		return -1;

	if ($result['Renewal'] >= 0) {
		/* Renewing */
		$remaining = $result['Renewal'];
	} else {
		$remaining = 0;
	}

	$keys = 0;
	$bad = 0;

	$match = array();
	if (array_key_exists("key0", $request) &&
	    strlen($request['key0']) == 35 &&
	    preg_match('/^([^-]{5})-([^-]{5})-([^-]{5})-([^-]{5})-'.
		       '([^-]{5})-([^-]{5})$/', $request['key0'], &$match)) {
		for ($i = 1; $i < 6; $i++) {
			if (!array_key_exists("key$i", $request) ||
			    $request["key$i"] != "")
				$bad++;
			$request["key$i"] = $match[$i + 1];
		}
		$request["key0"] = $match[1];
	}

	for ($i = 0; $i < 6; $i++) {
		if (array_key_exists("key$i", $request))
			$keys++;
		$k = $request["key$i"];
		if (strlen($k) != 5)
			$bad++;
	}
	if (!$keys)
		return 2;
	if ($keys != 6 || $bad)
		return 1;

	$expiration = '';
	if ($_mx_registration_key_expires) {
		if (!array_key_exists("expiration", $request))
			return 1;
		$expiration = $request['expiration'];
		if (mx_db_validate_date($expiration))
			return 1;
	}
	// cookie, key0, key1 (and expiration) should hash to key2..key5
	$nonce = ($result['Cookie'] . $request["key0"] .
		  $expiration . $request["key1"]);
	$hmac = substr(mx_authenticate_hmac($nonce), 0, 20);
	$userval = ($request["key2"] .
		    $request["key3"] . 
		    $request["key4"] . 
		    $request["key5"]);
	if ($userval != $hmac)
		return 1;

	if ($expiration == '')
		$expiration = NULL;

	$key = $request["key0"] . $request["key1"] . $userval;
	$db = mx_db_connect();
	$stmt = ('UPDATE bmd_register SET '.
		 '"Expires" = ' . mx_db_sql_quote($expiration) . ',' .
		 '"Registered" = NOW(), ' .
		 '"RegisterKey" = ' . mx_db_sql_quote($key));
	pg_query($db, $stmt);
	return 0;
}

////////////////////////////////////////////////////////////////
// Takes username, password pair and checks if it is a valid
// user.  If it is, returns a newly minted session cookie string.
// The sole caller of this function is "log-in please" page.

function mx_authenticate_login($username, $password) {
    $db = mx_db_connect();
    $passhash = mx_authenticate_hmac($username . ':' . $password);
    $stmt = ('SELECT userid, username, passhash FROM mx_authenticate ' .
	     'WHERE username = ' . mx_db_sql_quote($username) .
	     ' AND passhash = ' . mx_db_sql_quote($passhash));
    $result = mx_db_fetch_single($db, $stmt);
    if (is_array($result)) {
	    $u = $result['userid'];
	    $stmt = "DELETE FROM mx_usermode WHERE userid = $u";
	    if (pg_query($db, $stmt))
		    pg_query($db, 'commit');
	    $cookie = mx_authenticate_cookie_mint($result['userid']);
	    return $cookie;
    }
}

////////////////////////////////////////////////////////////////
// Redirect to the top for the user.

function mx_authenticate_user_default_application($cookie) {
	global $_mx_default_app;

	$db = mx_db_connect();
	$stmt = 'SELECT D.path
FROM mx_default_apps as D
JOIN "職種一覧表" as C
    ON D."職種" = C."職種" AND C."Superseded" IS NULL
JOIN "職員台帳" as E
    ON E."職種" = C."ObjectID" AND E."Superseded" IS NULL
JOIN mx_authenticate as U
    ON E.userid = U.userid
JOIN mx_session as S
    ON S.userid = U.userid
WHERE
    S.cookie = ' . mx_db_sql_quote($cookie[0]);
  $result = mx_db_fetch_single($db, $stmt);
  if (is_array($result))
	  $app = $result['path'];
  else
	  $app = $_mx_default_app;
  if (substr($app, 0, 1) != '/')
	  $app = '/' . $app;
  return $app;
}

function mx_authenticate_redirect_to_user_top($username, $cookie) {
	$app = mx_authenticate_user_default_application($cookie);
	mx_authenticate_redirect_to($app, $cookie);
}

function mx_redirect_to_user_top($u) {
	global $_mx_default_app;
	$db = mx_db_connect();
	$stmt = '
SELECT D.path
FROM mx_authenticate as U
JOIN "職員台帳" as E
    ON E.userid = U.userid AND E."Superseded" IS NULL
JOIN "職種一覧表" as C
    ON E."職種" = C."ObjectID"
LEFT JOIN mx_default_apps as D
    ON D."職種" = C."職種"
WHERE
    U.userid = ' . mx_db_sql_quote($u);

	$result = mx_db_fetch_single($db, $stmt);
	$app = NULL;
	if (is_array($result))
		$app = $result['path'];
	if (is_null($app) || trim($app) == '')
		$app = $_mx_default_app;
	if (substr($app, 0, 1) != '/')
		$app = '/' . $app;
	$cookie = $_SERVER['URL_PREFIX_COOKIE'];
	$app = '/au/' . $cookie . $app;
	mx_http_redirect($app);
}


////////////////////////////////////////////////////////////////
// Redirect to the originally requested page.
function mx_authenticate_redirect_to($target, $cookie) {
  global $_mx_authenticate_browser_hint_name;

  $browser_hint = $cookie[1];
  $cookie = $cookie[0];
  setcookie($_mx_authenticate_browser_hint_name, $browser_hint,
	    time() + 60*60*24*30, '/');
  mx_http_redirect("/au/$cookie$target");
}

////////////////////////////////////////////////////////////////
// Check authorization and record the application access.
function mx_userdata_sql($u, $program)
{
	if (!$program) {
		$join_program_auth = '';
		$limit_program_auth = '';
	} else {
		$join_program_auth = <<<SQL
			JOIN mx_authorization as X
			ON E."職種" = X."職種" AND E."職位" = X."職位"
			JOIN mx_application as P
			ON P."ObjectID" = X.appid and P."Superseded" IS NULL
SQL;
		$program = mx_db_sql_quote($program);
		$limit_program_auth = <<<SQL
			AND P.path = $program
SQL;
	}
	$q_userid = mx_db_sql_quote($u);
	return <<<SQL
		SELECT
		E.userid, E."ObjectID", E."職員ID", E."姓", E."名",
		(E."姓" || ' ' || E."名") as "氏名",
		C."職種" as "職種", R."職位" as "職位",
		(C."職種" || '・' || R."職位") as "職種・職位",
		D."大分類", D."中分類1", D."中分類2", D."小分類",
		(CASE WHEN
		 ((D."小分類" IS NULL) OR D."小分類" = '')
		 THEN D."中分類2"
		 ELSE D."小分類"
		 END) AS "科目",
		E."職種" as "職種ObjectID", E."職位" as "職位ObjectID"
		FROM "職員台帳" as E
		JOIN "職種一覧表" as C
		ON E."職種" = C."ObjectID" AND C."Superseded" IS NULL
		JOIN "職位一覧表" as R
		ON E."職位" = R."ObjectID" AND R."Superseded" IS NULL
		JOIN "部署一覧表" as D
		ON E."部署" = D."ObjectID" AND D."Superseded" IS NULL
		$join_program_auth
		WHERE E."Superseded" IS NULL AND E.userid = $q_userid
		$limit_program_auth
SQL;
}

function mx_authorization() {
  global $mx_authenticate_current_user;
  global $mx_authenticate_current_session;

  $u = $mx_authenticate_current_user;
  if (is_null($u))
    return array(0, 'unknown application');

  $prog0 = preg_replace('/^(?:\/au\/[^\/]*)+\//', '', $_SERVER['PHP_SELF']);
  // You could do funny tricks with PATH_INFO if you wanted to...
  $program = preg_replace('/\.php\/.*$/', '.php', $prog0);

  $db = mx_db_connect();
  $stmt = ('SELECT P.name, P."ObjectID" FROM mx_application as P ' .
	   'WHERE P."Superseded" IS NULL AND P.path = ' .
	   mx_db_sql_quote($program));
  $data = pg_fetch_array(pg_query($db, $stmt));
  if (! ($data && is_array($data)))
    return array(0, 'unknown application ' . $program);

  $appid = $data['ObjectID'];

  $stmt = ('SELECT RECORD_ACCESS(' .
	     $mx_authenticate_current_session . ', ' .
	     $appid . ')');
  pg_query($db, $stmt);

  $appname = $data['name'];

  $stmt = mx_userdata_sql($u, $program);
  $data = pg_fetch_all(pg_query($db, $stmt));
  if ($data &&
      is_array($data) &&
      $data[0]["userid"] == $u) {
    return array(1, $appname, $data[0], $appid);
  }
  else if ($program == 'u/change-password.php') {
	  $data = array('userid' => $u,
			'職員ID' => '非職員',
			'氏名' => '非職員');
	  return array(1, $appname, $data, $appid);
  }
  else {
    return array(0, $appname);
  }
}

////////////////////////////////////////////////////////////////
//
function mx_authorize_patient_access($patient) {
  global $mx_authenticate_current_user;
  $user = $mx_authenticate_current_user;
  $prog0 = preg_replace('/^(?:\/au\/[^\/]*)+\//', '', $_SERVER['PHP_SELF']);

  // You could do funny tricks with PATH_INFO if you wanted to...
  $program = preg_replace('/\.php\/.*$/', '.php', $prog0);
  $db = mx_db_connect();
  return mx_authorize_patient_access_for_user(&$db, $patient, $program, $user);
}

function mx_authorize_patient_access_for_user(&$db, $patient, $program, $user)
{
  if (is_null($user))
	  return -1;
  $stmt = ('SELECT pt_limited FROM mx_application ' .
	   'WHERE "Superseded" IS NULL AND path = ' .
	   mx_db_sql_quote($program));
  $data = pg_fetch_array(pg_query($db, $stmt));
  if (! ($data && is_array($data)))
	  return -1;

  /*
   * $data['pt_limited'] can be:
   * 'Y': patient can be accessed only by assigned employees;
   * 'X': patient with assigned employees can be accessed only by them,
   *      but ones without assignment can be accessed by anybody;
   * any other values allow access by anybody.
   */

  switch ($data['pt_limited']) {
  case 'Y':
  case 'X':
	  break;
  default:
	  return 0;
  }

  $stmt = ('
      SELECT DISTINCT R."担当役割", E.userid AS u
      FROM "患者台帳" AS P
      JOIN "患者担当職員" AS A ON A."患者" = P."ObjectID" AND A."Superseded" IS NULL
      JOIN "患者担当職員データ" AS D ON D."患者担当職員" = A."ObjectID"
      JOIN "職員台帳" AS E ON E."ObjectID" = D."職員"
      JOIN "担当役割" AS R ON D."担当役割" = R."ObjectID"
      WHERE
      P."ObjectID" = ' . mx_db_sql_quote($patient));

  $result = pg_fetch_all(pg_query($db, $stmt));
  if ($result && is_array($result) && count($result))
	  ; /* has assigned employees */
  else if ($data['pt_limited'] == 'X')
	  return 0;

  if ($result && is_array($result) && count($result))
	  foreach ($result as $row) {
		  if ($row['u'] == $user)
			  return 0;
	  }
  return -1;
}

function mx_limit_patient_with_authorization($pidcol) {
  global $mx_authenticate_current_user;

  $prog0 = preg_replace('/^(?:\/au\/[^\/]*)+\//', '', $_SERVER['PHP_SELF']);
  // You could do funny tricks with PATH_INFO if you wanted to...
  $program = preg_replace('/\.php\/.*$/', '.php', $prog0);

  $db = mx_db_connect();
  $stmt = ('SELECT pt_limited FROM mx_application ' .
	   'WHERE "Superseded" IS NULL AND path = ' .
	   mx_db_sql_quote($program));
  $data = pg_fetch_array(pg_query($db, $stmt));
  if (! ($data && is_array($data)))
	  return '';
  if ($data['pt_limited'] != 'Y' && $data['pt_limited'] != 'X')
	  return ''; /* No further limit applied */

  $u = $mx_authenticate_current_user;
  if (is_null($u))
	  return ' AND (1 = 0)'; /* Nothing is to be returned */

  $base_query = <<<SQL
	SELECT AA."患者"
	FROM
	     "患者担当職員" AS AA
	JOIN "患者担当職員データ" AS DD ON DD."患者担当職員" = AA."ObjectID"
	JOIN "職員台帳" AS EE ON EE."ObjectID" = DD."職員"
	JOIN "担当役割" AS RR ON DD."担当役割" = RR."ObjectID"
	WHERE
	AA."Superseded" IS NULL AND
	EE.userid = $u 
SQL;

  if ($data['pt_limited'] == 'X') {
  	return <<<SQL
	AND $pidcol in (
	(
	$base_query
	) UNION
	(
		SELECT AB."患者"
		FROM "患者担当職員" AS AB
		WHERE NOT EXISTS (
			SELECT 1
			FROM "患者担当職員データ" AS DB
			WHERE DB."患者担当職員" = AB."ObjectID"
		) AND AB."Superseded" IS NULL
	)
)
SQL;

  } else {
	  return "AND $pidcol in ( $base_query )";
  }
}

////////////////////////////////////////////////////////////////
// Boilerplate authorization error message
function mx_authorization_error(&$auth) {
  global $_mx_resource_dir;

  $path = preg_replace('/^(?:\/au\/[^\/]*)+\//', '', $_SERVER['PHP_SELF']);
  // e.g. u/therapist/execution.php
  $depth = count(explode('/', $path)) - 1;
  $up = str_repeat('../', $depth);
  $program_name = $auth[1];
  mx_html_head($program_name);
  print "<body>\n";
  print "本アプリケーション($program_name)のアクセスが許可されていません。";
  print "<br /><a href=\"${up}index.php\">";
  print "<img src=\"/$_mx_resource_dir/images/top_button.png\" ";
  print "align=\"absbottom\"></a>\n";
  print "</body></html>\n";
}

////////////////////////////////////////////////////////////////
// Boilerplate content-type and other headers
function mx_html_head($title=NULL, $do_not_close_head=NULL) {
	$option = array();
	if ($do_not_close_head)
		$option['do_not_close_head'] = 1;
	return mx_html_head_1($title, $option);
}

$_mx_js_files = array(
	'AC_OETags.js', 'mx.js', 'PopupWindow.js', 'date.js',
	'CalendarPopup.js', 'AnchorPosition.js', 'MochiKit.js', 'post_code.js',
	'inc_search_sjis.js', 'vocabulary.js', 'apptcal.js',
	'drawapp-js.php',
);
$_mx_css_files = array(
	'mxstyle', 'calend', 'qxr',
);

function mx_html_head_1($title, $option) {
  global $_mx_resource_dir, $_mx_js_files, $_mx_css_files;
  global $_mx_yui;

  // The <script> tag here used to be closed with itself without using
  // a separate </script>.  This was deliberate, to
  // make sure that people would not try running the applications
  // with IE, which is unusable with the mx widget set.  One of the
  // most important widget in the mx widget set relies on <button>
  // element to act as improved <input type="submit">, which IE
  // does not implement.

  $do_not_close_head=NULL;
  $rsrc = "/$_mx_resource_dir";
  $ctype = "text/html; charset=euc-jp";
  if ($option) {
	  if ($option['do_not_close_head'])
		  $do_not_close_head = 1;
	  if ($option['rsrc'])
		  $rsrc = $option['rsrc'];
	  if ($option['ctype'])
		  $ctype = $option['ctype'];
  }
  if($_mx_yui) {
    print <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html><head><meta http-equiv="content-type" "content="$ctype">
<link rel="shortcut icon" href="${rsrc}/favicon.ico">
<!-- Combo-handled YUI CSS files: -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.8.0/build/reset-fonts/reset-fonts.css&2.8.0/build/container/assets/skins/sam/container.css&2.8.0/build/menu/assets/skins/sam/menu.css">
<!-- Combo-handled YUI JS files: -->
<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.8.0/build/utilities/utilities.js&2.8.0/build/container/container-min.js&2.8.0/build/menu/menu-min.js"></script>
HTML;
  }
  else {
    print ('<html><head><meta http-equiv="content-type" ' .
	 "content=\"$ctype\">" .
	 '<link rel="shortcut icon" href="'. $rsrc . '/favicon.ico">' .
	 "\n");
  }
  foreach ($_mx_js_files as $js) {
	  $js = "$rsrc/$js";
	  print "<script language=\"JavaScript\" src=\"$js\"></script>\n";
  }
  foreach ($_mx_css_files as $css) {
	  $css = "$rsrc/$css.css";
	  print "<link rel=\"stylesheet\" href=\"$css\" />\n";
  }

  if (! is_null($title))
    print "<title>" . htmlspecialchars($title) . "</title>\n";
  if (! $do_not_close_head)
    print "</head>\n";
}

////////////////////////////////////////////////////////////////
// Title span
function mx_titlespan($string, $class='heading') {
  print '<span class="'.$class.'">';
  print $string;
  print '</span>';
}

////////////////////////////////////////////////////////////////
// Redirect to the named URL.
function mx_http_redirect($url) {
    header("Location: $url");
    print "<html><head>";
    print "<meta http-equiv='refresh' content='0;url=$url'></head></html>\n";
}

////////////////////////////////////////////////////////////////
// image element
function mx_img_url($img, $note=NULL, $xtra=NULL) {
  global $_mx_resource_dir;
  $img_url = "/$_mx_resource_dir/images/$img";
  // Needswork: we should generate width and height here.
  $alt = '';
  if (! is_null($note)) {
    $alt = ' alt="' . htmlspecialchars($note) . '"';
    $alt .= ' title="' . htmlspecialchars($note) . '"';
  }
  if (! is_null($xtra)) {
	  $alt .= " $xtra";
  }
  return "<img src=\"$img_url\"$alt/>";
}


////////////////////////////////////////////////////////////////
// Various convenience functions.

// Trim but leave NULL as intact
function mx_trim($s) {
  if (is_null($s)) return NULL;
  return trim($s);
}

// Pick named keys from an array
function mx_pick_array() {
  $arg = func_get_args();
  $a = array(); $v = $arg[0]; $n = func_num_args();
  for ($ix = 1; $ix < $n; $ix++) {
    $k = $arg[$ix];
    $a[$k] = $v[$k];
  }
  return $a;
}

// Pick named key from $_REQUEST
function mx_check_request($key, $default=NULL) {
  if (array_key_exists($key, $_REQUEST))
    return $_REQUEST[$key];
  return $default;
}

// Today
function mx_today_string($offset = 0) {
  $today = localtime(time() + $offset, 1);
  return sprintf("%04d-%02d-%02d",
		 $today['tm_year'] + 1900,
		 $today['tm_mon'] + 1,
		 $today['tm_mday']);
}

// Now
function mx_now_string($offset = 0) {
  $now = localtime(time() + $offset, 1);
  return sprintf("%04d-%02d-%02d %02d:%02d:%02d",
		 $now['tm_year'] + 1900,
		 $now['tm_mon'] + 1,
		 $now['tm_mday'],
		 $now['tm_hour'],
		 $now['tm_min'],
		 $now['tm_sec']);
}

// Get textual 'YYYY-MM-DD' or 'YYYY-MM-DD HH:MM:SS' and parse it down
function mx_timestamp_parse($ts) {
	$match = array();
	if (preg_match('/^\d+-\d+-\d+$/', $ts, &$match))
		$ts = $ts . " 00:00:00";
	$match = array();
	if (!preg_match('/^(\d+)-(\d+)-(\d+)(?:[ T]?(\d+):(\d+)(?::([\d]+)(?:[.\d]*)))?$/',
			$ts, &$match))
		return NULL;
	$year = $match[1];
	$month = $match[2];
	$day = $match[3];
	$hour = $match[4] ? $match[4] : 0;
	$minute = $match[5] ? $match[5] : 0;
	$second = $match[6] ? $match[6] : 0;
	$time = mktime($hour, $minute, $second, $month, $day, $year);
	$a = localtime($time, true);
	$a['timestamp'] = $time;
	return $a;
}

function mx_offset_day($date, $offset) {
	// Even though Japan does not have DST right now...
	// This adds N days plus 12 hours to bring it to around
	// noon of the target day instead of midnight that begins
	// that target day, for both positive and negative offsets
	$offset = ($offset * 24 + 12) * 3600;
	$a = mx_timestamp_parse($date);
	$ts = $a['timestamp'] + $offset;
	$a = localtime($ts, 1);
	return sprintf("%04d-%02d-%02d",
		       $a['tm_year'] + 1900,
		       $a['tm_mon'] + 1,
		       $a['tm_mday']);
}

// Trim timestamp (-1 == date, 0 == minute, 1 == seconds)
function mx_format_timestamp($ts, $to_seconds=1) {
  $match = array();
  if (preg_match('/^(((\d+-\d+-\d+ )?\d+:\d+)(?::\d+)?)(?:\.\d+)?$/', $ts, &$match)) {
    if (0 < $to_seconds)
	    $ts = $match[1];
    else if ($to_seconds < 0)
	    $ts = $match[3];
    else
	    $ts = $match[2];
  }
  return $ts;
}

////////////////////////////////////////////////////////////////
// Capture debugging output.
function mx_var_dump($v)
{
  ob_start();
  var_dump($v);
  $v = ob_get_contents();
  ob_end_clean();
  return $v;
}

////////////////////////////////////////////////////////////////
// Undo magic quotes.  Very annoying.
function mx_undo_magic_quotes() {
  $magic = ' mx.undo.magic.quotes.done ';
  if (get_magic_quotes_gpc() &&
      ! array_key_exists($magic, $_REQUEST)) {
    foreach ($_REQUEST as $k => $v) {
      if (is_array($v)) {
	$vv = array();
	foreach($v as $s)
	  $vv[] = stripslashes($s);
	$_REQUEST[$k] = $vv;
      }
      else {
	$_REQUEST[$k] = stripslashes($v);
      }
    }
    $_REQUEST[$magic] = 1;
  }
}

////////////////////////////////////////////////////////////////
// Work around post PHP 5.2 regression to mbstring.encoding_translation
// Cf. http://kona.tonakaj.org/sc430/x2.html
function mx_fix_mbencode_on_php5() {

  if (version_compare(phpversion(), '5.2.0') < 0)
    return;

  $magic = ' mx.workaround.php52.encoding.bug.done ';
  if (! array_key_exists($magic, $_REQUEST)) {
    if (ini_get('mbstring.encoding_translation') and
	isset($_SERVER["CONTENT_TYPE"]) and
	strpos($_SERVER["CONTENT_TYPE"], "multipart/form-data;") === 0)
    {
      mb_convert_variables(mb_internal_encoding(), 'auto',
			   $_POST, $_GET, $_REQUEST);
      if (is_array($_FILES) and count($_FILES))
	foreach ($_FILES as $input => $hash) {
	  mb_convert_variables(mb_internal_encoding(), 'auto',
			       $_FILES[$input]['name']);
	}
    }
    $_REQUEST[$magic] = 1;
  }
}

////////////////////////////////////////////////////////////////
// PHP mucks with form names in the $_REQUEST[] array.  Better
// inefficient and safe than sorry.
function mx_form_encode_name($name) {
  return bin2hex($name);
}

function mx_form_decode_name($encoded) {
  return pack ("H*", $encoded);
}

function mx_form_escape_key($list_or_null) {
  if (is_null($list_or_null)) {
    return 'E';
  }

  $val = array();
  foreach ($list_or_null as $data) {
    if (is_null($data)) {
      $val[] = 'N';
    } else {
      $val[] = bin2hex($data);
    }
  }
  return implode('X', $val);
}

function mx_form_unescape_key($encoded) {
  if ($encoded == 'E')
    return NULL;
  $val = array();
  foreach (explode('X', $encoded) as $hexval) {
    if ($hexval == 'N') {
      $val[] = NULL;
    } else {
      $val[] = pack("H*", $hexval);
    }
  }
  return $val;
}

////////////////////////////////////////////////////////////////
// Convert form input data for constraints
function mx_forme_convert($constraints, &$data, $colname)
{
	$num_done = 0;
	foreach (explode(',', $constraints) as $c) {
		switch ($c) {
		case 'datetime':
			$dt = mx_ui_japanese_date($data[$colname . '_dt']);
			$data[$colname] = sprintf("%s %02d:%02d:00",
						  $dt,
						  $data[$colname . '_hh'],
						  $data[$colname . '_mm']);
			break;
		case 'date':
			$data[$colname] = mx_ui_japanese_date($data[$colname]);
			break;
		case 'number':
		case 'nonzero':
		case 'digits':
		case 'posint':
		case 'nnint':
		case 'range':
			if (!$num_done)
				$data[$colname] =
					mb_convert_kana($data[$colname],
							'n', 'EUC-JP');
			$num_done = 1;
			break;
		}
	}
}


////////////////////////////////////////////////////////////////
// Read-only output.

function mx_html_paragraph($s, $no_quote=NULL) {
  if (!$no_quote)
    $s = htmlspecialchars($s);
  $s = preg_replace('/\n/', "<br />\n", $s);
  return "<div>$s</div>";
}

////////////////////////////////////////////////////////////////
// Form elements.
function mx_formi_input($type, $name, $value, $xtra='') {
  $value = htmlspecialchars($value);
  print "<input type=\"$type\"$xtra name=\"$name\" value=\"$value\">\n";
}

function mx_check_option($key, $ary, $default=NULL) {
	if (is_array($ary) && array_key_exists($key, $ary))
		return $ary[$key];
	return $default;
}

$__mx_formi_dek = ' onKeyPress="return disableEnterKey(this,event)"';
$__mx_formi_inc_search = ' onKeyPress="return incSearch(this,event,\'%s\',\'%s\');"';

function __mx_option_css($option) {
	$ret = array();
	$ime = mx_check_option('ime', $option);
	switch ($ime) {
	case 'disabled':
	case 'active':
	case 'inactive':
		$ret[] = '.ime-' . $ime;
		break;
	}
	if (count($ret))
		return ' class="' . implode(' ', $ret) . '"';
	else
		return '';
}

function mx_formi_upload($name, $option=NULL) {
  # FireFox doesn't seem to support onFocus in this type of input element yet.
  print "<input type=\"file\" name=\"$name\" />\n";
}

function mx_formi_text($name, $value, $option=NULL) {
  global $__mx_formi_dek;
  global $__mx_formi_inc_search;
  $css = __mx_option_css($option);
  $size = mx_check_option('size', $option);
  $maxlength = mx_check_option('maxlength', $option);
  $trailer = mx_check_option('trailer', $option);
  $z = '';
  if (!is_null($size)) { $z = "$z size=\"$size\""; }
  if (!is_null($maxlength)) { $z = "$z maxlength=\"$maxlength\""; }
  if (mx_check_option('add_id', $option)) { $z = "$z id=\"$name\""; }
  $key_handler = $__mx_formi_dek;
  if (mx_check_option('key_handler', $option))
    $key_handler = mx_check_option('key_handler', $option);
  mx_formi_input('text', $name, $value, "$z$key_handler$css");
  if (!is_null($trailer))
    print $trailer;
}

$__mx_formi_date_used = 0;
function mx_formi_date($name, $value, $option=NULL) {
  global $__mx_formi_dek;
  global $__mx_formi_date_used;

  $__mx_formi_date_used++;
  $css = __mx_option_css($option);
  $size = mx_check_option('size', $option);
  $maxlength = mx_check_option('maxlength', $option);
  $trailer = mx_check_option('trailer', $option);
  $z = '';
  if (!is_null($size)) { $z .= "$z size=\"$size\""; }
  if (!is_null($maxlength)) { $z .= "$z maxlength=\"$maxlength\""; }

  $id=" id=\"$name\"";
  $vname = mx_check_option('vname', $option, "calend");
  print "<script>var $vname = new MedexCalendarPopup(\"div-$name\");</script>\n";
  print "<div id=\"div-$name\" class=\"soecal\" style=\"position:absolute; visibility:hidden\"></div>\n";

  mx_formi_input('text', $name, $value, "$z$__mx_formi_dek$css$id");
  print ('<a href="#" '.
	 "onclick=\"$vname.select(document.getElementById('$name'),".
	 "'anchor-$name', ".
	 "'yyyy-MM-dd'); return false;\"" .
	 " id=\"anchor-$name\" name=\"anchor-$name\" >*${trailer}</a>");
}

function mx_formi_datetime($name, $value, $option=NULL) {
  global $__mx_formi_dek;

  # split value into date and time
  $dt = $tm = $hh = $mm = NULL;

  if(!is_null($value) and $value != '')
    list($dt, $tm) = explode(' ', $value);
  
  if(!is_null($tm) and $value != '')
    list($hh, $mm, $ss) = explode(':', $tm);

  # show date part
  mx_formi_date($name . '_dt', $dt, $option);

  # show time part
  $css = __mx_option_css($option);
  $size = 2;
  $maxlength = 2;
  $z = '';
  if (!is_null($size)) { $z .= "$z size=\"$size\""; }
  if (!is_null($maxlength)) { $z .= "$z maxlength=\"$maxlength\""; }

  print '&nbsp;';
  mx_formi_input('text', $name . '_hh' , $hh, "$z$__mx_formi_dek$css");
  print '&nbsp;:&nbsp;';
  mx_formi_input('text', $name . '_mm' , $mm, "$z$__mx_formi_dek$css");

}

function mx_formi_password($name, $value, $option=NULL) {
  global $__mx_formi_dek;
  $css = __mx_option_css($option);
  mx_formi_input('password', $name, $value, "$__mx_formi_dek$css");
}

function mx_formi_textarea($name, $value, $option=NULL) {
  $z = '';
  if (mx_check_option('add_id', $option)) { $z = "$z id=\"$name\""; }
  $value = htmlspecialchars($value);
 
  $css = __mx_option_css($option);
  $cols = mx_check_option('cols', $option, 30);
  $rows = mx_check_option('rows', $option, 2);
  $maxlength = mx_check_option('maxlength', $option);
  $show_remain = mx_check_option('show-remain', $option, 1);
  $vocab = mx_check_option('vocab', $option);
  $void = "";
  if ($vocab) {
    $void = " id=\"id_$name\"";
    $icon = mx_check_option('icon', $option);
    if(!$icon)
      $icon = 'vocab.png';
    $UseList = mx_img_url($icon, "定型文を選択");
    $words = array();
    $words[] = "'div0_$name'";
    $words[] = "'div1_$name'";
    $words[] = "'id_$name'";
    $words[] = "'button_$name'";
    $words[] = "'選択'";
 //   $words[] = mx_check_option('vocab-no-newline', $option) ? 0 : 1;
 $words[] =0;

    foreach ($vocab as $w) {
	$words[] = "'" . $w . "'";
    }
    $use = "vocabularyuse(" . implode(", ", $words) . ")";
    print "<span id=\"div0_$name\" style=\"position: float\">";
    print "<span onclick=\"$use\" id=\"button_$name\">$UseList</span>";
    print "</span>";
    print "<div class=\"vocab\" id=\"div1_$name\" style=\"display: none\">";
    print "</div>\n";
  }

  $remain_div = $show_remain ? "'${name}_remain'" : 'null';
  $script = $maxlength ? " onkeyup=\"limitChars(this, $maxlength, $remain_div);\" " : "";
  print "<textarea $z $script name=\"$name\" cols=\"$cols\" rows=\"$rows\"$css$void>";
  print $value;
  print "</textarea>\n";
//0825-2012
 
  if ($show_remain )
    print "<div id=${remain_div}></div>";

}
//0808-2012

/* Mimics how los draws clickable elements */
function mx_formi_linkalike($label, $button, $msg) {
	if (is_null($button)) {
		print $label;
		return;
	}
	print "<div onclick=\"activateInnerButton(this);\">";
	mx_formi_submit_2part(0, $button, $msg);
	print '<span class="link">';
	print $label;
	print '</span>';
	mx_formi_submit_2part(1, $button, $msg);
	print "</div>";
}

function mx_formi_submit_2part($part, $name, $value, $title=NULL, $option=NULL) {
  switch ($part) {
  case 0:
    if (! is_null($title))
      $title = ' title="' . htmlspecialchars($title) . '"';
    $name = htmlspecialchars($name);
    $value = htmlspecialchars($value);
    $cls = mx_check_option('class', $option);
    if(!$cls)
      $cls = 'plain';
    print "<button class=\"$cls\"$title ";
    print "onclick=\"mx_submit_button(this, '$name', '$value');\"";
    print ">\n";
    break;
  case 1:
    print "</button>\n";
    break;
  }
}

function mx_formi_submit_x($name, $value, $option){
  $label = mx_check_option('label', $option);
  $title = mx_check_option('title', $option);
  mx_formi_submit($name, $value, $label, $title, $option);
}

function mx_formi_submit($name, $value, $label=NULL, $title=NULL, $option=NULL) {
  global $_mx_icon_with_text;
  if (! is_null($label)) {
    mx_formi_submit_2part(0, $name, $value, $title, $option);
    print $label;
    if ($_mx_icon_with_text && $title) {
       print " " . htmlspecialchars($title);
    }
    mx_formi_submit_2part(1, $name, $value, $title, $option);
  } else {
    $cls = mx_check_option('class', $option);
    $extra = '';
    if ($cls)
      $extra = " class=\"$cls\"";
    mx_formi_input('submit', $name, $value, $extra);
  }
}

function mx_formi_nosubmit($label = NULL, $title = NULL) {
	if (!is_null($title))
		$title = ' title="' . htmlspecialchars($title) . '"';
	print "<span class=\"nosubmit\"$title>";
	print $label;
	print "</span>";
}

function mx_formi_hidden($name, $value, $xtra=NULL) {
  mx_formi_input('hidden', $name, $value, $xtra);
}

function mx_formi_select($name, $value, $selection, $options=NULL) {
  global $__mx_formi_dek;
  $cript = $__mx_formi_dek;
  if (mx_check_option('immediate-submit', $options))
	  $cript .= ' onChange="return form.submit()"';
  else if (mx_check_option('onchange-script', $options))
	  $cript .= sprintf(' onChange="%s"', $options['onchange-script']);
  print "<select name=\"$name\" id=\"$name\"$cript>\n";
  $hit_exists = NULL;
  foreach ($selection as $key => $label) {
    if ($value == $key) $hit_exists = 1;
  }
  foreach ($selection as $key => $label) {
	  if (is_array($label) && array_key_exists('old', $label)) {
		  $historical = 1;
	  }
	  else {
		  $historical = 0;
	  }
	  if (is_array($label) && array_key_exists('value', $label)) {
		  $label = $label['value'];
	  }
	  if ($historical && ($value != $key))
		  continue;
	  print "<option value=\"" . htmlspecialchars($key) . "\"";
	  if (($value == $key) ||
	      (is_null($hit_exists) && $key == "")) {
		  print " selected";
		  $hit_exists = 1;
	  }
	  print ">" . htmlspecialchars($label) . "</option>\n";
  }
  print "</select>\n";
}

function mx_formi_radio($name, $value, $selection, $option=NULL) {

  global $__mx_formi_dek;

  $omit_label = mx_check_option('omit-label', $option);
  $immediate_submit = mx_check_option('immediate-submit', $option);
  $lf = mx_check_option('item-delimiter', $option);

  $cript = $__mx_formi_dek;
  if ($immediate_submit)
    $cript .= ' onChange="return form.submit()"';

  $hit_exists = NULL;
  foreach ($selection as $key => $label) {
    if ($value == $key) $hit_exists = 1;
  }
  $first = 1;
  foreach ($selection as $key => $label) {
    if (!$first)
	print $lf;
    $first = 0;
    $k = htmlspecialchars($key);
    $checked = (($value == $key) ||
		(is_null($hit_exists) && $key == '')) ? ' checked' : '';
    print "<input type=\"radio\" name=\"$name\" value=\"$k\"$checked$cript>\n";
    if (! $omit_label)
      print htmlspecialchars($label);
  }
}

function mx_formi_checkbox($name, $value, $desc=NULL) {
  global $__mx_formi_dek;
  $caption = mx_check_option('Caption', $desc);
  $with_id = mx_check_option('WithID', $desc) ? " id=\"$name\"" : '';
  print "<input type=\"checkbox\" name=\"$name\"$with_id";
  if ($value)
    print ' checked';
  print $__mx_formi_dek;
  print ">";
  if ($caption)
    print $caption;
  print "\n";
}

function mx_formi_pt_submit($what)
{
	global $_mx_bmd_layout, $_mx_resource_dir;

	switch ($what) {
	case 'SetPatient':
		$img = 'pt_select.png';
		$txt = 'Pt Select';
		break;
	case 'UseListOfPatients':
		$img = 'pt_list.png';
		$txt = 'Pt List';
		break;
	case 'UseListOfCheckIn':
		$img = 'ci_list.png';
		$txt = 'Schedule';
		break;
	case 'UseListOfCheckInForMe':
		$img = 'ci_list.png';
		$txt = 'checkin';
		break;
	}
	if ($_mx_bmd_layout)
		mx_formi_submit($what, 1, $txt, $txt);
	else
		mx_formi_submit($what, 1,
				"<img src=\"/$_mx_resource_dir" .
				"/images/$img\">");
}

function mx_formi_login_submit()
{
	global $_mx_bmd_layout;
	if ($_mx_bmd_layout)
		mx_formi_submit('submit', "login",
				"login", "login");
	else
		mx_formi_submit('submit', "login",
				mx_img_url('login_button.png'),
				"login");
}

function mx_explicit_refresh()
{
	global $_mx_qbe_explicit_refresh;

	if ($_mx_qbe_explicit_refresh)
		mx_formi_submit('NOOOPREFRESH', 'リフレッシュ');
}

function mx_zeropad($data, $width)
{
	$v = trim($data);
	if ($v != '' && strlen($v) < $width) {
		$cnt = $width - strlen($v);
		for ($i = 0; $i < $cnt; $i++) {
			$v = "0$v";
		}
		return $v;
	}
	return $data;
}

function mx_xlate_jzspace($data)
{
	$l = mb_strlen($data, 'EUC-JP');
	$o = '';
	for ($i = 0; $i < $l; $i++) {
		$c = mb_substr($data, $i, 1, 'EUC-JP');
		if ($c == '　')
			$c = ' ';
		$o .= $c;
	}
	return $o;
}

function mx_vstring($s) {
  $o = '';
  for($i=0; $i < mb_strlen($s, 'EUC-JP'); $i++ )
    $o .= mb_substr($s, $i, 1, 'EUC-JP') . '<br>';
  return $o;
}

function mx_wareki($yyyy_mm_dd, $option=array())
{
  list($y, $m,$d) = split('-', $yyyy_mm_dd);
  $ymd = sprintf("%02d%02d%02d", $y, $m, $d);
  $n=null;
  if ($ymd <= "19120729") {
    $gg = "明治";
    $yy = $y - 1867;
    $n=1;
  } elseif ($ymd >= "19120730" && $ymd <= "19261224") {
    $gg = "大正";
    $yy = $y - 1911;
    $n=2;
  } elseif ($ymd >= "19261225" && $ymd <= "19890107") {
    $gg = "昭和";
    $yy = $y - 1925;
    $n=3;
  } elseif ($ymd >= "19890108") {
    $gg = "平成";
    $yy = $y - 1988;
    $n=4;
  }
  
  if($option['numeric'])
    return sprintf("%d%02d%02d%02d" , $n, $yy, $m, $d);
  $s = "{$gg}{$yy}年{$m}月{$d}日";
  if ($option['dayofweek']) {
    $dow = array('日','月','火','水','木','金','土');
    $w = $dow[date('w', mktime(0,0,0,$m, $d, $y))];
    $s .= "(${w})";
  }
  return $s;
}

function mx_calc_age($birth, $asof=NULL)
{
    if(is_null($asof))
        $asof = date("Y-m-d");
    list($ty, $tm, $td) = explode('-', $asof);
    list($by, $bm, $bd) = explode('-', $birth);
    $age = $ty - $by;
    if($tm * 100 + $td < $bm * 100 + $bd) $age--;
    return $age;
}


function mx_empty_field_mark()
{
	global $_mx_say_empty_for_empty;
	return ($_mx_say_empty_for_empty ? '(値無し)' : '　');
}

////////////////////////////////////////////////////////////////
// extdocument

function mx_find_ext_document($id) {
	$db = mx_db_connect();
	$stmt = ('SELECT t.label_string, t.mime_type, t.extension, t.handler as handler,
		  octet_length(d.data) as numbytes
		  FROM mx_extdocument d
		  JOIN mx_doctype t ON d.doctype = t."ObjectID"
 		  WHERE d."ObjectID" = ' .
		 "'" . pg_escape_string($id) . "'");
	$d = mx_db_fetch_single($db, $stmt);
	if (!$d)
		return NULL;
	return $d;
}

function mx_find_extmedia_annotation($id, $handler=NULL) {
	$db = mx_db_connect();
	$stmt = ('SELECT t.label_string, t.mime_type, t.extension, t.handler as handler,
                  d."ObjectID"
		  FROM mx_extdocument d
		  JOIN mx_doctype t ON d.doctype = t."ObjectID"
 		  WHERE d.annotates = ' . mx_db_sql_quote($id));
	if ($handler)
	  $stmt .= ' AND t.handler=' . mx_db_sql_quote($handler);

	$d = mx_db_fetch_all($db, $stmt);
	if (!$d)
		return NULL;
	return $d;
}

function template_restore_appstate(&$db, $id, $action, $debug=NULL)
{
	$stmt = '
SELECT A.path AS path, S.data as data
FROM mx_appstate AS S
JOIN mx_application AS A ON S.application = A."ObjectID"
WHERE S.id = ' . mx_db_sql_quote($id);
	$d = mx_db_fetch_single($db, $stmt);

	if ($debug) {
		print "$stmt\n";
		var_dump($d);
	}

	$application = $d['path'];
	$goto = ('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
		 "/$application?RestoreApplicationState=$id");
	if (!is_null($action))
		$goto .= "&RestoreAction=$action";
	if (!$debug)
		mx_http_redirect($goto);

	print "Redirect: $goto\n";
	print "Data:\n";
	var_dump($d['data']);
	print "\n";
}

////////////////////////////////////////////////////////////////
// Holidays
function mx__add_holiday_m_d(&$h, $parsed, $date, $desc, $mon, $day, $year)
{
	if ((is_null($year) || ($parsed['tm_year'] + 1900 == $year)) &&
	    (is_null($mon) || ($parsed['tm_mon'] + 1 == $mon)) &&
	    ($parsed['tm_mday'] == $day))
		$h[] = $desc;
}

function mx__add_holiday_m_w(&$h, $parsed, $date, $desc, $mon, $week, $dow, $year)
{
	if ((is_null($year) || ($parsed['tm_year'] + 1900 == $year)) &&
	    (is_null($mon) || ($parsed['tm_mon'] + 1 == $mon)) &&
	    ($parsed['tm_wday'] == $dow) &&
	    (is_null($week) ||
	     ((int)(($parsed['tm_mday'] - 1) / 7) + 1 == $week)))
		$h[] = $desc;
}

function mx__add_holiday(&$h, $parsed, $date, $desc, $year, $mon, $day)
{
	if (($parsed['tm_mon'] + 1 == $mon) &&
	    ($parsed['tm_mday'] == $day) &&
	    ($parsed['tm_year'] + 1900 == $year))
		$h[] = $desc;
}

function mx_holiday_table($year, $rules, $tweak)
{
	$holiday = array();

	$date = sprintf("%04d-01-01", $year);

	// 官報を見ないと祝日は決まらないが、天文上は、2100 年までの春
	// 分・秋分日は、三月と九月の下の日である。

	$spring_equinox = (int)(20.8431 +
				($year - 1980) * 0.242194 -
				(int)(($year - 1980)/4));
	$autumn_equinox = (int)(23.2488 +
				($year - 1980) * 0.242194 -
				(int)(($year - 1980)/4));

	// 祝日法の扱いをする場合のみ意味がある値
	$subst_needed = 0;
	$last_holiday = 0;
	while (1) {
		$parsed = mx_timestamp_parse($date);
		if ($parsed['tm_year'] + 1900 != $year)
			break;

		$h = array();
		foreach ($rules as $r) {
			switch ($r['rule']) {
			case 'D':
				mx__add_holiday_m_d(&$h, $parsed, $date,
						    $r,
						    $r['month'],
						    $r['mday'],
						    $r['year']);
				break;
			case 'W':
				mx__add_holiday_m_w(&$h, $parsed, $date,
						    $r,
						    $r['month'],
						    $r['nth'],
						    $r['wday'],
						    $r['year']);
				break;
			case 'S':
				mx__add_holiday_m_d(&$h, $parsed, $date,
						    $r,
						    3,
						    $spring_equinox,
						    $r['year']);
				break;
			case 'A':
				mx__add_holiday_m_d(&$h, $parsed, $date,
						    $r,
						    9,
						    $autumn_equinox,
						    $r['year']);
				break;
			}
		}
		if (count($h) == 0) {
			// 2005 年 改正祝日法
			if ($tweak && $subst_needed) {
				$m = array('name' => '振替休日', 'avail' => 0);
				$holiday[$date] = array($m);
				$subst_needed = 0;
			}
		} else {
			$since_last = $parsed['timestamp'] - $last_holiday;
			if ($tweak) {
				if ($parsed['tm_wday'] == 0)
					$subst_needed = 1;
				if (36 * 3600 <= $since_last &&
				    $since_last <= 60 * 3600 &&
				    $parsed['tm_wday'] != 1) {
					// 祝日法、第三条第三項
					$yesterday = mx_offset_day($date, -1);
					$m = array('name' => '国民の休日',
						   'avail' => 0);
					$holiday[$yesterday] = array($m);
				}
			}
			$holiday[$date] = $h;
			$last_holiday = $parsed['timestamp'];
		}

		$date = mx_offset_day($date, 1);
	}
	return $holiday;
}

function mx_national_holiday($year)
{
	$db = mx_db_connect();
	$stmt = <<<SQL
SELECT rule, year, month, mday, nth, wday, name, 0 as avail,
null as start_time,
null as end_time,
null as modality
FROM "国民の祝日"
WHERE
"Superseded" IS NULL
SQL;
	$rules = array();
	foreach (mx_db_fetch_all($db, $stmt) as $d) {
		if (!is_null($d['year']) && $d['year'] != $year)
			continue;
		$rules[] = $d;
	}

	return mx_holiday_table($year, $rules, 1);
}

function mx_hospital_holiday($year, $modality)
{
	$db = mx_db_connect();
	if (is_null($modality)) {
		$mlimit = '';
	} else {
		$mlimit = " OR (modality = $modality)";
	}
	$stmt = <<<SQL
SELECT rule, year, month, mday, nth, wday, name, avail,
to_char(start_time, 'HH24:MI') as start_time,
to_char(end_time, 'HH24:MI') as end_time,
modality
FROM "病院休日表"
WHERE
(modality IS NULL)$mlimit
ORDER BY
(CASE WHEN modality IS NULL THEN 1 ELSE 2 END),
sortorder
SQL;
	$rules = array();
	foreach (mx_db_fetch_all($db, $stmt) as $d) {
		if (!is_null($d['year']) && $d['year'] != $year)
			continue;
		$rules[] = $d;
	}

	return mx_holiday_table($year, $rules, 0);
}

////////////////////////////////////////////////////////////////
// Appointment Schedule
//
// Given the date range, return an array of elements that have
// the following elements:
//
//   - start_time
//   - end_time
//   - available
//
// where (start_time, end_time) defines the timerange that this
// entry applies to, available is the capacity the modality has
// (i.e. number of appointments that can be made to the modality)
// for each unit time (30-minutes).
//
// Later element in this array overrides the earlier elements
// if there are overlap in the time ranges.
//
// time ranges expressed by start_time and end_type are matched
// textually when checking against a proposed time range for
// making a new appointment.  I.e. start_time and end_time in
// this array does not have to be valid timestamp.
//
// E.g.
//        array(
//                array('start_time' => '0000-00-00 00:00',
//                      'end_time' => '9999-99-99 99:99',
//                      'available' => 3),
//                array('start_time' => '2008-07-10 12:00',
//                      'end_time' => '2008-07-10 99:99',
//                      'available' => 0),
//                array('start_time' => '2008-07-22 08:00',
//                      'end_time' => '2008-07-22 12:00',
//                      'available' => 2),
//        );
//
// shows that by default 3 appointments per unit time is possible
// (because any timestamp will be between start and end of the
// first element, until year 10000), but the modality is unavailable
// on July 10th 2008 in the afternoon.  The modality has reduced
// capacity in the morning of July 22th 2008.

function mx__add_sched_avail(&$avail, $date, $available, $desc='')
{
	$avail[] = array('start_time' => "$date 00:00",
			 'end_time' => "$date 99:99",
			 'available' => $available,
			 'description' => trim($desc));
}

function mx_default_modality_capacity($modality)
{
	global $_mx_appt_max_dups;
	if (is_null($modality))
		return $_mx_appt_max_dups;

	$stmt = <<<SQL
SELECT capacity
FROM modality_capacity
WHERE modality = $modality;
SQL;
	$db = mx_db_connect();
	$d = mx_db_fetch_single($db, $stmt);
	if (is_null($d) || !$d || $d['capacity'] < 0)
		return $_mx_appt_max_dups;
	return $d['capacity'];
}

function mx_update_modality_capacity($modality, $capacity)
{
	if (is_null($capacity) || trim($capacity) == '')
		$delete_only = 1;
	else {
		$current = mx_default_modality_capacity($modality);
		if ($current == $capacity)
			return;
	}

	$db = mx_db_connect();

	$stmt = <<<SQL
DELETE FROM modality_capacity WHERE modality = $modality;
SQL;
	pg_query($db, $stmt);

	if ($delete_only)
		return;

	$stmt = <<<SQL
INSERT INTO modality_capacity
(modality, capacity) VALUES ($modality, $capacity);
SQL;
	pg_query($db, $stmt);
}

function mx_sched_available($fromdate, $todate, $modality)
{
	global $_mx_appt_days;

	$from = mx_timestamp_parse($fromdate);

	$avail = array();

	// default
	$default_capacity = mx_default_modality_capacity($modality);


	$avail[] = array('start_time' => '0000-00-00 00:00',
			 'end_time' => '9999-99-99 99:99',
			 'available' => $default_capacity);

	$calyear = NULL;
	$cal_n = NULL;
	$cal_h = NULL;

	for ($d = $fromdate;
	     $d <= $todate;
	     $d = mx_offset_day($d, 1)) {
		// days of the week
		$a = mx_timestamp_parse($d);
		$dow = $a['tm_wday'];

		// 国民の祝日表および病院の休診日
		if ($calyear != $a['tm_year'] + 1900) {
			$cal_n = mx_national_holiday($a['tm_year'] + 1900);
			$cal_h = mx_hospital_holiday($a['tm_year'] + 1900,
						     $modality);
			$calyear = $a['tm_year'] + 1900;
		}

		if ($dow == 0) {
			// 日曜日
			mx__add_sched_avail(&$avail, $d, 0);
		} else if (array_key_exists($d, $cal_n)) {
			// 国民の祝日
			$name = $cal_n[$d][0]['name'];
			mx__add_sched_avail(&$avail, $d, 0, $name);
		} else if (!array_key_exists($dow, $_mx_appt_days)) {
			// 予約カレンダーにそもそも表示しない日
			mx__add_sched_avail(&$avail, $d, 0);
		}

		if (array_key_exists($d, $cal_h)) {
			// 病院休日表が最優先
			foreach ($cal_h[$d] as $data) {
				if ($data['avail'] &&
				    is_null($data['modality']))
					$available = $default_capacity;
				else
					$available = $data['avail'];
				$st = $data['start_time'];
				$et = $data['end_time'];
				$st = $st ? $st : '00:00';
				$et = $et ? $et : '99:99';
				$name = $data['name'];
				if (!is_null($data['modality']))
					$name = NULL;
				$avail[] = array('start_time' => "$d $st",
						 'end_time' => "$d $et",
						 'available' => $available,
						 'description' => $name);
			}
		}
	}

	return $avail;
}

function mx_check_capacity($avail, $date, $time, $duration)
{
	global $_mx_appt_max_dups;

	$m = array();
	if (!preg_match('/^(\d+):(\d+)$/', $time, &$m))
		return array(0);
	$h = $m[1];
	$m = $m[2];
	$cnts = array();
	for ($offset = 0; $offset < $duration; $offset += 30) {
		$range0 = sprintf("%s %02d:%02d", $date, $h, $m);
		if ($m + 30 >= 60) {
			$h = $h + 1;
			$m = $m + 30 - 60;
		} else {
			$m = $m + 30;
		}
		$range1 = sprintf("%s %02d:%02d", $date, $h, $m);

		$cnt = $_mx_appt_max_dups;
		foreach ($avail as $data) {
			$start_time = $data['start_time'];
			$end_time = $data['end_time'];

			if ($start_time < $range1 && $range0 < $end_time)
				$cnt = $data['available'];
		}
		$cnts[] = $cnt;
	}
	return $cnts;
}

function mx_sched_holiday($fromdate, $todate, $modality)
{
	global $_mx_appt_days;

	$from = mx_timestamp_parse($fromdate);

	$holidays = array();

	$calyear = NULL;
	$cal_n = NULL;
	$cal_h = NULL;

	$calendar = array();
	for ($d = $fromdate;
	     $d <= $todate;
	     $d = mx_offset_day($d, 1)) {
		// days of the week
		$a = mx_timestamp_parse($d);
		$dow = $a['tm_wday'];

		// 国民の祝日表および病院の休診日
		if ($calyear != $a['tm_year'] + 1900) {
			$cal_n = mx_national_holiday($a['tm_year'] + 1900);
			$cal_h = mx_hospital_holiday($a['tm_year'] + 1900,
						     $modality);
			$calyear = $a['tm_year'] + 1900;
		}

		$avail = array();
		if ($dow == 0) {
			// 日曜日
			mx__add_sched_avail(&$avail, $d, 0);
		} else if (array_key_exists($d, $cal_n)) {
			// 国民の祝日
			$name = $cal_n[$d][0]['name'];
			mx__add_sched_avail(&$avail, $d, 0, $name);
		} else if (!array_key_exists($dow, $_mx_appt_days)) {
			// 予約カレンダーにそもそも表示しない日
			mx__add_sched_avail(&$avail, $d, 0);
		}

		if (array_key_exists($d, $cal_h)) {
			// 病院休日表が最優先
			foreach ($cal_h[$d] as $data) {
				if ($data['avail'] &&
				    is_null($data['modality']))
					$available = 1;
				else
					$available = $data['avail'];
				$st = $data['start_time'];
				$et = $data['end_time'];
				$st = $st ? $st : '00:00';
				$et = $et ? $et : '99:99';
				$name = $data['name'];
				if (!is_null($data['modality']))
					$name = NULL;
				$avail[] = array('start_time' => "$d $st",
						 'end_time' => "$d $et",
						 'available' => $available,
						 'description' => $name);
			}
		}

		$holiday = 0;
		foreach ($avail as $data) {
			if (!$data['available'])
				$holiday = 1;
			else
				$holiday = 0;
		}
		if ($holiday > 0)
			$calendar[$d] = 1;
	}
	return $calendar;
}

////////////////////////////////////////////////////////////////
// Random utility functions

function mx_hide_patient_selection()
{
	global $_mx_product_name;

	if ($_mx_product_name != 'MYKARTE')
		return 0;
	/*
	 * NEEDSWORK: here we would need to see if the
	 * user is a doctor-user class and return false.
	 */
	return 1;
}

function mx_send_mail($recipient, $subject, $message, $extra=NULL)
{
	global $_mx_sender_email;

	$idata = mx_get_install_data();
	$from = mx_check_option('MX_SENDER_EMAIL', $idata, $_mx_sender_email);
	$eh = "From: $from";
	mb_send_mail($recipient, $subject, $message, $eh);
}

function mx_force_claim_on_date($db, $pt_oid, $date) {
  global $_mx_orca_send_rx_on_orderdate;
  global $_mx_orca_send_injection_on_orderdate;

  print "<!-- force pt_oid=$pt_oid, send=$date -->\n";

  // find app/objectid on the date
    $specs = array('rx' =>
		   array('TABLE' => '薬剤処方箋',
			 'COLUMN' => $_mx_orca_send_rx_on_orderdate ? '処方年月日' : '処方開始日'),
		   'injection' =>
		   array('TABLE' => '注射処方箋',
			 'COLUMN' => $_mx_orca_send_injection_on_orderdate ? '処方年月日' : '処方開始日'),
		   );

  $date = mx_db_sql_quote($date);

  foreach($specs as $app => $tc) {
    $tbl = '"' . $tc['TABLE'] . '"';
    $col = '"' . $tc['COLUMN'] . '"';
    $app = mx_db_sql_quote($app);
    $del_stmt = <<<SQL
      DELETE FROM claim_request2
      WHERE objectid in
      (
       SELECT "ObjectID" FROM $tbl
       WHERE $col = $date AND "患者"=$pt_oid
       AND "Superseded" IS NULL
       )
SQL;
    print "<!-- $del_stmt -->";
    pg_query($db, $del_stmt);

    $ins_stmt = <<<SQL
      INSERT INTO claim_request2 (app, objectid, send_on)
      SELECT $app, "ObjectID", $date FROM $tbl
      WHERE $col = $date AND "Superseded" IS NULL
      AND "患者"=$pt_oid
SQL;
    print "<!-- $ins_stmt -->";
    pg_query($db, $ins_stmt);
  }
}

function mx_record_claim_request($db, $app, $objectid, $send_on) {
  print "<!-- record app=$app, oid=$objectid, send=$send_on -->\n";

  if (!$app || !$objectid)
    return;
  $app = mx_db_sql_quote($app);

  $objectid = mx_db_sql_quote($objectid);
  $send_on = mx_db_sql_quote($send_on);
  $del_stmt = <<<SQL
    DELETE FROM claim_request2
    WHERE app=$app AND objectid=$objectid
SQL;

  $ins_stmt = <<<SQL
    INSERT INTO claim_request2
    (app, objectid, send_on)
    VALUES ($app, $objectid, $send_on)
SQL;

  pg_query($db, $del_stmt);
  pg_query($db, $ins_stmt);
}

function mx_kick_claim_if_by_pid($db, $patient_id, $date, $urgent=0, $app=NULL, $objectid=NULL)
{
  $patient_id = mx_db_sql_quote($patient_id);
  if($app == 'force') {
    $stmt = <<<SQL
		SELECT "ObjectID"
		FROM "患者台帳"
		WHERE "患者ID" = $patient_id AND "Superseded" IS NULL
SQL;
    $r = mx_db_fetch_single($db, $stmt);
    mx_force_claim_on_date($db, $r['ObjectID'], $date);
  } else {
    mx_record_claim_request($db, $patient_id, $app, $objectid, $date);
  }
	if (!$urgent && (mx_today_string() != $date)) {
		print "<!-- $date is not ";
		print mx_today_string();
		print " so not kicking -->\n";
		return;
	}

	$date = mx_db_sql_quote($date);

	print "<!-- kicking P $patient_id for $date -->\n";

	$patient_id = mx_db_sql_quote(trim($patient_id));
	$stmt = <<<SQL
		INSERT INTO claim_request
		(patient, date_since, date_until, result_flag)
		SELECT "ObjectID", $date, $date, $urgent
		FROM "患者台帳"
		WHERE "患者ID" = $patient_id AND "Superseded" IS NULL
SQL;
	return pg_query($db, $stmt);
}

function mx_kick_claim_if_by_poid($db, $patient_objectid, $date, $urgent=0, $app=NULL, $objectid=NULL)
{
  if($app == 'force')
    mx_force_claim_on_date($db, $patient_objectid, $date);
  else
    mx_record_claim_request($db, $app, $objectid, $date);
	if (!$urgent && (mx_today_string() != $date)) {
		print "<!-- $date is not ";
		print mx_today_string();
		print " so not kicking -->\n";
		return;
	}

	$date = mx_db_sql_quote($date);

	print "<!-- kicking O $patient_objectid for $date -->\n";

	$patient_objectid = mx_db_sql_quote($patient_objectid);
	$stmt = <<<SQL
		INSERT INTO claim_request
		(patient, date_since, date_until, result_flag)
		values ($patient_objectid, $date, $date, $urgent)
SQL;
	return pg_query($db, $stmt);
}

////////////////////////////////////////////////////////////////
// Modal applications
function mx_encounter_mode($u)
{
	global $_mx_default_encounter_mode;

	$db = mx_db_connect();
	$stmt = "SELECT modevalue FROM mx_usermode WHERE
		userid = $u AND modeclass = '外来診療'";
	$it = mx_db_fetch_single($db, $stmt);
	if (is_array($it)) {
		if ($it['modevalue'] == '入院診療')
			return 'I'; // inpatient
		else if ($it['modevalue'] == '外来診療')
			return 'O'; // outpatient
	}
	if (trim($_mx_default_encounter_mode) != '') {
		mx_set_encounter_mode($u, $_mx_default_encounter_mode);
		return $_mx_default_encounter_mode;
	}
	return NULL;
}

function mx_set_encounter_mode($u, $io)
{
//0315-2014
if (is_null($u)){return NULL;}
//0315-2014	
$db = mx_db_connect();
	$k = '外来診療';
	$v = 'NULL';
	if (!is_null($io)) {
		if ($io == 'I')
			$v = "'入院診療'";
		else if ($io == 'O')
			$v = "'$k'";
	}
	$stmt = "SELECT * FROM mx_set_usermode($u, '外来診療', $v)";
	return mx_db_fetch_single($db, $stmt);
}

function mx_put_qbe_state($name, $value)
{
	global $mx_authenticate_current_user;
	global $_mx_allow_persistent_qbe;

	if (!$_mx_allow_persistent_qbe)
		return;

	$db = mx_db_connect();
	$u = $mx_authenticate_current_user;
	$name = 'QBE_' . $name;
	$value = bin2hex(serialize($value));
	$stmt = "SELECT * FROM mx_set_usermode($u, '$name', '$value')";
	return mx_db_fetch_single($db, $stmt);
}

function mx_get_qbe_state($name)
{
	global $mx_authenticate_current_user;
	global $_mx_allow_persistent_qbe;

	if (!$_mx_allow_persistent_qbe)
		return NULL;
	$db = mx_db_connect();
	$u = $mx_authenticate_current_user;
	$name = 'QBE_' . $name;
	$stmt = "SELECT modevalue FROM mx_usermode WHERE
		userid = $u AND modeclass = '$name'";

	$it = mx_db_fetch_single($db, $stmt);
	if (!is_array($it))
		return NULL;
	$value = unserialize(mx_form_decode_name($it['modevalue']));
	return $value;
}

function mx_note_checkin_list_use($u, $o, $p)
{
	$db = mx_db_connect();
	$v = "$o,$p";
	$stmt = "SELECT * FROM mx_set_usermode($u, '受付患者表ID', '$v')";
	return mx_db_fetch_single($db, $stmt);
}

function mx_finish_encounter_drop_checkin($u, $p)
{
	$db = mx_db_connect();
	$stmt = "SELECT modevalue FROM mx_usermode WHERE
		userid = $u AND modeclass = '受付患者表ID'";
	$it = mx_db_fetch_single($db, $stmt);
	if (!is_array($it))
		return;
	$m = array();
	if (!preg_match('/^(\d+),(\d+)$/', $it['modevalue'], &$m))
		return;
	$objectid = $m[1];
	$patientid = $m[2];
	if ($p != $patientid)
		return;
	$stmt = 'UPDATE "受付患者表" SET "ステータス" = 1, ' .
		'"診療終了時刻" = now() ' .
		'WHERE "ObjectID" = ' . mx_db_sql_quote($objectid);
	pg_query($db, $stmt);
	mx_note_checkin_list_use($u, '', '');
}

function mx_get_current_reception_info($u, $p)
{
	$db = mx_db_connect();
	$stmt = "SELECT modevalue FROM mx_usermode WHERE
		userid = $u AND modeclass = '受付患者表ID'";
	$it = mx_db_fetch_single($db, $stmt);
	if (!is_array($it))
		return;
	if (!preg_match('/^(\d+),(\d+)$/', $it['modevalue'], &$m))
		return;
	$objectid = $m[1];
	$patientid = $m[2];
	$stmt = 'SELECT * FROM "受付患者表" WHERE "ObjectID" = ' .
		mx_db_sql_quote($objectid);
	$it = mx_db_fetch_single($db, $stmt);
	if (!is_array($it) || $it["患者"] != $p)
		return;
	return $it;
}

function is_encounter_state_application($path)
{
	if ($path == 'u/everybody/finish-encounter.php')
		return 1;
	if ($path == 'u/everybody/interrupt-encounter.php')
		return 1;
	return 0;
}

$_mx_application_id = array();
function mx_get_application_id($application)
{
	global $_mx_application_id;

	if (!array_key_exists($application, $_mx_application_id)) {
		$db = mx_db_connect();
		$stmt = <<<SQL
SELECT "ObjectID" FROM mx_application
WHERE "Superseded" IS NULL AND path = '$application'
SQL;
		$it = mx_db_fetch_single($db, $stmt);
		if ($it)
			$_mx_application_id[$application] = $it['ObjectID'];
		else
			$_mx_application_id[$application] = NULL;
	}
	return $_mx_application_id[$application];
}

function mx_get_usermode($u, $cls)
{
	$db = mx_db_connect();
	$qcls = mx_db_sql_quote($cls);
	$stmt = "SELECT modevalue FROM mx_usermode WHERE
		userid = $u AND modeclass = $qcls";
	$it = mx_db_fetch_single($db, $stmt);
	if (is_array($it))
	  return $it['modevalue'];
	return NULL;
}

function mx_set_usermode($u, $cls, $value)
{
	$db = mx_db_connect();
	$qcls = mx_db_sql_quote($cls);
	$qvalue = mx_db_sql_quote($value);
	pg_query($db, 'begin');
	$stmt = "DELETE FROM mx_usermode WHERE userid=$u AND modeclass = $qcls";
	pg_query($db, $stmt);
	$stmt = "INSERT INTO mx_usermode (userid, modeclass, modevalue)
                 VALUES ($u, $qcls, $qvalue)";
	pg_query($db, $stmt);
	pg_query($db, 'commit');
}


////////////////////////////////////////////////////////////////
// Enums backed by the database

function mx_dbenum_apptcandidatedr() {
	$stmt = <<<SQL
SELECT E."姓", E."名", (E."姓" || ' ' || E."名") AS "姓名",
       M.modality AS "予約先"
FROM modalities_to_medex_employee AS M
JOIN "職員台帳" AS E
ON E."ObjectID" = M.employee
SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array('' => '');
	if ($them) {
		foreach ($them as $e)
			$result['=' . $e['予約先']] = $e['姓名'];
	}
	return $result;
}

function mx_dbenum_patientgroup() {
	$stmt = <<<SQL
SELECT "グループ", "ラベル" FROM "患者グループ"
WHERE ("不使用" IS NULL OR "不使用" != 'Y') AND "Superseded" IS NULL
ORDER BY "表示順位"
SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array('' => '');
	if ($them) {
		foreach ($them as $e)
			$result[$e['グループ']] = $e['ラベル'];
	}
	return $result;
}

function mx_dbenum_primarydoctor() {
	$stmt = <<<SQL
SELECT DISTINCT ( E."姓" || E."名" ) AS "姓名", E."ObjectID" AS "職員"
FROM "担当役割" AS AR
JOIN "患者担当職員" AS A
ON   A."Superseded" IS NULL AND AR."Superseded" IS NULL AND AR."担当役割" = '主治医'
JOIN "患者担当職員データ" AS AD
ON   AD."患者担当職員" = A."ObjectID" AND AD."担当役割" = AR."ObjectID"
JOIN "職員台帳" AS E
ON   E."Superseded" IS NULL AND E."ObjectID" = AD."職員"
SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array('' => '');
	if ($them) {
		foreach ($them as $e)
			$result['=' . $e['職員']] = $e['姓名'];
	}
	return $result;
}
//0405-2012
function mx_dbenum_emp() {
	$stmt = <<<SQL
SELECT DISTINCT ( E."姓" || E."名" ) AS "姓名", E."ObjectID" AS "職員"

from "職員台帳" AS E
where   E."Superseded" IS NULL 
SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array('' => '');
	if ($them) {
		foreach ($them as $e)
			$result[$e['職員']] = $e['姓名'];
	}
	return $result;
}
function mx_dbenum_shiji() {
	$stmt = <<<SQL
SELECT DISTINCT ( E."姓" || E."名" ) AS "姓名", E."userid" AS userid

from "職員台帳" AS E
where   E."Superseded" IS NULL 
SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array('' => '');
	if ($them) {
		foreach ($them as $e)
			$result[$e['userid']] = $e['姓名'];
	}
	return $result;
}

function mx_dbenum_doctordepartment() {
	$stmt = <<<SQL
SELECT DISTINCT (CASE WHEN
		 ((D."小分類" IS NULL) OR D."小分類" = '')
		 THEN D."中分類2"
		 ELSE D."小分類"
		 END) AS "科目名",
		D."ObjectID" AS "科目"
FROM "部署一覧表" AS D
JOIN "職員台帳" AS E
  ON E."Superseded" IS NULL AND E."部署" = D."ObjectID"
JOIN modalities_to_medex_employee AS ME
  ON ME.employee = E."ObjectID"
JOIN modalities AS M
  ON M.id = ME.modality
SQL;
	$db = mx_db_connect();
	$them = pg_fetch_all(pg_query($db, $stmt));
	$result = array('' => '');
	if ($them) {
		foreach ($them as $e)
			$result['=' . $e['科目']] = $e['科目名'];
	}
	return $result;
}

function mx_dbenum_patientmark($prefix='') {
	$it = mx_dbenum("患者", "患者マーク");
	if (is_null($it))
		return array('' => '', '〇' => '〇');
	$it = explode("\n", $it['選択肢']);
	$result = array('' => '');

	foreach ($it as $item)
		$result[$prefix . $item] = $item;
	return $result;
}

function mx_dbenum($group, $item) {
	global $_mx_dbenum_cache;

	if (is_null($_mx_dbenum_cache))
		$_mx_dbenum_cache = array();

	if (!array_key_exists($group, $_mx_dbenum_cache)) {
		$g = mx_db_sql_quote($group);
		$stmt = <<<SQL
SELECT "グループ", "名称", "Multi", "選択肢"
FROM "列挙型"
WHERE "Superseded" IS NULL AND "グループ" = $g
SQL;
		$db = mx_db_connect();
		$them = pg_fetch_all(pg_query($db, $stmt));
		if (!$them) {
			print "$stmt\n";
			return NULL;
		}
		$r = array();
		foreach ($them as $e) {
			$r[$e['名称']] = $e;
		}
		$_mx_dbenum_cache[$group] = $r;
	}
	$r = $_mx_dbenum_cache[$group];
	if (!array_key_exists($item, $r))
		return NULL;
	return $r[$item];
}

function mx_drawapp_image_list() {
	global $__cached_drawapp_image_list;
	if (is_null($__cached_drawapp_image_list)) {
		$db = mx_db_connect();
		$stmt = <<<SQL
SELECT C.name AS cname, I.filename as fname, I.extdocument as xname
FROM drawapp_category AS C
JOIN drawapp_image AS I
  ON I.category = C."ObjectID"
WHERE I."Superseded" IS NULL AND
      C."Superseded" IS NULL AND
      0 <= C.sortorder AND
      0 <= I.sortorder
ORDER BY C.sortorder, I.sortorder
SQL;
		$them = pg_fetch_all(pg_query($db, $stmt));

		$cc = array();
		foreach ($them as $d) {
			$cname = $d['cname'];
			$fname = $d['fname'];
			if ($d['xname'])
				$fname = ("/blobmedia.php/drawapp/" .
					  $d['xname']);
			if (!array_key_exists($cname, $cc))
				$cc[$cname] = array();
			$cc[$cname][] = $fname;
		}
		$__cached_drawapp_image_list = $cc;
	}
	return $__cached_drawapp_image_list;
}

?>
