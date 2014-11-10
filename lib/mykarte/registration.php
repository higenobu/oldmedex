<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';

$_mykarte_registration_cfg = array
(
	'ECOLS' => array(array('Column' => 'handle',
			       'Label' => '�����꡼��̾',
			       'Option' => array('validate' => 'nonnull,len',
						 'validate-minlen' => 6,
						 'validate-maxlen' => 16)),

			 array('Column' => "����0",
			       'Label' => "��",
			       'Draw' => 'post_code',
			       'Option' => array('ime' => 'disabled',
						 'zip' => '����0',
						 'prefecture' => '����1',
						 'city' => '����2',
						 'block' => '����3',
						 'add_id' => 1,
						 'validate' => 'nonnull,len',
						 'validate-minlen' => 7,
						 'validate-maxlen' => 7)),
			 array('Column' => "����1",
			       'Label' => "��ƻ�ܸ�",
			       'Option' => array('add_id' => 1,
						 'validate' => 'nonnull,len',
						 'validate-maxlen' => 20)),
			 array('Column' => "����2",
			       'Label' => "��Į¼",
			       'Option' => array('add_id' => 1,
						 'validate' => 'nonnull,len',
						 'validate-maxlen' => 20)),
			 array('Column' => "����3",
			       'Label' => "����",
			       'Option' => array('add_id' => 1,
						 'validate' => 'len',
						 'validate-maxlen' => 60)),

			 array('Column' => '��',
			       'Label' => '��',
			       'Option' => array('validate' => 'nonnull,len',
						 'validate-maxlen' => 30)),
			 array('Column' => '̾',
			       'Label' => '̾',
			       'Option' => array('validate' => 'nonnull,len',
						 'validate-maxlen' => 30)),
			 array('Column' => '�եꥬ��',
			       'Label' => '̾���դ꤬��',
			       'Option' => array('validate' => 'nonnull,len',
						 'validate-maxlen' => 60)),
			 array('Column' => 'anonymous',
			       'Label' => '��̾��������ʤ�',
			       'Draw' => 'check'),
			 array('Column' => '����',
			       'Draw' => 'enum',
			       'Enum' => array('' => '',
					       'M' => '��', 'F' => '��'),
			       'Option' => array('validate' => 'nonnull')),
			 array('Column' => 'email',
			       'Label' => '�᡼�륢�ɥ쥹',
			       'Option' => array('validate' => 'nonnull,len',
						 'validate-minlen' => 0,
						 'validate-maxlen' => 128)),
			 array('Column' => '��ǯ����',
			       'Label' => '��ǯ����',
			       'Draw' => 'date',
			       'Option' => array('validate' => 'date,nonnull'))
		),
);

function email_valid($e) {
	$e = trim($e);
	return preg_match('/^[-.\w]+@(?:[-\w]+\.)+[\w]+$/', $e);
}

class registration_data_edit extends simple_object_edit {

