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

//	echo $row5['pt_id']."<br>"; 
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
echo $row['komokucode']."=".$itemv[$row['komokucode']]."=".$row['kname']."=".$row['unit']."<br>";
 

}

 

 

echo "orderdate=".$ordate."<br>";

 








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
$rs2=array();

  $rs2 = mx_db_fetch_all($db, $stmt2);
 

if (!$rs2)
{
	echo " same pid and orderdate record does not exist" . "<br />\n";
	 

// set values of most recent test-values.

$stmt3 = <<<SQL
select  
              patient, 
            order_date, 
kk10,kk11,kk12,kk13,kk14,kk30,kk35,kk31,kk36,kk32,kk37,
kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk51,kk52,
kk64,kk66,kk67,kk53,kk54,kk55,kk56,kk57,kk60,
kk61,kk71,kk72,kk73,kk74,kk76,kk77,kk78,kk79,
kk92,kk93,kk95,                        
pp10,pp11,pp12,pp13,pp14,pp30,pp35,pp31,pp36,pp32,pp37,
pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp51,pp52,pp64,pp66,
pp67,pp53,pp54,pp55,pp56,pp57,pp60,pp61,
pp71,pp72,pp73,pp74,pp76,pp77,pp78,pp79,pp92,pp93,pp95 
               from otatest_order
	where patient=$ptid   and order_date is not null and "Superseded" is null order by order_date desc
	 
          
SQL;

echo $stmt3."<br />\n";
//all 
  $rs3 = mx_db_fetch_all($db, $stmt3);

 $preodate=$rs3[0]['order_date'];
 echo $preodate."<br />\n";
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
 echo "preorder record  does not  exist, then the first record"."<br />\n";
$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,
  kk10,kk11,kk12,kk13,kk14,kk30,kk35,kk31,kk36,kk32,kk37,
kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk51,kk52,
kk64,kk66,kk67,kk53,kk54,kk55,kk56,kk57,kk60,
kk61,kk71,kk72,kk73,kk74,kk76,kk77,kk78,kk79,
kk92,kk93,kk95,                        
pp10,pp11,pp12,pp13,pp14,pp30,pp35,pp31,pp36,pp32,pp37,
pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp51,pp52,pp64,pp66,
pp67,pp53,pp54,pp55,pp56,pp57,pp60,pp61,
pp71,pp72,pp73,pp74,pp76,pp77,pp78,pp79,pp92,pp93,pp95)
values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),
$ptid,'$ordate', 
'$itemv[01180]','$itemv[01330]','$itemv[01590]','$itemv[01530]','$itemv[01392]',
'$itemv[01470]',
'$itemv[01230]','$itemv[01040]','$itemv[01370]','$itemv[80053]','$itemv[01090]',
'$itemv[01490]','$itemv[01060]','$itemv[01500]','$itemv[07524]','$itemv[07520]',
'$itemv[07530]','$itemv[01250]','$itemv[80010]','$itemv[02300]','$itemv[80008]',
'$itemv[80012]','$itemv[80018]','$itemv[01510]','$itemv[01440]','$itemv[01160]',
'$itemv[01190]','$itemv[01130]','$itemv[80020]','$itemv[80022]','$itemv[00508]',
'$itemv[00506]','$itemv[00502]','$itemv[00504]','$itemv[80050]','$itemv[80052]',
'$itemv[80054]','$itemv[00510]','$itemv[02089]','$itemv[02080]','$itemv[02087]',


	 
'$p10','$p11','$p12','$p13','$p14','$p30','$p35','$p31','$p36','$p32',
'$p37','$p33','$p38',
'$p34','$p39','$p40','$p41','$p50','$p51','$p52','$p64','$p66','$p67',
'$p53','$p54','$p55',
'$p56','$p57','$p60','$p61','$p71','$p72','$p73','$p74','$p76','$p77',
'$p78','$p79','$p92',
'$p93','$p95')



          
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



}
 
 


else {

echo "preorder record   exist"."<br />\n";
$stmt20 = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,preorderdate,
 kk10,kk11,kk12,kk13,kk14,kk30,kk35,kk31,kk36,kk32,kk37,
kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk51,kk52,
kk64,kk66,kk67,kk53,kk54,kk55,kk56,kk57,kk60,
kk61,kk71,kk72,kk73,kk74,kk76,kk77,kk78,kk79,
kk92,kk93,kk95,                        
pp10,pp11,pp12,pp13,pp14,pp30,pp35,pp31,pp36,pp32,pp37,
pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp51,pp52,pp64,pp66,
pp67,pp53,pp54,pp55,pp56,pp57,pp60,pp61,
pp71,pp72,pp73,pp74,pp76,pp77,pp78,pp79,pp92,pp93,pp95)
values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),
$ptid,'$ordate', '$preodate',
'$itemv[01180]','$itemv[01330]','$itemv[01590]','$itemv[01530]','$itemv[01392]',
'$itemv[01470]',
'$itemv[01230]','$itemv[01040]','$itemv[01370]','$itemv[80053]','$itemv[01090]',
'$itemv[01490]','$itemv[01060]','$itemv[01500]','$itemv[07524]','$itemv[07520]',
'$itemv[07530]','$itemv[01250]','$itemv[80010]','$itemv[02300]','$itemv[80008]',
'$itemv[80012]','$itemv[80018]','$itemv[01510]','$itemv[01440]','$itemv[01160]',
'$itemv[01190]','$itemv[01130]','$itemv[80020]','$itemv[80022]','$itemv[00508]',
'$itemv[00506]','$itemv[00502]','$itemv[00504]','$itemv[80050]','$itemv[80052]',
'$itemv[80054]','$itemv[00510]','$itemv[02089]','$itemv[02080]','$itemv[02087]',


	 
'$p10','$p11','$p12','$p13','$p14','$p30','$p35','$p31','$p36','$p32',
'$p37','$p33','$p38',
'$p34','$p39','$p40','$p41','$p50','$p51','$p52','$p64','$p66','$p67',
'$p53','$p54','$p55',
'$p56','$p57','$p60','$p61','$p71','$p72','$p73','$p74','$p76','$p77',
'$p78','$p79','$p92',
'$p93','$p95')


          
SQL;



 
echo $stmt20."<br />\n";

  $sh20=pg_query($db, $stmt20);  
