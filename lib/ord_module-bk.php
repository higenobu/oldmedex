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
  $sql = "SELECT cast(M.order_dt as date) as order_dt, P.\"患者ID\" as pid,
                 M.ObjectID as oid
          FROM tbl_order as M, \"患者台帳\" P
          WHERE M.Superseded is NULL AND
                P.\"患者ID\" = M.pt_no AND
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
    $text = sprintf("(画像) 依頼日 %s", $image["order_dt"]);
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
	  $tf = 'AND M."記録日" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."記録日" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }
  $sql = 'SELECT M."実行時", M."食種",M."記録日",
                 M."ObjectID" as oid
          FROM "食事箋" as M
          WHERE M."Superseded" is NULL AND
                M."患者" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
	        ORDER BY M."ObjectID"';
  $meals = pg_fetch_all(pg_query($dbh, $sql));
  if ($meals === false)
	  return $result;
  foreach ($meals as $meal) {
    $uri = sprintf("/u/nutrition/order.php?pid=%s&detail=%s&oid=%s&move=yes",
		   $p_oid,$meal['oid'],$meal['oid']);
    $text = sprintf("(食事箋) 実行時 %s 食種 %s", $meal["実行時"], $meal["食種"]);
    $result[] = array('timestamp' => $meal["記録日"],
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
	    $tt = 'AND M."処方開始日" + ' .
	    'CAST((CAST(M."日数" AS VARCHAR) || \' day\') AS INTERVAL) > ' .
		    mx_db_sql_quote($time_from);
    if (!is_null($time_to))
	    $tf = 'AND M."処方開始日" <= '. mx_db_sql_quote($time_to);
  } else {
    if (!is_null($time_from))
	    $tf = 'AND M."処方年月日" >= '. mx_db_sql_quote($time_from);
    if (!is_null($time_to))
	    $tt = 'AND M."処方年月日" <= '. mx_db_sql_quote($time_to);
  }
  $qp_oid = mx_db_sql_quote($p_oid);
  if ($which == 'r') {
  $sql = <<<SQL
         SELECT M."処方年月日", M."区分", NULL AS "注射",
                 M."ObjectID" as oid, M."後発品", M."処方開始日",
	         E."姓" as "処方医姓", E."名" as "処方医名",
	         M."記録者" as "処方医",
		 (CASE WHEN
		  ((D."小分類" IS NULL) OR D."小分類" = '')
		  THEN D."中分類2"
		  ELSE D."小分類"
		  END) AS "科目名"
          FROM "薬剤処方箋" as M
	  LEFT JOIN "職員台帳" as E ON M."記録者" = E."ObjectID"
	  LEFT JOIN "部署一覧表" as D ON E."部署" = D."ObjectID"
          WHERE M."Superseded" is NULL AND
                M."患者" = $qp_oid $tf $tt
                ORDER BY M."ObjectID" DESC
SQL;
  } else {
  $sql = <<<SQL
         SELECT M."処方年月日", M."区分", 1 AS "注射",
                 M."ObjectID" as oid, M."後発品", M."処方開始日",
	         E."姓" as "処方医姓", E."名" as "処方医名",
	         M."記録者" as "処方医",
		 (CASE WHEN
		  ((D."小分類" IS NULL) OR D."小分類" = '')
		  THEN D."中分類2"
		  ELSE D."小分類"
		  END) AS "科目名"
          FROM "注射処方箋" as M
	  LEFT JOIN "職員台帳" as E ON M."記録者" = E."ObjectID"
	  LEFT JOIN "部署一覧表" as D ON E."部署" = D."ObjectID"
          WHERE M."Superseded" is NULL AND
                M."患者" = $qp_oid $tf $tt
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
    if ($rx["注射"]) {
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

    $text = sprintf("(%s箋) %s",
		    ($rx['注射'] ? "注射" : "薬剤"),
		    $rx["区分"]);
    
    // read content for each Rx
    $meds = get_meds($rx['oid'], $rx['注射'] ? 1:0);
    if (mx_check_option('limit', $options) == 1)
      $buf = set_body($meds, False, $rx['後発品']);
    else {
      $buf = set_body($meds, True, $rx['後発品']);
      array_unshift($buf, $rx["処方年月日"]);
    }
    $fuller=NULL;
    if(is_array($buf)) {
      $fuller = implode("<br>", $buf);
      $vb_array[] = mx_form_escape_key(array('RX', implode("\n", $buf)));
      $tmp = array();
      if (is_array($meds))
	foreach($meds as $m)
	  $tmp[] = $m['レセプト電算処理システム医薬品名'].$m['用量'].$m['用量単位'];
      $vb_array[] = mx_form_escape_key(array('MED', implode("、", $tmp)));
    }
    $it = array('timestamp' => $rx["処方年月日"],
		'text' => $text,
		'callback_url' => $uri,
		'thumb' => NULL,
		'value_blob' => implode('&', $vb_array),
		'fuller' => $fuller,
		'object_id' => $rx['oid'],
		'execdate' => $rx['処方開始日'],
		'処方医' => $rx['処方医'],
		'処方医名' => $rx['処方医名'],
		'処方医姓' => $rx['処方医姓'],
		'科目名' => $rx['科目名'],
	    );
    if ($options['ReturnOrderPieces']) {
	    $them = array();
	    $lacking = array();
	    foreach ($meds as $m) {
		    if ($options['CheckLabelPrint'] &&
			$m['病院使用ラベル要印刷'] != 'Y')
			    continue;
		    if (trim($m['日数']) == '') {
			    $lacking[] = $m;
			    continue;
		    }
		    if (count($lacking)) {
			    foreach ($lacking as $l) {
				    foreach (array('手技',
						   '日数',
						   '一日当り回数',
						   '用法') as $col)
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
		    $ed = mx_timestamp_parse($rx['処方開始日']);
		    $since = mx_timestamp_parse($time_from);
		    $until = mx_timestamp_parse($time_to);
		    $ed = $ed['timestamp'];
		    $since = $since['timestamp'];
		    $until = $until['timestamp'];
		    foreach ($hold as $m) {
			    $endoffset = $m['日数'] * 86400;
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
	  $tf = 'AND M."記録日" >= '. mx_db_sql_quote($time_from);
  } else {
	  $tf = '';
  }
  if (!is_null($time_to)) {
	  $tt = 'AND M."記録日" <= '. mx_db_sql_quote($time_to);
  } else {
	  $tt = '';
  }

  $sql = 'SELECT M."記録日", M."理学療法", M."作業療法", M."言語聴覚療法",
                 M."処方区分", M."ObjectID" as oid
          FROM "リハ処方箋" as M
          WHERE M."Superseded" is NULL AND
                M."患者" = ' . mx_db_sql_quote($p_oid) . " $tf $tt " . '
                ORDER BY M."ObjectID"';

  $rehabs = pg_fetch_all(pg_query($dbh,$sql));
  if ($rehabs === false)
	  return $result;
  foreach ($rehabs as $rehab) {
    $uri = sprintf("/u/rehabdr/rehab-order.php?pid=%s&detail=%s&oid=%s",
		   $p_oid,$rehab['oid'],$rehab['oid']);
    $text = sprintf("(リハ箋) 処方区分 %s %s %s %s",
		    $rehab["処方区分"],
		    ($rehab['理学療法'] ? "理学療法" : ""),
		    ($rehab['作業療法'] ? "作業療法" : ""),
		    ($rehab['言語聴覚療法'] ? "言語聴覚療法" : ""));
    $result[] = array('timestamp' => $rehab["記録日"],
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
		$limit[] = 'PDV."開始日" >= '. mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$limit[] = 'PDV."開始日" <= '. mx_db_sql_quote($time_to);
	$sql = '
SELECT PDV."ObjectID", PDV."開始日", PDV."疾病名",
PDV."接頭語名",
PDV."接頭語名2",
PDV."接頭語名3",
PDV."接頭語名4",
PDV."接尾語名1",
PDV."接尾語名2",
PDV."疑い",
PDV."主病名"
FROM "患者病名" AS PDV
WHERE PDV."Superseded" IS NULL AND
PDV."患者" = ' . mx_db_sql_quote($p_oid);

	if (count($limit)) {
		$sql .= ' AND ' . implode(' AND ', $limit);
	}		
	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;
	foreach ($all as $e) {
		$vb_array= NULL;
		$url = sprintf("/u/doctor/patient-disease.php?SetPatient=1&PatientID=%s&SetSODObject=%s", $p_pid, $e['ObjectID']);
		$text = sprintf("(病名) %s%s%s%s%s%s%s",
				$e['接頭語名'],
				$e['接頭語名2'],
				$e['接頭語名3'],
				$e['接頭語名4'],
				$e['疾病名'],
				$e['接尾語名1'],
				$e['接尾語名2']
				);
		if ($e['主病名'] == 'Y') {
			$text .= " [主病名〇]";
		}
		if ($e['疑い'] == 'Y') {
			$text .= " [疑い〇]";
		}
		$vb_array[] = mx_form_escape_key(array('病名', $text));
		$result[] = array('timestamp' => $e['開始日'],
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
		SELECT "予約時刻", "予約終了時刻", "予約先", "目的"
		FROM APPT_LIST_YET_TO_SHOW
		WHERE patient_id = $poid
SQL;
	if (!is_null($time_from))
		$sql .= 'AND "予約時刻" >= ' . mx_db_sql_quote($time_from);
	if (!is_null($time_to))
		$sql .= 'AND "予約終了時刻" <= ' . mx_db_sql_quote($time_to);
	$all = pg_fetch_all(pg_query($dbh, $sql));
	if ($all === false)
		return $result;
	foreach ($all as $e) {
		$t = substr($e['予約時刻'], 0, 10);
		$ob = '';
		if (trim($e['目的']) != '') {
			$ob = " (" . trim($e['目的']) . ")";
		}
		$text = sprintf("(予約) %s, %s%s",
				substr($e['予約時刻'], 11, 5),
				$e['予約先'],
				$ob);
		$result[] = array('timestamp' => $t,
				  'text' => $text);
	}
	return $result;
}
?>
