<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/dodwell.php';

$_lib_u_manage_patient_basic_cfg = array
(
 'TABLE' => '´µ¼ÔÂæÄ¢',
 'COLS' => array("´µ¼ÔID", "À«", "Ì¾", "¥Õ¥ê¥¬¥Ê",
		 "À­ÊÌ", "Íø¤­¼ê", "À¸Ç¯·îÆü",
		 "½»½ê0", "½»½ê1",
		 "½»½ê2", "½»½ê3", "½»½ê4",
		 "²ÃÆşÅÅÏÃ",
		 "·ÈÂÓÅÅÏÃ",
		 "¥á¡¼¥ë¥¢¥É¥ì¥¹",
		 "Æş³°¶èÊ¬",
		"ÉÂ¼¼",
		 "Êİ¸±¼ÔÈÖ¹æ",
		 "ÈïÊİ¸±¼Ô",
		 "ÈïÊİ¸±¼Ô¼êÄ¢¤Îµ­¹æ",
		 "ÈïÊİ¸±¼Ô¼êÄ¢¤ÎÈÖ¹æ",
		 "¸øÈñÉéÃ´¼ÔÈÖ¹æ",
		 "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ",
		 "¸øÈñÉéÃ´¼ÔÈÖ¹æ2",
		 "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ2",
		 "¸øÈñÉéÃ´¼ÔÈÖ¹æ3",
		 "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3",

		 "È¯¾ÉÆü", "Æş±¡Æü", "Âà±¡Í½ÄêÆü", "Âà±¡Í½Äê¡¦¸«¹ş",
		 "»àË´Æü", "È÷¹Í",
		 "²óÉü´ü", "°å³ØÅªÉÔ°ÂÄê", "´õË¾ÉÂÅï", "Æş±¡²Ê", "´µ¼Ô¥Ş¡¼¥¯",
		 "·ì±Õ·¿£Á£Â£Ï¼°", "·ì±Õ·¿£Ò£è¼°",
		 "£È£Â£ó¹³¸¶",
		 "¥¢¥ì¥ë¥®¡¼",
		 "´¶À÷¾É",
		 "Æ©ÀÏ´µ¼Ô¥Õ¥é¥°",
		 "¶ĞÌ³ÀèÌ¾", "¶ĞÌ³ÀèÍ¹ÊØÈÖ¹æ", "¶ĞÌ³Àè½»½ê",
		 "¶ĞÌ³ÀèÅÅÏÃÈÖ¹æ",
		 "ÀÁµáÀèÌ¾", "ÀÁµáÀèÍ¹ÊØÈÖ¹æ", "ÀÁµáÀè½»½ê",
		 "ÀÁµáÀèÅÅÏÃÈÖ¹æ"),

 'ENABLE_QBE' => array(array('Column' => '´µ¼ÔID',
			     'Singleton' => 1,
			     'CompareMethod' => 'zeropad_exact',
			     'ZeroPad' => $_mx_patient_id_zeropad,
			     ),
		       "À«",
		       "Ì¾",
		       "¥Õ¥ê¥¬¥Ê",
		       '//',
		       array('Column' => "À­ÊÌ", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('M' => 'ÃË', 'F' => '½÷',
					     '' => '(¤É¤Á¤é¤Ç¤â)')),
		       "À¸Ç¯·îÆü",
		       array('Column' => "Æş³°¶èÊ¬", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => array('I' => 'Æş±¡', 'O' => '³°Íè',
					     'E' => 'Æş±¡È½ÄêÂĞ¾İ',
					     'W' => 'Æş±¡ÂÔ¤Á',
					     '' => '(Ì¤ÀßÄê)') ),
		       array('Column' => "´µ¼Ô¥Ş¡¼¥¯", 'Draw' => 'enum',
			     'Singleton' => 1,
			     'Enum' => 'lazy'),
		       ),

 'LCOLS' => array("´µ¼ÔID", "À«", "Ì¾", "¥Õ¥ê¥¬¥Ê", "À­ÊÌ", "À¸Ç¯·îÆü","ÉÂ¼¼"),
 'ALLOW_SORT' => 1,
 'LLAYO' => array('´µ¼ÔID', "À«", "Ì¾", "¥Õ¥ê¥¬¥Ê",
		  '//',
		  array('Column' => "À­ÊÌ",
			'Draw' => 'enum',
			'Enum' => array('M' => 'ÃË', 'F' => '½÷',
					NULL => 'ÉÔÌÀ') ),
		  "À¸Ç¯·îÆü",
		  array('Column' => "Æş³°¶èÊ¬", 'Draw' => 'enum',
			'Singleton' => 1,
			'Enum' => array('I' => 'Æş±¡', 'O' => '³°Íè',
					'E' => 'Æş±¡È½ÄêÂĞ¾İ',
					'W' => 'Æş±¡ÂÔ¤Á',
					'' => '(Ì¤ÀßÄê)') ),
		  "´µ¼Ô¥Ş¡¼¥¯",
		  ),
 'DCOLS' => array("´µ¼ÔID", "À«", "Ì¾", "¥Õ¥ê¥¬¥Ê",
		  array('Column' => "À­ÊÌ",
			'Draw' => 'enum',
			'Enum' => array('M' => 'ÃË', 'F' => '½÷',
					NULL => 'ÉÔÌÀ') ),
		  "À¸Ç¯·îÆü",
		  array('Column' => "½»½ê0", 'Label' => "¢©"),
		  array('Column' => "½»½ê1", 'Label' => "ÅÔÆ»ÉÜ¸©"),
		  array('Column' => "½»½ê2", 'Label' => "»ÔÄ®Â¼"),
		  array('Column' => "½»½ê3", 'Label' => "ÈÖÃÏ"),
		  array('Column' => "½»½ê4", 'Label' => "¼¼ÈÖ"),
		  "²ÃÆşÅÅÏÃ",
		  "·ÈÂÓÅÅÏÃ",
		  "¥á¡¼¥ë¥¢¥É¥ì¥¹",
		  array('Column' => "Æş³°¶èÊ¬",
			'Draw' => 'enum',
			'Enum' => array('I' => 'Æş±¡', 'O' => '³°Íè',
					'E' => 'Æş±¡È½ÄêÂĞ¾İ',
					'W' => 'Æş±¡ÂÔ¤Á',
					NULL => 'Ì¤»ØÄê') ),

		  "Êİ¸±¼ÔÈÖ¹æ",
		  array('Column' => "ÈïÊİ¸±¼Ô",
			'Draw' => 'enum',
			'Enum' => array('1' => 'ËÜ¿Í', '2' => '²ÈÂ²',
					NULL => 'Ì¤»ØÄê') ),
		  "ÈïÊİ¸±¼Ô¼êÄ¢¤Îµ­¹æ",
		  "ÈïÊİ¸±¼Ô¼êÄ¢¤ÎÈÖ¹æ",
		  "¸øÈñÉéÃ´¼ÔÈÖ¹æ",
		  "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ",
		  "¸øÈñÉéÃ´¼ÔÈÖ¹æ2",
		  "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ2",
		  "¸øÈñÉéÃ´¼ÔÈÖ¹æ3",
		  "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3",

		  "È¯¾ÉÆü", "Æş±¡Æü", "Âà±¡Í½ÄêÆü",
		  array('Column' => "Âà±¡Í½Äê¡¦¸«¹ş",
			'Draw' => 'enum',
			'Enum' => array('F' => 'Í½Äê', 'E' => '¸«¹ş',
					'N/A' => 'N/A', 
					NULL => 'Ì¤»ØÄê') ),
		  array('Column' => "´õË¾ÉÂÅï",
			'Label' => "´µ¼Ô¥°¥ë¡¼¥×"),
		  "Æş±¡²Ê",
		  "´µ¼Ô¥Ş¡¼¥¯",
		  "»àË´Æü", "È÷¹Í",
		  array('Column' => "²óÉü´ü",
			'Draw' => 'enum',
			'Enum' => array('A' => 'ÂĞ¾İ A',
					'B' => 'ÂĞ¾İ B',
					'C' => 'ÂĞ¾İ C',
					'D' => 'ÂĞ¾İ D',
					'E' => 'ÂĞ¾İ E',
					'Z' => 'ÂĞ¾İ³°',
					NULL => '(Ì¤ÀßÄê)')),
		  array('Column' => "°å³ØÅªÉÔ°ÂÄê",
			'Draw' => 'enum',
			'Enum' => array('S' => '°ÂÄê', 'U' => 'ÉÔ°ÂÄê',
					'W' => 'Í×Ãí°Õ',
					NULL => '(Ì¤ÀßÄê)')),
		  "·ì±Õ·¿£Á£Â£Ï¼°", "·ì±Õ·¿£Ò£è¼°",
		  "£È£Â£ó¹³¸¶",
		  "¥¢¥ì¥ë¥®¡¼",
		  "´¶À÷¾É",
		  "Æ©ÀÏ´µ¼Ô¥Õ¥é¥°",
		  "¶ĞÌ³ÀèÌ¾", "¶ĞÌ³ÀèÍ¹ÊØÈÖ¹æ", "¶ĞÌ³Àè½»½ê",
		  "¶ĞÌ³ÀèÅÅÏÃÈÖ¹æ",
		  "ÀÁµáÀèÌ¾", "ÀÁµáÀèÍ¹ÊØÈÖ¹æ", "ÀÁµáÀè½»½ê",
		  "ÀÁµáÀèÅÅÏÃÈÖ¹æ",
			"ÉÂ¼¼",
		  array('Column' => 'CreatedBy',
			'Label' => 'µ­Ï¿¼Ô',
			'Draw' => 'user'),
		  ),

 'ECOLS' => array(array('Column' => "´µ¼ÔID",
			'Option' => array('ime' => 'disabled',
					  'zeropad' => $_mx_patient_id_zeropad,
					  'validate' => 'nonnull')),
		  array('Column' => "À«",
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "Ì¾",
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "¥Õ¥ê¥¬¥Ê",
			'Option' => array('validate' => 'nonnull')),
		  array('Column' => "À­ÊÌ",
			'Draw' => 'enum',
			'Enum' => array('M' => 'ÃË', 'F' => '½÷',
					NULL => 'ÉÔÌÀ') ),
		  array('Column' => "À¸Ç¯·îÆü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "½»½ê0",
			'Label' => "¢©",
			'Draw' => 'post_code',
			'Option' => array('ime' => 'disabled',
					  'zip' => '½»½ê0',
					  'prefecture' => '½»½ê1',
					  'city' => '½»½ê2',
					  'block' => '½»½ê3',
					  'add_id' => 1,
					  )),
		  array('Column' => "½»½ê1", 'Label' => "ÅÔÆ»ÉÜ¸©", 'Option' => array('add_id' => 1)),
		  array('Column' => "½»½ê2", 'Label' => "»ÔÄ®Â¼", 'Option' => array('add_id' => 1)),
		  array('Column' => "½»½ê3", 'Label' => "ÈÖÃÏ", 'Option' => array('add_id' => 1)),
		  array('Column' => "½»½ê4", 'Label' => "¼¼ÈÖ", 'Option' => array('add_id' => 1)),
		  array('Column' => "²ÃÆşÅÅÏÃ",
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "·ÈÂÓÅÅÏÃ",
			'Option' => array('ime' => 'disabled')),
		  array('Column' => "¥á¡¼¥ë¥¢¥É¥ì¥¹",
			'Draw' => 'text',
			'Option' => array('size' => 80)),
		  array('Column' => "Æş³°¶èÊ¬",
			'Draw' => 'enum',
			'Enum' => array('I' => 'Æş±¡', 'O' => '³°Íè',
					'E' => 'Æş±¡È½ÄêÂĞ¾İ',
					'W' => 'Æş±¡ÂÔ¤Á',
					NULL => 'Ì¤»ØÄê') ),

		  array('Column' => "Êİ¸±¼ÔÈÖ¹æ",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "ÈïÊİ¸±¼Ô",
			'Draw' => 'enum',
			'Enum' => array('1' => 'ËÜ¿Í', '2' => '²ÈÂ²',
					NULL => 'Ì¤»ØÄê') ),
		  array('Column' => "ÈïÊİ¸±¼Ô¼êÄ¢¤Îµ­¹æ",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "ÈïÊİ¸±¼Ô¼êÄ¢¤ÎÈÖ¹æ",
			'Option' => array('validate' => 'len',
					  'validate-maxlen' => 15)),
		  array('Column' => "¸øÈñÉéÃ´¼ÔÈÖ¹æ",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "¸øÈñÉéÃ´¼ÔÈÖ¹æ2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ2",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),
		  array('Column' => "¸øÈñÉéÃ´¼ÔÈÖ¹æ3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 8)),
		  array('Column' => "¸øÈñÉéÃ´°åÎÅ¤Î¼õµë¼ÔÈÖ¹æ3",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'len,digits',
					  'validate-maxlen' => 7)),

		  array('Column' => "È¯¾ÉÆü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "Æş±¡Æü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "Âà±¡Í½ÄêÆü",
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date')),
		  array('Column' => "Âà±¡Í½Äê¡¦¸«¹ş",
			'Draw' => 'enum',
			'Enum' => array('F' => 'Í½Äê', 'E' => '¸«¹ş',
					NULL => 'Ì¤»ØÄê') ),
		  array('Column' => "´õË¾ÉÂÅï",
			'Label' => "´µ¼Ô¥°¥ë¡¼¥×",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  array('Column' => "´µ¼Ô¥Ş¡¼¥¯",
			'Draw' => 'enum',
			'Enum' => 'lazy'),
		  "Æş±¡²Ê",
		  array('Column' => "»àË´Æü",
			'Option' => array('ime' => 'disabled')),
		  "È÷¹Í",
		  array('Column' => "²óÉü´ü",
			'Draw' => 'enum',
			'Enum' => array('A' => 'ÂĞ¾İ A',
					'B' => 'ÂĞ¾İ B',
					'C' => 'ÂĞ¾İ C',
					'D' => 'ÂĞ¾İ D',
					'E' => 'ÂĞ¾İ E',
					'Z' => 'ÂĞ¾İ³°',
					NULL => '(Ì¤ÀßÄê)')),
		  array('Column' => "°å³ØÅªÉÔ°ÂÄê",
			'Draw' => 'enum',
			'Enum' => array('S' => '°ÂÄê', 'U' => 'ÉÔ°ÂÄê',
					'W' => 'Í×Ãí°Õ',
					NULL => '(Ì¤ÀßÄê)')),
		  array('Column' => "·ì±Õ·¿£Á£Â£Ï¼°",
			'Draw' => 'enum',
			'Enum' => array('A' => 'A', 'B' => 'B',
					'AB' => 'AB', 'O' => 'O',
					NULL => 'ÉÔÌÀ')),
		  array('Column' => "·ì±Õ·¿£Ò£è¼°",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => 'ÉÔÌÀ')),
		  array('Column' => "£È£Â£ó¹³¸¶",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => 'ÉÔÌÀ')),
		  "¥¢¥ì¥ë¥®¡¼",
		  "´¶À÷¾É",
		  array('Column' => "Æ©ÀÏ´µ¼Ô¥Õ¥é¥°",
			'Draw' => 'enum',
			'Enum' => array('+' => '+', '-' => '-',
					NULL => 'ÉÔÌÀ')),
		  "¶ĞÌ³ÀèÌ¾",
		  array('Column' => "¶ĞÌ³ÀèÍ¹ÊØÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		  "¶ĞÌ³Àè½»½ê",
		  array('Column' => "¶ĞÌ³ÀèÅÅÏÃÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		  "ÀÁµáÀèÌ¾",
		  array('Column' => "ÀÁµáÀèÍ¹ÊØÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		  "ÀÁµáÀè½»½ê",
		  array('Column' => "ÀÁµáÀèÅÅÏÃÈÖ¹æ",
			'Option' => array('ime' => 'disabled')),
		array('Column' => "ÉÂ¼¼",
			),
		  ),
 );
$_lib_u_manage_patient_basic_cfg['HSTMT'] =
('SELECT "ObjectID", "CreatedBy", ' .
 implode(', ', array_map('mx_db_sql_quote_name',
			 $_lib_u_manage_patient_basic_cfg['COLS'])) .
 ', ("À«" || "Ì¾") AS "À«Ì¾"' .
 ' FROM ' . mx_db_sql_quote_name($_lib_u_manage_patient_basic_cfg['TABLE']) .
 ' WHERE (NULL IS NULL) ');

function _lib_u_manage_patient_basic_cfg_add_page_num($ary, &$map) {
	global $_lib_u_manage_patient_basic_cfg;

	$in = $_lib_u_manage_patient_basic_cfg[$ary];
	$out = array();
	foreach ($in as $data) {
		if (!is_array($data)) {
			$name = $data;
		}
		else {
			$name = $data['Column'];
		}
		if (array_key_exists($name, $map)) {
			if (!is_array($data)) {
				$data = array('Column' => $data,
					      'Label' => $data,
					      'Draw' => 'text');
			}
			$data['Page'] = $map[$name];
		}
		$out[] = $data;
	}
	$_lib_u_manage_patient_basic_cfg[$ary] = $out;
}

if ($_mx_cheap_layout) {
	$all_page_numcol = 3;
	$first_page_numcol = 25;
	$page = array();
	for ($i = 0;
	     $i < count($_lib_u_manage_patient_basic_cfg['COLS']);
	     $i++) {
		$name = $_lib_u_manage_patient_basic_cfg['COLS'][$i];
		if ($i < $all_page_numcol)
			;
		else if ($i < $first_page_numcol)
			$page[$name] = 0;
		else
			$page[$name] = 1;
	}
	_lib_u_manage_patient_basic_cfg_add_page_num('DCOLS', &$page);
	_lib_u_manage_patient_basic_cfg_add_page_num('ECOLS', &$page);
	$_lib_u_manage_patient_basic_cfg['DPAGES'] = array('(1)', '(2)');
	$_lib_u_manage_patient_basic_cfg['EPAGES'] = array('(1)', '(2)');
}

class list_of_patient_basics extends list_of_simple_objects {
  function list_of_patient_basics($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '´µ¼Ô¥Ş¡¼¥¯') {
		  return mx_dbenum_patientmark('=');
	  }
	  return $desc['Enum'];
  }

}

class patient_basic_display extends simple_object_display {
  function patient_basic_display($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    simple_object_display::simple_object_display($prefix, $cfg);
  }
  
  function annotate_row_data(&$data) {
    if ($data["Æş³°¶èÊ¬"] == 'O') {
      $data["Âà±¡Í½ÄêÆü"] = 'N/A';
      $data["Âà±¡Í½Äê¡¦¸«¹ş"] = 'N/A';
    }
  }

  function issue_card() {
    $data = $this->prepare_data_for_draw();
    $dodwell = new Dodwell($data);
    if(! $dodwell->write_csv()) {
      print implode('<br>', $dodwell->error);
    }
  }
}

class patient_basic_edit extends simple_object_edit {
  function patient_basic_edit($prefix, $cfg=array()) {
    global $_lib_u_manage_patient_basic_cfg;
    $cfg = array_merge($_lib_u_manage_patient_basic_cfg, $cfg);
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function enum_list($desc) {
	  if ($desc['Column'] == '´õË¾ÉÂÅï')
		  return mx_dbenum_patientgroup();
	  if ($desc['Column'] == '´µ¼Ô¥Ş¡¼¥¯')
		  return mx_dbenum_patientmark();
	  return $desc['Enum'];
  }

  function _validate() {
    global $_lib_u_manage_patient_basic_cfg;

    $d =& $this->data;

    $bad = 0;
    // Ugh.
    if (! $this->id) {
      $db = mx_db_connect();
      $r = mx_db_fetch_single($db, 'SELECT "ObjectID" FROM "´µ¼ÔÂæÄ¢"
WHERE "´µ¼ÔID" = ' . mx_db_sql_quote($d['´µ¼ÔID']));
      if (is_array($r) || is_null($r)) {
	$this->err("(´µ¼ÔID): »ØÄê¤µ¤ì¤¿ÃÍ¤Ï¤¹¤Ç¤Ë»È¤ï¤ì¤Æ¤¤¤Ş¤¹¡£\n");
	$bad++;
      }
    }

    if (simple_object_edit::_validate() != 'ok')
	    $bad++;

    if (($d['Æş³°¶èÊ¬'] == 'I') || ($d['Æş³°¶èÊ¬'] == 'W')) {
	    foreach (array('Æş±¡Æü', 'Âà±¡Í½ÄêÆü') as $c) {
		    if ($st = mx_db_validate_date($d[$c])) {
			    $this->err("($c): $st\n");
			    $bad++;
		    }
	    }
    }
    if (! $bad)
      return 'ok';
  }

  function trim_data(&$row) {
    foreach ($row as $k => $v) {
      if (! is_null($v))
	$row[$k] = trim($v);
    }
  }

  function annotate_row_data(&$data) { $this->trim_data(&$data); }
  function annotate_form_data(&$data) {
	  foreach (array('À«','Ì¾','¥Õ¥ê¥¬¥Ê') as $c) {
		  if (is_null($data[$c]))
			  continue;
		  $data[$c] = trim(mx_xlate_jzspace($data[$c]));
	  }
	  $fn = $data['À«'];
	  $gn = $data['Ì¾'];

	  if ($gn == '' && $fn != '') {
		  $m = array();
		  if (preg_match('/^(.*?) +(.*)$/', $fn, &$m)) {
			  $data['À«'] = $m[1];
			  $data['Ì¾'] = $m[2];
		  }
	  }
    simple_object_edit::annotate_form_data(&$data);
    $this->trim_data(&$data);
  }

  function allocate_new_patient_id() {
	  global $_mx_patient_id_zeropad, $_mx_auto_allocate_patient_id;

	  if (!$_mx_auto_allocate_patient_id)
		  return NULL;
	  $m = array();
	  if (preg_match('/^(.+),(.+)$/', $_mx_auto_allocate_patient_id,
			 &$m)) {
		  $bottom = $m[1];
		  $top = $m[2];
		  if (0 < $_mx_patient_id_zeropad)
			  $top = mx_zeropad($top, $_mx_patient_id_zeropad);
		  $tsql = '"´µ¼ÔID" <= ' . mx_db_sql_quote($top) . ' AND ';
	  }
	  else {
		  $bottom = $_mx_auto_allocate_patient_id;
		  $top = undef;
		  $tsql = '';
	  }

	  $db = mx_db_connect();
	  $stmt = 'SELECT max("´µ¼ÔID") AS "I" FROM "´µ¼ÔÂæÄ¢" WHERE ' .
		  $tsql .
		  '"Superseded" IS NULL';
	  if($_mx_patient_id_zeropad > 0)
	    $stmt .= ' AND length("´µ¼ÔID") = ' . $_mx_patient_id_zeropad;

	  $curr = mx_db_fetch_single($db, $stmt);

	  if (is_null($curr))
		  $curr = $_mx_auto_allocate_patient_id;
	  else {
		  /*
		   * NEEDSWORK: this should be
		   * $curr = mz_un_zeropad($curr['I']) + 1
		   */
		  $curr = intval($curr['I']) + 1;
	  }

	  if (0 < $_mx_patient_id_zeropad)
		  $curr = mx_zeropad($curr, $_mx_patient_id_zeropad);
	  return $curr;
  }

  function anew_tweak($orig_id) {
	  global $_mx_config_pt_class_default;

	  $this->data['´µ¼ÔID'] = $this->allocate_new_patient_id();
	  $this->data["Æş³°¶èÊ¬"] = $_mx_config_pt_class_default;
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    if ($this->_validate() != 'ok')
      return 'failure';

    $orig_id = $this->id;
    if (($ok = simple_object_edit::try_commit(&$db)) != 'ok')
      return $ok;
    if (! $orig_id) {
      // We created a patient.  Need to create the associated
      // Patient-Employee association hook, if it does not exist.
      $stmt = 'SELECT "ObjectID" FROM "´µ¼ÔÃ´Åö¿¦°÷" WHERE
               "Superseded" IS NULL AND "´µ¼Ô" = ' . $this->id;
      $curr = mx_db_fetch_single($db, $stmt);
      $stmt = 'INSERT INTO "´µ¼ÔÃ´Åö¿¦°÷" ("´µ¼Ô", "CreatedBy") VALUES (' .
	$this->id . ', ' .
	mx_db_sql_quote($mx_authenticate_current_user) . ')';
      if (! $curr && ! is_null($curr) && pg_query($db, $stmt))
	; // All is well.
      else
	return 'failure';
    }
    if ($this->id &&
	($this->data['Æş³°¶èÊ¬'] != 'I') &&
	($this->data['Æş³°¶èÊ¬'] != 'W') &&
	!mx_db_validate_date($this->data['Âà±¡Í½ÄêÆü'])) {
	    $dis = sprintf("'%s'", $this->data['Âà±¡Í½ÄêÆü']);
	    $stmt = sprintf(
		    'DELETE FROM "ÉÂ¾²´ÉÍı"
		    WHERE "ÀêÍ­¼Ô" = %d AND (%s <= "ÀêÍ­³«»Ï")',
		    $this->id, $dis);
	    pg_query($db, $stmt);
	    $stmt = sprintf(
		    'UPDATE "ÉÂ¾²´ÉÍı" SET "ÀêÍ­½ªÎ»" = %s
		     WHERE "ÀêÍ­¼Ô" = %d AND
		     ("ÀêÍ­³«»Ï" <= %s) AND (%s < "ÀêÍ­½ªÎ»")',
		    $dis, $this->id, $dis, $dis);
	    pg_query($db, $stmt);
    }
    return 'ok';
  }

}
?>
