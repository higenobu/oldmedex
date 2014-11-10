<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
//11-06-3014
//printml2 or printml3
//111010-2014 added shiji character varying

function _lib_u_meal_shiji() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."id" as id ,  "name" as name
    from modalities E where rtype=904
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array();
  foreach($rows as $row){
if($row['name']!=null) 
    $ret[$row['name']] = $row['name'];}
  return $ret;
}

function mk_enum2($a) {
	$r = array();
	foreach ($a as $k) {
		if (trim($k) == '') {
			$r[NULL] = '';
		} else {
			$r[$k] = $k;
		}
	}
	return $r;
}


$__lib_u_nutrition_meal_nutri_category_enum = array
(
	'¿·µ¬','ÊÑ¹¹','Ãæ»ß','³°½Ð','³°Çñ',
);

$__lib_u_nutrition_meal_nutri_addition_enum = array
(
	'','ÆÃÊÌ²Ã»»','Èó²Ã»»',
);

$__lib_u_nutrition_meal_nutri_disease_dr_enum = array
(
	'°ßÄÙáç','°ß´â','µÞÀ­Ä²±ê','¥±¥â¥Æ¥éÌÜÅª','¸¡ººÌÜÅª',
	'¹â·ì°µ','½½Æó»ØÄ²ÄÙáç','¾åÉô¾Ã²½´É½Ð·ì','ÂçÄ²´â',
	'ÃÀÀÐ¾É','ÃÀÇ¹','Ãî¿â±ê','Ä²ÊÄºÉ','ÅüÇ¢ÉÂ','ÅüÇ¢ÉÂÀ­¿Õ¾É',
	'Æý´â','Ç¢Ï©·ëÀÐ','Ê¢Ëì±ê','ËýÀ­¿ÕÉÔÁ´','ËýÀ­ç¹±ê','¤½¤ÎÂ¾',
);

$__lib_u_nutrition_meal_nutri_disease_nu_enum = array
(
	'¸¡ººÌÜÅª','¿ÕÂ¡ÉÂ','¿´Â¡¼À´µ','´ÎÂ¡ÉÂ','ÊÄºÉÀ­²«áÕ',
	'ÅüÇ¢ÉÂ','°ßÄÙáç°ß´â¡Ê½Ñ¸å3Ç¯°ÊÆâ¡Ë','ÉÏ·ì¾É¡ÊÅ´·çË³¾É¡Ë',
	'ç¹Â¡ÉÂ','¹â»éËÃ·ì¾É','¹âÅÙÈîËþ¾É','ÄÌÉ÷','ÃÀÀÐ¾É','ÃÀ¤Î¤¦±ê',
	'Ãî¿â±ê','µÞÀ­Ä²±ê','¥¤¥ì¥¦¥¹','ÂçÄ²´â','Æý´â','¥Ø¥ë¥Ë¥¢',
	'Ç¢Ï©´¶À÷¾É','¤½¤ÎÂ¾',
);

$__lib_u_nutrition_meal_nutri_meal_dr_order_enum = array
(
	'ÉáÄÌ¿©','¿ÕÂ¡¿©¡¡Æ©ÀÏ¿©','¿ÕÂ¡¿©¡¡CAPD¿©','¿ÕÂ¡¿©¡¡¿ÕÉÔÁ´ÊÝÂ¸´ü¿©',
	'´ÎÂ¡¿©','ÅüÇ¢ÉÂ¿©','°ßÄÙáç¿©','ÂçÄ²¼ê½Ñ¿©','°ß½Ñ¸å¿©',
	'Äã»éËÃ¡¡ç¹Â¡¿©','Äã»éËÃ¡¡ÃÀÀÐ¾ÉÍÑ','ÉÏ·ì¿©','¹â»é·ì¾É',
	'¸º±ö¿©','Äã»ÄÞÖ¿©','¸¡ºº¿©','Ç»¸üÎ®Æ°¿©','¥¹¥È¡¼¥Þ¿©','Óë²¼¿©',
);

