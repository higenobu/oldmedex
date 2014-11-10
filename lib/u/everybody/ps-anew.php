<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ps-prep.php';

$_lib_u_everybody_ps_eclass = array
(
	'�缣��' => '�缣��',
	'���ô����' => '���ô����',
	'ô��PT' => 'PT',
	'ô��OT' => 'OT',
	'ô��ST' => 'ST',
	'ô���Ǹ��' => '�Ǹ��',
	'ô��SW' => 'SW',
);

$_lib_u_everybody_ps_theo_cols = array
(
"�ռ��㳲", "�ռ��㳲������",
"����", "���򥳥���", // �ָ�������
"�����㳲", "�����㳲������", // �ֵ����ϡ�
"ɽ�ߴ��о㳲", "ɽ�ߴ��о㳲������",
"�������о㳲", "�������о㳲������",
"�ݿ���ǽ�㳲", "�ݿ���ǽ�㳲������",
"��Ǣ��ǽ�㳲", "��Ǣ��ǽ�㳲������",
"���ص�ǽ�㳲", "���ص�ǽ�㳲������",
"�Ƶ۽۴Ĵﵡǽ�㳲", "�Ƶ۽۴Ĵﵡǽ�㳲������",
"�����㳲", "�����㳲������",
"�����㲼", "�����㲼������",
"����", "���ϥ�����",
"�ˤ�", "�ˤߥ�����",
"Ⱦ¦�����̵��", "Ⱦ¦�����̵�륳����", 
"��վ㳲", "��վ㳲������",

);

$_lib_u_everybody_ps_fim_cols = array
(

"����_P", "����_C", "����_P", "����_C", "����_P", "����_C",
"���ᡦ��Ⱦ��_P", "���ᡦ��Ⱦ��_C", "���ᡦ��Ⱦ��_P",
"���ᡦ��Ⱦ��_C", "�ȥ���ư��_P", "�ȥ���ư��_C", "��Ǣ����_P",
"��Ǣ����_C", "��������_P", "��������_C",

"�٥åɡ��ػҡ��ְػ�_P", "�٥åɡ��ػҡ��ְػ�_C",
"�ȥ���_P", "�ȥ���_C", "���奷��_P",
"���奷��_C",

"�ְػ�_P", "�ְػ�_C",
"���_P", "���_C",

"����_P", "����_C", "��ư����",
"����_P", "����_C", "ɽ��_P", "ɽ��_C",
"�Ҳ�Ū��ή_P", "�Ҳ�Ū��ή_C",
"������_P", "������_C",
"����_P", "����_C"

);

$_lib_u_everybody_ps_theo_stmt = '';
foreach ($_lib_u_everybody_ps_theo_cols as $c) {
	$q = mx_db_sql_quote_name($c);
	$_lib_u_everybody_ps_theo_stmt .= ",\n  T." . $q . ' AS ' . $q;
}

$_lib_u_everybody_ps_fim_stmt = '';
foreach ($_lib_u_everybody_ps_fim_cols as $c) {
	$q = mx_db_sql_quote_name($c);
	$_lib_u_everybody_ps_fim_stmt .= ",\n  F." . $q . ' AS ' . $q;
}

function __lib_u_everybody_ps_anew_fim_fetch($t, $pt, &$db, &$ret, $cnt=1) {
	global $_lib_u_everybody_ps_fim_stmt;
	$stmt = '
SELECT NULL AS "Ignore"' . $_lib_u_everybody_ps_fim_stmt . '
FROM "������Ģ" as P
JOIN "FIMɾ��ɽ" AS F
ON P."ObjectID" = F."����" AND F."Superseded" IS NULL
WHERE
     F."ɾ����" = \'' . $t . '\' AND
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt) . '
ORDER BY F."����" DESC
LIMIT ' . $cnt;
	// print "<!--\nstmt = $stmt;\n-->\n";
	$rarray = array();
	if ($cnt == 1) {
		$result = mx_db_fetch_single($db, $stmt);
		if ($result) {
			$rarray = array($result);
		}
	}
	else {
		$sth = pg_query($db, $stmt);
		$rarray = pg_fetch_all($sth);
	}
	if (count($rarray)) {
		if (count($rarray) < $cnt)
			$cnt = count($rarray);
		for ($i = 0; $i < $cnt; $i++) {
			$sfx = ($i ? "_$i" : "");
			$result = $rarray[$i];
			if (!is_array($result))
				continue;
			foreach ($result as $col => $val) {
				if (substr($col, -2, 2) != '_P') {
					if ($t == 'N') {
						$ret[$col . $sfx] = $val;
						continue;
					}
				}
				// all points.
				if ($t != 'N') 
					$col = substr($col, 0, -2) . "_TP";
				$ret[$col. $sfx] = $val;
			}
		}
	}
}


