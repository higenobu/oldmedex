<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_heiyou_base_stmt = 'select "ObjectID" from "¼£¸³Ê»ÍÑ"';

$_lib_u_ct_record_heiyou_cfg = array
(
 'TABLE' => '¼£¸³Ê»ÍÑ',
 'COLS' => array('¼£¸³¥ª¡¼¥À'),
 'ECOLS' => array('¼£¸³¥ª¡¼¥À'),
 'ICOLS' => array('¼£¸³¥ª¡¼¥À'),
 'HSTMT' => $_lib_u_ct_record_heiyou_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_heiyou_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

 //
 $_lib_u_ct_record_heiyou_ecols = array
 (
  0 => "(1)",
  1 => "(2)",
  2 => "(3)",
  3 => "(4)",
  4 => "(5)",
  5 => "(6)",
  6 => "(7)",
  7 => "(8)",
  8 => "(9)",
  9 => "(10)",
  10=> "(11)",
  11=> "(12)",
  );

 $_lib_u_ct_record_heiyou_ecols_template = array(
					  array("Column" => "¼£ÎÅÆâÍÆ(ÌôºŞÌ¾)",
						'Draw' => 'text',
						"Rowspan" => 4  // 4 rows edit field
						),
					  array("Column" => "ÅêÍ¿´ü´Ö(³«»Ï)",
						'Draw' => 'date',
						),
					  array("Column" => "ÅêÍ¿´ü´Ö(½ªÎ»)",
						'Draw' => 'date',
						),
					  array("Column" => "1ÆüÅêÍ¿ÎÌ",
						'Draw' => 'text',
						),
					  array("Column" => "ÅêÍ¿·ĞÏ©",
						'Draw' => 'text',
						'Rowspan' => 4
						),
					  array("Column" => "Ê»ÍÑÌÜÅª1",
						'Draw' => 'check',
						'Caption' => '¹çÊ»¾É',
						'Enum' => array('1' => 'XXX')

						),
					  array("Column" => "Ê»ÍÑÆâÍÆ1",
						'Draw' => 'text',
						),
					  array("Column" => "¥³¥á¥ó¥È",
						'Draw' => 'text',
						'Rowspan' => 4,
						'Newline' =>1
						),

					  array('Column' => "Á°·ÑÂ³",
						'Draw' => 'check',
						'Rowspan' => 3,
						'Caption' => '¼£¸³Á°¤è¤ê·ÑÂ³',
						'Enum' => array('1' => 'XXX')

						),
					  array('Column' => '¸å·ÑÂ³',
						'Draw' => 'check',
						'Rowspan' => 3,
						'Caption' => '¼£¸³½ªÎ»¸å¤â·ÑÂ³',
						'Enum' => array('1' => 'XXX')

						),
					  array('Column' => 'ÆÜÍÑ',
						'Draw' => 'check',
						'Rowspan' => 3,
						'Caption' => 'ÆÜÍÑ(´ü´ÖÆâÅ¬»ş)',
						'Enum' => array('1' => 'XXX')

						),
					  array("Column" => "Ê»ÍÑÌÜÅª2",
						'Draw' => 'check',
						'Caption' => 'Í­³²»ö¾İ',
						'Rowspan' => 2,
						'Enum' => array('1' => 'XXX')

						),
					  array("Column" => "Ê»ÍÑÆâÍÆ2",
						'Draw' => 'text',
						'Newline' => 1,
						),

					  array("Column" => "Ê»ÍÑÆâÍÆ22",
						'Draw' => 'text',
						'Newline' => 1,
						),

					  array("Column" => "Ê»ÍÑÌÜÅª4",
						'Draw' => 'check',
						'Enum' => array('1' => 'XXX'),
						'Caption' => '¤½¤ÎÂ¾',
						),
					  array("Column" => "Ê»ÍÑÆâÍÆ4",
						'Draw' => 'text',
						),
					  );
 
function _lib_u_ct_record_heiyou_fetch_data($it, $oid) {
  global $_lib_u_ct_record_heiyou_cfg;
  global $_lib_u_ct_record_heiyou_ecols;
  global $_lib_u_ct_record_heiyou_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record_heiyou_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT * FROM "¼£¸³Ê»ÍÑ" s, "¼£¸³Ê»ÍÑ¥Ç¡¼¥¿" d WHERE s."ObjectID" = d."¼£¸³Ê»ÍÑ" and s."ObjectID"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    # bogus
    $i = 0;
    foreach($d as $row) {
      $base = $_lib_u_ct_record_heiyou_ecols[$i++];
      foreach($_lib_u_ct_record_heiyou_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }
  return $data;
}

class ct_record_heiyou_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_heiyou_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_heiyou_cfg;
    global $_lib_u_ct_record_heiyou_ecols;
    global $_lib_u_ct_record_heiyou_ecols_template;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_heiyou_cfg;
    _lib_u_ct_annotate_cfg(&$cfg, $_lib_u_ct_record_heiyou_ecols,
			   $_lib_u_ct_record_heiyou_ecols_template);
    $this->app = $app;
    $this->data['¼£¸³'] = $this->app->loo->CT_ObjectID;
    $this->data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ¼£¸³¥ª¡¼¥À id.  What I really want is ¼£¸³Ê»ÍÑ id.
    $stmt = 'select "ObjectID" from "¼£¸³Ê»ÍÑ" where "Superseded" is NULL and "¼£¸³¥ª¡¼¥À"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_HEIYOU FOUND for chiken_id=$chiken_id";
      return $this->anew(null);
    }
    
    $this->id = $r["ObjectID"];
    $this->data = $this->fetch_data($this->id);
    $this->data['¼£¸³¥ª¡¼¥À'] = $chiken_id;
    $this->annotate_row_data(&$this->data);
    $this->Subpick = NULL;
    $this->page = 0;
    $this->edit_tweak();
    $this->origin = $this->fetch_origin_info();
    $this->chosen = 1;

    //var_dump($this->data);

  }

  function fetch_data($id) {
    $d = _lib_u_ct_record_heiyou_fetch_data($this, $id);
    $d['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
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
    $data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    $data['CreatedBy'] = $this->u;
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "¼£¸³Ê»ÍÑ¥Ç¡¼¥¿" SET "¼£¸³Ê»ÍÑ" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "¼£¸³Ê»ÍÑ" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }

    foreach($this->data as $k=>$v) {
      if($k == '¼£¸³¥ª¡¼¥À' || $k == 'CreatedBy')
	continue;
      $a = explode('|', $k);
      if(!is_array($a))
	continue;
      $col = array_pop($a);
      $tbl = implode('|', $a);
      $h[$tbl][$col] = $v;
      list($big,$mid,$item) = $a;
      $h[$tbl]['¼£¸³Ê»ÍÑ'] = $id;
    }

    foreach($h as $r) {
      $stmt = 'INSERT INTO "¼£¸³Ê»ÍÑ¥Ç¡¼¥¿" ("' .
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
    print "<tr><th colspan=8>Ê»ÍÑÌô¡¦Ê»ÍÑÎÅË¡</th></tr>";
    print "<tr><th colspan=1>Ê»ÍÑÎÅË¡¤ÎÍ­Ìµ</th><th colspan=7>¤Ê¤·¡¢¤¢¤ê</th></tr>";
    print "<tr><th>¼£ÎÅÆâÍÆ(ÌôºŞÌ¾)</th><th>ÅêÍ¿´ü´Ö(³«»Ï)</td><th>ÅêÍ¿´ü´Ö(½ªÎ»)</td><th>1ÆüÅêÍ¿ÎÌ</th><th>ÅêÍ¿·ĞÏ©</th><th colspan=2>Ê»ÍÑÌÜÅª</th><th>¥³¥á¥ó¥È</th></tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record_heiyou_ecols;
    $nrows_edit = 4;
    $first = true;
    $colhead = false;
    
    foreach($_lib_u_ct_record_heiyou_ecols as $k => $v) {
      # 0 => shoujyou 1
      # 1 => shoujyou 2...
      if(!$first) {
	$first = false;
	print "</tr>";
      }
      print "<tr>";
      if($colhead)
	printf("<td rowspan=%d>%s</td>", count_elements($v, $nrows_edit),
	       mx_vstring($k));
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
	      printf("<td rowspan=4>%s</td>", $v3);
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
	    printf("<td rowspan=4 colspan=1>%s</td>", $v2);
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
	if($count < count($_lib_u_ct_record_heiyou_ecols) - 1) 
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
