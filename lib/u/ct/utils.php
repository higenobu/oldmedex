<?php

function count_elements($a, $nrows) {
  $c = 0;
  if(is_array($a)) {
    foreach($a as $k => $v)
      if(is_array($v))
	$c += count_elements($v, $nrows);
      else
	$c += $nrows; // 2 rows edit field
  }else{
    $c += $nrows;
  }
  return $c;
}

function get_subset($key, $a) {
  $o = array();
  foreach ($a as $k => $v) {
    if(strstr($v['Column'], $key)) {
      $o[$k] = $v;
    }
  }
  return $o;
}

/*

function copy_ecols2(&$cfg, $col) {
  global $_lib_u_ct_record_naibun_ecols_template;
  foreach($_lib_u_ct_record_naibun_ecols_template as $k => $v) {
    $v['Column'] = $col . '|' . $v['Column'];
    $cfg['ECOLS'][] = $v;
  }
}
*/
function copy_ecols2(&$cfg, $col, $ecols_template) {
  foreach($ecols_template as $k => $v) {
    $v['Column'] = $col . '|' . $v['Column'];
    $cfg['ECOLS'][] = $v;
  }
}


function _lib_u_ct_annotate_cfg(&$cfg, $ecols, $ecols_template) {
  $cfg['ECOLS'] = array();
  //大項目（必ずarray）
  foreach($ecols as $dk => $dv)
    if(is_array($dv))
      foreach($dv as $ck => $cv)
	if(is_array($cv))
	  foreach($cv as $sk => $sv)
	    copy_ecols2(&$cfg, $dk . '|' . $ck . '|' . $sv, $ecols_template);
	else
	  copy_ecols2(&$cfg, $dk . '||' .$cv, $ecols_template);
    else
      copy_ecols2(&$cfg, $dv, $ecols_template);
      
}

?>