<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/therapist/norder-2.php';

$_lib_u_therapist_execution_code_enum = array
('180022110' => '理学療法（Ⅰ）個別',
 '180022210' => '理学療法（Ⅰ）集団',
 '180023610' => '作業療法（Ⅰ）個別',
 '180023710' => '作業療法（Ⅰ）集団',
 '180024810' => '言語聴覚療法（Ⅰ）個別',
 '180024910' => '言語聴覚療法（Ⅰ）集団');

function __lib_u_therapist_nexec_list_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'リハ実施記録',
      'ALLOW_SORT' => array
      ('ObjectID' => array('実施記録ID' => 'X."ObjectID"'),
       '日付' => array('日付' => 'X."日付"'),
       '実施療法士' => array('実施療法士' => '(T."姓" || T."名")')),
      'DEFAULT_SORT' => '日付',
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => '実施記録ID'),
		       '日付', '実施療法士'),
      'ENABLE_QBE' =>
      array(array('Column' => '日付', 'Compare' => 'X."日付"',
		  'Draw' => 'text'),
	    array('Column' => '実施療法士',
		  'Compare' => '(T."姓" || T."名")',
		  'Draw' => 'text')),
      'UNIQ_ID' => 'X."ObjectID"',
      ));

  $stmt_head = '
SELECT X."ObjectID",
       X."日付",
       (T."姓" || T."名") as "実施療法士"
FROM "リハ実施記録" as X
JOIN "リハ処方箋" as RX ON X."リハ処方箋" = RX."ID"
LEFT JOIN "職員台帳" as T ON T."ObjectID" = X."実施療法士"
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';
}

class list_of_rehab_nexecs extends list_of_simple_objects {

  var $default_row_per_page = 4;
  var $debug = 1;

  function list_of_rehab_nexecs($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    $this->loo = new list_of_rehab_norders($prefix . 'loo-', $cfg);
    $this->sod = new rehab_norder_display($prefix . 'sod-', $cfg);

    if ($this->loo->changed() && $this->loo->chosen()) {
	    $this->sod->reset($this->loo->chosen());
	    $this->sod_changed = 1;
    }
    if (array_key_exists($prefix. 'OHistory', $_REQUEST))
	    $this->sod->history($_REQUEST[$prefix . 'OHistory']); 

    __lib_u_therapist_nexec_list_cfg(&$cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);

    if (!$this->sod->chosen())
	    return; 
    $this->Rx_ObjectID = $this->sod->chosen();
  }

  function lost_selection() {
	  return $this->sod_changed;
  }

  function reset($id) {
	  if (is_null($id)) {
		  $this->loo->reset($id);
		  $this->sod->reset($id);
		  $this->Rx_ObjectID = $this->sod->chosen();
	  }
	  list_of_simple_objects::reset($id);
  }

  function allow_new() {
	  if (!$this->sod->chosen())
		  return 0;
	  return 1;
  }

  function draw() {
	  $this->loo->draw();
	  if (!$this->Rx_ObjectID)
		  return;

	  print "<br />\n";
	  mx_titlespan('[リハ箋内容]');
	  $this->sod->draw();
	  $sod_history = $this->sod->history();
	  $oh = $this->prefix . 'OHistory';
	  if (($sod_history & 3) == 3)
		  mx_formi_submit($oh, 'Prev',
				  mx_img_url('history.png'),'履歴');
	  else {
		  if (($sod_history & 5) == 5)
			  mx_formi_submit($oh, 'Prev',
					  mx_img_url('history-prev.png'),
					  '前へ');
		  if (($sod_history & 9) == 9)
			  mx_formi_submit($oh, 'Next',
					  mx_img_url('history-next.png'),
					  '後へ');
	  }
	  print "<br />\n";
	  mx_titlespan('[リハ実施記録]');
	  return list_of_simple_objects::draw();
  }

  function base_fetch_stmt_0() {
    return (list_of_simple_objects::base_fetch_stmt_0() .
	    ' AND RX."ObjectID" = ' .
	    mx_db_sql_quote($this->Rx_ObjectID));
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '日付') ? 1 : 0);
    }
    return $paging_orders;
  }

}

