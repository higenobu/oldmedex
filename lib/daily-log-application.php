<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/calend.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';

class _lib_daily_log_application_dummy {
  function chosen() { return NULL; }
  function changed() { return NULL; }
  function reset($id=NULL) { return NULL; }
  function draw() { return NULL; }
}

class daily_log_application extends single_table_application {

  function setup() {

    $this->set_loo_calendar('dlc-');

    if (mx_check_request('SetDate'))
      $this->loo->reset(NULL, NULL, $_REQUEST['SetDate']);

    if ($this->loo->chosen()) {

      // SOE must be created before SOD in daily log applications,
      // because SOD is shown even when there is no object created yet.
      // In the request to commit SOE for the object creation, SOD will
      // first peek the ID, which still yields NULL if we use the default
      // STA initialization order, and then upon drawing SOD will pick
      // up its data with the chosen date as the key.  This breaks the
      // translation between CreatedBy to the recording username, since
      // SOD->history() is never called in such a case.

      $this->soe = $this->daily_log_edit('dle-');

      $this->sod = $this->daily_log_display('dld-');
      if ($this->loo->changed())
	$this->sod->reset(NULL);
    }
    else {
      $this->sod = new _lib_daily_log_application_dummy();
      $this->soe = new _lib_daily_log_application_dummy();
    }

    if (array_key_exists('Edit', $_REQUEST))
      $this->soe->edit($this->sod->chosen());
    elseif (array_key_exists('History', $_REQUEST))
      $this->sod->history($_REQUEST['History']);

    return 0; // for now
  }

  function set_loo_calendar($prefix, $config=NULL) {
    $this->_loo_title = 'Calendar';
    $this->loo = new simple_clickable_month_calendar_display($prefix, $config);
  }

  function allow_new() { return 0; }
}

?>
