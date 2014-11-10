<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/lib/u/manage/employee.php';

function _lib_u_manage_patient_employee_fetch_data($id) {
  $stmt = '
SELECT
   (P."À«" || \' \' || P."Ì¾") as "´µ¼ÔÌ¾",
   P."ObjectID" as "´µ¼ÔObjectID", A."CreatedBy",
   AR."ObjectID" AS "Ã´ÅöÌò³äObjectID", AR."Ã´ÅöÌò³ä", AR."Employee_Class",
   E."ObjectID" as "¿¦°÷ObjectID", E."¿¦°÷ID", E."À«", E."Ì¾",
   (COALESCE(D."ÂçÊ¬Îà", \'\')||COALESCE(D."ÃæÊ¬Îà1",\'\')||
    COALESCE(D."ÃæÊ¬Îà2",\'\')||COALESCE(D."¾®Ê¬Îà",\'\')) as "Éô½ð",
   C."¿¦¼ï" as "¿¦¼ïÌ¾", R."¿¦°Ì" as "¿¦°ÌÌ¾"
FROM "´µ¼ÔÂæÄ¢" as P
JOIN "´µ¼ÔÃ´Åö¿¦°÷" as A
ON   P."Superseded" IS NULL AND
     P."ObjectID" = A."´µ¼Ô"
LEFT JOIN "Ã´ÅöÌò³ä" as AR
ON   AR."Superseded" IS NULL
LEFT JOIN "´µ¼ÔÃ´Åö¿¦°÷¥Ç¡¼¥¿" as A1
ON   A1."´µ¼ÔÃ´Åö¿¦°÷" = A."ObjectID" AND
     AR."ObjectID" = A1."Ã´ÅöÌò³ä"
LEFT JOIN "¿¦°÷ÂæÄ¢" as E
ON   E."Superseded" IS NULL AND
     A1."¿¦°÷" = E."ObjectID"
LEFT JOIN "¿¦¼ï°ìÍ÷É½" as C
ON   C."Superseded" IS NULL AND
     C."ObjectID" = E."¿¦¼ï"
LEFT JOIN "¿¦°Ì°ìÍ÷É½" as R
ON   R."Superseded" IS NULL AND
     R."ObjectID" = E."¿¦°Ì"
LEFT JOIN "Éô½ð°ìÍ÷É½" as D
ON   D."Superseded" IS NULL AND
     D."ObjectID" = E."Éô½ð"
WHERE
     A."ObjectID" = ' . mx_db_sql_quote($id) . '
ORDER BY AR."À°Îó½ç°Ì", E."ObjectID"';
  if (0) {
    print "<!--\n";
    print "$stmt;\n";
    print "-->\n";
  }

  $data = array();
  $clist = array();
  $rolist = array();
  $r = pg_query(mx_db_connect(), $stmt);
  $nr = pg_num_rows($r);
  for ($ix = 0; $ix < $nr; $ix++) {
    $d = pg_fetch_array($r, $ix, PGSQL_ASSOC);
    $role = $d['Ã´ÅöÌò³ä'];
    if ($ix == 0) {
      $data['*META*'] = array($d['´µ¼ÔÌ¾'],$d['´µ¼ÔObjectID'],$d['CreatedBy']);
    }
    if (! array_key_exists($role, $data)) {
      $data[$role] = array();
      $clist[] = $d['Employee_Class'];
      $rolist[] = $d['Ã´ÅöÌò³äObjectID'];
    }
    if (! is_null($d["¿¦°÷ObjectID"]))
      $data[$role][] = $d;
  }
  return array($data, $clist, $rolist);
}

// This must match the SQL and LIST_IDS of $loe
function _lib_u_manage_patient_employee_fmt_name($d) {
  return sprintf("%s %s (%s - %s)",
		 $d['À«'], $d['Ì¾'], $d['¿¦°ÌÌ¾'], $d['¿¦¼ïÌ¾']);
}

class patient_employee_display extends simple_object_display {

  function patient_employee_display($prefix) {
    $cfg = array('COLS' => array(), 'TABLE' => '´µ¼ÔÃ´Åö¿¦°÷');
    simple_object_display::simple_object_display($prefix, $cfg);
  }

  function reset($id) {
    // This stuff is really different---the $this->id was passed by
    // the single-table-application for the patient object, not
    // the patient-employee object.  We need to convert it here.

    $db = mx_db_connect();
    $stmt = 'SELECT "ObjectID" FROM "´µ¼ÔÃ´Åö¿¦°÷" WHERE "Superseded" IS NULL
             AND "´µ¼Ô" = ' . mx_db_sql_quote($id);
    $d = mx_db_fetch_single($db, $stmt);
    $this->id = $d["ObjectID"];

    $this->history_ls = NULL;
    $this->history_ix = NULL;
  }

  function fetch_data($id) {
    $d = _lib_u_manage_patient_employee_fetch_data($id);
    if ($this->debug) {
      print "<!-- FETCH DATA WITH $id\n";
      var_dump($d);
      print "-->\n";
    }
    $d = $d[0];
    $dcols = array();
    $dcols[] = array('Column' => '*META*',
		     'Label' => '´µ¼ÔÌ¾',
		     'Draw' => 'pe_patient');
    foreach ($d as $role => $data) {
      if ($role == '*META*') continue;
      $dcols[] = array('Column' => $role,
		       'Label' => $role,
		       'Draw' => 'pe_assignment');
    }
    $dcols[] = array('Column' => '*META*',
		     'Label' => 'µ­Ï¿¼Ô',
		     'Draw' => 'pe_user');
    $this->so_config['DCOLS'] = $dcols;
    return $d;
  }

  function hist_compare($data, $hdata, $col, $desc) {
    if (is_null($hdata)) { return ''; }
    if (!is_array($col)) { $col = array($col); }
    $r = '';
    foreach ($col as $cc) {
      if ($cc == '*META*') {
	// Do not compare meta if we are showing patient name.
	if ($desc['Draw'] == 'pe_patient')
	  continue;
	// If we are showing creator, we do want to compare them
	if ($desc['Draw'] == 'pe_user') {
	  if ($data[$cc][2] != $hdata[$cc][2]) {
	    $r = ' class="changed"'; break;
	  }
	}
      }
      $cnt = count($data[$cc]);
      if ($cnt != count($hdata[$cc])) {
	$r = ' class="changed"'; break;
      }
      $d_ids = array();
      $h_ids = array();
      for ($ix = 0; $ix < $cnt; $ix++) {
	$d_ids[] = $data[$cc][$ix]['¿¦°÷ObjectID'];
	$h_ids[] = $hdata[$cc][$ix]['¿¦°÷ObjectID'];
      }
      sort($d_ids);
      sort($h_ids);
      if ($d_ids != $h_ids) {
	$r = ' class="changed"'; break;
      } else {
	if ($this->debug) {
	  print "<!-- $cc comparison OK\n";
	  var_dump($d_ids);
	  print "\nvs\n";
	  var_dump($h_ids);
	  print "\ncount was ";
	  print $cnt;
	  print " -->\n";
	}
      }
    }
    if ($this->debug) {
      print "<!-- HC\n";
      foreach ($col as $cc) {
	if ($data[$cc] != $hdata[$cc]) {
	  print "\$data[$cc]: ";
	  var_dump($data[$cc]);
	  print "\n\$hdata[$cc]: ";
	  var_dump($hdata[$cc]);
	}
      }
      print "-->\n";
    }
    return $r;
  }

  function dx_pe_patient($desc, $value, $changed) {
    print htmlspecialchars($value[0]);
  }

  function dx_pe_user($desc, $value, $changed) {
    $this->dx_user($desc, $value[2], $changed);
  }

  function dx_pe_assignment($desc, $value, $changed) {
    // Value here is the array of fetched employees, potentiall empty.
    $dd = array();
    foreach ($value as $d) {
      $s = _lib_u_manage_patient_employee_fmt_name($d);
      $dd[] = htmlspecialchars($s);
    }
    // Overstrike does not show if the string is empty.
    if (count($dd) == 0 && ! is_null($this->history_ix) && $changed)
	    print mx_empty_field_mark();
    else
      print implode('<br />', $dd);
  }

}

class patient_employee_edit extends simple_object_edit {

  function patient_employee_edit($prefix) {
    $cfg = array('COLS' => array(''),
		 'TABLE' => '´µ¼ÔÃ´Åö¿¦°÷');
    $this->loe = $this->ChangeThis = NULL;
    $this->rolist = $_REQUEST[$prefix . 'role-oid-list'];
    $this->clist = $_REQUEST[$prefix . 'role-cat-list'];
    $this->rlist = $_REQUEST[$prefix . 'role-list'];
    $cfg['ECOLS'] = $this->fix_ecols();
    simple_object_edit::simple_object_edit($prefix, $cfg);
  }

  function edit($id) {
    simple_object_edit::edit($id);
    $this->clear_subedit();
  }

  function during_subpick() {
    return !is_null($this->ChangeThis);
  }

  function clear_subedit() {
    // Are we sure about this?
    unset($_REQUEST[$prefix . 'ChangeThis']);
    $this->loe = $this->ChangeThis = NULL;
  }

  function fix_ecols($db_data=NULL) {
    $ecol = array(array('Column' => '*META*',
			'Label' => '´µ¼ÔÌ¾',
			'Draw' => 'patient_oonly'));

    if ($db_data) {
      $rolist = $db_data[2];
      $clist = $db_data[1];
      $db_data = $db_data[0];
      $rlist = array();
      foreach ($db_data as $role => $d) {
	if ($role == '*META*')
	  continue;
	$rlist[] = $role;
	$ecol[] = array('Label' => $role,
			'Column' => $role,
			'Draw' => 'pe_edit');
      }
      $this->rlist = mx_form_escape_key($rlist);
      $this->clist = mx_form_escape_key($clist);
      $this->rolist = mx_form_escape_key($rolist);
    } else {
      $rlist = mx_form_unescape_key($this->rlist);
      foreach ($rlist as $role) {
	$ecol[] = array('Label' => $role,
			'Column' => $role,
			'Draw' => 'pe_edit');
      }
    }
    return $ecol;
  }

  function dx_pe_edit($desc, $name, $value) {
    // value here does not make any meaning.
    $n = mx_form_decode_name(substr($name, strlen($this->prefix)));
    $data = $this->data[$n];
    $cnt = count($data);
    // Pass the number of people in that capacity.
    mx_formi_hidden($name, $cnt);
    for ($ix = 0; $ix < $cnt; $ix++) {
      $s = $data[$ix]["¿¦°÷É½µ­"];
      $e = mx_form_escape_key(array($data[$ix]["¿¦°÷ObjectID"], $s));
      mx_formi_hidden("$name-$ix", $e);

      $s = htmlspecialchars($s);
      if ($this->during_subpick())
	print $s;
      else
	mx_formi_submit($this->prefix . 'ChangeThis',
			"$name-$ix",
			"<span class=\"link\">$s</span>");

      print "<br />\n";
    }
  }

  function dx_patient_oonly($desc, $name, $value) {
    mx_formi_hidden($name, mx_form_escape_key($value));
    print htmlspecialchars($value[0]);
  }

  function fetch_data($id) {
    $_d = _lib_u_manage_patient_employee_fetch_data($id);
    $d = $_d[0];
    $clist = $_d[1];
    $rolist = $_d[2];
    $this->so_config['ECOLS'] = $this->fix_ecols($_d);

    $rlist = mx_form_unescape_key($this->rlist);
    foreach ($rlist as $role) {
      $cnt = count($d[$role]);
      for ($ix = 0; $ix < $cnt; $ix++) {
	$d[$role][$ix]["¿¦°÷É½µ­"] =
	  _lib_u_manage_patient_employee_fmt_name($d[$role][$ix]);
      }
      $d[$role][] = $this->dummy_assignment($role); // Empty one at end.
    }
    return $d;
  }

  function dummy_assignment($role) {
    return array('¿¦°÷É½µ­' => "($role ÄÉ²Ã)",
		 '¿¦°÷ObjectID' => '');
  }

  function annotate_form_data(&$data) {
    if ($this->debug) {
      print "<!-- DATA\n";
      var_dump($data);
      print "-->\n";
    }

    $data['*META*'] = mx_form_unescape_key($data['*META*']);

    if ($this->rlist) {
      $rlist = mx_form_unescape_key($this->rlist);
      foreach ($rlist as $role) {
	$cnt = $data[$role];
	$data[$role] = array();
	$n = $this->prefix . mx_form_encode_name($role);
	for ($ix = 0; $ix < $cnt; $ix++) {
	  $d = mx_form_unescape_key($_REQUEST["$n-$ix"]);
	  $nd = array();
	  $nd["¿¦°÷ObjectID"] = $d[0];
	  $nd["¿¦°÷É½µ­"] = $d[1];
	  $data[$role][$ix] = $nd;
	}
      }
    }

    if (array_key_exists($this->prefix . 'ChangeThis', $_REQUEST)) {
      global $_lib_u_manage_employee_cfg;

      $ChangeThis = $_REQUEST[$this->prefix . 'ChangeThis'];
      // Decode it
      $match = array();
      preg_match('/(.*)-(.*)$/', substr($ChangeThis, strlen($this->prefix)),
		 &$match);
      $role_name = mx_form_decode_name($match[1]);
      $ix_in_role = $match[2];

      $rlist = mx_form_unescape_key($this->rlist);
      $clist = mx_form_unescape_key($this->clist);
      $cname = NULL;
      for ($ix = 0; $ix < count($rlist); $ix++)
	if ($rlist[$ix] == $role_name) {
	  $cname = $clist[$ix];
	  break;
	}

      $loe_config = $_lib_u_manage_employee_cfg;
      $loe_config['MSGS']['Inspect'] = '¤³¤Î¿¦°÷¤ËÀßÄê¤¹¤ë';
      $list_ids = array('ObjectID', "À«", "Ì¾", "¿¦¼ïÌ¾", "¿¦°ÌÌ¾");
      $loe_config['LIST_IDS'] = $list_ids;

      if ($cname && count(_lib_enum__ee($cname, 'array')) > 0) {
	global $_lib_u_manage_employee_fetch_stmt;
	$loe_config['HSTMT'] = $_lib_u_manage_employee_fetch_stmt .
	  ' AND C."¿¦¼ï" IN ' . _lib_enum__ee($cname, 'sql');
	if ($this->debug) {
	  print "<!-- HSTMT NOW IS\n";
	  print $loe_config['HSTMT'];
	  print ";\n-->\n";
	}
	$loe_config['STMT'] = $loe_config['HSTMT'] .
	  ' AND E."Superseded" IS NULL';
      }
      $loe = new list_of_employees($this->prefix . 'loe-',
				   $loe_config);
      if ($loe->changed() && $loe->chosen()) {
	$o = mx_form_unescape_key($loe->chosen());
	$d = array();
	for ($ix = 0; $ix < count($o); $ix++) {
	  $d[$list_ids[$ix]] = $o[$ix];
	}
	$data[$role_name][$ix_in_role] = array
	  ('¿¦°÷ObjectID' => $d['ObjectID'],
	   '¿¦°÷É½µ­' => _lib_u_manage_patient_employee_fmt_name($d));
	$loe = NULL;

	// If we changed the last one we need to add another dummy.
	if ($ix_in_role == count($data[$role_name]) - 1)
	  $data[$role_name][] = $this->dummy_assignment($role_name);
      }
      elseif (array_key_exists($this->prefix . 'DontChangeIt', $_REQUEST)) {
	$loe = NULL;
      }
      elseif (array_key_exists($this->prefix . 'UnassignIt', $_REQUEST)) {
	$nd = array();
	foreach ($data[$role_name] as $ix => $d) {
	  if ($ix == $ix_in_role) continue;
	  $nd[] = $d;
	}
	$data[$role_name] = $nd;
	$loe = NULL;
      }
      else {
	if ($ix_in_role == count($data[$role_name]) - 1)
	  $this->ChangeRoleName = $role_name . "¤ÎÄÉ²Ã";
	else
	  $this->ChangeRoleName = "$role_name " .
	    $data[$role_name][$ix_in_role]['¿¦°÷É½µ­'] . " ¤ÎÊÑ¹¹";
	$this->ChangeRoleIx = $ix_in_role;
	$this->ChangeThis = $ChangeThis;
	$this->loe = $loe;
      }
    }

    if ($this->debug) {
      print "<!-- DATA (annotated) \n";
      var_dump($data);
      print "-->\n";
    }
  }

  function draw_body() {
    simple_object_edit::draw_body();
    mx_formi_hidden($this->prefix . 'role-list', $this->rlist);
    mx_formi_hidden($this->prefix . 'role-cat-list', $this->clist);
    mx_formi_hidden($this->prefix . 'role-oid-list', $this->rolist);

    if (! $this->during_subpick())
      return;

    mx_formi_hidden($this->prefix . 'ChangeThis', $this->ChangeThis);

    print "<hr />\n";
    mx_titlespan($this->ChangeRoleName);

    print "<br />";
    mx_formi_submit($this->prefix . 'DontChangeIt', 1,
		    "<span class=\"link\">ÊÑ¹¹¤·¤Ê¤¤</span>");

    // The last one is dummy.  This is to allow adding a new one.
    if ($this->ChangeRoleIx != count($this->data[$this->ChangeRoleName]) - 1)
      mx_formi_submit($this->prefix . 'UnassignIt', 1,
		      "<span class=\"link\">Ã´Åö¤ò³°¤¹</span>");
    print "<br />";
    $this->loe->draw();
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    if ($this->_validate() != 'ok')
      return 'failure';

    $d_id = $this->id;
    $d =& $this->data;

    // We should always be updating.
    if (! $d_id) return 'failure';

    $curr = $this->fetch_data($d_id);
    $data = $this->data;
    $this->annotate_row_data(&$curr);

    // Compare form data and database data
    $different = 0;
    $rlist = mx_form_unescape_key($this->rlist);
    foreach ($rlist as $role) {
	    if ($this->debug)
		    print "<!-- Checking $role -->\n";
	    $cnt = count($data[$role]);
	    if ($cnt != count($curr[$role])) {
		    $different = 1;
		    break;
	    }
	    $curr_ids = array();
	    $form_ids = array();
	    // Note that the last element in both database and
	    // form are dummies.
	    for ($ix = 0; $ix < $cnt-1; $ix++) {
		    if ($this->debug) {
			    print "<!-- curr ";
			    print $curr[$role][$ix]['¿¦°÷ObjectID'];
			    print " form ";
			    print $data[$role][$ix]['¿¦°÷ObjectID'];
			    print " -->\n";
		    }
		    $curr_ids[] = $curr[$role][$ix]['¿¦°÷ObjectID'];
		    $form_ids[] = $data[$role][$ix]['¿¦°÷ObjectID'];
	    }
	    sort($curr_ids);
	    sort($form_ids);
	    if ($this->debug) {
		    for ($ix = 0; $ix < $cnt-1; $ix++) {
			    print "<!-- curr ";
			    print $curr_ids[$ix];
			    print " form ";
			    print $form_ids[$ix];
			    print " -->\n";
		    }
    }
	    if ($curr_ids != $form_ids) {
		    $this->log('Changed ' . $role);
		    $different = 1; break;
	    }
    }

    if (! $different) {
      $this->log('No Change');
      return 'ok';
    }

    // Update things.
    $patient_id = $curr['*META*'][1];
    $created_by = $curr['*META*'][2];

    // ... However, hack around to discard the initial emptiness
    $cnt = 0;
    foreach ($rlist as $role) {
      $cnt += count($curr[$role]) - 1;
    }
    if ($cnt == 0)
      ; // Skip stashing
    else {
      $stash_id = $this->_allocate_unused_id($db);
      $stmt = ('INSERT INTO "´µ¼ÔÃ´Åö¿¦°÷" '.
	       '("ID", "ObjectID", "Superseded", "CreatedBy", "´µ¼Ô") '.
	       'VALUES (' . $d_id . ', ' . $stash_id . ', now(), ' .
	       mx_db_sql_quote($created_by) . ', ' .
	       $patient_id . ')');
      $this->log("-- Stash\n$stmt;\n");
      if (! pg_query($db, $stmt)) {
	$this->err(pg_last_error($db));
	return 'failure';
      }
      $stmt = ('UPDATE "´µ¼ÔÃ´Åö¿¦°÷¥Ç¡¼¥¿" SET "´µ¼ÔÃ´Åö¿¦°÷" = ' .
	       $stash_id . ' WHERE "´µ¼ÔÃ´Åö¿¦°÷" = ' . $d_id);
      if (! pg_query($db, $stmt)) {
	$this->err(pg_last_error($db));
	return 'failure';
      }
    }
    $stmt = ('UPDATE ' .
	     mx_db_sql_quote_name($this->so_config['TABLE']) .
	     ' SET "CreatedBy" = ' .
	     mx_db_sql_quote($mx_authenticate_current_user) .
	     ' WHERE "ObjectID" = ' . mx_db_sql_quote($d_id) .
	     ' AND "Superseded" IS NULL');
    $this->log("-- Update\n$stmt;\n");
    if (! pg_query($db, $stmt)) {
      $this->err(pg_last_error($db));
      return 'failure';
    }

    $rolist = mx_form_unescape_key($this->rolist);
    for ($iy = 0; $iy < count($rlist); $iy++) {
      $roid = $rolist[$iy];
      $role = $rlist[$iy];
      $cnt = count($data[$role]);
      // Note that the last element in form are dummies.
      for ($ix = 0; $ix < $cnt-1; $ix++) {
	$eoid = $data[$role][$ix]['¿¦°÷ObjectID'];
	$stmt = ('INSERT INTO "´µ¼ÔÃ´Åö¿¦°÷¥Ç¡¼¥¿" '.
		 '("´µ¼ÔÃ´Åö¿¦°÷", "¿¦°÷", "Ã´ÅöÌò³ä") VALUES (' .
		 $d_id . ', ' . $eoid . ', ' . $roid . ')');
	$this->log("-- Update\n$stmt;\n");
	if (! pg_query($db, $stmt)) {
	  $this->err(pg_last_error($db));
	  return 'failure';
	}
      }
    }
    $this->log('Updated');

    if (! pg_query($db, 'commit')) {
      $this->err(pg_last_error($db));
      return 'failure';
    }

    return 'ok';
  }

}

?>
