<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
//
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
//
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_edit.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/direction.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/mediserve.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdf.php';

function _validate_mediserve_check($new_order_details, $old_order_details, $opt) {
  $dbh = mx_db_connect();
  $checker = new mediserve_checker($dbh);
  $_cache = array();
  $_new_only = array();
  if (!is_array($old_order_details))
    $old_order_details = array();
  foreach(array_merge($new_order_details,
		      $old_order_details) as $m) {
    $checker->add_med($m['薬剤'],
		      $m['日数'],
		      $m['用量'],
		      $m['日数'] * $m['用量'],
		      $m['包装単位単位']
		      );
    $_cache[$m["薬剤"]] = $m["レセプト電算処理システム医薬品名"];
  }
  foreach($new_order_details as $m) {
    $_new_only[$m["薬剤"]] = $m["レセプト電算処理システム医薬品名"];
  }

	if(is_array($opt['diseases']))
		foreach($opt['diseases'] as $disease)
			$checker->add_dis($disease['疾病']);

  $checker->check();
  $err = $checker->get_err();
  if (!$err)
    return '';

  $msg = array();
  foreach ($err as $e) {
    $med = array();
    foreach(array('原則禁忌', '絶対禁忌', '一日最大量', '禁忌病名') as $k) {
      if ($e[$k] ) {
	$med[] = $_cache[$e['Medicine']];
	if (is_array($e[$k]))
	  foreach($e[$k] as $m)
	    $med[] = $_cache[$m];
	if ($_new_only[$e['Medicine']]) {
	  if ($k == '一日最大量') {
	    $v = $e['一日最大量'];
	    $u = $e['最大量単位'];
	  } else if ($k == '禁忌病名') {
	    $k = $e['病名表記'];
	    $v = 'に対して禁忌';
	    $u = '';
	  }
	  $msg[] = implode(', ', $med) . "は${k}${v}${u}です";
	}
      }
    }
  }


  if ($msg)
    return implode("\n", $msg);
  return '';
}

