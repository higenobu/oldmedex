<?php // -*- mode: php; coding: euc-japan -*-
// Ʃ�ϴǸϿ

function __lib_u_nurse_hdn_cfg(&$cfg)
{
	$cfg = array_merge
		(array
		 ('TABLE' => 'Ʃ�ϴǸϿ',
		  'LCOLS' => array(
			  array('Column' => "Ʃ����"),
			  array('Column' => "Ʃ�ϻ���"),
			  array('Column' => "Ʃ�Ͻ�λ����"),
			  array('Column' => "�������饤����"),
			  ),

		  'ALLOW_SORT' => array('Ʃ����' =>
					array('Ʃ����' =>
					      '"Ʃ����"'),
					),

		  'DEFAULT_SORT' => 'Ʃ����',

		  'DCOLS' => array(
			  array('Column' => "Ʃ����",
				'Draw' => 'date'),
			  array('Column' => "Ʃ�ϻ���",
				'Draw' => 'timestamp',
				'Option' => array('validate' =>
						  'nonnull,time',
						  'timespec' =>
						  '2,8,18')),
			  array('Column' => "Ʃ�Ͻ�λ����",
				'Draw' => 'timestamp',
				'Option' => array('validate' =>
						  'nonnull,time',
						  'timespec' =>
						  '2,8,20')),
			  array('Column' => "�������饤����",
				'Draw' => 'dbenum',
				'DBEnum' => array('HD', '�������饤����'),
				'Option' => array('validate' =>
						  'nonnull'),
				),
			  array('Column' => "������ɸ",
				'Label' => "������ɸ (L/H)",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "�������",
				'Label' => "������� (L/H)",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "�νš���",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "�νš���",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "�찵����",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "�찵����",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "������͡�����",
				'Draw' => 'dbenum',
				'DBEnum' => array('HD', '������͡�����')),
			  array('Column' => '�ǸϿ',
				'Draw' => 'textarea'),
			  array('Column' => 'Ʃ�ϥ�����',
				'Draw' => NULL),
			  )
			 ),
		  $cfg);

	$cfg['ECOLS'] = $cfg['DCOLS'];
	$__c = array();
	foreach ($cfg['ECOLS'] as $elem) {
		$__c[] = $elem['Column'];
	}
	$cfg['COLS'] = $__c;
}

class list_of_hdns extends list_of_poa_objects {
	var $order_column_name_quoted = '"Ʃ�ϥ�����"';

	function list_of_hdns($prefix, $config=NULL) {
		__lib_u_nurse_hdn_cfg(&$config);
		list_of_simple_objects::list_of_simple_objects($prefix, $config);
	}

	function row_paging_orders() {
		$paging_keys = $this->row_paging_keys();
		$paging_orders = array();
		foreach ($paging_keys as $col) {
			$order = 0;
			switch ($col) {
			case 'Ʃ����':
				$order = 1;
				break;
			}
			$paging_orders[] = $order;
		}
		return $paging_orders;
	}

}

class hdn_display extends simple_object_display {
	function hdn_display($prefix, $config=NULL) {
		__lib_u_nurse_hdn_cfg(&$config);
		simple_object_display::simple_object_display($prefix, $config);
	}
}

class hdn_edit extends simple_object_poa_edit {
	var $order_column_name = "Ʃ�ϥ�����";

	function hdn_edit($prefix, $config=NULL) {
		__lib_u_nurse_hdn_cfg(&$config);
		simple_object_edit::simple_object_edit($prefix, $config);
	}

	function anew_tweak($orig_id) {
		simple_object_poa_edit::anew_tweak($orig_id);
		$this->data['Ʃ����'] = mx_today_string();
	}

	function anew_tweak_from_order($order, $orig_id) {
		foreach (array('�������饤����' => '�������饤����',
			       )
			 as $order_col => $exec_col) {
		if (!array_key_exists($exec_col, $this->data) ||
		    trim($this->data[$exec_col] == ''))
			$this->data[$exec_col] = $order[$order_col];
		}
	}
}

?>
