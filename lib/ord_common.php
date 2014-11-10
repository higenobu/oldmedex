<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
//0510-2013 hayashi
//add get_meds_shot 11-01-2014
/* array_walk($_GET,'test_print') */
function test_print($item, $key)
{ echo "$key => $item<br>\n"; }

function show_buttons($pid,$action) {
  if ($pid) {
    switch ($action) {
    case "nutrition" :
      $act[1]['label']="薬剤";
      $act[1]['url']="../../u/pharmacy/pharm-order.php";
      $act[2]['label']="注射";
      $act[2]['url']="../../u/pharmacy/injection-order.php";
      break;
    case "pharm-order" :
      $act[1]['label']="注射";
      $act[1]['url']="../../u/pharmacy/injection-order.php";
      $act[2]['label']="食事";
      $act[2]['url']="../../u/nutrition/order.php";
      break;
    case "injection-order" :
      $act[1]['label']="薬剤";
      $act[1]['url']="../../u/pharmacy/pharm-order.php";
      $act[2]['label']="食事";
      $act[2]['url']="../../u/nutrition/order.php";
      break;
    }
    print '<table><tr>';
    foreach ($act as $item)
      print '<td><form method="post" action="'.$item['url'].'">
             <button type="submit" name="PID" value="'.$pid.'">'.
             $item['label']."</button></form></td>\n";
    print "</table>\n";
  }
}

