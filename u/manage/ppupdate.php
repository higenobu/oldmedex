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
/*
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/common.php';
 
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
*/

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
$ptno=$_POST[ptno];

//echo "ptno=".$ptno."<br>";
//echo "ordate=".$ordate."<br>";

$stmt0 = <<<SQL
select * from attrlist

SQL;
 

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $ppp = array();
$attr = array();
$ii=0;
  foreach($rows as $row)
 {
  

	$attr[$ii]=$row['attrnm'];
//	print $attr[$ii]."\n";
	$ii++; 
 

}
//***********************
 $stmt5 = <<<SQL
SELECT patient 
       from otatest_order  where
	order_date>='$ordate'  and  "Superseded" is null
       order by patient
SQL;

	$rows5 =  mx_db_fetch_all($db, $stmt5);
  	$ptids = array();
	 
	$i=0;

  foreach($rows5 as $row5)
 {

  
if($ptid ==$row5['patient']){
//skip
  	 }
	else {
	$ptid=$row5['patient'];
	
	  
	$ptids[$i]=$ptid;
	 
	 $i++;

	}
}  //foreach end


 
//**********************


for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

	$ptid=$ptids[$k];

 
$stmt2 = <<<SQL
select  
              patient, 
            order_date  
               from otatest_order
	where patient=$ptid   and order_date>='$ordate'  and  "Superseded" is null order by order_date desc 
	 
          
SQL;

 
$rs2=array();

  $rs2 = mx_db_fetch_all($db, $stmt2);
 
//10-03-2012
if (count($rs2) > 0)
{
	echo "    ptid ".$ptid." exist" . "<br />\n";
	 


$topdate=$rs2[0]['order_date'];
//print "topdate=".$topdate."  ";

 

 

$stmt3 = <<<SQL
SELECT * FROM otatest_order

	where patient=$ptid   and order_date <CAST('$topdate' AS DATE) + CAST('-30 days' AS INTERVAL) and "Superseded" is null  order by order_date desc	 limit 1
          
SQL;
 
$rs3=array();
  $rs3 = mx_db_fetch_all($db, $stmt3);
 $i=0;
 
if (count($rs3)>0){
  echo "    ptid= ".$ptid." : ";
//past data exists
 $preodate=$rs3[0]['order_date'];
 
 echo "pastdate=".$preodate."<br />\n";
$update="";
$attrval=array();
$komma="";
for($i=0;$i<$ii;$i++){
$var=$attr[$i];
 

$attrval[$var]=$rs3[0][$var];
 
$val=$attrval[$var];
//
  //0821-2014
if (substr($var,0,4)=='ss50'|| substr($var,0,4)=='ss51' || substr($var,0,4)=='ss52'){
//print $var."=";
$kkvar='ss4'.substr($var,3);
$val=$attrval[$kkvar];
 if ($val !=''){
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";


}

}
//0821-2014
//
if (substr($var,0,2)=='pp'){
//print $var."=";
$kkvar='kk'.substr($var,2);
$val=$attrval[$kkvar];
 if ($val !=''){
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}

}
else if 
  (substr($var,0,1)=='p' ){

//print $var."=";
$kkvar='k'.substr($var,1);
if ( $kkvar != 'k103' && $kkvar != 'k104' && $kkvar != 'k106' && $kkvar != 'k1006' && $kkvar != 'k1007'){
//print $kkvar."=";
$val=$attrval[$kkvar];
//print $val."val";
 if ($val !=''){
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}
} 

}
 


}
 //

 $k101=$attrval['k101'];
$k100=$attrval['k100'];
if ($k101) {

 $attrval['k1007'] = sprintf("%.2f",$k101/($k100/100*$k100/100));
$riso=$k100/100*$k100/100*22;
$himando=($k101 - $riso);

$attrval['k1006'] = sprintf("%.2f",$himando/$riso*100);
}
 if ($attrval['k1006'] !=''){
$var='p1006';
$val=$attrval['k1006'];
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}
 
if ($attrval['k1007'] !=''){
$var='p1007';
$val=$attrval['k1007'];
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}
//hack 
/*
$var='p103';
$val=$attrval['k103'];
$update=$update."$komma".$var."="."'".$val."'";
*/



//haikatsu

 $k200=$attrval['k200'];
$k100=$attrval['k100'];
 
if ($k200) {
 $attrval['k106'] = sprintf("%.2f",$attrval['k203']/$k200*100);

		$pat1 = get_patient($ptid,false);
//print "sex".$pat1['性別'];
//print "bday ".$pat1["生年月日"];
		if ($pat1['性別'] == 'M'){
 
 //yosouhaikatu
		$attrval['k103']= sprintf("%.2f",(27.63-0.112*mx_calc_age($pat1["生年月日"]))*$k100);
 
		}
		else {		 
		$attrval['k103']= sprintf("%.2f",(21.78-0.101*mx_calc_age($pat1["生年月日"]))*$k100);
		}
 

$attrval['k104'] = sprintf("%.2f",$attrval['k200']/$attrval['k103']*100);
 }
 

if ($attrval['k103'] !=''){
$var='p103';
$val=$attrval['k103'];
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}
if ($attrval['k104'] !=''){
$var='p104';
$val=$attrval['k104'];
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}

if ($attrval['k106'] !=''){
$var='p106';
$val=$attrval['k106'];
$update=$update."$komma".$var."="."'".$val."'";
$komma=",";
}
 



//print $update;

 //update pp,p rattribute
//0506-2014
$stmt30 = <<<SQL
update otatest_order 
	set
preorderdate='$preodate',
$update
 
 
where patient=$ptid   and order_date >='$ordate' and "Superseded" is null 
          
SQL;
//echo $stmt30."<br />\n";

  $sh30=pg_query($db, $stmt30);  
if ($sh30){
print '<p>update </p>';
}
else {
print '<p > update error</p>';
die;
}

 }

 }

} //for loop end of ptids




?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
