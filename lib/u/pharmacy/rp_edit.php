<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/list_edit.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/direction.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/rx.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/mediserve.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/los.php';
//11-02-2014 debug
//added 
$__direction_cache = NULL;
function direction_enum($med_type=NULL) {
  global $__direction_cahce;
  if($__direction_cache)
    return $__direction_cache;

  $dbh = mx_db_connect();
  $where_med = '';
  if ($med_type)
    $where_med = 'AND type in ('.implode(',', $med_type). ')';
  $stmt = <<<SQL
    SELECT "ObjectID", "用法"
    FROM "処方箋用法"
    WHERE "Superseded" IS NULL AND (SORTORDER IS NULL OR SORTORDER >0 ) 
    $where_med
    ORDER BY SORTORDER
SQL;
  $rs = mx_db_fetch_all($dbh, $stmt);
  foreach($rs as $r)
    $__direction_cache[$r['ObjectID']] = $r['用法'];
  return $__direction_cache;
}


function _convert_checkbox_value($v) {
  return $v == 'on' ? 1 : 0;
}
$_row_med_config = array
  (
   'COLS' => array(
		   array('Column' => '薬剤',
			 'Draw' => 'hidden'
			 ),
		   array('Column' => 'レセプト電算処理システム医薬品名',
			 'Label' => '薬剤名',
			 'Draw' => 'mediserv'),
		   array('Column' => '用量',
			 'Option' => array('validate' => 'nonnull,range',
					   'validate-min' => 0),
			 ),
		   array('Column' => '包装単位単位',
			 'Label' => '単位',
			 'Draw' => 'static'),
		   '//',
		   array('Column' => '用法分類',
			 'Label' => '不均等指示',
			 ),
		   array('Column' => 'その他コメント',
			 'Label' => 'コメント',
			 'Draw' => 'textarea',
			 'Option' => array('cols' => 40,
					   'rows' => 1,
					   'vocab' => array('RXコメント'),
					   'icon' => 'vocab2.png'
					   )
			 ),
		   array('Column' => 'generic_ok',
			 'Label' => '後発品可',
			 'Draw' => 'check',
			 ),
		   )
   );

$_row_dir_config = array
  (
   'COLS' => array(
		   array('Column' => '用法',
			 'Draw' => 'enum',
			 'Enum' => direction_enum()
			 ),
		   array('Column' => '日数',
			 'Option' => array('validate' => 'nonnull,posint')
			 ),
		   array('Column' => '一包',
			 'Label' => '上記、一包化',
			 'Draw' => 'check',
			 )
		   )
   );


class row_med extends list_edit_row
{
  function row_med($prefix) {
    global $_row_med_config;
    list_edit_row::list_edit_row($prefix, $_row_med_config);
  }

  function get_data() {
    $d = $this->data;
    $d['generic_ok'] = _convert_checkbox_value($d['generic_ok']);
    $this->annotate_form_data(&$d);
    return $d;
  }

  function annotate_form_data(&$data) {
    foreach ($this->so_config['COLS'] as $v) {
	    $col = $v['Column'];
	    $opt = mx_check_option('Option', $v);
	    $constraints = mx_check_option('validate', $opt);
	    if (!array_key_exists($col, $data) || !$constraints)
		    continue;
	    mx_forme_convert($constraints, &$data, $col);
    }
  }

  function from_db_row($prefix, $d) {
    // convert from drug picker
    return list_edit_row::from_array($prefix, $d);
  }

  function from_picker($prefix, $d) {
    // convert from Rx detail row
    $row = list_edit_row::from_array($prefix, $d);
    $row->data['薬剤'] = $d['ObjectID'];
    return $row;
  }

  function dx_mediserv($x, $name, $value) {
    global $_mx_link_mediserve;
    $med = $this->data["薬剤"];
    if ($_mx_link_mediserve)
      print "<button type=\"button\" onClick=\"javascript:window.open('/svc/mediserve.php?med={$med}', '_blank'); return false;\">";
    print $value;
    if ($_mx_link_mediserve)
      print "</button>";
    mx_formi_hidden($name, $value);
  }
}

class row_dir extends list_edit_row
{
  function row_dir($prefix) {
    global $_row_dir_config;
    list_edit_row::list_edit_row($prefix, $_row_dir_config);
  }
  
  function get_data() {
    $d = $this->data;
    $this->annotate_form_data(&$d);
    return $d;
  }

  function annotate_form_data(&$data) {
    foreach ($this->so_config['COLS'] as $v) {
	    $col = $v['Column'];
	    $opt = mx_check_option('Option', $v);
	    $constraints = mx_check_option('validate', $opt);
	    if (!array_key_exists($col, $data) || !$constraints)
		    continue;
	    mx_forme_convert($constraints, &$data, $col);
    }
  }

