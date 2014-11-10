<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';

function _lib_u_manage_patient_summary_stmt() {
	return '
SELECT	L."ÆüÉÕ",
	"´µ¼ÔID", P."À«", P."Ì¾", P."¥Õ¥ê¥¬¥Ê",
	CASE
	    WHEN P."À­ÊÌ" = \'M\' THEN \'ÃË\'
	    WHEN P."À­ÊÌ" = \'F\' THEN \'½÷\'
	    ELSE \'ÉÔÌÀ\'
	END AS "À­ÊÌ",
	P."À¸Ç¯·îÆü",
	E."À«" AS "¼ç¼£°åÀ«", E."Ì¾" AS "¼ç¼£°åÌ¾",
	CASE
	    WHEN IO."Æþ½Ð" = \'I\' THEN \'Æþ±¡\'
	    WHEN IO."Æþ½Ð" = \'i\' THEN \'Å¾ÅïÅ¾Æþ\'
	    WHEN IO."Æþ½Ð" = \'O\' THEN \'Âà±¡\'
	    WHEN IO."Æþ½Ð" = \'o\' THEN \'Å¾ÅïÅ¾½Ð\'
	END AS "»ö¾Ý",
	CASE
	    WHEN IO."Æþ½Ð" IN (\'I\', \'i\') THEN NULL
	    ELSE R."ÉÂ¼¼Ì¾"
	END AS "¸µÉÂ¼¼",
	CASE
	    WHEN IO."Æþ½Ð" IN (\'O\', \'o\') THEN NULL
	    ELSE R."ÉÂ¼¼Ì¾"
	END AS "ÀèÉÂ¼¼",
	IO."È÷¹Í" AS "È÷¹Í"
FROM "ÉÂÅï´ÉÍýÆü»ï¡¦Å¾ÆþÅ¾½Ð" AS IO
JOIN "ÉÂÅï´ÉÍýÆü»ï" AS L
ON   L."ObjectID" = IO."ÉÂÅï´ÉÍýÆü»ï"
JOIN "´µ¼ÔÂæÄ¢" AS P
ON   P."ObjectID" = IO."´µ¼Ô"
JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
ON   R."ObjectID" = IO."ÉÂ¼¼"

LEFT JOIN
(
	"´µ¼ÔÃ´Åö¿¦°÷" AS EP
	JOIN "´µ¼ÔÃ´Åö¿¦°÷¥Ç¡¼¥¿" AS EPD
	ON EP."ObjectID" = EPD."´µ¼ÔÃ´Åö¿¦°÷"
	JOIN "¿¦°÷ÂæÄ¢" AS E
	ON E."ObjectID" = EPD."¿¦°÷"
	JOIN "Ã´ÅöÌò³ä" AS ROLE
	ON EPD."Ã´ÅöÌò³ä" = ROLE."ObjectID" AND ROLE."Ã´ÅöÌò³ä" = \'¼ç¼£°å\'
)
ON EP."´µ¼Ô" = P."ObjectID" AND EP."Superseded" IS NULL

WHERE L."Superseded" IS NULL

UNION

SELECT	L."ÆüÉÕ",
	"´µ¼ÔID", P."À«", P."Ì¾", P."¥Õ¥ê¥¬¥Ê",
	CASE
	    WHEN P."À­ÊÌ" = \'M\' THEN \'ÃË\'
	    WHEN P."À­ÊÌ" = \'F\' THEN \'½÷\'
	    ELSE \'ÉÔÌÀ\'
	END AS "À­ÊÌ",
	P."À¸Ç¯·îÆü",
	E."À«" AS "¼ç¼£°åÀ«", E."Ì¾" AS "¼ç¼£°åÌ¾",
	\'Å¾¼¼\' AS "»ö¾Ý",
	RB."ÉÂ¼¼Ì¾" AS "¸µÉÂ¼¼",
	RA."ÉÂ¼¼Ì¾" AS "ÀèÉÂ¼¼",
	NULL AS "È÷¹Í"
FROM "ÉÂÅï´ÉÍýÆü»ï¡¦Å¾¼¼" AS XR
JOIN "ÉÂÅï´ÉÍýÆü»ï" AS L
ON   L."ObjectID" = XR."ÉÂÅï´ÉÍýÆü»ï"
JOIN "´µ¼ÔÂæÄ¢" AS P
ON   P."ObjectID" = XR."´µ¼Ô"
JOIN "ÉÂ¼¼°ìÍ÷É½" AS RA
ON   RA."ObjectID" = XR."Å¾¼¼ÀèÉÂ¼¼"
JOIN "ÉÂ¼¼°ìÍ÷É½" AS RB
ON   RB."ObjectID" = XR."Å¾¼¼¸µÉÂ¼¼"

LEFT JOIN
(
	"´µ¼ÔÃ´Åö¿¦°÷" AS EP
	JOIN "´µ¼ÔÃ´Åö¿¦°÷¥Ç¡¼¥¿" AS EPD
	ON EP."ObjectID" = EPD."´µ¼ÔÃ´Åö¿¦°÷"
	JOIN "¿¦°÷ÂæÄ¢" AS E
	ON E."ObjectID" = EPD."¿¦°÷"
	JOIN "Ã´ÅöÌò³ä" AS ROLE
	ON EPD."Ã´ÅöÌò³ä" = ROLE."ObjectID" AND ROLE."Ã´ÅöÌò³ä" = \'¼ç¼£°å\'
)
ON EP."´µ¼Ô" = P."ObjectID" AND EP."Superseded" IS NULL

WHERE L."Superseded" IS NULL

ORDER BY "´µ¼ÔID", "ÆüÉÕ", "¸µÉÂ¼¼"
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
			print "°Ê²¼¤Î¥¨¥é¡¼¤¬¤¢¤ê¤Þ¤¹<br>";
			foreach ($this->errs as $msg) {
				print "$msg<br>";
			}
			print "<br>\n";
		}
		else if ($this->empty_sod()) {
			print "Åö³º´ü´Ö¤Îµ­Ï¿¤Ï¤¢¤ê¤Þ¤»¤ó<br>\n";
		}
		mx_formi_hidden('CSV', 'CSV');
		mx_formi_submit($this->prefix . 'show', 'CSV½ÐÎÏ');
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
			$en = $row["¼ç¼£°åÀ«"] . ' ' . $row["¼ç¼£°åÌ¾"];
			if (!is_null($last_pa) && $row['´µ¼ÔID'] == $last_pa) {
				$en = '';
			}
			$last_pa = $row['´µ¼ÔID'];
			$result[] = array
				(trim($last_pa),
				 $row["À«"] . ' ' . $row["Ì¾"],
				 $row["¥Õ¥ê¥¬¥Ê"],
				 $row["À­ÊÌ"],
				 $row["À¸Ç¯·îÆü"],
				 $row["ÆüÉÕ"],
				 $row["»ö¾Ý"],
				 $row["¸µÉÂ¼¼"],
				 $row["ÀèÉÂ¼¼"],
				 $row["È÷¹Í"],
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
