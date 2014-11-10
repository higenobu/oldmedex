<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_nurse_training_chart_cfg(&$cfg) { 

  $cfg = array_merge
    ($cfg, array
     ('TABLE' => '·±Îý¥Á¥ã¡¼¥ÈÉ½',
      'ALLOW_SORT' => 1,
      'DEFAULT_SORT' => 'ÆüÉÕ',
      'LCOLS' => array('ÆüÉÕ', 'µ­Ï¿¼ÔÌ¾'),

      'UNIQ_ID' => 'F."ObjectID"',
      ));

  $stmt_head = '
SELECT F.*, (E."À«" || E."Ì¾") AS "µ­Ï¿¼ÔÌ¾"
FROM "·±Îý¥Á¥ã¡¼¥ÈÉ½" AS F
LEFT JOIN "¿¦°÷ÂæÄ¢" AS E
ON E."userid" = F."CreatedBy" AND E."Superseded" IS NULL
';

  $cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
  $cfg['STMT'] = $stmt_head . 'WHERE F."Superseded" IS NULL';

  // List of flip-pages: db column, label, widget type
  $flippage = array
    (
     '¥³¥ß¥å¥Ë¥±¡¼¥·¥ç¥ó' => array
     (
      array("¥³¥ß¥å¡½½¸ÃÄ·±ÎýÅ¬±þ",
	    "½¸ÃÄ·±ÎýÅ¬±þ", "check"),
      ),

     '¾å»è' => array
     (
      array("¾å»è¡½Â¾Æ°²ÄÆ°°è·±Îý",
	    "Â¾Æ°²ÄÆ°°è·±Îý", "check"),
      array("¾å»è¡½²ð½õ¼«Æ°²ÄÆ°°è·±Îý",
	    "²ð½õ¼«Æ°²ÄÆ°°è·±Îý", "check"),
      array("¾å»è¡½¼ê»Ø¤òÍÑ¤¤¤ë¼ñÌ£³èÆ°",
	    "¼ê»Ø¤òÍÑ¤¤¤ë¼ñÌ£³èÆ°", "check"),
      array("¾å»è¡½Å½¤ê³¨¡¦ÅÉ¤ê³¨¡¦ÀÞ¤ê»æÅù",
	    "Å½¤ê³¨¡¦ÅÉ¤ê³¨¡¦ÀÞ¤ê»æÅù", "check"),
      ),

     'ÂÎ´´' => array
     (
      array("ÂÎ´´¡½Â¾Æ°²ÄÆ°°è·±Îý",
	    "Â¾Æ°²ÄÆ°°è·±Îý", "check"),
      array("ÂÎ´´¡½¥Ö¥ê¥Ã¥¸",
	    "¥Ö¥ê¥Ã¥¸", "check"),
      array("ÂÎ´´¡½¶þ¶Ê±¿Æ°",
	    "¶þ¶Ê±¿Æ°", "check"),
      array("ÂÎ´´¡½ºÂ°ÌÊÝ»ý·±Îý",
	    "ºÂ°ÌÊÝ»ý·±Îý", "check"),
      ),

     '²¼»è' => array
     (
      array("²¼»è¡½Â¾Æ°²ÄÆ°°è·±Îý",
	    "Â¾Æ°²ÄÆ°°è·±Îý", "check"),
      array("²¼»è¡½¥Ù¥Ã¥É¾å¡§Â­ÇØ¶þ±¿Æ°",
	    "¥Ù¥Ã¥É¾å¡§Â­ÇØ¶þ±¿Æ°", "check"),
      array("²¼»è¡½¶þ¿­±¿Æ°",
	    "¶þ¿­±¿Æ°", "check"),
      array("²¼»è¡½SLR",
	    "SLR", "check"),
      array("²¼»è¡½ºÂ°Ì¡§É¨¿­Å¸¼«Æ°±¿Æ°",
	    "ºÂ°Ì¡§É¨¿­Å¸¼«Æ°±¿Æ°", "check"),
      array("²¼»è¡½¸Ô¶þ¶Ê¼«Æ°±¿Æ°",
	    "¸Ô¶þ¶Ê¼«Æ°±¿Æ°", "check"),
      array("²¼»è¡½É¨¿­Å¸Äñ¹³±¿Æ°",
	    "É¨¿­Å¸Äñ¹³±¿Æ°", "check"),
      array("²¼»è¡½Î©°Ì¡§²¼»è¶þ¿­±¿Æ°",
	    "Î©°Ì¡§²¼»è¶þ¿­±¿Æ°", "check"),
      ),

     '´ðËÜÆ°ºî' => array
     (
      array("´ðËÜÆ°ºî¡½Â¾Æ°²ÄÆ°°è·±Îý",
	    "Â¾Æ°²ÄÆ°°è·±Îý", "check"),
      array("´ðËÜÆ°ºî¡½ÂÎ°ÌÊÑ´¹",
	    "ÂÎ°ÌÊÑ´¹", "check"),
      array("´ðËÜÆ°ºî¡½¿²ÊÖ¤êÆ°ºî·±Îý",
	    "¿²ÊÖ¤êÆ°ºî·±Îý", "check"),
      array("´ðËÜÆ°ºî¡½µ¯¾å¤ê¡¦ºÂ°ÌÊÝ»ý·±Îý",
	    "µ¯¾å¤ê¡¦ºÂ°ÌÊÝ»ý·±Îý", "check"),
      array("´ðËÜÆ°ºî¡½µ¯Î©¡¦Â­Æ§¤ß·±Îý",
	    "µ¯Î©¡¦Â­Æ§¤ß·±Îý", "check"),
      array("´ðËÜÆ°ºî¡½²ð½õÊâ¹Ô·±Îý",
	    "²ð½õÊâ¹Ô·±Îý", "check"),
      array("´ðËÜÆ°ºî¡½±þÍÑÊâ¹Ô·±Îý",
	    "±þÍÑÊâ¹Ô·±Îý", "check"),
      array("´ðËÜÆ°ºî¡½Á´¿ÈÄ´À°·±Îý",
	    "Á´¿ÈÄ´À°·±Îý", "check"),
      ),
     );

  $cfg['ECOLS'] = array(array('Column' => 'ÆüÉÕ',
			      'Option' => array('ime' => 'disabled',
						'validate' => 'date')));
  $cfg['DCOLS'] = array('ÆüÉÕ');
  $cfg['ICOLS'] = array('ÆüÉÕ', '´µ¼Ô');
  $cfg['DPAGES'] = array_keys($flippage);
  $cfg['EPAGES'] = $cfg['DPAGES'];
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
      if (! is_null($c[0]))
	$cfg['ICOLS'][] = $c[0];
    }
  }
  $cfg['DCOLS'][] = 'µ­Ï¿¼ÔÌ¾';
}

class list_of_training_charts extends list_of_ppa_objects {

  function list_of_training_charts($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_training_chart_cfg(&$cfg);
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

class nurse_training_chart_display extends simple_object_display {

  function nurse_training_chart_display($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_training_chart_cfg(&$cfg);
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

}

class nurse_training_chart_edit extends simple_object_edit {

  function nurse_training_chart_edit($prefix, $cfg=NULL) {
    if (is_null($cfg)) $cfg = array();
    __lib_u_nurse_training_chart_cfg(&$cfg);
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
