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
		       
		       '//',
		       array('Column' => "性別",'Label' => 'コード', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'M', 'F' => 'F',
					     '' => '(n/a)')),

		      array('Column' => "生年月日",
			'Label'=>'開始日'),
 
/*
		       array('Column' => "入外区分", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('I' => '入院', 'O' => '外来',
					     'E' => '入院判定対象',
					     'W' => '入院待ち',
					     '' => '(未設定)') ),
		       array('Column' => "患者マーク", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => 'lazy'),
*/

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
/*
 'LLAYO' => array('患者ID', "姓", "名", "フリガナ",
		  '//',
		  array('Column' => "性別",
			'Draw' => 'enum',
			'Enum' => array('M' => '男', 'F' => '女',
					NULL => '不明') ),
		  "生年月日",
 
		  array('Column' => "入外区分", 'Draw' => 'enum',
			'Singleton' => 1,
			'Enum' => array('I' => '入院', 'O' => '外来',
					'E' => '入院判定対象',
					'W' => '入院待ち',
					'' => '(未設定)') ),
		  "患者マーク",
 
*/
'LLAYO' => array(array('Column' => '患者ID',
			'Label' => '分類番号',
			      
			    
			     ),
		      array('Column' =>  "姓",
			'Label'=>'大分類'),
		 array('Column' =>  "名",
			'Label'=>'中分類'),
array('Column' =>  "フリガナ",
 			'Label'=>'小分類'),
array('Column' => "性別",'Label' => 'コード', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'M', 'F' => 'F',
					     '' => '(n/a)')),

		      array('Column' => "生年月日",
			'Label'=>'開始日'),
		  

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
array('Column' => "性別",'Label' => 'コード', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'M', 'F' => 'F',
					     '' => '(n/a)')),

		      array('Column' => "生年月日",
			'Label'=>'開始日'),
		   
//		  array('Column' => "住所0", 'Label' => "ZIP"),
//		  array('Column' => "住所1", 'Label' => "STATE"),
//		  array('Column' => "住所2", 'Label' => "CITY"),
//		  array('Column' => "住所3", 'Label' => "STREET"),
//		  array('Column' => "住所4", 'Label' => "ROOM"),
//array('Column' => '加入電話',
//			'Label' => 'Home tel',
			      
//			    
//			     ),
		      array('Column' =>  "携帯電話",
			'Label'=>'cell'),
		 array('Column' =>  "メールアドレス",
			'Label'=>'mail'),
array('Column' =>  "勤務先名",
			'Label'=>'Occupation'),
		   
