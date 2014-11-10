<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

$_lib_u_pharmacy_common_tr = array(0 => '', 1 => '定期', 2 => '臨時', 3=> '退院時');
$_lib_u_pharmacy_common_tr_short = array(0 => '', 1 => '定', 2 => '臨', 3=> '退');

function get_teiki_rinji($i) {
  global $_lib_u_pharmacy_common_tr;
  return $_lib_u_pharmacy_common_tr[$i];
}

/* do not display these for history */
$no_disp = array("ObjectID","Superseded","CreatedBy");

function list_pharmacists($varname,$def) {

  global $__mx_formi_dek;

  $con = mx_db_connect();
  $res = pg_query($con, '
  select E."ID", E."姓", E."名" from
          "職員台帳" as E,
          "職種一覧表" as C
          where
          C."Superseded" is NULL and
          E."Superseded" is NULL and
          C."ID" = E."職種" and
          C."職種" = \'薬剤師\'');
  printf("<select %s name=\"%s\">\n",$__mx_formi_dek,$varname);

  print '<option ';
  if (!$def || $def == 0) echo "selected ";
  print "value=\"\">-\n";
  
  while ($row = pg_fetch_assoc($res)) {
    echo "<option ";
    if ($def && $def == $row['ID']) echo "selected ";
    printf("value=\"%d\">%s %s\n",
           $row['ID'],$row['姓'],$row['名']);
  }
  printf("</select>\n<p>");

}

