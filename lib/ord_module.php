<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/karteview.php';

// include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/xctorder.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/vital-data.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order.php';

// needs MX linkage.
//updated 0615-2011 xct-module
//11-3-2014 added sei ,meal yakuzai old-rx old_shot
//11-07-2014 newer than amazon server for karte
function image_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
  $result = array();
  
  if (!is_null($time_from)) {
	  $tf = 'AND M.order_dt >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M.order_dt <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }
  $sql = "SELECT cast(M.order_dt as date) as order_dt, P.\"����ID\" as pid,
                 M.ObjectID as oid
          FROM tbl_order as M, \"������Ģ\" P
          WHERE M.Superseded is NULL AND
                P.\"����ID\" = M.pt_no AND
                P.\"ObjectID\" = " . mx_db_sql_quote($p_oid) . " $tf $tt " . '
	        ORDER BY M.ObjectID';
  $images = pg_fetch_all(pg_query($dbh,$sql));
  if ($images == false)
    return $result;

  foreach($images as $image) {
    // fuller
    $fuller_sql = "SELECT bui
          FROM tbl_order_detail 
          WHERE order_oid = " . mx_db_sql_quote($image['oid']) . '
	        ORDER BY ObjectID';
    $fullers = pg_fetch_all(pg_query($dbh,$fuller_sql));
    $fuller = '';
    foreach($fullers as $f)
      $fuller .= $f["bui"] . "<br>";
      
    //--------------------

    $uri = sprintf("/u/doctor/image_order.php?SetPatient=1&PatientID=%s&SetSODObject=%s",
		   trim($image['pid']), $image['oid']);
    $text = sprintf("(����) ������ %s", $image["order_dt"]);
    $result[] = array('timestamp' => $image['order_dt'],
		      'text' => $text,
		      'callback_url' => $uri,
		      'fuller' => $fuller,
		      'thumb' => NULL,
		      'object_id' => $image['oid']);
  }
  return ($result);
}

function meal_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
  $result = array();
  
  if (!is_null($time_from)) {
	  $tf = 'AND M."order_date" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."order_date" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }

  $sql = 'SELECT M."order_since", M."dr_order",M."order_date",P."����ID" as pid,
                 M."ObjectID" as oid
          FROM "meal_order" as M, "������Ģ" P
          WHERE M."Superseded" is NULL AND P."ObjectID" = M.patient AND
                M."patient" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
	        ORDER BY M."ObjectID"';
//print $sql;
  $meals = pg_fetch_all(pg_query($dbh, $sql));
  if ($meals === false)
	  return $result;
  foreach ($meals as $meal) {
//11-01-2014
$uri = sprintf("/u/doctor/meal-nutri.php?SetPatient=1&PatientID=%s&SetSODObject=%s",
		    trim($meal['pid']),$meal['oid']);
//    $uri = sprintf("/u/doctor/meal-nutri.php?SetPatient=1&PatientID=%s",
//		    trim($meal['pid']),$meal['oid']);
    $text = sprintf("(�����) �¹Ի� %s ���� %s", $meal["order_since"], $meal["dr_order"]);
    $result[] = array('timestamp' => $meal["order_date"],
		      'text' => $text,
		      'callback_url' => $uri,
		      'thumb' => NULL,
		      'object_id' => $meal['oid']);
  }
//print_r($result);
  return ($result);
}
//11-01-2014 seiorder
function sei_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
  $result = array();
// seir_master
 $db = mx_db_connect();
  $stmt1 = <<<SQL
    select  medis_cd as cd ,  kensa_name as name
    from seiri_master   
SQL;
  $rows =  mx_db_fetch_all($db, $stmt1);
  $ret = array(NULL => '');
  foreach($rows as $row)

	 $ret[$row['cd']] = $row['name'];
