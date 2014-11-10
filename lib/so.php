<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

// Simple Object has been split into five files:
// so.php contains the services common to all three classes.
// los.php contains list_of_simple_objects.
// sod.php contains simple_object_display.
// soe.php contains simple_object_edit.
// simple_object.php is for backward compatibility to include all of them.

// Simple Object can be used as the base class to manage versioned objects
// that follows the project convention described in ../../../../doc/.
//
// The subclasses can use the default behaviour by passing a
// configuration array with the following elements to the superclass
// constructor:
//
// TABLE - the name of the SQL table.
//
// COLS  - the columns of the table to be placed in the object.
//         This should exclude "ObjectID", "ID", and "Superseded".
//         Values from these columns are passed around between us and
//         the browser $_REQUEST in simple_object_edit, and also are
//         shown in simple_object_dispaly.
//
// LCOLS - Columns for list-of-simple-objects.  These column headers will
//         will appear in draw_list_head and are used in draw_list_row.
//         STMT below is expected to return rows that contain these;
//         alternatively annotate_row_data() can add columns missing from
//         data STMT returns.  Defaults to COLS.  Also see LLAYO.
//	   ALLOW_SORT and ENABLE_QBE uses this if insufficient information
//         was supplied.
//
// DCOLS - Columns for simple-object-display.  STMT below is expected
//         to return rows that contain these; alternatively
//         annotate_row_data() can add columns missing from data STMT returns.
//         Defaults to COLS.
//
// ECOLS - Columns for simple-object-edit.
//         Defaults to COLS.
//         'Options'  validate, nonnull, date, digits,
//          len, validate-minlen, validate-maxlen
//
// HSTMT - the SQL statement to fetch a single object given its ObjectID.
//         This is used by single_object_display to fetch a possibly
//         superseded row.  If missing, created from TABLE and COLS.
//         The returned columns must include ObjectID and CreatedBy.
// STMT  - the base fetch statement used by list_of_blah which is the
//         superclass of list_of_simple_objects.  If missing, this is
//         created from HSTMT --- it should return only the latest rows.
//
// LCHOICE - list_of_simple_objects can be configured to use one of UI
//         user selectable SQL statement to fetch rows (completely bypassing
//         STMT above).  Pass an array of choices presented in the UI here,
//         and the method base_fetch_stmt_1() is called with the index
//         of the item selected from the UI when value of STMT is needed.
//
// SEQUENCE - the name of the sequence to generate a new "ObjectID".
//         If missing, this is created from TABLE by following the
//         versioning convention.
// ICOLS - the columns of the table to be inserted from the object
//         when creating a new row and updating the values by following
//         the versioning convention.  If missing, set to the value in COLS.
// UNIQ_ID - the name of the unique ID column, quoted for SQL.  By default
//         this is "ObjectID" (with quotes), but can be set by a subclass
//         that uses non default STMT that joins multiple tables and needs
//         to be able to say something like E."ObjectID" when fetching a
//         single row using STMT.
// LIST_IDS - the columns whose values are placed at "${prefix}id-select"
//         in $_REQUEST when a row in list_of_simple_objects is clicked.
//         By default this is just ObjectID, but can be set by a subclass
//         to multiple columns.  Note that the value in "id-select" is not
//         used by list_of_blah nor list_of_simple_objects classes.
// MSGS  - UI messages (help text).
//         Inspect  - list_of_simple_objects rows (they are clickable).
//         Commit   - commiting changes in simple_object_edit.
//         Rollback - discarding changes in simple_object_edit.
//         CCommit   - commiting creation, in simple_object_edit.
//         CRollback - not performing creation, in simple_object_edit.
// NOLINK - If true, do not draw clickable id-select links.
//
// LLAYO  - If exists, controls the appearance of the table used by
//         list-of-simple-objects.  Its elements can be either table
//         control '//', '  ', number, or an array that describes the
//         data, whose contents are:
//         Column : column name returned by STMT
//         Label  : label for the column (defaults to Column)
//         Draw   : method name (sans dx_ prefix) to draw the column
//         Span   : column span for displaying this column only.
//         Table control strings '//', and '  ' ends the current and starts
//         a new row, and creates a single empty cell respectively.
//         Also a number in the list specifies colspan for the rest of
//         the list (see draw_list_head() and draw_list_row() below).
//
// DPAGES - If exists and is an non-empty array, simple-object-display
//         uses these pages as 'flippage' interface labels.  DCOLS elements
//         with 'Page' attribute are displayed only on pages with the same
//         page index (starting 0)
//
// ALLOW_SORT - if true, list_of_simple_objects has a UI control to sort on
//         column values.  In addition, this can be an array whose contents
//         are: "col" => array('col1' => '"col1"', 'col2' => '"col2"', ...);
//         in such case, clicking on "col" from the UI lets the rows sorted
//         using "col1"+"col2"+...
//
// DEFAULT_SORT_ON - if true, use this column ("col" in ALLOW_SORT) as
//        the sort key by default.
//
// ENABLE_QBE - if true, list_of_simple_objects has a UI control to restrict
//        rows shown.  Each element of this is either table control similar
//        to those of LLAYO, or an array to describe the entry, whose
//        contents are:
//           "Column" => name for the form value
//           "Compare" => SQL expression to compare against
//           "Draw" => input widget
//           "Singleton" => if true, newer specification from UI does not
//                     AND into the existing expression; instead it replaces
//                     the subexpression derived from the same column.
//           "CompareMethod" => if exists, names the method of the subclass
//                     to generate SQL snippets for comparison.  See
//                     lib/u/pharmacy/csl.php for an example.
//        A string in this list is a shorthand for :
//            array('Column' => $str, 'Compare' => mx_db_sql_quote_name($str),
//                  'Draw' => 'text');
//        See lib/u/manage/employee.php and lib/u/pharmacy/drugpick.php
//        for examples.
// SHOW_ANNOTATION - show annotation if 1. default is 1.
//
//////////////////////////////////////////////////////////////////
// Widgets
//
// daysoftheweek widget
//
// This allows you to draw a set of checkboxes to choose N elements
// out of a set of M choices.  In addition, a select/option to set
// and unset multiple choices at the same time can be drawn.
//
// The default set is days of the week as the name of the widget
// suggests, but this could be used to pick from other things.  Two
// options are supported:
//
// 	dow: an array of size M that lists possible values;
//
// 	dow-shorthand: an array of two element array <label
// 	value> that defines a shorthand.
//
// The database column to store the value of this widget is
// expected to be of a char array of size M, and i-th character
// becomes either 'Y' (when the corresponding element is chosen) or
// 'N' (otherwise).
//
// Example:
//
//    array('Column' => 'DOW',
//          'Draw' => 'daysoftheweek',
//          'Option' => array('dow' =>
//                         array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
//                         'dow-shorthand' =>
//                         array(array('Label' => '', 'Value' => 'NNNNNN'),
//                         array('Label' => 'MWF', 'Value' => 'YNYNYN'),
//                         array('Label' => 'TTS', 'Value' => 'NYNYNY'),
//                               ))),
//
// This defines 6 days of a week (so the column 'DOW' should be of
// char(6) or longer in the database), with a select/option that
// has three elements (empty, MWF and TTS).
//////////////////////////////////////////////////////////////////

