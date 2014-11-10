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
      $act[1]['label']="����";
      $act[1]['url']="../../u/pharmacy/pharm-order.php";
      $act[2]['label']="���";
      $act[2]['url']="../../u/pharmacy/injection-order.php";
      break;
    case "pharm-order" :
      $act[1]['label']="���";
      $act[1]['url']="../../u/pharmacy/injection-order.php";
      $act[2]['label']="����";
      $act[2]['url']="../../u/nutrition/order.php";
      break;
    case "injection-order" :
      $act[1]['label']="����";
      $act[1]['url']="../../u/pharmacy/pharm-order.php";
      $act[2]['label']="����";
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
    
    $str = 'select C."ObjectID" as medid, C."����", C."����¾������",
                 C."����" as medis,
                 C."����ñ��" as unit, Y."��ˡ" as "�����ˡ",
                 C."����", C."��ˡʬ��", C."����", C.generic_ok,
                 M."��̾��", M."�쥻�ץ��Ż����������ƥ������̾",
                 M."�±����Ѱ�����̾", M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
                 M."���������ܰ����ʥ�����",
                 M."�±����ѥ�٥��װ���",
                 Y."ObjectID" as dosageid,
		 Y."����������",
                 I."�굻", I."ID" as methodid,
		 M."����ñ��ñ��" as "����ñ��",
                 U."����ñ��" as "old����ñ��",
                 M."��������" as accept
