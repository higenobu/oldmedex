<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_module.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/test/sorder.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/procedure/order.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';
$__everybody_index_pt_applist = array(
				      'meal_module_index_info',
				      'rx_module_index_info',
				 // 'otatest_module_index_info',
				      'injection_module_index_info',
				      'rehab_module_index_info',
				      'disease_module_index_info',
				      'karte_module_index_info',
					'xct_module_index_info',
				   // 'quickxray_module_index_info',
					// 'quickxray2_module_index_info',
				      'vital_module_index_info',
				      'test_module_index_info',
				      'stest_module_index_info',
				      'procedure_module_index_info',
				      'appt_module_index_info',
				      );

$__everybody_index_pt_order_applist = array(
				      'meal_module_index_info',
				      'rx_module_index_info',
				      'injection_module_index_info',
				      'rehab_module_index_info',
				      'test_module_index_info',
					// 'quickxray_module_index_info',
					//'quickxray2_module_index_info',
					//0328-2012   'xct_module_index_info',
				      'stest_module_index_info',
				      'procedure_module_index_info',
				      );

$__everybody_index_pt_reorder_applist = array(
				      'test_module_index_info',
				      'stest_module_index_info',
				      'procedure_module_index_info',
				      'rx_module_index_info',
					//'quickxray_module_index_info',
					//'quickxray2_module_index_info',
					'xct_module_index_info',
				      'injection_module_index_info',
				      );

$__everybody_index_pt_print_test_applist = array('test_module_index_info');
//0328-2012
//if($_mx_link_dreamx > 0) {
//  $__everybody_index_pt_applist[] = 'image_module_index_info';
//}

function timestamp_cmp($a, $b) {
  // sort in reverse chronological order
  if ($a['timestamp'] < $b['timestamp'])
    return 1;
  else if ($b['timestamp'] < $a['timestamp'])
    return -1;
  else
    return 0;
}
       
function __lib_u_everybody_index_pt__applist($apps)
{
	global $__everybody_index_pt_applist;
	global $__everybody_index_pt_order_applist;
	global $__everybody_index_pt_reorder_applist;
	global $__everybody_index_pt_print_test_applist;

	if ($apps == 'orders')
		$apps = $__everybody_index_pt_order_applist;
	else if ($apps == 'reorder' || $apps == 'print')
		$apps = $__everybody_index_pt_reorder_applist;
	else if ($apps == 'print-test')
		$apps = $__everybody_index_pt_print_test_applist;
	else if (is_null($apps))
		$apps = $__everybody_index_pt_applist;
	return $apps;
}

