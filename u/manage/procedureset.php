<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/procedureset.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class procedureset_application extends single_table_application {
  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');
  var $_loo_title = '処置セット一覧';
  var $_sod_title = '処置セットの表示';
  var $_soe_title = '処置セットの編集';
  var $can_use_subpick_on_left = 1;

  function list_of_objects($prefix) {
    return new list_of_proceduresets($prefix);
  }
  function object_display($prefix) {
    return new procedureset_display($prefix);
  }
  function object_edit($prefix) {
    return new procedureset_edit($prefix);
  }
}

$rpa = new procedureset_application();
$rpa->main();
?>