function &_lib_so_fetch_data(&$db, $id, &$config) {
  $stmt = ($config['HSTMT'] . ' AND ' . $config['UNIQ_ID'] .
	   ' = ' . mx_db_sql_quote($id));
  $d = pg_fetch_all(pg_query($db, $stmt));
  return $d[0];
}

function _lib_so_fetch_origin_next(&$db, $id, $origin, &$config)
{
	if (is_null($origin) || $origin == '')
		$origin_limit = '';
	else
		$origin_limit = (' AND "ObjectID" > ' .
				 mx_db_sql_quote($origin));

	$stmt = ('SELECT "ObjectID", "ID", "Superseded" FROM ' .
		 mx_db_sql_quote_name($config['TABLE']) .
		 ' WHERE "ID" = ' . mx_db_sql_quote($id) . 
		 ' AND "Superseded" IS NOT NULL' .
		 $origin_limit .
		 ' ORDER BY "Superseded" DESC, "ObjectID" DESC LIMIT 1');
	$sth = pg_query($db, $stmt);
	if ($sth) {
		$d = pg_fetch_array($sth, NULL, PGSQL_ASSOC);
		return $d['ObjectID'];
	}
	return NULL;
}

function _lib_so_fetch_origin_info(&$db, $id, &$config)
{
	return _lib_so_fetch_origin_next(&$db, $id, NULL, &$config);
}

