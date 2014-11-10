<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/department.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-cat.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-rank.php';

function type_enum_values() {
	   
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "����"
FROM "�������ɽ"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c['����'];
	  }
	  return $ret;
  }


function rank_enum_values() {
	   
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "����"
FROM "���̰���ɽ"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c["����"];
	  }
	  return $ret;
  }

function dept_enum_values() {
	   
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "��ʬ��"
FROM "�������ɽ"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c["��ʬ��"];
	  }
	  return $ret;
  }

 
 
$_lib_u_manage_employee_cfg = array
(
 'TABLE' => '������Ģ',
 
 
 
 'COLS' => array( userid,"����ID", 
       "����", "����", "����", "��", "̾",  "����", "��ǯ����", "��������"
	 
 ),

 'LCOLS' => array(

array('Column' =>  "����ID",'Label'=>'ID'),
array('Column' =>  "��",'Label'=>'LastName'),

array('Column' =>  "̾",'Label'=>'FirstName'),
array('Column' => '����',
					'Label' => 'emptype',
				   
				       'Draw' => 'enum',
				       'Enum' =>type_enum_values()),
array('Column' => '����',
					'Label' => 'rank',
				   
				       'Draw' => 'enum',
				       'Enum' =>rank_enum_values()),

array('Column' => '����',
					'Label' => 'dept',
				   
				       'Draw' => 'enum',
				       'Enum' =>dept_enum_values()),

 

),
			 
 'DCOLS' => array(
array('Column' =>  "����ID",'Label'=>'ID'),
array('Column' =>  "��",'Label'=>'LastName'),

array('Column' =>  "̾",'Label'=>'FirstName'),
		 
 
array('Column' =>  "��ǯ����",'Label'=>'DOB'),
array('Column' =>  "��������",'Label'=>'Tel'),
 
 array('Column' => '����',
					'Label' => 'emptype',
				   
				       'Draw' => 'enum',
				       'Enum' =>type_enum_values()),
array('Column' => '����',
					'Label' => 'rank',
				   
				       'Draw' => 'enum',
				       'Enum' =>rank_enum_values()),
 
array('Column' => '����',
					'Label' => 'dept',
				   
				       'Draw' => 'enum',
				       'Enum' =>dept_enum_values()),

 
array('Column' =>  "userid",'Label'=>'internal uid'),
),

 'ECOLS' => array(
 
array('Column' =>  "����ID",'Label'=>'ID'),
array('Column' =>  "��",'Label'=>'LastName'),

array('Column' =>  "̾",'Label'=>'FirstName'),
		 
 
array('Column' =>  "��ǯ����",'Label'=>'DOB'),
array('Column' =>  "��������",'Label'=>'Tel'),
array('Column' => '����',
					'Label' => 'emptype',
				   
				       'Draw' => 'enum',
				       'Enum' =>type_enum_values()),
array('Column' => '����',
					'Label' => 'rank',
				   
				       'Draw' => 'enum',
				       'Enum' =>rank_enum_values()),
array('Column' => '����',
					'Label' => 'dept',
				   
				       'Draw' => 'enum',
				       'Enum' =>dept_enum_values()),
 
 
 
),
 
 );

 


class list_of_employees extends list_of_simple_objects {

  function list_of_employees($prefix, $cfg=NULL) {
    global $_lib_u_manage_employee_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_manage_employee_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
 
}

class employee_display extends simple_object_display {

  function employee_display($prefix) {
    global $_lib_u_manage_employee_cfg;
    simple_object_display::simple_object_display
      ($prefix, $_lib_u_manage_employee_cfg);
  }
 
 

 }
 
class employee_edit extends simple_object_edit {
function employee_edit($prefix, $cfg=array()) {
    global $_lib_u_manage_employee_cfg;
    $cfg = array_merge($_lib_u_manage_employee_cfg, $cfg);
    simple_object_edit::simple_object_edit($prefix, $cfg);
 
  }
 
 }

 
   
 
 
 
?>
