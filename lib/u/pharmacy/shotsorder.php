<?php // -*- mode: php; coding: euc-japan -*-
//revised 11-01-2014
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_edit.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/direction.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/mediserve.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdf-new.php';
//0407-2012 doctor list
function _lib_u_get_doctor9() {

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."ID", E."��", E."̾" , E.userid from 
          "������Ģ" as E,
	  "�������ɽ" as C
	  where
          C."Superseded" is NULL and
          E."Superseded" is NULL and
	  C."ID" = E."����" and C."������"='10'
          
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array();
  foreach($rows as $row)
    $ret[$row['userid']] = $row['��'].$row['̾'];
  return $ret;
}

//0407-2012
/*
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
  foreach($new_order_details as $m) {
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
    foreach(array('��§�ش�', '���жش�', '����������', '�ش���̾') as $k) {
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
*/

function _lib_u_shot_order_cfg(&$cfg)
{

  $pt_outin = 'I';
  $cfg = array_merge(array('TABLE'=>'��ͽ����',
			   'ALLOW_SORT' => '����ǯ����',
			   'DEFAULT_SORT' => '����ǯ����',
			   'COLS' => array('����ǯ����',
					   '����������',
					   '��Ͽ��',
					   '����',
					   '���ⱡ��',
					   '����׻�',
					   '�����',
					   '��߰�',
					   'Ĵ�����޻�',
					   'Ĵ��ǯ����',
					   '�Ǹ�ռ»�',
					   '�ǸϿ��',
					   '���޵�Ͽ��',
					   '��ȯ��',
'shiji',
'generic',
'�±����ѥ쥻�����ݸ�����',
'�±����ѥ쥻������ǲʾ���',
'startdate',
'Comment',
'recorded'
					   #'Comment',
					   #'PDF',
					   ),
			   'LCOLS' => array(array('Label' => '�����ID',
						  'Column' => 'ObjectID'),
					    '����ǯ����',
					    array('Column'=>'���ⱡ��',
						  'Label' => '��ʬ',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '����',
								  2 => '����')
						  ),
					    array('Column'=>'shiji',
						  'Label' => '�ؼ���',
						  'Draw' => 'enum',
						  'Enum' => _lib_u_get_doctor9()),
					    array('Column' => '��߰�',
						  'Label' => '��߰�',
						  'Draw' => 'employee'
						  ),
//11-01-2014					    
					   array('Column' => 'Comment',
'Label' => 'Summary', 'Draw' => 'text'),
					     array('Label' => '��ȯ�����Բ�',
					     'Column' => 'generic',
					     'Draw' => 'enum',
					     'Enum' => array('0' => '��',
					     '1' => '�Բ�'))
					 
					    ),
			   'DCOLS' => array('����ǯ����',
					    'startdate',
					    array('Column' => 'shiji',
						  'Draw' => 'employee'),
					    array('Column'=>'���ⱡ��',
						  'Label' => '��ʬ',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '����',
								  2 => '����')
						  ),
					    array('Column' => '����׻�',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '���',
								  2 => '�׻�')),
					    '�����',
					    array('Column' =>'��߰�',
						  'Draw' => 'employee'),
					    array('Column' => 'Ĵ�����޻�',
						  'Draw' => 'employee'),
					    'Ĵ��ǯ����',
					    array('Column' => '�Ǹ�ռ»�',
						 'Draw' => 'employee'),
					    array('Column' => '�ǸϿ��',
						 'Draw' => 'employee'),
					    array('Column'=>'shiji',
						  'Label' => '�ؼ���',
						  'Draw' => 'enum',
						  'Enum' => _lib_u_get_doctor9()),
					    array('Column' => 'generic',
						  'Enum' => array(0 => '��',
								  1 => '�Բ�'),
						  'Draw' => 'enum',
						  ),
//11-01-2014
array('Column' => 'recorded',
'Label' => '��Ͽ', 'Draw' => 'timestamp'),
//
					    array('Column' => 'DETAILS',
						  'Label' => '��������',
						  'Draw' => 'order_detail'
						  ),
					    ),
			   'ECOLS' => array(array('Column' => '����ǯ����',
						  'Draw' => 'date',
						  'Option' => array('validate' => 'date,nonnull')
						  ),
					    array('Column' => 'startdate',
						  'Draw' => 'date',
						  'Option' => array('validate' => 'date,nonnull')
						  ),
						array('Column'=>'shiji',
						  'Label' => '�ؼ���',
						  'Draw' => 'enum',
						  'Enum' => _lib_u_get_doctor9()),
					    array('Column'=>'���ⱡ��',
						  'Label' => '��ʬ',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '����',
								  2 => '����')
						  ),
					    array('Column' => '����׻�',
						  'Draw' => 'enum',
						  'Enum' => array(1 => '���',
								  2 => '�׻�')),
					    array('Column' => 'generic',
						  'Enum' => array(0 => '��',
								  1 => '�Բ�'),
						  'Draw' => 'enum',
						  ),
