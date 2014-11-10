<?php // -*- mode: php; coding: euc-japan -*-

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
//changed 04-10-2012
class shotdrugpick_selector extends list_of_simple_objects {

  var $default_row_per_page = 10;

  function shotdrugpick_selector($prefix, $shotdrugpick) {
    $this->shotdrugpick = $shotdrugpick;
    $cfg = array();

    $effic_code = $shotdrugpick->effic_code;
    $injection = $shotdrugpick->injection;
    $not_injection = $shotdrugpick->not_injection;


    $oo = $shotdrugpick->lcols;
    foreach ($shotdrugpick->list_ids as $col)
      if (! array_key_exists($col, $oo))
	$oo[] = $col;
    $cfg['COLS'] = $shotdrugpick->lcols;
    $cfg['ALLOW_SORT'] = $shotdrugpick->allow_sort;

    $synth = array("����ñ��" => '(M."����ñ�̿�" || M."����ñ��ñ��")',
		   "��������" => '(M."�������̿�" || M."��������ñ��")',
		   );
    $o = array();
    foreach ($oo as $col)
      if(!is_array($col))
        if (array_key_exists($col, $synth))
	  $o[] = $synth[$col] . ' AS ' . mx_db_sql_quote_name($col);
        else
	  $o[] = 'M.' . mx_db_sql_quote_name($col);
      else
        $o[] =  'M.' . mx_db_sql_quote_name($col['Column']);

    $stmt = ('SELECT M."ObjectID", ' .
	     implode(",\n       ", $o) .
	     ' FROM "Medis�����ʥޥ�����" AS M WHERE M."Superseded" IS NULL');
    if ($injection)
      $stmt = $stmt . (' AND M."���������ܰ����ʥ�����" SIMILAR TO ' .
		       "'____[4-6]%'");
    if ($not_injection)
      $stmt = $stmt . (' AND M."���������ܰ����ʥ�����" NOT SIMILAR TO ' .
		       "'____[4-6]%' AND " .
		       "M.\"���������ܰ����ʥ�����\" <> ''");
    if ($effic_code)
      $stmt = $stmt . (' AND M."���������ܰ����ʥ�����" LIKE ' .
		       "'$effic_code%'");
    $cfg['STMT'] = $stmt;
    $cols = $shotdrugpick->lcols;
    $stride = $shotdrugpick->stride;
    $cnt = count($cols);
    $layo = array();
    $w = $shotdrugpick->lwidths;
    for ($ix = 0; $ix < $cnt; $ix += $stride) {
      if (count($layo))
	$layo[] = '//';
      for ($iy = $ix; $iy < $ix + $stride; $iy++) {
	if ($iy < $cnt) {
		if (!is_array($cols[$iy])) {
		   $l = array('Column' => $cols[$iy],
			      'Label' => $cols[$iy]);
		}else{
		   $l = $cols[$iy];
		}
		if ($ix == 0 && is_array($w) &&
		    $iy < count($w) && $w[$iy]) {
			$l['Width'] = $w[$iy];
		}
		$layo[] = $l;
	}
	elseif ($cnt <= $stride)
	  ;
	else
	  $layo[] = '  ';
      }
    }
    $cfg['LLAYO'] = $layo;
    $cfg['ENABLE_QBE'] = $shotdrugpick->enable_qbe;
    $cfg['DEFAULT_QBE'] = $shotdrugpick->default_qbe;
    $cfg['LIST_IDS'] = $this->shotdrugpick->list_ids;
    $cfg['SHOW_ANNOTATION'] = $shotdrugpick->show_annotation;
    list_of_simple_objects::list_of_simple_objects
      ($prefix, $cfg);
  }

  function draw_no_data_message() {
    print '<br />�����������ޤ�����ޤ���';
  }

  function qbe_limit_too_vague() {
	  $has_specific = 0;
	  if ($this->shotdrugpick->effic_code)
		  return 0;
	  foreach ($this->qbe_current as $qbe) {
		  if (($qbe[0] == '��������') && ($qbe[1] != 'F'))
			  continue;
		  return 0;
	  }
	  return 1;
  }

