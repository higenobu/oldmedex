<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_everybody_vital_data_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '�Х�����ǡ���ɽ',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '����',
      'LCOLS' => array('����', '��Ͽ��̾'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾",
(F."����" + F."¬�����") AS "����"
FROM "�Х�����ǡ���ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $no_ime = array('ime' => 'disabled', 'to-seconds' => 0);

  // List of flip-pages: db column, label, widget type
  // we do not do flippage for this one, but it is easier
  // to keep the general structure the same.
  $flippage = array
    (
     '�ڡ�����' => array
     (
      array("¬�����", "¬�����", "timestamp", $no_ime),

      array("��Ĺ", "��Ĺ", "text", $no_ime),
      array("�ν�", "�ν�", "text", $no_ime),

      array("�β�", "�β�", "text", $no_ime),
      array("�찵(��)", "�찵(��)", "text", $no_ime),
      array("�찵(��)", "�찵(��)", "text", $no_ime),
      array("̮��", "̮��", "text", $no_ime),
      array("�Ƶۿ�", "�Ƶۿ�", "text", $no_ime),

      array("�翩�ݼ���", "�翩�ݼ���", "text", $no_ime),
      array("�����ݼ���", "�����ݼ���", "text", $no_ime),
      array("��(���)", "��(���)", "text", $no_ime),
      array("��(����)", "��(����)", "text"),
      array("Ǣ(���)", "Ǣ(���)", "text", $no_ime),
      array("Ǣ(����)", "Ǣ(����)", "text"),

      array("������", "������", "textarea"),
      ),

     );

  $cfg['ECOLS'] = array(array('Column' => '����',
			      'Draw' => 'date',
			      'Option' =>
			      array_merge($no_ime,
					  array('validate' => 'date'))));
  $cfg['DCOLS'] = array('����');
  $cfg['ICOLS'] = array('����', '����');
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $c) {
      $a = array('Column' => $c[0],
		 'Label' => ($c[1] ? $c[1] : $c[0]),
		 'Draw' => $c[2]);
      if ($c[3])
	$a['Option'] = $c[3];
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      if (! is_null($c[0]))
	$cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_everybody_vital_datas extends list_of_ppa_objects {

  function list_of_everybody_vital_datas($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_vital_data_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
  
  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	    if ($col == '����' || $col == 'ObjectID')
		    $paging_orders[] = 1;
	    else
		    $paging_orders[] = 0;
    }
    return $paging_orders;
  }

}

class everybody_vital_data_display extends simple_object_display {

