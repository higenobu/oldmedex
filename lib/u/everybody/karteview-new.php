<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharamacy/pdf.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/defkarte2.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/defkarte.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';



$__karte_cfg['TABLE'] = "カルテデモ表";
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

//print_r($__karte_cfg);
/*
function __lib_u_everybody_karteview_layout_default(&$cfg)
{
	$cfg['DPAGES'] = $cfg['EPAGES'] =
		array('二号用紙(主訴)','二号用紙(一般所見)', '三号用紙');

	return array(
		'二号用紙(主訴)' => array
		(
			array("JUNK", "主訴", 'group_head'),
			array("S0", "主訴名", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array
				    ('いつ','部位が','どうした'))),
			array("S1", "現症病歴", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("S2", "既往病歴", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S3", "家族病歴", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S4", "その他", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			),
		'二号用紙(一般所見)' => array
		(
			array("JUNK", "一般所見", 'group_head'),
			array("O0", "体温・脈・血圧", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O1", "顔貌等", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O2", "胸部・腹部等", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O3", "四肢・知覚", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O4", "反射", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O5", "その他", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array('O 部位','O 症状'))),
			array("O6", "精細所見", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => -1)),
			array("A", "診断", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("P", "方針", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		'三号用紙' => array
		(
			array("D", "シェーマ", "schema", array()),

			array("I1", "画像", "extdocument",
			      array('img' => 'always'),
			      array('Extdocument' => '画像')),
			array("I2", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I1')),
			      array('Extdocument' => '画像')),
			array("I3", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I2')),
			      array('Extdocument' => '画像')),
			array("I4", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I3')),
			      array('Extdocument' => '画像')),
			array("I5", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I4')),
			      array('Extdocument' => '画像')),
			array("I6", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I5')),
			      array('Extdocument' => '画像')),
			array("I7", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I6')),
			      array('Extdocument' => '画像')),
			array("I8", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I7')),
			      array('Extdocument' => '画像')),

			array("T", "自由形式", "textarea",
			      array('rows' => $tahl, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		);
}
*/
/*
function __lib_u_everybody_karteview_layout_soap001(&$cfg)
{

 	$cfg['DPAGES'] = $cfg['EPAGES'] =
 		array('Karte', 'Orderlist','シェーマ');

	return array(
 		'Karte' => array
 		(

			array("JUNK", "Karte", 'group_head'),
			array("O0", "２号用紙", "textarea",
			      array('rows' => 50, 'cols' => 60)),
 ),

 	'Orderlist'=> array
 (



			array("JUNK", "OrderList", 'group_head'),
			array("P", "Order", "textarea",
			      array('rows' => 50, 'cols' => 76,
				   )),
 			),
 		'シェーマ' => array
 		(
			array("D", "シェーマ", "schema", array()),

			array("I1", "画像", "extdocument",
			      array('img' => 'always'),
			      array('Extdocument' => '画像')),
			array("I2", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I1')),
			      array('Extdocument' => '画像')),
			array("I3", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I2')),
			      array('Extdocument' => '画像')),
			array("I4", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I3')),
			      array('Extdocument' => '画像')),
			array("I5", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I4')),
			      array('Extdocument' => '画像')),
			array("I6", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I5')),
			      array('Extdocument' => '画像')),
			array("I7", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I6')),
			      array('Extdocument' => '画像')),
			array("I8", "画像", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I7')),
			      array('Extdocument' => '画像')),
array("T", "自由形式", "textarea",
			      array('rows' => $tahl, 'cols' => 76,
				    'AbbrevField' => 1)),
			
 			),
		
		);
}

*/
/*
function __lib_u_everybody_karteview_cfg(&$cfg) {
  global $_mx_cheap_layout;
  global $_mx_karte_layout;

  if ($_mx_cheap_layout) {
	  $tah = 5;
	  $tahl = 20;
  }
  else {
	  $tah = 10;
	  $tahl = 40;
  }
  if ($_mx_karte_layout == '')
	  $_mx_karte_layout = 'default';

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'カルテデモ表',
      'ALLOW_SORT' =>
      array('日付' => array('日付' => 'F."日付"'),
	    '記録者名' => array('記録者名' => '(E."姓" || E."名")'),
	    '主訴' => array('主訴' => 'F."主訴"')),
      'DEFAULT_SORT' => '日付',
      'SORT_TIEBREAK' => 'F."日付"',
      'LCOLS' => array('日付', '記録者名',
		       array('Column' => 'O0', 'Label' => "Karte"),
			array('Column' => 'P', 'Label' => "Orderlist")),
 
'DCOLS' => array('日付', '記録者名',
		       array('Column' => 'O0', 'Label' => "Karte"),
		array('Column' => 'P', 'Label' => "Orderlist")),
'ECOLS' => array('日付', '記録者名',
		       array('Column' => 'O0', 'Label' => "Karte"),
		array('Column' => 'P', 'Label' => "Orderlist")),
 
     		 'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名"
FROM "カルテデモ表" AS F
LEFT JOIN "職員台帳" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

 $layout_fn = '__lib_u_everybody_karteview_layout_' . $_mx_karte_layout;

  $flippage = $layout_fn(&$cfg);

  $cfg['ECOLS'] = array(
array('Column' => '日付',
			      'Draw' => 'date',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date,nonnull')),
array('Column' => 'O0',
			      'Draw' => 'textarea',
			     ),



);
  $cfg['DCOLS'] = array('日付');
  $cfg['ICOLS'] = array('日付', '患者');
  $page_num = 0;


  foreach ($flippage as $page_name => $page_desc) {
    foreach ($page_desc as $c) {
	    if ($c[0] == 'JUNK') {
		    $a = array('Column' => NULL,
			       'Label' => $c[1],
			       'Draw' => $c[2]);
	    }
	    else {
		    $a = array('Column' => $c[0],
			       'Label' => ($c[1] ? $c[1] : $c[0]),
			       'Draw' => $c[2]);
	    }
	    $a['Page'] = $page_num;
	    if (is_array($c[4])) {
		    foreach ($c[4] as $k => $v) {
			    $a[$k] = $v;
		    }
	    }
	    if (array_key_exists(3, $c)) {
		    $a['Option'] = $c[3];
	    }
	    if ($c[0] != 'JUNK') {
		    $cfg['ECOLS'][] = $a;
	    }
	    $cfg['DCOLS'][] = $a;
	    if ($c[0] != 'JUNK')
		    $cfg['ICOLS'][] = $c[0];
    }
    $page_num++;
  }


  $cfg['DCOLS'][] = '記録者名';
}
*/

