<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/admission-info.php';
$_lib_u_reception_admission_info_cfg = array
(
 'TABLE' => 'admission_info',
 'COLS' => array('患者', 'admitted_on', 'doctor',
		 'ward', 'ward_type', 'admission_type',
		 'admission_history', 'last_admission1', 'last_admission2',
		 'first_visit', 'referral', 'referred_by',
		 'estimated_duration', 'visiting', 'daycare', 'ot',
		 'home_visit', 'case_card', 'disease1', 'disease2',
		 'infection1', 'infection2', 'infection3', 'infection4',
		 'height', 'weight', 'history', 'written_by'),
 'ECOLS' => array(
		  array('Column' => 'admitted_on',
			'Label' => '入院日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date,nonnull'),
			),
		  array('Column' => 'doctor',
			'Label' => '主治医',
			'Enum' => _lib_u_test_get_doctors3(),
			'Draw' => 'enum'
			),
		  array('Column' => 'ward',
			'Label' => '病棟',
			'Enum' => mx_dbenum_patientgroup(),
			'Draw' => 'enum'
			),
		  array('Column' => 'ward_type',
			'Label' => '病棟形態',
			'Draw' => 'radio',
			'Enum' => array(1 => '開放',
					2 => '閉鎖',
					3 => '隔離')
			),
		  array('Column' => 'admission_type',
			'Label' => '入院形態',
			'Draw' => 'radio',
			'Enum' => array(1 => '任意',
					2 => '医保',
					3 => '措置')
			),
		  array('Column' => 'admission_history',
			'Label' => '当院入院歴',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'last_admission1',
			'Label' => '前回入院日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date',
					  'trailer' => 'から'),
			),
		  array('Column' => 'last_admission2',
			'Label' => '前回退院日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date',
					  'trailer' => 'まで'),
			),
		  array('Column' => 'first_visit',
			'Label' => '外来初診日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'referral',
			'Label' => '他院からの紹介状',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'referred_by',
			'Label' => '紹介元診療機関',
			),
		  array('Column' => 'estimated_duration',
			'Label' => '入院見込み',
			'Option' => array('validate' => 'posint',
					  'trailer' => '日',
					  ),
			),
		  array('Column' => 'visiting',
			'Label' => '外来通院',
			'Draw' => 'radio',
			'Enum' => array(1 => '定期的',
					2 => '不規則',
					3 => '中断',
					4 => '初診',
					)
			),
		  array('Column' => 'daycare',
			'Label' => 'デイケア参加',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'ot',
			'Label' => 'OT参加',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'home_visit',
			'Label' => '訪問看護',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'case_card',
			'Label' => 'ケースカード',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'disease1',
			'Label' => '病名',
			),
		  array('Column' => 'disease2',
			'Label' => '',
			),
		  array('Column' => 'infection1',
			'Label' => '感染症',
			),
		  array('Column' => 'infection2',
			'Label' => '',
			),
		  array('Column' => 'infection3',
			'Label' => '',
			),
		  array('Column' => 'infection4',
			'Label' => '',
			),
		  array('Column' => 'height',
			'Label' => '身長',
			'Option' => array('validate' => 'number',
					  'trailer' => 'cm')
			),
		  array('Column' => 'weight',
			'Label' => '体重',
			'Option' => array('validate' => 'number',
					  'trailer' => 'kg')
			),
		  array('Column' => 'history',
			'Label' => '入院までの経過',
			'Draw' => 'textarea',
			'Option' => array('rows' => 30,
					  'cols' => 60)
			),

		  array('Column' => 'written_by',
			'Label' => '記載者',
			'Draw' => 'static'
			),
		  ),
