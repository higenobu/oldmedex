<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/dodwell.php';

$_lib_u_manage_patient_basic_cfg = array
(
 'TABLE' => '患者台帳',
 'COLS' => array("患者ID", "姓", "名", "フリガナ",
		 "性別", "利き手", "生年月日",
		 "住所0", "住所1",
		 "住所2", "住所3", "住所4",
		 "加入電話",
		 "携帯電話",
		 "メールアドレス",		
		 "請求先電話番号"),

 'ENABLE_QBE' => array(array('Column' => '患者ID',
'Label' => '分類番号',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		      array('Column' =>  "姓",
'Label'=>'大分類'),
array('Column' =>  "名",
			'Label'=>'中分類'),
 		        array('Column' =>  "フリガナ",
 			'Label'=>'小分類'),
		      
		        
		       
 
 

		       ),

 'LCOLS' => array(array('Column' => '患者ID',
			'Label' => '分類番号',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'大分類'),
array('Column' =>  "名",
			'Label'=>'中分類'),
array('Column' =>  "フリガナ",
 			'Label'=>'小分類'),
),

 'ALLOW_SORT' => 1,
 
'LLAYO' => array(array('Column' => '患者ID',
			'Label' => '分類番号',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'大分類'),
		 array('Column' =>  "名",
			'Label'=>'中分類'),
array('Column' =>  "フリガナ",
 			'Label'=>'小分類'),
 

		  ),
 'DCOLS' => array(array('Column' => '患者ID',
			'Label' => '分類番号',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'大分類'),
		 array('Column' =>  "名",
			'Label'=>'中分類'),
array('Column' =>  "フリガナ",
 			'Label'=>'小分類'),
 
 
		 
		   
 
		  array('Column' => 'CreatedBy',
			'Label' => 'record',
			'Draw' => 'user'),
		  ),

 'ECOLS' => array(array('Column' => "患者ID",
'Label' => '分類番号',
			'Option' => array('ime' => 'disabled',
					  'zeropad' => $_mx_patient_id_zeropad,
					  'validate' => 'nonnull')),
		   
array('Column' =>  "姓",
			'Label'=>'大分類',
		 
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "名",
'Label'=>'中分類',
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "フリガナ",
'Label'=>'小分類',
			'Option' => array('validate' => 'nonnull')),
 
 ),
 

		 
 );
$_lib_u_manage_patient_basic_cfg['HSTMT'] =
('SELECT "ObjectID", "CreatedBy", ' .
 implode(', ', array_map('mx_db_sql_quote_name',
			 $_lib_u_manage_patient_basic_cfg['COLS'])) .
 ', ("姓" || "名") AS "姓名"' .
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
	  if ($desc['Column'] == '患者マーク') {
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
    if ($data["入外区分"] == 'O') {
      $data["退院予定日"] = 'N/A';
      $data["退院予定・見込"] = 'N/A';
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
	  if ($desc['Column'] == '希望病棟')
		  return mx_dbenum_patientgroup();
	  if ($desc['Column'] == '患者マーク')
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
      $r = mx_db_fetch_single($db, 'SELECT "ObjectID" FROM "患者台帳"
WHERE "患者ID" = ' . mx_db_sql_quote($d['患者ID']));
      if (is_array($r) || is_null($r)) {
	$this->err("(分類番号): 指定された値はすでに使われています。\n");
	$bad++;
      }
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

    if (($d['入外区分'] == 'I') || ($d['入外区分'] == 'W')) {
	    foreach (array('入院日', '退院予定日') as $c) {
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
	  foreach (array('姓','名','フリガナ') as $c) {
		  if (is_null($data[$c]))
			  continue;
		  $data[$c] = trim(mx_xlate_jzspace($data[$c]));
	  }
	  $fn = $data['姓'];
	  $gn = $data['名'];

	  if ($gn == '' && $fn != '') {
		  $m = array();
		  if (preg_match('/^(.*?) +(.*)$/', $fn, &$m)) {
			  $data['姓'] = $m[1];
			  $data['名'] = $m[2];
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
		  $tsql = '"患者ID" <= ' . mx_db_sql_quote($top) . ' AND ';
	  }
	  else {
		  $bottom = $_mx_auto_allocate_patient_id;
		  $top = undef;
		  $tsql = '';
	  }

	  $db = mx_db_connect();
	  $stmt = 'SELECT max("患者ID") AS "I" FROM "患者台帳" WHERE ' .
		  $tsql .
		  '"Superseded" IS NULL';
	  if($_mx_patient_id_zeropad > 0)
	    $stmt .= ' AND length("患者ID") = ' . $_mx_patient_id_zeropad;

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

	  $this->data['患者ID'] = $this->allocate_new_patient_id();
	  $this->data["入外区分"] = $_mx_config_pt_class_default;
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
