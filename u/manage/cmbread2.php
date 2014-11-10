
<?php

/*
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
*/

//07-22-2014

function mx_db_fetch_all(&$db, $stmt)
{
    $sth = pg_query($db, $stmt);
    $result = array();
    if (!$sth) {
	    print "<!-- $stmt -->";
	    return $result;
    }
    while (1) {
	    $tuple = pg_fetch_array($sth, NULL, PGSQL_ASSOC);
	    if (!$tuple)
		    break;
	    $result[] = $tuple;
    }
    return $result;
}
// for osato need to change dbname

function mx_db_connect9() {
     
     
		    $hostspec = 'host=localhost';
	    
	    $_mx_db_db =
		    pg_connect("$hostspec".
			       " port=5432 dbname=medexdb5 user=medex");
	pg_set_client_encoding('euc-jp');
   
    return $_mx_db_db;
}

//
function mx_db_fetch_single(&$db, $stmt)
{
    $sth = pg_query($db, $stmt);
    if (!$sth) {
	    print "<!-- $stmt -->";
	    return NULL;
    }
    $result = pg_fetch_array($sth, NULL, PGSQL_ASSOC);
    if ($result) {
	if (pg_fetch_array($sth)) {
	    // error: what is our error handling convention?
	    return NULL;
	}
    }
    return $result;
}
//


$db = mx_db_connect9();


//this is for osato-clinic CMB

function fwrite_stream($fp, $string) {
	for ($written = 0; $written < strlen($string); $written += $fwrite) {
		$fwrite = fwrite($fp, substr($string, $written));
		if ($fwrite === false) {
			return $written;
		}
	}
	return $written;
}

 $ff=fopen("/home/medex/cmbfile","a");

$connection = ssh2_connect('63.240.71.180',5513);

//print_r($connection);
/*
$pub_key = file_get_contents('id_dsa.pub');
print "<pre>";
var_export($pub_key);
print "</pre>";
 

 
 
$prv_key = file_get_contents('id_dsa');
print "<pre>";
var_export($prv_key);
print "</pre>";
 
 */
 
 
if (ssh2_auth_pubkey_file($connection, 'cmb',
						  'id_dsa.pub',
						  'id_dsa','secret')) {
  echo "Public Key Authentication Successful"."<br />\n";
} else {
  die('Public Key Authentication Failed');
}

 

 
$sftp = ssh2_sftp($connection);
//print_r($sftp);
 

 
 $loop=1;
if ($dir = opendir("ssh2.sftp://$sftp/live/")) {
	while (($file = readdir($dir)) !== false) {
		if ($file != "." && $file != "..") {
           	print "$file\n";
		$loop++;
		$dir1="/live/";
		$filename = $dir1.$file;
		ini_set("auto_detect_line_endings", true);
 
		$filen=$file."\n";
		

//0827-2013

$selstmt = <<<SQL
	select filenm from  cmbfile1 where filenm='$file'          
SQL;

// print $selstmt."\n";

 
  $rs2 = mx_db_fetch_all($db, $selstmt);

if (count($rs2)==0)
{
	echo " this file   does not exist".$file . "<br />\n";


		
$istmt = <<<SQL
	insert into cmbfile1(filenm,insdate)   	values ('$file',current_date)
SQL;
 
if (pg_query($db, $istmt)){
// print '<p>inserted into comfile1 </p>';
}
else {
print '<p > DB access to cmbfile1 error</p>';
die;
}		
 
$stream = fopen("ssh2.sftp://$sftp/$filename", 'rb');
$data = "";
		while ($buf = fread($stream, 4096)) {
			$data .= $buf;
		}
		fclose($stream);
$topdata=substr($data,1,200)."\n";

// print $topdata;

 $ss=fwrite($ff,$filen.$topdata);
 
 
$fpp=fopen("/home/medex/cmbtest/$file",'wb');

$fff=fwrite_stream($fpp,$data);
fclose($fpp);
 
 
 

 } //no file in DB


}//if files

}//while opendir

}//if opedir
 

fclose($ff);

//*******************************************************
//from here, testcmb

//*************************************************

// get contents of a file into a string
if ($dir = opendir("/home/medex/cmbtest")) {
	while (($file = readdir($dir)) !== false) {
		if ($file != "." && $file != "..") {
           echo "$file\n";
   
$dir1="/home/medex/cmbtest/";
$filename = $dir1.$file;
ini_set("auto_detect_line_endings", true);
$fh = fopen($filename, "rb");



 

$selstmt = <<<SQL
	select filenm from  cmbfile where filenm='$file' limit 1         
SQL;

 


  $rs2 = mx_db_fetch_all($db, $selstmt);

if (count($rs2)==0)
{
	echo " this file   does not exist" . "<br />\n";




 


 
$istmt = <<<SQL
	insert into cmbfile(filenm)   	values ('$file')
SQL;
 
if (pg_query($db, $istmt)){

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
print $wptname."\n";

$stmt2 = <<<SQL
	select pt_no from  tbl_patient2  where pt_nm='$wptname'          
SQL;

 
// print "<p>".$stmt2.'</p>';

  $rs3 = mx_db_fetch_single($db, $stmt2);

 if ($rs3){
	

	$kno=$rs3['pt_no'];

	print $kno;
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
//echo  $name9."<br />\n";
	$code = $name9;
	$name1=substr($info[3],$pos1,50);
		$name=str_replace( "'","",$name1);
	$name=str_replace( "^","",$name);
//	echo $name . "<br />\n";
	$value1=$info[5];
	$value=str_replace( "\\","",$value1);
	$wunit=$info[6];
	$wkijun1=$info[7];
	$wkijo=$info[8];




$selstmt9 = <<<SQL
	select karteno from  test_resultwkcmb where testdate='$wodate' and ptname='$wptname' and komokucode='$code'          
SQL;

 
// echo $selstmt9 . "<br />\n";

  $rs9 = mx_db_fetch_all($db, $selstmt9);
 
if (count($rs9)==0)
{
//print '<p > no data in wk</p>';

		$stmt = <<<SQL
	insert into test_resultwkcmb(karteno, testdate,ptname,komokucode,kname,kekka,unit,kijun1,ijo,sw) 	values ('$kno','$wodate','$wptname','$code','$name','$value','$wunit','$wkijun1','$wkijo','0')
SQL;

//echo $stmt . "<br />\n";

if (pg_query($db, $stmt)){
//echo $info[1] . "<br />\n";
}
else {
print '<p > DB insert from  cmb file  error</p>';
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
print '<p> '.'This file exists:'.$filename.'</p>';
unlink($filename);
}




} //loop as long as more files


} //while 
closedir($dir);

print '<p> Inserting to workingDB is COMPLETED </p>';



}



 

?>


