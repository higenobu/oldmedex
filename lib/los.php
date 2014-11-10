<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/so.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/list_of_blah.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';

// Simple Object has been split into five files:
// so.php contains the services common to all three classes.
// los.php contains list_of_simple_objects.
// sod.php contains simple_object_display.
// soe.php contains simple_object_edit.
// simple_object.php is for backward compatibility to include all of them.

class list_of_simple_objects extends list_of_blah {

  var $boundary_every_nth_line = 0;
  var $use_refresh = 0;

  function list_of_simple_objects($prefix, &$config) {
    global $_mx_boundary_every_nth_line;

    $this->boundary_every_nth_line = $_mx_boundary_every_nth_line;

    list_of_blah::list_of_blah($prefix);
    $this->so_config = $config;
    _lib_so_prepare_config($this->so_config);

    if (array_key_exists('ALLOW_SORT', $this->so_config) &&
	$this->so_config['ALLOW_SORT']) {
      $as = $this->so_config['ALLOW_SORT'];
      if (! is_array($as)) {
	$as = array();
	foreach ($this->so_config['LCOLS'] as $desc) {
	  $c = $desc['Column'];
	  $as[$c] = array($c => mx_db_sql_quote_name($c));
	}
      }
      $this->allow_sort = $as;
    } else
      $this->allow_sort = NULL;

    $sk = NULL;
    if ($this->allow_sort) {
      if (count($this->allow_sort) == 1) {
	// If there is only one, use it always without bothering the user.
	foreach ($this->allow_sort as $k => $v)
	  $sk = $k;
      }
      elseif (array_key_exists($prefix . 'sort-on-select', $_REQUEST)) {
	$sk = $_REQUEST[$prefix . 'sort-on-select'];
	$this->reset($this->context);
      }
      elseif (array_key_exists($prefix . 'sort-on', $_REQUEST))
	$sk = $_REQUEST[$prefix . 'sort-on'];
      elseif ($this->so_config['DEFAULT_SORT'] &&
	      array_key_exists
	      ($this->so_config['DEFAULT_SORT'], $this->allow_sort))
	$sk = $this->so_config['DEFAULT_SORT'];
    }
    $this->sort_on = $sk;

    if (array_key_exists('ENABLE_QBE', $this->so_config) &&
	$this->so_config['ENABLE_QBE']) {
      $eq = $this->so_config['ENABLE_QBE'];
      if (! is_array($eq)) {
	$eq = array();
	foreach ($this->so_config['LCOLS'] as $desc) {
	  $eq[] = $desc['Column'];
	}
      }
      $ct = count($eq);
      for ($ix = 0; $ix < $ct; $ix++) {
	if (! is_array($eq[$ix])) {
	  $d = $eq[$ix];
	  if (_lib_so__is_table_control($d))
	    continue;
	  $eq[$ix] = array('Column' => $d);
	}
	if (! array_key_exists('Compare', $eq[$ix]))
	  $eq[$ix]['Compare'] = mx_db_sql_quote_name($eq[$ix]['Column']);
	if (! array_key_exists('Draw', $eq[$ix]))
	  $eq[$ix]['Draw'] = 'text';
      }
      $this->enable_qbe = $eq;
    } else
      $this->enable_qbe = NULL;

    $this->qbe_where = $this->qbe_twhere = NULL;
    $this->qbe_state = NULL;
    if ($this->enable_qbe) {

      // Draw will draw the following in this case:
      // (1) ``click here to limit display'' -- this is shown at the very
      //     beginning.  Nothing else related to QBE is drawn in this case.
      // (2) After (1) is clicked:
      //  - ``click here to unlimit'' to go back to the initial state.
      //  - current limit criteria (QBE will be anded together).
      //  - QBE input fields.
      //  - ``click to limit further limit with these values''.
      //  - ``click here to unlimit but stay in QBE mode''.

      // $this->qbe_state == NULL - no QBE
      // $this->qbe_state == 1 - with QBE but not in effect (i.e. (1) above)
      // $this->qbe_state == 2 - with QBE.

      // When $this->qbe_state == 2,
      // $this->qbe_current : current limitation expression.

      // In $_REQUEST[] we will encode the above with:

      // [hidden] $prefix . 'qbe-state'
      // [hidden] $prefix . 'qbe-current'

      // [submit] $prefix . 'qbe-toggle-state': toggle between (1) and (2)
      // [input ] $prefix . 'qbe-set-' . mx_form_escape_key($label)
      // [submit] $prefix . 'qbe-limit-further'
      // [submit] $prefix . 'qbe-clear'

      $qc00 = NULL;
      /* Pick up saved query, if defined */
      if ($this->so_config['SAVE_QBE_STATE']) {
	      $qc00 = mx_get_qbe_state($this->so_config['SAVE_QBE_STATE']);
      }

      /* If default exists, and saved one does not, use it */
      if (array_key_exists('DEFAULT_QBE', $this->so_config) &&
	  is_array($this->so_config['DEFAULT_QBE']) &&
	  (!is_array($qc00) || !count($qc00))) {
	      $qc00 = $this->so_config['DEFAULT_QBE'];
      }

      if (array_key_exists($prefix . 'qbe-state', $_REQUEST) &&
	  (1 <= ($qbe_state = $_REQUEST[$prefix . 'qbe-state'])) &&
	  $qbe_state <= 2)
	      ;
      elseif (is_array($qc00) && count($qc00))
	      /* If search criteria exists, use QBE */
	      $qbe_state = 2;
      else
	      $qbe_state = 1;

      if (array_key_exists($prefix . 'qbe-toggle-state', $_REQUEST)) {
	$qbe_state = 3 - $qbe_state;
	$this->reset($this->context);
      }

      $qc0 = $qc00;
      if ($qbe_state == 2) {
	// QBE in effect.
	if (array_key_exists($prefix . 'qbe-clear', $_REQUEST)) {
		/* Clear always goes back to the built-in default */
		$qc0 = $this->so_config['DEFAULT_QBE'];
	} else if (array_key_exists($prefix . 'qbe-current', $_REQUEST)) {
		$qc0 = unserialize($_REQUEST[$prefix . 'qbe-current']);
	}
	if (! is_array($qc0)) {
		$qc0 = array();
	}
	if (array_key_exists($prefix . 'qbe-limit-further', $_REQUEST)) {
	  foreach ($this->enable_qbe as $desc) {
	    if (! is_array($desc))
	      continue;
	    $col = $desc['Column'];
	    $xqkey = $prefix . 'qbe-set-' . mx_form_escape_key(array($col));
	    if (array_key_exists($xqkey, $_REQUEST))
	      $qc0[] = array($col, trim($_REQUEST[$xqkey]));
	  }
	  $this->reset($this->context);
	}

	// Sanitize it
	$singleton = array();
	$singleton_rejects = array();
	$qc = array();
	foreach ($qc0 as $qbe) {
	  $xq = $qbe[1];
	  $found = NULL;
	  foreach ($this->enable_qbe as $desc) {
	    if ($qbe[0] == $desc['Column']) {
	      $found = $desc;
	      break;
	    }
	  }
	  if (is_null($found))
	    continue; // Being defensive here

	  if ($found['Singleton']) {
	    // Empty input in Singleton means "do not limit with this column".
	    if (trim($xq) == '')
	      $singleton_rejects[$qbe[0]] = 1;
	    else
	      $singleton[$qbe[0]] = $qbe;
	  }
	  elseif (trim($xq) == '')
	    continue;
	  else
	    $qc[] = $qbe;
	}
	$qc = array_merge($qc, array_values($singleton));

	$this->qbe_current = array();
	$where = array();
	$twhere = array();
	foreach ($qc as $qbe) {
	  if (array_key_exists($qbe[0], $singleton_rejects))
	    continue;

	  $xq = $qbe[1];
	  $found = NULL;
	  foreach ($this->enable_qbe as $desc) {
	    if ($qbe[0] == $desc['Column']) {
	      $found = $desc;
	      break;
	    }
	  }
	  if (is_null($found))
	    continue; // Being extra defensive here

	  if ($found['CompareMethod']) {
		  $cm = 'cm_' . $found['CompareMethod'];
		  $this->$cm($qbe, $found, &$twhere, &$where);
	  }
	  else {
		  $this->translate_qbe_atom($xq,
					    mx_db_sql_quote_name($qbe[0]),
					    $found['Compare'],
					    $found,
					    &$twhere,
					    &$where);
	  }
	  $this->qbe_current[] = $qbe;
	}
	$this->qbe_where = $where;
	$this->qbe_twhere = $twhere;
      }
      $this->qbe_state = $qbe_state;
    }

    if (count($this->so_config['LCHOICE'])) {
      if (array_key_exists($prefix . 'widen-select', $_REQUEST) &&
	  array_key_exists($_REQUEST[$prefix . 'widen-select'],
			   $this->so_config['LCHOICE']))
	$this->widen = $_REQUEST[$prefix . 'widen-select'];
      elseif (array_key_exists($prefix . 'widen', $_REQUEST) &&
	  array_key_exists($_REQUEST[$prefix . 'widen'],
			   $this->so_config['LCHOICE']))
	$this->widen = $_REQUEST[$prefix . 'widen'];
      else {
	// the original code was;
	// $this->widen = NULL;
	// take 1st item as a default choice rather than NULL.
	foreach($this->so_config['LCHOICE'] as $k=>$v) {
	  $this->widen = $k;
	  break;
	}
      }
    }

    if (array_key_exists('ROW_PER_PAGE', $this->so_config))
        $this->row_per_page = $this->so_config['ROW_PER_PAGE'];

    if (array_key_exists('SCROLLABLE_HEIGHT', $this->so_config) &&
	$this->so_config['SCROLLABLE_HEIGHT']) {
        $this->scrollable_height = $this->so_config['SCROLLABLE_HEIGHT'];
    }

  }

