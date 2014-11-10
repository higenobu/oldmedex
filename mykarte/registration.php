<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/mykarte/registration.php';

function registration_header() {
	mx_html_head('MyKarte 登録');
	mx_titlespan('MyKarte 登録');
	print "<br /><br /><br />";
}

function main($data) {

	$soe = new registration_data_edit('reg-');

	registration_header();
	if ($soe->commit_ran) {
		$top = $_SERVER['PHP_SELF'];
		$top = preg_replace('/mykarte\/registration.php$/', '', $top);
		$soe->send_confirm_mail();
		print "登録確認メールを送信します。<br />";
		print "メールにある URL をアクセスすることで、";
		print "登録完了してください。";
	}
	else {
		print "<form method=\"POST\">";
		$soe->draw();
		if ($soe->errmsg == '')
			mx_formi_submit("reg-commit", "登録", "登録", "登録");
		print "</form>";
	}
	if ($soe->logmsg != '') {
		print "<!--\n";
		print $soe->logmsg;
		print "-->\n";
	}
}

main($_REQUEST);
?>
