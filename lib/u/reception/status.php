<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';

function __lib_u_reception_status_cfg(&$cfg)
{
	$ledcols = array(
		array('Column' => '記録日時',
		      'Draw' => 'static',
		      'Option' => array('validate' => 'nonnull',
					'to-seconds' => 0)),
		array('Column' => '状態',
		      'Draw' => 'dbenum',
		      'DBEnum' => array('受付患者状態', '受付患者状態')),
		);

	$cols = array();
	foreach ($ledcols as $led) {
		if (is_array($led) && $led['Column'])
			$led = $led['Column'];
		$cols[] = $led;
	}
	$cols[] = '患者';

	$default_cfg = array(
		'TABLE' => '受付患者状態',
		'LCOLS' => $ledcols,
		'DCOLS' => $ledcols,
		'ECOLS' => $ledcols,
		'COLS' => $cols,
		'DEFAULT_SORT' => '記録日時',
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
			$paging_orders[] = (($col == '記録日時') ||
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
	SELECT CS."状態"
	FROM "受付患者状態" AS CS
	WHERE (CURRENT_DATE <= CS."記録日時") AND
	(CS."記録日時" < (CURRENT_DATE + INTERVAL '1 DAY')) AND
	(CS."ObjectID" = (SELECT MAX("ObjectID")
			  FROM "受付患者状態"
			  WHERE "患者" = %s AND
			  (CS."記録日時" < (CURRENT_DATE + INTERVAL '1 DAY'))))
SQL;
		simple_object_ppa_edit::anew_tweak($orig_id);
		if (is_null($orig_id)) {
			$p = $this->so_config['Patient_ObjectID'];
			$stmt = sprintf($stmt, mx_db_sql_quote($p));
			$db = mx_db_connect();
			$it = mx_db_fetch_single($db, $stmt);
			$spec = mx_dbenum('受付患者状態','受付患者状態');
			$repertoire = array();
			foreach (explode("\n", $spec['選択肢']) as $r) {
				$r = trim($r);
				if ($r != '')
					$repertoire[] = $r;
			}
			$next = 0;
			if (!is_null($it)) {
				$it = $it['状態'];
				$this->last_state = $it;
				for ($i = 0; $i < count($repertoire); $i++) {
					if ($repertoire[$i] == $it &&
					    $i + 1 < count($repertoire)) {
						$next = $i + 1;
						break;
					}
				}
			}
			$this->data['状態'] = $repertoire[$next];
		}
		$this->data['記録日時'] = mx_now_string();
	}
}

function reception_status_adjust($patient_objectid, $status)
{
	$it = new reception_status_edit('faux-');
	$it->so_config['Patient_ObjectID'] = $patient_objectid;
	$it->anew(NULL);
	if ($it->last_state != $status) {
		$it->data['状態'] = $status;
		$it->commit();
	}
}

?>
