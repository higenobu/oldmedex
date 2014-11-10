<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/calend.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/everybody/ps-anew.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';

$__lib_u_manage_floor_ed_floors = array();
$__floor_config = $_SERVER['DOCUMENT_ROOT']."/lib/u/manage/floor";
//include_once "$__floor_config/$_mx_hospital_floor/floors.php";
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/floor/krh/floors.php';
$u = mx_authenticate_user();
$auth = mx_authorization();
if (! $auth[0]) {
  mx_authorization_error($auth);
  return;
}

$__lib_u_manage_floor_ed_coloring_button_breaks = array("ＦＩＭ総合" => 1);
$__lib_u_manage_floor_ed_coloring_ix_to_label =
array(
      '性別',
     '回復',
     '在院日数',
     '医学的不安定',
   '感染症',
      'ＦＩＭ総合',
      'ＦＩＭ移動',
     'ＦＩＭトイレ',
      'ＦＩＭ理解',
      );
$__lib_u_manage_floor_ed_coloring_label_to_ix = array();

function __lib_u_manage_floor_ed_coloring_setup() {
	global $__lib_u_manage_floor_ed_coloring_ix_to_label;
	global $__lib_u_manage_floor_ed_coloring_label_to_ix;
	foreach ($__lib_u_manage_floor_ed_coloring_ix_to_label
		 as $ix => $label) {
		$__lib_u_manage_floor_ed_coloring_label_to_ix[$label] = $ix;
	}
}
__lib_u_manage_floor_ed_coloring_setup();

class floor_ed_calendar extends simple_clickable_month_calendar_display {
	function floor_ed_calendar($prefix) {
		$config = array('ShowHide' => 0);
		simple_clickable_month_calendar_display::simple_clickable_month_calendar_display($prefix, $config);
		$this->showing = 1;
	}

	function draw_day($year, $month, $mday) {
		$ymd = $this->chosen();
		$mday_show = $mday;
		if ($ymd &&
		    $ymd[0] == $year && $ymd[1] == $month &&
		    $ymd[2] == $mday) {
			print "<span class=\"title\">$mday</span>";
			return;
		}
		simple_clickable_month_calendar_display::draw_day($year, $month, $mday);
	}
}

/* This is a callback from the HTML template to embed HTML representation
 * for each slot (i.e. the "o()" function).
 */
function draw_floor_element($thing) {
	global $__this_output_data, $__calendar;

	if ($thing == 'calendar') {
		$__calendar->draw();
	}
	else if ($thing == 'legend') {
		$l = $__this_output_data['legend'];
		$s = $__this_output_data['legend-stat'];
		if (!(is_array($l) && count($l)))
			return;

		$w = array();
		foreach ($s as $ward => $data) {
			if ($ward != '')
				$w[$ward] = $data;
		}
		ksort($w);
		$w['退避'] = $s[''];

		/*
		 * $w[$wardname] => $data (wardname includes "wait" and "sum")
		 * $data[$cclass] => $number (cclass are 'red', 'blue',...)
		 * $l[$cclass] => $wardname
		 */
		print '<table class="legend"><tr><td>(凡例)</td>';
		foreach ($w as $ward => $data) {
			print '<td>';
			print htmlspecialchars($ward);
			print '</td>';
		}
		print "<td>合計</td></tr>\n";

		/*
		 * $sums[$ward] => $number
		 */
		$sums = array();
		foreach ($l as $cclass => $txt) {
			print "<tr class=\"$cclass\">";
			print "<td>";
			print htmlspecialchars($txt);
			print "</td>\n";
			$sum = 0;
			foreach ($w as $ward => $data) {
				print '<td>';
				$cnt = $w[$ward][$cclass] + 0;
				print $cnt;
				print '</td>';
				$sum += $cnt;
				$sums[$ward] += $cnt;
			}
			print "<td>$sum</td>";
			print "</tr>\n";
		}

		print "<tr>";
		print "<td>合計</td>\n";
		$sum = 0;
		foreach ($w as $ward => $data) {
			print '<td>';
			$cnt = $sums[$ward] + 0;
			print $cnt;
			print '</td>';
			$sum += $cnt;
		}
		print "<td>$sum</td>";
		print "</tr>\n";
		
		print "</table>\n";
	}
	else if ($thing == 'soon') {
		$l = $__this_output_data['soon-discharge'];
//0723-2012
$l=null;
//

		if (!(is_array($l) && count($l)))
			return;
		asort($l);
		print '<table class="soon"><tr><td class="caption">';
		print '</td></tr>';
		foreach ($l as $junk) {
			print "<tr><td class=\"data\">$junk</td></tr>";
		}
		print "</tr></table>\n";
	}
	else {
		$d = $__this_output_data[$thing];
		if ($d == '') {
			print '&nbsp;';
		}
		else {
			print $d;
		}
	}
}

function floor_ed_fill_in_fim_diff($d) {
	$nv = $d[0];
	$ov = $d[1];
	if ($ov)
		return htmlspecialchars("$nv (前回 $ov)");
	else
		return htmlspecialchars("$nv");
}

