<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$__lib_u_ct_filename_prefix_separator = ' ';

function __lib_u_ct_ws_anno(&$desc, &$data)
{
	global $__lib_u_ct_filename_prefix_separator;
	$desc['Option']['filename_prefix'] =
		$data["¥×¥í¥È¥³¡¼¥ëNo"] . $__lib_u_ct_filename_prefix_separator .
		$data["»î¸³No"] . $__lib_u_ct_filename_prefix_separator;
}

$_lib_u_ct_ct_cfg = array
(
 'TABLE' => '¼£¸³',
 'COLS' => array(
		 "¥×¥í¥È¥³¡¼¥ëNo",
		 "°ÍÍê¼Ô",
		 "¼£¸³Ì¾",
		 "¼£¸³ÌôÌ¾",
		 "ÃùË¡",
		 "Èï¸³¼Ô¿ô(Í½»î¸³)",
		 "Èï¸³¼Ô¿ô(ËÜ»î¸³)",
		 "ÌôºÞÅêÍ¿ÎÌ",
		 "¼£¸³¼Â»Ü´ü´Ö(³«»Ï)",
		 "¼£¸³¼Â»Ü´ü´Ö(½ªÎ»)",
		 "Æþ±¡Æü»þ",
		 "ÅêÌôÆü»þ",
		 "Âà±¡Æü»þ",
		 "ÀÕÇ¤¼Ô",
		 ),
 'LCOLS' => array("¥×¥í¥È¥³¡¼¥ëNo", "¼£¸³Ì¾", "¼£¸³¼Â»Ü´ü´Ö(³«»Ï)"),
 'DCOLS' => array(
		 "¥×¥í¥È¥³¡¼¥ëNo",
		 "°ÍÍê¼Ô",
		 "¼£¸³Ì¾",
		 "¼£¸³ÌôÌ¾",
		 "ÃùË¡",
		 "Èï¸³¼Ô¿ô(Í½»î¸³)",
		 "Èï¸³¼Ô¿ô(ËÜ»î¸³)",
		 "ÌôºÞÅêÍ¿ÎÌ",
		 "¼£¸³¼Â»Ü´ü´Ö(³«»Ï)",
		 "¼£¸³¼Â»Ü´ü´Ö(½ªÎ»)",
		 "Æþ±¡Æü»þ",
		 "ÅêÌôÆü»þ",
		 "Âà±¡Æü»þ",
		 "ÀÕÇ¤¼Ô",
		  ),
 'ECOLS' => array(
		 "¥×¥í¥È¥³¡¼¥ëNo",
		 "°ÍÍê¼Ô",
		 "¼£¸³Ì¾",
		 "¼£¸³ÌôÌ¾",
		 "ÃùË¡",
		 "Èï¸³¼Ô¿ô(Í½»î¸³)",
		 "Èï¸³¼Ô¿ô(ËÜ»î¸³)",
		 "ÌôºÞÅêÍ¿ÎÌ",
		 array('Column' => "¼£¸³¼Â»Ü´ü´Ö(³«»Ï)",
		       'Draw' => 'date'
		       ),
		 array('Column' => "¼£¸³¼Â»Ü´ü´Ö(½ªÎ»)",
		       'Draw' => 'date'
		       ),
		 array('Column' => "Æþ±¡Æü»þ",
		       'Draw' => 'datetime'
		       ),
		 array('Column' => "ÅêÌôÆü»þ",
		       'Draw' => 'datetime'
		       ),
		 array('Column' => "Âà±¡Æü»þ",
		       'Draw' => 'datetime'
		       ),
		 "ÀÕÇ¤¼Ô",
		  ),
 'ICOLS' => array(
		 "¥×¥í¥È¥³¡¼¥ëNo",
		 "°ÍÍê¼Ô",
		 "¼£¸³Ì¾",
		 "¼£¸³ÌôÌ¾",
		 "ÃùË¡",
		 "Èï¸³¼Ô¿ô(Í½»î¸³)",
		 "Èï¸³¼Ô¿ô(ËÜ»î¸³)",
		 "ÌôºÞÅêÍ¿ÎÌ",
		 "¼£¸³¼Â»Ü´ü´Ö(³«»Ï)",
		 "¼£¸³¼Â»Ü´ü´Ö(½ªÎ»)",
		 "Æþ±¡Æü»þ",
		 "ÅêÌôÆü»þ",
		 "Âà±¡Æü»þ",
		 "ÀÕÇ¤¼Ô",
		  ),
 );

class list_of_ct_cts extends list_of_simple_objects {

	var $debug = 1;

	function list_of_ct_cts($prefix, $cfg=NULL) {
		global $_lib_u_ct_ct_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_ct_ct_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}
}

class ct_ct_display extends simple_object_display {

	var $debug = 1;

	function ct_ct_display($prefix, $cfg=NULL) {
		global $_lib_u_ct_ct_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_ct_ct_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class ct_ct_edit extends simple_object_edit {

	var $debug = 1;

	function ct_ct_edit($prefix, $cfg=NULL) {
		global $_lib_u_ct_ct_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_ct_ct_cfg;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function _validate($force=NULL) {
		$empty_to_null = array("¼£¸³¼Â»Ü´ü´Ö(³«»Ï)",);

		foreach ($empty_to_null as $col) {
			if ($this->data[$col] == "") {
				$this->data[$col] = NULL;
			}
		}

		$bad = 0;
		if (!is_null($this->data["¼£¸³¼Â»Ü´ü´Ö(³«»Ï)"])) {
			if ($st = mx_db_validate_date($this->data["¼£¸³¼Â»Ü´ü´Ö(³«»Ï)"])) {
				$this->err("(¼£¸³¼Â»Ü´ü´Ö(³«»Ï)): $st\n");
				$bad++;
			}
		}
		if ($bad == 0)
			return 'ok';
	}

  function annotate_form_data(&$data) {
    $data['CreatedBy'] = $this->u;
  }

}

?>
