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

 


// read otestr table 

//0415-2014 add order by
 $stmt5 = <<<SQL
SELECT pt_id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient on pt_no=karteno order by (pt_id,testdate)
SQL;

	$rows5 =  mx_db_fetch_all($db, $stmt5);
  	$ptids = array();
	$ordates = array();
	$i=0;
  foreach($rows5 as $row5)
 {

 
 if($ptid ==$row5['pt_id'] && $ordate ==substr($row5['testdate'],0,8)){
  	 }
	else {
	$ptid=$row5['pt_id'];
	 $ordate=substr($row5['testdate'],0,8);
	$ptids[$i]=$row5['pt_id'];
	 $ordates[$i]=substr($row5['testdate'],0,8);
	 $i++;
$row['komokucode']
	}
}  //foreach end

	 
 /*

 for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

	$ptid=$ptids[$k];
	$ordate=$ordates[$k];

 $stmt0 = <<<SQL
SELECT pt_id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient on pt_no=karteno    where pt_id=$ptid and substring(testdate,1,8)= '$ordate'           
SQL;

print $stmt0;
 $rows =  mx_db_fetch_all($db, $stmt0);
  $itemv = array();
  $itemijo=array();
$updatestr="";
  foreach($rows as $row)
 {
 
	$ptid=$row['id']; 
	$itemv[$row['komokucode']]=$row['kekka'];
// echo $row['komokucode']."=".$itemv[$row['komokucode']]."=".$row['kname']."=".$row['unit']."<br>";
	 $var=$map[$row['komokucode']];
//print $var."=var\n";
	$val=$row['kekka'];
	if ($row['ijo']=='H'|$row['ijo']=='L') {
			$row['ijo']='1';
		}
		else { 
			$row['ijo']='0';
			}
 	print $row['komokucode']."+".$row['ijo']."\n";
	$itemijo[$row['komokucode']]=$row['ijo'];
	$varab=$abmap[$row['komokucode']];
	$valab= $row['ijo'];
if ($var !="") {
	$updatestr=$updatestr." $var="."'$val' ";}
if ($varab !="") {
	$updatestr=$updatestr." $varab="."'$valab' ";}



} //for each end

 

 
print $updatestr."\n";


 
*/