  function _nk($qv, $desc) {
    if(is_null($desc['NormalizeCompareKey']) )
      return $qv;
    $x = mb_convert_kana($qv, $desc['NormalizeCompareKey'], 'euc');
    return str_replace(
       array('ァ','ィ','ゥ','ェ','ォ','ッ','ャ','ュ','ョ','ヮ','ガ','ギ','グ','ゲ','ゴ','ザ','ジ','ズ','ゼ','ゾ','ダ','ヂ','ヅ','デ','ド','バ','パ','ビ','ピ','ブ','プ','ベ','ペ','ボ','ポ'),
       array('ア','イ','ウ','エ','オ','ツ','ヤ','ユ','ヨ','ワ','カ','キ','ク','ケ','コ','サ','シ','ス','セ','ソ','タ','チ','ツ','テ','ト','ハ','ハ','ヒ','ヒ','フ','フ','ヘ','ヘ','ホ','ホ'),
       $x);
  }


	function translate_qbe_atom($query_string,
				    $human_readable_query_column_name,
				    $query_column_name,
				    $desc,
				    &$twhere,
				    &$where) {
		$wadd = $twadd = $not = NULL;
		$xq = $query_string;
		$tqn = $human_readable_query_column_name;
		$qn = $query_column_name;
		if (strstr(mx_check_option('NormalizeCompareKey', $desc), 'C'))
		    $qn = sprintf("to_regular(to_kana(%s))" , $qn);
		if (substr($xq, 0, 1) == '!') {
			$not = 1;
			$xq = substr($xq, 1);
		}
		$match = array();
		if (preg_match('/^([<>=~^](?:[<>=~^])?)(.*)$/', $xq,
			       &$match))
			;
		elseif (preg_match('/^(.*?)\.\.(.*?)$/', $xq, &$match)) {
			$match[3] = $match[2];
			$match[2] = $match[1];
			$match[1] = '..';
		} else {
			$match[1] = '~';
			$match[2] = $xq;
		}
		// value given by the end user to match against
		$qv = mx_db_sql_quote($this->_nk($match[2], $desc));
		$op = $match[1];
		switch ($op) {
		case '=': case '>=': case '>': case '<': case '<=': case '<>':
			$twadd = "$tqn $op $qv";
			$wadd = "$qn $op $qv";
			break;
		case '^': // begins with
			$qv = mx_db_sql_quote($this->_nk($match[2],$desc) . '%');
			$twadd = "$tqn LIKE $qv";
			$wadd = "$qn LIKE $qv";
			break;
		case '~': // contains
			$qv = mx_db_sql_quote('%' . $this->_nk($match[2],$desc) . '%');
			$twadd = "$tqn LIKE $qv";
			$wadd = "$qn LIKE $qv";
			break;
		case '~~': // any SQL "LIKE" match
			$qv = mx_db_sql_quote($this->_nk($match[2],$desc));
			$twadd = "$tqn LIKE $qv";
			$wadd = "$qn LIKE $qv"; break;
		case '..': // between
			$qv1 = mx_db_sql_quote($this->_nk($match[2],$desc));
			$qv2 = mx_db_sql_quote($this->_nk($match[3],$desc));
			# reasonably big character in character set
			$qv3 = mx_db_sql_quote($this->_nk($match[3],$desc) . '煕'
);
			$twadd = "$tqn BETWEEN $qv1 AND $qv2";
			$wadd = "$qn BETWEEN $qv1 AND $qv3";
			break;
		}
		if (is_null($wadd))
			return;
		if ($not) {
			$where[] = "NOT ($wadd)";
			$twhere[] = "NOT ($twadd)";
		} else {
			$where[] = $wadd;
			$twhere[] = $twadd;
		}
	}


