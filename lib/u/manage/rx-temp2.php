<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/doctor/xctorder2.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

function mk_enum_n($a) {
	$r = array(0=>'',1=>'today');
       $i=2;
	while($i <=$a) {
		
			$r[$i] = $i;
			$i++;

		}
	
	return $r;
}

function _lib_u_xct_get_pt_name() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select  distinct( pt_id),  pt_last||pt_first as pt_nm from rx_temp where   orderdate >date'today'-50

SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array();
  foreach($rows as $row)
    $ret[$row['pt_id']] = $row['pt_nm'];
  return $ret;
}



$_lib_u_manage_rx_temp2_cfg = array
(
'COLS' =>  array(
		"rx_id", "pt_num", "pt_last", "pt_first", "pt_kana", "nyugai", "indate", "outdate", 
       "room", "orderdate", "startdate", "selected"
		),

 'TABLE' => 'rx_temp',
'ALLOW_SORT' =>1,

'ENABLE_QBE' => array(
		      
		       array('Column' => 'orderdate','Label'=>'オーダ日' ),
			array('Column' => 'startdate','Label'=>'予定日' ),
			array('Column' => 'pt_num','Label'=>'患者ID' ),
			array('Column' => 'pt_kana','Label'=>'kana' ),
			array('Column' => 'indate','Label'=>'indate' ),
			array('Column' => 'outdate','Label'=>'outdate' ),
			array('Column' => 'room','Label'=>'room' ),
		       array('Column' => 'selected','Label'=>'selected' ),
		       ),
//*****************************************
LCOLS => array(
			array('Column' => 'orderdate','Label'=>'オーダ日' ),
			array('Column' => 'startdate','Label'=>'開始日' ),
			array('Column' => 'pt_num','Label'=>'患者ID' ),
			array('Column' => 'pt_kana','Label'=>'kana' ),
			array('Column' => 'indate','Label'=>'indate' ),
			array('Column' => 'outdate','Label'=>'outdate' ),
			array('Column' => 'room','Label'=>'room' ),
		       array('Column' => 'selected','Label'=>'selected' ),
			array('Column' => 'rx_id','Label'=>'rx_id' ),
),




//******************************************************************* 

DCOLS => array(
			array('Column' => 'orderdate','Label'=>'オーダ日' ),
			array('Column' => 'startdate','Label'=>'開始日' ),
			array('Column' => 'pt_num','Label'=>'患者ID' ),
			array('Column' => 'pt_kana','Label'=>'kana' ),
			array('Column' => 'indate','Label'=>'indate' ),
			array('Column' => 'outdate','Label'=>'outdate' ),
			array('Column' => 'room','Label'=>'room' ),
		       array('Column' => 'selected','Label'=>'selected' ),
			array('Column' => 'rx_id','Label'=>'rx_id' ),
),


//*******************************************

ECOLS => array(

array('Column' => 'orderdate','Label' => '依頼日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'startdate',
'Label' => '開始日',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),


			array('Column' => 'pt_num','Label'=>'患者ID','Draw' => 'static' ),
			array('Column' => 'pt_kana','Label'=>'kana','Draw' => 'static' ),
			array('Column' => 'indate','Label'=>'indate','Draw' => 'static' ),
			array('Column' => 'outdate','Label'=>'outdate' ,'Draw' => 'static'),
			array('Column' => 'room','Label'=>'room','Draw' => 'static' ),
			array('Column' => 'rx_id','Label'=>'rx_id','Draw' => 'static' ),

 array('Column' => 'selected',
'Label' => 'selected',

				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       
						       '1' => '1',
						       '2' => '2'
						     ),
				       )

),


 
'LCHOICE' => mk_enum_n(50),
		   						      
 






);



class list_of_rx_temp2s extends list_of_simple_objects {

var $use_printer =1;


  function list_of_rx_temp2s($prefix, $cfg=NULL) {
    global $_lib_u_manage_rx_temp2_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_rx_temp2_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

 
function base_fetch_stmt_1($i) {
   
    $base = 'select 	"ID", "ObjectID",
"rx_id", "pt_num", "pt_last", "pt_first", "pt_kana", "nyugai", "indate", "outdate", 
       "room", "orderdate", "startdate","selected" from rx_temp  where "Superseded" is null and  ';
  if ($i != '')
    $base .= "  \"orderdate\" >=  date'today' -  $i+1 ";
    return $base;
  }

 

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if (// $col == 'orderdate' ||
			    $col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}


}

class rx_temp2_display extends simple_object_display {
var $use_printer =1;
  function rx_temp2_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_rx_temp2_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_rx_temp2_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }

function print_sod() {
    go_pdf($this->id, 0);
  }


/*
	function print_sod() {
    		$this->sod->print_sod();
  }
*/


}

class rx_temp2_edit extends simple_object_edit {
  function rx_temp2_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_rx_temp2_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_rx_temp2_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }


function commit($force=NULL) {
  

   

    $db = mx_db_connect();
    $date = $this->data['startdate'];

//7015-2011 ?????
 $patient_objectid = $this->data['pt_id'];
 
 simple_object_edit::commit($force); 
  
    

/* always claim_request 

$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";


  
	 pg_query($db, $stmt); 

*/


     
  }




 function anew_tweak($orig_id) {
		$this->data['orderdate'] = mx_today_string();
		
		
	} 
}
?>