class list_of_everybody_karteviews extends list_of_ppa_objects {

  var $default_row_per_page = 4;
var $patient_column_name_quoted = "患者";
  function list_of_everybody_karteviews($prefix, $cfg=NULL) {
global $__karte_cfg;
    if (is_null($cfg)) $cfg = array();
     
		$cfg = array_merge($cfg,$__karte_cfg);
//    $this->tweak_config(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
    $this->paging_keys_namemap['F."ObjectID"'] = 'ObjectID';
  }
  
  function tweak_config(&$cfg) {
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	    $paging_orders[] = (($col == '日付') || ($col == 'ObjectID'));
    }
    return $paging_orders;
  }

}

class list_of_everybody_karteviews_static extends list_of_everybody_karteviews {

	var $default_row_per_page = -1;

	function tweak_config(&$cfg) {
		$c = array();
		foreach ($cfg as $k => $v) {
			if ($k == 'ALLOW_SORT')
				continue;
			$c[$k] = $v;
		}
		$cfg = $c;
	}

}

class everybody_karteview_display extends simple_object_display {

  function everybody_karteview_display($prefix, $cfg=NULL) {
global $__karte_cfg;
    if (is_null($cfg)) $cfg = array();
    $cfg = array_merge($cfg,$__karte_cfg);
    $this->tweak_config(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }
/*
function print_sod() {
    go_pdf($this->id, 0);
  }

}
*/


function print_sod($template='srl') {
    $db = mx_db_connect();

  $oid = $this->id;



    $stmt = 'SELECT "ID" from "カルテデモ表"  WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);


    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printkarte.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }


  function tweak_config(&$cfg) {
  }

  function draw_body_3($data, $hdata, $dcols) {
	  print '<col width="100px"><col width="50%"><tbody>';
	  simple_object_display::draw_body_3($data, $hdata, $dcols);
	  print '</tbody>';
  }

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

function lib_u_everybody_karteview_exturl_fn($v, $l, $x)
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
					'lib_u_everybody_karteview_exturl_fn';
		}
		$d[] = $v;
	}
	return $d;
}

class everybody_karteview_display_static extends everybody_karteview_display {
	function tweak_config(&$cfg) {
		$c = array();
		foreach ($cfg as $k => $v) {
			if ($k == 'DPAGES')
				continue;
			if ($k == 'DCOLS')
				$v = __tweak_dcols($v);
			$c[$k] = $v;
		}
		$cfg = $c;
	}
}

class everybody_karteview_edit extends simple_object_edit {

  function everybody_karteview_edit($prefix, $cfg=NULL) {
global $__karte_cfg;
if (is_null($cfg)) $cfg = array();
    $cfg = array_merge($cfg,$__karte_cfg);
    simple_object_edit::simple_object_edit
      ($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
	$this->data['日付'] = mx_today_string();
  }

  function annotate_form_data(&$data) {
	if (trim($data['日付']) == '')
		$data['日付'] = mx_today_string();
	simple_object_edit::annotate_form_data(&$data);
  }

  function commit($force=NULL) {
    $this->data['患者'] = $this->so_config['Patient_ObjectID'];
    return simple_object_edit::commit($force);
  }

  function draw_body() {
	  simple_object_edit::draw_body();
  }

  function draw_body_3($d, $ecols, $epages, $span) {
	  print '<col width="10px"><col width="30%"><tbody>';
	  simple_object_edit::draw_body_3($d, $ecols, $epages, $span);
	  print '</tbody>';
  }

}



?>