function _lib_so__is_table_control($d) {
  return ($d == '//' || $d == '  ' ||
	  (strspn($d, '0123456789') == strlen($d) && strlen($d)));
}

function _lib_so_prepare_config_ledcols(&$cols) {
  for ($ix = 0; $ix < count($cols); $ix++) {
    if (! is_array($cols[$ix]))
      $cols[$ix] = array('Column' => $cols[$ix]);
    $desc =& $cols[$ix];
    if (! array_key_exists('Label', $desc))
      $desc['Label'] = $desc['Column'];
    if (! array_key_exists('Draw', $desc))
      $desc['Draw'] = 'text';
    unset($desc);
  }
}

function _lib_so_prepare_config(&$config) {
  if (! array_key_exists('UNIQ_ID', $config))
    $config['UNIQ_ID'] = mx_db_sql_quote_name("ObjectID");

  if (! array_key_exists('LCHOICE', $config))
    $config['LCHOICE'] = array();
  
  foreach (array('L','D','E') as $ctype_prefix) {
    $ctype = $ctype_prefix . 'COLS';
    if (! array_key_exists($ctype, $config)) {
      $config[$ctype] = $config['COLS'];
      if ($ctype == 'DCOLS') {
	$config[$ctype][] = array('Column' => 'CreatedBy',
				  'Label' => '記録者',
				  'Draw' => 'user');
      }
    }
    if (array_key_exists("SKIP_LEDCOLS", $config) &&
	array_key_exists($ctype, $config['SKIP_LEDCOLS']))
	    ;
    else
	    for ($iy = 0; $iy < count($config[$ctype]); $iy++) {
		    if (! is_array($config[$ctype][$iy]))
			    $config[$ctype][$iy] = array
				    ('Column' => $config[$ctype][$iy]);
		    if (! array_key_exists('Label', $config[$ctype][$iy]))
			    $config[$ctype][$iy]['Label'] =
				    $config[$ctype][$iy]['Column'];
		    if (! array_key_exists('Draw', $config[$ctype][$iy]))
			    $config[$ctype][$iy]['Draw'] = 'text';
	    }

    $clayo = $ctype_prefix . 'LAYO';
    if (! array_key_exists($clayo, $config)) {
      $layo = array();
      if (! is_null($config[$ctype]))
	foreach ($config[$ctype] as $desc)
	  $layo[] = $desc;
      $config[$clayo] = $layo;
    }
    for ($ix = 0; $ix < count($config[$clayo]); $ix++) {
      $d = $config[$clayo][$ix];
      if (! is_array($d)) {
	if (_lib_so__is_table_control($d))
	  continue;
	$config[$clayo][$ix] = array('Column' => $d);
      }
      if (! array_key_exists('Label', $config[$clayo][$ix]))
	$config[$clayo][$ix]['Label'] =
	  htmlspecialchars($config[$clayo][$ix]['Column']);
      if (! array_key_exists('Draw', $config[$clayo][$ix]))
	$config[$clayo][$ix]['Draw'] = 'text';
    }
    
  }

  if (! array_key_exists('HSTMT', $config)) {
    if (! array_key_exists('COLS', $config))
      $config['HSTMT'] = 'HSTMT UNDEFINED';
    else {
      $col_defs = array();
      foreach ($config['COLS'] as $col)
	      $col_defs[] = mx_db_sql_quote_name($col);
      if (is_array($config['SCOLS'])) {
	      foreach ($config['SCOLS'] as $alias => $def)
		      $col_defs[] = sprintf("($def) as %s",
					    mx_db_sql_quote_name($alias));
      }
      $config['HSTMT'] =
	('SELECT "ObjectID", "CreatedBy", ' .
	 implode(', ', $col_defs) .
	 ' FROM ' . mx_db_sql_quote_name($config['TABLE']) .
	 ' WHERE (NULL IS NULL) ');
    }
  }

  if (! array_key_exists('STMT', $config))
    $config['STMT'] = $config['HSTMT'] . ' AND "Superseded" IS NULL';

  if (array_key_exists('STMT_FURTHER_LIMIT', $config)) {
	  $further = $config['STMT_FURTHER_LIMIT'];
	  $config['HSTMT'] .= ' AND ' . $further;
	  $config['STMT'] .= ' AND ' . $further;
  }

  if (! array_key_exists('SEQUENCE', $config)) {
	  if (is_array($config['TABLE'])) {
		  $config['SEQUENCE'] = $config['TABLE'];
		  $last = count($config['SEQUENCE']);
		  $config['SEQUENCE'][$last-1] .= '_ID_seq';
	  }
	  else
		  $config['SEQUENCE'] = $config['TABLE'] . '_ID_seq';
  }

  if (! array_key_exists('ICOLS', $config))
    $config['ICOLS'] = $config['COLS'];


  if (!array_key_exists('MSGS', $config))
    $config['MSGS'] = array();
  foreach (array('Commit' => '/images/commit_button.png',
		 'CCommit' => '/images/ccommit_button.png',
		 'Rollback' => '/images/rollback_button.png',
		 'CRollback' => '/images/crollback_button.png',
		 'Inspect' => '詳細を見る',
		 ) as $key => $msg)
    if (!array_key_exists($key, $config['MSGS']))
      $config['MSGS'][$key] = $msg;

  if (!array_key_exists('SHOW_ANNOTATION', $config))
      $config['SHOW_ANNOTATION'] = 1;

}

