<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

/*
 * For now we will not allow wordlist dependency to be edited
 */
function __lib_u_manage_vocab_config(&$cfg) {
	$stmt = <<<SQL
SELECT "ObjectID", "CreatedBy", "����", "��٥�", "��¸����"
FROM "����"
WHERE "�Ի���" = 'N' AND "Superseded" IS NULL
SQL;
	$_cfg = array('TABLE' => '����',
		      'COLS' => array('����', '��٥�'),
		      'LCOLS' => array(
			      array('Column' => '����',
				    'Label' => '�귿ʸ��ʬ'),
			      array('Column' => '��٥�',
				    'Label' => '�귿ʸ��٥�')),
		      'LIST_IDS' => array('ObjectID', '����', '��¸����'),
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
SELECT "ObjectID", "CreatedBy", "��緲", "��¸���"
FROM "��緲"
WHERE "�Ի���" = 'N' AND "Superseded" IS NULL AND "����" = $v
SQL;
	if ($d) {
		$_cfg = array('TABLE' => '��緲',
			      'COLS' => array('��¸���', '��緲'),
			      'LCOLS' => array(
				      array('Column' => '��¸���',
					    'Label' => '�귿ʸ��¸��',
					    'Draw' => 'dotted'),
				      array('Column' => '��緲',
					    'Label' => '�귿ʸ����',
					    'Draw' => 'dotted')),
			      'LIST_IDS' => array('ObjectID', '��緲',
						  '��¸���'),
			      'STMT' => $stmt);
	} else {
		$_cfg = array('TABLE' => '��緲',
			      'COLS' => array('��緲'),
			      'LCOLS' => array(array('Column' => '��緲',
						     'Label' => '�귿ʸ����',
						     'Draw' => 'dotted')),
			      'LIST_IDS' => array('ObjectID', '��緲',
						  '��¸���'),
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
		$this->_dx_textish(str_replace("\n", "��", trim($value)));
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
SELECT "ObjectID", "��緲", "��¸���"
FROM "��緲"
WHERE "�Ի���" = 'N' AND "Superseded" IS NULL AND "����" = $v
ORDER BY "��¸���"
SQL;
	$them = pg_fetch_all(pg_query($db, $stmt));
	if (!$them)
		return;

	$data = array();
	$depends = array();
	$oid = array();
	foreach ($them as $e) {
		$d = __lib_u_manage_vocabulary_consolidate_wc($e['��¸���']);
		if (!array_key_exists($d, $data))
			$data[$d] = "\n";
		$data[$d] .= "\n" . $e['��緲'];
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
UPDATE "��緲"
SET "�Ի���" = 'Y'
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
UPDATE "��緲"
SET "��緲" = $w
WHERE "ObjectID" = $first
SQL;
		print "<!-- $stmt -->";
		pg_query($db, $stmt);
	}
}

function __lib_u_manage_vocabulary_consolidate() {
	$db = mx_db_connect();
	$stmt = <<<SQL
SELECT "ObjectID" FROM "����" WHERE "Superseded" IS NULL
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
