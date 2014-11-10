<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/patientinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/simple-object.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/testmaster-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/testset-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/testcategory-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/testmaster2-pick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ooo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/vital-data.php';

$decision_str = array(array('Label' => '未判定'),
		      array('Label' => '正常',
			    'Color' => 'green'),
		      array('Label' => '過小',
			    'Color' => 'red'),
		      array('Label' => '過大',
			    'Color' => 'red')
		      );
function get_arriving() {
  return array(NULL => '未送信',
	       '10' => '送信済',
	       '20' => '一部到着',
	       '30' => '到着済',
	       '40' => '一部結果',
	       '50' => '完了',
	       );
}

function _lib_u_test_get_doctors() {
  global $_mx_employee_labosystemcode;

  if ($_mx_employee_labosystemcode)
    $id_col = $_mx_employee_labosystemcode;
  else
    $id_col = 'ObjectID';

  $db = mx_db_connect();
  $stmt = <<<SQL
    select E."${id_col}" as id , E."姓" || E."名" as name
    from "職員台帳" E JOIN "職種一覧表" T ON E."職種" = T."ObjectID"
    AND E."Superseded" IS NULL
    AND T."Superseded" IS NULL
    where E."${id_col}" is not null
    order by E."userid"
SQL;
  $rows =  mx_db_fetch_all($db, $stmt);
  $ret = array(NULL => '指定なし');
  foreach($rows as $row)
    $ret[$row['id']] = $row['name'];
  return $ret;
}

function normal_value($bottom, $top, $text, $value) {
  $n = is_numeric($value);

  if(!is_null($text))
    return array($text, trim($text) == trim($value) ? 1 : 0);

  $r = 0;
  $s = '';

  if(is_null($bottom) and !is_null($top)) {
    $s = sprintf("%s <", $top);
    if($n) {
      if(floatval($value) <= floatval($top))
	$r = 1;
      else
	$r = 3;
    }
  }
  elseif(!is_null($bottom) and is_null($top)){
    $s = sprintf("< %s", $bottom);
    if($n) {
      if(floatval($bottom) >= floatval($value))
	$r = 1;
      else
	$r = 2;
    }
  }
  elseif(!is_null($bottom) and !is_null($top)) {
    $s = sprintf("%s - %s", $bottom, $top);
    if ($n) {
      if(!(floatval($bottom) <= floatval($value))){
	$r = 2;
      }
      elseif(!(floatval($value) <= floatval($top))){
	$r = 3;
      }
      else{
	$r = 1;
      }
    }
  }
  return array($s, $r);
}

if ($_mx_ct) {
$_lib_u_test_order_base_stmt = <<<SQL
   SELECT O.*,
          (E."姓" || E."名") AS "Orderer",
          CT."治験名" as "治験名", CTS."ラベル" as "ラベル"
   FROM "test_order" AS O
   LEFT JOIN "職員台帳" AS E
   ON E."userid" = O."CreatedBy" AND E."Superseded" IS NULL
   LEFT JOIN "治験スケジュール" AS CTS
   ON O."CTS"=CTS."ObjectID" AND CTS."Superseded" IS NULL
   LEFT JOIN "治験" AS CT
   ON CT."ObjectID" = CTS."治験" AND CT."Superseded" IS NULL
SQL;
 }else{
$_lib_u_test_order_base_stmt = <<<SQL
   SELECT O.*,
          (E."姓" || E."名") AS "Orderer"
   FROM "test_order" AS O
   LEFT JOIN "職員台帳" AS E
   ON E."userid" = O."CreatedBy" AND E."Superseded" IS NULL
SQL;
 }
