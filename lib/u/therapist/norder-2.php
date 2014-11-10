<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_therapist_norder_cfg(&$cfg) {
  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'リハ処方箋',
      'ALLOW_SORT' => array
      ('ObjectID' => array('リハ箋ID' => 'X."ObjectID"'),
       '日付' => array('日付' => 'X."処方日"'),
       '処方医' => array('処方医' => '(DR."姓" || DR."名")')),
      'DEFAULT_SORT' => '日付',
      'ENABLE_QBE' =>
      array(array('Column' => '日付', 'Compare' => 'X."処方日"',
		  'Draw' => 'text'),
	    array('Column' => '処方医',
		  'Compare' => '(DR."姓" || DR."名")',
		  'Draw' => 'text')),
      'LCOLS' => array(array('Column' => 'ObjectID',
			     'Label' => 'リハ箋ID'),
		       '日付',
		       '処方区分',
		       '処方医', '理', '作', '言'),

      'UNIQ_ID' => 'X."ObjectID"',
      ));
  
  $cfg['AlreadyHasSelectionFor'] = array('日付' => 1,
					 '処方医' => 1,
					 '処方区分' => 1,
					 '理学療法' => 1,
					 '作業療法' => 1,
					 '言語聴覚療法' => 1);
  $cfg['STMT_SEL'] = '
SELECT X."ObjectID",
       X."処方日" as "日付",
       X."処方区分",
       (DR."姓" || DR."名") as "処方医",
       CASE WHEN X."理学療法" = \'on\' THEN \'○\' ELSE \'×\' END as "理",
       CASE WHEN X."作業療法" = \'on\' THEN \'○\' ELSE \'×\' END as "作",
       CASE WHEN X."言語聴覚療法" = \'on\' THEN \'○\' ELSE \'×\' END as "言"
';
  $cfg['STMT_FROM'] = '
FROM "リハ処方箋" as X
LEFT JOIN "職員台帳" as DR ON DR."ObjectID" = X. "医者"
';

  $stmt_head = $cfg['STMT_SEL'] . $cfg['STMT_FROM'];
  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';
}

class list_of_rehab_norders extends list_of_ppa_objects {

  var $default_row_per_page = 4;
  var $debug = 1;

  function list_of_rehab_norders($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_therapist_norder_cfg(&$cfg);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '日付') ? 1 : 0);
    }
    return $paging_orders;
  }

}

$__lib_u_therapist_norder_yucky_cols = array
(
"関節可動域訓練PT",
"関節可動域訓練OT",
"筋力増強訓練PT",
"筋力増強訓練OT",
"神経筋再教育PT",
"神経筋再教育OT",
"協調性訓練PT",
"協調性訓練OT",
"全身調整訓練PT",
"全身調整訓練OT",
"巧緻動作訓練PT",
"巧緻動作訓練OT",
"基本動作訓練PT",
"基本動作訓練OT",
"日常生活活動訓練PT",
"日常生活活動訓練OT",
"日常関連動作訓練PT",
"日常関連動作訓練OT",
"認知訓練PT",
"認知訓練OT",
"病棟環境設定PT",
"病棟環境設定OT",
"補装具・自助具の検討PT",
"補装具・自助具の検討OT",
"在宅評価・訓練PT",
"在宅評価・訓練OT",
"持久力評価PT",
"持久力評価OT",
"その他特定の評価依頼PT",
"その他特定の評価依頼OT",
"高次脳機能訓練OT",
"高次脳機能訓練ST",
);

function __lib_u_therapist_norder_cfg_detailed_flippage(&$cfg, $name, $ary)
{
	global $__lib_u_therapist_norder_yucky_cols;
	if (!array_key_exists('DPAGES', $cfg))
		$cfg['DPAGES'] = array();
	$ix = count($cfg['DPAGES']); 
	$cfg['DPAGES'][] = $name;
	foreach ($ary as $a) {
		if (!is_array($a))
			$a = array('Column' => $a);
		$a['Page'] = $ix;
		if (!array_key_exists('Label', $a))
			$a['Label'] = $a['Column'];
		if (!array_key_exists('Draw', $a))
			$a['Draw'] = 'text';
		$cfg['DCOLS'][] = $a;
	}
}

