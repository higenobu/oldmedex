<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/pharmacy/csl.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';

function main() {
  $u = mx_authenticate_user();
  $auth = mx_authorization();
  if (! $auth[0])
    return mx_authorization_error($auth);

  mx_html_head($auth[1]);
  print '<body>';
  mx_titlespan($auth[1], 'appname');
  print "<a href=\"../../index.php\"><img src=\"/images/top_button.png\" align=\"absbottom\"></a>\n";
  mx_draw_userinfo($auth);

  print '<br />';

  print '<form method="POST">';
  $what = '����';
  $pcols = array("��̾��",
		 "�쥻�ץ��Ż����������ƥ������̾",
		 "����ñ��",
		 "��������",
		 "��¤���",
		 "������",
		 );
  $cfg = _lib_u_pharmacy_csl_config();
  $cfg['LimitTo'] = $what;
  $cfg['LIST_IDS'] = $pcols;

  $dp = new list_of_controlled_substances('csl-', $cfg);
  if (array_key_exists('reset', $_REQUEST))
    $dp->reset(NULL);

  if ($dp->chosen()) {
    $k = mx_form_unescape_key($dp->chosen());
    print '���򤵤줿' . $what . '�Ϥ���Ǥ���';
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
    mx_formi_submit('reset', '�Ϥᤫ��⤦����');
  }
  else {
    $dp->draw();
  }

  print '</form>';
  print "</body></html>\n";
}

main();
?>
