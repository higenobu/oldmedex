<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/oootemplate.php';

class oootemplate_application extends single_table_application {
  var $use_upload = 1;
  var $_upper = array('u/manage/index.php' => '�������ץꥱ�������',
		      'index.php' => '/images/top_button.png');
  var $_loo_title = 'OOO�ƥ�ץ졼�Ȱ���';
  var $_sod_title = 'OOO�ƥ�ץ졼�Ȥ�ɽ��';
  var $_soe_title = 'OOO�ƥ�ץ졼�Ȥ��Խ�';

  function list_of_objects($prefix) {
    return new list_of_oootemplates($prefix);
  }
  function object_display($prefix) {
    return new oootemplate_display($prefix);
  }
  function object_edit($prefix) {
    return new oootemplate_edit($prefix);
  }
}

$rpa = new oootemplate_application();
$rpa->main();
?>