$_lib_u_test_order_cfg = array
(
 'TABLE' => 'test_order',
 'ALLOW_SORT' => 1,
 'DEFAULT_SORT' => 'SampleDate',
 'COLS' => array('OrderDate', 'SampleDate', 'scheduled', 'urgent', 'Type',
		 'DrCode', 'Arriving', 'test_app_type', 'printer','title', 'comment',
		 'Orderer','clip_num', 'PatientGroup'
		 ),
 'LCOLS' => array(array('Column' => 'ObjectID',
			'Label' => '検査箋ID',
			),
		  array('Column' => 'Arriving',
			'Label' => '状況',
			'Draw' => 'enum',
			'Enum' => get_arriving(),
			),
		  array('Column' => 'OrderDate',
			'Label' => '依頼日',
			'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			),
		  array('Column' => 'SampleDate',
			'Label' => '検査日',
			'Draw' => 'timestamp',
			'Option' => array('to-seconds' => -1)
			),
		  array('Column' => 'clip_num',
			'Label' => '受付番号',
			),
		  array('Column' => 'Orderer',
			'Label' => '記入者'),
		  array('Column' => 'DrCode',
			'Label' => '指示医',
			'Draw' => 'enum',
			'Enum' => _lib_u_test_get_doctors(),
			),
		  array('Column' => 'title',
			'Label' => '検査タイトル',
			'Draw' => 'trim',
			'Option' => array('Length' => 20),
			),
		  array('Column' => 'scheduled',
			'Label' => '定期',
			'Enum' => array(1 => '臨時',
					2 => '定期',
					),
			'Draw' => 'enum',
			),
		   array('Column' => 'urgent',
			 'Label' => '至急',
			 'Enum' => array(1 => '通常',
					 2 => '至急',
					 ),
			 'Draw' => 'enum',
			 ),
		  array('Column' => 'comment',
		        'Label' => '依頼時コメント',
			),
		  ),	 
 'ECOLS' => array (array('Column' => 'ObjectID', 'Draw' => NULL),
		   array('Column' => 'PatientGroup', 'Draw' => NULL),
		   array('Column' => 'DrCode',
			 'Label' => '指示医',
			 'Draw' => 'enum',
			 'Enum' => _lib_u_test_get_doctors(),
			 'Option' => array('validate' => 'nonnull'),
			 ),
		   array('Column' => 'OrderDate',
			 'Label' => '依頼日',
			 'Draw' => 'static',
	  		 ),	   
		   array('Column' => 'SampleDate',
			 'Label' => '検査日',
			 'Draw' => 'date',
			 'Option' => array('validate' => 'date_not_in_past'),
			 ),
		   array('Column' => 'title',
			 'Label' => '検査タイトル',
			 'Draw' => 'textarea'
			 ),
		   array('Column' => 'scheduled',
			 'Label' => '定期',
			 'Enum' => array(1 => '臨時',
					 2 => '定期',
					 ),
			 'Draw' => 'enum',
			 ),
		   array('Column' => 'urgent',
			 'Label' => '至急',
			 'Enum' => array(1 => '通常',
					 2 => '至急',
					 ),
			 'Draw' => 'enum',
			 ),
/*
		   array('Column' => 'type',
			 'Label' => '事後',
			 'Enum' => array(1 => '通常',
					 2 => '事後',
					 ),
			 'Draw' => 'enum',
			 ),
*/
		   array('Column' => 'Test',
			 'Label' => '検査項目',
			 'Draw' => 'tableview'),
		   array('Column' => 'comment',
			 'Label' => '依頼時コメント',
			 ),
		   ),
 'DCOLS' => array (
		   array('Column' => 'ObjectID',
			'Label' => '検査箋ID',
			),
		   array('Column' => 'Arriving',
			 'Label' => '状況',
			 'Draw' => 'enum',
			 'Enum' => get_arriving(),
			 ),
		   array('Column' => 'OrderDate',
			 'Label' => '依頼日',
			 'Draw' => 'timestamp',
			 'Option' => array('to-seconds' => -1)
			 ),
		   array('Column' => 'SampleDate',
			 'Label' => '検査日',
			 'Draw' => 'timestamp',
			 'Option' => array('to-seconds' => -1)
			 ),
		   array('Column' => 'clip_num',
			 'Label' => '受付番号',
			 ),
		   array('Column' => 'Orderer',
			 'Label' => '記入者'),
		   array('Column' => 'DrCode',
			 'Label' => '指示医',
			 'Draw' => 'enum',
			 'Enum' => _lib_u_test_get_doctors(),
			 ),
		   array('Column' => 'title',
			 'Label' => '検査タイトル'),
		   array('Column' => 'scheduled',
			 'Label' => '定期',
			 'Enum' => array(1 => '臨時',
					 2 => '定期',
					 ),
			 'Draw' => 'enum',
			 ),
		   array('Column' => 'urgent',
			 'Label' => '至急',
			 'Enum' => array(1 => '通常',
					 2 => '至急',
					 ),
			 'Draw' => 'enum',
			 ),
/*
		   array('Column' => 'late_entry',
			 'Label' => '事後',
			 'Enum' => array(1 => '通常',
					 2 => '事後',
					 ),
			 'Draw' => 'enum',
			 ),
*/
		   array('Column' => 'Test',
			 'Label' => '検査項目',
			 'Draw' => 'tableview'),
		   array('Column' => 'comment',
			 'Label' => '依頼時コメント',
			 ),

		   array('Column' => 'LaboComment',
			 'Label' => '検査室コメント',
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
		   ),
 'ICOLS' => array ('OrderDate', 'Patient', 'SampleDate', 'urgent', 'scheduled', 'late_entry', 'comment', 'title', 'DrCode', 'Cancelled', 'test_app_type', 'printer', 'PatientGroup'),
 'HSTMT' => $_lib_u_test_order_base_stmt . ' WHERE (NULL IS NULL) ',
 'STMT' => $_lib_u_test_order_base_stmt . ' WHERE (O."Superseded" IS NULL) ',
 'UNIQ_ID' => 'ObjectID'
 );

if($_mx_test_labosystem) {
  $_lib_u_test_order_cfg['ECOLS'][] =  array('Column' => 'printer',
			   'Label' => '印刷場所',
			   'Draw' => 'radio',
			   'Enum' => array('KENSA' => '検査室',
					   'SYOCHI' => '処置室'),
			   'Option' => array('validate' => 'nonnull'),
			   );
 }

$_lib_u_test_order_cfg2 = array
(
 'TABLE' => 'test_order',
 'COLS' => array('OrderDate', 'SampleDate', 'scheduled', 'urgent', 'Type',
		 'DrCode', 'Arriving', 'test_app_type', 'printer','title', 'comment',
		 'Orderer',
		 ),
 'ECOLS' => array (
		   array('Column' => 'Test',
			 'Label' => '検査項目',
			 'Draw' => 'tableview'),
		   ),
 'HSTMT' => $_lib_u_test_order_base_stmt . ' WHERE (NULL IS NULL) ',
 'STMT' => $_lib_u_test_order_base_stmt . ' WHERE (O."Superseded" IS NULL) ',
 'UNIQ_ID' => 'O."ObjectID"'
 );

if ($_mx_ct) {
  $_lib_u_test_order_cfg['LCOLS'][] = array('Column' => '治験名', 'Label' => '治験');
  $_lib_u_test_order_cfg['LCOLS'][] = array('Column' => 'ラベル', 'Label' => '時期');
}

function _lib_u_test_order_fetch_data($it, $oid, $pt_oid=NULL, $sod=False) {
  global $_lib_u_test_order_cfg;
  $db = mx_db_connect();

  // Fetch from the main table.
  $stmt = ($_lib_u_test_order_cfg['HSTMT'] .
	   'AND O."ObjectID" = ' . mx_db_sql_quote($oid));
  if ($it) $it->dbglog("Fetch-Main: $stmt;\n");
  $data = pg_fetch_all(pg_query($db, $stmt));
  $data = $data[0];

  // Fetch from subtables.
  if($sod==True) {
    // select for SOD
    // select ordered items, and possible child items
    $stmt = '
select
       O."ObjectID" as "TestOrder", O."Patient", O."SampleDate", O.clip_num,

       C."ObjectID" as "TestOrderContent", C."TestID" as "Test", 

       RM."ObjectID" as "TestMaster", RM."Name" as "TestName", 
       RM."LaboSystemCode" as "LaboSystemCode",
       RM."FemaleNormalBottom" AS "NormalBottom",
       RM."FemaleNormalTop" AS "NormalTop",
       RM."FemaleNormalText" AS "NormalText",
       RM."Unit" as "MasterTestUnit",
       RM."SortOrder" as "SortOrder", 

       CG."Name" AS "Category"
       
FROM test_order AS O 
     LEFT JOIN test_order_content AS C ON O."ObjectID" = C."TestOrder"
     LEFT JOIN test_master AS RM ON RM."ObjectID" = C."TestID"
     LEFT JOIN test_category AS CG ON CG."ObjectID" = RM."Category"

WHERE O."ObjectID" = ' . mx_db_sql_quote($oid);
    $stmt = $stmt . ' ORDER BY RM."SortOrder"';

    // select result items
    $stmt2 = '
select  O."SampleDate",
	O."Patient",
        O."ObjectID" as "TestOrder", 
	RM."ObjectID" as "TestMaster",
	RM."Name" as "ResultName", RM."Independent",
 	RM."SetHeader" as "SetHeader", RM."LaboSystemCode" as "LaboSystemCode",
	RM."Parent" as "Parent",
	RM."FemaleNormalBottom" AS "NormalBottom",
	RM."FemaleNormalTop" AS "NormalTop",
        RM."FemaleNormalText" AS "NormalText",
        RM."Unit" as "MasterTestUnit", G."Name" AS "Category", R."value" as "TestValue", R."unit" as "TestUnit", R."decision" as "TestDecision", RM."SortOrder" as "SortOrder" , R."TestOrderContent" as "TestOrderContent", R."quantification_limit" as quantification_limit, 	R.normal_text, R.comment

from test_order As O join test_result as R
         ON O."ObjectID" = R."TestOrder" AND R."Superseded" IS NULL
     JOIN test_master AS RM
      ON RM."ObjectID" = R."TestMaster"
     LEFT JOIN test_category AS G
      ON G."ObjectID" = RM."Category"
WHERE O."ObjectID" = ' . mx_db_sql_quote($oid);
    $stmt2 = $stmt2 . ' ORDER BY RM."SortOrder"';

  } else {
    if($_REQUEST['NewLikeThis']) {
      // select for SOE
      $stmt = 'SELECT C."TestID" as "Test", O."SampleDate", O."Patient", O.printer,
                  M."Name" AS "TestName", M."ObjectID" as "TestID",

                  M."FemaleNormalBottom" AS "NormalBottom",
                  M."FemaleNormalTop" AS "NormalTop",
                  M."FemaleNormalText" AS "NormalText",

                  M."Unit" as "MasterTestUnit",

                  G."Name" AS "Category", C."ObjectID" as "TestOrderContent"
           FROM "test_order" AS O JOIN "test_order_content" AS C
                   ON O."ObjectID" = C."TestOrder",
                "test_master" AS M, "test_category" AS G
           WHERE O."ObjectID" = C."TestOrder" AND
                 M."ObjectID" = C."TestID" AND
                 G."ObjectID" = M."DispCategory" AND
                 M."当院採用" = \'Y\' AND
                 M."Superseded" IS NULL AND
                 G."Superseded" IS NULL AND
                 M."Parent" IS NULL AND
                 O."ObjectID" = ' . mx_db_sql_quote($oid);
      $stmt = $stmt . ' ORDER BY M."SortOrder"';
    }else{
      // select for SOE
      $stmt = 'SELECT C."TestID" as "Test", O."SampleDate", O."Patient", O.printer,
                  M."Name" AS "TestName", M."ObjectID" as "TestID",

                  M."FemaleNormalBottom" AS "NormalBottom",
                  M."FemaleNormalTop" AS "NormalTop",
                  M."FemaleNormalText" AS "NormalText",

                  M."Unit" as "MasterTestUnit",

                  G."Name" AS "Category", C."ObjectID" as "TestOrderContent",
                  R."value" as "TestValue", R."unit" as "TestUnit",
                  R."quantification_limit" as quantification_limit, R.state as state,
                  R."decision" as "TestDecision", R.normal_text
           FROM "test_order" AS O JOIN "test_order_content" AS C
                   ON O."ObjectID" = C."TestOrder"
                 LEFT JOIN "test_result" AS R
                   ON C."ObjectID" = R."TestOrderContent",
                "test_master" AS M, "test_category" AS G
           WHERE O."ObjectID" = C."TestOrder" AND
                 M."ObjectID" = C."TestID" AND
                 G."ObjectID" = M."DispCategory" AND
                 M."当院採用" = \'Y\' AND
                 M."Superseded" IS NULL AND
                 G."Superseded" IS NULL AND
                 M."Parent" IS NULL AND
                 O."ObjectID" = ' . mx_db_sql_quote($oid);
      $stmt = $stmt . ' ORDER BY M."SortOrder"';
    }
  }

  // picking normal range hack for male/female
  if($pt_oid) {
    $ptinfo = mx_draw_patientinfo_get_data($pt_oid);
    if($ptinfo['性別'] == '女') {
      ;//default
    }
    elseif($ptinfo['性別'] == '男') {
      $stmt = str_replace('Female', 'Male', $stmt);
      if($stmt2)
	$stmt2 = str_replace('Female', 'Male', $stmt2);
    }
    else
      print "患者の性別(".$ptinfo['性別'].")が男または女でないため、女性の正常値をデフォルトで使います";
  }

  // mix order and result 
  if ($it) $it->dbglog("Fetch-Sub: $stmt;\n");
  $data['Test'] = array();
  $d = pg_fetch_all(pg_query($db, $stmt));
  $d2 = null;
  if($stmt2)
    $d2 = pg_fetch_all(pg_query($db, $stmt2));
  if (is_array($d2) and count($d2) > 0) {
    // we have test results
    // loop through result items
    foreach ($d2 as $result_row) {
      // find matching order if exists
      $found = False;
      foreach($d as $order_row) {
	if($order_row['TestOrderContent'] == $result_row['TestOrderContent']) {
	  // found the order item. Append the order first, then result later
	  $result_row['TestName'] = $order_row['TestName'];
	  $found = True;
	  break;
	}
      }
      if($found) {
	if(is_null($order_row["Parent"]) or $order['TestOrder'] == $order['Parent']) {
	  // order = item or order is in results
	  $data['Test'][] = array_map('mx_trim', $result_row);
	}else{
	  $data['Test'][] = array_map('mx_trim', $order_row);
	}
	// next result for the same order item
	continue;
      }

      // no order item found. most likely independent result row
      $data['Test'][] = array_map('mx_trim', $result_row);
    }
  }else{
    // no result yet. just append orders
    foreach($d as $order_row) {
      $data['Test'][] = array_map('mx_trim', $order_row);
    }
  }
  return $data;
}

class list_of_test_orders extends list_of_ppa_objects {
  var $debug=1;
  var $default_row_per_page = 4;
  var $patient_column_name_quoted = '"Patient"';

  function dx_trim($desc, $value, $row) {

     if (array_key_exists('Option', $desc) &&
        is_array($desc['Option']) &&
        array_key_exists('Length', $desc['Option']))
        $len = $desc['Option']['Length'];
    else
      $len = 20;
    if(mb_substr($value, 0, $len) != $value)
	$value = mb_substr($value, 0, $len) . '...';	
    $this->_dx_textish($value);
  }

  function row_paging_aliases() {
    $pk_map = array("ObjectID" => 'O."ObjectID"');
    if (is_null($this->_pk_aliases)) {
      $a = array();
      foreach ($this->row_paging_keys() as $pk) {
	if(!is_null($pk_map[$pk]))
	  $a[] = $pk_map[$pk];
	else
	  $a[] = mx_db_sql_quote_name($pk);
      }
      $this->_pk_aliases =& $a;
    }
    return $this->_pk_aliases;
  }
  
  function list_of_test_orders($prefix, $cfg=NULL) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg))
      $cfg = $_lib_u_test_order_cfg;
    if(array_key_exists('ShowLoo', $_REQUEST))
      unset($_REQUEST['sod-id']);
    list_of_ppa_objects::list_of_ppa_objects($prefix, $cfg);
  }
  
  function base_fetch_stmt_0() {
    return (list_of_ppa_objects::base_fetch_stmt_0() .
	    ' AND test_app_type=0');
  }

  function row_paging_orders() {
    $paging_keys = $this->row_paging_keys();
    $paging_orders = array();
    foreach ($paging_keys as $col) {
	if($col == 'SampleDate')
	      $paging_orders[] = 1;

	else if($col == 'ObjectID')
	      $paging_orders[] = 1;
	else
	      $paging_orders[] = 0;
    }
    return $paging_orders;
  }

  function row_decoration(&$row, $ix, $total) {
    if(!is_null($row['Cancelled']))
      $row_class = 'r';
    else
      $row_class = ($ix % 2) ? 'o' : 'e';
    return "class=\"$row_class\"";
  }

}

