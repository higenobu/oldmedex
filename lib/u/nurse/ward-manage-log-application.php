<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/hospital-ward.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/calend.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/ward-manage-log.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/daily-log-application.php';

class wml_calendar extends simple_clickable_month_calendar_display {
  function draw() {
    mx_formi_submit('BackToWardSelection', 1,
		    '<span class="link">病棟選択に戻る</span>');
    simple_clickable_month_calendar_display::draw();
  }
}

class ward_manage_log_application extends daily_log_application {

  var $_upper = array('u/manage/index.php' => '管理アプリケーション',
		      'index.php' => '/images/top_button.png');

  function left_pane() {
    daily_log_application::left_pane();
    mx_formi_hidden('Ward', $this->ward);
    mx_formi_hidden('WardName', $this->ward_name);

    if ($this->sod->chosen()) {
	    print mx_img_url('printer.png', '印刷',
			     'onClick="printThisWindow(window)"');
    }
  }

  function setup() {
    global $_lib_u_manage_hospital_ward_cfg;

    if (mx_check_request('BackToWardSelection'))
      $this->ward = $this->ward_name = NULL;
    else {
      $this->ward = mx_check_request('Ward');
      $this->ward_name = mx_check_request('WardName');
    }

    if (! $this->ward) {
      $this->_loo_title = '病棟の選択';
      $loo_config = $_lib_u_manage_hospital_ward_cfg;
      $loo_config['LIST_IDS'] = array('ObjectID', '病棟名');
      $this->loo = new list_of_hospital_wards('low-', $loo_config);
      $this->sod = $this->soe =
	new _lib_daily_log_application_dummy();

      if ($this->loo->changed() && $this->loo->chosen()) {
	$a = mx_form_unescape_key($this->loo->chosen());
	$this->ward = $a[0];
	$this->ward_name = $a[1];
      }
    }

    if ($this->ward)
      return daily_log_application::setup();

  }

  function set_loo_calendar($prefix) {
    $this->_loo_title = $this->ward_name . '病棟管理日誌';
    $this->loo = new wml_calendar
      ('cal-', array('Ward' => $this->ward, 'WardName' => $this->ward_name));
  }

  function daily_log_display($prefix) {
    $this->_sod_title = '';
    $ymd = $this->loo->chosen();
    $config = array('Year' => $ymd[0],
		    'Month' => $ymd[1],
		    'Date' => $ymd[2],
		    'Ward' => $this->ward);
    return new ward_manage_log_display($prefix, $config);
  }

  function daily_log_edit($prefix) {
    $this->_soc_title =
      $this->_soe_title = '病棟管理日誌の編集';
    return new ward_manage_log_edit($prefix, array());
  }

}

?>
