<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_nurse_ward_cfg = array
(
 'COLS' => array('����̾'),
 'TABLE' => '�������ɽ',
 'LIST_IDS' => array('ObjectID', '����̾'),
 'LCOLS' => array('����̾'),
 );

class list_of_wards extends list_of_simple_objects {

  function list_of_wards($prefix, $config=NULL) {
    global $_lib_u_nurse_ward_cfg;
    if (is_null($config))
      $config = $_lib_u_nurse_ward_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);
  }

  function draw() {
    mx_titlespan($this->Title);
    list_of_simple_objects::draw();
    mx_formi_submit($this->prefix . 'id-select', $this->Original,
		    "<span class=\"link\">�ѹ����ʤ�</span>");
  }

}
?>
