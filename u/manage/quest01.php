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
//cmbtest2 1205-2012
// get contents of a file into a string
if ($dir = opendir("/home/medex/cmbtest")) {
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            echo "$file\n";
   
$dir1="/home/medex/cmbtest/";
$filename = $dir1.$file;
ini_set("auto_detect_line_endings", true);
$fh = fopen($filename, "rb");



 




 




while ($info =fgetcsv($fh,1024,"|")){

	$num = count($info);
//        print "num=".$num;

        $row++;
	$tuple =null;
        for ($c=0; $c < $num; $c++) {
           
        }




 
 	echo $info[0] . "<br />\n";
	echo $info[3] . "<br />\n";
	echo $info[5] . "<br />\n";
 

	if ($info[0]=='PID'){	
	$kno=$info[3];
		$wptname1=$info[5];
		$wptname=str_replace( "^","",$wptname1);
//04-20-2013




		}
	if ($info[0]=='MSH'){	
	$wodate=$info[6];
 
	}
	if ($info[0]=='OBX'){	
   $pos1=strpos($info[3],"^");
	$wkstr=substr($info[3],$pos1+1);
//	print $wkstr."\n";

	$pos2=strpos($wkstr,"^");
$wkname=substr($wkstr,0,$pos2);
print "00".$wkname."\n";
	$wkstr2=substr($wkstr,$pos2+1);
	print "AA".$wkstr2."\n";
	$pos3=strpos($wkstr2,"^");
	$wkstr3=substr($wkstr2,$pos3+1);
	print "BB".$wkstr3."\n";
	$pos4=strpos($wkstr3,"^");
	$wkstr4=substr($wkstr3,0,$pos4);
print "CC".$wkstr4."\n";
	$code=$wkstr4;
	$name1=$wkname;
        $name=str_replace( "'","",$name1);
	$name=str_replace( "^","",$name);
 
	$value1=$info[5];
 	$value=str_replace( "\\","",$value1);
	$wunit=$info[6];
	$wkijun1=$info[7];
 	$wkijo=$info[8];
 
		$stmt = <<<SQL
	insert into test_resultwkcmb(karteno, testdate,ptname,komokucode,kname,kekka,unit,kijun1,ijo,sw) 	values ('$kno','$wodate','$wptname','$code','$name','$value','$wunit','$wkijun1','$wkijo','0')
SQL;
//echo $stmt . "<br />\n";
if (pg_query($db, $stmt)){
//echo $info[1] . "<br />\n";
}
else {
print '<p > DB insert to cmb error</p>';
die;
}
 


}

//OBX


    }

fclose($fh);

} //file inserted

 


} //loop as long as more files



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
