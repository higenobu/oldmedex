<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST </title>
	</head>
	<body>
<a href="index.php?tab=1">return to main</a>


<br>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

 
$db = mx_db_connect();

 


// read otestr table 

/*
 $stmt5 = <<<SQL
SELECT  "name" FROM wk_tensu limit 200

SQL;
*/

$stmt='SELECT  "レセプト電算処理システム医薬品名" as "name", "レセプト電算処理システムコード（１）" as orcacode  FROM "Medis医薬品マスター" limit 1000';
$stmte=mb_convert_encoding($stmt,"EUC-JP","auto");
//print $stmte;

/*
 $stmt5 = <<<SQL
SELECT  "name"
  FROM modalities limit 200

SQL;
*/

 

$rows5 =  mx_db_fetch_all($db, $stmte);
 
print count($rows5);
print "EEE";

  foreach($rows5 as $row5)
 {
	
 
 




	$orca=$row5['orcacode'];
	$stra=$row5['name'];
	echo $stra."<br>";
	echo $orca."<br>";
//$stre=mb_convert_encoding($stra,"EUC-JP","auto");
//echo $stre."<br>"; 
//echo mb_detect_encoding($sta)."<br>";

	$qa=mb_substr($stra,0,1);
	echo  $qa."<br>";
	$qa1=mb_convert_encoding($qa,"UTF-8","auto");
 
 
	mb_regex_encoding('UTF-8');
 
 
 

/*
if (preg_match("/[ア-ヴ]+/u",$qa1)){
 
echo "YES".$qa."<br>";
}
else {
echo "NO".$qa."<br>";
}
*/
//if first character is kanji, then update name from orca-kananame
	if (preg_match("/[一-龠]+/u",$qa1)){ 
	echo "kanji".$qa."<br>";
	$update='update  "Medis医薬品マスター"
   SET 
       "病院使用医薬品名"=  
       
 (select "kananame" from  wk_tensu  where  srycd="レセプト電算処理システムコード（１）" limit 1)
 where "レセプト電算処理システムコード（１）"= ';
	$update1=mb_convert_encoding($update,"EUC-JP","auto");
	$update2=$update1."'".$orca."'";
	echo $update2."<br>";

	$sh=pg_query($db, $update2); 
	}


}


?>

