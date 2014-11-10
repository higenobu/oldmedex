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
if ($_POST['i失語症訓練評価'] || $_POST['失語症訓練評価DEL'])
     print '<body onLoad="location.hash=\'word\';"';
elseif ($_POST['i構音訓練評価'] || $_POST['構音訓練評価DEL'])
     print '<body onLoad="location.hash=\'sound\';"';
elseif ($_POST['i高次脳機能訓練評価'] || $_POST['高次脳機能訓練評価DEL'])
     print '<body onLoad="location.hash=\'brain\';"';
elseif ($_POST['i聴覚評価'] || $_POST['聴覚評価DEL'])
     print '<body onLoad="location.hash=\'hear\';"';
else print '<body>';

function show_static_order($pat,$var) {
  global $action;

  if ($var['dbaction'] == "新規登録") {
    if (!insert_rehab_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }
  elseif ($var['dbaction'] == "更新") {
    if (!update_rehab_order($var))
      pg_query(mx_db_connect(),"rollback");
    flush();
  }

  $pid = $pat['ObjectID'] ? $pat['ObjectID'] : $var['pid'];
  $patf = $pat['名'] ? $pat['名'] : $var['patf'];
  $patl = $pat['姓'] ? $pat['姓'] : $var['patl'];
  print "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n
         <input type=\"hidden\" name=\"patl\" value=\"$patl\">\n
         <input type=\"hidden\" name=\"patf\" value=\"$patf\">\n
         <button type=\"submit\" name=\"new\" value=\"1\">
         新規リハ処方箋</button>\n";
  if ($hists = get_rehab($pid,false)) {
    print "<table><tr><th>リハ箋ID<th>処方年月日<th>区分<th>療法\n";
    foreach ($hists as $hist) {
      $oid = $hist['ObjectID'];
      if ($hist['理学療法'] == "on") $method = "理学療法";
      if ($hist['作業療法'] == "on") $method = $method . "&nbsp;作業療法";
      if ($hist['言語聴覚療法'] == "on") $method = $method . "&nbsp;言語聴覚療法";
      print '<tr><td><button type="submit" name="detail" value="'.$oid. 
	              '">リハ箋ID'.$oid.'</button>
	         <td>'.$hist['処方日'].'<td>'.$hist['処方区分'].
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

    print "<table border=1><tr><th>リハ箋ID<td>{$oid}<tr>";
    print_detail($ord);
    print '<tr>
       <td colspan="4" align="left">
       <button type="submit" name="copy" value="'.$oid.'">コピー</button>';
    if ($ord['CreatedBy'] == $var['u'])
      print '<button type="submit" name="update" value="'.
	$oid.'">更新</button>';
    print "<button type=\"button\" 
      OnClick=\"window.open('print.php?oid={$oid}','',
      'width=640,height=640')\">
      この処方の印刷画面を開く</button>
      <tr>
      <td colspan=4>";
    get_order_history("リハ処方箋",$oid,"rehab");
    print '</table>';
  }
}

function show_edit_order($var) {
  global $action, $auth, $oid;

  $pid = $var['pid'];
  if (!$action) return;
  if ($pid && $var["dbaction"] != "新規登録") {
    if ($var['copy'] || $var['update']) {
      $ord = get_rehab(false,$oid);
    } elseif (!$var['new']) {
      foreach ($var as $k => $v)
	if (ereg("^i.*",$k)) $ord[substr($k,1)] = $v;
    }
    print '<input type="hidden" name="action" value="'.$action.'">
           <input type="hidden" name="i患者" value="'.$pid.'">';
    $rec['id'] = $auth[2]['ObjectID'];
    $rec['name'] = get_emp_name($rec['id']);
    $dname = get_emp_name($ord['医者']);
    $today = date("Y-m-d");
    print '<table border="1">';
    if ($action == "update") print "<tr><td><th>リハ箋ID<td align=center>{$oid}<td><td>";
    print 
      "<tr><th>記録者<th align=center>
           {$rec['name']['lname']}&nbsp;{$rec['name']['fname']}\n".
          '<input type="hidden" name="i記録者" value="'.$rec['id'].'">
           <th>記録日<td align=center>'.$today.
      '<input type="hidden" name="i記録日" value="'.$today.'">
       <tr><th>処方医師<td>';
    list_doctors('i医者',$_POST['医者'],$pid,"rehab",$rec);
    print
          '<th>処方日<td>'.print_input('処方日',10,($ord["処方日"] ? $ord["処方日"] : date("Y-m-d"))).
      '<tr><th>リハ処方<td>'.print_checkb("リハ処方",$ord).
	'<th>処方区分<td colspan="3">'.print_select_com("処方区分",$ord,0,false,false).
      '<tr><th>中止理由<td>'.print_checkb("中止理由",$ord).
          '<th>コメント<td>'.print_input("中止理由コメント",33,$ord["中止理由コメント"]).
      '<tr><th colspan="4">機能障害
       <tr><th>意識障害<td colspan="3">'.print_select_com("意識障害",$ord,22,"コメント",false).
      '<tr><th>JCS<td colspan="3">'.print_select_com("JCS",$ord,0,false,false).'<td><td>
       <tr><th>見当識障害<td>'.print_select_com("痴呆",$ord,22,"コメント",false).
          '<th>知的障害<td>'.print_select_com("知的障害",$ord,22,"コメント",false).
      '<tr><th colspan="4">高次機能障害
       <tr><th>注意力障害<td>'.print_select_com("注意障害",$ord,22,"コメント",false).
          '<th>記銘力障害<td>'.print_select_com("記憶障害",$ord,22,"コメント",false).
      '<tr><th>失認<td>'.print_select_com("失認",$ord,22,"コメント",false).
          '<th>失行<td>'.print_select_com("失行",$ord,22,"コメント",false).
      '<tr><th>失語<td>'.print_select_com("失語",$ord,22,"コメント",false).
          '<th nowrap>半側視空間無視<td nowrap>'.print_select_com("半側視空間無視",$ord,22,"コメント",false).
      '<tr><th colspan="4">知覚障害
       <tr><th nowrap>視覚障害<td nowrap>'.print_select_com("視覚障害",$ord,22,"コメント",false).
          '<th>聴覚障害<td>'.print_select_com("聴覚障害",$ord,22,"コメント",false).
      '<tr><th>表在感覚障害<td>'.print_select_com("表在感覚障害",$ord,22,"コメント",false).
          '<th>深部感覚障害<td>'.print_select_com("深部感覚障害",$ord,22,"コメント",false).
      '<tr><th>痛み<td>'.print_select_com("痛み",$ord,22,"コメント",false).
          '<th>構音障害<td>'.print_select_com("構音障害",$ord,22,"コメント",false).
      '<tr><th>呼吸・循環器障害<br>（起立性低血圧・末梢循環障害）<td>'.
               print_select_com("呼吸循環器機能障害",$ord,22,"コメント",false).
          '<th>摂食機能障害<td>'.print_select_com("摂食機能障害",$ord,22,"コメント",false).
      '<td><td><tr><th colspan="4">排泄機能障害
       <tr><th>排尿機能障害<td>'.print_select_com("排尿機能障害",$ord,22,"コメント",false).
           '<th>排便機能障害<td>'.print_select_com("排便機能障害",$ord,22,"コメント",false).
      '<tr><th>中枢性麻痺<td>'.print_select_com("中枢性麻痺",$ord,22,"コメント",false).
          '<th>拘縮<td>'.print_select_com("拘縮",$ord,22,"コメント",false).
      '<tr><th>筋力低下<td>'.print_select_com("筋力低下",$ord,22,"コメント",false).
      '<tr><th colspan="4">筋緊張の障害
       <tr><th>弛緩<td>'.print_select_com("弛緩",$ord,22,"コメント",false).
          '<th>痙性<td>'.print_select_com("痙性",$ord,22,"コメント",false).
      '<tr><th>固縮<td>'.print_select_com("固縮",$ord,22,"コメント",false).
          '<th>不随意運動<br>（失調・振戦）<td>'.print_select_com("不随意運動",$ord,22,"コメント",false).
      '<tr><th>褥創<td>'.print_select_com("褥創",$ord,22,"コメント",false).
          '<th>機能障害コメント<td>'.print_input("機能障害コメント",33,$ord["機能障害コメント"]).
      '<tr><th>訓練形態<td>'.print_select_com("訓練形態",$ord,0,false,false).
      '<tr><th>運動時モニター<td>'.print_checkb("運動時モニター必要",$ord).
          '<th>モニタニングの内容
           <td>'.print_input('モニタニングの内容',22,$ord['モニタニングの内容']).
      '<tr><th colspan="4">訓練中止基準&nbsp;
           85歳以上の場合は、心拍数の上限をつける（0.9(220　-　年齢)）
       <tr><th>意識レベル低下<td>'.print_checkb("意識レベル低下",$ord).
          '<th>けいれんの重責<td>'.print_checkb("けいれんの重責",$ord).
      '<tr><th>体温<td>'.print_select_com("体温",$ord,10,"自由記載",false).'℃以上
       <tr><th>収縮期血圧
           <td>'.print_input('収縮期血圧',10,$ord['収縮期血圧']).'ｍｍＨｇ以上
           <th>拡張期血圧
           <td>'.print_input('拡張期血圧',10,$ord['拡張期血圧']).'ｍｍＨｇ以上
       <tr><th>SPO2%<td>'.print_select_com("SPO2",$ord,"10","自由記載","％以下<br>").'％以下
           <th>Andersonの基準<td>'.print_select_com("Andersonの基準",$ord,22,"コメント","<br>").
      '<tr><th colspan="4">患者ニーズ（リハ的）<br>目標（訓練目標）
       <tr><th>基本動作能力<br>目標とするレベルを選択<th>'.print_select_com("基本動作能力",$ord,0,false,false).
          '<th>ｾﾙﾌｹｱ能力<br>目標とするレベルを選択<th>'.print_select_com("ｾﾙﾌｹｱ能力",$ord,0,false,false).
      '<tr><th>認知能力<br>目標とするレベルを選択<th>'.print_select_com("認知能力",$ord,0,false,false).
          '<th>コメント<td>'.print_input("目標コメント",22,$ord["目標コメント"]).
      '<tr><th colspan="4">内容
       <tr><th>関節可動域訓練<td>'.print_checkb("関節可動域訓練",$ord).
          '<th>筋力増強訓練<td>'.print_checkb("筋力増強訓練",$ord).
      '<tr><th>神経筋再教育<td>'.print_checkb("神経筋再教育",$ord).
          '<th>協調性訓練<td>'.print_checkb("協調性訓練",$ord).
      '<tr><th>全身調整訓練<td colspan="3">'.print_checkb("全身調整訓練",$ord).'<p>有酸素運動<br>';
    foreach (array('歩行','車椅子駆動','起居動作','座位での全身運動','状況に応じて') as $item) {
      printf('<input type="checkbox" name="i有酸素運動%s" %s>%sを',
	     $item,($ord['有酸素運動'.$item] == "on" ? "checked" : ""),$item);
      print print_select_com("有酸素運動".$item."Time",$ord,4,"自由記載","分間<br>");
      print "分間<br>\n";
    }
    print 'または、'.print_input("有酸素運動",22,$ord["有酸素運動"]).'を'.
           print_input("有酸素運動Time",10,$ord["有酸素運動Time"]).'分間<p>
           目標心拍数'.print_input('有酸素運動心拍数',10,$ord['有酸素運動心拍数']).'b／分 Max HR'.
           print_input('有酸素運動心拍数MAX',10,$ord['有酸素運動心拍数MAX']).'%付近<p>
           チルトテーブル<br>'.
           print_input('チルトテーブル',10,$ord['チルトテーブル']).'&deg;'.
           print_input('チルトテーブルTime',10,$ord['チルトテーブルTime']).'分間 X'.
           print_input('チルトテーブルSet',10,$ord['チルトテーブルSet']).'セット<p>
           ベッドギャッジ<br>'.
           print_select_com("ベッドギャッジ",$ord,0,false,false).'&deg;'.
           print_select_com("ベッドギャッジTime",$ord,0,false,false).'分間 X'.
           print_select_com("ベッドギャッジSet",$ord,0,false,false).'セット<br>または、'.
           print_input('ベッドギャッジ自由記載',8,$ord['ベッドギャッジ自由記載']).'&deg;'.
           print_input('ベッドギャッジTime自由記載',6,$ord['ベッドギャッジTime自由記載']).'分間 X'.
           print_input('ベッドギャッジSet自由記載',4,$ord['ベッドギャッジSet自由記載']).'セット<p>
           <p>目標心拍数'.print_input('ベッドギャッジ心拍数',8,$ord['ベッドギャッジ心拍数']).'b／分 Max HR'.
           print_input('ベッドギャッジ心拍数MAX',8,$ord['ベッドギャッジ心拍数MAX']).'%付近<p>
           SPO2 '.print_input('SPO2MAX',6,$ord['SPO2MAX']).'％以下の下降で休憩をとる。
       <tr><th>巧緻動作訓練<td>'.print_checkb("巧緻動作訓練",$ord).
          '<th>基本動作訓練<td>'.print_checkb("基本動作訓練",$ord).
      '<tr><th>日常生活活動訓練<td>'.print_checkb("日常生活活動訓練",$ord).
          '<th>日常関連動作訓練<td>'.print_checkb("日常関連動作訓練",$ord).
      '<tr><th>認知訓練<td>'.print_checkb("認知訓練",$ord).
          '<th>病棟環境設定<td>'.print_checkb("病棟環境設定",$ord).
      '<tr><th>補装具・自助具の検討<td>'.print_checkb("補装具・自助具の検討",$ord).
          '<th>補装具種類
           <td><input type="text" name="i補装具種類" value="'.$ord['補装具種類'].'">
       <tr><th>在宅評価・訓練<td>'.print_checkb("在宅評価・訓練",$ord).
          '<th>持久力評価<td>'.print_checkb("持久力評価",$ord).
      '<tr><th>その他特定の評価依頼<td>'.print_checkb("その他特定の評価依頼",$ord).
          '<th>評価内容
           <td><input type="text" name="i評価内容" value="'.$ord['評価内容'].'">
       <tr><th><a name="word">失語症訓練</a>
           <td><button type=submit name="i失語症訓練評価" value="1">評価有り</button>
               <button type=submit name="失語症訓練評価DEL" value="1">評価無し</button>
       <tr><td><td>'.print_checkb("失語症訓練",$ord).
          '<th>コメント
           <td><input type="text" name="i失語症訓練コメント" value="'.$ord['失語症訓練コメント'].'">';
    if ($ord['失語症訓練評価'] && !$_POST['失語症訓練評価DEL'])
      print '
       <tr><th colspan="4">失語症検査
       <tr><th>総合的検査<td>'.print_checkb("総合的検査",$ord).
          '<th>掘り下げ検査<br>聴く過程<td>'.print_checkb("聴く過程",$ord).
      '<tr><th>話す過程<td>'.print_checkb("話す過程",$ord).
	  '<th>読み書き過程<td>'.print_checkb("読み書き過程",$ord).
      '<tr><th>構文能力<td>'.print_checkb("構文能力",$ord).
	  '<th>実用的なｺﾐｭﾆｹｰｼｮﾝに関する検査<td>'.print_checkb("CADL",$ord).
      '<tr><th>全失語〜重度失語が疑われる場合<td>'.print_checkb("重度失語症検査",$ord).
	  '<th>コメント
           <td><input type="text" name="i失語症検査コメント" value="'.$ord['失語症検査コメント'].'">
               <input type="hidden" name="i失語症訓練評価" value="on">';

    print '<tr><th><a name="sound">構音訓練</a>
           <td><button type=submit name="i構音訓練評価" value="1">評価有り</button>
               <button type=submit name="構音訓練評価DEL" value="1">評価無し</button>

       <tr><td><td>'.print_checkb("構音訓練",$ord).
          '<th>コメント
           <td><input type="text" name="i構音訓練コメント" value="'.$ord['構音訓練コメント'].'">';

    if ($ord['構音訓練評価'] && !$_POST['構音訓練評価DEL'])
      print '
       <tr><th colspan="4">構音検査
       <tr><th>検査内容<td>'.print_checkb("構音検査",$ord).
          '<th>コメント
           <td><input type="text" name="i構音検査コメント" value="'.$ord['構音検査コメント'].'">
               <input type="hidden" name="i構音訓練評価" value="on">';

    print '<tr><th><a name="brain">高次脳機能訓練</a>
           <td><button type=submit name="i高次脳機能訓練評価" value="1">評価有り</button>
               <button type=submit name="高次脳機能訓練評価DEL" value="1">評価無し</button>

       <tr><td><td>'.print_checkb("高次脳機能訓練",$ord).
          '<th>コメント
           <td><input type="text" name="i高次脳機能訓練コメント" value="'.$ord['高次脳機能訓練コメント'].'">';
    if ($ord['高次脳機能訓練評価'] && !$_POST['高次脳機能訓練評価DEL'])
      print '
       <tr><th colspan="4">高次脳機能評価
       <tr><th>知能検査<td>'.print_checkb("知能検査",$ord).
          '<th>半側空間無視・半盲検査<td>'.print_checkb("半盲検査",$ord).
      '<tr><th>注意検査<td>'.print_checkb("注意検査",$ord).
          '<th>記憶検査<td>'.print_checkb("記憶検査",$ord).
      '<tr><th>失行・失認<td>'.print_checkb("失行・失認",$ord).
          '<th>前頭葉機能<td>'.print_checkb("前頭葉機能",$ord).
      '<tr><th>コメント
           <td><input type="text" name="i高次脳機能評価コメント" value="'.$ord['高次脳機能評価コメント'].'">
               <input type="hidden" name="i高次脳機能訓練評価" value="on">';

    print '<tr><th>摂食嚥下訓練<td>'.print_checkb("摂食嚥下訓練",$ord).
          '<th>コメント
           <td><input type="text" name="i摂食嚥下訓練コメント" value="'.$ord['摂食嚥下訓練コメント'].'"> 
       <tr><th>VF施行日(yyyy-mm-dd)
           <td><input type="text" name="iVF施行日" value="'.$ord['VF施行日'].'"> 
           <th>VF目的
           <td><input type="text" name="iVF目的" value="'.$ord['VF目的'].'"> 
       <tr><th><a name="hear">聴覚評価</a>
           <td><button type=submit name="i聴覚評価" value="1">評価有り</button>
               <button type=submit name="聴覚評価DEL" value="1">評価無し</button>';

    if ($ord['聴覚評価'] && !$_POST['聴覚評価DEL'])
      print '
           <th>聴覚評価コメント
           <td><input type="text" name="i聴覚評価コメント" value="'.$ord['聴覚評価コメント'].'">
       <tr><th colspan="4">聴力検査
       <tr><th>検査内容<td>'.print_checkb("聴力検査",$ord).
          '<th>コメント
           <td><input type="text" name="i聴力検査コメント" value="'.$ord['聴力検査コメント'].'">
               <input type="hidden" name="i聴覚評価" value="on">';

    print '<tr><th colspan="4">物理療法';
    foreach(array('ホットパック','マイクロウエーブ','超音波法療','低周波法療',
                  '過流浴','アイスパック','ハドマー','牽引')
	    as $item) {
      if (!($c++ % 2)) print "<tr>";
      printf('<td><input type="checkbox" name="i%s" %s> %s
              <td><input type="text" name="i%s部位" value="%s"> 部位',
	     $item,($ord[$item] == "on" ? "checked" : ""),$item,$item,$ord[$item.'部位']);
    }
    print '<tr><th colsapn="4">部位
           <tr><td>'.print_checkb("頚部",$ord).'<td>'.
      print_select_com("頚部強度",$ord,0,false,false).
              '<td>'.print_checkb("腰部",$ord).'<td>'.
      print_select_com("腰部強度",$ord,0,false,false).
   '<tr><th>その他特記事項<td colspan="3">'.
      print_input("その他特記事項",44,$ord["その他特記事項"]).
   '</table>';
    if ($action == "new" || $action == "copy") $label = "新規登録";
    else $label = "更新";
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
$stmt = ('SELECT "患者ID" FROM "患者台帳" WHERE "Superseded" IS NULL
	    AND "ObjectID" = ' . mx_db_sql_quote($pid));
$d = mx_db_fetch_single(mx_db_connect(), $stmt);
$pt_hid = $d['患者ID'];

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
