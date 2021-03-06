<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/discharge-info.php';

function en($pfx, $name) {
  return $pfx . mx_form_encode_name($name);
}

$_lib_u_reception_discharge_info_cfg = array
(
 'TABLE' => 'discharge_info',
 'COLS' => array('患者', 'disease1','disease2','disease3',
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
			'Label' => '主治医',
		       ),
		  array('Column' => 'written_on',
			'Label' => '記載日',
			'Draw' => 'date'
		       ),
		  array('Column' => 'icd10_1',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease1'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease1',
			'Label' => '診断名1',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'icd10_2',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease2'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease2',
			'Label' => '診断名2',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'icd10_3',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease3'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease3',
			'Label' => '診断名3',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'admission_date_from',
			'Label' => '入院期間(自)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_date_to',
			'Label' => '入院期間(至)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from3',
			'Label' => '入院形態・措置(自)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to3',
			'Label' => '入院形態・措置(至)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from2',
			'Label' => '入院形態・医療保護(自)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to2',
			'Label' => '入院形態・医療保護(至)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from1',
			'Label' => '入院形態・任意(自)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to1',
			'Label' => '入院形態・任意(至)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from4',
			'Label' => '入院形態・その他(自)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to4',
			'Label' => '入院形態・その他(至)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'comment',
			'Label' => 'コメント',
			),
		  array('Column' => 'history',
			'Label' => '経過',
			'Draw' => 'textarea',
			'Option' => array('rows' => 10,
					  'cols' => 60)
			),
		  array('Column' => 'outcome',
			'Label' => '転帰',
			'Draw' => 'radio',
			'Enum' => array(1 => '治癒',
					2 => '軽快',
					3 => '不変',
					4 => '悪化',
					5 => '中断',
					6 => '転院',
					7 => '死亡')
			),

		  array('Column' => 'rx',
			'Label' => '退院時処方',
			'Draw' => 'textarea',
			'Option' => array('rows' => 40,
					  'cols' => 60)
			),

		  array('Column' => 'rx_days',
			'Label' => '',
			'Option' => array('validate' => 'posint',
					  'trailer' => '日分')
			),

		  array('Column' => 'special_note',
			'Label' => '特記事項',
			'Draw' => 'textarea'
			),
		  array('Column' => 'next_visit',
			'Label' => '来院予定日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'transfer',
			'Label' => '紹介',
			'Draw' => 'radio',
			'Enum' => array(1 => '他院に紹介',
					2 => '施設入所',
					3 => 'その他')
			),
		  array('Column' => 'transfer_to',
			'Label' => '紹介先機関',
			),
		  array('Column' => 'training',
			'Label' => '社会復帰プログラム',
			'Draw' => 'daysoftheweek',
			'Option' => array('dow' => array('デイケア',
							 '訪問看護',
							 'OT',
							 'その他'))
			),
		  array('Column' => 'training_other',
			'Label' => '社会復帰その他'
			),
		  array('Column' => 'written_by',
			'Label' => '記載者',
			'Draw' => 'static',
			),
		  ),
 'LCOLS' => array(
		  array('Column' => 'disease1',
			'Label'=>'診断名1'),
		  array('Column' => 'admission_date_from',
			'Label' => '入院期間',
			),
		  array('Column' => 'admission_date_to',
			'Label' => '',
			),
		  array('Column' => 'primary_dr',
			'Label' => '主治医',
			),
		  array('Column' => 'written_by',
			'Label' => '記載者',
			),
		  array('Column' => 'written_on',
			'Label' => '記載日')
		  ),
 'E_RANDOM_LAYOUT' => array
    (
     array('Column' => 'primary_dr',
	   'Label' => '主治医'),
     array('Insn' => '//'),
     array('Column' => 'disease1',
	   'Label' => '診断名1',
	   ),
     array('Column' => 'icd10_1',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'disease2',
	   'Label' => '診断名2',
	   ),
     array('Column' => 'icd10_2',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'disease3',
	   'Label' => '診断名3',
	   ),
     array('Column' => 'icd10_3',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_date_from',
	   'Label' => '入院期間(自)',
	   ),
     array('Column' => 'admission_date_to',
	   'Label' => '(至)',
	   'Draw' => 'date',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from3',
	   'Label' => '・措置(自)',
	   ),
     array('Column' => 'admission_type_to3',
	   'Label' => '(至)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from2',
	   'Label' => '・医療保護(自)',
	   ),
     array('Column' => 'admission_type_to2',
	   'Label' => '(至)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from1',
	   'Label' => '・任意(自)',
	   ),
     array('Column' => 'admission_type_to1',
	   'Label' => '(至)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from4',
	   'Label' => '・その他(自)',
	   ),
     array('Column' => 'admission_type_to4',
	   'Label' => '(至)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'comment',
	   'Label' => 'その他の場合のコメント',
	   ),
     array('Insn' => '//'),

     array('Label' => '経過'),
     array('Column' => 'history',
	   'Draw' => 'textarea',
	   'Span' => 5,
	   ),
     array('Insn' => '//'),

     array('Label' => '転帰'),
     array('Column' => 'outcome',
	   'Span' => 5
	   ),
     array('Insn' => '//'),

     array('Label' => '退院時処方'),
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

     array('Label' => '特記事項'),
     array('Column' => 'special_note',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => '来院予定日'),
     array('Column' => 'next_visit',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => '紹介'),
     array('Column' => 'transfer',
	   'Span' => 3
	   ),
     array('Insn' => '//'),

     array('Label' => '紹介先機関'),
     array('Column' => 'transfer_to',
	   ),
     array('Insn' => '//'),

     array('Label' => '社会復帰プログラム'),
     array('Column' => 'training',
	   'Span' => 2
	   ),
     array('Column' => 'training_other'),
     array('Insn' => '//'),

     array('Column' => 'written_by',
	   'Label' => '記載者',
	   'Draw' => 'static',
	   ),
     array('Column' => 'written_on',
	   'Label' => '記載日'),
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
