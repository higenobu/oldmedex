<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';

$_u_manage_sessions_cfg = array
(
 'HSTMT' => '
SELECT
    E."職員ID", (E."姓" || \' \' || E."名") as "職員名",
    S.login_time as "ログイン時刻",
    S.logout_time as "ログアウト時刻",
    S.remote_addr as "リモートアドレス",
    S.expired as "自動ログアウト",
    S."ID" as "ObjectID",
    S.browser_hint as "ブラウザID"
FROM (
    SELECT "ID", userid, login_time, logout_time, remote_addr, expired,
         browser_hint
    FROM mx_session_log
UNION
    SELECT "ID", userid, login_time, NULL, remote_addr, NULL, browser_hint
    FROM mx_session
) as S
LEFT JOIN "職員台帳" as E
ON E.userid = S.userid AND E."Superseded" IS NULL
WHERE (NULL IS NULL)',
 'LCOLS' => array('職員ID', '職員名',
		  array('Column' => 'ログイン時刻',
			'Draw' => 'timestamp'),
		  array('Column' => 'ログアウト時刻',
			'Draw' => 'timestamp'),
		  '自動ログアウト',
		  'リモートアドレス',
		  'ブラウザID'),
);

class list_of_sessions extends list_of_simple_objects {

  var $debug = 0;

  function list_of_sessions($prefix, $config=NULL) {
    global $_u_manage_sessions_cfg;
    $config = array_merge($_u_manage_sessions_cfg, $config);
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function row_paging_keys() {
    return array('ログイン時刻', '職員ID');
  }

  function row_paging_aliases() {
    return array('S.login_time', 'E."職員ID"');
  }

  function row_paging_orders() {
    return array(1, 0);
  }

}

$_u_manage_session_apps_cfg = array
(
 'LCOLS' => array('アプリケーション名', 'path'),
 'NOLINK' => 1,
);

class list_of_session_apps extends list_of_simple_objects {

  var $debug = 0;

  function list_of_session_apps($prefix, $id) {
    global $_u_manage_session_apps_cfg;
    $config = $_u_manage_session_apps_cfg;
    $config['HSTMT'] = 'SELECT P.name as "アプリケーション名", P.path
FROM mx_application as P
JOIN mx_session_access as A
ON A.application = P."ObjectID"
WHERE A."ID" = ' . mx_db_sql_quote($id);
    $config['STMT'] = $config['HSTMT'] . ' AND P."Superseded" IS NULL';
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

}

class mx_management_sessions extends single_table_application {
	var $use_single_pane = 1;
	var $_browse_only = 1;
	var $_upper = array('index.php' => '/images/top_button.png',
			    'u/manage/index.php' => '管理アプリケーション'
			    );

	function setup() {
		; // nothing
	}

	function single_pane() {
		$ls = new list_of_sessions('los-');

		if ($ls->changed() && $ls->chosen())
			$la = new list_of_session_apps('loa-', $ls->chosen());
		else
			$la = NULL;

		print '<form method="POST">';

		$ls->draw();

		if ($la) {
			print '<hr />';
			mx_titlespan('職員がアクセスしたアプリケーションのリスト');
			$la->draw();
		}

		print "</form>\n";
	}

}

$it = new mx_management_sessions();
$it->main();
?>