//11-01-2014
array('Column' => 'recorded',
'Label' => '��Ͽ', 'Draw' => 'timestamp'),
array('Column' => 'Comment',
'Label' => 'Summary', 'Draw' => 'text'),


//
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
     array('Label' => '����ǯ����'),
     array('Label' => '����������'),
     array('Label' => '�ؼ�'),
     array('Label' => '���ⱡ��'),
     array('Label' => '����׻�'),
     array('Label' => '��ȯ��'),
     array('Label' => '�����'),
     array('Label' => '��߰�'),
array('Label' => '��Ͽ'),
     array('Insn' => '//'),

     array('Column' => '����ǯ����'),
     array('Column' => 'startdate'),
     array('Column' => 'shiji'),
     array('Column' => '���ⱡ��'),
     array('Column' => '����׻�'),
     array('Column' => 'generic'),
     array('Column' => '�����'),
     array('Column' => '��߰�'),
//11-01-2014
array('Column' => 'recorded'),
     array('Insn' => '//'),

     array('Label' => '����', 'Span' => 3),
     array('Label' => '����'),
     array('Label' => 'ñ��'),
     array('Label' => '��ˡ'),
     array('Label' => '�Զ���'),
     array('Label' => '����'),
     array('Label' => '������'),
//11-01-2014
//array('Label' => 'recorded'),
//8->6
     array('Column' => 'DETAILS', 'Span' => 6),
     );

  $cfg['E_RANDOM_LAYOUT'] = array
    (
     array('Label' => '����ǯ����'),
     array('Label' => '����������'),
	 array('Label' => '�ؼ���'),
     array('Label' => '���ⱡ��'),
     array('Label' => '����׻�'),
     array('Label' => '��ȯ��'),

     array('Insn' => '//'),

     array('Column' => '����ǯ����'),
     array('Column' => 'startdate'),
     array('Column' => 'shiji'),
     array('Column' => '���ⱡ��'),
     array('Column' => '����׻�'),
     array('Column' => 'generic'),
//11-01-2014
array('Insn' => '//'),
//04-07-2012
array('Column' => 'recorded'),
array('Column'=>'Comment'),
     array('Insn' => '//'),
//04-07-2012
     array('Column' => 'DETAILS', 'Span' => 6),
     );

  $cfg['ICOLS'] = $cfg['COLS'];
  $table = '��ͽ����';
  $detail_table =  '��ͽ��������';
  $cfg['DETAIL_TABLE_STMT'] = <<<SQL
SELECT C."ObjectID", C."RPID",
    C."����", M."�쥻�ץ��Ż����������ƥ������̾", M."����ñ��ñ��",
    C."����", 
    C."��ˡ", Y."��ˡ" as "��ˡ̾��", 
    C."����", C."��ˡʬ��", C."����¾������"
FROM "${detail_table}" as C
    LEFT JOIN "�������ˡ" as Y
    ON C."��ˡ" = Y."ObjectID" AND Y."Superseded" is NULL
    JOIN "Medis�����ʥޥ�����" as M
    ON M."ObjectID" = C."����" AND M."Superseded" IS NULL
WHERE C."${table}" =  %d
    ORDER BY C."ObjectID"
SQL;
}

class list_of_shot_orders extends list_of_ppa_objects {
  function list_of_shot_orders($prefix, $cfg=NULL) {
    _lib_u_shot_order_cfg($cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	    $paging_orders[] = (($col == '����ǯ����') || ($col == '"ObjectID"'));
    }
    return $paging_orders;
  }

  function dx_employee($desc, $value, $row) {
    $name = get_emp_name($value);
    $this->_dx_textish($name['lname'] . $name['fname']);
  }
}