'DCOLS' => array(
		  array('Column' => 'admitted_on',
			'Label' => '入院日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date,nonnull'),
			),
		  array('Column' => 'doctor',
			'Label' => '主治医',
			'Enum' => _lib_u_test_get_doctors2(),
			'Draw' => 'enum'
			),
		  array('Column' => 'ward',
			'Label' => '病棟',
			'Enum' => mx_dbenum_patientgroup(),
			'Draw' => 'enum'
			),
		  array('Column' => 'ward_type',
			'Label' => '病棟形態',
			'Draw' => 'radio',
			'Enum' => array(1 => '開放',
					2 => '閉鎖',
					3 => '隔離')
			),
		  array('Column' => 'admission_type',
			'Label' => '入院形態',
			'Draw' => 'radio',
			'Enum' => array(1 => '任意',
					2 => '医保',
					3 => '措置')
			),
		  array('Column' => 'admission_history',
			'Label' => '当院入院歴',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'last_admission1',
			'Label' => '前回入院日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date',
					  'trailer' => 'から'),
			),
		  array('Column' => 'last_admission2',
			'Label' => '前回退院日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date',
					  'trailer' => 'まで'),
			),
		  array('Column' => 'first_visit',
			'Label' => '外来初診日',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'referral',
			'Label' => '他院からの紹介状',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'referred_by',
			'Label' => '紹介元診療機関',
			),
		  array('Column' => 'estimated_duration',
			'Label' => '入院見込み',
			'Option' => array('validate' => 'posint',
					  'trailer' => '日',
					  ),
			),
		  array('Column' => 'visiting',
			'Label' => '外来通院',
			'Draw' => 'radio',
			'Enum' => array(1 => '定期的',
					2 => '不規則',
					3 => '中断',
					4 => '初診',
					)
			),
		  array('Column' => 'daycare',
			'Label' => 'デイケア参加',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'ot',
			'Label' => 'OT参加',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'home_visit',
			'Label' => '訪問看護',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'case_card',
			'Label' => 'ケースカード',
			'Draw' => 'radio',
			'Enum' => array(1 => 'あり',
					2 => 'なし')
			),
		  array('Column' => 'disease1',
			'Label' => '病名',
			),
		  array('Column' => 'disease2',
			'Label' => '',
			),
		  array('Column' => 'infection1',
			'Label' => '感染症',
			),
		  array('Column' => 'infection2',
			'Label' => '',
			),
		  array('Column' => 'infection3',
			'Label' => '',
			),
		  array('Column' => 'infection4',
			'Label' => '',
			),
		  array('Column' => 'height',
			'Label' => '身長',
			'Option' => array('validate' => 'number',
					  'trailer' => 'cm')
			),
		  array('Column' => 'weight',
			'Label' => '体重',
			'Option' => array('validate' => 'number',
					  'trailer' => 'kg')
			),
		  array('Column' => 'history',
			'Label' => '入院までの経過',
			'Draw' => 'textarea',
			'Option' => array('rows' => 30,
					  'cols' => 60)
			),

		  array('Column' => 'written_by',
			'Label' => '記載者',
			'Draw' => 'static'
			),
		  ),
 'LCOLS' => array(array('Column' => 'admitted_on',
			'Label' => '入院日',
			),
		  array('Column' => 'ward',
			'Label' => '病棟',
			),
		  array('Column' => 'ward_type',
			'Label' => '病棟形態',
			'Draw' => 'radio',
			'Enum' => array(1 => '開放',
					2 => '閉鎖',
					3 => '隔離')
			),
		  array('Column' => 'admission_type',
			'Label' => '入院形態',
			'Draw' => 'radio',
			'Enum' => array(1 => '任意',
					2 => '医保',
					3 => '措置')
			),
		  array('Column' => 'written_by',
			'Label' => '記載者',
			),
		  ),
