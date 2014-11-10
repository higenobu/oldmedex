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
<form><input type="button" value="����" onClick="printPopup()">
<input type="button" value="���̤��Ĥ���" onClick="window.parent.close()">
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
	return implode("��", explode("|", $value));
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
		$o['�±�̾'] = $h;
		$o['�»���'] = htmlspecialchars($date);
		$o['����̾'] = htmlspecialchars($d['��'] . $d['̾']);
		$o['����ID'] = htmlspecialchars($d['����ID']);

		$o['����'] = htmlspecialchars(dow_to_text($d['����']));

		$o['������'] = htmlspecialchars($d['������������']);
		$o['��λ��'] = htmlspecialchars($d['��������λ��']);
		$o['������'] = htmlspecialchars($d['������']);
		$o['������'] =
			htmlspecialchars(concat_to_text($d['������']));
		$o['��շ�'] = htmlspecialchars($d['��շ�']);
		$o['RH'] = htmlspecialchars($d['RH']);
		$o['�֥�åɥ�������'] =
			htmlspecialchars(concat_to_text($d['�֥�åɥ�������']));
		$o['Ʃ�ϻ���'] = htmlspecialchars($d['HD����']);
		$o['��ή��'] = htmlspecialchars($d['QB��']);
		$o['��վ���ˡ'] = htmlspecialchars($d['Ʃ����ˡ']);
		$o['Ʃ�ϱ�'] = htmlspecialchars($d['Ʃ�ϱ�']);
		$o['���ŸǺ�'] = htmlspecialchars($d['���ŸǺ�']);
		$o['���'] = htmlspecialchars($d["���������"]);
		$o['������'] = htmlspecialchars($d['��³��']);
		$o['�ɥ饤��������'] = htmlspecialchars($d['�ɥ饤��������']);
		$o['�������饤����'] = htmlspecialchars($d['�������饤����']);
		$o['����'] = htmlspecialchars($d['����']);

		$o['�ִ���'] = '';
		$o['�ִ���'] = '';

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