function get_main_doctor($pid) {
    $con = mx_db_connect();
    $str = 'select * 
            from  "職員台帳" 
            where
            "Superseded" is NULL and
            "ObjectID" = (
                SELECT z1."職員"
                FROM "患者担当職員データ" as z1
                     JOIN "患者担当職員" as z0
                     ON z1."患者担当職員" = z0."ObjectID" AND
                        z0."Superseded" IS NULL
                     JOIN "担当役割" as z3
                     ON z1."担当役割" = z3."ObjectID" AND    
                        z3 ."Superseded" IS NULL
                WHERE z0."患者" = '.  mx_db_sql_quote($pid) .
                      ' and z3."担当役割" = \'主治医\')';
    return (pg_fetch_assoc(pg_query($con,$str)));
}

function get_bloods($pid) {
  $con = mx_db_connect();
  $str = 'select O."ObjectID" as oid, O."日数", O."調剤薬剤師", O."調剤年月日",
                 O."注射", P."姓", P."名", P."住所0",P."住所1", P."住所2", 
                 P."住所3", P."住所4"       
          from   "薬剤処方箋" as O, "患者台帳" as P
          where O."Superseded" is NULL and
                P."Superseded" is NULL and
                O."患者" = P."ObjectID" and
                O."患者" = ' .  mx_db_sql_quote($pid);
  $res = pg_query($con,$str);
  $index = 0;
  while ($ord = pg_fetch_assoc($res)) {
    if ($ord['注射'])
    $str = 'select B."製造番号", C."ObjectID", C."用量", C."注射用法",
                   U."用量単位", D."レセプト電算処理システム医薬品名"
            from "血液生物由来製品使用記録" as B, "薬剤処方箋内容" as C, 
                 "Medis医薬品マスター" as D,
                 "処方箋用量単位" as U
            where B."Superseded" is NULL and
                  D."Superseded" is NULL and
                  U."Superseded" is NULL and
                  B."処方箋" = '."'{$ord['oid']}'".' and 
                  C."薬剤" = D."ID" and
                  C."用量単位" = U."ID" and
                  B."薬剤" = C."薬剤" and
                  C."薬剤処方箋" = '."'{$ord['oid']}'";
    else
    $str = 'select B."製造番号", C."ObjectID", C."用量", U."用量単位",
                   M."用法", D."レセプト電算処理システム医薬品名"
            from "血液生物由来製品使用記録" as B, "薬剤処方箋内容" as C, 
                 "Medis医薬品マスター" as D, "処方箋用法" as M, 
                 "処方箋用量単位" as U
            where B."Superseded" is NULL and
                  D."Superseded" is NULL and
                  M."Superseded" is NULL and
                  U."Superseded" is NULL and
                  B."処方箋" = '."'{$ord['oid']}'".' and 
                  C."薬剤" = D."ID" and
                  C."用量単位" = U."ID" and
                  C."用法" = M."ID" and
                  B."薬剤" = C."薬剤" and
                  C."薬剤処方箋" = '."'{$ord['oid']}'";
    $medres = pg_query($con,$str);
    while ($med = pg_fetch_assoc($medres)) {
      $ret[$index++] = $ord + $med;
  }
  }
  return($ret);
}

function search_drugs($title,&$ym) {
  global $__mx_formi_dek;
  if ($_POST['ym']) $ym = $_POST['ym'];
  $ym = mb_convert_kana($ym,'a','EUC-JP');

  if (!$ym)
    print "<h3>$title".'</h3>
           <table border=0>
           <tr><td nowrap>受払年月
               <td nowrap><input type="text" name="ym" '
      .$__mx_formi_dek.'> (yyyy-mm)
                   <button class="plain" type="submit" value="検索">
                   <img src="/images/use-qbe.png"></button>
                   </table>';
  else {
    $con = mx_db_connect();
    $str = 'select D."ID", D."受払", D."受払年月日", D."受払数量", D."薬剤師",
            D."薬剤", D."残数量", D."備考", D."処方箋", M."レセプト電算処理システム医薬品名"
            from "麻毒管理" as D, 
                 "Medis医薬品マスター" as M
            where
             D."Superseded" is NULL and
             M."Superseded" is NULL and
             D."薬剤" = M."ID" and
             D."受払年月日" like ' . "'{$ym}%' 
             order by D.\"受払年月日\", D.\"ID\" asc"; 
    if (($drugs = pg_fetch_all(pg_query($con,$str))))
      return ($drugs);
    else {
      print "該当する項目がありません。<br>\n" .
	'<input type="submit" value="戻る">';
      return false;
    }
  }
}

function search_patient($title,&$ym) {
  global $uri;
  global $__mx_formi_dek;
  global $_mx_patient_id_zeropad;

  $pid = mb_convert_kana($_REQUEST['PID'],'n','EUC-JP');
  $kana = mb_convert_kana($_REQUEST['kana'],'K','EUC-JP');
  $ym = mb_convert_kana($_REQUEST['YM'],'a','EUC-JP');

  if (!$pid && !$kana) {
    if ($title) print "<h3>${title}</h3>";
    print '<form action="'.$uri.'" method="post">
           <table border="0">
           <tr><td>患者ID</td>
               <td><input type="text" name="PID" '.$__mx_formi_dek.'></td>
               <td><button class="plain" type="submit" value="検索">
                   <img src="/images/pt_select.png"></button></td>
           </tr>
           <tr><td nowrap>フリガナ</td>
               <td><input type="text" name="kana" '.$__mx_formi_dek.'></td>
               <td> </td>
           <tr><td nowrap>対象年月</td>
               <td nowrap><input type="text" name="YM" '.$__mx_formi_dek.'></td>
               <td> (yyyy-mm オプション)</td></tr>
           </table></form>';
  } else {
    if ($pid && $_mx_patient_id_zeropad > 0)
	$pid = mx_zeropad($pid, $_mx_patient_id_zeropad);
    
    $con = mx_db_connect();
    $pat = FALSE;
    if ($ym) {
      $str = 'select distinct P.*
              from 薬剤処方箋 as O, 患者台帳 as P
              where
                    P."ObjectID" = O."患者" and
                    O."処方年月日" like '."'$ym%'".' and
                    O."Superseded" is NULL and 
                    P."Superseded" is NULL ';
      if ($pid)
	$str = $str . 'and P."患者ID" = '."'$pid'";
      if ($kana && $kana != "*")
	$str = $str . 'and P."フリガナ" like '."'%{$kana}%'";
    }
    else {
      $str =  'select * 
               from "患者台帳"
               where "Superseded" is NULL ';
      if ($pid)
	$str = $str . ' and "患者ID" = '."'$pid'";
      if ($kana && $kana != "*")
	$str = $str . ' and "フリガナ" like '."'%{$kana}%'";
    }
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
             <table><tr><td colspan=4>患者を選択してください。</td></tr>
                    <tr><th>姓名</td><th>フリガナ</th><th>生年月日</th><th>性別</th>\n";
      while ($pat = pg_fetch_assoc($res)) {
	printf("<tr><td nowrap><button value=\"%s\" name=\"PID\">
                %s %s</button></td><td nowrap>%s</td><td nowrap>%s</td><td nowrap>%s</td>\n",
	       $pat['患者ID'],$pat['姓'],$pat['名'],
	       $pat['フリガナ'],$pat['生年月日'],
	       ($pat['性別'] == 'M' ? "男" : 
		($pat['性別'] == 'F' ? "女" : "")));
      }
      print "</table></form>\n";
    }
  }
}

