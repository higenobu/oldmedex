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

 


// read otestr table 


 $stmt5 = <<<SQL
SELECT patient, patientid, 
            plname, pfname, orderid, orderdate, resultdate, category, itemcode, 
            itemname, itemvalue1, itemvalue2, itemunit, normalvalue, comments 
 from otestr  where sw is null order by (patient,orderdate)  desc
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

for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

$ptid=$ptids[$k];
$ordate=$ordates[$k];
echo $ptid."=ptid<br>";
 $stmt0 = <<<SQL
SELECT patient, patientid, 
            plname, pfname, orderid, orderdate, resultdate, category, itemcode, 
            itemname, itemvalue1, itemvalue2, itemunit, normalvalue, comments 
 from otestr  where patient=$ptid and orderdate= '$ordate' and sw is null
SQL;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $itemv = array();
  foreach($rows as $row)
 {
	switch ($row['itemcode']) {
    case "11":
        echo "i is 11<br>";
	$itemv['11']=$row['itemvalue1'];
        break;
    case "12":
        echo "i is 12 <br>";
	$itemv['12']=$row['itemvalue1'];
        break;
    case "13":
        echo "i is 13 <br>";
	$itemv['13']=$row['itemvalue1'];
        break;
	}
}

echo $itemv['11']."<br>";
echo $itemv['12']."<br>";
echo $itemv['13']."<br>";













$stmt2 = <<<SQL
select  
              patient, 
            order_date  
               from otatest_order
	where patient=$ptid   and order_date='$ordate'   
	 
          
SQL;

echo $stmt2."<br />\n";

  $rs2 = mx_db_fetch_single($db, $stmt2);

if (!$rs2)
{
	echo "non exist" . "<br />\n";
	 


$stmt3 = <<<SQL
select  
              patient, 
            order_date, kk11,kk12,kk13  
               from otatest_order
	where patient=$ptid  and order_date is not null and "Superseded" is null order by order_date desc
	 
          
SQL;

echo $stmt3."<br />\n";

  $rs3 = mx_db_fetch_single($db, $stmt3);

 
$p11=$rs3['kk11'];
echo $p11."<br />\n";






$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, 
            order_date,  kk11,   kk12, 
             kk13,pp11 ) 
	values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),$ptid,'$ordate',
	'$itemv[11]','$itemv[12]','$itemv[13]', $p11)
          
SQL;

echo $stmt."<br />\n";

  $sh=pg_query($db, $stmt);  
if ($sh){
print '<p>test insert </p>';
}
else {
print '<p > test insert error</p>';
die;
}

//set sw to 1 update

 $stmt11 = <<<SQL
update  otestr set sw=1 where patient=$ptid and orderdate= '$ordate' and sw is null
SQL;
  $sh=pg_query($db, $stmt11);  
if ($sh){
print '<p>update sw  </p>';
}
else {
print '<p > update error</p>';
die;
}
//


print '<p> COMPLETED </p>';
  
}
else{
	echo "patient exist :".$rs2['patient'] . "<br />\n";
	echo "orderdate exist :".$rs2['order_date'] . "<br />\n";
	//set sw to 1 update

 	$stmt11 = <<<SQL
	update  otestr set sw=1 where patient=$ptid and orderdate= '$ordate' and sw is null
SQL;
  $sh=pg_query($db, $stmt11);  
if ($sh){
print '<p>update sw  </p>';
}
else {
print '<p > update error</p>';
die;
}
//
	
 } //else-end
// for-end
} 

?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
