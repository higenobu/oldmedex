<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/mykarte/registration.php';

function registration_header() {
	mx_html_head('MyKarte ��Ͽ');
	mx_titlespan('MyKarte ��Ͽ');
	print "<br /><br /><br />";
}

function main($data) {

	$soe = new registration_data_edit('reg-');

	registration_header();
	if ($soe->commit_ran) {
		$top = $_SERVER['PHP_SELF'];
		$top = preg_replace('/mykarte\/registration.php$/', '', $top);
		$soe->send_confirm_mail();
		print "��Ͽ��ǧ�᡼����������ޤ���<br />";
		print "�᡼��ˤ��� URL �򥢥��������뤳�Ȥǡ�";
		print "��Ͽ��λ���Ƥ���������";
	}
	else {
		print "<form method=\"POST\">";
		$soe->draw();
		if ($soe->errmsg == '')
			mx_formi_submit("reg-commit", "��Ͽ", "��Ͽ", "��Ͽ");
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
