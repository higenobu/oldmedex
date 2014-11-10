<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_ward_patient_cfg = array
(
 'COLS' => array('´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð', 'ÉÂ¼¼Ì¾'),
 'TABLE' => '´µ¼ÔÂæÄ¢',
 'LIST_IDS' => array('ObjectID',
		     '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð', 'ÉÂ¼¼', 'ÉÂ¼¼Ì¾'),
 'LCOLS' => array('ÉÂ¼¼Ì¾', '´µ¼ÔID', '´µ¼ÔÌ¾', '´µ¼ÔÇ¯Îð'),
 'LCHOICE' => array(0 => 'ÉÂÅï´µ¼Ô¤«¤éÁª¤Ö',1 => 'Á´´µ¼Ô¤«¤éÁª¤Ö'),
 'ALLOW_SORT' => array('ÉÂ¼¼Ì¾' => array('ÉÂ¼¼Ì¾' => 'R."ÉÂ¼¼Ì¾"'),
		       '´µ¼ÔID' => array('´µ¼ÔID' => 'P."´µ¼ÔID"'),
		       '´µ¼ÔÌ¾' => array('´µ¼ÔÌ¾' => 
					 '(P."À«" || \' \' || P."Ì¾") '),
		       '´µ¼ÔÇ¯Îð' => array
		       ('´µ¼ÔÇ¯Îð' =>
			'(extract(year from age(now(), P."À¸Ç¯·îÆü")))')),
 'UNIQ_ID' => 'P."ObjectID"',
 'ENABLE_QBE' => array(array('Column' => '´µ¼ÔID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		       array('Column' => '´µ¼ÔÌ¾',
			     'Compare' => '("À«"||"Ì¾")'),
		       ),
 );

class list_of_ward_patients extends list_of_simple_objects {
  var $base_select_stmt = '
SELECT P."ObjectID", (P."À«" || \' \' || P."Ì¾") AS "´µ¼ÔÌ¾", P."´µ¼ÔID",
(extract(year from age(now(), P."À¸Ç¯·îÆü"))) AS "´µ¼ÔÇ¯Îð",
RP."ÉÂ¼¼", R."ÉÂ¼¼Ì¾", R."ÉÂÅï", W."ÉÂÅïÌ¾"
FROM "´µ¼ÔÂæÄ¢" AS P
LEFT JOIN ("ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" AS RPD
           JOIN "ÉÂ¼¼´µ¼ÔÉ½" AS RP
           ON RPD."ÉÂ¼¼´µ¼ÔÉ½" = RP."ObjectID" AND RP."Superseded" IS NULL
           JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
           ON RP."ÉÂ¼¼" = R."ObjectID" AND R."Superseded" IS NULL
	   JOIN "ÉÂÅï°ìÍ÷É½" AS W
           ON R."ÉÂÅï" = W."ObjectID" AND W."Superseded" IS NULL )
ON RPD."´µ¼Ô" = P."ObjectID" AND P."Superseded" IS NULL
WHERE (NULL IS NULL)';

  function list_of_ward_patients($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_patient_cfg;
    if (is_null($config))
      $config = $_lib_u_nurse_ward_patient_cfg;
    $this->ward = $config['Ward'];
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function base_fetch_stmt_1($ix) {
    if ($ix == 1)
      return $this->base_select_stmt;
    return ($this->base_select_stmt . ' AND  R."ÉÂÅï" = ' .
	    mx_db_sql_quote($this->ward));
  }

  function draw() {
    mx_titlespan($this->Title);
    list_of_simple_objects::draw();
    mx_formi_submit($this->prefix . 'id-select', $this->Original,
		    "<span class=\"link\">ÊÑ¹¹¤·¤Ê¤¤</span>");
  }

}
?>