//end of sei master
	 
  if (!is_null($time_from)) {
//	  $tf = 'AND M."orderdate" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."orderdate" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }
  $sql = 'SELECT M."memo1", M."bui1",M."plandate",M."orderdate",P."����ID" as pid,
                 M."ObjectID" as oid
          FROM "seiorder" as M, "������Ģ" P
          WHERE M."Superseded" is NULL AND P."ObjectID" = M."����" AND
                M."����" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
	        ORDER BY M."ObjectID"';
//print $sql;
  $meals = pg_fetch_all(pg_query($dbh, $sql));
  if ($meals === false)
	  return $result;
  foreach ($meals as $meal) {
//11-01-2014
//$url = sprintf("/u/doctor/patient-disease.php?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $e['ObjectID']);
     $uri = sprintf("/u/doctor/seiorder-app.php?SetPatient=1&PatientID=%s&SetSODObject=%s",
		    trim($meal['pid']),$meal['oid']);
    $text = sprintf("(��������) �¹Ի� %s ����̾ %s memo %s", $meal["plandate"], $ret[$meal["bui1"]],$meal["memo1"]);
    $result[] = array('timestamp' => $meal["orderdate"],
		      'text' => $text,
		      'callback_url' => $uri,
		      'thumb' => NULL,
		      'object_id' => $meal['oid']);
  }
  return ($result);
}




