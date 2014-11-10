<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>report writing</title>
	</head>
	<body>
<a href="index.php?tab=1">go back to main</a>

<a href="rxlist-app.php?tab=1">redo</a>
 

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
//zeropadd 8
 function zeropad($data)
{

$v=$data;
 $leng= strlen($data );
 $ksps="0";




	 
		$cnt = 6-$leng;
		for ($i = 0; $i < $cnt; $i++) {

			$v = $ksps.$v;
		}
		return $v;
	 
}


$con = mx_db_connect();




  
//pg_set_client_encoding('EUC_JP');
$ordate=$_POST['kenshindate'];
$ptno=$_POST['ptno'];
//$ptno=zeropad($ptno);
echo "ptno=".$ptno."<br>";
 
 
 
$kubun =$_POST['shiji'];
if ($kubun==null){$kubun="x";}

$cont0v=null;
 foreach ($_POST['phraze0'] as $names)
{
	$cont0v=$cont0v.$names."\n";
        print "You are selected $names<br/>";
}

$cont1v=null;
 foreach ($_POST['phraze1'] as $names)
{
	$cont1v=$cont1v.$names."\n";
        print "You are selected $names<br/>";
}
$cont2v=null;
 foreach ($_POST['phraze2'] as $names)
{
	$cont2v=$cont2v.$names."\n";
        print "You are selected $names<br/>";
}

$cont3v=null;
 foreach ($_POST['phraze3'] as $names)
{
	$cont0v=$cont3v.$names."\n";
        print "You are selected $names<br/>";
}

$cont="\n".$cont0v.$cont1v.$cont2v.$cont3v."\n";
 
 
$stmt0 = <<<SQL
SELECT  id ,pt_nm from  tbl_patient   where pt_no='$ptno'  
SQL;
 
print $stmt0;

 
 $d = mx_db_fetch_single($con, $stmt0);
  if (! is_null($d)) {
     $ptid=$d['id'];
 
  
   
 
 


 }
else {

echo "This ID is invalid"."<br>";
}
//ptid exits

if ($ptid!==null){ 
 
 
	echo "ID=".$ptid."<br>";
  	
 

	echo "orderdate=".$ordate."<br>";


 

$stmt3 = <<<SQL
SELECT      
"ID",   
order_date,   
special_req,notes
               from otatest_order
	where patient=$ptid   and order_date ='$ordate' and "Superseded" is null order by order_date desc
	 
          
SQL;

// echo $stmt3."<br />\n";

 $d1 = mx_db_fetch_single($con, $stmt3);

//order exits
  if (! is_null($d1)) {
      
 
 
 
	$lcmid=$d1["ID"];
 	$odate=$d1['order_date'];
	$pasts= $d1['special_req'];
	$pastn= $d1['notes'];
if ($kubun=='a'){
	
	$cont=$pasts.$cont;
echo "a:".$cont."<br>";
}
if ($kubun=='b'){
	
	$cont=$pastn.$cont;
echo "b:".$cont."<br>";
}



$stmt = <<<SQL
	insert into lcmrep("ID","ObjectID",lcmid,orderdate,ptid,kubun,cont1) 	values (nextval('lcmrep_id_seq'),nextval('lcmrep_id_seq'),'$lcmid','$odate','$ptid','$kubun',E'$cont')
SQL;
	print $stmt;
 
	if (pg_query($con, $stmt)){
// 	echo $cont. "<br />\n";
	}
	else {
	print '<p > DB insert to lcmtemp error</p>';
	die;
	}

	}
 
else {
	echo "This date deos not exist in DB"."<br>";
	}

 }

else {
echo "This ID is invalid"."<br>";
 	}

 //*******************************************************

 
 

?>

	</tbody>
     </table>

	</body>






</html>
