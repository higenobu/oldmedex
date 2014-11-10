<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_undesirable_base_stmt = 'select "ObjectID" from "¼£¸³Áí³ç"';

$_lib_u_ct_record_undesirable_cfg = array
(
 'TABLE' => '¼£¸³Áí³ç',
 'COLS' => array('¼£¸³¥ª¡¼¥À'),
 'ECOLS' => array('¼£¸³¥ª¡¼¥À'),
 'ICOLS' => array('¼£¸³¥ª¡¼¥À'),
 'HSTMT' => $_lib_u_ct_record_undesirable_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_undesirable_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

 //Áí³ç
 $_lib_u_ct_record_undesirable_ecols = array
 (
  0 => "(¾É¾õ 1)",
  1 => "(¾É¾õ 2)",
  2 => "(¾É¾õ 3)",
  3 => "(¾É¾õ 4)",
  4 => "(¾É¾õ 5)",
  5 => "(¾É¾õ 6)",
  6 => "(¾É¾õ 7)",
  7 => "(¾É¾õ 8)",
  8 => "(¾É¾õ 9)",
  9 => "(¾É¾õ10)",
  10=> "(¾É¾õ11)",
  11=> "(¾É¾õ12)",
  12=> "(¾É¾õ13)",
  13=> "(¾É¾õ14)",
  14=> "(¾É¾õ15)",
  15=> "(¾É¾õ16)",
  );

 $_lib_u_ct_record_undesirable_ecols_template = array(
					  array("Column" => "»ö¾ÝÌ¾",
						'Draw' => 'text',
						"Rowspan" => 3  // 3 rows edit field
						),
					  array("Column" => "È¯¸½Æü",
						'Draw' => 'date',
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
					  array("Column" => "½ÅÆÆÅÙ",
						'Draw' => 'radion',
						'Enum' => array('1' => '1.Èó½ÅÆÆ',
								'2' => '2.½ÅÆÆ',
								),
						),
					  array('Column' => "½èÃÖ",
						'Draw' => 'radion',
						'Enum' => array('1' => '1.¤Ê¤·',
								'2' => '2.¤¢¤ê',
								),
						),
					  array('Column' => 'Å¾µ¢³ÎÇ§Æü',
						'Draw' => 'date',
						),
					  array('Column' => 'Å¾µ¢',
						'Draw' => 'radion',
						'Enum' => array('1' => '²óÉü',
								'2' => '·Ú²÷',
								'3' => 'ÉÔÊÑ',
								'4' => '°­²½',
								),
						'Rowspan' => 3
						),
					  array('Column' => '°ø²Ì´Ø·¸',
						'Draw' => 'radion',
						'Enum' => array('1' => '´ØÏ¢¤¢¤ê',
								'2' => 'Â¿Ê¬´ØÏ¢¤¢¤ê',
								'3' => '´ØÏ¢¤¢¤ë¤«¤â¤·¤ì¤Ê¤¤',
								'4' => 'Â¿Ê¬´ØÏ¢¤Ê¤·',
								'5' => '´ØÏ¢¤Ê¤·',
								),
						'Rowspan' => 3
						),
					  array('Column' => '4¡¦5¤ÎÍýÍ³',
						'Draw' => 'radion',
						'Enum' => array('1' => '¹çÊ»¾É',
								'2' => '¤½¤ÎÂ¾',
								),
						'Rowspan' => 3
						),
					  array('Column' => '¥³¥á¥ó¥È',
						'Draw' => 'text',
						'Rowspan' => 3,
						'Newline' => 1
						),
					  array("Column" => "È¯¸½»þ¹ï",
						'Rowspan' => 2,
						'Draw' => 'text'
						),
					  array('Column' => "½ÅÆÆÅÙÍýÍ³",
						'Draw' => 'text',
						'Rowspan' =>2
						),
					  array('Column' => 'Ê»ÍÑÌô',
						'Draw' => 'check',
						'Rowspan' =>2
						),
					  array('Column' => 'Å¾µ¢³ÎÇ§»þ¹ï',
						'Draw' => 'text',
						'Rowspan' => 2
						),
					  );
 

function _lib_u_ct_record_undesirable_fetch_data($it, $oid) {
  global $_lib_u_ct_record_undesirable_cfg;
  global $_lib_u_ct_record_undesirable_ecols;
  global $_lib_u_ct_record_undesirable_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record_undesirable_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT * FROM "¼£¸³Áí³ç¥Ç¡¼¥¿" WHERE "¼£¸³Áí³ç"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    # bogus
    $i = 0;
    foreach($d as $row) {
      if($row['È¯¸½Æü»þ'])
	list($row['È¯¸½Æü'],$row['È¯¸½»þ¹ï']) = explode(' ', $row['È¯¸½Æü»þ']);
      if($row['Å¾µ¢³ÎÇ§Æü»þ'])
	list($row['Å¾µ¢³ÎÇ§Æü'],$row['Å¾µ¢³ÎÇ§»þ¹ï']) = explode(' ', $row['Å¾µ¢³ÎÇ§Æü»þ']);
      unset($row['È¯¸½Æü»þ']);
      unset($row['Å¾µ¢³ÎÇ§Æü»þ']);
      $base = $_lib_u_ct_record_undesirable_ecols[$i++];
      foreach($_lib_u_ct_record_undesirable_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }
  return $data;
}

class ct_record_undesirable_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_undesirable_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_undesirable_cfg;
    global $_lib_u_ct_record_undesirable_ecols;
    global $_lib_u_ct_record_undesirable_ecols_template;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_undesirable_cfg;
    _lib_u_ct_annotate_cfg(&$cfg, $_lib_u_ct_record_undesirable_ecols,
			   $_lib_u_ct_record_undesirable_ecols_template);
    $this->app = $app;
    $this->data['¼£¸³'] = $this->app->loo->CT_ObjectID;
    $this->data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ¼£¸³¥ª¡¼¥À id.  What I really want is ¼£¸³Áí³ç id.
    $stmt = 'select "ObjectID" from "¼£¸³Áí³ç" where "Superseded" is NULL and "¼£¸³¥ª¡¼¥À"=' . $chiken_id;
    //print $stmt;
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_DEVIATION FOUND for chiken_id=$chiken_id";
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
    $d = _lib_u_ct_record_undesirable_fetch_data($this, $id);
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
      $stmt = ('UPDATE "¼£¸³Áí³ç¥Ç¡¼¥¿" SET "¼£¸³Áí³ç" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "¼£¸³Áí³ç" = ' .
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
      $h[$tbl]['¼£¸³Áí³ç'] = $id;
    }

    foreach($h as $r) {
      if($r['È¯¸½Æü'])
	$r['È¯¸½Æü»þ'] = sprintf("%s %s", $r['È¯¸½Æü'], $r['È¯¸½»þ¹ï']);
      if($r['Å¾µ¢³ÎÇ§Æü'])
	$r['Å¾µ¢³ÎÇ§Æü»þ'] = sprintf("%s %s", $r['Å¾µ¢³ÎÇ§Æü'], $r['Å¾µ¢³ÎÇ§»þ¹ï']);
      unset($r['È¯¸½Æü']);
      unset($r['È¯¸½»þ¹ï']);
      unset($r['Å¾µ¢³ÎÇ§Æü']);
      unset($r['Å¾µ¢³ÎÇ§»þ¹ï']);

      $stmt = 'INSERT INTO "¼£¸³Áí³ç¥Ç¡¼¥¿" ("' .
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
    print "<tr><th colspan=10>Í­³²»ö¾Ý(¼«Â¾³Ð¾É¾õ)Áí³ç</th></tr>";
    print "<tr><th colspan=2>Í­³²»ö¾ÝÍ­Ìµ</th><th colspan=8>¤Ê¤·¡¢¤¢¤ê</th></tr>";
    print "<tr><th>Í­³²»ö¾Ý</th><th>È¯¸½Æü¡¦È¯¸½»þ¹ï</td><th>Grade</th><th>½ÅÆÆÅÙ</th><th>½èÃÖ</th><th>Å¾µ¢³ÎÇ§Æü¡¦³ÎÇ§»þ¹ï</th><th>Å¾µ¢</th><th colspan=2>°ø²Ì´Ø·¸</th><th>¥³¥á¥ó¥È</th></tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record_undesirable_ecols;
    $nrows_edit = 3;
    $first = true;
    $colhead = false;
    
    foreach($_lib_u_ct_record_undesirable_ecols as $k => $v) {
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
	  if(is_array($v2)) {
	    printf("<td rowspan=%d>%s</td>", count_elements($v2, $nrows_edit),
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
	if($count < count($_lib_u_ct_record_undesirable_ecols) - 1) 
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
