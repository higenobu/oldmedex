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
  if ($hr)
    $buff[] = "------------------------------------------------------------";
    
  if(!$meds) {
    $_ = "XCT�ξܺ����Ƥ�����ޤ���";
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
    $header = "XCT  ";
    if ($newline) {
      $header = sprintf("%d", $c);
      $newline = false;
    }

   $medname = $meds['bui1'];

    $size = $meds['memo11'];
   $maisu= $meds['memo12'];
    
   $buff[] = sprintf("%2s %-46s %2s%s",$header,$medname,$maisu,$size);
   
  
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
