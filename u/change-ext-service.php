<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext-service.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

function no_ext() {
	mx_titlespan("使用できる外部サービスはありません");
}

function try_change_ext_service($db, $u, $service, $acct, $pass)
{
	$stmt = sprintf("DELETE FROM mx_ext_service_account WHERE ".
			"localuser = %s AND service = %s",
			mx_db_sql_quote($u),
			mx_db_sql_quote($service));
	if (!pg_query($db, $stmt))
		return 'error';
	$stmt = sprintf("INSERT INTO mx_ext_service_account ".
			"(localuser, service, account, password) ".
			"VALUES (%s, %s, %s, %s)",
			mx_db_sql_quote($u),
			mx_db_sql_quote($service),
			mx_db_sql_quote($acct),
			mx_db_sql_quote($pass));
	if (!pg_query($db, $stmt))
		return 'error';
	if (!pg_query($db, 'commit'))
		return 'retry';
	return 'ok';
}

function change_ext_service($db, $u, $service, $acct, $pass)
{
	while (1) {
		pg_query($db, 'begin');
		$status = try_change_ext_service($db, $u, $service, $acct, $pass);
		if ($status == 'ok')
			break;
		pg_query($db, 'rollback');
		if ($status != 'retry')
			return "トランザクション・エラー";
	}
	return NULL;
}

function body($u) {

  $ext = ext_service_list();
  if (count($ext) == 0)
	  return no_ext();

  $service = NULL;
  foreach ($ext as $k => $v) {
	  $s = 'submit-' . $k;
	  if (array_key_exists($s, $_REQUEST) && $_REQUEST[$s] != '') {
		  $service = $k;
		  break;
	  }
  }

  if ($service) {
	  $extdata = $ext[$service];
	  if (array_key_exists('password0', $_REQUEST) &&
	      array_key_exists('account-' . $service, $_REQUEST) &&
	      array_key_exists('password1-' . $service, $_REQUEST) &&
	      array_key_exists('password2-' . $service, $_REQUEST)) {

		  $name = htmlspecialchars($extdata['name']);
		  $password0 = $_REQUEST['password0'];
		  $account = $_REQUEST['account-' . $service];
		  $password1 = $_REQUEST['password1-' . $service];
		  $password2 = $_REQUEST['password2-' . $service];

		  $db = mx_db_connect();
		  $err = "";
		  if ($password1 != $password2)
			  $err .= "２つのパスワードが一致しません\n";
		  elseif ($password1 == '')
			  $err .= "パスワードが空です\n";
		  if ($account == '')
			  $err .= "アカウントが空です\n";

		  $stmt = "SELECT username, passhash FROM mx_authenticate
				WHERE userid = $u";
		  $rlt = mx_db_fetch_single($db, $stmt);
		  if (! is_array($rlt))
			  $err .= "ローカル・パスワードをアクセスできません\n";
		  else {
			  $hm = mx_authenticate_hmac($rlt['username'] . ':' .
						     $password0);
			  if ($hm != $rlt['passhash'])
				  $err .= "ローカル・パスワードが一致しません\n";
		  }

		  if (! $err)
			  $err = change_ext_service($db, $u,
						     $service, $account, $password1);

		  if ($err) {
			  print "以下のエラーにより外部サービス'${name}'を変更できません。\n";
			  print mx_html_paragraph($err);
		  }
		  else {
			  print '<span class="heading">';
			  print "外部サービス'${name}'のパスワードを変更しました。";
			  print '</span>';
			  //M3 hack
			  if($service == 'M3') {
			    print '<META HTTP-EQUIV="Set-Cookie" content="m3=1; expires=Wed, 31-Dec-1969 16:00:00 GMT; path=/">';
			    print "<br><br>※パスワードは次の画面から有効になります。";
			  }
			  return;
		  }
	  }
  }

  print '<form method="POST">';

  $first = 1;
  print "<table class=\"tabular-data\">\n";
  foreach ($ext as $k => $v) {

	  if ($first) {
		  $first = 0;
		  print "<tr><th>ローカル・パスワード</th><td>";
		  mx_formi_password('password0', $_REQUEST['password0'],
				    array('ime' => 'disabled'));
		  print "</td></tr>\n";
	  }
	  print "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";


	  $name = htmlspecialchars($v['name']);

	  print "<tr><th>外部サービス</th><td>";
	  print $name;
	  print "</td></tr>\n";

	  print "<tr><th>アカウント</th><td>";
	  mx_formi_text('account-' . $k, $_REQUEST['account-' . $k],
			array('ime' => 'disabled'));
	  print "</td></tr>\n";

	  print "<tr><th>パスワード</th><td>";
	  mx_formi_password('password1-' . $k, $_REQUEST['password1-' . $k],
			    array('ime' => 'disabled'));
	  print "</td></tr>\n";

	  print "<tr><th>パスワード（もう一度）</th><td>";
	  mx_formi_password('password2-' . $k, $_REQUEST['password2-' . $k],
			    array('ime' => 'disabled'));
	  print "</td></tr>\n";

	  print "<tr><th>&nbsp;</th><td>";
	  mx_formi_submit('submit-' . $k, $name . "を設定");
	  print "</td></tr>\n";
  }
  print '</td></tr></table>';
  print '</form>';
}

class mx_change_ext_service_application extends single_table_application {
	var $no_control_bar = 1;
	var $use_single_pane = 1;

	function setup() {
		; // nothing
	}

	function single_pane() {
		body($this->u);
	}

}

$it = new mx_change_ext_service_application();
$it->main();
