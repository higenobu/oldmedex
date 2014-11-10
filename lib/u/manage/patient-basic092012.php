<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/dodwell.php';

$_lib_u_manage_patient_basic_cfg = array
(
 'TABLE' => '������Ģ',
 'COLS' => array("����ID", "��", "̾", "�եꥬ��",
		 "����", "������", "��ǯ����",
		 "����0", "����1",
		 "����2", "����3", "����4",
		 "��������",
		 "��������",
		 "�᡼�륢�ɥ쥹",
		 "������ʬ",
		"�¼�",
		 "�ݸ����ֹ�",
		 "���ݸ���",
		 "���ݸ��Լ�Ģ�ε���",
		 "���ݸ��Լ�Ģ���ֹ�",
		 "������ô���ֹ�",
		 "������ô���Ťμ�����ֹ�",
		 "������ô���ֹ�2",
		 "������ô���Ťμ�����ֹ�2",
		 "������ô���ֹ�3",
		 "������ô���Ťμ�����ֹ�3",

		 "ȯ����", "������", "�ౡͽ����", "�ౡͽ�ꡦ����",
		 "��˴��", "����",
		 "������", "���Ū�԰���", "��˾����", "������", "���ԥޡ���",
		 "��շ����£ϼ�", "��շ��ң輰",
		 "�ȣ£󹳸�",
		 "����륮��",
		 "������",
		 "Ʃ�ϴ��ԥե饰",
		 "��̳��̾", "��̳��͹���ֹ�", "��̳�轻��",
		 "��̳�������ֹ�",
		 "������̾", "������͹���ֹ�", "�����轻��",
		 "�����������ֹ�"),

 'ENABLE_QBE' => array(array('Column' => '����ID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		       "��",
		       "̾",
		       "�եꥬ��",
		       '//',
		       array('Column' => "����", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => '��', 'F' => '��',
					     '' => '(�ɤ���Ǥ�)')),
		       "��ǯ����",
		       array('Column' => "������ʬ", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('I' => '����', 'O' => '����',
					     'E' => '����Ƚ���о�',
					     'W' => '�����Ԥ�',
					     '' => '(̤����)') ),
		       array('Column' => "���ԥޡ���", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => 'lazy'),
		       ),

 'LCOLS' => array("����ID", "��", "̾", "�եꥬ��", "����", "��ǯ����","�¼�"),
 'ALLOW_SORT' => 1,
 'LLAYO' => array('����ID', "��", "̾", "�եꥬ��",
		  '//',
		  array('Column' => "����",
			'Draw' => 'enum',
			'Enum' => array('M' => '��', 'F' => '��',
					NULL => '����') ),
		  "��ǯ����",
		  array('Column' => "������ʬ", 'Draw' => 'enum',
			'Singleton' => 1,
			'Enum' => array('I' => '����', 'O' => '����',
					'E' => '����Ƚ���о�',
					'W' => '�����Ԥ�',
					'' => '(̤����)') ),
		  "���ԥޡ���",
		  ),
 'DCOLS' => array("����ID", "��", "̾", "�եꥬ��",
		  array('Column' => "����",
			'Draw' => 'enum',
			'Enum' => array('M' => '��', 'F' => '��',
					NULL => '����') ),
		  "��ǯ����",
		  array('Column' => "����0", 'Label' => "��"),
		  array('Column' => "����1", 'Label' => "��ƻ�ܸ�"),
		  array('Column' => "����2", 'Label' => "��Į¼"),
		  array('Column' => "����3", 'Label' => "����"),
		  array('Column' => "����4", 'Label' => "����"),
		  "��������",
		  "��������",
		  "�᡼�륢�ɥ쥹",
		  array('Column' => "������ʬ",
			'Draw' => 'enum',
			'Enum' => array('I' => '����', 'O' => '����',
					'E' => '����Ƚ���о�',
					'W' => '�����Ԥ�',
					NULL => '̤����') ),

		  "�ݸ����ֹ�",
		  array('Column' => "���ݸ���",
			'Draw' => 'enum',
			'Enum' => array('1' => '�ܿ�', '2' => '��²',
					NULL => '̤����') ),
		  "���ݸ��Լ�Ģ�ε���",
		  "���ݸ��Լ�Ģ���ֹ�",
		  "������ô���ֹ�",
		  "������ô���Ťμ�����ֹ�",
		  "������ô���ֹ�2",
		  "������ô���Ťμ�����ֹ�2",
		  "������ô���ֹ�3",
		  "������ô���Ťμ�����ֹ�3",

		  "ȯ����", "������", "�ౡͽ����",
		  array('Column' => "�ౡͽ�ꡦ����",
			'Draw' => 'enum',
			'Enum' => array('F' => 'ͽ��', 'E' => '����',
					'N/A' => 'N/A', 
					NULL => '̤����') ),
		  array('Column' => "��˾����",
			'Label' => "���ԥ��롼��"),
		  "������",
		  "���ԥޡ���",
		  "��˴��", "����",
		  array('Column' => "������",
			'Draw' => 'enum',
			'Enum' => array('A' => '�о� A',
					'B' => '�о� B',
					'C' => '�о� C',
					'D' => '�о� D',
					'E' => '�о� E',
					'Z' => '�оݳ�',
					NULL => '(̤����)')),
		  array('Column' => "���Ū�԰���",
			'Draw' => 'enum',
			'Enum' => array('S' => '����', 'U' => '�԰���',
					'W' => '�����',
					NULL => '(̤����)')),
		  "��շ����£ϼ�", "��շ��ң輰",
		  "�ȣ£󹳸�",
		  "����륮��",
		  "������",
		  "Ʃ�ϴ��ԥե饰",
		  "��̳��̾", "��̳��͹���ֹ�", "��̳�轻��",
		  "��̳�������ֹ�",
		  "������̾", "������͹���ֹ�", "�����轻��",
		  "�����������ֹ�",
			"�¼�",
		  array('Column' => 'CreatedBy',
			'Label' => '��Ͽ��',
			'Draw' => 'user'),
		  ),

 'ECOLS' => array(array('Column' => "����ID",
			'Option' => array('ime' => 'disabled',
					  'zeropad' => $_mx_patient_id_zeropad,
					  'validate' => 'nonnull')),
		  array('Column' => "��",
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "̾",
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "�եꥬ��",
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "����",
			'Draw' => 'enum',
			'Enum' => array('M' => '��', 'F' => '��',
					NULL => '����') ),
		  array('Column' => "��ǯ����",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "����0",
			'Label' => "��",
			'Draw' => 'post_code',
			'Option' => array('ime' => 'disabled',
					  'zip' => '����0',
					  'prefecture' => '����1',
					  'city' => '����2',
					  'block' => '����3',
					  'add_id' => 1,
					  )),
		  array('Column' => "����1", 'Label' => "��ƻ�ܸ�", 'Option' => array('add_id' => 1)),
		  array('Column' => "����2", 'Label' => "��Į¼", 'Option' => array('add_id' => 1)),
		  array('Column' => "����3", 'Label' => "����", 'Option' => array('add_id' => 1)),
		  array('Column' => "����4", 'Label' => "����", 'Option' => array('add_id' => 1)),
		  array('Column' => "��������",
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "��������",
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "�᡼�륢�ɥ쥹",
			'Draw' => 'text',
			'Option' => array('size' => 80)),
		  array('Column' => "������ʬ",
			'Draw' => 'enum',
			'Enum' => array('I' => '����', 'O' => '����',
					'E' => '����Ƚ���о�',
					'W' => '�����Ԥ�',
					NULL => '̤����') ),

		  array('Column' => "�ݸ����ֹ�",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "���ݸ���",
			'Draw' => 'enum',
			'Enum' => array('1' => '�ܿ�', '2' => '��²',
					NULL => '̤����') ),
		  array('Column' => "���ݸ��Լ�Ģ�ε���",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "���ݸ��Լ�Ģ���ֹ�",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "������ô���ֹ�",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "������ô���Ťμ�����ֹ�",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "������ô���ֹ�2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "������ô���Ťμ�����ֹ�2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "������ô���ֹ�3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "������ô���Ťμ�����ֹ�3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),

		  array('Column' => "ȯ����",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "������",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "�ౡͽ����",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "�ౡͽ�ꡦ����",
			'Draw' => 'enum',
			'Enum' => array('F' => 'ͽ��', 'E' => '����',
					NULL => '̤����') ),
		  array('Column' => "��˾����",
			'Label' => "���ԥ��롼��",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  array('Column' => "���ԥޡ���",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  "������",
		  array('Column' => "��˴��",
			'Option' => array('ime' => 'disabled')),
		  "����",
		  array('Column' => "������",
			'Draw' => 'enum',
			'Enum' => array('A' => '�о� A',
					'B' => '�о� B',
					'C' => '�о� C',
					'D' => '�о� D',
					'E' => '�о� E',
					'Z' => '�оݳ�',
					NULL => '(̤����)')),
		  array('Column' => "���Ū�԰���",
			'Draw' => 'enum',
			'Enum' => array('S' => '����', 'U' => '�԰���',
					'W' => '�����',
					NULL => '(̤����)')),
		  array('Column' => "��շ����£ϼ�",
			'Draw' => 'enum',
			'Enum' => array('A' => 'A', 'B' => 'B',
					'AB' => 'AB', 'O' => 'O',
					NULL => '����')),
		  array('Column' => "��շ��ң輰",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => '����')),
		  array('Column' => "�ȣ£󹳸�",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => '����')),
		  "����륮��",
		  "������",
		  array('Column' => "Ʃ�ϴ��ԥե饰",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => '����')),
		  "��̳��̾",
		  array('Column' => "��̳��͹���ֹ�",
			'Option' => array('ime' => 'disabled')),
		  "��̳�轻��",
		  array('Column' => "��̳�������ֹ�",
			'Option' => array('ime' => 'disabled')),
		  "������̾",
		  array('Column' => "������͹���ֹ�",
			'Option' => array('ime' => 'disabled')),
		  "�����轻��",
		  array('Column' => "�����������ֹ�",
			'Option' => array('ime' => 'disabled')),
		array('Column' => "�¼�",
			),
		  ),
 );