function draw_header($opts, $data, $output_data) {
	global $__lib_u_manage_floor_ed_coloring_ix_to_label;
	global $_mx_resource_dir;
	$ix_to_label = $__lib_u_manage_floor_ed_coloring_ix_to_label;

	$floor = $opts['floor'];

?><html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-jp">
<link rel="stylesheet"
 href="/<? print $_mx_resource_dir ?>/mxstyle.css" />
<script language="JavaScript"
 src="/<? print $_mx_resource_dir ?>/mx.js"></script>
<script language="JavaScript"
 src="/<? print $_mx_resource_dir ?>/infobox.js"></script>
<style type="text/css">
 td.bedcell {
     background-color: #ccc;
     text-align: center;
     vertical-align: center;
 }
 td.wait {
     background-color: #ccc;
     text-align: left;
     vertical-align: top;
     padding-top: 8px;
 }
 td.wait span.padded { padding: 2px; }
 tr.caption {
     height: 24px;
 }
 td.calendar {
     vertical-align: top;
 }
 td.caption { text-align: center; background-color: <?
 print $opts['capcolor'] ?>; }
 table.floor { background-color: <?
 print $opts['floorcolor'] ?>; }
 td.tiptd { background-color: #ffc; }
 td.tiptd * table { width: 100%; }
 td.tiptd * th.ptt { background-color: #282; }
 td.tiptd * td.pst { background-color: #cf8; }
 td.tiptd * th.divider { background-color: #000; color: #fff; }

 a:visited, a:link { color: #000; }
 a:hover { color: #888; }
 a.active { background-color: #fff; }
 a { text-decoration: none; }
 a.pending:before { content: "?" }
 a.pending { text-decoration: underline; }
 a.soon { text-decoration: ; }

 table.legend {
	 border-color: black;
	 border-width: thin;
	 border-style: dashed;
	 padding: 8px;
 }

 table.legend tr.paint-blue td { background-color: #88f; }
 table.legend tr.paint-red td { background-color: #f88; }
 table.legend tr.paint-green td { background-color: #8f8; }
 table.legend tr.paint-yellow td { background-color: #ff8; }
 table.legend tr.paint-pink td { background-color: #faf; }
 table.legend tr td { text-align: right; }

 span.padded { padding: 2px; }
 span.paint-blue { background-color: #88f; }
 span.paint-red { background-color: #f88; }
 span.paint-green { background-color: #8f8; }
 span.paint-yellow { background-color: #ff8; }
 span.paint-pink { background-color: #faf; }

 table.soon { width: 100%; }
 table.soon td.caption:before { content: "予定" }
 table.soon td.caption { background-color: #fca; }
 table.soon td.data { background-color: #000; color: #ff0; }
</style>

<script language="JavaScript">

// use it like this:
// (a class="active" onclick="pickit('901.1')") Foo (/a)
function pickit(ident) {
	elem = document.getElementById('update')
	elem.value = 'pickit=' + ident
	form = document.getElementById('form')
	form.submit() 
}
function floorit(ident) {
	elem = document.getElementById('floor')
	elem.value = 'pickit=' + ident
	form = document.getElementById('form')
	form.submit() 
}
function colorit(ident) {
	elem = document.getElementById('color')
	elem.value = 'pickit=' + ident
	form = document.getElementById('form')
	form.submit() 
}
</script>
</head>
<body>
<script language="JavaScript">
inittips()
<?php
 $pi = $data['patient-info'];
 $es = $data['empty-schedule'];
 foreach ($pi as $oid => $data) {
	 $slot = $output_data['occupies'][$oid];
	 if (!$slot ||
	     /* This is a hack */
	     (strcmp(substr($slot, 0, 5), "wait.") &&
	      strcmp(substr($slot, 0, 1), $floor)))
		 continue;

	$n = addslashes($data['姓名']);
	$tt = '';
	foreach (array('患者ID', '性別', '利き手', '生年月日',
		       '発症日', '入院日',
		       '退院予定日') as $c) {
		if (array_key_exists('DD'.$c, $data))
			$da = $data['DD'.$c];
		else
			$da = $data[$c];
		$da = htmlspecialchars($da);
		$tt .= '<tr><td>'.addslashes(htmlspecialchars($c)).'</td>';
		$tt .= '<td>'.addslashes($da);
		$tt .= '</td></tr>';
	}

	$color_label = ((0 <= $opts['coloring']) ?
			$ix_to_label[$opts['coloring']] : NULL);
	switch ($color_label) {
	case '性別':
	case '医学的不安定':
		break; /* nothing to add */

	case '感染症':
		if ($data['感染症'] != '') {
			$da = htmlspecialchars($data['感染症']);
			$tt .= '<tr><td>感染症</td><td>';
			$tt .= addslashes($da);
			$tt .= '</td></tr>';
		}
		break;
	case 'ＦＩＭ移動':
	case 'ＦＩＭトイレ':
	case 'ＦＩＭ理解':
	case 'ＦＩＭ総合':
		$raw_fim = $data['FIM']['RAW'];
		$fim_head = addslashes("<tr><th class=\"divider\" " .
				       "colspan=\"2\">ＦＩＭ</th></tr>");
		if ($data['FIM']['PHY'][0] && $data['FIM']['COG'][0]) {
			$t = '<tr><td>運動項目合計</td><td>';
			$t .= floor_ed_fill_in_fim_diff($data['FIM']['PHY']);
			$t .= '</td></tr>';
			$t .= '<tr><td>認知項目合計</td><td>';
			$t .= floor_ed_fill_in_fim_diff($data['FIM']['COG']);
			$t .= '</td></tr>';
			$t .= '<tr><td>ＦＩＭ合計</td><td>';
			$t .= floor_ed_fill_in_fim_diff($data['FIM']['SUM']);
			$t .= '</td></tr>';
			$tt .= $fim_head . addslashes($t);
			$fim_head = '';
		}
		foreach ($raw_fim[0] as $col => $val) {
			if ($col == '移動手段')
				continue;
			if (array_key_exists($col, $raw_fim[1])) {
				$oval = $raw_fim[1][$col];
				$da = htmlspecialchars("$val (前回 $oval)");
			}
			else {
				$da = htmlspecialchars("$val");
			}
			$tt .= $fim_head . '<tr><td>'.
				addslashes(htmlspecialchars($col)).
				'</td>';
			$tt .= '<td>'.addslashes($da);
			$tt .= '</td></tr>';
			$fim_head = '';
		}
		break;
	case '回復':
	case '在院日数':
		$da = htmlspecialchars($data['在院日数'] . ' 日');
		$tt .= '<tr><td>在院日数</td><td>';
		$tt .= addslashes($da);
		$tt .= '</td></tr>';
		break;
	}
	$s = "<table>$tt</table>";
	print "maketip(\"tip-$oid\", \"$n\", \"$s\")\n";
 }
 foreach ($es as $slot => $data) {
	/* This is a hack */
	if (strcmp(substr($slot, 0, 5), "wait.") &&
	    strcmp(substr($slot, 0, 1), $floor))
		continue;
	$n = $slot;
	$t = '';
	$t .= ('<tr><td>予定患者名</td><td>' .
	      htmlspecialchars($data['patient-name']) .
	      '</td></tr>');
	$t .= ('<tr><td>患者ID</td><td>' .
	      htmlspecialchars($data['patient-id']) .
	      '</td></tr>');
	$t .= ('<tr><td>自</td><td>' .
	      htmlspecialchars($data['begin-full']) .
	      '</td></tr>');
	$t .= ('<tr><td>至</td><td>' .
	      htmlspecialchars($data['end-full']) .
	      '</td></tr>');
	$s = "<table>$t</table>";
	print "maketip(\"tip-b-$slot\", \"$n\", \"$s\")\n";
 }
?>
</script>
<form id="form" method="POST">
<?php }

function draw_footer($session, $data) {
	global $__dbg;
	global $__this_output_data;
	print "</form>";
	if (0) {
		print "Session: $session<br />\n";
		print "Debug: $__dbg<br />\n";
		print "<!--\n";
		var_dump($data);
		print "-->\n";
	}
	if (0) {
		print "Debug: $__dbg<br />\n";
		$db = mx_db_connect();
		$stmt = 'SELECT * FROM "病床管理"
ORDER BY "占有者","占有開始"';
		print "<table>";
		print "<tr><th>";
		print "占有者";
		print "</th><th>";
		print "病室";
		print "</th><th>";
		print "病床";
		print "</th><th>";
		print "占有開始";
		print "</th><th>";
		print "占有終了";
		print "</th></tr>";
		foreach (pg_fetch_all(pg_query($db, $stmt)) as $data) {
			print "<tr><td>";
			print htmlspecialchars($data["占有者"]);
			print "</td><td>";
			print htmlspecialchars($data["病室"]);
			print "</td><td>";
			print htmlspecialchars($data["病床"]);
			print "</td><td>";
			print htmlspecialchars($data["占有開始"]);
			print "</td><td>";
			print htmlspecialchars($data["占有終了"]);
			print "</td></tr>";
		}
		print "</table>";
	}
	print $__this_output_data['schedule'];
	print "</body>\n</html>\n";
}

$__ses_table = "病床管理セッション";

function floor_ed_mark_stay($data)
{
	$days = $data['在院日数'];
	/* 4mo < red
	 * 3mo < yellow
	 * 2mo < blue
	 * < 2mo no mark
	 */
	if (150 < $days)
		return 'paint-red';
	else if (90 < $days)
		return 'paint-yellow';
	else if (60 < $days)
		return 'paint-blue';
	else
		return 'paint-green';
}

function floor_ed_mark_reha_class($data)
{
	$basis = $data['回復期'];
	$days = $data['在院日数'];
	$class = array(
		'A' => 150,
		'B' => 180,
		'C' => 90,
		'D' => 90,
		'E' => 60,
		);
	if (array_key_exists($basis, $class) &&
	    ($days <= $class[$basis]))
		return 'paint-blue';
	else
		return 'paint-red';
}

function floor_ed_mark_fim($data)
{
	if (!$data || !array_key_exists('FIM', $data))
		return;
	$fim = $data['FIM'];
	if (!array_key_exists('SUM', $fim))
		return;
	if (!$fim['SUM'][0] || !$fim['SUM'][1])
		return NULL;
	$improvement = $fim['SUM'][0] - $fim['SUM'][1];
	if (5 < $improvement)
		return 'paint-blue';
	else if (3 <= $improvement)
		return 'paint-green';
	else if (-3 < $improvement)
		return 'paint-yellow';
	else if (-5 < $improvement)
		return 'paint-pink';
	else
		return 'paint-red';
}

function floor_ed_mark_fim_mobil($data)
{
	if (!$data ||
	    !array_key_exists('移動手段', $data['FIM']['RAW'][0]))
		return;
	$fim = $data['FIM']['RAW'][0];
	$wheelchair = ($fim['移動手段'] == 'W');
	if (!$wheelchair && 3 < $fim['歩行'])
		return 'paint-blue';
	if (5 <= $fim['車椅子'])
		return 'paint-yellow';
	else
		return 'paint-red';
}

function floor_ed_mark_fim_generic($data, $fld)
{
	if (0) {
		print "<!-- GENERIC $fld\n";
		var_dump($data);
		print "-->\n";
	}

	if (!$data ||
	    !array_key_exists($fld, $data['FIM']['RAW'][0]))
		return;
	$toi = $data['FIM']['RAW'][0][$fld];
	if (6 <= $toi)
		return 'paint-blue';
	else if (4 <= $toi)
		return 'paint-yellow';
	else
		return 'paint-red';
}

function floor_ed_mark_fim_toi($data)
{
	return floor_ed_mark_fim_generic($data, 'トイレ動作');
}

function floor_ed_mark_fim_und($data)
{
	return floor_ed_mark_fim_generic($data, '理解');
}

function floor_ed_summarize_fim_data(&$data)
{
	$cog = array('理解' => 1,
		     '表出' => 1,
		     '社会的交流' => 1,
		     '問題解決' => 1,
		     '記憶' => 1);
	$cogsum = array(0, 0);
	$physum = array(0, 0);
	$indiv = array(array(), array());
	foreach ($data as $col => $val) {
		$round = 0;
		if (substr($col, -2, 2) == '_1') {
			$round = 1;
			$col = substr($col, 0, -2);
		}
		if ($col == '移動手段') {
			$indiv[$round][$col] = $val;
			continue;
		}
		if (substr($col, -2, 2) != '_P')
			continue;
		$base = substr($col, 0, -2);
		if (array_key_exists($base, $cog)) {
			$cogsum[$round] += $val;
		}
		else {
			$physum[$round] += $val;
		}
		$indiv[$round][$base] = $val;
	}

	$sumsum = array(0, 0);
	for ($round = 0; $round < 2; $round++) {
		$physum[$round] -= min($indiv[$round]['車椅子'],
				       $indiv[$round]['歩行']);
		$sumsum[$round] = $physum[$round] + $cogsum[$round];
	}
	return array('PHY' => $physum,
		     'COG' => $cogsum,
		     'SUM' => $sumsum,
		     'RAW' => $indiv);
}

function floor_ed_read_data($db, $theday, $today) {
 
	$d = array();
	$pi = array();
	$stmt = '
SELECT
  P."ObjectID", P."患者ID", (P."姓" || P."名") AS "姓名",
  P."性別", P."利き手", P."生年月日", P."感染症", P."医学的不安定",
  P."回復期", P."発症日", P."入院日", P."退院予定日", P."退院予定・見込",
  P."希望病棟", P."入外区分",

  (CASE WHEN P."性別" = \'M\' THEN \'男\'
   WHEN P."性別" = \'F\' THEN \'女\'
   ELSE \'不明\' END) as "DD性別",
  (CASE WHEN P."利き手" = \'R\' THEN \'右\'
   WHEN P."利き手" = \'L\' THEN \'左\'
   WHEN P."利き手" = \'r\' THEN \'右(矯正)\'
   ELSE \'不明\' END) as "DD利き手",
  COALESCE(to_char(P."生年月日", \'YYYY-MM-DD\'), \'不明\')
      as "DD生年月日",
  COALESCE(to_char(P."発症日", \'YYYY-MM-DD\'), \'-\')
      as "DD発症日",
  COALESCE(to_char(P."入院日", \'YYYY-MM-DD\'), \'-\')
      as "DD入院日",
  COALESCE(to_char(P."退院予定日", \'YYYY-MM-DD\'), \'-\')
      as "DD退院予定日",

  BC."病床", BC."占有開始", BC."占有終了",
  W."病棟名", R."病室名", R."定数",
  BC."病室", BC."占有者"
FROM
  "患者台帳" AS P
LEFT JOIN
  "病床管理" AS BC
    ON (BC."占有者" = P."ObjectID") AND
       (BC."占有開始" <= \'' . $theday . '\') AND
       NOT (BC."占有終了" <= \'' .  $theday . '\')
LEFT JOIN
 "病室一覧表" AS R ON BC."病室" = R."ObjectID"
LEFT JOIN
 "病棟一覧表" AS W ON R."病棟" = W."ObjectID"
WHERE
  P."Superseded" IS NULL AND
  (
    ( (P."入外区分" IN (\'I\', \'W\')) AND
      (P."入院日" <= \'' . $theday . '\') AND
      (NOT (P."退院予定日" <= \'' . $theday . '\')) ) OR
    ( \'' . $theday . '\' < \'' . $today . '\' AND
      BC."占有開始" IS NOT NULL ) )
ORDER BY
  P."ObjectID"
';
	if (0) {
		print "<!--";
		print $stmt;
		print "-->";
	}
//0820-2012
//print "SQL=".$stmt;
//0820-2012
	$sth = pg_query($db, $stmt);
	$result = pg_fetch_all($sth);
	if (!$result) { $result = array(); }
	$w = 0;
	foreach ($result as $v) {
		if ($v['病室名'] && $v['病床']) { 
			$b = $v['病室名'] . '.' . $v['病床'];
		}
		else {
			$w++;
			$b = 'wait' . '.' . $w;
		}
		$oid = $d[$b] = $v['ObjectID'];
		$fim = array();
		__lib_u_everybody_ps_anew_fim_fetch('N', $oid, $db, &$fim, 2);
		// we could pass &$v and add everything...
		if (0) {
			print "<!-- FIM $oid\n";
			var_dump($fim);
			print "-->\n";
		}
		$v['FIM'] = floor_ed_summarize_fim_data(&$fim);
		if (0) {
			print "<!-- FIM SUM $oid\n";
			var_dump($v['FIM']);
			print "-->\n";
		}
		$in = strtotime($v['入院日']);
		$cur = strtotime($theday);
		$v['在院日数'] = round(($cur - $in) / 3600 / 24);

		$pi[$oid] = $v;
	}

	$stmt = "
SELECT R.\"病室名\", B.\"病床\",
       P.\"患者ID\", (P.\"姓\" || P.\"名\") AS \"姓名\",
       min(B.\"占有開始\") AS \"占有\", B.\"占有終了\"
FROM
    \"病床管理\" AS B
JOIN
    \"病室一覧表\" AS R
ON
    B.\"病室\" = R.\"ObjectID\"
JOIN
    \"患者台帳\" AS P
ON
    B.\"占有者\" = P.\"ObjectID\"
WHERE
    '$theday' <= \"占有開始\"
GROUP BY R.\"病室名\", B.\"病床\", B.\"占有開始\", B.\"占有終了\",
 P.\"患者ID\", P.\"姓\", P.\"名\"
HAVING  B.\"占有開始\" = min(B.\"占有開始\")
";
	$sth = pg_query($db, $stmt);
	$esdata = pg_fetch_all($sth);
	if (!$esdata) { $esdata = array(); }
	$es = array();
	foreach ($esdata as $v) {
		$match = array();
		$t = '空';
		if (preg_match('/^(\d+)-(\d+)-(\d+) \d+:\d+:\d+(?:\.\d+)?$/',
			       $v["占有"], &$match)) {
			$bf = sprintf("%04d/%02d/%02d",
				      $match[1], $match[2], $match[3]);
			$b = sprintf("%02d/%02d", $match[2], $match[3]);
		}
		if (preg_match('/^(\d+)-(\d+)-(\d+) \d+:\d+:\d+(?:\.\d+)?$/',
			       $v["占有終了"], &$match)) {
			$e = sprintf("%04d/%02d/%02d",
				     $match[1], $match[2], $match[3]);
		}
		if ($b && $bf && $e) {
			$es[$v["病室名"].'.'.$v["病床"]] =
				array('begin' => $b,
				      'begin-full' => $bf,
				      'end-full' => $e,
				      'patient-id' => $v["患者ID"],
				      'patient-name' => $v["姓名"]);
		}
	}
	$esdata = $es;

	$stmt = 'SELECT R."病室名", R."定数" FROM "病室一覧表" AS R';
	$sth = pg_query($db, $stmt);
	$result = pg_fetch_all($sth);
	if (!$result) { $result = array(); }
	$sth = pg_query($db, $stmt);
	$result = pg_fetch_all($sth);
	if (!$result) { $result = array(); }
	foreach ($result as $v) {
		$r = $v['病室名'];
		$c = $v['定数'];
		for ($i = 1; $i <= $c; $i++) {
			$k = $r . '.' . $i;
			if (!array_key_exists($k, $d)) {
				$d[$k] = 0;
				if (array_key_exists($k, $esdata))
					$es[$k] = $esdata[$k];
			}
		}
	}
	return array('floor-data' => $d,
		     'patient-info' => $pi,
		     'empty-schedule' => $es);
}

/* This probably needs to be kept when database view switches from "where
 * are things right now" model to "who occupies which slot during what
 * timerange".  But the array itself should come from the database.
 */
function initialize_session(&$db, $theday, $today) {
	global $__ses_table;
	$session = mx_db_allocate_unused_id(&$db, $__ses_table . "_ID_seq");


	$d = floor_ed_read_data(&$db, $theday, $today);
	if (0) {
		print "<!--";
		var_dump($d);
		print "-->";
	}
	$d = serialize($d);

	mx_db_insert_tuple(&$db, $__ses_table,
			   array('ID' => $session, 'data' => $d));
	return $session;
}

function floor_ed_locate(&$db, $pt, $ymd)
{
	global $__dbg;
	
	$__dbg .= "<br /> locate $pt $ymd";
	$p = mx_db_sql_quote($pt);
	$t = mx_db_sql_quote($ymd);
	$stmt = 'SELECT
  P."ObjectID", P."入院日", P."退院予定日", P."退院予定・見込",
  BC."占有開始", BC."占有終了", BC."占有者",
  BC."病室", BC."病床"
FROM
  "病床管理" AS BC
JOIN
  "患者台帳" AS P
    ON BC."占有者" = P."ObjectID"
WHERE
  P."ObjectID" = '. $p . ' AND
  BC."占有開始" <= ' . $t . ' AND NOT (BC."占有終了" <= ' . $t . ')';
	return mx_db_fetch_single(&$db, $stmt);
}

/* Kick out $pt at $ymd from all beds */
function floor_ed_vacate(&$db, $pt, $ymd, &$pcr)
{
	global $__dbg;
	$__dbg .= "<br />vacate $pt $ymd";
	$res = floor_ed_locate($db, $pt, $ymd);
	if (!$res) {
		$__dbg .= "<br />vacate no current.";
		return;
	}
	$room = $res['病室'];

	/* The occupants of the room is going to change */
	$pcr[$room] = 1;

	$bed = $res['病床'];
	if ($res['占有開始'] == $ymd) {
		$ymd = mx_db_sql_quote($ymd);
		$t2_q = mx_db_sql_quote($res['占有終了']);
		$stmt = 'DELETE FROM "病床管理" WHERE
  "病室" = '.$room.' AND "病床" = '.$bed.' AND "占有者" = '.$pt.' AND
  "占有開始" = '.$ymd.' AND "占有終了" = '.$t2_q;
		$__dbg .= "<br />" . htmlspecialchars($stmt);
		pg_query($db, $stmt);
	}
	else {
		$ymd = mx_db_sql_quote($ymd);
		$t1_q = mx_db_sql_quote($res['占有開始']);
		$t2_q = mx_db_sql_quote($res['占有終了']);
		$stmt = 'UPDATE "病床管理" SET "占有終了" = '.$ymd.' WHERE
  "病室" = '.$room.' AND "病床" = '.$bed.' AND "占有者" = '.$pt.' AND
  "占有開始" = '.$t1_q.' AND "占有終了" =  '.$t2_q;
		$__dbg .= "<br />" . htmlspecialchars($stmt);
		pg_query($db, $stmt);
	}
}

function floor_ed_locate_occupant(&$db, $room, $bed, $ymd)
{
	global $__dbg;
	
	$__dbg .= "<br /> locate-occupant $room $bed $ymd";
	$ymd_q = mx_db_sql_quote($ymd);
	$room_q = mx_db_sql_quote($room);
	$bed_q = mx_db_sql_quote($bed);
	$stmt = 'SELECT
  P."ObjectID", P."入院日", P."退院予定日", P."退院予定・見込",
  BC."占有開始", BC."占有終了", BC."占有者",
  BC."病室", BC."病床"
FROM
  "病床管理" AS BC
JOIN
  "患者台帳" AS P
    ON BC."占有者" = P."ObjectID"
WHERE
  BC."病室" = '.$room_q.' AND BC."病床" = '.$bed_q.' AND
  BC."占有開始" <= '.$ymd_q.' AND NOT (BC."占有終了" <= '.$ymd_q.')';
	$__dbg .= "<br /> $stmt";
	return mx_db_fetch_single(&$db, $stmt);
}

function floor_ed_next_use(&$db, $room, $bed, $ymd)
{
	global $__dbg;
	$__dbg .= "<br />next-use $room $bed $ymd";
	$ymd_q = mx_db_sql_quote($ymd);
	$room_q = mx_db_sql_quote($room);
	$bed_q = mx_db_sql_quote($bed);
	$stmt = 'SELECT
  BC."病床", BC."占有開始", BC."占有終了", BC."病室", BC."占有者"
FROM
  "病床管理" AS BC
WHERE
  BC."病室" = '.$room_q.' AND BC."病床" = '.$bed_q.' AND '.$ymd_q.' <= BC."占有開始"
ORDER BY
  BC."占有開始"
LIMIT 1';
	$__dbg .= "<br />next-use $stmt";
	return mx_db_fetch_single(&$db, $stmt);
}

function floor_ed_next_assigned(&$db, $pt, $ymd)
{
	global $__dbg;
	$__dbg .= "<br />next-assigned $pt $ymd";
	/* When will the $pt have any assignment after $ymd */
	$pt_q = mx_db_sql_quote($pt);
	$ymd_q = mx_db_sql_quote($ymd);
	$stmt = '
SELECT
  BC."病床", BC."占有開始", BC."占有終了", BC."病室", BC."占有者"
FROM
  "病床管理" AS BC
WHERE
  BC."占有者" = '.$pt_q.' AND '.$ymd_q.' <= BC."占有開始"
ORDER BY
  BC."占有開始"
LIMIT 1';
	return mx_db_fetch_single(&$db, $stmt);
}

function floor_ed_coalesce_use(&$db, $pt, $room, $bed, $ymd, $pt_end)
{
	global $__dbg;

	$__dbg .= "<br />coalesce $pt $room $bed $ymd $pt_end";
	/* If $pt's use of $room/$bed ends at $ymd, extend its use til
	 * $pt_end.  At this point we know the bed is unused between
	 * $ymd and $pt_end.
	 */
	$ymd_q = mx_db_sql_quote($ymd);
	$room_q = mx_db_sql_quote($room);
	$bed_q = mx_db_sql_quote($bed);
	$pt_q = mx_db_sql_quote($pt);
	$pt_end_q = mx_db_sql_quote($pt_end);
	$stmt = 'SELECT 
  BC."病床", BC."占有開始", BC."占有終了", BC."病室", BC."占有者"
FROM
  "病床管理" AS BC
WHERE
  BC."病室" = '.$room_q.' AND BC."病床" = '.$bed_q.' AND
  '.$ymd_q.' = BC."占有終了" AND BC."占有者" = '.$pt_q;
	$res = mx_db_fetch_single($db, $stmt);

	$__dbg .= "<br /> ". htmlspecialchars($stmt);
	if (!$res) {
		$__dbg .= "<br />No result.";
		return NULL;
	}
	$cbeg = $res['占有開始'];
	$cend = $res['占有終了'];
	$cbeg_q = mx_db_sql_quote($cbeg);
	$cend_q = mx_db_sql_quote($cend);

	$beg = $cbeg;
	if ($ymd < $beg)
		$beg = $ymd;
	$beg_q = mx_db_sql_quote($beg);
	$stmt = 'UPDATE "病床管理"
SET
  "占有開始" = '.$beg_q.',
  "占有終了" = '.$pt_end_q.'
WHERE
  "病室" = '.$room_q.' AND "病床" = '.$bed_q.' AND
  "占有開始" = '.$cbeg_q.' AND
  "占有終了" = '.$cend_q.' AND
  "占有者" = '.$pt_q;
	$__dbg .= "<br /> ". htmlspecialchars($stmt);
	pg_query($db, $stmt);
	return 1;
}

function floor_ed_occupy(&$db, $pt, $room, $bed, $ymd, &$pcr)
{
	global $__dbg;

	/* room needs to be converted to its object id */
	$room_q = mx_db_sql_quote($room);
	$stmt = 'SELECT "ObjectID" FROM "病室一覧表"
WHERE "病室名" = '.$room_q.' AND "Superseded" IS NULL';
	$room_obj = mx_db_fetch_single(&$db, $stmt);
	if (!$room_obj)
		die("OOPS? $room");
	$room_obj = $room_obj['ObjectID'];

	$__dbg .= "<br /> occupy $pt $room ($room_obj) $bed $ymd";

	/* Where is the patient on that day? */
	$current = floor_ed_locate($db, $pt, $ymd);
	if ($current) {
		if ($current['病室'] == $room_obj &&
		    $current['病床'] == $bed) {
			$__dbg .= "<br /> $pt already at $room $bed";
			return; /* already there */
		}
		$__dbg .= "<br /> $pt at " .
			htmlspecialchars($current['病室']) . " " .
			htmlspecialchars($current['病床']);
		floor_ed_vacate($db, $pt, $ymd, &$pcr);
		/* Double check */
		$current = floor_ed_locate($db, $pt, $ymd);
		if ($current) {
			$__dbg .= "<br />OOPS $pt $room $bed $ymd";
		}
	}

	/* At this point, we know that the room's occupants may change */
	$pcr[$room_obj] = 1;

	/* Who occupies that bed on that day? */
	$current = floor_ed_locate_occupant($db, $room_obj, $bed, $ymd);
	if ($current)
		floor_ed_vacate($db, $current['ObjectID'], $ymd, &$pcr);

	/* When will the bed used first after that day? */
	$next = floor_ed_next_use($db, $room_obj, $bed, $ymd);

	$pt_q = mx_db_sql_quote($pt);
	$discharge = 'SELECT "退院予定日" FROM "患者台帳"
WHERE "ObjectID" = '.$pt_q;
	$discharge = mx_db_fetch_single($db, $discharge);
	if (!$discharge)
		die("OOPS? discharge $pt_q");
	$pt_end = $discharge['退院予定日'];

	/* When will the patient have a place to stay after $ymd? */
	$next_assigned = floor_ed_next_assigned($db, $pt, $ymd);
	if ($next_assigned) {
		$pt_end = $next_assigned['占有開始'];
	}

	/* At this point, $pt_end is the end of the occupation period.
	 * There are a few cases:
	 * (0) If that bed is occupied up to that date by the patient,
	 *     coalesce_use will extend that use. 
	 * (1) If the bed is unused after ymd, give it to the patient
	 *     until $pt_end by adding a new entry between $ymd..$pt_end.
	 * (2) If the bed is used after ymd by somebody else, give it
	 *     to the patient until $pt_end.
	 * (3) If the bed is used by the same patient, extend its use
	 *     till $discharge date.
	 */

	$__dbg .= "<br />pt-end is $pt_end, next[o] is " . $next['占有開始'];
	if (!$next) {
		if (floor_ed_coalesce_use($db, $pt, $room_obj, $bed,
					  $ymd, $pt_end))
			return;
		$ymd_q = mx_db_sql_quote($ymd);
		$room_q = mx_db_sql_quote($room_obj);
		$bed_q = mx_db_sql_quote($bed);
		$t1_q = mx_db_sql_quote($pt_end);
		$stmt = 'INSERT INTO "病床管理"
("病室", "病床", "占有開始", "占有終了", "占有者")
VALUES ('.$room_q.', '.$bed_q.', '.$ymd_q.', '.$t1_q.', '.$pt_q.')';
		$__dbg .= "<br />" . htmlspecialchars($stmt);
		pg_query($stmt);
		return;
	}
	else if ($next['占有者'] != $pt) {
		if (floor_ed_coalesce_use($db, $pt, $room_obj, $bed,
					  $ymd, $pt_end))
			return;
		$ymd_q = mx_db_sql_quote($ymd);
		$room_q = mx_db_sql_quote($room_obj);
		$bed_q = mx_db_sql_quote($bed);
		$t1_q = mx_db_sql_quote($pt_end);
		$t2_q = mx_db_sql_quote($next['占有開始']);
		$smaller = ($pt_end < $next['占有開始']) ? $t1_q : $t2_q;

		$stmt = 'INSERT INTO "病床管理"
("病室", "病床", "占有開始", "占有終了", "占有者")
VALUES ('.$room_q.', '.$bed_q.', '.$ymd_q.', '.$smaller.', '.$pt_q.')';
		pg_query($stmt);
		return;
	}
	else if ($next['占有開始'] == $pt_end) {
		$t2_q = mx_db_sql_quote($next['占有開始']);
		$stmt = 'DELETE FROM "病床管理"
WHERE "占有者" = '.$pt_q.' AND  "占有開始" = '.$t2_q;
		pg_query($stmt);
		$__dbg .= "<br />$stmt";
		return floor_ed_occupy($db, $pt, $room, $bed, $ymd, &$pcr);
	}
	else {
		$ymd_q = mx_db_sql_quote($ymd);
		$t2_q = mx_db_sql_quote($next['占有開始']);
		$stmt = 'UPDATE  "病床管理" SET "占有開始" = '.$ymd_q.'
WHERE "占有者" = '.$pt_q.' AND  "占有開始" = '.$t2_q;
		pg_query($stmt);
		return;
	}
}

/* Compute and update the floor allocation */
function floor_ed_occupies(&$db, $pt, $slot, $ymd, &$pcr)
{
	if (substr($slot, 0, 5) == 'wait.') {
		/* Kick out from the current slot */
		floor_ed_vacate(&$db, $pt, $ymd, &$pcr);
	}
	else {
		$pos = strpos($slot, '.');
		if ($pos === FALSE)
			return; /* OOPS */
		$room = substr($slot, 0, $pos);
		$bed = substr($slot, $pos+1);
		floor_ed_occupy(&$db, $pt, $room, $bed, $ymd, &$pcr);
	}
}

/*
 * Adjust the room-patient table if the change involves "today"
 */
function update_room_patient(&$db, $room, $today)
{
	global $u;
	global $__dbg;

	$stmt = <<<SQL
SELECT R."病室名", R."定数" FROM "病室一覧表" AS R WHERE "ObjectID" = $room
SQL;
	$it = mx_db_fetch_single($db, $stmt);
	$c = $it['定数'];
	$n = $it['病室名'];
	$occupant = array();
	for ($bed = 1; $bed <= $c; $bed++) {
		$o = floor_ed_locate_occupant($db, $room, $bed, "$today");
		if ($o && $o['ObjectID']) {
			$__dbg .= "Found " . $o['ObjectId'];
			$occupant[$o['ObjectID']] = 1;
		} else {
			$__dbg .= "Not Found";
		}
	}
	$stmt = <<<SQL
SELECT RD."患者", RP."ObjectID", RP."日付", RP."CreatedBy"
FROM "病室患者表" RP
LEFT JOIN "病室患者データ" RD ON RD."病室患者表" = RP."ObjectID"
WHERE RP."病室" = $room AND RP."Superseded" IS NULL
SQL;
	$sth = pg_query($db, $stmt);
	$current = pg_fetch_all($sth);
	if (!$current) {
		$current = array();
		$object_id = NULL;
		$date = NULL;
		$old_u = NULL;
	} else {
		$object_id = $current[0]['ObjectID'];
		$date = mx_db_sql_quote($current[0]['日付']);
		$old_u = mx_db_sql_quote($current[0]['CreatedBy']);
		if (is_null($current[0]['患者']))
			$current = array();
	}
	$differs = 0;
	if (count($current) == count($occupant)) {
		foreach ($current as $o) {
			$o = $o['患者'];
			if (!array_key_exists($o['患者'], $occupant)) {
				$differs = 1;
				break;
			}
		}
	} else {
		$differs = 1;
	}
	$__dbg .= "<br />STMT $stmt";
	$__dbg .= "<br />OOO $object_id";
	$__dbg .= "<br />DDD $date";
	$__dbg .= "<br />UUU $old_u";

	if (!$differs && !is_null($object_id))
		return;

	/*
	 * We need to update the room-patient table
	 */
	$id = mx_db_allocate_unused_id($db, "病室患者表_ID_seq");
	$new_u = mx_db_sql_quote($u);

	if ($object_id) {
		$stmt = <<<SQL
INSERT INTO "病室患者表"
("ID", "ObjectID", "Superseded", "CreatedBy", "病室", "日付")
VALUES ($object_id, $id, now(), $old_u, $room, $date)
SQL;
		$__dbg .= "<br />STMT $stmt";
		pg_query($db, $stmt);
		$stmt = <<<SQL
UPDATE "病室患者データ"
SET "病室患者表" = $id WHERE "病室患者表" = $object_id
SQL;
		$__dbg .= "<br />STMT $stmt";
		pg_query($db, $stmt);

		$stmt = <<<SQL
UPDATE "病室患者表" SET "CreatedBy" = $new_u, "日付" = '$today'
WHERE "ObjectID" = $object_id;
SQL;
		$__dbg .= "<br />STMT $stmt";
		pg_query($db, $stmt);
	} else {
		$object_id = $id;
		$stmt = <<<SQL
INSERT INTO "病室患者表"
("ID", "ObjectID", "Superseded", "CreatedBy", "病室", "日付")
VALUES ($object_id, $object_id, NULL, $new_u, $room, '$today')
SQL;
		$__dbg .= "<br />STMT $stmt";
		pg_query($db, $stmt);
	}

	foreach ($occupant as $o => $junk) {
		$stmt = <<<SQL
INSERT INTO "病室患者データ"
("病室患者表", "患者")
VALUES ($object_id, $o)
SQL;
		$__dbg .= "<br />STMT $stmt";
		pg_query($db, $stmt);
	}
}

function update_room_patients($db, $pcr, $today)
{
	foreach ($pcr as $room => $one)
		update_room_patient(&$db, $room, $today);
}

/* The callers of this do not have to change, but this function itself
 * needs to be majorly rewritten for the world-model switch.
 *
 * This takes an action "$update" from the browser that says:
 * 	"pickit=<patientID>"
 * and responsible for returning a $data array, whose contents
 * are:
 * 
 * 	bed-ident => patient-id
 *	active	  => patient-id
 * 
 * bed-ident are typically of form 'room-name.number' (e.g. "901.1"),
 * or 'wait.number' (e.g. "wait.1", "wait.2", ...).
 * The HTML template should use:
 * 	<tag id="bed-ident"><?php o(bed-ident) ?></tag>
 * to mark the location on the map.  For wait area, say: 
 * 	<tag id="wait"><?php o(wait) ?></tag>
 * because the template should not care how many are waiting.
 *
 * patient-id 0 means unoccupied, and nobody active.
 *
 * It not just updates the session but updates the database.
 */
function update_session(&$db, $update, $session, $session_data, $theday,
			$today, $read_only) {
	global $__ses_table, $__dbg;

	$ymd = sprintf("%s 00:00:00", $theday);
	$__dbg = '';
	$d = $session_data;
	$data = $d['floor-data'];
	$pi = $d['patient-info'];
	$es = $d['empty-schedule'];

	if (!$update)
		return $d;

	if (substr($update, 0, 7) == 'pickit=') {
		$update = substr($update, 7);

		$pcr = array(); /* potentially_changed_room */

		/*
		 * Look for which slot the active thing is in
		 * and swap them.  Make the other active.
		 */
		$active_slot = $data['active'];
		if ($active_slot)
			$active = $data[$active_slot];
		else {
			$active = 0;
			$active_slot = NULL;
		}

		$pt = $data[$update];
		if (!$active || $read_only) {
			/* Nothing was active, so just activate the
			 * chosen, if it is not empty.
			 *
			 * In a read-only application, we just
			 * deactivate the active one, and activate
			 * the newly chosen one, if it is not empty.
			 */
			if ($pt) {
				$active = $pt;
				$active_slot = $update;
			}
			else {
				$active = 0;
				$active_slot = NULL;
			}
		}
		else if ($update == $active_slot) {
			/* Deactivate */
			$active = 0;
			$active_slot = NULL;
		}
		else if (!$pt) {
			/* Moving active one to an empty place */
			floor_ed_occupies($db, $active, $update, $ymd, &$pcr);
			$data[$active_slot] = NULL;
			$data[$update] = $active;
			$active = 0;
			$active_slot = NULL;
		}
		else {
			$__dbg .= "<br />?? $update vs $active_slot";
			/* Moving active one to an occupied place.
			 * Swap them and make the bumped one active.
			 */
			floor_ed_occupies($db, $active, $update, $ymd, &$pcr);
			$data[$update] = $active;
			floor_ed_occupies($db, $pt, $active_slot, $ymd, &$pcr);
			$data[$active_slot] = $pt;
			$active = $pt;
		}

		/* Clean it up */
		$newdata = array();
		foreach ($data as $slot => $junk) {
			if (substr($slot, 0, 5) == 'wait.')
				continue;
			if ($slot == 'active')
				continue;
			$newdata[$slot] = $junk;
		}
		for ($j = $i = 1;
		     array_key_exists("wait.$i", $data);
		     $i++) {
			if ($data["wait.$i"]) {
				$newdata["wait.$j"] = $data["wait.$i"];
				$j++;
			}
		}
		if (!$active_slot ||
		    substr($active_slot, 0, 5) == 'wait.' ||
		    $read_only)
			;
		else
			$newdata["wait.$j"] = 0;
		if ($active_slot)
			$newdata['active'] = $active_slot;

		$data = $newdata;
		$__dbg .= "<br />Active is $active_slot";

		if ($pcr) {
			$__dbg .= "<br />Potentially these rooms would change";
			$__dbg .= implode(",", array_keys($pcr));
			update_room_patients($db, $pcr, $today);
		}

	}

	$d = array('floor-data' => $data,
		   'patient-info' => $pi,
		   'empty-schedule' => $es);
	$into = serialize($d);
	$stmt = ("UPDATE \"$__ses_table\" SET " .
		 "data = " . mx_db_sql_quote($into) .
		 "\nWHERE \"ID\" = " . $session);
	pg_query($db, $stmt);
	return $d;
}

/* This stuffs the HTML representation for each slot in the $ddata
 * array for presentation, out of given $data.
 */
function format_ddata($db, $d, $opts) {
	global $__lib_u_manage_floor_ed_coloring_ix_to_label;
	$ix_to_label = $__lib_u_manage_floor_ed_coloring_ix_to_label;
	$coloring = $opts['coloring'];
	$color_ward_map = array();

	# If you want to highlight soon-to-be-discharged pt, based on
	# the calendar date, use "theday", otherwise use "today".
	$base_time = mx_datetime_to_unixtime($opts['theday'] . " 00:00");

	$ddata = array();
	$occupies = array();
	$data = $d['floor-data'];
	$empty_schedule = $d['empty-schedule'];
	$pi = $d['patient-info'];
	$active = $data['active'];
	$active_pt = NULL;
	$active_pt_ptid = NULL;
	$soon_discharge = array();
//print "PI===";
//print_r($pi);

	foreach ($data as $slot => $junk) {
		$aa = '';
		$class = array();
		$is_wait = (substr($slot, 0, 5) == 'wait.');
		$aa .= " onclick=\"pickit('$slot')\"";
		$dst = $is_wait ? 'wait' : $slot;
		if ($junk) {
			$aa .= ("onmouseover=\"tip('tip-$junk')\" " .
				"onmouseout=\"untip()\"");
			$ji = $pi[$junk];
			if ($slot == $active) {
				$class[] = 'active';
				$active_pt = $junk;
				$active_pt_ptid = $ji['患者ID'];
			}

			$j = $ji['希望病棟'];
			$pn = $ji['姓名'];

			if ($ji['入外区分'] == 'W') {
				$class[] = 'pending';
			}
			$dis_time = mx_datetime_to_unixtime($ji['退院予定日'] .
							    " 00:00");
			if ($dis_time != -1 && $base_time != -1 &&
			    $base_time <= $dis_time &&
			    $dis_time <= $base_time + 86400*7) {
				$class[] = 'soon';
				$a_name = $pn;
				$a_date = substr($ji['退院予定日'], 5);
				if ($ji['病室名'])
					$a_name = "$pn (" . $ji['病室名'] . ")";
				$soon_discharge[] = "$a_date $a_name";
			}

			if ($dst == 'wait' && $j != '') {
				$pn = ($pn . "($j)");
			}
			$pn = htmlspecialchars($pn);
			$occupies[$junk] = $slot;
		}
		else {
			if (array_key_exists($slot, $empty_schedule)) {
				$aa .= ("onmouseover=\"tip('tip-b-$slot')\" " .
					"onmouseout=\"untip()\"");
				$pn = $empty_schedule[$slot]['begin'];
			}
			else
				$pn = "(空き)";
		}

		if (count($class)) {
			$aa .= ' class="' . implode(' ', $class) . '"';
		}
		$it = ("<a$aa>$pn</a>");

		$around = '';
		if ($junk && 0 <= $coloring) {
			switch ($ix_to_label[$coloring]) {
			case 'ＦＩＭ総合':
				$around = floor_ed_mark_fim($pi[$junk]);
				break;
			case 'ＦＩＭ移動':
				$around = floor_ed_mark_fim_mobil($pi[$junk]);
				break;
			case 'ＦＩＭトイレ':
				$around = floor_ed_mark_fim_toi($pi[$junk]);
				break;
			case 'ＦＩＭ理解':
				$around = floor_ed_mark_fim_und($pi[$junk]);
				break;
			case '回復':
				$around = floor_ed_mark_reha_class($pi[$junk]);
//0820-2012 add am-pm number of yoyaku
//print "ABC=";
//print_r($pi[$junk]);

 if ($pi[$junk]['病室'] <49  ) {
					$around = 'paint-blue';
				}
				else  
					$around = 'paint-red';
				 
				break;
			case '性別':
				if ($pi[$junk]['性別'] == 'M') {
					$around = 'paint-blue';
				}
				else if ($pi[$junk]['性別'] == 'F') {
					$around = 'paint-red';
				}
				break;
			case '在院日数':
				$around = floor_ed_mark_stay($pi[$junk]);
				break;
			case '感染症':
				if ($pi[$junk]['感染症'] != '') {
					$around = 'paint-red';
				}
				break;
			case '医学的不安定':
				$toi = $pi[$junk]['医学的不安定'];
				if ($toi == 'U') {
					$around = 'paint-red';
				}
				else if ($toi == 'W') {
					$around = 'paint-yellow';
				}
				break;
			default:
				break;
			}
			$ward = $pi[$junk]['病棟名'];
			if (is_null($ward))
				$ward = '';
			if (!array_key_exists($ward, $color_ward_map))
				$color_ward_map[$ward] = array();
			if (!array_key_exists($around,$color_ward_map[$ward]))
				$color_ward_map[$ward][$around] = 0;
			$color_ward_map[$ward][$around]++;
		}

		if ($around) {
			$it = ("<span class=\"padded $around\">$it</span>");
		}

		if (!array_key_exists($dst, $ddata)) {
			$ddata[$dst] = '';
		} else {
			$ddata[$dst] .= '<br />';
		}
		$ddata[$dst] .= $it;
	}
	if (0) {
		print "<!-- OCC\n";
		var_dump($occupies);
		print "-->";
	}
	$legend = array();
	switch ($ix_to_label[$coloring]) {
	case 'ＦＩＭ総合':
		$legend = array('paint-blue' => '合計点改善 5 点以上',
				'paint-green' => '合計点改善 3 点以上',
				'paint-yellow' => '合計点悪化 3 点以下',
				'paint-pink' => '合計点悪化 5 点以下',
				'paint-red' => '合計点悪化 5 点以上');
		break;
	case 'ＦＩＭ移動':
		$legend = array('paint-blue' => '歩行',
				'paint-yellow' => '車椅子（5 点以上)',
				'paint-red' => '車椅子（5 点未満)');
		break;
	case 'ＦＩＭトイレ':
		$legend = array('paint-blue' => 'トイレ（6 点以上）',
				'paint-yellow' => 'トイレ（4 点以上)',
				'paint-red' => 'トイレ（4 点未満)');
		break;
	case 'ＦＩＭ理解':
		$legend = array('paint-blue' => '理解（6 点以上）',
				'paint-yellow' => '理解（4 点以上)',
				'paint-red' => '理解（4 点未満)');
		break;
	case '回復':
//0813-2012
		$legend = array('paint-blue' => '回復 ',
				'paint-red' => '回復');
		break;
	case '性別':
		$legend = array('paint-blue' => '男',
				'paint-red' => '女');
		break;
	case '在院日数':
		$legend = array('paint-red' => '在院 150 日以上',
				'paint-yellow' => '在院 90 日以上',
				'paint-blue' => '在院 60 日以上',
				'paint-green' => '在院 60 日未満');
		break;
	case '感染症':
		$legend = array('paint-red' => '感染症あり');
		break;
	case '医学的不安定':
		$legend = array('paint-red' => '医学的不安定',
				'paint-yellow' => '要注意');
		break;
	default:
		break;
	}
	$ddata['legend'] = $legend;
	$ddata['legend-stat'] = $color_ward_map;
	$ddata['occupies'] = $occupies;
	$ddata['schedule'] = '';
	$ddata['active-pt'] = $active_pt;
	$ddata['active-pt-ptid'] = $active_pt_ptid;
	if ($active_pt) {
		/* For the active patient, show schedule table */
		$name = $pi[$active_pt]['姓名'];
		$ent = $pi[$active_pt]['入院日'] . " 00:00:00";
		$dis = $pi[$active_pt]['退院予定日'] . " 00:00:00";
		$stmt = '
SELECT W."病棟名", R."病室名", BC."病床", BC."占有開始", BC."占有終了"
FROM
  "病床管理" AS BC
JOIN
  "病室一覧表" AS R ON BC."病室" = R."ObjectID"
JOIN
 "病棟一覧表" AS W ON R."病棟" = W."ObjectID"
JOIN
  "患者台帳" AS P
    ON (BC."占有者" = P."ObjectID")
WHERE BC."占有者" = '.mx_db_sql_quote($active_pt).'
ORDER BY BC."占有開始"';
		$sch2data = array();
		$result = pg_fetch_all(pg_query($db, $stmt));
		if ($result) {
			foreach ($result as $row) {
				$cnt = count($sch2data);
				$beg = $row['占有開始'];
				if ($cnt == 0)
					$last = $ent;
				else
					$last = $sch2data[$cnt-1]['占有終了'];
				if ($last < $beg) {
					$sch2data[] =
						array('占有開始' => $last,
						      '占有終了' => $beg);
				}
				$sch2data[] = $row;
			}
		}
		$cnt = count($sch2data);
		$beg = $row['占有開始'];
		if ($cnt == 0)
			$last = $ent;
		else
			$last = $sch2data[$cnt-1]['占有終了'];
		if ($last < $dis) {
			$sch2data[] =
				array('占有開始' => $last,
				      '占有終了' => $dis);
		}

		$sch0 = ('<table width="480px">'.
			 '<tr>'.
			 '<th colspan="2" align="left">患者氏名</td>'.
			 '<th colspan="3" align="left">'.
			 htmlspecialchars($name).
			 '</th>'.
			 '</tr>');
		$sch1 = ('<tr>'.
			 '<th width="120px">病棟</th>'.
			 '<th width="60px">病室</th>'.
			 '<th width="60px">病床</th>'.
			 '<th width="120px">自</th>'.
			 '<th width="120px">至</th>'.
			 '</tr>');
		foreach ($sch2data as $row) {
			$fm = $row['占有開始'];
			$to = $row['占有終了'];
			if (substr($fm, 11) == '00:00:00')
				$fm = substr($fm, 0, 10);
			if (substr($to, 11) == '00:00:00')
				$to = substr($to, 0, 10);
			if (array_key_exists('病床', $row))
				$sch2 .= ('<tr><td>'.
					  htmlspecialchars($row['病棟名']).
					  '</td><td>'.
					  htmlspecialchars($row['病室名']).
					  '</td><td>'.
					  htmlspecialchars($row['病床']).
					  '</td>');
			else
				$sch2 .= ('<tr><td colspan="3">'.
					  '<span class="paint-red">割当なし'.
					  '</span></td>');
			$sch2 .= ('<td>'.
				  htmlspecialchars($fm).'</td>'.
				  '<td>'.
//0722-2012
				  htmlspecialchars($to).'</td>'.
				  '</tr>');

					  
		}
		$ddata['schedule'] = $sch0 . $sch1 . $sch2 . '</table>';
	}
	$ddata['soon-discharge'] = $soon_discharge;
	return $ddata;
}

function check_session($session, &$db) {
	global $__ses_table, $__dbg;
	if ($session) {
		$data = mx_db_fetch_single($db, 'SELECT "ID" FROM "' .
					   $__ses_table .
					   '" WHERE "ID" = ' . $session);
		if (!$data)
			return NULL;
		return $session;
	}
	return NULL;
}

function ask_for_confirmation($session_data) {
	global $_mx_resource_dir;
	print '<html>
<meta http-equiv="content-type" content="text/html; charset=euc-jp">
<link rel="stylesheet" href="/' . $_mx_resource_dir . '/mxstyle.css" />
<script language="JavaScript"
 src="/' . $_mx_resource_dir . '/mx.js"></script>
</head>
<body>
過去の記録を変更しようとしていますが間違いないですね？
<form method="POST">';
	if (1) {
		print "<!--\n";
		var_dump($_REQUEST);
		print "active = " . $session_data['floor-data']['active'];
		print "-->\n";
	}
	foreach ($_POST as $k => $v) {
		mx_formi_hidden($k, $v);
	}
	mx_formi_submit('ok_to_backdate', 1,
		"<img src=\"/$_mx_resource_dir/images/continue_button.png\">");
	mx_formi_submit('ok_to_backdate', 0,
		"<img src=\"/$_mx_resource_dir/images/abort_button.png\">");
	print "</form></body></html>\n";
}

function main($read_only) {
	global $__this_output_data, $__calendar;
	global $__lib_u_manage_floor_ed_coloring_label_to_ix;
	global $__lib_u_manage_floor_ed_coloring_button_breaks;
	global $__ses_table, $__dbg;
	global $auth;
	global $__lib_u_manage_floor_ed_floors;

	$db = mx_db_connect();

	/* Check if there is an existing session */
	$session = check_session($_REQUEST['session'], &$db);

	/* Handle non 'update' requests.  E.g. 'calendar' */
	$__calendar = new floor_ed_calendar('calendar', array());
	$ymd = $__calendar->chosen();
	$today = strftime('%Y-%m-%d', time());
	if (!$ymd) {
		$__calendar->reset(NULL, NULL, $today);
		$ymd = $__calendar->chosen();
	}
	$theday = sprintf("%04d-%02d-%02d", $ymd[0], $ymd[1], $ymd[2]);
	$in_the_past = ($theday < $today);
	if ($__calendar->changed()) {
		$session = NULL;
	}
	if (array_key_exists('floor', $_REQUEST) &&
	    substr($_REQUEST['floor'], 0, 7) == 'pickit=') {
		$floor = substr($_REQUEST['floor'], 7);
	}
	else {
		$user_info = mx_prepare_userinfo($auth);
		$section = $user_info["部署名"];
		$floor = NULL;
		foreach ($__lib_u_manage_floor_ed_floors as $ig => $fcfg) {
			if (is_null($floor) ||
			    ($section == $fcfg['section']))
			    $floor = $ig;
		}
	}
	if (array_key_exists('color', $_REQUEST) &&
	    substr($_REQUEST['color'], 0, 7) == 'pickit=') {
		$coloring = substr($_REQUEST['color'], 7);
	}
	else {
		$coloring = -1;
	}

	/* Get data out of the database as needed */
	if (!$session) {
 
		$session = initialize_session(&$db, $theday, $today);
	}
 
	/* If we are looking at historical data, and if we already have
	 * somebody we picked, picking somewhere else would swap them,
	 * but we would need a confirmation before doing so.
	 *
	 * If $_REQUEST['update'] is pickit=<slot>, and
	 * the patient in that slot is not already active,
	 * and if $theday is earlier tan $today,
	 * and if we do not have the OK to proceed, ask for that.
	 */
	$session_data = mx_db_fetch_single($db, 'SELECT data FROM "' .
					   $__ses_table .
					   '" WHERE "ID" = ' . $session);
	$session_data = unserialize($session_data['data']);

	if (array_key_exists('ok_to_backdate', $_REQUEST) &&
	    !$_REQUEST['ok_to_backdate'])
		$_REQUEST['update'] = 'unset';

	if (! (array_key_exists('ok_to_backdate', $_REQUEST) &&
	       $_REQUEST['ok_to_backdate']) &&
	    $in_the_past && !$read_only &&
	    (substr($_REQUEST['update'], 0, 7) == 'pickit=')) {
		$update_slot = substr($_REQUEST['update'], 7);
		$active_slot = $session_data['floor-data']['active'];
		if ($active_slot != '' && $update_slot != $active_slot) {
			return ask_for_confirmation($session_data);
		}
	}
	$data = update_session(&$db, $_REQUEST['update'], $session,
			       $session_data, $theday, $today, $read_only);
	$capcolor = $__lib_u_manage_floor_ed_floors[$floor]['caption_color'];
	$floorcolor = $__lib_u_manage_floor_ed_floors[$floor]['floor_color'];

	$opts = array('capcolor' => $capcolor,
		      'floorcolor' => $floorcolor,
		      'coloring' => $coloring,
		      'floor' => $floor,
		      'today' => $today,
		      'theday' => $theday);
	$__this_output_data = format_ddata(&$db, $data, $opts);

	draw_header($opts, $data, $__this_output_data);
	print '<input type="hidden" name="update" id="update" value="unset">';
	print '<input type="hidden" name="floor" id="floor" value="pickit=';
	print $floor;
	print '">';
	print '<input type="hidden" name="color" id="color" value="pickit=';
	print $coloring;
	print '">';
	print '<input type="hidden" name="session" value="';
	print $session;
	print "\">\n";

	print "<strong>";
	print htmlspecialchars($auth[1]);
	print "</strong>\n";
	mx_draw_ppa_applist($__this_output_data['active-pt-ptid']);
	print "<hr />\n";
//0925-2014
	print "<table><tr><td><strong>FLOOR</strong></td><td>";
	foreach ($__lib_u_manage_floor_ed_floors as $ident => $floorcfg) {
		$label = $floorcfg['label'];
		if ($floor == $ident) {
			print "&nbsp;<img src=\"/images/floor";
		print $ident;
			print "_s.png\" align=\"absbottom\">";
		}
		else {
			print "&nbsp;<a onclick=\"floorit('";
			print $ident;
			print"')\">";
			print "<img src=\"/images/floor";
			print $ident;
			print ".png\" align=\"absbottom\">";
			print "</a>\n";
		}
	}
	print "</td></tr>\n";

	print "<tr><td><strong>色分け</strong></td><td>";
	foreach ($__lib_u_manage_floor_ed_coloring_label_to_ix
		 as $label => $ident) {
		if (array_key_exists
		    ($label,
		     $__lib_u_manage_floor_ed_coloring_button_breaks))
			print "</td></tr><tr><td>&nbsp;</td><td>";
		if ($coloring == $ident) {
			print "&nbsp;<a onclick=\"colorit('-1')\">";
			print "&nbsp;<img src=\"/images/coloring";
			print $ident;
			print "_s.png\" align=\"absbottom\">";
			print "</a>\n";
		}
		else {
			print "&nbsp;<a onclick=\"colorit('";
			print $ident;
			print"')\">";
			print "<img src=\"/images/coloring";
			print $ident;
			print ".png\" align=\"absbottom\">";
			print "</a>\n";
		}
	}
	print "</td></tr>";
	print "</table>\n";

	$draw_func = "draw_floor_" . $floor;
	$draw_func();
	draw_footer($session, $data);
}
?>
