<?php // -*- mode: php; coding: euc-japan -*-
// To use this boilerplate application, define the following global variables
// before including this file:
// $stm_msgs:
//    message strings with the following string constants:
//    'New', 'New Like This', 'Edit'
// $stm_list_of_objects:
//    name of the class which implements list of objects.
// $stm_object_display:
//    name of the class which implements object display.
// $stm_object_edit:
//    name of the class which implements object edit.

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class manage_compat extends single_table_application {
  var $_def_msgs = array
  ('New' => '新規FROTZを登録する',
   'New Like This' => 'このFROTZをコピーして新しいFROTZを作る',
   'Edit' => 'このFROTZを編集する');
  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');
  var $can_use_subpick_on_left = 1;

  function manage_compat() {
    global $stm_msgs;
    $this->msg = $stm_msgs;
    if (array_key_exists('Object Name', $stm_msgs)) {
      $o = $stm_msgs['Object Name'];
      foreach ($this->_def_msgs as $key => $pattern) {
	if (! array_key_exists($key, $this->msg))
	  $this->msg[$key] = str_replace('FROTZ', $o, $pattern);
      }
    }
    $this->_should_allow_new = ($this->msg['New'] != '');
    single_table_application::single_table_application();
  }

  function allow_new() {
    return $this->_should_allow_new;
  }

  function list_of_objects($prefix) {
    global $stm_list_of_objects;
    return new $stm_list_of_objects($prefix);
  }
  function object_display($prefix) {
    global $stm_object_display;
    return new $stm_object_display($prefix);
  }
  function object_edit($prefix) {
    global $stm_object_edit;
    return new $stm_object_edit($prefix);
  }

  function loo_title() {
    global $stm_msgs;
    return $stm_msgs['Object Name'] . 'のリスト';
  }
  function sod_title() {
    global $stm_msgs;
    return $stm_msgs['Object Name'] . 'の内容表示';
  }
  function soe_title() {
    global $stm_msgs;
    return $stm_msgs['Object Name'] . 'の編集';
  }
  function soc_title() {
    global $stm_msgs;
    return $stm_msgs['Object Name'] . 'の新規作成';
  }
}

$osm = new manage_compat();
$osm->main();
?>
