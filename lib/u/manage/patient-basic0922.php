<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/dodwell.php';

$_lib_u_manage_patient_basic_cfg = array
(
 'TABLE' => '´µ¼ÔÂæÄ¢',
 'COLS' => array("´µ¼ÔID", "À«", "Ì¾", "¥Õ¥ê¥¬¥Ê",
		 "À­ÊÌ", "Íø¤­¼ê", "À¸Ç¯·îÆü",
		 "½»½ê0", "½»½ê1",
		 "½»½ê2", "½»½ê3", "½»½ê4",
		 "²ÃÆþÅÅÏÃ",
		 "·ÈÂÓÅÅÏÃ",
		 "¥á¡¼¥ë¥¢¥É¥ì¥¹",
		
		 "ÀÁµáÀèÅÅÏÃÈÖ¹æ"),

 'ENABLE_QBE' => array(array('Column' => '´µ¼ÔID',
'Label' => 'Ê¬ÎàÈÖ¹æ',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		      array('Column' =>  "À«",
'Label'=>'ÂçÊ¬Îà'),
array('Column' =>  "Ì¾",
			'Label'=>'ÃæÊ¬Îà'),
 		        array('Column' =>  "¥Õ¥ê¥¬¥Ê",
 			'Label'=>'¾®Ê¬Îà'),
		       
		       '//',
		        
		       
 
 

		       ),

 'LCOLS' => array(array('Column' => '´µ¼ÔID',
			'Label' => 'Ê¬ÎàÈÖ¹æ',
			      
			    
			     ),
		      array('Column' =>  "À«",
			'Label'=>'ÂçÊ¬Îà'),
array('Column' =>  "Ì¾",
			'Label'=>'ÃæÊ¬Îà'),
array('Column' =>  "¥Õ¥ê¥¬¥Ê",
 			'Label'=>'¾®Ê¬Îà'),
),

 'ALLOW_SORT' => 1,
 
'LLAYO' => array(array('Column' => '´µ¼ÔID',
			'Label' => 'Ê¬ÎàÈÖ¹æ',
			      
			    
			     ),
		      array('Column' =>  "À«",
			'Label'=>'ÂçÊ¬Îà'),
		 array('Column' =>  "Ì¾",
			'Label'=>'ÃæÊ¬Îà'),
array('Column' =>  "¥Õ¥ê¥¬¥Ê",
 			'Label'=>'¾®Ê¬Îà'),
 

		  ),
 'DCOLS' => array(array('Column' => '´µ¼ÔID',
			'Label' => 'Ê¬ÎàÈÖ¹æ',
			      
			    
			     ),
		      array('Column' =>  "À«",
			'Label'=>'ÂçÊ¬Îà'),
		 array('Column' =>  "Ì¾",
			'Label'=>'ÃæÊ¬Îà'),
array('Column' =>  "¥Õ¥ê¥¬¥Ê",
 			'Label'=>'¾®Ê¬Îà'),
 
		   
//		  array('Column' => "½»½ê0", 'Label' => "ZIP"),
//		  array('Column' => "½»½ê1", 'Label' => "STATE"),
//		  array('Column' => "½»½ê2", 'Label' => "CITY"),
//		  array('Column' => "½»½ê3", 'Label' => "STREET"),
//		  array('Column' => "½»½ê4", 'Label' => "ROOM"),
//array('Column' => '²ÃÆþÅÅÏÃ',
//			'Label' => 'Home tel',
			      
//			    
//			     ),
		      ),
		   
 
		  array('Column' => 'CreatedBy',
			'Label' => 'record',
			'Draw' => 'user'),
		  ),

 'ECOLS' => array(array('Column' => "´µ¼ÔID",
'Label' => 'Ê¬ÎàÈÖ¹æ',
			'Option' => array('ime' => 'disabled',
					  'zeropad' => $_mx_patient_id_zeropad,
					  'validate' => 'nonnull')),
		   
array('Column' =>  "À«",
			'Label'=>'ÂçÊ¬Îà',
		 
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "Ì¾",
'Label'=>'ÃæÊ¬Îà',
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "¥Õ¥ê¥¬¥Ê",
'Label'=>'¾®Ê¬Îà',
			'Option' => array('validate' => 'nonnull')),
		   
/*
		  array('Column' => "½»½ê0",
			'Label' => "ZIP CODE",
//			'Draw' => 'post_code',
			'Draw' => 'text',
			'Option' => array('ime' => 'disabled',
					  'zip' => '½»½ê0',
					  'prefecture' => '½»½ê1',
					  'city' => '½»½ê2',
					  'block' => '½»½ê3',
					  'add_id' => 1,
					  )),
		  array('Column' => "½»½ê1", 'Label' => "STATE", 'Option' => array('add_id' => 1)),
		  array('Column' => "½»½ê2", 'Label' => "CITY", 'Option' => array('add_id' => 1)),
		  array('Column' => "½»½ê3", 'Label' => "STREET", 'Option' => array('add_id' => 1)),
		  array('Column' => "½»½ê4", 'Label' => "ROOM", 'Option' => array('add_id' => 1)),
		  array('Column' => "²ÃÆþÅÅÏÃ",
'Label'=>'Home tel',
			'Option' => array('ime' => 'disabled')),
*/

 ),
/*
		  array('Column' => "Æþ³°¶èÊ¬",
			'Draw' => 'enum',
			'Enum' => array('I' => 'Æþ±¡', 'O' => '³°Íè',
					'E' => 'Æþ±¡È½ÄêÂÐ¾Ý',
					'W' => 'Æþ±¡ÂÔ¤Á',
					NULL => 'Ì¤»ØÄê') ),

		  array('Column' => "ÊÝ¸±¼ÔÈÖ¹æ",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "ÈïÊÝ¸±¼Ô",
			'Draw' => 'enum',
			'Enum' => array('1' => 'ËÜ¿Í', '2' => '²ÈÂ²',
					NULL => 'Ì¤»ØÄê') ),
		  array('Column' => "ÈïÊÝ¸±¼Ô¼êÄ¢¤Îµ­¹æ",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "ÈïÊÝ¸±¼Ô¼êÄ¢¤ÎÈÖ¹æ",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "¸øÈñÉéÃ´¼ÔÈÖ¹æ",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "¸øÈñÉéÃ´¼ÔÈÖ¹æ2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "¸øÈñÉéÃ´¼ÔÈÖ¹æ3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),

		  array('Column' => "È¯¾ÉÆü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "Æþ±¡Æü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "Âà±¡Í½ÄêÆü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "Âà±¡Í½Äê¡¦¸«¹þ",
			'Draw' => 'enum',
			'Enum' => array('F' => 'Í½Äê', 'E' => '¸«¹þ',
					NULL => 'Ì¤»ØÄê') ),
		  array('Column' => "´õË¾ÉÂÅï",
			'Label' => "´µ¼Ô¥°¥ë¡¼¥×",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  array('Column' => "´µ¼Ô¥Þ¡¼¥¯",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  "Æþ±¡²Ê",
		  array('Column' => "»àË´Æü",
			'Option' => array('ime' => 'disabled')),
		  "È÷¹Í",
		  array('Column' => "²óÉü´ü",
			'Draw' => 'enum',
			'Enum' => array('A' => 'ÂÐ¾Ý A',
					'B' => 'ÂÐ¾Ý B',
					'C' => 'ÂÐ¾Ý C',
					'D' => 'ÂÐ¾Ý D',
					'E' => 'ÂÐ¾Ý E',
					'Z' => 'ÂÐ¾Ý³°',
					NULL => '(Ì¤ÀßÄê)')),
		  array('Column' => "°å³ØÅªÉÔ°ÂÄê",
			'Draw' => 'enum',
			'Enum' => array('S' => '°ÂÄê', 'U' => 'ÉÔ°ÂÄê',
					'W' => 'Í×Ãí°Õ',
					NULL => '(Ì¤ÀßÄê)')),
		  array('Column' => "·ì±Õ·¿£Á£Â£Ï¼°",
			'Draw' => 'enum',
			'Enum' => array('A' => 'A', 'B' => 'B',
					'AB' => 'AB', 'O' => 'O',
					NULL => 'ÉÔÌÀ')),
		  array('Column' => "·ì±Õ·¿£Ò£è¼°",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => 'ÉÔÌÀ')),
		  array('Column' => "£È£Â£ó¹³¸¶",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => 'ÉÔÌÀ')),
		  "¥¢¥ì¥ë¥®¡¼",
		  "´¶À÷¾É",
		  array('Column' => "Æ©ÀÏ´µ¼Ô¥Õ¥é¥°",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => 'ÉÔÌÀ')),

		  "¶ÐÌ³ÀèÌ¾",
		  array('Column' => "¶ÐÌ³ÀèÍ¹ÊØÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		  "¶ÐÌ³Àè½»½ê",
		  array('Column' => "¶ÐÌ³ÀèÅÅÏÃÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		  "ÀÁµáÀèÌ¾",
		  array('Column' => "ÀÁµáÀèÍ¹ÊØÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		  "ÀÁµáÀè½»½ê",
		  array('Column' => "ÀÁµáÀèÅÅÏÃÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		array('Column' => "ÉÂ¼¼",
			),
*/

		  ),
 );
