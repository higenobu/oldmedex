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
$db = mx_db_connect();

$delstmt="delete from test_resultwksol";
if (pg_query($db, $delstmt)){
print '<p>TEST WKDB  is deleted </p>';
}
else {
print '<p > DB access error</p>';
die;
}

// get contents of a file into a string
setlocale(LC_ALL, 'ja_JP.Shift_JIS');

$dir="/home/medex/";
$filename = $dir."DENFXX.31";
$fileData = file_get_contents($filename);




     $file=mb_convert_encoding($fileData, "EUC-JP","auto");
print "**********************************************************************";




 


 

$length=strlen($file);


print '<br>';

 


$fst=1;

$iii=0;

for ($i=0;$i*256<$length;$i++){


$rc1=mb_substr($file, $i*256,256);


	print $rc1;
	print '<br>';



if ($fst==1){
   $iii++;
	$fst=0;
	$cdate=mb_substr($rc1,0,6);
	$cnum=mb_substr($rc1,13,5);
	$pname=mb_substr($rc1,37,20);
	$orderid=mb_substr($rc1,59,10);
	$cont=mb_substr($rc1,24,1);
	$cdate=mb_substr($rc1,0,6);
	$cyy=substr($cdate,0,2);
	$cmd=substr($cdate,2,4);
	$cyyyy=$cyy+2000-11;
	$ymd=$cyyyy.$cmd;
	print $ymd;

	print $cont;
	
	print $cnum;

	print $pname;
	
	print $orderid;
	print "###".strlen($orderid);

	print'<br>';

print '<br>';
print "ID:";

print $orderid;
 
	print '<br>';
//0927-2011 add fro test 
$orderid="00001031";
//debug

$db = mx_db_connect();

$stmt="select  id from tbl_patient where pt_no="."'".$orderid."'";
 


$res1 = pg_query($db, $stmt);

 


  if (pg_num_rows($res1) && 
      ($pat1 = pg_fetch_array($res1, PG_ASSOC)))
     pg_free_result($res1);
  else
     $pat1 = FALSE;
//

$pt_id=$pat1['id'];
//

print '<br>';
print "Again PTID:";

print $pt_id;
 
	print '<br>';

// insert to clip_num of test-order
$rand = rand(0,100000000);
$stmt = <<<SQL
INSERT INTO test_order(
         "OrderDate", kekkadate, 
            "Patient", printer,clip_num) values 
    ('$ymd','$ymd',$pt_id,'1',$rand)
SQL;

if (pg_query($db, $stmt)){
print '<p>TEST order created</p>';
}
else {
print '<p > TEST order   error</p>';
die;
}
//insert 


	}

else{
	
	
	$cdate=mb_substr($rc1,0,6);
	$cyy=substr($cdate,0,2);
	$cmd=substr($cdate,2,4);
	$cyyyy=$cyy+2000-11;
	$ymd=$cyyyy.$cmd;
	 
	$pnum=mb_substr($rc1,13,5);
	 
	 $cont=mb_substr($rc1,24,1);
	print $cont."  continue??";
	print '<br>';

  for ($j=0; $j<9;$j++){
    	$bumon=mb_substr($rc1,6+25*$j,2);
 	$code=mb_substr($rc1,31+25*$j,3);
	$result=mb_substr($rc1,34+25*$j,8);
	$status=mb_substr($rc1,52+25*$j,1);
	$tcode=$bumon.$code;

	if ( $code !='   '){
	


	
	

	
	// print $pnum;
	
	print $tcode;
	
	print $result;
	print $status;

/* no need 0930-2011
	$stmt = <<<SQL
insert into test_resultwksol (karteno, testdate,ptname,komokucode,kekka,ijo,ptnum,ptid) values 		('$orderid','$ymd','$pname','$tcode','$result','$status','$orderid',$pt_id ) 
SQL;

	if (pg_query($db, $stmt)){
 
		}
	else {
	print '<p > TEST WK   error</p>';
	die;
		}
*/

//debug 444 0930-2011

	$stmt2 = <<<SQL
insert into test_result( "TestMaster","TestOrder" ,tcode,value ,decision ,state, ptid,kekkadate )
select 444,"ObjectID",'$tcode','$result','$status','$status', $pt_id,'$ymd' from test_order where
clip_num=$rand
SQL;

if (pg_query($db, $stmt2)){
print '<p>TEST result  created</p>';
}
else {
print '<p > TEST result  error</p>';
die;
}

	}




	}







 		


	if ($cont!='A'){
	 print "***end of this patient";
		print '<br>';

		$fst=1;
		}

	}

}

 
 
/*





	$stmt = <<<SQL
insert into test_result(
"TestMaster",
"value" ,
decision ,
state, 
ptid,
kekkadate )

select  m."ID",  r.kekka , r.ijo, r.ijo , r.ptid,  r.testdate from test_master m, test_resultwksol r 
where trim (leading '0'from r.komokucode)=trim(trailing ' ' from m."LaboSystemCode")
SQL;

if (pg_query($db, $stmt)){
print '<p>Added to TEST RESULT </p>';
}
else {
print '<p>TEST RESULT  insertion  error</p>';
die;
}
	

//0606-2011 testorder is null

$stmt = <<<SQL
update test_result r set "TestOrder"=(select t."ID" from test_order t where

r.ptid=t."Patient" and r.kekkadate=t.kekkadate limit 1) where "TestOrder" is null;
SQL;

if (pg_query($db, $stmt)){
print '<p>TEST ID was added to test result</p>';
}
else {
print '<p >TEST ID update error , duplicate ID?</p>';
die;
}


print '<p> COMPLETED </p>';

*/
print "***end of session, order no:";
		print '<br>';
print $iii;

		print '<br>';

?>


<a href="index.php?tab=1">メインに戻る</a>


	</tbody>

</html>





































?>




	</body>
</html>
