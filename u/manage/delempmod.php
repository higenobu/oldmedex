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

values (86,'��߷ ����',904);


SELECT "ID", "ObjectID", "Superseded", "CreatedBy", userid, "����ID", 
       "����", "����", "����", "��", "̾", "�եꥬ��", "����", "��ǯ����", "����0", "����1", 
       "����2", "����3", "����4", "��������", "��������", "LaboSystemCode", shijii
  FROM "������Ģ";
 */
 $stmt10 = <<<SQL
SELECT "ID" FROM "������Ģ" where "����ID"='$empno' and "Superseded" is null
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


<a href="index.php?tab=1">�ᥤ������</a>
<br>

	</tbody>
</table>

	</body>
</html>
