<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/calend.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/hospital-manage-log.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/daily-log-application.php';

class hospital_manage_log_application extends daily_log_application {

  var $_upper = array('u/manage/index.php' => '´ÉÍý¥¢¥×¥ê¥±¡¼¥·¥ç¥ó',
		      'index.php' => '/images/top_button.png');

  function set_loo_calendar($prefix) {
    $this->_loo_title = 'ÉÂ±¡´Ç¸î´ÉÍýÆü»ï';
    $cfg = array();
    $this->loo = new simple_clickable_month_calendar_display('cal-', $cfg);
  }

  function left_pane() {
    daily_log_application::left_pane();

    if ($this->sod->chosen()) {
	    print mx_img_url('printer.png', '°õºþ',
			     'onClick="printThisWindow(window)"');
    }
  }

  function daily_log_display($prefix) {
    $this->_sod_title = '';
    $ymd = $this->loo->chosen();
    $config = array('Year' => $ymd[0],
		    'Month' => $ymd[1],
		    'Date' => $ymd[2]);
    return new hospital_manage_log_display($prefix, $config);
  }

  function daily_log_edit($prefix) {
    $this->_soc_title =
      $this->_soe_title = 'ÉÂ±¡´Ç¸î´ÉÍýÆü»ï¤ÎÊÔ½¸';
    return new hospital_manage_log_edit($prefix, array());
  }

}

?>