function _lib_so_zip_layo(&$acols, $insn) {
	$layo = array();
	$zip = 0;
	$limit = count($acols);
	foreach ($insn as $a) {
		if (_lib_so__is_table_control($a))
			$it = $a;
		else {
			while ($zip < $limit) {
				$ac = $acols[$zip];
				if ((is_array($ac) && $ac['Column'] == $a) ||
				    $ac == $a)
					break;
				$zip++;
			}
			if ($limit <= $zip) {
				$found = $limit;
				for ($zip = 0;
				     $zip < $limit;
				     $zip++) {
					$ac = $acols[$zip];
					if ((is_array($ac) &&
					     $ac['Column'] == $a) ||
					    $ac == $a) {
						$found = $zip;
						break;
					}
				}
			}
			if ($limit <= $zip) {
				$it = $a;
				$zip = 0;
			} else {
				$it = $acols[$zip];
			}
		}
		$layo[] = $it;
	}
	return $layo;
}

function _lib_so_dropzip_layo($acols, $excludes) {
	$layo = array();
	foreach ($acols as $a) {
		if (is_array($a))
			$col = $a['Column'];
		else
			$col = $a;
		if (array_search($col, $excludes) !== false)
			continue;
		$layo[] = $a;
	}
	return $layo;
}

$_lib_so_dummy_cfg = array('COLS' => array('DUMMY'));

class _lib_so_drawer {

  var $in_los = 0;

  function _lib_so_drawer(&$it) {
    $this->it =& $it;
  }

  function dx_user($desc, $value, $changed) {
    if ($this->it->umap && array_key_exists($value, $this->it->umap)) {
      $val = $this->it->umap[$value];
      $value = '';
      $fmt = (array_key_exists('Format', $desc)
	      ? $desc['Format']
	      : $this->it->default_creator_format);

      foreach ($fmt as $key => $fmt) {
	$value = $value . sprintf($fmt, $val[$key]);
      }
    }
    elseif (is_null($value))
      $value = '(初期設定)';
    else
      $value = '(非職員)';

    $this->_dx_textish($value, $changed);
  }

