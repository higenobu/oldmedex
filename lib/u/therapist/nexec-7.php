<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/norder-2.php';

$_lib_u_therapist_execution_code_enum = array
('180022110' => '������ˡ�ʡ˸���',
 '180022210' => '������ˡ�ʡ˽���',
 '180023610' => '�����ˡ�ʡ˸���',
 '180023710' => '�����ˡ�ʡ˽���',
 '180024810' => '����İ����ˡ�ʡ˸���',
 '180024910' => '����İ����ˡ�ʡ˽���');

function __lib_u_therapist_nexec_list_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '��ϼ»ܵ�Ͽ',
      'ALLOW_SORT' => array
      ('ObjectID' => array('�»ܵ�ϿID' => 'X."ObjectID"'),
       '����' => array('����' => 'X."����"'),
       '�»���ˡ��' => array('�»���ˡ��' => '(T."��" || T."̾")')),
      'DEFAULT_SORT' => '����',
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => '�»ܵ�ϿID'),
		       '����', '�»���ˡ��'),
      'ENABLE_QBE' =>
      array(array('Column' => '����', 'Compare' => 'X."����"',
		  'Draw' => 'text'),
	    array('Column' => '�»���ˡ��',
		  'Compare' => '(T."��" || T."̾")',
		  'Draw' => 'text')),
      'UNIQ_ID' => 'X."ObjectID"',
      ));

  $stmt_head = '
SELECT X."ObjectID",
       X."����",
       (T."��" || T."̾") as "�»���ˡ��"
FROM "��ϼ»ܵ�Ͽ" as X
JOIN "��Ͻ����" as RX ON X."��Ͻ����" = RX."ID"
LEFT JOIN "������Ģ" as T ON T."ObjectID" = X."�»���ˡ��"
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';
}

class list_of_rehab_nexecs extends list_of_simple_objects {

  var $default_row_per_page = 4;
  var $debug = 1;

  function list_of_rehab_nexecs($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    $this->loo = new list_of_rehab_norders($prefix . 'loo-', $cfg);
    $this->sod = new rehab_norder_display($prefix . 'sod-', $cfg);

    if ($this->loo->changed() && $this->loo->chosen()) {
	    $this->sod->reset($this->loo->chosen());
	    $this->sod_changed = 1;
    }
    if (array_key_exists($prefix. 'OHistory', $_REQUEST))
	    $this->sod->history($_REQUEST[$prefix . 'OHistory']); 

    __lib_u_therapist_nexec_list_cfg(&$cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);

    if (!$this->sod->chosen())
	    return; 
    $this->Rx_ObjectID = $this->sod->chosen();
  }

  function lost_selection() {
	  return $this->sod_changed;
  }

  function reset($id) {
	  if (is_null($id)) {
		  $this->loo->reset($id);
		  $this->sod->reset($id);
		  $this->Rx_ObjectID = $this->sod->chosen();
	  }
	  list_of_simple_objects::reset($id);
  }

  function allow_new() {
	  if (!$this->sod->chosen())
		  return 0;
	  return 1;
  }

  function draw() {
	  $this->loo->draw();
	  if (!$this->Rx_ObjectID)
		  return;

	  print "<br />\n";
	  mx_titlespan('[��������]');
	  $this->sod->draw();
	  $sod_history = $this->sod->history();
	  $oh = $this->prefix . 'OHistory';
	  if (($sod_history & 3) == 3)
		  mx_formi_submit($oh, 'Prev',
				  mx_img_url('history.png'),'����');
	  else {
		  if (($sod_history & 5) == 5)
			  mx_formi_submit($oh, 'Prev',
					  mx_img_url('history-prev.png'),
					  '����');
		  if (($sod_history & 9) == 9)
			  mx_formi_submit($oh, 'Next',
					  mx_img_url('history-next.png'),
					  '���');
	  }
	  print "<br />\n";
	  mx_titlespan('[��ϼ»ܵ�Ͽ]');
	  return list_of_simple_objects::draw();
  }

  function base_fetch_stmt_0() {
    return (list_of_simple_objects::base_fetch_stmt_0() .
	    ' AND RX."ObjectID" = ' .
	    mx_db_sql_quote($this->Rx_ObjectID));
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '����') ? 1 : 0);
    }
    return $paging_orders;
  }

}