class test_order_display extends simple_object_display {
  var $debug = 1;

  function test_order_display($prefix, $cfg=NULL) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_test_order_cfg;
    simple_object_display::simple_object_display
      ($prefix, $cfg);
  }

  function fetch_data($id) {
    return  _lib_u_test_order_fetch_data(&$this, $id,
					 $this->so_config['Patient_ObjectID'],
					 True);
  }

  function annotate_row_data(&$data) {
    
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
  }

  function draw_body($data, $hdata) {
    simple_object_display::draw_body($data, $hdata);
  }
  
  function dx_mx_authenticate($desc, $data, $changed) {
    if(!is_null($data)) {
      $u = get_mx_authenticate_user($data);
      $this->_dx_textish($u['氏名'], $changed, 'noquote');
    }
  }

  function dx_tableview($desc, $data, $changed) {
    global $decision_str;
    $value = '<table class="listofstuff">
              <tr><th>カテゴリ</th><th>検査項目</th><th>値</th><th>判定結果</th><th>正常値</th></th></tr>

';
    
    $prev_category = '';
    $prev_item = '';
    $rownum = 0;
    $row_class = 'e';
    foreach ($data as $row) {
      
      // show category inline header
      if ($prev_category != $row['Category']) {
	$rownum++;
	$row_class = $rownum % 2 ? "e" : "o";
	$value = $value . '<tr class="'.$row_class.'"><td width="20%">'.$row['Category']."</td>";
	$prev_category = $row['Category'];
      }
      else
	$value = $value .'<tr class="'.$row_class.'"><td width="20%"></td>';

      //$value = $value . sprintf('<td>%s</td>', $row['LaboSystemCode']);
      // show test item header
      #print "TESTNAME=" . $row['TestName'];
      if ($row['TestName'] and $prev_item != $row['TestName'] and $row['Independent'] != 1) {
	// add item name
	$value = $value . '<td width="30%">' .htmlspecialchars($row['TestName']) . '</td>';
	$prev_item = $row['TestName'];
      }else
	$value = $value . '<td width="30%" style="text-align:right">' .htmlspecialchars($row['ResultName']).'</td>';
      
      // show value
      if(is_null($row['TestOrder']) or $row['TestOrder'] == $row['Parent']) {
	$value .= '<td colspan=3> </td>';
      }else
	// add  value & unit
	$v = $row['TestValue'].$row['TestUnit'];
	if (is_null($v))
	    $v = '(値なし)';
	$value .= '<td width="20%" style="text-align:right">';
	$ql = $row['quantification_limit'];
	$value .= htmlspecialchars($v) .htmlspecialchars($ql).  '</td>';
	  
	$d = $row['TestDecision'];
	list($s, $r) = normal_value($row['NormalBottom'], $row['NormalTop'],
				    $row['NormalText'], $row['TestValue']);
	
	// add decision
	/*
	 if (is_null($d) or $d=='') {
	   $c = '';
	   $d = $decision_str[$r]['Label'];
	   if(!is_null($decision_str[$r]['Color']))
	   $c = 'background-color: ' . $decision_str[$r]['Color'];
	   $value .= '<td width="10%" style="text-align:center;'. $c .'">';
	   $value .= htmlspecialchars($d). '</td>';
	   
	   $value .= '<td></td>';
	   }else {
	*/
	$bg = '';
	if($d == 'H' or $d == 'L')
	  $bg = 'lightpink';
	$value .= "<td width=\"10%\" style=\"text-align:center; background-color: $bg\">";
	$value .= htmlspecialchars($d). '</td>';
	  
    	// show comment
	//$value .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
	// show normal range
	$value .= '<td width="30%" style="text-align:right">';
	//$value .= htmlspecialchars($s). '</td>';
	$value .= htmlspecialchars($row['normal_text']). '</td>';
	$value .= '</tr>';
    }
    $value .= '</table>';
    $this->_dx_textish($value, $changed, 'noquote');
  }

  function print_sod($template='srl') {
    $db = mx_db_connect();

    $oid = $this->id;
    $stmt = 'SELECT test_app_type, "Arriving" from "test_order" WHERE "ObjectID"=' . $oid;
    $rs = mx_db_fetch_single($db, $stmt);
    if(is_null($rs))
      return;

    $status = 0;
    $test_app_type = $rs['test_app_type'];
    if($rs['Arriving'] >= 20)
	$status = 1;
    $s =  <<< HTML
    <SCRIPT LANGUAGE="JavaScript">
       window.open("print2.php?test_app_type=${test_app_type}&status=${status}&oid={$oid}", "","width=640,height=640");
    </SCRIPT>
HTML;
    print $s;
  }

  function draw_body_2($data, $hdata, $dcols) {
    $id = $this->id;
    $pt_id = $this->so_config['Patient_ID'];
    if(is_null($data["Cancelled"]) and $data['SampleDate'] >= date("Y-m-d"))
      print "<button type=\"submit\" name=\"cancel_order\" value=\"${id}\">この依頼を中止する</button>";
  }
}



