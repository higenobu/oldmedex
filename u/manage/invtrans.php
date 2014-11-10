<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
/*

setlocale(LC_ALL, 'ja_JP.UTF-8');

setlocale(LC_ALL, 'ja_JP.EUC-JP');

setlocale(LC_ALL, 'ja_JP.Shift_JIS');
*/


$db = mx_db_connect();

// get contents of a file into a string

$dir="/home/medex/";
$filename = $dir."invtrans.csv";

$fh = fopen($filename, "rb");

/*
$delstmt="delete from ptestwk";
if (pg_query($db, $delstmt)){
print '<p>ptestwk  is deleted </p>';
}
else {
print '<p > DB access error</p>';
die;
}

*/




$row=0;
// setlocale(LC_ALL, 'ja_JP.EUC-JP');

while (($info = fgetcsv($fh, 1024, ":")) !== FALSE){


if ($info[0] !==null) {

        
        $row++;



	echo $row . "<br />\n";

	


 	echo $info[0] . "<br />\n";





if ($info[3]==''||$info[3]=='-'){
$stmt1 = <<<SQL
update invent set "value" = value-$info[1] , "usedate" ='$info[2]'  where "inventcode"='$info[0]' and "Superseded" is null
SQL;

}
else {

$stmt1 = <<<SQL
update invent set "value" = value+$info[1] , "usedate" ='$info[2]'  where "inventcode"='$info[0]' and "Superseded" is null
SQL;

}

echo $stmt1."<br />\n";

  $sh=pg_query($db, $stmt1);  
if ($sh){
print '<p>invent update </p>';
}
else {
print '<p >  error</p>';
die;
}







}  //end if

}
  
//end while



fclose($fh);







?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