function __lib_u_therapist_nexec_cfg(&$cfg) {
	__lib_u_therapist_nexec_list_cfg(&$cfg);

	$select = 'SELECT
X."ID",
X."日付",
X."リハ処方箋",
ET."ObjectID" as "実施療法士",
(EE."姓" || \' \' || EE."名") as "記録者",
X."CreatedBy",
X."評価S", X."評価O", X."評価A", X."評価P"
FROM "リハ実施記録" AS X
LEFT JOIN "職員台帳" AS ET ON X."実施療法士" = ET."ObjectID"
LEFT JOIN "職員台帳" AS EE
	ON X."CreatedBy" = EE.userid AND EE."Superseded" IS NULL
';
	$cfg['HSTMT'] = $select . 'WHERE NULL IS NULL';
	$cfg['STMT'] = $select . 'WHERE X."Superseded" IS NULL';

	$cfg['DCOLS_BASE'] = array
		(array('Column' => 'リハ処方箋',
		       'Label' => 'リハ処方箋',
		       'Draw' => 'static',
		       'Page' => 0),
		 array('Column' => "ID",
		       'Label' => '実施記録ID',
		       'Draw' => 'static',
		       'ICOLS-Exclude' => 1,
		       'Page' => 0),
		 array('Column' => '日付',
		       'Label' => '日付',
		       'Draw' => 'text',
		       'Page' => 0),
		 array('Column' => '実施療法士',
		       'Label' => '実施療法士',
		       'Draw' => 'enum',
		       'Enum' => $cfg['EmployeeEnum'],
		       'Page' => 0),
		 array('Column' => '記録者',
		       'Label' => '記録者名',
		       'Draw' => 'static',
		       'ICOLS-Exclude' => 1,
		       'Page' => 0),
		 array('Column' => 'CreatedBy',
		       'Draw' => NULL,
		       'ICOLS-Exclude' => 1),
		 array('Column' => '評価S',
		       'Label' => '評価S',
		       'Draw' => 'textarea',
		       'Page' => 0),
		 array('Column' => '評価O',
		       'Label' => '評価O',
		       'Draw' => 'textarea',
		       'Page' => 0),
		 array('Column' => '評価A',
		       'Label' => '評価A',
		       'Draw' => 'textarea',
		       'Page' => 0),
		 array('Column' => '評価P',
		       'Label' => '評価P',
		       'Draw' => 'textarea',
		       'Page' => 0));

	$cfg['DPAGES'] = $cfg['DPAGES_BASE'] = array('実施記録');
	$cfg['DCOLS'] = $cfg['DCOLS_BASE'];

	$cfg['EPAGES'] = $cfg['EPAGES_BASE'] = array('実施記録');
	$cfg['ECOLS'] = $cfg['ECOLS_BASE'] = $cfg['DCOLS_BASE'];

	$cfg['ICOLS'] = array();
	foreach ($cfg['ECOLS_BASE'] as $d) {
		if (array_key_exists('ICOLS-Exclude', $d))
			continue;
		$cfg['ICOLS'][] = $d['Column'];
	}
}

class rehab_nexec_display extends simple_object_display {

	var $debug = 1;

	function rehab_nexec_display($prefix, $cfg=NULL) {
		if (is_null($cfg)) $cfg = array();
		__lib_u_therapist_nexec_cfg(&$cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function pre_draw_hook($data, $hdata) {
		global $_lib_u_therapist_execution_code_enum;

		$last_i = 0;
		for ($i = 1;
		     array_key_exists("$i-ObjectID", $data);
		     $i++) {
			$last_i = $i;
		}
		if ($hdata)
			for ($i = $last_i;
			     array_key_exists("$i-ObjectID", $hdata);
			     $i++) {
				$last_i = $i;
			}

		$cfg =& $this->so_config;
		$cfg['DCOLS'] = $cfg['DCOLS_BASE'];
		$cfg['DPAGES'] = $cfg['DPAGES_BASE'];
		for ($i = 1; $i <= $last_i; $i++) {
			$cfg['DCOLS'][] = array('Column' => "$i-開始日時",
						'Label' => "開始日時",
						'Draw' => 'text',
						'Page' => $i);
			$cfg['DCOLS'][] = array('Column' => "$i-終了日時",
						'Label' => "終了日時",
						'Draw' => 'text',
						'Page' => $i);
			$cfg['DCOLS'][] = array('Column' => "$i-訓練場所",
						'Label' => "訓練場所",
						'Draw' => 'enum',
						'Enum' =>
						array('訓練室' => '訓練室',
						      '病室' => '病室'),
						'Page' => $i);
			$cfg['DCOLS'][] = array('Column' => "$i-訓練内容",
						'Label' => "訓練内容",
						'Draw' => 'text',
						'Page' => $i);
			$cfg['DCOLS'][] = array('Column' => "$i-単位数",
						'Label' => "単位数",
						'Draw' => 'text',
						'Page' => $i);
	
			$cfg['DCOLS'][] = array('Column' => "$i-診療行為コード",
						'Label' => "診療行為コード",
						'Page' => $i,
						'Draw' => 'enum',
						'Enum' => $_lib_u_therapist_execution_code_enum);

			$cfg['DCOLS'][] = array('Column' => "$i-コメント",
						'Label' => "コメント",
						'Draw' => 'textarea',
						'Page' => $i);
			$cfg['DPAGES'][] = "($i)";
		}
		simple_object_display::pre_draw_hook($data, $hdata);
	}

	function fetch_data($id) {
		$data = simple_object_display::fetch_data($id);
		$stmt = ('SELECT 
"ObjectID", "CreatedBy", "開始日時", "終了日時", "訓練場所", "訓練内容",
"単位数", "単位種別", "診療行為コード", "コメント"
FROM "リハ実施記録内容"
WHERE "リハ実施記録" = ' . mx_db_sql_quote($id) . '
ORDER BY "ObjectID"');

		$db = mx_db_connect();
		$data1 = pg_fetch_all(pg_query($db, $stmt));
		if (!is_array($data1))
			return $data;
		for ($i = 1; $i <= count($data1); $i++) {
			$tum = $data1[$i-1];
			foreach ($tum as $k => $v) {
				$data["$i-$k"] = $v;
			}
		}
		return $data;
	}

}

class rehab_nexec_edit extends simple_object_edit {

	var $debug = 1;

	function rehab_nexec_edit($prefix, $cfg=NULL) {
		if (is_null($cfg)) $cfg = array();
		__lib_u_therapist_nexec_cfg(&$cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function anew_tweak($orig_id) {
		$this->data['リハ処方箋'] = $this->Rx_ObjectID;
	}

	function _validate($force=NULL) {
		$fatal_errs = $errs = 0;
		if ($msg = mx_db_validate_date($this->data['日付'])) {
			$this->err("日付: $msg");
			$errs++;
			$fatal_errs++;
		}

		$this->err('データベースへの書き戻しはまだです');
		return 'bad';

		if ($errs == 0 || (($fatal_errs == 0) && $force)) {
			$this->errmsg = '';
			return 'ok';
		}
	}
}

?>