	function registration_data_edit($prefix, $cfg=NULL) {
		global $_mykarte_registration_cfg;
		if (is_null($cfg))
			$cfg = $_mykarte_registration_cfg;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	// We do not do insert at all.
	function precompute_insert_stmt_head() {}
	function resync() {}

	function create_one(&$db, $d) {
		$this->confirm = NULL;

		// Create a new mx_authenticate object.
		$qid = mx_db_sql_quote($d['handle']);
		$result = mx_db_fetch_single($db,
					     "SELECT max(userid) FROM ".
					     "mx_authenticate");
		$uid = $result['max'] + 1;
		$this->log('UID is ' . $uid . "\n");
		$this->log('QID is ' . $qid . "\n");
		pg_query($db,
			 "INSERT INTO mx_authenticate ".
			 "(userid, username, passhash) ".
			 "VALUES ($uid, $qid, NULL)");
		$result = mx_db_fetch_single($db,
					     "SELECT userid FROM " .
					     "mx_authenticate " .
					     "WHERE username = $qid");
		if (! (is_array($result) && $result['userid'] == $uid) ) {
			$this->err("�����꡼��̾�����Ǥ˻Ȥ��Ƥ��ޤ�(1)");
			$this->log('RESULT: ' . mx_var_dump($result));
			return 'failure';
		}

		$stmt = ("SELECT ".
			 "nextval('\"������Ģ_ID_seq\"') AS pid," .
			 "nextval('\"������Ģ_ID_seq\"') AS eid");

		$sth = pg_query($db, $stmt);
		if (!$sth) {
			$this->err("�桼����Ͽ�Ǥ��ޤ���(1)");
			return 'failure';
		}
		$data = pg_fetch_all($sth);
		$pid = $data[0]['pid'];
		$eid = $data[0]['eid'];

		$v = array($pid, $pid,
			   mx_db_sql_quote(sprintf("A%07d", $pid)),
			   mx_db_sql_quote($d['��']),
			   mx_db_sql_quote($d['̾']),
			   mx_db_sql_quote($d['�եꥬ��']),
			   mx_db_sql_quote($d['����']),
			   mx_db_sql_quote($d['��ǯ����']),
			   mx_db_sql_quote($d['����0']),
			   mx_db_sql_quote($d['����1']),
			   mx_db_sql_quote($d['����2']),
			   mx_db_sql_quote($d['����3']));
		$stmt = ('INSERT INTO "������Ģ" (' .
			 '"ID", "ObjectID", ' .
		 '"����ID", "��", "̾", "�եꥬ��", "����", "��ǯ����", ' .
			 '"����0", "����1", "����2", "����3") VALUES (' .
			 implode(', ', $v) . ')');
		if (!pg_query($db, $stmt)) {
			$this->err("�桼����Ͽ�Ǥ��ޤ���(2)");
			return 'failure';
		}

		$v[0] = $v[1] = $eid;

		// Customer log-in; see psql/Customize-MYKARTE.sql
		$edata = array(2, 2, 2);

		$stmt = ('INSERT INTO "������Ģ" (' .
			 '"ID", "ObjectID", ' .
		 '"����ID", "��", "̾", "�եꥬ��", "����", "��ǯ����", ' .
			 '"����0", "����1", "����2", "����3", ' .
			 '"����", "����", "����", userid) VALUES (' .
			 implode(', ', $v) . ', ' .
			 implode(', ', $edata) . ', ' .
			 $uid .
			 ')');
		if (!pg_query($db, $stmt)) {
			$this->err("�桼����Ͽ�Ǥ��ޤ���(3)");
			return 'failure';
		}

		$stmt = ('INSERT INTO "����ô������" ("����") '.
			 'VALUES (' . $pid . ')');
		if (!pg_query($db, $stmt)) {
			$this->err("�桼����Ͽ�Ǥ��ޤ���(4)");
			return 'failure';
		}
		$stmt = ('SELECT "ObjectID" FROM "����ô������" WHERE '.
			 '"Superseded" IS NULL AND "����" = ' . $pid);
		$curr = mx_db_fetch_single($db, $stmt);
		$rid = $curr['ObjectID'];

		$v = array($rid, $eid, 1);
		$stmt = ('INSERT INTO "����ô�������ǡ���" (' .
			 '"����ô������", "����", "ô�����") VALUES (' .
			 implode(', ', $v) . ')');
		if (!pg_query($db, $stmt)) {
			$this->err("�桼����Ͽ�Ǥ��ޤ���(5)");
			return 'failure';
		}

		$cookie = mx_random_cookie(24, $d['email']);

		$v = array($eid,
			   $pid,
			   mx_db_sql_quote($d['handle']),
			   mx_db_sql_quote($d['email']),
			   mx_db_sql_quote($d['anonymous']),
			   mx_db_sql_quote($cookie));
		$stmt = ('INSERT INTO mykarte_users (' .
			 'mx_employee, mx_patient, handle, ' .
			 'email, anonymous, confirm_cookie' .
			 ') VALUES (' .
			 implode(', ', $v) . ')');
		if (!pg_query($db, $stmt)) {
			$this->err("�����꡼��̾�����Ǥ˻Ȥ��Ƥ��ޤ�(2)");
			return 'failure';
		}
		if (! pg_query($db, 'commit')) {
			$this->err(pg_last_error($db));
			return 'failure';
		}
		$this->confirm = array('email' => $d['email'],
				       'eid' => $eid,
				       'handle' => $d['handle'],
				       'cookie' => $cookie);
		return 'ok';
	}

	// this is inside "begin"
	function try_commit(&$db) {
		$this->change_nature = 'create';
		return $this->create_one($db, $this->data);
	}

	function _validate($force=NULL) {
		$status = simple_object_edit::_validate($force);
		if (!email_valid($this->data['email'])) {
			$this->err("�᡼�륢�ɥ쥹�������Ǥ�\n");
			$status = 'bad';
		}
		return $status;
	}

	function send_confirm_mail() {
		global $_mx_site_url;

		$email = $this->confirm['email'];
		$eid = $this->confirm['eid'];
		$cookie = $this->confirm['cookie'];

		$target = $_mx_site_url . "mykarte/confirm.php";
		$subject = 'MyKARTE registration';
		$msg = (
"���Υ᡼��ϡ����ʤ��� MyKARTE �桼����Ͽ��\n" .
$_mx_site_url . "mykarte/registration.php ����\n".
"�Ԥʤä��Τ��ǧ���뤿��ˤ����ꤷ�Ƥ��ޤ���\n\n".
"  $target/$eid/$cookie\n\n" .
"�򥢥��������ơ��桼����Ͽ��λ���Ƥ���������\n\n" .
"�������꤬�ʤ����ˤϡ����ʤ��ˤʤꤹ�ޤ����Ȥ���ï����\n".
"���ʤ��Υ᡼�륢�ɥ쥹�򾡼�˻Ȥä���Ͽ�����Τ����Τ�ޤ���\n".
"��� URL �򥢥���������ޤǤ���Ͽ���줿 MyKARTE �桼����\n".
"�Ȥ��ޤ��󤫤顢���ξ��ϵ��ˤ������Υ᡼���\n".
"̵�뤷�Ʋ����äƷ빽�Ǥ���\n");

		mx_send_mail($email, $subject, $msg);
	}
}

?>
