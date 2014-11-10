<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/appt.php';

function main_frameset() {
	$args = array();
	foreach ($_REQUEST as $k => $v)
		$args[] = "$k=" . htmlspecialchars($v);
	$args = implode('&amp;', $args);
	print "<frameset rows=\"60,*\" noresize border=\"0\">\n";
	print "<frame src=\"print.php?what=top\" ".
		"name=\"top_frame\" scrolling=\"no\">\n";
	print "<frame src=\"print.php?what=bottom&amp;$args\" ".
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
<form><input type="button" value="印刷" onClick="printPopup()">
<input type="button" value="画面を閉じる" onClick="window.parent.close()">
</form></center></body></html>
<?php
}

function bottom_appt()
{
	$data = $_REQUEST;
	$idata = mx_get_install_data();

	$ptID = $data["patient_ID"];
	$ptName = $data["patient_Name"];
	$drName = $data["modality_Name"];
	$atDate = $data["apptdate"];
	$atTime = $data["appttime"];
	list($h, $m) = explode(':', $data["appttime"]);
	$m += $data["apptdur"];
	$h += ($m / 60)%24;
	$m = $m%60;
	$endTime = sprintf("%02d:%02d", $h, $m);
	$hAddr = $idata['HOSPITAL_ADDR'];
	$hName = $idata['HOSPITAL_NAME'];
	$hPhone = $idata['HOSPITAL_TEL'];

	$drLabel = $data["modality_Type"];

	mx_html_head(NULL);
print <<<HTML
<style type="text/css">
	*,body {
		background-color: #fff;
		background-image: none;
	}
	div.head, div.body, div.name, div.drname,
	div.note, div.apdate, div.tail {
		margin-top: 24px;
		margin-left: 24px;
		margin-right: 24px;
		padding: 12px;
	}
	div.head {
		font-size: 36px;
		text-align: center;
		line-height: 48px;
		text-decoration: underline;
	}
	div.body {
		font-size: 16px;
	}
	div.name {
		font-size: 18px;
	}
	div.drname {
		font-size: 18px;
		text-align: right;
	}
	div.apdate {
		font-size: 18px;
		text-align: center;
	}
	div.note {
		border-width: 1px;
		border-style: solid;
		border-color: #000;
	}
	div.hname {
		font-size: 18px;
	}
	div.haddr, div.hphone {
		font-size: 12px;
	}
</style>
<div class="head">予約票</div>
<div class="body">
<div class="name">$ptName 様</div>
<div class="drname">$drLabel: $drName</div>
<div class="apdate">予約日時: $atDate $atTime から $endTime</div>
<div class="note">
<p>診療予約の注意事項</p>
<ol>
<li>予約されている時間までに本票を診療窓口にご提出ください</li>
<li>急患の方の診察、検査等の都合により診察時間が遅れることや順番が前後することがありますので御承知ください</li>
<li>担当医は都合により変更となる場合があります</li>
<li>容態がすぐれない場合は診療窓口にお申し出ください</li>
</ol>
</div>
<div class="note">
<div class="hname">$hName</div>
<div class="haddr">$hAddr</div>
<div class="hphone">$hPhone</div>
</div>
</div>
</html>
HTML;
}

if (array_key_exists('what', $_REQUEST)) {
	switch ($_REQUEST['what']) {
	case 'top':
		top_frame();
		break;
	case 'bottom':
		bottom_appt();
		break;
	}
}
else {
	main_frameset();
}
?>
