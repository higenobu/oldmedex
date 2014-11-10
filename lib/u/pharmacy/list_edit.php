<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class list_edit_row
{
  var $max_cols =6;
  function list_edit_row($prefix, &$config) {
    $this->prefix = $prefix;
    $this->so_config = $config;
    //_lib_so_prepare_config($this->so_config);
  }

  function enum_list($desc) {
    /* override */
    return $desc['Enum'];
  }

  function from_array($prefix, $arr) {
    $cls = $arr['__type'];
    $row = new $cls($prefix);
    foreach($row->so_config['COLS'] as $x) {
      if (is_array($x))
	$column = $x['Column'];
      else
	$column = $x;
      $row->data[$column] = $arr[$column];
    }
    return $row;
  }

  function draw_control($idx) {
    mx_formi_hidden("{$this->prefix}_${idx}___type", get_class($this));
    print '<td>';
    mx_formi_linkalike("↑", "{$this->prefix}_${idx}___up", $idx);
    print "</td>";
    print '<td>';
    mx_formi_linkalike("↓", "{$this->prefix}_${idx}___down", $idx);
    print "</td>";
    print '<td>';
    mx_formi_linkalike("×", "{$this->prefix}_${idx}___delete", $idx);
    print "</td>";
  }

  function draw($idx) {
    
    if (!is_array($this->data))
      return;
    $remains = $this->max_cols;
    print "<tr>";
    foreach($this->so_config['COLS'] as $x) {
      if ($x == '//') {
	while($remains--) 
	  print "<td>&nbsp;</td>";
	print "</tr><tr>";
	$remains = $this->max_cols;
	continue;
      }

      if (is_array($x)) {
	$column = $x['Column'];
	if ($x['Draw'])
	  $draw = "dx_" . $x['Draw'];
	else
	  $draw = "dx_text";
      }else{
	$column = $x;
	$draw = 'dx_text';
      }
      $name = "{$this->prefix}_${idx}_${column}";
      $value = $this->data[$column];
      if($draw != 'dx_hidden') {
	print "<td>";
	if (method_exists($this, $draw))
	  $this->$draw($x, $name, $value);
	else
	  simple_object_edit::$draw($x, $name, $value);
	print "</td>";
	$remains -= 1;
      }else
	simple_object_edit::$draw($x, $name, $value);
    }
    while($remains-- > 3) 
      print "<td>&nbsp;</td>";
    $this->draw_control($idx);
    $remains -= 3;
    print "</tr>";
  }

  function validate() {
    $err = array();
    foreach($this->so_config['COLS'] as $elem) {
      $opt = mx_check_option('Option', $elem);
      if (is_null($opt))
	continue;
      $constraints = mx_check_option('validate', $opt);
      if (trim($constraints) == '')
	continue;
      $col = $elem['Column'];
      $value = $this->data[$col];
      if (trim($value) == '')
	$value = NULL;
      foreach (explode(',', $constraints) as $c) {
		    print "<!-- validate $col - $c -->\n";
		    if ($c == 'nonnull') {
		      if (is_null($value))
			$err[] = "${col}は空ではいけません。";
		      continue;
		    }
		    $validator = 'mx_db_validate_' . $c;
		    if (is_callable($validator, false)) {
		      if ($c == 'range') {
			$min = mx_check_option('validate-min', $opt);
			$max = mx_check_option('validate-max', $opt);
			$st = $validator($value, $min, $max);
		      }else
			$st = $validator($value, $opt);
		      if ($st)
			$err[] = $st;
		      print "<!-- result $st -->";
		    }else
		      print "Not callable $c";
	    }

    }
    return $err;
  }
}


class list_edit
{
  var $max_cols = 6;
  function list_edit($prefix, &$config) {
    $this->prefix = $prefix;
    $this->so_config = $config;
    $this->row_prefix = $this->prefix . '_row';
    $this->data = array();
    $this->cursor_pos = 0;
    $this->populate_from_request();

  }

  function populate_from_request() {
    // find rows in REQUEST
    $form_data = array();
    foreach ($_REQUEST as $k => $v) {
      //print "$k=>$v<br>";
      $matches = NULL;
      if (preg_match("/^{$this->prefix}_row_(\d+)_(.+)$/",
		     $k, &$matches)) {
	$index = intval($matches[1]);
	$name = $matches[2];
	$form_data[$index][$name] = $_REQUEST[$k];
      }
    }
    ksort($form_data);
    $up_idx = $down_idx = $delete_idx = NULL;
    foreach($form_data as $index => $value) {
      if (!is_null($value['__up']))
	$up_idx = $value['__up'];
      if (!is_null($value['__down']))
	$down_idx = $value['__down'];
      if (!is_null($value['__delete']))
	$delete_idx = $value['__delete'];
      $this->data[] = list_edit_row::from_array($this->row_prefix, $value);
    }
    $this->cursor_pos = $_REQUEST["{$this->prefix}cursor_pos"];
    if(!is_null($up_idx)) {
      $this->move_up($up_idx);
    }
    else if(!is_null($down_idx)) {
      $this->move_down($down_idx);
    }
    else if(!is_null($delete_idx)){
      $this->delete($delete_idx);
    }
  }

  function add($element, $idx=NULL) {
    $element->max_cols = $this->max_cols;
    if(is_null($idx))
      $idx = $this->cursor_pos;
    $start = array_slice($this->data, 0, $idx);
    $end = array_slice($this->data, $idx);
    $start[] = $element;
    $this->data = array_merge($start, $end);
    $this->cursor_pos++;
  }
  
  function delete($idx) {
    unset($this->data[$idx]); 
    if($idx < $this->cursor_pos)
	  $this->cursor_pos--;
  }

  function move_up($idx) {
    if ($idx == 0)
      return;
    $x = $this->data[$idx-1];
    $this->data[$idx-1] = $this->data[$idx];
    $this->data[$idx] = $x;
  }

  function move_down($idx) {
    if ($idx == count($this->data)-1)
      return;
    $x = $this->data[$idx+1];
    $this->data[$idx+1] = $this->data[$idx];
    $this->data[$idx] = $x;
  }

  function draw_head() {
    print '<TABLE class="listofstuff">';
    if($this->header)
      print "<tr>".$this->header."</tr>";
  }
  function draw_tail() {
    print '</TABLE>';
  }
  function draw_row_head() {
    print '<TR>';
  }
  function draw_row_tail() {
    print '</TR>';
  }
  
  function draw_cursor($row_num) {
    global $__mx_formi_dek;
    $this->draw_row_head();
    $pfx = $this->prefix;
    $checked = $this->cursor_pos == $row_num ? ' CHECKED' : '';

    print <<<HTML
<TD id="${pfx}ins_pos${row_num}" valign="middle" colspan="{$this->max_cols}">
      <LABEL><INPUT TYPE="RADIO" NAME="${pfx}cursor_pos" VALUE="$row_num" ${checked} ${__mx_formi_dek}><font color="grey">ここに挿入</font></LABEL></TD>
HTML;
    $this->draw_row_tail();
    print "\n";
  }
  
  function draw() {
    $this->draw_head();
    $row_num = 0;
    if(is_array($this->data))
      foreach($this->data as $row) {
	$this->draw_cursor($row_num);
	$row->draw($row_num);
	$row_num += 1;
      }
    
    $this->draw_cursor($row_num);
    $this->draw_tail();
    mx_formi_hidden($this->prefix . 'max_row', $row_num);
  }
}

?>
