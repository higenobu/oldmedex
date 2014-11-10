<?php // -*- mode: php; coding: euc-japan -*-
// Æ©ÀÏ´Ç¸îµ­Ï¿

function __lib_u_nurse_hdn_cfg(&$cfg)
{
	$cfg = array_merge
		(array
		 ('TABLE' => 'Æ©ÀÏ´Ç¸îµ­Ï¿',
		  'LCOLS' => array(
			  array('Column' => "Æ©ÀÏÆü"),
			  array('Column' => "Æ©ÀÏ»þ¹ï"),
			  array('Column' => "Æ©ÀÏ½ªÎ»»þ¹ï"),
			  array('Column' => "¥À¥¤¥¢¥é¥¤¥¶¡¼"),
			  ),

		  'ALLOW_SORT' => array('Æ©ÀÏÆü' =>
					array('Æ©ÀÏÆü' =>
					      '"Æ©ÀÏÆü"'),
					),

		  'DEFAULT_SORT' => 'Æ©ÀÏÆü',

		  'DCOLS' => array(
			  array('Column' => "Æ©ÀÏÆü",
				'Draw' => 'date'),
			  array('Column' => "Æ©ÀÏ»þ¹ï",
				'Draw' => 'timestamp',
				'Option' => array('validate' =>
						  'nonnull,time',
						  'timespec' =>
						  '2,8,18')),
			  array('Column' => "Æ©ÀÏ½ªÎ»»þ¹ï",
				'Draw' => 'timestamp',
				'Option' => array('validate' =>
						  'nonnull,time',
						  'timespec' =>
						  '2,8,20')),
			  array('Column' => "¥À¥¤¥¢¥é¥¤¥¶¡¼",
				'Draw' => 'dbenum',
				'DBEnum' => array('HD', '¥À¥¤¥¢¥é¥¤¥¶¡¼'),
				'Option' => array('validate' =>
						  'nonnull'),
				),
			  array('Column' => "½ü¿åÌÜÉ¸",
				'Label' => "½ü¿åÌÜÉ¸ (L/H)",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "Áí½ü¿åÎÌ",
				'Label' => "Áí½ü¿åÎÌ (L/H)",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "ÂÎ½Å¡¦Á°",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "ÂÎ½Å¡¦¸å",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "·ì°µ¡¦Á°",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "·ì°µ¡¦¸å",
				'Draw' => 'text',
				'Option' => array('validate' =>
						  'number,nonnull')),
			  array('Column' => "Ìô¡¦Ãí¼Í¡¦½èÃÖ",
				'Draw' => 'dbenum',
				'DBEnum' => array('HD', 'Ìô¡¦Ãí¼Í¡¦½èÃÖ')),
			  array('Column' => '´Ç¸îµ­Ï¿',
				'Draw' => 'textarea'),
			  array('Column' => 'Æ©ÀÏ¥ª¡¼¥À',
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
	var $order_column_name_quoted = '"Æ©ÀÏ¥ª¡¼¥À"';

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
			case 'Æ©ÀÏÆü':
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
	var $order_column_name = "Æ©ÀÏ¥ª¡¼¥À";

	function hdn_edit($prefix, $config=NULL) {
		__lib_u_nurse_hdn_cfg(&$config);
		simple_object_edit::simple_object_edit($prefix, $config);
	}

	function anew_tweak($orig_id) {
		simple_object_poa_edit::anew_tweak($orig_id);
		$this->data['Æ©ÀÏÆü'] = mx_today_string();
	}

	function anew_tweak_from_order($order, $orig_id) {
		foreach (array('¥À¥¤¥¢¥é¥¤¥¶¡¼' => '¥À¥¤¥¢¥é¥¤¥¶¡¼',
			       )
			 as $order_col => $exec_col) {
		if (!array_key_exists($exec_col, $this->data) ||
		    trim($this->data[$exec_col] == ''))
			$this->data[$exec_col] = $order[$order_col];
		}
	}
}

?>