$__lib_u_nutrition_meal_nutri_meal_nu_order_enum = array(NULL => '');
$db = mx_db_connect();
$stmt = <<<SQL
SELECT "ObjectID", name
FROM meal_nutrition_master
WHERE "Superseded" IS NULL AND (dont_use IS NULL OR dont_use != 'Y')
ORDER BY sort_order
SQL;
if (($sth = pg_query($db, $stmt)) &&
    ($data = pg_fetch_all($sth))) {
	foreach ($data as $v) {
		$k = $v['ObjectID'];
		$v = $v['name'];
		$__lib_u_nutrition_meal_nutri_meal_nu_order_enum[$k] = $v;
	}
}

$__lib_u_nutrition_meal_nutri_meal_staple_shape_enum = array
(
	'ÊÆÈÓ','Á´´¡','5Ê¬´¡','Î®Æ°',
);

$__lib_u_nutrition_meal_nutri_meal_side_shape_enum = array
(
	'ÉáÄÌ','¹ï¤ß',
);

$__lib_u_nutrition_meal_nutri_meal_drug_enum = array
(
	'¤½¤ÎÂ¾','¥ï¡¼¥Õ¥¡¥ê¥ó','¥«¥ë¥·¥¦¥àÙÉ¹³ºÞ','¥«¥ó¥×¥È',
);

$__lib_u_nutrition_meal_nutri_all_cols = array(
	array('Column' => 'recompute',
	      'Label' => 'ºÆ·×»»',
	      'Draw' => 'submit',
	      'Option' => array('nostore' => 1, 'nodisp' => 1)),
	array('Column' => 'patient',
	      'Draw' => NULL,
	      'Option' => array('noedit' => 1, 'nodisp' => 1),
	      ),
	array('Column' => 'recorded',
'Label' => 'µ­Ï¿', 'Draw' => 'timestamp'),
	    
	array('Column' => 'order_date',
	      'Label' => '½èÊýÆü',
	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1),
	      ),
	array('Column' => 'category',
	      'Label' => '¶èÊ¬',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_category_enum),
	      'Option' => array('list' => 1),
	      ),

