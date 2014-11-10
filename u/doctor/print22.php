<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/quickxray2.php';
$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
//print22.php for CT u/doctor/
class to extends quickxray_display {
  
}

if ($top) {
  print '<script language="javascript" type="text/javascript">
         <!--
         function printPopup() {
         parent.frames[1].focus();
         parent.frames[1].print();
         }
         -->
         </script>';
  mx_html_head("",false);
  print '<body><center>
         <form><input type="button" value="印刷" onClick="printPopup()">
         <input type="button" value="画面を閉じる" onClick="window.parent.close()">
         </form>
         </center>';
}
elseif ($bottom) {
  $test_app_types = array('Xray');
  $titles = array("Xray");
  $ttl = $test_app_types[$test_app_type] . $titles[$status];
  mx_html_head($ttl,false);
  print '<center><span class="appname">'."CTオーダ".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select "患者" , "その他" from "ctorder" where "Superseded" is null and "ObjectID"=' . $oid;
  $r = mx_db_fetch_single($db, $stmt);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['患者'];
  $sod->id = $oid;
  $sod->fetch_data($oid);

  mx_draw_patientinfo_bmd($r['患者'], array('Culture' => 'Japanese',
					       'ShowWardPref' => 1));
 // 03252011
 $memo = $r['その他'];



//$db = mx_db_connect();


$stmt = 'SELECT M.name AS "部位名称",M."ObjectID" AS "部位ObjectID",D.dirs, D.leftdir, D.rightdir,D.bothdirs, D.digits, O.shiji, O.yotei  FROM "ctorder" AS O JOIN "ctordercont" AS D ON O."ObjectID" = D."Ｘ線オーダ" JOIN "ctmaster" AS M ON M."ObjectID" = D."部位" AND M."Superseded" IS NULL WHERE O."ObjectID" = ' . $oid;


	
	$sth = pg_query($db, $stmt);


	
	$data = pg_fetch_all($sth);


    
	






	foreach ($data as $e) {
		$name = $e['部位名称'];
	        $dirs =$e['dirs'];
                 $digits =$e['digits'];
 	$leftdir =$e['leftdir'];
 	$rightdir =$e['rightdir'];
 	$bothdirs =$e['bothdirs'];
	$shiji =$e['shiji'];
	$yotei =$e['yotei'];

	if ($dirs ='1'){
 	$dirs ='-';}
 
	if ($dirs ='2'){
 	$dirs ='+';}
	if ($dirs ='3'){
 	$dirs ='-+';}
 $ocont=$ocont.$name."|".$dirs."|".$leftdir."|";

	print '<br><span class="plain">検査部位    -+            </span></br>';
print <<<HTML
 	<br><span class="plain">  $name     $dirs  </span></br>

 

HTML;
}

//new 10-24-2014

// insert rx-info into orderdb 10-24-2014

$con = mx_db_connect();
$otype="ct";
$odate=$yotei;
$ptid=$r['患者'];

$ocont="CT\n".$ocont.$shiji."|";


$stmt10 = <<<SQL
SELECT "ID" FROM orderinfo  where oid=$oid and patient=$ptid and ordertype='$otype' limit 1
SQL;
 print $stmt10;

$rs0 = mx_db_fetch_single($con, $stmt10);

if ($rs0 == null){




 $stmt1 = <<<SQL
INSERT INTO orderinfo(
            orderdate, patient, 
            ordertype, "content",oid)
    
    VALUES ('$odate','$ptid', '$otype', '$ocont',$oid)
        
SQL;
//print $stmt1;
if (pg_query($con, $stmt1)){
//print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}
}
else {
 



 $stmt1 = <<<SQL
update  orderinfo set "content"='$ocont',"update"=1
		 
             where oid=$oid and patient=$ptid and ordertype='$otype' 
    
  
        
SQL;
print $stmt1;
if (pg_query($con, $stmt1)){

}
else {
print '<p > DB access error</p>';
die;
}


 
}

//

//
/* old 10-24-2014
$con = mx_db_connect();
$stmt10 = <<<SQL
SELECT "ID" FROM orderinfo where oid=$oid limit 1
SQL;
 
$rs = mx_db_fetch_single($con, $stmt10);
if ($rs == null){
 
$otype="ct";
$odate=$yotei;
$ptid=$r['患者'];
//$con = mx_db_connect();
$ocont="CT\n".$ocont.$shiji."|";

 $stmt1 = <<<SQL
INSERT INTO orderinfo(
            orderdate, patient, 
            ordertype, "content",oid)
    
    VALUES ('$odate','$ptid', '$otype', '$ocont',$oid)
        
SQL;
if (pg_query($con, $stmt1)){
//print $stmt1;
}
else {
print '<p > DB access error</p>';
die;
}

}
*/
		
  print <<<HTML

<br><span class="darker">指示医:  $shiji </span></br>
<br><span class="darker">予定日:  $yotei </span></br>

 <br><span class="plain">メモ:  $memo </span></br>


 

HTML;
	
//03252011

}
else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print22.php?top=1&oid=$oid" name="top_frame" scrolling="no">
         <frame src="print22.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
