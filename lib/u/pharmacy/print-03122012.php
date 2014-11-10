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
 
//0526-2011--------------------------------------------------
//$tab=null;
//

  $buff = array();
  $tsv = array();
//  if ($hr)
 // $buff[] = "------------------------------------------------------------";
    
  if(!$meds) {
    $_ = "薬剤箋または注射箋の詳細内容がありません";
    if ($tab)
       $buff[] = "\t" . $_;
    else
       $buff[] = $_;
    return $buff;
  }
  /* 内容の表示 */
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


	

    // 薬剤名 用量
    $medname = trim($med['レセプト電算処理システム医薬品名']);
    $unit = $med['old用量単位'] ? $med['old用量単位'] : $med['用量単位'];
	
    if (strlen($medname) > 46) {$medname = substr($medname, 0, 46);}
 
       if($tab){
           $buff[] = sprintf("%s\t%s\t%s\t%s",$header,$medname,$med['用量'],$unit);
		}     
	else {
           $buff[] = sprintf("%2s %-46s %2s %s",$header,$medname,$med['用量'],$unit);
   	}

    // Generic
    if(!$_mx_simple_rx_label and $generic_ok != 0 and $med['generic_ok'] == 0) {
	$icount++;

       if($tab){
          $buff[] = "\t（↑後発医薬品変更不可）";}
       else {
          $buff[] = "   （↑後発医薬品変更不可）";
		}
   	 }

    // 飲みかたコメント(用法分類)
    $com = '';
    $com0 = trim($med['用法分類']);
    $com1 = trim($med['その他コメント']);
    if ($com0 == '' && $com1 == ''){ $aaa=0;}
    else {
	    $com .= $tab ? "\t" : "  ";
	    if ($com0 != '')
		    $com .= " ({$com0})";
	    if ($com1 != '')
		    $com .= " {$com1}";
	    $buff[] = $com;
    	}

    // 用法 + 日数
    $s = '';
    $h = '';
    if ($med['用法'] && $med['用法'] != '-') {
      $s = $s . $med['用法'] . ' ';
      $h ='';
    }
    if ($med['注射用法'] && $med['注射用法'] != '-')
      $s = $s . $med['注射用法'] . ' ';
    
    if ($med['手技'] && $med['手技'] != "-")
      $s = $s . $med['手技'] . ' ';

    if ($s != '') {
      $ippo = '';
      $nissu = '';
      if ($med['一包'] == 1) $ippo='上記、一包化';
      $u = $tab ? "\t" : "";
      if($med['手技'])
	$nissu = sprintf("%2s${u}日分",$med['日数']);
      elseif ($med['日数'] && is_null($med['頓服']))
		$nissu = sprintf("%2s${u}日分",$med['日数']);
     	 elseif ($med['日数'] &&  $med['頓服'] == 0)
			$nissu = sprintf("%2s${u}日分",$med['日数']);
     		 elseif ($med['日数'] &&  $med['頓服'] == 1)
			$nissu = sprintf("%2s${u}回分",$med['日数']);
      //elseif (!$med['日数'])
      //  $nissu = sprintf("屯用");
	
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


	if ($s != '' || $med['区分'] == '外') {
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
