<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT </title>
	</head>
	<body>
<a href="index.php?tab=1">gpback to main</a>


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

 


// if testdate >=orderdate, then reset sw to 0
$pwd=$_POST[pwd];
$qid=$_POST[empid];

echo "empid=".$qid."<br>";
 echo "password=".$pwd."<br>";
 $result = mx_db_fetch_single($db,"SELECT max(userid) FROM mx_authenticate");
      $uid = $result['max']+1;
  

     $qph = mx_db_sql_quote(mx_authenticate_hmac($qid.':'.$pwd));

$sql4='select "ID" from  "������Ģ"   where  "����ID"='."'"."$qid"."'";
$res4=mx_db_fetch_single($db,$sql4);
print $sql4;

if ($res4["ID"]>0)
{ 
print "qid exist";

 $sql="SELECT  count(username)  FROM mx_authenticate WHERE username = "."'".$qid."'";  

print $sql;
      $rs = mx_db_fetch_single($db,$sql);
if ( $rs['count']==0){
 
 
     $sql0="INSERT INTO mx_authenticate (userid, username, passhash)
              VALUES ( $uid ,'$qid',$qph)"; 

print $sql0;
     $res=pg_query($db,$sql0);
if (!$res){
print "error: insert password error";
}

 

 
/* 
$sql="SELECT userid FROM mx_authenticate WHERE username = "."'".$qid."'";  
print $sql;

      $rs = mx_db_fetch_single($db,$sql);
 
*/


 
 echo "userid=".$uid."<br>";
 
 

$sql2='UPDATE "������Ģ" SET "userid"='.$uid.' where  "����ID"='."'"."$qid"."'";
$res2=pg_query($db,$sql2);
 

if (!$res2){
print $sql2;
}

}

else {
echo "existed userid, no insert "."<br>";
}

}
else {


echo "does not exist empID=".$qid."<br>";

}
print '<p> password set COMPLETED </p>';



?>


<a href="index.php?tab=1">�ᥤ������</a>
<br>

	</tbody>
</table>

	</body>
</html>
