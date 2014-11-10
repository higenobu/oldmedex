<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/pp_attr.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/patient-basic.php';
//0410-2014 modified for debug
$__pdf_params = NULL;
function _lib_u_everybody_form_tweak_template($self, $template) {
    global $mx_authenticate_current_user;
    global $__pdf_params;

    foreach(array("¥·¥¹¥Æ¥à.%s" => mx_get_install_data(),
		  "¥æ¡¼¥¶.%s" => get_mx_authenticate_user($mx_authenticate_current_user),
//0415-2014
		  "´µ¼ÔÂæÄ¢.%s" => mx_draw_patientinfo_get_data2($self->so_config['Patient_ObjectID'])) as $label => $v_list)
	    {
	      foreach($v_list as $k => $v) {
		if(is_null($v)) {
		  $__pdf_params[sprintf($label, $k)] = '';
		  $v = '&nbsp;';
		}else
		  $__pdf_params[sprintf($label, $k)] = $v;
		$template = str_replace(sprintf("@@$label@@", $k), $v, $template);
	      }
	    }
    $template = str_replace("@@µ­Ï¿Æü@@", '', $template);
    return $template;
}

function _lib_forms_attr_fetch_data($type, $id, $attr) {
	$db = mx_db_connect();
	$substmt = '
SELECT M."¥°¥ë¡¼¥×", M."Ì¾¾Î", D."Â°À­ÃÍ", D."Ä¢É¼Â°À­", A."µ­Ï¿Æü", A."´µ¼Ô",
       A."ID", A."ObjectID", A."Superseded", A."CreatedBy", A.form_type
FROM "Ä¢É¼Â°À­" AS A
JOIN "Ä¢É¼Â°À­¥Ç¡¼¥¿" AS D ON D."Ä¢É¼Â°À­" = A."ObjectID"
JOIN "´µ¼ÔÂ°À­°ìÍ÷" AS M ON M."ObjectID" = D."Â°À­"
WHERE D."Â°À­" IN (' . implode(', ', array_keys($attr)) . ')';
	$stmt = $substmt . ' AND A."ObjectID" = ' . mx_db_sql_quote($id);
	$d = array();
	$q = pg_fetch_all(pg_query($db, $stmt));
	if (!is_array($q)) {
		# A rare case that the parent table has
		# a row but no child table element exist
		# for it.
		$stmt = '
SELECT A."ID", A."ObjectID", A."Superseded", A."CreatedBy",
       A."µ­Ï¿Æü", A."´µ¼Ô", A.form_type
FROM "Ä¢É¼Â°À­" AS A
WHERE A."ObjectID" = ' . mx_db_sql_quote($id);
		$q = pg_fetch_all(pg_query($db, $stmt));
	}
	if (!is_array($q))
		return $d;
	foreach ($q as $e) {
		if (count($d) == 0) {
			$d['ID'] = $e['ID'];
			$d['ObjectID'] = $e['ObjectID'];
			$d['Superseded'] = $e['Superseded'];
			$d['CreatedBy'] = $e['CreatedBy'];
			$d['´µ¼Ô'] = $e['´µ¼Ô'];
			$d['µ­Ï¿Æü'] = $e['µ­Ï¿Æü'];
			$d['form_type'] = $e['form_type'];
		}
		if (is_null($e['Ä¢É¼Â°À­']))
			continue;
		$g = __pp_attr_colname($e['¥°¥ë¡¼¥×'], $e['Ì¾¾Î']);
		$d[$g] = $e['Â°À­ÃÍ'];
	}
	return $d;
}

class list_of_forms extends pp_attr_los {
  var $table = 'Ä¢É¼Â°À­';
//0410-2014 debug only kenshin 
  var $group = array( 
		     'kenshin');
  function base_fetch_stmt_0() {
    $stmt = pp_attr_los::base_fetch_stmt_0();
    return $stmt . ' AND form_type=' . mx_db_sql_quote($this->so_config['FORM_TYPE']);
  }
}

