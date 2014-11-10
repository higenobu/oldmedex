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
         print $info[$c]."\n";  
        }



 

	if ($info[0]=='PID'){
	print $info[1]."\n";
	print $info[2]."\n";
	print $info[3]."\n";
	print $info[4]."\n";
	print $info[5]."\n";
print $info[6]."\n";
print $info[7]."\n";
print $info[8]."\n";
print $info[9]."\n";
	
	$kno=$info[3];
		$wptname1=$info[5];
		$wptname=str_replace( "^","",$wptname1);
//04-20-2013

 if ($wptname=='ARAYAKENICHI'){
print "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";
}



	}

	if ($info[0]=='MSH'){	
	$wodate=$info[6];
 	 print "date".$wodate;
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




 
 
 

	}

	//OBX

} //while read end
 

fclose($fh);

 } //if end
 



 


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
