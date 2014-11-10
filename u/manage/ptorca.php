<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<title>karte</title>
	</head>
	<body>
<a href="index.php?tab=1">メインに戻る</a>

<a href="ptorca-app.php?tab=1">再実行</a>


<br>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';

global $_mx_rx_template;
function sppad($data)
{

$v=$data;
 $leng= strlen($data );
 $ksps=" ";




	 
		$cnt = 8-$leng;
		for ($i = 0; $i < $cnt; $i++) {

			$v = $ksps.$v;
		}
		return $v;
	 
}
  
function zeropad($data)
{

$v=$data;
 $leng= strlen($data );
 $ksps="0";




	 
		$cnt = 8-$leng;
		for ($i = 0; $i < $cnt; $i++) {

			$v = $ksps.$v;
		}
		return $v;
	 
}

function mx_space($data)
{

   
	$o = '';
	for ($i = 0; $i < 8; $i++) {
		$c = mb_substr($data, $i, 1);
		if ($c == '　')
			$c = '';
		if ($c == ' ')
			$c = '';
		if ($c == '')
			$c = '';
		$o .= $c;
	}
	return $o;
}

$template='shohousen_k.ods';

//pg_set_client_encoding('EUC_JP');


$zid=$_POST[ptid];
$id= zeropad($zid);
//$name=$_POST[name];
//0415-2014
$hostaddr='10.13.55.2';

$con =  pg_connect("hostaddr='$hostaddr' dbname=orca user=orca password=orca");
if (!$con) {
	echo("<p><span style=\"color:red\">orca cannot be connected</span></p>\n");
	echo("</body></html>\n");
	exit;
}




$query = 'select a.ptid, p.ptnum, a.name as ptname,a.kananame,a.birthday as ptdob,home_post, home_adrs, home_tel1, home_banti,setainusi,
a.sex as ptsex, 
office_name,office_post,office_adrs,office_banti,honkzkkbn,
skkgetymd,tekedymd,b.hknid, b.hknjanum,b.kigo,b.num, b.hihknjaname, b.kakuninymd, d.hknjaname, d.adrs

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



	
$query = $query."  order by hknid desc  limit 1";

$res = pg_query($con, $query);

 


  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;


if ($pat==FALSE){
$query = 'select a.ptid, p.ptnum, a.name as ptname,a.kananame,a.birthday as ptdob,home_post, home_adrs, home_tel1, home_banti,setainusi,
a.sex as ptsex, 
office_name,office_post,office_adrs,office_banti,honkzkkbn,
skkgetymd,tekedymd,b.hknid, b.hknjanum,b.kigo,b.num, b.hihknjaname, b.kakuninymd

from tbl_ptinf a,

  tbl_pthkninf b,
  
 
tbl_ptnum p

where
 
a.ptid=b.ptid and

a.ptid=p.ptid and
 p.ptnum=';
 

$cond11="'".$id."'";	

 

$query = $query.$cond11;



	
$query = $query."  order by hknid desc  limit 1";

$res = pg_query($con, $query);

  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;
}
if ($pat==FALSE){
$query = 'select a.ptid, p.ptnum, a.name as ptname,a.kananame,a.birthday as ptdob,home_post, home_adrs, home_tel1, home_banti,setainusi,
a.sex as ptsex, 
office_name,office_post,office_adrs,office_banti


from tbl_ptinf a,

 
  
 
tbl_ptnum p

where
 


a.ptid=p.ptid and
 p.ptnum=';
 

$cond11="'".$id."'";	

 

$query = $query.$cond11;



	
$query = $query."  order by hknid desc  limit 1";

$res = pg_query($con, $query);

  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;

}


 

$insnum=$pat['num'];
 
//************************************************

$query = 'select c.ftnjanum, c.jkysnum
 
from 
  tbl_ptkohinf as c, tbl_ptnum as p
where
c.ptid=p.ptid and
 p.ptnum=';
 

$cond11="'".$id."'";	

 

