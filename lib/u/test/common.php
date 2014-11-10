<?php

/* do not display these for history */
$no_disp = array("ObjectID","Superseded","CreatedBy");

function get_test_order($id,$case){
  $con = mx_db_connect();
  $str = 'select "ID" as oid, "処方年月日", "記録者", "患者",
          "停止日", "停止医", "検査師", "検査年月日", "CreatedBy"
  from "検査処方箋"
  where ';

  switch ($case) { 
  case 0:
    $str = $str . ' "Superseded" is NULL and "患者" = '."'$id' ";
    break;
  case 1:  
    $str = $str . ' "Superseded" is NULL and "ObjectID" = '."'$id' ";
    break;
  case 2:
    $str = $str . ' "ObjectID" = '."'$id' ";
    break;
  }
  
  $str = $str . ' order by "ObjectID"';
  if ($case)
    return pg_fetch_assoc(pg_query($con,$str));
  else
    return pg_fetch_all(pg_query($con,$str));

}

function get_tp($rec,$oid) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "SET名", "記録者", "CreatedBy"
          from "TSET"
          where ';
  if ($rec) $str = $str . ' "記録者" = '."'$rec'".' and "Superseded" is NULL ';
  if ($oid) $str = $str . ' "ObjectID" = '."'$oid' ";
  if ($rec)
    return pg_fetch_all(pg_query($con,$str));
  else
    return pg_fetch_assoc(pg_query($con,$str));
}

function get_tp_tests($id,$tp) {
  $con = mx_db_connect();
  $str = 'select A."ObjectID" as medid, A."用量",
                 A."検査" as medis,
                 A."その他コメント",
                 A."投与形態" as shapeid,
                 A."用法" as freqid,
                 B."項目名 (略式名)" as "項目名(略式名)",
                 B."単位名",
                 C."投与形態", D."用法"
  from  "検査処方箋内容" as A, "検体検査マスター" as B,
        "処方箋投与形態" as C, "処方箋用法" as D
  where
   C."Superseded" is NULL and
   D."Superseded" is NULL and
   A."用法" = D."ObjectID" and
   A."検査" = B."ObjectID" and
   A."投与形態" = C."ObjectID" and ';
  if ($tp)
    $str = $str . 'A."TSETID" = ' . mx_db_sql_quote($id);
  else
    $str = $str . 'A."検査処方箋" = ' . mx_db_sql_quote($id);

  return pg_fetch_all(pg_query($con,$str));
}

function show_tp($rec,$bname,$ind) {
  $tp = get_tp($rec,"");
  print "<table><tr><th>SET\n";
  foreach ($tp as $val) {
    if ($ind)
      $meds = get_tp_tests($val['oid'],1);
    else
      $meds = get_tp_tests($val['oid'],0);

    printf ("<tr><td><button name=\"%s\" value=\"%s\">SETID %s %s</button>\n",
	    $bname,$val['oid'],$val['oid'],$val['SET名']);

    foreach ($meds as $mval) {
      printf("<td>%s",$mval["項目名(略式名)"]);
    }
  }
  print "</table>\n";
}

