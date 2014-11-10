<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf4.php';

function _lib_u_xct_get_kiroku() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "姓" || "名" as empname , userid
    from "職員台帳"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}


function _lib_u_xct_get_bui() {
  
$id_col = 'bui_code';



  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id ,  E.bui_id as buiid, E.bui_name as name
    from bui_master4 E 
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
//0707-2011
    if ($row['buiid']>300) {
    $ret[$row['id']] = "CT".$row['name'];}
else {
	 $ret[$row['id']] = $row['name'];
	}

  return $ret;
}
//0615-2011 fro shiji
function _lib_u_xct_get_shiji() {
  
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
function _lib_u_xct_get_gishi() {
  
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

function mk_enum($a) {
	$r = array();
	foreach ($a as $k) {
		if (trim($k) == '') {
			$r[NULL] = '';
		} else {
			$r[$k] = $k;
		}
	}
	return $r;
}




function __lib_u_doctor_xctorder_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'xctorder',
'ALLOW_SORT' =>1,

 COLS => array(
	
 "orderdate" ,
 "plandate",
 "procdate" ,
  "患者" ,
"stop",
  "teikikubun",
  "xctkubun" ,
  "techname",
  "techid" ,
  "bui1" ,
  "bui2" ,
 "bui3" ,
 "bui4" ,
 "bui5",
"memo1",
"memo2" ,
"memo3" ,
"memo4" ,
"memo5" ,
"memo11" ,
"memo21" ,
"memo31" ,
"memo41" ,
"memo51",
"memo12" ,
"memo22" ,
"memo32" ,
"memo42" ,
"memo52",
"syoken1" ,
"syoken2" ,
"syoken3" ,
"syoken4" ,
"syoken5" ,
"techsyoken" ,
"drsyoken" ,
"shiji",
"gishi",

"proof" 
 ),

LCOLS => array(

array('Column' => 'orderdate',
'Label' => 'Orderdate'),			      
array('Column' => 'plandate',
'Label' => 'Plandate'),
array('Column' => 'procdate',
'Label' => 'Executiondate'),
array('Column' => 'stop',
'Label' => 'Stop'),
array('Column' => 'xctkubun',
					'Label' => 'XCT',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
'170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => 'Tech',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => 'Record',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_kiroku()

				       ),

array('Column' => 'bui1',
					'Label' => 'Body1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => 'Direction1',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => 'Body2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => 'Direction2',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => 'Body3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken3',
					'Label' => 'Direction3',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => 'Body4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => 'Direction4',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => 'Body5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => 'Direction5',
				   
				      
				      
				       ),


array('Column' => 'proof',
'Label' => 'Exec・notyet・proofed')),





//*******************************************************************8

DCOLS => array(
array('Column' => 'orderdate',
'Label' => 'Orderdate'
),			      
array('Column' => 'plandate',
'Label' => 'Plandate'),
array('Column' => 'procdate',
'Label' => 'Executiondate'),
array('Column' => 'stop',
'Label' => 'Stop'),
array('Column' => 'xctkubun',
					'Label' => 'XCT',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),

array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'text',
				        

				       ),

array('Column' => 'gishi',
					'Label' => 'Tech',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),
array('Column' => "CreatedBy",
					'Label' => 'Record',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_kiroku()

				       ),

array('Column' => 'memo1',
'Label' => 'Spec'),
				      
array('Column' => 'memo4',
'Label' => 'Purpose'),
array('Column' => 'memo2',
'Label' => 'procedure'),
array('Column' => 'memo3',
'Label' => 'Others'),
/* array('Column' => 'bui1',
'Label' => 'Body１'),*/



array('Column' => 'bui1',
					'Label' => 'Body1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken1',
					'Label' => 'Direction',
				   
				      
				      
				       ),
array('Column' => 'bui2',
					'Label' => 'Body2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken2',
					'Label' => 'Direction',
				   
				      
				      
				       ),
array('Column' => 'bui3',
					'Label' => 'Body3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => 'Direction',
				   
				      
				      
				       ),
array('Column' => 'bui4',
					'Label' => 'Body4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken4',
					'Label' => 'Direction',
				   
				      
				      
				       ),
array('Column' => 'bui5',
					'Label' => 'Body5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => 'Direction',
				   
				      
				      
				       ),
array('Column' => 'techsyoken',
'Label' => 'tech comment'),			      
array('Column' => 'drsyoken',
'Label' => 'Dr report'),
array('Column' => 'proof',
'Label' => 'Exec・notyet・proofed')
),

//***************************************************************
ECOLS => array(
array('Column' => 'orderdate','Label' => 'Orderdate',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),

array('Column' => 'plandate',
'Label' => 'Plandate',
				       'Draw' => 'date',
				       'Option' => array('validate' =>
							 'date')),

array('Column' => 'procdate',
'Label' => 'Executiondate',
				       'Draw' => 'date',
				       'Option' => array('validate' =>

							 'date')),
//0701-2011
array('Column' => 'stop',
'Label' => 'Stop'),

array('Column' => 'xctkubun',
					'Label' => 'XCT',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 '170027910' => 'XP',
'170011810' => 'CT',
'170020110' => 'MRI'

						     )
				       ),


array('Column' => 'shiji',
					'Label' => 'Doctor',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_shiji()

				       ),
array('Column' => 'gishi',
					'Label' => 'Tech',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_gishi()

				       ),

array('Column' => 'memo4',
'Label' => 'purpose',
 'Draw' => 'text'),

 array('Column' => 'memo1',
					'Label' => 'Spec',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),
array('Column' => 'memo2',
					'Label' => 'Procedure',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'イオバミロン300' => 'イオバミロン３００',
'オムニバーグ240' => 'オムニバーグ240',
'ガストログラフィン' => 'ガストログラフィン',
'バリトゲン３００' => 'バリトゲン３００',
'バレックスモルトＳ' => 'バレックスモルトＳ',
'バリエネマ３００' => 'バリエネマ３００',
'ビリスコビンＤＩＣ５０' => 'ビリスコビンＤＩＣ５０',
'その他の造影剤' => 'その他の造影剤',
'輸液点滴セット（ディスポ）' => '輸液点滴セット（ディスポ）',

'翼状針' => '翼状針',

'生食注' => '生食注',
'スポラミン注シリンジ（20ml/1ml）' => 'スポラミン注シリンジ（20ml/1ml）',
'グルカゴン' => 'グルカゴン',
						     )
				       ),

 array('Column' => 'memo3',
					'Label' => 'Others',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLct'),
'cols' => 80)

),