  function cm_enum_single_char($qbe, $desc, &$twhere, &$where) {
	  // $qbe[0] is Column name, $qbe[1] is UI input.
	  if (trim($qbe[1]) == '') return;
	  $qn = $desc['Compare'];
	  $tqn = mx_db_sql_quote_name($qbe[0]);
	  if (mb_strlen($qbe[1], 'EUC-JP') == 1) {
		  $wadd = "$qn = " . mx_db_sql_quote($qbe[1]);
	  }
	  else {
		  $sa = array();
		  for ($ix = 0; $ix < mb_strlen($qbe[1], 'EUC-JP'); $ix++)
			  $sa[] = mx_db_sql_quote(mb_substr($qbe[1],
							    $ix, 1, 'EUC-JP'));
		  $w = ('IN ( ' .
			implode(', ', $sa) .
			' )');
		  $wadd = "$qn $w";
	  }
	  $twadd = "$tqn = " . mx_db_sql_quote($desc['Enum'][$qbe[1]]);
	  $where[] = $wadd;
	  $twhere[] = $twadd;
  }

  function cm_multi_column_select($qbe, $desc, &$twhere, &$where) {
    if (trim($qbe[1]) == '') return;

    $local_twhere = array();
    $local_where = array();
    $this->translate_qbe_atom($qbe[1],
			      mx_db_sql_quote_name($qbe[0]),
			      $desc['Compare'],
			      $desc,
			      &$local_twhere,
			      &$local_where);
    
    
    if(is_array($desc['CompareAlternateColumn'])){
      foreach($desc['CompareAlternateColumn'] as $k)
	$this->translate_qbe_atom($qbe[1],
				  mx_db_sql_quote_name($qbe[0]),
				  $k,
				  $desc,
				  &$local_twhere,
				  &$local_where);
    }
      
    $wadd = "( " . implode(' or ', $local_where) . "  )";
    $twadd = $wadd;
      
    $where[] = $wadd;
    $twhere[] = $twadd;

  }

