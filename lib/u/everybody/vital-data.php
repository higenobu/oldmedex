<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_everybody_vital_data_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'バイタルデータ表',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '日時',
      'LCOLS' => array('日時', '記録者名'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名",
(F."日付" + F."測定時刻") AS "日時"
FROM "バイタルデータ表" AS F
LEFT JOIN "職員台帳" AS E
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
     'ページ一' => array
     (
      array("測定時刻", "測定時刻", "timestamp", $no_ime),

      array("身長", "身長", "text", $no_ime),
      array("体重", "体重", "text", $no_ime),

      array("体温", "体温", "text", $no_ime),
      array("血圧(上)", "血圧(上)", "text", $no_ime),
      array("血圧(下)", "血圧(下)", "text", $no_ime),
      array("脈拍", "脈拍", "text", $no_ime),
      array("呼吸数", "呼吸数", "text", $no_ime),

      array("主食摂取量", "主食摂取量", "text", $no_ime),
      array("副食摂取量", "副食摂取量", "text", $no_ime),
      array("便(回数)", "便(回数)", "text", $no_ime),
      array("便(性状)", "便(性状)", "text"),
      array("尿(回数)", "尿(回数)", "text", $no_ime),
      array("尿(性状)", "尿(性状)", "text"),

      array("コメント", "コメント", "textarea"),
      ),

     );

  $cfg['ECOLS'] = array(array('Column' => '日付',
			      'Draw' => 'date',
			      'Option' =>
			      array_merge($no_ime,
					  array('validate' => 'date'))));
  $cfg['DCOLS'] = array('日付');
  $cfg['ICOLS'] = array('日付', '患者');
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
  $cfg['DCOLS'][] = '記録者名';
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
	    if ($col == '日時' || $col == 'ObjectID')
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
		  array("体温" => 1,
			"血圧(上)" => 1,
			"血圧(下)" => 1,
			"脈拍" => 1,
			"呼吸数" => 1,
			"主食摂取量" => 1,
			"副食摂取量" => 1,
			"便(回数)" => 1,
			"便(性状)" => 1,
			"尿(回数)" => 1,
			"尿(性状)" => 1,
			"コメント" => 1);

	  if (is_null($cfg)) $cfg = array();
	  __lib_u_everybody_vital_data_cfg(&$cfg);
	  simple_object_edit::simple_object_edit
		  ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
	$this->data['測定時刻'] = '';
	$this->data['日付'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
	  $d['患者'] = $this->so_config['Patient_ObjectID'];
	  $d["測定時刻"] = mx_format_timestamp($d["測定時刻"], 0);
  }

  function annotate_form_data(&$d) {
	if (trim($d['日付']) == '')
		$d['日付'] = mx_today_string();
	simple_object_edit::annotate_form_data($d);
	$this->annotate_row_data($d);
  }

  function _validate() {
	  $empty_means_null_and_ok = $this->empty_means_null_and_ok;

	  $bad = 0;
	  if ($st = mx_db_validate_date($this->data['日付'])) {
		  $this->err("(日付): $st\n");
		  $bad++;
	  }
	  if ($st = mx_db_validate_time($this->data['測定時刻'])) {
		  $this->err("(測定時刻): $st\n");
		  $bad++;
	  }

	  foreach (array("脈拍", "呼吸数", "便(回数)", "尿(回数)") as $c) {
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

	  foreach (array("体温", "血圧(上)", "血圧(下)",
			 "主食摂取量", "副食摂取量") as $c) {
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

	  foreach (array("身長", "体重") as $c) {
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
		$limit[] = 'V."日付" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'V."日付" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;

	$limit[] = 'V."Superseded" IS NULL';

	/* Grab column definitions from the main application */
	$cfg = array();
	__lib_u_everybody_vital_data_cfg($cfg);
	_lib_so_prepare_config_ledcols(&$config['DCOLS']);

	/* Interesting columns */
	$primary = '体温';
	$columns = array('血圧(上)',
			 '血圧(下)',
			 '脈拍',
			 '呼吸数',
			 '身長',
			 '体重');
	$namemap = array();

	$them = array('V."ObjectID"', 'V."日付"',
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
FROM "バイタルデータ表" AS V
WHERE V."患者" = ' . mx_db_sql_quote($p_oid);
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
		$text = sprintf("(バイタル) 体温 %s", $e[$primary]);

		$fuller = '';
		$vb_array = array();
		foreach ($namemap as $c => $t) {
			if (trim($e[$c]) == '')
				continue;
			$fuller .= htmlspecialchars("$t: $e[$c]") . "<br />";
			$vb_array[] = mx_form_escape_key(array($t,$e[$c]));
		}
		$result[] = array('timestamp' => $e['日付'],
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