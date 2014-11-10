<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/department.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-cat.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee-rank.php';

function type_enum_values() {
	   
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "¿¦¼ï"
FROM "¿¦¼ï°ìÍ÷É½"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c['¿¦¼ï'];
	  }
	  return $ret;
  }


function rank_enum_values() {
	   
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "¿¦°Ì"
FROM "¿¦°Ì°ìÍ÷É½"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c["¿¦°Ì"];
	  }
	  return $ret;
  }

function dept_enum_values() {
	   
	  $db = mx_db_connect();
	  $stmt = <<<SQL
SELECT "ObjectID", "ÂçÊ¬Îà"
FROM "Éô½ð°ìÍ÷É½"
WHERE "Superseded" IS NULL
SQL;
	  $ret = array();
	  foreach (mx_db_fetch_all($db, $stmt) as $c) {
		  $ret[$c['ObjectID']] = $c["ÂçÊ¬Îà"];
	  }
	  return $ret;
  }

 
 
$_lib_u_manage_employee_cfg = array
(
 'TABLE' => '¿¦°÷ÂæÄ¢',
 
 
 
 'COLS' => array( userid,"¿¦°÷ID", 
       "¿¦¼ï", "¿¦°Ì", "Éô½ð", "À«", "Ì¾",  "À­ÊÌ", "À¸Ç¯·îÆü", "²ÃÆþÅÅÏÃ"
	 
 ),

 'LCOLS' => array(

array('Column' =>  "¿¦°÷ID",'Label'=>'ID'),
array('Column' =>  "À«",'Label'=>'LastName'),

array('Column' =>  "Ì¾",'Label'=>'FirstName'),
array('Column' => '¿¦¼ï',
					'Label' => 'emptype',
				   
				       'Draw' => 'enum',
				       'Enum' =>type_enum_values()),
array('Column' => '¿¦°Ì',
					'Label' => 'rank',
				   
				       'Draw' => 'enum',
				       'Enum' =>rank_enum_values()),

array('Column' => 'Éô½ð',
					'Label' => 'dept',
				   
				       'Draw' => 'enum',
				       'Enum' =>dept_enum_values()),

 

),
			 
 'DCOLS' => array(
array('Column' =>  "¿¦°÷ID",'Label'=>'ID'),
array('Column' =>  "À«",'Label'=>'LastName'),

array('Column' =>  "Ì¾",'Label'=>'FirstName'),
		 
 
array('Column' =>  "À¸Ç¯·îÆü",'Label'=>'DOB'),
array('Column' =>  "²ÃÆþÅÅÏÃ",'Label'=>'Tel'),
 
 array('Column' => '¿¦¼ï',
					'Label' => 'emptype',
				   
				       'Draw' => 'enum',
				       'Enum' =>type_enum_values()),
array('Column' => '¿¦°Ì',
					'Label' => 'rank',
				   
				       'Draw' => 'enum',
				       'Enum' =>rank_enum_values()),
 
array('Column' => 'Éô½ð',
					'Label' => 'dept',
				   
				       'Draw' => 'enum',
				       'Enum' =>dept_enum_values()),

 
array('Column' =>  "userid",'Label'=>'internal uid'),
),

 'ECOLS' => array(
 
array('Column' =>  "¿¦°÷ID",'Label'=>'ID'),
array('Column' =>  "À«",'Label'=>'LastName'),

array('Column' =>  "Ì¾",'Label'=>'FirstName'),
		 
 
array('Column' =>  "À¸Ç¯·îÆü",'Label'=>'DOB'),
array('Column' =>  "²ÃÆþÅÅÏÃ",'Label'=>'Tel'),
array('Column' => '¿¦¼ï',
					'Label' => 'emptype',
				   
				       'Draw' => 'enum',
				       'Enum' =>type_enum_values()),
array('Column' => '¿¦°Ì',
					'Label' => 'rank',
				   
				       'Draw' => 'enum',
				       'Enum' =>rank_enum_values()),
array('Column' => 'Éô½ð',
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
