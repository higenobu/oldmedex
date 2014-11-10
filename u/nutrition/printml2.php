<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nutrition/meal-nutri.php';

$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
$bottom=true;

//print $oid."oid";

class to extends meal_nutri_order_display {
  function draw_body_2() {
  }
}


if ($top) {
//print "TTTTTTTTTTTT";

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
//print "BBBBBBBBBBBBBBBBBBBBBB";
  $test_app_types = array('MEAL');
  $titles = array("MEAL");
  $ttl = "";
  mx_html_head($ttl,false);
  print '<center><span class="appname">'."MEAL".'</span></center>';

$stmt = 'select order_date, patient, dr_order,staple_shape,kk1, ss1,cc1 ,pt_no , pt_nm, pt_kana from meal_order  inner join tbl_patient on  patient=id where "Superseded" is null and "ObjectID"=' . $oid;
   
  $db = mx_db_connect();
  
 

  $r = mx_db_fetch_single($db, $stmt);
// print_r($r);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['patient'];

  $sod->id = $oid;
  $sod->fetch_data($oid);

 


   print '
        <input type="button" value="印刷" onClick="printPopup()">
        <input type="button" value="画面を閉じる" onClick="window.parent.close()">';
        
        					     
$sod->draw();
/*

$memo1=$r['memo1'];

print '<br><span class="plain">検査部位    -+            </span></br>';
print <<<HTML
 	<br><span class="plain">  $memo1     </span></br>

 

HTML;

*/


//
print <<<HTML
<center>
<table width="480px" height="160px">
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;width: 30%">□変更</td><td style="vertical-align: top;border: 1px; border-style:solid; width:30%;">□中止</td><td style="vertical-align: top;border: 1px; border-style:solid;width:30%">&nbsp;</td>
</tr>
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;">指示医サイン</td><td style="vertical-align: top; border: 1px; border-style:solid;">担当者サイン</td><td style="vertical-align: top; border: 1px; border-style:solid;">&nbsp;</td>
</tr>
</table>
HTML;

}

else {


  print '<frameset rows="60, *" noresize border="0">
         <frame src="printml2.php?top=1" name="top_frame" scrolling="no">
         <frame src="printml2.php?bottom=1&';
 if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
