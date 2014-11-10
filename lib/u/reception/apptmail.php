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
			"����ID", "��", "̾", "�᡼�륢�ɥ쥹",
			"ͽ����", "ͽ�����", "��Ū",
			"ͽ�����", "ͽ��λ����"
		FROM	APPT_LIST_YET_TO_SHOW T
		WHERE	($since IS NULL OR (T."ͽ�����" >= $since))
		AND	($until IS NULL OR
			 ((T."ͽ�����" - INTERVAL '1 DAY') <= $until))
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
			 FROM "����°������" AS PL
			 JOIN "����°���ǡ���" AS PD
			 ON PD."°��" = PL."ObjectID"
			 JOIN "����°��" AS PA
			 ON PD."����°��" = PA."ObjectID"
			 JOIN nagmail_purpose AS NP
			 ON NP.label = PL."̾��"
			 WHERE PL."Superseded" IS NULL
			 AND PA."Superseded" IS NULL
			 AND NP."Superseded" IS NULL
			 AND PA."����" = T.patient_id
			 AND PL."���롼��" = '��ޥ���ɥ᡼��'
			 AND NP.purpose = $purpose
			 AND PD."°����" = '+')
		

		ORDER BY DATE_TRUNC('day', "ͽ�����") DESC,
			 "ͽ����",
			 "ͽ�����",
			 "ͽ��λ����",
			 appt_id

SQL;

	return mx_db_fetch_all($db, $stmt);
}

?>
