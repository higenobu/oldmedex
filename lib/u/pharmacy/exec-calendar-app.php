<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ppa.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/pdf.php';
//change 0713-2012
function __x($a, $b) {
  if ($a['sort'] == $b['sort'])
    return 0;
  return ($a['sort'] < $b['sort'] ? -1 : 1);
}

class pharmacy_exec_calendar { // extends nothing
  var $use_printer = 1;
  function pharmacy_exec_calendar($prefix='exec-calendar-') {
    $this->prefix = $prefix;
    $this->selection = NULL;
    if(array_key_exists($this->prefix . 'set-target-month', $_REQUEST) ||
       array_key_exists($this->prefix . 'target-month', $_REQUEST)) {
      $tm = $_REQUEST[$this->prefix . 'target-month'];
      if(array_key_exists($this->prefix . 'set-target-month', $_REQUEST))
	$tm = $_REQUEST[$this->prefix . 'set-target-month'];
      list($this->year, $this->month) = explode('-', $tm);
    }
    else {
      $this->year = date("Y");
      $this->month = date("m");
    }
  }

  function sort_by_effic($data) {
    if(!is_array($data))
      return $data;
    /*
     A: 117, 123, 11X, 12X
    */
    $pfx = array('117' => 'A',
	       '123' => 'B',
	       '11' => 'C',
	       '12' => 'D');

    $output = NULL;
    foreach($pfx as $k => $v) {
      foreach($data as $oid => $row) {
	if (is_null($data[$oid]['sort']) and
	    substr($row['effic'], 0, strlen($k)) == $k)
	  $data[$oid]['sort'] = $v . $row['effic'];
      }
    }
    foreach($data as $oid => $row) {
      if (is_null($row['sort']))
	$data[$oid]['sort'] = 'X' . $row['effic'];
    }

    /* for debugging
    foreach($data as $oid => $row) {
      print $data[$oid]['sort'] .' '. $row['effic']."<br>";
    }
    */

    usort($data, '__x');
    return $data;
  }

  function n_month($n) {
    $year = date("Y", mktime(0, 0, 0, $this->month + $n , 1, $this->year));
    $month = date("m", mktime(0, 0, 0, $this->month + $n, 1, $this->year));
    return array($year, $month);
  }
  
  function next_month() {
    return $this->n_month(+1);
  }

  function prev_month() {
    return $this->n_month(-1);
  }

  function fetch_data() {

    /*  fetch pharma orders 
      |target month|
  s--------e
              s-------e
        s--------e
  s-------------------e
    */
    
    if(!$this->year or !$this->month)
      return;
    $db = mx_db_connect();
    $start_date = sprintf("%s-%s-01", $this->year, $this->month);
    $nm = $this->next_month();
    $start_date2 = sprintf("%s-%s-01", $nm[0], $nm[1]);
//0713-2012 change satartdate
    $stmt = <<<SQL
SELECT M."ObjectID", M."レセプト電算処理システム医薬品名" as name,
      M."病院使用医薬品名" as name2,
      M."薬価基準収載医薬品コード" as effic,
      O."startdate" as start_date,
      O."停止日" as stop_date,
      OD."用量" as amount,
      OD."日数" as days_times,
      OD."区分" as type,
      Y."用法" as direction,
      Y."頓服" as as_needed
FROM "薬剤処方箋" O
      JOIN "薬剤処方箋内容" OD
      ON (O."ObjectID" = OD."薬剤処方箋" AND O."Superseded" IS NULL)
      JOIN "Medis医薬品マスター" M
      ON (M."ObjectID" = OD."薬剤" AND M."Superseded" IS NULL)
      LEFT JOIN "処方箋用法" Y
      ON (Y."ObjectID" = OD."用法" AND Y."Superseded" IS NULL)
      WHERE 
      ((O."startdate" >= '$start_date' AND O."startdate" < '$start_date2') OR
      (O."startdate" + OD."日数" >= '$start_date' AND
       O."startdate" + OD."日数" <  '$start_date2') OR

      (O."startdate" < '$start_date' AND O."startdate" > '$start_date2'))
SQL;
    if($this->application->patient_ObjectID)
      $stmt .= sprintf(' AND "患者"=%d', $this->application->patient_ObjectID);
    $stmt .= ' ORDER BY O."ObjectID", OD."ObjectID" ';
    $rows = mx_db_fetch_all($db, $stmt);
    $type = NULL;
    $days_times = NULL;
    $direction = NULL;
    for($i=count($rows)-1; $i >=0; $i--) {
      if (is_null($days_times)) {
	$days_times = $rows[$i]['days_times'];
	$type = $rows[$i]['type'];
	$as_needed = $rows[$i]['as_needed'];
	$direction = $rows[$i]['direction'];
	continue;
      }
      # basic rule: copy from previous row
      if (is_null($rows[$i]['days_times'])) {
	$rows[$i]['days_times'] = $days_times;
	$rows[$i]['type'] = $type;
	$rows[$i]['as_needed'] = $as_needed;
	$rows[$i]['direction'] = $direction;
	continue;
      }
      $days_times = $rows[$i]['days_times'];
      $type = $rows[$i]['type'];
      $as_needed = $rows[$i]['as_needed'];
      $direction = $rows[$i]['direction'];
    }
    return $rows;
  }

  function changed() {
    return 1;
  }

  function chosen() {
    return $this->selection;
  }

