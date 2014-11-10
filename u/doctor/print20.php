<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/quickxray.php';
$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];

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
  print '<center><span class="appname">'."Ｘ線オーダ".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select "患者" , "その他" , shiji, yotei from "Ｘ線オーダ" where "Superseded" is null and "ObjectID"=' . $oid;
  $r = mx_db_fetch_single($db, $stmt);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['患者'];
  $sod->id = $oid;
  $sod->fetch_data($oid);

  mx_draw_patientinfo_bmd($r['患者'], array('Culture' => 'Japanese',
					       'ShowWardPref' => 1));
 // 03252011
 $memo = $r['その他'];
$yotei =$r['yotei'];

$shiji =$r['shiji'];


//$db = mx_db_connect();


$stmt = 'SELECT M.name AS "部位名称",M."ObjectID" AS "部位ObjectID",D.dirs, D.leftdir, D.rightdir,D.bothdirs, D.digits, O.shiji, O.yotei  FROM "Ｘ線オーダ" AS O JOIN "Ｘ線オーダ内容" AS D ON O."ObjectID" = D."Ｘ線オーダ" JOIN "Ｘ線部位マスタ" AS M ON M."ObjectID" = D."部位" AND M."Superseded" IS NULL WHERE O."ObjectID" = ' . $oid;


	
	$sth = pg_query($db, $stmt);


	
	$data = pg_fetch_all($sth);


	

print '<br><span class="darker">検査部位    方向      (番号）      左      右      両           </span></br>';





	foreach ($data as $e) {
		$name = $e['部位名称'];
	        $dirs =$e['dirs'];
                 $digits =$e['digits'];
 $leftdir =$e['leftdir'];
 $rightdir =$e['rightdir'];
 $bothdirs =$e['bothdirs'];

$shiji =$e['shiji'];
$yotei =$e['yotei'];

 
	print <<<HTML
 <br><span class="plain">  $name       $dirs  ($digits)   Left($leftdir)   Right($rightdir)  B($bothdirs) </span></br>

 

HTML;
}
		
  print <<<HTML
<br><span class="darker">指示医:  $shiji </span></br>
<br><span class="darker">予定日:  $yotei </span></br>


 <br><span class="darker">メモ:  $memo </span></br>


 

HTML;
	
//03252011

}
else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="print20.php?top=1&oid=$oid" name="top_frame" scrolling="no">
         <frame src="print20.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
