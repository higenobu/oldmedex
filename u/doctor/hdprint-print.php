<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/hdorder.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/hdprint-form.php';

function main_frameset() {
	$args = array();
	foreach ($_REQUEST as $k => $v)
		$args[] = "$k=" . htmlspecialchars($v);
	$args = implode('&amp;', $args);
	print "<frameset rows=\"60,*\" noresize border=\"0\">\n";
	print "<frame src=\"hdprint-print.php?what=top\" ".
		"name=\"top_frame\" scrolling=\"no\">\n";
	print "<frame src=\"hdprint-print.php?what=bottom&amp;$args\" ".
		"name=\"bottom_frame\">\n";
	print "</frameset>\n";
}

function top_frame() {
	mx_html_head(NULL, 'do_not_close_head');
?>
<script language="javascript" type="text/javascript">
         <!--
		function printPopup() {
			parent.frames[1].focus();
			parent.frames[1].print();
		}
         -->
         </script>
<style type="text/css">
	body {
		background-image: none;
		background-color: #fff;
	}
</style>
</head><body><center>
<form><input type="button" value="°õºþ" onClick="printPopup()">
<input type="button" value="²èÌÌ¤òÊÄ¤¸¤ë" onClick="window.parent.close()">
</form></center></body></html>
<?php
}

function dow_to_text($value)
{
	global $_mx_daysoftheweek;
	$txt = '';
	$dow = $_mx_daysoftheweek;
	for ($i = 0; $i < count($dow); $i++) {
		if (substr($value, $i, 1) != 'Y')
			continue;
		if ($txt != '')
			$txt = $txt . ' ';
		$txt = $txt . $dow[$i];
	}
	return $txt;
}

function concat_to_text($value)
{
	return implode("¡¦", explode("|", $value));
}

function bottom_frame()
{
	$idata = mx_get_install_data();
	$h = htmlspecialchars($idata['HOSPITAL_NAME']);

	print '<html><head><meta http-equiv="content-type"
content="text/html; charset=euc-jp">';
	hdprint_emit_style();
	print '
<style type="text/css">
body {
	background-image: none;
	background-color: #fff;
}
table.listofstuff {
    font-size: 10pt;
    border: solid 1px;
    border-collapse: collapse;
    text-align: left;
    vertical-align: top;
}
table.listofstuff * th {
    border: solid 1px;
    text-align: left;
    vertical-align: top;
}
table.listofstuff tr.o td {
    background-color: #ffc;
    border: solid 1px;
    text-align: left;
    vertical-align: top;
    padding: 10px;
}
table.listofstuff tr.e td {
    background-color: #fdc;
    border: solid 1px;
    text-align: left;
    vertical-align: top;
    padding: 10px;
}
</style>
</head><body>
';
	if (array_key_exists('HDOrders', $_REQUEST))
		$l = hdorder_list_orders_by_oid($_REQUEST['HDOrders']);
	else if (array_key_exists('HDExecDate', $_REQUEST))
		$l = hdorder_list_orders($_REQUEST['HDExecDate']);
	else
		$l = array();

	$date = $_REQUEST['HDExecDate'];
	$cnt = count($l);
	for ($i = 0; $i < $cnt; $i++) {
		$d = $l[$i];
		$o = array();
		$o['ÉÂ±¡Ì¾'] = $h;
		$o['¼Â»ÜÆü'] = htmlspecialchars($date);
		$o['´µ¼ÔÌ¾'] = htmlspecialchars($d['À«'] . $d['Ì¾']);
		$o['´µ¼ÔID'] = htmlspecialchars($d['´µ¼ÔID']);

		$o['ÍËÆü'] = htmlspecialchars(dow_to_text($d['ÍËÆü']));

		$o['³«»ÏÆü'] = htmlspecialchars($d['¥ª¡¼¥À³«»ÏÆü']);
		$o['½ªÎ»Æü'] = htmlspecialchars($d['¥ª¡¼¥À½ªÎ»Æü']);
		$o['»þ´ÖÂÓ'] = htmlspecialchars($d['»þ´ÖÂÓ']);
		$o['´¶À÷¾É'] =
			htmlspecialchars(concat_to_text($d['´¶À÷¾É']));
		$o['·ì±Õ·¿'] = htmlspecialchars($d['·ì±Õ·¿']);
		$o['RH'] = htmlspecialchars($d['RH']);
		$o['¥Ö¥é¥Ã¥É¥¢¥¯¥»¥¹'] =
			htmlspecialchars(concat_to_text($d['¥Ö¥é¥Ã¥É¥¢¥¯¥»¥¹']));
		$o['Æ©ÀÏ»þ´Ö'] = htmlspecialchars($d['HD»þ´Ö']);
		$o['·ìÎ®ÎÌ'] = htmlspecialchars($d['QBÎÌ']);
		$o['·ì±Õ¾ô²½Ë¡'] = htmlspecialchars($d['Æ©ÀÏÊýË¡']);
		$o['Æ©ÀÏ±Õ'] = htmlspecialchars($d['Æ©ÀÏ±Õ']);
		$o['¹³¶Å¸ÇºÞ'] = htmlspecialchars($d['¹³¶Å¸ÇºÞ']);
		$o['½é²ó'] = htmlspecialchars($d["½é²óÃíÆþÎÌ"]);
		$o['»þ´ÖÅö'] = htmlspecialchars($d['»ýÂ³ÎÌ']);
		$o['¥É¥é¥¤¥¦¥§¥¤¥È'] = htmlspecialchars($d['¥É¥é¥¤¥¦¥§¥¤¥È']);
		$o['¥À¥¤¥¢¥é¥¤¥¶¡¼'] = htmlspecialchars($d['¥À¥¤¥¢¥é¥¤¥¶¡¼']);
		$o['È÷¹Í'] = htmlspecialchars($d['È÷¹Í']);

		$o['ÃÖ´¹±Õ'] = '';
		$o['ÃÖ´¹ÎÌ'] = '';

		print "<div style='page-break-after:always'>";
		hdprint_emit_table($o);
		print "</div>\n";
	}

	print "<br /><br /><br />\n";

	hdorder_show_table_order($l, NULL, NULL, 'omit');

	print "</body></html>\n";
}

if (array_key_exists('what', $_REQUEST)) {
	switch ($_REQUEST['what']) {
	case 'top':
		top_frame();
		break;
	case 'bottom':
		bottom_frame();
		break;
	}
}
else {
	main_frameset();
}

?>