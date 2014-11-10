<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';

function __lib_u_reception_status_cfg(&$cfg)
{
	$ledcols = array(
		array('Column' => '��Ͽ����',
		      'Draw' => 'static',
		      'Option' => array('validate' => 'nonnull',
					'to-seconds' => 0)),
		array('Column' => '����',
		      'Draw' => 'dbenum',
		      'DBEnum' => array('���մ��Ծ���', '���մ��Ծ���')),
		);

	$cols = array();
	foreach ($ledcols as $led) {
		if (is_array($led) && $led['Column'])
			$led = $led['Column'];
		$cols[] = $led;
	}
	$cols[] = '����';

	$default_cfg = array(
		'TABLE' => '���մ��Ծ���',
		'LCOLS' => $ledcols,
		'DCOLS' => $ledcols,
		'ECOLS' => $ledcols,
		'COLS' => $cols,
		'DEFAULT_SORT' => '��Ͽ����',
		);
	$cfg = array_merge($default_cfg, $cfg);
}

class list_of_reception_status extends list_of_ppa_objects {
	var $default_row_per_page = 4;
	function list_of_reception_status($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_reception_status_cfg(&$cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
	function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			$paging_orders[] = (($col == '��Ͽ����') ||
					    ($col == 'ObjectID'));
		}
		return $paging_orders;
	}
}

class reception_status_display extends simple_object_display {
	function reception_status_display($prefix, $cfg=NULL) {
		if (is_null($cfg))
			$cfg = array();
		__lib_u_reception_status_cfg(&$cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class reception_status_edit extends simple_object_ppa_edit {
	function reception_status_edit($prefix, $cfg=NULL) {
		$this->last_state = NULL;
		if (is_null($cfg))
			$cfg = array();
		__lib_u_reception_status_cfg(&$cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}
	function anew_tweak($orig_id) {
		$stmt = <<<SQL
	SELECT CS."����"
	FROM "���մ��Ծ���" AS CS
	WHERE (CURRENT_DATE <= CS."��Ͽ����") AND
	(CS."��Ͽ����" < (CURRENT_DATE + INTERVAL '1 DAY')) AND
	(CS."ObjectID" = (SELECT MAX("ObjectID")
			  FROM "���մ��Ծ���"
			  WHERE "����" = %s AND
			  (CS."��Ͽ����" < (CURRENT_DATE + INTERVAL '1 DAY'))))
SQL;
		simple_object_ppa_edit::anew_tweak($orig_id);
		if (is_null($orig_id)) {
			$p = $this->so_config['Patient_ObjectID'];
			$stmt = sprintf($stmt, mx_db_sql_quote($p));
			$db = mx_db_connect();
			$it = mx_db_fetch_single($db, $stmt);
			$spec = mx_dbenum('���մ��Ծ���','���մ��Ծ���');
			$repertoire = array();
			foreach (explode("\n", $spec['�����']) as $r) {
				$r = trim($r);
				if ($r != '')
					$repertoire[] = $r;
			}
			$next = 0;
			if (!is_null($it)) {
				$it = $it['����'];
				$this->last_state = $it;
				for ($i = 0; $i < count($repertoire); $i++) {
					if ($repertoire[$i] == $it &&
					    $i + 1 < count($repertoire)) {
						$next = $i + 1;
						break;
					}
				}
			}
			$this->data['����'] = $repertoire[$next];
		}
		$this->data['��Ͽ����'] = mx_now_string();
	}
}

function reception_status_adjust($patient_objectid, $status)
{
	$it = new reception_status_edit('faux-');
	$it->so_config['Patient_ObjectID'] = $patient_objectid;
	$it->anew(NULL);
	if ($it->last_state != $status) {
		$it->data['����'] = $status;
		$it->commit();
	}
}

?>