  function cm_zeropad_exact($qbe, $desc, &$twhere, &$where) {
	  $given = trim($qbe[1]);
	  if ($given == '')
		  return;
	  $qn = $desc['Compare'];
	  $tqn = mx_db_sql_quote_name($qbe[0]);
	  $padwidth = mx_check_option('ZeroPad', $desc);
	  if ($padwidth)
		  $given = mx_zeropad($given, $padwidth);
	  $twhere[] = "$tqn = '$given'";
	  $where[] = "$qn = " . mx_db_sql_quote($given);
  }

  function cm_number($qbe, $desc, &$twhere, &$where) {
	  $given = trim($qbe[1]);
	  if ($given == '' ||!is_numeric($given))
		  return;
	  $qn = $desc['Compare'];
	  $tqn = mx_db_sql_quote_name($qbe[0]);
	  $given = intval($given);
	  $twhere[] = "$tqn = $given";
	  $where[] = "$qn::int = $given";
  }

  function base_fetch_stmt_1($i) { // override
    return 'the subclass did not override base_fetch_stmt_1() but
specified LCHOICE.';
  }

  function base_fetch_stmt_0() {
     return $this->so_config['STMT'];
  }

  function base_fetch_stmt() {
    if (count($this->so_config['LCHOICE']))
      $base = $this->base_fetch_stmt_1($this->widen);
    else
      $base = $this->base_fetch_stmt_0();

    if (is_null($this->qbe_where) || count($this->qbe_where) == 0)
      return $base;
    $stmt = ($base .
	     " AND\n" . implode("  \nAND  ", $this->qbe_where));



    if ($this->debug)
      print "<!-- " . $stmt . "-->\n";
    return $stmt;
  }

  function lost_selection() {
    return 0;
  }

  function dq_text($lbl, $xqk, $desc, $value) {
    global $__mx_formi_inc_search;
    $option = mx_check_option('Option', $desc, array());
    if ($desc['IncSearch'])
      if(is_array($desc['IncSearch']))
	$option['key_handler'] = sprintf($__mx_formi_inc_search,
					$desc['IncSearch']['IncSearch'],
					$desc['IncSearch']['Prefix']);
      else
	$option['key_handler'] = sprintf($__mx_formi_inc_search,
					$desc['IncSearch'],
					$this->prefix);
    mx_formi_text($xqk, $value, $option);
  }

  function enum_list($desc) {
    /* override */
    return $desc['Enum'];
  }

  function dq_enum($lbl, $xqk, $desc, $value) {
    $enum = $this->enum_list($desc);
    mx_formi_select($xqk, $value, $enum);
  }

  function __format_class_list($class_list) {
    if (is_null($class_list) ||
	(is_array($class_list) && !count($class_list)))
	    return '';
    if (is_array($class_list))
      return ' class="' . implode(' ', $class_list) . '"';
    else
      return ' class="' . $class_list . '"';
  }

 function table_control_head($sp, $desc, $thtd, $extra=NULL) {
    $option = mx_check_option('Option', $desc);
    $cls = mx_check_option('Class', $option);
    if (is_array($cls))
	    ;
    else if (!$cls)
	    $cls = array();
    else
	    $cls = explode(' ', $cls);
    if ($extra)
	    $cls[] = $extra;
    $cls = $this->__format_class_list($cls);

    $wi = mx_check_option('Width', $desc);
    $wi = $wi ? " width=\"$wi\"" : "";

    $sp = (array_key_exists('Span', $desc)) ? $desc['Span'] : $sp;
    $sp = (1 < $sp) ? " colspan=\"$sp\"" : '';

    $vsp = (array_key_exists('VSpan', $desc)) ? $desc['VSpan'] : 0;
    $vsp = (1 < $vsp) ? " rowspan=\"$vsp\"" : '';

    $al = (array_key_exists('Align', $desc)) ? $desc['Align'] : '';
    $al = ($al != '') ? " align=\"$al\"" : '';

    $val = (array_key_exists('VAlign', $desc)) ? $desc['VAlign'] : '';
    $val = ($val != '') ? " valign=\"$val\"" : '';

    print "<$thtd$cls$wi$val$al$sp$vsp>";
  }

  function table_control_tail($span, $desc, $thtd, $row_decoration=NULL) {
    $decoration = is_null($row_decoration) ? "" : " $row_decoration";
//10-21-2012    
if ($desc == '//') {
      print "</tr>\n<tr$decoration>";
	 
}
	
//10-21-2012	 
    elseif ($desc == '  ') {
      if (1 < $span) print "<$thtd colspan=\"$span\">";
      else print "<$thtd>";
      print "&nbsp;</$thtd>\n";
    }
    elseif (strspn($desc, '0123456789') == strlen($desc) && strlen($desc))
      $span = $desc;
    return $span;
  }

  function qbe_limit_too_vague() {
	  // override -- see draw() below
	  return 0;
  }

