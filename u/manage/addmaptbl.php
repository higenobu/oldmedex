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

 
$aname=$_POST[aname];
$bname=$_POST[bname];
$para=$_POST[para];
$med=$_POST[med];
$aid=$_POST[aid];
$bid=$_POST[bid]; 
 

 
 
 

 
 

 $stmt1 = <<<SQL
INSERT INTO reportmap(
            attrnm, attrvar, attrclass, attrnmi, attrclassi, attrlabel)
    VALUES ('$aname', '$para', '$bname',$aid, $bid, '$med') 
SQL;
 
 print $stmt1;
if (pg_query($db, $stmt1)){

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