/*






$stmt2 = <<<SQL
select  
              patient, 
            order_date  
               from otatest_order
	where patient=$ptid   and order_date='$ordate'  and  "Superseded" is null 
	 
          
SQL;

echo $stmt2."<br />\n";
 
$rs2=array();

  $rs2 = mx_db_fetch_all($db, $stmt2);
 
//10-03-2012
if (count($rs2)==0)
{



$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,
kk10 ,kk11 ,kk12 ,kk13 ,kk14 ,kk21 ,kk30 ,kk35 ,kk31 ,
kk36 ,kk32 ,kk37 ,kk33 ,kk38 ,kk34 ,kk39 ,kk40 ,kk41 ,
kk50 ,kk51 ,kk52 ,kk64 ,kk65 ,kk66 ,kk67 ,kk53 ,kk54 ,

kk55 ,kk56 ,kk57 ,kk58,kk59,kk60 ,kk61,kk62 ,kk71 ,kk72 ,kk73 ,kk74 ,
kk76 ,kk77 ,kk78 ,kk79 ,kk92 ,kk93 ,kk94 ,kk95 ,
kk600 ,kk601 ,kk602 ,kk603 ,kk604 ,kk605 ,kk606 ,
kk607 ,kk608 ,kk609 ,kk610 ,kk611 ,kk612 ,k1003,k1004,
                      
pp0,
pp1,
pp2,
pp3,
pp4,
pp5,
pp6,
pp7,
pp8,
pp9,
pp10,
pp11,
pp12,
pp13,
pp14,
pp15,
pp20,
pp21,
pp22,
pp40,
pp41,
pp42,
pp50,
pp51,
pp52,
pp53,
pp54,
pp55,
pp56,
pp57,
pp58,
pp59,
pp30,
pp31,
pp32,
pp33,
pp34,
pp35,
pp36,
pp37,
pp38,
pp39,
pp60,
pp61,
pp62,
pp63,
pp64,
pp65,
pp66,
pp67,
pp70,
pp71,
pp72,
pp73,
pp74,
pp75,
pp76,
pp77,
pp78,
pp79,
pp90,
pp91,
pp92,
pp93,
pp94,
pp95,
p100,
p101,
p102,
p103,
p104,
p105,
p106,
p200,
p201,
p202,
p203,
p204,
p205,
p206,
p207,
p208,
p300,
p301,
p302,
p500,
p501,
p502,
p503,
p504,
p505,
p506,
p507,
p508,
p509,
p510,
p511,
p512,
p513,
p514,
p515,
p516,
p517,
p518,
p519,
p520,
p80,
p81,
p82,
p83,
p84,
p85,
p86,
p87,
p88,
p89,
p90,
p91,
pp600,
pp601,
pp602,
pp603,
pp604,
pp605,
pp606,
pp607,
pp608,
pp609,
pp610,
pp611,
pp612,
pp613,
pp614,
pp615,
pp616,
pp617,
pp618,
pp619,
pp620,
p1003,
p1004,
aa1 ,aa2 ,aa3 ,aa4 ,aa5 ,aa7 ,aa8 ,aa13 ,aa9 ,
aa14 ,aa10 ,aa15 ,aa11 ,aa16 ,aa12 ,aa17 ,aa18 ,aa19 ,
aa20 ,aa21 ,aa22 ,aa23 ,aa24 ,aa25 ,aa26 ,aa27 ,aa28 ,

aa29 ,aa30 ,aa35 ,aa36,aa37,aa31 ,aa32,aa33 ,aa38 ,aa39 ,aa40 ,aa41 ,
aa42 ,aa43 ,aa44 ,aa45 ,aa46 ,aa47 ,aa48 ,aa49 

 
)
values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),
$ptid,'$ordate',  
'$itemv[01180]','$itemv[01330]','$itemv[01590]','$itemv[01530]','$itemv[01392]',
'$itemv[01070]',
'$itemv[01470]','$itemv[01230]','$itemv[01040]','$itemv[01370]','$itemv[80053]',
'$itemv[01090]','$itemv[01490]','$itemv[01060]','$itemv[01500]','$itemv[07524]',
'$itemv[07520]','$itemv[07530]','$itemv[01250]','$itemv[80010]','$itemv[02300]',
'$itemv[80008]','','$itemv[80012]','$itemv[80018]','$itemv[01510]',
'$itemv[01440]',

'$itemv[01160]','$itemv[01190]','$itemv[01130]',
'$itemv[01210]','$itemv[01560]',

'$itemv[80020]',
'$itemv[80022]',
'$itemv[80026]',
'$itemv[00508]','$itemv[00506]','$itemv[00502]','$itemv[00504]',
'$itemv[80050]','$itemv[80052]','$itemv[80054]','$itemv[00510]','$itemv[02089]',
'$itemv[02080]','$itemv[07420]','$itemv[02087]','$itemv[80056]','$itemv[80058]',
'$itemv[80090]','$itemv[00511]','$itemv[01140]','$itemv[01210]','$itemv[01560]',
'$itemv[01420]','$itemv[80062]','$itemv[80064]','$itemv[80066]','$itemv[80068]',
'$itemv[80070]',
'$itemv[01340]',
'$itemv[00502]',
'$pp0',
'$pp1',
'$pp2',
'$pp3',
'$pp4',
'$pp5',
'$pp6',
'$pp7',
'$pp8',
'$pp9',
'$pp10',
'$pp11',
'$pp12',
'$pp13',
'$pp14',
'$pp15',
'$pp20',
'$pp21',
'$pp22',
'$pp40',
'$pp41',
'$pp42',
'$pp50',
'$pp51',
'$pp52',
'$pp53',
'$pp54',
'$pp55',
'$pp56',
'$pp57',
'$pp58',
'$pp59',
'$pp30',
'$pp31',
'$pp32',
'$pp33',
'$pp34',
'$pp35',
'$pp36',
'$pp37',
'$pp38',
'$pp39',
'$pp60',
'$pp61',
'$pp62',
'$pp63',
'$pp64',
'$pp65',
'$pp66',
'$pp67',
'$pp70',
'$pp71',
'$pp72',
'$pp73',
'$pp74',
'$pp75',
'$pp76',
'$pp77',
'$pp78',
'$pp79',
'$pp90',
'$pp91',
'$pp92',
'$pp93',
'$pp94',
'$pp95',
'$p100',
'$p101',
'$p102',
'$p103',
'$p104',
'$p105',
'$p106',
'$p200',
'$p201',
'$p202',
'$p203',
'$p204',
'$p205',
'$p206',
'$p207',
'$p208',
'$p300',
'$p301',
'$p302',
'$p500',
'$p501',
'$p502',
'$p503',
'$p504',
'$p505',
'$p506',
'$p507',
'$p508',
'$p509',
'$p510',
'$p511',
'$p512',
'$p513',
'$p514',
'$p515',
'$p516',
'$p517',
'$p518',
'$p519',
'$p520',
'$p80',
'$p81',
'$p82',
'$p83',
'$p84',
'$p85',
'$p86',
'$p87',
'$p88',
'$p89',
'$p90',
'$p91',
'$pp600',
'$pp601',
'$pp602',
'$pp603',
'$pp604',
'$pp605',
'$pp606',
'$pp607',
'$pp608',
'$pp609',
'$pp610',
'$pp611',
'$pp612',
'$pp613',
'$pp614',
'$pp615',
'$pp616',
'$pp617',
'$pp618',
'$pp619',
'$pp620',
'$p1003',
'$p1004',

'$itemijo[01180]','$itemijo[01330]','$itemijo[01590]','$itemijo[01530]','$itemijo[01392]',
'$itemijo[01070]',
'$itemijo[01470]','$itemijo[01230]','$itemijo[01040]','$itemijo[01370]','$itemijo[80053]',
'$itemijo[01090]','$itemijo[01490]','$itemijo[01060]','$itemijo[01500]','$itemijo[07524]',
'$itemijo[07520]','$itemijo[07530]','$itemijo[01250]','$itemijo[80010]','$itemijo[02300]',
'$itemijo[80008]','$itemijo[01250]','$itemijo[80012]','$itemijo[80018]','$itemijo[01510]',
'$itemijo[01440]',

'$itemijo[01160]','$itemijo[01190]','$itemijo[01130]',
'$itemijo[01210]','$itemijo[01560]',

'$itemijo[80020]',
'$itemijo[80022]',
'$itemijo[80026]',
'$itemijo[00508]','$itemijo[00506]','$itemijo[00502]','$itemijo[00504]',
'$itemijo[80050]','$itemijo[80052]','$itemijo[80054]','$itemijo[00510]','$itemijo[02089]',
'$itemijo[02080]','$itemijo[07420]','$itemijo[02087]'



)




          
SQL;
 
print $stmt."\n";

 
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






 
 




//start existing recod case start
 
/*
//exists order of pid and orderdate record already
print '<p>Warning: the same pid and orderdate  record exists </p>';

print $updatestr."\n";

$stmt1=<<<SQL
update otatest_order
set $updatestr
where patient=$ptid   and order_date>='$ordate'  and  "Superseded" is null            

SQL;
 print $stmt1."\n";

 
pg_query($db, "BEGIN WORK");

 
$res=pg_query($db, $stmt1);

 
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
