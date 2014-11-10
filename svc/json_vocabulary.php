<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// Unfortunately these are not symmetric
function is_really_array($data)
{
	$count = count($data);

	for ($i = 0; $i < $count; $i++) {
		if (!array_key_exists($i, $data))
			return 0;
	}
	return 1;
}

function array_encode($data)
{
	$count = count($data);

	$encoded = '[';
	$first = 1;
	for ($i = 0; $i < $count; $i++) {
		if (!$first) {
			$encoded .= ",\n";
		}
		$first = 0;
		$encoded .= data_encode($data[$i]);
	}
	$encoded .= ']';
	return $encoded;
}

function data_encode($data)
{
	$encoded = '';

	if (is_array($data)) {
		if (is_really_array($data))
			return array_encode($data);
		$encoded = '{';
		$first = 1;
		foreach ($data as $k => $v) {
			if (!$first) {
				$encoded .= ",\n";
			}
			$first = 0;
			$encoded .= data_encode($k);
			$encoded .= ':';
			$encoded .= data_encode($v);
		}
		$encoded .= "}";
	} else {
		$encoded = sprintf("'%s'", addslashes($data));
	}
	return $encoded;
}

function data_decode(&$array, $basekey)
{
	$i = 0;
	$result = array();
	while (1) {
		$key = sprintf("%s%d", $basekey, $i++);
		if (!array_key_exists($key, $array))
			break;
		$result[] = $array[$key];
	}
	return $result;
}

function get_vocab($db, $vocab, $depend)
{

	$depend = trim($depend);
	if ($depend != "")
		$depend = pg_escape_string($depend);

	$stmt = sprintf('
SELECT W."��緲" AS found
FROM  "��緲" AS W
JOIN  "����" AS V
ON    V."�Ի���" != \'Y\' AND
      W."�Ի���" != \'Y\' AND
      W."����" = V."ObjectID" AND
      W."Superseded" IS NULL
WHERE
      V."����" = %s AND
      ( V."��¸����" IS NULL OR
	W."��¸���" IS NULL OR
        W."��¸���" LIKE \'%s\' )',
			mx_db_sql_quote($vocab),
			'%\n' . $depend . '\n%');

	$result = pg_fetch_all(pg_query($db, $stmt));
	$retval = array();

	if (!$result)
		return $retval;
	foreach ($result as $tuple) {
		$t = $tuple['found'];
		foreach (explode("\n", $t) as $a) {
			$a = trim($a);
			if ($a == '')
				continue;
			if (array_search($a, $retval) === FALSE)
				$retval[] = $a;
		}
	}
	return $retval;
}

function show_vocab()
{

	$vocab_list = data_decode($_REQUEST, 'vocab');
	$select_list = data_decode($_REQUEST, 'select');

	$db = mx_db_connect();
	$retval = array();

	$count = count($vocab_list);
	$vocab = array();
	$w_from_v = array();
	for ($i = 0; $i < $count; $i++) {
		$v = $vocab_list[$i];
		$w_from_v[$v] = trim($select_list[$i]);
		$vocab[] = mx_db_sql_quote($v);
	}
	$vocab = implode(",\n", $vocab);

	$depend = array();
	$label = array();
	$stmt = sprintf('
		SELECT V."����", V."��٥�", D."����" AS "��¸����"
		FROM   "����" AS V
		LEFT JOIN   "����" AS D
		ON     V."��¸����" = D."ObjectID" AND
		       V."�Ի���" != \'Y\'
		WHERE  V."Superseded" IS NULL AND
		V."����" IN (%s)', $vocab);

	$dependency = pg_fetch_all(pg_query($db, $stmt));
	if ($dependency) {
		foreach ($dependency as $t) {
			if (!is_null($t['��¸����'])) {
				$depend[$t['����']] = $t['��¸����'];
			}
			$label[$t['����']] = $t['��٥�'];
		}
	}

	for ($i = 0; $i < $count; $i++) {
		$vocab = $vocab_list[$i];
		$select = $select_list[$i];
		$a = array();
		$a['select'] = $select;

		if (array_key_exists($vocab, $depend)) {
			$d = $w_from_v[$depend[$vocab]];
		} else {
			$d = NULL;
		}
		$a['choice'] = get_vocab($db, $vocab, $d);
		$a['name'] = $vocab;
		$a['label'] = $label[$vocab];
		$a['order'] = $i;
		$retval[] = $a;
	}

	print data_encode($retval);

}
show_vocab();
?>
