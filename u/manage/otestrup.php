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
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
/*

setlocale(LC_ALL, 'ja_JP.UTF-8');

setlocale(LC_ALL, 'ja_JP.EUC-JP');

setlocale(LC_ALL, 'ja_JP.Shift_JIS');
*/


$db = mx_db_connect();

// get contents of a file into a string
/*
$i=0;
if ($dir1 = opendir("/home/medex/cmb1/")) {
    while (($file = readdir($dir1)) !== false and $i<2) {
 	$i++;
        if ($file != "." && $file != "..") {
            
 	$dir="/home/medex/cmb/";
	$filename = $dir.$file;
	print $filename;
*/

$dir="/home/medex/";
$filename = $dir."testcmb.csv";
	$fh = fopen($filename, "rb");

 



	$row=0;
 

while ($info =fgetcsv($fh,1024,"|")){




 	echo $info[0] . "<br />\n";
	echo $info[3] . "<br />\n";
	echo $info[5] . "<br />\n";
if ($info[0]=='PID'){	
	$kno=$info[3];
	$wptname=$info[5];

//


//select ptid
 $stmt = <<<SQL
SELECT pt_id from tbl_patient WHERE pt_no='$kno'
SQL;
  $sh=pg_query($db, $stmt);  
if ($sh){
print '<p>pt read </p>';
}
else {
print '<p > db error</p>';
die;
}
$rs = pg_fetch_array($sh);

    $rs = mx_db_fetch_single($db, $stmt);


	echo "patient-id:".$rs['pt_id'] . "<br />\n";

	$ptid=$rs['pt_id'];


}
	
 

// end PID


if ($info[0]=='MSH'){	
$wodate=substr($info[6],0,8);

}


if ($info[0]=='OBX'){	

	$code=substr($info[3],0,5);
	$name=substr($info[3],6,50);
	$value=$info[5];
	$wunit=$info[6];
	$wkijun1=$info[7];
		$stmt = <<<SQL
	insert into test_resultwkcmb(karteno, testdate,ptname,komokucode,kname,kekka,unit,kijun1) 	values ('$kno','$wodate','$wptname','$code','$name','$value','$wunit','$wkijun1')
SQL;

if (pg_query($db, $stmt)){
echo $info[1] . "<br />\n";
}
else {
print '<p > DB insert to cmb error</p>';
die;
}
//
//test 08-2012
$ptid=25;

$stmt1 = <<<SQL
 
INSERT INTO otestr("ID","ObjectID",
             patient, patientid, 
            plname, pfname, orderid, orderdate, resultdate, category, itemcode, 
            itemname, itemvalue1, itemunit, normalvalue)
values 
(nextval('otestr_id_seq'),
currval('otestr_id_seq'),
'$ptid',
'$kno',
'$wptname',
'$wptname',
'123',
'$wodate',
'$wodate','AAA','$code','$name','$value','$wunit','$wkijun1')
             
SQL;






echo $stmt1."<br />\n";

  $sh1=pg_query($db, $stmt1);  
if ($sh1){
print '<p>testr insert </p>';
}
else {
print '<p > testr insert error</p>';
die;
}
//



}

//OBX













} 
//while end file




 



fclose($fh);
/*
echo "$file\n";
$i++;

        }


 

// end while
    closedir($dir1);
}

//if end
*/




?>


<a href="index.php?tab=1">メインに戻る</a>
<br>

	</tbody>
</table>

	</body>
</html>
