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

/*

setlocale(LC_ALL, 'ja_JP.UTF-8');

setlocale(LC_ALL, 'ja_JP.EUC-JP');

setlocale(LC_ALL, 'ja_JP.Shift_JIS');
*/


$db = mx_db_connect();
//cmbtest2 1205-2012
// get contents of a file into a string

   
$dir1="/home/medex/drj/";
$file="csvfile.csv";
$filename = $dir1.$file;
ini_set("auto_detect_line_endings", true);
$fh = fopen($filename, "rb");



 





 



// setlocale(LC_ALL, 'ja_JP.EUC-JP');

while ($info =fgetcsv($fh,1024,"|")){

	$num = count($info);
//        print "num=".$num;

        $row++;
	$tuple =null;
        for ($c=0; $c < $num; $c++) {
           
        }




 
	
		$kno=$info[1];
		 
 
 

$stmt2 = <<<SQL
	select pt_no from  tbl_patient2  where pt_no='$kno'          
SQL;

 
print "<p>".$stmt2.'</p>';

  $rs3 = mx_db_fetch_single($db, $stmt2);

 if ($rs3){
	
      
  	$kno=$rs3['pt_no'];

	print $kno;
	}

else {
//insert header
}




$value2=$info[2];
$value3=$info[3];
$value4=$info[4];
$value5=$info[5];
$value6=$info[6];
$value7=$info[7];
$value8=$info[8];
	 
$value9=$info[9];
 $value10=$info[10];

$stmt = <<<SQL
	insert into  drjtest(recordid, 	bunruicode,dai,tyu,sho,koban,kanten,naiyou,sikencond,kitaichi,kakunin) 	
values 
SQL;

//echo $stmt . "<br />\n";
if (pg_query($db, $stmt)){
//echo $info[1] . "<br />\n";
}
else {
print '<p > DB insert to cmb error</p>';
die;
}
 








fclose($fh);

 


 


} //while 
closedir($dir);

print '<p> Inserting to DB is COMPLETED </p>';



}


 
 

?>


<a href="index.php?tab=1">go back to main</a>
<br>

	</tbody>
</table>

	</body>
</html>
