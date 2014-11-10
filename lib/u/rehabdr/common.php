<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

/* do not show these for history diff */
$no_disp = array('ObjectID','Superseded');

$rehab_pd = array(
  "リハ処方" => array('理学療法','作業療法','言語聴覚療法'),
  "処方区分" => array('新規','変更','中止','再開','終了'),
  "中止理由" => array('医学的安静必要','精神状態の急激な悪化',
		      '著しい訓練拒否','著しい疲労・疼痛の訴え','中止理由その他'),
  "意識障害" => array('なし','評価中','あり','不明','未確認'),
  "JCS" => array('-','だいたい意識清明だが、いまひとつはっきりしない','見当識障害がある',
		 '自分の名前、生年月日がいえない','普通の呼びかけで容易に開眼する',
		 '大きな声または体を揺さぶることにより開眼する',
		 '痛み刺激を加えつつ呼びかけを繰り返すことでかろうじて開眼する',
		 '痛み刺激に対し、払いのけるような動作をする',
		 '痛み刺激で少し手足を動かしたり、顔をしかめる','痛み刺激に開眼しない'),
  "痴呆" => array('なし','評価中','あり','不明','未確認'),
  "知的障害" => array('なし','評価中','あり','不明','未確認'),
  "注意障害" =>  array('なし','評価中','あり','不明','未確認'),
  "記憶障害" =>  array('なし','評価中','あり','不明','未確認'),
  "失認" =>  array('なし','評価中','あり','不明','未確認'),
  "失行" =>  array('なし','評価中','あり','不明','未確認'),
  "失語" =>  array('なし','評価中','あり','不明','未確認'),
  "半側視空間無視" =>  array('なし','評価中','あり','不明','未確認'),
  "視覚障害" =>  array('なし','評価中','あり','不明','未確認'),
  "聴覚障害" =>  array('なし','評価中','あり','不明','未確認'),
  "表在感覚障害" =>  array('なし','評価中','あり','不明','未確認'),
  "深部感覚障害" =>  array('なし','評価中','あり','不明','未確認'),
  "痛み" =>  array('なし','評価中','あり','不明','未確認'),
  "構音障害" => array('なし','評価中','あり','不明','未確認'), 
  "呼吸循環器機能障害" => array('なし','評価中','あり','不明','未確認'), 
  "摂食機能障害" => array('なし','評価中','あり','不明','未確認'), 
  "排尿機能障害" => array('なし','評価中','あり','不明','未確認'), 
  "排便機能障害" => array('なし','評価中','あり','不明','未確認'), 
  "中枢性麻痺" => array('なし','評価中','あり','不明','未確認'), 
  "拘縮" =>  array('なし','評価中','あり','不明','未確認'),
  "筋力低下" =>  array('なし','評価中','あり','不明','未確認'),
  "弛緩" =>  array('なし','評価中','あり','不明','未確認'),
  "痙性" =>  array('なし','評価中','あり','不明','未確認'),
  "固縮" =>  array('なし','評価中','あり','不明','未確認'),
  "不随意運動" =>  array('なし','評価中','あり','不明','未確認'),
  "褥創" =>  array('なし','評価中','あり','不明','未確認'),
  "訓練形態" => array('ベッドサイドのみ','訓練室','病棟等','訓練室+病棟等','屋外訓練も可能'),
  "運動時モニター必要" => array('運動時モニター必要'),
  "意識レベル低下" => array('意識レベル低下'),
  "けいれんの重責" => array('けいれんの重責'),
  "体温" => array('-','38.0','37.5','37.0'),
  "SPO2" => array('-','95','94','93','92','91','90','89','88','87','86','85'),
  "Andersonの基準" => array('84歳以下','85歳以上'),
  "基本動作能力" => array('歩行','移乗・駆動','端座位','支持座位',
			'臥床・寝返り','臥床・現状維持'),
  "ｾﾙﾌｹｱ能力" => array('入浴レベル','更衣レベル','トイレレベル','整容レベル',
		      '食事レベル','全介助レベル'),
  "認知能力" => array('自立','時に監視','常時監視','時に介助','常時介助','無反応'),
  "関節可動域訓練" => array('関節可動域訓練PT','関節可動域訓練OT'),
  "筋力増強訓練" => array('筋力増強訓練PT','筋力増強訓練OT'),
  "神経筋再教育" => array('神経筋再教育PT','神経筋再教育OT'),
  "協調性訓練" => array('協調性訓練PT','協調性訓練OT'),
  "全身調整訓練" => array('全身調整訓練PT','全身調整訓練OT'),
  "巧緻動作訓練" => array('巧緻動作訓練PT','巧緻動作訓練OT'),
  "基本動作訓練" => array('基本動作訓練PT','基本動作訓練OT'),
  "日常生活活動訓練" => array('日常生活活動訓練PT','日常生活活動訓練OT'),
  "日常関連動作訓練" => array('日常関連動作訓練PT','日常関連動作訓練OT'),
  "認知訓練" => array('認知訓練PT','認知訓練OT'),
  "病棟環境設定" => array('病棟環境設定PT','病棟環境設定OT'),
  "補装具・自助具の検討" => array('補装具・自助具の検討PT','補装具・自助具の検討OT'),
  "在宅評価・訓練" => array('在宅評価・訓練PT','在宅評価・訓練OT'),
  "持久力評価" => array('持久力評価PT','持久力評価OT'),
  "その他特定の評価依頼" => array('その他特定の評価依頼PT','その他特定の評価依頼OT'),
  "有酸素運動歩行Time" => array('-','40','20','15','10'),
  "有酸素運動車椅子駆動Time" => array('-','40','20','15','10'),
  "有酸素運動起居動作Time" => array('-','40','20','15','10'),
  "有酸素運動座位での全身運動Time" => array('-','40','20','15','10'),
  "有酸素運動状況に応じてTime" => array('-','40','20','15','10'),
  "ベッドギャッジ" => array('-','80','70','60','50','40','30','中止基準以外の範囲で'),
  "ベッドギャッジTime" => array('-','40','30','20','10'),
  "ベッドギャッジSet" => array('-','3','2','1'),
  "失語症訓練" => array('失語症訓練','発語失行訓練','代償手段の検討','環境調整'),
  "構音訓練" => array('構音訓練'),
  "高次脳機能訓練" => array('高次脳機能訓練ST','高次脳機能訓練OT','高次脳機能訓練'),
  "摂食嚥下訓練" => array('摂食嚥下訓練','摂食嚥下訓練評価','直接訓練','間接訓練'),
  "総合的検査" => array('SLTA','SLTA補助検査','SALA','老健版失語症鑑別診断検査','WAB'),
  "聴く過程" => array('トークンテスト','理解語彙検査','聴覚的把持力検査',
			 '単語のモーラ分解能力検査','単語のモーラ抽出能力検査','語音弁別検査'),
  "話す過程" => array('100単語呼称検査','復唱検査','発語失行検査'),
  "読み書き過程" => array('漢字-仮名検査','音読検査','読解力検査','100単語書称検査'),
  "構文能力" => array('失語症構文検査'),
  "CADL" => array('CADL'),
  "重度失語症検査" => array('重度失語症検査'),
  "構音検査" => array('構音器官検査','単語明瞭度検査','会話明瞭度判定'),
  "知能検査" => array('レーブン色彩','コース立方体','MMSE','HDS-R','WAIS-R'),
  "半盲検査" => array('線分2等分','BIT'),
  "注意検査" => array('TMT-A','TMT-B','かな拾い（有意味）','かな拾い（無意味）'),
  "記憶検査" => array('三宅式記銘力検査','ベントン視覚記銘検査','Rey複雑図形',
		     'リバーミード行動記憶検査','ウェクスラー記憶検査'),
  "失行・失認" => array('標準高次動作性検査','高次視知覚検査'),
  "前頭葉機能" => array('Wisconsin　Card　Sorting　Test','Word　Fluency　Test',
		       'BADS','ハノイの塔'),
  "聴力検査" => array('純音聴力検査','語音聴力検査'),
  "頚部" => array('頚部'),
  "腰部" => array('腰部'),
  "頚部強度" => array('-','4〜6Kg','6〜8Kg','8〜10Kg','10〜12Kg','12〜14Kg',
		     '15〜20Kg','20〜25Kg','25〜30Kg','30〜40Kg'),
  "腰部強度" => array('-','4〜6Kg','6〜8Kg','8〜10Kg','10〜12Kg','12〜14Kg',
		     '15〜20Kg','20〜25Kg','25〜30Kg','30〜40Kg')
  );

