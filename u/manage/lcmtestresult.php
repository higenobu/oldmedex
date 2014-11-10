<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


<br>


<?php
//0121-2014
//using test_master0 fro CMB

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

/*

setlocale(LC_ALL, 'ja_JP.UTF-8');

setlocale(LC_ALL, 'ja_JP.EUC-JP');

setlocale(LC_ALL, 'ja_JP.Shift_JIS');
*/


$db = mx_db_connect();
$ptobj=$_POST['ptid'];

$query='select id,pt_nm from 
 tbl_patient2 where  pt_no='."'".$ptobj."'";
 print $query;
 
$row  =  mx_db_fetch_single($db,$query);

 

	echo $row['id']."<br>"; 
  
	 $ptid=$row['id'];
$ptnm=$row['pt_nm'];
   
 
 $stmt = <<<SQL
update   test_resultwkcmb  set ptid=$ptid
 where  trim(ptname)='$ptnm' 

SQL;

print $stmt;
if (pg_query($db, $stmt)){
print '<p>WK3 Data updated</p>';
}
else {
print '<p > WK3 update error</p>';
die;
}

 

 






//0909-2013 add SampleDate

$stmt = <<<SQL
INSERT INTO test_order(
         "OrderDate", "SampleDate",kekkadate, 
            "Patient", printer )
    select  date(substring(testdate,1,8)) , date(substring(testdate,1,8)) , substring(testdate,1,8) , ptid , '1'  from test_resultwkcmb where ptid =$ptid    ;
SQL;
print $stmt;
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

 select  m."ID",  r.kekka , r.ijo, r.ijo , r.ptid,  substring(r.testdate,1,8)   from test_master0 m, test_resultwkcmb r 
where    r.komokucode = m."LaboSystemCode" and ptid=$ptid
SQL;
print $stmt;
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

r.ptid=t."Patient" and r.kekkadate=t.kekkadate limit 1) where ptid=$ptid;
SQL;
print $stmt;
if (pg_query($db, $stmt)){
print '<p>TEST ID was added to test result</p>';
}
else {
print '<p >TEST ID update error , duplicate ID?</p>';
die;
}


print '<p> COMPLETED </p>';




?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
