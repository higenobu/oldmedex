<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_edit.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/direction.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/mediserve.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/demo/pdf.php';

function _validate_mediserve_check($new_order_details, $old_order_details, $opt) {
  $dbh = mx_db_connect();
  $checker = new mediserve_checker($dbh);
  $_cache = array();
  $_new_only = array();
  if (!is_array($old_order_details))
    $old_order_details = array();
  foreach(array_merge($new_order_details,
		      $old_order_details) as $m) {
    $checker->add_med($m['����'],
		      $m['����'],
		      $m['����'],
		      $m['����'] * $m['����'],
		      $m['����ñ��ñ��']
		      );
    $_cache[$m["����"]] = $m["�쥻�ץ��Ż����������ƥ������̾"];
  }
  foreach($new_order_details asget_ $m) {
    $_new_only[$m["����"]] = $m["�쥻�ץ��Ż����������ƥ������̾"];
  }

	if(is_array($opt['diseases']))
		foreach($opt['diseases'] as $disease)
			$checker->add_dis($disease['����']);

  $checker->check();
  $err = $checker->get_err();
  if (!$err)
    return '';

  $msg = array();
  foreach ($err as $e) {
    $med = array();
    foreach(array('��§�ش�', '��get_�жش�', '����������', '�ش���̾') as $k) {
      if ($e[$k] ) {
	$med[] = $_cache[$e['Medicine']];
	if (is_array($e[$k]))
	  foreach($e[$k] as $m)
	    $med[] = $_cache[$m];
	if ($_new_only[$e['Medicine']]) {
	  if ($k == '����������') {
	    $v = $e['����������'];
	    $u = $e['������ñ��'];
	  } else if ($k == '�ش���̾') {
	    $k = $e['��̾ɽ��'];
	    $v = '���Ф��ƶش�';
	    $u = '';
	  }
	  $msg[] = implode(', ', $med) . "��${k}${v}${u}�Ǥ�";
	}
      }
    }
  }


  if ($msg)
    return implode("\n", $msg);
  return '';
}

function _lib_u_demo_order_cfg(&$cfg)
{
  $pt_outin = 'I';
  $cfg = array_merge(array(
			   'ALLOW_SORT' => 'orderdate',
			   'DEFAULT_SORT' => 'orderdate',
			   'COLS' => array('orderdate',
					   'procdate',
					  
					   'bui1',
					   'memo1',
					   'memo2',
					   'memo11',
					   'memo12',
					   'techsyoken',
					   'drsyoken',
					   'proof'
					   
					   ),
			   'LCOLS' => array(array('Label' => 'orderID',
						  'Column' => 'ObjectID'),
					    'orderdate',
					    
					    array('Column' => 'procdate',
						  'Label' => 'procdate'),
					    array('Column' => '��߰�',
						  'Label' => '��߰�',
						  'Draw' => 'employee'
						  ),
					    array('Column' => 'drsyoken',
						  'Label' => 'drsyoken',
						  ),
					    
					    ),
			   'DCOLS' => array('orderdate',
					    'procdate',
					    
					    array('Column' =>'��߰�',
						  'Draw' => 'employee'),
					    
					    array('Column' => 'DETAILS',
						  'Label' => '��������',
						  'Draw' => 'order_detail'
						  ),
					    ),
			   'ECOLS' => array(array('Column' => 'orderdate',
						  'Draw' => 'date',
						  'Option' => array('validate' => 'date,nonnull')
						  ),
					    array('Column' => 'procdate',
						  'Draw' => 'date',
						  'Option' => array('validate' => 'date,nonnull')
						  ),
					   
					    array('Column' => 'DETAILS',
						  'Label' => '��������',
						  'Draw' => 'rp_edit',
						  'Option' => array('validate' => 'mediserve_check'),
						  ),
					    array('Column' => '�±����ѥ쥻�����ݸ�����',
					          'Draw' => NULL,
					          'Option' => array('empty-is-null' => 1)),
					    array('Column' => '�±����ѥ쥻������ǲʾ���',
					          'Draw' => NULL,
					          'Option' => array('empty-is-null' => 1)),
					    ),
			   ),
		     $cfg
		     );
  $cfg['D_RANDOM_LAYOUT'] = array
    (
     array('Label' => 'orderdate'),
     array('Label' => 'procdate'),
     array('Label' => 'memo1'),
     array('Label' => 'memo2'),
     

     array('Insn' => '//'),

     array('Column' => 'orderdate'),
     array('Column' => 'procdate'),
     array('Column' => 'memo1'),
     array('Column' => 'memo2'),
     
     array('Insn' => '//'),

     array('Label' => 'bui1', 'Span' => 3),
     array('Label' => 'memo11'),
     array('Label' => 'memo12'),
     array('Label' => 'texhsyoken'),
     array('Label' => 'drsyoken'),
     

     array('Column' => 'DETAILS', 'Span' => 8),
     );

  $cfg['E_RANDOM_LAYOUT'] = array
    (
     array('Label' => 'orderdate'),
     array('Label' => 'procdate'),
     
     array('Insn' => '//'),

     array('Column' => 'orderdate'),
     array('Column' => 'procdate'),
     
     array('Insn' => '//'),

     array('Column' => 'DETAILS', 'Span' => 5),
     );

  $cfg['ICOLS'] = $cfg['COLS'];
  $table = $cfg['TABLE'];
  $detail_table = $cfg['DETAIL_TABLE'];
  $cfg['DETAIL_TABLE_STMT'] = <<<SQL
SELECT C."ObjectID", 
    C."bui1", M."�쥻�ץ��Ż����������ƥ������̾", M."����ñ��ñ��",
    C."memo11", 
    C."memo12", 
    C."memo1", C."techsyoken", C."drsyoken"
FROM "${detail_table}" as C
    
WHERE C."${table}" =  %d
    ORDER BY C."ObjectID"
SQL;
}

