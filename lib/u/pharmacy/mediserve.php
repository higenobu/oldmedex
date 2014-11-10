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

	function add_med($med, // ObjectID from Medis�����ʥޥ�����
			 $num_days, // Number of days
			 $max_dose_per_day, // Max dosage in a day
			 $total_dose, // Total dosage
			 $dosage_unit // ����ñ��(ʸ����)
		) {
		$this->med[] = array('Medicine' => $med,
				     'NumDays' => $num_days,
				     'MaxDosePerDay' => $max_dose_per_day,
				     'TotalDose' => $total_dose,
				     'DosageUnit' => $dosage_unit);
	}

	function add_dis($disease // ObjectID from "Medis��̾�ޥ�����"
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
SELECT Y."�٣ʥ�����", M."ObjectID"
FROM "MediServe_YJ_�쥻�Ż������ɥޥ�����" AS Y
JOIN "Medis�����ʥޥ�����" AS M
ON   M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���" = Y."�쥻�Ż�������"
AND  M."Superseded" IS NULL
WHERE M."ObjectID" IN ($in)
SQL;
		$yjmap = array();
		$ryjmap = array();
		$map = mx_db_fetch_all($this->dbh, $stmt);
		foreach ($map as $m) {
			$o = $m["ObjectID"];
			$yjmap[$o] = $m["�٣ʥ�����"];
			$ryjmap[$m["�٣ʥ�����"]] = $o;
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
SELECT "ObjectID", "�쥻�Ż�������", "��̾ɽ��"
FROM "Medis��̾�ޥ�����"
WHERE "Superseded" IS NULL AND "ObjectID" IN ($in)
SQL;
		$map = mx_db_fetch_all($this->dbh, $stmt);
		$d2r = array();
		$r2d = array();
		$d2n = array();
		foreach ($map as $m) {
			$o = $m['ObjectID'];
			$r = $m["�쥻�Ż�������"];
			$d2r[$o] = $r;
			$r2d[$r] = $d;
			$d2n[$o] = $m['��̾ɽ��'];
		}
		$disease = array();
		foreach ($this->disease as $dis) {
			$d = $dis['Disease'];
			if (!array_key_exists($d, $d2r))
				continue;
			$dis['Rececode'] = $d2r[$d];
			$dis['��̾ɽ��'] = $d2n[$d];
			$disease[] = $dis;
		}
		$this->disease = $disease;
		$this->d2rmap = $d2r;
		$this->r2dmap = $r2d;
	}

	function check_kinki_sub($tbltype, $coltype, $label) {
		$yjtomed = $this->yjtomed;
		$seen = array();
		$maincol = '�٣ʥ�����';
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
SELECT "�٣ʥ�����", "$coltype"
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
					     '�٣ʥ�����' => $yj,
					     $label => $other));
		}
	}

	function check_kinki() {
		$this->check_kinki_sub('MediServe_��§ʻ�Ѷش������ɥޥ�����',
				       '��§�ش��٣ʥ�����',
				       '��§�ش�');
		$this->check_kinki_sub('MediServe_����ʻ�Ѷش������ɥޥ�����',
				       '���жش��٣ʥ�����',
				       '���жش�');
	}

	function check_nissu() {
		$in = array();
		foreach ($this->yjs as $yj)
			$in[] = "'$yj'";
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "�٣ʥ�����", "����̾", "��Ϳ������������", "��Ϳ����������ͳ"
FROM "MediServe_��Ϳ�������¥ޥ�����"
WHERE "�٣ʥ�����" IN ($in)
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$check = array();
		$duration_ceil = 0;
		foreach ($result as $r)
			$check[$r["�٣ʥ�����"]] = $r;
		foreach ($this->med as $m) {
			$o = $m['YJCode'];
			if (!array_key_exists($o, $check))
				continue;
			$r = $check[$o];
			$days = $m['NumDays'];
			if ($duration_ceil < $r['��Ϳ������������'])
				$duration_ceil = $r['��Ϳ������������'];
			if ($days < $r['��Ϳ������������'])
				continue;
			$this->add_err(array('Medicine' => $m['Medicine'],
					     '�٣ʥ�����' => $o,
					     '��Ϳ������������' =>
					     $r["��Ϳ������������"],
					     '��Ϳ����������ͳ' =>
					     $r["��Ϳ����������ͳ"]));
		}
		$this->duration_ceil = $duration_ceil;
	}

	function check_dosage() {
		$in = array();
		foreach ($this->yjs as $yj)
			$in[] = "'$yj'";
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "�٣ʥ�����", "����̾", "��Ϳñ���Ѵ���Ψ", "��Ϳñ��̾��",
    "�����̾��̲�����","�����̾��̾����","����������","������ñ��"
FROM "MediServe_���ͺǹ����̥ޥ�����"
WHERE "�٣ʥ�����" IN ($in)
AND "����������" IS NOT NULL
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$check = array();
		foreach ($result as $r)
			$check[$r["�٣ʥ�����"]] = $r;
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
		$units = explode('\\', $d['��Ϳñ��̾��']);
		$scale = explode('\\', $d['��Ϳñ���Ѵ���Ψ']);
		$limit = $d['����������'];
		$use_scale_mul = 0;
		$use_scale_div = 0;

		if (!$this->cmp_unit_name($unit, $d['������ñ��'])) {
			$use_scale_mul = 1;
			$use_scale_div = 1;
		} else {
			for ($i = 0; $i < count($units); $i++) {
				if (!$this->cmp_unit_name($unit, $units[$i]))
					$use_scale_mul = $scale[$i];
				if (!$this->cmp_unit_name($d['������ñ��'],
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
					     "�٣ʥ�����" => $m['YJCode'],
					     '��Ϳ��ñ��' => $m['DosageUnit'],
					     '��ǽ��Ϳ��ñ��' => $c_units));
			return;
		}
		$limit = ($limit * $use_scale_mul) / $use_scale_div;
		if ($limit < $dosage) {
			$lo = ($d['�����̾��̲�����'] * $use_scale_mul /
			       $use_scale_div);
			$hi = ($d['�����̾��̾����'] * $use_scale_mul /
			       $use_scale_div);
			$this->add_err(array('Medicine' => $m['Medicine'],
					     "�٣ʥ�����" => $m['YJCode'],
					     '����������' => $limit,
					     '�����̾��̲�����' => $lo,
					     '�����̾��̾����' => $hi,
					     '������ñ��' => $unit));
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
SELECT "�٣ʥ�����", "����̾����", "���̲���̾�����ɣ�", "���̲��ش���̾�����ɣ�"
FROM "MediServe_Ŭ����̾���ش���̾�����ɥޥ�����"
WHERE "�٣ʥ�����" IN ($in)
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		foreach ($result as $r)
			$this->check_dismed_one($r, &$dis_need_med_advice);
		foreach ($dis_need_med_advice as $d)
			$this->check_dismed_two($d);
	}

	function check_dismed_one($check, &$dnma) {
		$codes = explode('\\', $check['���̲��ش���̾�����ɣ�']);
		$okcodes = explode('\\', $check['���̲���̾�����ɣ�']);
		$map = array();
		$okmap = array();
		$yj = $check['�٣ʥ�����'];
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
					     '�٣ʥ�����' => $yj,
					     '�ش���̾' => $dis['Disease'],
					     '��̾ɽ��' => $dis['��̾ɽ��']));
		}
		if ($found)
			return;

		if (!count($okmap)) {
			$allowed = array();
		} else {
			$in = implode(', ', $okmap);
			$stmt = <<<SQL
SELECT "ObjectID" AS "Disease", "�쥻�Ż�������", "��̾ɽ��", "��������"
FROM "Medis��̾�ޥ�����"
WHERE "Superseded" IS NULL AND "�쥻�Ż�������" IN ($in)
SQL;
			$allowed = mx_db_fetch_all($this->dbh, $stmt);
		}
		$this->add_err(array('Medicine' => $m,
				     '�٣ʥ�����' => $yj,
				     'Ŭ����̾' => $allowed));


	}

	function check_dismed_two($dis) {
		$d = $dis['Rececode'];
		$stmt = <<<SQL
SELECT M."ObjectID", P."�٣ʥ�����", M."��̾��"
FROM "MediServe_Tweak_��̾Ŭ�������" AS P
JOIN "MediServe_YJ_�쥻�Ż������ɥޥ�����" AS Y
ON   P."�٣ʥ�����" = Y."�٣ʥ�����"
JOIN "Medis�����ʥޥ�����" AS M
ON   M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���" = Y."�쥻�Ż�������"
AND  M."Superseded" IS NULL
WHERE "��ĥ��̾������" = '$d'
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$this->add_err(array('̵������̾' => $dis['Disease'],
				     '��̾ɽ��' => $dis['��̾ɽ��'],
				     'Ŭ�Ѳ�ǽ����' => $result));
	}

	function check_pharmacologic_action() {
		if (!count($this->yjs))
			return;
		$in = array();
		foreach ($this->yjs as $yj)
			$in[] = "'$yj'";
		$in = implode(", ", $in);
		$stmt = <<<SQL
SELECT "�٣ʥ�����", "����̾", "��������1������", "��������2������"
FROM "MediServe_Ʊ��Ʊ���������ɥޥ�����"
WHERE "�٣ʥ�����" IN ($in)
SQL;
		$result = mx_db_fetch_all($this->dbh, $stmt);
		$action = array();
		foreach ($result as $a) {
			$a1 = $a["��������1������"];
			$a2 = $a["��������2������"];
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
				$yj = $act['�٣ʥ�����'];
				$seen[$yj] = 1;
				$dup = array();
				foreach ($yjs as $other) {
					$oyj = $other['�٣ʥ�����'];
					if (array_key_exists($oyj, $seen))
						continue;
					$dup[] = array('Medicine' =>
						       $this->yjtomed[$oyj],
						       '�٣ʥ�����' => $oyj);
				}
				if (!count($dup))
					continue;
				$this->add_err(array('Medicine' =>
						     $this->yjtomed[$yj],
						     '�٣ʥ�����' => $yj,
						     'Ʊ��Ʊ����' => $dup));
			}
		}
	}

	function find_similar($med) {
		$stmt = <<<SQL
SELECT M."ObjectID" AS "Medicine", A."�٣ʥ�����", A."����̾"
FROM "MediServe_Ʊ��Ʊ���������ɥޥ�����" AS AA
JOIN "MediServe_YJ_�쥻�Ż������ɥޥ�����" AS YY
  ON YY."�٣ʥ�����" = AA."�٣ʥ�����"
JOIN "Medis�����ʥޥ�����" AS MM
  ON MM."�쥻�ץ��Ż����������ƥॳ���ɡʣ���" = YY."�쥻�Ż�������"
 AND MM."ObjectID" = $med
JOIN "MediServe_Ʊ��Ʊ���������ɥޥ�����" AS A
  ON A."��������1������" = AA."��������1������"
 AND A."��������2������" = AA."��������2������"
JOIN "MediServe_YJ_�쥻�Ż������ɥޥ�����" AS Y
  ON Y."�٣ʥ�����" = A."�٣ʥ�����"
JOIN "Medis�����ʥޥ�����" AS M
  ON M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���" = Y."�쥻�Ż�������"
 AND M."ObjectID" != $med AND M."Superseded" IS NULL
SQL;
		return mx_db_fetch_all($this->dbh, $stmt);
	}


	function find_document($med) {
		$yj = mx_db_sql_quote($_GET['yj']);
		$stmt = <<<SQL
SELECT A.*
FROM "MediServe_ź��ʸ�����ޥ�����" A
JOIN "Medis�����ʥޥ�����" AS M
  ON M."���̰����ʥ�����" = A."�٣ʥ�����"
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
		      if ($v == '��ɽ���ϡ�') {
			$ret[] = "<table style=\"border: 1px solid\">";
			$intable = TRUE;
		      }else if ($v == '��ɽ��λ��') {
			$ret[] = '</table>';
			$intable = FALSE;
		      }else if ($intable) {
			$row = explode('��', $v);
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
		  $s[] = "�������ޤ�ź��ʸ�����Ͽ����Ƥ��ޤ���";
		return implode("\n", $s);
	}
}
?>