  function draw_qbe_entry_form() {
    global $__uiconfig_kbd;
    global $_mx_inc_kbd_search;
    mx_formi_submit($this->prefix . 'qbe-limit-further', 1,
		    mx_img_url('use-qbe.png'),
		    '絞り込み条件を追加して再検索');
    mx_formi_submit($this->prefix . 'qbe-toggle-state', 1,
			mx_img_url('unuse-qbe.png'),
			'絞り込み検索を解除');
    mx_formi_submit($this->prefix . 'qbe-clear', 1,
		    mx_img_url('clear-qbe.png'),
		    '絞り込み条件をクリア');
    if ($this->use_refresh)
	    mx_explicit_refresh();
    $span = 1;
    print '<div class="'.$this->prefix.'qbe">';
    print '<table class="tabular-data"><tr>';
    foreach ($this->enable_qbe as $desc) {
      if (is_array($desc)) {
	$lbl = $desc['Label'];
//09-27-2011
	if ($lbl==''){$lbl = $desc['Column'];}
	$extra = $desc['Singleton'] ? 'darker' : NULL;
	$this->table_control_head($span, $desc, 'th', $extra);
	print htmlspecialchars($lbl). "</th>\n";
      }
      else
	$span = $this->table_control_tail($span, $desc, 'th');
    }
    print "</tr>\n<tr class=\"qbe-bo-entry\">";
    // show one-click search keyboard
    foreach ($this->enable_qbe as $desc) {
      if (is_array($desc) && $desc['SearchByInitial']) {
        $lbl = $desc['Column'];
        $xqk = $this->prefix . 'qbe-set-' . mx_form_escape_key(array($lbl));
	$kb = mx_check_option('SearchByInitial', $desc);

        if (!$kb) {
            $kb = array('ア' => 'ア..オ', 'カ' => 'カ..ゴ',
                        'サ' => 'サ..ゾ', 'タ' => 'タ..ド',
                        'ナ' => 'ナ..ノ', 'ハ' => 'ハ..ポ',
                        'マ' => 'マ..モ', 'ヤ' => 'ヤ..ヨ',
                        'ラ' => 'ラ..ロ', 'ワ' => 'ワ..ヲ',
                        'その他' => '!ア..ヲ');
        }

	// NEEDSWORK:  INC_KBD_SEARCH=1 overrides whatever the kbd
	// configuration.  This is not always desirable.
	if ($_mx_inc_kbd_search)
	  $kb = $__uiconfig_kbd;

        foreach (array_keys($kb) as $i) {
	  if ($kb[$i] == "br")
	     print "<br>\n";
	  else if($kb[$i] == "_")
	          printf("<button class=\"keyboard\">　</button>");
	  else
	    if ($_mx_inc_kbd_search)
	      printf("<button class=\"keyboard\" name=\"%sqbe-limit-further\" value=\"1\" type=\"button\" onClick=\"setAndSubmit(this, '%s','%s', true);\">%s</button>", $this->prefix, $xqk, $kb[$i], $i);
	    else
	      printf("<button class=\"keyboard\" name=\"%sqbe-limit-further\" value=\"1\" onClick=\"setAndSubmit(this, '%s','%s', false)\">%s</button>", $this->prefix, $xqk, $kb[$i], $i);
        }
      }
    }
    # XXX:  needs to combine all the qbe items if one of them
    #  has IncSearch
    foreach ($this->enable_qbe as $desc) {
      if (is_array($desc)) {
	$lbl = $desc['Column'];
	$xqk = $this->prefix . 'qbe-set-' . mx_form_escape_key(array($lbl));
	$this->table_control_head($span, $desc, 'td');
	// If singleton, show the current value.  Otherwise show
	// empty.
	$value = '';
	if ($desc['Singleton']) {
	  foreach ($this->qbe_current as $qbe) {
	    if ($qbe[0] == $lbl) {
	      $value = $qbe[1];
	      break;
	    }
	  }
	}
	$draw = "dq_" . $desc['Draw'];
	$this->$draw($lbl, $xqk, $desc, $value);
	print "</td>\n";
      }
      else
	$span = $this->table_control_tail($span, $desc, 'td');
    }
    print "</tr></table></div>\n";
  }

  function log($msg) {
    $this->logmsg .= $msg;
  }

  function dbglog($msg) {
    if ($this->debug)
      $this->log($msg);
  }

  function select_first($only=NULL) {
	  $this->fetch_data();
	  if (is_array($this->fetched_data)) {
		  $cnt = count($this->fetched_data);
		  if (!$cnt || ($only && $cnt != 1))
			  return 0;
		  $row = $this->fetched_data[0];
		  return $this->set_selection($row);
	  }
	  return 0;
  }

  function draw_0($no_qbe=False){
    $header_written = False;
    if (! is_null($this->qbe_state)) {
      mx_formi_hidden($this->prefix . 'qbe-state', $this->qbe_state);
      mx_formi_hidden($this->prefix . 'qbe-current',
		      serialize($this->qbe_current));
      if ($this->so_config['SAVE_QBE_STATE']) {
	      mx_put_qbe_state($this->so_config['SAVE_QBE_STATE'],
			       $this->qbe_current);
      }
      if ($this->qbe_state == 1) {
	mx_formi_submit($this->prefix . 'qbe-toggle-state', 1,
			mx_img_url('use-qbe.png'),
			'絞り込み検索');
	if ($this->use_refresh)
		mx_explicit_refresh();
      }
      else {
	if(!$no_qbe)
	  $this->draw_qbe_entry_form();
	if ($this->so_config['SHOW_ANNOTATION'] &&
	    is_array($this->qbe_twhere) && count($this->qbe_twhere)) {
	  print '<div id="'.$this->prefix.'qbe_result">';
	  $header_written = True;
	  print '<div class="annote">';
//do not show select condition
	  print 'current condition:';
//0920-2013
 	  print htmlspecialchars(implode(" AND ", $this->qbe_twhere));
	  print "</div>\n";
	}
	// QBE in effect.  If the query is not specific enough (i.e.
	// only the default QBE limits are applied but there is no
	// user input), the application may want to disable the list
	// output here.
	if ($this->qbe_limit_too_vague()) {
		if (!$header_written)
			print '<div id="'.$this->prefix.'qbe_result">';
		if ($this->selection)
			mx_formi_hidden($this->prefix . 'id',
					$this->selection);
		print '</div>';
		return;
	}
      }
    }
    if(!$header_written)
	  print '<div id="'.$this->prefix.'qbe_result">';
    list_of_blah::draw();
    print '</div>';
  }

