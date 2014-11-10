<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/room-patient.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class room_patient_application extends single_table_application {
  var $_upper = array('u/manage/index.php' => '�������ץꥱ�������',
		      'index.php' => '/images/top_button.png');
  var $_loo_title = '�¼�����ɽ�ꥹ��';
  var $_sod_title = '�¼�����ɽ��ɽ��';
  var $_soe_title = '�¼�����ɽ���Խ�';
  var $can_use_subpick_on_left = 1;

  function allow_new() { return 0; }
  function list_of_objects($prefix) {
    return new list_of_room_patients($prefix);
  }
  function object_display($prefix) {
    return new room_patient_display($prefix);
  }
  function object_edit($prefix) {
    return new room_patient_edit($prefix);
  }
}

$rpa = new room_patient_application();
$rpa->main();
?>