function print_checkb($key,$def) {
  global $rehab_pd;

  foreach ($rehab_pd[$key] as $val) {
    if (ereg("PT$",$val) || ereg("OT$",$val) || ereg("ST$",$val))
      $dispval = substr($val,-2);
    else
      $dispval =$val;
    $str = $str . sprintf('<input type="checkbox" name="i%s" %s>%s<br>',
			  $val,($def[$val] == "on" ? "checked" : ""),$dispval);
  }
  return $str;
}

function print_select_com($key,$def,$size,$com,$unit) {
  global $__mx_formi_dek;
  global $rehab_pd;

  $str = sprintf("<select %s name=\"i%s\">\n",$__mx_formi_dek,$key);
  foreach ($rehab_pd[$key] as $val) {
    $str = $str . sprintf("<option %s value=\"%s\">%s\n",
	   ($val == $def[$key] ? "selected" : ""),$val,$val);
  }
  $str = $str . "</select>\n";
  if ($unit) $str = $str . "&nbsp{$unit}&nbsp;";
  if ($com)
    $str = $str . sprintf('%s<input type="text" name="i%s%s" %s size="%d" maxlength="%d" value="%s">'
	   ,$com,$key,$com,$__mx_formi_dek,intval($size*1.6),$size,$def[$key.$com]);
  return $str;
}