class list_of_demo_orders extends list_of_ppa_objects {
  function list_of_demo_orders($prefix, $cfg=NULL) {
    _lib_u_demo_order_cfg($cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	    $paging_orders[] = (($col == 'orderdate') || ($col == '"ObjectID"'));
    }
    return $paging_orders;
  }

  function dx_employee($desc, $value, $row) {
    $name = get_emp_name($value);
    $this->_dx_textish($name['lname'] . $name['fname']);
  }
}

class demo_order_display extends simple_object_display {
  function demo_order_display($prefix, $cfg=NULL) {
    _lib_u_demo_order_cfg($cfg);
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
  <th>bui1</th><th>memo11</th><th>memo12</th><th>techsyoken</th><th>drsyoken</th><th>����</th><th>������</th></tr>
 </tr>
HTML;
      foreach($value as $v) {
	$drug = $v['bui1'];
	$amount = $v['memo11'];
	$unit = $v['memo12'];
	$direction = $v['techsyoken'];
	$uneven = $v['drsyoken'];
	$days_times = $v['����'];
	$comment = $v['����¾������'];
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

class demo_order_edit extends simple_object_ppa_edit {
  var $debug = 1;
  var $reception_column_name = '���Լ��վ���';
  var $rececom_inscol_name = '�±����ѥ쥻�����ݸ�����';
  var $rececom_dptcol_name = '�±����ѥ쥻������ǲʾ���';

  function demo_order_edit($prefix, $cfg=NULL) {
    _lib_u_demo_order_cfg(&$cfg);
    $this->rp_edit = new rp_edit($prefix . 'rp-edit-', $cfg);
    $this->kick_claim_column = $cfg['KICK_CLAIM_COLUMN'];
    simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['orderdate'] = mx_today_string();
    $this->data['procdate'] = mx_today_string();
  }

  function annotate_row_data(&$data) {
    $this->rp_edit->db2rp_edit(&$data['DETAILS']);
    simple_object_ppa_edit::annotate_row_data(&$data);
  }

  function annotate_form_data(&$data) {
    simple_object_ppa_edit::annotate_form_data(&$data);
    // at this point the $data only has parent row data
    $details = array();
    $this->rp_edit->rp_edit2db(&$details);
    $data["DETAILS"] = $details;
  }

  function find_effective_orders($date_as_of) {
    $db = mx_db_connect();
    $stmt = <<<SQL
SELECT O."ObjectID"
FROM "{$this->so_config['TABLE']}" O 
WHERE 
      O."����"={$this->so_config['Patient_ObjectID']} 
      AND O."Superseded" IS NULL
     
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

    // do not include ��Ϳ����,��ˡ
    $detail_cols = array('bui1','memo11','memo12',
			 'techsyoken', 'drsyoken','�굻','�����ˡ','����¾������',
			 'RPID','����','��ˡʬ��','��ʬ', '����',
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

    $old_order_oids = $this->find_effective_orders($this->data["procdate"]);
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
