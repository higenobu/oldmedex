<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-manage-log.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';

function _lib_u_nurse_hospital_manage_log_config(&$config)
{
  $config['DCOLS'] = array('��ľ���̾',
			   '��ľ���̾',
			   '����ô���Ǹ��̾',
			   '���ʱ�����̾',
			   '�±����贵�Կ�',
			   '���ʳ��贵�Կ�',
			   array('Column' => '�õ�����',
				 'Draw' => 'textarea'),
			   array('Column' => 'CreatedBy',
				 'Draw' => 'user'));
  _lib_so_prepare_config_ledcols(&$config['DCOLS']);
}

function _lib_u_nurse_hospital_manage_log_peek_id(&$it, $db, $dt)
{
  $stmt = ('SELECT HM."ObjectID"
FROM "�±��Ǹ��������" AS HM
WHERE HM."Superseded" IS NULL AND HM."����" = ' . mx_db_sql_quote($dt));
  $it->dbglog("CHECK-EXISTS: $stmt;\n");
  $st = pg_query($db, $stmt);
  if (! $st) {
    var_dump(debug_backtrace());
  }
  $r = pg_fetch_all($st);
  if (! is_array($r) || count($r) != 1)
    return NULL;
  return $r[0]['ObjectID'];
}

function _lib_u_nurse_hospital_manage_log_borrow_wml(&$it, &$data, $db)
{
  // Fetch data from the ward management log.
  $stmt = ('SELECT "ObjectID", "����̾" FROM "�������ɽ"
WHERE "Superseded" IS NULL
ORDER BY "����̾"');
  $it->dbglog("$stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  $dt = $data['����'];
  $it->ward_list = array();
  $data['ž��'] = array();
  $data['����'] = array();
  $data['�ౡ'] = array();
  $data['����'] = array();
  $ward_total = '��������';
  foreach ($r as $_ward_data) {
    $ward = $_ward_data['ObjectID'];
    $ward_name = $_ward_data['����̾'];
    $it->ward_list[$ward] = $ward_name;
    $wcf = array();
    _lib_u_nurse_ward_manage_log_prepare_config($wcf);
    $wd =
      _lib_u_nurse_ward_manage_log_get_by_dt_ward($it, $wcf, $db, $dt, $ward);

    foreach (array("��������", "�����¾���",
		   "��������", "��������",
		   "�ڲ�", "ž��", "��˴", "¾��ž��",
		   "ž��", "ž��") as $col) {
      $data[$ward_name . '.' . $col] = $wd['����'][0][$col];
      $data[$ward_total . '.' . $col] += $wd['����'][0][$col];
    }
    foreach (array("���Կ�" => array("ô��", "����", "����"),
		   "����" => array("��������", "��������"),
		   "ž����ž��" => array("�ڲ�", "ž��", "��˴", "¾��ž��"))
	     as $col => $scols) {
      $sum = 0;
      $all_null = 1;
      foreach ($scols as $scol) {
	if ($wd['����'][0][$scol] != '') $all_null = 0;
	$sum += $wd['����'][0][$scol];
      }
      if (! $all_null) {
	$data[$ward_name . '.' . $col] = $sum;
	$data[$ward_total . '.' . $col] += $sum;
      }
    }

    foreach ($wd['ž��ž��'] as $d) {
      $d['����'] = $ward;
      $d['����̾'] = $ward_name;
      switch ($d['����']) {
      case 'i': // ¾������ž��
	$data['ž��'][] = $d; break;
      case 'o': // ¾�����ž��
	;; // already covered above.
      case 'I': // ����
	$data['����'][] = $d; break;
      case 'O': // �ౡ
	$data['�ౡ'][] = $d; break;
      }
    }

    foreach ($wd['���񡦳���'] as $d) {
      if ($d['����������'] == 0) {
	$d['����'] = $ward;
	$d['����̾'] = $ward_name;
	$data['����'][] = $d;
      }
    }
  }
}

function _lib_u_nurse_hospital_manage_log_fetch_data(&$it, $db, $id)
{
  $stmt = ('SELECT HM."ObjectID", HM."Superseded", HM."CreatedBy",
HM."����",
HM."��ľ���", (ED0."��" || ED0."̾") AS "��ľ���̾",
HM."��ľ���", (ED1."��" || ED1."̾") AS "��ľ���̾",
HM."����ô���Ǹ��", (EN."��" || EN."̾") AS "����ô���Ǹ��̾",
HM."���ʱ�����", (EP."��" || EP."̾") AS "���ʱ�����̾",
HM."�±����贵�Կ�",
HM."���ʳ��贵�Կ�",
HM."�õ�����",
HM."���Ǹ�ա�������",
HM."���Ǹ�ա��ѡ������",
HM."���Ǹ�ա�����Х������",
HM."�ڴǸ�ա�������",
HM."�ڴǸ�ա��ѡ������",
HM."�ڴǸ�ա�����Х������",
HM."�Ǹ�������"
FROM "�±��Ǹ��������" AS HM
LEFT JOIN "������Ģ" AS ED0
ON ED0."ObjectID" = HM."��ľ���" AND ED0."Superseded" IS NULL
LEFT JOIN "������Ģ" AS ED1
ON ED1."ObjectID" = HM."��ľ���" AND ED1."Superseded" IS NULL
LEFT JOIN "������Ģ" AS EN
ON EN."ObjectID" = HM."����ô���Ǹ��" AND EN."Superseded" IS NULL
LEFT JOIN "������Ģ" AS EP
ON EP."ObjectID" = HM."���ʱ�����" AND EP."Superseded" IS NULL
WHERE HM."ObjectID" = ' . mx_db_sql_quote($id));
  $it->dbglog("fetch: $stmt;\n");
  $data = mx_db_fetch_single($db, $stmt);
  _lib_u_nurse_hospital_manage_log_borrow_wml(&$it, &$data, $db);
  return $data;
}

function _lib_u_nurse_hospital_manage_log_get_by_dt(&$it, $db, $dt)
{
  $id = _lib_u_nurse_hospital_manage_log_peek_id(&$it, $db, $dt);
  if (! is_null($id))
    $data =
      _lib_u_nurse_hospital_manage_log_fetch_data($it, $db, $id);
  else {
    $data = array('����' => $dt);
    _lib_u_nurse_hospital_manage_log_borrow_wml(&$it, &$data, $db);
  }
  return $data;
}

class hospital_manage_log_display extends simple_object_display {

  var $debug = 0;

  function hospital_manage_log_display($prefix, $config=NULL) {
    $this->prefix = $prefix;
    _lib_u_nurse_hospital_manage_log_config(&$config);
    $this->so_config = $config;
    $this->so_config['TABLE'] = "�±��Ǹ��������";
    $this->drawer = new _lib_so_drawer($this);

    if (array_key_exists($prefix . 'history-at', $_REQUEST))
      $this->history_ix = $_REQUEST[$prefix . 'history-at'];
    else
      $this->history_ix = NULL;

    $db = mx_db_connect();
    $this->chosen = $this->config_dt();
    $this->id = _lib_u_nurse_hospital_manage_log_peek_id
      ($this, $db, $this->chosen);

  }

  function config_dt() {
    return sprintf("%04d-%02d-%02d", $this->so_config['Year'],
		   $this->so_config['Month'],
		   $this->so_config['Date']);
  }

  function reset($id=NULL) {
    $this->history_ls = $this->history_ix = NULL;
  }

  function chosen() {
    return $this->chosen;
  }

  function fetch_data($id=NULL) {
    $db = mx_db_connect();
    if (is_null($id))
      return _lib_u_nurse_hospital_manage_log_get_by_dt
	($this, $db, $this->config_dt());
    else
      return _lib_u_nurse_hospital_manage_log_fetch_data($this, $db, $id);
  }

  function history($move_direction=NULL) {
    if (is_null($this->id)) return 2;
    return simple_object_display::history($move_direction);
  }

  var $table_class = array('random-format', 'random-format-historical');
  var $history_in_body =1;

  function draw_body_3($data, $hdata, $dcols) {
    // Ugh.  random format

    print '<tr><th>����</th><td>';
    print $data['����'];
    print '</td><th>��ľ���</th>';
    $this->draw_body_atom($this->find_dcol('��ľ���̾', $dcols),
			  $data, $hdata, 3);
    print '<th colspan="2">��ľ���</th>';
    $this->draw_body_atom($this->find_dcol('��ľ���̾', $dcols),
			  $data, $hdata, 2);
    print "</tr>\n";

    print '<tr><th>����ô���Ǹ��</th>';
    $this->draw_body_atom($this->find_dcol('����ô���Ǹ��̾', $dcols),
			  $data, $hdata, 4);
    print '</td><th>���ʱ�����</th>';
    $this->draw_body_atom($this->find_dcol('���ʱ�����̾', $dcols),
			  $data, $hdata, 4);
    print "</tr>\n";

    print '<tr><th>�±����贵�Կ�</th>';
    $this->draw_body_atom($this->find_dcol('�±����贵�Կ�', $dcols),
			  $data, $hdata, 4);
    print '</td><th>���ʳ��贵�Կ�</th>';
    $this->draw_body_atom($this->find_dcol('���ʳ��贵�Կ�', $dcols),
			  $data, $hdata, 4);
    print "</tr>\n";

    $this->draw_ward_row_title();
    foreach ($this->ward_list as $ward => $ward_name)
      $this->draw_ward_row($data, $hdata, $dcols, $ward, $ward_name);
    $this->draw_ward_row($data, $hdata, $dcols, NULL, '��������');

    $this->draw_inout_row($data, $hdata, $dcols, '����');
    $this->draw_inout_row($data, $hdata, $dcols, '�ౡ');
    $this->draw_xfer_row($data, $hdata, $dcols, 'ž��');
    $this->draw_outstay_row($data, $hdata, $dcols, '����');

    print '<tr><th>�õ�����</th>';
    $desc = $this->find_dcol('�õ�����', $dcols);
    $this->draw_body_atom($this->find_dcol('�õ�����', $dcols),
			  $data, $hdata, 8);
    print "</tr>\n";

    print '<tr><th>��Ͽ��</th>';

    $this->draw_body_atom($this->find_dcol('CreatedBy', $dcols),
			  $data, $hdata, 2);

    if (is_null($this->history_ix))
      print '<td colspan="6">&nbsp;</td>';
    else {
      print '<td colspan="3">�ѹ������ॹ�����</td><td colspan="3">';
      print htmlspecialchars(mx_format_timestamp($data['Superseded']));
    }

    print "</td></tr>\n";
  }

  function draw_random_row_helper($data, $hdata, $colname, $rs, $pfx='') {
    $changed = ($data && $hdata && ($data[$colname] != $hdata[$colname]))
      ? ' class="changed"' : '';
    $rs = (1 < $rs) ? (' rowspan="' . $rs . '"') : '';
    print "<td$rs$changed>$pfx";
    print htmlspecialchars($data[$colname]);
    print "</td>";
  }

  function draw_ward_row_title() {
    print "<tr>";
    print "<th>����</th>";
    print "<th>��������</th>";
    print "<th>���/���Կ�</th>";
    print "<th colspan=\"2\">����</th>";
    print "<th colspan=\"3\">ž����ž��</th>";
    print "<th>ž��/ž��</th>";
    print "</tr>\n";
  }

  function draw_ward_row($data, $hdata, $dcols, $ward_id, $ward) {

    print '<tr>';
    print '<th rowspan="2">';
    if (! is_null($ward_id)) {
      print ('<a href="/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
	     '/u/nurse/wardlog.php?Ward=' . htmlspecialchars($ward_id) .
	     '&amp;WardName=' . htmlspecialchars($ward) .
	     '&amp;SetDate=' . htmlspecialchars($this->config_dt()) .
	     '">');
      print htmlspecialchars($ward);
      print "</a>";
    } else
      print htmlspecialchars($ward);

    print '</th>';

    $this->draw_ward_row_helper($data, $hdata, $ward, '��������', 2);

    $this->draw_ward_row_helper($data, $hdata, $ward, '�����¾���', 1);
    $this->draw_ward_row_helper($data, $hdata, $ward, '����', 2);
    $this->draw_ward_row_helper($data, $hdata, $ward, '��������', 1, '��');

    $this->draw_ward_row_helper($data, $hdata, $ward, 'ž����ž��', 2);
    $this->draw_ward_row_helper($data, $hdata, $ward, '�ڲ�', 1, '�ڲ�');
    $this->draw_ward_row_helper($data, $hdata, $ward, '��˴', 1, '��˴');

    $this->draw_ward_row_helper($data, $hdata, $ward, 'ž��', 1);
    print "</tr>\n";

    print '<tr>';
    $this->draw_ward_row_helper($data, $hdata, $ward, '���Կ�', 1);

    $this->draw_ward_row_helper($data, $hdata, $ward, '��������', 1, '��');
    $this->draw_ward_row_helper($data, $hdata, $ward, 'ž��', 1, 'ž��');
    $this->draw_ward_row_helper($data, $hdata, $ward, '¾��ž��', 1, '¾');

    $this->draw_ward_row_helper($data, $hdata, $ward, 'ž��', 1);
    print "</tr>\n";

  }

  function draw_ward_row_helper($data, $hdata, $ward, $colname, $rs, $pfx='') {
    $this->draw_random_row_helper($data, $hdata, "$ward.$colname", $rs, $pfx);
  }

  function draw_inout_row($data, $hdata, $dcols, $label) {

    print '<tr><th colspan="9">';
    print htmlspecialchars($label);
    print "</th></tr>\n";

    if ($data[$label]) {
      print '<tr>';
      print '<th>����</th><th>����</th><th>̾��</th><th>ǯ��</th>';
      print '<th colspan="5">����</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['����̾']);
	print '</td><td>';
	print htmlspecialchars($d['�¼�̾']);
	print '</td><td>';
	print htmlspecialchars($d['����̾']);
	print '</td><td>';
	print htmlspecialchars($d['����ǯ��']);
	print '</td><td colspan="5">';
	print htmlspecialchars($d['����']);
	print "</td></tr>\n";
      }
    } else
      print "<tr><td colspan=\"9\">&nbsp;</td></tr>\n";
  }

  function draw_xfer_row($data, $hdata, $dcols, $label) {
    print '<tr><th colspan="9">';
    print htmlspecialchars($label);
    print "</th></tr>\n";

    if ($data[$label]) {
      print '<tr>';
      print '<th>ž��������</th><th>����</th>';
      print '<th>̾��</th><th>ǯ��</th><th colspan="5">ž�︵����</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['����̾']);
	print '</td><td>';
	print htmlspecialchars($d['�¼�̾']);
	print '</td><td>';
	print htmlspecialchars($d['����̾']);
	print '</td><td>';
	print htmlspecialchars($d['����ǯ��']);
	print '</td><td colspan="5">';
	print htmlspecialchars($d['ž������̾']);
	print "</td></tr>\n";
      }

    } else
      print "<tr><td colspan=\"9\">&nbsp;</td></tr>\n";

  }

  function draw_outstay_row($data, $hdata, $dcols, $label) {
    print '<tr><th colspan="9">';
    print htmlspecialchars($label);
    print "</th></tr>\n";

    if ($data[$label]) {
      print '<tr>';
      print '<th>����</th><th>����</th><th>̾��</th><th>ǯ��</th>';
      print '<th colspan="2">����л���</th><th>��������</th>';
      print '<th colspan="2">����</th>';
      print "</tr>\n";

      foreach ($data[$label] as $d) {
	print '<tr><td>';
	print htmlspecialchars($d['����̾']);
	print '</td><td>';
	print htmlspecialchars($d['�¼�̾']);
	print '</td><td>';
	print htmlspecialchars($d['����̾']);
	print '</td><td>';
	print htmlspecialchars($d['����ǯ��']);
	print '</td><td colspan="2">';
	print htmlspecialchars($d['����л���']);
	print '</td><td>';
	print htmlspecialchars($d['��������']);
	print '</td><td colspan="2">';
	print htmlspecialchars($d['����']);
	print "</td></tr>\n";
      }
    } else
      print "<tr><td colspan=\"9\">&nbsp;</td></tr>\n";

  }

}

class hospital_manage_log_edit extends simple_object_edit {

  var $debug = 0;

  function hospital_manage_log_edit($prefix, $config=NULL) {

    global $_lib_u_manage_employee_cfg;

    if (is_null($config)) $config = array();

    $list_of_employees_dr_cfg =
      $list_of_employees_ns_cfg =
	$list_of_employees_pp_cfg = $_lib_u_manage_employee_cfg;

    $list_of_employees_dr_cfg['HSTMT'] .= '
      AND C."����" in ' . enum_doctor_cat_sql();
    $list_of_employees_ns_cfg['HSTMT'] .= '
      AND C."����" in ' . enum_nurse_cat_sql();

    $list_of_employees_dr_cfg['STMT'] =
      $list_of_employees_dr_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $list_of_employees_ns_cfg['STMT'] =
      $list_of_employees_ns_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $list_of_employees_pp_cfg['STMT'] =
      $list_of_employees_pp_cfg['HSTMT'] . ' AND E."Superseded" IS NULL';

    $config['TABLE'] = '�±��Ǹ��������';
    $config['ECOLS'] = array
      (array('Column' => '����', 'Draw' => NULL),

       array('Column' => '��ľ���', 'Draw' => NULL),
       array('Column' => '��ľ���̾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '���ΰ�դ����ꤹ��',
	      'Config' => $list_of_employees_dr_cfg,
	      'ListID' => array('ObjectID', '��̾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '��ľ���') ),

       array('Column' => '��ľ���', 'Draw' => NULL),
       array('Column' => '��ľ���̾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '���ΰ�դ����ꤹ��',
	      'Config' => $list_of_employees_dr_cfg,
	      'ListID' => array('ObjectID', '��̾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '��ľ���') ),

       array('Column' => '����ô���Ǹ��', 'Draw' => NULL),
       array('Column' => '����ô���Ǹ��̾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '���δǸ�դ����ꤹ��',
	      'Config' => $list_of_employees_ns_cfg,
	      'ListID' => array('ObjectID', '��̾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '����ô���Ǹ��') ),

       array('Column' => '���ʱ�����', 'Draw' => NULL),
       array('Column' => '���ʱ�����̾',
	     'Draw' => 'Subpick',
	     'Subpick' => array
	     ('Class' => 'list_of_employees',
	      'Message' => '���δǸ�դ����ꤹ��',
	      'Config' => $list_of_employees_pp_cfg, // # NEEDSWORK
	      'ListID' => array('ObjectID', '��̾'),
	      'Allow_NULL' => 0,
	      'ObjectColumn' => '���ʱ�����') ),

       array('Column' => '�±����贵�Կ�', 'Draw' => 'text'),
       array('Column' => '���ʳ��贵�Կ�', 'Draw' => 'text'),

       array('Column' => '�õ�����',
	     'Draw' => 'textarea'),
       // employee numbers left unspecified
       );
    $config['ICOLS'] = array
	    ('����', '��ľ���', '��ľ���', '����ô���Ǹ��',
	     '���ʱ�����', '�±����贵�Կ�', '���ʳ��贵�Կ�', '�õ�����');

    simple_object_edit::simple_object_edit($prefix, $config);

  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_hospital_manage_log_fetch_data(&$this, $db, $id);
  }

  function resync() {
    $this->data = $this->fetch_data($this->id);
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function _validate() {

    $bad = 0;
    foreach (array('��ľ���', '��ľ���', '����ô���Ǹ��', '���ʱ�����')
	     as $not_null_col) {
      if ($st = mx_db_validate_length($this->data[$not_null_col], 1, 0)) {
	$this->err("($not_null_col): $st\n");
	$bad++;
      }
    }
    foreach (array('�±����贵�Կ�', '���ʳ��贵�Կ�')
	     as $not_null_col) {
      if ($st = mx_db_validate_nnint($this->data[$not_null_col])) {
	$this->err("($not_null_col): $st\n");
	$bad++;
      }
    }

    if (! $bad)
      return 'ok';

  }

  function edit($chosen) {
    $this->chosen = $chosen;
    $db = mx_db_connect();
    $id = _lib_u_nurse_hospital_manage_log_peek_id
      ($this, $db, $this->chosen);
    simple_object_edit::edit($id);
    if (is_null($id))
      $this->data =
	_lib_u_nurse_hospital_manage_log_get_by_dt(&$this, $db, $chosen);

  }

  function _broken_origin_check() {
     if ($this->id == '') {
	     $db = mx_db_connect();
	     $id = _lib_u_nurse_hospital_manage_log_peek_id
		     ($this, $db, $this->data['����']);
	     if (is_null($id)) {
		     $this->broken_origin = 0;
		     return 0;
	     }
	     $this->id = $id;
	     $this->broken_origin = 1;
	     return 1;
     }
     else
        return simple_object_edit::_broken_origin_check();
  }

}
?>
