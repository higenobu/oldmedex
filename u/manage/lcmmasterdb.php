<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>


<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

 

 

// setlocale(LC_ALL, 'ja_JP.EUC-JP');

// setlocale(LC_ALL, 'ja_JP.Shift_JIS');
// setlocale(LC_ALL, 'ja_JP.UTF-8');


$db = mx_db_connect();

 
$dir="/home/medex/files/";
$filename = $dir."lcmmaster.csv";
$fh = fopen($filename, "rb");




 
while ($info =fgetcsv($fh,1024,";")){

	$num = count($info);
        print $num."\n";
        $row++;
	$tuple =null;
        for ($c=0; $c < $num; $c++) {
        print $info[$c]."<br />\n";
       		 }


	
 

if ($info[0]=='' & $info[1]=='' & $info[2]==''){

print "Error. no data";
}
else {

$stmt2 = <<<SQL
INSERT INTO "wktemp"(
           temp1,temp2,kubun)
    
 values ('$info[0]','$info[1]','$info[2]')
SQL;
print $stmt2;

if (pg_query($db, $stmt2)){
 print '<p>karte  created</p>';
}
else {
 print '<p >karte   error</p>';
die;
	}
} //else
 
    }


fclose($fh);

 

print '<p> COMPLETED </p>';




?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