  function draw_qbe_entry_form() {
    global $_mx_inc_kbd_search;
    global $_mx_rx_match_exp;
    global $_mx_hack_takamiya;

    print '<div class="'. $this->prefix . 'qbe">';
    $span = 1;
    print '<table class="tabular-data">';
    foreach ($this->enable_qbe as $desc) {
      if (is_array($desc)) {
	$lbl = $desc['Column'];
	$xqk = $this->prefix . 'qbe-set-' . mx_form_escape_key(array($lbl));
	//$this->table_control_head($span, $desc, 'td');
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
      }
      else
	$span = $this->table_control_tail($span, $desc, 'td');
    }
    $p = $this->prefix;
    print "<input id=\"${p}qbe-limit-further\" type=submit name=\"${p}qbe-limit-further\" value='����'>";
    if (!$_mx_hack_takamiya)
      print "<input type=\"button\" value=\"�õ�\" onClick=\"emptify('${p}qbe-set-b0e5ccf4c9caccbe')\">";
    $um = mx_get_usermode($this->shotdrugpick->config['u'], 'rx_match_exp');
    $pref = $um;
    if(is_null($um) and is_null($_REQUEST["${p}match_exp"]))
      $pref = $_mx_rx_match_exp;
    else if(!is_null($_REQUEST["${p}match_exp"]))
      $pref = $_REQUEST["${p}match_exp"] == '^' ? 1:2;
    if($pref != $um)
      mx_set_usermode($this->shotdrugpick->config['u'], 'rx_match_exp', $pref);
    $checked = $pref == 1 ? ' CHECKED' : '';
    $checked2 = $pref == 2  ? ' CHECKED' : '';
    if (!$_mx_hack_takamiya)
      print <<<HTML
      <label for="${p}match_exp">
      <input type="radio" name="${p}match_exp" id="${p}match_exp" value="^" ${checked}>��Ƭ</input>
       </label>
      <label for="${p}match_exp2">
      <input type="radio" name="${p}match_exp" id="${p}match_exp2" value="" ${checked2}>��ʬ</input>
       </label>
HTML;

   print "<table>";

    foreach ($this->enable_qbe as $desc) {
      if (is_array($desc) && $desc['SearchByInitial']) {
        $lbl = $desc['Column'];
        $xqk = $this->prefix . 'qbe-set-' . mx_form_escape_key(array($lbl));
	$kb = mx_check_option('SearchByInitial', $desc);

        if (!$kb) {
            $kb = array('��' => '��..��', '��' => '��..��',
                        '��' => '��..��', '��' => '��..��',
                        '��' => '��..��', '��' => '��..��',
                        '��' => '��..��', '��' => '��..��',
                        '��' => '��..��', '��' => '��..��',
                        '����¾' => '!��..��');
        }
        foreach (array_keys($kb) as $i) {
	  if ($kb[$i] == "br")
	     print "<br>\n";
	  else if($kb[$i] == "_")
	          printf("<button class=\"keyboard\">��</button>");
	  else
	    if($_mx_inc_kbd_search) {
	      $inc = mx_check_option('IncSearch', $desc);
	      if ($inc) {
		$inc_search = sprintf("do_lookup_inc_search(document.getElementById('%s'), '%s', '%s');", $xqk, $inc['IncSearch'], $this->prefix);
	      }
	      printf("<button class=\"keyboard\" name=\"%sqbe-limit-further\" value=\"1\" type=\"button\" onClick=\"setAndSubmit(this, '%s','%s', true);$inc_search\">%s</button>", $this->prefix, $xqk, $kb[$i], $i);
	    }
	  else
	    printf("<button class=\"keyboard\" name=\"%sqbe-limit-further\" value=\"1\" onClick=\"setAndSubmit(this, '%s','%s', false)\">%s</button>", $this->prefix, $xqk, $kb[$i], $i);
        }
      }
    }

    print "</table>\n";


    print '</div>';
  }


}