  function _dx_textish($value, $changed, $noquote=NULL) {
    // Overstrike does not show if the string is empty.
    if ($value == '')
	    $value = mx_empty_field_mark();
    if ($noquote)
	    print $value;
    else
	    print htmlspecialchars($value);
  }

  function dx_text($desc, $value, $changed) {
    $this->_dx_textish($value, $changed);
  }

  function dx_static($desc, $value, $changed) {
    $this->dx_text($desc, $value, $changed);
  }

  function dx_timestamp($desc, $value, $changed) {
    if (array_key_exists('Option', $desc) &&
	is_array($desc['Option']) &&
	array_key_exists('to-seconds', $desc['Option']))
      $to_seconds = $desc['Option']['to-seconds'];
    else
      $to_seconds = 0;
    $this->_dx_textish(mx_format_timestamp($value, $to_seconds), $changed);
  }

  function dx_date($desc, $value, $changed) {
    $this->dx_text($desc, $value, $changed);
  }

  function dx_textarea($desc, $value, $changed) {
    // Overstrike does not show if the string is empty.
    if ($value == '')
	    $value = mx_empty_field_mark();
    $this->_dx_textish(mx_html_paragraph($value), $changed, 'no-quote');
  }

  function dx_static_enum($desc, $value, $changed) {
    $this->dx_enum($desc, $value, $changed);
  }

  function dx_subpick($desc, $value, $changed) {
    $this->dx_text($desc, $value, $changed);
  }

  function dx_enum($desc, $value, $changed) {
    if (array_key_exists($value, $desc['Enum']))
      $show = $desc['Enum'][$value];
    elseif (array_key_exists(NULL, $desc['Enum']))
      $show = $desc['Enum'][''];
    else
      $show = '';

    if (is_array($show) && array_key_exists('value', $show)) {
      $show = $show['value'];
    }
    $this->_dx_textish($show, $changed);
  }

  function dx_check($desc, $value, $changed) {
    if (is_callable($desc['Check']))
      $value = $desc['Check']($value);
    else
      $value = ($value && $value != 'N');
    if ($value)
      print mx_img_url('checked.png');
    else
      print mx_img_url('unchecked.png');
  }

  function dx_daysoftheweek($desc, $value, $changed) {
	  global $_mx_daysoftheweek;
	  $dow = mx_check_option('dow', $desc['Option'], $_mx_daysoftheweek);
	  $txt = '';
	  for ($i = 0; $i < count($dow); $i++) {
		  if (substr($value, $i, 1) != 'Y')
			  continue;
		  if ($txt != '')
			  $txt = $txt . ' ';
		  $txt = $txt . $dow[$i];
	  }
	  return $this->dx_text($desc, $txt, $changed);
  }

  function en($name) {
    return $this->it->prefix . mx_form_encode_name($name);
  }

