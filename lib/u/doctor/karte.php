<?php // -*- mode: php; coding: euc-japan -*-

//karte.php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';

//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf44.php';
//include def2.php
//0918-2014

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/defkarte1.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/layoutdef.php';




/*

'ALLOW_SORT' =>
      array('日付' => array('日付' => 'F."日付"'),
	    '記録者名' => array('記録者名' => '(E."姓" || E."名")'),
	    '主訴' => array('主訴' => 'F."主訴"')),
      'DEFAULT_SORT' => '日付', 
*/


// $__karte_cfg['DEFAULT_SORT'] => '日付';
//$__karte_cfg['ALLOW_SORT'] = array('日付' => array('日付' => 'F."日付"'),
//	    '記録者名' => array('記録者名' => '(E."姓" || E."名")'),
//	    '主訴' => array('主訴' => 'F."主訴"'));
$__karte_cfg['TABLE'] = 'カルテデモ表';
$__karte_cfg['SEQUENCE'] = "カルテデモ表_ID_seq";
$__karte_cfg['COLS'] = array();
$__karte_cfg['ICOLS'] = array();
$__karte_cfg['ECOLS'] = array();
$__karte_cfg['LCOLS'] = array();
$__karte_cfg['DCOLS'] = array();



 




foreach ($__karte_all_cols as $v) {
	$o = mx_check_option('Option', $v);
	$c = $v['Column'];

	if (!mx_check_option('nostore', $o)) {
		$__karte_cfg['COLS'][] = $c;
		$__karte_cfg['ICOLS'][] = $c;

	}
	if (mx_check_option('list', $o))
		$__karte_cfg['LCOLS'][] = $v;
	if (!mx_check_option('nodisp', $o))
		$__karte_cfg['DCOLS'][] = $v;
	if (!mx_check_option('noedit', $o))
		$__karte_cfg['ECOLS'][] = $v;
}

 
//
function __lib_u_doctor_karte_anno(&$data)
{


}

 
class list_of_kartes extends list_of_ppa_objects {

	var $debug = 1;
	var $patient_column_name_quoted = '患者';

	function list_of_kartes($prefix, $cfg=NULL) {
		global $__karte_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__karte_cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_doctor_karte_anno(&$data);
		return list_of_ppa_objects::annotate_row_data(&$data);
	}
//0716-2014 added sort order

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == '日付' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}


}

class karte_display extends simple_object_display {

	var $debug = 1;

	function karte_display($prefix, $cfg=NULL) {
		global $__karte_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__karte_cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_doctor_karte_anno(&$data);
		return simple_object_display::annotate_row_data(&$data);
	}

//

//


 //for english go_pdfe
function print_sod() {
//    go_pdf($this->id, 0);
  }

function draw_body_3($data, $hdata, $dcols) {
//	  print '<col width="220px"><col width="90%"><tbody>';
 print '<col width="50%"><col width="50%"><tbody>';
	  simple_object_display::draw_body_3($data, $hdata, $dcols);
	  print '</tbody>';
  }



function __splice_out($a, $k) {
	$aa = array();
	foreach ($a as $kk => $vv) {
		if ($k == $kk)
			continue;
		$aa[$kk] = $vv;
	}
	return $aa;

}

 
function lib_u_doctor_karte_exturl_fn($v, $l, $x)
{
	return sprintf("../../blob/%d%s", $v, $x);
}

function __tweak_dcols($dcols) {
	$d = array();
	foreach ($dcols as $v) {
		if (is_array($v) &&
		    array_key_exists('Option', $v)) {
			$o = $v['Option'];
			if (array_key_exists('AbbrevField', $o))
				$v['Option'] = __splice_out($o, 'AbbrevField');
			if ($v['Draw'] == 'extdocument')
				$v['Option']['ext_url'] =
					'lib_u_doctor_karte_exturl_fn';
		}
		$d[] = $v;
	}
	return $d;
}

}


class karte_edit extends simple_object_ppa_edit {

	var $debug = 1;

	var $patient_column_name = '患者';

	function edit_tweak() {
//		$this->data['recorded_on'] = mx_today_string();
		__lib_u_doctor_karte_anno(&$this->data);
$this->data['recorded'] = date("Y-m-d H:i:s");

	}

	function anew_tweak($orig_id) {
		if (trim($this->data['日付']) == '')
			$this->data['日付'] = mx_today_string();
//		$this->data['recorded_on'] = mx_today_string();
 $this->data['recorded'] = date("Y-m-d H:i:s");

	}

	function annotate_form_data(&$data) {
//1030-2013
// 		if ($data['k200'])
// 			__lib_u_doctor_karte_anno(&$data);

		return simple_object_ppa_edit::annotate_form_data(&$data);
	}

	function karte_edit($prefix, $cfg=NULL) {
		global $__karte_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__karte_cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}
function commit($force=NULL) {
    $this->data['患者'] = $this->so_config['Patient_ObjectID'];
    return simple_object_edit::commit($force);
  }
	function _validate($force=NULL) {
		$bad = simple_object_ppa_edit::_validate($force) != 'ok';
		$d =& $this->data;
		
		if ($bad)
			return '';
		return 'ok';
	}

function draw_body() {

//	print '<col width="100px"><col width="50%"><tbody>';

//
	  simple_object_edit::draw_body();
// print '</tbody>';
  }

  function draw_body_3($d, $ecols, $epages, $span) {
//	  print '<col width="220px"><col width="90%"><tbody>';
  print '<col width="50%"><col width="50%"><tbody>';
	  simple_object_edit::draw_body_3($d, $ecols, $epages, $span);
 	  print '</tbody>';
  }
}



?>
