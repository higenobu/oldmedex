<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order.php';

class list_of_stest_orders extends list_of_test_orders {
  var $debug=1;
  var $default_row_per_page = 4;
  
  function list_of_stest_orders($prefix, $cfg=NULL) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_test_order_cfg;
    if(array_key_exists('ShowLoo', $_REQUEST))
      unset($_REQUEST['sod-id']);
    list_of_test_orders::list_of_test_orders($prefix, $cfg);
  }
  
  function base_fetch_stmt_0() {
    return (list_of_ppa_objects::base_fetch_stmt_0() .
	    ' AND test_app_type=1');
  }
}

class stest_order_edit extends test_order_edit {
  function stest_order_edit($prefix, $cfg=NULL) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_test_order_cfg;
    $cfg['ECOLS'] = array (array('Column' => 'ObjectID', 'Draw' => NULL),
			   array('Column' => 'PatientGroup', 'Draw' => NULL),
			   array('Column' => 'DrCode',
				 'Label' => '»Ø¼¨°å',
				 'Draw' => 'enum',
				 'Enum' =>  _lib_u_test_get_doctors3() ,
				 'Option' => array('validate' => 'nonnull'),
				 ),
			   array('Column' => 'OrderDate',
				 'Label' => '°ÍÍêÆü',
				 'Draw' => 'static',
				 ),	   
			   array('Column' => 'SampleDate',
				 'Label' => '¸¡ººÆü',
				 'Draw' => 'date',
				 'Option' => array('validate' => 'date_not_in_past'),
				 ),
			   array('Column' => 'title',
				 'Label' => '¸¡ºº¥¿¥¤¥È¥ë',
				 'Draw' => 'textarea'
				 ),
			   array('Column' => 'scheduled',
				 'Label' => 'Äê´ü',
				 'Enum' => array(1 => 'Î×»þ',
						 2 => 'Äê´ü',
						 ),
				 'Draw' => 'enum',
				 ),
			   array('Column' => 'urgent',
				 'Label' => '»êµÞ',
				 'Enum' => array(1 => 'ÄÌ¾ï',
						 2 => '»êµÞ',
						 ),
				 'Draw' => 'enum',
				 ),
			   /*
 array('Column' => 'type',
		   'Label' => '»ö¸å',
		   'Enum' => array(1 => 'ÄÌ¾ï',
			 2 => '»ö¸å',
					 ),
					 'Draw' => 'enum',
			 ),
			   */
			   array('Column' => 'Test',
				 'Label' => '¸¡ºº¹àÌÜ',
				 'Draw' => 'tableview'),
			   array('Column' => 'comment',
				 'Label' => '°ÍÍê»þ¥³¥á¥ó¥È',
				 ),
			   array('Column' => 'printer',
				 'Label' => '°õºþ¾ì½ê',
				 'Draw' => 'radio',
				 'Enum' => array('KENSA' => '¸¡ºº¼¼',
						 ),
				 'Option' => array('validate' => 'nonnull'),
				 ),
			   );
    test_order_edit::test_order_edit($prefix, $cfg);
  }

  function anew_tweak($orig_id) {
    $this->data['OrderDate'] = mx_today_string();
    $this->data['SampleDate'] = mx_today_string();
    $this->data['Cancelled'] = NULL;
    $this->data['clip_num'] = NULL;
    $this->data['test_app_type'] = 1;
    $this->data['printer'] = 'KENSA';
    $ptinfo = mx_draw_patientinfo_get_data($this->so_config['Patient_ObjectID']);
    $this->data['PatientGroup'] = $ptinfo['´õË¾ÉÂÅï'];
  }
  
  function duplicate_tweak($attr) {
    if (array_key_exists('DuplicateDate', $attr)) {
      $this->data['SampleDate'] = $attr['DuplicateDate'];
    }
    $this->so_config['Patient_ObjectID'] = $this->data['Patient'];
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_testmaster_pick_cfg;
    $data['Patient'] = $this->so_config['Patient_ObjectID'];
    
    
    if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'tp'])) {
      $data['Test'] = array();
      foreach($_REQUEST[$this->prefix . 'tp'] as $ent) {
	$a = mx_form_unescape_key($ent);
	// must match with LIST_ID in testmaster-pick config
	$data['Test'][] = array('TestID' => $a[0],
				'TestName' => $a[1]);
      }
    }
    
    $set_param = NULL;
    $testmaster2_param = NULL;
    $preset_data = array();
    
    if(array_key_exists('testmaster2-id-select', $_REQUEST)) {
      $testmaster2_param = mx_form_unescape_key($_REQUEST['testmaster2-id-select']);
      if(is_null($_REQUEST['soe-Subpick'])
	 and !is_null($this->id)
	 and $this->id != '') {
	$db = mx_db_connect();
	$stmt = 'select M."ObjectID" as "TestID", M."Name" as "TestName"
                   from test_master M , test_order_content C
                  where M."ObjectID" = C."TestID" AND "TestOrder"=' .
	  mx_db_sql_quote($this->id);
	$preset_data = pg_fetch_all(pg_query($db, $stmt));
      }

      $_REQUEST['soe-Subpick'] = 1;
      $_REQUEST['soe-subpick-shown'] = 1;
    }

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST)) {
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];
      $cfg = $_lib_u_manage_testmaster_pick_cfg;
      if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	  is_array($_REQUEST[$this->prefix . 'tp']))
	$cfg['Select'] = $_REQUEST[$this->prefix . 'tp'];
      else
	$cfg['Select'] = array();
      //----------------------------
      // fill $cfg['Select'] with preset data
      if(!is_null($set_param)) {
	$db = mx_db_connect();
	$stmt = 'SELECT "TestID", "Name" as "TestName"
               FROM test_set_map S, test_master M
               WHERE M."ObjectID" = S."TestID" AND
                     S."Superseded" IS NULL AND
                     M."Superseded" IS NULL AND
                     S."SetID" = ' . 
	  mx_db_sql_quote($set_param);
	$preset_data = pg_fetch_all(pg_query($db, $stmt));
      }elseif(!is_null($testmaster2_param)) {
	$preset_data[] = array('TestID' => $testmaster2_param[0],
			       'TestName' => $testmaster2_param[1]);
      }
      foreach ($preset_data as $v) {
	$found = False;
	foreach($cfg['Select'] as $s) {
	  if ($s['TestID'] == $v['TestID']) {
	    $found = True;
	    break;
	  }
	}
	if(!$found)
	  $cfg['Select'][] = mx_form_escape_key($v);
      }
      // do the same for HSelect.
      foreach ($preset_data as $v) {
	$_REQUEST['soe-tsp-HSelect'][] = mx_form_escape_key($v);
	$_REQUEST['soe-tsp-subpick-shown'][] = mx_form_escape_key($v);
	$_REQUEST['soe-tsp-subpick-value-' . mx_form_escape_key($v)]='on';
      }

      //----------------------------
      $cfg['TEST_APP_TYPE'] = 1;
      $this->Subpicker = new testmaster_pick($this->prefix . 'tsp-', $cfg);
      $this->Subpick = array('Column' => '¸¡ºº¹àÌÜ¥ê¥¹¥È',
			     'Subpick' => array());
    }
    if ($preset_data)
      unset($_REQUEST['soe-tsp-HSelect-empty']);

    if ($_REQUEST['soe-tsp-subpick-done']) {
      foreach($this->Subpicker->selected as $e) {
	$a = mx_form_unescape_key($e);
	$this->data['title'] .= " " . $a[1];
      }
      $this->data['title'] = trim($this->data['title']);
    }

    simple_object_edit::annotate_form_data(&$data);
    $data['test_app_type'] = 1;
  }
}

