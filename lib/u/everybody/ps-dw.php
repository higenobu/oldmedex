<?php
function o($name) {
	global $__this_output_data;
	$d = $__this_output_data[$name];
	if ($d == '') {
		print '&nbsp;';
	}
	else {
		print htmlspecialchars($d);
	}
}
function draw_plansheet($data) {
	global $_mx_resource_dir;
	global $__this_output_data;
	$__this_output_data = $data;
?>
<html xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=euc-jp">
<meta name=ProgId content=Excel.Sheet>
<script language="JavaScript"
 src="/<? print $_mx_resource_dir ?>/mx.js"></script>
<title>リハビリテーション総合実施計画書</title>
<style type="text/css">
<!--
body,td,th {
	font-family: ＭＳ Ｐゴシック, Osaka, ヒラギノ角ゴ Pro W3;
	font-size: 8pt;
}
.style1 {font-size: 14pt}
.news {border: 0.5px solid black}
.north {border-top: 0.5px solid black}
.south {border-bottom: 0.5px solid black}
.east {border-right: 0.5px solid black}
.west {border-left: 0.5px solid black}
-->
</style></head>
<body bgcolor="#FFFFFF">

  <table border=0 cellpadding=0 cellspacing=0 width=720pt>
    <tbody>
    <tr>
      <td height="37" class=news colspan=20><p class="style1"
>リハビリテーション総合実施計画書</p></td>
      <td colspan=3 class=news>評価実施日</td>
      <td colspan=5 class=news><?php o("日付") ?></td>
    </tr>
    <tr>
      <td colspan=2 class=news>氏名</td>
      <td colspan=5 class=news><?php o('氏名') ?></td>
      <td class=news>(<?php o('性別') ?>)</td>
      <td colspan=2 class=news>生年月日</td>
      <td colspan=5 class=news><?php o('生年月日') ?></td>
      <td colspan=2 class=news><?php o('年齢') ?></td>
      <td colspan=2 class=news>歳</td>
      <td colspan=4 class=news>利き手</td>
      <td colspan=5 class=news><?php o('利き手') ?></td>
    </tr>
    <tr>
      <td colspan=2 class=news>主治医</td>
      <td colspan=2 class=news><?php o('主治医名') ?></td>
      <td class=news>リハ医</td>
      <td colspan=3 class=news><?php o('リハ担当医名') ?></td>
      <td class=news>PT</td>
      <td colspan=3 class=news><?php o('PT名') ?></td>
      <td class=news>OT</td>
      <td colspan=3 class=news><?php o('OT名') ?></td>
      <td class=news>ST</td>
      <td colspan=3 class=news><?php o('ST名') ?></td>
      <td colspan="2" class=news>NS</td>
      <td colspan=4 class=news><?php o('看護師名') ?></td>
      <td class=news>SW</td>
      <td class=news><?php o('SW名') ?></td>
    </tr>
    <tr>
      <td colspan=9 class=news>診断名</td>
      <td colspan=11 class=news>併存疾患・合併症</td>
      <td colspan=8 class=news>リハビリテーション歴</td>
    </tr>
    <tr>
      <td colspan=9 rowspan=3 class=news><?php o('診断名') ?></td>
      <td colspan=11 class=news><?php o('並存疾患・合併症') ?></td>
      <td colspan=8 rowspan=4 class=news
	><?php o('リハビリテーション歴') ?></td>
    </tr>
    <tr>
      <td colspan=11 class=news>コントロール状態</td>
    </tr>
    <tr>
      <td colspan=11 rowspan=2 class=news><?php o('コントロール状態') ?></td>
    </tr>
    <tr>
      <td colspan=2 class=news>発症日</td>
      <td colspan=7 class=news><?php o('発症日') ?></td>
    </tr>
    <tr>
      <td colspan=4 class=news>日常生活自立度</td>
      <td colspan=10 class=news><?php o('日常生活自立度') ?></td>
      <td colspan=10 class=news>痴呆性老人の日常生活自立度判定基準</td>
      <td colspan=4 class=news
	><?php o('痴呆性老人の日常生活自立度判定基準') ?></td>
    </tr>
    </tbody>
  </table>

  <br />

  <table border=0 cellpadding=0 cellspacing=0 width=720pt>
    <tr>
      <td colspan=28 class=news
	>評価項目・内容(コロン（：）の後ろに具体的内容を記入</td>
    </tr>
    <tr>
      <td class=news rowspan=15>心<br>身<br>機<br>能<br><br>構<br>造</td>
      <td class=news colspan=4>意識障害：　</td>
      <td class=news><?php o('意識障害') ?></td>
      <td class=news colspan=7><?php o('意識障害コメント') ?></td>
      <td class=news colspan=4>構音障害：　</td>
      <td class=news colspan=2><?php o('構音障害') ?></td>
      <td class=news colspan=9><?php o('構音障害コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>見当識障害：</td>
      <td class=news><?php o('痴呆') ?></td>
      <td class=news colspan=7><?php o('痴呆コメント') ?></td>
      <td class=news colspan=4>関節可動域制限：</td>
      <td class=news colspan=2><?php o('関節可動域制限') ?></td>
      <td class=news colspan=9><?php o('関節可動域制限コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>記銘力障害：</td>
      <td class=news><?php o('記憶障害') ?></td>
      <td class=news colspan=7><?php o('記憶障害コメント') ?></td>
      <td class=news colspan=4>筋力低下：</td>
      <td class=news colspan=2><?php o('筋力低下') ?></td>
      <td class=news colspan=9><?php o('筋力低下コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>運動障害：</td>
      <td class=news><?php o('運動障害') ?></td>
      <td class=news colspan=7><?php o('運動障害コメント') ?></td>
      <td class=news colspan=4>褥創：</td>
      <td class=news colspan=2><?php o('褥創') ?></td>
      <td class=news colspan=9><?php o('褥創コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>表在感覚障害：</td>
      <td class=news><?php o('表在感覚障害') ?></td>
      <td class=news colspan=7><?php o('表在感覚障害コメント') ?></td>
      <td class=news colspan=4>痛み：</td>
      <td class=news colspan=2><?php o('痛み') ?></td>
      <td class=news colspan=9><?php o('痛みコメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>深部感覚障害：</td>
      <td class=news><?php o('深部感覚障害') ?></td>
      <td class=news colspan=7><?php o('深部感覚障害コメント') ?></td>
      <td class=news colspan=4>半側視空間無視：</td>
      <td class=news colspan=2><?php o('半側視空間無視') ?></td>
      <td class=news colspan=9><?php o('半側視空間無視コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>摂食嚥下障害：</td>
      <td class=news><?php o('摂食機能障害') ?></td>
      <td class=news colspan=7><?php o('摂食機能障害コメント') ?></td>
      <td class=news colspan=4>注意力障害：</td>
      <td class=news colspan=2><?php o('注意障害') ?></td>
      <td class=news colspan=9><?php o('注意障害コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>排尿機能障害：</td>
      <td class=news><?php o('排尿機能障害') ?></td>
      <td class=news colspan=7><?php o('排尿機能障害コメント') ?></td>
      <td class=news colspan=4>構成障害：</td>
      <td class=news colspan=2><?php o('構成障害') ?></td>
      <td class=news colspan=9><?php o('構成障害コメント') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>排便機能障害：</td>
      <td class=news><?php o('排便機能障害') ?></td>
      <td class=news colspan=7><?php o('排便機能障害コメント') ?></td>
      <td class=news colspan=3 rowspan=2>その他：</td>
      <td class=news colspan=12 rowspan=2><?php o('心身機能・その他') ?></td>
    </tr>
    <tr>
      <td class=news colspan=4>呼吸・循環器障害：</td>
      <td class=news><?php o('呼吸循環器機能障害') ?></td>
      <td class=news colspan=7><?php o('呼吸循環器機能障害コメント') ?></td>
    </tr>
    <tr>
      <td rowspan=5 class=news>基<br>本<br>動<br>作</td>
      <td class=news colspan=3>寝返り：　</td>
      <td class=news colspan=8><?php o('寝返り') ?></td>
      <td class=news colspan=6>短期目標</td>
      <td class=news colspan=9>具体的アプローチ</td>
    </tr>
    <tr>
      <td class=news colspan=3>起き上がり：</td>
      <td class=news colspan=8><?php o('起き上がり') ?></td>
      <td class=news colspan=6 rowspan=4><?php o('基本動作・短期目標') ?></td>
      <td class=news colspan=9 rowspan=4><?php o('基本動作・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>座位：</td>
      <td class=news colspan=8><?php o('座位') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>立ち上がり：</td>
      <td class=news colspan=8><?php o('立ち上がり') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>立位：　</td>
      <td class=news colspan=8><?php o('立位') ?></td>
    </tr>
    <tr>
      <td class=news rowspan=24>活<br>動</td>
      <td class=news colspan=12>活動度　(安静度の制限とその理由・リスクについて）</td>
      <td class=news colspan=6 rowspan=2><?php o('活動度・短期目標') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('活動度・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=12><?php o('活動度') ?></td>
    </tr>
    <tr>
      <td class=news colspan=5>日常生活動作実行状況</td>
      <td class=news colspan=2>ＦＩＭ</td>
      <td class=news colspan=5>使用用具(杖・装具）介助</td>
      <td class=news colspan=6>短期目標</td>
      <td class=news colspan=9>具体的アプローチ</td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=6>セルフケア</td>
      <td class=news colspan=3>食事</td>
      <td class=news><?php o('食事_P') ?></td>
      <td class=news><?php o('食事_TP') ?></td>
      <td class=news colspan=5><?php o('食事_C') ?></td>
      <td class=news colspan=6 rowspan=6><?php o('セルフケア・短期目標') ?></td>
      <td class=news colspan=9 rowspan=6><?php o('セルフケア・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>整容</td>
      <td class=news><?php o('整容_P') ?></td>
      <td class=news><?php o('整容_TP') ?></td>
      <td class=news colspan=5><?php o('整容_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>清拭</td>
      <td class=news><?php o('清拭_P') ?></td>
      <td class=news><?php o('清拭_TP') ?></td>
      <td class=news colspan=5><?php o('清拭_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>更衣上</td>
      <td class=news><?php o('更衣・上半身_P') ?></td>
      <td class=news><?php o('更衣・上半身_TP') ?></td>
      <td class=news colspan=5><?php o('更衣・上半身_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>更衣下</td>
      <td class=news><?php o('更衣・下半身_P') ?></td>
      <td class=news><?php o('更衣・下半身_TP') ?></td>
      <td class=news colspan=5><?php o('更衣・下半身_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>トイレ動作</td>
      <td class=news><?php o('トイレ動作_P') ?></td>
      <td class=news><?php o('トイレ動作_TP') ?></td>
      <td class=news colspan=5><?php o('トイレ動作_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=2>排泄</td>
      <td class=news colspan=3>排尿管理</td>
      <td class=news><?php o('排尿管理_P') ?></td>
      <td class=news><?php o('排尿管理_TP') ?></td>
      <td class=news colspan=5><?php o('排尿管理_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('排泄・短期目標') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('排泄・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>排便管理</td>
      <td class=news><?php o('排泄管理_P') ?></td>
      <td class=news><?php o('排泄管理_TP') ?></td>
      <td class=news colspan=5><?php o('排泄管理_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=3>移乗</td>
      <td class=news colspan=3>車椅子移乗</td>
      <td class=news><?php o('ベッド・椅子・車椅子_P') ?></td>
      <td class=news><?php o('ベッド・椅子・車椅子_TP') ?></td>
      <td class=news colspan=5><?php o('ベッド・椅子・車椅子_C') ?></td>
      <td class=news colspan=6><?php o('車椅子移乗・短期目標') ?></td>
      <td class=news colspan=9><?php o('車椅子移乗・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>トイレ移乗</td>
      <td class=news><?php o('トイレ_P') ?></td>
      <td class=news><?php o('トイレ_TP') ?></td>
      <td class=news colspan=5><?php o('トイレ_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('移乗・短期目標') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('移乗・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>浴槽移乗</td>
      <td class=news><?php o('浴槽シャワー_P') ?></td>
      <td class=news><?php o('浴槽シャワー_TP') ?></td>
      <td class=news colspan=5><?php o('浴槽シャワー_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=3>移動</td>
      <td class=news colspan=3>車椅子</td>
      <td class=news><?php o('車椅子_P') ?></td>
      <td class=news><?php o('車椅子_TP') ?></td>
      <td class=news colspan=5><?php o('車椅子_C') ?></td>
      <td class=news colspan=6><?php o('車椅子・短期目標') ?></td>
      <td class=news colspan=9><?php o('車椅子・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>歩行</td>
      <td class=news><?php o('歩行_P') ?></td>
      <td class=news><?php o('歩行_TP') ?></td>
      <td class=news colspan=5><?php o('歩行_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('移動・短期目標') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('移動・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>階段</td>
      <td class=news><?php o('階段_P') ?></td>
      <td class=news><?php o('階段_TP') ?></td>
      <td class=news colspan=5><?php o('階段_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=2>コミュニケーション</td>
      <td class=news colspan=3>理解</td>
      <td class=news><?php o('理解_P') ?></td>
      <td class=news><?php o('理解_TP') ?></td>
      <td class=news colspan=5><?php o('理解_C') ?></td>
      <td class=news colspan=6 rowspan=2><?php o('コミュニケーション・短期目標') ?></td>
      <td class=news colspan=9 rowspan=2><?php o('コミュニケーション・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>表出</td>
      <td class=news><?php o('表出_P') ?></td>
      <td class=news><?php o('表出_TP') ?></td>
      <td class=news colspan=5><?php o('表出_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2 rowspan=3>社会的認知</td>
      <td class=news colspan=3>社会的交流</td>
      <td class=news><?php o('社会的交流_P') ?></td>
      <td class=news><?php o('社会的交流_TP') ?></td>
      <td class=news colspan=5><?php o('社会的交流_C') ?></td>
      <td class=news colspan=6 rowspan=3><?php o('社会的認知・短期目標') ?></td>
      <td class=news colspan=9 rowspan=3><?php o('社会的認知・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>問題解決</td>
      <td class=news><?php o('問題解決_P') ?></td>
      <td class=news><?php o('問題解決_TP') ?></td>
      <td class=news colspan=5><?php o('問題解決_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=3>記憶</td>
      <td class=news><?php o('記憶_P') ?></td>
      <td class=news><?php o('記憶_TP') ?></td>
      <td class=news colspan=5><?php o('記憶_C') ?></td>
    </tr>
    <tr>
      <td class=news colspan=5>合計</td>
      <td class=news><?php o('FIM_NURSE_SUM') ?></td>
      <td class=news><?php o('FIM_THERAPIST_SUM') ?></td>
      <td>（運動</td>
      <td><?php o('FIM_NURSE_SUM_PHY') ?></td>
      <td>認知</td>
      <td><?php o('FIM_NURSE_SUM_COG') ?></td>
      <td>）</td>
      <td class=news colspan=3>移動手段</td>
      <td class=news colspan=12><?php o('移動手段') ?></td>
    </tr>
    <tr>
      <td class=news colspan=27>7.自立　6.修正自立　5.監視・準備　4.最小介助　3.中等度介助　2．最大介助　1.全介助</td>
    </tr>

  </table>

  <br /><hr /><br />

  <table border=0 cellpadding=0 cellspacing=0 width=720pt>

    <tr>
      <td class=news>&nbsp;</td>
      <td class=news colspan=8>評価</td>
      <td class=news colspan=11>短期目標</td>
      <td class=news colspan=8>具体的アプローチ</td>
    </tr>
    <tr>
      <td class=news rowspan=10>参<br>加</td>
      <td class=east colspan=8>職業</td>
      <td class=news colspan=3>退院先</td>
      <td class=news colspan=8><?php o('退院先') ?></td>
      <td class=news colspan=8 rowspan=10><?php o('参加・アプローチ') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('職業') ?></td>
      <td class=news colspan=3>復職</td>
      <td class=news colspan=8><?php o('復職') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>職種・業種・仕事内容</td>
      <td class=news colspan=3>復職時期</td>
      <td class=news colspan=8><?php o('復職時期') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('職種・業種・仕事内容') ?></td>
      <td class=news colspan=3>仕事内容</td>
      <td class=news colspan=8><?php o('仕事内容') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>経済状況</td>
      <td class=news colspan=3>通勤方法</td>
      <td class=news colspan=8><?php o('通勤方法') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('経済状況') ?></td>
      <td colspan=3>家庭内役割</td>
      <td colspan=8><?php o('家庭内役割') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>社会参加(内容・頻度等）</td>
      <td class=news colspan=3>社会活動</td>
      <td class=news colspan=8><?php o('社会活動') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('社会参加（内容、頻度等）') ?></td>
      <td class=news colspan=3>趣味</td>
      <td class=news colspan=8><?php o('趣味') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>余暇活動(内容・頻度等）</td>
      <td class=news colspan=11 rowspan=2><?php o('参加・短期目標') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('余暇活動（内容、頻度等）') ?></td>
    </tr>
    
    <tr>
      <td class=news rowspan=4>心<br>理</td>
      <td class=news colspan=2>抑鬱</td>
      <td colspan=6 class=news><?php o('抑鬱') ?></td>
      <td colspan=11 rowspan=4 class=news><?php o('心理・短期目標') ?></td>
      <td class=news colspan=8 rowspan=4><?php o('心理・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>障害の否認</td>
      <td class=news colspan=6><?php o('障害の否認') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>その他</td>
      <td class=east colspan=6><?php o('その他心理') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8>　</td>
    </tr>
    <tr>
      <td class=news rowspan=14>環<br>境</td>
      <td class=news colspan=2>同居家族</td>
      <td class=east colspan=6></td>
      <td class=news colspan=3>自宅改造</td>
      <td class=east colspan=8
	><?php o('自宅改造') ?><?php o('自宅改造内容') ?></td>
      <td class=news colspan=8 rowspan=14><?php o('環境・アプローチ') ?></td>
    </tr>
    <tr>
      <td class="south east" colspan=8><?php o('同居家族') ?></td>
      <td class=news colspan=3>福祉機器</td>
      <td class=news colspan=8
	><?php o('福祉機器') ?><?php o('福祉機器内容') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>親族関係</td>
      <td class=news colspan=17><?php o('親族関係') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>住居形態</td>
      <td class=east colspan=6>　</td>
      <td class=news colspan=3>社会保障サービス</td>
      <td class=east colspan=8>　</td>
    </tr>
    <tr>
      <td class="south east" colspan=8 rowspan=3><?php o('住居形態') ?></td>
      <td class="south east" colspan=11
	><?php o('社会保障サービス') ?><?php o('社会保障サービス内容') ?></td>
    </tr>
    
    <tr>
      <td class=news colspan=3>介護保険サービス</td>
      <td class=east colspan=8>　</td>
    </tr>
    <tr>
      <td class="south east" colspan=11
	><?php o('介護保険サービス') ?><?php o('介護保険サービス内容') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>階数</td>
      <td class=news colspan=6><?php o('階数') ?></td>
      <td class=news colspan=3>特記事項</td>
      <td class=east colspan=8>　</td>
    </tr>
    <tr>
      <td class=news colspan=2>居室の種類</td>
      <td class=news colspan=6><?php o('居室の種類') ?></td>
      <td class=news colspan=11 rowspan=6><?php o('環境・短期目標') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>トイレ様式</td>
      <td class=news colspan=6><?php o('トイレ様式') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>住宅改修の可否</td>
      <td class=news colspan=6><?php o('住宅改修の可否') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>家周囲</td>
      <td class=east colspan=6 rowspan=2><?php o('家周囲') ?></td>
    </tr>
    <tr>
      <td class=south colspan="2">　</td>
    </tr>
    <tr>
      <td class=news colspan=2>交通</td>
      <td class=news colspan=6><?php o('交通') ?></td>
    </tr>
    <tr>
      <td class=news rowspan=6>第<br>三<br>者<br>の<br>不<br>利</td>
      <td class=news colspan=2>発病による家族の変化</td>
      <td class=news colspan=6><?php o('発病による家族の変化') ?></td>
      <td class=news colspan=3>退院後の主介護者</td>
      <td class=news colspan=8
	><?php o('退院後の主介護者') ?><?php o('退院後の主介護者内容') ?></td>
      <td class=news colspan=8 rowspan=6
	><?php o('第三者の不利・アプローチ') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>社会生活</td>
      <td class=news colspan=6><?php o('社会生活') ?></td>
      <td class=news colspan=3>家族構成の変化</td>
      <td class=news colspan=8
	><?php o('家族構成の変化') ?><?php o('家族構成の変化内容') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>健康上の問題の発生</td>
      <td class=news colspan=6><?php o('健康上の問題の発生') ?></td>
      <td class=news colspan=3>家族内役割の変化</td>
      <td class=news colspan=8
	><?php o('家族内役割の変化') ?><?php o('家族内役割の変化内容') ?></td>
    </tr>
    <tr>
      <td class=news colspan=2>心理的問題の発生</td>
      <td class=news colspan=6><?php o('心理的問題の発生') ?></td>
      <td class=news colspan=3>家族の社会活動変化</td>
      <td class=news colspan=8
	><?php o('家族の社会活動変化') ?><?php o('家族の社会活動変化内容') ?></td>
    </tr>
    <tr>
      <td class=east colspan=8>備考</td>
      <td class=east colspan=11>備考</td>
    </tr>
    <tr>
      <td colspan=8 class="south east"
	><?php o('第三者の不利・評価・備考') ?></td>
      <td class="south east" colspan=11
><?php o('第三者の不利・短期目標') ?></td>
    </tr>

    <tr>
      <td class="east west" colspan=12>1ヵ月後の目標</td>
      <td class="east west" colspan=16>本人の希望</td>
    </tr>
    <tr>
      <td class=news colspan=12 rowspan=3><?php o('1ヶ月後の目標') ?></td>
      <td class="south east" colspan=16><?php o('本人の希望') ?></td>
    </tr>
    <tr>
      <td class=east colspan=16>家族の希望</td>
    </tr>
    <tr>
      <td class="south east" colspan=16><?php o('家族の希望') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=12>リハビリテーション治療方針</td>
      <td class="east west" colspan=16>外泊計画</td>
    </tr>
    <tr>
      <td class="west south east" colspan=12
	><?php o('リハビリテーションの治療方針') ?></td>
      <td class="west south east" colspan=16
	><?php o('外泊計画') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=28>退院時の目標と見込み時期</td>
    </tr>
    <tr>
      <td class="west south east" colspan=28
	><?php o('退院時目標・見込時期') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=28
	>将来または退院後のリハビリテーション計画など</td>
    </tr>
    <tr>
      <td class="west south east" colspan=28><?php o('将来計画') ?></td>
    </tr>
    <tr>
      <td class="east west" colspan=28>将来または退院後の社会参加の見込み</td>
    </tr>
    <tr>
      <td class="west south east" colspan=28
	><?php o('将来または退院後の社会参加の見込み') ?></td>
    </tr>
    <tr>
      <td class=west colspan=12>　</td>
      <td colspan=8>本人/家族への説明</td>
      <td class=east colspan=8>　　　　年　　月　　日</td>
    </tr>
    <tr>
      <td class="east west" colspan=28>　</td>
    </tr>
    <tr>
      <td class="south west" colspan=12
	>説明を受けた人：本人・家族　　署名：</td>
      <td class="south east" colspan=16>説明者署名</td>
    </tr>
  </table>

</body>
<script language="javascript" type="text/javascript">
<!--
printThisWindow(window);
-->
</script>
</html>
<?php
}
?>
