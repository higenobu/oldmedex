<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_test_base_stmt = 'select "ObjectID" from "¼£¸³Î×¾²¸¡ºº"';

$_lib_u_ct_record_test_cfg = array
(
 'TABLE' => '¼£¸³Î×¾²¸¡ºº',
 'COLS' => array('¼£¸³¥ª¡¼¥À'),
 'ECOLS' => array('¼£¸³¥ª¡¼¥À'),
 'ICOLS' => array('¼£¸³¥ª¡¼¥À'),
 'HSTMT' => $_lib_u_ct_record_test_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_test_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

$_lib_u_ct_record_test_ecols = array
(
 "·ì±Õ³ØÅª¸¡ºº" => array("Çò·ìµå¿ô",
			 "ÀÖ·ìµå¿ô",
			 "¥Ø¥â¥°¥í¥Ó¥óÎÌ",
			 "¥Ø¥Þ¥È¥¯¥ê¥Ã¥ÈÃÍ",
			 "·ì¾®ÈÄ",
			 "ÌÖ¾õÀÖ·ìµå¿ô",
			 "Çò·ìµå¿ôÊ¬²è" => array("¹¥Ãæµå",
						 "¹¥»Àµå",
						 "¹¥±ö´ðµå",
						 "Ã±µå",
						 "¥ê¥ó¥Ñµå"),
			 "¥Õ¥£¥Ö¥ê¥Î¡¼¥²¥ó",
			 "¥¢¥ó¥Á¥È¥í¥ó¥Ó¥ó­·"),
 "·ì±ÕÀ¸²½³Ø¸¡ºº" => array("ÁíÃÁÇò",
			   "£Á¡¿£ÇÈæ",
			   "¥¢¥ë¥Ö¥ß¥ó",
			   "Áí¥Ó¥ê¥ë¥Ó¥ó",
			   "£Ç£Ï£Ô",
			   "£Ç£Ð£Ô",
			   "£Á£Ì£Ð",
			   "·ìÅü",
			   "Áí¥³¥ì¥¹¥Æ¥í¡¼¥ë",
			   "£È£Ä£Ì¡Ý¥³¥ì¥¹¥Æ¥í¡¼¥ë",
			   "£Ì£Ä£Ì¡Ý¥³¥ì¥¹¥Æ¥í¡¼¥ë",
			   "¥È¥ê¥°¥ê¥»¥é¥¤¥É",
			   "¥ê¥ó»é¼Á",
			   "Ç¢»À",
			   "£Â£Õ£Î",
			   "¥¯¥ì¥¢¥Á¥Ë¥ó",
			   "£Î£á",
			   "£Ë",
			   "£Ã£ì",
			   "£Ã£á",
			   "£Ð"
			   ),
 "Ç¢¸¡ºº" => array("ÃÁÇò",
		   "Åü",
		   "¥¦¥í¥Ó¥ê¥Î¡¼¥²¥ó",
		   "Àø·ìÈ¿±þ",
		   "¦Á1¥Þ¥¤¥¯¥í¥°¥í¥Ó¥ê¥ó",
		   "¦Â1¥Þ¥¤¥¯¥í¥°¥í¥Ó¥ê¥ó",
		   "Ç¢ÄÀÞÖ"
		   )
 );
 $_lib_u_ct_record_test_ecols_template = array
 (
  array('Column' => "Test1",
	'Label' => "",),
  array('Column' => "Test2",
	'Label' => "",),
  array('Column' => "Test3",
	'Label' =>   "",),
  array('Column' => "Test4",
	'Label' =>   "",),
  array('Column' => "Test5",
	'Label' =>   "",),
  array('Column' => "Test6",
	'Label' =>   "",),
  array('Column' => "Test7",
	'Label' =>   "",),
  array('Column' => "Test8",
	'Label' =>   "",
	'Newline' => 1),
  );

function _lib_u_ct_record_test_fill_testcols($ct) {
  global $_lib_u_ct_record_test_ecols_template;
  $db = mx_db_connect();
  $stmt = 'SELECT "Î×¾²¸¡ºº¥«¥é¥à", "¥é¥Ù¥ë" from "¼£¸³¥¹¥±¥¸¥å¡¼¥ë"
           WHERE "Superseded" IS NULL AND "¼£¸³"=' . mx_db_sql_quote($ct) . ' AND "Î×¾²¸¡ºº¥«¥é¥à" IS NOT NULL ORDER BY "Î×¾²¸¡ºº¥«¥é¥à"';
  $data = pg_fetch_all(pg_query($db, $stmt));
  if(!$data)
	return;
  foreach($data as $row)
    $_lib_u_ct_record_test_ecols_template[$row["Î×¾²¸¡ºº¥«¥é¥à"]-1]["Label"] = $row["¥é¥Ù¥ë"];
  $c = count($data);
  for($i=0; $i < count($_lib_u_ct_record_test_ecols_template) - $c;$i++)
    $_lib_u_ct_record_test_ecols_template[$i+$c]["Label"] = sprintf("Í½È÷%d", $i+1);
}

function _lib_u_ct_record_test_fetch_data($it, $oid) {
  global $_lib_u_ct_record_test_cfg;
  global $_lib_u_ct_record_test_ecols_template;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_record_test_cfg['HSTMT'] .
	   'AND "ObjectID" = ' . mx_db_sql_quote($oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  // Fetch from subtables.
  $stmt = 'SELECT "ÂçÊ¬Îà", "ÃæÊ¬Îà", "¹àÌÜÌ¾", d.* FROM "¼£¸³¸¡ºº¹àÌÜ" k, "¼£¸³Î×¾²¸¡ºº¥Ç¡¼¥¿" d WHERE k."ObjectID" = d."¸¡ºº¹àÌÜ" and "¼£¸³Î×¾²¸¡ºº"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach($d as $row) {
      $base = $row['ÂçÊ¬Îà'] . '|' . $row['ÃæÊ¬Îà'] . '|' . $row['¹àÌÜÌ¾'];
      foreach($_lib_u_ct_record_test_ecols_template as $k => $v) {
	$data[$base . '|' . $v['Column']] = $row[$v['Column']];
      }
    }
  }

  return $data;
}