  function draw($no_qbe=False) {
    if ($this->logmsg != '') {
      print "<!--\n";
      print $this->logmsg;
      print "-->\n";
    }

    if ($this->allow_sort && ! is_null($this->sort_on))
      mx_formi_hidden($this->prefix . 'sort-on', $this->sort_on);

    $lc = $this->so_config['LCHOICE'];
    if (count($lc)) {
      mx_formi_hidden($this->prefix . 'widen', $this->widen);
      if (count($lc) == 1)
	;
      elseif (count($lc) == 2 && !$this->so_config['X_LCHOICE_FORCE_DROPDOWN']) {
	foreach ($lc as $k => $v)
	  if ($this->widen != $k)
	    mx_formi_submit($this->prefix . 'widen-select', $k,
			    '<span class="link">' . htmlspecialchars($v) .
			    '</span>');
      }
      else
	mx_formi_select($this->prefix . 'widen-select',
			$this->widen, $this->so_config['LCHOICE'],
			array('immediate-submit' => 1));
    }
    $this->draw_0($no_qbe);
  }

  function lazily_compute_row_per_page($data) {
    $this->row_per_page = count($data);
  }

  function draw_list_body($data){
    if ($this->row_per_page == 0)
      $this->lazily_compute_row_per_page($data);
    if ($this->so_config['MULTI_COLS'] >= 2) {
      $mc = $this->so_config['MULTI_COLS'];
      $max_rows = ($mc + $this->row_per_page - 1) / $mc;

      $chunk = array();
      $prev_group = NULL;
      $pos = 0;
      $nchunks = 0;
      for($ix=0; $ix < count($data); $ix++) {
	      if($this->so_config['INLINE_GROUP_HEADER']) {
		      $group = $this->so_config['INLINE_GROUP_HEADER'];
		      if ($data[$ix][$group] != $prev_group){
			      $prev_group = $data[$ix][$group];
			      $pos++;

			      if($pos % $max_rows == 0) {
				      printf ( '<td valign=top width="%d%%"><table class="listofstuff">', 100/$this->so_config['MULTI_COLS']);
				      $this->draw_list_body_1($chunk);
				      print '</td></table>';
				      $chunk = array();
				      $nchunks++;
				      $pos = 0;
				      $prev_group=NULL;
			      }
		      }
	      }
	      $chunk[] = $data[$ix];
	      $pos++;

	      if($pos % $max_rows == 0) {
		      printf ( '<td valign=top width="%d%%"><table class="listofstuff">', 100/$this->so_config['MULTI_COLS']);
		      $this->draw_list_body_1($chunk);
		      print '</td></table>';
		      $chunk = array();
		      $nchunks++;
		      $pos = 0;
		      $prev_group=NULL;
	      }
      }
      if($pos != 0) {
	      printf ( '<td valign=top width="%d%%"><table class="listofstuff">', 100/$this->so_config['MULTI_COLS']);
	      $this->draw_list_body_1($chunk);
	      print '</td></table>';
		$nchunks++;
	}
      for($i = $nchunks; $i < $this->so_config['MULTI_COLS']; $i++)
	      print "<td>&nbsp;</td>";
      print '</tr>';
    }else{
	$this->draw_list_body_1($data);
    }
  }

  function draw_list_body_1($data){

      print "<thead>\n";

      $this->draw_list_head();

      print "</thead>\n";
      print "<tbody>\n";
      $span = 1;
      $desc = $this->so_config;
      $prev_group = NULL;
      $total = count($data);
      for ($ix = 0; $ix < $total; $ix++) {
	if($this->so_config['INLINE_GROUP_HEADER']) {
	  $group = $this->so_config['INLINE_GROUP_HEADER'];
	  if ($data[$ix][$group] != $prev_group){
	    $prev_group = $data[$ix][$group];
	    print "<tr class=\"g\"><td style=\"background-color: #ce4; visibility: hidden\"><input type=checkbox></td>";
	    print $this->table_control_head($span, $desc, 'td');
	    print "$prev_group</tr>";
	  }
	}
	$this->draw_list_row($data[$ix], $ix, $total);
      }
      print "</tbody>\n";
  }

