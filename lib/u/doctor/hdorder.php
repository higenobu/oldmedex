<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';

function __lib_u_doctor_hdorder_cfg(&$cfg)
{
	$cfg = array_merge
		(array(
			 'TABLE' => 'Ʃ�ϥ�����',

			 'LCOLS' => array(
				 array('Column' => '������������'),
				 array('Column' => '��������λ��'),
				 array('Column' => '����',
				       'Draw' => 'daysoftheweek'),
				 array('Column' => '������',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '������')),
				 array('Column' => "Ʃ����ˡ",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', 'Ʃ����ˡ')),
				 ),

			 'ALLOW_SORT' => array('������������' =>
					       array('������������' =>
						     '"������������"'),
					       ),

			 'DEFAULT_SORT' => '������������',

			 'ECOLS' => array(
				 array('Column' => '����',
				       'Draw' => 'daysoftheweek',
				       'Option' => array('dow' =>
							 array('��', '��', '��', '��', '��', '��'),
							 'dow-shorthand' =>
							 array(array('Label' => '', 'Value' => 'NNNNNN'),
array('Label' => '����', 'Value' => 'YNYNYN'),
							       array('Label' => '������', 'Value' => 'NYNYNY'),
							       ))),
				 array('Column' => '������������',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
				 array('Column' => '��������λ��',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),
				 array('Column' => '������',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '������')),
				 array('Column' => '������',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '������')),
				 array('Column' => '��շ�',
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       'A' => 'A',
						       'B' => 'B',
						       'AB' => 'AB',
						       'O' => 'O'),
				       'Option' => array('validate' =>
							 'nonnull')),
				 array('Column' => 'RH',
				       'Draw' => 'enum',
				       'Enum' => array('+' => '+',
						       '-' => '-'),
				       'Option' => array('validate' =>
							 'nonnull')),
				 array('Column' => '�֥�åɥ�������',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '�֥�åɥ�������')),
				 array('Column' => "HD����",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', 'HD����')),
				 array('Column' => 'QB��',
				       'Label' => 'QB�� (mL/min)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "Ʃ����ˡ",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', 'Ʃ����ˡ')),
				 array('Column' => "Ʃ�ϱ�",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', 'Ʃ�ϱ�')),
				 array('Column' => "���ŸǺ�",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '���ŸǺ�')),
				 array('Column' => "���������",
				       'Label' => '��������� (u)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "��³��",
				       'Label' => '��³�� (u/h)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "�ɥ饤��������",
				       'Label' => '�ɥ饤�������� (kg)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "�������",
				       'Label' => '��������� (L)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'number,nonnull',
							 'validate-min' => 0,
							 'validate-precision' => 2)),
				 array('Column' => "UFR",
				       'Label' => 'UFR (L/H)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'number,nonnull',
							 'validate-min' => 0,
							 'validate-precision' => 2)),
				 array('Column' => "�ղ�",
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'number,nonnull',
							 'validate-min' => 20,
							 'validate-max' => 45,
							 'validate-precision' => 1)),
				 array('Column' => "�����ֹ�",
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'posint,nonnull')),
				 array('Column' => "�������饤����",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD',
							 '�������饤����'),
				       'Option' => array('validate' =>
							 'nonnull')),
				 array('Column' => "����",
				       'Draw' => 'textarea')
				 ),

			 ),
		 $cfg);

	$cfg['DCOLS'] = $cfg['ECOLS'];
	$__c = array();
	foreach ($cfg['ECOLS'] as $elem) {
		$__c[] = $elem['Column'];
	}
	$__c[] = '����';
	$__c[] = '����';
	$cfg['COLS'] = $__c;
}

class list_of_hdorders extends list_of_ppa_objects {

	var $debug = 1;

	function list_of_hdorders($prefix, $config=NULL) {
		__lib_u_doctor_hdorder_cfg(&$config);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $config);
	}

	function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			$order = 0;
			switch ($col) {
			case '������������':
			case '��������λ��':
				$order = 1;
				break;
			}
			$paging_orders[] = $order;
		}
		return $paging_orders;
	}

}

class hdorder_display extends simple_object_display {

	var $debug = 1;

	function hdorder_display($prefix, $config=NULL) {
		__lib_u_doctor_hdorder_cfg(&$config);
		simple_object_display::simple_object_display($prefix, $config);
	}

}

class hdorder_edit extends simple_object_edit {

	var $debug = 1;

	function hdorder_edit($prefix, $config=NULL) {
		__lib_u_doctor_hdorder_cfg(&$config);
		simple_object_edit::simple_object_edit($prefix, $config);
	}

	function anew_tweak_infection($data) {
		$x = array();
		foreach ($data as $d) {
			if ($d['°����'] == '+')
				$x[$d['̾��']] = 1;
		}
		$value = '';
		$infix = '';
		$spec = mx_dbenum('HD', '������');
		foreach (explode("\n", $spec['�����']) as $i) {
			if ($i == '')
				continue;
			if (array_key_exists($i, $x)) {
				$value = $value . $infix . $i;
				$infix = '|';
			}
		}
		$this->data['������'] = $value;
	}