FROM "��ͽ��������" as C
            LEFT JOIN "�����ˡ" as Y
                ON C."�����ˡ" = Y."ObjectID" AND Y."Superseded" is NULL
            JOIN "Medis�����ʥޥ�����" as M
                ON M."ObjectID" = C."����" AND M."Superseded" IS NULL
            LEFT JOIN "��ͼ굻" as I
                ON I."ObjectID" = C."�굻" AND I."Superseded" IS NULL
	    LEFT JOIN "���������ñ��" as U
                ON U."ObjectID" = C."����ñ��" AND U."Superseded" is NULL
            WHERE C."��ͽ����" =  ' . mx_db_sql_quote($oid) . '
            ORDER BY C."ObjectID"';
  }
  elseif  ($injection == 2) {
    $str = 'select C."ObjectID" as medid, C."����", C."����¾������",
                 C."��Ϳ����" as shape, C."����" as medis,
                 C."����ñ��" as unit, C."��ˡ" as freq,
                 C."����", C."��ˡʬ��", C."����", C.generic_ok,
                 M."��̾��", M."�쥻�ץ��Ż����������ƥ������̾",
                 M."�±����Ѱ�����̾",  M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
                 M."���������ܰ����ʥ�����",
                 M."�±����ѥ�٥��װ���",
                 T."��Ϳ����", T."ID" as shapeid,
                 Y."��ˡ", Y."ID" as freqid,
		 Y."����������",
		 M."����ñ��ñ��" as "����ñ��",
                 M."��������" as accept
  from "���޽��������" as C, "Medis�����ʥޥ�����" as M,
       "�������Ϳ����" as T,
       "�������ˡ" as Y
  where C."RPID" = ' . mx_db_sql_quote($oid) . ' and
        C."��Ϳ����" = T."ObjectID" and C."����" = M."ObjectID" and 
        C."��ˡ" = Y."ObjectID" and  
        M."Superseded" is NULL and T."Superseded" is NULL and 
        Y."Superseded" is NULL
        order by C."ObjectID"';
  }
  else {
    $str = 'select C."ObjectID" as medid, C."����", C."����¾������",
                 C."��Ϳ����" as shape, C."����" as medis,
                 C."����ñ��" as unit, C."��ˡ" as freq,
                 C."����", C."��ˡʬ��", C."����", trim(C."��ʬ") as "��ʬ",
                 C."����" as "����", C.generic_ok,
                 M."��̾��", M."�쥻�ץ��Ż����������ƥ������̾",
                 M."�±����Ѱ�����̾", M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
                 M."���������ܰ����ʥ�����",
                 M."�±����ѥ�٥��װ���",
                 T."��Ϳ����", T."ID" as shapeid,
                 Y."��ˡ", Y."ID" as freqid,
		 Y."����������",
		 M."����ñ��ñ��" as "����ñ��",
                 U."����ñ��" as "old����ñ��",
                 M."��������" as accept
	    FROM "���޽��������" as C
	    LEFT JOIN "�������ˡ" as Y
		ON C."��ˡ" = Y."ObjectID" AND Y."Superseded" is NULL
	    JOIN "Medis�����ʥޥ�����" as M
		ON M."ObjectID" = C."����" AND M."Superseded" IS NULL
	    LEFT JOIN "�������Ϳ����" as T
		ON T."ObjectID" = C."��Ϳ����" AND T."Superseded" IS NULL
	    LEFT JOIN "���������ñ��" as U
                ON U."ObjectID" = C."����ñ��" AND U."Superseded" is NULL
	    WHERE C."���޽����" =  ' . mx_db_sql_quote($oid) . '
	    ORDER BY C."ObjectID"';
  }
  return pg_fetch_all(pg_query($con,$str));
}
//11-01-2014
function get_meds_shot ($oid,$injection) {
  $con = mx_db_connect();
  
    $str = 'select C."ObjectID" as medid, C."����", C."����¾������",
                 C."��Ϳ����" as shape, C."����" as medis,
                 C."����ñ��" as unit, C."��ˡ" as freq,
                 C."����", C."��ˡʬ��", C."����", trim(C."��ʬ") as "��ʬ",
                 C."����" as "����", C.generic_ok,
                 M."��̾��", M."�쥻�ץ��Ż����������ƥ������̾",
                 M."�±����Ѱ�����̾", M."�쥻�ץ��Ż����������ƥॳ���ɡʣ���",
                 M."���������ܰ����ʥ�����",
                 M."�±����ѥ�٥��װ���",
                 T."��Ϳ����", T."ID" as shapeid,
                 Y."��ˡ", Y."ID" as freqid,
		 Y."����������",
		 M."����ñ��ñ��" as "����ñ��",
                 U."����ñ��" as "old����ñ��",
                 M."��������" as accept
	    FROM "yakuzai-d" as C
	    LEFT JOIN "�������ˡ" as Y
		ON C."��ˡ" = Y."ObjectID" AND Y."Superseded" is NULL
	    JOIN "Medis�����ʥޥ�����" as M
		ON M."ObjectID" = C."����" AND M."Superseded" IS NULL
	    LEFT JOIN "�������Ϳ����" as T
		ON T."ObjectID" = C."��Ϳ����" AND T."Superseded" IS NULL
	    LEFT JOIN "���������ñ��" as U
                ON U."ObjectID" = C."����ñ��" AND U."Superseded" is NULL
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
        ��������ơ�'.$c.'</font>';
    print "<tr><td colspan=".$col.">{$med['�쥻�ץ��Ż����������ƥ������̾']}&nbsp;
               {$med['����']}{$med['����ñ��']}&nbsp;";
    if ($disp)
      $med['�굻'] ? print $med['�굻'] : print $med['��Ϳ����'];
    print "&nbsp;{$med['��ˡ']}{$med['�����ˡ']}";
    if ($med['����']) print "&nbsp;{$med['����']} ��";
    if ($med['��ˡʬ��']) printf(" (%s) ",$med['��ˡʬ��']);
    if ($med['����¾������'])
      printf('<tr><td colspan="%d">%s',$col,$med['����¾������']);
    $c++;
  }
}

function lib_ord_common_get_doctors($pid, $type, $opt) {
//0404-2012 add userid
  $str = 'select E."ID", E."��", E."̾" , userid from 
          "������Ģ" as E,
	  "�������ɽ" as C
	  where
          C."Superseded" is NULL and
          E."Superseded" is NULL and
	  C."ID" = E."����"
          ';
  if (!is_null($pid))
    $str .= ' and E."ID" in (SELECT z1."����" FROM
                     "����ô�������ǡ���" as z1
                     JOIN "����ô������" as z0
                     ON z1."����ô������" = z0."ObjectID" AND
                        z0."Superseded" IS NULL
                     WHERE z0."����" = ' . mx_db_sql_quote($pid) . ')';
  switch ($type) {
  case "rehab" :
    $str = $str . " and C.\"����\" in " . enum_doctor_cat_sql() . "";
    break;
  case "pharm0" :
    $str = $str . " and C.\"����\" in " . enum_pharmacist_cat_sql() . "";
    break;
  case "doctor" :
    $str = $str . " and C.\"����\" in " . enum_doctor_cat_sql() . "";
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
	$ret[$prefix.$row['ID']] = $row['��'].$row['̾'];
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
	   $row['ID'],$row['��'],$row['̾']);
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
	   $row['userid'],$row['��'],$row['̾']);
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
          FROM "�������ɽ" as W 
               JOIN "�¼�����ɽ" AS R
               ON W."ObjectID" = R."����" and
                  R."Superseded" IS NULL AND 
                  W."Superseded" IS NULL
               JOIN "�¼�����ɽ" AS P
               ON R."ObjectID" = P."�¼�" AND
                  P."Superseded" IS NULL
               JOIN "�¼����ԥǡ���" AS D
               ON P."ObjectID" = D."�¼�����ɽ"
          WHERE D."����" = ' . mx_db_sql_quote($pid);
  return (pg_fetch_assoc(pg_query($con,$str)));
}

