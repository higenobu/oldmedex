<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function _lib_u_pharmacy_csl_config() {
  $_lib_u_pharmacy_csl_locs_llayo = array
    (2, "¹ð¼¨Ì¾¾Î", "¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾", '//',
     1, "À½Â¤²ñ¼Ò", "ÈÎÇä²ñ¼Ò",
     "ÊñÁõÃ±°Ì", "ÊñÁõÁíÎÌ");

  $_lib_u_pharmacy_csl_locs_cols = array();
  foreach ($_lib_u_pharmacy_csl_locs_llayo as $elt) {
    if (! _lib_so__is_table_control($elt))
      $_lib_u_pharmacy_csl_locs_cols[] = $elt;
  }

  $_lib_u_pharmacy_csl_base_stmt =
    ('SELECT C."ObjectID", ' .
     // 'C."ËãÌô", C."ÆÇÌô", C."Í¢·ìÍÑ·ì±Õ", C."ÆÃÄêÀ¸ÊªÍ³ÍèÀ½ÉÊ", ' .
     '"¹ð¼¨Ì¾¾Î", "¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾", "À½Â¤²ñ¼Ò", "ÈÎÇä²ñ¼Ò",
     ("ÊñÁõÃ±°Ì¿ô" || "ÊñÁõÃ±°ÌÃ±°Ì") AS "ÊñÁõÃ±°Ì",
     ("ÊñÁõÁíÎÌ¿ô" || "ÊñÁõÁíÎÌÃ±°Ì") AS "ÊñÁõÁíÎÌ"
     FROM "Medis°åÌôÉÊ¥Þ¥¹¥¿¡¼" AS D
     JOIN "´ÉÍýÌôÉÊ¥Þ¥¹¥¿¡¼" AS C
     ON D."ObjectID" = C."ObjectID" WHERE (NULL IS NULL)');

  $_lib_u_pharmacy_csl_locs_cfg = array
    (
     'TABLE' => '´ÉÍýÌôÉÊ¥Þ¥¹¥¿¡¼',
     'STMT' => $_lib_u_pharmacy_csl_base_stmt . ' AND "Superseded" IS NULL',
     'COLS' => array('unused'),
     'LCOLS' => $_lib_u_pharmacy_csl_locs_cols,
     'LLAYO' =>   $_lib_u_pharmacy_csl_locs_llayo,
     'ENABLE_QBE' => array
     ("¹ð¼¨Ì¾¾Î", "¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾",
      array('Column' => "Åö±¡ºÎÍÑ",
	    'Compare' => '"Åö±¡ºÎÍÑ"',
	    'Draw' => 'enum',
	    'Enum' => array('F' => 'ÉÑ½ÐÊ¬¤Î¤ß',
			    'YF' => 'ºÎÍÑÊ¬¤Î¤ß',
			    '' => '¥Þ¥¹¥¿Á´¤Æ'),
	    'CompareMethod' => 'enum_single_char',
	    'Singleton' => 1),
      ),
     'DEFAULT_QBE' => array(array('Åö±¡ºÎÍÑ', 'F')),
     'LIST_IDS' => array('ObjectID', "¹ð¼¨Ì¾¾Î", "¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾"),
     'UNIQ_ID' => 'C."ObjectID"',
     'ALLOW_SORT' => array
     ('¹ð¼¨Ì¾¾Î' => array('¹ð¼¨Ì¾¾Î' => '"¹ð¼¨Ì¾¾Î"'),
      '¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾' => array('¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾' => '"¥ì¥»¥×¥ÈÅÅ»»½èÍý¥·¥¹¥Æ¥à°åÌôÉÊÌ¾"'),
      'À½Â¤²ñ¼Ò' => array('À½Â¤²ñ¼Ò' => '"À½Â¤²ñ¼Ò"'),
      'ÈÎÇä²ñ¼Ò' => array('ÈÎÇä²ñ¼Ò' => '"ÈÎÇä²ñ¼Ò"') ),
     );
  return $_lib_u_pharmacy_csl_locs_cfg;
}

class list_of_controlled_substances extends list_of_simple_objects {

  function list_of_controlled_substances($prefix, $config=NULL) {
    if (is_null($config))
      $config = _lib_u_pharmacy_csl_config();
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function base_fetch_stmt_0() {
    $bfs = list_of_simple_objects::base_fetch_stmt_0();
    if ('' != ($lmt = $this->so_config['LimitTo']))
      $bfs = $bfs . ' AND C.' . mx_db_sql_quote_name($lmt) . " = 'Y'";
    return $bfs;
  }

}

class list_of_blood_or_bio extends list_of_controlled_substances {
  function list_of_blood_or_bio($prefix, $config=NULL) {
    list_of_controlled_substances::list_of_controlled_substances
      ($prefix, $config);
  }
  function base_fetch_stmt_0() {
    $bfs = list_of_controlled_substances::base_fetch_stmt_0();
    if ('' != ($lmt = $this->so_config['LimitTo']))
      $bfs = $bfs . ' AND C.' . mx_db_sql_quote_name($lmt) . " = 'Y'";
    return ($bfs . ' AND (C."Í¢·ìÍÑ·ì±Õ" = \'Y\' OR ' .
	    'C."ÆÃÄêÀ¸ÊªÍ³ÍèÀ½ÉÊ" = \'Y\')');
  }
}

class list_of_narcotic_or_poison extends list_of_controlled_substances {
  function list_of_narcotic_or_poison($prefix, $config=NULL) {
    list_of_controlled_substances::list_of_controlled_substances
      ($prefix, $config);
  }
  function base_fetch_stmt_0() {
    $bfs = list_of_controlled_substances::base_fetch_stmt_0();
    if ('' != ($lmt = $this->so_config['LimitTo']))
      $bfs = $bfs . ' AND C.' . mx_db_sql_quote_name($lmt) . " = 'Y'";
    return ($bfs . ' AND (C."ËãÌô" = \'Y\' OR ' .
	    'C."ÆÇÌô" = \'Y\')');
  }
}

?>