array('Column' => 'bui1',
					'Label' => 'Body1',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken1',
					'Label' => 'Direction',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo12',
					'Label' => 'Number１',
				    'Draw' => 'text'),
array('Column' => 'bui2',
					'Label' => 'Body2',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken2',
					'Label' => 'Direction',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo22',
					'Label' => 'Number2',
				    'Draw' => 'text'),


array('Column' => 'bui3',
					'Label' => 'Body3',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),
array('Column' => 'syoken3',
					'Label' => 'Direction',
				   
				       'Draw' => 'text'
				       

				       ),




array('Column' => 'memo32',
					'Label' => 'Number3',
				    'Draw' => 'text'),
array('Column' => 'bui4',
					'Label' => 'Body4',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),


array('Column' => 'syoken4',
					'Label' => 'Direction',
				   
				       'Draw' => 'text'
				       

				       ),


array('Column' => 'memo42',
					'Label' => 'Number4',
				    'Draw' => 'text'),
array('Column' => 'bui5',
					'Label' => 'Body5',
				   
				       'Draw' => 'enum',
				       'Enum' => _lib_u_xct_get_bui()

				       ),

array('Column' => 'syoken5',
					'Label' => 'Direction',
				   
				       'Draw' => 'text'
				       

				       ),



array('Column' => 'memo52',
					'Label' => 'Number5',
				    'Draw' => 'text'),



array('Column' => 'drsyoken',
'Label' => 'Dr report',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLdoc'),
'cols' => 80)
),

array('Column' => 'techsyoken',
'Label' => 'tech comment',
'Draw' => 'textarea',
'Option' => array('vocab' => array('SOLgishi'),
'cols' => 80)
),


 array('Column' => 'proof',
'Label' => 'Execution・notyet・Proofed',

				       'Draw' => 'enum',
				       'Enum' => array('notyet' => 'notyet',
						       
						       'execution' => 'execution',
						       'proofed' => 'proofed'
						     ),
				       'Option' => array('validate' =>
							 'nonnull'))

)
), $cfg);
	return $cfg;
}

class list_of_xctorders extends list_of_ppa_objects {
	function list_of_xctorders($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder_cfg($cfg);
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

class xctorder_display extends simple_object_display {
	
function xctorder_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder_cfg($cfg);
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




 



class xctorder_edit extends simple_object_edit {
	function xctorder_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_doctor_xctorder_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}


function commit($force=NULL) {
  

    $this->data['患者'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['procdate'];
 $patient_objectid = $this->data['患者'];
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
		$this->data['患者'] = $this->so_config['Patient_ObjectID'];
	

		return simple_object_edit::commit($force);
	}  */

}
?>

