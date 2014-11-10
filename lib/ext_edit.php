<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

//  External Document edit handling.

$apps = array('MedexDraw' => array('Name' => 'Medex Draw',
				   'Link' => '/svc/launcher.php/ext_edit.%s?blobid=%s&img_url=%s'),
	      'MX' => array('Name' => 'MX Image Order',
				   'Link' => '/svc/launcher.php?ext_edit.%s?blobid=%s&img_url=%s'),
	      );



function mx_get_ext_edit_href($app_key, $blobid, $extdocid) {
  global $apps;
  if(array_key_exists($app_key, $apps) &&
     array_key_exists('Link', $apps[$app_key])) {
    $url = '';
    if (mx_db_blobmedia_exists(mx_db_connect(), $blobid))
      $url = "/blobmedia.php?id=$blobid";
    else if ($extdocid) {
      $d = mx_find_ext_document($extdocid);
      if (!is_null($d)) {
	$ext = $d['extension'];
	$url = "/blobmedia.php/$extdocid/j.$ext";
      }
    }
    return sprintf($apps[$app_key]['Link'], $app_key, $blobid, $url);
  }
}
  
function mx_get_ext_edit_name($app_key) {
  global $apps;
  if(array_key_exists($app_key, $apps) &&
     array_key_exists('Name', $apps[$app_key]))
    return $app['Name'];
}