function __lib_u_therapist_nexec_cfg(&$cfg) {
	__lib_u_therapist_nexec_list_cfg(&$cfg);

	$select = 'SELECT
X."ID",
X."����",
X."��Ͻ����",
ET."ObjectID" as "�»���ˡ��",
(EE."��" || \' \' || EE."̾") as "��Ͽ��",
X."CreatedBy",
X."ɾ��S", X."ɾ��O", X."ɾ��A", X."ɾ��P"
FROM "��ϼ»ܵ�Ͽ" AS X
LEFT JOIN "������Ģ" AS ET ON X."�»���ˡ��" = ET."ObjectID"
LEFT JOIN "������Ģ" AS EE
	ON X."CreatedBy" = EE.userid AND EE."Superseded" IS NULL
';
	$cfg['HSTMT'] = $select . 'WHERE NULL IS NULL';
	$cfg['STMT'] = $select . 'WHERE X."Superseded" IS NULL';

	$cfg['DCOLS_BASE'] = array
		(array('Column' => '��Ͻ����',
		       'Label' => '��Ͻ����',
		       'Draw' => 'static',
		       'Page' => 0),
		 array('Column' => "ID",
		       'Label' => '�»ܵ�ϿID',
		       'Draw' => 'static',
		       'ICOLS-Exclude' => 1,
		       'Page' => 0),
		 array('Column' => '����',
		       'Label' => '����',
		       'Draw' => 'text',
		       'Option' => array('ime' => 'disabled'),
		       'Page' => 0),
		 array('Column' => '�»���ˡ��',
		       'Label' => '�»���ˡ��',
		       'Draw' => 'enum',
		       'Enum' => $cfg['EmployeeEnum'],
		       'Page' => 0),
		 array('Column' => '��Ͽ��',
		       'Label' => '��Ͽ��̾',
		       'Draw' => 'static',
		       'ICOLS-Exclude' => 1,
		       'Page' => 0),
		 array('Column' => 'CreatedBy',
		       'Draw' => NULL,
		       'ICOLS-Exclude' => 1),
		 array('Column' => 'ɾ��S',
		       'Label' => 'ɾ��S',
		       'Draw' => 'textarea',
		       'Page' => 0),
		 array('Column' => 'ɾ��O',
		       'Label' => 'ɾ��O',
		       'Draw' => 'textarea',
		       'Page' => 0),
		 array('Column' => 'ɾ��A',
		       'Label' => 'ɾ��A',
		       'Draw' => 'textarea',
		       'Page' => 0),
		 array('Column' => 'ɾ��P',
		       'Label' => 'ɾ��P',
		       'Draw' => 'textarea',
		       'Page' => 0));

	$cfg['DPAGES'] = $cfg['DPAGES_BASE'] = array('�»ܵ�Ͽ');
	$cfg['DCOLS'] = $cfg['DCOLS_BASE'];

	$cfg['EPAGES'] = $cfg['EPAGES_BASE'] = array('�»ܵ�Ͽ');
	$cfg['ECOLS'] = $cfg['ECOLS_BASE'] = $cfg['DCOLS_BASE'];

	$cfg['ICOLS'] = array();
	foreach ($cfg['ECOLS_BASE'] as $d) {
		if (array_key_exists('ICOLS-Exclude', $d))
			continue;
		$cfg['ICOLS'][] = $d['Column'];
	}
}

function __lib_u_therapist_nexec_fetch_subs($id, &$data)
{
	$stmt = ('SELECT 
"ObjectID", "��������", "��λ����", "�������", "��������",
"ñ�̿�", "ñ�̼���", "���Ź԰٥�����", "������"
FROM "��ϼ»ܵ�Ͽ����"
WHERE "��ϼ»ܵ�Ͽ" = ' . mx_db_sql_quote($id) . '
ORDER BY "ObjectID"');

	$db = mx_db_connect();
	$data1 = pg_fetch_all(pg_query($db, $stmt));
	if (!is_array($data1))
		return $data;
	for ($i = 1; $i <= count($data1); $i++) {
		$tum = $data1[$i-1];
		foreach ($tum as $k => $v) {
			$data["$i-$k"] = $v;
		}
	}
	return $data;
}

function __lib_u_therapist_nexec_unannotate_datetime($data, $v)
{
	/* Reverse annotation */
	$match = array();
	if (preg_match('/^\d+:\d+(?::\d+)?$/',
		       $v, &$match))
		$v = $data['����'] . " $v";
	return $v;
}

function __lib_u_therapist_nexec_annotate_datetime(&$data)
{
	$this_date = $data['����'];
	$last_i = __lib_u_therapist_nexec_count_subpage($data);
	for ($i = 1; $i <= $last_i; $i++) {
		foreach (array("��������", "��λ����") as $k) {
			$v = $data["$i-$k"];
			$match = array();
			/* Full date -- is it the same day */
			if (preg_match('/^(\d+-\d+-\d+) (.*)$/',
				       $v, &$match) &&
			    $this_date == $match[1])
				$v = $match[2];
			if (preg_match('/(\d+:\d+):00$/', $v,
				       &$match))
				$v = $match[1];
			$data["$i-$k"] = $v;
		}
	}
}

function __lib_u_therapist_nexec_tweak_cfg(&$acols, &$apages, $fm, $to)
{
	global $_lib_u_therapist_execution_code_enum;
	for ($i = $fm; $i <= $to; $i++) {
		$acols[] = array('Column' => "$i-��������",
				      'Label' => "��������",
				      'Draw' => 'timestamp',
				      'Page' => $i);
		$acols[] = array('Column' => "$i-��λ����",
				      'Label' => "��λ����",
				      'Draw' => 'timestamp',
				      'Page' => $i);
		$acols[] = array('Column' => "$i-�������",
				      'Label' => "�������",
				      'Draw' => 'enum',
				      'Enum' =>
				      array('������' => '������',
					    '�¼�' => '�¼�'),
				      'Page' => $i);
		$acols[] = array('Column' => "$i-��������",
				      'Label' => "��������",
				      'Draw' => 'text',
				      'Page' => $i);
		$acols[] = array('Column' => "$i-ñ�̿�",
				      'Label' => "ñ�̿�",
				      'Draw' => 'text',
				      'Option' => array('ime' => 'disabled'),
				      'Page' => $i);

		$acols[] = array('Column' => "$i-���Ź԰٥�����",
				      'Label' => "���Ź԰٥�����",
				      'Page' => $i,
				      'Draw' => 'enum',
				      'Enum' => $_lib_u_therapist_execution_code_enum);

		$acols[] = array('Column' => "$i-������",
				      'Label' => "������",
				      'Draw' => 'textarea',
				      'Page' => $i);
		$apages[] = "($i)";
	}
}

function __lib_u_therapist_nexec_count_subpage($data) {
	$last_i = 0;
	for ($i = 1;
	     array_key_exists("$i-��������", $data);
	     $i++) {
		$last_i = $i;
	}
	return $last_i;
}

class rehab_nexec_display extends simple_object_display {

	var $debug = 1;

	function rehab_nexec_display($prefix, $cfg=NULL) {
		if (is_null($cfg)) $cfg = array();
		__lib_u_therapist_nexec_cfg(&$cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function pre_draw_hook($data, $hdata) {
		$last_i = __lib_u_therapist_nexec_count_subpage($data);
		if ($hdata) {
			$ii = __lib_u_therapist_nexec_count_subpage($hdata);
			if ($last_i < $ii)
				$last_i = $ii;
		}

		$cfg =& $this->so_config;
		$dcols = $cfg['DCOLS_BASE'];
		$dpages = $cfg['DPAGES_BASE'];
		__lib_u_therapist_nexec_tweak_cfg(&$dcols,
						  &$dpages,
						  1, $last_i);
		$cfg['DCOLS'] = $dcols;
		$cfg['DPAGES'] = $dpages;
		simple_object_display::pre_draw_hook($data, $hdata);
	}

	function fetch_data($id) {
		$data = simple_object_display::fetch_data($id);
		return __lib_u_therapist_nexec_fetch_subs($id, &$data);
	}

	function annotate_row_data(&$data) {
		__lib_u_therapist_nexec_annotate_datetime(&$data);
	}

}

class rehab_nexec_edit extends simple_object_edit {

	var $debug = 1;

	function rehab_nexec_edit($prefix, $cfg=NULL) {
		if (is_null($cfg)) $cfg = array();
		__lib_u_therapist_nexec_cfg(&$cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function get_form_data() {
		# Slurp subpage fields from the $_REQUEST
		$i = 1;
		
		$cfg =& $this->so_config;
		$ecols = $cfg['ECOLS_BASE'];
		$epages = $cfg['EPAGES_BASE'];
		while (1) {
			$field = $this->en("$i-��������");
			if (!array_key_exists($field, $_REQUEST))
				break;
			__lib_u_therapist_nexec_tweak_cfg(&$ecols,
							  &$epages,
							  $i, $i);
			$i++;
		}
		$cfg['ECOLS'] = $ecols;
		$cfg['EPAGES'] = $epages;
		simple_object_edit::get_form_data();
		$this->add_empty_subpage_as_needed();
	}

	function add_empty_subpage_as_needed() {
		$last_i = __lib_u_therapist_nexec_count_subpage($this->data);
		$cfg =& $this->so_config;

		# If there are pages already, and the last one is empty,
		# there is no point adding any.  However, we want to
		# make sure its tab does not say "($last_i)".
		if (0 < $last_i && $this->is_empty_subpage($last_i)) {
			$cfg['EPAGES'][$last_i] = "����";
			return;
		}

		# Otherwise, we would add one page.
		$ecols = $cfg['ECOLS'];
		$epages = $cfg['EPAGES'];
		$last_i++;
		__lib_u_therapist_nexec_tweak_cfg(&$ecols,
						  &$epages,
						  $last_i, $last_i);
		$epages[$last_i] = "����";

		# For pages other than the last one, if the date-time
		# is empty, fill the default based on the current time.
		# But leave the new page empty.
		$now = time();
		$begintime = strftime('%H:%M', $now - 1200);
		$endtime = strftime('%H:%M', $now);
		for ($i = 1; $i < $last_i; $i++) {
			if ($this->data["$i-��������"] == '' &&
			    $this->data["$i-��λ����"] == '') {
				$this->data["$i-��������"] = $begintime;
				$this->data["$i-��λ����"] = $endtime;
			}
		}
		$cfg['ECOLS'] = $ecols;
		$cfg['EPAGES'] = $epages;
	}

	function is_empty_subpage($i, $data=NULL) {
		if (is_null($data))
			$data = $this->data;
		foreach (array('��������', 'ñ�̿�', '������') as $fld) {
			if ($data["$i-$fld"] != '') {
				return 0;
			}
		}
		return 1;
	}

	function fetch_data($id) {
		$data = simple_object_edit::fetch_data($id);
		return __lib_u_therapist_nexec_fetch_subs($id, &$data);
	}

	function anew_edit_tweak() {
		$this->data['��Ͻ����'] = $this->Rx_ObjectID;
		$last_i = __lib_u_therapist_nexec_count_subpage($this->data);
		$cfg =& $this->so_config;
		$ecols = $cfg['ECOLS_BASE'];
		$epages = $cfg['EPAGES_BASE'];
		__lib_u_therapist_nexec_tweak_cfg(&$ecols, &$epages,
						  1, $last_i);
		$cfg['ECOLS'] = $ecols;
		$cfg['EPAGES'] = $epages;

		$this->add_empty_subpage_as_needed();
	}

	function anew_tweak($orig_id) {
		return $this->anew_edit_tweak();
	}

	function edit_tweak() {
		return $this->anew_edit_tweak();
	}

	function annotate_row_data(&$data) {
		__lib_u_therapist_nexec_annotate_datetime(&$data);
	}

	function annotate_form_data(&$data) {
		simple_object_edit::annotate_form_data(&$data);
		__lib_u_therapist_nexec_annotate_datetime(&$data);
	}

	function _validate($force=NULL) {
		$time_unit_err = $fatal_errs = $errs = 0;
		$d = $this->data;
//0426-2014
 
 
		if ($msg = mx_db_validate_date($d['����'])) {
			$this->err("����: $msg");
			$errs++;
			$fatal_errs++;
		}
 


		for ($i = 1;
		     array_key_exists("$i-��������", $d);
		     $i++) {
			if ($this->is_empty_subpage($i) &&
			    $d["$i-��������"] == '' &&
			    $d["$i-��λ����"] == '')
				continue; // discard

			foreach (array('��������', '��λ����') as $fld) {
				$v = __lib_u_therapist_nexec_unannotate_datetime($d, $d["$i-$fld"]);
				if ($msg = mx_db_validate_datetime($v)) {
					$this->err("$fld($i): $msg");
					$errs++;
					$fatal_errs++;
					$time_unit_err = 1;
				}
			}
			if ($msg = mx_db_validate_posint($d["$i-ñ�̿�"])) {
				$this->err("ñ�̿�($i): $msg");
				$errs++;
				$fatal_errs++;
				$time_unit_err = 1;
			}
			if (($msg = mx_db_validate_length
			     ($d["$i-������"], 1, 0)) &&
			    ($msg = mx_db_validate_length
			     ($d["$i-��������"], 1, 0))) {
				$this->err("������($i)����������($i)��ξ�������ǤϤ����ޤ���");
				$errs++;
				$fatal_errs++;
			}
			if (!$time_unit_err) {
				$v = __lib_u_therapist_nexec_unannotate_datetime($d, $d["$i-��������"]);
				$begin = mx_datetime_to_unixtime($v);
				$v = __lib_u_therapist_nexec_unannotate_datetime($d, $d["$i-��λ����"]);

				$end = mx_datetime_to_unixtime($v);
				$duration = $end - $begin;
				$units = $d["$i-ñ�̿�"];

				if ($duration < $units * 20 * 60) {
					$minutes = floor($duration / 60);
					$seconds = $duration - $minutes * 60;
					$this->err("($i) �η������� $minutes ʬ $seconds �� ��".
						   " $units ñ�̤�­��ޤ���");
					$errs++;
				}
				else if (($units + 1) * 20 * 60 <= $duration) {
					$minutes = floor($duration / 60);
					$seconds = $duration - $minutes * 60;
					$this->err("($i) �η������� $minutes ʬ $seconds �� ��".
						   " $units ñ�̤�Ķ�ᤷ�Ƥ��ޤ�");
					$errs++;
				}
			}
		}

		$this->error_override_allowed = (!$force && $fatal_errs == 0);

		if ($errs == 0 || (($fatal_errs == 0) && $force)) {
			$this->errmsg = '';
			return 'ok';
		}
 
//0430-2014
 
	}

	function data_compare($curr, $data) {
		if (simple_object_edit::data_compare($curr, $data))
			return 1;
		$names = array("��������", "��λ����", "�������",
			       "��������", "ñ�̿�", "ñ�̼���",
			       "���Ź԰٥�����", "������");

		$last_c = __lib_u_therapist_nexec_count_subpage($curr);
		while (0 < $last_c) {
			if (!$this->is_empty_subpage($last_c, $curr))
				break;
			$last_c--;
		}
		$last_d = __lib_u_therapist_nexec_count_subpage($data);
		while (0 < $last_d) {
			if (!$this->is_empty_subpage($last_d, $data))
				break;
			$last_d--;
		}
		if ($last_c != $last_d)
			return 1;

		for ($i = 1; $i <= $last_c; $i++) {
			foreach ($names as $n) {
				if ($curr["$i-$n"] != $data["$i-$n"])
					return 1;
			}
		}
		return 0;
	}

	function _update_subtables(&$db, $id, $stash_id) {
		if (! is_null($stash_id)) {
			$stmt = ('
UPDATE "��ϼ»ܵ�Ͽ����" 
SET "��ϼ»ܵ�Ͽ" = ' . mx_db_sql_quote($stash_id) . '
WHERE "��ϼ»ܵ�Ͽ" = ' . mx_db_sql_quote($id));
			$this->dbglog("Stash-Subs: $stmt\n");
			if (! pg_query($db, $stmt))
				return pg_last_error($db);
		}
		$i = 1;
		$names = array("��������", "��λ����", "�������",
			       "��������", "ñ�̿�", "ñ�̼���",
			       "���Ź԰٥�����", "������");
		while (1) {
			if (!array_key_exists("$i-��������", $this->data))
				break;
			if ($this->is_empty_subpage($i) &&
			    $d["$i-��������"] == '' &&
			    $d["$i-��λ����"] == '') {
				$i++;
				continue; // discard
			}
			$time_begin = __lib_u_therapist_nexec_unannotate_datetime($this->data, $this->data["$i-��������"]);
			$time_end = __lib_u_therapist_nexec_unannotate_datetime($this->data, $this->data["$i-��λ����"]);
			$stmt = ('
INSERT INTO "��ϼ»ܵ�Ͽ����"
("��ϼ»ܵ�Ͽ", "��������", "��λ����", "�������",
 "��������", "ñ�̿�", "ñ�̼���",
 "���Ź԰٥�����", "������") VALUES
(' . mx_db_sql_quote($id) . ', ' .
mx_db_sql_quote($time_begin) . ', ' .
mx_db_sql_quote($time_end) . ', ' .
mx_db_sql_quote($this->data["$i-�������"]) . ', ' .
mx_db_sql_quote($this->data["$i-��������"]) . ', ' .
mx_db_sql_quote($this->data["$i-ñ�̿�"]) . ', ' .
mx_db_sql_quote($this->data["$i-ñ�̼���"]) . ', ' .
mx_db_sql_quote($this->data["$i-���Ź԰٥�����"]) . ', ' .
mx_db_sql_quote($this->data["$i-������"]) . ')');
			$this->dbglog("Insert-Subs: $stmt\n");
			if (! pg_query($db, $stmt))
				return pg_last_error($db);
			$i++; 
		}
		return '';
	}

}

?>
