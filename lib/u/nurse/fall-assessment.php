<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_fall_assessment_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '転倒転落アセス表',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '日付',
      'LCOLS' => array('日付', '記録者名'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名"
FROM "転倒転落アセス表" AS F
LEFT JOIN "職員台帳" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    (
     '身体運動性の変調' => array
     (
      array("身体運動_01", "補助器具を使用している", "check"),
      array("身体運動_02", "移動に一部介助が必要である", "check"),
      array("身体運動_03", "歩行時にふらつきがある", "check"),
      array("身体運動_04", "骨、関節に異常がある", "check"),
      array("身体運動_05", "跛行がある", "check"),
      ),

     '感覚機能障害の程度' => array
     (
      array("感覚機能_01", "視力障害がある", "check"),
      array("感覚機能_02", "視野狭窄がある", "check"),
      array("感覚機能_03", "暗さの変化に順応できない", "check"),
      array("感覚機能_04", "眼鏡を使用している", "check"),
      array("感覚機能_05", "聴力障害がある", "check"),
      array("感覚機能_06", "補聴器を使用している", "check"),
      array("感覚機能_07", "抹消神経障害がある", "check"),
      ),

     '循環動態' => array
     (
      array("循環動態_01", "起立性低血圧がある", "check"),
      array("循環動態_02", "不整脈がある", "check"),
      array("循環動態_03", "神経発作の経験がある", "check"),
      array("循環動態_04", "めまいがある", "check"),
      array("循環動態_05", "貧血がある", "check"),
      ),

     'ガス交換障害' => array
     (
      array("ガス交換_01", "PaO２の低下がある", "check"),
      array("ガス交換_02", "PaCO２の低下がある上昇がある", "check"),
      ),

     '薬物の使用状況' => array
     (
      array("薬物使用_01", "睡眠剤、精神安定剤を服用", "check"),
      array("薬物使用_02", "降圧剤を服用している", "check"),
      array("薬物使用_03", "利尿剤を服用している", "check"),
      array("薬物使用_04", "抗アレルギー剤を服用している", "check"),
      ),

     '精神状態' => array
     (
      array("精神状態_01", "夜間せん妄がある", "check"),
      array("精神状態_02", "判断力の低下がある", "check"),
      array("精神状態_03", "排泄の援助を受けることにたいする抵抗感がある", "check"),
      ),

     '転倒・転落の経験' => array
     (
      array("転倒落経験_01", "転倒・転落の経験がある", "check"),
      ),

     '睡眠のパターン' => array
     (
      array("睡眠_01", "寝つきが悪い", "check"),
      array("睡眠_02", "熟睡間がない", "check"),
      array("睡眠_03", "ねぼけがある", "check"),
      array("睡眠_04", "昼寝の習慣がある", "check"),
      ),

     '排泄パターン' => array
     (
      array("排泄_01", "夜間排泄のために覚醒する", "check"),
      array("排泄_02", "尿意を我慢できない", "check"),
      array("排泄_03", "排尿困難がある", "check"),
      array("排泄_04", "排泄の変調がある", "check"),
      ),

     'その他（ご家族より情報を得る）' => array
     (
      array("その他_01", "行動をあせりやすい", "check"),
      array("その他_02", "何かをする時あわてる", "check"),
      array("その他_03", "他者から見て危険と思う行動を平気でする", "check"),
      ),
     );

  $cfg['ECOLS'] = array(array('Column' => '日付',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('日付');
  $cfg['ICOLS'] = array('日付', '患者');
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
  $cfg['DPAGE_BREAKS'] = 
    $cfg['EPAGE_BREAKS'] = array(1, 3, 5, 7);
  $page_num = -1;
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $c) {
      $a = array('Page' => $page_num,
		 'Column' => $c[0],
		 'Label' => ($c[1] ? $c[1] : $c[0]),
		 'Draw' => $c[2]);
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      if (! is_null($c[0]))
	$cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = '記録者名';
}

class list_of_nurse_fall_assessments extends list_of_ppa_objects {

  function list_of_nurse_fall_assessments($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fall_assessment_cfg(&$cfg);
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

class nurse_fall_assessment_display extends simple_object_display {

  function nurse_fall_assessment_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fall_assessment_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_fall_assessment_edit extends simple_object_edit {

  function nurse_fall_assessment_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_fall_assessment_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['日付'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['患者'] = $this->so_config['Patient_ObjectID'];
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {

    $bad = 0;
    if ($st = mx_db_validate_date($this->data['日付'])) {
      $this->err("(日付): $st\n");
      $bad++;
    }

    if ($bad == 0)
      return 'ok';
  }

  function _pre_commit_hook($db) {
    return NULL;
    return 'データベースへの書き戻しはまだですから、「中止」で終って下さい';
  }

}
?>
