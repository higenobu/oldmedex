<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';

////////////////////////////////////////////////////////////////
// Fetch doctors in charge of the patient
function mx_find_dr_for_patient($patientID, $must_be_modality=1) {

	$db = mx_db_connect();
	$patientID = mx_db_sql_quote($patientID);

	$stmt = <<<SQL
		SELECT E."ObjectID", (E."姓" || E."名") AS "姓名"
		FROM "患者担当職員" AS T
		JOIN "患者担当職員データ" AS D
		ON T."ObjectID" = D."患者担当職員" AND T."Superseded" IS NULL
		JOIN "職員台帳" AS E
		ON E."ObjectID" = D."職員"
		JOIN "患者台帳" AS P
		ON P."ObjectID" = T."患者"
SQL;
	if ($must_be_modality) {
		$stmt .= <<<SQL
			JOIN modalities_to_medex_employee M
			ON M.employee = E."ObjectID"
SQL;
	}
	$stmt .= <<<SQL
		WHERE P."ObjectID" = $patientID
SQL;

	print "<!-- $stmt\n\n";
	$r = pg_fetch_all(pg_query($db, $stmt));
	var_dump($r);
	print "-->\n";
	return $r;
}

////////////////////////////////////////////////////////////////
// Fetch data for patient information
function mx_find_patient_by_patient_id($patientID) {
  $stmt = ('SELECT "ObjectID" FROM "患者台帳" WHERE "Superseded" IS NULL
	    AND "患者ID" = ' . mx_db_sql_quote($patientID));
  $d = mx_db_fetch_single(mx_db_connect(), $stmt);
  if (! is_null($d)) {
    return $d['ObjectID'];
  }
  return NULL;
}

////////////////////////////////////////////////////////////////
// Boilerplate patient information

function mx_draw_patientinfo_get_data($ObjectID, $options=NULL)
{
  $q = mx_db_sql_quote($ObjectID);

  $db = mx_db_connect();
  $stmt = ('SELECT "患者ID","フリガナ", 
	    ("姓" || \' \' || "名") as "氏名",
	    (CASE WHEN "性別" = \'M\' THEN \'Male\'
	     WHEN "性別" = \'F\' THEN \'Female\'
	     ELSE \'不明\' END) as "性別",
	     "加入電話", "携帯電話", "住所0", "住所1", "住所2", "住所3", "住所4","生年月日"
	    FROM "患者台帳"
	    WHERE "Superseded" IS NULL AND "ObjectID" = ' . $q);

  $a = pg_fetch_array(pg_query($db, $stmt));

  if (mx_check_option('ShowRoomPref', $options)) {
	  $stmt = <<<SQL
		SELECT RPD."患者", R."病室名"
		FROM "病室患者表" AS RP
		JOIN "病室患者データ" AS RPD
		ON RP."ObjectID" = RPD."病室患者表"
		JOIN "病室一覧表" AS R
		ON R."ObjectID" = RP."病室"
		WHERE
		RP."Superseded" IS NULL AND
		RPD."患者" = $q
		ORDER BY RP."日付" DESC, RPD."患者"
SQL;
	  $r = pg_fetch_array(pg_query($db, $stmt));
	  if ($r && is_array($r))
		  $a['病室名'] = sprintf("(%s)", $r['病室名']);
	  else
		  $a['病室名'] = '';
  }

  if (1) {
    $stmt = <<<SQL
      SELECT orca_insurance_uid, "保険種別", "保険者番号", 
       "被保険者", "被保険者手帳の記号", "被保険者手帳の番号",
       "負担割合"
FROM insurance
      WHERE patient = $q
SQL;
    $a['insurances'] = mx_db_fetch_all($db, $stmt);
  }
  return $a;
}
//0415-2014
function mx_draw_patientinfo_get_data2($ObjectID, $options=NULL)
{
  $q = mx_db_sql_quote($ObjectID);

  $db = mx_db_connect();
  $stmt = ('SELECT "患者ID","フリガナ", 
	    ("姓" || \' \' || "名") as "氏名",
	    (CASE WHEN "性別" = \'M\' THEN \'Male\'
	     WHEN "性別" = \'F\' THEN \'Female\'
	     ELSE \'不明\' END) as "性別",
	     "加入電話", "携帯電話", "住所0", "住所1", "住所2", "住所3", "住所4","生年月日",tel,country
	    FROM "患者台帳"
	    WHERE "Superseded" IS NULL AND "ObjectID" = ' . $q);

  $a = pg_fetch_array(pg_query($db, $stmt));

   
  return $a;
}

//
function mx_draw_patientinfo_custom($ObjectID, $show)
{
  $d = mx_draw_patientinfo_get_data($ObjectID);
  $show_cnt = count($show[0]);
  print "<table class=\"tabular-data\">";
  foreach ($show as $a) {
    print "<tr>";
    for ($i = 0; $i < $show_cnt; $i++) {
	    print "<th>" . htmlspecialchars($a[$i]) . "</th><td>";
	    if (array_key_exists($a[$i], $d)) {
		    print htmlspecialchars($d[$a[$i]]);
	    }
	    else {
		    print "&nbsp;";
	    }
	    print "</td>";
    }
    print "</tr>\n";
  }
  print "</table>\n";
  return $d;
}

////////////////////////////////////////////////////////////////
// Boilerplate patient information

function mx_draw_patientinfo_brief($ObjectID)
{
  global $__uiconfig_patientinfo_brief_show, $_mx_bmd_layout;
  if ($_mx_bmd_layout) {
	  global $_mx_show_room_patient_info;
	  $option = array();
	  if ($_mx_show_room_patient_info)
		  $option['ShowRoomPref'] = 1;
	  return mx_draw_patientinfo_bmd($ObjectID, &$option);
  }
  return mx_draw_patientinfo_custom($ObjectID,
				    $__uiconfig_patientinfo_brief_show);
}
//0325-2013
function mx_draw_patientinfo_bmd($ObjectID, $options=NULL)
{
  $d = mx_draw_patientinfo_get_data($ObjectID, $options);

  $mark = $d['患者マーク'];

  print "<table class=\"tabular-data\">";
//0909-2013 
  print "<tr><th>PatientID</th><td>";
  print htmlspecialchars($d['患者ID']);
  print "</td><th>DOB</th><td>";
//1006-2013
$x=sprintf("%02d/%02d/%04d",
		
		 substr($d["生年月日"],5,2),
		substr($d["生年月日"],8,2),
		substr($d["生年月日"],0,4));
//  $x = $d["生年月日"];
  if(mx_check_option('Culture', $options) == 'Japanese')
    $x = mx_wareki($x);
//1006-2013
  $x .=' (' . mx_calc_age($d["生年月日"]) . ':AGE)';
  print htmlspecialchars($x);
  print "</td><th rowspan=\"2\" style=\"vertical-align: middle\">NAME</th>";
  print "<td rowspan=\"2\" style=\"vertical-align: middle; ";
  print "font-size: 150%; font-weight: bold; \">";
  print htmlspecialchars($d['氏名']);
  print "</td>\n";

  if ($mark) {
	  print "<td rowspan=\"2\" style=\"vertical-align: middle; ";
	  print "font-size: 150%; font-weight: bold; \">";
	  print "${mark}</td>";
  }

  if (is_array($d['insurances']) && count($d['insurances']) > 0) {
    print "<td><select name=\"orca_insurance_uid\">";
    foreach($d['insurances'] as $ins) {
      printf("<option value=\"%s\">%s %s %s割</option>",
	     $ins['orca_insurance_uid'],
	     $ins["保険種別"],
	     $ins["被保険者"] == '2' ? '家族' : '本人',
	     is_null($ins['負担割合']) ? "--" : $ins['負担割合']);
    }
    print "</select></td>";
  }
  print "</tr>";   

  print "<tr><th>SEX</th><td>";
  print htmlspecialchars($d['性別']);
   
  print "</tr>\n";

  print "</table>\n";
}
?>
