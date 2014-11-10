<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf11.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';


function __lib_u_invent_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'invent',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'inventid',
 COLS => array(

 "inventid" ,

 "indate",
 "usedate"
 "inventcode",
"inventname",

"value",

"unit",
 

"memo"

 ),




LCOLS => array(


 "inventid" ,

 "indate",
"usedate",
 "inventcode",
"inventname",

"value",

"unit",
 

"memo"

),

DCOLS => array(

  "inventid" ,

 "indate",
"usedate",
 "inventcode",
"inventname",

"value",

"unit",
 

"memo"

), 

ECOLS => array(

  "inventid" ,

 "indate",
"usedate",
 "inventcode",
"inventname",

"value",

"unit",
 

"memo"

)

), $cfg);
	return $cfg;
}

class list_of_invent extends list_of_simple_objects {
	function list_of_invent($prefix, &$cfg) { 
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_invent_cfg($cfg);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'factdate' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}
}

class invent_display extends simple_object_display {

var $use_printer =1;
	function invent_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_invent_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
/*
function print_sod() {
    go_pdf($this->id, 0);
  }
*/


function print_sod($template='srl') {
    $db = mx_db_connect();

  $oid = $this->id;



    $stmt = 'SELECT "ID" from "invent" WHERE "ID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printinvent.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}

class invent_edit extends simple_object_edit {
	function invent_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_invent_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

 



	function commit($force=NULL) {
//		$this->data['Patient'] = $this->so_config['Patient_ObjectID'];


		return simple_object_edit::commit($force);
	}
}




?>