  function draw_list_head() {
    $layo = $this->so_config['LLAYO'];
    $cnt = count($layo);
    $span = 1;
    print "<tr>";
    for ($ix = 0; $ix < $cnt; $ix++) {
      $insn = $layo[$ix];
      if (is_array($insn)) {
	$col = $insn['Column'];
	$label = $insn['Label'];
	$this->table_control_head($span, $insn, 'th');
	if (! $this->allow_sort)
	  print $label;
	else {
		if (!array_key_exists($col, $this->allow_sort))
			print "$label";
		else if (is_null($this->sort_on) || $this->sort_on != $col) {
			print "<div onclick=\"activateInnerButton(this);\">";
			mx_formi_submit($this->prefix . 'sort-on-select', $col,
					"<span class=\"link\">$label</span>",
					'この欄で整列');
			print "</div>";
		}
		else
			print "<font color=#ffffff>$label</font>";
	}
	print "</th>\n";
      }
      else
	$span = $this->table_control_tail($span, $insn, 'th');
    }
    print "</tr>\n";
  }

  function row_paging_keys() {
    if (is_null($this->sort_on) ||
	! is_array($this->allow_sort) ||
	! array_key_exists($this->sort_on, $this->allow_sort))
      return $this->paging_keys;
    // $this->allow_sort is an array of this shape:
    // $colname => array($col1 => dbqn($col1), $col2 => dbqn($col2),...)
    $pk = array();
    foreach ($this->allow_sort[$this->sort_on] as $c => $qc) {
      $pk[] = $c;
    }
    if (array_key_exists('SORT_TIEBREAK', $this->so_config)) {
	    $tb = $this->so_config['SORT_TIEBREAK'];
	    if (!is_array($tb))
		    $tb = array($tb);
	    foreach ($tb as $tiebreak) {
		    $found = NULL;
		    foreach ($this->allow_sort as $d) {
			    if (!is_null($found))
				    break;
			    foreach ($d as $c => $qc) {
				    if ($tiebreak == $qc) {
					    $found = $c;
					    break;
				    }
			    }
		    }
		    if (!is_null($found))
			    $pk[] = $found;
	    }
    }

    if ($this->so_config['UNIQ_KEY'])
	    $last_key = $this->so_config['UNIQ_KEY'];
    else if ($this->so_config['UNIQ_ID'])
	    $last_key = $this->so_config['UNIQ_ID'];
    else
	    $last_key = 'ObjectID';
    $pk[] = $last_key;
    if ($this->debug) {
	    print "<!-- RPK:";
	    var_dump($pk);
	    print "-->\n";
    }
    return $pk;
  }

  function row_paging_aliases_plain() { // override
	  $result = array();
	  if (array_key_exists('SORT_TIEBREAK', $this->so_config)) {
		  $tb = $this->so_config['SORT_TIEBREAK'];
		  if (!is_array($tb))
			  $tb = array($tb);
		  foreach ($tb as $tiebreak)
			  $result[] = $tiebreak;
	  }
	  if ($this->so_config['UNIQ_KEY'])
		  $last_key = $this->so_config['UNIQ_KEY'];
	  else if ($this->so_config['UNIQ_ID'])
		  $last_key = $this->so_config['UNIQ_ID'];
	  else
		  $last_key = 'ObjectID';
	  $result[] = $last_key;
	  return $result;
  }

  function row_paging_aliases() {
    if (is_null($this->sort_on) ||
	! is_array($this->allow_sort) ||
	! array_key_exists($this->sort_on, $this->allow_sort))
      return $this->row_paging_aliases_plain();

    // $this->allow_sort is an array of this shape:
    // $colname => array($col1 => dbqn($col1), $col2 => dbqn($col2),...)
    $pa = array();
    foreach ($this->allow_sort[$this->sort_on] as $c => $qc) {
      $pa[] = $qc;
    }
    foreach ($this->row_paging_aliases_plain() as $tiebreak) {
	    $pa[] = $tiebreak;
    }
    if ($this->debug) {
	    print "<!-- RPA:";
	    var_dump($pa);
	    print "-->\n";
    }
    return $pa;
  }

  // Override:
  // Called after a row is fetched using base_fetch_stmt by
  // list_of_blah, before we send it to the browser.
  function annotate_row_data(&$row) { ; }

  function row_decoration(&$row, $ix, $total) {
    $row_class = ($ix % 2) ? 'o' : 'e';
    if ($this->boundary_every_nth_line &&
	(($ix + 1) != $total) &&
	!(($ix + 1) % $this->boundary_every_nth_line)) {
	    $row_class = "$row_class boundaryline";
    }
    return "class=\"$row_class\"";
  }

  function build_selection_data($row) {
	  if (!$row)
		  return NULL;
	  if (array_key_exists('LIST_IDS', $this->so_config)) {
		  $o = array();
		  foreach ($this->so_config['LIST_IDS'] as $col)
			  $o[] = $row[$col];
		  $oid = mx_form_escape_key($o);
	  } else
		  $oid = $row['ObjectID'];
	  return $oid;
  }

  function chosen_data() {
	  $k = $this->chosen();
	  if (!$k)
		  return NULL;
	  $a = mx_form_unescape_key($k);
	  $v = array();
	  $lids = $this->so_config['LIST_IDS'];
	  $lim = count($lids);
	  for ($ix = 0; $ix < $lim; $ix++) {
		  $v[$lids[$ix]] = $a[$ix];
	  }
	  return $v;
  }

  function set_selection($row) {
	  $selection = $this->build_selection_data($row);
	  if (!$selection)
		  return NULL;
	  $this->selection = $selection;
	  $this->selection_changed = 1;
	  return 1;
  }

