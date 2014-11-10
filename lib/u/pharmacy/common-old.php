<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

$_lib_u_pharmacy_common_tr = array(0 => '', 1 => '���', 2 => '�׻�', 3=> '�ౡ��');
$_lib_u_pharmacy_common_tr_short = array(0 => '', 1 => '��', 2 => '��', 3=> '��');

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
  select E."ID", E."��", E."̾" from
          "������Ģ" as E,
          "�������ɽ" as C
          where
          C."Superseded" is NULL and
          E."Superseded" is NULL and
          C."ID" = E."����" and
          C."����" = \'���޻�\'');
  printf("<select %s name=\"%s\">\n",$__mx_formi_dek,$varname);

  print '<option ';
  if (!$def || $def == 0) echo "selected ";
  print "value=\"\">-\n";
  
  while ($row = pg_fetch_assoc($res)) {
    echo "<option ";
    if ($def && $def == $row['ID']) echo "selected ";
    printf("value=\"%d\">%s %s\n",
           $row['ID'],$row['��'],$row['̾']);
  }
  printf("</select>\n<p>");

}

function get_main_doctor($pid) {
    $con = mx_db_connect();
    $str = 'select * 
            from  "������Ģ" 
            where
            "Superseded" is NULL and
            "ObjectID" = (
                SELECT z1."����"
                FROM "����ô�������ǡ���" as z1
                     JOIN "����ô������" as z0
                     ON z1."����ô������" = z0."ObjectID" AND
                        z0."Superseded" IS NULL
                     JOIN "ô�����" as z3
                     ON z1."ô�����" = z3."ObjectID" AND    
                        z3 ."Superseded" IS NULL
                WHERE z0."����" = '.  mx_db_sql_quote($pid) .
                      ' and z3."ô�����" = \'�缣��\')';
    return (pg_fetch_assoc(pg_query($con,$str)));
}

function get_bloods($pid) {
  $con = mx_db_connect();
  $str = 'select O."ObjectID" as oid, O."����", O."Ĵ�����޻�", O."Ĵ��ǯ����",
                 O."���", P."��", P."̾", P."����0",P."����1", P."����2", 
                 P."����3", P."����4"       
          from   "���޽����" as O, "������Ģ" as P
          where O."Superseded" is NULL and
                P."Superseded" is NULL and
                O."����" = P."ObjectID" and
                O."����" = ' .  mx_db_sql_quote($pid);
  $res = pg_query($con,$str);
  $index = 0;
  while ($ord = pg_fetch_assoc($res)) {
    if ($ord['���'])
    $str = 'select B."��¤�ֹ�", C."ObjectID", C."����", C."�����ˡ",
                   U."����ñ��", D."�쥻�ץ��Ż����������ƥ������̾"
            from "�����ʪͳ�����ʻ��ѵ�Ͽ" as B, "���޽��������" as C, 
                 "Medis�����ʥޥ�����" as D,
                 "���������ñ��" as U
            where B."Superseded" is NULL and
                  D."Superseded" is NULL and
                  U."Superseded" is NULL and
                  B."�����" = '."'{$ord['oid']}'".' and 
                  C."����" = D."ID" and
                  C."����ñ��" = U."ID" and
                  B."����" = C."����" and
                  C."���޽����" = '."'{$ord['oid']}'";
    else
    $str = 'select B."��¤�ֹ�", C."ObjectID", C."����", U."����ñ��",
                   M."��ˡ", D."�쥻�ץ��Ż����������ƥ������̾"
            from "�����ʪͳ�����ʻ��ѵ�Ͽ" as B, "���޽��������" as C, 
                 "Medis�����ʥޥ�����" as D, "�������ˡ" as M, 
                 "���������ñ��" as U
            where B."Superseded" is NULL and
                  D."Superseded" is NULL and
                  M."Superseded" is NULL and
                  U."Superseded" is NULL and
                  B."�����" = '."'{$ord['oid']}'".' and 
                  C."����" = D."ID" and
                  C."����ñ��" = U."ID" and
                  C."��ˡ" = M."ID" and
                  B."����" = C."����" and
                  C."���޽����" = '."'{$ord['oid']}'";
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
           <tr><td nowrap>��ʧǯ��
               <td nowrap><input type="text" name="ym" '
      .$__mx_formi_dek.'> (yyyy-mm)
                   <button class="plain" type="submit" value="����">
                   <img src="/images/use-qbe.png"></button>
                   </table>';
  else {
    $con = mx_db_connect();
    $str = 'select D."ID", D."��ʧ", D."��ʧǯ����", D."��ʧ����", D."���޻�",
            D."����", D."�Ŀ���", D."����", D."�����", M."�쥻�ץ��Ż����������ƥ������̾"
            from "���Ǵ���" as D, 
                 "Medis�����ʥޥ�����" as M
            where
             D."Superseded" is NULL and
             M."Superseded" is NULL and
             D."����" = M."ID" and
             D."��ʧǯ����" like ' . "'{$ym}%' 
             order by D.\"��ʧǯ����\", D.\"ID\" asc"; 
    if (($drugs = pg_fetch_all(pg_query($con,$str))))
      return ($drugs);
    else {
      print "����������ܤ�����ޤ���<br>\n" .
	'<input type="submit" value="���">';
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
           <tr><td>����ID</td>
               <td><input type="text" name="PID" '.$__mx_formi_dek.'></td>
               <td><button class="plain" type="submit" value="����">
                   <img src="/images/pt_select.png"></button></td>
           </tr>
           <tr><td nowrap>�եꥬ��</td>
               <td><input type="text" name="kana" '.$__mx_formi_dek.'></td>
               <td> </td>
           <tr><td nowrap>�о�ǯ��</td>
               <td nowrap><input type="text" name="YM" '.$__mx_formi_dek.'></td>
               <td> (yyyy-mm ���ץ����)</td></tr>
           </table></form>';
  } else {
    if ($pid && $_mx_patient_id_zeropad > 0)
	$pid = mx_zeropad($pid, $_mx_patient_id_zeropad);
    
    $con = mx_db_connect();
    $pat = FALSE;
    if ($ym) {
      $str = 'select distinct P.*
              from ���޽���� as O, ������Ģ as P
              where
                    P."ObjectID" = O."����" and
                    O."����ǯ����" like '."'$ym%'".' and
                    O."Superseded" is NULL and 
                    P."Superseded" is NULL ';
      if ($pid)
	$str = $str . 'and P."����ID" = '."'$pid'";
      if ($kana && $kana != "*")
	$str = $str . 'and P."�եꥬ��" like '."'%{$kana}%'";
    }
    else {
      $str =  'select * 
               from "������Ģ"
               where "Superseded" is NULL ';
      if ($pid)
	$str = $str . ' and "����ID" = '."'$pid'";
      if ($kana && $kana != "*")
	$str = $str . ' and "�եꥬ��" like '."'%{$kana}%'";
    }
    $res = pg_query($con,$str);
    $rescount = pg_num_rows($res);
    if ($rescount == 0) {
      print "<form action=\"$uri\" method=\"post\">";
      print '�������봵�Ԥ����ޤ���<br><input type="submit" value="���">
             </form>';
    }
    elseif ($rescount == 1)
      return pg_fetch_assoc($res);
    else {
      print "<form action=\"$uri\" method=\"post\">
             <table><tr><td colspan=4>���Ԥ����򤷤Ƥ���������</td></tr>
                    <tr><th>��̾</td><th>�եꥬ��</th><th>��ǯ����</th><th>����</th>\n";
      while ($pat = pg_fetch_assoc($res)) {
	printf("<tr><td nowrap><button value=\"%s\" name=\"PID\">
                %s %s</button></td><td nowrap>%s</td><td nowrap>%s</td><td nowrap>%s</td>\n",
	       $pat['����ID'],$pat['��'],$pat['̾'],
	       $pat['�եꥬ��'],$pat['��ǯ����'],
	       ($pat['����'] == 'M' ? "��" : 
		($pat['����'] == 'F' ? "��" : "")));
      }
      print "</table></form>\n";
    }
  }
}

function get_pharm_order ($oid, $shots=NULL) {
  $con = mx_db_connect();
  if($shots == 1)
    $str = 'select "ObjectID" as oid, 
                 "����ǯ����", "����������", "����", "��ȯ��",
                 "����", "��ʬ", "����׻�", "�����", "��߰�", "��Ͽ��", "���޵�Ͽ��",
                 "Ĵ�����޻�", "Ĵ��ǯ����", "�Ǹ�ռ»�", "�ǸϿ��",
                 "Comment"
  from  "��ͽ����"
  where "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID"';
  else
    $str = 'select "ObjectID" as oid, 
                 "����ǯ����", "����������", "���", "����", "��ȯ��",
                 "����", "��ʬ", "����׻�", "�����", "��߰�", "��Ͽ��", "���޵�Ͽ��",
                 "Ĵ�����޻�", "Ĵ��ǯ����", "�Ǹ�ռ»�", "�ǸϿ��",
                 "Comment", "funsai"
  from  "���޽����"
  where "ObjectID" = ' . mx_db_sql_quote($oid) . ' 
  order by "ObjectID"';

  return pg_fetch_assoc(pg_query($con,$str));
}

function get_nurse_history ($pid,$ym) {
  $con = mx_db_connect();
  $str = 'select "ObjectID" as oid, "����ǯ����", "��ʬ", 
         "�Ǹ�ռ»�", "�ǸϿ��"
  from "���޽����"
  where "����" = ' . mx_db_sql_quote($pid) . ' and
        "Superseded" is NULL  and 
        "���" is not NULL and
        "Ĵ��ǯ����" is not NULL and
        "��߰�" is NULL';

  if ($ym) $str = $str . " and \"����ǯ����\" like '{$ym}%' ";
  $str = $str . ' order by "ObjectID"';

  return pg_fetch_all(pg_query($con,$str));
}

function get_history ($pid,$ym,$case) {
  $con = mx_db_connect();
  $str = 'select "ID" as oid, "����ǯ����", "��ʬ", "�����"
  from "���޽����"
  where "����" = ' . mx_db_sql_quote($pid) . ' and
        "Superseded" is NULL ';

  switch ($case) {
  case 0 : $str = $str . ' and "���" is NULL ';
    break;
  case 1 : $str = $str . ' and "���" is not NULL ';
    break;
  case 2 : $str = $str . ' and "Ĵ�����޻�" is not NULL ';
    break;
  }

  if ($ym) $str = $str . " and \"����ǯ����\" like '{$ym}%' ";
  $str = $str . 'order by "ObjectID" desc';

  return pg_fetch_all(pg_query($con,$str));
}

function get_curr_ord($table,$oid,$shots=NULL) {
  $con = mx_db_connect();
  if ($table == "RP")
    $str = '
  select "CreatedBy", "��Ͽ��", "RP̾"
  from "RP"
  where "ObjectID" = ' . "'{$oid}'";
  else if($shots)
    $str = '
  select "CreatedBy", "����ǯ����", "����������", "����", "��Ͽ��",
         "����", "��ʬ", "�����", "��߰�", "Ĵ�����޻�", 
         "Ĵ��ǯ����", "�Ǹ�ռ»�", "�ǸϿ��",
         "���޵�Ͽ��", "��ȯ��", "Comment"
  from "��ͽ����"
  where "ObjectID" = ' . "'{$oid}'";
  else
    $str = '
  select "CreatedBy", "����ǯ����", "����������", "����", "��Ͽ��",
         "����", "��ʬ", "�����", "��߰�", "Ĵ�����޻�", 
         "Ĵ��ǯ����", "���", "�Ǹ�ռ»�", "�ǸϿ��",
         "���޵�Ͽ��", "��ȯ��", "Comment", "funsai"
  from "���޽����"
  where "ObjectID" = ' . "'{$oid}'";

  return pg_fetch_assoc(pg_query($con,$str));
}

function update_pharm_order($var, $shots=NULL) {
  global $mx_authenticate_current_user;

  $oid = $var['oid'];
  if ($var["iRP̾"]) $rp = 1;
  /* get the current order from database */
  if ($rp) {
    $current_order = get_curr_ord("RP",$oid);
    $ordinsstr = make_insert_str("RP",$current_order,$oid);
  } else if($shots) {
    $current_order = get_curr_ord("��ͽ����",$oid,1);
    $ordinsstr = make_insert_str("��ͽ����",$current_order,$oid);
  } else {
    $current_order = get_curr_ord("���޽����",$oid);
    $ordinsstr = make_insert_str("���޽����",$current_order,$oid);
  }
  /* get the current contents from database */
  if ($current_order['���'] || $shots )
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
      case "����" :
	$val = mb_convert_kana($val,'n','EUC-JP');
	break;
      case "����ǯ����" :
      case "����������" :
      case "�����" :
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
      case "unitid" : $rest = "����ñ��"; break;
      case "freqid" : $rest = "��ˡ"; break;
      case "medis" : $rest = "����"; break;
      case "shapeid" : $rest = "��Ϳ����"; break;
      case "methodid" : $rest = "�굻"; break;
      case "dosageid" : $rest = "�����ˡ"; break;
      case "����":
	      $val = mb_convert_kana($val,'n','EUC-JP');
	      if ($days_max + 0 < $val * 1)
		      $days_max = $val;
	      break;
      case "��ˡʬ��": $val = mb_convert_kana($val,'n','EUC-JP'); break;
      case "����" : $val = mb_convert_kana($val,'n','EUC-JP'); break;
      }
      if ($rest == "medid") {
	if ($cur_id[$val])
	  $cur_id[$val] = false;
	continue;
      } elseif ($rest == "�쥻�ץ��Ż����������ƥ������̾"
		|| $rest == "�쥻�ץ��Ż����������ƥॳ���ɡʣ���"
	        || $rest == "���������ܰ����ʥ�����")
	  continue;
      if ($rp) $medications[$prefix]["��ˡ"] = $var['rp-freqid'];
      $medications[$prefix][$rest] = $val;
    }
  }

  // $var['PID'] has ����ID and $var['pid'] has ����.ObjectID
  $it = mx_get_current_reception_info($mx_authenticate_current_user,
				      $var['pid']);
  if ($it) {
	  $insinfo = trim($it['�ݸ��ȹ礻']);
	  if ($insinfo)
		  $ordup[] = "\"�±����ѥ쥻�����ݸ�����\" = '$insinfo'";
	  $deptinfo = trim($it['���ǲ��ܥ�����']);
	  if ($deptinfo)
		  $ordup[] = "\"�±����ѥ쥻������ǲʾ���\" = '$deptinfo'";
  }

  $ordup[] = '"����" = ' . $days_max;

  $cur=current($medications);
  foreach ($medications as $med) {
    if (!array_key_exists('����',$med)) {
      print '<h1><blink><font color="red">���ޤ���ꤷ�Ƥ���������
           </font></blink></h1><br>';
      return false;
    }
  }

  $con = mx_db_connect();
  if ($rp)
    $ordupstr = "update \"RP\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";
  else if($shots)
    $ordupstr = "update  \"��ͽ����\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";
  else
    $ordupstr = "update  \"���޽����\" set \"CreatedBy\" = '{$var['u']}'," 
      . implode(',',$ordup) . " where \"ObjectID\" = '{$oid}';\n";

  $ret = true;
  pg_query($con,"begin");
  pg_query($con,$ordinsstr) || $ret = false;
  if ($rp)
    pg_query($con,"update \"���޽��������\" 
   set \"RPID\" = currval('\"RP_ID_seq\"')
   where \"RPID\" = '{$oid}'") ||$ret = false; 
  else if($shots){
    pg_query($con,"update \"��ͽ��������\" 
   set \"��ͽ����\" = currval('\"��ͽ����_ID_seq\"')
   where \"��ͽ����\" = '{$oid}'") ||$ret = false; 
  }else
    pg_query($con,"update \"���޽��������\" 
   set \"���޽����\" = currval('\"���޽����_ID_seq\"')
   where \"���޽����\" = '{$oid}'") ||$ret = false; 
  pg_query($con,$ordupstr) || $ret = false;

  if ($medications)
    foreach ($medications as $item) {
      if ($rp)
	$item['RPID'] = $oid;
      else if($shots) {
	$item['��ͽ����'] = $oid;
	// XXX
	unset( $item['��Ϳ����'] );
	$instr =  make_insert_str("��ͽ��������",$item,false,"");
      }
      else{
	$item['���޽����'] = $oid;
	$instr =  make_insert_str("���޽��������",$item,false,"");
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
      case "i����" :
	$val = mb_convert_kana($val,'n','EUC-JP');
	break;
      case "i����ǯ����" :
      case "i����������" :
      case "i�����" : 
        $val = mx_ui_japanese_date($val);
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      case 'iRP̾' : $rp=1;
      }
      if (is_null($val) || !strlen($val)) $ordval[$indx] = "NULL";
      else $ordval[$indx] = "'" . $val . "'";
      $ordkey[$indx++] = '"' . substr($key,1) . '"';
    } elseif (ereg("^med[-]*[0-9]",$key)) {
      $prefix = ereg_replace("^(med[0-9]*).*","\\1",$key);
      $rest =  ereg_replace("^med[0-9]*(.*)","\\1",$key);
      if ($rest == "medid" || $rest == "�쥻�ץ��Ż����������ƥ������̾"
	  || $rest == "�쥻�ץ��Ż����������ƥॳ���ɡʣ���"
	  || $rest == "���������ܰ����ʥ�����"
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
      print '<h1><blink><font color="red">���ޤ���ꤷ�Ʋ�������
             </font></blink></h1><br>';
      return false;
    }
    $nshpid = (int) $med['shapeid'];
    if ($oshpid != $nshpid) {
      if ((1 <= $oshpid && $oshpid <= 8 && $nshpid > 8) ||
	  ($oshpid > 8 && $nshpid <= 8)) {
	print '<h1><blink><font color="red">����䵤���ͤ�¾����Ϳ���֤򺮺ߤ��뤳�ȤϤǤ��ޤ���
             </font></blink></h1><br>';
	return false;
      }
      $oshpid = $nshpid;
    }
    $med["����"] = mb_convert_kana($med["����"],'a','EUC-JP');
    $days = mb_convert_kana($med["����"],'a','EUC-JP');
    $med["����"] = $days;
    if ($days_max + 0 < $days * 1)
	    $days_max = $days;
    $med["��ˡʬ��"] = mb_convert_kana($med["��ˡʬ��"],'a','EUC-JP');
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
      elseif ($mkey == "methodid") $mkey = "�굻";
      elseif ($mkey == "dosageid") $mkey = "�����ˡ";

      // HACK
      if($injection && $mkey == "��Ϳ����")
	  continue;

      if (is_null($mval) || !strlen($mval))
	$medval[$strindx][$indx] = "NULL"; 
      else $medval[$strindx][$indx] = "'" . $mval . "'";
      $medkey[$strindx][$indx++] = '"' . $mkey . '"';
    }
    $strindx++;
  }

  // $var[i����] has ����.ObjectID
  $it = mx_get_current_reception_info($mx_authenticate_current_user,
				      $var['i����']);
  if ($it) {
	  $insinfo = trim($it['�ݸ��ȹ礻']);
	  if ($insinfo) {
		  $ordkey[] = '"�±����ѥ쥻�����ݸ�����"';
		  $ordval[] = "'$insinfo'";
	  }
	  $deptinfo = trim($it['���ǲ��ܥ�����']);
	  if ($deptinfo) {
		  $ordkey[] ='"�±����ѥ쥻������ǲʾ���"';
		  $ordval[] = "'$deptinfo'";
	  }
  }

  $ordkey[] = '"����"';
  $ordval[] = $days_max;

  $ret = true;
  $con = mx_db_connect();
  pg_query($con, "begin;");
  if ($injection)
    $str = 'insert into "��ͽ����" ("CreatedBy",' . implode(',',$ordkey) . 
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  elseif ($rp)
    $str = 'insert into "RP" ("CreatedBy",' . implode(',',$ordkey) .
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";
  else 
    $str = 'insert into "���޽����" ("CreatedBy",' . implode(',',$ordkey) . 
      ") values ('{$var['u']}'," . implode(',',$ordval) . ");\n";

  pg_query($con,$str) or $ret = false;
  for ($i = 0, $c = count($medkey); $i < $c; $i++) {
    if ($rp)
      $str =  'insert into "���޽��������" ("RPID",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"RP_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    else{
      $tbl = "����";
      if($injection) 
	$tbl = "���";
      $str =  'insert into "'.$tbl.'���������" ("'.$tbl.'�����",' .
	implode(',',$medkey[$i]) . 
	") values (currval('\"".$tbl."�����_ID_seq\"')," .
	implode(',',$medval[$i]) . ");\n";
    }
    pg_query($con,$str) or $ret = false;

    $medis = $medval[$i][array_search('"����"',$medkey[$i],true)];

    if ($ret && $new_oid_return == 'please') {
      $new_oid_return = NULL;
      $sth = pg_query($con, "select currval('\"".$tbl."�����_ID_seq\"') as it");
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
  'select "����", "����", "͢���ѷ��", "������ʪͳ������"
   from "�������ʥޥ�����"
   where "ObjectID" = ' . $id));
  if ($drug["����"] == "Y") $ret = 1;
  if ($drug["����"] == "Y") $ret = $ret + 2;
  if ($drug["͢���ѷ��"] == "Y") $ret = $ret + 4;
  if ($drug["������ʪͳ������"] == "Y") $ret = $ret + 8;

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
      case "Ĵ��ǯ����" :
        $val = mx_ui_japanese_date($val);
	if (check_date($key,$val)) return;
	$val = mb_convert_kana($val,'a','EUC-JP');
	break;
      case "Ĵ�����޻�" :
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

  if (diff_contents("���޽����",$ordstr)) {
    make_update_str("���޽����",$ordstr,$upstr,$insstr);
    pg_query($con,$insstr) or $ret = false;
    $str = 'select "���޽����", "��Ϳ����", "�굻", "����", "����", "����",
              "��ˡʬ��", "����ñ��", "����", "��ˡ", "�����ˡ", "����¾������"
            from  "���޽��������"
            where "���޽����" = ' . "'{$var['oid']}'";

    $meds = pg_fetch_all(pg_query($con,$str));
    $str = 'update "���޽��������" 
           set "���޽����" = currval(\'"���޽����_ID_seq"\')
           where "���޽����" = ' . "'{$var['oid']}'";
    pg_query($con,$str) || $ret = false;
    if ($meds)
      foreach ($meds as $med)
	pg_insert($con,"���޽��������",$med) || $ret = false;
    pg_query($con,$upstr) or $ret = false;
  }

  if ($bmed) {
    $btable = "�����ʪͳ�����ʻ��ѵ�Ͽ";
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
    $dtable = "���Ǵ���";
    foreach ($dmed as $med) {
      $med['���޻�'] = $pharmacist;
      $med['CreatedBy'] = $var['u'];
      $med['��ʧ'] = "O";
      $med["��ʧǯ����"] = mb_convert_kana($med["��ʧǯ����"],'a','EUC_JP');
      $med["��ʧ����"] = mb_convert_kana($med["��ʧ����"],'n','EUC_JP');
      $med["�Ŀ���"] = mb_convert_kana($med["�Ŀ���"],'n','EUC_JP');
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
            "�����" = ' .mx_db_sql_quote($oid). ' and
            "����" = ' .mx_db_sql_quote($medis);
  return(pg_fetch_assoc(pg_query($con,$str)));
}

function get_drug_with_id($id) {
  $con = mx_db_connect();
  $str = 'select d.*, m."�쥻�ץ��Ż����������ƥ������̾"
          from  "���Ǵ���" as d, "Medis�����ʥޥ�����" as m
          where d."Superseded" is NULL and
                m."Superseded" is NULL and
                d."����" = m."ObjectID" and
                d."ObjectID" = '. mx_db_sql_quote($id);
  return(pg_fetch_assoc(pg_query($con,$str)));
}

function get_unitid($unit_name) {
  $con = mx_db_connect();
  return pg_fetch_assoc(pg_query($con,'select "ObjectID" as id from "���������ñ��"
                                       where "Superseded" is NULL and
                                       ����ñ�� = '."'$unit_name'"));
}

function get_prescription_list() {

  $con = mx_db_connect();
  $list = FALSE;
  $res = pg_query($con, 'select O."ObjectID" as oid,
     O."��ʬ", O."����ǯ����", O."����������", O."����" as pid
     from
          "���޽����" as O
     where
           O."Ĵ�����޻�" is NULL and
           O."Ĵ��ǯ����" is NULL and
           O."�����" is NULL AND
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