  function draw_calendar($da) {
    $ndays = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    $colspan1 = $ndays + 2;
    // top row styles
    $s1 = ' style="border-top: 1pt solid #000000; border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;width: 360"';
    $s2 = ' style="border-top: 1pt solid #000000; border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; background-color: ff0; width: 15; text-align: center"';
    $s3 = ' style="border-top: 1pt solid #000000; border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; background-color: ff0"';

    // middle row styles
    $ms1 = ' style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;width: 360; background-color: fff"';
    $ms2 = ' style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; background-color: fff; width: 15; text-align: center"';
    $ms3 = ' style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; background-color: fff"';


    print <<<HTML
      <tr><td ${s1}>処方内容</td>
HTML;
    for($i = 1; $i <= $ndays+1; $i++) {
      if($i == $ndays+1)
	print "<td ${s3}>計</td>";
      else
	print "<td ${s2}>$i</td>";
    }
    print "</tr>";
    //-------- print detail
    if(!$da) {
      print "<tr style=\"text-align: center\"><td colspan=\"$colspan1\">当月の投薬はありません</td></tr>";
      return;
    }
    $da = $this->sort_by_effic($da);
    foreach($da as $oid => $med) {
      $name = $med['name'];
      $effic = $med['effic'];
      $dir = $med['direction'];
      
      print "<tr><td ${ms1}>${name}<br>${dir}</td>";
      for($i=1; $i <= $ndays; $i++) {
	$txt = $med['daily_amount'][$i];
	$txt = is_null($txt) ? '&nbsp;' : $txt;
	print "<td ${ms2}>${txt}</td>";
	if($i == $ndays) {
	  $total = sprintf("%d", $med['total']);
	  print "<td ${ms3}>${total}</td>";
	}
      }
      print "</tr>";
    }
  }

  function calc_daily_value($data) {
    if(!$data)
      return;

    $ndays = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    $output = NULL;
    foreach($data as $med)
      $output[$med['ObjectID']] = array('name' => $med['name'],
					'direction' => $med['direction'],
					'effic' => $med['effic'],
					'daily_amount' => array());

    foreach($output as $oid => $v) {
      foreach($data as $med) {
	if ($med['ObjectID'] != $oid)
	  continue;

	$daily_amount = $med['amount'];
	$days = $med['days_times'];
	$as_needed = $med['as_needed'];

	list($y, $m, $d) = explode('-', $med['start_date']);
	$start_date = mktime(0, 0, 0, $m, $d, $y);
	$stop_date = NULL;
	if($med['stop_date']) {
	  list($y, $m, $d) = explode('-', $med['stop_date']);
	  $stop_date = mktime(0, 0, 0, $m, $d, $y);
	}

	// tonpuku
	if ($as_needed) {
	  $output[$oid]['daily_amount'] = $daily_amount * $days;
	  $output[$oid]['total'] += $daily_amount * $days;
	  continue;
	}

	// regular
	for($i=1; $i <= $ndays; $i++) {
	  $current_date = mktime(0, 0, 0, $this->month, $i, $this->year);
	  if($stop_date && $current_date > $stop_date)
	    ;
	  else if($current_date == $stop_date)
	    ;
	  else if($current_date >= $start_date &&
		  $current_date <= $start_date + ($days-1) * 86400){
	    $output[$oid]['daily_amount'][$i] += $daily_amount;
	    $output[$oid]['total'] += $daily_amount;
	  }
	}
      }
    }
    return $output;
  }

  function draw() {
    if(!$this->year or !$this->month) {
      print "対象月が指定されていません";
      return;
    }

    $this->data = $this->fetch_data();
    $daily_amounts = $this->calc_daily_value($this->data);

    $prev = $this->prev_month();
    $next = $this->next_month();
    $ptid = $this->application->patient_ID;
    $ptnm = $this->application->patient_Name;
    $ptkn = $this->application->patient_Kana;
    $year = $this->year;
    $month = $this->month;
    $ndays = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    $colspan1 = $ndays + 2;
    $colspan2 = $colspan1 - 8;
    $colspan3 = $colspan1 - 15 - 3;
print <<<HTML
<TABLE cellspacing="0">
  <TBODY>
    <TR>
      <TD colspan="${colspan1}" style="text-align:center"><B><FONT size="+2">投薬記録簿</FONT></B>&nbsp</TD>
    </TR>
    <TR>
      <TD>ID:${ptid}</TD>
    </TR>
    <TR>
        <TD colspan="${colspan2}">${ptnm}（${ptkn}）</TD>
	<TD colspan="4" align="right">${year}年</TD>
	<TD colspan="4" align="right">${month}月分</TD>
    </TR>
HTML;

    $daily_amounts = $this->sort_by_effic($daily_amounts);
    $this->draw_calendar($daily_amounts);
    print '<TR><TD colspan="15">';
    mx_formi_submit($this->prefix . 'set-target-month',sprintf("%s-%s", $prev[0], $prev[1]), "前月");
    print "</TD><TD colspan=3>&nbsp;</TD><TD colspan=$colspan3>";
    mx_formi_submit($this->prefix . 'set-target-month',sprintf("%s-%s", $next[0], $next[1]), "次月");
    mx_formi_hidden($this->prefix . 'target-month', sprintf("%s-%s", $this->year, $this->month));
    print "</TD></TR></TBODY></TABLE>";
    print "<br>";

   if($_REQUEST['Print']) 
	go_rx_control_pdf($daily_amounts, $this->year, $this->month,
			  $ptid, NULL);
  }

  function lost_selection() {
    return 0;
  }

  function reset() {
  }
}


class pharmacy_exec_calendar_application extends per_patient_application {
  var $use_single_pane = 1;

  function pharmacy_exec_calendar_application() {
    per_patient_application::per_patient_application();
  }
  
  function list_of_objects($prefix, &$it) {
    $loo = new pharmacy_exec_calendar();
    $loo->application = &$this;
    return $loo;
  }

  function allow_new() {
    return NULL;
  }

}

?>
