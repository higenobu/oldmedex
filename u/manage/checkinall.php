<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>checkinall</title>
	</head>
 
 
<a href="index.php?tab=1">�ᥤ������</a>


<br>
 

<?php



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

 
 function zeropadmm($data, $width)
{
	$v = $data;


	if ($v != '' && mb_strlen($v) < $width) {
		$cnt = $width- strlen($v);
		for ($i = 0; $i < $cnt; $i++) {
			$v = "0".$v;
		}
		return $v;
	}
	return $data;
}


 $db = mx_db_connect();
 
$doctor=$_POST['yoyaku'];
//print $doctor;
 
while ($count <6){
$count++;

$iid='id'."$count";
 
$PID=$_POST["$iid"];
 $patientID=zeropadmm($PID,8);
if ($patientID=="") {$count=7;}
else {
 $id=mx_find_patient_by_patient_id($patientID);
 
print "___________________________________________________________\n"; 
 

//mx_draw_patientinfo_brief($id);
$stmt1 = <<<SQL
	 SELECT   "��" ,"̾" ,"�եꥬ��"  FROM "������Ģ"  WHERE "ObjectID" = $id  
		  AND "Superseded" IS NULL  
                
SQL;
//print $stmt1;


$result = mx_db_fetch_single($db, $stmt1); 
print $patientID."|";         
 $sei=$result["��"];
print $sei."| ";
 $mei=$result["̾"];
print $mei."| ";
 $furi=$result["�եꥬ��"];
print $furi."\n";

 

 
	$stmt = <<<SQL
	  INSERT INTO "���մ���ɽ"(
              "����", "����ID", "���ջ���", 
              "ͽ����", "��", "̾", "�եꥬ��" )
     	values ('$id','$patientID',now(),'$doctor','$sei','$mei','$furi')
SQL;
// print $stmt;

if (pg_query($db, $stmt)){
//		print $stmt;
	}
else 	{
	print 'error';
	die;
	}
 }

 


}

 


 

 
 



 


 
 

?>


 