function get_pharm_order ($oid, $shots=NULL) {
  $con = mx_db_connect();
  if($shots == 1)
    $str = 'select "ObjectID" as oid, 
                 "処方年月日", "処方開始日", "患者", "後発品",
                 "日数", "区分", "定期臨時", "停止日", "停止医", "記録者", "薬剤記録者",
                 "調剤薬剤師", "調剤年月日", "看護師実施", "看護記録者",
                 "Comment"
  from  "注射処方箋"
  where "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID"';
  else
    $str = 'select "ObjectID" as oid, 
                 "処方年月日", "処方開始日", "注射", "患者", "後発品",
                 "日数", "区分", "定期臨時", "停止日", "停止医", "記録者", "薬剤記録者",
                 "調剤薬剤師", "調剤年月日", "看護師実施", "看護記録者",
                 "Comment", "funsai"
  from  "薬剤処方箋"
  where "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID"';

  return pg_fetch_assoc(pg_query($con,$str));
}

function get_nurse_history ($pid,$ym) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "処方年月日", "区分", 
         "看護師実施", "看護記録者"
  from "薬剤処方箋"
  where "患者" = ' . mx_db_sql_quote($pid) . ' and
        "Superseded" is NULL  and 
        "注射" is not NULL and
        "調剤年月日" is not NULL and
        "停止医" is NULL';

  if ($ym) $str = $str . " and \"処方年月日\" like '{$ym}%' ";
  $str = $str . ' order by "ObjectID"';

  return pg_fetch_all(pg_query($con,$str));
}

function get_history ($pid,$ym,$case) {
  $con = mx_db_connect();
  $str = 'select "ID" as oid, "処方年月日", "区分", "停止日"
  from "薬剤処方箋"
  where "患者" = ' . mx_db_sql_quote($pid) . ' and
        "Superseded" is NULL ';

  switch ($case) {
  case 0 : $str = $str . ' and "注射" is NULL ';
    break;
  case 1 : $str = $str . ' and "注射" is not NULL ';
    break;
  case 2 : $str = $str . ' and "調剤薬剤師" is not NULL ';
    break;
  }

  if ($ym) $str = $str . " and \"処方年月日\" like '{$ym}%' ";
  $str = $str . 'order by "ObjectID" desc';

  return pg_fetch_all(pg_query($con,$str));
}

function get_curr_ord($table,$oid,$shots=NULL) {
  $con = mx_db_connect();
  if ($table == "RP")
    $str = '
  select "CreatedBy", "記録者", "RP名"
  from "RP"
  where "ObjectID" = ' . "'{$oid}'";
  else if($shots)
    $str = '
  select "CreatedBy", "処方年月日", "処方開始日", "日数", "記録者",
         "患者", "区分", "停止日", "停止医", "調剤薬剤師", 
         "調剤年月日", "看護師実施", "看護記録者",
         "薬剤記録者", "後発品", "Comment"
  from "注射処方箋"
  where "ObjectID" = ' . "'{$oid}'";
  else
    $str = '
  select "CreatedBy", "処方年月日", "処方開始日", "日数", "記録者",
         "患者", "区分", "停止日", "停止医", "調剤薬剤師", 
         "調剤年月日", "注射", "看護師実施", "看護記録者",
         "薬剤記録者", "後発品", "Comment", "funsai"
  from "薬剤処方箋"
  where "ObjectID" = ' . "'{$oid}'";

  return pg_fetch_assoc(pg_query($con,$str));
}