array('Column' => 'shiji',
	      'Label' => '»Ø¼¨°å',
	      'Draw' => 'enum',
	      'Enum' =>_lib_u_meal_shiji(),
	      'Option' => array('validate' => 'nonnull', 'list' => 1),
	      ),


	array('Column' => 'addition',
	      'Label' => 'ÆÃÊÌ²Ã»»',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_addition_enum),
	      ),
	array('Column' => 'disease0',
	      'Label' => 'Å¬±þ¼À´µ',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_disease_dr_enum),
	      ),
	array('Column' => 'disease1',
	      'Label' => 'Å¬±þ¼À´µ',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_disease_nu_enum),
	      ),
	array('Column' => 'dr_order',
	      'Label' => '¿©»öÆâÍÆ(°å)',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_dr_order_enum),
	      'Option' => array('validate' => 'nonnull', 'list' => 1),
	      ),
	array('Column' => 'nu_order',
	      'Label' => '¿©»öÆâÍÆ(±É)',
	      'Draw' => 'enum',
	      'Enum' => $__lib_u_nutrition_meal_nutri_meal_nu_order_enum,
	      'Option' => array('validate' => 'nonnull', 'list' => 1),
	      ),
	array('Column' => 'staple_qty',
	      'Label' => '¼ç¿©ÎÌ',
	      'Draw' => 'text',
	      'Option' => array('validate' => 'number,nonnull'),
	      ),
	array('Column' => 'order_since',
	      'Label' => '¿©»ö´ü´Ö',
	      'Draw' => 'date',
	      'Option' => array('nodisp' => 1, 'validate' => 'date,nonnull'),
	      ),
	array('Column' => 'order_since1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2(array('Ä«¤«¤é','Ãë¤«¤é','Ìë¤«¤é')),
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_until1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2(array('','Âà±¡¤Þ¤Ç','ÌÀÆü¤Î¤ß','ËÜÆü¤Î¤ß')),
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_until',
	      'Draw' => 'date',
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_until2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2(array('Ä«¤Þ¤Ç','Ãë¤Þ¤Ç','Ìë¤Þ¤Ç')),
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_range',
	      'Label' => '¿©»ö´ü´Ö',
	      'Draw' => 'static',
	      'Option' => array('nostore' => 1, 'noedit' => 1, 'list' => 1),
	      ),
	array('Column' => 'energy_base',
	      'Label' => '¥¨¥Í¥ë¥®¡¼',
	      'Draw' => 'static',
	      ),
	array('Column' => 'energy_mod',
	      'Draw' => 'text',
	      'Option' => array('validate' => 'number'),
	      ),
	array('Column' => 'energy_total',
	      'Draw' => 'static',
	      'Option' => array('nostore' => 1),
	      ),
	array('Column' => 'protein_base',
	      'Label' => '¥¿¥ó¥Ñ¥¯¼Á',
	      'Draw' => 'static',
	      ),
	array('Column' => 'protein_mod',
	      'Draw' => 'text',
	      'Option' => array('validate' => 'number'),
	      ),
	array('Column' => 'protein_total',
	      'Draw' => 'static',
	      'Option' => array('nostore' => 1),
	      ),
	array('Column' => 'fat_base',
	      'Label' => '»é¼Á',
	      'Draw' => 'static',
	      ),
	array('Column' => 'fat_mod',
	      'Draw' => 'text',
	      'Option' => array('validate' => 'number'),
	      ),
	array('Column' => 'fat_total',
	      'Draw' => 'static',
	      'Option' => array('nostore' => 1),
	      ),
	array('Column' => 'salt_base',
	      'Label' => '±öÊ¬',
	      'Draw' => 'static',
	      ),
	array('Column' => 'salt_mod',
	      'Draw' => 'text',
	      'Option' => array('validate' => 'number'),
	      ),
	array('Column' => 'salt_total',
	      'Draw' => 'static',
	      'Option' => array('nostore' => 1),
	      ),
	array('Column' => 'staple_shape',
	      'Label' => '¿©»ö·ÁÂÖ(¼ç¿©)',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_staple_shape_enum),
	      ),
	array('Column' => 'side_shape',
	      'Label' => '¿©»ö·ÁÂÖ(Éû¿©)',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_side_shape_enum),
	      ),
	array('Column' => 'drug',
	      'Label' => '»ÈÍÑÌôºÞ',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_drug_enum),
	      ),
	array('Column' => 'drug_extra',
	      'Draw' => 'text',
	      ),
	array('Column' => 'special_req',
	      'Label' => '´õË¾»ö¹à',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('¤¨¿©»öäµ¥³¥á¥ó¥È'),
				'cols' => 80),
	      ),
	array('Column' => 'allergies',
	      'Label' => '¥¢¥ì¥ë¥®¡¼',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('¤¨¿©»öäµ¥¢¥ì¥ë¥®¡¼'),
				'cols' => 80),
	      ),
	array('Column' => 'notes',
	      'Label' => 'È÷¹Í',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('¤¨¿©»öäµ¥³¥á¥ó¥È'),
				'cols' => 80),
	      ),
);

$__lib_u_nutrition_meal_nutri_order_cfg = array();

$__lib_u_nutrition_meal_nutri_order_cfg['TABLE'] = 'meal_order';
$__lib_u_nutrition_meal_nutri_order_cfg[ 'DEFAULT_SORT'] = 'order_date';
 $__lib_u_nutrition_meal_nutri_order_cfg[ 'ALLOW_SORT'] = 1;
