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






function __lib_u_lcmfm_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'lcmfm',
'ALLOW_SORT' =>1,

 COLS => array(
	
 "dos" ,
 
 
  "patient" ,

   
'a1',
'a2',
'b1',
'a5'

 ),

LCOLS => array(

array('Column' => 'dos',
'Label' => 'ExamDate'),			      
 

array('Column' => 'a2',
'Label' => 'template',

				       'Draw' => 'enum',
				       'Enum' => array('1' => 'a',
						       
						       '2' => 'b',
						       '3' => 'c',
'4' => 'd',
						       
						       '5' => 'e',
						       '6' => 'f',
'7' => 'g',
						       
						       
						       '8' => 'h'
						     ),
				       ),



array('Column' => 'a1',
					'Label' => 'Course',
				   
				       'Draw' => 'enum',
				       'Enum' => array('1' => '1',
						       
						       '2' => '2',
						       '3' => '3'
						     ),),


array('Column' => 'a5',
'Label' => 'Done¡¦NOTyet')),





//*******************************************************************8

DCOLS => array(
array('Column' => 'dos',
'Label' => 'ExamDate'
),			      
 
array('Column' => 'a2',
'Label' => 'template',

				       'Draw' => 'enum',
				       'Enum' => array('1' => 'a',
						       
						       '2' => 'b',
						       '3' => 'c',
'4' => 'd',
						       
						       '5' => 'e',
						       '6' => 'f',
'7' => 'g',
						       
						       
						       '8' => 'h'
						     ),
				       ),

array('Column' => 'a1',
					'Label' => 'Course',
				   
				       'Draw' => 'enum',
				       'Enum' => array('1' => '1',
						       
						       '2' => '2',
						       '3' => '3'
						     ),),


array('Column' => 'a5',
'Label' => 'Done¡¦NOTyet')),

//***************************************************************
ECOLS => array(
array('Column' => 'dos',
'Label' => 'ExamDate'
),			      
 
array('Column' => 'a2',
'Label' => 'template',

				       'Draw' => 'enum',
				       'Enum' => array('1' => 'a',
						       
						       '2' => 'b',
						       '3' => 'c',
'4' => 'd',
						       
						       '5' => 'e',
						       '6' => 'f',
'7' => 'g',
						       
						       
						       '8' => 'h'
						     ),
				       ),

array('Column' => 'a1',
					'Label' => 'Course',
				   
				       'Draw' => 'enum',
				       'Enum' => array('1' => '1',
						       
						       '2' => '2',
						       '3' => '3'
						     ),),


 

 array('Column' => 'a5',
'Label' => 'DONE¡¦NOTyet',

				       'Draw' => 'enum',
				       'Enum' => array('notyet' => 'notyet',
						       
						       'done' => 'done',
						       'proof' => 'proof'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)
), $cfg);
	return $cfg;
}

class list_of_lcmfms extends list_of_ppa_objects {
	function list_of_lcmfms($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_lcmfm_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'dos' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}




}

class lcmfm_display extends simple_object_display {
	
function lcmfm_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_lcmfm_cfg($cfg);
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

class lcmfm_edit extends simple_object_edit {
	function lcmfm_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_lcmfm_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}


function commit($force=NULL) {
  

    $this->data['patient'] = $this->so_config['Patient_ObjectID'];

//    $db = mx_db_connect();
//    $date = $this->data['dos'];
 $patient_objectid = $this->data['patient'];


 simple_object_edit::commit($force); 
  
    



     
  }




 function anew_tweak($orig_id) {
		$this->data['dos'] = mx_today_string();
		
		
	} 


	/* could inherit from simple_object_ppa_edit */
	
/* 0407-2011 change  
function commit($force=NULL) {
		$this->data['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
	

		return simple_object_edit::commit($force);
	}  */

}
?>

