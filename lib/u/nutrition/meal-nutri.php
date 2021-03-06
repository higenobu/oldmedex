<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
//11-2-2014
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
	'新規','変更','中止','外出','外泊',
);

$__lib_u_nutrition_meal_nutri_addition_enum = array
(
	'','特別加算','非加算',
);

$__lib_u_nutrition_meal_nutri_disease_dr_enum = array
(
	'胃潰瘍','胃癌','急性腸炎','ケモテラ目的','検査目的',
	'高血圧','十二指腸潰瘍','上部消化管出血','大腸癌',
	'胆石症','胆嚢','虫垂炎','腸閉塞','糖尿病','糖尿病性腎症',
	'乳癌','尿路結石','腹膜炎','慢性腎不全','慢性膵炎','その他',
);

$__lib_u_nutrition_meal_nutri_disease_nu_enum = array
(
	'検査目的','腎臓病','心臓疾患','肝臓病','閉塞性黄疸',
	'糖尿病','胃潰瘍胃癌（術後3年以内）','貧血症（鉄欠乏症）',
	'膵臓病','高脂肪血症','高度肥満症','通風','胆石症','胆のう炎',
	'虫垂炎','急性腸炎','イレウス','大腸癌','乳癌','ヘルニア',
	'尿路感染症','その他',
);

$__lib_u_nutrition_meal_nutri_meal_dr_order_enum = array
(
	'普通食','腎臓食　透析食','腎臓食　CAPD食','腎臓食　腎不全保存期食',
	'肝臓食','糖尿病食','胃潰瘍食','大腸手術食','胃術後食',
	'低脂肪　膵臓食','低脂肪　胆石症用','貧血食','高脂血症',
	'減塩食','低残渣食','検査食','濃厚流動食','ストーマ食','嚥下食',
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
	'米飯','全粥','5分粥','流動',
);

$__lib_u_nutrition_meal_nutri_meal_side_shape_enum = array
(
	'普通','刻み',
);

$__lib_u_nutrition_meal_nutri_meal_drug_enum = array
(
	'その他','ワーファリン','カルシウム拮抗剤','カンプト',
);

