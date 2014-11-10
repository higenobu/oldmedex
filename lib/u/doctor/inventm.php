<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';


include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';



function __lib_u_inventm_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'drjms',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 's0',
 COLS => array(
 hizuke,headid,
       s0, s1, s2, s3, s4, p0, p1, p2, p3, p4, p5, p6, a1, a2, a3, a4, 
       a5, a6

 ),

 'ENABLE_QBE' => array(s0, s1, s2, s3, s4, p0, p1, p2, p3, p4, p5, p6, a1, a2, a3, a4, 
       a5, a6),



LCOLS => array(

array('Column' => 's0',
'Label' => '分類番号'),
array('Column' => 's1',
'Label' => '大分類'),
array('Column' => 's2',
'Label' => '中分類'),
array('Column' => 's3',
'Label' => '小分類'),
array('Column' => 'p0',
'Label' => '項番'),
array('Column' => 'p1',
'Label' => '観点'),
array('Column' => 'p2',
'Label' => '試験内容'),
array('Column' => 'p3',
'Label' => '試験条件'),
array('Column' => 'p4',
'Label' => '確認条件'),
array('Column' => 'p5',
'Label' => '期待値'),
array('Column' => 'p6',
'Label' => '確認対象物'),  
 ), 
 
 
DCOLS => array(
array('Column' => 's0',
'Label' => '分類番号'),
array('Column' => 's1',
'Label' => '大分類'),
array('Column' => 's2',
'Label' => '中分類'), 
array('Column' => 's3',
'Label' => '小分類'),
array('Column' => 'p0',
'Label' => '項番'),
array('Column' => 'p1',
'Label' => '観点'),
array('Column' => 'p2',
'Label' => '試験内容'),
array('Column' => 'p3',
'Label' => '試験条件'),
 array('Column' => 'p4',
'Label' => '確認条件'),
array('Column' => 'p5',
'Label' => '期待値'),
array('Column' => 'p6',
'Label' => '確認対象物'), 
), 

ECOLS => array(
array('Column' => 's0',
'Label' => '分類番号'),
array('Column' => 's1',
'Label' => '大分類'),
array('Column' => 's2',
'Label' => '中分類'), 
array('Column' => 's3',
'Label' => '小分類'),
array('Column' => 'p0',
'Label' => '項番'),
array('Column' => 'p1',
'Label' => '観点'),
array('Column' => 'p2',
'Label' => '試験内容'),
array('Column' => 'p3',
'Label' => '試験条件'),
array('Column' => 'p4',
'Label' => '確認条件'),
array('Column' => 'p5',
'Label' => '期待値'),
array('Column' => 'p6',
'Label' => '確認対象物'), 
)

), $cfg);
	return $cfg;
}

class list_of_inventm extends list_of_simple_objects {
	function list_of_inventm($prefix, &$cfg) { 
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_inventm_cfg($cfg);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 's0' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}
}

class inventm_display extends simple_object_display {

var $use_printer =1;
	function inventm_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_inventm_cfg($cfg);
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



    $stmt = 'SELECT "ID" from "drjtest" WHERE "ID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printinventm.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

}

class inventm_edit extends simple_object_edit {
	function inventm_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_inventm_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

 



	function commit($force=NULL) {
//		$this->data['Patient'] = $this->so_config['Patient_ObjectID'];


		return simple_object_edit::commit($force);
	}
}




?>