# 'D_TEMPLATE' => 'admission_info.html',
# 'E_TEMPLATE' => 'admission_info.html',
 'E_RANDOM_LAYOUT' => array
    (
     array('Column' => 'admitted_on',
	   'Label' => '入院日',
	   ),
     array('Insn' => '//'),
     array('Column' => 'doctor',
	   'Label' => '主治医',
	   ),
     array('Insn' => '//'),
     array('Column' => 'ward',
	   'Label' => '病棟',
	   ),
     array('Insn' => '//'),
     array('Label' => '病棟形態'),
     array('Column' => 'ward_type',
	   'Span' => 2
	   ),
     array('Insn' => '//'),
     array('Label' => '入院形態'),
     array('Column' => 'admission_type',
	   'Span' => 2
	   ),
     array('Insn' => '//'),
     array('Column' => 'admission_history',
	   'Label' => '当院入院歴',
	   ),
     array('Insn' => '//'),
     array('Label' => '前回入院日'),
     array('Column' => 'last_admission1'),
     array('Column' => 'last_admission2'),
     array('Insn' => '//'),
     array('Column' => 'first_visit',
	   'Label' => '外来初診日',
	   ),
     array('Insn' => '//'),
     array('Column' => 'referral',
	   'Label' => '他院からの紹介状',
	   ),
     array('Insn' => '//'),
     array('Column' => 'referred_by',
	   'Label' => '紹介元診療機関',
	   ),
     array('Insn' => '//'),
     array('Column' => 'estimated_duration',
	   'Label' => '入院見込み日数',
	   ),
     array('Insn' => '//'),
     array('Label' => '外来通院'),
     array('Column' => 'visiting',
	   'Span' => 2
	   ),
     array('Insn' => '//'),
     array('Column' => 'daycare',
	   'Label' => 'デイケア参加',
	   ),
     array('Insn' => '//'),
     array('Column' => 'ot',
	   'Label' => 'OT参加',
	   ),
     array('Insn' => '//'),
     array('Column' => 'home_visit',
	   'Label' => '訪問看護',
	   ),
     array('Insn' => '//'),
     array('Column' => 'case_card',
	   'Label' => 'ケースカード',
	   ),
     array('Insn' => '//'),
     array('Column' => 'disease1',
	   'Label' => '病名',
	   ),
     array('Insn' => '//'),
     array('Column' => 'disease2',
	   'Label' => ' '),
     array('Insn' => '//'),
     array('Column' => 'infection1',
	   'Label' => '感染症',
	   ),
     array('Insn' => '//'),
     array('Column' => 'infection2',
	   'Label' => ' '),
     array('Insn' => '//'),
     array('Column' => 'infection3',
	   'Label' => ' '),
     array('Insn' => '//'),
     array('Column' => 'infection4',
	   'Label' => ' '),
     array('Insn' => '//'),
     array('Column' => 'height',
	   'Label' => '身長',
	   ),
     array('Insn' => '//'),
     array('Column' => 'weight',
	   'Label' => '体重',
	   ),
     array('Insn' => '//'),
     array('Label' => '入院までの経過'),
     array('Column' => 'history',
	   'Span' => 2
	   ),
     array('Insn' => '//'),
     array('Column' => 'written_by',
	   'Label' => '記載者',
	   ),
     ),
 );


$_lib_u_reception_admission_info_cfg['D_RANDOM_LAYOUT'] =
  $_lib_u_reception_admission_info_cfg['E_RANDOM_LAYOUT'];


class reception_admission_info_application extends per_patient_application {
  var $use_printer = 1;

  function print_sod() {
    $this->sod->print_sod();
  }

  function list_of_objects($prefix, &$it) {
    global $_lib_u_reception_admission_info_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_reception_admission_info_cfg;
    $this->cfg_pt($cfg, $it);
    return new list_of_admissioninfos($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    global $_lib_u_reception_admission_info_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_reception_admission_info_cfg;
    $this->cfg_pt($cfg, $it);
    return new admissioninfo_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    global $_lib_u_reception_admission_info_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_reception_admission_info_cfg;
    $this->cfg_pt($cfg, $it);
    $cfg['AUTH'] = $this->auth;
    return new admissioninfo_edit($prefix, $cfg);
  }
}
?>