class shot_order_display extends simple_object_display {
  function shot_order_display($prefix, $cfg=NULL) {
    _lib_u_shot_order_cfg($cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }

  function print_sod() {
    go_pdf_new($this->id, 0);
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
  <th>����</th><th>����</th><th>ñ��</th><th>��ˡ</th><th>�Զ���</th><th>����</th><th>������</th></tr>
 </tr>
HTML;
      foreach($value as $v) {
	$drug = $v['�쥻�ץ��Ż����������ƥ������̾'];
	$amount = $v['����'];
	$unit = $v['����ñ��ñ��'];
	$direction = $v['��ˡ̾��'];
	$uneven = $v['��ˡʬ��'];
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

class shot_order_edit extends simple_object_ppa_edit {
  var $debug = 1;
  var $reception_column_name = '���Լ��վ���';
  var $rececom_inscol_name = '�±����ѥ쥻�����ݸ�����';
  var $rececom_dptcol_name = '�±����ѥ쥻������ǲʾ���';

  function shot_order_edit($prefix, $cfg=NULL) {
    _lib_u_shot_order_cfg(&$cfg);
    $this->rp_edit = new rp_edit($prefix . 'rp-edit-', $cfg);
    $this->kick_claim_column = $cfg['KICK_CLAIM_COLUMN'];
    simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['����ǯ����'] = mx_today_string();
    $this->data['startdate'] = mx_today_string();
$this->data['recorded'] =  date("Y-m-d H:i:s");
 		
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
FROM "{$this->so_config['TABLE']}" O JOIN
     "{$this->so_config['DETAIL_TABLE']}" D ON O."ObjectID"=D."{$this->so_config['TABLE']}" AND O."Superseded" IS NULL
WHERE 
      O."����"={$this->so_config['Patient_ObjectID']} AND
      O."startdate" + D."����" >= '${date_as_of}' AND
     (O."�����" IS NULL OR (O."�����" >= '${date_as_of}'))
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
    $detail_cols = array('����','����ñ��','����',
			 '����', '��ˡ','�굻','�����ˡ','����¾������',
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
//11-1-2014
//print $stmt;
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

    $old_order_oids = $this->find_effective_orders($this->data["startdate"]);
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
/*
    if ($_mx_link_mediserve) {
      $st = _validate_mediserve_check($v, $old_order_details, $opt);
      if ($st) {
	$this->err($st);
	$bad += 1;
      }
    }
*/

    $fatal_errs = 0; // NEEDSWORK
    $this->error_override_allowed = (!$force && $fatal_errs == 0);
    
    if ($bad == 0 || (($fatal_errs == 0) && $force)) {
      $this->errmsg = '';
      return 'ok';
    }
    return '';
  }

//11-01-2014 added simple_object_ppa_edit::commit($force);

function commit($force=NULL) {

//simple_object_ppa_edit::commit($force);

$abc=$this->data;
//print_r($abc);

 
//important!!!
$p_oid=$this->so_config['Patient_ObjectID']; 
//
$date = $this->data["����ǯ����"];
//$p_oid= $this->data["����"];
$startdate=$this->data["startdate"];
$detail = $this->data["DETAILS"];
print $date;
print $p_oid;
print $startdate;
print $pid;
 

 
 
$ocont="";
 
for ($i=0;$i<count($detail);$i++){
$cc=$detail[$i]["�쥻�ץ��Ż����������ƥ������̾"];
$pp=$detail[$i]["����"];
$aa=$detail[$i]["����ñ��ñ��"];
$dd=$detail[$i]["����"];
$ocont=$ocont." ����=".$cc." ����=".$pp." ñ��=".$aa." ����=".$dd.
"  |\n";
}

$ocont="------------------\n"."����"."\n".$ocont;
print $ocont;

$db = mx_db_connect();

//insert into karte

 
$stmt10 = <<<SQL
select * from "����ƥǥ�ɽ" where "����"='$date' and "����"=$p_oid and 
	"Superseded" is null;
SQL;
 


$rs0 = mx_db_fetch_all($db, $stmt10);

if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "����ƥǥ�ɽ" ("����", "����","P") values ($p_oid,'$date','$ocont');
SQL;
 
//print $stmt11;

if (pg_query($db, $stmt11)){

	}
else {
print '<p > karte insert DB access error</p>';
die;
	}

 }

else{ 
$ocont2="";
 for ($i=0;$i<count($rs0);$i++){	
 $pp=$rs0[$i]["P"];
 $idd=$rs0[$i]["ID"];
// print $pp."=";
$ocont2=$pp.'\r\n'.'-(updated to)-'."\n".$ocont;


$stmt1 = <<<SQL
   	update  "����ƥǥ�ɽ" set "P"='$ocont2' where "����"='$date' and "����"=$p_oid and 
	"Superseded" is null and "ID"=$idd
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > karte update DB access error</p>';
die;
}

}

} //end else


//end of insert into karte
//added to comment 11-01-2014
$this->data["Comment"]=$ocont;
simple_object_ppa_edit::commit($force);

//
    $date = $this->data['ExecDate'];
    $match = array();
    if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);
	    mx_kick_claim_if_by_poid($db, $this->so_config['Patient_ObjectID'],
				     $date);
    }



  }



function edit_tweak() {
$this->data['recorded'] =  date("Y-m-d H:i:s");

}

}
?>
