<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">goback to main</a>

<br>


<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
//1111-2013 add ijo
/*

setlocale(LC_ALL, 'ja_JP.UTF-8');

setlocale(LC_ALL, 'ja_JP.EUC-JP');

setlocale(LC_ALL, 'ja_JP.Shift_JIS');
*/


$db = mx_db_connect();
 
if ($dir = opendir("/home/medex/cmbtest")) {
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            echo "$file\n";
   
	$dir1="/home/medex/cmbtest/";
	$filename = $dir1.$file;
	ini_set("auto_detect_line_endings", true);
	$fh = fopen($filename, "rb");
/*

$istmt = <<<SQL
	insert into cmbfile(filenm)   	values ('$file')
SQL;
 
if (pg_query($db, $istmt)){
// print '<p>inserted </p>';
}
else {
print '<p > DB access error</p>';
die;
}
 */

 
 
$selstmt = <<<SQL
	select filenm from  cmbfile where filenm='$file'          
SQL;

 
 

  $rs2 = mx_db_fetch_all($db, $selstmt);

if ($rs2)
{
	echo " this file    exist" . "<br />\n";
       
unlink($filename);

}  
 

}

} //while 
closedir($dir);

print '<p>  COMPLETED </p>';



}


 
 

?>


<a href="index.php?tab=1">go back to main</a>
<br>

	</tbody>
</table>

	</body>
</html>
