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



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//delete past data from this date and insert all data from this date into resultwk9
//0425-2014 will not delete. Only insert into test_resultwk9

 $ordate=$_POST[dos];

$db = mx_db_connect();

 

$today=mx_today_string();
//
 $stmt = <<<SQL
SELECT id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient2 on ptname=pt_nm   where
	substring(testdate,1,8)>= '$ordate' 
       order by (id,komokucode)
SQL;
//print $stmt;

$rows5 =  mx_db_fetch_all($db, $stmt);
  	 
  foreach($rows5 as $row5)
 {
//&& $ordate ==substr($row5['testdate'],0,8)
//avoid duplicate data 
if($ptid ==$row5['id']  && $komoku==$row5['komokucode'] )
{

//print "same";

 } //if then end
else {
//	print "dff"."\n";
	$ptid=$row5['id'];
	 $ordate=substr($row5['testdate'],0,8);
	$komoku=$row5['komokucode'];
	$kname=$row5['kname'];
	$ijo=$row5['ijo'];
	$ptname=$row5['ptname'];
	$kekka=$row5['kekka'];
	print $ptid."=".$ordate."=".$komoku."\n";

	$stmt9 = <<<SQL
SELECT  * from test_resultwk9  where pt_id=$ptid and komokucode='$komoku' and testdate='$ordate'
SQL;
//print $stmt9;
	$rows9 =  mx_db_fetch_all($db, $stmt9);
	if ( count($rows9)>0){
//exist then skip
		}
	else {


//no data then insert into resultwk9



	$stmt2 = <<<SQL
insert into test_resultwk9(pt_id,testdate,komokucode,kname,ptname,kekka,ijo)   
values ($ptid,'$ordate', '$komoku','$kname','$ptname','$kekka','$ijo')
SQL;
	pg_query($db, "BEGIN WORK");
	$res=pg_query($db, $stmt2);
	if (!$res) {
    	pg_query($db, "ROLLBACK");
	} else {
    	pg_query($db, "COMMIT");
	} 
	print $stmt2;

	
	  

	}  
 }
  
}  //foreach end

/* 

 $stmt = <<<SQL
delete   
       from test_resultwkcmb    where substring(testdate,1,8)<= '$ordate' 
SQL;
pg_query($db, "BEGIN WORK");

// Some insert/update/delete queries.
$res=pg_query($db, $stmt);

// Verify and end the transaction as appropriate.
if (!$res) {
    pg_query($db, "ROLLBACK");
} else {
    pg_query($db, "COMMIT");
} 


*/

 

 

 







?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
