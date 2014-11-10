<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_func_eval_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '機能評価表',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '日付',
      'LCOLS' => array('日付', '記録者名'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名"
FROM "機能評価表" AS F
LEFT JOIN "職員台帳" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    ('コミュニケーション' =>
     array
     (array("コミュ_01", '意思疎通困難', "check"),
      array("コミュ_02", '挨拶ができる', "check"),
      array("コミュ_03", '模倣ができる', "check"),
      array("コミュ_04", '指示により動作が出来る', "check"),
      ),

     '上肢機能' =>
     array
     (array("上肢_L_01", 'ベッド上で上肢が動かない（左）', "check"),
      array("上肢_R_01", 'ベッド上で上肢が動かない（右）', "check"),
      array("上肢_L_02", '上肢を挙上できる（左）', "check"),
      array("上肢_R_02", '上肢を挙上できる（右）', "check"),
      array("上肢_L_03", '顔に手が届く（左）', "check"),
      array("上肢_R_03", '顔に手が届く（右）', "check"),
      array("上肢_L_04", '机に手が届く（左）', "check"),
      array("上肢_R_04", '机に手が届く（右）', "check"),
      array("上肢_L_05", 'ボールを掴める（左）', "check"),
      array("上肢_R_05", 'ボールを掴める（右）', "check"),
      array("上肢_L_06", '鉛筆を摘み上げられる（左）', "check"),
      array("上肢_R_06", '鉛筆を摘み上げられる（右）', "check"),
      array("上肢_L_07", '箸で食事ができる（左）', "check"),
      array("上肢_R_07", '箸で食事ができる（右）', "check"),
      ),

     '体幹機能' =>
     array
     (array("体幹_01", '体動不能', "check"),
      array("体幹_02", 'ベッド上でブリッジができる', "check"),
      array("体幹_03", '車椅子でバックレストから背中を離せる', "check"),
      array("体幹_04", '10分以上の座位保持ができる', "check"),
      ),

     '下肢機能' =>
     array
     (array("下肢_L_01", 'ベッド上で下肢が動かない（左）', "check"),
      array("下肢_R_01", 'ベッド上で下肢が動かない（右）', "check"),
      array("下肢_L_02", '足背屈できる（他動）（左）', "check"),
      array("下肢_R_02", '足背屈できる（他動）（右）', "check"),
      array("下肢_L_03", '背屈＜０°（左）', "check"),
      array("下肢_R_03", '背屈＜０°（右）', "check"),
      array("下肢_L_04", '下肢屈伸ができる（他動）（左）', "check"),
      array("下肢_R_04", '下肢屈伸ができる（他動）（右）', "check"),
      array("下肢_L_05", '膝＜-30°　（膝が伸びない）（左）', "check"),
      array("下肢_R_05", '膝＜-30°　（膝が伸びない）（右）', "check"),
      array("下肢_L_06", '膝＜90°　　（膝が曲がらない）（左）', "check"),
      array("下肢_R_06", '膝＜90°　　（膝が曲がらない）（右）', "check"),
      array("下肢_L_07", '下肢挙上ができる（自動）（左）', "check"),
      array("下肢_R_07", '下肢挙上ができる（自動）（右）', "check"),
      array("下肢_L_08", '座位で膝伸展できる（左）', "check"),
      array("下肢_R_08", '座位で膝伸展できる（右）', "check"),
      array("下肢_L_09", '足踏みできる（左）', "check"),
      array("下肢_R_09", '足踏みできる（右）', "check"),
      array("下肢_L_10", '抵抗に対し膝の伸展ができる（左）', "check"),
      array("下肢_R_10", '抵抗に対し膝の伸展ができる（右）', "check"),
      array("下肢_L_11", '立位で下肢の屈伸ができる（左）', "check"),
      array("下肢_R_11", '立位で下肢の屈伸ができる（右）', "check"),
      ),

     '基本動作' =>
     array
     (array("基本動作_01", '不良肢位拘縮', "check"),
      array("拘縮部位", '拘縮部位', "text"),
      array("基本動作_02", '臥床', "check"),
      array("基本動作_03", '寝返り', "check"),
      array("基本動作_04", '座位保持', "check"),
      array("基本動作_05", '起上がり', "check"),
      array("基本動作_06", '立位保持', "check"),
      array("基本動作_07", '起立', "check"),
      array("基本動作_08", '歩行', "check"),
      array("基本動作_09", '独歩', "check"),
      array("基本動作_10",
	    '起上り、起立等体位変換にて20mHg以上の血圧低下', "check"),
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
    $cfg['EPAGE_BREAKS'] = array(1);
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

class list_of_nurse_func_evals extends list_of_ppa_objects {

  function list_of_nurse_func_evals($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_func_eval_cfg(&$cfg);
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

class nurse_func_eval_display extends simple_object_display {

  function nurse_func_eval_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_func_eval_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_func_eval_edit extends simple_object_edit {

  function nurse_func_eval_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_func_eval_cfg(&$cfg);
    simple_object_edit::simple_object_edit
      ($prefix, &$cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['日付'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['患者'] = $this->so_config['Patient_ObjectID'];
    $this->dbglog("ARD: ");
    $this->dbglog(mx_var_dump($d));
    $this->dbglog(mx_var_dump($this->so_config));
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {

    $bad = 0;
    if ($this->data['基本動作_01'] == 'Y' &&
	mx_db_validate_length($this->data['拘縮部位'], 1, 0)) {
      $this->err("(拘縮部位): 空ではいけません\n");
      $bad++;
    }
    if ($st = mx_db_validate_date($this->data['日付'])) {
      $this->err("(日付): $st\n");
      $bad++;
    }

    if ($bad == 0)
      return 'ok';
  }

}

?>