function show_tp_tests($array) {
  foreach($array as $test)
    printf("<tr><td colspan=4 align=left>%s %s %s %s %s %s",
	   $test["項目名(略式名)"],$test['用法'],$test['投与形態'],
	   $test['用量'],$test["単位名"],$test['その他コメント']);
}
function insert_test_order($var) {
  /* 
  if the key name in $var starts with "i", they are the actual order
  values. if the key name starts with "med.." they are the content(s)
  of the order.
   */
  $indx = 0;
  foreach ($var as $key => $val) {
    if (ereg("^i.*",$key)) {
      switch ($key) {
      case "i処方年月日" :
      case  "i検査年月日" :
      case "i停止日" : 
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      case 'iSET名' : 
	if (!$val)   {
	  print '<h1><blink><font color="red">SET名を指定してください。
           </font></blink></h1><br>';
	  return true;
	}
	$tp=1;
      }
      if (is_null($val) || !strlen($val)) $ordval[$indx] = "NULL";
      else $ordval[$indx] = "'" . $val . "'";
      $ordkey[$indx++] = '"' . substr($key,1) . '"';
    } elseif (ereg("^med[-]*[0-9]",$key)) {
      $prefix = ereg_replace("^(med[-]*[0-9]*).*","\\1",$key);
      $rest =  ereg_replace("^med[-]*[0-9]*(.*)","\\1",$key);
      if ($rest == "medid" || $rest == "項目名(略式名)" ||
	  $rest == "単位名") continue;
      if ($tp) $medications[$prefix]['freqid'] = $var['tp-freqid'];
      $medications[$prefix][$rest] = $val;
    }
  }

  /*
  $medications contains several contents. Separate them into
  individual "insert strings".
   */
  $strindx = 0;
  /*  $cur=current($medications);*/
  $oshpid = (int) $cur['shapeid'];
  foreach ($medications as $med) {
    $nshpid = (int) $med['shapeid'];

    $med["用量"] = mb_convert_kana($med["用量"],'a','EUC-JP');
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
      elseif ($mkey == "medis")	$mkey = "検査";
      elseif ($mkey == "freqid") $mkey = "用法";
      elseif ($mkey == "placeid") $mkey = "手技";
      if (is_null($mval) || !strlen($mval))
	$medval[$strindx][$indx] = "NULL"; 
      else $medval[$strindx][$indx] = "'" . $mval . "'";
      $medkey[$strindx][$indx++] = '"' . $mkey . '"';
    }
    $strindx++;
  }

  $ret = true;
  $con = mx_db_connect();
  pg_query($con, "begin;");
 
  if ($tp)
    $str = 'insert into "TSET" ("CreatedBy",' . implode(',',$ordkey) .
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  else 
    $str = 'insert into "検査処方箋" ("CreatedBy",' . implode(',',$ordkey) . 
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  pg_query($con,$str) or $ret = false;
  for ($i = 0, $c = count($medkey); $i < $c; $i++) {
    if ($tp)
      $str =  'insert into "検査処方箋内容" ("TSETID",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"TSET_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    else 
      $str =  'insert into "検査処方箋内容" ("検査処方箋",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"検査処方箋_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    pg_query($con,$str) or $ret = false;

    $medis = $medval[$i][array_search('"検査"',$medkey[$i],true)];
  }
  pg_query($con, "commit;");
  return $ret;
}

function update_test_order($var) {
  $oid = $var['oid'];
  if (array_key_exists('iSET名',$var)) {
    if ($var["iSET名"]) $tp = 1;
    else {
      print '<h1><blink><font color="red">SET名を指定してください。
             </font></blink></h1><br>';
      return true;
    }
  }

  /* get the current order from database */
  if ($tp) {
    $current_order = get_tp("",$oid);
    $ordinsstr = make_insert_str("TSET",$current_order,$oid);
  } else {
    $current_order = get_test_order($oid,1);
    $ordinsstr = make_insert_str("検査処方箋",$current_order,$oid);
  }

  /* get the current contents from database */
  if ($tp)
    $cur_meds = get_tp_tests($oid,1);
  else
    $cur_meds = get_tp_tests($oid,0);

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
  foreach ($var as $key => $val) {
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      switch ($key) {
      case "日数" :
	$val = mb_convert_kana($val,'n','EUC-JP');
	break;
      case "処方年月日" :
      case "検査年月日" :
      case "停止日" :
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
      case "medis" : $rest = "検査"; break;
      case "shapeid" : $rest = "投与形態"; break;
      case "placeid" : $rest = "手技"; break;
      case "用量" : $val = mb_convert_kana($val,'n','EUC-JP'); break;
      }
      if ($rest == "medid") {
	if ($cur_id[$val])
	  $cur_id[$val] = false;
	continue;
      } elseif ($rest == "項目名(略式名)" || $rest == "単位名")
	continue;
      if ($tp) $medications[$prefix]["用法"] = $var['tp-freqid'];
      $medications[$prefix][$rest] = $val;
    }
  }
  $cur=current($medications);

  $con = mx_db_connect();
  if ($tp)
    $ordupstr = "update \"TSET\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";
  else
    $ordupstr = "update  \"検査処方箋\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";

  $ret = true;
  pg_query($con,"begin");
  pg_query($con,$ordinsstr) || $ret = false;

  if ($tp)
    pg_query($con,"update \"検査処方箋内容\" 
    set \"TSETID\" = currval('\"TSET_ID_seq\"')
    where \"TSETID\" = '{$oid}'") ||$ret = false;
  else
    pg_query($con,"update \"検査処方箋内容\" 
    set \"検査処方箋\" = currval('\"検査処方箋_ID_seq\"')
    where \"検査処方箋\" = '{$oid}'") ||$ret = false; 
  pg_query($con,$ordupstr) || $ret = false;

  if ($medications)
    foreach ($medications as $item) {
      if ($tp)
	$item['TSETID'] = $oid;
      else
	$item['検査処方箋'] = $oid;
      $instr =  make_insert_str("検査処方箋内容",$item,false,"");
      pg_query($con, $instr) ||	$ret = false;
    }
  pg_query($con,"commit");
  return $ret;
}

?>