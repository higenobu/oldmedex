<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>add emploee modality</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
 
 
 

$db = mx_db_connect();

 
$goi=$_POST[lname];
 
echo "goi=".$lname."<br>";
 
 
 

 
 $stmt10 = <<<SQL
SELECT  "ID" 
  FROM "語彙" where  "Superseded" is null order by "ID"   desc limit 1
SQL;
// print $stmt10;

 
  $rs = mx_db_fetch_single($db, $stmt10);
if ($rs != null){
 
 $nextid=$rs['ID']+1;
}

 
 $stmt11 = <<<SQL
SELECT  "ID" 
  FROM"語句群" where  "Superseded" is null order by "ID"   desc limit 1
SQL;


 
  $rs1 = mx_db_fetch_single($db, $stmt11);
if ($rs1 != null){
 
 $nextid2=$rs1['ID']+1;
}

 //
 $stmt1 = <<<SQL
INSERT INTO "語彙"("語彙","不使用", "ラベル", "ID", "ObjectID") values ('$goi','N','$goi',$nextid,$nextid) 
SQL;
if (pg_query($db, $stmt1)){
// print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
       

$stmt2 = <<<SQL
INSERT INTO "語句群"("語句群", "語彙","不使用","ID", "ObjectID") values (E'\nABC\nDEF\n',$nextid,'N',$nextid2,$nextid2) 

   
SQL;

if (pg_query($db, $stmt2)){
 
}
else {
print '<p > DB access error</p>';
die;
}


?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