class stest_order_edit2 extends test_order_edit2 {
  function stest_order_edit2($prefix, $cfg=NULL) {
    test_order_edit2::test_order_edit2($prefix, $cfg);
  }
}

/*
 * This is used by index-pt via lib/ord_module.php.
 */
function stest_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	$result = array();
	$num_limit = 0;
	if (!is_null($time_from))
		$limit[] = 'K."SampleDate" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'K."SampleDate" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;
	if (mx_check_option('OmitCancelled', $options))
		$limit[] = 'K."Cancelled" IS NULL';
	$limit[] = 'K."Superseded" IS NULL';
	
	$sql = 'SELECT "ObjectID", "OrderDate", "SampleDate", urgent, scheduled, title FROM test_order AS K WHERE K.test_app_type=1 AND "Patient"=' .
	  mx_db_sql_quote($p_oid);
	if(count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}
	$sql .= ' ORDER BY "SampleDate" ';
	if ($num_limit) {
		$sql .= ' LIMIT 30';
	}
	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;

	$application = '/u/test/sorder.php';

	foreach($all as $e) {
	  $oid = $e['ObjectID'];
	  $all2 = _lib_u_test_order_fetch_data(NULL, $oid, $p_oid, True);
	  if ($all2 === false)
	    continue;

	  $primary = '°ÍÍêÆü:'.$e['OrderDate'];

	  $url = sprintf("$application?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $oid);
	  if($e['Arriving']>30)
	    $text = sprintf("(À¸Íý¸¡ºº·ë²Ì) %s %s %s %s",
			    $primary,
			    $e['urgent'] == 1 ? 'ÄÌ¾ï' : '»êµÞ',
			    $e['scheduled'] == 1 ? 'Î×»þ' : 'Äê´ü',
			    $e['title']);
	  else
	    $text = sprintf("(À¸Íý¸¡ºº) %s %s %s",
			    $e['urgent'] == 1 ? 'ÄÌ¾ï' : '»êµÞ',
			    $e['scheduled'] == 1 ? 'Î×»þ' : 'Äê´ü',
			    $e['title']);
	  $fuller = '';
	  $fuller2 = $e['SampleDate'] ."\n";
	  $vb_array = array();
	  $firstrow = True;
	  foreach($all2['Test'] as $re){
	    if(!$firstrow) {
	      $fuller .= ", ";
	      $firstrow = False;
	    }
	    $item = htmlspecialchars($re['TestName'].':'.
				     $re['TestValue'].
				     $re['TestUnit']);
	    $fuller .= $item . "<br />";
	    $fuller2 .= $item . "\n";
	  }
	  $fuller2 .= "------------------------------------------------\n";
	  $oe_date = $e['SampleDate'] ? $e['SampleDate'] : $e['OrderDate'];
	  if($oe_date) {
	    $_oe_date = explode(' ', $oe_date);
	    if(is_array($_oe_date) and count($_oe_date) == 2)
	      $oe_date = $_oe_date[0];
	  }
	  $result[] = array('timestamp' => $oe_date,
			    'text' => $text,
			    'fuller' => $fuller,
			    'callback_url' => $url,
			    'thumb' => NULL,
			    'object_id' => $oid,
			    'value_blob' => mx_form_escape_key(array('TEST_RESULT',$fuller2)),
			    'html' => test_module_draw_row($e)
			    );
	}
	return $result;
}

/*
 * Return an SQL boolean snippet that tells if given patient P has
 * an order within that range of dates (or the default one)
 */
function stest_module_index_info_patient_sql($date_from, $date_to, $options=NULL)
{
	$limit = array();
	if (!is_null($date_from))
		$limit[] = 'K."SampleDate" >= '. mx_db_sql_quote($date_from);
	if (!is_null($date_to))
		$limit[] = 'K."SampleDate" <= '. mx_db_sql_quote($date_to);
	$limit[] = 'K."Superseded" IS NULL';
	if (mx_check_option('OmitCancelled', $options))
		$limit[] = 'K."Cancelled" IS NULL';
	$limit = implode(' AND ', $limit);

	return <<<SQL
		EXISTS (SELECT 1 FROM test_order AS K
			WHERE K.test_app_type=1 AND K."Patient" = P."ObjectID"
			AND $limit)
SQL;
}

?>