function &index_pt_collect($patients, $date_from=NULL, $date_to=NULL, $apps=NULL, $options=NULL) {

	$result = array();
	if (!$patients || !is_array($patients) || !count($patients))
		return $result;

	$apps = __lib_u_everybody_index_pt__applist($apps);
	$apps_limit = array();
	if ($options &&
	    array_key_exists('OrderType', $options) &&
	    $options['OrderType'] &&
	    count($options['OrderType'])) {
		foreach ($options['OrderType'] as $k) {
			$k = $k . '_module_index_info';
			$apps_limit[$k] = $k;
		}
	}

	$dbh = mx_db_connect();

	# Grab patient-room relationships first
	$ps = array();
	foreach ($patients as $p)
		$ps[] = $p[0];
	$patient_list = implode(', ', $ps);

	$stmt = <<<SQL
		SELECT RPD."´µ¼Ô", R."ÉÂ¼¼Ì¾"
		FROM "ÉÂ¼¼´µ¼ÔÉ½" AS RP
		JOIN "ÉÂ¼¼´µ¼Ô¥Ç¡¼¥¿" AS RPD
		ON RP."ObjectID" = RPD."ÉÂ¼¼´µ¼ÔÉ½"
		JOIN "ÉÂ¼¼°ìÍ÷É½" AS R
		ON R."ObjectID" = RP."ÉÂ¼¼"
		WHERE
		RP."Superseded" IS NULL AND
		RPD."´µ¼Ô" IN ($patient_list)
		ORDER BY RP."ÆüÉÕ" DESC, RPD."´µ¼Ô"
SQL;

	$pt2room = array();
	$pt2room_count = 0;

	$assign = pg_fetch_all(pg_query($dbh, $stmt));
	if (is_array($assign) && count($assign)) {
		foreach ($assign as $d) {
			$pt = $d['´µ¼Ô'];
			if (array_key_exists($pt, $pt2room))
				continue;
			$pt2room[$pt] = $d['ÉÂ¼¼Ì¾'];
			if (++$pt2room_count >= count($ps))
				break;
		}
	}

	foreach ($apps as $fn) {
		if (count($apps_limit) &&
		    !array_key_exists($fn, $apps_limit))
			continue;
		foreach ($patients as $p) {
			$oid = $p[0];
			$pid = $p[1];
			foreach ($fn($dbh, $oid, $pid,
				     $date_from, $date_to, $options) as $r) {
				$r['type'] = $fn;
				$r['´µ¼ÔObjectID'] = $oid;
				$r['´µ¼ÔID'] = $pid;
				$r['ÉÂ¼¼Ì¾'] = $pt2room[$oid];
				$result[] = $r;
			}
		}
	}
	return $result;
}
//0405-2012
function index_pt_collect_patients2($patientgroups,
				   $date_from=NULL,
				   $date_to=NULL,
				   $apps=NULL,
				   $options=NULL)
{
	$apps = __lib_u_everybody_index_pt__applist($apps);
	$apps_limit = array();
	if ($options &&
	    array_key_exists('OrderType', $options) &&
	    $options['OrderType'] &&
	    count($options['OrderType'])) {
		foreach ($options['OrderType'] as $k) {
			$k = $k . '_module_index_info';
			$apps_limit[$k] = $k;
		}
	}

	$group_limit = '';
 $u=mx_authenticate_user();
$uid=get_empid($u);
//  $a[]=$u;
 $a[]=$uid["ID"];
//0722-2012 shiji
//print 'AAAAAAAAAAAAAAAAAAA'.$a[].'login-user';
 

		$group_limit = '  (' .
			implode(', ', $a) . ') ';
/* 2012
	if ($patientgroups) {
		foreach ($patientgroups as $g)
			$a[] = mx_db_sql_quote($g);

		$group_limit = ' AND y.shiji IN (' .
			implode(', ', $a) . ') ';
	}
*/
	$a = array();
	foreach ($apps as $info_fn) {
		if (count($apps_limit) &&
		    !array_key_exists($info_fn, $apps_limit))
			continue;
		$pt_fn = $info_fn . "_patient_sql";
		$e = $pt_fn($date_from, $date_to, $options);
		if ($e)
			$a[] = $e;
	}
	if ($a) {
		$a = "AND (\n" . implode("\nOR\n", $a) . "\n)\n";
	} else {
		$a = '';
	}
//0722-2012 old: "½èÊý³«»ÏÆü" new: startdate
$a=' AND y."½èÊý³«»ÏÆü" >='."'".$date_from."'".' AND y."½èÊý³«»ÏÆü" <='."'".$date_to."'";
//0722-2012 old: µ­Ï¿¼Ô¡¡¡¡new:shiji
	$stmt = <<<SQL
		SELECT DISTINCT
		P."ObjectID", P."´µ¼ÔID", (P."À«" || P."Ì¾") AS "À«Ì¾"
		FROM "´µ¼ÔÂæÄ¢" AS P JOIN "ÌôºÞ½èÊýäµ" y ON y."´µ¼Ô"=P."ObjectID"
		WHERE P."Superseded" IS NULL AND y."Superseded" IS NULL AND y."µ­Ï¿¼Ô" IN
		$group_limit
		$a
SQL;
//0722-2012
 

print "<!--\n$stmt\n-->\n";
	$db = mx_db_connect();
	return pg_fetch_all(pg_query($db, $stmt));
}
//2012 

function index_pt_collect_patients($patientgroups,
				   $date_from=NULL,
				   $date_to=NULL,
				   $apps=NULL,
				   $options=NULL)
{
	$apps = __lib_u_everybody_index_pt__applist($apps);
	$apps_limit = array();
	if ($options &&
	    array_key_exists('OrderType', $options) &&
	    $options['OrderType'] &&
	    count($options['OrderType'])) {
		foreach ($options['OrderType'] as $k) {
			$k = $k . '_module_index_info';
			$apps_limit[$k] = $k;
		}
	}

	$group_limit = '';
 

		 
	if ($patientgroups) {
		foreach ($patientgroups as $g)
			$a[] = mx_db_sql_quote($g);

 
	}

	$a = array();
	foreach ($apps as $info_fn) {
		if (count($apps_limit) &&
		    !array_key_exists($info_fn, $apps_limit))
			continue;
		$pt_fn = $info_fn . "_patient_sql";
		$e = $pt_fn($date_from, $date_to, $options);
		if ($e)
			$a[] = $e;
	}
	if ($a) {
		$a = "AND (\n" . implode("\nOR\n", $a) . "\n)\n";
	} else {
		$a = '';
	}
//04-04-2012
//$a=' AND y.startdate >='."'".$date_from."'".' AND y.startdate <='."'".$date_to."'";
//04-04-2012
	$stmt = <<<SQL
		SELECT DISTINCT
		P."ObjectID", P."´µ¼ÔID", (P."À«" || P."Ì¾") AS "À«Ì¾"
		FROM "´µ¼ÔÂæÄ¢" AS P 
		WHERE P."Superseded" IS NULL 
		$group_limit
		$a
SQL;
print "<!--\n$stmt\n-->\n";
	$db = mx_db_connect();
	return pg_fetch_all(pg_query($db, $stmt));
}

