<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_doctor_diseasepick_dps_cfg = array
('COLS' => array('病名表記', "病名表記カナ", 'ICD10'),
 'ALLOW_SORT' => 1,
 'TABLE' => 'Medis病名マスター',
 'ENABLE_QBE' => array(array('Column' => '病名表記',
			     'Draw' => 'text',
			     'Singleton' => 1),
		       array('Column' => 'カナ',
			     'Draw' => 'text',
			     'Compare' => '"病名表記カナ"',
			     'NormalizeCompareKey' => 'AC',
			     'IncSearch' => 'disease',
			     'Singleton' => 1),
		       array('Column' => 'ICD10',
			     'Draw' => 'text',
			     'Singleton' => 1),
		       array('Column' => '当院採用',
			     'Compare' => '"当院採用"',
			     'Draw' => 'enum',
			     'Enum' => array('F' => '頻出分のみ',
					     'YF' => '採用分のみ',
					     '' => 'マスタ全て'),
			     'CompareMethod' => 'enum_single_char',
			     'Singleton' => 1) ),
 'DEFAULT_QBE' => array(array('当院採用', 'YF')),
 'LIST_IDS' => array('ObjectID', '病名表記', 'ICD10'),
 );

class diseasepick extends list_of_simple_objects {
  function diseasepick($prefix) {
    global $_lib_u_doctor_diseasepick_dps_cfg;
    $cfg = $_lib_u_doctor_diseasepick_dps_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $cfg);
  }

  function draw_no_data_message() {
    print '<br />該当する病名がありません。';
  }

  function qbe_limit_too_vague() {
    $has_specific = 0;
    foreach ($this->qbe_current as $qbe) {
	    if ($qbe[0] == '当院採用') {
		    if ($qbe[1] == 'F')
			    return 0;
	    }
	    else if ($qbe[1] != '')
		    return 0;
    }
    return 1;
  }

  function enum($name, $value) {
    $value = trim($value);

    if ($value == '')
	    $metoo = '';
    else
	    $metoo = 'OR "ObjectID" = ' . mx_db_sql_quote($value);

    $db = mx_db_connect();
    $stmt = <<<SQL
SELECT "ObjectID", "病名表記" FROM "Medis病名マスター"
WHERE "Superseded" IS NULL AND ("当院採用" = 'F' $metoo)
SQL;
    $all = mx_db_fetch_all($db, $stmt);

    $a = array('' => mx_empty_field_mark());
    $a[' '] = 'その他';
    foreach ($all as $elem) {
	$l = trim($elem['病名表記']);
	$v = $elem['ObjectID'];
	$a[$v] = $l;
    }
    /* The 1st entry (Others) activates subpick */
    return array('Enum' => $a, 'Activate' => 1);
  }

}

$_lib_u_doctor_dismodpick_cfg = array
('COLS' => array('修飾語表記', "修飾語表記カナ"),
 'ALLOW_SORT' => 1,
 'ENABLE_QBE' => array(array('Column' => '修飾語表記',
			     'Draw' => 'text',
			     'Singleton' => 1),
		       array('Column' => 'カナ',
			     'Draw' => 'text',
			     'Compare' => '"修飾語表記カナ"',
			     'NormalizeCompareKey' => 'AC',
			     'Singleton' => 1),
		       array('Column' => '当院採用',
			     'Compare' => '"当院採用"',
			     'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array(' ' => 'マスタ全て',
					     'F' => '頻出分のみ',
					     'YF' => '採用分のみ',
					     ),
			     'CompareMethod' => 'enum_single_char'),
		       ),
 'DEFAULT_QBE' => array(array('当院採用', 'YF')),
 'LIST_IDS' => array('ObjectID', '修飾語表記'),
 );

class dismodpick_base extends list_of_simple_objects {
	function dismodpick_base($prefix) {
		global $_lib_u_doctor_dismodpick_cfg;
		$cfg = $_lib_u_doctor_dismodpick_cfg;
		if ($this->prepost == 'pre') {
			$cfg['TABLE'] = "Medis病名接頭語";
			$cfg['ENABLE_QBE'][1]['IncSearch'] = 'dismodpick_pre';
		}
		else {
			$cfg['TABLE'] = "Medis病名接尾語";
			$cfg['ENABLE_QBE'][1]['IncSearch'] = 'dismodpick_post';
		}
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function qbe_limit_too_vague() {
		foreach ($this->qbe_current as $qbe) {
			if ($qbe[1] != '')
				return 0;
		}
		return 1;
	}

	function enum($name, $value) {
		$value = trim($value);

		if ($value == '')
			$metoo = '';
		else
			$metoo = 'OR "ObjectID" = ' . mx_db_sql_quote($value);

		$db = mx_db_connect();
		$table = $this->table;
		$stmt = <<<SQL
SELECT "ObjectID", "修飾語表記" FROM "$table"
WHERE "Superseded" IS NULL AND ("当院採用" = 'F' $metoo)
SQL;
		$all = mx_db_fetch_all($db, $stmt);

		$a = array('' => mx_empty_field_mark());
		$a[' '] = 'その他';
		foreach ($all as $elem) {
			$l = trim($elem['修飾語表記']);
			$v = $elem['ObjectID'];
			$a[$v] = $l;
		}
		/* The 1st entry (Others) activates subpick */
		return array('Enum' => $a, 'Activate' => 1);
	}
}

class dismodpick_pre extends dismodpick_base {
	var $prepost = 'pre';
	var $table = "Medis病名接頭語";
}

class dismodpick_post extends dismodpick_base {
	var $prepost = 'post';
	var $table = "Medis病名接尾語";
}

?>
