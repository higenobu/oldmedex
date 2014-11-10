<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

////////////////////////////////////////////////////////////////
$_lib_u_manage_patientgroup_cfg = array
(
 'TABLE' => '���ԥ��롼��',
 'DEFAULT_SORT' => 'ɽ�����',
 'ALLOW_SORT' => 1,
 'COLS' => array("���롼��", "��٥�", "�Ի���", "ɽ�����"),
);
$__c = array(array('Column' => '���롼��',
		   'Option' => array('validate' => 'nonnull')),
	     array('Column' => '��٥�',
		   'Option' => array('validate' => 'nonnull')),
	     array('Column' => '�Ի���',
		   'Label' => '�Ի���',
		   'Draw' => 'enum',
		   'Enum' => array('N' => '', 'Y' => '�Ի���')),
	     array('Column' => 'ɽ�����',
		   'Option' => array('validate' => 'nonnull,posint')));
$_lib_u_manage_patientgroup_cfg['LCOLS'] = $__c;
$_lib_u_manage_patientgroup_cfg['DCOLS'] = $__c;
$_lib_u_manage_patientgroup_cfg['ECOLS'] = $__c;

class list_of_patientgroups extends list_of_simple_objects {
  function list_of_patientgroups($prefix, $cfg=NULL) {
    global $_lib_u_manage_patientgroup_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_patientgroup_cfg;
    list_of_simple_objects::list_of_simple_objects($prefix, $cfg);
  }
}

class patientgroup_display extends simple_object_display {
  function patientgroup_display($prefix, $cfg=NULL) {
    global $_lib_u_manage_patientgroup_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_patientgroup_cfg;
    simple_object_display::simple_object_display($prefix, $cfg);
  }
}

class patientgroup_edit extends simple_object_edit {
  function patientgroup_edit($prefix, $cfg=NULL) {
    global $_lib_u_manage_patientgroup_cfg;
    if (is_null($cfg))
      $cfg =& $_lib_u_manage_patientgroup_cfg;
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }
}
?>