  function from_db_row($prefix, $d) {
    // convert from direction picker
    return list_edit_row::from_array($prefix, $d);
  }

  function from_picker($prefix, $d) {
    // convert from Rx detail row
    $row = list_edit_row::from_array($prefix, $d);
    $row->data['用法'] = $d['ObjectID'];
    $row->data['用法名称'] = $d['用法'];
    return $row;
  }

  function set_med_type($d) {
    $opt = NULL;
    $k = trim($d['区分']);
    if ($k == '内') {
      $med_type[] = 0;
      $med_type[] = 1;
    }
    if ($k == '外') {
      $med_type[] = 2;
    }
    if (!$med_type)
      return;
    //
    for($i=0; $i < count($this->so_config['COLS']); $i++)
      if ($this->so_config['COLS'][$i]['Column'] == '用法') {
	$this->so_config['COLS'][$i]['Enum'] = direction_enum($med_type);
	break;
      }
  }
}

class rp_edit extends list_edit {
  function rp_edit($prefix, $cfg) {
    list_edit::list_edit($prefix, &$cfg);
    $this->drug_prefix = $prefix . 'drug-';
    $this->dir_prefix = $prefix . 'dir-';
    $dp_cfg = u_pharmacy_rx_order_drugpick_cfg($cfg['PatientInOut']);
    $this->drug_pick = new drugpick($this->drug_prefix, $dp_cfg);

    // configure direction picker 
    $this->set_pick = new list_of_pharmacy_rxs('set-list-', NULL,
						array('SETONLY' => 2,
						      'AUTH' => 0));
    $this->setup_widgets();
  }

  function db2rp_edit(&$db) {
    $prev_rp = NULL;
    foreach($db as $d) {
      if (is_null($prev_rp))
	$prev_rp = $d['RPID'];
      $d['__type'] = 'row_med';
      $this->data[] = row_med::from_db_row($this->row_prefix, $d);
      if($d['日数']) {
	$d['__type'] = 'row_dir';
	$this->data[] = row_dir::from_db_row($this->row_prefix, $d);
      }
    }
  }

  function rp_edit2db(&$db) {
    foreach($this->data as $row_obj) {
      if (get_class($row_obj) == 'row_dir') {
	$last = count($db) - 1;
	foreach($row_obj->get_data() as $k => $v)
	  $db[$last][$k] = $v;
      }else
	$db[] = $row_obj->get_data();
    }
  }

  function data_compare($curr, $data) {
    if (count($curr) != count($data))
      return 1;
    for($i=0; $i < count($curr); $i++) {
      $c = $curr[$i];
      $d = $data[$i];
      foreach($c as $k => $v) {
	if ($d[$k] != $v)
	  return 1;
      }
    }
    return 0;
  }
  function setup_widgets() {

    // new element
    $add_dir = false;
    if($this->drug_pick->chosen()) {
      $d = $this->drug_pick->chosen_data();
      $d['__type'] = 'row_med';
      $this->add(row_med::from_picker($this->row_prefix, $d));
      $this->drug_pick->selector->reset(NULL);

      $add_dir = true;
      for($i = $this->cursor_pos; $i < count($this->data); $i++) {
	if (get_class($this->data[$i]) == 'row_dir') {
	  $add_dir = false;
	  break;
	}
      }
    }

    if ($add_dir || $_REQUEST[$this->prefix . 'add_direction']) {
      $d['__type'] = 'row_dir';
      $r = row_dir::from_picker($this->row_prefix, $d);
      $r->set_med_type($d);
      $this->add($r);
      $this->cursor_pos--;
    }
    if($this->set_pick->chosen_for_do) {
      $db = mx_db_connect();
      $stmt = sprintf($this->so_config['DETAIL_TABLE_STMT'], 
		      $this->set_pick->chosen_for_do);
      $db_rows = mx_db_fetch_all($db, $stmt);
      if (!is_array($db_rows))
	return;
      $this->db2rp_edit(&$db_rows);
    }
  }

  function draw_pickers() {
    if(!$this->drug_pick->skip_category)
      $this->drug_pick->draw();
    else {
      print "<br>";
      mx_titlespan("セット選択", "small_heading");
      $this->set_pick->draw();
      print "<br>";
      $this->drug_pick->draw();
      print "<br>";
      /*
      mx_titlespan('用法選択', 'small_heading');
      $this->dir_pick->draw();
      */
    }
  }
  /*
  function draw_tail() {
    list_edit::draw_tail();
    mx_formi_submit_x($this->prefix . 'add_direction', '用法追加', '1');
  }
  */

  function validate() {
    $err = array();
    foreach($this->data as $row_obj) {
      $e = $row_obj->validate();
      if ($e)
	$err = array_merge($err, $e);
    }
    return $err;
  }
}
?>