class form_display extends pp_attr_sod {
  var $table = 'Ä¢É¼Â°À­';
//0410-2014
  var $group = array( 'kenshin');
  function form_display ($prefix, $config) {
    
    $builder = new pp_attr_builder($this->group);
    $config['TABLE'] = "Ä¢É¼Â°À­";
    $this->attr = $builder->attr;
    pp_attr_sod::pp_attr_sod($prefix, $config);
  }

  function fetch_data($id) {
    return _lib_forms_attr_fetch_data($this->so_config['FORM_TYPE'], $id, $this->attr);
  }
  
  function tweak_template($template) {
    return  _lib_u_everybody_form_tweak_template($this, $template);
  }

  function draw_body_template($data, $hdata, $dcols) {
    global $_mx_resource_dir;
    global $__pdf_params;
    $template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/templates/' . $this->so_config['D_TEMPLATE']);
    $template = $this->tweak_template($template);
    foreach ($dcols as $desc) {
      if($desc['Draw'] == 'group_head')
         continue;
	$col = $desc['Column'];
	$gc = "@@$col@@";
	$pat = "/@@$col:(.*?)@@/";
	$m = array();
	if(preg_match($pat, $template, &$m)) {
	  $opt = explode(':', $m[1]);
	  foreach($opt as $x) {
	    list($k, $v) = explode('=', $x);
	    switch($k) {
	    case 'draw':
	      $desc['Draw'] = $v;
	      break;
	    case 'option':
	      if($desc['Draw'] == 'icd10')
      	          $desc['Option'] = array('disease' => $v,
		  		          'add_id' => 1);
	    }
	  }
	  $gc = $m[0];
	}
	
	ob_start();
	$this->draw_body_atom($desc, $data, FALSE);
	$v = ob_get_contents();
	ob_end_clean();
	$template = str_replace($gc, $v, $template);
	$v = str_replace('<br />', '', $v);
	$v = str_replace('<div>', '', $v);
	$v = str_replace('</div>', '', $v);
	if ($v == '(ÃÍÌµ¤·)')
	  $v = '';
	$__pdf_params[$col] = $v;

    }
    print $template;
    if($this->want_pdf)
      $this->print_sod2($__pdf_params);
  } 

  function print_sod() {
    $this->want_pdf = 1;
  }

  function print_sod2($params) {
    $db = mx_db_connect();
    $oid = $this->id;
    $template = $this->so_config['D_OOO_TEMPLATE'];
    // read DB

    $rand = rand(0,100000000);
    $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
    $params['PDF_PATH'] = $pdf_path;
    $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
    $params['TEMPLATE'] = $template;
    $params['BODY'] = '\n';

    // RPC 
    $descriptorspec = array(
			  0 => array("pipe", "r"),  // stdin 
			  1 => array("pipe", "w"),  // stdout 
			  2 => array("pipe", "w")   // stderr
			  );
    $process = proc_open('../../../tools/pdfgen2.py', $descriptorspec, $pipes);
  
    if (!is_resource($process)) {
      print "OOO¤¬³«¤±¤Þ¤»¤ó¤Ç¤·¤¿";
      return -1;
    }

    // pass arguments
    foreach($params as $k => $v) {
      $ek = urlencode(mb_convert_encoding($k, 'UTF-8', 'eucJP-win'));
      $ev = urlencode(mb_convert_encoding($v, 'UTF-8', 'eucJP-win'));
      fwrite($pipes[0], sprintf("%s=%s\n", urlencode($ek), $ev));
    }
    fclose($pipes[0]);
    //$txt = stream_get_contents($pipe[2]);
    while (!feof($pipes[2])) {
      $txt .= fread($pipes[2], 8192);
    }
    proc_close($process);

    if($txt) {
      print $txt;
      return;
    }
    
    if(file_exists($pdf_path)) {
      //---- read pdf file
      $handler = fopen($pdf_path, 'rb');
      $content = fread($handler, filesize($pdf_path));
      fclose($handler);
      //unlink($pdf_path);

      //---- store into db
      $db = mx_db_connect();
      $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
      $type = 'PDF';
      $id = mx_db_insert_extdocument($db, $type, $bid,
				     $pt=NULL, $comment=NULL);
      //HACK: open window and show PDF for client-side printing
      print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
	'/generated.pdf","","width=640,height=640");
</SCRIPT>';
    
    }else{
      print "PDF¤ÎÀ¸À®¤Ë¼ºÇÔ¤·¤Þ¤·¤¿";
    }
  }
}
 
