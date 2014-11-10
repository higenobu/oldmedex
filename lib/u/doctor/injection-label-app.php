<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/doctor/reorder-app.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/tcpdf_php4/config/lang/eng.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/tcpdf_php4/tcpdf.php';

class injection_label_application extends reorder_application {

	var $application_title = "注射ラベル";
	var $app_ix_name = 'reorder';
	var $default_all_select = 1;
	var $fixed_order_type = NULL;
	var $use_choose_patients = 0;

	function injection_label_application() {
		$this->fixed_order_type = array('injection');
		reorder_application::reorder_application();
	}

	function draw_control_on_order_list() {
		foreach ($this->patients_passthru as $passthru) {
			mx_formi_hidden($passthru, 1);
		}
		mx_formi_submit('PrintOrders', "印刷");
	}

	function injection_info($med) {
		$name = trim($med['レセプト電算処理システム医薬品名']);
		$xage = array();
		if ($med['用法'])
			$xage[] = $med['用法'];
		if ($med['注射用法'])
			$xage[] = $med['注射用法'];
		if ($med['手技'])
			$xage[] = $med['手技'];
		$usage = implode("・", $xage);
		return array('drugname' => $name,
			     'usage' => $usage,
			     'days' => $med['日数']);
	}

	function fmt_one_med($med) {
		$info = $this->injection_info($med);
		$name = $info['drugname'];
		$xage = array();
		if (trim($info['usage']) != '')
			$xage[] = trim($info['usage']);
		if (trim($info['days']) != '')
			$xage[] = trim($info['days']) . '日分';
		$usage = implode("・", $xage);
		return "$name<br />$usage";
	}

	function draw_one_med($order_id, $order, $med) {
		$oid = $med['medid'];
		$control = "ILID-$order_id-$oid";
		mx_formi_checkbox($control, 1); /* default selected? */
		print $this->fmt_one_med($med);
	}

	function draw_one_order($o) {
		$order_id = $o['object_id'];

		print "<td>";
		print htmlspecialchars($o['timestamp']);
		print "<br />";
		print htmlspecialchars($o['text']);
		print "</td><td>";

		$hr = '';
		foreach ($o['OrderPieces'] as $med) {
			print "$hr";
			$this->draw_one_med($order_id, $o, $med);
			$hr = "<br /><hr />"; 
		}
	}

	function options_to_collect() {
		$option = reorder_application::options_to_collect();
		$option['ReturnOrderPieces'] = 1;
		$option['CheckLabelPrint'] = 1;
		return $option;
	}

	function setup_application_widgets () {
		if (mx_check_request('PrintOrders')) {
			$this->setup_print();
		} else {
			reorder_application::setup_application_widgets();
		}
	}

	function setup_print() {
		$patients = array();
		$patient_map = array();
		$ordids = array();
		$ordpieces = array();
		foreach ($_REQUEST as $k => $v) {
			$m = array();
			if (preg_match('/^RAPT-([0-9]+)-([^-]*)-(.+)$/',
				       $k, &$m)) {
				$o = $m[1];
				$i = mx_form_decode_name($m[2]);
				$n = mx_form_decode_name($m[3]);
				$patients[] = array($o, $i);
				$patient_map[$o] = $n;
				continue;
			}

			$m = array();
			if (preg_match('/^RAREDO-([0-9]+)-(.+)$/',
					$k, &$m)) {
				$oid = $m[1];
				$app = mx_form_decode_name($m[2]);
				if ($app != 'injection_module_index_info')
					continue;
				$ordids[$oid] = 1;
			} else if (preg_match('/^ILID-([0-9]+)-([0-9]+)$/',
					      $k, &$m)) {
				$oid = $m[1];
				$opid = $m[2];
				if (!array_key_exists($oid, $ordpieces))
					$ordpieces[$oid] = array();
				$ordpieces[$oid][$opid] = 1;
			}
		}
		$prints = array();
		foreach ($ordpieces as $k => $v) {
			if (!array_key_exists($k, $ordids))
				continue;
			$prints[$k] = $v;
		}
		$this->prints = $prints;
		$this->patients = $patients;
		$this->patient_map = $patient_map;

		if (0) {
			$this->action = 'draw_print';
		} else {
			$this->action = 'draw_nothing';
			$this->direct_print();
			exit(0); /* nothing else left to do */
		}
	}

