<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">�ᥤ������</a>


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
$p10=$rs3[0]['kk10'];
$p11=$rs3[0]['kk11'];
$p12=$rs3[0]['kk12'];
$p13=$rs3[0]['kk13'];
$p14=$rs3[0]['kk14'];
$p30=$rs3[0]['kk30'];
$p35=$rs3[0]['kk35'];
$p31=$rs3[0]['kk31'];
$p36=$rs3[0]['kk36'];
$p32=$rs3[0]['kk32'];
$p37=$rs3[0]['kk37'];
$p33=$rs3[0]['kk33'];
$p38=$rs3[0]['kk38'];
$p34=$rs3[0]['kk34'];
$p39=$rs3[0]['kk39'];
$p40=$rs3[0]['kk40'];
$p41=$rs3[0]['kk41'];
$p50=$rs3[0]['kk50'];
$p51=$rs3[0]['kk51'];
$p52=$rs3[0]['kk52'];
$p64=$rs3[0]['kk64'];
$p66=$rs3[0]['kk66'];
$p67=$rs3[0]['kk67'];
$p53=$rs3[0]['kk53'];
$p54=$rs3[0]['kk54'];
$p55=$rs3[0]['kk55'];
$p56=$rs3[0]['kk56'];
$p57=$rs3[0]['kk57'];
$p60=$rs3[0]['kk60'];
$p61=$rs3[0]['kk61'];
$p71=$rs3[0]['kk71'];
$p72=$rs3[0]['kk72'];
$p73=$rs3[0]['kk73'];
$p74=$rs3[0]['kk74'];
$p76=$rs3[0]['kk76'];
$p77=$rs3[0]['kk77'];
$p78=$rs3[0]['kk78'];
$p79=$rs3[0]['kk79'];
$p92=$rs3[0]['kk92'];
$p93=$rs3[0]['kk93'];
$p95=$rs3[0]['kk95'];

if ($preodate==null){

$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,
 kk10,kk11,kk12,kk13,kk14,kk30,kk35,kk31,kk36,kk32,kk37,kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk51,kk52,
kk64,kk66,kk67,kk53,kk54,kk55,kk56,kk57,kk60,kk61,kk71,kk72,kk73,kk74,kk76,kk77,kk78,kk79,kk92,kk93,kk95,            
pp10,pp11,pp12,pp13,pp14,pp30,pp35,pp31,pp36,pp32,pp37,
pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp51,pp52,pp64,pp66,
pp67,pp53,pp54,pp55,pp56,pp57,pp60,pp61,
pp71,pp72,pp73,pp74,pp76,pp77,pp78,pp79,pp92,pp93,pp95)
	values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),$ptid,'$ordate', 
	 $itemv[$row['01180']],$itemv[$row['01330']],$itemv[$row['01590']],$itemv[$row['01530']],$itemv[$row['01392']],$itemv[$row['01470']],$itemv[$row['01230']],$itemv[$row['01040']],$itemv[$row['01370']],$itemv[$row['80053']],$itemv[$row['01090']],$itemv[$row['01490']],$itemv[$row['01060']],$itemv[$row['01500']],$itemv[$row['07524']],$itemv[$row['07524']],$itemv[$row['07530']],$itemv[$row['01250']],$itemv[$row['80010']],$itemv[$row['02300']],$itemv[$row['80008']],$itemv[$row['80012']],$itemv[$row['80018']],$itemv[$row['01510']],$itemv[$row['01440']],$itemv[$row['01160']],$itemv[$row['01190']],$itemv[$row['01130']],$itemv[$row['80020']],$itemv[$row['80022']],$itemv[$row['00508']],$itemv[$row['00506']],$itemv[$row['00502']],$itemv[$row['00504']],$itemv[$row['80050']],$itemv[$row['80052']],$itemv[$row['80054']],$itemv[$row['00510']],$itemv[$row['02089']],$itemv[$row['02080']],$itemv[$row['02087']],
p10,p11,p12,p13,p14,p30,p35,p31,p36,p32,p37,
p33,p38,p34,p39,p40,p41,p50,p51,p52,p64,p66,
p67,p53,p54,p55,p56,p57,p60,p61,
p71,p72,p73,p74,p76,p77,p78,p79,p92,p93,p95)

          
SQL;

echo $stmt."<br />\n";


}
 
 


else {




$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,preorderdate,
 kk10,kk11,kk12,kk13,kk14,kk30,kk35,kk31,kk36,kk32,kk37,kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk51,kk52,
kk64,kk66,kk67,kk53,kk54,kk55,kk56,kk57,kk60,kk61,kk71,kk72,kk73,kk74,kk76,kk77,kk78,kk79,kk92,kk93,kk95,            
pp10,pp11,pp12,pp13,pp14,pp30,pp35,pp31,pp36,pp32,pp37,
pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp51,pp52,pp64,pp66,
pp67,pp53,pp54,pp55,pp56,pp57,pp60,pp61,
pp71,pp72,pp73,pp74,pp76,pp77,pp78,pp79,pp92,pp93,pp95)
	values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),$ptid,'$ordate', '$preodate',
	 $itemv[$row['01180']],$itemv[$row['01330']],$itemv[$row['01590']],$itemv[$row['01530']],$itemv[$row['01392']],$itemv[$row['01470']],$itemv[$row['01230']],$itemv[$row['01040']],$itemv[$row['01370']],$itemv[$row['80053']],$itemv[$row['01090']],$itemv[$row['01490']],$itemv[$row['01060']],$itemv[$row['01500']],$itemv[$row['07524']],$itemv[$row['07524']],$itemv[$row['07530']],$itemv[$row['01250']],$itemv[$row['80010']],$itemv[$row['02300']],$itemv[$row['80008']],$itemv[$row['80012']],$itemv[$row['80018']],$itemv[$row['01510']],$itemv[$row['01440']],$itemv[$row['01160']],$itemv[$row['01190']],$itemv[$row['01130']],$itemv[$row['80020']],$itemv[$row['80022']],$itemv[$row['00508']],$itemv[$row['00506']],$itemv[$row['00502']],$itemv[$row['00504']],$itemv[$row['80050']],$itemv[$row['80052']],$itemv[$row['80054']],$itemv[$row['00510']],$itemv[$row['02089']],$itemv[$row['02080']],$itemv[$row['/** @noinspection PhpExpressionResultUnusedInspection */02087']],
p10,p11,p12,p13,p14,p30,p35,p31,p36,p32,p37,
p33,p38,p34,p39,p40,p41,p50,p51,p52,p64,p66,
p67,p53,p54,p55,p56,p57,p60,p61,
p71,p72,p73,p74,p76,p77,p78,p79,p92,p93,p95)

          
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


<a href="index.php?tab=1">�ᥤ������</a>
<br>

	</tbody>
</table>

	</body>
</html>