  function draw_list_row(&$row, $ix, $total) {

    $decoration = $this->row_decoration($row, $ix, $total);

    $this->annotate_row_data(&$row);
    $oid = $this->build_selection_data($row);

    $layo = $this->so_config['LLAYO'];
    $cnt = count($layo);
    $span = 1;
    print "<tr $decoration>";
    for ($ix = 0; $ix < $cnt; $ix++) {
      $insn = $layo[$ix];
      if (is_array($insn)) {
	$col = $insn['Column'];
	$this->table_control_head($span, $insn, 'td');
	$draw = "dx_" . $insn['Draw'];
	$compute_nolink = $this->so_config['COMPUTE_NOLINK'];

	if ($this->so_config['NOLINK'] or
	    ($compute_nolink and $this->$compute_nolink($row))) {
		$nowrap = ($this->so_config['NOLINK'] == 'nowrap');
		if ($nowrap)
			print '<span style="white-space: nowrap;">';
		$this->$draw($insn, $row[$col], $row);
		if ($nowrap)
			print '</span>';
	} else if ($this->so_config['REQUEST_VIA_GET']) {
		$href = sprintf($this->so_config['REQUEST_VIA_GET'], $oid);
		print '<span style="white-space: nowrap;">';
		print "<a href=\"$href\">";
		$this->$draw($insn, $row[$col], $row);
		print '</a></span>';
	} else {
	  print "<div onclick=\"activateInnerButton(this);\">";
	  mx_formi_submit_2part
	    (0, $this->prefix . "id-select", $oid,
	     $this->so_config['MSGS']['Inspect']);
	  print '<span class="link">';
	  $this->$draw($insn, $row[$col], $row);
	  print '</span>';
	  mx_formi_submit_2part
	    (1, $this->prefix . "id-select", $oid,
	     $this->so_config['MSGS']['Inspect']);
	  print "</div>";
	}
	print '</td>';
      }
      else
	$span = $this->table_control_tail($span, $insn, 'td',
					  $decoration);
    }
    print "</tr>\n";
  }

  function _dx_textish($value) {
    // This is debatable...
    if ($value == '')
	    $value = mx_empty_field_mark();
    print htmlspecialchars($value);
  }

  function dx_text($desc, $value, $row) {
    $this->_dx_textish($value);
  }

  function dx_textarea($desc, $value, $row) {
    $this->_dx_textish($value);
  }

  function dx_static($desc, $value, $row) {
    $this->dx_text($desc, $value, $row);
  }

  function dx_timestamp($desc, $value, $row) {
    if (array_key_exists('Option', $desc) &&
	is_array($desc['Option']) &&
	array_key_exists('to-seconds', $desc['Option']))
      $to_seconds = $desc['Option']['to-seconds'];
    else
      $to_seconds = 0;
    $this->_dx_textish(mx_format_timestamp($value, $to_seconds));
  }

  function dx_date($desc, $value, $row) {
    $this->_dx_textish($value);
  }

  function dx_subpick($desc, $value, $row) {
    $this->_dx_textish($value);
  }

  function dx_enum($desc, $value, $row) {
    if (array_key_exists($value, $desc['Enum']))
      print htmlspecialchars($desc['Enum'][$value]);
    elseif (array_key_exists(NULL, $desc['Enum']))
      print $desc['Enum'][NULL];
  }

  function dx_static_enum($desc, $value, $row) {
    $this->dx_enum($desc, $value, $row);
  }

  function dx_radio($desc, $value, $row) {
    $this->dx_enum($desc, $value, $row);
  }

  function dx_dbenum($desc, $value, $row) {
    $value = str_replace('|', ' ・ ', $value);
    $this->dx_text($desc, $value, $row);
  }

  function dx_daysoftheweek($desc, $value, $changed) {
        $drawer = new _lib_so_drawer($this);
	return $drawer->dx_daysoftheweek($desc, $value, $changed);
  }

  function draw_no_data_message() {
//0401-2013 English
    print '<br />No records';
  }

  function dx_extdocument($desc, $value, $changed) {
    // I'm not sure if other guys want drawer.
    
        $drawer = new _lib_so_drawer($this);
	$drawer->in_los = 1;
	return $drawer->dx_extdocument($desc, $value, $changed);
  }
}

class list_of_ppa_objects extends list_of_simple_objects {
	var $patient_column_name_quoted = '"患者"';

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
			' AND ' .
			$this->patient_column_name_quoted .
			' = ' .
			mx_db_sql_quote($this->so_config['Patient_ObjectID']));
	}
}

class list_of_poa_objects extends list_of_simple_objects {
	var $order_column_name_quoted = '"オーダ"';

	function base_fetch_stmt_0() {
		return (list_of_simple_objects::base_fetch_stmt_0() .
			' AND ' .
			$this->order_column_name_quoted .
			' = ' .
			mx_db_sql_quote($this->application->order_ObjectID));
	}
}

class _lib_so_list_of_dummy_objects extends list_of_simple_objects {
  function _lib_so_list_of_dummy_objects($prefix) {
    global $_lib_so_dummy_cfg;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $_lib_so_dummy_cfg);
  }
  function changed() { return NULL; }
  function chosen() { return NULL; }
  function draw() { mx_titlespan('LIST OF DUMMY OBJECTS'); }
}

?>
