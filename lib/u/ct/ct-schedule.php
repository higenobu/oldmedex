<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/ct.php';

$_lib_u_ct_schedule_base_stmt =
'SELECT CTS."ObjectID" as "ObjectID",CT."ObjectID" as "����",
        CTS."ͽ�����",
        CTS."�׾����������",
	CTS."��٥�" as "��٥�",
        CTS."�������å�" as "�������å�",
        TS."Name" as "�������å�̾"
FROM "�����������塼��" as CTS JOIN "����" as CT
          ON CT."ObjectID" = CTS."����" AND CT."Superseded" IS NULL AND CTS."Superseded" IS NULL
     LEFT JOIN "test_set" AS TS
      ON CTS."�������å�" = TS."ObjectID" AND TS."Superseded" IS NULL
';


$__name = array();

function get_test_sets() {
  global $_oid;
  global $__name;
  $db = mx_db_connect();
  $stmt = 'SELECT "ObjectID", "Name" FROM "test_set"
           WHERE "Superseded" IS NULL AND "CT"=\'Y\'
           ORDER BY "SortOrder"';
  $rs = mx_db_fetch_all($db, $stmt);
  foreach($rs as $r)
    $__name[$r['ObjectID']] = $r['Name'];
}

function _lib_ct_schedule(&$cfg) {
  global $_lib_u_ct_schedule_base_stmt;
  global $__name;
  get_test_sets();
  $cfg = array('TABLE' => '�����������塼��',
	       'COLS' => array("ObjectID", "����", "ͽ�����", "�׾����������",
			       "��٥�", "�������å�", "�������å�̾",
			       "���θ���"),
	       'LCOLS' => array(
				array('Column' => "�׾����������",
				      'Label' => "����ɼ�׾����������",
				      ),
				array('Column' => "��٥�",
				      'Label' => "��������"
				      ),
				"ͽ�����",
				array('Column' => "�������å�",
				      'Draw' => 'enum',
				      'Enum' => $__name,
				      )
				),
	       'DCOLS' => array("ͽ�����",
				array('Column' => "�׾����������",
				      'Label' => "����ɼ�׾����������",
				      ),
				"��٥�", 
				array('Column' => "�������å�",
				      'Draw' => 'enum',
				      'Enum' => $__name,
				      ),
				"���θ���"),
	       'ECOLS' => array(array('Column' => "ͽ�����",
				      'Draw' => 'datetime'
				      ),
				"�׾����������",
				
				"��٥�",
				array('Column' => "�������å�",
				      'Draw' => 'enum',
				      'Enum' => $__name,
				      ),
				),
	       'ICOLS' => array("����", "ͽ�����", "�׾����������",
				"��٥�", "�������å�", "���θ���"),
	       // 'LIST_IDS' => array("�������塼��"),
	       //'UNIQ_ID' => 'CTS."ObjectID"'
	       );
    $cfg['HSTMT'] = $_lib_u_ct_schedule_base_stmt;
    $cfg['STMT'] = $_lib_u_ct_schedule_base_stmt;
}

function _lib_u_ct_schedule_fetch_data(&$this, $cts_oid) {
  global $_lib_u_ct_schedule_base_stmt;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_ct_schedule_base_stmt .
	   ' WHERE CT."ObjectID" = ' . mx_db_sql_quote($this->app->CT_ObjectID) .
	   ' AND CTS."ObjectID" = ' . mx_db_sql_quote($cts_oid));
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];
  return $data;
}

class ct_schedule_display extends simple_object_display {
  var $debug = 1;
  function ct_schedule_display($prefix, &$app) {
    $cfg = array();
    _lib_ct_schedule(&$cfg);
    $this->app = $app;
    $this->data['����'] = $this->app->CT_ObjectID;
    simple_object_display::simple_object_display($prefix, &$cfg);
  }

  function fetch_data($id) {
    return  _lib_u_ct_schedule_fetch_data(&$this, $id);
  }

}

class ct_schedule_edit extends simple_object_edit {
  function ct_schedule_edit($prefix, $app) {
    $cfg = array();
    _lib_ct_schedule(&$cfg);
    $this->app = $app;
    $this->data['����'] = $this->app->CT_ObjectID;
    $this->data['����������'] = $this->app->sod->chosen();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
  function fetch_data($id) {
    return  _lib_u_ct_schedule_fetch_data(&$this, $id);
  }

  function annotate_form_data(&$data) {
    $data['����'] = $this->app->CT_ObjectID;
    simple_object_edit::annotate_form_data($data);
  }
}

class list_of_ct_schedules extends list_of_simple_objects {

  var $debug = 1;

  function list_of_ct_schedules($prefix, $cfg=NULL) {
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function base_fetch_stmt_0() {
    $stmt =  (list_of_simple_objects::base_fetch_stmt_0() .
	      ' WHERE CT."ObjectID" = ' .
	      mx_db_sql_quote($this->app->CT_ObjectID));
     return $stmt;
  }
  
}

class ct_schedule_application extends single_table_application {
  
