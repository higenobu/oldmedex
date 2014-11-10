<?php // -*- coding: euc-jp -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/enum.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/karteview.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/quickxray.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/vital-data.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/print.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/order.php';

// needs MX linkage.
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
	  $tf = 'AND M."��Ͽ��" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."��Ͽ��" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }
  $sql = 'SELECT M."�¹Ի�", M."����",M."��Ͽ��",
                 M."ObjectID" as oid
          FROM "�����" as M
          WHERE M."Superseded" is NULL AND
                M."����" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
	        ORDER BY M."ObjectID"';
  $meals = pg_fetch_all(pg_query($dbh, $sql));
  if ($meals === false)
	  return $result;
  foreach ($meals as $meal) {
    $uri = sprintf("/u/nutrition/order.php?pid=%s&detail=%s&oid=%s&move=yes",
		   $p_oid,$meal['oid'],$meal['oid']);
    $text = sprintf("(�����) �¹Ի� %s ���� %s", $meal["�¹Ի�"], $meal["����"]);
    $result[] = array('timestamp' => $meal["��Ͽ��"],
		      'text' => $text,
		      'callback_url' => $uri,
		      'thumb' => NULL,
		      'object_id' => $meal['oid']);
  }
  return ($result);
}

function pharma_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options, $which) {
  global $_mx_use_old_rx_order;
  global $_mx_use_old_shots_order;

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
  if ($which == 'r') {
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
  } else {
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
          FROM "��ͽ����" as M
	  LEFT JOIN "������Ģ" as E ON M."��Ͽ��" = E."ObjectID"
	  LEFT JOIN "�������ɽ" as D ON E."����" = D."ObjectID"
          WHERE M."Superseded" is NULL AND
                M."����" = $qp_oid $tf $tt
                ORDER BY M."ObjectID" DESC
SQL;
  }
  if (mx_check_option('limit', $options))
    $sql .= "  LIMIT " . $options['limit'];
  $rxs = pg_fetch_all(pg_query($dbh,$sql));
  if ($rxs === false)
    return $result;

  foreach ($rxs as $rx) {
    $vb_array=array();

// 0601-2011
    // $rx_app = $_mx_use_old_rx_order ? 'rx_order.php' : 'rx_order3.php';
$rx_app = 'rx_order.php';

    $shots_app = $_mx_use_old_shots_order ? 'shots_order.php' : 'shots_order3.php';
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
    $meds = get_meds($rx['oid'], $rx['���'] ? 1:0);
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

function injection_module_index_info
(&$dbh, $p_oid, $p_pid, $time_from, $time_to, $options=NULL) {
	return pharma_module_index_info(&$dbh, $p_oid, $p_pid,
					$time_from, $time_to, $options, 'i');
}

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

// should return dict of timestamp, text, callback_url, and thumb.
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
	if (!is_null($time_to))
		$sql .= 'AND "ͽ��λ����" <= ' . mx_db_sql_quote($time_to);
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
	return $result;
}
?>
