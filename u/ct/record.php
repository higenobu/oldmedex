<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/ct/pt.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-haikei.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-vital.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-naibun.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-heiyou.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-deviation.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-undesirable.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-undesirable2.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-test.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/ct/record-chushi.php';

/*
 *
 *  Design: this is basically a per-patient application with an unusual
 *          patient chooser.  A patient may be chosen after a clinical
 *          trial is chosen, or just type patient id and hit [SELECT]
 *          button.
 *
 *          You will have many options in flippage once a patient is chosen.
 *          
 *
 */

$_ct_record_pages = array(
			  /*
			  array('Class' => 'ct_record_haikei_edit',
				'Label' => '¾ÉÎãÊó¹ð½ñ¥È¥Ã¥×'),
			      
			      array('Class' => 'ct_record_haikei_edit',
				    'Label' => '½¤ÀµÍúÎò'),
			  */  
			      array('Class' => 'ct_record_haikei_edit',
				    'Label' => 'ÇØ·Ê'),
			      
			      array('Class' => 'ct_record_heiyou_edit',
				    'Label' => 'Ê»ÍÑÌô¡¦Ê»ÍÑÎÅË¡'),
			      
			      array('Class' => 'ct_record_vital_edit',
				    'Label' => '¥Ð¥¤¥¿¥ë¡¦¿´ÅÅ¿Þ'),
			      
			      array('Class' => 'ct_record_naibun_edit',
				    'Label' => 'ÆâÊ¬Èç¸¡ºº'),
			      
			      array('Class' => 'ct_record_test_edit',
				    'Label' => 'Î×¾²¸¡ºº·Ð²á'),
			      
			      array('Class' => 'ct_record_deviation_edit',
				    'Label' => '°Û¾ïÊÑÆ°È½Äê'),
			      
			      array('Class' => 'ct_record_undesirable_edit',
				    'Label' => 'Í­³²»ö¾Ý(¼«Â¾³Ð¾É¾õ)Áí³ç'),
			      
			      array('Class' => 'ct_record_undesirable2_edit',
				    'Label' => 'Í­³²»ö¾Ý(Î×¾²¸¡ººÃÍ)Áí³ç'),
			      
			      array('Class' => 'ct_record_chushi_edit',
				    'Label' => 'Ãæ»ß¡¦Ã¦Íî'),
			      );

function draw_flippage(&$this) {
  global $_ct_record_pages;
  // Flip Page.
  print "<table class=\"flippage\" width=\"100%\"><tr>";
  $page_num = -1;
  foreach ($_ct_record_pages as $page_def) {
    $page_num++;
    if ($page_num == $this->page) {
      print "<td class=\"focused ltcorner\">&nbsp;</td>";
      print "<td class=\"focused\">&nbsp;";
      print $page_def['Label'];
      mx_formi_hidden('page', $page_num);
      print "&nbsp;</td><td class=\"focused rtcorner\">&nbsp;</td>";
    } else {
      // A page that is hidden
      print "<td class=\"unfocused ltcorner\">&nbsp;</td>";
      print "<td class=\"unfocused\">";
      mx_formi_submit('page-to', $page_num, $page_def['Label']);
      print "</td><td class=\"unfocused rtcorner\">&nbsp;</td>";
    }
    if ($this->dpage_breaks && in_array($page_num, $this->dpage_breaks))
      print "</tr></table><table class=\"flippage\" width=\"100%\"><tr>";
  }
  print "</tr></table>\n";
}

class ct_record_application extends single_table_application {
  var $use_single_pane = 1;
  
  function ct_record_application() {
    global $_mx_template_input;
    global $_mx_use_checkin_list;
    global $_mx_auto_sodsoe_setup;
    $this->use_template = $_mx_template_input;
    $this->use_list_of_ct_patients = 1;
    $this->use_auto_sod_soe_setup = $_mx_auto_sodsoe_setup;
    $this->dpage_breaks = array(6);
    $this->page = 0;
    if (!is_null($_REQUEST['page']))
      $this->page = $_REQUEST['page'];
    if (!is_null($_REQUEST['page-to']))
      $this->page = $_REQUEST['page-to'];
    single_table_application::single_table_application();
  }
  
  function list_of_objects($prefix) {
    return new list_of_ct_pts($prefix);
  }
  
  function object_display($prefix) {
    return new ct_pt_display($prefix);
  }
  
  function object_edit($prefix) {
    global $_ct_record_pages;
    $func = $_ct_record_pages[$this->page]['Class'];
    $e = new $func($prefix, &$this);
    if (!is_null($_REQUEST['page-to'])) {
      $e->reset();
      # sod initialized earlier in sta setup_widget()
      $e->edit($this->sod->chosen());
    }
    return $e;
  }
  
  function allow_new() {
    return null;
  }

  function right_pane() {
    global $_mx_cheap_layout;

    if ($this->_browse_only) {
      if ($this->sod->chosen()) {
	 $this->sod_pane();
      }
    }
    else {
      if ($this->soe->chosen()) {
	      if (!$_mx_cheap_layout) {
		      if ($this->soe->creating())
			      mx_titlespan($this->soc_title());
		      else
			      mx_titlespan($this->soe_title());
	      }
	      draw_flippage($this);
	      $this->soe->draw();
      }
    }
  }
}


$main = new ct_record_application();
$main->main();
?>