if ($sh20){
print '<p>test insert </p>';
}
else {
print '<p > test insert error</p>';
die;
}

} //end else
 
 
 


} //no exist
 else {

//exits order of pid and orderdate record already
print '<p>previous record exists </p>';

 
$stmt4 = <<<SQL
select  
              patient, 
            order_date, 
kk10,kk11,kk12,kk13,kk14,kk30,kk35,kk31,kk36,kk32,kk37,
kk33,kk38,kk34,kk39,kk40,kk41,kk50,kk51,kk52,
kk64,kk66,kk67,kk53,kk54,kk55,kk56,kk57,kk60,
kk61,kk71,kk72,kk73,kk74,kk76,kk77,kk78,kk79,
kk92,kk93,kk95,                        
pp10,pp11,pp12,pp13,pp14,pp30,pp35,pp31,pp36,pp32,pp37,
pp33,pp38,pp34,pp39,pp40,pp41,pp50,pp51,pp52,pp64,pp66,
pp67,pp53,pp54,pp55,pp56,pp57,pp60,pp61,
pp71,pp72,pp73,pp74,pp76,pp77,pp78,pp79,pp92,pp93,pp95 
               from otatest_order
	where patient=$ptid   and order_date is not null and "Superseded" is null order by order_date desc

	 
          
SQL;

echo $stmt4."<br />\n";
//all 
  $rs3 = mx_db_fetch_all($db, $stmt4);
 $preodate=$rs3[0]['order_date'];
 echo $preodate."<br />\n";
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
 
  
 
$stmt30 = <<<SQL
update otatest_order 
	set
kk10='$itemv[01180]',kk11='$itemv[01330]',kk12='$itemv[01590]',kk13='$itemv[01530]',kk14='$itemv[01392]',kk30='$itemv[01470]',
kk35='$itemv[01230]',kk31='$itemv[01040]',kk36='$itemv[01370]',kk32='$itemv[80053]',kk37='$itemv[01090]',
kk33='$itemv[01490]',kk38='$itemv[01060]',kk34='$itemv[01500]',kk39='$itemv[07524]',kk40='$itemv[07520]',
kk41='$itemv[07530]',kk50='$itemv[01250]',kk51='$itemv[80010]',kk52='$itemv[02300]',kk64='$itemv[80008]',
kk66='$itemv[80012]',kk67='$itemv[80018]',kk53='$itemv[01510]',kk54='$itemv[01440]',kk55='$itemv[01160]',
kk56='$itemv[01190]',kk57='$itemv[01130]',kk60='$itemv[80020]',kk61='$itemv[80022]',kk71='$itemv[00508]',
kk72='$itemv[00506]',kk73='$itemv[00502]',kk74='$itemv[00504]',kk76='$itemv[80050]',kk77='$itemv[80052]',
kk78='$itemv[80054]',kk79='$itemv[00510]',kk92='$itemv[02089]',kk93='$itemv[02080]',kk95='$itemv[02087]',

pp10='$p10',pp11='$p11',pp12='$p12',pp13='$p13',pp14='$p14',
pp30='$p30',pp35='$p35',pp31='$p31',pp36='$p36',pp32='$p32',
pp37='$p37',pp33='$p33',pp38='$p38',pp34='$p34',pp39='$p39',
pp40='$p40',pp41='$p41',pp50='$p50',pp51='$p51',pp52='$p52',
pp64='$p64',pp66='$p66',pp67='$p67',pp53='$p53',pp54='$p54',
pp55='$p55',pp56='$p56',pp57='$p57',pp60='$p60',pp61='$p61',
pp71='$p71',pp72='$p72',pp73='$p73',pp74='$p74',pp76='$p76',
pp77='$p77',pp78='$p78',pp79='$p79',pp92='$p92',pp93='$p93',pp95='$p95'

 		  
	where patient=$ptid   and order_date ='$ordate' and "Superseded" is null 
          
SQL;

echo $stmt30."<br />\n";

  $sh30=pg_query($db, $stmt30);  
if ($sh30){
print '<p>update </p>';
}
else {
print '<p > update error</p>';
die;
}


} //else end

 

 
} //for end


print '<p> COMPLETED </p>';
  
 
$stmt9 = <<<SQL
update test_resultwkcmb
	set sw='1'
	          
SQL;

echo $stmt9."<br />\n";

  $sh9=pg_query($db, $stmt9);  
if ($sh9){
print '<p>update sw </p>';
}
else {
print '<p > update sw error</p>';
die;
}
 


?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