$__lib_u_nutrition_meal_nutri_all_cols = array(
	array('Column' => 'recompute',
	      'Label' => '再計算',
	      'Draw' => 'submit',
	      'Option' => array('nostore' => 1, 'nodisp' => 1)),
	array('Column' => 'patient',
	      'Draw' => NULL,
	      'Option' => array('noedit' => 1, 'nodisp' => 1),
	      ),
	
	array('Column' => 'order_date',
	      'Label' => '処方日',
	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1),
	      ),
	array('Column' => 'category',
	      'Label' => '区分',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_category_enum),
	      'Option' => array('list' => 1),
	      ),
	array('Column' => 'addition',
	      'Label' => '特別加算',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_addition_enum),
	      ),
	array('Column' => 'disease0',
	      'Label' => '適応疾患',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_disease_dr_enum),
	      ),
	array('Column' => 'disease1',
	      'Label' => '適応疾患',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_disease_nu_enum),
	      ),
	array('Column' => 'dr_order',
	      'Label' => '食事内容(医)',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_dr_order_enum),
	      'Option' => array('validate' => 'nonnull', 'list' => 1),
	      ),
	array('Column' => 'nu_order',
	      'Label' => '食事内容(栄)',
	      'Draw' => 'enum',
	      'Enum' => $__lib_u_nutrition_meal_nutri_meal_nu_order_enum,
	      'Option' => array('validate' => 'nonnull', 'list' => 1),
	      ),
	array('Column' => 'staple_qty',
	      'Label' => '主食量',
	      'Draw' => 'text',
	      'Option' => array('validate' => 'number,nonnull'),
	      ),
	array('Column' => 'order_since',
	      'Label' => '食事期間',
	      'Draw' => 'date',
	      'Option' => array('nodisp' => 1, 'validate' => 'date,nonnull'),
	      ),
	array('Column' => 'order_since1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2(array('朝から','昼から','夜から')),
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_until1',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2(array('','退院まで','明日のみ','本日のみ')),
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_until',
	      'Draw' => 'date',
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_until2',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2(array('朝まで','昼まで','夜まで')),
	      'Option' => array('nodisp' => 1),
	      ),
	array('Column' => 'order_range',
	      'Label' => '食事期間',
	      'Draw' => 'static',
	      'Option' => array('nostore' => 1, 'noedit' => 1, 'list' => 1),
	      ),
	array('Column' => 'energy_base',
	      'Label' => 'エネルギー',
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
	      'Label' => 'タンパク質',
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
	      'Label' => '脂質',
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
	      'Label' => '塩分',
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
	      'Label' => '食事形態(主食)',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_staple_shape_enum),
	      ),
	array('Column' => 'side_shape',
	      'Label' => '食事形態(副食)',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_side_shape_enum),
	      ),
	array('Column' => 'drug',
	      'Label' => '使用薬剤',
	      'Draw' => 'enum',
	      'Enum' => mk_enum2($__lib_u_nutrition_meal_nutri_meal_drug_enum),
	      ),
	array('Column' => 'drug_extra',
	      'Draw' => 'text',
	      ),
	array('Column' => 'special_req',
	      'Label' => '希望事項',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('え食事箋コメント'),
				'cols' => 80),
	      ),
	array('Column' => 'allergies',
	      'Label' => 'アレルギー',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('え食事箋アレルギー'),
				'cols' => 80),
	      ),
	array('Column' => 'notes',
	      'Label' => '備考',
	      'Draw' => 'textarea',
	      'Option' => array('vocab' => array('え食事箋コメント'),
				'cols' => 80),
	      ),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp'),
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

	array('Label' => '記録者'),
	array('Insn' => 'CreatedBy', 'Span' => 3),
	array('Label' => '記録'),
	array('Column' => 'recorded', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '処方日'),
	array('Column' => 'order_date'),
	array('Label' => '区分'),
	array('Column' => 'category'),
	array('Label' => '特別加算'),
	array('Column' => 'addition', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '適応疾患'),
	array('Column' => 'disease0', 'Span' => 3),
	array('Label' => '適応疾患'),
	array('Column' => 'disease1', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '食事内容(医)'),
	array('Column' => 'dr_order', 'Span' => 3),
	array('Label' => '食事内容(栄)'),
	array('Column' => 'nu_order', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '主食量'),
	array('Column' => 'staple_qty', 'Span' => 2),
	array('Label' => 'g'),
	array('Insn' => '  ', 'Span' => 4),
	array('Insn' => '//'),

	array('Label' => '食事期間'),
	array('Column' => 'order_range', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => 'エネルギー'),
	array('Column' => 'energy_base', 'Span' => 2),
	array('Label' => 'Cal'),
	array('Column' => 'energy_mod'),
	array('Label' => 'Cal'),
	array('Column' => 'energy_total'),
	array('Label' => 'Cal'),
	array('Insn' => '//'),

	array('Label' => 'タンパク質'),
	array('Column' => 'protein_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'protein_mod'),
	array('Label' => 'g'),
	array('Column' => 'protein_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '脂質'),
	array('Column' => 'fat_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'fat_mod'),
	array('Label' => 'g'),
	array('Column' => 'fat_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '塩分'),
	array('Column' => 'salt_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'salt_mod'),
	array('Label' => 'g'),
	array('Column' => 'salt_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '食事形態', 'Rowspan' => 2),
	array('Label' => '主食'),
	array('Column' => 'staple_shape', 'Span' => 2),
	array('Label' => '使用薬剤', 'Rowspan' => 2),
	array('Column' => 'drug', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '副食'),
	array('Column' => 'side_shape', 'Span' => 2),
	array('Column' => 'drug_extra', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '希望事項'),
	array('Column' => 'special_req', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => 'アレルギー'),
	array('Column' => 'allergies', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => '備考'),
	array('Column' => 'notes', 'Span' => 7),
	array('Insn' => '//'),

);

$__lib_u_nutrition_meal_nutri_order_cfg['E_RANDOM_LAYOUT'] = array(

	array('Column' => 'recompute'),
	array('Insn' => '  ', 'Span' => 3),
	array('Label' => '記録'),
	array('Column' => 'recorded', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '処方日'),
	array('Column' => 'order_date'),
	array('Label' => '区分'),
	array('Column' => 'category'),
	array('Label' => '特別加算'),
	array('Column' => 'addition', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '適応疾患'),
	array('Column' => 'disease0', 'Span' => 3),
	array('Label' => '適応疾患'),
	array('Column' => 'disease1', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '食事内容(医)'),
	array('Column' => 'dr_order', 'Span' => 3),
	array('Label' => '食事内容(栄)'),
	array('Column' => 'nu_order', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '主食量'),
	array('Column' => 'staple_qty', 'Span' => 2),
	array('Label' => 'g'),
	array('Insn' => '  ', 'Span' => 4),
	array('Insn' => '//'),

	array('Label' => '食事期間'),
	array('Column' => 'order_since', 'Span' => 2),
	array('Column' => 'order_since1'),
	array('Column' => 'order_until1'),
	array('Column' => 'order_until'),
	array('Column' => 'order_until2'),
	array('Insn' => '//'),

	array('Label' => 'エネルギー'),
	array('Column' => 'energy_base', 'Span' => 2),
	array('Label' => 'Cal'),
	array('Column' => 'energy_mod'),
	array('Label' => 'Cal'),
	array('Column' => 'energy_total'),
	array('Label' => 'Cal'),
	array('Insn' => '//'),

	array('Label' => 'タンパク質'),
	array('Column' => 'protein_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'protein_mod'),
	array('Label' => 'g'),
	array('Column' => 'protein_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '脂質'),
	array('Column' => 'fat_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'fat_mod'),
	array('Label' => 'g'),
	array('Column' => 'fat_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '塩分'),
	array('Column' => 'salt_base', 'Span' => 2),
	array('Label' => 'g'),
	array('Column' => 'salt_mod'),
	array('Label' => 'g'),
	array('Column' => 'salt_total'),
	array('Label' => 'g'),
	array('Insn' => '//'),

	array('Label' => '食事形態', 'Rowspan' => 2),
	array('Label' => '主食'),
	array('Column' => 'staple_shape', 'Span' => 2),
	array('Label' => '使用薬剤', 'Rowspan' => 2),
	array('Column' => 'drug', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '副食'),
	array('Column' => 'side_shape', 'Span' => 2),
	array('Column' => 'drug_extra', 'Span' => 3),
	array('Insn' => '//'),

	array('Label' => '希望事項'),
	array('Column' => 'special_req', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => 'アレルギー'),
	array('Column' => 'allergies', 'Span' => 7),
	array('Insn' => '//'),

	array('Label' => '備考'),
	array('Column' => 'notes', 'Span' => 7),
	array('Insn' => '//'),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp'),

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
		sprintf("%s (%s) 〜 %s (%s)",
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

//printmeal.php?  printnml2.php

    $stmt = 'SELECT "ID" from "meal_order" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
//print_r($rs);

    if(is_null($rs))
      return;

    $status = 0;
   
    
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("printml2.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
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
		$this->data['recorded_on'] = mx_today_string();
		__lib_u_nutrition_meal_nutri_anno(&$this->data);
		$this->data['recorded'] =  date("Y-m-d H:i:s");
 		
	}

	function anew_tweak($orig_id) {
		if (trim($this->data['order_date']) == '')
			$this->data['order_date'] = mx_today_string();
		$this->data['recorded_on'] = mx_today_string();
		$this->data['recorded'] =  date("Y-m-d H:i:s");
 		
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
			$this->err("(食事期間)終了日指定は空ではいけません\n");
			$bad = 1;
		}
		if ($bad)
			return '';
		return 'ok';
	}
//11-02-2014
function commit($force=NULL) {


	$p_oid=$this->so_config['Patient_ObjectID']; 
//
	$date = $this->data["order_date"];

	$nuord=$this->data["nu_order"];
	$drord = $this->data["dr_order"];
//print $date;
//print $p_oid;
//print "nuord".$nuord;
//print $drord;
	$nuname = array(NULL => '');
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
		$nuname[$k] = $v;
	}
}
 

/ 
$ocont="";
 
$ocont="Orderdate=".$date." Dr=".$drord." Nu=".$nuname[$nuord]."\n";

$ocont="------------------\n"."食事箋"."\n".$ocont;
print $ocont;

//$db = mx_db_connect();

//insert into karte

 
$stmt10 = <<<SQL
select * from "カルテデモ表" where "日付"='$date' and "患者"=$p_oid and 
	"Superseded" is null;
SQL;
 


$rs0 = mx_db_fetch_all($db, $stmt10);

if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "カルテデモ表" ("患者", "日付","P") values ($p_oid,'$date','$ocont');
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
$ocont2="";
 for ($i=0;$i<count($rs0);$i++){	
 $pp=$rs0[$i]["P"];
 $idd=$rs0[$i]["ID"];
// print $pp."=";
$ocont2=$pp.'\r\n'.'-(updated to)-'."\n".$ocont;


$stmt1 = <<<SQL
   	update  "カルテデモ表" set "P"='$ocont2' where "日付"='$date' and "患者"=$p_oid and 
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




//end of insert into karte

simple_object_ppa_edit::commit($force);

//need this ? 11-02-2014
    $date = $this->data['order_date'];
    $match = array();
    if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);
	    mx_kick_claim_if_by_poid($db, $this->so_config['Patient_ObjectID'],
				     $date);
    }



  }



//11-02-2014

}
?>
