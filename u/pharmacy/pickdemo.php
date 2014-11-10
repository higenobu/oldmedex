<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/drugpick.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

function main() {
  $u = mx_authenticate_user();
  $auth = mx_authorization();
  if (! $auth[0])
    return mx_authorization_error($auth);

  $pcols = array(// "告示名称",
		 "レセプト電算処理システム医薬品名",
		 "包装単位",
		 "包装総量",
		 "製造会社",
		 "販売会社",
		 );

  mx_html_head($auth[1]);
  print '<body>';
  mx_titlespan($auth[1]), 'appname';
  print "<a href=\"../../index.php\"><img src=\"/images/top_button.png\" align=\"absbottom\"></a>\n";
  mx_draw_userinfo($auth);

  print '<br />';

  print '<form method="POST">';
  $dp = new drugpick('dp-',
		     array('LIST_IDS' => $pcols, 'SKIP_CATEGORY' => 1));
  if (array_key_exists('reset', $_REQUEST))
    $dp->reset(NULL);

  if ($dp->chosen()) {
    $k = mx_form_unescape_key($dp->chosen());
    print '選択された薬剤はこれですね';
    print '<table class="listofstuff">';
    $cnt = count($pcols);
    for ($ix = 0; $ix < $cnt; $ix++) {
      print '<tr><th>';
      print htmlspecialchars($pcols[$ix]);
      print '</th><td>';
      print htmlspecialchars($k[$ix]);
      print '</td></tr>';
    }
    print '</table>';
    print "<a href=\"pickdemo.php\">始めからもう一度</a>\n";
  }
  else {
    $dp->draw();
  }

  print '</form>';
  print "</body></html>\n";
}

main();
?>