function __lib_u_pharmacy_drugpic_default () {
	global $__uiconfig_kbd;
	global $__uiconfig_rx_kbd;
	global $_mx_inc_kbd_search;
	$kbd = $__uiconfig_rx_kbd;
	if($_mx_inc_kbd_search)
	  $kbd = $__uiconfig_kbd;
	return array('LCOLS' => array(// "��̾��",
//04-12-2012
				       "�±����Ѱ�����̾",
				    "�쥻�ץ��Ż����������ƥ������̾",
			     // "���������ܰ����ʥ�����",
			     // "����ñ��",
			     // "��������",
			     "�±���������ñ��",
				      // "����ñ��",
			     // "����ñ�̿�",
			     // "����ñ��ñ��",
			     "��������",
			     // "�������̿�",
			     // "��������ñ��",
			     // "��ʬ",
			     "��¤���",
			     "������",
			     ),

		     'ROW_PER_PAGE' => 10,
		     'SCROLLABLE_HEIGHT' => NULL,

		     'LWIDTHS' => NULL,

		     'ALLOW_SORT' =>
		     array('�쥻�ץ��Ż����������ƥ������̾' =>
			   array('�쥻�ץ��Ż����������ƥ������̾' =>
				 '"�쥻�ץ��Ż����������ƥ������̾"'),
			   '����ñ��' => array('����ñ��' =>
					       '(M."����ñ�̿�" || M."����ñ��ñ��")'),
			   '��������' => array('��������' =>
					       '(M."�������̿�" || M."��������ñ��")'),
			   '��¤���' => array('��¤���' => '"��¤���"'),
			   '������' => array('������' => '"������"'),
			   ),

		     'LIST_IDS' =>  array("��̾��", "ObjectID"),
		     'ENABLE_QBE' => array(// "��̾��",
			     array('Column' => '������̾',
 'Compare' => 'M."kananame"',
				  //02-2013 'Compare' => 'M."�±����Ѱ�����̾"',
				   'Draw' => 'text',
				   'Singleton' => 1,
				   'SearchByInitial' => $kbd,
				   ),
			     array('Column' => "��������",
				   'Compare' => 'M."��������"',
				   'Draw' => 'enum',
				   'Enum' => array('F' => '�ѽ�ʬ�Τ�',
						   'YF' => '����ʬ�Τ�',
						   '' => '�ޥ�������'),
				   'CompareMethod' => 'enum_single_char',
				   'Singleton' => 1,
				   )),
		     'DEFAULT_QBE' => array(array('��������', 'YF')),
		     'STRIDE' => 7,
		     'SKIP_CATEGORY' => 0,
//11-01-2014 for shots 
		     'INJECTION' => 1,
		     'NOT_INJECTION' => 0,
		     'SHOW_ANNOTATION' => 1,);
}

class shotdrugpick {

  function _get_default_config($elem) {
    if (array_key_exists($elem, $this->config))
	return $this->config[$elem];
    return $this->default_config[$elem];
  }

