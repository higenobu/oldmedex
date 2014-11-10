<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

/*
 * For now we will not allow wordlist dependency to be edited
 */
function __lib_u_manage_vocab_config(&$cfg) {
	$stmt = <<<SQL
SELECT "ObjectID", "CreatedBy", "¸ì×Ã", "¥é¥Ù¥ë", "°ÍÂ¸¸ì×Ã"
FROM "¸ì×Ã"
WHERE "ÉÔ»ÈÍÑ" = 'N' AND "Superseded" IS NULL
SQL;
	$_cfg = array('TABLE' => '¸ì×Ã',
		      'COLS' => array('¸ì×Ã', '¥é¥Ù¥ë'),
		      'LCOLS' => array(
			      array('Column' => '¸ì×Ã',
				    'Label' => 'Äê·¿Ê¸¶èÊ¬'),
			      array('Column' => '¥é¥Ù¥ë',
				    'Label' => 'Äê·¿Ê¸¥é¥Ù¥ë')),
		      'LIST_IDS' => array('ObjectID', '¸ì×Ã', '°ÍÂ¸¸ì×Ã'),
		      'STMT' => $stmt);

	foreach ($_cfg as $k => $v) {
		$cfg[$k] = $v;
	}
}

class list_of_vocab extends list_of_simple_objects {
	function list_of_vocab($prefix, $config=NULL) {
		__lib_u_manage_vocab_config(&$config);
		list_of_simple_objects::list_of_simple_objects($prefix, $config);
	}
}

function __lib_u_manage_vocab_words_config(&$cfg, $vocab) {
	$v = $vocab[0];
	$d = $vocab[2];
	$stmt = <<<SQL
SELECT "ObjectID", "CreatedBy", "¸ì¶ç·²", "°ÍÂ¸¸ì¶ç"
FROM "¸ì¶ç·²"
WHERE "ÉÔ»ÈÍÑ" = 'N' AND "Superseded" IS NULL AND "¸ì×Ã" = $v
SQL;
	if ($d) {
		$_cfg = array('TABLE' => '¸ì¶ç·²',
			      'COLS' => array('°ÍÂ¸¸ì¶ç', '¸ì¶ç·²'),
			      'LCOLS' => array(
				      array('Column' => '°ÍÂ¸¸ì¶ç',
					    'Label' => 'Äê·¿Ê¸°ÍÂ¸¸ì',
					    'Draw' => 'dotted'),
				      array('Column' => '¸ì¶ç·²',
					    'Label' => 'Äê·¿Ê¸ÆâÍÆ',
					    'Draw' => 'dotted')),
			      'LIST_IDS' => array('ObjectID', '¸ì¶ç·²',
						  '°ÍÂ¸¸ì¶ç'),
			      'STMT' => $stmt);
	} else {
		$_cfg = array('TABLE' => '¸ì¶ç·²',
			      'COLS' => array('¸ì¶ç·²'),
			      'LCOLS' => array(array('Column' => '¸ì¶ç·²',
						     'Label' => 'Äê·¿Ê¸ÆâÍÆ',
						     'Draw' => 'dotted')),
			      'LIST_IDS' => array('ObjectID', '¸ì¶ç·²',
						  '°ÍÂ¸¸ì¶ç'),
			      'STMT' => $stmt);
	}

	foreach ($_cfg as $k => $v) {
		$cfg[$k] = $v;
	}
}

class list_of_vocab_words extends list_of_simple_objects {
	var $debug = 1;

	function list_of_vocab_words($prefix, $vocab, $config=NULL) {
		__lib_u_manage_vocab_words_config(&$config, $vocab);
		list_of_simple_objects::list_of_simple_objects($prefix, $config);
	}

	function dx_dotted($desc, $value, $row) {
		$this->_dx_textish(str_replace("\n", "¡¦", trim($value)));
	}

}

function __lib_u_manage_vocabulary_consolidate_wc($w) {
	if (is_null($w))
		return '';
	$data = array();
	foreach (explode("\n", $w) as $e) {
		if (trim($e) == '')
			continue;
		$data[] = $e;
	}
	if (count($data) == 0)
		return '';
	$data = array_unique($data);
	sort(&$data);
	return "\n" . implode("\n", $data) . "\n";
}

function __lib_u_manage_vocabulary_consolidate_1($db, $v) {

	$stmt = <<<SQL
SELECT "ObjectID", "¸ì¶ç·²", "°ÍÂ¸¸ì¶ç"
FROM "¸ì¶ç·²"
WHERE "ÉÔ»ÈÍÑ" = 'N' AND "Superseded" IS NULL AND "¸ì×Ã" = $v
ORDER BY "°ÍÂ¸¸ì¶ç"
SQL;
	$them = pg_fetch_all(pg_query($db, $stmt));
	if (!$them)
		return;

	$data = array();
	$depends = array();
	$oid = array();
	foreach ($them as $e) {
		$d = __lib_u_manage_vocabulary_consolidate_wc($e['°ÍÂ¸¸ì¶ç']);
		if (!array_key_exists($d, $data))
			$data[$d] = "\n";
		$data[$d] .= "\n" . $e['¸ì¶ç·²'];
		if (!array_key_exists($d, $oid)) {
			$oid[$d] = array();
		}
		$oid[$d][] = $e['ObjectID'];
	}

	foreach ($data as $depend => $words) {
		if (count($oid[$depend]) < 2)
			continue;
		$w = __lib_u_manage_vocabulary_consolidate_wc($words);
		$first = NULL;
		foreach ($oid[$depend] as $o) {
			$stmt = <<<SQL
UPDATE "¸ì¶ç·²"
SET "ÉÔ»ÈÍÑ" = 'Y'
WHERE "ObjectID" = $o
SQL;
			if (is_null($first))
				$first = $o;
			else {
				print "<!-- $stmt -->";
				pg_query($db, $stmt);
			}
		}
		$w = mx_db_sql_quote($w);
		$stmt = <<<SQL
UPDATE "¸ì¶ç·²"
SET "¸ì¶ç·²" = $w
WHERE "ObjectID" = $first
SQL;
		print "<!-- $stmt -->";
		pg_query($db, $stmt);
	}
}

function __lib_u_manage_vocabulary_consolidate() {
	$db = mx_db_connect();
	$stmt = <<<SQL
SELECT "ObjectID" FROM "¸ì×Ã" WHERE "Superseded" IS NULL
SQL;
	$them = pg_fetch_all(pg_query($db, $stmt));
	if (!$them)
		return;
	foreach ($them as $e) {
		$v = $e['ObjectID'];
		__lib_u_manage_vocabulary_consolidate_1($db, $v);
	}
}

?>
