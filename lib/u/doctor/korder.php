<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf50.php';


function _lib_u_k_kiroku() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "À«" || "Ì¾" as empname , userid
    from "¿¦°÷ÂæÄ¢"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}


function _lib_u_kensa() {
  




  $db = mx_db_connect();
  $stmt = <<<SQL
    select  "ID" as cd ,  "Name" as name
    from test_course   
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)

	 $ret[$row['cd']] = $row['name'];
	

  return $ret;
}

function _lib_u_k_shiji() {
  
$id_col = 'id';




  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  "name" as name
    from modalities E where rtype=904
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}


//0617-2011 fro gishi
function _lib_u_k_gishi() {
  
$id_col = 'id';




  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  "name" as name
    from modalities E where rtype= 905 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['name']] = $row['name'];
  return $ret;
}

//**********************************






function __lib_u_korder_cfg(&$cfg)
{
 

$cfg = array_merge(

	array(
	'TABLE' => 'korder1',
	'ALLOW_SORT' =>1,

 'COLS' => array(
	
 	"orderdate" ,
  
  	"´µ¼Ô" ,
	copysign
	      ),

  	

	

'LCOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	 array( 'Column'=>copysign,'Label'=>'Copied'),
	       
	 
	 ),
	



//*******************************************************************8

'DCOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	   array( 'Column'=>copysign,'Label'=>'Copied'),
	       
	 ),
	

//***************************************************************
'ECOLS' => array(

	array('Column' => 'orderdate','Label'=>'DOS'),
	 			      
	  array( 'Column'=>'copysign','Label'=>'Copied',
'Draw'=>'enum',
'Enum'=>array(
'1'=>'','2'=>'Copied'),




),
	       
	 
	 ),

	
), $cfg);
	return $cfg;
}

class list_of_korders extends list_of_ppa_objects {
	function list_of_korders($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_korder_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
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

class korder_display extends simple_object_display {
	
function korder_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_korder_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

function print_sod() {
    go_pdf($this->id, 0);
  }

}

/*

function print_sod($template='srl') {
    $db = mx_db_connect();

    $oid = $this->id;
    $stmt = 'SELECT "ID" from "xctorder" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("print12.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}

*/

class korder_edit extends simple_object_edit {
	function korder_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_korder_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}


function commit($force=NULL) {
  

    $this->data["´µ¼Ô"] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['orderdate'];
 $patient_objectid = $this->data["´µ¼Ô"];
/* 0408-2011*/

 simple_object_edit::commit($force); 
  
    

/* always claim_request */

$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";

//print $stmt;
  
	 pg_query($db, $stmt); 



     
  }




 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	
/* 0407-2011 change  
function commit($force=NULL) {
		$this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
	

		return simple_object_edit::commit($force);
	}  */

}
?>

