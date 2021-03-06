<?php // -*- mode: php; coding: euc-japan -*-
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
//add 0320-2012



include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/sod.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/soe.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';
//only for osato-clinic pdf print
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/pdf44.php';
 
//

function __lib_u_drjtest_cfg(&$cfg)
{
$cfg = array_merge(

array(
TABLE => 'drjms',
'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 's0',
 COLS => array(
 hizuke,s0, s1, s2, s3, s4, p0, p1, p2, p3, p4, p5, p6, a1, a2, a3, a4, 
       a5, a6
 ),

LCOLS => array(

array('Column' => 's0',
'Label' => '分類番号'),
array('Column' => 's1',
'Label' => '大分類'),
 
array('Column' => 's3',
'Label' => '小分類'),
array('Column' => 'p0',
'Label' => '項番'),
array('Column' => 'p1',
'Label' => '観点'),
array('Column' => 'p2',
'Label' => '試験内容'),
array('Column' => 'p3',
'Label' => '試験条件'),
array('Column' => 'p4',
'Label' => '期待値'),
array('Column' => 'p5',
'Label' => '確認対象物'),),
 

'DEFAULT_SORT' => 's0',

DCOLS => array(
array('Column' => 's0',
'Label' => '分類番号'),
array('Column' => 's1',
'Label' => '大分類'),
 
array('Column' => 's3',
'Label' => '小分類'),
array('Column' => 'p0',
'Label' => '項番'),
array('Column' => 'p1',
'Label' => '観点'),
array('Column' => 'p2',
'Label' => '試験内容'),
array('Column' => 'p3',
'Label' => '試験条件'),
array('Column' => 'p4',
'Label' => '期待値'),
array('Column' => 'p5',
'Label' => '確認対象物'),),


ECOLS => array(
array('Column' => 'hizume',
'Option' => array('validate' => 'date,nonnull', 'list' => 1)
),


 array('Column' => 's0',
					'Label' => '指示',
				    'Draw' => 'textarea',
'Option' => array('vocab' => array('え食事箋コメント'),
'cols' => 80)

),
array('Column' => 's1',		'Label' => '処方',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'イオベリン300シリンジ100ｍｌ生食１００ｍｌ' => 'イオベリン300シリンジ100ｍ生食１００ｍｌ',
'オムニバーグ300シリンジ' => 'オムニバーグ300シリンジ',
'ガストログラフィン' => 'ガストログラフィン',
'バリトゲン３００' => 'バリトゲン３００',
'バレックスモルトＳ' => 'バレックスモルトＳ',
'バリエネマ３００' => 'バリエネマ３００',
'ビリスコビンＤＩＣ５０' => 'ビリスコビンＤＩＣ５０',
'その他' => 'その他'
						     )
				       ),


array('Column' => 's2',
					'Label' => '部位1',
				   
				       'Draw' => 'enum',
				       'Enum' => array
('' => '',
 '単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 
 'その他' => 'その他'),
				    'Option' => array('validate' =>
							 'nonnull')),

array('Column' => 's3',
					'Label' => 'フィルムサイズ1',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ１' => 'サイズ１',
'その他' => 'その他'
						     )
				       ),


array('Column' => 's4',
					'Label' => '枚数１',
				    'Draw' => 'text'),

array('Column' => 'p1',
					'Label' => '部位2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
'単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 'その他' => 'その他'

						     )
				       ),
array('Column' => 'p2',
					'Label' => 'フィルムサイズ2',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ2' => 'サイズ2',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'p3',
					'Label' => '枚数2',
				    'Draw' => 'text'),



array('Column' => 'p4',
					'Label' => '部位3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						       '単純頭部' => '単純頭部',
 '単純胸部' => '単純胸部',
 '単純腹部' => '単純腹部',
 '単純骨盤腔' => '単純骨盤腔',
 '造影頭部' => '造影頭部',
 '造影胸部' => '造影胸部',
 '造影腹部' => '造影腹部',
 '造影骨盤腔' => '造影骨盤腔',
 '胸部' => '胸部',
 '腹部' => '腹部',
 '頭部' => '頭部',
 '頚椎' => '頚椎',
 '胸椎' => '胸椎',
 '腰椎' => '腰椎',
 '骨盤' => '骨盤',
 '鎖骨' => '鎖骨',
 '胸骨' => '胸骨',
 '肩関節' => '肩関節',
 
 '上腕' => '上腕',
 '肘関節' => '肘関節',
 '前腕' => '前腕',
 '手関節' => '手関節',
 '手指' => '手指',
 '股関節' => '股関節',
 '下腿' => '下腿',

 '足関節' => '足関節',
 'ＭＤＬ' => 'ＭＤＬ',
 'ＤＤＬ' => 'ＤＤＬ',
 'ＤＩＣ' => 'ＤＩＣ',
 'ＤＩＰ' => 'ＤＩＰ',
 'ＫＵＢ' => 'ＫＵＢ',
 'ＵＶＧ' => 'ＵＶＧ',
 'ＺＧ' => 'ＺＧ',
 '骨塩定量' => '骨塩定量',
 'その他' => 'その他'

 )
),
array('Column' => 'p5',
					'Label' => 'フィルムサイズ3',
				   
				       'Draw' => 'enum',
				       'Enum' => array('' => '',
						      
 'サイズ3' => 'サイズ3',
'その他' => 'その他'
						     )
				       ),