$query = $query.$cond11;



//10-20-2012	
//$query = $query."    limit 1";
$query = $query."  and ftnjanum >'0' order by c.kakuninymd desc   limit 1";
print $query;

$res1 = pg_query($con, $query);

 


  if (pg_num_rows($res1) && 
      ($pat1 = pg_fetch_array($res1, PG_ASSOC)))
     pg_free_result($res1);
  else
     $pat1 = FALSE;


/*
$con1 = mx_db_connect();


$stmt = <<<SQL
    

UPDATE "患者台帳"
   SET 
       "被保険者手帳の番号"='$insnum'
 WHERE "患者ID"='$id'
SQL;
$rs = pg_query($con1, $stmt);
if (!$rs) {
	
	pg_close($con);
	echo("<p><span style=\"color:red\">$_POST[ptid] doe not exist </span></p>\n");
	echo("</body></html>\n");
	exit;
}



*/


//***********************************************


// $pat['患者ID'] = ereg_replace("^(.*) .*","\\1",$pat['患者ID']);
  

  $params = array();
 
 $params['BODY'] = "  ";

  
 $params['PRESCRIPTION_TITLE'] = '診療録';
  
  // 公費負担者番号
  // 受給者番号
if ($pat1==FALSE){
$params['WELFARE_NUMBER:%8s']=null;
$params['WELFARE_RECIPIENT_0']= " ";
$params['WELFARE_RECIPIENT_1']= " ";
$params['WELFARE_RECIPIENT_2']= " ";
$params['WELFARE_RECIPIENT_3']= " ";
$params['WELFARE_RECIPIENT_4']= " ";
$params['WELFARE_RECIPIENT_5']= " ";
$params['WELFARE_RECIPIENT_6']= " ";
}
else {
 $params['WELFARE_NUMBER:%8s'] = $pat1['ftnjanum'];


print "jkysnum:".$pat1['jkysnum'];
print "hknjanum:".$pat['hknjanum'];

 
$params['WELFARE_RECIPIENT_0']= substr($pat1['jkysnum'],0,1);
$params['WELFARE_RECIPIENT_1']= substr($pat1['jkysnum'],1,1);
$params['WELFARE_RECIPIENT_2']= substr($pat1['jkysnum'],2,1);
$params['WELFARE_RECIPIENT_3']= substr($pat1['jkysnum'],3,1);
$params['WELFARE_RECIPIENT_4']= substr($pat1['jkysnum'],4,1);
$params['WELFARE_RECIPIENT_5']= substr($pat1['jkysnum'],5,1);
$params['WELFARE_RECIPIENT_6']= substr($pat1['jkysnum'],6,1);

}
  $params['WELFARE_GOOD_THRU'] = $pat['公費有効期限'];
  
  
  
  // 患者氏名、生年月日、区分、割合
  $params['PATIENT_ID'] = $pat['ptnum'];
if ($pat['kananame']=='') {$pat['kananame']='     ';}
  $params['PATIENT_KANA'] = $pat['kananame'];
  $params['PATIENT_KANJI'] = $pat['ptname'];
   
   
  
  $params['PATIENT_KUBUN'] = $pat['honkzkkbn'] == '1' ? '本人' : '家族';
 // $params['PATIENT_GROUP'] = $pat['希望病棟'];
   $dob = substr($pat['ptdob'],0,4) . "-" . substr($pat['ptdob'],4,2) . "-".substr($pat['ptdob'],6,2); 
 $params['PATIENT_DOB'] = mx_wareki($dob);
$params['PATIENT_AGE'] = mx_calc_age($dob);
  $params['PATIENT_SEX'] = $pat['ptsex'] == '1' ? '男' : '女';
//12-09-20111 
$params['PatientAddr'] = $pat['home_adrs']." ".$pat['home_banti'];
$params['PatientTel']= $pat['home_tel1'];
 $params['OfficeAddr'] = $pat['office_post']."   ".$pat['office_adrs']." ".$pat['office_banti'];
  $params['OfficeTel'] = $pat['office_tel'];
