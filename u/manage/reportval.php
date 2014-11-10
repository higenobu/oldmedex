<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>add value to report</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
 
 

$db = mx_db_connect();

 
$ptid=$_POST[ptid];
$rdate=$_POST[rdate];
 //0410-2014 read data where  A."記録日">'$rdate'
 
 //JOIN "帳票属性データ" AS D ON D."帳票属性" = A."ObjectID"??? "ID"??
 

 
 $stmt10 = <<<SQL
SELECT pt_id FROM tbl_patient where pt_no='$ptid'
SQL;

 print $stmt10."\n";

 
  $rs = mx_db_fetch_single($db, $stmt10);
if ($rs != null){
$ptoid=$rs['pt_id'];


 $stmt0 = <<<SQL
SELECT M."グループ", M."名称", D."属性" as zoku,D."属性値" as zokuval, D."帳票属性" as chohyo, A."記録日", A."患者",

    attrlabel,attrvar,attrnm

FROM "帳票属性" AS A

JOIN "帳票属性データ" AS D ON D."帳票属性" = A."ObjectID"

JOIN "患者属性一覧" AS M ON M."ID" = D."属性"

JOIN reportmap AS L ON  L.attrnmi= D."属性" 

where  A."患者"=$ptoid and A."Superseded" is null and A."記録日">= '$rdate'
SQL;

print $stmt0."\n";

 
$rows5 = mx_db_fetch_all($db, $stmt0);
 foreach($rows5 as $row5)
 {     
//print $row5[attrvar]."\n";
$varname=$row5[attrvar];
 $attrval=$row5[attrlabel];
 $attrnm=$row5[attrnm];
$zokuval=$row5[zokuval];
 $chohyo=$row5[chohyo];
//$attrval2="(".$zokuval.")".$attrval;
 
if ($zokuval !='None' && $zokuval !='-' && $zokuval ){

$stmt3 = <<<SQL
select   * from reportmap where  attrnm= '$attrnm'
SQL;
// print $stmt3;
  $rs3 = mx_db_fetch_single($db, $stmt3);
if ($rs3 != null){
$attrclassi=$rs3['attrclassi'];
//print $attrclassi."\n";
}
$stmt4 = <<<SQL
select * from "帳票属性データ" where "属性"=$attrclassi and "帳票属性"=$chohyo limit 1
SQL;
//print $stmt4; 
  $rs4 = mx_db_fetch_single($db, $stmt4);
if ($rs4 != null){
$classval=$rs4["属性値"];
 
}

$attrval2="(".$zokuval.")".$attrval.": ".$classval;

//
$stmt5 = <<<SQL
select $varname   from otatest_order  where patient=$ptoid and order_date>= '$rdate' and "Superseded" is null limit 1
SQL;
print $stmt5; 
  $rs5 = mx_db_fetch_single($db, $stmt5);
if ($rs5 != null){
$org=$rs5[ $varname];
 
$string=$org."\n".$attrval2;
print $string;
}
//
$stmt2 = <<<SQL
update  otatest_order set $varname='$string'

           where patient=$ptoid and order_date>= '$rdate' and "Superseded" is null
SQL;
//print $stmt2;
if (pg_query($db, $stmt2)){
print '<p > DB access success</p>';
}
else {
print '<p > DB access error</p>';
die;
}
 }

 


 


} //end foreach
} //end if
//else if
else {
print "Error: this employee is not in DB\n";
}


?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