	function draw_nothing() {
		return;
	}

	function date_to_dow($date) {
		global $_mx_daysoftheweek;

		$a = mx_timestamp_parse($date);
		return $_mx_daysoftheweek[($a['tm_wday'] + 6) % 7];
	}

	function grab_print_info() {
		$retval = array();
		$options = $this->options_to_collect();
		$result = index_pt_collect($this->patients, NULL, NULL,
					   $this->app_ix_name,
					   $options);
		foreach ($result as $o) {
			$oid = $o['object_id'];
			if (!array_key_exists($oid, $this->prints))
				continue;
			$print = $this->prints[$oid];
			$p = $o['患者ObjectID'];
			$i = $o['患者ID'];
			$r = $o['病室名'];
			$n = $this->patient_map[$p];
			$d = $o['execdate'];
			$t = $o['timestamp'];
			$dr = $o['処方医姓'] . ' ' . $o['処方医名'];
			$dpt = $o['科目名'];
			foreach ($o['OrderPieces'] as $med) {
				$opid = $med['medid'];
				if (!array_key_exists($opid, $print))
					continue;
				$info = $this->injection_info($med);
				$info['orderid'] = $oid;
				$info['patientid'] = $i;
				$info['patientname'] = $n;
				$info['patientroom'] = $r;
				$info['execdate'] = $d;
				$info['execdate_dow'] =
					$this->date_to_dow($d);
				$info['timestamp'] = $t;
				$info['timestamp_dow'] =
					$this->date_to_dow($t);
				$info['orderedby'] = $dr;
				$info['department'] = $dpt;
				$retval[] = $info;
			}
		}
		return $retval;
	}

	function expand_print_info($info) {
		$retval = array();
		foreach ($info as $in) {
			$d = $in['execdate'];
			$days = $in['days'];
			for ($i = 0; $i < $days; $i++) {
				$d = mx_offset_day($in['execdate'], $i);
				$dow = $this->date_to_dow($d);
				$it = array
					('patientid' => $in['patientid'],
					 'patientname' => $in['patientname'],
					 'patientroom' => $in['patientroom'],
					 'orderdate' => $in['timestamp'],
					 'orderdate_dow' => $in['timestamp_dow'],
					 'begindate' => $in['execdate'],
					 'begindate_dow' => $in['execdate_dow'],
					 'execdate' => $d,
					 'execdate_dow' => $dow,
					 'drugname' => $in['drugname'],
					 'usage' => $in['usage'],
					 'count' => $i + 1,
					 'days' => $in['days'],
					 'orderedby' => $in['orderedby'],
					 'department' => $in['department'],
					 'orderid' => $in['orderid'],
					 );
				$retval[] = $it;
			}
		}
		return $retval;
	}

	function draw_print() {
		$info = $this->grab_print_info();
		$info = $this->expand_print_info($info);
		foreach ($info as $in) {
			$oid = $in['orderid'];
			$i = $in['patientid'];
			$n = $in['patientname'];
			$r = $in['patientroom'];
			if ($r != '')
				$r = "(病室: $r)";
			$c = $in['count'];
			$name = $in['drugname'];

			$xage = array();
			if (trim($in['usage']) != '')
				$xage[] = trim($in['usage']);
			if (trim($in['days']) != '') {
				$days = trim($in['days']);
				$xage[] = trim($days) . '日分';
			} else {
				$days = 1;
			}
			$b = sprintf("%s (%s)",
				     $in['begindate'], $in['begindate_dow']);
			$d = sprintf("%s (%s)",
				     $in['execdate'], $in['execdate_dow']);
			$o = sprintf("%s (%s)",
				     $in['orderdate'], $in['orderdate_dow']);
			$dr = $in['orderedby'];
			$dpt =  $in['department'];
			$usage = implode("・", $xage);

			print "$hr";
			print "処方箋ID $oid<br />\n";
			print "$i $n$r<br />\n";
			print "$name<br />\n";
			print "処方日 $o<br />\n";
			print "処方医 $dr<br />\n";
			print "科目 $dpt<br />\n";
			print "$b 開始<br />\n";
			print "$usage<br />\n";
			print "$d 使用分($c/$days)<br />\n";
			$hr = "<hr />\n";
		}
	}