function get_meds ($oid,$injection) {
  $con = mx_db_connect();
  if ($injection == 1) {
    
    $str = 'select C."ObjectID" as medid, C."用量", C."その他コメント",
                 C."薬剤" as medis,
                 C."用量単位" as unit, Y."用法" as "注射用法",
                 C."日数", C."用法分類", C."一包", C.generic_ok,
                 M."告示名称", M."レセプト電算処理システム医薬品名",
                 M."病院使用医薬品名", M."レセプト電算処理システムコード（１）",
                 M."薬価基準収載医薬品コード",
                 M."病院使用ラベル要印刷",
                 Y."ObjectID" as dosageid,
		 Y."一日当り回数",
                 I."手技", I."ID" as methodid,
		 M."包装単位単位" as "用量単位",
                 U."用量単位" as "old用量単位",
                 M."当院採用" as accept
FROM "注射処方箋内容" as C
            LEFT JOIN "注射用法" as Y
                ON C."注射用法" = Y."ObjectID" AND Y."Superseded" is NULL
            JOIN "Medis医薬品マスター" as M
                ON M."ObjectID" = C."薬剤" AND M."Superseded" IS NULL
            LEFT JOIN "注射手技" as I
                ON I."ObjectID" = C."手技" AND I."Superseded" IS NULL
	    LEFT JOIN "処方箋用量単位" as U
                ON U."ObjectID" = C."用量単位" AND U."Superseded" is NULL
            WHERE C."注射処方箋" =  ' . mx_db_sql_quote($oid) . '
            ORDER BY C."ObjectID"';
  }
  elseif  ($injection == 2) {
    $str = 'select C."ObjectID" as medid, C."用量", C."その他コメント",
                 C."投与形態" as shape, C."薬剤" as medis,
                 C."用量単位" as unit, C."用法" as freq,
                 C."日数", C."用法分類", C."一包", C.generic_ok,
                 M."告示名称", M."レセプト電算処理システム医薬品名",
                 M."病院使用医薬品名",  M."レセプト電算処理システムコード（１）",
                 M."薬価基準収載医薬品コード",
                 M."病院使用ラベル要印刷",
                 T."投与形態", T."ID" as shapeid,
                 Y."用法", Y."ID" as freqid,
		 Y."一日当り回数",
		 M."包装単位単位" as "用量単位",
                 M."当院採用" as accept
  from "薬剤処方箋内容" as C, "Medis医薬品マスター" as M,
       "処方箋投与形態" as T,
       "処方箋用法" as Y
  where C."RPID" = ' . mx_db_sql_quote($oid) . ' and
        C."投与形態" = T."ObjectID" and C."薬剤" = M."ObjectID" and 
        C."用法" = Y."ObjectID" and  
        M."Superseded" is NULL and T."Superseded" is NULL and 
        Y."Superseded" is NULL
        order by C."ObjectID"';
  }
  else {
    $str = 'select C."ObjectID" as medid, C."用量", C."その他コメント",
                 C."投与形態" as shape, C."薬剤" as medis,
                 C."用量単位" as unit, C."用法" as freq,
                 C."日数", C."用法分類", C."一包", trim(C."区分") as "区分",
                 C."頓服" as "頓服", C.generic_ok,
                 M."告示名称", M."レセプト電算処理システム医薬品名",
                 M."病院使用医薬品名", M."レセプト電算処理システムコード（１）",
                 M."薬価基準収載医薬品コード",
                 M."病院使用ラベル要印刷",
                 T."投与形態", T."ID" as shapeid,
                 Y."用法", Y."ID" as freqid,
		 Y."一日当り回数",
		 M."包装単位単位" as "用量単位",
                 U."用量単位" as "old用量単位",
                 M."当院採用" as accept
	    FROM "薬剤処方箋内容" as C
	    LEFT JOIN "処方箋用法" as Y
		ON C."用法" = Y."ObjectID" AND Y."Superseded" is NULL
	    JOIN "Medis医薬品マスター" as M
		ON M."ObjectID" = C."薬剤" AND M."Superseded" IS NULL
	    LEFT JOIN "処方箋投与形態" as T
		ON T."ObjectID" = C."投与形態" AND T."Superseded" IS NULL
	    LEFT JOIN "処方箋用量単位" as U
                ON U."ObjectID" = C."用量単位" AND U."Superseded" is NULL
	    WHERE C."薬剤処方箋" =  ' . mx_db_sql_quote($oid) . '
	    ORDER BY C."ObjectID"';
  }
  return pg_fetch_all(pg_query($con,$str));
}
//11-01-2014
function get_meds_shot ($oid,$injection) {
  $con = mx_db_connect();
  
    $str = 'select C."ObjectID" as medid, C."用量", C."その他コメント",
                 C."投与形態" as shape, C."薬剤" as medis,
                 C."用量単位" as unit, C."用法" as freq,
                 C."日数", C."用法分類", C."一包", trim(C."区分") as "区分",
                 C."頓服" as "頓服", C.generic_ok,
                 M."告示名称", M."レセプト電算処理システム医薬品名",
                 M."病院使用医薬品名", M."レセプト電算処理システムコード（１）",
                 M."薬価基準収載医薬品コード",
                 M."病院使用ラベル要印刷",
                 T."投与形態", T."ID" as shapeid,
                 Y."用法", Y."ID" as freqid,
		 Y."一日当り回数",
		 M."包装単位単位" as "用量単位",
                 U."用量単位" as "old用量単位",
                 M."当院採用" as accept
	    FROM "yakuzai-d" as C
	    LEFT JOIN "処方箋用法" as Y
		ON C."用法" = Y."ObjectID" AND Y."Superseded" is NULL
	    JOIN "Medis医薬品マスター" as M
		ON M."ObjectID" = C."薬剤" AND M."Superseded" IS NULL
	    LEFT JOIN "処方箋投与形態" as T
		ON T."ObjectID" = C."投与形態" AND T."Superseded" IS NULL
	    LEFT JOIN "処方箋用量単位" as U
                ON U."ObjectID" = C."用量単位" AND U."Superseded" is NULL
	    WHERE C."yakuzai" =  ' . mx_db_sql_quote($oid) . '
	    ORDER BY C."ObjectID"';
 
  return pg_fetch_all(pg_query($con,$str));
}


