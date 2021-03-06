<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$_lib_u_ct_record2_base_stmt = 'select "ObjectID" from "治験総括"';

$_lib_u_ct_record2_cfg = array
(
 'TABLE' => '治験総括',
 'COLS' => array('治験オーダ'),
 'ECOLS' => array('治験オーダ'),
 'ICOLS' => array('治験オーダ'),
 'HSTMT' => $_lib_u_ct_record2_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record2_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

 //総括
 $_lib_u_ct_record2_ecols = array
 (
  "(症状 1)",
  "(症状 2)",
  "(症状 3)",
  "(症状 4)",
  "(症状 5)",
  "(症状 6)",
  "(症状 7)",
  "(症状 8)",
  "(症状 9)",
  "(症状10)",
  "(症状11)",
  "(症状12)",
  "(症状13)",
  "(症状14)",
  "(症状15)",
  "(症状16)",
  );

 $_lib_u_ct_record2_ecols_template = array(
					  array("Column" => "事象名",
						'Draw' => 'text',
						"Rowspan" => 3  // 3 rows edit field
						),
					  array("Column" => "発現日",
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
					  array("Column" => "重篤度",
						'Draw' => 'radion',
						'Enum' => array('1' => '1.非重篤',
								'2' => '2.重篤',
								),
						),
					  array('Column' => "処置",
						'Draw' => 'radion',
						'Enum' => array('1' => '1.なし',
								'2' => '2.あり',
								),
						),
					  array('Column' => '転帰確認日',
						'Draw' => 'text',
						),
					  array('Column' => '転帰',
						'Draw' => 'radion',
						'Enum' => array('1' => '回復',
								'2' => '軽快',
								'3' => '不変',
								'4' => '悪化',
								),
						'Rowspan' => 3
						),
					  array('Column' => '因果関係',
						'Draw' => 'radion',
						'Enum' => array('1' => '関連あり',
								'2' => '多分関連あり',
								'3' => '関連あるかもしれない',
								'4' => '多分関連なし',
								'5' => '関連なし',
								),
						'Rowspan' => 3
						),
					  array('Column' => '4・5の理由',
						'Draw' => 'radion',
						'Enum' => array('1' => '合併症',
								'2' => 'その他',
								),
						'Rowspan' => 3
						),
					  array('Column' => 'コメント',
						'Draw' => 'text',
						'Rowspan' => 3,
						'Newline' => 1
						),
					  array("Column" => "発現時刻",
						'Rowspan' => 2,
						),
					  array('Column' => "重篤度理由",
						'Draw' => 'text',
						'Rowspan' =>2
						),
					  array('Column' => '併用薬',
						'Draw' => 'check',
						'Rowspan' =>2
						),
					  array('Column' => '転帰確認時刻',
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
  //大項目（必ずarray）
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
  $stmt = 'SELECT * FROM "治験総括データ" WHERE "治験総括"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    # bogus
    $i = 0;
    foreach($d as $row) {
      if($row['発現日時'])
	list($row['発現日'],$row['発現時刻']) = explode(' ', $row['発現日時']);
      if($row['転帰確認日時'])
	list($row['転帰確認日'],$row['転帰確認時刻']) = explode(' ', $row['転帰確認日時']);
      unset($row['発現日時']);
      unset($row['転帰確認日時']);
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
    $this->data['治験'] = $this->app->loo->CT_ObjectID;
    $this->data['治験オーダ'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means 治験オーダ id.  What I really want is 治験総括 id.
    $stmt = 'select "ObjectID" from "治験総括" where "Superseded" is NULL and "治験オーダ"=' . $chiken_id;
    //print $stmt;
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD2 FOUND for chiken_id=$chiken_id";
      return $this->anew(null);
    }
    
    $this->id = $r["ObjectID"];
    $this->data = $this->fetch_data($this->id);
    $this->data['治験オーダ'] = $chiken_id;
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
    $d['治験オーダ'] = $this->app->sod->chosen();
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
    $data['治験オーダ'] = $this->app->sod->chosen();
    $data['CreatedBy'] = $this->u;
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "治験総括データ" SET "治験総括" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "治験総括" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }

    foreach($this->data as $k=>$v) {
      if($k == '治験オーダ' || $k == 'CreatedBy')
	continue;
      $a = explode('|', $k);
      if(!is_array($a))
	continue;
      $col = array_pop($a);
      $tbl = implode('|', $a);
      $h[$tbl][$col] = $v;
      list($big,$mid,$item) = $a;
      $h[$tbl]['治験総括'] = $id;
    }

    foreach($h as $r) {
      if($r['発現日'])
	$r['発現日時'] = sprintf("%s %s", $r['発現日'], $r['発現時刻']);
      if($r['転帰確認日'])
	$r['転帰確認日時'] = sprintf("%s %s", $r['転帰確認日'], $r['転帰確認時刻']);
      unset($r['発現日']);
      unset($r['発現時刻']);
      unset($r['転帰確認日']);
      unset($r['転帰確認時刻']);

      $stmt = 'INSERT INTO "治験総括データ" ("' .
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
    print "<tr><th colspan=10>有害事象(自他覚症状)総括</th></tr>";
    print "<tr><th colspan=2>有害事象有無</th><th colspan=8>なし、あり</th></tr>";
    print "<tr><th>有害事象</th><th>発現日・発現時刻</td><th>Grade</th><th>重篤度</th><th>処置</th><th>転帰確認日・確認時刻</th><th>転帰</th><th colspan=2>因果関係</th><th>コメント</th></tr>";
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