function update_pharm_order($var, $shots=NULL) {
  global $mx_authenticate_current_user;

  $oid = $var['oid'];
  if ($var["iRP名"]) $rp = 1;
  /* get the current order from database */
  if ($rp) {
    $current_order = get_curr_ord("RP",$oid);
    $ordinsstr = make_insert_str("RP",$current_order,$oid);
  } else if($shots) {
    $current_order = get_curr_ord("注射処方箋",$oid,1);
    $ordinsstr = make_insert_str("注射処方箋",$current_order,$oid);
  } else {
    $current_order = get_curr_ord("薬剤処方箋",$oid);
    $ordinsstr = make_insert_str("薬剤処方箋",$current_order,$oid);
  }
  /* get the current contents from database */
  if ($current_order['注射'] || $shots )
    $cur_meds = get_meds($oid,1);
  else $cur_meds = get_meds($oid,0);

  if ($cur_meds) 
    foreach ($cur_meds as $item)
      $cur_id[$item['medid']] = true;
  /* 
  if the key name in $var starts with "i", they are the actual order
  values. if the key name starts with "med.." they are the content(s)
  of the order. If the requested content ID matches with the current
  contents in the DB, then it's a update request, otherwise it's
  either delete or insert.
  */
  $diff = false;
  $indx = 0;
  $medications = array();
  $days_max = 1;
  foreach(array('inoclaim', 'isetflag', 'ifunsai') as $k)
    if (is_null($var[$k]))
      $var[$k] = 'off';

  foreach ($var as $key => $val) {
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      switch ($key) {
      case "noclaim" :
        $val = $val == 'on' ? 1 : 0;
        break;
      case "setflag" :
        $val = $val == 'on' ? 1 : 0;
        break;
      case "funsai" :
        $val = $val == 'on' ? 1 : 0;
        break;
      case "日数" :
	$val = mb_convert_kana($val,'n','EUC-JP');
	break;
      case "処方年月日" :
      case "処方開始日" :
      case "停止日" :
        $val = mx_ui_japanese_date($val);
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      }
      if ($val != $current_order[$key]) $diff = true;
      if (is_null($val) || !strlen($val)) $ordval[$indx] = "NULL";
      else $ordval[$indx] = "'" . $val . "'";
      $ordkey[$indx] = '"' . $key . '"';
      $ordup[$indx] = $ordkey[$indx] . " = " . $ordval[$indx++];
    } elseif (ereg("^med[-]*[0-9]",$key)) {
      $prefix = ereg_replace("^(med[-]*[0-9]*).*","\\1",$key);
      $rest =  ereg_replace("^med[-]*[0-9]*(.*)","\\1",$key);
      switch ($rest) {
      case "unitid" : $rest = "用量単位"; break;
      case "freqid" : $rest = "用法"; break;
      case "medis" : $rest = "薬剤"; break;
      case "shapeid" : $rest = "投与形態"; break;
      case "methodid" : $rest = "手技"; break;
      case "dosageid" : $rest = "注射用法"; break;
      case "日数":
	      $val = mb_convert_kana($val,'n','EUC-JP');
	      if ($days_max + 0 < $val * 1)
		      $days_max = $val;
	      break;
      case "用法分類": $val = mb_convert_kana($val,'n','EUC-JP'); break;
      case "用量" : $val = mb_convert_kana($val,'n','EUC-JP'); break;
      }
      if ($rest == "medid") {
	if ($cur_id[$val])
	  $cur_id[$val] = false;
	continue;
      } elseif ($rest == "レセプト電算処理システム医薬品名"
		|| $rest == "レセプト電算処理システムコード（１）"
	        || $rest == "薬価基準収載医薬品コード")
	  continue;
      if ($rp) $medications[$prefix]["用法"] = $var['rp-freqid'];
      $medications[$prefix][$rest] = $val;
    }
  }

  // $var['PID'] has 患者ID and $var['pid'] has 患者.ObjectID
  $it = mx_get_current_reception_info($mx_authenticate_current_user,
				      $var['pid']);
  if ($it) {
	  $insinfo = trim($it['保険組合せ']);
	  if ($insinfo)
		  $ordup[] = "\"病院使用レセコン保険情報\" = '$insinfo'";
	  $deptinfo = trim($it['受診科目コード']);
	  if ($deptinfo)
		  $ordup[] = "\"病院使用レセコン受診科情報\" = '$deptinfo'";
  }

  $ordup[] = '"日数" = ' . $days_max;

  $cur=current($medications);
  foreach ($medications as $med) {
    if (!array_key_exists('薬剤',$med)) {
      print '<h1><blink><font color="red">薬剤を指定してください。
           </font></blink></h1><br>';
      return false;
    }
  }

  $con = mx_db_connect();
  if ($rp)
    $ordupstr = "update \"RP\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";
  else if($shots)
    $ordupstr = "update  \"注射処方箋\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";
  else
    $ordupstr = "update  \"薬剤処方箋\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";

  $ret = true;
  pg_query($con,"begin");
  pg_query($con,$ordinsstr) || $ret = false;
  if ($rp)
    pg_query($con,"update \"薬剤処方箋内容\" 
   set \"RPID\" = currval('\"RP_ID_seq\"')
   where \"RPID\" = '{$oid}'") ||$ret = false; 
  else if($shots){
    pg_query($con,"update \"注射処方箋内容\" 
   set \"注射処方箋\" = currval('\"注射処方箋_ID_seq\"')
   where \"注射処方箋\" = '{$oid}'") ||$ret = false; 
  }else
    pg_query($con,"update \"薬剤処方箋内容\" 
   set \"薬剤処方箋\" = currval('\"薬剤処方箋_ID_seq\"')
   where \"薬剤処方箋\" = '{$oid}'") ||$ret = false; 
  pg_query($con,$ordupstr) || $ret = false;

  if ($medications)
    foreach ($medications as $item) {
      if ($rp)
	$item['RPID'] = $oid;
      else if($shots) {
	$item['注射処方箋'] = $oid;
	// XXX
	unset( $item['投与形態'] );
	$instr =  make_insert_str("注射処方箋内容",$item,false,"");
      }
      else{
	$item['薬剤処方箋'] = $oid;
	$instr =  make_insert_str("薬剤処方箋内容",$item,false,"");
      }
      pg_query($con, $instr) ||	$ret = false;
    }
  pg_query($con,"commit");
  return $ret;
}

