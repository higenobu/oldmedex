<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT  from kenshin</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


<br>


<?php
//debug purpose


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

 


// read otestr table 


 $stmt5 = <<<SQL
SELECT patient,  orderdate
 from kenshin  
SQL;

$rows5 =  mx_db_fetch_all($db, $stmt5);
  $ptids = array();
$ordates = array();
$i=0;
  foreach($rows5 as $row5)
 {

	echo $row5['patient']."<br>"; 
 if($ptid ==$row5['patient'] && $ordate ==$row5['orderdate']){
  	 }
	else {
	$ptid=$row5['patient'];
	 $ordate=$row5['orderdate'];
	$ptids[$i]=$row5['patient'];
	 $ordates[$i]=$row5['orderdate'];
	 $i++;

	}

}
//

for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

$ptid=$ptids[$k];
$ordate=$ordates[$k];
echo $ptid."=ptid<br>";
 $stmt0 = <<<SQL
SELECT patient,   orderdate
 from kenshin  where patient=$ptid and orderdate= '$ordate'  
SQL;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $itemv = array();
 










//if record exists or not 


$stmt2 = <<<SQL
select  
              patient, 
            order_date  
               from otatest_order
	where patient=$ptid   and order_date='$ordate' and "Superseded" is null 
	 
          
SQL;

echo $stmt2."<br />\n";

  $rs2 = mx_db_fetch_single($db, $stmt2);
if (!rs2){print "no record of ptid and ordate";}

 
//
 
	 
/*
//add previous data 
$stmt3 = <<<SQL
select  
              patient, 
            order_date,kk11,kk12 
               from otatest_order
	where order_date is not null and 
patient=$ptid   and order_date<'$ordate' and "Superseded" is null order by order_date desc
	   
	 
SQL;

echo $stmt3."<br />\n";
$rs3=array();
  $rs3 = mx_db_fetch_all($db, $stmt3);

 if (!$rs3){ print "no record of previous record";}
$i=0;
  foreach($rs3 as $row3)
 {
 
	if ($i<1){$p11=$row3['kk11'];
		$predate=$row3['order_date'];
		}
	 
	 $i++;

}
 
echo $p11."=p11<br />\n";
 echo $predate."=predate<br />\n";
*/


//insert a new record to otatest_order

if (!$rs2){




}
else {
//if record exists, update

$stmt9 = <<<SQL
update  otatest_order
set   kk10='$ptid',
	kk11='11',
	kk12='12',
	kk13='13',
	kk14='14',
	
        kk15='10',
	 
kk30='10',
	kk31='11',
	kk32='12',
	kk33='13',
	kk34='14',
	
        kk35='10',
	kk36='11',
	kk37='12',
	kk38='13',
	kk39='14',

kk70='10',
	kk71='11',
	kk72='12',
	kk73='13',
	kk74='14',
	
        kk75='10',
	kk76='11',
	kk77='12',
	kk78='13',
	kk79='14',

kk60='10',
	kk61='11',
	kk62='12',
	kk63='13',
	kk64='14',
	
        kk65='10',
	kk66='11',
	kk67='12',
kk50='10',
	kk51='11',
	kk52='12',
	kk53='13',
	kk54='14',
	
        kk55='10',
	kk56='11',
	kk57='12',
	kk58='13',
	kk59='14',


kk90='10',
	kk91='11',
	kk92='12',
	kk93='13',
	kk94='14',
	
        kk95='10',
kk20='10',
	kk21='11',
	kk22='12',
kk40='10',
	kk41='11',
	kk42='12'


  

	
          where patient=$ptid   and order_date='$ordate'   and "Superseded" is null 
SQL;

echo $stmt9."<br />\n";

  $sh1=pg_query($db, $stmt9);  
if ($sh1){
	print '<p>test updated </p>';
	}
	else {
	print '<p > test update error</p>';
	die;
	}



} //else-end

//set sw to 1 update
 

 


print '<p> COMPLETED </p>';
  

 
// for-end
} 

?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
