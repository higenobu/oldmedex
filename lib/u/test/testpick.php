<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

class testpick_selector extends list_of_simple_objects {

  var $default_row_per_page = 10;
  var $debug = 1;
  function testpick_selector($prefix, $testpick) {
    $this->testpick = $testpick;
    $cfg = array();

    $oo = $testpick->lcols;
    foreach ($testpick->list_ids as $col)
      if (! array_key_exists($col, $oo))
	$oo[] = $col;
    $cfg['COLS'] = $testpick->lcols;
    $cfg['ALLOW_SORT'] = array('����̾ (ά��̾)' => array('����̾ (ά��̾)' => '"����̾ (ά��̾)"'),
			       '����̾ (���ܸ�)' => array('����̾ (���ܸ�)' => '"����̾ (���ܸ�)"'),
			       'ñ��̾' => array('ñ��̾' => '"ñ��̾"'));

    $o = array();
    foreach ($oo as $col)
      $o[] = 'M.' . mx_db_sql_quote_name($col);

    $stmt = ('SELECT M."ObjectID", ' .
	     implode(",\n       ", $o) .
	     ' FROM "���θ����ޥ�����" AS M WHERE M."Superseded" IS NULL');

    $cfg['STMT'] = $stmt;
    $cols = $testpick->lcols;
    $stride = $testpick->stride;
    $cnt = count($cols);
    $layo = array();
    for ($ix = 0; $ix < $cnt; $ix += $stride) {
      if (count($layo))
	$layo[] = '//';
      for ($iy = $ix; $iy < $ix + $stride; $iy++) {
	if ($iy < $cnt)
	  $layo[] = $cols[$iy];
	elseif ($cnt <= $stride)
	  ;
	else
	  $layo[] = '  ';
      }
    }
    $cfg['LLAYO'] = $layo;
    $cfg['ENABLE_QBE'] = $testpick->enable_qbe;
    $cfg['DEFAULT_QBE'] = $testpick->default_qbe;
    $cfg['LIST_IDS'] = $this->testpick->list_ids;

    list_of_simple_objects::list_of_simple_objects
      ($prefix, $cfg);
  }

  function draw_no_data_message() {
    print '<br />�����������ޤ�����ޤ���';
  }

}

class testpick {

  var $default_config = array
  ('LCOLS' => array("����̾ (ά��̾)",
		    "����̾ (���ܸ�)",
		    "ñ��̾",
		    "�����ﲼ��",
		    "��������",
		    "�����ﲼ��",
		    "��������",
		    ),

   'LIST_IDS' =>  array("����̾ (���ܸ�)", "ObjectID"),
   'ENABLE_QBE' => array("����̾ (���ܸ�)",
			 array('Column' => "��������",
			       'Compare' => 'M."��������"',
			       'Draw' => 'enum',
			       'Enum' => array('F' => '�ѽ�ʬ�Τ�',
					       'YF' => '����ʬ�Τ�',
					       '' => '�ޥ�������'),
			       'CompareMethod' => 'enum_single_char',
			       'Singleton' => 1,
			       )),
   'DEFAULT_QBE' => array(array('��������', 'F')),
   'STRIDE' => 7);

  function _get_default_config($elem) {
    if (array_key_exists($elem, $this->config))
	return $this->config[$elem];
    return $this->default_config[$elem];
  }

  function testpick($prefix, $config=NULL) {
    if (is_null($config))
      $config = $this->default_config;
    $this->config = $config;

    $this->prefix = $prefix;
    $this->lcols = $this->_get_default_config('LCOLS');
    $this->list_ids = $this->_get_default_config('LIST_IDS');
    $this->enable_qbe = $this->_get_default_config('ENABLE_QBE');
    $this->default_qbe = $this->_get_default_config('DEFAULT_QBE');
    $this->stride = $this->_get_default_config('STRIDE');

    // We have enough information to set up the selector now.
    $this->selector = new testpick_selector($prefix . 'sel-', $this);
  }

  function reset() {
    $this->selector->reset(NULL);
  }

  function chosen() {
    return $this->selector->chosen();
  }

  function draw() {
    print '<hr />';
    $this->draw_selection_ui();
  }

  function draw_selection_ui() {
    print '<hr />';
    mx_titlespan('���ν�������');
    $this->selector->draw();
  }
}
?>
