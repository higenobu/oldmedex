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

 $ordate=$_POST[ordate];
 
 
 
 

 

echo "orderdate=".$ordate."<br>";

 
$stmt2 = <<<SQL
select  
              patient, 
            order_date  
               from otatest_order 
	where   order_date >= '$ordate'  and  "Superseded" is null
	 
          
SQL;


$ptids = array();
$ordates = array();
$i=0; 
$rows5=array();

  $rows5 = mx_db_fetch_all($db, $stmt2);

 foreach($rows5 as $row5)
 {

  
if($ptid ==$row5['patient']){
//skip
  	 }
	else {
	$ptid=$row5['patient'];
	
	 $ordate=$row5['order_date'];
print $ptid."=".$ordate."<br>\n";
	$ptids[$i]=$ptid;
	 $ordates[$i]=$ordate;
	 $i++;

	}
}  //foreach end

for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

	$ptid=$ptids[$k];

	$ordates[$i]=$ordate;






 

$stmt3 = <<<SQL
SELECT * FROM otatest_order

	where patient=$ptid   and order_date <CAST('$ordate' AS DATE) + CAST('-30 days' AS INTERVAL) and "Superseded" is null  order by order_date desc
	 
          
SQL;

 
$rs3=array();
  $rs3 = mx_db_fetch_all($db, $stmt3);
