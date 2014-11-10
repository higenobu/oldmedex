<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ps-prep.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/plansheet.php';

$_lib_u_everybody_pre_conference_cfg = array
(
 'TABLE' => '��Ϸײ�����',
 'Patient_ID' => NULL,
 'Patient_ObjectID' => NULL,
 'Patient_Name' => NULL,
 'DEFAULT_SORT' => '����',
 'LCOLS' => array('����', '��Ͽ��̾'),
 'ICOLS' => array('����', '����', '����̾', '������'),
 'ALLOW_SORT' => array('����' => array('����' => '"����"'),
		       '��Ͽ��̾' => array
		       ('��Ͽ��̾' => '(E."��" || E."̾")')),
 'UNIQ_ID' => 'C."ObjectID"',
 );

$_lib_u_everybody_pre_conference_stmt_head = '';

function _lib_u_everybody_pre_conference__init() {
  global $_lib_u_everybody_pre_conference_cfg,
	 $_lib_u_everybody_ps_prep_cols,
         $_lib_u_everybody_pre_conference_stmt_head;

  $other_column = array();
  foreach ($_lib_u_everybody_ps_prep_cols as $ec => $loc) {
	  foreach ($loc as $col) {
		  if (substr($col, 0, 2) == '//')
			  continue;
		  $other_column[] = 'C.' . mx_db_sql_quote_name($col);
	  }
  }
  $other_column = implode(",\n  ", $other_column);

  $_lib_u_everybody_pre_conference_stmt_head = '
SELECT C."ObjectID", C."Superseded", C."CreatedBy",
C."����", C."����", C."����̾", C."������",
(E."��" || E."̾") AS "��Ͽ��̾",
' . $other_column . '

FROM "��Ϸײ�����" AS C
LEFT JOIN "������Ģ" AS E
ON E."userid" = C."CreatedBy" AND E."Superseded" IS NULL

';

  $_lib_u_everybody_pre_conference_cfg['HSTMT'] =
      $_lib_u_everybody_pre_conference_stmt_head . ' WHERE NULL IS NULL';
  $_lib_u_everybody_pre_conference_cfg['STMT'] =
      $_lib_u_everybody_pre_conference_stmt_head .
	  ' WHERE C."Superseded" IS NULL';
  $ecol = array(array('Column' => '����', 'Draw' => 'static', 'Page' => 0),
		array('Column' => '����', 'Draw' => NULL, 'Page' => 0),
		array('Column' => '����̾', 'Draw' => 'static', 'Page' => 0),
		array('Column' => '������', 'Draw' => 'textarea',
		      'Option' => array('cols' => 50, 'rows' => 6),
		      'Page' => 0),
		);
  $dcol = array_merge(array(array('Column' => '��Ͽ��̾',
				  'Page' => 0,
				  'Label' => '��Ͽ��̾',
				  'Draw' => 'text')), $ecol);
  $_lib_u_everybody_pre_conference_cfg['DCOLS'] = $dcol;
  $_lib_u_everybody_pre_conference_cfg['ECOLS'] = $ecol;
}
_lib_u_everybody_pre_conference__init();

class list_of_everybody_pre_conferences extends list_of_ppa_objects {
  var $debug = 0;
  var $default_row_per_page = 4;

  function list_of_everybody_pre_conferences($prefix, $config=NULL) {
    global $_lib_u_everybody_pre_conference_cfg;
    $config = array_merge($_lib_u_everybody_pre_conference_cfg, $config);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $config);

  }

  function base_fetch_stmt_0() {
    return (list_of_ppa_objects::base_fetch_stmt_0() .
	    ' AND "����̾" = ' .
	    mx_db_sql_quote($this->so_config['����̾']));
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == '����') ? 1 : 0);
    }
    return $paging_orders;
  }

}

function _lib_u_everybody_pre_conference_find_config($colcfg, $col)
{
  foreach ($colcfg as $elem) {
	  if ($elem['Column'] == $col)
		  return $elem;
  }
  return NULL;
}

function _lib_u_everybody_pre_conference_munge_config(&$config, $ec) {
    global $_lib_u_everybody_ps_prep_cols;
    if (!array_key_exists($ec, $_lib_u_everybody_ps_prep_cols))
	    return;
    $pscfg = array();
    __lib_u_everybody_plansheet_cfg(&$pscfg);
    $page = 0;
    $pages = array();
    foreach ($_lib_u_everybody_ps_prep_cols[$ec] as $col) {
      if (substr($col,0,2) == '//') {
	      $pages[] = substr($col, 2);
	      $page++;
	      continue;
      }
      $c = _lib_u_everybody_pre_conference_find_config($pscfg['ECOLS'], $col);
      if (!is_null($c)) {
	      $c['Page'] = $page;
	      $config['ECOLS'][] = $c;
      }
      $c = _lib_u_everybody_pre_conference_find_config($pscfg['DCOLS'], $col);
      if (!is_null($c)) {
	      $c['Page'] = $page;
	      $config['DCOLS'][] = $c;
      }
      $ix = array_search($col, $pscfg['ICOLS']);
      if ($ix === 0 || $ix) {
	      $config['ICOLS'][] = $col;
      }
    }
    if ($page) {
	    $config['DPAGES'] = $config['EPAGES'] = $pages;
    }
}

