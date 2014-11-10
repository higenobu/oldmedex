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

 

$db = mx_db_connect();
 
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
        print "num=".$num;

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
 
  $selstmt = <<<SQL
	select pt_no from  tbl_patient2  where pt_nm='$wptname'          
SQL;

 
print "<p>".$selstmt.'</p>';

  $rs2 = mx_db_fetch_single($db, $selstmt);

 
	
      
  $ptname=$rs2['pt_no'];

print $ptname;


 
	 
 }

 
	if ($info[0]=='MSH'){	
	$wodate=$info[6];
 print "date".$wodate;
	}
	if ($info[0]=='OBX'){	

	$code=substr($info[3],0,5);
	$name1=substr($info[3],6,50);
        $name=str_replace( "'","",$name1);
	echo $name . "<br />\n";
	$value1=$info[5];
 $value=str_replace( "\\","",$value1);
	$wunit=$info[6];
	$wkijun1=$info[7];
 

	}
 


//OBX


    } //while end

fclose($fh);

 
 


} //loop as long as more files


}
closedir($dir);

print '<p> COMPLETED </p>';



}


 
 

?>


<a href="index.php?tab=1">gpback to main</a>
<br>

	</tbody>
</table>

	</body>
</html>