  function everybody_vital_data_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_vital_data_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class everybody_vital_data_edit extends simple_object_edit {

  function everybody_vital_data_edit($prefix, $cfg=NULL) {
	  $this->empty_means_null_and_ok =
		  array("�β�" => 1,
			"�찵(��)" => 1,
			"�찵(��)" => 1,
			"̮��" => 1,
			"�Ƶۿ�" => 1,
			"�翩�ݼ���" => 1,
			"�����ݼ���" => 1,
			"��(���)" => 1,
			"��(����)" => 1,
			"Ǣ(���)" => 1,
			"Ǣ(����)" => 1,
			"������" => 1);

	  if (is_null($cfg)) $cfg = array();
	  __lib_u_everybody_vital_data_cfg(&$cfg);
	  simple_object_edit::simple_object_edit
		  ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
	$this->data['¬�����'] = '';
	$this->data['����'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
	  $d['����'] = $this->so_config['Patient_ObjectID'];
	  $d["¬�����"] = mx_format_timestamp($d["¬�����"], 0);
  }

  function annotate_form_data(&$d) {
	if (trim($d['����']) == '')
		$d['����'] = mx_today_string();
	simple_object_edit::annotate_form_data($d);
	$this->annotate_row_data($d);
  }

  function _validate() {
	  $empty_means_null_and_ok = $this->empty_means_null_and_ok;

	  $bad = 0;
	  if ($st = mx_db_validate_date($this->data['����'])) {
		  $this->err("(����): $st\n");
		  $bad++;
	  }
	  if ($st = mx_db_validate_time($this->data['¬�����'])) {
		  $this->err("(¬�����): $st\n");
		  $bad++;
	  }

	  foreach (array("̮��", "�Ƶۿ�", "��(���)", "Ǣ(���)") as $c) {
		  if (array_key_exists($c, $empty_means_null_and_ok)) {
			  if ($this->data[$c] == "") {
				  $this->data[$c] = NULL;
				  continue;
			  }
		  }
		  if ($st = mx_db_validate_nnint($this->data[$c])) {
			  $this->err("($c): $st\n");
			  $bad++;
		  }
	  }

	  foreach (array("�β�", "�찵(��)", "�찵(��)",
			 "�翩�ݼ���", "�����ݼ���") as $c) {
		  if (array_key_exists($c, $empty_means_null_and_ok)) {
			  if ($this->data[$c] == "") {
				  $this->data[$c] = NULL;
				  continue;
			  }
		  }
		  if ($st = mx_db_validate_nonzero($this->data[$c])) {
			  $this->err("($c): $st\n");
			  $bad++;
		  }
	  }

	  foreach (array("��Ĺ", "�ν�") as $c) {
		  if (array_key_exists($c, $empty_means_null_and_ok)) {
			  if ($this->data[$c] == "") {
				  $this->data[$c] = NULL;
				  continue;
			  }
		  }
		  if ($st = mx_db_validate_nonzero($this->data[$c])) {
			  $this->err("($c): $st\n");
			  $bad++;
		  }
	  }

	  if ($bad == 0)
		  return 'ok';
  }

}


////////////////////////////////////////////////////////////////
/*
 * This is used by index-pt via lib/ord_module.php.
 */

function vital_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	$result = array();

	$num_limit = 0;
	if (!is_null($time_from))
		$limit[] = 'V."����" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'V."����" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;

	$limit[] = 'V."Superseded" IS NULL';

	/* Grab column definitions from the main application */
	$cfg = array();
	__lib_u_everybody_vital_data_cfg($cfg);
	_lib_so_prepare_config_ledcols(&$config['DCOLS']);

	/* Interesting columns */
	$primary = '�β�';
	$columns = array('�찵(��)',
			 '�찵(��)',
			 '̮��',
			 '�Ƶۿ�',
			 '��Ĺ',
			 '�ν�');
	$namemap = array();

	$them = array('V."ObjectID"', 'V."����"',
		      'V."' . $primary . '"');
	foreach ($columns as $c) {
		$them[] = 'V."' . $c . '"';

		foreach ($cfg['ECOLS'] as $one) {
			if ($one['Column'] == $c) {
				$namemap[$c] = $one['Label'];
				break;
			}
		}
	}
	$them = implode(", ", $them);

	$sql = "SELECT $them" . '
FROM "�Х�����ǡ���ɽ" AS V
WHERE V."����" = ' . mx_db_sql_quote($p_oid);
	if (count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}
	if ($num_limit) {
		$sql .= ' LIMIT 30';
	}

	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;
	$application = "/u/everybody/vital-data.php";

	foreach ($all as $e) {
		$url = sprintf("$application?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $e['ObjectID']);
		$text = sprintf("(�Х�����) �β� %s", $e[$primary]);

		$fuller = '';
		$vb_array = array();
		foreach ($namemap as $c => $t) {
			if (trim($e[$c]) == '')
				continue;
			$fuller .= htmlspecialchars("$t: $e[$c]") . "<br />";
			$vb_array[] = mx_form_escape_key(array($t,$e[$c]));
		}
		$result[] = array('timestamp' => $e['����'],
				  'text' => $text,
				  'fuller' => $fuller,
				  'callback_url' => $url,
				  'thumb' => NULL,
				  'value_blob' => implode('&', $vb_array),
				  'object_id' => $e['ObjectID'],
				  );
	}
	return $result;
}

?>