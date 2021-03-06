<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

$ord_array =  array(
    "区分" => array('入院','変更','停止（外泊）','停止（外出）',
		    '停止（検査）','再開','絶食','退院'),
    "保険" => array('特別加算','非加算'),
    "実行時" => array('朝','昼','夕'),
    "食種" => array('-','常食 ��','常食 ��','常食 ��','嚥下��','嚥下��',
		    '嚥下��','ピューレ食','あんかけ食',
		    '軟菜一口大（トロミつき）','三分粥食',
		    '五分粥食','七分粥食','全粥食','米飯食','流動食',
		    '濃厚流動（経口）','流動食（治療）','ｴﾈﾙｷﾞｰｺﾝﾄﾛｰﾙ1000',
		    'ｴﾈﾙｷﾞｰｺﾝﾄﾛｰﾙ1400','ｴﾈﾙｷﾞｰｺﾝﾄﾛｰﾙ1800','ﾀﾝﾊﾟｸｺﾝﾄﾛｰﾙ1600',
		    'ﾀﾝﾊﾟｸｺﾝﾄﾛｰﾙ1800','脂質ｺﾝﾄﾛｰﾙ1500','脂質ｺﾝﾄﾛｰﾙ1800',
		    '塩分ｺﾝﾄﾛｰﾙ1000','塩分ｺﾝﾄﾛｰﾙ1400','塩分ｺﾝﾄﾛｰﾙ1800',
		    '易消化三分粥食','易消化五分粥食','易消化七分粥食',
		    '易消化全粥食','易消化米飯食','濃厚流動（経管）','絶食'),
    "主食" => array('-','パン','米飯','全粥','七分粥','五分粥','三分粥','パン粥',
		    '粥ピューレ','ゼリー'),
    "適応疾患" => array('-','糖尿病','脂肪肝','高度肥満症','痛風','鉄欠乏性貧血',
			'腎疾患・糖尿病性腎炎','肝疾患','急性肝炎・膵臓疾患',
			'高脂血症','腎疾患','心臓疾患',
			'潰瘍（胃潰瘍・十二指腸）',
			'クローン病・潰瘍性大腸炎など','PEG・経鼻'),
    "適応疾患2" => array('糖尿病','脂肪肝','高度肥満症','痛風','鉄欠乏性貧血',
			'腎疾患・糖尿病性腎炎','肝疾患','急性肝炎・膵臓疾患',
			'高脂血症','腎疾患','心臓疾患',
			'潰瘍（胃潰瘍・十二指腸）',
			'クローン病・潰瘍性大腸炎など','PEG・経鼻'),
    "副菜" => array('-','常菜','軟菜一口大','軟菜一口大（とろみ付き）',
		    '一口大','あんかけ','ピューレ','ゼリー'),
    "アレルギーの確認" => array('-','牛乳','卵','そば','サバ'),
    "時" => array('-','7','8','9','10','11','12','13','14','15','16','17','18',
		  '19','20','21','22','23','24','1','2','3','4','5','6'),
    "種類" => array('-','2.0','1.5','1.0','糖質調整流動（DM用）'),
    "水分・間水" => array('-','有'),
    "速度" => array('-','30','45','60'));

$ins = array('流動食（治療）','ｴﾈﾙｷﾞｰｺﾝﾄﾛｰﾙ1000','ｴﾈﾙｷﾞｰｺﾝﾄﾛｰﾙ1400',
	     'ｴﾈﾙｷﾞｰｺﾝﾄﾛｰﾙ1800','ﾀﾝﾊﾟｸｺﾝﾄﾛｰﾙ1600','ﾀﾝﾊﾟｸｺﾝﾄﾛｰﾙ1800',
	     '脂質ｺﾝﾄﾛｰﾙ1500','脂質ｺﾝﾄﾛｰﾙ1800','塩分ｺﾝﾄﾛｰﾙ1000','塩分ｺﾝﾄﾛｰﾙ1400',
	     '塩分ｺﾝﾄﾛｰﾙ1800','易消化三分粥食','易消化五分粥食','易消化七分粥食',
	     '易消化全粥食','易消化米飯食','濃厚流動（経管）');

