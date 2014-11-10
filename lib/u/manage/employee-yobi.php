<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/department.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-cat.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-rank.php';

$_lib_u_manage_employee_fetch_stmt = 'SELECT
E."ObjectID", E."CreatedBy", "����ID" as "EID",
"��" as "lastname",
"̾" as "firstname",
  "����" as "gender", "��ǯ����" as "DOB",
 
"��������" as "tel",
("��" || \' \' || "̾") as "name",
E.userid, U.username as "username",
E."����", C."����" as "emptype",
E."����", R."����" as "rank",
E."����" as "dept",
D."������" as "deptcode",
D."��ʬ��" as "bigcat",
D."��ʬ��1" as "midcat1",
D."��ʬ��2" as "midcat2",
D."��ʬ��" as "smallcat"
FROM
 "������Ģ" as E,
 mx_authenticate as U,
 "�������ɽ" as C,
 "���̰���ɽ" as R,
 "�������ɽ" as D
WHERE
 C."Superseded" IS NULL AND
 R."Superseded" IS NULL AND
 D."Superseded" IS NULL AND
 E."����" = C."ObjectID" AND
 E."����" = R."ObjectID" AND
 E."����" = D."ObjectID" AND
 E.userid = U.userid';

// Must match $_lib_u_manage_department_cfg['COLS']
$_lib_u_manage_employee_dept_cols = array();
foreach ($_lib_u_manage_department_cfg['COLS'] as $c) {
  $_lib_u_manage_employee_dept_cols[] = "����" . $c;
}

function _lib_u_manage_employee_fix_abbrev_dept(&$row)
{
  global $_lib_u_manage_employee_dept_cols;
  if (! array_key_exists("deptname", $row))
    $row["deptname"] = _lib_u_manage_department_abbrev
      ($row, &$_lib_u_manage_employee_dept_cols);
}

$_lib_u_manage_employee_ldcol_d = array();
$_lib_u_manage_employee_ldcol_r = array();
if (!$_mx_use_one_employee_dept) {
	$_lib_u_manage_employee_ldcol_d[] = 'deptname';
}
if (!$_mx_use_one_employee_rank) {
	$_lib_u_manage_employee_ldcol_r[] = 'rankname';
}

$_lib_u_manage_employee_cfg = array
(
 'TABLE' => '������Ģ',
 'UNIQ_ID' => 'E."ObjectID"',
 'MSGS' => array('Choose' => '����'),

 // This is only used by employee-edit to pass things around between forms.
 // Especially this is not used for database query in any way, because
 // we override most of the methods.
 
 'COLS' => array("ObjectID", "CreatedBy", "����ID",
		 "��", "̾", "�եꥬ��", "����", "��ǯ����",
		 "����0", "����1", "����2", "����3", "����4",
		 "��������", "��������",
		 '�桼��̾',
		 "����̾", "����̾", "����̾",
		 'userid',
		 "����", "����", "����",
		 "�ѥ����", "�ѥ����(�⤦����)",
		 ),
 /*
 
 'COLS' => array("ObjectID", "CreatedBy", "EID",
		 "lastname", "firstname",   "gender", "DOB",
		  
		 "��������",  
		 'username',
		 "emptypename", "rankname", "deptname",
		 'userid',
		 "����", "����", "����",
		 "�ѥ����", "�ѥ����(�⤦����)",
		 ),
*/

 'LCOLS' => array_merge(array(
 array('Column' =>  "EID",'Label'=>'EID'),
array('Column' =>  "lastname",'Label'=>'LastName'),

array('Column' =>  "firstname",'Label'=>'FirstName'),) 
 			 ),
 'DCOLS' => array_merge(array(array('Column' =>  "EID",'Label'=>'EID')),
			$_lib_u_manage_employee_ldcol_d,
 //			array("����̾"),
			$_lib_u_manage_employee_ldcol_r,
			array(array('Column' =>  "name",'Label'=>'Name'), 
array('Column' =>  "gender",'Label'=>'Gender'), 
array('Column' =>  "DOB",'Label'=>'DOB'),
			       
array('Column' =>  "tel",'Label'=>'Tel'), 
			      array('Column' => "CreatedBy",
				    'Label' => "record",
				    'Draw' => 'user'))),
 /*
'ECOLS' =>array(
array('Column' =>  "����ID",'Label'=>'EID'),
 
array('Column' =>  "��",'Label'=>'lastname'), 
 array('Column' =>  "����",'Label'=>'Gender'), 
 array('Column' =>  "��ǯ����",'Label'=>'DOB'),
			       
 array('Column' =>  "��������",'Label'=>'Tel'), 
),
 
*/

 'ICOLS' => array('userid', "����ID", "����", "����", "����", "��", "̾",
		   "����", "��ǯ����",  
		    "��������"
		   ),
 'HSTMT' => $_lib_u_manage_employee_fetch_stmt,
 'STMT' => $_lib_u_manage_employee_fetch_stmt . ' AND E."Superseded" IS NULL',

 'LCHOICE' => array('' => '��������������',
		    '13' => '�������狼������',
'14' => '�������狼������',
'7' => '��̳��������',
'3' => '��ɤ�������',
'4' => '���ɤ�������',
'5' => '��������������',
'6' => '������������',
		    '10' => '���ܤ�������'),

 'ALLOW_SORT' => array('����ID' => array('����ID' => '"����ID"'),
		       '����̾' => array("������ʬ��" => 'D."��ʬ��"',
					 "������ʬ��1" => 'D."��ʬ��1"',
					 "������ʬ��2" => 'D."��ʬ��2"',
					 "����ʬ��" => 'D."��ʬ��"'),
		       '��̾' => array("��" => 'E."��"',
				       "̾" => 'E."̾"')
		       ),

 // The RHS is used in the SQL statement appended to the STMT.
 // E.g. the expression entered for "����̾" is compared with the
 // result of the experession 'COALESCE()||...' shown below.
 'ENABLE_QBE' => array('����ID',
		       array('Column' => '��̾',
			     'Compare'=> ('COALESCE(E."��",\'\')||' .
					  'COALESCE(E."̾",\'\')')),
		       array('Column' => '����̾',
			     'Compare' => ('COALESCE(D."��ʬ��",\'\')||' .
					   'COALESCE(D."��ʬ��1",\'\')||' .
					   'COALESCE(D."��ʬ��2",\'\')||' .
					   'COALESCE(D."��ʬ��",\'\')')),
		       ),
 );

class list_of_employees extends list_of_simple_objects {

  function list_of_employees($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function base_fetch_stmt_1($i) {
    $base = $this->so_config['STMT'];
    if ($i != '')
      $base .= " AND \"����\" = '$i'";
    return $base;
  }

  function annotate_row_data(&$row) {
    _lib_u_manage_employee_fix_abbrev_dept(&$row);
  }

  function draw_no_data_message() {
    print '<br />�������뿦��������ޤ���';
  }

}

class employee_display extends simple_object_display {

  function employee_display($prefix) {
    global $_lib_u_manage_employee_cfg;
    simple_object_display::simple_object_display
      ($prefix, $_lib_u_manage_employee_cfg);
  }

  function annotate_row_data(&$row) {
    _lib_u_manage_employee_fix_abbrev_dept(&$row);
    switch ($row["����"]) {
    case 'M': $row["����"] = 'M'; break;
    case 'F': $row["����"] = 'F'; break;
    case '��': break;
    case '��': break;
    default: $row["����"] = 'unknwon'; break;
    }
  }

}

$_lib_u_manage_employee_enum_cfg = array
(
 '����̾' => array('cf' => $_lib_u_manage_employee_cat_cfg,
		   'cls' => 'list_of_employee_cats',
		   'twm' => '���ο�������ꤹ��',
		   'col' => '����'),
 '����̾' => array('cf' => $_lib_u_manage_employee_rank_cfg,
		   'cls' => 'list_of_employee_ranks',
		   'twm' => '���ο��̤����ꤹ��',
		   'col' => '����'),
 '����̾' => array('cf' => $_lib_u_manage_department_cfg,
		   'cls' => 'list_of_departments',
		   'twm' => '������������ꤹ��',
		   'col' => '����'),
 );

class employee_edit extends simple_object_edit {

  // 0: hidden
  // 1: text
  // 2: enum (subpick)
  // 3: allow mods only on create
  // 4: show and ask only on create
  // 5: enum (choice)
  // 1, 3 and 4 can be ORed with 8 to mean "ASCII only".
  // array: choice (immediate enum)
  //
  var $cols = array("ObjectID" => 0,
		    "CreatedBy" => 0,
		    "����ID" => 3,
		    "�ѥ����" => 4,
		    "�ѥ����(�⤦����)" => 4,
		    "��" => 1,
		    "̾" => 1,
		    "�եꥬ��" => 1,
		    "����" => array('' => "����", "M" => '��', "F" => '��'),
		    "��ǯ����" => 9,
		    "����0" => 1,
		    "����1" => 1,
		    "����2" => 1,
		    "����3" => 1,
		    "����4" => 1,
		    "��������" => 9,
		    "��������" => 9,
		    '�桼��̾' => 0,
		    "����̾" => 2,
		    "����̾" => 2,
		    "����̾" => 2,
		    "userid" => 0,
		    "����" => 0,
		    "����" => 0,
		    "����" => 0);

  function employee_edit($prefix) {
    global $_lib_u_manage_employee_cfg;
    global $_mx_min_length_of_employee_id;
    global $_mx_max_length_of_employee_id;
    global $_mx_use_one_employee_dept;
    global $_mx_use_one_employee_rank;
    global $_mx_use_employee_class_choice;

    if ($_mx_use_one_employee_dept)
	    $this->cols["deptname"] = 0;
    if ($_mx_use_one_employee_rank)
	    $this->cols["rankname"] = 0;
    if ($_mx_use_employee_class_choice)
	    $this->cols["emptypename"] = 5;

    if (0) {
	    $this->min_length_of_employee_id = $_mx_min_length_of_employee_id;
	    $this->max_length_of_employee_id = $_mx_max_length_of_employee_id;
    }
    simple_object_edit::simple_object_edit
      ($prefix, $_lib_u_manage_employee_cfg);

    $this->changeThis = NULL;
    if (array_key_exists($prefix . 'changeThis', $_REQUEST))
      $this->enum_change($_REQUEST[$prefix . 'changeThis']);

    if ($this->changeThis) {
      if ($this->changeThis->chosen()) {
	# The user picked one element from the list.
	# Set it to the field and fold this selector.
	$this->enum_set($this->changeCol, $this->changeThis->chosen());
	$this->changeThis = $this->changeCol = NULL;
      }
      elseif (array_key_exists($prefix . 'notChangeThis', $_REQUEST))
	$this->changeThis = $this->changeCol = NULL;
    }
  }

  function enum_change($col) {
    global $_lib_u_manage_employee_enum_cfg;
    $pfx = $this->prefix . 'enum-';
    if (! array_key_exists($col, $_lib_u_manage_employee_enum_cfg))
      return;

    $cls = $_lib_u_manage_employee_enum_cfg[$col]['cls'];
    $cfg = $_lib_u_manage_employee_enum_cfg[$col]['cf'];
    $cfg['MSGS']['Inspect'] = $_lib_u_manage_employee_enum_cfg[$col]['twm'];
    $cfg['LIST_IDS'] =
      array('ObjectID', $_lib_u_manage_employee_enum_cfg[$col]['col']);
    $this->changeThis = new $cls($pfx, $cfg);
    $this->changeCol = $col;
  }

  function enum_set($col, $id) {
    global $_lib_u_manage_employee_enum_cfg;
    $o = mx_form_unescape_key($id);
    $this->data[$_lib_u_manage_employee_enum_cfg[$col]['col']] = $o[0];
    $this->data[$col] = $o[1];
  }

  function find_enum_values($col) {
	  if ($col != '����̾') {
		  return array('�Ф�' => '�Ф�');
	  }
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "����"
FROM "�������ɽ"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c['����'];
	  }
	  return $ret;
  }

  function handle_enum_column($col, $d) {
	  global $_lib_u_manage_employee_enum_cfg;
	  $selection = $this->find_enum_values($col);
	  $co = $_lib_u_manage_employee_enum_cfg[$col]['col'];
	  mx_formi_select($this->en($col), $d[$co], $selection);
  }

  function edit($id) {
    $this->changeThis = NULL;
    simple_object_edit::edit($id);
  }

  function anew_tweak($orig_id) {
    global $_mx_use_one_employee_dept;
    global $_mx_use_one_employee_rank;

    if ($_mx_use_one_employee_dept)
	    $this->data["����"] = $_mx_use_one_employee_dept;
    if ($_mx_use_one_employee_rank)
	    $this->data["����"] = $_mx_use_one_employee_rank;

    $this->changeThis = NULL;
    $this->data['����ID'] = '';
    $this->data['userid'] = '';
  }

  function data_compare($curr, $data) {
    $data['CreatedBy'] = $curr['CreatedBy'];
    $data['ObjectID'] = $curr['ObjectID'];
    foreach (array('��̾', '����̾', '���𥳡���', '������ʬ��',
		   '������ʬ��1','������ʬ��2','����ʬ��',
		   "�ѥ����", "�ѥ����(�⤦����)") as $c) {
      unset($curr[$c]);
      unset($data[$c]);
    }
    if ($curr != $data && $this->debug) {
      print "<!-- EMPLOYEE-data-compare this->data\n";
      var_dump($data);
      print "\ncurr\n";
      var_dump($curr);
      print "-->\n";
    }
    return ($curr != $data);
  }

  function _validate() {
    global $_lib_u_manage_employee_enum_cfg;
    global $_mx_min_length_of_employee_pw;

    $errs = 0;
    $d = $this->data;
/*
    if (trim($d['DOB']) == '')
	    $this->data['DOB'] = NULL;
    else if ($msg = mx_db_validate_date($d['DOB'])) {
	    $this->err("DOB: $msg");
	    $errs++;
	    $this->data['DOB'] = NULL;
	    $this->note_badcol('DOB');
    }
 

    // ���� must be set
    switch ($d['����']) {
    case 'M':
    case 'F':
	    break;
    default:
      $this->err('gender: ̤����ǤϤ����ޤ���');
      $this->note_badcol('����');
      $errs++;
    }
*/


    // ����, ����, and ���� must be set
    foreach ($this->cols as $col => $type) {
      if ($type == 2) {
	$real_col = $_lib_u_manage_employee_enum_cfg[$col]['col'];
	if ($d[$real_col] == '') {
	  $this->err("$col: ���ꤵ��Ƥ��ޤ���");
	  $this->note_badcol($col);
	  $errs++;
	}
      }
    }
 
    // Some columns cannot be empty
    
      if ($d["��"] == '') {
	$this->err("lastname: ���ꤵ��Ƥ��ޤ���");
	//$this->note_badcol($col);
	$errs++;
      }
 
   

    if ($this->id == '') {
      // Creating a new one.

      // ����ID must be set and non-conflicting.
      if ($msg = mx_db_validate_length($d['����ID'],
				       $this->min_length_of_employee_id,
				       $this->max_length_of_employee_id)) {
	$this->err("EID: $msg");
	$this->note_badcol('EID');
	$errs++;
      }
      else {
	$db = mx_db_connect();
	$result = mx_db_fetch_single($db,
				     'SELECT count(*) from "������Ģ"
                                      WHERE "Superseded" IS NULL
                                      AND "����ID" = ' .
				     mx_db_sql_quote($d['EID']));
	if (! is_null($result) && $result['count'] != 0) {
	  $this->err('����ID: ���ꤵ�줿�ͤϤ��Ǥ˻��Ѥ���Ƥ��ޤ�');
	  $this->note_badcol('EID');
	  $errs++;
	}
      }

 
      if ($d['�ѥ����'] != $d["�ѥ����(�⤦����)"]) {
	$this->err('password does not match');
	$this->note_badcol('password');
	$this->note_badcol('password(again)');
	$errs++;
      }
 

    if ($errs == 0)
      return 'ok';
  }
}


  function try_commit(&$db) {
    global $_mx_create_modalities_for_doctors;

    $creating = ($this->id == '');

    if ($creating) {
      if ($this->_validate() != 'ok')
	return 'failure';

      // Create a new mx_authenticate object.
      $qid = mx_db_sql_quote($this->data['����ID']);
      $qph = mx_db_sql_quote(mx_authenticate_hmac($this->data['����ID'] . ':' .
						  $this->data['�ѥ����']));
      $result = mx_db_fetch_single($db,
				   "SELECT max(userid) FROM mx_authenticate");
      $uid = $result['max']+1;
      $this->log('UID is ' . $uid . "\n");
      $this->log('QID is ' . $qid . "\n");
      $this->log('QPH is ' . $qph . "\n");
      pg_query($db,
	       "INSERT INTO mx_authenticate (userid, username, passhash)
                VALUES ($uid, $qid, $qph)");
      $result = mx_db_fetch_single($db,
				   "SELECT userid FROM mx_authenticate
                                    WHERE username = $qid AND
                                    passhash = $qph");
      if (! (is_array($result) && $result['userid'] == $uid) ) {
	$this->err('�����ƥ�桼�������ޤ���');
	$this->log('RESULT: ' . mx_var_dump($result));
	return 'failure';
      }
      $this->data['userid'] = $result['userid'];
    }
    $status = simple_object_edit::try_commit($db);

    if ($_mx_create_modalities_for_doctors) {
	    if ($status == 'ok') {
		    $id = mx_db_sql_quote($this->id);
		    $r = pg_query($db, "SELECT ADD_EMPLOYEE_MODALITY($id)");
		    if (!$r) {
			    $this->err('ͽ���оݤ����ޤ���');
			    return 'failure';
		    }
	    }
    }

    if ($creating && $status != 'ok')
      $this->data['userid'] = '';
    return $status;
  }

  function annotate_form_data(&$d) {
    foreach ($this->cols as $col => $type) {
	    if ($type & 8) {
		    $v = mb_convert_kana($d[$col], 'as', 'euc');
		    $this->dbglog("Kana $d[$col] => $v\n");
		    $d[$col] = $v;
	    }
	    if (($type & 7) == 5) {
		    global $_lib_u_manage_employee_enum_cfg;
		    $co = $_lib_u_manage_employee_enum_cfg[$col]['col'];
		    $d[$co] = $d[$col];
	    }
    }
  }

  function draw_body() {
    global $_mx_use_control_bar;

    $d =& $this->data;
    _lib_u_manage_employee_fix_abbrev_dept($d);
    foreach ($this->cols as $col => $type) {
      if ($type == 0 || $type == 2)
	mx_formi_hidden($this->en($col), $d[$col]);
    }

    print "<table class=\"tabular-data\">\n";
    foreach ($this->cols as $col => $type) {

      if ($type & 8) {
	$ime_option = array('ime' => 'disabled');
	$type = $type - 8;
      } else
	$ime_option = NULL;

      if ($type == 0 || ($type == 4 && $d['userid'] != ''))
	continue;

      print "<tr><th>" . htmlspecialchars($col) . "</th>";

      $previous_error = '';
      if ($this->check_previous_error($col))
	      $previous_error = ' class="has_errors"';
      print "<td$previous_error>";
      if (is_array($type))
	mx_formi_select($this->en($col), $d[$col], $type);
      elseif ($type == 1)
	mx_formi_text($this->en($col), $d[$col], $ime_option);
      elseif ($type == 2) {
	$v = $d[$col];
	if ($v == '') $v = "($col)";
	if (is_null($this->changeThis))
	  mx_formi_submit($this->prefix . 'changeThis', $col,
			  "<span class=\"link\">" .
			  htmlspecialchars($v) .
			  "</span>",
			  $this->so_config['MSGS']['Choose']);
	else
	  print htmlspecialchars($v);
      }
      elseif ($type == 3) {
	if ($d['userid'] == '') { // Creating
	  mx_formi_text($this->en($col), $d[$col], $ime_option);
	}
	else {
	  mx_formi_hidden($this->en($col), $d[$col]);
	  print htmlspecialchars($d[$col]);
	}
      }
      elseif ($type == 4) {
	// We are here only when creating -- see the first if()
	// within this foreach().
	mx_formi_password($this->en($col), $d[$col], $ime_option);
      }
      elseif ($type == 5) {
	      $this->handle_enum_column($col, $d);
      }
      print "</td></tr>";
    }
    print "</table>";

    if ($this->changeThis) {
      mx_formi_hidden($this->prefix . 'changeThis', $this->changeCol);
      print "<hr /><span class=\"heading\">";
      print htmlspecialchars($this->changeCol) . "���ѹ�";
      print "</span>";
      mx_formi_submit($this->prefix . 'notChangeThis', '',
		      "<span class=\"link\">" .
		      htmlspecialchars('�ѹ����ʤ�') .
		      "</span>");
      $this->changeThis->draw();
    }
    else if (!$_mx_use_control_bar) {
      $soc = $this->so_config;
      if ($this->id) { // Modifying
	$commit = ( (array_key_exists('MSGS', $soc) &&
		     array_key_exists('Commit', $soc['MSGS']))
		    ? $soc['MSGS']['Commit'] : '�Խ���λ' );
	$rollback = ( (array_key_exists('MSGS', $soc) &&
		       array_key_exists('Rollback', $soc['MSGS']))
		      ? $soc['MSGS']['Rollback'] : '�Խ����' );
      } else { // Creating
	$commit = ( (array_key_exists('MSGS', $soc) &&
		     array_key_exists('Commit', $soc['MSGS']))
		    ? $soc['MSGS']['CCommit'] : '��Ͽ��λ' );
	$rollback = ( (array_key_exists('MSGS', $soc) &&
		       array_key_exists('Rollback', $soc['MSGS']))
		      ? $soc['MSGS']['CRollback'] : '��Ͽ���' );
      }
      if (substr($commit, 0, 8) == "/images/")
	      $commit = "<img src=\"$commit\">";
      if (substr($rollback, 0, 8) == "/images/")
	      $rollback = "<img src=\"$rollback\">";
      mx_formi_submit($this->prefix . 'commit', 1, $commit);
      mx_formi_submit($this->prefix . 'rollback', 1, $rollback);
    }
  }

}

?>
