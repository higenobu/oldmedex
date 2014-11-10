<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';


include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';



function __lib_u_newrx_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'rx',
 'ALLOW_SORT' => 1,
  'DEFAULT_SORT' => 'orderdate',
 COLS => array(




"orderdate",
"shiji",

"patient",
"recorder"



 ),


'ENABLE_QBE' => array(
		      
		        
			 
			array('Column' => 'shiji', 'Label' => 'shijii','Draw' => 'enum',
				  
'Enum' => array('' => '',1 => 'A',2 => 'B',
3 => 'C',),),),

					

LCOLS => array(


 
 
"orderdate",

"shiji",
"patient",
"recorder"



),

DCOLS => array(


 
 
"orderdate",
"shiji",

"patient",
"recorder"

), 

ECOLS => array(


 
 
"orderdate",
"shiji",
"patient",
"recorder"


)

), $cfg);
	return $cfg;
}

class list_of_newrx extends list_of_simple_objects {
	function list_of_newrx($prefix, &$cfg) { 
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_newrx_cfg($cfg);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'orderdate' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}
}

class newrx_display extends simple_object_display {

var $use_printer =1;
	function newrx_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_newrx_cfg($cfg);
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



    $stmt = 'SELECT "ID" from "rx" WHERE "ID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printrx.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}

class newrx_edit extends simple_object_edit {
	function newrx_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_newrx_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

 



	function commit($force=NULL) {
//		$this->data['Patient'] = $this->so_config['Patient_ObjectID'];


		return simple_object_edit::commit($force);
	}
}




?>