  var $use_upload = 1;
  var $use_single_pane = 0;
  function ct_schedule_application() {
    $cfg = array();
    $this->app_config = $cfg;
    $this->prefix = '';
    $this->CT_ObjectID = $_REQUEST['CTID'];
    single_table_application::single_table_application();

    if($_REQUEST[$prefix . 'Exec'])
      $this->place_result = $this->ct_place_test_order();
  }

  /* function allow_new() {
    return $this->sod->chosen();
  }
  */

  function list_of_objects($prefix) { // override
    $cfg = array();
    _lib_ct_schedule($cfg);
    $x = new list_of_ct_schedules($prefix, &$cfg);
    $x->app = $this;
    return $x;
  }
  
  function object_display($prefix) { // override
    $x = new ct_schedule_display($prefix, &$this);
    $x->app = $this;
    return $x;
  }
  
  function object_edit($prefix) { //override
    $x = new ct_schedule_edit($prefix, &$this);
    $x->app = $this;
    return $x;
  }

  function left_pane() {
    // not a hook but just want to insert HIDDEN
    mx_formi_hidden('CTID', $this->CT_ObjectID);
    single_table_application::left_pane();
    if($this->place_result)
 	print "<br><br>���θ����ΰ����꤬��λ���ޤ���";
  }

  function draw_plain_new_control($vertical=0) {
	  global $_mx_uniform_control;

	  if ($this->allow_new())
		  mx_formi_submit('New', 'New', mx_img_url('new.png'),
				  $this->msg['New']);
	  else if ($_mx_uniform_control)
		  mx_formi_nosubmit(mx_img_url('noop-new.png'));
	  else
		  return;

	  mx_formi_submit('Exec', '������', NULL,
			  $this->msg['Exec']);

	  if (!$this->use_template)
		  return;
	  if ($vertical)
		  print "<br />";
	  if ($this->allow_new())
		  mx_formi_submit('NewWithTemplate', 'NewWithTemplate',
				  mx_img_url('new-w-template.png'),
				  $this->msg['New With Template']);
	  else if ($_mx_uniform_control)
		  mx_formi_nosubmit(mx_img_url('noop-new-w-template.png'));
	  if ($vertical)
		  print "<br />";
  }

  function ct_place_test_order() {
    /*
   1. insert into test_order ("CreatedBy", "OrderDate", "SampleDate", "Patient")
      VALUES($u->id, $now, $edate, $pid) 
   2. insert into test_order_content
    */
    $ctid = $this->CT_ObjectID;
    $user = $this->u;
    $odate = 'now()';
    
    $db = mx_db_connect();
    $pts = get_patients_by_ct($db, $ctid);
    $test_sets = get_tests_by_ct($db, $ctid);

    foreach($pts as $pt) {
      $pt_oid = $pt['����'];
      # insert into test_order
      foreach($test_sets as $test_set) {
	$stmt = sprintf('INSERT INTO test_order ("Patient", "OrderDate", "CreatedBy", "CTS") VALUES (%d, %s, %s, %d)',
			$pt_oid, mx_db_sql_quote($test_set['ͽ�����']),
			$user, $test_set['ObjectID']);
	pg_query($db, $stmt);
	$cv = get_currval($db, "test_order_ID_seq");
	$tests = get_test_content_by_set($db, $test_set['�������å�']);
	// insert into test_order_content
	foreach($tests as $test) {
	  $stmt = sprintf("INSERT INTO test_order_content (\"TestOrder\", \"TestID\") VALUES (%d, %s)", $cv, $test["TestID"]);
	  pg_query($db, $stmt);
	}
	// 
      }
    }
    return 1;
  }
}

function get_patients_by_ct($db, $ctid) {
  $stmt = 'SELECT "����" FROM "����������" WHERE "Superseded" IS NULL AND "����"=' . mx_db_sql_quote($ctid);
  $rs = mx_db_fetch_all($db, $stmt);
  return $rs;
}

function get_tests_by_ct($db, $ctid) {
  $stmt = 'SELECT "�������å�", "ͽ�����", "ObjectID" FROM "�����������塼��" WHERE "�������å�" IS NOT NULL AND "Superseded" IS NULL AND "����"=' . mx_db_sql_quote($ctid) . ' ORDER BY "ͽ�����"';
  $rs = mx_db_fetch_all($db, $stmt);
  return $rs;
}

function get_test_content_by_set($db, $setid) {
  $stmt = 'SELECT "TestID" FROM "test_set_map" WHERE "SetID"=' . mx_db_sql_quote($setid);
  $rs = mx_db_fetch_all($db, $stmt);
  return $rs;
}

function get_currval($db, $seq) {
  $stmt = "SELECT currval('\"" . $seq . "\"') as cv";
  $v = mx_db_fetch_single($db, $stmt);
  return $v['cv'];
}

function get_test_result_by_test($db, $testid) {
  $stmt = 'SELECT "ObjectID" as "TestID" FROM "test_master"
           WHERE "Parent"=' . mx_db_sql_quote($testid);
  $rs = mx_db_fetch_all($db, $stmt);
  return $rs;
}

?>
