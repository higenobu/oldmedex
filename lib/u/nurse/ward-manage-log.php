<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-employee-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-patient.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-room.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward.php';

function _lib_u_nurse_ward_manage_log_A1C4($ty=NULL) {
  if (is_null($ty))
    return array("A1", "A2", "A3", "A4",
		 "B1", "B2", "B3", "B4",
		 "C1", "C2", "C3", "C4");
  $l = array();
  foreach (_lib_u_nurse_ward_manage_log_A1C4(NULL) as $elt) {
    $l[] = 'L.' . mx_db_sql_quote_name($elt);
  }
  return implode(', ', $l);
}

function _lib_u_nurse_ward_manage_log_prepare_config(&$config)
{
  $config['TABLE'] = '�����������';
  $config['COLS'] = array('unused');
  $config['ECOLS'] = array();
  $config['Pages'] = array
    ('����' => array_merge(array("����", "����", "ô��", "����", "����"),
			   _lib_u_nurse_ward_manage_log_A1C4(),
			   array("����", "��������",
				 "��������", "��������", "�ڲ�", "ž��",
				 "��˴", "¾��ž��", "ž��", "ž��",
				 "��Ĺ", "��Ĺ", "����̾", "�����¾���",
				 "��Ĺ̾", "��Ĺ̾")),
     '����' => array("����", "����̾", "����������"),
     'ž��ž��' => array("����", "�¼�", "����", "ž������", "����",
			 "����̾", "����ID", "����ǯ��",
			 "ž������̾", "�¼�̾"),
     'ž��' => array("����", "ž�����¼�", "ž�����¼�",
		     "����̾", "����ID", "����ǯ��",
		     "ž�����¼�̾", "ž�����¼�̾"),
     '���񡦳���' => array("����", "�¼�", "����л���", "��������",
			   "����������", "����",
			   "����̾", "����ID", "����ǯ��",
			   "�¼�̾"),
     '���Դ����õ�����' => array("����", "�¼�",
				 "�������õ�����", "������õ�����",
				 "����̾", "����ID", "����ǯ��",
				 "�¼�̾"),
     '�߸˴���' => array("̾��", "�߸˿�", "����������"));

  $config['ICOLS'] = array_merge(array("����", "����", "ô��", "����", "����"),
				 _lib_u_nurse_ward_manage_log_A1C4(),
				 array("����",
				       "��������", "��������", "��������",
				       "�ڲ�", "ž��", "��˴", "¾��ž��",
				       "ž��", "ž��", "��Ĺ", "��Ĺ"));
}

function _lib_u_nurse_ward_manage_log_peek_id(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT "ObjectID" FROM "�����������"
WHERE "����" = ' . mx_db_sql_quote($ward) . '
AND "����" = ' . mx_db_sql_quote($dt) . '
AND "Superseded" IS NULL');
  $it->dbglog("CHECK-EXISTS: $stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  if (! is_array($r) || count($r) != 1)
    return NULL;
  if (! is_null($r[0]['ObjectID']))
    return $r[0]['ObjectID'];
  return NULL;
}

function _lib_u_nurse_ward_manage_log_get_by_dt_ward(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT WML."ObjectID", W."����̾", sum(R."���") AS "�����¾���"
            FROM "�������ɽ" AS W
            JOIN "�¼�����ɽ" AS R
            ON W."ObjectID" = R."����" AND
               W."Superseded" IS NULL AND R."Superseded" IS NULL
            LEFT JOIN "�����������" AS WML
            ON W."ObjectID" = WML."����" AND
	       WML."Superseded" IS NULL AND
               WML."����" = ' . mx_db_sql_quote($dt) . '
            WHERE W."ObjectID" = ' . mx_db_sql_quote($ward) . '
            GROUP BY WML."ObjectID", W."����̾";');
  $it->dbglog("CHECK-EXISTS: $stmt;\n");
  $r = pg_fetch_all(pg_query($db, $stmt));
  if (! is_array($r) || count($r) != 1)
    die('Whoa');

  if (! is_null($r[0]['ObjectID']))
    return
      _lib_u_nurse_ward_manage_log_fetch_data($it->debug, $db,
					      $r[0]['ObjectID']);
  $a = array('CreatedBy' => NULL,
	     'ObjectID' => NULL,
	     'Superseded' => NULL,
	     '����' => array(array('����' => $dt,
				   '����' => $ward,
				   '����̾' => $r[0]['����̾'],
				   '�����¾���' => $r[0]['�����¾���'])) );
  foreach ($cf['Pages'] as $slot => $cfg)
    if (! array_key_exists($slot, $a)) $a[$slot] = array();

  // Annotate with default inventory hints
  $hints = _lib_u_nurse_ward_manage_log_neigh_hints_by_dt_ward
    ($it, $cf, $db, $dt, $ward);

  if (is_array($hints)) {
    foreach ($hints['�߸˴���'] as $row)
      $a['�߸˴���'][] = mx_pick_array($row, '̾��', '����������');

    foreach ($hints['���񡦳���'] as $row)
      if (is_null($row['��������']))
	$a['���񡦳���'][] = mx_pick_array
	  ($row,
	   '����', '����̾', '����ID', '����ǯ��', '�¼�', '�¼�̾',
	   '����������');

  }
  return $a;
}

function _lib_u_nurse_ward_manage_log_neigh_hints_by_dt_ward(&$it, $cf, $db, $dt, $ward)
{
  $stmt = ('SELECT WML."ObjectID"
FROM "�����������" AS WML
WHERE WML."Superseded" IS NULL AND WML."����" < ' . mx_db_sql_quote($dt) . '
AND WML."����" = ' .mx_db_sql_quote($ward) . '
ORDER BY WML."����" DESC
LIMIT 1');
  $it->dbglog("PRV -- $stmt;\n");
  $hint_oid = mx_db_fetch_single($db, $stmt);
  if (! is_array($hint_oid) || ! array_key_exists('ObjectID', $hint_oid))
    return NULL;
  return _lib_u_nurse_ward_manage_log_fetch_data($it->debug, $db,
						 $hint_oid['ObjectID']);
}

function _lib_u_nurse_ward_manage_log_compare_data($new, $org, $cfg)
{
  $diff = 0;
  foreach ($cfg['Pages'] as $page => $conf) {
    if ($diff) break;
    $o = $org[$page]; $n =& $new[$page];
    if (count($o) != count($n))
      $diff = 1;
    else {
      $cnt = count($o);
      for ($ix = 0; $ix < $cnt; $ix++) {
	if ($diff) break;
	foreach ($conf as $col) {
	  if ($o[$ix][$col] != $n[$ix][$col]) {
	    $diff = 1;
	    break;
	  }
	}
      }
    }
  }
  return $diff;
}

function __d($debug, $db, $stmt, &$data, $column) {
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d))
    $data[$column] = $d;
  else
    $data[$column] = array();

  if ($debug) {
    print "<!--\n$stmt;\n";
    if (is_array($d))
      print "Returned " . count($d) . " items\n";
    elseif (! is_array($d) && ! is_bool($d)) {
      print var_dump($d);
      print "Error?\n";
    }
    print "-->\n";
  }
}