$params['OfficeName'] = $pat['office_name'];
  // 交付年月日
//  $params['PRESCRIPTION_DATE'] = mx_wareki($rs['日付']);
                                           
//0917-2011
if ($pat['hknjaname']==""){$params['hknjaname']=" ";}
if ($pat['hihknjaname']==""){$params['hihknjaname']=" ";}
$params['hknjaname']=$pat['hknjaname'];


$params['hihknjaname']=$pat['hihknjaname'];
$params['adrs']=$pat['adrs']; 
if ($pat['kakuninymd']==""){$params['kakuninymd']=" ";}
else {   $kakunin = substr($pat['kakuninymd'],0,4) . "-" . substr($pat['kakuninymd'],4,2) . "-".substr($pat['kakuninymd'],6,2); 
$params['kakuninymd']=mx_wareki($kakunin);
}
if ($pat['skkgetymd']==""){$params['skkgetymd']=" ";}
 else {
 $skkgetymd=$pat['skkgetymd'];
$skkgetymd=substr($skkgetymd,0,4) . "-" . substr($skkgetymd,4,2) . "-".substr($skkgetymd,6,2);
 $params['skkgetymd']=mx_wareki($skkgetymd);
}
 if ($pat['tekedymd']==""){$params['tekedymd']=" ";}
 else { $tekedymd=$pat['tekedymd'];
$tekedymd=substr($tekedymd,0,4) . "-" . substr($tekedymd,4,2) . "-".substr($tekedymd,6,2);
 $params['tekedymd']=mx_wareki($tekedymd);
}

  // 保険者番号
  // 被保険者証記号・番号
$insnumx=mx_space($pat['hknjanum']);
$insnum=sppad($insnumx);
print $insnum;
print "AAA";
print strlen($insnum);
print "BBB";

$pat['hknjanum']=$insnum;

$params['INSURER_NUMBER_0']= substr($pat['hknjanum'],0,1);
$params['INSURER_NUMBER_1']= substr($pat['hknjanum'],1,1);
$params['INSURER_NUMBER_2']= substr($pat['hknjanum'],2,1);
$params['INSURER_NUMBER_3']= substr($pat['hknjanum'],3,1);
$params['INSURER_NUMBER_4']= substr($pat['hknjanum'],4,1);
$params['INSURER_NUMBER_5']= substr($pat['hknjanum'],5,1);
$params['INSURER_NUMBER_6']= substr($pat['hknjanum'],6,1);
$params['INSURER_NUMBER_7']= substr($pat['hknjanum'],7,1);