function insert_pharm_order($var, &$new_oid_return, $shots=NULL) {
  global $mx_authenticate_current_user;

  /* 
  if the key name in $var starts with "i", they are the actual order
  values. if the key name starts with "med.." they are the content(s)
  of the order.
   */
  $indx = 0;
  $medications = array();
  foreach ($var as $key => $val) {
    if (ereg("^i.*",$key)) {
      switch ($key) {
      case "inoclaim" :
        $val = $val == 'on' ? 1 : 0;
        break;
      case "isetflag" :
        $val = $val == 'on' ? 1 : 0;
        break;
      case "ifunsai" :
        $val = $val == 'on' ? 1 : 0;
        break;
      case "i日数" :
	$val = mb_convert_kana($val,'n','EUC-JP');
	break;
      case "i処方年月日" :
      case "i処方開始日" :
      case "i停止日" : 
        $val = mx_ui_japanese_date($val);
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      case 'iRP名' : $rp=1;
      }
      if (is_null($val) || !strlen($val)) $ordval[$indx] = "NULL";
      else $ordval[$indx] = "'" . $val . "'";
      $ordkey[$indx++] = '"' . substr($key,1) . '"';
    } elseif (ereg("^med[-]*[0-9]",$key)) {
      $prefix = ereg_replace("^(med[0-9]*).*","\\1",$key);
      $rest =  ereg_replace("^med[0-9]*(.*)","\\1",$key);
      if ($rest == "medid" || $rest == "レセプト電算処理システム医薬品名"
	  || $rest == "レセプト電算処理システムコード（１）"
	  || $rest == "薬価基準収載医薬品コード"
	  )
	continue;
      if ($rest == "methodid")
	$injection = true;
      if ($rp) $medications[$prefix]['freqid'] = $var['rp-freqid'];
      $medications[$prefix][$rest] = $val;
    }
  }

  /*
  $medications contains several contents. Separate them into
  individual "insert strings".
   */
  $strindx = 0;
  $cur=current($medications);
  if ( $cur ) $oshpid = (int) $cur['shapeid'];
  $days_max = 1;
  foreach ($medications as $med) {
    if (!array_key_exists('medis',$med)) {
      print '<h1><blink><font color="red">薬剤を指定して下さい。
             </font></blink></h1><br>';
      return false;
    }
    $nshpid = (int) $med['shapeid'];
    if ($oshpid != $nshpid) {
      if ((1 <= $oshpid && $oshpid <= 8 && $nshpid > 8) ||
	  ($oshpid > 8 && $nshpid <= 8)) {
	print '<h1><blink><font color="red">処方箋に注射と他の投与形態を混在することはできません。
             </font></blink></h1><br>';
	return false;
      }
      $oshpid = $nshpid;
    }
    $med["用量"] = mb_convert_kana($med["用量"],'a','EUC-JP');
    $days = mb_convert_kana($med["日数"],'a','EUC-JP');
    $med["日数"] = $days;
    if ($days_max + 0 < $days * 1)
	    $days_max = $days;
    $med["用法分類"] = mb_convert_kana($med["用法分類"],'a','EUC-JP');
    $indx = 0;
    foreach ($med as $mkey => $mval) {
      if ($mkey == "製造番号") {
	$blood[$strindx][$mkey] = $mval;
	continue;
      }
      if ($mkey == "受払年月日" || $mkey == "受払数量" ||
	  $mkey == "残数量" || $mkey == "備考") {
	$narcotic[$strindx][$mkey] = $mval;
	continue;
      }
      if ($mkey == "unitid") $mkey = "用量単位";
      elseif ($mkey == "shapeid") $mkey = "投与形態";
      elseif ($mkey == "medis")	$mkey = "薬剤";
      elseif ($mkey == "freqid") $mkey = "用法";
      elseif ($mkey == "methodid") $mkey = "手技";
      elseif ($mkey == "dosageid") $mkey = "注射用法";

      // HACK
      if($injection && $mkey == "投与形態")
	  continue;

      if (is_null($mval) || !strlen($mval))
	$medval[$strindx][$indx] = "NULL"; 
      else $medval[$strindx][$indx] = "'" . $mval . "'";
      $medkey[$strindx][$indx++] = '"' . $mkey . '"';
    }
    $strindx++;
  }

  // $var[i患者] has 患者.ObjectID
  $it = mx_get_current_reception_info($mx_authenticate_current_user,
				      $var['i患者']);
  if ($it) {
	  $insinfo = trim($it['保険組合せ']);
	  if ($insinfo) {
		  $ordkey[] = '"病院使用レセコン保険情報"';
		  $ordval[] = "'$insinfo'";
	  }
	  $deptinfo = trim($it['受診科目コード']);
	  if ($deptinfo) {
		  $ordkey[] ='"病院使用レセコン受診科情報"';
		  $ordval[] = "'$deptinfo'";
	  }
  }

  $ordkey[] = '"日数"';
  $ordval[] = $days_max;

  $ret = true;
  $con = mx_db_connect();
  pg_query($con, "begin;");
  if ($injection)
    $str = 'insert into "注射処方箋" ("CreatedBy",' . implode(',',$ordkey) . 
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  elseif ($rp)
    $str = 'insert into "RP" ("CreatedBy",' . implode(',',$ordkey) .
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  else 
    $str = 'insert into "薬剤処方箋" ("CreatedBy",' . implode(',',$ordkey) . 
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";

  pg_query($con,$str) or $ret = false;
  for ($i = 0, $c = count($medkey); $i < $c; $i++) {
    if ($rp)
      $str =  'insert into "薬剤処方箋内容" ("RPID",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"RP_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    else{
      $tbl = "薬剤";
      if($injection) 
	$tbl = "注射";
      $str =  'insert into "'.$tbl.'処方箋内容" ("'.$tbl.'処方箋",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"".$tbl."処方箋_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    }
    pg_query($con,$str) or $ret = false;

    $medis = $medval[$i][array_search('"薬剤"',$medkey[$i],true)];

    if ($ret && $new_oid_return == 'please') {
      $new_oid_return = NULL;
      $sth = pg_query($con, "select currval('\"".$tbl."処方箋_ID_seq\"') as it");
      if ($sth) {
	$array = pg_fetch_all($sth);
	if (is_array($array) && count($array) == 1)
		$new_oid_return = $array[0]['it'];
      }
    }
  }
  pg_query($con, "commit;");
  return $ret;
}

