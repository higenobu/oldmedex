<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';

function __lib_u_pharmacy_shot_cfg(&$cfg, $opt) {
  global $_lib_u_pharmacy_common_tr;
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => '��ͽ����',
		  'ALLOW_SORT' => 1,
		  'DEFAULT_SORT' => 'ObjectID',
		  'COLS' => array('����ǯ����',
				  '��ʬ',
				  '����׻�',
				  '��ȯ��',
				  '�����',
				  '��߰�',
				  'Comment',
				  'noclaim',
				  'setflag',
				  'setcomment'
				  ),
		  'LCOLS' => array(array('Label' => '�����ID',
					 'Column' => 'ObjectID'),
				   '����ǯ����',
				   '��ʬ',
				   array('Column' => '����׻�',
					 'Label' => '',
					 'Draw' => 'enum',
					 'Enum' => $_lib_u_pharmacy_common_tr
					 ),
				   array('Column' => '�����',
					 'Label' => '�����'),
				   array('Column' => '��߰�',
					 'Label' => '��߰�',
					 'Draw' => 'employee'
					 ),
				   array('Column' => 'Comment',
					 'Label' => '������',
					 ),
				   array('Column' => 'noclaim',
					 'Label' => '����',
					 'Draw' => 'enum',
					 'Enum' => array(1 => "����")
					 ),
				   /*
				   array('Label' => '��ȯ�ʲ�',
					 'Column' => '��ȯ��',
					 'Draw' => 'enum',
					 'Enum' => array('0' => '�Բ�',
							 '1' => '��'))
				   */
				   ),
		  'LIST_IDS' => array('ObjectID'),
		  ));
	if($opt['SETONLY']) {
	  $cfg['LCOLS'] = array(
				array('Column' => 'setcomment',
				      'Label' => '���å�̾'),
				array('Column' => 'Show',
				      'Label' => '',
				      'Draw' => 'show'),
				array('Column' => 'Do',
				      'Label' => '',
				      'Draw' => 'do'),
				);
	  $cfg['NOLINK'] = array('Show', 'Do');
	  $cfg['SETONLY'] = 1;
	}
}

class list_of_pharmacy_shots extends list_of_ppa_objects {

	var $default_row_per_page = 4;

	function list_of_pharmacy_shots($prefix, $pid, $opt=array()) {
		$cfg = array();
		$this->pid = $pid;
		$this->setonly = $opt['SETONLY'];
		__lib_u_pharmacy_shot_cfg(&$cfg, $opt);
		list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
		if ($_REQUEST[$prefix . 'show-set-id'])
		  $this->chosen_for_show = $_REQUEST[$prefix . 'show-set-id'];
		if ($_REQUEST[$prefix . 'do-set-id'])
		  $this->chosen_for_do = $_REQUEST[$prefix . 'do-set-id'];
	}

	function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			if ($col == '����ǯ����' ||
			    $col == 'ObjectID')
				$paging_orders[] = 1;
			else
				$paging_orders[] = 0;
		}
		return $paging_orders;
	}
	function dx_employee($desc, $value, $row) {
	  $name = get_emp_name($value);
	  $this->_dx_textish($name['lname'] . $name['fname']);
	}

	function base_fetch_stmt_0() {
	  $stmt = list_of_simple_objects::base_fetch_stmt_0();
	  if ($this->setonly) 
	    return $stmt . " AND setflag=1";

	  $patient_column_name_quoted = '"����"';
	  if ($this->pid)
	    return $stmt . ' AND ' .
	      $patient_column_name_quoted .
	      ' = ' .
	      mx_db_sql_quote($this->pid);
	}


	function dx_do($desc, $value, $row) {
	  $value = $row['ObjectID'];
	  $pfx = $this->prefix;
	  print <<<HTML
<button type="submit" name="${pfx}do-set-id" value="$value">Do</button>
HTML;
	}

	function dx_show($desc, $value, $row) {
	  $value = $row['ObjectID'];
	  $pfx = $this->prefix;
	  print <<<HTML
<button type="submit" name="${pfx}show-set-id" value="$value">�ܺ�</button>
HTML;
	}
}
?>
