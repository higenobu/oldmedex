<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';

function mail_addr($ptOID)
{
	$stmt = 'SELECT "�᡼�륢�ɥ쥹" AS "addr" FROM "������Ģ"
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
	$msg = ("$hName ����Τ��Τ餻�Ǥ�\n\n" .
		"$ptName ��  $drLabel: $drName\n" .
		"ͽ������: $atDate $atTime ���顣\n\n" .
		"[����ͽ�����ջ���]\n\n" .
		"ͽ�󤵤�Ƥ�����֤ޤǤˤ��ۤ�����������\n" .
		"�޴������οǻ������������Թ�ˤ��ǻ����֤��٤��\n" .
		"���Ȥ���֤����夹�뤳�Ȥ�����ޤ��ΤǸ澵�Τ���������\n" .
		"ô������Թ�ˤ���ѹ��Ȥʤ��礬����ޤ���\n" .
		"���֤�������ʤ����Ͽ�������ˤ������Ф���������\n\n");

	mx_titlespan('����ͽ��᡼������');

	if (array_key_exists('submit', $_REQUEST)) {
		print "<br />";
		print "(�������᡼�륢�ɥ쥹�����ϳ�ǧ���Ʋ�����)";
		print "<br />";
		$msg = $_REQUEST['message'];
	}

	print '<form method="POST">';
	mx_formi_textarea('message', $msg,
			  array('cols' => 60, 'rows' => 20,
				'vocab' => array('����������')));
	print '<br />(�õ����ढ��о���ɵ����ޤ�)<br />';

	$mail = mail_addr($ptOID);
	if (!$mail)
		$mail = '';
	print '�Żҥ᡼�륢�ɥ쥹:';
	mx_formi_text('email', $mail, array('size' => 60));
	print '<br />';
	mx_formi_submit('submit', '����');
	print '<input type="button" value="���̤��Ĥ���" ';
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
	smp($email,"����ͽ������",$_REQUEST['message']);
/*
	mx_send_mail($email,
		     "����ͽ������",
		     $_REQUEST['message']);
*/

	mx_html_head(NULL);
	mx_titlespan('����ͽ��᡼������');
	print "<br />�᡼��ˤ����Τ��ޤ���";
	print "<form>";
	print '<input type="button" value="���̤��Ĥ���" ';
	print 'onClick="window.parent.close()">';
	print "</form>";
	print "</html>\n";
}
else {
	email_notify();
}
?>
