<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/nurse/fim.php';

class list_of_fims_0 extends list_of_nurse_fims {
  function list_of_fims_0($prefix, &$it) {
    $cfg = array();
    $cfg['Patient_ID'] = $it->patient_ID;
    $cfg['Patient_ObjectID'] = $it->patient_ObjectID;
    $cfg['Patient_Name'] = $it->patient_Name;
    list_of_nurse_fims::list_of_nurse_fims($prefix, $cfg);
  }
}
class fim_display_0 extends nurse_fim_display {
  function fim_display_0($prefix, &$it) {
    $cfg = array();
    $cfg['Patient_ID'] = $it->patient_ID;
    $cfg['Patient_ObjectID'] = $it->patient_ObjectID;
    $cfg['Patient_Name'] = $it->patient_Name;
    nurse_fim_display::nurse_fim_display($prefix, $cfg);
  }
}
class fim_edit_0 extends nurse_fim_edit {
  function fim_edit_0($prefix, &$it) {
    $cfg = array();
    $cfg['Patient_ID'] = $it->patient_ID;
    $cfg['Patient_ObjectID'] = $it->patient_ObjectID;
    $cfg['Patient_Name'] = $it->patient_Name;
    nurse_fim_edit::nurse_fim_edit($prefix, $cfg);
  }
}

class list_of_fims_N extends list_of_fims_0 { var $side = 'N'; }
class list_of_fims_T extends list_of_fims_0 { var $side = 'T'; }
class fim_display_N extends fim_display_0 { var $side = 'N'; }
class fim_display_T extends fim_display_0 { var $side = 'T'; }
class fim_edit_N extends fim_edit_0 { var $side = 'N'; }
class fim_edit_T extends fim_edit_0 { var $side = 'T'; }

class fim_application extends per_patient_application {

  var $side = 'T'; // or 'N'

  function setup() {

    $this->setup_patient();
    if (is_null($this->patient_ObjectID))
      return 0;

    $this->looN = new list_of_fims_N('loon-', $this);
    $this->looT = new list_of_fims_T('loot-', $this);
    $this->sodN = new fim_display_N('sodn-', $this);
    $this->sodT = new fim_display_T('sodt-', $this);

    if ($this->side == 'T') {
      $this->soe = new fim_edit_T('soe-', $this);
      $this->_soe_title = 'FIM…æ≤¡…Ω(Œ≈À°ªŒ)§Œ ‘Ω∏';
      $this->_soc_title = 'FIM…æ≤¡…Ω(Œ≈À°ªŒ)§Œ∫Ó¿Æ';
    }
    else {
      $this->soe = new fim_edit_N('soe-', $this);
      $this->_soe_title = 'FIM…æ≤¡…Ω(¥«∏Óª’)§Œ ‘Ω∏';
      $this->_soc_title = 'FIM…æ≤¡…Ω(¥«∏Óª’)§Œ∫Ó¿Æ';
    }

    if ($this->switch_patient) {
      $this->looN->reset(NULL);
      $this->looT->reset(NULL);
      $this->sodN->reset(NULL);
      $this->sodT->reset(NULL);
      $this->soe->reset(NULL);
    }

    if ($this->looN->changed() && $this->looN->chosen())
      $this->sodN->reset($this->looN->chosen());
    if ($this->looT->changed() && $this->looT->chosen())
      $this->sodT->reset($this->looT->chosen());

    if (array_key_exists('NewN', $_REQUEST) ||
	array_key_exists('NewT', $_REQUEST))
      $this->soe->anew(NULL);
    elseif (array_key_exists('NewLikeThisN', $_REQUEST))
      $this->soe->anew($this->sodN->chosen());
    elseif (array_key_exists('NewLikeThisT', $_REQUEST))
      $this->soe->anew($this->sodT->chosen());
    elseif (array_key_exists('EditN', $_REQUEST))
      $this->soe->edit($this->sodN->chosen());
    elseif (array_key_exists('EditT', $_REQUEST))
      $this->soe->edit($this->sodT->chosen());
    elseif (array_key_exists('HistoryN', $_REQUEST))
      $this->sodN->history($_REQUEST['HistoryN']);
    elseif (array_key_exists('HistoryT', $_REQUEST))
      $this->sodT->history($_REQUEST['HistoryT']);

    return 0;

  }

  function left_pane_1() {
    global $_mx_cheap_layout;

    $myname = $_mx_cheap_layout ? "" : "FIM…æ≤¡…Ω";

    print "<table width=\"100%\"><tr valign=\"top\"><td width=\"50%\">";
    mx_titlespan("$myname(¥«∏Óª’)");
    $this->looN->draw();
    print "</td><td width=\"50%\">";
    mx_titlespan("$myname(Œ≈À°ªŒ)");
    $this->looT->draw();
    print "</td></tr>\n<tr valign=\"top\">";
    foreach (array(array($this->sodN, "(¥«∏Óª’)", 'N'),
		   array($this->sodT, "(Œ≈À°ªŒ)", 'T')) as $data) {
      print "<td>";
      $sod = $data[0];
      $title = $data[1];
      $side = $data[2];
      if ($sod->chosen()) {
	$sod->draw();
	$sod_history = $sod->history();

	if ($this->allow_new() && $side == $this->side) {
	  mx_formi_submit('New'.$side, 'New', mx_img_url('new.png'),
			  'ø∑µ¨FIM…æ≤¡µ≠œø§Ú∫Ó¿Æ');
	  mx_formi_submit('NewLikeThis'.$side, 'New Like This',
			  mx_img_url('copy.png'),
			  '•≥•‘°º§∑§∆ø∑µ¨');
	}

	if ($sod_history & 2) {
	  if (($sod_history & 16) == 0 && $side == $this->side)
	    mx_formi_submit('Edit'.$side, 'Edit', mx_img_url('edit.png'),
			    $this->msg['Edit']);
	  if ($sod_history & 1)
	    mx_formi_submit('History'.$side, 'Prev',
			    mx_img_url('history.png'), $this->msg['History']);
	}
	else {
	  if (($sod_history & 5) == 5)
	    mx_formi_submit('History'.$side, 'Prev',
			    mx_img_url('history-prev.png'),
			    $this->msg['History Prev']);
	  if (($sod_history & 9) == 9)
	    mx_formi_submit('History'.$side, 'Next',
			    mx_img_url('history-next.png'),
			    $this->msg['History Next']);
	}
      } else {
	if ($this->allow_new() && $side == $this->side) {
	  mx_formi_submit('New'.$side, 'New', mx_img_url('new.png'),
			  'ø∑µ¨FIM…æ≤¡µ≠œø§Ú∫Ó¿Æ');
	}
      }
      print "</td>\n";
    }
    print "</tr></table>\n";
  }

}
?>
