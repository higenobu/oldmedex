<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order-app.php';
$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];

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
  $sod->fetch_data($oid);

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
         <frame src="print2.php?top=1" name="top_frame" scrolling="no">
         <frame src="print2.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
