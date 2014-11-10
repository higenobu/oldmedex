<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
//include_once $_SERVER['DOCUMENT_ROOT']. '/lib/dodwell.php';
//delete furigana  09-22-2013
//for LCM
$_lib_u_manage_patient_basic_cfg = array
(
 'TABLE' => '������Ģ',
 'COLS' => array("����ID", "��", "̾",country,tel, 
		 "����", "������", "��ǯ����","birth",
		 "����0", "����1",
		 "����2", "����3", "����4",
		 "��������",
		 "��������",
		 "�᡼�륢�ɥ쥹",
		 "������ʬ",
		"�¼�",
		 

		 "ȯ����", "������", "�ౡͽ����", "�ౡͽ�ꡦ����",
		 "��˴��", "����",
		 "������", "���Ū�԰���", "��˾����", "������", "���ԥޡ���",
		 "��շ����£ϼ�", "��շ��ң輰",
		 "�ȣ£󹳸�",
		 "����륮��",
		 "������",
		 "Ʃ�ϴ��ԥե饰",
		 "��̳��̾", "��̳��͹���ֹ�", "��̳�轻��",
		 "��̳�������ֹ�",
		 "������̾", "������͹���ֹ�", "�����轻��",
		 "�����������ֹ�"),

 'ENABLE_QBE' => array(array('Column' => '����ID',
'Label' => 'PatientID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		      array('Column' =>  "��",
'Label'=>'Last'),
array('Column' =>  "̾",
			'Label'=>'First'),
 		        
		       
		       '//',
		       array('Column' => "����",'Label' => 'Sex', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),

		      
 
array('Column' => "ptage",
					'Label' => 'Age',
				    'Compare' => 'EXTRACT(YEAR FROM AGE("��ǯ����"))',
				    'Draw' => 'text',
				    'Option' => array('size' => 3),
				    ),

		       ),
'LCOLS' => array(array('Column' => '����ID',
			'Label' => 'PatientID',
			      
			    
			     ),
		      array('Column' =>  "��",
			'Label'=>'Last'),
array('Column' =>  "̾",
			'Label'=>'First'),),

 'ALLOW_SORT' => 1,

'LLAYO' => array(array('Column' => '����ID',
			'Label' => 'PatientID',
			      
			    
			     ),
		      array('Column' =>  "��",
			'Label'=>'Last'),
		 array('Column' =>  "̾",
			'Label'=>'First'),
array('Column' => "����",'Label' => 'Sex', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),

		      array('Column' => "��ǯ����",
			'Label'=>'DOB'),
		  

		  ),
 'DCOLS' => array(array('Column' => '����ID',
			'Label' => 'PatientID',
			      
			    
			     ),
		      array('Column' =>  "��",
			'Label'=>'Last'),
		 array('Column' =>  "̾",
			'Label'=>'First'),
array('Column' => "����",'Label' => 'Sex', 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),

		      array('Column' => "��ǯ����",
			'Label'=>'DOB'),
array('Column' => "����3", 'Label' => "Street"),
array('Column' => "����2", 'Label' => "City"),

array('Column' => "����1", 'Label' => "State"),
 array('Column' =>"country", 'Label' => "Country"),

array('Column' => "����0", 'Label' => "Zip"),
		  
array('Column' => 'tel','Label' => 'tel'),
			      
			    
			   
		      array('Column' =>  "��������",
			'Label'=>'Cell'),
		 array('Column' =>  "�᡼�륢�ɥ쥹",
			'Label'=>'Mail'),
array('Column' =>  "��̳��̾",
			'Label'=>'Company'),
//"�����������ֹ�"
array('Column' =>  "�����������ֹ�",
			'Label'=>'Company tel'),
array('Column' =>  "����",
			'Label'=>'NOTE', 
'Draw' => 'textarea',
		'Option' => array('cols' =>30,'rows'=>5),
),
 
 

		 array('Column' => 'CreatedBy',
			'Label' => 'record',
			'Draw' => 'user'),
		  ),
//**************************************************
 'ECOLS' => array(array('Column' => "����ID",
'Label' => 'PatientID',
			'Option' => array('ime' => 'disabled',
					  'zeropad' => $_mx_patient_id_zeropad,
					  'validate' => 'nonnull')),
		   
array('Column' =>  "��",
			'Label'=>'Last',
		 
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "̾",
'Label'=>'First',
			'Option' => array('validate' => 'nonnull')),
		  
		  array('Column' => "����",'Label' => 'Sex', 'Draw' => 'enum',
			     
			     'Enum' => array('M' => 'male', 'F' => 'female',
					     '' => '(n/a)')),
		  array('Column' => "��ǯ����",
'Label'=>'DOB',
			'Option' => array('ime' => 'disabled',
					   )),
		  
		  array('Column' => "����3", 'Label' => "Street", 'Option' => array('add_id' => 1)),
		  array('Column' => "����2", 'Label' => "City", 'Option' => array('add_id' => 1)),
		  array('Column' => "����1", 'Label' => "State", 'Option' => array('add_id' => 1)),
array('Column' => "����0",
			'Label' => "Zip Code",
//			'Draw' => 'post_code',
			'Draw' => 'text',
			'Option' => array('ime' => 'disabled',
					  'Zip' => '����0',
					  'State' => '����1',
					  'City' => '����2',
					  'Street' => '����3',
					  'add_id' => 1,
					  )),

array('Column' => "country", 'Label' => "Country", 'Option' => array('add_id' => 1)),
 array('Column' => "tel", 'Label' => "tel", 'Option' => array('add_id' => 1)),
		   
		  array('Column' => "��������",
'Label'=>'Cell',
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "�᡼�륢�ɥ쥹",
'Label'=>'Mail',
			'Draw' => 'text',
			'Option' => array('size' => 30)),
array('Column' =>  "��̳��̾",
			'Label'=>'Company', 'Draw' => 'text'),
//"�����������ֹ�"
array('Column' =>  "�����������ֹ�",
			'Label'=>'Company tel','Draw'=>'text'),
array('Column' =>  "����",
			'Label'=>'NOTE', 'Draw' => 'textarea',
'Option' => array('cols' =>30,'rows'=>5),),
 


		  ),
 );