class ct_record_test_edit extends simple_object_edit {
  var $debug = 1;
  function ct_record_test_edit($prefix, &$app, $cfg=NULL) {
    global $_lib_u_ct_record_test_cfg;
    global $_lib_u_ct_record_test_ecols;
    global $_lib_u_ct_record_test_ecols_template;
    if (is_null($cfg))
      $cfg =& $_lib_u_ct_record_test_cfg;
    _lib_u_ct_annotate_cfg(&$cfg, $_lib_u_ct_record_test_ecols,
			   $_lib_u_ct_record_test_ecols_template);
    $this->app = $app;
    $this->data['¼£¸³'] = $this->app->loo->CT_ObjectID;
    $this->data['¼£¸³¥ª¡¼¥À'] = $this->app->sod->chosen();
    _lib_u_ct_record_test_fill_testcols($this->app->loo->CT_ObjectID);
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means ¼£¸³¥ª¡¼¥À id.  What I really want is ¼£¸³Î×¾²¸¡ºº id.
    $stmt = 'select "ObjectID" from "¼£¸³Î×¾²¸¡ºº" where "Superseded" is NULL and "¼£¸³¥ª¡¼¥À"=' . $chiken_id;
    //print $stmt;
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD FOUND for chiken_id=$chiken_id";
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
    $d = _lib_u_ct_record_test_fetch_data($this, $id);
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
      $stmt = ('UPDATE "¼£¸³Î×¾²¸¡ºº¥Ç¡¼¥¿" SET "¼£¸³Î×¾²¸¡ºº" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "¼£¸³Î×¾²¸¡ºº" = ' .
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
      $h[$tbl]['¸¡ºº¹àÌÜ'] = $this->get_tid($big,$mid,$item);
      $h[$tbl]['¼£¸³Î×¾²¸¡ºº'] = $id;
    }

    foreach($h as $r) {
      $stmt = 'INSERT INTO "¼£¸³Î×¾²¸¡ºº¥Ç¡¼¥¿" ("' .
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
      $stmt = 'select "ObjectID" from "¼£¸³¸¡ºº¹àÌÜ" where ';
      $w = array();
      if($big)
	$w[] = '"ÂçÊ¬Îà"='. mx_db_sql_quote($big);

      if($mid)
	$w[] = '"ÃæÊ¬Îà"='. mx_db_sql_quote($mid);
      if($item)
	$w[] = '"¹àÌÜÌ¾"='. mx_db_sql_quote($item);
      $stmt .= implode(' AND ' , $w);
      $tid = mx_db_fetch_single(mx_db_connect(), $stmt);
      //if(!$tid)
      //print "MISSING: $big, $mid, $item";
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
    global $_lib_u_ct_record_test_ecols_template;
    // draw header
    print "<thead>";
    print "<tr><th colspan=11>Î×¾²¸¡ºº</th></tr>";
    print "<tr><th colspan=3>¸¡ºº¹àÌÜ</th>";
    foreach(   $_lib_u_ct_record_test_ecols_template as $k => $v) {
      print "<th>";
      print $v['Label'];
      print "</th>";
    }
    print "</tr>";
    print "</thead>";
  }

  function _draw_body($desc, $name, $value) {
    global $_lib_u_ct_record_test_ecols;
    $nrows_edit = 2;
    $first = true;
    foreach($_lib_u_ct_record_test_ecols as $k => $v) {
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
	  printf("<td rowspan=1 colspan=2>%s</td>", $v2);
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
