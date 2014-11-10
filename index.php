<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';


$u = mx_authenticate_user('do-not-redirect');
if (!$u) {
	return mx_http_redirect('/login.php');
}

global $_mx_default_app;
if ($_mx_default_app != '/index.php') {
	$app = substr($_mx_default_app, 1);
	return mx_http_redirect($app);
}

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/boilerplates/userinfo.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/index-tmpl.php';

$auth = mx_authorization();

$apps = mx_find_application($u);
$mapps = mx_find_application($u, 'M');
if (count($mapps) == 0) {
	$apps['M'] = array();
}
?>
<?php draw_application_map($apps, $auth); ?>

<?php

function draw_applink($ix, &$apps, $omit_empty=NULL)
{
	global $__lib_u_manage_app_auth__applink_names;
	$name = $__lib_u_manage_app_auth__applink_names[$ix];
	$non_empty = (is_array($apps[$ix]) && count($apps[$ix]));
	if (is_null($omit_empty) || $non_empty) {
		if (! $non_empty && $omit_empty != '')
			print $omit_empty;
		print "<span class=\"title\">$ix. " .
			htmlspecialchars($name) . "</span>";
		print "<br />\n";
		if (is_array($apps[$ix])) {
			# If there are duplicates in names,
			# we need to disambiguate them.
			$name_count = array();
			foreach ($apps[$ix] as $d) {
				$n = $d['name'];
				if (!array_key_exists($n, $name_count))
					$name_count[$n] = 0;
				$name_count[$n]++;
			}
			foreach ($apps[$ix] as $d) {
				$n = $d['name'];
				if (1 < $name_count[$n] && $d['disamb'])
					$n = ($d['abbrev'] . "(" .
					      $d['disamb'] . ")");
				print "<a href=\"" . $d['path'] . "\">";
				print htmlspecialchars($n);
				print "</a><br />\n";
			}
		}
	}
}

function draw_application_map(&$apps, $auth)
{
  global $_mx_resource_dir;
  mx_html_head("アプリケーション・マップ", 'do-not-close-head');
?>
<link rel="stylesheet" href="/<? print $_mx_resource_dir ?>/mxmap.css">
</head>
<body>
<?php draw_application_map_tmpl($apps, $auth); ?>
</body>


</html>
<?php
}
?>
