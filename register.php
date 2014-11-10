<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

function already_registered() {
	print "製品はすでに登録されています<br />";
	print "<a href=\"/login.php\">ログイン</a>";
}

function register_product($check) {
	global $_mx_registration_key_expires;

	$state = mx_register_product($_REQUEST);
	if (! $state) {
		print "御登録ありがとうございました<br />";
		print "<a href=\"/login.php\">ログイン</a>";
		return;
	}

	if ($check && !is_null($check['Renewal'])) {
		$what = '更新';
		print "連続して使用するには、登録更新が必要です。";
	} else {
		global $_mx_trial_use_period;

		$what = '登録';
		if ($_mx_trial_use_period)
			$how = "試用期間を越えて使用するには、";
		else
			$how = '';
		print "${how}製品登録が必要です。";
	}

	print "<br />";
	print "製品キーコードを購入の上で、このページから登録して下さい。";
	print "<br />";
	print "製品番号:";
	print $check['Cookie'];
	print "<br /><br />";

	if ($state == 1) {
		$what = "正しい製品キーコード";
		if ($_mx_registration_key_expires)
			$what .= "と有効期限";
		print "${what}を入力して下さい<br />";
	}
	mx_titlespan('製品キーコードの入力');
	print "<form class=\"login-submit\" method=\"POST\">";
	for ($i = 0; $i < 6; $i++) {
		if ($i) {
			print "&nbsp;";
		}
		print "<input type=\"text\" name=\"key$i\" size=\"5\">";
	}
	print "<br />";
	if ($_mx_registration_key_expires) {
		mx_titlespan('有効期限');
		print "<br />";
		print "<input type=\"text\" name=\"expiration\" size=\"8\">";
		print "<br />";
	}
	mx_formi_submit('submit', $what, $what, $what);
	print "</form>";
}

mx_html_head('製品登録');
print "<br /><br /><br />";

$check = mx_product_expiry_check();
if ($check && $check['Registered'] &&
    (is_null($check['Renewal']) || 30 < $check['Renewal'])) {
    already_registered();
} else {
    register_product($check);
}
?>