//0319-2014
$__lib_u_nutrition_meal_nutri_order_cfg['SEQUENCE'] = 'meal_order_id_seq';
$__lib_u_nutrition_meal_nutri_order_cfg['COLS'] = array();
$__lib_u_nutrition_meal_nutri_order_cfg['ICOLS'] = array();
$__lib_u_nutrition_meal_nutri_order_cfg['ECOLS'] = array();
$__lib_u_nutrition_meal_nutri_order_cfg['LCOLS'] = array();
$__lib_u_nutrition_meal_nutri_order_cfg['DCOLS'] = array();

$__lib_u_nutrition_meal_nutri_order_cfg['D_RANDOM_LAYOUT'] = array(

	array('Label' => 'µ­Ï¿¼Ô'),
	array('Insn' => 'CreatedBy', 'Span' => 2),
	array('Label' => 'µ­Ï¿'),
	array('Column' => 'recorded', 'Span' => 2),
//11-10-2014
array('Label' => '»Ø¼¨°å'),
array('Column'=>'shiji','Span' => 1),

	array('Insn' => '//'),

	array('Label' => '½èÊýÆü'),
	array('Column' => 'order_date'),
	array('Label' => '¶èÊ¬'),
	array('Column' => 'category'),
	array('Label' => 'ÆÃÊÌ²Ã»»'),
	array('Column' => 'addition', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => 'Å¬±þ¼À´µ'),
	array('Column' => 'disease0', 'Span' => 3),
	array('Label' => 'Å¬±þ¼À´µ'),
	array('Column' => 'disease1', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '¿©»öÆâÍÆ(°å)'),
	array('Column' => 'dr_order', 'Span' => 3),
	array('Label' => '¿©»öÆâÍÆ(±É)'),
	array('Column' => 'nu_order', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '¼ç¿©ÎÌ'),
	array('Column' => 'staple_qty', 'Span' => 2),
	array('Label' => 'g'),
	array('Insn' => '  ', 'Span' => 4),
	array('Insn' => '//'),

	array('Label' => '¿©»ö´ü´Ö'),
	array('Column' => 'order_range', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => '¥¨¥Í¥ë¥®¡¼'),
	array('Column' => 'energy_base', 'Span' => 2),
	array('Label' => 'Cal'),
	array('Column' => 'energy_mod'),
	array('Label' => 'Cal'),
	array('Column' => 'energy_total'),
	array('Label' => 'Cal'),
	array('Insn' => '//'),

	array('Label' => '¥¿¥ó¥Ñ¥¯¼Á'),
	array('Column' => 'protein_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'protein_mod'),
	array('Label' => 'g'),
	array('Column' => 'protein_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '»é¼Á'),
	array('Column' => 'fat_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'fat_mod'),
	array('Label' => 'g'),
	array('Column' => 'fat_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '±öÊ¬'),
	array('Column' => 'salt_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'salt_mod'),
	array('Label' => 'g'),
	array('Column' => 'salt_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '¿©»ö·ÁÂÖ', 'Rowspan' => 2),
	array('Label' => '¼ç¿©'),
	array('Column' => 'staple_shape', 'Span' => 2),
	array('Label' => '»ÈÍÑÌôºÞ', 'Rowspan' => 2),
	array('Column' => 'drug', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => 'Éû¿©'),
	array('Column' => 'side_shape', 'Span' => 2),
	array('Column' => 'drug_extra', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '´õË¾»ö¹à'),
	array('Column' => 'special_req', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => '¥¢¥ì¥ë¥®¡¼'),
	array('Column' => 'allergies', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => 'È÷¹Í'),
	array('Column' => 'notes', 'Span' => 7),
	array('Insn' => '//'),

);

$__lib_u_nutrition_meal_nutri_order_cfg['E_RANDOM_LAYOUT'] = array(

	array('Column' => 'recompute'),
	array('Insn' => '  ', 'Span' => 2),
	array('Label' => 'µ­Ï¿'),
	array('Column' => 'recorded', 'Span' => 2),
//11-10-2014
array('Label' => '»Ø¼¨°å'),
array('Column'=>'shiji','Span' => 1),
	array('Insn' => '//'),

	array('Label' => '½èÊýÆü'),
	array('Column' => 'order_date'),
	array('Label' => '¶èÊ¬'),
	array('Column' => 'category'),
	array('Label' => 'ÆÃÊÌ²Ã»»'),
	array('Column' => 'addition', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => 'Å¬±þ¼À´µ'),
	array('Column' => 'disease0', 'Span' => 3),
	array('Label' => 'Å¬±þ¼À´µ'),
	array('Column' => 'disease1', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '¿©»öÆâÍÆ(°å)'),
	array('Column' => 'dr_order', 'Span' => 3),
	array('Label' => '¿©»öÆâÍÆ(±É)'),
	array('Column' => 'nu_order', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '¼ç¿©ÎÌ'),
	array('Column' => 'staple_qty', 'Span' => 2),
	array('Label' => 'g'),
	array('Insn' => '  ', 'Span' => 4),
	array('Insn' => '//'),

	array('Label' => '¿©»ö´ü´Ö'),
	array('Column' => 'order_since', 'Span' => 2),
	array('Column' => 'order_since1'),
	array('Column' => 'order_until1'),
	array('Column' => 'order_until'),
	array('Column' => 'order_until2'),
	array('Insn' => '//'),

	array('Label' => '¥¨¥Í¥ë¥®¡¼'),
	array('Column' => 'energy_base', 'Span' => 2),
	array('Label' => 'Cal'),
	array('Column' => 'energy_mod'),
	array('Label' => 'Cal'),
	array('Column' => 'energy_total'),
	array('Label' => 'Cal'),
	array('Insn' => '//'),

	array('Label' => '¥¿¥ó¥Ñ¥¯¼Á'),
	array('Column' => 'protein_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'protein_mod'),
	array('Label' => 'g'),
	array('Column' => 'protein_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '»é¼Á'),
	array('Column' => 'fat_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'fat_mod'),
	array('Label' => 'g'),
	array('Column' => 'fat_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '±öÊ¬'),
	array('Column' => 'salt_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'salt_mod'),
	array('Label' => 'g'),
	array('Column' => 'salt_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '¿©»ö·ÁÂÖ', 'Rowspan' => 2),
	array('Label' => '¼ç¿©'),
	array('Column' => 'staple_shape', 'Span' => 2),
	array('Label' => '»ÈÍÑÌôºÞ', 'Rowspan' => 2),
	array('Column' => 'drug', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => 'Éû¿©'),
	array('Column' => 'side_shape', 'Span' => 2),
	array('Column' => 'drug_extra', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '´õË¾»ö¹à'),
	array('Column' => 'special_req', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => '¥¢¥ì¥ë¥®¡¼'),
	array('Column' => 'allergies', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => 'È÷¹Í'),
	array('Column' => 'notes', 'Span' => 7),
	array('Insn' => '//'),

);

foreach ($__lib_u_nutrition_meal_nutri_all_cols as $v) {
	$o = mx_check_option('Option', $v);
	$c = $v['Column'];

	if (!mx_check_option('nostore', $o)) {
		$__lib_u_nutrition_meal_nutri_order_cfg['COLS'][] = $c;
		$__lib_u_nutrition_meal_nutri_order_cfg['ICOLS'][] = $c;
	}
	if (mx_check_option('list', $o))
		$__lib_u_nutrition_meal_nutri_order_cfg['LCOLS'][] = $v;
	if (!mx_check_option('nodisp', $o))
		$__lib_u_nutrition_meal_nutri_order_cfg['DCOLS'][] = $v;
	if (!mx_check_option('noedit', $o))
		$__lib_u_nutrition_meal_nutri_order_cfg['ECOLS'][] = $v;
}

function __lib_u_nutrition_meal_nutri_anno(&$data)
{
	if ($data['nu_order'] && $data['staple_qty']) {
		$nu_order = mx_db_sql_quote($data['nu_order']);
		$db = mx_db_connect();
		$stmt = <<<SQL
			SELECT energy_base, protein_base, fat_base, salt_base,
			energy_mod, protein_mod, fat_mod, salt_mod
			FROM meal_nutrition_master
			WHERE "ObjectID" = $nu_order
SQL;

		if ($d = mx_db_fetch_single($db, $stmt)) {
			foreach (array('energy', 'protein', 'fat', 'salt')
				 as $k) {
				$b = ($d[$k.'_base'] +
				      $data['staple_qty'] * $d[$k.'_mod']
				      / 100.0);
				$data[$k.'_base'] = sprintf("%.2f", $b);
				$b += $data[$k.'_mod'];
				$data[$k.'_total'] = sprintf("%.2f", $b);
			}
		}
	}


	$data['order_range'] =
		sprintf("%s (%s) ¡Á %s (%s)",
			$data['order_since'], $data['order_since1'],
			(trim($data['order_until1']) == ''
			 ? $data['order_until'] : $data['order_until1']),
			$data['order_until2']);
}

class list_of_meal_nutri_orders extends list_of_ppa_objects {

	var $debug = 1;
	var $patient_column_name_quoted = 'patient';

	function list_of_meal_nutri_orders($prefix, $cfg=NULL) {
		global $__lib_u_nutrition_meal_nutri_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__lib_u_nutrition_meal_nutri_order_cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_nutrition_meal_nutri_anno(&$data);
		return list_of_ppa_objects::annotate_row_data(&$data);
	}

function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == 'order_date' ||$col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}



}


class meal_nutri_order_display extends simple_object_display {

//	var $debug = 1;
var $use_printer =1;
	function meal_nutri_order_display($prefix, $cfg=NULL) {
		global $__lib_u_nutrition_meal_nutri_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__lib_u_nutrition_meal_nutri_order_cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		__lib_u_nutrition_meal_nutri_anno(&$data);
		return simple_object_display::annotate_row_data(&$data);
	}

function print_sod($template='srl') {
    $db = mx_db_connect();

  $oid = $this->id;

// printml2.php printml3.php

    $stmt = 'SELECT "ID" from "meal_order" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
//print_r($rs);

    if(is_null($rs))
      return;

    $status = 0;
   
   
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printml3.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }


//

}

class meal_nutri_order_edit extends simple_object_ppa_edit {

//	var $debug = 1;

	var $patient_column_name = 'patient';

	function edit_tweak() {
//		$this->data['recorded_on'] = mx_today_string();
		__lib_u_nutrition_meal_nutri_anno(&$this->data);
$this->data['recorded'] = date("Y-m-d H:i:s");
	}

	function anew_tweak($orig_id) {
		if (trim($this->data['order_date']) == '')
			$this->data['order_date'] = mx_today_string();
//		$this->data['recorded_on'] = mx_today_string();
$this->data['recorded'] = date("Y-m-d H:i:s");
	}

	function annotate_form_data(&$data) {
		if ($data['nu_order'] && $data['staple_qty'])
			__lib_u_nutrition_meal_nutri_anno(&$data);
		return simple_object_ppa_edit::annotate_form_data(&$data);
	}

	function meal_nutri_order_edit($prefix, $cfg=NULL) {
		global $__lib_u_nutrition_meal_nutri_order_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__lib_u_nutrition_meal_nutri_order_cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}

	function _validate($force=NULL) {
		$bad = simple_object_ppa_edit::_validate($force) != 'ok';
		$d =& $this->data;
		if (trim($d['order_until1']) == '' &&
		    trim($d['order_until']) == '') {
			$this->err("(¿©»ö´ü´Ö)½ªÎ»Æü»ØÄê¤Ï¶õ¤Ç¤Ï¤¤¤±¤Þ¤»¤ó\n");
			$bad = 1;
		}
		if ($bad)
			return '';
		return 'ok';
	}
//insert into karte and claim
function commit($force=NULL) {

//print "commit";
    $this->data['patient'] = $this->so_config['Patient_ObjectID'];

    $db = mx_db_connect();
    $date = $this->data['order_date'];
 $patient_objectid = $this->data['patient'];
$p_oid = $this->data['patient'];
//print $o_oid;

//get master
$stmt = <<<SQL
SELECT "ObjectID", name
FROM meal_nutrition_master
WHERE "Superseded" IS NULL AND (dont_use IS NULL OR dont_use != 'Y')
ORDER BY sort_order
SQL;
if (($sth = pg_query($db, $stmt)) &&
    ($data = pg_fetch_all($sth))) {
	foreach ($data as $v) {
		$k = $v['ObjectID'];
		$v = $v['name'];
		$name[$k] = $v;
	}
}
//

//print_r($name);
$byomei=$this->data["category"];
$kaishi=$this->data['order_since'];
$tenkibi=$this->data['order_until'];
$tenki=$this->data['shiji'];

$st=$this->data['dr_order'];
$st2=$this->data['staple_shape'];
$st3=$name[$this->data['nu_order']];
$bi1=$this->data["notes"];
$bi2=$this->data["allergies"];

$ocont="----------------------------\n"."MEAL\n".'»Ø¼¨°å¡§'.$tenki.
'¥ª¡¼¥À='.$date.'³«»ÏÆü:'.$kaishi.'¤¤¤Ä¤Þ¤Ç:'.$tenkibi."\n  "."TYPE:".$byomei." "."ÆâÍÆ:".$st."  SHAPE:".$st2." NUTR:".$st3." ".$bi1." ".$bi2."\n";

//print $ocont;
 
 //use order_date
//new 10-24-2014
$stmt10 = <<<SQL
select * from "¥«¥ë¥Æ¥Ç¥âÉ½" where "ÆüÉÕ"='$date' and "´µ¼Ô"=$p_oid and 
	"Superseded" is null;
SQL;
 
//print $stmt10;

$rs0 = mx_db_fetch_all($db, $stmt10);
//print "all?".count($rs0);
if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "¥«¥ë¥Æ¥Ç¥âÉ½" ("´µ¼Ô", "ÆüÉÕ","P") values ($p_oid,'$date','$ocont');
SQL;
 
//print $stmt11;

if (pg_query($db, $stmt11)){

	}
else {
print '<p > karte insert DB access error</p>';
die;
	}

 }

else{ 

 for ($i=0;$i<count($rs0);$i++){	
 $pp=$rs0[$i]["P"];
 $idd=$rs0[$i]["ID"];
// print $pp."=";
$ocont2=$pp.'\r\n'.'-----(updated to)-----'.$ocont;


$stmt1 = <<<SQL
   	update  "¥«¥ë¥Æ¥Ç¥âÉ½" set "P"='$ocont2' where "ÆüÉÕ"='$date' and "´µ¼Ô"=$p_oid and 
	"Superseded" is null and "ID"=$idd
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > karte update DB access error</p>';
die;
}

}

} //end else

 





//
 simple_object_edit::commit($force); 
  
    

/* always claim_request */

$urgent = 0;
if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);}

$stmt = 'INSERT INTO claim_request (patient, date_since, date_until, utime, result_flag) values ('. $patient_objectid.","."'". $date."'".",". "'". $date."'".","."current_timestamp".",".$urgent.")";
//print "Claim insert";
//print $stmt;
  
	 pg_query($db, $stmt); 





     
  } //end of commit







}

//
?>