function get_rehab($pid,$oid) {
  $con = mx_db_connect();
  $str = 'select * from "リハ処方箋"
          where ';
  if ($oid) $str = $str . ' "ObjectID" = ' . "'$oid'";
  else $str = ($str . '"Superseded" IS NULL and "患者" = '.
	       "'$pid'". ' order by "ObjectID"');
  if ($oid)
    return pg_fetch_assoc(pg_query($con,$str));
  else
    return pg_fetch_all(pg_query($con,$str));
}

$check_keys = array('処方日','体温','体温自由記載','収縮期血圧','拡張期血圧','SPO2',
		    'SPO2自由記載','有酸素運動歩行Time','有酸素運動歩行Time自由記載',
		    '有酸素運動車椅子駆動Time','有酸素運動車椅子駆動Time自由記載',
		    '有酸素運動起居動作Time','有酸素運動起居動作Time自由記載',
		    '有酸素運動座位での全身運動Time','有酸素運動座位での全身運動Time自由記載',
		    '有酸素運動状況に応じてTime','有酸素運動状況に応じてTime自由記載',
		    '有酸素運動Time','有酸素運動心拍数','有酸素運動心拍数MAX',
		    'チルトテーブル','チルトテーブルTime','チルトテーブルSet',
		    'ベッドギャッジ','ベッドギャッジ自由記載',
		    'ベッドギャッジTime','ベッドギャッジTime自由記載',
		    'ベッドギャッジSet','ベッドギャッジSet自由記載',
		    'ベッドギャッジ心拍数','ベッドギャッジ心拍数MAX','SPO2MAX','VF施行日');

function insert_rehab_order ($var) {
  global $check_keys;

  foreach ($var as $key => $val)
    if (ereg("^i.*",$key)) { 
     $key = substr($key,1);
      if (check_key($key,$check_keys)) {
        if ($key == "処方日" || $key == "VF施行日") {
	  $val = mb_convert_kana($val,'a','EUC-JP');
	  $val = mx_ui_japanese_date($val);
	  if (check_date($key,$val)) return;
	}
	$ins[$key] = mb_convert_kana($val,'a','EUC-JP');
      }
      else $ins[$key]=$val;
    }
  $ins['CreatedBy'] = $var['u'];
  $str = make_insert_str("リハ処方箋",$ins,$oid);

  $ret = true;
  $con = mx_db_connect();
  pg_query($con,"begin");
  pg_query($con,$str) or $ret = false;
  pg_query($con, "commit;");

  return $ret;
}

