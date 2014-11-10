<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_bedsore_eval_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => 'êóáì·Ð²áÉ¾²ÁÉ½',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => 'ÆüÉÕ',
      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $columns = array
    (array('Column' => 'Depth',
	   'Label' => '¿¼¥µ',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('d0' => 'd0: ÈéÉæÂ»½ý¡¦È¯ÀÖ¤Ê¤·',
	    'd1' => 'd1: »ýÂ³¤¹¤ëÈ¯ÀÖ',
	    'd2' => 'd2: ¿¿Èé¤Þ¤Ç¤ÎÂ»½ý',
	    'D3' => 'D3: Èé²¼ÁÈ¿¥¤Þ¤Ç¤ÎÂ»½ý',
	    'D4' => 'D4: Èé²¼ÁÈ¿¥¤òÄ¶¤¨¤ëÂ»½ý',
	    'D5' => 'D5: ´ØÀá¹Ð¡¦ÂÎ¹Ð¤Ë»ê¤ëÂ»½ý¤Þ¤¿¤Ï¡¢¿¼¤µÈ½Äê¤¬ÉÔÇ½¤Ê¾ì¹ç')
	   ),
     array('Column' => 'Exudate',
	   'Label' => '¿»½Ð±Õ',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('e0' => 'e0: ¤Ê¤·',
	    'e1' => 'e1: ¾¯ÎÌ¡§ËèÆü¤Î¥É¥ì¥Ã¥·¥ó¥°¸ò´¹¤òÍ×¤·¤Ê¤¤',
	    'E2' => 'E2: ÃæÅùÎÌ¡§£±Æü£±²ó¤Î¥É¥ì¥Ã¥·¥ó¥°¸ò´¹¤òÍ×¤¹¤ë',
	    'E3' => 'E3: Â¿ÎÌ¡§£±Æü£²²ó°Ê¾å¤Î¥É¥ì¥Ã¥·¥ó¥°¸ò´¹¤òÍ×¤¹¤ë')
	   ),
     array('Column' => 'Size',
	   'Label' => '¥µ¥¤¥º',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('s0' => 's0: ÈéÉæÂ»½ý¤Ê¤·',
	    's1' => 's1: 4Ì¤Ëþ',
	    's2' => 's2: £´°Ê¾å¡¢£±£¶Ì¤Ëþ',
	    's3' => 's3: £±£¶°Ê¾å¡¢£³£¶Ì¤Ëþ',
	    's4' => 's4: £³£¶°Ê¾å¡¢£¶£´Ì¤Ëþ',
	    's5' => 's5: £¶£´°Ê¾å¡¢100Ì¤Ëþ',
	    'S6' => 'S6: 100°Ê¾å')
	   ),
     array('Column' => 'Inflammation',
	   'Label' => '±ê¾É',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('i0' => 'i0: ¶É½ê¤Î±ê¾ÉÄ§¸õ¤Ê¤·',
	    'i1' => 'i1: ¶É½ê¤Î±ê¾ÉÄ§¸õ¤Ê¤·±ê¾ÉÄ§¸õ¤¢¤ê¡ÊÈ¯ÀÖ¡¦¼ðÄ±¡¦Ç®´¶¡¦áÖÄË¡Ë',
	    'I2' => 'I2: ¶É½ê¤ÎÌÀ¤é¤«¤Ê´¶À÷Ä§¸õ¤¢¤ê¡Ê±ê¾ÉÄ§¸õ¡¦Ç¿¡¦°­½­¤Ê¤É¡Ë',
	    'I3' => 'I3: Á´¿ÈÅª±Æ¶Á¤¢¤ê¡ÊÈ¯Ç®¤Ê¤É¡Ë')
	   ),
     array('Column' => 'Granulation',
	   'Label' => 'Æù²êÁÈ¿¥',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('g0' => 'g0: ¼£ÎÅ¤¢¤ë¤¤¤ÏÁÏ¤¬Àõ¤¤¤¿¤áÆù²ê·ÁÀ®¤ÎÉ¾²Á¤¬¤Ç¤­¤Ê¤¤',
	    'g1' => 'g1: ÎÉÀ­Æù²ê¤¬¡¢ÁÏÌÌ¤Î90¡ó¤òÀê¤á¤ë',
	    'g2' => 'g2: ÎÉÀ­Æù²ê¤¬¡¢ÁÏÌÌ¤Î50¡ó°Ê¾å90¡óÌ¤Ëþ¤òÀê¤á¤ë',
	    'G3' => 'G3: ÎÉÀ­Æù²ê¤¬¡¢ÁÏÌÌ¤Î10¡ó°Ê¾å50¡óÌ¤Ëþ¤òÀê¤á¤ë',
	    'G4' => 'G4: ÎÉÀ­Æù²ê¤¬¡¢ÁÏÌÌ¤Î10¡ó°Ê¾å51¡óÌ¤Ëþ¤òÀê¤á¤ë',
	    'G5' => 'G5: ÎÉÀ­Æù²ê¤¬¡¢¤Þ¤Ã¤¿¤¯·ÁÀ®¤µ¤ì¤Æ¤¤¤Ê¤¤')
	   ),
     array('Column' => 'NecroticTissue',
	   'Label' => '²õ»àÁÈ¿¥',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('n0' => 'n0: ²õ»àÁÈ¿¥¤Ê¤·',
	    'N1' => 'N1: ½À¤é¤«¤¤²õ»àÁÈ¿¥¤¢¤ê',
	    'N2' => 'N2: ¹Å¤¯¸ü¤¤Ì©Ãå¤·¤¿²õ»àÁÈ¿¥¤¢¤ê')
	   ),
     array('Column' => 'Pocket',
	   'Label' => '¥Ý¥±¥Ã¥È',
	   'Draw' => 'enum',
	   'Enum' => array
	   ('  ' => '¤Ê¤·',
	    'P1' => 'P1: 4Ì¤Ëþ',
	    'P2' => 'P2: £´°Ê¾å¡¢16Ì¤Ëþ',
	    'P3' => 'P3: £±£¶°Ê¾å¡¢36Ì¤Ëþ',
	    'P4' => 'P4: 36°Ê¾å')
	   ),
     );

  $stmt_head = '
