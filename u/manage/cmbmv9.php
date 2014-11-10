<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT </title>
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

 


 
$ordate=$_POST[kenshindate];
$ptno=$_POST[ptno];

echo "ptno=".$ptno."<br>";

//$ordate='2013-02-22';
//$ptid=29;
$stmt0 = <<<SQL
SELECT  id from  tbl_patient   where pt_no='$ptno'  
SQL;
print $stmt0;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $ppp = array();
  foreach($rows as $row)
 {
 
$ptid=$row['id'];
echo "ID".$row['id']."<br>";
 

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
 
//10-03-2012
if (count($rs2)==0)
{
	echo " same pid and orderdate record does not exist" . "<br />\n";
	 

// set values of most recent test-values.

$stmt3 = <<<SQL
SELECT   patient,   
       order_date,   
        kk0, ss0, cc0, kk1, ss1, cc1, kk2, ss2, cc2, kk3, ss3, 
       cc3, kk4, ss4, cc4, pp0, pp1, pp2, pp3, pp4, kk5, pp5, cc5, ss5, 
       kk6, pp6, ss6, cc6, kk7, pp7, ss7, cc7, kk8, pp8, ss8, cc8, kk9, 
       pp9, cc9, ss9, kk10, pp10, ss10, cc10, kk11, pp11, ss11, cc11, 
       kk12, pp12, ss12, cc12, kk13, pp13, ss13, cc13, kk14, pp14, ss14, 
       cc14, kk15, pp15, ss15, cc15, kk20, pp20, ss20, cc20, kk21, pp21, 
       ss21, cc21, kk22, pp22, ss22, cc22, kk40, pp40, ss40, cc40, kk41, 
       pp41, ss41, cc41, kk42, pp42, ss42, cc42, kk50, pp50, ss50, cc50, 
       kk51, pp51, ss51, cc51, kk52, pp52, ss52, cc52, kk53, pp53, ss53, 
       cc53, kk54, pp54, ss54, cc54, kk55, pp55, ss55, cc55, kk56, pp56, 
       ss56, cc56, kk57, pp57, ss57, cc57, kk58, pp58, ss58, cc58, kk59, 
       pp59, ss59, cc59, kk30, pp30, ss30, cc30, kk31, pp31, ss31, cc31, 
       kk32, pp32, ss32, cc32, kk33, pp33, ss33, cc33, kk34, pp34, ss34, 
       cc34, kk35, pp35, ss35, cc35, kk36, pp36, ss36, cc36, kk37, pp37, 
       ss37, cc37, kk38, pp38, ss38, cc38, kk39, pp39, ss39, cc39, kk60, 
       pp60, ss60, cc60, kk61, pp61, ss61, cc61, kk62, pp62, ss62, cc62, 
       kk63, pp63, ss63, cc63, kk64, pp64, ss64, cc64, kk65, pp65, ss65, 
       cc65, kk66, pp66, ss66, cc66, kk67, pp67, ss67, cc67, kk70, pp70, 
       ss70, cc70, kk71, pp71, ss71, cc71, kk72, pp72, ss72, cc72, kk73, 
       pp73, ss73, cc73, kk74, pp74, ss74, cc74, kk75, pp75, ss75, cc75, 
       kk76, pp76, ss76, cc76, kk77, pp77, ss77, cc77, kk78, pp78, ss78, 
       cc78, kk79, pp79, ss79, cc79, kk90, pp90, ss90, cc90, kk91, pp91, 
       ss91, cc91, kk92, pp92, ss92, cc92, kk93, pp93, ss93, cc93, kk94, 
       pp94, ss94, cc94, kk95, pp95, ss95, cc95, k100, k101, k102, k103, 
       k104, k105, k106, p100, p101, p102, p103, p104, p105, p106, k200, 
       k201, k202, k203, k204, k205, k206, k207, k208, p200, p201, p202, 
       p203, p204, p205, p206, p207, p208, k300, k301, k302, p300, p301, 
       p302, k500, k501, k502, k503, k504, k505, k506, k507, k508, k509, 
       k510, k511, k512, k513, k514, k515, k516, k517, k518, k519, k520, 
       p500, p501, p502, p503, p504, p505, p506, p507, p508, p509, p510, 
       p511, p512, p513, p514, p515, p516, p517, p518, p519, p520, c500, 
       c501, c502, c503, c504, c505, c506, c507, c508, c509, c510, c511, 
       c512, c513, c514, c515, c516, c517, c518, c519, c520, k80, k81, 
       k82, k83, k84, k85, k86, k87, k88, k89, k90, k91, p80, p81, p82, 
       p83, p84, p85, p86, p87, p88, p89, p90, p91, k400, k401, k402, 
       k403, k404, k405, k406, k407, k408, k409, k410, k411, k412, k413, 
       k414, k415, plandate, kk600, pp600, kk601, pp601, kk602, pp602, 
       kk603, pp603, kk604, pp604, kk605, pp605, kk606, pp606, kk607, 
       pp607, kk608, pp608, kk609, pp609, kk610, pp610, kk611, pp611, 
       kk612, pp612, kk613, pp613, kk614, pp614, kk615, pp615, kk616, 
       pp616, kk617, pp617, kk618, pp618, kk619, pp619, kk620, pp620, 
       aa1, aa2, aa3, aa4, aa5, aa6, aa7, aa8, aa9, aa10, aa11, aa12, 
       aa13, aa14, aa15, aa16, aa17, aa18, aa19, aa20, aa21, aa22, aa23, 
       aa24, aa25, aa26, aa27, aa28, aa29, aa30, aa31, aa32, aa33, aa34, 
       aa35, aa36, aa37, aa38, aa39, aa40, aa41, aa42, aa43, aa44, aa45, 
       aa46, aa47, aa48, aa49, aa50, cc16, cc17, cc18, cc19
   
               from otatest_order
	where patient=$ptid   and order_date <'$ordate' and "Superseded" is null order by order_date desc
	 
          
SQL;

echo $stmt3."<br />\n";
//all 
$rs3=array();
  $rs3 = mx_db_fetch_all($db, $stmt3);

 $preodate=$rs3[0]['order_date'];
 echo $preodate."<br />\n";
 
$pp0=$rs3[0]['kk0'];
$pp1=$rs3[0]['kk1'];
$pp2=$rs3[0]['kk2'];
$pp3=$rs3[0]['kk3'];
$pp4=$rs3[0]['kk4'];
$pp5=$rs3[0]['kk5'];
$pp6=$rs3[0]['kk6'];
$pp7=$rs3[0]['kk7'];
$pp8=$rs3[0]['kk8'];
$pp9=$rs3[0]['kk9'];
$pp10=$rs3[0]['kk10'];
$pp11=$rs3[0]['kk11'];
$pp12=$rs3[0]['kk12'];
$pp13=$rs3[0]['kk13'];
$pp14=$rs3[0]['kk14'];
$pp15=$rs3[0]['kk15'];
$pp20=$rs3[0]['kk20'];
$pp21=$rs3[0]['kk21'];
$pp22=$rs3[0]['kk22'];
$pp40=$rs3[0]['kk40'];
$pp41=$rs3[0]['kk41'];
$pp42=$rs3[0]['kk42'];
$pp50=$rs3[0]['kk50'];
$pp51=$rs3[0]['kk51'];
$pp52=$rs3[0]['kk52'];
$pp53=$rs3[0]['kk53'];
$pp54=$rs3[0]['kk54'];
$pp55=$rs3[0]['kk55'];
$pp56=$rs3[0]['kk56'];
$pp57=$rs3[0]['kk57'];
$pp58=$rs3[0]['kk58'];
$pp59=$rs3[0]['kk59'];
$pp30=$rs3[0]['kk30'];
$pp31=$rs3[0]['kk31'];
$pp32=$rs3[0]['kk32'];
$pp33=$rs3[0]['kk33'];
$pp34=$rs3[0]['kk34'];
$pp35=$rs3[0]['kk35'];
$pp36=$rs3[0]['kk36'];
$pp37=$rs3[0]['kk37'];
$pp38=$rs3[0]['kk38'];
$pp39=$rs3[0]['kk39'];
$pp60=$rs3[0]['kk60'];
$pp61=$rs3[0]['kk61'];
$pp62=$rs3[0]['kk62'];
$pp63=$rs3[0]['kk63'];
$pp64=$rs3[0]['kk64'];
$pp65=$rs3[0]['kk65'];
$pp66=$rs3[0]['kk66'];
$pp67=$rs3[0]['kk67'];
$pp70=$rs3[0]['kk70'];
$pp71=$rs3[0]['kk71'];
$pp72=$rs3[0]['kk72'];
$pp73=$rs3[0]['kk73'];
$pp74=$rs3[0]['kk74'];
$pp75=$rs3[0]['kk75'];
$pp76=$rs3[0]['kk76'];
$pp77=$rs3[0]['kk77'];
$pp78=$rs3[0]['kk78'];
$pp79=$rs3[0]['kk79'];
$pp90=$rs3[0]['kk90'];
$pp91=$rs3[0]['kk91'];
$pp92=$rs3[0]['kk92'];
$pp93=$rs3[0]['kk93'];
$pp94=$rs3[0]['kk94'];
$pp95=$rs3[0]['kk95'];
$p100=$rs3[0]['k100'];
$p101=$rs3[0]['k101'];
$p102=$rs3[0]['k102'];
$p103=$rs3[0]['k103'];
$p104=$rs3[0]['k104'];
$p105=$rs3[0]['k105'];
$p106=$rs3[0]['k106'];
$p200=$rs3[0]['k200'];
$p201=$rs3[0]['k201'];
$p202=$rs3[0]['k202'];
$p203=$rs3[0]['k203'];
$p204=$rs3[0]['k204'];
$p205=$rs3[0]['k205'];
$p206=$rs3[0]['k206'];
$p207=$rs3[0]['k207'];
$p208=$rs3[0]['k208'];
$p300=$rs3[0]['k300'];
$p301=$rs3[0]['k301'];
$p302=$rs3[0]['k302'];
$p500=$rs3[0]['k500'];
$p501=$rs3[0]['k501'];
$p502=$rs3[0]['k502'];
$p503=$rs3[0]['k503'];
$p504=$rs3[0]['k504'];
$p505=$rs3[0]['k505'];
$p506=$rs3[0]['k506'];
$p507=$rs3[0]['k507'];
$p508=$rs3[0]['k508'];
$p509=$rs3[0]['k509'];
$p510=$rs3[0]['k510'];
$p511=$rs3[0]['k511'];
$p512=$rs3[0]['k512'];
$p513=$rs3[0]['k513'];
$p514=$rs3[0]['k514'];
$p515=$rs3[0]['k515'];
$p516=$rs3[0]['k516'];
$p517=$rs3[0]['k517'];
$p518=$rs3[0]['k518'];
$p519=$rs3[0]['k519'];
$p520=$rs3[0]['k520'];
$p80=$rs3[0]['k80'];
$p81=$rs3[0]['k81'];
$p82=$rs3[0]['k82'];
$p83=$rs3[0]['k83'];
$p84=$rs3[0]['k84'];
$p85=$rs3[0]['k85'];
$p86=$rs3[0]['k86'];
$p87=$rs3[0]['k87'];
$p88=$rs3[0]['k88'];
$p89=$rs3[0]['k89'];
$p90=$rs3[0]['k90'];
$p91=$rs3[0]['k91'];
$pp600=$rs3[0]['kk600'];
$pp601=$rs3[0]['kk601'];
$pp602=$rs3[0]['kk602'];
$pp603=$rs3[0]['kk603'];
$pp604=$rs3[0]['kk604'];
$pp605=$rs3[0]['kk605'];
$pp606=$rs3[0]['kk606'];
$pp607=$rs3[0]['kk607'];
$pp608=$rs3[0]['kk608'];
$pp609=$rs3[0]['kk609'];
$pp610=$rs3[0]['kk610'];
$pp611=$rs3[0]['kk611'];
$pp612=$rs3[0]['kk612'];
$pp613=$rs3[0]['kk613'];
$pp614=$rs3[0]['kk614'];
$pp615=$rs3[0]['kk615'];
$pp616=$rs3[0]['kk616'];
$pp617=$rs3[0]['kk617'];
$pp618=$rs3[0]['kk618'];
$pp619=$rs3[0]['kk619'];
$pp620=$rs3[0]['kk620'];

 if (count($rs3) ==0) {

 echo "  the first record"."<br />\n";
$stmt = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,

                      
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
pp620
 
)
values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),
$ptid,'$ordate', 


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
'$pp620'
)




          
SQL;
 
 