//
function pharma_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options, $which) {
  global $_mx_use_old_rx_order;
  global $_mx_use_old_shots_order;
//0706-2014
if ($which =='r')
{$_mx_use_old_rx_order=0;}
if ($which =='p') {$_mx_use_old_rx_order=1;}
//11-01-2014
//use yakuzai for shots
if ($which =='i')
{$_mx_use_old_shots_order=0;}
else {$_mx_use_old_shots_order=1;}

  $result = array();
  
  $tf = $tt = '';
  if (mx_check_option('LimitWithRxRange', $options)) {
    if (!is_null($time_from))
	    $tt = 'AND M."����������" + ' .
	    'CAST((CAST(M."����" AS VARCHAR) || \' day\') AS INTERVAL) > ' .
		    mx_db_sql_quote($time_from);
    if (!is_null($time_to))
	    $tf = 'AND M."����������" <= '. mx_db_sql_quote($time_to);
  } else {
    if (!is_null($time_from))
	    $tf = 'AND M."����ǯ����" >= '. mx_db_sql_quote($time_from);
    if (!is_null($time_to))
	    $tt = 'AND M."����ǯ����" <= '. mx_db_sql_quote($time_to);
  }
  $qp_oid = mx_db_sql_quote($p_oid);
  if ($which == 'r'|| $which == 'p') {
  $sql = <<<SQL
         SELECT M."����ǯ����", M."��ʬ", NULL AS "���",
                 M."ObjectID" as oid, M."��ȯ��", M."����������",
	         E."��" as "��������", E."̾" as "������̾",
	         M."��Ͽ��" as "������",
		 (CASE WHEN
		  ((D."��ʬ��" IS NULL) OR D."��ʬ��" = '')
		  THEN D."��ʬ��2"
		  ELSE D."��ʬ��"
		  END) AS "����̾"
          FROM "���޽����" as M
	  LEFT JOIN "������Ģ" as E ON M."��Ͽ��" = E."ObjectID"
	  LEFT JOIN "�������ɽ" as D ON E."����" = D."ObjectID"
          WHERE M."Superseded" is NULL AND
                M."����" = $qp_oid $tf $tt
                ORDER BY M."ObjectID" DESC
SQL;
  } 
else{ 
if ($which == 'i'){
  $sql = <<<SQL
         SELECT M."����ǯ����", M."��ʬ", 1 AS "���",
                 M."ObjectID" as oid, M."��ȯ��", M."����������",
	         E."��" as "��������", E."̾" as "������̾",
	         M."��Ͽ��" as "������",
		 (CASE WHEN
		  ((D."��ʬ��" IS NULL) OR D."��ʬ��" = '')
		  THEN D."��ʬ��2"
		  ELSE D."��ʬ��"
		  END) AS "����̾"
          FROM "yakuzai" as M
	  LEFT JOIN "������Ģ" as E ON M."��Ͽ��" = E."ObjectID"
	  LEFT JOIN "�������ɽ" as D ON E."����" = D."ObjectID"
          WHERE M."Superseded" is NULL AND
                M."����" = $qp_oid $tf $tt
                ORDER BY M."ObjectID" DESC
SQL;
  }
else {

$sql = <<<SQL
         SELECT M."����ǯ����", M."��ʬ", 2 AS "���",
                 M."ObjectID" as oid, M."��ȯ��", M."����������",
	         E."��" as "��������", E."̾" as "������̾",
	         M."��Ͽ��" as "������",
		 (CASE WHEN
		  ((D."��ʬ��" IS NULL) OR D."��ʬ��" = '')
		  THEN D."��ʬ��2"
		  ELSE D."��ʬ��"
		  END) AS "����̾"
          FROM "��ͽ����" as M
	  LEFT JOIN "������Ģ" as E ON M."��Ͽ��" = E."ObjectID"
	  LEFT JOIN "�������ɽ" as D ON E."����" = D."ObjectID"
          WHERE M."Superseded" is NULL AND
                M."����" = $qp_oid $tf $tt
                ORDER BY M."ObjectID" DESC
SQL;


}
}
  if (mx_check_option('limit', $options))
    $sql .= "  LIMIT " . $options['limit'];
  $rxs = pg_fetch_all(pg_query($dbh,$sql));
  if ($rxs === false)
    return $result;

  foreach ($rxs as $rx) {
    $vb_array=array();

// 0706-2014
$rx_app = $_mx_use_old_rx_order ? 'rx_order.php' : 'rx_order3.php';
//$rx_app = 'rx_order.php';

   $shots_app = $_mx_use_old_shots_order ? 'shots_order.php' : 'shots_order3.php';

//11-01-2014
    if ($rx["���"]) {
      $uri = "/u/pharmacy/${shots_app}";
      if ($_mx_use_old_shots_order)
	$uri = sprintf("%s?pid=%s&detail=%s&oid=%s",
		       $uri,$p_oid,$rx['oid'],$rx['oid']);
      else
	$uri = sprintf("%s?SetPatient=1&PatientID=%s&SetSODObject=%s",
		       $uri,$p_pid,$rx['oid']);
    }
    else {
      $uri = "/u/pharmacy/${rx_app}";
      if ($_mx_use_old_rx_order)
	$uri = sprintf("%s?pid=%s&detail=%s&oid=%s",
		       $uri,$p_oid,$rx['oid'],$rx['oid']);
      else {
	$uri = sprintf("%s?SetPatient=1&PatientID=%s&SetSODObject=%s",
		       $uri,$p_pid,$rx['oid']);
      }
    }

    $text = sprintf("(%s�) %s",
		    ($rx['���'] ? "���" : "����"),
		    $rx["��ʬ"]);
    
    // read content for each Rx
if ($rx['���']==2) {$meds=get_meds($rx['oid'],1);}
if ($rx['���']==1) {$meds=get_meds_shot($rx['oid'],1);}
if ($rx['���']==0) {$meds=get_meds($rx['oid'],0);}

//   $meds = get_meds($rx['oid'], $rx['���'] ? 1:0);
//print_r($meds);
    if (mx_check_option('limit', $options) == 1)
      $buf = set_body($meds, False, $rx['��ȯ��']);
    else {
      $buf = set_body($meds, True, $rx['��ȯ��']);
      array_unshift($buf, $rx["����ǯ����"]);
    }
    $fuller=NULL;
    if(is_array($buf)) {
      $fuller = implode("<br>", $buf);
      $vb_array[] = mx_form_escape_key(array('RX', implode("\n", $buf)));
      $tmp = array();
      if (is_array($meds))
	foreach($meds as $m)
	  $tmp[] = $m['�쥻�ץ��Ż����������ƥ������̾'].$m['����'].$m['����ñ��'];
      $vb_array[] = mx_form_escape_key(array('MED', implode("��", $tmp)));
    }
    $it = array('timestamp' => $rx["����ǯ����"],
		'text' => $text,
		'callback_url' => $uri,
		'thumb' => NULL,
		'value_blob' => implode('&', $vb_array),
		'fuller' => $fuller,
		'object_id' => $rx['oid'],
		'execdate' => $rx['����������'],
		'������' => $rx['������'],
		'������̾' => $rx['������̾'],
		'��������' => $rx['��������'],
		'����̾' => $rx['����̾'],
	    );
    if ($options['ReturnOrderPieces']) {
	    $them = array();
	    $lacking = array();
	    foreach ($meds as $m) {
		    if ($options['CheckLabelPrint'] &&
			$m['�±����ѥ�٥��װ���'] != 'Y')
			    continue;
		    if (trim($m['����']) == '') {
			    $lacking[] = $m;
			    continue;
		    }
		    if (count($lacking)) {
			    foreach ($lacking as $l) {
				    foreach (array('�굻',
						   '����',
						   '����������',
						   '��ˡ') as $col)
					    if (array_key_exists($col, $m))
						    $l[$col] = $m[$col];
				    $them[] = $l;
			    }
			    $lacking = array();
		    }
		    $them[] = $m;
	    }

	    if (mx_check_option('LimitWithRxRange', $options)) {
		    $hold = $them;
		    $them = array();
		    $ed = mx_timestamp_parse($rx['����������']);
		    $since = mx_timestamp_parse($time_from);
		    $until = mx_timestamp_parse($time_to);
		    $ed = $ed['timestamp'];
		    $since = $since['timestamp'];
		    $until = $until['timestamp'];
		    foreach ($hold as $m) {
			    $endoffset = $m['����'] * 86400;
			    if ($since < $ed + $endoffset && $ed <= $until)
				    $them[] = $m;
		    }
	    }
	    $it['OrderPieces'] = $them;
	    if (!count($them))
		    continue; /* Do not let this get returned */
    }
    $result[] = $it;
  }
  return ($result);
}