  function shotdrugpick($prefix, $config=NULL) {
    $this->default_config = __lib_u_pharmacy_drugpic_default();
    if (is_null($config))
	    $config = $this->default_config;
    $this->config = $config;

    $this->prefix = $prefix;
    $this->lcols = $this->_get_default_config('LCOLS');
    $this->lwidths = $this->_get_default_config('LWIDTHS');
    $this->list_ids = $this->_get_default_config('LIST_IDS');
    $this->enable_qbe = $this->_get_default_config('ENABLE_QBE');
    $this->default_qbe = $this->_get_default_config('DEFAULT_QBE');
    $this->stride = $this->_get_default_config('STRIDE');
    $this->skip_category = $this->_get_default_config('SKIP_CATEGORY');
    $this->injection = $this->_get_default_config('INJECTION');
    $this->not_injection = $this->_get_default_config('NOT_INJECTION');
    $this->allow_sort = $this->_get_default_config('ALLOW_SORT');
    $this->show_annotation = $this->_get_default_config('SHOW_ANNOTATION');
    $this->major = $this->minor = NULL;

    if (array_key_exists($this->prefix . 'skip-category-set', $_REQUEST))
      $this->skip_category = $_REQUEST[$this->prefix . 'skip-category-set'];
    elseif (array_key_exists($this->prefix . 'skip-category', $_REQUEST))
      $this->skip_category = $_REQUEST[$this->prefix . 'skip-category'];

    $this->effic_code = NULL;

    if (! $this->skip_category) {
      // Populate from the request
      $db = mx_db_connect();
      $stmt = ('SELECT "ObjectID", "��ʬ��", "��ʬ��", "ʬ��̾��"
              FROM "����ʬ��̾��"
              WHERE "Superseded" IS NULL
	      ORDER BY "��ʬ��", "��ʬ��"');
      $major = array();
      $minor = array();
      foreach (pg_fetch_all(pg_query($db, $stmt)) as $row) {
	if (is_null($row['��ʬ��'])) {
	  $major[] = array($row['��ʬ��'],$row['ʬ��̾��'],$row['ObjectID']);
	}
	else {
	  if (! array_key_exists($row['��ʬ��'], $minor))
	    $minor[$row['��ʬ��']] = array();
	  $minor[$row['��ʬ��']][] =
	    array($row['��ʬ��'],$row['ʬ��̾��'],$row['ObjectID']);
	}
      }

      $this->major_cats =& $major;
      $this->minor_cats =& $minor;

      foreach (array('-select', '') as $sfx) {
	$k = $prefix . 'major' . $sfx;
	if (array_key_exists($k, $_REQUEST)) {
	  $this->major = mx_form_unescape_key($_REQUEST[$k]);
	  break;
	}
      }

      // Reset the minor selection if major has changed.
      if (! array_key_exists($prefix . 'major-select', $_REQUEST)) {
	foreach (array('-select', '') as $sfx) {
	  $k = $prefix . 'minor' . $sfx;
	  if (array_key_exists($k, $_REQUEST)) {
	    $this->minor = mx_form_unescape_key($_REQUEST[$k]);
	    break;
	  }
	}
      }

      // Set up efficiacy ID for selection if we already have enough
      // information.

      // If major has not been chosen there is no way for us to choose.
      if (! is_null($this->major)) {
	// Some majors have no minors, in which case NULL minor is perfectly
	// OK.  Also some majors have only one minor.
	if (is_null($this->minor)) {
	  if (!array_key_exists($this->major[0], $this->minor_cats))
	    // Fine.  We do not have any minor for this major.  Use major
	    // itself as the selection key.
	    $this->effic_code = $this->major[0];
	  else {
	    if (count($this->minor_cats[$this->major[0]]) == 1) {
	      // This major has only one minor.  Do not bother having the
	      // user to select it.
	      $minor = $this->minor_cats[$this->major[0]][0];
	      $this->effic_code = $minor[0];
	    }
	  }
	}
	else
	  $this->effic_code = $this->minor[0];
      }
    }

    // We have enough information to set up the selector now.
    $this->selector = new shotdrugpick_selector($prefix . 'sel-', $this);
    $this->selector->row_per_page = $this->_get_default_config('ROW_PER_PAGE');
    $this->selector->scrollable_height = $this->_get_default_config('SCROLLABLE_HEIGHT');
    // Reset the selector if major or minor has changed, or skip-category
    // was changed.
    if (array_key_exists($prefix . 'major-select', $_REQUEST) ||
	array_key_exists($prefix . 'minor-select', $_REQUEST) ||
	array_key_exists($prefix . 'skip-category-set', $_REQUEST))
      $this->selector->reset(NULL);
  }

  function reset() {
    $this->skip_category = $this->major = $this->minor = NULL;
    $this->selector->reset(NULL);
  }

  function chosen() {
    return $this->selector->chosen();
  }

  function chosen_data() {
    return $this->selector->chosen_data();
  }

  function draw() {
    mx_formi_hidden($this->prefix . 'skip-category', $this->skip_category);
    if (! $this->skip_category) {
      print '<table><tr><th width="66%">��ʬ��</th>';
      print '<th width="33%">';
      if (is_null($this->major))
	print '��ʬ��';
      else
	print htmlspecialchars($this->major[1]);
      print'</th></tr>';
      print '<tr valign="top"><td>';

      $this->draw_major_selection();

      print '</td><td>';

      $this->draw_minor_selection();

      print '<hr />';
      mx_formi_submit($this->prefix . 'skip-category-set', '1',
		      '<span class="link">ʬ��ɽ��Ȥ鷺����</span>');

      print '</td></tr></table>';
    }
    $this->draw_selection_ui();
  }

  function draw_major_selection() {
    print '<table class="listofstuff">';
    $cnt = count($this->major_cats);
    $stride = 2;
    print '<tr>';
    for ($ix = 0; $ix < $stride; $ix++) {
      print '<th>��ʬ��</th><th>ʬ��̾��</th>';
    }
    print "</tr>\n";

    for ($ix = 0; $ix < $cnt; $ix += $stride) {
      $evenodd = (($ix/$stride) % 2) ? 'o' : 'e';
      print "<tr class=\"$evenodd\">\n";
      for ($iy = $ix; $iy < $ix + $stride; $iy++) {
	if ($iy < $cnt) {
	  $row = $this->major_cats[$iy];
	  $major = mx_form_escape_key($row);
	  for ($col = 0; $col < 2; $col++) {
	    $val = htmlspecialchars($row[$col]);
	    print '<td>';
	    mx_formi_submit($this->prefix . 'major-select', $major,
			    "<span class=\"link\">$val</span>", "����");
	    print '</td>';
	  }
	}
	else
	  print '<td>&nbsp;</td><td>&nbsp;</td>';
      }
      print "</tr>\n";
    }
    print "</table>\n";
  }

  function draw_minor_selection() {
    if (is_null($this->major))
      return;
    mx_formi_hidden($this->prefix . 'major', mx_form_escape_key($this->major));
    $data = $this->minor_cats[$this->major[0]];
    $cnt = count($data);
    if ($cnt == 0) {
      print "����ʬ��ˤϾ�ʬ�ब����ޤ���";
       return;
    }
    print '<table class="listofstuff">';
    print '<tr><th>��ʬ��</th><th>ʬ��̾��</th></tr>';
    for ($ix = 0; $ix < $cnt; $ix++) {
      $row = $data[$ix];
      $evenodd = ($ix % 2) ? 'o' : 'e';
      $minor = mx_form_escape_key($row);
      print "<tr class=\"$evenodd\">\n";
      for ($col = 0; $col < 2; $col++) {
	$val = htmlspecialchars($row[$col]);
	print '<td>';
	mx_formi_submit($this->prefix . 'minor-select', $minor,
			"<span class=\"link\">$val</span>", "����");
	print '</td>';
      }
      print "</tr>\n";
    }
    print "</table>\n";
  }

  function draw_selection_ui() {
    if (is_null($this->effic_code) && ! $this->skip_category)
      return;

    mx_formi_hidden($this->prefix . 'minor', mx_form_escape_key($this->minor));
    mx_titlespan('��������', 'small_heading');
    if ($this->skip_category)
      mx_formi_submit($this->prefix . 'skip-category-set', '0',
		      '<span class="link">ʬ��ɽ</span>');
    $this->selector->draw();
  }

  function draw_0($no_qbe) {
    $this->selector->draw_0($no_qbe);
  }

}
function u_pharmacy_rx_order_shotdrugpick_cfg($pt_outin='O', $u=0) {
  global $__uiconfig_u_pharmacy_qbe;
  global $__uiconfig_kbd;
  global $__uiconfig_rx_kbd;
  global $_mx_inc_kbd_search;
  global $__uiconfig_u_pharmacy_default_qbe;
  global $__uiconfig_u_pharmacy_outpatient_default;
  global $_mx_show_accept;
  $kbd = $__uiconfig_rx_kbd;
  if($_mx_inc_kbd_search)
    $kbd = $__uiconfig_kbd;
//0410-2012
  $shotdrugpick_cfg = array('LIST_IDS' 
	       => array ("ObjectID", "�±����Ѱ�����̾", "�쥻�ץ��Ż����������ƥ������̾", "��¤���",
			 "������","����ñ��ñ��", "��ʬ","��������", "�쥻�ץ��Ż����������ƥॳ���ɡʣ���"), 
//12-2012  �쥻�ץ��Ż����������ƥ������̾ from �±����Ѱ�����̾
	       'LCOLS' => array(array('Column' => "�쥻�ץ��Ż����������ƥ������̾",
				      'Label' => "������"),
				),
	       'ROW_PER_PAGE' => 1000,
	       'SCROLLABLE_HEIGHT' => "140px",
	       'ALLOW_SORT' => array(
				     '�쥻�ץ��Ż����������ƥ������̾' => array('�쥻�ץ��Ż����������ƥ������̾' => '"�쥻�ץ��Ż����������ƥ������̾"'),
				     ),
	       'DEFAULT_SORT' => "�쥻�ץ��Ż����������ƥ������̾",
	       'ENABLE_QBE' => array(
				     array('Column' => "��������",
					   'Compare' => 'M."��������"',
					   'Draw' => 'enum',
					   'Enum' => $__uiconfig_u_pharmacy_qbe,
					   'CompareMethod' => 'enum_single_char',
					   'Singleton' => 1,
					   ),
				     array('Column' => '������̾',
//1229-2012 kananame from �±����Ѱ�����̾ if kanamae is used
//					   'Compare' => 'M."�±����Ѱ�����̾"',
 'Compare' => 'M."kananame"',
					   'Draw' => 'text',
					   'Option' => array('add_id' =>1),
					   'Singleton' => 1,
					   'SearchByInitial' => $kbd,
					   'CompareMethod' => 'multi_column_select',
//12-2012
					   'CompareAlternateColumn' => array('M."�쥻�ץ��Ż����������ƥ������̾"'),
					   'NormalizeCompareKey' => 'AC',
					   'IncSearch' => array('IncSearch' =>'medicine',
								'Prefix' => 'dp-sel-'
								),
					   )),
	       'DEFAULT_QBE' => array($__uiconfig_u_pharmacy_default_qbe),
	       'SKIP_CATEGORY' => 1,
	       'INJECTION' => 1,
//11-01-2014 for shots
	       'NOT_INJECTION' => 0,
	       'SHOW_ANNOTATION' => 0,);
  if($_mx_show_accept) 
//0410-2012 12-2012 �쥻�ץ��Ż����������ƥ������̾ from �±����Ѱ�����̾
    $shotdrugpick_cfg['LCOLS'] = array(array('Column' => "�쥻�ץ��Ż����������ƥ������̾",
					  'Label' => "������"),
				    array('Column' => "��������",
					  'Label' => "���������",
					  'Draw' => 'enum',
					  'Enum' => array('I' => '��'),
					  ),
				    );
//0604-2011
/*
  if($pt_outin == 'O')
    $shotdrugpick_cfg['DEFAULT_QBE'] = array($__uiconfig_u_pharmacy_outpatient_default);

*/

  $shotdrugpick_cfg['u'] = $u;
  return $shotdrugpick_cfg;
}

function u_pharmacy_shots_order_shotdrugpick_cfg($pt_outin='O', $u=0) {
  global $__uiconfig_u_pharmacy_qbe;
  global $__uiconfig_kbd;
  global $__uiconfig_rx_kbd;
  global $_mx_inc_kbd_search;
  global $__uiconfig_u_pharmacy_default_qbe;
  global $__uiconfig_u_pharmacy_outpatient_default;

  $kbd = $__uiconfig_rx_kbd;
  if ($_mx_inc_kbd_search)
    $kbd = $__uiconfig_kbd;

  $shotdrugpick_cfg = array('LIST_IDS' 
			=> array ("ObjectID", "�±����Ѱ�����̾", "�쥻�ץ��Ż����������ƥ������̾", "��¤���",
                                  "������","����ñ��ñ��", "��ʬ","��������","�쥻�ץ��Ż����������ƥॳ���ɡʣ���"), 
			  'LCOLS' => array(array('Column' => "�쥻�ץ��Ż����������ƥ������̾",
					         'Label' => "������"),
					  ),
			  'ROW_PER_PAGE' => 1000,
			  'SCROLLABLE_HEIGHT' => "140px",
			  'ALLOW_SORT' => array(
				  '�쥻�ץ��Ż����������ƥ������̾' => array('�쥻�ץ��Ż����������ƥ������̾' => '"�쥻�ץ��Ż����������ƥ������̾"'),
				  ),
			  'DEFAULT_SORT' => "�쥻�ץ��Ż����������ƥ������̾",
			  'ENABLE_QBE' => array(
			     array('Column' => "��������",
				   'Compare' => 'M."��������"',
				   'Draw' => 'enum',
				   'Enum' => $__uiconfig_u_pharmacy_qbe,
				   'CompareMethod' => 'enum_single_char',
				   'Singleton' => 1,
				   ),
			     array('Column' => '������̾',
				   'Compare' => 'M."�±����Ѱ�����̾"',
				   'Draw' => 'text',u_pharmacy_rx_order_drugpick_cfg
				   'Option' => array('add_id' =>1),
				   'Singleton' => 1,
				   'SearchByInitial' => $kbd,
				   'CompareMethod' => 'multi_column_select',
				   'CompareAlternateColumn' => array('M."�쥻�ץ��Ż����������ƥ������̾"'),
				   'NormalizeCompareKey' => 'AC',
				   'IncSearch' => array('IncSearch' =>'shots',
								'Prefix' => 'dp-sel-'
								),
				   )),
			  'DEFAULT_QBE' => array($__uiconfig_u_pharmacy_default_qbe),
			  'SKIP_CATEGORY' => 1,
			  'INJECTION' => 1,
			  'NOT_INJECTION' => 0,
			  'SHOW_ANNOTATION' => 0,);
 // if($pt_outin == 'O')
 //   $shotdrugpick_cfg['DEFAULT_QBE'] = array($__uiconfig_u_pharmacy_outpatient_default);
  $shotdrugpick_cfg['u'] = $u;
  return $shotdrugpick_cfg;
}
?>