/* 04-23-2012
		  array('Column' => "入外区分",
			'Draw' => 'enum',
			'Enum' => array('I' => '入院', 'O' => '外来',
					'E' => '入院判定対象',
					'W' => '入院待ち',
					NULL => '未指定') ),

		  "保険者番号",
		  array('Column' => "被保険者",
			'Draw' => 'enum',
			'Enum' => array('1' => '本人', '2' => '家族',
					NULL => '未指定') ),
		  "被保険者手帳の記号",
		  "被保険者手帳の番号",
		  "公費負担者番号",
		  "公費負担医療の受給者番号",
		  "公費負担者番号2",
		  "公費負担医療の受給者番号2",
		  "公費負担者番号3",
		  "公費負担医療の受給者番号3",

		  "発症日", "入院日", "退院予定日",
		  array('Column' => "退院予定・見込",
			'Draw' => 'enum',
			'Enum' => array('F' => '予定', 'E' => '見込',
					'N/A' => 'N/A', 
					NULL => '未指定') ),
		  array('Column' => "希望病棟",
			'Label' => "患者グループ"),
		  "入院科",
		  "患者マーク",
		  "死亡日", "備考",
		  array('Column' => "回復期",
			'Draw' => 'enum',
			'Enum' => array('A' => '対象 A',
					'B' => '対象 B',
					'C' => '対象 C',
					'D' => '対象 D',
					'E' => '対象 E',
					'Z' => '対象外',
					NULL => '(未設定)')),
		  array('Column' => "医学的不安定",
			'Draw' => 'enum',
			'Enum' => array('S' => '安定', 'U' => '不安定',
					'W' => '要注意',
					NULL => '(未設定)')),
		  "血液型ＡＢＯ式", "血液型Ｒｈ式",
		  "ＨＢｓ抗原",
		  "アレルギー",
		  "感染症",
		  "透析患者フラグ",
		  "勤務先名", "勤務先郵便番号", "勤務先住所",
		  "勤務先電話番号",
		  "請求先名", "請求先郵便番号", "請求先住所",
		  "請求先電話番号",
			"病室",
*/

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
		  array('Column' => "性別",'Label' => 'コード', 'Draw' => 'enum',
			     
			     'Enum' => array('M' => 'M', 'F' => 'F',
					     '' => '(n/a)')),
		  array('Column' => "生年月日",
'Label'=>'開始日',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
/*
		  array('Column' => "住所0",
			'Label' => "ZIP CODE",
//			'Draw' => 'post_code',
			'Draw' => 'text',
			'Option' => array('ime' => 'disabled',
					  'zip' => '住所0',
					  'prefecture' => '住所1',
					  'city' => '住所2',
					  'block' => '住所3',
					  'add_id' => 1,
					  )),
		  array('Column' => "住所1", 'Label' => "STATE", 'Option' => array('add_id' => 1)),
		  array('Column' => "住所2", 'Label' => "CITY", 'Option' => array('add_id' => 1)),
		  array('Column' => "住所3", 'Label' => "STREET", 'Option' => array('add_id' => 1)),
		  array('Column' => "住所4", 'Label' => "ROOM", 'Option' => array('add_id' => 1)),
		  array('Column' => "加入電話",
'Label'=>'Home tel',
			'Option' => array('ime' => 'disabled')),
*/

		  array('Column' => "携帯電話",
'Label'=>'cell',
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "メールアドレス",
'Label'=>'mail',
			'Draw' => 'text',
			'Option' => array('size' => 30)),
array('Column' =>  "勤務先名",
			'Label'=>'Occupation', 'Draw' => 'text'),
/*
		  array('Column' => "入外区分",
			'Draw' => 'enum',
			'Enum' => array('I' => '入院', 'O' => '外来',
					'E' => '入院判定対象',
					'W' => '入院待ち',
					NULL => '未指定') ),

		  array('Column' => "保険者番号",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "被保険者",
			'Draw' => 'enum',
			'Enum' => array('1' => '本人', '2' => '家族',
					NULL => '未指定') ),
		  array('Column' => "被保険者手帳の記号",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "被保険者手帳の番号",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "公費負担者番号",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "公費負担医療の受給者番号",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "公費負担者番号2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "公費負担医療の受給者番号2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "公費負担者番号3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "公費負担医療の受給者番号3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),

		  array('Column' => "発症日",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "入院日",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "退院予定日",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "退院予定・見込",
			'Draw' => 'enum',
			'Enum' => array('F' => '予定', 'E' => '見込',
					NULL => '未指定') ),
		  array('Column' => "希望病棟",
			'Label' => "患者グループ",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  array('Column' => "患者マーク",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  "入院科",
		  array('Column' => "死亡日",
			'Option' => array('ime' => 'disabled')),
		  "備考",
		  array('Column' => "回復期",
			'Draw' => 'enum',
			'Enum' => array('A' => '対象 A',
					'B' => '対象 B',
					'C' => '対象 C',
					'D' => '対象 D',
					'E' => '対象 E',
					'Z' => '対象外',
					NULL => '(未設定)')),
		  array('Column' => "医学的不安定",
			'Draw' => 'enum',
			'Enum' => array('S' => '安定', 'U' => '不安定',
					'W' => '要注意',
					NULL => '(未設定)')),
		  array('Column' => "血液型ＡＢＯ式",
			'Draw' => 'enum',
			'Enum' => array('A' => 'A', 'B' => 'B',
					'AB' => 'AB', 'O' => 'O',
					NULL => '不明')),
		  array('Column' => "血液型Ｒｈ式",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => '不明')),
		  array('Column' => "ＨＢｓ抗原",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => '不明')),
		  "アレルギー",
		  "感染症",
		  array('Column' => "透析患者フラグ",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => '不明')),

		  "勤務先名",
		  array('Column' => "勤務先郵便番号",
			'Option' => array('ime' => 'disabled')),
		  "勤務先住所",
		  array('Column' => "勤務先電話番号",
			'Option' => array('ime' => 'disabled')),
		  "請求先名",
		  array('Column' => "請求先郵便番号",
			'Option' => array('ime' => 'disabled')),
		  "請求先住所",
		  array('Column' => "請求先電話番号",
			'Option' => array('ime' => 'disabled')),
		array('Column' => "病室",
			),
*/

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
    if (! $orig_id) {
      // We created a patient.  Need to create the associated
      // Patient-Employee association hook, if it does not exist.
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
    }
    if ($this->id &&
	($this->data['入外区分'] != 'I') &&
	($this->data['入外区分'] != 'W') &&
	!mx_db_validate_date($this->data['退院予定日'])) {
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
    }
    return 'ok';
  }

}
?>
