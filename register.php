<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

function already_registered() {
	print "���ʤϤ��Ǥ���Ͽ����Ƥ��ޤ�<br />";
	print "<a href=\"/login.php\">������</a>";
}

function register_product($check) {
	global $_mx_registration_key_expires;

	$state = mx_register_product($_REQUEST);
	if (! $state) {
		print "����Ͽ���꤬�Ȥ��������ޤ���<br />";
		print "<a href=\"/login.php\">������</a>";
		return;
	}

	if ($check && !is_null($check['Renewal'])) {
		$what = '����';
		print "Ϣ³���ƻ��Ѥ���ˤϡ���Ͽ������ɬ�פǤ���";
	} else {
		global $_mx_trial_use_period;

		$what = '��Ͽ';
		if ($_mx_trial_use_period)
			$how = "���Ѵ��֤�ۤ��ƻ��Ѥ���ˤϡ�";
		else
			$how = '';
		print "${how}������Ͽ��ɬ�פǤ���";
	}

	print "<br />";
	print "���ʥ��������ɤ�����ξ�ǡ����Υڡ���������Ͽ���Ʋ�������";
	print "<br />";
	print "�����ֹ�:";
	print $check['Cookie'];
	print "<br /><br />";

	if ($state == 1) {
		$what = "���������ʥ���������";
		if ($_mx_registration_key_expires)
			$what .= "��ͭ������";
		print "${what}�����Ϥ��Ʋ�����<br />";
	}
	mx_titlespan('���ʥ��������ɤ�����');
	print "<form class=\"login-submit\" method=\"POST\">";
	for ($i = 0; $i < 6; $i++) {
		if ($i) {
			print "&nbsp;";
		}
		print "<input type=\"text\" name=\"key$i\" size=\"5\">";
	}
	print "<br />";
	if ($_mx_registration_key_expires) {
		mx_titlespan('ͭ������');
		print "<br />";
		print "<input type=\"text\" name=\"expiration\" size=\"8\">";
		print "<br />";
	}
	mx_formi_submit('submit', $what, $what, $what);
	print "</form>";
}

mx_html_head('������Ͽ');
print "<br /><br /><br />";

$check = mx_product_expiry_check();
if ($check && $check['Registered'] &&
    (is_null($check['Renewal']) || 30 < $check['Renewal'])) {
    already_registered();
} else {
    register_product($check);
}
?>