function __lib_u_therapist_norder_cfg_detailed(&$cfg) {
	__lib_u_therapist_norder_cfg(&$cfg);

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, 'リハ箋', array
		 ('日付',
		  '記録日',
		  '処方医',
		  array('Label' => 'リハ処方',
			'Column' => array('理学療法',
					    '作業療法',
					    '言語聴覚療法'),
			'Draw' => 'check_box'),
		  array('Label' => '中止理由',
			'Column' => array('医学的安静必要',
					    '精神状態の急激な悪化',
					    '著しい訓練拒否',
					    '著しい疲労・疼痛の訴え',
					    '中止理由その他'),
			'Draw' => 'check_box'),
		  '中止理由コメント',
		  '処方区分'));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '機能障害1', array
		 ("意識障害",
		  array('OmitIfEmpty' => 1,
			'Column' => "意識障害コメント"),
		  "JCS",
		  array('Column' => "痴呆",
			'Label' => "痴呆(見当識障害)"),
		  array('OmitIfEmpty' => 1,
			'Column' => "痴呆コメント"),
		  "知的障害",
		  array('OmitIfEmpty' => 1,
			'Column' => "知的障害コメント"),
		  array('Label' => '高次機能障害'),
		  array('Column' => "注意障害",
			'Label' => "・注意障害"),
		  array('Column' => "注意障害コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・注意障害コメント"),
		  array('Column' => "記憶障害",
			'OmitIfEmpty' => 1,
			'Label' => "・記憶障害"),
		  array('Column' => "記憶障害コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・記憶障害コメント"),
		  array('Column' => "失認",
			'Label' => "・失認"),
		  array('Column' => "失認コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・失認コメント"),
		  array('Column' => "失行",
			'Label' => "・失行"),
		  array('Column' => "失行コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・失行コメント"),
		  array('Column' => "失語",
			'Label' => "・失語"),
		  array('Column' => "失語コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・失語コメント"),
		  array('Column' => "半側視空間無視",
			'Label' => "・半側視空間無視"),
		  array('Column' => "半側視空間無視コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・半側視空間無視コメント")));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '機能障害2', array
		 ("意識障害",
		  array('Label' => '知覚障害'),
		  array('Column' => "視覚障害",
			'Label' => "・視覚障害"),
		  array('Column' => "視覚障害コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・視覚障害コメント"),
		  array('Column' => "聴覚障害",
			'Label' => "・聴覚障害"),
		  array('Column' => "聴覚障害コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・聴覚障害コメント"),
		  array('Column' => "表在感覚障害",
			'Label' => "・表在感覚障害"),
		  array('Column' => "表在感覚障害コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・表在感覚障害コメント"),
		  array('Column' => "深部感覚障害",
			'Label' => "・深部感覚障害"),
		  array('Column' => "深部感覚障害コメント",
			'OmitIfEmpty' => 1,
			'Label' => "・深部感覚障害コメント"),
		  "痛み",
		  array('OmitIfEmpty' => 1,
			'Column' => "痛みコメント"),
		  "構音障害",
		  array('OmitIfEmpty' => 1,
			'Column' => "構音障害コメント"),
		  array('Column' => "呼吸循環器機能障害",
			'Label' => "呼吸循環器機能障害（起立性低血圧・末梢循環障害）"),
		  array('OmitIfEmpty' => 1,
			'Column' => "呼吸循環器機能障害コメント"),
		  "摂食機能障害",
		  array('OmitIfEmpty' => 1,
			'Column' => "摂食機能障害コメント"),
		  array('Label' => '排泄機能障害'),
		  array('Column' => "排尿機能障害",
			'Label' => "・排尿機能障害"),
		  array('OmitIfEmpty' => 1,
			'Column' => "排尿機能障害コメント",
			'Label' => "・排尿機能障害コメント"),
		  array('Column' => "排便機能障害",
			'Label' => "・排便機能障害"),
		  array('OmitIfEmpty' => 1,
			'Column' => "排便機能障害コメント",
			'Label' => "・排便機能障害コメント"),
		  "中枢性麻痺",
		  array('OmitIfEmpty' => 1,
			'Column' => "中枢性麻痺コメント"),
		  "拘縮",
		  array('OmitIfEmpty' => 1,
			'Column' => "拘縮コメント"),
		  "筋力低下",
		  array('OmitIfEmpty' => 1,
			'Column' => "筋力低下コメント"),
		  array('Label' => '筋緊張の障害'),
		  array('Column' => "弛緩",
			'Label' => '・弛緩'),
		  array('OmitIfEmpty' => 1,
			'Column' => "弛緩コメント",
			'Label' => '・弛緩コメント'),
		  array('Column' => "痙性",
			'Label' => '・痙性'),
		  array('OmitIfEmpty' => 1,
			'Column' => "痙性コメント",
			'Label' => '・痙性コメント'),
		  array('Column' => "固縮",
			'Label' => '・固縮'),
		  array('OmitIfEmpty' => 1,
			'Column' => "固縮コメント",
			'Label' => '・固縮コメント'),
		  array('Column' => "不随意運動",
			'Label' => "不随意運動；（失調・振戦）"),
		  array('OmitIfEmpty' => 1,
			'Column' => "不随意運動コメント"),
		  "褥創",
		  array('OmitIfEmpty' => 1,
			'Column' => "褥創コメント"),
		  array('OmitIfEmpty' => 1,
			'Column' => "機能障害コメント")));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '訓練中止基準', array
		 ('訓練形態',
		  array('Column' => '運動時モニター必要',
			'Label' => '運動時モニター',
			'Draw' => 'check_box',
			'Column' => array('運動時モニター必要')),
		  'モニタニングの内容',
		  array('Label' => '訓練中止基準',
			'Draw' => 'stop_basis',
			'Check' => array("意識レベル低下","けいれんの重責",
					 "体温", "体温自由記載", 
					 "収縮期血圧", "拡張期血圧",
					 "SPO2", "SPO2自由記載",
					 "Andersonの基準","Andersonの基準コメント")),
		  ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '訓練目標', array
		 ('基本動作能力',
		  'ｾﾙﾌｹｱ能力',
		  '認知能力',
		  array('Label' => 'コメント',
			'Column' => '目標コメント'),
		  ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容1', array
		 (array('Column' => "関節可動域訓練",
			'Draw' => 'pos'),
		  array('Column' => "筋力増強訓練",
			'Draw' => 'pos'),
		  array('Column' => "神経筋再教育",
			'Draw' => 'pos'),
		  array('Column' => "協調性訓練",
			'Draw' => 'pos'),
		  array('Label' => '全身調整訓練',
			'Draw' => 'coordinate',
			'Check' => 
			array("全身調整訓練PT",
			      "全身調整訓練OT",
			      "有酸素運動歩行",
			      "有酸素運動車椅子駆動",
			      "有酸素運動起居動作",
			      "有酸素運動座位での全身運動",
			      "有酸素運動状況に応じて",
			      "有酸素運動コメント",
			      "有酸素運動歩行Time",
			      "有酸素運動歩行Time自由記載",
			      "有酸素運動車椅子駆動Time",
			      "有酸素運動車椅子駆動Time自由記載",
			      "有酸素運動起居動作Time",
			      "有酸素運動起居動作Time自由記載",
			      "有酸素運動座位での全身運動Time",
			      "有酸素運動座位での全身運動Time自由記載",
			      "有酸素運動状況に応じてTime",
			      "有酸素運動状況に応じてTime自由記載",
			      "有酸素運動".
			      "有酸素運動Time",
			      "有酸素運動心拍数",
			      "有酸素運動心拍数MAX",
			      "チルトテーブル",
			      "チルトテーブルTime",
			      "チルトテーブルSet",
			      "ベッドギャッジ",
			      "ベッドギャッジ自由記載",
			      "ベッドギャッジTime",
			      "ベッドギャッジTime自由記載",
			      "ベッドギャッジSet",
			      "ベッドギャッジSet自由記載",
			      "ベッドギャッジ心拍数",
			      "ベッドギャッジ心拍数MAX",
			      "SPO2MAX")),
		  array('Column' => "巧緻動作訓練",
			'Draw' => 'pos'),
		  array('Column' => "基本動作訓練",
			'Draw' => 'pos'),
		  array('Column' => "日常生活活動訓練",
			'Draw' => 'pos'),
		  array('Column' => "日常関連動作訓練",
			'Draw' => 'pos'),
		  array('Column' => "認知訓練",
			'Draw' => 'pos'),
		  array('Column' => "病棟環境設定",
			'Draw' => 'pos'),
		  array('Column' => "補装具・自助具の検討",
			'Draw' => 'pos'),
		  "補装具種類",
		  array('Column' => "在宅評価・訓練",
			'Draw' => 'pos'),
		  array('Column' => "持久力評価",
			'Draw' => 'pos'),
		  array('Column' => "その他特定の評価依頼",
			'Draw' => 'pos'),
		  "評価内容"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容2', array
		 (array('Label' => '失語症訓練',
			'Draw' => 'check_box',
			'Column' => array("失語症訓練評価",
					    "失語症訓練",
					    "発語失行訓練",
					    "代償手段の検討",
					    "環境調整")),
		  "失語症訓練コメント",
		  array('Label' => '失語症検査'),
		  array('Label' => '総合的検査',
			'Draw' => 'check_box',
			'Column' => array("SLTA",
					    "SLTA補助検査",
					    "SALA",
					    "老健版失語症鑑別診断検査",
					    "WAB")),
		  array('Label' => '掘り下げ検査聴く過程',
			'Draw' => 'check_box',
			'Column' => array("トークンテスト",
					    "理解語彙検査",
					    "聴覚的把持力検査",
					    "単語のモーラ分解能力検査",
					    "単語のモーラ抽出能力検査",
					    "語音弁別検査")),
		  array('Label' => '話す過程',
			'Draw' => 'check_box',
			'Column' => array("100単語呼称検査",
					    "復唱検査",
					    "発語失行検査")),
		  array('Label' => '読み書き過程',
			'Draw' => 'check_box',
			'Column' => array("漢字-仮名検査",
					    "音読検査",
					    "読解力検査",
					    "100単語書称検査")),
		  array('Label' => '構文能力',
			'Draw' => 'check_box',
			'Column' => array("失語症構文検査")),
		  array('Label' => '実用的なｺﾐｭﾆｹｰｼｮﾝに関する検査',
			'Draw' => 'check_box',
			'Column' => array("CADL")),
		  array('Label' => '全失語',
			'Draw' => 'check_box',
			'Column' => array("重度失語症検査")),
		  "失語症検査コメント"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容3', array
		 (array('Label' => '構音訓練',
			'Draw' => 'check_box',
			'Column' => array("構音訓練評価",
					    "構音訓練")),
		  "構音訓練コメント",
		  array('Label' => '構音検査'),
		  array('Label' => '検査内容',
			'Draw' => 'check_box',
			'Column' => array("構音器官検査",
					    "単語明瞭度検査",
					    "会話明瞭度判定")),
		  "構音検査コメント" ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容4', array
		 (array('Label' => '高次脳機能訓練'),
		  array('Label' => '担当',
			'Column' => '高次脳機能訓練',
			'Draw' => 'pos'),
		  array('Label' => '高次脳機能訓練',
			'Draw' => 'check_box',
			'Column' => array("高次脳機能訓練",
					    "高次脳機能訓練評価")),
		  "高次脳機能訓練コメント",
		  array('Label' => '高次脳機能評価'),
		  array('Label' => '知能検査',
			'Draw' => 'check_box',
			'Column' => array("レーブン色彩",
					    "コース立方体",
					    "MMSE",
					    "HDS-R",
					    "WAIS-R")),
		  array('Label' => '半側空間無視・半盲検査',
			'Draw' => 'check_box',
			'Column' => array("線分2等分",
					    "BIT")),
		  array('Label' => '注意検査',
			'Draw' => 'check_box',
			'Column' => array("TMT-A",
					    "TMT-B",
					    "かな拾い（有意味）",
					    "かな拾い（無意味）")),
		  array('Label' => '記憶検査',
			'Draw' => 'check_box',
			'Column' => array("三宅式記銘力検査",
					    "ベントン視覚記銘検査",
					    "Rey複雑図形",
					    "リバーミード行動記憶検査",
					    "ウェクスラー記憶検査")),
		  array('Label' => '失行・失認',
			'Draw' => 'check_box',
			'Column' => array("標準高次動作性検査",
					    "高次視知覚検査")),
		  array('Label' => '前頭葉機能',
			'Draw' => 'check_box',
			'Column' => array("Wisconsin　Card　Sorting　Test",
					    "Word　Fluency　Test",
					    "BADS",
					    "ハノイの塔")),
		  "高次脳機能評価コメント" ));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容5', array
		 (array('Label' => '摂食嚥下訓練',
			'Draw' => 'check_box',
			'Column' => array("摂食嚥下訓練",
					    "摂食嚥下訓練評価",
					    "直接訓練",
					    "間接訓練")),
		  "摂食嚥下訓練コメント",
		  "VF施行日",
		  "VF目的"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容6', array
		 (array('Label' => '聴覚評価'),
		  array('Label' => '聴覚評価',
			'Draw' => 'check_box',
			'Column' => array("聴覚評価")),
		  "聴覚評価コメント",
		  array('Label' => '検査内容',
			'Draw' => 'check_box',
			'Column' => array("純音聴力検査",
					    "語音聴力検査")),
		  "聴力検査コメント"));

	__lib_u_therapist_norder_cfg_detailed_flippage
		(&$cfg, '内容7', array
		 (array('Label' => '物理療法'),
		  array('Label' => "ホットパック",
			'Draw' => 'with_position',
			'Column' => array("ホットパック",
					  "ホットパック部位")),
		  array('Label' => "マイクロウエーブ",
			'Draw' => 'with_position',
			'Column' => array("マイクロウエーブ",
					  "マイクロウエーブ部位")),
		  array('Label' => "超音波法療",
			'Draw' => 'with_position',
			'Column' => array("超音波法療",
					  "超音波法療部位")),
		  array('Label' => "低周波法療",
			'Draw' => 'with_position',
			'Column' => array("低周波法療",
					  "低周波法療部位")),
		  array('Label' => "過流浴",
			'Draw' => 'with_position',
			'Column' => array("過流浴",
					  "過流浴部位")),
		  array('Label' => "アイスパック",
			'Draw' => 'with_position',
			'Column' => array("アイスパック",
					  "アイスパック部位")),
		  array('Label' => "ハドマー",
			'Draw' => 'with_position',
			'Column' => array("ハドマー",
					  "ハドマー部位")),

		  array('Label' => '牽引',
			'Draw' => 'traction',
			'Column' => array("牽引", "頚部", "頚部強度",
					  "腰部", "腰部強度")),
		  "その他特記事項"));

	$cfg['DPAGE_BREAKS'] = array(4);

	$sel = array();

	foreach ($cfg['DCOLS'] as $elem) {
		if (!array_key_exists('Column', $elem))
			continue;
		$col = $elem['Column'];
		if (!is_array($col)) {
			$col = array($col);
		}
		foreach ($col as $c) {
			if (array_key_exists($c,
					     $cfg['AlreadyHasSelectionFor']))
				continue;

			/* This specific hack is needed only because
			 * the base code and the schema definition is yucky.
			 */
			if ($elem['Draw'] == 'pos')
				continue;
			$sel[] = "X.\"$c\" AS \"$c\"";
			$cfg['AlreadyHasSelectionFor'][$c] = 1;
		}
	}
	foreach ($__lib_u_therapist_norder_yucky_cols as $c) {
		$sel[] = "X.\"$c\" AS \"$c\"";
		$cfg['AlreadyHasSelectionFor'][$c] = 1;
	}

	$sel = implode(",\n  ", $sel);
	$stmt_head = ($cfg['STMT_SEL'] . ",\n  "
		      . $sel . "\n" . $cfg['STMT_FROM']);

	$cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
	$cfg['STMT'] = $stmt_head . 'WHERE X."Superseded" IS NULL';

}

class rehab_norder_display extends simple_object_display {

  var $debug = 1;

  function rehab_norder_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_therapist_norder_cfg_detailed(&$cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }

  function omit_if_empty($desc, $data, $hdata) {
    $col = $desc['Column'];
    if ($data[$col]) { return 0; }
    return 1;
  }

  function history($direction=NULL) {
    $result = simple_object_display::history($direction);
    return $result + 16;
  }

  function dx_check_box($desc, $data, $changed) {
	  foreach ($desc['Column'] as $item)
		  if ($data[$item] == 'on')
			  print htmlspecialchars($item)."&nbsp;";
  }

  function dx_stop_basis($desc, $data, $changed) {
    if ($data["意識レベル低下"] == 'on')
      print "意識レベル低下<br>";
    if ($data["けいれんの重責"] == 'on')
      print "けいれんの重責<br>";

    if (($v = $data["体温自由記載"]) || ($v = $data["体温"]))
      printf("<br />体温 %s &deg;以上",$v);

    if ($v = $data["収縮期血圧"])
      printf("<br />収縮期血圧 %s mmHg以上",$v);
    if ($v = $data["拡張期血圧"])
      printf("<br />拡張期血圧 %s mmHg以上",$v);
    if ($v = $data["心拍数inc"])
      printf("<br />運動時心拍数 %s/分上昇",$v);
    if ($v = $data["心拍数dec"])
      printf("<br />運動時心拍数 %s/分下降",$v);
    if (($v = $data["SPO2自由記載"]) ||
	($v = $data["SPO2"]))
      printf("<br />SPO2％ %s％以上",$v);
    if ($v = $data["Andersonの基準"])
      printf("<br />Andersonの基準 %s",$v);
    if ($v = $data["Andersonの基準コメント"])
      printf("<br />Andersonの基準コメント %s",$v);
  }

  function dx_pos($d, $data, $changed) {
    if ($data[$d['Column'].'PT'] == 'on')
      print " PT";
    if ($data[$d['Column'].'OT'] == 'on')
      print " OT";
    if ($data[$d['Column'].'ST'] == 'on')
      print " ST";
  }

  function dx_coordinate($d, $data, $changed) {
    $this->dx_pos(array('Column' => '全身調整訓練'));
    if ($data["有酸素運動歩行"])
      printf("<br />歩行 %s 分間",
	     $data["有酸素運動歩行Time自由記載"] ?
	     $data["有酸素運動歩行Time自由記載"] :
	     $data["有酸素運動歩行Time"]);
    if ($data["有酸素運動車椅子駆動"])
      printf("<br />車椅子駆動 %s 分間",
	     $data["有酸素運動車椅子駆動Time自由記載"] ?
	     $data["有酸素運動車椅子駆動Time自由記載"] :
	     $data["有酸素運動車椅子駆動Time"]);
    if ($data["有酸素運動起居動作"])
      printf("<br />起居動作 %s 分間",
	     $data["有酸素運動起居動作Time自由記載"] ?
	     $data["有酸素運動起居動作Time自由記載"] :
	     $data["有酸素運動起居動作Time"]);
    if ($data["有酸素運動座位での全身運動"])
      printf("<br />座位での全身運動 %s 分間",
	     $data["有酸素運動座位での全身運動Time自由記載"] ?
	     $data["有酸素運動座位での全身運動Time自由記載"] :
	     $data["有酸素運動座位での全身運動Time"]);
    if ($data["有酸素運動状況に応じて"])
      printf("<br />状況に応じて %s 分間",
	     $data["有酸素運動状況に応じてTime自由記載"] ?
	     $data["有酸素運動状況に応じてTime自由記載"] :
	     $data["有酸素運動状況に応じてTime"]);
    if ($data["有酸素運動"])
      printf("<br />%s %s 分間",
	     $data["有酸素運動"],
	     $data["有酸素運動Time"]);
    if ($data["有酸素運動心拍数"])
      printf("<br />目標心拍数 %s b／分 MaxHR %s ％付近",
	     $data["有酸素運動心拍数"],
	     $data["有酸素運動心拍数MAX"]);
    if ($data["チルトテーブル"])
      printf("<br />チルトテーブル %s &deg; %s 分間 %s セット",
	     $data["チルトテーブル"],
	     $data["チルトテーブルTime"],
	     $data["チルトテーブルSet"]);
    if (($v = $data["ベッドギャッジ自由記載"]) ||
	($v = $data["ベッドギャッジ"]) && $v != '-')
      printf("<br />ベッドギャッジ %s &deg; %s 分間 %s セット", $v,
	     $data["ベッドギャッジTime自由記載"] ?
	     $data["ベッドギャッジTime自由記載"] :
	     $data["ベッドギャッジTime"],
	     $data["ベッドギャッジSet自由記載"] ?
	     $data["ベッドギャッジSet自由記載"] :
	     $data["ベッドギャッジSet"]);
    if ($data["ベッドギャッジ心拍数"] &&
	$data["ベッドギャッジ心拍数MAX"])
      printf("<br />目標心拍数 %s b／分 MaxHR %s ％付近",
	     $data["ベッドギャッジ心拍数"],
	     $data["ベッドギャッジ心拍数MAX"]);
    if ($data["SPO2MAX"])
      printf("<br />SPO2 %s ％以下の下降で休憩をとる",
	     $data["SPO2MAX"]);
  }

  function dx_with_position($d, $data, $changed) {
    $c = $d['Column'][0];
    $cp = $d['Column'][1];
    if ($data[$c]) {
      print "* ";
      print $data[$cp];
    }
  }

  function dx_traction($d, $data, $changed) {
      if ($data["頚部"]) {
	print "頚部 (強度";
	print $data["頚部強度"];
	print ")<br />";
      }
      if ($data["腰部"]) {
	print $data["腰部強度"];
	print "腰部 (強度";
	print $data["腰部強度"];
	print ")<br />";
      }
  }
}


?>