$_lib_u_manage_patient_basic_cfg['HSTMT'] =
('SELECT "ObjectID", "CreatedBy", ' .
 implode(', ', array_map('mx_db_sql_quote_name',
			 $_lib_u_manage_patient_basic_cfg['COLS'])) .
 ', ("��" || "̾") AS "��̾"' .
 ' FROM ' . mx_db_sql_quote_name($_lib_u_manage_patient_basic_cfg['TABLE']) .
 ' WHERE (NULL IS NULL) ');

function _lib_u_manage_patient_basic_cfg_add_page_num($ary, &$map) {
	global $_lib_u_manage_patient_basic_cfg;

	$in = $_lib_u_manage_patient_basic_cfg[$ary];
	$out = array();
	foreach ($in as $data) {
		if (!is_array($data)) {
			$name = $data;
		}
		else {
			$name = $data['Column'];
		}
		if (array_key_exists($name, $map)) {
			if (!is_array($data)) {
				$data = array('Column' => $data,
					      'Label' => $data,
					      'Draw' => 'text');
			}
			$data['Page'] = $map[$name];
		}
		$out[] = $data;
	}
	$_lib_u_manage_patient_basic_cfg[$ary] = $out;
}

if ($_mx_cheap_layout) {
	$all_page_numcol = 3;
	$first_page_numcol = 25;
	$page = array();
	for ($i = 0;
	     $i < count($_lib_u_manage_patient_basic_cfg['COLS']);
	     $i++) {
		$name = $_lib_u_manage_patient_basic_cfg['COLS'][$i];
		if ($i < $all_page_numcol)
			;
		else if ($i < $first_page_numcol)
			$page[$name] = 0;
		else
			$page[$name] = 1;
	}
	_lib_u_manage_patient_basic_cfg_add_page_num('DCOLS', &$page);
	_lib_u_manage_patient_basic_cfg_add_page_num('ECOLS', &$page);
	$_lib_u_manage_patient_basic_cfg['DPAGES'] = array('(1)', '(2)');
	$_lib_u_manage_patient_basic_cfg['EPAGES'] = array('(1)', '(2)');
}

