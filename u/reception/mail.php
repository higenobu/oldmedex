<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';

function mail_addr($ptOID)
{
	$stmt = 'SELECT "メールアドレス" AS "addr" FROM "患者台帳"
WHERE "Superseded" IS NULL AND "ObjectID" = ' . mx_db_sql_quote($ptOID);
	$db = mx_db_connect();
	$a = mx_db_fetch_single($db, $stmt);
	if ($a)
		$a = $a['addr'];
	return $a;
}
function smp($mailaddr,$mailsubject, $mailcont)
{
//mb_language("japanese");
//mb_internal_encoding("UTF-8");
include_once '/home/medex/PHPMailer_5.2.1/class.phpmailer.php';
// require("class.phpmailer.php");
$mailer = new PHPMailer();
$mailer->IsSMTP();
$mailer->Host = 'ssl://smtp.gmail.com:465';
$mailer->SMTPAuth = TRUE;
$mailer->Username = 'info@mio-pro.com';  // Gmailn
$mailer->Password = 'osakajapan';  // Gmail
$mailer->From     = 'info@mio-pro.com';  // Fromn
/*
$mailer->FromName = mb_encode_mimeheader(mb_convert_encoding("From MM","JIS","UTF-8"));
$mailer->Subject  = mb_encode_mimeheader(mb_convert_encoding($mailsubject,"JIS","UTF-8"));
$mailer->Body     = mb_convert_encoding($mailcont,"UTF-8","auto");
$mailer->AddAddress('matsuo@twinsun.com'); // 
*/
$mailer->FromName =  "From medex";
$mailer->Subject  =  mb_encode_mimeheader($mailsubject);
$mailer->Body     = $mailcont;
//$mailer->Body     = mb_convert_encoding($mailcont,"UTF-8","auto");
$mailer->AddAddress($mailaddr); // 
// $mailer->AddReplyTo($email, $from);
 
if(!$mailer->Send()) {
   echo "Message was not sent<br/ >";
   echo "Mailer Error: " . $mailer->ErrorInfo;
} else {
   echo "Message has been sent";
}



}



function email_notify()
{
	$data = $_REQUEST;
	$idata = mx_get_install_data();

	$ptOID = htmlspecialchars($data["patient_ObjectID"]);
	$ptName = htmlspecialchars($data["patient_Name"]);
	$drName = htmlspecialchars($data["modality_Name"]);
	$atDate = htmlspecialchars($data["apptdate"]);
	$atTime = htmlspecialchars($data["appttime"]);

	$hAddr = htmlspecialchars($idata['HOSPITAL_ADDR']);
	$hName = htmlspecialchars($idata['HOSPITAL_NAME']);
	$hPhone = htmlspecialchars($idata['HOSPITAL_TEL']);

	$drLabel = $data["modality_Type"];

	mx_html_head(NULL);
	$msg = ("$hName からのお知らせです\n\n" .
		"$ptName 様  $drLabel: $drName\n" .
		"予約日時: $atDate $atTime から。\n\n" .
		"[診療予約の注意事項]\n\n" .
		"予約されている時間までにお越しください。\n" .
		"急患の方の診察、検査等の都合により診察時間が遅れる\n" .
		"ことや順番が前後することがありますので御承知ください。\n" .
		"担当医は都合により変更となる場合があります。\n" .
		"容態がすぐれない場合は診療窓口にお申し出ください。\n\n");

	mx_titlespan('診療予約メール通知');

	if (array_key_exists('submit', $_REQUEST)) {
		print "<br />";
		print "(正しいメールアドレスを入力確認して下さい)";
		print "<br />";
		$msg = $_REQUEST['message'];
	}

	print '<form method="POST">';
	mx_formi_textarea('message', $msg,
			  array('cols' => 60, 'rows' => 20,
				'vocab' => array('おだいじに')));
	print '<br />(特記事項あれば上に追記します)<br />';

	$mail = mail_addr($ptOID);
	if (!$mail)
		$mail = '';
	print '電子メールアドレス:';
	mx_formi_text('email', $mail, array('size' => 60));
	print '<br />';
	mx_formi_submit('submit', '送信');
	print '<input type="button" value="画面を閉じる" ';
	print 'onClick="window.parent.close()">';

	foreach (array("patient_ObjectID", "patient_Name", "modality_Name",
		       "apptdate", "appttime") as $c) {
		mx_formi_hidden($c, $data[$c]);
	}
	print '</form>';
	print "</html>\n";
}

if (array_key_exists('submit', $_REQUEST) &&
    array_key_exists('email', $_REQUEST) &&
    ($email = trim($_REQUEST['email'])) != '' &&
    preg_match('/^[-.\w]+@(?:[-\w]+\.)+[\w]+$/', $email)) {
	smp($email,"診療予約通知",$_REQUEST['message']);
/*
	mx_send_mail($email,
		     "診療予約通知",
		     $_REQUEST['message']);
*/

	mx_html_head(NULL);
	mx_titlespan('診療予約メール通知');
	print "<br />メールにて通知しました";
	print "<form>";
	print '<input type="button" value="画面を閉じる" ';
	print 'onClick="window.parent.close()">';
	print "</form>";
	print "</html>\n";
}
else {
	email_notify();
}
?>
