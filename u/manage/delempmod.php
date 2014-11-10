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

 
$empno=$_POST[empno];
 
echo "empno=".$empno."<br>";
 
 

/*
INSERT INTO modalities(

           id, "name", rtype )

values (86,'伊澤 一也',904);


SELECT "ID", "ObjectID", "Superseded", "CreatedBy", userid, "職員ID", 
       "職種", "職位", "部署", "姓", "名", "フリガナ", "性別", "生年月日", "住所0", "住所1", 
       "住所2", "住所3", "住所4", "加入電話", "携帯電話", "LaboSystemCode", shijii
  FROM "職員台帳";
 */
 $stmt10 = <<<SQL
SELECT "ID" FROM "職員台帳" where "職員ID"='$empno' and "Superseded" is null
SQL;

  print $stmt10."\n";

 
  $rs = mx_db_fetch_single($db, $stmt10);
if ($rs != null){
 

 $empid=$rs["ID"];
 print $empid."EMPID";

$stmt2 = <<<SQL
delete from  modalities_to_medex_employee where 

     "employee"=$empid
SQL;

if (pg_query($db, $stmt2)){
 print $stmt2;
}
else {
print '<p > DB access error</p>';
die;
}
}
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
