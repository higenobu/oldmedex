<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/list_of_blah.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_authorization_cfg = array
(
 'MSGS' => array
 ('Inspect' => 'このアプリケーションの権限を設定する'),
 'COLS' => array('path', 'name'),
 'LIST_IDS' => array('ObjectID', 'name'),
 'TABLE' => 'mx_application',
 'ALLOW_SORT' => 1,
 'ENABLE_QBE' => 1,
);

class list_of_applications extends list_of_simple_objects {

  function list_of_applications($prefix, $cfg=NULL) {
    global $_lib_u_manage_authorization_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_authorization_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

function _lib_u_manage_authorization_fetch_data_0(&$self, &$db, $appid)
{
  $stmt1 = 'SELECT "ObjectID", "職種" FROM "職種一覧表" WHERE
 "Superseded" IS NULL ORDER BY "コード"';
  $stmt2 = 'SELECT "ObjectID", "職位" FROM "職位一覧表" WHERE
 "Superseded" IS NULL ORDER BY "ObjectID"';

  $cat = array();
  foreach (pg_fetch_all(pg_query($db, $stmt1)) as $row)
    $cat[$row['ObjectID']] = $row['職種'];
  $self->cat =& $cat;

  $rnk = array();
  foreach (pg_fetch_all(pg_query($db, $stmt2)) as $row)
    $rnk[$row['ObjectID']] = $row['職位'];
  $self->rnk =& $rnk;
}

function _lib_u_manage_authorization_fetch_data(&$self, &$db, $appid)
{
  $stmt0 = 'SELECT "職種", "職位" FROM mx_authorization WHERE appid = ' .
    mx_db_sql_quote($appid);

  if (! is_array($self->cat))
    _lib_u_manage_authorization_fetch_data_0(&$self, &$db, $appid);

  $self->data = array();
  $rnk =& $self->rnk;
  $cat =& $self->cat;

  foreach ($cat as $c => $cn) {
    $self->data[$c] = array();
    foreach ($rnk as $r => $rn)
      $self->data[$c][$r] = 0;
  }

  $d = pg_fetch_all(pg_query($db, $stmt0));
  if (is_array($d))
    foreach ($d as $row) {
      $c = $row['職種'];
      $r = $row['職位'];
      $self->data[$c][$r] = 1;
    }
}

class authorization_edit {

  function authorization_edit($prefix) {
    $this->prefix = $prefix;
    $this->appid = NULL;
    $this->cat = $this->rnk = NULL;
    $this->appid = mx_check_request($this->prefix . 'appid');
    $this->appname = mx_check_request($this->prefix . 'appname');
  }

  function reset($selected) {
    $selected = mx_form_unescape_key($selected);
    $this->appid = $selected[0];
    $this->appname = $selected[1];
    unset($_REQUEST[$this->prefix . 'appid']);
    unset($_REQUEST[$this->prefix . 'appname']);
  }

  function try_commit() {
    $a = mx_db_sql_quote($this->appid);
    $db =& mx_db_connect();
    pg_query($db, 'DELETE FROM mx_authorization WHERE appid = ' . $a);

    foreach ($this->rnk as $r => $rn) {
      foreach ($this->cat as $c => $cn) {
	$k = $this->prefix . $c . '-' . $r;
	if (array_key_exists($k, $_REQUEST)) {
	  pg_query($db, 'INSERT INTO mx_authorization (appid, "職種", "職位")
VALUES (' . $a . ', ' . $c . ', ' . $r . ')');
	}
      }
    }
    // NEEDSWORK:
    return 'ok';
  }

  function commit() {

    $db =& mx_db_connect();

    while (1) {
      pg_query($db, 'begin');
      $st = $this->try_commit($db);
      if ($st == 'ok') {
	pg_query($db, 'commit');
	_lib_u_manage_authorization_fetch_data(&$this, $db, $this->appid);
      }
      else
	pg_query($db, 'rollback');
      if ($st != 'retry')
	break;
      error_log('lib/u/manage/authorization:authorization_edit xn retry');
    }
  }

  function alltoggle($cat) {
    $tot = $cnt = 0;
    foreach ($this->rnk as $r => $rn) {
      $tot++;
      if ($this->data[$cat][$r]) $cnt++;
    }
    if ($cnt <= $tot / 2)
      $allset = 1;
    else
      $allset = 0;
    foreach ($this->rnk as $r => $rn)
      $this->data[$cat][$r] = $allset;
  }

  function draw() {

    if (array_key_exists($this->prefix . 'rollback', $_REQUEST))
      $this->appid = NULL;
    if ($this->appid == '')
      return;

    $db =& mx_db_connect();

    if (array_key_exists($this->prefix . 'appid', $_REQUEST)) {
      _lib_u_manage_authorization_fetch_data_0(&$this, $db, $this->appid);

      // Get data from the form, if the form existed.
      $this->data = array();
      foreach ($this->cat as $c => $cn) {
	$this->data[$c] = array();
	foreach ($this->rnk as $r => $rn) {
	  if (array_key_exists($this->prefix . $c . '-' . $r, $_REQUEST))
	    $this->data[$c][$r] = 1;
	  else
	    $this->data[$c][$r] = 0;
	}
      }
    }
    else
      _lib_u_manage_authorization_fetch_data(&$this, $db, $this->appid);

    if (array_key_exists($this->prefix . 'commit', $_REQUEST)) {
      $this->commit();
      return;
    }
    elseif (array_key_exists($this->prefix . 'alltoggle', $_REQUEST))
      $this->alltoggle($_REQUEST[$this->prefix . 'alltoggle']);

    print '<span class="heading">';
    print htmlspecialchars($this->appname);
    print "のアクセス権限</span>\n";
    mx_formi_hidden($this->prefix . 'appid', $this->appid);
    mx_formi_hidden($this->prefix . 'appname', $this->appname);
    print '<table class="tabular-data"><tr><td>&nbsp;</td>';
    foreach ($this->rnk as $r => $rn) {
      print '<th>';
      print htmlspecialchars($rn);
      print '</th>';
    }
    print "</tr>\n";

    foreach ($this->cat as $c => $cn) {
      print '<tr><th>';
      mx_formi_submit($this->prefix . 'alltoggle', $c,
		      '<span class="link">' .
		      htmlspecialchars($cn) . '</span>',
		      '本職種全職位をトグル');
      print "</th>";
      foreach ($this->rnk as $r => $rn) {
	print '<td>';
	mx_formi_checkbox($this->prefix . $c . '-' . $r,
			  $this->data[$c][$r]);
	print '</td>';
      }
      print "</tr>\n";
    }

    print "</table>\n";
    mx_formi_submit($this->prefix . 'commit', '編集完了');
    mx_formi_submit($this->prefix . 'rollback', '編集中止');

  }
}

?>
