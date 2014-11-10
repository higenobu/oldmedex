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
 

$db = mx_db_connect();

 


// read otestr table 


 $stmt5 = <<<SQL
SELECT pt_id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient on pt_no=karteno and sw !='1'     
SQL;

$rows5 =  mx_db_fetch_all($db, $stmt5);
  $ptids = array();
$ordates = array();
$i=0;
  foreach($rows5 as $row5)
 {

	echo $row5['pt_id']."<br>"; 
 if($ptid ==$row5['pt_id'] && $ordate ==substr($row5['testdate'],0,8)){
  	 }
	else {
	$ptid=$row5['pt_id'];
	 $ordate=substr($row5['testdate'],0,8);
	$ptids[$i]=$row5['pt_id'];
	 $ordates[$i]=substr($row5['testdate'],0,8);
	 $i++;

	}

}  //foreach end




for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

$ptid=$ptids[$k];
$ordate=$ordates[$k];
echo $ptid."=ptid<br>";

 $stmt0 = <<<SQL
SELECT pt_id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient on pt_no=karteno    where pt_id=$ptid and substring(testdate,1,8)= '$ordate'  and sw !='1'          
SQL;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $itemv = array();
  foreach($rows as $row)
 {
 
$itemv[$row['komokucode']]=$row['kekka'];
echo $row['komokucode']."=".$itemv[$row['komokucode']]."<br>";
/*
switch ($row['komokucode']) {
    case "00508":
         
	$itemv['00508']=$row['kekka'];
        break;
    case "00506":
         
	$itemv['00506']=$row['kekka'];
        break;
    case "00502":
         
	$itemv['00502']=$row['kekka'];
        break;
case "00504":
         
	$itemv['00504']=$row['kekka'];
        break;
case "80050":
         
	$itemv['80050']=$row['kekka'];
        break;
    case "80052":
         
	$itemv['80052']=$row['kekka'];
        break;
    
	}
*/

}

 

 



 








//where patient=$ptid   and order_date='$ordate'  

$stmt2 = <<<SQL
select  
              patient, 
            order_date  
               from otatest_order
	where patient=$ptid   and order_date='$ordate'  and  "Superseded" is null
	 
          
SQL;

echo $stmt2."<br />\n";
//all not single
  $rs2 = mx_db_fetch_all($db, $stmt2);

if (!$rs2)
{
	echo "non exist" . "<br />\n";
	 

// set values of most recent test-values.

$stmt3 = <<<SQL
select  
              patient, 
            order_date, kk11,kk12,kk13  
               from otatest_order
	where patient=$ptid   and order_date is not null and "Superseded" is null order by order_date desc
	 
          
SQL;

echo $stmt3."<br />\n";
//all 
  $rs3 = mx_db_fetch_all($db, $stmt3);

 $preodate=$rs3[0]['order_date'];
$p11=$rs3[0]['kk11'];
$p12=$rs3[0]['kk12'];
$p13=$rs3[0]['kk13'];

if ($preodate==null){

$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,
            kk10,kk11,kk12,kk13,kk14,kk21, kk30,kk35,kk31,kk36,kk32,kk37,kk33,kk37,kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk52,kk64,kk65,kk66,kk67,kk53,
kk54,kk56,kk57,kk60,kk61,kk71,kk72,kk73,kk74,kk76,kk78,kk79,kk92,kk93,kk94,kk95, 
pp10,pp11,pp12,pp13,pp14,pp21, pp30,pp35,pp31,pp36,pp32,pp37,pp33,pp37,pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp52,pp64,pp65,pp66,pp67,pp53,
pp54,pp56,pp57,pp60,pp61,pp71,pp72,pp73,pp74,pp76,pp78,pp79,pp92,pp93,pp94,pp95)
	values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),$ptid,'$ordate', 
	'$itemv[00508]','$itemv[00506]','$itemv[00502]', '$p11', '$p12', '$p13')
          
SQL;

echo $stmt."<br />\n";


}
 
 


else {



	$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, 
            order_date,preorderdate,  kk11,   kk12, 
             kk13,pp11,pp12,pp13 ) 
	values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),	$ptid,'$ordate','$preodate',
	'$itemv[00508]','$itemv[00506]','$itemv[00502]', '$p11', '$p12', '$p13')
          
SQL;

echo $stmt."<br />\n";
}

  $sh=pg_query($db, $stmt);  
if ($sh){
print '<p>test insert </p>';
}
else {
print '<p > test insert error</p>';
die;
}

} //no exist
 else {

//exits order record already
print '<p>record exists </p>';

 
$stmt3 = <<<SQL
select              patient, 
            order_date, kk11,kk12,kk13  
               from otatest_order
	where patient=$ptid   and order_date is not null and "Superseded" is null order by order_date desc
	 
          
SQL;

echo $stmt3."<br />\n";
//all 
  $rs3 = mx_db_fetch_all($db, $stmt3);

  
$p11=$rs3[0]['kk11'];
$p12=$rs3[0]['kk12'];
$p13=$rs3[0]['kk13'];
 
$stmt = <<<SQL
update otatest_order 
	set
	 kk11='$itemv[00508]',kk12='$itemv[00506]',
kk13='$itemv[00502]'  ,pp11='$p11',pp12='$p12',pp13='$p13'		  
	where patient=$ptid   and order_date ='$ordate' and "Superseded" is null 
          
SQL;

echo $stmt."<br />\n";

  $sh=pg_query($db, $stmt);  
if ($sh){
print '<p>update </p>';
}
else {
print '<p > update error</p>';
die;
}


}

 

 
} //for end


print '<p> COMPLETED </p>';
  
 // delete wkcmb
$stmt9 = <<<SQL
update test_resultwkcmb
	set sw='1'
	          
SQL;

echo $stmt9."<br />\n";

  $sh9=pg_query($db, $stmt9);  
if ($sh9){
print '<p>update9 </p>';
}
else {
print '<p > update9 error</p>';
die;
}

?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
