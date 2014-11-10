<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/utils.php';

$_lib_u_ct_record_deviation_base_stmt = 'select "ObjectID" from "治験変動判定"';

$_lib_u_ct_record_deviation_cfg = array
(
 'TABLE' => '治験変動判定',
 'COLS' => array('治験オーダ'),
 'ECOLS' => array('治験オーダ'),
 'ICOLS' => array('治験オーダ'),
 'HSTMT' => $_lib_u_ct_record_deviation_base_stmt .' WHERE (NULL IS NULL) ',
 'STMT' => ($_lib_u_ct_record_deviation_base_stmt .' WHERE  ("Superseded" IS NULL) '),
 );

$_lib_u_ct_record_deviation_ecols = array
(
 "血液学的検査" => array("白血球数",
			 "赤血球数",
			 "ヘモグロビン量",
			 "ヘマトクリット値",
			 "血小板",
			 "網状赤血球数",
			 "白血球数分画" => array("好中球",
						 "好酸球",
						 "好塩基球",
						 "単球",
						 "リンパ球"),
			 "フィブリノーゲン",
			 "アンチトロンビン��"),
 "血液生化学検査" => array("総蛋白",
			   "Ａ／Ｇ比",
			   "アルブミン",
			   "総ビリルビン",
			   "ＧＯＴ",
			   "ＧＰＴ",
			   "ＡＬＰ",
			   "血糖",
			   "総コレステロール",
			   "ＨＤＬ−コレステロール",
			   "ＬＤＬ−コレステロール",
			   "トリグリセライド",
			   "リン脂質",
			   "尿酸",
			   "ＢＵＮ",
			   "クレアチニン",
			   "Ｎａ",
			   "Ｋ",
			   "Ｃｌ",
			   "Ｃａ",
			   "Ｐ"
			   ),
 "尿検査" => array("蛋白",
		   "糖",
		   "ウロビリノーゲン",
		   "潜血反応",
		   "α1マイクログロビリン",
		   "β1マイクログロビリン",
		   "尿沈渣"
		   )
 );
 
 $_lib_u_ct_record_deviation_ecols_template = array(
					  array('Column' => "異常の有無",
						'Draw' => "radio",
						'Enum' => array(NULL => '未判定',
								'1' => 'なし',
								'2' => 'あり'),
						'Rowspan' => 2   // 2 rows edit field
						),
					  array('Column' => "異常の高低・上昇",
						'Draw' => 'check',
						'Caption' => '上昇',
						'Enum' => array('1' => '上昇')
						),
					  
					  array('Column' => "異常変動の有無・上昇",
						'Draw' => "enum",
						'Enum' => array(NULL => '未判定',
								'1' => 'なし',
								'2' => 'あり'),
						),
					  array('Column' => "異常変動なしの理由・上昇",
						'Draw' => "radio",
						'Enum' => array(NULL => '未判定',
								'1' => '生理的変動',
								'2' => '被検者固有値',
								'3' => 'その他'),
						),
					  array('Column' => "異常変動なしの理由テキスト・上昇",
						'Draw' => 'text',
						'Newline' => 1
						),
					  
					  array('Column' => "異常の高低・下降",
						'Draw' => 'check',
						'Caption' => '下降',
						'Enum' => array('1' => '下降')
						),
					  
					  array('Column' => "異常変動の有無・下降",
						'Draw' => "enum",
						'Enum' => array(NULL => '未判定',
								'1' => 'なし',
								'2' => 'あり'),
						),
					  array('Column' => "異常変動なしの理由・下降",
						'Draw' => "radio",
						'Enum' => array(NULL => '未判定',
								'1' => '生理的変動',
								'2' => '被検者固有値',
								'3' => 'その他'),
						),
					  array('Column' => "異常変動なしの理由テキスト・下降",
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
  $stmt = 'SELECT "大分類", "中分類", "項目名", d.* FROM "治験検査項目" k, "治験変動判定データ" d WHERE k."ObjectID" = d."検査項目" and "治験変動判定"=' .
    mx_db_sql_quote($oid);
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach($d as $row) {
      $base = $row['大分類'] . '|' . $row['中分類'] . '|' . $row['項目名'];
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
    $this->data['治験'] = $this->app->loo->CT_ObjectID;
    $this->data['治験オーダ'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($chiken_id) {
    $db = mx_db_connect();

    // ID here means 治験オーダ id.  What I really want is 治験変動判定 id.
    $stmt = 'select "ObjectID" from "治験変動判定" where "Superseded" is NULL and "治験オーダ"=' . $chiken_id;
    //print $stmt;
    $r = mx_db_fetch_single($db, $stmt);
    if(!$r) {
      //print "NO OLD RECORD_UNDESIRABLE FOUND for chiken_id=$chiken_id";
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
    $d = _lib_u_ct_record_deviation_fetch_data($this, $id);
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
      $stmt = ('UPDATE "治験変動判定データ" SET "治験変動判定" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "治験変動判定" = ' .
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
      $h[$tbl]['検査項目'] = $this->get_tid($big,$mid,$item);
      $h[$tbl]['治験変動判定'] = $id;
    }

    foreach($h as $r) {
      $stmt = 'INSERT INTO "治験変動判定データ" ("' .
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
      $stmt = 'select "ObjectID" from "治験検査項目" where ';
      $w = array();
      if($big)
	$w[] = '"大分類"='. mx_db_sql_quote($big);

      if($mid)
	$w[] = '"中分類"='. mx_db_sql_quote($mid);
      if($item)
	$w[] = '"項目名"='. mx_db_sql_quote($item);
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
    print "<tr><th colspan=8>臨床検査 異常変動の判定</th></tr>";
    print "<tr><th colspan=3>検査項目</th><th>異常の有無</th><th>異常の高低</th><th>異常変動の有無</th><th colspan=2>異常変動なしの理由</th></tr>";
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