//
function show_meds($meds,$col) {
  $disp = true;
  if ($col == 44) {$col = 4; $disp = false;}
  $c=1;
  foreach ($meds as $med) {
    print '<tr><th colspan="'.$col.'" align=center><font color="green">
        処方箋内容　'.$c.'</font>';
    print "<tr><td colspan=".$col.">{$med['レセプト電算処理システム医薬品名']}&nbsp;
               {$med['用量']}{$med['用量単位']}&nbsp;";
    if ($disp)
      $med['手技'] ? print $med['手技'] : print $med['投与形態'];
    print "&nbsp;{$med['用法']}{$med['注射用法']}";
    if ($med['日数']) print "&nbsp;{$med['日数']} 日";
    if ($med['用法分類']) printf(" (%s) ",$med['用法分類']);
    if ($med['その他コメント'])
      printf('<tr><td colspan="%d">%s',$col,$med['その他コメント']);
    $c++;
  }
}

function lib_ord_common_get_doctors($pid, $type, $opt) {
//0404-2012 add userid
  $str = 'select E."ID", E."姓", E."名" , userid from 
          "職員台帳" as E,
	  "職種一覧表" as C
	  where
          C."Superseded" is NULL and
          E."Superseded" is NULL and
	  C."ID" = E."職種"
          ';
  if (!is_null($pid))
    $str .= ' and E."ID" in (SELECT z1."職員" FROM
                     "患者担当職員データ" as z1
                     JOIN "患者担当職員" as z0
                     ON z1."患者担当職員" = z0."ObjectID" AND
                        z0."Superseded" IS NULL
                     WHERE z0."患者" = ' . mx_db_sql_quote($pid) . ')';
  switch ($type) {
  case "rehab" :
    $str = $str . " and C.\"職種\" in " . enum_doctor_cat_sql() . "";
    break;
  case "pharm0" :
    $str = $str . " and C.\"職種\" in " . enum_pharmacist_cat_sql() . "";
    break;
  case "doctor" :
    $str = $str . " and C.\"職種\" in " . enum_doctor_cat_sql() . "";
    break;
  }
  $str = $str . ' order by E."ID"';
  $con = mx_db_connect();
  $rows = mx_db_fetch_all($con,$str);

  if (!$opt['enum'] && !$opt['qbeenum'])
    return $rows;

  $ret = NULL;
  $prefix = $opt['qbeenum'] ? '=' : '';
  foreach ($rows as $row) {
	$ret[$prefix.$row['ID']] = $row['姓'].$row['名'];
  }
  return $ret;
}

function list_doctors($varname,$def,$pid,$type,$rec) {
  global $__mx_formi_dek;
  $rows = lib_ord_common_get_doctors($pid, $type, NULL);
  printf("<select %s name=\"%s\">\n",$__mx_formi_dek,$varname);
  if (ereg('^.*0$',$type)) {
    print '<option ';
    if (!$def || $def == 0) echo "selected ";
    print "value=\"\">-\n";
  }
  $id = array();
  foreach($rows as $row) {
    print "<option ";
    if ($def && $def == $row['ID']) echo "selected ";
    printf("value=\"%d\">%s %s\n",
	   $row['ID'],$row['姓'],$row['名']);
    $id[$row['ID']] = 1;
  }

  if ($rec && ($rec['id'] != 0) && !$id[$rec['id']]) {
    print "<option ";
    if ($def && $def == $rec['id']) echo "selected ";
    printf("value=\"%d\">%s %s\n",
	   $rec['id'],$rec['name']['lname'],$rec['name']['fname']);
  }
  printf("</select>");
}
//0404-2012 new
function list_doctors2($varname,$def,$pid,$type,$rec) {
  global $__mx_formi_dek;
  $rows = lib_ord_common_get_doctors($pid, $type, NULL);
  printf("<select %s name=\"%s\">\n",$__mx_formi_dek,$varname);
  if (ereg('^.*0$',$type)) {
    print '<option ';
    if (!$def || $def == 0) echo "selected ";
    print "value=\"\">-\n";
  }
  $id = array();
  foreach($rows as $row) {
    print "<option ";
    if ($def && $def == $row['userid']) echo "selected ";
    printf("value=\"%d\">%s %s\n",
	   $row['userid'],$row['姓'],$row['名']);
    $id[$row['userid']] = 1;
  }

  if ($rec && ($rec['id'] != 0) && !$id[$rec['id']]) {
    print "<option ";
    if ($def && $def == $rec['id']) echo "selected ";
    printf("value=\"%d\">%s %s\n",
	   $rec['id'],$rec['name']['lname'],$rec['name']['fname']);


  }
  printf("</select>");
}
// end 2012
function get_pat_build($pid) {
  $con = mx_db_connect();
  $str = 'SELECT W.*
          FROM "病棟一覧表" as W 
               JOIN "病室一覧表" AS R
               ON W."ObjectID" = R."病棟" and
                  R."Superseded" IS NULL AND 
                  W."Superseded" IS NULL
               JOIN "病室患者表" AS P
               ON R."ObjectID" = P."病室" AND
                  P."Superseded" IS NULL
               JOIN "病室患者データ" AS D
               ON P."ObjectID" = D."病室患者表"
          WHERE D."患者" = ' . mx_db_sql_quote($pid);
  return (pg_fetch_assoc(pg_query($con,$str)));
}