	function prepare_pdf() {
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION,
				 PDF_UNIT,
				 PDF_PAGE_FORMAT,
				 true, 'UTF-8', false);

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetFont('arialunicid0', '', 10);
		$this->pdf = &$pdf;
		$this->cell_count = 0;
		$this->pdf->AddPage();
	}

	function draw_cell_pdf($in) {

		$origin_x = 20;
		$origin_y = 20;
		$width = 86.4;
		$height = 50.8;

		$cnt = $this->cell_count;
		if (10 <= $cnt) {
			$this->pdf->AddPage();
			$cnt = 0;
		}
		$this->cell_count = $cnt + 1;

		$in['room'] = '';
		if ($in['patientroom'] != '')
			$in['room'] = sprintf("病室 %s ",
					      $in['patientroom']);
		$in['oid'] = sprintf("処方箋ID: %s", $in['orderid']);
		$in['ord'] = sprintf(" (処方日 %s)", $in['orderdate']);
		$in['exec'] = sprintf("%s (%s) 使用分",
				      $in['execdate'],$in['execdate_dow']);
		$in['dr'] = sprintf("処方医: %s", $in['orderedby']);
		if ($in['department'] != '')
			$in['dr'] .= sprintf(" (%s)", $in['department']);
		$in['seq'] = sprintf("(%d/%d)",
				     $in['count'], $in['days']);
		mb_convert_variables('utf-8', 'auto', $in);

		$x = $origin_x + (($cnt % 2) ? $width : 0);
		$y = $origin_y + (floor($cnt / 2) * $height);

		$pdf = &$this->pdf;

		$pdf->SetDrawColor(208);
		$pdf->Line($x, $y, $x + $width, $y);
		$pdf->Line($x, $y + $height, $x + $width, $y + $height);
		$pdf->Line($x, $y, $x, $y + $height);
		$pdf->Line($x + $width, $y, $x + $width, $y + $height);
		$pdf->SetDrawColor(0);

		$pdf->SetFont('arialunicid0', '', 8);
		$pdf->Text($x + 5,                 $y+8, $in['oid']);

		$pdf->SetFont('arialunicid0', '', 12);
		$str = ($in['room'] . trim($in['patientid']) . ' ' .
			$in['patientname']);
		$w = $pdf->GetStringWidth($str);
		$pdf->Text($x + ($width - 5 - $w),     $y+8, $str);

		foreach (array(14,12,10,8) as $size) {
			$pdf->SetFont('arialunicid0', '', $size);
			$w = $pdf->GetStringWidth($in['drugname']);
			if ($w <= $width - 10)
				break;
		}
		$pdf->Text($x + ($width - $w) / 2, $y+20, $in['drugname']);

		$pdf->SetFont('arialunicid0', '', 8);
		$str = $in['usage'] . ' ' . $in['ord'];
		$w = $pdf->GetStringWidth($str);
		$pdf->Text($x + ($width - $w) / 2, $y+25, $str);

		$pdf->SetFont('arialunicid0', '', 8);
		$w = $pdf->GetStringWidth($in['dr']);
		$pdf->Text($x + ($width - $w) / 2, $y+30, $in['dr']);

		$pdf->SetFont('arialunicid0', '', 12);
		$w = $pdf->GetStringWidth($in['exec']);
		$pdf->Text($x + ($width - $w) / 2, $y+38, $in['exec']);

		$pdf->SetFont('arialunicid0', '', 12);
		$w = $pdf->GetStringWidth($in['seq']);
		$pdf->Text($x + ($width - $w) / 2, $y+45, $in['seq']);
	}

	function finish_pdf() {
		$this->pdf->Output('injection.pdf', 'I');
	}

	function direct_print() {

		$info = $this->grab_print_info();
		$info = $this->expand_print_info($info);

		$this->prepare_pdf();
		foreach ($info as $in)
			$this->draw_cell_pdf($in);
		$this->finish_pdf();

	}

}
?>