echo $stmt."<br />\n";
 
pg_query($db, "BEGIN WORK");

// Some insert/update/delete queries.
$res=pg_query($db, $stmt);

// Verify and end the transaction as appropriate.
if (!$res) {
    pg_query($db, "ROLLBACK");
} else {
    pg_query($db, "COMMIT");
} 

}
 
 


else {
//count($rs3)>0

echo "preorder record   exist"."<br />\n";
$stmt20 = <<<SQL
INSERT INTO otatest_order(
            "ID", "ObjectID", patient, order_date,preorderdate,

                      
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
pp620
 
)
values (nextval('otatest_order_id_seq'),currval('otatest_order_id_seq'),
$ptid,'$ordate','$preodate', 
 


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
'$pp620'
)


          
SQL;



 
echo $stmt20."<br />\n";

pg_query($db, "BEGIN WORK");

// Some insert/update/delete queries.
$res1=pg_query($db, $stmt20);

// Verify and end the transaction as appropriate.
if (!$res1) {
    pg_query($db, "ROLLBACK");
} else {
    pg_query($db, "COMMIT");
} 
 

} //end prerecord value set
 
 
 


} //first record case end

//start existing recod case start
 else {

//exists order of pid and orderdate record already
print '<p>the same pid and orderdate  record exists </p>';

 



} //else end (update)

 

 



print '<p> COMPLETED </p>';



?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
