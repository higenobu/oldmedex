<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharamacy/pdf.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
function __lib_u_everybody_karte2_layout_default(&$cfg)
{
	$cfg['DPAGES'] = $cfg['EPAGES'] =
		array('Æó¹æÍÑ»æ(¼çÁÊ)','Æó¹æÍÑ»æ(°ìÈÌ½ê¸«)', '»°¹æÍÑ»æ');

	return array(
		'Æó¹æÍÑ»æ(¼çÁÊ)' => array
		(
			array("JUNK", "¼çÁÊ", 'group_head'),
			array("S0", "¼çÁÊÌ¾", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array
				    ('¤¤¤Ä','Éô°Ì¤¬','¤É¤¦¤·¤¿'))),
			array("S1", "¸½¾ÉÉÂÎò", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("S2", "´û±ýÉÂÎò", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S3", "²ÈÂ²ÉÂÎò", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S4", "¤½¤ÎÂ¾", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			),
		'Æó¹æÍÑ»æ(°ìÈÌ½ê¸«)' => array
		(
			array("JUNK", "°ìÈÌ½ê¸«", 'group_head'),
			array("O0", "ÂÎ²¹¡¦Ì®¡¦·ì°µ", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O1", "´éËÆÅù", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O2", "¶»Éô¡¦Ê¢ÉôÅù", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O3", "»Í»è¡¦ÃÎ³Ð", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O4", "È¿¼Í", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O5", "¤½¤ÎÂ¾", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array('O Éô°Ì','O ¾É¾õ'))),
			array("O6", "ÀººÙ½ê¸«", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => -1)),
			array("A", "¿ÇÃÇ", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("P", "Êý¿Ë", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		'»°¹æÍÑ»æ' => array
		(
			array("D", "¥·¥§¡¼¥Þ", "schema", array()),

			array("I1", "²èÁü", "extdocument",
			      array('img' => 'always'),
			      array('Extdocument' => '²èÁü')),
			array("I2", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I1')),
			      array('Extdocument' => '²èÁü')),
			array("I3", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I2')),
			      array('Extdocument' => '²èÁü')),
			array("I4", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I3')),
			      array('Extdocument' => '²èÁü')),
			array("I5", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I4')),
			      array('Extdocument' => '²èÁü')),
			array("I6", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I5')),
			      array('Extdocument' => '²èÁü')),
			array("I7", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I6')),
			      array('Extdocument' => '²èÁü')),
			array("I8", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I7')),
			      array('Extdocument' => '²èÁü')),

			array("T", "¼«Í³·Á¼°", "textarea",
			      array('rows' => $tahl, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		);
}

function __lib_u_everybody_karte2_layout_soap001(&$cfg)
{
	$cfg['DPAGES'] = $cfg['EPAGES'] =
		array('SOAP', '¥·¥§¡¼¥Þ', 'ÉÂÎò');

	return array(
		'SOAP' => array
		(
			array("JUNK", "(S) ¼çÁÊ", 'group_head'),
			array("S0", "¼çÁÊÌ¾", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array
				    ('¤¤¤Ä','Éô°Ì¤¬','¤É¤¦¤·¤¿'))),

			array("JUNK", "(O) °ìÈÌ½ê¸«", 'group_head'),
			array("O0", "½ê¸«", "textarea",
			      array('rows' => 12, 'cols' => 60)),

			array("JUNK", "(A) ¿ÇÃÇ", 'group_head'),
			array("A", "¿ÇÃÇ", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),

			array("JUNK", "(P) Êý¿Ë", 'group_head'),
			array("P", "Êý¿Ë", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		'¥·¥§¡¼¥Þ' => array
		(
			array("D", "¥·¥§¡¼¥Þ", "schema", array()),

			array("I1", "²èÁü", "extdocument",
			      array('img' => 'always'),
			      array('Extdocument' => '²èÁü')),
			array("I2", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I1')),
			      array('Extdocument' => '²èÁü')),
			array("I3", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I2')),
			      array('Extdocument' => '²èÁü')),
			array("I4", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I3')),
			      array('Extdocument' => '²èÁü')),
			array("I5", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I4')),
			      array('Extdocument' => '²èÁü')),
			array("I6", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I5')),
			      array('Extdocument' => '²èÁü')),
			array("I7", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I6')),
			      array('Extdocument' => '²èÁü')),
			array("I8", "²èÁü", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I7')),
			      array('Extdocument' => '²èÁü')),

			array("T", "¼«Í³·Á¼°", "textarea",
			      array('rows' => $tahl, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		'ÉÂÎò' => array
		(
			array("S1", "¸½¾ÉÉÂÎò", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("S2", "´û±ýÉÂÎò", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S3", "²ÈÂ²ÉÂÎò", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S4", "¤½¤ÎÂ¾", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			),
		);
}

function __lib_u_everybody_karte2_cfg(&$cfg) {
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
$_mx_karte_layout = 'soap001';
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'karte',
     
      'DEFAULT_SORT' => 'order_date',
     
      'LCOLS' => array('order_date',
		       array('Column' => 'S0', 'Label' => "¼çÁÊ")),

      
      ));

  $stmt_head = '
SELECT F.*
FROM "karte" AS F


';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $layout_fn = '__lib_u_everybody_karte2_layout_' . 'soap001';
  $flippage = $layout_fn(&$cfg);

  $cfg['ECOLS'] = array(array('Column' => 'order_date',
			      'Draw' => 'date',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date,nonnull')));
  $cfg['DCOLS'] = array('order_date');
  $cfg['ICOLS'] = array('order_date', 'patient');
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
  
}

class list_of_everybody_karte2s extends list_of_ppa_objects {

  var $default_row_per_page = 4;

  function list_of_everybody_karte2s($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_karte2_cfg(&$cfg);
    $this->tweak_config(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
    $this->paging_keys_namemap['F."ObjectID"'] = 'ObjectID';
  }
  
  function tweak_config(&$cfg) {
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	    $paging_orders[] = (($col == 'order_date') || ($col == 'ObjectID'));
    }
    return $paging_orders;
  }

}

class list_of_everybody_karte2s_static extends list_of_everybody_karte2s {

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

class everybody_karte2_display extends simple_object_display {

  function everybody_karte2_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_karte2_cfg(&$cfg);
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



    $stmt = 'SELECT "ID" from "karte"  WHERE "ObjectID"=' . $oid;
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
	  print '<col width="220px"><col width="90%"><tbody>';
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

function lib_u_everybody_karte2_exturl_fn($v, $l, $x)
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
					'lib_u_everybody_karte2_exturl_fn';
		}
		$d[] = $v;
	}
	return $d;
}

class everybody_karte2_display_static extends everybody_karte2_display {
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

class everybody_karte2_edit extends simple_object_edit {

  function everybody_karte2_edit($prefix, $cfg=NULL) {
    __lib_u_everybody_karte2_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
	$this->data['order_date'] = mx_today_string();
  }

  function annotate_form_data(&$data) {
	if (trim($data['order_date']) == '')
		$data['order_date'] = mx_today_string();
	simple_object_edit::annotate_form_data(&$data);
  }

  function commit($force=NULL) {
    $this->data['patient'] = $this->so_config['Patient_ObjectID'];
    return simple_object_edit::commit($force);
  }

  function draw_body() {
	  simple_object_edit::draw_body();
  }

  function draw_body_3($d, $ecols, $epages, $span) {
	  print '<col width="220px"><col width="90%"><tbody>';
	  simple_object_edit::draw_body_3($d, $ecols, $epages, $span);
	  print '</tbody>';
  }

}


/*
 * This is used by index-pt via lib/ord_module.php.
 */

?>
