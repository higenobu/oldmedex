<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharamacy/pdf.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
function __lib_u_everybody_karteview_layout_default(&$cfg)
{
	$cfg['DPAGES'] = $cfg['EPAGES'] =
		array('����ѻ�(����)','����ѻ�(���̽긫)', '�����ѻ�');

	return array(
		'����ѻ�(����)' => array
		(
			array("JUNK", "����", 'group_head'),
			array("S0", "����̾", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array
				    ('����','���̤�','�ɤ�����'))),
			array("S1", "��������", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("S2", "��������", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S3", "��²����", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("S4", "����¾", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			),
		'����ѻ�(���̽긫)' => array
		(
			array("JUNK", "���̽긫", 'group_head'),
			array("O0", "�β���̮���찵", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O1", "������", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O2", "������ʢ����", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O3", "�ͻ衦�γ�", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O4", "ȿ��", "textarea",
			      array('rows' => 1, 'cols' => 60)),
			array("O5", "����¾", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array('O ����','O �ɾ�'))),
			array("O6", "���ٽ긫", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => -1)),
			array("A", "����", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			array("P", "����", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		'�����ѻ�' => array
		(
			array("D", "��������", "schema", array()),

			array("I1", "����", "extdocument",
			      array('img' => 'always'),
			      array('Extdocument' => '����')),
			array("I2", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I1')),
			      array('Extdocument' => '����')),
			array("I3", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I2')),
			      array('Extdocument' => '����')),
			array("I4", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I3')),
			      array('Extdocument' => '����')),
			array("I5", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I4')),
			      array('Extdocument' => '����')),
			array("I6", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I5')),
			      array('Extdocument' => '����')),
			array("I7", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I6')),
			      array('Extdocument' => '����')),
			array("I8", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I7')),
			      array('Extdocument' => '����')),

			array("T", "��ͳ����", "textarea",
			      array('rows' => $tahl, 'cols' => 76,
				    'AbbrevField' => 1)),
			),
		);
}

function __lib_u_everybody_karteview_layout_soap001(&$cfg)
{
	$cfg['DPAGES'] = $cfg['EPAGES'] =
		array('Karte', 'Orderlist','��������');

	return array(
		'Karte' => array
		(/*
			array("JUNK", "(S) ����", 'group_head'),
			array("S0", "����̾", "textarea",
			      array('rows' => 1, 'cols' => 60,
				    'vocab' => array
				    ('����','���̤�','�ɤ�����'))),
*/


			array("JUNK", "Karte", 'group_head'),
			array("O0", "�����ѻ�", "textarea",
			      array('rows' => 50, 'cols' => 60)),
),
/*
			array("JUNK", "(A) ����", 'group_head'),
			array("A", "����", "textarea",
			      array('rows' => $tah, 'cols' => 76,
				    'AbbrevField' => 1)),
*/
	'Orderlist'=> array
(



			array("JUNK", "OrderList", 'group_head'),
			array("P", "Order", "textarea",
			      array('rows' => 50, 'cols' => 76,
				   )),
			),
		'��������' => array
		(
			array("D", "��������", "schema", array()),

			array("I1", "����", "extdocument",
			      array('img' => 'always'),
			      array('Extdocument' => '����')),
			array("I2", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I1')),
			      array('Extdocument' => '����')),
			array("I3", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I2')),
			      array('Extdocument' => '����')),
			array("I4", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I3')),
			      array('Extdocument' => '����')),
			array("I5", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I4')),
			      array('Extdocument' => '����')),
			array("I6", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I5')),
			      array('Extdocument' => '����')),
			array("I7", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I6')),
			      array('Extdocument' => '����')),
			array("I8", "����", "extdocument",
			      array('img' => 'always',
				    'OmitIfEmpty' => array('I7')),
			      array('Extdocument' => '����')),
array("T", "��ͳ����", "textarea",
			      array('rows' => $tahl, 'cols' => 76,
				    'AbbrevField' => 1)),
			
			),
		
		);
}

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
     ('TABLE' => '����ƥǥ�ɽ',
      'ALLOW_SORT' =>
      array('����' => array('����' => 'F."����"'),
	    '��Ͽ��̾' => array('��Ͽ��̾' => '(E."��" || E."̾")'),
	    '����' => array('����' => 'F."����"')),
      'DEFAULT_SORT' => '����',
      'SORT_TIEBREAK' => 'F."����"',
      'LCOLS' => array('����', '��Ͽ��̾',
		       array('Column' => 'S0', 'Label' => "����")),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."��" || E."̾") AS "��Ͽ��̾"
FROM "����ƥǥ�ɽ" AS F
LEFT JOIN "������Ģ" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $layout_fn = '__lib_u_everybody_karteview_layout_' . $_mx_karte_layout;
  $flippage = $layout_fn(&$cfg);

  $cfg['ECOLS'] = array(array('Column' => '����',
			      'Draw' => 'date',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date,nonnull')));
  $cfg['DCOLS'] = array('����');
  $cfg['ICOLS'] = array('����', '����');
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
  $cfg['DCOLS'][] = '��Ͽ��̾';
}

class list_of_everybody_karteviews extends list_of_ppa_objects {

  var $default_row_per_page = 4;

  function list_of_everybody_karteviews($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_karteview_cfg(&$cfg);
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
	    $paging_orders[] = (($col == '����') || ($col == 'ObjectID'));
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
    if (is_null($cfg)) $cfg = array();
    __lib_u_everybody_karteview_cfg(&$cfg);
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



    $stmt = 'SELECT "ID" from "����ƥǥ�ɽ"  WHERE "ObjectID"=' . $oid;
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
    __lib_u_everybody_karteview_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
	$this->data['����'] = mx_today_string();
  }

  function annotate_form_data(&$data) {
	if (trim($data['����']) == '')
		$data['����'] = mx_today_string();
	simple_object_edit::annotate_form_data(&$data);
  }

  function commit($force=NULL) {
    $this->data['����'] = $this->so_config['Patient_ObjectID'];
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
function karte_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	$result = array();
	$num_limit = 0;
	if (!is_null($time_from))
		$limit[] = 'K."����" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'K."����" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;

	$limit[] = 'K."Superseded" IS NULL';

	/* Grab column definitions from the main application */
	$cfg = array();
	__lib_u_everybody_karteview_cfg($cfg);
	_lib_so_prepare_config_ledcols(&$config['DCOLS']);

	/* Interesting columns */
	$primary = 'S0';
	$columns = array('S1', 'S2', 'S3', 'O0', 'O1', 'O2', 'A', 'P');
	$namemap = array();

	$them = array('K."ObjectID"', 'K."����"',
		      'K."' . $primary . '"');
	foreach ($columns as $c) {
		$them[] = 'K."' . $c . '"';

		foreach ($cfg['ECOLS'] as $one) {
			if ($one['Column'] == $c) {
				$namemap[$c] = $one['Label'];
				break;
			}
		}
	}
	$them = implode(", ", $them);

	$sql = "SELECT $them" . '
FROM "����ƥǥ�ɽ" AS K
WHERE K."����" = ' . mx_db_sql_quote($p_oid);
	if (count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}
	if ($num_limit) {
		$sql .= ' LIMIT 30';
	}

	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;
	$me = 'doctor'; # Perhaps needs work.
	$application = "/u/$me/karteview.php";

	foreach ($all as $e) {
//07-10-2014
//		$url = sprintf("$application?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $e['ObjectID']);
 $url = sprintf("$application?SetPatient=1&PatientID=%s", $p_pid);
		$text = sprintf("(�����) %s", $e[$primary]);

		$fuller = '';
		$vb_array = array();
		foreach ($namemap as $c => $t) {
			if (trim($e[$c]) == '')
				continue;
			$fuller .= htmlspecialchars("$t: $e[$c]") . "<br />";
			$vb_array[] = mx_form_escape_key(array($t,$e[$c]));
		}
		$result[] = array('timestamp' => $e['����'],
				  'text' => $text,
				  'fuller' => $fuller,
				  'callback_url' => $url,
				  'thumb' => NULL,
				  'value_blob' => implode('&', $vb_array),
 //				  'object_id' => $e['ObjectID'],
//0710-2014
				  );
	}
	return $result;
}

?>
