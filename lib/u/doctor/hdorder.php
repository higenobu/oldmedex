<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';

function __lib_u_doctor_hdorder_cfg(&$cfg)
{
	$cfg = array_merge
		(array(
			 'TABLE' => '透析オーダ',

			 'LCOLS' => array(
				 array('Column' => 'オーダ開始日'),
				 array('Column' => 'オーダ終了日'),
				 array('Column' => '曜日',
				       'Draw' => 'daysoftheweek'),
				 array('Column' => '時間帯',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '時間帯')),
				 array('Column' => "透析方法",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '透析方法')),
				 ),

			 'ALLOW_SORT' => array('オーダ開始日' =>
					       array('オーダ開始日' =>
						     '"オーダ開始日"'),
					       ),

			 'DEFAULT_SORT' => 'オーダ開始日',

			 'ECOLS' => array(
				 array('Column' => '曜日',
				       'Draw' => 'daysoftheweek',
				       'Option' => array('dow' =>
							 array('月', '火', '水', '木', '金', '土'),
							 'dow-shorthand' =>
							 array(array('Label' => '', 'Value' => 'NNNNNN'),
array('Label' => '月水金', 'Value' => 'YNYNYN'),
							       array('Label' => '火木土', 'Value' => 'NYNYNY'),
							       ))),
				 array('Column' => 'オーダ開始日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'nonnull,date')),
				 array('Column' => 'オーダ終了日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),
				 array('Column' => '時間帯',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '時間帯')),
				 array('Column' => '感染症',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '感染症')),
				 array('Column' => '血液型',
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
				 array('Column' => 'ブラッドアクセス',
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', 'ブラッドアクセス')),
				 array('Column' => "HD時間",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', 'HD時間')),
				 array('Column' => 'QB量',
				       'Label' => 'QB量 (mL/min)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "透析方法",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '透析方法')),
				 array('Column' => "透析液",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '透析液')),
				 array('Column' => "抗凝固剤",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD', '抗凝固剤')),
				 array('Column' => "初回注入量",
				       'Label' => '初回注入量 (u)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "持続量",
				       'Label' => '持続量 (u/h)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "ドライウェイト",
				       'Label' => 'ドライウェイト (kg)',
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'nonnull,number')),
				 array('Column' => "食事回収",
				       'Label' => '食事・回収 (L)',
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
				 array('Column' => "液温",
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'number,nonnull',
							 'validate-min' => 20,
							 'validate-max' => 45,
							 'validate-precision' => 1)),
				 array('Column' => "装置番号",
				       'Draw' => 'text',
				       'Option' => array('validate' =>
							 'posint,nonnull')),
				 array('Column' => "ダイアライザー",
				       'Draw' => 'dbenum',
				       'DBEnum' => array('HD',
							 'ダイアライザー'),
				       'Option' => array('validate' =>
							 'nonnull')),
				 array('Column' => "備考",
				       'Draw' => 'textarea')
				 ),

			 ),
		 $cfg);

	$cfg['DCOLS'] = $cfg['ECOLS'];
	$__c = array();
	foreach ($cfg['ECOLS'] as $elem) {
		$__c[] = $elem['Column'];
	}
	$__c[] = '日付';
	$__c[] = '患者';
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
			case 'オーダ開始日':
			case 'オーダ終了日':
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
			if ($d['属性値'] == '+')
				$x[$d['名称']] = 1;
		}
		$value = '';
		$infix = '';
		$spec = mx_dbenum('HD', '感染症');
		foreach (explode("\n", $spec['選択肢']) as $i) {
			if ($i == '')
				continue;
			if (array_key_exists($i, $x)) {
				$value = $value . $infix . $i;
				$infix = '|';
			}
		}
		$this->data['感染症'] = $value;
	}

	function anew_tweak($orig_id) {
		$this->data['オーダ開始日'] = mx_today_string();
		$this->data['オーダ終了日'] = NULL;

		$p = $this->so_config['Patient_ObjectID'];
		$in = _lib_pp_attr_find($p, "感染症");
		if ($in && is_array($in)) {
			$this->anew_tweak_infection($in);
		}
	}

	function _validate($force=NULL) {

		$bad = 0;
		$d =& $this->data;
		$v = $d['曜日'];
		if (strstr($v, 'Y') == '') {
			$bad++;
			$this->err("(曜日): 空ではいけません\n");
		}
		$bad += (simple_object_edit::_validate($force) != 'ok');

		if ($bad)
			return '';
		return 'ok';

	}

	/* could inherit from simple_object_ppa_edit */
	function commit($force=NULL) {
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
		$this->data['日付'] = mx_now_string();
		return simple_object_edit::commit($force);
	}

}

function __lib_u_doctor_hdorder_stmt_head() {
	return <<<SQL
	SELECT H."ObjectID",
	    P."姓", P."名", P."患者ID",
	    H."曜日",
	    H."オーダ開始日", H."オーダ終了日", H."時間帯",
	    H."感染症", H."血液型", H."RH",
	    H."ブラッドアクセス", H."HD時間", H."QB量",
	    H."透析方法",H."透析液", H."抗凝固剤",
	    H."初回注入量", H."持続量", H."ドライウェイト",
	    H."ダイアライザー"
	FROM "透析オーダ" AS H
	JOIN "患者台帳" AS P ON P."ObjectID" = H."患者"
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
	ORDER BY P."患者ID"
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
	H."オーダ開始日" <= '$date' AND
	('$date' <= H."オーダ終了日" OR H."オーダ終了日" IS NULL) AND
	"曜日" LIKE '$dow_pattern'
	ORDER BY P."患者ID"
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
	print "<th>患者ID</th>";
	print "<th>患者</th>";
	print "<th>オーダ開始日</th>";
	print "<th>オーダ終了日</th>";
	print "<th>血液型</th>";
	print "<th>透析方法</th>";
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
			$msg = "オーダを参照";
		}
		print "<tr class=\"$evenodd\">";
		if (!$omit_selection) {
			print "<td>";
			print mx_formi_checkbox($order, $set);
			print "</td>";
		}
		print "<td>";
		mx_formi_linkalike(htmlspecialchars($d['患者ID']),
				   $button, $msg);
		print "</td>";
		print "<td>";
		mx_formi_linkalike(htmlspecialchars($d['姓'] . $d['名']),
				   $button, $msg);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['オーダ開始日']);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['オーダ終了日']);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['血液型'] . $d['RH']);
		print "</td>";
		print "<td>";
		print htmlspecialchars($d['透析方法']);
		print "</td>";
		print "</tr>\n";
	}
	print "</tbody></table></div>\n";

}

?>
