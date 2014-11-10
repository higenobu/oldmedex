<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';

////////////////////////////////////////////////////////////////
// Fetch doctors in charge of the patient
function mx_find_dr_for_patient($patientID, $must_be_modality=1) {

	$db = mx_db_connect();
	$patientID = mx_db_sql_quote($patientID);

	$stmt = <<<SQL
		SELECT E."ObjectID", (E."��" || E."̾") AS "��̾"
		FROM "����ô������" AS T
		JOIN "����ô�������ǡ���" AS D
		ON T."ObjectID" = D."����ô������" AND T."Superseded" IS NULL
		JOIN "������Ģ" AS E
		ON E."ObjectID" = D."����"
		JOIN "������Ģ" AS P
		ON P."ObjectID" = T."����"
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
  $stmt = ('SELECT "ObjectID" FROM "������Ģ" WHERE "Superseded" IS NULL
	    AND "����ID" = ' . mx_db_sql_quote($patientID));
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
  $stmt = ('SELECT "����ID","�եꥬ��", 
	    ("��" || \' \' || "̾") as "��̾",
	    (CASE WHEN "����" = \'M\' THEN \'Male\'
	     WHEN "����" = \'F\' THEN \'Female\'
	     ELSE \'����\' END) as "����",
	     "��������", "��������", "����0", "����1", "����2", "����3", "����4",birth
	    FROM "������Ģ"
	    WHERE "Superseded" IS NULL AND "ObjectID" = ' . $q);

  $a = pg_fetch_array(pg_query($db, $stmt));

  if (mx_check_option('ShowRoomPref', $options)) {
	  $stmt = <<<SQL
		SELECT RPD."����", R."�¼�̾"
		FROM "�¼�����ɽ" AS RP
		JOIN "�¼����ԥǡ���" AS RPD
		ON RP."ObjectID" = RPD."�¼�����ɽ"
		JOIN "�¼�����ɽ" AS R
		ON R."ObjectID" = RP."�¼�"
		WHERE
		RP."Superseded" IS NULL AND
		RPD."����" = $q
		ORDER BY RP."����" DESC, RPD."����"
SQL;
	  $r = pg_fetch_array(pg_query($db, $stmt));
	  if ($r && is_array($r))
		  $a['�¼�̾'] = sprintf("(%s)", $r['�¼�̾']);
	  else
		  $a['�¼�̾'] = '';
  }

  if (1) {
    $stmt = <<<SQL
      SELECT orca_insurance_uid, "�ݸ�����", "�ݸ����ֹ�", 
       "���ݸ���", "���ݸ��Լ�Ģ�ε���", "���ݸ��Լ�Ģ���ֹ�",
       "��ô���"
FROM insurance
      WHERE patient = $q
SQL;
    $a['insurances'] = mx_db_fetch_all($db, $stmt);
  }
  return $a;
}

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

  $mark = $d['���ԥޡ���'];

  print "<table class=\"tabular-data\">";
//0909-2013 
  print "<tr><th>PatientID</th><td>";
  print htmlspecialchars($d['����ID']);
  print "</td><th>DOB</th><td>";
//1006-2013
  $x = $d['birth'];
  if(mx_check_option('Culture', $options) == 'Japanese')
    $x = mx_wareki($x);
//1006-2013
  $x .=' (' . mx_calc_age1($d['birth']) . ':AGE)';
  print htmlspecialchars($x);
  print "</td><th rowspan=\"2\" style=\"vertical-align: middle\">NAME</th>";
  print "<td rowspan=\"2\" style=\"vertical-align: middle; ";
  print "font-size: 150%; font-weight: bold; \">";
  print htmlspecialchars($d['��̾']);
  print "</td>\n";

  if ($mark) {
	  print "<td rowspan=\"2\" style=\"vertical-align: middle; ";
	  print "font-size: 150%; font-weight: bold; \">";
	  print "${mark}</td>";
  }

  if (is_array($d['insurances']) && count($d['insurances']) > 0) {
    print "<td><select name=\"orca_insurance_uid\">";
    foreach($d['insurances'] as $ins) {
      printf("<option value=\"%s\">%s %s %s��</option>",
	     $ins['orca_insurance_uid'],
	     $ins["�ݸ�����"],
	     $ins["���ݸ���"] == '2' ? '��²' : '�ܿ�',
	     is_null($ins['��ô���']) ? "--" : $ins['��ô���']);
    }
    print "</select></td>";
  }
  print "</tr>";   

  print "<tr><th>SEX</th><td>";
  print htmlspecialchars($d['����']);
   
  print "</tr>\n";

  print "</table>\n";
}
?>
