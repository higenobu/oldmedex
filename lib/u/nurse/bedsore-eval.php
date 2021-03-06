<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_bedsore_eval_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '褥瘡経過評価表',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => '日付',
      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $columns = array
    (array('Column' => 'Depth',
	   'Label' => '深サ',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('d0' => 'd0: 皮膚損傷・発赤なし',
	    'd1' => 'd1: 持続する発赤',
	    'd2' => 'd2: 真皮までの損傷',
	    'D3' => 'D3: 皮下組織までの損傷',
	    'D4' => 'D4: 皮下組織を超える損傷',
	    'D5' => 'D5: 関節腔・体腔に至る損傷または、深さ判定が不能な場合')
	   ),
     array('Column' => 'Exudate',
	   'Label' => '浸出液',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('e0' => 'e0: なし',
	    'e1' => 'e1: 少量：毎日のドレッシング交換を要しない',
	    'E2' => 'E2: 中等量：１日１回のドレッシング交換を要する',
	    'E3' => 'E3: 多量：１日２回以上のドレッシング交換を要する')
	   ),
     array('Column' => 'Size',
	   'Label' => 'サイズ',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('s0' => 's0: 皮膚損傷なし',
	    's1' => 's1: 4未満',
	    's2' => 's2: ４以上、１６未満',
	    's3' => 's3: １６以上、３６未満',
	    's4' => 's4: ３６以上、６４未満',
	    's5' => 's5: ６４以上、100未満',
	    'S6' => 'S6: 100以上')
	   ),
     array('Column' => 'Inflammation',
	   'Label' => '炎症',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('i0' => 'i0: 局所の炎症徴候なし',
	    'i1' => 'i1: 局所の炎症徴候なし炎症徴候あり（発赤・腫脹・熱感・疼痛）',
	    'I2' => 'I2: 局所の明らかな感染徴候あり（炎症徴候・膿・悪臭など）',
	    'I3' => 'I3: 全身的影響あり（発熱など）')
	   ),
     array('Column' => 'Granulation',
	   'Label' => '肉芽組織',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('g0' => 'g0: 治療あるいは創が浅いため肉芽形成の評価ができない',
	    'g1' => 'g1: 良性肉芽が、創面の90％を占める',
	    'g2' => 'g2: 良性肉芽が、創面の50％以上90％未満を占める',
	    'G3' => 'G3: 良性肉芽が、創面の10％以上50％未満を占める',
	    'G4' => 'G4: 良性肉芽が、創面の10％以上51％未満を占める',
	    'G5' => 'G5: 良性肉芽が、まったく形成されていない')
	   ),
     array('Column' => 'NecroticTissue',
	   'Label' => '壊死組織',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('n0' => 'n0: 壊死組織なし',
	    'N1' => 'N1: 柔らかい壊死組織あり',
	    'N2' => 'N2: 硬く厚い密着した壊死組織あり')
	   ),
     array('Column' => 'Pocket',
	   'Label' => 'ポケット',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('  ' => 'なし',
	    'P1' => 'P1: 4未満',
	    'P2' => 'P2: ４以上、16未満',
	    'P3' => 'P3: １６以上、36未満',
	    'P4' => 'P4: 36以上')
	   ),
     );

  $stmt_head = '
SELECT F.*, (E."姓" || E."名") AS "記録者名", (\'\'';

  foreach ($columns as $a) {
    $stmt_head .= '|| \' \' || COALESCE(' .
      mx_db_sql_quote_name($a['Column']) . ',\'\')';
  }

  $stmt_head .= ') as "評価"
FROM "褥瘡経過評価表" AS F
LEFT JOIN "職員台帳" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $cfg['ECOLS'] = array(array('Column' => '日付',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('日付');
  $cfg['ICOLS'] = array('日付', '患者');
  $cfg['LCOLS'] = array('日付', '記録者名', '評価');

  foreach ($columns as $a) {
    $cfg['ECOLS'][] = $a;
    $cfg['DCOLS'][] = $a;
    if (! is_null($a['Column']))
      $cfg['ICOLS'][] = $a['Column'];
  }
  $cfg['DCOLS'][] = '記録者名';
}

class list_of_nurse_bedsore_evals extends list_of_ppa_objects {

  function list_of_nurse_bedsore_evals($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_bedsore_eval_cfg(&$cfg);
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

class nurse_bedsore_eval_display extends simple_object_display {

  function nurse_bedsore_eval_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_bedsore_eval_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_bedsore_eval_edit extends simple_object_edit {

  function nurse_bedsore_eval_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_bedsore_eval_cfg(&$cfg);
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