function get_patient($id,$type)
{
  if (!ereg("^[0-9]+$",$id)) return FALSE;
  $id = mx_db_sql_quote($id);

  $con = mx_db_connect();

  if ($type)
    $res = pg_query($con, "select * from 患者台帳 where \"患者ID\" = $id
                         and \"Superseded\" is NULL")
      or die('pg_query => '. pg_last_error());
  else
    $res = pg_query($con, "select * from 患者台帳 where \"ObjectID\" = $id
                         and \"Superseded\" is NULL")
      or die('pg_query => '. pg_last_error());

  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;

  return $pat;
}
function get_empid($id )
{
  if (!ereg("^[0-9]+$",$id)) return FALSE;
  $id = mx_db_sql_quote($id);

  $con = mx_db_connect();

   
    $res = pg_query($con, "select * from 職員台帳 where \"userid\" = $id
                         and \"Superseded\" is NULL");
   
  if (pg_num_rows($res) && 
      ($pat = pg_fetch_array($res, PG_ASSOC)))
     pg_free_result($res);
  else
     $pat = FALSE;

  return $pat;
}

function make_insert_str($table,$array,$oid) {
  $index = 0;
  if ($table == "薬剤処方箋" && $array['RP名'])
    $seqid = 'RP_ID_seq';
  else
    $seqid = $table . '_ID_seq';
  foreach($array as $key => $val) {
    if ($key == 'act' || $key == 'ID' || $key == 'oid' ||
	$key == 'ObjectID' || $key == 'Superseded')
      continue;
    if (is_null($val) || !strlen($val)) $v[$index] = "NULL";
    else $v[$index] = "'" . $val . "'";
    $k[$index++] = '"' . $key . '"';
  }
  if ($oid)
    return 'insert into "' . $table . '" ("ID", "ObjectID",
       "Superseded", ' . implode(',',$k) . ") values ('{$oid}', 
       nextval('\"{$seqid}\"'), now()," . implode(',',$v) . ");\n";
  else
    return 'insert into "' . $table . '" (' . 
      implode(',',$k) . ") values (" . implode(',',$v) . ");\n";
}

function make_update_str($table,$array,&$upstr,&$insstr) {
  $id = $array['act'];
  $med = get_curr_cont($table,$id);
  $insstr =  make_insert_str($table,$med,$id);
  $upstr = "update \"$table\" set ";

  /* if array is size of 1 (act only), flag the '削除' */
  if (count($array) == 1) {
    $array = $med;
    $array['削除'] = 1;
  }
  foreach ($array as $k => $v)
    if ($k == "act") continue;
    elseif (is_null($v) || !strlen($v)) 
      $upstr = $upstr . ' "' . $k . '" = NULL, ';
    else 
      $upstr = $upstr . ' "' . $k . '" = \'' . ($v) . "', ";

  $upstr = substr($upstr,0,-2) . " where \"ObjectID\" = '{$id}'";
}

function get_curr_cont($table,$id) {
  $con = mx_db_connect();
  $str = 'select * from "' . $table . '" where "ObjectID" = ' . ($id);
  return pg_fetch_assoc(pg_query($con,$str));
}

