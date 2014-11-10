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


function _lib_u_inv_get_invcode() {
 

   

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."inventcode" as id , "inventname" as name
    from "inventm" E 
    where  E."Superseded" IS NULL
    order by E."ID"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => ' ');
  foreach($rows as $row)
    $ret[$row['id']] = $row['name'];
  return $ret;
}
function __lib_u_invent_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'invent',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'transdate',
 COLS => array(


"transdate",
"inout",

 "inventcode",


"value",

"unit",
 
"メモ",
"memo"

 ),




LCOLS => array(
array('Column' => 'transdate','Label' => '更新日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)),

 array('Column' => 'inventcode',
'Label' =>'品目名',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_inv_get_invcode(),
			 'Option' => array('validate' => 'nonnull')),


 array('Column' => 'value',
'Label' =>'数量',

				       'Draw' => 'text',),


 array('Column' => 'unit',
'Label' =>'単位',

				       'Draw' => 'text',),


array('Column' => 'inout',
'Label' => '受・払',

				       'Draw' => 'enum',
				       'Enum' => array('+' => '受',
						       
						       '-' => '払'),

						   
				       'Option' => array('validate' =>
							 'nonnull')),
"メモ",

 array('Column' => 'memo',
'Label' =>'備考',

				       'Draw' => 'text',),



),

DCOLS => array(




array('Column' => 'transdate','Label' => '更新日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)),

 array('Column' => 'inventcode',
'Label' =>'品目名',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_inv_get_invcode(),
			 'Option' => array('validate' => 'nonnull')),


 array('Column' => 'value',
'Label' =>'数量',

				       'Draw' => 'text',),


 array('Column' => 'unit',
'Label' =>'単位',

				       'Draw' => 'text',),


array('Column' => 'inout',
'Label' => '受・払',

				       'Draw' => 'enum',
				       'Enum' => array('+' => '受',
						       
						       '-' => '払'),

						   
				       'Option' => array('validate' =>
							 'nonnull')),
"メモ",

 array('Column' => 'memo',
'Label' =>'備考',

				       'Draw' => 'text',),



), 

ECOLS => array(

array('Column' => 'transdate','Label' => '更新日',
'Draw' => 'date',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)),



 array('Column' => 'inventcode',
'Label' =>'品目名',

				       'Draw' => 'enum',
				       'Enum' => _lib_u_inv_get_invcode(),
			 'Option' => array('validate' => 'nonnull')),

 array('Column' => 'value',
'Label' =>'数量',

				       'Draw' => 'text',),


 array('Column' => 'unit',
'Label' =>'単位',

				       'Draw' => 'text',),


array('Column' => 'inout',
'Label' => '受・払',

				       'Draw' => 'enum',
				       'Enum' => array('+' => '受',
						       
						       '-' => '払'),

						   
				       'Option' => array('validate' =>
							 'nonnull')),
"メモ",

 array('Column' => 'memo',
'Label' =>'備考',

				       'Draw' => 'text',),


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
			if ($col == 'transdate' ||$col == 'ObjectID')
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

