<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_doctor_imgtestpick_dps_cfg = array
('COLS' => array('����ʣ��̾��', '�����ƥ�', '��ʬ��', '��ʬ��', '��ĥ',
		 '���̥�����'),
 'ALLOW_SORT' => 1,
 'TABLE' => 'Medis���������ޥ�����',
 'ENABLE_QBE' => array('����ʣ��̾��',
		       '���̥�����',
		       array('Column' => '��������',
			     'Compare' => '"��������"',
			     'Draw' => 'enum',
			     'Enum' => array('F' => '�ѽ�ʬ�Τ�',
					     'YF' => '����ʬ�Τ�',
					     '' => '�ޥ�������'),
			     'CompareMethod' => 'enum_single_char',
			     'Singleton' => 1) ),
 'DEFAULT_QBE' => array(array('��������', 'F')),
 'LIST_IDS' => array('ObjectID', '����ʣ��̾��'),
 );

class imgtestpick extends list_of_simple_objects {

  function imgtestpick($prefix) {
    global $_lib_u_doctor_imgtestpick_dps_cfg;
    $cfg = $_lib_u_doctor_imgtestpick_dps_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $cfg);
  }

  function draw_no_data_message() {
    print '<br />�������븡��������ޤ���';
  }

}
?>
