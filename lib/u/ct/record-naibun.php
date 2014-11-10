<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_naibun_base_stmt = 'select "ObjectID" from "������ʬ��"';

$_lib_u_ct_record_naibun_cfg = array
(
 'TABLE' => '������ʬ��',
 'COLS' => array('����������'),
 'ECOLS' => array('����������'),
 'ICOLS' => array('����������'),
 'HSTMT' => $_lib_u_ct_record_naibun_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_naibun_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

 //
 $_lib_u_ct_record_naibun_ecols = array
 (
  0 => "��������",
  1 => "��Ϳ������",
  2 =>"��Ϳ24���ָ�",
  3 =>"��Ϳ48���ָ�",
  4 =>"ͽ��1",
  5 =>"ͽ��2",
  6 =>"ͽ��3",
  7 =>"ͽ��4",
  );

 $_lib_u_ct_record_naibun_ecols_template = array(
					  array("Column" => "ǯ����",
						'Draw' => 'date',
						#'Rowspan' => 1 // 2 rows edit field
						),
					  array("Column" => "E2",
						'Draw' => 'text',
						),
					  array("Column" => "E2_X",
						'Draw' => 'text',
						),
					  array("Column" => "FSH",
						'Draw' => 'text',
						),
					  array("Column" => "FSH_X",
						'Draw' => 'text',
						),
					  array("Column" => "LH",
						'Draw' => 'text',
						),
					  array("Column" => "LH_X",
						'Draw' => 'text',
						),
					  array("Column" => "TSH",
						'Draw' => 'text',
						),
					  array("Column" => "TSH_X",
						'Draw' => 'text',
						),
					  array("Column" => "�����������",
						'Draw' => 'text',
						),
					  array("Column" => "�����������_X",
						'Draw' => 'text',
						),
					  array("Column" => "ACTH",
						'Draw' => 'text',
						),
					  array("Column" => "ACTH_X",
						'Draw' => 'text',
						'Newline' =>1
						),
					  );
 
function _lib_u_ct_record_naibun_fetch_data($it, $oid) {
  global $_lib_u_ct_record_naibun_cfg;
  global $_lib_u_ct_record_naibun_ecols;
  global $_lib_u_ct_record_naibun_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record_naibun_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT * FROM "������ʬ��" s, "������ʬ��ǡ���" d WHERE s."ObjectID" = d."������ʬ��" and s."ObjectID"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    # bogus
    $i = 0;
    foreach($d as $row) {
      $base = $_lib_u_ct_record_naibun_ecols[$i++];
      foreach($_lib_u_ct_record_naibun_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }
  return $data;
}

class ct_record_naibun_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_naibun_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_naibun_cfg;
    global $_lib_u_ct_record_naibun_ecols;
    global $_lib_u_ct_record_naibun_ecols_template;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_naibun_cfg;
    _lib_u_ct_annotate_cfg(&$cfg, $_lib_u_ct_record_naibun_ecols,
			   $_lib_u_ct_record_naibun_ecols_template);
    $this->app = $app;
    $this->data['����'] = $this->app->loo->CT_ObjectID;
    $this->data['����������'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ���������� id.  What I really want is ������ʬ�� id.
    $stmt = 'select "ObjectID" from "������ʬ��" where "Superseded" is NULL and "����������"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_NAIBUN FOUND for chiken_id=$chiken_id";
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
    $d = _lib_u_ct_record_naibun_fetch_data($this, $id);
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
      $stmt = ('UPDATE "������ʬ��ǡ���" SET "������ʬ��" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "������ʬ��" = ' .
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
      $h[$tbl]['������ʬ��'] = $id;
    }

    foreach($h as $r) {
      $stmt = 'INSERT INTO "������ʬ��ǡ���" ("' .
	implode('","', array_keys($r)) . '") VALUES (';

      $vv = array();
      foreach($r as $k=>$v)
	$vv[] = mx_db_sql_quote($v);
      $stmt .= implode(',', $vv) .")";
      #print $stmt."<br>";
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
    print "<tr><th colspan=15>��ʬ�縡��</th></tr>";
    print "<tr><th>����</th><th>ǯ����</td><th colspan=2>�����ȥ饸������(E2) (pg/mL)</td><th colspan=2>FSH (mIU/mL)</th><th colspan=2>LH (mIU/mL)</th><th colspan=2>TSH (mIU/mL)</th><th colspan=2>����������� (��g/dL)</th><th colspan=2>ACTH (pg/mL)</th></tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record_naibun_ecols;
    $nrows_edit = 1;
    $first = true;
    $colhead = true;
    
    foreach($_lib_u_ct_record_naibun_ecols as $k => $v) {
      # 0 => shoujyou 1
      # 1 => shoujyou 2...
      if(!$first) {
	$first = false;
	print "</tr>";
      }
      print "<tr>";
      if($colhead)
	printf("<td rowspan=%d>%s</td>", count_elements($v, $nrows_edit), $v);
      $count = 0;
      if(is_array($v)) {
	foreach($v as $k2 => $v2) {
	  print $k2;
	  if(is_array($v2)) {
	    printf("<td rowspan=%d>%s</td>", count_elements($v2, $nrows_edit),
		   mx_vstring($k2));
	    $count2 = 0;
	    foreach($v2 as $k3 => $v3) {
	      // 2 rows edit field
	      printf("<td rowspan=1>%s</td>", $v3);
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
	    printf("<td rowspan=1 colspan=1>%s</td>", $v2);
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
	if($count < count($_lib_u_ct_record_naibu_ecols) - 1) 
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
      if($desc['Newline']) {
	print '</tr><tr>';
      }
    }
  }

}
?>
