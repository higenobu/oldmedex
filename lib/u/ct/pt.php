<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/patient-basic.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/ct.php';

function __lib_u_pt_q_anno(&$desc, &$data)
{
	global $__lib_u_ct_filename_prefix_separator;
	$desc['Option']['filename_prefix'] =
		$data["プロトコールNo"] . $__lib_u_ct_filename_prefix_separator .
		$data["試験No"] . $__lib_u_ct_filename_prefix_separator .
		trim($data["患者ID"]) . $__lib_u_ct_filename_prefix_separator;
}

$_lib_u_ct_pt_cfg = array
(
	'TABLE' => '治験オーダ',
	'COLS' => array(
		"患者",
		"患者姓名",

		"SCRNO",
		"イニシャル",
		"SCR結果",
		"仮被験者番号",
		"被験者番号",
		),
	'LCOLS' => array(
			 #"患者姓名",
			 "SCRNO",
			 "イニシャル", 
			 array('Column' => "SCR結果",
			       'Draw' => 'enum',
			       'Enum' => array(NULL => '未判定',
					       '1' => '判定中',
					       '2' => '辞退',
					       '3' => '不適',
					       '4' => '適'),
			       ),
			 "仮被験者番号",
			 "被験者番号",
			 ),
	'DCOLS' => array(
		#"患者姓名",
		"SCRNO",
		"イニシャル", 
		array('Column' => "SCR結果",
		      'Draw' => 'enum',
		      'Enum' => array(NULL => '未判定',
				      '1' => '判定中',
				      '2' => '辞退',
				      '3' => '不適',
				      '4' => '適'),
		      ),
		"仮被験者番号",
		"被験者番号",
		),
	'ECOLS' => array(
		array('Column' => "治験", Draw => NULL),
		array('Column' => "患者",
		      'Draw' => NULL),
		array('Column' => "患者姓名",
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'list_of_patient_basics',
		       'Message' => 'この患者に設定する',
		       'Config' => $_lib_u_manage_patient_basic_cfg,
		       'ListID' => array('ObjectID', '姓名'),
		       'Allow_NULL' => 0,
		       'ObjectColumn' => "患者",
		       )),
		"SCRNO",
		"イニシャル",
		array('Column' => "SCR結果",
		      'Draw' => 'enum',
		      'Enum' => array(NULL => '未判定',
				      '1' => '判定中',
				      '2' => '辞退',
				      '3' => '不適',
				      '4' => '適'),
		      ),
		"仮被験者番号",
		"被験者番号",
		),
	'ICOLS' => array(
		"治験",
		"患者",
		"SCR結果",
		"SCRNO",
		"イニシャル",
		"仮被験者番号",
		"被験者番号",
		),
);

$stmt_head = '
SELECT  O."ObjectID",
	O."SCRNO",
	O."イニシャル",
	O."備考",
	O."治験",
	P."ObjectID" AS "患者", (P."姓" || P."名") AS "患者姓名",
	O."SCR結果",
	O."仮被験者番号",
	O."被験者番号",
	O."調査票",
	O."治験患者記録",
	CT."プロトコールNo",
	P."患者ID"
FROM "治験オーダ" AS O
JOIN "治験" AS CT ON CT."ObjectID" = O."治験"
JOIN "患者台帳" AS P On P."ObjectID" = O."患者"
';

$_lib_u_ct_pt_cfg['HSTMT'] = $stmt_head . 'WHERE NULL IS NULL';
$_lib_u_ct_pt_cfg['STMT' ] = $stmt_head . 'WHERE O."Superseded" IS NULL';
$_lib_u_ct_pt_cfg['UNIQ_ID'] = 'O."ObjectID"';

class list_of_ct_pts extends list_of_simple_objects {

	var $debug = 1;

	function list_of_ct_pts($prefix, $cfg=NULL) {
		global $_lib_u_ct_pt_cfg;
		$this->loo = new list_of_ct_cts($prefix . 'loo-');
		$this->sod = new ct_ct_display($prefix . 'sod-');

		if ($this->loo->changed() && $this->loo->chosen()) {
			$this->sod->reset($this->loo->chosen());
			$this->sod_changed = 1;
		}
		if (array_key_exists($prefix. 'OHistory', $_REQUEST))
			$this->sod->history($_REQUEST[$prefix . 'OHistory']); 

		if (is_null($cfg))
			$cfg =& $_lib_u_ct_pt_cfg;
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
		if (!$this->sod->chosen())
			return; 
		$this->CT_ObjectID = $this->sod->chosen();
	}

	function lost_selection() {
		return $this->sod_changed;
	}

	function reset($id) {
		if (is_null($id)) {
			$this->loo->reset($id);
			$this->sod->reset($id);
			$this->CT_ObjectID = $this->sod->chosen();
		}
		list_of_simple_objects::reset($id);
	}

	function allow_new() {
		if (!$this->sod->chosen())
			return 0;
		return 1;
	}

	function draw() {
		$this->loo->draw();
		if (!$this->CT_ObjectID)
			return;

		print "<br />\n";
		mx_titlespan('[治験プロトコール表示]');
		$this->sod->draw();
		$sod_history = $this->sod->history();
		$oh = $this->prefix . 'OHistory';
		if (($sod_history & 3) == 3)
			mx_formi_submit($oh, 'Prev',
					mx_img_url('history.png'),'履歴');
		else {
			if (($sod_history & 5) == 5)
				mx_formi_submit($oh, 'Prev',
						mx_img_url('history-prev.png'),
						'前へ');
			if (($sod_history & 9) == 9)
				mx_formi_submit($oh, 'Next',
						mx_img_url('history-next.png'),
						'後へ');
		}
		print "<br />\n";
		mx_titlespan('[治験患者一覧]');
		return list_of_simple_objects::draw();
	}

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
			' AND CT."ObjectID" = ' .
			mx_db_sql_quote($this->CT_ObjectID));
	}

}

class ct_pt_display extends simple_object_display {

	var $debug = 1;

	function ct_pt_display($prefix, $cfg=NULL) {
		global $_lib_u_ct_pt_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_ct_pt_cfg;
		simple_object_display::simple_object_display($prefix, $cfg);
	}
}

class ct_pt_edit extends simple_object_edit {

	var $debug = 1;

	function ct_pt_edit($prefix, &$app, $cfg=NULL) {
		global $_lib_u_ct_pt_cfg;
		if (is_null($cfg))
			$cfg =& $_lib_u_ct_pt_cfg;
		$this->app = $app;
		$this->data['治験'] = $this->app->loo->CT_ObjectID;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		return simple_object_edit::annotate_row_data($data);
	}

	function _validate($force=NULL) {
		$empty_to_null = array("治験日", "調査票", "治験患者記録");

		$this->data['治験'] = $this->app->loo->CT_ObjectID;

		foreach ($empty_to_null as $col) {
			if ($this->data[$col] == "")
				$this->data[$col] = NULL;
		}

		$bad = 0;
		if (!is_null($this->data["治験日"])) {
			if ($st = mx_db_validate_date($this->data["治験日"])) {
				$this->err("(治験日): $st\n");
				$bad++;
			}
		}
		if (!$this->data['患者']) {
			$this->err("患者を指定しないといけません\n");
			$bad++;
		}

		if ($bad == 0)
			return 'ok';
	}

}

?>
