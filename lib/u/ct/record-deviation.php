<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_deviation_base_stmt = 'select "ObjectID" from "������ưȽ��"';

$_lib_u_ct_record_deviation_cfg = array
(
 'TABLE' => '������ưȽ��',
 'COLS' => array('����������'),
 'ECOLS' => array('����������'),
 'ICOLS' => array('����������'),
 'HSTMT' => $_lib_u_ct_record_deviation_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_deviation_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

$_lib_u_ct_record_deviation_ecols = array
(
 "��ճ�Ū����" => array("�����",
			 "�ַ���",
			 "�إ⥰��ӥ���",
			 "�إޥȥ���å���",
			 "�쾮��",
			 "�־��ַ���",
			 "�����ʬ��" => array("�����",
						 "������",
						 "�������",
						 "ñ��",
						 "���ѵ�"),
			 "�ե��֥�Ρ�����",
			 "������ȥ��ӥ�"),
 "��������ظ���" => array("������",
			   "��������",
			   "����֥ߥ�",
			   "��ӥ��ӥ�",
			   "�ǣϣ�",
			   "�ǣУ�",
			   "���̣�",
			   "����",
			   "���쥹�ƥ���",
			   "�ȣģ̡ݥ��쥹�ƥ���",
			   "�̣ģ̡ݥ��쥹�ƥ���",
			   "�ȥꥰ�ꥻ�饤��",
			   "�����",
			   "Ǣ��",
			   "�£գ�",
			   "���쥢���˥�",
			   "�Σ�",
			   "��",
			   "�ã�",
			   "�ã�",
			   "��"
			   ),
 "Ǣ����" => array("����",
		   "��",
		   "����ӥ�Ρ�����",
		   "����ȿ��",
		   "��1�ޥ�������ӥ��",
		   "��1�ޥ�������ӥ��",
		   "Ǣ����"
		   )
 );
 
 $_lib_u_ct_record_deviation_ecols_template = array(
					  array('Column' => "�۾��̵ͭ",
						'Draw' => "radio",
						'Enum' => array(NULL => '̤Ƚ��',
								'1' => '�ʤ�',
								'2' => '����'),
						'Rowspan' => 2   // 2 rows edit field
						),
					  array('Column' => "�۾�ι��㡦�徺",
						'Draw' => 'check',
						'Caption' => '�徺',
						'Enum' => array('1' => '�徺')
						),
					  
					  array('Column' => "�۾���ư��̵ͭ���徺",
						'Draw' => "enum",
						'Enum' => array(NULL => '̤Ƚ��',
								'1' => '�ʤ�',
								'2' => '����'),
						),
					  array('Column' => "�۾���ư�ʤ�����ͳ���徺",
						'Draw' => "radio",
						'Enum' => array(NULL => '̤Ƚ��',
								'1' => '����Ū��ư',
								'2' => '�︡�Ը�ͭ��',
								'3' => '����¾'),
						),
					  array('Column' => "�۾���ư�ʤ�����ͳ�ƥ����ȡ��徺",
						'Draw' => 'text',
						'Newline' => 1
						),
					  
					  array('Column' => "�۾�ι��㡦����",
						'Draw' => 'check',
						'Caption' => '����',
						'Enum' => array('1' => '����')
						),
					  
					  array('Column' => "�۾���ư��̵ͭ������",
						'Draw' => "enum",
						'Enum' => array(NULL => '̤Ƚ��',
								'1' => '�ʤ�',
								'2' => '����'),
						),
					  array('Column' => "�۾���ư�ʤ�����ͳ������",
						'Draw' => "radio",
						'Enum' => array(NULL => '̤Ƚ��',
								'1' => '����Ū��ư',
								'2' => '�︡�Ը�ͭ��',
								'3' => '����¾'),
						),
					  array('Column' => "�۾���ư�ʤ�����ͳ�ƥ����ȡ�����",
						'Draw' => 'text',
						),
					  
					  );


function _lib_u_ct_record_deviation_fetch_data($it, $oid) {
  global $_lib_u_ct_record_deviation_cfg;
  global $_lib_u_ct_record_deviation_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record_deviation_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT "��ʬ��", "��ʬ��", "����̾", d.* FROM "������������" k, "������ưȽ��ǡ���" d WHERE k."ObjectID" = d."��������" and "������ưȽ��"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach($d as $row) {
      $base = $row['��ʬ��'] . '|' . $row['��ʬ��'] . '|' . $row['����̾'];
      foreach($_lib_u_ct_record_deviation_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }
  return $data;
}

class ct_record_deviation_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_deviation_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_deviation_cfg;
    global $_lib_u_ct_record_deviation_ecols;
    global $_lib_u_ct_record_deviation_ecols_template;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_deviation_cfg;
    _lib_u_ct_annotate_cfg(&$cfg, $_lib_u_ct_record_deviation_ecols,
			   $_lib_u_ct_record_deviation_ecols_template);
    $this->app = $app;
    $this->data['����'] = $this->app->loo->CT_ObjectID;
    $this->data['����������'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ���������� id.  What I really want is ������ưȽ�� id.
    $stmt = 'select "ObjectID" from "������ưȽ��" where "Superseded" is NULL and "����������"=' . $chiken_id;
    //print $stmt;
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_UNDESIRABLE FOUND for chiken_id=$chiken_id";
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
    $d = _lib_u_ct_record_deviation_fetch_data($this, $id);
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
      $stmt = ('UPDATE "������ưȽ��ǡ���" SET "������ưȽ��" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "������ưȽ��" = ' .
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
      $h[$tbl]['��������'] = $this->get_tid($big,$mid,$item);
      $h[$tbl]['������ưȽ��'] = $id;
    }

    foreach($h as $r) {
      $stmt = 'INSERT INTO "������ưȽ��ǡ���" ("' .
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

  function get_tid($big, $mid, $item) {
      $stmt = 'select "ObjectID" from "������������" where ';
      $w = array();
      if($big)
	$w[] = '"��ʬ��"='. mx_db_sql_quote($big);

      if($mid)
	$w[] = '"��ʬ��"='. mx_db_sql_quote($mid);
      if($item)
	$w[] = '"����̾"='. mx_db_sql_quote($item);
      $stmt .= implode(' AND ' , $w);
      $tid = mx_db_fetch_single(mx_db_connect(), $stmt);
      if(!$tid)
	print "MISSING: $big, $mid, $item";
      return $tid['ObjectID'];
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
    print "<tr><th colspan=8>�׾����� �۾���ư��Ƚ��</th></tr>";
    print "<tr><th colspan=3>��������</th><th>�۾��̵ͭ</th><th>�۾�ι���</th><th>�۾���ư��̵ͭ</th><th colspan=2>�۾���ư�ʤ�����ͳ</th></tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record_deviation_ecols;
    $nrows_edit = 2;

    $first = true;
    foreach($_lib_u_ct_record_deviation_ecols as $k => $v) {
      if(!$first) {
	$first = false;
	print "</tr>";
      }
      print "<tr>";
      printf("<td rowspan=%d>%s</td>", count_elements($v, $nrows_edit),
	     mx_vstring($k));
      $count = 0;
      foreach($v as $k2 => $v2) {
	if(is_array($v2)) {
	  printf("<td rowspan=%d>%s</td>", count_elements($v2, $nrows_edit),
		 mx_vstring($k2));
	  $count2 = 0;
	  foreach($v2 as $k3 => $v3) {
	    // 2 rows edit field
	    printf("<td rowspan=2>%s</td>", $v3);
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
	  printf("<td rowspan=2 colspan=2>%s</td>", $v2);
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