/*
if ($pat['hknjanum']==""){
$params['INSURER_NUMBER_0']= " ";
$params['INSURER_NUMBER_1']= " ";
$params['INSURER_NUMBER_2']= " ";
$params['INSURER_NUMBER_3']= " ";
$params['INSURER_NUMBER_4']= " ";
$params['INSURER_NUMBER_5']= " ";
$params['INSURER_NUMBER_6']= " ";
$params['INSURER_NUMBER_7']= " ";
}
else {
if (strlen($pat['hknjanum'])==8){
$params['INSURER_NUMBER_0']= substr($pat['hknjanum'],0,1);
$params['INSURER_NUMBER_1']= substr($pat['hknjanum'],1,1);
$params['INSURER_NUMBER_2']= substr($pat['hknjanum'],2,1);
$params['INSURER_NUMBER_3']= substr($pat['hknjanum'],3,1);
$params['INSURER_NUMBER_4']= substr($pat['hknjanum'],4,1);
$params['INSURER_NUMBER_5']= substr($pat['hknjanum'],5,1);
$params['INSURER_NUMBER_6']= substr($pat['hknjanum'],6,1);
$params['INSURER_NUMBER_7']= substr($pat['hknjanum'],7,1);
}
if (strlen($pat['hknjanum'])==7){
$params['INSURER_NUMBER_0']=" ";
$params['INSURER_NUMBER_1']= substr($pat['hknjanum'],0,1);
$params['INSURER_NUMBER_2']= substr($pat['hknjanum'],1,1);
$params['INSURER_NUMBER_3']= substr($pat['hknjanum'],2,1);
$params['INSURER_NUMBER_4']= substr($pat['hknjanum'],3,1);
$params['INSURER_NUMBER_5']= substr($pat['hknjanum'],4,1);
$params['INSURER_NUMBER_6']= substr($pat['hknjanum'],5,1);
$params['INSURER_NUMBER_7']= substr($pat['hknjanum'],6,1);

}
if (strlen($pat['hknjanum'])==6){
$params['INSURER_NUMBER_0']=" ";
$params['INSURER_NUMBER_1']=" ";
$params['INSURER_NUMBER_2']= substr($pat['hknjanum'],0,1);
$params['INSURER_NUMBER_3']= substr($pat['hknjanum'],1,1);
$params['INSURER_NUMBER_4']= substr($pat['hknjanum'],2,1);
$params['INSURER_NUMBER_5']= substr($pat['hknjanum'],3,1);
$params['INSURER_NUMBER_6']= substr($pat['hknjanum'],4,1);
$params['INSURER_NUMBER_7']= substr($pat['hknjanum'],5,1);


}
if (strlen($pat['hknjanum'])==5){
$params['INSURER_NUMBER_0']=" ";
$params['INSURER_NUMBER_1']=" ";
$params['INSURER_NUMBER_2']= " ";
$params['INSURER_NUMBER_3']= substr($pat['hknjanum'],0,1);
$params['INSURER_NUMBER_4']= substr($pat['hknjanum'],1,1);
$params['INSURER_NUMBER_5']= substr($pat['hknjanum'],2,1);
$params['INSURER_NUMBER_6']= substr($pat['hknjanum'],3,1);
$params['INSURER_NUMBER_7']= substr($pat['hknjanum'],4,1);


}

}

*/


print "insurer:";

print $insnum;




  $params['INSURED_KIGO']=$pat['kigo'];
  $params['INSURED_NUMBER']=$pat['num'];
  
  // 医療機関所在地、名称
  // 電話番号、保険医氏名
 /* 
  $idata = mx_get_install_data();
 // $params['HOSPITAL_NAME'] = 'AAAAAAAAAAAAAAAAAAAAAAAA';

  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  */

  
  $params['BODY'] = ' ';

  
  $params['COMMENT'] = '';
//  $params['COMMENT'] = $pat['備考']."\n";
//  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
 //   $params['COMMENT'] .= '公費負担者番号: ' . $pat['公費負担者番号3']."\n";
//    $params['COMMENT'] .= '公費負担医療の受給者番号: ' . $pat['公費負担医療の受給者番号3'] . "\n";
//  }


  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;

 
 
 

  print ooo_print_pdf2($params);
	
  if(file_exists($pdf_path)) {
    //---- read pdf file
    $handler = fopen($pdf_path, 'rb');
    $content = fread($handler, filesize($pdf_path));
    fclose($handler);
    unlink($pdf_path);

    //---- store into db
    $db = mx_db_connect();
    $bid = mx_db_insert_blobmedia($db, 'application/pdf', $content);
    $type = 'karte';
     
    $id = mx_db_insert_extdocument($db, $type, $bid,
				   $pt=NULL, $comment=NULL);
    // update record...
    // this is irregular design. SOD should not update db in normal case
     /*
      $stmt = 'UPDATE "薬剤処方箋" SET "PDF"=' . mx_db_sql_quote($id) .
	' WHERE "ObjectID"=' . mx_db_sql_quote($oid);
  

   pg_query($db, $stmt);
*/

    //HACK: open window and show PDF for client-side printing

    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("/blobmedia.php/' . $id .
      '/generated.pdf","","width=640,height=640");
</SCRIPT>'; 

}






?>

