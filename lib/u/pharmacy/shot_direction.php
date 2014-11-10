<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

function __lib_u_pharmacy_shot_method_cfg(&$cfg) {
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => '��ͼ굻',
		  'COLS' => array('�굻',
				  ),
		  'LCOLS' => array('�굻',
				  ),
		  'LIST_IDS' => array('ObjectID','�굻'),
                  'ROW_PER_PAGE' => 0,
		  'MULTI_COLS' => 0,
		  ));
}

class list_of_pharmacy_shot_methods extends list_of_simple_objects {

	function list_of_pharmacy_shot_methods($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_pharmacy_shot_method_cfg(&$cfg);

                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

	function lazily_compute_row_per_page($data) {
		$rpp = count($data);
		$this->row_per_page = $rpp;
		if ($rpp <= 10)
			$this->so_config['MULTI_COLS'] = 2;
		else
			$this->so_config['MULTI_COLS'] = 3;
	}
}

function __lib_u_pharmacy_shot_dosage_cfg(&$cfg) {
	$cfg = array_merge
		($cfg,
		 array
		 ('TABLE' => '�����ˡ',
		  'COLS' => array('��ˡ'),
		  'LCOLS' => array('��ˡ'),
		  'LIST_IDS' => array('ObjectID','��ˡ'),
                  'ROW_PER_PAGE' => 100,
		  'SCROLLABLE_HEIGHT' => '140px'
		  ));
}

class list_of_pharmacy_shot_dosages extends list_of_simple_objects {

	function list_of_pharmacy_shot_dosages($prefix, $config=NULL ) {
		$cfg = array();
		__lib_u_pharmacy_shot_dosage_cfg(&$cfg);

                if($config)
                   $cfg = array_merge($cfg, $config);
		list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
	}

}

?>