function _lib_u_everybody_pre_conference_fetch(&$it, &$db, $id) {
  global $_lib_u_everybody_pre_conference_stmt_head;
  $stmt = ($_lib_u_everybody_pre_conference_stmt_head .
	   'WHERE C."ObjectID" = ' . mx_db_sql_quote($id));
  $it->dbglog($stmt);
  return mx_db_fetch_single($db, $stmt);
}

class everybody_pre_conference_display extends simple_object_display {

  function everybody_pre_conference_display($prefix, $config=NULL) {
    global $_lib_u_everybody_pre_conference_cfg;

    $config = array_merge($_lib_u_everybody_pre_conference_cfg, $config);
    _lib_u_everybody_pre_conference_munge_config(&$config, $config['����̾']);
    simple_object_display::simple_object_display($prefix, $config);

  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_everybody_pre_conference_fetch(&$this, &$db, $id);
  }

  function annotate_row_data(&$row) {
	  if ($row['����'] != $this->so_config['Patient_ObjectID']) {
		  if (0) {
			  var_dump($row);
			  var_dump($this->so_config['Patient_ObjectID']);
		  }
	  }
  }

}

class everybody_pre_conference_edit extends simple_object_edit {
  var $debug = 0;
  var $default_threeway_ok = 1;

  function everybody_pre_conference_edit($prefix, $config=NULL) {
    global $_lib_u_everybody_pre_conference_cfg;
    $config = array_merge($_lib_u_everybody_pre_conference_cfg, $config);
    _lib_u_everybody_pre_conference_munge_config(&$config, $config['����̾']);
    simple_object_edit::simple_object_edit($prefix, $config);
  }

  function fetch_data($id) {
    $db = mx_db_connect();
    return _lib_u_everybody_pre_conference_fetch(&$this, &$db, $id);
  }

  function annotate_row_data(&$d) {
    foreach ($d as $c => $v) {
      if (!is_null($v) && substr($v, -1, 1) == ' ') {
        $d[$c] = rtrim($v);
      }
    }
  }

  function anew_tweak($orig_id) {
    $this->data['����'] = mx_today_string();
    $this->data['����̾'] = $this->so_config['����̾'];
    $this->data['����'] = $this->so_config['Patient_ObjectID'];
    $this->annotate_row_data(&$this->data);
  }

  function _validate() {
    return _lib_u_everybody_ps_prep_validate(&$this);
  }

}

////////////////////////////////////////////////////////////////

class everybody_pre_conference_application extends per_patient_application {

  function setup() {
	  global $_lib_enum_data;

	  $ec_to_label = array('doctor_cat' => '���',
			       'nurse_cat' => '�Ǹ��',
			       'therapist_pt_cat' => 'PT',
			       'therapist_ot_cat' => 'OT',
			       'therapist_st_cat' => 'ST',
			       'msw_cat' => 'MSW',
			       'nutritionist_cat' => '���ܻ�');
	  $ec = $this->auth[2]['����'];
	  $this->ec_label = NULL;
	  foreach ($_lib_enum_data as $ec_name => $ec_array) {
		  $key = array_search($ec, $ec_array);
		  if ($ec_array[$key] == $ec &&
		      array_key_exists($ec_name, $ec_to_label)) {
			  $this->ec_label = $ec_to_label[$ec_name];
			  break;
		  }
	  }
	  $v = per_patient_application::setup();
	  return $v;
  }	

  function list_of_objects($prefix, &$it) {
    $cfg = array('����̾' => $this->ec_label);
    $this->cfg_pt(&$cfg, $it);
    $los = new list_of_everybody_pre_conferences($prefix, $cfg);
    return $los;
  }

  function object_display($prefix, &$it) {
    $cfg = array('����̾' => $this->ec_label);
    $this->cfg_pt(&$cfg, $it);
    $sod = new everybody_pre_conference_display($prefix, $cfg);
    return $sod;
  }

  function object_edit($prefix, &$it) {
    $cfg = array('����̾' => $this->ec_label);
    $this->cfg_pt(&$cfg, $it);
    $soe = new everybody_pre_conference_edit($prefix, &$cfg);
    return $soe;
  }

}
?>