function _lib_u_nurse_ward_manage_log_fetch_data($debug, $db, $oid)
{
  $stmt = ('SELECT L."CreatedBy", L."ObjectID", L."ID", L."Superseded",
		   L."����", L."����", L."ô��", L."����", L."����", '.
	   _lib_u_nurse_ward_manage_log_A1C4('L') . ',
	           L."����", L."��������",
		   L."��������", L."��������", L."�ڲ�", L."ž��",
		   L."��˴", L."¾��ž��", L."ž��", L."ž��",
		   L."��Ĺ", L."��Ĺ", W."����̾",
		   sum(R."���") AS "�����¾���",
		   (E0."��" || \' \' || E0."̾") AS "��Ĺ̾",
		   (E1."��" || \' \' || E1."̾") AS "��Ĺ̾"
	    FROM "�����������" AS L JOIN "�������ɽ" AS W
	    ON L."����" = W."ObjectID" AND W."Superseded" IS NULL
	    JOIN "�¼�����ɽ" AS R
	    ON R."����" = W."ObjectID" AND R."Superseded" IS NULL
	    LEFT JOIN "������Ģ" AS E0
	    ON L."��Ĺ" = E0."ObjectID" AND E0."Superseded" IS NULL
	    LEFT JOIN "������Ģ" AS E1
	    ON L."��Ĺ" = E1."ObjectID" AND E1."Superseded" IS NULL
	    WHERE L."ObjectID" = ' . mx_db_sql_quote($oid) .
	   ' GROUP BY
	    L."CreatedBy", L."ObjectID", L."ID", L."Superseded",
	    L."����", L."����", L."ô��", L."����", L."����", ' .
	   _lib_u_nurse_ward_manage_log_A1C4('L') . ',
            L."����", L."��������",
	    L."��������", L."��������", L."�ڲ�", L."ž��",
	    L."��˴", L."¾��ž��", L."ž��", L."ž��",
	    L."��Ĺ", L."��Ĺ", W."����̾",
	    "��Ĺ̾",
	    "��Ĺ̾"'); // Ugh.
  $data = array();
  __d($debug, $db, $stmt, &$data, '����');
  $data['CreatedBy'] = $data['����'][0]['CreatedBy'];
  $data['ObjectID'] = $oid;

  $stmt = ('SELECT X."����", (E."��" || \' \' || E."̾") AS "����̾",
            X."����������"
            FROM "��������������" AS X JOIN "������Ģ" AS E
            ON X."����" = E."ObjectID" AND E."Superseded" IS NULL
            WHERE X."�����������" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY E."����ID"');
  __d($debug, $db, $stmt, &$data, '����');

  $pex = ('(P."��" || \' \' || P."̾") AS "����̾", P."����ID",
           (extract(year from age(timestamp \'' .
	  $data['����'][0]['����'] .
	  '\', P."��ǯ����"))) AS "����ǯ��"');

  $stmt = ('SELECT X."����", X."�¼�", X."����", X."ž������", X."����",
            ' . $pex . ',
            W."����̾" AS "ž������̾", R."�¼�̾"
            FROM "����������ž��ž��" AS X JOIN "������Ģ" AS P
            ON X."����" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "�¼�����ɽ" AS R
            ON X."�¼�" = R."ObjectID" AND R."Superseded" IS NULL
            LEFT JOIN "�������ɽ" AS W
            ON X."ž������" = W."ObjectID" AND W."Superseded" IS NULL
            WHERE X."�����������" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."�¼�̾", P."����ID"');
  __d($debug, $db, $stmt, &$data, 'ž��ž��');

  $stmt = ('SELECT X."����", X."ž�����¼�", X."ž�����¼�",
            ' . $pex . ',
            R0."�¼�̾" AS "ž�����¼�̾",
            R1."�¼�̾" AS "ž�����¼�̾"
            FROM "����������ž��" AS X JOIN "������Ģ" AS P
            ON X."����" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "�¼�����ɽ" AS R0
            ON X."ž�����¼�" = R0."ObjectID" AND R0."Superseded" IS NULL
            JOIN "�¼�����ɽ" AS R1
            ON X."ž�����¼�" = R1."ObjectID" AND R1."Superseded" IS NULL
            WHERE X."�����������" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY P."����ID"');
  __d($debug, $db, $stmt, &$data, 'ž��');

  $stmt = ('SELECT X."����", X."�¼�", X."����л���", X."��������",
            X."����������", X."����",
            ' . $pex . ', R."�¼�̾"
            FROM "�������������񡦳���" AS X JOIN "������Ģ" AS P
            ON X."����" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "�¼�����ɽ" AS R
            ON X."�¼�" = R."ObjectID" AND R."Superseded" IS NULL
            WHERE X."�����������" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."�¼�̾", P."����ID"');
  __d($debug, $db, $stmt, &$data, '���񡦳���');

  $stmt = ('SELECT X."����", X."�¼�", X."�������õ�����", X."������õ�����",
            ' . $pex . ',
            R."�¼�̾"
            FROM "�������������Դ����õ�����" AS X JOIN "������Ģ" AS P
            ON X."����" = P."ObjectID" AND P."Superseded" IS NULL
            JOIN "�¼�����ɽ" AS R
            ON X."�¼�" = R."ObjectID" AND R."Superseded" IS NULL
            WHERE X."�����������" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY R."�¼�̾", P."����ID"');
  __d($debug, $db, $stmt, &$data, '���Դ����õ�����');

  $stmt = ('SELECT X."̾��", X."�߸˿�", X."����������"
            FROM "�����������߸˴���" AS X
            WHERE X."�����������" = ' . mx_db_sql_quote($oid) .
	   ' ORDER BY X."̾��"');
  __d($debug, $db, $stmt, &$data, '�߸˴���');

  return $data;
}

$_lib_u_nurse_ward_manage_log_employee_work = array
  ('����', '���', '�ٽ�', '��ľ����', '��ľ����', '����', '����',
   '���', 'ͭ��', '���̵ٲ�', '��ĥ', '����', '����', '�·�');

class ward_manage_log_display0 extends simple_object_display {

  var $debug = NULL;

  var $logmsg = '';
  var $inventory_label = array('����̾' => 2, 'ʪ��̾' => 3);

  function ward_manage_log_display0($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_manage_log_employee_work;
    $this->employee_work = $_lib_u_nurse_ward_manage_log_employee_work;
    $this->prefix = $prefix;
    $this->so_config = $config;
    _lib_u_nurse_ward_manage_log_prepare_config($this->so_config);
    $this->drawer = new _lib_so_drawer($this);

    $this->chosen = array($config['Year'],
			  $config['Month'],
			  $config['Date'],
			  $config['Ward']);
    if (array_key_exists($prefix . 'history-at', $_REQUEST))
      $this->history_ix = $_REQUEST[$prefix . 'history-at'];
    else
      $this->history_ix = NULL;

    // We only need id not data.
    $db = mx_db_connect();
    $chosen = $this->chosen;
    $ward = $chosen[3];
    $dt = sprintf("%04d-%02d-%02d", $chosen[0], $chosen[1], $chosen[2]);
    $this->id = _lib_u_nurse_ward_manage_log_peek_id
      ($this, $this->so_config, $db, $dt, $ward);
  }

  function reset($id=NULL) {
    $this->history_ls = $this->history_ix = NULL;
  }

  function chosen() {
    return $this->chosen;
  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_nurse_ward_manage_log_fetch_data($this->debug, $db, $id);
  }

  function _check($v) {
    if ($v != '')
      return mx_img_url('check.png');
    else
      return '';
  }

  function _thtd($thtd, $colspan, $rowspan, $a) {
    array_splice($a, 0, 2);
    $a = join(' ', $a);
    if ($a == '') $a = '&nbsp;';

    if ($colspan) print "<$thtd colspan=\"$colspan\"";
    else print "<$thtd";
    if ($rowspan) print " rowspan=\"$rowspan\"";
    print ">"; // may add class later here
    print $a;
    print "</$thtd>\n";
  }

  function _th($colspan, $rowspan) {
    $a = func_get_args();
    $this->_thtd('th', $colspan, $rowspan, $a);
  }

  function _td($colspan, $rowspan) {
    $a = func_get_args();
    $this->_thtd('td', $colspan, $rowspan, $a);
    return;

    $a = func_get_args();
    array_splice($a, 0, 2);
    $a = join(' ', $a);
    if ($a == '') $a = '&nbsp;';

    if ($colspan) print "<td colspan=\"$colspan\"";
    else print "<td";
    if ($rowspan) print " rowspan=\"$rowspan\"";
    print ">"; // may add class later here
    print $a;
    print "</td>\n";
  }

  function nps($v, $unit='̾') { // Num People String
    if ($v) return "$v$unit";
    if ($v != '' && $v == 0)
      return "0$unit";
    return '';
  }

  function draw() {

    if ($this->debug) {
      print "<!--\n";
      var_dump($data);
      print "-->\n";
    }

    if (! is_null($this->id))
      $this->history();

    if (is_null($this->history_ix)) {
      // Showing the latest.
      $id = $this->id;
      if (is_array($this->history_ls) && count($this->history_ls)) {
	// Let's compare the latest with one before since it exists.
	$cid = $this->history_ls[count($this->history_ls)-1]['ObjectID'];
	$hdata =& $this->fetch_data($cid);
      }
      else
	$hdata = NULL;
      $chosen = $this->chosen;
      $ward = $chosen[3];
      $db = mx_db_connect();
      $dt = sprintf("%04d-%02d-%02d", $chosen[0], $chosen[1], $chosen[2]);
      $data = _lib_u_nurse_ward_manage_log_get_by_dt_ward
	(&$this, $this->so_config, $db, $dt, $ward);
    }
    else {
      // Showing something from history.
      $id = $this->history_ls[$this->history_ix]['ObjectID'];
      mx_formi_hidden($this->prefix . 'history-at', $this->history_ix);

      // We are lookinig at history item $this->history_ix (0 being the
      // oldest).  Fetch one after that one so that we can compare what
      // got overwritten.
      $cid = $this->history_ix + 1;
      if (count($this->history_ls) <= $cid)
	$cid = $this->id; // comparison against the current
      else
	// comparison against the one after
	$cid = $this->history_ls[$cid]['ObjectID'];
      $data = $this->fetch_data($id);
      $hdata = $this->fetch_data($cid);
      // we are going to show $data and give comparison to $hdata
    }

    if ($this->logmsg != '') {
      print "<!--\n";
      print $this->logmsg;
      print "-->\n";
    }

    if (is_null($this->history_ix))
      print '<table class="random-format">';
    else
      print '<table class="random-format-historical">';

    $this->draw_summary_rows($data, $hdata);

    $this->draw_employee_rows($data, $hdata);

    $this->draw_external_transfer_rows($data, $hdata);

    $this->draw_transfer_rows($data, $hdata);

    $this->draw_temporary_out_rows($data, $hdata);

    $this->draw_patient_notes_rows($data, $hdata);

    $this->draw_inventory_rows($data, $hdata);

    $this->draw_ward_notes_rows($data, $hdata);

    $this->draw_superseded_notes($data, $hdata);

    print "</table>\n";
  }

  function draw_superseded_notes($data, $hdata) {
    $d = $data['����'][0];
    if (! is_null($hdata)) {
      $h = $hdata['����'][0];
      $changed = ($d['CreatedBy'] != $h['CreatedBy']);
    }
    print '<tr><td colspan="2">��Ͽ��</td>';
    if ($changed) print '<td colspan="2" class="changed">';
    else print '<td colspan="2">';
    $this->dx_user(array(), $d['CreatedBy'], $changed);
    print '</td>';
    if (is_null($this->history_ix))
      print '<td colspan="5">&nbsp;</td>';
    else {
      print '<td colspan="2">�ѹ������ॹ�����</td><td colspan="3">';
      print htmlspecialchars(mx_format_timestamp($d['Superseded']));
    }
    print "</tr>\n";
  }

  function number_of_people_in_categories($d) {
    $a = func_get_args();
    array_shift($a);
    $s = $found_non_empty = 0;
    foreach ($a as $k) {
      $tdk = trim($d[$k]);
      $s += $tdk;
      if (!$found_non_empty && $tdk != '')
	$found_non_empty = 1;
    }
    if ($found_non_empty)
      return $s;
    return NULL;
  }

  function draw_summary_rows($data, $hdata) {

    $d = $data['����'][0];

    print '<tr>';
    $this->_td(4, 0, '����:', $d['����']);
    $this->_td(5, 0, '�����������:', $d['����̾']);
    print "</tr>\n";

    print '<tr>';
    $this->_th(2, 0, '��������');
    $this->_th(2, 0, '����');
    $this->_th(3, 0, 'ž����ž��');
    $this->_th(0, 0, 'ž��');
    $this->_th(0, 0, 'ž��');
    print "</tr>\n";

    $discharge_sum = $this->number_of_people_in_categories
      ($d, "�ڲ�","ž��","��˴","¾��ž��");
    $hospitalized_sum = $this->number_of_people_in_categories
      ($d, '��������', '��������');

    print '<tr>';
    $this->_td(2, 2, $this->nps($d['��������'], '��'));
    $this->_td(0, 2, $this->nps($hospitalized_sum));
    $this->_td(0, 0, '��:', $this->nps($d['��������']));
    $this->_td(1, 2, $this->nps($discharge_sum));
    $this->_td(0, 0, '�ڲ�:', $this->nps($d["�ڲ�"]));
    $this->_td(0, 0, '��˴:', $this->nps($d["��˴"]));
    $this->_td(0, 2, $this->nps($d['ž��']));
    $this->_td(0, 2, $this->nps($d['ž��']));
    print "</tr>\n";

    print '<tr>';
    $this->_td(0, 0, '��:', $this->nps($d['��������']));
    $this->_td(0, 0, 'ž��:', $this->nps($d["ž��"]));
    $this->_td(0, 0, '¾:', $this->nps($d["¾��ž��"]));
    print "</tr>\n";

    $current = $this->number_of_people_in_categories
      ($d, 'ô��', '����', '����');

    print '<tr>';
    $this->_td(4, 0, '�����¾���', $this->nps($d['�����¾���'], '��'));
    $this->_td(5, 0, '���߿�', $this->nps($current));
    print "</tr>\n";

    print '<tr>';
    $this->_td(3, 0, 'ô��', $this->nps($d['ô��']));
    $this->_td(3, 0, '����', $this->nps($d['����']));
    $this->_td(3, 0, '����', $this->nps($d['����']));
    print "</tr>\n";

    print '<tr>';
    $this->_th(9, 0, '�Ǹ����̴��Կ�');
    print "</tr>\n";
    foreach (array('A', 'B', 'C') as $c0) {
      print '<tr>';
      foreach (array('1' => 2, '2' => 2, '3' => 2, '4' => 3) as $c1 => $cs)
	$this->_td($cs, 0, "$c0$c1", $this->nps($d["$c0$c1"]));
      print "</tr>\n";
    }

  }

  function draw_ward_notes_rows($data, $hdata) {
    print '<tr>';
    $this->_th(9, 0, '����');
    print "</tr>\n";
    print '<tr>';
    $this->_td(9, 0, mx_html_paragraph($data['����'][0]['����']));
    print "</tr>\n";
  }

  function draw_inventory_rows($data, $hdata) {
    if (count($data['�߸˴���']) == 0) return;
    $tr = $data['�߸˴���'];

    print '<tr>';
    $this->_th(9, 0, '�߸˴���');
    print "</tr>\n";

    $d = array();
    foreach ($tr as $e) {
      $d[$e['����������']][] = $e;
    }
    $lim = 0;
    foreach ($d as $col => $row) {
      if ($lim < count($row)) $lim = count($row);
    }

    print "<tr>";
    foreach ($this->inventory_label as $label => $colspan) {
      $this->_td($colspan, 0, $label);
      $this->_td(2, 0, '�߸˿�');
    }
    print "</tr>\n";

    for ($ix = 0; $ix < $lim; $ix++) {
      print "<tr>";
      $iy = 0;
      foreach ($this->inventory_label as $label => $colspan) {
	if (count($d[$iy]) <= $ix) {
	  $this->_td($colspan, 0, '');
	  $this->_td(2, 0, '');
	} else {
	  $e = $d[$iy][$ix];
	  $this->_td($colspan, 0, htmlspecialchars($e['̾��']));
	  $this->_td(2, 0, htmlspecialchars($e['�߸˿�']));
	}
	$iy++;
      }
      print "</tr>\n";
    }
  }

  function draw_external_transfer_rows($data, $hdata) {
    $ih = array(); // ����
    $it = array(); // ž��
    $oh = array(); // �ౡ
    $ot = array(); // ž��
    foreach ($data['ž��ž��'] as $e) {
      switch ($e['����']) {
      case 'i': $it[] = $e; break;
      case 'I': $ih[] = $e; break;
      case 'o': $ot[] = $e; break;
      case 'O': $oh[] = $e; break;
      }
    }
    $this->draw_inout_row('����', '����', $ih);
    $this->draw_inout_row('ž��', 'ž������̾', $it);
    $this->draw_inout_row('ž��', 'ž������̾', $ot);
    $this->draw_inout_row('�ౡ', '����', $oh);
  }

  function draw_employee_rows($data, $hdata) {
    print '<tr>';
    $this->_th(9, 0, '����');
    print "</tr>\n";

    foreach ($this->employee_work as $ix => $label) {
	    print '<tr><th colspan="2">';
	    print htmlspecialchars($label);
	    print '</th>';
	    $a = '';
	    foreach ($data['����'] as $e) {
		    if ($e['����������'] == $ix)
			    $a = $a . $e['����̾'] . " ";
	    }
	    $this->_td(7, 0, $a);
	    print "</tr>\n";
    }
  }

  function draw_patient_notes_rows($data, $hdata) {
    if (0 == count($data['���Դ����õ�����'])) return;

    $tr = $data['���Դ����õ�����'];
    print '<tr>';
    $this->_th(9, 0, '���Դ����õ�����');
    print "</tr>\n";

    print '<tr>';
    $this->_td(1, 0, '�����ֹ�');
    $this->_td(1, 0, '����̾');
    $this->_td(1, 0, 'ǯ��');
    $this->_td(3, 0, '�������õ�����');
    $this->_td(3, 0, '������õ�����');
    print "</tr>\n";

    foreach ($tr as $e) {
      print '<tr>';
      $this->_td(1, 0, $e['�¼�̾']);
      $this->_td(1, 0, $e['����̾']);
      $this->_td(1, 0, $e['����ǯ��']);
      $this->_td(3, 0, htmlspecialchars($e['�������õ�����']));
      $this->_td(3, 0, htmlspecialchars($e['������õ�����']));
      print "</tr>\n";
    }
  }

  function draw_temporary_out_rows($data, $hdata) {
    if (0 == count($data['���񡦳���'])) return;

    $tr = $data['���񡦳���'];
    print '<tr>';
    $this->_th(9, 0, '���񡦳���');
    print "</tr>\n";

    print '<tr>';
    $this->_td(1, 0, '�����ֹ�');
    $this->_td(1, 0, '����̾');
    $this->_td(1, 0, '�񡦽�');
    $this->_td(2, 0, '����л���');
    $this->_td(2, 0, '��������');
    $this->_td(2, 0, '����');
    print "</tr>\n";

    foreach ($tr as $e) {
      switch ($e['����������']) {
      case 0: $stay_shape = '����'; break;
      case 1: $stay_shape = '����'; break;
      }
      print '<tr>';
      $this->_td(1, 0, $e['�¼�̾']);
      $this->_td(1, 0, $e['����̾']);
      $this->_td(1, 0, $stay_shape);
      $this->_td(2, 0, $e['����л���']);
      $this->_td(2, 0, $e['��������']);
      $this->_td(2, 0, htmlspecialchars($e['����']));
      print "</tr>\n";
    }
  }

  function draw_transfer_rows($data, $hdata) {
    if (0 == count($data['ž��'])) return;

    $tr = $data['ž��'];
    print '<tr>';
    $this->_th(9, 0, 'ž��');
    print "</tr>\n";

    print '<tr>';
    $this->_td(2, 0, '����̾');
    $this->_td(2, 0, '�����ֹ梪�����ֹ�');
    $this->_td(2, 0, '����̾');
    $this->_td(3, 0, '�����ֹ梪�����ֹ�');
    print "</tr>\n";

    $lim = count($tr);
    if ($lim % 2) { $lim++; }
    for ($ix = 0; $ix < $lim; $ix += 2) {
      print '<tr>';

      $n = $tr[$ix]['����̾'];
      $t = $tr[$ix]['ž�����¼�̾'] . '��' .  $tr[$ix]['ž�����¼�̾'];
      $this->_td(2, 0, $n);
      $this->_td(2, 0, $t);

      if ($ix+1 < count($tr)) {
	$n = $tr[$ix+1]['����̾'];
	$t = $tr[$ix+1]['ž�����¼�̾'] . '��' .  $tr[$ix+1]['ž�����¼�̾'];
      } else {
	$n = $t = '&nbsp;';
      }
      $this->_td(2, 0, $n);
      $this->_td(3, 0, $t);

      print "</tr>\n";
    }
  }

  function draw_inout_row($label, $foo, $data) {
    if (count($data)) {
      print '<tr>';
      $this->_th(9, 0, $label);
      print "</tr>\n";

      print '<tr>';
      $this->_td(1, 0, "�����ֹ�");
      $this->_td(1, 0, "�����ֹ�");
      $this->_td(1, 0, "����̾");
      $this->_td(1, 0, "ǯ��");
      $this->_td(5, 0, $foo);
      print "</tr>\n";

      foreach ($data as $e) {
	$comment = $e['ž������̾'];
	if ($e['����'])
	  $comment = $e['����'];
	print '<tr>';
	$this->_td(1, 0, $e['�¼�̾']);
	$this->_td(1, 0, $e['����ID']);
	$this->_td(1, 0, $e['����̾']);
	$this->_td(1, 0, $e['����ǯ��']);
	$this->_td(5, 0, htmlspecialchars($comment));
	print "</tr>\n";
      }
    }
  }

}

class ward_manage_log_display extends ward_manage_log_display0 {
}

class ward_manage_log_edit extends simple_object_edit {

  function ward_manage_log_edit($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_manage_log_employee_work;
    $this->employee_work = $_lib_u_nurse_ward_manage_log_employee_work;
    $this->_Subpicker = NULL;
    if (is_null($config)) { $config = array(); }
    _lib_u_nurse_ward_manage_log_prepare_config(&$config);
    simple_object_edit::simple_object_edit($prefix, $config);
  }

  function resync() {
    $this->data = $this->fetch_data($this->id);
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;
  }

  function edit($chosen) {

    $db = mx_db_connect();
    $year = $chosen[0];
    $month = $chosen[1];
    $date = $chosen[2];
    $ward = $chosen[3];

    $dt = sprintf("%04d-%02d-%02d", $year, $month, $date);
    $this->data = _lib_u_nurse_ward_manage_log_get_by_dt_ward
      ($this, $this->so_config, $db, $dt, $ward);
    $this->id = $this->data['ObjectID'];

    if ($this->debug) {
      print "<!-- EDIT\n";
      var_dump($this);
      print "-->\n";
    }

    $this->_Subpicker = NULL;
    $this->chosen = 1;
  }

  function chosen() { return $this->chosen; }

  function fetch_data($id) {
    $db = mx_db_connect();
    $data = _lib_u_nurse_ward_manage_log_fetch_data($this->debug, $db, $id);
    $dd = array('CreatedBy' => $data['CreatedBy'],
		'ObjectID' => $data['ObjectID']);

    $page_num = 0;
    foreach ($this->so_config['Pages'] as $page_name => $cfg) {
      $it = array();
      foreach ($data[$page_name] as $row) {
	$i = array();
	foreach ($cfg as $colname)
	  if (! is_null($row[$colname]))
	    $i[$colname] = trim($row[$colname]);
	  else
	    $i[$colname] = NULL;
	$it[] = $i;
      }
      $dd[$page_name] = $it;
      $page_num++;
    }
    $this->annotate_row_data(&$dd);
    return $dd;
  }

  function annotate_row_data(&$d) {
    $ws_null_col = array('����' => array('��Ĺ', '��Ĺ'),
			 'ž��ž��' => array('ž������'),
			 '���񡦳���' => array('����л���', '��������'),
			 );
    // First sanitize
    foreach ($this->so_config['Pages'] as $page => $cfg)
      foreach ($d[$page] as $ix => $row)
	foreach ($cfg as $col) {
	  if (! is_null($row[$col]))
	    $d[$page][$ix][$col] = trim($row[$col]);
	}

    // Then nullify
    foreach ($ws_null_col as $page => $cfg)
      foreach ($d[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if ($row[$col] == '')
	    $d[$page][$ix][$col] = NULL;
  }

  function annotate_form_data() {
    global
      $_lib_u_nurse_ward_employee_pick_cfg,
      $_lib_u_nurse_ward_patient_cfg,
      $_lib_u_nurse_ward_room_cfg;

    if (array_key_exists($this->prefix . 'page-to', $_REQUEST))
      $this->page = $_REQUEST[$this->prefix . 'page-to'];
    elseif (array_key_exists($this->prefix . 'page', $_REQUEST))
      $this->page = $_REQUEST[$this->prefix . 'page'];
    else
      $this->page = 0;
    $page_num = 0;
    foreach ($this->so_config['Pages'] as $page_name => $cfg) {
      $num_items = $_REQUEST[$this->prefix . $page_num . '-total'];
      $data = array();
      for ($ix = 0; $ix < $num_items; $ix++) {
	$it = array();
	foreach ($cfg as $colname) {
	  $cnx = mx_form_encode_name($colname);
	  $it[$colname] = $_REQUEST[$this->prefix . $page_num .
				    '-data-' . $ix . '-' . $cnx];
	}
	$data[] = $it;
      }
      $this->data[$page_name] = $data;
      $page_num++;
    }

    // Handle subpicks, addrows, etc.
    // Note that this should be done *after* the above code slurped
    // the values into $this->data[]; otherwise the row numbers will
    // become inconsistent.

    if (array_key_exists($this->prefix . 'AddRow', $_REQUEST)) {
      $addrow = $_REQUEST[$this->prefix . 'AddRow'];
      $add_switch = array('2-I' => array('ž��ž��', array('����' => 'I')),
			  '2-i' => array('ž��ž��', array('����' => 'i')),
			  '2-o' => array('ž��ž��', array('����' => 'o')),
			  '2-O' => array('ž��ž��', array('����' => 'O')),
			  '3' => array('ž��', array()),
			  '4-0' => array('���񡦳���',
					 array('����������' => 0)),
			  '4-1' => array('���񡦳���',
					 array('����������' => 1)),
			  '5' => array('���Դ����õ�����', array()),
			  '6-0' => array('�߸˴���', array('����������' => 0)),
			  '6-1' => array('�߸˴���', array('����������' => 1)),
			  );
      if (array_key_exists($addrow, $add_switch)) {
	$as = $add_switch[$addrow];
	$this->data[$as[0]][] = $as[1];
      }
    }

    if (array_key_exists($this->prefix . 'DelRow', $_REQUEST)) {
      $delrow = $_REQUEST[$this->prefix . 'DelRow'];
      $delkey = substr($delrow, 0, 2);
      $del_switch = array('2-' => 'ž��ž��',
			  '3-' => 'ž��',
			  '4-' => '���񡦳���',
			  '5-' => '���Դ����õ�����',
			  '6-' => '�߸˴���');
      if (array_key_exists($delkey, $del_switch)) {
	$ds = $del_switch[$delkey];
	$ix = substr($delrow, 2);
	array_splice($this->data[$ds], $ix, 1); // remove 1 elt
      }
    }

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST))
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];

    if ($subpick) {
      if (substr($subpick, 0, 2) == '1-') {
	// ����
	$slot = substr($subpick, 2);
	$ss = array();
	foreach ($this->data['����'] as $row)
	  if ($row['����������'] == $slot)
	    $ss[] = mx_form_escape_key(array($row['����'], $row['����̾']));
	$subconfig = $_lib_u_nurse_ward_employee_pick_cfg;
	$subconfig['Ward'] = $this->data['����'][0]['����'];
	$subconfig['Select'] = $ss;
	$subconfig['Title'] = '����('.$this->employee_work[$slot].')������';
	$this->_Subpicker = new ward_employee_pick($this->prefix . 'wep-',
						   $subconfig);

	$chosen = $this->_Subpicker->chosen();
	if (is_array($chosen)) {
	  $this->_Subpicker = NULL;
	  $d = array();
	  foreach ($this->data['����'] as $row)
	    if ($row['����������'] != $slot)
	      $d[] = $row;
	  foreach ($chosen as $v) {
	    $a = mx_form_unescape_key($v);
	    $d[] = array('����' => $a[0],
			 '����̾' => $a[1],
			 '����������' => $slot);
	  }
	  $this->data['����'] = $d;
	}
      }
      elseif (substr($subpick, 0, 4) == '2-0-' ||
	      substr($subpick, 0, 4) == '3-0-' ||
	      substr($subpick, 0, 4) == '4-0-' ||
	      substr($subpick, 0, 4) == '5-0-') {
	// ž��ž�Сʴ��ԡ� or ž���ʴ��ԡ�or �õ�����ʴ��ԡ�
	// or ���񡦳��Сʴ��ԡ�
	$ix = substr($subpick, 4);
	$ty = substr($subpick, 0, 1);
	$idl = array('����', '����ID', '����̾', '����ǯ��', '�¼�', '�¼�̾');
	switch ($ty) {
	case '2': $page = 'ž��ž��'; break;
	case '3':
	  $idl = array('����', '����ID', '����̾', '����ǯ��',
		       'ž�����¼�', 'ž�����¼�̾');
	  $page = 'ž��'; break;
	case '4': $page = '���񡦳���'; break;
	case '5': $page = '���Դ����õ�����'; break;
	}
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_patient_cfg;
	$subconfig['Ward'] = $this->data['����'][0]['����'];
	$this->_Subpicker = new list_of_ward_patients($this->prefix . 'lwp-',
						      $subconfig);
	$this->_Subpicker->Title = "����̾������";
	$a = array();
	foreach ($idl as $iy => $col)
	  $a[] = $d[$col];
	$this->_Subpicker->Original = mx_form_escape_key($a);
	if ($this->_Subpicker->changed() &&
	    $this->_Subpicker->chosen()) {
	  $a = mx_form_unescape_key($this->_Subpicker->chosen());
	  $this->_Subpicker = NULL;
	  foreach ($idl as $iy => $col)
	    $d[$col] = $a[$iy];
	}
	unset($d);
      }
      elseif (substr($subpick, 0, 4) == '2-1-' ||
	      substr($subpick, 0, 4) == '3-1-' ||
	      substr($subpick, 0, 4) == '3-2-' ||
	      substr($subpick, 0, 4) == '4-1-' ||
	      substr($subpick, 0, 4) == '5-1-') {
	// ž��ž�С��¼���or
	// ž����ž�����¼���or ž����ž�����¼���or
	// �õ�������¼���or ���񡦳��С��¼���
	$ix = substr($subpick, 4);
	$ty = substr($subpick, 0, 4);
	$idl = array('�¼�', '�¼�̾');
	switch ($ty) {
	case '2-1-': $page = 'ž��ž��'; break;
  	case '3-1-':
	  $idl = array('ž�����¼�', 'ž�����¼�̾');
	  $page = 'ž��';
	  break;
  	case '3-2-':
	  $idl = array('ž�����¼�', 'ž�����¼�̾');
	  $page = 'ž��';
	  break;
	case '4-1-': $page = '���񡦳���'; break;
	case '5-1-': $page = '���Դ����õ�����'; break;
	}
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_room_cfg;
	$subconfig['Ward'] = $this->data['����'][0]['����'];
	$this->_Subpicker = new list_of_ward_rooms($this->prefix . 'lwr-',
						   $subconfig);
	$this->_Subpicker->Title = "�¼�������";
	$a = array();
	foreach ($idl as $iy => $col)
	  $a[] = $d[$col];
	$this->_Subpicker->Original = mx_form_escape_key($a);
	if ($this->_Subpicker->changed() &&
	    $this->_Subpicker->chosen()) {
	  $a = mx_form_unescape_key($this->_Subpicker->chosen());
	  $this->_Subpicker = NULL;
	  foreach ($idl as $iy => $col)
	    $d[$col] = $a[$iy];
	}
	unset($d);
      }
      elseif (substr($subpick, 0, 4) == '2-2-') {
	// ž��ž�С�ž�������
	$ix = substr($subpick, 4);
	$idl = array('ž������', 'ž������̾');
	$page = 'ž��ž��';
	$d =& $this->data[$page][$ix];
	$subconfig = $_lib_u_nurse_ward_cfg;
	$this->_Subpicker = new list_of_wards($this->prefix . 'lww-',
					      $subconfig);
	$this->_Subpicker->Title = "ž�����������";
	$a = array();
	foreach ($idl as $iy => $col)
	  $a[] = $d[$col];
	$this->_Subpicker->Original = mx_form_escape_key($a);
	if ($this->_Subpicker->changed() &&
	    $this->_Subpicker->chosen()) {
	  $a = mx_form_unescape_key($this->_Subpicker->chosen());
	  $this->_Subpicker = NULL;
	  foreach ($idl as $iy => $col)
	    $d[$col] = $a[$iy];
	}
	unset($d);
      }
    }

    // Yuck.
    foreach (array_merge(array("����",
			       "��������",
			       "��������", "��������", "�ڲ�", "ž��",
			       "��˴", "¾��ž��", "ž��", "ž��",
			       "ô��", "����", "����"),
			 _lib_u_nurse_ward_manage_log_A1C4()) as $asc) {
      $o = $v = $this->data['����'][0][$asc];
      if (! is_array($v) && ! is_null($v)) {
	$v = mb_convert_kana($v, 'as', 'euc');
	if ($v != $o) {
	  $this->dbglog("Kana $o => $v\n");
	  $this->data['����'][0][$asc] = $v;
	}
      }
    }

    for ($ix = 0; $ix < count($this->data['���񡦳���']); $ix++) {
      foreach (array("����л���", "��������") as $asc) {
	$o = $v = $this->data['���񡦳���'][$ix][$asc];
	$v = mb_convert_kana($v, 'as', 'euc');
	if ($v != $o) {
	  $this->dbglog("Kana $o => $v\n");
	  $this->data['���񡦳���'][$ix][$asc] = $v;
	}
      }
    }

    $this->annotate_row_data(&$this->data);
  }

  function draw_body() {

    $page = $this->page;
    $data = $this->data;
    $config = $this->so_config;

    // Draw flippage and propagate hidden
    print "<table class=\"flippage\"><tr>";
    $page_num = -1;
    foreach ($this->so_config['Pages'] as $page_name => $cfg) {
      $page_num++;
      $a = $this->data[$page_name];
      $num_items = count($a);
      mx_formi_hidden($this->prefix . $page_num . '-total', $num_items);
      if ($page_num == $page) {
	print "<td class=\"focused ltcorner\">&nbsp;</td>";
	print "<td class=\"focused\">&nbsp;";
	print $page_name;
	mx_formi_hidden($this->prefix . 'page', $page_num);
	print "&nbsp;</td><td class=\"focused rtcorner\">&nbsp;</td>";
      } else {
	print "<td class=\"unfocused ltcorner\">&nbsp;</td>";
	print "<td class=\"unfocused\">";
	if ($this->_Subpicker)
	  print $page_name;
	else
	  mx_formi_submit($this->prefix . 'page-to', $page_num, $page_name);

	for ($ix = 0; $ix < $num_items; $ix++) {
	  foreach ($cfg as $colname) {
	    $cnx = mx_form_encode_name($colname);
	    mx_formi_hidden(($this->prefix . $page_num . '-data-' .
			     $ix . '-' . $cnx), $a[$ix][$colname]);
	  }
	}

	print "</td><td class=\"unfocused rtcorner\">&nbsp;</td>";
      }
    }
    print "</tr></table>\n";

    // Draw shown page.
    $draw_page_method = 'draw_page_' . $page;
    $this->$draw_page_method();

    if ($this->_Subpicker) {
      print "<hr />\n";
      mx_formi_hidden($this->prefix . 'Subpick',
		      $_REQUEST[$this->prefix . 'Subpick']);
      $this->_Subpicker->draw();
    }
    else {
      print "<br />\n";
      mx_formi_submit($this->prefix . 'commit', '�Խ���λ');
      mx_formi_submit($this->prefix . 'rollback', '�Խ����');
    }

  }

  function dx_hidden($pfx, $p, $colname) {
    $cnx = mx_form_encode_name($colname);
    mx_formi_hidden($pfx . $cnx, $p[$colname]);
  }

  function dx_ro($evenodd, $pfx, $p, $colname, $label=NULL) {
    if (is_null($label)) $label = $colname;
    print "<tr class=\"$evenodd\"><th>$label</th><td>" . $p[$colname];
    $cnx = mx_form_encode_name($colname);
    mx_formi_hidden($pfx . $cnx, $p[$colname]);
    print "</td></tr>\n";
  }

  function dx_checkbox($evenodd, $pfx, $p, $colname, $label=NULL) {
    if (is_null($label)) $label = $colname;
    print "<tr class=\"$evenodd\"><th>$label</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      if ($value) print "x";
    }
    else
	mx_formi_checkbox($pfx . $cnx, $p[$colname]);
    print "</td></tr>\n";
  }

  function dx_text($evenodd, $pfx, $p, $colname, $option=NULL) {
    print "<tr class=\"$evenodd\"><th>$colname</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      print htmlspecialchars($p[$colname]);
    }
    else
      mx_formi_text($pfx . $cnx, $p[$colname], $option);
    print "</td></tr>\n";
  }

  function dx_textarea($evenodd, $pfx, $p, $colname, $option=NULL) {
    print "<tr class=\"$evenodd\"><th>$colname</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      print htmlspecialchars($p[$colname]);
    }
    else
      mx_formi_textarea($pfx . $cnx, $p[$colname], $option);
    print "</td></tr>\n";
  }

  function dx_enum($evenodd, $pfx, $p, $colname, $choice, $label=NULL) {
    if (is_null($label)) $label = $colname;
    print "<tr class=\"$evenodd\"><th>$label</th><td>";
    $cnx = mx_form_encode_name($colname);
    if ($this->_Subpicker) {
      mx_formi_hidden($pfx . $cnx, $p[$colname]);
      print htmlspecialchars($choice[$p[$colname]]);
    } else
      mx_formi_select($pfx . $cnx, $p[$colname], $choice);
    print "</td></tr>\n";
  }

  function draw_page_0() {
    // "����", "����", "ô��", "����",
    // "����", "A1".."C4", "����", "��������",
    // "��������", "��������", "�ڲ�", "ž��",
    // "��˴", "¾��ž��", "ž��", "ž��",
    // "��Ĺ", "��Ĺ", "����̾",
    // "�����¾���",
    // "��Ĺ̾", "��Ĺ̾"
    $pfx = $this->prefix . 0 . '-data-0-';
    $p = $this->data['����'][0];
    $oe = array(0 => 'e', 1 => 'o');
    $oex = 1;

    $ime_opt = array('ime' => 'disabled');

    print '<table class="listofstuff">';

    $this->dx_ro($oe[$oex = 1 - $oex], $pfx, $p, '����');
    $this->dx_ro($oe[$oex = 1 - $oex], $pfx, $p, '����̾');

    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '��������', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '��������', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '��������', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '�ڲ�', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'ž��', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '��˴', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '¾��ž��', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'ž��', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'ž��', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, 'ô��', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '����', $ime_opt);
    $this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, '����', $ime_opt);

    print "<tr><th colspan=\"2\">�Ǹ����̴��Կ�</th><tr>\n";
    foreach (array('A', 'B', 'C') as $c0)
      foreach (array('1', '2', '3', '4') as $c1)
	$this->dx_text($oe[$oex = 1 - $oex], $pfx, $p, "$c0$c1", $ime_opt);

    $this->dx_textarea($oe[$oex = 1 - $oex], $pfx, $p, '����');

    print "</table>\n";
    $this->dx_hidden($pfx, $p, '����');
    $this->dx_hidden($pfx, $p, '�����¾���');
    $this->dx_hidden($pfx, $p, '��Ĺ');
    $this->dx_hidden($pfx, $p, '��Ĺ');
    $this->dx_hidden($pfx, $p, '��Ĺ̾');
    $this->dx_hidden($pfx, $p, '��Ĺ̾');
  }

  function patient_label($p) {
    if (trim($p['����']) != '')
      return sprintf("%s %s (%s ��)",
		     $p['����ID'], $p['����̾'], $p['����ǯ��']);
    else
      return "(̤����)";
  }

  function draw_page_1() {
    // '����' => array("����", "����̾", "����������"),
    $cfg = $this->so_config['Pages']['����'];
    $pfx = $this->prefix . 1 . '-data-';
    $p = $this->data['����'];
    print '<table class="listofstuff">';
    foreach ($this->employee_work as $slot => $label) {
      $evenodd = ($slot % 2) ? "o" : "e";
      print "<tr class=\"$evenodd\"><th>";
      if ($this->_Subpicker)
	print $label;
      else
	mx_formi_submit($this->prefix . 'Subpick', '1-' . $slot,
			"<span class=\"link\">$label</span>");
      print "</th><td>";
      $it = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	$row = $p[$ix];
	if ($row['����������'] == $slot) {
	  if ($it++) print "<br />";
	  print $row['����̾'];
	  foreach ($cfg as $colname) {
	    $cnx = mx_form_encode_name($colname);
	    mx_formi_hidden(($pfx . $ix . '-' . $cnx), $row[$colname]);
	  }
	}
      }
      print "</td></tr>\n";
    }
    print "</table>\n";
  }

  function draw_page_2() {
    // 'ž��ž��' => array("����", "�¼�", "����", "ž������", "����",
    // "����̾", "����ID", "����ǯ��",
    // "ž������̾", "�¼�̾"),
    $cfg = $this->so_config['Pages']['ž��ž��'];
    $pfx = $this->prefix . 2 . '-data-';
    $p = $this->data['ž��ž��'];

    print '<table class="listofstuff">';
    $iy = 0;
    foreach (array('I' => '����', 'i' => 'ž��',
		   'o' => 'ž��', 'O' => '�ౡ') as $slot => $slotname) {
      print "<tr><th colspan=\"2\">$slotname</th></tr>\n";
      for ($ix = 0; $ix < count($p); $ix++) {
	if ($p[$ix]['����'] != $slot) continue;
	$evenodd = ($iy % 2) ? "o" : "e";

	print "<tr class=\"$evenodd\"><th>����</th><td>";
	foreach (array('����', '����ID', '����̾', '����ǯ��',
		       '�¼�', '�¼�̾', '����',
		       'ž������', 'ž������̾') as $col) {
	  $cnx = mx_form_encode_name($col);
	  mx_formi_hidden(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	}
	$label = $this->patient_label($p[$ix]);
	if ($this->_Subpicker)
	  print $label;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '2-0-' . $ix,
			  "<span class=\"link\">$label</span>");
	print "</td></tr>\n";

	$label = '�¼�';
	print "<tr class=\"$evenodd\"><th>$label</th><td>";
	$value = trim($p[$ix][$label . '̾']);
	if ($value == '')
	  $value = '(̤����)';
	if ($this->_Subpicker)
	  print $value;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '2-1-' . $ix,
			  "<span class=\"link\">$value</span>");
	print "</td></tr>\n";

	if ($slot == 'i' || $slot == 'o') {
	  $label = 'ž������';
	  $show = ($slot == 'i') ? 'ž������' : 'ž������';
	  print "<tr class=\"$evenodd\"><th>$show</th><td>";
	  $value = trim($p[$ix][$label . '̾']);
	  if ($value == '')
	    $value = '(̤����)';
	  if ($this->_Subpicker)
	    print $value;
	  else
	    mx_formi_submit($this->prefix . 'Subpick', '2-2-' . $ix,
			    "<span class=\"link\">$value</span>");
	  print "</td></tr>\n";
	}
	else {
	  $col = '����';
	  print "<tr class=\"$evenodd\"><th>$col</th><td>";
	  $cnx = mx_form_encode_name($col);
	  mx_formi_textarea(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	  print "</td></tr>\n";
	}
	if (! $this->_Subpicker) {
	  print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	  mx_formi_submit($this->prefix . 'DelRow', '2-' . $ix,
			  "<span class=\"link\">���ι��ܤ���</span>");
	  print "</td></tr>\n";
	}
	$iy++;
      }
      if (! $this->_Subpicker) {
	print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'AddRow', '2-' . $slot,
			"<span class=\"link\">���ܤ��ɲ�</span>");
	print "</td></tr>\n";
      }
    }
    print "</table>\n";
  }

  function draw_page_3() {
    // 'ž��' => array("����", "ž�����¼�", "ž�����¼�",
    // "����̾", "����ID", "����ǯ��",
    // "ž�����¼�̾", "ž�����¼�̾"),
    $cfg = $this->so_config['Pages']['ž��'];
    $pfx = $this->prefix . 3 . '-data-';
    $p = $this->data['ž��'];

    print '<table class="listofstuff">';
    for ($ix = 0; $ix < count($p); $ix++) {
      $evenodd = ($ix % 2) ? "o" : "e";

      print "<tr class=\"$evenodd\"><th>����</th><td>";
      foreach (array('����', '����ID', '����̾', '����ǯ��',
		     'ž�����¼�', 'ž�����¼�̾',
		     'ž�����¼�', 'ž�����¼�̾') as $col) {
	$cnx = mx_form_encode_name($col);
	mx_formi_hidden(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
      }
      $label = $this->patient_label($p[$ix]);
      if ($this->_Subpicker)
	print $label;
      else
	mx_formi_submit($this->prefix . 'Subpick', '3-0-' . $ix,
			"<span class=\"link\">$label</span>");
      print "</td></tr>\n";

      foreach (array(1 => 'ž����', 2 => 'ž����') as $slot => $col) {
	$label = $col . '�¼�';
	print "<tr class=\"$evenodd\"><th>$label</th><td>";
	$value = trim($p[$ix][$label . '̾']);
	if ($value == '')
	  $value = '(̤����)';
	if ($this->_Subpicker)
	  print $value;
	else
	  mx_formi_submit($this->prefix . 'Subpick', '3-' . $slot . '-' . $ix,
			  "<span class=\"link\">$value</span>");
	print "</td></tr>\n";
      }
      if (! $this->_Subpicker) {
	print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'DelRow', '3-' . $ix,
			"<span class=\"link\">���ι��ܤ���</span>");
	print "</td></tr>\n";
      }
    }
    if (! $this->_Subpicker) {
      print "<tr><td colspan=\"2\">";
      mx_formi_submit($this->prefix . 'AddRow', 3,
		      "<span class=\"link\">���ܤ��ɲ�</span>");
      print "</td></tr>\n";
    }
    print "</table>\n";
  }

  function draw_page_4() {
    // '���񡦳���' => array("����", "�¼�", "����������",
    // "����л���", "��������",
    // "����","����̾", "����ID", "����ǯ��", "�¼�̾"),
    $cfg = $this->so_config['Pages']['���񡦳���'];
    $pfx = $this->prefix . 4 . '-data-';
    $p = $this->data['���񡦳���'];

    $this->__draw_page_sub_45($cfg, $pfx, $p, 4,
			      array('����л���' => 'text-i',
				    '��������' => 'text-i',
				    '����' => 'textarea'),
			      array('����������',
				    array(0 => '����',
					  1 => '����')));
  }

  function __draw_page_sub_45($cfg, $pfx, $p, $pg,
			      $xtra, $subloop=NULL) {

    $ime_opt = array('ime' => 'disabled');

    print '<table class="listofstuff">';

    if (is_null($subloop)) {
      $subfield = NULL;
      $picklist = array(0 => 1);
    } else {
      $subfield = $subloop[0];
      $picklist = $subloop[1];
    }

    foreach ($picklist as $pickkey => $picklabel) {
      if (! is_null($subfield))
	print ("<tr><th colspan=\"2\">" . htmlspecialchars($picklabel) .
	       "</th></tr>\n");
      $ccnt = 0; $lix = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	if (! is_null($subfield) && ($p[$ix][$subfield] != $pickkey))
	  continue;
	$lix = $ix;
	$evenodd = ($ccnt % 2) ? "o" : "e";
	$ccnt++;
	print "<tr class=\"$evenodd\"><th>����</th><td>";
	foreach (array('����', '����ID', '����̾', '����ǯ��',
		       '�¼�', '�¼�̾') as $col) {
	  $cnx = mx_form_encode_name($col);
	  mx_formi_hidden(($pfx . $ix . '-' .$cnx), $p[$ix][$col]);
	}
	if (! is_null($subfield)) {
	  $col = $subfield;
	  $cnx = mx_form_encode_name($col);
	  mx_formi_hidden(($pfx . $ix . '-' . $cnx), $p[$ix][$col]);
	}
	$label = $this->patient_label($p[$ix]);
	if ($this->_Subpicker)
	  print $label;
	else
	  mx_formi_submit($this->prefix . 'Subpick', $pg . '-0-' . $ix,
			  "<span class=\"link\">$label</span>");
	print "</td></tr>\n";

	$label = $p[$ix]['�¼�̾'];
	if (trim($label) == '') $label = '(̤����)';
	print "<tr class=\"$evenodd\"><th>�¼�</th><td>";
	if ($this->_Subpicker)
	  print $label;
	else
	  mx_formi_submit($this->prefix . 'Subpick', $pg . '-1-' . $ix,
			  "<span class=\"link\">$label</span>");
	print "</td></tr>\n";

	foreach ($xtra as $col => $kind) {
	  print "<tr class=\"$evenodd\"><th>$col</th><td>";
	  $cnx = mx_form_encode_name($col);
	  switch ($kind) {
	  case 'text':
	    mx_formi_text(($pfx . $ix . '-' . $cnx), $p[$ix][$col]);
	    break;
	  case 'text-i':
	    mx_formi_text(($pfx . $ix . '-' . $cnx), $p[$ix][$col], $ime_opt);
	    break;
	  case 'textarea':
	    mx_formi_textarea(($pfx . $ix . '-' . $cnx), $p[$ix][$col]);
	    break;
	  }
	  print "</td></tr>\n";
	}

	if (! $this->_Subpicker) {
	  print "<tr class=\"$evenodd\"><td colspan=\"2\">";
	  mx_formi_submit($this->prefix . 'DelRow', $pg . '-' . $ix,
			  "<span class=\"link\">���ι��ܤ���</span>");
	  print "</td></tr>\n";
	}

      }

      if (! is_null($subfield) &&
	  ! $this->_Subpicker && (! $ccnt || $p[$lix]['����'] != '')) {
	print "<tr><td colspan=\"2\">";
	mx_formi_submit($this->prefix . 'AddRow', "$pg-$pickkey",
			"<span class=\"link\">���ܤ��ɲ�</span>");
	print "</td></tr>\n";
      }
    }

    print "</table>\n";

    if (is_null($subloop)) {
      if (! $this->_Subpicker &&
	  (! count($p) || $p[count($p)-1]['����'] != ''))
	mx_formi_submit($this->prefix . 'AddRow', $pg,
			"<span class=\"link\">���ܤ��ɲ�</span>");
    }
  }

  function draw_page_5() {
    // '���Դ����õ�����' => array("����", "�¼�",
    // "�������õ�����", "������õ�����",
    // "����̾", "����ID", "����ǯ��",
    // "�¼�̾"),
    $cfg = $this->so_config['Pages']['���Դ����õ�����'];
    $pfx = $this->prefix . 5 . '-data-';
    $p = $this->data['���Դ����õ�����'];

    $this->__draw_page_sub_45($cfg, $pfx, $p, 5,
			      array('�������õ�����' => 'textarea',
				    '������õ�����' => 'textarea'));
  }

  function draw_page_6() {
    // '�߸˴���' => array("̾��", "�߸˿�", "����������"));
    $cfg = $this->so_config['Pages']['�߸˴���'];
    $pfx = $this->prefix . 6 . '-data-';
    $p = $this->data['�߸˴���'];
    print '<table class="listofstuff">';

    foreach (array(0 => '����', 1 => 'ʪ��') as $slot => $slotname) {
      print "<tr><th colspan=\"3\">$slotname</th></tr>\n";
      print "<tr><th>̾��</th><th>�߸˿�</th><th>(�Ժ��)</th></tr>\n";
      $iy = 0;
      for ($ix = 0; $ix < count($p); $ix++) {
	if ($p[$ix]['����������'] != $slot)
	  continue;
	$evenodd = ($iy % 2) ? "o" : "e";
	print "<tr class=\"$evenodd\">";
	foreach (array('̾��','�߸˿�') as $col) {
	  print "<td>";
	  mx_formi_text($pfx . $ix . '-' . mx_form_encode_name($col),
			$p[$ix][$col]);
	  print "</td>";
	}
	print "<td>";
	$col = '����������';
	mx_formi_hidden($pfx . $ix . '-' . mx_form_encode_name($col),
			$p[$ix][$col]);
	mx_formi_submit($this->prefix . 'DelRow', '6-' . $ix,
			'<span class="link">x</span>');
	print "</td></tr>\n";
	$iy++;
      }
      $evenodd = ($iy % 2) ? "o" : "e";
      print "<tr class=\"$evenodd\"><td colspan=\"3\">";
      mx_formi_submit($this->prefix . 'AddRow', '6-' . $slot,
		      "<span class=\"link\">(���ɲ�)</span><br />");
      print "</td></tr>\n";
    }
    print "</table>\n";
  }


  function data_compare($curr, $data) {
    return _lib_u_nurse_ward_manage_log_compare_data
      ($data, $curr, $this->so_config);
  }

  function _update_stmt($d, $u, $id) {
    // simple-object-edit expects to find $this->data formatted
    // a bit differently.  We override.
    $stmt = ('UPDATE ' .
	     mx_db_sql_quote_name($this->so_config['TABLE']) .
	     ' SET "CreatedBy" = ' .
	     mx_db_sql_quote($u));
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= (",\n " . mx_db_sql_quote_name($col) . ' = ' .
		mx_db_sql_quote($d['����'][0][$col]));
    $stmt .= (' WHERE "ObjectID" = ' . mx_db_sql_quote($id) .
	      ' AND "Superseded" IS NULL ');
    return $stmt;
  }

  function _insert_stmt(&$d, $ObjectID, $StashID) {
    global $mx_authenticate_current_user;

    // $d passed is of shape $this->data, but we do not store things
    // as the simple-object does.  We override.

    if (is_null($StashID)) {
      $o = mx_db_sql_quote($ObjectID);
      $o = "$o, $o, NULL, " . mx_db_sql_quote($mx_authenticate_current_user);
    } else {
      // We are stashing the old information away.
      $o = (mx_db_sql_quote($ObjectID) . ', ' .
	    mx_db_sql_quote($StashID) . ', now(), ' .
	    mx_db_sql_quote($d["CreatedBy"]));
    }

    // foreach (array('��Ĺ', '��Ĺ') as $col)
    // if ($d['����'][0][$col] == '')
    // $d['����'][0][$col] = NULL;

    $stmt = (($this->insert_stmt_head) . 'VALUES (' . "$o");
    foreach ($this->so_config['ICOLS'] as $col)
      $stmt .= ",\n " . mx_db_sql_quote($d['����'][0][$col]);
    $stmt .= ')';
    return $stmt;
  }

  function _validate() {
    $bad = 0;
    $null_bad_col = array('����' => array(),
			  '����' => array('����'),
			  'ž��ž��' => array('����', '�¼�'),
			  'ž��' => array('����', 'ž�����¼�', 'ž�����¼�'),
			  '���񡦳���' => array('����', '�¼�'),
			  '���Դ����õ�����'=> array('����', '�¼�'),
			  '�߸˴���' => array('̾��', '�߸˿�'));
    $pos_num_col = array('����' => array_merge
			 (array
			  ("��������", "��������", "��������",
			   "�ڲ�", "ž��", "��˴", "¾��ž��",
			   "ž��", "ž��", "ô��", "����", "����"),
			  _lib_u_nurse_ward_manage_log_A1C4()),
			 );

    $time_of_day_or_null_col = array
      ('���񡦳���' => array('����л���', '��������'));

    foreach ($null_bad_col as $page => $cfg)
      foreach ($this->data[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if (($st = mx_db_validate_length($row[$col], 1, NULL)) != '') {
	    $this->err("($page) $col: " . $st . "\n");
	    $bad++;
	  }

    foreach ($pos_num_col as $page => $cfg)
      foreach ($this->data[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if (($st = mx_db_validate_nnint($row[$col])) != '') {
	    $this->err("($page) $col: " . $st . "\n");
	    $bad++;
	  }

    foreach ($time_of_day_or_null_col as $page => $cfg)
      foreach ($this->data[$page] as $ix => $row)
	foreach ($cfg as $col)
	  if (! is_null($row[$col]) &&
	      (($st = mx_db_validate_time($row[$col])) != '')) {
	    $this->err("($page) $col: " . $st . "\n");
	    $bad++;
	  }

    foreach ($this->data['���񡦳���'] as $ix => $row)
      if (($row['����������'] == 1) &&
	  (is_null($row['����л���']) || is_null($row['��������']))) {
	$this->err("���Фγ���л��֤䵢������϶��ǤϤ����ޤ���\n");
	$bad++;
      }

    foreach ($this->data['ž��ž��'] as $ix => $row)
      if (($row['����'] == 'i' || $row['����'] == 'o') &&
	  ($st = mx_db_validate_length($row['ž������'], 1, NULL)) != '') {
	$this->err("(ž��ž��) ž����ž�ФǤ�ž������϶��ǤϤ����ޤ���\n");
	$bad++;
      }

    foreach ($this->data['ž��'] as $ix => $row)
      if ($row['ž�����¼�'] == $row['ž�����¼�']) {
	$this->err("(ž��) ž�����¼���ž�����¼���Ʊ���ǤϤ����ޤ���\n");
	$bad++;
      }

    $rm = $pt = $conflict = $ptname = $rmname = NULL;
    $this->summarize_patient_movement($rm, $pt, $conflict,
				      $ptname, $rmname);

    foreach ($conflict as $ix => $row) {
      $this->err("ž����ž��ž��: ���� $row ��ʣ������ꤵ��Ƥ��ޤ�\n");
      $bad++;
    }


    $tot_num = ($this->data['����'][0]['ô��'] +
		$this->data['����'][0]['����'] +
		$this->data['����'][0]['����']);
    $sub_num = 0;
    foreach (_lib_u_nurse_ward_manage_log_A1C4() as $col)
      $sub_num += $this->data['����'][0][$col];

    if ($tot_num != $sub_num) {
      $this->err("(����) ���߿� $tot_num ��".
		 "�Ǹ����̴��Կ������ $sub_num �����פ��ޤ���\n");
      $bad++;
    }

    if (! $bad)
      return 'ok';
  }

  function _update_subtables(&$db, $id, $stash_id) {
    $subtable = array
      (array("��������������", "����",
	     array("����", "����������")),
       array("����������ž��ž��", "ž��ž��",
	     array("����", "�¼�", "����", "ž������", "����")),
       array("����������ž��", "ž��",
	     array("����", "ž�����¼�", "ž�����¼�")),
       array("�������������񡦳���", "���񡦳���",
	     array("����", "�¼�", "����������",
		   "����л���", "��������", "����")),
       array("�������������Դ����õ�����", "���Դ����õ�����",
	     array("����", "�¼�", "�������õ�����", "������õ�����")),
       array("�����������߸˴���", "�߸˴���",
	     array("̾��", "�߸˿�", "����������")),
       );

    if (! is_null($stash_id)) {
      // Current rows in subtables should point at $stash_id
      foreach ($subtable as $d) {
	$st = $d[0];
	$stmt = ('UPDATE "' . $st . '" SET "�����������" = ' .
		 mx_db_sql_quote($stash_id) .
		 ' WHERE "�����������" = ' .
		 mx_db_sql_quote($id));
	$this->dbglog("Stash-Subs: $stmt\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);
      }
    }

    foreach ($subtable as $d) {
      $st = $d[0];
      $slot = $d[1];
      $cols = $d[2];
      foreach ($this->data[$slot] as $r) {
	$cc = array();
	foreach ($cols as $col)
	  $cc[] = '"' . $col . '"';
	$cc = join(', ', $cc);
	if (trim($r[$cols[0]]) != '') {
	  $stmt = ('INSERT INTO "' . $st . '" ("�����������", ' .
		   $cc . ') values (' .
		   mx_db_sql_quote($id));
	  foreach ($cols as $col) {
	    $stmt = $stmt . ', ' . mx_db_sql_quote($r[$col]);
	  }
	  $stmt = $stmt . ")\n";
	  $this->dbglog("Insert-Subs: $stmt\n");
	  if (! pg_query($db, $stmt))
	    return pg_last_error($db);
	}
      }
    }

    $st = $this->move_patients($db);
    return $st;
  }

  function summarize_patient_movement(&$rm, &$pt, &$conflict,
				      &$ptname, &$rmname) {

    $rm = array(); $pt = array(); $conflict = array();
    $ptname = array(); $rmname = array();

    // Find affected patients and rooms.
    // For each patient, find where it belongs now.
    // For each patient, find where it wants to belong to with this log.
    // For each room, find which patient should move with this log.

    $pt_seen = array();
    foreach ($this->data['ž��ž��'] as $ent) {
      $ptname[$ent['����']] = $ent['����̾'];
      $rmname[$ent['�¼�']] = $ent['�¼�̾'];
      if (array_key_exists($ent['����'], $pt_seen))
	$conflict[$ent['����']] = $ent['����̾'];

      $pt_seen[$ent['����']] = 1;
      switch ($ent['����']) {
      case 'i':
      case 'I':
	$pt[$ent['����']] = $ent['�¼�'];
	$rm[$ent['�¼�']][] = array('+', $ent['����']);
	break;
      case 'o':
      case 'O':
	$pt[$ent['����']] = NULL;
	$rm[$ent['�¼�']][] = array('-', $ent['����']);
	break;
      }
    }

    foreach ($this->data['ž��'] as $ent) {
      $ptname[$ent['����']] = $ent['����̾'];
      $rmname[$ent['ž�����¼�']] = $ent['ž�����¼�̾'];
      $rmname[$ent['ž�����¼�']] = $ent['ž�����¼�̾'];
      if (array_key_exists($ent['����'], $pt_seen))
	$conflict[$ent['����']] = $ent['����̾'];

      $pt[$ent['����']] = $ent['ž�����¼�'];
      $rm[$ent['ž�����¼�']][] = array('-', $ent['����']);
      $rm[$ent['ž�����¼�']][] = array('+', $ent['����']);
    }

    // These records say where they are without mentioning explicit movements.
    foreach (array('���񡦳���', '���Դ����õ�����') as $slot) {
      foreach ($this->data[$slot] as $ent) {
	$ptname[$ent['����']] = $ent['����̾'];
	$rmname[$ent['�¼�']] = $ent['�¼�̾'];
	if (array_key_exists($ent['����'], $pt_seen))
	  continue;
	$pt[$ent['����']] = $ent['�¼�'];
	$rm[$ent['�¼�']][] = array('+', $ent['����']);
      }
    }

  }

  function move_patients(&$db) {
    global $mx_authenticate_current_user;

    $rm = $pt = $conflict = $patient_name = $room_name = NULL;
    $this->summarize_patient_movement($rm, $pt, $conflict,
				      $patient_name, $room_name);

    if (count($rm) == 0) {
      // pt should be empty otherwise something is wrong.
      if (count($pt)) die("rm empty but pt not?");
      return '';
    }

    // Find where they are right now.
    $stmt = ('SELECT RP."�¼�", R."�¼�̾", RP."ObjectID", RP."����",
              RP."CreatedBy", RPD."����"
	      FROM "�¼�����ɽ" AS RP
              JOIN "�¼�����ɽ" AS R
              ON R."ObjectID" = RP."�¼�" AND R."Superseded" IS NULL
	      LEFT JOIN "�¼����ԥǡ���" AS RPD
	      ON RP."ObjectID" = RPD."�¼�����ɽ" AND RP."Superseded" IS NULL
              WHERE RP."�¼�" IN (' . join(',', array_keys($rm)) . ') OR
                    RPD."����" IN (' . join(',', array_keys($pt)) . ')');
    $this->dbglog("Get RPD: $stmt;\n");
    $_rpd = pg_fetch_all(pg_query($db, $stmt));
    $this->dbglog("RPD raw: " . mx_var_dump($_rpd));

    $rpd = array();
    $rrpd = array();
    if (is_array($_rpd)) {
      foreach ($_rpd as $r) {
	$room = $r['�¼�'];
	$patient = $r['����'];
	$date = $r['����'];
	if (! array_key_exists($room, $rpd)) {
	  $rpd[$room] = array('ObjectID' => $r['ObjectID'],
			      'CreatedBy' => $r['CreatedBy'],
			      '�¼�̾' => $r['�¼�̾'],
			      '����' => $date,
			      '����' => array());
	  $room_name[$room] = $r['�¼�̾'];
	}
	if (! is_null($patient)) {
	  $rpd[$room]['����'][$patient] = $date;
	  $rrpd[$patient][] = $room;
	}
      }
    }
    $this->dbglog("RPD: " . mx_var_dump($rpd));
    $this->dbglog("RRPD: " . mx_var_dump($rrpd));

    foreach ($rrpd as $p => $rm_list) {
      if (count($rm_list) != 1)
	// There is something very wrong with this data.  The
	// patient belongs to more than one room.
	$this->log("Bad RPD entry: " . $patient_name[$p] . " ($p) in rooms " .
		   join(" ", $rm_list) . "\n");
    }

    $this->dbglog("RM: " . mx_var_dump($rm));

    $dt = $this->data['����'][0]['����'];

    // A room-patient record newer than this log entry should not be
    // touched, and the patients described there should not appear
    // anywhere else.
    $room_forbidden = array();
    $patient_forbidden = array();
    foreach ($rpd as $room => $data) {
      if ($dt < $data['����']) {
	$room_forbidden[$room] = 1;
	foreach ($data['����'] as $patient => $junk)
	  $patient_forbidden[$patient] = array($room, $data['����']);
      }
    }

    // Compute who should be in what room and record it in $nrpd.
    // Also remember patients with explicit move instruction.
    $nrpd = array();
    $pt_explicit = array();
    foreach ($rm as $room => $insn_list) {
      if (array_key_exists($room, $room_forbidden))
	continue;
      $nrpd[$room] = array();
      if (array_key_exists($room, $rpd))
	$nrpd[$room] = $rpd[$room]['����'];
      foreach ($patient_forbidden as $patient => $junk)
	unset($nrpd[$room][$patient]);
      foreach ($insn_list as $insn) {
	$patient = $insn[1];
	if (array_key_exists($patient, $patient_forbidden))
	  continue;
	if ($insn[0] == '-')
	  unset($nrpd[$room][$patient]);
	elseif (! array_key_exists($patient, $nrpd[$room]))
	  $nrpd[$room][$patient] = $dt;
	$pt_explicit[$patient] = $dt;
      }
    }

    // $nrpd[$r] is the list of patients explicitly placed.
    // people without explicit instruction can be placed now.
    foreach ($pt as $patient => $room) {
      if (is_null($room) ||
	  array_key_exists($patient, $patient_forbidden) ||
	  array_key_exists($room, $room_forbidden) ||
	  array_key_exists($patient, $pt_explicit))
	continue;
      if (! array_key_exists($patient, $nrpd[$room]))
	$nrpd[$room][$patient] = $dt;
    }
    $this->dbglog("NRPD-0: " . mx_var_dump($nrpd));

    // Now $nrpd[] has list of desired patient movements.
    // Sanitize it.

    // Phase I: against NRPD itself.
    $pt_after = array();
    foreach ($nrpd as $room => $pt_list) {
      foreach ($pt_list as $patient => $junk) {
	if (array_key_exists($patient, $pt_after)) {
	  // This should not happen, because summarize should have
	  // detected conflicts in explicit movements already and
	  // we have been careful constructing nrpd so far...
	  $this->log("Bad NRPD entry: " . $patient . " in both " .
		     $pt_after[$patient] . ' and ' . $room . "\n");
	  unset($nrpd[$pt_after[$patient]][$patient]);
	}
	$pt_after[$patient] = $room;
      }
    }
    $this->dbglog("NRPD-1: " . mx_var_dump($nrpd));

    // Phase II: against RPD.
    foreach ($rpd as $room => $data) {
      if (array_key_exists($room, $nrpd))
	continue;
      foreach ($data['����'] as $patient => $junk) {
	if (array_key_exists($patient, $pt_after)) {
	  // This should not happen, either.
	  $this->log("Bad NRPD entry tells to move $patient to " .
		     $pt_after[$patient] .
		     ", but newer RPD entry has it in $room\n");
	  unset($nrpd[$pt_after[$patient]][$patient]);
	}
      }
    }
    $this->dbglog("NRPD-2: " . mx_var_dump($nrpd));

    // Phase III: final sanity check.  Each patient should appear at
    // most once.
    $pt_after = array();
    foreach ($rpd as $room => $data) {
      if (array_key_exists($room, $nrpd))
	foreach ($nrpd[$room] as $patient => $junk)
	  $pt_after[$patient][] = $room;
      else
	foreach ($data['����'] as $patient => $junk)
	  $pt_after[$patient][] = $room;
    }
    foreach ($nrpd as $room => $data) {
      if (array_key_exists($room, $rpd))
	; // we have done this above.
      else
	foreach ($data as $patient => $junk)
	  $pt_after[$patient][] = $room;
    }
    $this->dbglog("PT-AFTER: " . mx_var_dump($pt_after));
    foreach ($pt_after as $patient => $rm_list) {
      if (count($rm_list) != 1)
	// There is something very wrong with this data.  The
	// patient belongs to more than one room.
	$this->log("Bad NRPD sanitization: " . $patient . " in rooms " .
		   join(" ", $rm_list) . "\n");
    }

    // Now move patients according to nrpd.
    foreach ($nrpd as $r => $pt_list) {
      if (array_key_exists($r, $rpd)) {
	$opt = array();
	foreach ($rpd[$r]['����'] as $p => $junk)
	  $opt[] = $p;
	$npt = array();
	foreach ($pt_list as $p => $junk)
	  $npt[] = $p;

	if ($rpd[$r]['����'] == $dt &&
	    count(array_diff($npt, $opt)) == 0 &&
	    count(array_diff($opt, $npt)) == 0) {
	  $this->dbglog("Patient list for $r remains the same (" .
			join(", ", $npt) . ")\n");
	  continue;
	}

	$oid = $rpd[$r]['ObjectID'];
	$this->dbglog("Setting patient list for $r to (" .
		      join(", ", $npt) . "), was (" .
		      join(", ", $opt) . ")\n");

	$stmt = ('SELECT nextval(\'"�¼�����ɽ_ID_seq"\') as "v"');
	$this->dbglog("SEQ -- $stmt;\n");
	$stash_id = mx_db_fetch_single($db, $stmt);
	$stash_id = $stash_id['v'];
	$this->dbglog("SEQ is $stash_id\n");

	// Stash
	$stmt = ('INSERT INTO "�¼�����ɽ" ("ID", "ObjectID", "CreatedBy",
                 "Superseded", "�¼�", "����") VALUES (' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($stash_id) . ', ' .
		 mx_db_sql_quote($rpd[$r]['CreatedBy']) . ', now(), ' .
		 mx_db_sql_quote($r) . ', ' .
		 mx_db_sql_quote($rpd[$r]['����']) . ')');
	$this->dbglog("Stash -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);

	$stmt = ('UPDATE "�¼����ԥǡ���" SET "�¼�����ɽ" = ' .
		 mx_db_sql_quote($stash_id) . ' WHERE "�¼�����ɽ" = ' .
		 mx_db_sql_quote($oid));
	$this->dbglog("Stash Sub -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);

	// UPDATE in place
	$stmt = ('UPDATE "�¼�����ɽ" SET "CreatedBy" = ' .
		 mx_db_sql_quote($mx_authenticate_current_user) . ', ' .
		 '"����" = ' .
		 mx_db_sql_quote($dt) . '
                 WHERE "ObjectID" = ' . $oid);
      }
      else {
	$npt = array();
	foreach ($pt_list as $p => $junk)
	  $npt[] = $p;
	$this->dbglog("Setting patient list for $r to (" .
		      join(", ", $npt) . "), a new RP entry\n");

	$stmt = ('SELECT nextval(\'"�¼�����ɽ_ID_seq"\') as "v"');
	$this->dbglog("SEQ -- $stmt;\n");
	$stash_id = mx_db_fetch_single($db, $stmt);
	$oid = $stash_id['v'];
	$this->dbglog("SEQ is $oid\n");

	// INSERT the new one.
	$stmt = ('INSERT INTO "�¼�����ɽ" ("ID", "ObjectID", "CreatedBy",
               "�¼�", "����") VALUES (' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($mx_authenticate_current_user) . ', ' .
		 mx_db_sql_quote($r) . ', ' .
		 mx_db_sql_quote($dt) . ')');
      }
      $this->dbglog("Latest -- $stmt;\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);

      foreach ($pt_list as $p => $junk) {
	$stmt = ('INSERT INTO "�¼����ԥǡ���" ("�¼�����ɽ", "����")
                 VALUES (' . mx_db_sql_quote($oid) . ', ' .
		 mx_db_sql_quote($p) . ')');
	$this->dbglog("Latest Sub -- $stmt;\n");
	if (! pg_query($db, $stmt))
	  return pg_last_error($db);
      }

    }

    $msg = '';

    // Filter patient-forbidden
    $pf = array();
    foreach ($patient_forbidden as $patient => $data) {
      if (array_key_exists($patient, $pt))
	$pf[$patient] = $data;
    }
    $this->dbglog("Patient-Forbidden: " . mx_var_dump($patient_forbidden));
    $this->dbglog("Patient-Forbidden: " . mx_var_dump($pf));

    if (count($pf)) {
      $msg .= ("<p>�ʲ��δ��Ԥˤϡ������Խ���� $dt �����������������" .
	       "��������Ͽ�ˤ�괵�Ԥν�ߤ����Ǥ˵�Ͽ����Ƥ��ޤ��Τǡ�" .
	       "�ܵ�Ͽ�ˤ�äƴ��Ԥΰ�ư�ϹԤʤ��ޤ���</p>\n<ul>");
      foreach ($pf as $patient => $data) {
	$room = $rpd[$data[0]]['�¼�̾'];
	$date = $data[1];
	$msg .= ("<li>" . htmlspecialchars($patient_name[$patient]) .
		 " ($date ���¼� $room)</li>\n");
      }
      $msg .= "</ul>\n";
    }

    // Compare new location with the old
    $patient_move_log = array();
    foreach ($pt_after as $patient => $rm_list)
      $patient_move_log[$patient] = array(NULL, $rm_list[0]);
    foreach ($rrpd as $patient => $rm_list) {
      if (! array_key_exists($patient, $patient_move_log))
	$patient_move_log[$patient] = array(NULL, NULL);
      $patient_move_log[$patient][0] = $rm_list[0];
    }
    // Filter it
    $pm = array();
    foreach ($patient_move_log as $patient => $data) {
      if (! is_null($data[0]) && ! is_null($data[1]) && $data[0] == $data[1])
	;
      elseif (is_null($data[0]) && is_null($data[1]))
	;
      else
	$pm[$patient] = $data;
    }
    $this->dbglog("Patient-Move: " . mx_var_dump($patient_move_log));
    $this->dbglog("Patient-Move: " . mx_var_dump($pm));

    if (count($pm)) {
      $msg .= ("<p>�ʲ��δ��ԤˤĤ��Ƥϡ������Խ���� $dt ���������������" .
	       "�����Ԥν�ߤ˴ؤ���ǿ��ǡ����Ǥ��Τǡ��ܵ�Ͽ�ˤ�äƴ���" .
	       "����¼��˵�Ͽ���ޤ�����</p>\n<ul>");
      foreach ($pm as $patient => $data) {
	$fromto = '';
	if (! is_null($data[0]))
	  $fromto = $room_name[$data[0]] . '����';
	if (! is_null($data[1]))
	  $fromto = $fromto . $room_name[$data[1]] . '��';
	$fromto = $fromto . '��ư';
	$msg .= ("<li>" . htmlspecialchars($patient_name[$patient]) .
		 " " . htmlspecialchars($fromto) . "</li>\n");
      }
      $msg .= "</ul>\n<br />";
    }
    $this->commit_message = $msg;
    return '';
  }

  function _broken_origin_check() {
     if ($this->id == '') {
	     $db = mx_db_connect();
	     $d = _lib_u_nurse_ward_manage_log_get_by_dt_ward
		     ($this, $this->so_config, $db,
		      $this->data['����'][0]['����'],
		      $this->data['����'][0]['����']);
	     $id = $d['ObjectID'];
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