function diff_contents($table,$new) {
  $curr = get_curr_cont($table,$new['act']);
  foreach ($new as $k => $v) {
    if ($k == 'act') continue;
    if ($v != $curr[$k]) return true;
  }
  return false;
}

function get_pat_room($pid) {
  $con = mx_db_connect();
  $str = 'SELECT R.*
          FROM "病室一覧表" AS R
               JOIN "病室患者表" AS P
               ON R."ObjectID" = P."病室" AND
                  R."Superseded" IS NULL AND 
                  P."Superseded" IS NULL
               JOIN "病室患者データ" AS D
               ON P."ObjectID" = D."病室患者表"
          WHERE D."患者" = ' . mx_db_sql_quote($pid);
  return (pg_fetch_assoc(pg_query($con,$str)));
}

function draw_back($uri) {
  print '<a href="'.$uri.'"><img src="/images/top_button.png"'.
	  ' align="absbottom"></a>';
}

function check_key($key,$array) {
  foreach ($array as $item) if ($item == $key) return true;
  return false;
}

function get_emp_name($empid) {
  if (!$empid) return "";
  $con = mx_db_connect();
  return (pg_fetch_assoc(pg_query($con,'
  select "姓" as lname, "名" as fname
  from "職員台帳" 
  where "Superseded" IS NULL and
        "ObjectID" = ' . mx_db_sql_quote($empid))));

}
function get_emp_name2($empid) {
  if (!$empid) return "";
  $con = mx_db_connect();
  return (pg_fetch_assoc(pg_query($con,'
  select "姓" as lname, "名" as fname
  from "職員台帳" 
  where "Superseded" IS NULL and
        "ID" = ' . mx_db_sql_quote($empid))));

}
function check_date($key,$date) {
  if (!$date) return false;
  if (ereg("[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]",$date))
    return false;
  else
    print '<p><font color="red">'."「{$key}」の書式が「{$date}」
     と指定されています。<br>日付は、「YYYY-MM−DD」の書式（例2006年
     3月1日の場合「2006-03-01」）で指定してください。</font><p>\n";
  return true;
}

function print_input($key,$size,$val) {
  global $__mx_formi_dek;
  return sprintf('<input type="text" name="i%s" %s size="%d" maxlength="%d" value="%s">',
                 $key,$__mx_formi_dek,intval($size*1.6),$size,$val);
}

function get_pat($title) {
  global $uri;
  global $__mx_formi_dek;

  $pid = mb_convert_kana($_REQUEST['PID'],'n','EUC-JP');
  $kana = mb_convert_kana($_REQUEST['kana'],'K','EUC-JP');

  if (!$pid && !$kana) {
    if ($title) print "<h3>$title</h3>";
    print "<form action=\"$uri\" method=\"post\">
           <table border=0>" .
          '<tr><td>患者ID
               <td><input type="text" name="PID" '.$__mx_formi_dek.'>
               <td><button class="plain" type="submit" value="検索">
                   <img src="/images/pt_select.png"></button>
        <tr><td>フリガナ
               <td><input type="text" name="kana" '.$__mx_formi_dek.'>
               <td>
           </table></form>';
  } else {
    $con = mx_db_connect();
    $str =  'select *
             from "患者台帳"
             where "Superseded" is NULL ';
    if ($pid)
      $str = $str . ' and "患者ID" = '."'$pid'";
    if ($kana && $kana != "*")
      $str = $str . ' and "フリガナ" like '."'%{$kana}%'";
    $res = pg_query($con,$str);
    $rescount = pg_num_rows($res);
    if ($rescount == 0) {
      print "<form action=\"$uri\" method=\"post\">";
      print '該当する患者がいません。<br><input type="submit" value="戻る">
             </form>';
    }
    elseif ($rescount == 1)
      return pg_fetch_assoc($res);
    else {
      print "<form action=\"$uri\" method=\"post\">
             <table><tr><td colspan=4>患者を選択してください。
                    <tr><th>姓名<th>フリガナ<th>生年月日<th>性別\n";
      while ($pat = pg_fetch_assoc($res)) {
        printf("<tr><td nowrap><button value=\"%s\" name=\"PID\">
                %s %s</button><td nowrap>%s<td nowrap>%s<td nowrap>%s\n",
               $pat['患者ID'],$pat['姓'],$pat['名'],
               $pat['フリガナ'],$pat['生年月日'],
               ($pat['性別'] == 'M' ? "男" :
                ($pat['性別'] == 'F' ? "女" : "")));
      }
      print "</table></form>\n";
    }
  }
}

function print_diff($table,$array,$page, $shots=NULL) {
  global $no_disp;
  if ($page-1)
    $prev= disp_day_time(substr($array[$page-1]['Superseded'],0,10),
			 substr($array[$page-1]['Superseded'],11,5));
  else $prev = "最新";
  $next= disp_day_time(substr($array[$page]['Superseded'],0,10),
		       substr($array[$page]['Superseded'],11,5));
  print "<table border=1><tr><th>変更日時<td>$prev<td>$next";
  foreach ($array[$page-1] as $key => $newval) {
    if (check_key($key,$no_disp)) continue;
    $oldval = $array[$page][$key];
    if ($newval != $oldval) {
      if ($key == "栄養士記録") {
	if ($newval) $newval = "済";
	else $newval = "無";
	if ($oldval) $oldval = "済";
	else $oldval = "無";
      } elseif ($key == "記録者" || $key == "医者" || 
		$key == "薬剤記録者" || $key == "停止医" ||
		$key == "調剤薬剤師" || $key == "看護記録者" ||
		$key == "検査師") {
	$newname = get_emp_name($newval);
	$oldname = get_emp_name($oldval);
	$newval = $newname['lname']."&nbsp;".$newname['fname'];
	$oldval = $oldname['lname']."&nbsp;".$oldname['fname'];
      } elseif ($newval == "on")
	$newval = "指定";
      elseif ($oldval == "on")
	$oldval = "指定";
      print "<tr><th>$key<td>$newval<td>$oldval";
    }
  }
  if ($table == "薬剤処方箋" || $table == "注射処方箋") {
    print "<tr><th>処方箋内容<td style=\"vertical-align: top;\"><table width=150>";
    if ($array[$page-1]['注射'] || $shots)
      show_meds(get_meds($array[$page-1]['ObjectID'],1),1);
    else
      show_meds(get_meds($array[$page-1]['ObjectID'],0),1);
    print "</table><td><table width=150>";
    if ($array[$page]['注射'] || $shots)
      show_meds(get_meds($array[$page]['ObjectID'],1),1);
    else
      show_meds(get_meds($array[$page]['ObjectID'],0),1);
    print "</table>";
  } elseif ($table == "RP") {
    print "<tr><th>処方箋内容<td><table width=150>";
    show_meds(get_meds($array[$page-1]['ObjectID'],2),1);
    print "</table><td><table width=150>";
    show_meds(get_meds($array[$page]['ObjectID'],2),1);
    print "</table>";
  } elseif ($table == "TSET") {
    print "<tr><th>処方箋内容<td><table width=150>";
    show_tp_tests(get_tp_tests($array[$page-1]['ObjectID'],1));
    print "</table><td><table width=150>";
    show_tp_tests(get_tp_tests($array[$page]['ObjectID'],1));
    print "</table>";
  } elseif ($table == "検査処方箋") {
    print "<tr><th>処方箋内容<td><table width=150>";
    show_tp_tests(get_tp_tests($array[$page-1]['ObjectID'],0));
    print "</table><td><table width=150>";
    show_tp_tests(get_tp_tests($array[$page]['ObjectID'],0));
    print "</table>";
  } 


  print '<tr><td><td><td>
         <button type="button"'. 
         "OnClick=\"window.open('print.php?";
  if($shots)
    print "shots=1&";
  print "oid={$array[$page]['ObjectID']}',
         '','width=640,height=640')\">印刷</button>
         </table>\n";
}

function get_order_history($table,$oid,$pagename,$shots=NULL) {

  $con = mx_db_connect();
  $str = 'select *
          from "'.$table.'" 
          where "ID" = '."'$oid'".
    'order by "Superseded" desc';
  $res = pg_query($con,$str);
  if (($max = pg_num_rows($res)) <= 1) return ""; /* no history */
  $hists = pg_fetch_all($res);
  $page = $_POST[$pagename];

  if (!($page-1)) $ptitle = "<font color=green>&lt;-&nbsp;最新</font>";
  else
    $ptitle = '<font color=green>&lt;-&nbsp;'.
      disp_day_time(substr($hists[$page-1]['Superseded'],0,10),
		    substr($hists[$page-1]['Superseded'],11,5)).'</font>';
  $ntitle = '<font color=red>'.
    disp_day_time(substr($hists[$page+1]['Superseded'],0,10),
		  substr($hists[$page+1]['Superseded'],11,5)).'&nbsp;-&gt;</font>';
  if (!$page)
    print '<button name="'.$pagename.'" value="'.($page+1).'">'.$ntitle.
      '</button>';
  else {
    if ($page == $max-1)
      print '<button name="'.$pagename.'" value="'.($page-1).'">'.
	$ptitle.'</button>';
    else {
      print '<button name="'.$pagename.'" value="'.($page-1).'">'.
	$ptitle.'</button>';
      print '<button name="'.$pagename.'" value="'.($page+1).'">'.
	$ntitle.'</button>';
    }
    print "<p>";
    print_diff($table,$hists,$page, $shots);
  }
}

function disp_day_time($day,$time) {
  $disp_year = seireki2wareki(substr($day,0,4));
  $disp_day = preg_replace("/-/","月",substr($day,5,10));
  $disp_day = preg_replace("/$/","日",$disp_day);
  $disp_time = preg_replace("/:/","時",substr($time,0,5));
  $disp_time = preg_replace("/$/","分",$disp_time);
  return $disp_year.$disp_day." ".$disp_time;
}

function seireki2wareki( $seireki ){
  $WAREKI  = array("平成"=>1989, "昭和"=>1926, "大正"=>1912);
  if( !ereg("^[0-9]{4}$",trim($seireki)) ) return FALSE;
  foreach( $WAREKI as $nengo => $gannen ){
    $year = $seireki - $gannen + 1;
    if( $year == 1 ) $year = "元";
    if( $seireki >= $gannen ) return "{$nengo}{$year}年";
  }
  return FALSE;
}

function list_med($varname,$def,$type) {
  global $__mx_formi_dek;

  $con = mx_db_connect();
//0510-2013 hayashi
  switch ($type) {
  case "units" : $str = 'select "ObjectID", "用量単位" as val from "処方箋用量単位"
                         where "Superseded" is NULL order by "ObjectID"';
    break;
  case "shape" : $str = 'select "ObjectID", "投与形態" as val from "処方箋投与形態"
			where "Superseded" is NULL order by "ObjectID"';
    break;
  case "freq" : $str = 'select "ObjectID", "用法" as val from "処方箋用法"
			where "Superseded" is NULL and (sortorder is null or sortorder >0 )
 order by sortorder';
    break;
  case "method" : $str = 'select "ObjectID", "手技" as val from "注射手技"
			where "Superseded" is NULL order by "ObjectID"';
    break;
  case "dosage" : $str = 'select "ObjectID", "用法" as val from "注射用法"
			where "Superseded" is NULL order by "ObjectID"';
    break;
  }

  $res = pg_query($con,$str) or die(pg_last_error());
  printf("<select %s name=\"%s\">\n",$__mx_formi_dek,$varname);
  while ($row = pg_fetch_assoc($res)) {
    echo "<option ";
    if ($def && (int) $def == $row['ObjectID']) echo "selected ";
    printf("value=\"%d\">%s\n",
	   $row['ObjectID'],$row['val']);
  }
  pg_free_result($res);
  printf("</select>");
}

?>
