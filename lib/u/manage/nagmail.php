<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

function _lib_u_manage_nagmail_find_template($purpose, $id=NULL)
{
	$db = mx_db_connect();
	$purpose = mx_db_sql_quote($purpose);
	if ($id) {
		$id = 'AND T."ObjectID" = ' . mx_db_sql_quote($id);
	} else {
		$id = "";
	}
	$stmt = <<<SQL
		SELECT T."ObjectID" AS template, T.subject, T.body
		FROM nagmail_template AS T
		JOIN nagmail_purpose AS P
		ON   P."ObjectID" = T.purpose
		WHERE P.purpose = $purpose
		AND T."Superseded" IS NULL
		AND P."Superseded" IS NULL $id
		ORDER BY T.sortorder
SQL;
	return mx_db_fetch_all($db, $stmt);
}

function _lib_u_manage_nagmail_apply_template($template, $data)
{
	$result = '';
	while (1) {
		$m = array();
		if (!preg_match('/^(?s)(.*?)%\(([^)]+?)\)(.*)$/',
				$template, &$m))
			break;
		$result .= $m[1];
		$token = $m[2];
		$template = $m[3];
		if (array_key_exists($token, $data))
			$result .= $data[$token];
		else
			$result .= "%(<<$token>>)";
	}
	$result .= $template;
	return $result;
}

/*
 * Fill the subject, body from the data, send nagmail and then
 * record the fact that a nagmail has already been sent for the
 * row in question to avoid duplicated sending of the same topic.
 *
 * Purpose:    nagmail_purpose."ObjectID"
 * Key1, Key2: keys (arbitrary assigned by the application that handles
 *             the given Purpose) to note that the message is sent in
 *             response to the row identified by these keys.
 *
 * Template:   nagmail_template."ObjectID"
 * Addressee:  recipient e-mail address string
 * Subject:    string, could be different from nagmail_template.subject
 *             if the application allows tweaking of the template before
 *             sending.
 * Body:       string, similar to Subject above but for "body".
 * Data:       an array to fill in the template with.
 */
function _lib_u_manage_nagmail_send_nagmail($db, $purpose, $key1, $key2,
					    $template, $addressee,
					    $subject, $body,
					    $data)
{
	/* Use $data to populate the text */
	$subject = _lib_u_manage_nagmail_apply_template($subject, $data);
	$body = _lib_u_manage_nagmail_apply_template($body, $data);

	/*
	 * Send it, but exclude test email addresses.
	 */
	$a = explode('@', $addressee);
	if (!$a || count($a) < 2 ||
	    $a[1] == "dom.ain" ||
	    $a[1] == "example.com")
		;
	else
//		mx_send_mail($addressee, $subject, $body);
//1010-2012
		mx_smp($addressee, $subject, $body);
//1010-2012
	/* Get purpose code */
	$purpose = mx_db_sql_quote($purpose);
	$stmt = <<<SQL
		SELECT "ObjectID" FROM nagmail_purpose
		WHERE "Superseded" IS NULL
		AND purpose = $purpose
SQL;
	$p = mx_db_fetch_single($db, $stmt);
	$p = $p['ObjectID'];

	/* Mark them as sent */
	$k1 = mx_db_sql_quote($key1);
	$k2 = mx_db_sql_quote($key2);
	$t = mx_db_sql_quote($template);
	$p = mx_db_sql_quote($p);
	$a = mx_db_sql_quote($addressee);
	$s = mx_db_sql_quote($subject);
	$b = mx_db_sql_quote($body);
	$stmt = "
	INSERT INTO nagmail
	(sent, purpose, template, addressee, key1, key2, subject, body)
	 values (current_timestamp, $p, $t, $a, $k1, $k2, $s, $b)";

	pg_query($db, $stmt);
}
?>