class form_edit extends pp_attr_soe {
  var $table = 'Ä¢É¼Â°À­';
  var $group = array( 
		     'kenshin');
//0417-2012

//

  function fetch_data($id) {
    return _lib_forms_attr_fetch_data($this->so_config['FORM_TYPE'], $id, $this->attr);
  }

  function tweak_template($template) {
    return  _lib_u_everybody_form_tweak_template($this, $template);
  }

	function anew_tweak_attrs($data) {
		foreach ($data as $d) {
		  $col = __pp_attr_colname($d['¥°¥ë¡¼¥×'], $d['Ì¾¾Î']);
		  $this->data[$col] = $d['Â°À­ÃÍ'];
		}
	}

	function anew_tweak($orig_id) {
		$dbh = mx_db_connect();
		$oid = $this->so_config['Patient_ObjectID'];
		$pid = $this->so_config['´µ¼ÔID'];
//04-17-2012 this function is from pp_attr.php and corrected

		$in = _lib_pp_attr_find($oid, $this->group);
		if ($in && is_array($in)) {
		  $this->anew_tweak_attrs($in);
		}
		// fill Rx info, etc
//04-17-2012 
		$col = __pp_attr_colname("°åÎÅÊÝ¸î", "ÅêÌôÆâÍÆ","kenshin");
		$result = rx_module_index_info($dbh, $oid, $pid, NULL, NULL);
		if(count($result) > 0) {
		  foreach($result as $r) {
		    if (!is_null($this->data[$col]))
		      break;
		    $vb_array = explode('&', $r['value_blob']);
		    foreach($vb_array as $vb) {
		      list($k, $v) = mx_form_unescape_key($vb);
		      if ($k == 'RX')
			$this->data[$col] .= "\n" . $v;
		    }
		  }
		}
	}

	function commit($force=NULL) {
		$this->data['form_type'] = $this->so_config['FORM_TYPE'];
		return pp_attr_soe::commit($force);
	}

	function _update_subtables(&$db, $ObjectID, $StashID) {

		if (!is_null($StashID)) {
			/*
			 * We are doing partial updates, so just
			 * make a copy of everything there to
			 * $StashID without touching the current
			 * data.
			 */
		  $ft = mx_db_sql_quote($this->so_config['FORM_TYPE']);
			$stmt = '
INSERT INTO "Ä¢É¼Â°À­¥Ç¡¼¥¿" ("Ä¢É¼Â°À­", "Â°À­", "Â°À­ÃÍ")
SELECT ' . mx_db_sql_quote($StashID) . ', "Â°À­", "Â°À­ÃÍ"
FROM "Ä¢É¼Â°À­¥Ç¡¼¥¿"
WHERE "Ä¢É¼Â°À­" = ' . mx_db_sql_quote($ObjectID);
			if (! pg_query($db, $stmt))
				return pg_last_error($db);
		}

		/*
		 * Then delete any existing ones that we are
		 * going to update.
		 */

		$stmt = '
DELETE FROM "Ä¢É¼Â°À­¥Ç¡¼¥¿"
WHERE "Ä¢É¼Â°À­" = ' . mx_db_sql_quote($ObjectID) . '
AND "Â°À­" IN (' . implode(', ', array_keys($this->attr)) . ')';

		if (! pg_query($db, $stmt))
			return pg_last_error($db);

		
//		 * And finally insert the ones from this round.
		




		foreach ($this->attr as $attr_id => $attr_name) {
			$stmt = '
INSERT INTO "Ä¢É¼Â°À­¥Ç¡¼¥¿" ("Â°À­", "Â°À­ÃÍ", "Ä¢É¼Â°À­")
VALUES (' . mx_db_sql_quote($attr_id) . ',
' . mx_db_sql_quote($this->data[$attr_name]) . ',
' . mx_db_sql_quote($ObjectID) . ')';
			if (! pg_query($db, $stmt))
				return pg_last_error($db);
		}

	}


  
}
?>
