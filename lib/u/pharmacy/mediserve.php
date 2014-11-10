<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class mediserve_checker {

	function mediserve_checker(&$dbh) {
		$this->dbh = $dbh;
		$this->clr_all();
	}

	function clr_all() {
		$this->clr_med();
		$this->clr_dis();
		$this->clr_err();
		$this->clr_unit();
		$this->duration_ceil = 0;
	}

	function clr_med() {
		$this->med = array();
	}

	function clr_dis() {
		$this->disease = array();
	}

	function clr_err() {
		$this->err = array();
	}

	function clr_unit() {
		$this->unit = array();
	}

	function get_err() {
		return $this->err;
	}

	function add_med($med, // ObjectID from Medis医薬品マスター
			 $num_days, // Number of days
			 $max_dose_per_day, // Max dosage in a day
			 $total_dose, // Total dosage
			 $dosage_unit // 用量単位(文字列)
		) {
		$this->med[] = array('Medicine' => $med,
				     'NumDays' => $num_days,
				     'MaxDosePerDay' => $max_dose_per_day,
				     'TotalDose' => $total_dose,
				     'DosageUnit' => $dosage_unit);
	}

	function add_dis($disease // ObjectID from "Medis病名マスター"
		) {
		$this->disease[] = array('Disease' => $disease);
	}

	function add_same_unit($ours, $canonical) {
		$this->unit[$ours] = $canonical;
	}

	function add_err($elem) {
		$this->err[] = $elem;
	}

	function check() {
		$this->clr_err();
		$this->set_yjcode();
		if (!count($this->yjs))
			return;
		$this->check_kinki();
		$this->check_nissu();
		$this->check_dosage();
		$this->check_pharmacologic_action();

		$this->set_discode();
		$this->check_dismed();
	}

	function get_duration_ceil() {
		return $this->duration_ceil;
	}

	function set_yjcode() {
		$code = array();
		foreach ($this->med as $med) {
			$m = $med['Medicine'];
			$code[$m] = "'$m'";
		}
		$in = implode(", ", $code);
		$stmt = <<<SQL
SELECT Y."ＹＪコード", M."ObjectID"
FROM "MediServe_YJ_レセ電算コードマスター" AS Y
JOIN "Medis医薬品マスター" AS M
ON   M."レセプト電算処理システムコード（１）" = Y."レセ電算コード"
AND  M."Superseded" IS NULL
WHERE M."ObjectID" IN ($in)
SQL;
		$yjmap = array();
		$ryjmap = array();
		$map = mx_db_fetch_all($this->dbh, $stmt);
		foreach ($map as $m) {
			$o = $m["ObjectID"];
			$yjmap[$o] = $m["ＹＪコード"];
			$ryjmap[$m["ＹＪコード"]] = $o;
		}
		$med = array();
		$yjs = array();
		foreach ($this->med as $m) {
			$o = $m['Medicine'];
			if (array_key_exists($o, $yjmap)) {
				$m['YJCode'] = $yjmap[$o];
				$yjs[] = $yjmap[$o];
			}
			$med[] = $m; 
		}
		$this->med = $med;
		$this->yjs = $yjs;
		$this->yjtomed = $ryjmap;
	}

	function set_discode() {
		$code = array();
		foreach ($this->disease as $dis) {
			$d = $dis['Disease'];
			$code[$d] = $d;
		}
		if (!count($code))
			return;
		$in = implode(", ", $code);
		$stmt = <<<SQL
SELECT "ObjectID", "レセ電算コード", "病名表記"
FROM "Medis病名マスター"
WHERE "Superseded" IS NULL AND "ObjectID" IN ($in)
SQL;
		$map = mx_db_fetch_all($this->dbh, $stmt);
		$d2r = array();
		$r2d = array();
		$d2n = array();
		foreach ($map as $m) {
			$o = $m['ObjectID'];
			$r = $m["レセ電算コード"];
			$d2r[$o] = $r;
			$r2d[$r] = $d;
			$d2n[$o] = $m['病名表記'];
		}
		$disease = array();
		foreach ($this->disease as $dis) {
			$d = $dis['Disease'];
			if (!array_key_exists($d, $d2r))
				continue;
			$dis['Rececode'] = $d2r[$d];
			$dis['病名表記'] = $d2n[$d];
			$disease[] = $dis;
		}
		$this->disease = $disease;
		$this->d2rmap = $d2r;
		$this->r2dmap = $r2d;
	}

	function check_kinki_sub($tbltype, $coltype, $label) {
		$yjtomed = $this->yjtomed;
		$seen = array();
		$maincol = 'ＹＪコード';
		$errs = array();
		foreach ($this->yjs as $yj) {
			$seen[$yj] = 1;
			$tocheck = array();
			foreach ($this->yjs as $other) {
				if (array_key_exists($other, $seen))
					continue;
				$st = ("(\"$maincol\" = '$yj' AND " .
				       "\"$coltype\" = '$other')");
				$tocheck[] = $st;
			}
			if (!count($tocheck))
				break;
			$tocheck = implode(" OR\n ", $tocheck);
			$stmt = <<<SQL
SELECT "ＹＪコード", "$coltype"
FROM "$tbltype"
WHERE $tocheck
SQL;
			$err = mx_db_fetch_all($this->dbh, $stmt);
			$other = array();
			foreach ($err as $e)
				$other[] = $yjtomed[$e[$coltype]];
			if (!count($other))
				continue;

			$this->add_err(array('Medicine' => $yjtomed[$yj],
					     'ＹＪコード' => $yj,
					     $label => $other));
		}
	}

	function check_kinki() {
		$this->check_kinki_sub('MediServe_原則併用禁忌コードマスター',
				       '原則禁忌ＹＪコード',
				       '原則禁忌');
		$this->check_kinki_sub('MediServe_絶対併用禁忌コードマスター',
				       '絶対禁忌ＹＪコード',
				       '絶対禁忌');
	}

	function check_nissu() {
		$in = array();
		foreach ($this->yjs as $yj)
			$in[] = "'$yj'";
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "ＹＪコード", "銘柄名", "投与期間制限日数", "投与期間制限理由"
FROM "MediServe_投与日数制限マスター"
WHERE "ＹＪコード" IN ($in)
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$check = array();
		$duration_ceil = 0;
		foreach ($result as $r)
			$check[$r["ＹＪコード"]] = $r;
		foreach ($this->med as $m) {
			$o = $m['YJCode'];
			if (!array_key_exists($o, $check))
				continue;
			$r = $check[$o];
			$days = $m['NumDays'];
			if ($duration_ceil < $r['投与期間制限日数'])
				$duration_ceil = $r['投与期間制限日数'];
			if ($days < $r['投与期間制限日数'])
				continue;
			$this->add_err(array('Medicine' => $m['Medicine'],
					     'ＹＪコード' => $o,
					     '投与期間制限日数' =>
					     $r["投与期間制限日数"],
					     '投与期間制限理由' =>
					     $r["投与期間制限理由"]));
		}
		$this->duration_ceil = $duration_ceil;
	}

	function check_dosage() {
		$in = array();
		foreach ($this->yjs as $yj)
			$in[] = "'$yj'";
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "ＹＪコード", "銘柄名", "投与単位変換倍率", "投与単位名称",
    "一日通常量下限値","一日通常量上限値","一日最大量","最大量単位"
FROM "MediServe_成人最高用量マスター"
WHERE "ＹＪコード" IN ($in)
AND "一日最大量" IS NOT NULL
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$check = array();
		foreach ($result as $r)
			$check[$r["ＹＪコード"]] = $r;
		foreach ($this->med as $m) {
			$o = $m['YJCode'];
			if (!array_key_exists($o, $check))
				continue;
			$this->check_dosage_one($m, $check[$o]);
		}
	}

	function convert_unit_name($name) {
		if (array_key_exists($name, $this->unit))
			return $this->unit[$name];
		return $name;
	}

	function cmp_unit_name($a, $b) {
		$a = $this->convert_unit_name($a);
		$b = $this->convert_unit_name($b);
		return ($a != $b);
	}

	function check_dosage_one($m, $d) {
		$dosage = $m['MaxDosePerDay'];
		$unit = $m['DosageUnit'];
		$units = explode('\\', $d['投与単位名称']);
		$scale = explode('\\', $d['投与単位変換倍率']);
		$limit = $d['一日最大量'];
		$use_scale_mul = 0;
		$use_scale_div = 0;

		if (!$this->cmp_unit_name($unit, $d['最大量単位'])) {
			$use_scale_mul = 1;
			$use_scale_div = 1;
		} else {
			for ($i = 0; $i < count($units); $i++) {
				if (!$this->cmp_unit_name($unit, $units[$i]))
					$use_scale_mul = $scale[$i];
				if (!$this->cmp_unit_name($d['最大量単位'],
							  $units[$i]))
					$use_scale_div = $scale[$i];
			}
		}
		if (!$use_scale_div || !$use_scale_mul) {
			$c_units = array();
			foreach ($units as $u) {
				if (trim($u) != '')
					$c_units[] = $u;
			}
			$this->add_err(array('Medicine' => $m['Medicine'],
					     "ＹＪコード" => $m['YJCode'],
					     '投与量単位' => $m['DosageUnit'],
					     '可能投与量単位' => $c_units));
			return;
		}
		$limit = ($limit * $use_scale_mul) / $use_scale_div;
		if ($limit < $dosage) {
			$lo = ($d['一日通常量下限値'] * $use_scale_mul /
			       $use_scale_div);
			$hi = ($d['一日通常量上限値'] * $use_scale_mul /
			       $use_scale_div);
			$this->add_err(array('Medicine' => $m['Medicine'],
					     "ＹＪコード" => $m['YJCode'],
					     '一日最大量' => $limit,
					     '一日通常量下限値' => $lo,
					     '一日通常量上限値' => $hi,
					     '最大量単位' => $unit));
		}
	}

	function check_dismed() {
		if (!count($this->yjs) || !count($this->disease))
			return;

		# Start dnmd with all diseases; as check_dismed_one()
		# finds diseases with matching medicine, it will
		# remove them from the array.

		$dis_need_med_advice = array();
		foreach ($this->disease as $dis) {
			$r = $dis['Rececode'];
			$dis_need_med_advice[$r] = $dis;
		}

		$in = array();
		foreach ($this->yjs as $yj) {
			$in[] = "'$yj'";
		}
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "ＹＪコード", "銘柄名漢字", "個別化病名コード２", "個別化禁忌病名コード２"
FROM "MediServe_適応病名・禁忌病名コードマスター"
WHERE "ＹＪコード" IN ($in)
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		foreach ($result as $r)
			$this->check_dismed_one($r, &$dis_need_med_advice);
		foreach ($dis_need_med_advice as $d)
			$this->check_dismed_two($d);
	}

	function check_dismed_one($check, &$dnma) {
		$codes = explode('\\', $check['個別化禁忌病名コード２']);
		$okcodes = explode('\\', $check['個別化病名コード２']);
		$map = array();
		$okmap = array();
		$yj = $check['ＹＪコード'];
		$m = $this->yjtomed[$yj];
		for ($i = 0; $i < count($codes); $i++)
			$map[$codes[$i]] = $codes[$i];
		for ($i = 0; $i < count($okcodes); $i++)
			$okmap[$okcodes[$i]] = "'" . $okcodes[$i] . "'";
		$found = 0;
		foreach ($this->disease as $dis) {
			$r = $dis['Rececode'];
			if (array_key_exists($r, $okmap)) {
				$found = 1;
				if (array_key_exists($r, $dnma))
					unset($dnma[$r]);
			}
			if (!array_key_exists($r, $map))
				continue;
			$this->add_err(array('Medicine' => $m,
					     'ＹＪコード' => $yj,
					     '禁忌病名' => $dis['Disease'],
					     '病名表記' => $dis['病名表記']));
		}
		if ($found)
			return;

		if (!count($okmap)) {
			$allowed = array();
		} else {
			$in = implode(', ', $okmap);
			$stmt = <<<SQL
SELECT "ObjectID" AS "Disease", "レセ電算コード", "病名表記", "当院採用"
FROM "Medis病名マスター"
WHERE "Superseded" IS NULL AND "レセ電算コード" IN ($in)
SQL;
			$allowed = mx_db_fetch_all($this->dbh, $stmt);
		}
		$this->add_err(array('Medicine' => $m,
				     'ＹＪコード' => $yj,
				     '適応病名' => $allowed));


	}

	function check_dismed_two($dis) {
		$d = $dis['Rececode'];
		$stmt = <<<SQL
SELECT M."ObjectID", P."ＹＪコード", M."告示名称"
FROM "MediServe_Tweak_病名適合医薬品" AS P
JOIN "MediServe_YJ_レセ電算コードマスター" AS Y
ON   P."ＹＪコード" = Y."ＹＪコード"
JOIN "Medis医薬品マスター" AS M
ON   M."レセプト電算処理システムコード（１）" = Y."レセ電算コード"
AND  M."Superseded" IS NULL
WHERE "拡張病名コード" = '$d'
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$this->add_err(array('無処方病名' => $dis['Disease'],
				     '病名表記' => $dis['病名表記'],
				     '適用可能薬剤' => $result));
	}

	function check_pharmacologic_action() {
		if (!count($this->yjs))
			return;
		$in = array();
		foreach ($this->yjs as $yj)
			$in[] = "'$yj'";
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "ＹＪコード", "一般名", "薬理作用1コード", "薬理作用2コード"
FROM "MediServe_同種同効薬コードマスター"
WHERE "ＹＪコード" IN ($in)
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$action = array();
		foreach ($result as $a) {
			$a1 = $a["薬理作用1コード"];
			$a2 = $a["薬理作用2コード"];
			$key = "$a1,$a2";
			if (!array_key_exists($key, $action)) {
				$action[$key] = array();
			}
			$action[$key][] = $a;
		}

		foreach ($action as $a => $yjs) {
			if (count($yjs) < 2)
				continue;
			$seen = array();
			foreach ($yjs as $act) {
				$yj = $act['ＹＪコード'];
				$seen[$yj] = 1;
				$dup = array();
				foreach ($yjs as $other) {
					$oyj = $other['ＹＪコード'];
					if (array_key_exists($oyj, $seen))
						continue;
					$dup[] = array('Medicine' =>
						       $this->yjtomed[$oyj],
						       'ＹＪコード' => $oyj);
				}
				if (!count($dup))
					continue;
				$this->add_err(array('Medicine' =>
						     $this->yjtomed[$yj],
						     'ＹＪコード' => $yj,
						     '同種同効薬' => $dup));
			}
		}
	}

	function find_similar($med) {
		$stmt = <<<SQL
SELECT M."ObjectID" AS "Medicine", A."ＹＪコード", A."一般名"
FROM "MediServe_同種同効薬コードマスター" AS AA
JOIN "MediServe_YJ_レセ電算コードマスター" AS YY
  ON YY."ＹＪコード" = AA."ＹＪコード"
JOIN "Medis医薬品マスター" AS MM
  ON MM."レセプト電算処理システムコード（１）" = YY."レセ電算コード"
 AND MM."ObjectID" = $med
JOIN "MediServe_同種同効薬コードマスター" AS A
  ON A."薬理作用1コード" = AA."薬理作用1コード"
 AND A."薬理作用2コード" = AA."薬理作用2コード"
JOIN "MediServe_YJ_レセ電算コードマスター" AS Y
  ON Y."ＹＪコード" = A."ＹＪコード"
JOIN "Medis医薬品マスター" AS M
  ON M."レセプト電算処理システムコード（１）" = Y."レセ電算コード"
 AND M."ObjectID" != $med AND M."Superseded" IS NULL
SQL;
		return mx_db_fetch_all($this->dbh, $stmt);
	}


	function find_document($med) {
		$yj = mx_db_sql_quote($_GET['yj']);
		$stmt = <<<SQL
SELECT A.*
FROM "MediServe_添付文書情報マスター" A
JOIN "Medis医薬品マスター" AS M
  ON M."個別医薬品コード" = A."ＹＪコード"
 AND M."ObjectID" = $med
 AND M."Superseded" IS NULL
LIMIT 1
SQL;
		$ret = mx_db_fetch_single($this->dbh, $stmt);
		$s = array();
		if($ret) {
		  $s[] = "<br><br><br><br><br>";
		  $s[] = "<table style=\"border: 1px solid\">";
		  foreach($ret as $k => $v) {
		    $ek = htmlspecialchars($k);
		    $vk = htmlspecialchars($v);
		    $vka = explode('!', $vk);
		    $ret = array();
		    $intable = FALSE;
		    foreach($vka as $v) {
		      if ($v == '（表開始）') {
			$ret[] = "<table style=\"border: 1px solid\">";
			$intable = TRUE;
		      }else if ($v == '（表終了）') {
			$ret[] = '</table>';
			$intable = FALSE;
		      }else if ($intable) {
			$row = explode('：', $v);
			for($i=0; $i < count($row); $i++)
			  if ($row[$i] == '')
			    $row[$i] = '&nbsp;';
			$ret[] = '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
		      }else{
			$ret[] = $v;
		      }
		    }
		    $vk = implode('<br>', $ret);
		    $s[] = "<tr><td style=\"white-space: nowrap\">$ek</td><td>$vk</td></tr>";
		  }
		  $s[] = "</table>";
		}
		else
		  $s[] = "この薬剤の添付文書は登録されていません";
		return implode("\n", $s);
	}
}
?>
