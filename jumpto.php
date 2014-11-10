<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';

function check_auth() {
	global $_mx_authenticate_browser_hint_name;

	if (!array_key_exists('auth', $_REQUEST))
		return NULL;

	$c = split(':', $_REQUEST['auth']);
	if (!is_array($c) || count($c) != 3)
		return NULL;

	$cookie = $c[0] . ':' . $c[1];
	$bhint = $c[2];
	putenv("URL_PREFIX_COOKIE=$cookie");
	$_COOKIE[$_mx_authenticate_browser_hint_name] = $bhint;
	$cookie = array($cookie, $bhint);
	$u = mx_authenticate_user('do not redirect');
	if (is_null($u))
		return NULL;

	return array($cookie, $u);
}


function check_application($cookie, $u) {
	$app = '';
	if (array_key_exists('application', $_REQUEST))
		$app = trim($_REQUEST['application']);
	if ($app == '') {
		$app = mx_authenticate_user_default_application($cookie);
	}
	if (substr($app, 0, 1) == '/')
		$app = substr($app, 1);
	$apps = mx_find_application($u, 'A');
	foreach ($apps as $cat => $ac) {
		if (!is_array($ac))
			continue;
		foreach ($ac as $d) {
			$path = $d['path'];
			if ($path == $app)
				return $d;
		}
	}
	return NULL;
}

function jumpto() {
	$cookie = check_auth();
	if (is_null($cookie))
		return NULL;
	$u = $cookie[1];
	$cookie = $cookie[0];
	$d = check_application($cookie, $u);
	if (is_null($d)) {
		print "unauthorized application\n";
		return NULL;
	}
	$target = '/' . $d['path'];
	$pt = '';
	if (array_key_exists('patient', $_REQUEST))
		$pt = trim($_REQUEST['patient']);
	if ($pt != '') {
		$pt = htmlspecialchars($pt);
		switch ($d['ppa']) {
		default:
			$pid = '';
			break;
		case 'F':
			$pid = '?PID=' . $pt;
			break;
		case 'O': case 'Y':
			$pid = '?SetPatient=1&PatientID=' . $pt;
			switch (trim($_REQUEST['action'])) {
			default:
				break;
			case 'new':
				$pid .= '&NewSOEObject=1';
				break;
			}
			break;
		}
		$target .= $pid;
	}
	mx_authenticate_redirect_to($target, $cookie);
	return 1;
}

function show_cookie($cookie) {
	$bhint = $cookie[1];
	$cookie = $cookie[0];

	if (!$_REQUEST['debug']) {
		print "$cookie:$bhint\n";
		return;
	}

	print '<html><body><form method="GET" action="/jumpto.php">';
	mx_formi_text('auth', "$cookie:$bhint");
	mx_formi_text('application', '');
	mx_formi_text('patient', '');
	mx_formi_submit('submit', 'submit');
	print "</form></body></html>\n";
}

function main() {
	if (jumpto())
		return;
	if (array_key_exists('username', $_POST) &&
	    array_key_exists('password', $_POST)) {
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$cookie = mx_authenticate_login($username, $password);
		if (! is_null($cookie)) {
			show_cookie($cookie);
		} else {
			print "bad username or password\n";
		}
	} else {
		print '<html><body><form method="POST" action="/jumpto.php">';
		mx_formi_text('username', '');
		mx_formi_password('password', '');
		mx_formi_submit('submit', 'submit');
		mx_formi_checkbox('debug', 0);
		print "</form></body></html>\n";
	}
}

main();

?>