function update_rehab_order ($var) {
  global $check_keys;

  foreach($var as $key => $val)
    if (ereg("^i.*",$key)) {
      $key = substr($key,1);
      if (check_key($key,$check_keys)) {
        if ($key == "処方日" || $key == "VF施行日") {
	  $val = mb_convert_kana($val,'a','EUC-JP');
	  $val = mx_ui_japanese_date($val);
	  if (check_date($key,$val)) return;
	}
	$array[$key] = mb_convert_kana($val,'a','EUC-JP');
      }
      else $array[$key]=$val;
    }
  $array['CreatedBy'] = $var['u'];
  $array['act'] = $var['oid'];

  $ret = true;
  if (diff_contents("リハ処方箋",$array)) {
    make_update_str("リハ処方箋",$array,$upstr,$insstr);
    $con = mx_db_connect();
    pg_query($con,"begin");
    pg_query($con,$insstr) or $ret = false;
    pg_query($con,$upstr) or $ret = false;
    pg_query($con, "commit;");
  }
  return $ret;
}

function print_detail($ord) {

  function _p($o,$k,$ext) {
    if ($o[$k] == '-') $o[$k] = "";
    if ($ext)
      return $o[$k]."&nbsp;".$o[$k.$ext];
    else
      return $o[$k];
  }

  function _pc($k,$o) {
    global $rehab_pd;
    foreach ($rehab_pd[$k] as $v) {
      if ($o[$v] == "on") 
	if (ereg("PT$",$v) || ereg("OT$",$v) || ereg("ST$",$v))
	  $str = $str.substr($v,-2)."&nbsp;指定<br>";
	else
	  $str = $str.$v."&nbsp;指定<br>";
    }
    return $str;
  }

  print "<th>処方日<td>{$ord['処方日']}
      <tr><th>リハ処方<td>"._pc('リハ処方',$ord)."
          <th>処方区分<td colspan=3>{$ord['処方区分']}
      <tr><th>中止理由<td>"._pc('中止理由',$ord)."
          <th>コメント<td>{$ord['中止理由コメント']}
      <tr><th colspan=4>機能障害
      <tr><th>意識障害<td colspan=3>"._p($ord,'意識障害','コメント')."
      <tr><th>JCS<td colspan=3>{$ord['JCS']}<td><td>
      <tr><th>見当識障害<td>"._p($ord,'痴呆','コメント')."
         <th>知的障害<td>"._p($ord,'知的障害','コメント')."
      <tr><th colspan=4>高次機能障害
      <tr><th>注意力障害<td>"._P($ord,'注意障害','コメント')."
          <th>記銘力障害<td>"._p($ord,'記憶障害','コメント')."
      <tr><th>失認<td>"._p($ord,'失認','コメント')."
          <th>失行<td>"._p($ord,'失行','コメント')."
      <tr><th>失語<td>"._p($ord,'失語','コメント')."
          <th nowrap>半側視空間無視<td nowrap>".
    _p($ord,'半側視空間無視','コメント')."
      <tr><th colspan=4>知覚障害
      <tr><th nowrap>視覚障害<td nowrap>"._p($ord,'視覚障害','コメント')."
          <th>聴覚障害<td>"._p($ord,'聴覚障害','コメント')."
      <tr><th>表在感覚障害<td>"._p($ord,'表在感覚障害','コメント')."
          <th>深部感覚障害<td>"._p($ord,'深部感覚障害','コメント')."
      <tr><th>痛み<td>"._p($ord,'痛み','コメント')."
          <th>構音障害<td>"._p($ord,"構音障害",'コメント').
     '<tr><th>呼吸・循環器障害<br>（起立性低血圧・末梢循環障害）<td>'.
               _p($ord,"呼吸循環器機能障害",'コメント').
          '<th>摂食機能障害<td>'._p($ord,"摂食機能障害",'コメント').
      '<td><td><tr><th colspan="4">排泄機能障害
       <tr><th>排尿機能障害<td>'._p($ord,"排尿機能障害",'コメント').
           '<th>排便機能障害<td>'._p($ord,"排便機能障害",'コメント').
      '<tr><th>中枢性麻痺<td>'._p($ord,"中枢性麻痺",'コメント').
          '<th>拘縮<td>'._p($ord,"拘縮",'コメント').
      '<tr><th>筋力低下<td>'._p($ord,"筋力低下",'コメント').
      '<tr><th colspan="4">筋緊張の障害
       <tr><th>弛緩<td>'._p($ord,"弛緩",'コメント').
          '<th>痙性<td>'._p($ord,"痙性",'コメント').
      '<tr><th>固縮<td>'._p($ord,"固縮",'コメント').
          '<th>不随意運動<br>（失調・振戦）<td>'._p($ord,"不随意運動",'コメント').
      '<tr><th>褥創<td>'._p($ord,"褥創",'コメント').
          "<th>機能障害コメント<td>{$ord['機能障害コメント']}
       <tr><th>訓練形態<td>{$ord['訓練形態']}
       <tr><th>運動時モニター<td>"._pc("運動時モニター必要",$ord).
          "<th>モニタニングの内容
           <td>{$ord['モニタニングの内容']}
       <tr><th nowrap colspan=4>訓練中止基準&nbsp;
           85歳以上の場合は、心拍数の上限をつける（0.9(220　-　年齢)）
       <tr><th>意識レベル低下<td>"._pc("意識レベル低下",$ord).
          '<th>けいれんの重責<td>'._pc("けいれんの重責",$ord).
      "<tr><th>体温<td>{$ord['体温']}&nbsp;{$ord['体温自由記載']}℃以上
       <tr><th>収縮期血圧
           <td>{$ord['収縮期血圧']}ｍｍＨｇ以上
           <th>拡張期血圧
           <td>{$ord['拡張期血圧']}ｍｍＨｇ以上
       <tr><th>SPO2%<td>{$ord['SPO2']}&nbsp;{$ord['SPO2自由記載']}％以下
           <th>Andersonの基準<td>"._p($ord,"Andersonの基準",'コメント').
      '<tr><th colspan="4">患者ニーズ（リハ的）<br>目標（訓練目標）
       <tr><th>基本動作能力<th>'._p($ord,"基本動作能力",false).
          '<th>ｾﾙﾌｹｱ能力<th>'._p($ord,"ｾﾙﾌｹｱ能力",false).
      '<tr><th>認知能力<th>'._p($ord,"認知能力",false).
          "<th>コメント<td>{$ord['目標コメント']}
       <tr><th colspan=4>内容
       <tr><th>関節可動域訓練<td>"._pc("関節可動域訓練",$ord).
          '<th>筋力増強訓練<td>'._pc("筋力増強訓練",$ord).
      '<tr><th>神経筋再教育<td>'._pc("神経筋再教育",$ord).
          '<th>協調性訓練<td>'._pc("協調性訓練",$ord).
      '<tr><th>全身調整訓練<td colspan="3">'._pc("全身調整訓練",$ord).'<p>有酸素運動<br>';
    foreach (array('歩行','車椅子駆動','起居動作','座位での全身運動','状況に応じて') as $item) {
      $time = '有酸素運動'.$item.'Time';
      $com = $time.'自由記載';
      print "{$item}を{$ord[$time]}&nbsp{$ord[$com]}分間<br>\n";
    }
    print "または、{$ord['有酸素運動']}を{$ord['有酸素運動Time']}分間<p>
           目標心拍数{$ord['有酸素運動心拍数']}b／分 Max HR
           {$ord['有酸素運動心拍数MAX']}%付近<p>
           チルトテーブル<br>
           {$ord['チルトテーブル']}&deg;
           {$ord['チルトテーブルTime']}分間 X
           {$ord['チルトテーブルSet']}セット<p>
           ベッドギャッジ<br>".
           _p($ord,"ベッドギャッジ",false)."&deg;".
           _p($ord,"ベッドギャッジTime",false).'分間 X'.
           _p($ord,"ベッドギャッジSet",false)."セット<br>または、
           {$ord['ベッドギャッジ自由記載']}&deg;
           {$ord['ベッドギャッジTime自由記載']}分間 X
           {$ord['ベッドギャッジSet自由記載']}セット<p>
           <p>目標心拍数{$ord['ベッドギャッジ心拍数']}b／分 Max HR
           {$ord['ベッドギャッジ心拍数MAX']}%付近<p>
           SPO2 {$ord['SPO2MAX']}％以下の下降で休憩をとる。
       <tr><th>巧緻動作訓練<td>"._pc("巧緻動作訓練",$ord).
          '<th>基本動作訓練<td>'._pc("基本動作訓練",$ord).
      '<tr><th>日常生活活動訓練<td>'._pc("日常生活活動訓練",$ord).
          '<th>日常関連動作訓練<td>'._pc("日常関連動作訓練",$ord).
      '<tr><th>認知訓練<td>'._pc("認知訓練",$ord).
          '<th>病棟環境設定<td>'._pc("病棟環境設定",$ord).
      '<tr><th>補装具・自助具の検討<td>'._pc("補装具・自助具の検討",$ord).
          "<th>補装具種類
           <td>{$ord['補装具種類']}
       <tr><th>在宅評価・訓練<td>"._pc("在宅評価・訓練",$ord).
          '<th>持久力評価<td>'._pc("持久力評価",$ord).
      '<tr><th>その他特定の評価依頼<td>'._pc("その他特定の評価依頼",$ord).
          "<th>評価内容
           <td>{$ord['評価内容']}
       <tr><th>失語症訓練<td>"._pc("失語症訓練",$ord).
          "<th>コメント<td>{$ord['失語症訓練コメント']}";
    if ($ord['失語症訓練評価'])
      print '
       <tr><th colspan="4">失語症検査
       <tr><th>総合的検査<td>'._pc("総合的検査",$ord).
          '<th>掘り下げ検査<br>聴く過程<td>'._pc("聴く過程",$ord).
      '<tr><th>話す過程<td>'._pc("話す過程",$ord).
	  '<th>読み書き過程<td>'._pc("読み書き過程",$ord).
      '<tr><th>構文能力<td>'._pc("構文能力",$ord).
	  '<th>実用的なｺﾐｭﾆｹｰｼｮﾝに関する検査<td>'._pc("CADL",$ord).
      '<tr><th>全失語〜重度失語が疑われる場合<td>'._pc("重度失語症検査",$ord).
	  "<th>コメント<td>{$ord['失語症検査コメント']}";

    print '<tr><th>構音訓練
           <td>'._pc("構音訓練",$ord).
          "<th>コメント<td>{$ord['構音訓練コメント']}";

    if ($ord['構音訓練評価'])
      print '
       <tr><th colspan="4">構音検査
       <tr><th>検査内容<td>'._pc("構音検査",$ord).
          "<th>コメント<td>{$ord['構音検査コメント']}";

    print '<tr><th>高次脳機能訓練<td>'._pc("高次脳機能訓練",$ord).
          "<th>コメント<td>{$ord['高次脳機能訓練コメント']}";
    if ($ord['高次脳機能訓練評価'])
      print '
       <tr><th colspan="4">高次脳機能評価
       <tr><th>知能検査<td>'._pc("知能検査",$ord).
          '<th>半側空間無視・半盲検査<td>'._pc("半盲検査",$ord).
      '<tr><th>注意検査<td>'._pc("注意検査",$ord).
          '<th>記憶検査<td>'._pc("記憶検査",$ord).
      '<tr><th>失行・失認<td>'._pc("失行・失認",$ord).
          '<th>前頭葉機能<td>'._pc("前頭葉機能",$ord).
      "<tr><th>コメント
           <td>{$ord['高次脳機能評価コメント']}";

    print '<tr><th>摂食嚥下訓練<td>'._pc("摂食嚥下訓練",$ord).
          "<th>コメント
           <td>{$ord['摂食嚥下訓練コメント']}
       <tr><th>VF施行日
           <td>{$ord['VF施行日']}
           <th>VF目的
           <td>{$ord['VF目的']}
       <tr><th>聴覚評価
           <td>{$ord['聴覚評価']}";


    if ($ord['聴覚評価'])
      print "
           <th>聴覚評価コメント
           <td>{$ord['聴覚評価コメント']}
       <tr><th colspan=4>聴力検査
       <tr><th>検査内容<td>"._pc("聴力検査",$ord).
	  "<th>コメント
           <td>{$ord['聴力検査コメント']}";

    print '<tr><th colspan="4">物理療法';
    foreach(array('ホットパック','マイクロウエーブ','超音波法療','低周波法療',
                  '過流浴','アイスパック','ハドマー','牽引')
	    as $item) {
      if (!($c++ % 2)) print "<tr>";
      $place = $item."部位";
      print "<td>{$item} 
             <td>部位&nbsp;{$ord[$place]}";
    }
    print '<tr><th colsapn="4">部位
           <tr><td>'._pc("頚部",$ord).'<td>'.
      _p($ord,"頚部強度",false).
              '<td>'._pc("腰部",$ord).'<td>'.
      _p($ord,"腰部強度",false).
   "<tr><th>その他特記事項<td colspan=3>{$ord['その他特記事項']}";
}
?>