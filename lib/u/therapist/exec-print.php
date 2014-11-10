<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function _lib_u_therapist_exec_print_stmt($bd, $ed) {
	$cond = array();
	if ($bd)
		$cond[] = 'X."����" >= ' . mx_db_sql_quote($bd);
	if ($ed)
		$cond[] = 'X."����" <= ' . mx_db_sql_quote($ed);
	if ($cond)
		$cond = 'WHERE ' . implode(" AND\n", $cond);
	else
		$cond = '';

	return '
SELECT	XD."ObjectID", P."����ID", (P."��" || P."̾") AS "����̾",
	XD."��ϼ»ܵ�Ͽ", X."����", (E."��" || E."̾") AS "�»���ˡ��",
	X."ɾ��S", X."ɾ��O", X."ɾ��A", X."ɾ��P",
	XD."��������", XD."��λ����", XD."�������", XD."��������",
	XD."ñ�̿�", XD."ñ�̼���", XD."���Ź԰٥�����", XD."������"
FROM	"������Ģ" AS P
JOIN	"��Ͻ����" AS RX
	ON P."Superseded" IS NULL AND
	   RX."Superseded" IS NULL AND
	   P."ObjectID" = RX."����"
JOIN	"��ϼ»ܵ�Ͽ" AS X
	ON X."Superseded" IS NULL AND
	   X."��Ͻ����" = RX."ObjectID"
JOIN	"��ϼ»ܵ�Ͽ����" AS XD
	ON XD."��ϼ»ܵ�Ͽ" = X."ObjectID"
LEFT JOIN "������Ģ" AS E
	ON E."Superseded" IS NULL AND
	   X."�»���ˡ��" = E."ObjectID"
' . "$cond" . '

ORDER BY
	XD."��������", P."����ID"';
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
				$this->errs[] = "(�Ϥ���): $e";

			$e = mx_db_validate_length($ed, 1, 0);
			if (!$e)
				$e = mx_db_validate_date($ed);
			if ($e)
				$this->errs[] = "(�����): $e";

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
			print "�ʲ��Υ��顼������ޤ�<br>";
			foreach ($this->errs as $msg) {
				print "$msg<br>";
			}
			print "<br>\n";
		}
		else if ($this->empty_sod()) {
			print "�������֤μ»ܵ�Ͽ�Ϥ���ޤ���<br>\n";
		}

		$bd = $_REQUEST[$this->prefix . 'begindate'];
		$ed = $_REQUEST[$this->prefix . 'enddate'];
		$kind = $_REQUEST[$this->prefix . 'outputtype'];
		if ($kind != 1 && $kind != 2)
			$kind = 2;

		print "�����ϰ�<br>";
		print "�Ϥ���:";
		mx_formi_text($this->prefix . 'begindate', $bd);
		print "<br>";
		print "�����:";
		mx_formi_text($this->prefix . 'enddate', $ed);
		print "<br>";
		mx_formi_radio($this->prefix . 'outputtype',
			       $kind,
			       array(1 => '��ˡ����',
				     2 => '��������'));
		print "<br>";
		mx_formi_hidden('CSV', 'CSV');
		mx_formi_submit($this->prefix . 'show', 'CSV����');
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
					(trim($row['����ID']),
					 $row["����̾"],
					 $row["��ϼ»ܵ�Ͽ"],
					 $row["����"],
					 $row["�»���ˡ��"],
					 $row["ɾ��S"],
					 $row["ɾ��O"],
					 $row["ɾ��A"],
					 $row["ɾ��P"],
					 $row["��������"],
					 $row["��λ����"],
					 $row["�������"],
					 $row["��������"],
					 $row["ñ�̿�"],
					 $row["ñ�̼���"],
					 $row["���Ź԰٥�����"],
					 $row["������"]);
			} else {
				$result[] = array
					(trim($row['����ID']),
					 '.810',
					 $row["���Ź԰٥�����"],
					 $row["ñ�̿�"],
					 $row["��������"],
					 $row["��λ����"]);
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
