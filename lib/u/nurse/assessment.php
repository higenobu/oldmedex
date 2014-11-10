<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_assessment_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '患者アセス表',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '日付',
      'LCOLS' => array('日付', '記録者名'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名"
FROM "患者アセス表" AS F
LEFT JOIN "職員台帳" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    ('食事/排泄' => array
     (
      array("食事―配膳・食事介助方法",
	    "配膳・食事介助方法", "text"),
      array("食事―食べるスピード",
	    "食べるスピード", "text"),
      array("食事―１回の食事に要する時間",
	    "１回の食事に要する時間", "text"),
      array("食事―好き嫌いなど",
	    "好き嫌いなど", "text"),
      array("排泄―排尿・排便",
	    "排尿・排便", "text"),
      array("排泄―おむつの当て方・枚数",
	    "おむつの当て方・枚数", "text"),
      array("排泄―排泄のパターン",
	    "排泄のパターン", "text"),
      ),

     '睡眠・休息/動作・移動' => array
     (
      array("睡眠・休息―睡眠のパターン",
	    "睡眠のパターン", "text"),
      array("睡眠・休息―イビキ",
	    "イビキ", "text"),
      array("動作・移動―杖使用・車椅子",
	    "杖使用・車椅子", "text"),
      array("動作・移動―問題行動",
	    "問題行動", "text"),
      array("動作・移動―自立介助必要",
	    "自立介助必要", "text"),
      ),

     '衣服/清潔/皮膚の状態' => array
     (
      array("衣服―洗濯",
	    "洗濯", "text"),
      array("清潔―特浴・介助浴",
	    "特浴・介助浴", "text"),
      array("清潔―清拭",
	    "清拭", "text"),
      array("清潔―整容",
	    "整容", "text"),
      array("皮膚の状態―褥瘡の状態",
	    "褥瘡の状態", "text"),
      array("皮膚の状態―湿疹・皮膚の乾燥",
	    "湿疹・皮膚の乾燥", "text"),
      ),

     '環境/内服管理' => array
     (
      array("環境―使用シーツ類",
	    "使用シーツ類", "text"),
      array("環境―ベッド柵数・位置",
	    "ベッド柵数・位置", "text"),
      array("環境―ベッド周囲に設置するもの",
	    "ベッド周囲に設置するもの", "text"),
      array("環境―床頭台に置く物",
	    "床頭台に置く物", "text"),
      array("内服管理―内服の方法",
	    "内服の方法", "text"),
      array("内服管理―配薬の時間",
	    "配薬の時間", "text"),
      ),

     '家族関係/コミュニケーション' => array
     (
      array("家族関係―キーパーソン",
	    "キーパーソン", "text"),
      array("家族関係―面会時間・回数",
	    "面会時間・回数", "text"),
      array("コミュ―会話・筆談",
	    "会話・筆談", "text"),
      array("コミュ―文字の大きさ",
	    "文字の大きさ", "text"),
      ),

     'その他' => array
     (
      array("楽しみ―嬉しそうな場面",
	    "嬉しそうな場面", "text"),
      array("楽しみ―よく話す内容",
	    "よく話す内容", "text"),
      array("クセ・特徴―体質的なこと",
	    "体質的なこと", "text"),
      array("クセ・特徴―性格的なこと",
	    "性格的なこと", "text"),
      array("その他―当てはまらないこと",
	    "当てはまらないこと", "text"),
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
    $cfg['EPAGE_BREAKS'] = array(2);
  $page_num = -1;
  foreach ($flippage as $page_name => $page_desc) {
    $page_num++;
    foreach ($page_desc as $c) {
      $a = array('Page' => $page_num,
		 'Column' => $c[0],
		 'Label' => $c[1],
		 'Draw' => $c[2]);
      $cfg['ECOLS'][] = $a;
      $cfg['DCOLS'][] = $a;
      $cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = '記録者名';
}

class list_of_nurse_assessments extends list_of_ppa_objects {

  function list_of_nurse_assessments($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_assessment_cfg(&$cfg);
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

class nurse_assessment_display extends simple_object_display {

  function nurse_assessment_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_assessment_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_assessment_edit extends simple_object_edit {

  function nurse_assessment_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_assessment_cfg(&$cfg);
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

}
?>
