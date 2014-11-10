<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST </title>
	</head>
	<body>
<a href="index-pt.php?tab=1">メインに戻る</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

/*

$db = mx_db_connect();
	$stmt = <<<SQL
	insert into test_result(
   "TestOrder" ,
  
  "TestMaster",
  "value" ,
 
  decision ,
  
  state )

select '28', m."ID",  r.kekka , r.ijo, r.ijo from test_master m, test_resultwk1 r where trim (leading '0'from r.komokucode)=trim(trailing ' ' from m."LaboSystemCode")
SQL;
	$sth = pg_query($db, $stmt);
	

print "DONE";


/* データベース名の入力チェック
if ($_POST['dbname'] == "" || $_POST['table'] == "") {
	echo("<p><span style=\"color:red\">必須項目が入力されてません。</span></p>\n");
	echo("</body></html>\n");
	exit;
}


















$query ="select * from test_resultwk1";
$rs = pg_query($con, $query);
if (!$rs) {
  echo "An error occured.\n";
  exit;
}
print "query done!";


$query ="copy test_resultwk1 from '/home/medex/testr0526.csv' using delimiters ';';";
print $query;

$rs = pg_query($con, $query);

$status = pg_result_status($rs);

if ($status == PGSQL_COPY_IN)
   echo "Copy began.";
else
   echo "Copy failed.";
if (!$rs) {
  echo "An error occured.\n";
  exit;
}


print "DONE!";




$query= "insert into test_result("."""."TestOrder"."""." ,"."""."TestMaster".""".",".""".
  "value"."""." ,decision ,state )"."select '28', m."."""."ID".""".","." r.kekka , r.ijo, r.ijo from"."test_master m, test_resultwk1 r where trim (leading '0'from r.komokucode)=trim(trailing ' ' from m."."""."LaboSystemCode".""".")" ;



$rs = pg_query($con, $query);
if (!$rs) {
  echo "An error occured.\n";
  exit;
}
print "query done!";












*/


?>


	</tbody>
</table>

	</body>
</html>