function __lib_u_everybody_ps_anew($pt, &$ret) {
	global $_lib_u_everybody_ps_eclass,
		$_lib_u_everybody_ps_theo_stmt,
		$_lib_u_everybody_ps_fim_stmt,
		$_lib_u_everybody_ps_prep_cols;
	$db = mx_db_connect();

	$stmt = '
SELECT 
   (P."��" || \' \' || P."̾") as "��̾",
   "����", "��ǯ����", "������",
   extract(year from age(timestamp \'' . $ret['����'] . '\',  P."��ǯ����"))
   AS "ǯ��"
FROM "������Ģ" as P
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt);

	$result = mx_db_fetch_single($db, $stmt);
	foreach ($result as $col => $val) {
		$ret[$col] = $val;
	}

	$stmt = '
SELECT
   AR."ô�����", E."ObjectID" as "����",
   (E."��" || \' \' || E."̾") as "̾"
FROM "������Ģ" as P
JOIN "����ô������" as A
ON   P."Superseded" IS NULL AND P."ObjectID" = A."����"
LEFT JOIN "ô�����" as AR
ON   AR."Superseded" IS NULL
LEFT JOIN "����ô�������ǡ���" as A1
ON   A1."����ô������" = A."ObjectID" AND
     AR."ObjectID" = A1."ô�����"
LEFT JOIN "������Ģ" as E
ON   E."Superseded" IS NULL AND
     A1."����" = E."ObjectID"
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt);

	$st = pg_query($db, $stmt);
	$result = pg_fetch_all($st);
	foreach ($result as $data) {
		$role = $data['ô�����'];
		if (array_key_exists($role, $_lib_u_everybody_ps_eclass)) {
			$n = $_lib_u_everybody_ps_eclass[$role];
			$ret[$n] = $data['����'];
			$ret["$n" . '̾'] = $data['̾'];
		}
	}

	$stmt = '
SELECT 
    I."�������輫Ω��" AS "�������輫Ω��",
    I."������Ϸ�ͤ��������輫Ω��Ƚ����" AS
      "������Ϸ�ͤ��������輫Ω��Ƚ����",
    I."������û����ɸ" AS "������û����ɸ",
    I."���������ץ���" AS "���������ץ���"
FROM "������Ģ" as P
JOIN "�������輫Ω�ٴ���ɽ" as I
ON P."ObjectID" = I."����" AND I."Superseded" IS NULL
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt) . '
ORDER BY I."����" DESC
LIMIT 1';

	print "<!--\nstmt = $stmt;\n-->\n"; 
	$result = mx_db_fetch_single($db, $stmt);
	if ($result) {
	    foreach ($result as $col => $val) {
		    $ret[$col] = $val;
	    }
	}

	$stmt = '
SELECT NULL AS "Ignore"' . $_lib_u_everybody_ps_theo_stmt . '
FROM "������Ģ" as P
JOIN "��Ͻ����" AS T
ON P."ObjectID" = T."����" AND T."Superseded" IS NULL
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt) . '
ORDER BY T."������" DESC
LIMIT 1';

	print "<!--\nstmt = $stmt;\n-->\n"; 
	$result = mx_db_fetch_single($db, $stmt);
	if ($result) {
	    foreach ($result as $col => $val) {
		    $ret[$col] = $val;
	    }
	}

	__lib_u_everybody_ps_anew_fim_fetch('N', $pt, &$db, &$ret);
	__lib_u_everybody_ps_anew_fim_fetch('T', $pt, &$db, &$ret);

	$stmt = '
SELECT *
FROM "��Ϸײ�����"
WHERE "ObjectID" IN
(
    SELECT max("ObjectID")
    FROM "��Ϸײ�����"
    WHERE "����" = ' . mx_db_sql_quote($pt). ' AND "Superseded" IS NULL
    GROUP BY "����̾"
)';
	print "<!--\nstmt = $stmt;\n-->\n"; 
	$st = pg_query($db, $stmt);
	$result = pg_fetch_all($st);
	foreach ($result as $data) {
		$ec = $data['����̾'];
		$ret[$ec . '�ˤ�륳����'] = $data['������'];
		if (!array_key_exists($ec, $_lib_u_everybody_ps_prep_cols))
			continue;
		foreach ($_lib_u_everybody_ps_prep_cols[$ec] as $col) {
			if (!array_key_exists($col, $data))
				continue;
			if (is_null($data[$col]))
				continue;
			$ret[$col] = $data[$col];
		}
	}
}

?>
