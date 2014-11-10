<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class holiday_calendar {

	function holiday_calendar($modality) {
		$this->modality = $modality;
	}

	function read_calendar() {
		$modality = $this->modality;
		if (is_null($modality))
			$modality = 'modality is NULL';
		else
			$modality = "modality = $modality";
		$stmt = <<<SQL
SELECT rule, year, month, mday, nth, wday, name, avail,
to_char(start_time, 'HH24:MI') as start_time,
to_char(end_time, 'HH24:MI') as end_time,
modality, sortorder
FROM "ÉÂ±¡µÙÆüÉ½"
WHERE
$modality
ORDER BY sortorder
SQL;
		$db = mx_db_connect();
		$data = pg_fetch_all(pg_query($db, $stmt));
		if (!$data)
			$data = array();
		return $data;
	}

	function everything_empty($d) {
		foreach ($d as $k => $v) {
			if ($k == 'avail' || $k == 'rule' || $k == 'name')
				continue;
			if (trim($v) != '')
				return 0;
		}
		return 1;
	}

	function update($data) {
		$db = mx_db_connect();

		pg_query($db, 'begin');
		while (1) {
			$modality = $this->modality;
			if (is_null($modality))
				$modality = 'modality is NULL';
			else
				$modality = "modality = $modality";
			$stmt = "DELETE FROM \"ÉÂ±¡µÙÆüÉ½\" WHERE $modality";
			if (!pg_query($db, $stmt))
				break;

			if (is_null($this->modality))
				$modality = 'NULL';
			else
				$modality = $this->modality;

			$cnt = count($data);
			$fail = 0;
			for ($i = 0; !$fail && $i < $cnt; $i++) {
				$d = $data[$i];
				if ($this->everything_empty($d))
					continue;
				$name = $this->trim_to_null($d['name']);
				$avail = $this->trim_to_null($d['avail']);
				$rule = $this->trim_to_null($d['rule']);
				$year = $this->trim_to_null($d['year']);
				$month = $this->trim_to_null($d['month']);
				$mday = $this->trim_to_null($d['mday']);
				$nth = $this->trim_to_null($d['nth']);
				$wday = $this->trim_to_null($d['wday']);
				$start_time = $this->trim_to_null($d['start_time']);
				$end_time = $this->trim_to_null($d['end_time']);
				$sortorder = $i;

				$stmt = <<<SQL
INSERT INTO "ÉÂ±¡µÙÆüÉ½"
(name, avail, rule, year, month, mday, nth, wday,
start_time, end_time, sortorder, modality)
VALUES (
$name, $avail, $rule, $year, $month, $mday, $nth, $wday,
$start_time, $end_time, $sortorder, $modality
)
SQL;
				if (!pg_query($db, $stmt))
					$fail = 1;
			}
			if ($fail)
				break;
			pg_query($db, 'commit');
			break;
		}
		pg_query($db, 'rollback');
		return NULL;
	}

	function trim_to_null($v) {
		if (trim($v) == '')
			return 'NULL';
		return mx_db_sql_quote($v);
	}
}

?>
