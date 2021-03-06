<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function _lib_u_manage_patient_summary_stmt() {
	return '
SELECT	L."日付",
	"患者ID", P."姓", P."名", P."フリガナ",
	CASE
	    WHEN P."性別" = \'M\' THEN \'男\'
	    WHEN P."性別" = \'F\' THEN \'女\'
	    ELSE \'不明\'
	END AS "性別",
	P."生年月日",
	E."姓" AS "主治医姓", E."名" AS "主治医名",
	CASE
	    WHEN IO."入出" = \'I\' THEN \'入院\'
	    WHEN IO."入出" = \'i\' THEN \'転棟転入\'
	    WHEN IO."入出" = \'O\' THEN \'退院\'
	    WHEN IO."入出" = \'o\' THEN \'転棟転出\'
	END AS "事象",
	CASE
	    WHEN IO."入出" IN (\'I\', \'i\') THEN NULL
	    ELSE R."病室名"
	END AS "元病室",
	CASE
	    WHEN IO."入出" IN (\'O\', \'o\') THEN NULL
	    ELSE R."病室名"
	END AS "先病室",
	IO."備考" AS "備考"
FROM "病棟管理日誌・転入転出" AS IO
JOIN "病棟管理日誌" AS L
ON   L."ObjectID" = IO."病棟管理日誌"
JOIN "患者台帳" AS P
ON   P."ObjectID" = IO."患者"
JOIN "病室一覧表" AS R
ON   R."ObjectID" = IO."病室"

LEFT JOIN
(
	"患者担当職員" AS EP
	JOIN "患者担当職員データ" AS EPD
	ON EP."ObjectID" = EPD."患者担当職員"
	JOIN "職員台帳" AS E
	ON E."ObjectID" = EPD."職員"
	JOIN "担当役割" AS ROLE
	ON EPD."担当役割" = ROLE."ObjectID" AND ROLE."担当役割" = \'主治医\'
)
ON EP."患者" = P."ObjectID" AND EP."Superseded" IS NULL

WHERE L."Superseded" IS NULL

UNION

SELECT	L."日付",
	"患者ID", P."姓", P."名", P."フリガナ",
	CASE
	    WHEN P."性別" = \'M\' THEN \'男\'
	    WHEN P."性別" = \'F\' THEN \'女\'
	    ELSE \'不明\'
	END AS "性別",
	P."生年月日",
	E."姓" AS "主治医姓", E."名" AS "主治医名",
	\'転室\' AS "事象",
	RB."病室名" AS "元病室",
	RA."病室名" AS "先病室",
	NULL AS "備考"
FROM "病棟管理日誌・転室" AS XR
JOIN "病棟管理日誌" AS L
ON   L."ObjectID" = XR."病棟管理日誌"
JOIN "患者台帳" AS P
ON   P."ObjectID" = XR."患者"
JOIN "病室一覧表" AS RA
ON   RA."ObjectID" = XR."転室先病室"
JOIN "病室一覧表" AS RB
ON   RB."ObjectID" = XR."転室元病室"

LEFT JOIN
(
	"患者担当職員" AS EP
	JOIN "患者担当職員データ" AS EPD
	ON EP."ObjectID" = EPD."患者担当職員"
	JOIN "職員台帳" AS E
	ON E."ObjectID" = EPD."職員"
	JOIN "担当役割" AS ROLE
	ON EPD."担当役割" = ROLE."ObjectID" AND ROLE."担当役割" = \'主治医\'
)
ON EP."患者" = P."ObjectID" AND EP."Superseded" IS NULL

WHERE L."Superseded" IS NULL

ORDER BY "患者ID", "日付", "元病室"
	';
}

class patient_summary_print_cfg { // extends nottin'

	function patient_summary_print_cfg($prefix) {
		$this->prefix = $prefix;
		$this->chosen_ = NULL;
		$this->errs = array();
		if (array_key_exists($this->prefix . 'show', $_REQUEST)) {
			$e = mx_db_validate_length($bd, 1, 0);
			if (!$this->errs)
				$this->chosen_ = 1;
		}
	}

	function changed() { return 1; }

	function chosen() {
		return $this->chosen_;
	}

	function lost_selection() { return 0; }

	function empty_sod() {
		$sod = $this->application->sod;
		return ($this->chosen_ && !is_null($sod->data));
	}

	function draw() {
		print "<br>";
		if ($this->errs) {
			print "以下のエラーがあります<br>";
			foreach ($this->errs as $msg) {
				print "$msg<br>";
			}
			print "<br>\n";
		}
		else if ($this->empty_sod()) {
			print "当該期間の記録はありません<br>\n";
		}
		mx_formi_hidden('CSV', 'CSV');
		mx_formi_submit($this->prefix . 'show', 'CSV出力');
	}

}

class patient_summary_print { // extends nottin'

	function reset($chosen) {
		$this->data = NULL;
		$stmt = _lib_u_manage_patient_summary_stmt();
		$db = mx_db_connect();
		$sth = pg_query($db, $stmt);
		$data = pg_fetch_all($sth);
		if (!$data) {
			$this->data = 0; // hack.  non-null but false.
			return;
		}
		$result = array();
		$last_pa = NULL;
		foreach ($data as $row) {
			$en = $row["主治医姓"] . ' ' . $row["主治医名"];
			if (!is_null($last_pa) && $row['患者ID'] == $last_pa) {
				$en = '';
			}
			$last_pa = $row['患者ID'];
			$result[] = array
				(trim($last_pa),
				 $row["姓"] . ' ' . $row["名"],
				 $row["フリガナ"],
				 $row["性別"],
				 $row["生年月日"],
				 $row["日付"],
				 $row["事象"],
				 $row["元病室"],
				 $row["先病室"],
				 $row["備考"],
				 $en,
				 );
		}
		$this->data = $result;
	}

	function chosen() {
		return ($this->data);
	}

	function csv_data() {
		return $this->data;
	}
}

?>
