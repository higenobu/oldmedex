<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/mykarte/confirm.php';

function confirmation_header() {
	mx_html_head('MyKarte ��Ͽ��ǧ');
	mx_titlespan('MyKarte ��Ͽ��ǧ');
	print "<br /><br /><br />";
}

function main($data) {

	$soe = new mykarte_confirm_edit('reg-');

	confirmation_header();
	if ($soe->commit_ran) {
		$top = $_SERVER['PHP_SELF'];
		$top = preg_replace('/mykarte\/confirm.php\/.*$/', '', $top);
		print "��Ͽ��λ���ޤ�����<br />";
		print "��Ͽ���������꡼��̾��桼��̾�Ȥ��ơ�";
		print "��Ͽ�����ѥ���ɤ� MyKARTE �򤪳ڤ��߲�������<br />";
		print "<a href=\"$top\">������</a>";
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
