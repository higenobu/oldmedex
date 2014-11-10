<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

$ord_array =  array(
    "��ʬ" => array('����','�ѹ�','��ߡʳ����','��ߡʳ��С�',
		    '��ߡʸ�����','�Ƴ�','�俩','�ౡ'),
    "�ݸ�" => array('���̲û�','��û�'),
    "�¹Ի�" => array('ī','��','ͼ'),
    "����" => array('-','�￩ ��','�￩ ��','�￩ ��','�벼��','�벼��',
		    '�벼��','�ԥ塼�쿩','���󤫤���',
		    '��ڰ����ʥȥ�ߤĤ���','��ʬ����',
		    '��ʬ����','��ʬ����','������','���ӿ�','ήư��',
		    'ǻ��ήư�ʷи���','ήư���ʼ��š�','���Ȏَ��ގ����ݎĎێ���1000',
		    '���Ȏَ��ގ����ݎĎێ���1400','���Ȏَ��ގ����ݎĎێ���1800','���ݎʎߎ����ݎĎێ���1600',
		    '���ݎʎߎ����ݎĎێ���1800','������ݎĎێ���1500','������ݎĎێ���1800',
		    '��ʬ���ݎĎێ���1000','��ʬ���ݎĎێ���1400','��ʬ���ݎĎێ���1800',
		    '�׾ò���ʬ����','�׾ò���ʬ����','�׾ò���ʬ����',
		    '�׾ò�������','�׾ò����ӿ�','ǻ��ήư�ʷдɡ�','�俩'),
    "�翩" => array('-','�ѥ�','����','����','��ʬ��','��ʬ��','��ʬ��','�ѥ�',
		    '���ԥ塼��','���꡼'),
    "Ŭ������" => array('-','��Ǣ��','���ô�','����������','����','Ŵ��˳���Ϸ�',
			'�ռ�������Ǣ�����ձ�','�μ���','�����αꡦ�¡����',
			'�����','�ռ���','��¡����',
			'����ʰ����硦�����Ĳ��',
			'�������¡���������Ĳ��ʤ�','PEG����ɡ'),
    "Ŭ������2" => array('��Ǣ��','���ô�','����������','����','Ŵ��˳���Ϸ�',
			'�ռ�������Ǣ�����ձ�','�μ���','�����αꡦ�¡����',
			'�����','�ռ���','��¡����',
			'����ʰ����硦�����Ĳ��',
			'�������¡���������Ĳ��ʤ�','PEG����ɡ'),
    "����" => array('-','���','��ڰ����','��ڰ����ʤȤ���դ���',
		    '�����','���󤫤�','�ԥ塼��','���꡼'),
    "����륮���γ�ǧ" => array('-','����','��','����','����'),
    "��" => array('-','7','8','9','10','11','12','13','14','15','16','17','18',
		  '19','20','21','22','23','24','1','2','3','4','5','6'),
    "����" => array('-','2.0','1.5','1.0','����Ĵ��ήư��DM�ѡ�'),
    "��ʬ���ֿ�" => array('-','ͭ'),
    "®��" => array('-','30','45','60'));

$ins = array('ήư���ʼ��š�','���Ȏَ��ގ����ݎĎێ���1000','���Ȏَ��ގ����ݎĎێ���1400',
	     '���Ȏَ��ގ����ݎĎێ���1800','���ݎʎߎ����ݎĎێ���1600','���ݎʎߎ����ݎĎێ���1800',
	     '������ݎĎێ���1500','������ݎĎێ���1800','��ʬ���ݎĎێ���1000','��ʬ���ݎĎێ���1400',
	     '��ʬ���ݎĎێ���1800','�׾ò���ʬ����','�׾ò���ʬ����','�׾ò���ʬ����',
	     '�׾ò�������','�׾ò����ӿ�','ǻ��ήư�ʷдɡ�');

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
                                  from "�Х�����ǡ���ɽ"
                                  where "Superseded" is NULL and
                                  "����" = '."'$pid'"));
  return $mes ? $mes[$type] : "";
}

function get_patient_meal($pid) {
  if (!ereg("^[0-9]+$",$pid)) return FALSE;

  $con = mx_db_connect();
  $res = pg_query($con, 'select P."ObjectID", P."��", P."̾"
     from ����� as M, ������Ģ as P
     where M."Superseded" is NULL and
           P."Superseded" is NULL and 
           P."ObjectID" = M."����" and
           P."����ID" = '."'$pid'".
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
				  'select * from "�����"
                                   where "Superseded" is NULL and
                                         "����" = '."'$pid'".
                                   'order by "ObjectID"')));
}

