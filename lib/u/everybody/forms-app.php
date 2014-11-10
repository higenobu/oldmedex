<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/forms.php';
//0410-2014
$__form_type = array
  (
   'form1' => array('type' => 'admission',
		    'title' => 'form1',
		    'html' => 'form01.html',
		    'ooo' => 'form21.ods',
		    ),
   'form2' => array('type' => 'admission33',
		    'title' => 'form2',
		    'html' => 'form212.html',
		    'ooo' => 'form21.ods'),
   
   'form3' => array('type' => 'condition',
		    'title' => '°åÎÅÊÝ¸îÆþ±¡¼Ô¤ÎÄê´üÉÂ¾õÊó¹ð½ñ',
		    'html' => 'condition_report.html',
		    'ooo' => 'condition_report.ods'),
   
   'form4' => array('type' => 'discharge',
		    'title' => '°åÎÅÊÝ¸îÆþ±¡¼Ô¤ÎÂà±¡ÆÏ',
		    'html' => 'delivery_of_leaving_hospital.html',
		    'ooo' => 'delivery_of_leaving_hospital.ods'),
   
   'form5' => array('type' => 'app_disabled_tky',
		    'title' => '¾ã³²¼Ô¼êÄ¢¿½ÀÁÍÑ¿ÇÃÇ½ñ(ÅìµþÅÔ)',
		    'html' => 'mentally_disabled_person_medical_report_tky.html',
		    'ooo' => 'mentally_disabled_person_medical_report_tky.ods'),
   'form6' => array('type' => 'app_disabled_kngw',
		    'title' => '¾ã³²¼Ô¼êÄ¢¿½ÀÁÍÑ¿ÇÃÇ½ñ(¿ÀÆàÀî¸©)',
		    'html' => 'mentally_disabled_person_medical_report_kngw.html',
		    'ooo' => 'mentally_disabled_person_medical_report_kngw.ods'),
'form62' => array('type' => 'app_disabled_kngw2',
		    'title' => '¾ã³²¼Ô¼êÄ¢¿½ÀÁÍÑ¿ÇÃÇ½ñ2(¿ÀÆàÀî¸©)',
		    'html' => 'mentally_disabled_person_medical_report_kngw2.html',
		    'ooo' => 'mentally_disabled_person_medical_report_kngw2.ods'),
   'form7' => array('type' => 'app_disabled_kwsk',
		    'title' => '¾ã³²¼Ô¼êÄ¢¿½ÀÁÍÑ¿ÇÃÇ½ñ(Àîºê»Ô)',
		    'html' => 'mentally_disabled_person_medical_report_kwsk.html',
		    'ooo' => 'mentally_disabled_person_medical_report_kwsk.ods'),
   'form8' => array('type' => 'app_disabled_ykhm',
		    'title' => '¾ã³²¼Ô¼êÄ¢¿½ÀÁÍÑ¿ÇÃÇ½ñ(²£ÉÍ»Ô)',
		    'html' => 'mentally_disabled_person_medical_report_ykhm.html',
		    'ooo' => 'mentally_disabled_person_medical_report_ykhm.ods'),
   
   'form9' => array('type' => 'app_pention',
		    'title' => '¾ã³²¼ÔÇ¯¶â¿½ÀÁÍÑ¿ÇÃÇ½ñ',
		    'html' => 'handicapped_person_medical_report.html',
		    'ooo' => 'handicapped_person_medical_report.ods'),

   
   'form10' => array('type' => 'app_support_tky',
		     'title' => '¼«Î©»Ù±ç¿½ÀÁÍÑ¿ÇÃÇ½ñ(ÅìµþÅÔ)',
		     'html' => 'independence_support_medical_report_tky.html',
		     'ooo' => 'independence_support_medical_report_tky.ods'),
   'form11' => array('type' => 'app_support_kngw',
		     'title' => '¼«Î©»Ù±ç¿½ÀÁÍÑ¿ÇÃÇ½ñ(¿ÀÆàÀî¸©)',
		     'html' => 'independence_support_medical_report_kngw.html',
		     'ooo' => 'independence_support_medical_report_kngw.ods'),
   'form12' => array('type' => 'app_support_kwsk',
		     'title' => '¼«Î©»Ù±ç¿½ÀÁÍÑ¿ÇÃÇ½ñ(Àîºê»Ô)',
		     'html' => 'independence_support_medical_report_kwsk.html',
		     'ooo' => 'independence_support_medical_report_kwsk.ods'),
   'form13' => array('type' => 'app_support_ykhm',
		     'title' => '¼«Î©»Ù±ç¿½ÀÁÍÑ¿ÇÃÇ½ñ(²£ÉÍ»Ô)',
		     'html' => 'independence_support_medical_report_ykhm.html',
		     'ooo' => 'independence_support_medical_report_ykhm.ods'),
'form15' => array('type' => 'karte1',
		     'title' => 'karte1',
		     'html' => 'karte1.html',
		     'ooo' => 'karte1.ods'),
   );

class everybody_forms_application extends per_patient_application {

//  0702-2012 var $use_printer =1;
var $use_printer2 =1;
  var $use_single_pane = 1;

  function everybody_forms_application($type) {
    global $__form_type;
    $this->form_cfg = $__form_type[$type];
    per_patient_application::per_patient_application();
  }

  function print_sod() {
    $this->sod->print_sod();
  }
  

  function list_of_objects($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $cfg['FORM_TYPE'] = $this->form_cfg['type'];
    return new list_of_forms($prefix, $cfg);
  }

  function object_display($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $cfg['FORM_TYPE'] = $this->form_cfg['type'];
    $cfg['D_TEMPLATE'] = $this->form_cfg['html'];
    $cfg['D_OOO_TEMPLATE'] = $this->form_cfg['ooo'];
    return new form_display($prefix, $cfg);
  }

  function object_edit($prefix, &$it) {
    $cfg = array();
    $this->cfg_pt($cfg, $it);
    $cfg['FORM_TYPE'] =  $this->form_cfg['type'];
    $cfg['E_TEMPLATE'] = $this->form_cfg['html'];
    $cfg['ICOLS'] = array('form_type');
    return new form_edit($prefix, $cfg);
  }
}
?>
