<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

$blobid =$_REQUEST['blobid'];
$key = 'upfile'; // hardcoded in a ext edit app; ex. MedexDraw

if(!array_key_exists($key, $_FILES))
     print "ERROR: No uploads";
else{
  
  $fname = $_FILES[$key]['tmp_name'];
  $length = $_FILES[$key]['size'];
  $type = $_FILES[$key]['type'];
  $type = 'image/jpeg'; // MedexDraw sends binary in application/octet-stream

  if($_FILES[$key]['error'])
    print "ERROR: PHP spits error";
  else {
    $fp = fopen($fname, 'rb');
    
    $data = fread($fp, $length);
    fclose($fp);
    
    $db = mx_db_connect();
    $ret = NULL;
    if($ret = mx_db_insert_blobmedia($db, $type, $data, $blobid))
      ;
    else
      $ret = mx_db_update_blobmedia($db, $blobid, $type, $data);
    if(!$ret)
      print "ERROR: insert and/or update failed.  $blobid";
    else
      print "blobid=$blobid";
  }
}
?>
