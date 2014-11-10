<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';

//0603-2013
 

$_reason_string = array
('session-expire' => 'logout',
 );
if (get_magic_quotes_gpc()) {
  foreach (array('username', 'password', 'passthru_target', 'reason') as $v) {
    if (array_key_exists($v, $_REQUEST)) {
      $_REQUEST[$v] = stripslashes($_REQUEST[$v]);
    }
  }
}
if (array_key_exists('username', $_REQUEST) &&
    array_key_exists('password', $_REQUEST)) {
  // The conversion is not that useful for password fields, since
  // the user cannot see what's being typed and vowels immediately
  // turns into Japanese, not staying in double-width ASCII.
  $username = $_REQUEST['username'];
  if ($_mx_narrow_username)
	  $username = mb_convert_kana($username, 'as', 'euc');
  $password = $_REQUEST['password'];
  $cookie = mx_authenticate_login($username, $password);
  if (! is_null($cookie)) {
    if (array_key_exists('passthru_target', $_REQUEST)) {
      mx_authenticate_redirect_to($_REQUEST['passthru_target'],
				  $cookie);
    } else {
      mx_authenticate_redirect_to_user_top($username, $cookie);
    }
    return;
  }
  if ($_REQUEST['username']) {
    $reason_string = 'Wrong password';
 

  } else {
    $reason_string = 'please login';
  }
}
/* 11-05-2014
elseif (array_key_exists('reason', $_REQUEST)) {
  $reason_string = $_reason_string[$_REQUEST['reason']];
}
$url = $_REQUEST['img_url'];
if (!$url) {
	if ($_COOKIE['top_image_url']) {
		$url = $_COOKIE['top_image_url'];
	}
	else {
		$url = $_mx_logo_url;
	}
}
if ($_COOKIE['top_image_url'] != $url) {
	if ($url == $_mx_logo_url)
//	0409-2012	setcookie('top_image_url', '');
	setcookie('top_image_url', '',0);
	else
	setcookie('top_image_url', '',0);
//	0409-2012	setcookie('top_image_url', $url, time() + 60*60*24*30, '/');
}

if ($_mx_product_name == 'M3') {
	setcookie('m3', '', 0);
}
*/

mx_html_head('login');
?>
<body>
<!-- Rev.  Wed Mar 2 10:18:34 2011 -0800 6272bfb -->
<div class="login-logo">
<? if($url)
     print "<img src=\"$url\">";
?>
<form method="POST">
<?php if ($reason_string) {
    print htmlspecialchars($reason_string);
    print "<br />\n";
}
    $check = mx_product_expiry_check();
    if (!$check && !$_mx_trial_use_begins_with_first_login) {
	mx_product_activate();
	$check = mx_product_expiry_check();
    }

    $usage = NULL;
    $remain = 999;
    if ($check) {
	    if (!$check['Registered']) {
		    $usage = '試用期間';
		    $remain = $check['Remaining'];
		    $url = "/register.php";
		    $action = '製品登録';
	    } else if (!is_null($check['Renewal']) &&
		       $check['Renewal'] <= 30) {
		    $usage = '使用ライセンス期間';
		    $remain = $check['Renewal'];
		    $url = "/register.php";
		    $action = '登録更新';
	    }
    }
    if ($remain > 0) {
	    print <<<HTML
<table>
<tr><th><p align="right"><font size="4">User：</font></p></th><td>
HTML;
	    mx_formi_text('username', $username);
	    print <<<HTML
</td></tr>
<tr><th><p align="right"><font size="4">Password：</font></p></th><td>
HTML;
	    mx_formi_password('password', '', array('ime' => 'disabled'));
	    print <<<HTML
</td></tr>
<tr><td colspan="2" style="text-align: center" class="login-submit">
HTML;
	    mx_formi_login_submit();
    }

    if ($usage) {
	    global $_mx_trial_use_period;
	    print "<br />";
	    if ($remain > 0) {
		    print "${usage}残り ";
		    print $remain;
		    print " 日です";
	    } else if ($check['Registered'] || $_mx_trial_use_period) {
		    print "${usage}を終了しました。";
	    }
	    print"<br /><a href=\"$url\">$action</a>\n";
    }
    if ($check) {
	    print "</br>";
	    print "製品番号:";
	    print $check['Cookie'];
	    print "</br>";
    }

    if ($_mx_product_name == 'MYKARTE') {
	    print "<br />ユーザ登録は";
	    print '<a href="mykarte/registration.php">こちら</a>';
	    print "\n";
    }
?>
</td></tr>
</table>
<br>
<br>
<br>
<center>
Use Firefox 
<a href="http://www.mozilla-japan.org/products/download.html">download</a><br>
 
</center>



</form>

</div>
</body>
</html>
