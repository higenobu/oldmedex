<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

function body($u) {
  global $_mx_min_length_of_employee_pw;

  if (array_key_exists('password0', $_REQUEST) &&
      array_key_exists('password1', $_REQUEST) &&
      array_key_exists('password2', $_REQUEST)) {
    $db = mx_db_connect();
    $err = "";
    if ($_REQUEST['password1'] != $_REQUEST['password2'])
      $err .= "２つのパスワードが一致しません\n";
    elseif (strlen($_REQUEST['password1']) < $_mx_min_length_of_employee_pw)
      // ... also in lib/u/manage/employee.php::_validate()
      $err .= "パスワードは $_mx_min_length_of_employee_pw 文字以上でないといけません\n";
    $rlt = mx_db_fetch_single($db,
			      "SELECT username, passhash FROM mx_authenticate
			       WHERE userid = $u");
    if (! is_array($rlt))
      $err .= "パスワード表をアクセスできません\n";
    else {
      $hm = mx_authenticate_hmac($rlt['username'] . ':' .
				 $_REQUEST['password0']);
      if ($hm != $rlt['passhash'])
	$err .= "現在のパスワードが一致しません\n";
    }
    if (! $err) {
      $hm = mx_db_sql_quote(mx_authenticate_hmac($rlt['username'] . ':' .
						 $_REQUEST['password1']));
      if (! pg_query("UPDATE mx_authenticate SET passhash = $hm
		      WHERE userid = $u"))
	$err .= pg_last_error($db);
    }
    if ($err) {
      print "以下のエラーにより変更できません。\n";
      print mx_html_paragraph($err);
    }
    else {
      mx_authenticate_logout();
      print '<span class="heading">';
      print "パスワードを変更しました。ログインしなおして下さい。";
      print '</span>';
      return;
    }
  }
  print '<form method="POST">
  <table class="tabular-data">
  <tr><th>現在のパスワード</th><td>';

  mx_formi_password('password0', $_REQUEST['password0'],
		    array('ime' => 'disabled'));

  print '</td></tr>
  <tr><th>パスワード</th><td>';

  mx_formi_password('password1', $_REQUEST['password1'],
		    array('ime' => 'disabled'));

  print '</td></tr>
  <tr><th>パスワード(もう一度)</th><td>';

  mx_formi_password('password2', $_REQUEST['password2'],
		    array('ime' => 'disabled'));

  print '</td></tr></table>';

  mx_formi_submit('submit', '変更');

  print '</form>';
}

class mx_change_password_application extends single_table_application {
	var $no_control_bar = 1;
	var $use_single_pane = 1;

	function setup() {
		; // nothing
	}

	function single_pane() {
		body($this->u);
	}

}

$it = new mx_change_password_application();
$it->main();
