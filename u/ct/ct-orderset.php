<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/appbar.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/ct/ct.php';

class phoney_ct_orderset_class {
	function phoney_ct_orderset_class($u) {
		$this->u = $u;
	}
	function edit_in_progress() {
		return 0; /* NEEDSWORK */
	}
}

$u = $_REQUEST['u'] = mx_authenticate_user();
$auth = mx_authorization();
/*
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}
*/


$rx_order_application = '/u/ct/ct-rx-order.php';
$test_order_application = '/u/ct/ct-schedule.php';
$target = '?CTID=' . $_REQUEST['CTID'];
$rx_app_goto = ('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
		$rx_order_application .
		$target);

$test_app_goto = ('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
		  $test_order_application .
		  $target);

$me = new phoney_ct_orderset_class($u);
mx_html_head("治験一括オーダー");
mx_titlespan($auth[1], 'appname');
mx_draw_userinfo();
mx_appbar($me);



print "<hr>";

print "被験者全員に一括でオーダーする機能を選択してください。<br>";
print '<br>';
print '<a href="' . $rx_app_goto . '">投薬一括オーダー</a>';
print '<br>';
print '<br>';
print '<br>';
print '<a href="'.$test_app_goto.'">検体検査一括オーダー</a>';
print '<br>';

?>
