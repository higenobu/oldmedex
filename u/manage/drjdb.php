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

 

 

 setlocale(LC_ALL, 'ja_JP.EUC-JP');

// setlocale(LC_ALL, 'ja_JP.Shift_JIS');
// setlocale(LC_ALL, 'ja_JP.UTF-8');


$db = mx_db_connect();

// get contents of a file into a string

$dir="/home/medex/files/";
$filename = $dir."drjtest.csv";
$fh = fopen($filename, "rb");




// setlocale(LC_ALL, 'ja_JP.EUC-JP');

while ($info =fgetcsv($fh,1024,";")){

	$num = count($info);
        
        $row++;
	$tuple =null;
        for ($c=0; $c < $num; $c++) {
        print $info[$c]."<br />\n";
        }


	
 
$sakuseibi=date("Ymd"); 
$ptid=0;
 $ptno=$info[0];
$stmt0 = <<<SQL
SELECT  id from  tbl_patient   where pt_no='$ptno'  
SQL;
//print $stmt0;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $ppp = array();
  foreach($rows as $row)
 {
 
$ptid=$row['id'];
echo "ID".$row['id']."<br>";
 

}
$idw=$ptid;
/*	
$stmt = <<<SQL
insert into drjms ("ID","ObjectID",hizuke, s0,s1,s2,s3,p0,p1,p2,p3,p4,p5,p6) values (nextval('"drjms_ID_seq"'),currval('"drjms_ID_seq"'),'$sakuseibi','$info[0]','$info[1]','$info[2]','$info[3]','$info[4]','$info[5]','$info[6]','$info[7]','$info[8]','$info[9]','$info[10]')
SQL;
//print $stmt;

if (pg_query($db, $stmt)){
//print '<p>drjms  created</p>';
}
else {
print '<p > drjms   error</p>';
die;
}
*/
//if data is null
if ($info[0]=='' & $info[1]=='' & $info[2]==''){}
else {
if ($ptid==0){
 $stmt1 = <<<SQL
INSERT INTO "患者台帳"(
            "ID", "ObjectID", "患者ID", "姓", "名", 
            "フリガナ") values (nextval('"患者台帳_ID_seq"'),currval('"患者台帳_ID_seq"'), '$info[0]','$info[1]','$info[2]','$info[3]')
SQL;
print $stmt1;

if (pg_query($db, $stmt1)){
print '<p>bunrui  created</p>';
}
else {
//print '<p >patient   error</p>';
die;
}
}
//

$stmt0 = <<<SQL
SELECT  id from  tbl_patient   where pt_no='$ptno'  
SQL;
//print $stmt0;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $ppp = array();
  foreach($rows as $row)
 {
 
$ptid=$row['id'];
//echo "ID".$row['id']."<br>";
 

}
$idw=$ptid;
//
$stmt2 = <<<SQL
INSERT INTO "カルテデモ表"(
            "ID", "ObjectID","日付", "患者", "S0","A","O0",
            "S1", "S2", "S3","S4")
    
 values (nextval('"カルテデモ表_ID_seq"'),
currval('"カルテデモ表_ID_seq"'),'$sakuseibi','$idw','$info[5]','$info[4]','$info[6]','$info[7]','$info[8]','$info[9]','$info[10]')
SQL;
print $stmt2;

if (pg_query($db, $stmt2)){
 print '<p>karte  created</p>';
}
else {
 print '<p >karte   error</p>';
die;
}
} //else
//
    }


fclose($fh);

/*
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







//0909-2013 add SampleDate

$stmt = <<<SQL
INSERT INTO test_order(
         "OrderDate", "SampleDate",kekkadate, 
            "Patient", printer )
    select max(date(testdate)),max(date(testdate)),max(testdate),  max(ptid) , '1'  from test_resultwk3 group by (testdate, ptid);
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

*/


print '<p> COMPLETED </p>';




?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