class test_order_edit extends simple_object_edit {
  var $debug = 1;

  function anew_tweak($orig_id) {
    global $_mx_employee_labosystemcode;
    global $mx_authenticate_current_user;

    $this->data['OrderDate'] = mx_today_string();
    $this->data['SampleDate'] = $_REQUEST['SampleDate'] ? $_REQUEST['SampleDate'] : mx_today_string();
    $this->data['Cancelled'] = NULL;
    $this->data['clip_num'] = NULL;
    $this->data['test_app_type'] = 0;

    $ptinfo = mx_draw_patientinfo_get_data($this->so_config['Patient_ObjectID']);
    $this->data['printer'] = is_null($this->data['printer']) ? ($ptinfo['入外区分'] == '外来' ? 'SYOCHI' : 'KENSA') : $this->data['printer'];
    $this->data['PatientGroup'] = $ptinfo['希望病棟'];

    $col = $_mx_employee_labosystemcode ? $_mx_employee_labosystemcode : "ObjectID";
    $stmt = <<<SQL
	SELECT "$col" FROM "職員台帳"
	WHERE "Superseded" IS NULL AND userid=
SQL;
    $stmt .= $mx_authenticate_current_user;
    $db = mx_db_connect();
    $e =  mx_db_fetch_single($db, $stmt);
    if (is_null($this->data['DrCode']))
      $this->data['DrCode'] = $e[$col];
  }