function _lib_u_pharmacy_order1_cfg(&$cfg)
{
  $pt_outin = 'I';
  $cfg = array_merge(array(
			   'ALLOW_SORT' => '処方年月日',
			   'DEFAULT_SORT' => '処方年月日',
			   'COLS' => array('処方年月日',
					   '処方開始日',
					   '記録者',
					   '患者',
					   '院内院外',
					   '定期臨時',
					   '停止日',
					   '停止医',
					   '調剤薬剤師',
					   '調剤年月日',
					   '看護師実施',
					   '看護記録者',
					   '薬剤記録者',
					   '後発品',
'generic',
'病院使用レセコン保険情報',
'病院使用レセコン受診科情報',
					   #'Comment',
					   #'PDF',
					   ),
			   'LCOLS' => array(array('Label' => '処方箋ID',
						  'Column' => 'ObjectID'),
					    '処方年月日',
					    array('Column'=>'院内院外',
						  'Label' => '区分',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '院内',
								  2 => '院外')
						  ),
					    
					    array('Column' => '停止医',
						  'Label' => '中止医',
						  'Draw' => 'employee'
						  ),
					    
					    
					     array('Label' => '後発品全不可',
					     'Column' => 'generic',
					     'Draw' => 'enum',
					     'Enum' => array('0' => '不可',
					     '1' => ''))
					 
					    ),
			   'DCOLS' => array('処方年月日',
					    '処方開始日',
					    array('Column' => '記録者',
						  'Draw' => 'employee'),
					    array('Column'=>'院内院外',
						  'Label' => '区分',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '院内',
								  2 => '院外')
						  ),
					    array('Column' => '定期臨時',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '定期',
								  2 => '臨時')),
					    '停止日',
					    array('Column' =>'停止医',
						  'Draw' => 'employee'),
					    array('Column' => '調剤薬剤師',
						  'Draw' => 'employee'),
					    '調剤年月日',
					    array('Column' => '看護師実施',
						 'Draw' => 'employee'),
					    array('Column' => '看護記録者',
						 'Draw' => 'employee'),
					    array('Column' => '薬剤記録者',
						 'Draw' => 'employee'),
					    array('Column' => 'generic',
						  'Enum' => array(1 => '可',
								  0 => '不可'),
						  'Draw' => 'enum',
						  ),
					    array('Column' => 'DETAILS',
						  'Label' => '処方内容',
						  'Draw' => 'order_detail'
						  ),
					    ),
			   'ECOLS' => array(array('Column' => '処方年月日',
						  'Draw' => 'date',
						  'Option' => array('validate' => 'date,nonnull')
						  ),
					    array('Column' => '処方開始日',
						  'Draw' => 'date',
						  'Option' => array('validate' => 'date,nonnull')
						  ),
						array('Column'=>'記録者',
						  'Label' => '指示医',
						  'Draw' => 'enum',
						  'Enum' => lib_ord_common_get_doctors(NULL, 'doctor', array('enum' => 1))),

					    array('Column'=>'院内院外',
						  'Label' => '区分',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '院内',
								  2 => '院外')
						  ),
					    array('Column' => '定期臨時',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '定期',
								  2 => '臨時')),
					    array('Column' => 'generic',
						  'Enum' => array(1 => '可',
								  0 => '不可'),
						  'Draw' => 'enum',
						  ),
					    array('Column' => 'DETAILS',
						  'Label' => '処方内容',
						  'Draw' => 'rp_edit',
						  'Option' => array('validate' => 'mediserve_check'),
						  ),
					    array('Column' => '病院使用レセコン保険情報',
					          'Draw' => NULL,
					          'Option' => array('empty-is-null' => 1)),
					    array('Column' => '病院使用レセコン受診科情報',
					          'Draw' => NULL,
					          'Option' => array('empty-is-null' => 1)),
					    ),
			   ),
		     $cfg
		     );
  $cfg['D_RANDOM_LAYOUT'] = array
    (
     array('Label' => '処方年月日'),
     array('Label' => '処方開始日'),
     array('Label' => '記録者'),
     array('Label' => '院内院外'),
     array('Label' => '定期臨時'),
     array('Label' => '後発品'),
     array('Label' => '停止日'),
     array('Label' => '停止医'),

     array('Insn' => '//'),

     array('Column' => '処方年月日'),
     array('Column' => '処方開始日'),
     array('Column' => '記録者'),
     array('Column' => '院内院外'),
     array('Column' => '定期臨時'),
     array('Column' => 'generic'),
     array('Column' => '停止日'),
     array('Column' => '停止医'),

     array('Insn' => '//'),

     array('Label' => '薬剤', 'Span' => 3),
     array('Label' => '用量'),
     array('Label' => '単位'),
     array('Label' => '用法'),
     array('Label' => '不均等'),
     array('Label' => '日数'),
     array('Label' => 'コメント'),

     array('Column' => 'DETAILS', 'Span' => 8),
     );

  $cfg['E_RANDOM_LAYOUT'] = array
    (
     array('Label' => '処方年月日'),
     array('Label' => '処方開始日'),
	 array('Label' => '記録者'),
     array('Label' => '院内院外'),
     array('Label' => '定期臨時'),
     array('Label' => '後発品'),

     array('Insn' => '//'),

     array('Column' => '処方年月日'),
     array('Column' => '処方開始日'),
     array('Column' => '記録者'),
     array('Column' => '院内院外'),
     array('Column' => '定期臨時'),
     array('Column' => 'generic'),

     array('Insn' => '//'),

     array('Column' => 'DETAILS', 'Span' => 5),
     );

  $cfg['ICOLS'] = $cfg['COLS'];
  $table = $cfg['TABLE'];
  $detail_table = $cfg['DETAIL_TABLE'];
  $cfg['DETAIL_TABLE_STMT'] = <<<SQL
SELECT C."ObjectID", C."RPID",
    C."薬剤", M."レセプト電算処理システム医薬品名", M."包装単位単位",
    C."用量", 
    C."用法", Y."用法" as "用法名称", 
    C."日数", C."用法分類", C."その他コメント"
