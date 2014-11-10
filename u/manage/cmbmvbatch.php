<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>


<br>


<?php

//0722-2014 added dos

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
 
 



$db = mx_db_connect();
//read 'from DOS' to now
 $ordatel=$_POST[dos];
// $PTnm=$_POST[PTnm];
  $stmt3 = <<<SQL
SELECT * from cmbmap   order by osato
SQL;

	$rows3 =  mx_db_fetch_all($db, $stmt3);
 $ii=0; 
$abmap=array();	 
  foreach($rows3 as $row3)
 {

$cmb=$row3['cmb'];
$osato=$row3['osato'];
$ab=$row3['ab'];
$map[$cmb]=$osato;
$abmap[$cmb]=$ab;


}

 
//do not care sw=0 or 1
//debug assume ptname is unique
//pick up latest date as testdate 0722-2014
//************************************************
 $stmt5 = <<<SQL
SELECT id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient2 on ptname=pt_nm   where
	substring(testdate,1,8)>= '$ordatel' 
       order by (id,testdate) desc
SQL;

	$rows5 =  mx_db_fetch_all($db, $stmt5);
  	$ptids = array();
	$ordates = array();
	$i=0;

  foreach($rows5 as $row5)
 {

  
if($ptid ==$row5['id']){
//skip
  	 }
	else {
	$ptid=$row5['id'];
	
	 $ordate=substr($row5['testdate'],0,8);
	$ptids[$i]=$row5['id'];
	 $ordates[$i]=substr($row5['testdate'],0,8);
	 $i++;

	}
}  //foreach end
//*********************************************

//

//print "ptids=".count($ptids)."\n";
//print "ordates=".count($ordates)."\n";

for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

	$ptid=$ptids[$k];
	$ordate=$ordates[$k];
//for each ptid read all record from ordate
 
 $stmt0 = <<<SQL
SELECT id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient2 on ptname=pt_nm     where id = '$ptid' and substring(testdate,1,8)>= '$ordate'    order by (id,komokucode)        
SQL;

 //print $stmt0."<br>\n";
//komma must be properly added
$komma="";
 $rows =  mx_db_fetch_all($db, $stmt0);
print $rows[0]['ptname']."=".$rows[0]['karteno']."<br>\n";
  $itemv = array();
  $itemijo=array();
$insert="";
$insertv="";
$updatestr="";
//0722-2014
$var="";
  foreach($rows as $row)
 {
	$ptid=$row['id']; 


if ($var!=$map[$row['komokucode']]){
	 $var=$map[$row['komokucode']];
 
	$val=$row['kekka'];


	if ($row['ijo']=='H'|$row['ijo']=='L') {
			$row['ijo']='1';
		}
		else { 
			$row['ijo']='0';
			}
	$itemijo[$row['komokucode']]=$row['ijo'];
	$varab=$abmap[$row['komokucode']];
	$valab= $row['ijo'];
	if ($var !="" && $val !="") {
	$updatestr=$updatestr.$komma." $var="."'$val' ";
//07-22-2014
	$insert=$insert.$komma.$var; 
	$insertv=$insertv.$komma."'$val'";
//add komma after first string

	$komma=",";}
	if ($varab !="") {
	$updatestr=$updatestr.$komma." $varab="."'$valab' ";


}



if ($varab !="" && $valab !="") {$insert=$insert.$komma.$varab; 
	$insertv=$insertv.$komma."'$valab'";
	}
//??????????????? this komma? 0820-2014
//	$komma=",";
}//ifend




}//for end

 

//exist record already
//0820-2014 if ss11='0'
$stmt5 = <<<SQL
SELECT * FROM otatest_order where  patient=$ptid   and order_date >=CAST('$ordate' AS DATE) + CAST('-5 days' AS INTERVAL) and order_date<=CAST('$ordate' AS DATE) + CAST('+5 days' AS INTERVAL) and "Superseded" is null  order by order_date desc
SQL;

print $stmt5."\n";

 $rs =  mx_db_fetch_all($db, $stmt5);
if (count($rs)>0){
if ($rs[0]['ss11'] !='1'){
print "UPDATE";

$stmt2=<<<SQL
update otatest_order
set $updatestr
where patient=$ptid   and order_date >=CAST('$ordate' AS DATE) + CAST('-5 days' AS INTERVAL) and order_date<=CAST('$ordate' AS DATE) + CAST('+5 days' AS INTERVAL) and "Superseded" is null           

SQL;
 print $stmt2."\n";

 
pg_query($db, "BEGIN WORK");

 
$res=pg_query($db, $stmt2);

 
if (!$res) {
    pg_query($db, "ROLLBACK");
} else {
    pg_query($db, "COMMIT");
} 

$updatestr="";
}


} // if then end
else {
print "INSERT";

$stmt1=<<<SQL
insert into  otatest_order
( "ID", "ObjectID", patient, order_date,dos,$insert) values (
nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),
$ptid,'$ordate', '$ordate', 
$insertv
)

SQL;

print $stmt1."\n";

 
pg_query($db, "BEGIN WORK");

 
$res=pg_query($db, $stmt1);

 
if (!$res) {
    pg_query($db, "ROLLBACK");
} else {
    pg_query($db, "COMMIT");
} 

} //else end






//************************
 
 } //end for
//***********************


 
 

 
 


print '<p> COMPLETED </p>';
 




?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