function get_meal_new_updates($search) {
  $con = mx_db_connect();

  $str = 'select M."ObjectID", M."��Ͽ��", M."��Ͽ����", P."��", P."̾"
         from "�����" as M
              join  "������Ģ" as P 
              on P."ObjectID" = M."����" and
                P."Superseded" is NULL
         where 
         M."Superseded" is NULL and ';
  switch ($search) {
  case '1' : $str = $str . ' M."�¹���" >= '."'today' and ".
	                   ' M."�¹���" <= '."'tomorrow' ";
    break;
  case '2' : $str = $str . ' M."���ܻε�Ͽ" is NULL ';
    break;
  }
  $str = $str . ' order by M."ObjectID"';
  return pg_fetch_all(pg_query($con,$str));
}

function get_meal_order($oid) {
  $con = mx_db_connect();
  return (pg_fetch_assoc(pg_query($con,
				  'select  * from "�����"
                                   where "ObjectID" = '. "'$oid'")));
}

function insert_meal_order ($var) {

  foreach ($var as $key => $val)
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      if ($val == '-') $val = "";
      if (($key == "������" || $key == "�¹���" || $key == "�Ƴ���") && 
	  check_date($key,mb_convert_kana($val,'a','EUC-JP'))) return;
      if ($key == "ǻ��ήư��Ǯ��" || $key == "��0" || $key == "��1" || 
	  $key == "��2" || $key == "��3" || $key == "��4" ||
	  $key == "�ֿ���0" || $key == "�ֿ���1" || $key == "�ֿ���2" ||
	  $key == "�ֿ���3" || $key == "�ֿ���4") {
	$val = mb_convert_kana($val,'a','EUC-JP');
	if ($val && !ereg("^[0-9]+$",$val)) {
	  print '<font color="red">{$key}�˿��������Ϥ��Ƥ���������</font>';
	  return FALSE;        
	}
      }
      $ins[$key]=$val;
    }
  $ins['CreatedBy'] = $var['u'];
  $str = make_insert_str("�����",$ins,$oid);

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
      if (($key == "������" || $key == "�¹���" || $key == "�Ƴ���") && 
	  check_date($key,mb_convert_kana($val,'a','EUC-JP'))) return;
      if ($key == "ǻ��ήư��Ǯ��" || $key == "��0" || $key == "��1" || 
	  $key == "��2" || $key == "��3" || $key == "��4" ||
	  $key == "�ֿ���0" || $key == "�ֿ���1" || $key == "�ֿ���2" ||
	  $key == "�ֿ���3" || $key == "�ֿ���4") {
	$val = mb_convert_kana($val,'a','EUC-JP');
	if ($val && !ereg("^[0-9]+$",$val)) {
	  print '<font color="red">{$key}�˿��������Ϥ��Ƥ���������</font>';
	  return FALSE;
	}
      }
      $array[$key]=$val;
    }
  $array['CreatedBy'] = $var['u'];
  $array['act'] = $var['oid'];

  $ret = true;
  if (diff_contents("�����",$array)) {
    make_update_str("�����",$array,$upstr,$insstr);
    $con = mx_db_connect();
    pg_query($con,"begin");
    pg_query($con,$insstr) or $ret = false;
    pg_query($con,$upstr) or $ret = false;
    pg_query($con, "commit;");
  }
  return $ret;
}

function print_meal_detail($ord,&$done) {

    $name = get_emp_name($ord['��Ͽ��']);
    $room = get_pat_room($ord['����']);
    print "<table>
           <tr><th nowrap>�����ID<td nowrap>{$ord['ObjectID']}
               <th nowrap>�¼�̾<td nowrap>{$room['�¼�̾']}";
    foreach($ord as $k => $v) {
      if ($k == "CreatedBy" || $k == "ObjectID" ||
	  $k == "Superseded" || $k == "ID" ||
	  $k == "����") continue;
      if (($k == "��0" || $k == "��1" || $k == "��2" ||
	  $k == "��3" || $k == "��4" || 
	  $k == "����0" || $k == "����1" || $k == "����2" ||
	  $k == "����3" || $k == "����4" ||
	  $k == "��0" || $k == "��1" || $k == "��2" ||
	  $k == "��3" || $k == "��4" || $k == "�ֿ���0" ||
	  $k == "�ֿ���1" ||$k == "�ֿ���2" ||$k == "�ֿ���3" ||
	  $k == "�ֿ���4" || $k == "®��" || $k == "®�٤���¾") && $v) {
	$tbl[$k] = $v;
	continue;
      }
      if (($k == "ī����̾" || $k == "ī�翩" || $k == "ī����" ||
	   $k == "�����" ||
	   $k == "�뿩��̾" || $k == "��翩" || $k == "������" ||
	   $k == "ͼ����̾" || $k == "ͼ�翩" || $k == "ͼ����") && $v) {
	$btbl[$k] = $v;
	continue;
      }
      if ($v && !($col++ % 2)) print '<tr>'; 
      if ($k == '��Ͽ��')
	print "<th nowrap>{$k}<td>{$name['lname']}&nbsp;{$name['fname']}\n";
      elseif ($k == '��Ͽ��' || $k == '��Ͽ����') {
	$time_tbl[$k] = $v;
	if (count($time_tbl) == 2) {
	  print "<th nowrap>��Ͽ����<td nowrap>".
	    disp_day_time($time_tbl['��Ͽ��'],$time_tbl['��Ͽ����']);
	  $col-=2;
	}
      }
      elseif ($k == '���̻ؼ�' && $v)
	print "<th nowrap><font color=red>{$k}</font>
               <td nowrap><font color=red>{$v}</font>\n";
      elseif ($k == '���ܻε�Ͽ' && $v) {
	print "<th nowrap>���ܻε�Ͽ�Ѥ�<td>\n";
	$done = 1;
      }
      elseif ($v)
	print "<th nowrap>{$k}<td nowrap>{$v}\n";
    }
    if (count($btbl)) {
      print "<tr><td colspan=4>
                 <table frame=border border=1><tr><td>
                 <th align=center>����̾
                 <th align=center>�翩
                 <th align=center>����";
      if ($btbl['ī����̾'] || $btbl['ī�翩'] || $btbl['ī����'])
	print "<tr><th>ī
                 <td align=center>{$btbl['ī����̾']}
                 <td align=center>{$btbl['ī�翩']}
                 <td align=center>{$btbl['ī����']}";
      if ($btbl['�뿩��̾'] || $btbl['��翩'] || $btbl['������'])
	print "<tr><th>��
                 <td align=center>{$btbl['�뿩��̾']}
                 <td align=center>{$btbl['��翩']}
                 <td align=center>{$btbl['������']}";
      if ($btbl['ͼ����̾'] || $btbl['ͼ�翩'] || $btbl['ͼ����'])
	print "<tr><th>ͼ
                 <td align=center>{$btbl['ͼ����̾']}
                 <td align=center>{$btbl['ͼ�翩']}
                 <td align=center>{$btbl['ͼ����']}";
      if ($btbl['�����'])
	print "<tr><th align=center>�����
                 <td align=left colspan=3>{$btbl['�����']}";
      print "</table>";
    }

    if (count($tbl)) {
      print '<tr><td colspan="4">
                 <table border="1"><tr><th align="center">����
                            <th align="center">����
                            <th align="center">��
                            <th align="center">��ʬ<br>�ֿ���';
      if ($tbl['��0'] || $tbl['����0'] || $tbl['��0'] || $tbl['�ֿ���0'])
	print "<tr><td align=center>".($tbl['��0']?$tbl['��0']:"-")."��
                   <td align=center>".($tbl['����0']?$tbl['����0']:"-")."
                   <td align=center>".($tbl['��0']?$tbl['��0']:"-")."ml
                   <td align=center>".($tbl['�ֿ���0']?$tbl['�ֿ���0']:"-")."ml";
      if ($tbl['��1'] || $tbl['����1'] || $tbl['��1'] || $tbl['�ֿ���1'])
	print "<tr><td align=center>".($tbl['��1']?$tbl['��1']:"-")."��
                   <td align=center>".($tbl['����1']?$tbl['����1']:"-")."
                   <td align=center>".($tbl['��1']?$tbl['��1']:"-")."ml
                   <td align=center>".($tbl['�ֿ���1']?$tbl['�ֿ���1']:"-")."ml";
      if ($tbl['��2'] || $tbl['����2'] || $tbl['��2'] || $tbl['�ֿ���2'])
	print "<tr><td align=center>".($tbl['��2']?$tbl['��2']:"-")."��
                   <td align=center>".($tbl['����2']?$tbl['����2']:"-")."
                   <td align=center>".($tbl['��2']?$tbl['��2']:"-")."ml
                   <td align=center>".($tbl['�ֿ���2']?$tbl['�ֿ���2']:"-")."ml";
      if ($tbl['��3'] || $tbl['����3'] || $tbl['��3'] || $tbl['�ֿ���3'])
	print "<tr><td align=center>".($tbl['��3']?$tbl['��3']:"-")."��
                   <td align=center>".($tbl['����3']?$tbl['����3']:"-")."
                   <td align=center>".($tbl['��3']?$tbl['��3']:"-")."ml
                   <td align=center>".($tbl['�ֿ���3']?$tbl['�ֿ���3']:"-")."ml";
      if ($tbl['��4'] || $tbl['����4'] || $tbl['��4'] || $tbl['�ֿ���4'])
	print "<tr><td align=center>".($tbl['��4']?$tbl['��4']:"-")."��
                   <td align=center>".($tbl['����4']?$tbl['����4']:"-")."
                   <td align=center>".($tbl['��4']?$tbl['��4']:"-")."ml
                   <td align=center>".($tbl['�ֿ���4']?$tbl['�ֿ���4']:"-")."ml";
      if ($tbl['®��'] || $tbl['®�٤���¾'])
	print "<tr><th align=center>®��
                   <td align=center>{$tbl['®��']}
                   <th align=center>®�٤���¾
                   <td align=center>{$tbl['®�٤���¾']}";
      print "</table>";
    }
}

$no_disp = array('ObjectID','Superseded','CreatedBy','��Ͽ��','��Ͽ����');

?>