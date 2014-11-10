<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nutrition/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

$_POST['u'] = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

mx_html_head($auth[1]); print '<body>';
$pid = $_REQUEST['pid'];
$uri = $_SERVER['SCRIPT_NAME'];
$search = $_REQUEST['search'];
print "${auth[1]}&nbsp;";
draw_back('../../index.php');
mx_draw_userinfo();
echo "<p>";

function show_static_order($var) {
  global $search;
  if ($var['i���ܻε�Ͽ']) {
    if (!update_meal_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  if ($hists = get_meal_new_updates($search)) {
    print "<table><tr><th>�����ID<th>����̾<th>��Ͽ����\n";
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['ObjectID'];
      print '<button type="submit" name="detail" value="' . $oid . 
             "\">�����ID{$oid}</button>" .
	"<td>{$hist['��']}&nbsp;{$hist['̾']}
         <td>{$hist['��Ͽ��']}&nbsp;{$hist['��Ͽ����']}\n";
    }
    print "</table><p>\n";
  }
}

function show_static_detail ($var) {

  $oid = $var['detail']?$var['detail']:$var['oid'];

  if ($oid) {
    $today = date("Y-m-d H:i:s");
    $day = ereg_replace(" .*$","",$today);
    $time = ereg_replace(".* ","",$today);
    print '<input type="hidden" name="i��Ͽ��" value="'.$day.'">
           <input type="hidden" name="i��Ͽ����" value="'.$time.'">';
    $ord = get_meal_order($oid);
    $pat = get_patient($ord['����'],false);
    print '<input type="hidden" name="oid" value="'.$oid.'">';
    print "<table>
           <tr><th>ID<td>{$pat['����ID']}
               <th>����<td>".($pat['����'] == "M" ? "��" :
	  		    ($pat['����'] == "F" ? "��" : "")).
         "<tr><th>��̾<td>{$pat['��']}&nbsp;{$pat['̾']}
              <th>�եꥬ��<td>{$pat['�եꥬ��']}
          <tr><th>��Ĺ<td>".get_measure($pat['ObjectID'],"��Ĺ").
             "<th>�ν�<td>".get_measure($pat['ObjectID'],"�ν�").
         "<tr><th>��ǯ����<td>{$pat['��ǯ����']}
          </table>";
    print_meal_detail($ord,$done);
    print '<tr><td colspan=4>';
    if (!$done)
      print '<button type="submit" name="i���ܻε�Ͽ" value="1">��Ͽ</button>';
    print '<button type="button"'. 
      "OnClick=\"window.open('print.php?oid={$oid}','',
          'width=640,height=640')\">���ν����ΰ������̤򳫤�</button>
        <tr><td colspan=4>";
    get_order_history("�����",$oid,"meal");
    print "</table>\n";
  }
}

function show_warning () {
  print "<p>�����ʤȿ��ʤ���ߺ���<br>
             <ul><li>��ե�����ǼƦ�ʺ��Ѹ����
                 <li>�����顼�ȥ��ץ��룵���ȥ��졼�ץե롼�ĥ��塼���ʺ���������
                 <li>�˥른�顼�Ⱦ������ȥ��졼�ץե롼�ĥ��塼���ʺ���������
                 <li>�ڥ른�ԥ���������ȥ��졼�ץե롼�ĥ��塼���ʺ���������
                 <li>�ߥΥޥ����󥫥ץ���100���ȥ��륷����ʵ����ʤɡˡʺ��Ѹ����</ul>";
}

if (!$search)
  print '<ul><li><a href="'.$uri.'?search=1">�¹����������������ο���䵤α���</a>
             <li><a href="'.$uri.'?search=2">��Ͽ�Ѥߤ�̵������䵤α���</a></ul>';
else {
  print '<form method="post" action="'.$uri.'">
         <input type="hidden" name="search" value="'.$search.'"';
  print '<table border="0"><tr><td valign="top" width="50%">' . "\n";
  show_static_order($_REQUEST);
  print "<hr>";
  show_static_detail($_REQUEST);
  show_warning();
  print "</table></form>\n";
}
?>
</body></html>