function print_hidden_vars($prefix,$var) {
  $k = array_keys($var);
  for ($i=0, $c=count($var);$i < $c;$i++) {
    printf("<input type=\"hidden\" name=\"%s%s\" value=\"%s\">\n",
	   $prefix,$k[$i],$var[$k[$i]]);
  }
}

function check_drug($id) {
  $con = mx_db_connect();
  $drug = pg_fetch_assoc(pg_query($con,
  'select "麻薬", "毒薬", "輸血用血液", "特定生物由来製品"
   from "管理薬品マスター"
   where "ObjectID" = ' . $id));
  if ($drug["麻薬"] == "Y") $ret = 1;
  if ($drug["毒薬"] == "Y") $ret = $ret + 2;
  if ($drug["輸血用血液"] == "Y") $ret = $ret + 4;
  if ($drug["特定生物由来製品"] == "Y") $ret = $ret + 8;

  return $ret ? $ret : false;
}

function record_pharm($var,$isNurse) {
  /* 
  if the key name in $var starts with "i", they are the actual order
  values. if the key name starts with "med.." they are either nacortic
  or blood related drug content(s) of the order.
  */
  foreach ($var as $key => $val) {
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      switch ($key) {
      case "調剤年月日" :
        $val = mx_ui_japanese_date($val);
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      case "調剤薬剤師" :
	$pharmacist = $val;
	break;
      }
      $ordstr[$key] = $val;
    } elseif (ereg("^bmed[-]*[0-9]",$key)) {
      $prefix = ereg_replace("^bmed[-]*([0-9]*).*","\\1",$key);
      $rest =  ereg_replace("^bmed[-]*[0-9]*(.*)","\\1",$key);
      if ($rest == "bloodid") {
	$bmed[$prefix]['act'] = $val;
	continue;
      }
      $bmed[$prefix][$rest] = $val;
    } elseif (ereg("^dmed[-]*[0-9]",$key)) {
      $prefix = ereg_replace("^dmed[-]*([0-9]*).*","\\1",$key);
      $rest =  ereg_replace("^dmed[-]*[0-9]*(.*)","\\1",$key);
      if ($rest == "drugid") {
	$dmed[$prefix]['act'] = $val;
	continue;
      }
      $dmed[$prefix][$rest] = $val;
    }
  }
  $ordstr['CreatedBy'] = $var['u'];

  $ret = true;
  $con = mx_db_connect();
  pg_query($con, "begin;");

  if (diff_contents("薬剤処方箋",$ordstr)) {
    make_update_str("薬剤処方箋",$ordstr,$upstr,$insstr);
    pg_query($con,$insstr) or $ret = false;
    $str = 'select "薬剤処方箋", "投与形態", "手技", "薬剤", "日数", "一包",
              "用法分類", "用量単位", "用量", "用法", "注射用法", "その他コメント"
            from  "薬剤処方箋内容"
            where "薬剤処方箋" = ' . "'{$var['oid']}'";

    $meds = pg_fetch_all(pg_query($con,$str));
    $str = 'update "薬剤処方箋内容" 
           set "薬剤処方箋" = currval(\'"薬剤処方箋_ID_seq"\')
           where "薬剤処方箋" = ' . "'{$var['oid']}'";
    pg_query($con,$str) || $ret = false;
    if ($meds)
      foreach ($meds as $med)
	pg_insert($con,"薬剤処方箋内容",$med) || $ret = false;
    pg_query($con,$upstr) or $ret = false;
  }

  if ($bmed) {
    $btable = "血液生物由来製品使用記録";
    foreach ($bmed as $med) {
      $med['CreatedBy'] = $var['u'];
      if ($med['act']) {
	if (diff_contents($btable,$med)) {
	  make_update_str($btable,$med,&$upstr,&$insstr);
	  pg_query($con,$insstr) or $ret = false;
	  pg_query($con,$upstr) or $ret = false;
	}
      }	else {
	$str = make_insert_str($btable,$med,false);
	pg_query($con,$str) or $ret = false;
      }
    }
  }
  if ($dmed) {
    $dtable = "麻毒管理";
    foreach ($dmed as $med) {
      $med['薬剤師'] = $pharmacist;
      $med['CreatedBy'] = $var['u'];
      $med['受払'] = "O";
      $med["受払年月日"] = mb_convert_kana($med["受払年月日"],'a','EUC_JP');
      $med["受払数量"] = mb_convert_kana($med["受払数量"],'n','EUC_JP');
      $med["残数量"] = mb_convert_kana($med["残数量"],'n','EUC_JP');
      if ($med['act']) {
	if (diff_contents($dtable,$med)) {
	  make_update_str($dtable,$med,&$upstr,&$insstr);
	  pg_query($con,$insstr) or $ret = false;
	  pg_query($con,$upstr) or $ret = false;
	}
      } else {
	$str =  make_insert_str($dtable,$med,false);
	pg_query($con,$str) or $ret = false;
      }
    }
  }
  pg_query($con, "commit;");
  return $ret;
}

function get_drug($table,$medis,$oid) {
  $con = mx_db_connect();
  $str = 'select * from '.$table.
    ' where "Superseded" is NULL and
            "処方箋" = ' .mx_db_sql_quote($oid). ' and
            "薬剤" = ' .mx_db_sql_quote($medis);
  return(pg_fetch_assoc(pg_query($con,$str)));
}

function get_drug_with_id($id) {
  $con = mx_db_connect();
  $str = 'select d.*, m."レセプト電算処理システム医薬品名"
          from  "麻毒管理" as d, "Medis医薬品マスター" as m
          where d."Superseded" is NULL and
                m."Superseded" is NULL and
                d."薬剤" = m."ObjectID" and
                d."ObjectID" = '. mx_db_sql_quote($id);
  return(pg_fetch_assoc(pg_query($con,$str)));
}

function get_unitid($unit_name) {
  $con = mx_db_connect();
  return pg_fetch_assoc(pg_query($con,'select "ObjectID" as id from "処方箋用量単位"
                                       where "Superseded" is NULL and
                                       用量単位 = '."'$unit_name'"));
}

function get_prescription_list() {

  $con = mx_db_connect();
  $list = FALSE;
  $res = pg_query($con, 'select O."ObjectID" as oid,
     O."区分", O."処方年月日", O."処方開始日", O."患者" as pid
     from
          "薬剤処方箋" as O
     where
           O."調剤薬剤師" is NULL and
           O."調剤年月日" is NULL and
           O."停止日" is NULL AND
           O."Superseded" is NULL
           order by O."ObjectID"')
    or die('pg_query => '. pg_last_error());
  if (pg_num_rows($res) &&
     ($list = pg_fetch_all($res)))
    pg_free_result($res);
  return $list;
}

class phoney_ppa {
	function phoney_ppa($u, $patient_ID, $patient_ObjectID) {
		$this->u = $u;
		$this->patient_ID = $patient_ID;
		$this->patient_ObjectID = $patient_ObjectID;
	}
	function appbar_filter($path, $name, $pid) {
	  if (trim($pid) == '') {
		  /*
		   * Do not show applications that set encounter to
		   * "finished" and such when seeing no patient.
		   */
		  if (is_encounter_state_application($path))
			  return 0;
	  } else {
		  /*
		   * Do not show applications that switch encounter
		   * mode between Inpatient and Outpatient when
		   * already seeing a patient.
		   */
		  if ($path == 'u/everybody/encounter-mode-flip.php')
			  return 0;
	  }
	  return 1;
	}
	function edit_in_progress() {
		global $dbaction, $action;

		if ($dbaction == 'dbpreview')
			return 1;
		return 0; /* NEEDSWORK */
	}
}
?>