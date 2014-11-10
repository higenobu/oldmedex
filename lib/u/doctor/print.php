<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
$_lib_u_pharmacy_print_body = array();

/*
preformatted Rx layout

No | 薬剤名称                      | 用量 | 用量単位
   | 薬剤名称                      | 用量 | 用量単位
   | 薬剤名称                      | 用量 | 用量単位
   | (不均等指示) その他コメント
   |                          用法 | 日数 | 日分/回分
   | 上記一包化  用法              |      |
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
    $_ = "XCTの詳細内容がありません";
    if ($tab)
       $buff[] = "\t" . $_;
    else
       $buff[] = $_;
    return $buff;
  }
  /* 内容の表示 */
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
		if($k=='用量単位' || $k=='用法' || $k=='手技'
		   || $k=='accept' || $k=='注射用法')
			continue;
		print '<INPUT TYPE="HIDDEN" NAME="'.$kh.$k.'" VALUE="'.$v.'">';
	}
	$count++;
  }
/*
 print '<input type="hidden" name="i記録者" value="'.$ord['記録者'].'">';
 print '<input type="hidden" name="i処方開始日" value="'.$ord["処方開始日"].'">';
 print '<input type="hidden" name="i後発品"  value="'.$ord['後発品'] . '">';
 print '<input type="hidden" name="i区分" value="'.$ord['区分'] . '">';
*/
}
?>