array('Column' => 'p6',
					'Label' => '枚数3',
				    'Draw' => 'text')
)
), $cfg);
	return $cfg;
}
class list_of_drjtests extends list_of_ppa_objects {
	function list_of_drjtests($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_drjtest_cfg($cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}
}

class drjtest_display extends simple_object_display {
	function drjtest_display($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_drjtest_cfg($cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}


class drjtest_edit extends simple_object_edit {
	function drjtest_edit($prefix, &$cfg) {
		if (is_null($cfg))
			$cfg = array();
		$cfg = __lib_u_drjtest_cfg($cfg);
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}
  
 
	function commit($force=NULL) {
		//$this->data['patient'] = $this->so_config['Patient_ObjectID'];
	 
		return simple_object_edit::commit($force);
	}
}
 
/*
$__drjtest_all_cols = array(
 
 
 
	array('Column' => 'hizuke',
	      'Label' => '作成日',
	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1,'size'=>10),
	      ),
 
　
 
 

 

 	array('Column' => 's0',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 
	array('Column' => 's1',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	
	array('Column' => 's2',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 's3',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 's4',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p1',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p2',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p3',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p4',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
	array('Column' => 'p5',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),

	array('Column' => 'p6',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
 	 
	 array('Column' => 'p5',
	      'Draw' => 'textarea',
		'Option' => array('vocab' => array('え食事箋コメント'),
				'cols' => 20),),
	 
 



 
);

$__drjtest_cfg = array();

$__drjtest_cfg['TABLE'] = 'drjms';
$__drjtest_cfg['SEQUENCE'] = 'drjms_id_seq';
$__drjtest_cfg['COLS'] = array();
$__drjtest_cfg['ICOLS'] = array();
$__drjtest_cfg['ECOLS'] = array();
$__drjtest_cfg['LCOLS'] = array();
$__drjtest_cfg['DCOLS'] = array();
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//display layout 0315-2013
//*************************************************************
//DISPLAY
$__drjtest_cfg['D_RANDOM_LAYOUT'] = array(

	
 
	array('Label' => '検査日'),
	array('Column' => 'hizuke'),
 
	 
	
	
	array('Insn' => '//'),
	
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '分類番号'),
	array('Column' => 's0', 'Span' => 1),
 	array('Label' => 's1'),
	array('Column' => 's1','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  's2'),
	array('Column' => 's2', 'Option' => array('size' => 5)),
	 
	 
array('Insn' => '//'),	
	array('Label' => 's3'),
	array('Column' => 's3','Option' => array('size' => 5)),
 
array('Insn' => '//'),
	array('Label' => 's4'),
	array('Column' => 's4','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p0'),
	array('Column' => 'p0','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p1'),
	array('Column' => 'p1','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p2'),
	array('Column' => 'p2','Option' => array('size' => 5)),
	 
 
);


//EDIT
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE 0315-2013 modefied
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
$__drjtest_cfg['E_RANDOM_LAYOUT'] = array(
array('Label' => '検査日'),
	array('Column' => 'hizuke'),
 
 
	
	array('Insn' => '//'),
	
	array('Insn' => '//'),
	array('Insn' => ''),
 
	array('Insn' => '//'),
 
array('Insn' => '//'),
array('Insn' => '  ', 'Span' => 1),
array('Label' =>  '分類番号'),
	array('Column' => 's0', 'Span' => 1),
 	array('Label' => 's1'),
	array('Column' => 's1','Span' => 3),
	
	array('Insn' => '//'),
	array('Label' =>  's2'),
	array('Column' => 's2', 'Option' => array('size' => 5)),
	 
	 
array('Insn' => '//'),	
	array('Label' => 's3'),
	array('Column' => 's3','Option' => array('size' => 5)),
 
array('Insn' => '//'),
	array('Label' => 's4'),
	array('Column' => 's4','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p0'),
	array('Column' => 'p0','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p1'),
	array('Column' => 'p1','Option' => array('size' => 5)),
	 
array('Insn' => '//'),
	array('Label' => 'p2'),
	array('Column' => 'p2','Option' => array('size' => 5)),
	 
 
);
 
foreach ($__drjtest_all_cols as $v) {
	$o = mx_check_option('Option', $v);
	$c = $v['Column'];

	if (!mx_check_option('nostore', $o)) {
		$__drjtest_cfg['COLS'][] = $c;
		$__drjtest_cfg['ICOLS'][] = $c;
	}
	if (mx_check_option('list', $o))
		$__drjtest_cfg['LCOLS'][] = $v;
	if (!mx_check_option('nodisp', $o))
		$__drjtest_cfg['DCOLS'][] = $v;
	if (!mx_check_option('noedit', $o))
		$__drjtest_cfg['ECOLS'][] = $v;
}
 
class list_of_drjtests extends list_of_ppa_objects {

	var $debug = 1;
	var $patient_column_name_quoted = 'header';

	function list_of_drjtests($prefix, $cfg=NULL) {
		global $__drjtest_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__drjtest_cfg);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
	}

	
}

class drjtest_display extends simple_object_display {

	var $debug = 1;

	function drjtest_display($prefix, $cfg=NULL) {
		global $__drjtest_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__drjtest_cfg);
		simple_object_display::simple_object_display($prefix, $cfg);
	}
 
	function annotate_row_data(&$data) {
		__lib_u_everybody_drjtest_anno(&$data);
		return simple_object_display::annotate_row_data(&$data);
	}
 


//

//


 
 
 


}


class drjtest_edit extends simple_object_ppa_edit {

	var $debug = 1;

//	var $patient_column_name = 'header';
 
	function edit_tweak() {
		$this->data['hizuke'] = mx_today_string();
		__lib_u_everybody_drjtest_anno(&$this->data);
	}

	function anew_tweak($orig_id) {
		if (trim($this->data['hizuke']) == '')
			$this->data['hizuke'] = mx_today_string();
		$this->data['hizuke'] = mx_today_string();
	}

	function annotate_form_data(&$data) {
		if ($data['s0'])
			__lib_u_everybody_drjtest_anno(&$data);
		return simple_object_ppa_edit::annotate_form_data(&$data);
	}
 

	function drjtest_edit($prefix, $cfg=NULL) {
		global $__drjtest_cfg;
		if (is_null($cfg))
			$cfg = array();
		$cfg = array_merge($cfg,
				   $__drjtest_cfg);
		simple_object_ppa_edit::simple_object_ppa_edit($prefix, $cfg);
	}

	function _validate($force=NULL) {
		$bad = simple_object_ppa_edit::_validate($force) != 'ok';
		$d =& $this->data;
		
		if ($bad)
			return '';
		return 'ok';
	}

}

 */



?>
