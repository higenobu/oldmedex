<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
$uri = $_SERVER['SCRIPT_NAME'];

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo($auth);
echo "<hr>";

function show_hist_list($var){
  if ($list = get_prescription_list()) {
    print "<table><tr><th>�����ID<th>����ǯ����
                      <th>����������<th>��ʬ<th>����̾\n";
    foreach ($list as $ps) {
      $build = get_pat_build($ps['pid']);
      print "<tr><td>\n";
      print '<button type="submit" name="detail" value="' . $ps['oid'] .
	"\">�����ID{$ps['oid']}</button>";
      print "<td>{$ps['����ǯ����']}
             <td>{$ps['����������']}
             <td>{$ps['��ʬ']}
             <td>{$build['����̾']}\n";
    }
    print "</table><p>\n";
  } else
    print "�����������䵤�����ޤ���";
}

function show_hist_detail($var) {
  $oid = $var['detail'];

  if ($oid) {
    $ord = get_pharm_order($oid);
    $doc = get_emp_name($ord['��Ͽ��']);
    $injection = $ord['���'];
    if ($injection) $meds = get_meds($oid,1);
    else $meds = get_meds($oid,0);
    $room = get_pat_room($ord['����'],false);
    $pat = get_patient($ord['����'],false);
    print "<table>
           <tr><th>�����ID<td>{$oid}
               <th>����̾<td>{$pat['��']}��{$pat['̾']}
           <tr><th>������<td>{$doc['lname']}��{$doc['fname']}
               <th>�¼�<td>{$room['�¼�̾']}
           <tr><th>����ǯ����<td>{$ord['����ǯ����']}
               <th>����������<td>{$ord['����������']}
           <tr><th>����<td>{$ord['����']}
               <th>��ʬ<td>{$ord['��ʬ']}";

    /* ���Ƥ�ɽ�� */
    show_meds($meds,4);
    print "<tr><td><button type=\"button\"
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      ���ν����ΰ������̤򳫤�</button>
      </table>";
  }
}

print "<form method=\"post\" action=\"$uri\">\n";
print '<table border="0"><tr><td valign="top">' . "\n";
show_hist_list($_POST);
print "<tr><td><hr>";
show_hist_detail($_POST);
print "</table></form>\n";

?>