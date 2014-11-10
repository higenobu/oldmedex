<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/rehabdr/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';

$_REQUEST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}
mx_html_head($auth[1]); 
$action = $_POST['new'] ? "new" : ($_POST['copy'] ? "copy" : 
          ($_POST['update'] ? "update" : $_POST['action']));
$dbaction = $_POST['dbaction'];
$oid = $_POST['update'] ? $_POST['update'] : 
	($_POST['copy'] ? $_POST['copy'] : $_REQUEST['oid']);
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];
if ($_POST['i����ɷ���ɾ��'] || $_POST['����ɷ���ɾ��DEL'])
     print '<body onLoad="location.hash=\'word\';"';
elseif ($_POST['i��������ɾ��'] || $_POST['��������ɾ��DEL'])
     print '<body onLoad="location.hash=\'sound\';"';
elseif ($_POST['i�⼡Ǿ��ǽ����ɾ��'] || $_POST['�⼡Ǿ��ǽ����ɾ��DEL'])
     print '<body onLoad="location.hash=\'brain\';"';
elseif ($_POST['iİ��ɾ��'] || $_POST['İ��ɾ��DEL'])
     print '<body onLoad="location.hash=\'hear\';"';
else print '<body>';

function show_static_order($pat,$var) {
  global $action;

  if ($var['dbaction'] == "������Ͽ") {
    if (!insert_rehab_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }
  elseif ($var['dbaction'] == "����") {
    if (!update_rehab_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  $pid = $pat['ObjectID'] ? $pat['ObjectID'] : $var['pid'];
  $patf = $pat['̾'] ? $pat['̾'] : $var['patf'];
  $patl = $pat['��'] ? $pat['��'] : $var['patl'];
  print "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n
         <button type=\"submit\" name=\"new\" value=\"1\">
         ������Ͻ����</button>\n";
  if ($hists = get_rehab($pid,false)) {
    print "<table><tr><th>����ID<th>����ǯ����<th>��ʬ<th>��ˡ\n";
    foreach ($hists as $hist) {
      $oid = $hist['ObjectID'];
      if ($hist['������ˡ'] == "on") $method = "������ˡ";
      if ($hist['�����ˡ'] == "on") $method = $method . "&nbsp;�����ˡ";
      if ($hist['����İ����ˡ'] == "on") $method = $method . "&nbsp;����İ����ˡ";
      print '<tr><td><button type="submit" name="detail" value="'.$oid. 
	              '">����ID'.$oid.'</button>
	         <td>'.$hist['������'].'<td>'.$hist['������ʬ'].
	        "<td>{$method}\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail ($var) {
  global $state;
  $oid= $var['detail'] ? $var['detail'] : 
    ($var['copy'] ? $var['copy'] : 
     ($var['update'] ? $var['update'] : $_REQUEST['oid']));

  if ($oid) {
    $ord = get_rehab(false,$oid);
    print '<input type="hidden" name="oid" value="'.$oid.'">';

    print "<table border=1><tr><th>����ID<td>{$oid}<tr>";
    print_detail($ord);
    print '<tr>
       <td colspan="4" align="left">
       <button type="submit" name="copy" value="'.$oid.'">���ԡ�</button>';
    if ($ord['CreatedBy'] == $var['u'])
      print '<button type="submit" name="update" value="'.
	$oid.'">����</button>';
    print "<button type=\"button\" 
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      ���ν����ΰ������̤򳫤�</button>
      <tr>
      <td colspan=4>";
    get_order_history("��Ͻ����",$oid,"rehab");
    print '</table>';
  }
}

function show_edit_order($var) {
  global $action, $auth, $oid;

  $pid = $var['pid'];
  if (!$action) return;
  if ($pid && $var["dbaction"] != "������Ͽ") {
    if ($var['copy'] || $var['update']) {
      $ord = get_rehab(false,$oid);
    } elseif (!$var['new']) {
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    }
    print '<input type="hidden" name="action" value="'.$action.'">
           <input type="hidden" name="i����" value="'.$pid.'">';
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    $dname = get_emp_name($ord['���']);
    $today = date("Y-m-d");
    print '<table border="1">';
    if ($action == "update") print "<tr><td><th>����ID<td align=center>{$oid}<td><td>";
    print 
      "<tr><th>��Ͽ��<th align=center>
           {$rec['name']['lname']}&nbsp;{$rec['name']['fname']}\n".
          '<input type="hidden" name="i��Ͽ��" value="'.$rec['id'].'">
           <th>��Ͽ��<td align=center>'.$today.
      '<input type="hidden" name="i��Ͽ��" value="'.$today.'">
       <tr><th>�������<td>';
    list_doctors('i���',$_POST['���'],$pid,"rehab",$rec);
    print
          '<th>������<td>'.print_input('������',10,($ord["������"] ? $ord["������"] : date("Y-m-d"))).
      '<tr><th>��Ͻ���<td>'.print_checkb("��Ͻ���",$ord).
	'<th>������ʬ<td colspan="3">'.print_select_com("������ʬ",$ord,0,false,false).
      '<tr><th>�����ͳ<td>'.print_checkb("�����ͳ",$ord).
          '<th>������<td>'.print_input("�����ͳ������",33,$ord["�����ͳ������"]).
      '<tr><th colspan="4">��ǽ�㳲
       <tr><th>�ռ��㳲<td colspan="3">'.print_select_com("�ռ��㳲",$ord,22,"������",false).
      '<tr><th>JCS<td colspan="3">'.print_select_com("JCS",$ord,0,false,false).'<td><td>
       <tr><th>�������㳲<td>'.print_select_com("����",$ord,22,"������",false).
          '<th>��Ū�㳲<td>'.print_select_com("��Ū�㳲",$ord,22,"������",false).
      '<tr><th colspan="4">�⼡��ǽ�㳲
       <tr><th>����Ͼ㳲<td>'.print_select_com("��վ㳲",$ord,22,"������",false).
          '<th>�����Ͼ㳲<td>'.print_select_com("�����㳲",$ord,22,"������",false).
      '<tr><th>��ǧ<td>'.print_select_com("��ǧ",$ord,22,"������",false).
          '<th>����<td>'.print_select_com("����",$ord,22,"������",false).
      '<tr><th>����<td>'.print_select_com("����",$ord,22,"������",false).
          '<th nowrap>Ⱦ¦�����̵��<td nowrap>'.print_select_com("Ⱦ¦�����̵��",$ord,22,"������",false).
      '<tr><th colspan="4">�γо㳲
       <tr><th nowrap>��о㳲<td nowrap>'.print_select_com("��о㳲",$ord,22,"������",false).
          '<th>İ�о㳲<td>'.print_select_com("İ�о㳲",$ord,22,"������",false).
      '<tr><th>ɽ�ߴ��о㳲<td>'.print_select_com("ɽ�ߴ��о㳲",$ord,22,"������",false).
          '<th>�������о㳲<td>'.print_select_com("�������о㳲",$ord,22,"������",false).
      '<tr><th>�ˤ�<td>'.print_select_com("�ˤ�",$ord,22,"������",false).
          '<th>�����㳲<td>'.print_select_com("�����㳲",$ord,22,"������",false).
      '<tr><th>�Ƶۡ��۴Ĵ�㳲<br>�ʵ�Ω����찵�������۴ľ㳲��<td>'.
               print_select_com("�Ƶ۽۴Ĵﵡǽ�㳲",$ord,22,"������",false).
          '<th>�ݿ���ǽ�㳲<td>'.print_select_com("�ݿ���ǽ�㳲",$ord,22,"������",false).
      '<td><td><tr><th colspan="4">������ǽ�㳲
       <tr><th>��Ǣ��ǽ�㳲<td>'.print_select_com("��Ǣ��ǽ�㳲",$ord,22,"������",false).
           '<th>���ص�ǽ�㳲<td>'.print_select_com("���ص�ǽ�㳲",$ord,22,"������",false).
      '<tr><th>���������<td>'.print_select_com("���������",$ord,22,"������",false).
          '<th>����<td>'.print_select_com("����",$ord,22,"������",false).
      '<tr><th>�����㲼<td>'.print_select_com("�����㲼",$ord,22,"������",false).
      '<tr><th colspan="4">�ڶ�ĥ�ξ㳲
       <tr><th>�д�<td>'.print_select_com("�д�",$ord,22,"������",false).
          '<th>����<td>'.print_select_com("����",$ord,22,"������",false).
      '<tr><th>�ǽ�<td>'.print_select_com("�ǽ�",$ord,22,"������",false).
          '<th>�Կ�ձ�ư<br>�ʼ�Ĵ�������<td>'.print_select_com("�Կ�ձ�ư",$ord,22,"������",false).
      '<tr><th>����<td>'.print_select_com("����",$ord,22,"������",false).
          '<th>��ǽ�㳲������<td>'.print_input("��ǽ�㳲������",33,$ord["��ǽ�㳲������"]).
      '<tr><th>��������<td>'.print_select_com("��������",$ord,0,false,false).
      '<tr><th>��ư����˥���<td>'.print_checkb("��ư����˥���ɬ��",$ord).
          '<th>��˥��˥󥰤�����
           <td>'.print_input('��˥��˥󥰤�����',22,$ord['��˥��˥󥰤�����']).
      '<tr><th colspan="4">������ߴ��&nbsp;
           85�аʾ�ξ��ϡ�������ξ�¤�Ĥ����0.9(220��-��ǯ��)��
       <tr><th>�ռ���٥��㲼<td>'.print_checkb("�ռ���٥��㲼",$ord).
          '<th>�������ν���<td>'.print_checkb("�������ν���",$ord).
      '<tr><th>�β�<td>'.print_select_com("�β�",$ord,10,"��ͳ����",false).'��ʾ�
       <tr><th>���̴��찵
           <td>'.print_input('���̴��찵',10,$ord['���̴��찵']).'���ȣ�ʾ�
           <th>��ĥ���찵
           <td>'.print_input('��ĥ���찵',10,$ord['��ĥ���찵']).'���ȣ�ʾ�
       <tr><th>SPO2%<td>'.print_select_com("SPO2",$ord,"10","��ͳ����","��ʲ�<br>").'��ʲ�
           <th>Anderson�δ��<td>'.print_select_com("Anderson�δ��",$ord,22,"������","<br>").
      '<tr><th colspan="4">���ԥˡ����ʥ��Ū��<br>��ɸ�ʷ�����ɸ��
       <tr><th>����ư��ǽ��<br>��ɸ�Ȥ����٥������<th>'.print_select_com("����ư��ǽ��",$ord,0,false,false).
          '<th>���َ̎���ǽ��<br>��ɸ�Ȥ����٥������<th>'.print_select_com("���َ̎���ǽ��",$ord,0,false,false).
      '<tr><th>ǧ��ǽ��<br>��ɸ�Ȥ����٥������<th>'.print_select_com("ǧ��ǽ��",$ord,0,false,false).
          '<th>������<td>'.print_input("��ɸ������",22,$ord["��ɸ������"]).
      '<tr><th colspan="4">����
       <tr><th>�����ư�跱��<td>'.print_checkb("�����ư�跱��",$ord).
          '<th>������������<td>'.print_checkb("������������",$ord).
      '<tr><th>���жںƶ���<td>'.print_checkb("���жںƶ���",$ord).
          '<th>��Ĵ������<td>'.print_checkb("��Ĵ������",$ord).
      '<tr><th>����Ĵ������<td colspan="3">'.print_checkb("����Ĵ������",$ord).'<p>ͭ���Ǳ�ư<br>';
    foreach (array('���','�ְػҶ�ư','����ư��','�°̤Ǥ����ȱ�ư','�����˱�����') as $item) {
      printf('<input type="checkbox" name="iͭ���Ǳ�ư%s" %s>%s��',
	     $item,($ord['ͭ���Ǳ�ư'.$item] == "on" ? "checked" : ""),$item);
      print print_select_com("ͭ���Ǳ�ư".$item."Time",$ord,4,"��ͳ����","ʬ��<br>");
      print "ʬ��<br>\n";
    }
    print '�ޤ��ϡ�'.print_input("ͭ���Ǳ�ư",22,$ord["ͭ���Ǳ�ư"]).'��'.
           print_input("ͭ���Ǳ�ưTime",10,$ord["ͭ���Ǳ�ưTime"]).'ʬ��<p>
           ��ɸ�����'.print_input('ͭ���Ǳ�ư�����',10,$ord['ͭ���Ǳ�ư�����']).'b��ʬ Max HR'.
           print_input('ͭ���Ǳ�ư�����MAX',10,$ord['ͭ���Ǳ�ư�����MAX']).'%�ն�<p>
           ����ȥơ��֥�<br>'.
           print_input('����ȥơ��֥�',10,$ord['����ȥơ��֥�']).'&deg;'.
           print_input('����ȥơ��֥�Time',10,$ord['����ȥơ��֥�Time']).'ʬ�� X'.
           print_input('����ȥơ��֥�Set',10,$ord['����ȥơ��֥�Set']).'���å�<p>
           �٥åɥ���å�<br>'.
           print_select_com("�٥åɥ���å�",$ord,0,false,false).'&deg;'.
           print_select_com("�٥åɥ���å�Time",$ord,0,false,false).'ʬ�� X'.
           print_select_com("�٥åɥ���å�Set",$ord,0,false,false).'���å�<br>�ޤ��ϡ�'.
           print_input('�٥åɥ���å���ͳ����',8,$ord['�٥åɥ���å���ͳ����']).'&deg;'.
           print_input('�٥åɥ���å�Time��ͳ����',6,$ord['�٥åɥ���å�Time��ͳ����']).'ʬ�� X'.
           print_input('�٥åɥ���å�Set��ͳ����',4,$ord['�٥åɥ���å�Set��ͳ����']).'���å�<p>
           <p>��ɸ�����'.print_input('�٥åɥ���å������',8,$ord['�٥åɥ���å������']).'b��ʬ Max HR'.
           print_input('�٥åɥ���å������MAX',8,$ord['�٥åɥ���å������MAX']).'%�ն�<p>
           SPO2 '.print_input('SPO2MAX',6,$ord['SPO2MAX']).'��ʲ��β��ߤǵٷƤ�Ȥ롣
       <tr><th>����ư���<td>'.print_checkb("����ư���",$ord).
          '<th>����ư���<td>'.print_checkb("����ư���",$ord).
      '<tr><th>���������ư����<td>'.print_checkb("���������ư����",$ord).
          '<th>�����Ϣư���<td>'.print_checkb("�����Ϣư���",$ord).
      '<tr><th>ǧ�η���<td>'.print_checkb("ǧ�η���",$ord).
          '<th>����Ķ�����<td>'.print_checkb("����Ķ�����",$ord).
      '<tr><th>�����񡦼�����θ�Ƥ<td>'.print_checkb("�����񡦼�����θ�Ƥ",$ord).
          '<th>���������
           <td><input type="text" name="i���������" value="'.$ord['���������'].'">
       <tr><th>����ɾ��������<td>'.print_checkb("����ɾ��������",$ord).
          '<th>������ɾ��<td>'.print_checkb("������ɾ��",$ord).
      '<tr><th>����¾�����ɾ������<td>'.print_checkb("����¾�����ɾ������",$ord).
          '<th>ɾ������
           <td><input type="text" name="iɾ������" value="'.$ord['ɾ������'].'">
       <tr><th><a name="word">����ɷ���</a>
           <td><button type=submit name="i����ɷ���ɾ��" value="1">ɾ��ͭ��</button>
               <button type=submit name="����ɷ���ɾ��DEL" value="1">ɾ��̵��</button>
       <tr><td><td>'.print_checkb("����ɷ���",$ord).
          '<th>������
           <td><input type="text" name="i����ɷ���������" value="'.$ord['����ɷ���������'].'">';
    if ($ord['����ɷ���ɾ��'] && !$_POST['����ɷ���ɾ��DEL'])
      print '
       <tr><th colspan="4">����ɸ���
       <tr><th>���Ū����<td>'.print_checkb("���Ū����",$ord).
          '<th>���겼������<br>İ������<td>'.print_checkb("İ������",$ord).
      '<tr><th>�ä�����<td>'.print_checkb("�ä�����",$ord).
	  '<th>�ɤ߽񤭲���<td>'.print_checkb("�ɤ߽񤭲���",$ord).
      '<tr><th>��ʸǽ��<td>'.print_checkb("��ʸǽ��",$ord).
	  '<th>����Ū�ʎ��Ў��Ǝ��������ݤ˴ؤ��븡��<td>'.print_checkb("CADL",$ord).
      '<tr><th>����������ټ��줬��������<td>'.print_checkb("���ټ���ɸ���",$ord).
	  '<th>������
           <td><input type="text" name="i����ɸ���������" value="'.$ord['����ɸ���������'].'">
               <input type="hidden" name="i����ɷ���ɾ��" value="on">';

    print '<tr><th><a name="sound">��������</a>
           <td><button type=submit name="i��������ɾ��" value="1">ɾ��ͭ��</button>
               <button type=submit name="��������ɾ��DEL" value="1">ɾ��̵��</button>

       <tr><td><td>'.print_checkb("��������",$ord).
          '<th>������
           <td><input type="text" name="i��������������" value="'.$ord['��������������'].'">';

    if ($ord['��������ɾ��'] && !$_POST['��������ɾ��DEL'])
      print '
       <tr><th colspan="4">��������
       <tr><th>��������<td>'.print_checkb("��������",$ord).
          '<th>������
           <td><input type="text" name="i��������������" value="'.$ord['��������������'].'">
               <input type="hidden" name="i��������ɾ��" value="on">';

    print '<tr><th><a name="brain">�⼡Ǿ��ǽ����</a>
           <td><button type=submit name="i�⼡Ǿ��ǽ����ɾ��" value="1">ɾ��ͭ��</button>
               <button type=submit name="�⼡Ǿ��ǽ����ɾ��DEL" value="1">ɾ��̵��</button>

       <tr><td><td>'.print_checkb("�⼡Ǿ��ǽ����",$ord).
          '<th>������
           <td><input type="text" name="i�⼡Ǿ��ǽ����������" value="'.$ord['�⼡Ǿ��ǽ����������'].'">';
    if ($ord['�⼡Ǿ��ǽ����ɾ��'] && !$_POST['�⼡Ǿ��ǽ����ɾ��DEL'])
      print '
       <tr><th colspan="4">�⼡Ǿ��ǽɾ��
       <tr><th>��ǽ����<td>'.print_checkb("��ǽ����",$ord).
          '<th>Ⱦ¦����̵�롦Ⱦ�ո���<td>'.print_checkb("Ⱦ�ո���",$ord).
      '<tr><th>��ո���<td>'.print_checkb("��ո���",$ord).
          '<th>��������<td>'.print_checkb("��������",$ord).
      '<tr><th>���ԡ���ǧ<td>'.print_checkb("���ԡ���ǧ",$ord).
          '<th>��Ƭ�յ�ǽ<td>'.print_checkb("��Ƭ�յ�ǽ",$ord).
      '<tr><th>������
           <td><input type="text" name="i�⼡Ǿ��ǽɾ��������" value="'.$ord['�⼡Ǿ��ǽɾ��������'].'">
               <input type="hidden" name="i�⼡Ǿ��ǽ����ɾ��" value="on">';

    print '<tr><th>�ݿ��벼����<td>'.print_checkb("�ݿ��벼����",$ord).
          '<th>������
           <td><input type="text" name="i�ݿ��벼����������" value="'.$ord['�ݿ��벼����������'].'"> 
       <tr><th>VF�ܹ���(yyyy-mm-dd)
           <td><input type="text" name="iVF�ܹ���" value="'.$ord['VF�ܹ���'].'"> 
           <th>VF��Ū
           <td><input type="text" name="iVF��Ū" value="'.$ord['VF��Ū'].'"> 
       <tr><th><a name="hear">İ��ɾ��</a>
           <td><button type=submit name="iİ��ɾ��" value="1">ɾ��ͭ��</button>
               <button type=submit name="İ��ɾ��DEL" value="1">ɾ��̵��</button>';

    if ($ord['İ��ɾ��'] && !$_POST['İ��ɾ��DEL'])
      print '
           <th>İ��ɾ��������
           <td><input type="text" name="iİ��ɾ��������" value="'.$ord['İ��ɾ��������'].'">
       <tr><th colspan="4">İ�ϸ���
       <tr><th>��������<td>'.print_checkb("İ�ϸ���",$ord).
          '<th>������
           <td><input type="text" name="iİ�ϸ���������" value="'.$ord['İ�ϸ���������'].'">
               <input type="hidden" name="iİ��ɾ��" value="on">';

    print '<tr><th colspan="4">ʪ����ˡ';
    foreach(array('�ۥåȥѥå�','�ޥ�����������','Ķ����ˡ��','�����ˡ��',
                  '��ή��','�������ѥå�','�ϥɥޡ�','����')
	    as $item) {
      if (!($c++ % 2)) print "<tr>";
      printf('<td><input type="checkbox" name="i%s" %s> %s
              <td><input type="text" name="i%s����" value="%s"> ����',
	     $item,($ord[$item] == "on" ? "checked" : ""),$item,$item,$ord[$item.'����']);
    }
    print '<tr><th colsapn="4">����
           <tr><td>'.print_checkb("����",$ord).'<td>'.
      print_select_com("��������",$ord,0,false,false).
              '<td>'.print_checkb("����",$ord).'<td>'.
      print_select_com("��������",$ord,0,false,false).
   '<tr><th>����¾�õ�����<td colspan="3">'.
      print_input("����¾�õ�����",44,$ord["����¾�õ�����"]).
   '</table>';
    if ($action == "new" || $action == "copy") $label = "������Ͽ";
    else $label = "����";
    print '<button type="submit" name="dbaction" value="'.$label.'">'
      .$label."</button>\n";
  }
}

print '<table border="0"><tr><td valign="top" width="40%">';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
print '<td valign="top" align="left">';
if (!$action && !$pid) {
	/*
	 * This part is incredibly stupid.  It sometimes draws and
	 * it sometimes doesn't.  If it is _functional_ it should do
	 * its thing and leave the drawing to the caller.  Otherwise
	 * it should always draw stuff.  This stupid style does not
	 * let the caller to tweak how the output begins with X-<.
	 */

  $pat = get_pat("");
  if (!$pat) {
    print '</table>';
    return;
  }
  $pid = $pat['ObjectID'];
}
$stmt = ('SELECT "����ID" FROM "������Ģ" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['����ID'];

mx_draw_patientinfo_brief($pid);
mx_draw_ppa_applist($pt_hid);
print '</td></tr></table>';
print '<hr />';

print "<form method=\"post\" action=\"{$uri}\">\n";
print '<table with="800" style="border-collapse: collapse; border: hidden">
       <tr><td valign="top" width=50% style="border-right: solid">'."\n";
show_static_order($pat,$_REQUEST);
print "<hr>";
show_static_detail($_REQUEST);
print '<td valign="top" width="50%">';
show_edit_order($_REQUEST);
print "</table></form>\n";

?>
</body></html>
