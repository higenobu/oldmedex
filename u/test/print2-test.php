<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order-app.php';
$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
//


//
class to extends test_order_display {
  function draw_body_2() {
  }
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

  $test_app_types = array('検体', '生理');
  $titles = array("検査指示箋", "検査結果");
  $ttl = $test_app_types[$test_app_type] . $titles[$status];
  mx_html_head($ttl,false);
  print '<center><span class="appname">'.$ttl.'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select "Patient" from test_order where "Superseded" is null and "ObjectID"=' . $oid;
  $r = mx_db_fetch_single($db, $stmt);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['Patient'];
  $sod->id = $oid;
  $alldata=$sod->fetch_data($oid);

//new 10-24-2014

 $stmt = '
select
       O."ObjectID" as "TestOrder", O."Patient",O."DrCode", O."SampleDate", O."OrderDate",O.clip_num,

       C."ObjectID" as "TestOrderContent", C."TestID" as "Test", 

       RM."ObjectID" as "TestMaster", RM."Name" as "TestName", 
       RM."LaboSystemCode" as "LaboSystemCode",
       RM."FemaleNormalBottom" AS "NormalBottom",
       RM."FemaleNormalTop" AS "NormalTop",
       RM."FemaleNormalText" AS "NormalText",
       RM."Unit" as "MasterTestUnit",
       RM."SortOrder" as "SortOrder", 

       CG."Name" AS "Category"
       
FROM test_order AS O 
     LEFT JOIN test_order_content AS C ON O."ObjectID" = C."TestOrder"
     LEFT JOIN test_master0 AS RM ON RM."ObjectID" = C."TestID"
     LEFT JOIN test_category AS CG ON CG."ObjectID" = RM."Category"

WHERE O."ObjectID" = ' . mx_db_sql_quote($oid);
    $stmt = $stmt . ' ORDER BY RM."SortOrder"';
$sth = pg_query($db, $stmt);


	
	$data = pg_fetch_all($sth);
//print count($data);
//print_r($data);
 
for ($i=0;$i<count($data);$i++){
$patient=$data[$i]['Patient'];
$drcode=$data[$i]['DrCode'];
$testnm=$testnm.":  ". $data[$i]['TestName'];
$orderdate=$data[$i]['OrderDate'];
$sampledate=$data[$i]['SampleDate'];

}
//print $testnm." ".$orderdate.$sampledate;


//result
$stmt2 = '
select  O."SampleDate",
	O."Patient",
        O."ObjectID" as "TestOrder", 
	RM."ObjectID" as "TestMaster",
	RM."Name" as "ResultName", RM."Independent",
 	RM."SetHeader" as "SetHeader", RM."LaboSystemCode" as "LaboSystemCode",
	RM."Parent" as "Parent",
	RM."FemaleNormalBottom" AS "NormalBottom",
	RM."FemaleNormalTop" AS "NormalTop",
        RM."FemaleNormalText" AS "NormalText",
        RM."Unit" as "MasterTestUnit", G."Name" AS "Category", R."value" as "TestValue", R."unit" as "TestUnit", R."decision" as "TestDecision", RM."SortOrder" as "SortOrder" , R."TestOrderContent" as "TestOrderContent", R."quantification_limit" as quantification_limit, 	R.normal_text, R.comment

from test_order As O join test_result as R
         ON O."ObjectID" = R."TestOrder" AND R."Superseded" IS NULL
     JOIN test_master0 AS RM
      ON RM."ObjectID" = R."TestMaster"
     LEFT JOIN test_category AS G
      ON G."ObjectID" = RM."Category"
WHERE O."ObjectID" = ' . mx_db_sql_quote($oid);
    $stmt2 = $stmt2 . ' ORDER BY RM."SortOrder"';

$sth2 = pg_query($db, $stmt2);


	
	$data2 = pg_fetch_all($sth2);
//print count($data2);
//print_r($data);
 
for ($i=0;$i<count($data2);$i++){
$testnm2=$testnm2.":  ". $data2[$i]["ResultName"]."=". $data2[$i]["TestValue"];
 
 

}
//print $testnm2." " ;

//

 
$otype="TEST";
$odate=substr($orderdate,0,10);
//print $odate;
$ptid=$patient;
$shiji=$drcode;
//$ocont=$testnm." Result\n".$testnm2;
$ocont=" Result\n".$testnm2;
$ocont="--------\n"."TEST\n"."Shiji=".$shiji."  ".$ocont."\n";

 
$stmt10 = <<<SQL
SELECT "ID" FROM orderinfo  where oid=$oid and patient=$ptid and ordertype='$otype' limit 1
SQL;
// print $stmt10;

$rs0 = mx_db_fetch_single($db, $stmt10);

if ($rs0 == null){

 $stmt1 = <<<SQL
INSERT INTO orderinfo(
            orderdate, patient, 
            ordertype, "content",oid)
    
    VALUES ('$odate','$ptid', '$otype', '$ocont',$oid)
        
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){
//print $stmt1;
}
else {
print '<p > insert to orderinfo DB access error</p>';
die;
}
}
else {
 



 $stmt1 = <<<SQL
update  orderinfo set "content"='$ocont',"update"=1
		 
             where oid=$oid and patient=$ptid and ordertype='$otype' 
    
  
        
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > update ordr info DB access error</p>';
die;
}


 
}




// end of karte insert

//Show rint image
  mx_draw_patientinfo_bmd($r['Patient'], array('Culture' => 'Japanese',
					       'ShowWardPref' => 1));
  $sod->draw();
  print <<<HTML
<center>
<table width="480px" height="160px">
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;width: 30%">□変更</td><td style="vertical-align: top;border: 1px; border-style:solid; width:30%;">□中止</td><td style="vertical-align: top;border: 1px; border-style:solid;width:30%">&nbsp;</td>
</tr>
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;">指示医サイン</td><td style="vertical-align: top; border: 1px; border-style:solid;">採取担当者サイン</td><td style="vertical-align: top; border: 1px; border-style:solid;">&nbsp;</td>
</tr>
</table>
HTML;
}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print2-test.php?top=1" name="top_frame" scrolling="no">
         <frame src="print2-test.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
