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
 

$db = mx_db_connect();
 

 
if ($dir = opendir("/home/medex/cmbtest")) {
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            echo "$file\n";
   
$dir1="/home/medex/cmbtest/";
$filename = $dir1.$file;
ini_set("auto_detect_line_endings", true);
$fh = fopen($filename, "rb");



 

$selstmt = <<<SQL
	select filenm from  cmbfile where filenm='$file'          
SQL;

 
print "<p>".$selstmt.'</p>';

  $rs2 = mx_db_fetch_all($db, $selstmt);

if (count($rs2)==0)
{
	echo " this file   does not exist" . "<br />\n";
       



 


 
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
 
 


 


 



 

while ($info =fgetcsv($fh,1024,"|")){

	$num = count($info);
//        print "num=".$num;

        $row++;
	$tuple =null;
        for ($c=0; $c < $num; $c++) {
           
        }



 

	if ($info[0]=='PID'){	
	$kno=$info[3];
		$wptname1=$info[5];
		$wptname=str_replace( "^","",$wptname1);
//04-20-2013

$stmt2 = <<<SQL
	select pt_no from  tbl_patient2  where pt_nm='$wptname'          
SQL;

 
// print "<p>".$stmt2.'</p>';

  $rs3 = mx_db_fetch_single($db, $stmt2);

 if ($rs3){
	
      
  	$kno=$rs3['pt_no'];

//	print $kno;
	}



	}

	if ($info[0]=='MSH'){	
	$wodate=$info[6];
// print "date".$wodate;
	}

	if ($info[0]=='OBX'){	

	$code=substr($info[3],0,5);
	 //     $pos1 = stripos($mystring1, $findme);
	$name1=substr($info[3],0,50);
$pos1 = stripos($name1, "^");
$name9= substr($name1,0,$pos1);
echo  $name9."<br />\n";
$code = $name9;
$name1=substr($info[3],$pos1,50);
        $name=str_replace( "'","",$name1);
	$name=str_replace( "^","",$name);
	echo $name . "<br />\n";
	$value1=$info[5];
 	$value=str_replace( "\\","",$value1);
	$wunit=$info[6];
	$wkijun1=$info[7];
 	$wkijo=$info[8];




$selstmt9 = <<<SQL
	select karteno from  test_resultwkcmb where testdate='$wodate' and ptname='$wptname' and komokucode='$code'          
SQL;

 
 echo $selstmt9 . "<br />\n";

  $rs9 = mx_db_fetch_all($db, $selstmt9);
 
if (count($rs9)==0)
{
print '<p > no data in wk</p>';

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

}

//OBX


    }

fclose($fh);

} //file inserted
else 
{
print '<p> '.'no insert'.'</p>';
//unlink($filename);
}




} //loop as long as more files


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
