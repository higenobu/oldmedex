<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function _lib_u_therapist_exec_print_stmt($bd, $ed) {
	$cond = array();
	if ($bd)
		$cond[] = 'X."日付" >= ' . mx_db_sql_quote($bd);
	if ($ed)
		$cond[] = 'X."日付" <= ' . mx_db_sql_quote($ed);
	if ($cond)
		$cond = 'WHERE ' . implode(" AND\n", $cond);
	else
		$cond = '';

	return '
SELECT	XD."ObjectID", P."患者ID", (P."姓" || P."名") AS "患者名",
	XD."リハ実施記録", X."日付", (E."姓" || E."名") AS "実施療法士",
	X."評価S", X."評価O", X."評価A", X."評価P",
	XD."開始日時", XD."終了日時", XD."訓練場所", XD."訓練内容",
	XD."単位数", XD."単位種別", XD."診療行為コード", XD."コメント"
FROM	"患者台帳" AS P
JOIN	"リハ処方箋" AS RX
	ON P."Superseded" IS NULL AND
	   RX."Superseded" IS NULL AND
	   P."ObjectID" = RX."患者"
JOIN	"リハ実施記録" AS X
	ON X."Superseded" IS NULL AND
	   X."リハ処方箋" = RX."ObjectID"
JOIN	"リハ実施記録内容" AS XD
	ON XD."リハ実施記録" = X."ObjectID"
LEFT JOIN "職員台帳" AS E
	ON E."Superseded" IS NULL AND
	   X."実施療法士" = E."ObjectID"
' . "$cond" . '

ORDER BY
	XD."開始日時", P."患者ID"';
}

class therapist_exec_print_cfg { // extends nottin'

	function therapist_exec_print_cfg($prefix) {
		$this->prefix = $prefix;
		$this->chosen_ = NULL;
		$this->errs = array();
		if (array_key_exists($this->prefix . 'show', $_REQUEST)) {
			$bd = $_REQUEST[$this->prefix . 'begindate'];
			$ed = $_REQUEST[$this->prefix . 'enddate'];
			$kind = $_REQUEST[$this->prefix . 'outputtype'];
			if ($kind != 1 && $kind != 2)
				$kind = 2;

			$e = mx_db_validate_length($bd, 1, 0);
			if (!$e)
				$e = mx_db_validate_date($bd);
			if ($e)
				$this->errs[] = "(はじめ): $e";

			$e = mx_db_validate_length($ed, 1, 0);
			if (!$e)
				$e = mx_db_validate_date($ed);
			if ($e)
				$this->errs[] = "(おわり): $e";

			if (!$this->errs)
				$this->chosen_ = array($bd, $ed, $kind);
		}
	}

	function changed() { return 1; }

	function chosen() {
		return $this->chosen_;
	}

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
			print "当該期間の実施記録はありません<br>\n";
		}

		$bd = $_REQUEST[$this->prefix . 'begindate'];
		$ed = $_REQUEST[$this->prefix . 'enddate'];
		$kind = $_REQUEST[$this->prefix . 'outputtype'];
		if ($kind != 1 && $kind != 2)
			$kind = 2;

		print "日付範囲<br>";
		print "はじめ:";
		mx_formi_text($this->prefix . 'begindate', $bd);
		print "<br>";
		print "おわり:";
		mx_formi_text($this->prefix . 'enddate', $ed);
		print "<br>";
		mx_formi_radio($this->prefix . 'outputtype',
			       $kind,
			       array(1 => '療法士用',
				     2 => '医事会計用'));
		print "<br>";
		mx_formi_hidden('CSV', 'CSV');
		mx_formi_submit($this->prefix . 'show', 'CSV出力');
	}

	function lost_selection() { return 0; }

}

class therapist_exec_print { // extends nottin'

	function reset($chosen) {
		$this->data = NULL;
		$stmt = _lib_u_therapist_exec_print_stmt
			($chosen[0], $chosen[1]);
		$db = mx_db_connect();
		$sth = pg_query($db, $stmt);
		$data = pg_fetch_all($sth);
		if (!$data) {
			$this->data = 0; // hack.  non-null but false.
			return;
		}
		$result = array();
		foreach ($data as $row) {
			if ($chosen[2] == 1) {
				$result[] = array
					(trim($row['患者ID']),
					 $row["患者名"],
					 $row["リハ実施記録"],
					 $row["日付"],
					 $row["実施療法士"],
					 $row["評価S"],
					 $row["評価O"],
					 $row["評価A"],
					 $row["評価P"],
					 $row["開始日時"],
					 $row["終了日時"],
					 $row["訓練場所"],
					 $row["訓練内容"],
					 $row["単位数"],
					 $row["単位種別"],
					 $row["診療行為コード"],
					 $row["コメント"]);
			} else {
				$result[] = array
					(trim($row['患者ID']),
					 '.810',
					 $row["診療行為コード"],
					 $row["単位数"],
					 $row["開始日時"],
					 $row["終了日時"]);
			}
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
