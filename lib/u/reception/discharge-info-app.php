<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/discharge-info.php';

function en($pfx, $name) {
  return $pfx . mx_form_encode_name($name);
}

$_lib_u_reception_discharge_info_cfg = array
(
 'TABLE' => 'discharge_info',
 'COLS' => array('´µ¼Ô', 'disease1','disease2','disease3',
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
			'Label' => '¼ç¼£°å',
		       ),
		  array('Column' => 'written_on',
			'Label' => 'µ­ºÜÆü',
			'Draw' => 'date'
		       ),
		  array('Column' => 'icd10_1',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease1'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease1',
			'Label' => '¿ÇÃÇÌ¾1',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'icd10_2',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease2'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease2',
			'Label' => '¿ÇÃÇÌ¾2',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'icd10_3',
			'Label' => 'ICD10',
			'Draw' => 'icd10',
			'Option' => array('disease' => en('soe-', 'disease3'),
					  'add_id' => 1)
			),
		  array('Column' => 'disease3',
			'Label' => '¿ÇÃÇÌ¾3',
			'Option' => array('add_id' => 1)
			),
		  array('Column' => 'admission_date_from',
			'Label' => 'Æþ±¡´ü´Ö(¼«)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_date_to',
			'Label' => 'Æþ±¡´ü´Ö(»ê)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from3',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦Á¼ÃÖ(¼«)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to3',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦Á¼ÃÖ(»ê)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from2',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦°åÎÅÊÝ¸î(¼«)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to2',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦°åÎÅÊÝ¸î(»ê)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from1',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦Ç¤°Õ(¼«)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to1',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦Ç¤°Õ(»ê)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_from4',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦¤½¤ÎÂ¾(¼«)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'admission_type_to4',
			'Label' => 'Æþ±¡·ÁÂÖ¡¦¤½¤ÎÂ¾(»ê)',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'comment',
			'Label' => '¥³¥á¥ó¥È',
			),
		  array('Column' => 'history',
			'Label' => '·Ð²á',
			'Draw' => 'textarea',
			'Option' => array('rows' => 10,
					  'cols' => 60)
			),
		  array('Column' => 'outcome',
			'Label' => 'Å¾µ¢',
			'Draw' => 'radio',
			'Enum' => array(1 => '¼£Ìþ',
					2 => '·Ú²÷',
					3 => 'ÉÔÊÑ',
					4 => '°­²½',
					5 => 'ÃæÃÇ',
					6 => 'Å¾±¡',
					7 => '»àË´')
			),

		  array('Column' => 'rx',
			'Label' => 'Âà±¡»þ½èÊý',
			'Draw' => 'textarea',
			'Option' => array('rows' => 40,
					  'cols' => 60)
			),

		  array('Column' => 'rx_days',
			'Label' => '',
			'Option' => array('validate' => 'posint',
					  'trailer' => 'ÆüÊ¬')
			),

		  array('Column' => 'special_note',
			'Label' => 'ÆÃµ­»ö¹à',
			'Draw' => 'textarea'
			),
		  array('Column' => 'next_visit',
			'Label' => 'Íè±¡Í½ÄêÆü',
			'Draw' => 'date',
			'Option' => array('ime' => 'disabled',
					  'validate' => 'date'),
			),
		  array('Column' => 'transfer',
			'Label' => '¾Ò²ð',
			'Draw' => 'radio',
			'Enum' => array(1 => 'Â¾±¡¤Ë¾Ò²ð',
					2 => '»ÜÀßÆþ½ê',
					3 => '¤½¤ÎÂ¾')
			),
		  array('Column' => 'transfer_to',
			'Label' => '¾Ò²ðÀèµ¡´Ø',
			),
		  array('Column' => 'training',
			'Label' => '¼Ò²ñÉüµ¢¥×¥í¥°¥é¥à',
			'Draw' => 'daysoftheweek',
			'Option' => array('dow' => array('¥Ç¥¤¥±¥¢',
							 'Ë¬Ìä´Ç¸î',
							 'OT',
							 '¤½¤ÎÂ¾'))
			),
		  array('Column' => 'training_other',
			'Label' => '¼Ò²ñÉüµ¢¤½¤ÎÂ¾'
			),
		  array('Column' => 'written_by',
			'Label' => 'µ­ºÜ¼Ô',
			'Draw' => 'static',
			),
		  ),
 'LCOLS' => array(
		  array('Column' => 'disease1',
			'Label'=>'¿ÇÃÇÌ¾1'),
		  array('Column' => 'admission_date_from',
			'Label' => 'Æþ±¡´ü´Ö',
			),
		  array('Column' => 'admission_date_to',
			'Label' => '',
			),
		  array('Column' => 'primary_dr',
			'Label' => '¼ç¼£°å',
			),
		  array('Column' => 'written_by',
			'Label' => 'µ­ºÜ¼Ô',
			),
		  array('Column' => 'written_on',
			'Label' => 'µ­ºÜÆü')
		  ),
 'E_RANDOM_LAYOUT' => array
    (
     array('Column' => 'primary_dr',
	   'Label' => '¼ç¼£°å'),
     array('Insn' => '//'),
     array('Column' => 'disease1',
	   'Label' => '¿ÇÃÇÌ¾1',
	   ),
     array('Column' => 'icd10_1',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'disease2',
	   'Label' => '¿ÇÃÇÌ¾2',
	   ),
     array('Column' => 'icd10_2',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'disease3',
	   'Label' => '¿ÇÃÇÌ¾3',
	   ),
     array('Column' => 'icd10_3',
	   'Label' => 'ICD10',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_date_from',
	   'Label' => 'Æþ±¡´ü´Ö(¼«)',
	   ),
     array('Column' => 'admission_date_to',
	   'Label' => '(»ê)',
	   'Draw' => 'date',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from3',
	   'Label' => '¡¦Á¼ÃÖ(¼«)',
	   ),
     array('Column' => 'admission_type_to3',
	   'Label' => '(»ê)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from2',
	   'Label' => '¡¦°åÎÅÊÝ¸î(¼«)',
	   ),
     array('Column' => 'admission_type_to2',
	   'Label' => '(»ê)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from1',
	   'Label' => '¡¦Ç¤°Õ(¼«)',
	   ),
     array('Column' => 'admission_type_to1',
	   'Label' => '(»ê)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'admission_type_from4',
	   'Label' => '¡¦¤½¤ÎÂ¾(¼«)',
	   ),
     array('Column' => 'admission_type_to4',
	   'Label' => '(»ê)',
	   ),
     array('Insn' => '//'),

     array('Column' => 'comment',
	   'Label' => '¤½¤ÎÂ¾¤Î¾ì¹ç¤Î¥³¥á¥ó¥È',
	   ),
     array('Insn' => '//'),

     array('Label' => '·Ð²á'),
     array('Column' => 'history',
	   'Draw' => 'textarea',
	   'Span' => 5,
	   ),
     array('Insn' => '//'),

     array('Label' => 'Å¾µ¢'),
     array('Column' => 'outcome',
	   'Span' => 5
	   ),
     array('Insn' => '//'),

     array('Label' => 'Âà±¡»þ½èÊý'),
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

     array('Label' => 'ÆÃµ­»ö¹à'),
     array('Column' => 'special_note',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => 'Íè±¡Í½ÄêÆü'),
     array('Column' => 'next_visit',
	   'Span' => 2
	   ),
     array('Insn' => '//'),

     array('Label' => '¾Ò²ð'),
     array('Column' => 'transfer',
	   'Span' => 3
	   ),
     array('Insn' => '//'),

     array('Label' => '¾Ò²ðÀèµ¡´Ø'),
     array('Column' => 'transfer_to',
	   ),
     array('Insn' => '//'),

     array('Label' => '¼Ò²ñÉüµ¢¥×¥í¥°¥é¥à'),
     array('Column' => 'training',
	   'Span' => 2
	   ),
     array('Column' => 'training_other'),
     array('Insn' => '//'),

     array('Column' => 'written_by',
	   'Label' => 'µ­ºÜ¼Ô',
	   'Draw' => 'static',
	   ),
     array('Column' => 'written_on',
	   'Label' => 'µ­ºÜÆü'),
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