class list_of_patient_basics extends list_of_simple_objects {
  function list_of_patient_basics($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '���ԥޡ���') {
		  return mx_dbenum_patientmark('=');
	  }
	  return $desc['Enum'];
  }

}

class patient_basic_display extends simple_object_display {
  function patient_basic_display($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  
  function annotate_row_data(&$data) {
    if ($data["������ʬ"] == 'O') {
      $data["�ౡͽ����"] = 'N/A';
      $data["�ౡͽ�ꡦ����"] = 'N/A';
    }
  }

  function issue_card() {
    $data = $this->prepare_data_for_draw();
    $dodwell = new Dodwell($data);
    if(! $dodwell->write_csv()) {
      print implode('<br>', $dodwell->error);
    }
  }
}

class patient_basic_edit extends simple_object_edit {
  function patient_basic_edit($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '��˾����')
		  return mx_dbenum_patientgroup();
	  if ($desc['Column'] == '���ԥޡ���')
		  return mx_dbenum_patientmark();
	  return $desc['Enum'];
  }

  function _validate() {
    global $_lib_u_manage_patient_basic_cfg;

    $d =& $this->data;

    $bad = 0;
    // Ugh.
    if (! $this->id) {
      $db = mx_db_connect();
      $r = mx_db_fetch_single($db, 'SELECT "ObjectID" FROM "������Ģ"
WHERE "����ID" = ' . mx_db_sql_quote($d['����ID']));
      if (is_array($r) || is_null($r)) {
	$this->err("(����ID): ���ꤵ�줿�ͤϤ��Ǥ˻Ȥ��Ƥ��ޤ���\n");
	$bad++;
      }
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

    if (($d['������ʬ'] == 'I') || ($d['������ʬ'] == 'W')) {
	    foreach (array('������', '�ౡͽ����') as $c) {
		    if ($st = mx_db_validate_date($d[$c])) {
			    $this->err("($c): $st\n");
			    $bad++;
		    }
	    }
    }
    if (! $bad)
      return 'ok';
  }

  function trim_data(&$row) {
    foreach ($row as $k => $v) {
      if (! is_null($v))
	$row[$k] = trim($v);
    }
  }

  function annotate_row_data(&$data) { $this->trim_data(&$data); }
  function annotate_form_data(&$data) {
	  foreach (array('��','̾','�եꥬ��') as $c) {
		  if (is_null($data[$c]))
			  continue;
		  $data[$c] = trim(mx_xlate_jzspace($data[$c]));
	  }
	  $fn = $data['��'];
	  $gn = $data['̾'];

	  if ($gn == '' && $fn != '') {
		  $m = array();
		  if (preg_match('/^(.*?) +(.*)$/', $fn, &$m)) {
			  $data['��'] = $m[1];
			  $data['̾'] = $m[2];
		  }
	  }
    simple_object_edit::annotate_form_data(&$data);
    $this->trim_data(&$data);
  }

  function allocate_new_patient_id() {
	  global $_mx_patient_id_zeropad, $_mx_auto_allocate_patient_id;

	  if (!$_mx_auto_allocate_patient_id)
		  return NULL;
	  $m = array();
	  if (preg_match('/^(.+),(.+)$/', $_mx_auto_allocate_patient_id,
			 &$m)) {
		  $bottom = $m[1];
		  $top = $m[2];
		  if (0 < $_mx_patient_id_zeropad)
			  $top = mx_zeropad($top, $_mx_patient_id_zeropad);
		  $tsql = '"����ID" <= ' . mx_db_sql_quote($top) . ' AND ';
	  }
	  else {
		  $bottom = $_mx_auto_allocate_patient_id;
		  $top = undef;
		  $tsql = '';
	  }

	  $db = mx_db_connect();
	  $stmt = 'SELECT max("����ID") AS "I" FROM "������Ģ" WHERE ' .
		  $tsql .
		  '"Superseded" IS NULL';
	  if($_mx_patient_id_zeropad > 0)
	    $stmt .= ' AND length("����ID") = ' . $_mx_patient_id_zeropad;

	  $curr = mx_db_fetch_single($db, $stmt);

	  if (is_null($curr))
		  $curr = $_mx_auto_allocate_patient_id;
	  else {
		  /*
		   * NEEDSWORK: this should be
		   * $curr = mz_un_zeropad($curr['I']) + 1
		   */
		  $curr = intval($curr['I']) + 1;
	  }

	  if (0 < $_mx_patient_id_zeropad)
		  $curr = mx_zeropad($curr, $_mx_patient_id_zeropad);
	  return $curr;
  }

  function anew_tweak($orig_id) {
	  global $_mx_config_pt_class_default;

	  $this->data['����ID'] = $this->allocate_new_patient_id();
	  $this->data["������ʬ"] = $_mx_config_pt_class_default;
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    if ($this->_validate() != 'ok')
      return 'failure';

    $orig_id = $this->id;
    if (($ok = simple_object_edit::try_commit(&$db)) != 'ok')
      return $ok;
    if (! $orig_id) {
      // We created a patient.  Need to create the associated
      // Patient-Employee association hook, if it does not exist.
      $stmt = 'SELECT "ObjectID" FROM "����ô������" WHERE
               "Superseded" IS NULL AND "����" = ' . $this->id;
      $curr = mx_db_fetch_single($db, $stmt);
      $stmt = 'INSERT INTO "����ô������" ("����", "CreatedBy") VALUES (' .
	$this->id . ', ' .
	mx_db_sql_quote($mx_authenticate_current_user) . ')';
      if (! $curr && ! is_null($curr) && pg_query($db, $stmt))
	; // All is well.
      else
	return 'failure';
    }
    if ($this->id &&
	($this->data['������ʬ'] != 'I') &&
	($this->data['������ʬ'] != 'W') &&
	!mx_db_validate_date($this->data['�ౡͽ����'])) {
	    $dis = sprintf("'%s'", $this->data['�ౡͽ����']);
	    $stmt = sprintf(
		    'DELETE FROM "�¾�����"
		    WHERE "��ͭ��" = %d AND (%s <= "��ͭ����")',
		    $this->id, $dis);
	    pg_query($db, $stmt);
	    $stmt = sprintf(
		    'UPDATE "�¾�����" SET "��ͭ��λ" = %s
		     WHERE "��ͭ��" = %d AND
		     ("��ͭ����" <= %s) AND (%s < "��ͭ��λ")',
		    $dis, $this->id, $dis, $dis);
	    pg_query($db, $stmt);
    }
    return 'ok';
  }

}
?>
