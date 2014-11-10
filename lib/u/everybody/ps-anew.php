<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ps-prep.php';

$_lib_u_everybody_ps_eclass = array
(
	'主治医' => '主治医',
	'リハ担当医' => 'リハ担当医',
	'担当PT' => 'PT',
	'担当OT' => 'OT',
	'担当ST' => 'ST',
	'担当看護師' => '看護師',
	'担当SW' => 'SW',
);

$_lib_u_everybody_ps_theo_cols = array
(
"意識障害", "意識障害コメント",
"痴呆", "痴呆コメント", // 「見当識」
"記憶障害", "記憶障害コメント", // 「記銘力」
"表在感覚障害", "表在感覚障害コメント",
"深部感覚障害", "深部感覚障害コメント",
"摂食機能障害", "摂食機能障害コメント",
"排尿機能障害", "排尿機能障害コメント",
"排便機能障害", "排便機能障害コメント",
"呼吸循環器機能障害", "呼吸循環器機能障害コメント",
"構音障害", "構音障害コメント",
"筋力低下", "筋力低下コメント",
"褥創", "褥創コメント",
"痛み", "痛みコメント",
"半側視空間無視", "半側視空間無視コメント", 
"注意障害", "注意障害コメント",

);

$_lib_u_everybody_ps_fim_cols = array
(

"食事_P", "食事_C", "整容_P", "整容_C", "清拭_P", "清拭_C",
"更衣・上半身_P", "更衣・上半身_C", "更衣・下半身_P",
"更衣・下半身_C", "トイレ動作_P", "トイレ動作_C", "排尿管理_P",
"排尿管理_C", "排泄管理_P", "排泄管理_C",

"ベッド・椅子・車椅子_P", "ベッド・椅子・車椅子_C",
"トイレ_P", "トイレ_C", "浴槽シャワー_P",
"浴槽シャワー_C",

"車椅子_P", "車椅子_C",
"歩行_P", "歩行_C",

"階段_P", "階段_C", "移動手段",
"理解_P", "理解_C", "表出_P", "表出_C",
"社会的交流_P", "社会的交流_C",
"問題解決_P", "問題解決_C",
"記憶_P", "記憶_C"

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
FROM "患者台帳" as P
JOIN "FIM評価表" AS F
ON P."ObjectID" = F."患者" AND F."Superseded" IS NULL
WHERE
     F."評価種" = \'' . $t . '\' AND
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt) . '
ORDER BY F."日付" DESC
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
   (P."姓" || \' \' || P."名") as "氏名",
   "性別", "生年月日", "利き手",
   extract(year from age(timestamp \'' . $ret['日付'] . '\',  P."生年月日"))
   AS "年齢"
FROM "患者台帳" as P
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt);

	$result = mx_db_fetch_single($db, $stmt);
	foreach ($result as $col => $val) {
		$ret[$col] = $val;
	}

	$stmt = '
SELECT
   AR."担当役割", E."ObjectID" as "職員",
   (E."姓" || \' \' || E."名") as "名"
FROM "患者台帳" as P
JOIN "患者担当職員" as A
ON   P."Superseded" IS NULL AND P."ObjectID" = A."患者"
LEFT JOIN "担当役割" as AR
ON   AR."Superseded" IS NULL
LEFT JOIN "患者担当職員データ" as A1
ON   A1."患者担当職員" = A."ObjectID" AND
     AR."ObjectID" = A1."担当役割"
LEFT JOIN "職員台帳" as E
ON   E."Superseded" IS NULL AND
     A1."職員" = E."ObjectID"
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt);

	$st = pg_query($db, $stmt);
	$result = pg_fetch_all($st);
	foreach ($result as $data) {
		$role = $data['担当役割'];
		if (array_key_exists($role, $_lib_u_everybody_ps_eclass)) {
			$n = $_lib_u_everybody_ps_eclass[$role];
			$ret[$n] = $data['職員'];
			$ret["$n" . '名'] = $data['名'];
		}
	}

	$stmt = '
SELECT 
    I."日常生活自立度" AS "日常生活自立度",
    I."痴呆性老人の日常生活自立度判定基準" AS
      "痴呆性老人の日常生活自立度判定基準",
    I."排泄・短期目標" AS "排泄・短期目標",
    I."排泄・アプローチ" AS "排泄・アプローチ"
FROM "患者台帳" as P
JOIN "日常生活自立度管理表" as I
ON P."ObjectID" = I."患者" AND I."Superseded" IS NULL
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt) . '
ORDER BY I."日付" DESC
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
FROM "患者台帳" as P
JOIN "リハ処方箋" AS T
ON P."ObjectID" = T."患者" AND T."Superseded" IS NULL
WHERE
     P."Superseded" IS NULL AND P."ObjectID" = ' . mx_db_sql_quote($pt) . '
ORDER BY T."処方日" DESC
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
FROM "リハ計画書準備"
WHERE "ObjectID" IN
(
    SELECT max("ObjectID")
    FROM "リハ計画書準備"
    WHERE "患者" = ' . mx_db_sql_quote($pt). ' AND "Superseded" IS NULL
    GROUP BY "職種名"
)';
	print "<!--\nstmt = $stmt;\n-->\n"; 
	$st = pg_query($db, $stmt);
	$result = pg_fetch_all($st);
	foreach ($result as $data) {
		$ec = $data['職種名'];
		$ret[$ec . 'によるコメント'] = $data['コメント'];
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