if (count($rs3)>0){
 $preodate=$rs3[0]['order_date'];
$special=$rs3[0]['special_req'];
$notes=$rs3[0]['notes'];
 echo $preodate."<br />\n";

$kk0 = $rs3[0]['kk0'];
$kk1 = $rs3[0]['kk1'];
$kk2 = $rs3[0]['kk2'];
$kk3 = $rs3[0]['kk3'];
$kk4 = $rs3[0]['kk4'];
$kk5 = $rs3[0]['kk5'];
$kk6 = $rs3[0]['kk6'];
$kk7 = $rs3[0]['kk7'];
$kk8 = $rs3[0]['kk8'];
$kk9 = $rs3[0]['kk9'];
$kk10 = $rs3[0]['kk10'];
$kk11 = $rs3[0]['kk11'];
$kk12 = $rs3[0]['kk12'];
$kk13 = $rs3[0]['kk13'];
$kk14 = $rs3[0]['kk14'];
$kk15 = $rs3[0]['kk15'];
$kk20 = $rs3[0]['kk20'];
$kk21 = $rs3[0]['kk21'];
$kk22 = $rs3[0]['kk22'];
$kk40 = $rs3[0]['kk40'];
$kk41 = $rs3[0]['kk41'];
$kk42 = $rs3[0]['kk42'];
$kk50 = $rs3[0]['kk50'];
$kk51 = $rs3[0]['kk51'];
$kk52 = $rs3[0]['kk52'];
$kk53 = $rs3[0]['kk53'];
$kk54 = $rs3[0]['kk54'];
$kk55 = $rs3[0]['kk55'];
$kk56 = $rs3[0]['kk56'];
$kk57 = $rs3[0]['kk57'];
$kk58 = $rs3[0]['kk58'];
$kk59 = $rs3[0]['kk59'];
$kk30 = $rs3[0]['kk30'];
$kk31 = $rs3[0]['kk31'];
$kk32 = $rs3[0]['kk32'];
$kk33 = $rs3[0]['kk33'];
$kk34 = $rs3[0]['kk34'];
$kk35 = $rs3[0]['kk35'];
$kk36 = $rs3[0]['kk36'];
$kk37 = $rs3[0]['kk37'];
$kk38 = $rs3[0]['kk38'];
$kk39 = $rs3[0]['kk39'];
$kk60 = $rs3[0]['kk60'];
$kk61 = $rs3[0]['kk61'];
$kk62 = $rs3[0]['kk62'];
$kk63 = $rs3[0]['kk63'];
$kk64 = $rs3[0]['kk64'];
$kk65 = $rs3[0]['kk65'];
$kk66 = $rs3[0]['kk66'];
$kk67 = $rs3[0]['kk67'];
$kk70 = $rs3[0]['kk70'];
$kk71 = $rs3[0]['kk71'];
$kk72 = $rs3[0]['kk72'];
$kk73 = $rs3[0]['kk73'];
$kk74 = $rs3[0]['kk74'];
$kk75 = $rs3[0]['kk75'];
$kk76 = $rs3[0]['kk76'];
$kk77 = $rs3[0]['kk77'];
$kk78 = $rs3[0]['kk78'];
$kk79 = $rs3[0]['kk79'];
$kk90 = $rs3[0]['kk90'];
$kk91 = $rs3[0]['kk91'];
$kk92 = $rs3[0]['kk92'];
$kk93 = $rs3[0]['kk93'];
$kk94 = $rs3[0]['kk94'];
$kk95 = $rs3[0]['kk95'];
$k100 = $rs3[0]['k100'];
$k101 = $rs3[0]['k101'];
$k102 = $rs3[0]['k102'];
$k103 = $rs3[0]['k103'];
$k104 = $rs3[0]['k104'];
$k105 = $rs3[0]['k105'];
$k106 = $rs3[0]['k106'];
$k200 = $rs3[0]['k200'];
$k201 = $rs3[0]['k201'];
$k202 = $rs3[0]['k202'];
$k203 = $rs3[0]['k203'];
$k204 = $rs3[0]['k204'];
$k205 = $rs3[0]['k205'];
$k206 = $rs3[0]['k206'];
$k207 = $rs3[0]['k207'];
$k208 = $rs3[0]['k208'];
$k300 = $rs3[0]['k300'];
$k301 = $rs3[0]['k301'];
$k302 = $rs3[0]['k302'];
$k500 = $rs3[0]['k500'];
$k501 = $rs3[0]['k501'];
$k502 = $rs3[0]['k502'];
$k503 = $rs3[0]['k503'];
$k504 = $rs3[0]['k504'];
$k505 = $rs3[0]['k505'];
$k506 = $rs3[0]['k506'];
$k507 = $rs3[0]['k507'];
$k508 = $rs3[0]['k508'];
$k509 = $rs3[0]['k509'];
$k510 = $rs3[0]['k510'];
$k511 = $rs3[0]['k511'];
$k512 = $rs3[0]['k512'];
$k513 = $rs3[0]['k513'];
$k514 = $rs3[0]['k514'];
$k515 = $rs3[0]['k515'];
$k516 = $rs3[0]['k516'];
$k517 = $rs3[0]['k517'];
$k518 = $rs3[0]['k518'];
$k519 = $rs3[0]['k519'];
$k520 = $rs3[0]['k520'];
$k80 = $rs3[0]['k80'];
$k81 = $rs3[0]['k81'];
$k82 = $rs3[0]['k82'];
$k83 = $rs3[0]['k83'];
$k84 = $rs3[0]['k84'];
$k85 = $rs3[0]['k85'];
$k86 = $rs3[0]['k86'];
$k87 = $rs3[0]['k87'];
$k88 = $rs3[0]['k88'];
$k89 = $rs3[0]['k89'];
$k90 = $rs3[0]['k90'];
$k91 = $rs3[0]['k91'];
$k400 = $rs3[0]['k400'];
$k401 = $rs3[0]['k401'];
$k402 = $rs3[0]['k402'];
$k403 = $rs3[0]['k403'];
$k404 = $rs3[0]['k404'];
$k405 = $rs3[0]['k405'];
$k406 = $rs3[0]['k406'];
$k407 = $rs3[0]['k407'];
$k408 = $rs3[0]['k408'];
$k409 = $rs3[0]['k409'];
$k410 = $rs3[0]['k410'];
$k411 = $rs3[0]['k411'];
$k412 = $rs3[0]['k412'];
$k413 = $rs3[0]['k413'];
$k414 = $rs3[0]['k414'];
$k415 = $rs3[0]['k415'];
$kk600 = $rs3[0]['kk600'];
$kk601 = $rs3[0]['kk601'];
$kk602 = $rs3[0]['kk602'];
$kk603 = $rs3[0]['kk603'];
$kk604 = $rs3[0]['kk604'];
$kk605 = $rs3[0]['kk605'];
$kk606 = $rs3[0]['kk606'];
$kk607 = $rs3[0]['kk607'];
$kk608 = $rs3[0]['kk608'];
$kk609 = $rs3[0]['kk609'];
$kk610 = $rs3[0]['kk610'];
$kk611 = $rs3[0]['kk611'];
$kk612 = $rs3[0]['kk612'];
$kk613 = $rs3[0]['kk613'];
$kk614 = $rs3[0]['kk614'];
$kk615 = $rs3[0]['kk615'];
$kk616 = $rs3[0]['kk616'];
$kk617 = $rs3[0]['kk617'];
$kk618 = $rs3[0]['kk618'];
$kk619 = $rs3[0]['kk619'];
$kk620 = $rs3[0]['kk620'];
$kk530 = $rs3[0]['kk530'];
$kk540 = $rs3[0]['kk540'];
$kk531 = $rs3[0]['kk531'];
$kk541 = $rs3[0]['kk541'];
$kk532 = $rs3[0]['kk532'];
$kk542 = $rs3[0]['kk542'];
$k107 = $rs3[0]['k107'];
$k416 = $rs3[0]['k416'];
$k417 = $rs3[0]['k417'];
$kk417 = $rs3[0]['kk417'];
$k1000 = $rs3[0]['k1000'];
$k1001 = $rs3[0]['k1001'];
$k1003 = $rs3[0]['k1003'];
$k1002 = $rs3[0]['k1002'];
$k1004 = $rs3[0]['k1004'];
$k1005 = $rs3[0]['k1005'];
$k1006 = $rs3[0]['k1006'];
$k1007 = $rs3[0]['k1007'];
$k418 = $rs3[0]['k418'];
$k560 = $rs3[0]['k560'];
$k561 = $rs3[0]['k561'];
$k562 = $rs3[0]['k562'];
$k563 = $rs3[0]['k563'];
$k564 = $rs3[0]['k564'];
$kk100 = $rs3[0]['kk100'];
$kk101 = $rs3[0]['kk101'];
$kk102 = $rs3[0]['kk102'];
$kk103 = $rs3[0]['kk103'];
$kk104 = $rs3[0]['kk104'];
$kk105 = $rs3[0]['kk105'];
$kk106 = $rs3[0]['kk106'];
$kk107 = $rs3[0]['kk107'];
$kk108 = $rs3[0]['kk108'];
$kk109 = $rs3[0]['kk109'];
$kk110 = $rs3[0]['kk110'];
$kk111 = $rs3[0]['kk111'];
$kk112 = $rs3[0]['kk112'];
$kk113 = $rs3[0]['kk113'];
$kk114 = $rs3[0]['kk114'];
$kk115 = $rs3[0]['kk115'];
$kk116 = $rs3[0]['kk116'];
$kk117 = $rs3[0]['kk117'];
$kk118 = $rs3[0]['kk118'];
$kk119 = $rs3[0]['kk119'];
$kk120 = $rs3[0]['kk120'];
$kk121 = $rs3[0]['kk121'];
$kk122 = $rs3[0]['kk122'];
$kk123 = $rs3[0]['kk123'];
$kk124 = $rs3[0]['kk124'];
$kk125 = $rs3[0]['kk125'];
$kk126 = $rs3[0]['kk126'];
$kk127 = $rs3[0]['kk127'];

 





 

  
 

 
 
 
$stmt30 = <<<SQL
update otatest_order 
	set
preorderdate='$preodate',


pp0 = '$kk0',
pp1 = '$kk1',
pp2 = '$kk2',
pp3 = '$kk3',
pp4 = '$kk4',
pp5 = '$kk5',
pp6 = '$kk6',
pp7 = '$kk7',
pp8 = '$kk8',
pp9 = '$kk9',
pp10 = '$kk10',
pp11 = '$kk11',
pp12 = '$kk12',
pp13 = '$kk13',
pp14 = '$kk14',
pp15 = '$kk15',
pp20 = '$kk20',
pp21 = '$kk21',
pp22 = '$kk22',
pp40 = '$kk40',
pp41 = '$kk41',
pp42 = '$kk42',
pp50 = '$kk50',
pp51 = '$kk51',
pp52 = '$kk52',
pp53 = '$kk53',
pp54 = '$kk54',
pp55 = '$kk55',
pp56 = '$kk56',
pp57 = '$kk57',
pp58 = '$kk58',
pp59 = '$kk59',
pp30 = '$kk30',
pp31 = '$kk31',
pp32 = '$kk32',
pp33 = '$kk33',
pp34 = '$kk34',
pp35 = '$kk35',
pp36 = '$kk36',
pp37 = '$kk37',
pp38 = '$kk38',
pp39 = '$kk39',
pp60 = '$kk60',
pp61 = '$kk61',
pp62 = '$kk62',
pp63 = '$kk63',
pp64 = '$kk64',
pp65 = '$kk65',
pp66 = '$kk66',
pp67 = '$kk67',
pp70 = '$kk70',
pp71 = '$kk71',
pp72 = '$kk72',
pp73 = '$kk73',
pp74 = '$kk74',
pp75 = '$kk75',
pp76 = '$kk76',
pp77 = '$kk77',
pp78 = '$kk78',
pp79 = '$kk79',
pp90 = '$kk90',
pp91 = '$kk91',
pp92 = '$kk92',
pp93 = '$kk93',
pp94 = '$kk94',
pp95 = '$kk95',
p100 = '$k100',
p101 = '$k101',
p102 = '$k102',
p103 = '$k103',
p104 = '$k104',
p105 = '$k105',
p106 = '$k106',
p200 = '$k200',
p201 = '$k201',
p202 = '$k202',
p203 = '$k203',
p204 = '$k204',
p205 = '$k205',
p206 = '$k206',
p207 = '$k207',
p208 = '$k208',
p300 = '$k300',
p301 = '$k301',
p302 = '$k302',
p500 = '$k500',
p501 = '$k501',
p502 = '$k502',
p503 = '$k503',
p504 = '$k504',
p505 = '$k505',
p506 = '$k506',
p507 = '$k507',
p508 = '$k508',
p509 = '$k509',
p510 = '$k510',
p511 = '$k511',
p512 = '$k512',
p513 = '$k513',
p514 = '$k514',
p515 = '$k515',
p516 = '$k516',
p517 = '$k517',
p518 = '$k518',
p519 = '$k519',
p520 = '$k520',
p80 = '$k80',
p81 = '$k81',
p82 = '$k82',
p83 = '$k83',
p84 = '$k84',
p85 = '$k85',
p86 = '$k86',
p87 = '$k87',
p88 = '$k88',
p89 = '$k89',
p90 = '$k90',
p91 = '$k91',

pp600 = '$kk600',
pp601 = '$kk601',
pp602 = '$kk602',
pp603 = '$kk603',
pp604 = '$kk604',
pp605 = '$kk605',
pp606 = '$kk606',
pp607 = '$kk607',
pp608 = '$kk608',
pp609 = '$kk609',
pp610 = '$kk610',
pp611 = '$kk611',
pp612 = '$kk612',
pp613 = '$kk613',
pp614 = '$kk614',
pp615 = '$kk615',
pp616 = '$kk616',
pp617 = '$kk617',
pp618 = '$kk618',
pp619 = '$kk619',
pp620 = '$kk620',
pp530 = '$kk530',
pp531 = '$kk531',
pp532 = '$kk532',
p107 = '$k107',
p1000 = '$k1000',
p1001 = '$k1001',
p1003 = '$k1003',
p1002 = '$k1002',
p1004 = '$k1004',
p1005 = '$k1005',
p1006 = '$k1006',
p1007 = '$k1007',
p560 = '$k560',
p561 = '$k561',
p562 = '$k562',
p563 = '$k563',
p564 = '$k564',
pp100 = '$kk100',
pp101 = '$kk101',
pp102 = '$kk102',
pp103 = '$kk103',
pp104 = '$kk104',
pp105 = '$kk105',
pp106 = '$kk106',
pp107 = '$kk107',
pp108 = '$kk108',
pp109 = '$kk109',
pp110 = '$kk110',
pp111 = '$kk111',
pp112 = '$kk112',
pp113 = '$kk113',
pp114 = '$kk114',
pp115 = '$kk115',
pp116 = '$kk116',
pp117 = '$kk117',
pp118 = '$kk118',
pp119 = '$kk119',
pp120 = '$kk120',
pp121 = '$kk121',
pp122 = '$kk122',
pp123 = '$kk123',
pp124 = '$kk124',
pp125 = '$kk125',
pp126 = '$kk126',
pp127 = '$kk127' 


 		  
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


} //if then end
} //end of for loop
 

 

 
 


print '<p> COMPLETED </p>';
 
 



?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
