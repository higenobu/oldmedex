<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>PostgreSQL Table Dump</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


<br>






<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
echo "**************\n";

print $_FILES['upfile']['name'];


print $_FILES['upfile']['tmp_name'];


if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
   echo "File ". $_FILES['upfile']['name'] ." uploaded successfully.\n";
   echo "Displaying contents\n";

 // $fileData = file_get_contents($_FILES['upfile']['tmp_name']);


   readfile($_FILES['upfile']['tmp_name']);




} else {
   echo "Possible file upload attack: ";
   echo "filename '". $_FILES['upfile']['tmp_name'] . "'.";
}



$target_path = "/home/medex/files/";

$target_path = $target_path .  $_FILES['upfile']['name']; 
print $target_path;


if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) {
    echo "The file ".  $_FILES['upfile']['name']. 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}
?>