  function dx_extdocument($desc, $value, $changed) {
	  if ($value == '') {
		  print htmlspecialchars('(未設定)');
		  return;
	  }
	  $d = mx_find_ext_document($value);
	  if (is_null($d)) {
		  print htmlspecialchars(mx_empty_field_mark());
		  return;
	  }

	  $filename = $d['label_string'];
	  $ext = $d['extension'];
	  if ($ext != '') {
		  if (substr($ext, 0, 1) != '.')
			  $ext = ".$ext"; /* avoid common mistakes */
	  }
	  if (array_key_exists('Option', $desc) &&
	      is_array($desc['Option']) && 
	      array_key_exists('filename', $desc['Option'])) {
	    $filename = ($desc['Option']['filename']);
	    $ext = strrchr($filename, '.');
	    if($ext != '')
	      $filename = substr($filename, 0, -strlen($ext));
	  }

	  if (array_key_exists('Option', $desc) &&
	      is_array($desc['Option']) &&
	      array_key_exists('filename_prefix', $desc['Option'])) {
		  $filename = ($desc['Option']['filename_prefix'] .
				   $filename);
	  }

	  if ($this->in_los) {
		  $l = sprintf("%s, %d bytes",
			       $filename . $ext, $d['numbytes']);
	  } else {
		  $l = sprintf("ダウンロード (%s, %d bytes)",
			       $filename . $ext, $d['numbytes']);
	  }

	  $exturl = NULL;

	  if (array_key_exists('Option', $desc) &&
	      is_array($desc['Option'])) {

		  if (array_key_exists('ext_url', $desc['Option'])) {
			  $exturl = $desc['Option']['ext_url'];
		  }
	  }
	  if ($exturl)
		  $extdocument_url = $exturl($value, $filename, $ext);
	  else
		  $extdocument_url = sprintf("/blobmedia.php/%d/%s%s",
					     $value, $filename, $ext);
	  
	  $mime_type = $d['mime_type'];
	  $fn = "dx_ext_" . $d['handler'];
	  if (!method_exists($this, $fn) ||
	      !$this->$fn($desc, $value, $changed, $extdocument_url, &$d)) {
	    print '<a href="';
	    print $extdocument_url;
	    print "\">$l</a>\n";
	  }
  }

  function dx_ext_image($desc, $value, $changed, $extdocument_url, &$d) {
	  $img_option = mx_check_option('img',
					mx_check_option('Option', $desc));
	  if ($img_option == 'separate') {
	    print "<a target=\"_blank\" ";
	    print "href=\"$extdocument_url\"\n>";
	    print "（画像表示）</a>";
	  }
	  else if ($img_option == 'ondemand') {
	    $col = $desc['Column'];
	    $showing = $this->en("$col.blobmedia-showing");
	    $showit =  $this->en("$col.blobmedia-showit");
	    if (array_key_exists($showit, $_REQUEST))
	      $_REQUEST[$showing] = $_REQUEST[$showit];
	    
	    if ($_REQUEST[$showing]) {
	      print "<img src=\"$extdocument_url\"><br />";
	      mx_formi_submit($showit, 0,
			      '(非表示)', '非表示');
	    }
	    else {
	      mx_formi_submit($showit, 1,
			      '(表示)', '表示');
	    }
	    mx_formi_hidden($showing, $_REQUEST[$showing]);
	  }
	  else
	    print "<img src=\"$extdocument_url\">";

	  if (mx_check_option('download',
			      mx_check_option('Option', $desc))) {
		  print "<a href=\"$extdocument_url\"\n>";
		  print "ダウンロード</a>";
	  }
	  return 1;
  }

  function dx_ext_drawapp_image($desc, $value, $changed, $extdocument_url, &$d) {
	  $annos = mx_find_extmedia_annotation($value, 'drawapp');
	  if(!$annos)
	    return $this->dx_ext_image($desc, $value, $changed, $extdocument_url, &$d);
	  $anno = $annos[0];
	  $db = mx_db_connect();
	  $xml = '';
	  if(!mx_db_fetch_extmedia($db, &$xml, $anno['ObjectID'])) {
	    print "注釈データがありません";
	    return $this->dx_ext_image($desc, $value, $changed, $extdocument_url, &$d);
	  }
	  $id = 'drawapp_' . $anno['ObjectID'];
	  $xml = htmlspecialchars($xml);
	  print <<<HTML
<script>
	    draw_drawapp('${id}', '', '${xml}', 1);
</script>
HTML;
	  return 1;
  }

  function dx_schema($desc, $value, $changed) {
    $col = $this->prefix . $desc['Column'];
    print <<<HTML
      <script>
      draw_drawapp('${col}', '', '${value}', 1);
    </script>
HTML;
  }

  function dx_icd10($desc, $value, $changed) {
    print $value;
  }
}



function find_by_column(&$cfg, $colname)
{
	foreach ($cfg as $elem) {
		if ($elem['Column'] == $colname)
			return $elem;
	}
	return NULL;
}

?>
