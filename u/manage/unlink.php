 
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
 

//$db = mx_db_connect();

 


/* 
$stmt1 = "select filenm from cmbfile ";
print $stmt1;
$result1 = mx_db_fetch_all($db, $stmt1);
foreach($result1 as $row){
  $filenm=$row['filenm'];
$dir1="/home/medex/cmbtest9/";
$filename = $dir1.$filenm;
echo $filename."<br>\n";
*/
$filename="/home/medex/cmbtest9/CMB_1305300267CP6_20130531113803.HL7";

print $filename;

unlink($filename);

//}





 




 

 



 



?>


 
