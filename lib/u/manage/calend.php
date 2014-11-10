<?php // -*- mode: php; coding: euc-japan -*-

$_lib_u_manage_calend_ = "";

function _lib_u_manage_calend_prepare_config(&$config) {
  $today = localtime(time(), 1);
  if (! array_key_exists('Month', $config))
    $config['Month'] = $today['tm_mon'] + 1;
  if (! array_key_exists('Year', $config))
    $config['Year'] = $today['tm_year'] + 1900;
  if (! array_key_exists('Prev', $config))
    $config['Prev'] = '前の月へ';
  if (! array_key_exists('Next', $config))
    $config['Next'] = '次の月へ';
  if (! array_key_exists('Navigation', $config))
    $config['Navigation'] = 1;
  if (! array_key_exists('ShowTitle', $config))
    $config['ShowTitle'] = 1;
  if (! array_key_exists('ShowMe', $config))
    $config['ShowMe'] = 'カレンダ表示';
  if (! array_key_exists('HideMe', $config))
    $config['HideMe'] = 'カレンダをかくす';
  if (! array_key_exists('ShowHide', $config))
    $config['ShowHide'] = 1;
  if ($config['Navigation'])
    $config['ShowTitle'] = 1;
}

class month_calendar_display {
  var $dow = array('日','月','火','水','木','金','土');
  var $showing = 1;

  function month_calendar_display($prefix, $config=NULL) {
    $this->prefix = $prefix;
    $this->config = $config;
    _lib_u_manage_calend_prepare_config(&$this->config);

    $this->_changed = NULL;
    // Moving around...
    if (array_key_exists($prefix . 'Month', $_REQUEST)) {
      $this->_changed = 1;
      $this->month = $_REQUEST[$prefix . 'Month'];
      $this->year = $_REQUEST[$prefix . 'Year'];
      if (array_key_exists($prefix . 'Move', $_REQUEST)) {
	if ($_REQUEST[$prefix . 'Move'] == 'prev') {
	  if (--$this->month < 1) {
	    $this->year--; $this->month = 12;
	  }
	}
	else {
	  if (12 < ++$this->month) {
	    $this->year++; $this->month = 1;
	  }
	}
      }
    } else {
      $this->month = $this->config['Month'];
      $this->year = $this->config['Year'];
    }
    // Showing and hiding...
    
    if (array_key_exists($prefix . 'ShowMe', $_REQUEST))
	    $this->showing = $_REQUEST[$prefix . 'ShowMe'];
    else if (array_key_exists($prefix . 'ShowHide', $_REQUEST))
	    $this->showing = $_REQUEST[$prefix . 'ShowHide'];

  }

  function changed() {
    return $this->_changed;
  }

  function reset($year, $month) {
    $this->year = $year;
    $this->month = $month;
  }

  function draw_day($year, $month, $mday) { // override
    print $mday;
  }

  function draw() {
    // Ugh.  PHP mktime takes 4-digit year as is.  Also tm_mon counts from one.
    $bom = mktime(0, 0, 0, $this->month, 1, $this->year);
    $eom = mktime(0, 0, 0, $this->month + 1, 0, $this->year);
    $beginning_of_the_month = localtime($bom, 1);
    $end_of_the_month = localtime($eom, 1);
    $mday = 0 - $beginning_of_the_month['tm_wday'];
    print '<table class="calendar">';
    if ($this->config['ShowTitle']) {
      print '<tr><td colspan="7">';
      if ($this->showing) {
	if ($this->config['Navigation'])
	  mx_formi_submit($this->prefix . 'Move', 'prev',
			  mx_img_url('history-prev.png'),
			  $this->config['Prev']);
	print $this->year . ' 年 ' . $this->month . ' 月 ';
	if ($this->config['Navigation'])
	  mx_formi_submit($this->prefix . 'Move', 'next',
			  mx_img_url('history-next.png'),
			  $this->config['Next']);

	if ($this->config['ShowHide']) {
		mx_formi_submit($this->prefix . 'ShowMe', '0',
				mx_img_url('hide-calendar.png'),
				$this->config['HideMe']);
	}
      }
      else if ($this->config['ShowHide']) {
        mx_formi_submit($this->prefix . 'ShowMe', '1',
			mx_img_url('show-calendar.png'),
			$this->config['ShowMe']);
      }
      print "</td></tr>";
    }
    if ($this->showing) {
      print "<tr>";
      for ($wday = 0; $wday < count($this->dow); $wday++) {
	print "<td>" . $this->dow[$wday] . "</td>";
      }
      print "</tr>\n";
      $wday = 0;
      while ($wday || $mday < $end_of_the_month['tm_mday']) {
	if ($wday == 0) print "<tr>";
	print "<td>";
	if ($mday < 0 || $end_of_the_month['tm_mday'] <= $mday)
	  print "&nbsp;";
	else {
	  $this->draw_day($this->year, $this->month, $mday + 1);
	}
	print "</td>";
	$mday++;
	if (count($this->dow) <= ++$wday) {
	  print "</tr>\n";
	  $wday = 0;
	}
      }
    }
    print "</table>\n";
    mx_formi_hidden($this->prefix . 'Year', $this->year);
    mx_formi_hidden($this->prefix . 'Month', $this->month);
    mx_formi_hidden($this->prefix . 'ShowHide', $this->showing);
  }
}

class simple_clickable_month_calendar_display extends month_calendar_display {
  function simple_clickable_month_calendar_display($prefix, $config=NULL) {
    month_calendar_display::month_calendar_display($prefix, $config);

    $this->changed = 0;
    if (array_key_exists($this->prefix . 'DayClick', $_REQUEST)) {
      $this->chosen = array($this->year,
			    $this->month,
			    $_REQUEST[$this->prefix . 'DayClick']);
      $this->changed = 1;
      if ($this->config['ShowHide'])
        $this->showing = 0;
    }
    elseif (array_key_exists($this->prefix . 'StickyDate', $_REQUEST))
      $this->chosen = explode('-', $_REQUEST[$this->prefix . 'StickyDate']);
    else
      $this->chosen = NULL;
  }

  function reset($year, $month, $setdate=NULL) {
    if ($setdate) {
      $match = array();
      if (preg_match('/^(\d+)-(\d+)-(\d+)$/', $setdate, &$match)) {
	$this->year = $match[1];
	$this->month = $match[2];
	array_shift($match);
	$this->chosen = $match;
	$this->changed = 1;
      }
    } else {
      month_calendar_display::reset($year, $month);
      $this->chosen = NULL;
    }
  }

  function chosen() {
    return $this->chosen;
  }

  function changed() {
    return $this->changed;
  }

  function draw_day($year, $month, $mday) {
    mx_formi_submit($this->prefix . 'DayClick', $mday,
		    "<span class=\"link\">$mday</span>");
  }

  function draw() {
    month_calendar_display::draw();
    if ($this->chosen)
      mx_formi_hidden($this->prefix . 'StickyDate',
		      implode('-', $this->chosen));
  }
}

?>