function rx_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	return pharma_module_index_info(&$dbh, $p_oid, $p_pid,
					$time_from, $time_to, $options, 'r');
}
function old_rx_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	return pharma_module_index_info(&$dbh, $p_oid, $p_pid,
					$time_from, $time_to, $options, 'p');
}

function injection_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	return pharma_module_index_info(&$dbh, $p_oid, $p_pid,
					$time_from, $time_to, $options, 'i');
}
//11-01-2014
function old_injection_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	return pharma_module_index_info(&$dbh, $p_oid, $p_pid,
					$time_from, $time_to, $options, 'o');
}
//
function rehab_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
  $result = array();
  
  if (!is_null($time_from)) {
	  $tf = 'AND M."��Ͽ��" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."��Ͽ��" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }

  $sql = 'SELECT M."��Ͽ��", M."������ˡ", M."�����ˡ", M."����İ����ˡ",
                 M."������ʬ", M."ObjectID" as oid
          FROM "��Ͻ����" as M
          WHERE M."Superseded" is NULL AND
                M."����" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
                ORDER BY M."ObjectID"';

  $rehabs = pg_fetch_all(pg_query($dbh,$sql));
  if ($rehabs === false)
	  return $result;
  foreach ($rehabs as $rehab) {
    $uri = sprintf("/u/rehabdr/rehab-order.php?pid=%s&detail=%s&oid=%s",
		   $p_oid,$rehab['oid'],$rehab['oid']);
    $text = sprintf("(����) ������ʬ %s %s %s %s",
		    $rehab["������ʬ"],
		    ($rehab['������ˡ'] ? "������ˡ" : ""),
		    ($rehab['�����ˡ'] ? "�����ˡ" : ""),
		    ($rehab['����İ����ˡ'] ? "����İ����ˡ" : ""));
    $result[] = array('timestamp' => $rehab["��Ͽ��"],
		      'text' => $text,
		      'callback_url' => $uri,
		      'thumb' => NULL,
		      'object_id' => $rehab['oid']
		      );
  }
  return ($result);
}


function xct_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
//limit dsplay data 11-01-2014
 $limit = array();
	if (!is_null($time_from))
		$limit[] = ' M."orderdate" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = ' M."orderdate" <= '. mx_db_sql_quote($time_to);

$bui=array();
$bui=array('170011810'=>'CT',
'170001910'=>'X');
   $stmt = <<<SQL
    select E."bui_code" as id ,  E.bui_name as name
    from bui_master4 E 