$_lib_u_manage_patient_basic_cfg['HSTMT'] =
('SELECT "ObjectID", "CreatedBy", ' .
 implode(', ', array_map('mx_db_sql_quote_name',
			 $_lib_u_manage_patient_basic_cfg['COLS'])) .
 ', ("À«" || "Ì¾") AS "À«Ì¾"' .
 ' FROM ' . mx_db_sql_quote_name($_lib_u_manage_patient_basic_cfg['TABLE']) .
 ' WHERE (NULL IS NULL) ');

function _lib_u_manage_patient_basic_cfg_add_page_num($ary, &$map) {
	global $_lib_u_manage_patient_basic_cfg;

	$in = $_lib_u_manage_patient_basic_cfg[$ary];
	$out = array();
	foreach ($in as $data) {
		if (!is_array($data)) {
			$name = $data;
		}
		else {
			$name = $data['Column'];
		}
		if (array_key_exists($name, $map)) {
			if (!is_array($data)) {
				$data = array('Column' => $data,
					      'Label' => $data,
					      'Draw' => 'text');
			}
			$data['Page'] = $map[$name];
		}
		$out[] = $data;
	}
	$_lib_u_manage_patient_basic_cfg[$ary] = $out;
}

if ($_mx_cheap_layout) {
	$all_page_numcol = 3;
	$first_page_numcol = 25;
	$page = array();
	for ($i = 0;
	     $i < count($_lib_u_manage_patient_basic_cfg['COLS']);
	     $i++) {
		$name = $_lib_u_manage_patient_basic_cfg['COLS'][$i];
		if ($i < $all_page_numcol)
			;
		else if ($i < $first_page_numcol)
			$page[$name] = 0;
		else
			$page[$name] = 1;
	}
	_lib_u_manage_patient_basic_cfg_add_page_num('DCOLS', &$page);
	_lib_u_manage_patient_basic_cfg_add_page_num('ECOLS', &$page);
//	$_lib_u_manage_patient_basic_cfg['DPAGES'] = array('(1)', '(2)');
//	$_lib_u_manage_patient_basic_cfg['EPAGES'] = array('(1)', '(2)');
	$_lib_u_manage_patient_basic_cfg['DPAGES'] = array('(1)' );
	$_lib_u_manage_patient_basic_cfg['EPAGES'] = array('(1)' );
}

