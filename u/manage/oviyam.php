<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>karte</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>

 



<br>




<?php
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';


$id=$_POST['ptid'];

$con1 = mx_db_connect();


$stmt = <<<SQL
    

select pt_id, pt_no, pt_nm tbl_patient
   
 WHERE "pt_no"='$id'
SQL;

//print $stmt."<br>";
$rs=mx_db_fetch_single($con1, $stmt);
 
if (!$rs) {
	
	 
//	print "patientID does not exist in DB<br>";
	
	
}

 

//print  "patientID=".$id."<br>";
//print "patientname=".$rs['pt_nm']."<br>";


print "<a href= \"http://mmhome.from-mn.com:8080/oviyam/oviyam?patientID=".$id."\" >DICOM Viewer</a>";
 
 







?>

