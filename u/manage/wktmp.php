<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>from CMB server into our server</title>
	</head>
	<body>
<a href="index.php?tab=1">goback to main</a>


<br>


<?php

/*
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

*/


//$db = mx_db_connect();




function fwrite_stream($fp, $string) {
    for ($written = 0; $written < strlen($string); $written += $fwrite) {
        $fwrite = fwrite($fp, substr($string, $written));
        if ($fwrite === false) {
            return $written;
        }
    }
    return $written;
}

 $stream=fopen("/s/medex/farm/php/u/manage/aaa","rb");
$ff=fopen("labellog","wb");


 

 

 
		




 
$data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
$topdata=substr($data,1,200)."\n";

 print $topdata;

  
 
 
 

$fff=fwrite_stream($ff,$topdata);
fclose($ff);
 
 
 

 



?>
