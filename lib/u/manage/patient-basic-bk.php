<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/dodwell.php';
//delete furigana  09-22-2013
$_lib_u_manage_patient_basic_cfg = array
(
 'TABLE' => '患者台帳',
 'COLS' => array("患者ID", "姓", "名", 
		 "性別", "利き手", "生年月日","birth",
		 "住所0", "住所1",
		 "住所2", "住所3", "住所4",
		 "加入電話",
		 "携帯電話",
		 "メールアドレス",
		 "入外区分",
		"病室",
		 "保険者番号",
		 "被保険者",
		 "被保険者手帳の記号",
		 "被保険者手帳の番号",
		 "公費負担者番号",
		 "公費負担医療の受給者番号",
		 "公費負担者番号2",
		 "公費負担医療の受給者番号2",
		 "公費負担者番号3",
		 "公費負担医療の受給者番号3",

		 "発症日", "入院日", "退院予定日", "退院予定・見込",
		 "死亡日", "備考",
		 "回復期", "医学的不安定", "希望病棟", "入院科", "患者マーク",
		 "血液型ＡＢＯ式", "血液型Ｒｈ式",
		 "ＨＢｓ抗原",
		 "アレルギー",
		 "感染症",
		 "透析患者フラグ",
		 "勤務先名", "勤務先郵便番号", "勤務先住所",
		 "勤務先電話番号",
		 "請求先名", "請求先郵便番号", "請求先住所",
		 "請求先電話番号"),

 'ENABLE_QBE' => array(array('Column' => '患者ID',
'Label' => 'PatientID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		      array('Column' =>  "姓",
'Label'=>'Last'),
array('Column' =>  "名",
			'Label'=>'First'),
 		        
		       
		       '//',
		       array('Column' => "性別",'Label' => 'Sex', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),

		      array('Column' => "birth",
			'Label'=>'DayOfBirth'),
 

		       ),
'LCOLS' => array(array('Column' => '患者ID',
			'Label' => 'PatientID',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'Last'),
array('Column' =>  "名",
			'Label'=>'First'),),

 'ALLOW_SORT' => 1,

'LLAYO' => array(array('Column' => '患者ID',
			'Label' => 'PatientID',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'Last'),
		 array('Column' =>  "名",
			'Label'=>'First'),
array('Column' => "性別",'Label' => 'Sex', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),

		      array('Column' => "生年月日",
			'Label'=>'DayOfBirth'),
		  

		  ),
 'DCOLS' => array(array('Column' => '患者ID',
			'Label' => 'PatientID',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'Last'),
		 array('Column' =>  "名",
			'Label'=>'First'),
array('Column' => "性別",'Label' => 'Sex', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),

		      array('Column' => "生年月日",
			'Label'=>'DayOfBirth'),
		   
		  array('Column' => "住所0", 'Label' => "Zip"),
		  array('Column' => "住所1", 'Label' => "State"),
		  array('Column' => "住所2", 'Label' => "City"),
		  array('Column' => "住所3", 'Label' => "Street"),
		  array('Column' => "住所4", 'Label' => "Room"),
array('Column' => '加入電話',
			'Label' => 'Home tel',
			      
			    
			     ),
		      array('Column' =>  "携帯電話",
			'Label'=>'Cell'),
		 array('Column' =>  "メールアドレス",
			'Label'=>'Mail'),
array('Column' =>  "勤務先名",
			'Label'=>'Occupation'),

		 array('Column' => 'CreatedBy',
			'Label' => 'record',
			'Draw' => 'user'),
		  ),
 'ECOLS' => array(array('Column' => "患者ID",
'Label' => 'PatientID',
			'Option' => array('ime' => 'disabled',
					  'zeropad' => $_mx_patient_id_zeropad,
					  'validate' => 'nonnull')),
		   
array('Column' =>  "姓",
			'Label'=>'Last',
		 
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "名",
'Label'=>'First',
			'Option' => array('validate' => 'nonnull')),
		  
		  array('Column' => "性別",'Label' => 'Sex', 'Draw' => 'enum',
			     
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),
		  array('Column' => "生年月日",
'Label'=>'DayOfBirth',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "住所0",
			'Label' => "Zip Code",
//			'Draw' => 'post_code',
			'Draw' => 'text',
			'Option' => array('ime' => 'disabled',
					  'Zip' => '住所0',
					  'Prefecture' => '住所1',
					  'City' => '住所2',
					  'Block' => '住所3',
					  'add_id' => 1,
					  )),
		  array('Column' => "住所1", 'Label' => "State", 'Option' => array('add_id' => 1)),
		  array('Column' => "住所2", 'Label' => "City", 'Option' => array('add_id' => 1)),
		  array('Column' => "住所3", 'Label' => "Street", 'Option' => array('add_id' => 1)),
		  array('Column' => "住所4", 'Label' => "Room", 'Option' => array('add_id' => 1)),
		  array('Column' => "加入電話",
'Label'=>'Home tel',
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "携帯電話",
'Label'=>'Cell',
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "メールアドレス",
'Label'=>'Mail',
			'Draw' => 'text',
			'Option' => array('size' => 30)),
array('Column' =>  "勤務先名",
			'Label'=>'Occupation', 'Draw' => 'text'),

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
//1030-2013
function annotate_row_data(&$data) {
    if ($data["生年月日"]) {
      $data["生年月日"]=sprintf("%02d/%02d/%04d",
		
		 substr($data["生年月日"],5,2),
		substr($data["生年月日"],8,2),
		substr($data["生年月日"],0,4));
    }

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
 //1030-2013 
  function annotate_row_data(&$data) {
    if ($data["生年月日"]) {
      $data["生年月日"]=sprintf("%02d/%02d/%04d",
		
		 substr($data["生年月日"],5,2),
		substr($data["生年月日"],8,2),
		substr($data["生年月日"],0,4));
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
	$this->err("(患者ID): 指定された値はすでに使われています。\n");
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

  function annotate_row_data(&$data) { $this->trim_data(&$data);
if ($data["生年月日"]) {
      $data["生年月日"]=sprintf("%02d/%02d/%04d",
		
		 substr($data["生年月日"],5,2),
		substr($data["生年月日"],8,2),
		substr($data["生年月日"],0,4));
    }
 }
  function annotate_form_data(&$data) {
	  foreach (array('姓','名') as $c) {
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
    if (! $orig_id) {
      // We created a patient.  Need to create the associated
      // Patient-Employee association hook, if it does not exist.
/*
      $stmt = 'SELECT "ObjectID" FROM "患者担当職員" WHERE
               "Superseded" IS NULL AND "患者" = ' . $this->id;
      $curr = mx_db_fetch_single($db, $stmt);
      $stmt = 'INSERT INTO "患者担当職員" ("患者", "CreatedBy") VALUES (' .
	$this->id . ', ' .
	mx_db_sql_quote($mx_authenticate_current_user) . ')';
      if (! $curr && ! is_null($curr) && pg_query($db, $stmt))
	; // All is well.
      else
	return 'failure';
*/

    }
    if ($this->id &&
	($this->data['入外区分'] != 'I') &&
	($this->data['入外区分'] != 'W') &&
	!mx_db_validate_date($this->data['退院予定日'])) {
/*
	    $dis = sprintf("'%s'", $this->data['退院予定日']);
	    $stmt = sprintf(
		    'DELETE FROM "病床管理"
		    WHERE "占有者" = %d AND (%s <= "占有開始")',
		    $this->id, $dis);
	    pg_query($db, $stmt);
	    $stmt = sprintf(
		    'UPDATE "病床管理" SET "占有終了" = %s
		     WHERE "占有者" = %d AND
		     ("占有開始" <= %s) AND (%s < "占有終了")',
		    $dis, $this->id, $dis, $dis);
	    pg_query($db, $stmt);
*/

    }
    return 'ok';
  }

}
?>
