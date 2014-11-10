<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_vital_base_stmt = 'select "ObjectID" from "¼£¸³¥Ð¥¤¥¿¥ë"';

$_lib_u_ct_record_vital_cfg = array
(
 'TABLE' => '¼£¸³¥Ð¥¤¥¿¥ë',
 'COLS' => array('¼£¸³¥ª¡¼¥À'),
 'ECOLS' => array('¼£¸³¥ª¡¼¥À'),
 'ICOLS' => array('¼£¸³¥ª¡¼¥À'),
 'HSTMT' => $_lib_u_ct_record_vital_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_vital_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

 //
 $_lib_u_ct_record_vital_ecols = array
 (
  0 => "»öÁ°¸¡ºº",
  1 => "ÅêÍ¿³«»ÏÁ°",
  2 =>"ÅêÍ¿30Ê¬¸å",
  3 =>"ÅêÍ¿1»þ´Ö¸å",
  4 =>"ÅêÍ¿2»þ´Ö¸å",
  5 =>"ÅêÍ¿8»þ´Ö¸å",
  6 =>"ÅêÍ¿24»þ´Ö¸å",
  7 =>"ÅêÍ¿48»þ´Ö¸å",
  8 =>"ÅêÍ¿72»þ´Ö¸å",
  9 =>"»ö¸å¸¡ºº",
  10 =>"Í½È÷1",
  );

 $_lib_u_ct_record_vital_ecols_template = array(
					  array("Column" => "Ç¯·îÆü",
						'Draw' => 'date',
						#'Rowspan' => 1 // 2 rows edit field
						),
					  array("Column" => "ÂÎ½Å(kg)",
						'Draw' => 'text',
						),
					  array("Column" => "Ì®Çï¿ô",
						'Draw' => 'text',
						),
					  array("Column" => "·ì°µ(¼ý½Ì´ü)",
						'Draw' => 'text',
						),
					  array("Column" => "·ì°µ(³ÈÄ¥´ü)",
						'Draw' => 'text',
						),
					  array("Column" => "ÂÎ²¹",
						'Draw' => 'text',
						),
					  array("Column" => "¿´ÅÅ¿Þ",
						'Draw' => 'radio',
						'Enum' => array(NULL => 'Ì¤È½Äê',
								'1' => 'Àµ¾ï',
								'2' => '°Û¾ï')

						),
					  array("Column" => "¥³¥á¥ó¥È",
						'Draw' => 'text',
						'Newline' =>1
						),
					  );
 
function _lib_u_ct_record_vital_fetch_data($it, $oid) {
  global $_lib_u_ct_record_vital_cfg;
  global $_lib_u_ct_record_vital_ecols;
  global $_lib_u_ct_record_vital_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record_vital_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT * FROM "¼£¸³¥Ð¥¤¥¿¥ë" s, "¼£¸³¥Ð¥¤¥¿¥ë¥Ç¡¼¥¿" d WHERE s."ObjectID" = d."¼£¸³¥Ð¥¤¥¿¥ë" and s."ObjectID"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    # bogus
    $i = 0;
    foreach($d as $row) {
      $base = $_lib_u_ct_record_vital_ecols[$i++];
      foreach($_lib_u_ct_record_vital_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }
  return $data;
}

class ct_record_vital_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_vital_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_vital_cfg;
    global $_lib_u_ct_record_vital_ecols;
    global $_lib_u_ct_record_vital_ecols_template;

    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_vital_cfg;
    _lib_u_ct_annotate_cfg(&$cfg,  $_lib_u_ct_record_vital_ecols,
			   $_lib_u_ct_record_vital_ecols_template);
    $this->app = $app;
    $this->data['¼£¸³'] = $this->app->loo->CT_ObjectID;
    $this->data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ¼£¸³¥ª¡¼¥À id.  What I really want is ¼£¸³¥Ð¥¤¥¿¥ë id.
    $stmt = 'select "ObjectID" from "¼£¸³¥Ð¥¤¥¿¥ë" where "Superseded" is NULL and "¼£¸³¥ª¡¼¥À"=' . $chiken_id;
    
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_VITAL FOUND for chiken_id=$chiken_id";
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
    $d = _lib_u_ct_record_vital_fetch_data($this, $id);
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
      $stmt = ('UPDATE "¼£¸³¥Ð¥¤¥¿¥ë¥Ç¡¼¥¿" SET "¼£¸³¥Ð¥¤¥¿¥ë" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "¼£¸³¥Ð¥¤¥¿¥ë" = ' .
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
      $h[$tbl]['¼£¸³¥Ð¥¤¥¿¥ë'] = $id;
    }

    foreach($h as $r) {
      $stmt = 'INSERT INTO "¼£¸³¥Ð¥¤¥¿¥ë¥Ç¡¼¥¿" ("' .
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
    print "<tr><th colspan=9>¥Ð¥¤¥¿¥ë¥µ¥¤¥ó¡¦¿´ÅÅ¿Þ(12Í¶Æ³)</th></tr>";
    print "<tr><th>»þ´ü</th><th>Ç¯·îÆü</td><th>ÂÎ½Å kg</td><th>Ì®Çï¿ô ²ó/Ê¬</th><th colspan=2>·ì°µ ¼ý½Ì´ü/³ÈÄ¥´ü mmHg</th><th>ÂÎ²¹</th><th>¿´ÅÅ¿Þ</th><th>°Û¾ïÆâÍÆ</th></tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record_vital_ecols;
    $nrow_edit = 1;
    $first = true;
    $colhead = true;
    
    foreach($_lib_u_ct_record_vital_ecols as $k => $v) {
      # 0 => shoujyou 1
      # 1 => shoujyou 2...
      if(!$first) {
	$first = false;
	print "</tr>";
      }
      print "<tr>";
      if($colhead)
	printf("<td rowspan=%d>%s</td>", count_elements($v, $nrow_edit), $v);
      $count = 0;
      if(is_array($v)) {
	foreach($v as $k2 => $v2) {
	  print $k2;
	  if(is_array($v2)) {
	    printf("<td rowspan=%d>%s</td>", count_elements($v2, $nrow_edit),
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
	if($count < count($_lib_u_ct_record_vital_ecols) - 1) 
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
