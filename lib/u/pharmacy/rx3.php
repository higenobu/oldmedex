<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/common.php';


function __lib_u_pharmacy_rx3_cfg(&$cfg, $opt) {
  global $_mx_rx_show_noclaim;
  global $_lib_u_pharmacy_common_tr_short;
  global $_mx_hack_takamiya;
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => '���޽����',
		  'DETAIL_TABLE' => '���޽��������',
		  'ALLOW_SORT' => 1,
		  'DEFAULT_SORT' => 'ObjectID',
		  'COLS' => array('����ǯ����',
				  '��Ͽ��',
				'����������',
				'startdate',
				  '��ʬ',
				  '����׻�',
				  '��ȯ��',

				  '�����',
				  '��߰�',
				  'Comment',
				  'noclaim',
				  'setflag',
				  'setcomment',
				  'PDF'
				  ),
		  'LCOLS' => array(array('Label' => '�����ID',
					 'Column' => 'ObjectID'),
				   '����ǯ����',
				  
				   array('Column' => '��Ͽ��',
					 'Label' => '�ؼ���',
					 'Draw' => 'enum',
					 'Enum' => mx_dbenum_primarydoctor(),
					 ),
				 
				   '��ʬ',
				   array('Column' => '����׻�',
					 'Label' => '',
					 'Draw' => 'enum',
					 'Enum' => $_lib_u_pharmacy_common_tr_short
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
				  /*
				   array('Label' => '��ȯ�����Բ�',
					 'Column' => '��ȯ��',
					 'Draw' => 'enum',
					 'Enum' => array('0' => '�Բ�',
							 '1' => ''))
				  */

				   array('Column' => 'PDF',
					 'Label' => '������',
					 'Draw' => 'notnull',
					 ),
				   ),
							 
		   'LIST_IDS' => array('ObjectID'),
		  ));

	if ($_mx_hack_takamiya)
	  $cfg['LCOLS'] = array(array('Label' => '�����ID',
				      'Column' => 'ObjectID'),
				'����ǯ����',
				'��ʬ',
				array('Column' => '�����',
				      'Label' => '�����'),
				array('Column' => '��߰�',
				      'Label' => '��߰�',
				      'Draw' => 'employee'
				      ),
				array('Column' => 'Comment',
				      'Label' => '������',
				      ),
				);

	if ($_mx_rx_show_noclaim)
	  $cfg['LCOLS'][] =
	    array('Column' => 'noclaim',
		  'Label' => '����',
		  'Draw' => 'enum',
		  'Enum' => array(1 => "����")
		  );
	

	if($opt['SETONLY']) {

	  if($opt['SETONLY'] == 2) {
	    $cfg['LCOLS'] = array(
				  array('Column' => 'setcomment',
					'Label' => '���å�̾'),
				  array('Column' => 'Do',
					'Label' => '',
					'Draw' => 'do'),
				  );
	    $cfg['NOLINK'] = array('Do');
	  }else{


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
	  }


	 $cfg['SETONLY'] = 1;
	  
	  $cfg['ENABLE_QBE'] = array(array('Column' => '�ؼ���',
					   'Compare' => '"��Ͽ��"',
					   'Draw' => 'enum',
					   'Singleton' => 1,
					   'Enum' => lib_ord_common_get_doctors(NULL, 'doctor', array('qbeenum' => 1))));
	  $a = $opt['AUTH'][2]['ObjectID'];
	  if ($a)
	    $cfg['DEFAULT_QBE'] = array(array('�ؼ���', '=' . $a));
	  

	}


}

class list_of_pharmacy_rx3s extends list_of_simple_objects {

	var $default_row_per_page = 4;

	function list_of_pharmacy_rx3s($prefix, $pid, $opt=array()) {
		$cfg = array();
		$this->pid = $pid;
		$this->setonly = $opt['SETONLY'];
		__lib_u_pharmacy_rx3_cfg(&$cfg, $opt);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
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

	function dx_notnull($desc, $value, $row) {
	  $chr = '';
	  if(!is_null($value))
	    $chr = '��';
	  $this->_dx_textish($chr);
	}

	function base_fetch_stmt_0() {
	  // calls grand super class' method statically.
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
