<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ext_edit.php';

if (array_key_exists('PATH_INFO', $_SERVER)) {
  $match = array();
  if (!preg_match('/^.*\/ext_edit\.(.*)$/', $_SERVER['PATH_INFO'], &$match)) {
    print "Invalid request";
    return;
  }
}

$app=$match[1];


$proto = 'http';
$port =$_SERVER['SERVER_PORT'];

$svr_url = $proto . "://" . $_SERVER['SERVER_NAME'];
if($port)
     $svr_url .= ":$port";

switch($app) {
 case 'MedexDraw':
   header("Content-Type: application/x-mx-draw");
   foreach(array('blobid', 'img_url') as $k) {
     if(array_key_exists($k, $_REQUEST) && $_REQUEST[$k] != '')
       printf("%s=%s\n", $k, urlencode($_REQUEST[$k]));
   }
   
   printf("server_url=%s\n", urlencode($svr_url));
   printf("post_url=%s\n", urlencode("/svc/post_blob.php"));
   break;
 case 'MX':
   header("Content-Type: application/x-mx-imaging");
   foreach(array('blobid', 'img_url' , 'pt', 'user_id') as $k) {
     if(array_key_exists($k, $_REQUEST) && $_REQUEST[$k] != '')
       printf("%s=%s\n", $k, urlencode($_REQUEST[$k]));
   }
   
   printf("server_url=%s\n", urlencode($svr_url));
   printf("post_url=%s\n", urlencode("/svc/post_blob.php"));
   break;
 default:
   print "Not implemented: $app";
}
?>
