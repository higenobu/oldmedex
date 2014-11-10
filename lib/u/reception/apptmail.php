<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

function appt_list_yet_to_show($db, $since, $until, $purpose)
{
	if (!$db)
		$db = mx_db_connect();

	$since = mx_db_sql_quote($since);
	$until = mx_db_sql_quote($until);
	$purpose = mx_db_sql_quote($purpose);
	$stmt = <<<SQL
		SELECT	appt_id, rsched_id, modality_id, patient_id,
			"´µ¼ÔID", "À«", "Ì¾", "¥á¡¼¥ë¥¢¥É¥ì¥¹",
			"Í½ÌóÀè", "Í½Ìó¼ïÊÌ", "ÌÜÅª",
			"Í½Ìó»þ¹ï", "Í½Ìó½ªÎ»»þ¹ï"
		FROM	APPT_LIST_YET_TO_SHOW T
		WHERE	($since IS NULL OR (T."Í½Ìó»þ¹ï" >= $since))
		AND	($until IS NULL OR
			 ((T."Í½Ìó»þ¹ï" - INTERVAL '1 DAY') <= $until))
		AND	NOT EXISTS
			(SELECT NULL
			 FROM nagmail AS N
			 JOIN nagmail_purpose AS NP
			 ON NP."ObjectID" = N.purpose
			 AND NP.purpose = $purpose
			 WHERE T.rsched_id = N.key1
			 AND N.sent IS NOT NULL)
		AND	EXISTS
			(SELECT NULL
			 FROM "´µ¼ÔÂ°À­°ìÍ÷" AS PL
			 JOIN "´µ¼ÔÂ°À­¥Ç¡¼¥¿" AS PD
			 ON PD."Â°À­" = PL."ObjectID"
			 JOIN "´µ¼ÔÂ°À­" AS PA
			 ON PD."´µ¼ÔÂ°À­" = PA."ObjectID"
			 JOIN nagmail_purpose AS NP
			 ON NP.label = PL."Ì¾¾Î"
			 WHERE PL."Superseded" IS NULL
			 AND PA."Superseded" IS NULL
			 AND NP."Superseded" IS NULL
			 AND PA."´µ¼Ô" = T.patient_id
			 AND PL."¥°¥ë¡¼¥×" = '¥ê¥Þ¥¤¥ó¥É¥á¡¼¥ë'
			 AND NP.purpose = $purpose
			 AND PD."Â°À­ÃÍ" = '+')
		

		ORDER BY DATE_TRUNC('day', "Í½Ìó»þ¹ï") DESC,
			 "Í½ÌóÀè",
			 "Í½Ìó»þ¹ï",
			 "Í½Ìó½ªÎ»»þ¹ï",
			 appt_id

SQL;

	return mx_db_fetch_all($db, $stmt);
}

?>
