<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/ct.php';


class ct_select extends single_table_application {
  function ct_select () {
    single_table_application::single_table_application();
  }
}