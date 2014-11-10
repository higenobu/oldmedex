<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/testset.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class testset_application extends single_table_application {
  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');
  var $_loo_title = '検体検査セットリスト';
  var $_sod_title = '検体検査セットの表示';
  var $_soe_title = '検体検査セットの編集';
  var $can_use_subpick_on_left = 1;

  function list_of_objects($prefix) {
    return new list_of_testsets($prefix);
  }
  function object_display($prefix) {
    return new testset_display($prefix);
  }
  function object_edit($prefix) {
    return new testset_edit($prefix);
  }
}

$rpa = new testset_application();
$rpa->main();
?>