class list_of_patient_basics extends list_of_simple_objects {
  function list_of_patient_basics($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '´µ¼Ô¥Þ¡¼¥¯') {
		  return mx_dbenum_patientmark('=');
	  }
	  return $desc['Enum'];
  }

}

class patient_basic_display extends simple_object_display {
  function patient_basic_display($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  
  function annotate_row_data(&$data) {
    if ($data["Æþ³°¶èÊ¬"] == 'O') {
      $data["Âà±¡Í½ÄêÆü"] = 'N/A';
      $data["Âà±¡Í½Äê¡¦¸«¹þ"] = 'N/A';
    }
  }

  function issue_card() {
    $data = $this->prepare_data_for_draw();
    $dodwell = new Dodwell($data);
    if(! $dodwell->write_csv()) {
      print implode('<br>', $dodwell->error);
    }
  }
}

class patient_basic_edit extends simple_object_edit {
  function patient_basic_edit($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '´õË¾ÉÂÅï')
		  return mx_dbenum_patientgroup();
	  if ($desc['Column'] == '´µ¼Ô¥Þ¡¼¥¯')
		  return mx_dbenum_patientmark();
	  return $desc['Enum'];
  }

  function _validate() {
    global $_lib_u_manage_patient_basic_cfg;

    $d =& $this->data;

    $bad = 0;
    // Ugh.
    if (! $this->id) {
      $db = mx_db_connect();
      $r = mx_db_fetch_single($db, 'SELECT "ObjectID" FROM "´µ¼ÔÂæÄ¢"
WHERE "´µ¼ÔID" = ' . mx_db_sql_quote($d['´µ¼ÔID']));
      if (is_array($r) || is_null($r)) {
	$this->err("(Ê¬ÎàÈÖ¹æ): »ØÄê¤µ¤ì¤¿ÃÍ¤Ï¤¹¤Ç¤Ë»È¤ï¤ì¤Æ¤¤¤Þ¤¹¡£\n");
	$bad++;
      }
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

    if (($d['Æþ³°¶èÊ¬'] == 'I') || ($d['Æþ³°¶èÊ¬'] == 'W')) {
	    foreach (array('Æþ±¡Æü', 'Âà±¡Í½ÄêÆü') as $c) {
		    if ($st = mx_db_validate_date($d[$c])) {
			    $this->err("($c): $st\n");
			    $bad++;
		    }
	    }
    }
    if (! $bad)
      return 'ok';
  }

  function trim_data(&$row) {
    foreach ($row as $k => $v) {
      if (! is_null($v))
	$row[$k] = trim($v);
    }
  }

  function annotate_row_data(&$data) { $this->trim_data(&$data); }
  function annotate_form_data(&$data) {
	  foreach (array('À«','Ì¾','¥Õ¥ê¥¬¥Ê') as $c) {
		  if (is_null($data[$c]))
			  continue;
		  $data[$c] = trim(mx_xlate_jzspace($data[$c]));
	  }
	  $fn = $data['À«'];
	  $gn = $data['Ì¾'];

	  if ($gn == '' && $fn != '') {
		  $m = array();
		  if (preg_match('/^(.*?) +(.*)$/', $fn, &$m)) {
			  $data['À«'] = $m[1];
			  $data['Ì¾'] = $m[2];
		  }
	  }
    simple_object_edit::annotate_form_data(&$data);
    $this->trim_data(&$data);
  }

  function allocate_new_patient_id() {
	  global $_mx_patient_id_zeropad, $_mx_auto_allocate_patient_id;

	  if (!$_mx_auto_allocate_patient_id)
		  return NULL;
	  $m = array();
	  if (preg_match('/^(.+),(.+)$/', $_mx_auto_allocate_patient_id,
			 &$m)) {
		  $bottom = $m[1];
		  $top = $m[2];
		  if (0 < $_mx_patient_id_zeropad)
			  $top = mx_zeropad($top, $_mx_patient_id_zeropad);
		  $tsql = '"´µ¼ÔID" <= ' . mx_db_sql_quote($top) . ' AND ';
	  }
	  else {
		  $bottom = $_mx_auto_allocate_patient_id;
		  $top = undef;
		  $tsql = '';
	  }

	  $db = mx_db_connect();
	  $stmt = 'SELECT max("´µ¼ÔID") AS "I" FROM "´µ¼ÔÂæÄ¢" WHERE ' .
		  $tsql .
		  '"Superseded" IS NULL';
	  if($_mx_patient_id_zeropad > 0)
	    $stmt .= ' AND length("´µ¼ÔID") = ' . $_mx_patient_id_zeropad;

	  $curr = mx_db_fetch_single($db, $stmt);

	  if (is_null($curr))
		  $curr = $_mx_auto_allocate_patient_id;
	  else {
		  /*
		   * NEEDSWORK: this should be
		   * $curr = mz_un_zeropad($curr['I']) + 1
		   */
		  $curr = intval($curr['I']) + 1;
	  }

	  if (0 < $_mx_patient_id_zeropad)
		  $curr = mx_zeropad($curr, $_mx_patient_id_zeropad);
	  return $curr;
  }

  function anew_tweak($orig_id) {
	  global $_mx_config_pt_class_default;

	  $this->data['´µ¼ÔID'] = $this->allocate_new_patient_id();
	  $this->data["Æþ³°¶èÊ¬"] = $_mx_config_pt_class_default;
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    if ($this->_validate() != 'ok')
      return 'failure';

    $orig_id = $this->id;
    if (($ok = simple_object_edit::try_commit(&$db)) != 'ok')
      return $ok;
     
     
    return 'ok';
  }

}
?>