function print_select($array,$key,$currval,$onchange) {
  global $__mx_formi_dek;
  global $ord_array;

  if ($onchange) 
    $onchange = 'OnChange="this.form.submit();"';
  else $onchange = "";
  printf("<select %s %s name=\"i%s\">\n",$__mx_formi_dek,$onchange,$key);
  foreach ($ord_array[$array] as $val)
    printf("<option %s value=\"%s\">%s\n",
	   ($val == $currval ? "selected" : ""),$val,$val);
  print "</select>\n";
}
function get_measure($pid,$type) {
  $con = mx_db_connect();
  $mes = pg_fetch_assoc(pg_query($con,
				 'select "'.$type.'"
                                  from "バイタルデータ表"
                                  where "Superseded" is NULL and
                                  "患者" = '."'$pid'"));
  return $mes ? $mes[$type] : "";
}

function get_patient_meal($pid) {
  if (!ereg("^[0-9]+$",$pid)) return FALSE;

  $con = mx_db_connect();
  $res = pg_query($con, 'select P."ObjectID", P."姓", P."名"
     from 食事箋 as M, 患者台帳 as P
     where M."Superseded" is NULL and
           P."Superseded" is NULL and 
           P."ObjectID" = M."患者" and
           P."患者ID" = '."'$pid'".
           'order by M."ObjectID"')
      or die('pg_query => '. pg_last_error());
  if (pg_num_rows($res) &&
      ($pat = pg_fetch_assoc($res)))
    pg_free_result($res);
  return $pat;
}

function get_meal_history($pid) {
  $con = mx_db_connect();

  return (pg_fetch_all(pg_query($con,
				  'select * from "食事箋"
                                   where "Superseded" is NULL and
                                         "患者" = '."'$pid'".
                                   'order by "ObjectID"')));
}

function get_meal_new_updates($search) {
  $con = mx_db_connect();

  $str = 'select M."ObjectID", M."記録日", M."記録時間", P."姓", P."名"
         from "食事箋" as M
              join  "患者台帳" as P 
              on P."ObjectID" = M."患者" and
                P."Superseded" is NULL
         where 
         M."Superseded" is NULL and ';
  switch ($search) {
  case '1' : $str = $str . ' M."実行日" >= '."'today' and ".
	                   ' M."実行日" <= '."'tomorrow' ";
    break;
  case '2' : $str = $str . ' M."栄養士記録" is NULL ';
    break;
  }
  $str = $str . ' order by M."ObjectID"';
  return pg_fetch_all(pg_query($con,$str));
}

function get_meal_order($oid) {
  $con = mx_db_connect();
  return (pg_fetch_assoc(pg_query($con,
				  'select  * from "食事箋"
                                   where "ObjectID" = '. "'$oid'")));
}

function insert_meal_order ($var) {

  foreach ($var as $key => $val)
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      if ($val == '-') $val = "";
      if (($key == "処方日" || $key == "実行日" || $key == "再開日") && 
	  check_date($key,mb_convert_kana($val,'a','EUC-JP'))) return;
      if ($key == "濃厚流動総熱量" || $key == "量0" || $key == "量1" || 
	  $key == "量2" || $key == "量3" || $key == "量4" ||
	  $key == "間水量0" || $key == "間水量1" || $key == "間水量2" ||
	  $key == "間水量3" || $key == "間水量4") {
	$val = mb_convert_kana($val,'a','EUC-JP');
	if ($val && !ereg("^[0-9]+$",$val)) {
	  print '<font color="red">{$key}に数字を入力してください。</font>';
	  return FALSE;        
	}
      }
      $ins[$key]=$val;
    }
  $ins['CreatedBy'] = $var['u'];
  $str = make_insert_str("食事箋",$ins,$oid);

  $ret = true;
  $con = mx_db_connect();
  pg_query($con,"begin");
  pg_query($con,$str) or $ret = false;
  pg_query($con, "commit;");
  return $ret;

}

function update_meal_order ($var) {

  foreach($var as $key => $val)
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      if ($val == '-') $val = "";
      if (($key == "処方日" || $key == "実行日" || $key == "再開日") && 
	  check_date($key,mb_convert_kana($val,'a','EUC-JP'))) return;
      if ($key == "濃厚流動総熱量" || $key == "量0" || $key == "量1" || 
	  $key == "量2" || $key == "量3" || $key == "量4" ||
	  $key == "間水量0" || $key == "間水量1" || $key == "間水量2" ||
	  $key == "間水量3" || $key == "間水量4") {
	$val = mb_convert_kana($val,'a','EUC-JP');
	if ($val && !ereg("^[0-9]+$",$val)) {
	  print '<font color="red">{$key}に数字を入力してください。</font>';
	  return FALSE;
	}
      }
      $array[$key]=$val;
    }
  $array['CreatedBy'] = $var['u'];
  $array['act'] = $var['oid'];

  $ret = true;
  if (diff_contents("食事箋",$array)) {
    make_update_str("食事箋",$array,$upstr,$insstr);
    $con = mx_db_connect();
    pg_query($con,"begin");
    pg_query($con,$insstr) or $ret = false;
    pg_query($con,$upstr) or $ret = false;
    pg_query($con, "commit;");
  }
  return $ret;
}

function print_meal_detail($ord,&$done) {

    $name = get_emp_name($ord['記録者']);
    $room = get_pat_room($ord['患者']);
    print "<table>
           <tr><th nowrap>食事箋ID<td nowrap>{$ord['ObjectID']}
               <th nowrap>病室名<td nowrap>{$room['病室名']}";
    foreach($ord as $k => $v) {
      if ($k == "CreatedBy" || $k == "ObjectID" ||
	  $k == "Superseded" || $k == "ID" ||
	  $k == "患者") continue;
      if (($k == "時0" || $k == "時1" || $k == "時2" ||
	  $k == "時3" || $k == "時4" || 
	  $k == "種類0" || $k == "種類1" || $k == "種類2" ||
	  $k == "種類3" || $k == "種類4" ||
	  $k == "量0" || $k == "量1" || $k == "量2" ||
	  $k == "量3" || $k == "量4" || $k == "間水量0" ||
	  $k == "間水量1" ||$k == "間水量2" ||$k == "間水量3" ||
	  $k == "間水量4" || $k == "速度" || $k == "速度その他") && $v) {
	$tbl[$k] = $v;
	continue;
      }
      if (($k == "朝食種名" || $k == "朝主食" || $k == "朝副菜" ||
	   $k == "補助食" ||
	   $k == "昼食種名" || $k == "昼主食" || $k == "昼副菜" ||
	   $k == "夕食種名" || $k == "夕主食" || $k == "夕副菜") && $v) {
	$btbl[$k] = $v;
	continue;
      }
      if ($v && !($col++ % 2)) print '<tr>'; 
      if ($k == '記録者')
	print "<th nowrap>{$k}<td>{$name['lname']}&nbsp;{$name['fname']}\n";
      elseif ($k == '記録日' || $k == '記録時間') {
	$time_tbl[$k] = $v;
	if (count($time_tbl) == 2) {
	  print "<th nowrap>記録日時<td nowrap>".
	    disp_day_time($time_tbl['記録日'],$time_tbl['記録時間']);
	  $col-=2;
	}
      }
      elseif ($k == '特別指示' && $v)
	print "<th nowrap><font color=red>{$k}</font>
               <td nowrap><font color=red>{$v}</font>\n";
      elseif ($k == '栄養士記録' && $v) {
	print "<th nowrap>栄養士記録済み<td>\n";
	$done = 1;
      }
      elseif ($v)
	print "<th nowrap>{$k}<td nowrap>{$v}\n";
    }
    if (count($btbl)) {
      print "<tr><td colspan=4>
                 <table frame=border border=1><tr><td>
                 <th align=center>食種名
                 <th align=center>主食
                 <th align=center>副菜";
      if ($btbl['朝食種名'] || $btbl['朝主食'] || $btbl['朝副菜'])
	print "<tr><th>朝
                 <td align=center>{$btbl['朝食種名']}
                 <td align=center>{$btbl['朝主食']}
                 <td align=center>{$btbl['朝副菜']}";
      if ($btbl['昼食種名'] || $btbl['昼主食'] || $btbl['昼副菜'])
	print "<tr><th>昼
                 <td align=center>{$btbl['昼食種名']}
                 <td align=center>{$btbl['昼主食']}
                 <td align=center>{$btbl['昼副菜']}";
      if ($btbl['夕食種名'] || $btbl['夕主食'] || $btbl['夕副菜'])
	print "<tr><th>夕
                 <td align=center>{$btbl['夕食種名']}
                 <td align=center>{$btbl['夕主食']}
                 <td align=center>{$btbl['夕副菜']}";
      if ($btbl['補助食'])
	print "<tr><th align=center>補助食
                 <td align=left colspan=3>{$btbl['補助食']}";
      print "</table>";
    }

    if (count($tbl)) {
      print '<tr><td colspan="4">
                 <table border="1"><tr><th align="center">時間
                            <th align="center">種類
                            <th align="center">量
                            <th align="center">水分<br>間水量';
      if ($tbl['時0'] || $tbl['種類0'] || $tbl['量0'] || $tbl['間水量0'])
	print "<tr><td align=center>".($tbl['時0']?$tbl['時0']:"-")."時
                   <td align=center>".($tbl['種類0']?$tbl['種類0']:"-")."
                   <td align=center>".($tbl['量0']?$tbl['量0']:"-")."ml
                   <td align=center>".($tbl['間水量0']?$tbl['間水量0']:"-")."ml";
      if ($tbl['時1'] || $tbl['種類1'] || $tbl['量1'] || $tbl['間水量1'])
	print "<tr><td align=center>".($tbl['時1']?$tbl['時1']:"-")."時
                   <td align=center>".($tbl['種類1']?$tbl['種類1']:"-")."
                   <td align=center>".($tbl['量1']?$tbl['量1']:"-")."ml
                   <td align=center>".($tbl['間水量1']?$tbl['間水量1']:"-")."ml";
      if ($tbl['時2'] || $tbl['種類2'] || $tbl['量2'] || $tbl['間水量2'])
	print "<tr><td align=center>".($tbl['時2']?$tbl['時2']:"-")."時
                   <td align=center>".($tbl['種類2']?$tbl['種類2']:"-")."
                   <td align=center>".($tbl['量2']?$tbl['量2']:"-")."ml
                   <td align=center>".($tbl['間水量2']?$tbl['間水量2']:"-")."ml";
      if ($tbl['時3'] || $tbl['種類3'] || $tbl['量3'] || $tbl['間水量3'])
	print "<tr><td align=center>".($tbl['時3']?$tbl['時3']:"-")."時
                   <td align=center>".($tbl['種類3']?$tbl['種類3']:"-")."
                   <td align=center>".($tbl['量3']?$tbl['量3']:"-")."ml
                   <td align=center>".($tbl['間水量3']?$tbl['間水量3']:"-")."ml";
      if ($tbl['時4'] || $tbl['種類4'] || $tbl['量4'] || $tbl['間水量4'])
	print "<tr><td align=center>".($tbl['時4']?$tbl['時4']:"-")."時
                   <td align=center>".($tbl['種類4']?$tbl['種類4']:"-")."
                   <td align=center>".($tbl['量4']?$tbl['量4']:"-")."ml
                   <td align=center>".($tbl['間水量4']?$tbl['間水量4']:"-")."ml";
      if ($tbl['速度'] || $tbl['速度その他'])
	print "<tr><th align=center>速度
                   <td align=center>{$tbl['速度']}
                   <th align=center>速度その他
                   <td align=center>{$tbl['速度その他']}";
      print "</table>";
    }
}

$no_disp = array('ObjectID','Superseded','CreatedBy','記録日','記録時間');

?>