	function anew_tweak($orig_id) {
		$this->data['������������'] = mx_today_string();
		$this->data['��������λ��'] = NULL;

		$p = $this->so_config['Patient_ObjectID'];
		$in = _lib_pp_attr_find($p, "������");
		if ($in && is_array($in)) {
			$this->anew_tweak_infection($in);
		}
	}

	function _validate($force=NULL) {

		$bad = 0;
		$d =& $this->data;
		$v = $d['����'];
		if (strstr($v, 'Y') == '') {
			$bad++;
			$this->err("(����): ���ǤϤ����ޤ���\n");
		}
		$bad += (simple_object_edit::_validate($force) != 'ok');

		if ($bad)
			return '';
		return 'ok';

	}

	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['����'] = $this->so_config['Patient_ObjectID'];
		$this->data['����'] = mx_now_string();
		return simple_object_edit::commit($force);
	}

}

function __lib_u_doctor_hdorder_stmt_head() {
	return <<<SQL
	SELECT H."ObjectID",
	    P."��", P."̾", P."����ID",
	    H."����",
	    H."������������", H."��������λ��", H."������",
	    H."������", H."��շ�", H."RH",
	    H."�֥�åɥ�������", H."HD����", H."QB��",
	    H."Ʃ����ˡ",H."Ʃ�ϱ�", H."���ŸǺ�",
	    H."���������", H."��³��", H."�ɥ饤��������",
	    H."�������饤����"
	FROM "Ʃ�ϥ�����" AS H
	JOIN "������Ģ" AS P ON P."ObjectID" = H."����"
SQL;

}

function hdorder_list_orders_by_oid($orders) {

	/* Resist injection attacks */
	$o = array();
	foreach (explode(',', $orders) as $oid) {
		$oid = trim($oid);
		$m = array();
		if (preg_match('/^\d+$/', $oid, &$m))
			$o[] = $oid;
	}
	if (count($o) == 0)
		return array();

	$orders = implode(',', $o);

	$stmt = __lib_u_doctor_hdorder_stmt_head() . <<<SQL
	WHERE H."Superseded" IS NULL AND
	H."ObjectID" IN ($orders)
	ORDER BY P."����ID"
SQL;
	print "<!-- $stmt\n -->\n";
	$db = mx_db_connect();

	return pg_fetch_all(pg_query($db, $stmt));

}

function hdorder_list_orders($date) {

	$d = array();

	$time = mx_datetime_to_unixtime("$date 00:00");
	if ($time < 0)
		return $d;
	$time = localtime($time, 1);
	$dow = $time['tm_wday']; /* 0 = Sunday */

	$dow_pattern = '';
	for ($i = 1; $i < 7; $i++) {
		$dow_pattern = $dow_pattern . (($i == $dow) ? 'Y' : '_');
	}
	/*
	 * Sunday is at the end in our schema.  It happens that the Book
	 * also says so.
	 */
	$dow_pattern .= ($dow == 0) ? 'Y' : '_';

	$stmt = __lib_u_doctor_hdorder_stmt_head() . <<<SQL
	WHERE H."Superseded" IS NULL AND
	H."������������" <= '$date' AND
	('$date' <= H."��������λ��" OR H."��������λ��" IS NULL) AND
	"����" LIKE '$dow_pattern'
	ORDER BY P."����ID"
SQL;
	print "<!-- $stmt\n -->\n";
	$db = mx_db_connect();

	return pg_fetch_all(pg_query($db, $stmt));

}

function hdorder_show_table_order($l, $orders=NULL, $linkalike=NULL, $omit_selection=NULL) {

	$cnt = count($l);

	print "<div>\n";
	print "<table class=\"listofstuff\">\n";
	print "<thead>";
	print "<tr>";
	if (!$omit_selection)
		print "<th>&nbsp;</th>";
	print "<th>����ID</th>";
	print "<th>����</th>";
	print "<th>������������</th>";
	print "<th>��������λ��</th>";
	print "<th>��շ�</th>";
	print "<th>Ʃ����ˡ</th>";
	print "</tr>\n";
	print "</thead><tbody>\n";
	for ($i = 0; $i < $cnt; $i++) {
		$evenodd = ($i % 2) ? 'o' : 'e';
		$d = $l[$i];
		$order = sprintf("HDOrderPicked%d", $d['ObjectID']);
		if (is_null($orders))
			$set = 1;
		else
			$set = in_array($d['ObjectID'], $orders);
		if (is_null($linkalike))
			$button = NULL;
		else {
			$button = sprintf("%s-%d", $linkalike, $d['ObjectID']);
			$msg = "�������򻲾�";
		}
		print "<tr class=\"$evenodd\">";
		if (!$omit_selection) {
			print "<td>";
			print mx_formi_checkbox($order, $set);
			print "</td>";
		}
		print "<td>";
		mx_formi_linkalike(htmlspecialchars($d['����ID']),
				   $button, $msg);
		print "</td>";
		print "<td>";
		mx_formi_linkalike(htmlspecialchars($d['��'] . $d['̾']),
				   $button, $msg);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['������������']);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['��������λ��']);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['��շ�'] . $d['RH']);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['Ʃ����ˡ']);
		print "</td>";
		print "</tr>\n";
	}
	print "</tbody></table></div>\n";

}

?>
