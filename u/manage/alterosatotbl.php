<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>alter table otatest_order</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
 
 

$db = mx_db_connect();

 
 
 


 
 
$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="c51".$kk;
 


 $stmt1 = <<<SQL
alter table otatest_order
            alter $index type character varying
SQL;
 print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > DB access error</p>';
die;
}
}

$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="c50".$kk;
 


 $stmt1 = <<<SQL
alter table otatest_order
            alter $index type character varying 
SQL;
 print $stmt1;
if (pg_query($db, $stmt1)){
// print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
}
 

 
 $kk=0;
 for ($kk=0;$kk<3;$kk++)
{
 
 
$index="cc53".$kk;
 


 $stmt1 = <<<SQL
alter table otatest_order
            alter $index type character varying 
SQL;
 print $stmt1;
if (pg_query($db, $stmt1)){
 
}
else {
print '<p > DB access error</p>';
die;
}     
}
 



$kk=0;
 for ($kk=0;$kk<10;$kk++)
{
 
 
$index="cc40".$kk;
 


 $stmt1 = <<<SQL
alter table otatest_order
            alter $index type character varying 
SQL;
 print $stmt1;
if (pg_query($db, $stmt1)){
// print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
}

 
$kk=0;
 for ($kk=0;$kk<9;$kk++)
{
 
 
$index="cc41".$kk;
 


 $stmt1 = <<<SQL
alter table otatest_order
            alter $index type character varying 
SQL;
 print $stmt1;
if (pg_query($db, $stmt1)){
// print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
}
 

 

 
 


 

?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