//2012
function index_pt_left_pane_1($oid, $pid, $date_from=NULL, $date_to=NULL) {
  global $_mx_resource_dir;
       
  $me = $_SERVER['PHP_SELF'];
  $match = array();
  if (! preg_match('/^(\/au\/[^\/]+)\//', $me, &$match)) {
    print "UNM $me";
    return;
  }
  $cookie = $match[1];
  $result =& index_pt_collect(array(array($oid, $pid)),
			      $date_from,
			      $date_to,
			      NULL);
  usort(&$result, 'timestamp_cmp');
  $bg = 'background: #ffebcd';
  print "<div style=\"height: 600px; $bg\">";
  if (count($result) == 0) 
    print "ÍúÎò¤¬¤¢¤ê¤Þ¤»¤ó¡£";
  else {
    print "<table width=\"100%\" class=\"listofstuff\">\n";
    $oe = 'o';
    $ix = 0;
    foreach ($result as $d) {
      print "<tr class=\"$oe\">";
      if ($oe == 'o') { $oe = 'e'; } else { $oe = 'o'; }

      print "<td width=\"100px\">";
      if ($d['callback_url']) {
	      printf("<a href=\"%s%s\">%s</a>",
		     $cookie,
		     htmlspecialchars($d['callback_url']),
		     htmlspecialchars($d['timestamp']));
      } else {
	      print htmlspecialchars($d['timestamp']);
      }
      if ($d['fuller']) {
	/*
	 * If you ever wanted to make this configurable, change
	 * this (0) to 0 when you want to hide some items initially.
	 */
	if (0) { /* initially hidden */
		$img = "/$_mx_resource_dir/images/hide.png";
		$stp = 'style="display: none"';
	} else { /* initially shown */
		$img = "/$_mx_resource_dir/images/show.png";
		$stp = '';
	}
	$show_hide = ("show_hide('$ix', " .
		      "'/$_mx_resource_dir')");
/*
	print ('<a href="javascript:void(0)" ' .
	       "onclick=\"$show_hide\">" .
	       "<img id=\"SHC-$ix\" " .
	       "src=\"$img\" alt=\"\" " .
	       'border="0" height="18" ' .
	       'width="18"></a>');
*/


      }
      print "</td>";
      printf("</td><td>%s",
	     htmlspecialchars($d['text']));
      if ($d['fuller'])
	print ("<div id=\"SHD-$ix\" " .
	       $stp .
	       '>' .
	       $d['fuller'] .
	       '</div>');
      print "</td>";
      print "</tr>\n";
      $ix++;
    }
    print "</table>\n";
  }
  print "</div>\n";
}

function mx_draw_ppa_index($patient_ObjectID, $patient_ID) {
	global $mx_authenticate_current_user;
	global $__lib_u_manage_app_auth__applink_names;
	global $__lib_u_manage_app_auth__app_related;
	global $_mx_lazy_ppa_index;

	$me = $_SERVER['PHP_SELF'];
	$match = array();
	if (! preg_match('/^(\/au\/[^\/]+\/)(.*)$/', $me, &$match)) {
		print "UNM $me";
		return;
	}
	$cookie = $match[1];
	$me = $match[2];
	$apps = mx_find_application($mx_authenticate_current_user, 'A');
	$applist = array();
	$name_count = array();
	foreach ($apps as $category => $cls) {
		foreach ($cls as $d) {
			if ($d['ppa'] == ' ' || is_null($d['ppa']))
				continue;
			if (!array_key_exists($category, $applist)) {
				$applist[$category] = array();
			}
			$applist[$category][] = $d;
			$n = $d['name'];
			if (!array_key_exists($n, $name_count))
				$name_count[$n] =0;
			$name_count[$n]++;
		}
	}
	$hidden=(' style="position: absolute; visibility:hidden;"');

	print "<div id=\"ppa-index\"$hidden ";
	if ($_mx_lazy_ppa_index) {
		print "uprefix=\"$cookie\" ";
		print "poid=$patient_ObjectID ";
		print "pid=$patient_ID>\n";
		print "L A Z Y";
	} else {
		print "uprefix=\"\" ";
		print "poid=\"\" ";
		print "pid=\"\">\n";
		index_pt_left_pane_1($patient_ObjectID, $patient_ID);
	}
	print "</div>\n";
}

function index_pt_soap_type()
{
  $t = array('timestamp' => 'datetime',
	     'text' => 'string',
	     'fuller' => 'string',
	     'callback_url' => 'string',
	     'type' => 'string',
	     'object_id' => 'int'
	     );
  return $t;
}
?>
