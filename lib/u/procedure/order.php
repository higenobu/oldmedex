<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/proceduremaster-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/procedure/procedureset-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/procedure/procedurecategory-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/procedure/proceduremaster2-pick.php';

$_lib_u_procedure_order_base_stmt =
'SELECT O."ObjectID" as "ProcedureOrderID", O.*, (E."姓" || E."名") AS "Orderer",
 O."OrderDate", O."ExecDate"
FROM "procedure_order" AS O
LEFT JOIN "職員台帳" AS E
ON E."userid" = O."CreatedBy" AND E."Superseded" IS NULL';

$_lib_u_procedure_order_cfg = array
(
 'TABLE' => 'procedure_order',
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'OrderDate',
 'COLS' => array('ProcedureOrderID', 'ProcedureName', 'title'),
 'LCOLS' => array(
		  array('Column' => 'OrderDate',
			'Label' => '依頼日',
			'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			),
		  array('Column' => 'title',
			'Label' => 'タイトル'),
		  array('Column' => 'Orderer',
			'Label' => '依頼者'),
		  array('Column' => 'part',
			'Label' => '部位'),
		  array('Column' => 'Cancelled',
			'Label' => '中止日',
			'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			),
		  /*
		   array('Column' => 'CanceledBy',
			 'Label' => '中止記入者',
			 'Draw' => 'mx_authenticate',
			 ),
		  */
		  ),
 'ECOLS' => array (array('Column' => 'ObjectID', 'Draw' => NULL),
		   array('Column' => 'OrderDate',
			 'Label' => '依頼日',
			 'Draw' => 'static'),
		   array('Column' => 'ExecDate',
			 'Label' => '実施日',
			 'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			 ),
		   array('Column' => 'title',
			 'Label' => '処置タイトル'
			 ),
		   array('Column' => 'Procedure',
			 'Label' => '処置項目',
			 'Draw' => 'tableview'),
		   array('Column' => 'part',
			 'Label' => '部位',
			 ),
		   array('Column' => 'comment',
			 'Label' => '備考',
			 'Draw' => 'textarea'
			 ),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp')
		   ),
 'DCOLS' => array (array('Column' => 'OrderDate',
			 'Label' => '依頼日',
			 'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			 ),
		   array('Column' => 'ExecDate',
			 'Label' => '実施日',
			 'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			 ),
		   array('Column' => 'title',
			 'Label' => '処置タイトル'
			 ),
		   array('Column' => 'Procedure',
			 'Label' => '処置項目',
			 'Draw' => 'tableview'),
		   array('Column' => 'part',
			 'Label' => '部位',
			 ),
		   array('Column' => 'comment',
			 'Label' => '備考',
			 'Draw' => 'textarea'
			 ),
		   array('Column' => 'Cancelled',
			 'Label' => '中止日',
			 'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			 ),
		   array('Column' => 'CanceledBy',
			 'Label' => '中止記入者',
			 'Draw' => 'mx_authenticate',
			 ),
array('Column' => 'recorded',
'Label' => '記録', 'Draw' => 'timestamp')
		   ),
 'ICOLS' => array ('OrderDate', 'Patient', 'ExecDate', 'title', 'part', 'comment','recorded'),
 'HSTMT' => $_lib_u_procedure_order_base_stmt . ' WHERE (NULL IS NULL) ',
 'STMT' => $_lib_u_procedure_order_base_stmt . ' WHERE (O."Superseded" IS NULL) ',
 'UNIQ_ID' => 'O."ObjectID"'
 );

function _lib_u_procedure_order_fetch_data($it, $oid) {
  global $_lib_u_procedure_order_cfg;

  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_procedure_order_cfg['HSTMT'] .
	   'AND O."ObjectID" = ' . mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Main: $stmt;\n");
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];

  // Fetch from subtables.
  $stmt = 'SELECT C."ProcedureID" as "Procedure", C.amount, C.duration,
                  M."Name" AS "ProcedureName", M."ObjectID" as "ProcedureID",
                  G."Name" AS "Category"
           FROM "procedure_order" AS O, "procedure_order_content" AS C,
                "procedure_master" AS M, "procedure_category" AS G
           WHERE O."ObjectID" = C."ProcedureOrder" AND
                 M."ObjectID" = C."ProcedureID" AND
                 G."ObjectID" = M."Category" AND
                 M."Superseded" IS NULL AND
                 G."Superseded" IS NULL AND
                 O."ObjectID" = ' . mx_db_sql_quote($oid);
  $stmt = $stmt . ' ORDER BY M."SortOrder"';
  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['Procedure'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  if (is_array($d)) {
    foreach ($d as $row)
      $data['Procedure'][] = array_map('mx_trim', $row);
  }
  return $data;
}

class list_of_procedure_orders extends list_of_ppa_objects {
  var $default_row_per_page = 4;
  var $patient_column_name_quoted = '"Patient"';

  function list_of_procedure_orders($prefix, $cfg=NULL) {
    global $_lib_u_procedure_order_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_procedure_order_cfg;
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
  
  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
      $paging_orders[] = (($col == 'OrderDate') ? 1 : 0);
    }
    return $paging_orders;
  }

  function dx_mx_authenticate($desc, $data, $changed) {
    if(!is_null($data)) {
      $u = get_mx_authenticate_user($data);
      $this->_dx_textish($u['氏名'], $changed, 'noquote');
    }
  }

}

class procedure_order_display extends simple_object_display {
  function procedure_order_display($prefix, $cfg=NULL) {
    global $_lib_u_procedure_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_procedure_order_cfg;
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

  function fetch_data($id) {
    return _lib_u_procedure_order_fetch_data(&$this, $id);
  }

  function dx_mx_authenticate($desc, $data, $changed) {
    if(!is_null($data)) {
      $u = get_mx_authenticate_user($data);
      $this->_dx_textish($u['氏名'], $changed, 'noquote');
    }
  }

  function dx_tableview($desc, $data, $changed) {
    ob_start();
    if (is_array($data)) {
      print "<table style=\"border: solid 1px\" class='listofstuff'>";
      print "<tr>";
      print "<td style=\"border-bottom: solid 1px; border-right: solid 1px\">カテゴリー</td><td style=\"border-bottom: solid 1px; border-right: solid 1px\">処置項目</td><td style=\"border-bottom: solid 1px; border-right: solid 1px\">回数/数量</td><td style=\"border-bottom: solid 1px; border-right: solid 1px\">時間</td>";
      print "</tr>";
      $prev_category = '';
      foreach ($data as $row) {
	if ($prev_category != $row['Category']) {
	  $prev_category = $row['Category'];
	  $cate = htmlspecialchars($row['Category']);
	}
	else
	  $cate = '';
	$id = $row['ProcedureID'];
	$subname = $this->prefix . "pitem" . $id;
	$pname = htmlspecialchars($row['ProcedureName']);
	$amount = htmlspecialchars($row['amount']);
	$duration = htmlspecialchars($row['duration']);
	print "<tr>";
	print "<td style=\"border-right: solid 1px\">${cate}</td><td style=\"border-bottom: solid 1px; border-right: solid 1px;\">${pname}</td><td style=\"border-bottom: solid 1px; border-right: solid 1px; text-align: right\">${amount}</td><td style=\"border-bottom: solid 1px; text-align: right\">${duration}</td>";
	print "</tr>";
      }
      print "</table>";
    }else
      print mx_empty_field_mark();
    $value = ob_get_contents();
    ob_end_clean();
    $this->_dx_textish($value, $changed, 'noquote');
  }
  
  function draw_body_2($data, $hdata, $dcols) {
    $id = $this->id;
    $pt_id = $this->so_config['Patient_ID'];
    if(is_null($data["Cancelled"]) and $data['ExecDate'] >= date("Y-m-d"))
      print "<button type=\"submit\" name=\"cancel_order\" value=\"${id}\">この依頼を中止する</button>";
  }
}

class procedure_order_edit extends simple_object_edit {
  var $debug = 1;
  function procedure_order_edit($prefix, $cfg=NULL) {
    global $_lib_u_procedure_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_procedure_order_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $cfg);
  }

  function fetch_data($id) {
    $x = _lib_u_procedure_order_fetch_data(&$this, $id);
    return $x;
  }

  function data_compare($curr, $data) {
    foreach (array('Name', 'SortOrder') as $col)
      if ($curr[$col] != $data[$col]) return 1;
    if (count($curr['Procedure']) != count($data['Procedure'])) return 1;
    $cpid = array();
    $dpid = array();
    foreach ($curr['Procedure'] as $r) $cpid[] = $r['Procedure'];
    foreach ($data['Procedure'] as $r) $dpid[] = $r['Procedure'];
    return $cpid != $dpid;
  }

  function anew_tweak($orig_id) {
    $this->data['OrderDate'] = mx_today_string();
    $this->data['ExecDate'] = mx_today_string();
$this->data['recorded'] = date("Y-m-d H:i:s");
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_proceduremaster_pick_cfg;

    $data['Patient'] = $this->so_config['Patient_ObjectID'];

    if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'tp'])) {
      $data['Procedure'] = array();
      foreach($_REQUEST[$this->prefix . 'tp'] as $ent) {
	$a = mx_form_unescape_key($ent);
	// must match with LIST_ID in proceduremaster-pick config
	$data['Procedure'][] = array
	  (
	   'ProcedureID' => $a[0],
	   'ProcedureName' => $a[1],
	   'amount' => intval($_REQUEST[$this->prefix.'pitem' .
					$a[0] . 'amount']),
	   'duration' => $_REQUEST[$this->prefix.'pitem' .
				   $a[0] . 'duration']
	   );
      }
    }
    
    $set_param = NULL;
    $proceduremaster2_param = NULL;
    
    if(array_key_exists('set-id-select', $_REQUEST)) {
      $a  = mx_form_unescape_key($_REQUEST['set-id-select']);
      $set_param = $a[0];
      $_REQUEST['soe-Subpick'] = 1;
    }

    if(array_key_exists('proceduremaster2-id-select', $_REQUEST)) {
      $proceduremaster2_param = mx_form_unescape_key($_REQUEST['proceduremaster2-id-select']);
      $_REQUEST['soe-Subpick'] = 1;
      $_REQUEST['soe-subpick-shown'] = 1;
    }

    if (array_key_exists($this->prefix . 'Subpick', $_REQUEST)) {
      $subpick = $_REQUEST[$this->prefix . 'Subpick'];

      $cfg = $_lib_u_manage_proceduremaster_pick_cfg;
      $cfg['ROW_PER_PAGE'] = 1000000;  // prevent paging
      $cfg['SELECTED_ONLY'] = 1;
      $cfg['SHOW_IDS'] = NULL;

      if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	  is_array($_REQUEST[$this->prefix . 'tp']))
	$cfg['Select'] = $_REQUEST[$this->prefix . 'tp'];
      else
	$cfg['Select'] = array();
      //----------------------------
      // fill $cfg['Select'] with preset data
      $preset_data = array();
      if(!is_null($set_param)) {
	$db = mx_db_connect();
	$stmt = 'SELECT "ProcedureID", "Name" as "ProcedureName"
               FROM procedure_set_map S, procedure_master M
               WHERE M."ObjectID" = S."ProcedureID" AND
                     S."Superseded" IS NULL AND
                     M."Superseded" IS NULL AND
                     S."SetID" = ' . 
	  mx_db_sql_quote($set_param);
	$preset_data = pg_fetch_all(pg_query($db, $stmt));
      }elseif(!is_null($proceduremaster2_param)) {
	$preset_data[] = array('ProcedureID' => $proceduremaster2_param[0],
			       'ProcedureName' => $proceduremaster2_param[1]);
      }
      foreach ($preset_data as $v) {
	$found = False;
	foreach($cfg['Select'] as $s) {
	  if ($s['ProcedureID'] == $v['ProcedureID']) {
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

      $this->Subpicker = new proceduremaster_pick($this->prefix . 'tsp-', $cfg);
      $this->Subpick = array('Column' => '処置項目リスト',
			     'Subpick' => array());
    }
    if ($preset_data)
      unset($_REQUEST['soe-tsp-HSelect-empty']);
    simple_object_edit::annotate_form_data(&$data);
  }

  function dx_tableview($desc, $name, $data) {
    if (!$this->Subpick && is_array($data)) {
      print "<table class='listofstuff'>";
      print "<tr>";
      print "<td>処置項目</td><td>回数/数量</td><td>時間</td>";
      print "</tr>";
      foreach ($data as $row) {
	$id = $row['ProcedureID'];
	$subname = $this->prefix . "pitem" . $id;
	$cate = $row['Category'];
	$pname = htmlspecialchars($row['ProcedureName']);
	// proc id
	ob_start();
	mx_formi_hidden($name, $id);
	//amount
	ob_start();
	mx_formi_text($subname . 'amount', $_REQUEST[$subname . 'amount' ] ?
		      $_REQUEST[$subname . 'amount' ] : $row['amount']);
	$amount = ob_get_contents();
	ob_end_clean();
	//duration
	ob_start();
	mx_formi_text($subname . 'duration', $_REQUEST[$subname . 'duration' ] ?
		      $_REQUEST[$subname . 'duration' ] : $row['duration']);
	$duration = ob_get_contents();
	ob_end_clean();
	print "<tr>";
	print "<td>${pname}</td><td>${amount}</td><td>${duration}</td>";
	print "</tr>";
	mx_formi_hidden($this->prefix . 'tp[]',
			mx_form_escape_key
			(array($row['ProcedureID'], $row['ProcedureName'])));
      }
      print "</table>";
    }else
	    print mx_empty_field_mark();
    if (!$this->Subpick)
      mx_formi_submit($this->prefix . 'Subpick', 0,
		      "<span class=\"link\">処置項目選択</span>");
  }

  function accept_subpick($subpick, $chosen) {
    $this->log('AS0' . mx_var_dump($this));
    $this->log('AS1' . mx_var_dump($chosen));
    if (is_array($chosen)) {
      $d = array();
      foreach ($chosen as $v) {
	$a = mx_form_unescape_key($v);
	// must match with LIST_ID in proceduremaster-pick config
	$d[] = array('ProcedureID' => $a[0],
		     'ProcedureName' => $a[1]);
      }
      $this->data['Procedure'] = $d;
    }
    $this->log('AS2' . mx_var_dump($this));
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "procedure_order_content" SET "ProcedureOrder" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "ProcedureOrder" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
    foreach ($this->data['Procedure'] as $r) {
      $pid = $r['ProcedureID'];
      $amount = $r['amount'];
      $duration = mx_db_sql_quote($r['duration']);
      if($duration == "''")
	$duration = 'NULL';
      $stmt = <<<SQL
	INSERT INTO "procedure_order_content"
	("ProcedureOrder", "ProcedureID", amount, duration)
	VALUES
	(
	 $id,
	 $pid,
	 $amount,
	 $duration
	 )
SQL;
      
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }

  function commit($force=NULL) {

    simple_object_edit::commit($force);
//1030-2014
//after commit, insert into karte
 
$wkid = $this->data['ObjectID'];
$kaishi = $this->data['OrderDate'];
$date=substr($kaishi,0,10);
$p_oid = $this->data['Patient'];
//using fetch_data
$insdata= _lib_u_procedure_order_fetch_data(&$this, $wkid);
$ocont="";
//print_r($insdata['Procedure']);
for ($i=0;$i<count($insdata['Procedure']);$i++){
$cc=$insdata['Procedure'][$i]['Category'];
$pp= $insdata['Procedure'][$i]['ProcedureName'];
$aa= $insdata['Procedure'][$i]['amount'];
$dd= $insdata['Procedure'][$i]['duration'];

$ocont=$ocont."Category=".$cc." Proc-name=".$pp." Proc amount=".$aa."Proc Duration=".$dd.
"  |\n";
}

$ocont="------------------\n"."処置"."\n".$ocont;


$db = mx_db_connect();

//insert into karte

 
$stmt10 = <<<SQL
select * from "カルテデモ表" where "日付"='$date' and "患者"=$p_oid and 
	"Superseded" is null;
SQL;
 


$rs0 = mx_db_fetch_all($db, $stmt10);

if (count($rs0) == 0){
$stmt11 = <<<SQL
INSERT INTO "カルテデモ表" ("患者", "日付","P") values ($p_oid,'$date','$ocont');
SQL;
 
//print $stmt11;

if (pg_query($db, $stmt11)){

	}
else {
print '<p > karte insert DB access error</p>';
die;
	}

 }

else{ 

 for ($i=0;$i<count($rs0);$i++){	
 $pp=$rs0[$i]["P"];
 $idd=$rs0[$i]["ID"];
// print $pp."=";
$ocont2=$pp.'\r\n'.'-----(updated to)-----'."\n".$ocont;


$stmt1 = <<<SQL
   	update  "カルテデモ表" set "P"='$ocont2' where "日付"='$date' and "患者"=$p_oid and 
	"Superseded" is null and "ID"=$idd
SQL;
//print $stmt1;
if (pg_query($db, $stmt1)){

}
else {
print '<p > karte update DB access error</p>';
die;
}

}

} //end else


//end of insert into karte

//
    $date = $this->data['ExecDate'];
    $match = array();
    if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	    $date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);
	    mx_kick_claim_if_by_poid($db, $this->so_config['Patient_ObjectID'],
				     $date);
    }
  }
}

function procedure_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	$result = array();
	$num_limit = 0;
	if (!is_null($time_from))
		$limit[] = 'K."ExecDate" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'K."ExecDate" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;
	if (mx_check_option('OmitCancelled', $options))
		$limit[] = 'K."Cancelled" IS NULL';
	$limit[] = 'K."Superseded" IS NULL';
	
	$sql = 'SELECT "ObjectID", "OrderDate", "ExecDate", title FROM procedure_order AS K WHERE "Patient"=' .
	  mx_db_sql_quote($p_oid);
	if(count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}
	$sql .= ' ORDER BY "ExecDate" ';
	if ($num_limit) {
		$sql .= ' LIMIT 30';
	}
	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;

	$application = '/u/procedure/order.php';

	foreach($all as $e) {
	  $oid = $e['ObjectID'];
	  $all2 = _lib_u_procedure_order_fetch_data(NULL, $oid, $p_oid, True);
	  if ($all2 === false)
	    continue;

	  $primary = '依頼日:'.$e['OrderDate'];

	  $url = sprintf("$application?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $oid);
	  $fuller = '';
	  $fuller2 = $e['ExecDate'] ."\n";
	  $vb_array = array();
	  $firstrow = True;
	  foreach($all2['Procedure'] as $re){
	    if(!$firstrow) {
	      $fuller .= ", ";
	      $firstrow = False;
	    }
	    /*
	    $item = htmlspecialchars($re['ProcedureName'].':'.
				     $re['amount'].
				     $re['duration']);
	    */
	    $item = htmlspecialchars($re['ProcedureName']);
	    $fuller .= $item . "<br />";
	    $fuller2 .= $item . "\n";
	  }
	  $fuller2 .= "------------------------------------------------\n";
	  $oe_date = $e['ExecDate'] ? $e['ExecDate'] : $e['OrderDate'];
	  if($oe_date) {
	    $_oe_date = explode(' ', $oe_date);
	    if(is_array($_oe_date) and count($_oe_date) == 2)
	      $oe_date = $_oe_date[0];
	  }
	  $text = sprintf("(処置)");
	  $result[] = array('timestamp' => $oe_date,
			    'text' => $text,
			    'fuller' => $fuller,
			    'callback_url' => $url,
			    'thumb' => NULL,
			    'object_id' => $oid,
			    );
	}
	return $result;
}

/*
 * Return an SQL boolean snippet that tells if given patient P has
 * an order within that range of dates (or the default one)
 */
function procedure_module_index_info_patient_sql($date_from, $date_to, $options=NULL)
{
	$limit = array();
	if (!is_null($date_from))
		$limit[] = 'K."ExecDate" >= '. mx_db_sql_quote($date_from);
	if (!is_null($date_to))
		$limit[] = 'K."ExecDate" <= '. mx_db_sql_quote($date_to);
	$limit[] = 'K."Superseded" IS NULL';
	if (mx_check_option('OmitCancelled', $options))
		$limit[] = 'K."Cancelled" IS NULL';
	$limit = implode(' AND ', $limit);

	return <<<SQL
		EXISTS (SELECT 1 FROM procedure_order AS K
			WHERE K."Patient" = P."ObjectID"
			AND $limit)
SQL;
}

?>
