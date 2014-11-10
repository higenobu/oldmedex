<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';

$_lib_u_manage_multi_select_list_default_cfg = array
(
 'NOLINK' => 1,
 'MPCOL' => '联买',
 'SHOW_IDS' => array(),
 );

// You must give LIST_IDS which is an array.
// Also SHOW_IDS can be used to show currently selected ones at the bottom.
class multi_select_list extends list_of_simple_objects {

  function multi_select_list($prefix, $config) {
    global $_lib_u_manage_multi_select_list_default_cfg;
    foreach ($_lib_u_manage_multi_select_list_default_cfg as $k => $v)
      if (! array_key_exists($k, $config))
	$config[$k] = $v;

    if (array_key_exists($prefix . 'HSelect', $_REQUEST))
      $this->selected = $_REQUEST[$prefix . 'HSelect'];
    elseif (array_key_exists($prefix . 'HSelect-empty', $_REQUEST))
      $this->selected = array();
    else
      $this->selected = $config['Select'];

    if (array_key_exists($prefix . 'subpick-shown', $_REQUEST)) {
      $this->selected = array_diff($this->selected,
				   $_REQUEST[$prefix . 'subpick-shown']);
      foreach ($_REQUEST[$prefix . 'subpick-shown'] as $v) {
	if (array_key_exists($prefix . 'subpick-value-' . $v, $_REQUEST) &&
	    (array_search($v, $this->selected) === false))
	  $this->selected[] = $v;
      }
    }

    foreach ($config['LCOLS'] as $k => $v) {
      if ($v == $config['MPCOL'])
	$config['LCOLS'][$k] = array('Column' => $v,
				     'Draw' => 'multi_select');
    }

    list_of_simple_objects::list_of_simple_objects
      ($prefix, $config);

    if (array_key_exists($prefix . 'subpick-done', $_REQUEST)) {
      $d = $_REQUEST[$prefix . 'subpick-done'];
      if ($d == 'done') {
	;
      }
      elseif ($d == 'cancel') {
	$this->selected = $config['Select'];
      }
      $this->selection_changed = 1;
      $this->selection = $this->selected;
    }

  }

  function __list_id($row) {
    $cfg = $this->so_config;
    if (array_key_exists('LIST_IDS', $cfg)) {
      $o = array();
      foreach ($cfg['LIST_IDS'] as $col)
	$o[] = $row[$col];
      $oid = mx_form_escape_key($o);
    } else
      $oid = $row['ObjectID'];
    return $oid;
  }

  function annotate_row_data(&$row) {
    $cfg = $this->so_config;
    $s = $this->selected;
    $sr = $this->__list_id($row);
    $row['SelectReturn'] = $sr;
    $row[$cfg['MPCOL']] = !(false === array_search($sr, $s));
  }

  function dx_multi_select($desc, $value, $row) {
    $v = $this->__list_id($row);
    mx_formi_hidden($this->prefix . 'subpick-shown' . '[]', $v);
    mx_formi_checkbox($this->prefix . 'subpick-value-' . $v, $value);
  }

  function draw() {
    $cfg = $this->so_config;
    mx_titlespan($cfg['Title']);
    print "<br />\n";
    if (count($this->selected) == 0)
      mx_formi_hidden($this->prefix . 'HSelect-empty', 1);
    else
      foreach ($this->selected as $ss)
	mx_formi_hidden($this->prefix . 'HSelect[]', $ss);

    mx_formi_submit($this->prefix . 'subpick-done', 'done',
		    '<span class="link">联买窗位</span>');
    mx_formi_submit($this->prefix . 'subpick-done', 'cancel',
		    '<span class="link">联买面贿</span>');
    list_of_simple_objects::draw();

    if (count($cfg['SHOW_IDS'])) {
      print "<br />联买面: <table>\n";
      foreach ($this->selected as $ss) {
	$a = mx_form_unescape_key($ss);
	print "<tr>";
	$ix = 0;
	foreach ($cfg['LIST_IDS'] as $col) {
	  if ((array_search($col, $cfg['SHOW_IDS']) === false) &&
	      ! $this->debug)
	    ;
	  else
	    print "<td>" . htmlspecialchars($a[$ix]) . "</td>";
	  $ix++;
	}
	print "</tr>\n";
      }
      print "</table>\n";
    }
    mx_formi_submit($this->prefix . 'subpick-done', 'done',
		    '<span class="link">联买窗位</span>');
    mx_formi_submit($this->prefix . 'subpick-done', 'cancel',
		    '<span class="link">联买面贿</span>');
  }

}
?>