SQL;
pg_set_client_encoding('EUC_JP');



	$rs = pg_query($dbh, $stmt);
  $rows = pg_fetch_all($rs);
  
  foreach($rows as $row)
    $bui[$row['id']] = $row['name'];



//$bui=array('002000006'=>'AAA',
//'002000005'=>'BBB');

 
  $result = array();
	$poid = mx_db_sql_quote($p_oid);
	$sql = <<<SQL
		 SELECT M."plandate", M.xctkubun, M."bui1", M."bui2", M."bui3",M."bui4",
                 M."bui5", M."ObjectID" as oid,M."orderdate"
          FROM "xctorder" as M
          WHERE M."Superseded" is NULL AND M."����" = $poid
SQL;

//11-01-2014 limit retrieve data              
if (count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}
  $xcts = pg_fetch_all(pg_query($dbh,$sql));
//print $sql;
  if ($xcts === false)
	  return $result;
  foreach ($xcts as $xct) {

	$uri = sprintf("/u/doctor/xctorder2-app.php?SetPatient=1&PatientID=%s&oid=%s",

   $p_pid,$xct['oid']);


//0615-2011
$xct['xctkubun']=$bui[$xct['xctkubun']];
 $xct['bui1']=$bui[$xct['bui1']];
$xct['bui2']=$bui[$xct['bui2']];
$xct['bui3']=$bui[$xct['bui3']];
$xct['bui4']=$bui[$xct['bui4']];
$xct['bui5']=$bui[$xct['bui5']];
    $text = sprintf("(xct) ��ʬ��%s ���� %s %s %s %s",
			$xct['xctkubun'],
		    $xct['bui1'],
		    ($xct['bui2'] ? $xct['bui2'] : ""),
		    ($xct['bui3'] ? $xct['bui3'] : ""),
		    ($xct['bui4'] ? $xct['bui4'] : ""));
    $result[] = array('timestamp' => $xct["orderdate"],
		      'text' => $text,
		      'callback_url' => $uri,
		      'thumb' => NULL,
		      'object_id' => $xct['oid']
		      );
  }
//print_r($result);
  return ($result);
}


// should return dict of timestamp, text, callback_url, and thumb.
//11=01-2014  good sample
function disease_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	$result = array();

	$limit = array();
	if (!is_null($time_from))
		$limit[] = 'PDV."������" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'PDV."������" <= '. mx_db_sql_quote($time_to);
	$sql = '
SELECT PDV."ObjectID", PDV."������", PDV."����̾",
PDV."��Ƭ��̾",
PDV."��Ƭ��̾2",
PDV."��Ƭ��̾3",
PDV."��Ƭ��̾4",
PDV."������̾1",
PDV."������̾2",
PDV."����",
PDV."����̾"
FROM "������̾" AS PDV
WHERE PDV."Superseded" IS NULL AND
PDV."����" = ' . mx_db_sql_quote($p_oid);

	if (count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}		
	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;
	foreach ($all as $e) {
		$vb_array= NULL;
		$url = sprintf("/u/doctor/patient-disease.php?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $e['ObjectID']);
		$text = sprintf("(��̾) %s%s%s%s%s%s%s",
				$e['��Ƭ��̾'],
				$e['��Ƭ��̾2'],
				$e['��Ƭ��̾3'],
				$e['��Ƭ��̾4'],
				$e['����̾'],
				$e['������̾1'],
				$e['������̾2']
				);
		if ($e['����̾'] == 'Y') {
			$text .= " [����̾��]";
		}
		if ($e['����'] == 'Y') {
			$text .= " [������]";
		}
		$vb_array[] = mx_form_escape_key(array('��̾', $text));
		$result[] = array('timestamp' => $e['������'],
				  'text' => $text,
				  'callback_url' => $url,
				  'thumb' => NULL,
				  'value_blob' => implode('&', $vb_array),
				  'object_id' => $e['ObjectID'],
				  );
	}
	return $result;
}

// should return dict of timestamp, text, callback_url=NULL, and thumb=NULL.

