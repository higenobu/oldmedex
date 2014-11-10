<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/diseasepick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/procedure/proceduremaster2-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/proceduremaster-pick.php';

$u = mx_authenticate_user(1);
if (is_null($u)) {
	exit();
}

function remove_sel($s) {
  return str_replace('sel-', '', $s);
}

$def = array('patient' => array('LOS' => 'ppa_patient_list',
				'DRAW' => 'draw'),
	     'medicine' => array('LOS' => 'drugpick',
				 'DRAW' => 'draw_0',
				 'CFG' => u_pharmacy_rx_order_drugpick_cfg(),
				 'PREFIX_HACK' => 'remove_sel'
				 ),
	     'shots' => array('LOS' => 'drugpick',
			      'DRAW' => 'draw_0',
			      'CFG' => u_pharmacy_shots_order_drugpick_cfg(),
			      'PREFIX_HACK' => 'remove_sel'
			      ),
	     'dismodpick_pre' => array('LOS' => 'dismodpick_pre',
				    'DRAW' => 'draw'),
	     'dismodpick_post' => array('LOS' => 'dismodpick_post',
				     'DRAW' => 'draw'),
	     'disease' => array('LOS' => 'diseasepick',
				'DRAW' => 'draw'),
	     'procedure' => array('LOS' => 'list_of_proceduremaster2',
				  'DRAW' => 'draw_0'),
	     'multi_select_procedure' => array('LOS' => 'proceduremaster_pick',
					       'DRAW' => 'draw_0'),
	     );

$ret = '';
$query = $_REQUEST['query'];
$type = $_REQUEST['type'];
$prefix = $_REQUEST['prefix'];
$prefix2 = $_REQUEST['prefix'];
$query = mb_convert_kana($query, 'AC', 'EUC-JP');

if ($type and $query) {
  $los_name = $def[$type]['LOS'];
  $cfg = $def[$type]['CFG'];
  $draw = $def[$type]['DRAW'];
  if ($def[$type]['PREFIX_HACK']) {
    $func = $def[$type]['PREFIX_HACK'];
    $prefix = $func($prefix);
  }
		  

  if($cfg) {
    $los = new $los_name($prefix, $cfg);
  }
  else
    $los = new $los_name($prefix);

  ob_start();
  $los->$draw(True);
  // $v is in EUC
  $v = ob_get_contents();
  ob_end_clean();
  #print $v;
  $ev = mb_convert_encoding($v, 'HTML-ENTITIES', 'eucJP-win');
  $ev = str_replace("'", "\'", $ev);
  $ev = str_replace("\n", "\\n", $ev);
  //$ev = str_replace("\\", "\\\\", $ev);

  $prefix = $prefix2;
  printf( "{'los': '%s', 'prefix' : '%s'}", $ev, $prefix);
  return;
}
print '{}';
?>
