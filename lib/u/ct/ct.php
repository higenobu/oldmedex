<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';

$__lib_u_ct_filename_prefix_separator = ' ';

function __lib_u_ct_ws_anno(&$desc, &$data)
{
	global $__lib_u_ct_filename_prefix_separator;
	$desc['Option']['filename_prefix'] =
		$data["�ץ�ȥ�����No"] . $__lib_u_ct_filename_prefix_separator .
		$data["�No"] . $__lib_u_ct_filename_prefix_separator;
}

$_lib_u_ct_ct_cfg = array
(
 'TABLE' => '����',
 'COLS' => array(
		 "�ץ�ȥ�����No",
		 "�����",
		 "����̾",
		 "������̾",
		 "��ˡ",
		 "�︳�Կ�(ͽ�)",
		 "�︳�Կ�(�ܻ)",
		 "������Ϳ��",
		 "�����»ܴ���(����)",
		 "�����»ܴ���(��λ)",
		 "��������",
		 "��������",
		 "�ౡ����",
		 "��Ǥ��",
		 ),
 'LCOLS' => array("�ץ�ȥ�����No", "����̾", "�����»ܴ���(����)"),
 'DCOLS' => array(
		 "�ץ�ȥ�����No",
		 "�����",
		 "����̾",
		 "������̾",
		 "��ˡ",
		 "�︳�Կ�(ͽ�)",
		 "�︳�Կ�(�ܻ)",
		 "������Ϳ��",
		 "�����»ܴ���(����)",
		 "�����»ܴ���(��λ)",
		 "��������",
		 "��������",
		 "�ౡ����",
		 "��Ǥ��",
		  ),
 'ECOLS' => array(
		 "�ץ�ȥ�����No",
		 "�����",
		 "����̾",
		 "������̾",
		 "��ˡ",
		 "�︳�Կ�(ͽ�)",
		 "�︳�Կ�(�ܻ)",
		 "������Ϳ��",
		 array('Column' => "�����»ܴ���(����)",
		       'Draw' => 'date'
		       ),
		 array('Column' => "�����»ܴ���(��λ)",
		       'Draw' => 'date'
		       ),
		 array('Column' => "��������",
		       'Draw' => 'datetime'
		       ),
		 array('Column' => "��������",
		       'Draw' => 'datetime'
		       ),
		 array('Column' => "�ౡ����",
		       'Draw' => 'datetime'
		       ),
		 "��Ǥ��",
		  ),
 'ICOLS' => array(
		 "�ץ�ȥ�����No",
		 "�����",
		 "����̾",
		 "������̾",
		 "��ˡ",
		 "�︳�Կ�(ͽ�)",
		 "�︳�Կ�(�ܻ)",
		 "������Ϳ��",
		 "�����»ܴ���(����)",
		 "�����»ܴ���(��λ)",
		 "��������",
		 "��������",
		 "�ౡ����",
		 "��Ǥ��",
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
		$empty_to_null = array("�����»ܴ���(����)",);

		foreach ($empty_to_null as $col) {
			if ($this->data[$col] == "") {
				$this->data[$col] = NULL;
			}
		}

		$bad = 0;
		if (!is_null($this->data["�����»ܴ���(����)"])) {
			if ($st = mx_db_validate_date($this->data["�����»ܴ���(����)"])) {
				$this->err("(�����»ܴ���(����)): $st\n");
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
