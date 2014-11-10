<?php // -*- coding: euc-jp -*-
//pdf.php
//insert to orderinfo
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rp_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';





function go_pdf_new($oid, $shots, $template=NULL) 
{
  global $_mx_rx_template;
/*
  if(is_null($template))
    $template = $_mx_rx_template;
  // read DB
  $ord = get_pharm_order($oid, $shots);
  $doc = get_emp_name($ord['��Ͽ��']);
//10-20-2014
  $meds = get_meds($oid,0);
  $pat = get_patient($ord['����'],false);
  
  $pat['����ID'] = ereg_replace("^(.*) .*","\\1",$pat['����ID']);
  
  
  // prepare values to be embedded into PDF
  $params = array();
  
  if ($ord['�����'])
    $params['PRESCRIPTION_TITLE'] = '����ߡ۽� �� �� ��';
  else
    $params['PRESCRIPTION_TITLE'] = '�衡����������������';
  if($shots) {
    if ($ord['�����'])
      $params['PRESCRIPTION_TITLE'] = '����ߡ��� �� �� ��';
    else
      $params['PRESCRIPTION_TITLE'] = '�����͡�����������';
  }
  
  // ������ô���ֹ�
  // ������ֹ�
  $params['WELFARE_NUMBER:%8s'] = $pat['������ô���ֹ�'];
  $params['WELFARE_RECIPIENT:%7s'] = $pat['������ô���Ťμ�����ֹ�'];
  $params['WELFARE_GOOD_THRU'] = $pat['����ͭ������'];
  
  $params['WELFARE_NUMBER2:%8s'] = $pat['������ô���ֹ�2'];
  $params['WELFARE_RECIPIENT2:%7s'] = $pat['������ô���Ťμ�����ֹ�2'];
  $params['WELFARE_GOOD_THRU2'] = $pat['����ͭ������2'];
  
  $params['WELFARE_NUMBER3'] = $pat['������ô���ֹ�3'];
  $params['WELFARE_RECIPIENT3'] = $pat['������ô���Ťμ�����ֹ�3'];
  $params['WELFARE_GOOD_THRU3'] = $pat['����ͭ������3'];
  
  // ���Ի�̾����ǯ��������ʬ�����
  $params['PATIENT_ID'] = $pat['����ID'];
  $params['PATIENT_KANA'] = $pat['�եꥬ��'];
  $params['PATIENT_KANJI'] = $pat['��'] .'��'. $pat['̾'];
  $params['PATIENT_DOB'] = mx_wareki($pat['��ǯ����']);
  $params['PATIENT_AGE'] = mx_calc_age($pat['��ǯ����']);
  $params['PATIENT_SEX'] = $pat['����'] == 'M' ? '��' : '��';
  $params['PATIENT_KUBUN'] = $pat['���ݸ���'] == '1' ? '�ܿ�' : '��²';
  $params['PATIENT_GROUP'] = $pat['��˾����'];
  if ($pat['������ʬ'] == 'I')
    $params['PATIENT_INOUT'] = '����';
  else if ($pat['������ʬ'] == 'O')
    $params['PATIENT_INOUT'] = '����';
  else
    $params['PATIENT_INOUT'] = '';

  //TODO: take them from room-patient table	
  $params['PATIENT_DEPT'] = $pat['������'];
  $params['PATIENT_ROOM'] = $pat['�¼�'];
  $params['DEDUCTIBLE'] = '';
  
  // ����ǯ����
  $params['PRESCRIPTION_DATE'] = mx_wareki($ord['����ǯ����'],
                                           array('dayofweek'=>1));
  $params['PRESCRIPTION_STARTDATE'] = mx_wareki($ord['����������']);
  // NEEDSWORK: ���������� + max(������
  $days = 0;
  foreach($meds as $m)
    $days = max($days, $m['����']);
  $days -= 1;
  if ($days< 0)
    $days = 0;
  $rx_end = date('Y-m-d',
		 strtotime(sprintf("%s +%d day", $ord['����������'], $days)));
  $params['PRESCRIPTION_ENDDATE'] = mx_wareki($rx_end);
  $params['PRESCRIPTION_ID'] = $oid;
  $params['PRESCRIPTION_NAIGAI'] = $ord['��ʬ'];
  if ($ord['����׻�'] == 1)
    $params['PRESCRIPTION_REGULAR'] = '���';
  else if ($ord['����׻�'] == 2)
    $params['PRESCRIPTION_REGULAR'] = '�׻�';
  else if ($ord['��ʬ'] == '�׻�' or $ord['��ʬ'] == '���') {
    # for old rx app that usese kubun for teiki/rinji
    $params['PRESCRIPTION_REGULAR'] = $ord['��ʬ'];
  }
  else
    $params['PRESCRIPTION_REGULAR'] = '';
    
  
  
  // �ݸ����ֹ�
  // ���ݸ��Ծڵ��桦�ֹ�
  $params['INSURER_NUMBER:%8s'] = $pat['�ݸ����ֹ�'];
  $params['INSURED_KIGO']=$pat['���ݸ��Լ�Ģ�ε���'];
  $params['INSURED_NUMBER']=$pat['���ݸ��Լ�Ģ���ֹ�'];
  
  // ���ŵ��ؽ���ϡ�̾��
  // �����ֹ桢�ݸ����̾
  
  $idata = mx_get_install_data();
  $params['HOSPITAL_NAME'] = $idata['HOSPITAL_NAME'];
  $params['HOSPITAL_ADDR'] = $idata['HOSPITAL_ADDR'];
  $params['HOSPITAL_TEL'] = $idata['HOSPITAL_TEL'];
  $params['CORPORATION_NAME'] = $idata['CORPORATION_NAME'];
  
  $params['DOCTOR'] = $doc['lname'] .'��'.$doc['fname'];
  
  // ����
  // preformatted body for shohousen.ods 10-20-2014 added 1 to set_body last para
$params['BODY'] = implode("\n", set_body($meds, $shots, $ord["��ȯ��"],1)). "\n";


 

  // individual lines
  $a = set_body($meds, $shots, $ord["��ȯ��"], 1);

  for($i=0; $i < count($a); $i++)
    $params[sprintf("LINE%04d", $i)] = $a[$i];

$b="";
for($i=0; $i < count($a); $i++){
   $b =$b."\n". $a[$i];
//print "<br>". $a[$i]."\n";

}

  // ����
  // comment in or append if you want to print a comment from rx
#$params['COMMENT'] = $ord['Comment'];
  $params['COMMENT'] = '';
  $params['COMMENT'] = $pat['����']."\n";
  if($params['WELFARE_NUMBER3'] and $params['WELFARE_RECIPIENT3']) {
    $params['COMMENT'] .= '������ô���ֹ�: ' . $pat['������ô���ֹ�3']."\n";
    $params['COMMENT'] .= '������ô���Ťμ�����ֹ�: ' . $pat['������ô���Ťμ�����ֹ�3'] . "\n";
  }

  // ��ȯ�ʲ�
  if ($ord['��ȯ��'] == 1) {
    $params['GENERIC_OK'] = '��';
    $params['GENERIC_DR'] = '';
  }else{
    $params['GENERIC_OK'] = '�Բ�';
    $params['GENERIC_DR'] = $params['DOCTOR'];
  }
  
  // ʴ��
  if ($ord['funsai'] == 1)
    $params['FUNSAI'] = 'ʴ��';
  else
    $params['FUNSAI'] = '';

  $rand = rand(0,100000000);
  $pdf_path = sprintf("/tmp/%s_%d.pdf", $template, $rand);
  $params['PDF_PATH'] = $pdf_path;
  $params['TEMPLATE_DIR'] = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
  $params['TEMPLATE'] = $template;
// insert rx-info into orderdb 10-24-2014

$con = mx_db_connect();

$otype="rx";
$odate=$ord['����ǯ����'];
$ptid=$ord['����'];

$ocont="RX\n".$b;
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


//  }
}
*/

//
  print '<frameset rows="55, *" noresize border="0">
         <frame src="print_inhospshot.php?top=1" name="top_frame" scrolling="no">
         <frame src="print_inhospshot.php?bottom=1&';
  if($shots) printf("shots=%d&",$shots);
  if ($oid) printf("oid=%d",$oid);
  else printf("rpid=%d",$rpid);
  print '" name="bottom_frame" ></frameset>';
/*
    print '
<SCRIPT LANGUAGE="JavaScript">
 window.open("print_inhosp.php?' . $id .
      '/generated.pdf","","width=640,height=640");
</SCRIPT>';
  */


}



?>
