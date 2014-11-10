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
  $buff = array();
  $tsv = array();
 // if ($hr)
 //   $buff[] = "------------------------------------------------------------";
    
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
  $newline = true;
  foreach ($meds as $med) {
    $header = "  ";
    if ($newline) {
      $header = sprintf("%d", $c);
      $newline = false;
    }

    // ����̾ ����
    $medname = trim($med['�쥻�ץ��Ż����������ƥ������̾']);
    $unit = $med['old����ñ��'] ? $med['old����ñ��'] : $med['����ñ��'];
    $LEN=70;
    if (strlen($medname) >36 ) { $medname = substr($medname, 0, 36); }
       if($tab)
         { $buff[] = sprintf("%s\t%s\t%s\t%s",$header,$medname,$med['����'],$unit);}
       else
         {  $buff[] = sprintf("%2s %36s %s%s",$header,$medname,$med['����'],$unit);}

    
    // Generic
/*    if( $generic_ok != 0 and $med['generic_ok'] == 0) {
// if(!$_mx_simple_rx_label and $generic_ok != 0 and $med['generic_ok'] == 0) {
       if($tab)
          $buff[] = "\t�ʢ���ȯ�������ѹ��Բġ�";
       else
          $buff[] = "   �ʢ���ȯ�������ѹ��Բġ�";
    }
*/

//05-12-2012 if 1 then fuka, 0 is ka	
	if($med['generic_ok'] == 1) {
       if($tab)
          $buff[] = "\t�ʢ���ȯ�������ѹ��Բġ�";
       else
          $buff[] = "   �ʢ���ȯ�������ѹ��Բġ�";
    }


    // ���ߤ���������(��ˡʬ��)
    $com = '';
    $com0 = trim($med['��ˡʬ��']);
    $com1 = trim($med['����¾������']);
    if ($com0 == '' && $com1 == '')
	    ;
    else {
	    $com .= $tab ? "\t" : "  ";
	    if ($com0 != '')
		    $com .= "({$com0})";
	    if ($com1 != '')
		    $com .= " {$com1}";
	    $buff[] = $com;
    }

    // ��ˡ + ����
    $s = '';
    $h = '';
    if ($med['��ˡ'] && $med['��ˡ'] != '-') {
//0413-2012
      $s = "..." . $med['��ˡ'] . ' ';
      $h ='';
    }
    if ($med['�����ˡ'] && $med['�����ˡ'] != '-')
      $s = $s . $med['�����ˡ'] . ' ';
    
    if ($med['�굻'] && $med['�굻'] != "-")
      $s = $s . $med['�굻'] . ' ';

    if ($s != '') {
      $ippo = '';
      $nissu = '';
//0413-2012
      if ($med['����'] == 1) $ippo='...�嵭������';
      $u = $tab ? "\t" : "";
      if($med['�굻'])
	$nissu = sprintf("(%s${u})��ʬ",$med['����']);
      elseif ($med['����'] && is_null($med['����']))
	$nissu = sprintf("(%s${u})��ʬ",$med['����']);
      elseif ($med['����'] &&  $med['����'] == 0)
	$nissu = sprintf("(%s${u})��ʬ",$med['����']);
      elseif ($med['����'] &&  $med['����'] == 1)
	$nissu = sprintf("(%s${u})��ʬ",$med['����']);
      //elseif (!$med['����'])
      //  $nissu = sprintf("����");
      if ($tab) {
           $buff[] = sprintf("%s\t%s\t%s", $h, $s, $nissu);
	   if ($ippo != '')
	       $buff[] = sprintf("\t%s", $ippo);
      }
      else
           $buff[] = sprintf("   %12s   %31s %s", $ippo, $s, $nissu);
    }
   
if ($s != '' || $med['��ʬ'] == '��') {
      $buff[] = "--------------------------------------- ";
      $c++;
      $newline = true;
    }


  }

  if($newline != true) {
      $buff[] = "--------------------------------------- ";
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
//0328-2012
 print '<input type="hidden" name="i��Ͽ��" value="'.$ord['��Ͽ��'].'">';
 print '<input type="hidden" name="i����������" value="'.$ord["����������"].'">';
 print '<input type="hidden" name="i��ȯ��"  value="'.$ord['��ȯ��'] . '">';
 print '<input type="hidden" name="i��ʬ" value="'.$ord['��ʬ'] . '">';

}
?>
