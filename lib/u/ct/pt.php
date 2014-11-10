<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/patient-basic.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/ct.php';

function __lib_u_pt_q_anno(&$desc, &$data)
{
	global $__lib_u_ct_filename_prefix_separator;
	$desc['Option']['filename_prefix'] =
		$data["�ץ�ȥ�����No"] . $__lib_u_ct_filename_prefix_separator .
		$data["�No"] . $__lib_u_ct_filename_prefix_separator .
		trim($data["����ID"]) . $__lib_u_ct_filename_prefix_separator;
}

$_lib_u_ct_pt_cfg = array
(
	'TABLE' => '����������',
	'COLS' => array(
		"����",
		"������̾",

		"SCRNO",
		"���˥����",
		"SCR���",
		"���︳���ֹ�",
		"�︳���ֹ�",
		),
	'LCOLS' => array(
			 #"������̾",
			 "SCRNO",
			 "���˥����", 
			 array('Column' => "SCR���",
			       'Draw' => 'enum',
			       'Enum' => array(NULL => '̤Ƚ��',
					       '1' => 'Ƚ����',
					       '2' => '����',
					       '3' => '��Ŭ',
					       '4' => 'Ŭ'),
			       ),
			 "���︳���ֹ�",
			 "�︳���ֹ�",
			 ),
	'DCOLS' => array(
		#"������̾",
		"SCRNO",
		"���˥����", 
		array('Column' => "SCR���",
		      'Draw' => 'enum',
		      'Enum' => array(NULL => '̤Ƚ��',
				      '1' => 'Ƚ����',
				      '2' => '����',
				      '3' => '��Ŭ',
				      '4' => 'Ŭ'),
		      ),
		"���︳���ֹ�",
		"�︳���ֹ�",
		),
	'ECOLS' => array(
		array('Column' => "����", Draw => NULL),
		array('Column' => "����",
		      'Draw' => NULL),
		array('Column' => "������̾",
		      'Draw' => 'subpick',
		      'Subpick' => array
		      ('Class' => 'list_of_patient_basics',
		       'Message' => '���δ��Ԥ����ꤹ��',
		       'Config' => $_lib_u_manage_patient_basic_cfg,
		       'ListID' => array('ObjectID', '��̾'),
		       'Allow_NULL' => 0,
		       'ObjectColumn' => "����",
		       )),
		"SCRNO",
		"���˥����",
		array('Column' => "SCR���",
		      'Draw' => 'enum',
		      'Enum' => array(NULL => '̤Ƚ��',
				      '1' => 'Ƚ����',
				      '2' => '����',
				      '3' => '��Ŭ',
				      '4' => 'Ŭ'),
		      ),
		"���︳���ֹ�",
		"�︳���ֹ�",
		),
	'ICOLS' => array(
		"����",
		"����",
		"SCR���",
		"SCRNO",
		"���˥����",
		"���︳���ֹ�",
		"�︳���ֹ�",
		),
);

$stmt_head = '
SELECT  O."ObjectID",
	O."SCRNO",
	O."���˥����",
	O."����",
	O."����",
	P."ObjectID" AS "����", (P."��" || P."̾") AS "������̾",
	O."SCR���",
	O."���︳���ֹ�",
	O."�︳���ֹ�",
	O."Ĵ��ɼ",
	O."�������Ե�Ͽ",
	CT."�ץ�ȥ�����No",
	P."����ID"
FROM "����������" AS O
JOIN "����" AS CT ON CT."ObjectID" = O."����"
JOIN "������Ģ" AS P On P."ObjectID" = O."����"
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
		mx_titlespan('[�����ץ�ȥ�����ɽ��]');
		$this->sod->draw();
		$sod_history = $this->sod->history();
		$oh = $this->prefix . 'OHistory';
		if (($sod_history & 3) == 3)
			mx_formi_submit($oh, 'Prev',
					mx_img_url('history.png'),'����');
		else {
			if (($sod_history & 5) == 5)
				mx_formi_submit($oh, 'Prev',
						mx_img_url('history-prev.png'),
						'����');
			if (($sod_history & 9) == 9)
				mx_formi_submit($oh, 'Next',
						mx_img_url('history-next.png'),
						'���');
		}
		print "<br />\n";
		mx_titlespan('[�������԰���]');
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
		$this->data['����'] = $this->app->loo->CT_ObjectID;
		simple_object_edit::simple_object_edit($prefix, $cfg);
	}

	function annotate_row_data(&$data) {
		return simple_object_edit::annotate_row_data($data);
	}

	function _validate($force=NULL) {
		$empty_to_null = array("������", "Ĵ��ɼ", "�������Ե�Ͽ");

		$this->data['����'] = $this->app->loo->CT_ObjectID;

		foreach ($empty_to_null as $col) {
			if ($this->data[$col] == "")
				$this->data[$col] = NULL;
		}

		$bad = 0;
		if (!is_null($this->data["������"])) {
			if ($st = mx_db_validate_date($this->data["������"])) {
				$this->err("(������): $st\n");
				$bad++;
			}
		}
		if (!$this->data['����']) {
			$this->err("���Ԥ���ꤷ�ʤ��Ȥ����ޤ���\n");
			$bad++;
		}

		if ($bad == 0)
			return 'ok';
	}

}

?>