  function test_order_edit($prefix, $cfg=NULL) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_test_order_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $cfg);
  }

  function fetch_data($id) {
    return  _lib_u_test_order_fetch_data(&$this, $id,
					 $this->so_config['Patient_ObjectID']);
  }

  function duplicate_tweak($attr) {
    if (array_key_exists('DuplicateDate', $attr)) {
      $this->data['SampleDate'] = $attr['DuplicateDate'];
    }
    $this->so_config['Patient_ObjectID'] = $this->data['Patient'];
  }

  function data_compare($curr, $data) {
    foreach (array('Name', 'SortOrder') as $col)
      if ($curr[$col] != $data[$col]) return 1;
    if (count($curr['Test']) != count($data['Test'])) return 1;
    $cpid = array();
    $dpid = array();
    foreach ($curr['Test'] as $r) $cpid[] = $r['Test'];
    foreach ($data['Test'] as $r) $dpid[] = $r['Test'];
    return $cpid != $dpid;
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_testmaster_pick_cfg;
    global $_mx_hack_takamiya;

    if(!array_key_exists('printer', $data))
       $data['printer'] = '';

    $data['Patient'] = $this->so_config['Patient_ObjectID'];
    
    $data['Test'] = array();
    if (array_key_exists($this->prefix . 'tp', $_REQUEST) &&
	is_array($_REQUEST[$this->prefix . 'tp'])) {
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
      $preset_data = $data['Test'];
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

    if(array_key_exists('set-id-select', $_REQUEST)) {
      $preset_data = $data['Test'];
      $a  = mx_form_unescape_key($_REQUEST['set-id-select']);
      $set_param = $a[0];
      $_REQUEST['soe-Subpick'] = 1;
      $this->data['title'] .= " " . $a[1];
      $this->data['title'] = trim($this->data['title']);
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
                     M."当院採用" = \'Y\' AND
                     S."SetID" = ' . 
	  mx_db_sql_quote($set_param);
	foreach(pg_fetch_all(pg_query($db, $stmt)) as $row) {
	  $preset_data[] = $row;
	}
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

      $cfg['TEST_APP_TYPE'] = 0;
      $this->Subpicker = new testmaster_pick($this->prefix . 'tsp-', $cfg);
      $popup = 1;
      if ($_mx_hack_takamiya)
	$popup = 0;
      $this->Subpick = array('Column' => '検査項目リスト',
			     'Subpick' => array(),
			     'Option' => array('popup' => $popup));
    }
    if ($preset_data)
      unset($_REQUEST['soe-tsp-HSelect-empty']);
    simple_object_edit::annotate_form_data(&$data);
    $data['test_app_type'] = 0;
  }

  function check_executed($id) {
    if(is_null($id) or $id == '')
      return NULL;
    $stmt = 'SELECT "Arriving" from "test_order" WHERE "Arriving" IS NOT NULL AND "ObjectID"=' . $id;
    $db = mx_db_connect();
    $rs = pg_fetch_all(pg_query($db, $stmt));
    return $rs;
  }

  function dx_tableview($desc, $name, $data) {
    global $decision_str;
    $value = '<table>';
    $prev_category = '';
    if($data) {
      foreach ($data as $row) {
	$value = $value . '<tr><td nowrap="nowrap">' . 
	         htmlspecialchars($row['TestName']) . '</td></tr>';
	mx_formi_hidden($this->prefix . 'tp[]',
			mx_form_escape_key
			(array($row['TestID'], $row['TestName'])));
      }
      $value = $value . '</table>';
    }else{
	    $value = mx_empty_field_mark();
    }
    if ($this->Subpick or $this->check_executed($this->id))
      print $value;
    else
      mx_formi_submit($this->prefix . 'Subpick', 0,
		      "<span class=\"link\">$value</span>");
  }

  function accept_subpick($subpick, $chosen) {
    global $_mx_hack_takamiya;

    $this->log('AS0' . mx_var_dump($this));
    $this->log('AS1' . mx_var_dump($chosen));
    if (is_array($chosen)) {
      $d = array();
      $has_NaK = False;
      $has_Li = False;
      foreach ($chosen as $v) {
	$a = mx_form_unescape_key($v);
	// must match with LIST_ID in testmaster-pick config
	$d[] = array('TestID' => $a[0],
		     'TestName' => $a[1]);
	if($a[0] == 29)
	  $has_NaK = True;
	if($a[0] == 33)
	  $has_Li = True;
      }

      # HACK for TAKAMIYA which depends on test_master id 29 for Na&K, 33 for Li
      if($_mx_hack_takamiya and $has_Li and !$has_NaK) {
        $x = array();
	$idx = 0;
        foreach($d as $tmp) {
	   if($tmp['TestID'] < 29) {
	      $idx++;
              continue;
	   }
	   break;
	}
	array_splice($d, $idx, 0, array(array('TestID' => 29, 'TestName' => 'ナトリウム・カリウム')));
      }
      $this->data['Test'] = $d;
    }
    $this->log('AS2' . mx_var_dump($this));
  }

  function _update_subtables(&$db, $id, $stash_id) {
    if (! is_null($stash_id)) {
      $stmt = ('UPDATE "test_order_content" SET "TestOrder" = ' .
	       mx_db_sql_quote($stash_id) .
	       ' WHERE "TestOrder" = ' .
	       mx_db_sql_quote($id));
      $this->dbglog("Stash-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
    foreach ($this->data['Test'] as $r) {
      $stmt = ('INSERT INTO "test_order_content" ("TestOrder", "TestID") VALUES '.
	       '(' . mx_db_sql_quote($id) . ', ' .
	       mx_db_sql_quote($r['TestID']) . ')');
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }

  function draw_body() {
    global $_mx_cheap_layout;

    $d =& $this->data;
    $soc =& $this->so_config;

    if (!$_mx_cheap_layout && $this->draw_control_at_the_top_too()) {
	    print "<br />";
	    $this->draw_body_2($d, $soc, 1);
    }


    // rules
    // do not show while editing order
    // do not show while subpick is enabled
    // do not show if test items is empty
    if(!$this->entering_result && !$this->Subpick &&
       !is_null($this->id) && $this->id != "" &&
       count($this->data['Test']) != 0)
      print '<button style="background: #ffccff" name="Edit" value="entering_result">結果を入力する</button>';


    $this->draw_body_0($d, $soc);
    $this->draw_body_1($d, $soc);
    $this->draw_body_2($d, $soc);
    mx_formi_hidden($this->prefix . 'soe', 'edit');
  }

  function commit($force=NULL) {
    global $_mx_claim_on_order;
    simple_object_edit::commit($force);
    if($_mx_claim_on_order) {
      $db = mx_db_connect();
      $date = $this->data['SampleDate'];
      $match = array();
      if (preg_match('/^(\d{4})-(\d+)-(\d+) /', $date, &$match)) {
	$date = sprintf("%s-%s-%s", $match[1], $match[2], $match[3]);
	mx_kick_claim_if_by_poid($db, $this->so_config['Patient_ObjectID'],
				 $date);
      }
    }
  }
}

////////////////////////////////////////////////////////////////
class test_order_edit2 extends simple_object_edit {
  var $debug = 1;
  function test_order_edit2($prefix, $cfg=NULL) {
    global $_lib_u_test_order_cfg;
    if (is_null($cfg)) $cfg = $_lib_u_test_order_cfg;
    simple_object_edit::simple_object_edit
      ($prefix, $cfg);
  }

  function fetch_data($id) {
    $x = _lib_u_test_order_fetch_data(&$this, $id,
					$this->so_config['Patient_ObjectID'],
					True);
    //print "EDIT2";
    //var_dump($x);
    return $x;
  }

  function dx_tableview($desc, $name, $data) {
    $value = '<table class="listofstuff" width=100%>';
    $prev_category = '';
    $prev_item = '';
    $rownum = 0;
    $row_class = 'e';
    if(is_null($data))
	    $value = mx_empty_field_mark();

    else{
      foreach ($data as $row) {
	if ($prev_category != $row['Category']) {
	  $rownum++;
	  $row_class = $rownum % 2 ? "e" : "o";
	  $value = $value . '<tr class="g"><td colspan=4>'.$row['Category']."</td></tr>";
	  $value = $value . '<tr class="'.$row_class.'">';
	  $prev_category = $row['Category'];
	}
	else
	  $value = $value .'<tr class="'.$row_class.'">';
	
	if ($prev_item != $row['TestName']) {
	  // add item name
	  $value = $value . '<td>' .htmlspecialchars($row['TestName']) . '</td>';
	  $prev_item = $row['TestName'];
	}else
	  $value = $value . '<td style="width: 100px; text-align:right">' .htmlspecialchars($row['ResultName']) . '</td>';
	
	// add result value
	list($s, $r) = normal_value($row['NormalBottom'], $row['NormalTop'],
				    $row['NormalText'], $row['TestValue']);


	$v = $row['TestValue'];
	$u = $row['TestUnit'];
	if(!$u)
	  $u = $row['MasterTestUnit'];

	$d = $row['TestDecision'];
	if(is_null($d) or $d == '')
	  $d= $decision_str[$r]['Label'];

	$value .= '<td>';
	# test value
	$px = $this->prefix .'tv-'. mx_form_escape_key(array($row['TestOrder'],$row['TestOrderContent'], $row['TestMaster'], $row['Category'], $row['TestName'],"TestValue"));
	$value .= '<input type=text style="width: 50%; text-align: right;" name="'. $px .'" value="'.htmlspecialchars($v).'">';

	# test unit
	$px = $this->prefix .'tv-'. mx_form_escape_key(array($row['TestOrder'],$row['TestOrderContent'],$row['TestMaster'],$row['Category'], $row['TestName'],"TestUnit"));
	$value .= '<input type=text style="width: 50%; text-align: right;" name="'. $px .'" value="'.htmlspecialchars($u).'" size=11>';

        # normal value
	$px = $this->prefix .'tv-'. mx_form_escape_key(array($row['TestOrder'],$row['TestOrderContent'],$row['TestMaster'],$row['Category'], $row['TestName'],"Normal"));
	$value .= '</td><td style="text-align: center">'.$s.'</td>';

	# decision
	$value .= "<td style=\"text-align: center\"><input type=hidden name=\"$px\" value=\"$s\">";
	$px = $this->prefix .'tv-'. mx_form_escape_key(array($row['TestOrder'],$row['TestOrderContent'],$row['TestMaster'],$row['Category'], $row['TestName'],"TestDecision"));
	$value .= '<input type=text style="text-align: right;" name="'. $px .'" value="'.htmlspecialchars($d).'" size=4>';

	#
	  $px = $this->prefix .'tv-'. mx_form_escape_key(array($row['TestOrder'],$row['TestOrderContent'],$row['TestMaster'],$row['Category'], $row['TestName'],"TestOrderContent"));
	mx_formi_hidden($px, $row["TestOrderContent"]);
	$value .= "</td></tr>\n";
      }
      $value .= '</table>';
    }
    print $value;
  }

  function annotate_form_data(&$data) {
    global $_lib_u_manage_testmaster_pick_cfg;
    $data['Patient'] = $this->so_config['Patient_ObjectID'];
    
    //find -tv[]
    $t = array();
    foreach($_REQUEST as $k => $v){
      if(strstr($k, 'soe-tv-') == $k){
	list($dummy, $dummy, $k2) = explode('-', $k);
	$a = mx_form_unescape_key($k2);
	$t[$a[1]]['TestOrder'] = $a[0];
	$t[$a[1]]['TestOrderContent'] = $a[1];
	$t[$a[1]]['TestMaster'] = $a[2];
	$t[$a[1]]['Category'] = $a[3];
	$t[$a[1]]['TestName'] = $a[4];
	$t[$a[1]][$a[5]] = $v;
      }
    }
    foreach ($t as $k=>$v)
      $data['Test'][] = $v;
    simple_object_edit::annotate_form_data(&$data);
    $data['test_app_type'] = 0;
  }

  function data_compare($curr, $data) {
    #NEEDSWORK
    return True;
  }

  function _update_subtables(&$db, $id, $stash_id) {
    $this->dbglog("Update-Subs: $stmt\n");
    $stmt = 'UPDATE "test_result" set "Superseded"=now() WHERE "TestOrder"=' . mx_db_sql_quote($id);
    if (! pg_query($db, $stmt))
      return pg_last_error($db);

    foreach ($this->data['Test'] as $r) {
      $stmt = ('INSERT INTO "test_result" ("TestOrder", "TestOrderContent", "TestMaster", "value", "unit", "decision") VALUES (' . 
	       mx_db_sql_quote($id) . ',' .
	       mx_db_sql_quote($r['TestOrderContent']) . ',' .
	       mx_db_sql_quote($r['TestMaster']) . ',' .
	       mx_db_sql_quote($r['TestValue']) . ',' .
	       mx_db_sql_quote($r['TestUnit']) . ',' .
	       mx_db_sql_quote($r['TestDecision']) . ')');
      $this->dbglog("Insert-Subs: $stmt\n");
      if (! pg_query($db, $stmt))
	return pg_last_error($db);
    }
  }

  function try_commit(&$db) {
    global $mx_authenticate_current_user;

    if ($this->_broken_origin_check())
      return 'failure';

    $d_id = $this->id;
    $d =& $this->data;
    $this->change_nature = 'nochange';

    $this->handle_extdocuments($db, &$d);

    if ($d_id) {
      // Updating.  Check if things have changed.

      $curr = $this->fetch_data($d_id);
      $this->annotate_row_data(&$curr);

      $d['CreatedBy'] = $curr['CreatedBy'];
      // $d['ObjectID'] = $d_id;
      if ($this->data_compare($curr, $d)) {
	
	# _update
	if (($st =
	     $this->_update_subtables($db, $this->id, $stash_id)) != '') {
	  $this->err($st);
	  return 'failure';
	}

	// Update the row in place.
	$stmt = $this->_update_stmt($d, $mx_authenticate_current_user, $d_id);

	$this->dbglog("-- Update\n$stmt;\n");
	#if (! pg_query($db, $stmt)) {
	#  $this->log("-- Failure\n$stmt;\n");
	#  $this->err(pg_last_error($db));
	#  return 'failure';
	#}
	$this->log("Updated\n");
	$this->change_nature = 'updated';
      }
      else{
	$this->log("No Change\n");
      }
    }
    else {
      // Creating new.
      $d_id = $this->_allocate_unused_id($db);
      $stmt = $this->_insert_stmt($d, $d_id, NULL);
      $this->dbglog("-- Create\n$stmt;\n");
      if (! pg_query($db, $stmt)) {
	$this->log("-- Failure\n$stmt;\n");
	$this->err(pg_last_error($db));
	return 'failure';
      }
      $this->log("Created\n");
      $this->change_nature = 'created';

	if (($st =
	     $this->_update_subtables($db, $d_id, NULL)) != '') {
	  $this->err($st);
	  return 'failure';
	}
    }

    if (($st = $this->_pre_commit_hook($db)) != '') {
      $this->err($st);
      return 'failure';
    }

    if (! pg_query($db, 'commit')) {
      $this->err(pg_last_error($db));
      return 'failure';
    }

    // Set the id on this object (in case this was new).
    // Note that this must be postponed *after* commit.
    $this->id = $d_id;

    return 'ok';
  }

  function draw() {
    // this flag keeps _edit2 to be chosen
    if($this->commit_ran != 'created' && $this->commit_ran != 'updated')
      mx_formi_hidden($this->prefix . 'entering_result', 1);
    simple_object_edit::draw();
    mx_formi_hidden($this->prefix . 'soe', 'edit2');
  }

}


/*
 * This is used by index-pt via lib/ord_module.php.
 */
function test_module_index_info
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
	
	$sql = <<<SQL
SELECT K."ObjectID", cast("OrderDate" as date) as "OrderDate",
       cast("SampleDate" as date) as "SampleDate",
	  urgent, scheduled, title, "DrCode", 
	  E."姓" || E."名" as author, comment
FROM test_order AS K
   LEFT JOIN "職員台帳" AS E
   ON E."userid" = K."CreatedBy" AND E."Superseded" IS NULL

 WHERE K.test_app_type=0 AND "Cancelled" is null and "Patient"=
SQL;
	$sql .= mx_db_sql_quote($p_oid);
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
	# fetch detail rows
	$details = array();
	foreach($all as $e) {
	  $stmt = 'SELECT "Name" FROM test_order_content C JOIN test_master M ON C."TestID"=M."ObjectID" AND M."Superseded" IS NULL WHERE "TestOrder"=' . $e['ObjectID'];
	  $ds = mx_db_fetch_all($dbh, $stmt);
	  $tmp = array();
	  foreach($ds as $d)
	    $tmp[] = $d['Name'];
	  $details[$e['ObjectID']] = implode('; ', $tmp);
	  
	}

	$application = '/u/test/order2.php';

	$drs = _lib_u_test_get_doctors();

	foreach($all as $e) {
	  $oid = $e['ObjectID'];
	  $e['order_items'] = $details[$oid];
	  $e['doctor'] = $drs[$e['DrCode']];
	  $e['CreatedBy'] = 
	  $all2 = _lib_u_test_order_fetch_data(NULL, $oid, $p_oid, True);
	  if ($all2 === false)
	    continue;

	  $primary = '依頼日:'.$e['OrderDate'];

	  $url = sprintf("$application?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $oid);
	  if($e['Arriving']>30)
	    $text = sprintf("(検体検査結果) %s %s %s %s",
			    $primary,
			    $e['urgent'] == 1 ? '通常' : '至急',
			    $e['scheduled'] == 1 ? '臨時' : '定期',
			    $e['title']);
	  else
	    $text = sprintf("(検体検査) %s %s %s",
			    $e['urgent'] == 1 ? '通常' : '至急',
			    $e['scheduled'] == 1 ? '臨時' : '定期',
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

function test_module_draw_row($e) {
  $urgent_enum = array(1 =>'通常',
		       2 =>'至急',
		       );
  $scheduled_enum = array(1 => '臨時',
			  2 => '定期',
			  );

  $id = $e['ObjectID'];
  $order_date = $e['OrderDate'];
  $sample_date = $e['SampleDate'];
  $urgent = $urgent_enum[$e['urgent']];
  $scheduled = $scheduled_enum[$e['scheduled']];
  $title = $e['title'] ? $e['title'] : '&nbsp;';
  $author = $e['author'];
  $doctor = $e['doctor'];
  $comment = $e['comment'];
  $order_items = $e['order_items'];
  $html = <<<HTML
    <TABLE width="100%" class="listofstuff">
    <tr>
    <TD bgcolor="#ff962d">依頼日</TD>
    <TD bgcolor="#ff962d">検査日</TD>
    <TD bgcolor="#ff962d">記入者</TD>
    <TD bgcolor="#ff962d">指示医</TD>
    <TD bgcolor="#ff962d" width="150">検査タイトル</TD>
    <TD bgcolor="#ff962d">定期</TD>
    <TD bgcolor="#ff962d">至急</TD>
    <TD bgcolor="#ff962d">コメント</TD>
    </tr>
    <tr>
    <TD>${order_date}</TD>
    <td>${sample_date}</td>
    <td>${author}</td>
    <td>${doctor}</td>
    <TD width="150">${id}: ${title}</TD>
    <td>${scheduled}</td>
    <td>${urgent}</td>
    <td>${comment}</td>
    </tr>
    <tr>
      <TD colspan="8">${order_items}</TD>
    </tr>
</TABLE>				  
HTML;
  return $html;
}

/*
 * Return an SQL boolean snippet that tells if given patient P has
 * an order within that range of dates (or the default one)
 */
function test_module_index_info_patient_sql($date_from, $date_to, $options=NULL)
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
			WHERE K.test_app_type=0 AND K."Patient" = P."ObjectID"
			AND $limit)
SQL;
}
?>
