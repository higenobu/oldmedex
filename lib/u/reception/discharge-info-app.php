<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/discharge-info.php';

function en($pfx, $name) {
  return $pfx . mx_form_encode_name($name);
}

$_lib_u_reception_discharge_info_cfg = array
(
 'TABLE' => 'discharge_info',
 'COLS' => array('����', 'disease1','disease2','disease3',
		 'icd10_1','icd10_2','icd10_3','admission_date_from',
		 'admission_date_to',
		 'admission_type_from1','admission_type_to1',
		 'admission_type_from2','admission_type_to2',
		 'admission_type_from3','admission_type_to3',
		 'admission_type_from4','admission_type_to4',
		 'comment','history','outcome','rx',
		 'rx_days','special_note','next_visit','transfer',
		 'transfer_to','training', 'training_other','written_by',
		 'written_on', 'primary_dr'),
 'ECOLS' => array(
		  array('Column' => 'primary_dr',
			'Label' => '�缣��',
		       ),
		  array('Column' => 'written_on',
			'Label' => '������',
			'Draw' => 'date'
		       ),
		  array('Column' => 'icd10_1',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease1'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease1',
			'Label' => '����̾1',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'icd10_2',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease2'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease2',
			'Label' => '����̾2',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'icd10_3',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease3'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease3',
			'Label' => '����̾3',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'admission_date_from',
			'Label' => '��������(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_date_to',
			'Label' => '��������(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from3',
			'Label' => '�������֡�����(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to3',
			'Label' => '�������֡�����(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from2',
			'Label' => '�������֡������ݸ�(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to2',
			'Label' => '�������֡������ݸ�(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from1',
			'Label' => '�������֡�Ǥ��(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to1',
			'Label' => '�������֡�Ǥ��(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from4',
			'Label' => '�������֡�����¾(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to4',
			'Label' => '�������֡�����¾(��)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'comment',
			'Label' => '������',
			),
		  array('Column' => 'history',
			'Label' => '�в�',
			'Draw' => 'textarea',
			'Option' => array('rows' => 10,
					  'cols' => 60)
			),
		  array('Column' => 'outcome',
			'Label' => 'ž��',
			'Draw' => 'radio',
			'Enum' => array(1 => '����',
					2 => '�ڲ�',
					3 => '����',
					4 => '����',
					5 => '����',
					6 => 'ž��',
					7 => '��˴')
			),

		  array('Column' => 'rx',
			'Label' => '�ౡ������',
			'Draw' => 'textarea',
			'Option' => array('rows' => 40,
					  'cols' => 60)
			),

		  array('Column' => 'rx_days',
			'Label' => '',
			'Option' => array('validate' => 'posint',
					  'trailer' => '��ʬ')
			),

		  array('Column' => 'special_note',
			'Label' => '�õ�����',
			'Draw' => 'textarea'
			),
		  array('Column' => 'next_visit',
			'Label' => '�象ͽ����',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'transfer',
			'Label' => '�Ҳ�',
			'Draw' => 'radio',
			'Enum' => array(1 => '¾���˾Ҳ�',
					2 => '��������',
					3 => '����¾')
			),
		  array('Column' => 'transfer_to',
			'Label' => '�Ҳ��赡��',
			),
		  array('Column' => 'training',
			'Label' => '�Ҳ������ץ����',
			'Draw' => 'daysoftheweek',
			'Option' => array('dow' => array('�ǥ�����',
							 'ˬ��Ǹ�',
							 'OT',
							 '����¾'))
			),
		  array('Column' => 'training_other',
			'Label' => '�Ҳ���������¾'
			),
		  array('Column' => 'written_by',
			'Label' => '���ܼ�',
			'Draw' => 'static',
			),
		  ),
 'LCOLS' => array(
		  array('Column' => 'disease1',
			'Label'=>'����̾1'),
		  array('Column' => 'admission_date_from',
			'Label' => '��������',
			),
		  array('Column' => 'admission_date_to',
			'Label' => '',
			),
		  array('Column' => 'primary_dr',
			'Label' => '�缣��',
			),
		  array('Column' => 'written_by',
			'Label' => '���ܼ�',
			),
		  array('Column' => 'written_on',
			'Label' => '������')
		  ),
 'E_RANDOM_LAYOUT' => array
    (
     array('Column' => 'primary_dr',
	   'Label' => '�缣��'),
     array('Insn' => '//'),
     array('Column' => 'disease1',
	   'Label' => '����̾1',
	   ),
     array('Column' => 'icd10_1',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'disease2',
	   'Label' => '����̾2',
	   ),
     array('Column' => 'icd10_2',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'disease3',
	   'Label' => '����̾3',
	   ),
     array('Column' => 'icd10_3',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_date_from',
	   'Label' => '��������(��)',
	   ),
     array('Column' => 'admission_date_to',
	   'Label' => '(��)',
	   'Draw' => 'date',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from3',
	   'Label' => '������(��)',
	   ),
     array('Column' => 'admission_type_to3',
	   'Label' => '(��)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from2',
	   'Label' => '�������ݸ�(��)',
	   ),
     array('Column' => 'admission_type_to2',
	   'Label' => '(��)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from1',
	   'Label' => '��Ǥ��(��)',
	   ),
     array('Column' => 'admission_type_to1',
	   'Label' => '(��)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from4',
	   'Label' => '������¾(��)',
	   ),
     array('Column' => 'admission_type_to4',
	   'Label' => '(��)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'comment',
	   'Label' => '����¾�ξ��Υ�����',
	   ),
     array('Insn' => '//'),

     array('Label' => '�в�'),
     array('Column' => 'history',
	   'Draw' => 'textarea',
	   'Span' => 5,
	   ),
     array('Insn' => '//'),

     array('Label' => 'ž��'),
     array('Column' => 'outcome',
	   'Span' => 5
	   ),
     array('Insn' => '//'),

     array('Label' => '�ౡ������'),
     array('Column' => 'rx',
	   'Draw' => 'textarea',
	   'Span' => 4,
	   ),
     array('Insn' => '//'),

     array('Label' => ' '),
     array('Column' => 'rx_days',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => '�õ�����'),
     array('Column' => 'special_note',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => '�象ͽ����'),
     array('Column' => 'next_visit',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => '�Ҳ�'),
     array('Column' => 'transfer',
	   'Span' => 3
	   ),
     array('Insn' => '//'),

     array('Label' => '�Ҳ��赡��'),
     array('Column' => 'transfer_to',
	   ),
     array('Insn' => '//'),

     array('Label' => '�Ҳ������ץ����'),
     array('Column' => 'training',
	   'Span' => 2
	   ),
     array('Column' => 'training_other'),
     array('Insn' => '//'),

     array('Column' => 'written_by',
	   'Label' => '���ܼ�',
	   'Draw' => 'static',
	   ),
     array('Column' => 'written_on',
	   'Label' => '������'),
     )

 );

$_lib_u_reception_discharge_info_cfg['DCOLS'] =
  $_lib_u_reception_discharge_info_cfg['ECOLS'];
$_lib_u_reception_discharge_info_cfg['D_RANDOM_LAYOUT'] =
  $_lib_u_reception_discharge_info_cfg['E_RANDOM_LAYOUT'];


class reception_discharge_info_application extends per_patient_application {
  var $use_printer = 1;

  function print_sod() {
    $this->sod->print_sod();
  }

  function list_of_objects($prefix, &$it) {
    global $_lib_u_reception_discharge_info_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_reception_discharge_info_cfg;
    $this->cfg_pt($cfg, $it);
    return new list_of_dischargeinfos($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    global $_lib_u_reception_discharge_info_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_reception_discharge_info_cfg;
    $this->cfg_pt($cfg, $it);
    return new dischargeinfo_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    global $_lib_u_reception_discharge_info_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_reception_discharge_info_cfg;
    $this->cfg_pt($cfg, $it);
    $cfg['AUTH'] = $this->auth;
    return new dischargeinfo_edit($prefix, $cfg);
  }
}
?>