$_lib_u_manage_patient_basic_cfg['HSTMT'] =
('SELECT "ObjectID", "CreatedBy", ' .
 implode(', ', array_map('mx_db_sql_quote_name',
			 $_lib_u_manage_patient_basic_cfg['COLS'])) .
 ', ("��" || "̾") AS "��̾"' .
',EXTRACT(YEAR FROM AGE("��ǯ����")) AS "ptage" '.
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
/*
    if ($data["��ǯ����"]) {
      $data["��ǯ����"]=sprintf("%02d/%02d/%04d",
		
		 substr($data["��ǯ����"],5,2),
		substr($data["��ǯ����"],8,2),
		substr($data["��ǯ����"],0,4));
    }
*/

  }
  function enum_list($desc) {
	  if ($desc['Column'] == '���ԥޡ���') {
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
/*
    if ($data["��ǯ����"]) {
      $data["��ǯ����"]=sprintf("%02d/%02d/%04d",
		
		 substr($data["��ǯ����"],5,2),
		substr($data["��ǯ����"],8,2),
		substr($data["��ǯ����"],0,4));
    }
*/

//0109-2014
/*
if ($data["tel"] && !strpos($data["tel"],"-")) {
//$data["tel"]=str_replace("-","",$data["tel"]);
      $data["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($data["tel"],0,3),
		substr($data["tel"],3,3),
		substr($data["tel"],6,4));
    }
if ($data["��������"] && !strpos("��������"],"-")  ) {
$data["��������"]=str_replace("-","",$data["��������"]);
      $data["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($data["tel"],0,3),
		substr($data["tel"],3,3),
		substr($data["tel"],6,4));
    }
if ($data["�����������ֹ�"] && !strpos("�����������ֹ�"],"-")  ) {
$data["�����������ֹ�"]=str_replace("-","",$data["�����������ֹ�"]);
      $data["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($data["tel"],0,3),
		substr($data["tel"],3,3),
		substr($data["tel"],6,4));
    }
*/

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
	  if ($desc['Column'] == '��˾����')
		  return mx_dbenum_patientgroup();
	  if ($desc['Column'] == '���ԥޡ���')
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
      $r = mx_db_fetch_single($db, 'SELECT "ObjectID" FROM "������Ģ"
WHERE "����ID" = ' . mx_db_sql_quote($d['����ID']));
      if (is_array($r) || is_null($r)) {
	$this->err("(����ID): ���ꤵ�줿�ͤϤ��Ǥ˻Ȥ��Ƥ��ޤ���\n");
	$bad++;
      }
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

     
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
/*
if ($data["��ǯ����"]) {

      $data["��ǯ����"]=sprintf("%02d/%02d/%04d",
		
		 substr($data["��ǯ����"],5,2),
		substr($data["��ǯ����"],8,2),
		substr($data["��ǯ����"],0,4));
    }
*/
//0315-2014
/*
if ($data["��������"] && !strpos("��������"],"-")  ) {
$data["��������"]=str_replace("-","",$data["��������"]);
      $data["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($data["tel"],0,3),
		substr($data["tel"],3,3),
		substr($data["tel"],6,4));
    }
if ($data["�����������ֹ�"] && !strpos("�����������ֹ�"],"-")  ) {
$data["�����������ֹ�"]=str_replace("-","",$data["�����������ֹ�"]);
      $data["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($data["tel"],0,3),
		substr($data["tel"],3,3),
		substr($data["tel"],6,4));
    }
*/

//0109-2014
if ($data["tel"]  ) {
$data["tel"]=str_replace("-","",$data["tel"]);
      $data["tel"]=sprintf("%03d-%03d-%04d",
		
		 substr($data["tel"],0,3),
		substr($data["tel"],3,3),
		substr($data["tel"],6,4));
    }
 }
  function annotate_form_data(&$data) {
	  foreach (array('��','̾') as $c) {
		  if (is_null($data[$c]))
			  continue;
		  $data[$c] = trim(mx_xlate_jzspace($data[$c]));
	  }
	  $fn = $data['��'];
	  $gn = $data['̾'];

	  if ($gn == '' && $fn != '') {
		  $m = array();
		  if (preg_match('/^(.*?) +(.*)$/', $fn, &$m)) {
			  $data['��'] = $m[1];
			  $data['̾'] = $m[2];
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
		  $tsql = '"����ID" <= ' . mx_db_sql_quote($top) . ' AND ';
	  }
	  else {
		  $bottom = $_mx_auto_allocate_patient_id;
		  $top = undef;
		  $tsql = '';
	  }

	  $db = mx_db_connect();
	  $stmt = 'SELECT max("����ID") AS "I" FROM "������Ģ" WHERE ' .
		  $tsql .
		  '"Superseded" IS NULL';
	  if($_mx_patient_id_zeropad > 0)
	    $stmt .= ' AND length("����ID") = ' . $_mx_patient_id_zeropad;

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

	  $this->data['����ID'] = $this->allocate_new_patient_id();
	  $this->data["������ʬ"] = $_mx_config_pt_class_default;
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
      $stmt = 'SELECT "ObjectID" FROM "����ô������" WHERE
               "Superseded" IS NULL AND "����" = ' . $this->id;
      $curr = mx_db_fetch_single($db, $stmt);
      $stmt = 'INSERT INTO "����ô������" ("����", "CreatedBy") VALUES (' .
	$this->id . ', ' .
	mx_db_sql_quote($mx_authenticate_current_user) . ')';
      if (! $curr && ! is_null($curr) && pg_query($db, $stmt))
	; // All is well.
      else
	return 'failure';
*/

    }
    if ($this->id &&
	($this->data['������ʬ'] != 'I') &&
	($this->data['������ʬ'] != 'W') &&
	!mx_db_validate_date($this->data['�ౡͽ����'])) {
/*
	    $dis = sprintf("'%s'", $this->data['�ౡͽ����']);
	    $stmt = sprintf(
		    'DELETE FROM "�¾�����"
		    WHERE "��ͭ��" = %d AND (%s <= "��ͭ����")',
		    $this->id, $dis);
	    pg_query($db, $stmt);
	    $stmt = sprintf(
		    'UPDATE "�¾�����" SET "��ͭ��λ" = %s
		     WHERE "��ͭ��" = %d AND
		     ("��ͭ����" <= %s) AND (%s < "��ͭ��λ")',
		    $dis, $this->id, $dis, $dis);
	    pg_query($db, $stmt);
*/

    }
    return 'ok';
  }

}
?>
