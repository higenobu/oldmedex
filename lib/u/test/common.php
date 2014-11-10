<?php

/* do not display these for history */
$no_disp = array("ObjectID","Superseded","CreatedBy");

function get_test_order($id,$case){
  $con = mx_db_connect();
  $str = 'select "ID" as oid, "����ǯ����", "��Ͽ��", "����",
          "�����", "��߰�", "������", "����ǯ����", "CreatedBy"
  from "���������"
  where ';

  switch ($case) { 
  case 0:
    $str = $str . ' "Superseded" is NULL and "����" = '."'$id' ";
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
  $str = 'select "ObjectID" as oid, "SET̾", "��Ͽ��", "CreatedBy"
          from "TSET"
          where ';
  if ($rec) $str = $str . ' "��Ͽ��" = '."'$rec'".' and "Superseded" is NULL ';
  if ($oid) $str = $str . ' "ObjectID" = '."'$oid' ";
  if ($rec)
    return pg_fetch_all(pg_query($con,$str));
  else
    return pg_fetch_assoc(pg_query($con,$str));
}

function get_tp_tests($id,$tp) {
  $con = mx_db_connect();
  $str = 'select A."ObjectID" as medid, A."����",
                 A."����" as medis,
                 A."����¾������",
                 A."��Ϳ����" as shapeid,
                 A."��ˡ" as freqid,
                 B."����̾ (ά��̾)" as "����̾(ά��̾)",
                 B."ñ��̾",
                 C."��Ϳ����", D."��ˡ"
  from  "�������������" as A, "���θ����ޥ�����" as B,
        "�������Ϳ����" as C, "�������ˡ" as D
  where
   C."Superseded" is NULL and
   D."Superseded" is NULL and
   A."��ˡ" = D."ObjectID" and
   A."����" = B."ObjectID" and
   A."��Ϳ����" = C."ObjectID" and ';
  if ($tp)
    $str = $str . 'A."TSETID" = ' . mx_db_sql_quote($id);
  else
    $str = $str . 'A."���������" = ' . mx_db_sql_quote($id);

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
	    $bname,$val['oid'],$val['oid'],$val['SET̾']);

    foreach ($meds as $mval) {
      printf("<td>%s",$mval["����̾(ά��̾)"]);
    }
  }
  print "</table>\n";
}

function show_tp_tests($array) {
  foreach($array as $test)
    printf("<tr><td colspan=4 align=left>%s %s %s %s %s %s",
	   $test["����̾(ά��̾)"],$test['��ˡ'],$test['��Ϳ����'],
	   $test['����'],$test["ñ��̾"],$test['����¾������']);
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
      case "i����ǯ����" :
      case  "i����ǯ����" :
      case "i�����" : 
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      case 'iSET̾' : 
	if (!$val)   {
	  print '<h1><blink><font color="red">SET̾����ꤷ�Ƥ���������
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
      if ($rest == "medid" || $rest == "����̾(ά��̾)" ||
	  $rest == "ñ��̾") continue;
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

    $med["����"] = mb_convert_kana($med["����"],'a','EUC-JP');
    $indx = 0;
    foreach ($med as $mkey => $mval) {
      if ($mkey == "��¤�ֹ�") {
	$blood[$strindx][$mkey] = $mval;
	continue;
      }
      if ($mkey == "��ʧǯ����" || $mkey == "��ʧ����" ||
	  $mkey == "�Ŀ���" || $mkey == "����") {
	$narcotic[$strindx][$mkey] = $mval;
	continue;
      }
      if ($mkey == "unitid") $mkey = "����ñ��";
      elseif ($mkey == "shapeid") $mkey = "��Ϳ����";
      elseif ($mkey == "medis")	$mkey = "����";
      elseif ($mkey == "freqid") $mkey = "��ˡ";
      elseif ($mkey == "placeid") $mkey = "�굻";
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
    $str = 'insert into "���������" ("CreatedBy",' . implode(',',$ordkey) . 
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  pg_query($con,$str) or $ret = false;
  for ($i = 0, $c = count($medkey); $i < $c; $i++) {
    if ($tp)
      $str =  'insert into "�������������" ("TSETID",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"TSET_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    else 
      $str =  'insert into "�������������" ("���������",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"���������_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    pg_query($con,$str) or $ret = false;

    $medis = $medval[$i][array_search('"����"',$medkey[$i],true)];
  }
  pg_query($con, "commit;");
  return $ret;
}

function update_test_order($var) {
  $oid = $var['oid'];
  if (array_key_exists('iSET̾',$var)) {
    if ($var["iSET̾"]) $tp = 1;
    else {
      print '<h1><blink><font color="red">SET̾����ꤷ�Ƥ���������
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
    $ordinsstr = make_insert_str("���������",$current_order,$oid);
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
      case "����" :
	$val = mb_convert_kana($val,'n','EUC-JP');
	break;
      case "����ǯ����" :
      case "����ǯ����" :
      case "�����" :
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
      case "unitid" : $rest = "����ñ��"; break;
      case "freqid" : $rest = "��ˡ"; break;
      case "medis" : $rest = "����"; break;
      case "shapeid" : $rest = "��Ϳ����"; break;
      case "placeid" : $rest = "�굻"; break;
      case "����" : $val = mb_convert_kana($val,'n','EUC-JP'); break;
      }
      if ($rest == "medid") {
	if ($cur_id[$val])
	  $cur_id[$val] = false;
	continue;
      } elseif ($rest == "����̾(ά��̾)" || $rest == "ñ��̾")
	continue;
      if ($tp) $medications[$prefix]["��ˡ"] = $var['tp-freqid'];
      $medications[$prefix][$rest] = $val;
    }
  }
  $cur=current($medications);

  $con = mx_db_connect();
  if ($tp)
    $ordupstr = "update \"TSET\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";
  else
    $ordupstr = "update  \"���������\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";

  $ret = true;
  pg_query($con,"begin");
  pg_query($con,$ordinsstr) || $ret = false;

  if ($tp)
    pg_query($con,"update \"�������������\" 
    set \"TSETID\" = currval('\"TSET_ID_seq\"')
    where \"TSETID\" = '{$oid}'") ||$ret = false;
  else
    pg_query($con,"update \"�������������\" 
    set \"���������\" = currval('\"���������_ID_seq\"')
    where \"���������\" = '{$oid}'") ||$ret = false; 
  pg_query($con,$ordupstr) || $ret = false;

  if ($medications)
    foreach ($medications as $item) {
      if ($tp)
	$item['TSETID'] = $oid;
      else
	$item['���������'] = $oid;
      $instr =  make_insert_str("�������������",$item,false,"");
      pg_query($con, $instr) ||	$ret = false;
    }
  pg_query($con,"commit");
  return $ret;
}

?>