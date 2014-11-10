<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>from CMB server into our server</title>
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
//            	print "$file\n";
   $loop++;
		$dir1="/live/";
		$filename = $dir1.$file;
		ini_set("auto_detect_line_endings", true);
 
		$filen=$file."\n";
		

//0827-2013

$selstmt = <<<SQL
	select filenm from  cmbfile1 where filenm='$file'          
SQL;

 
 
  $rs2 = mx_db_fetch_single($db, $selstmt);

if (!$rs2)
{
//	echo " this file   does not exist" . "<br />\n";
       

		
$istmt = <<<SQL
	insert into cmbfile1(filenm,insdate)   	values ('$file',current_date)
SQL;
 
if (pg_query($db, $istmt)){
//print '<p>inserted </p>';
}
else {
print '<p > DB access error</p>';
die;
}		
 
 //0827-2013
		




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
fclose($fp);
 
 
 

 } //sql if

}//if files
}//while opendir

}//if opedir
 

fclose($ff);


 
 

?>

<a href="index.php?tab=1">go back to main</a>
<br>

	</tbody>
</table>

	</body>
</html>

