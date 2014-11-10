<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function _lib_u_manage_patient_summary_stmt() {
	return '
SELECT	L."����",
	"����ID", P."��", P."̾", P."�եꥬ��",
	CASE
	    WHEN P."����" = \'M\' THEN \'��\'
	    WHEN P."����" = \'F\' THEN \'��\'
	    ELSE \'����\'
	END AS "����",
	P."��ǯ����",
	E."��" AS "�缣����", E."̾" AS "�缣��̾",
	CASE
	    WHEN IO."����" = \'I\' THEN \'����\'
	    WHEN IO."����" = \'i\' THEN \'ž��ž��\'
	    WHEN IO."����" = \'O\' THEN \'�ౡ\'
	    WHEN IO."����" = \'o\' THEN \'ž��ž��\'
	END AS "����",
	CASE
	    WHEN IO."����" IN (\'I\', \'i\') THEN NULL
	    ELSE R."�¼�̾"
	END AS "���¼�",
	CASE
	    WHEN IO."����" IN (\'O\', \'o\') THEN NULL
	    ELSE R."�¼�̾"
	END AS "���¼�",
	IO."����" AS "����"
FROM "����������ž��ž��" AS IO
JOIN "�����������" AS L
ON   L."ObjectID" = IO."�����������"
JOIN "������Ģ" AS P
ON   P."ObjectID" = IO."����"
JOIN "�¼�����ɽ" AS R
ON   R."ObjectID" = IO."�¼�"

LEFT JOIN
(
	"����ô������" AS EP
	JOIN "����ô�������ǡ���" AS EPD
	ON EP."ObjectID" = EPD."����ô������"
	JOIN "������Ģ" AS E
	ON E."ObjectID" = EPD."����"
	JOIN "ô�����" AS ROLE
	ON EPD."ô�����" = ROLE."ObjectID" AND ROLE."ô�����" = \'�缣��\'
)
ON EP."����" = P."ObjectID" AND EP."Superseded" IS NULL

WHERE L."Superseded" IS NULL

UNION

SELECT	L."����",
	"����ID", P."��", P."̾", P."�եꥬ��",
	CASE
	    WHEN P."����" = \'M\' THEN \'��\'
	    WHEN P."����" = \'F\' THEN \'��\'
	    ELSE \'����\'
	END AS "����",
	P."��ǯ����",
	E."��" AS "�缣����", E."̾" AS "�缣��̾",
	\'ž��\' AS "����",
	RB."�¼�̾" AS "���¼�",
	RA."�¼�̾" AS "���¼�",
	NULL AS "����"
FROM "����������ž��" AS XR
JOIN "�����������" AS L
ON   L."ObjectID" = XR."�����������"
JOIN "������Ģ" AS P
ON   P."ObjectID" = XR."����"
JOIN "�¼�����ɽ" AS RA
ON   RA."ObjectID" = XR."ž�����¼�"
JOIN "�¼�����ɽ" AS RB
ON   RB."ObjectID" = XR."ž�����¼�"

LEFT JOIN
(
	"����ô������" AS EP
	JOIN "����ô�������ǡ���" AS EPD
	ON EP."ObjectID" = EPD."����ô������"
	JOIN "������Ģ" AS E
	ON E."ObjectID" = EPD."����"
	JOIN "ô�����" AS ROLE
	ON EPD."ô�����" = ROLE."ObjectID" AND ROLE."ô�����" = \'�缣��\'
)
ON EP."����" = P."ObjectID" AND EP."Superseded" IS NULL

WHERE L."Superseded" IS NULL

ORDER BY "����ID", "����", "���¼�"
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
			print "�ʲ��Υ��顼������ޤ�<br>";
			foreach ($this->errs as $msg) {
				print "$msg<br>";
			}
			print "<br>\n";
		}
		else if ($this->empty_sod()) {
			print "�������֤ε�Ͽ�Ϥ���ޤ���<br>\n";
		}
		mx_formi_hidden('CSV', 'CSV');
		mx_formi_submit($this->prefix . 'show', 'CSV����');
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
			$en = $row["�缣����"] . ' ' . $row["�缣��̾"];
			if (!is_null($last_pa) && $row['����ID'] == $last_pa) {
				$en = '';
			}
			$last_pa = $row['����ID'];
			$result[] = array
				(trim($last_pa),
				 $row["��"] . ' ' . $row["̾"],
				 $row["�եꥬ��"],
				 $row["����"],
				 $row["��ǯ����"],
				 $row["����"],
				 $row["����"],
				 $row["���¼�"],
				 $row["���¼�"],
				 $row["����"],
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
