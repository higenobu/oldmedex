<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>


<br>


<?php

/*

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
*/



setlocale(LC_ALL, 'ja_JP.UTF-8');
/*
setlocale(LC_ALL, 'ja_JP.EUC-JP');

setlocale(LC_ALL, 'ja_JP.Shift_JIS');
*/

$db =  pg_connect("host=localhost dbname=medexdb5 user=medex ");
if (!$db) {
	echo("<p><span style=\"color:red\">medexdb5 cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}




$stmt = <<<SQL
update   test_resultwk3  set ptid=(select id from 
 tbl_patient where  trim(karteno)=trim(pt_no))

SQL;

if (pg_query($db, $stmt)){
print '<p>WK3 Data updated</p>';
}
else {
print '<p > WK3 update error</p>';
die;
}









$stmt = <<<SQL
INSERT INTO test_order(
         "OrderDate", kekkadate, 
            "Patient", printer )
    select max(date(testdate)), max(testdate),  max(ptid) , '1'  from test_resultwk3 group by (testdate, ptid);
SQL;

if (pg_query($db, $stmt)){
print '<p>TEST order created</p>';
}
else {
print '<p > TEST order   error</p>';
die;
}








	$stmt = <<<SQL
insert into test_result(
"TestMaster",
"value" ,
decision ,
state, 
ptid,
kekkadate )

select  m."ID",  r.kekka , r.ijo, r.ijo , r.ptid,  r.testdate from test_master m, test_resultwk3 r 
where trim (leading '0'from r.komokucode)=trim(trailing ' ' from m."LaboSystemCode")
SQL;

if (pg_query($db, $stmt)){
print '<p>Added to TEST RESULT </p>';
}
else {
print '<p>TEST RESULT  insertion  error</p>';
die;
}
	

//0606-2011 testorder is null

$stmt = <<<SQL
update test_result r set "TestOrder"=(select t."ID" from test_order t where

r.ptid=t."Patient" and r.kekkadate=t.kekkadate limit 1) where "TestOrder" is null;
SQL;

if (pg_query($db, $stmt)){
print '<p>TEST ID was added to test result</p>';
}
else {
print '<p >TEST ID update error , duplicate ID?</p>';
die;
}


print '<p> COMPLETED </p>';




?>



