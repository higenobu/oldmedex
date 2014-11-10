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
  if ($var['i栄養士記録']) {
    if (!update_meal_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  if ($hists = get_meal_new_updates($search)) {
    print "<table><tr><th>食事箋ID<th>患者名<th>記録日時\n";
    foreach ($hists as $hist) {
      print "<tr><td>\n";
      $oid = $hist['ObjectID'];
      print '<button type="submit" name="detail" value="' . $oid . 
             "\">食事箋ID{$oid}</button>" .
	"<td>{$hist['姓']}&nbsp;{$hist['名']}
         <td>{$hist['記録日']}&nbsp;{$hist['記録時間']}\n";
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
    print '<input type="hidden" name="i記録日" value="'.$day.'">
           <input type="hidden" name="i記録時間" value="'.$time.'">';
    $ord = get_meal_order($oid);
    $pat = get_patient($ord['患者'],false);
    print '<input type="hidden" name="oid" value="'.$oid.'">';
    print "<table>
           <tr><th>ID<td>{$pat['患者ID']}
               <th>性別<td>".($pat['性別'] == "M" ? "男" :
	  		    ($pat['性別'] == "F" ? "女" : "")).
         "<tr><th>氏名<td>{$pat['姓']}&nbsp;{$pat['名']}
              <th>フリガナ<td>{$pat['フリガナ']}
          <tr><th>身長<td>".get_measure($pat['ObjectID'],"身長").
             "<th>体重<td>".get_measure($pat['ObjectID'],"体重").
         "<tr><th>生年月日<td>{$pat['生年月日']}
          </table>";
    print_meal_detail($ord,$done);
    print '<tr><td colspan=4>';
    if (!$done)
      print '<button type="submit" name="i栄養士記録" value="1">記録</button>';
    print '<button type="button"'. 
      "OnClick=\"window.open('print.php?oid={$oid}','',
          'width=640,height=640')\">この処方の印刷画面を開く</button>
        <tr><td colspan=4>";
    get_order_history("食事箋",$oid,"meal");
    print "</table>\n";
  }
}

function show_warning () {
  print "<p>医薬品と食品の相互作用<br>
             <ul><li>ワーファリンと納豆（作用減弱）
                 <li>アダラートカプセル５ｍｇとグレープフルーツジュース（作用増強）
                 <li>ニルジラート錠２ｍｇとグレープフルーツジュース（作用増強）
                 <li>ペルジピン錠１０ｍｇとグレープフルーツジュース（作用増強）
                 <li>ミノマイシンカプセル100ｍｇとカルシウム（牛乳など）（作用減弱）</ul>";
}

if (!$search)
  print '<ul><li><a href="'.$uri.'?search=1">実行日が今日と明日の食事箋の閲覧</a>
             <li><a href="'.$uri.'?search=2">記録済みで無い食事箋の閲覧</a></ul>';
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