FROM "${detail_table}" as C
    LEFT JOIN "処方箋用法" as Y
    ON C."用法" = Y."ObjectID" AND Y."Superseded" is NULL
    JOIN "Medis医薬品マスター" as M
    ON M."ObjectID" = C."薬剤" AND M."Superseded" IS NULL
WHERE C."${table}" =  %d
    ORDER BY C."ObjectID"
SQL;
}

class list_of_pharmacy_order1s extends list_of_simple_objects {
  function list_of_pharmacy_order1s($prefix, $cfg=NULL) {
    _lib_u_pharmacy_order1_cfg($cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	    $paging_orders[] = (($col == '処方年月日') || ($col == '"ObjectID"'));
    }
    return $paging_orders;
  }

  function dx_employee($desc, $value, $row) {
    $name = get_emp_name($value);
    $this->_dx_textish($name['lname'] . $name['fname']);
  }
}

class pharmacy_order1_display extends simple_object_display {
  function pharmacy_order1_display($prefix, $cfg=NULL) {
    _lib_u_pharmacy_order1_cfg($cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }

  function print_sod() {
    go_pdf($this->id, 0);
  }
  function fetch_data($id) {
    $data = simple_object_display::fetch_data($id);
    if($this->so_config['DETAIL_TABLE_STMT']) {
      $stmt = sprintf($this->so_config['DETAIL_TABLE_STMT'], $id);
      $data['DETAILS'] = mx_db_fetch_all(mx_db_connect(), $stmt);
    }
    return $data;
  }

  function dx_order_detail($desc, $value, $row) {
    if(is_array($value)) {
      if(0) 
	print <<<HTML
 <tr>
  <th>薬剤</th><th>用量</th><th>単位</th><th>用法</th><th>不均等</th><th>日数</th><th>コメント</th></tr>
 </tr>
HTML;
      foreach($value as $v) {
	$drug = $v['レセプト電算処理システム医薬品名'];
	$amount = $v['用量'];
	$unit = $v['包装単位単位'];
	$direction = $v['用法名称'];
	$uneven = $v['用法分類'];
	$days_times = $v['日数'];
	$comment = $v['その他コメント'];
	print <<<HTML
  <tr>
    <td colspan=3>${drug}</td><td>${amount}</td><td>${unit}</td><td>${direction}</td><td>${uneven}</td><td>${days_times}</td><td>${comment}</td>
  </tr>
HTML;
      }
    }
  }
  function dx_employee($desc, $value, $changed) {
    $name = get_emp_name($value);
    $this->_dx_textish($name['lname'] . $name['fname'], $changed);
  }
}

class pharmacy_order1_edit extends simple_object_edit {
  var $debug = 1;
  var $reception_column_name = '患者受付情報';
  var $rececom_inscol_name = '病院使用レセコン保険情報';
  var $rececom_dptcol_name = '病院使用レセコン受診科情報';

