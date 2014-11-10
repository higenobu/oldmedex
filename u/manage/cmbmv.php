<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>TEST RESULT INSERT from CSV to DB</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>


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
 

$db = mx_db_connect();

 //need to set mapping table;

$map['01180']='kk10';
$map['01330']='kk11';
$map['01590']='kk12';
$map['01530']='kk13';
//aa1 ,aa2 ,aa3 ,aa4 ,aa5 ,aa7 ,
$abmap['01180']='aa1';
$abmap['01330']='aa2';
$abmap['01590']='aa3';
$abmap['01530']='aa4';



// read otestr table 

//1111-2013?? missing where sw ='0'
 $stmt5 = <<<SQL
SELECT pt_id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient on pt_no=karteno order by (pt_id,testdate)
SQL;

$rows5 =  mx_db_fetch_all($db, $stmt5);
  $ptids = array();
$ordates = array();
$i=0;
  foreach($rows5 as $row5)
 {


 if($ptid ==$row5['pt_id'] && $ordate ==substr($row5['testdate'],0,8)){
  	 }
	else {
	$ptid=$row5['pt_id'];
	 $ordate=substr($row5['testdate'],0,8);
	$ptids[$i]=$row5['pt_id'];
	 $ordates[$i]=substr($row5['testdate'],0,8);
	 $i++;

	}

}  //foreach end



 for ($k=0,$num_pt=count($ptids);$k < $num_pt;$k++){

	$ptid=$ptids[$k];
	$ordate=$ordates[$k];
 
 $stmt0 = <<<SQL
SELECT pt_id,karteno,testdate,ptname,komokucode,kname,kekka,unit,kijun1,kijun2,ijo
       from test_resultwkcmb    join tbl_patient on pt_no=karteno    where pt_id=$ptid and substring(testdate,1,8)= '$ordate'           
SQL;

//read all records
 $rows =  mx_db_fetch_all($db, $stmt0);
  $itemv = array();
  $itemijo=array();
   
 

 

 }






print '<p> COMPLETED </p>';





?>


<a href="index.php?tab=1">�ᥤ������</a>
<br>

	</tbody>
</table>

	</body>
</html>
