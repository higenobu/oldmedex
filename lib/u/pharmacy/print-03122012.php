<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
$_lib_u_pharmacy_print_body = array();

/*
preformatted Rx layout

No | ����̾��                      | ���� | ����ñ��
   | ����̾��                      | ���� | ����ñ��
   | ����̾��                      | ���� | ����ñ��
   | (�Զ����ؼ�) ����¾������
   |                          ��ˡ | ���� | ��ʬ/��ʬ
   | �嵭����  ��ˡ              |      |
----------------------------------------------------

 */

function set_body($meds, $hr, $generic_ok, $tab=NULL)
{
  global $_mx_simple_rx_label;
 
//0526-2011--------------------------------------------------
//$tab=null;
//

  $buff = array();
  $tsv = array();
//  if ($hr)
 // $buff[] = "------------------------------------------------------------";
    
  if(!$meds) {
    $_ = "����䵤ޤ������䵤ξܺ����Ƥ�����ޤ���";
    if ($tab)
       $buff[] = "\t" . $_;
    else
       $buff[] = $_;
    return $buff;
  }
  /* ���Ƥ�ɽ�� */
 	 $c = 1;
	$break=1;
	$icount=0;
 	 $newline = true;
  foreach ($meds as $med) {
    	$header = "  ";
	
	if ($icount>=19 ) {
	 
     	 $buff[] = sprintf("%s", '##------------------');

     	 $icount=0;
	
   	 }
        $icount++;
    if ($newline) {
	 
      $header = sprintf("%d", $c);
	
      $newline = false;
	
    	}


	

    // ����̾ ����
    $medname = trim($med['�쥻�ץ��Ż����������ƥ������̾']);
    $unit = $med['old����ñ��'] ? $med['old����ñ��'] : $med['����ñ��'];
	
    if (strlen($medname) > 46) {$medname = substr($medname, 0, 46);}
 
       if($tab){
           $buff[] = sprintf("%s\t%s\t%s\t%s",$header,$medname,$med['����'],$unit);
		}     
	else {
           $buff[] = sprintf("%2s %-46s %2s %s",$header,$medname,$med['����'],$unit);
   	}

    // Generic
    if(!$_mx_simple_rx_label and $generic_ok != 0 and $med['generic_ok'] == 0) {
	$icount++;

       if($tab){
          $buff[] = "\t�ʢ���ȯ�������ѹ��Բġ�";}
       else {
          $buff[] = "   �ʢ���ȯ�������ѹ��Բġ�";
		}
   	 }

    // ���ߤ���������(��ˡʬ��)
    $com = '';
    $com0 = trim($med['��ˡʬ��']);
    $com1 = trim($med['����¾������']);
    if ($com0 == '' && $com1 == ''){ $aaa=0;}
    else {
	    $com .= $tab ? "\t" : "  ";
	    if ($com0 != '')
		    $com .= " ({$com0})";
	    if ($com1 != '')
		    $com .= " {$com1}";
	    $buff[] = $com;
    	}

    // ��ˡ + ����
    $s = '';
    $h = '';
    if ($med['��ˡ'] && $med['��ˡ'] != '-') {
      $s = $s . $med['��ˡ'] . ' ';
      $h ='';
    }
    if ($med['�����ˡ'] && $med['�����ˡ'] != '-')
      $s = $s . $med['�����ˡ'] . ' ';
    
    if ($med['�굻'] && $med['�굻'] != "-")
      $s = $s . $med['�굻'] . ' ';

    if ($s != '') {
      $ippo = '';
      $nissu = '';
      if ($med['����'] == 1) $ippo='�嵭������';
      $u = $tab ? "\t" : "";
      if($med['�굻'])
	$nissu = sprintf("%2s${u}��ʬ",$med['����']);
      elseif ($med['����'] && is_null($med['����']))
		$nissu = sprintf("%2s${u}��ʬ",$med['����']);
     	 elseif ($med['����'] &&  $med['����'] == 0)
			$nissu = sprintf("%2s${u}��ʬ",$med['����']);
     		 elseif ($med['����'] &&  $med['����'] == 1)
			$nissu = sprintf("%2s${u}��ʬ",$med['����']);
      //elseif (!$med['����'])
      //  $nissu = sprintf("����");
	
      if ($tab) {
           $buff[] = sprintf("%s\t%s\t%s", $h, $s, $nissu);
		$icount++;
	   	if ($ippo != '')
	      		 $buff[] = sprintf("\t%s", $ippo);
		
     		 }
      else	{
		$icount++;           
		$buff[] = sprintf("   %12s   %31s %s", $ippo, $s, $nissu);
   		}

	}


	if ($s != '' || $med['��ʬ'] == '��') {
    	 $buff[] = "------------------------------------------------------------";
     	$c++;
	$icount++;
    	 $newline = true;
   	 }
// end if 


}
  return $buff;
}

function dump_hidden($meds){
  $count = 0;
  foreach($meds as $med) {
	$kh = "med".$count;
	foreach($med as $k => $v){
		if($k=='����ñ��' || $k=='��ˡ' || $k=='�굻'
		   || $k=='accept' || $k=='�����ˡ')
			continue;
		print '<INPUT TYPE="HIDDEN" NAME="'.$kh.$k.'" VALUE="'.$v.'">';
	}
	$count++;
  }
/*
 print '<input type="hidden" name="i��Ͽ��" value="'.$ord['��Ͽ��'].'">';
 print '<input type="hidden" name="i����������" value="'.$ord["����������"].'">';
 print '<input type="hidden" name="i��ȯ��"  value="'.$ord['��ȯ��'] . '">';
 print '<input type="hidden" name="i��ʬ" value="'.$ord['��ʬ'] . '">';
*/
}
?>