  function pharmacy_order1_edit($prefix, $cfg=NULL) {
    _lib_u_pharmacy_order1_cfg(&$cfg);
    $this->rp_edit = new rp_edit($prefix . 'rp-edit-', $cfg);
    $this->kick_claim_column = $cfg['KICK_CLAIM_COLUMN'];
    simple_object_ppa_edit::simple_object_edit($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['処方年月日'] = mx_today_string();
    $this->data['処方開始日'] = mx_today_string();
  }

  function annotate_row_data(&$data) {
    $this->rp_edit->db2rp_edit(&$data['DETAILS']);
    simple_object_edit::annotate_row_data(&$data);
  }

  function annotate_form_data(&$data) {
    simple_object_edit::annotate_form_data(&$data);
    // at this point the $data only has parent row data
    $details = array();
    $this->rp_edit->rp_edit2db(&$details);
    $data["DETAILS"] = $details;
  }

  function find_effective_orders($date_as_of) {
    $db = mx_db_connect();
    $stmt = <<<SQL
SELECT O."ObjectID"
FROM "{$this->so_config['TABLE']}" O JOIN
     "{$this->so_config['DETAIL_TABLE']}" D ON O."ObjectID"=D."{$this->so_config['TABLE']}" AND O."Superseded" IS NULL
WHERE 
      O."患者"={$this->so_config['Patient_ObjectID']} AND
      O."処方開始日" + D."日数" >= '${date_as_of}' AND
     (O."停止日" IS NULL OR (O."停止日" >= '${date_as_of}'))
SQL;
    return mx_db_fetch_all($db, $stmt);
  }
  
  function fetch_data($id) {
    $data = simple_object_display::fetch_data($id);
    if($this->so_config['DETAIL_TABLE_STMT']) {
      $stmt = sprintf($this->so_config['DETAIL_TABLE_STMT'], $id);
      $data['DETAILS'] = mx_db_fetch_all(mx_db_connect(), $stmt);
    }
    return $data;
  }

  function data_compare($curr, $data) {
    $ret = simple_object_ppa_edit::data_compare($curr, $data);
    return $ret | $this->rp_edit->data_compare($curr['DETAILS'], $data['DETAILS']);
  }
  function _update_subtables(&$db, $id, $stash_id) {
    $tbl = $this->so_config['TABLE'];
    $detail_tbl = $this->so_config['DETAIL_TABLE'];
    if (! is_null($stash_id)) {
      $stmt = ("UPDATE \"$detail_tbl\" SET \"${tbl}\" = " .
	       mx_db_sql_quote($stash_id) .
	       " WHERE \"$tbl\" = " .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }

    // do not include 投与形態,用法
    $detail_cols = array('薬剤','用量単位','用量',
			 '日数', '用法','手技','注射用法','その他コメント',
			 'RPID','一包','用法分類','区分', '頓服',
			 'generic_ok');

    foreach ($this->data['DETAILS'] as $r) {
      $cols = array($tbl);
      $vals = array($id);
      foreach($detail_cols as $c) {
	$cols[] = "\"$c\"";
	$vals[] = mx_db_sql_quote($r[$c]);
      }
      $col = implode(",", $cols);
      $val = implode(",", $vals);
      $stmt = "INSERT INTO \"$detail_tbl\" ($col) VALUES ($val)";
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }

  function dx_rp_edit($desc, $name, $value) {
    $this->rp_edit->draw();
  }

  function _validate($force=NULL) {
    global $_mx_link_mediserve;

    print "<!-- local validate called-->";
    if ($force == 'force')
      return 'ok';
    print "<!-- calling super -->";
    $bad = simple_object_ppa_edit::_validate($force) != 'ok' ? 1 : 0;
    print "<!-- done ($bad)-->";
    $err = $this->rp_edit->validate();
    if (is_array($err))
      foreach($err as $e) {
	$this->err($e);
	$bad += 1;
      }

    // when rp editing has error we should not check inhibit
    if ($bad)
      return '';

    $old_order_oids = $this->find_effective_orders($this->data["処方開始日"]);
    $old_orders = array();
    $old_order_details = array();
    if (is_array($old_order_oids)) {
      foreach($old_order_oids as $o) {
	$old_order = $this->fetch_data($o["ObjectID"]);
	$old_order_details = array_merge($old_order_details, $old_order['DETAILS']);
      }
    }
    foreach($this->so_config['ECOLS'] as $elem)
      if ($elem['Column'] == 'DETAILS')
	break;
    $opt = mx_check_option('Option', $elem);
    $opt['diseases'] = $this->so_config['diseases'];
    $v = $this->data['DETAILS'];
    if ($_mx_link_mediserve) {
      $st = _validate_mediserve_check($v, $old_order_details, $opt);
      if ($st) {
	$this->err($st);
	$bad += 1;
      }
    }
    $fatal_errs = 0; // NEEDSWORK
    $this->error_override_allowed = (!$force && $fatal_errs == 0);
    
    if ($bad == 0 || (($fatal_errs == 0) && $force)) {
      $this->errmsg = '';
      return 'ok';
    }
    return '';
  }

}
?>
