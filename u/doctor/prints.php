<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/seiorder.php';
//10-28-2014  added common, ord_common
//seiorder new seiri order

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];
//$bottom=true;

//print $oid."oid";

class to extends seiorder_display {
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
         <form><input type="button" value="°õºþ" onClick="printPopup()">
         <input type="button" value="²èÌÌ¤òÊÄ¤¸¤ë" onClick="window.parent.close()">
         </form>
         </center>';

}
elseif ($bottom) {
//print "BBBBBBBBBBBBBBBBBBBBBB";
  $test_app_types = array('SEIRI');
  $titles = array("SEIRI");
  $ttl = "";
  mx_html_head($ttl,false);
  print '<center><span class="appname">'."À¸Íý¸¡ºº".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'SELECT orderdate, plandate, 
       procdate, "´µ¼Ô", teikikubun, xctkubun, techname, techid, bui1, 
       bui2, bui3, bui4, bui5, memo1, memo2, memo3, memo4, memo5, memo11, 
       memo21, memo31, memo41, memo51, memo12, memo22, memo32, memo42, 
       memo52, syoken1, syoken2, syoken3, syoken4, syoken5, techsyoken, 
       drsyoken, proof, shiji, gishi, stop
  FROM seiorder
 inner join tbl_patient on  "´µ¼Ô"=id
       where "Superseded" is null and "ObjectID"=' . $oid;
//print $stmt;

  $r = mx_db_fetch_single($db, $stmt);
//print_r($r);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['´µ¼Ô'];

  $sod->id = $oid;
  $sod->fetch_data($oid);

 


   print '
        <input type="button" value="°õºþ" onClick="printPopup()">
        <input type="button" value="²èÌÌ¤òÊÄ¤¸¤ë" onClick="window.parent.close()">';
        
        					     
$sod->draw();
/*

$memo1=$r['memo1'];

print '<br><span class="plain">¸¡ººÉô°Ì    -+            </span></br>';
print <<<HTML
 	<br><span class="plain">  $memo1     </span></br>

 

HTML;

*/


//
print <<<HTML
<center>
<table width="480px" height="160px">
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;width: 30%">¢¢ÊÑ¹¹</td><td style="vertical-align: top;border: 1px; border-style:solid; width:30%;">¢¢Ãæ»ß</td><td style="vertical-align: top;border: 1px; border-style:solid;width:30%">&nbsp;</td>
</tr>
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;">»Ø¼¨°å¥µ¥¤¥ó</td><td style="vertical-align: top; border: 1px; border-style:solid;">Ã´Åö¼Ô¥µ¥¤¥ó</td><td style="vertical-align: top; border: 1px; border-style:solid;">&nbsp;</td>
</tr>
</table>
HTML;

}

else {


  print '<frameset rows="60, *" noresize border="0">
         <frame src="prints.php?top=1" name="top_frame" scrolling="no">
         <frame src="prints.php?bottom=1&';
 if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
