<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

/*
 * This is NOT a way to write a SOE class, but is a quick hack to get
 * something that supports duplicate() method via duck typing.  It was
 * done without consideration for people who may want to eventually
 * rewrite Rx application using the framework some day.
 */
class rx_order_edit /* extends simple_object_edit */ {

	var $parent_table = "���޽����";
	var $child_table = "���޽��������";
	var $link_in_child = "���޽����";

	function rx_order_edit($dummy) {
	}

	function _g_($db, $stmt, $all=0) {

		print "<!-- $stmt -->\n";

		$data = pg_query($db, $stmt);
		if (!$data)
			return NULL; /* Ugh */
		$data = pg_fetch_all($data);
		if (!$data)
			return NULL;
		if (!$all) {
			if (!is_array($data) || count($data) != 1)
				return NULL;
			return $data[0];
		}
		return $data;
	}

	function _i_($db, $table, $data) {

		$table = mx_db_sql_quote_name($table);

		$cols = array();
		$vals = array();
		foreach ($data as $col => $val) {
			$cols[] = mx_db_sql_quote_name($col);
			$vals[] = mx_db_sql_quote($val);
		}

		$stmt = "INSERT INTO $table (" .
			implode(",\n\t", $cols) . ")\nVALUES (" .
			implode(",\n\t", $vals) . ")";

		print "<!-- $stmt -->\n";

		pg_query($db, $stmt);
	}


	function duplicate($id, $attr) {
		$db = mx_db_connect();

		$parent_table = $this->parent_table;
		$child_table = $this->child_table;
		$link_in_child = $this->link_in_child;

		$stmt = <<<SQL
SELECT * FROM "$parent_table" WHERE "ObjectID" = $id
SQL;
		$data = $this->_g_($db, $stmt);
		if (!$data)
			return NULL; /* Ugh */

		$stmt = <<<SQL
SELECT nextval('"{$parent_table}_ID_seq"') AS it
SQL;
		$newid = $this->_g_($db, $stmt);
		if (!$newid)
			return NULL;
		$newid = $newid['it'];

		$d = $attr['DuplicateDate'];
		$data['����ǯ����'] = $d;
		$data['����������'] = $d;
		$data['ObjectID'] = $newid;
		$data['ID'] = $newid;
		foreach (array('�����', '��߰�', 'Ĵ�����޻�', 'Ĵ��ǯ����',
			       "�ǸϿ��", "���޵�Ͽ��", "PDF", "Superseded"
			       ) as $nullify)
			$data[$nullify] = NULL;

		$this->_i_($db, $parent_table, $data);

		$stmt = <<<SQL
SELECT * FROM "$child_table" WHERE "$link_in_child" = $id
ORDER BY "ObjectID"
SQL;
		$child = $this->_g_($db, $stmt, 'all');
		if (!$child)
			return NULL; /* Ugh */

		foreach ($child as $c) {
			unset($c['ObjectID']);
			$c[$link_in_child] = $newid;
			$this->_i_($db, $child_table, $c);
		}

		mx_kick_claim_if_by_poid($db, $data['����'], $d);
	}

}

class injection_order_edit extends rx_order_edit {
	var $parent_table = "��ͽ����";
	var $child_table = "��ͽ��������";
	var $link_in_child = "��ͽ����";
}

/*
 * Return an SQL boolean snippet that tells if given patient P has
 * an order within that range of dates (or the default one)
 */

function pharma_module_index_info_patient_sql($date_from, $date_to, $options, $which)
{
	$table = ($which == 'r') ? '���޽����' : '��ͽ����';

	$limit = array();

	if (mx_check_option('LimitWithRxRange', $options)) {
		if (!is_null($date_from))
			$limit[] = 'K."����������" + ' .
	    'CAST((CAST(K."����" AS VARCHAR) || \' day\') AS INTERVAL) > ' .
				mx_db_sql_quote($date_from);
		if (!is_null($date_to))
			$limit[] = 'K."����������" <= ' .
				mx_db_sql_quote($date_to);
	} else {
		if (!is_null($date_from))
			$limit[] = 'K."����ǯ����" >= ' .
				mx_db_sql_quote($date_from);
		if (!is_null($date_to))
			$limit[] = 'K."����ǯ����" <= '.
				mx_db_sql_quote($date_to);
	}
	$limit[] = 'K."Superseded" IS NULL';
	$limit[] = 'K."�����" IS NULL';
	$limit = implode(' AND ', $limit);

	return <<<SQL
		EXISTS (SELECT 1 FROM "$table" AS K
			WHERE K."����" = P."ObjectID"
			AND $limit)
SQL;
}

function rx_module_index_info_patient_sql($date_from, $date_to, $options=NULL)
{
	return pharma_module_index_info_patient_sql($date_from, $date_to,
						    $options, 'r');
}

function injection_module_index_info_patient_sql($date_from, $date_to, $options=NULL)
{
	return pharma_module_index_info_patient_sql($date_from, $date_to,
						    $options, 'i');
}
?>
