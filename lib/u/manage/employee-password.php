<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
//changed 0103-2014 because employee.php was changed
class employee_password_application extends single_table_application {

  var $_browse_only = 1;
  var $use_single_pane = 1;
  var $employee_list_class = 'list_of_employees';

  function list_of_objects($prefix) {
    global $_lib_u_manage_employee_cfg;
    $cfg = $_lib_u_manage_employee_cfg;
    $cfg['LIST_IDS'] = array('職員ID', '姓名', "ユーザ名", 'userid');
    $class = $this->employee_list_class;
    return new $class($prefix, $cfg);
  }

  function allow_new() { return 0; }

  function object_display($prefix) {
    return new password_reset($prefix);
  }

}

class password_reset  {

  function password_reset($prefix) {
    global $_mx_min_length_of_employee_pw;

    $this->prefix = $prefix;
    $this->select = mx_check_request($prefix . 'select');

    if (mx_check_request($prefix . 'submit')) {
      $pw0 = mx_check_request($prefix . 'pw0');
      $pw1 = mx_check_request($prefix . 'pw1');
      $a = mx_form_unescape_key($this->select);
      if (($pw0 != $pw1))
	$this->err = "２つのパスワードが一致しません\n";
      elseif (strlen($pw0) < $_mx_min_length_of_employee_pw)
	$this->err = "パスワードは $_mx_min_length_of_employee_pw 文字以上でないといけません\n";
      if (! $this->err) {
//0101-2014 changed $a[2]->$a[0]

	$hm = mx_db_sql_quote(mx_authenticate_hmac($a[0] . ':' . $pw0));
	if (! pg_query("UPDATE mx_authenticate SET passhash = $hm
 		        WHERE userid = $a[3]"))
	  $this->err .= pg_last_error($db);

      }
      if (! $this->err)
	$this->err = '変更しました';
    }

    if (mx_check_request($prefix . 'cancel'))
      $this->select = NULL;
  }

  function reset($select) {
    $this->select = $select;
  }

  function draw() {
    print "<p>\n";

    if ($this->err) {
      print mx_html_paragraph($this->err);
      return;
    }

    $a = mx_form_unescape_key($this->select);
    print htmlspecialchars($a[0]) . ': ';
    print htmlspecialchars($a[1]) . '(';
    print htmlspecialchars($a[2]) . ") のパスワードをリセットします。<br />";
    mx_formi_hidden($this->prefix . 'select', $this->select);
    print "パスワードを二回入力して下さい<br />";
    mx_formi_password($this->prefix . 'pw0', '', array('ime' => 'disabled'));
    mx_formi_password($this->prefix . 'pw1', '', array('ime' => 'disabled'));
    print "<br/>\n";
    mx_formi_submit($this->prefix . 'submit', 'はい');
    mx_formi_submit($this->prefix . 'cancel', 'やめる');
  }

  function history() {
	  return 18;
  }

  function chosen() {
    return $this->select;
  }

}

