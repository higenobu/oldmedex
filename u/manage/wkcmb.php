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
$ordate=$_POST[kenshindate];
$qid=$_POST[ptname];

echo "empid=".$ptname."<br>";
 
//$qid = mx_db_sql_quote($this->data['職員ID']);
     $qph = mx_db_sql_quote(mx_authenticate_hmac($qid . ':' .
 						 "11111111"));
print $qid;
print $qph;

      $result = mx_db_fetch_single($db,"SELECT max(userid) FROM mx_authenticate");
      $uid = $result['max']+1;
       print $uid;

      pg_query($db,"INSERT INTO mx_authenticate (userid, username, passhash)
              VALUES ($uid, $qid, $qph)");
 

      $result = mx_db_fetch_single($db,"SELECT userid FROM mx_authenticate
                                    WHERE username = $qid AND
                                    passhash = $qph");
 
print $result['userid'];

}




 




 

 



print '<p> COMPLETED </p>';



?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
