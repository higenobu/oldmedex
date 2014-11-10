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

function _lib_u_ota_kiroku2() {
  $db = mx_db_connect();
  $stmt = <<<SQL
    select "姓" || "名" as empname , userid
    from "職員台帳"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '');
  foreach($rows as $row)
    $ret[$row['userid']] = $row['empname'];
  return $ret;
}

 

function mk_enum($a) {
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

$__drjtest_category_enum = array
(
	'新規','変更','中止'
);

$__drjtest_addition_enum = array
(
	'','特別','普通',
);




$__drjtest_exam_enum = array
(
	'N/A','異常なし','所見あり','再検査','不明',
);
$__drjtest_hantei_enum = array
(
	'-','A','B','C(1)','C(2)','C(3)','D','E','F','G'
);
//1020-2012
$__drjtest_n_enum = array
(
'0'=>'-','1'=>'Ab',
);
$__drjtest_plus_enum = array
(
	'N/A','(-)','(+)', 
);
$__drjtest_abo_enum = array
(
   'N/A','A','B','O','AB',
);

$__drjtest_rh_enum = array
(
	'N/A','Rh+','Rh-', 
);
$__drjtest_np_enum = array
(
	'N/A','NEGATIVE','POSITIVE', 
);

$__drjtest_all_cols = array(
 

	array('Column' => 'header',
	      'Draw' => NULL,
	      'Option' => array('noedit' => 1, 'nodisp' => 1),
	      ),
 
	array('Column' => 'hizuke',
	      'Label' => '作成日',
	      'Draw' => 'date',
	      'Option' => array('validate' => 'date,nonnull', 'list' => 1,'size'=>10),
	      ),
 
　
 
 

//04=20-2012 

 	array('Column' => 's0',
	       'Draw' => 'text','Option' => array('size' => 10),
	      ),
//04-23-2012
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
 
	array('Label' => ''),
	
	
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
 
	array('Label' => ''),
	
	
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
function __lib_u_everybody_drjtest_anno(&$data)
{

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

	function annotate_row_data(&$data) {
		__lib_u_everybody_drjtest_anno(&$data);
		return list_of_ppa_objects::annotate_row_data(&$data);
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


 
function print_sod() {
    go_pdf($this->id, 0);
  }

 


}

class drjtest_edit extends simple_object_ppa_edit {

	var $debug = 1;

	var $patient_column_name = 'header';

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

 


?>