function appt_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL)
{
	$result = array();
	$poid = mx_db_sql_quote($p_oid);
	$sql = <<<SQL
		SELECT "ͽ�����", "ͽ��λ����", "ͽ����", "��Ū"
		FROM APPT_LIST_YET_TO_SHOW
		WHERE patient_id = $poid
SQL;

	if (!is_null($time_from))
		$sql .= 'AND "ͽ�����" >= ' . mx_db_sql_quote($time_from);
/*
	if (!is_null($time_to))
		$sql .= 'AND "ͽ��λ����" <= ' . mx_db_sql_quote($time_to);
*/
//11-01-2014
if (!is_null($time_to))
		$sql .= 'AND "ͽ��λ����" is not null ' ;
//print $sql;
	$all = pg_fetch_all(pg_query($dbh, $sql));

	if ($all === false)
		return $result;
	foreach ($all as $e) {
		$t = substr($e['ͽ�����'], 0, 10);
		$ob = '';
		if (trim($e['��Ū']) != '') {
			$ob = " (" . trim($e['��Ū']) . ")";
		}
		$text = sprintf("(ͽ��) %s, %s%s",
				substr($e['ͽ�����'], 11, 5),
				$e['ͽ����'],
				$ob);
		$result[] = array('timestamp' => $t,
				  'text' => $text);
	}
//print_r($result);
//print "AAAAA";
	return $result;
}

//12-19-2012
 
function kenshin_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
  $result = array();
  
  if (!is_null($time_from)) {
	  $tf = 'AND M."order_date" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."order_date" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }
  $sql = 'SELECT M."order_date", M."special_req",M."notes",
                 M."ObjectID" as oid
          FROM "otatest_order" as M
          WHERE M."Superseded" is NULL AND
                M."patient" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
	        ORDER BY M."ObjectID"';
  $kenshin = pg_fetch_all(pg_query($dbh, $sql));
  if ($kenshin === false)
	  return $result;
  foreach ($kenshin as $ken) {
    $uri = sprintf("/u/doctor/otatest-app.php?SetPatient=1&PatientID=%s",
		   $p_pid);
    $text = sprintf("(���) �긫 %s ������ %s", $ken["special_req"], $ken["notes"]);
    $result[] = array('timestamp' => $ken["order_date"],
		      'text' => $text,
		      'callback_url' => $uri,
		      'thumb' => NULL,
		      'object_id' => $ken['oid']);
  }
  return ($result);
}
 

//12-19-2012
function kartenew_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	$result = array();
	$num_limit = 0;
	if (!is_null($time_from))
		$limit[] = 'K."����" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'K."����" <= '. mx_db_sql_quote($time_to);
	if (!count($limit))
		$num_limit = 1;

	$limit[] = 'K."Superseded" IS NULL';

	
	$sql='SELECT "ID", "ObjectID", "Superseded", "CreatedBy", "����", "����", "S0", 
       "S1", "S2", "S3", "S4", "O0", 
       "A", "P", "D", "I1", "I2", "I3", "I4", "I5",   
       "T", recorded  ';
	
	$sql = $sql. '
FROM "����ƥǥ�ɽ" AS K
WHERE K."����" = ' . mx_db_sql_quote($p_oid);
	if (count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}
	if ($num_limit) {
		$sql .= ' LIMIT 30';
	}

	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;
	$me = 'doctor'; # Perhaps needs work.
	$application = "/u/$me/karte-app.php";

	foreach ($all as $e) {
		$url = sprintf("$application?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $e['ObjectID']);
		$text = sprintf("(�����) %s", $e["O0"]);

		$fuller ="Order-list:".$e["P"];
		$vb_array = array();
/*
		foreach ($namemap as $c => $t) {
			if (trim($e[$c]) == '')
				continue;
			$fuller .= htmlspecialchars("$t: $e[$c]") . "<br />";
			$vb_array[] = mx_form_escape_key(array($t,$e[$c]));
		}
*/

		$result[] = array('timestamp' => $e['����'],
				  'text' => $text,
				  'fuller' => $fuller,
				  'callback_url' => $url,
				  'thumb' => NULL,
				  'value_blob' => implode('&', $vb_array),
				  'object_id' => $e['ObjectID'],
				  );
	}
	return $result;
}

?>
