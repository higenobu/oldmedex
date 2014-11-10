<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record2_base_stmt = 'select "ObjectID" from "�������"';

$_lib_u_ct_record2_cfg = array
(
 'TABLE' => '�������',
 'COLS' => array('����������'),
 'ECOLS' => array('����������'),
 'ICOLS' => array('����������'),
 'HSTMT' => $_lib_u_ct_record2_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record2_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

 //���
 $_lib_u_ct_record2_ecols = array
 (
  "(�ɾ� 1)",
  "(�ɾ� 2)",
  "(�ɾ� 3)",
  "(�ɾ� 4)",
  "(�ɾ� 5)",
  "(�ɾ� 6)",
  "(�ɾ� 7)",
  "(�ɾ� 8)",
  "(�ɾ� 9)",
  "(�ɾ�10)",
  "(�ɾ�11)",
  "(�ɾ�12)",
  "(�ɾ�13)",
  "(�ɾ�14)",
  "(�ɾ�15)",
  "(�ɾ�16)",
  );

 $_lib_u_ct_record2_ecols_template = array(
					  array("Column" => "����̾",
						'Draw' => 'text',
						"Rowspan" => 3  // 3 rows edit field
						),
					  array("Column" => "ȯ����",
						'Draw' => 'text',
						),
					  array("Column" => "Grade",
						'Draw' => 'radion',
						'Enum' => array('1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								),
						'Rowspan' => 3
						),
					  array("Column" => "������",
						'Draw' => 'radion',
						'Enum' => array('1' => '1.�����',
								'2' => '2.����',
								),
						),
					  array('Column' => "����",
						'Draw' => 'radion',
						'Enum' => array('1' => '1.�ʤ�',
								'2' => '2.����',
								),
						),
					  array('Column' => 'ž����ǧ��',
						'Draw' => 'text',
						),
					  array('Column' => 'ž��',
						'Draw' => 'radion',
						'Enum' => array('1' => '����',
								'2' => '�ڲ�',
								'3' => '����',
								'4' => '����',
								),
						'Rowspan' => 3
						),
					  array('Column' => '���̴ط�',
						'Draw' => 'radion',
						'Enum' => array('1' => '��Ϣ����',
								'2' => '¿ʬ��Ϣ����',
								'3' => '��Ϣ���뤫�⤷��ʤ�',
								'4' => '¿ʬ��Ϣ�ʤ�',
								'5' => '��Ϣ�ʤ�',
								),
						'Rowspan' => 3
						),
					  array('Column' => '4��5����ͳ',
						'Draw' => 'radion',
						'Enum' => array('1' => '��ʻ��',
								'2' => '����¾',
								),
						'Rowspan' => 3
						),
					  array('Column' => '������',
						'Draw' => 'text',
						'Rowspan' => 3,
						'Newline' => 1
						),
					  array("Column" => "ȯ������",
						'Rowspan' => 2,
						),
					  array('Column' => "��������ͳ",
						'Draw' => 'text',
						'Rowspan' =>2
						),
					  array('Column' => 'ʻ����',
						'Draw' => 'check',
						'Rowspan' =>2
						),
					  array('Column' => 'ž����ǧ����',
						'Draw' => 'text',
						'Rowspan' => 2
						),
					  );
 
function count_elements($a) {
  $c = 0;
  foreach($a as $k => $v)
    if(is_array($v))
      $c += count_elements($v);
    else
      $c += 3; // 2 rows edit field
  return $c;
}

function get_subset($key, $a) {
  $o = array();
  foreach ($a as $k => $v) {
    if(strstr($v['Column'], $key)) {
      $o[$k] = $v;
    }
  }
  return $o;
}

function copy_ecols2(&$cfg, $col) {
  global $_lib_u_ct_record2_ecols_template;
  foreach($_lib_u_ct_record2_ecols_template as $k => $v) {
    $v['Column'] = $col . '|' . $v['Column'];
    $cfg['ECOLS'][] = $v;
  }
}

function _lib_u_ct_annotate_cfg(&$cfg) {
  global $_lib_u_ct_record2_ecols;
  
  $cfg['ECOLS'] = array();
  //����ܡ�ɬ��array��
  foreach($_lib_u_ct_record2_ecols as $dk => $dv)
    if(is_array($dv))
      foreach($dv as $ck => $cv)
	if(is_array($cv))
	  foreach($cv as $sk => $sv)
	    copy_ecols2(&$cfg, $dk . '|' . $ck . '|' . $sv);
	else
	  copy_ecols2(&$cfg, $dk . '||' .$cv);
    else
      copy_ecols2(&$cfg, $dv);
      
}

function _lib_u_ct_record2_fetch_data($it, $oid) {
  global $_lib_u_ct_record2_cfg;
  global $_lib_u_ct_record2_ecols;
  global $_lib_u_ct_record2_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record2_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT * FROM "�������ǡ���" WHERE "�������"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    # bogus
    $i = 0;
    foreach($d as $row) {
      if($row['ȯ������'])
	list($row['ȯ����'],$row['ȯ������']) = explode(' ', $row['ȯ������']);
      if($row['ž����ǧ����'])
	list($row['ž����ǧ��'],$row['ž����ǧ����']) = explode(' ', $row['ž����ǧ����']);
      unset($row['ȯ������']);
      unset($row['ž����ǧ����']);
      $base = $_lib_u_ct_record2_ecols[$i++];
      foreach($_lib_u_ct_record2_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }
  return $data;
}

class ct_record2_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record2_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record2_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record2_cfg;
    _lib_u_ct_annotate_cfg(&$cfg);
    $this->app = $app;
    $this->data['����'] = $this->app->loo->CT_ObjectID;
    $this->data['����������'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ���������� id.  What I really want is ������� id.
    $stmt = 'select "ObjectID" from "�������" where "Superseded" is NULL and "����������"=' . $chiken_id;
    //print $stmt;
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD2 FOUND for chiken_id=$chiken_id";
      return $this->anew(null);
    }
    
    $this->id = $r["ObjectID"];
    $this->data = $this->fetch_data($this->id);
    $this->data['����������'] = $chiken_id;
    $this->annotate_row_data(&$this->data);
    $this->Subpick = NULL;
    $this->page = 0;
    $this->edit_tweak();
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;

    //var_dump($this->data);

  }

  function fetch_data($id) {
    $d = _lib_u_ct_record2_fetch_data($this, $id);
    $d['����������'] = $this->app->sod->chosen();
    $d['CreatedBy'] = $this->u;
    return $d;
  }

  function data_compare($curr, $data) {
    if(is_null($curr))
       return true;
    $differs = false;
    foreach($curr as $k => $v) {
      if($data[$k] != $v) {
	//print "$k differs (org: $v  form: ".$data[$k]."<br>";
	$differs = true;
	break;
      }
    }
    return $differs;
  }

  function annotate_form_data(&$data) {
    $data['����������'] = $this->app->sod->chosen();
    $data['CreatedBy'] = $this->u;
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "�������ǡ���" SET "�������" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "�������" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }

    foreach($this->data as $k=>$v) {
      if($k == '����������' || $k == 'CreatedBy')
	continue;
      $a = explode('|', $k);
      if(!is_array($a))
	continue;
      $col = array_pop($a);
      $tbl = implode('|', $a);
      $h[$tbl][$col] = $v;
      list($big,$mid,$item) = $a;
      $h[$tbl]['�������'] = $id;
    }

    foreach($h as $r) {
      if($r['ȯ����'])
	$r['ȯ������'] = sprintf("%s %s", $r['ȯ����'], $r['ȯ������']);
      if($r['ž����ǧ��'])
	$r['ž����ǧ����'] = sprintf("%s %s", $r['ž����ǧ��'], $r['ž����ǧ����']);
      unset($r['ȯ����']);
      unset($r['ȯ������']);
      unset($r['ž����ǧ��']);
      unset($r['ž����ǧ����']);

      $stmt = 'INSERT INTO "�������ǡ���" ("' .
	implode('","', array_keys($r)) . '") VALUES (';

      $vv = array();
      foreach($r as $k=>$v)
	$vv[] = mx_db_sql_quote($v);
      $stmt .= implode(',', $vv) .")";
      //print $stmt."<br>";
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }


  //-------------------------------------------------------------
  // drawing stuff
  //-------------------------------------------------------------

  function draw_body_3($d, $ecols, $epages, $span) {
    $this->_draw_header();
    $this->_draw_body(null,null,null);
  }

  function _draw_header() {
    // draw header
    print "<thead>";
    print "<tr><th colspan=10>ͭ������(��¾�оɾ�)���</th></tr>";
    print "<tr><th colspan=2>ͭ������̵ͭ</th><th colspan=8>�ʤ�������</th></tr>";
    print "<tr><th>ͭ������</th><th>ȯ������ȯ������</td><th>Grade</th><th>������</th><th>����</th><th>ž����ǧ������ǧ����</th><th>ž��</th><th colspan=2>���̴ط�</th><th>������</th></tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record2_ecols;
    $first = true;
    $colhead = false;
    
    foreach($_lib_u_ct_record2_ecols as $k => $v) {
      # 0 => shoujyou 1
      # 1 => shoujyou 2...
      if(!$first) {
	$first = false;
	print "</tr>";
      }
      print "<tr>";
      if($colhead)
	printf("<td rowspan=%d>%s</td>", count_elements($v), mx_vstring($k));
      $count = 0;
      if(is_array($v)) {
	foreach($v as $k2 => $v2) {
	  if(is_array($v2)) {
	    printf("<td rowspan=%d>%s</td>", count_elements($v2),
		   mx_vstring($k2));
	    $count2 = 0;
	    foreach($v2 as $k3 => $v3) {
	      // 2 rows edit field
	      printf("<td rowspan=3>%s</td>", $v3);
	      // now draw ECOLS2, which we really need to draw
	      $ec = get_subset("$k|$k2|$v3", $this->so_config['ECOLS']);
	      $this->_draw_body3($this->data, $ec, $epages, $span);
	      printf("</tr>\n");
	      if($count2 < count($v2) - 1) 
		print "<tr>";
	      $count2 += 1;
	    }
	  }
	  else {
	    // 2 rows edit field
	    printf("<td rowspan=3 colspan=1>%s</td>", $v2);
	    // now draw ECOLS2, which we really need to draw
	    $ec = get_subset("$k||$v2", $this->so_config['ECOLS']);
	    $this->_draw_body3($this->data, $ec, $epages, $span);
	  }
	  
	  print "</tr>\n";
	  if($count < count($v) - 1) 
	    print "<tr>";
	  $count += 1;
	}
      }
      else {
	// now draw ECOLS2, which we really need to draw
	$ec = get_subset("$v", $this->so_config['ECOLS']);
	$this->_draw_body3($this->data, $ec, $epages, $span);
	print "</tr>\n";
	if($count < count($_lib_u_ct_record2_ecols) - 1) 
	  print "<tr>";
	$count += 1;
      }
    }
  }

  function _draw_body3($d, $ecols, $epages, $span) {
    foreach ($ecols as $desc) {
      $rowspan = '';
      if($desc['Rowspan'])
	$rowspan = ' rowspan="'.$desc['Rowspan'].'" ';

      printf('<td %s>', $rowspan);
      $this->draw_body_atom($desc, $d);
      print '</td>';
      if($desc['Newline'])
	print '</tr><tr>';
    }
  }

}
?>