SELECT F.*, (E."À«" || E."Ì¾") AS "µ­Ï¿¼ÔÌ¾", (\'\'';

  foreach ($columns as $a) {
    $stmt_head .= '|| \' \' || COALESCE(' .
      mx_db_sql_quote_name($a['Column']) . ',\'\')';
  }

  $stmt_head .= ') as "É¾²Á"
FROM "êóáì·Ð²áÉ¾²ÁÉ½" AS F
LEFT JOIN "¿¦°÷ÂæÄ¢" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  $cfg['ECOLS'] = array(array('Column' => 'ÆüÉÕ',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('ÆüÉÕ');
  $cfg['ICOLS'] = array('ÆüÉÕ', '´µ¼Ô');
  $cfg['LCOLS'] = array('ÆüÉÕ', 'µ­Ï¿¼ÔÌ¾', 'É¾²Á');

  foreach ($columns as $a) {
    $cfg['ECOLS'][] = $a;
    $cfg['DCOLS'][] = $a;
    if (! is_null($a['Column']))
      $cfg['ICOLS'][] = $a['Column'];
  }
  $cfg['DCOLS'][] = 'µ­Ï¿¼ÔÌ¾';
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
      $paging_orders[] = (($col == 'ÆüÉÕ') ? 1 : 0);
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
    $this->data['ÆüÉÕ'] = mx_today_string();
  }

  function annotate_row_data(&$d) {
    $d['´µ¼Ô'] = $this->so_config['Patient_ObjectID'];
  }

  function annotate_form_data(&$d) {
    simple_object_edit::annotate_form_data($d);
    $this->annotate_row_data($d);
  }

  function _validate() {

    $bad = 0;
    if ($st = mx_db_validate_date($this->data['ÆüÉÕ'])) {
      $this->err("(ÆüÉÕ): $st\n");
      $bad++;
    }

    if ($bad == 0)
      return 'ok';
  }

}
?>
