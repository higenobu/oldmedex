<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/karteview.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

function get_patientorca($id,$type)
{
  
   

$con =  pg_connect("host=localhost dbname=orca user=orca ");
print $con;

$query = 'select a.ptid, a.name as ptname,a.birthday as ptdob,home_post, home_adrs, home_tel1, setainusi,
a.sex as ptsex, 
b.hknid, b.hknjanum,b.kigo,b.num, b.hihknjaname, b.kakuninymd, d.hknjaname, d.adrs

from tbl_ptinf a,

  tbl_pthkninf b,
  
 tbl_hknjainf d,
tbl_ptnum p

where
 
a.ptid=b.ptid and
b.hknjanum=d.hknjanum  and
a.ptid=p.ptid and
 p.ptnum=';
 

$cond11="'".$id."'";	

 

$query = $query.$cond11;




//0615-2011  	
$query = $query."  order by hknid desc  limit 1";




 



$res = pg_query($con, $query);

 
print pg_num_rows($res);

  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;

 

  return $pat;
}

function s($d) {
  if(is_null($d) || $d == '')
    return '&nbsp;';
  return htmlspecialchars($d);
}

function rx_template($data) {
  global $_mx_hack_takamiya;

  $DateOfIssue = s($data['reportdate']);

  $PatientID = s($data['PATIENT_ID']);
$PatientDOB=s($data['PATIENT_DOB']);
$PatientSex=s($data['PATIENT_SEX']);
  $PatientName = s($data['ptname']);
  $setainusi = s($data['setainusi']);
  $EnteredBy = s($data['EnteredBy']);
  $PatientAddr = s($data['PatientAddr']);
  $PatientAge = s($data['PatientAge']);
  // $RxBody = s($data['RxBody']);
  $facttype = s($data['facttype']);
  $DrName = s($data['DrName']);
  $HospitalName = s($data['HospitalName']);
  $CorporationName = s($data['CorporationName']);
 $S0=s($data['S0']);
$kohnum  = s($data['WELFARE_NUMBER']);
  $ftnjanum = s($data['WELFARE_RECIPIENT']);
  $kohkigen = s($data['WELFARE_GOOD_THRU'] );
  
 
  // 患者氏名、生年月日、区分、割合
  
  $ptkubun = s($data['PATIENT_KUBUN']);
  
 // 保険者番号
  // 被保険者証記号・番号
  $hknjanum = s($data['INSURER_NUMBER']);
  $kigo=s($data['INSURED_KIGO']);
  $bango=s($data['INSURED_NUMBER']);
  $insname=s($data['insname']);
  
$insadrs=s($data['insadrs']);
$kaku=s($data['kaku']);
  // 医療機関所在地、名称
  // 電話番号、保険医氏名
  


//0707-2011
//	$Stop = s($data['Stop']);
  $template = 'karte.html';
   
  eval("\$html=<<<HTML\n" . file_get_contents(dirname(__FILE__) . '/../../templates/' . $template) . "HTML;\n");

  return $html;
}











$oid=$_GET['oid'];
$status = $_GET['status'];
$test_app_type = $_GET['test_app_type'];
$top=$_GET['top'];
$bottom=$_GET['bottom'];


class to extends everybody_karteview_display {
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
  $test_app_types = array('insident');
  $titles = array("insident");
  $ttl = "";
  mx_html_head($ttl,false);
 


  $db = mx_db_connect();
  $stmt = 'select "ID","ObjectID","Superseded", "日付" ,"患者","S0"
  from "カルテデモ表"   where "Superseded" is null and "ObjectID"='.$oid;
  $r = mx_db_fetch_single($db, $stmt);


 $sod = new to("");
  $sod->so_config['Patient_ObjectID'] = $r['患者'];
  $sod->id = $oid;
  $sod->fetch_data($oid);


 
//0916-2011 test
 $pat = get_patient($r['患者'],false);


$ptid=$pat['患者ID'];
$pat=array();

$pat = get_patientorca($ptid,false);


$data = array();
  
  $data['reportdate'] = mx_wareki($r['日付']);
 	 
 
 $data['S0'] = $r['S0'];
//$pat is ORCA info 
// 公費負担者番号
  // 受給者番号
  $data['WELFARE_NUMBER'] = $pat['kohnum'];
  $data['WELFARE_RECIPIENT'] = $pat['ftnjanum'];
  $data['WELFARE_GOOD_THRU'] = $pat['公費有効期限'];
  
  
  
  // 患者氏名、生年月日、区分、割合
  
  
  
 // 保険者番号
  // 被保険者証記号・番号
  $data['INSURER_NUMBER'] = $pat['hknjanum'];
$data['insname'] = $pat['hknjaname'];
$data['insadrs'] = $pat['adrs'];
$data['kaku'] = $pat['kakuninymd'];
  $data['INSURED_KIGO']=$pat['kigo'];
  $data['INSURED_NUMBER']=$pat['num'];
  
  // 医療機関所在地、名称
  // 電話番号、保険医氏名
  
  
  

  
  // 処方
$data['PATIENT_ID'] = $pat['患者ID'];
  $data['setainusi'] = $pat['setainusi'];
  $data['ptname'] = $pat['ptname'];
// 
 $dob = substr($pat['ptdob'],0,4) . "-" . substr($pat['ptdob'],4,2) . "-".substr($pat['ptdob'],6,2); 
 $data['PATIENT_DOB'] = mx_wareki($dob);
  $data['PATIENT_SEX'] = $pat['ptsex'] == '1' ? '男' : '女';
 
$data['PatientAddr'] = $pat['home_post']." ".$pat['home_adrs']." ".$pat['home_tel1'];
  $data['PatientAge'] = mx_calc_age($dob);
// $idata is Hospital info

  $idata = mx_get_install_data();
  $data['HospitalName'] = $idata['HOSPITAL_NAME'];
  $data['CorporationName'] = $idata['CORPORATION_NAME'];
 
$sata['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $data['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
 

  print rx_template($data);
	 

}

else {
  print '<frameset rows="60, *" noresize border="0">
         <frame src="printkarte.php?top=1" name="top_frame" scrolling="no">
         <frame src="printkarte.php?bottom=1&';
  if ($oid) printf("test_app_type=%d&status=%d&oid=%d",$test_app_type, $status, $oid);
  print '" name="bottom_frame" ></frameset>';
}

if ($top || $bottom) print '</body></html>';
?>
