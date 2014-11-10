<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/seiorder.php';
//10-20-2014 unfinished
//10-28-2014  added common, ord_common
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';








print "PPPPPPPPPPPPPPPPPPPPPPPP";

$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];


class to extends seiorder_display {
  function draw_body_2() {
  }
}

if ($top) {
print "TTTTTTTTTTTT";

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
print "BBBBBBBBBBBBBBBBBBBBBB";
  $test_app_types = array('SEIRI');
  $titles = array("SEIRI");
  $ttl = "";
  mx_html_head($ttl,false);
  print '<center><span class="appname">'."SEIRI".'</span></center>';

  $db = mx_db_connect();
  $stmt = 'select orderdate, plandate, 
       procdate, "´µ¼Ô", teikikubun, xctkubun, techname, techid, bui1, 
       bui2, bui3, bui4, bui5, memo1, memo2, memo3, memo4, memo5, memo11, 
       memo21, memo31, memo41, memo51, memo12, memo22, memo32, memo42, 
       memo52, syoken1, syoken2, syoken3, syoken4, syoken5, techsyoken, 
       drsyoken, proof, shiji, gishi, stop , pt_no , pt_nm, pt_kana from seiorder  inner join tbl_patient on  "´µ¼Ô"=id
       where "Superseded" is null and "ObjectID"=' . $oid;
print $stmt;

  $r = mx_db_fetch_single($db, $stmt);
  $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['´µ¼Ô'];
  $sod->id = $oid;
  $sod->fetch_data($oid);
print_r($r);
$sod->draw();
// mx_draw_patientinfo_bmd($r['´µ¼Ô'], array('Culture' => 'Japanese',
//					       'ShowWardPref' => 1));

//
print <<<HTML
<center>
<table width="480px" height="160px">
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;width: 30%">¢¢ÊÑ¹¹</td><td style="vertical-align: top;border: 1px; border-style:solid; width:30%;">¢¢Ãæ»ß</td><td style="vertical-align: top;border: 1px; border-style:solid;width:30%">&nbsp;</td>
</tr>
<tr>
    <td style="vertical-align: top; border: 1px; border-style:solid;">»Ø¼¨°å¥µ¥¤¥ó</td><td style="vertical-align: top; border: 1px; border-style:solid;">ºÎ¼èÃ´Åö¼Ô¥µ¥¤¥ó</td><td style="vertical-align: top; border: 1px; border-style:solid;">&nbsp;</td>
</tr>
</table>
HTML;
//
/*  
$sod->draw();
  print <<<HTML

HTML;
*/


/*
$pat = get_patient($r['´µ¼Ô'],false);
$data = array();
  
  $data['DateOfIssue'] = mx_wareki($r['orderdate']);
 
//$db = mx_db_connect();
  $stmt = <<<SQL
    select  medis_cd as cd ,  kensa_name as name
    from seiri_master   
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)

	 $ret[$row['cd']] = $row['name'];
	
 print_r($ret);

 $data['bui1'] = $ret[$r['bui1']]; 
$data['bui2'] = $ret[$r['bui2']]; 
 $data['bui3'] = $ret[$r['bui3']]; 
$data['bui4'] = $ret[$r['bui4']]; 
 $data['bui5'] = $ret[$r['bui5']]; 
 $data['drsyoken'] = $r['drsyoken'];
$data['DateOfIssue'] = mx_wareki($r['orderdate']);
$data['pt_no'] = $r['pt_no'];
$data['pt_nm'] = $r['pt_nm'];
$data['pt_kana'] = $r['pt_kana'];
$data['PatientGroup'] = $pat['´õË¾ÉÂÅï'];
  $data['PatientAge'] = mx_calc_age($pat['À¸Ç¯·îÆü']);
 $data['techsyoken'] = $r['techsyoken'];
  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
  $data['syoken1'] = $r['syoken1']; 
$data['syoken2'] = $r['syoken2']; 
$data['syoken3'] = $r['syoken3']; 
$data['syoken4'] = $r['syoken4']; 
$data['syoken5'] = $r['syoken5']; 


  print rx_template($data);

*/
	 

}

else {


  print '<frameset rows="60, *" noresize border="0">
         <frame src="printsenri.php?top=1" name="top_frame" scrolling="no">
         <frame src="printsenri.php?bottom=1&';
  if ($oid) printf("status=%d&oid=%d", $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