function get_patient($id,$type)
{
  if (!ereg("^[0-9]+$",$id)) return FALSE;
  $id = mx_db_sql_quote($id);

  $con = mx_db_connect();

  if ($type)
    $res = pg_query($con, "select * from ������Ģ where \"����ID\" = $id
                         and \"Superseded\" is NULL")
      or die('pg_query => '. pg_last_error());
  else
    $res = pg_query($con, "select * from ������Ģ where \"ObjectID\" = $id
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

   
    $res = pg_query($con, "select * from ������Ģ where \"userid\" = $id
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
  if ($table == "���޽����" && $array['RP̾'])
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

  /* if array is size of 1 (act only), flag the '���' */
  if (count($array) == 1) {
    $array = $med;
    $array['���'] = 1;
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
          FROM "�¼�����ɽ" AS R
               JOIN "�¼�����ɽ" AS P
               ON R."ObjectID" = P."�¼�" AND
                  R."Superseded" IS NULL AND 
                  P."Superseded" IS NULL
               JOIN "�¼����ԥǡ���" AS D
               ON P."ObjectID" = D."�¼�����ɽ"
          WHERE D."����" = ' . mx_db_sql_quote($pid);
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
  select "��" as lname, "̾" as fname
  from "������Ģ" 
  where "Superseded" IS NULL and
        "ObjectID" = ' . mx_db_sql_quote($empid))));

}
function get_emp_name2($empid) {
  if (!$empid) return "";
  $con = mx_db_connect();
  return (pg_fetch_assoc(pg_query($con,'
  select "��" as lname, "̾" as fname
  from "������Ģ" 
  where "Superseded" IS NULL and
        "ID" = ' . mx_db_sql_quote($empid))));

}
function check_date($key,$date) {
  if (!$date) return false;
  if (ereg("[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]",$date))
    return false;
  else
    print '<p><font color="red">'."��{$key}�פν񼰤���{$date}��
     �Ȼ��ꤵ��Ƥ��ޤ���<br>���դϡ���YYYY-MM��DD�פν񼰡���2006ǯ
     3��1���ξ���2006-03-01�סˤǻ��ꤷ�Ƥ���������</font><p>\n";
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
          '<tr><td>����ID
               <td><input type="text" name="PID" '.$__mx_formi_dek.'>
               <td><button class="plain" type="submit" value="����">
                   <img src="/images/pt_select.png"></button>
        <tr><td>�եꥬ��
               <td><input type="text" name="kana" '.$__mx_formi_dek.'>
               <td>
           </table></form>';
  } else {
    $con = mx_db_connect();
    $str =  'select *
             from "������Ģ"
             where "Superseded" is NULL ';
    if ($pid)
      $str = $str . ' and "����ID" = '."'$pid'";
    if ($kana && $kana != "*")
      $str = $str . ' and "�եꥬ��" like '."'%{$kana}%'";
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
             <table><tr><td colspan=4>���Ԥ����򤷤Ƥ���������
                    <tr><th>��̾<th>�եꥬ��<th>��ǯ����<th>����\n";
      while ($pat = pg_fetch_assoc($res)) {
        printf("<tr><td nowrap><button value=\"%s\" name=\"PID\">
                %s %s</button><td nowrap>%s<td nowrap>%s<td nowrap>%s\n",
               $pat['����ID'],$pat['��'],$pat['̾'],
               $pat['�եꥬ��'],$pat['��ǯ����'],
               ($pat['����'] == 'M' ? "��" :
                ($pat['����'] == 'F' ? "��" : "")));
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
  else $prev = "�ǿ�";
  $next= disp_day_time(substr($array[$page]['Superseded'],0,10),
		       substr($array[$page]['Superseded'],11,5));
  print "<table border=1><tr><th>�ѹ�����<td>$prev<td>$next";
  foreach ($array[$page-1] as $key => $newval) {
    if (check_key($key,$no_disp)) continue;
    $oldval = $array[$page][$key];
    if ($newval != $oldval) {
      if ($key == "���ܻε�Ͽ") {
	if ($newval) $newval = "��";
	else $newval = "̵";
	if ($oldval) $oldval = "��";
	else $oldval = "̵";
      } elseif ($key == "��Ͽ��" || $key == "���" || 
		$key == "���޵�Ͽ��" || $key == "��߰�" ||
		$key == "Ĵ�����޻�" || $key == "�ǸϿ��" ||
		$key == "������") {
	$newname = get_emp_name($newval);
	$oldname = get_emp_name($oldval);
	$newval = $newname['lname']."&nbsp;".$newname['fname'];
	$oldval = $oldname['lname']."&nbsp;".$oldname['fname'];
      } elseif ($newval == "on")
	$newval = "����";
      elseif ($oldval == "on")
	$oldval = "����";
      print "<tr><th>$key<td>$newval<td>$oldval";
    }
  }
  if ($table == "���޽����" || $table == "��ͽ����") {
    print "<tr><th>���������<td style=\"vertical-align: top;\"><table width=150>";
    if ($array[$page-1]['���'] || $shots)
      show_meds(get_meds($array[$page-1]['ObjectID'],1),1);
    else
      show_meds(get_meds($array[$page-1]['ObjectID'],0),1);
    print "</table><td><table width=150>";
    if ($array[$page]['���'] || $shots)
      show_meds(get_meds($array[$page]['ObjectID'],1),1);
    else
      show_meds(get_meds($array[$page]['ObjectID'],0),1);
    print "</table>";
  } elseif ($table == "RP") {
    print "<tr><th>���������<td><table width=150>";
    show_meds(get_meds($array[$page-1]['ObjectID'],2),1);
    print "</table><td><table width=150>";
    show_meds(get_meds($array[$page]['ObjectID'],2),1);
    print "</table>";
  } elseif ($table == "TSET") {
    print "<tr><th>���������<td><table width=150>";
    show_tp_tests(get_tp_tests($array[$page-1]['ObjectID'],1));
    print "</table><td><table width=150>";
    show_tp_tests(get_tp_tests($array[$page]['ObjectID'],1));
    print "</table>";
  } elseif ($table == "���������") {
    print "<tr><th>���������<td><table width=150>";
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
         '','width=640,height=640')\">����</button>
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

  if (!($page-1)) $ptitle = "<font color=green>&lt;-&nbsp;�ǿ�</font>";
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
  $disp_day = preg_replace("/-/","��",substr($day,5,10));
  $disp_day = preg_replace("/$/","��",$disp_day);
  $disp_time = preg_replace("/:/","��",substr($time,0,5));
  $disp_time = preg_replace("/$/","ʬ",$disp_time);
  return $disp_year.$disp_day." ".$disp_time;
}

function seireki2wareki( $seireki ){
  $WAREKI  = array("ʿ��"=>1989, "����"=>1926, "����"=>1912);
  if( !ereg("^[0-9]{4}$",trim($seireki)) ) return FALSE;
  foreach( $WAREKI as $nengo => $gannen ){
    $year = $seireki - $gannen + 1;
    if( $year == 1 ) $year = "��";
    if( $seireki >= $gannen ) return "{$nengo}{$year}ǯ";
  }
  return FALSE;
}

function list_med($varname,$def,$type) {
  global $__mx_formi_dek;

  $con = mx_db_connect();
//0510-2013 hayashi
  switch ($type) {
  case "units" : $str = 'select "ObjectID", "����ñ��" as val from "���������ñ��"
                         where "Superseded" is NULL order by "ObjectID"';
    break;
  case "shape" : $str = 'select "ObjectID", "��Ϳ����" as val from "�������Ϳ����"
			where "Superseded" is NULL order by "ObjectID"';
    break;
  case "freq" : $str = 'select "ObjectID", "��ˡ" as val from "�������ˡ"
			where "Superseded" is NULL and (sortorder is null or sortorder >0 )
 order by sortorder';
    break;
  case "method" : $str = 'select "ObjectID", "�굻" as val from "��ͼ굻"
			where "Superseded" is NULL order by "ObjectID"';
    break;
  case "dosage" : $str = 'select "ObjectID", "��ˡ" as val from "�����ˡ"
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
